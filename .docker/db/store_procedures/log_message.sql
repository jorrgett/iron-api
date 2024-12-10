CREATE OR REPLACE PROCEDURE public.log_message(
    IN p_message character varying
    )
 LANGUAGE plpgsql
AS $procedure$
DECLARE
    project VARCHAR(50) := 'iron-staging-db';
BEGIN
    RAISE LOG '% - % - %', project, p_message, current_timestamp;
END;
$procedure$
;