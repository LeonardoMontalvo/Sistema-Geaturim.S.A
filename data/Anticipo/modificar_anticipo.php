<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
date_default_timezone_set('America/Guayaquil');
$hora = date('h:i:s A', time());
$hora = strtotime($hora);
$conexion = conectarse();
$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];
$campo5 = $_POST['campo5'];

$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);
$arreglo5 = explode('|', $campo5);
$nelem = count($arreglo1);

function error_log_fv($errno, $errstr, $errfile, $errline) {
    $ddf = fopen('../../error.log', 'a');
    $errfile = explode('/', $errfile);
    $errfile = $errfile[count($errfile) - 1];
    fwrite($ddf, "[" . date("r") . "] Error $errno-$errfile-$errline: $errstr\r\n");
    fclose($ddf);
}

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
$cliente1 = $_POST['id_empleado'];


for ($i = 1; $i < $nelem; $i++) {
    if ($arreglo5[$i] == "") {

        //GUARDAR SI DETALLE ES DIFERENTE A DETALLE GUARDADO EN BASE

        $cont4 = 0;
        $consulta = pg_query("select max(id_anticipos) from anticipos");
        while ($row = pg_fetch_row($consulta)) {
            $cont4 = $row[0];
        }
        $cont4++;

        pg_query("insert into anticipos values('$cont4','$_POST[fecha_actual]','$arreglo2[$i]','$arreglo4[$i]','$arreglo3[$i]',$_POST[valor_total],'$_POST[id_empleado]','Activo','$_POST[slct_anio_cf]','$_POST[select_mes]','$_SESSION[id]','$_POST[fecha_actual]')");

        ////////////////////////////////////
        /////////INSERT INTO TRANSACCIONES//
        ////////////////////////////////////

        $idtran = pg_query("select max(id_transacciones) from transacciones");
        $fila = pg_fetch_row($idtran);
        $fila[0] = $fila[0] + 1;
        //CONTADOR TRANSACCIONES 
        $prove = pg_query("select identificacion,nombres_empleado from empleado where id_empleado='$cliente1'");
        $p = pg_fetch_row($prove);
        //CEDULA EMPLEADO

        $ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1' and id_empresa='$_SESSION[PV]'");
        $res = pg_fetch_row($ing);
        //SECUENCIAL SIN GUARDAR POR MODULO

        $ing_pv = pg_query("select max(id_transaccion_pv::int) from transacciones where  id_empresa= '$_SESSION[PV]'");
        $res_pv = pg_fetch_row($ing_pv);
//        echo 'arregolo'.$arreglo2[$i];
        //SECUENCIAL AL GUARDAR POR MODULO
//        echo '::' . "insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont4 . "','$arreglo2[$i]','$hora', 'ANTICIPO NOMINA : " . $p[0] . ":" . $p[1] . ", COMPROBANTE:  " . $cont4 . ", DE: " . $arreglo3[$i] . "', '$arreglo4[$i]', '$arreglo4[$i]', '0.000','1','" . ($res[0] + 1) . "','Activo','$_POST[id_empleado]','','','','','ANTN','','$conpuntoresult','$arreglo2[$i]','" . ($res_pv[0] + 1) . "' )";
        $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont4 . "','$arreglo2[$i]','$hora', 'ANTICIPO NOMINA : " . $p[0] . ":" . $p[1] . ", COMPROBANTE: " . $cont4 . ", DE: " . $arreglo3[$i] . "', '$arreglo4[$i]', '$arreglo4[$i]', '0.000','1','" . ($res[0] + 1) . "','Activo','$_POST[id_empleado]','','','','','ANTN','','$conpuntoresult','$arreglo2[$i]','" . ($res_pv[0] + 1) . "' )");

        
        
        
        if ($_POST['mixtoAnticipo'] == "CONTADO") {

            $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
            $fila1 = pg_fetch_row($iddettran);
            //CONTADOR DETALLE TRANSACCIONES
            // CUENTA DEBE
            $sql = pg_query("SELECT id_plan_cuentas FROM plan_cuentas where descripcion like '%ANTICIPOS SUELDOS%'");
            $buscaCuenta = pg_fetch_row($sql);
            $fila1[0] = $fila1[0] + 1;
//            echo '<br>DETALLE TRANSACCION CREDITO: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo4[$i]','0.000','Activo')"; //////////////////////////
            if (pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo4[$i]','0.000','Activo')")) {
                $data = 2;
            } else {
                error_log_fv(0, "id_detalle_transaccion= $fila1[0]", "modificar_anticipo.php", 1);
                error_log_fv(0, pg_last_error($conexion), "modificar_anticipo.php", 2);
                $data = 60;
            }
            // CUENTA CREDITO
            $sql = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
            $buscaCuenta = pg_fetch_row($sql);
            $fila1[0] = $fila1[0] + 1;
//            echo '<br>DETALLE TRANSACCION DEBITO: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','$arreglo4[$i]','Activo')";
            if (pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','$arreglo4[$i]','Activo')")) {
                $data = 2;
            } else {
                error_log_fv(0, "id_detalle_transaccion= $fila1[0]", "modificar_anticipo.php", 3);
                error_log_fv(0, pg_last_error($conexion), "modificar_anticipo.php", 4);
                $data = 60;
            }
            $fila1 = pg_fetch_row($iddettran);
        } else { //MIXTO
            $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
            $fila1 = pg_fetch_row($iddettran);
            //CONTADOR DETALLE TRANSACCIONES   
            //CUENTA DEBITO
            $sql = pg_query("SELECT id_plan_cuentas FROM plan_cuentas where descripcion like '%ANTICIPOS SUELDOS%'");
            $buscaCuenta = pg_fetch_row($sql);
            $fila1[0] = $fila1[0] + 1;
//            echo '<br>DETALLE TRANSACCION DEBITO1: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo4[$i]','0.000','Activo')"; //////////////////////////
            if (pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo4[$i]','0.000','Activo')")) {
                $data = 2;
            } else {
                error_log_fv(0, "id_detalle_transaccion= $fila1[0]", "modificar_anticipo.php", 5);
                error_log_fv(0, pg_last_error($conexion), "modificar_anticipo.php", 6);
                $data = 60;
            }
            $consulta_mixto = pg_query("select formas_pago_mixto_anti.forma_pago,formas_pago_mixto_anti.valor,formas_pago_mixto_anti.id_cuenta from anticipos, formas_pago_mixto_anti where anticipos.id_anticipos=formas_pago_mixto_anti.id_anticipos and anticipos.id_anticipos='$cont4' and formas_pago_mixto_anti.forma_pago='CONTADO'");
            while ($row = pg_fetch_row($consulta_mixto)) {
                $cont2_mixto_contado = $row[0];
                $valor_contado = $row[1];
                $id_cuenta_contado = $row[2];
            }

            //MIXTO CONTADO
            if ($cont2_mixto_contado != "") {

                $sql = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
                $buscaCuenta = pg_fetch_row($sql);
                $fila1[0] = $fila1[0] + 1;

//                echo '<br>DETALLE TRANSACCION CREDITO: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','$valor_contado','Activo')"; //////////////////////////
                if (pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','$valor_contado','Activo')")) {
                    $data = 2;
                } else {
                    error_log_fv(0, "id_detalle_transaccion= $fila1[0]", "modificar_anticipo.php", 7);
                    error_log_fv(0, pg_last_error($conexion), "modificar_anticipo.php", 8);
                    $data = 60;
                }
            }
            //MIXTO TRANSFERENCIAS
            $consulta_mixto = pg_query("select formas_pago_mixto_anti.forma_pago,formas_pago_mixto_anti.valor,formas_pago_mixto_anti.id_cuenta from anticipos, formas_pago_mixto_anti where anticipos.id_anticipos=formas_pago_mixto_anti.id_anticipos and anticipos.id_anticipos='$cont4' and formas_pago_mixto_anti.forma_pago='TRANSFERENCIAS'");
            while ($row = pg_fetch_row($consulta_mixto)) {
                $cont2_mixto_transferencias = $row[0];
                $valor_transferencias = $row[1];
                $id_cuenta_banco = $row[2];
            }
            if ($cont2_mixto_transferencias != "") {
                $fila1[0] = $fila1[0] + 1;

//                echo '<br>DETALLE TRANSACCION TRANSFERENCIAS: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco','0.000','$valor_transferencias','Activo')"; //////////////////////////
                if (pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco','0.000','$valor_transferencias','Activo')")) {
                    $data = 2;
                } else {
                    error_log_fv(0, "id_detalle_transaccion= $fila1[0]", "modificar_anticipo.php", 9);
                    error_log_fv(0, pg_last_error($conexion), "modificar_anticipo.php", 10);
                    $data = 60;
                }
            }

            //MIXTO CHEQUE
//            echo 'formas_cheque'."select formas_pago_mixto_anti.forma_pago,formas_pago_mixto_anti.valor,formas_pago_mixto_anti.id_cuenta from anticipos, formas_pago_mixto_anti where anticipos.id_anticipos=formas_pago_mixto_anti.id_anticipos and anticipos.id_anticipos='$cont4' and formas_pago_mixto_anti.forma_pago='CHEQUE'";
            $consulta_mixto = pg_query("select formas_pago_mixto_anti.forma_pago,formas_pago_mixto_anti.valor,formas_pago_mixto_anti.id_cuenta from anticipos, formas_pago_mixto_anti where anticipos.id_anticipos=formas_pago_mixto_anti.id_anticipos and anticipos.id_anticipos='$cont4' and formas_pago_mixto_anti.forma_pago='CHEQUE'");
            while ($row = pg_fetch_row($consulta_mixto)) {
                $cont2_mixto_cheque = $row[0];
                $valor_cheque = $row[1];
                $id_cuenta_cheque = $row[2];
            }
            if ($cont2_mixto_cheque != "") {

                $fila1[0] = $fila1[0] + 1;

//                echo '<br>DETALLE TRANSACCION CHEQUE: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_cheque','0.000','$valor_cheque','Activo')"; //////////////////////////
                if (pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_cheque','0.000','$valor_cheque','Activo')")) {
                    $data = 2;
                } else {
                    error_log_fv(0, "id_detalle_transaccion= $fila1[0]", "modificar_anticipo.php", 11);
                    error_log_fv(0, pg_last_error($conexion), "modificar_anticipo.php", 12);
                    $data = 60;
                }
            }
        }
    }
}


echo $data;
?>
