<?php
    require('../fpdf/fpdf.php');
    include '../procesos/base.php';
    include '../procesos/funciones.php';
    conectarse();    
    date_default_timezone_set('America/Guayaquil'); 
    session_start()   ;
    class PDF extends FPDF
    {   
        var $widths;
        var $aligns;
        function SetWidths($w){            
            $this->widths=$w;
        }                       
        function Header(){             
            $this->AddFont('Amble-Regular','','Amble-Regular.php');
            $this->SetFont('Amble-Regular','',10);        
            $fecha = date('Y-m-d', time());
            $this->SetX(1);
            $this->SetY(1);
            $this->Cell(20, 5, $fecha, 0,0, 'C', 0);                         
            $this->Cell(170, 5, "CLIENTE", 0,1, 'R', 0);      
            $this->SetFont('Arial','B',16);                                                    
            $this->Cell(190, 8, "EMPRESA: ".$_SESSION['nombre_empresa'], 0,1, 'C',0);                                
            $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"],5,8,45,14);
            $this->SetFont('Amble-Regular','',10);        
            $this->Cell(190, 5, "PROPIETARIO: ".utf8_decode($_SESSION['propietario']),0,1, 'C',0);                                
            $this->Cell(80, 5, "TEL.: ".utf8_decode($_SESSION['telefono']),0,0, 'R',0);                                
            $this->Cell(80, 5, "CEL.: ".utf8_decode($_SESSION['celular']),0,1, 'C',0);                                
            $this->Cell(180, 5, "DIR.: ".utf8_decode($_SESSION['direccion']),0,1, 'C',0);                                
            $this->Cell(180, 5, "SLOGAN.: ".utf8_decode($_SESSION['slogan']),0,1, 'C',0);                                
            $this->Cell(180, 5, utf8_decode( $_SESSION['pais_ciudad']),0,1, 'C',0);                                                                                        
            $this->SetDrawColor(0,0,0);
            $this->SetLineWidth(0.4);

            $this->SetFillColor(120,120,120);
            $this->Line(1,78,210,78);            
            $this->Line(1,45,210,45);            
            $this->SetFont('Arial','B',12);                                                                
            $this->Cell(190, 5, utf8_decode("RESERVACIÓN"),0,1, 'C',0);                                                                                                                
            $this->SetFont('Amble-Regular','',10);        
            $this->Ln(2);
            $this->SetFillColor(255,255,225);
            $sql=pg_query("select id_reservacion,comprobante,fecha_actual,hora_actual, nombres_cli, telefono, celular, direccion_cli from reservaciones, clientes where reservaciones.id_cliente=clientes.id_cliente and id_reservacion='$_GET[id]'");    
            $this->SetLineWidth(0.2);
            while($row=pg_fetch_row($sql)){                          
                $this->SetX(1);                                  
                $this->Cell(85, 6, utf8_decode('COMPROBANTE: RES-'.$row[1]),0,0, 'L',1);                                
                $this->Cell(120, 6, utf8_decode('FECHA: '.$row[2]),0,1, 'L',1);                
                $this->SetX(1);                                  
                $this->Cell(85, 6, utf8_decode('HORA: '.$row[3]),0,0, 'L',1);                       
                $this->SetX(1);                               
                $this->Cell(205, 6, utf8_decode('CLIENTE: '.$row[4]),0,1, 'L',1);                
                $this->SetX(1);                                  
                $this->Cell(85, 6, utf8_decode('TELEFONO '.$row[5]),0,0, 'L',1);                                
                $this->Cell(120, 6, utf8_decode('CELULAR: '.$row[6]),0,1, 'L',1);           
                $this->SetX(1);                                                
                $this->Cell(205, 6, utf8_decode('DIRECCION: '.$row[7]),0,1, 'L',1);   
                $this->SetX(1);                                   
                $this->Cell(205, 6, utf8_decode(''),0,1, 'L',1);                
                
                
            }            
            $this->Ln(5);                        
            $this->SetX(1);
            $this->SetFont('Amble-Regular','',10);        
            $this->Cell(35, 5, utf8_decode("Cantidad"),1,0, 'C',0);
            $this->Cell(110, 5, utf8_decode("Descripción"),1,0, 'C',0);
            $this->Cell(30, 5, utf8_decode("V. Unitario"),1,0, 'C',0);        
            $this->Cell(30, 5, utf8_decode("V. Total"),1,0, 'C',0);                
            $this->Ln(5);
        }
        function Footer(){            
            $this->SetY(-15);            
            $this->SetFont('Arial','I',8);            
            $this->Cell(0,10,'Pag. '.$this->PageNo().'/{nb}',0,0,'C');
        }               
    }
    $pdf = new PDF('P','mm','a4');
    $pdf->AddPage();
    $pdf->SetMargins(0,0,0,0);
    $pdf->AliasNbPages();
    $pdf->AddFont('Amble-Regular','','Amble-Regular.php');
    $pdf->SetFont('Amble-Regular','',10);       
    $pdf->SetFont('Arial','B',9);   
    $pdf->SetX(5);    
    $pdf->SetFont('Amble-Regular','',9); 
    $total = 0;      
    $calculoIVA=pg_query("select valor from parametros where descripcion='IVA'");
    while($rowi=pg_fetch_row($calculoIVA)){
        $iva_base = $rowi[0];   
    } 
    $iva_base=($iva_base/100)+1;
    $sql=pg_query("select detalle_reservacion.cantidad,productos.articulo,detalle_reservacion.precio_venta,detalle_reservacion.total_venta from reservaciones,detalle_reservacion,productos where reservaciones.id_reservacion=detalle_reservacion.id_reservacion and detalle_reservacion.cod_productos=productos.cod_productos and detalle_reservacion.id_reservacion='$_GET[id]' and productos.incluye_iva = 'No'");   
    while($row=pg_fetch_row($sql)){                
        $pdf->SetX(1);                  
        $pdf->Cell(35, 5, maxCaracter(utf8_decode($row[0]),20),0,0, 'C',0);
        $pdf->Cell(110, 5, maxCaracter(utf8_decode($row[1]),80),0,0, 'L',0);
        $pdf->Cell(30, 5, maxCaracter(utf8_decode($row[2]),20),0,0, 'C',0);        
        $pdf->Cell(30, 5, maxCaracter(utf8_decode($row[3]),20),0,0, 'C',0);                                     
        $pdf->Ln(5);                                                                
    }
    $sql=pg_query("select detalle_reservacion.cantidad,productos.articulo,detalle_reservacion.precio_venta,detalle_reservacion.total_venta from reservaciones,detalle_reservacion,productos where reservaciones.id_reservacion=detalle_reservacion.id_reservacion and detalle_reservacion.cod_productos=productos.cod_productos and detalle_reservacion.id_reservacion='$_GET[id]' and productos.incluye_iva = 'Si'");   
    while($row=pg_fetch_row($sql)){                
        $total_si = 0;
        $total_sit = 0;
        $total_si = $row[3] / $iva_base;
        $total_sit = $total_si / $row[0];

        $total_si = truncateFloat($total_si,2);
        $total_sit = truncateFloat($total_sit,2);
        $pdf->SetX(1);                  
        $pdf->Cell(35, 5, maxCaracter(utf8_decode($row[0]),20),0,0, 'C',0);
        $pdf->Cell(110, 5, maxCaracter(utf8_decode($row[1]),80),0,0, 'L',0);
        $pdf->Cell(30, 5, maxCaracter(utf8_decode($total_sit),20),0,0, 'C',0);        
        $pdf->Cell(30, 5, maxCaracter(utf8_decode($total_si),20),0,0, 'C',0);                                     
        $pdf->Ln(5);                                                                
    }
    $pdf->SetX(1);                  
    $pdf->Ln(5);   
    $sql=pg_query("select reservaciones.tarifa0,reservaciones.tarifa12,reservaciones.iva,reservaciones.total from reservaciones,detalle_reservacion,productos where reservaciones.id_reservacion=detalle_reservacion.id_reservacion and detalle_reservacion.cod_productos=productos.cod_productos and detalle_reservacion.id_reservacion='$_GET[id]' LIMIT 1");    
    while($row=pg_fetch_row($sql)){
        $pdf->Cell(173, 6, utf8_decode("Tarifa 0"),0,0, 'R',0);
        $pdf->Cell(35, 6, number_format(round($row[0],2),2,'.',''),0,1, 'C',0);
        $pdf->Cell(173, 6, utf8_decode("Tarifa IVA"),0,0, 'R',0);
        $pdf->Cell(35, 6, number_format(round($row[1],2),2,'.',''),0,1, 'C',0);
        $pdf->Cell(173, 6, utf8_decode("Iva ...%"),0,0, 'R',0);
        $pdf->Cell(35, 6, number_format(round($row[2],2),2,'.',''),0,1, 'C',0);
        $pdf->Cell(173, 6, utf8_decode("Total"),0,0, 'R',0);
        $pdf->Cell(35, 6, number_format(round($row[3],2),2,'.',''),0,1, 'C',0);
    }
    $pdf->Output();
?>
