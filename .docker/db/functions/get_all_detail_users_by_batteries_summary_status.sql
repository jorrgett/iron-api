CREATE OR REPLACE FUNCTION public.get_all_detail_users_by_batteries_summary_status(p_status character varying)
 RETURNS TABLE(full_name character varying, email character varying, phone character varying, ubicacion character varying, plate character varying, battery_brand_name character varying, status character varying)
 LANGUAGE plpgsql
AS $function$
    BEGIN
        RETURN QUERY
		select 	details.full_name, 
				details.email, 
				details.phone, 
				details.ubicacion, 
				details.plate, 
				details.battery_brand_name,
				details.status
from 
(
select 	CAST(services_battery_complete.full_name AS varchar(191)) as full_name, 
		CAST(services_battery_complete.email AS varchar(191)) AS email, 
		CAST(services_battery_complete.phone AS varchar(50)) AS phone, 
		CAST(services_battery_complete.ubicacion AS varchar(50)) AS ubicacion,
		CAST(services_battery_complete.plate AS varchar(191)) AS plate, 
		CAST(services_battery_complete.battery_brand_name AS varchar(191)) AS battery_brand_name,
		CAST('Usuarios con baterías registradas'AS varchar(191)) AS status
  from public.services_battery_complete 
union all
select CAST(vehicle_without_services_battery.full_name AS varchar(191)) as full_name, 
		CAST(vehicle_without_services_battery.email AS varchar(191)) AS email, 
		CAST(vehicle_without_services_battery.phone AS varchar(50)) AS phone, 
		CAST(vehicle_without_services_battery.ubicacion AS varchar(50)) AS ubicacion,
		CAST(vehicle_without_services_battery.plate AS varchar(191)) AS plate, 
		CAST('N/D' AS varchar(191)) AS battery_brand_name,
		CAST('Usuarios sin baterías registradas' AS varchar(191)) AS status
  from vehicle_without_services_battery
union all
select CAST(services_battery_complete.full_name AS varchar(191)) as full_name, 
		CAST(services_battery_complete.email AS varchar(191)) AS email, 
		CAST(services_battery_complete.phone AS varchar(50)) AS phone, 
		CAST(services_battery_complete.ubicacion AS varchar(50)) AS ubicacion,
		CAST(services_battery_complete.plate AS varchar(191)) AS plate, 
		CAST(services_battery_complete.battery_brand_name AS varchar(191)) AS battery_brand_name,
		CAST('Usuarios con recargas' AS varchar(191)) AS status
  from public.services_battery_complete
  where services_battery_complete.battery_charged = true	) details
where details.status = p_status ;
    END;
$function$
;
