CREATE OR REPLACE FUNCTION public.count_all_users_batteries_physical_state()
 RETURNS TABLE(users bigint, status character varying)
 LANGUAGE plpgsql
AS $function$ 
BEGIN 
	RETURN QUERY
        select 	CAST(subquery.quantity AS bigint ) AS users_output,
        CAST(subquery.status AS varchar(50)) AS status_output
  from (        
	select 	'Buen estado' status, sum (services_battery_complete.count_buen_estado) as quantity
	  from services_battery_complete
	union all
	select 	'Fuga de líquido' status, sum (services_battery_complete.count_fuga_de_liquido) as quantity
	  from services_battery_complete
	union all
	select 	'Bornes sulfatados y/o dañados' status, sum (services_battery_complete.count_bornes_sulfatados) as quantity
	  from services_battery_complete
	union all
	select 	'Cables partidos y/o sulfatados' status, sum (services_battery_complete.count_cables_partidos) as quantity
	  from services_battery_complete
	union all
	select 	'Carcasa partida o impactada' status, sum (services_battery_complete.count_carcasa_partida) as quantity
	  from services_battery_complete
	union all
	select 	'Batería Inflada' status, sum (services_battery_complete.count_bateria_inflada) as quantity
	  from services_battery_complete  
	) subquery;  
    END;
$function$
;
