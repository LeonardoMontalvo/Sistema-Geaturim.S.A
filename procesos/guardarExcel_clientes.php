<?php
	include 'base.php';
	require_once '../phpexcel/PHPExcel-1.7.7/Classes/PHPExcel/IOFactory.php';
	conectarse();
	//error_reporting(0);
	$cont_prod = 0;
	$data=1;
	$consulta = pg_query("select max(id_cliente)+1 from clientes;");
	while ($row = pg_fetch_row($consulta)) {
	    $cont_prod = $row[0];
	}

	$extension = explode(".", $_FILES["archivo_excel_cli"]["name"]);

	$extension = end($extension);
	$type = $_FILES["archivo_excel_cli"]["type"];
	$tmp_name = $_FILES["archivo_excel_cli"]["tmp_name"];
	$size = $_FILES["archivo_excel_cli"]["size"];
	$nombre = basename($_FILES["archivo_excel_cli"]["name"], "." . $extension);

	$nombreTemp = "datosProductos" . '.' . $extension;
	if(move_uploaded_file($_FILES["archivo_excel_cli"]["tmp_name"], "../temp/" . $nombreTemp)){
		$data = 1;
	}else{
		$data = 0;
	}
	if($data==1){	
		//cargamos el archivo_excel que deseamos leer
		$objPHPExcel = PHPExcel_IOFactory::load('../temp/'.$nombreTemp);
		$objHoja=$objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		$cont=0;
		foreach ($objHoja as $iIndice=>$objCelda) {
			if($cont>=5){
				$lista[] = $objCelda['A'];//ruc 0 var
				$lista[] = $objCelda['B'];//nombres 1 var1
				$lista[] = $objCelda['C'];//dir 2      var2
				$lista[] = $objCelda['D'];//telefono 3   var3
				$lista[] = $objCelda['E'];//correo 4     var4
				
			}
			$cont++;
		}	
	}
	echo $lista = json_encode($lista);
?>