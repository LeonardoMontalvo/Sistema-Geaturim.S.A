<?php
include_once __DIR__ . "/autorizar_factura.php";

$prefix = "est_";
$dir = "tmp/";
$fecha = date("Y-m-d");
$filename = $dir . $prefix . $fecha . ".tmp";
$logfile = __DIR__ . "/../../logs/autorizar_facturas_automatico.log";

$autorizarAutomatico = $conf->getParametroEmpresa("autorizar_fac_auto");
if ($autorizarAutomatico == '1') {
    autorizar();
}

function autorizar()
{
    global $filename, $logfile, $fecha;
    if (!existeFile($filename)) {
        borrarFiles();
        crearFile($filename);


        $noautorizadas = [];
        $facturas = buscarFacturasNoAutorizadas($fecha);
        foreach ($facturas as $value) {
            $res = autorizarFactura($value["id_factura_venta"], $value["clave"]);
            if ($res["estado"] != 2) {
                array_push($noautorizadas, $value["num_factura"]);
            }
        }
        $msg = "no autorizadas " . json_encode($noautorizadas);
        sis_error_log_file("--", $msg, "autorizar_facturas_ayer.php", "--", $logfile);
    }
}

function crearFile($filename)
{
    $myfile = fopen($filename, "w");
    fclose($myfile);
}
function existeFile($filename)
{
    return file_exists($filename);
}
function borrarFiles()
{
    global $dir, $prefix, $logfile;
    $files = glob("$dir*"); // get all file names
    foreach ($files as $file) { // iterate files
        if (strpos($file, $prefix)) {
            if (is_file($file)) {
                unlink($file); // delete file
            }
        }
    }
    unlink($logfile);
}

function buscarFacturasNoAutorizadas($fecha)
{
    $fechaaterior = date("Y-m-d", strtotime($fecha . " -1 days"));
    $sql = "select id_factura_venta, clave, num_factura
    from factura_venta 
    where estado_fac::numeric<>1 
    and estado_fac::numeric<>2
    and estado='Activo'
    and fecha_actual::date between '$fechaaterior' and '$fecha';";
    $res = pg_query($sql);
    if (empty($res)) {
        return [];
    } else {
        return pg_fetch_all($res);
    }
}
