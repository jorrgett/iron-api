CREATE OR REPLACE PROCEDURE public.vehicle_tire_histories_addnewservice(
    IN p_vehicle_id bigint, 
    IN p_service_id bigint
    )
 LANGUAGE plpgsql
AS $procedure$
DECLARE
    last_service_id bigint;
    item RECORD;
    item2 RECORD;
    Perf RECORD;
    p_otd float8;
    safe_depth float8;
    lifespan_consumed float8;
    km_traveled int4;
    mm_consumed float8;
    months_between_visits float8;
    vperformance_index int4;
	prom_performance_index float8;
    km_proyected int4;
    odometer_estimated int4;
    p_message VARCHAR(512);
    p_current_sp VARCHAR(512);


begin
	--Comments:
    ----20-03-2024: Se modifica la formula para los kilometros proyectados segun tarjeta AC-451
	
    -- Borrar si existe en vehicle_tire_histories el Id de servicio definido por p_service_id
    p_current_sp := 'SP: vehicle_tire_histories_addnewservice: ';
    DELETE FROM vehicle_tire_histories vth
    WHERE vth.service_id = p_service_id AND vth.service_id = (SELECT MAX(service_id) FROM vehicle_tire_histories);
    -- Obtener el último servicio registrado
    SELECT
        coalesce(max(vth.service_id),0)
    FROM
        vehicle_tire_histories vth INTO last_service_id
    WHERE
        vth.vehicle_id = p_vehicle_id;
    -- validar que el id de servicio a procesar sea mayor al ultimo id de servicio procesado. esto para garantizar que el historial no se se altere por el reprocesamiento
    IF p_service_id > last_service_id THEN
        -- Obtener valor otd_standar

        -- Data actual en services -> service_tires
        FOR item IN (
            SELECT
                s.vehicle_id,
                s.odoo_id AS service_id,
                s."date" AS service_date,
                s.odometer,
                st."location" AS tire_location,
                st."depth" AS tread_depth,
                st."depth" - 1.6 AS safe_depth,
                ts."name",
                CASE
                    WHEN st.depth_original > 0 THEN st.depth_original
                    WHEN st.depth_original <= 0 THEN
                        CASE
                            WHEN tos.otd > 0 THEN tos.otd
                            WHEN tos.otd <= 0 THEN 0
                        END
                    ELSE 0
                END AS  otd,
                st.tire_change

            FROM
                public.services s
                INNER JOIN public.service_tires st ON s.odoo_id = st.service_id
                INNER JOIN public.tire_sizes ts ON st.tire_size_id = ts.odoo_id
                /* si no existe una profundidad standart para la medida no se debe registrar nada en el historico */
                left outer JOIN public.tire_otd_standars tos ON trim(ts."name") = trim(tos.tire_size)
            WHERE
                s.vehicle_id = p_vehicle_id
                AND s.odoo_id = p_service_id
            ORDER BY
                s.vehicle_id,
                st."location",
                s.odoo_id)
                LOOP
                    -- se debe validar que si el valor de tread_depth que se esta recibiendo en el servicio es mayor que el otd standart para esa medida no se llene el historial ya que esta condicion causa negativos que inhabilitan la proyecciones.

                    IF item.otd > 0 THEN

                        IF item.otd >= item.tread_depth  THEN

                            IF item.tire_change = TRUE THEN
								RAISE NOTICE 'Cambio de caucho ln 81';

                                DELETE FROM vehicle_tire_histories vehicle_th
                                    WHERE
                                    vehicle_th.vehicle_id = item.vehicle_id AND
                                    vehicle_th.tire_location = item.tire_location;

                                DELETE FROM vehicle_tire_summaries vehicle_ts
                                    WHERE
                                    vehicle_ts.vehicle_id = item.vehicle_id AND
                                    vehicle_ts.tire_location = item.tire_location;

                            ELSE
                                -- NO HUBO CAMBIO DE CAUCHO
                            END IF;

                            RAISE NOTICE 'item1 : last_service_id=% vehicle_id=% service_id=% tire_location=% tread_depth=% safe_depth=%', last_service_id, item.vehicle_id, item.service_id, item.tire_location, item.tread_depth, item.safe_depth;
                            -- Init variables
                            safe_depth = item.safe_depth;
                            lifespan_consumed = 1 - (item.tread_depth / item.otd);
                            RAISE NOTICE 'lifespan_consumed (%) = 1 - (item.tread_depth (%) / item.otd (%));', lifespan_consumed, item.tread_depth, item.otd;

                            -- lifespan_consumed = item.lifespan_consumed; SE CALCULA LIFE SPAND DESDE EL LOOP

                            -- lifespan_consumed = 1 - ( item.tread_depth / item.otd);

                            km_traveled = 0;
                            mm_consumed = 0;
                            months_between_visits = 0;
                            vperformance_index = 0;
                            prom_performance_index = 0;
                            km_proyected = 0;
                            odometer_estimated = 0;
                            -- Data en vehicle_tire_histories
                            FOR item2 IN (
                                SELECT
                                    vth.id,
                                    vth.vehicle_id,
                                    vth.service_id,
                                    vth.tire_location,
                                    vth.odometer,
                                    vth.tread_depth,
                                    vth.service_date
                                FROM
                                    vehicle_tire_histories vth
                                WHERE
                                    vth.vehicle_id = item.vehicle_id
                                    AND vth.tire_location = item.tire_location
                                    AND vth.service_id = last_service_id -- Id de servicio anterior
                                ORDER BY
                                    vth.vehicle_id,
                                    vth.tire_location,
                                    vth.service_id)
                                    LOOP

                                        RAISE NOTICE 'item2 : tire_location=% odometer=% tread_depth=% service_date=%', item2.tire_location, item2.odometer, item2.tread_depth, item2.service_date;
                                        -- Formulas
										RAISE NOTICE 'Formulas';
										
                                        km_traveled = item.odometer - item2.odometer;
										RAISE NOTICE 'km_traveled (%) = item.odometer (%) - item2.odometer (%)', km_traveled, item.odometer, item2.odometer;
										
                                        mm_consumed = item2.tread_depth - item.tread_depth;
										RAISE NOTICE 'mm_consumed (%) = item2.tread_depth (%) - item.tread_depth (%)', mm_consumed, item2.tread_depth, item.tread_depth;
										
                                        --fecha2 2023-01-05, fecha 1 2023-01-20, restado 15
                                        --RAISE NOTICE 'fecha2 %, fecha 1 %, restado %, resultado %', item2.service_date, item.service_date, item.service_date - item2.service_date, (item.service_date - item2.service_date) / 30::float8;
										
										IF mm_consumed > 0 THEN
											vperformance_index = km_traveled / mm_consumed;
										ELSE
											vperformance_index = 0;
										END IF;
										RAISE NOTICE 'vperformance_index (%) = km_traveled (%) / mm_consumed (%)', vperformance_index, km_traveled, mm_consumed;

									    -- AC-555
										FOR Perf IN (
											SELECT
												sum(vth.performance_index) AS Suma,
												count(*) AS Cantidad
											FROM
												public.vehicle_tire_histories vth
											WHERE
												vth.vehicle_id = item.vehicle_id
												AND vth.tire_location = item.tire_location
												AND vth.performance_index > 0)
											loop
												RAISE NOTICE 'Suma %, Cantidad %', Perf.Suma, Perf.Cantidad;
											    prom_performance_index = (coalesce(Perf.Suma, 0) + vperformance_index) / (Perf.Cantidad + 1);
											    RAISE NOTICE 'prom_performance_index = %', prom_performance_index;
											END LOOP;
									
                                        -- km_proyected = floor(vperformance_index * item.otd)::integer;
                                        km_proyected = floor(prom_performance_index * item.otd)::integer;
                                       
										RAISE NOTICE 'km_proyected (%) = floor(vperformance_index (%) * item.otd (%))::integer;', km_proyected, vperformance_index, item.otd;
										
                                        odometer_estimated = km_proyected + item.odometer;
										RAISE NOTICE 'odometer_estimated (%) = km_proyected (%) + item.odometer (%)', odometer_estimated, km_proyected, item.odometer;
										
                                        --lifespan_consumed = 1 - (item.tread_depth / item.otd);
										--RAISE NOTICE 'lifespan_consumed (%) = 1 - (item.tread_depth (%) / item.otd (%));', lifespan_consumed, item.tread_depth, item.otd;
										
                                        --RAISE NOTICE 'mm_consumed % % %', item2.tread_depth, item.tread_depth, mm_consumed;
                                        months_between_visits = (item.service_date::date - item2.service_date::date) / 30::float8;
										RAISE NOTICE 'months_between_visits (%) = (item.service_date::date (%) - item2.service_date::date (%)) / 30::float8 (%)', months_between_visits, item.service_date::date, item2.service_date::date, (item.service_date::date - item2.service_date::date) / 30::float8;

                                    END LOOP;
                            RAISE NOTICE 'Data ; % % % % % % % %', safe_depth, lifespan_consumed, km_traveled, mm_consumed, months_between_visits, vperformance_index, km_proyected, odometer_estimated;
                            -- Insert Data
                            INSERT INTO public.vehicle_tire_histories (id, vehicle_id, service_id, service_date, odometer, tire_location, otd, tread_depth, mm_consumed, performance_index, prom_performance_index, km_traveled, km_proyected, odometer_estimated, safe_depth, lifespan_consumed, months_between_visits, created_at, sequence_id)
                                VALUES (nextval('vehicle_tire_histories_id_seq'::regclass), item.vehicle_id, item.service_id, item.service_date, item.odometer, item.tire_location, item.otd, item.tread_depth, mm_consumed, vperformance_index, prom_performance_index, km_traveled, km_proyected, odometer_estimated, safe_depth, lifespan_consumed, months_between_visits, CURRENT_TIMESTAMP, nextval('vehicle_tire_histories_sequence'))
                            ON CONFLICT (vehicle_id, service_id, tire_location)
                            /* or you may use [DO NOTHING;] */
                                DO UPDATE SET
                                    service_date = EXCLUDED.service_date, odometer = EXCLUDED.odometer, otd = EXCLUDED.otd, tread_depth = EXCLUDED.tread_depth, mm_consumed = EXCLUDED.mm_consumed, performance_index = EXCLUDED.performance_index, prom_performance_index = EXCLUDED.prom_performance_index, km_traveled = EXCLUDED.km_traveled, km_proyected = EXCLUDED.km_proyected, odometer_estimated = EXCLUDED.odometer_estimated, safe_depth = EXCLUDED.safe_depth, lifespan_consumed = EXCLUDED.lifespan_consumed, months_between_visits = EXCLUDED.months_between_visits, sequence_id = nextval('vehicle_tire_histories_sequence'), updated_at = CURRENT_TIMESTAMP;
							RAISE NOTICE 'Debe haber insertado o modificado';
							RAISE NOTICE '';
                        ELSE
							RAISE NOTICE 'ERROR item.otd no es mayor o igual que item.tread_depth, ln 79';
                            p_message := p_current_sp || 'Tread_depth = ' || item.tread_depth || ' es mayor que el otd standart = ' || item.otd || ', p_service_id = ' || p_service_id;
                            CALL log_message(p_message);

                        END IF;

                    ELSE

                    -- NO PUEDO REALIZAR REGISTROS CON OTD 0
						RAISE NOTICE 'ERROR item.otd no es mayor que 0, ln 77';

                        p_message:=  p_current_sp || 'No puedo realizar registros con OTD 0, p_service_id = ' || p_service_id;
                        CALL log_message(p_message);

                    END IF;
                END LOOP;
    ELSE


        p_message:=  p_current_sp || 'p_service_id > last_service_id, p_service_id = ' || p_service_id;
        CALL log_message(p_message);

    END IF;
EXCEPTION
    WHEN division_by_zero THEN

        p_message:=  p_current_sp || 'Division por Cero, p_service_id = ' || p_service_id;
        CALL log_message(p_message);

        /*
        RAISE NOTICE 'División por cero';
        */
END;
$procedure$
;