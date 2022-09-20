<?php

session_start();
date_default_timezone_set('America/Guayaquil');
require '../../escpos-php/autoload.php';

use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;

include("../../fpdf/barcode.inc.php");
require_once '../../procesos/base.php';
conectarse();

$fecha = date('Y-m-d', time());
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}
$consulta = pg_query("select * from empresa left join factura_venta on empresa.id_empresa  = factura_venta.id_empresa left join clientes on factura_venta.id_cliente=clientes.id_cliente left join tipo_documento on tipo_documento.id_tdocu=clientes.id_tdocu where factura_venta.id_factura_venta='" . $id . "' ");
while ($row = pg_fetch_row($consulta)) {
    $mesa=nroMesaFactura($row[24]);
    if(!empty($mesa)){
        $mesa=" - MESA ".$mesa
    }
    $ruc = $row[2];
    $numeroAutorizacion = $row[35];
    $fechaEmision = $row[30];
    $date = new DateTime($fechaEmision);
    $fechaEmision = $date->format('d/m/Y');
    $claveAcceso = $row[51];
    $razonSocial =  "NUM ORDEN"."  ".$row[28].$mesa;
    $nombreComercial = $row[16];
    $direcionMatriz = $row[7];
    $direccionEstablecimiento = $row[3];
    $nroContribuyente = $row[19];
    $obligado = $row[17];
    $contribuyente = $row[66];
    $identificacion = $row[65];
    $direcion = $row[65];
    $telefono = $row[66];
    $email = $row[70];
    $secuencial = $row[29];
    $ip = $secuencial;
    $iparr = split("\-", $ip);
    $secuencial = $iparr[2];
    $establecimiento = $row[21];
    $puntoEmision = $row[22];
    $fechaAut = $row[36];
    $codigo = $row[73];

    $marca_delvehiculo = $row[55];
    $placanum = $row[56];
    $propiedad = $row[57];
    $num_reclamo = $row[58];
    $num_chasis = $row[59];

    $num_serie_guia = $row[54];
    if ($num_serie_guia != "000000000") {
        $num_serie_guia = $row[54];
    } else {
        $num_serie_guia = "";
    }

    $consulta_ambiente = pg_query("select nombre_ambi from ambiente  ");
    while ($row = pg_fetch_row($consulta_ambiente)) {
        $nombre_ambi = $row[0];
    }
    $ambiente = $nombre_ambi;
    $consulta_emision = pg_query("select nombre_temision from tipo_emision  ");
    while ($row = pg_fetch_row($consulta_emision)) {
        $nombre_emi = $row[0];
    }
    $emision = $nombre_emi;
}

