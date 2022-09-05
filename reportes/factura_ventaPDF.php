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
    $pdf = new PDF('P','mm',array(195,220));
    //$pdf = new PDF('P','mm','a5');
    $pdf->AddPage();
    $pdf->SetMargins(0,0,0,0);
    $pdf->AliasNbPages();
    $pdf->AddFont('Amble-Regular','','Amble-Regular.php');
    $pdf->SetFont('Amble-Regular','',12);       
    $pdf->SetFont('Arial','B',9);   
    $pdf->SetX(5);    
    $pdf->SetFont('Amble-Regular','',9);     

    $sql = pg_query("select id_factura_venta, num_factura,fecha_actual, tarifa0,tarifa12,iva_venta,descuento_venta,total_venta,clientes.id_cliente,identificacion,nombres_cli,direccion_cli,telefono,factura_venta.estado from factura_venta,clientes where id_factura_venta = '".$_GET['id']."' and factura_venta.id_cliente = clientes.id_cliente");
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
    }        
    /////////header   
    $pdf->SetFont('Arial','B',12);        
            /////////medio
    $pdf->SetFont('Amble-Regular','',12); 
//           $pdf->Text(50,38,utf8_decode(''."Nombre:"),0,'C', 0);
    $pdf->Text(25, 25, maxCaracter(utf8_decode($cliente),80),1,0, 'L',0);
    
//    $pdf->Text(50,44,utf8_decode(''."Fecha:"),0,'C', 0); 
    $pdf->Text(25, 33, maxCaracter(utf8_decode($fecha),20),1,0, 'L',0);
//     $pdf->Text(50,50,utf8_decode(''."Dirección:"),0,'C', 0);
    $pdf->Text(25, 38, maxCaracter(utf8_decode($direccion),35),1,0, 'L',0);
//    $pdf->Text(130,44,utf8_decode(''."CI/RUC:"),0,'C', 0); 
    $pdf->Text(110, 30, maxCaracter(utf8_decode($ci_ruc),20),1,0, 'L',0);
