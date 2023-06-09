<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
    // datos 
    $campo1 = $_POST['campo1'];
    $campo2 = $_POST['campo2'];
    $campo3 = $_POST['campo3'];
    $campo4 = $_POST['campo4'];
    $campo5 = $_POST['campo5'];
    $campo6 = $_POST['campo6'];
    // fin 
    // agregar 
    $arreglo1 = explode('|', $campo1);
    $arreglo2 = explode('|', $campo2);
    $arreglo3 = explode('|', $campo3);
    $arreglo4 = explode('|', $campo4);
    $arreglo5 = explode('|', $campo5);
    $arreglo6 = explode('|', $campo6);
    


  
    $nelem = count($arreglo1);
    // eliminar detalle productos
    
    pg_query("DELETE FROM  rubro where id_clase = '$_POST[tipo_tarifa]'");
    // fin  

    for ($i = 0; $i <= $nelem; $i++) {

        // contador detalle factura compra
        $cont4 = 0;
        $consulta = pg_query("select max(id_rubro) from rubro");
        while ($row = pg_fetch_row($consulta)) {
            $cont4 = $row[0];
        }
        $cont4++;
        // fin


     
       pg_query("insert into rubro values('$cont4','$_POST[tipo_tarifa]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','$arreglo6[$i]','Activo','$arreglo1[$i]')");
   


    }
    $data = $_POST['id_rubro'];

echo $data;
?>
