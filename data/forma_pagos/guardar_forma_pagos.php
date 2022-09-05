<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

///////////////////contador clientes////////////////////////
$cont = 0;
$consulta = pg_query("select max(id_form_pagos) from formas_pagos");
while ($row = pg_fetch_row($consulta)) {
    $cont = $row[0];
}
$cont++;
/////////////////////////////////////////////////////////

if (pg_query("insert into formas_pagos values('$cont','$_POST[moneda_form_pagos]','$_POST[tipo_form_pagos]','Activo')")) {
    $data = 1;
}

echo $data;
?>
