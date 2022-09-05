<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$cont1=0;


// contador factura venta
$cont1 = 0;
$consulta = pg_query("select max(id_ordenes) from ordenes_produccion");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;
// fin
        
// guardar reservacion
pg_query("insert into ordenes_produccion values('$cont1','$_SESSION[id]', '$_POST[id_receta]', '$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[cantidad]','$_POST[costo]','Activo',0)");
// fin

    
$data=$cont1;
echo $data;
?>
