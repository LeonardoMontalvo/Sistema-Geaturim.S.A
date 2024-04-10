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

    function Header()
    {
        $this->rango = false;
        if ($_GET['inicio'] != '') {
            $this->rango = true;
        }
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->AddFont('helvetica', 'B', 'helveticab.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(0);
        $this->SetY(0);
        $this->Cell(105, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(105, 5, "VENTAS", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(210, 8, utf8_decode($_SESSION['nombre_empresa']), 0, 1, 'C', 0);
        $this->Image('../images/logo.png', 10, 7, 15, 15);
        $this->Image('../images/logo.png', 180, 7, 15, 15);
        // $this->Cell(180, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        // $this->Cell(80, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        // $this->Cell(80, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(0, 25, 210, 25);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 5, utf8_decode('FACTURAS POR CLIENTE'), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->SetFillColor(220, 240, 210);
        $this->SetX(0);
        $this->cliente = false;

        /*  $this->Ln(3);
          $this->SetFont('helvetica', 'B', 9);
          $this->SetFillColor(175, 215, 240);
          $this->Cell(22, 6, utf8_decode('COMPRO.'), 1, 0, 'C', 1);
          $this->Cell(22, 6, utf8_decode('FECHA'), 1, 0, 'C', 1);
          $this->Cell(25, 6, utf8_decode('NÚMERO'), 1, 0, 'C', 1);
          $this->Cell(17, 6, utf8_decode('SUB'), 1, 0, 'C', 1);
          $this->Cell(17, 6, utf8_decode('DESC'), 1, 0, 'C', 1);
          $this->Cell(15.5, 6, utf8_decode('0%'), 1, 0, 'C', 1);
          $this->Cell(15.5, 6, utf8_decode('15%'), 1, 0, 'C', 1);
          $this->Cell(17, 6, utf8_decode('IVA'), 1, 0, 'C', 1);
          $this->Cell(17, 6, utf8_decode('TOTAL'), 1, 0, 'C', 1);
          $this->Cell(22, 6, utf8_decode('PAGO'), 1, 0, 'C', 1);
          $this->Cell(20, 6, utf8_decode('TIPO'), 1, 1, 'C', 1); */
        $this->Ln(1);
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

$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetTitle('Ventas por Cliente');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();
$total = 0;
$sub = 0;
$desc = 0;
$ivaT = 0;
$t0 = 0;
$contador = 0;
$query_fecha = "";
// RANGO DE FECHAS O FECHA ACTUAL
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}
if ($_GET['id1'] != '') {

    $query = pg_query("select * from clientes where id_cliente={$_GET['id1']} and estado ='Activo' order by id_cliente asc");
} else {
    $query = pg_query("select * from clientes where estado='Activo' order by id_cliente asc");
}
while ($row = pg_fetch_row($query)) {
    $consulta1 = pg_query("select FV.num_factura,
    FV.fecha_actual,
    FV.hora_actual,
    fecha_cancelacion,
    tipo_precio,
    forma_pago,
    tarifa0,
    tarifa12,
    iva_venta,
    descuento_venta, "
        . "total_venta,
    identificacion,
    nombres_cli,
    nombre_empresa,
    id_factura_venta,
    FV.estado,
    U.nombre_usuario, marca_vehiculo "
        . "FROM factura_venta FV INNER JOIN clientes C ON C.id_cliente = FV.id_cliente "
        . "INNER JOIN empresa E ON E.id_empresa = FV.id_empresa "
        . "INNER JOIN usuario U ON U.id_usuario = FV.id_usuario "
        . "WHERE FV.id_empresa='$_GET[id]' and FV.id_cliente='{$row[0]}' and FV.fecha_actual $query_fecha '$_GET[fin]' "
        . "order by FV.id_factura_venta asc");
    $contador = pg_num_rows($consulta1);
    if($contador>0){
        $pdf->Ln(2);
        $pdf->SetX(0);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetFillColor(220, 240, 210);
        $pdf->Cell(110, 8, maxCaracter(utf8_decode('RUC/CI:' . $row[2]), 35), 0, 0, 'L', 1);
        $pdf->Cell(100, 8, maxCaracter(utf8_decode('NOMBRES:' . $row[3]), 50), 0, 1, 'L', 1);
        $pdf->SetFont('helvetica', '', 9);
        cabeceraTabla();
        registrosTabla(pg_fetch_all($consulta1));
    }
    
    /* if ($contador > 0) {
      $repetido = 0;
      while ($row1 = pg_fetch_row($consulta1)) {
      if ($repetido == 0) {
      $repetido = 1;
      }
      if ($row1[15] == "Activo") {
      $pdf->SetTextColor(0, 0, 0);
      $pdf->SetFont('helvetica', '', 9);
      $pdf->SetX(0);
      $pdf->Cell(22, 6, utf8_decode($row1[1]), 0, 0, 'C', 0);
      $pdf->Cell(22, 6, utf8_decode($row1[14]), 0, 0, 'C', 0);
      $pdf->Cell(25, 6, utf8_decode($row1[0]), 0, 0, 'C', 0);
      $sub = $sub + ($row1[10] - $row1[8] + $row1[9]);
      $pdf->Cell(17, 6, number_format($row1[10] - $row1[8] + $row1[9], 2, '.', ''), 0, 0, 'C', 0);
      $desc = $desc + $row1[9];
      $pdf->Cell(17, 6, number_format($row1[9], 2, '.', ''), 0, 0, 'C', 0);
      $pdf->Cell(15.5, 6, number_format($row1[6], 2, '.', ''), 0, 0, 'C', 0);
      $pdf->Cell(15.5, 6, number_format($row1[7], 2, '.', ''), 0, 0, 'C', 0);
      $ivaT = $ivaT + $row1[8];
      $pdf->Cell(17, 6, number_format($row1[8], 2, '.', ''), 0, 0, 'C', 0);
      $total = $total + $row1[10];
      $t0 = $row1[6];
      $pdf->Cell(17, 6, number_format($row1[10], 2, '.', ''), 0, 0, 'C', 0);
      $pdf->Cell(22, 6, $row1[3], 0, 0, 'C', 0);
      $pdf->Cell(20, 6, $row1[5], 0, 0, 'C', 0);
      $pdf->Ln(6);
      } elseif ($row1[15] == "Pasivo") {
      $pdf->SetTextColor(208, 17, 52);
      $pdf->SetFont('helvetica', '', 9);
      $pdf->SetX(0);
      $pdf->Cell(22, 6, utf8_decode($row1[14]), 0, 0, 'C', 0);
      $pdf->Cell(22, 6, utf8_decode($row1[1]), 0, 0, 'C', 0);
      $pdf->Cell(25, 6, utf8_decode($row1[0]), 0, 0, 'C', 0);
      $pdf->Cell(17, 6, number_format($row1[10] - $row1[8] - $row1[9], 2, '.', ''), 0, 0, 'C', 0);
      $pdf->Cell(17, 6, number_format($row1[9], 2, '.', ''), 0, 0, 'C', 0);
      $pdf->Cell(15.5, 6, number_format($row1[6], 2, '.', ''), 0, 0, 'C', 0);
      $pdf->Cell(15.5, 6, number_format($row1[7], 2, '.', ''), 0, 0, 'C', 0);
      $pdf->Cell(17, 6, number_format($row1[8], 2, '.', ''), 0, 0, 'C', 0);
      $pdf->Cell(17, 6, number_format($row1[10], 2, '.', ''), 0, 0, 'C', 0);
      $pdf->Cell(22, 6, $row1[3], 0, 0, 'C', 0);
      $pdf->Cell(20, 6, $row1[5], 0, 0, 'C', 0);
      $pdf->Ln(6);
      }
      }
      } */
}
/* if ($contador > 0) {
  $pdf->SetTextColor(0, 0, 0);
  $pdf->SetFont('helvetica', 'B', 9);
  $pdf->SetX(0);
  $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
  $pdf->Cell(69, 6, utf8_decode("Totales"), 0, 0, 'R', 0);
  $pdf->Cell(17, 6, maxCaracter((number_format($sub, 2, ',', '.')), 20), 0, 0, 'C', 0);
  $pdf->Cell(17, 6, maxCaracter((number_format($desc, 2, ',', '.')), 20), 0, 0, 'C', 0);
  $pdf->Cell(15.5, 6, maxCaracter((number_format($t0, 2, ',', '.')), 20), 0, 0, 'C', 0);
  $pdf->Cell(15.5, 6, maxCaracter((number_format($sub - $desc, 2, ',', '.')), 20), 0, 0, 'C', 0);
  $pdf->Cell(17, 6, maxCaracter((number_format($ivaT, 2, ',', '.')), 20), 0, 0, 'C', 0);
  $pdf->Cell(17, 6, maxCaracter((number_format($total, 2, ',', '.')), 20), 0, 0, 'C', 0);
  $pdf->Ln(8);
  } */
$pdf->Output();

function cabeceraTabla()
{
    global $pdf;
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Ln(0);
    $pdf->SetX(0);
    $totalw = $pdf->GetCurrentWidth();
    $colw = $totalw / 7;
    $pdf->SetWidths([
        $colw - 5, $colw - 5, $colw - 5, $colw + 30,
        $colw - 5, $colw - 5, $colw
    ]);
    $pdf->SetAligns(array_fill(0, 8, "C"));
    $pdf->Row([
        "Fecha",
        "Comp",
        "Nro. Fac",
        "Tarifa 0%",
        "Tarifa 15%",
        "IVA",
        "Total"
    ], 1);
}

function registrosTabla($rows)
{
    global $pdf;
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Ln(0);
    $pdf->SetX(0);
    $totalw = $pdf->GetCurrentWidth();
    $colw = $totalw / 7;

    $tiva0 = 0;
    $tiva12 = 0;
    $tiva = 0;
    $total = 0;

    $pdf->SetWidths([
        $colw - 5,
        $colw - 5,
        $colw - 5,
        $colw + 30,
        $colw - 5,
        $colw - 5,
        $colw,
    ]);
    $pdf->SetAligns(array_fill(0, 8, "C"));

    if (!!$rows) {
        foreach ($rows as $val) {
            if ($val["estado"] == "Activo") {
                $pdf->Row([
                    $val["fecha_actual"],
                    $val["id_factura_venta"],
                    $val["num_factura"],
                    round($val["tarifa0"], 2),
                    round($val["tarifa12"], 2),
                    round($val["iva_venta"], 2),
                    round($val["total_venta"], 2)
                ]);
                $tiva0 += round($val["tarifa0"], 2);
                $tiva12 += round($val["tarifa12"], 2);
                $total += round($val["total_venta"], 2);
                $tiva += round($val["iva_venta"], 2);
            }
        }
    }


    $pdf->SetAligns([
        "C", "C", "R", "C", "C", "C", "C"
    ]);

    $pdf->Line(0, $pdf->GetY(), $totalw, $pdf->GetY());
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Row([
        "",
        "",
        "Totales: ",
        $tiva0,
        $tiva12,
        $tiva,
        $total
    ]);
    $pdf->SetFont('helvetica', '', 9);
}
