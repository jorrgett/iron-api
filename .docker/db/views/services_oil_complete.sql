CREATE OR REPLACE VIEW public.services_oil_complete
AS  SELECT u.res_partner_id,
    u.full_name,
    u.email,
    u.phone,
    'N/D'::text AS ubicacion,
    v.odoo_id AS vehicle_id,
    v.plate,
    v.vehicle_brand_id,
        CASE
            WHEN (vb.name IS NULL) THEN 'N/D'::character varying
            ELSE vb.name
        END AS vehicle_brand_name,
    v.vehicle_model_id,
        CASE
            WHEN (vm.name IS NULL) THEN 'N/D'::character varying
            ELSE vm.name
        END AS vehicle_model_name,
    last_service_oil.odoo_id AS service_oil_id,
    last_service_oil.odometer AS service_oil_odometer,
    last_service_oil.date AS service_oil_date,
    last_services.odoo_id AS service_id,
    last_services.odometer,
    last_services.date AS service_date,
    so.life_span,
    (last_services.odometer - last_service_oil.odometer) AS kms_recorridos,
    (last_services.date - last_service_oil.date) AS elapsed_days,
    (((((('ACEITE '::text || ' '::text) || (tb.name)::text) || ' '::text) || (so.oil_viscosity)::text) || ' '::text) || (so.type_oil)::text) AS display_name,
    tb.name AS brand_name,
    so.oil_viscosity,
    so.type_oil,
    9 AS qty,
    'FILTER NAME'::text AS filter_name,
    999 AS filter_brand_id,
    'FILTER_BRAND'::text AS filter_brand_name
   FROM (((((((last_service_oil_by_vehicle last_service_oil
     JOIN last_service_by_vehicle last_services ON ((last_service_oil.vehicle_id = last_services.vehicle_id)))
     JOIN service_oil so ON ((last_service_oil.odoo_id = so.service_id)))
     JOIN users u ON ((last_service_oil.owner_id = u.res_partner_id)))
     JOIN vehicles v ON ((last_service_oil.vehicle_id = v.odoo_id)))
     JOIN vehicle_brands vb ON ((v.vehicle_brand_id = vb.odoo_id)))
     JOIN vehicle_models vm ON ((v.vehicle_model_id = vm.odoo_id)))
     JOIN tire_brands tb ON ((so.tire_brand_id = tb.odoo_id)))
  ORDER BY v.odoo_id;