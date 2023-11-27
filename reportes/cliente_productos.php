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
        $this->AddFont('helvetica', 'B', 'helveticab.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(0);
        $this->SetY(0);
        $this->Cell(105, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(105, 5, "VENTAS", 0, 1, 'C', 0);
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
        $this->Line(0, 25, 210, 25);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 5, utf8_decode("PRODUCTOS POR CLIENTE"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(3);
        $this->SetFillColor(255, 255, 225);
        $this->SetLineWidth(0.2);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

}

$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetTitle('Clientes Productos');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();

$total = 0;
$sub = 0;
$repetido = 0;
$contador = 0;
$pv = 0;
$pc = 0;
$util = 0;
$query_fecha = "";
$id_cliente = "";
$id_cliente_consult = "";
$id_cliente_consult_nv = "";
$id_producto_consult = "";
// RANGO DE FECHAS O FECHA ACTUAL
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}

if ($_GET['idclientes'] != "") {
    $id_cliente = "where id_cliente='$_GET[idclientes]'";
} else {
    $id_cliente = "";
}
if ($_GET['idclientes'] != "") {
    $id_cliente_consult = "and factura_venta.id_cliente='$_GET[idclientes]'";
} else {
    $id_cliente_consult = "";
}
if ($_GET['idclientes'] != "") {
    $id_cliente_consult_nv = "and facturas_novalidas.id_cliente='$_GET[idclientes]'";
} else {
    $id_cliente_consult_nv = "";
}
if ($_GET['idProd'] != "") {
    $id_producto_consult = "and fv.cod_productos='$_GET[idProd]'";
} else {
    $id_producto_consult = "";
}
if ($_GET['idclientes'] != "") {


    $consulta = pg_query("select id_cliente,identificacion,nombres_cli from clientes $id_cliente ");
    while ($row = pg_fetch_row($consulta)) {





        $sql1 = pg_query("select num_factura, factura_venta.fecha_actual, id_factura_venta,usuario.usuario, identificacion,nombres_cli,hora_actual,'VENTAS'
  from factura_venta,usuario ,clientes 
    where factura_venta.fecha_actual $query_fecha '$_GET[fin]' $id_cliente_consult
     and factura_venta.estado='Activo' 
     and factura_venta.id_usuario=usuario.id_usuario
       and factura_venta.id_cliente=clientes.id_cliente
 union   

select comprobante, facturas_novalidas.fecha_actual, id_facturas_novalidas,usuario.usuario, identificacion,nombres_cli,hora_actual,'NV'
 from facturas_novalidas,usuario ,clientes 
 where facturas_novalidas.fecha_actual $query_fecha '$_GET[fin]' $id_cliente_consult_nv
  and facturas_novalidas.estado='Activo' 
  and facturas_novalidas.id_usuario=usuario.id_usuario
  and facturas_novalidas.id_cliente=clientes.id_cliente
");

        if (pg_num_rows($sql1)) {
            while ($row1 = pg_fetch_row($sql1)) {

                $sql2 = pg_query("
               select p.codigo, p.articulo, fv.precio_venta, fv.cantidad from detalle_factura_venta fv,productos p where fv.cod_productos=p.cod_productos and id_factura_venta='$row1[2]' $id_producto_consult      
union
select p.codigo, p.articulo, fv.precio_venta, fv.cantidad 
from detalle_facturas_novalidas fv,productos p 
where fv.cod_productos=p.cod_productos 
and id_facturas_novalidas='$row1[2]'
$id_producto_consult ");


                if (pg_num_rows($sql2)) {
                    $pdf->SetX(1);
//                    $pdf->SetFillColor(216, 216, 231);
//                    $pdf->SetFont('helvetica', 'B', 9);
                    $pdf->SetX(1);
                    $pdf->SetFillColor(216, 216, 231);
                    $pdf->SetFont('helvetica', 'B', 9);
                    $pdf->Cell(35, 6, utf8_decode($row[2]), 1, 0, 'L', true);
                    $pdf->Cell(30, 6, utf8_decode($row[1]), 1, 0, 'L', true);

                    $pdf->Cell(45, 6, utf8_decode("F. NRO.: " . $row1[0]), 1, 0, 'L', true);
                    $pdf->Cell(35, 6, utf8_decode("FECHA: " . $row1[1]), 1, 0, 'L', true);
                    $pdf->Cell(35, 6, utf8_decode("HORA: " . $row1[6]), 1, 0, 'L', true);
                    $pdf->Cell(14, 6, utf8_decode($row1[3]), 1, 0, 'L', true);
                $pdf->Cell(15, 6, utf8_decode($row1[7]), 1, 1, 'L', true);
                    $pdf->SetX(1);
                    $pdf->Cell(34, 6, utf8_decode('Cód. Producto'), 1, 0, 'L', 0);
                    $pdf->Cell(120, 6, utf8_decode('Descripción'), 1, 0, 'L', 0);
                    $pdf->Cell(25, 6, utf8_decode('Cantidad'), 1, 0, 'C', 0);
                    $pdf->Cell(25, 6, utf8_decode('Precio'), 1, 1, 'C', 0);
                    while ($row2 = pg_fetch_row($sql2)) {
                        $pdf->SetX(1);
                        $pdf->SetFont('helvetica', '', 9);
                        $pdf->Cell(34, 6, utf8_decode($row2[0]), 0, 0, 'L', false);
                        $pdf->Cell(120, 6, utf8_decode($row2[1]), 0, 0, 'L', false);
                        $pdf->Cell(25, 6, utf8_decode($row2[3]), 0, 0, 'C', false);
                        $pdf->Cell(25, 6, utf8_decode($row2[2]), 0, 0, 'C', false);
                       
                        $pdf->Ln(4);
                    }
                }
            }
        }
    }

} else {



    $sql1 = pg_query("
        
select num_factura, factura_venta.fecha_actual, id_factura_venta,usuario.usuario, identificacion,nombres_cli,hora_actual,'VENTAS'
 from factura_venta,usuario ,clientes 
 where factura_venta.fecha_actual $query_fecha '$_GET[fin]'
  and factura_venta.estado='Activo' 
  and factura_venta.id_usuario=usuario.id_usuario
  and factura_venta.id_cliente=clientes.id_cliente

union
select comprobante, facturas_novalidas.fecha_actual, id_facturas_novalidas,usuario.usuario, identificacion,nombres_cli,hora_actual,'NV'
 from facturas_novalidas,usuario ,clientes 
 where facturas_novalidas.fecha_actual $query_fecha '$_GET[fin]'
  and facturas_novalidas.estado='Activo' 
  and facturas_novalidas.id_usuario=usuario.id_usuario
  and facturas_novalidas.id_cliente=clientes.id_cliente
    union       
 
  select num_serie, factura_compra.fecha_actual, id_factura_compra,usuario.usuario, identificacion_pro,empresa_pro,hora_actual,'COMPRA'
 from factura_compra,usuario ,proveedores 
 where factura_compra.fecha_emision $query_fecha '$_GET[fin]'
  and factura_compra.estado='Activo' 
  and factura_compra.id_usuario=usuario.id_usuario
  and factura_compra.id_proveedor=proveedores.id_proveedor   
            
           ");

    if (pg_num_rows($sql1)) {
        while ($row1 = pg_fetch_row($sql1)) {

            $sql2 = pg_query("
     select p.codigo, p.articulo, fv.precio_venta, fv.cantidad
     from detalle_factura_venta fv,productos p 
     where fv.cod_productos=p.cod_productos 
     and id_factura_venta='$row1[2]'  
                  $id_producto_consult            

union
select p.codigo, p.articulo, fv.precio_venta, fv.cantidad 
from detalle_facturas_novalidas fv,productos p 
where fv.cod_productos=p.cod_productos 
and id_facturas_novalidas='$row1[2]'
$id_producto_consult 
    
union

select p.codigo, p.articulo, fv.precio_compra, fv.cantidad 
from detalle_factura_compra fv,productos p 
where fv.cod_productos=p.cod_productos 
and id_factura_compra='$row1[2]'
$id_producto_consult 
                  ");
     
echo "Soy una línea.\n"."
     select p.codigo, p.articulo, fv.precio_venta, fv.cantidad
     from detalle_factura_venta fv,productos p 
     where fv.cod_productos=p.cod_productos 
     and id_factura_venta='$row1[2]'  
                  $id_producto_consult            

union
select p.codigo, p.articulo, fv.precio_venta, fv.cantidad 
from detalle_facturas_novalidas fv,productos p 
where fv.cod_productos=p.cod_productos 
and id_facturas_novalidas='$row1[2]'
$id_producto_consult 
    
union

select p.codigo, p.articulo, fv.precio_compra, fv.cantidad 
from detalle_factura_compra fv,productos p 
where fv.cod_productos=p.cod_productos 
and id_factura_compra='$row1[2]'
$id_producto_consult 
                  .\n";

            if (pg_num_rows($sql2)) {
                $pdf->SetX(1);
                $pdf->SetFillColor(216, 216, 231);
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->Cell(35, 6, utf8_decode($row1[5]), 1, 0, 'L', true);
                $pdf->Cell(30, 6, utf8_decode($row1[4]), 1, 0, 'L', true);
                $pdf->Cell(45, 6, utf8_decode("F. NRO.: " . $row1[0]), 1, 0, 'L', true);
                $pdf->Cell(35, 6, utf8_decode("FECHA: " . $row1[1]), 1, 0, 'L', true);
                $pdf->Cell(35, 6, utf8_decode("HORA: " . $row1[6]), 1, 0, 'L', true);
                $pdf->Cell(14, 6, utf8_decode($row1[3]), 1, 0, 'L', true);
                $pdf->Cell(15, 6, utf8_decode($row1[7]), 1, 1, 'L', true);
                $pdf->SetX(1);
                $pdf->Cell(34, 6, utf8_decode('Cód. Producto'), 1, 0, 'L', 0);
                $pdf->Cell(120, 6, utf8_decode('Descripción'), 1, 0, 'L', 0);
                $pdf->Cell(25, 6, utf8_decode('Cantidad'), 1, 0, 'C', 0);
                $pdf->Cell(25, 6, utf8_decode('Precio'), 1, 1, 'C', 0);
                while ($row2 = pg_fetch_row($sql2)) {
                    $pdf->SetX(1);
                    $pdf->SetFont('helvetica', '', 9);
                    
                    $pdf->Cell(34, 6, utf8_decode($row2[0]), 0, 0, 'L', false);
                    $pdf->Cell(120, 6, utf8_decode($row2[1]), 0, 0, 'L', false);
                    $pdf->Cell(25, 6, utf8_decode($row2[3]), 0, 0, 'C', false);
                    $pdf->Cell(25, 6, utf8_decode($row2[2]), 0, 1, 'C', false);
//                   $pdf->Cell(25, 6, utf8_decode($row2[2]), 0, 1, 'C', false);
                    $pdf->Ln(4);
                }
            }
        }
    }
//} 
}

$pdf->Output();
