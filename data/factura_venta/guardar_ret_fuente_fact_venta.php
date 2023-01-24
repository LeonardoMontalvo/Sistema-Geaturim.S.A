<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
// datos detalle factura
$campo1reten = $_POST['campo1reten'];
$campo2reten = $_POST['campo2reten'];
$campo3reten = $_POST['campo3reten'];
$campo4reten = $_POST['campo4reten'];
$campo5reten = $_POST['campo5reten'];
$campo6reten = $_POST['campo6reten'];
$campo7reten = $_POST['campo7reten'];
$arreglo1reten = explode('|', $campo1reten);
$arreglo2reten = explode('|', $campo2reten);
$arreglo3reten = explode('|', $campo3reten);
$arreglo4reten = explode('|', $campo4reten);
$arreglo5reten = explode('|', $campo5reten);
$arreglo6reten = explode('|', $campo6reten);
$arreglo7reten = explode('|', $campo7reten);
$nelemreten = count($arreglo1reten);
// fin
$conpuntoresult = $_SESSION['PV'];
date_default_timezone_set('America/Guayaquil');

//contador factura compra
$cont1 = 0;
$consulta = pg_query("select max(id_retencion_fuente_factura_venta) from retencion_fuente_factura_venta");
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
$comprobar = pg_query("select id_factura from retencion_fuente_factura_venta");
while ($row2 = pg_fetch_row($comprobar)) {
    if ($row2[0] == $_POST['id_factura']) {
        $data = 2;
    }
}
$datos = 0;
$valoreten = 0;
$resultreten = 0;
$fecha = date('Y-m-d', time());
$hora = date('h:i:s A', time());
if ($data != 2) {
    //    echo '<br>GUARDAR FACTURA VENTA1: <br>' . "select SUM(total_venta::float) from detalle_factura_venta where id_factura_venta ='$_POST[id_factura]' and  bien_servicio='B'"; //////////////////////////


    $consulta_bienes = pg_query("select SUM(total_venta::float) from detalle_factura_venta where id_factura_venta ='$_POST[id_factura]' and  bien_servicio='B'");
    while ($row = pg_fetch_row($consulta_bienes)) {
        $valor_totalBienes = $row[0];
    }

    if ($_POST[id_retencion_fuente] == 0) {
        $_POST[id_retencion_fuente] = 1;
    }

    for ($i = 0; $i <= $nelemreten; $i++) {

        if (!empty($arreglo1reten[$i])) {
            $validreten = $_POST['id_retencion_fuente'];

            if ($arreglo2reten[$i] == 'IVA' || $arreglo2reten[$i] == 'IVA SERVICIOS') {
                $arreglo2reten[$i] = 2;
            } else {
                $arreglo2reten[$i] = 1;
            }
            $cont1 = 0;
            $consulta = pg_query("select max(id_retencion_fuente_factura_venta) from retencion_fuente_factura_venta");
            while ($row = pg_fetch_row($consulta)) {
                $cont1 = $row[0];
            }
            $cont1++;
            $contre = 0;
            $consultare = pg_query("select max(id_detalles_venta_reten) from detallecomprobanteretencion_v");
            while ($row1 = pg_fetch_row($consultare)) {
                $contre = $row1[0];
            }
            $contre++;
            //            echo '<br>GUARDAR FACTURA VENTA12r: <br>' . "insert into retencion_fuente_factura_venta values('" . $cont1 . "', '$_POST[id_factura]', '$arreglo6reten[$i]','$_POST[fecha_retencion]','" . $hora . "','$arreglo1reten[$i]','$_POST[iva_factura]','$arreglo4reten[$i]', '', 'Activo','$_POST[serie_retencion]','$_POST[formaspago_mixto_reten]','$_POST[idCuenta_reten]')"; //////////////////////////

            pg_query("insert into retencion_fuente_factura_venta values('" . $cont1 . "', '$_POST[id_factura]', '$arreglo6reten[$i]','$_POST[fecha_retencion]','" . $hora . "','$arreglo1reten[$i]','$_POST[iva_factura]','$arreglo4reten[$i]', '', 'Activo','$_POST[serie_retencion]','$_POST[formaspago_mixto_reten]','$_POST[idCuenta_reten]')");
            //            echo '<br>GUARDAR FACTURA VENTA12: <br>' . "insert into detallecomprobanteretencion_v values('$contre','$cont1' ,'$arreglo6reten[$i]','$arreglo1reten[$i]','$arreglo2reten[$i]','$arreglo3reten[$i]','$arreglo4reten[$i]')"; //////////////////////////

            pg_query("insert into detallecomprobanteretencion_v values('$contre','$cont1' ,'$arreglo6reten[$i]','$arreglo1reten[$i]','$arreglo2reten[$i]','$arreglo3reten[$i]','$arreglo4reten[$i]')");
            //            echo '<br>GUARDAR FACTURA VENTA123: <br>' . "update retencion_fuente_factura_venta set valor_retencion='$arreglo4reten[$i]'  where id_factura='$cont1' and id_retencion_fuente='$arreglo6reten[$i]' and valor_compra='$arreglo1reten[$i]' "; //////////////////////////

            pg_query("update retencion_fuente_factura_venta set valor_retencion='$arreglo4reten[$i]'  where id_factura='$cont1' and id_retencion_fuente_r='$arreglo6reten[$i]' and valor_compra='$arreglo1reten[$i]' ");
        }

        if ($_POST['valor_seleccion_si_no'] == 1) {
            $consulta_servicio = pg_query("select SUM(total_venta::float) from detalle_factura_venta where id_factura_venta =$_POST[id_factura] and  bien_servicio='S'");
            while ($row = pg_fetch_row($consulta_servicio)) {
                $valor_totalServicio = $row[0];
            }
        }
    }



    ///////////////////
    //    $valreten = $_POST['valor_retencion'];

    $valfac = pg_query("SELECT  monto_credito FROM pagos_venta where  estado='Activo' and id_factura_venta='$_POST[id_factura]'");
    $valfacresult = pg_fetch_row($valfac);

    //    print_r($valfacresult);
    //    $valfac_reten = pg_query("select  sum(dcr.valor_retenido)
    //            FROM retencion_fuente_factura_venta rf, retencion_fuentes_r f, detallecomprobanteretencion_v dcr
    //            WHERE rf.id_factura='$_POST[id_factura]' and rf.id_retencion_fuente_r=f.id_retencion_fuentes_r
    //            AND  dcr.id_retencion_fuente_factura_venta=rf.id_retencion_fuente_factura_venta ");
    //    $valfacresult_reten = pg_fetch_row($valfac_reten);


    $resultreten = $valfacresult[0] - $_POST['total_reten_iva'];

    //    pg_query("UPDATE retencion_fuente_factura_venta set clave='" . $clave . "' where id_factura=$_POST[id_factura] and id_gastos=1");
    // echo '<br>GUARDAR FACTURA monto_credito: <br>' . "update pagos_venta set monto_credito='" . $resultreten . "' , saldo='" . $resultreten . "' where id_factura_venta='$_POST[id_factura]'"; //////////////////////////

    pg_query("update pagos_venta set monto_credito='" . $resultreten . "' , saldo='" . $resultreten . "' where id_factura_venta='$_POST[id_factura]'");
    // echo '<br>GUARDAR FACTURA formas_pago_mixto: <br>' . "update formas_pago_mixto set valor='" . $resultreten . "' where id_factura_venta='$_POST[id_factura]'"; //////////////////////////

    pg_query("update formas_pago_mixto set valor='" . $resultreten . "' where id_factura_venta='$_POST[id_factura]'");

    ///////////////////////
    ////// ASIENTO CONTABLE
    //    $tran = pg_query("select * from transacciones where comprobante='$_POST[id_factura]' and id_tipo_transaccion='1' and concepto like 'VEN%' and id_empresa= $conpuntoresult");
    //    $fila = pg_fetch_row($tran);
    ////    print_r($valfacresult . "entro");
    $idtran = pg_query("select max(id_transacciones) from transacciones");
    $fila = pg_fetch_row($idtran);
    $fila[0] = $fila[0] + 1;
    $consf = pg_query("select dcr.valor_retenido,dcr.id_retencion_fuentes from detallecomprobanteretencion_v dcr, retencion_fuente_factura_venta rff ,factura_venta fc where 
dcr.id_retencion_fuente_factura_venta=rff.id_retencion_fuente_factura_venta
and rff.id_factura=fc.id_factura_venta and fc.id_factura_venta=$_POST[id_factura] and  dcr.id_trete=1
");


    $consf1 = pg_query("select sum(dcr.valor_retenido)from detallecomprobanteretencion_v dcr, retencion_fuente_factura_venta rff ,factura_venta fc where 
dcr.id_retencion_fuente_factura_venta=rff.id_retencion_fuente_factura_venta
and rff.id_factura=fc.id_factura_venta and fc.id_factura_venta=$_POST[id_factura]
");
    $sum_retencion = pg_fetch_row($consf1);


    $xr = 0;

    while ($cont2f = pg_fetch_row($consf)) {
        $cons = pg_query("select cuenta_credito from retencion_fuentes_r where id_retencion_fuentes_r='" . $cont2f[1] . "'");

        while ($cont2 = pg_fetch_row($cons)) {
            $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
            $fila1 = pg_fetch_row($iddettran);
            ////////////////////////////////

            $sub = $_POST['sub'];
            $cliente1 = $_POST['id_cliente'];

            $ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1' and id_empresa= '$_SESSION[PV]'");
            $res = pg_fetch_row($ing);
            $ing_pv = pg_query("select max(id_transaccion_pv::int) from transacciones where  id_empresa= '$_SESSION[PV]'");
            $res_pv = pg_fetch_row($ing_pv);

            $cliente1 = $_POST['id_cliente'];

            $prove = pg_query("select identificacion from clientes where id_cliente='$cliente1'");
            $p = pg_fetch_row($prove);

            //             echo '<br>GUARDAR FACTURA transacciones: <br>' . "insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'RETENCION EN VENTA PRODUCTOS, CLIENTE:" . $p[0] . " , COMPROBANTE: " . $_POST['num_factura'] . "', '" . $sum_retencion[0] . "', '" . $sum_retencion[0] . "', '0.00','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','VEN','',$conpuntoresult,'$_POST[fecha_retencion]')";//////////////////////////
            //	 

            $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'RETENCION EN VENTA PRODUCTOS, CLIENTE:" . $p[0] . " , COMPROBANTE: " . $_POST['num_factura'] . "', '" . $sum_retencion[0] . "', '" . $sum_retencion[0] . "', '0.00','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','VEN','',$conpuntoresult,'$_POST[fecha_retencion]','" . ($res_pv[0] + 1) . "')");



            $fila1[0] = $fila1[0] + 1;
            $plancliente = pg_query("select cuenta_debito from parametros where descripcion='CUENTAS' ");
            $fila2 = pg_fetch_row($plancliente);

            //            echo '<br>GUARDAR FACTURA VENTA1: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$_POST[idCuenta_reten]','0.000','" . $sum_retencion[0] . "','Activo')"; //////////////////////////


            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$_POST[idCuenta_reten]','0.000','" . $sum_retencion[0] . "','Activo')");
            $fila1[0] = $fila1[0] + 1;

            //            echo '<br>GUARDAR FACTURA VENTA2: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $cont2[0] . "','$cont2f[0]','0.000','Activo')"; //////////////////////////


            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $cont2[0] . "','$cont2f[0]','0.000','Activo')");
            $xr = $xr + $cont2f[0];
        }
    }
    //////////RETIENE IVA//////////

    $data_iva = 0;
    $comprobar = pg_query("select id_factura from retencion_iva_factura_venta  ");
    while ($row2 = pg_fetch_row($comprobar)) {
        if ($row2[0] == $_POST[id_factura]) {
            $data_iva = 2;
        }
    }
    if ($data_iva != 2) {
        $cont2 = 0;
        $consultaiva = pg_query("select max(id_retencion_iva_factura_venta) from retencion_iva_factura_venta");
        while ($row = pg_fetch_row($consultaiva)) {
            $cont2 = $row[0];
        }
        $cont2++;

        $validporcentiva = $_POST['porcent_iva'];

        pg_query("insert into retencion_iva_factura_venta values('" . $cont2 . "', '$_POST[id_factura]', '$_POST[id_retencion_iva]','" . $fecha . "','" . $hora . "','$_POST[valor_facturaiva]','$_POST[iva_factura]','$_POST[valor_retencioni]', '$_POST[autorizacion_ret]','Activo','$_POST[serie_retencion]')");

        $consf = pg_query("select dcr.valor_retenido,dcr.id_retencion_fuentes from detallecomprobanteretencion_v dcr, retencion_fuente_factura_venta rff ,factura_venta fc where 
dcr.id_retencion_fuente_factura_venta=rff.id_retencion_fuente_factura_venta
and rff.id_factura=fc.id_factura_venta and fc.id_factura_venta=$_POST[id_factura] and  dcr.id_trete=2");
        $xi = 0;

        while ($cont2f = pg_fetch_row($consf)) {
            $cons = pg_query("select cuenta_credito from retencion_iva_r where id_retencion_iva_r='" . $cont2f[1] . "'");

            while ($cont2 = pg_fetch_row($cons)) {
                $fila1 = 0;
                $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
                $fila1 = pg_fetch_row($iddettran);
                $sub = $_POST['sub'];
                $cliente1 = $_POST['id_cliente'];
                $ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1'");
                $res = pg_fetch_row($ing);
                $ing_pv = pg_query("select max(id_transaccion_pv::int) from transacciones where  id_empresa= '$_SESSION[PV]'");
                $res_pv = pg_fetch_row($ing_pv);



                //                $fila1[0] = $fila1[0] + 1;
                //
                //                $plancliente = pg_query("select cuenta_debito from parametros where descripcion='CUENTAS'");
                //                $fila2 = pg_fetch_row($plancliente);
                //                echo '<br>GUARDAR FACTURA VENTA3: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','0.000','$cont2f[0]','Activo')"; //////////////////////////
                //
                //
                //
                //                pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','0.000','$cont2f[0]','Activo')");

                $fila1[0] = $fila1[0] + 1;
                //                echo '<br>GUARDAR FACTURA VENTA4: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $cont2[0] . "','$cont2f[0]','0.000','Activo')"; //////////////////////////



                pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $cont2[0] . "','$cont2f[0]','0.000','Activo')");
                $xi = $xi + $cont2f[0];
            }
        }

        // 70     --> Retención IVA 30%
        // 75     --> Retención Fuente 1% Transporte
        // 74     --> Retención Fuente 1% Compras
        // 78     --> Retención Fuente 10% Honorarios
        // 77     --> Retención Fuente 8% Arriendos
        // 211    --> DESCUENTOS COMPRAS
        // 28     --> IVA
        // 23     --> Inventario Materia Prima
        //23,24,25,216,217 Excluye retenciones 

        $sql = pg_query(
            "select id_plan_cuentas from detalle_transaccion where id_transacciones='" . $fila[0] . "' "
                . "and id_plan_cuentas<>'94'" //"Retenciones IVA Proveedores"
                . "and id_plan_cuentas<>'95'" //"Retenciones en la Fuente Proveedores"
                . "and id_plan_cuentas<>'96'" //"Retenciones en la Fuente Empleados"
                . "and id_plan_cuentas<>'97'" //"Retenciones en la Fuente Socios"
                . "and id_plan_cuentas<>'98'" //"Retenciones en la Fuente Otros"
                . "and id_plan_cuentas<>'99'" //"Impuesto a la Renta por Pagar"
                . "and id_plan_cuentas<>'100'" //"Retención Fuente 2.75% Servicios"
                . "and id_plan_cuentas<>'101'" //"Retención Fuente 8% Arriendos"
                . "and id_plan_cuentas<>'102'" //"Retención Fuente 10% Honorarios"
                . "and id_plan_cuentas<>'103'" //"Iva en Compras"
                . "and id_plan_cuentas<>'104'" //"Inventario Materia Prima"
                . "and id_plan_cuentas<>'105'" //"Inventario Productos en Proceso"
                . "and id_plan_cuentas<>'106'" //"Inventario Productos Terminados"
                . "and id_plan_cuentas<>'107'" //"Inventario 12%"
                . "and id_plan_cuentas<>'108'" //"Inventario 0%"
                . "and id_plan_cuentas<>'250'" //"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'251'" //"Retención Fuente 10% Honorarios"
                . "and id_plan_cuentas<>'252'" //"Iva en Compras"
                . "and id_plan_cuentas<>'253'" //"Inventario Materia Prima"
                . "and id_plan_cuentas<>'12'" //"Inventario Productos en Proceso"
                . "and id_plan_cuentas<>'23'" //"Inventario Productos Terminados"
                . "and id_plan_cuentas<>'60'" //"Inventario 12%"
                . "and id_plan_cuentas<>'11'" //"Inventario 0%"
                . "and id_plan_cuentas<>'27'" //"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'28'" //"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'110'" //"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'111'" //"Retención Fuente 10% Honorarios"
                . "and id_plan_cuentas<>'112'" //"Iva en Compras"
                . "and id_plan_cuentas<>'116'" //"Inventario Materia Prima"
                . "and id_plan_cuentas<>'114'" //"Inventario Productos en Proceso"
                . "and id_plan_cuentas<>'118'" //"Inventario Productos Terminados"
                . "and id_plan_cuentas<>'117'" //"Inventario 12%"
                . "and id_plan_cuentas<>'475'" //"Inventario 0%"
                . "and id_plan_cuentas<>'140'" //"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'164'" //"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'4'" //"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'270'" //"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'271'" //"Inventario 0%"
                . "and id_plan_cuentas<>'179'" //"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'4'" //"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'9'" //"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'30'" //"Suministros y Materiales Agrícolas"
        );
        $idPlan = pg_fetch_row($sql);
        //         echo '<br>GUARDAR FACTURA VENTArerer: <br>' . "select cuenta_credito from parametros where cuenta_debito='" . $idPlan[0] . "'";//////////////////////////
        //	 
        $plancaja = pg_query("select cuenta_credito from parametros where cuenta_debito='" . $idPlan[0] . "'");
        $caja = pg_fetch_row($plancaja);

        $tot = pg_query("select credito, id_detalle_transaccion from detalle_transaccion where id_transacciones='" . $fila[0] . "' and id_plan_cuentas='" . $caja[0] . "'");
        $s = pg_fetch_row($tot);
        $caja = $s[0] - $xi;


        //        pg_query("update detalle_transaccion set debito='" . $caja . "' where id_detalle_transaccion='" . $s[1] . "'");
        ////////////////////////////////////////////
        /////////////////////////////////////////   



        $data = 1;
        $validiva = $_POST['id_retencion_iva'];
        $valfaciva = $_POST[iva_factura];
        $valiva = $_POST['valor_retencioni'];
    }

    // 70     --> Retención IVA 30%
    // 75     --> Retención Fuente 1% Transporte
    // 74     --> Retención Fuente 1% Compras
    // 78     --> Retención Fuente 10% Honorarios
    // 77     --> Retención Fuente 8% Arriendos
    // 211    --> DESCUENTOS COMPRAS
    // 28     --> IVA
    // 23     --> Inventario Materia Prima
    //23,24,25,216,217 Excluye retenciones 

    $sql = pg_query(
        "select id_plan_cuentas from detalle_transaccion where id_transacciones='" . $fila[0] . "' "
            . "and id_plan_cuentas<>'250'" //"Retenciones IVA Proveedores"
            . "and id_plan_cuentas<>'251'" //"Retenciones en la Fuente Proveedores"
            . "and id_plan_cuentas<>'252'" //"Retenciones en la Fuente Empleados"
            . "and id_plan_cuentas<>'253'" //"Retenciones en la Fuente Socios"
            . "and id_plan_cuentas<>'254'" //"Retenciones en la Fuente Otros"
            . "and id_plan_cuentas<>'255'" //"Impuesto a la Renta por Pagar"
            . "and id_plan_cuentas<>'256'" //"Retención Fuente 2.75% Servicios"
            . "and id_plan_cuentas<>'101'" //"Retención Fuente 8% Arriendos"
            . "and id_plan_cuentas<>'102'" //"Retención Fuente 10% Honorarios"
            . "and id_plan_cuentas<>'103'" //"Iva en Compras"
            . "and id_plan_cuentas<>'278'" //"Inventario Materia Prima"
            . "and id_plan_cuentas<>'105'" //"Inventario Productos en Proceso"
            . "and id_plan_cuentas<>'106'" //"Inventario Productos Terminados"
            . "and id_plan_cuentas<>'107'" //"Inventario 12%"
            . "and id_plan_cuentas<>'108'" //"Inventario 0%"
            . "and id_plan_cuentas<>'250'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'251'" //"Retención Fuente 10% Honorarios"
            . "and id_plan_cuentas<>'252'" //"Iva en Compras"
            . "and id_plan_cuentas<>'253'" //"Inventario Materia Prima"
            . "and id_plan_cuentas<>'12'" //"Inventario Productos en Proceso"
            . "and id_plan_cuentas<>'23'" //"Inventario Productos Terminados"
            . "and id_plan_cuentas<>'60'" //"Inventario 12%"
            . "and id_plan_cuentas<>'11'" //"Inventario 0%"
            . "and id_plan_cuentas<>'27'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'28'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'110'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'111'" //"Retención Fuente 10% Honorarios"
            . "and id_plan_cuentas<>'112'" //"Iva en Compras"
            . "and id_plan_cuentas<>'93'" //"Inventario Materia Prima"
            . "and id_plan_cuentas<>'94'" //"Inventario Productos en Proceso"
            . "and id_plan_cuentas<>'95'" //"Inventario Productos Terminados"
            . "and id_plan_cuentas<>'96'" //"Inventario 12%"
            . "and id_plan_cuentas<>'97'" //"Inventario 0%"
            . "and id_plan_cuentas<>'98'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'99'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'100'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'101'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'91'" //"Iva en Compras"
            . "and id_plan_cuentas<>'92'" //"Inventario Materia Prima"
            . "and id_plan_cuentas<>'93'" //"Inventario Productos en Proceso"
            . "and id_plan_cuentas<>'94'" //"Inventario Productos Terminados"
            . "and id_plan_cuentas<>'95'" //"Inventario 12%"
            . "and id_plan_cuentas<>'96'" //"Inventario 0%"
            . "and id_plan_cuentas<>'97'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'98'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'99'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'115'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'4'" //"Iva en Compras"
            . "and id_plan_cuentas<>'270'" //"Inventario Materia Prima"
            . "and id_plan_cuentas<>'271'" //"Inventario Productos en Proceso"
            . "and id_plan_cuentas<>'9'" //"Inventario Productos Terminados"
            . "and id_plan_cuentas<>'179'" //"Inventario 12%"
            . "and id_plan_cuentas<>'9'" //"Inventario 0%"
            . "and id_plan_cuentas<>'90'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'98'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'99'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'115'" //"Suministros y Materiales Agrícolas"
    );
    $idPlan = pg_fetch_row($sql);
    //       echo '<br>GUARDAR FACTURA cuenta_debito: <br>' . "select cuenta_debito from parametros where cuenta_debito='" . $idPlan[0] . "'"; //////////////////////////

    $plancaja = pg_query("select cuenta_debito from parametros where cuenta_debito='" . $idPlan[0] . "'");
    $caja = pg_fetch_row($plancaja);
    //      echo '<br>GUARDAR FACTURA credito: <br>' . "select credito, id_detalle_transaccion from detalle_transaccion where id_transacciones='" . $fila[0] . "' and id_plan_cuentas='" . $caja[0] . "'"; //////////////////////////

    $tot = pg_query("select credito, id_detalle_transaccion from detalle_transaccion where id_transacciones='" . $fila[0] . "' and id_plan_cuentas='" . $caja[0] . "'");
    $s = pg_fetch_row($tot);
    $caja = $s[0] - $xr;



    //    pg_query("update detalle_transaccion set debito='" . $caja . "' where id_detalle_transaccion='" . $s[1] . "'");
    ///////////////////////////////////////
    ////////////////////////////////////
    //print_r($_POST[valor_seleccion_iva]);
    /////////////////////////////////////////////
    /////////////////////////////////////////////
    $data = 1;
}
echo $data;

