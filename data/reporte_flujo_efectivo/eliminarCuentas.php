<?php
require_once __DIR__ . "/UtilJsonFile.php";
session_start();

$filename = "cuentas.json";
$cuentas = [];

UtilJsonFile::cargarJson($cuentas, $filename);

if (!empty($_GET["cuenta"])) {
    switch ($_GET["cuenta"]) {
        case "efectivo":
            eliminarIdCuentaEf($_POST["id"]);
            echo json_encode("ok");
            break;
    }
}

function eliminarIdCuentaEf($id)
{
    global $cuentas, $filename;
    $cuenasef = [];
    if (!empty($cuentas["efectivo"])) {
        $cuenasef = $cuentas["efectivo"];
    }
    $index = array_search($id, $cuenasef);
    //var_dump($cuenasef);
    if (!!!$index && $index != 0) {
        return;
    }
    //eliminar elementos repetidos 
    array_splice($cuenasef, $index, 1);
    $cuenasef = array_unique($cuenasef);
    $cuentas["efectivo"] = $cuenasef;
    UtilJsonFile::guardarDatos($cuentas, $filename);
}
