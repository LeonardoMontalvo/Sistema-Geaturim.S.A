<?php
//require('../fpdf/fpdf.php');
include __DIR__ . '/../../../fpdf/rotation.php';
include(__DIR__ . "/../../../fpdf/barcode.inc.php");
require_once(__DIR__ . '/../../../procesos/base.php');
require_once(__DIR__ . '/../../../procesos/funciones.php');

conectarse();
date_default_timezone_set('America/Guayaquil');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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

    function GetMultiCellHeight($w, $h, $txt, $border = null, $align = 'J')
    {
        // Calculate MultiCell with automatic or explicit line breaks height
        // $border is un-used, but I kept it in the parameters to keep the call
        //   to this function consistent with MultiCell()
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $ns = 0;
        $height = 0;
        while ($i < $nb) {
            // Get next character
            $c = $s[$i];
            if ($c == "\n") {
                // Explicit line break
                if ($this->ws > 0) {
                    $this->ws = 0;
                    $this->_out('0 Tw');
                }
                //Increase Height
                $height += $h;
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
                continue;
            }
            if ($c == ' ') {
                $sep = $i;
                $ls = $l;
                $ns++;
            }
            $l += $cw[$c];
            if ($l > $wmax) {
                // Automatic line break
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                    if ($this->ws > 0) {
                        $this->ws = 0;
                        $this->_out('0 Tw');
                    }
                    //Increase Height
                    $height += $h;
                } else {
                    if ($align == 'J') {
                        $this->ws = ($ns > 1) ? ($wmax - $ls) / 1000 * $this->FontSize / ($ns - 1) : 0;
                        $this->_out(sprintf('%.3F Tw', $this->ws * $this->k));
                    }
                    //Increase Height
                    $height += $h;
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
            } else
                $i++;
        }
        // Last chunk
        if ($this->ws > 0) {
            $this->ws = 0;
            $this->_out('0 Tw');
        }
        //Increase Height
        $height += $h;

        return $height;
    }

    function GetCurrentWidth()
    {
        return $this->w - ($this->lMargin * 2);
    }

    // Page header
    function Header()
    {
        // Logo
        $this->Image('../../../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 3, 1, 30);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 9);
        // Move to the right
        $this->setY(2);
        $this->Cell(47, 2);
        // Title
        // Line break
        $this->Ln(20);
    }

    function RotatedText($x, $y, $txt, $angle)
    {
        //Text rotated around its origin
        $this->Rotate($angle, $x, $y);
        $this->Text($x, $y, $txt);
        $this->Rotate(0);
    }

    function RotatedImage($file, $x, $y, $w, $h, $angle)
    {
        //Image rotated around its upper-left corner
        $this->Rotate($angle, $x, $y);
        $this->Image($file, $x, $y, $w, $h);
        $this->Rotate(0);
    }
}

$pdf = new PDF('P', 'mm', array(77, 400));
$pdf->AddPage();
$pdf->SetTitle('Nota de Credito');
$pdf->SetMargins(.5, 1);
$pdf->AliasNbPages();
$pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
$pdf->SetFont('Amble-Regular', '', 10);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX(5);
$pdf->SetFont('Amble-Regular', '', 9);
$pdf->Ln(0);
imprimirInfoNotaC($_GET['id']);
$pdf->Ln(3);
imprimirDetallesNotaC($_GET['id']);
$pdf->Output();

