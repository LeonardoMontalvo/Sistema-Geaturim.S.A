<?php

require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();
$total_debe_haber = 0;
$total_calculo = 0;

class PDF extends FPDF {

    var $widths;
    var $aligns;

    function SetWidths($w) {
        $this->widths = $w;
    }

    function SetAlings($a) {
        $this->aligns = $a;
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
        $this->SetY(0);
        $this->Cell(105, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(105, 5, "CONTABILIDAD", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(210, 8, $_SESSION['nombre_empresa'], 0, 1, 'C', 0);
        $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 10, 7, 15, 15);
        $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 250, 7, 15, 15);
        // $this->Cell(180, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        // $this->Cell(80, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        // $this->Cell(80, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(0, 25, 400, 25);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 5, utf8_decode('Conciliación Bancaria'), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(200, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $sub_debe = 0;
        $sub_haber = 0;
        $total_debe_haber = 0;

        $total_debe = 0;
        $total_haber = 0;
        $query_id = "";
        $rango_id = false;

        $sub_debe = 0;
        $sub_haber = 0;
        $sub_haber_conci = 0;
        $sub_debe_conci = 0;
        $total_total = 0;
        $query_fecha = "";

        $comprobante = $_GET['comprobante'];
        $descrip = $_GET['id_plan'];
        $descrip1 = $_GET['id_plan1'];

        
//        echo '/'."  SELECT '$_GET[inicio]', 
//    ('SALDO INICIAL')concepto, 
//    round(coalesce(sum(dt.debito),0),2)debito, 
//    round(coalesce(sum(dt.credito),0),2)credito, 
//    round(coalesce(sum(dt.debito)-sum(dt.credito),0),2)saldo
//    from transacciones t 
//    inner join detalle_transaccion dt
//    on t.id_transacciones=dt.id_transacciones
//    where dt.id_plan_cuentas='$_GET[id_plan]'
//    and t.estado='Activo'
//    and t.fecha_registro 
//    between (select fecha_registro from transacciones t
//    where fecha_registro is not null and estado='Activo'
//    group by id_transacciones,t.fecha_registro
//    order by id_transacciones asc
//    limit 1) and '$_GET[fin]'
//    and t.id_empresa=1" ;
        
        $query_saldo_inicial = pg_query("  SELECT '$_GET[inicio]', 
    ('SALDO INICIAL')concepto, 
    round(coalesce(sum(dt.debito),0),2)debito, 
    round(coalesce(sum(dt.credito),0),2)credito, 
    round(coalesce(sum(dt.debito)-sum(dt.credito),0),2)saldo
    from transacciones t 
    inner join detalle_transaccion dt
    on t.id_transacciones=dt.id_transacciones
    where dt.id_plan_cuentas='$_GET[id_plan]'
    and t.estado='Activo'
    and t.fecha_registro 
    between (select fecha_registro from transacciones t
    where fecha_registro is not null and estado='Activo'
    group by id_transacciones,t.fecha_registro
    order by id_transacciones asc
    limit 1) and '$_GET[fin]'
    and t.id_empresa=1" );

        $sal_debe = 0;
        $sal_haber = 0;
        $total_debe_saldo = 0;
        $total_haber_saldo = 0;
        $total_debe_haber_sal = 0;


        if (pg_num_rows($query_saldo_inicial)) {
            
            while ($row1 = pg_fetch_row($query_saldo_inicial)) {
                $sal_debe += $row1[2];

                $sal_haber += $row1[3];
                
            }
           
            $total_debe_saldo = $sal_debe;
            $total_haber_saldo = $sal_haber;
            $total_debe_haber_sal = $total_debe - $total_haber;
           
        }
        
//        echo '/'. "SELECT T.comprobante, T.fecha_registro, TT.abreviatura, T.num_transaccion, T.concepto, D.debito, D.credito , fpm.numero_documento
//            FROM transacciones T INNER JOIN tipo_transaccion TT USING(id_tipo_transaccion) 
//            INNER JOIN detalle_transaccion D USING(id_transacciones) 
//            INNER JOIN plan_cuentas P USING(id_plan_cuentas)
//            left JOIN gastos g on g.id_gastos=T.comprobante::integer
//             left JOIN formas_pago_mixto_g fpm on g.id_gastos=fpm.id_gastos
//            WHERE D.id_plan_cuentas = '$_GET[id_plan]' AND T.fecha_registro between '$_GET[inicio]' and '$_GET[fin]'
//            AND T.estado='Activo' and  T.id_empresa='$_SESSION[PV]'
//            ORDER BY T.fecha_registro ASC ";
        $query_detalle_libro = pg_query(
                "SELECT T.comprobante, T.fecha_registro, TT.abreviatura, T.num_transaccion, T.concepto, D.debito, D.credito , fpm.numero_documento
            FROM transacciones T INNER JOIN tipo_transaccion TT USING(id_tipo_transaccion) 
            INNER JOIN detalle_transaccion D USING(id_transacciones) 
            INNER JOIN plan_cuentas P USING(id_plan_cuentas)
            left JOIN gastos g on g.id_gastos=T.comprobante::integer
             left JOIN formas_pago_mixto_g fpm on g.id_gastos=fpm.id_gastos
            WHERE D.id_plan_cuentas = '$_GET[id_plan]' AND T.fecha_registro between '$_GET[inicio]' and '$_GET[fin]'
            AND T.estado='Activo' and  T.id_empresa='$_SESSION[PV]'
            ORDER BY T.fecha_registro ASC ");

        if (pg_num_rows($query_detalle_libro)) {
            while ($row1 = pg_fetch_row($query_detalle_libro)) {
                $sub_debe += $row1[5];

                $sub_haber += $row1[6];
            }
            $total_debe += $sub_debe;
            $total_haber += $sub_haber;
            $total_debe_haber = $total_debe - $total_haber;
        }
        $total_debe_haber_sal = $total_debe_saldo - $total_haber_saldo;


        $saldo_anterior = pg_fetch_row(pg_query(
                        "   SELECT SUM(saldo) FROM transacciones T INNER JOIN tipo_transaccion TT USING(id_tipo_transaccion) 
                INNER JOIN detalle_transaccion D USING(id_transacciones) 
                INNER JOIN plan_cuentas P USING(id_plan_cuentas)
                WHERE D.id_plan_cuentas = '$_GET[id_plan]' and  T.id_empresa='$_SESSION[PV]' AND T.fecha_registro::date between '$_GET[inicio]' and '$_GET[fin]' AND T.estado='Activo'"
        ));

        $this->SetFillColor(220, 240, 210);
        $this->SetFont('helvetica', 'B', 10);
        $this->Ln(15);
        $saldo = $saldo_anterior[0];
        $this->Cell(250, 6, utf8_decode($descrip1), 0, 0, 'R', 1);
        $this->Ln(5);
        $this->Cell(250, 6, utf8_decode('SALDO SEGUN LIBROS=> ' . $total_debe_haber_sal), 0, 0, 'R', 1);
        $this->Ln(5);
        $this->Cell(250, 6, utf8_decode('MOVIMIENTOS PENDIENTES'), 0, 0, 'R', 1);
//            $pdf->Cell(15, 6, number_format($saldo, 2, ',', '.'), 0, 1, 'L', 1);

        $this->Ln(10);
        $this->Ln(3);
        $this->SetFont('helvetica', 'B', 8);
        $this->SetFillColor(175, 215, 240);
        $this->Cell(10, 6, utf8_decode('ID T.'), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode('FECHA R.'), 1, 0, 'C', 1);
        $this->Cell(12, 6, utf8_decode('COMP.'), 1, 0, 'C', 1);
        $this->Cell(10, 6, utf8_decode('DOC.'), 1, 0, 'C', 1);
        $this->Cell(50, 6, utf8_decode('BENEFICIARIO'), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode('N.Cheque'), 1, 0, 'C', 1);
        $this->Cell(150, 6, utf8_decode('CONCEPTO'), 1, 0, 'C', 1);
        $this->Cell(13, 6, utf8_decode('DEBE'), 1, 0, 'C', 1);
        $this->Cell(13, 6, utf8_decode('HABER'), 1, 0, 'C', 1);
//        $this->Cell(15, 6, utf8_decode('HABER'), 1, 0, 'C', 1);
//        $this->Cell(15, 6, utf8_decode('SALDO'), 1, 1, 'C', 1);
        $this->Ln(10);
        $this->SetFillColor(255, 255, 225);
        $this->SetLineWidth(0.2);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

}

$comprobante = $_GET['comprobante'];
$descrip = $_GET['id_plan'];
$pdf = new PDF('l', 'mm', 'a4');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->SetTitle('Conciliacion Bancaria');
$pdf->AliasNbPages();

$total_debe = 0;
$total_haber = 0;
$query_id = "";
$rango_id = false;

$sub_debe = 0;
$sub_haber = 0;
$sub_haber_conci = 0;
$sub_debe_conci = 0;
$total_total = 0;
$total_calculo = 0;
$query_fecha = "";
// RANGO DE FECHAS O FECHA ACTUAL
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}
conectarse();

$comprobante = $_GET['comprobante'];
$descrip = $_GET['id_plan'];

$pdf->SetFillColor(220, 240, 210);
$pdf->SetFont('helvetica', 'B', 7.5);
// saldo anterior
$query_detalle_libro = pg_query(
        "SELECT T.comprobante, T.fecha_registro, TT.abreviatura, T.num_transaccion, T.concepto, D.debito, D.credito , fpm.numero_documento
            FROM transacciones T INNER JOIN tipo_transaccion TT USING(id_tipo_transaccion) 
            INNER JOIN detalle_transaccion D USING(id_transacciones) 
            INNER JOIN plan_cuentas P USING(id_plan_cuentas)
            left JOIN gastos g on g.id_gastos=T.comprobante::integer
             left JOIN formas_pago_mixto_g fpm on g.id_gastos=fpm.id_gastos
            WHERE D.id_plan_cuentas = '$_GET[id_plan]' and  T.id_empresa='$_SESSION[PV]' AND T.fecha_registro between '$_GET[inicio]' and '$_GET[fin]'
            AND T.estado='Activo'
            ORDER BY T.fecha_registro ASC "
);

//    
//    	 echo '<br>GUARDAR FACTURA VENTA: <br>' .   "SELECT t.id_transacciones,fecha_registro,comprobante,identificador_cli_pro, p.empresa_pro,c.nombres_cli,t.concepto,pc.descripcion ,c.nombres_cli,credito
//            FROM transacciones t
//            INNER JOIN detalle_transaccion dt USING(id_transacciones) 
//            INNER JOIN plan_cuentas pc USING(id_plan_cuentas)
//               left JOIN proveedores p on p.id_proveedor=t.id_cliente
//                left JOIN clientes c on c.id_cliente=t.id_cliente
//                    INNER JOIN detalle_conciliacion  dc on t.id_transacciones=dc.id_transaccion::int                             
//                WHERE (identificador_cli_pro='CxC'  or identificador_cli_pro='CxP' or identificador_cli_pro='EGR' or identificador_cli_pro='GAS')
//              and pc.descripcion like '%$_GET[id_plan]%'  and dc.id_conciliacion='$comprobante'
//            AND T.estado='Activo'
//            ORDER BY t.fecha_registro ASC;";//////////////////////////

$query_detalle = pg_query(
        "                SELECT t.id_transacciones,fecha_registro,t.comprobante,identificador_cli_pro, p.empresa_pro,c.nombres_cli,t.concepto,pc.descripcion ,c.nombres_cli,debito,credito , fpm.numero_documento,dg.concepto
            FROM transacciones t
            INNER JOIN detalle_transaccion dt USING(id_transacciones) 
            INNER JOIN plan_cuentas pc USING(id_plan_cuentas)
              left JOIN gastos g on g.id_gastos=T.comprobante::integer
                 left JOIN detalle_gastos dg on g.id_gastos=dg.id_gastos
             left JOIN formas_pago_mixto_g fpm on g.id_gastos=fpm.id_gastos
               left JOIN proveedores p on p.id_proveedor=t.id_cliente
                left JOIN clientes c on c.id_cliente=t.id_cliente
                    INNER JOIN detalle_conciliacion  dc on t.id_transacciones=dc.id_transaccion::int                             
                WHERE 
              dt.id_plan_cuentas = '$_GET[id_plan]'   and dc.id_conciliacion='$comprobante'
            AND T.estado='Activo' and  T.id_empresa='$_SESSION[PV]'
            ORDER BY t.fecha_registro ASC
;
"
);

// RANGO DE FECHAS O FECHA ACTUAL
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]'::date - 1 AND";
} else {
    $query_fecha = "=";
}
$pdf->SetFillColor(220, 240, 210);
$pdf->SetFont('helvetica', 'B', 7.5);
// saldo anterior
//    $saldo_anterior = pg_fetch_row(pg_query(
//                    "   SELECT SUM(saldo) FROM transacciones T INNER JOIN tipo_transaccion TT USING(id_tipo_transaccion) 
//                INNER JOIN detalle_transaccion D USING(id_transacciones) 
//                INNER JOIN plan_cuentas P USING(id_plan_cuentas)
//                WHERE p.descripcion like '%$_GET[id_plan]%' AND T.fecha_registro::date " . $query_fecha . " '$_GET[fin]'::date - 1 AND T.estado='Activo'"
//    ));
//
//    $saldo = $saldo_anterior[0];
//            $pdf->Cell(165, 6, utf8_decode('SALDO ANTERIOR=> '), 0, 0, 'L', 1);
//            $pdf->Cell(30, 6, utf8_decode('SALDO ANTERIOR=> '), 0, 0, 'R', 1);
//            $pdf->Cell(15, 6, number_format($saldo, 2, ',', '.'), 0, 1, 'L', 1);
$saldo = 0;
while ($row1 = pg_fetch_row($query_detalle)) {
    $sub_debe += $row1[5];
    $saldo += $row1[5];

    $saldo -= $row1[6];
    
    $pos1="";
      $pos2="";
        $pos3="";

    $pizza = $row1[6];

 $porciones = explode(":", $pizza);
 if(count($porciones)==2){
      $pos1 = $porciones[1]; // porción1
       
 }
  if(count($porciones)==3){
       $pos1 = $porciones[1]; // porción1
        $pos2 = $porciones[2];
         
 }
  if(count($porciones)==4){
      $pos1 = $porciones[1]; // porción1
      $pos2 = $porciones[2];
      $pos3 = $porciones[3];
     
 }
 $pos4 =  $pos1."". $pos2 ."". $pos3 ;
    
     

    
    
//    print_r($pos4);
    $pdf->SetX(1);
    $pdf->SetFont('helvetica', '', 7);
    $pdf->Cell(10, 6, utf8_decode($row1[0]), 0, 0, 'L', 0);//ID
    $pdf->Cell(15, 6, utf8_decode($row1[1]), 0, 0, 'L', 0);//FECHA
    $pdf->Cell(12, 6, utf8_decode($row1[2]), 0, 0, 'L', 0);//COMP
    $pdf->Cell(10, 6, maxCaracter(utf8_decode($row1[3]), 20), 0, 0, 'L', 0);//DOC
    $pdf->Cell(50, 6, maxCaracter(utf8_decode($row1[8]), 30), 0, 0, 'L', 0);//BENEFICIA
    $pdf->Cell(15, 6, maxCaracter(utf8_decode($row1[11]), 10), 0, 0, 'R', 0);//NUM CHE
    $pdf->Cell(150, 6, maxCaracter(utf8_decode($pos4."---".$row1[12]), 105), 0, 0, 'L', 0);//CONCE
   
    $pdf->Cell(13, 6,  number_format($row1[9], 2, '.', ''), 0, 0, 'R', 0);//DEBE
    $pdf->Cell(13, 6,  number_format($row1[10], 2, '.', ''), 0, 0, 'R', 0);//HABER
    $sub_haber_conci += $row1[10];
    $sub_debe_conci += $row1[9];
//                $pdf->Cell(15, 6, number_format($row1[6], 2, ',', '.'), 0, 0, 'R', 0);
//                $pdf->Cell(15, 6, number_format($saldo, 2, ',', '.'), 0, 1, 'R', 0);
    $pdf->Ln(5);
}

