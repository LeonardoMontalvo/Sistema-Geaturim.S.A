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
    $pdf->SetX(3);
    $pdf->Cell(20, 4, $fecha, 0,0, 'C', 0);                         
    $pdf->Cell(180, 4, "", 0,1, 'R', 0);      
    $pdf->SetFont('Arial','B',14);                                                    
    $pdf->Cell(210, 6, $_SESSION['nombre_empresa'], 0,1, 'C',0);                                
    $pdf->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"],5,8,45,14);
    $pdf->SetFont('Amble-Regular','',10);            
    $pdf->Cell(210, 4, "PROPIETARIO: ".maxCaracter(utf8_decode($_SESSION['propietario']),55),0,1, 'C',0);
    $pdf->Cell(109, 4, "TEL.: ".utf8_decode($_SESSION['telefono']),0,0, 'R',0);                                
    $pdf->Cell(50, 4, "CEL.: ".maxCaracter(utf8_decode($_SESSION['celular']),15),0,0, 'L',0);                                
    $pdf->Cell(45, 4, utf8_decode( $_SESSION['pais_ciudad']),0,1, 'R',0);                                                                                                    
    $pdf->Cell(210, 4, "DIR.: ".maxCaracter(utf8_decode($_SESSION['direccion']),60),0,1, 'C',0);                                
    $pdf->Cell(210, 4, maxCaracter(utf8_decode($_SESSION['slogan']),60),0,1, 'C',0);                                    
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(0.4);            
    $pdf->Line(1,35,297,35);            
    $pdf->SetFont('Arial','B',12);                                                                            
    $pdf->Cell(210, 5, utf8_decode("PAGOS DE RESERVACIÓN"),0,1, 'C',0);                                                                                                                            
    $pdf->SetFont('Amble-Regular','',9);                

    $id=pg_query("select max(id_pago_reservacion) from pago_reservacion where id_reservacion='".$_GET['id']."'");
    $id_pago=pg_fetch_row($id);
    $pago=pg_query("select r.total, p.fecha_actual, p.valor_pago, p.saldo from reservaciones r, pago_reservacion p where r.id_reservacion=p.id_reservacion and p.id_pago_reservacion='".$id_pago[0]."'");
    $row1=pg_fetch_row($pago);
    $sql = "select c.nombres_cli, c.telefono, c.celular, c.direccion_cli from clientes c, reservaciones r where r.id_reservacion='".$_GET['id']."' and r.id_cliente=c.id_cliente";
    $sql1 = pg_query($sql);           
    while ($row = pg_fetch_row($sql1)) {
        $pdf->Ln(1);
        $pdf->SetX(3);
        $pdf->Cell(30, 5, "REGISTRO NRO.: ",0,0, 'L',0);
        $pdf->Cell(90, 5, maxCaracter(utf8_decode("RES-".$_GET['id']),10),0,1, 'L',0);

        $pdf->SetX(3);
        $pdf->Cell(30, 5, "CLIENTE.: ",0,0, 'L',0);
        $pdf->Cell(90, 5, maxCaracter(utf8_decode($row[0]),40),0,1, 'L',0);    

        $pdf->SetX(3);
        $pdf->Cell(30, 5, "TEL.: ",0,0, 'L',0);    
        $pdf->Cell(90, 5, maxCaracter(utf8_decode($row[1]),40),0,1, 'L',0);  

        $pdf->SetX(3);
        $pdf->Cell(30, 5, "CEL.: ",0,0, 'L',0);        
        $pdf->Cell(90, 5, maxCaracter(utf8_decode($row[2]),40),0,1, 'L',0);  
        
        $pdf->SetX(3);    
        $pdf->Cell(30, 5, "DIR..: ",0,0, 'L',0);        
        $pdf->Cell(90, 5, maxCaracter(utf8_decode($row[3]),40),0,1, 'L',0); 

        $pdf->SetX(3);
        $pdf->Cell(50, 5, utf8_decode("VALOR TOTAL RESERVACIÓN: "),0,0, 'L',0);
        $pdf->Cell(90, 5, round($row1[0],2),0,1, 'L',0);

        $pdf->Ln(6);
        $pdf->SetX(25);
        $pdf->Cell(40, 5, "FECHA PAGO",1,0, 'C',0);
        $pdf->Cell(40, 5, "VALOR PAGO",1,0, 'C',0);
        $pdf->Cell(40, 5, "TOTAL ABONADO",1,0, 'C',0);
        $pdf->Cell(40, 5, "SALDO",1,1, 'C',0);
        $pdf->Ln(2); 

        $pago=pg_query("select r.total, p.fecha_actual, p.valor_pago, p.saldo from reservaciones r, pago_reservacion p where r.id_reservacion=p.id_reservacion and r.id_reservacion='".$_GET['id']."'");
        
        while ($row1=pg_fetch_row($pago)) {
            $dato=$row1[0]-$row1[3];
              
            $pdf->SetX(25);
            $pdf->Cell(40, 5, utf8_decode($row1[1]),0,0, 'C',0);
            $pdf->Cell(40, 5, utf8_decode($row1[2]),0,0, 'C',0);
            $pdf->Cell(40, 5, number_format($dato,2,'.',''),0,0, 'C',0);
            $pdf->Cell(40, 5, utf8_decode($row1[3]),0,1, 'C',0);
        }

        $pdf->Ln(30); 
        $pdf->SetX(3);
        $pdf->Cell(202, 5, "__________________________________________",0,1, 'C',0);    
        $pdf->SetX(3);
        $pdf->Cell(202, 5, "RESPONSABLE",0,1, 'C',0);   
        $pdf->SetX(3);
        $pdf->Cell(202, 5, $_SESSION['nombres'],0,1, 'C',0);    

    }        

    $pdf->Output();

?>