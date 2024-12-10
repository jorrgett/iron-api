CREATE OR REPLACE VIEW public.services_oil_histories_complete
AS SELECT u.res_partner_id,
    u.full_name,
    u.email,
    u.phone,
    'N/D'::text AS ubicacion,
    v.odoo_id AS vehicle_id,
    v.plate,
    v.vehicle_brand_id,
        CASE
            WHEN vb.name IS NULL THEN 'N/D'::character varying
            ELSE vb.name
        END AS vehicle_brand_name,
    v.vehicle_model_id,
        CASE
            WHEN vm.name IS NULL THEN 'N/D'::character varying
            ELSE vm.name
        END AS vehicle_model_name,
    services.odoo_id AS service_id,
    services.odometer,
    services.date,
    so.life_span
   FROM service_oil so
     JOIN services ON so.service_id = services.odoo_id
     JOIN users u ON services.owner_id = u.res_partner_id
     JOIN vehicles v ON services.vehicle_id = v.odoo_id
     JOIN vehicle_brands vb ON v.vehicle_brand_id = vb.odoo_id
     JOIN vehicle_models vm ON v.vehicle_model_id = vm.odoo_id
  ORDER BY v.odoo_id;