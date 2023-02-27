<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
date_default_timezone_set('America/Guayaquil');

$hora = date('h:i:s A', time());
$hora = strtotime($hora);

$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];
$campo5 = $_POST['campo5'];
$campo6 = $_POST['campo6'];
$campo7 = $_POST['campo7'];

$campo8 = $_POST['campo8'];
$campo9 = $_POST['campo9'];
$campo10 = $_POST['campo10'];
$campo11 = $_POST['campo11'];
$campo12 = $_POST['campo12'];
$campo13 = $_POST['campo13'];
$campo14 = $_POST['campo14'];

$campo15 = $_POST['campo15'];
$campo16 = $_POST['campo16'];
$campo17 = $_POST['campo17'];
$campo18 = $_POST['campo18'];
$campo19 = $_POST['campo19'];

$cont1 = 0;
$consulta = pg_query("select max(id_rol_pagos) from rol_pagos");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;
$conpuntoresult = $_SESSION['PV'];
$nomina_mes = $_POST['nomina_mes'];
$conpunto = 0;

$consultapunto = pg_query("select * from rol_pagos where mes='$_POST[select_mes]' and anio='$_POST[slct_anio_cf]' ");
while ($row = pg_fetch_row($consultapunto)) {
    $conpunto = $row[0];
}

