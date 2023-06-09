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
        $this->AddFont('helvetica', 'B', 'helveticab.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(0);
        $this->SetY(0);
        $this->Cell(105, 5, $fecha, 0, 0, 'L', 0);
        $this->Cell(105, 5, "PRODUCTOS VENDIDOS", 0, 1, 'R', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->SetX(0);
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
        $this->Cell(210, 5, utf8_decode("REPORTE DE PRODUCTOS VENDIDOS"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        $this->Ln(10);
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

    public function __construct()
    {
        $this->pdf = new PDF('P', 'mm', 'a4');
        $this->pdf->SetTitle('Productos Vendidos');
        $this->pdf->AddPage();
        $this->pdf->SetMargins(5, 0);
        $this->pdf->AliasNbPages();
        $this->pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->pdf->SetFont('Amble-Regular', '', 9);
    }

    public function titulo($titulo)
    {
        $cierrecaja = $this->obtenerCierreCaja();

        $this->pdf->Ln(0);
        $totalw = $this->pdf->GetCurrentWidth();
        $this->pdf->SetFont("helvetica", "", 11);
        if ($cierrecaja[0]["estado"] == 'Pasivo') {
            $this->pdf->SetFont("helvetica", "B", 11);
            $this->pdf->Cell($totalw / 2, 5, "(Registro de caja anulado)", 0, 1);
        }
        $this->pdf->Cell($totalw / 2, 5, "Usuario Caja: " . $cierrecaja[0]["nombre_usuario"] . " " . $cierrecaja[0]["apellido_usuario"], 0, 1);
        $this->pdf->Cell($totalw / 2, 5, "Fecha Apertura: " . $cierrecaja[0]["fecha_actual"] . " - " . $cierrecaja[0]["hora_actual"], 0, 1);
        $this->pdf->Cell($totalw / 2, 5, "Fecha Cierre: " . $cierrecaja[0]["fecha_cierre"] . " - " . $cierrecaja[0]["hora_cierre"], 0, 1);

        $this->pdf->Ln(5);
    }

    public function tabla()
    {
        $cierrecaja = $this->obtenerCierreCaja();
        $fechaapertura = $cierrecaja[0]["fecha_actual"];
        $fechacierre = $cierrecaja[0]["fecha_cierre"];
        $horaapertura = $cierrecaja[0]["hora_actual"];
        $horacierre = $cierrecaja[0]["hora_cierre"];
        $idempresa = $cierrecaja[0]["id_empresa"];
        $idusuario = $cierrecaja[0]["id_usuario"];

        $this->pdf->Ln(1);

        $this->pdf->SetFont("Arial", "B", 10);

        $totalw = $this->pdf->GetCurrentWidth();
        $colw = $totalw / 6;

        $this->pdf->SetWidths([
            $colw - 16,
            $colw + 68,
            $colw - 12,
            $colw - 14,
            $colw - 14,
            $colw - 12,
        ]);
        $this->pdf->SetAligns(array_fill(0, 6, "C"));
        $this->pdf->Row([
            utf8_decode("CÓDIGO"),
            "PRODUCTO",
            "CANTIDAD",
            "P.VENTA",
            "IVA",
            "TOTAL",
        ], 1);

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
                and fv.fecha_actual between '$fechaapertura' and '$fechacierre'
                and fv.hora_actual::time between '$horaapertura' and '$horacierre'
                and fv.id_empresa=$idempresa
                and fv.id_usuario=$idusuario
                and fv.estado = 'Activo'
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
                and fv.fecha_actual between '$fechaapertura' and '$fechacierre'
                and fv.hora_actual::time between '$horaapertura' and '$horacierre'
                and fv.id_empresa=$idempresa
                and fv.id_usuario=$idusuario
                and fv.estado = 'Activo'
                group by dfv.cod_productos,p.articulo,p.iva,dfv.precio_venta,p.incluye_iva,p.precio_compra,p.cod_barras
                order by cantidad desc
            )
        ) as x
        group by x.cod_productos,x.articulo,x.iva,x.precio_venta,x.incluye_iva,x.precio_compra,x.cod_barras
        order by cantidad desc
        ";
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
        $this->pdf->SetAligns(["L", "L",  "R", "R", "R", "R"]);
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
                    number_format($value["precio_venta"], 2, ",", "."),
                    number_format($ivapv, 2, ",", "."),
                    number_format($totali, 2, ",", "."),
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
            ($colw * 2 + 52),
            $colw - 12,
            $colw - 14,
            $colw - 14,
            $colw - 12,
        ]);
        $this->pdf->SetAligns(array_fill(0, 5, "R"));
        $this->pdf->Row([
            "TOTALES",
            $cantidad,

            "",
            "",
            number_format($total, 2, ",", "."),

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

    public function imprimir($titulo)
    {
        $this->titulo($titulo);
        $this->tabla();
        $this->pdf->Output();
    }

    public function obtenerCierreCaja()
    {
        $sql = "
        select u.nombre_usuario,u.apellido_usuario, cc.* from cierre_caja cc
        inner join usuario u
        using(id_usuario)
        where id_cierre_caja = $_GET[id]
        ";
        $res = pg_query($sql);
        $rows = pg_fetch_all($res);
        if (empty($rows)) {
            return [];
        }
        return $rows;
    }
}

$repo = new Reporte();
$repo->imprimir("RESUMEN DE PRODUCTOS VENDIDOS");
