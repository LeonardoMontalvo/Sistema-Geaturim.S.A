<?php

require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
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
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(1);
        $this->SetY(1);
        $this->Cell(105, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(105, 5, "SOCIOS PAGADOS", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(210, 8, $_SESSION['nombre_empresa'], 0, 1, 'C', 0);
        $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 10, 7, 15, 15);
        $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 180, 7, 15, 15);
        // $this->SetFont('Amble-Regular', '', 10);
        // $this->Cell(190, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        // $this->Cell(80, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        // $this->Cell(80, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
        // $this->Cell(190, 5, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        // $this->Cell(190, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.5);
        $this->Line(0, 25, 210, 25);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 5, utf8_decode("SOCIOS PAGADOS"), 0, 1, 'C', 0);
        $this->SetFont('helvetica', 'B', 10);
        $this->Ln(7);
        $this->SetX(0);
        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(175, 215, 240);
        $this->Cell(8, 6, utf8_decode("N°"), 1, 0, 'C', 1);
        $this->Cell(100, 6, utf8_decode("NOMBRE Y APELLIDOS"), 1, 0, 'l', 1);
        $this->Cell(40, 6, utf8_decode("CEDULA"), 1, 0, 'l', 1);
        $this->Cell(40, 6, utf8_decode("MESES PAGADOS"), 1, 0, 'l', 1);
        $this->Cell(30, 6, utf8_decode("VALOR"), 1, 1, 'l', 1);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

}

$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetTitle('Lista de Precios');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();
$query_fecha = "";
$query = "";
// RANGO DE FECHAS O FECHA ACTUAL
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}
$conpunto = 1;
$consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($consultapunto)) {
    $conpunto = $row[0];
}

$conpuntoresult = 1;
$consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
while ($row = pg_fetch_row($consultapuntoresult)) {
    $conpuntoresult = $row[0];
}

if ($_GET['fin'] == "" && $_GET['id'] != "") {

    $consulta = pg_query(
            "   
     select clientes.id_cliente,nombres_cli,identificacion, count(*),sum(factura_venta.total_venta )
  from clientes
left join factura_venta on factura_venta.id_cliente=clientes.id_cliente
left join detalle_factura_venta on detalle_factura_venta.id_factura_venta=factura_venta.id_factura_venta
  left join productos on productos.cod_productos=detalle_factura_venta.cod_productos
 
  where  clientes.id_cliente='$_GET[id]' and factura_venta.estado='Activo' and id_empresa='$conpuntoresult' and detalle_factura_venta.cod_productos='1'
  GROUP BY clientes.id_cliente,nombres_cli,identificacion
 order by clientes.id_cliente

"
    );
}
if ($_GET['fin'] != "" && $_GET['id'] == "") {

    $consulta = pg_query(
            "   
     select clientes.id_cliente,nombres_cli,identificacion, count(*),sum(factura_venta.total_venta )
  from clientes
left join factura_venta on factura_venta.id_cliente=clientes.id_cliente
left join detalle_factura_venta on detalle_factura_venta.id_factura_venta=factura_venta.id_factura_venta
  left join productos on productos.cod_productos=detalle_factura_venta.cod_productos
 
  where fecha_cancelacion $query_fecha '$_GET[fin]'  and factura_venta.estado='Activo' and id_empresa='$conpuntoresult' and detalle_factura_venta.cod_productos='1'
  GROUP BY clientes.id_cliente,nombres_cli,identificacion
  order by clientes.id_cliente

"
    );
}
if ($_GET['fin'] != "" && $_GET['id'] != "") {

    $consulta = pg_query(
            "   
     select clientes.id_cliente,nombres_cli,identificacion, count(*),sum(factura_venta.total_venta )
  from clientes
left join factura_venta on factura_venta.id_cliente=clientes.id_cliente
left join detalle_factura_venta on detalle_factura_venta.id_factura_venta=factura_venta.id_factura_venta
  left join productos on productos.cod_productos=detalle_factura_venta.cod_productos
 
  where fecha_cancelacion $query_fecha '$_GET[fin]' and factura_venta.id_cliente='$_GET[id]' and factura_venta.estado='Activo' and id_empresa='$conpuntoresult' and detalle_factura_venta.cod_productos='1'
  GROUP BY clientes.id_cliente,nombres_cli,identificacion
  order by clientes.id_cliente

"
    );
}

$contador = 0;

if (pg_num_rows($consulta)) {
    $total = 0;
    while ($row = pg_fetch_row($consulta)) {
        $pdf->SetX(1);
        $pdf->SetFont('helvetica', '', 9);

$contador=$contador+1;



        $pdf->Cell(8, 5, maxCaracter(utf8_decode($contador), 30), 0, 0, 'L', 0); //nu
        $pdf->Cell(100, 5, maxCaracter(utf8_decode($row[1]), 40), 0, 0, 'L', 0); //nombres
        $pdf->Cell(40, 5, utf8_decode($row[2]), 0, 0, 'l', 0); //cedula
        $pdf->Cell(40, 5, utf8_decode($row[3]), 0, 0, 'l', 0); //meses     
        $pdf->Cell(30, 5, (number_format($row[4], 2, ',', '.')), 0, 1, 'l', 0); //valor



        $total = $total + ($row[4]);
    }
    $pdf->Ln(4);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(175, 6, utf8_decode('Total'), 0, 0, 'R', 0);
    $pdf->Cell(25, 6, (number_format($total, 2, ',', '.')), 0, 1, 'R', 0);
}
$pdf->Output();
