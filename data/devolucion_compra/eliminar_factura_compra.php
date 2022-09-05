<?php

session_start();
include '../../procesos/base.php';
require_once '../../procesos/kardexValorizado.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
error_reporting(0);
$conpuntoresult = $_SESSION['PV'];
//////////////eliminar series///////////
pg_query("Update devolucion_compra Set estado='Pasivo' where id_devolucion_compra='$_POST[id_devolucion_compra]'");
$data = 1;
 insert_registro('ELIMINACION D.C. CON ID: ' . $_POST['comprobante'] . ', DE LA FACTURA: ' . $_POST[id_devolucion_compra]);

// RESTAR stock productos
$consulta = pg_query("select * from detalle_devolucion_compra where id_devolucion_compra = '$_POST[id_devolucion_compra]'");
while ($row = pg_fetch_row($consulta)) {
  $canti1 = $row[3];
  $id = $row[2];
//	 echo '<br>GUARDAR FACTURA VENTA: <br>' . "select * from detalle_producto_bodega where id_bodega=$conpuntoresult and cod_productos= '" . $id . "'";//////////////////////////
//	 
	 
   $consulta2 = pg_query("select * from detalle_producto_bodega where id_bodega=$conpuntoresult and cod_productos= '" . $id . "'");
    while ($row = pg_fetch_row($consulta2)) {
        $cod_pro = $row[1];
        $id_bod = $row[2];
        $stock = $row[6];
    }
    $cal = $stock - $arreglo2[$i];
  //pg_query("Update productos Set stock='".$cal1."' where cod_productos='".$id."'");

  /* procesarKardexAnulacionCompras(
            $id,
            $documento,
            $key['cantidad'],
            'AC',
            "C",
            $key['comprobante'],
            $_POST['observacion'],
            $conpuntoresult,
            $key['id_proveedor']
          ); */

  
}
$detalleCompra = obtenerDetalleCompra($_POST['id_devolucion_compra'], 1);
foreach ($detalleCompra as $item) {
    $documento = "Anulación D.C: " . $item['num_serie'];

//    procesarKardexAnulacionCompras($item['cod_productos'],$documento,$key['cantidad'],'AC',"C",$key['comprobante'],'',1,$key['id_proveedor']);
// 
    procesarKardexEntrada($item['cod_productos'],$documento,$item['cantidad'], $stock,$costoPromedio,'Activo',$conpuntoresult,'AC',$_POST['comprobante'],$total, NULL,NULL,'', NULL,NULL,$item['id_proveedor'],$_SESSION['id']
        );
    
}
// fin resta
/////////////////////////////////

$asiento = pg_query("select id_transacciones from transacciones where comprobante='$_POST[id_devolucion_compra]' and concepto like 'DEVOLUCIÓN COMPRA%'");
$row = pg_fetch_row($asiento);
if ($row[0] != "") {
  pg_query("update transacciones set estado='Pasivo' where id_transacciones=$row[0]");
  pg_query("update detalle_transaccion set estado='Pasivo' where id_transacciones=$row[0]");
}

//$retIva=pg_query("select id_retencion_iva_factura_compra from retencion_iva_factura_compra where id_factura='$_POST[id_factura_compra]'");
//$row1=pg_fetch_row($retIva);
//if($row1[0]!= ""){
//    pg_query("update retencion_iva_factura_compra set estado='Pasivo' where id_retencion_iva_factura_compra=$row1[0]");
//}

//$retFuente=pg_query("select id_retencion_fuente_factura_compra from retencion_fuente_factura_compra where id_factura='$_POST[id_factura_compra]' and id_gastos='1'");
//$row1=pg_fetch_row($retFuente);
//if($row1[0]!= ""){
//    pg_query("update retencion_fuente_factura_compra set estado='Pasivo' where id_retencion_fuente_factura_compra=$row1[0] and id_gastos='1'");
//}
echo $data;

function obtenerDetalleCompra($idFactura, $bodega)
{
    
    
  $sql = "SELECT cod_productos,cantidad,precio_compra,comprobante,DFC.total_compra,id_proveedor,FC.observaciones,FC.num_serie 
    FROM detalle_devolucion_compra DFC 
    INNER JOIN devolucion_compra FC ON FC.id_devolucion_compra = DFC.id_devolucion_compra 
    WHERE FC.id_empresa=$bodega AND FC.id_devolucion_compra=$idFactura";
  $row = pg_fetch_all(pg_query($sql));
  return $row;
}
