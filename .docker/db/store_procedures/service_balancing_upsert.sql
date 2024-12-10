CREATE OR REPLACE PROCEDURE public.service_balancing_upsert(
    IN p_odoo_id bigint, 
    IN p_service_id bigint, 
    IN p_location character varying, 
    IN p_lead_used double precision, 
    IN p_type_lead character varying, 
    IN p_balanced boolean, 
    IN p_wheel_good_state boolean, 
    IN p_wheel_scratched boolean, 
    IN p_wheel_cracked boolean, 
    IN p_wheel_bent boolean
    )
 LANGUAGE plpgsql
AS $procedure$

BEGIN

IF (EXISTS (SELECT "odoo_id" FROM "public"."service_balancing" WHERE "odoo_id" = p_odoo_id)) THEN
    UPDATE "public"."service_balancing" SET
           "service_id" = p_service_id,
          "location" = p_location,
           "lead_used" = p_lead_used,
          "type_lead" = p_type_lead,
          "balanced" = p_balanced,
          "wheel_good_state" = p_wheel_good_state,
          "wheel_scratched" = p_wheel_scratched,
          "wheel_cracked" = p_wheel_cracked,
          "wheel_bent" = p_wheel_bent,
          "updated_at" = CURRENT_TIMESTAMP,
          "sequence_id" = nextval('service_balancing_sequence')
      WHERE "odoo_id" = p_odoo_id;
ELSE
    INSERT INTO "public"."service_balancing" ("sequence_id","odoo_id","service_id", "location", "lead_used", "type_lead", "balanced", "wheel_good_state", "wheel_scratched", "wheel_cracked", "wheel_bent", "created_at")
    VALUES(nextval('service_balancing_sequence'), p_odoo_id, p_service_id, p_location, p_lead_used, p_type_lead, p_balanced, p_wheel_good_state, p_wheel_scratched, p_wheel_cracked, p_wheel_bent, CURRENT_TIMESTAMP);

END IF;

END;
$procedure$
;