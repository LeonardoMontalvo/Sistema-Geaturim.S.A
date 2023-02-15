<?php

session_start();
include '../../procesos/base.php';
conectarse();
$data = "";
$cont = 0;
 $id_plan_cuenta = $_POST['id_plan'];
    if ($_POST['id'] != "" && $_POST['f1'] != "" && $_POST['f2'] != "") {
        $plan_cuenta = trim($_POST['id']);
        $id_plan_cuenta = $_POST['id_plan'];

//        echo '::'."SELECT DISTINCT ON (dc.id_conciliacion)c.id_conciliacion,descripcion,fecha_inicio, 
//       fecha_fin,usuario
//  FROM conciliacion c,plan_cuentas pc, detalle_conciliacion dc, usuario u
//  where  
//  dc.id_conciliacion=c.id_conciliacion 
//  and c.id_plan_cuentas=pc.id_plan_cuentas   
//    and c.id_usuario=u.id_usuario   
//    and c.fecha_inicio = '$_POST[f1]' and fecha_fin ='$_POST[f2]'
//   and c.id_plan_cuentas ='$id_plan_cuenta'
//  and c.estado='Activo'  
//   group by dc.id_conciliacion,pc.id_plan_cuentas,dc.id_detalle_conciliacion ,c.id_conciliacion,usuario  
//    ORDER BY  dc.id_conciliacion desc";
//        
        
        
$consulta = pg_query("SELECT DISTINCT ON (dc.id_conciliacion)c.id_conciliacion,descripcion,fecha_inicio, 
       fecha_fin,usuario
  FROM conciliacion c,plan_cuentas pc, detalle_conciliacion dc, usuario u
  where  
  dc.id_conciliacion=c.id_conciliacion 
  and c.id_plan_cuentas=pc.id_plan_cuentas   
    and c.id_usuario=u.id_usuario   
    and c.fecha_inicio = '$_POST[f1]' and fecha_fin ='$_POST[f2]'
   and c.id_plan_cuentas ='$id_plan_cuenta'
  and c.estado='Activo'  
   group by dc.id_conciliacion,pc.id_plan_cuentas,dc.id_detalle_conciliacion ,c.id_conciliacion,usuario  
    ORDER BY  dc.id_conciliacion desc");
    }
while ($row = pg_fetch_row($consulta)) {
    $cont = $row[0];
}
if ($cont != "") {
    $data = $cont;
} else {
    $data = 0;
}
echo $data;
?>
