<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto2 = $_POST['id_tipo_transaccion'];
$id = $_POST['id_asiento'];
$sema = 0;
$conpunto = 1;
$consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
    while ($row = pg_fetch_row($consultapunto)) {
        $conpunto = $row[0];
    }        
$conpuntoresult = 1;
$consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
    while ($row = pg_fetch_row($consultapuntoresult)) {
        $conpuntoresult = $row[0];
    }
if($id<>""){
	$consulta1 = pg_query("select num_transaccion, id_tipo_transaccion from transacciones where id_transacciones=$id and  id_empresa='$conpuntoresult'");
	while ($row = pg_fetch_row($consulta1)) {
    	if($row[1]!=$texto2){
    		$consulta = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion=$texto2 and  id_empresa='$conpuntoresult'");
    		$sema=1;
    	}else{
    		$consulta = pg_query("select num_transaccion, id_tipo_transaccion from transacciones where id_transacciones=$id and  id_empresa='$conpuntoresult'");
    		$sema=0;
    	}
	}
}else{
	$consulta = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion=$texto2 and  id_empresa='$conpuntoresult'");
	$sema=1;
}

while ($row = pg_fetch_row($consulta)) {
	if($sema==1){
   	 	$data=$row[0]+1;
	}else{
		$data=$row[0];
	}
}
echo $data;
?>
