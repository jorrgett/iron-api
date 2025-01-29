CREATE OR REPLACE PROCEDURE public.product_categories_upsert(
	IN p_name character varying, 
	IN p_odoo_id bigint
	)
 LANGUAGE plpgsql
AS $procedure$

BEGIN

IF (EXISTS (SELECT "odoo_id" FROM "public"."product_categories" WHERE "odoo_id" = p_odoo_id)) THEN
	UPDATE "public"."product_categories" SET
			   "name" = p_name,
		 "updated_at" = CURRENT_TIMESTAMP,
		"sequence_id" = nextval('product_categories_sequence')
	WHERE
			"odoo_id" = p_odoo_id;
ELSE
	INSERT INTO "public"."product_categories" ("name", "created_at", "sequence_id", "odoo_id")
	VALUES (p_name, CURRENT_TIMESTAMP, nextval('product_categories_sequence'), p_odoo_id );


END IF;

END;
$procedure$
;