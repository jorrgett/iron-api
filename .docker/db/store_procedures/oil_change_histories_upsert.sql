CREATE OR REPLACE PROCEDURE public.oil_change_histories_upsert(
    IN p_vehicle_id bigint, 
    IN p_service_id bigint, 
    IN p_change_date date, 
    IN p_change_km double precision, 
    IN p_change_next_km double precision, 
    IN p_change_next_date date, 
    IN p_life_span integer, 
    IN p_life_span_standar integer, 
    IN p_service_state character varying
    )
 LANGUAGE plpgsql
AS $procedure$
BEGIN
    IF (EXISTS (
        SELECT
            vehicle_id
        FROM
            public.oil_change_histories
        WHERE
            vehicle_id = p_vehicle_id AND service_id = p_service_id)) THEN
        UPDATE
            public.oil_change_histories
        SET
            change_date = p_change_date,
            change_km = p_change_km,
            change_next_km = p_change_next_km,
            change_next_date = p_change_next_date,
            life_span = p_life_span,
            life_span_standar = p_life_span_standar,
            service_state = p_service_state,
            updated_at = CURRENT_TIMESTAMP,
            sequence_id = NEXTVAL('oil_change_histories_sequence')
        WHERE
            vehicle_id = p_vehicle_id
            AND service_id = p_service_id;
    ELSE
        INSERT INTO public.oil_change_histories (vehicle_id, service_id, change_date, change_km, change_next_km, change_next_date, life_span, life_span_standar, service_state, created_at, sequence_id)
            VALUES (p_vehicle_id, p_service_id, p_change_date, p_change_km, p_change_next_km, p_change_next_date, p_life_span, p_life_span_standar, p_service_state, CURRENT_TIMESTAMP, NEXTVAL('oil_change_histories_sequence'));
    END IF;
END;
$procedure$
;