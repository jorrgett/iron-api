CREATE OR REPLACE VIEW public.last_service_by_vehicle
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
     JOIN ( SELECT s_aux.vehicle_id,
            max(s_aux.odoo_id) AS odoo_id,
            max(s_aux.date) AS max
           FROM services s_aux
          GROUP BY s_aux.vehicle_id) last_service ON s.odoo_id = last_service.odoo_id
  ORDER BY s.vehicle_id;
