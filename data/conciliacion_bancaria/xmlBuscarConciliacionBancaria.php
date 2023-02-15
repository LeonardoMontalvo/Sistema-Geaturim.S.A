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
$result = pg_query("SELECT COUNT(*) AS count from conciliacion where estado='Activo'");
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
if ($search == 'false') {
    
//    echo '::'."SELECT DISTINCT ON (dc.id_conciliacion)comprobante,fecha_actual,descripcion,identificador,c.total,c.id_conciliacion
//  FROM conciliacion c,plan_cuentas pc, detalle_conciliacion dc 
//  where  
//  dc.id_conciliacion=c.id_conciliacion 
//  and c.id_plan_cuentas=pc.id_plan_cuentas 
//  
//  and c.estado='Activo'
//   group by dc.id_conciliacion,pc.id_plan_cuentas,dc.id_detalle_conciliacion ,c.id_conciliacion  
//    ORDER BY  dc.id_conciliacion desc ";
//    
//    
    $SQL = "SELECT DISTINCT ON (dc.id_conciliacion)c.id_conciliacion,descripcion,fecha_inicio, 
       fecha_fin,usuario
  FROM conciliacion c,plan_cuentas pc, detalle_conciliacion dc, usuario u
  where  
  dc.id_conciliacion=c.id_conciliacion 
  and c.id_plan_cuentas=pc.id_plan_cuentas   
    and c.id_usuario=u.id_usuario   
  and c.estado='Activo'  
   group by dc.id_conciliacion,pc.id_plan_cuentas,dc.id_detalle_conciliacion ,c.id_conciliacion,usuario  
    ORDER BY  dc.id_conciliacion desc 


 ";
} else {
//    if ($_GET['searchOper'] == 'eq') {
//        $SQL = "SELECT DISTINCT comprobante,fecha_actual,descripcion,identificador,c.total
//  FROM conciliacion c,plan_cuentas pc, detalle_conciliacion dc where  dc.id_conciliacion=c.id_conciliacion 
//  and c.id_plan_cuentas=pc.id_plan_cuentas and c.estado='Activo' group by c.id_conciliacion,pc.id_plan_cuentas,dc.id_detalle_conciliacion order by comprobante and $_GET[searchField] = '$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
//    }
//    if ($_GET['searchOper'] == 'ne') {
//        $SQL = "SELECT DISTINCT comprobante,fecha_actual,descripcion,identificador,c.total
//  FROM conciliacion c,plan_cuentas pc, detalle_conciliacion dc where  dc.id_conciliacion=c.id_conciliacion 
//  and c.id_plan_cuentas=pc.id_plan_cuentas and c.estado='Activo' group by c.id_conciliacion,pc.id_plan_cuentas,dc.id_detalle_conciliacion order by comprobante and $_GET[searchField] != '$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
//    }
//    if ($_GET['searchOper'] == 'bw') {
//        $SQL = "SELECT DISTINCT comprobante,fecha_actual,descripcion,identificador,c.total
//  FROM conciliacion c,plan_cuentas pc, detalle_conciliacion dc where  dc.id_conciliacion=c.id_conciliacion 
//  and c.id_plan_cuentas=pc.id_plan_cuentas and c.estado='Activo' group by c.id_conciliacion,pc.id_plan_cuentas,dc.id_detalle_conciliacion order by comprobante and $_GET[searchField] like '$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
//    }
//    if ($_GET['searchOper'] == 'bn') {
//        $SQL = "SELECT DISTINCT comprobante,fecha_actual,descripcion,identificador,c.total
//  FROM conciliacion c,plan_cuentas pc, detalle_conciliacion dc where  dc.id_conciliacion=c.id_conciliacion 
//  and c.id_plan_cuentas=pc.id_plan_cuentas and c.estado='Activo' group by c.id_conciliacion,pc.id_plan_cuentas,dc.id_detalle_conciliacion order by comprobante and $_GET[searchField] not like '$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
//    }
//    if ($_GET['searchOper'] == 'ew') {
//        $SQL = "SELECT DISTINCT comprobante,fecha_actual,descripcion,identificador,c.total
//  FROM conciliacion c,plan_cuentas pc, detalle_conciliacion dc where  dc.id_conciliacion=c.id_conciliacion 
//  and c.id_plan_cuentas=pc.id_plan_cuentas and c.estado='Activo' group by c.id_conciliacion,pc.id_plan_cuentas,dc.id_detalle_conciliacion order by comprobante and $_GET[searchField] like '%$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
//    }
//    if ($_GET['searchOper'] == 'en') {
//        $SQL = "SELECT DISTINCT comprobante,fecha_actual,descripcion,identificador,c.total
//  FROM conciliacion c,plan_cuentas pc, detalle_conciliacion dc where  dc.id_conciliacion=c.id_conciliacion 
//  and c.id_plan_cuentas=pc.id_plan_cuentas and c.estado='Activo' group by c.id_conciliacion,pc.id_plan_cuentas,dc.id_detalle_conciliacion order by comprobante and $_GET[searchField] not like '%$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
//    }
//    if ($_GET['searchOper'] == 'cn') {
//        $SQL = "SELECT DISTINCT comprobante,fecha_actual,descripcion,identificador,c.total
//  FROM conciliacion c,plan_cuentas pc, detalle_conciliacion dc where  dc.id_conciliacion=c.id_conciliacion 
//  and c.id_plan_cuentas=pc.id_plan_cuentas and c.estado='Activo' group by c.id_conciliacion,pc.id_plan_cuentas,dc.id_detalle_conciliacion order by comprobante and $_GET[searchField] like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
//    }
//    if ($_GET['searchOper'] == 'nc') {
//        $SQL = "SELECT DISTINCT comprobante,fecha_actual,descripcion,identificador,c.total
//  FROM conciliacion c,plan_cuentas pc, detalle_conciliacion dc where  dc.id_conciliacion=c.id_conciliacion 
//  and c.id_plan_cuentas=pc.id_plan_cuentas and c.estado='Activo' group by c.id_conciliacion,pc.id_plan_cuentas,dc.id_detalle_conciliacion order by comprobante and $_GET[searchField] not like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
//    }
//    if ($_GET['searchOper'] == 'in') {
//        $SQL = "SELECT DISTINCT comprobante,fecha_actual,descripcion,identificador,c.total
//  FROM conciliacion c,plan_cuentas pc, detalle_conciliacion dc where  dc.id_conciliacion=c.id_conciliacion 
//  and c.id_plan_cuentas=pc.id_plan_cuentas and c.estado='Activo' group by c.id_conciliacion,pc.id_plan_cuentas,dc.id_detalle_conciliacion order by comprobante and $_GET[searchField] like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
//    }
//    if ($_GET['searchOper'] == 'ni') {
//        $SQL = "SELECT DISTINCT comprobante,fecha_actual,descripcion,identificador,c.total
//  FROM conciliacion c,plan_cuentas pc, detalle_conciliacion dc where  dc.id_conciliacion=c.id_conciliacion 
//  and c.id_plan_cuentas=pc.id_plan_cuentas and c.estado='Activo' group by c.id_conciliacion,pc.id_plan_cuentas,dc.id_detalle_conciliacion order by comprobante and $_GET[searchField] not like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
//    }
    //echo $SQL;
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
    $s .= "</row>";
}
$s .= "</rows>";
echo $s;
?>
