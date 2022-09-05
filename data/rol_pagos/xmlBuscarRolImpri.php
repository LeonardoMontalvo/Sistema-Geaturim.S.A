<?php

session_start();
include '../../procesos/base.php';
conectarse();
$data = "";
$consulta = pg_query("
select DISTINCT rol_pagos.id_rol_pagos, mes,anio,total,rol_pagos.fecha_actual from rol_pagos,empleado,usuario,empresa,detalle_rol where  detalle_rol.id_empleado=empleado.id_empleado and rol_pagos.id_usuario=usuario.id_usuario and rol_pagos.estado = 'Activo'  and  rol_pagos.id_empresa='1' and rol_pagos.mes='$_GET[select_mes]'  and rol_pagos.anio='$_GET[anio]' and empleado.estado='Activo' ");
while ($row = pg_fetch_row($consulta)) {
    $data = $data . $row[0];
    $data = $data . '*' . $row[1];
   
}
////////////////////////////////
echo $data;
?>
