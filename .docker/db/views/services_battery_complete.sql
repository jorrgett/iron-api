CREATE OR REPLACE VIEW public.services_battery_complete
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
        END AS vehicle_brand_model,
    sb.battery_brand_id,
        CASE
            WHEN tb.name IS NULL OR tb.name::text = '0'::text THEN 'N/D'::character varying
            ELSE tb.name
        END AS battery_brand_name,
    sb.battery_model_id,
        CASE
            WHEN tm.name IS NULL OR tm.name::text = '0'::text THEN 'N/D'::character varying
            ELSE tm.name
        END AS battery_model_name,
    sb.date_of_purchase,
    sb.warranty_date,
    sb.amperage,
    sb.battery_voltage,
    sb.alternator_voltage,
        CASE
            WHEN sb.status_battery::text = '0'::text THEN 'No Disponible'::character varying
            ELSE sb.status_battery
        END AS status_battery,
    sb.health_status,
    sb.health_percentage,
        CASE
            WHEN sb.health_status_final::text = 'Buen estado'::text THEN 'Buen estado'::character varying
            WHEN sb.health_status_final::text = 'Requiere carga'::text THEN 'Recargar'::character varying
            WHEN sb.health_status_final::text = 'Deficiente'::text THEN 'Reemplazar'::character varying
            WHEN sb.health_status_final::text = 'Dañada'::text THEN 'Dañada'::character varying
            ELSE sb.health_status_final
        END AS health_status_final,
    sb.good_condition,
    sb.liquid_leakage,
    sb.corroded_terminals,
    sb.frayed_cables,
    sb.inflated,
    sb.cracked_case,
    sb.new_battery,
    sb.battery_charged,
        CASE
            WHEN sb.good_condition = true THEN 1
            ELSE 0
        END AS count_buen_estado,
        CASE
            WHEN sb.liquid_leakage = true THEN 1
            ELSE 0
        END AS count_fuga_de_liquido,
        CASE
            WHEN sb.corroded_terminals = true THEN 1
            ELSE 0
        END AS count_bornes_sulfatados,
        CASE
            WHEN sb.frayed_cables = true THEN 1
            ELSE 0
        END AS count_cables_partidos,
        CASE
            WHEN sb.cracked_case = true THEN 1
            ELSE 0
        END AS count_carcasa_partida,
        CASE
            WHEN sb.inflated = true THEN 1
            ELSE 0
        END AS count_bateria_inflada,
        CASE
            WHEN sb.battery_charged = true THEN 1
            ELSE 0
        END AS count_recarga_bateria
   FROM ( SELECT s_1.vehicle_id,
            max(s_1.odoo_id) AS service_id,
            max(s_1.date) AS last_service_battery
           FROM service_battery sb_1
             JOIN services s_1 ON sb_1.service_id = s_1.odoo_id
          GROUP BY s_1.vehicle_id) lsb
     JOIN service_battery sb ON lsb.service_id = sb.service_id
     JOIN services s ON lsb.service_id = s.odoo_id
     LEFT JOIN tire_brands tb ON sb.battery_brand_id = tb.odoo_id
     LEFT JOIN tire_models tm ON sb.battery_brand_id = tm.odoo_id
     JOIN users u ON s.owner_id = u.res_partner_id
     JOIN vehicles v ON s.vehicle_id = v.odoo_id
     JOIN vehicle_brands vb ON v.vehicle_brand_id = vb.odoo_id
     JOIN vehicle_models vm ON v.vehicle_model_id = vm.odoo_id;