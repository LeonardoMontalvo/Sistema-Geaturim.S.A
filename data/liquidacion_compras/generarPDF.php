<?php
include '../../fpdf/rotation.php';
include("../../fpdf/barcode.inc.php");
require_once('../../procesos/base.php');
require_once __DIR__ . "./../../reportes/formatos_ride/layout_ride.php";


if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

error_reporting(0);

class PDF extends PDF_Rotate
{

	var $widths;
	var $aligns;

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

	function Header()
	{
		$this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
		$this->SetFont('Amble-Regular', '', 10);
		$fecha = date('Y-m-d', time());
		$this->SetY(1);
		$this->Cell(20, 5, 'Generado: ' . $fecha, 0, 0, 'C', 0);
		//	        $this->Cell(178, 5, 'SUPERMERCADO SUPER FIESTA', 0,0, 'R', 0);                                                             
		$this->Ln(7);
		$this->SetX(13);
		// $this->RotatedImage('../../fpdf/logo.fw.png', 50, 150, 100, 80, 45);                            
		$this->SetX(0);
	}

	function Footer()
	{
		$this->SetY(-10);
		$this->SetFont('Arial', 'I', 8);
		$this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
	}

	function RotatedImage($file, $x, $y, $w, $h, $angle)
	{
		$this->Rotate($angle, $x, $y);
		$this->Image($file, $x, $y, $w, $h);
		$this->Rotate(0);
	}
}

if (isset($_GET['id'])) {

	$id = $_GET['id'];

	generarPDFcorreo($id);
}

