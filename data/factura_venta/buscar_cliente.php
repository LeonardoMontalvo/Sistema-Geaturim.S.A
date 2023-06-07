<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto = $_GET['term'];
$consulta = pg_query("select identificacion,id_cliente,nombres_cli,direccion_cli,telefono ,correo,nombre_vendedor,vendedores.id_vendedor
from clientes inner join rutas on rutas.id_ruta=clientes.credito_cupo
left join vendedores on vendedores.id_vendedor=rutas.id_vendedor
where clientes.estado='Activo' and rutas.estado='Activo' and identificacion like '%$texto%' limit 200");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[0],
        'id_cliente' => $row[1],
        'nombre_cliente' => $row[2],
        'direccion_cliente' => $row[3],
        'telefono_cliente' => $row[4],
        'correo' => $row[5],
         'nombre_vendedor' => $row[6],
         'id_vendedor' => $row[7]
    );
}

echo $data = json_encode($data);
?>