function getIdPagoC()
{
    $sql = "select max(id_pagos_cobrar) max from pagos_cobrar";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!empty($rows)) {
        return 1;
    }
    return $rows[0]["max"] + 1;
}

function getCompPagoC()
{
    $sql = "select max(comprobante) max from pagos_cobrar";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!empty($rows)) {
        return 1;
    }
    return $rows[0]["max"] + 1;
}

function getSaldoPagoV($idfactura)
{
    $sql = "SELECT  saldo FROM pagos_venta where  estado='Activo' and id_factura_venta='$idfactura' and tipo_documento='Factura'";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return 0;
    }
    return $rows[0]["saldo"];
}

function getFactura($idfactura)
{
    $sql = "select * from factura_venta where id_factura_venta=$idfactura";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows[0];
}

function guardarPagoC($idfactura, $formap, $tipop, $valorp)
{
    $id = getIdPagoC();
    $comprobante = getCompPagoC();
    $usuario = $_SESSION["id"];
    $factura = getFactura($idfactura);
    $fechaA = date("Y-m-d");
    $sql = "
    INSERT INTO pagos_cobrar(
        id_pagos_cobrar, id_cliente, id_usuario, comprobante, fecha_actual, 
        hora_actual, forma_pago, tipo_pago, num_factura, tipo_factura, 
        fecha_factura, total_factura, valor_pagado, saldo_factura, observaciones, 
        estado, id_empresa, banco)
        VALUES (?, ?, ?, ?, ?, 
        ?, ?, ?, ?, ?, 
        ?, ?, ?, ?, ?, 
        ?, ?, ?);

    ";
}
