<?php


include '../../procesos/base.php';
require_once __DIR__ . "/guardar_pxp_retencion.php";
conectarse();


$sql = "
SELECT id_factura_compra, id_empresa, id_proveedor, id_usuario, comprobante, 
       fecha_actual, hora_actual, fecha_registro, fecha_emision, fecha_caducidad, 
       tipo_comprobante, num_serie, num_autorizacion, fecha_cancelacion, 
       forma_pago, tarifa0, tarifa12, iva_compra, descuento_compra, 
       total_compra, estado, observaciones, pago_ats, temporal
  FROM factura_compra where estado='Activo' order by id_factura_compra
";
conectarse();
$res = pg_query($sql);
$rows = pg_fetch_all($res);

foreach ($rows as $value) {

    $idtran = pg_query("select max(id_transacciones) from transacciones");
    $fila = pg_fetch_row($idtran);
    $fila[0] = $fila[0] + 1;
    
    
    //DETALLES
    
    $consf = pg_query("select dcr.valor_retenido,dcr.id_retencion_fuentes,rff.id_retencion_fuente_factura_compra from detallecomprobanteretencion dcr, retencion_fuente_factura_compra rff ,factura_compra fc where 
dcr.id_retencion_fuente_factura_compra=rff.id_retencion_fuente_factura_compra
and rff.id_factura=fc.id_factura_compra and fc.id_factura_compra='$value[id_factura_compra]' and rff.id_gastos=1 and  dcr.id_trete=1 ");

// TOTAL
    $consf1 = pg_query("select sum(dcr.valor_retenido)from detallecomprobanteretencion dcr, retencion_fuente_factura_compra rff ,factura_compra fc where 
dcr.id_retencion_fuente_factura_compra=rff.id_retencion_fuente_factura_compra
and rff.id_factura=fc.id_factura_compra and fc.id_factura_compra='$value[id_factura_compra]'  and rff.id_gastos=1 
");
    $sum_retencion = pg_fetch_row($consf1);


    $id_proveedor = $value[id_proveedor];

    $ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1' and id_empresa= '$value[id_empresa]'");
    $res = pg_fetch_row($ing);
    $ing_pv = pg_query("select max(id_transaccion_pv::int) from transacciones where  id_empresa= '$value[id_empresa]'");
    $res_pv = pg_fetch_row($ing_pv);



    $prove = pg_query("select identificacion_pro from proveedores where id_proveedor='$id_proveedor'");
    $p = pg_fetch_row($prove);
 if ($sum_retencion[0] != '') {
    echo '<br>GUARDAR FACTURA transacciones1: <br>' . "insert into transacciones values('" . $fila[0] . "', '$value[id_empresa]', '" . $value[id_factura_compra] . "','$value[fecha_emision]','$value[hora_actual]', 'RETENCION EN COMPRA PRODUCTOS, PROVEEDOR:" . $p[0] . " , COMPROBANTE: " . $value[num_serie] . "', '" . $sum_retencion[0] . "', '" . $sum_retencion[0] . "', '0.00','1','" . ($res[0] + 1) . "','Activo','$id_proveedor','','','','','COM','',$value[id_empresa],'$value[fecha_emision]','" . ($res_pv[0] + 1) . "')" . "</br>";
    $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$value[id_empresa]', '" . $value[id_factura_compra] . "','$value[fecha_emision]','$value[hora_actual]', 'RETENCION EN COMPRA PRODUCTOS, PROVEEDOR:" . $p[0] . " , COMPROBANTE: " . $value[num_serie] . "', '" . $sum_retencion[0] . "', '" . $sum_retencion[0] . "', '0.00','1','" . ($res[0] + 1) . "','Activo','$id_proveedor','','','','','COM','',$value[id_empresa],'$value[fecha_emision]','" . ($res_pv[0] + 1) . "')");
   ////////////////UPDATE COBROS
 }
    $valfac = pg_query("SELECT  monto_credito FROM pagos_compra where  estado='Activo' and id_factura_compra='$value[id_factura_compra]'");
    $valfacresult = pg_fetch_row($valfac);

    if (isFacturaCredito($value[id_factura_compra])) {
        guardarPagoP($value[id_factura_compra], "RETENCION", "INTERNA", $sum_retencion[0], "RETENCION", "", $value[fecha_emision]);
    }
    $fila1 = 0;
    $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
    $fila1 = pg_fetch_row($iddettran);


    $fila1[0] = $fila1[0] + 1;

    echo 'dt1' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','140','" . $sum_retencion[0] . "','0.000','Activo')" . "</br>";
        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','140','" . $sum_retencion[0] . "','0.000','Activo')");

    while ($cont2f = pg_fetch_row($consf)) {
        $cons = pg_query("select cuenta_credito from retencion_fuentes where id_retencion_fuentes='" . $cont2f[1] . "'");

        while ($cont2 = pg_fetch_row($cons)) {
            $fila1 = 0;
            $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
            $fila1 = pg_fetch_row($iddettran);


            $fila1[0] = $fila1[0] + 1;
            $plancliente = pg_query("select cuenta_debito from parametros where descripcion='CUENTAS' ");
            $fila2 = pg_fetch_row($plancliente);


            $fila1[0] = $fila1[0] + 1;

            /////////NUEVO CODIGO//////////////////
           echo 'dt2' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $cont2[0] . "','0.000','$cont2f[0]','Activo')" . "</br>";
               pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $cont2[0] . "','0.000','$cont2f[0]','Activo')");

        }
    }
    
    
    
     $consf = pg_query("select dcr.valor_retenido,dcr.id_retencion_fuentes,rff.id_retencion_fuente_factura_compra from detallecomprobanteretencion dcr, retencion_fuente_factura_compra rff ,factura_compra fc where 
dcr.id_retencion_fuente_factura_compra=rff.id_retencion_fuente_factura_compra
and rff.id_factura=fc.id_factura_compra and fc.id_factura_compra='$value[id_factura_compra]' and rff.id_gastos=1 and  dcr.id_trete=2 ");

        $xi = 0;

        while ($cont2f = pg_fetch_row($consf)) {
            $cons = pg_query("select cuenta_credito from retencion_iva where id_retencion_iva='" . $cont2f[1] . "'");

            while ($cont2 = pg_fetch_row($cons)) {
                $fila1 = 0;
                $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
                $fila1 = pg_fetch_row($iddettran);
         
        

                $fila1[0] = $fila1[0] + 1;
                echo 'dt3iva' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $cont2[0] . "','0.000','$cont2f[0]','Activo')" . "</br>";
                pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $cont2[0] . "','0.000','$cont2f[0]','Activo')");

                $xi = $xi + $cont2f[0];
            }
        }
    
    
}