CREATE OR REPLACE PROCEDURE public.service_oil_upsert(
  IN p_odoo_id bigint,
  IN p_service_id bigint,
  IN p_tire_brand_id bigint,
  IN p_oil_viscosity character varying,
  IN p_type_oil character varying,
  IN p_life_span integer
  )
 LANGUAGE plpgsql
AS $procedure$

BEGIN

IF (EXISTS (SELECT "odoo_id" FROM "public"."service_oil" WHERE "odoo_id" = p_odoo_id)) THEN
    UPDATE "public"."service_oil" SET
           "service_id" = p_service_id,
          "tire_brand_id" = p_tire_brand_id,
           "oil_viscosity" = p_oil_viscosity,
         "type_oil" = p_type_oil,
               "life_span" = p_life_span,
        "updated_at" = CURRENT_TIMESTAMP,
        "sequence_id" = nextval('service_oil_sequence')
      WHERE "odoo_id" = p_odoo_id;
ELSE
    INSERT INTO "public"."service_oil" ("odoo_id", "service_id", "tire_brand_id", "oil_viscosity", "type_oil", "life_span", "created_at", "sequence_id")
    VALUES(p_odoo_id, p_service_id, p_tire_brand_id, p_oil_viscosity, p_type_oil, p_life_span,  CURRENT_TIMESTAMP, nextval('service_oil_sequence'));

END IF;

END;
$procedure$;
