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
        $this->SetX(0);
        $this->SetY(1);
        $this->Cell($this->GetCurrentWidth() / 2, 5, $fecha, 0, 0, 'L', 0);
        $this->Cell($this->GetCurrentWidth() / 2, 5, "RESUMEN CxP Internas", 0, 1, 'R', 0);
        $this->SetFont('Arial', 'B', 16);
        $this->SetX(0);
        $this->Cell($this->GetCurrentWidth(), 8, "EMPRESA: " . $_SESSION['empresa'], 0, 1, 'C', 0);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 5, 8, 35, 28);
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

$puntov = $_GET["id_empre"];

$querycli = "";
if (!empty($_GET['id_proveedor'])) {
    $querycli = " id_proveedor='" . $_GET['id_proveedor'] . "'";
}


$pdf = new PDF('L', 'mm', 'a4');
$pdf->AddPage();
$pdf->SetMargins(5, 0);
$pdf->AliasNbPages();
$pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
$pdf->SetFont('Amble-Regular', '', 9);

$pdf->Ln(6);
$pdf->SetFont("Arial", "B", 12);
$pdf->Cell($pdf->GetCurrentWidth(), 5, "RESUMEN CxP INTERNAS", 0, 1, "C");

$registros = getRegistrosPagos($_GET["inicio"], $_GET["fin"]);

$totalw = $pdf->GetCurrentWidth();
$colw = $totalw / 11;

$pdf->SetWidths([
    $colw + 4,
    $colw - 8,
    $colw - 8,
    $colw - 8,
    $colw - 2,
    $colw + 50,
    $colw - 6,
    $colw - 6,
    $colw - 6,
    $colw - 4,
    $colw - 6,
]);
$pdf->SetFont("Arial", "B", 8);
$pdf->SetAligns(array_fill(0, 11, "C"));
$pdf->Row([
    "FACTURA",
    utf8_decode("FECHA EMISIÓN"),
    "FECHA VENCE",
    "FECHA PAGO",
    "RUC PROVEEDOR",
    "NOMBRE PROVEEDOR",
    utf8_decode("MONTO CRÉDITO"),
    "VALOR PAGO",
    "SALDO",
    "FORMA PAGO",
    "TIPO DOC."
], 1);
$pdf->SetFont('Amble-Regular', '', 8);
$pdf->SetAligns([
    "L",
    "L",
    "L",
    "L",
    "L",
    "L",
    "R",
    "R",
    "R",
    "L",
    "L"
]);
$pdf->SetFillColor(236, 165, 165);
$tvalorpagado = 0;
foreach ($registros as $value) {
    $formapago = $value["forma_pago"];
    $pintar = "";
    if ($value["estado"] == 'Anulado') {
        $pintar = "FD";
        $pagoanulado = getPagoCxp($value["id_cuentas_pagar"]);
        $formapago = $pagoanulado["forma_pago"] . " ANULADO";
    }

    $pdf->Row([
        $value["num_serie"],
        $value["fecha_emision"],
        $value["fecha_caducidad"],
        $value["fecha_pago"],
        $value["identificacion_pro"],
        utf8_decode($value["empresa_pro"]),
        number_format($value["monto_credito"], 2, ",", "."),
        number_format($value["valor_pagado"], 2, ",", "."),
        number_format($value["saldo_pendiente"], 2, ",", "."),
        substr(utf8_decode($formapago), 0, 11),
        $value["tipo_doc"],
    ], 1, $pintar);
    $tvalorpagado += $value["valor_pagado"];
}
$totales = getTotales($_GET["inicio"], $_GET["fin"]);

$totalsaldo = $totales["total_credito"] - $tvalorpagado;

$pdf->Ln(5);
$pdf->SetFont("Arial", "B", 10);

