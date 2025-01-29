CREATE OR REPLACE FUNCTION public.push_service_battery_notifications()
    RETURNS void
    LANGUAGE plpgsql
AS $function$
BEGIN
    WITH latest_battery_service AS (
        SELECT DISTINCT
            s.vehicle_id,
            s.owner_id,
            sb.service_id,
            sb.liquid_leakage,
            sb.inflated,
            sb.cracked_case,
            s.date AS last_service_date
        FROM
            public.services s
        JOIN public.service_battery sb ON sb.service_id = s.odoo_id
        WHERE
            (s.vehicle_id, s.date) IN (
                SELECT
                    s.vehicle_id,
                    MAX(s.date) AS max_date
                FROM
                    public.services s
                JOIN public.service_battery sb ON sb.service_id = s.odoo_id
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
            'battery' AS topic_1,
            CASE
                WHEN lb.liquid_leakage = TRUE AND n.topic_2 = 'liquid_leakage' THEN 'liquid_leakage'
                WHEN lb.inflated = TRUE AND n.topic_2 = 'inflated' THEN 'inflated'
                WHEN lb.cracked_case = TRUE AND n.topic_2 = 'cracked_case' THEN 'cracked_case'
            END AS topic_2,
            CURRENT_DATE AS created_at,
            CURRENT_DATE AS updated_at
        FROM
            latest_battery_service lb
        JOIN
            public.users u ON u.res_partner_id = lb.owner_id
        JOIN
            public.vehicles v ON v.odoo_id = lb.vehicle_id
        LEFT JOIN
            public.notifications n ON n.topic_1 = 'battery' AND (
                (lb.liquid_leakage = TRUE AND n.topic_2 = 'liquid_leakage') OR
                (lb.inflated = TRUE AND n.topic_2 = 'inflated') OR
                (lb.cracked_case = TRUE AND n.topic_2 = 'cracked_case')
            )
        WHERE
            lb.liquid_leakage = TRUE OR
            lb.inflated = TRUE OR
            lb.cracked_case = TRUE
        AND NOT EXISTS (
            SELECT 1
            FROM public.user_notifications un
            WHERE un.user_id = u.id
            AND un.vehicle_id = v.id
            AND un.topic_1 = 'battery'
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
