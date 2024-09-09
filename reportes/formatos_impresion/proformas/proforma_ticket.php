<?php
require __DIR__ . "/../../../fpdf/fpdf.php";
include __DIR__ . "/../../../procesos/base.php";
include __DIR__ . "/../../../procesos/funciones.php";

conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//                require_once( '../../procesos/funciones.php');
//error_reporting(0);


class PDF extends FPDF
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
}

$gdescuento = 0;
$id = 0;
$id = $_GET['id'];
$copias = !empty($_GET["copias"]);

$sql = pg_query("SELECT nombre_empresa, ruc_empresa, direccion_empresa, telefono_empresa, celular_empresa,
email_empresa, nombre_comercial, obligacion, contribuyente_espe, establecimiento, punto_emision,
fv.fecha_actual as fecha_emision, num_autorizacion, fecha_autorizacion, num_factura, num_serie, 
fv.clave, serie_guia_remision, marca_vehiculo, identificacion, nombres_cli, direccion_cli, 
case when telefono!='' then telefono else celular end as telefono_cli,ciudad,id_vendedor, fv.id_usuario,hora_actual,
u.usuario
from empresa e left join factura_venta fv using(id_empresa) 
left join clientes c using(id_cliente) 
left join tipo_documento td using(id_tdocu) 
left join usuario u using(id_usuario) 
where fv.id_factura_venta='" . $id . "'  ");

$rowempre = pg_fetch_assoc($sql);

$pdf = new PDF('P', 'mm', array(69, 700));
date_default_timezone_set('America/Guayaquil');

$fecha = date('Y-m-d H:i:s', time());



//list($width, $height, $type, $attr) = getimagesize('../../../images/' . $_SESSION["parametros_empresa"]["logo_empresa"]);
//$pdf->Image('../../../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 8, 5, 10); // Img Empresa


if (!empty($copias)) {
    imprimirPagina();
    imprimirPagina();
} else {
    imprimirPagina();
}



//$pdf->SetY(50);        
//$pdf->Row(array("**","."));


function imprimirPagina()
{
    imprimirReporte();
    imprimirPagare();
}

$pdf->Output();

function imprimirReporte()
{
    global $pdf, $id, $gdescuento, $rowempre;

    $rowempre = getEmpresa();
    $proforma = getProforma();

    $pdf->AddPage();
    $pdf->SetMargins(0, 0, 0, 0);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Ln(0);

    /* $conf = new Configuracion();
    $val_rimpe = $conf->getParametroEmpresa("val_rimpe");
    $agente_reten = $conf->getParametroEmpresa("check_agente_reten"); */



    $pagew = $pdf->GetCurrentWidth();

    $pdf->SetFont('Helvetica', '', 9);
    $pdf->Cell($pagew, 4, $rowempre['nombre_comercial'], 0, 1, "C");
    $pdf->SetFont('Helvetica', '', 7);
    $pdf->Cell($pagew, 4, $rowempre['nombre_empresa'], 0, 1, "C");
    $pdf->Cell($pagew, 4, $rowempre['ruc_empresa'], 0, 1, "C");
    $pdf->MultiCell($pagew, 4, $rowempre['direccion_empresa'], 0, "C");
    $pdf->Cell($pagew, 4, $rowempre['celular_empresa'] . "/" . $rowempre['telefono_empresa'], 0, 1, "C");
    $pdf->Cell($pagew, 4, "E-mail: " . $rowempre['email_empresa'], 0, 1, "C");
    $pdf->Ln(2);
    $pdf->Cell($pagew, 4, utf8_decode("Número Proforma: ") . str_pad($proforma["comprobante"], 9, "0", STR_PAD_LEFT), 0, 1);
    $pdf->Ln(2);
    $pdf->MultiCell($pagew, 4, utf8_decode("Cliente: " . $proforma["nombres_cli"]));
    $pdf->Cell($pagew, 4, utf8_decode("CI/RUC: " . $proforma["identificacion"]), 0, 1);
    $pdf->MultiCell($pagew, 4, utf8_decode("Dirección: " . $proforma["direccion_cli"]));
    $pdf->Cell($pagew, 4, utf8_decode("Email: " . $proforma["correo"]), 0, 1);
    $pdf->Cell($pagew, 4, utf8_decode("Teléfono: " . $proforma["celular"]), 0, 1);
    $pdf->Ln(2);
    $pdf->MultiCell($pagew, 4, utf8_decode("Responsable: " . $proforma["nombre_usuario"] . " " . $proforma["apellido_usuario"]));
    $pdf->Cell($pagew, 4, utf8_decode("Teléfono:" . $proforma["celular_usuario"]), 0, 1);

    $w = $pagew / 4;
    $pdf->SetAligns(["C", "C", "C", "C"]);
    $pdf->SetWidths([$w - 5, $w + 18, $w - 8, $w - 5]);
    $pdf->Row(["CANT", "PRODUCTO", "PU", "TOTAL"], 1);

    $detalles = getDetallesProforma();
    /* var_dump($detalles); */

    $pdf->SetAligns(["L", "L", "R", "R"]);
    foreach ($detalles as $key => $value) {
        $descuento = $value["descuento_venta"];
        $pv = $value["precio_venta"];
        if (!empty($descuento)) {
            $pdesc = (100 - $descuento) / 100;
            $vpu = $pv * $pdesc;
            $pv = utf8_decode(round($vpu, 2));
        }
        if ($value["iva"] == "Si") {
            $pdf->Row([$value["cantidad"], $value["articulo"], round($pv, 2), round($value["total_venta"], 2) . "*"]);
        } else {
            $pdf->Row([$value["cantidad"], $value["articulo"], round($value["precio_venta"], 2), round($value["total_venta"], 2)]);
        }
    }

    $pdf->Cell($pagew, 4, "", "T", 1);

    $tarifasimpfactura = obtenerTarifasImpuestoFactura($id);

    $sql = pg_query("select tarifa0,tarifa12,iva_venta,descuento_venta,total_venta from factura_venta where id_factura_venta= '" . $id . "' ");

    $sub0 = 0;

    $sub12 = 0;

    $iva = 0;

    $total = 0;

    while ($fila = pg_fetch_row($sql)) {

        if ($fila[4] < 1000) {

            $tar0 = round($fila[0], 3, PHP_ROUND_HALF_EVEN);

            $sub0 = round($fila[1], 3, PHP_ROUND_HALF_EVEN);

            $sub12 = round($fila[2], 2, PHP_ROUND_HALF_EVEN);

            $iva = round($fila[3], 2, PHP_ROUND_HALF_EVEN);

            $total = round($fila[4], 3, PHP_ROUND_HALF_EVEN);

            $pdf->SetFont('Arial', '', 8);

            $sub_total = $sub0 + $tar0;

            $tar0 = $tar0 + 0;

            $sub = $sub_total;

            $total = $total + 0;
            $total = number_format($total, 2, '.', '');

            ///
            $pdf->SetX(35);

            $pdf->SetWidths(array(22, 35));

            $gdescuento = $iva;
            $pdf->Row(array("Descuento", $iva));

            if (!empty($tarifasimpfactura)) {
                $sub = 0;
                foreach ($tarifasimpfactura as $key => $value) {
                    $pdf->SetX(35);
                    $pdf->SetWidths(array(22, 80));
                    $pdf->Row(array("Tarifa $value[tarifa]%", round($value["base_imponible"], 2)));
                    $sub += $value["base_imponible"];
                }
            } else {
                $pdf->SetX(35);

                $pdf->SetWidths(array(22, 80));

                $pdf->Row(array("Tarifa 15 .%", $sub0));

                $pdf->SetX(35);

                $pdf->SetWidths(array(22, 35));

                $pdf->Row(array("Tarifa 0%", $tar0));
            }

            ///

            $pdf->SetX(35);

            $pdf->SetWidths(array(22, 35));

            $pdf->Row(array("Subtotal", round($sub, 2)));



            ///
            if (!empty($tarifasimpfactura)) {
                foreach ($tarifasimpfactura as $key => $value) {
                    if ($value["valor_impuesto"] == 0) {
                        continue;
                    }

                    $pdf->SetX(35);

                    $pdf->SetWidths(array(22, 35));

                    $pdf->Row(array("Iva $value[tarifa]%", round($value["valor_impuesto"], 2)));
                }
            } else {
                $pdf->SetX(35);

                $pdf->SetWidths(array(22, 35));

                $pdf->Row(array("Iva 15%", $sub12));
            }
            ///

            $pdf->SetX(35);

            $pdf->SetWidths(array(22, 35));

            $pdf->Row(array("Total", round($value["total_proforma"])));
        } else {

            $tar0 = $fila[0];

            //$tar0 = truncateFloat(round($fila[0], 5, PHP_ROUND_HALF_EVEN),5);

            $sub0 = round($fila[1], 3, PHP_ROUND_HALF_EVEN);

            $sub12 = round($fila[2], 3, PHP_ROUND_HALF_EVEN);

            $iva = round($fila[3], 3, PHP_ROUND_HALF_EVEN);

            $total = round($fila[4], 3, PHP_ROUND_HALF_EVEN);


            $pdf->SetFont('Arial', '', 7);


            $sub_total = $sub0 + $tar0;
            $tarvar = $tar0 + 0;
            $tarvar1 = round($tarvar, 2);

            $sub = round($sub_total, 2);

            ///
            if (!empty($tarifasimpfactura)) {
                $sub = 0;
                foreach ($tarifasimpfactura as $key => $value) {
                    $pdf->SetX(35);
                    $pdf->SetWidths(array(22, 80));
                    $pdf->Row(array("Tarifa $value[tarifa]%", $value["base_imponible"]));
                    $sub += $value["base_imponible"];
                }
            } else {
                $pdf->SetX(35);

                $pdf->SetWidths(array(22, 80));

                $pdf->Row(array("Tarifa 15 .%", $sub0));

                $pdf->SetX(35);

                $pdf->SetWidths(array(22, 80));

                $pdf->Row(array("Tarifa 0%", $tarvar1));
            }
            //

            $pdf->SetX(35);

            $pdf->SetWidths(array(22, 35));

            $pdf->Row(array("Subtotal", $sub));

            $pdf->SetX(35);

            $pdf->SetWidths(array(22, 35));

            $gdescuento = $iva;
            $pdf->Row(array("Descuento", $iva));

            ///
            if (!empty($tarifasimpfactura)) {
                foreach ($tarifasimpfactura as $key => $value) {
                    if ($value["valor_impuesto"] == 0) {
                        continue;
                    }

                    $pdf->SetX(35);

                    $pdf->SetWidths(array(22, 35));

                    $pdf->Row(array("Iva $value[tarifa]%", round($value["valor_impuesto"], 2)));
                }
            } else {
                $pdf->SetX(35);

                $pdf->SetWidths(array(22, 35));

                $pdf->Row(array("Iva 15%", $sub12));
            }

            ///

            $pdf->SetX(35);

            $pdf->SetWidths(array(22, 35));

            $pdf->Row(array("Total", $total));
        }
    }
}

function buscarVendedor($id)
{
    $sql = "select*from vendedores where id_vendedor=$id";
    $res = pg_query($sql);
    $row = pg_fetch_assoc($res);
    return $row;
}
function buscarUsuaraio($id)
{
    $sql = "select*from usuario where id_usuario=$id";
    $res = pg_query($sql);
    $row = pg_fetch_assoc($res);
    return $row;
}

function buscarUmAb($desc)
{
    if (empty($desc)) {
        return "";
    }
    $sql = "
    SELECT id_unidades, descripcion, abreviatura, cantidad, estado
    FROM unidades_medida where descripcion='$desc';
    ";
    $res = pg_query($sql);
    $row = pg_fetch_assoc($res);
    if (empty($row)) {
        return maxCaracter($desc, 4) . "-";
    }
    return maxCaracter($row["abreviatura"], 4) . "-";
}


function getAmbiente($ambiente)
{
    $consulta_ambiente = pg_query("select nombre_ambi from ambiente where id_ambi='$ambiente'  ");
    while ($row = pg_fetch_row($consulta_ambiente)) {
        $nombre_ambi = $row[0];
    }
    return $ambiente = $nombre_ambi;
}

function getEmision($emision)
{
    $consulta_emision = pg_query("select nombre_temision from tipo_emision  where id_temision='$emision' ");
    $row = pg_fetch_row($consulta_emision);
    return $row[0];
}


function obtenerTarifasImpuestoFactura($id)
{
    $sql = "
    select
    di.cod_impuesto, 
    di.cod_tarifa, 
    di.tarifa, 
    sum(di.valor_impuesto)valor_impuesto, 
    sum(di.base_imponible)base_imponible,
	fc.total_proforma
    from
    proforma fc
    inner join detalle_proforma dfc
    using(id_proforma)
    inner join detalle_impuesto_producto_proforma di
    using(id_detalle_proforma)
    where id_proforma=$id
    group by di.cod_tarifa, di.cod_impuesto, di.tarifa,fc.total_proforma
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!empty($rows)) {
        return $rows;
    }
    return [];
}


