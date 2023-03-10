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
