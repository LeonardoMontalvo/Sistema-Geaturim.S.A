<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
//$codigo_barras = $_GET["codigo_barras"];
$codigo_barras = strtoupper($_GET["codigo_barras"]);
$precio = $_GET["precio"];
$arr_data = array();

if ($codigo_barras != "") {
    $consulta = pg_query("select * from productos where (cod_barras = '$codigo_barras' or codigo='$codigo_barras') and estado = 'Activo'");
    while ($row = pg_fetch_row($consulta)) {

        $infoiva = obtenerInfoIva($row[0]);

        if ($precio == "MINORISTA") {
            $arr_data[] = strtoupper($row[1]);
            $arr_data[] = $row[3];
            $arr_data[] = $row[9];
            $arr_data[] = $row[13];
            $arr_data[] = $row[4];
            $arr_data[] = $row[5];
            $arr_data[] = $row[0];
            $arr_data[] = $row[19];
            $arr_data[] = $row[21];
            $arr_data[] = $row[26];
            $arr_data[] = $row[6];

            $arr_data[] = $infoiva["codigo_timpu"];
            $arr_data[] = $infoiva["codigo_taimpuesto"];
            $arr_data[] = $infoiva["valor"];
        } else {
            if ($precio == "MAYORISTA") {
                $arr_data[] = strtoupper($row[1]);
                $arr_data[] = $row[3];
                $arr_data[] = $row[10];
                $arr_data[] = $row[13];
                $arr_data[] = $row[4];
                $arr_data[] = $row[5];
                $arr_data[] = $row[0];
                $arr_data[] = $row[19];
                $arr_data[] = $row[21];
                $arr_data[] = $row[26];
                $arr_data[] = $row[6];

                $arr_data[] = $infoiva["codigo_timpu"];
                $arr_data[] = $infoiva["codigo_taimpuesto"];
                $arr_data[] = $infoiva["valor"];
            } else {
                if ($precio == "NEGOCIO") {
                    $arr_data[] = strtoupper($row[1]);
                    $arr_data[] = $row[3];
                    $arr_data[] = $row[27];
                    $arr_data[] = $row[13];
                    $arr_data[] = $row[4];
                    $arr_data[] = $row[5];
                    $arr_data[] = $row[0];
                    $arr_data[] = $row[19];
                    $arr_data[] = $row[21];
                    $arr_data[] = $row[26];
                    $arr_data[] = $row[6];

                    $arr_data[] = $infoiva["codigo_timpu"];
                    $arr_data[] = $infoiva["codigo_taimpuesto"];
                    $arr_data[] = $infoiva["valor"];
                }
            }
        }
    }
}

echo json_encode($arr_data);

function obtenerInfoIva($idprod)
{
    $consulta = "
    select ti.codigo_timpu,tri.codigo_taimpuesto, tri.valor
    from productos p 
    inner join tipo_impuesto ti using(id_timpu)
    inner join tarifa_impuesto tri using(id_taimpuesto) 
    where p.cod_productos=$idprod;
  ";

    $res = pg_query($consulta);
    $row = pg_fetch_assoc($res);
    if (empty($row)) {
        return [];
    }
    return $row;
}
