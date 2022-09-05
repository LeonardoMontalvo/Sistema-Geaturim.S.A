<?php
date_default_timezone_set('America/Guayaquil');

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

function modificarCabecera()
{

    $id = $_POST["comprobante"];
    $idusuario = $_SESSION["id"];
    $idcliente = $_POST["id_cliente"];
    $fechaa = date("Y-m-d");
    $horaa = date("H:i:s");
    $tipop = $_POST["tipo_precio"];
    $tarifa0 = $_POST["tarifa0"];
    $tarifa12 = $_POST["tarifa12"];
    $iva = $_POST["iva"];
    $descuento = $_POST["desc"];
    $total = $_POST["tot"];
    $obs = $_POST["observacionesp"];
    $datos = $_POST["datos"];
    $idregistro = $_POST["id_registro"];
    
    
    
    echo ''."UPDATE proforma_tecnico
    SET 
    id_cliente=$idcliente,
    id_usuario=$idusuario,
    fecha_actual='$fechaa',
    hora_actual='$horaa',
    tipo_precio='$tipop',
    tarifa0=$tarifa0,
    tarifa12=$tarifa12,
    iva_proforma=$iva,
    descuento_proforma=$descuento,
    total_proforma=$total,
    observaciones='$obs',
    datos='$datos',
    id_registro=$idregistro
    WHERE id_proforma=$id
    ";

    $sql = "UPDATE proforma_tecnico
    SET 
    id_cliente=$idcliente,
    id_usuario=$idusuario,
    fecha_actual='$fechaa',
    hora_actual='$horaa',
    tipo_precio='$tipop',
    tarifa0=$tarifa0,
    tarifa12=$tarifa12,
    iva_proforma=$iva,
    descuento_proforma=$descuento,
    total_proforma=$total,
    observaciones='$obs',
    datos='$datos',
    id_registro=$idregistro
    WHERE id_proforma=$id
    ";
    $res = pg_query($sql);
    if (!$res) {
        return null;
    }
    return $id;
}

function modificarDetalles($idprof)
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

    $sqldel = "delete from detalle_proforma_tecnico where id_proforma=$idprof";
    $resdel = pg_query($sqldel);

    foreach ($arreglo1 as $key => $value) {
        $detalle = [
            $idprof,
            $arreglo1[$key],
            $arreglo2[$key],
            $arreglo3[$key],
            $arreglo4[$key],
            $arreglo5[$key],
        ];

        $detalle = modificarDetalle($detalle);
        if (!is_numeric($detalle)) {
            return null;
        }
    }

    return $nelem;
}

function modificarDetalle($detalle)
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

function transaccionModificar()
{
    pg_query("begin");
    $cabecera = modificarCabecera();
    if (is_numeric($cabecera)) {
        $detalles = modificarDetalles($cabecera);
        if (is_numeric($detalles)) {
            pg_query("commit");
            return $cabecera;
        }
    }
    pg_query("rollback");
    return 0;
}

$transaccion=transaccionModificar();

echo json_encode($transaccion);