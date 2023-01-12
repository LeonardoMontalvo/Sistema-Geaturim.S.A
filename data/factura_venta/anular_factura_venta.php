<?php

session_start();
include '../../procesos/base.php';
require_once '../../procesos/detalleProductosBodega.php';
require_once '../../procesos/kardexValorizado.php';
conectarse();
error_reporting(0);
date_default_timezone_set('America/Guayaquil');
$horap = date("g:ia");
$conpunto = 1;
$conpuntoresult = $_SESSION['PV'];

if ($_POST["tipo_venta"] == "FACTURA") {
    // datos detalle factura
    $campo1 = $_POST['campo1'];
    $campo2 = $_POST['campo2'];

    // modificar estado factura venta
    pg_query("Update factura_venta Set estado = 'Pasivo', fecha_anulacion='$_POST[fecha_anulacion]' where id_factura_venta = '$_POST[comprobante]'");
    pg_query("Update pagos_venta Set estado = 'Pasivo' where id_factura_venta = '$_POST[comprobante]' and tipo_documento='Factura'");
    ////////////modificar cantidades////////
    $arreglo1 = explode('|', $campo1);
    $arreglo2 = explode('|', $campo2);
    $nelem = count($arreglo1);
    foreach (obtenerDetallaVenta($_POST['comprobante'], $conpuntoresult) as $item) {
        $cant=$item["cantidad_unidad"];

        $documento = 'Anulación F.V: ' . $item['num_serie'] . '-' . $item['num_factura'];
        $stock = obtenerStock($item['cod_productos'], $conpuntoresult);
        $total = number_format(($cant * $item['precio_venta']), 4, '.', '');
        updateKardex($_POST['comprobante'], $conpuntoresult, $item['cod_productos'], 'Inactivo', 'V', NULL, NULL);
        updateKardexValorizado($_POST['comprobante'], $conpuntoresult, $item['cod_productos'], 'Inactivo', 'V');
        $costoPromedio = obtenerCostoPromedioUnitarioAnular($item['cod_productos'], $conpuntoresult, $_POST['comprobante'], 'V');
        procesarKardexEntrada($item['cod_productos'], $documento, $cant, $stock, $costoPromedio, 'Activo', $conpuntoresult, 'AV', $_POST['comprobante'], $total, NULL, NULL, '', NULL, NULL, $item['id_cliente'], $_SESSION['id']);
    }
} else {
    if ($_POST["tipo_venta"] == "NOTA") {
        pg_query("Update facturas_novalidas Set estado = 'Pasivo' where id_facturas_novalidas = '$_POST[comprobante]'");
        pg_query("Update pagos_venta Set estado = 'Pasivo' where id_factura_venta = '$_POST[comprobante]' and tipo_documento='Nota'");
        foreach (obtenerDetallaNota($_POST['comprobante'], $conpuntoresult) as $item) {
            $cant=$item["cantidad_unidad"];

            $documento = 'Anulación N.V: ' . $item['comprobante'];
            $stock = obtenerStock($item['cod_productos'], $conpuntoresult);
            $total = number_format(($cant * $item['precio_venta']), 4, '.', '');
            updateKardex($_POST['comprobante'], $conpuntoresult, $item['cod_productos'], 'Inactivo', 'NV', NULL, NULL);
            updateKardexValorizado($_POST['comprobante'], $conpuntoresult, $item['cod_productos'], 'Inactivo', 'NV');
            $costoPromedio = obtenerCostoPromedioUnitarioAnular($item['cod_productos'], $conpuntoresult, $_POST['comprobante'], 'NV');
            procesarKardexEntrada($item['cod_productos'], $documento, $cant, $stock, $costoPromedio, 'Activo', $conpuntoresult, 'ANV', $_POST['comprobante'], $total, NULL, NULL, '', NULL, NULL, $item['id_cliente'], $_SESSION['id']);
        }
    }
}
$data = 1;
///////////////////////////////////////////
///////////ASIENTO CONTABLE ANULACION FACTURA
if ($_POST["tipo_venta"] == "FACTURA") {

    //    echo 'factura1::' . "update transacciones set estado='Pasivo' where comprobante='$_POST[comprobante]'  and id_empresa='" . $conpuntoresult . "' and identificador_cli_pro='VEN'  ";
    pg_query("update transacciones set estado='Pasivo' where comprobante='$_POST[comprobante]'  and id_empresa='" . $conpuntoresult . "' and identificador_cli_pro='VEN'  ");
}

///////////ASIENTO CONTABLE ANULACION NOTA DE VENTA
if ($_POST["tipo_venta"] == "NOTA") {


    //    echo 'nota::' . "update transacciones set estado='Pasivo' where comprobante='$_POST[comprobante]'  and id_empresa='" . $conpuntoresult . "' and identificador_cli_pro='NV' ";
    pg_query("update transacciones set estado='Pasivo' where comprobante='$_POST[comprobante]'  and id_empresa='" . $conpuntoresult . "' and identificador_cli_pro='NV' ");
}


//////////////////////////////////////////////////////////////////////////////
$retIva = pg_query("select id_retencion_iva_factura_venta from retencion_iva_factura_venta where id_factura='$_POST[comprobante]'");
$row1 = pg_fetch_row($retIva);
if ($row1[0] != "") {
    pg_query("update retencion_iva_factura_venta set estado='Pasivo' where id_retencion_iva_factura_venta=$row1[0]");
}

$retFuente = pg_query("select id_retencion_fuente_factura_venta from retencion_fuente_factura_venta where id_factura='$_POST[comprobante]'");
$row1 = pg_fetch_row($retFuente);
if ($row1[0] != "") {
    pg_query("update retencion_fuente_factura_venta set estado='Pasivo' where id_retencion_fuente_factura_venta=$row1[0]");
}
///////////////////////////////////////////
////////////////////////////////////////// 

echo $data;

function obtenerDetallaVenta($idFactura, $bodega)
{
    $sql = "SELECT * FROM detalle_factura_venta DFV INNER JOIN factura_venta FV ON FV.id_factura_venta = DFV.id_factura_venta "
        . "WHERE FV.id_factura_venta=$idFactura AND FV.id_empresa=$bodega";
    return pg_fetch_all(pg_query($sql));
}

function obtenerDetallaNota($idFactura, $bodega)
{
    $sql = "SELECT * FROM detalle_facturas_novalidas DFV INNER JOIN facturas_novalidas FV ON FV.id_facturas_novalidas = DFV.id_facturas_novalidas 
            WHERE FV.id_facturas_novalidas=$idFactura AND FV.id_empresa=$bodega";
    return pg_fetch_all(pg_query($sql));
}

function obtenerCantidadUnidadMedida($nombre)
{
    $sql = "select cantidad from unidades_medida
 where descripcion='$nombre'";
    $res = pg_query($sql);
    $rows=pg_fetch_all($res);
    if(empty($rows)){
        return 1;
    }
    return $rows[0]["cantidad"];
}
