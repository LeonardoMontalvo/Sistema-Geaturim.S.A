<?php
session_start();
include '../../procesos/base.php';

conectarse();
error_reporting(0);

$sql = "
DELETE FROM unidad_medida_productos
 WHERE id_unidad_medida_productos=$_POST[id_umprod];
";

$res = pg_query($sql);
