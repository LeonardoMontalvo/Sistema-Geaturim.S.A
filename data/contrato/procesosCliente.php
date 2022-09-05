<?php

session_start();
include '../../procesos/base.php';
conectarse();
$cont = 0;

$idcliente = !empty($_POST['id_cliente']) ? $_POST['id_cliente'] : 0;

$operaicon = $_POST['oper'];
if (isset($operaicon) && !empty($operaicon)) {
    switch ($operaicon) {
        case 'buscartodopaginado';
            echo json_encode(buscarTodoPaginado());
            break;
        case 'buscarxid':
            echo json_encode(buscarPorId($idcliente));
            break;
    }
}

function buscarTodoPaginado()
{
    $texto = $_POST['search'];
    $pagina = $_POST['page'];
    $offset = 10 * ($pagina - 1);

    $consultaTotal = pg_query("select count(*) from clientes
    where estado='Activo' and (lower(nombres_cli) like lower('%$texto%') or identificacion like '%$texto%')");

    $totalRegistros = pg_fetch_row($consultaTotal)[0];

    $sql = "select*from clientes
    where estado='Activo' and (lower(nombres_cli) like lower('%$texto%') or identificacion like '%$texto%')
    limit 10 offset " . $offset;

    $consulta = pg_query($sql);

    if (pg_num_rows($consulta) > 0) {
        return array('registros' => pg_fetch_all($consulta), 'totalRegistros' => $totalRegistros);
    }
    return array('registros' => [], 'totalRegistros' => 0);
}

function buscarPorId($id)
{
    $sql = "SELECT id_cliente, tipo_documento, identificacion, nombres_cli, tipo_cliente, 
    direccion_cli, telefono, celular, pais, ciudad, correo, credito_cupo, 
    notas, estado, id_plan_cuentas, id_tdocu
    FROM clientes
    WHERE	 id_cliente=$id;
    ";

    $consulta = pg_query($sql);

    if (pg_num_rows($consulta) > 0) {
        return pg_fetch_all($consulta);
    }
    return [];
}
