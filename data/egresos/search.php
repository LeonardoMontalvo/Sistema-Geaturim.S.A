<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
//$codigo_barras = $_GET["codigo_barras"];

$codigo_barras=strtoupper($_GET["codigo_barras"]); 
$codigo=strtoupper($_GET["cod"]);
$arr_data = array();
$conpunto = 1;
$consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($consultapunto)) {
    $conpunto = $row[0];
}
$conpuntoresult = 1;
$consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
while ($row = pg_fetch_row($consultapuntoresult)) {
    $conpuntoresult = $row[0];
}

if ($codigo_barras != "") {
    $consulta = pg_query("select * from productos where cod_barras = '$codigo_barras'  or codigo='$codigo' and estado = 'Activo'");

    $consulta1 = pg_query("select * from productos p left join detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos "
            . "where p.cod_barras='$codigo_barras' or codigo='$codigo' and dpb.id_bodega=$conpuntoresult ");
//    $row1 = pg_fetch_row($consulta1);
    
    while ($row = pg_fetch_row($consulta1)) {
           if($row[37]==""){
            $row[37]=$row[6];
        }else{
           $row[37]=$row[37]; 
        }
        $arr_data[] = strtoupper($row[1]);
        $arr_data[] = $row[3];
        $arr_data[] = $row[37];
        $arr_data[] = $row[9];
        $arr_data[] = $row[4];
        $arr_data[] = $row[0];
        $arr_data[] = $row[26];
        $arr_data[] = $row[44];
    }
}
echo json_encode($arr_data);
?>