function imprimirPagare()
{
    global $pdf, $id, $rowempre;
    $sql = "
    select*from formas_pago_mixto
    where tipo_documento='FACTURA'
    and forma_pago='CREDITO'
    and id_factura_venta=$id 
    and estado='Activo'
    limit 1
    ";

    $res = pg_query($sql);
    $row = pg_fetch_assoc($res);
    if (!empty($row)) {
        $pagew = $pdf->GetCurrentWidth();
        $pdf->Ln(2);
        $pdf->cell($pagew, 4, utf8_decode("Crédito. " . $row["valor"]), 0, 1);
        $pdf->cell($pagew, 4, utf8_decode("Crédito, " . $row["fecha_actual"]), 0, 1);
        $pdf->Ln(2);
        $pdf->MultiCell($pagew, 4, utf8_decode("Debo y pagare de manera incondicional a la orden de " . $rowempre["nombre_empresa"] . " , en la ciudad de: IBARRA, el valor de $" . $row["valor"] . ", valor que recibo en productos a crédito detallados en este documento."), 0);
        $pdf->Ln(10);
        $pdf->cell($pagew, 4, $rowempre["nombres_cli"], "Ts", 1);
        $pdf->cell($pagew, 4, "Id: " . $rowempre["identificacion"], 0, 1);
    }
    return;
}

function getEmpresa()
{
    $sql = "select*from empresa where id_empresa=$_SESSION[PV]";
    $res = pg_query($sql);
    $rows = pg_fetch_assoc($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}

function getProforma()
{
    $sql = "select
    p.*, 
    c.identificacion,
    c.nombres_cli,
    c.direccion_cli,
    c.telefono,
    c.celular,
    c.correo,
    u.nombre_usuario,
    u.apellido_usuario,
    u.ci_usuario,
    u.telefono_usuario,
    u.celular_usuario
    from 
    proforma p
    inner join clientes c using(id_cliente)
    inner join usuario u using (id_usuario)
    where id_proforma=$_GET[id]";
    $res = pg_query($sql);
    $rows = pg_fetch_assoc($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}

function getDetallesProforma()
{
    $sql = "select
    dp.*, p.articulo,p.iva
    from detalle_proforma dp
    inner join productos p using(cod_productos) where id_proforma=$_GET[id]";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}
