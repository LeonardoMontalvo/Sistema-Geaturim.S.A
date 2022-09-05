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
$porcen_tc = $_POST['porcen_tc'];

//guardar cuentas contables/////
pg_query("insert into empresa values(1,'$nombre', '$ruc', '$direccion', '$telefono', '$celular', '$pais', '$ciudad', '$fax', '$email', '$pagina', '$descripcion', '$representante', '', 'Activo','$num_items','$nombre_comercial','$obligacion','$contribuyente_espe','$token','$clave','$establecimiento','$punto_emision','$porcen_tc')");
////////////////////////////////
// Auditoria
insert_registro('CREACION EMPRESA: ' . $nombre . ' CON RUC: ' . $ruc . ' DE: ' . $representante);
    
$data = 1;
echo $data;
