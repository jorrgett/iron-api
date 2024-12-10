CREATE OR REPLACE FUNCTION public.count_all_users_tires_require_change()
 RETURNS TABLE(status character varying, cantidad bigint)
 LANGUAGE plpgsql
AS $function$
    BEGIN
    RETURN QUERY
        SELECT combined.status AS status, CAST(SUM(combined.cantidad) AS bigint) AS cantidad

        FROM
        (
            
			select subquery.status, sum (subquery.cantidad) cantidad
			  from (
			  	select CAST('Requieren Cambio' AS varchar(50)) status, 1 cantidad 
			  	  from services_tires_complete where count_100 > 0 or count_75 > 0 
			  	union all 
			  	select CAST('No Requieren Cambio' AS varchar(50)) status, 1 cantidad  
			  	  from services_tires_complete where count_25 > 0 or count_50 > 0 
			  ) subquery
			group by subquery.status

        UNION ALL 

        SELECT CAST(status_temp AS varchar(50)) AS status_tmp, CAST(cantidad_temp AS bigint) AS cantidad_temp
            FROM (
                VALUES 
                	('Requieren Cambio', 0),
                    ('No Requieren Cambio', 0)
            ) AS t(status_temp, cantidad_temp)

        ) AS combined
        GROUP BY combined.status;
END;
$function$
;
