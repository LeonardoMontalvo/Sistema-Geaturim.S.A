<?php

session_start();
include '../../procesos/base.php';
conectarse();
$data = "";
$consulta = pg_query(" select um.estado,p.iva,cantidad, precio_compra, pvpmayo, pvpnego from productos p left join unidad_medida_productos ump on p.cod_productos=ump.cod_productos 
left join unidades_medida um on um.id_unidades=ump.id_unidades 
  where p.cod_productos='$_GET[cod_producto]' and um.estado='Activo' and ump.estado='Activo' ");
while ($row = pg_fetch_row($consulta)) {
   
    $data = $data . '*' . $row[0];
  
}
////////////////////////////////
echo $data;
?>
