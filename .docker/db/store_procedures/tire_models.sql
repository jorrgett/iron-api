CREATE OR REPLACE PROCEDURE public.tire_models_upsert(
	IN p_name character varying, 
	IN p_tire_brand_id bigint, 
	IN p_odoo_id bigint
	)
 LANGUAGE plpgsql
AS $procedure$

BEGIN

IF (EXISTS (SELECT "odoo_id" FROM "public"."tire_models" WHERE "odoo_id" = p_odoo_id)) THEN
	UPDATE "public"."tire_models" SET
			   "name" = p_name,
      "tire_brand_id" = p_tire_brand_id,
		"updated_at" = CURRENT_TIMESTAMP,
		"sequence_id" = nextval('tire_models_sequence')
	WHERE
			"odoo_id" = p_odoo_id;
ELSE
	INSERT INTO "public"."tire_models" ("name", "tire_brand_id", "created_at", "sequence_id", "odoo_id")
	VALUES (p_name, p_tire_brand_id, CURRENT_TIMESTAMP, nextval('tire_models_sequence'), p_odoo_id );
END IF;

END;
$procedure$
;