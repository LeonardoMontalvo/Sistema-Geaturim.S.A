<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

// datos detalle factura
$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];
$campo5 = $_POST['campo5'];
// fin

//contador factura compra
$cont1 = 0;
$consulta = pg_query("select max(id_factura_compra) from factura_compra");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;
// fin
$i=strlen($_POST[serie])-1;
$a=substr($_POST[serie], $i,1);
$c=$_POST[serie];
$cont=0;
while($a == '_'){
    $cont++;
    $i-=1;
    $a=substr($_POST[serie], $i,1);
}
$x="0";
$es=$cont;
$cont=$cont-1;
while($cont != 0){
   $x=$x."0";
   $cont=$cont-1;
}
$subserie=substr($_POST[serie], 8,(9-$es));
$seriefin=substr($_POST[serie], 0,8).$x.$subserie;
// guardar factura compra
pg_query("insert into factura_compra values('$cont1','1','$_POST[id_proveedor]','$_SESSION[id]','$cont1','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[fecha_registro]'
    ,'$_POST[fecha_emision]','$_POST[fecha_caducidad]','$_POST[tipo_comprobante]','".$seriefin."','$_POST[autorizacion]','$_POST[cancelacion]','$_POST[formas]'
    ,'$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]','$_POST[tot]','Activo', '$_POST[observaciones]', '$_POST[pago_ats]',0)");
// fin

// agregar detalle_factura_compra
$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);
$arreglo5 = explode('|', $campo5);
$nelem = count($arreglo1);
$forma = $_POST['formas'];
// fin

