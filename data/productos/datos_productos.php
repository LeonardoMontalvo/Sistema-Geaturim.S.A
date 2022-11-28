<?php

session_start();
include '../../procesos/base.php';
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$search = $_GET['_search'];

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

    $SQL = " SELECT   DISTINCT ON (p.cod_productos)  P.cod_productos, P.codigo, P.cod_barras, P.articulo, P.iva, P.series, P.precio_compra, P.utilidad_minorista, P.iva_minorista, P.utilidad_mayorista, P.iva_mayorista, "
            . "g.nombre_generico, c.nombre_categoria, P.descuento, P.stock, P.id_usuario, P.stock_minimo, P.stock_maximo, P.fecha_creacion, P.fecha_creacion, dpb.hora, m.nombre_marca, "
            . "P.estado, P.inventariable, P.imagen, P.id_bodega, P.id_bodega, P.incluye_iva, P.iva_negocio, P.id_plan_cuentas, P.cantidad_descuento, P.utilidad_negocio, "
            . "P.bien_servicios, PC.descripcion, PC.id_plan_cuentas, PR.id_proveedor, PR.empresa_pro ,P.cantidad_mayorista,P.cantidad_negocio "
            . "FROM productos P  "
            . "LEFT JOIN plan_cuentas PC on PC.id_plan_cuentas=P.id_plan_cuentas LEFT JOIN proveedores PR on p.id_proveedor=pr.id_proveedor "
            . "LEFT JOIN detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos LEFT JOIN generico g on P.id_generico = g.id_generico "
            . "LEFT JOIN categoria c on P.id_categoria = c.id_categoria LEFT JOIN marcas m on P.id_marca = m.id_marca LEFT JOIN aplicacion a on P.id_aplicacion = a.id_aplicacion "
            . "ORDER BY p.$sidx $sord offset $start limit $limit  ";
    
    
