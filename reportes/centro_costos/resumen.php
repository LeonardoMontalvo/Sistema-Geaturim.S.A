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
$pdf->Ln(0);

$totalw = $pdf->GetCurrentWidth();
$idcuenta = $_GET["id_cuenta"];

$condcuenta_1 = "";
$condcuenta_2 = "";
if (!empty($idcuenta)) {
    $condcuenta_1 = "and dd.id_cuenta=$idcuenta";
    $condcuenta_2 = "and  p.id_plan_cuentas=$idcuenta";
}


//inventario
$fng = function () {
    return gruposCuentasProuctosDocumento(
        "inventario",
        "detalle_inventario",
        "id_inventario",
        "id_detalle_inventario"
    );
};
$tcostoi = buildDocumento(
    "INVENTARIO",
    $fng,
    function ($idplanc) {
        return obtenerDetallesInventario($idplanc);
    },
    [
        utf8_decode("COMPROBANTE"),
        utf8_decode("F. REGISTRO"),
        utf8_decode("PRODUCTO"),
        utf8_decode("CANTIDAD"),
        utf8_decode("P. COMPRA"),
        //utf8_decode("IVA"),
        utf8_decode("TOTAL")
    ],
    [
        "comprobante",
        "fecha_actual",
        "articulo",
        "cantidad",
        "p_costo",
        /* function ($value) {
            $totalreg = $value["p_costo"] * $value["cantidad"];
            $psiniva = $totalreg / (1 + ($value["iva_porc"] / 100));
            $ivaval = $value["iva"] == 'Si' ? utf8_decode($totalreg - $psiniva) : 0;
            return round($ivaval, 2);
        }, */
        function ($value) {
            $totalreg = $value["p_costo"] * $value["cantidad"];
            return $totalreg;
        },

    ],
    [-20, -25, 120, -25, -25, -25],
    ["L", "L", "L", "R", "R", "R"],
    [null, null, "TOTALES", 0, null, 0],
    ["L", "L", "R", "R", "R", "R"]
);

//compras
$fng = function () {
    return gruposCuentasProuctosDocumento(
        "factura_compra",
        "detalle_factura_compra",
        "id_factura_compra",
        "id_detalle_compra"
    );
};
$tcostoc = buildDocumento(
    "COMPRAS",
    $fng,
    function ($idplanc) {
        return obtenerDetallesFacturaCompra($idplanc);
    },
    [
        utf8_decode("FACTURA"),
        utf8_decode("F. EMISIÓN"),
        utf8_decode("PROVEEDOR"),
        utf8_decode("PRODUCTO"),
        utf8_decode("CANTIDAD"),
        utf8_decode("P. COMPRA"),
        //utf8_decode("IVA"),
        utf8_decode("TOTAL")
    ],
    [
        "num_serie",
        "fecha_emision",
        "empresa_pro",
        "articulo",
        "cantidad",
        "precio_compra",
        /* function ($value) {
            $psinva = $value["total_compra"] / (1 + ($value["iva_porc"] / 100));
            $valiva = $value["iva"] == 'Si' ? utf8_decode($value["total_compra"] - $psinva) : 0;
            return round($valiva, 2);
        }, */
        function ($value) {
            return $value["total_compra"];
        },

    ],
    [-5, -20, 40, 45, -20, -20, -20],
    ["L", "L", "L", "L", "R", "R", "R"],
    [null, null, null, "TOTALES", 0, null, 0],
    ["L", "L", "L", "L", "R", "R", "R"]
);

//gastos
$fng = function () {
    return gruposCuentasGastos();
};
$tcostog = buildDocumento(
    "GASTOS",
    $fng,
    function ($idplanc) {
        return obtenerDetallesGastos($idplanc);
    },
    [
        utf8_decode("FACTURA"),
        utf8_decode("F. EMISIÓN"),
        utf8_decode("CONCEPTO"),
        utf8_decode("BIEN/SERV."),
        utf8_decode("VALOR"),
        utf8_decode("TOTAL")
    ],
    [
        "num_factura",
        "fecha_emision",
        "concepto",
        "bien_servicio",
        "precio_compra",
        "total_compra",
    ],
    [-10, -25, 110, -25, -25, -25],
    ["L", "L", "L", "L",  "R", "R"],
    [null, null, null, "TOTALES", 0, 0],
    ["L", "L", "L", "R", "R", "R"]
);

