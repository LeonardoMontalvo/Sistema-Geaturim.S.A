<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
$cont = 0;
$repe = 0;

//////////////////validar repetidos//////////////////
$consulta = pg_query("select * from generico where nombre_generico='" . strtoupper($_POST['nombre_generico']) . "'");
while ($row = pg_fetch_row($consulta)) {
    $repe++;
}
///////////////////////////////////////////////////    

if ($_POST['oper'] == "add") {
    $consulta = pg_query("select max(id_generico) from generico");
    while ($row = pg_fetch_row($consulta)) {
        $cont = $row[0];
    }
    $cont++;

    if ($repe == 0) {
        pg_query("insert into generico values('$cont','" . strtoupper($_POST['nombre_generico']) . "','Activo')");
        // Auditoria
        insert_registro('CREACION GENERICO: ' . strtoupper($_POST['nombre_generico']) );
    }
} elseif ($_POST['oper'] == "edit") {
    if ($repe == 0) {
        pg_query("update generico set id_generico='$_POST[id_generico]', nombre_generico='" . strtoupper($_POST['nombre_generico']) . "' where id_generico='$_POST[id_generico]'");
        // Auditoria
        insert_registro('MODIFICACION GENERICO: ' . strtoupper($_POST['nombre_generico']) );
    }
}