function generarPDFcorreo($id)
{
	global $pdf;
	$infofac = getInfoLiquidacion($id);
	$detallesfac = getDetallesLiquidacion($id);

	//datos empresa
	$razonsocial = $infofac["nombre_empresa"];
	$dirmatriz = $infofac["direccion_empresa"];
	$rucempresa = $infofac["ruc_empresa"];
	$obligadoconta = $infofac["obligacion"];
	$contribuyenteespe = $infofac["contribuyente_espe"];
	//datos factura
	$numliquidacion = $infofac["num_factura"];
	$numautorizacion = $infofac["num_autorizacion"];
	if ($numautorizacion == "") {
		$numautorizacion = $infofac['clave'];
	} else {
		$numautorizacion = $infofac['num_autorizacion'];
	}
	$fechaaut = $infofac["fecha_autorizacion"];
	$claveacceso = $infofac["clave"];
	$fechaemision = $infofac["fecha_actual"];
	$formapago = $infofac["forma_pago"];
	$tarifa0liq = $infofac["tarifa0"];
	$tarifa12liq = $infofac["tarifa12"];
	$ivaliq = $infofac["iva_venta"];
	$totalliq = $infofac["total_venta"];
	$descuentoventa = $infofac["descuento_venta"];
	$ambiente = 2;
	$emision = 1;
	$dirsucursal=$infofac["ubicacion"];
	//datos proveedor
	$razonsocialpro = $infofac["empresa_pro"];
	$identificacionpro = $infofac["identificacion_pro"];
	$direccionpro = $infofac["direccion_pro"];
	$telefonopro = $infofac["telefono_pro"];
	$celularpro = $infofac["celular_pro"];
	$correopro = $infofac["correo"];

	$pdf = new PDF('P', 'mm', 'A4');
	$pdf->AddPage();
	$pdf->SetMargins(2, 0);
	$pdf->AliasNbPages();
	$pdf->SetAutoPageBreak(true, 10);
	$pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
	$pdf->SetFont('Amble-Regular', '', 9);
	$pdf->SetFont('Arial', '', 9);

	$totalw = $pdf->GetCurrentWidth();
	$halfw = $totalw / 2;
	$cellheight = 5;

	cabeceraRide(
		$pdf,
		$razonsocial,
		$dirmatriz,
		$obligadoconta,
		$contribuyenteespe,
		"LIQUIDACIÓN DE COMPRA DE BIENES Y PRESTACIÓN DE SERVICIOS",
		$rucempresa,
		$numliquidacion,
		$numautorizacion,
		$ambiente,
		$emision,
		$fechaaut,
		$claveacceso,
		'../../images/' . $_SESSION["parametros_empresa"]["logo_empresa"],
		$dirsucursal,
		$cellheight
	);

	//información proveedor
	$pdf->Cell($halfw - 70, $cellheight, utf8_decode("Nombre y Apellidos:"), 0, 0);
	$pdf->Cell($halfw + 70, $cellheight, utf8_decode($razonsocialpro), 0, 1);
	$pdf->Cell($halfw - 70, $cellheight, utf8_decode("RUC/CI:"), 0, 0);
	$pdf->Cell($halfw + 70, $cellheight, utf8_decode($identificacionpro), 0, 1);
	$pdf->Cell($halfw - 70, $cellheight, utf8_decode("Fecha Emisión:"), 0, 0);
	$pdf->Cell($halfw + 70, $cellheight, utf8_decode($fechaemision), 0, 1);
	$pdf->Cell($halfw - 70, $cellheight, utf8_decode("Dirección:"), 0, 0);
	$pdf->Cell($halfw + 70, $cellheight, utf8_decode($direccionpro), 0, 1);
	$pdf->Ln(2);

	//detalles factura
	$cellwidth = $totalw / 6;
	$pdf->SetWidths([
		$cellwidth - 6,
		$cellwidth - 10,
		$cellwidth + 46,
		$cellwidth - 10,
		$cellwidth - 10,
		$cellwidth - 10
	]);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetAligns(array_fill(0, 7, "C"));
	$pdf->Row([
		"Cod. Principal",
		utf8_decode("Cantidad"),
		utf8_decode("Descripción"),
		"Precio U.",
		"Descu. %",
		"Total"
	], 1);
	$pdf->SetFont('Arial', '', 9);
	$pdf->SetAligns(["L", "C", "L", "R", "R", "R"]);
	foreach ($detallesfac as $row) {
		$descripcion = utf8_decode($row["articulo"]);

		$cantidad = $row["cantidad"];
		$tarifa12 = 0;
		$tarifa12 = $row["precio_venta"];

		$tarifa12 = $tarifa12 * $cantidad;
		$Descucaltres = 0;
		$desc = 0;
		$desc = $row["descuento_producto"];
		$valcien = 100;
		$Descucaltres = ($tarifa12 / $valcien) * $desc;
		$tarifa12sin = $tarifa12 - $Descucaltres;
		$total = number_format($tarifa12sin, 2, '.', '');
		$pdf->Row([
			$row["codigo"],
			$row["cantidad"],
			$descripcion,
			number_format($row["precio_venta"], 2, ".", ""),
			$row["descuento_producto"],
			$total
		]);
	}

	//lìnea divisora
	$pdf->Line(2, $pdf->GetY(), $totalw + 2, $pdf->GetY());
	$pdf->Ln(2);

	//totales
	$x = $pdf->GetX();
	$y = $pdf->GetY();

	$offsetleft = $halfw + 50;
	$cellwidth = ($totalw - $offsetleft) / 2;
	$pdf->Cell($offsetleft, $cellheight, "", 0, 0);
	$pdf->Cell($cellwidth, $cellheight, utf8_decode("Subtotal 15% "), 0, 0);
	$pdf->Cell($cellwidth, $cellheight, number_format(round($tarifa12liq, 2), 2, ".", ""), 0, 1, "R");

	$pdf->Cell($offsetleft, $cellheight, "", 0, 0);
	$pdf->Cell($cellwidth, $cellheight, utf8_decode("Subtotal 0 % "), 0, 0);
	$pdf->Cell($cellwidth, $cellheight, number_format(round($tarifa0liq, 2), 2, ".", ""), 0, 1, "R");

	$pdf->Cell($offsetleft, $cellheight, "", 0, 0);
	$pdf->Cell($cellwidth, $cellheight, utf8_decode("Descuento "), 0, 0);
	$pdf->Cell($cellwidth, $cellheight, number_format(round($descuentoventa, 2), 2, ".", ""), 0, 1, "R");

	$pdf->Cell($offsetleft, $cellheight, "", 0, 0);
	$pdf->Cell($cellwidth, $cellheight, utf8_decode("IVA 15%"), 0, 0);
	$pdf->Cell($cellwidth, $cellheight, number_format(round($ivaliq, 2), 2, ".", ""), 0, 1, "R");

	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell($offsetleft, $cellheight, "", 0, 0);
	$pdf->Cell($cellwidth, $cellheight, utf8_decode("Total"), 0, 0);
	$pdf->Cell($cellwidth, $cellheight, number_format(round($totalliq, 2), 2, ".", ""), 0, 1, "R");
	$pdf->SetFont('Arial', '', 9);

	//información adicional
	$pdf->SetXY($x, $y);
	$cellwidth = $offsetleft - 10;
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell($cellwidth, $cellheight, utf8_decode("Informaciòn Adicional:"), 0, 1);
	$pdf->SetFont('Arial', '', 9);
	$pdf->Ln(2);
	$pdf->Cell($cellwidth, $cellheight, utf8_decode("Email: $correopro"), 0, 1);
	$pdf->Cell($cellwidth, $cellheight, utf8_decode("Teléfono: " . (empty($celularpro) ? $telefonopro : $celularpro)), 0, 1);
	$pdf->Ln(2);

	//lìnea divisora
	$pdf->Line(2, $pdf->GetY(), $cellwidth, $pdf->GetY());
	$pdf->Ln(2);

	//forma de pago
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell($cellwidth, $cellheight, utf8_decode("Formas de Pago:"), 0, 1);
	$pdf->SetFont('Arial', '', 9);
	$pdf->Ln(2);
	$pdf->Cell($cellwidth, $cellheight, utf8_decode("Forma de Pago: $formapago"), 0, 1);
	$pdf->Cell($cellwidth, $cellheight, utf8_decode("Monto de Pago: " . number_format(round($totalliq, 2), 2, ".", "")), 0, 1);

	if (isset($_GET['id'])) {
		$pdf->Output();
	} else {
		$pdf_file_contents = $pdf->Output("", "S");
		return $pdf_file_contents;
	}
}

