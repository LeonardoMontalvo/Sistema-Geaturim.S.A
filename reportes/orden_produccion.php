<?php
    require('../fpdf/fpdf.php');
    include '../procesos/base.php';
    include '../procesos/funciones.php';
    conectarse();    
    date_default_timezone_set('America/Guayaquil'); 
    session_start()   ;
    $cantidad=0;
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
            $this->Cell(170, 5, "RESPONSABLE", 0,1, 'R', 0);      
            $this->SetFont('Arial','B',16);                                                    
            $this->Cell(190, 8, "EMPRESA: ".$_SESSION['nombre_empresa'], 0,1, 'C',0);                                
            $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"],5,8,45,15);
            $this->SetFont('Amble-Regular','',10);        
            $this->Cell(190, 5, "PROPIETARIO: ".utf8_decode($_SESSION['propietario']),0,1, 'C',0);                                
            $this->Cell(80, 5, "TEL.: ".utf8_decode($_SESSION['telefono']),0,0, 'R',0);                                
            $this->Cell(80, 5, "CEL.: ".utf8_decode($_SESSION['celular']),0,1, 'C',0);                                
            $this->Cell(180, 5, "DIR.: ".utf8_decode($_SESSION['direccion']),0,1, 'C',0);                                
            $this->Cell(180, 5, "SLOGAN.: ".utf8_decode($_SESSION['slogan']),0,1, 'C',0);                                
            $this->Cell(180, 5, utf8_decode( $_SESSION['pais_ciudad']),0,1, 'C',0);                                                                                        
            $this->SetDrawColor(0,0,0);
            $this->SetLineWidth(0.4);
            $consulta=pg_query("select p.articulo, o.fecha_actual, o.cantidad from productos p, recetas r, ordenes_produccion o where r.cod_productos=p.cod_productos and o.id_receta=CAST(r.id_receta as text) and o.id_ordenes='$_GET[id]'");       
            $row=pg_fetch_row($consulta);
            $this->SetFillColor(120,120,120);
            $this->Line(1,50,210,50);
            $this->Line(1,57,210,57);           
            $this->SetFont('Arial','B',12); 
            $this->Cell(190, 5, utf8_decode("ORDEN DE PRODUCCIÓN"),0,1, 'C',0);                                                               
            $this->Cell(190, 5, utf8_decode($row[0]),0,1, 'C',0);                                                                                                                
            $this->SetFont('Amble-Regular','',10);        
            $this->Ln(2);
            $this->SetFillColor(255,255,225);                     
            $this->SetX(1);
            $this->SetFont('Amble-Regular','',10); 
            
            $this->Cell(105, 5, utf8_decode("FECHA MODIFICACIÓN: ".$row[1]),0,0, 'L',1); 
            $this->Cell(100, 5, utf8_decode("CANTIDAD A PRODUCIR: ".$row[2]),0,0, 'L',1);   
            $cantidad=$row[2];           
            $this->Ln(7);                       
            $this->SetX(1);
            $this->SetFont('Amble-Regular','',10);        
            $this->Cell(35, 5, utf8_decode("Cantidad"),1,0, 'C',0);
            $this->Cell(170, 5, utf8_decode("Descripción"),1,0, 'C',0);             
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
    $sql=pg_query("select d.cantidad, p.articulo, o.cantidad from detalle_receta d, productos p, ordenes_produccion o, recetas r where d.cod_productos=p.cod_productos and o.id_receta=CAST(r.id_receta as text) and r.id_receta=d.id_receta and o.id_ordenes='$_GET[id]'");   
    while($row=pg_fetch_row($sql)){                
        $pdf->SetX(1);                  
        $pdf->Cell(35, 5, maxCaracter(utf8_decode($row[0]*$row[2]),20),0,0, 'C',0);
        $pdf->Cell(170, 5, maxCaracter(utf8_decode($row[1]),80),0,0, 'L',0);                                   
        $pdf->Ln(5);                                                                
    }
    $pdf->SetX(1);                  
    $pdf->Ln(30);   
    $pdf->SetX(3);
    $pdf->Cell(100, 5, "__________________________________________",0,0, 'C',0);    
    $pdf->Cell(102, 5, "__________________________________________",0,1, 'C',0);    
    $pdf->SetX(3);
    $pdf->Cell(100, 5, "ENTREGE CONFORME",0,0, 'C',0);    
    $pdf->Cell(102, 5, "RECIBI CONFORME",0,1, 'C',0); 
    $pdf->Output();
?>
