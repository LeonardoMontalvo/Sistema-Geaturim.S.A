
<?php

session_start();
include '../../procesos/base.php';
conectarse();

$consulta = pg_query("SELECT valor
  FROM tarifa_impuesto where id_taimpuesto='$_POST[id]' 
");
$row = pg_fetch_row($consulta);
echo $row[0];
?>

