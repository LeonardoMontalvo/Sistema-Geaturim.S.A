<?php
include_once __DIR__ . "/../../../procesos/base.php";

conectarse();

$idproducto = $_POST["id_producto"];
$preciocompra = $_POST["precio_compra"];

$ids = [];

foreach ($_POST["precios"] as $value) {
    $sql = "
    update unidad_medida_productos
    set 
    pvpmino=$value[pvp_min], 
    pvpmayo=$value[pvp_may], 
    pvpnego=$value[pvp_neg]
    where id_unidad_medida_productos=$value[id];
    ";
    $res = pg_query($sql);
}
$sql = "
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
