<?php

//      include '../fpdf/rotation.php';        
//      include("../fpdf/barcode.inc.php");
//      include '../procesos/base.php';
include '../../fpdf/rotation.php';
include("../../fpdf/barcode.inc.php");
require_once('../../procesos/base.php');
require_once('../../procesos/funciones.php');

require_once __DIR__ . "/../../procesos/configuracion.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Guayaquil');

//error_reporting(0);
class PDF extends PDF_Rotate
{

    var $widths;
    var $aligns;

    function SetWidths($w)
    {
        //Set the array of column widths

        $this->widths = $w;
    }

    function SetAligns($a)
    {
        //Set the array of column alignments

        $this->aligns = $a;
    }

    function Row($data, $border = 0, $style = "", $fill = false)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 5 * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();

            if ($border == 1) {
                //Draw the border
                $this->Rect($x, $y, $w, $h, $style);
            }

            $this->MultiCell($w, 5, $data[$i], 0, $a, $fill);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt)
    {
        //Computes the number of lines a MultiCell of width w will take
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }

    function GetCurrentWidth()
    {
        return $this->w - ($this->lMargin * 2);
    }

    function RotatedImage($file, $x, $y, $w, $h, $angle)
    {
        $this->Rotate($angle, $x, $y);
        $this->Image($file, $x, $y, $w, $h);
        $this->Rotate(0);
    }
}


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    generarPDF($id);
}

