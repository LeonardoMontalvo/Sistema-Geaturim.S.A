<?php
session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$data = 0;
$cont = 0;

$sql = "
Update registro_equipo Set estado='Pasivo' where id_registro='$_POST[id_registro]';
--Update proforma_tecnico set estado='Pasivo' where id_registro='$_POST[id_registro]';
";
$res=pg_query($sql);
if(!$res){
    $data=1;
}
$data=0;
echo $data;
