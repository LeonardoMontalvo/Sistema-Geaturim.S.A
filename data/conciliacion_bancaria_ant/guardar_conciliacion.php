<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

//////////////////////////  
$id_cuenta_banco=$_POST['idCuenta'];
$mes=$_POST['mes'];
$anio=$_POST['anio'];
$saldo_estado=$_POST['saldo_estado'];
$saldo_libro=$_POST['saldo_libro'];
$deposito=$_POST['des_depo'];
$val_deposito=$_POST['val_depo'];
$cheques=$_POST['des_cheq'];
$val_cheques=$_POST['val_cheq'];
$otros=$_POST['des_otr'];
$val_otros=$_POST['val_otr'];
$acreditados=$_POST['des_acre'];
$val_acreditados=$_POST['val_acre'];
$debitados=$_POST['des_debi'];
$val_debitados=$_POST['val_debi'];
$estado_fin=$_POST['estado_fin'];
$libro_fin=$_POST['libro_fin'];
//id conciliacion bancaria/////
$id=pg_query("select max(id_conciliacion_bancaria) from conciliacion_bancaria");
while ($row=pg_fetch_row($id)) {
	$cont=$row[0];
}
$cont=$cont+1;

pg_query("insert into conciliacion_bancaria values('$cont', '$id_cuenta_banco', '$mes', '$anio', '$saldo_estado', '$saldo_libro', 
	'$deposito', '$val_deposito', '$cheques', '$val_cheques', '$otros', '$val_otros', '$acreditados', '$val_acreditados', 
	'$debitados', '$val_debitados', '$estado_fin','$libro_fin', 'Activo')");
////////////////////////////////

$data = $cont;
echo $data;
?>
