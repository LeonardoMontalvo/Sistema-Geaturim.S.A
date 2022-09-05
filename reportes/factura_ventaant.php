<?php
    //require('../fpdf/fpdf.php');
    include '../fpdf/rotation.php';
    include '../procesos/base.php';
    include '../procesos/funciones.php';

    conectarse();    
    date_default_timezone_set('America/Guayaquil'); 
    session_start()   ;
    class PDF extends PDF_Rotate {   
        var $widths;
        var $aligns;
        function SetWidths($w) {            
            $this->widths=$w;
        }    

        function RotatedText($x, $y, $txt, $angle) {
            //Text rotated around its origin
            $this->Rotate($angle, $x, $y);
            $this->Text($x, $y, $txt);
            $this->Rotate(0);
        }

        function RotatedImage($file, $x, $y, $w, $h, $angle) {
            //Image rotated around its upper-left corner
            $this->Rotate($angle, $x, $y);
            $this->Image($file, $x, $y, $w, $h);
            $this->Rotate(0);
        }                      
    }
    $pdf = new PDF('P','mm',array(240,350));
    //$pdf = new PDF('P','mm','a5');
    $pdf->AddPage();
    $pdf->SetMargins(0,0,0,0);
    $pdf->AliasNbPages();
    $pdf->AddFont('Amble-Regular','','Amble-Regular.php');
    $pdf->SetFont('Amble-Regular','',13);       
    $pdf->SetFont('Arial','B',13);   
    $pdf->SetX(5);    
    $pdf->SetFont('Amble-Regular','',11);     

    $sql = pg_query("select id_factura_venta, num_factura,fecha_actual, tarifa0,tarifa12,iva_venta,descuento_venta,total_venta,clientes.id_cliente,identificacion,nombres_cli,direccion_cli,telefono,factura_venta.estado,celular from factura_venta,clientes where id_factura_venta = '".$_GET['id']."' and factura_venta.id_cliente = clientes.id_cliente");
    while($row = pg_fetch_row($sql)){
        $id_cliente = $row[8];
        $cliente = $row[10];
        $ci_ruc = $row[9];
        $direccion = $row[11];
        $telefono = $row[12];
        $fecha = $row[2];
        $nro_fac = substr($row[1],8);
        $iva0 = $row[3];
        $iva12 = $row[4];
        $iva_venta = $row[5];
        $descuento_venta = $row[6];
        $total_venta = $row[7];
        $estado = $row[13];
        
        $telefono = $row[12];
        
        if($telefono=="")
        {
             $tel = $row[14];
        }
        else
        {
            $tel = $row[12];
        }
    }        
    /////////header   
    $pdf->SetFont('Arial','B',13);        
            /////////medio
    $pdf->SetFont('Amble-Regular','',13); 
    
    //$pdf->Text(20,75,utf8_decode(''."CLIENTE:"),0,'C', 0);
    $pdf->Text(45, 70, maxCaracter(utf8_decode($cliente),80),0,0, 'L',0);
	
   // $pdf->Text(20,65,utf8_decode(''."FECHA:"),0,'C', 0);
   // $pdf->Text(54,70,utf8_decode(''."IBARRA,"),0,'C', 0);   
    $pdf->Text(45, 59,maxCaracter(utf8_decode($fecha),20),0,0, 'L',0);
	
    // $pdf->Text(20,85,utf8_decode(''."DIRECCION:"),0,'C', 0);
    $pdf->Text(50, 82, maxCaracter(utf8_decode($direccion),35),0,0, 'L',0);
	
    //$pdf->Text(150,65,utf8_decode(''."C.L. RUC:"),0,'C', 0); 
    $pdf->Text(170, 70, maxCaracter(utf8_decode($ci_ruc),20),0,0, 'L',0);
	
    // $pdf->Text(150,75,utf8_decode(''."TELF:"),0,'C', 0); 
    $pdf->Text(165, 59, maxCaracter(utf8_decode($tel),20),0,0, 'L',0);
        
    if($estado == 'Pasivo') {        
        $pdf->SetTextColor(249,33,33);
        $pdf->RotatedImage('../images/circle.png', 110, 42, 30, 10, 45);        
        $pdf->RotatedText(120,41, 'ANULADO!', 45);        

        $pdf->RotatedImage('../images/circle.png', 260, 42, 30, 10, 45);
        $pdf->RotatedText(269,41, 'ANULADO!', 45);        
    }
    ////////detalles

    $sql = pg_query("select codigo,cantidad,articulo,precio_venta,total_venta from  detalle_factura_venta,productos where id_factura_venta = '".$_GET['id']."' and detalle_factura_venta.cod_productos = productos.cod_productos and productos.incluye_iva= 'Si'");
  
    $calculoIVA=pg_query("select valor from parametros where descripcion='IVA'");
 
    while($rowi=pg_fetch_row($calculoIVA)){
        $iva_base = $rowi[0];   
    } 
    $iva_base=($iva_base/100)+1;    
    $pdf->SetTextColor(0,0,0);
     $yy =107;

    while($row = pg_fetch_row($sql)){
             $pdf->SetX(20); 


$pdf->SetFillColor(255,255,255);
  
$pdf->SetFont('Arial','',11);
        $total_si = 0;
        $total_sit = 0;
        $total_si = $row[4] / $iva_base;
        $total_sit = $total_si / $row[1];
        //$total_si = truncateFloat($total_si,2);
        //$total_sit = truncateFloat($total_sit,2);
              $pdf->SetXY(15,$yy); 
        //$pdf->Cell(22,15, (utf8_decode($row[0])),0,0, 'C',0);  
        $pdf->Cell(15, 15, maxCaracter(utf8_decode($row[1]),3),0,0, 'C',0);            
        
        $array = ceil_caracter($row[2],35);
        if(sizeof($array) > 1){
            $zz = $yy;
            for($i = 0; $i < sizeof($array); $i++){
                $pdf->Cell(120, $zz, utf8_decode($array[$i]),0,0, 'C',0);  
              
                        $zz = $zz + 3;
            }
            $yy = $yy + 6;
        }else{
        
              $pdf->Cell(70, 15, maxCaracter(utf8_decode($row[2]),30),0,0, 'C',0);                           
                }                            

        $pdf->Cell(115, 15, maxCaracter(number_format($total_sit,2,'.',''),10),0,0, 'C',0);            
        
        $pdf->Cell(-35, 15, maxCaracter(number_format($total_si,2,'.',''),10),0,0, 'C',0);                                    
          
///////////////////////////////////////  
                  $yy = $yy + 18;
    $pdf->SetXY(15,$yy); 

    }
        

    $sql = pg_query("select codigo,cantidad,articulo,precio_venta,total_venta from  detalle_factura_venta,productos where id_factura_venta = '".$_GET['id']."' and detalle_factura_venta.cod_productos = productos.cod_productos and productos.incluye_iva= 'No'");    
    $pdf->SetTextColor(0,0,0);
         $yy = $yy + 0;
    $pdf->SetXY(15,$yy); 
    while($row = pg_fetch_row($sql)){
        $temp_1 =  number_format($row[4],2,',','.');    
        
        
      $pdf->SetX(15); 


$pdf->SetFillColor(255,255,255);
 
$pdf->SetFont('Arial','',13);


        
        
//        $pdf->Text(20,70,utf8_decode(''."CÓDIGO"),0,'C', 0);
//        $pdf->Cell(22, 6, (utf8_decode($row[0])),0,0, 'C',0);
      
        
        
//         $pdf->Text(40,70,utf8_decode(''."CANTIDAD"),0,'C', 0);
        $pdf->Cell(12, 6, maxCaracter(utf8_decode($row[1]),3),0,0, 'C',0);                                                    
       
        $array = ceil_caracter($row[1],50);
        if(sizeof($array) > 1){
            $zz = $yy;
                  $pdf->SetX(15); 


$pdf->SetFillColor(255,255,255);
 
$pdf->SetFont('Arial','',13);
            for($i = 0; $i < sizeof($array); $i++){
                $pdf->Cell(25, $zz, utf8_decode($array[$i]),1,0, 'C',0);                               
                        $zz = $zz + 3;
            }
            $yy = $yy + 4;
           
        } else {
//             $pdf->Text(85,70,utf8_decode(''."DESCRIPCIÓN"),0,'C', 0);
            $pdf->Cell(120, 6, maxCaracter(utf8_decode($row[2]),30),0,0, 'C',0);                           
        }    
//        $pdf->Text(140,70,utf8_decode(''."P/UNT."),0,'C', 0);
        $pdf->Cell(70, 6, maxCaracter(utf8_decode($row[3]),6),0,0, 'C',0);    
           
        
//        $pdf->Text(170,70,utf8_decode(''."P.TOTAL"),0,'C', 0);
        $pdf->Cell(25, 6, maxCaracter($temp_1,8),0,0, 'C',0);                                    
       
        
 $pdf->Ln(10);
    }
        


