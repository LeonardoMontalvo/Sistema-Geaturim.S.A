<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';

conectarse();
error_reporting(0);
echo json_encode(obtenerParametrosEmpresa());



function obtenerParametrosEmpresa()
{
    $sql = "select*from parametros_empresa";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}
