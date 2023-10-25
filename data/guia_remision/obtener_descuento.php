<?php
session_start();
include '../../procesos/base.php';
conectarse();
$codigop = $_GET["cod_prod"];

$sql = "SELECT cantidad_descuento, descuento, cod_productos
FROM productos
where cod_barras='$codigop';
";
$res = pg_query($sql);
$rows = pg_fetch_all($res);
if (empty($rows)) {
    echo json_encode([]);
} else {
    echo json_encode($rows[0]);
}
