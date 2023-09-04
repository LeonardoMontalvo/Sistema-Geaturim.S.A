<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$data = "";


date_default_timezone_set('America/Guayaquil');

$fecha = date('Y-m-d H:i:s', time());
$total_diario_caja=0;
$apertura=0;

$sql1 = pg_query(
        
        
        "SELECT nombre_empresa, ruc_empresa,u.usuario, t.fecha_actual, t.hora_actual,total_debe
    FROM   empresa e, usuario u ,transacciones t
    WHERE    t.id_usuario= u.id_usuario and identificador_cli_pro='AUD'  and concepto like '%INICIO%' and t.fecha_actual BETWEEN '$fecha' AND '$fecha'   order by id_transacciones desc limit 1
"
);



while ($fila = pg_fetch_row($sql1)) {
    $apertura = $fila[5]; 
}

    $data = $data . $apertura;
   

////////////////////////////////
echo $data;
?>
