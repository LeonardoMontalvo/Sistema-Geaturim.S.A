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
$campo5 = $_POST['campo5'];
$campo6 = $_POST['campo6'];
$campo7 = $_POST['campo7'];

// fin 
// agregar 
$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);

$arreglo5 = explode('|', $campo5);
$arreglo6 = explode('|', $campo6);
$arreglo7 = explode('|', $campo7);

$nelem = count($arreglo1);
// eliminar detalle productos
//    	 echo '<br>GUARDAR FACTURA VENTA: <br>' . "DELETE FROM  multas where id_empleado = '$_POST[id_empleadom]' and mes='$_POST[select_mesm]' and anio='$_POST[slct_anio_cfm]'";//////////////////////////
//	 

pg_query("DELETE FROM  horas_extras where id_empleado = '$_POST[id_empleadoh]' and mes='$_POST[select_mesh]' and anio='$_POST[slct_anio_cfh]'");
// fin  

for ($i = 0; $i <= $nelem; $i++) {

    // contador detalle factura compra
    $cont4 = 0;
    $consulta = pg_query("select max(id_horas_extras) from horas_extras");
    while ($row = pg_fetch_row($consulta)) {
        $cont4 = $row[0];
    }
    $cont4++;
    // fin
//    echo '<br>GUARDAR FACTURA VENTA: <br>' . "insert into horas_extras values('$cont2','$_POST[fecha_actual]','$arreglo2[$i]','$arreglo5[$i]','$arreglo6[$i]','$arreglo7[$i]','$_POST[valor_totalh]','$arreglo1[$i]','Activo','$_POST[slct_anio_cfh]','$_POST[select_mesh]','$_SESSION[id]','$_POST[fecha_actual]','$arreglo4[$i]')"; //////////////////////////
//	



    pg_query("insert into horas_extras values('$cont4','$_POST[fecha_actualh]','$arreglo2[$i]','$arreglo5[$i]','$arreglo6[$i]','$arreglo7[$i]','$_POST[valor_totalh]','$arreglo1[$i]','Activo','$_POST[slct_anio_cfh]','$_POST[select_mesh]','$_SESSION[id]','$_POST[fecha_actualh]','$arreglo4[$i]')");
}
$data = $_POST['id_empleadoh'];

echo $data;
?>
