<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto2 = $_GET['term'];

$consulta = pg_query("  select * from plan_cuentas where codigo_plan like '%$texto2%' and descripcion like '%BAN%'  or  descripcion like '%MUTU%' and cuenta='M' and estado = 'Activo' order by codigo_plan");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'codigo_plan' => $row[1],
        'descripcion' => $row[2],
        'cuenta' => $row[3],
        'id_plan_cuentas' => $row[0],
    );
}

echo $data = json_encode($data);
?>
