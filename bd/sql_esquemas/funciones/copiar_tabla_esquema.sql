create or replace function manejo_esquemas.copiar_tabla_esquema(source_schema text, dest_schema text, tabla text)
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

	execute 'insert into '||quote_ident(dest_schema)||'.'||quote_ident(tabla)||' select*from '||quote_ident(source_schema)||'.'||quote_ident(tabla);
	
	return;
end;
$$
LANGUAGE plpgsql;
