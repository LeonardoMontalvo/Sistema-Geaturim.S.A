<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
//$codigo_barras = $_GET["codigo_barras"];
$codigo_barras=strtoupper($_GET["codigo_barras"]);
$codigo=strtoupper($_GET["cod"]);
$arr_data = array();

if ($codigo_barras != "") {
    $consulta = pg_query("select * from productos where cod_barras = '$codigo_barras' or codigo='$codigo' and estado = 'Activo'");
    while ($row = pg_fetch_row($consulta)) {
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
    }
}
echo json_encode($arr_data);
?>