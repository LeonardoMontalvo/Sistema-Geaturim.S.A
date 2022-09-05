<?php
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();
session_start();
date_default_timezone_set('America/Guayaquil');

require_once '../dompdf1/autoload.inc.php';

use Dompdf\Dompdf;

$fechai = $_GET['inicio'];
$fechaf = $_GET['fin'];
$cliente = $_GET['id'];
$contrato = [];
$condicionid = "";
if ($_GET['id'] != "") {
    if (!empty($ciruc)) {
        $condicionid = "and c.id_cliente='$cliente'";
    }
}
$sql = "SELECT id_flete, fecha_contrato, fecha_salida, fecha_retorno,
        valor, nro_viaje,  
       id_usuario, fecha_creacion, fecha_modificacion, c.nombres_cli, c.identificacion
    FROM flete_alquiler_vehiculo_transporte cat 
    inner join clientes c 
    on cat.id_cliente = c.id_cliente
    where cat.estado='Activo' and fecha_contrato between '$fechai' and '$fechaf' $condicionid
    order by cat.fecha_creacion;
    ";
$consultacontrato = pg_query($sql);
if (pg_num_rows($consultacontrato) > 0) {
    $contrato = pg_fetch_all($consultacontrato);
}

function totalIngresos($idcontrato)
{
    $totali = 0;
    $sql = "SELECT co.id_flete, sum(co.valor)as total
  FROM flete_operacion co
  inner join flete_alquiler_vehiculo_transporte cat
  on co.id_flete=cat.id_flete
  where co.estado='Activo'
  and co.id_flete=$idcontrato
  and (co.accion='a'or co.accion='i')
  group by co.id_flete;";
    $consulta = pg_query($sql);
    if (pg_num_rows($consulta) > 0) {
        return pg_fetch_all($consulta)[0]['total'];
    }
    return $totali;
}

function vehiculosContrato($idcontrato)
{
    $vehiculos = '';
    $sql = "SELECT placa
    FROM contrato_vehiculo cv
    inner join flete_alquiler_vehiculo cav
    on cv.id_vehiculo=cav.id_vehiculo
    inner join flete_alquiler_vehiculo_transporte cat
    on cat.id_flete=cav.id_flete
    where cav.id_flete=$idcontrato
    ";
    $consulta = pg_query($sql);
    if (pg_num_rows($consulta) > 0) {
        $vehiculosr = pg_fetch_all($consulta);
        foreach ($vehiculosr as $key => $v) {
            $vehiculos .= $v['placa'] . ", ";
        }
    }
    $vehiculos = substr($vehiculos, 0, -2);
    return $vehiculos;
}

ob_start();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman", Times, serif';
            font-size: 11pt;
        }

        body {
            height: 100%;
            width: 100%;
            padding-top: 4cm;
        }

        .main {
            margin-bottom: 1cm;
            margin-left: 0.5cm;
            margin-right: 0.5cm;
            text-align: justify;
        }

        .header {
            position: fixed;
            padding-top: 0.5cm;
            padding-left: 2.54cm;
            padding-right: 1.9cm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
        }

        table th {
            border: 1px #000 solid;
        }
    </style>
</head>

<body>
    <header>
        <table class="header">
            <tr>
                <td style="width: 150px;">
                    <img src="../images/<?echo $_SESSION["parametros_empresa"]["logo_empresa"]?>" alt="" width="70">
                </td>
                <td style="text-align: left;">
                    <div style="font-size: 16; text-align: center;"><b><?php echo 'EMPRESA: ' . $_SESSION['nombre_empresa']; ?></b></div>
                    <div style="text-align: center;"><?php echo "PROPIETARIO: " . utf8_decode($_SESSION['propietario']); ?></div>
                    <div style="text-align: center;">
                        <span><?php echo "TEL.: " . utf8_decode($_SESSION['telefono']); ?></span>
                        <span><?php echo "CEL.: " . utf8_decode($_SESSION['celular']); ?></span>
                    </div>
                    <!--                    <div style="text-align: center;"><?php echo "DIR.: " . utf8_decode($_SESSION['direccion']); ?></div>
                    <div style="text-align:center;"><?php echo "SLOGAN.: " . utf8_decode($_SESSION['slogan']); ?></div>
                    <div style="text-align: center;"><?php echo utf8_decode($_SESSION['pais_ciudad']); ?></div>-->
                </td>
                <!--                <td style="width: 150px;">

                </td>-->
            </tr>
        </table>
        <div style="text-align: center;">
            <!--            <h4>LISTA DE FLETES DESDE <?php echo $fechai ?> HASTA <?php echo $fechaf ?></h4>-->
        </div>
    </header>
    <div class="main" style=" margin-top: 75px;">
        <table>
            <tr style="text-align: center;">
                <th>NRO. VIAJE</th>
                <th>ID. CLIENTE</th>
                <th>NOM. CLIENTE</th>
                <th>FEC. FLETE</th>
                <th>VEHICULOS</th>
                <th>VALOR FLETE</th>
                <th>INGRESOS</th>
            </tr>
            <?php
            $acumulado = 0;
            foreach ($contrato as $key => $val) {
            ?>
                <tr style="text-align: center;">
                    <td><?php echo $val['nro_viaje']; ?></td>
                    <td><?php echo $val['identificacion']; ?></td>
                    <td><?php echo $val['nombres_cli']; ?></td>
                    <td><?php echo $val['fecha_contrato']; ?></td>
                    <td><?php echo vehiculosContrato($val['id_flete']) ?></td>
                    <td><?php echo $val['valor']; ?></td>
                    <td><?php echo totalIngresos($val['id_flete']) ?></td>
                </tr>
            <?php
                $acumulado += totalIngresos($val['id_flete']);
            }
            ?>
            <tr>
                <td colspan="7" style="border-top: 1px #000 solid; text-align: right; font-size: 12pt;">
                    <b>TOTAL ACUMULADO INGRESOS:</b>
                </td>
                <td style="border-top: 1px #000 solid; text-align: center; font-size: 12pt;"><b><?php echo $acumulado; ?></b></td>
            </tr>
        </table>
    </div>
</body>

</html>
<?php
$html = ob_get_clean();
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
//$dompdf->setPaper(array(0, 0, 368.503937008, 549.921259843), 'landscape');
//$dompdf->setPaper(array(0, 0, 549.921259843, 368.503937008), 'portrait');
$dompdf->setPaper('a4', 'portrait');
$dompdf->set_option("isPhpEnabled", true);
$dompdf->render();
$dompdf->stream('reporte_fletes_clientes' . '.pdf', array('Attachment' => 0));
?>