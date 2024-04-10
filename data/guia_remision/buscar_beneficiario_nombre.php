<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto = $_GET['term'];
$consulta = pg_query("select * from beneficiario where nombre_beneficiario like '%$texto%' and estado='Activo'");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[2],
        'id_beneficiario' => $row[0],
        'ruc_ci_bene' => $row[3]
       
    );
}
echo $data = json_encode($data);
?>
