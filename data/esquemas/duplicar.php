<?php
include_once __DIR__ . '/../../procesos/base.php';
$conexion = conectarse();

$sql = "
drop schema if exists $_POST[destino] cascade;
select manejo_esquemas.clone_schema('$_POST[origen]','$_POST[destino]',true);
--select manejo_esquemas.generar_datos_empresa_maestros('$_POST[origen]','$_POST[destino]');
";
$res = pg_query($sql);
echo 1;
