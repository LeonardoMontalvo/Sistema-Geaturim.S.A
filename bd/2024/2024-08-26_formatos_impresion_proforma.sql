set search_path to public;
INSERT INTO parametros_tipos_formato_impresion(
            id_tipo_formato, nombre_formato)
    VALUES (8, 'PROFORMA');
INSERT INTO parametros_formatos_impresion(
            id_formato, nombre_formato, archivo_formato, id_tipo_formato)
    VALUES 
    (81, 'Proforma ', 'proforma.php', 8),
    (82, 'Proforma Ticket', 'proforma_ticket.php', 8);
ALTER TABLE parametros_punto_venta 
ADD COLUMN formato_imperesion_proforma INTEGER;