//     $pdf->Text(130,50,utf8_decode(''."Teléfono:"),0,'C', 0); 
    $pdf->Text(110, 36, maxCaracter(utf8_decode($telefono),20),1,0, 'L',0);
        
    if($estado == 'Pasivo') {        
        $pdf->SetTextColor(249,33,33);
        $pdf->RotatedImage('../images/circle.png', 110, 42, 30, 10, 45);        
        $pdf->RotatedText(120,41, 'ANULADO!', 45);        

        $pdf->RotatedImage('../images/circle.png', 260, 42, 30, 10, 45);
        $pdf->RotatedText(269,41, 'ANULADO!', 45);        
    }
    ////////detalles

    $sql = pg_query("select cantidad,articulo,precio_venta,total_venta from  detalle_factura_venta,productos where id_factura_venta = '".$_GET['id']."' and detalle_factura_venta.cod_productos = productos.cod_productos and productos.incluye_iva= 'Si'");
    $yy = 55;
    $calculoIVA=pg_query("select valor from parametros where descripcion='IVA'");
    while($rowi=pg_fetch_row($calculoIVA)){
        $iva_base = $rowi[0];   
    } 
    $iva_base=($iva_base/100)+1;    
    $pdf->SetTextColor(0,0,0);
    while($row = pg_fetch_row($sql)){
        $total_si = 0;
        $total_sit = 0;
        $total_si = $row[3] / $iva_base;
        $total_sit = $total_si / $row[0];
        $total_si = truncateFloat($total_si,4);
        $total_sit = truncateFloat($total_sit,4);

        $pdf->Text(17, $yy, maxCaracter(utf8_decode($row[0]),3),0,1, 'L',0);            
        
        $array = ceil_caracter($row[1],35);
        if(sizeof($array) > 1){
            $zz = $yy;
            for($i = 0; $i < sizeof($array); $i++){
                $pdf->Text(26, $zz, utf8_decode($array[$i]),0,0, 'J',0);                               
                        $zz = $zz + 3;
            }
            $yy = $yy + 4;
        }else{
            $pdf->Text(26, $yy, maxCaracter(utf8_decode($row[1]),30),0,0, 'L',0);                           
                }                            

        $pdf->Text(110, $yy, maxCaracter(number_format($total_sit,2,',','.'),6),0,0, 'L',0);            
        
       // $pdf->Text(128, $yy, maxCaracter(number_format($total_si,2,',','.'),6),0,0, 'L',0); 
		  $pdf->Text(123, $yy, maxCaracter(($total_si),6),0,0, 'L',0); 
		
            $yy = $yy + 4;    
///////////////////////////////////////  
    }

    $sql = pg_query("select cantidad,articulo,precio_venta,total_venta from  detalle_factura_venta,productos where id_factura_venta = '".$_GET['id']."' and detalle_factura_venta.cod_productos = productos.cod_productos and productos.incluye_iva= 'No'");    
    $pdf->SetTextColor(0,0,0);
    while($row = pg_fetch_row($sql)){
        $temp_1 =  number_format($row[3],2,',','.');    
        
//         $pdf->Text(50,60,utf8_decode(''."CANTIDAD"),0,'C', 0);
        $pdf->Text(17, $yy, maxCaracter(utf8_decode($row[0]),3),0,1, 'L',0);                                                    
        
        $array = ceil_caracter($row[1],35);
        if(sizeof($array) > 1){
            $zz = $yy;
            for($i = 0; $i < sizeof($array); $i++){
                $pdf->Text(20, $zz, utf8_decode($array[$i]),0,0, 'J',0);                               
                        $zz = $zz + 3;
            }
            $yy = $yy + 4;
        } else {
//             $pdf->Text(85,60,utf8_decode(''."DESCRIPCION"),0,'C', 0);
            $pdf->Text(26, $yy, maxCaracter(utf8_decode($row[1]),25),0,0, 'L',0);                           
        }    
//        $pdf->Text(140,60,utf8_decode(''."V.UNITARIO"),0,'C', 0);
        $pdf->Text(110, $yy, maxCaracter(utf8_decode($row[2]),6),0,0, 'L',0);    
           
        
//        $pdf->Text(178,60,utf8_decode(''."V.TOTAL"),0,'C', 0);
        $pdf->Text(123, $yy, maxCaracter($temp_1,6),0,0, 'L',0);                                    
        $yy = $yy + 4;                                                
        
    }
    /////////pie        

    $subtotal = truncateFloat($iva12,2);
    $descuento_venta = truncateFloat($descuento_venta,2);
    $iva_venta = round($iva_venta,2);
    $iva0 = truncateFloat($iva0,2);
   $total_venta = round($total_venta,2);

   
 $result1 = substr("$total_venta", -1, 1);
 $result2 = substr("$total_venta", -2, 1);
 $result3 = substr("$total_venta", -3, 1);
 $result4 = substr("$total_venta", -4, 1);
 $result5 = substr("$total_venta", -5, 1);


 
if($result1=="."||$result2=="."||$result3=="."||$result4=="."||$result5=="."){
    $pdf->Text(125, 162, maxCaracter($total_venta,10),0,1, 'L',0);   
    
}
else{
    
     $total_ventacero=$total_venta.".00";
      $pdf->Text(125, 162, maxCaracter($total_ventacero,10),0,1, 'L',0); 
    
}
  

    $pdf->Text(125, 145, maxCaracter($subtotal,6),0,1, 'L',0);

	
    $pdf->Text(125, 150, maxCaracter($iva0,6),0,1, 'L',0);     
    $pdf->Text(125, 157, maxCaracter($iva_venta,6),0,1, 'L',0);    
//    $pdf->Text(180, 192, maxCaracter($descuento_venta,6),0,1, 'L',0);    
    //$pdf->Text(127, 162, maxCaracter($total_venta,10),0,1, 'L',0);    
    
    $fh = fopen('C:\facturas\prueba.pdf', 'a');
    fclose($fh);
    unlink('C:\facturas\prueba.pdf');
    $pdf->Output("prueba.pdf",'D');
  

   
?>
 