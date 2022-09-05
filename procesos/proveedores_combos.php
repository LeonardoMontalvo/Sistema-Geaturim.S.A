<?php
session_start();
include 'base.php';
conectarse();
$data = [];
$texto = $_GET['term'];
$consulta = pg_query("select id_proveedor, identificacion_pro, empresa_pro from proveedores where identificacion_pro like '%$texto%';");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'empresa_pro' => $row[2],
        'value' => $row[1],
        'id_proveedor' => $row[0],
    );
}
echo $data = json_encode($data);