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

//    	 echo '<br>GUARDAR FACTURA VENTA: <br>' . "select  t.id_transacciones,fecha_registro,comprobante,identificador_cli_pro,debito,credito,concepto,pc.descripcion 
// from transacciones t, detalle_transaccion dt,plan_cuentas pc where t.id_transacciones=dt.id_transacciones and dt.id_plan_cuentas=pc.id_plan_cuentas
//and (identificador_cli_pro='CxC'  or identificador_cli_pro='CxP'
// or identificador_cli_pro='EGR' or identificador_cli_pro='GAS' or identificador_cli_pro='OTRO'  or identificador_cli_pro='ANTC' or identificador_cli_pro='ANTP'
// or identificador_cli_pro='VEN' or identificador_cli_pro='ING'  or identificador_cli_pro='COM')
//  and dt.id_plan_cuentas ='$id_plan_cuenta' and fecha_registro between '$_GET[f1]' and '$_GET[f2]'  and t.estado='Activo' and  t.id_empresa='$_SESSION[PV]'";
//


    $SQL = "select  t.id_transacciones,fecha_registro,comprobante,identificador_cli_pro,debito,credito,concepto,pc.descripcion, t.id_transacciones
 from transacciones t, detalle_transaccion dt,plan_cuentas pc where t.id_transacciones=dt.id_transacciones and dt.id_plan_cuentas=pc.id_plan_cuentas
and (identificador_cli_pro='CxC'  or identificador_cli_pro='CxP'
 or identificador_cli_pro='EGR' or identificador_cli_pro='GAS' or identificador_cli_pro='OTRO'  or identificador_cli_pro='ANTC' or identificador_cli_pro='ANTP'
 or identificador_cli_pro='VEN' or identificador_cli_pro='ING'  or identificador_cli_pro='COM')
  and dt.id_plan_cuentas ='$id_plan_cuenta' and fecha_registro between '$_GET[f1]' and '$_GET[f2]'  and t.estado='Activo' and  t.id_empresa='$_SESSION[PV]'



 ORDER BY  fecha_registro,$sidx $sord offset $start limit $limit";
}

$query_detalle = pg_query(
        "                SELECT t.id_transacciones,fecha_registro,t.comprobante,identificador_cli_pro, p.empresa_pro,c.nombres_cli,t.concepto,pc.descripcion ,c.nombres_cli,debito,credito , fpm.numero_documento,dg.concepto
            FROM transacciones t
            INNER JOIN detalle_transaccion dt USING(id_transacciones) 
            INNER JOIN plan_cuentas pc USING(id_plan_cuentas)
              left JOIN gastos g on g.id_gastos=T.comprobante::integer
                 left JOIN detalle_gastos dg on g.id_gastos=dg.id_gastos
             left JOIN formas_pago_mixto_g fpm on g.id_gastos=fpm.id_gastos
               left JOIN proveedores p on p.id_proveedor=t.id_cliente
                left JOIN clientes c on c.id_cliente=t.id_cliente
                    INNER JOIN detalle_conciliacion  dc on t.id_transacciones=dc.id_transaccion::int                             
                WHERE 
              dt.id_plan_cuentas = '$id_plan_cuenta'  
            AND T.estado='Activo' and  T.id_empresa='$_SESSION[PV]'
            ORDER BY t.fecha_registro ASC
;
"
);
$id_trans="";
while ($row1 = pg_fetch_row($query_detalle)) {
    
    $id_trans=$row1[1];
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
//    print_r($row[4]);
    if ($row[4] == '0.000') {
        $row[4] = '---';
    } else {
        $row[4] = $row[4] ;
    }
    if ($row[5] == '0.000') {
        $row[5] = '---';
    } else {
        $row[5] = $row[5] ;
    }
      if ($row[1] == $id_trans) {
        $row[7] = '1';
    } else {
        $row[7] = "0" ;
    }
    
    
    $s .= "<row id='" . $row[0] . "'>";
//          $s .= "<cell></cell>";
    $s .= "<cell>" . $row[0] . "</cell>";
    $s .= "<cell>" . $row[1] . "</cell>";
    $s .= "<cell>" . $row[2] . "</cell>";
    $s .= "<cell>" . $row[3] . "</cell>";

    $s .= "<cell>" . $row[4]  . "</cell>";

    $s .= "<cell>" . $row[5]  . "</cell>";

    $s .= "<cell>" . $row[6] . "</cell>";
  $s .= "<cell>" . $row[7] . "</cell>";
    $s .= "</row>";
}
$s .= "</rows>";
echo $s;
?>