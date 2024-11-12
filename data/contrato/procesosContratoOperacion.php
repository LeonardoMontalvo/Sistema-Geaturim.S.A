<?php

session_start();
include_once '../../procesos/base.php';
conectarse();
date_default_timezone_set('America/Guayaquil');

$idcontrato = !empty($_POST['id_contrato']) ? $_POST['id_contrato'] : 0;
$idoperacion = !empty($_POST['id_operacion']) ? $_POST['id_operacion'] : 0;


$tipodocumento = !empty($_POST['tipo_documento']) ? $_POST['tipo_documento'] : '';
$nrodocumento = !empty($_POST['nro_documento']) ? $_POST['nro_documento'] : '';
$valor = !empty($_POST['valor_o']) ? $_POST['valor_o'] : 0;
$accion = !empty($_POST['accion']) ? $_POST['accion'] : '';
$descripcion = !empty($_POST['descripcion']) ? mb_strtoupper(str_replace("'", "''", trim($_POST['descripcion']))) : '';

$operaicon = $_POST['oper'];
if (isset($operaicon) && !empty($operaicon)) {
    switch ($operaicon) {
        case "add":
            echo agregarOperacion();
            break;
        case "del":
            echo eliminarOperacion();
            break;
        case "totaloperaciones";
            echo json_encode(totalOperaciones());
            break;
    }
}

function agregarOperacion() {
    global $idcontrato,
    $tipodocumento,
    $nrodocumento,
    $valor, $accion,
    $descripcion;

    if (empty($idcontrato)) {
        return -1;
    }
    $consultaid = pg_query("select max(id_operacion) from contrato_operacion");
    while ($row = pg_fetch_row($consultaid)) {
        $cont = $row[0];
    }
    $cont++;
    $sql = "INSERT INTO contrato_operacion(
            id_operacion, tipo_documento, nro_documento, valor, accion, descripcion, 
            fecha_creacion, fecha_modificacion, id_contrato, estado)
            VALUES ($cont, '{$tipodocumento}', '{$nrodocumento}', {$valor}, '{$accion}', '{$descripcion}', 
            '" . date('Y-m-d H:i:s') . "', '" . date('Y-m-d H:i:s') . "', $idcontrato, 'Activo');
            ";
    $consulta = pg_query($sql);
    if ($consulta) {
        return $cont;
    }
    return 0;
}

function eliminarOperacion() {
    global $idoperacion;
    if (empty($idoperacion)) {
        return -1;
    }
    $sql = "UPDATE contrato_operacion SET estado='Inactivo', fecha_modificacion='" . date('Y-m-d H:i:s') . "' where id_operacion=$idoperacion";
    $consulta = pg_query($sql);
    if ($consulta) {
        return $idoperacion;
    }
    return 0;
}

function totalOperaciones() {
    global $idcontrato;


    $ingresos = 0;
    $abonos = 0;
    $egresos = 0;

    $sqli = "select sum(valor) as ingresos from contrato_operacion 
            where estado='Activo' and accion='i' and id_contrato=$idcontrato group by accion";
    $sqla = "select sum(valor) from contrato_operacion 
            where estado='Activo' and accion='a' and id_contrato=$idcontrato group by accion;";
    $sqle = "select sum(valor) from contrato_operacion 
            where estado='Activo' and accion='e' and id_contrato=$idcontrato group by accion;";
    $consultai = pg_query($sqli);
    $consultaa = pg_query($sqla);
    $consultae = pg_query($sqle);
    if (pg_num_rows($consultai) > 0) {
        $ingresos = pg_fetch_row($consultai)[0];
    }
    if (pg_num_rows($consultaa) > 0) {
        $abonos = pg_fetch_row($consultaa)[0];
    }
    if (pg_num_rows($consultae) > 0) {
        $egresos = pg_fetch_row($consultae)[0];
    }
    return array('ingresos' => $ingresos, 'abonos' => $abonos, 'egresos' => $egresos);
}
