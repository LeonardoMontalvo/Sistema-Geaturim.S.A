<?php

session_start();
include '../../procesos/base.php';
require_once '../../procesos/kardexValorizado.php';
require_once '../../procesos/auditoria.php';
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

// contador devolucion factura compra
$cont1 = 0;
$consulta = pg_query("select max(id_devolucion_compra) from devolucion_compra");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;

if ($_POST[clave] == '') {
    $num_clave = '000000001';
} else {
    $num_clave = $_POST[clave];
}

if ($_POST[id_factura_compra] != 0) {

    pg_query("insert into  devolucion_compra values('$cont1','1','$_POST[id_proveedor]','$_SESSION[id]','$cont1','$_POST[fecha_actual]','$_POST[hora_actual]'
    ,'$_POST[tipo_comprobante]','$_POST[serie]','$_POST[autorizacion]'
    ,'$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]','$_POST[tot]','$_POST[observaciones]','Activo','$num_clave','$_POST[fecha_registro_nc]','$_POST[secuencial_nc]','$_POST[autorizacion_nc]','Si')");

    insert_registro('CREACION ' . $_POST[tipo_comprobante] . 'DEVO COMPRA: ' . $cont1 . ', DEL PROVEEDOR CON ID: ' . $_POST[id_proveedor] . ', CON FORMA DE PAGO:  Y TOTAL DE: ' . $_POST[tot]);
} else {
    pg_query("insert into devolucion_compra values('$cont1','1','$_POST[id_proveedor]','$_SESSION[id]','$cont1','$_POST[fecha_actual]','$_POST[hora_actual]'
    ,'$_POST[tipo_comprobante]','$_POST[secuencial]','$_POST[autorizacion_credito]'
    ,'$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]','$_POST[tot]','$_POST[observaciones]','Activo','$num_clave','$_POST[fecha_registro_nc]','$_POST[secuencial_nc]','$_POST[autorizacion_nc]','No')");
    insert_registro('CREACION ' . $_POST[tipo_comprobante] . 'DEVO COMPRA: ' . $cont1 . ', DEL PROVEEDOR CON ID: ' . $_POST[id_proveedor] . ', CON FORMA DE PAGO:  Y TOTAL DE: ' . $_POST[tot]);
}
// agregar detalle_dev_compra
$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);
$arreglo5 = explode('|', $campo5);
$nelem = count($arreglo1);



for ($i = 1; $i < $nelem; $i++) {  
    
    $cont2 = 0;
    $consulta = pg_query("select max(id_detalle_devcompra) from detalle_devolucion_compra");
    while ($row = pg_fetch_row($consulta)) {
        $cont2 = $row[0];
    }
    $cont2++;
    $cont_v = 0;
    pg_query("insert into detalle_devolucion_compra values('$cont2','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo')");

    insert_registro('CREACION DETALLE DE LA  D.COMPRA CON ID: ' . $cont1 . ' Y ' . $arreglo2[$i] . ' PRODUCTO/S CON ID: ' . $arreglo1[$i] . ', CON PRECIO DE: ' . $arreglo5[$i]);
  
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

    procesarKardexSalida($arreglo1[$i], 'DV.Com ' . $_POST['serie'], $arreglo2[$i], $stock, NULL, 'Activo', $_SESSION['PV'], 'DC', $cont1, $arreglo5[$i], NULL, NULL, $_POST['id_proveedor'], '', NULL, NULL, $_SESSION['id']);
   
}
$data = $cont1;

$idtran = pg_query("select max(id_transacciones) from transacciones");
$fila = pg_fetch_row($idtran);
$fila[0] = $fila[0] + 1;
$sum = 0;
$bool = true;
$pos = 0;
$vec = 0;
$auxiliar = $arreglo1;
while ($bool) {
    $cuenta = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[0] . "'");
    $plan = pg_fetch_row($cuenta);
    $nelem = count($auxiliar);
    $vec = 0;
    for ($i = 0; $i <= $nelem; $i++) {
        $cuenta1 = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[$i] . "'");
        $plan1 = pg_fetch_row($cuenta1);
        if ($plan1[$i] == $plan[0]) {
            $sum = $arreglo5[$i] + $sum;
        } else {
            $vec[$pos] = $auxiliar[$i];
            $pos++;
        }
    }
    if ($vec == 0) {
        $bool = false;
    } else {
        $auxiliar = $vec;
        $pos = 0;
    }
}
$sum = number_format($sum, 3, '.', '');
$sum = $sum + $_POST['iva'];
$saldo = $sum - $_POST['tot'];
$saldo = number_format($saldo, 3, '.', '');
$prove = pg_query("select identificacion_pro from proveedores where id_proveedor='$_POST[id_proveedor]'");
$p = pg_fetch_row($prove);
$ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1' and id_empresa= '$_SESSION[PV]'");
$res = pg_fetch_row($ing);
$asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'DEVOLUCIÓN COMPRA, PROVEEDOR: " . $p[0] . ", COMPROBANTE: " . "$_POST[serie]" . "', '" . $sum . "', '$_POST[tot]', '" . $saldo . "','1','" . ($res[0] + 1) . "','Activo' )");

$auxiliar = $arreglo1;
$suma = 0;
$bool = true;
$aa = 0;
$ab = 0;
$pos = 1;
$vec = "";
$ant = $arreglo5;
$vec1 = "";
while ($bool) {
    $cont1 = 0;
    $cont2 = 0;
    $aa = 0;
    $ab = 0;
    $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
    $fila1 = pg_fetch_row($iddettran);
    $fila1[0] = $fila1[0] + 1;
    $cuenta = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[1] . "'");
    while ($plan = pg_fetch_row($cuenta)) {
        $cont2 = $plan[0];
    }
    if ($cont2 == 0) {
        $cuenta = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[0] . "'");
        while ($plan = pg_fetch_row($cuenta)) {
            $cont2 = $plan[0];
        }
    }
    $nelem1 = count($auxiliar);
    $suma = 0;
    for ($i = 0; $i <= $nelem1; $i++) {
        $cuenta1 = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[$i] . "'");
        while ($plan1 = pg_fetch_row($cuenta1)) {
            $cont1 = $plan1[0];
        }
        if ($cont1 != 0) {
            if ($cont1 == $cont2) {
                $aa++;
                $suma = $ant[$i] + $suma;
                //$fila1[0]++;
                //pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','4','".$cont2."-".$aa."','".$nelem1."-i".$i."-".$cont1."','prueba')");
            } else {
                $ab++;
                $vec[$pos] = $auxiliar[$i];
                $vec1[$pos] = $ant[$i];
                $pos++;               
            }
        }
       
    }

    $suma = number_format($suma, 3, '.', '');
    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $cont2 . "','0.000','$suma','Activo')");
    if ($vec == "") {
      
        $bool = false;
    } else {     
        $auxiliar = $vec;
        $ant = $vec1;
        $vec = "";
        $vec1 = "";
        $pos = 0;
    }
}
$iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
$fila1 = pg_fetch_row($iddettran);
$fila1[0] = $fila1[0] + 1;

$planiva = pg_query("select cuenta_debito from parametros where descripcion='IVA'");
$fila2 = pg_fetch_row($planiva);
pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','0.000','$_POST[iva]','Activo')");
$fila1[0] = $fila1[0] + 1;
$plancaja = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
$fila2 = pg_fetch_row($plancaja);
pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','$_POST[tot]','0.000','Activo')");

echo $data;
