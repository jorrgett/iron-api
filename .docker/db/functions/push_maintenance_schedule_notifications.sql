CREATE OR REPLACE FUNCTION public.push_maintenance_schedule_notifications()
    RETURNS void
    LANGUAGE plpgsql
AS $function$
BEGIN
    WITH latest_service AS (
        SELECT *
        FROM public.services s
        WHERE s.state = 'done'
        AND (s.vehicle_id, s.date, s.id) IN (
            SELECT s.vehicle_id, MAX(s.date), MAX(id)
            FROM public.services s
            WHERE s.state = 'done'
            GROUP BY s.vehicle_id
        )
    ),
    max_orders AS (
        SELECT
            vehicle_id,
            MAX("order") AS max_order
        FROM maintenance_schedules
        GROUP BY vehicle_id
    ),
    filter_maintenance as (
        SELECT
            ls.owner_id,
            ls.vehicle_id,
            ls.odometer,
            ms.maintenance_kms,
            ms.created_at + (ms.maintenance_interval || ' days')::interval as next_date,
            ROUND(((EXTRACT(EPOCH FROM CURRENT_DATE - ms.created_at) / 
            (EXTRACT(EPOCH FROM (ms.maintenance_interval || ' days')::interval))) * 100)) AS date_percentage,
            ROUND((ls.odometer / ms.maintenance_kms) * 100) as kms_percentage
        FROM maintenance_schedules ms
        JOIN max_orders mo ON ms.vehicle_id = mo.vehicle_id AND ms."order" = mo.max_order
        JOIN latest_service ls ON ls.vehicle_id = ms.vehicle_id
    ),
    potential_notifications as (
        select
            u.id as user_id,
            n.id as notification_id,
            v.id as vehicle_id,
            'for send' as status,
            'maintenance_schedule' as topic_1,
            n.topic_2 as topic_2,
            CURRENT_DATE as created_at,
            current_date as updated_at
        from filter_maintenance fm
        join vehicles v on fm.vehicle_id = v.odoo_id
        join users u on fm.owner_id = u.res_partner_id
        left join notifications n on n.topic_1 = 'maintenance_schedule'
            and (
                (fm.kms_percentage >= 75 AND fm.kms_percentage < 90 AND n.topic_2 = '75') OR
                (fm.kms_percentage >= 90 AND fm.kms_percentage < 105 AND n.topic_2 = '90') OR
                (fm.kms_percentage >= 105 AND n.topic_2 = '105') OR
                    
                (fm.date_percentage >= 75 AND fm.date_percentage < 90 AND n.topic_2 = '75') OR
                (fm.date_percentage >= 90 AND fm.date_percentage < 105 AND n.topic_2 = '90') OR
                (fm.date_percentage >= 105 AND n.topic_2 = '105')
            )
        where
            (fm.kms_percentage >= 75) or
            (fm.date_percentage >= 75)
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
        GROUP BY pn.user_id, pn.notification_id, pn.vehicle_id, pn.status, pn.topic_1, pn.topic_2, pn.created_at, pn.updated_at
    )
    INSERT INTO user_notifications (user_id, notification_id, vehicle_id, tire_location, status, topic_1, topic_2, created_at, updated_at)
    SELECT
        user_id,
        notification_id,
        vehicle_id,
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