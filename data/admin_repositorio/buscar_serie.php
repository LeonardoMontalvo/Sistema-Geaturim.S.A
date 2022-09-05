<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto = $_GET['term'];
$consulta = pg_query("select * from factura_venta where num_factura like '%$texto' and estado='Activo' order by id_factura_venta");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[5],
        'num_factura' => $row[5]
        
    );
}

echo $data = json_encode($data);
?>
