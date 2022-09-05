<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

   
    // modificar reservacion
    pg_query("Update recetas set id_usuario = '$_SESSION[id]', fecha_modificacion = '$_POST[fecha_actual]',  costo_producto = '$_POST[costo]', cod_productos='$_POST[producto]' where id_receta = '$_POST[id_receta]'");
    // fin
    
    
    // eliminar detalle productos
    pg_query("DELETE FROM  detalle_receta where id_receta = '$_POST[id_receta]'");
    // fin  

    // datos detalle factura
    $campo1 = $_POST['campo1'];
    $campo2 = $_POST['campo2'];
    $campo3 = $_POST['campo3'];
    $campo4 = $_POST['campo4'];
    $campo5 = $_POST['campo5'];
    // fin 

    // agregar detalle_factura_venta
    $arreglo1 = explode('|', $campo1);
    $arreglo2 = explode('|', $campo2);
    $arreglo3 = explode('|', $campo3);
    $arreglo4 = explode('|', $campo4);
    $arreglo5 = explode('|', $campo5);
    $nelem = count($arreglo1);
    /// fin

    for ($i = 0; $i <= $nelem; $i++) {

        // contador detalle factura venta
        $cont6 = 0;
        $consulta = pg_query("select  max(id_detalle_receta) from detalle_receta");
        while ($row = pg_fetch_row($consulta)) {
            $cont6 = $row[0];
        }
        $cont6++;
        // fin 

        //guardar detalle_venta
        pg_query("insert into detalle_receta values('$cont6','$arreglo1[$i]','$arreglo4[$i]','$arreglo3[$i]','$arreglo2[$i]','$arreglo5[$i]','Activo','$_POST[id_receta]')");
        // fin

        /*// modificar productos
        $consulta2 = pg_query("select * from productos where cod_productos = '$arreglo1[$i]'");
        while ($row = pg_fetch_row($consulta2)) {
            $stock = $row[13];
        }
        $cal = $stock - $arreglo2[$i];

        pg_query("Update productos Set stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "'");
        // fin*/
    }
    $data = $_POST['id_receta'];

echo $data;
?>
