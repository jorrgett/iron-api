CREATE OR REPLACE FUNCTION public.count_all_batteries_status()
 RETURNS TABLE(status_battery character varying, quantity bigint)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    SELECT combined.health_status_final, CAST(SUM(combined.quantity) AS bigint) AS quantity
from (
select CAST(sb.health_status_final AS varchar(191)), CAST(count (sb.health_status_final) AS bigint) as quantity
  from services_battery_complete sb
-- where sb.status_battery in ('Buen estado', 'Nueva', 'Requiere Carga', 'Dañada','Reemplazar')
 group by sb.health_status_final
union all 
SELECT CAST(status_battery_temp AS varchar(191)), CAST(cantidad_temp AS bigint) AS cantidad_temp
    FROM (
        VALUES ('Buen estado', 0),
               ('Recargar', 0),
               ('Dañada', 0),
               ('Reemplazar', 0)
    ) AS t(status_battery_temp, cantidad_temp)
) combined
GROUP BY combined.health_status_final;
END;
$function$
;