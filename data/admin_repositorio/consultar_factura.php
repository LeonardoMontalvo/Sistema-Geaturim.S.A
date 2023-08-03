<?php
include_once __DIR__ . "/../../procesos/autorizacion_documentos/autorizar_factura.php";

if (empty($_POST["op"]) || empty($_POST["id_factura"])) {
    exit();
}
$factura = buscarFactura($_POST["id_factura"]);
$operacion = $_POST["op"];
switch ($operacion) {
    case "enviar":
        if (!empty($factura)) {
            $res = autorizarFactura($factura["id_factura_venta"], $factura["clave"]);
            echo json_encode($res);
        }
        break;
    case "consultar":
        if (!empty($factura)) {
            $res = autorizarFactura($factura["id_factura_venta"], $factura["clave"], false);
            echo json_encode($res);
        }
        break;
}

function buscarFactura($idfactura)
{
    $sql = "select id_factura_venta,clave from factura_venta where id_factura_venta=$idfactura";
    $res = pg_query($sql);
    $row = pg_fetch_assoc($res);
    if (empty($res)) {
        return [];
    }
    return $row;
}
