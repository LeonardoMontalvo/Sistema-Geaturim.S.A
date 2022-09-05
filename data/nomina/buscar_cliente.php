<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto = $_GET['term'];
$consulta = pg_query("select * from empleado where identificacion like '%$texto%' and estado='Activo'");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[1],
        'id_cliente' => $row[0],
        'nombre_cliente' => $row[2],
        'direccion_cliente' => $row[3]
       
    );
}

echo $data = json_encode($data);
?>
