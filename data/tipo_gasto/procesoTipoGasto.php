<?php
session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
$cont = 0;
/* $repe = 0; */
date_default_timezone_set('America/Guayaquil');
/* $fecha = date("j/n/Y");
$hora = date("g:ia"); */
//////////////////validar repetidos//////////////////
/* $consulta = pg_query("select * from punto_venta where nombre_punto='" . strtoupper($_POST['nombre_punto']) . "'");
while ($row = pg_fetch_row($consulta)) {
    $repe++;
} */
//////////////////////////////////////////////////    

if ($_POST['oper'] == "add") {
    $consulta = pg_query("select max(id_tipo_gasto) from tipo_gasto");
    while ($row = pg_fetch_row($consulta)) {
        $cont = $row[0];
    }
    $cont++;

    $sql = "INSERT INTO tipo_gasto(
        id_tipo_gasto, nombre_tipo_gasto, estado, id_plan_cuentas)
        VALUES ($cont, '$_POST[nombre_tipo_gasto]', 'Activo', $_POST[id_plan_cuentas]);
    ";
    $res = pg_query($sql);
    // Auditoria
    insert_registro('CREACION TIPO GASTO: ' . strtoupper($_POST['nombre_tipo_gasto']));
} elseif ($_POST['oper'] == "edit") {
    $sql = "update tipo_gasto set nombre_tipo_gasto='$_POST[nombre_tipo_gasto]', id_plan_cuentas=$_POST[id_plan_cuentas] where id_tipo_gasto=$_POST[id]";
    $res = pg_query($sql);
    // Auditoria
    insert_registro('MODIFICACION TIPO GASTO: ' . strtoupper($_POST['nombre_tipo_gasto']));
} elseif ($_POST['oper'] == "del") {
    $sql = "update tipo_gasto set estado='Pasivo' where id_tipo_gasto=$_POST[id]";
    $res = pg_query($sql);
     // Auditoria
     insert_registro('ELIMINACIÓN TIPO GASTO: ' . strtoupper($_POST['nombre_tipo_gasto']));
}
