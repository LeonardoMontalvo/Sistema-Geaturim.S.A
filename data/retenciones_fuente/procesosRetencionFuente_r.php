<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
$cont = 0;
$repe = 0;

//////////////////validar repetidos//////////////////
$consulta = pg_query("select * from retencion_fuentes_r where descripcion_r='" . strtoupper($_POST['descripcion_r']) . "'");
while ($row = pg_fetch_row($consulta)) {
    $repe++;
}
///////////////////////////////////////////////

if ($_POST['oper'] == "add") {
    $consulta = pg_query("select max(id_retencion_fuentes_r) from retencion_fuentes_r");
    while ($row = pg_fetch_row($consulta)) {
        $cont = $row[0];
    }
    $cont++;

    if ($repe == 0) {
        pg_query("insert into retencion_fuentes_r values('$cont','" . strtoupper($_POST['descripcion_r']) . "','$_POST[valor_r]','Activo','$_POST[codigo_formulario_r]','$_POST[cuenta_debito]','$_POST[cuenta_credito]')");
        // Auditoria
        insert_registro('CREACION RET. FUENTE RECIVIDAS: ' . strtoupper($_POST['descripcion_r']));
    }
} elseif ($_POST['oper'] == "edit") {

    pg_query("update retencion_fuentes_r set descripcion_r='" . strtoupper($_POST['descripcion_r']) . "' , valor_r='$_POST[valor_r]', estado_r='Activo', codigo_formulario_r='$_POST[codigo_formulario_r]'  where id_retencion_fuentes_r='$_POST[id_retencion_fuentes_r]'");
    if ($_POST[cuenta_debito] != "0") {
        pg_query("update retencion_fuentes_r set  cuenta_debito='$_POST[cuenta_debito]'  where id_retencion_fuentes_r='$_POST[id_retencion_fuentes_r]'");
    }
    if ($_POST[cuenta_credito] != "0") {
        pg_query("update retencion_fuentes_r set  cuenta_credito='$_POST[cuenta_credito]'  where id_retencion_fuentes_r='$_POST[id_retencion_fuentes_r]'");
    }

    insert_registro('MODIFICACION RET. FUENTE RECIVIDAS: ' . strtoupper($_POST['descripcion_r']));
}
