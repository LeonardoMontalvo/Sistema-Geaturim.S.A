<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
error_reporting(0);
$data = 0;
$cont = 0;



if ($cont == 0) {
    pg_query("Update cierre_caja Set estado='Pasivo' where comprobante='$_POST[comprobante]'");
    $data = 0;
    // Auditoria
    insert_registro('ELIMINACION CIERRE DE CAJA: ' . $_POST['comprobante']);
} else {
    $data = 1;
}

echo $data;
