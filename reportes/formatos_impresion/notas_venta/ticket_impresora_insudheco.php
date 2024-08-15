<?php

session_start();
date_default_timezone_set('America/Guayaquil');
//require '../../escpos-php/autoload.php';
require __DIR__ . "/../../../escpos-php/autoload.php";

use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;

/* include("../../fpdf/barcode.inc.php"); */
//require_once '../../procesos/base.php';
include __DIR__ . "/../../../fpdf/barcode.inc.php";
require_once __DIR__ . "/../../../procesos/base.php";
require_once __DIR__ . "/../../../procesos/configuracion.php";

conectarse();


//list($width, $height, $type, $attr) = getimagesize('../../../images/' . $_SESSION["parametros_empresa"]["logo_empresa"]);

/* var_dump(informacionFactura(1));
  exit(); */
try {
    //$nombre_impresora = 'POS-80_3';
    //$connector = new WindowsPrintConnector($nombre_impresora);
    $bodega = $_SESSION['PV'];
    if ($bodega == '1') {

        $connector = new \Mike42\Escpos\PrintConnectors\NetworkPrintConnector("192.168.1.87", 9100);
    } else {
        $connector = new \Mike42\Escpos\PrintConnectors\NetworkPrintConnector("192.168.1.175", 9100);
    }
    /* if ($_SESSION['id'] == 1) {
      $connector = new \Mike42\Escpos\PrintConnectors\NetworkPrintConnector("192.168.1.100", 9100);
      } else if ($_SESSION['id'] == 2) {
      $connector = new \Mike42\Escpos\PrintConnectors\NetworkPrintConnector("192.168.1.101", 9100);
      } */

    //  $connector = new \Mike42\Escpos\PrintConnectors\NetworkPrintConnector("192.168.1.157", 9100);

    $id = 0;
    $id = $_GET['id'];

    $datosf = informacionFactura($id);

    $printer = new Printer($connector);
    $printer->setPrintLeftMargin(1);

    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->setFont(Printer::FONT_B);

    //    imprimirLogo(0.3);
    imprimirInfoFactura();
    imprimirDetallesFacura();
    $printer->feed();
    imprimirPagare();

    $printer->feed();
    $printer->cut();
    $printer->pulse();


    $printer->close();
} catch (Exception $e) {
    http_response_code(500);
    echo "Couldn't print to this printer: " . $e->getMessage() . "\n";
}

function informacionFactura($idfactura)
{
    $sql = "SELECT nombre_empresa, ruc_empresa, direccion_empresa, telefono_empresa, celular_empresa,
    email_empresa, nombre_comercial, obligacion, contribuyente_espe, establecimiento, punto_emision,
    fv.fecha_actual as fecha_emision, comprobante, 
    identificacion, nombres_cli, direccion_cli, 
    case when telefono!='' then telefono else celular end as telefono_cli,ciudad,id_vendedor, fv.id_usuario, hora_actual,
    u.usuario
    from empresa e left join facturas_novalidas fv using(id_empresa) 
    left join clientes c using(id_cliente) 
    left join tipo_documento td using(id_tdocu) 
    left join usuario u using(id_usuario) 
    where fv.id_facturas_novalidas='" . $idfactura . "'  ";

    $res = pg_query($sql);
    $row = pg_fetch_assoc($res);
    if (empty($row)) {
        return [];
    }
    return $row;
}

function imprimirInfoFactura()
{
    global $printer, $datosf;

    $conf = new Configuracion();


    $printer->setFont(Printer::FONT_A);
    $printer->text($datosf["nombre_comercial"] . "\n");
    $printer->setFont(Printer::FONT_B);
    $printer->text($datosf["nombre_empresa"] . "\n");
    $printer->setFont(Printer::FONT_A);
    $printer->text($datosf["ruc_empresa"] . "\n");
    $printer->setFont(Printer::FONT_B);
    $printer->text($datosf["direccion_empresa"] . "\n");
    $printer->text($datosf["celular_empresa"] . "/" . $datosf["telefono_empresa"] . "\n");
    $printer->text($datosf["email_empresa"] . "\n");
    imprimirLineaDivisora();
    $printer->feed(1);
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text("Nota de entrega: " . $datosf["comprobante"] . "\n");
    $printer->text("Fecha de Emisión: " . $datosf["fecha_emision"] . "\n");
    $printer->text("Cliente: " . $datosf["nombres_cli"] . "\n");
    $printer->text("RUC/CI : " . $datosf["identificacion"] . "\n");
    $printer->text("Direcc : " . $datosf["direccion_cli"] . "\n");
    $printer->text("Telf   : " . $datosf["telefono_cli"] . "\n");
    $printer->text("Ciudad : " . $datosf["ciudad"] . "\n");
}

