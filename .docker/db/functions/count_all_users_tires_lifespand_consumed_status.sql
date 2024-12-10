CREATE OR REPLACE FUNCTION public.count_all_users_tires_lifespand_consumed_status()
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
			  	select CAST('0%-25%' AS varchar(10)) status, count_25 cantidad from services_tires_complete where count_25 > 0 
			  	union all 
			  	select CAST('26%-50%' AS varchar(10)) status, count_50 cantidad  from services_tires_complete where count_50 > 0 
			  	union all 
			  	select CAST('51%-75%' AS varchar(10)) status, count_75 cantidad  from services_tires_complete where count_75 > 0 
			  	union all 
			  	select CAST('76%-100%' AS varchar(10)) status, count_100 cantidad  from services_tires_complete where count_100 > 0 
			  ) subquery
			group by subquery.status

        UNION ALL 

        SELECT CAST(status_temp AS varchar(10)) AS status_tmp, CAST(cantidad_temp AS bigint) AS cantidad_temp
            FROM (
                VALUES 
                	('0%-25%', 0),
                    ('26%-50%', 0),
                    ('51%-75%', 0),
                    ('76%-100%', 0)
            ) AS t(status_temp, cantidad_temp)

        ) AS combined
        GROUP BY combined.status;
END;
$function$
;
