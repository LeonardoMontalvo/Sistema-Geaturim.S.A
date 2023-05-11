<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

$sql00 = "select comprobante, identificador_cli_pro from transacciones
where id_transacciones=$_POST[id_transacciones]";
$res = pg_query($sql00);
$rows = pg_fetch_row($res);
if (!empty($rows[0])) {
    if ($rows[0] !== '00' && !sePuedeEliminarAsiento($rows[0], $rows[1])) {
        exit('<div style="text-align:left"><b><span style="color:red; margin-bottom:5px;">NO SE PUDO ELIMINAR EL ASIENTO</span>.<br>Este asiento debe ser eliminado desde el módulo en el que fue creado.<b></div>');
    }
}


$conpuntoresult = $_SESSION['PV'];
//////////////eliminar asiento contable///////////
pg_query("Update transacciones Set estado='Pasivo' where id_transacciones='$_POST[id_transacciones]'  and id_empresa='$conpuntoresult'");
$data = 1;


// Eliminar detalles de asientos
$consulta = pg_query("update detalle_transaccion where id_transacciones = '$_POST[id_transacciones]'  and id_empresa='$conpuntoresult'");
// fin resta
/////////////////////////////////
echo $data;

function sePuedeEliminarAsiento($comprobante, $identificador)
{
    switch ($identificador) {
            /*permitir eliminar asientos con identificador VEN de comprobantes
            que pertenescan a un centro de costos*/
        case 'VEN':
            $sql = "select*from detalle_centro_costos
            where tipo_documento='factura_venta'
            and id_documento=$comprobante";
            $res = pg_query($sql);
            $numrows = pg_num_rows($res);
            return $numrows > 0;
    }
}
