CREATE OR REPLACE FUNCTION public.count_all_users_service_balancing_status()
 RETURNS TABLE(users bigint, status character varying)
 LANGUAGE plpgsql
AS $function$
    BEGIN
        RETURN QUERY
        SELECT CAST(SUM(combined.users) AS bigint), CAST(combined.status AS VARCHAR(191))
          FROM (
			select count(*) users, 'Requieren Servicio' status
  			  from services_balancing_complete sbc 
             where kms_recorridos >= 5000 or elapsed_days >= 180
			 union all 
			select count(*) users, 'No Requieren Servicio' status
			  from services_balancing_complete sbc 
			 where kms_recorridos < 5000 and elapsed_days < 180
			 union all 
			select count(*) users, 'Sin Servicios Registrados' status
			  from vehicle_without_services_balancing
			UNION ALL
			SELECT CAST(users_temp AS bigint) AS users_temp, CAST(status_temp AS varchar(191))
                FROM (
                    VALUES 
                        (0, 'No Requieren Servicio'),
                        (0, 'Requieren Servicio'),
                        (0, 'Sin Servicios Registrados') 
                ) AS t(users_temp, status_temp)
	  	) AS combined
		GROUP BY combined.status;
    END;
$function$
;
