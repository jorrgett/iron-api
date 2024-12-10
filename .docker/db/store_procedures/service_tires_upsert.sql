CREATE OR REPLACE PROCEDURE public.service_tires_upsert(
IN p_service_id integer, 
IN p_location character varying, 
IN p_depth double precision, 
IN p_starting_pressure double precision, 
IN p_finishing_pressure double precision, 
IN p_dot character varying, 
IN p_tire_brand_id integer, 
IN p_tire_model_id integer, 
IN p_tire_size_id integer, 
IN p_odoo_id integer, 
IN p_regular boolean, 
IN p_staggered boolean, 
IN p_central boolean, 
IN p_right_shoulder boolean, 
IN p_left_shoulder boolean, 
IN p_not_apply boolean, 
IN p_bulge boolean, 
IN p_perforations boolean, 
IN p_vulcanized boolean, 
IN p_aging boolean, 
IN p_cracked boolean, 
IN p_deformations boolean, 
IN p_separations boolean, 
IN p_tire_change boolean, 
IN p_depth_original double precision
)
 LANGUAGE plpgsql
AS $procedure$
BEGIN
    IF (EXISTS (
        SELECT
            "odoo_id"
        FROM
            "public"."service_tires"
        WHERE
            "odoo_id" = p_odoo_id)) THEN
        UPDATE
            "public"."service_tires"
        SET
            "service_id" = p_service_id,
            "location" = p_location,
            "depth" = p_depth,
            "starting_pressure" = p_starting_pressure,
            "finishing_pressure" = p_finishing_pressure,
            "dot" = p_dot,
            "tire_brand_id" = p_tire_brand_id,
            "tire_model_id" = p_tire_model_id,
            "tire_size_id" = p_tire_size_id,
            "regular" = p_regular,
            "staggered" = p_staggered,
            "central" = p_central,
            "right_shoulder" = p_right_shoulder,
            "left_shoulder" = p_left_shoulder,
            "not_apply" = p_not_apply,
            "bulge" = p_bulge,
            "perforations" = p_perforations,
            "vulcanized" = p_vulcanized,
            "aging" = p_aging,
            "cracked" = p_cracked,
            "deformations" = p_deformations,
            "separations" = p_separations,
            "updated_at" = CURRENT_TIMESTAMP,
            "sequence_id" = NEXTVAL('service_tires_sequence'),
			"tire_change" = p_tire_change,
            "depth_original" = p_depth_original
        WHERE
            "odoo_id" = p_odoo_id;
    ELSE
        INSERT INTO "public"."service_tires" ("service_id", "location", "depth", "starting_pressure", "finishing_pressure", "dot", "tire_brand_id", "tire_model_id", "tire_size_id", "created_at", "sequence_id", "odoo_id", "regular", "staggered", "central", "right_shoulder", "left_shoulder", "not_apply", "bulge", "perforations", "vulcanized", "aging", "cracked", "deformations", "separations", "tire_change", "depth_original")
            VALUES (p_service_id, p_location, p_depth, p_starting_pressure, p_finishing_pressure, p_dot, p_tire_brand_id, p_tire_model_id, p_tire_size_id, CURRENT_TIMESTAMP, NEXTVAL('service_tires_sequence'), p_odoo_id, p_regular, p_staggered, p_central, p_right_shoulder, p_left_shoulder, p_not_apply, p_bulge, p_perforations, p_vulcanized, p_aging, p_cracked, p_deformations, p_separations, p_tire_change, p_depth_original);
    END IF;
END;
$procedure$
;