//ingresos
$fng = function () {
    return gruposCuentasProuctosDocumento(
        "ingresos",
        "detalle_ingreso",
        "id_ingresos",
        "id_detalle_ingreso"
    );
};
$tcostoin = buildDocumento(
    "INGRESOS",
    $fng,
    function ($idplanc) {
        return obtenerDetallesIngreso($idplanc);
    },
    [
        utf8_decode("COMPROBANTE"),
        utf8_decode("F. REGISTRO"),
        utf8_decode("PRODUCTO"),
        utf8_decode("CANTIDAD"),
        utf8_decode("P. COMPRA"),
        utf8_decode("TOTAL")
    ],
    [
        "comprobante",
        "fecha_actual",
        "articulo",
        "cantidad",
        "precio_costo",
        function ($value) {
            return $value["cantidad"] * $value["precio_costo"];
        },

    ],
    [-20, -25, 120, -25, -25, -25],
    ["L", "L", "L", "R", "R", "R"],
    [null, null, "TOTALES", 0, null, 0],
    ["L", "L", "L", "R", "R", "R"]
);

//egresos
$fng = function () {
    return gruposCuentasProuctosDocumento(
        "egresos",
        "detalle_egreso",
        "id_egresos",
        "id_detalle_egreso"
    );
};
$tcostoe = buildDocumento(
    "EGRESOS",
    $fng,
    function ($idplanc) {
        return obtenerDetallesEgreso($idplanc);
    },
    [
        utf8_decode("COMPROBANTE"),
        utf8_decode("F. REGISTRO"),
        utf8_decode("PRODUCTO"),
        utf8_decode("CANTIDAD"),
        utf8_decode("P. COMPRA"),
        utf8_decode("TOTAL")
    ],
    [
        "comprobante",
        "fecha_actual",
        "articulo",
        "cantidad",
        "precio_costo",
        function ($value) {
            return $value["cantidad"] * $value["precio_costo"];
        },

    ],
    [-20, -25, 120, -25, -25, -25],
    ["L", "L", "L", "R", "R", "R"],
    [null, null, "Totales", 0, null, 0],
    ["L", "L", "L", "R", "R", "R"]
);

//liquidacion compras
$fng = function () {
    return gruposCuentasProuctosDocumento(
        "liquidacion_compra",
        "detalle_liquidacion_compra",
        "id_liquidacion_compra",
        "id_detalle_liquidacion_compra"
    );
};
$tcostolc = buildDocumento(
    "LIQUIDACIÓN COMPRAS",
    $fng,
    function ($idplanc) {
        return obtenerDetallesLiquidacionC($idplanc);
    },
    [
        utf8_decode("FACTURA"),
        utf8_decode("F. EMISIÓN"),
        utf8_decode("PROVEEDOR"),
        utf8_decode("PRODUCTO"),
        utf8_decode("CANTIDAD"),
        utf8_decode("P. COMPRA"),
        utf8_decode("TOTAL")
    ],
    [
        "num_factura",
        "fecha_actual",
        "empresa_pro",
        "articulo",
        "cantidad",
        "precio_venta",
        function ($value) {
            return $value["total_venta"];
        },

    ],
    [10, -20, 30, 40, -20, -20, -20],
    ["L", "L", "L", "L", "R", "R", "R"],
    [null, null, null, "TOTALES", 0, null, 0],
    ["L", "L", "L", "L", "R", "R", "R"]
);

//gastos internos
$tcostogi = buildTabla(
    "GASTOS INTERNOS",
    "",
    obtenerGastosInternos(),
    [
        utf8_decode("COMPROBANTE"),
        utf8_decode("F. REGISTRO"),
        utf8_decode("FACTURA"),
        utf8_decode("PROVEEDOR"),
        utf8_decode("DESCRIPCIÓN"),
        utf8_decode("TOTAL")
    ],
    [
        "comprobante",
        "fecha_actual",
        "num_factura",
        "empresa_pro",
        "descripcion",
        "total",
    ],
    [-20, -25, -10, 30, 50, -25],
    ["L", "L", "L", "L", "L", "R"],
    [null, null, null, null, "Totales", 0],
    ["L", "L", "L", "L", "R", "R"]
);
$pdf->Ln(5);

//ventas facturas
buildTabla(
    "FACTURAS DE VENTA",
    "",
    obtenerFacturasVenta(),
    [
        utf8_decode("Factura"),
        utf8_decode("F. Emisión"),
        utf8_decode("Identificación"),
        utf8_decode("Cliente"),
        utf8_decode("IVA"),
        utf8_decode("Total")
    ],
    [
        "num_factura",
        "fecha_actual",
        "identificacion",
        "nombres_cli",
        "iva_venta",
        "total_venta"
    ],
    [],
    ["L", "L", "L", "L", "R", "R"],
    [null, null, null, "TOTALES", 0, 0],
    ["L", "L", "L", "L", "R", "R"]
);
$pdf->Ln(5);

