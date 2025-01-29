CREATE OR REPLACE FUNCTION public.count_all_users_tires_summary_physical_state()
 RETURNS TABLE(users bigint, status character varying)
 LANGUAGE plpgsql
AS $function$
    BEGIN
        RETURN QUERY
        
        select 	CAST(subquery.quantity AS bigint ) AS users_output,
        CAST(subquery.status AS varchar(50)) AS status_output
  from (        
	select 	'Buen estado' status, sum (services_tires_complete.count_not_apply) as quantity
	  from services_tires_complete
	union all
	select 	'Abultamiento' status, sum (services_tires_complete.count_bulge) as quantity
	  from services_tires_complete
	union all
	select 	'Perforaciones' status, sum (services_tires_complete.count_perforations) as quantity
	  from services_tires_complete
	union all
	select 	'Vulcanizado' status, sum (services_tires_complete.count_vulcanized) as quantity
	  from services_tires_complete
	union all
	select 	'Envejecimiento' status, sum (services_tires_complete.count_aging) as quantity
	  from services_tires_complete
	union all
	select 	'Grietas' status, sum (services_tires_complete.count_cracked) as quantity
	  from services_tires_complete
	union all
	select 	'Deformaciones' status, sum (services_tires_complete.count_deformations) as quantity
	  from services_tires_complete  
	union all
	select 	'Separaciones' status, sum (services_tires_complete.count_separations) as quantity
	  from services_tires_complete  
	) subquery;  
    END;
$function$
;
