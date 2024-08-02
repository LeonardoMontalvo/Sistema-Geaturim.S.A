<?php
session_start();
include '../../procesos/base.php';

echo adelante();

function adelante()
{
    $resp = "";
    $contador = 0;

    $sql = pg_query("select min(comprobante::integer) from  pagos_pagar");
    while ($row = pg_fetch_row($sql)) {
        $contador = $row[0];
    }

    $sql = pg_query("select comprobante from pagos_pagar where comprobante::integer not BETWEEN " . $contador . " and " . $_POST['comprobante'] . " and id_empresa= '$_SESSION[PV]' order by comprobante asc  limit 1;");
    while ($row = pg_fetch_row($sql)) {
        $resp = $row[0];
    }
    return $resp;
}
