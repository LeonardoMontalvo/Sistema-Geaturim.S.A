<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

   
    // modificar reservacion
    pg_query("Update reservaciones set id_cliente = '$_POST[id_cliente]', id_usuario = '$_SESSION[id]', fecha_actual = '$_POST[fecha_actual]', hora_actual = '$_POST[hora_actual]', tarifa0 = '$_POST[tarifa0]', tarifa12 = '$_POST[tarifa12]', iva = '$_POST[iva]', total = '$_POST[tot]' where id_reservacion = '$_POST[id_reservacion]'");
    // fin
    

    /*// sumar stock productos
    $consulta = pg_query("select * from detalle_reservacion where id_reservacion = '$_POST[id_reservacion]'");
    while ($row = pg_fetch_row($consulta)) {
        $canti1 = $row[3];
        $id = $row[2];
        $consulta2 = pg_query("select * from productos where cod_productos = '" .$id. "'"); 
        while ($row = pg_fetch_row($consulta2)) {
            $canti2 = $row[13];
        }
           
        $cal1 = $canti2 + $canti1;
        pg_query("Update productos Set stock='" .$cal1. "' where cod_productos='" .$id. "'");
    }
    // fin suma*/
    
    // eliminar detalle productos
    pg_query("DELETE FROM  detalle_reservacion where id_reservacion = '$_POST[id_reservacion]'");
    // fin  

    // datos detalle factura
    $campo1 = $_POST['campo1'];
    $campo2 = $_POST['campo2'];
    $campo3 = $_POST['campo3'];
    $campo4 = $_POST['campo4'];
    $campo5 = $_POST['campo5'];
    $campo6 = $_POST['campo6'];
    // fin 

    // agregar detalle_factura_venta
    $arreglo1 = explode('|', $campo1);
    $arreglo2 = explode('|', $campo2);
    $arreglo3 = explode('|', $campo3);
    $arreglo4 = explode('|', $campo4);
    $arreglo5 = explode('|', $campo5);
    $arreglo6 = explode('|', $campo6);
    $nelem = count($arreglo1);
    /// fin

    for ($i = 0; $i <= $nelem; $i++) {

        // contador detalle factura venta
        $cont6 = 0;
        $consulta = pg_query("select  max(id_detalle_reservacion) from detalle_reservacion");
        while ($row = pg_fetch_row($consulta)) {
            $cont6 = $row[0];
        }
        $cont6++;
        // fin 

        //guardar detalle_venta
        pg_query("insert into detalle_reservacion values('$cont6','$_POST[id_reservacion]','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]')");
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
    $data = $_POST['id_reservacion'];

echo $data;
?>
