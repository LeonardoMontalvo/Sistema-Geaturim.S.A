<?php

require('../../fpdf/fpdf.php');
include '../../procesos/base.php';
include '../../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();

class PDF extends FPDF {

    var $widths;
    var $aligns;

    function SetWidths($w) {
        //Set the array of column widths

        $this->widths = $w;
    }

    function SetAligns($a) {
        //Set the array of column alignments

        $this->aligns = $a;
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

    function GetCurrentWidth() {
        return $this->w - ($this->lMargin * 2);
    }

    function Header() {
        $query_fecha = "";
// RANGO DE FECHAS O FECHA ACTUAL
        $this->rango = false;
        if ($this->rango) {
            $query_fecha = "BETWEEN '$_GET[inicio]' AND";
        } else {
            $query_fecha = "=";
        }
        $totalw = $this->GetCurrentWidth();
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(0);
        $this->SetY(0);
        $this->Cell($totalw / 2, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell($totalw / 2, 5, "COMPRAS", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell($totalw, 8, utf8_decode($_SESSION['nombre_empresa']), 0, 1, 'C', 0);
        $this->Image('../../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], $this->lMargin + 10, 7, 15, 15);
        $this->Image('../../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], $totalw - 10, 7, 15, 15);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(0, 25, $totalw + ($this->lMargin * 2), 25);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell($totalw, 5, utf8_decode("RESUMEN DOCUMENTOS"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);

        $this->Ln(12);
    }

}

$pdf = new PDF('L', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetMargins(2, 0);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true, 10);
$pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
$pdf->SetFont('Amble-Regular', '', 9);
$pdf->Ln(0);

$totalw = $pdf->GetCurrentWidth();

////////////id_Cgp////////////////////

$idClacificacion = $_GET["id_Cgp"];

$idClacificacion_sql = "";

if (!empty($idClacificacion)) {
    $idClacificacion_sql = " and cg.id_clasificacion=$idClacificacion";
}

///////////////
$idClacificacion_sql_id = "";

if (!empty($idClacificacion)) {
    $idClacificacion_sql_id = " where id_clasificacion=$idClacificacion";
}
////////////id_Dtg////////////////////

$idTipo_gasto = $_GET["id_Dtg"];

$idTipo_gasto_sql = "";

if (!empty($idTipo_gasto)) {
    $idTipo_gasto_sql = " and tgp.id_tipo_gasto=$idTipo_gasto ";
}

$idTipo_gasto_sql_sin = "";

if (!empty($idTipo_gasto)) {
    $idTipo_gasto_sql_sin = "  where tgp.id_tipo_gasto=$idTipo_gasto ";
}
////////////proveedor////////////////////
$idProveedor = $_GET["proveedor"];

$idProveedor_sql = "";

if (!empty($idProveedor)) {
    $idProveedor_sql = " and p.id_proveedor=$idProveedor ";
}

//compras
$fng = function () {
    return gruposCuentasProuctosDocumento(
            "gastos_personales", "detalle_gastos_personales", "id_gastos_personales", "id_detalle_gastos_personales"
    );
};
$tcostoc = buildDocumento(
        "COMPRAS", $fng, function ($idplanc) {
    return obtenerFacturasVenta($idplanc);
}, [
    utf8_decode("Factura"),
    utf8_decode("F. Emisión"),
    utf8_decode("Identificación"),
    utf8_decode("Proveedor"),
    utf8_decode("Total")
        ], [
    "num_factura",
    "fecha_actual",
    "identificacion_pro",
    "empresa_pro",
   
              function ($value) {
        return $value["total"];
    },
        ], [], ["L", "L", "L", "L", "R"], [null, null, null, "TOTALES", 0], ["L", "L", "L", "L", "R"]
);
$pdf->Ln(5);
//ventas facturas
//buildTabla(
//        "FACTURAS DE VENTA", "", obtenerFacturasVenta(), [
//    utf8_decode("Factura"),
//    utf8_decode("F. Emisión"),
//    utf8_decode("Identificación"),
//    utf8_decode("Proveedor"),
//    utf8_decode("Total")
//        ], [
//    "num_factura",
//    "fecha_actual",
//    "identificacion_pro",
//    "empresa_pro",
//    "total"
//        ], [], ["L", "L", "L", "L", "R"], [null, null, null, "TOTALES", 0], ["L", "L", "L", "L", "R"]
//);
//$pdf->Ln(5);

//total
/* $total =
  $tcostoi + $tcostoc + $tcostog + $tcostoin + $tcostolc + $tcostogi
  - $tcostoe;

  mostrarTotal($total); */

$pdf->Output();


function obtenerFacturasVenta($idplanc) {
    global $idClacificacion_sql, $idTipo_gasto_sql, $idProveedor_sql;
     
    $sql = "
           select 
           id_gastos_personales,
           fecha_emision,
           razon_social_comprador,
           gp.fecha_actual,
           gp.num_factura,
           round(gp.total,2) as total,
           p.identificacion_pro,
           p.empresa_pro
           
           from gastos_personales gp
           inner join proveedores p using(id_proveedor) 
           inner join detalle_gastos_personales dgp using(id_gastos_personales)
           inner join tipos_gastos_personales tgp using(id_tipo_gasto) 
           inner join clasificacion_gastos_personales cg using(id_clasificacion)
           where gp.estado='Activo'
          $idClacificacion_sql
          and tgp.id_tipo_gasto=$idplanc 
          $idProveedor_sql
           and gp.fecha_actual BETWEEN '$_GET[inicio]' and '$_GET[fin]'
               GROUP BY gp.fecha_actual,gp.num_factura,p.identificacion_pro,
           p.empresa_pro,gp.id_gastos_personales
           order by gp.id_gastos_personales asc
    ";
    $res = pg_query($sql);
//echo '////'.$sql;
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}

function gruposCuentasProuctosDocumento(

) {
   global $idClacificacion_sql, $idTipo_gasto_sql, $idProveedor_sql;
    $sql = "
       select DISTINCT ON (tgp.id_tipo_gasto) tgp.id_tipo_gasto,tgp.nombre
           from gastos_personales gp
           inner join proveedores p using(id_proveedor) 
           inner join detalle_gastos_personales dgp using(id_gastos_personales)
           inner join tipos_gastos_personales tgp using(id_tipo_gasto) 
           inner join clasificacion_gastos_personales cg using(id_clasificacion)
           where gp.estado='Activo'
          $idClacificacion_sql
          $idTipo_gasto_sql
          $idProveedor_sql
           and gp.fecha_actual BETWEEN '$_GET[inicio]' and '$_GET[fin]'
               GROUP BY  tgp.id_tipo_gasto,tgp.nombre,gp.id_gastos_personales 
          order by tgp.id_tipo_gasto ,gp.id_gastos_personales asc
         
    ";

    $res = pg_query($sql);
//    echo ''."
//       select DISTINCT ON (tgp.id_tipo_gasto) tgp.id_tipo_gasto,tgp.nombre
//           from gastos_personales gp
//           inner join proveedores p using(id_proveedor) 
//           inner join detalle_gastos_personales dgp using(id_gastos_personales)
//           inner join tipos_gastos_personales tgp using(id_tipo_gasto) 
//           inner join clasificacion_gastos_personales cg using(id_clasificacion)
//           where gp.estado='Activo'
//          $idClacificacion_sql
//          $idTipo_gasto_sql
//          $idProveedor_sql
//           and gp.fecha_actual BETWEEN '$_GET[inicio]' and '$_GET[fin]'
//               GROUP BY  tgp.id_tipo_gasto,tgp.nombre,gp.id_gastos_personales 
//          order by tgp.id_tipo_gasto ,gp.id_gastos_personales asc
//         
//    ";
    $rows = pg_fetch_all($res);
    if (empty($res)) {
        return [];
    }
    return $rows;
}
// funciones utilitarias
function buildDocumento(
$titulo, $fngrupos, $datos, $columnascabecera, $columnasdatos, $arrofssetwidths, $alignscolumnasdatos, $colssum, $alignscolssum
) {
   
    global $pdf;
    $totalw = $pdf->GetCurrentWidth();

    $gruposdi = $fngrupos();
    if (empty($gruposdi)) {
        return;
    }
    mostrarTituloDocumento($titulo);
    $total = 0;
    foreach ($gruposdi as $grupo) {
        $total += buildTabla(
                "", $grupo["nombre"], $datos($grupo["id_tipo_gasto"]), $columnascabecera, $columnasdatos, $arrofssetwidths, $alignscolumnasdatos, $colssum, $alignscolssum
        );
    }
    $pdf->Ln(2);
    $pdf->SetFont('Arial', 'B', 13);
    $pdf->Cell($totalw - 50, 5, "TOTAL: ", "T", 0, "R");
    $pdf->Cell(25, 5, $total, "T", 1, "R");
    $pdf->Ln(5);

    return $total;
}

function buildTabla(
$titulo, $subtitulo, $datos, $columnascabecera, $columnasdatos, $arrofssetwidths, $alignscolumnasdatos, $colssum, $alignscolssum = []
) {
    global $pdf;
    if (empty($datos)) {
        return;
    }
    if (!empty($titulo)) {
        mostrarTituloDocumento($titulo);
    }
    if (!empty($subtitulo)) {
        mostrarTituloTabla($subtitulo);
    }
    $totalw = $pdf->GetCurrentWidth();
    $numcols = count($columnascabecera);
    $wc = $totalw / $numcols;
    $arrwidths = array_fill(0, $numcols + 1, $wc);
    if (!empty($arrofssetwidths)) {
        for ($i = 0; $i < count($arrofssetwidths); $i++) {
            $arrwidths[$i] = $arrwidths[$i] + ($arrofssetwidths[$i]);
        }
    }
    $pdf->SetWidths($arrwidths);
    $pdf->SetAligns(array_fill(0, $numcols + 1, "C"));
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Row($columnascabecera, 1);
    $pdf->SetFont('Amble-Regular', '', 9);
    if (!empty($alignscolumnasdatos)) {
        $pdf->SetAligns($alignscolumnasdatos);
    }
    foreach ($datos as $value) {
        $cols = [];
        foreach ($columnasdatos as $value1) {
            if (is_string($value1)) {
                array_push($cols, utf8_decode($value[$value1]));
            } else {
                array_push($cols, $value1($value));
            }
        }
        foreach ($colssum as $key => $value2) {
            if (is_numeric($value2)) {
                $colssum[$key] += $cols[$key];
            }
        }
        $pdf->Row($cols, 1);
    }
    if (!empty($alignscolssum)) {
        $pdf->SetAligns($alignscolssum);
    }
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Row($colssum);
    $pdf->SetFont('Amble-Regular', '', 9);
    return end($colssum);
}

function mostrarTituloTabla($titulo) {


    global $pdf;
    $totalw = $pdf->GetCurrentWidth();
    $pdf->SetFillColor(207, 216, 220);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell($totalw, 5, utf8_decode($titulo), 0, 1, "L", true);
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->Ln(1);
}

function mostrarTituloDocumento($titulo) {
    global $idClacificacion_sql, $idTipo_gasto_sql, $idProveedor_sql;
    $query = pg_query(
            " select cg.nombre 
           from gastos_personales gp
           inner join proveedores p using(id_proveedor) 
           inner join detalle_gastos_personales dgp using(id_gastos_personales)
           inner join tipos_gastos_personales tgp using(id_tipo_gasto) 
           inner join clasificacion_gastos_personales cg using(id_clasificacion)
           where gp.estado='Activo'
          $idClacificacion_sql
          $idTipo_gasto_sql
          $idProveedor_sql
           and gp.fecha_actual BETWEEN '$_GET[inicio]' and '$_GET[fin]'
                GROUP BY cg.nombre order by cg.nombre asc"
    );
   
    $nombre_cc = '';
    while ($row = pg_fetch_row($query)) {
        $nombre_cc = $row[0];
    }
    global $pdf;
    $totalw = $pdf->GetCurrentWidth();
    $pdf->SetFillColor(66, 66, 66);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell($totalw, 7, utf8_decode($nombre_cc), 0, 1, "C", true);
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->Ln(1);
}

function mostrarTotal($total) {
    global $pdf;
    $totalw = $pdf->GetCurrentWidth();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell($totalw - 25, 5, "Total ", "T", 0, "R");
    $pdf->Cell(25, 5, $total, "T", 1, "R");
    $pdf->Ln(5);
}
