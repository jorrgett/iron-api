CREATE OR REPLACE VIEW public.datato_oil_change_histories
AS WITH life_span AS (
         SELECT so.service_id,
                CASE
                    WHEN so.life_span > 0 THEN so.life_span
                    WHEN so.life_span = 0 THEN 5000
                    ELSE NULL::integer
                END AS life_span,
            5000 AS life_span_standar
           FROM service_oil so
        UNION
         SELECT si_1.service_id,
                CASE
                    WHEN p.life_span > 0 THEN p.life_span
                    WHEN p.life_span = 0 THEN 5000
                    ELSE NULL::integer
                END AS life_span,
            5000 AS life_span_standar
           FROM service_items si_1
             JOIN services s_1 ON si_1.service_id = s_1.odoo_id
             JOIN products p ON si_1.product_id = p.odoo_id
             JOIN service_items_actions sia_1 ON si_1.product_id = sia_1.product_id
          WHERE sia_1.code = 2 AND NOT (si_1.service_id IN ( SELECT service_oil.service_id
                   FROM service_oil))
        )
 SELECT DISTINCT s.vehicle_id,
    si.service_id,
    s.date AS change_date,
    s.date AS change_next_date,
    s.odometer::integer AS change_km,
    s.odometer::integer + ls.life_span AS change_next_km,
    s.state AS service_state,
    ls.life_span,
    ls.life_span_standar
   FROM service_items si
     JOIN services s ON si.service_id = s.odoo_id
     JOIN service_items_actions sia ON si.product_id = sia.product_id
     JOIN life_span ls ON si.service_id = ls.service_id
  WHERE sia.code = 1;