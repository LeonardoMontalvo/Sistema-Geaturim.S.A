-- Table: gastos_personales

-- DROP TABLE gastos_personales;

CREATE TABLE gastos_personales
(
  id_gastos_personales integer NOT NULL,
  id_usuario integer,
  id_proveedor integer,
  identificacion_comprador text,
  razon_social_comprador text,
  num_factura text,
  num_autorizacion text,
  fecha_emision date,
  descuento numeric,
  subtotal numeric,
  tarifa0 numeric,
  tarifa12 numeric,
  iva numeric,
  total numeric,
  comprobante integer,
  tipo_comprobante text,
  fecha_actual date,
  hora_actual text,
  estado text,
  id_empresa integer,
  CONSTRAINT pk_gastos_personales PRIMARY KEY (id_gastos_personales),
  CONSTRAINT fk_proveedor_gastos_personales FOREIGN KEY (id_proveedor)
      REFERENCES proveedores (id_proveedor) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT fk_punto_venta_gastos_personales FOREIGN KEY (id_empresa)
      REFERENCES pagos_venta (id_pagos_venta) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT fk_usuario_gastos_personales FOREIGN KEY (id_usuario)
      REFERENCES usuario (id_usuario) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
ALTER TABLE gastos_personales
  OWNER TO postgres;
