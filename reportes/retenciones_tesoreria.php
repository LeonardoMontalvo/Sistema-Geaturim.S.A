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
        $this->widths = $w;
    }

    function SetAlings($a)
    {
        $this->aligns = $a;
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
        $this->Cell(105, 5, "TESORERIA", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(210, 8, utf8_decode($_SESSION['nombre_empresa']), 0, 1, 'C', 0);
        $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 10, 7, 15, 15);
        $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 180, 7, 15, 15);
        // $this->Cell(180, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        // $this->Cell(80, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        // $this->Cell(80, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(0, 30, 210, 30);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 5, utf8_decode('RETENCIONES'), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(4);
        $this->SetFont('helvetica', 'B', 8);
        $this->SetFillColor(175, 215, 240);
        $this->Cell(20, 6, utf8_decode('RUC/CI'), 1, 0, 'C', 1);
        $this->Cell(60, 6, utf8_decode('NOMBRE'), 1, 0, 'C', 1);
        $this->Cell(18, 6, utf8_decode('ASIENTO'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('FACTURA'), 1, 0, 'C', 1);
        $this->Cell(17, 6, utf8_decode('FECHA'), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode('BASE'), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode('IVA'), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode('RET.'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('NO. RET.'), 1, 1, 'C', 1);
        $this->Ln(2);
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
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->SetTitle('Retenciones');
$pdf->AliasNbPages();

$total_base = 0;
$total_iva = 0;
$total_ret = 0;

$query = pg_query(
    "SELECT id_retencion_fuentes, codigo_formulario, descripcion, valor 
    FROM retencion_fuentes WHERE estado='Activo' ORDER BY codigo_formulario ASC;"
);
if (pg_num_rows($query)) {
    $query_fecha = "";
    // RANGO DE FECHAS O FECHA ACTUAL
    if ($pdf->rango) {
        $query_fecha = "BETWEEN '$_GET[inicio]' AND";
    } else {
        $query_fecha = "=";
    }
    while ($row = pg_fetch_row($query)) {
        // RENTA
        $query_fc = pg_query(
            "SELECT p.identificacion_pro, p.empresa_pro, t.num_transaccion, f.num_serie, f.fecha_actual, d.base_imponible, r.iva_compra, r.valor_retencion, r.num_serie  
            FROM factura_compra f INNER JOIN retencion_fuente_factura_compra r ON f.id_factura_compra=r.id_factura
            INNER JOIN detallecomprobanteretencion d USING(id_retencion_fuente_factura_compra)
            INNER JOIN proveedores p USING(id_proveedor) 
            INNER JOIN retencion_fuentes rf USING(id_retencion_fuentes)
            INNER JOIN transacciones t USING(comprobante)
            WHERE rf.id_retencion_fuentes='$row[0]' AND t.identificador_cli_pro='COM'
            AND d.id_trete=1 AND f.fecha_actual $query_fecha '$_GET[fin]' 
            ORDER BY f.id_factura_compra ASC;"
        );
        $query_g = pg_query(
            "SELECT p.identificacion_pro, p.empresa_pro, t.num_transaccion, f.num_serie, f.fecha_actual, d.base_imponible, r.iva_compra, r.valor_retencion, r.num_serie  
            FROM gastos f INNER JOIN retencion_fuente_factura_compra r ON f.id_gastos=r.id_factura
            INNER JOIN detallecomprobanteretencion d USING(id_retencion_fuente_factura_compra)
            INNER JOIN proveedores p USING(id_proveedor) 
            INNER JOIN retencion_fuentes rf USING(id_retencion_fuentes)
            INNER JOIN transacciones t ON t.comprobante::integer=f.comprobante
            WHERE rf.id_retencion_fuentes='$row[0]' AND t.identificador_cli_pro='GAS'
            AND d.id_trete=1 AND f.fecha_actual $query_fecha '$_GET[fin]'
            ORDER BY f.id_gastos ASC;"
        );
        $query_fv = pg_query(
            "SELECT c.identificacion, c.nombres_cli, t.num_transaccion, f.num_factura, f.fecha_actual, r.valor_compra, r.iva_compra, r.valor_retencion, r.num_serie  
            FROM factura_venta f INNER JOIN retencion_fuente_factura_venta r ON f.id_factura_venta=r.id_factura
            INNER JOIN clientes c USING(id_cliente) 
            INNER JOIN retencion_fuentes_r rf ON rf.id_retencion_fuentes_r=r.id_retencion_fuente_r
            INNER JOIN transacciones t USING(comprobante)
            WHERE rf.id_retencion_fuentes_r='$row[0]' AND t.identificador_cli_pro='VEN'
            AND f.fecha_actual $query_fecha '$_GET[fin]'
            ORDER BY f.id_factura_venta ASC;"
        );
        if (pg_num_rows($query_fc) || pg_num_rows($query_g) || pg_num_rows($query_fv)) {
            $pdf->SetFillColor(220, 240, 210);
            $pdf->SetFont('helvetica', 'B', 7.5);
            $pdf->Cell(165, 6, maxCaracter(utf8_decode($row[1] . ' ' . $row[2]), 100), 0, 0, 'C', 1);
            $pdf->Cell(30, 6, utf8_decode('% de Retencion=> '), 0, 0, 'R', 1);
            $pdf->Cell(15, 6, $row[3], 0, 1, 'C', 1);
            $sub_base = 0;
            $sub_iva = 0;
            $sub_ret = 0;
        }
        if (pg_num_rows($query_fc)) {
            while ($row1 = pg_fetch_row($query_fc)) {
                $sub_base += $row1[5];
                $sub_iva += $row1[6];
                $sub_ret += $row1[7];
                $pdf->SetX(1);
                $pdf->SetFont('helvetica', '', 7);
                $pdf->Cell(19, 6, utf8_decode($row1[0]), 0, 0, 'L', 0);
                $pdf->Cell(60, 6, maxCaracter(utf8_decode($row1[1]), 40), 0, 0, 'L', 0);
                $pdf->Cell(18, 6, utf8_decode($row1[2]), 0, 0, 'C', 0);
                $pdf->Cell(25, 6, utf8_decode($row1[3]), 0, 0, 'C', 0);
                $pdf->Cell(17, 6, utf8_decode($row1[4]), 0, 0, 'C', 0);
                $pdf->Cell(15, 6, number_format($row1[5], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(15, 6, number_format($row1[6], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(15, 6, number_format($row1[7], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(25, 6, utf8_decode($row1[8]), 0, 1, 'C', 0);
            }
        }
        if (pg_num_rows($query_g)) {
            while ($row1 = pg_fetch_row($query_g)) {
                $sub_base += $row1[5];
                $sub_iva += $row1[6];
                $sub_ret += $row1[7];
                $pdf->SetX(1);
                $pdf->SetFont('helvetica', '', 7);
                $pdf->Cell(19, 6, utf8_decode($row1[0]), 0, 0, 'L', 0);
                $pdf->Cell(60, 6, maxCaracter(utf8_decode($row1[1]), 40), 0, 0, 'L', 0);
                $pdf->Cell(18, 6, utf8_decode($row1[2]), 0, 0, 'C', 0);
                $pdf->Cell(25, 6, utf8_decode($row1[3]), 0, 0, 'C', 0);
                $pdf->Cell(17, 6, utf8_decode($row1[4]), 0, 0, 'C', 0);
                $pdf->Cell(15, 6, number_format($row1[5], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(15, 6, number_format($row1[6], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(15, 6, number_format($row1[7], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(25, 6, utf8_decode($row1[8]), 0, 1, 'C', 0);
            }
        }
        if (pg_num_rows($query_fv)) {
            while ($row1 = pg_fetch_row($query_fv)) {
                $sub_base += $row1[5];
                $sub_iva += $row1[6];
                $sub_ret += $row1[7];
                $pdf->SetX(1);
                $pdf->SetFont('helvetica', '', 7);
                $pdf->Cell(19, 6, utf8_decode($row1[0]), 0, 0, 'L', 0);
                $pdf->Cell(60, 6, maxCaracter(utf8_decode($row1[1]), 40), 0, 0, 'L', 0);
                $pdf->Cell(18, 6, utf8_decode($row1[2]), 0, 0, 'C', 0);
                $pdf->Cell(25, 6, utf8_decode($row1[3]), 0, 0, 'C', 0);
                $pdf->Cell(17, 6, utf8_decode($row1[4]), 0, 0, 'C', 0);
                $pdf->Cell(15, 6, number_format($row1[5], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(15, 6, number_format($row1[6], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(15, 6, number_format($row1[7], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(25, 6, utf8_decode($row1[8]), 0, 1, 'C', 0);
            }
        }
        if (pg_num_rows($query_fc) || pg_num_rows($query_g) || pg_num_rows($query_fv)) {
            $pdf->SetFont('helvetica', 'B', 7.5);
            $pdf->Cell(210, 0, utf8_decode(''), 1, 1, 'R', 1);
            $pdf->Cell(140, 6, utf8_decode('Subtotal:'), 0, 0, 'R', 0);
            $pdf->Cell(15, 6, (number_format($sub_base, 2, ',', '.')), 0, 0, 'R', 0);
            $pdf->Cell(15, 6, (number_format($sub_iva, 2, ',', '.')), 0, 0, 'R', 0);
            $pdf->Cell(15, 6, (number_format($sub_ret, 2, ',', '.')), 0, 1, 'R', 0);
            $pdf->Ln(2);
            $total_base += $sub_base;
            $total_iva += $sub_iva;
            $total_ret += $sub_ret;
        }
    }
}

// IVA
$query_iva = pg_query(
    "SELECT id_retencion_iva, descripcion, valor 
    FROM retencion_iva WHERE estado='Activo' ORDER BY valor ASC;"
);
if (pg_num_rows($query_iva)) {
    while ($row = pg_fetch_row($query_iva)) {
        $query_fc = pg_query(
            "SELECT p.identificacion_pro, p.empresa_pro, t.num_transaccion, f.num_serie, f.fecha_actual, d.base_imponible, r.iva_compra, r.valor_retencion, r.num_serie  
            FROM factura_compra f INNER JOIN retencion_fuente_factura_compra r ON f.id_factura_compra=r.id_factura
            INNER JOIN detallecomprobanteretencion d USING(id_retencion_fuente_factura_compra)
            INNER JOIN proveedores p USING(id_proveedor) 
            INNER JOIN retencion_fuentes rf USING(id_retencion_fuentes)
            INNER JOIN transacciones t USING(comprobante)
            WHERE d.id_retencion_fuentes='$row[0]' AND t.identificador_cli_pro='COM'
            AND d.id_trete=2 AND f.fecha_actual $query_fecha '$_GET[fin]' 
            ORDER BY f.id_factura_compra ASC;"
        );
        $query_g = pg_query(
            "SELECT p.identificacion_pro, p.empresa_pro, t.num_transaccion, f.num_serie, f.fecha_actual, d.base_imponible, r.iva_compra, r.valor_retencion, r.num_serie  
            FROM gastos f INNER JOIN retencion_fuente_factura_compra r ON f.id_gastos=r.id_factura
            INNER JOIN detallecomprobanteretencion d USING(id_retencion_fuente_factura_compra)
            INNER JOIN proveedores p USING(id_proveedor) 
            INNER JOIN retencion_fuentes rf USING(id_retencion_fuentes)
            INNER JOIN transacciones t ON t.comprobante::integer=f.comprobante
            WHERE d.id_retencion_fuentes='$row[0]' AND t.identificador_cli_pro='GAS'
            AND d.id_trete=2 AND f.fecha_actual $query_fecha '$_GET[fin]'
            ORDER BY f.id_gastos ASC;"
        );
        $query_fv = pg_query(
            "SELECT c.identificacion, c.nombres_cli, t.num_transaccion, f.num_factura, f.fecha_actual, r.valor_factura, r.iva_factura, r.valor_retencion, r.num_serie  
            FROM factura_venta f INNER JOIN retencion_iva_factura_venta r ON f.id_factura_venta=r.id_factura
            INNER JOIN clientes c USING(id_cliente) 
            INNER JOIN retencion_iva_r rf USING(id_retencion_iva_r)
            INNER JOIN transacciones t USING(comprobante)
            WHERE r.id_retencion_iva_r='$row[0]' AND t.identificador_cli_pro='VEN'
            AND f.fecha_actual $query_fecha '$_GET[fin]'
            ORDER BY f.id_factura_venta ASC;"
        );
        if (pg_num_rows($query_fc) || pg_num_rows($query_g) || pg_num_rows($query_fv)) {
            $pdf->SetFillColor(220, 240, 210);
            $pdf->SetFont('helvetica', 'B', 7.5);
            $pdf->Cell(165, 6, maxCaracter(utf8_decode($row[1]), 100), 0, 0, 'C', 1);
            $pdf->Cell(30, 6, utf8_decode('% de Retencion=> '), 0, 0, 'R', 1);
            $pdf->Cell(15, 6, $row[2], 0, 1, 'C', 1);
            $sub_base = 0;
            $sub_iva = 0;
            $sub_ret = 0;
        }
        if (pg_num_rows($query_fc)) {
            while ($row1 = pg_fetch_row($query_fc)) {
                $sub_base += $row1[5];
                $sub_iva += $row1[6];
                $sub_ret += $row1[7];
                $pdf->SetX(1);
                $pdf->SetFont('helvetica', '', 7);
                $pdf->Cell(19, 6, utf8_decode($row1[0]), 0, 0, 'L', 0);
                $pdf->Cell(60, 6, maxCaracter(utf8_decode($row1[1]), 40), 0, 0, 'L', 0);
                $pdf->Cell(18, 6, utf8_decode($row1[2]), 0, 0, 'C', 0);
                $pdf->Cell(25, 6, utf8_decode($row1[3]), 0, 0, 'C', 0);
                $pdf->Cell(17, 6, utf8_decode($row1[4]), 0, 0, 'C', 0);
                $pdf->Cell(15, 6, number_format($row1[5], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(15, 6, number_format($row1[6], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(15, 6, number_format($row1[7], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(25, 6, utf8_decode($row1[8]), 0, 1, 'C', 0);
            }
        }
        if (pg_num_rows($query_g)) {
            while ($row1 = pg_fetch_row($query_g)) {
                $sub_base += $row1[5];
                $sub_iva += $row1[6];
                $sub_ret += $row1[7];
                $pdf->SetX(1);
                $pdf->SetFont('helvetica', '', 7);
                $pdf->Cell(19, 6, utf8_decode($row1[0]), 0, 0, 'L', 0);
                $pdf->Cell(60, 6, maxCaracter(utf8_decode($row1[1]), 40), 0, 0, 'L', 0);
                $pdf->Cell(18, 6, utf8_decode($row1[2]), 0, 0, 'C', 0);
                $pdf->Cell(25, 6, utf8_decode($row1[3]), 0, 0, 'C', 0);
                $pdf->Cell(17, 6, utf8_decode($row1[4]), 0, 0, 'C', 0);
                $pdf->Cell(15, 6, number_format($row1[5], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(15, 6, number_format($row1[6], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(15, 6, number_format($row1[7], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(25, 6, utf8_decode($row1[8]), 0, 1, 'C', 0);
            }
        }
        if (pg_num_rows($query_fv)) {
            while ($row1 = pg_fetch_row($query_fv)) {
                $sub_base += $row1[5];
                $sub_iva += $row1[6];
                $sub_ret += $row1[7];
                $pdf->SetX(1);
                $pdf->SetFont('helvetica', '', 7);
                $pdf->Cell(19, 6, utf8_decode($row1[0]), 0, 0, 'L', 0);
                $pdf->Cell(60, 6, maxCaracter(utf8_decode($row1[1]), 40), 0, 0, 'L', 0);
                $pdf->Cell(18, 6, utf8_decode($row1[2]), 0, 0, 'C', 0);
                $pdf->Cell(25, 6, utf8_decode($row1[3]), 0, 0, 'C', 0);
                $pdf->Cell(17, 6, utf8_decode($row1[4]), 0, 0, 'C', 0);
                $pdf->Cell(15, 6, number_format($row1[5], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(15, 6, number_format($row1[6], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(15, 6, number_format($row1[7], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(25, 6, utf8_decode($row1[8]), 0, 1, 'C', 0);
            }
        }
        if (pg_num_rows($query_fc) || pg_num_rows($query_g) || pg_num_rows($query_fv)) {
            $pdf->SetFont('helvetica', 'B', 7.5);
            $pdf->Cell(210, 0, utf8_decode(''), 1, 1, 'R', 1);
            $pdf->Cell(140, 6, utf8_decode('Subtotal:'), 0, 0, 'R', 0);
            $pdf->Cell(15, 6, (number_format($sub_base, 2, ',', '.')), 0, 0, 'R', 0);
            $pdf->Cell(15, 6, (number_format($sub_iva, 2, ',', '.')), 0, 0, 'R', 0);
            $pdf->Cell(15, 6, (number_format($sub_ret, 2, ',', '.')), 0, 1, 'R', 0);
            $pdf->Ln(2);
            $total_base += $sub_base;
            $total_iva += $sub_iva;
            $total_ret += $sub_ret;
        }
    }
}

$pdf->SetFont('helvetica', 'B', 8);
$pdf->Cell(210, 0, utf8_decode(''), 1, 1, 'R', 1);
$pdf->Cell(140, 6, utf8_decode('Totales:'), 0, 0, 'R', 0);
$pdf->Cell(15, 6, (number_format($total_base, 2, ',', '.')), 0, 0, 'R', 0);
$pdf->Cell(15, 6, (number_format($total_iva, 2, ',', '.')), 0, 0, 'R', 0);
$pdf->Cell(15, 6, (number_format($total_ret, 2, ',', '.')), 0, 1, 'R', 0);

$pdf->Ln(15);
$pdf->SetX(7);
$pdf->Cell(40, 5, utf8_decode(''), 0, 1, 'R', 0);
$pdf->SetX(7);
$pdf->Cell(40, 0, utf8_decode(''), 1, 1, 'R', 1);
$pdf->SetX(44);
$pdf->Cell(5, 0, "", 0, 0, 'R', 0);
$pdf->SetY($pdf->getY()-5);
$pdf->SetX(57);
$pdf->Cell(40, 5, utf8_decode(''), 0, 1, 'R', 0);
$pdf->SetX(57);
$pdf->Cell(40, 0, utf8_decode(''), 1, 1, 'R', 1);
$pdf->SetX(94);
$pdf->Cell(5, 0, "", 0, 0, 'R', 0);
$pdf->SetY($pdf->getY()-5);
$pdf->SetX(107);
$pdf->Cell(40, 5, utf8_decode(''), 0, 1, 'R', 0);
$pdf->SetX(107);
$pdf->Cell(40, 0, utf8_decode(''), 1, 1, 'R', 1);
$pdf->SetX(144);
$pdf->Cell(5, 0, "", 0, 0, 'R', 0);
$pdf->SetY($pdf->getY()-5);
$pdf->SetX(157);
$pdf->Cell(40, 5, utf8_decode(''), 0, 1, 'R', 0);
$pdf->SetX(157);
$pdf->Cell(40, 0, utf8_decode(''), 1, 1, 'R', 1);
$pdf->Ln(4);
$pdf->SetX(7);
$pdf->Cell(40, 0, utf8_decode('Elaborado por: ' . $_SESSION['user']), 0, 0, 'C', 0);
$pdf->SetX(44);
$pdf->SetX(57);
$pdf->Cell(40, 0, utf8_decode('Aprobado'), 0, 0, 'C', 0);
$pdf->SetX(94);
$pdf->SetX(107);
$pdf->Cell(40, 0, utf8_decode('Contabilidad'), 0, 0, 'C', 0);
$pdf->SetX(144);
$pdf->SetX(157);
$pdf->Cell(40, 0, utf8_decode('Recibí Conforme'), 0, 1, 'C', 0);
$pdf->Ln(3);
$pdf->SetX(157);
$pdf->Cell(40, 0, utf8_decode('C.I.:'), 0, 1, 'L', 0);

$pdf->Output();
