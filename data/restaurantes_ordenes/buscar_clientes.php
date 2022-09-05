<?php
session_start();
include '../../procesos/base.php';
conectarse();
$texto2 = $_GET['term'];

$sql = "
select
nombres_cli,
direccion_cli,
telefono,
celular,
correo,
identificacion,
id_cliente
from clientes where (identificacion like '$texto2%' or nombres_cli ilike '%$texto2%') and estado = 'Activo'";


$consulta = pg_query($sql);
$data = [];
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'nombres_cli' => $row[0],
        'direccion_cli' => $row[1],
        'telefono' => $row[2],
        'celular' => $row[3],
        'correo' => $row[4],
        'identificacion' => $row[5],
        'id_cliente'=>$row[6]
    );
}

echo $data = json_encode($data);
