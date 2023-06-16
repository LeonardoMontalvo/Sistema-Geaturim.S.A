
SET search_path = vipdistribuciones, pg_catalog;

ALTER TABLE productos
	ADD COLUMN cantidad_mayorista text,
	ADD COLUMN cantidad_negocio text;

ALTER TABLE rutas
	ADD COLUMN frecuencia_vicitas text;
