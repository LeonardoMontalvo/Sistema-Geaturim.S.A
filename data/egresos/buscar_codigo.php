<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto2 = $_GET['term'];
$conpunto = 1;
$consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($consultapunto)) {
    $conpunto = $row[0];
}
$conpuntoresult = 1;
$consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
while ($row = pg_fetch_row($consultapuntoresult)) {
    $conpuntoresult = $row[0];
}
$consulta = pg_query("select * from productos where codigo like '%$texto2%' and estado = 'Activo'");
if (pg_num_rows($consulta) > 0) {
    while ($row = pg_fetch_row($consulta)) {
        $consulta1 = pg_query("select * from   productos p left join detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos where p.cod_productos=$row[0] and dpb.id_bodega=$conpuntoresult ");
        $row1 = pg_fetch_row($consulta1);
        $data[] = array(
            'value' => $row[1],
            'codigo_barras' => $row[2],
            'producto' => $row[3],
            'precio' => $row[6],
            'p_venta' => $row[9],
            'iva_producto' => $row[4],
            'cod_producto' => $row[0],
            'incluye' => $row[26],
            'disponibles' => $row1[42]
        );
    }
    echo $data = json_encode($data);
}
?>
