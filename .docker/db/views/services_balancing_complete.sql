CREATE OR REPLACE VIEW public.services_balancing_complete
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
    last_service_balancing.odoo_id AS service_balancig_id,
    last_service_balancing.odometer AS service_balancig_odometer,
    last_service_balancing.date AS service_balancig_date,
    last_services.odoo_id AS service_id,
    last_services.odometer,
    last_services.date AS service_date,
    last_services.odometer - last_service_balancing.odometer AS kms_recorridos,
    last_services.date - last_service_balancing.date AS elapsed_days
   FROM last_service_balancing_by_vehicle last_service_balancing
     JOIN last_service_by_vehicle last_services ON last_service_balancing.vehicle_id = last_services.vehicle_id
     JOIN users u ON last_service_balancing.owner_id = u.res_partner_id
     JOIN vehicles v ON last_service_balancing.vehicle_id = v.odoo_id
     JOIN vehicle_brands vb ON v.vehicle_brand_id = vb.odoo_id
     JOIN vehicle_models vm ON v.vehicle_model_id = vm.odoo_id;