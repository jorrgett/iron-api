CREATE OR REPLACE VIEW public.datato_vehicle_summaries
AS SELECT DISTINCT s.vehicle_id,
    s.odoo_id AS service_id,
    0 AS visits_number,
    row_number() OVER (PARTITION BY s.vehicle_id ORDER BY s.vehicle_id, s.odoo_id)::integer AS accum_oil_changes,
    s.date AS last_visit,
    s.odometer::integer AS odometer,
    0 AS accum_km_traveled,
    0 AS accum_days_total,
    'Aceite'::character varying(10) AS fuente
   FROM service_items si
     JOIN services s ON si.service_id = s.odoo_id
     JOIN service_items_actions sia ON si.product_id = sia.product_id
  WHERE sia.code = 1 AND NOT s.state::text = 'canceled'::text
UNION
 SELECT DISTINCT s.vehicle_id,
    s.odoo_id AS service_id,
    row_number() OVER (PARTITION BY s.vehicle_id ORDER BY s.vehicle_id, s.odoo_id)::integer AS visits_number,
    0 AS accum_oil_changes,
    s.date AS last_visit,
    s.odometer::integer AS odometer,
    COALESCE(s.odometer - vs.initial_km::double precision, 0::double precision)::integer AS accum_km_traveled,
    COALESCE(s.date - vs.initial_date, 0) AS accum_days_total,
    'Otros'::character varying(10) AS fuente
   FROM services s
     LEFT JOIN vehicle_summaries vs ON s.vehicle_id = vs.vehicle_id
  WHERE NOT s.state::text = 'canceled'::text;