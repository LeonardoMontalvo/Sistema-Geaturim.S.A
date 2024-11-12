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

$contrato = [];
$sql = "SELECT id_contrato, fecha_contrato, fecha_salida, fecha_retorno, nro_dias, 
       nro_personas, valor, nro_contrato,  
       id_usuario, fecha_creacion, fecha_modificacion, c.nombres_cli, c.identificacion
    FROM contrato_alquiler_vehiculo_trasporte cat 
    inner join clientes c 
    on cat.id_cliente = c.id_cliente
    where cat.estado='Activo' and fecha_contrato between '$fechai' and '$fechaf'
    order by cat.fecha_creacion;
    ";
$consultacontrato = pg_query($sql);
if (pg_num_rows($consultacontrato) > 0) {
    $contrato = pg_fetch_all($consultacontrato);
}

////////////////////////////////////////////////////////CREDIFE Y BANCO PICHINCHA
$total_credifesum = 0;
$total_bancosum = 0;
$total_credife = [];
$sql = "select num_factura,factura_venta.fecha_actual,hora_actual,fecha_cancelacion,"
        . "tipo_precio,forma_pago,tarifa0,tarifa12,iva_venta,descuento_venta,"
        . "total_venta,identificacion,nombres_cli,nombre_empresa,"
        . "id_factura_venta,factura_venta.estado from factura_venta,"
        . " clientes,empresa,usuario where factura_venta.id_cliente=clientes.id_cliente"
        . " and factura_venta.id_empresa=empresa.id_empresa and usuario.id_usuario=factura_venta.id_usuario "
        . " and factura_venta.fecha_actual between '$fechai' and '$fechaf'
            and nombres_cli='CREDIFE S.A' order by factura_venta.id_factura_venta asc;
    ";
$consultatotal_credife = pg_query($sql);
if (pg_num_rows($consultatotal_credife) > 0) {

    $total_credife = pg_fetch_all($consultatotal_credife);
}
foreach ($total_credife as $key => $val1) {


    if ($val1['estado'] == "Activo") {
        $total_credifesum = $total_credifesum + $val1['total_venta'];
    }
}
////
$total_banco = [];
$sql = "select num_factura,factura_venta.fecha_actual,hora_actual,fecha_cancelacion,"
        . "tipo_precio,forma_pago,tarifa0,tarifa12,iva_venta,descuento_venta,"
        . "total_venta,identificacion,nombres_cli,nombre_empresa,"
        . "id_factura_venta,factura_venta.estado from factura_venta,"
        . " clientes,empresa,usuario where factura_venta.id_cliente=clientes.id_cliente"
        . " and factura_venta.id_empresa=empresa.id_empresa and usuario.id_usuario=factura_venta.id_usuario "
        . " and factura_venta.fecha_actual between '$fechai' and '$fechaf'
            and nombres_cli='BANCO PICHINCHA C.A' order by factura_venta.id_factura_venta asc;
    ";
$consultatotal_banco = pg_query($sql);
if (pg_num_rows($consultatotal_banco) > 0) {

    $total_banco = pg_fetch_all($consultatotal_banco);
}
foreach ($total_banco as $key => $val11) {


    if ($val11['estado'] == "Activo") {
        $total_bancosum = $total_bancosum + $val11['total_venta'];
    }
}
$sum_total=0;
$sum_total_por=0;
$sum_total=$total_credifesum+$total_bancosum;
$sum_total_por=$sum_total*9/100;
//echo 'ee'.$sum_total_por;
/////////////////////////////////////////////////////////////////////////////////////

function totalIngresos($idcontrato) {
    $totali = 0;
    $sql = "SELECT co.id_contrato, sum(co.valor)as total
  FROM contrato_operacion co
  inner join contrato_alquiler_vehiculo_trasporte cat
  on co.id_contrato=cat.id_contrato
  where co.estado='Activo'
  and co.id_contrato=$idcontrato
  and (co.accion='a'or co.accion='i')
  group by co.id_contrato;";
    $consulta = pg_query($sql);
    if (pg_num_rows($consulta) > 0) {
        return pg_fetch_all($consulta)[0]['total'];
    }
    return $totali;
}

