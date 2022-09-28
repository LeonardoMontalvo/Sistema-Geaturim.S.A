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
    pg_query("Update pagos_venta Set estado = 'Pasivo' where id_factura_venta = '$_POST[comprobante]'");
    ////////////modificar cantidades////////
    $arreglo1 = explode('|', $campo1);
    $arreglo2 = explode('|', $campo2);
    $nelem = count($arreglo1);
    foreach (obtenerDetallaVenta($_POST['comprobante'], $conpuntoresult) as $item) {
        $documento = 'Anulación F.V: ' . $item['num_serie'] . '-' . $item['num_factura'];
        $stock = obtenerStock($item['cod_productos'], $conpuntoresult);
        $total = number_format(($item['cantidad'] * $item['precio_venta']), 4, '.', '');
        updateKardex($_POST['comprobante'], $conpuntoresult, $item['cod_productos'], 'Inactivo', 'V', NULL, NULL);
        updateKardexValorizado($_POST['comprobante'], $conpuntoresult, $item['cod_productos'], 'Inactivo', 'V');
        $costoPromedio = obtenerCostoPromedioUnitarioAnular($item['cod_productos'],$_POST['comprobante'], $conpuntoresult,'V');
        procesarKardexEntrada($item['cod_productos'], $documento, $item['cantidad'], $stock, $costoPromedio, 'Activo', 
                $conpuntoresult, 'AV', $_POST['comprobante'], $total, NULL, NULL, '', NULL, NULL, $item['id_cliente'], $_SESSION['id']);
    }
} else {
    if ($_POST["tipo_venta"] == "NOTA") {
        pg_query("Update facturas_novalidas Set estado = 'Pasivo' where id_facturas_novalidas = '$_POST[comprobante]'");
        foreach (obtenerDetallaNota($_POST['comprobante'], $conpuntoresult) as $item) {
            $documento = 'Anulación N.V: ' . $item['comprobante'];
            $stock = obtenerStock($item['cod_productos'], $conpuntoresult);
            $total = number_format(($item['cantidad'] * $item['precio_venta']), 4, '.', '');
            updateKardex($_POST['comprobante'], $conpuntoresult, $item['cod_productos'], 'Inactivo', 'NV', NULL, NULL);
            updateKardexValorizado($_POST['comprobante'], $conpuntoresult, $item['cod_productos'], 'Inactivo', 'NV');
            $costoPromedio = obtenerCostoPromedioUnitarioAnular($item['cod_productos'],$_POST['comprobante'], $conpuntoresult,'NV');
            procesarKardexEntrada($item['cod_productos'],$documento,$item['cantidad'], $stock,$costoPromedio,'Activo', 
                    $conpuntoresult,'ANV',$_POST['comprobante'],$total,NULL,NULL,'', NULL,NULL, $item['id_cliente'], $_SESSION['id'] );
        }
    }
}
$data = 1;
///////////////////////////////////////////
///////////ASIENTO CONTABLE

echo 'ANULACION VENTA1'."select id_transacciones from transacciones where comprobante='$_POST[comprobante]' and concepto like 'VENTA%'";
$asiento = pg_query("select id_transacciones from transacciones where comprobante='$_POST[comprobante]' and concepto like 'VENTA%'");
$row = pg_fetch_row($asiento);
if ($row[0] != "") {
    pg_query("update transacciones set estado='Pasivo' where id_transacciones=$row[0]  and id_empresa='" . $conpuntoresult . "' ");
    pg_query("update detalle_transaccion set estado='Pasivo' where id_transacciones=$row[0] ");
}
echo 'ANULACION VENTA2'."select id_transacciones from transacciones where comprobante='$_POST[comprobante]' and concepto like 'VENTA%'";
$asiento_nota = pg_query("select id_transacciones from transacciones where comprobante='$_POST[comprobante]' and concepto like 'COSTO%'");
$row = pg_fetch_row($asiento_nota);
if ($row[0] != "") {
    echo 'ANULACION DETALLE1'."update transacciones set estado='Pasivo' where id_transacciones=$row[0]  and id_empresa='" . $conpuntoresult . "' ";
    pg_query("update transacciones set estado='Pasivo' where id_transacciones=$row[0]  and id_empresa='" . $conpuntoresult . "' ");
    echo 'ANULACION DETALLE2'."update detalle_transaccion set estado='Pasivo' where id_transacciones=$row[0] ";
    pg_query("update detalle_transaccion set estado='Pasivo' where id_transacciones=$row[0] ");
}

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

function obtenerDetallaVenta($idFactura, $bodega) {
    $sql = "SELECT * FROM detalle_factura_venta DFV INNER JOIN factura_venta FV ON FV.id_factura_venta = DFV.id_factura_venta "
            . "WHERE FV.id_factura_venta=$idFactura AND FV.id_empresa=$bodega";
    return pg_fetch_all(pg_query($sql));
}
function obtenerDetallaNota($idFactura, $bodega) {
    $sql = "SELECT * FROM detalle_facturas_novalidas DFV INNER JOIN facturas_novalidas FV ON FV.id_facturas_novalidas = DFV.id_facturas_novalidas 
            WHERE FV.id_facturas_novalidas=$idFactura AND FV.id_empresa=$bodega";
    return pg_fetch_all(pg_query($sql));
}


?>