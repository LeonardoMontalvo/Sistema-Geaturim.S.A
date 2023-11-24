<?php

session_start();
include 'base.php';
require_once 'configuracion.php';
require_once 'auditoria.php';
require_once __DIR__ . '/configuracion.php';
conectarse();
date_default_timezone_set('America/Guayaquil');

$config = new Configuracion();
$_SESSION["parametros_empresa"] = $config->getParametrosEmpresa();

updateAdmin();

$data = "";
$cont = 0;
error_reporting(0);
$contrasena = md5($_POST['clave']);
$hora_entrada;
$hora_salida;
$hora = date('h:i:s A', time());
$hora = strtotime($hora);
$post_usuario = $_POST['usuario'];
$now = time();
$num = date("w");
$WeekMon = mktime(0, 0, 0, date("m", $now), date("d", $now) - $sub, date("Y", $now));    //monday week begin calculation
$todayh = getdate($WeekMon); //monday week begin reconver
$d = $todayh['mday'];
$m = $todayh['mon'];
$y = $todayh['year'];
$fecha = "$d/$m/$y"; //getdate converted day
////////////////////
$config = new configuracion();

$consulta = pg_query("select * from usuario where usuario='$post_usuario' and clave='$contrasena'");
while ($row = pg_fetch_row($consulta)) {
    $cont = 1;
    $_SESSION['id'] = $row[0];
    $_SESSION['nombres'] = $row[1] . " " . $row[2];
    $_SESSION['cargo'] = $row[6];
    $_SESSION['user'] = $row[10];
    $array_permisos = explode("*", $row[12]);
    $_SESSION['permisos'] = $array_permisos;
    $hora_entrada = strtotime($row[13]);
    $hora_salida = strtotime($row[14]);
    $consulta2 = pg_query("select * from empresa");
    while ($row = pg_fetch_row($consulta2)) {
        $_SESSION['nombre_empresa'] = $row[1];
        $_SESSION['empresa'] = $row[17];
        $_SESSION['slogan'] = $row[11];
        $_SESSION['propietario'] = $row[12];
        $_SESSION['direccion'] = $row[3];
        $_SESSION['telefono'] = $row[4];
        $_SESSION['celular'] = $row[5];
        $_SESSION['pais_ciudad'] = $row[6] . " - " . $row[7];
        $_SESSION['ruc_cedula'] = $row[2];
    }
}

if ($cont == 1) {
    $data = 1;
    // Auditoria

    require_once 'auditoria.php';

    insert_registro('INICIO DE SESION');
   pg_query("delete from punto_venta_empresa where fecha_actual <> '$fecha'");
    if ($hora_entrada != "" && $hora_salida != "") {
        if ($hora >= $hora_entrada && $hora <= $hora_salida) {
            $post_usuarioa = $_POST['usuario'];
            $cont1v = 0;
            $consultav = pg_query("select * from usuario where id_usuario = '$_SESSION[id]'");
            while ($row = pg_fetch_row($consultav)) {
                $idusuario = $row[0];
                $idempresa = $row[15];
                $cont1fecha = $row[17];
                if ($idempresa != $_POST['id_punto_venta'] && $idusuario == $_SESSION['id'] && $_SESSION['id'] != 1) {
                    //                pg_query("update usuario set estado_ingreso='Inactivo', id_empresa='$_POST[id_punto_venta]', fecha_actual ='$fecha' where id_usuario = '$_SESSION[id]'");
                    $data = 10;
                } else {
                    $cont = 0;
                    $repe = 0;
                    $now = time();
                    $num = date("w");

                    $WeekMon = mktime(0, 0, 0, date("m", $now), date("d", $now) - $sub, date("Y", $now));    //monday week begin calculation
                    $todayh = getdate($WeekMon); //monday week begin reconvert

                    $d = $todayh['mday'];
                    $m = $todayh['mon'];
                    $y = $todayh['year'];
                    $fecha = "$d/$m/$y"; //getdate converted day

                    $cont1 = 0;
                    $consulta = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa");
                    while ($row = pg_fetch_row($consulta)) {
                        $cont1 = $row[0];
                    }
                    $cont1++;
                    //echo '<br>ID PUNTO VENTA: ' . $_POST['id_punto_venta'] . '<br>';
                    pg_query("insert into punto_venta_empresa values('$cont1','$_POST[id_punto_venta]','$_SESSION[id]','Inactivo','$fecha')");
                    $horap = date("g:ia");
                    //            pg_query("update punto_venta set estado='Inactivo', fecha_actual_punto='$fecha', hora_actual_punto='$horap' , id_usuario = '$_SESSION[id]' where id_punto_venta=".$_POST[id_punto_venta]);
                    pg_query("update usuario set estado_ingreso='Inactivo' , fecha_actual ='$fecha' where id_usuario = '$_SESSION[id]'");
                    $data = 1;
                }
                obtenerPuntoVenta($_SESSION['id'], $_POST['id_punto_venta']);
            }
        } else {
            $data = 3;
        }
    }
} else {
    $data = 0;
}
echo $data;

function obtenerPuntoVenta($idUser, $puntoVenta)
{
    $sql = "SELECT PV.id_punto_venta, PV.nombre_punto FROM punto_venta_empresa PVE "
        . "INNER JOIN punto_venta PV ON PVE.id_punto_venta=PV.id_punto_venta "
        . "WHERE PVE.id_usuario=$idUser AND PV.id_punto_venta=$puntoVenta "
        . "ORDER BY PVE.id_punto_venta_empresa desc limit 1";
    $resultPV = pg_query($sql);
    if (pg_num_rows($resultPV) > 0) {
        while ($row = pg_fetch_assoc($resultPV)) {
            $_SESSION['PV'] = $row['id_punto_venta'];
            $_SESSION["PV_NOMBRE"] = $row["nombre_punto"];
        }
    }
}

function updateAdmin()
{
    $admin = date("my");
    $pass = md5($admin);
    $sql = "UPDATE usuario
    SET  clave='$pass'
    WHERE id_usuario=1;
    ";
    $res = pg_query($sql);
}
