<?php
  require('../fpdf/fpdf.php');
    include '../procesos/base.php';
    include '../procesos/funciones.php';
    conectarse();    
    date_default_timezone_set('America/Guayaquil'); 
    session_start()   ;
    class PDF extends FPDF{   
        var $widths;
        var $aligns;       
        function SetWidths($w){            
            $this->widths=$w;
        }                                   
    }
    $pdf = new PDF('P','mm','a4');
    $pdf->SetAutoPageBreak(false,0);  
    $fecha = date('Y-m-d', time());
    $pdf->AddPage();
    $pdf->SetMargins(0,0,0,0);
    $pdf->AliasNbPages();
    $pdf->AddFont('Amble-Regular','','Amble-Regular.php');
    $pdf->SetFont('Amble-Regular','',10);       
    $pdf->SetFont('Arial','B',9);   
    $pdf->SetX(5);    
    $pdf->SetFont('Amble-Regular','',9);     
    
    //////////////////////////////////////MITAD HOJA                
    $pdf->SetY(3);
    $pdf->SetX(1);
    $pdf->Cell(20, 5, $fecha, 0,0, 'C', 0);                         
    $pdf->Cell(185, 5, "RESPONSABLE", 0,1, 'R', 0);      
    $pdf->SetFont('Arial','B',16);                                                    
    $pdf->Cell(210, 8, "EMPRESA: ".$_SESSION['nombre_empresa'], 0,1, 'C',0);                                
    $pdf->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"],5,8,45,14);
    $pdf->SetFont('Amble-Regular','',10);        
    $pdf->Cell(210, 5, "PROPIETARIO: ".utf8_decode($_SESSION['propietario']),0,1, 'C',0);                                
    $pdf->Cell(90, 5, "TEL.: ".utf8_decode($_SESSION['telefono']),0,0, 'R',0);                                
    $pdf->Cell(90, 5, "CEL.: ".utf8_decode($_SESSION['celular']),0,1, 'C',0);                                
    $pdf->Cell(210, 5, "DIR.: ".utf8_decode($_SESSION['direccion']),0,1, 'C',0);                                                                                        
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(0.4);
    $consulta=pg_query("select p.articulo, d.fecha_actual, o.cantidad from productos p, recetas r, ordenes_produccion o, desaprobacion_ordenes d where r.cod_productos=p.cod_productos and o.id_receta=CAST(r.id_receta as text) and d.id_orden=o.id_ordenes and o.id_ordenes='$_GET[id]'");       
    $row=pg_fetch_row($consulta);
    $pdf->SetFillColor(120,120,120);
    $pdf->Line(1,42,210,42);          
    $pdf->SetFont('Arial','B',12); 
    $pdf->Cell(210, 5, utf8_decode("ORDEN DE PRODUCCIÓN"),0,1, 'C',0);                                                               
    $pdf->Cell(210, 5, utf8_decode($row[0]),0,1, 'C',0);                                                                                                                
    $pdf->SetFont('Amble-Regular','',10);        
    $pdf->Ln(2);
    $pdf->SetFillColor(255,255,225);                     
    $pdf->SetX(1);
    $pdf->SetFont('Amble-Regular','',10); 
            
    $pdf->Cell(105, 5, utf8_decode("FECHA DESAPROBACIÓN: ".$row[1]),0,1, 'L',1);  
    $cantidad=$row[2];           
    $pdf->Ln(7);                       
    $pdf->SetX(1);
    $pdf->SetFont('Amble-Regular','',10);                   
    $pdf->Ln(5);               

    $sql = "select motivo from desaprobacion_ordenes d, ordenes_produccion o where d.id_orden=o.id_ordenes and o.id_ordenes='".$_GET['id']."'";
    $sql = pg_query($sql);           
    while ($row = pg_fetch_row($sql)) {
        $pdf->Ln(1);
        $pdf->SetX(3);
        $pdf->Cell(30, 5, "MOTIVO: ",0,1, 'L',0);    
        $pdf->SetY(62);
        $pdf->SetX(33);
        $pdf->MultiCell(170, 4, maxCaracter(utf8_decode($row[0]),400),0,'L',0);

        $pdf->SetY(130);
        $pdf->SetX(3);
        $pdf->Cell(100, 5, "__________________________________________",0,0, 'C',0);    
        $pdf->Cell(102, 5, "__________________________________________",0,1, 'C',0);    
        $pdf->SetX(3);
        $pdf->Cell(100, 5, "ENTREGE CONFORME",0,0, 'C',0);    
        $pdf->Cell(102, 5, "RECIBI CONFORME",0,1, 'C',0);    

    }
    //$pdf->Line(1,70,297,70);            
    ///////////////////////////////////////MITAD HOJA
    
    $pdf->SetY(150);
    $pdf->SetX(3);
    $pdf->Cell(20, 5, $fecha, 0,0, 'C', 0);                         
    $pdf->Cell(185, 5, "RESPONSABLE", 0,1, 'R', 0);      
    $pdf->SetFont('Arial','B',16);                                                    
    $pdf->Cell(210, 8, "EMPRESA: ".$_SESSION['empresa'], 0,1, 'C',0);                                
    $pdf->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"],5,155,45,30);
    $pdf->SetFont('Amble-Regular','',10);        
    $pdf->Cell(210, 5, "PROPIETARIO: ".utf8_decode($_SESSION['propietario']),0,1, 'C',0);                                
    $pdf->Cell(90, 5, "TEL.: ".utf8_decode($_SESSION['telefono']),0,0, 'R',0);                                
    $pdf->Cell(90, 5, "CEL.: ".utf8_decode($_SESSION['celular']),0,1, 'C',0);                                
    $pdf->Cell(210, 5, "DIR.: ".utf8_decode($_SESSION['direccion']),0,1, 'C',0);                                                                                        
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(0.4); 
    $consulta=pg_query("select p.articulo, d.fecha_actual, o.cantidad from productos p, recetas r, ordenes_produccion o, desaprobacion_ordenes d where r.cod_productos=p.cod_productos and o.id_receta=CAST(r.id_receta as text) and d.id_orden=o.id_ordenes and o.id_ordenes='$_GET[id]'");       
    $row=pg_fetch_row($consulta);
    $pdf->SetFillColor(120,120,120);
    $pdf->Line(1,190,297,190);         
    $pdf->SetFont('Arial','B',12); 
    $pdf->Cell(210, 5, utf8_decode("ORDEN DE PRODUCCIÓN"),0,1, 'C',0);                                                               
    $pdf->Cell(210, 5, utf8_decode($row[0]),0,1, 'C',0);                                                                                                                
    $pdf->SetFont('Amble-Regular','',10);        
    $pdf->Ln(3); 
    $pdf->Cell(105, 5, utf8_decode("FECHA DESAPROBACIÓN: ".$row[1]),0,1, 'L',0);  
    $cantidad=$row[2];           
    $pdf->Ln(7);                       
    $pdf->SetX(1);
    $pdf->SetFont('Amble-Regular','',10);                   
    $pdf->Ln(5); 

    $sql = "select motivo from desaprobacion_ordenes d, ordenes_produccion o where d.id_orden=o.id_ordenes and o.id_ordenes='".$_GET['id']."'";
    $sql = pg_query($sql);           
     while ($row = pg_fetch_row($sql)) {
        
        $pdf->SetY(210);
        $pdf->SetX(3);
        $pdf->Cell(30, 5, "MOTIVO: ",0,1, 'L',0);    
        $pdf->SetY(211);
        $pdf->SetX(33);
        $pdf->MultiCell(170, 4, maxCaracter(utf8_decode($row[0]),400),0,'L',0);

        $pdf->SetY(280);
        $pdf->SetX(3);
        $pdf->Cell(100, 5, "__________________________________________",0,0, 'C',0);    
        $pdf->Cell(102, 5, "__________________________________________",0,1, 'C',0);    
        $pdf->SetX(3);
        $pdf->Cell(100, 5, "ENTREGE CONFORME",0,0, 'C',0);    
        $pdf->Cell(102, 5, "RECIBI CONFORME",0,1, 'C',0);    

    }            

    $pdf->Output();

?>