CREATE OR REPLACE PROCEDURE public.tire_oem_depths_upsert(
	IN p_tire_brand_id bigint, 
	IN p_tire_model_id bigint, 
	IN p_tire_size_id bigint, 
	IN p_otd bigint, 
	IN p_odoo_id bigint
	)
 LANGUAGE plpgsql
AS $procedure$

BEGIN

IF (EXISTS (SELECT "odoo_id" FROM "public"."tire_oem_depths" WHERE "odoo_id" = p_odoo_id)) THEN
	UPDATE "public"."tire_oem_depths" SET
		"tire_brand_id" = p_tire_brand_id,
		"tire_model_id" = p_tire_model_id,
		 "tire_size_id" = p_tire_size_id,
				  "otd" = p_otd,
		  "updated_at" = CURRENT_TIMESTAMP,
		  "sequence_id" = nextval('tire_oem_depths_sequence')
	WHERE
			"odoo_id" = p_odoo_id;
ELSE
	INSERT INTO "public"."tire_oem_depths" ("tire_brand_id", "tire_model_id", "tire_size_id", "otd", "created_at", "sequence_id", "odoo_id")
	VALUES (p_tire_brand_id, p_tire_model_id, p_tire_size_id, p_otd, CURRENT_TIMESTAMP, nextval('tire_oem_depths_sequence'), p_odoo_id );
END IF;

END;
$procedure$
;