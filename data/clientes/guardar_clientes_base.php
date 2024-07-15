<?php

$sql = "SELECT cod_productos, codigo, cod_barras, articulo, iva, series, precio_compra, 
       utilidad_minorista, utilidad_mayorista, iva_minorista, iva_mayorista, 
       categoria, marca, stock, stock_minimo, stock_maximo, fecha_creacion, 
       caracteristicas, observaciones, descuento, estado, inventariable, 
       existencia, diferencia, imagen, id_bodega, incluye_iva, iva_negocio, 
       id_plan_cuentas, proveedor, cantidad_descuento, utilidad_negocio, 
       id_usuario, bien_servicios
  FROM productos where estado='Activo' order by cod_productos;
;
 ";



session_start();
include '../../procesos/base.php';
require_once '../../procesos/fecha.php';
require_once '../../procesos/kardexValorizado.php';
$conexion = conectarse_ori();
conectarse_ori();
error_reporting(0);


$id = obtenerId();
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sql = "SELECT id_cliente, tipo_documento, identificacion, nombres_cli, tipo_cliente, 
       direccion_cli, telefono, celular, pais, ciudad, correo, credito_cupo, 
       notas, estado, id_plan_cuentas
  FROM naturalife2024.clientes where estado='Activo' order by id_cliente;
 ";
//echo ''."SELECT id_cliente, tipo_documento, identificacion, nombres_cli, tipo_cliente, 
//       direccion_cli, telefono, celular, pais, ciudad, correo, credito_cupo, 
//       notas, estado, id_plan_cuentas
//  FROM clientes where estado='Activo' order by id_cliente;
// ";
$conexion = conectarse_ori();
$resultDPB = pg_query($sql);
//echo '//'.$resultDPB;
if (pg_num_rows($resultDPB) > 0) {
    while ($rowDPB = pg_fetch_assoc($resultDPB)) {
 $conexion = conectarse_ori();
//echo '//'.$rowDPB[identificacion];

        if (strlen($rowDPB[identificacion]) == 9) {
            $tipIdent = "Cedula";
            $idIdentif = 2;
             $rowDPB[identificacion] = '0' .  $rowDPB[identificacion];
        }
        if (strlen($rowDPB[identificacion]) == 13) {
            $tipIdent = "Ruc";
            $idIdentif = 1;
        }
        if (strlen($rowDPB[identificacion]) == 10) {
            $tipIdent = "Cedula";
            $idIdentif = 2;
        }
        if (strlen($rowDPB[identificacion]) == 12) {
            $tipIdent = "Ruc";
            $idIdentif = 1;
            $rowDPB[identificacion] = '0' . $rowDPB[identificacion];
        }
    $conexion = conectarse();
     pg_query("INSERT INTO clientes VALUES ( ". obtenerId() . " , "
                . "'$tipIdent',"
                . " '$rowDPB[identificacion]', "
                . " '$rowDPB[nombres_cli]',"
                . " 'Persona Natural',"
                . " '$rowDPB[direccion_cli]', "
                . " '$rowDPB[telefono]',"
                . " '$rowDPB[celular]',"
                . " '$rowDPB[pais]',"
                . " '$rowDPB[ciudad]',"
                . " '$rowDPB[correo]', "
                . "'1',"
                . " '', "
                . "'Activo',"
                . " '1',"
                . " '$idIdentif');");
        
        echo '//'."INSERT INTO clientes VALUES ( ". obtenerId() . " , "
                . "'$tipIdent',"
                . " '$rowDPB[identificacion]', "
                . " '$rowDPB[nombres_cli]',"
                . " 'Persona Natural',"
                . " '$rowDPB[direccion_cli]', "
                . " '$rowDPB[telefono]',"
                . " '$rowDPB[celular]',"
                . " '$rowDPB[pais]',"
                . " '$rowDPB[ciudad]',"
                . " '$rowDPB[correo]', "
                . "'1',"
                . " '', "
                . "'Activo',"
                . " '1',"
                . " '$idIdentif');";
    }
}

function obtenerId() {
    $conexion = conectarse();

    $consulta = pg_query("select max(id_cliente) from clientes");
    $id = (pg_fetch_row($consulta)[0] + 1);
    return $id;
}

///////////////////////////////////////////////////////
?>