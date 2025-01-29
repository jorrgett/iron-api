CREATE OR REPLACE VIEW public.datato_vehicle_oil_chart
AS SELECT oil_change_histories.vehicle_id,
    oil_change_histories.service_id,
    oil_change_histories.change_km,
    oil_change_histories.change_date,
    oil_change_histories.life_span,
    oil_change_histories.change_next_days,
    COALESCE(oil_change_histories.change_km - lag(oil_change_histories.change_km, 1) OVER (PARTITION BY oil_change_histories.vehicle_id ORDER BY oil_change_histories.service_id), 0) AS km_traveled,
    COALESCE(oil_change_histories.change_date - lag(oil_change_histories.change_date, 1) OVER (PARTITION BY oil_change_histories.vehicle_id ORDER BY oil_change_histories.service_id), 0) AS days_passed,
    row_number() OVER (PARTITION BY oil_change_histories.vehicle_id ORDER BY oil_change_histories.service_id) AS rownumber
   FROM oil_change_histories
  WHERE oil_change_histories.service_state::text = 'done'::text
  ORDER BY oil_change_histories.vehicle_id, oil_change_histories.service_id;