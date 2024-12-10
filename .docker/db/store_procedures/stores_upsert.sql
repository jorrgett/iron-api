CREATE OR REPLACE PROCEDURE public.stores_upsert(
	IN p_name character varying, 
	IN p_street character varying, 
	IN p_street2 character varying, 
	IN p_city character varying, 
	IN p_state character varying, 
	IN p_country character varying, 
	IN p_phone character varying, 
	IN p_odoo_id bigint
	)
 LANGUAGE plpgsql
AS $procedure$

BEGIN

IF (EXISTS (SELECT "odoo_id" FROM "public"."stores" WHERE "odoo_id" = p_odoo_id)) THEN
	UPDATE "public"."stores" SET
			   "name" = p_name,
			 "street" = p_street,
			"street2" = p_street2,
			   "city" = p_city,
			  "state" = p_state,
			"country" = p_country,
			  "phone" = p_phone,
		"updated_at" = CURRENT_TIMESTAMP,
		"sequence_id" = nextval('stores_sequence')
	  WHERE "odoo_id" = p_odoo_id;
ELSE
	INSERT INTO "public"."stores" ("name", "street", "street2", "city", "state", "country", "phone", "created_at", "sequence_id", "odoo_id")
	VALUES ( p_name, p_street, p_street2, p_city, p_state, p_country, p_phone, CURRENT_TIMESTAMP, nextval('stores_sequence'), p_odoo_id );
END IF;

END;
$procedure$
;