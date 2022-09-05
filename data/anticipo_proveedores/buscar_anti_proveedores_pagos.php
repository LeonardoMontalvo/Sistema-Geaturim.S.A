<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto2 = $_GET['term'];
$consulta = pg_query("select * from proveedores where empresa_pro like '%$texto2%' and estado='Activo'");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[3],
        'id_proveedor' => $row[0],
        'ruc_ci' => $row[2],
        'saldo' => $row[11]
    );
}
echo $data = json_encode($data);
?>