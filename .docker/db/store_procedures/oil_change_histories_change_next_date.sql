CREATE OR REPLACE PROCEDURE public.oil_change_histories_change_next_date(
    IN p_vehicle_id bigint, 
    IN p_service_id bigint
    )
 LANGUAGE plpgsql
AS $procedure$
DECLARE
    p_rowcount int4;
    F2 date;
    F1 date;
    O2 int4;
    O1 int4;
    lsp int4;
    FP date;
    p_change_next_days int4;
BEGIN
    SELECT
        COUNT(*)
    FROM
        public.datato_oil_change_histories INTO p_rowcount
    WHERE
        vehicle_id = p_vehicle_id
        AND NOT service_state = 'cancelled';
    IF (p_rowcount >= 2) THEN
        WITH dataVeh AS (
            SELECT
                vehicle_id,
                service_id,
                change_date,
                change_km,
                FIRST_VALUE(change_date) OVER (PARTITION BY vehicle_id) AS FF1,
                FIRST_VALUE(change_km) OVER (PARTITION BY vehicle_id) AS FO1,
                life_span
            FROM
                public.datato_oil_change_histories
            WHERE
                vehicle_id = p_vehicle_id
)
        SELECT
            change_date,
            change_km,
            FF1,
            FO1,
            life_span
        FROM
            dataVeh INTO F2,
            O2,
            F1,
            O1,
            lsp
        WHERE
            vehicle_id = p_vehicle_id
            AND service_id = p_service_id;
        --RAISE NOTICE 'Cantidad %, data: % % % % %', p_rowcount, F2, O2, F1, O1, lsp;
        -- FP = F2 + (lsp / ((O2 - o1) / (F2 - F1 + 1)));
        --RAISE NOTICE ' DATA % % %', lsp, (O2 - O1), (F2 - F1 + 1);
        p_change_next_days = lsp / ((O2 - O1) / (F2 - F1 + 1));
        FP = F2 + p_change_next_days;
        --RAISE NOTICE 'Cantidad %, FP %, days %, data: % % % % %', p_rowcount, FP, p_change_next_days, F2, O2, F1, O1, lsp;
        UPDATE
            public.oil_change_histories
        SET
            change_next_date = FP,
            change_next_days = p_change_next_days,
            updated_at = CURRENT_TIMESTAMP
        WHERE
            vehicle_id = p_vehicle_id
            AND service_id = p_service_id;
    END IF;
EXCEPTION
    WHEN division_by_zero THEN
        RAISE NOTICE 'División por cero';
END
$procedure$
;