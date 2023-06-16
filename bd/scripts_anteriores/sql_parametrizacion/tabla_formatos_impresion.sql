drop table public.parametros_formatos_impresion;
create table public.parametros_formatos_impresion(
id_formato integer primary key,
nombre_formato text,
archivo_formato text,
id_tipo_formato integer
);

insert into public.parametros_formatos_impresion 
values(1,'Factura 1','generarPDF_1.php', 1);
insert into public.parametros_formatos_impresion 
values(2,'Factura 2','generarPDF_unido.php',1);
insert into public.parametros_formatos_impresion 
values(3,'Nota Venta 1','nota_venta.php',2);
insert into public.parametros_formatos_impresion 
values(4,'Nota Crédito 1','generarPDFNota.php',3);
insert into public.parametros_formatos_impresion 
values(5,'Factura Compra 1','factura_compra.php',4);
insert into public.parametros_formatos_impresion 
values(6,'Retencion Compra 1','generarPDFReten_impri.php',5);
