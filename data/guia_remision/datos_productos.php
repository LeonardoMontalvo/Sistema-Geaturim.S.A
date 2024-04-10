<?php

session_start();
include '../../procesos/base.php';
conectarse();
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$search = $_GET['_search'];
$texto2 = $_GET['texto'];


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

if (!$sidx)
    $sidx = 1;
$result = pg_query("SELECT COUNT(*) AS count FROM productos");
$resultproduc = pg_query("SELECT * FROM productos");
$resultprove = pg_query("SELECT * FROM proveedores");

$row = pg_fetch_row($result);
$rowproduc = pg_fetch_row($resultproduc);
$nombreproducresult = $rowproduc[29];

$rowprove = pg_fetch_row($resultprove);
$nombreprovecresult = $rowprove[0];


$count = $row[0];
if ($count > 0 && $limit > 0) {
    $total_pages = ceil($count / $limit);
} else {
    $total_pages = 0;
}
if ($page > $total_pages)
    $page = $total_pages;
$start = $limit * $page - $limit;
if ($start < 0)
    $start = 0;

if ($search == 'false') {
    
    
     $SQL = "select  DISTINCT ON (p.cod_productos) * from productos P     LEFT join punto_venta PV on P.id_bodega = PV.id_punto_venta   LEFT join plan_cuentas PC on PC.id_plan_cuentas=P.id_plan_cuentas     LEFT join proveedores PR on p.proveedor=pr.id_proveedor LEFT join detalle_producto_bodega  dpb on p.cod_productos=dpb.cod_productos   LEFT join  generico g on P.caracteristicas::int = g.id_generico    LEFT join categoria c on P.categoria::int = c.id_categoria  LEFT join marcas m on P.marca::int = m.id_marca LEFT join aplicacion a on P.observaciones::int = a.id_aplicacion where P.articulo ilike '%$texto2%'  and dpb.id_bodega=$conpuntoresult and P.estado = 'Activo'  ORDER BY p.$sidx $sord offset $start limit $limit  ";
    
     
} else {
    $campo = $_GET['searchField'];
    if ($campo == 'cod_prod') {
        $campo = 'codigo';
    }
    if ($campo == 'nombre_art') {
        $campo = 'articulo';
    }
    if ($campo == 'marca') {
        $campo = 'nombre_marca';
    }
     
    if ($campo == 'modelo') {
        $campo = 'nombre_generico';
    }
   
   
    if ($_GET['searchOper'] == 'eq') {
        $SQL = "select  DISTINCT ON (p.cod_productos) * from productos P     LEFT join punto_venta PV on P.id_bodega = PV.id_punto_venta   LEFT join plan_cuentas PC on PC.id_plan_cuentas=P.id_plan_cuentas     LEFT join proveedores PR on p.proveedor=pr.id_proveedor LEFT join detalle_producto_bodega  dpb on p.cod_productos=dpb.cod_productos   LEFT join  generico g on P.caracteristicas::int = g.id_generico    LEFT join categoria c on P.categoria::int = c.id_categoria  LEFT join marcas m on P.marca::int = m.id_marca LEFT join aplicacion a on P.observaciones::int = a.id_aplicacion where P.articulo ilike '%$texto2%'  and dpb.id_bodega=$conpuntoresult and P.estado = 'Activo' and  $campo = '$_GET[searchString]' ORDER BY p.$sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ne') {
        $SQL = "select  DISTINCT ON (p.cod_productos) * from productos P     LEFT join punto_venta PV on P.id_bodega = PV.id_punto_venta   LEFT join plan_cuentas PC on PC.id_plan_cuentas=P.id_plan_cuentas     LEFT join proveedores PR on p.proveedor=pr.id_proveedor LEFT join detalle_producto_bodega  dpb on p.cod_productos=dpb.cod_productos   LEFT join  generico g on P.caracteristicas::int = g.id_generico    LEFT join categoria c on P.categoria::int = c.id_categoria  LEFT join marcas m on P.marca::int = m.id_marca LEFT join aplicacion a on P.observaciones::int = a.id_aplicacion where P.articulo ilike '%$texto2%'  and dpb.id_bodega=$conpuntoresult and P.estado = 'Activo'  and $campo != '$_GET[searchString]' ORDER BY p.$sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'bw') {
        $SQL = "select  DISTINCT ON (p.cod_productos) * from productos P     LEFT join punto_venta PV on P.id_bodega = PV.id_punto_venta   LEFT join plan_cuentas PC on PC.id_plan_cuentas=P.id_plan_cuentas     LEFT join proveedores PR on p.proveedor=pr.id_proveedor LEFT join detalle_producto_bodega  dpb on p.cod_productos=dpb.cod_productos   LEFT join  generico g on P.caracteristicas::int = g.id_generico    LEFT join categoria c on P.categoria::int = c.id_categoria  LEFT join marcas m on P.marca::int = m.id_marca LEFT join aplicacion a on P.observaciones::int = a.id_aplicacion where P.articulo ilike '%$texto2%'  and dpb.id_bodega=$conpuntoresult and P.estado = 'Activo' and $campo like '$_GET[searchString]%' ORDER BY p.$sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'bn') {
        $SQL = "select  DISTINCT ON (p.cod_productos) * from productos P     LEFT join punto_venta PV on P.id_bodega = PV.id_punto_venta   LEFT join plan_cuentas PC on PC.id_plan_cuentas=P.id_plan_cuentas     LEFT join proveedores PR on p.proveedor=pr.id_proveedor LEFT join detalle_producto_bodega  dpb on p.cod_productos=dpb.cod_productos   LEFT join  generico g on P.caracteristicas::int = g.id_generico    LEFT join categoria c on P.categoria::int = c.id_categoria  LEFT join marcas m on P.marca::int = m.id_marca LEFT join aplicacion a on P.observaciones::int = a.id_aplicacion where P.articulo ilike '%$texto2%'  and dpb.id_bodega=$conpuntoresult and P.estado = 'Activo' and $campo not like '$_GET[searchString]%' ORDER BY p.$sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ew') {
        $SQL = "select  DISTINCT ON (p.cod_productos) * from productos P     LEFT join punto_venta PV on P.id_bodega = PV.id_punto_venta   LEFT join plan_cuentas PC on PC.id_plan_cuentas=P.id_plan_cuentas     LEFT join proveedores PR on p.proveedor=pr.id_proveedor LEFT join detalle_producto_bodega  dpb on p.cod_productos=dpb.cod_productos   LEFT join  generico g on P.caracteristicas::int = g.id_generico    LEFT join categoria c on P.categoria::int = c.id_categoria  LEFT join marcas m on P.marca::int = m.id_marca LEFT join aplicacion a on P.observaciones::int = a.id_aplicacion where P.articulo ilike '%$texto2%'  and dpb.id_bodega=$conpuntoresult and P.estado = 'Activo' and $campo like '%$_GET[searchString]' ORDER BY p.$sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'en') {
        $SQL = "select  DISTINCT ON (p.cod_productos) * from productos P     LEFT join punto_venta PV on P.id_bodega = PV.id_punto_venta   LEFT join plan_cuentas PC on PC.id_plan_cuentas=P.id_plan_cuentas     LEFT join proveedores PR on p.proveedor=pr.id_proveedor LEFT join detalle_producto_bodega  dpb on p.cod_productos=dpb.cod_productos   LEFT join  generico g on P.caracteristicas::int = g.id_generico    LEFT join categoria c on P.categoria::int = c.id_categoria  LEFT join marcas m on P.marca::int = m.id_marca LEFT join aplicacion a on P.observaciones::int = a.id_aplicacion where P.articulo ilike '%$texto2%'  and dpb.id_bodega=$conpuntoresult and P.estado = 'Activo' and $campo not like '%$_GET[searchString]' ORDER BY p.$sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'cn') {
        $SQL = "select  DISTINCT ON (p.cod_productos) * from productos P     LEFT join punto_venta PV on P.id_bodega = PV.id_punto_venta   LEFT join plan_cuentas PC on PC.id_plan_cuentas=P.id_plan_cuentas     LEFT join proveedores PR on p.proveedor=pr.id_proveedor LEFT join detalle_producto_bodega  dpb on p.cod_productos=dpb.cod_productos   LEFT join  generico g on P.caracteristicas::int = g.id_generico    LEFT join categoria c on P.categoria::int = c.id_categoria  LEFT join marcas m on P.marca::int = m.id_marca LEFT join aplicacion a on P.observaciones::int = a.id_aplicacion where P.articulo ilike '%$texto2%'  and dpb.id_bodega=$conpuntoresult and P.estado = 'Activo'  and $campo like '%$_GET[searchString]%' ORDER BY p.$sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'nc') {
        $SQL = "select  DISTINCT ON (p.cod_productos) * from productos P     LEFT join punto_venta PV on P.id_bodega = PV.id_punto_venta   LEFT join plan_cuentas PC on PC.id_plan_cuentas=P.id_plan_cuentas     LEFT join proveedores PR on p.proveedor=pr.id_proveedor LEFT join detalle_producto_bodega  dpb on p.cod_productos=dpb.cod_productos   LEFT join  generico g on P.caracteristicas::int = g.id_generico    LEFT join categoria c on P.categoria::int = c.id_categoria  LEFT join marcas m on P.marca::int = m.id_marca LEFT join aplicacion a on P.observaciones::int = a.id_aplicacion where P.articulo ilike '%$texto2%'  and dpb.id_bodega=$conpuntoresult and P.estado = 'Activo' and $campo not like '%$_GET[searchString]%' ORDER BY p.$sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'in') {
        $SQL = "select  DISTINCT ON (p.cod_productos) * from productos P     LEFT join punto_venta PV on P.id_bodega = PV.id_punto_venta   LEFT join plan_cuentas PC on PC.id_plan_cuentas=P.id_plan_cuentas     LEFT join proveedores PR on p.proveedor=pr.id_proveedor LEFT join detalle_producto_bodega  dpb on p.cod_productos=dpb.cod_productos   LEFT join  generico g on P.caracteristicas::int = g.id_generico    LEFT join categoria c on P.categoria::int = c.id_categoria  LEFT join marcas m on P.marca::int = m.id_marca LEFT join aplicacion a on P.observaciones::int = a.id_aplicacion where P.articulo ilike '%$texto2%'  and dpb.id_bodega=$conpuntoresult and P.estado = 'Activo' and $campo like '%$_GET[searchString]%' ORDER BY p.$sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ni') {
        $SQL = "select  DISTINCT ON (p.cod_productos) * from productos P     LEFT join punto_venta PV on P.id_bodega = PV.id_punto_venta   LEFT join plan_cuentas PC on PC.id_plan_cuentas=P.id_plan_cuentas     LEFT join proveedores PR on p.proveedor=pr.id_proveedor LEFT join detalle_producto_bodega  dpb on p.cod_productos=dpb.cod_productos   LEFT join  generico g on P.caracteristicas::int = g.id_generico    LEFT join categoria c on P.categoria::int = c.id_categoria  LEFT join marcas m on P.marca::int = m.id_marca LEFT join aplicacion a on P.observaciones::int = a.id_aplicacion where P.articulo ilike '%$texto2%'  and dpb.id_bodega=$conpuntoresult and P.estado = 'Activo' and $campo not like '%$_GET[searchString]%' ORDER BY p.$sidx $sord offset $start limit $limit";
    }
}
$result = pg_query($SQL);

