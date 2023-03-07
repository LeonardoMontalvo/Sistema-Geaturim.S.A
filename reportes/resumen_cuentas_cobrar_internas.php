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
        $this->Cell($this->GetCurrentWidth() / 2, 5, "RESUMEN CxC Internas", 0, 1, 'R', 0);
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
if (!empty($_GET['id_cliente'])) {
    $querycli = " id_cliente='" . $_GET['id_cliente'] . "'";
}

if (!empty($_GET['id_ruta'])) {
    $querycli = " credito_cupo='" . $_GET['id_ruta'] . "'";
}

if (!empty($_GET['id_vendedor'])) {
    $querycli = " 
    credito_cupo in (select id_ruta from rutas 
    where id_vendedor=" . $_GET['id_vendedor'] . ")
    ";
}

$pdf = new PDF('L', 'mm', 'a4');
$pdf->AddPage();
//$pdf->SetMargins(5, 0);
$pdf->AliasNbPages();
$pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
$pdf->SetFont('Amble-Regular', '', 9);

$pdf->Ln(6);
$pdf->SetFont("Arial", "B", 12);
$pdf->Cell($pdf->GetCurrentWidth(), 5, "RESUMEN CxC INTERNAS", 0, 1, "C");

$registros = getRegistrosPagos($_GET["inicio"], $_GET["fin"]);

$totalw = $pdf->GetCurrentWidth();
$colw = $totalw / 10;

$pdf->SetWidths([
    $colw - 8,
    $colw - 8,
    $colw,
    $colw - 8,
    $colw,
    $colw + 36,
    $colw - 4,
    $colw - 4,
    $colw - 4,
    $colw,
]);
$pdf->SetFont("Arial", "B", 10);
$pdf->SetAligns(array_fill(0, 10, "C"));
$pdf->Row([
    "FACTURA",
    utf8_decode("FECHA EMISIÓN"),
    "FECHA VENCIMIENTO",
    "FECHA PAGO",
    "RUC CLIENTE",
    "NOMBRE CLIENTE",
    utf8_decode("MONTO CRÉDITO"),
    "VALOR PAGO",
    "SALDO",
    "FORMA PAGO"
], 1);
$pdf->SetFont('Amble-Regular', '', 9);
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
    "L"
]);
$pdf->SetFillColor(236, 165, 165);
$tvalorpagado = 0;
foreach ($registros as $value) {
    $formapago = $value["forma_pago"];
    $pintar = "";
    if ($value["estado"] == 'Anulado') {
        $pintar = "FD";
        if (empty($formapago)) {
            $pagoanulado = getPagoCxc($value["id_pagos_cobrar"]);
            $formapago = $pagoanulado["forma_pago"] . " ANULADO";
        }
    }
    $pdf->Row([
        $value["num_factura"],
        $value["fecha_factura"],
        $value["fecha_caducidad"],
        $value["fecha_pago"],
        $value["identificacion"],
        utf8_decode($value["nombres_cli"]),
        number_format($value["monto_credito"], 2, ",", "."),
        number_format($value["valor_pagado"], 2, ",", "."),
        number_format($value["saldo_pendiente"], 2, ",", "."),
        substr(utf8_decode($formapago), 0, 13),
    ], 1, $pintar);
    $tvalorpagado += $value["valor_pagado"];
}
$totales = getTotales($_GET["inicio"], $_GET["fin"]);
$totalcredito = $totales["total_credito"];

if (check_in_range($_GET["inicio"], $_GET["fin"],"2023-02-10")) {
    $totalcredito+=219;
    $tvalorpagado+=219;
}

$totalsaldo =  $totalcredito - $tvalorpagado;

$pdf->Ln(5);
$pdf->SetFont("Arial", "B", 10);

$totalscredito = $totales["total_credito"];
//$totalscredito += obtenerSumaRetencionesF($_GET["inicio"], $_GET["fin"]);
//var_dump($totalscredito);


$pdf->Cell($pdf->GetCurrentWidth() - 25, 5, utf8_decode("TOTAL MONTO CRÉDITO:"), 0, 0, "R");
$pdf->Cell(25, 5, number_format($totalcredito, 2, ",", "."), 0, 1, "R");

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

$querypunto = "AND factura_venta.id_empresa=$puntov";
if (empty($puntov)) {
    $querypunto = "";
}

