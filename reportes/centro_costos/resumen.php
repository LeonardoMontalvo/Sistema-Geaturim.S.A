<?php
require('../../fpdf/fpdf.php');
include '../../procesos/base.php';
include '../../procesos/funciones.php';
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

    function GetCurrentWidth()
    {
        return $this->w - ($this->lMargin * 2);
    }

    function Header()
    {
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
        /*   if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } */
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

$totalw = $pdf->GetCurrentWidth();

$facturasv = obtenerFacturasVenta();
$notasv = obtenerNotasVenta();
$gastosint = obtenerGastosInternos();
$detfaccompra = obtenerDetallesFacturaCompra();
$detgastos = obtenerDetallesGastos();
$detliccomp = obtenerDetallesLiquidacionC();
$detingresos = obtenerDetallesIngreso();
$detegresos = obtenerDetallesEgreso();


$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell($totalw, 5, utf8_decode("NOTAS DE VENTA"), 0, 1, "C");
$pdf->Ln(5);
$pdf->SetFont('Amble-Regular', '', 9);

buildTabla(
    $notasv,
    [
        utf8_decode("Nota V."),
        utf8_decode("F. Emisión"),
        utf8_decode("Identificación"),
        utf8_decode("Cliente"),
        utf8_decode("Subtotal"),
        utf8_decode("0%"),
        utf8_decode("12%"),
        utf8_decode("IVA"),
        utf8_decode("Total")
    ],
    [
        "comprobante",
        "fecha_actual",
        "identificacion",
        "nombres_cli",
        function ($value) {
            return 0;
        },
        "tarifa0",
        "tarifa12",
        "iva_venta",
        "total_venta"
    ],
    [-10, -10, -5, 90, -13, -13, -13, -13, -13],
    [
        "L", "L", "L", "L", "R", "R", "R", "R", "R"
    ],
    [
        null, null, null, "Totales", 0, 0, 0, 0, 0
    ],
    [
        "L", "L", "L", "R", "R", "R", "R", "R", "R"
    ]
);
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell($totalw, 5, "PRODUCTOS FACTURAS DE COMPRA", 0, 1, "C");
$pdf->Ln(5);
$pdf->SetFont('Amble-Regular', '', 9);

buildTabla(
    $detfaccompra,
    [
        utf8_decode("Factura"),
        utf8_decode("F. Emisión"),
        utf8_decode("Artículo"),
        utf8_decode("Cantidad"),
        utf8_decode("Precio C."),
        utf8_decode("Descuento"),
        utf8_decode("Subtotal"),
        utf8_decode("V. IVA"),
        utf8_decode("Total"),
        utf8_decode("U. Medida")
    ],
    [
        "num_serie",
        "fecha_emision",
        "articulo",
        "cantidad",
        "precio_compra",
        "descuento_producto",
        "total_compra",
        function ($value) {
            return $value["iva"] == 'Si' ? utf8_decode($value["total_compra"] * ($value["iva_porc"] / 100)) : $value["total_compra"];
        },
        function ($value) {
            $valiva = $value["iva"] == 'Si' ? utf8_decode($value["total_compra"] * ($value["iva_porc"] / 100)) : $value["total_compra"];
            return $valiva + $value["total_compra"];
        },
        "unidad_medida",
    ],
    [7, -7, +60, -10, -10, -10, -10, -10, -10, 0],
    ["L", "L", "L", "R", "R", "R", "R", "R", "C"],
    [null, null, "Totales", 0, null, 0, 0, 0, 0, null],
    ["L", "L", "R", "R", "R", "R", "R", "R", "C"]
);
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell($totalw, 5, "CONCEPTOS GASTOS", 0, 1, "C");
$pdf->Ln(5);
$pdf->SetFont('Amble-Regular', '', 9);

buildTabla(
    $detgastos,
    [
        utf8_decode("Factura"),
        utf8_decode("F. Emisión"),
        utf8_decode("Concepto"),
        utf8_decode("Bien/Serv."),
        utf8_decode("Valor"),
        utf8_decode("Subtotal"),
        utf8_decode("V. IVA"),
        utf8_decode("Total")
    ],
    [
        "num_factura",
        "fecha_emision",
        "concepto",
        "bien_servicio",
        "precio_compra",
        "total_compra",
        function ($value) {
            return $value["iva"] == 'Si' ? utf8_decode($value["total_compra"] * ($value["iva_porc"] / 100)) : $value["total_compra"];
        },
        function ($value) {
            $valiva = $value["iva"] == 'Si' ? utf8_decode($value["total_compra"] * ($value["iva_porc"] / 100)) : $value["total_compra"];
            return $valiva + $value["total_compra"];
        }
    ],
    [0, -14, 89, -15, -15, -15, -15, -15],
    ["L", "L", "L", "L", "R", "R", "R", "R"],
    [null, null, null, "Totales", 0, 0, 0, 0],
    ["L", "L", "L", "R", "R", "R", "R", "R"]
);
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell($totalw, 5, "GASTOS INTERNOS", 0, 1, "C");
$pdf->Ln(5);
$pdf->SetFont('Amble-Regular', '', 9);

buildTabla(
    $gastosint,
    [
        utf8_decode("Comprobante"),
        utf8_decode("F. Registro"),
        utf8_decode("Factura"),
        utf8_decode("RUC"),
        utf8_decode("Proveedor"),
        utf8_decode("Descripción"),
        utf8_decode("Total")
    ],
    [
        "comprobante",
        "fecha_actual",
        "num_factura",
        "identificacion_pro",
        "empresa_pro",
        "descripcion",
        "total",
    ],
    [-15, -15, -5, -15, 32.5, 32.5, -15],
    ["L", "L", "L", "L", "L", "L", "R"],
    [null, null, null, null, null, "Totales", 0],
    ["L", "L", "L", "L", "L", "R", "R"]
);
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell($totalw, 5, utf8_decode("PRODUCTOS LIQUIDACIÓN EN COMPRAS"), 0, 1, "C");
$pdf->Ln(5);
$pdf->SetFont('Amble-Regular', '', 9);

buildTabla(
    $detliccomp,
    [
        utf8_decode("Factura"),
        utf8_decode("F. Emisión"),
        utf8_decode("Artículo"),
        utf8_decode("Cantidad"),
        utf8_decode("Precio V."),
        utf8_decode("Descuento"),
        utf8_decode("Subtotal"),
        utf8_decode("V. IVA"),
        utf8_decode("Total")
    ],
    [
        "num_factura",
        "fecha_actual",
        "articulo",
        "cantidad",
        "precio_venta",
        "descuento_producto",
        "total_venta",
        function ($value) {
            return $value["iva"] == 'Si' ? utf8_decode($value["total_venta"] * ($value["iva_porc"] / 100)) : 0;
        },
        function ($value) {
            $valiva = $value["iva"] == 'Si' ? utf8_decode($value["total_venta"] * ($value["iva_porc"] / 100)) : 0;
            return $value["total_venta"] + $valiva;
        }
    ],
    [
        10, -10, 60, -10, -10, -10, -10, -10, -10
    ],
    [
        "L", "L", "L", "R", "R", "R", "R", "R", "R"
    ],
    [
        null, null, "Totales", 0, null, 0, 0, 0, 0
    ],
    [
        "L", "L", "R", "R", "R", "R", "R", "R", "R"
    ]
);
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell($totalw, 5, "PRODUCTOS DE INGRESOS", 0, 1, "C");
$pdf->Ln(5);
$pdf->SetFont('Amble-Regular', '', 9);

buildTabla(
    $detingresos,
    [
        utf8_decode("Ingreso"),
        utf8_decode("F. Ingreso"),
        utf8_decode("Artículo"),
        utf8_decode("Cantidad"),
        utf8_decode("Precio C."),
        utf8_decode("Total"),
        utf8_decode("U. Medida")
    ],
    [
        "comprobante",
        "fecha_actual",
        "articulo",
        "cantidad",
        "precio_costo",
        "total",
        "unidad_medida"
    ],
    [-15, -20, 101, -22, -22, -22, 0],
    ["L", "L", "L", "R", "R", "R", "L"],
    [null, null, "Totales", 0, null, 0, null],
    ["L", "L", "R", "R", "R", "R", "L"]
);
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell($totalw, 5, "PRODUCTOS DE EGRESOS", 0, 1, "C");
$pdf->Ln(5);
$pdf->SetFont('Amble-Regular', '', 9);

buildTabla(
    $detegresos,
    [
        utf8_decode("Egreso"),
        utf8_decode("F. Egreso"),
        utf8_decode("Artículo"),
        utf8_decode("Cantidad"),
        utf8_decode("Precio C."),
        utf8_decode("Total"),
        utf8_decode("U. Medida")
    ],
    [
        "comprobante",
        "fecha_actual",
        "articulo",
        "cantidad",
        "precio_costo",
        "total",
        "unidad_medida"
    ],
    [-15, -20, 101, -22, -22, -22, 0],
    ["L", "L", "L", "R", "R", "R", "L"],
    [null, null, "Totales", 0, null, 0, null],
    ["L", "L", "R", "R", "R", "R", "L"]
);
$pdf->Ln(5);


$pdf->Output();


function obtenerDetallesFacturaCompra()
{
    $sql = "select
    p.articulo,
    p.codigo,
    p.cod_barras,
    p.iva,
    fc.precio_compra,
    fc.fecha_emision,
    fc.cantidad,
    fc.descuento_producto,
    fc.num_serie,
    fc.total_compra,
    fc.centro_costos,
    fc.id_centro_costo,
    fc.unidad_medida,
    coalesce(param.valor,'12') iva_porc
    from (
    select fc.num_serie,fc.fecha_emision,dcc.id_centro_costo,
    cc.nombre centro_costos, dfc.* from factura_compra fc
    inner join detalle_factura_compra dfc
    using(id_factura_compra)
    inner join detalle_centro_costos dcc
    on dcc.id_documento=dfc.id_detalle_compra
    and dcc.tipo_documento='detalle_factura_compra'
    inner join centro_costos cc
    using(id_centro_costo)
    where fc.estado='Activo'
    )as fc
    inner join productos p
    using(cod_productos),parametros param
    where param.descripcion='IVA'";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}
function obtenerDetallesGastos()
{
    $sql = "
    select 
    g.num_factura,
    g.fecha_emision,
    dg.precio_compra,
    dg.total_compra,
    dg.bien_servicio,
    dg.tipo_iva iva,
    dg.concepto,
    1 cantidad,
    cc.nombre centro_costos,
    coalesce(param.valor,'12') iva_porc
    from gastos g
    inner join detalle_gastos dg
    using(id_gastos)
    inner join detalle_centro_costos dcc
    on dcc.id_documento=dg.id_detalle_gastos
    and dcc.tipo_documento='detalle_gastos'
    inner join centro_costos cc
    using(id_centro_costo),
    parametros param
    where g.estado='Activo'
    and param.descripcion='IVA'
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}
function obtenerDetallesIngreso()
{
    $sql = "select
    p.articulo,
    p.codigo,
    p.cod_barras,
    fc.fecha_actual,
    fc.cantidad,
    fc.comprobante,
    fc.total,
    fc.centro_costos,
    fc.unidad_medida,
    fc.precio_costo
    from (
    select fc.fecha_actual, fc.comprobante, cc.nombre centro_costos,dfc.* from ingresos fc
    inner join detalle_ingreso dfc
    using(id_ingresos)
    inner join detalle_centro_costos dcc
    on dcc.id_documento=dfc.id_detalle_ingreso
    and dcc.tipo_documento='detalle_ingreso'
    inner join centro_costos cc
    using(id_centro_costo)
    where fc.estado='Activo'
    )as fc
    inner join productos p
    using(cod_productos)";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}
function obtenerDetallesEgreso()
{
    $sql = "select
    p.articulo,
    p.codigo,
    p.cod_barras,
    fc.fecha_actual,
    fc.cantidad,
    fc.comprobante,
    fc.total,
    fc.centro_costos,
    fc.unidad_medida,
    fc.precio_costo
    from (
    select fc.fecha_actual, fc.comprobante, cc.nombre centro_costos,dfc.* 
    from egresos fc
    inner join detalle_egreso dfc
    using(id_egresos)
    inner join detalle_centro_costos dcc
    on dcc.id_documento=dfc.id_detalle_egreso
    and dcc.tipo_documento='detalle_egreso'
    inner join centro_costos cc
    using(id_centro_costo)
    where fc.estado='Activo'
    )as fc
    inner join productos p
    using(cod_productos)
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}
function obtenerDetallesLiquidacionC()
{
    $sql = "select
    p.articulo,
    p.codigo,
    p.cod_barras,
    p.iva,
    fc.precio_venta,
    fc.fecha_actual,
    fc.cantidad,
    fc.descuento_producto,
    fc.num_factura,
    fc.total_venta,
    fc.centro_costos,
    fc.bien_servicio,
    coalesce(param.valor,'12') iva_porc
    from (
    select fc.fecha_actual, fc.num_factura, cc.nombre centro_costos ,dfc.* from liquidacion_compra fc
    inner join detalle_liquidacion_compra dfc
    using(id_liquidacion_compra)
    inner join detalle_centro_costos dcc
    on dcc.id_documento=dfc.id_detalle_liquidacion_compra
    and dcc.tipo_documento='detalle_liquidacion_compra'
    inner join centro_costos cc
    using(id_centro_costo)
    where fc.estado='Activo'
    order by dfc.id_detalle_liquidacion_compra asc
    )as fc
    inner join productos p
    using(cod_productos),parametros param
    where param.descripcion='IVA'";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}
function obtenerFacturasVenta()
{
    $sql = "select 
    fc.fecha_actual,
    fc.num_factura,
    fc.tarifa0,
    fc.tarifa12,
    fc.iva_venta,
    fc.descuento_venta,
    fc.total_venta,
    c.identificacion,
    c.nombres_cli
    from factura_venta fc
    inner join clientes c
    using(id_cliente)
    inner join detalle_centro_costos dcc
    on dcc.id_documento=fc.id_factura_venta
    and dcc.tipo_documento='factura_venta'
    inner join centro_costos cc
    using(id_centro_costo)
    where fc.estado='Activo'
    order by fc.id_factura_venta asc";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}
function obtenerNotasVenta()
{
    $sql = "
    select 
    fc.fecha_actual,
    fc.comprobante,
    fc.tarifa0,
    fc.tarifa12,
    fc.iva_venta,
    fc.descuento_venta,
    fc.total_venta,
    c.identificacion,
    c.nombres_cli
    from facturas_novalidas fc
    inner join clientes c
    using(id_cliente)
    inner join detalle_centro_costos dcc
    on dcc.id_documento=fc.id_facturas_novalidas
    and dcc.tipo_documento='facturas_novalidas'
    inner join centro_costos cc
    using(id_centro_costo)
    where fc.estado='Activo'
    order by fc.id_facturas_novalidas asc
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}
function obtenerGastosInternos()
{
    $sql = "
    select 
    fc.comprobante,
    fc.fecha_actual,
    fc.num_factura,
    fc.descripcion,
    fc.total,
    c.identificacion_pro,
    c.empresa_pro
    from gastos_internos fc
    inner join proveedores c
    using(id_proveedor)
    inner join detalle_centro_costos dcc
    on dcc.id_documento=fc.id_gastos
    and dcc.tipo_documento='gastos_internos'
    inner join centro_costos cc
    using(id_centro_costo)
    where fc.estado='Activo'
    order by fc.id_gastos asc
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($res)) {
        return [];
    }
    return $rows;
}

function buildTabla(
    $datos,
    $columnascabecera,
    $columnasdatos,
    $arrofssetwidths,
    $alignscolumnasdatos,
    $colssum,
    $alignscolssum = []
) {
    global $pdf;
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
        /* foreach ($cols as $key => $value3) {
            if (is_numeric($value3)) {
                $cols[$key] = number_format($value3, 2, ",", ".");
            }
            if (is_numeric($colssum[$key])) {
                $colssum[$key] = number_format($colssum[$key], 2, ",", ".");
            }
        } */
        $pdf->Row($cols, 1);
    }
    if (!empty($alignscolssum)) {
        $pdf->SetAligns($alignscolssum);
    }
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Row($colssum);
    $pdf->SetFont('Amble-Regular', '', 9);
}
