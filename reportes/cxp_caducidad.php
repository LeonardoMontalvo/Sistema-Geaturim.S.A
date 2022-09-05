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
    function Header()
    {
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->AddFont('helvetica', 'B', 'helveticab.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(0);
        $this->SetY(0);
        $this->Cell(105, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(105, 5, "CARTERA CxP", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(210, 8, $_SESSION['nombre_empresa'], 0, 1, 'C', 0);
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
        $this->Line(0, 25, 210, 25);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 5, utf8_decode("RESUMEN CUENTAS POR CADUCIDAD"), 0, 1, 'C', 0);
        $this->SetFont('Amble-Regular', '', 10);
        $this->Ln(8);
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
$pdf->SetTitle('Caducidad ' . $_GET['tipo'] . ' ' . $_GET['fin']);
$pdf->AliasNbPages();
$pdf->SetFont('Amble-Regular', '', 9);

$total = 0;
$sub = 0;
$desc = 0;
$ivaT = 0;
$subto = 0;
$date = date('Y-m-d');
$otros = false;

if ($_GET['fin'] == '30') {
    $dias = [1, 30];
} elseif ($_GET['fin'] == '60') {
    $dias = [31, 60];
} elseif ($_GET['fin'] == '90') {
    $dias = [61, 90];
} elseif ($_GET['fin'] == '120') {
    $dias = [91, 120];
} else {
    $otros = true;
}

$pdf->SetX(1);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(216, 216, 231);
if ($otros) {
    $pdf->Cell(208, 8, utf8_decode("FACTURAS A CREDITO"), 1, 0, 'C', true);
} else {
    $pdf->Cell(208, 8, utf8_decode("FACTURAS A " . $dias[1] . " DIAS PLAZO"), 1, 0, 'C', true);
}
$pdf->Ln(10);
$pdf->SetFont('Amble-Regular', '', 9);
$pdf->SetX(1);
$query_punto="";

if ($_GET['id_empre'] != '0') {
    $query_punto = "AND fc.id_empresa='$_GET[id_empre]'";
    
}
$query_punto_cp="";

if ($_GET['id_empre'] != '0') {
    $query_punto_cp = "AND fc.id_empresa='$_GET[id_empre]'";
    
}
//CUENTAS EXTERNAS E INTERNAS
if ($_GET['tipo'] == 'Externas') {
    $pdf->SetX(1);
    $pdf->Cell(27, 6, utf8_decode('RUC/CI'), 1, 0, 'C', 0);
    $pdf->Cell(60, 6, utf8_decode('NOMBRES'), 1, 0, 'C', 0);
    $pdf->Cell(34, 6, utf8_decode('COMPROBANTE'), 1, 0, 'C', 0);
    $pdf->Cell(27, 6, utf8_decode('FECHA FACT.'), 1, 0, 'C', 0);
    $pdf->Cell(20, 6, utf8_decode('TOTAL'), 1, 0, 'C', 0);
    $pdf->Cell(20, 6, utf8_decode('ABONOS'), 1, 0, 'C', 0);
    $pdf->Cell(20, 6, utf8_decode('SALDO'), 1, 1, 'C', 0);
    //Tipo de consulta Cancelados, Caducados, Entre fechas
    if ($otros) {
        if ($_GET['fin'] == '1') {
            $sql = pg_query(
                "SELECT identificacion_pro, empresa_pro, num_factura, fecha_actual, total, saldo 
                from c_pagarexternas cp inner join proveedores p using(id_proveedor)
                where cp.estado='Cancelado' $query_punto_cp AND  cp.id_usuario='$_GET[id]';"
            );
        } else {
            $sql = pg_query(
                "SELECT identificacion_pro, empresa_pro, num_factura, fecha_actual, total, saldo
                from c_pagarexternas cp inner join proveedores p using(id_proveedor)
                where (fecha_actual::date - '{$date}'::date) <= 0
                and cp.estado='Activo' $query_punto_cp AND  cp.id_usuario='$_GET[id]';"
            );
        }
    } else {
        $sql = pg_query(
            "SELECT identificacion_pro, empresa_pro, num_factura, fecha_actual, total, saldo 
            from c_pagarexternas cp inner join proveedores p using(id_proveedor)
            where (fecha_actual::date - '{$date}'::date) <= $dias[1]
            and (fecha_actual::date - '{$date}'::date) > $dias[0]
            and cp.estado='Activo' $query_punto_cp AND  cp.id_usuario='$_GET[id]';"
        );
    }

    if (pg_num_rows($sql)) {
        while ($row = pg_fetch_row($sql)) {
            $pdf->SetX(1);
            $pdf->Cell(27, 6, utf8_decode($row[0]), 0, 0, 'l', 0);
            $pdf->Cell(60, 6, maxCaracter(utf8_decode($row[1]), 30), 0, 0, 'l', 0);
            $pdf->Cell(34, 6, utf8_decode($row[2]), 0, 0, 'C', 0);
            $pdf->Cell(27, 6, $row[3], 0, 0, 'C', 0);
            $pdf->Cell(20, 6, utf8_decode(number_format($row[4], 2, ',', '.')), 0, 0, 'C', 0);
            $pdf->Cell(20, 6, utf8_decode(number_format($row[4] - $row[5], 2, ',', '.')), 0, 0, 'C', 0);
            $pdf->Cell(20, 6, utf8_decode(number_format($row[5], 2, ',', '.')), 0, 1, 'C', 0);
            $sub += $row[5];
        }
        $subto += $sub;
    }
} else {
    $pdf->SetX(1);
    $pdf->Cell(27, 6, utf8_decode('RUC/CI'), 1, 0, 'C', 0);
    $pdf->Cell(47, 6, utf8_decode('NOMBRES'), 1, 0, 'C', 0);
    $pdf->Cell(35, 6, utf8_decode('COMPROBNATE'), 1, 0, 'C', 0);
    $pdf->Cell(27, 6, utf8_decode('REGISTRO'), 1, 0, 'C', 0);
    $pdf->Cell(27, 6, utf8_decode('CADUCIDAD'), 1, 0, 'C', 0);
    $pdf->Cell(15, 6, utf8_decode('TOTAL'), 1, 0, 'C', 0);
    $pdf->Cell(15, 6, utf8_decode('ABONOS'), 1, 0, 'C', 0);
    $pdf->Cell(15, 6, utf8_decode('SALDO'), 1, 1, 'C', 0);
    //Tipo de consulta Cancelados, Caducados, Entre fechas
    if ($otros) {
        if ($_GET['fin'] == '1') {
            $sql = pg_query(
                "SELECT p.identificacion_pro, p.empresa_pro, pc.id_factura_compra, pc.fecha_credito, pc.fecha_dias, pc.monto_credito, pc.saldo 
                from  factura_compra fc inner join proveedores p using(id_proveedor)
                left join pagos_compra pc using(id_factura_compra)
                inner join empresa e using(id_empresa) 
                where fc.forma_pago='otros' $query_punto AND  fc.id_usuario='$_GET[id]'
                and  fc.estado='Activo' and pc.estado='Cancelado'
            union         
                SELECT p.identificacion_pro, p.empresa_pro, pc.id_factura_compra, pc.fecha_credito, pc.fecha_dias, pc.monto_credito, pc.saldo 
                from  facturas_novalidas fc inner join proveedores p using(id_proveedor)
                left join pagos_compra pc on pc.id_factura_compra=fc.id_facturas_novalidas
                inner join empresa e using(id_empresa)
                where fc.forma_pago='otros' $query_punto AND  fc.id_usuario='$_GET[id]'
                and  fc.estado='Activo' and pc.estado='Cancelado';"
            );
        } else {
            
            
            	 echo '<br>GUARDAR FACTURA VENTA: <br>' . "SELECT p.identificacion_pro, p.empresa_pro, pc.id_factura_compra, pc.fecha_credito, pc.fecha_dias, pc.monto_credito, pc.saldo
                from  factura_compra fc inner join proveedores p using(id_proveedor)
                left join pagos_compra pc using(id_factura_compra)
                inner join empresa e using(id_empresa) 
                where (fecha_dias::date - '$date'::date) <= 0
                and fc.forma_pago='otros' and  fc.estado='Activo' and pc.estado='Activo' $query_punto AND  fc.id_usuario='$_GET[id]'
                union
                SELECT p.identificacion_pro, p.empresa_pro, pc.id_factura_compra, pc.fecha_credito, pc.fecha_dias, pc.monto_credito, pc.saldo 
                from  facturas_novalidas fc inner join proveedores p using(id_proveedor)
                left join pagos_compra pc on pc.id_factura_compra=fc.id_facturas_novalidas
                inner join empresa e using(id_empresa) 
                where (fecha_dias::date - '$date'::date) <= 0
                and fc.forma_pago='otros' and  fc.estado='Activo' and pc.estado='Activo' $query_punto AND  fc.id_usuario='$_GET[id]'";//////////////////////////
	 
            
            $sql = pg_query(
                "SELECT p.identificacion_pro, p.empresa_pro, pc.id_factura_compra, pc.fecha_credito, pc.fecha_dias, pc.monto_credito, pc.saldo
                from  factura_compra fc inner join proveedores p using(id_proveedor)
                left join pagos_compra pc using(id_factura_compra)
                inner join empresa e using(id_empresa) 
                where (fecha_dias::date - '$date'::date) <= 0
                and fc.forma_pago='otros' and  fc.estado='Activo' and pc.estado='Activo' $query_punto AND  fc.id_usuario='$_GET[id]'
                union
                SELECT p.identificacion_pro, p.empresa_pro, pc.id_factura_compra, pc.fecha_credito, pc.fecha_dias, pc.monto_credito, pc.saldo 
                from  facturas_novalidas fc inner join proveedores p using(id_proveedor)
                left join pagos_compra pc on pc.id_factura_compra=fc.id_facturas_novalidas
                inner join empresa e using(id_empresa) 
                where (fecha_dias::date - '$date'::date) <= 0
                and fc.forma_pago='otros' and  fc.estado='Activo' and pc.estado='Activo' $query_punto AND  fc.id_usuario='$_GET[id]'" 
            );
        }
    } else {
        $sql = pg_query(
            "SELECT p.identificacion_pro, p.empresa_pro, pc.id_factura_compra, pc.fecha_credito, pc.fecha_dias, pc.monto_credito, pc.saldo
            from  factura_compra fc inner join proveedores p using(id_proveedor)
            left join pagos_compra pc using(id_factura_compra)
            inner join empresa e using(id_empresa) 
            where (fecha_dias::date - fecha_credito::date) <= '$dias[1]'
            and (fecha_dias::date - fecha_credito::date) > '$dias[0]'
            and fc.forma_pago='otros' and  fc.estado='Activo' and pc.estado='Activo' $query_punto AND  fc.id_usuario='$_GET[id]'
            union            
            SELECT p.identificacion_pro, p.empresa_pro, pc.id_factura_compra, pc.fecha_credito, pc.fecha_dias, pc.monto_credito, pc.saldo
            from  facturas_novalidas fc inner join proveedores p using(id_proveedor)
            left join pagos_compra pc on pc.id_factura_compra=fc.id_facturas_novalidas
            inner join empresa e using(id_empresa) 
            where (fecha_dias::date - fecha_credito::date) <= '$dias[1]'
            and (fecha_dias::date - fecha_credito::date) > '$dias[0]'
            and fc.forma_pago='otros' and  fc.estado='Activo' and pc.estado='Activo' $query_punto AND  fc.id_usuario='$_GET[id]'"
        );
    }

    if (pg_num_rows($sql)) {
        while ($row = pg_fetch_row($sql)) {
            $pdf->SetX(1);
            $pdf->Cell(27, 6, utf8_decode($row[0]), 0, 0, 'l', 0);
            $pdf->Cell(47, 6, maxCaracter(utf8_decode($row[1]), 30), 0, 0, 'l', 0);
            $pdf->Cell(35, 6, utf8_decode($row[2]), 0, 0, 'C', 0);
            $pdf->Cell(27, 6, $row[3], 0, 0, 'C', 0);
            $pdf->Cell(22, 6, $row[4], 0, 0, 'C', 0);
            $pdf->Cell(15, 6, utf8_decode(number_format($row[5], 2, ',', '.')), 0, 0, 'C', 0);
            $pdf->Cell(15, 6, utf8_decode(number_format($row[5] - $row[6], 2, ',', '.')), 0, 0, 'C', 0);
            $pdf->Cell(15, 6, utf8_decode(number_format($row[6], 2, ',', '.')), 0, 1, 'C', 0);
            $sub += $row[6];
        }
        $subto += $sub;
    }
}

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
$pdf->Cell(186, 6, utf8_decode("Total Saldos:"), 0, 0, 'R', 0);
$pdf->Cell(23, 6, maxCaracter((number_format($subto, 2, ',', '.')), 10), 0, 1, 'C', 0);
$pdf->Ln(3);
$pdf->Output();
