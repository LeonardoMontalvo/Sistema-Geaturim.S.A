<?php
session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();

$idcaracteristica = $_POST["id_caracteristica"];

$sql = "delete from producto_caracteristicas where id_caracteristica=$idcaracteristica";
$res = pg_query($sql);
if (empty($res)) {
    echo 0;
}
echo $idcaracteristica;
