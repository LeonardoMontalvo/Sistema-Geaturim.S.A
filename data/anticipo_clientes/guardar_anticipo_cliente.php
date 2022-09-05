<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$conpunto = 1;
$consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($consultapunto)) {
    $conpunto = $row[0];
}

$conpuntoresult = 1;
$consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
while ($row = pg_fetch_row($consultapuntoresult)) {
    $conpuntoresult = $row[0];
}
/////////////////contador cuentas x cobrar externas/////////////
$cont = 0;
$consulta = pg_query("select max(id_anticipo_clientes) from anticipo_clientes");
while ($row = pg_fetch_row($consulta)) {
    $cont = $row[0];
}
$cont++;
//////////////////////////  
//guardar detalle_factura/////
$total = $_POST['monto'];
$format_numero = number_format($total, 2, '.', '');

//echo '<br>GUARDAR FACTURA VENTA: <br>' . "insert into anticipo_clientes values('$cont', '$_POST[id_cliente]', '$_SESSION[PV]', '$_SESSION[id]' , '$_POST[idCuenta]','$_POST[secuencial]' ,'$_POST[fecha_actual]' ,'$_POST[hora_actual]' ,'$total','$_POST[formaspago_mixto]','$_POST[fecha_registro]','$_POST[comentario]','Activo')"; //////////////////////////
//	 
pg_query("insert into anticipo_clientes values('$cont', '$_POST[id_cliente]', '$_SESSION[PV]', '$_SESSION[id]' , '$_POST[idCuenta]','$_POST[secuencial]' ,'$_POST[fecha_actual]' ,'$_POST[hora_actual]' ,'$total','$_POST[formaspago_mixto]','$_POST[fecha_registro]','$_POST[comentario]','Activo')");

////////////////////////////////
////////////////////////////CREACION ASIENTO CONTADO - CHEQUE
// guardar asiento contable

$idtran = pg_query("select max(id_transacciones) from transacciones");
$fila = pg_fetch_row($idtran);
$fila[0] = $fila[0] + 1;
$sum = 0;
$bool = true;
$pos = 0;
$vec = 0;
$tieneiva;
$ivafin = 0;
$auxiliar = $arreglo1;
$abc = 0;
$xy = 0;
$iva = pg_query("select valor from parametros where descripcion='IVA'");
while ($ivavalor = pg_fetch_row($iva)) {
    $ivafin = $ivavalor[0];
}
$abc = ($ivafin + 100) / 100;
while ($bool) {
    $cuenta = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[0] . "'");
    $plan = pg_fetch_row($cuenta);
    $nelem = count($auxiliar);
    $vec = 0;
    for ($i = 0; $i <= $nelem; $i++) {
        $cuenta1 = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[$i] . "'");
        $cIva = pg_query("select incluye_iva from productos where cod_productos='" . $auxiliar[$i] . "'");
        $plan1 = pg_fetch_row($cuenta1);
        $si = pg_fetch_row($cIva);
        $tieneiva = $si[0];

//                            $bien_servicio = pg_query("SELECT  bien_servicios FROM productos where cod_productos='" . $arreglo1[$i] . "'");
//                    $bien_servicioid = pg_fetch_row($bien_servicio);
//                    $bien_serviciob = $bien_servicioid[0];
//                     print_r($bien_serviciob."ll");
    }
    if ($vec == 0) {
        $bool = false;
    } else {
        $auxiliar = $vec;
        $pos = 0;
    }
}

$cliente1 = "";
if ($_POST['id_cliente'] == "") {
    $cliente1 = $contt;
} else {
    $cliente1 = $_POST['id_cliente'];
}
$prove = pg_query("select identificacion from clientes where id_cliente='$cliente1'");
$p = pg_fetch_row($prove);
$ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1' and id_empresa= '$_SESSION[PV]'");
$res = pg_fetch_row($ing);
  $ing_pv = pg_query("select max(id_transaccion_pv::int) from transacciones where  id_empresa= '$_SESSION[PV]'");
                $res_pv = pg_fetch_row($ing_pv);
//                  print_r("trans2".$costoVenta1);
//echo '<br>GUARDAR FACTURA VENTA12F: <br>' . "insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'ANTICIPO CLIENTES, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['secuencial'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $_POST[monto] . "', '$_POST[monto]', '" . $_POST[monto] . "','2','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','ANTC','',$conpuntoresult,'$_POST[fecha_actual]')"; //////////////////////////

$asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'ANTICIPO CLIENTES, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['secuencial'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $_POST[monto] . "', '$_POST[monto]', '" . $_POST[monto] . "','2','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','ANTC','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')");
//                  print_r("transjj".$bien_serviciob);



$iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
$fila1 = pg_fetch_row($iddettran);
if ($_POST['formaspago_mixto'] == "Contado" || $_POST['formaspago_mixto'] == "Cheque") {

    $sql = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
    $buscaCuenta = pg_fetch_row($sql);
    $fila1[0] = $fila1[0] + 1;
//    echo '<br>GUARDAR FACTURA VENTA3345HHG: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','" . $_POST['monto'] . "','0.000','Activo')"; //////////////////////////

    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','" . $_POST['monto'] . "','0.000','Activo')");
}
if ($_POST['formaspago_mixto'] == "Transferencias") {
    $id_cuenta_banco = $_POST['idCuenta'];
    $sql = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
    $buscaCuenta = pg_fetch_row($sql);
    $fila1[0] = $fila1[0] + 1;
//    echo '<br>GUARDAR FACTURA VENTA3345H: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco','" . $_POST['monto'] . "','0.000','Activo')"; //////////////////////////

    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco','" . $_POST['monto'] . "','0.000','Activo')");
}

$sql = pg_query("SELECT id_plan_cuentas FROM plan_cuentas where descripcion like '%ANTICIPOS CLIENTES%'");
$buscaCuenta = pg_fetch_row($sql);
$fila1[0] = $fila1[0] + 1;
//echo '<br>GUARDAR FACTURA VENTA3345HHJ: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','" . $_POST['monto'] . "','Activo')"; //////////////////////////

pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','" . $_POST['monto'] . "','Activo')");


$data = 1;
echo $data;
