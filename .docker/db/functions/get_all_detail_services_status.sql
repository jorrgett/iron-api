CREATE OR REPLACE FUNCTION public.get_all_detail_services_status(p_user character varying, p_vehicle character varying, p_store character varying)
 RETURNS TABLE(service_id bigint, marca character varying, modelo character varying, placa character varying, dueno character varying, conductor character varying, fecha timestamp without time zone, estado character varying, tienda character varying)
 LANGUAGE plpgsql
AS $function$ BEGIN RETURN QUERY
SELECT subquery.service_id_output,
    subquery.marca_output,
    subquery.modelo_output,
    subquery.placa_output,
    subquery.dueno_output,
    subquery.conductor_output,
    subquery.fecha_output,
    subquery.estado_output,
    subquery.tienda_output
FROM (
        SELECT CAST(service_id_ AS bigint) AS service_id_output,
            CAST(marca_ AS varchar(191)) AS marca_output,
            CAST(modelo_ AS varchar(191)) AS modelo_output,
            CAST(placa_ AS varchar(191)) AS placa_output,
            CAST(dueno_ AS varchar(191)) AS dueno_output,
            CAST(conductor_ AS varchar(191)) AS conductor_output,
            CAST(fecha_ AS timestamp) AS fecha_output,
            CAST(estado_ AS varchar(50)) AS estado_output,
            CAST(tienda_ AS varchar(50)) AS tienda_output
        FROM (
                VALUES (
                        123,
                        'Chevrolet',
                        'Aveo',
                        'ABC123',
                        'Jesus Salas',
                        'Jesus Salas',
                        '2024-01-01',
                        'Hecho',
                        'inv.facol'
                    ),
                    (
                        123,
                        'Chevrolet',
                        'Aveo',
                        'ABC123',
                        'Jesus Salas',
                        'Jesus Salas',
                        '2024-01-01',
                        'Hecho',
                        'inv.facol'
                    ),
                    (
                        123,
                        'Chevrolet',
                        'Aveo',
                        'ABC123',
                        'Jesus Salas',
                        'Jesus Salas',
                        '2024-01-01',
                        'Hecho',
                        'inv.facol'
                    ),
                    (
                        123,
                        'Chevrolet',
                        'Aveo',
                        'ABC123',
                        'Jesus Salas',
                        'Jesus Salas',
                        '2024-01-01',
                        'Hecho',
                        'inv.facol'
                    ),
                    (
                        123,
                        'Chevrolet',
                        'Aveo',
                        'ABC123',
                        'Jesus Salas',
                        'Jesus Salas',
                        '2024-01-01',
                        'Hecho',
                        'inv.facol'
                    ),
                    (
                        123,
                        'Chevrolet',
                        'Aveo',
                        'ABC123',
                        'Jesus Salas',
                        'Jesus Salas',
                        '2024-01-01',
                        'Hecho',
                        'inv.facol'
                    ),
                    (
                        123,
                        'Chevrolet',
                        'Aveo',
                        'ABC123',
                        'Jesus Salas',
                        'Jesus Salas',
                        '2024-01-01',
                        'Hecho',
                        'inv.facol'
                    ),
                    (
                        123,
                        'Chevrolet',
                        'Aveo',
                        'ABC123',
                        'Jesus Salas',
                        'Jesus Salas',
                        '2024-01-01',
                        'Hecho',
                        'inv.facol'
                    ),
                    (
                        123,
                        'Chevrolet',
                        'Aveo',
                        'ABC123',
                        'Jesus Salas',
                        'Jesus Salas',
                        '2024-01-01',
                        'Hecho',
                        'inv.facol'
                    )
            ) AS t(
                service_id_,
                marca_,
                modelo_,
                placa_,
                dueno_,
                conductor_,
                fecha_,
                estado_,
                tienda_
            )
    ) AS subquery;
END;
$function$
;
