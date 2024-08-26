-- Table: detalle_impuesto_producto_proforma

-- DROP TABLE detalle_impuesto_producto_proforma;

CREATE TABLE detalle_impuesto_producto_proforma
(
  id_detalle_impuesto_producto_proforma integer NOT NULL,
  cod_impuesto text,
  cod_tarifa text,
  tarifa numeric,
  valor_impuesto numeric,
  base_imponible numeric,
  id_detalle_proforma integer,
  CONSTRAINT detalle_impuesto_producto_proforma_pkey PRIMARY KEY (id_detalle_impuesto_producto_proforma)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE detalle_impuesto_producto_proforma
  OWNER TO postgres;
