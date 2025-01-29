CREATE OR REPLACE FUNCTION public.get_all_detail_users_by_tires_summary_physical_state(p_status character varying)
 RETURNS TABLE(full_name character varying, email character varying, phone character varying, ubicacion character varying, plate character varying, vehicle_brand_name character varying, vehicle_model_name character varying, status character varying)
 LANGUAGE plpgsql
AS $function$
    BEGIN
        RETURN QUERY
        
		select 
				CAST(subquery.full_name AS varchar(191)) as full_name, 
				CAST(subquery.email AS varchar(191)) AS email, 
				CAST(subquery.phone AS varchar(50)) AS phone, 
				CAST(subquery.ubicacion AS varchar(50)) AS ubicacion,
				CAST(subquery.plate AS varchar(191)) AS plate, 
				CAST(subquery.vehicle_brand_name AS varchar(191)) AS vehicle_brand_name,
				CAST(subquery.vehicle_model_name AS varchar(191)) AS vehicle_model_name,
				CAST(subquery.status_temp AS varchar(191)) AS status
  from (
	select 'Buen estado' status_temp, services_tires_complete.*
	  from services_tires_complete
	 where services_tires_complete.count_not_apply = 1  
	union all
	select 	'Abultamiento' status_temp, services_tires_complete.*
	  from services_tires_complete
	 where services_tires_complete.count_bulge = 1
	union all
	select 	'Perforaciones' status_temp, services_tires_complete.*
	  from services_tires_complete
	 where services_tires_complete.count_perforations = 1
	union all
	select 	'Vulcanizado' status_temp, services_tires_complete.*
	  from services_tires_complete
	 where services_tires_complete.count_vulcanized = 1
	union all
	select 	'Envejecimiento' status_temp, services_tires_complete.*
	  from services_tires_complete
	 where services_tires_complete.count_aging = 1
	union all
	select 	'Grietas' status_temp, services_tires_complete.*
	  from services_tires_complete
	 where services_tires_complete.count_cracked = 1
	union all
	select 	'Deformaciones' status_temp, services_tires_complete.*
	  from services_tires_complete
	 where services_tires_complete.count_deformations = 1
	union all
	select 	'Separaciones' status_temp, services_tires_complete.*
	  from services_tires_complete
	 where services_tires_complete.count_separations = 1
 ) subquery
 where status_temp = p_status;
    END;
$function$
;