//if ($nomina_mes == 'on') {
//if ($conpunto == 0) {
    $sql = "insert into rol_pagos values('$cont1','$_POST[fecha_actual]','','$conpuntoresult','$_POST[neto_recibirt]','Activo','$_SESSION[id]','$_POST[slct_anio_cf]','$_POST[select_mes]')";
    pg_query($sql);

    $arreglo1 = explode('|', $campo1);
    $arreglo2 = explode('|', $campo2);
    $arreglo3 = explode('|', $campo3);
    $arreglo4 = explode('|', $campo4);
    $arreglo5 = explode('|', $campo5);
    $arreglo6 = explode('|', $campo6);
    $arreglo7 = explode('|', $campo7);
    $arreglo8 = explode('|', $campo8);
    $arreglo9 = explode('|', $campo9);
    $arreglo10 = explode('|', $campo10);
    $arreglo11 = explode('|', $campo11);
    $arreglo12 = explode('|', $campo12);
    $arreglo13 = explode('|', $campo13);
    $arreglo14 = explode('|', $campo14);
    $arreglo15 = explode('|', $campo15);
    $arreglo16 = explode('|', $campo16);
    $arreglo17 = explode('|', $campo17);
    $arreglo18 = explode('|', $campo18);
    $arreglo19 = explode('|', $campo19);

    $nelem = count($arreglo1);

    for ($i = 1; $i < $nelem; $i++) {

        $cont2 = 0;
        $consulta = pg_query("select max(id_detalle_rol) from detalle_rol");
        while ($row = pg_fetch_row($consulta)) {
            $cont2 = $row[0];
        }
        $cont2++;

        pg_query("insert into detalle_rol values('$cont2','$cont1','$arreglo1[$i]','$arreglo2[$i]','','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','$arreglo6[$i]','','$arreglo7[$i]','$arreglo8[$i]','$arreglo9[$i]','$arreglo10[$i]','$arreglo11[$i]','$arreglo12[$i]','$arreglo13[$i]','$arreglo14[$i]','','$arreglo15[$i]','$arreglo16[$i]','$arreglo17[$i]','$arreglo18[$i]','$arreglo19[$i]')");
        pg_query("Update anticipos Set estado = 'Pasivo' where id_empleado = '$arreglo1[$i]' and anio = '$_POST[slct_anio_cf]' and mes = '$_POST[select_mes]'");
        pg_query("Update multas Set estado = 'Pasivo' where id_empleado = '$arreglo1[$i]' and anio = '$_POST[slct_anio_cf]' and mes = '$_POST[select_mes]'");
        ///////////////////////////////
        ///////ASIENTO CONTABLE////////
        ///////////////////////////////

        $idtran = pg_query("select max(id_transacciones) from transacciones");
        $fila = pg_fetch_row($idtran);
        $fila[0] = $fila[0] + 1;
        //CONTADOR TRANSACCIONES 
        $prove = pg_query("select identificacion,nombres_empleado from empleado where id_empleado='$arreglo1[$i]'");
        $p = pg_fetch_row($prove);
        //CEDULA EMPLEADO

        $ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1' and id_empresa='$_SESSION[PV]'");
        $res = pg_fetch_row($ing);
        //SECUENCIAL SIN GUARDAR POR MODULO

        $ing_pv = pg_query("select max(id_transaccion_pv::int) from transacciones where  id_empresa= '$_SESSION[PV]'");
        $res_pv = pg_fetch_row($ing_pv);
        //SECUENCIAL AL GUARDAR POR MODULO

        if ($_POST['forma_pago'] == "CHEQUE") {
            echo 'CHEQUE' . "insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'ROL DE PAGO, NOMINA: " . $p[0] . ":" . "$p[1]" . ", COMPROBANTE: CHEQUE:" . "" . "$_POST[cheque_tarjeta]" . " " . "$_POST[bancos]', '$_POST[neto_recibirt]', '$_POST[neto_recibirt]', '0.000','1','" . ($res[0] + 1) . "','Activo' ,'$arreglo1[$i]','','','','','RP','','$conpuntoresult' ,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')";
            $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'ROL DE PAGO, NOMINA: " . $p[0] . ":" . "$p[1]" . ", COMPROBANTE: CHEQUE:" . "" . "$_POST[cheque_tarjeta]" . " " . "$_POST[bancos]', '$_POST[neto_recibirt]', '$_POST[neto_recibirt]', '0.000','1','" . ($res[0] + 1) . "','Activo' ,'$arreglo1[$i]','','','','','RP','','$conpuntoresult' ,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')");
        } else if ($_POST['forma_pago'] == "CXP") {
            echo 'CXP' . "insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'ROL DE PAGO, NOMINA: " . $p[0] . ", COMPROBANTE: TARJETA:" . "', '$_POST[neto_recibirt]', '$_POST[neto_recibirt]', '0.000','1','" . ($res[0] + 1) . "','Activo','$arreglo1[$i]','','','','','RP','','$conpuntoresult','$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "' )";
            $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'ROL DE PAGO, NOMINA: " . $p[0] . ", COMPROBANTE: TARJETA:" . "', '$_POST[neto_recibirt]', '$_POST[neto_recibirt]', '0.000','1','" . ($res[0] + 1) . "','Activo','$arreglo1[$i]','','','','','RP','','$conpuntoresult','$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "' )");
        } else if ($_POST['forma_pago'] == "CONTADO") {
            echo 'CONTADO' . "insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'ROL DE PAGO, NOMINA: " . $p[0] . ", COMPROBANTE: CONTADO', '$_POST[neto_recibirt]', '$_POST[neto_recibirt]', '0.000','1','" . ($res[0] + 1) . "','Activo','$arreglo1[$i]','','','','','RP','','$conpuntoresult','$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "' )";
            $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'ROL DE PAGO, NOMINA: " . $p[0] . ", COMPROBANTE: CONTADO', '$_POST[neto_recibirt]', '$_POST[neto_recibirt]', '0.000','1','" . ($res[0] + 1) . "','Activo','$arreglo1[$i]','','','','','RP','','$conpuntoresult','$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "' )");
        } else if ($_POST['forma_pago'] == "TRANSFERENCIA") {
            echo 'TRANSFERENCIA' . "insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'ROL DE PAGO, NOMINA: " . $p[0] . ", COMPROBANTE: TRANSFERENCIA:" . "" . "$_POST[cheque_tarjeta]" . " " . "$_POST[bancos]', '$_POST[neto_recibirt]', '$_POST[neto_recibirt]','0.000','1','" . ($res[0] + 1) . "','Activo','$arreglo1[$i]','','','','','RP','','$conpuntoresult','$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')";
            $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'ROL DE PAGO, NOMINA: " . $p[0] . ", COMPROBANTE: TRANSFERENCIA:" . "" . "$_POST[cheque_tarjeta]" . " " . "$_POST[bancos]', '$_POST[neto_recibirt]', '$_POST[neto_recibirt]','0.000','1','" . ($res[0] + 1) . "','Activo','$arreglo1[$i]','','','','','RP','','$conpuntoresult','$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')");
        }
        //////////////////////////////
        //////DETALLES TRANSACCION////
        //////////////////////////////    
        $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
        $fila1 = pg_fetch_row($iddettran);
        //CONTADOR DETALLE TRANSACCIONES
        ////////////////////////////////////////
