-- Table: detalle_gastos_personales

-- DROP TABLE detalle_gastos_personales;

CREATE TABLE detalle_gastos_personales
(
  id_detalle_gastos_personales integer NOT NULL,
  id_gastos_personales integer,
  id_tipo_gasto integer,
  producto text,
  bien_servicio text,
  iva integer,
  valor_descuento numeric,
  precio_u numeric,
  cantidad numeric,
  total numeric,
  CONSTRAINT pk_detalle_gastos_personales PRIMARY KEY (id_detalle_gastos_personales),
  CONSTRAINT fk_gastos_personales_detalle_gastos_personales FOREIGN KEY (id_gastos_personales)
      REFERENCES gastos_personales (id_gastos_personales) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT fk_tipo_gasto_detalle_gastos_personales FOREIGN KEY (id_tipo_gasto)
      REFERENCES tipos_gastos_personales (id_tipo_gasto) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
ALTER TABLE detalle_gastos_personales
  OWNER TO postgres;
