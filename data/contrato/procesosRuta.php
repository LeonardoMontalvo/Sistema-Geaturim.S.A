<?php

if (!isset($_SESSION))
    session_start();
include_once '../../procesos/base.php';
conectarse();
$datos = array(
    $id_lugar_origen = !empty($_POST['id_lugar_origen']) ? $_POST['id_lugar_origen'] : 0,
    $id_lugar_destino = !empty($_POST['id_lugar_destino']) ? $_POST['id_lugar_destino'] : 0,
    $hora_salida = !empty($_POST['hora_salida']) ? $_POST['hora_salida'] : '',
    $ruta_completa = !empty($_POST['ruta_completa']) ? mb_strtoupper(str_replace("'", "''", trim($_POST['ruta_completa']))) : '',
    $comentario = !empty($_POST['comentario']) ? mb_strtoupper(str_replace("'", "''", trim($_POST['comentario']))) : '',
    $id_contrato = !empty($_POST['id_contrato']) ? $_POST['id_contrato'] : 0,
);
$operaicon = $_POST['oper'];
if (isset($operaicon) && !empty($operaicon)) {
    switch ($operaicon) {
        case 'add';
            echo agregarRuta($datos);
            break;
        case 'buscarxidcontrato':
            echo json_encode(buscarPorIdContrato($id_contrato));
            break;
    }
}

function agregarRuta($parametros) {
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
        '$parametros[2]', 'Activo',$parametros[5]);
        ";
    $consulta = pg_query($sql);

    if ($consulta) {
        return $cont;
    }
    return 0;
}

function buscarPorIdContrato($id) {
    $sql = "SELECT id_ruta, id_lugar_origen, id_lugar_destino, ruta_completa, comentario, 
	hora_salida, estado, id_contrato
	FROM contrato_ruta
	WHERE id_contrato=$id
        ";
    $consulta = pg_query($sql);
    if (pg_num_rows($consulta) > 0) {
        return pg_fetch_all($consulta);
    }
    return [];
}
