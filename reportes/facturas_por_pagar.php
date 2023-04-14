<?php

require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();
/* var_dump(obtenerFacturas());
  exit(); */
$query_punto = "";
if ($_GET['id_empre'] != '0') {
    $query_punto = "AND cp.id_empresa='$_GET[id_empre]'";
}

$id_usuario_cp = "";
if ($_GET['id'] != '0') {
    $id_usuario_cp = "and cp.id_usuario='$_GET[id]'";
}

////////////////////////////

$query_punto_fv = "";
if ($_GET['id_empre'] != '0') {
    $query_punto_fv = "AND g.id_empresa='$_GET[id_empre]'";
}
$id_usuario_fv = "";
if ($_GET['id'] != '0') {
    $id_usuario_fv = "and g.id_usuario='$_GET[id]'";
}

class PDF extends FPDF
{

    var $widths;
    var $aligns;
    var $tipor;

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

    function SetTipoReporte($tr)
    {
        $this->tipor = $tr;
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
        $this->Cell(105, 5, "CARTERA CxP", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(210, 8, utf8_decode($_SESSION['nombre_empresa']), 0, 1, 'C', 0);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 10, 7, 15, 15);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 180, 7, 15, 15);
        // $this->Cell(180, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        // $this->Cell(80, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        // $this->Cell(80, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(0, 27, 210, 27);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 5, utf8_decode("FACTURAS POR PAGAR PROVEEDORES"), 0, 1, 'C', 0);
        $this->Cell(210, 5, utf8_decode($this->tipor), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(4);
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
if ($_GET['tipo'] == 'Externas') {
    $pdf->SetTipoReporte(" CUENTAS EXTERNAS");
} else if ($_GET['tipo'] == 'Internas') {
    $pdf->SetTipoReporte("CUENTAS INTERNAS");
} else {
    $pdf->SetTipoReporte("CUENTAS INTERNAS Y EXTERNAS");
}

$pdf->AddPage();
$pdf->SetTitle('Facturas por Pagar');
$pdf->AliasNbPages();
$pdf->SetFont('Amble-Regular', '', 9);

$query_fecha = "";
// RANGO DE FECHAS O FECHA ACTUAL
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}
//GENERAL O POR PROVEEDOR
if (isset($_GET['id_pro']) && $_GET['id_pro'] != '')
    $sql = pg_query(
        "SELECT id_proveedor, tipo_documento, identificacion_pro, empresa_pro, representante_legal 
        FROM proveedores WHERE estado='Activo' and id_proveedor={$_GET['id_pro']}"
    );
else
    $sql = pg_query(
        "SELECT id_proveedor, tipo_documento, identificacion_pro, empresa_pro, representante_legal 
        FROM proveedores WHERE estado='Activo'"
    );

if (pg_num_rows($sql)) {
    $query_fecha = "";
    // RANGO DE FECHAS O FECHA ACTUAL
    if ($pdf->rango) {
        $query_fecha = "BETWEEN '$_GET[inicio]' AND";
    } else {
        $query_fecha = "=";
    }

    //EXTERNAS E INTERNAS
    if ($_GET['tipo'] == 'Externas') {
        $total = 0;
        while ($row = pg_fetch_row($sql)) {
            $sub = 0;
            $filas = obtenerCPExternas($row[0]);
            if (count($filas) > 0) {
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->SetFillColor(216, 216, 231);
                $pdf->Cell(50, 8, utf8_decode(strtoupper($row[1]) . ": " . $row[2]), 1, 0, 'L', true);
                $pdf->Cell(80, 8, utf8_decode("PROVEEDOR: " . $row[3]), 1, 0, 'L', true);
                $pdf->Cell(80, 8, utf8_decode("REPRESENTANTE: " . $row[4]), 1, 1, 'L', true);
                $pdf->SetFillColor(175, 215, 240);
                //$pdf->Cell(30, 6, utf8_decode('COMPROBANTE'), 1, 0, 'C', 1);
                $pdf->Cell(30, 6, utf8_decode('N° DOCUMENTO'), 1, 0, 'C', 1);
                $pdf->Cell(22, 6, utf8_decode('EMISIÓN'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('VENCIMIENTO'), 1, 0, 'C', 1);
                $pdf->Cell(30, 6, utf8_decode('CADUCA (DÍAS)'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('TIPO DOC.'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('TOTAL'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('ABONOS'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('SALDO'), 1, 1, 'C', 1);
                $pdf->Ln(7);
                foreach ($filas as $fila) {
                    $pdf->SetFont('helvetica', '', 9);
                    //$pdf->Cell(30, 6, utf8_decode($fila["comprobante"]), 0, 0, 'C', 0);
                    $pdf->Cell(30, 6, utf8_decode($fila["num_factura"]), 0, 0, 'L', 0);
                    $pdf->Cell(22, 6, utf8_decode($fila["fecha_emicion"]), 0, 0, 'L', 0);
                    $pdf->Cell(25, 6, utf8_decode($fila["fecha_vencimiento"]), 0, 0, 'L', 0);
                    if($fila['vence']>=0){
                        $pdf->Cell(30, 6, utf8_decode($fila['vence']), 0, 0, 'L', 0);
                    }else{
                        $pdf->Cell(30, 6, utf8_decode("VENCIDA"), 0, 0, 'L', 0);
                    }
                    $pdf->Cell(25, 6, utf8_decode($fila["abreviatura"]), 0, 0, 'C', 0);
                    $pdf->Cell(26, 6, number_format($fila["total"], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(26, 6, number_format($fila["abonos"], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(26, 6, number_format($fila["saldo"], 2, ',', '.'), 0, 1, 'R', 0);
                    $sub += $fila["saldo"];
                }
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
                $pdf->Cell(180, 6, utf8_decode("Saldo Proveedor"), 0, 0, 'R', 0);
                $pdf->Cell(30, 6, maxCaracter((number_format($sub, 2, ',', '.')), 20), 0, 1, 'R', 0);
                $pdf->Ln(3);
                $total += $sub;
            }
        }
    } else if ($_GET['tipo'] == 'Internas') {
        $total = 0;
        while ($row = pg_fetch_row($sql)) {
            /*  var_dump($row[0]);
              var_dump(obtenerFacturas($row[0])); */
            $sub = 0;
            
            $filas = obtenerFacturas($row[0]);
            if (!empty($filas)) {
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->SetFillColor(216, 216, 231);
                $pdf->Cell(40, 8, utf8_decode(strtoupper($row[1]) . ": " . $row[2]), 1, 0, 'L', true);
                $pdf->Cell(85, 8, utf8_decode("PROVEEDOR: " . $row[3]), 1, 0, 'L', true);
                $pdf->Cell(85, 8, utf8_decode("REPRESENTANTE: " . $row[4]), 1, 1, 'L', true);
                $pdf->SetFillColor(175, 215, 240);
                //$pdf->Cell(10, 6, utf8_decode('Comp.'), 1, 0, 'C', 1);
                //$pdf->Cell(20, 6, utf8_decode('Tipo C/G.'), 1, 0, 'C', 1);
                $pdf->Cell(30, 6, utf8_decode('Nº DOCUMENTO'), 1, 0, 'C', 1);
                $pdf->Cell(20, 6, utf8_decode('EMISIÓN'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('VENCIMIENTO'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('COMP./GAST.'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('TOTAL'), 1, 0, 'C', 1);
                $pdf->Cell(20, 6, utf8_decode('- R.FUENTE'), 1, 0, 'C', 1);
                $pdf->Cell(15, 6, utf8_decode('- R.IVA'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('ABONOS'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('SALDO'), 1, 1, 'C', 1);
                foreach ($filas as $row1) {
                    $pdf->SetFont('helvetica', '', 9);
                    $pdf->Cell(30, 6, utf8_decode($row1["num_factura"]), 0, 0, 'L', 0);
                    $pdf->Cell(20, 6, utf8_decode($row1["fecha_emision"]), 0, 0, 'L', 0);
                    $pdf->Cell(25, 6, utf8_decode($row1["fecha_dias"]), 0, 0, 'L', 0);
                    $pdf->Cell(25, 6, utf8_decode($row1["tipo"]), 0, 0, 'C', 0);
                    $pdf->Cell(25, 6, number_format($row1["monto_credito"], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(20, 6, number_format($row1["renta"], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(15, 6, number_format($row1["iva"], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(25, 6, number_format($row1["valor_pagado"], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(25, 6, number_format($row1["saldo_factura"], 2, ',', '.'), 0, 1, 'R', 0);
                    $sub += $row1["saldo_factura"];
                }
                $pdf->Ln(6);
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
                $pdf->Cell(180, 6, utf8_decode("Saldo Proveedor"), 0, 0, 'R', 0);
                $pdf->Cell(30, 6, maxCaracter((number_format($sub, 2, ',', '.')), 20), 0, 1, 'R', 0);
                $pdf->Ln(2);
                $total += $sub;
            }
        }
    } else {
        $total = 0;
        while ($row = pg_fetch_row($sql)) {
            $sub = 0;
            $filas = obtenerCpIternasExternas($row[0]);
            if (count($filas) > 0) {
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->SetFillColor(216, 216, 231);
                $pdf->Cell(50, 8, utf8_decode(strtoupper($row[1]) . ": " . $row[2]), 1, 0, 'L', true);
                $pdf->Cell(80, 8, utf8_decode("PROVEEDOR: " . $row[3]), 1, 0, 'L', true);
                $pdf->Cell(80, 8, utf8_decode("REPRESENTANTE: " . $row[4]), 1, 1, 'L', true);
                $pdf->SetFillColor(175, 215, 240);
                $pdf->Cell(30, 6, utf8_decode('N° DOCUMENTO'), 1, 0, 'C', 1);
                $pdf->Cell(22, 6, utf8_decode('EMISIÓN'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('VENCIMIENTO'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('TIPO DOC.'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('TOTAL'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('ABONOS'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('SALDO'), 1, 0, 'C', 1);
                //$pdf->Cell(30 - 5, 6, utf8_decode('ULTIMO PAGO'), 1, 0, 'C', 1);
                $pdf->Cell(30, 6, utf8_decode('CUENTA'), 1, 1, 'C', 1);
                foreach ($filas as $fila) {
                    $pdf->SetFont('helvetica', '', 9);
                    $pdf->Cell(30, 6, utf8_decode($fila["num_factura"]), 0, 0, 'L', 0);
                    $pdf->Cell(22, 6, utf8_decode($fila["fecha_emision"]), 0, 0, 'L', 0);
                    $pdf->Cell(25, 6, utf8_decode($fila["fecha_actual"]), 0, 0, 'L', 0);
                    $pdf->Cell(25, 6, utf8_decode($fila["tipo_documento"]), 0, 0, 'C', 0);
                    $pdf->Cell(26, 6, number_format($fila["total"], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(26, 6, number_format($fila["abonos"], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(26, 6, number_format($fila["saldo"], 2, ',', '.'), 0, 0, 'R', 0);
                    //$pdf->Cell(26 - 5, 6, utf8_decode($fila["fecha_pago"]), 0, 0, 'C', 0);
                    $pdf->Cell(30, 6, utf8_decode($fila["tipo"]), 0, 1, 'C', 0);
                    $sub += $fila["saldo"];
                }
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
                $pdf->Cell(150, 6, utf8_decode("Saldo Proveedor"), 0, 0, 'R', 0);
                $pdf->Cell(30, 6, maxCaracter((number_format($sub, 2, ',', '.')), 20), 0, 1, 'R', 0);
                $pdf->Ln(3);
                $total += $sub;
            }
        }
    }
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
    if(!empty($_GET['tipo'])){
        $pdf->Cell(180, 6, utf8_decode("Total Saldos"), 0, 0, 'R', 0);
    }else{
        $pdf->Cell(150, 6, utf8_decode("Total Saldos"), 0, 0, 'R', 0);
    }
    $pdf->Cell(30, 6, maxCaracter((number_format($total, 2, ',', '.')), 20), 0, 1, 'R', 0);
    $pdf->Ln(3);
}

$pdf->Output();

function obtenerFacturas($idproveedor)
{
    global $query_punto_fv, $id_usuario_fv;

    $sql = "
    (select*from(
        select
        row_number() over(partition by g.id_factura_compra order by  pp.id_cuentas_pagar DESC) rn,
        p.identificacion_pro,
        p.empresa_pro,
        p.representante_legal,
        g.comprobante,
        g.num_serie as num_factura,
        fpg.fecha_actual as fecha_dias,
        g.total_compra as total,
        pc.monto_credito,
        round(coalesce(sum(pp.valor_pagado)over(partition by g.id_factura_compra),0),2)valor_pagado,
        round(coalesce(pp.saldo_factura,pc.monto_credito,0),2)saldo_factura,
        pp.fecha_actual,
        'C'::text as tipo,
        SUM(drv1.valor_retenido) as renta,
        SUM( drv2.valor_retenido) as iva,
        g.fecha_emision
        from factura_compra g
        inner join formas_pago_mixto_c fpg
        on g.id_factura_compra=fpg.id_factura_compra
        inner join proveedores p
        on p.id_proveedor=g.id_proveedor
         
        left join pagos_compra pc 
        on pc.id_factura_compra=g.id_factura_compra
        and pc.comprao_gasto='C'         
        left JOIN retencion_fuente_factura_compra rfc 
        ON pc.id_factura_compra=rfc.id_factura and rfc.estado <> 'g' and rfc.id_gastos=1  
        LEFT JOIN detallecomprobanteretencion drv1
                    on drv1.id_retencion_fuente_factura_compra=rfc.id_retencion_fuente_factura_compra
                    and drv1.id_trete=1
                    LEFT JOIN detallecomprobanteretencion drv2
                    on drv2.id_retencion_fuente_factura_compra=rfc.id_retencion_fuente_factura_compra  
                    and drv2.id_trete=2                      
        left join pagos_pagar pp
        on pp.id_factura_compra=g.id_factura_compra
        and pp.comprao_gasto='C'
        where fpg.forma_pago='CREDITO' 
        and g.estado='Activo'
        and g.id_proveedor='$idproveedor' $query_punto_fv $id_usuario_fv
        and pc.fecha_credito BETWEEN '$_GET[inicio]' AND '$_GET[fin]' 
        GROUP BY p.identificacion_pro,p.empresa_pro,p.representante_legal,g.comprobante,g.num_serie,fpg.fecha_actual,g.total_compra,
        pc.monto_credito,pp.valor_pagado,pp.saldo_factura,pp.fecha_actual,pp.id_cuentas_pagar,g.id_factura_compra
      
        )as cuentas_compras
        where rn =1
        and saldo_factura>0 )
        union all
        (select*from(
        select
        row_number() over(partition by g.id_gastos order by  pp.id_cuentas_pagar DESC) rn,
        p.identificacion_pro,
        p.empresa_pro,
        p.representante_legal,
        g.comprobante::text,
        g.num_factura,
        fpg.fecha_actual as fecha_dias,
        g.total,
        pc.monto_credito,
        round(coalesce(sum(pp.valor_pagado)over(partition by g.id_gastos),0),2)valor_pagado,
        round(coalesce(pp.saldo_factura,pc.monto_credito,0),2)saldo_factura,
        pp.fecha_actual,
        'G'::text as tipo,
        SUM(drv1.valor_retenido) as renta,
        SUM( drv2.valor_retenido) as iva,
        g.fecha_emision
        from gastos g
        inner join formas_pago_mixto_g fpg
        on g.id_gastos=fpg.id_gastos
        inner join proveedores p
        on p.id_proveedor=g.id_proveedor
        left join pagos_compra pc 
        on pc.id_factura_compra=g.id_gastos
        and pc.comprao_gasto='G' 
        left JOIN retencion_fuente_factura_compra rfc 
        ON pc.id_factura_compra=rfc.id_factura and rfc.estado <> 'g' and rfc.id_gastos=10
          LEFT JOIN detallecomprobanteretencion drv1
                    on drv1.id_retencion_fuente_factura_compra=rfc.id_retencion_fuente_factura_compra
                    and drv1.id_trete=1
                    LEFT JOIN detallecomprobanteretencion drv2
                    on drv2.id_retencion_fuente_factura_compra=rfc.id_retencion_fuente_factura_compra  
                    and drv2.id_trete=2         
        left join pagos_pagar pp
        on pp.id_factura_compra=g.id_gastos
        and pp.comprao_gasto='G'
        where fpg.forma_pago='CREDITO' 
        and g.estado='Activo'
        and g.id_proveedor='$idproveedor' $query_punto_fv $id_usuario_fv
        and pc.fecha_credito BETWEEN '$_GET[inicio]' AND '$_GET[fin]' 
        GROUP BY p.identificacion_pro,p.empresa_pro,p.representante_legal,g.comprobante,g.num_serie,fpg.fecha_actual,g.total_compra,
        pc.monto_credito,pp.valor_pagado,pp.saldo_factura,pp.fecha_actual,pp.id_cuentas_pagar,g.id_gastos
    
        )as cuentas_gastos
        where rn =1
        and saldo_factura>0 
        )
   
        order by comprobante
    ";
    //var_dump($sql);
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows;
}

function obtenerCPExternas($idproveedor)
{
    global $query_fecha, $query_punto,  $id_usuario_cp;
    $sqlext = "SELECT DISTINCT 
        ON (cp.comprobante) cp.comprobante, cp.fecha_actual, descripcion, 
            cp.num_factura, total, (total::numeric - saldo::numeric) as abonos, saldo, pp.fecha_actual as fecha_pago,
            cp.fecha_emicion, cp.fecha_vencimiento, tc.descripcion abreviatura,
            (cp.fecha_vencimiento::date-date(now())) vence
            FROM tipo_comprobante tc, c_pagarexternas cp
            LEFT JOIN pagos_pagar pp USING (num_factura)
            WHERE cp.tipo_documento=tc.id_tipo_comprobante 
            AND cp.estado='Activo'
            AND cp.id_proveedor='$idproveedor'
            AND cp.fecha_actual $query_fecha '$_GET[fin]' 
            $query_punto 
            $id_usuario_cp
            ORDER BY cp.comprobante asc, fecha_pago desc;";

    $res = pg_query($sqlext);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows;
}

function obtenerCpIternasExternas($idproveedor)
{
    global $query_punto, $id_usuario_cp, $query_punto_fv, $id_usuario_fv;
    $sql = "
    (
        select*from( 
        select  
        g.comprobante,
        row_number() over(partition by g.id_factura_compra order by pp.id_cuentas_pagar DESC) rn, 
        g.num_serie as num_factura, 
        fpg.fecha_actual,
        pc.monto_credito total, 
        round(coalesce(sum(pp.valor_pagado)over(partition by g.id_factura_compra),0),2)abonos, 
        round(coalesce(pp.saldo_factura,pc.monto_credito,0),2)saldo, 
        pp.fecha_actual fecha_pago, 
        'C'::text as descripcion,
        'I'::text tipo,
        g.fecha_emision,
        pc.tipo_documento
        from factura_compra g inner join formas_pago_mixto_c fpg 
        on g.id_factura_compra=fpg.id_factura_compra 
        inner join proveedores p 
        on p.id_proveedor=g.id_proveedor 
        left join pagos_compra pc 
        on pc.id_factura_compra=g.id_factura_compra 
        and pc.comprao_gasto='C' 
        left JOIN retencion_fuente_factura_compra rfc 
        ON pc.id_factura_compra=rfc.id_factura 
        and rfc.estado <> 'g' and rfc.id_gastos=1 
        LEFT JOIN detallecomprobanteretencion drv1 
        on drv1.id_retencion_fuente_factura_compra=rfc.id_retencion_fuente_factura_compra 
        and drv1.id_trete=1 
        LEFT JOIN detallecomprobanteretencion drv2 
        on drv2.id_retencion_fuente_factura_compra=rfc.id_retencion_fuente_factura_compra 
        and drv2.id_trete=2 
        left join pagos_pagar pp 
        on pp.id_factura_compra=g.id_factura_compra 
        and pp.comprao_gasto='C'
        where fpg.forma_pago='CREDITO' and g.estado='Activo' and g.id_proveedor='$idproveedor' 
        and pc.fecha_credito BETWEEN '$_GET[inicio]' AND '$_GET[fin]' 
        $query_punto_fv $id_usuario_fv
        GROUP BY p.identificacion_pro,p.empresa_pro,p.representante_legal,g.comprobante,g.num_serie,fpg.fecha_actual,
        g.total_compra, pc.monto_credito,pp.valor_pagado,pp.saldo_factura,pp.fecha_actual,pp.id_cuentas_pagar,g.id_factura_compra,
        pc.tipo_documento )
        as cuentas_compras where rn =1 and saldo>0 ) 
        union all (
        select*from( select 
        g.comprobante::text,
        row_number() over(partition by g.id_gastos order by pp.id_cuentas_pagar DESC) rn,  
        g.num_factura, 
        fpg.fecha_actual,  
        pc.monto_credito total, 
        round(coalesce(sum(pp.valor_pagado)over(partition by g.id_gastos),0),2)abonos, 
        round(coalesce(pp.saldo_factura,pc.monto_credito,0),2)saldo, 
        pp.fecha_actual, 'G'::text as descripcion,
        'I'::text tipo,
        g.fecha_emision,
        pc.tipo_documento
        from gastos g inner join formas_pago_mixto_g fpg 
        on g.id_gastos=fpg.id_gastos inner join proveedores p on p.id_proveedor=g.id_proveedor 
        left join pagos_compra pc on pc.id_factura_compra=g.id_gastos and pc.comprao_gasto='G' 
        left JOIN retencion_fuente_factura_compra rfc ON pc.id_factura_compra=rfc.id_factura and rfc.estado <> 'g' and rfc.id_gastos=10 
        LEFT JOIN detallecomprobanteretencion drv1 
        on drv1.id_retencion_fuente_factura_compra=rfc.id_retencion_fuente_factura_compra 
        and drv1.id_trete=1 LEFT JOIN detallecomprobanteretencion drv2 
        on drv2.id_retencion_fuente_factura_compra=rfc.id_retencion_fuente_factura_compra and drv2.id_trete=2 
        left join pagos_pagar pp on pp.id_factura_compra=g.id_gastos and pp.comprao_gasto='G' 
        where fpg.forma_pago='CREDITO' and g.estado='Activo' and g.id_proveedor='$idproveedor' 
        and pc.fecha_credito BETWEEN '$_GET[inicio]' AND '$_GET[fin]' 
        $query_punto_fv $id_usuario_fv
        GROUP BY p.identificacion_pro,p.empresa_pro,p.representante_legal,g.comprobante,g.num_serie,fpg.fecha_actual,
        g.total_compra, pc.monto_credito,pp.valor_pagado,pp.saldo_factura,pp.fecha_actual,pp.id_cuentas_pagar,g.id_gastos, pc.tipo_documento )
        as cuentas_gastos where rn =1 and saldo>0)
        union all(
        SELECT DISTINCT ON (cp.comprobante) cp.comprobante,0 rn,
        cp.num_factura,cp.fecha_actual::date,total::numeric, 
        (total::numeric - saldo::numeric) as abonos, 
        saldo::numeric, pp.fecha_actual as fecha_pago,descripcion,
        'E'::text tipo,
        fecha_emicion::date,
        tc.descripcion abreviatura
        FROM tipo_comprobante tc, c_pagarexternas cp 
        LEFT JOIN pagos_pagar pp USING (num_factura) 
        WHERE cp.tipo_documento=tc.id_tipo_comprobante 
        AND cp.estado='Activo' AND cp.id_proveedor='$idproveedor' 
        AND cp.fecha_actual BETWEEN '$_GET[inicio]' AND '$_GET[fin]' 
        $query_punto 
        $id_usuario_cp
        ORDER BY cp.comprobante asc, fecha_pago desc
        )
        order by fecha_emision asc
    ";

    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows;
}