$pdf->SetFont('helvetica', 'B', 7);
//$pdf->Cell(210, 0, utf8_decode(''), 1, 1, 'R', 1);
//$pdf->Cell(165, 6, utf8_decode('Subtotal:'), 0, 0, 'R', 0);
//$pdf->Cell(15, 6, number_format($sub_debe, 2, ',', '.'), 0, 0, 'R', 0);
//$pdf->Cell(15, 6, number_format($sub_haber, 2, ',', '.'), 0, 1, 'R', 0);
$pdf->Ln(5);

$total_debe += $sub_debe;

$total_total = $sub_debe_conci + $sub_haber_conci;
if (pg_num_rows($query_detalle_libro)) {
    while ($row1 = pg_fetch_row($query_detalle_libro)) {
        $sub_debe += $row1[5];

        $sub_haber += $row1[6];
    }
    $total_debe += $sub_debe;
    $total_haber += $sub_haber;
    $total_debe_haber = $total_debe - $total_haber;
    $total_calculo = $total_debe_haber - $sub_debe_conci + $sub_haber_conci;
//    print_r($total_total);
}


$pdf->SetFont('helvetica', 'B', 8);
$pdf->Cell(260, 0, utf8_decode(''), 1, 1, 'R', 1);
$pdf->Cell(260, 6, utf8_decode('Totales:'), 0, 0, 'R', 0);
$pdf->Cell(15, 6, number_format($sub_debe_conci, 2, ',', '.'), 0, 0, 'R', 0);
$pdf->Cell(15, 6, number_format($sub_haber_conci, 2, ',', '.'), 0, 1, 'R', 0);
//$pdf->Ln(1);
//$pdf->Cell(175, 6, utf8_decode('Total:'), 0, 0, 'R', 0);
//
//$pdf->Cell(15, 6, number_format($total_total, 2, ',', '.'), 0, 1, 'R', 0);
$pdf->Ln(5);
$pdf->Cell(250, 6, utf8_decode('SALDO CONCILIADO SEGUN LIBROS:=> ' . $total_calculo), 0, 0, 'R', 1);
$pdf->Ln(5);
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

$pdf->Output();
