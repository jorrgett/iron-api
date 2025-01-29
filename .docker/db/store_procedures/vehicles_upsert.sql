CREATE OR REPLACE PROCEDURE public.vehicles_upsert(
    IN p_plate character varying, 
    IN p_vehicle_brand_id bigint, 
    IN p_vehicle_model_id bigint, 
    IN p_register_date date, 
    IN p_color character varying, 
    IN p_year integer, 
    IN p_transmission character varying, 
    IN p_fuel character varying, 
    IN p_odometer double precision, 
    IN p_odoo_id bigint, 
    IN p_nickname character varying, 
    IN p_color_hex character varying, 
    IN p_icon smallint,
    IN p_type_vehicle character varying
    )
 LANGUAGE plpgsql
AS $procedure$

BEGIN

IF (EXISTS (SELECT "odoo_id" FROM "public"."vehicles" WHERE "odoo_id" = p_odoo_id)) THEN
    UPDATE "public"."vehicles" SET
                   "plate" = p_plate,
        "vehicle_model_id" = p_vehicle_model_id,
           "register_date" = p_register_date,
                    "year" = p_year,
                   "color" = p_color,
            "transmission" = p_transmission,
                    "fuel" = p_fuel,
                "odometer" = p_odometer,
             "updated_at" = CURRENT_TIMESTAMP,
        "vehicle_brand_id" = p_vehicle_brand_id,
                "nickname" = p_nickname,
               "color_hex" = p_color_hex,
                    "icon" = p_icon,
            "type_vehicle" = p_type_vehicle,
             "sequence_id" = nextval('vehicles_sequence')
                 WHERE "odoo_id" = p_odoo_id;
ELSE
    INSERT INTO "public"."vehicles" ("plate", "vehicle_model_id", "register_date", "year", "color", "transmission", "fuel", "odometer", "created_at", "vehicle_brand_id", "sequence_id", "odoo_id","color_hex", "nickname", "icon", "type_vehicle")
    VALUES (p_plate, p_vehicle_model_id, p_register_date, p_year, p_color, p_transmission, p_fuel, p_odometer, CURRENT_TIMESTAMP, p_vehicle_brand_id, nextval('vehicles_sequence'), p_odoo_id, p_color_hex, p_nickname, p_icon, p_type_vehicle);
END IF;

END;
$procedure$;