<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);


$cont=0;
$consulta = pg_query("select max(id_desaprobacion) from desaprobacion_ordenes");
while ($row = pg_fetch_row($consulta)) {
    $cont = $row[0];
}
$cont++;

$data = $cont;

//aprobación de orden de producción
pg_query("insert into desaprobacion_ordenes values('$cont', '$_POST[comprobante]', '$_POST[motivo]', '$_POST[fecha_actual]', 'Activo')");
    



// guardar detalle compra
    

echo $data;
?>
