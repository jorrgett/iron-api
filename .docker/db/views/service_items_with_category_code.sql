CREATE OR REPLACE VIEW public.service_items_with_category_code
AS SELECT si.id,
    si.odoo_id,
    si.service_id,
    si.type,
    si.product_id,
    si.display_name,
    si.qty,
    si.operator_id,
    si.created_at,
    si.updated_at,
    si.sequence_id,
    c.code AS category_code,
    so.tire_brand_id as oil_brand_id,
    tb.odoo_id as brand_id,
    tb.name as brand_name
   FROM service_items si
     JOIN products p ON p.odoo_id = si.product_id
     left outer join service_oil so on so.service_id = si.service_id 
     left outer join tire_brands tb on so.tire_brand_id = tb.odoo_id 
     LEFT outer JOIN product_categories pc ON p.product_category_id = pc.odoo_id
     LEFT outer JOIN categories c ON c.id = pc.category_id