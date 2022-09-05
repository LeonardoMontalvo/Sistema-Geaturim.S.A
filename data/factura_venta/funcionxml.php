<?php

session_start();
include '../../procesos/base.php';
include '../../reportes/fact_elect_xml.php';
conectarse();
$data = 0;
$codDoc = '01';
$id='3469';
    	$consulta_ambiente = pg_query("select codigo_ambi from ambiente where estado_ambi = 'Activo' order by codigo_ambi asc");			
        while ($row = pg_fetch_row($consulta_ambiente)) {
		$ambiente = $row[0];	
	}           
      	$consulta_emision = pg_query("select codigo_temision from tipo_emision order by codigo_temision asc  limit 1");						
	while ($row = pg_fetch_row($consulta_emision)) {
		$emision = $row[0];	//normal cuando generamos la clave
	}    
//        $result = generarXML($id,$codDoc,$ambiente,$emision);
       
          $result = generarXML("2069","2","1","2");
          $doc = new DOMDocument('1.0', 'UTF-8');
          $doc->loadXML($result);//xml
          print_r($doc->saveXML());
          $doc->save('../../xmls/Fc.xml');       
          $data=1;       
        echo $data;    
?>