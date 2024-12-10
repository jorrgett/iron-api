CREATE OR REPLACE PROCEDURE public.products_upsert(
	IN p_name character varying, 
	IN p_otd double precision, 
	IN p_life_span integer, 
	IN p_life_span_unit character varying, 
	IN p_product_category_id bigint, 
	IN p_odoo_id bigint
	)
 LANGUAGE plpgsql
AS $procedure$

BEGIN

IF (EXISTS (SELECT "odoo_id" FROM "public"."products" WHERE "odoo_id" = p_odoo_id)) THEN
	UPDATE "public"."products" SET
			   "name" = p_name,
				"otd" = p_otd,
		  "life_span" = p_life_span,
	 "life_span_unit" = p_life_span_unit,
"product_category_id" = p_product_category_id,
		"updated_at" = CURRENT_TIMESTAMP,
		"sequence_id" = nextval('products_sequence')
	WHERE
			"odoo_id" = p_odoo_id;
ELSE
	INSERT INTO "public"."products" ("name", "otd", "life_span", "life_span_unit", "product_category_id", "created_at", "sequence_id", "odoo_id")
	VALUES (p_name, p_otd, p_life_span, p_life_span_unit, p_product_category_id, CURRENT_TIMESTAMP, nextval('products_sequence'), p_odoo_id );
END IF;

END;
$procedure$
;