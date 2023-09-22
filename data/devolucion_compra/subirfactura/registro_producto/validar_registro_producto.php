<?php
session_start();
include __DIR__ . "/../../../../procesos/base.php";
conectarse();

if (existeCodigoProducto($_POST["cod_prod"])) {
    echo -1;
    exit();
}

if (existeCodigoBarrasProducto($_POST["cod_barras"])) {
    echo -2;
    exit();
}

function existeCodigoProducto($codigo)
{
    $sql = "select*from productos where codigo='$codigo';";
    $res = pg_query($sql);
    return pg_num_rows($res) > 0;
}
function existeCodigoBarrasProducto($codigo)
{
    $sql = "select*from productos where cod_barras='$codigo';";
    $res = pg_query($sql);
    return pg_num_rows($res) > 0;
}

echo 1;
