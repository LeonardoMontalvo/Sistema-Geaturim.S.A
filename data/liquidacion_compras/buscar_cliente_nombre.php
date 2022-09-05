<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto = $_GET['term'];
$consulta = pg_query("select * from proveedores where   empresa_pro like '%$texto%' and estado= 'Activo'");

while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[3],
        'id_proveedor' => $row[0],
        'ruc_ci' => $row[2],
        'direccion_cliente' => $row[6],
        'telefono_cliente' => $row[7],
        'correo' => $row[13]
    );
}
echo $data = json_encode($data);
?>
