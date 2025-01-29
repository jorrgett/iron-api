CREATE OR REPLACE PROCEDURE public.service_alignment_upsert(
	IN p_service_id bigint, 
	IN p_eje character varying, 
	IN p_valor character varying, 
	IN p_full_convergence_d character varying, 
	IN p_semiconvergence_izq_d character varying, 
	IN p_semiconvergence_der_d character varying, 
	IN p_camber_izq_d character varying, 
	IN p_camber_der_d character varying, 
	IN p_odoo_id bigint
	)
 LANGUAGE plpgsql
AS $procedure$

BEGIN

IF (EXISTS (SELECT "odoo_id" FROM "public"."service_alignment" WHERE "odoo_id" = p_odoo_id)) THEN
		UPDATE "public"."service_alignment" SET
		"service_id" = p_service_id,
		"eje" = p_eje,
		"valor" = p_valor,
		"full_convergence_d" = p_full_convergence_d,
		"semiconvergence_izq_d" = p_semiconvergence_izq_d,
		"semiconvergence_der_d" = p_semiconvergence_der_d,
		"camber_izq_d" = p_camber_izq_d,
		"camber_der_d" = p_camber_der_d,
		"updated_at" = CURRENT_TIMESTAMP,
		"sequence_id" = nextval('service_alignment_sequence')
	  WHERE "odoo_id" = p_odoo_id;
ELSE
	INSERT INTO "public"."service_alignment" ("service_id", "eje", "valor", "full_convergence_d", "semiconvergence_izq_d", "semiconvergence_der_d", "camber_izq_d", "camber_der_d", "created_at", "sequence_id", "odoo_id")
	VALUES(p_service_id, p_eje, p_valor, p_full_convergence_d, p_semiconvergence_izq_d, p_semiconvergence_der_d, p_camber_izq_d, p_camber_der_d, CURRENT_TIMESTAMP, nextval('service_alignment_sequence'), p_odoo_id);


END IF;

END;
$procedure$
;