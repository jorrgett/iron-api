CREATE OR REPLACE FUNCTION public.count_all_users_service_oil_change_status()
 RETURNS TABLE(users bigint, status character varying)
 LANGUAGE plpgsql
AS $function$ BEGIN 
	RETURN QUERY
        SELECT CAST(SUM(combined.users) AS bigint), CAST(combined.status AS VARCHAR(191))
          FROM (
			select count(*) users, 'Requiere Cambio de Aceite' status
  			  from services_oil_complete sbc 
             where kms_recorridos >= life_span or elapsed_days >= 90
			 union all 
			select count(*) users, 'Aceite Saludable' status
			  from services_oil_complete sbc 
			 where kms_recorridos < life_span and elapsed_days < 90
			 union all 
			select count(*) users, 'Sin Cambios Registrados' status
			  from vehicle_without_services_oil
			UNION ALL
			SELECT CAST(users_temp AS bigint) AS users_temp, CAST(status_temp AS varchar(191))
                FROM (
                    VALUES 
                        (0, 'Aceite Saludable'),
                        (0, 'Requiere Cambio de Aceite'),
                        (0, 'Sin Cambios Registrados') 
                ) AS t(users_temp, status_temp)
	  	) AS combined
		GROUP BY combined.status;
    END;
$function$
;
