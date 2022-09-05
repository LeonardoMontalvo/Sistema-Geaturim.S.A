<?php

session_start();
require_once '../base.php';
require_once '../detalleProductosBodega.php';
require_once '../kardexValorizado.php';
conectarse();

switch ($_POST['op']) {
    case 1:
        echo validarStock($_POST['producto'], $_POST['cantidad']);
        break;
    case 2:
        echo anular($_POST['comprobante'], $_POST['anulacionComentario']);
        break;
}

function validarStock($producto, $cantidad) {
    if (floatval(obtenerStock($producto, $_SESSION['PV'])) >= floatval($cantidad)) {
        $estado = TRUE;
    } else {
        $estado = FALSE;
    }
    return json_encode(["estado" => $estado]);
}

function anular($id, $comentario) {
    try {
        $docu = str_pad($id, 9, "0", STR_PAD_LEFT);
        foreach (obtenerDetalle($id, $_SESSION['PV']) as $key) {
            if (!empty($key['origen']) && !empty($key['destino'])) {
                $costoPromedio = obtenerCostoPromedioUnitarioAnular($key['cod_productos'], $key['origen'], $id, 'EI');
                updateKardex($id, $key['origen'], $key['cod_productos'], 'Inactivo', 'EI', $key['origen'], $key['destino']);
                updateKardexValorizado($id, $key['origen'], $key['cod_productos'], 'Inactivo', 'EI');
                procesarKardexEntrada($key['cod_productos'], "ANULACION T.E - " . $docu, $key['cantidad'], obtenerStock($key['cod_productos'], $key['origen']), 
                        $costoPromedio, 'Activo', $key['origen'], 'AE', $id, NULL, $key['origen'], $key['destino'], $comentario, NULL, NULL, NULL, $_SESSION['id']);
                                                                            //TOTAL
                $costoPromedio = obtenerCostoPromedioUnitarioAnular($key['cod_productos'], $key['destino'], $id, 'EI');
                updateKardex($id, $key['destino'], $key['cod_productos'], 'Inactivo', 'EI', $key['origen'], $key['destino']);
                updateKardexValorizado($id, $key['destino'], $key['cod_productos'], 'Inactivo', 'EI');
                procesarKardexSalida($key['cod_productos'], "ANULACION T.E - " . $docu, $key['cantidad'], obtenerStock($key['cod_productos'], $key['destino']), 
                        $costoPromedio, 'Activo', $key['destino'], 'AEI', $id, $key['total'], $key['origen'], $key['destino'], NULL, $comentario, NULL, NULL, $_SESSION['id']);
            } else {
                $documento = "ANULACION T.E.L - " . $docu;
                $costoPromedio = obtenerCostoPromedioUnitarioAnular($key['cod_productos'], $_SESSION['PV'], $id, 'EI');
                updateKardex($id, $_SESSION['PV'], $key['cod_productos'], 'Inactivo', 'E', NULL, NULL);
                updateKardexValorizado($id, $_SESSION['PV'], $key['cod_productos'], 'Inactivo', 'E');
                procesarKardexEntrada($key['cod_productos'], $documento, $key['cantidad'], obtenerStock($key['cod_productos'], $_SESSION['PV']), 
                        $costoPromedio, 'Activo', $_SESSION['PV'], 'AE', $id, $key['total'], NULL, NULL, $comentario, NULL, NULL, NULL, $_SESSION['id']);
            }
        }
        cambiarEstadoEgreso($id, 'Pasivo');
        $datos=["estado"=>TRUE,"registro"=>$id];
    } catch (Exception $ex) {
        $datos=["estado"=>FALSE];
    }
    return json_encode($datos);
}

function obtenerDetalle($id, $bodega) {
    $sql = "SELECT * FROM egresos E INNER JOIN detalle_egreso DE ON DE.id_egresos = E.id_egresos WHERE E.id_egresos=$id AND E.id_empresa=$bodega";
    return pg_fetch_all(pg_query($sql));
}

function cambiarEstadoEgreso($id, $estado) {
    $sql = "UPDATE egresos SET estado='$estado' WHERE id_egresos=$id";
    pg_query($sql);
}
