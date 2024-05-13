set search_path to imbacasa;
-- Table: detalle_impuesto_producto_compra

-- DROP TABLE detalle_impuesto_producto_compra;

CREATE TABLE detalle_impuesto_producto_compra
(
  id_detalle_impuesto_producto_compra integer NOT NULL,
  cod_impuesto text,
  cod_tarifa text,
  tarifa numeric,
  valor_impuesto numeric,
  base_imponible numeric,
  id_detalle_compra integer,
  CONSTRAINT pk_detalle_impuesto_producto_compra PRIMARY KEY (id_detalle_impuesto_producto_compra)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE detalle_impuesto_producto_compra
  OWNER TO postgres;
