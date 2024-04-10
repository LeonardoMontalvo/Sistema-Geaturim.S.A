<?php
session_start();

guardarArchivosCarpetaTmp();
echo json_encode(obtenerInfoArchivos());

function guardarArchivosCarpetaTmp()
{
    if (count($_FILES)) {
        $zip = new ZipArchive;
        $file = $_FILES["file"]["tmp_name"];
        $res = $zip->open($file);
        if ($res === TRUE) {
            $hash = hash_file('md5', $file);
            $zip->extractTo(sys_get_temp_dir() . "/$hash");
            $zip->close();
            $_SESSION["dirtmp_facturas_xml"] = sys_get_temp_dir() . "/$hash";
        } else {
            echo 'failed, code:' . $res;
            die();
        };
    }
}

function obtenerInfoArchivos()
{
    $dir = new DirectoryIterator($_SESSION["dirtmp_facturas_xml"]);
    $data = [];
    foreach ($dir as $key => $value) {
        if (!$value->isDot()) {
            $_FILES = [];
            require_once __DIR__ . "/../../procesos/obtener_factura_autorizada.php";
            $factura = UtilXml::obtenerDetallesFacturaArchivoXml($value->getRealPath());
            $infofac = $factura['infoFac'];

            //procesar solo facturas
            if ($infofac["codDoc"] != "01") {
                continue;
            }
            array_push($data, [
                $value->getFilename(),
                "Factura",
                "$infofac[estab]-$infofac[ptoEmi]-$infofac[secuencial]",
                $infofac['identificacionComprador'],
                $infofac['ruc'],
                $infofac['razonSocial'],
                $infofac['fechaEmision'],
                $infofac['claveAcceso'],
                $infofac['importeTotal'],
            ]);
        }
    }
    return $data;
}
