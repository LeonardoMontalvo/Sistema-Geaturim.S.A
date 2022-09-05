<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto2 = $_GET['term'];
$consulta = pg_query("select * from proveedores where identificacion_pro ilike '%$texto2%' and estado='Activo'");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[2],
        'id_proveedor' => $row[0],
        'empresa_pro' => $row[3]
        //'saldo' => $row[11]
    );
}
echo $data = json_encode($data);
?>