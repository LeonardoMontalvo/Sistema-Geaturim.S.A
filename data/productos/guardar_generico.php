<?php //

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
error_reporting(0);
$cont = 0;
$repe = 0;

//////////////////validar repetidos//////////////////
$consulta = pg_query("select * from generico where nombre_generico='" . strtoupper($_POST[nombre_generico]) . "'");
while ($row = pg_fetch_row($consulta)) {
    $repe++;
}
/////////////////////////////////////////////////// 

if ($repe == 0) {
///////////////////contador marca//////////////
    $consulta = pg_query("select max(id_generico) from generico");
    while ($row = pg_fetch_row($consulta)) {
        $cont = $row[0];
    }
    $cont++;
////////////////////////////////////////

    pg_query("insert into generico values('$cont','" . strtoupper($_POST[nombre_generico]) . "','Activo')");
    $data = 1;
     // Auditoria
    insert_registro('CREACION GENERICO: ' . strtoupper($_POST['nombre_generico']));
} else {
    $data = 0;
}
echo $data;
?>
