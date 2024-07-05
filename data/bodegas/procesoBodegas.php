<?php
session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
$cont = 0;
$repe = 0;
date_default_timezone_set('America/Lima');
$fecha = date("j/n/Y");
$hora = date("g:ia");
//////////////////validar repetidos//////////////////
$consulta = pg_query("select * from punto_venta where nombre_punto='" . strtoupper($_POST['nombre_punto']) . "'");
while ($row = pg_fetch_row($consulta)) {
    $repe++;
}
//////////////////////////////////////////////////    

if ($_POST['oper'] == "add") {
    $consulta = pg_query("select max(id_punto_venta) from punto_venta");
    while ($row = pg_fetch_row($consulta)) {
        $cont = $row[0];
    }
    $cont++;

    if ($repe == 0) {
        pg_query("insert into punto_venta values('$cont','" . strtoupper($_POST['nombre_punto']) . "','$_POST[estado]','$fecha','$hora','" . strtoupper($_POST['ubicacion']) . "','$_POST[telefono]','$_SESSION[id]')");
        crearEmpresa($cont);
        crearRegistroParametrosPv($cont);
        // Auditoria
        insert_registro('CREACION PUNTO VENTA: ' . strtoupper($_POST['nombre_punto']));
    }
} elseif ($_POST['oper'] == "edit") {
    pg_query("update punto_venta set nombre_punto='" . strtoupper($_POST['nombre_punto']) . "', estado='$_POST[estado]', fecha_actual='$fecha', hora_actual='$hora' ,ubicacion='" . strtoupper($_POST['ubicacion']) . "', telefono=$_POST[telefono] where id_punto_venta='$_POST[id_punto_venta]'");
    // Auditoria
    insert_registro('MODIFICACION PUNTO VENTA: ' . strtoupper($_POST['nombre_punto']));
}

function crearEmpresa($idpv)
{
    $sql = "INSERT INTO empresa(
        id_empresa, nombre_empresa, ruc_empresa, direccion_empresa, telefono_empresa, 
        celular_empresa, pais_empresa, ciudad_empresa, fax_empresa, email_empresa, 
        pagina_web, descripcion, propietario, imagen, estado, num_items, 
        porcentaje_tarjeta, nombre_comercial, obligacion, contribuyente_espe, 
        token, clave, establecimiento, punto_emision)
        SELECT $idpv id_empresa, nombre_empresa, ruc_empresa, direccion_empresa, telefono_empresa, 
            celular_empresa, pais_empresa, ciudad_empresa, fax_empresa, email_empresa, 
            pagina_web, descripcion, propietario, imagen, estado, num_items, 
            porcentaje_tarjeta, nombre_comercial, obligacion, contribuyente_espe, 
            token, clave, establecimiento, punto_emision
            FROM empresa where id_empresa=1;
        ";
    $res = pg_query($sql);
    return $res;
}

function crearRegistroParametrosPv($idpv)
{
    $sql = "INSERT INTO parametros_punto_venta(id_punto_venta)
    VALUES ($idpv);";
    pg_query($sql);
}
