<?php

session_start();
include '../../procesos/base.php';
require_once '../../procesos/kardexValorizado.php';
require_once '../../procesos/detalleProductosBodega.php';
conectarse();
error_reporting(0);
date_default_timezone_set('America/Guayaquil');
$horap = date("g:ia");
$conpunto = 1;
// agregar detalle_factura_venta
date_default_timezone_set('America/Guayaquil');
$dt = new DateTime();
$dt1 = $dt->format('Y-m-d');

// datos detalle factura
$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];
$campo5 = $_POST['campo5'];
$campo6 = $_POST['campo6'];
// fin  
// modificar estado factura venta
pg_query("Update inventario Set estado = 'Pasivo', fecha_actual='$_POST[fecha_anulacion]' where id_inventario = '$_POST[comprobante]'");
////////////modificar cantidades////////
$doc = str_pad($_POST['comprobante'], 9, '0', STR_PAD_LEFT);
$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);
$arreglo5 = explode('|', $campo5);
$arreglo6 = explode('|', $campo6);
$nelem = count($arreglo1);

for ($i = 0; $i <= $nelem; $i++) {
    if (!empty($arreglo1[$i])) {
        // consulta productos               
        /* $consulta_v = pg_query("select * from productos where cod_productos=$arreglo1[$i]");
          while ($row = pg_fetch_row($consulta_v)) {
          $cod_pro = $row[1];
          $id_bod = $row[2];
          $stock = $row[13];
          } */
        $stock = obtenerStock($arreglo1[$i], $_SESSION['PV']);
        //if ($stock > $arreglo4[$i]) {
        $cal = $stock - $arreglo4[$i];
        /* } else {
          $cal = $arreglo4[$i] - $stock;
          } */
        //print_r($cal);
//     print_r("entroeeeee");
        $SQL = "Update productos Set  stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "' ";

        //DESBLOQUEAR pg_query("Update productos Set  stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "' ");
        /* $cont_k = 0;
          $consulta_k = pg_query("select max(id_kardex) from kardex");
          while ($row = pg_fetch_row($consulta_k)) {
          $cont_k = $row[0];
          }
          $cont_k++;
          $consulta_v = pg_query("select * from productos where cod_productos=$arreglo1[$i]");
          while ($row = pg_fetch_row($consulta_v)) {
          $cod_pro = $row[1];
          $id_bod = $row[2];
          $stock = $row[13];
          } */

        //pg_query("Update kardex Set estado='Inactivo'  where cod_productos='" . $arreglo1[$i] . "' and compra_venta='INV' and  comprobante='$_POST[comprobante]'");
        updateKardex($_POST['comprobante'], $_SESSION['PV'], $arreglo1[$i], 'Inactivo', 'INV');
        updateKardexValorizado($_POST['comprobante'], $_SESSION['PV'], $arreglo1[$i], 'Inactivo', 'INV');
        //DESBLOQUEAR pg_query("insert into kardex values('$cont_k','$dt1', '" . 'ANULADA INV:' . "' ,'$arreglo4[$i]','$arreglo3[$i]','','$arreglo1[$i]','$stock','3','','','0','$_POST[comprobante]','A','1','$_POST[anulacionComentario]')");
        $costoPromedio = obtenerCostoPromedioUnitarioAnular($arreglo1[$i], $_SESSION['PV'], $_POST['comprobante'], 'INV');
        $total = floatval($arreglo4[$i]) * floatval($arreglo3[$i]);
        procesarKardexSalida($arreglo1[$i], 'Anulación INV:' . $doc, $arreglo4[$i], $stock, $arreglo2[$i], 'Activo', $_SESSION['PV'], 'AINV', 
                $_POST['comprobante'], $total, NULL, NULL, NULL, $_POST['anulacionComentario'], NULL, NULL, $_SESSION['id']);
        
        //$total = floatval($arreglo4[$i]) * floatval($costoPromedio);
        /*procesarKardexSalida($arreglo1[$i], 'Anulación INV:' . $doc, $arreglo4[$i], $stock, $costoPromedio, 'Activo', $_SESSION['PV'], 'AINV', 
                $_POST['comprobante'], $total, NULL, NULL, NULL, $_POST['anulacionComentario'], NULL, NULL, $_SESSION['id']);*/
    }
}

$data = 1;
echo $data;
?>
