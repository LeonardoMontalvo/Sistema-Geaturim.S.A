<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto = $_GET['term'];
$consulta = pg_query("select * from beneficiario where ci_beneficiario like '%$texto%' and estado='Activo'");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[3],
        'id_beneficiario' => $row[0],
        'nombre_cliente_bene' => $row[2]
       
    );
}

echo $data = json_encode($data);
?>
