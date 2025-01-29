CREATE OR REPLACE FUNCTION public.push_service_tire_notifications()
    RETURNS void
    LANGUAGE plpgsql
AS $function$
BEGIN
    WITH kms_traveled AS (
        SELECT
            vth.vehicle_id,
            vth.tire_location,
            FIRST_VALUE(vth.odometer) OVER (
                PARTITION BY vth.vehicle_id, vth.tire_location 
                ORDER BY vth.service_date ASC
            ) AS first_odometer,
            LAST_VALUE(vth.odometer) OVER (
                PARTITION BY vth.vehicle_id, vth.tire_location 
                ORDER BY vth.service_date ASC
            ) AS last_odometer,
            vth.service_date,
            tp.service_id,
            tp.kms_next,
            tp.date_next,
            (tp.km_proyected / (tp.prom_tire_km_month / 30)) AS projected_days,
            ROW_NUMBER() OVER (
                PARTITION BY vth.vehicle_id 
                ORDER BY vth.service_date DESC
            ) AS row_num
        FROM vehicle_tire_histories vth
        JOIN tire_projection tp 
            ON vth.vehicle_id = tp.vehicle_id 
        AND vth.tire_location = tp.tire_location
        WHERE tp.km_proyected != 0
    ),
    kms_percentage AS (
        SELECT *,
        ROUND(((last_odometer - first_odometer) / (kms_next - first_odometer)) * 100) AS percentage_km_traveled,
        ROUND(DATE_PART('day', now() - service_date) / projected_days * 100) AS percentage_date_next
        FROM kms_traveled kt
        WHERE row_num = 1
    ),
    potential_notifications AS (
        SELECT
            u.id AS user_id,
            n.id AS notification_id,
            v.id AS vehicle_id,
            kp.tire_location,
            'for send' AS status,
            'tire' AS topic_1,
            n.topic_2 AS topic_2,
            CURRENT_date AS created_at,
            current_date AS updated_at
        FROM kms_percentage kp
        JOIN vehicles v ON kp.vehicle_id = v.odoo_id
        JOIN services s ON kp.service_id = s.odoo_id
        JOIN users u ON s.owner_id = u.res_partner_id
        LEFT join notifications n ON n.topic_1 = 'tire'
            AND (
                (kp.percentage_km_traveled >= 75 AND kp.percentage_km_traveled < 90 AND n.topic_2 = '75') OR
                (kp.percentage_km_traveled >= 90 AND kp.percentage_km_traveled < 105 AND n.topic_2 = '90') OR
                (kp.percentage_km_traveled >= 105 AND n.topic_2 = '105') OR
                
                (kp.percentage_date_next >= 75 AND kp.percentage_date_next < 90 AND n.topic_2 = '75') OR
                (kp.percentage_date_next >= 90 AND kp.percentage_date_next < 105 AND n.topic_2 = '90') OR
                (kp.percentage_date_next >= 105 AND n.topic_2 = '105')
            )
        WHERE
            (kp.percentage_km_traveled >= 75) OR
            (kp.percentage_date_next >= 75)
    ),
    filtered_notifications AS (
        SELECT
            pn.user_id,
            pn.notification_id,
            pn.vehicle_id,
            pn.tire_location,
            pn.status,
            pn.topic_1,
            pn.topic_2,
            pn.created_at,
            pn.updated_at
        FROM
            potential_notifications pn
        INNER JOIN (
            SELECT
                vehicle_id,
                MAX(CAST(topic_2 AS INTEGER)) AS max_topic_2
            FROM
                potential_notifications
            GROUP BY
                vehicle_id
        ) sub ON pn.vehicle_id = sub.vehicle_id AND CAST(pn.topic_2 AS INTEGER) = sub.max_topic_2
        WHERE NOT EXISTS (
            SELECT 1
            FROM user_notifications un
            WHERE un.user_id = pn.user_id
            AND un.vehicle_id = pn.vehicle_id
            AND un.topic_1 = pn.topic_1
            AND un.topic_2 < pn.topic_2
        )
        GROUP BY pn.user_id, pn.notification_id, pn.vehicle_id, pn.tire_location, pn.status, pn.topic_1, pn.topic_2, pn.created_at, pn.updated_at
    )
    INSERT INTO user_notifications (user_id, notification_id, vehicle_id, tire_location, status, topic_1, topic_2, created_at, updated_at)
    SELECT
        user_id,
        notification_id,
        vehicle_id,
        tire_location,
        status,
        topic_1,
        topic_2,
        created_at,
        updated_at
    FROM filtered_notifications fn
    WHERE NOT EXISTS (
        SELECT 1
        FROM user_notifications un
        WHERE un.user_id = fn.user_id
        AND un.vehicle_id = fn.vehicle_id
        AND un.topic_1 = fn.topic_1
        AND un.topic_2 = fn.topic_2
    );
END;
$function$