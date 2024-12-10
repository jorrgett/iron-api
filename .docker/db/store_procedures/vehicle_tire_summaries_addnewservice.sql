CREATE OR REPLACE PROCEDURE public.vehicle_tire_summaries_addnewservice(
    IN p_vehicle_id bigint
    )
 LANGUAGE plpgsql
AS $procedure$
DECLARE
    item RECORD;
    accum_km_traveled float8;
    accum_days_total float8;
    prom_tire_km_month float8;
    prom_tire_mm_x_visit float8;
    months_to_tire_unsafe float8;
    projected_tire_visits float8;
    estimated_months_tire_visits float8;
    life_span_consumed float8;
BEGIN
    FOR item IN ( SELECT DISTINCT
            vehicle_id,
            tire_location,
            FIRST_VALUE(odometer) OVER (PARTITION BY vehicle_id, tire_location ORDER BY vehicle_id, tire_location, service_id) AS odometer1,
            LAST_VALUE(odometer) OVER (PARTITION BY vehicle_id, tire_location ORDER BY vehicle_id, tire_location) AS odometer2,
            FIRST_VALUE(service_date) OVER (PARTITION BY vehicle_id, tire_location ORDER BY vehicle_id, tire_location, service_id) AS service_date1,
            LAST_VALUE(service_date) OVER (PARTITION BY vehicle_id, tire_location ORDER BY vehicle_id, tire_location) AS service_date2,
            FIRST_VALUE(tread_depth) OVER (PARTITION BY vehicle_id, tire_location ORDER BY vehicle_id, tire_location, service_id) AS tread_depth1,
            LAST_VALUE(tread_depth) OVER (PARTITION BY vehicle_id, tire_location ORDER BY vehicle_id, tire_location) AS tread_depth2,
            LAST_VALUE(lifespan_consumed) OVER (PARTITION BY vehicle_id, tire_location ORDER BY vehicle_id, tire_location) AS life_span_consumed,
            COUNT(*) OVER (PARTITION BY vehicle_id, tire_location)::integer AS NumVisits,
          --SUM(months_between_visits) OVER (PARTITION BY vehicle_id, tire_location) AS months_between_visits,
            AVG(months_between_visits) OVER (PARTITION BY vehicle_id, tire_location) AS months_between_visits
        FROM
            public.vehicle_tire_histories
        WHERE
            vehicle_id = p_vehicle_id)
        LOOP
            accum_km_traveled = item.odometer2 - item.odometer1;
            accum_days_total = item.service_date2 - item.service_date1;
           	--validate if accum_days_total is bigger than 0 to avoid division by zero in calculations
           	if accum_days_total > 0 then
            	prom_tire_km_month = accum_km_traveled / (accum_days_total / 30);
	            prom_tire_mm_x_visit = (item.tread_depth1 - item.tread_depth2) / (accum_days_total / 30);
	            if prom_tire_mm_x_visit > 0 then
					months_to_tire_unsafe =  item.tread_depth2 / prom_tire_mm_x_visit;
				else
					months_to_tire_unsafe = 0;
				end if;
           	else
	           	prom_tire_km_month = 0;
	           	prom_tire_mm_x_visit = 0;
	           	months_to_tire_unsafe = 0;
           	end if;
			projected_tire_visits = 12 - item.NumVisits;
          --estimated_months_tire_visits = (months_to_tire_unsafe - item.months_between_visits) / projected_tire_visits;
            estimated_months_tire_visits = item.months_between_visits;
            life_span_consumed = item.life_span_consumed;
            RAISE NOTICE 'data : % % % % % % % % %', item.vehicle_id, item.tire_location, accum_km_traveled, accum_days_total, prom_tire_km_month, prom_tire_mm_x_visit, months_to_tire_unsafe, projected_tire_visits, estimated_months_tire_visits;
            CALL public.vehicle_tire_summaries_upsert (item.vehicle_id, item.tire_location, prom_tire_km_month, prom_tire_mm_x_visit, months_to_tire_unsafe, projected_tire_visits, estimated_months_tire_visits, accum_km_traveled, accum_days_total, life_span_consumed);
        END LOOP;
EXCEPTION
    WHEN division_by_zero THEN
        RAISE NOTICE 'División por cero';
END;
$procedure$
;