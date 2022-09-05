<?php

session_start();
include '../../procesos/base.php';

conectarse();
error_reporting(0);
date_default_timezone_set('America/Guayaquil');

$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];
$campo5 = $_POST['campo5'];
$campo6 = $_POST['campo6'];
$campo7 = $_POST['campo7'];

$campo8 = $_POST['campo8'];
$campo9 = $_POST['campo9'];
$campo10 = $_POST['campo10'];
$campo11 = $_POST['campo11'];
$campo12 = $_POST['campo12'];
$campo13 = $_POST['campo13'];
$campo14 = $_POST['campo14'];

$campo15 = $_POST['campo15'];
$campo16 = $_POST['campo16'];
$campo17 = $_POST['campo17'];
$campo18 = $_POST['campo18'];
$campo19 = $_POST['campo19'];

$cont1 = 0;
$consulta = pg_query("select max(id_rol_pagos) from rol_pagos");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;
$conpuntoresult = $_SESSION['PV'];
$nomina_mes = $_POST['nomina_mes'];
$conpunto = 0;




$consultapunto = pg_query("select * from rol_pagos where mes='$_POST[select_mes]' and anio='$_POST[slct_anio_cf]'");
while ($row = pg_fetch_row($consultapunto)) {
    $conpunto = $row[0];
}

