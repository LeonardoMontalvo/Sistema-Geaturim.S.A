<?php

session_start();
include '../../procesos/base.php';
include 'guardar_pxc_retencion.php';
conectarse();
error_reporting(0);

// datos detalle factura
$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];
$campo5 = $_POST['campo5'];
// fin
//contador factura compra
$cont1 = 0;
$consulta = pg_query("select max(id_retencion_iva_factura_venta) from retencion_iva_factura_venta");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;
// fin
//fecha actual
/* $Digital = new Date();
  $year = Digital.getYear();
  $month = Digital.getMonth();
  $day = Digital.getDay();
  $fecha_actual=$year+":"+$month+":"+$day;
  //hora actual
  $Digital = new Date();
  $hours = Digital.getHours();
  $minutes = Digital.getMinutes();
  $seconds = Digital.getSeconds();
  $hora_actual=$hours+":"+$minutes+":"+$seconds; */
$data = 0;
$fecha = date('Y-m-d', time());
$hora = date('h:i:s A', time());
$comprobar = pg_query("select id_factura from retencion_iva_factura_venta");
while ($row2 = pg_fetch_row($comprobar)) {
    if ($row2[0] == $_POST['id_factura']) {
        $data = 2;
    }
}
if ($data != 2) {
    pg_query("insert into retencion_iva_factura_venta values('" . $cont1 . "', '$_POST[id_factura]', '$_POST[id_retencion_iva]','" . $fecha . "','" . $hora . "','$_POST[valor_factura]','$_POST[iva_factura]','$_POST[valor_retencion]', '$_POST[autorizacion_ret]')");
    $valreten = $_POST['iva_factura'];

    $valfac = pg_query("select * from pagos_venta where id_factura_venta ='$_POST[id_factura]'");
    $valfacresult = pg_fetch_row($valfac);
    $resultreten = $valfacresult[9] - $valreten;


    //pg_query("update pagos_venta set monto_credito='" . $resultreten . "' , saldo='" . $resultreten . "' where id_factura_venta='$_POST[id_factura]'");

    if (isFacturaCredito($_POST["id_factura"])) {
        guardarPagoC($_POST["id_factura"], "", "INTERNA", $valreten, "RETENCION IVA", "");
    }

    /////////////////////////////////////////////
    /////////////////////////////ASIENTO CONTABLE
    $tran = pg_query("select * from transacciones where comprobante='$_POST[id_factura]' and id_tipo_transaccion='1' and concepto like 'VEN%' and id_empresa=".$_SESSION['PV']);
    $fila = pg_fetch_row($tran);
    $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
    $fila1 = pg_fetch_row($iddettran);
    $fila1[0] = $fila1[0] + 1;
    $cons = pg_query("select cuenta_debito from retencion_iva where id_retencion_iva='$_POST[id_retencion_iva]'");
    $cont2 = pg_fetch_row($cons);
    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $cont2[0] . "','$_POST[valor_retencion]','0.000','Activo')");
    $x = $_POST['valor_retencion'];

    // 
    $sql = pg_query("select id_plan_cuentas from detalle_transaccion where id_transacciones='" . $fila[0] . "' "
            . "and id_plan_cuentas<>'29' "
            . "and id_plan_cuentas<>'30' "
            . "and id_plan_cuentas<>'31' "
            . "and id_plan_cuentas<>'32' "
            . "and id_plan_cuentas<>'33' "
            . "and id_plan_cuentas<>'34' "
            . "and id_plan_cuentas<>'35' "
            . "and id_plan_cuentas<>'36' "
            . "and id_plan_cuentas<>'212' "
            . "and id_plan_cuentas<>'118' "
            . "and id_plan_cuentas<>'69' "
            . "and id_plan_cuentas<>'210'"
            . "and id_plan_cuentas<>'210'"
            . "and id_plan_cuentas<>'216'"
            . "and id_plan_cuentas<>'217'"
            . "and id_plan_cuentas<>'218'"
            . "and id_plan_cuentas<>'28'"
    );

    $idPlan = pg_fetch_row($sql);

    $plancaja = pg_query("select cuenta_debito from parametros where cuenta_debito='" . $idPlan[0] . "'");
    $caja = pg_fetch_row($plancaja);
    $tot = pg_query("select debito, id_detalle_transaccion from detalle_transaccion where id_transacciones='" . $fila[0] . "' and id_plan_cuentas='" . $caja[0] . "'");
    $s = pg_fetch_row($tot);
    $caja = $s[0] - $x;
    pg_query("update detalle_transaccion set debito='" . $caja . "' where id_detalle_transaccion='" . $s[1] . "'");

/////////////////////////////////////////////
    /////////////////////////////
    $data = 1;
}
echo $data;
