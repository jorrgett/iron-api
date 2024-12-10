CREATE OR REPLACE PROCEDURE public.vehicle_tire_summaries_upsert(
    IN p_vehicle_id bigint, 
    IN p_tire_location character varying, 
    IN p_prom_tire_km_month double precision, 
    IN p_prom_tire_mm_x_visit double precision, 
    IN p_months_to_tire_unsafe double precision, 
    IN p_projected_tire_visits double precision, 
    IN p_estimated_months_tire_visits double precision, 
    IN p_accum_km_traveled double precision, 
    IN p_accum_days_total double precision, 
    IN p_life_span_consumed double precision
    )
 LANGUAGE plpgsql
AS $procedure$
DECLARE
    p_sequence_id int4;
BEGIN
    p_sequence_id = NEXTVAL('vehicle_tire_summaries_sequence');
    INSERT INTO public.vehicle_tire_summaries (vehicle_id, tire_location, prom_tire_km_month, prom_tire_mm_x_visit, months_to_tire_unsafe, projected_tire_visits, estimated_months_tire_visits, accum_km_traveled, accum_days_total, life_span_consumed, sequence_id, created_at)
        VALUES (p_vehicle_id, p_tire_location, p_prom_tire_km_month, p_prom_tire_mm_x_visit, p_months_to_tire_unsafe, p_projected_tire_visits, p_estimated_months_tire_visits, p_accum_km_traveled, p_accum_days_total, p_life_span_consumed, p_sequence_id, CURRENT_TIMESTAMP)
    ON CONFLICT (vehicle_id, tire_location)
    /* or you may use [DO NOTHING;] */
        DO UPDATE SET
            vehicle_id = EXCLUDED.vehicle_id, tire_location = EXCLUDED.tire_location, prom_tire_km_month = EXCLUDED.prom_tire_km_month, prom_tire_mm_x_visit = EXCLUDED.prom_tire_mm_x_visit, months_to_tire_unsafe = EXCLUDED.months_to_tire_unsafe, projected_tire_visits = EXCLUDED.projected_tire_visits, estimated_months_tire_visits = EXCLUDED.estimated_months_tire_visits, accum_km_traveled = EXCLUDED.accum_km_traveled, accum_days_total = EXCLUDED.accum_days_total, life_span_consumed = EXCLUDED.life_span_consumed, sequence_id = p_sequence_id, updated_at = CURRENT_TIMESTAMP;
END;
$procedure$
;