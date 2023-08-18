<?php
include_once __DIR__ . "/../../../procesos/base.php";

conectarse();

$idproducto = $_GET["id_producto"];

$sql = "
select iva_minorista,iva_mayorista,iva_negocio,precio_compra,
utilidad_minorista, utilidad_mayorista, utilidad_negocio
from productos where cod_productos=$idproducto";

$res = pg_query($sql);
$row = pg_fetch_assoc($res);

if (empty($row)) {
    echo json_encode([]);
} else {
    echo json_encode($row);
}
