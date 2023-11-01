<?php
session_start();
include '../../procesos/base.php';
conectarse();
$idumprod = $_POST['id_umprod'];
$pordefecto = $_POST['por_defecto'];

$sql = "
UPDATE unidad_medida_productos set por_defecto = 'f';
UPDATE unidad_medida_productos set por_defecto = '$pordefecto' where id_unidad_medida_productos=$idumprod;
";
$res = pg_query($sql);

echo json_decode(1);
