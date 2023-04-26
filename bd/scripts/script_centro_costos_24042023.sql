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

-- Table: detalle_centro_costos

-- DROP TABLE detalle_centro_costos;

CREATE TABLE detalle_centro_costos
(
  id_detalle_centro_costo integer NOT NULL,
  id_documento integer,
  id_centro_costo integer,
  tipo_documento text,
  CONSTRAINT detalle_centro_costos_pkey PRIMARY KEY (id_detalle_centro_costo),
  CONSTRAINT fk_detalle_centro_costo_centro_costo FOREIGN KEY (id_centro_costo)
      REFERENCES centro_costos (id_centro_costo) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
ALTER TABLE detalle_centro_costos
  OWNER TO postgres;

