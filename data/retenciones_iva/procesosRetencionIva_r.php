<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
$cont = 0;
$repe = 0;

//////////////////validar repetidos//////////////////
$consulta = pg_query("select * from retencion_iva_r where descripcion_r='" . strtoupper($_POST['descripcion_r']) . "'");
while ($row = pg_fetch_row($consulta)) {
    $repe++;
}
///////////////////////////////////////////////

if ($_POST['oper'] == "add") {
    $consulta = pg_query("select max(id_retencion_iva_r) from retencion_iva_r");
    while ($row = pg_fetch_row($consulta)) {
        $cont = $row[0];
    }
    $cont++;
    if ($repe == 0) {
        pg_query("insert into retencion_iva_r values('$cont', '" . strtoupper($_POST['descripcion_r']) . "', '$_POST[valor_r]', 'Activo', '$_POST[cuenta_debito]', '$_POST[cuenta_credito]', '$_POST[codigo_formulario_r]')");
        // Auditoria
        insert_registro('CREACION RET. IVA RECIVIDAS: ' . strtoupper($_POST['descripcion_r']) );
    }
} else {
    if ($_POST['oper'] == "edit") {
        //if ($repe == 0) {

        pg_query("update retencion_iva_r set descripcion_r='" . strtoupper($_POST['descripcion_r']) . "' , valor_r='$_POST[valor_r]', estado_r='Activo', cuenta_debito='$_POST[cuenta_debito]',cuenta_credito='$_POST[cuenta_credito]', codigo_formulario_r='$_POST[codigo_formulario_r]' where id_retencion_iva_r='$_POST[id_retencion_iva_r]'");
        //}
        // Auditoria
        insert_registro('MODIFICACION RET. IVA RECIVIDAS: ' . strtoupper($_POST['descripcion_r']) );
    }
}
