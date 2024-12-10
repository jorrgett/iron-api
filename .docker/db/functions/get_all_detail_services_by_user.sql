CREATE OR REPLACE FUNCTION public.get_all_detail_services_by_user(p_user_id bigint, p_service_id character varying)
 RETURNS TABLE(service_id bigint, vehicle_id bigint, marca character varying, modelo character varying, placa character varying, dueno character varying, conductor character varying, fecha date, estado character varying, tienda character varying, odometro double precision, odometer_unit character varying)
 LANGUAGE plpgsql
AS $function$ BEGIN 
	RETURN QUERY
SELECT services.odoo_id as service_id, 
			   services.vehicle_id, 	
			   services.vehicle_brand_name, 
			   services.vehicle_model_name,
			   services.plate,
			   services.owner_name,
			   services.driver_name,
			   services.service_date,
			   services.status,
			   services.store_name,
			   services.odometer,
			   vehicles.odometer_unit
		  from services_by_user_complete services
		  inner join vehicles on services.vehicle_id = vehicles.odoo_id
		 where (services.owner_id = p_user_id or driver_id = p_user_id) 
		   and CAST(services.service_id AS varchar) like CONCAT (p_service_id, '%') 
		 order by services.plate, services.service_date;
    END;
$function$
