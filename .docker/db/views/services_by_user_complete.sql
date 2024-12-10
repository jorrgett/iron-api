CREATE OR REPLACE VIEW public.services_by_user_complete
AS SELECT services.odoo_id,
    user_owner.id AS user_id,
    user_owner.res_partner_id AS owner_id,
    user_owner.full_name AS owner_name,
    user_owner.email AS owner_email,
    user_owner.phone AS owner_phone,
        CASE
            WHEN user_driver.res_partner_id IS NULL THEN user_owner.res_partner_id
            ELSE user_driver.res_partner_id
        END AS driver_id,
        CASE
            WHEN user_driver.full_name IS NULL THEN user_owner.full_name
            ELSE user_driver.full_name
        END AS driver_name,
    user_driver.email AS driver_email,
    user_driver.phone AS driver_phone,
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
    services.date AS service_date,
        CASE
            WHEN services.state::text = 'done'::text THEN 'Completado'::character varying
            WHEN services.state::text = 'running'::text THEN 'En Proceso'::character varying
            WHEN services.state::text = 'to do'::text THEN 'Iniciado'::character varying
            ELSE 'N/D'::character varying
        END AS status,
    services.state,
    stores.name AS store_name
   FROM services services
     JOIN users user_owner ON services.owner_id = user_owner.res_partner_id
     LEFT JOIN users user_driver ON services.driver_id = user_driver.res_partner_id
     JOIN vehicles v ON services.vehicle_id = v.odoo_id
     JOIN vehicle_brands vb ON v.vehicle_brand_id = vb.odoo_id
     JOIN vehicle_models vm ON v.vehicle_model_id = vm.odoo_id
     JOIN stores stores ON services.store_id = stores.odoo_id
  ORDER BY v.odoo_id, services.odoo_id;