<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto = $_GET['term'];
$consulta = pg_query("select nombre_vendedor,id_vendedor,ci_vendedor,direccion_vendedor,telefono_vendedor,email_vendedor from vendedores where nombre_vendedor like '%$texto%' and estado='Activo'");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[0],
        'id_vendedor' => $row[1],
        'ruc_ci_cli' => $row[2],
        'direccion_vendedor' => $row[3],
        'telefono_vendedor' => $row[4],
        'correo' => $row[5]
    );
}
echo $data = json_encode($data);
?>