header("Content-type: text/xml;charset=utf-8");
$s = "<?xml version='1.0' encoding='utf-8'?>";
$s .= "<rows>";
$s .= "<page>" . $page . "</page>";
$s .= "<total>" . $total_pages . "</total>";
$s .= "<records>" . $count . "</records>";

    
while ($row = pg_fetch_row($result)) {
    
    $s .= "<row id='" . $row[0] . "'>";
    $s .= "<cell>" . $row[0] . "</cell>";
    $s .= "<cell>" . $row[1] . "</cell>";
    $s .= "<cell>" . $row[2] . "</cell>";
    $s .= "<cell>" . $row[3] . "</cell>";
    $s .= "<cell>" . $row[4] . "</cell>";
    $s .= "<cell>" . $row[5] . "</cell>";
    $s .= "<cell>" . $row[6] . "</cell>";
    $s .= "<cell>" . $row[7] . "</cell>";
    $s .= "<cell>" . $row[9] . "</cell>";
    $s .= "<cell>" . $row[8] . "</cell>";
    $s .= "<cell>" . $row[10] . "</cell>";
    $s .= "<cell>" . $row[77] . "</cell>";
    $s .= "<cell>" . $row[80] . "</cell>";
    $s .= "<cell>" . $row[19] . "</cell>";
    $s .= "<cell>" . $row[72] . "</cell>";
    $s .= "<cell>" . $row[14] . "</cell>";
    $s .= "<cell>" . $row[15] . "</cell>";
    $s .= "<cell>" . $row[16] . "</cell>";
    $s .= "<cell>" . $row[74] . "</cell>";
    $s .= "<cell>" . $row[83] . "</cell>";
    $s .= "<cell>" . $row[20] . "</cell>";
    $s .= "<cell>" . $row[21] . "</cell>";
    $s .= "<cell>" . $row[24] . "</cell>";
    $s .= "<cell>" . $row[25] . "</cell>";
    $s .= "<cell>" . $row[34] . "</cell>";
    $s .= "<cell>" . $row[26] . "</cell>";
    $s .= "<cell>" . $row[27] . "</cell>";
    $s .= "<cell>" . $row[28] . "</cell>";
    $s .= "<cell>" . $row[41]."  -  ".$row[42] . "</cell>";
    $s .= "<cell>" . $row[49] . "</cell>";
    $s .= "<cell>" . $row[30] . "</cell>";
    $s .= "</row>";
}

$s .= "</rows>";
echo $s;
?>