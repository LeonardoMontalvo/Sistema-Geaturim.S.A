<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

/////datos detalle factura/////
$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];
$campo5 = $_POST['campo5'];
$campo6 = $_POST['campo6'];
$campo7 = $_POST['campo7'];
$campo8 = $_POST['campo8'];
$campo9 = $_POST['campo9'];
$camponc = $_POST['camponc'];

$formas_pago = $_POST["formas_pago"];
$formas_pago = json_decode($formas_pago, true);

///////////////////////////////
//
////////////agregar pagos////////
$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);
$arreglo5 = explode('|', $campo5);
$arreglo6 = explode('|', $campo6);
$arreglo7 = explode('|', $campo7);
$arreglo8 = explode('|', $campo8);
$arreglo9 = explode('|', $campo9);

$nelem = count($arreglo1);
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

for ($i = 1; $i < $nelem; $i++) {
    if ($arreglo9[$i] == "EXTERNA") {
        /////////////////contador  pagos///////////
        $cont1 = 0;
        $consulta = pg_query("select max(id_cuentas_pagar) from pagos_pagar");
        while ($row = pg_fetch_row($consulta)) {
            $cont1 = $row[0];
        }
        $cont1++;
        ////////////guardar pagos////////
        pg_query("insert into pagos_pagar values('$cont1','$_POST[id_proveedor]','$_SESSION[id]','$_POST[comprobante]','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[forma_pago]','$arreglo9[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','$arreglo6[$i]','$arreglo7[$i]','$_POST[observaciones]','Activo', '$arreglo1[$i]', '$conpuntoresult' ,'$arreglo8[$i]')");
        ////////////////////////////////////////
        //
        ////////////modificar pagos////////
        $consulta2 = pg_query("select * from c_pagarexternas where id_c_pagarexternas = '$arreglo1[$i]'");
        while ($row = pg_fetch_row($consulta2)) {
            $saldo = $row[10];
        }

        $cal = $arreglo7[$i] - $saldo;
        $format_numero = number_format($cal, 2, '.', '');
        if ($format_numero == 0.00) {
            pg_query("Update c_pagarexternas Set saldo='" . $format_numero . "', estado='Cancelado' where id_c_pagarexternas='" . $arreglo1[$i] . "'");
        } else {
            pg_query("Update c_pagarexternas Set saldo='" . $format_numero . "' where id_c_pagarexternas='" . $arreglo1[$i] . "'");
            //////////////////////////////
        }
        ///////asiento contable
        $provee1 = $_POST['id_proveedor'];
        $prove = pg_query("select identificacion_pro from proveedores where id_proveedor='$provee1'");
        $p = pg_fetch_row($prove);
        $ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1' and id_empresa= '$_SESSION[PV]'");
        $res = pg_fetch_row($ing);
        $ing_pv = pg_query("select max(id_transaccion_pv::int) from transacciones where  id_empresa= '$_SESSION[PV]'");
        $res_pv = pg_fetch_row($ing_pv);
        $idtran = pg_query("select max(id_transacciones) from transacciones");
        $fila = pg_fetch_row($idtran);
        $fila[0] = $fila[0] + 1;
        if ($_POST['forma_pago'] == "CHEQUE" || $_POST['forma_pago'] == "TARJETA" || $_POST['forma_pago'] == "TRANSFERENCIA" || $_POST['forma_pago'] == "EFECTIVO") {
            $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'CUENTA POR PAGAR, PROVEEDOR: " . $p[0] . ", COMPROBANTE: CHEQUE, $_POST[cheque_tarjeta], $_POST[bancos]', '$arreglo6[$i]', '$arreglo6[$i]', '0.000','1','" . ($res[0] + 1) . "','Activo','$_POST[id_proveedor]','','','','','CxP','','$conpuntoresult' ,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')");
            //////DETALLES TRANSACCION////
            $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
            $fila1 = pg_fetch_row($iddettran);
            $fila1[0] = $fila1[0] + 1;
            ///clientes
            $plancliente = pg_query("select cuenta_debito from parametros where descripcion='CUENTAS POR PAGAR'");
            $fila2 = pg_fetch_row($plancliente);
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','$arreglo6[$i]','0.000','Activo')");
            //cuenta bancos
            $fila1[0] = $fila1[0] + 1;
            //            	 echo '<br>GUARDAR FACTURA VENTA: <br>' . "insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','$_POST[cuenta_cheque]','0.000','$arreglo6[$i]','Activo')";//////////////////////////
            //	 
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$_POST[cuenta_cheque]','0.000','$arreglo6[$i]','Activo')");
        } else {
            $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'CUENTA POR PAGAR, PROVEEDOR: " . $p[0] . ", COMPROBANTE: EFECTIVO', '$arreglo6[$i]', '$arreglo6[$i]', '0.000','1','" . ($res[0] + 1) . "','Activo','$_POST[id_proveedor]','','','','','CxP','','$conpuntoresult' ,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "' )");
            //////DETALLES TRANSACCION////
            $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
            $fila1 = pg_fetch_row($iddettran);
            $fila1[0] = $fila1[0] + 1;
            ///clientes
            $plancliente = pg_query("select cuenta_debito from parametros where descripcion='CUENTAS POR PAGAR'");
            $fila2 = pg_fetch_row($plancliente);
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','$arreglo6[$i]','0.000','Activo')");
            $fila1[0] = $fila1[0] + 1;
            //caja general
            $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
            $fila2 = pg_fetch_row($plancaja);
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','0.000','$arreglo6[$i]','Activo')");
        }
    } else {
        /////////////////contador  pagos///////////
        $cont1 = 0;
        $consulta = pg_query("select max(id_cuentas_pagar) from pagos_pagar");
        while ($row = pg_fetch_row($consulta)) {
            $cont1 = $row[0];
        }


        $idtran1 = pg_query("select * from pagos_compra where id_pagos_compra = '$arreglo1[$i]'");
        $fila1 = pg_fetch_row($idtran1);
        $fila1[0] = $fila1[2];

        $cont1++;
        ////////////guardar pagos////////
        //        	 echo '<br>GUARDAR FACTURA pagos_pagar0: <br>' . "insert into pagos_pagar values('$cont1','$_POST[id_proveedor]','$_SESSION[id]','$_POST[comprobante]','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[forma_pago]','$_POST[tipo_pago]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','$arreglo6[$i]','$arreglo7[$i]','$_POST[observaciones]','Activo','$fila1[0]')";//////////////////////////

        pg_query("insert into pagos_pagar values('$cont1','$_POST[id_proveedor]','$_SESSION[id]','$_POST[comprobante]','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[forma_pago]','$arreglo9[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','$arreglo6[$i]','$arreglo7[$i]','$_POST[observaciones]','Activo','$fila1[0]','$conpuntoresult','$arreglo8[$i]')");
        ////////////////////////////////////////
        //
        ////////modificar los pagos///
        $consulta2 = pg_query("select * from pagos_compra where id_pagos_compra = '$arreglo1[$i]'");
        while ($row = pg_fetch_row($consulta2)) {
            $saldo = $row[9];
        }

        $cal = $saldo - $arreglo6[$i];
        $format_numero = number_format($cal, 2, '.', '');

        if ($format_numero == 0.00) {
            //            	 echo '<br>GUARDAR FACTURA pagos_compra1: <br>' . "Update pagos_compra Set saldo='" . $format_numero . "', estado='Cancelado' where id_pagos_compra='" . $arreglo1[$i] . "' and comprao_gasto='" . $arreglo8[$i] . "'";//////////////////////////

            pg_query("Update pagos_compra Set saldo='" . $format_numero . "', estado='Cancelado' where id_pagos_compra='" . $arreglo1[$i] . "' and comprao_gasto='" . $arreglo8[$i] . "'");
        } else {
            //             echo '<br>GUARDAR FACTURA pagos_compra2: <br>' . "Update pagos_compra Set saldo='" . $format_numero . "' where id_pagos_compra='" . $arreglo1[$i] . "' and comprao_gasto='" . $arreglo8[$i] . "'";//////////////////////////

            pg_query("Update pagos_compra Set saldo='" . $format_numero . "' where id_pagos_compra='" . $arreglo1[$i] . "' and comprao_gasto='" . $arreglo8[$i] . "'");
        }

        ///////asiento contable
        $provee1 = $_POST['id_proveedor'];
        $prove = pg_query("select identificacion_pro from proveedores where id_proveedor='$provee1'"); //4
        $p = pg_fetch_row($prove);
        $ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1' and id_empresa= '$_SESSION[PV]'"); //2
        $res = pg_fetch_row($ing);
        $ing_pv = pg_query("select max(id_transaccion_pv::int) from transacciones where  id_empresa= '$_SESSION[PV]'"); //1
        $res_pv = pg_fetch_row($ing_pv);
        $idtran = pg_query("select max(id_transacciones) from transacciones"); //3
        $fila = pg_fetch_row($idtran);
        $fila[0] = $fila[0] + 1;
        if ($_POST['forma_pago'] == "CHEQUE" || $_POST['forma_pago'] == "TARJETA" || $_POST['forma_pago'] == "TRANSFERENCIA" || $_POST['forma_pago'] == "NOTA_CREDITO") {
            //            	 echo '<br>GUARDAR FACTURA transacciones1: <br>' . "insert into transacciones values('".$fila[0]."', '$_SESSION[id]', '".$cont1."','$_POST[fecha_actual]','$_POST[hora_actual]', 'CUENTA POR PAGAR, PROVEEDOR: ".$p[0].", COMPROBANTE: CHEQUE, $_POST[cheque_tarjeta], $_POST[bancos]', '$arreglo6[$i]', '$arreglo6[$i]', '0.000','1','".($res[0]+1)."','Activo','$_POST[id_proveedor]','','','','','CxP','','$conpuntoresult'  ,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')";//////////////////////////

            $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'CUENTA POR PAGAR, PROVEEDOR: " . $p[0] . ", COMPROBANTE: CHEQUE, $_POST[cheque_tarjeta], $_POST[bancos]', '$arreglo6[$i]', '$arreglo6[$i]', '0.000','1','" . ($res[0] + 1) . "','Activo','$_POST[id_proveedor]','','','','','CxP','','$conpuntoresult' ,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "' )");
            //////DETALLES TRANSACCION////
            $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
            $fila1 = pg_fetch_row($iddettran);
            $fila1[0] = $fila1[0] + 1;
            ///clientes
            $plancliente = pg_query("select cuenta_debito from parametros where descripcion='CUENTAS POR PAGAR'");
            $fila2 = pg_fetch_row($plancliente);
            //             echo '<br>GUARDAR FACTURA detalle_transaccion2: <br>' . "insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','".$fila2[0]."','$arreglo6[$i]','0.000','Activo')";//////////////////////////

            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','$arreglo6[$i]','0.000','Activo')");
            //cuenta bancos
            $cformap = $_POST["cuenta_cheque"];
            $fila1[0] = $fila1[0] + 1;
            if ($_POST['forma_pago'] == "NOTA_CREDITO") {
                $plancaja = pg_query("select cuenta_debito from parametros where descripcion='NC PROVEEDORES'");
                $fila2 = pg_fetch_row($plancaja);
                $cformap = $fila2[0];
            }
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$cformap','0.000','$arreglo6[$i]','Activo')");
        } else {
            //           	 echo '<br>GUARDAR FACTURA transacciones3: <br>' . "insert into transacciones values('".$fila[0]."', '$_SESSION[id]', '".$cont1."','$_POST[fecha_actual]','$_POST[hora_actual]', 'CUENTA POR PAGAR, PROVEEDOR: ".$p[0].", COMPROBANTE: EFECTIVO', '$arreglo6[$i]', '$arreglo6[$i]', '0.000','1','".($res[0]+1)."','Activo','$_POST[id_proveedor]','','','','','CxP','','$conpuntoresult' ,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "' )";//////////////////////////

            $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'CUENTA POR PAGAR, PROVEEDOR: " . $p[0] . ", COMPROBANTE: EFECTIVO', '$arreglo6[$i]', '$arreglo6[$i]', '0.000','1','" . ($res[0] + 1) . "','Activo','$_POST[id_proveedor]','','','','','CxP','','$conpuntoresult' ,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "' )");
            //////DETALLES TRANSACCION////
            $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
            $fila1 = pg_fetch_row($iddettran);
            $fila1[0] = $fila1[0] + 1;
            ///clientes
            $plancliente = pg_query("select cuenta_debito from parametros where descripcion='CUENTAS POR PAGAR'");
            $fila2 = pg_fetch_row($plancliente);
            //             echo '<br>GUARDAR FACTURA detalle_transaccionGG: <br>' . "insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','".$fila2[0]."','$arreglo6[$i]','0.000','Activo')";//////////////////////////

            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','$arreglo6[$i]','0.000','Activo')");
            $fila1[0] = $fila1[0] + 1;
            //caja general
            $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
            $fila2 = pg_fetch_row($plancaja);
            //             echo '<br>GUARDAR FACTURA detalle_transaccionTT: <br>' . "insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','".$fila2[0]."','0.000','$arreglo6[$i]','Activo')";//////////////////////////

            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','0.000','$arreglo6[$i]','Activo')");
        }
    }
}
guardarFormasPago();
$data = 1;

