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
$consulta = pg_query("select cod_productos,articulo,cod_barras,codigo,precio_compra,iva_minorista,iva,cod_productos,incluye_iva,venta_promedio from productos where  estado='Activo' and  articulo like '%$texto2%'    ");
if (pg_num_rows($consulta) > 0) {
    while ($row = pg_fetch_assoc($consulta)) {
//        echo ''."select * from   productos p left join detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos where p.cod_productos=$row[0] and dpb.id_bodega=$conpuntoresult ";
        $consulta1 = pg_query("select dpb.stock from   productos p left join detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos where p.cod_productos='$row[cod_productos]' and dpb.id_bodega=$conpuntoresult ");
        $row1 = pg_fetch_assoc($consulta1);
        if($row['venta_promedio']==""){
           $row['venta_promedio']=$row['precio_compra'];
        }else{
          $row['venta_promedio']=$row['venta_promedio']; 
        }
        
     $data[] = array(
            'value' => $row['articulo'],
            'codigo_barras' => $row['cod_barras'],
            'codigo' => $row['codigo'],
            'precio' => $row['venta_promedio'],
            'p_venta' => $row['iva_minorista'],
            'iva_producto' => $row['iva'],
            'cod_producto' => $row['cod_productos'],
            'incluye' => $row['incluye_iva'],
            'disponibles' => $row1['stock']
        );
    }
    echo $data = json_encode($data);
}
?>
