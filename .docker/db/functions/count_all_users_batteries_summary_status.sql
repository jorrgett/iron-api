CREATE OR REPLACE FUNCTION public.count_all_users_batteries_summary_status()
 RETURNS TABLE(users bigint, status character varying)
 LANGUAGE plpgsql
AS $function$
    BEGIN
        RETURN QUERY

        select cast (count (*) as bigint) users_output, CAST('Usuarios con baterías registradas' AS varchar(50)) status_output
  from public.services_battery_complete
union all
select cast (count (*) as bigint) users_output, CAST('Usuarios sin baterías registradas' AS varchar(50)) status_output
  from vehicle_without_services_battery
union all
select cast (sum (count_recarga_bateria) as bigint) users_output, CAST('Usuarios con recargas' AS varchar(50)) status_output
  from public.services_battery_complete ;
    END;
$function$
;
