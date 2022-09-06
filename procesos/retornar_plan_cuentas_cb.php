<?php

session_start();
include 'base.php';
conectarse();
$texto2 = $_GET['term'];

$consulta = pg_query("select id_plan_cuentas,
 codigo_plan,
 descripcion 
 from plan_cuentas 
 where 
 cuenta='M' 
 and descripcion ilike '%BANCO%' AND 
 ( 
 descripcion 
 ilike '%".$texto2."%'
 or codigo_plan like'$texto2%'
 ) 
 order by codigo_plan asc");
while ($row = pg_fetch_row($consulta)) {
        $data[] = array(
            'value' => $row[1]."-".$row[2],
            'id_plan_cuentas' => $row[0]
        );
}
//$x=substr($s, 0, -2);
echo $data = json_encode($data);
?>
