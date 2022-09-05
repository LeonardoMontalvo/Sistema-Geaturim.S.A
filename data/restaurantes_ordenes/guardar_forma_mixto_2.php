<?php
session_start();
include '../../procesos/base.php';
include '../../procesos/funciones.php';
include_once '../../procesos/kardexValorizado.php';
require_once '../../procesos/detalleProductosBodega.php';

date_default_timezone_set('America/Guayaquil');
$conexion = conectarse();

function guardarFormasPagoMixto($idfactura, $formas, $tipoDoc)
{
    global $conexion, $fechaactual;
    foreach ($formas as $forma) {
        $id = obtenerIdFormaPagoMixto();
        $formap = $forma["formaPago"];
        $nrodoc = $forma["nroDoc"];
        $valor = $forma["valor"];
        $sql = "INSERT INTO formas_pago_mixto(
            id_formas_pago_mixto, id_factura_venta, fecha_actual, forma_pago, 
            tarjeta_credito, numero_documento, valor, estado, id_cuenta, 
            tipo_documento)
            VALUES ($id, $idfactura, '$fechaactual', '$formap', 
            null, '$nrodoc', '$valor', 'Activo', null, 
            '$tipoDoc');
            ";
        $res = pg_query($conexion, $sql);
        if (empty($res)) {
            return 0;
        }
    }
    return $idfactura;
}

function obtenerIdFormaPagoMixto()
{
    global $conexion;
    $sql = "select max(id_formas_pago_mixto) from formas_pago_mixto";
    $res = pg_query($conexion, $sql);
    if (pg_num_rows($res) > 0) {
        return pg_fetch_row($res)[0] + 1;
    }
    return 0;
}
