<?php

session_start();
include_once '../../procesos/base.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
$cont = 0;

$idcontrato = !empty($_POST['id_contrato']) ? $_POST['id_contrato'] : 0;

$datosContrato = array(
    $fecha_contrato = !empty($_POST['fecha_contrato']) ? $_POST['fecha_contrato'] : '',
    $fecha_salida = !empty($_POST['fecha_salida']) ? $_POST['fecha_salida'] : '',
    $fecha_retorno = !empty($_POST['fecha_retorno']) ? $_POST['fecha_retorno'] : '',
    $nro_dias = !empty($_POST['nro_dias']) ? $_POST['nro_dias'] : 0,
    $nro_personas = !empty($_POST['nro_personas']) ? $_POST['nro_personas'] : 0,
    $valor = !empty($_POST['valor']) ? $_POST['valor'] : 0,
    $nro_contrato = !empty($_POST['nro_contrato']) ? $_POST['nro_contrato'] : 0,
    $id_cliente = !empty($_POST['id_cliente']) ? $_POST['id_cliente'] : 0,
);
$datosRuta = array(
    $id_lugar_origen = !empty($_POST['id_lugar_origen']) ? $_POST['id_lugar_origen'] : 0,
    $id_lugar_destino = !empty($_POST['id_lugar_destino']) ? $_POST['id_lugar_destino'] : 0,
    $hora_salida = !empty($_POST['hora_salida']) ? $_POST['hora_salida'] : '',
    $ruta_completa = !empty($_POST['ruta_completa']) ? mb_strtoupper(str_replace("'", "''", trim($_POST['ruta_completa']))) : '',
    $comentario = !empty($_POST['comentario']) ? mb_strtoupper(str_replace("'", "''", trim($_POST['comentario']))) : '',
);
$detallesVehiculoContrato = !empty($_POST['detalles']) ? $_POST['detalles'] : [];

$operaicon = $_POST['oper'];
if (isset($operaicon) && !empty($operaicon)) {
    switch ($operaicon) {
        case 'add':
            echo json_encode(transaccionNuevoContrato());
            break;
        case 'siguientenrocontrato':
            echo obtenerSiguienteNroContrato();
            break;
        case 'buscarxid':
            echo json_encode(buscarPorId($idcontrato));
            break;
        case 'edit':
            echo json_encode(transaccionActualizarContrato());
            break;
        case 'del':
            echo eliminarContato($idcontrato);
            break;
    }
}

function agregarContrato($parametros) {

    if (existeContrato($parametros[6])) {
        return -1;
    }

    $consultaid = pg_query("select max(id_contrato) from contrato_alquiler_vehiculo_trasporte");
    while ($row = pg_fetch_row($consultaid)) {
        $cont = $row[0];
    }
    $cont++;

    $sql = "INSERT INTO contrato_alquiler_vehiculo_trasporte(
        id_contrato, fecha_contrato, fecha_salida, fecha_retorno, nro_dias, 
        nro_personas, valor, id_cliente, estado, nro_contrato,id_usuario,
        fecha_creacion,fecha_modificacion)
        VALUES ($cont,'{$parametros[0]}', 
        '{$parametros[1]}', 
        '{$parametros[2]}', 
        {$parametros[3]}, 
        {$parametros[4]}, 
        {$parametros[5]}, 
        {$parametros[7]}, 
        'Activo', 
        {$parametros[6]},
        {$_SESSION['id']},
        '" . date('Y-m-d H:i:s') . "',
        '" . date('Y-m-d H:i:s') . "');
        ";
    $consulta = pg_query($sql);
    if ($consulta) {
        return $cont;
    }
    return 0;
}

function modificarContrato($parametros) {
    global $idcontrato;
    $sql = "UPDATE contrato_alquiler_vehiculo_trasporte
        SET fecha_contrato='{$parametros[0]}', 
        fecha_salida='{$parametros[1]}', fecha_retorno='{$parametros[2]}', 
        nro_dias={$parametros[3]}, nro_personas={$parametros[4]}, 
        valor={$parametros[5]}, id_cliente={$parametros[7]}, 
        id_usuario={$_SESSION['id']}, 
        fecha_modificacion='" . date('Y-m-d H:i:s') . "'
        WHERE id_contrato=$idcontrato;
        ";
    $consulta = pg_query($sql);
    if ($consulta) {
        return 1;
    }
    return 0;
}

function agregarRuta($parametros, $idcontrato) {
    $consultaid = pg_query("select max(id_ruta) from contrato_ruta");
    while ($row = pg_fetch_row($consultaid)) {
        $cont = $row[0];
    }
    $cont++;

    $sql = "INSERT INTO contrato_ruta(
        id_ruta, id_lugar_origen, id_lugar_destino, ruta_completa, comentario, 
        hora_salida, estado, id_contrato)
        VALUES ($cont, 
        $parametros[0], 
        $parametros[1], 
        '$parametros[3]', 
        '$parametros[4]', 
        '$parametros[2]', 'Activo',$idcontrato);
        ";
    $consulta = pg_query($sql);

    if ($consulta) {
        return $cont;
    }
    return 0;
}

function modificarRuta($parametros, $id_contrato) {
    $sql = "UPDATE contrato_ruta
	SET id_lugar_origen={$parametros[0]}, 
	id_lugar_destino={$parametros[1]}, ruta_completa='{$parametros[3]}', 
	comentario='{$parametros[4]}', hora_salida='{$parametros[2]}'
    WHERE id_contrato=$id_contrato;";

    $consulta = pg_query($sql);
    if ($consulta) {
        return 1;
    }
    return 0;
}

