<?php

session_start();
include '../../procesos/base.php';
require_once '../../procesos/kardexValorizado.php';
// Auditoria
require_once '../../procesos/auditoria.php';
$conexion = conectarse();
error_reporting(0);
$conpuntoresult = $_SESSION['PV'];
$pvinv = $_SESSION['PV_INV'];


if (hayValoresFavorEmpresaCruzados($_POST['comprobante'])) {
  echo json_encode(-1);
  exit();
}

//////////////eliminar series///////////
pg_query("Update devolucion_compra Set estado='Pasivo' where id_devolucion_compra='$_POST[id_devolucion_compra]'");
$data = 1;
insert_registro('ELIMINACION D.C. CON ID: ' . $_POST['comprobante'] . ', DE LA FACTURA: ' . $_POST["id_devolucion_compra"]);

// RESTAR stock productos
/*$consulta = pg_query("select * from detalle_devolucion_compra where id_devolucion_compra = '$_POST[id_devolucion_compra]'");
while ($row = pg_fetch_row($consulta)) {
  $canti1 = $row[3];
  $id = $row[2];
  //	 echo '<br>GUARDAR FACTURA VENTA: <br>' . "select * from detalle_producto_bodega where id_bodega=$conpuntoresult and cod_productos= '" . $id . "'";//////////////////////////
  //	 

  $consulta2 = pg_query("select * from detalle_producto_bodega where id_bodega=$conpuntoresult and cod_productos= '" . $id . "'");
  while ($row = pg_fetch_row($consulta2)) {
    $cod_pro = $row[1];
    $id_bod = $row[2];
    $stock = $row[6];
  }
  $cal = $stock - $arreglo2[$i];
  //pg_query("Update productos Set stock='".$cal1."' where cod_productos='".$id."'");

  /* procesarKardexAnulacionCompras(
            $id,
            $documento,
            $key['cantidad'],
            'AC',
            "C",
            $key['comprobante'],
            $_POST['observacion'],
            $conpuntoresult,
            $key['id_proveedor']
          ); */


//}
$detalleCompra = obtenerDetalleCompra($_POST['id_devolucion_compra'], 1);
foreach ($detalleCompra as $item) {
  $documento = "Anulación D.C: " . $item['num_serie'];

  //    procesarKardexAnulacionCompras($item['cod_productos'],$documento,$key['cantidad'],'AC',"C",$key['comprobante'],'',1,$key['id_proveedor']);
  // 
  $cant = $item['cantidad'];
  if (!empty($item['cantidad_unidad'])) {
    $cant = $item['cantidad_unidad'];
  }
  $stock = obtenerStockProducto($item['cod_productos'], $pvinv);

  procesarKardexEntrada(
    $item['cod_productos'],
    $documento,
    $cant,
    $stock,
    $item['precio_compra'],
    'Activo',
    $pvinv,
    'ADC',
    $_POST['comprobante'],
    NULL,
    NULL,
    NULL,
    '',
    NULL,
    NULL,
    $item['id_proveedor'],
    $_SESSION['id']
  );
}
// fin resta
/////////////////////////////////

$asiento = pg_query("select id_transacciones from transacciones where comprobante='$_POST[id_devolucion_compra]' and concepto like 'DEVOLUCIÓN COMPRA%'");
$row = pg_fetch_row($asiento);
if ($row[0] != "") {
  pg_query("update transacciones set estado='Pasivo' where id_transacciones=$row[0]");
  pg_query("update detalle_transaccion set estado='Pasivo' where id_transacciones=$row[0]");
}

//$retIva=pg_query("select id_retencion_iva_factura_compra from retencion_iva_factura_compra where id_factura='$_POST[id_factura_compra]'");
//$row1=pg_fetch_row($retIva);
//if($row1[0]!= ""){
//    pg_query("update retencion_iva_factura_compra set estado='Pasivo' where id_retencion_iva_factura_compra=$row1[0]");
//}

//$retFuente=pg_query("select id_retencion_fuente_factura_compra from retencion_fuente_factura_compra where id_factura='$_POST[id_factura_compra]' and id_gastos='1'");
//$row1=pg_fetch_row($retFuente);
//if($row1[0]!= ""){
//    pg_query("update retencion_fuente_factura_compra set estado='Pasivo' where id_retencion_fuente_factura_compra=$row1[0] and id_gastos='1'");
//}

if ($_POST["tipo_comprobante"] == 'FACTURA') {
  anularAsientos($_POST["comprobante"]);
  anularPagoCxc($_POST["comprobante"]);
}
echo $data;

function obtenerDetalleCompra($idFactura, $bodega)
{
  $sql = "SELECT cod_productos,cantidad,precio_compra,comprobante,DFC.total_compra,id_proveedor,FC.observaciones,FC.num_serie, DFC.cantidad_unidad 
    FROM detalle_devolucion_compra DFC 
    INNER JOIN devolucion_compra FC ON FC.id_devolucion_compra = DFC.id_devolucion_compra 
    WHERE FC.id_empresa=$bodega AND FC.id_devolucion_compra=$idFactura";
  $row = pg_fetch_all(pg_query($sql));
  return $row;
}
////////////////////////////////
//ANULACION
function anularAsientos($idnc)
{
  global $conpuntoresult, $conexion;
  $sql = "
    select id_transacciones
    from transacciones 
    where id_empresa='" . $conpuntoresult . "' and identificador_cli_pro='DC' 
    and comprobante='$idnc'";

  $res = pg_query($conexion, $sql);
  $rows = pg_fetch_all($res);
  if (empty($rows)) {
    return;
  }
  foreach ($rows as $value) {
    $sql = "UPDATE transacciones
        SET estado='Pasivo' 
        WHERE id_transacciones=$value[id_transacciones];";
    $res = pg_query($conexion, $sql);
  }
}

function buscarPagoCxp($idnc)
{
  global $conexion;
  $sql = "
    select numero_documento,valor from formas_pago_mixto_nc
    where id_devolucion_compra=$idnc
    and forma_pago='CXP'
    and estado='Activo';
    ";
  $res = pg_query($conexion, $sql);
  $rows = pg_fetch_all($res);
  if (empty($rows)) {
    return [];
  }
  return $rows[0];
}

function anularPagoCxc($idnc)
{
  global $conexion;
  $idpago = "";
  $doc = buscarPagoCxp($idnc);
  if (!empty($doc)) {
    $ids = explode(",", $doc["numero_documento"]);
    $valor = $doc["valor"];
    $idcxc = $ids[0];
    $idpago = $ids[1];
    $sql = "
        UPDATE pagos_pagar
        SET estado='Pasivo' 
        WHERE id_cuentas_pagar= $idpago;

        UPDATE pagos_compra
        SET saldo = saldo+$valor,
        estado='Activo'
        WHERE id_pagos_compra= $idcxc;
        ";
    $res = pg_query($conexion, $sql);
  }
}

function hayValoresFavorEmpresaCruzados($idnc)
{
  global $conexion;
  $sql = "select * from formas_pago_mixto_nc
  where estado='Cruzado'
  and id_devolucion_compra=$idnc";
  $res = pg_query($conexion, $sql);
  if (pg_num_rows($res) > 0) {
    return true;
  }
  return false;
}


function obtenerStockProducto($idProducto, $bodega)
{
  $sql = "select stock from detalle_producto_bodega where id_bodega=$bodega and cod_productos= $idProducto";
  $res = pg_query($sql);
  $rows = pg_fetch_assoc($res);
  if (empty($rows)) {
    return [];
  }
  return $rows["stock"];
};
