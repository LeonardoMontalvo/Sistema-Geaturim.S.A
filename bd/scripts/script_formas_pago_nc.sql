-- Table: formas_pago_mixto_nv

-- DROP TABLE formas_pago_mixto_nv;

CREATE TABLE formas_pago_mixto_nv
(
  id_formas_pago_mixto_nv integer NOT NULL,
  id_devolucion_venta integer,
  fecha_actual date,
  forma_pago text,
  tarjeta_credito text,
  numero_documento text,
  valor numeric,
  estado text,
  id_cuenta text,
  tipo_documento text,
  CONSTRAINT formas_pago_mixto_nv_pkey PRIMARY KEY (id_formas_pago_mixto_nv)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE formas_pago_mixto_nv
  OWNER TO postgres;

-- cuenta nc clientes
with x as (
INSERT INTO plan_cuentas(id_plan_cuentas, codigo_plan, descripcion, cuenta, estado)
VALUES (
(select max(id_plan_cuentas)+1 from  plan_cuentas), 
'1.1.02.05.05', '(-) N/C CLIENTES', 'M', 'Activo')returning id_plan_cuentas
)
INSERT INTO parametros(id_parametro, descripcion, valor, cuenta_debito, cuenta_credito)
VALUES (
(select max(id_parametro)+1 from  parametros), 
'NC CLIENTES', null, null, (select id_plan_cuentas from x));

