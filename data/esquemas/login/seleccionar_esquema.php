<?php
include_once __DIR__ . "/../../../procesos/base.php";


if (!empty($_POST["tipo"])) {
    seleccionarPorDefecto();
} else {
    seleccionar();
}


function seleccionar()
{
    if (empty($_POST["esquema"])) {
        $data = false;
    } else {
        $cookie_name = "esquema";
        $cookie_value = $_POST["esquema"];
        setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
        $data = $cookie_value;

        $esquema = obtenerEsquema($_POST["esquema"]);
        $valores = [];
        if (!empty($esquema["color"])) {
            $valores["color_esquema"] = $esquema["color"];
        }
        $cookie_name1 = "valores_app";
        setcookie($cookie_name1, json_encode($valores), time() + (86400 * 30), "/"); // 86400 = 1 day
    }
    echo json_encode($data);
}

function seleccionarPorDefecto()
{
    $sql = "select * from manejo_esquemas.esquemas where estado='Activo' and por_defecto='t'";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        $data = null;
    } else {
        $cookie_name = "esquema";
        $cookie_value =  $rows[0]["nombre"];
        setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
        $data = $cookie_value;

        $valores = [];
        if (!empty($rows[0]["color"])) {
            $valores["color_esquema"] = $rows[0]["color"];
        }
        $cookie_name1 = "valores_app";
        setcookie($cookie_name1, json_encode($valores), time() + (86400 * 30), "/"); // 86400 = 1 day
    }
    echo json_encode($data);
}

function obtenerEsquema($nombre)
{
    $sql = "select * from manejo_esquemas.esquemas where estado='Activo' and nombre='$nombre'";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    return $rows[0];
}
