<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto2 = $_GET['term'];

$consulta = pg_query("select * from denominacion where denominacion like '%$texto2%' and estado = 'Activo'");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[1],       
        'cod_denominacion' => $row[0],
                'valor' => $row[2],
    );
}

echo $data=json_encode($data);
?>