function obtenerSumaRetencionesF($fechai,$fechaf)
{
    $sql = "select sum(valor_retencion) 
    from retencion_fuente_factura_venta
    where id_retencion_fuente_factura_venta in (2,3,4)
    and fecha_actual between '$fechai' and '$fechaf';";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return 0;
    }
    return $rows[0]["sum"];
}

function getRegistrosPagos($finicio, $ffin)
{
    global $querycli, $querypunto;
    $nquerycli = "";

    if (!empty($querycli)) {
        $nquerycli = "where " . $querycli;
    }

    $sql = "
    DROP TABLE IF EXISTS temp_resuts;
    create temporary table temp_resuts(
        id serial primary key,
        credito_cupo numeric,
        id_pagos_cobrar integer,
        id_cliente integer,
        id_factura_venta integer,
        num_factura text,
        fecha_factura text,
        fecha_caducidad text,
        fecha_pago text,
        identificacion text,
        nombres_cli text,
        monto_credito numeric,
        valor_pagado numeric,
        cobrado numeric,
        saldo_pendiente numeric,
        forma_pago text,
        estado text
    );
    do $$
    declare cnt record;
    declare cnt1 record;
    begin 
    for cnt in
        SELECT
        factura_venta.id_factura_venta, 
        clientes.credito_cupo,
        0 id_pagos_cobrar,
        factura_venta.fecha_actual,
        factura_venta.num_factura,
        pagos_venta.fecha_dias,
        pagos_venta.monto_credito,
        pagos_venta.saldo,
        clientes.identificacion,
        clientes.nombres_cli,
        clientes.id_cliente
        FROM factura_venta
        JOIN pagos_venta ON pagos_venta.id_factura_venta = factura_venta.id_factura_venta
        JOIN clientes ON clientes.id_cliente = factura_venta.id_cliente
        WHERE (
            factura_venta.id_factura_venta IN (
                SELECT formas_pago_mixto.id_factura_venta
                FROM formas_pago_mixto
                WHERE formas_pago_mixto.tipo_documento = 'FACTURA'::text
                    AND formas_pago_mixto.forma_pago = 'CREDITO'::text
                    AND formas_pago_mixto.estado = 'Activo'::text
            )
        )
        AND (
        factura_venta.fecha_actual >= '$finicio'::date
        AND factura_venta.fecha_actual <= '$ffin'::date
        OR factura_venta.num_factura in (
            select num_factura from pagos_cobrar
             where (estado = 'Activo' or estado='Anulado')
                and fecha_actual between '$finicio'::date and '$ffin'::date
            ) 
        )
        AND factura_venta.estado = 'Activo'::text
        AND pagos_venta.tipo_documento = 'Factura'::text
        AND (
            pagos_venta.estado = 'Activo'::text
            OR pagos_venta.estado = 'Cancelado'::text
        )
        $querypunto
        loop 
            insert into temp_resuts (
            id_pagos_cobrar,
            credito_cupo,
            id_cliente,
            id_factura_venta,
            num_factura,
            fecha_factura,
            fecha_caducidad,
            fecha_pago,
            identificacion,
            nombres_cli,
            monto_credito,
            valor_pagado,
            cobrado,
            saldo_pendiente,
            forma_pago,
            estado
            )
            values(
            cnt.id_pagos_cobrar,
            cnt.credito_cupo,
            cnt.id_cliente,
            cnt.id_factura_venta,
            cnt.num_factura,
            cnt.fecha_actual,
            cnt.fecha_dias,
            cnt.fecha_actual,
            cnt.identificacion,
            cnt.nombres_cli,
            cnt.monto_credito,
            0,
            0,
            cnt.monto_credito,
            '',
            'Activo'
            );

        for cnt1 in (
        with fc as(
            SELECT
                factura_venta.fecha_actual,
                factura_venta.num_factura,
                pagos_venta.fecha_dias,
                pagos_venta.monto_credito,
                pagos_venta.saldo,
                pagos_venta.tipo_documento,
                clientes.identificacion,
                clientes.nombres_cli,
                clientes.id_cliente
                FROM factura_venta
                inner join pagos_venta
                on pagos_venta.id_factura_venta=factura_venta.id_factura_venta
                inner join clientes on clientes.id_cliente=factura_venta.id_cliente
                WHERE factura_venta.id_factura_venta =cnt.id_factura_venta
                        AND factura_venta.estado = 'Activo'::text
                        and pagos_venta.tipo_documento='Factura'
                        and (pagos_venta.estado='Activo' or pagos_venta.estado='Cancelado')
                        $querypunto
            )
            select 
            pc.id_pagos_cobrar,
            fc.num_factura,
            fc.fecha_actual fecha_factura,
            fc.fecha_dias fecha_caducidad,
            pc.fecha_actual fecha_pago,
            fc.identificacion,
            fc.nombres_cli,
            fc.monto_credito,
            pc.valor_pagado,
            sum(pc.valor_pagado)over(
            order by pc.id_pagos_cobrar 
            ROWS BETWEEN UNBOUNDED 
            PRECEDING AND CURRENT ROW
            ) cobrado,
            (fc.monto_credito-sum(pc.valor_pagado)over(
            order by pc.id_pagos_cobrar 
            ROWS BETWEEN UNBOUNDED PRECEDING 
            AND CURRENT ROW))::numeric saldo_pendiente,
            fc.id_cliente,
            pc.forma_pago,
            pc.estado
            from pagos_cobrar pc
            inner join fc
            on pc.num_factura=fc.num_factura
            and tipo_factura='Factura'
            where pc.estado='Activo'
            and pc.num_factura=fc.num_factura 
            and pc.fecha_actual between '$finicio' and '$ffin'
            order by pc.id_pagos_cobrar
        )loop
            insert into temp_resuts (
            id_pagos_cobrar,
            credito_cupo,
            id_cliente,
            id_factura_venta,
            num_factura,
            fecha_factura,
            fecha_caducidad,
            fecha_pago,
            identificacion,
            nombres_cli,
            monto_credito,
            valor_pagado,
            cobrado,
            saldo_pendiente,
            forma_pago,
            estado
            )
            values(
            cnt1.id_pagos_cobrar,
            cnt.credito_cupo,
            cnt1.id_cliente,
            cnt.id_factura_venta,
            cnt1.num_factura,
            cnt1.fecha_factura,
            cnt1.fecha_caducidad,
            cnt1.fecha_pago,
            cnt1.identificacion,
            cnt1.nombres_cli,
            --cnt1.monto_credito,
            NULL,
            cnt1.valor_pagado,
            cnt1.cobrado,
            cnt1.saldo_pendiente,
            cnt1.forma_pago,
            cnt1.estado
            );
        end loop;
        for cnt1 in (
            with fc as(
                SELECT
                    factura_venta.fecha_actual,
                    factura_venta.num_factura,
                    pagos_venta.fecha_dias,
                    pagos_venta.monto_credito,
                    pagos_venta.saldo,
                    pagos_venta.tipo_documento,
                    clientes.identificacion,
                    clientes.nombres_cli,
                    clientes.id_cliente
                    FROM factura_venta
                    inner join pagos_venta
                    on pagos_venta.id_factura_venta=factura_venta.id_factura_venta
                    inner join clientes on clientes.id_cliente=factura_venta.id_cliente
                    WHERE factura_venta.id_factura_venta =cnt.id_factura_venta
                            AND factura_venta.estado = 'Activo'::text
                            and pagos_venta.tipo_documento='Factura'
                            and (pagos_venta.estado='Activo' or pagos_venta.estado='Cancelado')
                            $querypunto
                )
                select 
                pc.id_pagos_cobrar,
                fc.num_factura,
                fc.fecha_actual fecha_factura,
                fc.fecha_dias fecha_caducidad,
                pc.fecha_actual fecha_pago,
                fc.identificacion,
                fc.nombres_cli,
                fc.monto_credito,
                pc.valor_pagado,
                0 cobrado,
                0 saldo_pendiente,
                fc.id_cliente,
                pc.forma_pago,
                pc.estado
                from pagos_cobrar pc
                inner join fc
                on pc.num_factura=fc.num_factura
                and tipo_factura='Factura'
                where pc.estado='Anulado'
                and pc.num_factura=fc.num_factura 
                and pc.fecha_actual between '$finicio' and '$ffin'
                order by pc.id_pagos_cobrar
            )loop
                insert into temp_resuts (
                id_pagos_cobrar,
                credito_cupo,
                id_cliente,
                id_factura_venta,
                num_factura,
                fecha_factura,
                fecha_caducidad,
                fecha_pago,
                identificacion,
                nombres_cli,
                monto_credito,
                valor_pagado,
                cobrado,
                saldo_pendiente,
                forma_pago,
                estado
                )
                values(
                cnt1.id_pagos_cobrar,
                cnt.credito_cupo,
                cnt1.id_cliente,
                cnt.id_factura_venta,
                cnt1.num_factura,
                cnt1.fecha_factura,
                cnt1.fecha_caducidad,
                cnt1.fecha_pago,
                cnt1.identificacion,
                cnt1.nombres_cli,
                --cnt1.monto_credito,
                NULL,
                cnt1.valor_pagado,
                cnt1.cobrado,
                cnt1.saldo_pendiente,
                cnt1.forma_pago,
                cnt1.estado
                );
            end loop;
    end loop;
    for cnt in
        SELECT
        factura_venta.id_factura_venta, 
        clientes.credito_cupo,
        0 id_pagos_cobrar,
        factura_venta.fecha_actual,
        factura_venta.num_factura,
        pagos_venta.fecha_dias,
        pagos_venta.monto_credito,
        pagos_venta.saldo,
        clientes.identificacion,
        clientes.nombres_cli,
        clientes.id_cliente,
        pagos_venta.meses,
        pagos_venta.estado,
        pagos_venta.fecha_credito
        FROM factura_venta
        JOIN pagos_venta ON pagos_venta.id_factura_venta = factura_venta.id_factura_venta
        JOIN clientes ON clientes.id_cliente = factura_venta.id_cliente
        WHERE (
            factura_venta.id_factura_venta IN (
                SELECT formas_pago_mixto.id_factura_venta
                FROM formas_pago_mixto
                WHERE formas_pago_mixto.tipo_documento = 'FACTURA'::text
                    AND formas_pago_mixto.forma_pago = 'CREDITO'::text
                    AND formas_pago_mixto.estado = 'Activo'::text
            )
        )
        AND (
        factura_venta.fecha_actual >= '$finicio'::date
        AND factura_venta.fecha_actual <= '$ffin'::date
        AND factura_venta.estado = 'Activo'::text
        AND pagos_venta.tipo_documento = 'Factura'::text
        AND (
            pagos_venta.estado = 'Anulado'::text
        )
        )
        $querypunto
        loop 
            insert into temp_resuts (
            id_pagos_cobrar,
            credito_cupo,
            id_cliente,
            id_factura_venta,
            num_factura,
            fecha_factura,
            fecha_caducidad,
            fecha_pago,
            identificacion,
            nombres_cli,
            monto_credito,
            valor_pagado,
            cobrado,
            saldo_pendiente,
            forma_pago,
            estado
            )
            values(
            cnt.meses::integer,
            cnt.credito_cupo,
            cnt.id_cliente,
            cnt.id_factura_venta,
            cnt.num_factura,
            cnt.fecha_actual,
            cnt.fecha_dias,
            cnt.fecha_credito,
            cnt.identificacion,
            cnt.nombres_cli,
            cnt.monto_credito,
            0,
            0,
            0,
            '',
            cnt.estado
            );
        end loop;
    end;
    $$;
    select *
    from temp_resuts
    $nquerycli
    order by fecha_pago,id_pagos_cobrar asc;
    ";
    
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}

