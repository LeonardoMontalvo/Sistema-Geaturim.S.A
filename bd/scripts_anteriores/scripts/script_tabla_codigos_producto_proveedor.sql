set search_path to public;
-- Table: codigos_productos_proveedor

-- DROP TABLE codigos_productos_proveedor;

CREATE TABLE codigos_productos_proveedor
(
  id_proveedor integer NOT NULL,
  cod_prod_proveedor text NOT NULL,
  cod_productos integer,
  CONSTRAINT pk_codigos_prod_proveedor PRIMARY KEY (id_proveedor, cod_prod_proveedor)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE codigos_productos_proveedor
  OWNER TO postgres;
