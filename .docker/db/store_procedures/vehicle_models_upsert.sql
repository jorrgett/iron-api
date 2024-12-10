CREATE OR REPLACE PROCEDURE public.vehicle_models_upsert(
	IN p_name character varying, 
	IN p_vehicle_brand_id bigint, 
	IN p_odoo_id bigint
	)
 LANGUAGE plpgsql
AS $procedure$

BEGIN

IF (EXISTS (SELECT "odoo_id" FROM "public"."vehicle_models" WHERE "odoo_id" = p_odoo_id)) THEN
	UPDATE "public"."vehicle_models" SET
			   "name" = p_name,
   "vehicle_brand_id" = p_vehicle_brand_id,
		"updated_at" = CURRENT_TIMESTAMP,
		"sequence_id" = nextval('vehicle_models_sequence')
	WHERE
			"odoo_id" = p_odoo_id;
ELSE
	INSERT INTO "public"."vehicle_models" ("name", "vehicle_brand_id", "created_at", "sequence_id", "odoo_id")
	VALUES (p_name, p_vehicle_brand_id, CURRENT_TIMESTAMP, nextval('vehicle_models_sequence'), p_odoo_id );
END IF;

END;
$procedure$
;