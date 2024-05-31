set search_path to imbacasa;
-- Table: detalle_impuesto_producto_notaventa

-- DROP TABLE detalle_impuesto_producto_notaventa;

CREATE TABLE detalle_impuesto_producto_notaventa
(
  id_detalle_impuesto_producto_notaventa integer NOT NULL,
  cod_impuesto text,
  cod_tarifa text,
  tarifa numeric,
  valor_impuesto numeric,
  base_imponible numeric,
  id_detalle_facturas_novalidas integer,
  CONSTRAINT pk_detalle_impuesto_producto_notaventa PRIMARY KEY (id_detalle_impuesto_producto_notaventa)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE detalle_impuesto_producto_notaventa
  OWNER TO postgres;
