<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$conpunto = 1;
$consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($consultapunto)) {
  $conpunto = $row[0];
}

$conpuntoresult = 1;
$consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
while ($row = pg_fetch_row($consultapuntoresult)) {
  $conpuntoresult = $row[0];
}
//////////////////////////  
//
//modificar detalle_factura/////
$total = $_POST['total'];
$format_numero = number_format($total, 2, '.', '');
$res = pg_query("update gastos_internos set id_usuario='$_SESSION[id]', id_proveedor='$_POST[id_proveedor]' ,fecha_actual='$_POST[fecha_actual]' ,hora_actual='$_POST[hora_actual]' ,num_factura='$_POST[num_factura]',descripcion='$_POST[descripcion]',total='$format_numero',estado='Activo' where comprobante='$_POST[comprobante]' and id_empresa=$conpuntoresult ");

if (!empty($_POST["id_centro_costo"])) {
  guardarCentroCostoTrans($_POST["comprobante"], $_POST["id_centro_costo"]);
} else {
  quitarCentroCostoTrans($_POST["comprobante"]);
}
////////////////////////////////

$data = 1;
echo $data;


function getIdDetalleCentroCosto()
{
  $sql = "select coalesce(max(id_detalle_centro_costo),0) max from detalle_centro_costos";
  $res = pg_query($sql);
  return pg_fetch_assoc($res)["max"] + 1;
}

function buscarDetalleCentroCostoTrans($idtrans)
{
  $sql = "
    select id_detalle_centro_costo from 
    detalle_centro_costos
    where tipo_documento='gastos_internos'
    and id_documento=$idtrans
    ";
  $res = pg_query($sql);
  $row = pg_fetch_assoc($res);
  if (!empty($row)) {
    return $row["id_detalle_centro_costo"];
  }
  return 0;
}

function guardarCentroCostoTrans($idtrans, $idcentrocosto)
{
  $iddetcc = buscarDetalleCentroCostoTrans($idtrans);
  if (!empty($iddetcc)) {
    $sql = "
        update detalle_centro_costos
        set id_centro_costo=$idcentrocosto
        where tipo_documento='gastos_internos'
        and id_detalle_centro_costo=$iddetcc
        ";
    $res = pg_query($sql);
  } else {
    $id = getIdDetalleCentroCosto();
    $sql = "INSERT INTO detalle_centro_costos(
            id_detalle_centro_costo, id_documento, tipo_documento, 
            id_centro_costo)
            VALUES ($id, $idtrans, 'gastos_internos', 
            $idcentrocosto);";
    $res = pg_query($sql);
  }
}

function quitarCentroCostoTrans($idtrans)
{
  $iddetcc = buscarDetalleCentroCostoTrans($idtrans);
  if (!empty($iddetcc)) {
    $sql = "
        delete from detalle_centro_costos
        where id_detalle_centro_costo=$iddetcc
        ";
  }
  $res = pg_query($sql);
}