$pdf->Cell($pdf->GetCurrentWidth() - 25, 5, utf8_decode("TOTAL MONTO CRÉDITO:"), 0, 0, "R");
$pdf->Cell(25, 5, number_format($totales["total_credito"], 2, ",", "."), 0, 1, "R");

$pdf->Cell($pdf->GetCurrentWidth() - 25, 5, utf8_decode("TOTAL SALDO PAGADO:"), 0, 0, "R");
$pdf->Cell(25, 5, number_format($tvalorpagado, 2, ",", "."), 0, 1, "R");

$pdf->Cell($pdf->GetCurrentWidth() - 25, 5, utf8_decode("TOTAL SALDO PENDIENTE:"), 0, 0, "R");
//$pdf->Cell(25, 5, $totales["total_saldo"], 0, 1, "R");
$pdf->Cell(25, 5, number_format($totalsaldo, 2, ",", "."), 0, 1, "R");

/* $pdf->SetFont("Arial", "B", 10);
$pdf->Row([
    "",
    "",
    "",
    "",
    "",
    "",
    "TOTAL",
    $tvalorpagado,
    ""
], 1); */

$pdf->Output();

$querypuntoc = "AND factura_compra.id_empresa=$puntov";
$querypuntog = "AND gastos.id_empresa=$puntov";
if (empty($puntov)) {
    $querypuntoc = "";
    $querypuntog = "";
}