/////////////////////DEBE////////////////
//////////////////////////////////////
//////////////////////////////////////
        // CUENTA DEBE GASTO SUELDO
        if ($arreglo3[$i] != "0.00") {
            $sql = pg_query("SELECT id_plan_cuentas FROM plan_cuentas where descripcion like '%SUELDOS%' and codigo_plan like '%5.1.02.01.01.01%'");
            $buscaCuenta = pg_fetch_row($sql);
            $fila1[0] = $fila1[0] + 1;
            echo '<br>DETALLE TRANSACCION SUELDOS D: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo3[$i]','0.000','Activo')";
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo3[$i]','0.000','Activo')");
        }

        // CUENTA DEBE EXTRAS
        if ($arreglo4[$i] != "0.00") {
            $sql = pg_query("SELECT id_plan_cuentas FROM plan_cuentas where descripcion like '%HORAS EXTRAS%' and codigo_plan like '%5.1.02.01.01.02%'");
            $buscaCuenta = pg_fetch_row($sql);
            $fila1[0] = $fila1[0] + 1;
            echo '<br>DETALLE TRANSACCION HORAS EXTRAS D: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo4[$i]','0.000','Activo')";
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo4[$i]','0.000','Activo')");
        }

        // CUENTA DEBE BONOS
        if ($arreglo5[$i] != "0.00") {
            $sql = pg_query("SELECT id_plan_cuentas FROM plan_cuentas where descripcion like '%BONOS VARIOS%' and codigo_plan like '%5.1.02.01.01.03%'");
            $buscaCuenta = pg_fetch_row($sql);
            $fila1[0] = $fila1[0] + 1;
            echo '<br>DETALLE TRANSACCION BONOS VARIOS D: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo5[$i]','0.000','Activo')";
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo5[$i]','0.000','Activo')");
        }

        // CUENTA DEBE APORTE PATRONAL
        if ($arreglo7[$i] != "0.00") {
            $sql = pg_query("SELECT id_plan_cuentas FROM plan_cuentas where descripcion like '%APORTE PATRONAL AL IESS%' and codigo_plan like '%5.1.02%'");
            $buscaCuenta = pg_fetch_row($sql);
            $fila1[0] = $fila1[0] + 1;
            echo '<br>DETALLE TRANSACCION APORTE PATRONAL AL IESS D: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo7[$i]','0.000','Activo')"; //////////////////////////
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo7[$i]','0.000','Activo')");
        }


        // CUENTA DEBE CUENTA DECIMO X111
        if ($arreglo8[$i] != "0.00") {
            $sql = pg_query("SELECT id_plan_cuentas FROM plan_cuentas where descripcion like '%DECIMO TERCER SUELDO%' and codigo_plan like '%5.1.02.01.03.01%'");
            $buscaCuenta = pg_fetch_row($sql);
            $fila1[0] = $fila1[0] + 1;
            echo '<br>DETALLE TRANSACCION DECIMO TERCER SUELDO D: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo8[$i]','0.000','Activo')"; //////////////////////////
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo8[$i]','0.000','Activo')");
        }
        // CUENTA DEBE APORTE PERSONAL 
        if ($arreglo11[$i] != "0.00") {
            $sql = pg_query("SELECT id_plan_cuentas FROM plan_cuentas where descripcion like '%APORTE PERSONAL%' and codigo_plan like '%2.1.07.02.02%'");
            $buscaCuenta = pg_fetch_row($sql);
            $fila1[0] = $fila1[0] + 1;
            echo '<br>DETALLE TRANSACCION CREDITO D: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo11[$i]','0.000','Activo')"; //////////////////////////
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo11[$i]','0.000','Activo')");
        }


        // CUENTA DEBE APORTE DECIMO X1v
        if ($arreglo9[$i] != "0.00") {
            $sql = pg_query("SELECT id_plan_cuentas FROM plan_cuentas where descripcion like '%DECIMO CUARTO SUELDO%' and codigo_plan like '%5.1.02.01.03%'");
            $buscaCuenta = pg_fetch_row($sql);
            $fila1[0] = $fila1[0] + 1;
            echo '<br>DETALLE TRANSACCION CREDITO D: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo9[$i]','0.000','Activo')"; //////////////////////////
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo9[$i]','0.000','Activo')");
        }

        // CUENTA DEBE FONDOS DE RESERVA
        if ($arreglo6[$i] != "0.00") {
            $sql = pg_query("SELECT id_plan_cuentas FROM plan_cuentas where descripcion like '%FONDOS DE RESERVA%' and codigo_plan like '%5.1.02.01.02.02%'");
            $buscaCuenta = pg_fetch_row($sql);
            $fila1[0] = $fila1[0] + 1;
            echo '<br>DETALLE TRANSACCION FONDOS DE RESERVA D: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo6[$i]','0.000','Activo')"; //////////////////////////
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo6[$i]','0.000','Activo')");
        }

