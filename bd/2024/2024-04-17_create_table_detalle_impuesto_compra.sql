-- Table: detalle_impuesto_producto_venta

-- DROP TABLE detalle_impuesto_producto_venta;

CREATE TABLE detalle_impuesto_producto_venta
(
  id_detalle_impuesto_producto_venta integer NOT NULL,
  cod_impuesto text,
  cod_tarifa text,
  tarifa numeric,
  valor_impuesto numeric,
  base_imponible numeric,
  id_detalle_venta integer,
  CONSTRAINT pk_detalle_impuesto_producto_venta PRIMARY KEY (id_detalle_impuesto_producto_venta)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE detalle_impuesto_producto_venta
  OWNER TO postgres;