function getRegistrosPagos($finicio, $ffin)
{
    global $querycli, $querypuntoc, $querypuntog;
    $nquerycli = "";

    if (!empty($querycli)) {
        $nquerycli = "where " . $querycli;
    }

    $sql = "
    DROP TABLE IF EXISTS temp_results;
    create temporary table temp_results(
        id serial primary key,
        id_cuentas_pagar integer,
        id_proveedor integer,
        id_factura_compra integer,
        num_serie text,
        fecha_emision text,
        fecha_pago text,
        identificacion_pro text,
        empresa_pro text,
        monto_credito numeric,
        valor_pagado numeric,
        cobrado numeric,
        saldo_pendiente numeric,
        forma_pago text,
        tipo_doc text,
        fecha_caducidad text,
        estado text
    );
    ---llenar datos de compras en tabla temporal
    --------------------------
    do $$
    declare cnt record;
    declare cnt1 record;
    begin 
    for cnt in
    SELECT factura_compra.id_factura_compra,
        0 id_cuentas_pagar,
        factura_compra.fecha_emision,
        factura_compra.num_serie,
        pagos_compra.monto_credito,
        pagos_compra.saldo,
        proveedores.identificacion_pro,
        proveedores.empresa_pro,
        proveedores.id_proveedor,
        formas_pago_mixto_c.fecha_actual fecha_caducidad
    FROM factura_compra
        JOIN pagos_compra ON pagos_compra.id_factura_compra = factura_compra.id_factura_compra
        JOIN proveedores ON proveedores.id_proveedor = factura_compra.id_proveedor
        JOIN formas_pago_mixto_c on formas_pago_mixto_c.id_factura_compra = factura_compra.id_factura_compra
    WHERE formas_pago_mixto_c.forma_pago = 'CREDITO'::text
        AND formas_pago_mixto_c.estado = 'Activo'::text
        AND (
            factura_compra.fecha_emision between '$finicio'::date and '$ffin'::date
            OR factura_compra.num_serie in (
                select num_factura
                from pagos_pagar
                where (estado = 'Activo' or estado='Anulado')
                    and fecha_actual between '$finicio'::date and '$ffin'::date
            )
        )
        AND factura_compra.estado = 'Activo'::text
        AND (
            pagos_compra.estado = 'Activo'::text
            OR pagos_compra.estado = 'Cancelado'::text
        )
        $querypuntoc
        AND pagos_compra.comprao_gasto = 'C'
    loop
    insert into temp_results (
            id_cuentas_pagar,
            id_proveedor,
            id_factura_compra,
            num_serie,
            fecha_emision,
            fecha_pago,
            identificacion_pro,
            empresa_pro,
            monto_credito,
            valor_pagado,
            cobrado,
            saldo_pendiente,
            forma_pago,
            tipo_doc,
            fecha_caducidad,
            estado
        )
    values(
            cnt.id_cuentas_pagar,
            cnt.id_proveedor,
            cnt.id_factura_compra,
            cnt.num_serie,
            cnt.fecha_emision,
            cnt.fecha_emision,
            cnt.identificacion_pro,
            cnt.empresa_pro,
            cnt.monto_credito,
            NULL,
            0,
            cnt.monto_credito,
            '---',
            'COMPRA',
            cnt.fecha_caducidad,
            'Activo'
        );
    for cnt1 in (
        with fc as(
            SELECT 
                factura_compra.id_factura_compra,
            factura_compra.fecha_emision,
            factura_compra.num_serie,
            pagos_compra.monto_credito,
            pagos_compra.saldo,
            proveedores.identificacion_pro,
            proveedores.empresa_pro,
            proveedores.id_proveedor
        FROM factura_compra
            JOIN pagos_compra ON pagos_compra.id_factura_compra = factura_compra.id_factura_compra
            JOIN proveedores ON proveedores.id_proveedor = factura_compra.id_proveedor
            WHERE factura_compra.id_factura_compra = cnt.id_factura_compra
                AND factura_compra.estado = 'Activo'::text
                and (
                    pagos_compra.estado = 'Activo'
                    or pagos_compra.estado = 'Cancelado'
                )
                AND pagos_compra.comprao_gasto='C'
                $querypuntoc
        )
        select pc.id_cuentas_pagar,
            fc.num_serie,
            fc.fecha_emision,
            pc.fecha_actual fecha_pago,
            fc.identificacion_pro,
            fc.empresa_pro,
            fc.monto_credito,
            pc.valor_pagado,
            sum(pc.valor_pagado) over(
                order by pc.id_cuentas_pagar ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW
            ) cobrado,
            (
                fc.monto_credito - sum(pc.valor_pagado) over(
                    order by pc.id_cuentas_pagar ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW
                )
            )::numeric saldo_pendiente,
            fc.id_proveedor,
            pc.forma_pago,
            pc.estado
        from pagos_pagar pc
            inner join fc on pc.id_factura_compra = fc.id_factura_compra
        where (pc.estado = 'Activo' or pc.estado='Anulado')
            and pc.fecha_actual between '$finicio' and '$ffin'
        order by pc.id_cuentas_pagar
    ) loop
    insert into temp_results (
            id_cuentas_pagar,
            id_proveedor,
            id_factura_compra,
            num_serie,
            fecha_emision,
            fecha_pago,
            identificacion_pro,
            empresa_pro,
            monto_credito,
            valor_pagado,
            cobrado,
            saldo_pendiente,
            forma_pago,
            tipo_doc,
            fecha_caducidad,
            estado
        )
    values(
            cnt1.id_cuentas_pagar,
            cnt1.id_proveedor,
            cnt.id_factura_compra,
            cnt1.num_serie,
            cnt1.fecha_emision,
            cnt1.fecha_pago,
            cnt1.identificacion_pro,
            cnt1.empresa_pro,
            NULL,
            cnt1.valor_pagado,
            cnt1.cobrado,
            cnt1.saldo_pendiente,
            cnt1.forma_pago,
            'COMPRA',
            cnt.fecha_caducidad,
            cnt1.estado
        );
    end loop;
    end loop;
    for cnt in
    SELECT factura_compra.id_factura_compra,
        0 id_cuentas_pagar,
        factura_compra.fecha_emision,
        factura_compra.num_serie,
        pagos_compra.monto_credito,
        pagos_compra.saldo,
        proveedores.identificacion_pro,
        proveedores.empresa_pro,
        proveedores.id_proveedor,
        formas_pago_mixto_c.fecha_actual fecha_caducidad,
        pagos_compra.fecha_credito,
        pagos_compra.meses,
        pagos_compra.estado
    FROM factura_compra
        JOIN pagos_compra ON pagos_compra.id_factura_compra = factura_compra.id_factura_compra
        JOIN proveedores ON proveedores.id_proveedor = factura_compra.id_proveedor
        JOIN formas_pago_mixto_c on formas_pago_mixto_c.id_factura_compra = factura_compra.id_factura_compra
    WHERE formas_pago_mixto_c.forma_pago = 'CREDITO'::text
        AND formas_pago_mixto_c.estado = 'Activo'::text
        AND factura_compra.estado = 'Activo'::text
        AND pagos_compra.estado = 'Anulado'::text
        $querypuntoc
        AND pagos_compra.comprao_gasto = 'C'
    loop
    insert into temp_results (
            id_cuentas_pagar,
            id_proveedor,
            id_factura_compra,
            num_serie,
            fecha_emision,
            fecha_pago,
            identificacion_pro,
            empresa_pro,
            monto_credito,
            valor_pagado,
            cobrado,
            saldo_pendiente,
            forma_pago,
            tipo_doc,
            fecha_caducidad,
            estado
        )
    values(
            cnt.meses,
            cnt.id_proveedor,
            cnt.id_factura_compra,
            cnt.num_serie,
            cnt.fecha_emision,
            cnt.fecha_credito,
            cnt.identificacion_pro,
            cnt.empresa_pro,
            cnt.monto_credito,
            NULL,
            0,
            cnt.monto_credito,
            '---',
            'COMPRA',
            cnt.fecha_caducidad,
            cnt.estado
        );
        end loop;
    end;
    $$;
    --------------------------
    --------------------------
    
    ---llenar datos de gastos en tabla temporal
    --------------------------
    do $$
    declare cnt record;
    declare cnt1 record;
    begin for cnt in
    SELECT gastos.id_gastos,
        0 id_cuentas_pagar,
        gastos.fecha_emision,
        gastos.num_serie,
        pagos_compra.monto_credito,
        pagos_compra.saldo,
        proveedores.identificacion_pro,
        proveedores.empresa_pro,
        proveedores.id_proveedor,
        formas_pago_mixto_g.fecha_actual fecha_caducidad
    FROM gastos
        JOIN pagos_compra ON pagos_compra.id_factura_compra = gastos.id_gastos
        JOIN proveedores ON proveedores.id_proveedor = gastos.id_proveedor
        JOIN formas_pago_mixto_g on formas_pago_mixto_g.id_gastos = gastos.id_gastos
    WHERE formas_pago_mixto_g.forma_pago = 'CREDITO'::text
    AND formas_pago_mixto_g.estado = 'Activo'::text
    AND (
        gastos.fecha_emision between '$finicio'::date and '$ffin'::date
        OR gastos.num_serie in (
            select num_factura
            from pagos_pagar
            where (estado = 'Activo' or estado='Anulado')
                and fecha_actual between '$finicio'::date and '$ffin'::date
        )
    )
    AND gastos.estado = 'Activo'::text
    AND (
        pagos_compra.estado = 'Activo'::text
        OR pagos_compra.estado = 'Cancelado'::text
    )
    $querypuntog
    AND pagos_compra.comprao_gasto = 'G'
    loop
    insert into temp_results (
            id_cuentas_pagar,
            id_proveedor,
            id_factura_compra,
            num_serie,
            fecha_emision,
            fecha_pago,
            identificacion_pro,
            empresa_pro,
            monto_credito,
            valor_pagado,
            cobrado,
            saldo_pendiente,
            forma_pago,
            tipo_doc,
            fecha_caducidad,
            estado
        )
    values(
            cnt.id_cuentas_pagar,
            cnt.id_proveedor,
            cnt.id_gastos,
            cnt.num_serie,
            cnt.fecha_emision,
            cnt.fecha_emision,
            cnt.identificacion_pro,
            cnt.empresa_pro,
            cnt.monto_credito,
            NULL,
            0,
            cnt.monto_credito,
            '---',
            'GASTO',
            cnt.fecha_caducidad,
            'Activo'
        );
    for cnt1 in (
        with fc as(
            SELECT 
                gastos.id_gastos,
            gastos.fecha_emision,
            gastos.num_serie,
            pagos_compra.monto_credito,
            pagos_compra.saldo,
            pagos_compra.estado,
            proveedores.identificacion_pro,
            proveedores.empresa_pro,
            proveedores.id_proveedor
        FROM gastos
            JOIN pagos_compra ON pagos_compra.id_factura_compra = gastos.id_gastos
            JOIN proveedores ON proveedores.id_proveedor = gastos.id_proveedor
            WHERE gastos.id_gastos = cnt.id_gastos
                AND gastos.estado = 'Activo'::text
                and (
                    pagos_compra.estado = 'Activo'
                    or pagos_compra.estado = 'Cancelado'
                )
                AND pagos_compra.comprao_gasto='G'
                $querypuntog
        )
        select pc.id_cuentas_pagar,
            fc.num_serie,
            fc.fecha_emision,
            pc.fecha_actual fecha_pago,
            fc.identificacion_pro,
            fc.empresa_pro,
            fc.monto_credito,
            pc.valor_pagado,
            sum(pc.valor_pagado) over(
                order by pc.id_cuentas_pagar ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW
            ) cobrado,
            (
                fc.monto_credito - sum(pc.valor_pagado) over(
                    order by pc.id_cuentas_pagar ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW
                )
            )::numeric saldo_pendiente,
            fc.id_proveedor,
            pc.forma_pago,
            pc.estado
        from pagos_pagar pc
            inner join fc on pc.id_factura_compra = fc.id_gastos
        where (pc.estado = 'Activo' or pc.estado='Anulado')
            and pc.fecha_actual between '$finicio' and '$ffin'
        order by pc.id_cuentas_pagar
    ) loop
    insert into temp_results (
            id_cuentas_pagar,
            id_proveedor,
            id_factura_compra,
            num_serie,
            fecha_emision,
            fecha_pago,
            identificacion_pro,
            empresa_pro,
            monto_credito,
            valor_pagado,
            cobrado,
            saldo_pendiente,
            forma_pago,
            tipo_doc,
            fecha_caducidad,
            estado
        )
    values(
            cnt1.id_cuentas_pagar,
            cnt1.id_proveedor,
            cnt.id_gastos,
            cnt1.num_serie,
            cnt1.fecha_emision,
            cnt1.fecha_pago,
            cnt1.identificacion_pro,
            cnt1.empresa_pro,
            NULL,
            cnt1.valor_pagado,
            cnt1.cobrado,
            cnt1.saldo_pendiente,
            cnt1.forma_pago,
            'GASTO',
            cnt.fecha_caducidad,
            cnt1.estado
        );
    end loop;
    end loop;
    for cnt in
    SELECT gastos.id_gastos,
        0 id_cuentas_pagar,
        gastos.fecha_emision,
        gastos.num_serie,
        pagos_compra.monto_credito,
        pagos_compra.saldo,
        pagos_compra.estado,
        proveedores.identificacion_pro,
        proveedores.empresa_pro,
        proveedores.id_proveedor,
        formas_pago_mixto_g.fecha_actual fecha_caducidad,
        pagos_compra.fecha_credito,
        pagos_compra.meses
    FROM gastos
        JOIN pagos_compra ON pagos_compra.id_factura_compra = gastos.id_gastos
        JOIN proveedores ON proveedores.id_proveedor = gastos.id_proveedor
        JOIN formas_pago_mixto_g on formas_pago_mixto_g.id_gastos = gastos.id_gastos
    WHERE formas_pago_mixto_g.forma_pago = 'CREDITO'::text
    AND formas_pago_mixto_g.estado = 'Activo'::text
    AND gastos.estado = 'Activo'::text
    AND pagos_compra.estado = 'Anulado'::text
    $querypuntog
    AND pagos_compra.comprao_gasto = 'G'
    loop
    insert into temp_results (
            id_cuentas_pagar,
            id_proveedor,
            id_factura_compra,
            num_serie,
            fecha_emision,
            fecha_pago,
            identificacion_pro,
            empresa_pro,
            monto_credito,
            valor_pagado,
            cobrado,
            saldo_pendiente,
            forma_pago,
            tipo_doc,
            fecha_caducidad,
            estado
        )
    values(
            cnt.meses,
            cnt.id_proveedor,
            cnt.id_gastos,
            cnt.num_serie,
            cnt.fecha_emision,
            cnt.fecha_credito,
            cnt.identificacion_pro,
            cnt.empresa_pro,
            cnt.monto_credito,
            NULL,
            0,
            cnt.monto_credito,
            '---',
            'GASTO',
            cnt.fecha_caducidad,
            cnt.estado
        );
        end loop;
    end;
    $$;
    --------------------------
    --------------------------
    
    --consultar tabla temporal
    select *
    from temp_results
    $nquerycli
    order by fecha_pago,
    id_cuentas_pagar asc;";
    //echo $sql;    
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}

