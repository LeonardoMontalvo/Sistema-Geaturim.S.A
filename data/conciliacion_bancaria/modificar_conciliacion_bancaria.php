<?php

session_start();
include '../../procesos/base.php';
conectarse();
//error_reporting(0);
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
conectarse();
$string = $_POST['ids'];
$string1 = explode(",", $string);
$array = explode(",", $string);
$varresult = count($array);

pg_query("DELETE FROM  detalle_conciliacion where id_conciliacion = '$_POST[comprobante]'");
// fin  

for ($i = 0; $i <= $nelem; $i++) {
    $cont2 = 0;
    $consulta = pg_query("select max(id_detalle_conciliacion) from detalle_conciliacion");
    while ($row = pg_fetch_row($consulta)) {
        $cont2 = $row[0];
    }
    $cont2++;
    if (!in_array($arreglo1[$i], $array)) {
        if (!empty($arreglo1[$i])) {

            $consultaw = pg_query("
SELECT id_detalle_conciliacion, id_conciliacion, id_transaccion, fecha_transaccion, 
       comprobante_movimiento, identificador, monto, concepto, banco, 
       total, estado
  FROM detalle_conciliacion where id_transaccion='$arreglo1[$i]'
");
            if (empty(pg_fetch_row($consultaw))) {

//                echo '<br>GUARDAR FACTURA INSERT: <br>' . "insert into detalle_conciliacion values('$cont2','$_POST[comprobante]','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','$arreglo6[$i]','','','Activo','$arreglo7[$i]')"; //////////////////////////
                pg_query("insert into detalle_conciliacion values('$cont2','$_POST[comprobante]','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','$arreglo6[$i]','','','Activo','$arreglo7[$i]')");
                $data = $_POST[comprobante];
            } else {
                $data = 0;
            }
        }
    }
}

echo $data;
?>
