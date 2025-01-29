CREATE OR REPLACE FUNCTION public.push_physical_state_service_tire_notifications()
    RETURNS void
    LANGUAGE plpgsql
AS $function$
BEGIN
    WITH latest_tire_service AS (
        SELECT DISTINCT
            s.vehicle_id,
            s.owner_id,
            st.service_id,
            st.perforations,
            st.deformations,
            st.vulcanized,
            st.aging,
            s.date AS last_service_date
        FROM
            services s
        JOIN service_tires st ON st.service_id = s.odoo_id
        WHERE
            (s.vehicle_id, s.date) IN (
                SELECT
                    s.vehicle_id,
                    MAX(s.date) AS max_date
                FROM
                    services s
                JOIN service_tires st ON st.service_id = s.odoo_id
                GROUP BY
                    s.vehicle_id
            )
    ),
    potential_notifications AS (
        SELECT
            u.id AS user_id,
            n.id AS notification_id,
            v.id AS vehicle_id,
            'for send' AS status,
            'tire' AS topic_1,
            CASE
                WHEN lts.perforations = TRUE AND n.topic_2 = 'perforations' THEN 'perforations'
                WHEN lts.deformations = TRUE AND n.topic_2 = 'deformations' THEN 'deformations'
                WHEN lts.vulcanized = TRUE AND n.topic_2 = 'vulcanized' THEN 'vulcanized'
                WHEN lts.aging = TRUE AND n.topic_2 = 'aging' THEN 'aging'
            END AS topic_2,
            CURRENT_DATE AS created_at,
            CURRENT_DATE AS updated_at
        FROM
            latest_tire_service lts
        JOIN
            users u ON u.res_partner_id = lts.owner_id
        JOIN
            vehicles v ON v.odoo_id = lts.vehicle_id
        LEFT JOIN
            notifications n ON n.topic_1 = 'tire' AND (
                (lts.perforations = TRUE AND n.topic_2 = 'perforations') OR
                (lts.deformations = TRUE AND n.topic_2 = 'deformations') OR
                (lts.vulcanized = TRUE AND n.topic_2 = 'vulcanized') OR
                (lts.aging = TRUE AND n.topic_2 = 'aging')
            )
        WHERE
            lts.perforations = TRUE OR
            lts.deformations = TRUE OR
            lts.vulcanized = TRUE OR
            lts.aging = TRUE
        AND NOT EXISTS (
            SELECT 1
            FROM user_notifications un
            WHERE un.user_id = u.id
            AND un.vehicle_id = v.id
            AND un.topic_1 = 'tire'
            AND un.topic_2 = n.topic_2
        )
    ),
    filtered_notifications AS (
        SELECT DISTINCT
            pn.user_id,
            pn.notification_id,
            pn.vehicle_id,
            pn.status,
            pn.topic_1,
            pn.topic_2,
            pn.created_at,
            pn.updated_at
        FROM
            potential_notifications pn
        WHERE NOT EXISTS (
            SELECT 1
            FROM user_notifications un
            WHERE un.user_id = pn.user_id
            AND un.vehicle_id = pn.vehicle_id
            AND un.topic_1 = pn.topic_1
            AND un.topic_2 = pn.topic_2
        )
    )
    INSERT INTO user_notifications (user_id, notification_id, vehicle_id, status, topic_1, topic_2, created_at, updated_at)
    SELECT
        user_id,
        notification_id,
        vehicle_id,
        status,
        topic_1,
        topic_2,
        created_at,
        updated_at
    FROM
        filtered_notifications;
END;
$function$