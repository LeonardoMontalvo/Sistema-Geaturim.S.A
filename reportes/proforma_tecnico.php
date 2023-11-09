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
        $this->Cell(180, 5, utf8_decode($pais), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(1, 45, 210, 45);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(182, 5, utf8_decode("PROFORMA TECNICO"), 0, 1, 'C', 0);
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
        $this->pdf->SetMargins(0, 0, 0, 0);
        $this->pdf->AliasNbPages();
        $this->pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->pdf->SetFont('Amble-Regular', '', 10);
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetX(5);
        $this->pdf->SetFont('Amble-Regular', '', 9);
    }

    public function datosProforma()
    {
        $sql = "
      select * 
        from 
        registro_equipo,
        clientes,
        usuario,
        proforma_tecnico,vendedores
        where 
        proforma_tecnico.id_registro=registro_equipo.id_registro and
        registro_equipo.id_cliente=clientes.id_cliente and
        registro_equipo.id_usuario=usuario.id_usuario and
         vendedores.id_vendedor=proforma_tecnico.id_vendedor and
        proforma_tecnico.id_proforma=$_GET[id]
        ";
        $sql = pg_query($sql);
        $rows = pg_fetch_all($sql);
        if ($rows) {
            foreach ($rows as $row) {
                $this->pdf->Ln(1);
                $this->pdf->SetX(3);

                $this->pdf->SetX(3);
                $this->pdf->Cell(30, 5, "CLIENTE.: ", 0, 0, 'L', 0);
                $this->pdf->Cell(90, 5, maxCaracter(utf8_decode($row["nombres_cli"]) . "(" . $row["identificacion"] . ")", 40), 0, 0, 'L', 0);
                $this->pdf->Cell(30, 5, "REGISTRO NRO.: ", 0, 0, 'L', 0);
                $this->pdf->Cell(52, 5,  utf8_decode($row["id_registro"]), 0, 1, 'L', 0);

                $this->pdf->SetX(3);
                $this->pdf->Cell(30, 5, "TEL.: ", 0, 0, 'L', 0);
                $this->pdf->Cell(90, 5, maxCaracter(utf8_decode($row["telefono"]), 40), 0, 0, 'L', 0);
                $this->pdf->Cell(30, 5, "FECHA REGISTRO: ", 0, 0, 'L', 0);
                $this->pdf->Cell(52, 5, maxCaracter(utf8_decode($row["fecha_ingreso"]), 10), 0, 1, 'L', 0);

                $this->pdf->SetX(3);
                $this->pdf->Cell(30, 5, "CEL.: ", 0, 0, 'L', 0);
                $this->pdf->Cell(90, 5, maxCaracter(utf8_decode($row["celular"]), 40), 0, 0, 'L', 0);
                $this->pdf->Cell(30, 5, "REGISTRADO: ", 0, 0, 'L', 0);
                $this->pdf->Cell(52, 5, maxCaracter($row["nombre_usuario"] . "(" . $row["ci_usuario"] . ")", 40), 0, 1, 'L', 0);

                $this->pdf->SetX(3);
                $this->pdf->Cell(30, 5, "DIR..: ", 0, 0, 'L', 0);
                $this->pdf->Cell(90, 5, maxCaracter(utf8_decode($row["direccion_cli"]), 40), 0, 0, 'L', 0);
                $this->pdf->Cell(30, 5, "TEL/CEL.: ", 0, 0, 'L', 0);
                $this->pdf->Cell(52, 5, maxCaracter($row["telefono_usuario"] . "/" . $row["celular_usuario"], 40), 0, 1, 'L', 0);

                $this->pdf->SetX(3);
                $this->pdf->Cell(30, 5, "CORREO: ", 0, 0, 'L', 0);
                $this->pdf->Cell(90, 5, maxCaracter(utf8_decode($row["correo"]), 40), 0, 0, 'L', 0);
                $this->pdf->Cell(30, 5, "", 0, 0, 'L', 0);
                $this->pdf->Cell(52, 5, maxCaracter("", 40), 0, 1, 'L', 0);

                $this->pdf->Ln(5);
                $this->pdf->SetX(3);
                
                $colw=$this->pdf->w/4;
                $this->pdf->SetWidths(array(
                    $colw,
                    $colw+15,
                    $colw-23,
                    $colw
                ));
                $this->pdf->SetAligns(array("L","L","L","L"));
                $this->pdf->Row(array("OBSERVACIONES: ",utf8_decode($row["observaciones"]),"ACCESORIOS: ",utf8_decode($row["detalles"])));
                     $this->pdf->SetX(5);
                $this->pdf->Row(array("VENDEDOR: ",utf8_decode($row["nombre_vendedor"]),""));

                $this->pdf->SetY($this->pdf->GetY() - 4);
                $this->pdf->SetX(33);
            }
            //$this->pdf->Line(1, $this->pdf->GetY(), 297, $this->pdf->GetY());
        }
    }

    public function productos()
    {
        $this->pdf->SetMargins(3,0,3);
        $this->pdf->SetX(3);

        $sql = "select * from 
        detalle_proforma_tecnico,
        productos 
        where id_proforma=$_GET[id]  and 
        detalle_proforma_tecnico.cod_productos=productos.cod_productos 
        order by id_detalle_proforma asc;";
        $res = pg_query($sql);
        $rows = pg_fetch_all($res);
        if (!$rows) {
            $rows = [];
        }
        $colw = ($this->pdf->w-6) / 6;
        $this->pdf->SetWidths(array(
            $colw,
            $colw + 40,
            $colw - 10,
            $colw - 10,
            $colw - 10,
            $colw - 10
        ));
        $this->pdf->SetAligns(array("C", "C", "C", "C", "C", "C"));
        $this->pdf->Row(array(
            utf8_decode("Código"),
            utf8_decode("Producto"),
            utf8_decode("Cantidad"),
            utf8_decode("PVP"),
            utf8_decode("Descuento"),
            utf8_decode("Total")
        ), 1);
        $this->pdf->SetAligns(array("L", "L", "C", "C", "C", "C"));

        foreach ($rows as $row) {
            $this->pdf->Row(array(
                utf8_decode($row["codigo"]),
                utf8_decode($row["articulo"]),
                utf8_decode($row["cantidad"]),
                utf8_decode(round($row["precio_venta"],2)),
                utf8_decode($row["descuento_venta"]),
                utf8_decode(round($row["total_venta"],2))
            ));
        }
    }

    public function totales()
    {
        $sql = "select * from proforma_tecnico
        where id_proforma=$_GET[id]
        ";
        $res = pg_query($sql);
        $rows = pg_fetch_all($res);
        if (!$rows) {
            $rows = [];
        }
        $row=$rows[0];

        $this->pdf->SetX(1);
        $this->pdf->Ln(5);
        $this->pdf->Cell(204, 0, utf8_decode(""), 1, 1, 'R', 0);
        $this->pdf->Cell(185, 6, utf8_decode("Tarifa 0%: "), 0, 0, 'R', 0);
        $this->pdf->Cell(25, 6, maxCaracter((number_format($row["tarifa0"], 2, ',', '.')), 20), 0, 1, 'L', 0);
        $this->pdf->SetX(1);
        $this->pdf->Cell(185, 6, utf8_decode("Tarifa IVA: "), 0, 0, 'R', 0);
        $this->pdf->Cell(25, 6, maxCaracter((number_format($row["tarifa12"], 2, ',', '.')), 20), 0, 1, 'L', 0);
        $this->pdf->SetX(1);
        $this->pdf->Cell(185, 6, utf8_decode("Iva ...%: "), 0, 0, 'R', 0);
        $this->pdf->Cell(25, 6, maxCaracter((number_format($row["iva_proforma"], 2, ',', '.')), 20), 0, 1, 'L', 0);
        $this->pdf->SetX(1);
        $this->pdf->Cell(185, 6, utf8_decode("Descuento: "), 0, 0, 'R', 0);
        $this->pdf->Cell(25, 6, maxCaracter((number_format($row["descuento_proforma"], 2, ',', '.')), 20), 0, 1, 'L', 0);
        $this->pdf->SetX(1);
        
        $this->pdf->SetFont("Arial", "B",11);

        $this->pdf->Cell(185, 6, utf8_decode("Totales: "), 0, 0, 'R', 0);
        $this->pdf->Cell(25, 6, maxCaracter((number_format($row["total_proforma"], 2, ',', '.')), 20), 0, 1, 'L', 0);
        $this->pdf->SetFont('Amble-Regular', '', 9);
    }

    public function imprimir()
    {
        $this->datosProforma();
        $this->pdf->Ln(5);
        $this->productos();
        $this->totales();
        $this->pdf->output();
    }
}

