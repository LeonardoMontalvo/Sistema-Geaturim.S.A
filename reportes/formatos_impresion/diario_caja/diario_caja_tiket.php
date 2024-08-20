<?php

require('../../../fpdf/fpdf.php');
include '../../../procesos/base.php';
include '../../../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();

class PDF extends FPDF {

    var $widths;
    var $aligns;

    function SetWidths($w) {
        $this->widths = $w;
    }

    function Header() {
        $this->rango = false;
        if ($_GET['inicio'] != '') {
            $this->rango = true;
        }
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->AddFont('helvetica', 'B', 'helveticab.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(0);
        $this->SetY(2);

        $this->SetFont('Arial', 'B', 9);
        $this->Cell(70, 8, utf8_decode($_SESSION['nombre_empresa']), 0, 1, 'C', 0);

        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
//        $this->Line(0, 27, 210, 27);
        $this->SetFont('Arial', 'B', 12);
//        $this->Cell(210, 5, utf8_decode("DIARIO DE CAJA"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        $this->Ln(3);

        $nombreuser = 0;
        $sqluser = pg_query("SELECT * FROM usuario where usuario.id_usuario='$_GET[id]'");
        while ($row = pg_fetch_row($sqluser)) {
            $nombreuser = $row[10];
        }
        $this->SetX(85);
        $this->Cell(170, 0, "USUARIO:" . $nombreuser, 0, 1, 'L', 0);
//        if ($this->rango) {
//            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
//            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
//        } else {
//            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
//        }
                $cw = 40;
        $this->Cell($cw, 4, utf8_decode("CIERRE DE CAJA"), 0, 1, "C");
        $this->SetFont('Arial', '', 9);
        $this->Cell($cw, 2, utf8_decode("-------------------------------------------------------------------"), 0, 1, "C");
        $this->Ln(2);

        $this->Cell($cw+2, 4, utf8_decode("Fecha de cierre:" . $_GET['inicio']), 0, 1, "L");
//$pdf->Cell($cw, 4, utf8_decode("Hora de cierre: $cierre[hora_cierre]"), 0, 1, "L");
        $this->Cell($cw+2, 4, utf8_decode("Usuario de cierre: $nombreuser"), 0, 1, "L");
        $this->Cell($cw+2, 2, utf8_decode("-------------------------------------------------------------------"), 0, 1, "C");


        $this->Ln(3);
        $this->SetFillColor(255, 255, 225);
        $this->SetLineWidth(0.2);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

//     function GetCurrentWidth()
//    {
//        return $this->w - ($this->lMargin * 2);
//    }
}

$pdf = new PDF('P', 'mm', array(70, 600));
$pdf->SetTitle('Ventas por Cliente');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();
$contado_mixto = 0;
$notaVentacont_mixto = 0;
$total = 0;
$contado = 0;
$cupones = 0;
$nrocupones = 0;
$anticipo_clientes = 0;
$credito = 0;
$cheque = 0;
$gastos = 0;
$gastos2 = 0;
$notaVentacont = 0;
$notaVentacredito = 0;
$notatarjetaCredito = 0;
$tarjetaCredito = 0;
$transferencia = 0;
$notaTransferencia = 0;
$cxce = 0;
$cxcc = 0;
$cxct = 0;
$ncred = 0;
$cxctrans_f = 0;
$cxctrans_nv = 0;
$query_fecha = "";
// RANGO DE FECHAS O FECHA ACTUAL
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}

/////NOTA DE VENTA
$sql = pg_query("SELECT sum(monto::float) FROM anticipo_clientes WHERE fecha_actual $query_fecha '$_GET[fin]'   and id_empresa='$_GET[id1]' and id_usuario='$_GET[id]'");
while ($row = pg_fetch_row($sql)) {
    $anticipo_clientes = $row[0];
}

$sql = pg_query("SELECT sum(total_venta::float) 
FROM factura_venta WHERE fecha_actual $query_fecha '$_GET[fin]' 
and forma_pago='Contado' 
and estado = 'Activo'   
and id_empresa='$_GET[id1]' and id_usuario='$_GET[id]'");
while ($row = pg_fetch_row($sql)) {
    $contado += $row[0];
}
$sqlc2 = pg_query("SELECT sum(valor::float) FROM factura_venta fv 
inner join formas_pago_mixto fpm on fv.id_factura_venta=fpm.id_factura_venta
 WHERE fpm.fecha_actual $query_fecha '$_GET[fin]' and  fpm.forma_pago='CONTADO' and fpm.tipo_documento='FACTURA' and fv.id_empresa='$_GET[id1]' and fv.estado = 'Activo' and  fv.id_usuario='$_GET[id]'");
while ($row = pg_fetch_row($sqlc2)) {
    $contado_mixto += $row[0];
}
$sqlc2 = pg_query("SELECT count(fv.id_factura_venta), sum(valor::float) FROM factura_venta fv 
inner join formas_pago_mixto fpm on fv.id_factura_venta=fpm.id_factura_venta
 WHERE fpm.fecha_actual $query_fecha '$_GET[fin]' and  fpm.forma_pago='CUPON' and fpm.tipo_documento='FACTURA' and fv.id_empresa='$_GET[id1]' and fv.estado = 'Activo' and  fv.id_usuario='$_GET[id]'");
while ($row = pg_fetch_row($sqlc2)) {
    $cupones += $row[1];
    $nrocupones += $row[0];
}
//$sqlc2 = pg_query("SELECT 
//sum(total_venta::float) 
//FROM factura_venta fv 
//inner join formas_pago_mixto fpm
//on fv.id_factura_venta=fpm.id_factura_venta
//WHERE 
//fv.fecha_actual $query_fecha '$_GET[fin]' 
//and (fv.forma_pago='otros' 
//and fv.estado = 'Activo'
//and fpm.forma_pago='CONTADO')   
//and fpm.tipo_documento='FACTURA'
//and fv.id_empresa='$_GET[id1]' and id_usuario='$_GET[id]'");
//while ($row = pg_fetch_row($sqlc2)) {
//    $contado += $row[0];
//}

$sql = pg_query("SELECT sum(total_venta::float) FROM factura_venta WHERE fecha_actual $query_fecha '$_GET[fin]' and forma_pago='Credito' and estado = 'Activo' and id_usuario='$_GET[id]'  and id_empresa='$_GET[id1]'");
while ($row = pg_fetch_row($sql)) {
    $credito = $row[0];
}
$sqlc2 = pg_query("SELECT 
sum(valor::float) 
FROM factura_venta fv 
inner join formas_pago_mixto fpm
on fv.id_factura_venta=fpm.id_factura_venta
WHERE 
fv.fecha_actual $query_fecha '$_GET[fin]' 
and (fv.forma_pago='otros' 
and fv.estado = 'Activo'
and fpm.forma_pago='CREDITO')   
and fpm.tipo_documento='FACTURA'
and fv.id_empresa='$_GET[id1]' and id_usuario='$_GET[id]'");
while ($row = pg_fetch_row($sqlc2)) {
    $credito += $row[0];
}

$sql = pg_query("SELECT sum(total_venta::float) FROM factura_venta WHERE fecha_actual $query_fecha '$_GET[fin]' and forma_pago='Cheque' and estado = 'Activo'   and id_empresa='$_GET[id1]'  and id_usuario='$_GET[id]'");
while ($row = pg_fetch_row($sql)) {
    $cheque = $row[0];
}
$sqlc2 = pg_query("SELECT 
sum(valor::float) 
FROM factura_venta fv 
inner join formas_pago_mixto fpm
on fv.id_factura_venta=fpm.id_factura_venta
WHERE 
fv.fecha_actual $query_fecha '$_GET[fin]' 
and (fv.forma_pago='otros' 
and fv.estado = 'Activo'
and fpm.forma_pago='CHEQUE')   
and fpm.tipo_documento='FACTURA'
and fv.id_empresa='$_GET[id1]' and id_usuario='$_GET[id]'");
while ($row = pg_fetch_row($sqlc2)) {
    $cheque += $row[0];
}

$sql = pg_query("SELECT sum(total_venta::float) FROM factura_venta 
WHERE fecha_actual $query_fecha '$_GET[fin]' 
and forma_pago='TCredito' 
and estado = 'Activo'   
and id_empresa='$_GET[id1]' and id_usuario='$_GET[id]'");
while ($row = pg_fetch_row($sql)) {
    $tarjetaCredito = $row[0];
}
$sqlc2 = pg_query("SELECT 
sum(valor::float) 
FROM factura_venta fv 
inner join formas_pago_mixto fpm
on fv.id_factura_venta=fpm.id_factura_venta
WHERE 
fv.fecha_actual $query_fecha '$_GET[fin]' 
and (fv.forma_pago='otros' 
and fv.estado = 'Activo'
and fpm.forma_pago='TCREDITO')   
and fpm.tipo_documento='FACTURA'
and fv.id_empresa='$_GET[id1]' and id_usuario='$_GET[id]'");
while ($row = pg_fetch_row($sqlc2)) {
    $tarjetaCredito += $row[0];
}

$sql = pg_query("SELECT sum(total_venta::float) 
FROM facturas_novalidas 
WHERE fecha_actual $query_fecha '$_GET[fin]' 
and estado = 'Activo'   
and id_empresa='$_GET[id1]' 
and forma_pago='TCredito' and id_usuario='$_GET[id]' ");
while ($row = pg_fetch_row($sql)) {
    $notatarjetaCredito = $row[0];
}
$sqlc2 = pg_query("SELECT 
sum(valor::float) 
FROM facturas_novalidas fv 
inner join formas_pago_mixto fpm
on fv.id_facturas_novalidas=fpm.id_factura_venta
WHERE 
fv.fecha_actual $query_fecha '$_GET[fin]' 
and (fv.forma_pago='otros' 
and fv.estado = 'Activo'
and fpm.forma_pago='TCREDITO')   
and fpm.tipo_documento='NOTA'
and fv.id_empresa='$_GET[id1]' and fv.id_usuario='$_GET[id]'");
while ($row = pg_fetch_row($sqlc2)) {
    $notatarjetaCredito += $row[0];
}

$sql = pg_query("SELECT sum(total_venta::float) FROM facturas_novalidas WHERE fecha_actual $query_fecha '$_GET[fin]' and estado = 'Activo'  and id_empresa='$_GET[id1]' and forma_pago='Contado'  and id_usuario='$_GET[id]'");
while ($row = pg_fetch_row($sql)) {
    $notaVentacont = $row[0];
}

$sql = pg_query("
with x as(select total from gastos
where id_gastos not in(
select id_gastos from formas_pago_mixto_g
)
and estado='Activo'
and fecha_actual $query_fecha '$_GET[fin]' and id_usuario='$_GET[id]'
union all
select total from gastos
where id_gastos in(
select id_gastos from formas_pago_mixto_g
where forma_pago='CONTADO'
)
and estado='Activo'
and fecha_actual $query_fecha '$_GET[fin]' and id_usuario='$_GET[id]'
)
select sum(total) total from x;
");
while ($row = pg_fetch_row($sql)) {
    $gastos2 += $row[0];
}
//$sqlc2 = pg_query("SELECT 
//sum(total_venta::float) 
//FROM facturas_novalidas fv 
//inner join formas_pago_mixto fpm
//on fv.id_facturas_novalidas=fpm.id_factura_venta
//WHERE 
//fv.fecha_actual $query_fecha '$_GET[fin]' 
//and (fv.forma_pago='otros' 
//and fv.estado = 'Activo'
//and fpm.forma_pago='CONTADO')   
//and fpm.tipo_documento='NOTA'
//and fv.id_empresa='$_GET[id1]' and id_usuario='$_GET[id]'");
//while ($row = pg_fetch_row($sqlc2)) {
//    $notaVentacont += $row[0];
//}
$sqlc2 = pg_query("SELECT 
sum(valor::float) 
FROM facturas_novalidas fv 
inner join formas_pago_mixto fpm
on fv.id_facturas_novalidas=fpm.id_factura_venta
WHERE 
fv.fecha_actual $query_fecha '$_GET[fin]' 
and  fv.estado = 'Activo'
and fpm.forma_pago='CONTADO'  
and fpm.tipo_documento='NOTA'
and fv.id_empresa='$_GET[id1]' and fv.id_usuario='$_GET[id]'");
while ($row = pg_fetch_row($sqlc2)) {
    $notaVentacont_mixto += $row[0];
}
$sql = pg_query("SELECT sum(total_venta::float) FROM facturas_novalidas WHERE fecha_actual $query_fecha '$_GET[fin]' and estado = 'Activo'   and id_empresa='$_GET[id1]' and forma_pago='Credito'  and id_usuario='$_GET[id]'");
while ($row = pg_fetch_row($sql)) {
    $notaVentacredito = $row[0];
}
$sqlc2 = pg_query("SELECT 
sum(valor::float) 
FROM facturas_novalidas fv 
inner join formas_pago_mixto fpm
on fv.id_facturas_novalidas=fpm.id_factura_venta
WHERE 
fv.fecha_actual $query_fecha '$_GET[fin]' 
and (fv.forma_pago='otros' 
and fv.estado = 'Activo'
and fpm.forma_pago='CREDITO')   
and fpm.tipo_documento='NOTA'
and fv.id_empresa='$_GET[id1]' and id_usuario='$_GET[id]'");
while ($row = pg_fetch_row($sqlc2)) {
    $notaVentacredito += $row[0];
}

$sql = pg_query("SELECT sum(total_venta::float) FROM factura_venta WHERE fecha_actual $query_fecha '$_GET[fin]' and forma_pago='Transferencias' and estado = 'Activo'   and id_empresa='$_GET[id1]'  and id_usuario='$_GET[id]'");
while ($row = pg_fetch_row($sql)) {
    $transferencia = $row[0];
}
$sqlc2 = pg_query("SELECT 
sum(valor::float) 
FROM factura_venta fv 
inner join formas_pago_mixto fpm
on fv.id_factura_venta=fpm.id_factura_venta
WHERE 
fv.fecha_actual $query_fecha '$_GET[fin]' 
and (fv.forma_pago='otros' 
and fv.estado = 'Activo'
and fpm.forma_pago='TRANSFERENCIAS')   
and fpm.tipo_documento='FACTURA'
and fv.id_empresa='$_GET[id1]' and fv.id_usuario='$_GET[id]'");
while ($row = pg_fetch_row($sqlc2)) {
    $transferencia += $row[0];
}

$sql = pg_query("SELECT sum(total_venta::float) FROM facturas_novalidas WHERE fecha_actual $query_fecha '$_GET[fin]' and estado = 'Activo'   and id_empresa='$_GET[id1]' and forma_pago='Transferencias'  and id_usuario='$_GET[id]'");
while ($row = pg_fetch_row($sql)) {
    $notaTransferencia = $row[0];
}
$sqlc2 = pg_query("SELECT 
sum(valor::float) 
FROM facturas_novalidas fv 
inner join formas_pago_mixto fpm
on fv.id_facturas_novalidas=fpm.id_factura_venta
WHERE 
fv.fecha_actual $query_fecha '$_GET[fin]' 
and (fv.forma_pago='otros' 
and fv.estado = 'Activo'
and fpm.forma_pago='TRANSFERENCIAS')   
and fpm.tipo_documento='NOTA'
and fv.id_empresa='$_GET[id1]' and fv.id_usuario='$_GET[id]'");
while ($row = pg_fetch_row($sqlc2)) {
    $notaTransferencia += $row[0];
}

$sql = pg_query("SELECT sum(total::float) FROM gastos_internos WHERE fecha_actual $query_fecha '$_GET[fin]'  and id_empresa='$_GET[id1]' AND estado='Activo'  and id_usuario='$_GET[id]';");
while ($row = pg_fetch_row($sql)) {
    $gastos = $row[0];
}

$sql = pg_query("SELECT sum(valor_pagado::float) FROM pagos_cobrar WHERE fecha_actual $query_fecha '$_GET[fin]' AND forma_pago='CONTADO'  and id_empresa='$_GET[id1]' AND estado='Activo' and id_usuario='$_GET[id]';");
while ($row = pg_fetch_row($sql)) {
    $cxce = $row[0];
}

$sql = pg_query("SELECT sum(valor_pagado::float) FROM pagos_cobrar WHERE fecha_actual $query_fecha '$_GET[fin]' AND forma_pago='CHEQUE'  and id_empresa='$_GET[id1]' AND estado='Activo' and id_usuario='$_GET[id]';");
while ($row = pg_fetch_row($sql)) {
    $cxcc = $row[0];
}

$sql = pg_query("SELECT sum(valor_pagado::float) FROM pagos_cobrar WHERE fecha_actual $query_fecha '$_GET[fin]' AND forma_pago='TARJETA'   and id_empresa='$_GET[id1]' AND estado='Activo' and id_usuario='$_GET[id]';");
while ($row = pg_fetch_row($sql)) {
    $cxct = $row[0];
}

//$sql = pg_query("SELECT sum(total_venta::float) FROM devolucion_venta WHERE fecha_actual $query_fecha '$_GET[fin]'   and id_empresa='$_GET[id1]' and estado='Activo' and id_usuario='$_GET[id]'");
$sql = pg_query("SELECT sum(total_venta::float) 
FROM devolucion_venta WHERE  fecha_actual $query_fecha '$_GET[fin]' 
and estado<>'Pasivo'
and id_usuario='$_GET[id]'
and num_serie in(
select num_factura from factura_venta
where forma_pago='Contado'
union
select fv.num_factura from factura_venta fv
inner join formas_pago_mixto fp on fv.id_factura_venta=fp.id_factura_venta
where fp.forma_pago='CONTADO' and tipo_documento='FACTURA'
)
and id_devolucion_venta not in(
SELECT id_devolucion_venta from formas_pago_mixto_nv
where fecha_actual $query_fecha '$_GET[fin]'
);");
while ($row = pg_fetch_row($sql)) {
    $ncred = $row[0];
}

$sql = pg_query("
select
sum(fpm.valor)
from formas_pago_mixto_nv fpm
inner join devolucion_venta dv
using(id_devolucion_venta)
where fpm.forma_pago='CONTADO'
and fpm.tipo_documento='FACTURA'
and fpm.estado='Activo'
and dv.estado<>'Pasivo'
and dv.id_usuario=$_GET[id]
and fpm.fecha_actual  $query_fecha '$_GET[fin]'");

while ($row = pg_fetch_row($sql)) {
    $ncred += $row[0];
}

$sql = pg_query("SELECT sum(valor_pagado::float) FROM pagos_cobrar WHERE fecha_actual $query_fecha '$_GET[fin]' AND forma_pago='TRANSFERENCIA'   and id_empresa='$_GET[id1]' AND estado='Activo' and tipo_factura='Factura' and id_usuario='$_GET[id]';");
while ($row = pg_fetch_row($sql)) {
    $cxctrans_f = $row[0];
}

$sql = pg_query("SELECT sum(valor_pagado::float) FROM pagos_cobrar WHERE fecha_actual $query_fecha '$_GET[fin]' AND forma_pago='TRANSFERENCIA'   and id_empresa='$_GET[id1]' AND estado='Activo' and tipo_factura='Nota' and id_usuario='$_GET[id]';");
while ($row = pg_fetch_row($sql)) {
    $cxctrans_nv = $row[0];
}

$pdf->SetFont('helvetica', 'B', 11);
$pdf->SetX(2);
$pdf->Cell(170, 6, "INGRESOS", 0, 0, 'L', 0);
$pdf->Cell(20, 6, "TOTAL", 0, 1, 'R', 0);

$pdf->SetFont('helvetica', '', 10);

$pdf->SetX(2);
$pdf->Cell(45, 6, "Ventas Efectivo", 0, 0, 'L', 0);
$pdf->Cell(20, 6, (number_format($contado + $contado_mixto, 3, ',', '.')), 0, 1, 'R', 0);

$pdf->SetX(2);
$pdf->Cell(45, 6, utf8_decode("Ventas Crédito"), 0, 0, 'L', 0);
$pdf->Cell(20, 6, (number_format($credito, 3, ',', '.')), 0, 1, 'R', 0);

$pdf->SetX(2);
$pdf->Cell(45, 6, "Ventas Cheque", 0, 0, 'L', 0);
$pdf->Cell(20, 6, (number_format($cheque, 3, ',', '.')), 0, 1, 'R', 0);

$pdf->SetX(2);
$pdf->Cell(45, 6, "Ventas Transferencia", 0, 0, 'L', 0);
$pdf->Cell(20, 6, (number_format($transferencia, 3, ',', '.')), 0, 1, 'R', 0);

$pdf->SetX(2);
$pdf->Cell(45, 6, utf8_decode("Ventas Tarjeta de Crèdito"), 0, 0, 'L', 0);
$pdf->Cell(20, 6, (number_format($tarjetaCredito, 3, ',', '.')), 0, 1, 'R', 0);

$pdf->SetX(2);
$pdf->Cell(45, 6, "Ventas Notas de Venta Contado", 0, 0, 'L', 0);
$pdf->Cell(20, 6, (number_format($notaVentacont + $notaVentacont_mixto, 3, ',', '.')), 0, 1, 'R', 0);
$pdf->SetX(2);
$pdf->Cell(45, 6, utf8_decode("Ventas Notas de V. Crédito"), 0, 0, 'L', 0);
$pdf->Cell(20, 6, (number_format($notaVentacredito, 3, ',', '.')), 0, 1, 'R', 0);
$pdf->SetX(2);
$pdf->Cell(45, 6, utf8_decode("Ventas Notas de V. Transferencia"), 0, 0, 'L', 0);
$pdf->Cell(20, 6, (number_format($notaTransferencia, 3, ',', '.')), 0, 1, 'R', 0);
$pdf->SetX(2);
$pdf->Cell(45, 6, utf8_decode("Ventas Notas de V. T.  Crèdito"), 0, 0, 'L', 0);
$pdf->Cell(20, 6, (number_format($notatarjetaCredito, 3, ',', '.')), 0, 1, 'R', 0);

$pdf->SetX(2);
$pdf->Cell(15, 6, "Cupones ", 0, 0, 'L', 0);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(155, 6, "(Cantidad: $nrocupones)", 0, 0, 'L', 0);
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(20, 6, (number_format($cupones, 3, ',', '.')), 0, 1, 'R', 0);


$pdf->SetX(2);
$pdf->Cell(45, 6, "Anticipo Clientes", 0, 0, 'L', 0);
$pdf->Cell(20, 6, (number_format($anticipo_clientes, 3, ',', '.')), 0, 1, 'R', 0);


$pdf->SetX(2);
$pdf->Cell(45, 6, "Cuentas Cobrar Efectivo", 0, 0, 'L', 0);
$pdf->Cell(20, 6, (number_format($cxce, 3, ',', '.')), 0, 1, 'R', 0);

$pdf->SetX(2);
$pdf->Cell(45, 6, "Cuentas Cobrar Cheque", 0, 0, 'L', 0);
$pdf->Cell(20, 6, (number_format($cxcc, 3, ',', '.')), 0, 1, 'R', 0);

$pdf->SetX(2);
$pdf->Cell(45, 6, "Cuentas Cobrar Tarjeta", 0, 0, 'L', 0);
$pdf->Cell(20, 6, (number_format($cxct, 3, ',', '.')), 0, 1, 'R', 0);

$pdf->SetX(2);
$pdf->Cell(45, 6, "Cuentas Co. Trans. factura", 0, 0, 'L', 0);
$pdf->Cell(20, 6, (number_format($cxctrans_f, 3, ',', '.')), 0, 1, 'R', 0);


$pdf->SetX(2);
$pdf->Cell(45, 6, "Cuentas Co. Trans. Nota venta", 0, 0, 'L', 0);
$pdf->Cell(20, 6, (number_format($cxctrans_nv, 3, ',', '.')), 0, 1, 'R', 0);



//$pdf->SetX(10);
//$pdf->Cell(170, 6, utf8_decode("Ventas Tarjeta de Crèdito"), 0, 0, 'L', 0);
//$pdf->Cell(20, 6, (number_format($tarjetaCredito, 3, ',', '.')), 0, 1, 'R', 0);
//
//$pdf->SetX(10);
//$pdf->Cell(170, 6, utf8_decode("Ventas Notas de Ventas Tarjeta de Crèdito"), 0, 0, 'L', 0);
//$pdf->Cell(20, 6, (number_format($notatarjetaCredito, 3, ',', '.')), 0, 1, 'R', 0);

$ventastotal = $contado + $contado_mixto + $cheque + $credito + $notaVentacont + $notaVentacont_mixto + $notaTransferencia + $transferencia + $notaVentacredito + $cupones + $tarjetaCredito + $notatarjetaCredito;
$otrosConceptos = $cxce + $cxcc + $cxct + $cxctrans_f + $cxctrans_nv + $anticipo_clientes;
$otrosConceptosef = $cxce + $anticipo_clientes;
$totalefectivo = $contado + $contado_mixto + $notaVentacont + $notaVentacont_mixto;


$pdf->Ln(5);

$pdf->SetFont('helvetica', 'B', 8);
$pdf->SetX(2);
$pdf->Cell(45, 6, "RESULTADOS VENTAS TOTAL", 0, 0, 'L', 0);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(20, 6, (number_format(($ventastotal), 3, ',', '.')), 0, 1, 'R', 0);
$pdf->SetFont('helvetica', 'B', 8);
$pdf->SetX(2);
$pdf->Cell(45, 6, "RESULTADOS OTROS CONCEPTOS", 0, 0, 'L', 0);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(20, 6, (number_format(($otrosConceptos), 3, ',', '.')), 0, 1, 'R', 0);
$pdf->SetX(10);

$pdf->Ln(5);
$pdf->SetX(2);
$pdf->SetFont('helvetica', 'B', 8);
$pdf->Cell(20, 6, "(+)RESULTADOS VENTAS EFECTIVO", 0, 0, 'L', 0);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(45, 6, (number_format($totalefectivo, 3, ',', '.')), 0, 1, 'R', 0);

$pdf->SetX(2);
$pdf->SetFont('helvetica', 'B', 8);
$pdf->Cell(45, 6, "(+)RESUL.OTROS CONCEP. EFECTIVO", 0, 0, 'L', 0);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(20, 6, (number_format($otrosConceptosef, 3, ',', '.')), 0, 1, 'R', 0);
$pdf->SetX(2);
/* $pdf->Cell(170, 6, "(-)GASTOS", 0, 0, 'L', 0);
  $pdf->Cell(20, 6, (number_format($gastos+$gastos2, 3, ',', '.')), 0, 1, 'R', 0);
  $pdf->SetX(10); */
$pdf->SetFont('helvetica', 'B', 8);
$pdf->Cell(45, 6, "(-)DEVOLUCIONES CONTADO", 0, 0, 'L', 0);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(20, 6, (number_format($ncred, 3, ',', '.')), 0, 1, 'R', 0);
$pdf->SetX(2);

$gastoscaja = getValorGastosCaja();
$pdf->Cell(45, 6, "(-)GASTOS CAJA", 0, 0, 'L', 0);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(20, 6, (number_format($gastoscaja, 3, ',', '.')), 0, 1, 'R', 0);
$pdf->SetFont('helvetica', 'B', 8);
$pdf->SetX(2);

$pdf->Cell(45, 6, "TOTAL DINERO EN CAJA", 0, 0, 'L', 0);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(20, 6, (number_format((($contado + $contado_mixto + $cxce + $notaVentacont + $notaVentacont_mixto + $anticipo_clientes) - $ncred - $gastoscaja), 3, ',', '.')), 0, 1, 'R', 0);

$pdf->Ln(6);
$pdf->Output();

function getGastosCaja() {
    global $query_fecha;
    $sql = "
    with x as(
        select id_gastos,estado,id_empresa,id_usuario,
        case 
        when tarifa12>0 then ceil((iva_compra*100)/tarifa12)
        else 0 end iva
        from gastos
        where fecha_emision $query_fecha '$_GET[fin]'
        )
        select sum(total_compra)total_compra, tipo_iva, x.iva
        from detalle_gastos dg
        inner join x using(id_gastos)
        where centro_costo='GastosCaja'
        and x.estado='Activo'
        and x.id_empresa=$_GET[id1]
        and x.id_usuario=$_GET[id]
        group by tipo_iva, x.iva
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}

function getValorGastosCaja() {
    $gastos = getGastosCaja();
    $valort = 0;
    foreach ($gastos as $value) {
        if ($value["tipo_iva"] == 'Si') {
            $iva = $value["iva"] / 100;
            $valort += $value["total_compra"] * (1 + $iva);
        } else {
            $valort += $value["total_compra"];
        }
    }
    return $valort;
}

//16122022 francis