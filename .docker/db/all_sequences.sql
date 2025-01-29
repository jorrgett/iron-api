--- CREATE SEQUENCES ----

-- CREATE SEQUENCE "actions_sequence" --------------------------
CREATE SEQUENCE IF NOT EXISTS "public"."actions_sequence"
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- CREATE SEQUENCE "categories_sequence" -----------------------
CREATE SEQUENCE IF NOT EXISTS "public"."categories_sequence"
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- CREATE SEQUENCE "error_logs_sequence" ------------------------
CREATE SEQUENCE IF NOT EXISTS "public"."error_logs_sequence"
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1;

-- CREATE SEQUENCE "odometers_sequence" ------------------------
CREATE SEQUENCE IF NOT EXISTS "public"."odometers_sequence"
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- CREATE SEQUENCE "oil_change_histories_sequence" -------------
CREATE SEQUENCE IF NOT EXISTS "public"."oil_change_histories_sequence"
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- CREATE SEQUENCE "product_categories_sequence" ---------------
CREATE SEQUENCE IF NOT EXISTS "public"."product_categories_sequence"
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- CREATE SEQUENCE "products_sequence" -------------------------
CREATE SEQUENCE IF NOT EXISTS "public"."products_sequence"
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- CREATE SEQUENCE "service_alignment_sequence" ----------------
CREATE SEQUENCE IF NOT EXISTS "public"."service_alignment_sequence"
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- CREATE SEQUENCE "service_balancing_sequence" ----------------
CREATE SEQUENCE "public"."service_balancing_sequence"
INCREMENT BY 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- CREATE SEQUENCE "service_battery_sequence" -------------------
CREATE SEQUENCE IF NOT EXISTS "public"."service_battery_sequence"
INCREMENT BY 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- CREATE SEQUENCE "service_items_actions_sequence" ------------
CREATE SEQUENCE IF NOT EXISTS "public"."service_items_actions_sequence"
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- CREATE SEQUENCE "service_items_sequence" --------------------
CREATE SEQUENCE IF NOT EXISTS "public"."service_items_sequence"
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- CREATE SEQUENCE "service_oil_sequence" -------------------------
CREATE SEQUENCE IF NOT EXISTS "public"."service_oil_sequence"
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- CREATE SEQUENCE "service_operators_sequence" ----------------
CREATE SEQUENCE IF NOT EXISTS "public"."service_operators_sequence"
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- CREATE SEQUENCE "service_tires_sequence" --------------------
CREATE SEQUENCE IF NOT EXISTS "public"."service_tires_sequence"
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- CREATE SEQUENCE "services_sequence" -------------------------
CREATE SEQUENCE IF NOT EXISTS "public"."services_sequence"
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- CREATE SEQUENCE "stores_sequence" ---------------------------
CREATE SEQUENCE IF NOT EXISTS "public"."stores_sequence"
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- CREATE SEQUENCE "tire_brands_sequence" ----------------------
CREATE SEQUENCE IF NOT EXISTS "public"."tire_brands_sequence"
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- CREATE SEQUENCE "tire_models_sequence" ----------------------
CREATE SEQUENCE IF NOT EXISTS "public"."tire_models_sequence"
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- CREATE SEQUENCE "tire_oem_depths_sequence" ------------------
CREATE SEQUENCE IF NOT EXISTS "public"."tire_oem_depths_sequence"
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- CREATE SEQUENCE "tire_otd_standars_sequence" ----------------
CREATE SEQUENCE IF NOT EXISTS "public"."tire_otd_standars_sequence"
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- CREATE SEQUENCE "tire_sizes_sequence" -----------------------
CREATE SEQUENCE IF NOT EXISTS "public"."tire_sizes_sequence"
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- CREATE SEQUENCE "vehicle_brands_sequence" -------------------
CREATE SEQUENCE IF NOT EXISTS "public"."vehicle_brands_sequence"
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- CREATE SEQUENCE "vehicle_models_sequence" -------------------
CREATE SEQUENCE IF NOT EXISTS "public"."vehicle_models_sequence"
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- CREATE SEQUENCE "vehicle_summaries_sequence" ----------------
CREATE SEQUENCE IF NOT EXISTS "public"."vehicle_summaries_sequence"
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- CREATE SEQUENCE "vehicle_tire_histories_sequence" -----------
CREATE SEQUENCE IF NOT EXISTS "public"."vehicle_tire_histories_sequence"
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- CREATE SEQUENCE "vehicle_tire_summaries_sequence" -----------
CREATE SEQUENCE IF NOT EXISTS "public"."vehicle_tire_summaries_sequence"
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- CREATE SEQUENCE "vehicles_sequence" -------------------------
CREATE SEQUENCE IF NOT EXISTS "public"."vehicles_sequence"
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;