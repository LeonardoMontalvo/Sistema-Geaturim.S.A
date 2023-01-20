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
    var $tipoCuenta;
    function SetWidths($w)
    {
        $this->widths = $w;
    }

    function SetTipoCuenta($tc)
    {
        $this->tipoCuenta = $tc;
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
        $this->fecha = date('Y-m-d', time());
        $this->SetX(0);
        $this->SetY(0);
        $this->Cell(105, 5, $this->fecha, 0, 0, 'C', 0);
        $this->Cell(105, 5, "CARTERA CxC", 0, 1, 'C', 0);
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
        $this->Line(0, 28, 210, 28);
        $this->SetFont('Arial', 'B', 12);
        //$this->Cell(210, 5, utf8_decode("RESUMEN CUENTAS EXTERNAS"), 0, 1, 'C', 0);
        $this->Cell(210, 5, utf8_decode("CUENTAS POR COBRAR"), 0, 1, 'C', 0);
        $this->Cell(210, 5, utf8_decode($this->tipoCuenta), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(3);
        /* $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(175, 215, 240);
        $this->Cell(25, 6, utf8_decode('No Factura'), 1, 0, 'C', 1);
        $this->Cell(44, 6, utf8_decode('Emisión'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Vencimiento'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('Días Plazo'), 1, 0, 'C', 1);
        $this->Cell(21, 6, utf8_decode('Días Vence'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Total Venta'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Abonos'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Saldo'), 1, 1, 'C', 1);
        $this->SetFillColor(255, 255, 225); */
        $this->SetLineWidth(0.2);
    }
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$tipoCuenta = "";
if ($_GET['tipo'] == 'Externas') {
    $tipoCuenta = "RESUMEN CUENTAS EXTERNAS";
} elseif ($_GET['tipo'] == 'Internas') {
    $tipoCuenta = "RESUMEN CUENTAS INTERNAS";
} else {
    $tipoCuenta = "RESUMEN CUENTAS INTERNAS Y EXTERNAS";
}

$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetTitle('Cuentas por Cobrar');
$pdf->SetTipoCuenta($tipoCuenta);
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();

$querycli = "";
if (!empty($_GET['id_cliente'])) {
    $querycli = " where id_cliente='" . $_GET['id_cliente'] . "'";
}

if (!empty($_GET['id_ruta'])) {
    $querycli = " where credito_cupo='" . $_GET['id_ruta'] . "'";
}

if (!empty($_GET['id_vendedor'])) {
    $querycli = " where
    credito_cupo in (select id_ruta from rutas 
    where id_vendedor=" . $_GET['id_vendedor'] . ")
    ";
}


$consulta = pg_query(
    "SELECT id_cliente, identificacion, nombres_cli from 
     clientes
     $querycli
     order by id_cliente asc;"
);

if (pg_num_rows($consulta)) {
    $adelantos = 0;
    $totalf = 0;
    $totala = 0;
    $saldos = 0;

    // RANGO DE FECHAS O FECHA ACTUAL
    $query_fecha = "=";
    if ($pdf->rango) {
        $query_fecha = "BETWEEN '$_GET[inicio]' AND";
    }
    $query_punto = "";
    $query_punto_2 = "";
    if ($_GET['id_empre'] != '0') {
        $query_punto = "AND cc.id_empresa='$_GET[id_empre]'";
        $query_punto_2 = "AND pv.id_empresa='$_GET[id_empre]'";
    }

    $id_usuario_fv = "";
    $id_usuario_fv_2 = "";
    if ($_GET['id'] != '0') {
        $id_usuario_fv = "and cc.id_usuario='$_GET[id]'";
        $id_usuario_fv_2 = "and pv.id_usuario='$_GET[id]'";
    }
    while ($row = pg_fetch_assoc($consulta)) {
        if ($_GET['tipo'] == 'Externas') {
            $sql = pg_query(
                "
                SELECT num_factura,
                fecha_actual,
                fecha_emicion,
                (fecha_actual::date - fecha_emicion::date) as dias,
                (fecha_vencimiento::date - date(now())) as vence,
                hora_actual,
                total,
                saldo,
                ( total::numeric-saldo::numeric) as abonos,
                tc.descripcion tipo_documento,
                fecha_vencimiento
                FROM c_cobrarexternas cc
                inner join clientes c using(id_cliente)
                inner join tipo_comprobante tc on tc.id_tipo_comprobante=cc.tipo_documento
                where c.id_cliente=$row[id_cliente] AND fecha_actual $query_fecha '$_GET[fin]' 
                $id_usuario_fv $query_punto order by fecha_emicion asc;"
            );

            if (pg_num_rows($sql)) {
                $subtf = 0;
                $subta = 0;
                $subs = 0;
                $pdf->SetFillColor(220, 240, 210);
                $pdf->SetFont('Helvetica', 'B', 9);
                $pdf->Cell(75, 6, maxCaracter(utf8_decode('RUC/CI:' . $row['identificacion']), 35), 0, 0, 'C', 1);
                $pdf->Cell(135, 6, maxCaracter(utf8_decode('NOMBRES:' . $row['nombres_cli']), 50), 0, 1, 'C', 1);
                $pdf->Ln(1);
                $pdf->SetFillColor(175, 215, 240);
                $pdf->Cell(30, 6, utf8_decode('N° DOCUMENTO'), 1, 0, 'C', 1);
                $pdf->Cell(22, 6, utf8_decode('EMISIÓN'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('VENCIMIENTO'), 1, 0, 'C', 1);
                $pdf->Cell(30, 6, utf8_decode('CADUCA (DÍAS)'), 1, 0, 'C', 1);
                //$pdf->Cell(15, 6, utf8_decode('DIAS'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('TIPO DOC.'), 1, 0, 'C', 1);
                //$pdf->Cell(25, 6, utf8_decode('ADELANTO'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('TOTAL'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('ABONOS'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('SALDO'), 1, 1, 'C', 1);
                while ($row = pg_fetch_assoc($sql)) {
                    $pdf->SetFont('Helvetica', '', 9);
                    $pdf->Cell(30, 6, utf8_decode($row['num_factura']), 0, 0, 'L', 0);
                    $pdf->Cell(22, 6, utf8_decode($row['fecha_emicion']), 0, 0, 'L', 0);
                    $pdf->Cell(25, 6, utf8_decode($row['fecha_vencimiento']), 0, 0, 'L', 0);
                    //$pdf->Cell(22, 6, utf8_decode($row['hora_actual']), 0, 0, 'C', 0);
                    //$pdf->Cell(25, 6, utf8_decode($row['fecha_emicion']), 0, 0, 'C', 0);
                    //$pdf->Cell(20, 6, utf8_decode($row['dias']), 0, 0, 'C', 0);
                    if ($row['vence'] >= 0) {
                        $pdf->Cell(30, 6, utf8_decode($row['vence']), 0, 0, 'L', 0);
                    } else {
                        $pdf->Cell(30, 6, utf8_decode("VENCIDA"), 0, 0, 'L', 0);
                    }
                    //$pdf->Cell(30, 6, utf8_decode($row['vence']), 0, 0, 'C', 0);
                    $pdf->Cell(26, 6, utf8_decode($row['tipo_documento']), 0, 0, 'L', 0);
                    $pdf->Cell(26, 6, utf8_decode(number_format($row['total'], 2, ',', '.')), 0, 0, 'R', 0);
                    $pdf->Cell(26, 6, utf8_decode(number_format($row['abonos'], 2, ',', '.')), 0, 0, 'R', 0);
                    $pdf->Cell(25, 6, utf8_decode(number_format($row['saldo'], 2, ',', '.')), 0, 1, 'R', 0);
                    $subtf += $row['total'];
                    $subta += $row['abonos'];
                    $subs += $row['saldo'];
                }
                $pdf->Cell(300, 0, utf8_decode(""), 1, 1, 'R', 0);
                $pdf->SetFont('Helvetica', 'B', 9);
                $pdf->Cell(134, 6, utf8_decode("Total Cliente:"), 0, 0, 'R', 0);
                $pdf->Cell(25, 6, maxCaracter((number_format($subtf, 2, ',', '.')), 20), 0, 0, 'R', 0);
                $pdf->Cell(26, 6, maxCaracter((number_format($subta, 2, ',', '.')), 20), 0, 0, 'R', 0);
                $pdf->Cell(25, 6, maxCaracter((number_format($subs, 2, ',', '.')), 20), 0, 1, 'R', 0);
                $totalf += $subtf;
                $totala += $subta;
                $saldos += $subs;
            }
        } elseif ($_GET['tipo'] == 'Internas') {
            $sql = pg_query(
                "(
                    SELECT 
                    num_factura, fecha_actual, fecha_dias, 
                    (fecha_dias::date - fecha_actual::date) as dias, 
                    (fecha_dias::date - date(now())) as vence, 
                    adelanto, monto_credito, 
                    pv.saldo, 
                    (monto_credito::numeric-saldo::numeric) as abonos , 
                    pv.tipo_documento,
                    fv.id_factura_venta id_doc,
                    'FACTURA'::text tipo_doc
                    FROM factura_venta fv 
                    inner join clientes c using(id_cliente) 
                    inner join pagos_venta pv using(id_factura_venta)
                    where c.id_cliente=$row[id_cliente] 
                    AND fv.fecha_actual $query_fecha '$_GET[fin]' and fv.estado='Activo'
                    and pv.tipo_documento='Factura'
                    $id_usuario_fv_2    $query_punto_2 order by fecha_actual asc
                )
                union all
                (
                    SELECT 
                    comprobante num_factura, 
                    fecha_actual, 
                    fecha_dias, 
                    (fecha_dias::date - fecha_actual::date) as dias, 
                    (fecha_dias::date - date(now())) as vence, 
                    adelanto, monto_credito, 
                    pv.saldo, 
                    (monto_credito::numeric-saldo::numeric) as abonos , 
                    pv.tipo_documento,
                    fv.id_facturas_novalidas id_doc,
                    'NOTA'::text tipo_doc
                    FROM facturas_novalidas fv 
                    inner join clientes c using(id_cliente) 
                    inner join pagos_venta pv on id_factura_venta=fv.id_facturas_novalidas
                    where c.id_cliente=$row[id_cliente] 
                    AND fv.fecha_actual $query_fecha '$_GET[fin]' and fv.estado='Activo'
                    and pv.tipo_documento='Nota'
                    $id_usuario_fv_2    $query_punto_2 order by fecha_actual asc
                )
                "
            );

            if (pg_num_rows($sql)) {
                $suba = 0;
                $subtf = 0;
                $subta = 0;
                $subs = 0;
                $pdf->SetFillColor(220, 240, 210);
                $pdf->SetFont('Helvetica', 'B', 9);
                $pdf->Cell(75, 6, maxCaracter(utf8_decode('RUC/CI:' . $row['identificacion']), 35), 0, 0, 'C', 1);
                $pdf->Cell(135, 6, maxCaracter(utf8_decode('NOMBRES:' . $row['nombres_cli']), 50), 0, 1, 'C', 1);
                $pdf->Ln(1);
                $pdf->SetFillColor(175, 215, 240);
                $pdf->Cell(30, 6, utf8_decode('N° DOCUMENTO'), 1, 0, 'C', 1);
                $pdf->Cell(22, 6, utf8_decode('EMISIÓN'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('VENCIMIENTO'), 1, 0, 'C', 1);
                $pdf->Cell(30, 6, utf8_decode('CADUCA (DÍAS)'), 1, 0, 'C', 1);
                //$pdf->Cell(15, 6, utf8_decode('DIAS'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('TIPO DOC.'), 1, 0, 'C', 1);
                //$pdf->Cell(25, 6, utf8_decode('ADELANTO'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('TOTAL'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('ABONOS'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('SALDO'), 1, 1, 'C', 1);

                while ($row = pg_fetch_assoc($sql)) {
                    $abonos = $row['abonos'];
                    $totalf1 = $row['monto_credito'];

                    if ($row['tipo_doc'] == 'FACTURA') {
                        $ret = mostrarRetencionFuente($row['id_doc']);
                        if (!empty($ret)) {
                            $totalf1 += $ret["valor_retencion"];
                            $abonos += $ret["valor_retencion"];
                        }
                        $reti = mostrarRetencionIVA($row['id_doc']);
                        if (!empty($ret)) {
                            $totalf1 += $reti["valor_retencion"];
                            $abonos += $reti["valor_retencion"];
                        }
                    }

                    $pdf->SetFont('Helvetica', '', 9);
                    $pdf->Cell(30, 6, utf8_decode($row['num_factura']), 0, 0, 'L', 0);
                    $pdf->Cell(22, 6, utf8_decode($row['fecha_actual']), 0, 0, 'L', 0);
                    $pdf->Cell(25, 6, utf8_decode($row['fecha_dias']), 0, 0, 'L', 0);
                    //$pdf->Cell(20, 6, utf8_decode($row['dias']), 0, 0, 'C', 0);
                    if ($row['vence'] >= 0) {
                        $pdf->Cell(30, 6, utf8_decode($row['vence']), 0, 0, 'L', 0);
                    } else {
                        $pdf->Cell(30, 6, utf8_decode("VENCIDA"), 0, 0, 'L', 0);
                    }

                    $pdf->Cell(26, 6, utf8_decode($row['tipo_documento']), 0, 0, 'L', 0);
                    //$pdf->Cell(26, 6, utf8_decode(number_format($row['adelanto'], 2, ',', '.')), 0, 0, 'R', 0);
                    $pdf->Cell(26, 6, utf8_decode(number_format($totalf1, 2, ',', '.')), 0, 0, 'R', 0);
                    $pdf->Cell(26, 6, utf8_decode(number_format($abonos, 2, ',', '.')), 0, 0, 'R', 0);
                    $pdf->Cell(25, 6, utf8_decode(number_format($row['saldo'], 2, ',', '.')), 0, 1, 'R', 0);
                    $suba += $row['adelanto'];
                    $subtf += $totalf1;
                    $subta += $abonos;
                    $subs += $row['saldo'];
                }
                $pdf->Cell(300, 0, utf8_decode(""), 1, 1, 'R', 0);
                $pdf->SetFont('Helvetica', 'B', 9);
                $pdf->Cell(134, 6, utf8_decode("Total Cliente:"), 0, 0, 'R', 0);
                //$pdf->Cell(22, 6, maxCaracter((number_format($suba, 2, ',', '.')), 20), 0, 0, 'R', 0);
                $pdf->Cell(25, 6, maxCaracter((number_format($subtf, 2, ',', '.')), 20), 0, 0, 'R', 0);
                $pdf->Cell(26, 6, maxCaracter((number_format($subta, 2, ',', '.')), 20), 0, 0, 'R', 0);
                $pdf->Cell(25, 6, maxCaracter((number_format($subs, 2, ',', '.')), 20), 0, 1, 'R', 0);
                $adelantos += $suba;
                $totalf += $subtf;
                $totala += $subta;
                $saldos += $subs;
            }
        } else {
            $filas = obtenerCuentasInternasExternas($row["id_cliente"]);
            if (!empty($filas)) {
                $suba = 0;
                $subtf = 0;
                $subta = 0;
                $subs = 0;
                $pdf->SetFillColor(220, 240, 210);
                $pdf->SetFont('Helvetica', 'B', 9);
                $pdf->Cell(75, 6, maxCaracter(utf8_decode('RUC/CI:' . $row['identificacion']), 35), 0, 0, 'C', 1);
                $pdf->Cell(135, 6, maxCaracter(utf8_decode('NOMBRES:' . $row['nombres_cli']), 50), 0, 1, 'C', 1);
                $pdf->Ln(1);
                $pdf->SetFillColor(175, 215, 240);
                $pdf->Cell(30, 6, utf8_decode('N° DOCUMENTO'), 1, 0, 'C', 1);
                $pdf->Cell(22, 6, utf8_decode('EMISIÓN'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('VENCIMIENTO'), 1, 0, 'C', 1);
                //$pdf->Cell(30, 6, utf8_decode('CADUCA (DÍAS)'), 1, 0, 'C', 1);
                //$pdf->Cell(15, 6, utf8_decode('DIAS'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('TIPO DOC.'), 1, 0, 'C', 1);
                //$pdf->Cell(25, 6, utf8_decode('ADELANTO'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('TOTAL'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('ABONOS'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('SALDO'), 1, 0, 'C', 1);
                $pdf->Cell(30, 6, utf8_decode('CUENTA'), 1, 1, 'C', 1);

                foreach ($filas as $key => $row) {
                    $abonos = $row['abonos'];
                    $totalf1 = $row['total'];
                    if ($row['tipo_doc'] == 'FACTURA') {
                        $ret = mostrarRetencionFuente($row['id_doc']);
                        if (!empty($ret)) {
                            $totalf1 += $ret["valor_retencion"];
                            $abonos += $ret["valor_retencion"];
                        }
                        $reti = mostrarRetencionIVA($row['id_doc']);
                        if (!empty($ret)) {
                            $totalf1 += $reti["valor_retencion"];
                            $abonos += $reti["valor_retencion"];
                        }
                    }
                    $pdf->SetFont('Helvetica', '', 9);
                    $pdf->Cell(30, 6, utf8_decode($row['num_factura']), 0, 0, 'L', 0);
                    $pdf->Cell(22, 6, utf8_decode($row['fecha_emision']), 0, 0, 'L', 0);
                    $pdf->Cell(25, 6, utf8_decode($row['fecha_vencimiento']), 0, 0, 'L', 0);
                    //$pdf->Cell(20, 6, utf8_decode($row['dias']), 0, 0, 'C', 0);
                    //$pdf->Cell(30, 6, utf8_decode($row['vence']), 0, 0, 'C', 0);
                    $pdf->Cell(26, 6, utf8_decode(mb_strtoupper($row['tipo_documento'])), 0, 0, 'L', 0);
                    //$pdf->Cell(26, 6, utf8_decode(number_format($row['adelanto'], 2, ',', '.')), 0, 0, 'R', 0);
                    $pdf->Cell(26, 6, utf8_decode(number_format($totalf1, 2, ',', '.')), 0, 0, 'R', 0);
                    $pdf->Cell(26, 6, utf8_decode(number_format($abonos, 2, ',', '.')), 0, 0, 'R', 0);
                    $pdf->Cell(26, 6, utf8_decode(number_format($row['saldo'], 2, ',', '.')), 0, 0, 'R', 0);
                    $pdf->Cell(30, 6, utf8_decode($row['tipo']), 0, 1, 'C', 0);
                    //$suba += $row['adelanto'];
                    $subtf += $totalf1;
                    $subta += $abonos;
                    $subs += $row['saldo'];
                }
                $pdf->Cell(300, 0, utf8_decode(""), 1, 1, 'R', 0);
                $pdf->SetFont('Helvetica', 'B', 9);
                $pdf->Cell(104, 6, utf8_decode("Total Cliente:"), 0, 0, 'R', 0);
                //$pdf->Cell(22, 6, maxCaracter((number_format($suba, 2, ',', '.')), 20), 0, 0, 'R', 0);
                $pdf->Cell(25, 6, maxCaracter((number_format($subtf, 2, ',', '.')), 20), 0, 0, 'R', 0);
                $pdf->Cell(26, 6, maxCaracter((number_format($subta, 2, ',', '.')), 20), 0, 0, 'R', 0);
                $pdf->Cell(26, 6, maxCaracter((number_format($subs, 2, ',', '.')), 20), 0, 1, 'R', 0);
                $adelantos += $suba;
                $totalf += $subtf;
                $totala += $subta;
                $saldos += $subs;
            }
        }
    }
    $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
    $pdf->SetFont('Helvetica', 'B', 9.5);
    if (empty($_GET['tipo'])) {
        $pdf->Cell(104, 6, utf8_decode("Totales:"), 0, 0, 'R', 0);
    } else {
        $pdf->Cell(135, 6, utf8_decode("Totales:"), 0, 0, 'R', 0);
    }

    $pdf->Cell(25, 6, maxCaracter((number_format($totalf, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(25, 6, maxCaracter((number_format($totala, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(25, 6, maxCaracter((number_format($saldos, 2, ',', '.')), 20), 0, 1, 'R', 0);
}
$pdf->Output();


function obtenerCuentasInternasExternas($idcliente)
{
    global $query_fecha, $id_usuario_fv, $query_punto, $id_usuario_fv_2, $query_punto_2;
    $sql = "
    (
        SELECT
            num_factura,
            fecha_emicion::date fecha_emision,
            fecha_vencimiento::date,
            tc.descripcion tipo_documento,
            (fecha_vencimiento::date - date(now())) as vence,
            total::numeric,
            (total::numeric-saldo::numeric) as abonos,
            saldo,
            'E'::text tipo,
            id_c_cobrarexternas id_doc,
            'EXTERNA'::text tipo_doc
            FROM c_cobrarexternas cc
            inner join clientes c using(id_cliente)
            inner join tipo_comprobante tc on tc.id_tipo_comprobante=cc.tipo_documento
            where c.id_cliente=$idcliente AND fecha_actual $query_fecha '$_GET[fin]' 
            $id_usuario_fv $query_punto 
            order by fecha_emision asc
    )
    union all
    (
        SELECT num_factura,
            fecha_actual fecha_emision,
            fecha_dias fecha_vencimiento,
            pv.tipo_documento,
            (fecha_dias::date - date(now())) as vence,
            monto_credito total,
            (monto_credito::numeric - saldo::numeric) as abonos,
            pv.saldo,
            'I'::text tipo,
            fv.id_factura_venta id_doc,
            'FACTURA'::text tipo_doc
            FROM factura_venta fv inner join clientes c using(id_cliente) inner join pagos_venta pv using(id_factura_venta)
            where c.id_cliente=$idcliente AND fv.fecha_actual $query_fecha '$_GET[fin]'  and fv.estado='Activo'
            $id_usuario_fv_2    $query_punto_2 and pv.tipo_documento='Factura' order by fecha_actual asc
    )
    union all
    (
        SELECT comprobante num_factura,
            fecha_actual fecha_emision,
            fecha_dias fecha_vencimiento,
            pv.tipo_documento,
            (fecha_dias::date - date(now())) as vence,
            monto_credito total,
            (monto_credito::numeric - saldo::numeric) as abonos,
            pv.saldo,
            'I'::text tipo,
            fv.id_facturas_novalidas id_doc,
            'NOTA'::text tipo_doc
        FROM facturas_novalidas fv
            inner join clientes c using(id_cliente)
            inner join pagos_venta pv on id_factura_venta=fv.id_facturas_novalidas
            where c.id_cliente=$idcliente AND fv.fecha_actual $query_fecha '$_GET[fin]' and fv.estado='Activo'
            $id_usuario_fv_2    $query_punto_2 and pv.tipo_documento='Nota' order by fecha_actual asc
    )
    ";

    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}

function mostrarRetencionFuente($idfactura)
{
    $sql = "select * from retencion_fuente_factura_venta 
    where id_factura=$idfactura";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows[0];
}

function mostrarRetencionIVA($idfactura)
{
    $sql = "select * from retencion_iva_factura_venta 
    where id_factura=$idfactura";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows[0];
}
