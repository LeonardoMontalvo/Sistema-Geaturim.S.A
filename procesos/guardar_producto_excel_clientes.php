<?php

session_start();
include 'base.php';
conectarse();
error_reporting(0);

$fecha = date("d-m-Y");

//if (!is_numeric($_POST[var1])) {
//    $data = 3;
//} else {
/////////////////comparar codigos/////////////////////


    $contt = 0;
    $consulta = pg_query("select * from clientes where nombres_cli='$_POST[var1]'");
    while ($row = pg_fetch_row($consulta)) {
        $contt++;
    }
/////////////////////////////////////////////////////

    if ($contt == 0) {
/////////////contador productos//////////
        $cont = 0;
        $consulta = pg_query("select max(id_cliente) from clientes");
        while ($row = pg_fetch_row($consulta)) {
            $cont = $row[0];
        }
        $cont++;



        if (strlen($_POST['var']) == 9) {
            $tipIdent = "Cedula";
            $idIdentif = 2;
            $_POST['var'] = '0' . $_POST['var'];
        }
        if (strlen($_POST['var']) == 13) {
            $tipIdent = "Ruc";
            $idIdentif = 1;
        }
        if (strlen($_POST['var']) == 10) {
            $tipIdent = "Cedula";
            $idIdentif = 2;
        }
          if (strlen($_POST['var']) == 12) {
            $tipIdent = "Ruc";
            $idIdentif = 1;
            $_POST['var'] = '0' . $_POST['var'];
        }
        
    $variable=    pg_query("INSERT INTO clientes VALUES ('$cont', '$tipIdent', '$_POST[var]', '$_POST[var1]', 'Persona Natural', '$_POST[var2]', '', '$_POST[var3]', 'ECUADOR', 'IBARRA','$_POST[var4]', '1', '', 'Activo', 1, $idIdentif);");
      
        
        if($variable==false)
        {
            echo '' . "INSERT INTO clientes VALUES ('$cont', '$tipIdent', '$_POST[var]', '$_POST[var1]', 'Persona Natural', '$_POST[var2]', '', '$_POST[var3]', 'ECUADOR', 'IBARRA','$_POST[var4]', '1', '', 'Activo', 1, $idIdentif);.\n";
            exit();
        }
        
        
        $data = 1;
//        }
    } else {
         echo '' . "INSERT INTO clientes VALUES ('$cont', '$tipIdent', '$_POST[var]', '$_POST[var1]', 'Persona Natural', '$_POST[var2]', '', '$_POST[var3]', 'ECUADOR', 'IBARRA','$_POST[var4]', '1', '', 'Activo', 1, $idIdentif);.\n";
        $data = 2;
    }
//}
echo $data;
?>