function getTotales($finicio, $ffin)
{
    global $querycli, $querypuntoc, $querypuntog;;
    $nquerycli = "";

    if (!empty($querycli)) {
        $nquerycli = " AND(proveedores.$querycli)";
    }

    $sql = "
    with c as(
        SELECT sum(pagos_compra.monto_credito) total_credito,
            sum(pagos_compra.saldo) total_saldo
        FROM factura_compra
            JOIN pagos_compra ON pagos_compra.id_factura_compra = factura_compra.id_factura_compra
            JOIN proveedores ON proveedores.id_proveedor = factura_compra.id_proveedor
        WHERE 
            pagos_compra.fecha_credito between '$finicio'::date AND '$ffin'::date
            AND factura_compra.estado = 'Activo'::text
            AND pagos_compra.comprao_gasto = 'C'
            AND (
                pagos_compra.estado = 'Activo'::text
                OR pagos_compra.estado = 'Cancelado'::text
                OR pagos_compra.estado = 'Anulado'::text
            )
            $querypuntoc
            $nquerycli
        ),
        g as(
        SELECT sum(pagos_compra.monto_credito) total_credito,
            sum(pagos_compra.saldo) total_saldo
        FROM gastos
            JOIN pagos_compra ON pagos_compra.id_factura_compra = gastos.id_gastos
            JOIN proveedores ON proveedores.id_proveedor = gastos.id_proveedor
        WHERE 
            pagos_compra.fecha_credito between '$finicio'::date AND '$ffin'::date
            AND gastos.estado = 'Activo'::text
            AND pagos_compra.comprao_gasto = 'G'
            AND (
                pagos_compra.estado = 'Activo'::text
                OR pagos_compra.estado = 'Cancelado'::text
                OR pagos_compra.estado = 'Anulado'::text
            )
            $querypuntog
            $nquerycli
        )
        select 
        (coalesce(g.total_credito,0)+coalesce(c.total_credito,0)) total_credito,
        (coalesce(g.total_saldo,0)+coalesce(c.total_saldo,0)) total_saldo from c,g;
    ";

    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return ["total_credito" => 0, "total_saldo" => 0];
    }
    return $rows[0];
}

function getPagoCxp($id)
{
    $sql = "select*from pagos_pagar where id_cuentas_pagar=$id";
    $res = pg_query($sql);
    $rows = pg_fetch_assoc($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}
