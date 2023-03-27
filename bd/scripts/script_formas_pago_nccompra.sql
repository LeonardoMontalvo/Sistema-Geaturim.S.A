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