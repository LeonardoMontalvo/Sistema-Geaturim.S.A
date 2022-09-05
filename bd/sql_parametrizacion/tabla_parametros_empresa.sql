create table public.parametros_empresa(
id_parametros_empresa integer primary key,
nombre_parametro text,
valor_parametro text
);

CREATE UNIQUE INDEX CONCURRENTLY uindex_nombre_parametro 
ON public.parametros_empresa (nombre_parametro);

ALTER TABLE public.parametros_empresa 
ADD CONSTRAINT unique_nombre_parametro 
UNIQUE USING INDEX uindex_nombre_parametro;

insert into public.parametros_empresa values(1,'logo_empresa','');
insert into public.parametros_empresa values(2,'app_firma','');
insert into public.parametros_empresa values(3,'host_correo','');
insert into public.parametros_empresa values(4,'user_correo','');
insert into public.parametros_empresa values(5,'pass_correo','');
insert into public.parametros_empresa values(6,'port_correo','');
insert into public.parametros_empresa values(7,'smtpsecure_correo','');
insert into public.parametros_empresa values(8,'copia_correo','');
insert into public.parametros_empresa values(9,'formato_imperesion_factura','');
insert into public.parametros_empresa values(10,'formato_imperesion_nota','');
insert into public.parametros_empresa values(11,'archivo_p12','');
insert into public.parametros_empresa values(12,'clave_firma','');
insert into public.parametros_empresa values(13,'formato_imperesion_nota_credito','');
insert into public.parametros_empresa values(14,'formato_imperesion_factura_compra','');
insert into public.parametros_empresa values(15,'formato_imperesion_retencion_compra','');