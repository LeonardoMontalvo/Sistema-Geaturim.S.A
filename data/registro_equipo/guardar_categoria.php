<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$cont = 0;
$repe = 0;

//////////////////validar repetidos//////////////////
$consulta = pg_query("select * from tipo_equipo where descripcion='" . strtoupper($_POST['descripcion']) . "'");
while ($row = pg_fetch_row($consulta)) {
    $repe++;
}
///////////////////////////////////////////////
if ($repe == 0) {
///////////////contador categoria//////////////////
    $consulta = pg_query("select max(id_tipo_equipo) from tipo_equipo");
    while ($row = pg_fetch_row($consulta)) {
        $cont = $row[0];
    }
    $cont++;
/////////////////////////////////////////////////
////////////////guardar categoria//////////////
    pg_query("insert into tipo_equipo values('$cont','" . strtoupper($_POST['descripcion']) . "','Activo')");
    $data = 1;
/////////////////////////////////////////////
}else{
   $data = 0; 
}
echo $data;
?>