////////////////////////////////////////////////
/////////////HABER////////////////////////////////
//////////////////////////////////////////////// 
////////////////////////////////  ////////////////    
        // CUENTA HABER ANTICIPOS SUELDOS
        if ($arreglo12[$i] != "0.00") {
            $sql = pg_query("SELECT id_plan_cuentas FROM plan_cuentas where descripcion like '%ANTICIPOS SUELDOS%' and codigo_plan like '%1.1.02.09.02%'");
            $buscaCuenta = pg_fetch_row($sql);
            $fila1[0] = $fila1[0] + 1;
            echo '<br>DETALLE TRANSACCION CREDITO H: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo12[$i]','0.000','Activo')"; //////////////////////////
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','$arreglo12[$i]','Activo')");
        }
        // CUENTA HABER IESS PERSONAL
        if ($arreglo11[$i] != "0.00") {
            $sql = pg_query("SELECT id_plan_cuentas FROM plan_cuentas where descripcion like '%APORTE PERSONAL%' and codigo_plan like '%2.1.07.02.02%'");
            $buscaCuenta = pg_fetch_row($sql);
            $fila1[0] = $fila1[0] + 1;
            echo '<br>DETALLE TRANSACCION APORTE PERSONAL H: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo11[$i]','0.000','Activo')"; //////////////////////////
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','$arreglo11[$i]','Activo')");
        }
        // CUENTA HABER PRESTAMO QUIROGRAFARIO
        if ($arreglo15[$i] != "0.00") {
            $sql = pg_query("SELECT id_plan_cuentas FROM plan_cuentas where descripcion like '%PRESTAMOS QUIROGRAFARIOS%' and codigo_plan like '%2.1.07.02.03%'");
            $buscaCuenta = pg_fetch_row($sql);
            $fila1[0] = $fila1[0] + 1;
            echo '<br>DETALLE TRANSACCION PRESTAMOS QUIROGRAFARIOS H: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo15[$i]','0.000','Activo')"; //////////////////////////
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','$arreglo15[$i]','Activo')");
        }


        // CUENTA HABER APORTE PATRONAL
        if ($arreglo7[$i] != "0.00") {
            $sql = pg_query("SELECT id_plan_cuentas FROM plan_cuentas where descripcion like '%APORTE PATRONAL%' and codigo_plan like '%2.1.07.02.01%'");
            $buscaCuenta = pg_fetch_row($sql);
            $fila1[0] = $fila1[0] + 1;
            echo '<br>DETALLE TRANSACCION CREDITO H: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','$arreglo7[$i]','Activo')"; //////////////////////////
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','$arreglo7[$i]','Activo')");
        }

        // CUENTA HABER NETO A PAGAR
        // CUENTA HABER NETO A PAGAR
        // CUENTA HABER NETO A PAGAR
        // CUENTA HABER NETO A PAGAR
        $forma = "";
        if ($_POST['forma_pago'] == "CHEQUE" || $_POST['forma_pago'] == "TRANSFERENCIA") {
            echo 'CHEQUE' . $_POST['forma_pago'];
            echo 'idCuenta' . $_POST['idCuenta'];
            if ($_POST['idCuenta'] != '') {

                $forma = $_POST['idCuenta'];
            } else if ($_POST['forma_pago'] == "CONTADO") {
                $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
                $fila2 = pg_fetch_row($plancaja);
                $forma = $fila2[0];
            } else if ($_POST['forma_pago'] == "CXP") {
                $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CUENTAS POR COBRAR'");
                $fila2 = pg_fetch_row($plancaja);
                $forma = $fila2[0];
            }
        }

        // CUENTA HABER CUENTA DECIMO X111
        if ($arreglo8[$i] != "0.00") {
            $sql = pg_query("SELECT id_plan_cuentas FROM plan_cuentas where descripcion like '%DECIMO TERCER SUELDO%' and codigo_plan like '%2.1.07.04.02%'");
            $buscaCuenta = pg_fetch_row($sql);
            $fila1[0] = $fila1[0] + 1;
            echo '<br>DETALLE TRANSACCION DECIMO TERCER SUELDO HABER: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo8[$i]','0.000','Activo')"; //////////////////////////
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','$arreglo8[$i]','Activo')");
        }

        // CUENTA HABER APORTE DECIMO XVL
        if ($arreglo9[$i] != "0.00") {
            $sql = pg_query("SELECT id_plan_cuentas FROM plan_cuentas where descripcion like '%DECIMO CUARTO SUELDO%' and codigo_plan like '%2.1.07.04.03%'");
            $buscaCuenta = pg_fetch_row($sql);
            $fila1[0] = $fila1[0] + 1;
            echo '<br>DETALLE TRANSACCION CREDITO HABER: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo9[$i]','0.000','Activo')"; //////////////////////////
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','$arreglo9[$i]','Activo')");
        }

        //NETO A RECIBIR

        $fila1[0] = $fila1[0] + 1;
        echo '<br>DETALLE TRANSACCION NETO RECIBIR HABER: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $forma . "','0.000','$arreglo19[$i]','Activo')"; //////////////////////////
        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $forma . "','0.000','$arreglo19[$i]','Activo')");

        $data = 1;
    }
