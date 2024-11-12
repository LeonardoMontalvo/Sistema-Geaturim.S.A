<?php

if (!isset($_SESSION))
    session_start();
include_once '../../procesos/base.php';
conectarse();

$id_contrato = !empty($_POST['id_contrato']) ? $_POST['id_contrato'] : 0;
$detalles = !empty($_POST['detalles']) ? $_POST['detalles'] : [];

$operaicon = $_POST['oper'];
if (isset($operaicon) && !empty($operaicon)) {
    switch ($operaicon) {
        case 'add';
            echo json_encode(agregarDetallesVehiculoContrato($id_contrato, $detalles));
            break;
        case 'buscarxidcontrato':
            echo json_encode(buscarPorIdContrato($id_contrato));
            break;
    }
}

function agregarDetallesVehiculoContrato($id_contrato, $detalles) {
    $respuesta = [];
    foreach ($detalles as $key => $detalle) {
        $consultaid = pg_query("select max(id_contrato_vehiculo) from contrato_alquiler_vehiculo_vehiculo");
        while ($row = pg_fetch_row($consultaid)) {
            $cont = $row[0];
        }
        $cont++;

        $sql = "INSERT INTO contrato_alquiler_vehiculo_vehiculo(
        id_contrato_vehiculo, id_contrato, id_vehiculo, id_conductor)
        VALUES ($cont, $id_contrato, {$detalle['id_vehiculo']}, {$detalle['id_conductor']});
        ";

        $consulta = pg_query($sql);

        if ($consulta) {
            array_push($respuesta, $cont);
        } else {
            array_push($respuesta, 0);
        }
    }
    return $respuesta;
}

function buscarPorIdContrato($id) {
    $sql = "SELECT id_contrato, id_vehiculo, id_conductor, data_vehiculo, data_conductor
	FROM contrato_alquiler_vehiculo_vehiculo
    WHERE id_contrato=$id";

    $consulta = pg_query($sql);

    if (pg_num_rows($consulta) > 0) {
        return pg_fetch_all($consulta);
    }
    return [];
}
