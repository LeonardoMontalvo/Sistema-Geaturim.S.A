<?php

require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();

class PDF extends FPDF {

    var $widths;
    var $aligns;
    var $textColors;

    function SetWidths($w) {
        //Set the array of column widths
        $this->widths = $w;
    }

    function SetAligns($a) {
        //Set the array of column alignments
        $this->aligns = $a;
    }

    function SetTextColors($tc) {
        $this->textColors = $tc;
    }

    function Row($data, $border = 0, $style = "", $fill = false) {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 5 * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            if (!empty($this->textColors[$i])) {
                $rgb = $this->textColors[$i];
                $this->SetTextColor($rgb[0], $rgb[1], $rgb[2]);
            }

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

            $this->SetTextColor(0, 0, 0);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h) {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt) {
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

    function GetMultiCellHeight($w, $h, $txt, $border = null, $align = 'J') {
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

    function GetCurrentWidth() {
        return $this->w - ($this->lMargin * 2);
    }

    function Header() {
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
        $this->Cell(105, 5, "CONTABILIDAD", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(210, 8, $_SESSION['nombre_empresa'], 0, 1, 'C', 0);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 10, 7, 15, 15);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 180, 7, 15, 15);
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
        $this->Cell(210, 5, utf8_decode('LIBRO DIARIO'), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(2);
        $this->SetX(5);
        $this->SetFillColor(175, 215, 240);
        $this->SetFont('helvetica', 'B', 10);
        $this->Cell(40, 6, utf8_decode('CODIGO'), 1, 0, 'C', 1);
        $this->Cell(120, 6, utf8_decode('DESCRIPCION'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('DEBE'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('HABER'), 1, 1, 'C', 1);
        $this->Ln(1);
        $this->SetFillColor(255, 255, 225);
        $this->SetLineWidth(0.2);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

}

$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetMargins(0, 0);
$pdf->AddPage();
$pdf->SetTitle('Libro Diario');
$pdf->AliasNbPages();
$pdf->SetFont('helvetica', 'B', 9.5);

$total_debe = 0;
$total_haber = 0;
$query_fecha = "";
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}
// filtro tipo
$query_tipo = "";
if ($_GET['tipo'] != '0') {
    $query_tipo = "AND identificador_cli_pro='$_GET[tipo]'";
}
$query_punto = "";

if ($_GET['id_empre'] != '0') {
    $query_punto = "AND t.id_empresa='$_GET[id_empre]'";
}
$query = pg_query(
        "

     SELECT t.id_transacciones,t.concepto as concepto_trans, string_agg(dg.concepto,',') as concepto_gasto,string_agg(pro.articulo,',') as articulo_co
     ,string_agg(prov.articulo,',') as articulo_ve,
     t.fecha_actual,T.fecha_registro,t.estado,t.comprobante,t.identificador_cli_pro,fv.num_serie as num_serie_fv,fc.num_serie as num_serie_fc,g.num_factura as num_factura_g
 
            ,t.id_transaccion_pv FROM transacciones t            
            INNER JOIN detalle_transaccion dt USING(id_transacciones)           
           
              left JOIN gastos g on g.id_gastos=T.comprobante::integer
                 left JOIN detalle_gastos dg on dg.id_gastos=g.id_gastos
                   left JOIN formas_pago_mixto_g fpm_g on g.id_gastos=fpm_g.id_gastos
                    left JOIN proveedores p_g   on p_g.id_proveedor=g.id_proveedor
                   
            
               left JOIN factura_compra fc on fc.id_factura_compra=T.comprobante::integer
                 left JOIN detalle_factura_compra dfc on fc.id_factura_compra=dfc.id_factura_compra
                  left JOIN productos pro on pro.cod_productos=dfc.cod_productos
                    left JOIN formas_pago_mixto_c fpc on fc.id_factura_compra=fpc.id_factura_compra
            left JOIN proveedores p_c   on p_c.id_proveedor=fc.id_proveedor
                  
               
                left JOIN factura_venta fv on fv.id_factura_venta=T.comprobante::integer
                     left JOIN detalle_factura_venta dfv on dfv.id_factura_venta=fv.id_factura_venta
               left JOIN productos prov on prov.cod_productos= dfv.cod_productos
                 left JOIN formas_pago_mixto fpmv on fv.id_factura_venta=fpmv.id_factura_venta
             left JOIN clientes c on c.id_cliente=fv.id_cliente
                            
               WHERE t.fecha_registro $query_fecha '$_GET[fin]' $query_tipo $query_punto         
            group by t.id_transacciones,T.fecha_registro,t.comprobante,identificador_cli_pro, p_g.empresa_pro,c.nombres_cli,t.concepto,fv.num_serie,fc.num_serie,g.num_factura
ORDER BY FECHA_REGISTRO ASC, id_transacciones asc"
);

if (pg_num_rows($query)) {
    while ($row = pg_fetch_row($query)) {
        $sub_debe = 0;
        $sub_haber = 0;

        //$pdf->SetX(5);
        $pdf->Ln(0);
        $pdf->SetFillColor(220, 240, 210);
        $pdf->SetFont('helvetica', 'B', 8);

        $totalw = $pdf->GetCurrentWidth();
        $colw = $totalw / 4;

        $pdf->SetWidths([$colw - 6, $colw - 6, $colw + 6, $colw + 6]);
        $pdf->SetAligns(["L", "L", "R", "R"]);
        //$cy = $pdf->GetY();

        if ($row[7] == "Activo") {
            $pdf->SetTextColors([]);
            //$pdf->Cell(105, 6, utf8_decode(' ' . " NÚMERO: " . $row[4]), 0, 1, 'L', 1);
            $pdf->Row([
                utf8_decode("ASIENTO NRO.: " . $row[13]),
                utf8_decode("REGISTRO: " . $row[8]),
                utf8_decode("FECHA MOVIMIENTO: " . $row[6]),
                utf8_decode("FECHA REGISTRO: " . $row[5])
                    ], 0, "", 1);
        } else {
            //$pdf->setTextColor(255, 0, 0);
            $pdf->SetTextColors([
                    [0, 0, 0],
                    [0, 0, 0],
                    [255, 0, 0],
            ]);
            $colw = $totalw / 5;

            $pdf->SetWidths([$colw + 5, $colw - 15, $colw - 20, $colw + 15, $colw + 15]);
            $pdf->SetAligns(["L", "L", "L", "R", "R"]);
            $pdf->Row([
                utf8_decode("ASIENTO NRO.: " . $row[0]),
                utf8_decode("REGISTRO: " . $row[8]),
                utf8_decode("ANULADO"),
                utf8_decode("FECHA MOVIMIENTO: " . $row[6]),
                utf8_decode("FECHA REGISTRO: " . $row[5])
                    ], 0, "", 1);
            //$pdf->setTextColor(0, 0, 0);
            /*   $pdf->Cell(45, 6, utf8_decode(' ' . " NÚMERO:" . $row[4]), 0, 1, 'L', 1);
              $pdf->SetY($cy);
              $pdf->SetX($pdf->GetX() + 45);
              $pdf->setTextColor(255, 0, 0);
              $pdf->Cell(60, 6, utf8_decode(" ANULADO"), 0, 1, 'L', 1);
              $pdf->setTextColor(0, 0, 0); */
        }
        $pdf->SetWidths([$totalw]);
        $pos1 = "";
        $pos2 = "";
        $pos3 = "";
        $pizza = $row[2];
        $porciones = explode(",", $pizza);
        if ($row[2] != "") {
            if (count($porciones) == 2) {
                $pos1 = $porciones[1]; // porción1
            }
            if (count($porciones) == 3) {
                $pos1 = $porciones[1]; // porción1
                $pos2 = $porciones[2];
            }
            if (count($porciones) == 4) {
                $pos1 = $porciones[1]; // porción1
                $pos2 = $porciones[2];
                $pos3 = $porciones[3];
            }
            $pos4 = $pos1;
        } else {
            $pos4 = $row[2];
        }
        ///////////////////////////////////ven
        $pos1_ven = "";
        $pos2_ven = "";
        $pos3_ven = "";
        $pizza_ven = $row[4];
        $porciones_ven = explode(",", $pizza_ven);
        if ($row[4] != "") {
            if (count($porciones_ven) == 2) {
                $pos1_ven = $porciones_ven[1]; // porción1
            }
            if (count($porciones) == 3) {
                $pos1_ven = $porciones_ven[1]; // porción1
                $pos2_ven = $porciones_ven[2];
            }
            if (count($porciones_ven) == 4) {
                $pos1_ven = $porciones_ven[1]; // porción1
                $pos2_ven = $porciones_ven[2];
                $pos3_ven = $porciones_ven[3];
            }
            $pos4_ven = $pos1_ven;
        } else {
            $pos4_ven = $row[4];
        }
        ///////////////////////////////////ven
        $pos1_com = "";
        $pos2_com = "";
        $pos3_com = "";
        $pizza_com = $row[3];
        $porciones_com = explode(",", $pizza_com);
        if ($row[3] != "") {
            if (count($porciones_com) == 2) {
                $pos1_com = $porciones_com[1]; // porción1
            }
            if (count($porciones) == 3) {
                $pos1_com = $porciones_com[1]; // porción1
                $pos2_com = $porciones_com[2];
            }
            if (count($porciones_com) == 4) {
                $pos1_com = $porciones_com[1]; // porción1
                $pos2_com = $porciones_com[2];
                $pos3_com = $porciones_com[3];
            }
            $pos4_com = $pos1_com;
        } else {
            $pos4_com = $row[3];
        }
        if ($row[9] == 'NV') {

            $pdf->Row([utf8_decode(maxCaracter(utf8_decode($row[1] . "---" . $row[10] . "---" . $pos4_ven), 190))], 0, "", 1);
        }
        if ($row[9] == 'VEN') {

            $pdf->Row([utf8_decode(maxCaracter(utf8_decode($row[1] . "---" . $row[10] . "---" . $pos4_ven), 190))], 0, "", 1);
        }
        if ($row[9] == 'COM') {

            $pdf->Row([utf8_decode(maxCaracter(utf8_decode($row[1] . "---" . $row[11] . "---" . $pos4_com), 190))], 0, "", 1);
        }
        if ($row[9] == 'GAS') {
            $pdf->Row([utf8_decode(maxCaracter(utf8_decode($row[1] . "---" . $pos4), 190))], 0, "", 1);
        }
        if ($row[9] == 'CxP') {
            $pdf->Row([utf8_decode(maxCaracter(utf8_decode($row[1]), 150))], 0, "", 1);
        }
        if ($row[9] == 'CxC') {
            $pdf->Row([utf8_decode(maxCaracter(utf8_decode($row[1]), 150))], 0, "", 1);
        }
        if ($row[9] == 'OTRO') {
            $pdf->Row([utf8_decode(maxCaracter(utf8_decode($row[1]), 150))], 0, "", 1);
        }
        if ($row[9] == 'ING') {
            $pdf->Row([utf8_decode(maxCaracter(utf8_decode($row[1]), 150))], 0, "", 1);
        }
        if ($row[9] == 'EGR') {
            $pdf->Row([utf8_decode(maxCaracter(utf8_decode($row[1]), 150))], 0, "", 1);
        }
        if ($row[9] == 'ANTP') {
            $pdf->Row([utf8_decode(maxCaracter(utf8_decode($row[1]), 150))], 0, "", 1);
        }
        if ($row[9] == 'E') {
            $pdf->Row([utf8_decode(maxCaracter(utf8_decode($row[1]), 150))], 0, "", 1);
        }
        if ($row[9] == 'DVFV') {
            $pdf->Row([utf8_decode(maxCaracter(utf8_decode($row[1]), 150))], 0, "", 1);
        }
        if ($row[9] == 'DVNV') {
            $pdf->Row([utf8_decode(maxCaracter(utf8_decode($row[1]), 150))], 0, "", 1);
        }

        if ($row[9] == 'I') {
            $pdf->Row([utf8_decode(maxCaracter(utf8_decode($row[1]), 150))], 0, "", 1);
        }
        if ($row[9] == 'ANTN') {
            $pdf->Row([utf8_decode(maxCaracter(utf8_decode($row[1]), 150))], 0, "", 1);
        }
        if ($row[9] == 'RP') {
            $pdf->Row([utf8_decode(maxCaracter(utf8_decode($row[1]), 150))], 0, "", 1);
        } 
        if ($row[9] == 'DC') {
            $pdf->Row([utf8_decode(maxCaracter(utf8_decode($row[1]), 150))], 0, "", 1);
        }




        /*    $pdf->SetY($cy);
          $pdf->SetX($pdf->GetX() + 105);
          $pdf->Cell(50, 6, utf8_decode(' ' . " FECHA MOVIMIENTO: " . $row[3]), 0, 1, 'R', 1);
          $pdf->SetY($cy);
          $pdf->SetX($pdf->GetX() + 155);
          $pdf->Cell(50, 6, utf8_decode(' ' . "FECHA REGISTRO: " . $row[2]), 0, 1, 'R', 1);
          $pdf->Ln(1);
          $pdf->SetX(5);
          $pdf->Cell(200, 6, utf8_decode(' ' . $row[1]), 0, 1, 'L', 1); //cabecera haciento
         */
        imprimirDebe($row[0], $sub_debe, $sub_haber, $row[7]);
        imprimirHaber($row[0], $sub_debe, $sub_haber, $row[7]);

        $pdf->SetX(5);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(200, 0, utf8_decode(''), 1, 1, 'R', 1);
        $pdf->Cell(165, 6, utf8_decode('Subtotal:'), 0, 0, 'R', 0);
        $pdf->Cell(20, 6, number_format($sub_debe, 2, ',', '.'), 0, 0, 'R', 0);
        $pdf->Cell(20, 6, number_format($sub_haber, 2, ',', '.'), 0, 1, 'R', 0);
        $pdf->Ln(2);
        /* if (pg_num_rows($query_detalle)) {
          while ($row1 = pg_fetch_row($query_detalle)) {
          $pdf->SetX(5);
          $pdf->SetFont('helvetica', '', 9);
          $pdf->Cell(40, 6, maxCaracter(utf8_decode($row1[1]), 15), 0, 0, 'L', 0);
          $pdf->Cell(120, 6, maxCaracter(utf8_decode($row1[2]), 70), 0, 0, 'L', 0);
          $pdf->Cell(20, 6, number_format($row1[3], 2, ',', '.'), 0, 0, 'R', 0);
          $sub_debe += $row1[3];
          $pdf->Cell(20, 6, number_format($row1[4], 2, ',', '.'), 0, 1, 'R', 0);
          $sub_haber += $row1[4];
          }
          $pdf->SetX(5);
          $pdf->SetFont('helvetica', 'B', 9);
          $pdf->Cell(200, 0, utf8_decode(''), 1, 1, 'R', 1);
          $pdf->Cell(165, 6, utf8_decode('Subtotal:'), 0, 0, 'R', 0);
          $pdf->Cell(20, 6, number_format($sub_debe, 2, ',', '.'), 0, 0, 'R', 0);
          $pdf->Cell(20, 6, number_format($sub_haber, 2, ',', '.'), 0, 1, 'R', 0);
          $pdf->Ln(2);
          } */
        $total_debe += $sub_debe;
        $total_haber += $sub_haber;
    }
    $pdf->SetX(0);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(210, 0, utf8_decode(''), 1, 1, 'R', 1);
    $pdf->Cell(165, 6, utf8_decode('Totales:'), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, number_format($total_debe, 2, ',', '.'), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, number_format($total_haber, 2, ',', '.'), 0, 1, 'R', 0);
    /* $pdf->Ln(20);
      $pdf->CheckPageBreak($pdf->GetY() - 30);
      $pdf->SetY(-30);
      $pdf->SetX(7);
      $pdf->Cell(40, 0, utf8_decode('x'), 1, 1, 'R', 1);
      $pdf->SetX(44);
      $pdf->Cell(5, 0, "", 0, 0, 'R', 0);
      $pdf->SetX(57);
      $pdf->Cell(40, 0, utf8_decode('x'), 1, 1, 'R', 1);
      $pdf->SetX(94);
      $pdf->Cell(5, 0, "", 0, 0, 'R', 0);
      $pdf->SetX(107);
      $pdf->Cell(40, 0, utf8_decode('x'), 1, 1, 'R', 1);
      $pdf->SetX(144);
      $pdf->Cell(5, 0, "", 0, 0, 'R', 0);
      $pdf->SetX(157);
      $pdf->Cell(40, 0, utf8_decode('x'), 1, 1, 'R', 1);
      $pdf->Ln(4);
      $pdf->SetX(7);
      $pdf->Cell(40, 0, utf8_decode('Elaborado por: '), 0, 0, 'C', 0);
      $pdf->SetX(44);
      $pdf->SetX(57);
      $pdf->Cell(40, 0, utf8_decode('Aprobado Presidente:'), 0, 0, 'C', 0);
      $pdf->SetX(94);
      $pdf->SetX(107);
      $pdf->Cell(40, 0, utf8_decode('Aprobado Tesorero:'), 0, 0, 'C', 0);
      $pdf->SetX(144);
      $pdf->SetX(157);
      $pdf->Cell(40, 0, utf8_decode('Contabilizado por:'), 0, 1, 'C', 0); */
    /* $pdf->Ln(5);
      $pdf->SetX(7);
      $pdf->Cell(40, 0, utf8_decode('x'), 1, 1, 'R', 1);
      $pdf->SetX(44);
      $pdf->Cell(5, 0, "", 0, 0, 'R', 0);
      $pdf->SetX(57);
      $pdf->Cell(40, 0, utf8_decode('x'), 1, 1, 'R', 1);
      $pdf->SetX(94);
      $pdf->Cell(5, 0, "", 0, 0, 'R', 0);
      $pdf->SetX(107);
      $pdf->Cell(40, 0, utf8_decode('x'), 1, 1, 'R', 1);
      $pdf->SetX(144);
      $pdf->Cell(5, 0, "", 0, 0, 'R', 0);
      $pdf->SetX(157);
      $pdf->Cell(40, 0, utf8_decode('x'), 1, 1, 'R', 1); */
    /* $pdf->Ln(3);
      $pdf->SetX(157);
      $pdf->Cell(40, 0, utf8_decode('C.I.:'), 0, 1, 'L', 0); */
}

$pdf->Output();

function imprimirDebe($idtrans, &$sub_debe, &$sub_haber, $estado) {
    global $pdf;
    $sqldebe = pg_query("
        SELECT P.id_plan_cuentas, P.codigo_plan, P.descripcion, round(D.debito,2)debito, round(D.credito,2)credito  
            FROM transacciones T, detalle_transaccion D, plan_cuentas P 
            WHERE T.id_transacciones=D.id_transacciones 
            AND D.id_plan_cuentas=P.id_plan_cuentas 
            AND T.id_transacciones='$idtrans'
            and d.debito>0
            order by
            case
            when P.codigo_plan like '5%' then 4
            when P.codigo_plan like '1%' then 2
            when P.codigo_plan like '2%' then 3
            else 1
            end asc,P.id_plan_cuentas asc;
        ");

    if (pg_num_rows($sqldebe)) {
        while ($row1 = pg_fetch_row($sqldebe)) {
            $pdf->SetX(5);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Cell(40, 6, maxCaracter(utf8_decode($row1[1]), 15), 0, 0, 'L', 0);
            $pdf->Cell(120, 6, maxCaracter(utf8_decode($row1[2]), 70), 0, 0, 'L', 0);
            $pdf->Cell(20, 6, number_format($row1[3], 2, ',', '.'), 0, 0, 'R', 0);
            $pdf->Cell(20, 6, number_format($row1[4], 2, ',', '.'), 0, 1, 'R', 0);
            if ($estado == "Activo") {
                $sub_debe += $row1[3];
                $sub_haber += $row1[4];
            }
        }
    }
}

function imprimirHaber($idtrans, &$sub_debe, &$sub_haber, $estado) {
    global $pdf;
    $sqldebe = pg_query("
    SELECT P.id_plan_cuentas, P.codigo_plan, P.descripcion, round(D.debito,2)debito, round(D.credito,2)credito 
    FROM transacciones T, detalle_transaccion D, plan_cuentas P 
    WHERE T.id_transacciones=D.id_transacciones 
    AND D.id_plan_cuentas=P.id_plan_cuentas 
    AND T.id_transacciones='$idtrans'
    and d.credito>0
    order by
    case
    when P.codigo_plan like '4%' then 1
    when P.codigo_plan like '1%' then 2
    when P.codigo_plan like '2%' then 3
    else 4
    end asc, P.id_plan_cuentas asc;
        ");

    if (pg_num_rows($sqldebe)) {
        while ($row1 = pg_fetch_row($sqldebe)) {
            $pdf->SetX(5);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Cell(40, 6, maxCaracter(utf8_decode($row1[1]), 15), 0, 0, 'L', 0);
            $pdf->Cell(120, 6, maxCaracter(utf8_decode($row1[2]), 70), 0, 0, 'L', 0);
            $pdf->Cell(20, 6, number_format($row1[3], 2, ',', '.'), 0, 0, 'R', 0);
            $pdf->Cell(20, 6, number_format($row1[4], 2, ',', '.'), 0, 1, 'R', 0);
            if ($estado == 'Activo') {
                $sub_debe += $row1[3];
                $sub_haber += $row1[4];
            }
        }
    }
}
