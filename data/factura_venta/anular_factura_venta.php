<?php

session_start();
include '../../procesos/base.php';
require_once '../../procesos/detalleProductosBodega.php';
require_once '../../procesos/kardexValorizado.php';
conectarse();
error_reporting(0);
date_default_timezone_set('America/Guayaquil');
$horap = date("g:ia");
$conpunto = 1;
$conpuntoresult = $_SESSION['PV'];
$var_comprobante = $_POST['comprobante'];
$var_comprobante_antnv = $_POST['comprobante_antnv'];




/////////////////
//echo '//' . $var_comprobante_antnv;
if ($var_comprobante_antnv != "") {

  
        pg_query("Update facturas_novalidas Set estado = 'Factura' where id_facturas_novalidas = '$_POST[comprobante_antnv]'");
        pg_query("Update pagos_venta Set estado = 'Pasivo' where id_factura_venta = '$_POST[comprobante_antnv]' and tipo_documento='Nota'");
        foreach (obtenerDetallaNota($_POST['comprobante_antnv'], $conpuntoresult) as $item) {
            $cant = $item["cantidad_unidad"];
            $cantidad = $item["cantidad"];
            if ($cant != 0) {
                $cantidad = $cant;
            } else {
                $cantidad = $cantidad;
            }
            $documento = 'Cambio a Factura N.V: ' . $_POST['comprobante_antnv'];
            $stock = obtenerStock($item['cod_productos'], $conpuntoresult);
            $total = number_format(($cantidad * $item['precio_venta']), 4, '.', '');
            updateKardex($_POST['comprobante_antnv'], $conpuntoresult, $item['cod_productos'], 'Inactivo', 'CNV', NULL, NULL);
            updateKardexValorizado($_POST['comprobante_antnv'], $conpuntoresult, $item['cod_productos'], 'Inactivo', 'CNV');
            $costoPromedio = obtenerCostoPromedioUnitarioAnular($item['cod_productos'], $conpuntoresult, $_POST['comprobante_antnv'], 'CNV');
            procesarKardexEntrada($item['cod_productos'], $documento, $cantidad, $stock, $costoPromedio, 'Activo', $conpuntoresult, 'CNV', $_POST['comprobante_antnv'], $total, NULL, NULL, '', NULL, NULL, $item['id_cliente'], $_SESSION['id']);
        }
    
} else {




    if ($_POST["tipo_venta"] == "FACTURA") {
        // datos detalle factura
        $campo1 = $_POST['campo1'];
        $campo2 = $_POST['campo2'];

        // modificar estado factura venta
        pg_query("Update factura_venta Set estado = 'Pasivo', fecha_anulacion='$_POST[fecha_anulacion]' where id_factura_venta = '$_POST[comprobante]'");
        pg_query("Update pagos_venta Set estado = 'Pasivo' where id_factura_venta = '$_POST[comprobante]' and tipo_documento='Factura'");
        //    echo '::'."Update pagos_cobrar Set estado = 'Pasivo' where num_factura = '$_POST[num_factura]' and tipo_documento='Factura'";
        pg_query("Update pagos_cobrar Set estado = 'Pasivo' where num_factura = '$_POST[num_factura]' and tipo_factura='Factura'");


        ////////////modificar cantidades////////
        $arreglo1 = explode('|', $campo1);
        $arreglo2 = explode('|', $campo2);
        $nelem = count($arreglo1);
        foreach (obtenerDetallaVenta($_POST['comprobante'], $conpuntoresult) as $item) {
            $cant = $item["cantidad_unidad"];
            $cantidad = $item["cantidad"];
            if ($cant != 0) {
                $cantidad = $cant;
            } else {
                $cantidad = $cantidad;
            }

            $documento = 'Anulación F.V: ' . $item['num_serie'] . '-' . $item['num_factura'];
            $stock = obtenerStock($item['cod_productos'], $conpuntoresult);
            $total = number_format(($cantidad * $item['precio_venta']), 4, '.', '');
            updateKardex($_POST['comprobante'], $conpuntoresult, $item['cod_productos'], 'Inactivo', 'V', NULL, NULL);
            updateKardexValorizado($_POST['comprobante'], $conpuntoresult, $item['cod_productos'], 'Inactivo', 'V');
            $costoPromedio = obtenerCostoPromedioUnitarioAnular($item['cod_productos'], $conpuntoresult, $_POST['comprobante'], 'V');
            procesarKardexEntrada($item['cod_productos'], $documento, $cantidad, $stock, $costoPromedio, 'Activo', $conpuntoresult, 'AV', $_POST['comprobante'], $total, NULL, NULL, '', NULL, NULL, $item['id_cliente'], $_SESSION['id']);
        }
    } else {


        if ($_POST["tipo_venta"] == "NOTA") {
            pg_query("Update facturas_novalidas Set estado = 'Pasivo' where id_facturas_novalidas = '$_POST[comprobante]'");
            pg_query("Update pagos_venta Set estado = 'Pasivo' where id_factura_venta = '$_POST[comprobante]' and tipo_documento='Nota'");
            foreach (obtenerDetallaNota($_POST['comprobante'], $conpuntoresult) as $item) {
                $cant = $item["cantidad_unidad"];
                $cantidad = $item["cantidad"];
                if ($cant != 0) {
                    $cantidad = $cant;
                } else {
                    $cantidad = $cantidad;
                }
                $documento = 'Anulación N.V: ' . $item['comprobante'];
                $stock = obtenerStock($item['cod_productos'], $conpuntoresult);
                $total = number_format(($cantidad * $item['precio_venta']), 4, '.', '');
                updateKardex($_POST['comprobante'], $conpuntoresult, $item['cod_productos'], 'Inactivo', 'NV', NULL, NULL);
                updateKardexValorizado($_POST['comprobante'], $conpuntoresult, $item['cod_productos'], 'Inactivo', 'NV');
                $costoPromedio = obtenerCostoPromedioUnitarioAnular($item['cod_productos'], $conpuntoresult, $_POST['comprobante'], 'NV');
                procesarKardexEntrada($item['cod_productos'], $documento, $cantidad, $stock, $costoPromedio, 'Activo', $conpuntoresult, 'ANV', $_POST['comprobante'], $total, NULL, NULL, '', NULL, NULL, $item['id_cliente'], $_SESSION['id']);
            }
        }
    }
}





