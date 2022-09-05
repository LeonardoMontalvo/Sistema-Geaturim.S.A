<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto = $_GET['term'];
$consulta = pg_query("select * from empleado, cargo where empleado.id_cargo=cargo.id_cargo and empleado.estado='Activo' and cargo.estado='Activo' and empleado.identificacion like '%$texto%'");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[1],
        'id_cliente' => $row[0],
        'nombre_cliente' => $row[2],
        'direccion_cliente' => $row[3],
           'cargo_empleado' => $row[20],
           'salario_empleado' => $row[21],
           'dias_trabajados' => '30',
        'esta_afiliado' => $row[18],
    );
}

echo $data = json_encode($data);
?>
