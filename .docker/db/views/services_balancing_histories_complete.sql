CREATE OR REPLACE VIEW public.services_balancing_histories_complete
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
    services.odometer AS service_balancig_odometer,
    services.date AS service_balancig_date,
    services.odometer,
    services.date AS service_date,
    service_balancing.location AS tire_location,
    service_balancing.lead_used,
    service_balancing.balanced,
    service_balancing.wheel_good_state,
    service_balancing.wheel_scratched,
    service_balancing.wheel_cracked,
    service_balancing.wheel_bent,
        CASE
            WHEN service_balancing.wheel_good_state THEN 'En Buen Estado'::text
            ELSE ''::text
        END AS wheel_good_state_desc,
        CASE
            WHEN service_balancing.wheel_scratched THEN 'Rayado'::text
            ELSE ''::text
        END AS wheel_scratched_desc,
        CASE
            WHEN service_balancing.wheel_cracked THEN 'Partido'::text
            ELSE ''::text
        END AS wheel_cracked_desc,
        CASE
            WHEN service_balancing.wheel_bent THEN 'Doblado'::text
            ELSE ''::text
        END AS wheel_bent_desc
   FROM service_balancing
     JOIN services ON service_balancing.service_id = services.odoo_id
     JOIN users u ON services.owner_id = u.res_partner_id
     JOIN vehicles v ON services.vehicle_id = v.odoo_id
     JOIN vehicle_brands vb ON v.vehicle_brand_id = vb.odoo_id
     JOIN vehicle_models vm ON v.vehicle_model_id = vm.odoo_id
  WHERE service_balancing.balanced;