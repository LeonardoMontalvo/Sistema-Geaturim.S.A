

CREATE TABLE formas_pago_mixto_cxc
(
  id_formas_pago_mixto_cxc integer NOT NULL,
  comprobante_pago integer,
  fecha_actual date,
  forma_pago text,
  numero_documento text,
  valor numeric,
  estado text,
  id_cuenta text,
    fecha_forma date,
  CONSTRAINT formas_pago_mixto_cxc_pkey PRIMARY KEY (id_formas_pago_mixto_cxc)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE formas_pago_mixto_cxc
  OWNER TO postgres;
