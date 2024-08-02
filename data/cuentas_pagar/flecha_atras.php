<?php
session_start();
include '../../procesos/base.php';

echo atras();
function atras()
{
    $resp = "";
    $contador = 0;
    $sql = pg_query("select max(comprobante::integer) + 1 from  pagos_pagar");
    while ($row = pg_fetch_row($sql)) {
        $contador = $row[0];
    }
    $sql = pg_query("select comprobante from pagos_pagar where comprobante::integer not BETWEEN " . $_POST['comprobante'] . " and " . $contador . " and id_empresa= '$_SESSION[PV]' order by comprobante desc  limit 1;");
    while ($row = pg_fetch_row($sql)) {
        $resp = $row[0];
    }
    return $resp;
}
