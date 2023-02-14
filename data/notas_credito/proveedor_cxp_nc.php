<?php
function getProveedor($identificli)
{
    $cliente = getCliente($identificli);
    $sql = "select * from proveedores where identificacion_pro='$cliente[identificacion]'";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        //return [];
        $res1 = crearProveedordeCliente($identificli);
        if (empty($res1)) {
            return [];
        }
        getProveedor($identificli);
    }
    return $rows[0];
}

function getCliente($identificli)
{
    $sql = "select * from clientes where id_cliente='$identificli'";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows[0];
}

function getIdProveedor()
{
    $sql = "select max(id_proveedor) max from proveedores";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return 1;
    }
    return $rows[0]["max"] + 1;
}

function crearProveedordeCliente($idcliente)
{
    $id = getIdProveedor();
    $cliente = getCliente($idcliente);
    $sql = "
    INSERT INTO proveedores(
        id_proveedor, tipo_documento, identificacion_pro, empresa_pro, 
        representante_legal, visitador, direccion_pro, telefono, celular, 
        fax, pais, ciudad, forma_pago, correo, principal, tipo_proveedor, 
        credito_cupo, observaciones, estado, id_plan_cuentas, id_tdocu)
        VALUES ($id, '$cliente[tipo_documento]', '$cliente[identificacion]', '$cliente[nombres_cli]', 
        '$cliente[nombres_cli]', '', '$cliente[direccion_cli]', '$cliente[telefono]', '$cliente[celular]', 
        '', '$cliente[pais]', '$cliente[ciudad]', 'Contado', '$cliente[correo]', 'Si', '$cliente[tipo_cliente]', 
        '$cliente[credito_cupo]', '', 'Activo', 1, $cliente[id_tdocu]);
    ";
    $res = pg_query($sql);
    return $res;
}
