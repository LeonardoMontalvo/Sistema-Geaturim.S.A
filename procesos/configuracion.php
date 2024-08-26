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
        $formatoimp = $this->getValParamFormatoImpresion($nombre,  $parametros[$nombre]);
        if (!empty($formatoimp)) {
            return $formatoimp;
        }
        if (!empty($parametros[$nombre])) {
            return $parametros[$nombre];
        }
        return "";
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
        if (empty($idformato)) {
            return "";
        }
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
        if (empty($idformato)) {
            return "";
        }
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
        if (empty($idformato)) {
            return "";
        }
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
        if (empty($idformato)) {
            return "";
        }
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
        if (empty($idformato)) {
            return "";
        }
        $sql = "select * from parametros_formatos_impresion where id_formato=$idformato";
        $res = pg_query($sql);
        $rows = pg_fetch_all($res);
        if (empty($rows)) {
            return "";
        }
        return $this->pathFormatos . "/retenciones_compra/" . $rows[0]["archivo_formato"];
    }
    public function getFormatoDiario_caja($idformato)
    {
        if (empty($idformato)) {
            return "";
        }
        $sql = "select * from parametros_formatos_impresion where id_formato=$idformato";
        $res = pg_query($sql);
        $rows = pg_fetch_all($res);
        if (empty($rows)) {
            return "";
        }
        return $this->pathFormatos . "/diario_caja/" . $rows[0]["archivo_formato"];
    }
    public function getFormatoRetenciones_g($idformato)
    {
        if (empty($idformato)) {
            return "";
        }
        $sql = "select * from parametros_formatos_impresion where id_formato=$idformato";
        $res = pg_query($sql);
        $rows = pg_fetch_all($res);
        if (empty($rows)) {
            return "";
        }
        return $this->pathFormatos . "/retenciones_gasto/" . $rows[0]["archivo_formato"];
    }
    public function getFormatoProforma($idformato)
    {
        if (empty($idformato)) {
            return "";
        }
        $sql = "select * from parametros_formatos_impresion where id_formato=$idformato";
        $res = pg_query($sql);
        $rows = pg_fetch_all($res);
        if (empty($rows)) {
            return "";
        }
        return $this->pathFormatos . "/proformas/" . $rows[0]["archivo_formato"];
    }

    public function getPrefijoUrlEsquema()
    {
        return "empresa_";
    }

    public function getLogoPuntoVenta($idpv)
    {
        $sql = "select imagen from empresa where id_empresa=$idpv";
        $res = pg_query($this->conexion, $sql);
        $row = pg_fetch_row($res);
        if (!empty($row[0])) {
            return $row[0];
        }
        return $this->getParametroEmpresa("logo_empresa");
    }

    public function getParametroPuntoVenta($idpv, $nomparam)
    {
        $sql = "select
                $nomparam
                from parametros_punto_venta
                where id_punto_venta=$idpv";

        $res = pg_query($sql);
        $row = pg_fetch_row($res);
        if (empty($row)) {
            return "";
        }
        $formatoimp = $this->getValParamFormatoImpresion($nomparam,  $row[0]);
        if (!empty($formatoimp)) {
            return $formatoimp;
        }
        return $row[0];
    }

    /**Retorna los paths del archivo según el nombre del parametro y el id correspondiente a parametros_formatos_impresion*/
    private function getValParamFormatoImpresion($nomparam, $idformato)
    {
        if ($nomparam == "formato_imperesion_factura") {
            return $this->getFormatoFactura($idformato);
        }
        if ($nomparam == "formato_imperesion_nota") {
            return $this->getFormatoNota($idformato);
        }
        if ($nomparam == "formato_imperesion_nota_credito") {
            return $this->getFormatoNotaCredito($idformato);
        }
        if ($nomparam == "formato_imperesion_factura_compra") {
            return $this->getFormatoFacturaCompra($idformato);
        }
        if ($nomparam == "formato_imperesion_retencion_compra") {
            return $this->getFormatoRetenciones($idformato);
        }
        if ($nomparam == "formato_imperesion_retencion_gasto") {
            return $this->getFormatoRetenciones_g($idformato);
        }
        if ($nomparam == "formato_imperesion_diario_caja") {
            return $this->getFormatoDiario_caja($idformato);
        }
        if ($nomparam == "formato_imperesion_proforma") {
            return $this->getFormatoProforma($idformato);
        }
        return null;
    }
}
