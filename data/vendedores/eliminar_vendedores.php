<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
error_reporting(0);
$data = 0;
$cont_ruta = 0;
$cont_ruta_cli=0;
$cont_ruta_fv=0;





$consulta2 = pg_query("select rutas.id_ruta,vendedores.id_vendedor,nombre_ruta,descripcion_ruta,ci_vendedor,nombre_vendedor

from rutas inner join vendedores
on rutas.id_vendedor=vendedores.id_vendedor where rutas.estado='Activo' and vendedores.estado='Activo' and vendedores.ci_vendedor='$_POST[ruc_ci]'");
while ($row = pg_fetch_row($consulta2)) {
    $cont_ruta++;
     
    
}


$consulta2_cli = pg_query("select identificacion,id_cliente,nombres_cli,direccion_cli,telefono ,correo,nombre_vendedor,vendedores.id_vendedor
from clientes inner join rutas on rutas.id_ruta=clientes.credito_cupo
left join vendedores on vendedores.id_vendedor=rutas.id_vendedor
where clientes.estado='Activo' and rutas.estado='Activo' and vendedores.estado='Activo' and  vendedores.ci_vendedor='$_POST[ruc_ci]'");
while ($row = pg_fetch_row($consulta2_cli)) {
      
       $cont_ruta_cli++;
    
    
}



//$consulta2_fv = pg_query("
//select identificacion,factura_venta.id_cliente,nombres_cli,direccion_cli,telefono ,correo,nombre_vendedor,vendedores.id_vendedor
//from clientes inner join rutas on rutas.id_ruta=clientes.credito_cupo
//left join vendedores on vendedores.id_vendedor=rutas.id_vendedor
//
//left join factura_venta on vendedores.id_vendedor=factura_venta.id_vendedor
//where factura_venta.id_cliente=clientes.id_cliente and clientes.estado='Activo' and rutas.estado='Activo' and vendedores.estado='Activo' and  vendedores.ci_vendedor='$_POST[ruc_ci]'");
//while ($row = pg_fetch_row($consulta2_fv)) {
//      
//       $cont_ruta_fv++;
//    
//}


if ($cont_ruta != 0) {
  
       
    $data =1;
} 
if ($cont_ruta_cli != 0) {
   
       
    $data = 2;
} 
//if ($cont_ruta_fv != 0) {
//   
//       
//    $data = 3;
//} 

if ($cont_ruta == 0 && $cont_ruta_cli == 0 ) {
     pg_query("Update vendedores Set estado='Pasivo' where ci_vendedor ='$_POST[ruc_ci]'");
       
    $data = 0;
     // Auditoria
    insert_registro('ELIMINACION VENDEDOR: ' . $_POST['ruc_ci']);
}

echo $data;
?>