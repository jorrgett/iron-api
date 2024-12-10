CREATE OR REPLACE PROCEDURE public.tire_sizes_upsert(
	IN p_name character varying, 
	IN p_odoo_id bigint
	)
 LANGUAGE plpgsql
AS $procedure$

BEGIN

IF (EXISTS (SELECT "odoo_id" FROM "public"."tire_sizes" WHERE "odoo_id" = p_odoo_id)) THEN
	UPDATE "public"."tire_sizes" SET
			   "name" = p_name,
		"updated_at" = CURRENT_TIMESTAMP,
		"sequence_id" = nextval('tire_sizes_sequence')
	WHERE
			"odoo_id" = p_odoo_id;
ELSE
	INSERT INTO "public"."tire_sizes" ("name", "created_at", "sequence_id", "odoo_id")
	VALUES (p_name, CURRENT_TIMESTAMP, nextval('tire_sizes_sequence'), p_odoo_id );
END IF;

END;
$procedure$
;