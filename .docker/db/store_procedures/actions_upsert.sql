DROP PROCEDURE IF EXISTS public.actions_upsert;

CREATE OR REPLACE PROCEDURE public.actions_upsert(
	IN p_id bigint, 
	IN p_description character varying, 
	IN p_statement character varying
	)
 LANGUAGE plpgsql
AS $procedure$

BEGIN

IF (EXISTS (SELECT "id" FROM "public"."actions" WHERE "id" = p_id)) THEN
	UPDATE "public"."actions" SET
		"description" = p_description,
		  "statement" = p_statement,
		"updated_at" = CURRENT_TIMESTAMP,
	    "sequence_id" = nextval('actions_sequence')
	WHERE
			"id" = p_id;
ELSE
	INSERT INTO "public"."actions" ("description", "statement", "created_at", "sequence_id")
	VALUES (p_description, p_statement, CURRENT_TIMESTAMP, nextval('actions_sequence') );
END IF;

END;
$procedure$
;