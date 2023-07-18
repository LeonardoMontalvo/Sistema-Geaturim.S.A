set search_path to public;
alter table factura_venta
add column desc_prod numeric,
add column desc_fact numeric;

alter table facturas_novalidas
add column desc_prod numeric,
add column desc_fact numeric;