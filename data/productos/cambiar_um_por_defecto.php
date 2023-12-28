<?php
session_start();
include '../../procesos/base.php';
conectarse();
$idumprod = $_POST['id_umprod'];
$pordefecto = $_POST['por_defecto'];
$idprod = $_POST['id_prod'];

$sql = "
UPDATE unidad_medida_productos set por_defecto = 'f'  where cod_productos=$idprod;
UPDATE unidad_medida_productos set por_defecto = '$pordefecto' where id_unidad_medida_productos=$idumprod;
";
$res = pg_query($sql);

echo json_decode(1);
