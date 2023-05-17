<?php

include '../../fpdf/rotation.php';
include("../../fpdf/barcode.inc.php");
require_once('../../procesos/base.php');
require_once __DIR__ . "./../../reportes/formatos_ride/layout_ride.php";


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(0);

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

    function Header()
    {
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetY(1);
        $this->Cell(20, 5, 'Generado: ' . $fecha, 0, 0, 'C', 0);
        //	        $this->Cell(178, 5, 'SUPERMERCADO SUPER FIESTA', 0,0, 'R', 0);                                                             
        $this->Ln(7);
        $this->SetX(13);
        // $this->RotatedImage('../../fpdf/logo.fw.png', 50, 150, 100, 80, 45);                            
        $this->SetX(0);
    }

    function Footer()
    {
        $this->SetY(-10);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
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
    generarPDFReten($id);
}

function generarPDFReten($id)
{
    global $pdf;
    $infofac = getInfoRetencion($id);
    $detallesfac = getDetallesRetención($infofac["id_factura"]);

    //datos empresa
    $razonsocial = $infofac["nombre_empresa"];
    $dirmatriz = $infofac["direccion_empresa"];
    $rucempresa = $infofac["ruc_empresa"];
    $obligadoconta = $infofac["obligacion"];
    $contribuyenteespe = $infofac["contribuyente_espe"];
    //datos retención
    $numreten = $infofac["num_serie"];
    $numautorizacion = $infofac["num_autorizacion"];
    if ($numautorizacion == "") {
        $numautorizacion = $infofac['clave'];
    } else {
        $numautorizacion = $infofac['num_autorizacion'];
    }
    $fechaaut = $infofac["fecha"] . " " . $infofac["hora_actual"];
    $claveacceso = $infofac["clave"];
    $fechaemision = $infofac["fecha"];
    $ambiente = 2;
    $emision = 1;
    //datos factura
    $tipocomprobante = $infofac["tipo_comprobante"];
    $numfactura = $infofac["num_factura"];
    $fechemisionfac = $infofac["fecha_emision_factura"];
    $periodofiscal = date("m/Y", strtotime($fechemisionfac));
    //datos proveedor
    $razonsocialpro = $infofac["empresa_pro"];
    $identificacionpro = $infofac["identificacion_pro"];
    $direccionpro = $infofac["direccion_pro"];
    $telefonopro = $infofac["telefono_pro"];
    $celularpro = $infofac["celular_pro"];
    $correopro = $infofac["correo"];

    $pdf = new PDF('P', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetMargins(2, 0);
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->SetFont('Arial', '', 9);

    $totalw = $pdf->GetCurrentWidth();
    $halfw = $totalw / 2;
    $cellheight = 5;

    cabeceraRide(
        $pdf,
        $razonsocial,
        $dirmatriz,
        $obligadoconta,
        $contribuyenteespe,
        "COMPROBANTE DE RETENCIÓN",
        $rucempresa,
        $numreten,
        $numautorizacion,
        $ambiente,
        $emision,
        $fechaaut,
        $claveacceso,
        '../../images/' . $_SESSION["parametros_empresa"]["logo_empresa"],
        $cellheight
    );

    //información proveedor
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->Cell($halfw, $cellheight, "", 0, 0);
    $pdf->Cell($halfw, $cellheight, utf8_decode("RUC/CI: $identificacionpro"), 0, 1);
    $pdf->SetXY($x, $y);
    $pdf->MultiCell($halfw, $cellheight, utf8_decode("Razón Social: $razonsocialpro"));
    $pdf->Ln(2);
    $pdf->Cell($halfw, $cellheight, utf8_decode("Fecha de emisión: " . $fechaemision), 0, 1);
    $pdf->Ln(2);

    //detalles factura
    $cellwidth = $totalw / 8;
    $pdf->SetWidths([
        $cellwidth + 10,
        $cellwidth + 10,
        $cellwidth - 5,
        $cellwidth - 10,
        $cellwidth,
        $cellwidth - 5,
        $cellwidth,
        $cellwidth
    ]);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetAligns(array_fill(0, 8, "C"));
    $pdf->Row([
        utf8_decode("Comprobante"),
        utf8_decode("Número"),
        utf8_decode("Fecha Emisión"),
        utf8_decode("Ejercicio Fiscal"),
        utf8_decode("Base Imponible"),
        utf8_decode("Impuesto"),
        utf8_decode("% Retención"),
        utf8_decode("Valor Retenido")
    ], 1);
    $pdf->SetFont('Arial', '', 9);
    $pdf->SetAligns(["C", "C", "C", "C", "C", "C", "C", "R"]);

    foreach ($detallesfac as $row) {
        $pdf->Row([
            $tipocomprobante,
            $numfactura,
            $fechaemision,
            $periodofiscal,
            $row["base_imponible"],
            $row["nombre_trete"],
            $row["porsentaje"],
            $row["valor_retenido"]
        ]);
    }

    //lìnea divisora
    $pdf->Line(2, $pdf->GetY(), $totalw + 2, $pdf->GetY());
    $pdf->Ln(2);

    //información adicional
    $offsetleft = $halfw + 50;
    $cellwidth = $offsetleft - 10;
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell($cellwidth, $cellheight, utf8_decode("Informaciòn Adicional:"), 0, 1);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Ln(2);
    $pdf->MultiCell($cellwidth, $cellheight, utf8_decode("Dirección: " . mb_strtoupper($direccionpro)));
    $pdf->Cell($cellwidth, $cellheight, utf8_decode("Email: $correopro"), 0, 1);
    $pdf->Cell($cellwidth, $cellheight, utf8_decode("Teléfono: " . (empty($celularpro) ? $telefonopro : $celularpro)), 0, 1);
    $pdf->Ln(2);

    if (isset($_GET['id'])) {
        $pdf->Output();
    } else {
        $pdf_file_contents = $pdf->Output("", "S");
        return $pdf_file_contents;
    }
}

function getInfoRetencion($id)
{
    $sql = "
    select
    e.nombre_empresa,
    e.ruc_empresa,
    e.direccion_empresa,
    e.telefono_empresa,
    e.celular_empresa,
    e.email_empresa,
    e.nombre_comercial,
    e.obligacion,
    e.contribuyente_espe,
    p.empresa_pro,
    p.identificacion_pro,
    p.direccion_pro,
    p.telefono telefono_pro,
    p.celular celular_pro,
    p.correo,
    rfc.fecha,
    rfc.hora_actual,
    rfc.clave,
    rfc.num_autorizacion,
    rfc.num_serie,
    rfc.id_factura,
    fc.tipo_comprobante,
    fc.num_serie num_factura,
    fc.fecha_emision fecha_emision_factura
    from factura_compra fc
    inner join proveedores p
    using(id_proveedor)
    inner join empresa e
    using(id_empresa)
    left join retencion_fuente_factura_compra rfc 
    on rfc.id_factura=fc.id_factura_compra 
    where rfc.id_retencion_fuente_factura_compra=$id
    and  rfc.id_gastos=1
    ";

    $res = pg_query($sql);
    $row = pg_fetch_assoc($res);
    if (empty($row)) {
        return [];
    }
    return $row;
}
function getDetallesRetención($id_fact)
{
    $sql = "
    select 
    CD.base_imponible,
    R.nombre_trete,
    CD.porsentaje,
    CD.valor_retenido,
    R.codigo_trete,
    TR.codigo_formulario
    from retencion_fuente_factura_compra CR
    inner join detallecomprobanteretencion CD on CR.id_retencion_fuente_factura_compra = CD.id_retencion_fuente_factura_compra
    inner join tipo_retencion R on CD.id_trete = R.id_trete
    inner join retencion_fuentes TR on CD.id_retencion_fuentes = TR.id_retencion_fuentes
    where CR.id_factura = $id_fact
    ";

    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}
