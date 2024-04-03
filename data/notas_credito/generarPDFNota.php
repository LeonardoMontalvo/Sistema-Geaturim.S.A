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

    generarPDFNota($id);
}

function generarPDFNota($id)
{
    global $pdf;
    $infofac = getInfoDevolucion($id);
    $detallesfac = getDetallesDevolucion($id);

    //datos empresa
    $razonsocial = $infofac["nombre_empresa"];
    $dirmatriz = $infofac["direccion_empresa"];
    $rucempresa = $infofac["ruc_empresa"];
    $obligadoconta = $infofac["obligacion"];
    $contribuyenteespe = $infofac["contribuyente_espe"];
    //datos devolución
    $numdevolucion = $infofac["num_nota_serie"] . "-" . $infofac["num_nota_credito"];
    $numautorizacion = $infofac["num_autorizacion"];
    if ($numautorizacion == "") {
        $numautorizacion = $infofac['clave'];
    } else {
        $numautorizacion = $infofac['num_autorizacion'];
    }
    $fechaaut = $infofac["fecha_actual"] . " " . $infofac["hora_actual"];
    $claveacceso = $infofac["clave"];
    $fechaemision = $infofac["fecha_actual"];
    $motivo = $infofac["motivo"];
    $fechafactura = $infofac["fecha_factura"];
    $tipocomprobante = $infofac["tipo_comprobante"];
    $numfactura = $infofac["num_nota_serie"] . "-" . $infofac["num_serie"];
    $tarifa0venta = $infofac["tarifa0"];
    $tarifa12venta = $infofac["tarifa12"];
    $ivaventa = $infofac["iva_venta"];
    $totalventa = $infofac["total_venta"];
    $descuentoventa = $infofac["descuento_venta"];
    $ambiente = 2;
    $emision = 1;
    $dirsucursal=$infofac["ubicacion"];
    //datos cliente
    $razonsocialcli = $infofac["nombres_cli"];
    $identificacioncli = $infofac["identificacion"];
    $direccioncli = $infofac["direccion_cli"];
    $telefonocli = $infofac["telefono_cli"];
    $celularcli = $infofac["celular_cli"];
    $correocli = $infofac["correo"];


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
        "NOTA DE CRÉDITO",
        $rucempresa,
        $numdevolucion,
        $numautorizacion,
        $ambiente,
        $emision,
        $fechaaut,
        $claveacceso,
        '../../images/' . $_SESSION["parametros_empresa"]["logo_empresa"],
        $dirsucursal,
        $cellheight
    );

    //información cliente
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->Cell($halfw, $cellheight, "", 0, 0);
    $pdf->Cell($halfw, $cellheight, utf8_decode("RUC/CI: $identificacioncli"), 0, 1);
    $pdf->SetXY($x, $y);
    $pdf->MultiCell($halfw, $cellheight, utf8_decode("Razón Social: $razonsocialcli"));
    $pdf->Ln(2);
    $pdf->Cell($halfw, $cellheight, utf8_decode("Fecha de emisión: " . $fechaemision), 0, 1);
    $pdf->Ln(2);

    //lìnea divisora
    $pdf->Line(10, $pdf->GetY(), $totalw - 6, $pdf->GetY());
    $pdf->Ln(2);

    //información factura
    $pdf->Cell($halfw - 35, $cellheight, utf8_decode("Comprobante que se Modifica:"), 0, 0);
    $pdf->Cell($halfw + 35, $cellheight, "$tipocomprobante NRO. $numfactura", 0, 1);

    $pdf->Cell($halfw - 35, $cellheight, utf8_decode("Fecha de emisión (Comprobane a Modificar):"), 0, 0);
    $pdf->Cell($halfw + 35, $cellheight, $fechafactura, 0, 1);

    $pdf->Cell($halfw - 35, $cellheight, utf8_decode("Motivo de Modificación: "), 0, 0);
    $pdf->Cell($halfw + 35, $cellheight, $motivo, 0, 1);

    $pdf->Ln(2);

    //detalles factura
    $cellwidth = $totalw / 6;
    $pdf->SetWidths([
        $cellwidth - 10,
        $cellwidth - 13,
        $cellwidth + 62,
        $cellwidth - 13,
        $cellwidth - 13,
        $cellwidth - 13
    ]);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetAligns(array_fill(0, 7, "C"));
    $pdf->Row([
        "Cod. Principal",
        "Cantidad",
        utf8_decode("Descripción"),
        "Precio U.",
        "Descu. %",
        "Total"
    ], 1);
    $pdf->SetFont('Arial', '', 9);
    $pdf->SetAligns(["L", "C", "L", "R", "R", "R"]);
    foreach ($detallesfac as $row) {
        if (!empty($row["unidad_medida"])) {
            $descripcion = utf8_decode($row["articulo"] . "(" . $row["unidad_medida"] . ")");
        } else {
            $descripcion = utf8_decode($row["articulo"]);
        }
        if (!empty($row[8])) {
            $descripcion .= " -- " . utf8_decode($row["detalle_producto"]);
        }

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
    $pdf->Ln(2);

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
    $pdf->Cell($cellwidth, $cellheight, number_format(round($tarifa12venta, 2), 2, ".", ""), 0, 1, "R");

    $pdf->Cell($offsetleft, $cellheight, "", 0, 0);
    $pdf->Cell($cellwidth, $cellheight, utf8_decode("Subtotal 0 % "), 0, 0);
    $pdf->Cell($cellwidth, $cellheight, number_format(round($tarifa0venta, 2), 2, ".", ""), 0, 1, "R");

    $pdf->Cell($offsetleft, $cellheight, "", 0, 0);
    $pdf->Cell($cellwidth, $cellheight, utf8_decode("Descuento "), 0, 0);
    $pdf->Cell($cellwidth, $cellheight, number_format(round($descuentoventa, 2), 2, ".", ""), 0, 1, "R");

    $pdf->Cell($offsetleft, $cellheight, "", 0, 0);
    $pdf->Cell($cellwidth, $cellheight, utf8_decode("IVA 15%"), 0, 0);
    $pdf->Cell($cellwidth, $cellheight, number_format(round($ivaventa, 2), 2, ".", ""), 0, 1, "R");

    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell($offsetleft, $cellheight, "", 0, 0);
    $pdf->Cell($cellwidth, $cellheight, utf8_decode("Total"), 0, 0);
    $pdf->Cell($cellwidth, $cellheight, number_format(round($totalventa, 2), 2, ".", ""), 0, 1, "R");
    $pdf->SetFont('Arial', '', 9);

    //información adicional
    $pdf->SetXY($x, $y);
    $cellwidth = $offsetleft - 10;
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell($cellwidth, $cellheight, utf8_decode("Informaciòn Adicional:"), 0, 1);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Ln(2);
    $pdf->MultiCell($cellwidth, $cellheight, utf8_decode("Dirección: " . mb_strtoupper($direccioncli)));
    $pdf->Cell($cellwidth, $cellheight, utf8_decode("Email: $correocli"), 0, 1);
    $pdf->Cell($cellwidth, $cellheight, utf8_decode("Teléfono: " . (empty($celularcli) ? $telefonocli : $celularcli)), 0, 1);
    $pdf->Ln(2);




    if (isset($_GET['id'])) {
        $pdf->Output();
    } else {
        $pdf_file_contents = $pdf->Output("", "S");
        return $pdf_file_contents;
    }
}

