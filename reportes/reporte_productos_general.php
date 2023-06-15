<?php

require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();

$constock = empty($_GET["stock"]);

$iva = 12;
$sql = "select valor from parametros where descripcion='IVA'";
$res = pg_query($sql);
$rows = pg_fetch_all($res);
if (!empty($rows)) {
    $iva = $rows[0]["valor"];
}


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
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(0);
        $this->SetY(0);
        $this->Cell(105, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(105, 5, "PRODUCTOS", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(210, 8, utf8_decode($_SESSION['nombre_empresa']), 0, 1, 'C', 0);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 10, 7, 15, 15);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 180, 7, 15, 15);
        // $this->SetFont('Amble-Regular', '', 10);
        // $this->Cell(190, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        // $this->Cell(80, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        // $this->Cell(80, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
        // $this->Cell(190, 5, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        // $this->Cell(190, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(0, 25, 210, 25);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 5, utf8_decode("PRODUCTOS INCLUYE IVA"), 0, 1, 'C', 0);
        $this->Ln(7);
        $this->SetX(0);
        $this->SetFont('helvetica', 'B', 8);
        $this->SetFillColor(175, 215, 240);
        //$this->Cell(32, 6, utf8_decode("CODIGO"), 1, 0, 'C', 1);
        $this->Cell(33, 6, utf8_decode("BARRAS"), 1, 0, 'C', 1);
        $this->Cell(70, 6, utf8_decode("PRODUCTO"), 1, 0, 'C', 1);
        $this->SetFont('helvetica', 'B', 7);
        $this->Cell(15, 6, utf8_decode("P. MAYOR."), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode("P. MINOR."), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode("P. NEGO."), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode("P. COSTO"), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode("STOCK"), 1, 0, 'C', 1);
        $this->Cell(32, 6, utf8_decode("COSTO TOTAL"), 1, 1, 'C', 1);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetTitle('Productos');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();

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

//$consulta = pg_query("select p.codigo,p.cod_barras,p.articulo,p.iva_minorista,p.iva_mayorista,dpb.stock from   productos p left join detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos WHERE  dpb.id_bodega=$conpuntoresult   and p.estado = 'Activo' order by p.articulo asc ");

$query1 = "";
$query2 = "";
$order = "";
if ($constock) {
    $query1 = "and dpb.id_bodega=$conpuntoresult";
    $order = "order by stock desc, p.articulo asc";
} else {
    $query2 = "and dpb.id_bodega=$conpuntoresult and dpb.stock>0";
    $order = "order by p.articulo asc";
}

$sql = "select codigo,
articulo,precio_compra,iva_minorista,
iva_mayorista,iva_negocio,coalesce(dpb.stock,0)stock,
cod_barras, p.iva,p.cod_productos
from productos p
left join detalle_producto_bodega dpb
on p.cod_productos=dpb.cod_productos
$query1
where estado = 'Activo'
$query2
$order";
$consulta = pg_query($sql);

$totalstock = 0;
$totalcosto = 0;

if (pg_num_rows($consulta)) {
    while ($row = pg_fetch_assoc($consulta)) {
        $pdf->SetX(1);
        $pdf->SetFont('helvetica', '', 8);
        //$pdf->Cell(32, 5, maxCaracter(utf8_decode($row["codigo"]), 20), 0, 0, 'L', 0);
        $pdf->Cell(33, 5, maxCaracter(utf8_decode($row["cod_barras"]), 20), 0, 0, 'L', 0);
        $pdf->Cell(70, 5, maxCaracter(utf8_decode($row["articulo"]), 40), 0, 0, 'L', 0);

        $precioc = obtenerCostoPromedioProducto($row["cod_productos"]);
        if (empty($precioc)) {
            $precioc = $row["precio_compra"];
        }

        $ivat = $row["iva"];

        $pmin = $row["iva_mayorista"];
        $pmay = $row["iva_minorista"];
        $pneg = $row["iva_negocio"];
        if ($ivat == "Si") {

            //$precioc = $precioc * (1+($iva/100));
            $pmin = $pmin * (1 + ($iva / 100));
            $pmay = $pmay * (1 + ($iva / 100));
            $pneg = $pneg * (1 + ($iva / 100));
        }
        //var_dump($iva);


        $pdf->Cell(15, 5, maxCaracter(utf8_decode(number_format($pmin, 2, ",", ".")), 20), 0, 0, 'R', 0);
        $pdf->Cell(15, 5, maxCaracter(utf8_decode(number_format($pmay, 2, ",", ".")), 20), 0, 0, 'R', 0);
        $pdf->Cell(15, 5, maxCaracter(utf8_decode(number_format($pneg, 2, ",", ".")), 20), 0, 0, 'R', 0);

        $pdf->Cell(15, 5, maxCaracter(utf8_decode(number_format($precioc, 2)), 20), 0, 0, 'R', 0);
        $pdf->Cell(14, 5, maxCaracter(utf8_decode($row["stock"]), 20), 0, 0, 'R', 0);
        $costototal = $row["stock"] * $precioc;
        $pdf->Cell(32, 5, maxCaracter(utf8_decode(number_format($costototal, 2)), 20), 0, 0, 'R', 0);
        $pdf->Ln(5);

        $totalstock += $row["stock"];
        $totalcosto += $costototal;
    }
}
$pdf->SetFont('helvetica', 'B', 8);
$pdf->Cell(177, 5, "TOTALES:", 0, 0, 'R', 0);
$pdf->Cell(32, 5, number_format($totalcosto, 4), 0, 0, 'R', 0);

$pdf->Output();

function obtenerCostoPromedioProducto($codprod)
{
    $sql = "select costo_prom_unitario from kardex_valorizado
    where cod_productos=$codprod
    order by id_kardex desc limit 1;";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return 0;
    }
    return $rows[0]["costo_prom_unitario"];
}
