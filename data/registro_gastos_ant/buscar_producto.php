<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto2 = $_GET['term'];

$consultaG = pg_query("select id_plan_cuentas from plan_cuentas where  codigo_plan like '5.2%' and cuenta='M' ");
if (pg_num_rows($consultaG) > 0) {
    while ($row1 = pg_fetch_assoc($consultaG)) {

//echo '<br>GUARDAR FACTURA VENTA: <br>' . "select * from productos where articulo ilike '%$texto2%'  and estado = 'Activo' and id_plan_cuentas='$row1[id_plan_cuentas]'"; //////////////////////////

$consulta = pg_query("select * from productos where articulo ilike '%$texto2%'  and estado = 'Activo' and id_plan_cuentas='$row1[id_plan_cuentas]'");
 if (pg_num_rows($consulta) > 0) {
$row = pg_fetch_row($consulta);
    $data[] = array(
        'value' => $row[3],
        'codigo' => $row[1],
        'codigo_barras' => $row[2],
        'precio' => $row[6],
        'iva_producto' => $row[4],
        'carga_series' => $row[5],
        'cod_producto' => $row[0],
        'incluye' => $row[26]
    );
    }
    }
}
echo $data = json_encode($data);
?>
