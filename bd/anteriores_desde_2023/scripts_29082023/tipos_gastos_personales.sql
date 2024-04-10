-- Table: tipos_gastos_personales

-- DROP TABLE tipos_gastos_personales;

CREATE TABLE tipos_gastos_personales
(
  id_tipo_gasto integer NOT NULL,
  nombre text,
  id_clasificacion integer,
  CONSTRAINT pk_tipos_gastos_personales PRIMARY KEY (id_tipo_gasto),
  CONSTRAINT fk_tipos_gastos_personales_clasificacion_gastos_personales FOREIGN KEY (id_clasificacion)
      REFERENCES clasificacion_gastos_personales (id_clasificacion) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
ALTER TABLE tipos_gastos_personales
  OWNER TO postgres;
