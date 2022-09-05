<?php
class UtilJsonFile
{
    //cargar datos de $filename
    public static function cargarJson(&$array, $filename)
    {
        if (!file_exists($filename)) {
            $file = fopen($filename, "w") or die("Unable to open file!");
        } else {
            $file = fopen($filename, "r") or die("Unable to open file!");
            $datos = fread($file, filesize($filename));
            fclose($file);
            $array = json_decode($datos, true);
        }
    }

    //guardar datos en $filename
    public static function guardarDatos($datos, $filename)
    {
        $file = fopen($filename, "w") or die("Unable to open file!");
        fwrite($file, json_encode($datos));
        fclose($file);
    }
}
