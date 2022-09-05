<?php
require_once __DIR__ . "/UtilJsonFile.php";
session_start();

$filename = "cuentas.json";
$cuentas = [];

UtilJsonFile::cargarJson($cuentas, $filename);

if (!empty($_GET["cuenta"])) {
    switch ($_GET["cuenta"]) {
        case "efectivo":
            guardarIdCuentaEf($_POST["id"]);
            echo json_encode("ok");
            break;
    }
}

//guardar ids de cuenas de efectivo en cuentas.json
function guardarIdCuentaEf($id)
{
    global $cuentas, $filename;
    $cuenasef = [];
    if (!empty($cuentas["efectivo"])) {
        $cuenasef = $cuentas["efectivo"];
    }
    array_push($cuenasef, $id);
    //eliminar elementos repetidos 
    $cuenasef = array_unique($cuenasef);
    $cuentas["efectivo"] = $cuenasef;
    UtilJsonFile::guardarDatos($cuentas, $filename);
}
