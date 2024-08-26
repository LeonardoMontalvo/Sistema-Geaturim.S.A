<?php

session_start();
include '../../procesos/base.php';
conectarse();
$tipo = $_GET['tipo_precio'];
$data = [];
$producto_nombre = htmlspecialchars($_GET['articulo']);
$consulta = pg_query("select * from productos P where articulo ilike '%$producto_nombre%' limit 200");

while ($row = pg_fetch_row($consulta)) {
    $infoIva = obtenerInfoIva($row[0]);

    if ($tipo == "MINORISTA") {
        $data[] = array(
            'value' => $row[3],
            'codigo_barras' => $row[2],
            'codigo' => $row[1],
            'p_venta' => $row[9],
            'descuento' => $row[19],
            'disponibles' => $row[13],
            'des' => $row[19],
            'iva_producto' => $row[4],
            'cod_producto' => $row[0],
            'incluye' => $row[26],
            'punto_venta' => $row[33],
            'precio' => $row[6],
            "codigo_timpu" => $infoIva["codigo_timpu"],
            "codigo_taimpuesto" => $infoIva["codigo_taimpuesto"],
            "valor" => $infoIva["valor"]
        );
    } else {
        if ($tipo == "MAYORISTA") {
            $data[] = array(
                'value' => $row[3],
                'codigo_barras' => $row[2],
                'codigo' => $row[1],
                'p_venta' => $row[10],
                'descuento' => $row[19],
                'disponibles' => $row[13],
                'des' => $row[19],
                'iva_producto' => $row[4],
                'cod_producto' => $row[0],
                'incluye' => $row[26],
                'punto_venta' => $row[33],
                'precio' => $row[6],
                "codigo_timpu" => $infoIva["codigo_timpu"],
                "codigo_taimpuesto" => $infoIva["codigo_taimpuesto"],
                "valor" => $infoIva["valor"]
            );
        } else {
            if ($tipo == "NEGOCIO") {
                $data[] = array(
                    'value' => $row[3],
                    'codigo_barras' => $row[2],
                    'codigo' => $row[1],
                    'p_venta' => $row[27],
                    'descuento' => $row[19],
                    'disponibles' => $row[13],
                    'des' => $row[19],
                    'iva_producto' => $row[4],
                    'cod_producto' => $row[0],
                    'incluye' => $row[26],
                    'punto_venta' => $row[33],
                    'precio' => $row[6],
                    "codigo_timpu" => $infoIva["codigo_timpu"],
                    "codigo_taimpuesto" => $infoIva["codigo_taimpuesto"],
                    "valor" => $infoIva["valor"]
                );
            }
        }
    }
}

echo $data = json_encode($data);

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
