<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto2 = $_GET['term'];
$sql = "select * from clientes where tipo_documento = '$_GET[tipo_docu]' and identificacion like '%$texto2%' and estado='Activo'";
if ($_GET["tipo_docu"] == "idext") {
    $sql = "select * from clientes where id_tdocu = 5 and identificacion like '%$texto2%' and estado='Activo'";
}
$consulta = pg_query($sql);
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[2],
        'id_cliente' => $row[0],
        'nombre_cli' => $row[3],
        'telefono_cli' => $row[7],
        'direccion_cli' => $row[5],
	'correo_cli' => $row[10]
    );
}
echo $data = json_encode($data);
