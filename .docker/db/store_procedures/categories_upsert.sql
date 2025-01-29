CREATE OR REPLACE PROCEDURE public.categories_upsert(
	IN p_name character varying, 
	IN p_action_id bigint, 
	IN p_parent_id bigint, 
	IN p_id bigint
	)
 LANGUAGE plpgsql
AS $procedure$

BEGIN

IF (EXISTS (SELECT "id" FROM "public"."categories" WHERE "id" = p_id)) THEN
	UPDATE "public"."categories" SET
			   "name" = p_name,
		  "action_id" = p_action_id,
		  "parent_id" = p_parent_id,
		"updated_at" = CURRENT_TIMESTAMP,
		"sequence_id" = nextval('categories_sequence')
	WHERE
			"id" = p_id;
ELSE
	INSERT INTO "public"."categories" ( "name", "action_id", "parent_id", "created_at", "sequence_id")
	VALUES ( p_name, p_action_id, p_parent_id, CURRENT_TIMESTAMP, nextval('categories_sequence') );
END IF;

END;
$procedure$
;