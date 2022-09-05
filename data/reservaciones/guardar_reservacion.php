<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$cont1=0;


// contador factura venta
$cont1 = 0;
$consulta = pg_query("select max(id_reservacion) from reservaciones");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;
// fin
        
// guardar reservacion
pg_query("insert into reservaciones values('$cont1','1','$_POST[id_cliente]','$_SESSION[id]','$cont1','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[tot]','Activo','','')");
// fin

    // datos detalle reservacion
    $campo1 = $_POST['campo1'];
    $campo2 = $_POST['campo2'];
    $campo3 = $_POST['campo3'];
    $campo4 = $_POST['campo4'];
    $campo5 = $_POST['campo5'];
    $campo6 = $_POST['campo6'];
    // fin
        
    // agregar detalle_reservacion
    $arreglo1 = explode('|', $campo1);
    $arreglo2 = explode('|', $campo2);
    $arreglo3 = explode('|', $campo3);
    $arreglo4 = explode('|', $campo4);
    $arreglo5 = explode('|', $campo5);
    $arreglo6 = explode('|', $campo6);
    $nelem = count($arreglo1);
    // fin

    for ($i = 0; $i <= $nelem; $i++) {
        // contador detalle factura venta
        $cont6 = 0;
        $consulta = pg_query("select  max(id_detalle_reservacion) from detalle_reservacion");
        while ($row = pg_fetch_row($consulta)) {
            $cont6 = $row[0];
        }
        $cont6++;
        // fin  

        // guardar detalle_venta
        pg_query("insert into detalle_reservacion values('$cont6','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]')");
        // fin
    } 
$data=$cont1;
echo $data;
?>