//ventas notas
buildTabla(
    "NOTAS DE VENTA",
    "",
    obtenerNotasVenta(),
    [
        utf8_decode("Comprobante"),
        utf8_decode("F. Emisión"),
        utf8_decode("Identificación"),
        utf8_decode("Cliente"),
        utf8_decode("IVA"),
        utf8_decode("Total")
    ],
    [
        "comprobante",
        "fecha_actual",
        "identificacion",
        "nombres_cli",
        "iva_venta",
        "total_venta"
    ],
    [],
    ["L", "L", "L", "L", "R", "R"],
    [null, null, null, "TOTALES", 0, 0],
    ["L", "L", "L", "L", "R", "R"]
);
$pdf->Ln(5);

//total
/* $total =
    $tcostoi + $tcostoc + $tcostog + $tcostoin + $tcostolc + $tcostogi
    - $tcostoe;

mostrarTotal($total); */

$pdf->Output();

//obtener datos
function obtenerDetallesInventario($idplanc)
{
    $sql = "
    select 
    d.comprobante,
    d.fecha_actual,
    dd.disponibles cantidad,
    dd.p_costo,
    p.articulo,
    p.iva,
    p.id_plan_cuentas,
    coalesce(param.valor,'12') iva_porc,
    pc.descripcion,
    p.id_plan_cuentas
    from inventario d
    inner join detalle_inventario dd
    using(id_inventario)
    inner join detalle_centro_costos dcc
    on dcc.id_documento=dd.id_detalle_inventario
    and dcc.tipo_documento = 'detalle_inventario'
    inner join productos p
    using (cod_productos)
    inner join plan_cuentas pc
    using(id_plan_cuentas),
    parametros param
    where d.estado='Activo'
    and param.descripcion='IVA'
    and dcc.id_centro_costo=$_GET[id_cc]
    and p.id_plan_cuentas=$idplanc
    order  by pc.id_plan_cuentas asc, comprobante asc, fecha_actual asc
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($res)) {
        return [];
    }
    return $rows;
}

function obtenerDetallesFacturaCompra($idplanc)
{
    $sql = "
    select 
    d.num_serie,
    d.fecha_emision,
    case
    when dd.cantidad_unidad::numeric>0 then dd.cantidad_unidad::numeric
    else dd.cantidad
    end cantidad,
    dd.precio_compra,
    dd.descuento_producto,
    dd.total_compra,
    coalesce(param.valor,'12') iva_porc,
    p.articulo,
    p.iva,
    pr.empresa_pro,
    pr.identificacion_pro
    from factura_compra d
    inner join proveedores pr
    using(id_proveedor)
    inner join detalle_factura_compra dd
    using(id_factura_compra)
    inner join detalle_centro_costos dcc
    on dcc.id_documento=dd.id_detalle_compra
    and dcc.tipo_documento = 'detalle_factura_compra'
    inner join productos p
    using (cod_productos)
    inner join plan_cuentas pc
    on pc.id_plan_cuentas=p.id_plan_cuentas,
    parametros param
    where d.estado='Activo'
    and param.descripcion='IVA'
    and dcc.id_centro_costo=$_GET[id_cc]
    and p.id_plan_cuentas=$idplanc
    order  by pc.id_plan_cuentas asc, comprobante asc, fecha_actual asc
    ";

    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($res)) {
        return [];
    }
    return $rows;
}

function obtenerDetallesIngreso($idplanc)
{
    $sql = "
    select 
    d.comprobante,
    d.fecha_actual,
    case
    when dd.cantidad_unidad::numeric>0 then dd.cantidad_unidad::numeric
    else dd.cantidad
    end cantidad,
    dd.precio_costo,
    dd.total,
    p.articulo,
    p.iva,
    coalesce(param.valor,'12') iva_porc
    from ingresos d
    inner join detalle_ingreso dd
    using(id_ingresos)
    inner join detalle_centro_costos dcc
    on dcc.id_documento=dd.id_detalle_ingreso
    and dcc.tipo_documento = 'detalle_ingreso'
    inner join productos p
    using (cod_productos)
    inner join plan_cuentas pc
    on pc.id_plan_cuentas=p.id_plan_cuentas,
    parametros param
    where d.estado='Activo'
    and param.descripcion='IVA'
    and dcc.id_centro_costo=$_GET[id_cc]
    and p.id_plan_cuentas=$idplanc
    order  by pc.id_plan_cuentas asc, comprobante asc, fecha_actual asc
    ";

    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($res)) {
        return [];
    }
    return $rows;
}

