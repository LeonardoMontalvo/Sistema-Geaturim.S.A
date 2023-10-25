<?php

include '../../fpdf/rotation.php';
include("../../fpdf/barcode.inc.php");
require_once('../../procesos/base.php');
require_once( '../../procesos/funciones.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//error_reporting(0);
class PDF extends PDF_Rotate {

    var $widths;
    var $aligns;

    function SetWidths($w) {
        $this->widths = $w;
    }

    function Header() {
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetY(1);
        $this->Cell(20, 5, 'Generado: ' . $fecha, 0, 0, 'C', 0);
//	        $this->Cell(178, 5, 'MADEHERRAJES 4A', 0,0, 'R', 0);                                                             
        $this->Ln(7);
        $this->SetX(13);
        // $this->RotatedImage('../../fpdf/logo.fw.png', 50, 150, 100, 80, 45);                            
        $this->SetX(0);
    }

    function Footer() {
        $this->SetY(-10);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function RotatedImage($file, $x, $y, $w, $h, $angle) {
        $this->Rotate($angle, $x, $y);
        $this->Image($file, $x, $y, $w, $h);
        $this->Rotate(0);
    }

}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    generarPDF($id);
}

function generarPDF($id) {
  $consulta = pg_query("select e.id_empresa, nombre_empresa, ruc_empresa, direccion_empresa, telefono_empresa, celular_empresa,
        email_empresa, nombre_comercial, obligacion, contribuyente_espe, establecimiento, punto_emision,
        fecha_actual as fecha_emision, num_autorizacion, fecha_autorizacion, num_serie, num_serie, 
        fv.clave, num_serie, c.identificacion, nombres_cli, direccion_cli, correo,
        case when c.telefono!='' then c.telefono else c.celular end as telefono_cli, codigo_tdocu, c.telefono telefono_cli,
        c.celular celular_cli, c.correo correo_cli, direccion_cli ,motivo,num_placa,id_transportista,num_guia_remision,nombres_trans,
         fecha_inicio,fecha_fin,fv.id_guia_remision,fecha_autorizacion,transportista.identificacion as ruc_trasportista
         ,punto_partida,punto_llegada
        from empresa e inner join guia_remision fv using(id_empresa) 
left join clientes c using(id_cliente) 
inner join tipo_documento using(id_tdocu) 
inner join transportista using(id_transportista) 
    where fv.id_guia_remision =  '" . $id . "' ");
    while ($row = pg_fetch_assoc($consulta)) {
  
        $ruc = $row['ruc_empresa'];
        $numeroAutorizacion = $row['num_autorizacion'];
        if ($numeroAutorizacion == "" || $numeroAutorizacion == "undefined") {
            $numeroAutorizacion = $row['clave'];
        } else {
            $numeroAutorizacion = $row['num_autorizacion'];
        }
        $motivacion = $row['motivo'];
        $idFactt = $row['id_guia_remision'];
        $fechaEmision = $row['fecha_emision'];
        $date = new DateTime($fechaEmision);
        $fechaEmision = $date->format('d/m/Y');
        $claveAcceso = $row['clave'];
        $razonSocial = $row['nombre_empresa'];
        $nombreComercial = $row['nombre_comercial'];
        $direcionMatriz = $row['direccion_empresa'];
        $direccionEstablecimiento = $row['direccion_empresa'];
//        $nroContribuyente = $row[19];
        $obligado = $row['obligacion'];
//        $contribuyente = $row[62];
        $identificacion = $row['identificacion'];
        $direcion = $row['direccion_cli'];
        $telefono = $row["telefono_cli"];
        $email = $row['correo'];
        $secuencial = "$row[num_serie]" . "-" . "$row[num_guia_remision]";
        $ip = $secuencial;
        $iparr = split("\-", $ip);
        $explnumserie = explode("-", $row["num_serie"]);
        $establecimiento = $explnumserie[0];
        $puntoEmision = $explnumserie[1];

        $fechaAut = $row['fecha_autorizacion'];

        $codigo = $row['direccion_cli'];
        $num_serie_gua = $secuencial;


        $num_autorizacion_guia = $row['num_autorizacion'];
        if ($num_autorizacion_guia == "" || $num_autorizacion_guia == "undefined") {
            $num_autorizacion_guia = $row['clave'];
        } else {
            $num_autorizacion_guia = $row['num_autorizacion'];
        }
        $fecha_autori_guia = $row['fecha_autorizacion'];
        $claveAcceso_guia = $row['clave'];
        $ruc_transportista = $row['ruc_trasportista'];
        $nombre_transportista = $row['nombres_trans'];
        $placa = $row['num_placa'];
        
        $fecha_inicio = $row['fecha_inicio'];
        $date = new DateTime($fecha_inicio);        
        $fecha_inicio = $date->format('d/m/Y');
        
   
        $fecha_fin = $row['fecha_fin'];
        $date = new DateTime($fecha_fin);        
        $fecha_fin = $date->format('d/m/Y');
       

        $lugar_destino = $row['direccion_cli'];
        $identificacion_destinatario = $row['identificacion'];
        $razon_destinatario = $row['nombres_cli'];
        $punto_partida=$row['punto_partida'];
   $punto_llegada=$row['punto_llegada'];

        $consulta_ambiente = pg_query("select nombre_ambi from ambiente where estado_ambi='Activo' ");
        while ($row = pg_fetch_row($consulta_ambiente)) {
            $nombre_ambi = $row[0];
        }
        $ambiente = $nombre_ambi;
        $consulta_emision = pg_query("select nombre_temision from tipo_emision WHERE id_temision='1' ");
        while ($row = pg_fetch_row($consulta_emision)) {
            $nombre_emi = $row[0];
        }
        $emision = $nombre_emi;
    }

    $consulta_ambiente = pg_query("select nombre_ambi from ambiente where estado_ambi='Activo' ");
    while ($row = pg_fetch_row($consulta_ambiente)) {
        $ambiente = $row[0];
    }
    $consulta_emision = pg_query("select nombre_temision from tipo_emision WHERE id_temision='1' ");
    while ($row = pg_fetch_row($consulta_emision)) {
        $emision = $row[0];
    }

//		$ceros = 9;
//		$temp = '';
//		$tam = $ceros - strlen($secuencial);
//	  	for ($i = 0; $i < $tam; $i++) {                 
//	    	$temp = $temp .'0';        
//	  	}
//	  	$secuencial = $temp .''. $secuencial;

    $pdf = new PDF('P', 'mm', 'a4');
    $pdf->AddPage();
    $pdf->SetMargins(10, 0, 0, 0);
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
    $pdf->SetFont('Amble-Regular', '', 10);

//		$logo = $imagen;
//		$pdf->Rect(3, 8, 100, 36 ,1, 'D');

    $pdf->Image('../../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 25, 10, 55); // Img Empresa 
    $pdf->Rect(3, 45, 100, 53, 'D'); // 2 datos personales
    $pdf->Text(108, 15, 'RUC:     ' . $ruc); // ruc		 	
    $pdf->Text(108, 23, utf8_decode("G     U     I     A     D  E     R     E     M     I     S     I     Ò     N")); // Tipo comprobante
    $pdf->Text(108, 29, 'No.    ' . $num_serie_gua); // Secuencial
    $pdf->Text(108, 36, utf8_decode('NÚMERO DE AUTORIZACIÓN')); // N° Autorizacion
    $pdf->SetY(40);
    $pdf->SetX(107);
    $pdf->Multicell(100, 5, $num_autorizacion_guia, 0); // N° Autorización		
    $code_number1 = $num_autorizacion_guia; // Código de barras		
    new barCodeGenrator($code_number1, 1, 'temp1.gif', 470, 60, true); /// img codigo barras	
    $pdf->Image('temp1.gif', 108, 37, 96, 15);

    if ($fechaAut != '') {
        $pdf->Text(108, 55, utf8_decode('FECHA Y HORA DE AUTORIZACIÓN')); // fecha y hora de autorizacion
        $pdf->Text(108, 61, $fecha_autori_guia); // FECHA
    }

    $pdf->Text(108, 68, utf8_decode('AMBIENTE: ' . $ambiente)); // Ambiente
    $pdf->Text(108, 75, utf8_decode('EMISIÓN: ' . $emision)); // Tipo de emision
    $pdf->Text(108, 81, utf8_decode('CLAVE DE ACCESO: ')); // Clave de acceso
    $code_number = $claveAcceso_guia; // Código de barras		
    new barCodeGenrator($code_number, 1, 'temp.gif', 470, 60, true); /// img codigo barras	
    $pdf->Image('temp.gif', 108, 83, 96, 15);

    $pdf->Rect(106, 8, 102, 90, 'D'); //Datos Empresa	 
    $pdf->SetY(46);
    $pdf->SetX(4);
    $pdf->multiCell(98, 5, $razonSocial, 0); // Razon Social Empresa	
    $pdf->SetY(56);
    $pdf->SetX(4);
    $pdf->SetY(66);
    $pdf->SetX(4);
    $pdf->multiCell(98, 5, 'Dir Matriz: ' . $direccionEstablecimiento, 0); // Direccion Matriz	
    $pdf->SetY(76);
    $pdf->SetX(4);
    $pdf->multiCell(98, 5, 'Dir Sucursal: ' . $direccionEstablecimiento, 0); // Direccion Establecimiento	
    $pdf->Text(5, 96, utf8_decode('Obligado a llevar Contabilidad: ' . $obligado)); // Obligado a llevar contabilidad
    $pdf->Rect(3, 101, 205, 30, 'D'); // INFO TRIBUTARIA			     
    $pdf->SetY(101);
    $pdf->SetX(3);
    $pdf->multiCell(130, 6, utf8_decode('Identificaciòn (Transportista)                              ' . $ruc_transportista), 0); // Nombre cliente		
    $pdf->Text(5, 110, utf8_decode('RAZÒN SOCIAL / NOMBRES Y APELLIDOS:        ' . $nombre_transportista)); //fecha de emision cliente

    $pdf->Text(5, 120, utf8_decode('Placa:                                           ' . $placa));
    $pdf->Text(5, 125, utf8_decode('Punto de Partida:                     ' . $punto_partida));
    $pdf->Text(5, 130, utf8_decode('Fecha Inicio Transporte:         ' . $fecha_inicio));
    $pdf->Text(100, 130, utf8_decode('Fecha fin Transporte:        ' . $fecha_fin));

    $pdf->Rect(3, 135, 205, 150, 'D'); // INFO TRIBUTARIA	

//    $pdf->Text(5, 140, utf8_decode('Comprobante de Venta      FACTURA:              ' . $secuencial));
//    $pdf->Text(120, 140, utf8_decode('Fecha de Emisiòn:                     ' . $fechaEmision));
//    $pdf->Text(5, 145, utf8_decode('Nùmero de Autorizaciòn:         ' . $numeroAutorizacion));

    $pdf->Text(5, 155, utf8_decode('Motivo de Traslado:'. $motivacion));

    $pdf->Text(5, 160, utf8_decode('Destino (Punto de Llegada):                    ' . $punto_llegada));
    $pdf->Text(5, 165, utf8_decode('Identificaciòn Destinatario:                     ' . $identificacion_destinatario));
    $pdf->Text(5, 170, utf8_decode('Razòn Social/Nombres Apellidos:         ' . $razon_destinatario));

    $pdf->Text(5, 175, utf8_decode('Documento Aduanero:        '));
    $pdf->Text(5, 180, utf8_decode('Còdigo Establecimeinto Destino:                     '));
    $pdf->Text(5, 185, utf8_decode('Ruta:  ' . maxCaracter($punto_partida,50) . '-' . $punto_llegada));

    // detalles factura
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->SetY(193);
    $pdf->SetX(20);
    $pdf->multiCell(20, 5, utf8_decode('Cantidad'), 1);
    $pdf->SetY(193);
    $pdf->SetX(40);
    $pdf->multiCell(90, 5, utf8_decode('Descripciòn'), 1);
    $pdf->SetY(193);
    $pdf->SetX(130);
    $pdf->multiCell(40, 5, utf8_decode('Còdigo Principal'), 1);
    $pdf->SetY(193);
    $pdf->SetX(170);
    $pdf->multiCell(30, 5, utf8_decode('Còdigo Auxiliar'), 1);
    $pdf->SetY(193);
    $pdf->SetX(170);
    $pdf->SetY(193);
    $pdf->SetX(188);
    $x = 198;
    $y = 1;

    $resultado = pg_query("select  P.codigo, P.articulo, D.cantidad 
 from detalle_guia_remision D  , productos P where  d.cod_productos =P.cod_productos and
    D.id_guia_remision = '" . $idFactt . "'");
    while ($row = pg_fetch_row($resultado)) {
        $codigo = maxCaracter(utf8_decode($row[0]), 15);
        $codigoAuxiliar = maxCaracter(utf8_decode($row[1]), 13);
        $descripcion = utf8_decode($row[2]);
        $cantidad = $row[2];

        $Descucaltres = 0;
        $desc = 0;
        $valcien = 100;
        $pdf->SetY($x);
        $pdf->SetX(20);
        if (strlen($cantidad) > 10)
            $tam = 3;
        else
            $tam = 6;
        $pdf->multiCell(20, $tam, $cantidad, 1);
//			$pdf->SetY($x);
//			$pdf->SetX(23);
//			if(strlen($codigoAuxiliar) > 19)
//				$tam = 5;
//			else
//				$tam = 10;	
//			$pdf->multiCell(20, $tam, $codigoAuxiliar,1);

        $pdf->SetY($x);
        $pdf->SetX(40);
        if (strlen($descripcion) > 50)
            $tam = 3;
        else
            $tam = 6;
        $pdf->multiCell(90, $tam, $descripcion, 1);
        $pdf->SetY($x);
        $pdf->SetX(130);
        $pdf->multiCell(40, 6, $codigo, 1);
        $pdf->SetY($x);
        $pdf->SetX(170);
        $pdf->multiCell(30, 6, $codigoAuxiliar, 1);
        $pdf->SetY($x);
        $pdf->SetX(170);
        $pdf->SetY($x);
        $pdf->SetX(188);
        $x = $x + 6;
    }

    // pie de pagina           	
    if ($pdf->getY() <= 500) {
        
    } else {
        
    }
    if (isset($_GET['id'])) {
        $pdf->Output();
    } else {
        $pdf_file_contents = $pdf->Output("", "S");
        return $pdf_file_contents;
    }
}

?>