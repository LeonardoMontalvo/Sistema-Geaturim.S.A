<?php
session_start();
include '../../procesos/base.php';
conectarse();
if (empty($_POST["id_producto"])) {
    exit();
}
$idprod = $_POST["id_producto"];
//$consulta = pg_query("select valor from parametros where descripcion='IVA'");
$consulta = "
select ti.codigo_timpu,tri.codigo_taimpuesto, tri.valor
from productos p 
inner join tipo_impuesto ti using(id_timpu)
inner join tarifa_impuesto tri using(id_taimpuesto) 
where p.cod_productos=$idprod;
";
$data = [];
$res = pg_query($consulta);
$row = pg_fetch_assoc($res);
if (!empty($row)) {
    $data = $row;
}

echo json_encode($data);