function obtenerDetallesEgreso($idplanc)
{
    $sql = "
    select 
    d.comprobante,
    d.fecha_actual,
    case
    when dd.cantidad_unidad::numeric>0 then dd.cantidad_unidad::numeric
    else dd.cantidad
    end cantidad,
    dd.precio_costo,
    dd.total,
    p.articulo,
    p.iva,
    coalesce(param.valor,'12') iva_porc
    from egresos d
    inner join detalle_egreso dd
    using(id_egresos)
    inner join detalle_centro_costos dcc
    on dcc.id_documento=dd.id_detalle_egreso
    and dcc.tipo_documento = 'detalle_egreso'
    inner join productos p
    using (cod_productos)
    inner join plan_cuentas pc
    on pc.id_plan_cuentas=p.id_plan_cuentas,
    parametros param
    where d.estado='Activo'
    and param.descripcion='IVA'
    and dcc.id_centro_costo=$_GET[id_cc]
    and p.id_plan_cuentas=$idplanc
    order  by pc.id_plan_cuentas asc, comprobante asc, fecha_actual asc
    ";

    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($res)) {
        return [];
    }
    return $rows;
}

function obtenerDetallesLiquidacionC($idplanc)
{
    $sql = "
    select 
    d.num_factura,
    d.fecha_actual,
    dd.cantidad::numeric cantidad,
    dd.precio_venta,
    dd.descuento_producto,
    dd.total_venta,
    p.articulo,
    p.iva,
    pr.empresa_pro,
    pr.identificacion_pro
    from liquidacion_compra d
    inner join proveedores pr
    using(id_proveedor)
    inner join detalle_liquidacion_compra dd
    using(id_liquidacion_compra)
    inner join detalle_centro_costos dcc
    on dcc.id_documento=dd.id_detalle_liquidacion_compra
    and dcc.tipo_documento = 'detalle_liquidacion_compra'
    inner join productos p
    using (cod_productos)
    inner join plan_cuentas pc
    on pc.id_plan_cuentas=p.id_plan_cuentas,
    parametros param
    where d.estado='Activo'
    and param.descripcion='IVA'
    and dcc.id_centro_costo=$_GET[id_cc]
    and p.id_plan_cuentas=$idplanc
    order  by pc.id_plan_cuentas asc, comprobante asc, fecha_actual asc
    ";

    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($res)) {
        return [];
    }
    return $rows;
}

function obtenerFacturasVenta()
{
    $sql = "
    select 
    d.fecha_actual,
    d.num_factura,
    d.tarifa0,
    d.tarifa12,
    d.iva_venta,
    d.descuento_venta,
    d.total_venta,
    c.identificacion,
    c.nombres_cli
    from factura_venta d
    inner join clientes c
    using(id_cliente)
    inner join detalle_centro_costos dcc
    on dcc.id_documento=d.id_factura_venta
    and dcc.tipo_documento='factura_venta'
    inner join centro_costos cc
    using(id_centro_costo)
    where d.estado='Activo'
    and dcc.id_centro_costo=$_GET[id_cc]
    order by d.id_factura_venta asc
    ";
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
    d.fecha_actual,
    d.comprobante,
    d.tarifa0,
    d.tarifa12,
    d.iva_venta,
    d.descuento_venta,
    d.total_venta,
    c.identificacion,
    c.nombres_cli
    from facturas_novalidas d
    inner join clientes c
    using(id_cliente)
    inner join detalle_centro_costos dcc
    on dcc.id_documento=d.id_facturas_novalidas
    and dcc.tipo_documento='facturas_novalidas'
    inner join centro_costos cc
    using(id_centro_costo)
    where d.estado='Activo'
    and dcc.id_centro_costo=$_GET[id_cc]
    order by d.id_facturas_novalidas asc
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
    d.comprobante,
    d.fecha_actual,
    d.num_factura,
    d.descripcion,
    d.total,
    c.identificacion_pro,
    c.empresa_pro
    from gastos_internos d
    inner join proveedores c
    using(id_proveedor)
    inner join detalle_centro_costos dcc
    on dcc.id_documento=d.id_gastos
    and dcc.tipo_documento='gastos_internos'
    inner join centro_costos cc
    using(id_centro_costo)
    where d.estado='Activo'
    and dcc.id_centro_costo=$_GET[id_cc]
    order by d.id_gastos asc
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($res)) {
        return [];
    }
    return $rows;
}

