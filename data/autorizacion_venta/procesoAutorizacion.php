<?php

session_start();
include '../../procesos/base.php';
conectarse();
$cont = 0;
$repe = 0;

    //////////////////validar repetidos//////////////////
    $consulta = pg_query("select * from autorizacion_venta where autorizacion='$_POST[autorizacion]'");
    while ($row = pg_fetch_row($consulta)) {
        $repe++;
    }
    ///////////////////////////////////////////////////    

if ($_POST['oper'] == "add") {
    $consulta = pg_query("select max(id_autorizacion_venta) from autorizacion_venta");
    while ($row = pg_fetch_row($consulta)) {
        $cont = $row[0];
    }
    $cont++;

    if ($repe == 0) {
        pg_query("insert into autorizacion_venta values('$cont','$_POST[factura_inicio]','$_POST[factura_fin]','$_POST[autorizacion]')");
    }
} else {
    if ($_POST['oper'] == "edit") {
        if ($repe == 0) {
            pg_query("update autorizacion_venta set factura_inicio='$_POST[factura_inicio]', factura_fin='$_POST[factura_fin]', autorizacion='$_POST[autorizacion]'");
        }
    }
}
?>
