CREATE OR REPLACE FUNCTION public.count_all_users_activity_status()
 RETURNS TABLE(months character varying, registered_users integer, users_using_app integer)
 LANGUAGE plpgsql
AS $function$
    BEGIN
        RETURN QUERY
            SELECT
                CAST(CASE subquery.month
                    WHEN 1 THEN 'Enero'
                    WHEN 2 THEN 'Febrero'
                    WHEN 3 THEN 'Marzo'
                    WHEN 4 THEN 'Abril'
                    WHEN 5 THEN 'Mayo'
                    WHEN 6 THEN 'Junio'
                    WHEN 7 THEN 'Julio'
                    WHEN 8 THEN 'Agosto'
                    WHEN 9 THEN 'Septiembre'
                    WHEN 10 THEN 'Octubre'
                    WHEN 11 THEN 'Noviembre'
                    WHEN 12 THEN 'Diciembre' END AS varchar(10)) AS months_output,
                CAST(COUNT(CASE WHEN page = 'R' THEN 1 END) AS integer) AS registered_users_output,
                CAST(COUNT(CASE WHEN page = 'U' THEN 1 END) AS integer) AS users_using_app_output
            FROM (
                SELECT
                    DISTINCT
                    user_id,
                    date_part('month', event_date) AS month,
                    CASE
                        WHEN page = 'webview' THEN 'R'
                        ELSE 'U'
                    END AS page,
                    object
                FROM public.heat_maps
                WHERE date_part('year', event_date) = date_part('year', current_date)
            ) subquery
            GROUP BY month
            ORDER BY month;
    END;
$function$
;
