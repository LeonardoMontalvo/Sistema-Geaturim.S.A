<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

/////datos series/////
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

///////////////////////////////////////////
for ($i = 1; $i < $nelem; $i++) {

    /////////////////contador serie venta/////////////
    $cont1 = 0;
    $consulta = pg_query("select max(id_cobro_anticipo_clientes) from cobro_anticipo_proveedores");
    while ($row = pg_fetch_row($consulta)) {
        $cont1 = $row[0];
    }
    $cont1++;
    //////////////////////////  
    //
    ///guardar series/////

    if ($arreglo4[$i] != "") {


//        echo '<br>GUARDAR FACTURA VENTARRQ: <br>' . "insert into cobro_anticipo_proveedores values('$cont1','" . strtoupper($arreglo2[$i]) . "','" . strtoupper($arreglo3[$i]) . "','" . strtoupper($arreglo4[$i]) . "', '1','$_SESSION[id]','$_POST[fecha_actual]','" . strtoupper($arreglo7[$i]) . "','" . strtoupper($arreglo5[$i]) . "','Activo')"; //////////////////////////

        pg_query("insert into cobro_anticipo_proveedores values('$cont1','" . strtoupper($arreglo2[$i]) . "','" . strtoupper($arreglo3[$i]) . "','" . strtoupper($arreglo4[$i]) . "', '1','$_SESSION[id]','$_POST[fecha_actual]','" . strtoupper($arreglo7[$i]) . "','" . strtoupper($arreglo5[$i]) . "','Activo')");
    } else {
        $idCli = 0;
        $consulta_cli = pg_query("select max(id_proveedor) from proveedores");
        while ($row = pg_fetch_row($consulta_cli)) {
            $idCli = $row[0];
        }
//        echo '<br>GUARDAR FACTURA VENTARRQ5: <br>' . "insert into cobro_anticipo_proveedores values('$cont1','" . strtoupper($arreglo2[$i]) . "','" . strtoupper($arreglo3[$i]) . "','$idCli', '1','$_SESSION[id]','$_POST[fecha_actual]','" . strtoupper($arreglo7[$i]) . "','" . strtoupper($arreglo5[$i]) . "','Activo','G')"; //////////////////////////

        pg_query("insert into cobro_anticipo_proveedores values('$cont1','" . strtoupper($arreglo2[$i]) . "','" . strtoupper($arreglo3[$i]) . "','$idCli', '1','$_SESSION[id]','$_POST[fecha_actual]','" . strtoupper($arreglo7[$i]) . "','" . strtoupper($arreglo5[$i]) . "','Activo','G')");
    }
    
     pg_query("update anticipo_proveedores set estado='Pasivo' where id_anticipo_proveedores='$arreglo2[$i]'  ");


////////////////////////////////
    ///////////////////modificar series////////
    ////////////////////////////////////////////
}
$data = 1;
echo $data;
?>
