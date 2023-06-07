<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$texto = $_GET['term'];

$consulta = pg_query("select id_cliente,nombres_cli||'('||identificacion||')' from clientes 
            where identificacion='$texto' 
            or nombres_cli ilike '%$texto%'
            and estado='Activo' limit 200");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[1],
        'label' => $row[0]
    );
}
echo $data = json_encode($data);
?>



