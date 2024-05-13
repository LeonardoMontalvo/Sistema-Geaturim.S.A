set search_path to imbacasa;
-- Table: detalle_impuesto_producto_gasto

-- DROP TABLE detalle_impuesto_producto_gasto;

CREATE TABLE detalle_impuesto_producto_gasto
(
  id_detalle_impuesto_producto_gasto integer NOT NULL,
  cod_impuesto text,
  cod_tarifa text,
  tarifa numeric,
  valor_impuesto numeric,
  base_imponible numeric,
  id_detalle_gastos integer,
  CONSTRAINT pk_detalle_impuesto_producto_gasto PRIMARY KEY (id_detalle_impuesto_producto_gasto)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE detalle_impuesto_producto_gasto
  OWNER TO postgres;
