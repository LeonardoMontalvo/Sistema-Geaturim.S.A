<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto2 = $_GET['term'];
$consulta = pg_query("select * from bancos where descripcion ilike '%$texto2%' and estado='Activo'");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'id_bancos' => $row[0],
        'descripcion' => $row[1]
    );
}
echo $data = json_encode($data);
?>