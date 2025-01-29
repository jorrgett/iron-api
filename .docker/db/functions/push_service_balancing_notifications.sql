CREATE OR REPLACE FUNCTION public.push_service_balancing_notifications()
    RETURNS void
    LANGUAGE plpgsql
AS $function$
BEGIN
    WITH latest_service AS (
        SELECT
            s.owner_id,
            s.vehicle_id,
            s.odometer,
            s.date
        FROM
            public.services s
        WHERE
            s.state = 'done'
            AND (s.vehicle_id, s.date) IN (
                SELECT s.vehicle_id, MAX(s.date)
                FROM public.services s
                WHERE s.state = 'done'
                GROUP BY s.vehicle_id
            )
    ),
    latest_balancing_service AS (
        SELECT DISTINCT
            s.vehicle_id,
            s.odometer,
            s.date AS last_balancing_date
        FROM
            public.services s
        JOIN public.service_balancing sb ON sb.service_id = s.odoo_id
        AND (s.vehicle_id, s.date) IN (
            SELECT s.vehicle_id, MAX(s.date)
            FROM public.services s
            JOIN public.service_balancing sb ON sb.service_id = s.odoo_id
            WHERE sb.balanced = true
            GROUP BY s.vehicle_id
        )
    ),
    potential_notifications AS (
        SELECT
            u.id AS user_id,
            n.id AS notification_id,
            v.id AS vehicle_id,
            'for send' AS status,
            'balancing' AS topic_1,
            n.topic_2 AS topic_2,
            CURRENT_DATE AS created_at,
            CURRENT_DATE AS updated_at
        FROM
            latest_service ls
        JOIN
            latest_balancing_service lbs ON ls.vehicle_id = lbs.vehicle_id
        JOIN
            public.users u ON u.res_partner_id = ls.owner_id
        JOIN
            public.vehicles v ON v.odoo_id = ls.vehicle_id
        LEFT JOIN
            public.notifications n ON n.topic_1 = 'balancing' AND (
                (ROUND((ls.odometer - lbs.odometer) / 8000 * 100) >= 75 AND ROUND((ls.odometer - lbs.odometer) / 8000 * 100) < 90 AND n.topic_2 = '75') OR
                (ROUND((ls.odometer - lbs.odometer) / 8000 * 100) >= 90 AND ROUND((ls.odometer - lbs.odometer) / 8000 * 100) < 105 AND n.topic_2 = '90') OR
                (ROUND((ls.odometer - lbs.odometer) / 8000 * 100) >= 105 AND n.topic_2 = '105') OR

                (ROUND(DATE_PART('day', now() - lbs.last_balancing_date) / 180 * 100) >= 75 AND ROUND(DATE_PART('day', now() - lbs.last_balancing_date) / 180 * 100) < 90 AND n.topic_2 = '75') OR
                (ROUND(DATE_PART('day', now() - lbs.last_balancing_date) / 180 * 100) >= 90 AND ROUND(DATE_PART('day', now() - lbs.last_balancing_date) / 180 * 100) < 105 AND n.topic_2 = '90') OR
                (ROUND(DATE_PART('day', now() - lbs.last_balancing_date) / 180 * 100) >= 105 AND n.topic_2 = '105')
            )
        WHERE
            (ROUND((ls.odometer - lbs.odometer) / 8000 * 100) >= 75) OR
            (ROUND(DATE_PART('day', now() - lbs.last_balancing_date) / 180 * 100) >= 75)
    ),
    filtered_notifications AS (
        SELECT 
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
        INNER JOIN (
            SELECT
                vehicle_id,
                MAX(CAST(topic_2 AS integer)) AS max_topic_2
            FROM 
                potential_notifications
            GROUP BY 
                vehicle_id
        ) sub ON pn.vehicle_id = sub.vehicle_id AND CAST(pn.topic_2 AS integer) = sub.max_topic_2
        WHERE NOT EXISTS (
            SELECT 1
            FROM user_notifications un
            WHERE un.user_id = pn.user_id
            AND un.vehicle_id = pn.vehicle_id
            AND un.topic_1 = pn.topic_1
            AND un.topic_2 < pn.topic_2
        )
        GROUP BY pn.user_id, pn.notification_id, pn.vehicle_id, pn.status, pn.topic_1, pn.topic_2, pn.created_at, pn.updated_at
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