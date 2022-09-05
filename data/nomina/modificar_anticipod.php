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
$campo8 = $_POST['campo8'];
$campo9 = $_POST['campo9'];

// fin 
// agregar 
$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);
$arreglo5 = explode('|', $campo5);
$arreglo6 = explode('|', $campo6);
$arreglo7 = explode('|', $campo7);
$arreglo8 = explode('|', $campo8);
$arreglo9 = explode('|', $campo9);

$nelem = count($arreglo1);
// eliminar detalle productos
//    	 echo '<br>GUARDAR FACTURA VENTA: <br>' . "DELETE FROM  multas where id_empleado = '$_POST[id_empleadom]' and mes='$_POST[select_mesm]' and anio='$_POST[slct_anio_cfm]'";//////////////////////////
//	 

pg_query("DELETE FROM  decimos where id_decimos = '$_POST[id_empleados]' and mes='$_POST[select_mess]' and anio='$_POST[slct_anio_cfs]'");
// fin  

for ($i = 0; $i <= $nelem; $i++) {

    // contador detalle factura compra
    $cont4 = 0;
    $consulta = pg_query("select max(id_decimos) from decimos");
    while ($row = pg_fetch_row($consulta)) {
        $cont4 = $row[0];
    }
    $cont4++;
    // fin
//	 echo '<br>GUARDAR FACTURA VENTA: <br>' . "insert into multas values('$cont4','$arreglo1[$i]','$arreglo3[$i]','$arreglo4[$i]','$_POST[valor_totalm]','Activo','$arreglo2[$i]','$_SESSION[id]','$_POST[fecha_actual]')";//////////////////////////
//	


     pg_query("insert into decimos values('$cont2','$_POST[fecha_actual]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','$arreglo6[$i]','$arreglo9[$i]','$arreglo7[$i]','$arreglo8[$i]','$arreglo1[$i]','Activo','$_POST[slct_anio_cfs]','$_POST[select_mess]','$_SESSION[id]','$_POST[fecha_actual]')"); 
}
$data = $_POST['id_empleadom'];

echo $data;
?>
