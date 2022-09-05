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
//    	 echo '<br>GUARDAR FACTURA VENTA: <br>' . "DELETE FROM  multas where id_empleado = '$_POST[id_empleadom]' and mes='$_POST[select_mesm]' and anio='$_POST[slct_anio_cfm]'";//////////////////////////
//	 

pg_query("DELETE FROM  multas where id_empleado = '$_POST[id_empleadom]' and mes='$_POST[select_mesm]' and anio='$_POST[slct_anio_cfm]'");
// fin  

for ($i = 0; $i <= $nelem; $i++) {

    // contador detalle factura compra
    $cont4 = 0;
    $consulta = pg_query("select max(id_multa) from multas");
    while ($row = pg_fetch_row($consulta)) {
        $cont4 = $row[0];
    }
    $cont4++;
    // fin
//	 echo '<br>GUARDAR FACTURA VENTA: <br>' . "insert into multas values('$cont4','$arreglo1[$i]','$arreglo3[$i]','$arreglo4[$i]','$_POST[valor_totalm]','Activo','$arreglo2[$i]','$_SESSION[id]','$_POST[fecha_actual]')";//////////////////////////
//	


    pg_query("insert into multas values('$cont4','$arreglo1[$i]','$arreglo3[$i]','$arreglo4[$i]','$_POST[valor_totalm]','Activo','$arreglo2[$i]','$_POST[slct_anio_cfm]','$_POST[select_mesm]','$_SESSION[id]','$_POST[fecha_actualm]')");
}
$data = $_POST['id_empleadom'];

echo $data;
?>
