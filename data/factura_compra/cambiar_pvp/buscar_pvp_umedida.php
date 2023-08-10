<?php
include_once __DIR__ . "/../../../procesos/base.php";

conectarse();

$idproducto = $_GET["id_producto"];

$sql = "
select id_unidad_medida_productos, descripcion,pvpmino,pvpmayo,pvpnego from 
unidad_medida_productos ump
inner join unidades_medida um
using(id_unidades)
where cod_productos=$idproducto
and ump.estado='Activo' order by um.cantidad::numeric asc
";
$res = pg_query($sql);
$row = pg_fetch_all($res);

if (empty($row)) {
    echo json_encode([]);
} else {
    echo json_encode($row);
}
