set search_path to imbacasa;
-- Table: parametros_cuentas_contables_iva

-- DROP TABLE parametros_cuentas_contables_iva;

CREATE TABLE parametros_cuentas_contables_iva
(
  id_taimpuesto integer NOT NULL,
  id_cuenta_iva_compras integer,
  CONSTRAINT pk_parametros_cc_iva PRIMARY KEY (id_taimpuesto),
  CONSTRAINT fk_parametros_cc_iva_tarifa_iva FOREIGN KEY (id_taimpuesto)
      REFERENCES tarifa_impuesto (id_taimpuesto) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
ALTER TABLE parametros_cuentas_contables_iva
  OWNER TO postgres;
