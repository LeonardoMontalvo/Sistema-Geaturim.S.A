<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
error_reporting(0);
$data = 0;
$cont = 0;

function tieneStock()
{
    $sql = "select coalesce(stock,0)stock from detalle_producto_bodega
    where cod_productos=$_POST[cod_productos] and id_bodega=$_SESSION[PV];";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!!$rows) {
        $row = $rows[0];
        if ($row["stock"] > 0) {
            return true;
        }
    }
    return false;
}

if(tieneStock()){
    exit("-1");
}

////////////////////contadores///////////////

$consulta = pg_query("select * from productos P, detalle_proforma D where P.cod_productos = D.cod_productos and D.cod_productos = '$_POST[cod_productos]'");
while ($row = pg_fetch_row($consulta)) {
    $cont++;
}

$consulta2 = pg_query("select * from productos P, detalle_factura_compra D where P.cod_productos = D.cod_productos and D.cod_productos = '$_POST[cod_productos]'");
while ($row = pg_fetch_row($consulta2)) {
    $cont++;
}

$consulta3 = pg_query("select * from productos P, detalle_factura_venta D where P.cod_productos = D.cod_productos and D.cod_productos = '$_POST[cod_productos]'");
while ($row = pg_fetch_row($consulta3)) {
    $cont++;
}

$consulta4 = pg_query("select * from productos P, detalle_ingreso D where P.cod_productos = D.cod_productos and D.cod_productos = '$_POST[cod_productos]'");
while ($row = pg_fetch_row($consulta4)) {
    $cont++;
}

$consulta5 = pg_query("select * from productos P, detalle_egreso D where P.cod_productos = D.cod_productos and D.cod_productos = '$_POST[cod_productos]'");
while ($row = pg_fetch_row($consulta5)) {
    $cont++;
}

if ($cont != 0 || $cont == 0) {
    pg_query("Update productos Set estado='Pasivo' where cod_productos='$_POST[cod_productos]'");
    $data = 0;
    // Auditoria
    insert_registro('ELIMINACION PRODUCTO CON ID: ' . $_POST['cod_productos']);
} else {
    $data = 1;
}

echo $data;