$data = 1;
///////////////////////////////////////////
///////////ASIENTO CONTABLE ANULACION FACTURA
if ($_POST["tipo_venta"] == "FACTURA") {
    //    echo 'factura1::' . "update transacciones set estado='Pasivo' where comprobante='$_POST[comprobante]'  and id_empresa='" . $conpuntoresult . "' and identificador_cli_pro='VEN'  ";
    pg_query("update transacciones set estado='Pasivo' where comprobante='$_POST[comprobante]'  and id_empresa='" . $conpuntoresult . "' and identificador_cli_pro='VEN'  ");
}
//////////CUENTA POR COBRAR REALIZADO /////////////
if ($_POST["tipo_venta"] == "FACTURA") {
    $consulta_id_pagos_cobrar = pg_query("select id_pagos_cobrar from pagos_cobrar  where num_factura = '$_POST[num_factura]' and tipo_factura='Factura'  ");
    while ($row = pg_fetch_row($consulta_id_pagos_cobrar)) {
        $resul_id = $row[0];
    }
    //        echo 'factura1::' . "update transacciones set estado='Pasivo' where comprobante= '$resul_id'  and id_empresa='" . $conpuntoresult . "' and identificador_cli_pro='CxC'  ";
    pg_query("update transacciones set estado='Pasivo' where comprobante= '$resul_id'  and id_empresa='" . $conpuntoresult . "' and identificador_cli_pro='CxC'  ");
}
///////////ASIENTO CONTABLE ANULACION NOTA DE VENTA
if ($_POST["tipo_venta"] == "NOTA") {
    //    echo 'nota::' . "update transacciones set estado='Pasivo' where comprobante='$_POST[comprobante]'  and id_empresa='" . $conpuntoresult . "' and identificador_cli_pro='NV' ";

    if ($var_comprobante_antnv != "") {


        pg_query("update transacciones set estado='Pasivo' where comprobante='$_POST[comprobante_antnv]'  and id_empresa='" . $conpuntoresult . "' and identificador_cli_pro='NV' ");
    } else {
        pg_query("update transacciones set estado='Pasivo' where comprobante='$_POST[comprobante]'  and id_empresa='" . $conpuntoresult . "' and identificador_cli_pro='NV' ");
    }
}

