CREATE OR REPLACE FUNCTION public.get_all_detail_users_by_service_oil_change_status(p_status character varying)
 RETURNS TABLE(full_name character varying, email character varying, phone character varying, ubicacion character varying, plate character varying, vehicle_brand_name character varying, vehicle_model_name character varying, status character varying)
 LANGUAGE plpgsql
AS $function$
    BEGIN
        RETURN QUERY
			SELECT 
				CAST(subquery.full_name AS varchar(191)) as full_name, 
				CAST(subquery.email AS varchar(191)) AS email, 
				CAST(subquery.phone AS varchar(50)) AS phone, 
				CAST(subquery.ubicacion AS varchar(50)) AS ubicacion,
				CAST(subquery.plate AS varchar(191)) AS plate, 
				CAST(subquery.vehicle_brand_name AS varchar(191)) AS vehicle_brand_name,
				CAST(subquery.vehicle_model_name AS varchar(191)) AS vehicle_model_name,
				CAST(subquery.status_temp AS varchar(191)) AS status
			FROM(
				select 	CAST(subquery_1.full_name AS varchar(191)) as full_name, 
						CAST(subquery_1.email AS varchar(191)) AS email, 
						CAST(subquery_1.phone AS varchar(50)) AS phone, 
						CAST(subquery_1.ubicacion AS varchar(50)) AS ubicacion,
						CAST(subquery_1.plate AS varchar(191)) AS plate, 
						CAST(subquery_1.vehicle_brand_name AS varchar(191)) AS vehicle_brand_name,
						CAST(subquery_1.vehicle_model_name AS varchar(191)) AS vehicle_model_name,
						CAST('Requiere Cambio de Aceite' AS varchar(191)) as status_temp
	  			  from services_oil_complete subquery_1
	             where kms_recorridos >= life_span or elapsed_days >= 90
				 union all 
				select 	CAST(subquery_2.full_name AS varchar(191)) as full_name, 
						CAST(subquery_2.email AS varchar(191)) AS email, 
						CAST(subquery_2.phone AS varchar(50)) AS phone, 
						CAST(subquery_2.ubicacion AS varchar(50)) AS ubicacion,
						CAST(subquery_2.plate AS varchar(191)) AS plate, 
						CAST(subquery_2.vehicle_brand_name AS varchar(191)) AS vehicle_brand_name,
						CAST(subquery_2.vehicle_model_name AS varchar(191)) AS vehicle_model_name,
					   CAST('Aceite Saludable' AS varchar(191)) as status_temp
				  from services_oil_complete  subquery_2
				 where kms_recorridos < life_span and elapsed_days < 90
				 union all 
				select 	CAST(subquery_3.full_name AS varchar(191)) as full_name, 
						CAST(subquery_3.email AS varchar(191)) AS email, 
						CAST(subquery_3.phone AS varchar(50)) AS phone, 
						CAST(subquery_3.ubicacion AS varchar(50)) AS ubicacion,
						CAST(subquery_3.plate AS varchar(191)) AS plate, 
						CAST(subquery_3.vehicle_brand_name AS varchar(191)) AS vehicle_brand_name,
						CAST(subquery_3.vehicle_model_name AS varchar(191)) AS vehicle_model_name,
					   CAST('Sin Cambios Registrados' AS varchar(191))  as status_temp
				  from vehicle_without_services_oil subquery_3
			) subquery		
			WHERE subquery.status_temp = p_status;
    END;
$function$
;
