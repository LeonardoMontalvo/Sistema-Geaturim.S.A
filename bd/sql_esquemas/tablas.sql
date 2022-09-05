--drop SCHEMA manejo_esquemas cascade;
CREATE SCHEMA manejo_esquemas;

--drop table manejo_esquemas.esquemas
create table manejo_esquemas.esquemas(
id_esquema integer primary key,
nombre text,
descripcion text,
estado text,
por_defecto boolean default false,
unique(nombre,estado)
);

insert into manejo_esquemas.esquemas values(1,'public','esquema principal','Activo',true);

--triggers tabla manejo_esquemas.esquemas
create or replace function manejo_esquemas.trigger_clone_schema_instert_fun()
returns trigger as $$
begin
	if new.nombre<>'public' then
		perform manejo_esquemas.clone_schema('public',new.nombre,false);
		perform manejo_esquemas.generar_datos_empresa_nueva('public', new.nombre);
	end if;
	return new;
end;
$$
language 'plpgsql';

create trigger trigger_clone_schema_insert
after insert on manejo_esquemas.esquemas
for each row
execute procedure manejo_esquemas.trigger_clone_schema_instert_fun();

alter table manejo_esquemas.esquemas
add column color text;