if ($var_comprobante_antnv != "") {

    //////////////////////////////////////////////////////////////////////////////
    $retIva = pg_query("select id_retencion_iva_factura_venta from retencion_iva_factura_venta where id_factura='$_POST[comprobante_antnv]'");
    $row1 = pg_fetch_row($retIva);
    if ($row1[0] != "") {
        pg_query("update retencion_iva_factura_venta set estado='Pasivo' where id_retencion_iva_factura_venta=$row1[0]");
    }

    $retFuente = pg_query("select id_retencion_fuente_factura_venta from retencion_fuente_factura_venta where id_factura='$_POST[comprobante_antnv]'");
    $row1 = pg_fetch_row($retFuente);
    if ($row1[0] != "") {
        pg_query("update retencion_fuente_factura_venta set estado='Pasivo' where id_retencion_fuente_factura_venta=$row1[0]");
    }
} else {
    //////////////////////////////////////////////////////////////////////////////
    $retIva = pg_query("select id_retencion_iva_factura_venta from retencion_iva_factura_venta where id_factura='$_POST[comprobante]'");
    $row1 = pg_fetch_row($retIva);
    if ($row1[0] != "") {
        pg_query("update retencion_iva_factura_venta set estado='Pasivo' where id_retencion_iva_factura_venta=$row1[0]");
    }

    $retFuente = pg_query("select id_retencion_fuente_factura_venta from retencion_fuente_factura_venta where id_factura='$_POST[comprobante]'");
    $row1 = pg_fetch_row($retFuente);
    if ($row1[0] != "") {
        pg_query("update retencion_fuente_factura_venta set estado='Pasivo' where id_retencion_fuente_factura_venta=$row1[0]");
    }
}



activarValoresCruzadosNCC($_POST["comprobante"]);
///////////////////////////////////////////
////////////////////////////////////////// 

echo $data;

function obtenerDetallaVenta($idFactura, $bodega) {
    $sql = "SELECT * FROM detalle_factura_venta DFV INNER JOIN factura_venta FV ON FV.id_factura_venta = DFV.id_factura_venta "
            . "WHERE FV.id_factura_venta=$idFactura AND FV.id_empresa=$bodega";
    return pg_fetch_all(pg_query($sql));
}

function obtenerDetallaNota($idFactura, $bodega) {
    $sql = "SELECT * FROM detalle_facturas_novalidas DFV INNER JOIN facturas_novalidas FV ON FV.id_facturas_novalidas = DFV.id_facturas_novalidas 
            WHERE FV.id_facturas_novalidas=$idFactura AND FV.id_empresa=$bodega";
    return pg_fetch_all(pg_query($sql));
}

function obtenerCantidadUnidadMedida($nombre) {
    $sql = "select cantidad from unidades_medida
 where descripcion='$nombre'";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return 1;
    }
    return $rows[0]["cantidad"];
}

function updateFormasPagoNCV($id) {
    $sql = "
    update formas_pago_mixto_nv
    set estado='Activo'
    where id_formas_pago_mixto_nv=$id";
    $res = pg_query($sql);
}

function obtenerFormasPagoNC($idventa) {
    $sql = "select*from formas_pago_mixto
    where id_factura_venta=$idventa
    and forma_pago='NOTA_CREDITO'
    and estado='Activo'";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}

function activarValoresCruzadosNCC($idventa) {
    $fp = obtenerFormasPagoNC($idventa);
    foreach ($fp as $value) {
        updateFormasPagoNCV($value["numero_documento"]);
    }
}
