CREATE OR REPLACE PROCEDURE public.odometers_upsert(
	IN p_vehicle_id bigint, 
	IN p_driver_id bigint, 
	IN p_date character varying, 
	IN p_value double precision, 
	IN p_odoo_id bigint
	)
 LANGUAGE plpgsql
AS $procedure$

BEGIN

IF (EXISTS (SELECT "odoo_id" FROM "public"."odometers" WHERE "odoo_id" = p_odoo_id)) THEN
	UPDATE "public"."odometers" SET
		 "vehicle_id" = p_vehicle_id,
		  "driver_id" = p_driver_id,
			   "date" = p_date,
			  "value" = p_value,
		"updated_at" = CURRENT_TIMESTAMP,
		"sequence_id" = nextval('odometers_sequence')
	WHERE
			"odoo_id" = p_odoo_id;
ELSE
	INSERT INTO "public"."odometers" ( "vehicle_id", "driver_id", "date", "value", "created_at", "sequence_id", "odoo_id")
	VALUES ( p_vehicle_id, p_driver_id, p_date, p_value, CURRENT_TIMESTAMP, nextval('odometers_sequence'), p_odoo_id );
END IF;

END;
$procedure$
;