//    ECHO ''.$SQL;
    
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
        $SQL = "SELECT   DISTINCT ON (p.cod_productos)  P.cod_productos, P.codigo, P.cod_barras, P.articulo, P.iva, P.series, P.precio_compra, P.utilidad_minorista, P.iva_minorista, P.utilidad_mayorista, P.iva_mayorista, "
                . "g.nombre_generico, c.nombre_categoria, P.descuento, P.stock, P.id_usuario, P.stock_minimo, P.stock_maximo, P.fecha_creacion, P.fecha_creacion, dpb.hora, m.nombre_marca, "
                . "P.estado, P.inventariable, P.imagen, P.id_bodega, P.id_bodega, P.incluye_iva, P.iva_negocio, P.id_plan_cuentas, P.cantidad_descuento, P.utilidad_negocio, "
                . "P.bien_servicios, PC.descripcion, PC.id_plan_cuentas, PR.id_proveedor, PR.empresa_pro ,P.cantidad_mayorista,P.cantidad_negocio  "
                . "FROM productos P   "
             
                . "LEFT JOIN plan_cuentas PC on PC.id_plan_cuentas=P.id_plan_cuentas "
                . "LEFT JOIN proveedores PR on p.id_proveedor=pr.id_proveedor "
                . "LEFT JOIN detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos "
                . "LEFT JOIN generico g on P.id_generico = g.id_generico "
                . "LEFT JOIN categoria c on P.id_categoria = c.id_categoria "
                . "LEFT JOIN marcas m on P.id_marca = m.id_marca "
                . "LEFT JOIN aplicacion a on P.id_aplicacion = a.id_aplicacion "
                . "WHERE $campo = '$_GET[searchString]' ORDER BY p.$sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ne') {
        $SQL = "SELECT   DISTINCT ON (p.cod_productos)  P.cod_productos, P.codigo, P.cod_barras, P.articulo, P.iva, P.series, P.precio_compra, P.utilidad_minorista, P.iva_minorista, P.utilidad_mayorista, P.iva_mayorista, "
                . "g.nombre_generico, c.nombre_categoria, P.descuento, P.stock, P.id_usuario, P.stock_minimo, P.stock_maximo, P.fecha_creacion, P.fecha_creacion, dpb.hora, m.nombre_marca, "
                . "P.estado, P.inventariable, P.imagen, P.id_bodega, P.id_bodega, P.incluye_iva, P.iva_negocio, P.id_plan_cuentas, P.cantidad_descuento, P.utilidad_negocio, "
                . "P.bien_servicios, PC.descripcion, PC.id_plan_cuentas, PR.id_proveedor, PR.empresa_pro ,P.cantidad_mayorista,P.cantidad_negocio "
                . "FROM productos P   "
                . "LEFT JOIN plan_cuentas PC on PC.id_plan_cuentas=P.id_plan_cuentas "
                . "LEFT JOIN proveedores PR on p.id_proveedor=pr.id_proveedor "
                . "LEFT JOIN detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos "
                . "LEFT JOIN generico g on P.id_generico = g.id_generico "
                . "LEFT JOIN categoria c on P.id_categoria = c.id_categoria "
                . "LEFT JOIN marcas m on P.id_marca = m.id_marca "
                . "LEFT JOIN aplicacion a on P.id_aplicacion = a.id_aplicacion "
                . "WHERE  where $campo != '$_GET[searchString]' ORDER BY p.$sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'bw') {
        $SQL = "SELECT   DISTINCT ON (p.cod_productos)  P.cod_productos, P.codigo, P.cod_barras, P.articulo, P.iva, P.series, P.precio_compra, P.utilidad_minorista, P.iva_minorista, P.utilidad_mayorista, P.iva_mayorista, "
                . "g.nombre_generico, c.nombre_categoria, P.descuento, P.stock, P.id_usuario, P.stock_minimo, P.stock_maximo, P.fecha_creacion, P.fecha_creacion, dpb.hora, m.nombre_marca, "
                . "P.estado, P.inventariable, P.imagen, P.id_bodega, P.id_bodega, P.incluye_iva, P.iva_negocio, P.id_plan_cuentas, P.cantidad_descuento, P.utilidad_negocio, "
                . "P.bien_servicios, PC.descripcion, PC.id_plan_cuentas, PR.id_proveedor, PR.empresa_pro ,P.cantidad_mayorista,P.cantidad_negocio"
                . "FROM productos P   "
                . "LEFT JOIN plan_cuentas PC on PC.id_plan_cuentas=P.id_plan_cuentas "
                . "LEFT JOIN proveedores PR on p.id_proveedor=pr.id_proveedor "
                . "LEFT JOIN detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos "
                . "LEFT JOIN generico g on P.id_generico = g.id_generico "
                . "LEFT JOIN categoria c on P.id_categoria = c.id_categoria "
                . "LEFT JOIN marcas m on P.id_marca = m.id_marca "
                . "LEFT JOIN aplicacion a on P.id_aplicacion = a.id_aplicacion "
                . "WHERE $campo like '$_GET[searchString]%' ORDER BY p.$sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'bn') {
        $SQL = "SELECT   DISTINCT ON (p.cod_productos)  P.cod_productos, P.codigo, P.cod_barras, P.articulo, P.iva, P.series, P.precio_compra, P.utilidad_minorista, P.iva_minorista, P.utilidad_mayorista, P.iva_mayorista, "
                . "g.nombre_generico, c.nombre_categoria, P.descuento, P.stock, P.id_usuario, P.stock_minimo, P.stock_maximo, P.fecha_creacion, P.fecha_creacion, dpb.hora, m.nombre_marca, "
                . "P.estado, P.inventariable, P.imagen, P.id_bodega, P.id_bodega, P.incluye_iva, P.iva_negocio, P.id_plan_cuentas, P.cantidad_descuento, P.utilidad_negocio, "
                . "P.bien_servicios, PC.descripcion, PC.id_plan_cuentas, PR.id_proveedor, PR.empresa_pro,P.cantidad_mayorista,P.cantidad_negocio "
                . "FROM productos P   "
                . "LEFT JOIN plan_cuentas PC on PC.id_plan_cuentas=P.id_plan_cuentas "
                . "LEFT JOIN proveedores PR on p.id_proveedor=pr.id_proveedor "
                . "LEFT JOIN detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos "
                . "LEFT JOIN generico g on P.id_generico = g.id_generico "
                . "LEFT JOIN categoria c on P.id_categoria = c.id_categoria "
                . "LEFT JOIN marcas m on P.id_marca = m.id_marca "
                . "LEFT JOIN aplicacion a on P.id_aplicacion = a.id_aplicacion "
                . "WHERE $campo not like '$_GET[searchString]%' ORDER BY p.$sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ew') {
        $SQL = "SELECT   DISTINCT ON (p.cod_productos)  P.cod_productos, P.codigo, P.cod_barras, P.articulo, P.iva, P.series, P.precio_compra, P.utilidad_minorista, P.iva_minorista, P.utilidad_mayorista, P.iva_mayorista, "
                . "g.nombre_generico, c.nombre_categoria, P.descuento, P.stock, P.id_usuario, P.stock_minimo, P.stock_maximo, P.fecha_creacion, P.fecha_creacion, dpb.hora, m.nombre_marca, "
                . "P.estado, P.inventariable, P.imagen, P.id_bodega, P.id_bodega, P.incluye_iva, P.iva_negocio, P.id_plan_cuentas, P.cantidad_descuento, P.utilidad_negocio, "
                . "P.bien_servicios, PC.descripcion, PC.id_plan_cuentas, PR.id_proveedor, PR.empresa_pro,P.cantidad_mayorista,P.cantidad_negocio "
                . "FROM productos P  "
                . "LEFT JOIN plan_cuentas PC on PC.id_plan_cuentas=P.id_plan_cuentas "
                . "LEFT JOIN proveedores PR on p.id_proveedor=pr.id_proveedor "
                . "LEFT JOIN detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos "
                . "LEFT JOIN generico g on P.id_generico = g.id_generico "
                . "LEFT JOIN categoria c on P.id_categoria = c.id_categoria "
                . "LEFT JOIN marcas m on P.id_marca = m.id_marca "
                . "LEFT JOIN aplicacion a on P.id_aplicacion = a.id_aplicacion "
                . "WHERE $campo like '%$_GET[searchString]' ORDER BY p.$sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'en') {
        $SQL = "SELECT   DISTINCT ON (p.cod_productos)  P.cod_productos, P.codigo, P.cod_barras, P.articulo, P.iva, P.series, P.precio_compra, P.utilidad_minorista, P.iva_minorista, P.utilidad_mayorista, P.iva_mayorista, "
                . "g.nombre_generico, c.nombre_categoria, P.descuento, P.stock, P.id_usuario, P.stock_minimo, P.stock_maximo, P.fecha_creacion, P.fecha_creacion, dpb.hora, m.nombre_marca, "
                . "P.estado, P.inventariable, P.imagen, P.id_bodega, P.id_bodega, P.incluye_iva, P.iva_negocio, P.id_plan_cuentas, P.cantidad_descuento, P.utilidad_negocio, "
                . "P.bien_servicios, PC.descripcion, PC.id_plan_cuentas, PR.id_proveedor, PR.empresa_pro,P.cantidad_mayorista,P.cantidad_negocio "
                . "FROM productos P   "
                . "LEFT JOIN plan_cuentas PC on PC.id_plan_cuentas=P.id_plan_cuentas "
                . "LEFT JOIN proveedores PR on p.id_proveedor=pr.id_proveedor "
                . "LEFT JOIN detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos "
                . "LEFT JOIN generico g on P.id_generico = g.id_generico "
                . "LEFT JOIN categoria c on P.id_categoria = c.id_categoria "
                . "LEFT JOIN marcas m on P.id_marca = m.id_marca "
                . "LEFT JOIN aplicacion a on P.id_aplicacion = a.id_aplicacion "
                . "WHERE $campo not like '%$_GET[searchString]' ORDER BY p.$sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'cn') {
        $SQL = "SELECT   DISTINCT ON (p.cod_productos)  P.cod_productos, P.codigo, P.cod_barras, P.articulo, P.iva, P.series, P.precio_compra, P.utilidad_minorista, P.iva_minorista, P.utilidad_mayorista, P.iva_mayorista, "
                . "g.nombre_generico, c.nombre_categoria, P.descuento, P.stock, P.id_usuario, P.stock_minimo, P.stock_maximo, P.fecha_creacion, P.fecha_creacion, dpb.hora, m.nombre_marca, "
                . "P.estado, P.inventariable, P.imagen, P.id_bodega, P.id_bodega, P.incluye_iva, P.iva_negocio, P.id_plan_cuentas, P.cantidad_descuento, P.utilidad_negocio, "
                . "P.bien_servicios, PC.descripcion, PC.id_plan_cuentas, PR.id_proveedor, PR.empresa_pro,P.cantidad_mayorista,P.cantidad_negocio "
                . "FROM productos P   "
              
                . "LEFT JOIN plan_cuentas PC on PC.id_plan_cuentas=P.id_plan_cuentas "
                . "LEFT JOIN proveedores PR on p.id_proveedor=pr.id_proveedor "
                . "LEFT JOIN detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos "
                . "LEFT JOIN generico g on P.id_generico = g.id_generico "
                . "LEFT JOIN categoria c on P.id_categoria = c.id_categoria "
                . "LEFT JOIN marcas m on P.id_marca = m.id_marca "
                . "LEFT JOIN aplicacion a on P.id_aplicacion = a.id_aplicacion "
                . "WHERE $campo like '%$_GET[searchString]%' ORDER BY p.$sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'nc') {
        $SQL = "SELECT   DISTINCT ON (p.cod_productos)  P.cod_productos, P.codigo, P.cod_barras, P.articulo, P.iva, P.series, P.precio_compra, P.utilidad_minorista, P.iva_minorista, P.utilidad_mayorista, P.iva_mayorista, "
                . "g.nombre_generico, c.nombre_categoria, P.descuento, P.stock, P.id_usuario, P.stock_minimo, P.stock_maximo, P.fecha_creacion, P.fecha_creacion, dpb.hora, m.nombre_marca, "
                . "P.estado, P.inventariable, P.imagen, P.id_bodega, P.id_bodega, P.incluye_iva, P.iva_negocio, P.id_plan_cuentas, P.cantidad_descuento, P.utilidad_negocio, "
                . "P.bien_servicios, PC.descripcion, PC.id_plan_cuentas, PR.id_proveedor, PR.empresa_pro,P.cantidad_mayorista,P.cantidad_negocio "
                . "FROM productos P   "
                . "LEFT JOIN plan_cuentas PC on PC.id_plan_cuentas=P.id_plan_cuentas "
                . "LEFT JOIN proveedores PR on p.id_proveedor=pr.id_proveedor "
                . "LEFT JOIN detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos "
                . "LEFT JOIN generico g on P.id_generico = g.id_generico "
                . "LEFT JOIN categoria c on P.id_categoria = c.id_categoria "
                . "LEFT JOIN marcas m on P.id_marca = m.id_marca "
                . "LEFT JOIN aplicacion a on P.id_aplicacion = a.id_aplicacion "
                . "WHERE $campo not like '%$_GET[searchString]%' ORDER BY p.$sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'in') {
        $SQL = "SELECT   DISTINCT ON (p.cod_productos)  P.cod_productos, P.codigo, P.cod_barras, P.articulo, P.iva, P.series, P.precio_compra, P.utilidad_minorista, P.iva_minorista, P.utilidad_mayorista, P.iva_mayorista, "
                . "g.nombre_generico, c.nombre_categoria, P.descuento, P.stock, P.id_usuario, P.stock_minimo, P.stock_maximo, P.fecha_creacion, P.fecha_creacion, dpb.hora, m.nombre_marca, "
                . "P.estado, P.inventariable, P.imagen, P.id_bodega, P.id_bodega, P.incluye_iva, P.iva_negocio, P.id_plan_cuentas, P.cantidad_descuento, P.utilidad_negocio, "
                . "P.bien_servicios, PC.descripcion, PC.id_plan_cuentas, PR.id_proveedor, PR.empresa_pro ,P.cantidad_mayorista,P.cantidad_negocio"
                . "FROM productos P  "
                . "LEFT JOIN plan_cuentas PC on PC.id_plan_cuentas=P.id_plan_cuentas "
                . "LEFT JOIN proveedores PR on p.id_proveedor=pr.id_proveedor "
                . "LEFT JOIN detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos "
                . "LEFT JOIN generico g on P.id_generico = g.id_generico "
                . "LEFT JOIN categoria c on P.id_categoria = c.id_categoria "
                . "LEFT JOIN marcas m on P.id_marca = m.id_marca "
                . "LEFT JOIN aplicacion a on P.id_aplicacion = a.id_aplicacion "
                . "WHERE $campo like '%$_GET[searchString]%' ORDER BY p.$sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ni') {
        $SQL = "SELECT   DISTINCT ON (p.cod_productos)  P.cod_productos, P.codigo, P.cod_barras, P.articulo, P.iva, P.series, P.precio_compra, P.utilidad_minorista, P.iva_minorista, P.utilidad_mayorista, P.iva_mayorista, "
                . "g.nombre_generico, c.nombre_categoria, P.descuento, P.stock, P.id_usuario, P.stock_minimo, P.stock_maximo, P.fecha_creacion, P.fecha_creacion, dpb.hora, m.nombre_marca, "
                . "P.estado, P.inventariable, P.imagen, P.id_bodega, P.id_bodega, P.incluye_iva, P.iva_negocio, P.id_plan_cuentas, P.cantidad_descuento, P.utilidad_negocio, "
                . "P.bien_servicios, PC.descripcion, PC.id_plan_cuentas, PR.id_proveedor, PR.empresa_pro ,P.cantidad_mayorista,P.cantidad_negocio "
                . "FROM productos P   "
                . "LEFT JOIN plan_cuentas PC on PC.id_plan_cuentas=P.id_plan_cuentas "
                . "LEFT JOIN proveedores PR on p.id_proveedor=pr.id_proveedor "
                . "LEFT JOIN detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos "
                . "LEFT JOIN generico g on P.id_generico = g.id_generico "
                . "LEFT JOIN categoria c on P.id_categoria = c.id_categoria "
                . "LEFT JOIN marcas m on P.id_marca = m.id_marca "
                . "LEFT JOIN aplicacion a on P.id_aplicacion = a.id_aplicacion "
                . "WHERE $campo not like '%$_GET[searchString]%' ORDER BY p.$sidx $sord offset $start limit $limit";
    }
}
/* echo '<br>OBTENER DATOS<br>';
  echo $SQL.'<br>'; */
