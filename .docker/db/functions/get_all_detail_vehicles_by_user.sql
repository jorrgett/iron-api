CREATE OR REPLACE FUNCTION public.get_all_detail_vehicles_by_user(p_user_id integer)
 RETURNS TABLE(vehicle_id bigint, placa character varying, marca character varying, modelo character varying, color character varying, anio integer, transmision character varying, combustible character varying, odometro double precision, odometer_unit character varying)
 LANGUAGE plpgsql
AS $function$
	begin
		        RETURN QUERY

		select distinct v.odoo_id vehicle_id,
			v.plate,
	        CASE
	            WHEN vb.name IS NULL THEN 'N/D'::character varying
	            ELSE vb.name
	        END AS vehicle_brand_name,
	        CASE
	            WHEN vm.name IS NULL THEN 'N/D'::character varying
	            ELSE vm.name
	        END AS vehicle_model_name,
	        v.color, 
	        v.year,
	        CASE
	            WHEN v.transmission = 'manual' THEN 'Sincrónico'::character varying
	            WHEN v.transmission = 'automatic' THEN 'Automático'::character varying
	            WHEN v.transmission = 'dual' THEN 'Dual'::character varying
	            ELSE 'N/D'::character varying
	        END AS transmission,
	        CASE
	            WHEN v.fuel = 'glp' THEN 'GLP'::character varying
	            WHEN v.fuel = 'gasolin' THEN 'Gasolina'::character varying
	            WHEN v.fuel = 'diesel' THEN 'Diesel'::character varying
	            WHEN v.fuel = 'electric' THEN 'Eléctrico'::character varying
	            ELSE 'N/D'::character varying
	        END AS fuel, 
	        v.odometer,
	        v.odometer_unit
		    from services services 
			JOIN vehicles v ON services.vehicle_id = v.odoo_id
			JOIN vehicle_brands vb ON v.vehicle_brand_id = vb.odoo_id
			JOIN vehicle_models vm ON v.vehicle_model_id = vm.odoo_id
           where services.owner_id = p_user_id or services.driver_id = p_user_id;			
	END;
$function$
