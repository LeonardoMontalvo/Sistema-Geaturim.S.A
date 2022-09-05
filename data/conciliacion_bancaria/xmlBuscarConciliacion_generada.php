<?php

session_start();
include '../../procesos/base.php';
$page = $_GET['page'];
$limit = 1000;
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$search = $_GET['_search'];


if (!$sidx)
    $sidx = 1;
$result = pg_query("SELECT COUNT(*) AS count
  FROM transacciones where identificador_cli_pro='CxC'  or identificador_cli_pro='CxP'
");
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


////////////////todo
if ($_GET['id'] != "" && $_GET['f1'] != "" && $_GET['f2'] != "") {
    $plan_cuenta = trim($_GET['id']);
    $id_plan_cuenta = $_GET['id_plan'];

//    	 echo '<br>GUARDAR FACTURA VENTA: <br>' . "select  t.id_transacciones,fecha_actual,comprobante,identificador_cli_pro,total_debe,concepto,pc.descripcion 
// from transacciones t, detalle_transaccion dt,plan_cuentas pc where t.id_transacciones=dt.id_transacciones and dt.id_plan_cuentas=pc.id_plan_cuentas
//and (identificador_cli_pro='CxC'  or identificador_cli_pro='CxP' or identificador_cli_pro='EGR')
//  and pc.descripcion like '%$_GET[id]%' and fecha_actual between '$plan_cuenta' and '$_GET[f2]'";



    $SQL = "select  t.id_transacciones,fecha_registro,comprobante,identificador_cli_pro,debito,credito,concepto,pc.descripcion 
 from transacciones t, detalle_transaccion dt,plan_cuentas pc where t.id_transacciones=dt.id_transacciones and dt.id_plan_cuentas=pc.id_plan_cuentas
and (identificador_cli_pro='CxC'  or identificador_cli_pro='CxP'
 or identificador_cli_pro='EGR' or identificador_cli_pro='GAS' or identificador_cli_pro='OTRO'  or identificador_cli_pro='ANTC' or identificador_cli_pro='ANTP'
 or identificador_cli_pro='VEN' or identificador_cli_pro='ING'  or identificador_cli_pro='COM')
  and dt.id_plan_cuentas ='$id_plan_cuenta' and fecha_registro between '$_GET[f1]' and '$_GET[f2]'  and t.estado='Activo' and  t.id_empresa='$_SESSION[PV]'



 ORDER BY  $sidx $sord offset $start limit $limit";
}
////////////solo clientes
//if($_GET['id']!="")
//  {
//         
//     $SQL = "SELECT FV.id_factura_venta, FV.fecha_actual, C.nombres_cli, FV.num_autorizacion, FV.total_venta::float, FV.estado_fac FROM factura_venta FV, clientes C where C.id_cliente = FV.id_cliente and C.id_cliente='$_GET[id]' and FV.estado_fac='7'   ORDER BY  $sidx $sord offset $start limit $limit";
//  }
//
///////fecha y clientes
//if($_GET['f1']!="" && $_GET['f2']!="" && $_GET['id']!="")
//  {
//     
//     $SQL = "SELECT FV.id_factura_venta, FV.fecha_actual, C.nombres_cli, FV.num_autorizacion, FV.total_venta::float, FV.estado_fac FROM factura_venta FV, clientes C where C.id_cliente = FV.id_cliente and C.id_cliente='$_GET[id]' and FV.estado_fac='7' and FV.fecha_actual between '$_GET[f1]' and '$_GET[f2]'  ORDER BY  $sidx $sord offset $start limit $limit";
//  }
///////solo por fecha
//if($_GET['f1']!=""&&$_GET['f2']!=""  && $_GET['id']=="")
//  {
//     
//     $SQL = "SELECT FV.id_factura_venta, FV.fecha_actual, C.nombres_cli, FV.num_autorizacion, FV.total_venta::float, FV.estado_fac FROM factura_venta FV, clientes C where C.id_cliente = FV.id_cliente  and FV.estado_fac='7' and FV.fecha_actual between '$_GET[f1]' and '$_GET[f2]'   ORDER BY  $sidx $sord offset $start limit $limit";
//  }
///////solo por serie
//if($_GET['s1']!=""&&$_GET['s2']!="")
//  {
//     
//     $SQL = "SELECT FV.id_factura_venta, FV.fecha_actual, C.nombres_cli, FV.num_autorizacion, FV.total_venta::float, FV.estado_fac FROM factura_venta FV, clientes C where C.id_cliente = FV.id_cliente  and FV.estado_fac='7' and FV.num_factura between '$_GET[s1]' and '$_GET[s2]'   ORDER BY  $sidx $sord offset $start limit $limit";
//  }
//print_r(pg_fetch_all(pg_query($SQL)));
$result = pg_query($SQL);
header("Content-type: text/xml;charset=utf-8");
$s = "<?xml version='1.0' encoding='utf-8'?>";
$s .= "<rows>";
$s .= "<page>" . $page . "</page>";
$s .= "<total>" . $total_pages . "</total>";
$s .= "<records>" . $count . "</records>";
while ($row = pg_fetch_row($result)) {

    $s .= "<row id='" . $row[0] . "'>";
//          $s .= "<cell></cell>";
    $s .= "<cell>" . $row[0] . "</cell>";
    $s .= "<cell>" . $row[1] . "</cell>";
    $s .= "<cell>" . $row[2] . "</cell>";
    $s .= "<cell>" . $row[3] . "</cell>";
    $s .= "<cell>" . $row[4] . "</cell>";
    $s .= "<cell>" . $row[5] . "</cell>";
    $s .= "<cell>" . $row[6] . "</cell>";

    $s .= "</row>";
}
$s .= "</rows>";
echo $s;
?>