function imprimirInfoNotaC($id)
{
    global $pdf;
    $consulta = pg_query(
        "SELECT nombre_empresa, ruc_empresa, direccion_empresa, telefono_empresa, email_empresa, 
        obligacion, establecimiento, punto_emision, fecha_actual as fecha_emision, num_serie, 
        num_nota_credito, dv.clave, num_autorizacion, motivo, identificacion, nombres_cli, direccion_cli,
        dv.tipo_comprobante, dv.estado
        from empresa e inner join devolucion_venta dv using(id_empresa)
        inner join clientes c using(id_cliente)
        inner join tipo_documento t using(id_tdocu) 
        where dv.id_devolucion_venta='" . $id . "'"
    );
    while ($row = pg_fetch_assoc($consulta)) {
        if ($row["estado"] == 'Pasivo') {
            $pdf->SetTextColor(249, 33, 33);
            $pdf->Cell(77, 5, "ANULADO", 0, 1, "C");
            $pdf->SetTextColor(0, 0, 0);
        }
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->MultiCell(77, 4, $row['nombre_empresa'], 0, "C");
        $pdf->SetFont('Amble-Regular', '', 9);
        $pdf->Cell(77, 4, "RUC: $row[ruc_empresa]", 0, 1);
        $pdf->MultiCell(77, 4, utf8_decode("Dir. Matriz: " . $row['direccion_empresa']), 0);
        $pdf->MultiCell(77, 4, utf8_decode("Dir. Sucursal: " . $row['direccion_empresa']), 0);
        $pdf->Cell(77, 4, "Contribuyente Regimen Rimpe - Emprendedor", 0, 1);
        $pdf->Cell(77, 4, "Obligado a llevar Contabilidad: $row[obligacion]", 0, 1);
        $pdf->Ln(2);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(77, 4, utf8_decode("NOTA DE CRÉDITO"), 0, 1, "C");
        $pdf->SetFont('Amble-Regular', '', 9);
        if ($row["tipo_comprobante"] == 'FACTURA') {
            $pdf->SetFont('Amble-Regular', '', 8);
        }
        $pdf->Cell(38.5, 4, "No. $row[establecimiento]-$row[punto_emision]-$row[num_nota_credito]", 0, 0);
        if ($row["tipo_comprobante"] == 'FACTURA') {
            $pdf->Cell(38.5, 4, utf8_decode("F. Autorización: $row[fecha_emision]"), 0, 1);
            $pdf->SetFont('Amble-Regular', '', 9);
            $pdf->Cell(77, 4, utf8_decode("Num. Autorización:"), 0, 1);
            $numeroAutorizacion = $row['num_autorizacion'];
            if ($numeroAutorizacion == "") {
                $pdf->Cell(77, 4, $row['clave'], 0, 1);
            } else {
                $pdf->Cell(77, 4, $row['num_autorizacion'], 0, 1);
            }
            $consulta_ambiente = pg_query("select nombre_ambi from ambiente where id_ambi=2  ");
            while ($row1 = pg_fetch_row($consulta_ambiente)) {
                $nombre_ambi = $row1[0];
            }
            if ($row["tipo_comprobante"] == 'FACTURA') {
                $pdf->SetFont('Amble-Regular', '', 8);
            }
            $pdf->Cell(38.5, 4, utf8_decode("Ambiente: $nombre_ambi"), 0, 0);
            $consulta_emision = pg_query("select nombre_temision from tipo_emision  where id_temision=1 ");
            while ($row2 = pg_fetch_row($consulta_emision)) {
                $nombre_emi = $row2[0];
            }
            $pdf->Cell(38.5, 4, utf8_decode("Emisión: $nombre_emi"), 0, 1);
            $pdf->SetFont('Amble-Regular', '', 9);
            $pdf->Cell(77, 4, utf8_decode("Clave de Acceso:"), 0, 1);
            $code_number = $row["clave"]; // Código de barras		
            new barCodeGenrator($code_number, 1, 'temp.gif', 470, 60, true); /// img codigo barras	
            $pdf->Image('temp.gif', 0, $pdf->GetY(), 77, 15);
            $pdf->Ln(17);
        } else {
            $pdf->Ln(4);
        }

        if ($row["tipo_comprobante"] == 'FACTURA') {
            $consultafecha_actual = pg_query("select * from factura_venta where num_factura='$row[num_serie]' ");
            while ($row3 = pg_fetch_row($consultafecha_actual)) {
                $fecha_actual_fac = $row3[6];

                $date = new DateTime($fecha_actual_fac);
                $fecha_actual_fac = $date->format('d/m/Y');
            }
        } else {
            $consultafecha_actual = pg_query("select fecha_actual from facturas_novalidas where comprobante='$row[num_serie]' ");
            while ($row3 = pg_fetch_row($consultafecha_actual)) {
                $fecha_actual_fac = $row3[0];

                $date = new DateTime($fecha_actual_fac);
                $fecha_actual_fac = $date->format('d/m/Y');
            }
        }

        $pdf->MultiCell(77, 4, utf8_decode('Razón Social / Nombres y Apellidos: ' . $row['nombres_cli']), 0, 1);
        $pdf->Cell(77, 4, utf8_decode("Identificación: $row[identificacion]"), 0, 1);
        $pdf->Cell(77, 4, utf8_decode("Fecha de Emisión: $row[fecha_emision]"), 0, 1);
        $pdf->Cell(77, 4, "Comprobante que se Modifica:", 0, 1);
        $tipocomp = $row["tipo_comprobante"];
        if ($tipocomp == 'NOTA') {
            $tipocomp = 'NOTA V.';
        }
        $pdf->SetFont('Amble-Regular', '', 7);
        $pdf->Cell(77, 4, utf8_decode("$tipocomp: No. $row[establecimiento]-$row[punto_emision]-$row[num_serie] - F. Emisión: $fecha_actual_fac"), 0, 1);
        $pdf->SetFont('Amble-Regular', '', 9);
        $pdf->Cell(77, 4, utf8_decode("Razón de Modificación:"), 0, 1);
        $pdf->MultiCell(77, 4, $row["motivo"]);
    }
}
function imprimirDetallesNotaC($id)
{
    global $pdf;
    $sql = pg_query(
        "SELECT id_devolucion_venta, 
        num_serie,fecha_actual, 
        tarifa0,tarifa12,iva_venta,descuento_venta,total_venta,
        clientes.id_cliente,identificacion,nombres_cli,direccion_cli,telefono,
        devolucion_venta.estado,ciudad,celular 
        FROM devolucion_venta,clientes 
        WHERE id_devolucion_venta = '" . $_GET['id'] . "' and devolucion_venta.id_cliente = clientes.id_cliente"
    );
    while ($row = pg_fetch_row($sql)) {
        $iva0 = $row[3];
        $iva12 = $row[4];
        $iva_venta = $row[5];
        $descuento_venta = $row[6];
        $total_venta = $row[7];
    }

    $sql = pg_query(
        "SELECT cantidad, articulo, precio_venta, total_venta, productos.iva 
        FROM  detalle_devolucion_venta,productos 
        WHERE id_devolucion_venta = '" . $id . "' and detalle_devolucion_venta.cod_productos = productos.cod_productos"
    );
    $pdf->SetTextColor(0, 0, 0);

    $pdf->SetWidths([14, 47, 17, 17]);

    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Row([
        utf8_decode("Cant"),
        utf8_decode("Descripción"),
        "Pre.Uni",
        "Total"
    ], 1);
    $pdf->SetFont('Amble-Regular', '', 9);
    while ($row = pg_fetch_row($sql)) {
        if ($row[4] == 'Si') {
            $pdf->Row([
                utf8_decode($row[0]),
                utf8_decode($row[1]),
                number_format($row[2], 2, ',', '.'),
                number_format($row[3], 2, ',', '.') . "*"
            ]);
        } else {
            $pdf->Row([
                utf8_decode($row[0]),
                utf8_decode($row[1]),
                number_format($row[2], 2, ',', '.'),
                number_format($row[3], 2, ',', '.')
            ]);
        }

        $pdf->SetX(1);
    }

    /////////pie
    $subtotal = $iva12 + $iva0;
    $subtotal = round($subtotal, 2);
    $descuento_venta = round($descuento_venta, 2);
    $iva_venta = round($iva_venta, 2);
    $iva0 = round($iva0, 2);
    $total_venta = round($total_venta, 2);
    $result1 = substr("$total_venta", -1, 1);
    $result2 = substr("$total_venta", -2, 1);
    $result3 = substr("$total_venta", -3, 1);
    $result4 = substr("$total_venta", -4, 1);
    $result5 = substr("$total_venta", -5, 1);
    $subtotal = number_format($subtotal, 2, ',', '.');
    $total_venta = number_format($total_venta, 2, ',', '.');
    $descuento_venta = number_format($descuento_venta, 2, ',', '.');
    $iva0 = number_format($iva0, 2, ',', '.');
    $iva_venta = number_format($iva_venta, 2, ',', '.');

    $pdf->Ln(5);
    $pdf->SetX(45);
    $pdf->SetWidths([50]);


    $pdf->Row([utf8_decode("Subtotal:      " . $subtotal)]);
    $pdf->SetX(45);
    $pdf->Row([utf8_decode("Descuento:  " . $descuento_venta)]);
    $pdf->SetX(45);
    $pdf->Row([utf8_decode("Tarifa 0%:   " . $iva0)]);
    $pdf->SetX(45);
    $pdf->Row([utf8_decode("Tarifa 12%: " . $iva_venta)]);
    $pdf->SetX(45);
    if ($result1 == "." || $result2 == "." || $result3 == "." || $result4 == "." || $result5 == ".") {
        $pdf->Row(["TOTAL:         " . $total_venta]);
    } else {
        $total_ventacero = $total_venta;
        $pdf->Row(["TOTAL:         " . $total_ventacero]);
    }
}
