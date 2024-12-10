CREATE OR REPLACE PROCEDURE public.vehicle_summaries_update_vehicle(
    IN p_vehicle_id bigint, 
    IN p_service_id bigint
    )
 LANGUAGE plpgsql
AS $procedure$
DECLARE
    p_sequence_id bigint;
BEGIN
    p_sequence_id = NEXTVAL('vehicle_summaries_sequence');
    WITH dataVeh AS (
        SELECT
            vehicle_id,
            service_id,
            visits_number,
            accum_oil_changes,
            last_visit,
            odometer,
            accum_km_traveled,
            accum_days_total,
            fuente
        FROM
            public.datato_vehicle_summaries
        WHERE
            vehicle_id = p_vehicle_id)
    INSERT INTO public.vehicle_summaries (vehicle_id, initial_date, initial_km, visits_number, last_visit, accum_days_total, accum_km_traveled, created_at, sequence_id)
    SELECT
        src.vehicle_id,
        src.last_visit AS initial_date,
        src.odometer AS initial_km,
        src.visits_number,
        src.last_visit,
        src.accum_days_total,
        src.accum_km_traveled,
        CURRENT_TIMESTAMP AS created_at,
        p_sequence_id
    FROM
        dataVeh AS src
    WHERE
        src.service_id = p_service_id
        AND src.fuente = 'Otros'
    LIMIT 1
ON CONFLICT (vehicle_id)
/* or you may use [DO NOTHING;] */
    DO UPDATE SET
        visits_number = EXCLUDED.visits_number,
        last_visit = EXCLUDED.last_visit,
        accum_days_total = EXCLUDED.accum_days_total,
        accum_km_traveled = EXCLUDED.accum_km_traveled,
        updated_at = CURRENT_TIMESTAMP,
        sequence_id = p_sequence_id;
    WITH dataVeh AS (
        SELECT
            vehicle_id,
            service_id,
            visits_number,
            accum_oil_changes,
            last_visit,
            odometer,
            accum_km_traveled,
            accum_days_total,
            fuente
        FROM
            public.datato_vehicle_summaries
        WHERE
            vehicle_id = p_vehicle_id)
    INSERT INTO public.vehicle_summaries (vehicle_id, accum_oil_changes, last_oil_change_date, last_oil_change_km, created_at, sequence_id)
    SELECT
        src.vehicle_id,
        src.accum_oil_changes,
        src.last_visit,
        src.odometer,
        CURRENT_TIMESTAMP AS created_at,
        p_sequence_id
    FROM
        dataVeh AS src
    WHERE
        src.service_id = p_service_id
        AND src.fuente = 'Aceite'
    LIMIT 1
ON CONFLICT (vehicle_id)
/* or you may use [DO NOTHING;] */
    DO UPDATE SET
        accum_oil_changes = EXCLUDED.accum_oil_changes,
        last_oil_change_date = EXCLUDED.last_oil_change_date,
        last_oil_change_km = EXCLUDED.last_oil_change_km,
        updated_at = CURRENT_TIMESTAMP,
        sequence_id = p_sequence_id;
END
$procedure$
;