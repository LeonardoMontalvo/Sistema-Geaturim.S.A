set search_path to imbacasa;
alter table parametros_cuentas_contables_iva
add column id_cuenta_iva_ventas integer;
alter table parametros_cuentas_contables_iva
add column id_cuenta_ventas integer;
alter table parametros_cuentas_contables_iva
add column id_cuenta_dev_ventas integer;
