CREATE OR REPLACE FUNCTION public.get_all_detail_users_by_batteries_physical_state(p_status character varying)
 RETURNS TABLE(full_name character varying, email character varying, phone character varying, ubicacion character varying, plate character varying, battery_brand_name character varying, status character varying)
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
				CAST(subquery.battery_brand_name AS varchar(191)) AS battery_brand_name,
				CAST(subquery.status_temp AS varchar(191)) AS status
  from (
	select 'Buen estado' status_temp, services_battery_complete.*
	  from services_battery_complete
	 where services_battery_complete.count_buen_estado = 1  
	union all
	select 	'Fuga de líquido' status_temp, services_battery_complete.*
	  from services_battery_complete
	 where services_battery_complete.count_fuga_de_liquido = 1
	union all
	select 	'Bornes sulfatados y/o dañados' status, services_battery_complete.*
	  from services_battery_complete
	 where services_battery_complete.count_bornes_sulfatados = 1
	union all
	select 	'Cables partidos y/o sulfatados' status_temp, services_battery_complete.*
	  from services_battery_complete
	 where services_battery_complete.count_cables_partidos = 1
	union all
	select 	'Carcasa partida o impactada' status_temp, services_battery_complete.*
	  from services_battery_complete
	 where services_battery_complete.count_carcasa_partida = 1
	union all
	select 	'Batería Inflada' status_temp, services_battery_complete.*
	  from services_battery_complete
	 where services_battery_complete.count_bateria_inflada = 1
 ) subquery
 where status_temp = p_status;
    END;
$function$
;
