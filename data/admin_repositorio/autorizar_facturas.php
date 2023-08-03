<?php
include_once __DIR__ . "/../../procesos/autorizacion_documentos/autorizar_factura.php";

echo json_encode(autorizar());

function autorizar()
{
    $noautorizadas = [];

    $facturas = buscarFacturasNoAutorizadas();
    foreach ($facturas as $value) {
        $res = autorizarFactura($value["id_factura_venta"], $value["clave"]);
        if ($res["estado"] != 2) {
            array_push($noautorizadas, $value["num_factura"]);
        }
    }
    return $noautorizadas;
}

function buscarFacturasNoAutorizadas()
{
    $SQL = "SELECT FV.id_factura_venta, FV.num_factura, FV.clave from factura_venta FV
    where FV.estado_fac::numeric<>1 and FV.estado_fac::numeric<>2 and FV.estado='Activo'";
    /////por fecha
    if (!empty($_GET['f1']) && !empty($_GET['f2'])) {
        $SQL .= " and FV.fecha_actual between '$_GET[f1]' and '$_GET[f2]'";
    }
    ////por cliente
    if (!empty($_GET['id'])) {
        $SQL .= " and C.id_cliente='$_GET[id]'";
    }
    $res = pg_query($SQL);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}
