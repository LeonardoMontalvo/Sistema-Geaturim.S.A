<?php
include_once __DIR__ . "/../../../procesos/base.php";

conectarse();

$idproducto = $_GET["id_producto"];
$umedida = $_GET["unidad_medida"];

$sql = "
select id_unidad_medida_productos,pvpmino,pvpmayo,pvpnego from 
unidad_medida_productos ump
inner join unidades_medida um
using(id_unidades)
where cod_productos=$idproducto
and descripcion='$umedida'
and ump.estado='Activo'
";
$res = pg_query($sql);
$row = pg_fetch_assoc($res);

if (empty($row)) {
    echo json_encode([]);
} else {
    echo json_encode($row);
}
