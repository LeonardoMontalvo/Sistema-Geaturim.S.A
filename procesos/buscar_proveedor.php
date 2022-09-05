<?php

session_start();
include 'base.php';
conectarse();
$texto = $_GET['term'];

$consulta = pg_query("select * from proveedores where identificacion_pro like '$texto%' or empresa_pro ilike '%$texto%'");
$data=[];
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[3],
        'id_proveedor' => $row[0]
    );
}
echo $data = json_encode($data);
?>
