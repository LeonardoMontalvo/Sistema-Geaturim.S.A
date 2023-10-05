<?php

session_start();
include 'base.php';
conectarse();
error_reporting(0);

$fecha = date("d-m-Y");

if (!is_numeric($_POST[var5]) || !is_numeric($_POST[var6]) || !is_numeric($_POST[var7]) || !is_numeric($_POST[var8]) || !is_numeric($_POST[var9]) || !is_numeric($_POST[var10]) || !is_numeric($_POST[var11])) {
    $data = 3;
} else {
/////////////////comparar codigos/////////////////////
    $contt = 0;
    $consulta = pg_query("select * from productos where codigo='$_POST[var]'");
    while ($row = pg_fetch_row($consulta)) {
        $contt++;
    }
/////////////////////////////////////////////////////

    if ($contt == 0) {
/////////////contador productos//////////
        $cont = 0;
        $consulta = pg_query("select max(cod_productos) from productos");
        while ($row = pg_fetch_row($consulta)) {
            $cont = $row[0];
        }
        $cont++;
////////////////////////////////////////
//
/////////////////guardar productos///////
        if ($_POST[var1] == "null") {
            $_POST[var1] = "";
            $valor = number_format($_POST[var5], 2, '.', '');
            $precio_minorista = ($valor);
            $precio_mayorista = ($valor);
            pg_query("insert into productos values('$cont','$_POST[var]','$_POST[var1]','$_POST[var2]','$_POST[var3]','$_POST[var4]','$valor','$_POST[var6]','$_POST[var7]','$_POST[var6]','$_POST[var7]','1','1','$_POST[var8]','$_POST[var9]','$_POST[var10]','$fecha','1','1','$_POST[var11]','Activo','$_POST[var12]','0','0','','1','$_POST[var13]','$_POST[var14]','$_POST[var15]')");
////////////////////////////////////////
            $data = 1;
        } else {
            $valor = number_format($_POST[var5], 2, '.', '');
            $precio_minorista = ($valor);
            $precio_mayorista = ($valor);
            pg_query("insert into productos values('$cont','$_POST[var]','$_POST[var1]','$_POST[var2]','$_POST[var3]','$_POST[var4]','$valor','$_POST[var6]','$_POST[var7]','$_POST[var6]','$_POST[var7]','1','1','$_POST[var8]','$_POST[var9]','$_POST[var10]','$fecha','1','1','$_POST[var11]','Activo','$_POST[var12]','0','0','1','1','$_POST[var13]','$_POST[var14]','$_POST[var15]')");
            $data = 1;
        }
    } else {
        $data = 2;
    }
}
echo $data;
?>