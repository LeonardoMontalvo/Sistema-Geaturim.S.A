<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
error_reporting(0);
date_default_timezone_set('America/Guayaquil');
// datos detalle factura
// fin
// contador inventario
$cont1 = 0;
$consulta = pg_query("select max(id_promociones) from promociones");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;
//	 echo '<br>GUARDAR FACTURA VENTA: <br>' . "insert into promociones values('$cont1','$_POST[id_promocion_pro]','$_POST[promocion_pro]','','Activo')";//////////////////////////
//	 

pg_query("insert into promociones values('$cont1','$_POST[cod_productos]','$_POST[id_promocion_pro]','$_POST[promocion_pro]','','Activo','$_POST[cantidad_promocion]','$_POST[pvp_promocion]')");
$data = 1;
 // Auditoria
    insert_registro('CREACION PROMOCIONES: ' . $_POST['cod_productos']);



echo $data;
?>