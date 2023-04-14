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
            $this->Cell(250, 8, utf8_decode($_SESSION['nombre_empresa']), 0,1, 'C',0);                               
            $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"],5,8,45,14);
            $this->SetFont('Amble-Regular','',10);        
            $this->Cell(190, 5, "PROPIETARIO: ".utf8_decode($_SESSION['propietario']),0,1, 'C',0);                                
            $this->Cell(80, 5, "TEL.: ".utf8_decode($_SESSION['telefono']),0,0, 'R',0);                                
            $this->Cell(80, 5, "CEL.: ".utf8_decode($_SESSION['celular']),0,1, 'C',0);                                
            $this->Cell(190, 5, "DIR.: ".utf8_decode($_SESSION['direccion']),0,1, 'C',0);                                
            $this->Cell(190, 5, utf8_decode($_SESSION['slogan']),0,1, 'C',0);                                
            $this->Cell(190, 5, utf8_decode( $_SESSION['pais_ciudad']),0,1, 'C',0);                                                                                                    
            $this->SetDrawColor(0,0,0);
            $this->SetLineWidth(0.4);            
            $this->Line(1,45,210,45);            
            $this->SetFont('Arial','B',12);                                                                            
            $this->Cell(190, 5, utf8_decode("HISTORIAL DE VENTAS DE PRODUCTOS"),0,1, 'C',0);                                                                                                                            
            $this->SetFont('Amble-Regular','',10);        
            $this->Ln(3);
            $this->SetFillColor(255,255,225);            
            $this->SetLineWidth(0.2);                                        
        }
        function Footer(){            
            $this->SetY(-15);            
            $this->SetFont('Arial','I',8);            
            $this->Cell(0,10,'Pag. '.$this->PageNo().'/{nb}',0,0,'C');
        }               
    }
    $fecha_ini=$_GET['inicio'];
    $val_id=$_GET['id'];
