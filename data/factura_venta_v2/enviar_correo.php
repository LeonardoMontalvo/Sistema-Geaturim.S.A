<?php
session_start();
include '../../procesos/base.php';
include '../../admin/correo.php';
include __DIR__.'/../factura_venta/generarPDF.php';
require_once __DIR__ . '/../../procesos/configuracion.php';
error_reporting(0);

$conf = new Configuracion();
$pathXmls = $conf->getPathXmlsFirma();



if (isset($_POST['reenviarcorreo']) == "reenviarcorreo") {
    $resultado = pg_query("SELECT C.correo, C.nombres_cli, F.total_venta ,F.num_autorizacion, F.fecha_actual  FROM factura_venta F, clientes C "
            . "WHERE F.id_cliente = C.id_cliente AND F.id_factura_venta= '" . $_POST['id'] . "'");
    while ($row = pg_fetch_row($resultado)) {
        $email = $row[0];
        $nombre = $row[1];
        $total = $row[2];
        $num_autorizacion = $row[3];
        $fecha = $row[4];
    }

    $total_venta_tot = 0;
    $total_venta_tot = round($total, 2);
    $data = correo($fecha, $total_venta_tot, $pathXmls . $num_autorizacion . '.xml', $num_autorizacion . '.pdf', $nombre, $email, $pathXmls . $num_autorizacion . '.xml', generarPDFcorreo($_POST['id']), 1);
    if ($data == 1) {
        $resultado = pg_query("UPDATE factura_venta set estado_fac = '1' where id_factura_venta = '" . $_POST['id'] . "'");
        if ($resultado) {
            $data = 1; // datos actualizados
        } else {
            $data = 4; // error al momento de guadar
        }
    }
    $item = array(
        'estado' => $data,
        'id' => $_POST['id']
    );
}