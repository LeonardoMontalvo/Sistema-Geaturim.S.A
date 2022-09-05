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
        $this->Cell(105, 5, "CONTABILIDAD", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(250, 8, $_SESSION['nombre_empresa'], 0, 1, 'C', 0);
        $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 10, 7, 15, 15);
        $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 220, 7, 15, 15);
        // $this->Cell(180, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        // $this->Cell(80, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        // $this->Cell(80, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(0, 25, 290, 25);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 5, utf8_decode('MAYOR GENERAL'), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(3);
        $this->SetFont('helvetica', 'B', 8);
        $this->SetFillColor(175, 250, 240);
        $this->Cell(17.5, 6, utf8_decode('ASIENTO'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('FECHA'), 1, 0, 'C', 1);
        $this->Cell(10, 6, utf8_decode('TD'), 1, 0, 'C', 1);
        $this->Cell(17.5, 6, utf8_decode('NUMERO'), 1, 0, 'C', 1);
        $this->Cell(50, 6, utf8_decode('BENEFICIARIO'), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode('N.Cheque'), 1, 0, 'C', 1);
        $this->Cell(120, 6, utf8_decode('DETALLE'), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode('DEBE'), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode('HABER'), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode('SALDO'), 1, 1, 'C', 1);
        $this->Ln(1);
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

$pdf = new PDF('L', 'mm', 'a4');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->SetTitle('Mayor General');
$pdf->AliasNbPages();

$total_debe = 0;
$total_haber = 0;
$query_id = "";
$rango_id = false;
if ($_GET['id_inicio'] != '') {
    $rango_id = true;
}
if ($rango_id) {
    $query_id = "BETWEEN '$_GET[id_inicio]' AND";
} else {
    $query_id = "=";
}
$query_punto="";

if ($_GET['id_empre'] != '0') {
    $query_punto = "AND t.id_empresa='$_GET[id_empre]'";
}

$query = pg_query(
    "SELECT id_plan_cuentas, codigo_plan, descripcion FROM plan_cuentas 
    WHERE estado='Activo'
    AND id_plan_cuentas " . $query_id . " '$_GET[id_fin]' ORDER BY codigo_plan ASC;"
);
if (pg_num_rows($query)) {
    while ($row = pg_fetch_row($query)) {
        $sub_debe = 0;
        $sub_haber = 0;
        $query_fecha = "";
        // RANGO DE FECHAS O FECHA ACTUAL
        if ($pdf->rango) {
            $query_fecha = "BETWEEN '$_GET[inicio]' AND";
        } else {
            $query_fecha = "=";
        }

        $query_detalle = pg_query(
            "  SELECT T.num_transaccion,T.fecha_registro,t.comprobante,identificador_cli_pro, p_g.empresa_pro as empresa_g,c.nombres_cli,t.concepto as concepto_trans,
 debito,credito , fpm_g.numero_documento as num_docu_gasto,fpc.numero_documento as num_docu_comp,fpmv.numero_documento as num_docu_venta,
 string_agg(dg.concepto,',') as concepto_gasto,   string_agg(pro.articulo,',') as articulo_co,string_agg(prov.articulo,',') as articulo_ve,
 p_c.empresa_pro as empresa_comp, string_agg(fpc.numero_documento,',')
 
            FROM transacciones t            
            INNER JOIN detalle_transaccion dt USING(id_transacciones)           
           
              left JOIN gastos g on g.id_gastos=T.comprobante::integer
                 left JOIN detalle_gastos dg on dg.id_gastos=g.id_gastos
                   left JOIN formas_pago_mixto_g fpm_g on g.id_gastos=fpm_g.id_gastos
                    left JOIN proveedores p_g   on p_g.id_proveedor=g.id_proveedor
                   
            
               left JOIN factura_compra fc on fc.id_factura_compra=T.comprobante::integer
                 left JOIN detalle_factura_compra dfc on fc.id_factura_compra=dfc.id_factura_compra
                  left JOIN productos pro on pro.cod_productos=dfc.cod_productos
                    left JOIN formas_pago_mixto_c fpc on fc.id_factura_compra=fpc.id_factura_compra
            left JOIN proveedores p_c   on p_c.id_proveedor=fc.id_proveedor
                  
               
                left JOIN factura_venta fv on fv.id_factura_venta=T.comprobante::integer
                     left JOIN detalle_factura_venta dfv on dfv.id_factura_venta=fv.id_factura_venta
               left JOIN productos prov on prov.cod_productos= dfv.cod_productos
                 left JOIN formas_pago_mixto fpmv on fv.id_factura_venta=fpmv.id_factura_venta
             left JOIN clientes c on c.id_cliente=fv.id_cliente
                         
             
                WHERE 

              dt.id_plan_cuentas = '$row[0]'  AND T.fecha_registro " . $query_fecha . " '$_GET[fin]'
            AND T.estado='Activo' $query_punto
            group by t.id_transacciones,T.fecha_registro,t.comprobante,identificador_cli_pro, p_g.empresa_pro,c.nombres_cli,t.concepto,
 debito,credito , fpm_g.numero_documento,fpc.numero_documento,fpmv.numero_documento ,p_c.empresa_pro,dfc.id_factura_compra,dg.id_gastos,
 dfv.id_factura_venta ORDER BY t.fecha_registro ASC"
        );
        if (pg_num_rows($query_detalle)) {
            // RANGO DE FECHAS O FECHA ACTUAL
            if ($pdf->rango) {
                $query_fecha = "BETWEEN '$_GET[inicio]'::date - 1 AND";
            } else {
                $query_fecha = "=";
            }
            $pdf->SetFillColor(220, 250, 210);
            $pdf->SetFont('helvetica', 'B', 7.5);
            // saldo anterior
            $saldo_anterior = pg_fetch_row(pg_query(
                "SELECT SUM(saldo) FROM transacciones T INNER JOIN tipo_transaccion TT USING(id_tipo_transaccion) 
                INNER JOIN detalle_transaccion D USING(id_transacciones) 
                INNER JOIN plan_cuentas P USING(id_plan_cuentas)
                WHERE P.id_plan_cuentas='$row[0]' AND T.fecha_registro::date " . $query_fecha . " '$_GET[fin]'::date - 1 AND T.estado='Activo';"
            ));
            
            $saldo = $saldo_anterior[0];
            $pdf->Cell(257, 6, utf8_decode($row[1] . ' ' . $row[2]), 0, 0, 'L', 1);
            $pdf->Cell(30, 6, utf8_decode('SALDO ANTERIOR=> '), 0, 0, 'R', 1);
            $pdf->Cell(15, 6, number_format($saldo, 2, ',', '.'), 0, 1, 'L', 1);
            while ($row1 = pg_fetch_row($query_detalle)) {
                  $pos1 = "";
    $pos2 = "";
    $pos3 = "";

    $pizza = $row1[6];

    $porciones = explode(":", $pizza);
    if (count($porciones) == 2) {
        $pos1 = $porciones[1]; // porción1
    }
    if (count($porciones) == 3) {
        $pos1 = $porciones[1]; // porción1
        $pos2 = $porciones[2];
    }
    if (count($porciones) == 4) {
        $pos1 = $porciones[1]; // porción1
        $pos2 = $porciones[2];
        $pos3 = $porciones[3];
    }
    $pos4 = $pos1 . "" . $pos2 . "" . $pos3;

                $sub_debe += $row1[7];
                $saldo += $row1[7];
                $sub_haber += $row1[8];
                $saldo -= $row1[8];
                $pdf->SetX(1);
                $pdf->SetFont('helvetica', '', 7);
                $pdf->Cell(16.5, 6, utf8_decode($row1[2]), 0, 0, 'C', 0);//ASIENTO
                $pdf->Cell(20, 6, utf8_decode($row1[1]), 0, 0, 'C', 0);//FECHA
                $pdf->Cell(10, 6, utf8_decode($row1[3]), 0, 0, 'C', 0);//TD
                $pdf->Cell(17.5, 6, utf8_decode($row1[0]), 0, 0, 'C', 0);//NUMERO
              
                
    if ($row1[3] == 'VEN') {
        $pdf->Cell(50, 6, maxCaracter(utf8_decode($row1[5]), 30), 0, 0, 'L', 0); //BENEFICIA
    }
    if ($row1[3] == 'COM') {
        $pdf->Cell(50, 6, maxCaracter(utf8_decode($row1[4]), 30), 0, 0, 'L', 0); //BENEFICIA
    }
    if ($row1[3] == 'GAS') {
        $pdf->Cell(50, 6, maxCaracter(utf8_decode($row1[4]), 30), 0, 0, 'L', 0); //BENEFICIA
    }
    if ($row1[3] == 'CxP') {
        $pdf->Cell(50, 6, maxCaracter(utf8_decode($row1[4]), 30), 0, 0, 'L', 0); //BENEFICIA
    }
    if ($row1[3] == 'CxC') {
        $pdf->Cell(50, 6, maxCaracter(utf8_decode($row1[5]), 30), 0, 0, 'L', 0); //BENEFICIA
    }
   if ($row1[3] == 'OTRO') {
        $pdf->Cell(15, 6, maxCaracter(utf8_decode($row1[5]), 30), 0, 0, 'L', 0); //BENEFICIA
    }
      if ($row1[3] == 'ING') {
        $pdf->Cell(15, 6, maxCaracter(utf8_decode($row1[5]), 30), 0, 0, 'L', 0); //BENEFICIA
    }
      if ($row1[3] == 'EGR') {
        $pdf->Cell(15, 6, maxCaracter(utf8_decode($row1[5]), 30), 0, 0, 'L', 0); //BENEFICIA
    }
     if ($row1[3] == 'ANTP') {
        $pdf->Cell(15, 6, maxCaracter(utf8_decode($row1[5]), 30), 0, 0, 'L', 0); //BENEFICIA
    }
    //////////////////////////////////////////////////////////////////////////////////////
     if ($row1[3] == 'VEN') {
        $pdf->Cell(15, 6, maxCaracter(utf8_decode($row1[11]), 105), 0, 0, 'L', 0); //CONCE
    }
    if ($row1[3] == 'COM') {
        $pdf->Cell(15, 6, maxCaracter(utf8_decode($row1[10]), 105), 0, 0, 'L', 0); //CONCE
    }
    if ($row1[3] == 'GAS') {
        $pdf->Cell(15, 6, maxCaracter(utf8_decode($row1[9]), 105), 0, 0, 'L', 0); //CONCE
    }
    if ($row1[3] == 'CxP') {
        $pdf->Cell(15, 6, maxCaracter(utf8_decode($row1[11]), 105), 0, 0, 'L', 0); //CONCE
    }
    if ($row1[3] == 'CxC') {
        $pdf->Cell(15, 6, maxCaracter(utf8_decode($row1[11]), 105), 0, 0, 'L', 0); //CONCE
    }
      if ($row1[3] == 'OTRO') {
        $pdf->Cell(15, 6, maxCaracter(utf8_decode($row1[11]), 30), 0, 0, 'L', 0); //BENEFICIA
    }
   if ($row1[3] == 'ING') {
        $pdf->Cell(15, 6, maxCaracter(utf8_decode($row1[11]), 30), 0, 0, 'L', 0); //BENEFICIA
    }
       if ($row1[3] == 'EGR') {
        $pdf->Cell(15, 6, maxCaracter(utf8_decode($row1[11]), 30), 0, 0, 'L', 0); //BENEFICIA
    }
     if ($row1[3] == 'ANTP') {
        $pdf->Cell(15, 6, maxCaracter(utf8_decode($row1[11]), 30), 0, 0, 'L', 0); //BENEFICIA
    }
//////////////////////////
               
               
    if ($row1[3] == 'VEN') {
        $pdf->Cell(120, 6, maxCaracter(utf8_decode($pos4 . "---" . $row1[14]), 80), 0, 0, 'L', 0); //CONCE
    }
    if ($row1[3] == 'COM') {
        $pdf->Cell(120, 6, maxCaracter(utf8_decode($pos4 . "---" . $row1[13]), 80), 0, 0, 'L', 0); //CONCE
    }
    if ($row1[3] == 'GAS') {
        $pdf->Cell(120, 6, maxCaracter(utf8_decode($pos4 . "---" . $row1[12]), 80), 0, 0, 'L', 0); //CONCE
    }
    if ($row1[3] == 'CxP') {
        $pdf->Cell(120, 6, maxCaracter(utf8_decode($pos4), 80), 0, 0, 'L', 0); //CONCE
    }
    if ($row1[3] == 'CxC') {
        $pdf->Cell(120, 6, maxCaracter(utf8_decode($pos4), 80), 0, 0, 'L', 0); //CONCE
    }
  if ($row1[3] == 'OTRO') {
        $pdf->Cell(120, 6, maxCaracter(utf8_decode($pos4), 80), 0, 0, 'L', 0); //BENEFICIA
    }
 if ($row1[3] == 'ING') {
        $pdf->Cell(120, 6, maxCaracter(utf8_decode($pos4), 80), 0, 0, 'L', 0); //BENEFICIA
    }
 if ($row1[3] == 'EGR') {
        $pdf->Cell(120, 6, maxCaracter(utf8_decode($pos4), 80), 0, 0, 'L', 0); //BENEFICIA
    }
 if ($row1[3] == 'ANTP') {
        $pdf->Cell(120, 6, maxCaracter(utf8_decode($pos4), 80), 0, 0, 'L', 0); //BENEFICIA
    }

         
                $pdf->Cell(15, 6, number_format($row1[7], 2, ',', '.'), 0, 0, 'R', 0);//DEBE
                $pdf->Cell(15, 6, number_format($row1[8], 2, ',', '.'), 0, 0, 'R', 0);//HABER
                $pdf->Cell(15, 6, number_format($saldo, 2, ',', '.'), 0, 1, 'R', 0);//SALDO
            }
            $pdf->SetFont('helvetica', 'B', 7);
            $pdf->Cell(280, 0, utf8_decode(''), 1, 1, 'R', 1);
            $pdf->Cell(252, 6, utf8_decode('Subtotal:'), 0, 0, 'R', 0);
            $pdf->Cell(15, 6, number_format($sub_debe, 2, ',', '.'), 0, 0, 'R', 0);
            $pdf->Cell(15, 6, number_format($sub_haber, 2, ',', '.'), 0, 1, 'R', 0);
            $pdf->Ln(2);
        }
        $total_debe += $sub_debe;
        $total_haber += $sub_haber;
    }
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(280, 0, utf8_decode(''), 1, 1, 'R', 1);
    $pdf->Cell(252, 6, utf8_decode('Totales:'), 0, 0, 'R', 0);
    $pdf->Cell(15, 6, number_format($total_debe, 2, ',', '.'), 0, 0, 'R', 0);
    $pdf->Cell(15, 6, number_format($total_haber, 2, ',', '.'), 0, 1, 'R', 0);

    $pdf->Ln(20);
    $pdf->SetX(7);
    $pdf->Cell(40, 0, utf8_decode('x'), 1, 1, 'R', 1);
    $pdf->SetX(44);
    $pdf->Cell(5, 0, "", 0, 0, 'R', 0);
    $pdf->SetX(57);
    $pdf->Cell(40, 0, utf8_decode('x'), 1, 1, 'R', 1);
    $pdf->SetX(94);
    $pdf->Cell(5, 0, "", 0, 0, 'R', 0);
    $pdf->SetX(107);
    $pdf->Cell(40, 0, utf8_decode('x'), 1, 1, 'R', 1);
    $pdf->SetX(144);
    $pdf->Cell(5, 0, "", 0, 0, 'R', 0);
    $pdf->SetX(157);
    $pdf->Cell(40, 0, utf8_decode('x'), 1, 1, 'R', 1);
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
}
$pdf->Output();
