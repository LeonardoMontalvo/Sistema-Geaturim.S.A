<?php
include_once __DIR__ . '/../../procesos/base.php';
$conexion = conectarse();

$tipo = $_POST["tipo"];
$esquema = $_POST["esquema"];

switch ($tipo) {
    case 'quitar':
        echo json_encode(quitarEsquema($esquema));
        break;
    case 'eliminar':
        echo json_encode(eliminarEsquema($esquema));
        break;
}


function quitarEsquema($esquema)
{
    $sql = "update manejo_esquemas.esquemas 
    set estado='Pasivo' where nombre='$esquema';";
    $res = pg_query($sql);
    return !!$res;
}

function eliminarEsquema($esquema)
{
    $sql = "drop schema if exists $esquema cascade;
    delete from manejo_esquemas.esquemas where nombre='$esquema';";
    $res = pg_query($sql);
    if(!!$res){
        deleteDirectory(__DIR__."/../../atsxml/$esquema/");
        deleteDirectory(__DIR__."/../../xmls/$esquema/");
    }
    return !!$res;
}

function deleteDirectory($dir)
{
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }

    return rmdir($dir);
}
