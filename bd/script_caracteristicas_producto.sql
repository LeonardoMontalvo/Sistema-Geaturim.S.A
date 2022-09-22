create table producto_caracteristicas(
id_caracteristica integer primary key,
cod_productos integer,
nombre text,
constraint fk_cr_prod foreign key (cod_productos)
references productos (cod_productos)
);