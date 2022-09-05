<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['id'];

$sql="
select
re.*,
pt.id_proforma,
cl.id_cliente,
cl.identificacion,
cl.nombres_cli
from 
registro_equipo re
left join proforma_tecnico pt
on pt.id_registro=re.id_registro,
clientes cl
where re.id_cliente=cl.id_cliente
and re.id_registro=$id
";
$res=pg_query($sql);
$rows=pg_fetch_all($res);
if(!$rows){
    echo json_encode([]);
}else{
    echo json_encode($rows[0]);
}