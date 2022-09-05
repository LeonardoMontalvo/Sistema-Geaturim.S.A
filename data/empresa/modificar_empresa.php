<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
error_reporting(0);
//////////////////////////  
$nombre = $_POST['nombre'];
$representante = $_POST['representante'];
$ruc = $_POST['ruc'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$celular = $_POST['celular'];
$fax = $_POST['fax'];
$pais = $_POST['pais'];
$ciudad = $_POST['ciudad'];
$email = $_POST['email'];
$pagina = $_POST['pagina'];
$descripcion = $_POST['descripcion'];
$num_items = $_POST['num_items'];
$nombre_comercial = $_POST['nombre_comercial'];
$obligacion = $_POST['obligacion'];
$contribuyente_espe = $_POST['contribuyente_espe'];
$token = $_POST['token'];
$clave = $_POST['clave'];
$establecimiento = $_POST['establecimiento'];
$punto_emision = $_POST['punto_emision'];
if (isset($_POST['porcen_tc'])) {
    $porcen_tc = $_POST['porcen_tc'];
} else {
    $porcen_tc = '';
}
//guardar cuentas contables/////
pg_query("update empresa set nombre_empresa='$nombre', ruc_empresa='$ruc', direccion_empresa='$direccion', telefono_empresa='$telefono', "
    . "celular_empresa='$celular', pais_empresa='$pais', ciudad_empresa='$ciudad', fax_empresa='$fax', email_empresa='$email', pagina_web='$pagina', "
    . "descripcion='$descripcion', propietario='$representante', num_items='$num_items', nombre_comercial='$nombre_comercial', obligacion='$obligacion', "
    . "contribuyente_espe='$contribuyente_espe', token='$token', clave='$clave', establecimiento='$establecimiento', punto_emision='$punto_emision', "
    . "porcentaje_tarjeta='$porcen_tc'");
////////////////////////////////
// Auditoria
insert_registro('MODIFICACION EMPRESA: ' . $nombre . ' CON RUC: ' . $ruc . ' DE: ' . $representante);
$data = 1;
echo $data;