$pdf->Ln(60);
$pdf->SetXY(15,80); 
    $pdf->SetFillColor(255,255,255);
 
$pdf->SetFont('Arial','',13);

//$pdf->Cell(22,6,'CODIGO',0,0,'C',1);
//$pdf->Cell(12,6,'CANT',0,0,'C',1);
//$pdf->Cell(120,6,'DESCRIPCION',0,0,'C',1);
 
//$pdf->Cell(27,6,'P/UNT.',0,0,'C',1);
//$pdf->Cell(32,6,'P.TOTAL',0,0,'C',1);
     
$pdf->Ln(180);
     $pdf->SetX(220); 
    $subtotal = $iva12;
    $descuento_venta = truncateFloat($descuento_venta,2);
//    $iva_venta = truncateFloat($iva_venta,2);
     $iva_venta = round($iva_venta,2);
//    $iva0 = truncateFloat($iva0,2);
//    $total_venta = truncateFloat($total_venta,2);
     $total_venta = round($total_venta,2);

    //$pdf->Cell(23,5,utf8_decode(''."SUBTOTAL"),0,0,'C', 0);
    $pdf->Text(222, 265, maxCaracter(number_format($subtotal,2,'.',''),10),0,0, 'L',0);  
  
    
    //$pdf->Cell(23,5,utf8_decode(''."IVA 0%"),0,0,'C', 0);
    $pdf->Text(222, 280, maxCaracter(number_format($iva0,2,'.',''),10),0,0, 'L',0);   
     
   // $pdf->Cell(23,5,utf8_decode(''."IVA 12%"),0,0,'C', 0);
    $pdf->Text(222, 305, maxCaracter(number_format($iva_venta,2,'.',''),10),0,0, 'L',0);    
    $pdf->Text(222, 293, maxCaracter($descuento_venta,6),0,1, 'L',0);  
    
    //$pdf->Cell(23,5,utf8_decode(''."TOTAL:"),0,0,'C', 0);
    $pdf->Text(222, 315, maxCaracter(number_format($total_venta,2,'.',''),10),0,0, 'L',0);    


 
  $pdf->Output();
  

   
?>
 