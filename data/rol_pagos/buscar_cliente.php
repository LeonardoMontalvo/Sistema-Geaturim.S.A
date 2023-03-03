<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto = $_GET['term'];
$consulta = pg_query("select id_empleado,identificacion,nombres_empleado,direccion_empleado,nombre_cargo,sueldo_base,afiliacion,decimo,fondos_reserva,fondos_acu_mensual,decimo_si_no  from empleado, cargo where empleado.id_cargo=cargo.id_cargo and empleado.estado='Activo' and cargo.estado='Activo' and empleado.identificacion like '%$texto%'");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[1],
        'id_cliente' => $row[0],
        'nombre_cliente' => $row[2],
        'direccion_cliente' => $row[3],
        'cargo_empleado' => $row[4],
        'salario_empleado' => $row[5],
        'dias_trabajados' => '30',
        'esta_afiliado' => $row[6],
        'decimo' => $row[7],
          'tiene_fondos' => $row[8],
        'acumula_fondos' => $row[9],
        'decimo_si_no' => $row[10],
    );
}

echo $data = json_encode($data);
?>
