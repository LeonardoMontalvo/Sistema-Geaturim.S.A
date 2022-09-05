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
        function Header(){                         
            $this->AddFont('Amble-Regular','','Amble-Regular.php');
            $this->SetFont('Amble-Regular','',10);        
            $fecha = date('Y-m-d', time());
            $this->SetX(1);
            $this->SetY(1);
            $this->Cell(20, 5, $fecha, 0,0, 'C', 0);                         
            $this->Cell(150, 5, "CLIENTE", 0,1, 'R', 0);      
            $this->SetFont('Arial','B',16);                                                    
            $this->Cell(190, 8, $_SESSION['nombre_empresa'], 0,1, 'C',0);                                
            $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"],5,8,45,14);
            $this->SetFont('Amble-Regular','',10);        
            $this->Cell(180, 5, "PROPIETARIO: ".utf8_decode($_SESSION['propietario']),0,1, 'C',0);                                
            $this->Cell(80, 5, "TEL.: ".utf8_decode($_SESSION['telefono']),0,0, 'R',0);                                
            $this->Cell(80, 5, "CEL.: ".utf8_decode($_SESSION['celular']),0,1, 'C',0);                                
            $this->Cell(180, 5, "DIR.: ".utf8_decode($_SESSION['direccion']),0,1, 'C',0);                                
            $this->Cell(180, 5, "SLOGAN.: ".utf8_decode($_SESSION['slogan']),0,1, 'C',0);                                
            $this->Cell(180, 5, utf8_decode( $_SESSION['pais_ciudad']),0,1, 'C',0);                                                                                                    
            $this->SetDrawColor(0,0,0);
            $this->SetLineWidth(0.4);            
            $this->Line(1,50,210,50);            
            $this->SetFont('Arial','B',12);                                                                                                                            
            $this->Cell(190, 5, utf8_decode("CONCILIACION BANCARIA"),0,1, 'C',0);                                                                                                                            
            $this->SetFont('Amble-Regular','',10);        
            $this->Ln(8);
            $this->SetFillColor(255,255,225);            
            $this->SetLineWidth(0.2);                                        
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
    $id=$_GET['id'];
    $sqlconciliacion=pg_query("select * from conciliacion_bancaria where id_conciliacion_bancaria='".$id."'");
    $row=pg_fetch_row($sqlconciliacion);
    $sqlcuenta=pg_query("select numero_cuenta, id_banco from cuentas_bancos where id_cuenta_banco=".$row[1]);
    $row1=pg_fetch_row($sqlcuenta);
    $sqlbanco=pg_query("select descripcion from bancos where id_bancos=".$row1[1]);
    $row2=pg_fetch_row($sqlbanco);
    $pdf->SetX(5);                                                
    $pdf->Cell(100, 6, utf8_decode('GESTIÓN CONTABLE Y TRIBUTARIA'),0,0, 'L',0);                                     
    $pdf->Cell(130, 6, utf8_decode('PERIODO: '.$row[2].' DE '.$row[3]),0,0, 'L',0); 
    $pdf->Ln(5); 
    $pdf->SetX(105);  
    $pdf->Cell(130, 6, utf8_decode('ENTIDAD FINANCIERA: '.$row2[0]),0,0, 'L',0);    
    $pdf->Ln(5); 
    $pdf->SetX(105);  
    $pdf->Cell(130, 6, utf8_decode('CTA. CTE.: '.$row1[0]),0,0, 'L',0); 
    $pdf->Ln(5); 
    $pdf->SetX(5);                                                
    $pdf->Cell(150, 6, utf8_decode('SALDO SEGÚN ESTADO DE CUENTA'),0,0, 'L',0);                                     
    $pdf->Cell(25, 6, utf8_decode($row[4]),0,0, 'C',0);     
    $pdf->Ln(5); 
    $pdf->SetX(5);                                                
    $pdf->Cell(175, 6, utf8_decode('SALDO LIBRO BANCOS'),0,0, 'L',0);                                     
    $pdf->Cell(25, 6, utf8_decode($row[5]),0,0, 'C',0);                   
    $pdf->Ln(7);
    $pdf->SetX(5);                                                
    $pdf->Cell(200, 6, utf8_decode('(+) DÉPOSITOS EN TRÁNSITO'),1,1, 'L',0);         
    $pdf->Ln(2); 
    $depos=explode('**', $row[6]);
    $val_depos=explode('**', $row[7]);
    for ($i=0; $i < count($depos) ; $i++) { 
        $pdf->SetX(10);                                                
        $pdf->Cell(145, 6, utf8_decode($depos[$i]),0,0, 'L',0);
        $pdf->Cell(25, 6, utf8_decode($val_depos[$i]),0,0, 'C',0);         
        $pdf->Ln(5); 
    }
    $pdf->Ln(2);
    $pdf->SetX(5);                                                
    $pdf->Cell(200, 6, utf8_decode('(-) CH/. GIRADOS Y NO COBRADOS'),1,1, 'L',0);         
    $pdf->Ln(2); 
    $cheques=explode('**', $row[8]);
    $val_cheques=explode('**', $row[9]);
    for ($i=0; $i < count($cheques) ; $i++) { 
        $pdf->SetX(10);                                                
        $pdf->Cell(145, 6, utf8_decode($cheques[$i]),0,0, 'L',0);
        $pdf->Cell(25, 6, utf8_decode($val_cheques[$i]),0,0, 'C',0);         
        $pdf->Ln(5); 
    }
    $pdf->Ln(2);
    $pdf->SetX(5);                                                
    $pdf->Cell(200, 6, utf8_decode('(+/-) OTROS'),1,1, 'L',0);         
    $pdf->Ln(2); 
    $otros=explode('**', $row[10]);
    $val_otros=explode('**', $row[11]);
    for ($i=0; $i < count($otros) ; $i++) { 
        $pdf->SetX(10);                                                
        $pdf->Cell(145, 6, utf8_decode($otros[$i]),0,0, 'L',0);
        $pdf->Cell(25, 6, utf8_decode($val_otros[$i]),0,0, 'C',0);         
        $pdf->Ln(5); 
    }
    $pdf->Ln(2);
    $pdf->SetX(5);                                                
    $pdf->Cell(200, 6, utf8_decode('(+) VALORES ACRÉDITADOS'),1,1, 'L',0);         
    $pdf->Ln(2); 
    $acred=explode('**', $row[12]);
    $val_acred=explode('**', $row[13]);
    for ($i=0; $i < count($acred) ; $i++) { 
        $pdf->SetX(10);                                                
        $pdf->Cell(145, 6, utf8_decode($acred[$i]),0,0, 'L',0);
        $pdf->Cell(25, 6, utf8_decode(""),0,0, 'C',0); 
        $pdf->Cell(25, 6, utf8_decode($val_acred[$i]),0,0, 'C',0);         
        $pdf->Ln(5); 
    }
    $pdf->Ln(2);
    $pdf->SetX(5);                                                
    $pdf->Cell(200, 6, utf8_decode('(+) VALORES ACRÉDITADOS'),1,1, 'L',0);         
    $pdf->Ln(2); 
    $debi=explode('**', $row[14]);
    $val_debi=explode('**', $row[15]);
    for ($i=0; $i < count($debi) ; $i++) { 
        $pdf->SetX(10);                                                
        $pdf->Cell(145, 6, utf8_decode($debi[$i]),0,0, 'L',0);
        $pdf->Cell(25, 6, utf8_decode(""),0,0, 'C',0); 
        $pdf->Cell(25, 6, utf8_decode($val_debi[$i]),0,0, 'C',0);         
        $pdf->Ln(5); 
    }
    //$pdf->SetFont('Arial','',9);
    
    $pdf->Ln(2);
    $pdf->Cell(205, 0, utf8_decode(''),1,1, 'R',0);                                     
    $pdf->Cell(155, 6, utf8_decode('SALDOS:'),0,0, 'R',0);                                     
    $pdf->Cell(25, 6,(number_format($row[16],2,',','.')) ,0,0, 'C',0);   
    $pdf->Cell(25, 6,(number_format($row[17],2,',','.')) ,0,0, 'C',0);                                                                                  
    $pdf->Output();
?>