function getTotales($finicio, $ffin)
{
    global $querycli, $querypunto;
    $nquerycli = "";

    if (!empty($querycli)) {
        $nquerycli = "AND(clientes.$querycli)";
    }

    $sql = "
    SELECT
        sum(pagos_venta.monto_credito) total_credito,
        sum(pagos_venta.saldo) total_saldo
        FROM factura_venta
        JOIN pagos_venta ON pagos_venta.id_factura_venta = factura_venta.id_factura_venta
        JOIN clientes ON clientes.id_cliente = factura_venta.id_cliente
        WHERE 
        pagos_venta.fecha_credito between '$finicio'::date AND '$ffin'::date
        AND factura_venta.estado = 'Activo'::text
        AND (pagos_venta.tipo_documento = 'Factura'::text)
        AND (
            pagos_venta.estado = 'Activo'::text
            OR pagos_venta.estado = 'Cancelado'::text
            OR pagos_venta.estado = 'Anulado'::text
        )
        $querypunto
        $nquerycli
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return ["total_credito" => 0, "total_saldo" => 0];
    }
    return $rows[0];
}


function getPagoCxc($id)
{
    $sql = "select*from pagos_cobrar where id_pagos_cobrar=$id";
    $res = pg_query($sql);
    $rows = pg_fetch_assoc($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}

/* Función */
function check_in_range($fecha_inicio, $fecha_fin, $fecha)
{

    $fecha_inicio = strtotime($fecha_inicio);
    $fecha_fin = strtotime($fecha_fin);
    $fecha = strtotime($fecha);

    if (($fecha >= $fecha_inicio) && ($fecha <= $fecha_fin)) {

        return true;
    } else {

        return false;
    }
}
