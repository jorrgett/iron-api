CREATE OR REPLACE PROCEDURE public.tire_brands_upsert(
	IN p_name character varying, 
	IN p_url_image character varying, 
	IN p_odoo_id bigint
	)
 LANGUAGE plpgsql
AS $procedure$

BEGIN

IF (EXISTS (SELECT "odoo_id" FROM "public"."tire_brands" WHERE "odoo_id" = p_odoo_id)) THEN
	UPDATE "public"."tire_brands" SET
			   "name" = p_name,
		  "url_image" = p_url_image,
		"updated_at" = CURRENT_TIMESTAMP,
		"sequence_id" = nextval('tire_brands_sequence')
	WHERE
			"odoo_id" = p_odoo_id;
ELSE
	INSERT INTO "public"."tire_brands" ("name", "url_image", "created_at", "sequence_id", "odoo_id")
	VALUES (p_name, p_url_image, CURRENT_TIMESTAMP, nextval('tire_brands_sequence'), p_odoo_id );
END IF;

END;
$procedure$
;