<?php
require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();

class PDF extends FPDF
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

    function Row($data, $border = 0)
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
            //Draw the border
            if ($border == 1) {
                $this->Rect($x, $y, $w, $h);
            }
            //Print the text
            $this->MultiCell($w, 5, $data[$i], 0, $a);
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

    function Header()
    {

        $fecha = date('Y-m-d', time());
        $empresa = $_SESSION['nombre_empresa'];
        $logo = "../images/".$_SESSION["parametros_empresa"]["logo_empresa"];
        $propietario = utf8_decode($_SESSION['propietario']);
        $telefono = utf8_decode($_SESSION['telefono']);
        $celular = utf8_decode($_SESSION['celular']);
        $pais = utf8_decode($_SESSION['pais_ciudad']);
        $direccion = utf8_decode($_SESSION['direccion']);
        $slogan = utf8_decode($_SESSION['slogan']);

        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->SetFont('Amble-Regular', '', 10);
        //$fecha = date('Y-m-d', time());
        $this->SetX(1);
        $this->SetY(1);
        $this->Cell(20, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(150, 5, "CLIENTE", 0, 1, 'R', 0);
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(182, 8, utf8_decode($empresa), 0, 1, 'C', 0);
        $this->Image($logo, 5, 8, 50, 16);
        $this->SetFont('Amble-Regular', '', 10);
        $this->Cell(190, 5, "PROPIETARIO: " . $propietario, 0, 1, 'C', 0);
        $this->Cell(80, 5, "TEL.: " . $telefono, 0, 0, 'R', 0);
        $this->Cell(80, 5, "CEL.: " . $celular, 0, 1, 'C', 0);
        $this->Cell(180, 5, "DIR.: " . $direccion, 0, 1, 'C', 0);
        $this->Cell(180, 5, "SLOGAN.: " . $slogan, 0, 1, 'C', 0);
        $this->Cell(180, 5, $pais, 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(1, 45, 210, 45);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(182, 5, utf8_decode("MANTENIMIETOS PENDIENTES DE COBRO"), 0, 1, 'C', 0);
        $this->SetFont('Amble-Regular', '', 10);
        $this->Ln(3);
        $this->SetFillColor(255, 255, 225);
        $this->SetLineWidth(0.2);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

class Reporte
{
    private $pdf;

    public function __construct()
    {
        $this->pdf = new PDF('P', 'mm', 'a4');
        $this->pdf->SetAutoPageBreak(true, 10);
        $this->pdf->AddPage();
        $this->pdf->SetMargins(5, 0);
        $this->pdf->AliasNbPages();
        $this->pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->pdf->SetFont('Amble-Regular', '', 10);
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetFont('Amble-Regular', '', 9);
        $this->pdf->Ln(1);
    }

    public function registros($inicio, $fin)
    {
        $consultafecha = "";
        if (!empty($inicio) && !empty($fin)) {
            $consultafecha = "and r.fecha_ingreso between '$inicio' and '$fin'";
        }

        $sql = "
        select
        r.id_registro,
        r.nro_serie,
        r.fecha_ingreso,
        r.fecha_salida,
        te.descripcion,
        u.nombre_usuario,
        c.nombres_cli,
        c.identificacion,
        pt.total_proforma
        from 
        registro_equipo r
        left join proforma_tecnico pt
        on pt.id_registro=r.id_registro,
        clientes c,
        tipo_equipo te,
        usuario u
        where 
        r.id_cliente=c.id_cliente
        and r.estado='Activo'
        and te.id_tipo_equipo=r.id_tipo_equipo
        and u.id_usuario=r.id_usuario
        and (pt.id_factura is null and pt.id_facturas_novalidas is null and pt.estado_entrega is null)
        and r.id_empresa=$_GET[id]
        $consultafecha
        order by id_registro asc
        ";
        $res = pg_query($sql);
        $rows = pg_fetch_all($res);
        if (!$rows) {
            $rows = [];
        }
        $totalw = ($this->pdf->w - ($this->pdf->lMargin * 2));
        if (!empty($inicio) && !empty($fin)) {
            $this->pdf->Cell($totalw, 4, "Registros desde $inicio hasta $fin", 0, 0, "L");
            $this->pdf->Ln(5);
        }
        $colw = $totalw / 8;
        $this->pdf->SetWidths(array(
            $colw - 6,
            $colw + 30,
            $colw,
            $colw,
            $colw - 7,
            $colw - 7,
            $colw,
            $colw - 10
        ));
        $this->pdf->SetAligns(array(
            "C",
            "C",
            "C",
            "C",
            "C",
            "C",
            "C",
            "C"
        ));
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->Row(array(
            utf8_decode("N°. Reg."),
            "Cliente",
            "Equipo",
            utf8_decode("N°. Serie"),
            "F. Ingreso",
            "F. Salida",
            "Usuario",
            "Total"
        ), 1);
        $this->pdf->SetAligns(array(
            "C",
            "L",
            "L",
            "L",
            "C",
            "C",
            "C",
            "C"
        ));
        $this->pdf->SetFont('Amble-Regular', '', 8);
        $total = 0;
        foreach ($rows as $value) {
            $this->pdf->Row(array(
                $value["id_registro"],
                $value["nombres_cli"] . "(" . $value["identificacion"] . ")",
                $value["descripcion"],
                $value["nro_serie"],
                $value["fecha_ingreso"],
                $value["fecha_salida"],
                $value["nombre_usuario"],
                number_format($value["total_proforma"], 2, ',', '.')
            ), 1);
            $total += $value["total_proforma"];
        }
        $this->pdf->Ln(2);
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->SetWidths(array($totalw - $colw, $colw));
        $this->pdf->SetAligns(array("R", "R"));
        $this->pdf->Row(array("Total:", number_format($total, 2, ',', '.')));
    }

    public function imprimir($inicio, $fin)
    {
        $this->registros($inicio, $fin);
        $this->pdf->Output();
    }
}

$reporte = new Reporte();
$inicio = (empty($_GET["inicio"]) ? null : $_GET["inicio"]);
$fin = (empty($_GET["fin"]) ? null : $_GET["fin"]);
$reporte->imprimir($inicio, $fin);