$reporte = new Reporte();
$reporte->imprimir();

exit();

$pdf = new PDF('P', 'mm', 'a4');
$pdf->AddPage();
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AliasNbPages();
$pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
$pdf->SetFont('Amble-Regular', '', 10);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX(5);
$pdf->SetFont('Amble-Regular', '', 9);


$sql = pg_query("select * from proforma_tecnico,clientes,usuario,empresa where proforma_tecnico.id_cliente=clientes.id_cliente and proforma_tecnico.id_usuario=usuario.id_usuario and proforma_tecnico.id_empresa=empresa.id_empresa and id_proforma='$_GET[id]'");
while ($row = pg_fetch_row($sql)) {
    $temp1 = $row[8];
    $temp2 = $row[9];
    $temp3 = $row[10];
    $temp4 = $row[11];
    $temp5 = $row[12];
    $pdf->SetX(1);
    $pdf->Cell(20, 6, utf8_decode('Cliente: '), 0, 0, 'L', 0);
    $pdf->Cell(85, 6, maxCaracter(utf8_decode($row[18]), 40), 0, 0, 'L', 0);
    $pdf->Cell(20, 6, utf8_decode('CI/RUC: '), 0, 0, 'L', 0);
    $pdf->Cell(25, 6, utf8_decode($row[17]), 0, 0, 'L', 0);
    $pdf->Cell(25, 6, utf8_decode('Nro Factura: '), 0, 0, 'L', 0);
    $pdf->Cell(30, 6, utf8_decode($row[0]), 0, 1, 'L', 0);
    $pdf->Ln(1);
    $pdf->SetX(1);
    $pdf->Cell(20, 6, utf8_decode('Dirección:'), 0, 0, 'L', 0);
    $pdf->Cell(60, 6, maxCaracter(utf8_decode($row[20]), 30), 0, 0, 'L', 0);
    $pdf->Cell(20, 6, utf8_decode('Email:'), 0, 0, 'L', 0);
    $pdf->Cell(60, 6, maxCaracter(utf8_decode($row[25]), 30), 0, 0, 'L', 0);
    $pdf->Cell(20, 6, utf8_decode('Celular:'), 0, 0, 'L', 0);
    $pdf->Cell(25, 6, utf8_decode($row[22]), 0, 1, 'L', 0);
    $pdf->Ln(1);
    $pdf->SetX(1);
    $pdf->Cell(20, 6, utf8_decode('Responsable:'), 0, 0, 'L', 0);
    $pdf->Cell(130, 6, maxCaracter(utf8_decode($row[31] . ' ' . $row[32]), 80), 0, 0, 'L', 0);
    $pdf->Cell(25, 6, utf8_decode('Celular:'), 0, 0, 'L', 0);
    $pdf->Cell(30, 6, utf8_decode($row[35]), 0, 1, 'L', 0);
}
$pdf->Ln(3);

