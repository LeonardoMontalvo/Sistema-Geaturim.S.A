<?php

session_start();
include '../../procesos/base.php';
conectarse();
date_default_timezone_set('America/Guayaquil'); 
$cont = 0;
$repe = 0;
$repe2 = 0;
$fecha = date('Y-m-d', time());
//////////////////validar repetidos//////////////////
$consulta = pg_query("select * from facturas_anuladas where (factura_inicio<='$_POST[factura_inicio]' and factura_fin >= '$_POST[factura_inicio]') or (factura_inicio<='$_POST[factura_fin]' and factura_fin >= '$_POST[factura_fin]') ");
while ($row = pg_fetch_row($consulta)) {
    $repe++;
}
///////////////////////////////////////////////

if ($_POST['oper'] == "add") {
    $consulta = pg_query("select max(id_facturas_anuladas) from facturas_anuladas");
    while ($row = pg_fetch_row($consulta)) {
        $cont = $row[0];
    }
    $cont++;

    if ($repe == 0) {
        pg_query("insert into facturas_anuladas values('$cont','$_POST[factura_inicio]','$_POST[factura_fin]', '".$fecha."', 'Activo')");
    }
} else {
    if ($_POST['oper'] == "edit") {
        $consulta = pg_query("select * from facturas_anuladas where (factura_inicio<='$_POST[factura_inicio]' and factura_fin >= '$_POST[factura_inicio]') and (factura_inicio<='$_POST[factura_fin]' and factura_fin >= '$_POST[factura_fin]') and id_facturas_anuladas <> $_POST[id_facturas_anuladas]");
        while ($row = pg_fetch_row($consulta)) {
            $repe2++;
        }
        if ($repe2 == 0) {
            pg_query("update facturas_anuladas set factura_inicio='$_POST[factura_inicio]', factura_fin='$_POST[factura_fin]' where id_facturas_anuladas='$_POST[id_facturas_anuladas]'");
        }
    }
}
?>