$resultado = pg_query("
select P.codigo,
P.cod_barras,
P.articulo,
 D.cantidad,
 D.precio_venta,
 D.descuento_producto,
 F.tarifa12
 from factura_venta F,detalle_factura_venta D ,productos P
 where  d.cod_productos =P.cod_productos
 and D.id_factura_venta = F.id_factura_venta
 AND F.id_factura_venta = '" . $id . "'");

$vendedor = '';
$resultado2 = pg_query("SELECT F.tarifa12, F.tarifa0, F.tarifa0, F.iva_venta, F.descuento_venta, F.total_venta, F.id_usuario FROM factura_venta f WHERE id_factura_venta = '" . $id . "'");
while ($row = pg_fetch_row($resultado2)) {
    $subtotal = $row[0];
    $tarifa = $row[0];
    $tarifa0 = $row[2];
    $iva = $row[3];
    $descuento2 = $row[4];
    $total2 = number_format($row[5], 2, ',', '.');

    $consultaven = pg_query("select nombre_usuario, apellido_usuario from usuario where id_usuario={$row[6]}");
    $rowven = pg_fetch_row($consultaven);
    $vendedor = mb_strtoupper($rowven[0]) . ' ' . mb_strtoupper($rowven[1]);
}
//$imagen = "C:/xampp/htdocs/syswebfe/images/logo.png";

/* if ($_SESSION['ip_cliente'] == '192.168.1.103') {
  echo $vendedor . ' ++ ' . $_SESSION['ip_cliente'];
  } else {
  echo $_SESSION['ip_cliente'];
  } */
try {
    $nombre_impresora = 'LR2000';
    $connector = new WindowsPrintConnector($nombre_impresora);
    /* if ($_SESSION['id'] == 1) {
      $connector = new \Mike42\Escpos\PrintConnectors\NetworkPrintConnector("192.168.1.100", 9100);
      } else if ($_SESSION['id'] == 2) {
      $connector = new \Mike42\Escpos\PrintConnectors\NetworkPrintConnector("192.168.1.101", 9100);
      } */
    
//    $connector = new \Mike42\Escpos\PrintConnectors\NetworkPrintConnector("192.168.100.22", 9100);

    $printer = new Printer($connector);

    //$logo = EscposImage::load($imagen, false);
    $printer->setFont(Printer::FONT_A);
    $printer->setPrintLeftMargin(1);

    $printer->setJustification(Printer::JUSTIFY_CENTER);
    //$printer->bitImage($logo);
    $printer->setEmphasis(true);
    $printer->text("COCINA\n");
    $printer->setEmphasis(false);
    $printer->text($razonSocial . "\n");

    $printer->setFont(Printer::FONT_B);
    /*$printer->text("RUC: " . $ruc . "\n");
    $printer->text("Matriz: " . $direccionEstablecimiento . "\n");
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text("No. Autorización: " . $numeroAutorizacion . "\n");
    $printer->setJustification(Printer::JUSTIFY_CENTER);*/
//    $printer->text("Factura No. " . $establecimiento . '-' . $puntoEmision . '-' . $secuencial . "\n");
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text("Cliente: " . $contribuyente . "\n");
    $printer->text("RUC/CI: " . $identificacion . "\n");
    $printer->setFont(Printer::FONT_A);
    $printer->text("Fecha de emisión: " . date('d/m/Y H:i:s') . "\n");

    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text("__________________________________________\n");
    $printer->text("CANTIDAD         PRODUCTO                 \n");
    $printer->text("__________________________________________\n");


    while ($row = pg_fetch_row($resultado)) {
        $codigo = utf8_decode($row[0]);
        $codigoAuxiliar = '';
        $descripcion = utf8_decode($row[2]);
        $cantidad = $row[3];
        $tarifa12 = 0;
        $tarifa12 = $row[4];

        $precio = number_format($row[4], 2, '.', '');

        $descuento = $row[5];
        $tarifa12 = $tarifa12 * $cantidad;
        $Descucaltres = 0;
        $desc = 0;
        $desc = $row[5];
        $valcien = 100;
        $Descucaltres = ($tarifa12 / $valcien) * $desc;
        $tarifa12sin = $tarifa12 - $Descucaltres;
        $total = number_format($tarifa12sin, 2, '.', '');

        $subdesc = substr($descripcion, 0, 32);

        /*$longc = strlen($cantidad);
        $longp = strlen($precio);
        $longt = strlen($total);
        $longd = strlen($subdesc);
        $primersp = 28 - $longc - $longd;
        $segundosp = 5 - $longp;
        $tercersp = 14 - $longt;*/

        $printer->text($cantidad);
        //$printer->text("         " . $subdesc."\n");
        $printer->text("         " .$subdesc."\n");
        /*$printer->text(str_repeat(' ', $primersp));
        $printer->text(str_repeat(' ', $segundosp));
        $printer->text($precio);
        $printer->text(str_repeat(' ', $tercersp));
        $printer->text($total . "\n");*/
    }
    //$printer->text("________________________________________________________\n");

    /*if (empty($descuento)) {
        $descuento = 0;
    }

    $longt = strlen(number_format($tarifa, 2, '.', ''));
    $longt0 = strlen(number_format($tarifa0, 2, '.', ''));
    $longiva = strlen(number_format($iva, 2, '.', ''));
    $longdesc = strlen(number_format($descuento, 2, '.', ''));
    $longaux1 = strlen("Subtotal IVA 12%: ");
    $longaux2 = strlen("Subtotal IVA 0%: ");
    $longaux3 = strlen("IVA 12%: ");
    $longaux4 = strlen("Descuento: ");
    $printer->text("Subtotal IVA 12%: ");
    $printer->text(str_repeat(' ', 56 - $longt - $longaux1));
    $printer->text(number_format($tarifa, 2, '.', '') . "\n");
    $printer->text("Subtotal IVA 0%: ");
    $printer->text(str_repeat(' ', 56 - $longt0 - $longaux2));
    $printer->text(number_format($tarifa0, 2, '.', '') . "\n");
    $printer->text("Descuento: ");
    $printer->text(str_repeat(' ', 56 - $longdesc - $longaux4));
    $printer->text(number_format($descuento, 2, '.', '') . "\n");
    $printer->text("IVA 12%: ");
    $printer->text(str_repeat(' ', 56 - $longiva - $longaux3));
    $printer->text(number_format($iva, 2, '.', '') . "\n");

    $printer->setJustification(Printer::JUSTIFY_RIGHT);
    $printer->text("________________________________________________________\n");
    $printer->text("Total:" . $total2 . "\n");
    $printer->setJustification(Printer::JUSTIFY_LEFT);*/
    $printer->setFont(Printer::FONT_B);
    $printer->text("________________________________________________________\n");
    $printer->text("Enviado por: {$vendedor}\n");
    /*$printer->text("Clave de acceso al documento en el SRI:\n");
    new barCodeGenrator($claveAcceso, 1, 'temp.gif', 470, 60, true);
    $barc = EscposImage::load('temp.gif', false);
    $printer->bitImage($barc);*/

    $printer->feed();
    $printer->cut();
    $printer->pulse();


    $printer->close();
} catch (Exception $e) {
    http_response_code(500);
    echo "Couldn't print to this printer: " . $e->getMessage() . "\n";
}

function nroMesaFactura($idfactura)
{
    $sql="
    select mesa from prueba.restaurante_ordenes
    where tipo_documento='FACTURA' and id_documento=$idfactura
    ";
    //var_dump($sql);
    $res=pg_query($sql);
    $row=pg_fetch_row($res);
    //var_dump($row);
    if(empty($row)){
        return "";
    }
    return $row[0];
}