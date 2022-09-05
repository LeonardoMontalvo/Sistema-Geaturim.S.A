<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$data = 0;

$campo1 = $_POST['productos'];
$campo2 = $_POST['cantidades'];
$campo3 = $_POST['precio'];
$campo4 = $_POST['stock'];
$campo5 = $_POST['total'];
// fin
        
// agregar kardex
$codigos = explode('|', $campo1);
$cantidades = explode('|', $campo2);
$precio = explode('|', $campo3);
$stock = explode('|', $campo4);
$total = explode('|', $campo5);

$nelem=count($codigos);

//aprobación de orden de producción
pg_query("Update ordenes_produccion set procesamiento = '1' where id_ordenes = '$_POST[comprobante]'");
    
$data=1;


// guardar detalle compra
for ($i = 1; $i <= $nelem; $i++) {
	
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
                
    // modificar productos general
    $consulta2 = pg_query("select * from productos where cod_productos = '$codigos[$i]'");
    while ($row = pg_fetch_row($consulta2)) {
        $stock = $row[13];
    }
    $cal = $stock - $cantidades[$i];

        
    pg_query("Update productos Set stock='" . $cal . "' where cod_productos='" . $codigos[$i] . "'");
    // fin

    // consulta kardex valorizado
    $cantidad=0;
    $precio_total=0;
    $precio_unitario=0;
    $consulta2 = pg_query("select * from kardex_valorizado where cod_productos = '$codigos[$i]' order by id_kardex asc");
    while ($row = pg_fetch_row($consulta2)) {
        $cantidad = $row[11];
        $precio_unitario = $row[7];
        $precio_total = $row[8];
    }

    $cantidad_salida = $cantidades[$i];
    $precio_unitario_salida = number_format($precio_unitario, 4, '.', '');
    $precio_total_salida = number_format($cantidades[$i] * $precio_unitario, 2, '.', '');

    $cantidad_total = $cantidad - $cantidades[$i];
    $precio_total_total = number_format($precio_total - $precio_total_salida, 2, '.', '');
    $precio_unitario_total = number_format($precio_total_total / $cantidad_total, 4, '.', '');

    pg_query("insert into kardex_valorizado values(".$cont_v.",'".$codigos[$i]."','$_POST[fecha_actual]', '" . 'O.P.: '. $_POST['comprobante']."','','".$cantidad_salida."','".$cantidad."','".$precio_unitario_salida."','".$precio_total_salida."','','','".$cantidad_total."','5')");
    // fin

   // guardar kardex
    pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'O.P.:' . $_POST['comprobante'] . "' ,'$cantidades[$i]','$precio[$i]','$total[$i]','$codigos[$i]','$cal','6','','')");
    // 
}

echo $data;
?>
