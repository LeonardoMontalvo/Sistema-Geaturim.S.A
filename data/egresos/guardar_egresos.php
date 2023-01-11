<?php
session_start();
require_once __DIR__ . "/egresos.php";
require_once __DIR__ . "/transferencias/guardar_transferencia.php";
include_once __DIR__ . '/../../procesos/base.php';
error_reporting(0);
$conexion = conectarse();

$conpuntoresult = $_SESSION['PV'];

$campo1 = $_POST['campo1'];//cod_pro
$campo2 = $_POST['campo2'];//cantidad
$campo3 = $_POST['campo3'];//precio_u
$campo4 = $_POST['campo4'];//descuento
$campo5 = $_POST['campo5'];//total
$campo6 = $_POST['campo6'];//cantidad_unidad
$campo7 = $_POST['campo7'];//unidad_medida

$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);
$arreglo5 = explode('|', $campo5);
$arreglo6 = explode('|', $campo6);
$arreglo7 = explode('|', $campo7);
$nelem = count($arreglo1);
$campos = array($arreglo1, $arreglo2, $arreglo3, $arreglo4, $arreglo5, $arreglo6, $arreglo7);

//pg_query($conexion, "BEGIN");
$egreso = procesoGuardarEgreso(
        $conpuntoresult, $_SESSION["id"], $_POST["origen"], $_POST["destino"], $_POST["tarifa0"], $_POST["tarifa12"], $_POST["iva"], $_POST["desc"], $_POST["tot"], $_POST["observaciones"], $campos
);
$data = $egreso;
if (is_numeric($egreso)) {
    if (!empty($_POST["origen"] && !empty($_POST["destino"]))) {
        $transferencia = guardarTransferencia($_POST["origen"], $_POST["destino"], $_SESSION["id"], $egreso);
        if (empty($transferencia)) {
//            pg_query("ROLLBACK");
            $data = "error";
        }
    }
} else {
    pg_query("ROLLBACK");
    $data = "error";
}
//pg_query($conexion, "COMMIT");

$costoVenta = 0;
$costoVenta1 = 0;

for ($i = 1; $i < $nelem; $i++) {
    if (!empty($arreglo1[$i])) {

        $cantidad = 0;
        $precio_total = 0;
        $precio_unitario = 0;
        $costoVenta = 0;
        $consulta2 = pg_query("select * from kardex_valorizado where cod_productos = '$arreglo1[$i]' order by id_kardex desc limit 1");
        while ($row = pg_fetch_row($consulta2)) {
            $cantidad = $row[11];
            $precio_unitario = round($row[7], 4);
            $precio_total = round($row[8], 4);
            $costoVenta = round($row[13], 4);
        }
        if ($costoVenta == "0.0000") {
            $costoVenta1 = $costoVenta1 + ( $arreglo2[$i]);
        } else {
            $costoVenta1 = $costoVenta1 + ($costoVenta * $arreglo2[$i]);
        }
        //Asiento Contable 
        $cuenta = pg_query("select iva,id_plan_cuentas from productos where cod_productos ='" . $arreglo1[$i] . "'  and bien_servicios='B'");
        $plan = pg_fetch_row($cuenta);

        if ($plan[0] == "Si") {
            if ($costoVenta == "0.0000") {
                $inventario12B = $inventario12B + ( $arreglo2[$i]);
            } else {
                $inventario12B = $inventario12B + ($costoVenta * $arreglo2[$i]);
            }

            $sumaSubtotalTarifa12B = $sumaSubtotalTarifa12B + $arreglo5[$i];
            $codplanTarifa12B = $plan[1];
            $contTarifa12++;
        } else if ($plan[0] == "No") {
            if ($costoVenta == "0.0000") {
                $inventario0B = $inventario0B + ( $arreglo2[$i]);
            } else {
                $inventario0B = $inventario0B + ($costoVenta * $arreglo2[$i]);
            }

            $sumaSubtotalTarifa0B = $sumaSubtotalTarifa0B + $arreglo5[$i];
            $codplanTarifa0B = $plan[1];
            $contTarifa0++;
        }
        /////////////////////////
        //Asiento Contable 
        $cuenta = pg_query("select iva,id_plan_cuentas from productos where cod_productos ='" . $arreglo1[$i] . "'  and bien_servicios='S'");
        $plan = pg_fetch_row($cuenta);

        if ($plan[0] == "Si") {
            if ($costoVenta == "0.0000") {
                $inventario12 = $inventario12 + ( $arreglo2[$i]);
            } else {
                $inventario12 = $inventario12 + ($costoVenta * $arreglo2[$i]);
            }

            $sumaSubtotalTarifa12 = $sumaSubtotalTarifa12 + $arreglo5[$i];
            $codplanTarifa12 = $plan[1];
            $contTarifa12++;
        } else if ($plan[0] == "No") {
            if ($costoVenta == "0.0000") {
                $inventario0 = $inventario0 + ($arreglo2[$i]);
            } else {
                $inventario0 = $inventario0 + ($costoVenta * $arreglo2[$i]);
            }

            $sumaSubtotalTarifa0 = $sumaSubtotalTarifa0 + $arreglo5[$i];
            $codplanTarifa0 = $plan[1];
            $contTarifa0++;
        }
    }
}

