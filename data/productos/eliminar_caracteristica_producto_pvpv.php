<?php
session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();

$idcaracteristica = $_POST["id_caracteristica_pvpv"];

$sql = "delete from pvp_venta_editable where id_pvp_venta_editable='$idcaracteristica'";
$res = pg_query($sql);
if (empty($res)) {
    echo 0;
}
echo $idcaracteristica;
