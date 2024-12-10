CREATE OR REPLACE PROCEDURE public.oil_change_histories_addnewvisits(
    IN p_vehicle_id bigint, 
    IN p_service_id bigint
    )
 LANGUAGE plpgsql
AS $procedure$
DECLARE
    p_rowcount int4;
    FC date;
    F2 date;
    F3 date;
    F1 date;
    O2 int4;
    O1 int4;
    lsp int4;
    FP date;
    p_change_next_days int4;
    last_service_id bigint;
BEGIN
    SELECT
        COUNT(*)
    FROM
        public.datato_oil_change_histories INTO p_rowcount
    WHERE
        vehicle_id = p_vehicle_id
        AND NOT service_state = 'cancelled';
    IF (p_rowcount >= 1) THEN
        WITH dataVeh AS (
            SELECT
              --FIRST_VALUE(change_date) OVER (PARTITION BY vehicle_id) AS FF1,
              --FIRST_VALUE(change_km) OVER (PARTITION BY vehicle_id) AS FO1,
                life_span,
                LAST_VALUE(service_id) OVER (PARTITION BY vehicle_id) AS service_id,
                LAST_VALUE(change_date) OVER (PARTITION BY vehicle_id) AS change_date,
                vehicle_id
            FROM
                public.datato_oil_change_histories
            WHERE
                vehicle_id = p_vehicle_id
            LIMIT 1
)
    SELECT
        life_span,
        service_id,
        change_date
    FROM
        dataVeh INTO lsp,
        last_service_id,
        FC
    WHERE
        vehicle_id = p_vehicle_id;
        SELECT
            "date",
            odometer
        FROM
            services s INTO F1,
            O1
        WHERE
            s.vehicle_id = p_vehicle_id
        ORDER BY
            s.vehicle_id,
            s.odoo_id
        LIMIT 1;
        SELECT
            "date",
            odometer
        FROM
            services s INTO F3,
            O2
        WHERE
            s.vehicle_id = p_vehicle_id
            AND s.odoo_id = p_service_id;
        p_change_next_days = lsp / ((O2 - O1) / ((F3 - F1) + 1));
        FP = FC + p_change_next_days;
        RAISE NOTICE 'Cantidad %, FP %, days %, data: F2: % | O2: % | F1: % | O1: % | LSP: % | LAST_OIL_CHANGE % | O2-O1: % | F2-F1: %  | KMXDIA: % ', p_rowcount, FP, p_change_next_days, F3, O2, F1, O1, lsp, last_service_id, O2 - O1, (F3 - F1)+1, ((O2 - O1) / ((F3 - F1) + 1)) ;
        UPDATE
            public.oil_change_histories
        SET
            change_next_date = FP,
            change_next_days = p_change_next_days,
            updated_at = CURRENT_TIMESTAMP
        WHERE
            vehicle_id = p_vehicle_id
            AND service_id = last_service_id;
    END IF;
EXCEPTION
    WHEN division_by_zero THEN
        RAISE NOTICE 'División por cero';
    WHEN OTHERS THEN
        RAISE NOTICE 'Error %', sqlstate;
END
$procedure$
;