////////////////////////////CREACION ASIENTO CONTADO - CHEQUE
// guardar asiento contable

$idtran = pg_query("select max(id_transacciones) from transacciones");
$fila = pg_fetch_row($idtran);
$fila[0] = $fila[0] + 1;
$sum = 0;
$bool = true;
$pos = 0;
$vec = 0;
$tieneiva;
$ivafin = 0;
$auxiliar = $arreglo1;
$abc = 0;
$xy = 0;
$iva = pg_query("select valor from parametros where descripcion='IVA'");
while ($ivavalor = pg_fetch_row($iva)) {
    $ivafin = $ivavalor[0];
}
$abc = ($ivafin + 100) / 100;

$cont1 = obtenerIdEgresog();

$ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1' and id_empresa= '$_SESSION[PV]'");
$res = pg_fetch_row($ing);
$ing_pv = pg_query("select max(id_transaccion_pv::int) from transacciones where id_empresa= '$_SESSION[PV]'");
$res_pvt = pg_fetch_row($ing_pv);
//////////////////////////////////////////////////////////////////
$punto_venta_origen = "";
$punto_venta = pg_query("SELECT nombre_punto
  FROM punto_venta where id_punto_venta='$_POST[origen]'");


while ($row = pg_fetch_row($punto_venta)) {
    $punto_venta_origen = $row[0];
}

$res_pv_destino = "";
$res_pv = pg_query("SELECT nombre_punto
  FROM punto_venta where id_punto_venta='$_POST[destino]'");

while ($row = pg_fetch_row($res_pv)) {
    $res_pv_destino = $row[0];
}
//                  print_r("trans2".$costoVenta1);
//echo '<br>GUARDAR FACTURA VENTA12F: <br>' . "insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'ANTICIPO CLIENTES, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['secuencial'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $_POST[monto] . "', '$_POST[monto]', '" . $_POST[monto] . "','2','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','ANTC','',$conpuntoresult,'$_POST[fecha_actual]')"; //////////////////////////
$valor_Servicio1_iva = '';
//echo '<br>GUARDAR FACTURA VENTA1 ggggg: <br>' . "SELECT  sum(precio_costo)FROM detalle_egreso where id_egresos='$cont1'"; 
$consulta_bien_servi_iva = pg_query("SELECT  sum(precio_costo)FROM detalle_egreso where id_egresos='$cont1' ");
while ($row = pg_fetch_row($consulta_bien_servi_iva)) {
    $valor_Servicio1_iva = $row[0];
}
if ($valor_Servicio1_iva != "") {

    if ($inventario12B > 0) {
        $total0total12B = $inventario12B + $inventario0B;
//        echo '<br>GUARDAR FACTURA transaccion111: <br>' . "insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'TRANSFERENCIA EGRES:  , COMPROBANTE: " . $cont1 . ", DEL PUNTO " . $punto_venta_origen . ", AL PUNTO" . $res_pv_destino . "', '" . $total0total12B . "', '$total0total12B', '0.000','2','" . ($res[0] + 1) . "','Activo',NULL,'','','','','E','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pvt[0] + 1) . "')";
        $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'TRANSFERENCIA EGRES:  , COMPROBANTE: " . $cont1 . ", DEL PUNTO " . $punto_venta_origen . ", AL PUNTO" . "  " . $res_pv_destino . "', '" . $total0total12B . "', '$total0total12B', '0.000','2','" . ($res[0] + 1) . "','Activo',NULL,'','','','','E','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pvt[0] + 1) . "')");
    } else {
        $total0total12 = $inventario12B + $inventario0B;
//        echo '<br>GUARDAR FACTURA transaccion22: <br>' . "insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'TRANSFERENCIA EGRES:  , COMPROBANTE: " . $cont1 . ", DEL PUNTO " . $punto_venta_origen . ", AL PUNTO" . $res_pv_destino . "', '" . $total0total12 . "', '$total0total12', '0.000','2','" . ($res[0] + 1) . "','Activo',NULL,'','','','','E','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')";
        $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'TRANSFERENCIA EGRES:  , COMPROBANTE: " . $cont1 . ", DEL PUNTO " . $punto_venta_origen . ", AL PUNTO" . "  " . $res_pv_destino . "', '" . $total0total12 . "', '$total0total12', '0.000','2','" . ($res[0] + 1) . "','Activo',NULL,'','','','','E','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pvt[0] + 1) . "')");
    }
}

$iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
$fila1 = pg_fetch_row($iddettran);
$buscaCuenta = 0;
////////////////////MATRIZ A PUNTO A 
if ($_POST["origen"] == '1' && $_POST["destino"] == '2') {
//     echo "1 a 2";
    $sql = pg_query("
  SELECT id_plan_cuentas, codigo_plan, descripcion, cuenta, estado
  FROM plan_cuentas where codigo_plan like '%1.1.03.01.02%'");
    $buscaCuenta = pg_fetch_row($sql);
}
////////////////////MATRIZ A PUNTO b
if ($_POST["origen"] == '1' && $_POST["destino"] == '3') {
//      echo "1 a 3";
    $sql = pg_query("
  SELECT id_plan_cuentas, codigo_plan, descripcion, cuenta, estado
 FROM plan_cuentas where codigo_plan like '%1.1.03.01.03%'");
    $buscaCuenta = pg_fetch_row($sql);
}
////////////////////A A PUNTO MATRIZ 
if ($_POST["origen"] == '2' && $_POST["destino"] == '1') {
//      echo "2 a 1";
    $sql = pg_query("
  SELECT id_plan_cuentas, codigo_plan, descripcion, cuenta, estado
  FROM plan_cuentas where codigo_plan like '%2.1.03.01.03%'");
    $buscaCuenta = pg_fetch_row($sql);
}
////////////////////A A PUNTO B 
if ($_POST["origen"] == '2' && $_POST["destino"] == '3') {
//      echo "2 a 3";
    $sql = pg_query("
  SELECT id_plan_cuentas, codigo_plan, descripcion, cuenta, estado
  FROM plan_cuentas where codigo_plan like '%2.1.03.01.05%'");
    $buscaCuenta = pg_fetch_row($sql);
}
////////////////////A B PUNTO MATRIZ
if ($_POST["origen"] == '3' && $_POST["destino"] == '1') {
//      echo "3 a 1";
    $sql = pg_query("
  SELECT id_plan_cuentas, codigo_plan, descripcion, cuenta, estado
  FROM plan_cuentas where codigo_plan like '%2.1.03.01.03%'");
    $buscaCuenta = pg_fetch_row($sql);
}
////////////////////A B PUNTO a A
if ($_POST["origen"] == '3' && $_POST["destino"] == '2') {
//      echo "3 a 2";
    $sql = pg_query("
  SELECT id_plan_cuentas, codigo_plan, descripcion, cuenta, estado
  FROM plan_cuentas where codigo_plan like '%1.1.03.01.02%'");
    $buscaCuenta = pg_fetch_row($sql);
}
/////////////////////////////////////////////////
////////////////////SOLO PUNTO A
if ($_POST["origen"] == '1' && $_POST["destino"] == '') {
//      echo "3 a 2";
    $sql = pg_query("
  SELECT id_plan_cuentas, codigo_plan, descripcion, cuenta, estado
  FROM plan_cuentas where codigo_plan like '%2.1.03.01.03%'");
    $buscaCuenta = pg_fetch_row($sql);
}
////////////////////SOLO PUNTO B
if ($_POST["origen"] == '2' && $_POST["destino"] == '') {
//      echo "3 a 2";
    $sql = pg_query("
  SELECT id_plan_cuentas, codigo_plan, descripcion, cuenta, estado
  FROM plan_cuentas where codigo_plan like '%1.1.03.01.02%'");
    $buscaCuenta = pg_fetch_row($sql);
}
////////////////////SOLO PUNTO C
if ($_POST["origen"] == '3' && $_POST["destino"] == '') {
//      echo "3 a 2";
    $sql = pg_query("
  SELECT id_plan_cuentas, codigo_plan, descripcion, cuenta, estado
  FROM plan_cuentas where codigo_plan like '%1.1.03.01.03%'");
    $buscaCuenta = pg_fetch_row($sql);
}

if ($inventario12B > 0) {
    $total0total12B = $inventario12B + $inventario0B;


    $fila1[0] = $fila1[0] + 1;
//    echo '<br>GUARDAR FACTURA VENTA3345HHGJ: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','" . $total0total12B . "','0.000','Activo')"; //////////////////////////

    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','" . $total0total12B . "','0.000','Activo')");



    $sql = pg_query("SELECT id_plan_cuentas FROM plan_cuentas where descripcion like '%INVENTARIO DE MERCADERIA%'");
    $buscaCuenta = pg_fetch_row($sql);
    $fila1[0] = $fila1[0] + 1;
//echo '<br>GUARDAR FACTURA VENTA3345HHJ: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','" . $total0total12B . "','Activo')"; //////////////////////////
    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','" . $total0total12B . "','Activo')");
} else if ($inventario0B > 0) {
    $total0total12 = $inventario12B + $inventario0B;


    $fila1[0] = $fila1[0] + 1;
//    echo '<br>GUARDAR FACTURA VENTA3345HHGHH: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','" . $total0total12 . "','0.000','Activo')"; //////////////////////////

    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','" . $total0total12 . "','0.000','Activo')");



    $sql = pg_query("SELECT id_plan_cuentas FROM plan_cuentas where descripcion like '%INVENTARIO DE MERCADERIA%'");
    $buscaCuenta = pg_fetch_row($sql);
    $fila1[0] = $fila1[0] + 1;
//    echo '<br>GUARDAR FACTURA VENTA3345HHJ: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','" . $total0total12 . "','Activo')"; //////////////////////////
    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','" . $total0total12 . "','Activo')");
}

function obtenerIdEgresog() {
    $sql = "SELECT max(e.id_egresos) FROM egresos e";
    $id = (pg_fetch_row(pg_query($sql))[0]);
    return $id;
}

echo $data;


