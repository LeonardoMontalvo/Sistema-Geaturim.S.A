<?php

session_start();
include '../../procesos/base.php';
conectarse();

/* @var $_POST type */
$texto2_max = $_POST['texto_max'];

$conpunto=1;
$consultapunto=pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
while($row=pg_fetch_row($consultapunto))
 {
  $conpunto=$row[0];
 }

$conpuntoresult=1;
$consultapuntoresult=pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
while($row=pg_fetch_row($consultapuntoresult))
 {
  $conpuntoresult=$row[0];
 }

$conpunto_max=0;
   
$consultapunto_max=pg_query("select min(p.cod_productos )  from productos P  LEFT join punto_venta PV on P.id_bodega = PV.id_punto_venta   LEFT join plan_cuentas PC on PC.id_plan_cuentas=P.id_plan_cuentas     LEFT join proveedores PR on p.proveedor=pr.id_proveedor LEFT join detalle_producto_bodega  dpb on p.cod_productos=dpb.cod_productos   LEFT join  generico g on P.caracteristicas::int = g.id_generico    LEFT join categoria c on P.categoria::int = c.id_categoria  LEFT join marcas m on P.marca::int = m.id_marca LEFT join aplicacion a on P.observaciones::int = a.id_aplicacion where P.articulo ilike '%$texto2_max%'  and dpb.id_bodega=$conpuntoresult and P.estado = 'Activo' ");
$row_max=pg_fetch_row($consultapunto_max);
 
  $data=$row_max[0];
 


echo $data;
?>