<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$data = 0;
$cont = 0;
$consulta=pg_query("SELECT id_anticipo_clientes FROM anticipo_clientes where   anticipo_clientes.estado = 'Facturado' and id_anticipo_clientes ='$_POST[id]'");
 while ($row = pg_fetch_row($consulta)) {
  $cont=$row[0];  
}

if($cont == 0){
    pg_query("Update anticipo_clientes Set estado='Pasivo' where id_anticipo_clientes ='$_POST[id]'");       
    $data = 0;
} else {
    $data = 1;
}
echo $data;
?>