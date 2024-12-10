CREATE OR REPLACE FUNCTION public.get_all_detail_users_by_service_balancing_status(p_status character varying)
 RETURNS TABLE(full_name character varying, email character varying, phone character varying, ubicacion character varying, plate character varying, vehicle_brand_name character varying, vehicle_model_name character varying)
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
				CAST(subquery.vehicle_model_name AS varchar(191)) AS vehicle_model_name
			FROM(
				select 	CAST(subquery_1.full_name AS varchar(191)) as full_name, 
						CAST(subquery_1.email AS varchar(191)) AS email, 
						CAST(subquery_1.phone AS varchar(50)) AS phone, 
						CAST(subquery_1.ubicacion AS varchar(50)) AS ubicacion,
						CAST(subquery_1.plate AS varchar(191)) AS plate, 
						CAST(subquery_1.vehicle_brand_name AS varchar(191)) AS vehicle_brand_name,
						CAST(subquery_1.vehicle_model_name AS varchar(191)) AS vehicle_model_name,
						'Requieren Servicio' status
	  			  from services_balancing_complete subquery_1
	             where kms_recorridos >= 5000 or elapsed_days >= 180
				 union all 
				select 	CAST(subquery_2.full_name AS varchar(191)) as full_name, 
						CAST(subquery_2.email AS varchar(191)) AS email, 
						CAST(subquery_2.phone AS varchar(50)) AS phone, 
						CAST(subquery_2.ubicacion AS varchar(50)) AS ubicacion,
						CAST(subquery_2.plate AS varchar(191)) AS plate, 
						CAST(subquery_2.vehicle_brand_name AS varchar(191)) AS vehicle_brand_name,
						CAST(subquery_2.vehicle_model_name AS varchar(191)) AS vehicle_model_name,
					   'No Requieren Servicio' status
				  from services_balancing_complete  subquery_2
				 where kms_recorridos < 5000 and elapsed_days < 180
				 union all 
				select 	CAST(subquery_3.full_name AS varchar(191)) as full_name, 
						CAST(subquery_3.email AS varchar(191)) AS email, 
						CAST(subquery_3.phone AS varchar(50)) AS phone, 
						CAST(subquery_3.ubicacion AS varchar(50)) AS ubicacion,
						CAST(subquery_3.plate AS varchar(191)) AS plate, 
						CAST(subquery_3.vehicle_brand_name AS varchar(191)) AS vehicle_brand_name,
						CAST(subquery_3.vehicle_model_name AS varchar(191)) AS vehicle_model_name,
					   'Sin Servicios Registrados' status
				  from vehicle_without_services_balancing subquery_3
			) subquery		
			WHERE subquery.status = p_status;
    END;
$function$
;
