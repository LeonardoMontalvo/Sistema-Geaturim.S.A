TRUNCATE public.parametros_formatos_impresion;
INSERT INTO public.parametros_formatos_impresion(
            id_formato, nombre_formato, archivo_formato, id_tipo_formato)
 VALUES 
 (1, 'A4 DOBLE HOJA', 'a4_doblehoja.php', 1),
 (2, 'A4 UNA HOJA', 'a4_una_hoja.php', 1),
 (3, 'TICKET 1', 'ticket_dany.php', 1),
 (4,'Nota Venta 1','nota_venta.php',2),
 (5,'Nota Crédito 1','generarPDFNota.php',3),
 (6,'Factura Compra 1','factura_compra.php',4),
 (7,'Retencion Compra 1','generarPDFReten_impri.php',5),
 (8,'TICKET 2','ticket_orden.php',1),
 (9,'TICKET 1','ticket_dany.php',2);

 --generarPDF_FE_DELAGADO.php