function generarPDF($id)
{
    conectarse();
    $consulta = pg_query("select e.id_empresa, nombre_empresa, ruc_empresa, direccion_empresa, telefono_empresa, celular_empresa,
        email_empresa, nombre_comercial, obligacion, contribuyente_espe, establecimiento, punto_emision,
        fecha_actual as fecha_emision, num_autorizacion, fecha_autorizacion, num_serie, num_serie, 
        fv.clave, num_serie, c.identificacion, nombres_cli, direccion_cli, correo,
        case when c.telefono!='' then c.telefono else c.celular end as telefono_cli, codigo_tdocu, c.telefono telefono_cli,
        c.celular celular_cli, c.correo correo_cli, direccion_cli ,motivo,num_placa,id_transportista,num_guia_remision,nombres_trans,
         fecha_inicio,fecha_fin,fv.id_guia_remision,fecha_autorizacion,transportista.identificacion as ruc_trasportista
         ,punto_partida,punto_llegada
        from empresa e inner join guia_remision fv using(id_empresa) 
        left join clientes c using(id_cliente) 
        inner join tipo_documento using(id_tdocu) 
        inner join transportista using(id_transportista) 
        where fv.id_guia_remision =  '" . $id . "' ");
    $rowempre = pg_fetch_assoc($consulta);

    $consulta_ambiente = pg_query("select nombre_ambi from ambiente where estado_ambi='Activo' ");
    while ($row = pg_fetch_row($consulta_ambiente)) {
        $ambiente = $row[0];
    }
    $consulta_emision = pg_query("select nombre_temision from tipo_emision WHERE id_temision='1' ");
    while ($row = pg_fetch_row($consulta_emision)) {
        $emision = $row[0];
    }

    //		$ceros = 9;
    //		$temp = '';
    //		$tam = $ceros - strlen($secuencial);
    //	  	for ($i = 0; $i < $tam; $i++) {                 
    //	    	$temp = $temp .'0';        
    //	  	}
    //	  	$secuencial = $temp .''. $secuencial;

    $pdf = new PDF('P', 'mm', array(69, 700));
    $pdf->AddPage();
    $pdf->SetMargins(0, 0, 0, 0);
    $pdf->SetFont('Helvetica', '', 7);
    $pdf->Ln(0);

    $conf = new Configuracion();
    $val_rimpe = $conf->getParametroEmpresa("val_rimpe");
    $agente_reten = $conf->getParametroEmpresa("check_agente_reten");

    $pagew = $pdf->GetCurrentWidth();

    $pagew = $pdf->GetCurrentWidth();

    $pdf->SetFont('Helvetica', '', 9);
    $pdf->Cell($pagew, 4, $rowempre['nombre_comercial'], 0, 1, "C");
    $pdf->SetFont('Helvetica', '', 7);
    $pdf->Cell($pagew, 4, $rowempre['nombre_empresa'], 0, 1, "C");
    $pdf->Cell($pagew, 4, $rowempre['ruc_empresa'], 0, 1, "C");
    $pdf->MultiCell($pagew, 4, $rowempre['direccion_empresa'], 0, "C");
    $pdf->Cell($pagew, 4, $rowempre['celular_empresa'] . "/" . $rowempre['telefono_empresa'], 0, 1, "C");
    $pdf->Ln(2);
    $pdf->Cell($pagew, 4, "E-mail: " . $rowempre['email_empresa'], 0, 1, "L");
    $pdf->Cell($pagew, 4, "Obligado a llevar Contabilidad: " . $rowempre["obligacion"], 0, 1, "L");

    if ($agente_reten != "") {
        $pdf->Cell($pagew, 4, 'AGENTE DE RETENCION RESOLUCION 00000001', 0, 1, "L");
    }
    if (!empty($val_rimpe)) {
        $pdf->Cell($pagew, 4, $val_rimpe, 0, 1, "L");
    }

    $ambiente = getAmbiente(2);
    $emision = getEmision(1);

    $pdf->Cell($pagew / 2, 4, utf8_decode("Ambiente " . $ambiente), 0, 0, "L");
    $pdf->Cell($pagew / 2, 4, utf8_decode("Emisíon" . $emision), 0, 1, "L");
    $pdf->Cell($pagew, 4, utf8_decode("Número Autorización: "), 0, 1, "L");
    $pdf->Cell($pagew, 4, utf8_decode($rowempre["clave"]), 0, 1, "L");
    $pdf->Cell($pagew, 4, utf8_decode("Clave de Acceso: "), 0, 1, "L");
    $numeroAutorizacion = $rowempre['clave'];
    $code_number = $numeroAutorizacion; // Código de barras		
    new barCodeGenrator($code_number, 1, 'temp.gif', 470, 60, true); /// img codigo barras	
    $pdf->Image('temp.gif', 5, $pdf->GetY(), $pagew - 10, 10);
    $pdf->Ln(11);

    /* $pdf->SetFont('Helvetica', 'B', 9);
    $pdf->Cell($pagew, 4, utf8_decode("GUÍA DE REMISIÓN"), 0, 1, "C"); */
    $secuencial = "$rowempre[num_serie]" . "-" . "$rowempre[num_guia_remision]";
    $pdf->Cell($pagew, 4, utf8_decode("Guía de Remisión: ") . $secuencial, 0, 1, "L");
    $pdf->Cell($pagew, 4, utf8_decode("Identificación Transportista: " . $rowempre['ruc_trasportista']), 0, 1, "L");
    $pdf->MultiCell($pagew, 4, utf8_decode("Razón Social Trasportista: " . $rowempre['nombres_trans']));
    $pdf->Cell($pagew, 4, utf8_decode("Placa: " . $rowempre["num_placa"]), 0, 1, "L");
    $pdf->MultiCell($pagew, 4, utf8_decode("Punto de Partida: " . $rowempre["punto_partida"]));
    $pdf->Cell($pagew / 2, 4, utf8_decode("Fecha Inicio: " . $rowempre["fecha_inicio"]), 0, 0, "L");
    $pdf->Cell($pagew / 2, 4, utf8_decode("Fecha Fin: " . $rowempre["fecha_fin"]), 0, 1, "L");
    $pdf->Cell($pagew, 4, str_pad("", $pagew, "-"), 0, 1, "C");
    $pdf->MultiCell($pagew, 4, utf8_decode("Motivo de Traslado: " . $rowempre["motivo"]));
    $pdf->MultiCell($pagew, 4, utf8_decode("Destino: " . $rowempre["punto_llegada"]));
    $pdf->MultiCell($pagew, 4, utf8_decode("Identificación Destinatario: " . $rowempre["identificacion"]));
    $pdf->MultiCell($pagew, 4, utf8_decode("Razón Social Destinatario: " . $rowempre["nombres_cli"]));
    $pdf->MultiCell($pagew, 4, utf8_decode("Documento Aduanero: "));
    $pdf->MultiCell($pagew, 4, utf8_decode("Código Establecimeinto Destino: "));
    $pdf->MultiCell($pagew, 4, utf8_decode("Ruta: " . $rowempre["punto_partida"] . "-" . $rowempre["punto_llegada"]));

    $pdf->Ln(4);

    $w = $pagew / 4;
    $pdf->SetAligns(["C", "C", "C", "C"]);
    $pdf->SetWidths([$w - 5, $w + 15, $w - 5, $w - 5]);
    $pdf->Row(["CANT", "DESC", "CP", "CA"], 1);

    $res = pg_query("select  P.codigo,P.cod_barras, P.articulo, D.cantidad 
    from detalle_guia_remision D  , productos P where  d.cod_productos =P.cod_productos and
    D.id_guia_remision = '" . $rowempre['id_guia_remision'] . "'");
    $detales = pg_fetch_all($res);
    $pdf->SetAligns(["L", "L", "L", "L"]);
    foreach ($detales as $value) {
        $pdf->Row([
            $value["cantidad"],
            utf8_decode($value["articulo"]),
            utf8_decode($value["codigo"]),
            utf8_decode($value["cod_barras"]),
        ]);
    }
    $pdf->Cell($pagew, 4, "", "T", 1);


    $pdf->Output();
}

function getAmbiente($ambiente)
{
    $consulta_ambiente = pg_query("select nombre_ambi from ambiente where id_ambi='$ambiente'  ");
    while ($row = pg_fetch_row($consulta_ambiente)) {
        $nombre_ambi = $row[0];
    }
    return $ambiente = $nombre_ambi;
}

function getEmision($emision)
{
    $consulta_emision = pg_query("select nombre_temision from tipo_emision  where id_temision='$emision' ");
    $row = pg_fetch_row($consulta_emision);
    return $row[0];
}
