<?php
session_start();
include '../../procesos/base.php';

$comp = $_GET["comprobante"];

$sql = "select*from formas_pago_mixto_cxp
where comprobante_pago=$comp";
$res = pg_query($sql);
$rows = pg_fetch_all($res);
if (empty($rows)) {
    $rows = [];
}
echo json_encode($rows);