//} 
//else {
//    $data = 11;
//}
//} else {
//
//    pg_query($sql);
//// fin
//// agregar detalle inventario
//    $arreglo1 = explode('|', $campo1);
//    $arreglo2 = explode('|', $campo2);
//    $arreglo3 = explode('|', $campo3);
//    $arreglo4 = explode('|', $campo4);
//    $arreglo5 = explode('|', $campo5);
//    $arreglo6 = explode('|', $campo6);
//    $arreglo7 = explode('|', $campo7);
//
//    $arreglo8 = explode('|', $campo8);
//    $arreglo9 = explode('|', $campo9);
//    $arreglo10 = explode('|', $campo10);
//    $arreglo11 = explode('|', $campo11);
//    $arreglo12 = explode('|', $campo12);
//    $arreglo13 = explode('|', $campo13);
//    $arreglo14 = explode('|', $campo14);
//    $arreglo15 = explode('|', $campo15);
//    $arreglo16 = explode('|', $campo16);
//    $arreglo17 = explode('|', $campo17);
//    $arreglo18 = explode('|', $campo18);
//    $arreglo19 = explode('|', $campo19);
//
//    $nelem = count($arreglo1);
//
//    for ($i = 0; $i <= $nelem; $i++) {
//        // contador detalle inventario
//        $cont2 = 0;
//        $consulta = pg_query("select max(id_detalle_rol) from detalle_rol");
//        while ($row = pg_fetch_row($consulta)) {
//            $cont2 = $row[0];
//        }
//        $cont2++;
//
//// echo '<br>GUARDAR FACTURA VENTA: <br>' . "insert into detalle_rol values('$cont2','$cont1','$arreglo1[$i]','$arreglo2[$i]','',$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','$arreglo6[$i]','','$arreglo7[$i]','$arreglo8[$i]','$arreglo9[$i]','$arreglo10[$i]','$arreglo11[$i]','$arreglo12[$i]','$arreglo13[$i]','$arreglo14[$i]','','$arreglo15[$i]','$arreglo16[$i]','$arreglo17[$i]','$arreglo18[$i]','$arreglo19[$i]')";//////////////////////////
//
//        $conpunto1 = 0;
//        $consultapunto = pg_query("select * from rol_pagos,detalle_rol where rol_pagos.id_rol_pagos=detalle_rol.id_rol_pagos and rol_pagos.mes='$_POST[select_mes]' and rol_pagos.anio='$_POST[slct_anio_cf]' and detalle_rol.id_empleado='$arreglo1[$i]'");
//        while ($row = pg_fetch_row($consultapunto)) {
//            $conpunto1 = $row[0];
//        }
//        if ($conpunto1 == 0) {
//            pg_query("insert into detalle_rol values('$cont2','$_POST[id_rol]','$arreglo1[$i]','$arreglo2[$i]','','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','$arreglo6[$i]','','$arreglo7[$i]','$arreglo8[$i]','$arreglo9[$i]','$arreglo10[$i]','$arreglo11[$i]','$arreglo12[$i]','$arreglo13[$i]','$arreglo14[$i]','','$arreglo15[$i]','$arreglo16[$i]','$arreglo17[$i]','$arreglo18[$i]','$arreglo19[$i]')");
//            pg_query("Update anticipos Set estado = 'Pasivo' where id_empleado = '$arreglo1[$i]' and anio = '$_POST[slct_anio_cf]' and mes = '$_POST[select_mes]'");
//            pg_query("Update multas Set estado = 'Pasivo' where id_empleado = '$arreglo1[$i]' and anio = '$_POST[slct_anio_cf]' and mes = '$_POST[select_mes]'");
//
//            $data = 1;
//        }
//    }
//}


echo $data;
?>