-- Table: puntos_venta_usuario

-- DROP TABLE puntos_venta_usuario;

CREATE TABLE puntos_venta_usuario
(
  id_punto_venta integer NOT NULL,
  id_usuario integer NOT NULL,
  CONSTRAINT pk_pv_usuarios PRIMARY KEY (id_punto_venta, id_usuario)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE puntos_venta_usuario
  OWNER TO postgres;
