<?php

session_start();
include '../../procesos/base.php';
conectarse();
$conpuntoresult = $_SESSION['PV'];
$consulta = pg_query("
select concepto,
    id_cuenta,
    cuenta_contable,
    tipo_iva,
    centro_costo,
    D.total_compra,
    D.bien_servicio,
    di.tarifa,
    di.valor_impuesto,
    di.cod_impuesto,
    di.cod_tarifa,
    di.base_imponible
from gastos F,
    detalle_gastos D
    left join detalle_impuesto_producto_gasto di using(id_detalle_gastos)
where F.id_gastos = D.id_gastos
    and F.id_empresa = '$conpuntoresult'
    and D.id_gastos = '$_GET[com]'
 ");


while ($row = pg_fetch_row($consulta)) {
    $lista[] = $row[0];
    $lista[] = $row[1];
    $lista[] = $row[2];
    $lista[] = $row[3];
    $lista[] = $row[4];
    $lista[] = $row[5];
    $lista[] = $row[6];
    $lista[] = $row[7];
    $lista[] = $row[8];
    $lista[] = $row[9];
    $lista[] = $row[10];
    $lista[] = $row[11];
}
echo $lista = json_encode($lista);
