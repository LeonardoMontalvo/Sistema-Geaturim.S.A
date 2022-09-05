<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();
$identiciacion = "";
$nombres = "";

$conpunto = 1;
$consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($consultapunto)) {
    $conpunto = $row[0];
}
$conpuntoresult = 1;
$consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
while ($row = pg_fetch_row($consultapuntoresult)) {
    $conpuntoresult = $row[0];
}
$consulta = pg_query("select T.id_transacciones, U.nombre_usuario, U.apellido_usuario, T.fecha_actual, T.concepto, T.total_debe, T.total_haber, T.saldo, T.id_tipo_transaccion, T.num_transaccion, T.estado, T.id_cliente, T.deposito, T.observacion, T.num_cuenta, T.banco, T.valor_concepto, T.identificador_cli_pro,T.fecha_registro  from transacciones T, usuario U where T.id_usuario = U.id_usuario and T.id_tipo_transaccion = T.id_tipo_transaccion and T.id_transacciones='" . $id . "'  and  T.id_empresa='$conpuntoresult' and identificador_cli_pro <> 'AUD'");
if (pg_num_rows($consulta) > 0) {
    while ($row = pg_fetch_row($consulta)) {

        if ($row[17] == "VEN") {

            $consulta1 = pg_query("select identificacion , nombres_cli from clientes where id_cliente='" . $row[11] . "'");
            while ($rows = pg_fetch_row($consulta1)) {
                $identiciacion = $rows[0];
                $nombres = $rows[1];
            }
        }

        if ($row[17] == "COM" || $row[17] == "GAS") {
            $consulta1 = pg_query("select identificacion_pro , empresa_pro from proveedores where id_proveedor='" . $row[11] . "'");
            while ($rows = pg_fetch_row($consulta1)) {
                $identiciacion = $rows[0];
                $nombres = $rows[1];
            }
        }

        $arr_data[] = $row[0];
        $arr_data[] = $row[1] . " " . $row[2];
        $arr_data[] = $row[3];
        $arr_data[] = $row[4];
        $arr_data[] = $row[5];
        $arr_data[] = $row[6];
        $arr_data[] = $row[7];
        $arr_data[] = $row[8];
        $arr_data[] = $row[9];
        $arr_data[] = $row[10];
        $arr_data[] = $row[11];
        $arr_data[] = $row[12];
        $arr_data[] = $row[13];
        $arr_data[] = $row[14];
        $arr_data[] = $row[15];
        $arr_data[] = $row[16];
        $arr_data[] = $identiciacion;
        $arr_data[] = $nombres;
        $arr_data[] = $row[18];
    }
}
echo json_encode($arr_data);
?>
