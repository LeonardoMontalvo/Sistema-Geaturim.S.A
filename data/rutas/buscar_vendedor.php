<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto = $_GET['term'];
$consulta = pg_query("select ci_vendedor,id_vendedor,nombre_vendedor from vendedores where ci_vendedor like '%$texto%' and estado='Activo'");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[0],
        'id_vendedor' => $row[1],
        'nombre_vendedor' => $row[2]
       
    );
}

echo $data = json_encode($data);
?>
