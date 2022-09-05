<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
require_once '../../procesos/kardexValorizado.php';
require_once '../../procesos/detalleProductosBodega.php';
conectarse();
error_reporting(0);
$bodega = $_SESSION['PV'];
$docu = str_pad($_POST['comprobante'], 9, "0", STR_PAD_LEFT);
$detalle = obtenerDetalleIngreso($_POST['comprobante'], $bodega);

foreach ($detalle as $key) {
    $documento = "Anulación T.I: " . $docu;
    $stock = obtenerStock($key['cod_productos'], $_SESSION['PV']);
    $costoPromedio = obtenerCostoPromedioUnitarioAnular($key['cod_productos'], $bodega, $key['comprobante'], 'I');
    updateKardex($key['comprobante'], $bodega, $key['cod_productos'], 'Inactivo', 'I', NULL, NULL);
    updateKardexValorizado($key['comprobante'], $bodega, $key['cod_productos'], 'Inactivo', 'I');
    procesarKardexSalida($key['cod_productos'], $documento, $key['cantidad'], $stock, $costoPromedio, 'Activo', $bodega, 'AI', 
            $key['comprobante'], $key['total'], NULL, NULL, NULL, $_POST['anulacionComentario'], NULL, NULL, NULL);
}

anularIngrso($_POST['comprobante'], $bodega);
echo "1";

function obtenerDetalleIngreso($ingreso, $bodega) {
    $sql = "SELECT DI.cod_productos,DI.cantidad, DI.precio_costo, DI.total, I.comprobante, I.observaciones "
            . "FROM ingresos I INNER JOIN detalle_ingreso DI ON I.id_ingresos = DI.id_ingresos "
            . "WHERE I.id_ingresos=$ingreso AND I.id_empresa=$bodega AND I.estado='Activo' ";
    $row = pg_fetch_all(pg_query($sql));
    return $row;
}

function anularIngrso($ingreso, $bodega) {
    $sql = "UPDATE ingresos SET estado='Pasivo' WHERE id_ingresos=$ingreso AND id_empresa=$bodega";
    pg_query($sql);
}
