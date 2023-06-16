create or replace function manejo_esquemas.generar_datos_empresa_nueva(source_schema text, dest_schema text)
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


--perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'empresa');
execute 'insert into '||quote_ident(dest_schema)||'.empresa select * from '||quote_ident(source_schema)||'.empresa where id_empresa = 1';
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'plan_cuentas');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'tipo_comprobante');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'tipo_documento');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'tipo_emision');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'tipo_impuesto');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'tarifa_impuesto');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'tipo_retencion');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'tipo_transaccion');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'ambiente');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'bodegas');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'cargo_usuario');

perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'forma_pagos');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'seguridad');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'retencion_fuentes');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'retencion_fuentes_r');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'retencion_iva');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'retencion_iva_r');
--perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'retenciones');

perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'permisos');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'parametros_iess');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'parametros');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'parametros_empresa');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'parametros_tipos_formato_impresion');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'parametros_formatos_impresion');
--perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'impuestos');
perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'formas_pagos');

perform manejo_esquemas.copiar_tabla_esquema(source_schema,dest_schema, 'autorizacion_venta');


execute 'insert into '||quote_ident(dest_schema)||'.usuario select * from '||quote_ident(source_schema)||'.usuario where usuario = ''Admin''';
execute 'insert into '||quote_ident(dest_schema)||'.clientes select * from '||quote_ident(source_schema)||'.clientes where id_cliente = 1';
execute 'insert into '||quote_ident(dest_schema)||'.punto_venta select * from '||quote_ident(source_schema)||'.punto_venta where id_punto_venta = 1';
execute 'insert into '||quote_ident(dest_schema)||'.proveedores select * from '||quote_ident(source_schema)||'.proveedores where id_proveedor = 1';
execute 'insert into '||quote_ident(dest_schema)||'.vendedores select * from '||quote_ident(source_schema)||'.vendedores where id_vendedor = 1';
execute 'insert into '||quote_ident(dest_schema)||'.rutas select * from '||quote_ident(source_schema)||'.rutas where id_ruta = 1';
execute 'insert into '||quote_ident(dest_schema)||'.transportista select * from '||quote_ident(source_schema)||'.transportista where id_transportista = 1';
execute 'insert into '||quote_ident(dest_schema)||'.unidades_medida select * from '||quote_ident(source_schema)||'.unidades_medida where id_unidades = 1';

return;
end;
$$
LANGUAGE plpgsql;