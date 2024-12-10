CREATE OR REPLACE PROCEDURE public.service_items_upsert(
	IN p_service_id bigint, 
	IN p_type character, 
	IN p_product_id bigint, 
	IN p_display_name character varying, 
	IN p_qty integer, 
	IN p_operator_id bigint, 
	IN p_odoo_id bigint
	)
 LANGUAGE plpgsql
AS $procedure$

BEGIN

IF (EXISTS (SELECT "odoo_id" FROM "public"."service_items" WHERE "odoo_id" = p_odoo_id AND "service_id" = p_service_id)) THEN
	UPDATE "public"."service_items" SET
				"type" = p_type,
		  "product_id" = p_product_id,
		"display_name" = p_display_name,
				 "qty" = p_qty,
		 "operator_id" = p_operator_id,
		 "updated_at" = CURRENT_TIMESTAMP,
	     "sequence_id" = nextval('service_items_sequence')
	WHERE
			"odoo_id" = p_odoo_id AND "service_id" = p_service_id;
ELSE
	INSERT INTO "public"."service_items" ("service_id", "type", "product_id", "display_name", "qty", "operator_id", "created_at", "sequence_id", "odoo_id")
	VALUES (p_service_id, p_type, p_product_id, p_display_name, p_qty, p_operator_id , CURRENT_TIMESTAMP, nextval('service_items_sequence'), p_odoo_id );
END IF;

END;
$procedure$
;