<?php
include_once __DIR__ . "/../../../procesos/base.php";

conectarse();
$idumedidaprod = $_POST["id_umedidia_prducto"];
$idproducto = $_POST["id_producto"];
$preciocompra = $_POST["precio_compra"];

$pvpmin = empty($_POST["pvp_minorista"]) ? "0" : $_POST["pvp_minorista"];
$pvpmay = empty($_POST["pvp_mayorista"]) ? "0" : $_POST["pvp_mayorista"];
$pvpneg = empty($_POST["pvp_negocio"]) ? "0" : $_POST["pvp_negocio"];

$sql = "
update unidad_medida_productos
set 
pvpmino=$pvpmin, 
pvpmayo=$pvpmay, 
pvpnego=$pvpneg
where id_unidad_medida_productos=$idumedidaprod;

update productos
set 
precio_compra=$preciocompra 
where cod_productos=$idproducto;
";


$res = pg_query($sql);

if (!$res) {
    echo json_encode(0);
} else {
    echo json_encode(1);
}
