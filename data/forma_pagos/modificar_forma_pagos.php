<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

/////////////////modificar clientes////////////////////
if (pg_query("Update formas_pagos Set moneda_form_pagos='$_POST[moneda_form_pagos]', tipo_form_pagos='$_POST[tipo_form_pagos]', estado_form_pagos='Activo' where id_form_pagos='$_POST[id_form_pagos]'")){

$data = 1;	
}
//////////////////////////////////////////////////////

echo $data;
?>
