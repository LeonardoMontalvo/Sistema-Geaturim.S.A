<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
//$codigo_barras=$_GET["codigo_barras"];

$codigo_barras = strtoupper($_GET["codigo_barras"]);
$codigo = $codigo_barras; //$codigo=strtoupper($_GET["cod"]);


$arr_data = array();










if ($codigo_barras != "") {



    $consulta = pg_query("select * from productos where (cod_barras = '$codigo_barras'  or codigo='$codigo')    and estado = 'Activo'");

    while ($row = pg_fetch_assoc($consulta)) {

    
       
         
            $arr_data[] = $row['cod_productos'];
           
        
    }
}







echo json_encode($arr_data);
?>