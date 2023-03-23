<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
require_once __DIR__ . "/../../procesos/configuracion.php";

$config = new Configuracion();
$logo = $config->getParametroEmpresa("logo_empresa");
$arhcivop12 = $config->getParametroEmpresa("archivo_p12");

conectarse();
error_reporting(0);

if (!empty($_FILES["logo_empresa"])) {
    $logof = $_FILES["logo_empresa"];
    $path = __DIR__ . "/../../images/";
    $ext = pathinfo($logof["name"], PATHINFO_EXTENSION);
    $imagen = guardarArchivo($logof, $path, "logo_empresa." . $ext);
    $resp = guardarParametros([
        "logo_empresa" => $imagen
    ]);
    echo count($resp);
} else if (!empty($_FILES["archivo_p12"])) {
    $p12 = $_FILES["archivo_p12"];
    $np12 = $_FILES["archivo_p12"]["name"];
    $path = __DIR__ . "/../../firma/";
    $imagen = guardarArchivo($p12, $path, $np12, false);
    $resp = guardarParametros([
        "archivo_p12" => $imagen
    ]);
    updateCampoTablaEmpresa("token", $np12);
    echo count($resp);
} else if (!empty($_POST["quitar_parametro"])) {
    quitarParametro($_POST["quitar_parametro"]);
} else {
    $resp = guardarParametros([
        "host_correo" => $_POST["host_correo"],
        "user_correo" => $_POST["user_correo"],
        "pass_correo" => $_POST["pass_correo"],
        "port_correo" => $_POST["port_correo"],
        "smtpsecure_correo" => $_POST["secure_correo"],
        "copia_correo" => $_POST["copia_correo"],
        "app_firma" => $_POST["app_firma"],
        "formato_imperesion_factura" => $_POST["formato_imperesion_factura"],
        "formato_imperesion_nota" => $_POST["formato_imperesion_nota"],
        "formato_imperesion_nota_credito" => $_POST["formato_imperesion_nota_credito"],
        "formato_imperesion_factura_compra" => $_POST["formato_imperesion_factura_compra"],
        "formato_imperesion_retencion_compra" => $_POST["formato_imperesion_retencion_compra"],
        "clave_firma" => $_POST["clave_firma"],
        "autorizar_fac_auto" => $_POST["autorizar_fac_auto"],
    ]);
    updateCampoTablaEmpresa("clave", $_POST["clave_firma"]);
    echo count($resp);
}


function guardarArchivo($file, $path, $name, $prefijoesquema = true)
{
    $nesquema = "";
    if ($prefijoesquema) {
        $nesquema = $_COOKIE["esquema"] . "_";
    }
    $nombref = $nesquema . $name;
    $path .= $nombref;
    if (move_uploaded_file($file["tmp_name"], $path)) {
        return $nombref;
    };
    return false;
}

function guardarParametros($parametros)
{
    $resp = [];
    foreach ($parametros as $key => $val) {
        $sql = "update parametros_empresa set valor_parametro = '$val'
        where nombre_parametro='$key'";
        $res = pg_query($sql);
        if (!empty($res)) {
            insert_registro('MODIFICADO PARAMETRO EMPRESA:' . $key . ' POR USUARIO CON ID:' . $_SESSION["id"]);
        } else {
            $resp = array_push($resp, $key);
        }
    }
    return $resp;
}

function quitarParametro($nomparametro)
{
    $resp = 0;
    $sql = "update parametros_empresa set valor_parametro = ''
    where nombre_parametro='$nomparametro'";
    $res = pg_query($sql);
    if (!empty($res)) {
        if ($nomparametro == "archivo_p12") {
            updateCampoTablaEmpresa("token", "");
        }
        //echo quitarArchivo($parametro);
        insert_registro('MODIFICADO PARAMETRO EMPRESA:' . $nomparametro . ' POR USUARIO CON ID:' . $_SESSION["id"]);
    } else {
        $resp = 1;
    }
    return $resp;
}

function quitarArchivo($filename)
{
    if (unlink($filename)) {
        echo 'The file ' . $filename . ' was deleted successfully!';
    } else {
        echo 'There was a error deleting the file ' . $filename;
    }
}

function updateCampoTablaEmpresa($campo, $valor)
{
    $resp = 0;
    $sql = "update empresa set $campo = '$valor'";
    $res = pg_query($sql);
    if (!empty($res)) {
        $resp = 1;
    }
    return $resp;
}
