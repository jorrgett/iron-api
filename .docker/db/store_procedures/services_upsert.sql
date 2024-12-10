CREATE OR REPLACE PROCEDURE public.services_upsert(
  IN p_store_id bigint, 
  IN p_driver_id bigint, 
  IN p_owner_id bigint, 
  IN p_vehicle_id bigint, 
  IN p_date date, 
  IN p_odometer double precision, 
  IN p_odometer_id bigint, 
  IN p_state character varying, 
  IN p_odoo_id bigint, 
  IN p_driver_name character varying, 
  IN p_owner_name character varying, 
  IN p_rotation_x boolean, 
  IN p_rotation_lineal boolean
  )
 LANGUAGE plpgsql
AS $procedure$

BEGIN
IF (EXISTS (SELECT "odoo_id" FROM "public"."services" WHERE "odoo_id" = p_odoo_id)) THEN
    UPDATE "public"."services" SET
           "store_id" = p_store_id,
          "driver_id" = p_driver_id,
           "owner_id" = p_owner_id,
         "vehicle_id" = p_vehicle_id,
               "date" = p_date,
           "odometer" = p_odometer,
        "odometer_id" = p_odometer_id,
              "state" = p_state,
        "driver_name" = p_driver_name,
         "owner_name" = p_owner_name,
         "rotation_x" = p_rotation_x,
         "rotation_lineal" = p_rotation_lineal,
        "updated_at" = CURRENT_TIMESTAMP,
        "sequence_id" = nextval('services_sequence')
      WHERE "odoo_id" = p_odoo_id;
ELSE
    INSERT INTO "public"."services" ("store_id", "driver_id", "owner_id", "vehicle_id", "date", "odometer", "odometer_id", "state", "created_at", "sequence_id", "odoo_id", "driver_name", "owner_name", "rotation_x", "rotation_lineal")
    VALUES(p_store_id, p_driver_id, p_owner_id, p_vehicle_id, p_date, p_odometer, p_odometer_id, p_state, CURRENT_TIMESTAMP, nextval('services_sequence'), p_odoo_id, p_driver_name, p_owner_name, p_rotation_x, p_rotation_lineal);

END IF;

END;
$procedure$
;