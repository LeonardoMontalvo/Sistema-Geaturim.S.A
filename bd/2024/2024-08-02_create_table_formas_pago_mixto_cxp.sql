-- Table: formas_pago_mixto_cxp

-- DROP TABLE formas_pago_mixto_cxp;

CREATE TABLE formas_pago_mixto_cxp
(
  id_formas_pago_mixto_cxp integer NOT NULL,
  comprobante_pago integer,
  fecha_actual date,
  forma_pago text,
  numero_documento text,
  valor numeric,
  estado text,
  id_cuenta text,
  CONSTRAINT formas_pago_mixto_cxp_pkey PRIMARY KEY (id_formas_pago_mixto_cxp)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE formas_pago_mixto_cxp
  OWNER TO postgres;
