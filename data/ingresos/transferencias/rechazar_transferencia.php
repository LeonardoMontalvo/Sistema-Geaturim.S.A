<?php

session_start();

include_once __DIR__ . '/../../../procesos/base.php';
require_once __DIR__ . '/../../../procesos/fecha.php';
require_once __DIR__ . '/../../../procesos/kardexValorizado.php';
require_once __DIR__ . '/../../../procesos/detalleProductosBodega.php';
//error_reporting(0);

date_default_timezone_set('America/Guayaquil');

$conexion = conectarse();

pg_query("BEGIN");
echo rechazarTransferencia($_POST["id"], $_SESSION["id"]);
pg_query("COMMIT");

function rechazarTransferencia($idtransferencia, $usuario) {
    $trans = obtenerTransferencia($idtransferencia);

    if ($trans["estado"] == 'Pasivo') {
        return "La transferencia fue anulada.";
    }

    guardarEntradaKardex($idtransferencia, $usuario);
    $aegreso = anularEgreso($trans["id_egreso"]);
    $anular_egre = anularasiento($trans["id_egreso"]);
    if (empty($aegreso)) {
        return "Error al anular egreso.";
    }
//    if (empty($anular_egre)) {
//        return "Error al anular egreso.";
//    }
    $cabmiare = cambiarEstadoTransferncia($idtransferencia, $usuario);
    if (empty($cabmiare)) {
        return "Error al cambiar estado de transferencia.";
    }
    return $idtransferencia;
}

function cambiarEstadoTransferncia($idtransferencia, $usuario) {
    global $conexion;
    $sql = "
    update transferencias_bodega 
    set estado_transferencia='rechazado',
    fecha_modificacion ='" . date("Y-m-d") . "',
    id_usuario_destino =$usuario
    where  id_transferencia_bodega=$idtransferencia
    ";
    $res = pg_query($conexion, $sql);
    if (!$res) {
        return null;
    }
    return $idtransferencia;
}

function obtenerTransferencia($idtransferencia) {
    global $conexion;
    $sql = "select*from transferencias_bodega where id_transferencia_bodega=$idtransferencia";
    $res = pg_query($conexion, $sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows[0];
}

function guardarEntradaKardex($idtransferencia, $usuario) {
    $tras = obtenerTransferencia($idtransferencia);
    $dets = obtenerDetallesEgreso($tras["id_egreso"]);
    foreach ($dets as $value) {
        $costoPromedio = obtenerCostoPromedioUnitario($value["cod_productos"], $tras['id_bodega_origen'])[0]['costo_prom_unitario'];
        procesarKardexEntrada($value["cod_productos"], "T.I: T. Rechazada - " . $tras['comprobante'], $value["cantidad"], obtenerStock($value["cod_productos"], $tras["id_bodega_origen"]), $costoPromedio, 'Activo', $tras["id_bodega_origen"], 'TEI', $idtransferencia, NULL, $tras['id_bodega_origen'], $tras['id_bodega_destino'], '', NULL, NULL, NULL, $usuario);
    }
}

function obtenerDetallesEgreso($idegreso) {
    global $conexion;
    $sql = "
    select * from detalle_egreso  
    where id_egresos=$idegreso
    ";
    $res = pg_query($conexion, $sql);
    $rows = pg_fetch_all($res);
    if (!$res) {
        return [];
    }
    return $rows;
}

function anularEgreso($idegreso) {
    global $conexion;
    $sql = "
    update egresos
    set estado='Pasivo' 
    where id_egresos=$idegreso
    ";
    $res = pg_query($conexion, $sql);
    if (!$res) {
        return null;
    }
    return $idegreso;
}

function anularasiento($idegreso) {
    $conexion = conectarse();
      global $conexion;

 $asiento_row = '';
$consultapuntoresult = pg_query("select id_transacciones from transacciones where comprobante='$idegreso' and concepto like '%EGRE%'");
  while ($row = pg_fetch_row($consultapuntoresult)) {
            $asiento_row = $row[0];
        }
      

     if ($asiento_row!="") {

//        echo 'ff' . "update transacciones set estado='Pasivo' where id_transacciones=$asiento_row";
        pg_query("update transacciones set estado='Pasivo' where id_transacciones=$asiento_row");
        pg_query("update detalle_transaccion set estado='Pasivo' where id_transacciones=$asiento_row ");
          return null;
    }
}
