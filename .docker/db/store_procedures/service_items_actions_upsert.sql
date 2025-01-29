CREATE OR REPLACE PROCEDURE public.service_items_actions_upsert(
	IN p_product_id bigint, 
	IN p_code integer
	)
 LANGUAGE plpgsql
AS $procedure$

BEGIN

IF (EXISTS (SELECT "code" FROM "public"."service_items_actions" WHERE "code" = p_code)) THEN
	UPDATE "public"."service_items_actions" SET
		 "product_id" = p_product_id,
		  "code" = p_code,
		"updated_at" = CURRENT_TIMESTAMP,
		"sequence_id" = nextval('service_items_actions_sequence')
	WHERE
			"product_id" = p_product_id AND "code" = p_code;
ELSE
	INSERT INTO "public"."service_items_actions" ( "product_id", "code", "created_at", "sequence_id")
	VALUES ( p_product_id, p_code, CURRENT_TIMESTAMP, nextval('service_items_actions_sequence') );
END IF;

END;
$procedure$
;