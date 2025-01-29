CREATE OR REPLACE FUNCTION public.get_all_detail_users_by_batteries_status(p_status character varying)
 RETURNS TABLE(full_name character varying, email character varying, phone character varying, ubicacion character varying, plate character varying, battery_brand_name character varying, status character varying)
 LANGUAGE plpgsql
AS $function$
    BEGIN
        RETURN QUERY

		select
			CAST(services_battery_complete.full_name AS varchar(191)) as full_name,
			CAST(services_battery_complete.email AS varchar(191)) AS email,
			CAST(services_battery_complete.phone AS varchar(50)) AS phone,
			CAST(services_battery_complete.ubicacion AS varchar(50)) AS ubicacion,
			CAST(services_battery_complete.plate AS varchar(191)) AS plate,
			CAST(services_battery_complete.battery_brand_name AS varchar(191)) AS battery_brand_name,
			CAST(services_battery_complete.health_status_final AS varchar(191)) AS status
		  from	services_battery_complete
 		 where health_status_final = p_status;
    END;
$function$
;
