<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto = $_GET['term'];
$consulta = pg_query("select * from marcas where nombre_marca like '%$texto%' ");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[1],
        'id_marca' => $row[0],
      
       
            
   
       
    );
}

echo $data = json_encode($data);
?>