if ($nomina_mes == 'on') {


    if ($conpunto == 0) {

//	 echo '<br>GUARDAR FACTURA VENTA: <br>' . "insert into rol_pagos values('$cont1','$_POST[fecha_actual]','','$conpuntoresult','$_POST[neto_recibirt]','Activo','$_SESSION[id]','$_POST[slct_anio_cf]','$_POST[select_mes]')";//////////////////////////
//	 
        $sql = "insert into rol_pagos values('$cont1','$_POST[fecha_actual]','','$conpuntoresult','$_POST[neto_recibirt]','Activo','$_SESSION[id]','$_POST[slct_anio_cf]','$_POST[select_mes]')";


        pg_query($sql);
// fin
// agregar detalle inventario
        $arreglo1 = explode('|', $campo1);
        $arreglo2 = explode('|', $campo2);
        $arreglo3 = explode('|', $campo3);
        $arreglo4 = explode('|', $campo4);
        $arreglo5 = explode('|', $campo5);
        $arreglo6 = explode('|', $campo6);
        $arreglo7 = explode('|', $campo7);


        $arreglo8 = explode('|', $campo8);
        $arreglo9 = explode('|', $campo9);
        $arreglo10 = explode('|', $campo10);
        $arreglo11 = explode('|', $campo11);
        $arreglo12 = explode('|', $campo12);
        $arreglo13 = explode('|', $campo13);
        $arreglo14 = explode('|', $campo14);
        $arreglo15 = explode('|', $campo15);
        $arreglo16 = explode('|', $campo16);
        $arreglo17 = explode('|', $campo17);
        $arreglo18 = explode('|', $campo18);
        $arreglo19 = explode('|', $campo19);

        $nelem = count($arreglo1);
// fin

        for ($i = 0; $i <= $nelem; $i++) {
            // contador detalle inventario
            $cont2 = 0;
            $consulta = pg_query("select max(id_detalle_rol) from detalle_rol");
            while ($row = pg_fetch_row($consulta)) {
                $cont2 = $row[0];
            }
            $cont2++;

// echo '<br>GUARDAR FACTURA VENTA: <br>' . "insert into detalle_rol values('$cont2','$cont1','$arreglo1[$i]','$arreglo2[$i]','',$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','$arreglo6[$i]','','$arreglo7[$i]','$arreglo8[$i]','$arreglo9[$i]','$arreglo10[$i]','$arreglo11[$i]','$arreglo12[$i]','$arreglo13[$i]','$arreglo14[$i]','','$arreglo15[$i]','$arreglo16[$i]','$arreglo17[$i]','$arreglo18[$i]','$arreglo19[$i]')";//////////////////////////
//	 
            pg_query("insert into detalle_rol values('$cont2','$cont1','$arreglo1[$i]','$arreglo2[$i]','','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','$arreglo6[$i]','','$arreglo7[$i]','$arreglo8[$i]','$arreglo9[$i]','$arreglo10[$i]','$arreglo11[$i]','$arreglo12[$i]','$arreglo13[$i]','$arreglo14[$i]','','$arreglo15[$i]','$arreglo16[$i]','$arreglo17[$i]','$arreglo18[$i]','$arreglo19[$i]')");
            pg_query("Update anticipos Set estado = 'Pasivo' where id_empleado = '$arreglo1[$i]' and anio = '$_POST[slct_anio_cf]' and mes = '$_POST[select_mes]'");
            pg_query("Update multas Set estado = 'Pasivo' where id_empleado = '$arreglo1[$i]' and anio = '$_POST[slct_anio_cf]' and mes = '$_POST[select_mes]'");

            $data = 1;
        }
    } else {
        $data = 11;
    }
} else {



    pg_query($sql);
// fin
// agregar detalle inventario
    $arreglo1 = explode('|', $campo1);
    $arreglo2 = explode('|', $campo2);
    $arreglo3 = explode('|', $campo3);
    $arreglo4 = explode('|', $campo4);
    $arreglo5 = explode('|', $campo5);
    $arreglo6 = explode('|', $campo6);
    $arreglo7 = explode('|', $campo7);


    $arreglo8 = explode('|', $campo8);
    $arreglo9 = explode('|', $campo9);
    $arreglo10 = explode('|', $campo10);
    $arreglo11 = explode('|', $campo11);
    $arreglo12 = explode('|', $campo12);
    $arreglo13 = explode('|', $campo13);
    $arreglo14 = explode('|', $campo14);
    $arreglo15 = explode('|', $campo15);
    $arreglo16 = explode('|', $campo16);
    $arreglo17 = explode('|', $campo17);
    $arreglo18 = explode('|', $campo18);
    $arreglo19 = explode('|', $campo19);

    $nelem = count($arreglo1);
// fin

    for ($i = 0; $i <= $nelem; $i++) {
        // contador detalle inventario
        $cont2 = 0;
        $consulta = pg_query("select max(id_detalle_rol) from detalle_rol");
        while ($row = pg_fetch_row($consulta)) {
            $cont2 = $row[0];
        }
        $cont2++;

// echo '<br>GUARDAR FACTURA VENTA: <br>' . "insert into detalle_rol values('$cont2','$cont1','$arreglo1[$i]','$arreglo2[$i]','',$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','$arreglo6[$i]','','$arreglo7[$i]','$arreglo8[$i]','$arreglo9[$i]','$arreglo10[$i]','$arreglo11[$i]','$arreglo12[$i]','$arreglo13[$i]','$arreglo14[$i]','','$arreglo15[$i]','$arreglo16[$i]','$arreglo17[$i]','$arreglo18[$i]','$arreglo19[$i]')";//////////////////////////
//	
        $conpunto1 = 0;
        $consultapunto = pg_query("select * from rol_pagos,detalle_rol where rol_pagos.id_rol_pagos=detalle_rol.id_rol_pagos and rol_pagos.mes='$_POST[select_mes]' and rol_pagos.anio='$_POST[slct_anio_cf]' and detalle_rol.id_empleado='$arreglo1[$i]'");
        while ($row = pg_fetch_row($consultapunto)) {
            $conpunto1 = $row[0];
        }
        if ($conpunto1 == 0) {
            pg_query("insert into detalle_rol values('$cont2','$_POST[id_rol]','$arreglo1[$i]','$arreglo2[$i]','','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','$arreglo6[$i]','','$arreglo7[$i]','$arreglo8[$i]','$arreglo9[$i]','$arreglo10[$i]','$arreglo11[$i]','$arreglo12[$i]','$arreglo13[$i]','$arreglo14[$i]','','$arreglo15[$i]','$arreglo16[$i]','$arreglo17[$i]','$arreglo18[$i]','$arreglo19[$i]')");
            pg_query("Update anticipos Set estado = 'Pasivo' where id_empleado = '$arreglo1[$i]' and anio = '$_POST[slct_anio_cf]' and mes = '$_POST[select_mes]'");
            pg_query("Update multas Set estado = 'Pasivo' where id_empleado = '$arreglo1[$i]' and anio = '$_POST[slct_anio_cf]' and mes = '$_POST[select_mes]'");

            $data = 1;
        }
    }
}




echo $data;
?>