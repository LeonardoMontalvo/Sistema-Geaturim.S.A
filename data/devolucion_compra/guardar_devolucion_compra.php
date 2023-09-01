<?php

session_start();
include '../../procesos/base.php';
require_once '../../procesos/kardexValorizado.php';
//require_once '../../procesos/auditoria.php';
include_once '../../procesos/kardexValorizado.php';
require_once '../../procesos/detalleProductosBodega.php';

conectarse();
error_reporting(0);
$conpuntoresult = $_SESSION['PV'];
$data = 0;
// datos detalle devolucion compra
$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];
$campo5 = $_POST['campo5'];
$campo6 = $_POST['campo6'];
$campo7 = $_POST['campo7'];
$campo8 = $_POST['campo8'];
$cont1 = 0;
$consulta = pg_query("select max(id_devolucion_compra) from devolucion_compra");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;
if ($_POST["clave"] == '') {
    $num_clave = '000000001';
} else {
    $num_clave = $_POST["clave"];
}
if ($_POST["id_factura_compra"] != 0) {
    pg_query("insert into  devolucion_compra values('$cont1','$conpuntoresult','$_POST[id_proveedor]','$_SESSION[id]','$cont1','$_POST[fecha_actual]','$_POST[hora_actual]'
    ,'$_POST[tipo_comprobante]','$_POST[serie]','$_POST[autorizacion]'
    ,'$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]','$_POST[tot]','$_POST[observaciones]','Activo','$num_clave','$_POST[fecha_registro_nc]','$_POST[secuencial_nc]','$_POST[autorizacion_nc]','Si','$_POST[fecha_emision_nc]','$_POST[tipo_devolucion]')");
    //    insert_registro('CREACION ' . $_POST[tipo_comprobante] . 'DEVO COMPRA: ' . $cont1 . ', DEL PROVEEDOR CON ID: ' . $_POST[id_proveedor] . ', CON FORMA DE PAGO:  Y TOTAL DE: ' . $_POST[tot]);
} else {
    pg_query("insert into devolucion_compra values('$cont1','$conpuntoresult','$_POST[id_proveedor]','$_SESSION[id]','$cont1','$_POST[fecha_actual]','$_POST[hora_actual]'
    ,'$_POST[tipo_comprobante]','$_POST[secuencial]','$_POST[autorizacion_credito]'
    ,'$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]','$_POST[tot]','$_POST[observaciones]','Activo','$num_clave','$_POST[fecha_registro_nc]','$_POST[secuencial_nc]','$_POST[autorizacion_nc]','No','$_POST[fecha_emision_nc]','$_POST[tipo_devolucion]')");
    //    insert_registro('CREACION ' . $_POST[tipo_comprobante] . 'DEVO COMPRA: ' . $cont1 . ', DEL PROVEEDOR CON ID: ' . $_POST[id_proveedor] . ', CON FORMA DE PAGO:  Y TOTAL DE: ' . $_POST[tot]);
}
// agregar detalle_dev_compra
$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);
$arreglo5 = explode('|', $campo5);
$arreglo6 = explode('|', $campo6);
$arreglo7 = explode('|', $campo7);
$arreglo8 = explode('|', $campo8);
$nelem = count($arreglo1);
for ($i = 1; $i < $nelem; $i++) {
    $cont2 = 0;
    $consulta = pg_query("select max(id_detalle_devcompra) from detalle_devolucion_compra");
    while ($row = pg_fetch_row($consulta)) {
        $cont2 = $row[0];
    }
    $cont2++;
    $cont_v = 0;
    pg_query("insert into detalle_devolucion_compra values('$cont2','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo', '$arreglo6[$i]','$arreglo7[$i]','$arreglo8[$i]')");
    $consulta2 = pg_query("select * from detalle_producto_bodega where id_bodega=$conpuntoresult and cod_productos=$arreglo1[$i]");
    while ($row = pg_fetch_row($consulta2)) {
        $cod_pro = $row[1];
        $id_bod = $row[2];
        $stock = $row[6];
    }
    $cal = $stock - $arreglo2[$i];
    $consulta2 = pg_query("select * from kardex_valorizado where cod_productos = '$arreglo1[$i]' order by id_kardex asc");
    while ($row = pg_fetch_row($consulta2)) {
        $cantidad = $row[9];
        $precio_total = $row[11];
    }
    $cantidad_entrada = $arreglo2[$i];
    $precio_unitario_entrada = number_format($arreglo3[$i], 4, '.', '');
    $precio_total_entrada = number_format($arreglo2[$i] * $arreglo3[$i], 2, '.', '');
    $cantidad_total = $cantidad - $arreglo2[$i];

    $precio_total_total = number_format($precio_total - $precio_total_entrada, 2, '.', '');
    $precio_unitario_total = number_format($precio_total_total / $cantidad_total, 4, '.', '');

    $consulta3 = pg_query("select * from series_compra where cod_productos = '$arreglo1[$i]' and id_factura_compra ='$_POST[id_factura_compra]'");
    while ($row = pg_fetch_row($consulta3)) {
        pg_query("delete from series_compra  where cod_productos='$arreglo1[$i]' and id_factura_compra='$_POST[id_factura_compra]' and estado='Pasivo'");
    }
    if ($arreglo6[$i] != 0) {
        $arreglo2[$i] = $arreglo6[$i];
    } else {
        $arreglo2[$i] = $arreglo2[$i];
    }
    procesarKardexSalida($arreglo1[$i], 'DV.Com:' . $_POST["serie"], $arreglo2[$i], obtenerStock($arreglo1[$i], $_SESSION['PV']), $arreglo3[$i], 'Activo', $conpuntoresult, 'DC', $cont1, $arreglo5[$i], NULL, NULL, $_POST['id_proveedor'], '', NULL, NULL, $_SESSION['id']);
    //    procesarKardexSalida($arreglo1[$i], 'DV.Com ' . $_POST['serie'], $arreglo2[$i], $stock, NULL, 'Activo', $_SESSION['PV'], 'DC', $cont1, $arreglo5[$i], NULL, NULL, $_POST['id_proveedor'], '', NULL, NULL, $_SESSION['id']);
    ///////////////////////////////
    ///////////////////////////////
    ///////////////////////////////
    ////////modificar los pagos///
    /*$consulta2 = pg_query("select * from pagos_compra where id_pagos_compra = '$arreglo1[$i]'");
    while ($row = pg_fetch_row($consulta2)) {
        $saldo = $row[9];
    }

    $cal = $saldo - $arreglo6[$i];
    $format_numero = number_format($cal, 2, '.', '');

    if ($format_numero == 0.00) {
        // echo '<br>GUARDAR FACTURA pagos_compra1: <br>' . "Update pagos_compra Set saldo='" . $format_numero . "', estado='Cancelado' where id_pagos_compra='" . $arreglo1[$i] . "' and comprao_gasto='" . $arreglo8[$i] . "'";//////////////////////////
        pg_query("Update pagos_compra Set saldo='" . $format_numero . "', estado='Cancelado' where id_pagos_compra='" . $arreglo1[$i] . "' and comprao_gasto='" . $arreglo8[$i] . "'");
    } else {
        //echo '<br>GUARDAR FACTURA pagos_compra2: <br>' . "Update pagos_compra Set saldo='" . $format_numero . "' where id_pagos_compra='" . $arreglo1[$i] . "' and comprao_gasto='" . $arreglo8[$i] . "'";//////////////////////////
        pg_query("Update pagos_compra Set saldo='" . $format_numero . "' where id_pagos_compra='" . $arreglo1[$i] . "' and comprao_gasto='" . $arreglo8[$i] . "'");
    } */
}

