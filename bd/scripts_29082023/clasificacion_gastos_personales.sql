-- Table: clasificacion_gastos_personales

-- DROP TABLE clasificacion_gastos_personales;

CREATE TABLE clasificacion_gastos_personales
(
  id_clasificacion integer NOT NULL,
  nombre text,
  CONSTRAINT pk_clasificacion_gastos_personales PRIMARY KEY (id_clasificacion)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE clasificacion_gastos_personales
  OWNER TO postgres;
