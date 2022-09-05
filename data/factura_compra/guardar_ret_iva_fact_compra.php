<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
date_default_timezone_set('America/Guayaquil'); 
 $conpuntoresult = $_SESSION['PV'];
// datos detalle factura
$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];
$campo5 = $_POST['campo5'];
// fin

//contador factura compra
$cont1 = 0;
$consulta = pg_query("select max(id_retencion_iva_factura_compra) from retencion_iva_factura_compra");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;
// fin
//fecha actual

$data=0;
$fecha = date('Y-m-d', time());
$hora=date('h:i:s A', time());
$comprobar = pg_query("select id_factura from retencion_iva_factura_compra");
while ($row2 = pg_fetch_row($comprobar)) {
	if($row2[0]==$_POST[id_factura]){
		$data=2;
	}
}
if($data!=2){
	      
////////////////////////////////
 ////////////////ASIENTO CONTABLE
        $tran=pg_query("select * from transacciones where comprobante='$_POST[id_factura]' and id_tipo_transaccion='1' and concepto like 'COM%' and id_empresa= $conpuntoresult");
	$fila=pg_fetch_row($tran);
	$iddettran=pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
	$fila1=pg_fetch_row($iddettran);
	$fila1[0]=$fila1[0]+1;
	$cons=pg_query("select cuenta_credito from retencion_iva where id_retencion_iva='$_POST[id_retencion_iva]'");
	$cont2=pg_fetch_row($cons);
	pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','".$cont2[0]."','0.000','$_POST[valor_retencion]','Activo')");
	$cont3 ="select * from transacciones where comprobante='$_POST[id_factura]' and id_tipo_transaccion='1' and concepto like 'COM%' and id_empresa= $conpuntoresult";
        echo $cont3.'<BR>';
        $x=$_POST['valor_retencion'];
        
              // 70     --> Retención IVA 30%
              // 75     --> Retención Fuente 1% Transporte
              // 74     --> Retención Fuente 1% Compras
              // 78     --> Retención Fuente 10% Honorarios
              // 77     --> Retención Fuente 8% Arriendos
              // 211    --> DESCUENTOS COMPRAS
              // 28     --> IVA
              // 23     --> Inventario Materia Prima
              //23,24,25,216,217 Excluye retenciones 
        
        $sql=pg_query("select id_plan_cuentas from detalle_transaccion where id_transacciones='".$fila[0]."' "
                    . "and id_plan_cuentas<>'53'"//"Retenciones IVA Proveedores"
            . "and id_plan_cuentas<>'55'"//"Retenciones en la Fuente Proveedores"
            . "and id_plan_cuentas<>'58'"//"Retenciones en la Fuente Empleados"
            . "and id_plan_cuentas<>'154'"//"Retenciones en la Fuente Socios"
            . "and id_plan_cuentas<>'164'"//"Retenciones en la Fuente Otros"
            . "and id_plan_cuentas<>'165'"//"Impuesto a la Renta por Pagar"
            . "and id_plan_cuentas<>'166'"//"Retención Fuente 2.75% Servicios"
            . "and id_plan_cuentas<>'167'"//"Retención Fuente 8% Arriendos"
            . "and id_plan_cuentas<>'168'"//"Retención Fuente 10% Honorarios"
            . "and id_plan_cuentas<>'169'"//"Iva en Compras"
            . "and id_plan_cuentas<>'170'"//"Inventario Materia Prima"
            . "and id_plan_cuentas<>'171'"//"Inventario Productos en Proceso"
            . "and id_plan_cuentas<>'172'"//"Inventario Productos Terminados"
            . "and id_plan_cuentas<>'173'"//"Inventario 12%"
            . "and id_plan_cuentas<>'174'"//"Inventario 0%"
            . "and id_plan_cuentas<>'175'"//"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'176'"//"Retención Fuente 10% Honorarios"
            . "and id_plan_cuentas<>'177'"//"Iva en Compras"
            . "and id_plan_cuentas<>'178'"//"Inventario Materia Prima"
            . "and id_plan_cuentas<>'179'"//"Inventario Productos en Proceso"
            . "and id_plan_cuentas<>'180'"//"Inventario Productos Terminados"
            . "and id_plan_cuentas<>'181'"//"Inventario 12%"
            . "and id_plan_cuentas<>'182'"//"Inventario 0%"
            . "and id_plan_cuentas<>'183'"//"Suministros y Materiales Agrícolas"
             . "and id_plan_cuentas<>'184'"//"Suministros y Materiales Agrícolas"
             . "and id_plan_cuentas<>'556'"//"Suministros y Materiales Agrícolas"
            
             . "and id_plan_cuentas<>'52'"//"Retención Fuente 10% Honorarios"
            . "and id_plan_cuentas<>'53'"//"Iva en Compras"
            . "and id_plan_cuentas<>'63'"//"Inventario Materia Prima"
            . "and id_plan_cuentas<>'395'"//"Inventario Productos en Proceso"
            . "and id_plan_cuentas<>'470'"//"Inventario Productos Terminados"
            . "and id_plan_cuentas<>'474'"//"Inventario 12%"
            . "and id_plan_cuentas<>'475'"//"Inventario 0%"
            . "and id_plan_cuentas<>'556'"//"Suministros y Materiales Agrícolas"
             . "and id_plan_cuentas<>'593'"//"Suministros y Materiales Agrícolas"
             . "and id_plan_cuentas<>'614'"//"Suministros y Materiales Agrícolas"
            
             . "and id_plan_cuentas<>'311'"//"Inventario 12%"
            . "and id_plan_cuentas<>'326'"//"Inventario 0%"
            . "and id_plan_cuentas<>'327'"//"Suministros y Materiales Agrícolas"
             . "and id_plan_cuentas<>'423'"//"Suministros y Materiales Agrícolas"
             . "and id_plan_cuentas<>'458'"//"Suministros y Materiales Agrícolas"
            
               . "and id_plan_cuentas<>'512'"//"Inventario 12%"
            . "and id_plan_cuentas<>'569'"//"Inventario 0%"
            . "and id_plan_cuentas<>'586'"//"Suministros y Materiales Agrícolas"
             . "and id_plan_cuentas<>'612'"//"Suministros y Materiales Agrícolas"
             . "and id_plan_cuentas<>'624'"//"Suministros y Materiales Agrícolas"
                );
	$idPlan=pg_fetch_row($sql);
        $plancaja=pg_query("select cuenta_debito from parametros where cuenta_debito='".$idPlan[0]."'");
	$caja=pg_fetch_row($plancaja);
       
	$tot=pg_query("select credito, id_detalle_transaccion from detalle_transaccion where id_transacciones='".$fila[0]."' and id_plan_cuentas='".$caja[0]."'");
	$s=pg_fetch_row($tot);
	$caja=$s[0]-$x;
	pg_query("update detalle_transaccion set credito='".$caja."' where id_detalle_transaccion='".$s[1]."'");
////////////////////////////////////////////
/////////////////////////////////////////   
        
        $data=1;
}

echo $data;

?>
