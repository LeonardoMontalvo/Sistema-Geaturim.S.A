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

$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);
$arreglo5 = explode('|', $campo5);
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
$cliente1 = $_POST['id_empleadoa'];
for ($i = 1; $i < $nelem; $i++) {

    $cont2 = 0;
    $consulta = pg_query("select max(id_anticipos) from anticipos");
    while ($row = pg_fetch_row($consulta)) {
        $cont2 = $row[0];
    }
    $cont2++;

//    echo '<br>INSERT ANTICIPO: <br>' . "insert into anticipos values('$cont2','$arreglo2[$i]','$arreglo2[$i]','$arreglo4[$i]','" . strtoupper($arreglo3[$i]) . "',$_POST[valor_total],'$arreglo1[$i]','Activo','$_POST[slct_anio_cf]','$_POST[select_mes]','$_SESSION[id]','$_POST[fecha_actual]')";
    pg_query("insert into anticipos values('$cont2','$arreglo2[$i]','$arreglo2[$i]','$arreglo4[$i]','" . strtoupper($arreglo3[$i]) . "',$_POST[valor_total],'$arreglo1[$i]','Activo','$_POST[slct_anio_cf]','$_POST[select_mes]','$_SESSION[id]','$_POST[fecha_actual]')");
    ////////////////////////////////////
    /////////INSERT INTO TRANSACCIONES//
    ////////////////////////////////////    
    $idtran = pg_query("select max(id_transacciones) from transacciones");
    $fila = pg_fetch_row($idtran);
    $fila[0] = $fila[0] + 1;
    //CONTADOR TRANSACCIONES 
    $prove = pg_query("select identificacion from empleado where id_empleado='$cliente1'");
    $p = pg_fetch_row($prove);
    //CEDULA EMPLEADO

    $ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1' and id_empresa='$_SESSION[PV]'");
    $res = pg_fetch_row($ing);
    //SECUENCIAL SIN GUARDAR POR MODULO

    $ing_pv = pg_query("select max(id_transaccion_pv::int) from transacciones where  id_empresa= '$_SESSION[PV]'");
    $res_pv = pg_fetch_row($ing_pv);
    //SECUENCIAL AL GUARDAR POR MODULO
//        echo '::' . "insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont2 . "','$arreglo2[$i]','$hora', 'ANTICIPO NOMINA : " . $p[0] . ", COMPROBANTE: ', '$arreglo4[$i]', '$arreglo4[$i]', '0.000','1','" . ($res[0] + 1) . "','Activo','$_POST[id_empleadoa]','','','','','ANTN','','$conpuntoresult','$arreglo2[$i]','" . ($res_pv[0] + 1) . "' )";
    $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont2 . "','$arreglo2[$i]','$hora', 'ANTICIPO NOMINA : " . $p[0] . ", COMPROBANTE: ', '$arreglo4[$i]', '$arreglo4[$i]', '0.000','1','" . ($res[0] + 1) . "','Activo','$_POST[id_empleadoa]','','','','','ANTN','','$conpuntoresult','$arreglo2[$i]','" . ($res_pv[0] + 1) . "' )");

    if ($_POST['mixtoAnticipo'] == "CONTADO") {

        $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
        $fila1 = pg_fetch_row($iddettran);
        //CONTADOR DETALLE TRANSACCIONES
        // CUENTA DEBE
        $sql = pg_query("SELECT id_plan_cuentas FROM plan_cuentas where descripcion like '%ANTICIPOS SUELDOS%'");
        $buscaCuenta = pg_fetch_row($sql);
        $fila1[0] = $fila1[0] + 1;
//        echo '<br>DETALLE TRANSACCION CREDITO: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo4[$i]','0.000','Activo')"; //////////////////////////
        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo4[$i]','0.000','Activo')");

        // CUENTA CREDITO
        $sql = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
        $buscaCuenta = pg_fetch_row($sql);
        $fila1[0] = $fila1[0] + 1;
//        echo '<br>DETALLE TRANSACCION DEBITO: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','$arreglo4[$i]','Activo')";
        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','$arreglo4[$i]','Activo')");
    } else {

        $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
        $fila1 = pg_fetch_row($iddettran);
        //CONTADOR DETALLE TRANSACCIONES   
        //CUENTA DEBITO
        $sql = pg_query("SELECT id_plan_cuentas FROM plan_cuentas where descripcion like '%ANTICIPOS SUELDOS%'");
        $buscaCuenta = pg_fetch_row($sql);
        $fila1[0] = $fila1[0] + 1;
//        echo '<br>DETALLE TRANSACCION DEBITO: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo4[$i]','0.000','Activo')"; //////////////////////////
        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','$arreglo4[$i]','0.000','Activo')");

        $consulta_mixto = pg_query("select formas_pago_mixto_anti.forma_pago,formas_pago_mixto_anti.valor,formas_pago_mixto_anti.id_cuenta from anticipos, formas_pago_mixto_anti where anticipos.id_anticipos=formas_pago_mixto_anti.id_anticipos and anticipos.id_anticipos='$cont2' and formas_pago_mixto_anti.forma_pago='CONTADO'");
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

//            echo '<br>DETALLE TRANSACCION CREDITO: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','$valor_contado','Activo')"; //////////////////////////
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','$valor_contado','Activo')");
        }
        //MIXTO TRANSFERENCIAS
        $consulta_mixto = pg_query("select formas_pago_mixto_anti.forma_pago,formas_pago_mixto_anti.valor,formas_pago_mixto_anti.id_cuenta from anticipos, formas_pago_mixto_anti where anticipos.id_anticipos=formas_pago_mixto_anti.id_anticipos and anticipos.id_anticipos='$cont2' and formas_pago_mixto_anti.forma_pago='TRANSFERENCIAS'");
        while ($row = pg_fetch_row($consulta_mixto)) {
            $cont2_mixto_transferencias = $row[0];
            $valor_transferencias = $row[1];
            $id_cuenta_banco = $row[2];
        }
        if ($cont2_mixto_transferencias != "") {
            $fila1[0] = $fila1[0] + 1;

//            echo '<br>DETALLE TRANSACCION TRANSFERENCIAS: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco','0.000','$valor_transferencias','Activo')"; //////////////////////////
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco','0.000','$valor_transferencias','Activo')");
        }

        //MIXTO CHEQUE
        $consulta_mixto = pg_query("select formas_pago_mixto_anti.forma_pago,formas_pago_mixto_anti.valor,formas_pago_mixto_anti.id_cuenta from anticipos, formas_pago_mixto_anti where anticipos.id_anticipos=formas_pago_mixto_anti.id_anticipos and anticipos.id_anticipos='$cont2' and formas_pago_mixto_anti.forma_pago='CHEQUE'");
        while ($row = pg_fetch_row($consulta_mixto)) {
            $cont2_mixto_cheque = $row[0];
            $valor_cheque = $row[1];
            $id_cuenta_cheque = $row[2];
        }
        if ($cont2_mixto_cheque != "") {

            $fila1[0] = $fila1[0] + 1;

//            echo '<br>DETALLE TRANSACCION CHEQUE: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_cheque','0.000','$valor_cheque','Activo')"; //////////////////////////
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_cheque','0.000','$valor_cheque','Activo')");
        }
    }
}
$data = $cont2;
echo $data;
?>