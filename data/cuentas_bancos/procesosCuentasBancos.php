<?php

session_start();
include '../../procesos/base.php';
conectarse();
$cont = 0;
$repe = 0;

//////////////////validar repetidos//////////////////
$consulta = pg_query("select * from cuentas_bancos where numero_cuenta='" .$_POST['numero_cuenta']. "'");
while ($row = pg_fetch_row($consulta)) {
    $repe++;
}
///////////////////////////////////////////////

if ($_POST['oper'] == "add") {
    $consulta = pg_query("select max(id_cuenta_banco) from cuentas_bancos");
    while ($row = pg_fetch_row($consulta)) {
        $cont = $row[0];
    }
    $cont++;

    if ($repe == 0) {
        pg_query("insert into cuentas_bancos values('$cont', '".$_POST['numero_cuenta']."', '$_POST[banco]', '$_POST[cuenta]', 'Activo')");
    }
} else {
    if ($_POST['oper'] == "edit") {
        //if ($repe == 0) {
            pg_query("update cuentas_bancos set numero_cuenta='" . $_POST['numero_cuenta'] . "' , id_banco='$_POST[banco]',  id_plan_cuentas='$_POST[cuenta]', estado='Activo' where id_cuenta_banco='$_POST[id_cuenta_banco]'");
        //}
    }
}
?>
