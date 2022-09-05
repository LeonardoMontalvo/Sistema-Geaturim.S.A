<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$data = 0;
$cont = 0;
// echo '<br>GUARDAR FACTURA VENTA: <br>' . "select * from retencion_fuente_factura_compra,gastos where gastos.id_gastos=retencion_fuente_factura_compra.id_gastos and gastos.estado='Activo' and retencion_fuente_factura_compra.num_serie ='$_POST[num_reten]'";//////////////////////////
//	 
$consulta = pg_query("select * from retencion_fuente_factura_compra, factura_compra where retencion_fuente_factura_compra.num_serie ='$_POST[num_reten]' and retencion_fuente_factura_compra.estado <> 'g' 
and factura_compra.id_factura_compra=retencion_fuente_factura_compra.id_factura and factura_compra.estado='Activo'");
while ($row = pg_fetch_row($consulta)) {
    if ($row[0] != "") {
    
    $cont++;
    }
}
$consulta = pg_query("select * from retencion_fuente_factura_compra, gastos where retencion_fuente_factura_compra.num_serie ='$_POST[num_reten]' and retencion_fuente_factura_compra.estado <> 'g' 
and gastos.id_gastos=retencion_fuente_factura_compra.id_factura and gastos.estado='Activo'");
while ($row = pg_fetch_row($consulta)) {
    if ($row[0] != "") {
    
    $cont++;
    }
}
//
//print_r($cont."dd");
if ($cont == 0) {
    $data = 0;
} else {
    $consulta = pg_query("select max(num_serie) from retencion_fuente_factura_compra where estado <> 'g'");
	while ($row = pg_fetch_row($consulta)) {
	    $data = $row[0];

	}
}
echo $data;
?>