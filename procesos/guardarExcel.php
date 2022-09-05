<?php
	include 'base.php';
	require_once '../phpexcel/PHPExcel-1.7.7/Classes/PHPExcel/IOFactory.php';
	conectarse();
	//error_reporting(0);
	$cont_prod = 0;
	$data=1;
	$consulta = pg_query("select max(cod_productos)+1 from productos;");
	while ($row = pg_fetch_row($consulta)) {
	    $cont_prod = $row[0];
	}

	$extension = explode(".", $_FILES["archivo_excel"]["name"]);

	$extension = end($extension);
	$type = $_FILES["archivo_excel"]["type"];
	$tmp_name = $_FILES["archivo_excel"]["tmp_name"];
	$size = $_FILES["archivo_excel"]["size"];
	$nombre = basename($_FILES["archivo_excel"]["name"], "." . $extension);

	$nombreTemp = "datosProductos" . '.' . $extension;
	if(move_uploaded_file($_FILES["archivo_excel"]["tmp_name"], "../temp/" . $nombreTemp)){
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
				$lista[] = $objCelda['A'];
				$lista[] = $objCelda['B'];
				$lista[] = $objCelda['C'];
				$lista[] = $objCelda['D'];
				$lista[] = $objCelda['E'];
				$lista[] = $objCelda['F'];
				$lista[] = $objCelda['G'];
				$lista[] = $objCelda['H'];
				$lista[] = $objCelda['I'];
				$lista[] = $objCelda['J'];
				$lista[] = $objCelda['K'];
				$lista[] = $objCelda['L'];
                                $lista[] = $objCelda['M'];
                                $lista[] = $objCelda['N'];
                                $lista[] = $objCelda['O'];
                                $lista[] = $objCelda['P'];
			}
			$cont++;
		}	
	}
	echo $lista = json_encode($lista);
?>