echo $data;

function updateFormaPagoNc($ids)
{
    $sql = "
    update formas_pago_mixto_nc
    set estado='Cruzado'
    where id_formas_pago_mixto_nc in ($ids)";
    $res = pg_query($sql);
}

function guardarFormasPago()
{
    global $formas_pago;
    foreach ($formas_pago as $key => $value) {
        $id = 1;
        $sqlid = "select max(id_formas_pago_mixto_cxp) from formas_pago_mixto_cxp;";
        $resid = pg_query($sqlid);
        $rowid = pg_fetch_row($resid);
        if (!empty($rowid)) {
            $id = $rowid[0] + 1;
        }
        $idcuenta = "NULL";
        if (!empty($value["id_cuenta"])) {
            $idcuenta = "'$value[id_cuenta]'";
        }
        $sql = "INSERT INTO formas_pago_mixto_cxp(
            id_formas_pago_mixto_cxp, comprobante_pago, fecha_actual, forma_pago, 
            numero_documento, valor, estado, id_cuenta)
        VALUES ($id, '$_POST[comprobante]', '$_POST[fecha_actual]', '$value[forma_pago]', 
                '$value[nro_documento]', '$value[valor]', 'Activo', $idcuenta); ";

        if ($value["forma_pago"] == 'NOTA_CREDITO') {
            updateFormaPagoNc($value["nro_documento"]);
        }
        $res = pg_query($sql);
    }
}
