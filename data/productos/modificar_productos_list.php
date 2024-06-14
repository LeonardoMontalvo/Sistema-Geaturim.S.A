<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
error_reporting(0);


$idproducto = $_POST["cod_productos"];
$nomparam = $_POST["nombre_columna"];
$valparam = $_POST["valor"];

//   $val = (empty($valparam) ? null : $valparam);
 $resp =   modificarParametro($idproducto, $nomparam, $valparam);
     echo $resp;
function modificarParametro($idproducto, $nomparam, $valparam)
{
//    $val = (empty($valparam) ? 'null' : $valparam);
    $sql = "
    UPDATE productos
    SET $nomparam='$valparam'
    WHERE cod_productos=$idproducto
    ";

    $res = pg_query($sql);
//    echo '--'."
//    UPDATE productos
//    SET $nomparam='$valparam'
//    WHERE cod_productos=$idproducto
//    ";

    if (empty($res)) {
        return 0;
    }
    return $idproducto;
}




