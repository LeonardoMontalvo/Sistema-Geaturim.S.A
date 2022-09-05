create table public.parametros_tipos_formato_impresion(
id_tipo_formato integer primary key,
nombre_formato text
);

insert into public.parametros_tipos_formato_impresion 
values(1,'FACTURA');
insert into public.parametros_tipos_formato_impresion 
values(2,'NOTA VENTA');
insert into public.parametros_tipos_formato_impresion 
values(3,'NOTA CREDITO');
insert into public.parametros_tipos_formato_impresion 
values(4,'FACTURA COMPRA');
insert into public.parametros_tipos_formato_impresion 
values(5,'RETENCION COMPRA');