function getInfoLiquidacion($id)
{
	$sql = "
	select
	e.nombre_empresa,
	e.ruc_empresa,
	e.direccion_empresa,
	e.telefono_empresa,
	e.celular_empresa,
	e.email_empresa,
	e.nombre_comercial,
	e.obligacion,
	e.contribuyente_espe,
	p.empresa_pro,
	p.identificacion_pro,
	p.direccion_pro,
	p.telefono telefono_pro,
	p.celular celular_pro,
	p.correo,
	lc.tarifa12,
	lc.tarifa0,
	lc.iva_venta,
	lc.descuento_venta,
	lc.total_venta,
	lc.fecha_autorizacion,
	lc.clave,
	lc.num_factura,
	lc.fecha_actual,
	lc.hora_actual,
	fp.descripcion forma_pago,
	pv.ubicacion
	from liquidacion_compra lc
	inner join proveedores p
	using(id_proveedor)
	inner join empresa e
	using(id_empresa)
	inner join forma_pagos fp
	using(id_forma_pago)
	inner join punto_venta pv on id_punto_venta=lc.id_empresa
	where lc.id_liquidacion_compra=$id
	";

	$res = pg_query($sql);
	$row = pg_fetch_assoc($res);
	if (empty($row)) {
		return [];
	}
	return $row;
}
function getDetallesLiquidacion($id)
{
	$sql = "
	select P.codigo,
    P.cod_barras,
    P.articulo,
    D.cantidad,
    D.precio_venta,
    D.descuento_producto,
    F.tarifa12
    from liquidacion_compra F,
    detalle_liquidacion_compra D,
    productos P
    where d.cod_productos = P.cod_productos
    and D.id_liquidacion_compra = F.id_liquidacion_compra
    AND F.id_liquidacion_compra =$id
	";

	$res = pg_query($sql);
	$row = pg_fetch_all($res);
	if (empty($row)) {
		return [];
	}
	return $row;
}
