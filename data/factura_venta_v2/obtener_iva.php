<?php
session_start();
include '../../procesos/base.php';
conectarse();

$sql = "select valor from parametros where descripcion='IVA'";
$res = pg_query($sql);
echo json_encode(pg_fetch_row($res)[0]);
