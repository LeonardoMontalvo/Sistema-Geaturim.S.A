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
$result = pg_query("SELECT COUNT(*) AS count from guia_remision F , clientes C where F.id_cliente=C.id_cliente");
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
    $SQL = "select g.id_guia_remision, g.num_autorizacion, g.fecha_actual, C.nombres_cli,C.correo, g.fecha_actual,  t.nombres_trans, g.estado
 from guia_remision g 

  inner join clientes c on g.id_cliente=c.id_cliente 
  inner join transportista t  on t.id_transportista=g.id_transportista ORDER BY $sidx $sord offset $start limit $limit";
} else {

}

$result = pg_query($SQL);

header("Content-type: text/xml;charset=utf-8");
$s = "<?xml version='1.0' encoding='utf-8'?>";
$s .= "<rows>";
$s .= "<page>" . $page . "</page>";
$s .= "<total>" . $total_pages . "</total>";
$s .= "<records>" . $count . "</records>";

while ($row = pg_fetch_row($result)) {
    $valorTotal = $row[6];
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
