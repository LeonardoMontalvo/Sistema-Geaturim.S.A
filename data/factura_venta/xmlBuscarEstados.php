<?php 
session_start();
include '../../procesos/base.php';
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$search = $_GET['_search'];
$pv=$_SESSION["PV"];

if (!$sidx)
    $sidx = 1;
$result = pg_query("SELECT COUNT(*) AS count from factura_venta F , clientes C where F.id_cliente=C.id_cliente AND F.id_empresa=$pv");
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
    $SQL = "select F.id_factura_venta, F.num_autorizacion, F.fecha_actual, C.nombres_cli,C.correo, F.fecha_autorizacion, F.total_venta, F.estado_fac from factura_venta F, clientes C where F.id_cliente = C.id_cliente and F.estado='Activo' and F.id_empresa=$pv  ORDER BY $sidx $sord offset $start limit $limit";
} else {
    if ($_GET['searchOper'] == 'eq') {
        $SQL = "select F.id_factura_venta, F.num_autorizacion, F.fecha_actual, C.nombres_cli,C.correo, F.fecha_autorizacion, F.total_venta, F.estado_fac from factura_venta F, clientes C where F.id_cliente = C.id_cliente and F.estado='Activo' and F.id_empresa=$pv and $_GET[searchField] = '$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ne') {
        $SQL = "select F.id_factura_venta, F.num_autorizacion, F.fecha_actual, C.nombres_cli,C.correo, F.fecha_autorizacion, F.total_venta, F.estado_fac from factura_venta F, clientes C where F.id_cliente = C.id_cliente and F.estado='Activo' and F.id_empresa=$pv and $_GET[searchField] != '$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'bw') {
        $SQL = "select F.id_factura_venta, F.num_autorizacion, F.fecha_actual, C.nombres_cli,C.correo, F.fecha_autorizacion, F.total_venta, F.estado_fac from factura_venta F, clientes C where F.id_cliente = C.id_cliente and F.estado='Activo' and F.id_empresa=$pv and $_GET[searchField] like '$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'bn') {
        $SQL = "select F.id_factura_venta, F.num_autorizacion, F.fecha_actual, C.nombres_cli,C.correo, F.fecha_autorizacion, F.total_venta, F.estado_fac from factura_venta F, clientes C where F.id_cliente = C.id_cliente and F.estado='Activo' and F.id_empresa=$pv and $_GET[searchField] not like '$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ew') {
        $SQL = "select F.id_factura_venta, F.num_autorizacion, F.fecha_actual, C.nombres_cli,C.correo, F.fecha_autorizacion, F.total_venta, F.estado_fac from factura_venta F, clientes C where F.id_cliente = C.id_cliente and F.estado='Activo' and F.id_empresa=$pv and $_GET[searchField] like '%$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'en') {
        $SQL = "select F.id_factura_venta, F.num_autorizacion, F.fecha_actual, C.nombres_cli,C.correo, F.fecha_autorizacion, F.total_venta, F.estado_fac from factura_venta F, clientes C where F.id_cliente = C.id_cliente and F.estado='Activo' and F.id_empresa=$pv and $_GET[searchField] not like '%$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'cn') {
        $SQL = "select F.id_factura_venta, F.num_autorizacion, F.fecha_actual, C.nombres_cli,C.correo, F.fecha_autorizacion, F.total_venta, F.estado_fac from factura_venta F, clientes C where F.id_cliente = C.id_cliente and F.estado='Activo' and F.id_empresa=$pv and $_GET[searchField] like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'nc') {
        $SQL = "select F.id_factura_venta, F.num_autorizacion, F.fecha_actual, C.nombres_cli,C.correo, F.fecha_autorizacion, F.total_venta, F.estado_fac from factura_venta F, clientes C where F.id_cliente = C.id_cliente and F.estado='Activo' and F.id_empresa=$pv and $_GET[searchField] not like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'in') {
        $SQL = "select F.id_factura_venta, F.num_autorizacion, F.fecha_actual, C.nombres_cli,C.correo, F.fecha_autorizacion, F.total_venta, F.estado_fac from factura_venta F, clientes C where F.id_cliente = C.id_cliente and F.estado='Activo' and F.id_empresa=$pv and $_GET[searchField] like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ni') {
        $SQL = "select F.id_factura_venta, F.num_autorizacion, F.fecha_actual, C.nombres_cli,C.correo, F.fecha_autorizacion, F.total_venta, F.estado_fac from factura_venta F, clientes C where F.id_cliente = C.id_cliente and F.estado='Activo' and F.id_empresa=$pv and $_GET[searchField] not like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
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
    $valorTotal = round($row[6],2);
    $nombre_estado=$row[7];
     if($nombre_estado==5){
        $row[7]="ERROR.P12";
       
    }
    if($nombre_estado==6){
        $row[7]="CONTRA.INCO.P12";
    }
    
    if($nombre_estado==2){
       
        $row[7]="AUTORIZADO";

    }
    if($nombre_estado==7){
        $row[7]="NO AUTORIZADO";
    }
    if($nombre_estado==1){
        $row[7]="AUTORI.ENVIADO";
    }
    if($nombre_estado==8){
        $row[7]="ERROR WEB.SERV";
    }
    if($nombre_estado==3){
        $row[7]="ERROR CORREO";
    }
    if($nombre_estado==0){
        $row[7]="NO AUTORIZADO";
    }
    
    $s .= "<row id='" . $row[0] . "'>";
    $s .= "<cell>" . $row[0] . "</cell>";
    $s .= "<cell>" . $row[1] . "</cell>";
    $s .= "<cell>" . $row[2] . "</cell>";
    $s .= "<cell>" . $row[3] . "</cell>";
    $s .= "<cell>" . $row[4] . "</cell>"; 
    $s .= "<cell>" . $row[5] . "</cell>";
    
    $s .= "<cell>" . $valorTotal . "</cell>";
    $s .= "<cell  >" . $row[7] . "</cell>";
    $s .= "<cell></cell>"; 
    $s .= "<cell></cell>";
    $s .= "<cell></cell>";
    $s .= "</row>";
}
$s .= "</rows>";
echo $s;
?>
