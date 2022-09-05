<?php
session_start();
include '../../procesos/base.php';
include '../../reportes/fact_elect_xml.php';
include '../../firma/firma.php';
include '../../firma/xades.php';
include '../../admin/correo.php';
include 'generarPDF.php';
include '../../procesos/funciones.php';
conectarse();
error_reporting(0);
$defaultMail = "jpantojarevelo@gmail.com";
$cont1=0;
$datos=0;

    $resultado = pg_query("SELECT C.correo, C.nombres_cli, F.total_venta ,F.num_autorizacion, F.fecha_actual,f.id_liquidacion_compra FROM liquidacion_compra F, clientes C WHERE F.id_cliente = C.id_cliente and f.estado_fac='2'");
    while ($row = pg_fetch_row($resultado)) {
           $fecha = $row[4];
            $email = $row[0];
            $nombre = $row[1];
            $total = $row[2];
            $num_autorizacion = $row[3];
            $num_facturas = $row[5];
     

  	    $total_venta_tot=0;
            $total_venta_tot = round($total,2);
            
            if($email!=0){
            $data = correo($fecha,$total_venta_tot,'../../xmls/'.$num_autorizacion.'.xml',$num_autorizacion.'.pdf', $nombre, $email,'../../xmls/'.$num_autorizacion.'.xml',generarPDF($num_facturas),1);

            if($data == 1) {
                    $resultado = pg_query("UPDATE liquidacion_compra set estado_fac = '1' where id_liquidacion_compra = '$num_facturas'");

                    if($resultado) {
                            $data = 1; // datos actualizados
                    } else {
                            $data = 4; // error al momento de guadar
                    }
            }	
            }
    	}		
   
        $item = array('estado' => $data);
    


echo $data = json_encode($item);
?>