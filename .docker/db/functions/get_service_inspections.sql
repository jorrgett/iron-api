CREATE OR REPLACE FUNCTION public.get_service_inspections(p_user_id bigint, p_service_id bigint)
 RETURNS TABLE(flag_service_tires boolean, flag_service_oil boolean, flag_service_battery boolean, flag_service_balancing boolean, flag_service_rotation boolean, flag_service_alignment boolean)
 LANGUAGE plpgsql
AS $function$ BEGIN 
	RETURN QUERY
select 
	case when
		summarize.count_service_tires > 0 then true else false
	end flag_service_tires,
	case when
       summarize.count_service_oil > 0 then true else false
	end flag_service_oil,
	case when
       summarize.count_service_battery > 0 then true else false
	end flag_service_battery,
	case when
       summarize.count_service_balancing > 0 then true else false
	end flag_service_balancing,
	case when
       summarize.count_service_rotation > 0 then true else false
	end flag_service_rotation,
	case when
       summarize.count_service_alignment > 0 then true else false
	end flag_service_alignment
  from (
select sum (details.count_service_tires) count_service_tires, 
       sum (details.count_service_oil) count_service_oil, 
       sum (details.count_service_battery) count_service_battery, 
       sum (details.count_service_balancing) count_service_balancing, 
       sum (details.count_service_rotation) count_service_rotation,
       sum (details.count_service_alignment) count_service_alignment
       from (
			select count(*) count_service_tires, 
			       0 count_service_oil, 
			       0 count_service_battery, 
			       0 count_service_balancing, 
			       0 count_service_rotation,
			       0 count_service_alignment
			  from services_tires_histories_complete 
			 where res_partner_id = p_user_id
			   and service_id = p_service_id
			 union all 
			select 0 count_service_tires, 
			       count(*) count_service_oil, 
			       0 count_service_battery, 
			       0 count_service_balancing, 
			       0 count_service_rotation,
			       0 count_service_service_alignment
			  from services_oil_histories_complete 
			 where res_partner_id = p_user_id
			   and service_id = p_service_id
			 union all 
			select 0 count_service_tires, 
			       0 count_service_oil, 
			        count(*)  count_service_battery, 
			       0 count_service_balancing, 
			       0 count_service_rotation,
			       0 count_service_service_alignment
			  from services_battery_histories_complete 
			 where res_partner_id = p_user_id
			   and service_id = p_service_id
			  union all 
			select 0 count_service_tires, 
			       0 count_service_oil, 
			       0 count_service_battery, 
			       count(*) count_service_balancing, 
			       0 count_service_rotation,
			       0 count_service_service_alignment
			  from services_balancing_histories_complete
			 where res_partner_id = p_user_id
			   and service_id = p_service_id
			union all 
			select 0 count_service_tires, 
			       0 count_service_oil, 
			       0 count_service_battery, 
			       0 count_service_balancing, 
			       count(*) count_service_rotation,
			       0 count_service_service_alignment
			  from services_tires_histories_complete 
			 where (rotation_x or rotation_lineal)
			   and res_partner_id = p_user_id
			   and service_id = p_service_id
	) details   
) summarize
;
    END;
$function$
;
