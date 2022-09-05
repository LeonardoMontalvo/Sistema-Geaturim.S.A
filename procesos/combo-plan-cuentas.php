<?php
session_start();
include 'base.php';
conectarse();
error_reporting(0);
$s = '<select  id="s1" style="width:200px;">';
$s .= '<option value="0" >Seleccione...</option>';
$consulta = pg_query(
	"SELECT id_plan_cuentas, codigo_plan, descripcion from plan_cuentas where estado='Activo' order by id_plan_cuentas"
);
while ($row = pg_fetch_row($consulta)) {
	$s .= '<option value=' . $row[0] . '>' . $row[1] . '-' . $row[2] . '</option>';
}
echo $s . '</select>';
