create or replace function manejo_esquemas.generar_datos_empresa_maestros(source_schema text, dest_schema text)
returns void as $$
begin
-- Check that source_schema exists
  PERFORM oid 
    FROM pg_namespace
   WHERE nspname = quote_ident(source_schema);
  IF NOT FOUND
    THEN 
    RAISE ' el esquema % no existe!', source_schema;
    RETURN;
  END IF;

  PERFORM oid 
    FROM pg_namespace
   WHERE nspname = quote_ident(dest_schema);
  IF NOT FOUND
    THEN 
   RAISE ' el esquema % no existe!', dest_schema;
    RETURN;
  END IF;

perform manejo_esquemas.truncate_scheme_tables_for_maestros('postgres',dest_schema);

--maestros
---usuarios
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'usuario');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'transportista');
---puntos venta
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'punto_venta');
---clientes
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'clientes');
---proveedores
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'proveedores');
---productos
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'aplicacion');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'categoria');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'generico');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'marcas');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'productos');
--vendedores
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'vendedores');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'sectores');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'rutas');
--registro equipos
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'tipo_equipo');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'color');
--empleado
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'empleado');
--bancos
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'bancos');
--beneficiario
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'beneficiario');
--contratos
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'contrato_conductor');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'contrato_lugar');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'contrato_tipo_vehiculo');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'contrato_marca_vehiculo');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'contrato_vehiculo');
return;
end;
$$
LANGUAGE plpgsql;