<?php
session_start();
include '../../procesos/base.php';
conectarse();

//$sql = "select * from categoria where estado='Activo' order by nombre_categoria asc";
$sql="
select c.* from categoria c
inner join productos p
on p.id_categoria=c.id_categoria
where c.estado='Activo' 
group  by c.id_categoria
order by nombre_categoria asc
";
$res = pg_query($sql);
$rows = pg_fetch_all($res);
if (empty($rows)) {
    $rows = [];
}
echo json_encode($rows);
