CREATE OR REPLACE PROCEDURE public.service_operators_upsert(
	IN p_vat character, 
	IN p_name character, 
	IN p_odoo_id bigint
	)
 LANGUAGE plpgsql
AS $procedure$

BEGIN

IF (EXISTS (SELECT "odoo_id" FROM "public"."service_operators" WHERE "odoo_id" = p_odoo_id)) THEN
	UPDATE "public"."service_operators" SET
				 "vat" = p_vat,
				"name" = p_name,
		 "updated_at" = CURRENT_TIMESTAMP,
	     "sequence_id" = nextval('service_operators_sequence')
	WHERE
			"odoo_id" = p_odoo_id;
ELSE
	INSERT INTO "public"."service_operators" ("vat", "name", "created_at", "sequence_id", "odoo_id")
	VALUES (p_vat, p_name, CURRENT_TIMESTAMP, nextval('service_operators_sequence'), p_odoo_id );
END IF;

END;
$procedure$
;