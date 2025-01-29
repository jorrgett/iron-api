CREATE OR REPLACE VIEW public.last_service_tires_by_vehicle
AS SELECT s.id,
    s.odoo_id,
    s.store_id,
    s.driver_id,
    s.owner_id,
    s.vehicle_id,
    s.date,
    s.odometer,
    s.odometer_id,
    s.state,
    s.created_at,
    s.updated_at,
    s.sequence_id,
    s.owner_name,
    s.driver_name,
    s.rotation_x,
    s.rotation_lineal
   FROM services s
     JOIN ( SELECT s_1.vehicle_id,
            max(s_1.odoo_id) AS service_id,
            max(s_1.date) AS last_service
           FROM service_tires sb
             JOIN services s_1 ON sb.service_id = s_1.odoo_id
          GROUP BY s_1.vehicle_id) last_service_tires ON s.odoo_id = last_service_tires.service_id
  ORDER BY s.vehicle_id;