#!/bin/sh

# Verifica si PostgreSQL está instalado
if ! command -v psql > /dev/null 2>&1; then
    echo "PostgreSQL aun no se ha inicializado..."
fi

# Ejecuta el comando psql
psql ABCopilot_db admin -f /home/db/all_sequences.sql
psql ABCopilot_db admin -f /home/db/categories.sql
psql ABCopilot_db admin -f /home/db/service_items_actions.sql
psql ABCopilot_db admin -f /home/db/tire_otd_standars.sql

# Functions 
psql ABCopilot_db admin -f /home/db/count_all_batteries_status.sql
psql ABCopilot_db admin -f /home/db/count_all_users_activity_status.sql
psql ABCopilot_db admin -f /home/db/count_all_users_batteries_physical_state.sql
psql ABCopilot_db admin -f /home/db/count_all_users_batteries_summary_status.sql
psql ABCopilot_db admin -f /home/db/count_all_users_service_balancing_status.sql
psql ABCopilot_db admin -f /home/db/count_all_users_service_oil_change_status.sql
psql ABCopilot_db admin -f /home/db/count_all_users_tires_lifespand_consumed_status.sql
psql ABCopilot_db admin -f /home/db/count_all_users_tires_require_change.sql
psql ABCopilot_db admin -f /home/db/count_all_users_tires_summary_physical_state.sql
psql ABCopilot_db admin -f /home/db/get_all_detail_services_by_user.sql
psql ABCopilot_db admin -f /home/db/get_all_detail_services_status.sql
psql ABCopilot_db admin -f /home/db/get_all_detail_users_by_batteries_physical_state.sql
psql ABCopilot_db admin -f /home/db/get_all_detail_users_by_batteries_status.sql
psql ABCopilot_db admin -f /home/db/get_all_detail_users_by_batteries_summary_status.sql
psql ABCopilot_db admin -f /home/db/get_all_detail_users_by_service_balancing_status.sql
psql ABCopilot_db admin -f /home/db/get_all_detail_users_by_service_oil_change_status.sql
psql ABCopilot_db admin -f /home/db/get_all_detail_users_by_tires_lifespand_consumed_status.sql
psql ABCopilot_db admin -f /home/db/get_all_detail_users_by_tires_require_change.sql
psql ABCopilot_db admin -f /home/db/get_all_detail_users_by_tires_summary_physical_state.sql
psql ABCopilot_db admin -f /home/db/get_all_detail_vehicles_by_user.sql
psql ABCopilot_db admin -f /home/db/get_app_warnings_resume.sql
psql ABCopilot_db admin -f /home/db/get_service_inspections.sql


# Store procedures
psql ABCopilot_db admin -f /home/db/actions_upsert.sql
psql ABCopilot_db admin -f /home/db/categories_upsert.sql
psql ABCopilot_db admin -f /home/db/log_message.sql
psql ABCopilot_db admin -f /home/db/oil_change_histories_addnewservice.sql
psql ABCopilot_db admin -f /home/db/oil_change_histories_addnewvisits.sql
psql ABCopilot_db admin -f /home/db/oil_change_histories_change_next_date.sql
psql ABCopilot_db admin -f /home/db/oil_change_histories_upsert.sql
psql ABCopilot_db admin -f /home/db/service_items_actions_upsert.sql
psql ABCopilot_db admin -f /home/db/tire_oem_depth_upsert.sql
psql ABCopilot_db admin -f /home/db/vehicle_summaries_update_vehicle.sql
psql ABCopilot_db admin -f /home/db/vehicle_summaries_upsert.sql
psql ABCopilot_db admin -f /home/db/vehicle_tire_histories_addnewservice.sql
psql ABCopilot_db admin -f /home/db/vehicle_tire_summaries_addnewservice.sql
psql ABCopilot_db admin -f /home/db/vehicle_tire_summaries_upsert.sql

# Views 
psql ABCopilot_db admin -f /home/db/datato_oil_change_histories.sql
psql ABCopilot_db admin -f /home/db/datato_vehicle_oil_chart.sql
psql ABCopilot_db admin -f /home/db/datato_vehicle_summaries.sql
psql ABCopilot_db admin -f /home/db/last_service_balancing_by_vehicle.sql
psql ABCopilot_db admin -f /home/db/last_service_battery_by_vehicle.sql
psql ABCopilot_db admin -f /home/db/last_service_oil_by_vehicle.sql
psql ABCopilot_db admin -f /home/db/last_service_tires_by_vehicle.sql
psql ABCopilot_db admin -f /home/db/last_service_by_vehicle.sql.sql
psql ABCopilot_db admin -f /home/db/services_balancing_complete.sql
psql ABCopilot_db admin -f /home/db/services_balancing_histories_complete.sql
psql ABCopilot_db admin -f /home/db/services_battery_complete.sql
psql ABCopilot_db admin -f /home/db/services_battery_histories_complete.sql
psql ABCopilot_db admin -f /home/db/services_by_user_complete.sql
psql ABCopilot_db admin -f /home/db/services_oil_complete.sql
psql ABCopilot_db admin -f /home/db/services_oil_histories_complete.sql
psql ABCopilot_db admin -f /home/db/services_tires_complete.sql
psql ABCopilot_db admin -f /home/db/services_tires_histories_complete.sql
psql ABCopilot_db admin -f /home/db/vehicle_without_services_balancing.sql
psql ABCopilot_db admin -f /home/db/vehicle_without_services_battery.sql
psql ABCopilot_db admin -f /home/db/vehicle_without_services_oil.sql
psql ABCopilot_db admin -f /home/db/warning_autohealing_tires.sql
rm -rf /home/db/*
