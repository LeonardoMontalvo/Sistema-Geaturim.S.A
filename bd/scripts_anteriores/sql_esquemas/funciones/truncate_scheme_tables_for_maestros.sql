CREATE OR REPLACE FUNCTION manejo_esquemas.truncate_scheme_tables_for_maestros(username IN VARCHAR, source_scheme text) RETURNS void AS $$
DECLARE
    statements CURSOR FOR
        SELECT tablename FROM pg_tables
        WHERE tableowner = username 
        AND schemaname = source_scheme
        AND tablename NOT IN (
        'empresa',
        'plan_cuentas',
        'tipo_comprobante',
        'tipo_documento',
        'tipo_emision',
        'tipo_impuesto',
        'tarifa_impuesto',
        'tipo_retencion',
        'tipo_transaccion',
        'ambiente',
        'bodegas',
        'cargo_usuario',
        'forma_pagos',
        'seguridad',
        'retencion_fuentes',
        'retencion_fuentes_r',
        'retencion_iva',
        'retencion_iva_r',
        'permisos',
        'parametros_iess',
        'parametros',
        'parametros_empresa',
        'formas_pagos',
        'autorizacion_venta',
        'parametros_empresa',
        'mes_actual'
        );
BEGIN
    FOR stmt IN statements LOOP
        EXECUTE 'TRUNCATE TABLE ' || quote_ident(source_scheme)||'.'||quote_ident(stmt.tablename) || ' CASCADE;';
    END LOOP;
END;
$$ LANGUAGE plpgsql;