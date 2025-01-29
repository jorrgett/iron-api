CREATE OR REPLACE PROCEDURE public.service_battery_upsert(
  IN p_odoo_id bigint, 
  IN p_battery_brand_id bigint, 
  IN p_battery_model_id bigint, 
  IN p_date_of_purchase timestamp without time zone, 
  IN p_warranty_date timestamp without time zone, 
  IN p_service_id bigint, 
  IN p_amperage character varying, 
  IN p_alternator_voltage double precision, 
  IN p_battery_voltage double precision, 
  IN p_status_battery character varying, 
  IN p_status_alternator character varying, 
  IN p_good_condition boolean, 
  IN p_liquid_leakage boolean, 
  IN p_corroded_terminals boolean, 
  IN p_frayed_cables boolean, 
  IN p_inflated boolean, 
  IN p_cracked_case boolean, 
  IN p_new_battery boolean, 
  IN p_replaced_battery boolean, 
  IN p_serial_product character varying, 
  IN p_starting_current double precision, 
  IN p_accumulated_load_capacity double precision, 
  IN p_health_status character varying, 
  IN p_health_percentage double precision, 
  IN p_battery_charged boolean
  )
 LANGUAGE plpgsql
AS $procedure$

DECLARE
  p_health_status_final character varying(50); -- Declaración de la variable p_health_status_final

BEGIN

--SI DETECTA UN CAMBIO DE BATERIA BORRA TODOS LOS REGISTROS service_battery PARA ESE VEHICULO
IF (p_replaced_battery) THEN

    WITH s1 AS (
      SELECT vehicle_id
      FROM services
      WHERE odoo_id = p_service_id
    )

    DELETE FROM service_battery
    WHERE service_id IN (
      SELECT odoo_id
      FROM services
      JOIN s1 ON services.vehicle_id = s1.vehicle_id
    );

END IF;

IF (p_health_percentage >= 75) THEN

    IF(p_battery_voltage >  12.4) THEN
      p_health_status_final := 'Buen estado';

    ELSIF(p_battery_voltage >= 12 AND p_battery_voltage <= 12.4) THEN
      p_health_status_final := 'Requiere carga';

    ELSIF(p_battery_voltage >= 10.5 AND p_battery_voltage <= 12) THEN
      p_health_status_final := 'Requiere carga';

    ELSIF(p_battery_voltage < 10.5) THEN
      p_health_status_final := 'Dañada';

    ELSE
      p_health_status_final := 'Dañada';

    END IF;

ELSIF (p_health_percentage <= 74) THEN

    IF(p_battery_voltage >  12.4) THEN
      p_health_status_final := 'Deficiente';

    ELSIF(p_battery_voltage >= 12 AND p_battery_voltage <= 12.4) THEN
      p_health_status_final := 'Deficiente';

    ELSIF(p_battery_voltage >= 10.5 AND p_battery_voltage < 12) THEN
      p_health_status_final := 'Deficiente';

    ELSIF(p_battery_voltage < 10.5) THEN
      p_health_status_final := 'Dañada';

    ELSE
      p_health_status_final := 'Dañada';

    END IF;
ELSE
    p_health_status_final := 'Dañada';

END IF;

-- SI EXISTE EL ID EN ODOO SE ACTUALIZA
IF (EXISTS (SELECT "odoo_id" FROM "public"."service_battery" WHERE "odoo_id" = p_odoo_id)) THEN
    UPDATE "public"."service_battery" SET
          "battery_brand_id" = p_battery_brand_id,
          "battery_model_id" = p_battery_model_id,
          "date_of_purchase" = p_date_of_purchase,
          "warranty_date" = p_warranty_date,
          "service_id" = p_service_id,
          "amperage" = p_amperage,
          "alternator_voltage" = p_alternator_voltage,
          "battery_voltage" = p_battery_voltage,
          "status_battery" = p_status_battery,
          "status_alternator" = p_status_alternator,
          "good_condition" = p_good_condition,
          "liquid_leakage" = p_liquid_leakage,
          "corroded_terminals" = p_corroded_terminals,
          "frayed_cables" = p_frayed_cables,
          "inflated" = p_inflated,
          "cracked_case" = p_cracked_case,
          "new_battery" = p_new_battery,
          "replaced_battery" = p_replaced_battery,
          "updated_at" = CURRENT_TIMESTAMP,
          "sequence_id" = nextval('service_battery_sequence'),
          "serial_product" = p_serial_product,
          "starting_current" = p_starting_current,
          "accumulated_load_capacity" = p_accumulated_load_capacity,
          "health_status" = p_health_status,
          "health_percentage" = p_health_percentage,
          "health_status_final" = p_health_status_final,
          "battery_charged" = p_battery_charged

      WHERE "odoo_id" = p_odoo_id;

-- SI NO EXISTE SE INSERTA
ELSE
  INSERT INTO "public"."service_battery"  (
        "sequence_id",
        "odoo_id",
        "battery_brand_id",
        "battery_model_id",
        "date_of_purchase",
        "warranty_date",
        "service_id",
        "amperage",
        "alternator_voltage",
        "battery_voltage",
        "status_battery",
        "status_alternator",
        "good_condition",
        "liquid_leakage",
        "corroded_terminals",
        "frayed_cables",
        "inflated",
        "cracked_case",
        "new_battery",
        "replaced_battery",
        "created_at",
        "serial_product",
        "starting_current",
        "accumulated_load_capacity",
        "health_status",
        "health_percentage",
        "health_status_final",
        "battery_charged")

      VALUES(
        nextval('service_battery_sequence'),
        p_odoo_id,
        p_battery_brand_id,
        p_battery_model_id,
        p_date_of_purchase,
        p_warranty_date,
        p_service_id,
        p_amperage,
        p_alternator_voltage,
        p_battery_voltage,
        p_status_battery,
        p_status_alternator,
        p_good_condition,
        p_liquid_leakage,
        p_corroded_terminals,
        p_frayed_cables,
        p_inflated,
        p_cracked_case,
        p_new_battery,
        p_replaced_battery,
        CURRENT_TIMESTAMP,
        p_serial_product,
        p_starting_current,
        p_accumulated_load_capacity,
        p_health_status,
        p_health_percentage,
        p_health_status_final,
        p_battery_charged);

END IF;

END;
$procedure$
;