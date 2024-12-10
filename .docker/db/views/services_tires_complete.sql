CREATE OR REPLACE VIEW public.services_tires_complete
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
    last_services.odoo_id AS service_id,
    last_services.odometer,
    last_services.date AS service_date,
    s_tires.tire_brand_id,
        CASE
            WHEN tb.name IS NULL THEN 'N/D'::character varying
            ELSE tb.name
        END AS tire_brand_name,
    s_tires.tire_model_id,
        CASE
            WHEN tm.name IS NULL THEN 'N/D'::character varying
            ELSE tm.name
        END AS tire_model_name,
    s_tires.tire_size_id,
        CASE
            WHEN ts.name IS NULL THEN 'N/D'::character varying
            ELSE ts.name
        END AS tire_size_name,
    tire_histories.tire_location,
    tire_histories.tread_depth,
    tire_histories.lifespan_consumed,
    tire_histories.mm_consumed,
    tire_histories.km_traveled AS km_travled,
    tire_histories.km_proyected,
    tire_histories.otd,
    tire_histories.performance_index,
    tire_histories.prom_performance_index,
    s_tires.dot,
    s_tires.starting_pressure,
    s_tires.finishing_pressure,
    s_tires.regular,
    s_tires.staggered,
    s_tires.central,
    s_tires.right_shoulder,
    s_tires.left_shoulder,
    s_tires.not_apply,
    s_tires.bulge,
    s_tires.perforations,
    s_tires.vulcanized,
    s_tires.aging,
    s_tires.cracked,
    s_tires.deformations,
    s_tires.separations,
    s_tires.tire_change,
        CASE
            WHEN s_tires.not_apply = true THEN 1
            ELSE 0
        END AS count_not_apply,
        CASE
            WHEN s_tires.bulge = true THEN 1
            ELSE 0
        END AS count_bulge,
        CASE
            WHEN s_tires.perforations = true THEN 1
            ELSE 0
        END AS count_perforations,
        CASE
            WHEN s_tires.vulcanized = true THEN 1
            ELSE 0
        END AS count_vulcanized,
        CASE
            WHEN s_tires.aging = true THEN 1
            ELSE 0
        END AS count_aging,
        CASE
            WHEN s_tires.cracked = true THEN 1
            ELSE 0
        END AS count_cracked,
        CASE
            WHEN s_tires.deformations = true THEN 1
            ELSE 0
        END AS count_deformations,
        CASE
            WHEN s_tires.separations = true THEN 1
            ELSE 0
        END AS count_separations,
        CASE
            WHEN tire_histories.lifespan_consumed <= 0.25::double precision THEN 1
            ELSE 0
        END AS count_25,
        CASE
            WHEN tire_histories.lifespan_consumed > 0.25::double precision AND tire_histories.lifespan_consumed <= 0.5::double precision THEN 1
            ELSE 0
        END AS count_50,
        CASE
            WHEN tire_histories.lifespan_consumed > 0.5::double precision AND tire_histories.lifespan_consumed <= 0.75::double precision THEN 1
            ELSE 0
        END AS count_75,
        CASE
            WHEN tire_histories.lifespan_consumed > 0.75::double precision THEN 1
            ELSE 0
        END AS count_100
   FROM last_service_by_vehicle last_services
     JOIN vehicle_tire_histories tire_histories ON last_services.odoo_id = tire_histories.service_id
     JOIN service_tires s_tires ON tire_histories.service_id = s_tires.service_id AND tire_histories.tire_location::text = s_tires.location::text
     JOIN users u ON last_services.owner_id = u.res_partner_id
     JOIN vehicles v ON last_services.vehicle_id = v.odoo_id
     JOIN vehicle_brands vb ON v.vehicle_brand_id = vb.odoo_id
     JOIN vehicle_models vm ON v.vehicle_model_id = vm.odoo_id
     JOIN tire_brands tb ON s_tires.tire_brand_id = tb.odoo_id
     JOIN tire_models tm ON s_tires.tire_model_id = tm.odoo_id
     JOIN tire_sizes ts ON s_tires.tire_size_id = ts.odoo_id
  WHERE tire_histories.tire_location::text <> 'Repuesto'::text
  ORDER BY v.odoo_id, last_services.odoo_id, tire_histories.tire_location;