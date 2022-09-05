<?php
session_start();
include '../../procesos/base.php';

function buscarTodo()
{
    $sql = "select * from tipo_documento ORDER BY id_tdocu  ASC";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows;
}

echo json_encode(buscarTodo());
