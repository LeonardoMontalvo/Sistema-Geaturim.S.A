<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

$cod_producto = $_GET["cod_producto"];
$unidad_medida = $_GET["unidad_medida"];
$precio = $_GET["precio"];
$arr_data = array();


if ($unidad_medida != "") {
    $consulta1 = pg_query("
  select p.cod_productos,p.iva,cantidad, precio_compra, pvpmayo, pvpnego,precio_compra, pvpmino from productos p left join unidad_medida_productos ump on p.cod_productos=ump.cod_productos 
left join unidades_medida um on um.id_unidades=ump.id_unidades 
  where p.cod_productos='$cod_producto' and um.id_unidades='$unidad_medida' and um.estado='Activo' and ump.estado='Activo'
");
    $row = pg_fetch_row($consulta1);
    if ($precio == "MINORISTA") {
        $arr_data[] = $row[0];
        $arr_data[] = $row[2];
        $arr_data[] = $row[3];
        if ($row[1] == 'Si') {
            $arr_data[] = ($row[6]);
        } else {
            $arr_data[] = ($row[6]);
        }
        $arr_data[]=$row[7];
    } elseif ($precio == "MAYORISTA") {
        $arr_data[] = $row[0];
        $arr_data[] = $row[2];
        $arr_data[] = $row[4];
        if ($row[1] == 'Si') {
            $arr_data[] = ($row[6]);
        } else {
            $arr_data[] = ($row[6]);
        }
    } elseif ($precio == "NEGOCIO") {
        $arr_data[] = $row[0];
        $arr_data[] = $row[2];  

        $arr_data[] = $row[5];
        if ($row[1] == 'Si') {
            $arr_data[] = ($row[6]);
        } else {
            $arr_data[] = ($row[6]);
        }
    }
}else{
    
    


    $consulta1 = pg_query("select p.cod_productos,p.iva,p.stock, iva_minorista, iva_mayorista, iva_negocio,precio_compra  from   productos p left join detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos where p.cod_productos='$cod_producto' ");
   
   $row = pg_fetch_row($consulta1);
    if ($precio == "MINORISTA") {
        $arr_data[] = $row[0];
        $arr_data[] = "";
        $arr_data[] = $row[3];
        if ($row[1] == 'Si') {
            $arr_data[] = floatval($row[6]);
        } else {
            $arr_data[] = floatval($row[6]);
        }
         $arr_data[]=$row[3];
    } elseif ($precio == "MAYORISTA") {
        $arr_data[] = $row[0];
        $arr_data[] = "";
        $arr_data[] = $row[4];
        if ($row[1] == 'Si') {
            $arr_data[] = floatval($row[6]);
        } else {
            $arr_data[] = floatval($row[6]);
        }
    } elseif ($precio == "NEGOCIO") {
        $arr_data[] = $row[0];
        $arr_data[] = ""; 

        $arr_data[] = $row[5];
        if ($row[1] == 'Si') {
            $arr_data[] = floatval($row[6]);
        } else {
            $arr_data[] = floatval($row[6]);
        }
    }

    
}
echo json_encode($arr_data);
