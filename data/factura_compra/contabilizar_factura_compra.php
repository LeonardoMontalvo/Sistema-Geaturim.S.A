<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

// datos detalle factura
$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];
$campo5 = $_POST['campo5'];
// fin

//contador factura compra
$cont1 = 0;
$consulta = pg_query("select max(id_factura_compra) from factura_compra");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
// fin

// agregar detalle_factura_compra
$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);
$arreglo5 = explode('|', $campo5);
$nelem = count($arreglo1);
$forma = $_POST['formas'];
// fin


$data = $cont1;
echo $data;
// guardar asiento contable
$idtran=pg_query("select max(id_transacciones) from transacciones");
$fila=pg_fetch_row($idtran);
$fila[0]=$fila[0]+1;
$sum=0;
$bool=true;
$pos=0;
$vec=0;
$auxiliar=$arreglo1;
while($bool){
    $cuenta=pg_query("select id_plan_cuentas from productos where cod_productos='".$auxiliar[0]."'");
    $plan=pg_fetch_row($cuenta);
    $nelem=count($auxiliar);
    $vec=0;
    for ($i = 0; $i <= $nelem; $i++) {        
        $cuenta1=pg_query("select id_plan_cuentas from productos where cod_productos='".$auxiliar[$i]."'");
        $plan1=pg_fetch_row($cuenta1);
        if($plan1[$i]==$plan[0]){
            $sum=$arreglo5[$i]+$sum;
        }else{
            $vec[$pos]=$auxiliar[$i];
            $pos++;
        }
    }
    if($vec==0){
        $bool=false;
    }else{
        $auxiliar=$vec;
        $pos=0;
    }
}
$sum=number_format($sum,3,'.','');
$sum=$sum+$_POST['iva'];
$saldo=$sum-$_POST['tot'];
$saldo=number_format($saldo,3,'.','');
$prove=pg_query("select identificacion_pro from proveedores where id_proveedor='$_POST[id_proveedor]'");
$p=pg_fetch_row($prove);
$ing=pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1'");
$res=pg_fetch_row($ing);
$asiento = pg_query("insert into transacciones values('".$fila[0]."', '$_SESSION[id]', '".$cont1."','$_POST[fecha_actual]','$_POST[hora_actual]', 'COMPRA PRODUCTOS, PROVEEDOR: ".$p[0].", COMPROBANTE: ".$seriefin."', '".$sum."', '$_POST[tot]', '".$saldo."','1','".($res[0]+1)."','Activo' )");

$auxiliar=$arreglo1;
$suma=0;
$bool=true;
$aa=0;
$ab=0;
$pos=1;
$vec="";
$ant=$arreglo5;
$vec1="";
while($bool){
    $cont1=0;
    $cont2=0;
    $aa=0;
    $ab=0;
    $iddettran=pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
    $fila1=pg_fetch_row($iddettran);
    $fila1[0]=$fila1[0]+1;
    $cuenta=pg_query("select id_plan_cuentas from productos where cod_productos='".$auxiliar[1]."'");
    while ($plan = pg_fetch_row($cuenta)) {
        $cont2= $plan[0];
    }
    if($cont2==0){
        $cuenta=pg_query("select id_plan_cuentas from productos where cod_productos='".$auxiliar[0]."'");
        while ($plan = pg_fetch_row($cuenta)) {
            $cont2= $plan[0];
        }
    }
    $nelem1=count($auxiliar);        
    $suma=0;
    for ($i = 0; $i <= $nelem1; $i++) {  
        $cuenta1=pg_query("select id_plan_cuentas from productos where cod_productos='".$auxiliar[$i]."'");
        while ($plan1 = pg_fetch_row($cuenta1)) {
            $cont1 = $plan1[0];
        }
        if($cont1!=0){
            if($cont1==$cont2){
                $aa++;
                $suma=$ant[$i]+$suma;
                //$fila1[0]++;
                //pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','4','".$cont2."-".$aa."','".$nelem1."-i".$i."-".$cont1."','prueba')");
            }else{
                $ab++;
                $vec[$pos]=$auxiliar[$i];
                $vec1[$pos]=$ant[$i];
                $pos++;
                //$fila1[0]++;
                //pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','4','".$cont2."-".$ab."','".$nelem1."-i".$i."-".$cont1."','prueba2')");
            } 
        }  
        //$ant=$ant.$cont1."-";     
    }
   // $fila1[0]++;
    $suma=number_format($suma,3,'.','');
    pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','".$cont2."','$suma','0.000','Activo')");
    if($vec==""){
        //$ab++;
        $bool=false;
    }else{
        //$fila1[0]++;
        //pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','4','".$pos."','".$vec[$pos-2]."','otra vez')");
        $auxiliar=$vec;
        $ant=$vec1;
        $vec="";
        $vec1="";
        $pos=0;
    }
}
$iddettran=pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
$fila1=pg_fetch_row($iddettran);
$fila1[0]=$fila1[0]+1;

$planiva=pg_query("select cuenta_debito from parametros where descripcion='IVA'");
$fila2=pg_fetch_row($planiva);
pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','".$fila2[0]."','$_POST[iva]','0.000','Activo')");
$fila1[0]=$fila1[0]+1;
$plancaja=pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
$fila2=pg_fetch_row($plancaja);
pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','".$fila2[0]."','0.000','$_POST[tot]','Activo')");



//DEVOLVER VALORES///

//echo $data;
?>
