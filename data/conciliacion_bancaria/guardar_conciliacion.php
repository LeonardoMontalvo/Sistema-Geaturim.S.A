<?php

session_start();
include '../../procesos/base.php';
require_once '../../procesos/kardexValorizado.php';
conectarse();
error_reporting(0);
$data = "";
$conpuntoresult = $_SESSION['PV'];

$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];
$campo5 = $_POST['campo5'];
$campo6 = $_POST['campo6'];
$campo7 = $_POST['campo7'];
$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);
$arreglo5 = explode('|', $campo5);
$arreglo6 = explode('|', $campo6);
$arreglo7 = explode('|', $campo7);
$nelem = count($arreglo1);
// fin
conectarse();
$string = $_POST['ids'];
$string1 = explode(",", $string);

$array = explode(",", $string);
$varresult = count($array);

$existencia_id = "";
$cont1 = 0;
$consulta = pg_query("select max(id_conciliacion) from conciliacion");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;
$var_consulta = "";
echo ':entro:';
for ($i = 0; $i <= $nelem; $i++) {
    $cont2 = 0;

    if (!in_array($arreglo1[$i], $array)) {
        if (!empty($arreglo1[$i])) {

//            echo '<br>GUARDAR FACTURA cabecera: <br>' . "insert into  conciliacion values('$cont1','$_SESSION[id]','$conpuntoresult','$cont1','$_POST[fecha_actual]','$_POST[hora_actual]'
//    ,'Activo','$_POST[observacion]','$_POST[id_plan]'
//    ,'$_POST[fecha_inicio]','$_POST[fecha_fin]','')"; 

            pg_query("insert into  conciliacion values('$cont1','$_SESSION[id]','$conpuntoresult','$cont1','$_POST[fecha_actual]','$_POST[hora_actual]'
    ,'Activo','$_POST[observacion]','$_POST[id_plan]'
    ,'$_POST[fecha_inicio]','$_POST[fecha_fin]','')");
            $data = $cont1;
        }
    }
}

for ($i = 0; $i <= $nelem; $i++) {
    $cont2 = 0;
    $consulta = pg_query("select max(id_detalle_conciliacion) from detalle_conciliacion");
    while ($row = pg_fetch_row($consulta)) {
        $cont2 = $row[0];
    }
    $cont2++;
    if (!in_array($arreglo1[$i], $array)) {
        if (!empty($arreglo1[$i])) {
            if ($arreglo5[$i] == '-') {
                $arreglo5[$i] = '0.000';
            }
            if ($arreglo7[$i] == '-') {
                $arreglo7[$i] = '0.000';
            }
            pg_query("insert into detalle_conciliacion values('$cont2','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','$arreglo6[$i]','','','Activo','$arreglo7[$i]')");
            $data = $cont1;
        }
    }
}

echo $data;
?>