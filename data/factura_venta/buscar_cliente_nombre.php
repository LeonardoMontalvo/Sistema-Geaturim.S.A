<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto = strtoupper($_GET['term']);
$consulta = pg_query("select identificacion,id_cliente,nombres_cli,direccion_cli,telefono ,correo,nombre_vendedor,vendedores.id_vendedor
from clientes inner join rutas on rutas.id_ruta=clientes.credito_cupo
left join vendedores on vendedores.id_vendedor=rutas.id_vendedor
where clientes.estado='Activo' and rutas.estado='Activo' and nombres_cli like '%$texto%'");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[2],
        'id_cliente' => $row[1],
        'ruc_ci' => $row[0],
        'direccion_cliente' => $row[3],
        'telefono_cliente' => $row[4],
        'correo' => $row[5],
         'nombre_vendedor' => $row[6],
         'id_vendedor' => $row[7]
    );
}
echo $data = json_encode($data);
?>
