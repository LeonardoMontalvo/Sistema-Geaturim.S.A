<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
// datos 
$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];

// fin 
// agregar 
$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);

$nelem = count($arreglo1);
// eliminar detalle productos
//    	 echo '<br>GUARDAR FACTURA VENTA: <br>' . "DELETE FROM  anticipos where id_empleado = '$_POST[id_empleado]' and mes='$_POST[select_mes]' and anio='$_POST[slct_anio_cf]'";//////////////////////////
//	 

pg_query("DELETE FROM  anticipos where id_empleado = '$_POST[id_empleado]' and mes='$_POST[select_mes]' and anio='$_POST[slct_anio_cf]'");
// fin  
//print_r($nelem);
for ($i = 1; $i < $nelem; $i++) {

    // contador detalle factura compra
    $cont4 = 0;
    $consulta = pg_query("select max(id_anticipos) from anticipos");
    while ($row = pg_fetch_row($consulta)) {
        $cont4 = $row[0];
    }
    $cont4++;
    // fin
//	 echo '<br>GUARDAR FACTURA VENTA: <br>' .  "insert into anticipos values('$cont4','$_POST[fecha_actual]','$arreglo2[$i]','$arreglo4[$i]','$arreglo3[$i]',$_POST[valor_total],'$_POST[id_empleado]','Activo','$_POST[slct_anio_cf]','$_POST[select_mes]','$_SESSION[id]','$_POST[fecha_actual]')";//////////////////////////
//	

    pg_query("insert into anticipos values('$cont4','$_POST[fecha_actual]','$arreglo2[$i]','$arreglo4[$i]','$arreglo3[$i]',$_POST[valor_total],'$_POST[id_empleado]','Activo','$_POST[slct_anio_cf]','$_POST[select_mes]','$_SESSION[id]','$_POST[fecha_actual]')");
}
$data = $_POST['id_empleado'];

echo $data;
?>
