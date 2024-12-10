CREATE OR REPLACE VIEW public.vehicle_without_services_oil
AS SELECT u.res_partner_id,
    u.full_name,
    u.email,
    u.phone,
    'N/D'::text AS ubicacion,
    max(v.odoo_id) AS vehicle_id,
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
        END AS vehicle_model_name
   FROM vehicles v
     JOIN services s ON v.odoo_id = s.vehicle_id
     JOIN vehicle_brands vb ON v.vehicle_brand_id = vb.odoo_id
     JOIN vehicle_models vm ON v.vehicle_model_id = vm.odoo_id
     JOIN users u ON s.owner_id = u.res_partner_id
     LEFT JOIN services_oil_complete sb ON v.odoo_id = sb.vehicle_id
  WHERE sb.vehicle_id IS NULL
  GROUP BY u.res_partner_id, u.full_name, u.email, u.phone, v.plate, v.vehicle_brand_id, vb.name, v.vehicle_model_id, vm.name;