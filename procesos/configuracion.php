<?php

class Configuracion
{

    private $conexion;
    private $esquema;
    private $pathFormatos = "../../reportes/formatos_impresion";
    function __construct()
    {
        require_once 'base.php';
        $this->conexion = conectarse();
        $this->esquema = $_COOKIE["esquema"];
    }

    public function getParametrosEmpresa()
    {
        $sql = "select*from parametros_empresa";
        $res = pg_query($this->conexion, $sql);
        $rows = pg_fetch_all($res);
        if (empty($rows)) {
            return [];
        }
        $params = [];
        foreach ($rows as $key => $row) {
            $params[$row["nombre_parametro"]] = $row["valor_parametro"];
        }
        return $params;
    }

    public function getParametroEmpresa($nombre)
    {
        $parametros = $this->getParametrosEmpresa();
        if ($nombre == "formato_imperesion_factura") {
            return $this->getFormatoFactura($parametros[$nombre]);
        }
        if ($nombre == "formato_imperesion_nota") {
            return $this->getFormatoNota($parametros[$nombre]);
        }
        if ($nombre == "formato_imperesion_nota_credito") {
            return $this->getFormatoNotaCredito($parametros[$nombre]);
        }
        if ($nombre == "formato_imperesion_factura_compra") {
            return $this->getFormatoFacturaCompra($parametros[$nombre]);
        }
        if ($nombre == "formato_imperesion_retencion_compra") {
            return $this->getFormatoRetenciones($parametros[$nombre]);
        }
        return $parametros[$nombre];
    }

    public function getPathXmlsFirma()
    {
        return __DIR__ . '/../xmls/' . $this->esquema . '/';
    }

    public function getPathAplicacionFIrma()
    {
        return __DIR__ . "/../firma/pruebasFE.exe";
    }

    public function getNombreEsquema()
    {
        return $this->esquema;
    }

    public function getNombreEmpresa()
    {
        $sql = "select nombre_empresa from empresa where estado='Activo'";
        $res = pg_query($this->conexion, $sql);
        if (pg_num_rows($res) > 0) {
            return pg_fetch_row($res)[0];
        }
        return "";
    }

    public function getArchivoP12()
    {
        return __DIR__ . "/../firma/" . $this->getParametroEmpresa("archivo_p12");
    }

    public function getFormatoFactura($idformato)
    {
        $sql = "select * from parametros_formatos_impresion where id_formato=$idformato";
        $res = pg_query($sql);
        $rows = pg_fetch_all($res);
        if (empty($rows)) {
            return "";
        }
        return $this->pathFormatos . "/facturas/" . $rows[0]["archivo_formato"];
    }

    public function getFormatoNota($idformato)
    {
        $sql = "select * from parametros_formatos_impresion where id_formato=$idformato";
        $res = pg_query($sql);
        $rows = pg_fetch_all($res);
        if (empty($rows)) {
            return "";
        }
        return $this->pathFormatos . "/notas_venta/" . $rows[0]["archivo_formato"];
    }

    public function getFormatoNotaCredito($idformato)
    {
        $sql = "select * from parametros_formatos_impresion where id_formato=$idformato";
        $res = pg_query($sql);
        $rows = pg_fetch_all($res);
        if (empty($rows)) {
            return "";
        }
        return $this->pathFormatos . "/notas_credito/" . $rows[0]["archivo_formato"];
    }

    public function getFormatoFacturaCompra($idformato)
    {
        $sql = "select * from parametros_formatos_impresion where id_formato=$idformato";
        $res = pg_query($sql);
        $rows = pg_fetch_all($res);
        if (empty($rows)) {
            return "";
        }
        return $this->pathFormatos . "/facturas_compra/" . $rows[0]["archivo_formato"];
    }

    public function getFormatoRetenciones($idformato)
    {
        $sql = "select * from parametros_formatos_impresion where id_formato=$idformato";
        $res = pg_query($sql);
        $rows = pg_fetch_all($res);
        if (empty($rows)) {
            return "";
        }
        return $this->pathFormatos . "/retenciones_compra/" . $rows[0]["archivo_formato"];
    }

    public function getPrefijoUrlEsquema()
    {
        return "empresa_";
    }
}