function totalAbonos($idcontrato) {
    $totala = 0;
    $sql = "SELECT co.id_contrato, sum(co.valor)as total
  FROM contrato_operacion co
  inner join contrato_alquiler_vehiculo_trasporte cat
  on co.id_contrato=cat.id_contrato
  where co.estado='Activo'
  and co.id_contrato=$idcontrato
  and co.accion='a'
  group by co.id_contrato;";
    $consulta = pg_query($sql);
    if (pg_num_rows($consulta) > 0) {
        return pg_fetch_all($consulta)[0]['total'];
    }
    return $totala;
}

function totalEgresos($idcontrato) {
    $totale = 0;
    $sql = "SELECT co.id_contrato, sum(co.valor)as total
  FROM contrato_operacion co
  inner join contrato_alquiler_vehiculo_trasporte cat
  on co.id_contrato=cat.id_contrato
  where co.estado='Activo'
  and co.id_contrato=$idcontrato
  and co.accion='e'
  group by co.id_contrato;";
    $consulta = pg_query($sql);
    if (pg_num_rows($consulta) > 0) {
        return pg_fetch_all($consulta)[0]['total'];
    }
    return $totale;
}

function vehiculosContrato($idcontrato) {
    $vehiculos = '';
    $sql = "SELECT placa
    FROM contrato_vehiculo cv
    inner join contrato_alquiler_vehiculo_vehiculo cav
    on cv.id_vehiculo=cav.id_vehiculo
    inner join contrato_alquiler_vehiculo_trasporte cat
    on cat.id_contrato=cav.id_contrato
    where cav.id_contrato=$idcontrato
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
            body{
                height: 100%;
                width: 100%;
                padding-top: 5.5cm;
            }
            .main{
                margin-bottom: 1cm;
                margin-left: 0.5cm; 
                margin-right: 0.5cm;
                text-align: justify;
            }
            .header{
                position: fixed;
                padding-top: 0.5cm; 
                padding-left: 2.54cm; 
                padding-right: 1.9cm;
            }

            table{
                width: 100%;
                border-collapse: collapse;
                font-size: 10pt;
            }
            table th{
                border: 1px #000 solid;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <table>
                <tr>
                    <td style="width: 150px;">                       
                        <img src="<?php echo '../images/' . $_SESSION["parametros_empresa"]["logo_empresa"]?>" alt="" width="150">
                    </td>
                    <td style="text-align: left;">
                        <div style="font-size: 16; text-align: center;"><b><?php echo 'EMPRESA: ' . $_SESSION['empresa']; ?></b></div>
                        <div style="text-align: center;"><?php echo "PROPIETARIO: " . utf8_decode($_SESSION['propietario']); ?></div>
                        <div style="text-align: center;">
                            <span><?php echo "TEL.: " . utf8_decode($_SESSION['telefono']); ?></span>
                            <span><?php echo "CEL.: " . utf8_decode($_SESSION['celular']); ?></span>
                        </div>
                        <div style="text-align: center;"><?php echo "DIR.: " . utf8_decode($_SESSION['direccion']); ?></div>
                        <div style="text-align:center;"><?php echo "SLOGAN.: " . utf8_decode($_SESSION['slogan']); ?></div>
                        <div style="text-align: center;"><?php echo utf8_decode($_SESSION['pais_ciudad']); ?></div>  
                    </td>
                    <td style="width: 150px;">

                    </td>
                </tr>
            </table>
            <div style="text-align: center;">
                <h4>LISTA DE CONTRATOS DESDE <?php echo $fechai ?> HASTA <?php echo $fechaf ?></h4>
            </div>
        </div>
        <div class="main">
            <table>
                <tr style="text-align: center;">
                    <th>NRO_CONTRATO</th>
                    <th>NOM_CLIENTE</th>
                    <th>IDENTIFICACIÓN</th>
                    <th>FECHA_CONTRATO</th>
                    <th>DÍAS</th>
                    <th>VEHICULOS</th>
                    <th>VALOR_CONTRATO</th>
                    <th>INGRESOS</th>
                    <th>EGRESOS</th>
                    <th>FALTANTE</th>
                    <th>UTILIDAD</th>
                </tr>
<?php
$acumulado = 0;
$acumuladoi = 0;
$acumuladoe = 0;
$acumuladof = 0;
$total_total=0;
$total_sum=0;
foreach ($contrato as $key => $val) {
    ?>
                    <tr style="text-align: left;">
                        <td><?php echo $val['nro_contrato']; ?></td>
                        <td><?php echo $val['identificacion']; ?></td>
                        <td><?php echo $val['nombres_cli']; ?></td>
                        <td><?php echo $val['fecha_contrato']; ?></td>
                        <td><?php echo $val['nro_dias'] ?></td>
                        <td><?php echo vehiculosContrato($val['id_contrato']) ?></td>
                        <td><?php echo $val['valor']; ?></td>
                        <td><?php echo totalIngresos($val['id_contrato']) ?></td>
                        <td><?php echo totalEgresos($val['id_contrato']) ?></td>
                        <td><?php echo $val['valor'] - totalAbonos($val['id_contrato']) ?></td>
                        <td><?php echo totalIngresos($val['id_contrato']) - totalEgresos($val['id_contrato']) ?></td>
                    </tr>
    <?php
    $acumuladoi += totalIngresos($val['id_contrato']);
    $acumuladoe += totalEgresos($val['id_contrato']);
    $acumuladof += ($val['valor'] - totalAbonos($val['id_contrato']));
    //$acumulado += (totalIngresos($val['id_contrato']) - totalEgresos($val['id_contrato']));
    
    $total_total=$acumuladoi - $acumuladoe;
    $total_sum=$sum_total_por+$total_total;
}
?>
                <tr>
                    <td colspan="7" style="border-top: 1px #000 solid; text-align: right; font-size: 12pt;">
                        <b>TOTAL ACUMULADO:</b>
                    </td>
                    <td style="border-top: 1px #000 solid; text-align: center; font-size: 12pt;"><b><?php echo $acumuladoi; ?></b></td>
                    <td style="border-top: 1px #000 solid; text-align: center; font-size: 12pt;"><b><?php echo $acumuladoe; ?></b></td>
                    <td style="border-top: 1px #000 solid; text-align: center; font-size: 12pt;"><b><?php echo $acumuladof; ?></b></td>
                    <td style="border-top: 1px #000 solid; text-align: center; font-size: 12pt;"><b><?php echo $acumuladoi - $acumuladoe; ?></b></td>


                </tr>
                <tr>
                    <td colspan="5" style="border-top: 1px #000 solid; text-align: right; font-size: 12pt;">
                        <b> TOTAL CREDIFE:</b>
                    </td>
                    <td style="border-top: 1px #000 solid; text-align: left; font-size: 12pt;"><b><?php echo number_format($total_credifesum, 2, ',', '.'); ?></b></td>

                    <td colspan="2" style="border-top: 1px #000 solid; text-align: left; font-size: 12pt;">
                        <b>TOTAL BANCO:</b>
                    </td>
                    <td style="border-top: 1px #000 solid; text-align: left; font-size: 12pt;"><b><?php echo number_format($total_bancosum, 2, ',', '.'); ?></b></td>

                </tr>
                  <tr>
                    <td colspan="5" style="border-top: 1px #000 solid; text-align: right; font-size: 12pt;">
                        <b> TOTAL + 9%==>: <?php echo number_format($sum_total_por, 2, ',', '.')."  "."TOTAL  " ?></b>
                    </td>
                    <td style="border-top: 1px #000 solid; text-align: left; font-size: 12pt;"><b><?php echo number_format($total_sum, 2, ',', '.')  ?></b></td>

                    
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
$dompdf->setPaper('a4', 'landscape');
$dompdf->set_option("isPhpEnabled", true);
$dompdf->render();
$dompdf->stream('reporte_contratos_utilidad' . '.pdf', array('Attachment' => 0));
?>