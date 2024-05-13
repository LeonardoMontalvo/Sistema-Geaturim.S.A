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
    var $temp1;
    var $temp2;
    var $temp3;
    var $temp4;
    var $temp5;
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
        $this->Cell(105, 5, "COMPRAS", 0, 1, 'C', 0);
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
        $this->Cell(210, 5, utf8_decode("DEVOLUCIÓN COMPRA"), 0, 1, 'C', 0);
        $this->SetFont('Amble-Regular', '', 10);
        $this->Ln(9);
        $this->SetFillColor(220, 240, 210);
        $row = pg_fetch_assoc(
            pg_query(
                "SELECT * 
                from devolucion_compra,
                proveedores,usuario,empresa 
                where devolucion_compra.id_proveedor=proveedores.id_proveedor 
                and devolucion_compra.id_usuario=usuario.id_usuario 
                and devolucion_compra.id_empresa=empresa.id_empresa 
                and id_devolucion_compra='$_GET[id]';"
            )
        );
        $this->SetLineWidth(0.2);
        $this->SetFont('helvetica', 'B', 10);
        $this->Cell(105, 6, utf8_decode('CI/Ruc: ' . $row["identificacion_pro"]), 0, 0, 'L', 1);
        $this->Cell(105, 6, utf8_decode('Proveedor: ' . $row["empresa_pro"]), 0, 1, 'L', 1);
        $this->Cell(105, 6, utf8_decode('Representante: ' . $row["representante_legal"]), 0, 0, 'L', 1);
        $this->Cell(105, 6, utf8_decode('Dirección: ' . $row["direccion_pro"]), 0, 1, 'L', 1);
        $this->Cell(105, 6, utf8_decode('Teléfono: ' . $row["telefono"]), 0, 0, 'L', 1);
        $this->Cell(105, 6, utf8_decode('Celular: ' . $row["celular"]), 0, 1, 'L', 1);
        $this->Cell(105, 6, utf8_decode('CI/RUC Responsable: ' . $row["ci_usuario"]), 0, 0, 'L', 1);
        $this->Cell(105, 6, utf8_decode('Responsable: ' . $row["nombre_usuario"] . ' ' . $row["apellido_usuario"]), 0, 0, 'L', 1);
        $this->temp1 = $row["tarifa0"];
        $this->temp2 = $row["tarifa12"];
        $this->temp3 = $row["iva_compra"];
        $this->temp4 = $row["descuento_compra"];
        $this->temp5 = $row["total_compra"];
        $this->Ln(8);
        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(175, 215, 240);
        $this->Cell(40, 6, utf8_decode("CODIGO"), 1, 0, 'C', 1);
        $this->Cell(90, 6, utf8_decode("PRODUCTO"), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode("CANT."), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode("PVP"), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode("DESC."), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode("TOTAL"), 1, 1, 'C', 1);
        $this->Ln(1);
    }
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$tarifasimpfactura = obtenerTarifasImpuestoFactura($_GET["id"]);

$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetTitle('Devolucion Compra');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();

$total = 0;
$temp1 = $pdf->temp1;
$temp2 = $pdf->temp2;
$temp3 = $pdf->temp3;
$temp4 = $pdf->temp4;
$temp5 = $pdf->temp5;
$sql = pg_query("select * from detalle_devolucion_compra,productos where detalle_devolucion_compra.cod_productos=productos.cod_productos and id_devolucion_compra='$_GET[id]' and productos.incluye_iva= 'No' order by id_devolucion_compra asc;");
while ($row = pg_fetch_row($sql)) {
    $pdf->SetX(1);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(39, 5, maxCaracter(utf8_decode($row[9]), 20), 0, 0, 'L', 0);
    $pdf->Cell(90, 5, maxCaracter(utf8_decode($row[11]), 20), 0, 0, 'L', 0);
    $pdf->Cell(20, 5, number_format($row[3], 2, ',', '.'), 0, 0, 'R', 0);
    $pdf->Cell(20, 5, number_format($row[4], 2, ',', '.'), 0, 0, 'R', 0);
    $pdf->Cell(20, 5, number_format($row[5], 2, ',', '.'), 0, 0, 'R', 0);
    $pdf->Cell(20, 5, number_format($row[6], 2, ',', '.'), 0, 1, 'R', 0);
}