function obtenerDetallesGastos($idplanc)
{
    $sql = "
    select 
    d.num_factura,
    d.fecha_emision,
    dd.precio_compra,
    dd.total_compra,
    dd.bien_servicio,
    dd.tipo_iva iva,
    dd.concepto,
    1 cantidad,
    cc.nombre centro_costos,
    pr.empresa_pro,
    pr.identificacion_pro
    from gastos d
    inner join proveedores pr
    using(id_proveedor)
    inner join detalle_gastos dd
    using(id_gastos)
    inner join detalle_centro_costos dcc
    on dcc.id_documento=dd.id_detalle_gastos
    and dcc.tipo_documento='detalle_gastos'
    inner join centro_costos cc
    using(id_centro_costo),
    parametros param
    where d.estado='Activo'
    and param.descripcion='IVA'
    and dcc.id_centro_costo=$_GET[id_cc]
    and dd.id_cuenta =$idplanc
    order by dd.id_detalle_gastos asc
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}

function gruposCuentasProuctosDocumento(
    $nombredoc,
    $nombredetalledoc,
    $nombreiddoc,
    $nombreiddetalledoc
) {
    global $condcuenta_2;
    $sql = "
    select 
    p.id_plan_cuentas,
    pc.descripcion
    from $nombredoc d
    inner join $nombredetalledoc dd
    using($nombreiddoc)
    inner join detalle_centro_costos dcc
    on dcc.id_documento=dd.$nombreiddetalledoc
    and dcc.tipo_documento = '$nombredetalledoc'
    inner join productos p
    using (cod_productos)
    inner join plan_cuentas pc
    using(id_plan_cuentas)
    where d.estado='Activo'
    and dcc.id_centro_costo=$_GET[id_cc]
    $condcuenta_2
    group by p.id_plan_cuentas,pc.descripcion
    order by p.id_plan_cuentas
    ";

    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($res)) {
        return [];
    }
    return $rows;
}

function gruposCuentasGastos()
{
    global $condcuenta_1;
    $sql = "
    select 
    dd.id_cuenta id_plan_cuentas,
    pc.descripcion
    from gastos d
    inner join detalle_gastos dd
    using(id_gastos)
    inner join detalle_centro_costos dcc
    on dcc.id_documento=dd.id_detalle_gastos
    and dcc.tipo_documento = 'detalle_gastos'
    inner join plan_cuentas pc
    on pc.id_plan_cuentas=dd.id_cuenta
    where d.estado='Activo'
    and dcc.id_centro_costo=$_GET[id_cc]
    $condcuenta_1
    group by dd.id_cuenta,pc.descripcion
    order by dd.id_cuenta;
    ";

    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($res)) {
        return [];
    }
    return $rows;
}


// funciones utilitarias
function buildDocumento(
    $titulo,
    $fngrupos,
    $datos,
    $columnascabecera,
    $columnasdatos,
    $arrofssetwidths,
    $alignscolumnasdatos,
    $colssum,
    $alignscolssum
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
            "",
            $grupo["descripcion"],
            $datos($grupo["id_plan_cuentas"]),
            $columnascabecera,
            $columnasdatos,
            $arrofssetwidths,
            $alignscolumnasdatos,
            $colssum,
            $alignscolssum
        );
    }
    $pdf->Ln(2);
    $pdf->SetFont('Arial', 'B', 13);
    $pdf->Cell($totalw - 25, 5,  "TOTAL: ", "T", 0, "R");
    $pdf->Cell(25, 5, $total, "T", 1, "R");
    $pdf->Ln(5);

    return $total;
}
function buildTabla(
    $titulo,
    $subtitulo,
    $datos,
    $columnascabecera,
    $columnasdatos,
    $arrofssetwidths,
    $alignscolumnasdatos,
    $colssum,
    $alignscolssum = []
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
function mostrarTituloTabla($titulo)
{
    global $pdf;
    $totalw = $pdf->GetCurrentWidth();
    $pdf->SetFillColor(207, 216, 220);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell($totalw, 5, utf8_decode($titulo), 0, 1, "L", true);
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->Ln(1);
}
function mostrarTituloDocumento($titulo)
{
    global $pdf;
    $totalw = $pdf->GetCurrentWidth();
    $pdf->SetFillColor(66, 66, 66);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell($totalw, 7,  utf8_decode($titulo), 0, 1, "C", true);
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->Ln(1);
}
function mostrarTotal($total)
{
    global $pdf;
    $totalw = $pdf->GetCurrentWidth();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell($totalw - 25, 5,  "Total ", "T", 0, "R");
    $pdf->Cell(25, 5, $total, "T", 1, "R");
    $pdf->Ln(5);
}
