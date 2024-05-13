set search_path to imbacasa;
-- Table: detalle_impuesto_producto_dev_venta

-- DROP TABLE detalle_impuesto_producto_dev_venta;

CREATE TABLE detalle_impuesto_producto_dev_venta
(
  id_detalle_impuesto_producto_dev_venta integer NOT NULL,
  cod_impuesto text,
  cod_tarifa text,
  tarifa numeric,
  valor_impuesto numeric,
  base_imponible numeric,
  id_detalle_deventa integer,
  CONSTRAINT pk_detalle_impuesto_producto_dev_venta PRIMARY KEY (id_detalle_impuesto_producto_dev_venta)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE detalle_impuesto_producto_dev_venta
  OWNER TO postgres;
