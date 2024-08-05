<?php

session_start();
include '../../procesos/base.php';
conectarse();

$idprod = $_GET["id_producto"];

$sql = "
select
iva_minorista,
iva_mayorista,
iva_negocio
from productos where cod_productos=$idprod
";

$res = pg_query($sql);
$row = pg_fetch_assoc($res);

if (empty($row)) {
    $row = [];
}

echo json_encode($row);
