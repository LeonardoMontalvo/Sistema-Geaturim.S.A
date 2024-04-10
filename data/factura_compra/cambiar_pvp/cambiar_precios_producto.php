<?php
include_once __DIR__ . "/../../../procesos/base.php";

conectarse();

$idproducto = $_POST["id_producto"];
$preciocompra = $_POST["precio_compra"];
$preciocompra_modi = $_POST["precio_compra_modi"];
$pvpmin = empty($_POST["pvp_minorista"]) ? "null" : $_POST["pvp_minorista"];
$pvpmay = empty($_POST["pvp_mayorista"]) ? "null" : $_POST["pvp_mayorista"];
$pvpneg = empty($_POST["pvp_negocio"]) ? "null" : $_POST["pvp_negocio"];
$utilmin = empty($_POST["util_minorista"]) ? "null" : $_POST["util_minorista"];
$utilmay = empty($_POST["util_mayorista"]) ? "null" : $_POST["util_mayorista"];
$utilneg = empty($_POST["util_negocio"]) ? "null" : $_POST["util_negocio"];
if ($preciocompra_modi > 0) {
    $preciocompra=$preciocompra_modi;
    
}else{
    $preciocompra=$preciocompra;
}
if ($preciocompra > 0) {
$sql = "update productos
set 
iva_minorista=$pvpmin, 
iva_mayorista=$pvpmay, 
iva_negocio=$pvpneg,
utilidad_minorista=$utilmin,
utilidad_mayorista=$utilmay,
utilidad_negocio=$utilneg,
precio_compra=$preciocompra 
where cod_productos=$idproducto";
} else {
    $sql = "update productos
    set 
    iva_minorista=$pvpmin, 
    iva_mayorista=$pvpmay, 
    iva_negocio=$pvpneg,
    utilidad_minorista=$utilmin,
    utilidad_mayorista=$utilmay,
    utilidad_negocio=$utilneg
    where cod_productos=$idproducto";
}

$res = pg_query($sql);

if (!$res) {
    echo json_encode(0);
} else {
    echo json_encode(1);
}
