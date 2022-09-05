<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$cont1=0;

$repe=0;
$consulta = pg_query("select * from recetas where nombre='" . strtoupper($_POST['nombre']) . "'");
while ($row = pg_fetch_row($consulta)) {
    $repe++;
}


// contador factura venta
$cont1 = 0;
$consulta = pg_query("select max(id_receta) from recetas");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;
// fin

if($repe==0){
// guardar reservacion
    pg_query("insert into recetas values('$cont1','$_SESSION[id]','$_POST[fecha_actual]','$_POST[producto]','$_POST[costo]','$_POST[fecha_actual]','Activo','$_POST[nombre]')");
    // fin

        // datos detalle reservacion
        $campo1 = $_POST['campo1'];
        $campo2 = $_POST['campo2'];
        $campo3 = $_POST['campo3'];
        $campo4 = $_POST['campo4'];
        $campo5 = $_POST['campo5'];
        // fin
            
        // agregar detalle_reservacion
        $arreglo1 = explode('|', $campo1);
        $arreglo2 = explode('|', $campo2);
        $arreglo3 = explode('|', $campo3);
        $arreglo4 = explode('|', $campo4);
        $arreglo5 = explode('|', $campo5);
        $nelem = count($arreglo1);
        // fin

        for ($i = 0; $i <= $nelem; $i++) {
            // contador detalle receta
            $cont6 = 0;
            $consulta = pg_query("select  max(id_detalle_receta) from detalle_receta");
            while ($row = pg_fetch_row($consulta)) {
                $cont6 = $row[0];
            }
            $cont6++;
            // fin  

            // guardar detalle_receta
            pg_query("insert into detalle_receta values('$cont6','$arreglo1[$i]','$arreglo4[$i]','$arreglo3[$i]','$arreglo2[$i]','$arreglo5[$i]','Activo','$cont1')");
            // fin
        } 
    $data=$cont1;
}else{
    $data=0;
}

echo $data;
?>