function imprimirDetallesFacura()
{
    global $id, $printer, $datosf;
    $tarifasimpfactura = obtenerTarifasImpuestoNota($id);

    $printer->setFont(Printer::FONT_B);
    imprimirLineaDivisora();
    imprirmirDatosDetalle("CANT", "PRODUCTO", "P Unit", "TOTAL");
    imprimirLineaDivisora();

    $sql = pg_query("
        select detalle_facturas_novalidas.cantidad,
        productos.articulo,
        detalle_facturas_novalidas.precio_venta,
        detalle_facturas_novalidas.total_venta,
        productos.iva,
        detalle_facturas_novalidas.descuento_producto
        from facturas_novalidas,
        detalle_facturas_novalidas,
        productos
        where facturas_novalidas.id_facturas_novalidas = detalle_facturas_novalidas.id_facturas_novalidas
        and detalle_facturas_novalidas.cod_productos = productos.cod_productos
        and detalle_facturas_novalidas.id_facturas_novalidas = '" . $id . "'
        order by detalle_facturas_novalidas.id_facturas_novalidas asc
    ");

    $vdescuentop = 0;
    while ($fila = pg_fetch_row($sql)) {

        $descuentop = $fila[5];
        if (!empty($descuentop)) {
            $cantidadp = $fila[0];
            $preciou = $fila[2];
            $totalprodv = $fila[3];
            $vdescuentop += ($cantidadp * $preciou) - $totalprodv;
        }


        if ($fila[4] == "Si") {

            $pu = utf8_decode(round($fila[2], 2));
            if (!empty($descuentop)) {
                $pdesc = (100 - $descuentop) / 100;
                $vpu = $fila[2] * $pdesc;
                $pu = utf8_decode(round($vpu, 2));
            }

            imprirmirDatosDetalle(utf8_decode(round($fila[0], 2)), utf8_decode($fila[1]), $pu, round($fila[3], 2, PHP_ROUND_HALF_EVEN) . "*");
        } else {

            $pu = utf8_decode(round($fila[2], 2));
            if (!empty($descuentop)) {
                $pdesc = (100 - $descuentop) / 100;
                $vpu = $fila[2] * $pdesc;
                $pu = utf8_decode(round($vpu, 2));
            }

            imprirmirDatosDetalle(utf8_decode(round($fila[0], 2)), utf8_decode($fila[1]), $pu, round($fila[3], 2, PHP_ROUND_HALF_EVEN));
        }
    }

    imprimirLineaDivisora();

    $gdescuento = 0;
    if (!empty($tarifasimpfactura)) {
        $sub = 0;
        $total = 0;
        $gdescuento = 0;

        $sql = pg_query("select tarifa0,tarifa12,iva_venta,descuento_venta,total_venta from facturas_novalidas where id_facturas_novalidas= '" . $id . "' ");
        while ($fila = pg_fetch_row($sql)) {
            $total = round($fila[4], 2, PHP_ROUND_HALF_EVEN);
            $gdescuento = round($fila[3], 2, PHP_ROUND_HALF_EVEN);
        }

        imprirmirDatosTotales("Desc.", $gdescuento);
        foreach ($tarifasimpfactura as $key => $value) {
            imprirmirDatosTotales("Tarifa $value[tarifa]%", round($value["base_imponible"], 2));
            $sub += $value["base_imponible"];
        }
        imprirmirDatosTotales("Subt.", round($sub, 2));
        foreach ($tarifasimpfactura as $key => $value) {
            if ($value["valor_impuesto"] == 0) {
                continue;
            }
            imprirmirDatosTotales("IVA $value[tarifa]%", round($value["valor_impuesto"], 2));
        }
        imprirmirDatosTotales("Total", $total);
    } else {
        $sql = pg_query("select tarifa0,tarifa12,iva_venta,descuento_venta,total_venta from facturas_novalidas where id_facturas_novalidas= '" . $id . "' ");

        $sub0 = 0;

        $sub12 = 0;

        $iva = 0;

        $total = 0;

        while ($fila = pg_fetch_row($sql)) {

            $tar0 = round($fila[0], 2, PHP_ROUND_HALF_EVEN);

            $sub0 = round($fila[1], 2, PHP_ROUND_HALF_EVEN);

            $sub12 = round($fila[2], 2, PHP_ROUND_HALF_EVEN);

            $iva = round($fila[3], 2, PHP_ROUND_HALF_EVEN);

            $total = round($fila[4], 2, PHP_ROUND_HALF_EVEN);

            $sub_total = $sub0 + $tar0;

            $tar0 = $tar0 + 0;

            $sub = $sub_total;

            $total = $total + 0;
            $total = number_format($total, 2, '.', '');


            $gdescuento = $iva; // + $vdescuentop;

            imprirmirDatosDetalle("", "", "Desc.", round($gdescuento, 2));

            imprirmirDatosDetalle("", "", "T. 15%", $sub0);

            imprirmirDatosDetalle("", "", "T. 0%", $tar0);

            imprirmirDatosDetalle("", "", "Subt.", $sub);

            imprirmirDatosDetalle("", "", "IVA 15%.", $sub12);

            imprirmirDatosDetalle("", "", "Total", $total);
        }
    }
    $printer->feed();

    if ($gdescuento > 0) {
        $printer->text("SU DESCUENTO ES DE: " . number_format($gdescuento, 2, ".", "") . "\n");
    }
    $printer->text("Artendido por: " . $datosf["usuario"] . "\n");
    $printer->text("Fecha : " . $datosf["fecha_emision"] . " " . $datosf["hora_actual"] . "\n");
    $printer->text("SALIDA LA MERCADERIA NO SE ACEPTAN DEVOLUCIONES\n");
    $printer->text("CANJEE SU FACTURA EN VENTANILLA\n");
}

function imprirmirDatosDetalle($cantidad, $producto, $pu, $total)
{
    global $printer;

    $cnt = str_pad($cantidad, 5, " ");
    $pr = str_pad(substr($producto, 0, 38), 42, " ");
    $pun = str_pad($pu, 7, " ");
    $tl = str_pad($total, 5, " ");

    $printer->text("$cnt$pr$pun$tl" . "\n");
}

function imprirmirDatosTotales($tag, $valor)
{
    global $printer;

    $cnt = str_pad("", 5, " ");
    $pr = str_pad(substr("", 0, 38), 38, " ");
    $pun = str_pad($tag, 11, " ");
    $tl = str_pad($valor, 5, " ");

    $printer->text("$cnt$pr$pun$tl" . "\n");
}

function imprimirLogo($percent)
{
    global $printer;
    $filename = '../../../images/' . $_SESSION["parametros_empresa"]["logo_empresa"];

    list($width, $height) = getimagesize($filename);
    $newwidth = $width * $percent;
    $newheight = $height * $percent;

    $thumb = imagecreatetruecolor($newwidth, $newheight);
    $source = imagecreatefromjpeg($filename);

    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    imagejpeg($thumb, "logo_tmp.jpg");

    $img = EscposImage::load("logo_tmp.jpg", false);
    $printer->bitImage($img);
}

function imprimirLineaDivisora()
{
    global $printer;
    $ln = str_pad("", 63, "-");
    $printer->text($ln . "\n");
}

function obtenerTarifasImpuestoNota($id)
{
    $sql = "select
    di.cod_impuesto, 
    di.cod_tarifa, 
    di.tarifa, 
    sum(di.valor_impuesto)valor_impuesto, 
    sum(di.base_imponible)base_imponible
    from
    facturas_novalidas fc
    inner join detalle_facturas_novalidas dfc
    using(id_facturas_novalidas)
    inner join detalle_impuesto_producto_notaventa di
    using(id_detalle_facturas_novalidas)
    where id_facturas_novalidas=$id
    group by di.cod_tarifa, di.cod_impuesto, di.tarifa";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!empty($rows)) {
        return $rows;
    }
    return [];
}

function imprimirPagare()
{
    global $printer, $id, $datosf;
    $sql = "
    select*from formas_pago_mixto
    where tipo_documento='NOTA'
    and forma_pago='CREDITO'
    and id_factura_venta=$id 
    and estado='Activo'
    limit 1
    ";

    $res = pg_query($sql);
    $row = pg_fetch_assoc($res);
    if (!empty($row)) {
        $printer->text("Crédito. " . $row["valor"] . "\n");
        $printer->text("Crédito, " . $row["fecha_actual"] . "\n");
        $printer->feed();
        $printer->text("Debo y pagare de manera incondicional a la orden de " . "\n");
        $printer->text($datosf["nombre_empresa"] . "," . "\n");
        $printer->text("el la ciudad de: IBARRA, el valor de $ " . $row["valor"] . ", valor que" . "\n");
        $printer->text("recibo en productos a crédito detallados en este documento." . "\n");
        $printer->feed(3);
        imprimirLineaDivisora();
        $printer->setFont(Printer::FONT_A);
        $printer->text($datosf["nombres_cli"] . "\n");
        $printer->text("Id: " . $datosf["identificacion"] . "\n");
    }
    return;
}
