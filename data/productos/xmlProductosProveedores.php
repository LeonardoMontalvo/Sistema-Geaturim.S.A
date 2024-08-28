<?php

include '../../procesos/base.php';
error_reporting(0);
$page = $_GET['page'];
$limit = $_GET['rows'];





$row = pg_fetch_row($result);
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

  
        $SQL = "  SELECT fc.id_factura_compra,c.identificacion_pro,c.empresa_pro ,fc.num_serie,fc.fecha_actual,dfc.cantidad,dfc.precio_compra,dfc.total_compra,p.articulo 
    FROM factura_compra fc, detalle_factura_compra dfc, proveedores c, productos p 
    where fc.id_factura_compra=dfc.id_factura_compra and fc.id_proveedor=c.id_proveedor and dfc.cod_productos=p.cod_productos 
    and dfc.cod_productos= '$_GET[id_producto]' and fc.estado='Activo'  order by fc.fecha_actual offset $start limit $limit";


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
            $s .= "</row>";
        }
        $s .= "</rows>";
    

echo $s;
