<?php
session_start();
include '../../procesos/base.php';
conectarse();

$idgasto = $_POST["id_gasto"];

$sql = "update gastos_personales set estado = 'Pasivo' where id_gastos_personales = $idgasto";
$res = pg_query($sql);
if (empty($res)) {
    echo json_encode(0);
} else {
    echo json_encode(1);
}