$calculoIVA = pg_query("select valor from parametros where descripcion='IVA'");
while ($rowi = pg_fetch_row($calculoIVA)) {
    $iva_base = $rowi[0];
}
$iva_base = ($iva_base / 100) + 1;
$sql = pg_query("select * from detalle_devolucion_compra,productos where detalle_devolucion_compra.cod_productos=productos.cod_productos and id_devolucion_compra='$_GET[id]' and productos.incluye_iva= 'Si' order by id_devolucion_compra asc;");
while ($row = pg_fetch_row($sql)) {
    $pdf->SetX(1);

    $total_si = 0;
    $total_sit = 0;
    $total_si = $row[6] / $iva_base;
    $total_sit = $total_si / $row[3];
    $total_si = truncateFloat($total_si, 2);
    $total_sit = truncateFloat($total_sit, 2);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(39, 5, maxCaracter(utf8_decode($row[9]), 20), 0, 0, 'L', 0);
    $pdf->Cell(90, 5, maxCaracter(utf8_decode($row[11]), 20), 0, 0, 'L', 0);
    $pdf->Cell(20, 5, maxCaracter(utf8_decode($row[3]), 80), 0, 0, 'R', 0);
    $pdf->Cell(20, 5, maxCaracter(utf8_decode($total_sit), 20), 0, 0, 'R', 0);        //precio
    $pdf->Cell(20, 5, maxCaracter(utf8_decode($row[5]), 20), 0, 0, 'R', 0);                ///descuento                     
    $pdf->Cell(20, 5, maxCaracter(utf8_decode($total_si), 20), 0, 0, 'R', 0);                            //total         
    $pdf->Ln(5);
}
$pdf->SetX(1);
$pdf->Ln(5);
$sql = pg_query("select factura_compra.descuento_compra,factura_compra.tarifa0,factura_compra.tarifa12,factura_compra.iva_compra,factura_compra.total_compra from factura_compra,detalle_factura_compra,productos where factura_compra.id_factura_compra=detalle_factura_compra.id_factura_compra and detalle_factura_compra.cod_productos=productos.cod_productos and detalle_factura_compra.id_factura_compra='$_GET[id]' LIMIT 1");
while ($row = pg_fetch_row($sql)) {
    $pdf->SetFont('helvetica', 'B', 9);

    $pdf->Cell(170, 6, utf8_decode("Descuento"), 0, 0, 'R', 0);
    $pdf->Cell(35, 6, maxCaracter(truncateFloat($temp4, 2), 20), 0, 1, 'R', 0);

    /* $pdf->Cell(170, 6, utf8_decode("Tarifa 0"), 0, 0, 'R', 0);
    $pdf->Cell(35, 6, maxCaracter(truncateFloat($temp1, 2), 20), 0, 1, 'R', 0);

    $pdf->Cell(170, 6, utf8_decode("Tarifa IVA"), 0, 0, 'R', 0);
    $pdf->Cell(35, 6, maxCaracter(truncateFloat($temp2, 2), 20), 0, 1, 'R', 0); */

    if (empty($tarifasimpfactura)) {
        $pdf->Cell(170, 6, utf8_decode("Tarifa 0"), 0, 0, 'R', 0);
        $pdf->Cell(35, 6, maxCaracter(truncateFloat($temp1, 2), 20), 0, 1, 'R', 0);
        $pdf->Cell(170, 6, utf8_decode("Tarifa IVA"), 0, 0, 'R', 0);
        $pdf->Cell(35, 6, maxCaracter(truncateFloat($temp2, 2), 20), 0, 1, 'R', 0);
    } else {
        foreach ($tarifasimpfactura as $key => $value) {
            $pdf->Cell(170, 6, utf8_decode("Tarifa " . $value["tarifa"]), 0, 0, 'R', 0);
            $pdf->Cell(35, 6, number_format(round($value["base_imponible"], 2), 2, ',', '.'), 0, 1, 'R', 0);
        }
    }

    $pdf->Cell(170, 6, utf8_decode("Iva ...%"), 0, 0, 'R', 0);
    $pdf->Cell(35, 6, maxCaracter(truncateFloat($temp3, 2), 20), 0, 1, 'R', 0);

    $pdf->Cell(170, 6, utf8_decode("Total"), 0, 0, 'R', 0);
    $pdf->Cell(35, 6, maxCaracter(truncateFloat($temp5, 2), 20), 0, 1, 'R', 0);
}
$pdf->Output();


function obtenerTarifasImpuestoFactura($id)
{
    $sql = "select
    di.cod_impuesto, 
    di.cod_tarifa, 
    di.tarifa, 
    sum(di.valor_impuesto)valor_impuesto, 
    sum(di.base_imponible)base_imponible
    from
    devolucion_compra fc
    inner join detalle_devolucion_compra dfc
    using(id_devolucion_compra)
    inner join detalle_impuesto_producto_dev_compra di
    using(id_detalle_devcompra)
    where id_devolucion_compra=$id
    group by di.cod_tarifa, di.cod_impuesto, di.tarifa";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!empty($rows)) {
        return $rows;
    }
    return [];
}
