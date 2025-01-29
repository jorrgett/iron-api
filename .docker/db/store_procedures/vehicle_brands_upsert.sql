CREATE OR REPLACE PROCEDURE public.vehicle_brands_upsert(
	IN p_name character varying, 
	IN p_url_image character varying, 
	IN p_odoo_id bigint
	)
 LANGUAGE plpgsql
AS $procedure$

BEGIN

IF (EXISTS (SELECT "odoo_id" FROM "public"."vehicle_brands" WHERE "odoo_id" = p_odoo_id)) THEN
	UPDATE "public"."vehicle_brands" SET
			   "name" = p_name,
		  "url_image" = p_url_image,
		"updated_at" = CURRENT_TIMESTAMP,
		"sequence_id" = nextval('vehicle_brands_sequence')
	WHERE
			"odoo_id" = p_odoo_id;
ELSE
	INSERT INTO "public"."vehicle_brands" ("name", "url_image", "created_at", "sequence_id", "odoo_id")
	VALUES (p_name, p_url_image, CURRENT_TIMESTAMP, nextval('vehicle_brands_sequence'), p_odoo_id );
END IF;

END;
$procedure$
;