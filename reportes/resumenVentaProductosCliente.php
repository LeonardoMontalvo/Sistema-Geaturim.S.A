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

    function Header()
    {
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(1);
        $this->SetY(1);
        $this->Cell($this->GetCurrentWidth() / 2, 5, $fecha, 0, 0, 'L', 0);
        $this->Cell($this->GetCurrentWidth() / 2, 5, "VENTAS PRODUCTOS", 0, 1, 'R', 0);
        $this->SetFont('Arial', 'B', 16);
        $this->SetX(0);
        $this->Cell($this->GetCurrentWidth(), 8, "EMPRESA: " . $_SESSION['empresa'], 0, 1, 'C', 0);
        $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 5, 8, 35, 28);
        $this->SetFont('Amble-Regular', '', 10);
        $this->SetX(0);
        $this->Cell($this->GetCurrentWidth(), 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        $this->SetX(0);
        $this->Cell($this->GetCurrentWidth() / 2, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        $this->Cell($this->GetCurrentWidth() / 2, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'L', 0);
        $this->SetX(0);
        $this->Cell($this->GetCurrentWidth(), 5, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        $this->SetX(0);
        $this->Cell($this->GetCurrentWidth(), 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        $this->SetX(0);
        $this->Cell($this->GetCurrentWidth(), 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
    }
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

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
}

class Reporte
{
    private $pdf;
    private $idCliente;

    public function __construct()
    {
        $this->pdf = new PDF('L', 'mm', 'a4');
        $this->pdf->AddPage();
        $this->pdf->SetMargins(5, 0);
        $this->pdf->AliasNbPages();
        $this->pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');

        $this->pdf->SetFont('Amble-Regular', '', 9);
    }

    public function titulo($titulo)
    {
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetLineWidth(0.4);
        $this->pdf->Line(0, 50, $this->pdf->GetCurrentWidth() + 10, 50);
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetX(0);
        $this->pdf->Cell($this->pdf->GetCurrentWidth(), 5, utf8_decode($_GET['inicio']) . " - " . utf8_decode($_GET['fin']), 0, 1, 'C', 0);
        $this->pdf->SetX(0);
        $this->pdf->Cell($this->pdf->GetCurrentWidth(), 5, utf8_decode($titulo), 0, 1, 'C', 0);
        $this->pdf->SetFont('Amble-Regular', '', 10);
        $this->pdf->Ln(3);
        $this->pdf->SetFillColor(255, 255, 225);
        $this->pdf->SetLineWidth(0.2);
        $this->pdf->Ln(5);
    }

    public function tabla($tipo)
    {
        $this->pdf->Ln(1);

        $this->pdf->SetFont("Arial", "B", 10);

        $totalw = $this->pdf->GetCurrentWidth();
        $colw = $totalw / 10;

        $this->pdf->SetWidths([
            $colw + 10,
            $colw + 54,
            $colw - 8,
            $colw - 7,
            $colw - 8,
            $colw - 8,
            $colw - 8,
            $colw - 8,
            $colw - 8,
            $colw - 8
        ]);
        $this->pdf->SetAligns(array_fill(0, 10, "C"));
        $this->pdf->Row([
            utf8_decode("CÓDIGO"),
            "PRODUCTO",
            "CANTIDAD",
            "P.COMPRA",
            "IVA",
            "TOTAL C.",
            "P.VENTA",
            "IVA.",
            "TOTAL V.",
            "UTILIDAD"
        ], 1);

        $condcli = "";
        if (!empty($this->idCliente)) {
            $condcli = "and fv.id_cliente=" . $this->idCliente;
        }

        switch ($tipo) {
            case "venta":
                $sql = "
                    select x.cod_productos,
                    x.articulo,
                    sum(x.cantidad) as cantidad,
                    sum(x.total) as total,
                    x.iva,
                    x.precio_venta,
                    x.incluye_iva,
                    x.precio_compra,
                    x.cod_barras
                    from(
                        (
                            select dfv.cod_productos,
                            p.articulo,
                            sum(cantidad::numeric) as cantidad,
                            coalesce(round(sum(dfv.total_venta::numeric -(dfv.total_venta::numeric *(round((fv.descuento_venta * 100) / nullif((fv.tarifa0::numeric + fv.tarifa12::numeric), 0),0) / 100))),4),0) as total,
                            p.iva,
                            dfv.precio_venta,
                            p.incluye_iva,
                            p.precio_compra,
                            p.cod_barras
                            from factura_venta fv,
                            detalle_factura_venta dfv,
                            productos p
                            where fv.id_factura_venta = dfv.id_factura_venta
                            and p.cod_productos = dfv.cod_productos
                            and fv.fecha_actual between '$_GET[inicio]' and '$_GET[fin]'
                            and fv.id_empresa=$_GET[id]
                            and fv.estado = 'Activo'
                            $condcli
                            group by dfv.cod_productos,p.articulo,p.iva,dfv.precio_venta,p.incluye_iva,p.precio_compra,p.cod_barras
                            order by cantidad desc
                        )
                        union all
                        (
                            select dfv.cod_productos,
                            p.articulo,
                            sum(cantidad::numeric) as cantidad,
                            coalesce(round(sum(dfv.total_venta::numeric -(dfv.total_venta::numeric *(round((fv.descuento_venta * 100) / nullif((fv.tarifa0::numeric + fv.tarifa12::numeric), 0),0) / 100))),4),0) as total,
                            p.iva,
                            dfv.precio_venta,
                            p.incluye_iva,
                            p.precio_compra,
                            p.cod_barras
                            from facturas_novalidas fv,
                            detalle_facturas_novalidas dfv,
                            productos p
                            where fv.id_facturas_novalidas = dfv.id_facturas_novalidas
                            and p.cod_productos = dfv.cod_productos
                            and fv.fecha_actual between '$_GET[inicio]' and '$_GET[fin]'
                            and fv.id_empresa=$_GET[id]
                            and fv.estado = 'Activo'
                            $condcli
                            group by dfv.cod_productos,p.articulo,p.iva,dfv.precio_venta,p.incluye_iva,p.precio_compra,p.cod_barras
                            order by cantidad desc
                        )
                    ) as x
                    group by x.cod_productos,x.articulo,x.iva,x.precio_venta,x.incluye_iva,x.precio_compra,x.cod_barras
                    order by cantidad desc
                    ";
                break;
        }

        $res = pg_query($sql);
        $rows = pg_fetch_all($res);
        if (!$rows) {
            $rows = [];
        }

        $total = 0;
        $cantidad = 0;
        $tutilidad = 0;
        $totalcompra = 0;
        $this->pdf->SetFont('Amble-Regular', '', 10);
        $this->pdf->SetAligns(["L", "L",  "R", "R", "R", "R", "R", "R", "R", "R"]);
        foreach ($rows as $value) {
            $totali = $value["total"];
            $ivapv = 0;
            $ivapc = 0;
            if (mb_strtolower($value["iva"]) == 'si') {
                $iva = $this->obtenerIva();
                $viva = $totali * ($iva / 100);
                $totali += $viva;


                if (mb_strtolower($value["incluye_iva"]) == 'si') {
                    $pvsi = $value["precio_venta"] / (1 + ($iva / 100));
                    $ivapv = $value["precio_venta"] - $pvsi;
                } else {
                    $ivapv = $value["precio_venta"] * ($iva / 100);
                }
                $ivapc = $value["precio_compra"] * ($iva / 100);
            }
            $utilidad = ($value["cantidad"] * ($value["precio_venta"] + $ivapv)) - ($value["cantidad"] * ($value["precio_compra"] + $ivapc));
            $totalc = ($value["precio_compra"] + $ivapc) * $value["cantidad"];
            $this->pdf->Row(
                [
                    utf8_decode($value["cod_barras"]),
                    utf8_decode($value["articulo"]),
                    number_format($value["cantidad"], 2, ",", "."),
                    number_format($value["precio_compra"], 2, ",", "."),
                    number_format($ivapc, 2, ",", "."),
                    number_format($totalc, 2, ",", "."),
                    number_format($value["precio_venta"], 2, ",", "."),
                    number_format($ivapv, 2, ",", "."),
                    number_format($totali, 2, ",", "."),
                    number_format($utilidad, 2, ",", "."),
                ],
                1
            );
            $tutilidad += $utilidad;
            $total += $totali;
            $cantidad += $value["cantidad"];
            $totalcompra += $totalc;
        }

        $this->pdf->SetFont("Arial", "B", 11);
        $this->pdf->SetWidths([
            121.4,
            20.5,
            21.9,
            20.8,
            20.6,
            20.7,
            20.7,
            20.7,
            20.7,
        ]);
        $this->pdf->SetAligns(array_fill(0, 10, "R"));
        $this->pdf->Row([
            "TOTALES",
            $cantidad,
            "",
            "",
            number_format($totalcompra, 2, ",", "."),
            "",
            "",
            number_format($total, 2, ",", "."),
            number_format($tutilidad, 2, ",", ".")
        ], 1);

        $this->pdf->Output();
    }

    public function obtenerIva()
    {
        $sql = "select valor from parametros where descripcion='IVA'";
        $res = pg_query($sql);
        $rows = pg_fetch_all($res);
        if (!$rows) {
            return null;
        }
        return $rows[0]["valor"];
    }

    public function cliente()
    {
        $sql = "select*from clientes where id_cliente=" . $this->idCliente;
        $res = pg_query($sql);
        $rows = pg_fetch_all($res);
        $cliente = $rows[0];
        $totalw = $this->pdf->GetCurrentWidth();
        $this->pdf->SetFont('Arial', 'B', 11);
        $this->pdf->Cell($totalw, 5, "CLIENTE: " . utf8_decode($cliente["nombres_cli"]), 0, 1, 'L', 0);
        $this->pdf->Cell($totalw, 5, "RUC/CI: " . utf8_decode($cliente["identificacion"]), 0, 1, 'L', 0);
        $this->pdf->Ln(2);
    }

    public function imprimir($titulo, $tipo, $idcliente)
    {
        $this->idCliente = $idcliente;
        $this->titulo($titulo);
        if(!empty($idcliente)){
            $this->cliente();
        }
        $this->tabla($tipo);
        $this->pdf->Output();
    }
}

$repo = new Reporte();
switch ($_GET["tipo"]) {
    case "venta":
        $repo->imprimir("RESUMEN DE PRODUCTOS VENDIDOS", $_GET["tipo"], $_GET["id_cliente"]);
        break;
}
