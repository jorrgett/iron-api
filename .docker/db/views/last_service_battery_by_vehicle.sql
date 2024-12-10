CREATE OR REPLACE VIEW public.last_service_battery_by_vehicle
AS SELECT s.vehicle_id,
    max(s.odoo_id) AS service_id,
    max(s.date) AS last_service_battery
   FROM service_battery sb
     JOIN services s ON sb.service_id = s.odoo_id
  GROUP BY s.vehicle_id;