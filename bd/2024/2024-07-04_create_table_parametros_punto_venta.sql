-- DROP TABLE parametros_punto_venta;
CREATE TABLE parametros_punto_venta (
    id_punto_venta integer NOT NULL,
    formato_imperesion_factura integer,
    formato_imperesion_nota integer,
    formato_imperesion_nota_credito integer,
    formato_imperesion_factura_compra integer,
    formato_imperesion_retencion_compra integer,
    formato_imperesion_retencion_gasto integer,
    CONSTRAINT pk_parametros_pv PRIMARY KEY (id_punto_venta)
) WITH (OIDS = FALSE);
ALTER TABLE parametros_punto_venta OWNER TO postgres;