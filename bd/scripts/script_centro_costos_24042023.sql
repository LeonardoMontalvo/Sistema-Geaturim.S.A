--CENTROS COSTO

-- Table: centro_costos

-- DROP TABLE centro_costos;

CREATE TABLE centro_costos
(
  id_centro_costo integer NOT NULL,
  nombre text,
  descripcion text,
  estado text,
  CONSTRAINT centro_costo_pkey PRIMARY KEY (id_centro_costo)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE centro_costos
  OWNER TO postgres;
