<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();

$sql=pg_query("select id_ordenes, id_receta, cantidad, costo_total, nombre_usuario, apellido_usuario, procesamiento, ordenes_produccion.estado from ordenes_produccion, usuario where ordenes_produccion.id_usuario = usuario.id_usuario and id_ordenes='" . $id . "'");
while($fila=pg_fetch_row($sql)){
	$consulta = pg_query("select F.id_receta, F.fecha_modificacion, F.estado, F.costo_producto, P.articulo, F.cod_productos from recetas F, productos P where P.cod_productos=F.cod_productos and F.id_receta = '" . $fila[1] . "'");
	while ($row = pg_fetch_row($consulta)) {
	    $arr_data[] = $row[0];
	    $arr_data[] = $row[1];
	    $arr_data[] = $fila[4]." ".$fila[5];
	    $arr_data[] = $fila[7];
	    $arr_data[] = $row[3];
	    $arr_data[] = $row[4];
	    $arr_data[] = $row[5];
	    $arr_data[] = $fila[0];
	    $arr_data[] = $fila[2];
	    $arr_data[] = $fila[3];
	    $arr_data[] = $fila[6];
	}
}
echo json_encode($arr_data);
?>
