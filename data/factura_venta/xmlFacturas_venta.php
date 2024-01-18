<?php

include '../../procesos/base.php';
error_reporting(0);
$page = $_GET['page'];
$limit = $_GET['rows'];


    $result = pg_query("SELECT COUNT(*) AS count from anticipo_clientes F , clientes C where f.id_clientes=c.id_cliente");


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
        
        
$SQL = "SELECT id_anticipo_clientes, comprobante, fecha_actual, forma_pago, observacion,monto    
      
  FROM anticipo_clientes, clientes where anticipo_clientes.id_clientes=clientes.id_cliente
  and anticipo_clientes.id_clientes='$_GET[id_cliente]' and anticipo_clientes.estado = 'Activo'
 offset $start limit $limit;";
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
            $s .= "</row>";
        }
        $s .= "</rows>";
  


echo $s;
