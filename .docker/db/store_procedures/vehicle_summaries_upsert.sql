CREATE OR REPLACE PROCEDURE public.vehicle_summaries_upsert(
    IN p_vehicle_id bigint, 
    IN p_initial_date date, 
    IN p_initial_km integer, 
    IN p_visits_number integer, 
    IN p_accum_km_traveled integer, 
    IN p_accum_days_total integer, 
    IN p_accum_oil_changes integer, 
    IN p_last_oil_change_date date, 
    IN p_last_oil_change_km integer
    )
 LANGUAGE plpgsql
AS $procedure$
BEGIN
    IF (EXISTS (
        SELECT
            vehicle_id
        FROM
            public.vehicle_summaries
        WHERE
            vehicle_id = p_vehicle_id)) THEN
        UPDATE
            public.vehicle_summaries
        SET
            initial_date = p_initial_date,
            initial_km = p_initial_km,
            visits_number = p_visits_number,
            accum_km_traveled = p_odometer,
            accum_days_total = p_accum_days_total,
            accum_oil_changes = p_accum_oil_changes,
            last_oil_change_date = p_last_visit,
            last_oil_change_km = p_odometer,
            updated_at = CURRENT_TIMESTAMP,
            sequence_id = NEXTVAL('vehicle_summaries_sequence')
        WHERE
            vehicle_id = p_vehicle_id;
    ELSE
        INSERT INTO public.vehicle_summaries (vehicle_id, initial_date, initial_km, visits_number, accum_km_traveled, accum_days_total, accum_oil_changes, last_oil_change_date, last_oil_change_km, created_at, sequence_id)
            VALUES (p_vehicle_id, p_initial_date, p_initial_km, p_visits_number, p_accum_km_traveled, p_accum_days_total, p_accum_oil_changes, p_last_oil_change_date, p_last_oil_change_km, CURRENT_TIMESTAMP, NEXTVAL('vehicle_summaries_sequence'));
    END IF;
END;
$procedure$
;