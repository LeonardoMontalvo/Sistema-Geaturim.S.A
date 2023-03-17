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
$camponc = $_POST['camponc'];
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
if ($_POST['tipo_pago'] == "EXTERNA") {
    ///////////////////////////////////////////
    for ($i = 1; $i < $nelem; $i++) {
        /////////////////contador  pagos///////////
        $cont1 = 0;
        $consulta = pg_query("select max(id_pagos_cobrar) from pagos_cobrar");
        while ($row = pg_fetch_row($consulta)) {
            $cont1 = $row[0];
        }
        $cont1++;
        ////////////////////////////////////////////
        ////////////guardar pagos////////
        pg_query("insert into pagos_cobrar values('$cont1','$_POST[id_cliente]','$_SESSION[id]','$cont1','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[forma_pago]','$_POST[tipo_pago]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','$arreglo6[$i]','$arreglo7[$i]','$_POST[observaciones]','Activo',$conpuntoresult, '$_POST[bancos]')");
        ////////////////////////////////////////
        //
        ////////////modificar pagos////////
        $consulta2 = pg_query("select * from c_cobrarexternas where id_c_cobrarexternas = '$arreglo1[$i]'");
        while ($row = pg_fetch_row($consulta2)) {
            $saldo = $row[10];
        }
        //////////////////////////////////
        // $cal =   $arreglo7[$i] - $saldo;
        //       $format_numero = number_format($cal, 2, '.', '');
        // $format_numero = $cal;
        $format_numero = $arreglo7[$i];

        if ($format_numero == 0.00) {
            pg_query("Update c_cobrarexternas Set saldo='" . $format_numero . "', estado='Cancelado' where id_c_cobrarexternas='" . $arreglo1[$i] . "'");
        } else {
            pg_query("Update c_cobrarexternas Set saldo='" . $format_numero . "' where id_c_cobrarexternas='" . $arreglo1[$i] . "'");
            //////////////////////////////
        }
        ///////asiento contable
        $cliente1 = $_POST['id_cliente'];
        $prove = pg_query("select identificacion from clientes where id_cliente='$cliente1'");
        $p = pg_fetch_row($prove);
        $ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1' and id_empresa= '$_SESSION[PV]'");
        $res = pg_fetch_row($ing);
        $ing_pv = pg_query("select max(id_transaccion_pv::int) from transacciones where  id_empresa= '$_SESSION[PV]'");
        $res_pv = pg_fetch_row($ing_pv);

        $idtran = pg_query("select max(id_transacciones) from transacciones");
        $fila = pg_fetch_row($idtran);
        $fila[0] = $fila[0] + 1;
        if ($_POST['forma_pago'] == "CHEQUE") {
            $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'CUENTA POR COBRAR, CLIENTE: " . $p[0] . ", COMPROBANTE: CHEQUE:" . "" . "$_POST[cheque_tarjeta]" . " " . "$_POST[bancos]', '$arreglo6[$i]', '$arreglo6[$i]', '0.000','1','" . ($res[0] + 1) . "','Activo' ,'$_POST[id_cliente]','','','','','CxC','','$conpuntoresult' ,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')");
        } else if ($_POST['forma_pago'] == "TARJETA") {
            $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'CUENTA POR COBRAR, CLIENTE: " . $p[0] . ", COMPROBANTE: TARJETA:" . "" . "$_POST[cheque_tarjeta]" . " " . "$_POST[bancos]', '$arreglo6[$i]', '$arreglo6[$i]', '0.000','1','" . ($res[0] + 1) . "','Activo','$_POST[id_cliente]','','','','','CxC','','$conpuntoresult','$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "' )");
        } else if ($_POST['forma_pago'] == "CONTADO") {
            $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'CUENTA POR COBRAR, CLIENTE: " . $p[0] . ", COMPROBANTE: CONTADO', '$arreglo6[$i]', '$arreglo6[$i]', '0.000','1','" . ($res[0] + 1) . "','Activo','$_POST[id_cliente]','','','','','CxC','','$conpuntoresult','$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "' )");
            //var_dump("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'CUENTA POR COBRAR, CLIENTE: " . $p[0] . ", COMPROBANTE: CONTADO', '$arreglo6[$i]', '$arreglo6[$i]', '0.000','1','" . ($res[0] + 1) . "','Activo','$_POST[id_cliente]','','','','','CxC','','$conpuntoresult','$_POST[fecha_actual]','" . ($res_pv[0] + 1) . " )");
        } else if ($_POST['forma_pago'] == "TRANSFERENCIA") {
            //             echo '<br>GUARDAR FACTURA VENTA: <br>' . "insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'CUENTA POR COBRAR, CLIENTE: " . $p[0] . ", COMPROBANTE: TRANSFERENCIA', $_POST[cheque_tarjeta], '$_POST[bancos]', '0.000','1','" . ($res[0] + 1) . "','Activo','$_POST[id_cliente]','','','','','CxC','','$conpuntoresult' )";//////////////////////////

            $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'CUENTA POR COBRAR, CLIENTE: " . $p[0] . ", COMPROBANTE: TRANSFERENCIA:" . "" . "$_POST[cheque_tarjeta]" . " " . "$_POST[bancos]','$arreglo6[$i]', '$arreglo6[$i]','0.000','1','" . ($res[0] + 1) . "','Activo','$_POST[id_cliente]','','','','','CxC','','$conpuntoresult','$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')");
        }
        //////DETALLES TRANSACCION////
        $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
        $fila1 = pg_fetch_row($iddettran);
        $fila1[0] = $fila1[0] + 1;
        //caja general



        if ($_POST[id_cuentas] != '') {

            $fila2 = $_POST[id_cuentas];
        } else {
            $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
            $fila2 = pg_fetch_row($plancaja);
        }

        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','$arreglo6[$i]','0.000','Activo')");
        $fila1[0] = $fila1[0] + 1;
        ///clientes
        $plancliente = pg_query("select cuenta_debito from parametros where descripcion='CUENTAS POR COBRAR'");
        $fila2 = pg_fetch_row($plancliente);
        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','0.000','$arreglo6[$i]','Activo')");
    }
    $data = 1;
} else {

    //////////////INTERNA//
    $variable = 0;
    $contComprobante = 0;
    $consulta = pg_query("select max(id_pagos_cobrar) from pagos_cobrar");
    while ($row = pg_fetch_row($consulta)) {
        $variable = $row[0];
    }
    //METODO PARA BUSCAR COMPROBANTE MAYOR
    $consultaComprobantantemayor = pg_query("select comprobante from pagos_cobrar where id_pagos_cobrar = '$variable'");
    while ($rowComprobante = pg_fetch_row($consultaComprobantantemayor)) {
        $contComprobante = $rowComprobante[0];
    }
    $contComprobante++;

    for ($i = 1; $i < $nelem; $i++) {

        /////////////////contador  pagos///////////
        $cont1 = 0;
        $cont2 = 0;

        $consulta = pg_query("select max(id_pagos_cobrar) from pagos_cobrar");
        while ($row = pg_fetch_row($consulta)) {
            $cont1 = $row[0];
        }

        $cont1++;

        ////////////guardar pagos////////
        //        echo '<br>GUARDAR FACTURA VENTA: <br>' . "insert into pagos_cobrar values('$cont1','$_POST[id_cliente]','$_SESSION[id]','$contComprobante','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[forma_pago]','$_POST[tipo_pago]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','$arreglo6[$i]','$arreglo7[$i]','$_POST[observaciones]','Activo',$conpuntoresult, '$_POST[bancos]')"; //////////////////////////

        pg_query("insert into pagos_cobrar values('$cont1','$_POST[id_cliente]','$_SESSION[id]','$contComprobante','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[forma_pago]','$_POST[tipo_pago]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','$arreglo6[$i]','$arreglo7[$i]','$_POST[observaciones]','Activo',$conpuntoresult, '$_POST[bancos]')");
        ////////modificar los pagos///
        $consulta2 = pg_query("select * from pagos_venta where id_pagos_venta = '$arreglo1[$i]'");
        while ($row = pg_fetch_row($consulta2)) {
            $saldo = $row[9];
        }

        $cal = $saldo - $arreglo6[$i];
        $format_numero = number_format($cal, 2, '.', '');
        print_r("FORMAT_NUMERO" . $format_numero);

        if ($format_numero == '0.00') {
            //            echo '<br>GUARDAR FACTURA VENTAEE: <br>' . "Update pagos_venta Set saldo='" . $format_numero . "', estado='Cancelado' where id_pagos_venta='" . $arreglo1[$i] . "'"; //////////////////////////

            pg_query("Update pagos_venta Set saldo='" . $format_numero . "', estado='Cancelado' where id_pagos_venta='" . $arreglo1[$i] . "'");
        } else {
            //            echo '<br>GUARDAR FACTURA VENTAEERR: <br>' . "Update pagos_venta Set saldo='" . $format_numero . "' where id_pagos_venta='" . $arreglo1[$i] . "'"; //////////////////////////

            pg_query("Update pagos_venta Set saldo='" . $format_numero . "' where id_pagos_venta='" . $arreglo1[$i] . "'");
        }
        ///////////modificar detalles pagos //////// 
        /////////////contador pagos////////////////     
        $consulta4 = pg_query("select max(id_detalles_pagos_interna) from detalles_pagos_internos");
        while ($row = pg_fetch_row($consulta4)) {
            $cont2 = $row[0];
        }
        $cont2++;
        //
        //
        //////procedimiento 1///////
        //        echo '<br>GUARDAR FACTURA detalle_pagos_venta: <br>' . "select * from detalle_pagos_venta where id_pagos_venta = '$arreglo1[$i]' and estado='Activo' order by id_detalle_pagos_venta desc"; //////////////////////////

        $consulta3 = pg_query("select * from detalle_pagos_venta where id_pagos_venta = '$arreglo1[$i]' and estado='Activo' order by id_detalle_pagos_venta desc");
        while ($row = pg_fetch_row($consulta3)) {
            $id = $row[0];
            $saldo2 = $row[4];
            $fecha_mes = $row[2];
        }

        $cal2 = $saldo2 - $arreglo7[$i];
        $format_numero2 = number_format($cal2, 2, '.', '');
        print_r("cal2:" . $cal2 . ":");
        if ($cal2 <= 0) {
            pg_query("Update detalle_pagos_venta Set saldo='0.00', estado='Cancelado'  where id_detalle_pagos_venta = '" . $id . "' and id_pagos_venta='" . $arreglo1[$i] . "' and estado='Activo' ");

            //            echo '<br>GUARDAR FACTURA VENTAEERRG: <br>' . "insert into detalles_pagos_internos values('$cont2','$cont1','$fecha_mes','$saldo2','0.00','Pasivo')"; //////////////////////////

            pg_query("insert into detalles_pagos_internos values('$cont2','$cont1','$fecha_mes','$saldo2','0.00','Pasivo')");
            $cont2++;
        } else {
            pg_query("Update detalle_pagos_venta Set saldo='" . $format_numero2 . "' where id_detalle_pagos_venta = '" . $id . "' and id_pagos_venta='" . $arreglo1[$i] . "' and estado='Activo' ");
            //            echo '<br>GUARDAR FACTURA VENTAEERRGG: <br>' . "insert into detalles_pagos_internos values('$cont2','$cont1','$fecha_mes','" . abs(($saldo2 - $cal2)) . "','$cal2','Activo')"; //////////////////////////

            pg_query("insert into detalles_pagos_internos values('$cont2','$cont1','$fecha_mes','" . abs(($saldo2 - $cal2)) . "','$cal2','Activo')");
            $cont2++;
        }

        //////procedimiento 2///////
        if ($cal2 < 0) {
            $consulta3 = pg_query("select * from detalle_pagos_venta where id_pagos_venta = '$arreglo1[$i]' and estado='Activo' order by id_detalle_pagos_venta desc");
            while ($row = pg_fetch_row($consulta3)) {
                $ids = $row[0];
                $saldo3 = $row[4];
                $fecha_mes2 = $row[2];
            }

            $cal3 = ($saldo3 - (abs($cal2)));
            $format_numero3 = number_format($cal3, 2, '.', '');

            if ($cal3 <= 0) {
                pg_query("Update detalle_pagos_venta Set saldo='0.00' , estado='Cancelado'  where id_detalle_pagos_venta = '" . $ids . "' and id_pagos_venta='" . $arreglo1[$i] . "' and estado='Activo' ");
                //                echo '<br>GUARDAR FF VENTAEERRGG: <br>' . "insert into detalles_pagos_internos values('$cont2','$cont1','$fecha_mes2','$saldo3','0.00','Pasivo')"; //////////////////////////

                pg_query("insert into detalles_pagos_internos values('$cont2','$cont1','$fecha_mes2','$saldo3','0.00','Pasivo')");
                $cont2++;
            } else {
                pg_query("Update detalle_pagos_venta Set saldo='" . $format_numero3 . "' where id_detalle_pagos_venta = '" . $ids . "' and id_pagos_venta='" . $arreglo1[$i] . "' and estado='Activo' ");
                //                echo '<br>GUARDAR FFE VENTAEERRGG: <br>' . "insert into detalles_pagos_internos values('$cont2','$cont1','$fecha_mes2','" . abs($cal2) . "','$cal3','Activo')"; //////////////////////////

                pg_query("insert into detalles_pagos_internos values('$cont2','$cont1','$fecha_mes2','" . abs($cal2) . "','$cal3','Activo')");
                $cont2++;
            }
        }
        ////////////////////////////////
        //
        //////procedimiento 3///////
        if ($cal3 < 0) {
            $consulta3 = pg_query("select * from detalle_pagos_venta where id_pagos_venta = '$arreglo1[$i]' and estado='Activo' order by id_detalle_pagos_venta desc");
            while ($row = pg_fetch_row($consulta3)) {
                $idss = $row[0];
                $saldo4 = $row[4];
                $fecha_mes3 = $row[2];
            }
            //////procedimiento///////
            $cal4 = ($saldo4 - (abs($cal3)));
            $format_numero4 = number_format($cal4, 2, '.', '');

            if ($cal4 <= 0.00) {

                pg_query("Update detalle_pagos_venta Set saldo='0.00' , estado='Cancelado'  where id_detalle_pagos_venta = '" . $idss . "' and id_pagos_venta='" . $arreglo1[$i] . "' and estado='Activo' ");
                //                echo '<br>GUARDAR FFE1 VENTAEERRGG: <br>' . "insert into detalles_pagos_internos values('$cont2','$cont1','$fecha_mes3','$saldo4','0.00','Pasivo')"; //////////////////////////

                pg_query("insert into detalles_pagos_internos values('$cont2','$cont1','$fecha_mes3','$saldo4','0.00','Pasivo')");
                $cont2++;
            } else {
                pg_query("Update detalle_pagos_venta Set saldo='" . $format_numero4 . "' where id_detalle_pagos_venta = '" . $idss . "' and id_pagos_venta='" . $arreglo1[$i] . "' and estado='Activo' ");
                //                echo '<br>GUARDAR FFE2 VENTAEERRGG: <br>' . "insert into detalles_pagos_internos values('$cont2','$cont1','$fecha_mes3','" . abs($cal3) . "','$cal4','Activo')"; //////////////////////////

                pg_query("insert into detalles_pagos_internos values('$cont2','$cont1','$fecha_mes3','" . abs($cal3) . "','$cal4','Activo')");
                $cont2++;
            }
        }

        ///////ASIENTO CONTABLE
        $cliente1 = $_POST['id_cliente'];
        $prove = pg_query("select identificacion from clientes where id_cliente='$cliente1'");
        $p = pg_fetch_row($prove);
        $ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1' and id_empresa= '$_SESSION[PV]'");
        $res = pg_fetch_row($ing);
        $ing_pv = pg_query("select max(id_transaccion_pv::int) from transacciones where  id_empresa= '$_SESSION[PV]'");
        $res_pv = pg_fetch_row($ing_pv);
        $idtran = pg_query("select max(id_transacciones) from transacciones");
        $fila = pg_fetch_row($idtran);
        $fila[0] = $fila[0] + 1;
        if ($_POST['forma_pago'] == "CHEQUE" || $_POST['forma_pago'] == "TARJETA") {
            //            echo '<br>GUARDAR FACTURA CHEQUE TARJETA: <br>' . "insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'CUENTA POR COBRAR, CLIENTE: " . $p[0] . ", COMPROBANTE: CHEQUE :" . "" . "$_POST[cheque_tarjeta]" . " " . "$_POST[bancos]', '$arreglo6[$i]', '$arreglo6[$i]', '0.000','1','" . ($res[0] + 1) . "','Activo','$_POST[id_cliente]','','','','','CxC','','$conpuntoresult' ,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')"; //////////////////////////
            //	 
            $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'CUENTA POR COBRAR, CLIENTE: " . $p[0] . ", COMPROBANTE: CHEQUE:" . "" . "$_POST[cheque_tarjeta]" . " " . "$_POST[bancos]', '$arreglo6[$i]', '$arreglo6[$i]', '0.000','1','" . ($res[0] + 1) . "','Activo','$_POST[id_cliente]','','','','','CxC','','$conpuntoresult','$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "' )");
        } else if ($_POST['forma_pago'] == "TARJETA") {
            $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'CUENTA POR COBRAR, CLIENTE: " . $p[0] . ", COMPROBANTE: TARJETA:" . "" . "$_POST[cheque_tarjeta]" . " " . "$_POST[bancos]', '$arreglo6[$i]', '$arreglo6[$i]', '0.000','1','" . ($res[0] + 1) . "','Activo','$_POST[id_cliente]','','','','','CxC','','$conpuntoresult','$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "' )");
        } else if ($_POST['forma_pago'] == "CONTADO") {
            //            echo '<br>GUARDAR FACTURA CONTADO: <br>' . "insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'CUENTA POR COBRAR, CLIENTE: " . $p[0] . ", COMPROBANTE: CONTADO', '$arreglo6[$i]', '$arreglo6[$i]', '0.000','1','" . ($res[0] + 1) . "','Activo','','','','','','','',$conpuntoresult,'$_POST[fecha_actual]' )"; //////////////////////////
            //	 
            $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'CUENTA POR COBRAR, CLIENTE: " . $p[0] . ", COMPROBANTE: CONTADO', '$arreglo6[$i]', '$arreglo6[$i]', '0.000','1','" . ($res[0] + 1) . "','Activo','$_POST[id_cliente]','','','','','CxC','','$conpuntoresult','$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')");
        } else if ($_POST['forma_pago'] == "TRANSFERENCIA") {
            //            echo '<br>GUARDAR FACTURA TRANSFERENCIA: <br>' . "insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'CUENTA POR COBRAR, CLIENTE: " . $p[0] . ", COMPROBANTE: TRANSFERENCIA:" . "" . "$_POST[cheque_tarjeta]" . " " . "$_POST[bancos]', '$arreglo6[$i]', '$arreglo6[$i]', '0.000','1','" . ($res[0] + 1) . "','Activo','$_POST[id_cliente]','','','','','CxC','','$conpuntoresult','$_POST[fecha_actual]' ,'" . ($res_pv[0] + 1) . "')"; //////////////////////////
            //	 
            $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'CUENTA POR COBRAR, CLIENTE: " . $p[0] . ", COMPROBANTE: TRANSFERENCIA:" . "" . "$_POST[cheque_tarjeta]" . " " . "$_POST[bancos]','$arreglo6[$i]', '$arreglo6[$i]', '0.000','1','" . ($res[0] + 1) . "','Activo','$_POST[id_cliente]','','','','','CxC','','$conpuntoresult' ,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')");
        } else if ($_POST['forma_pago'] == "NOTA_CREDITO") {
            //            echo '<br>GUARDAR FACTURA TRANSFERENCIA: <br>' . "insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'CUENTA POR COBRAR, CLIENTE: " . $p[0] . ", COMPROBANTE: TRANSFERENCIA:" . "" . "$_POST[cheque_tarjeta]" . " " . "$_POST[bancos]', '$arreglo6[$i]', '$arreglo6[$i]', '0.000','1','" . ($res[0] + 1) . "','Activo','$_POST[id_cliente]','','','','','CxC','','$conpuntoresult','$_POST[fecha_actual]' ,'" . ($res_pv[0] + 1) . "')"; //////////////////////////
            //	 
            $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'CUENTA POR COBRAR, CLIENTE: " . $p[0] . ", COMPROBANTE: NOTA CRÉDITO', '$arreglo6[$i]', '$arreglo6[$i]', '0.000','1','" . ($res[0] + 1) . "','Activo','$_POST[id_cliente]','','','','','CxC','','$conpuntoresult','$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')");
        }
        //////DETALLES TRANSACCION////

        $sql = pg_query("select forma_pago from factura_venta where num_factura='" . $_POST['serie'] . "'");
        $formaPagoFac = pg_fetch_row($sql);
        $forma = "";
        if ($_POST['forma_pago'] == "CHEQUE" || $_POST['forma_pago'] == "CONTADO") {

            if ($_POST[id_cuentas] != '') {

                $forma = $_POST[id_cuentas];
            } else {
                $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
                $fila2 = pg_fetch_row($plancaja);
                $forma = $fila2[0];
            }
        }
        if ($_POST['forma_pago'] == "TRANSFERENCIA") {


            $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CUENTAS POR COBRAR'");
            $fila2 = pg_fetch_row($plancaja);
            $forma = $fila2[0];
        }
        if ($_POST['forma_pago'] == "TARJETA") {
            $plancaja = pg_query("select cuenta_debito from parametros where descripcion='TARJETA DE CREDITO'");
            $fila2 = pg_fetch_row($plancaja);
            $forma = $fila2[0];
        }
        if ($_POST['forma_pago'] == "NOTA_CREDITO") {
            $plancaja = pg_query("select cuenta_credito from parametros where descripcion='NC CLIENTES'");
            $fila2 = pg_fetch_row($plancaja);
            $forma = $fila2[0];
        }

        if ($_POST[cuenta_cheque] != 0) {
            $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
            $fila1 = pg_fetch_row($iddettran);
            $fila1[0] = $fila1[0] + 1;
            //        echo '<br>GUARDAR FACTURA TARJETA DE CREDITOrr: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $forma . "','0.000','$arreglo6[$i]','Activo')"; //////////////////////////

            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $forma . "','0.000','$arreglo6[$i]','Activo')");
            $fila1[0] = $fila1[0] + 1;
        } else {

            $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
            $fila1 = pg_fetch_row($iddettran);
            $fila1[0] = $fila1[0] + 1;
            //        echo '<br>GUARDAR FACTURA TARJETA DE CREDITOgg: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $forma . "','$arreglo6[$i]','0.000','Activo')"; //////////////////////////

            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $forma . "','$arreglo6[$i]','0.000','Activo')");
            $fila1[0] = $fila1[0] + 1;
        }

        ///clientes
        if ($_POST[cuenta_cheque] != 0) {
            //            echo '<br>GUARDAR FACTURA CUENTA CHEQUE: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$_POST[cuenta_cheque]','$arreglo6[$i]','0.000','Activo')"; //////////////////////////

            $plancliente = pg_query("select cuenta_debito from parametros where descripcion='CUENTAS POR COBRAR'");
            $fila2 = pg_fetch_row($plancliente);
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$_POST[cuenta_cheque]','$arreglo6[$i]','0.000','Activo')");
        } else {
            //            echo '<br>GUARDAR FACTURA CUENTA CHEQUE1: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','0.000','$arreglo6[$i]','Activo')"; //////////////////////////

            $plancliente = pg_query("select cuenta_debito from parametros where descripcion='CUENTAS POR COBRAR'");
            $fila2 = pg_fetch_row($plancliente);
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','0.000','$arreglo6[$i]','Activo')");
        }
    }
    if (!empty($camponc)) {
        updateFormaPagoNc($camponc);
    }
    $data = 1;
}


echo $data;

function updateFormaPagoNc($ids)
{
    $sql = "
    update formas_pago_mixto_nv
    set estado='Cruzado'
    where id_formas_pago_mixto_nv in ($ids)";
    $res = pg_query($sql);
}
