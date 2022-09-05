<?php
date_default_timezone_set('America/Guayaquil');

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

function guardarCabecera()
{
    /////////////////punto de venta///////////////////
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

    /////////////////contador factura venta///////////
    $cont1 = 0;
    $consulta = pg_query("select max(id_proforma) from proforma_tecnico");
    while ($row = pg_fetch_row($consulta)) {
        $cont1 = $row[0];
    }
    $cont1++;

    ////////////guardar proforma////////
    $fecha = date("Y-m-d H:i:s");
    $sql = "
    insert into proforma_tecnico values($cont1,
    '$_POST[id_cliente]',
    '$_SESSION[id]',
    '$conpuntoresult',
    '$cont1',
    '$_POST[fecha_actual]',
    '$_POST[hora_actual]',
    '$_POST[tipo_precio]',
    '$_POST[tarifa0]',
    '$_POST[tarifa12]',
    '$_POST[iva]',
    '$_POST[desc]',
    '$_POST[tot]',
    '$_POST[observacionesp]',
    'Activo',
    '$_POST[datos]',
    $_POST[id_registro],
    '$fecha',NULL,NULL,$_POST[tipo_entrega],$_POST[id_vendedor])
    ";
    $res = pg_query($sql);
    if (!$res) {
        return null;
    }
    return $cont1;
}

function guardarDetalles($idprof)
{
    /////datos detalle factura/////
    $campo1 = $_POST['campo1'];
    $campo2 = $_POST['campo2'];
    $campo3 = $_POST['campo3'];
    $campo4 = $_POST['campo4'];
    $campo5 = $_POST['campo5'];
    ////////////agregar detalle_proforma////////
    $arreglo1 = explode('|', $campo1);
    $arreglo2 = explode('|', $campo2);
    $arreglo3 = explode('|', $campo3);
    $arreglo4 = explode('|', $campo4);
    $arreglo5 = explode('|', $campo5);

    unset($arreglo1[0]);
    unset($arreglo2[0]);
    unset($arreglo3[0]);
    unset($arreglo4[0]);
    unset($arreglo5[0]);

    $nelem = count($arreglo1);

    foreach ($arreglo1 as $key => $value) {
        $detalle = [
            $idprof,
            $arreglo1[$key],
            $arreglo2[$key],
            $arreglo3[$key],
            $arreglo4[$key],
            $arreglo5[$key],
        ];

        $detalle = guardarDetalle($detalle);
        if (!is_numeric($detalle)) {
            return null;
        }
    }

    return $nelem;
}

function guardarDetalle($detalle)
{
    $cont2 = 0;
    $consulta = pg_query("select max(id_detalle_proforma) from detalle_proforma_tecnico");
    while ($row = pg_fetch_row($consulta)) {
        $cont2 = $row[0];
    }
    $cont2++;

    $sql = "insert into detalle_proforma_tecnico values($cont2,
    $detalle[0],
    $detalle[1],
    $detalle[2],
    $detalle[3],
    $detalle[4],$detalle[5],'Activo')";
    $res = pg_query($sql);
    if (!$res) {
        return null;
    }
    return $cont2;
}

function transaccionGuardar()
{
    pg_query("begin");
    $cabecera = guardarCabecera();
    if (is_numeric($cabecera)) {
        $detalles = guardarDetalles($cabecera);
        if (is_numeric($detalles)) {
            pg_query("commit");
            return $cabecera;
        }
    }
    pg_query("rollback");
    return 0;
}

$transaccion=transaccionGuardar();

echo json_encode($transaccion);
