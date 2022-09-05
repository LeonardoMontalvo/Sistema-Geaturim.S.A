<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
$cont = 0;
$repe = 0;

//////////////////validar repetidos//////////////////
$consulta = pg_query("select * from plan_cuentas where codigo_plan='$_POST[codigo_cuenta]'");
while ($row = pg_fetch_row($consulta)) {
    $repe++;
}
///////////////////////////////////////////////

if ($_POST['oper'] == "add") {
    $consulta = pg_query("select max(id_plan_cuentas) from plan_cuentas");
    while ($row = pg_fetch_row($consulta)) {
        $cont = $row[0];
    }
    $cont++;

    if ($repe == 0) {
        if ($_POST['cuenta'] == "G") {
            pg_query("insert into plan_cuentas values('$cont','$_POST[codigo_cuenta]','" . strtoupper($_POST['descripcion']) . "','$_POST[cuenta]','Activo')");
            // Auditoria
            insert_registro('CREACION PLAN CUENTAS: ' . $_POST['codigo_cuenta'] . ' ' . strtoupper($_POST['descripcion']) );
        } elseif ($_POST['cuenta'] == "M") {
            pg_query("insert into plan_cuentas values('$cont','$_POST[codigo_cuenta]','$_POST[descripcion]','$_POST[cuenta]','Activo')");
            // Auditoria
            insert_registro('CREACION PLAN CUENTAS: ' . $_POST['codigo_cuenta'] . ' ' . strtoupper($_POST['descripcion']) );
        }
    }
} elseif ($_POST['oper'] == "edit") {
    //if ($repe == 0) {
    if ($_POST['cuenta'] == "G") {
        pg_query("update plan_cuentas set codigo_plan='$_POST[codigo_cuenta]', descripcion='" . strtoupper($_POST['descripcion']) . "', cuenta='$_POST[cuenta]', estado='Activo' where id_plan_cuentas=" . $_POST['id_plan_cuentas']);
        // Auditoria
        insert_registro('MODIFICACION PLAN CUENTAS: ' . $_POST['codigo_cuenta'] . ' ' . strtoupper($_POST['descripcion']) );
    } elseif ($_POST['cuenta'] == "M") {
        pg_query("update plan_cuentas set codigo_plan='$_POST[codigo_cuenta]', descripcion='$_POST[descripcion]', cuenta='$_POST[cuenta]', estado='Activo' where id_plan_cuentas=" . $_POST['id_plan_cuentas']);
        // Auditoria
        insert_registro('MODIFICACION PLAN CUENTAS: ' . $_POST['codigo_cuenta'] . ' ' . strtoupper($_POST['descripcion']) );
    }
    //}
}