///////////////////////////////
///////ASIENTO CONTABLE////////
///////////////////////////////
$iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
$fila1 = pg_fetch_row($iddettran);
$provee1 = $_POST['id_proveedor'];
$prove = pg_query("select identificacion_pro from proveedores where id_proveedor='$provee1'"); //4
$p = pg_fetch_row($prove);
//IDENTIFICACION PROVEEDOR

$idtran = pg_query("select max(id_transacciones) from transacciones"); //3
$fila = pg_fetch_row($idtran);
$fila[0] = $fila[0] + 1;
//CONTADOR TRANSACCIONES    

$ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1' and id_empresa='$_SESSION[PV]'"); //2
$res = pg_fetch_row($ing);
//SECUENCIAL SIN GUARDAR POR MODULO

$ing_pv = pg_query("select max(id_transaccion_pv::int) from transacciones where  id_empresa= '$_SESSION[PV]'"); //1
$res_pv = pg_fetch_row($ing_pv);
if (empty($_POST['op_descuento'])) {
    insertTransaccion($fila[0], ($res[0] + 1), ($res_pv[0] + 1), $cont1, $p[0]);
    insertDetallesTransaccionFormaPago($fila[0], $cont1);
    insertDetalleTransaccionInventario($fila[0]);
} else if (!empty($_POST['op_descuento'])) {
    insertTransaccion($fila[0], ($res[0] + 1), ($res_pv[0] + 1), $cont1, $p[0]);
    insertDetallesTransaccionFormaPago($fila[0], $cont1);
    insertDetalleTransaccionDescuento($fila[0]);
}

