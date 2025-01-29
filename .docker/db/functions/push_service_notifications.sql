CREATE OR REPLACE FUNCTION public.push_service_notifications()
 RETURNS void
 LANGUAGE plpgsql
AS $function$
BEGIN
    PERFORM public.push_service_alignment_notifications();
    PERFORM public.push_service_balancing_notifications();
    PERFORM public.push_service_battery_notifications();
    PERFORM public.push_service_oil_notifications();
    PERFORM public.push_service_rotation_notifications();
    PERFORM public.push_physical_state_service_tire_notifications();
    PERFORM public.push_service_tire_notifications();

    RAISE NOTICE 'Todas las tareas de notificaciones han sido ejecutadas con éxito.';
END;
$function$