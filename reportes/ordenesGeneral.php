<?php
    require('../fpdf/fpdf.php');
    include '../procesos/base.php';
    include '../procesos/funciones.php';
    conectarse();    
    date_default_timezone_set('America/Guayaquil'); 
    session_start()   ;
    class PDF extends FPDF {   
        var $widths;
        var $aligns;       
        function SetWidths($w) {            
            $this->widths=$w;
        }                       
        function Header() {                         
            $this->AddFont('Amble-Regular','','Amble-Regular.php');
            $this->SetFont('Amble-Regular','',10);        
            $fecha = date('Y-m-d', time());
            $this->SetX(1);
            $this->SetY(1);
            $this->Cell(20, 5, $fecha, 0,0, 'C', 0);                         
            $this->Cell(150, 5, "FACTURAS VENTAS", 0,1, 'R', 0);      
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
            $this->Line(1,50,210,50);            
            $this->SetFont('Arial','B',12);                                                                
            $this->Cell(90, 5, utf8_decode($_GET['inicio']),0,0, 'R',0);                                                                                        
            $this->Cell(40, 5, utf8_decode($_GET['fin']),0,1, 'C',0);                                                                                                    
            $this->Cell(190, 5, utf8_decode("RESUMEN DE ORDENES DE PRODUCCION"),0,1, 'C',0);                                                                                                                            
            $this->SetFont('Amble-Regular','',10);        
            $this->Ln(3);
            $this->SetFillColor(255,255,225);            
            $this->SetLineWidth(0.2);                                        
        }
        function Footer() {            
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
    $total=0;
    $sub=0;
    $desc=0;
    $ivaT=0;
    $t0 = 0;
    $pdf->SetX(1); 
    $pdf->Cell(20, 6, utf8_decode('ID'),1,0, 'C',0);
    $pdf->Cell(25, 6, utf8_decode('Fecha'),1,0, 'C',0); 
    $pdf->Cell(25, 6, utf8_decode('Cantidad'),1,0, 'C',0);                                     
    $pdf->Cell(107, 6, utf8_decode('Producto'),1,0, 'C',0);                                     
    $pdf->Cell(30, 6, utf8_decode('Costo'),1,1, 'C',0);    

    $consulta=pg_query("select id_ordenes, id_receta, fecha_actual, cantidad, estado, costo_total from ordenes_produccion where fecha_actual between '$_GET[inicio]' and '$_GET[fin]' order by id_receta asc");
    while($row=pg_fetch_row($consulta)) {  
        $consultapro=pg_query("select cod_productos from recetas where id_receta=$row[1]");
        while ($p=pg_fetch_row($consultapro)) {
            $pro=$p[0];
        }      
        $consulta1=pg_query("select articulo from productos where cod_productos=$pro");                        
        while($row1=pg_fetch_row($consulta1)) { 
            if($row[4] == "Activo") {                                    
                $pdf->SetTextColor(0,0,0);
                $pdf->SetX(1); 
                $pdf->Cell(20, 6, utf8_decode($row[0]),0,0, 'C',0);
                $pdf->Cell(25, 6, utf8_decode($row[2]),0,0, 'C',0); 
                $pdf->Cell(25, 6, utf8_decode($row[3]),0,0, 'C',0);                                     
                $pdf->Cell(107, 6, utf8_decode($row1[0]),0,0, 'C',0);                    
                $pdf->Cell(30, 6, number_format($row[5],2,'.',''),0,0, 'C',0);                                   
                $pdf->Ln(6);              
            } else {
                if($row[4] == "Pasivo") {
                    $pdf->SetTextColor(208,17,52);
                    $pdf->SetX(1); 
                    $pdf->Cell(20, 6, utf8_decode($row[0]),0,0, 'C',0);
                    $pdf->Cell(25, 6, utf8_decode($row[2]),0,0, 'C',0); 
                    $pdf->Cell(25, 6, utf8_decode($row[3]),0,0, 'C',0);                                    
                    $pdf->Cell(107, 6, utf8_decode($row1[0]),0,0, 'C',0);                    
                    $pdf->Cell(30, 6, number_format($row[5],2,'.',''),0,0, 'C',0);                             
                    $pdf->Ln(6);              
                }                
            }              
        }                       
    }  

    $pdf->SetTextColor(0,0,0);          
    $pdf->SetX(1);                                             
    $pdf->Cell(230, 0, utf8_decode(""),1,1, 'R',0);                        
    $pdf->Ln(8);           
    $pdf->Output();
?>
