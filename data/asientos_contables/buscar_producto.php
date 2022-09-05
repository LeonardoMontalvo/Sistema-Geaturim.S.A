<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto2 = $_GET['term'];

$consulta = pg_query("select * from plan_cuentas where descripcion ilike '%$texto2%' and cuenta='M' and estado = 'Activo' ORDER BY descripcion");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'codigo_plan' => $row[1],
        'descripcion' => $row[2]."--".$row[1]."--".$row[3],
        'cuenta' => $row[3],
        'id_plan_cuentas' => $row[0],
    );
}

echo $data = json_encode($data);
?>
