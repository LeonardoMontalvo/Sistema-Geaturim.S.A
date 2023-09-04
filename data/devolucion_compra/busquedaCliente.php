<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$texto = $_GET['term'];

$consulta = pg_query("select id_proveedor,empresa_pro,identificacion_pro from proveedores 
            where identificacion_pro='$texto' 
            or empresa_pro ilike '%$texto%'
            and estado='Activo'");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[1],
        'label' => $row[0],
         'label1' => $row[2]
    );
}
echo $data = json_encode($data);
?>



