<?php
session_start();
include '../../procesos/base.php';

$idcuenta = $_GET["id_cuenta"];

$sql = "select id_plan_cuentas,codigo_plan,descripcion from plan_cuentas where id_plan_cuentas=$idcuenta";

$res = pg_query($sql);
$row = pg_fetch_assoc($res);

if (empty($row)) {
    $row = [];
}

echo json_encode($row);
