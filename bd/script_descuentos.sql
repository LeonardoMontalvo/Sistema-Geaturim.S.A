--set search_path to prueba;
--drop table descuentos_producto CASCADE;
create table descuentos_producto(
id_descuento integer primary key,
descripcion text,
nro_producto integer,
porcentaje_descuento integer,
estado text,
id_punto_venta integer,
constraint fk_desc_prod_pv FOREIGN KEY (id_punto_venta)
references punto_venta (id_punto_venta)
);

--drop table detalle_descuento CASCADE;
create table detalle_descuento(
id_descuento integer,
id_producto integer,
primary key (id_descuento,id_producto),
constraint fk_det_desc_prod FOREIGN KEY (id_producto)
references productos (cod_productos)ON DELETE CASCADE,
constraint fk_det_desc_desc FOREIGN KEY (id_descuento)
references descuentos_producto (id_descuento) ON DELETE CASCADE
);