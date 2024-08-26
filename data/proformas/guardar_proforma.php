<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
//
/////datos detalle factura/////
$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];
$campo5 = $_POST['campo5'];
$tarifas = $_POST['tarifas'];
$vlores_iva = $_POST['vlores_iva'];
$cods_impuesto = $_POST['cods_impuesto'];
$cods_tarifa = $_POST['cods_tarifa'];

/////////////////contador factura venta///////////
$cont1 = 0;
$consulta = pg_query("select max(id_proforma) from proforma");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;
//
 $conpunto = 1;
        $consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
         while ($row = pg_fetch_row($consultapunto)) {
            $conpunto = $row[0];
        }
        
                 $conpuntoresult = 1;
         $consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
         while ($row = pg_fetch_row($consultapuntoresult)) {
            $conpuntoresult = $row[0];
        }
////////////guardar proforma////////
$_POST["tarifa0"] = 0;
$_POST["tarifa12"] = 0;
pg_query("insert into proforma 
(id_proforma, id_cliente, id_usuario, id_empresa, comprobante, 
fecha_actual, hora_actual, tipo_precio, tarifa0, tarifa12, iva_proforma, 
descuento_proforma, total_proforma, observaciones, estado)
values('$cont1',
'$_POST[id_cliente]',
'$_SESSION[id]',
'$conpuntoresult',
'$cont1',
'$_POST[fecha_actual]',
'$_POST[hora_actual]',
'$_POST[tipo_precio]',
'$_POST[tarifa0]',
'$_POST[tarifa12]',
'$_POST[iva]',
'$_POST[desc]',
'$_POST[tot]',
'$_POST[observaciones]',
'Activo'
)");
////////////////////////////////////////
//
////////////agregar detalle_proforma////////
$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);
$arreglo5 = explode('|', $campo5);
$arreglotarifas = explode('|', $tarifas);
$arreglovlores_iva = explode('|', $vlores_iva);
$arreglocods_impuesto = explode('|', $cods_impuesto);
$arreglocods_tarifa = explode('|', $cods_tarifa);
$nelem = count($arreglo1);

///////////////////////////////////////////

for ($i = 1; $i <= $nelem; $i++) {
    /////////////////contador detalle factura compra/////////////
    $cont2 = 0;
    $consulta = pg_query("select max(id_detalle_proforma) from detalle_proforma");
    while ($row = pg_fetch_row($consulta)) {
        $cont2 = $row[0];
    }
    $cont2++;
    //////////////////////////  
    //
    ///guardar detalle_factura/////
    $insert = pg_query("insert into detalle_proforma values(
    '$cont2',
    '$cont1',
    '$arreglo1[$i]',
    '$arreglo2[$i]',
    '$arreglo3[$i]',
    '$arreglo4[$i]',
    '$arreglo5[$i]',
    'Activo')");
    ////////////////////////////////
    if (!empty($insert)) {
        guardarDetalleImpuestoProducto($arreglocods_impuesto[$i], $arreglocods_tarifa[$i], $arreglotarifas[$i], $arreglovlores_iva[$i], $arreglo5[$i], $cont2);
    }
}
$data = 1;
echo $data;


function guardarDetalleImpuestoProducto($codImpuesto, $codTarifa, $tarifa, $valoriva, $baseimponible, $iddetalle)
{
    $id = obtenerNextIdDetalleImpuestoProducto();
    $sql = "INSERT INTO detalle_impuesto_producto_proforma(
            id_detalle_impuesto_producto_proforma, cod_impuesto, cod_tarifa, 
            tarifa, valor_impuesto, base_imponible, id_detalle_proforma)
    VALUES ($id, '$codImpuesto', '$codTarifa', 
            $tarifa, $valoriva, $baseimponible,$iddetalle);
    ";
    $res = pg_query($sql);
}

function obtenerNextIdDetalleImpuestoProducto()
{
    $sql = "select coalesce(max(id_detalle_impuesto_producto_proforma),0)+1 from detalle_impuesto_producto_proforma";
    $res = pg_query($sql);
    $row = pg_fetch_row($res);
    return $row[0];
}