//    print_r("hola".$fecha_ini);
    $pdf = new PDF('l','mm','a4');
    $pdf->AddPage();
    $pdf->SetMargins(0,0,0,0);
    $pdf->AliasNbPages();
    $pdf->AddFont('Amble-Regular','','Amble-Regular.php');
    $pdf->SetFont('Amble-Regular','',10);       
    $pdf->SetFont('Arial','B',9);   
    $pdf->SetX(5);    
    $contador=0;

    $pdf->SetFont('Amble-Regular','',9); 
      $sql1=pg_query("select P.ARTICULO FROM productos p where P.cod_productos=$_GET[id] ");        
         while($row2=pg_fetch_row($sql1)){ 

                $pdf->SetFillColor(187, 179, 180);            

                    $pdf->Cell(135, 6, (('NOMBRE PRODUCTO:     '.$row2[0])),1,1, 'L',1);                                                             
                    $pdf->Ln(2); 
         }
                    $pdf->SetX(1); 
                    $pdf->Cell(25, 6, utf8_decode('Fecha'),1,0, 'C',0);                                     
                    $pdf->Cell(70, 6, utf8_decode('Cliente'),1,0, 'C',0);                                     
                    $pdf->Cell(40, 6, utf8_decode('Identificacion'),1,0, 'C',0);                                                             
                    $pdf->Cell(35, 6, utf8_decode('Num Factura'),1,0, 'C',0);                                     
                    $pdf->Cell(30, 6, utf8_decode('Cantidad'),1,0, 'C',0);                                     
                    $pdf->Cell(30, 6, utf8_decode('p.v.p'),1,0, 'C',0);                                     
                    $pdf->Cell(30, 6, utf8_decode('p.total'),1,1, 'C',0);                                                                                                                                                        

                    $num_fact=0;    
                    $sub=0;
                    $saldo=0;
                   
               if($fecha_ini==''){     
                    
               $sql1=pg_query("select fv.fecha_actual,c.nombres_cli,c.identificacion ,fv.num_factura,dfv.cantidad,dfv.precio_venta,dfv.total_venta,p.articulo from factura_venta fv, detalle_factura_venta dfv, clientes c, productos p where fv.id_factura_venta=dfv.id_factura_venta and fv.id_cliente=c.id_cliente  and dfv.cod_productos=p.cod_productos and   dfv.cod_productos= '$_GET[id]' and  fv.id_empresa='$_SESSION[PV]' order by fv.fecha_actual");        
               while($row2=pg_fetch_row($sql1)){                 

//                while($row2=pg_fetch_row($sql1)){
                    $pdf->Cell(25, 6, utf8_decode($row2[0]),0,0, 'C',0);
                    $pdf->Cell(70, 6, utf8_decode($row2[1]),0,0, 'L',0);
                    $pdf->Cell(40, 6, $row2[2],0,0, 'L',0);
                    $pdf->Cell(35, 6, $row2[3],0,0, 'C',0);
                    $pdf->Cell(30, 6, $row2[4],0,0, 'C',0);                    
                    $pdf->Cell(30, 6, utf8_decode($row2[5]),0,0, 'C',0); 
                    $pdf->Cell(30, 6, utf8_decode($row2[6]),0,1, 'C',0);
                    $sub=$sub+$row2[5];                                        
                    $saldo=$saldo+$row2[6];                                        
//                }                

        } 

            $pdf->Ln(2);                               
            $pdf->SetX(1);                                             
            $pdf->Cell(190, 0, utf8_decode(""),0,1, 'R',0);
            $pdf->Cell(200, 6, utf8_decode("Totales"),0,0, 'R',0);
            $pdf->Cell(30, 6, maxCaracter((number_format($sub,2,',','.')),20),1,0, 'C',0);                                                    
            $pdf->Cell(30, 6, maxCaracter((number_format($saldo,2,',','.')),20),1,1, 'C',0);                                                    
            $pdf->Ln(3);                                                                        
               }else{
                  
                      
                           $sql1=pg_query("select fv.fecha_actual,c.nombres_cli,c.identificacion ,fv.num_factura,dfv.cantidad,dfv.precio_venta,dfv.total_venta,p.articulo from factura_venta fv, detalle_factura_venta dfv, clientes c, productos p where fv.id_factura_venta=dfv.id_factura_venta and fv.id_cliente=c.id_cliente  and dfv.cod_productos=p.cod_productos and   dfv.cod_productos= '$_GET[id]' and   fv.fecha_actual between '$_GET[inicio]'  and '$_GET[fin]' and  fv.id_empresa='$_SESSION[PV]' order by fv.fecha_actual");
                while($row2=pg_fetch_row($sql1)){                 

//                while($row2=pg_fetch_row($sql1)){
                    $pdf->Cell(25, 6, utf8_decode($row2[0]),0,0, 'C',0);
                    $pdf->Cell(70, 6, utf8_decode($row2[1]),0,0, 'L',0);
                    $pdf->Cell(40, 6, $row2[2],0,0, 'L',0);
                    $pdf->Cell(35, 6, $row2[3],0,0, 'C',0);
                    $pdf->Cell(30, 6, $row2[4],0,0, 'C',0);                    
                    $pdf->Cell(30, 6, utf8_decode($row2[5]),0,0, 'C',0); 
                    $pdf->Cell(30, 6, utf8_decode($row2[6]),0,1, 'C',0);
                    $sub=$sub+$row2[5];                                        
                    $saldo=$saldo+$row2[6];                                        
//                }                

        } 

            $pdf->Ln(2);                               
            $pdf->SetX(1);                                             
            $pdf->Cell(190, 0, utf8_decode(""),0,1, 'R',0);
            $pdf->Cell(200, 6, utf8_decode("Totales"),0,0, 'R',0);
            $pdf->Cell(30, 6, maxCaracter((number_format($sub,2,',','.')),20),1,0, 'C',0);                                                    
            $pdf->Cell(30, 6, maxCaracter((number_format($saldo,2,',','.')),20),1,1, 'C',0);                                                    
            $pdf->Ln(3);
             
               }
               
    $pdf->Output();
?>