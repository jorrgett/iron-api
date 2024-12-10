CREATE OR REPLACE FUNCTION public.get_app_warnings_resume()
 RETURNS TABLE(id integer, warning_name character varying, quantity bigint, threshold bigint, result_color character varying)
 LANGUAGE plpgsql
AS $function$
	BEGIN 
	RETURN QUERY

		select 1 as id, cast('Neumático Autoregenerado' as varchar(50)) warning_name, 
				(select count(*) from  warning_autohealing_tires) as quantity, cast(500 as bigint) threshold, cast('#CA0000' as varchar(20)) result_color
		union all
		select 2 as id, cast('Warning Desconocido' as varchar(50)) warning_name, 
				(select count(*) from  services_oil_complete soc) as quantity, cast(300 as bigint) threshold, cast('' as varchar(20)) result_color;
			
	END;
$function$
;
