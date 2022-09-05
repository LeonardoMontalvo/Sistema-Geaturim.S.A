--drop table restaurante_ordenes cascade;
create table restaurante_ordenes(
id_restaurante_orden integer primary key,
id_punto_venta integer,
id_cliente integer,
id_usuario integer,
comprobante text,
fecha_creacion timestamp without time zone,
tarifa12 numeric,
tarifa0 numeric,
iva numeric,
descuento numeric,
total numeric,
estado text,
tipo_documento text,
id_documento integer
);

--drop table restaurante_detalle_ordenes;
create table restaurante_detalle_ordenes(
id_restaurante_detalle_orden integer primary key,
id_restaurante_orden integer,
cod_productos integer,
cantidad integer,
precio_venta numeric,
descuento numeric,
total numeric
);

alter table restaurante_detalle_ordenes
add constraint fk_restaurante_ordentes_detalles
foreign key (id_restaurante_orden)
references restaurante_ordenes (id_restaurante_orden)
