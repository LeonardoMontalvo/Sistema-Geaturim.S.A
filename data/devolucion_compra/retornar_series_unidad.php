<?php

session_start();
include '../../procesos/base.php';
conectarse();
//error_reporting(0);
$id = $_GET['cod'];
$arr_data = array();



$num_factu_venta = $_GET["num_fac_venta"];

$tipo_compro = $_GET["tipo_comprobante"];

$codprod = $_GET["cod"];

if ($tipo_compro == 'NOTA') {

  $consulta12 = pg_query("
 SELECT tipo_precio,unidad_medida
  FROM facturas_novalidas,detalle_facturas_novalidas where facturas_novalidas.id_facturas_novalidas=detalle_facturas_novalidas.id_facturas_novalidas AND comprobante='$num_factu_venta' and facturas_novalidas.estado='Activo'
");
  $row1 = pg_fetch_row($consulta12);
  $tipo_precio = $row1[0];
  $unidad_medida = $row1[1];

  $consulta = pg_query("  select um.id_unidades, descripcion,cantidad from productos p left join unidad_medida_productos ump on p.cod_productos=ump.cod_productos 
left join unidades_medida um on um.id_unidades=ump.id_unidades 
  where p.cod_productos='$id' and um.estado='Activo' and descripcion= '$unidad_medida' and  ump.estado='Activo'");
  while ($row = pg_fetch_row($consulta)) {
    $arr_data[] = $row[0];
    $arr_data[] = $row[1] . " ---- " . $row[2];
  }
} else {
  $sql = "
    select * from unidades_medida
    where descripcion =(
    select trim(unidad_medida) from factura_compra fv 
    inner join detalle_factura_compra dfv
    using(id_factura_compra)
    where fv.num_serie = '$num_factu_venta'
    and dfv.cod_productos=$codprod)
    ";
  $res = pg_query($sql);
  $row = pg_fetch_assoc($res);
  if (!empty($row)) {
    $arr_data[] = $row["id_unidades"];
    $arr_data[] = $row["descripcion"] . " ---- " . $row["cantidad"];
  }
}






echo json_encode($arr_data);
