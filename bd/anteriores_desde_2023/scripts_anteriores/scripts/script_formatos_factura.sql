SET SEARCH_PATH TO mgpimampiro;

/*TRUNCATE parametros_formatos_impresion;
	INSERT INTO parametros_formatos_impresion(
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
 (9,'TICKET 1','ticket_dany.php',2),
 (10,'TICKET 3','san_lorenzo.php',1),
 (11,'TICKET 3','san_lorenzo.php',1);*/
 TRUNCATE parametros_formatos_impresion;

 --Facturas
 INSERT INTO parametros_formatos_impresion(
            id_formato, nombre_formato, archivo_formato, id_tipo_formato)
 VALUES 
 (1, 'A4 DOBLE HOJA', 'a4_doblehoja.php', 1),
 (12, 'A4 UNA HOJA', 'a4_una_hoja.php', 1),
 (13, 'TICKET 1', 'ticket_dany.php', 1),
 (14,'TICKET 2','ticket_orden.php',1),
 (15,'TICKET 3','san_lorenzo.php',1);

--Nota venta
 INSERT INTO parametros_formatos_impresion(
            id_formato, nombre_formato, archivo_formato, id_tipo_formato)
 VALUES 
 (2,'Nota Venta 1','nota_venta.php',2),
 (21,'TICKET 1','ticket_dany.php',2),
 (22,'TICKET 2','san_lorenzo.php',2);

 --Otros formatos
 INSERT INTO parametros_formatos_impresion(
            id_formato, nombre_formato, archivo_formato, id_tipo_formato)
 VALUES 
 (3,'Nota Crédito 1','generarPDFNota.php',3),
 (31,'TICKET 1','ticket.php',3),
 (4,'Factura Compra 1','factura_compra.php',4),
 (5,'Retencion Compra 1','generarPDFReten_impri.php',5);

 --generarPDF_FE_DELAGADO.php