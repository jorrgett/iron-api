CREATE OR REPLACE FUNCTION public.get_all_detail_users_by_tires_lifespand_consumed_status(p_status character varying)
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
	select CAST('0%-25%' AS varchar(191)) AS status_temp, services_tires_complete.*
	  from services_tires_complete
	 where services_tires_complete.count_25 > 0   
	union all
	select 	CAST('26%-50%' AS varchar(191)) AS status_temp, services_tires_complete.*
	  from services_tires_complete
	 where services_tires_complete.count_50 > 0
	union all
	select 	CAST('51%-75%' AS varchar(191)) AS status_temp, services_tires_complete.*
	  from services_tires_complete
	 where services_tires_complete.count_75 > 0 
	union all
	select 	CAST('76%-100%' AS varchar(191)) AS status_temp, services_tires_complete.*
	  from services_tires_complete
	 where services_tires_complete.count_100 > 0
 ) subquery
 where status_temp = p_status;
    END;
$function$
;