function getInfoDevolucion($id)
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
    c.nombres_cli,
    c.identificacion,
    c.direccion_cli,
    c.telefono telefono_cli,
    c.celular celular_cli,
    c.correo,
    dc.tarifa12, 
    dc.tarifa0, 
    dc.tarifa0, 
    dc.iva_venta, 
    dc.descuento_venta, 
    dc.total_venta,
    dc.clave,
    dc.fecha_actual,
    dc.hora_actual,
    dc.num_serie,
    dc.num_nota_serie,
    dc.num_nota_credito,
    dc.num_autorizacion,
    dc.tipo_comprobante,
    dc.fecha_actual,
    dc.motivo,
    fv.fecha_actual fecha_factura,
    pv.ubicacion
    from devolucion_venta dc
    inner join clientes c
    using(id_cliente)
    inner join empresa e
    using(id_empresa)
    inner join factura_venta fv
    on dc.num_serie=fv.num_factura
    inner join punto_venta pv on id_punto_venta=fv.id_empresa
    where id_devolucion_venta=$id
    ";
    $res = pg_query($sql);
    $row = pg_fetch_assoc($res);
    if (empty($row)) {
        return [];
    }
    return $row;
}

function getDetallesDevolucion($id)
{
    $sql = "
    select 
    P.codigo, 
    P.articulo, 
    D.cantidad, 
    D.precio_venta, 
    D.descuento_producto, 
    D.precio_venta,f.tarifa12, 
    (D.cantidad::float*D.precio_venta::float) as tarifa12,
    ((D.cantidad::float*D.precio_venta::float)*0.15) as iva12, 
    p.iva, D.unidad_medida  
    from devolucion_venta F,detalle_devolucion_venta D, 
    productos P 
    where  d.cod_productos =P.cod_productos 
    and D.id_devolucion_venta = F.id_devolucion_venta 
    AND F.id_devolucion_venta=$id
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}