$data = $cont1;
echo $data;

///FORMAS PAGO MIXTO
function getIdDetTransaccion()
{
    $sql = "SELECT COALESCE(max(id_detalle_transaccion),0) FROM detalle_transaccion;";
    $res = pg_query($sql);
    $row = pg_fetch_row($res);
    $id = $row[0] + 1;
    return $id;
}
function insertTransaccion($idtrans, $numtrans, $idtranspv, $iddev, $rucprov)
{
    global $conpuntoresult;
    $asiento = pg_query("insert into transacciones values('" . $idtrans . "', '$_SESSION[id]', '" . $iddev . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'NOTA CREDITO COMPRA: " . $rucprov . ", COMPROBANTE: " . $_POST['comprobante'] . " ','$_POST[tot]', ' $_POST[tot]', '0.000','1','" . $numtrans . "','Activo','$_POST[id_proveedor]','','','','','DC','','$conpuntoresult','$_POST[fecha_actual]','" . $idtranspv . "' )");
}
function insertDetallesAsiento($idtrans, $idcuenta, $debito, $credito)
{
    $id = getIdDetTransaccion();
    $sql = "insert into detalle_transaccion values('$id','$idtrans','$idcuenta','$debito','" . $credito . "','Activo')";
    $res = pg_query($sql);
    if (empty($res)) {
        return 0;
    }
    return $id;
}
function insertDetalleTransaccionInventario($idtrans)
{
    $sql = pg_query("SELECT id_plan_cuentas FROM plan_cuentas where  codigo_plan like '%1.1.03.01.01%'"); //ID 63 INVENTARIO DE MERCADERIA
    $buscaCuenta = pg_fetch_row($sql);
    insertDetallesAsiento($idtrans, $buscaCuenta[0], '0.000', $_POST["tot"]);
}
function insertDetalleTransaccionDescuento($idtrans)
{
    $sql = pg_query("SELECT id_plan_cuentas FROM plan_cuentas where  codigo_plan like '%5.1.01.04%'"); // ID 346 Descuento Especial Compras
    $buscaCuenta = pg_fetch_row($sql);
    insertDetallesAsiento($idtrans, $buscaCuenta[0], '0.000', $_POST["tot"]);
}
function insertDetallesTransaccionFormaPago($idtrans, $iddev)
{
    /*$sql = "
    select 
    fpm.forma_pago,
    fpm.valor,
    fpm.id_cuenta
    from devolucion_compra dv,
    formas_pago_mixto_nc fpm
    where dv.id_devolucion_compra = fpm.id_devolucion_compra
    and dv.id_devolucion_compra = '$iddev'
    ";*/
    $sql = "
    select 
    fpm.forma_pago,
    sum(fpm.valor)valor,
    fpm.id_cuenta
    from devolucion_compra dv,
    formas_pago_mixto_nc fpm
    where dv.id_devolucion_compra = fpm.id_devolucion_compra
    and dv.id_devolucion_compra = '$iddev'
    group by forma_pago, fpm.id_cuenta
    ";
    $res = pg_query($sql);
    if (pg_num_rows($res) > 0) {
        $rows = pg_fetch_all($res);
        foreach ($rows as $value) {
            if ($value["forma_pago"] == 'CXP') {
                $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CUENTAS POR PAGAR'");
                $fila2 = pg_fetch_row($plancaja);
                $forma = $fila2[0];
                insertDetallesAsiento($idtrans, $forma, $value["valor"], "0.000");
            } else if ($value["forma_pago"] == 'VALOR_FAVOR_EMPRESA') {
                $plancaja = pg_query("select cuenta_debito from parametros where descripcion='NC PROVEEDORES'");
                $fila2 = pg_fetch_row($plancaja);
                $forma = $fila2[0];
                insertDetallesAsiento($idtrans, $forma, $value["valor"], "0.000");
            }
        }
    }
}