$pdf->SetX(1);
$pdf->Cell(40, 6, utf8_decode('Código'), 1, 0, 'C', 0);
$pdf->Cell(65, 6, utf8_decode('Producto'), 1, 0, 'C', 0);
$pdf->Cell(25, 6, utf8_decode('Cantidad'), 1, 0, 'C', 0);
$pdf->Cell(25, 6, utf8_decode('PVP'), 1, 0, 'C', 0);
$pdf->Cell(25, 6, utf8_decode('Descuento'), 1, 0, 'C', 0);
$pdf->Cell(25, 6, utf8_decode('Total'), 1, 1, 'C', 0);
$sql = pg_query("select * from detalle_proforma_tecnico,productos where id_proforma='$_GET[id]'  and productos.incluye_iva = 'No' and detalle_proforma_tecnico.cod_productos=productos.cod_productos order by id_detalle_proforma asc;");
while ($row = pg_fetch_row($sql)) {
    $pdf->SetX(1);
    $pdf->Cell(40, 6, maxCaracter(utf8_decode($row[9]), 15), 0, 0, 'L', 0);
    $pdf->Cell(65, 6, maxCaracter(utf8_decode($row[11]), 30), 0, 0, 'L', 0);
    $pdf->Cell(25, 6, utf8_decode($row[3]), 0, 0, 'C', 0);
    $pdf->Cell(25, 6, utf8_decode($row[4]), 0, 0, 'C', 0);
    $pdf->Cell(25, 6, utf8_decode($row[5]), 0, 0, 'C', 0);
    $pdf->Cell(25, 6, utf8_decode($row[6]), 0, 1, 'C', 0);
}
$calculoIVA = pg_query("select valor from parametros where descripcion='IVA'");
while ($rowi = pg_fetch_row($calculoIVA)) {
    $iva_base = $rowi[0];
}
$iva_base = ($iva_base / 100) + 1;
$sql = pg_query("select * from detalle_proforma_tecnico,productos where id_proforma='$_GET[id]' and productos.incluye_iva = 'Si'  and detalle_proforma_tecnico.cod_productos=productos.cod_productos order by id_detalle_proforma asc;");
while ($row = pg_fetch_row($sql)) {
    $pdf->SetX(1);
    $total_si = 0;
    $total_sit = 0;
    $total_si = $row[6] / $iva_base;
    $total_sit = $total_si / $row[3];

    $total_si = truncateFloat($total_si, 2);
    $total_sit = truncateFloat($total_sit, 2);

    $pdf->Cell(40, 6, maxCaracter(utf8_decode($row[9]), 15), 0, 0, 'L', 0);
    $pdf->Cell(65, 6, maxCaracter(utf8_decode($row[11]), 30), 0, 0, 'L', 0);

    $pdf->Cell(25, 6, utf8_decode($row[3]), 0, 0, 'C', 0);
    $pdf->Cell(25, 6, utf8_decode($total_sit), 0, 0, 'C', 0);
    $pdf->Cell(25, 6, utf8_decode($row[5]), 0, 0, 'C', 0);
    $pdf->Cell(25, 6, utf8_decode($total_si), 0, 1, 'C', 0);
}
$pdf->SetX(1);
$pdf->Ln(5);
$pdf->Cell(207, 0, utf8_decode(""), 1, 1, 'R', 0);
$pdf->Cell(181, 6, utf8_decode("Tarifa 0%"), 0, 0, 'R', 0);
$pdf->Cell(25, 6, maxCaracter((number_format($temp1, 2, ',', '.')), 20), 0, 1, 'C', 0);
$pdf->SetX(1);
$pdf->Cell(181, 6, utf8_decode("Tarifa IVA"), 0, 0, 'R', 0);
$pdf->Cell(25, 6, maxCaracter((number_format($temp2, 2, ',', '.')), 20), 0, 1, 'C', 0);
$pdf->SetX(1);
$pdf->Cell(181, 6, utf8_decode("Iva ...%"), 0, 0, 'R', 0);
$pdf->Cell(25, 6, maxCaracter((number_format($temp3, 2, ',', '.')), 20), 0, 1, 'C', 0);
$pdf->SetX(1);
$pdf->Cell(181, 6, utf8_decode("Descuento"), 0, 0, 'R', 0);
$pdf->Cell(25, 6, maxCaracter((number_format($temp4, 2, ',', '.')), 20), 0, 1, 'C', 0);
$pdf->SetX(1);
$pdf->Cell(181, 6, utf8_decode("Totales"), 0, 0, 'R', 0);
$pdf->Cell(25, 6, maxCaracter((number_format($temp5, 2, ',', '.')), 20), 0, 1, 'C', 0);

$pdf->Ln(3);
$pdf->Output();
