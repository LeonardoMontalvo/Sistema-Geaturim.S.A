-- Table: formas_pago_mixto_nc

-- DROP TABLE formas_pago_mixto_nc;

CREATE TABLE formas_pago_mixto_nc
(
  id_formas_pago_mixto_nc integer NOT NULL,
  id_devolucion_compra integer,
  fecha_actual date,
  forma_pago text,
  tarjeta_credito text,
  numero_documento text,
  valor numeric,
  estado text,
  id_cuenta text,
  tipo_documento text,
  CONSTRAINT formas_pago_mixto_nc_pkey PRIMARY KEY (id_formas_pago_mixto_nc)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE formas_pago_mixto_nc
  OWNER TO postgres;


alter table detalle_devolucion_compra
add column cantidad_unidad text,
add column unidad_medida text;

-- cuenta nc proveedores
with x as (
INSERT INTO plan_cuentas(id_plan_cuentas, codigo_plan, descripcion, cuenta, estado)
VALUES (
(select max(id_plan_cuentas)+1 from  plan_cuentas), 
'2.1.03.01.06', '(-) N/C PROVEEDORES', 'M', 'Activo')returning id_plan_cuentas
)
INSERT INTO parametros(id_parametro, descripcion, valor, cuenta_debito, cuenta_credito)
VALUES (
(select max(id_parametro)+1 from  parametros), 
'NC PROVEEDORES', null, (select id_plan_cuentas from x), null);