function agregarDetallesVehiculoContrato($detalles, $id_contrato) {
    $sql = "INSERT INTO contrato_alquiler_vehiculo_vehiculo(
        id_contrato, id_vehiculo, id_conductor, data_vehiculo, data_conductor) VALUES";
    foreach ($detalles as $key => $detalle) {

        $sql .= "($id_contrato, {$detalle['id_vehiculo']}, {$detalle['id_conductor']},
        '" . htmlentities($detalle['vehiculo']) . "','" . htmlentities($detalle['conductor']) . "'),";
    }
    $sql = substr($sql, 0, -1);
    $consulta = pg_query($sql);
    if ($consulta) {
        return 1;
    }
    return 0;
}

function modificarDetallesVehiculoContrato($detalles, $id_contrato) {
    $sqld = "delete from contrato_alquiler_vehiculo_vehiculo
    where id_contrato in (
    select id_contrato from contrato_alquiler_vehiculo_vehiculo
    where id_contrato=$id_contrato
    )";
    $consultad = pg_query($sqld);
    if (!$consultad) {
        return 0;
    }

    $sql = "INSERT INTO contrato_alquiler_vehiculo_vehiculo(
        id_contrato, id_vehiculo, id_conductor, data_vehiculo, data_conductor) VALUES";
    foreach ($detalles as $key => $detalle) {
        $sql .= "($id_contrato, {$detalle['id_vehiculo']}, {$detalle['id_conductor']},
        '" . htmlentities($detalle['vehiculo']) . "','" . htmlentities($detalle['conductor']) . "'),";
    }
    $sql = substr($sql, 0, -1);
    $consulta = pg_query($sql);
    if ($consulta) {
        return 1;
    }
    return 0;
}

function existeContrato($nroContrato, $estado = 'Activo') {
    $sql = "select * from contrato_alquiler_vehiculo_trasporte
    where estado='$estado' 
    and nro_contrato='$nroContrato'
    limit 1";
    $consulta = pg_query($sql);
    if (pg_num_rows($consulta) > 0) {
        return true;
    }
    return false;
}

function obtenerSiguienteNroContrato() {
    $consulta = pg_query("select max(nro_contrato) from contrato_alquiler_vehiculo_trasporte");
    while ($row = pg_fetch_row($consulta)) {
        $cont = $row[0];
    }
    $cont++;
    return $cont;
}

function transaccionNuevoContrato() {
    global $datosContrato, $datosRuta, $detallesVehiculoContrato;
    $resultado = [];
    pg_query("BEGIN;");
    $contrato = agregarContrato($datosContrato);
    $resultado = [
        "tipo" => 'contrato',
        "res" => $contrato
    ];
    if ($contrato > 0) {
        $ruta = agregarRuta($datosRuta, $contrato);
        $resultado = [
            "tipo" => 'ruta',
            "res" => $ruta
        ];
        if ($ruta > 0) {
            $detalles = agregarDetallesVehiculoContrato($detallesVehiculoContrato, $contrato);
            $resultado = [
                "tipo" => 'vehiculo_contrato',
                "res" => $detalles
            ];
            if (count($detalles) > 0) {
                pg_query("COMMIT;");
                return [
                    "tipo" => 'contrato',
                    "res" => $contrato
                ];
            }
        }
    }
    pg_query("ROLLBACK;");
    return $resultado;
}

function transaccionActualizarContrato() {
    global $datosContrato, $datosRuta, $detallesVehiculoContrato, $idcontrato;
    $resultado = [];
    pg_query("BEGIN;");
    $contrato = modificarContrato($datosContrato);
    $resultado = [
        "tipo" => 'contrato',
        "res" => $contrato
    ];
    if ($contrato > 0) {
        $ruta = modificarRuta($datosRuta, $idcontrato);
        $resultado = [
            "tipo" => 'ruta',
            "res" => $ruta
        ];

        if ($ruta > 0) {
            $detalles = modificarDetallesVehiculoContrato($detallesVehiculoContrato, $idcontrato);
            $resultado = [
                "tipo" => 'vehiculo_contrato',
                "res" => $detalles
            ];
            if (count($detalles) > 0) {
                pg_query("COMMIT;");
                return [
                    "tipo" => 'contrato',
                    "res" => $contrato
                ];
            }
        }
    }
    pg_query("ROLLBACK;");
    return $resultado;
}

function buscarPorId($id) {
    $sql = "SELECT id_contrato, fecha_contrato, fecha_salida, fecha_retorno, nro_dias, 
    nro_personas, valor, id_cliente, estado, nro_contrato, 
    id_usuario, fecha_creacion, fecha_modificacion
    FROM contrato_alquiler_vehiculo_trasporte
    WHERE id_contrato=$id
    ";

    $consulta = pg_query($sql);

    if (pg_num_rows($consulta) > 0) {
        return pg_fetch_all($consulta);
    }

    return [];
}

function eliminarContato($idcontrato) {
    $sql = "UPDATE contrato_alquiler_vehiculo_trasporte
        SET estado='Inactivo'
        WHERE id_contrato=$idcontrato;
        ";
    $consulta = pg_query($sql);
    if ($consulta) {
        return 1;
    }
    return 0;
}