$result = pg_query($SQL);

header("Content-type: text/xml;charset=utf-8");
$s = "<?xml version='1.0' encoding='utf-8'?>";
$s .= "<rows>";
$s .= "<page>" . $page . "</page>";
$s .= "<total>" . $total_pages . "</total>";
$s .= "<records>" . $count . "</records>";

//while ($row = pg_fetch_row($result)) {
while ($row = pg_fetch_assoc($result)) {

    $s .= "<row id='" . $row['cod_productos'] . "'>"; //cod_productos
    $s .= "<cell>" . $row['cod_productos'] . "</cell>"; //cod_productos
    $s .= "<cell>" . $row['codigo'] . "</cell>"; //codigo
    $s .= "<cell>" . $row['cod_barras'] . "</cell>"; //cod_barras
    $s .= "<cell>" . htmlspecialchars($row['articulo']) . "</cell>"; //articulo
    $s .= "<cell>" . $row['iva'] . "</cell>"; //iva
    $s .= "<cell>" . $row['series'] . "</cell>"; //series
    $s .= "<cell>" . $row['precio_compra'] . "</cell>"; //precio_compra
    $s .= "<cell>" . $row['utilidad_minorista'] . "</cell>"; //utilidad_minorista
    $s .= "<cell>" . $row['iva_minorista'] . "</cell>"; //iva_minorista
    $s .= "<cell>" . $row['utilidad_mayorista'] . "</cell>"; //utilidad_mayorista
    $s .= "<cell>" . $row['iva_mayorista'] . "</cell>"; //iva_mayorista
    $s .= "<cell>" . htmlspecialchars($row['nombre_generico']) . "</cell>"; //nombre_generico
    $s .= "<cell>" . htmlspecialchars($row['nombre_categoria']) . "</cell>"; //nombre_categoria
    $s .= "<cell>" . $row['descuento'] . "</cell>"; //descuento
    $s .= "<cell>" . $row['stock'] . "</cell>"; //stock
    $s .= "<cell>" . $row['id_usuario'] . "</cell>"; //id_usuario
    $s .= "<cell>" . $row['stock_minimo'] . "</cell>"; //stock_minimo
    $s .= "<cell>" . $row['stock_maximo'] . "</cell>"; //stock_maximo
    $s .= "<cell>" . $row['fecha_creacion'] . "</cell>"; //fecha_creacion
    $s .= "<cell>" . $row['hora'] . "</cell>"; //hora_actual
    $s .= "<cell>" . htmlspecialchars($row['nombre_marca']) . "</cell>"; //nombre_marca
    $s .= "<cell>" . $row['estado'] . "</cell>"; //estado
    $s .= "<cell>" . $row['inventariable'] . "</cell>"; //inventariable
    $s .= "<cell>" . $row['imagen'] . "</cell>"; //imagen
    $s .= "<cell>" . $row['id_bodega'] . "</cell>"; //id_bodega
    $s .= "<cell>" . $row['id_bodega'] . "</cell>"; //nombre_punto
    $s .= "<cell>" . $row['incluye_iva'] . "</cell>"; //incluye_iva
    $s .= "<cell>" . $row['utilidad_negocio'] . "</cell>"; //utilidad_negocio
    $s .= "<cell>" . $row['iva_negocio'] . "</cell>"; //iva_negocio
    $s .= "<cell>" . $row['id_plan_cuentas'] . "</cell>"; //id_plan_cuentas
    $s .= "<cell>" . htmlspecialchars($row['descripcion']) . "  -  " . $row['id_plan_cuentas'] . "</cell>"; //descripcion plan de cuenta - id_plan_cuentas
    //$s .= "<cell>" . $row[54] . "</cell>"; //tipo_documento
    $s .= "<cell>" . $row['empresa_pro'] . "</cell>"; //proveedor
    $s .= "<cell>" . $row['id_proveedor'] . "</cell>"; //id_proveedor
    $s .= "<cell>" . $row['cantidad_descuento'] . "</cell>"; //cantidad_descuento
    $s .= "<cell>" . $row['bien_servicios'] . "</cell>"; //bien_servicios
      $s .= "<cell>" . $row['cantidad_mayorista'] . "</cell>";
        $s .= "<cell>" . $row['cantidad_negocio'] . "</cell>";
    $s .= "</row>";
}

$s .= "</rows>";
echo $s;
?>