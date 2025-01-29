CREATE OR REPLACE PROCEDURE public.oil_change_histories_addnewservice(
    IN p_service_id bigint
    )
 LANGUAGE plpgsql
AS $procedure$
DECLARE
    p_sequence_id bigint;
BEGIN
    p_sequence_id = NEXTVAL('oil_change_histories_sequence');
    INSERT INTO public.oil_change_histories (vehicle_id, service_id, change_date, change_km, change_next_km, change_next_date, life_span, life_span_standar, service_state, created_at, sequence_id)
    SELECT
        src.vehicle_id,
        src.service_id,
        src.change_date,
        src.change_km,
		src.change_next_km,
		src.change_next_date,
		src.life_span,
		src.life_span_standar,
	    src.service_state,
        CURRENT_TIMESTAMP AS created_at,
        p_sequence_id
    FROM
        public.datato_oil_change_histories AS src
    WHERE
        src.service_id = p_service_id
    ON CONFLICT (vehicle_id, service_id)
    /* or you may use [DO NOTHING;] */
        DO UPDATE SET
          change_date = EXCLUDED.change_date,
            change_km = EXCLUDED.change_km,
       change_next_km = EXCLUDED.change_next_km,
     change_next_date = EXCLUDED.change_next_date,
            life_span = EXCLUDED.life_span,
    life_span_standar = EXCLUDED.life_span_standar,
	    service_state = EXCLUDED.service_state,
           updated_at = CURRENT_TIMESTAMP,
          sequence_id = p_sequence_id;
END
$procedure$
;