<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
$cont = 0;
$repe = 0;

//////////////////validar repetidos//////////////////
$consulta = pg_query("select * from aplicacion where nombre_aplicacion='" . strtoupper($_POST['nombre_aplicacion']) . "'");
while ($row = pg_fetch_row($consulta)) {
    $repe++;
}
///////////////////////////////////////////////////    

if ($_POST['oper'] == "add") {
    $consulta = pg_query("select max(id_aplicacion) from aplicacion");
    while ($row = pg_fetch_row($consulta)) {
        $cont = $row[0];
    }
    $cont++;

    if ($repe == 0) {
        pg_query("insert into aplicacion values('$cont','" . strtoupper($_POST['nombre_aplicacion']) . "','Activo')");
        // Auditoria
        insert_registro('CREACION APLICACION: ' . strtoupper($_POST['nombre_aplicacion']) );
    }
} elseif ($_POST['oper'] == "edit") {
    if ($repe == 0) {
        pg_query("update aplicacion set id_aplicacion='$_POST[id_aplicacion]', nombre_aplicacion='" . strtoupper($_POST['nombre_aplicacion']) . "' where id_aplicacion='$_POST[id_aplicacion]'");
        // Auditoria
        insert_registro('MODIFICACION APLICACION: ' . strtoupper($_POST['nombre_aplicacion']) );
    }
}