if ($forma == "Credito") {

    //contador pagos compra
    $cont2 = 0;
    $consulta = pg_query("select max(id_pagos_compra) from pagos_compra");
    while ($row = pg_fetch_row($consulta)) {
        $cont2 = $row[0];
    }
    $cont2++;
    // fin

    // variables pagos
    $total = $_POST['tot'];
    // 

    // guardar pagos compra
    pg_query("insert into pagos_compra values('$cont2','$_POST[id_proveedor]','$cont1','$_SESSION[id]','$_POST[fecha_actual]','0','0','$_POST[tipo_comprobante]','$total','$total','Activo')");
    // fin

    for ($i = 0; $i <= $nelem; $i++) {

        // contador detalle factura compra
        $cont4 = 0;
        $consulta = pg_query("select max(id_detalle_compra) from detalle_factura_compra");
        while ($row = pg_fetch_row($consulta)) {
            $cont4 = $row[0];
        }
        $cont4++;
        // fin

        // contador kardex
        $cont_k = 0;
        $consulta_k = pg_query("select max(id_kardex) from kardex");
        while ($row = pg_fetch_row($consulta_k)) {
            $cont_k = $row[0];
        }
        $cont_k++;
        // fin

        // contador kardex valorizado
        $cont_v = 0;
        $consulta_v = pg_query("select max(id_kardex) from kardex_valorizado");
        while ($row = pg_fetch_row($consulta_v)) {
            $cont_v = $row[0];
        }
        $cont_v++;
        // fin

        // guardar detalle_factura_compra
        pg_query("insert into detalle_factura_compra values('$cont4','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo')");
        // fin 

        // modificar productos
        $consulta2 = pg_query("select * from productos where cod_productos = '$arreglo1[$i]'");
        while ($row = pg_fetch_row($consulta2)) {
            $stock = $row[13];
        }
        $cal = $stock + $arreglo2[$i];

        pg_query("Update productos Set precio_compra='" . $arreglo3[$i] . "', stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "'");
        // fin

        // consulta kardex valorizado
        $cantidad = 0;
        $precio_total = 0;
        $consulta2 = pg_query("select * from kardex_valorizado where cod_productos = '$arreglo1[$i]' order by id_kardex asc");
        while ($row = pg_fetch_row($consulta2)) {
                $cantidad = $row[11];
                $precio_total = $row[8];
        }

        $cantidad_entrada = $arreglo2[$i];
        $precio_unitario_entrada = number_format($arreglo3[$i], 2, '.', '');
        $precio_total_entrada = number_format($arreglo2[$i] * $arreglo3[$i], 2, '.', '');

        $cantidad_total = $cantidad + $arreglo2[$i];
        $precio_total_total = number_format($precio_total + $precio_total_entrada, 2, '.', '');
        $precio_unitario_total = number_format($precio_total_total / $cantidad_total, 2, '.', '');
        
        pg_query("insert into kardex_valorizado values('$cont_v','$arreglo1[$i]','$_POST[fecha_actual]', '". 'Compra: '.$seriefin."','".$cantidad_entrada."','','".$cantidad."','".$precio_unitario_entrada."','".$precio_total_entrada."','','','".$cantidad_total."','2')");
        // fin
       
        // guardar kardex
        pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'F.C:' . $_POST['serie'] . "' ,'$arreglo2[$i]','$arreglo3[$i]','$arreglo5[$i]','$arreglo1[$i]','$cal','1','','')");
        // fin
    }
} else {
    if ($forma == "Contado") {
        for ($i = 0; $i <= $nelem; $i++) {
            // contador detalle factura compra
            $cont6 = 0;
            $consulta = pg_query("select  max(id_detalle_compra) from detalle_factura_compra");
            while ($row = pg_fetch_row($consulta)) {
                $cont6 = $row[0];
            }
            $cont6++;
            // fin

            // contador kardex
            $cont_k = 0;
            $consulta_k = pg_query("select max(id_kardex) from kardex");
            while ($row = pg_fetch_row($consulta_k)) {
                $cont_k = $row[0];
            }
            $cont_k++;
            // fin

            // contador kardex valorizado
            $cont_v = 0;
            $consulta_v = pg_query("select max(id_kardex) from kardex_valorizado");
            while ($row = pg_fetch_row($consulta_v)) {
                $cont_v = $row[0];
            }
            $cont_v++;
            // fin

            // guardar detalle_factura
            pg_query("insert into detalle_factura_compra values('$cont6','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo')");
            // fin
            
            // modificar productos
            $consulta2 = pg_query("select * from productos where cod_productos = '$arreglo1[$i]'");
            while ($row = pg_fetch_row($consulta2)) {
                $stock = $row[13];
            }
            $cal = $stock + $arreglo2[$i];

            pg_query("Update productos Set precio_compra='" . $arreglo3[$i] . "', stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "'");
            // fin

            // consulta kardex valorizado
            $cantidad = 0;
            $precio_total = 0;
            $consulta2 = pg_query("select * from kardex_valorizado where cod_productos = '$arreglo1[$i]' order by id_kardex asc");
            while ($row = pg_fetch_row($consulta2)) {
                    $cantidad = $row[11];
                    $precio_total = $row[8];
            }

            $cantidad_entrada = $arreglo2[$i];
            $precio_unitario_entrada = number_format($arreglo3[$i], 4, '.', '');
            $precio_total_entrada = number_format($arreglo2[$i] * $arreglo3[$i], 2, '.', '');

            $cantidad_total = $cantidad + $arreglo2[$i];
            $precio_total_total = number_format($precio_total + $precio_total_entrada, 2, '.', '');
            $precio_unitario_total = number_format($precio_total_total / $cantidad_total, 4, '.', '');
            
            pg_query("insert into kardex_valorizado values('$cont_v','$arreglo1[$i]','$_POST[fecha_actual]', '". 'Compra: '.$seriefin."','".$cantidad_entrada."','','".$cantidad."','".$precio_unitario_entrada."','".$precio_total_entrada."','','','".$cantidad_total."','2')");
            // fin
           
            // guardar kardex
            pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'F.C:' . $_POST['serie'] . "' ,'$arreglo2[$i]','$arreglo3[$i]','$arreglo5[$i]','$arreglo1[$i]','$cal','1','','')");
            // fin
        }  
    }
}

$data = $cont1;

//DEVOLVER VALORES///

echo $data;
?>
