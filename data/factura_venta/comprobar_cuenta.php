<?php

session_start();
include '../../procesos/base.php';
conectarse();

$data = 0;
$consulta = pg_query("SELECT id_pagos_venta, clientes.id_cliente, id_factura_venta, id_usuario, fecha_credito, 
       adelanto, meses, clientes.tipo_documento, monto_credito, saldo, clientes.estado, 
       fecha_dias, id_empresa
  FROM pagos_venta, clientes where clientes.id_cliente=pagos_venta.id_cliente and  clientes.identificacion='$_POST[prod]' and pagos_venta.estado='Activo'
");
$row = pg_fetch_row($consulta);
if ($row[10] == 'Activo') {
    $data = 1;
}

echo $data;
