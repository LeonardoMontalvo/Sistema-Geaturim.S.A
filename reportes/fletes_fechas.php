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
// $placa = mb_strtoupper(trim($_GET['placa']));
$condicion = "";
$titulo = "LISTA FLETES GENERAL";
// if(!empty($placa)){
//     $titulo="LISTA FLETES VEHÍCULO $placa";
//     $condicion="and upper(cv.placa)=upper('$placa')";
// }
$contratos = [];
$sql = "SELECT cv.placa,c.nombres_cli,cr.ruta_completa,c.identificacion,cat.*
    FROM contrato_vehiculo cv
    inner join flete_alquiler_vehiculo cav
    on cv.id_vehiculo=cav.id_vehiculo
    inner join flete_alquiler_vehiculo_transporte cat
    on cat.id_flete=cav.id_flete
    inner join clientes c
    on cat.id_cliente=c.id_cliente
    inner join flete_ruta cr
    on cr.id_flete=cat.id_flete
    where cat.fecha_contrato between '$fechai' and '$fechaf'
    $condicion order by cat.fecha_creacion;";
$consultacontrato = pg_query($sql);
if (pg_num_rows($consultacontrato)) {
    $contratos = pg_fetch_all($consultacontrato);
}

function ingresosEgresosContrato($idcontrato) {
    $ingresos = [];
    $egresos = [];
    $sql = "select descripcion, valor, accion 
from flete_operacion
where id_flete=$idcontrato 
and estado='Activo';";
    $consultaop = pg_query($sql);
    if (pg_num_rows($consultaop) > 1) {
        $res = pg_fetch_all($consultaop);
        foreach ($res as $key => $val) {
            if ($val['accion'] == 'a' || $val['accion'] == 'i') {
                array_push($ingresos, $val);
            } else if ($val['accion'] == 'e') {
                array_push($egresos, $val);
            }
        }
    }
    return [$ingresos, $egresos];
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
                padding-top: 2cm;
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
                padding-left: 0.0cm; 
                padding-right: 1.9cm;
            }

            table{
                width: 100%;
                border-collapse: collapse;
                font-size: 10pt;
            }
            .main table td,th{
                border: 1px #000 solid;
            }
        </style>
    </head>
    <body>
        <header>
        <table class="header">
                <tr>
                    <td style="width: 150px; padding-left:30px">                       
                        <img src="../images/<?echo $_SESSION["parametros_empresa"]["logo_empresa"]?>" alt="" width="70">
                    </td>
                    <td style="text-align: center;">
                        <div style="font-size: 12; text-align: center;"><b><?php echo 'EMPRESA: ' . $_SESSION['nombre_empresa']; ?></b></div>
                        <div style="text-align: center;"><?php echo "PROPIETARIO: " . utf8_decode($_SESSION['propietario']); ?></div>
                        <div style="text-align: center;">
                            <span><?php echo "TEL.: " . utf8_decode($_SESSION['telefono']); ?></span>
                            <span><?php echo "CEL.: " . utf8_decode($_SESSION['celular']); ?></span>
                        </div>
<!--                        <div style="text-align: center;"><?php echo "DIR.: " . utf8_decode($_SESSION['direccion']); ?></div>
                        <div style="text-align:center;"><?php echo "SLOGAN.: " . utf8_decode($_SESSION['slogan']); ?></div>
                        <div style="text-align: center;"><?php echo utf8_decode($_SESSION['pais_ciudad']); ?></div>
                        <div style="text-align: center;"><?php echo $titulo; ?> DESDE <?php echo $fechai ?> HASTA <?php echo $fechaf ?></div>-->
                    </td>
                    <td style="width: 150px;">

                    </td>
                </tr>
            </table>
            <!--            <div style="text-align: center; margin-top: 2px;">
                            <h4><?php echo $titulo; ?> DESDE <?php echo $fechai ?> HASTA <?php echo $fechaf ?></h4>
                        </div>-->
         </header>
        <div class="main" style=" margin-top: 100px;">
            <?php foreach ($contratos as $key => $val) { ?>
                <div style=" margin-top: 25px;"></div>
                <table>
                    <tr style="background-color: #c1d5e0;">
                        <td colspan="3"><b>NRO. VIAJE:</b> <?php echo $val['nro_viaje'] ?></td>
                        <td colspan="3"><b>VEHICULO:</b> <?php echo $val['placa'] ?></td>
                    </tr>
                    <tr>
                        <td colspan="3"><b>IDENTIFICACIÓN CLIENTE:</b> <?php echo $val['identificacion'] ?></td>
                        <td colspan="3"><b>NOMBRE CLIENTE:</b> <?php echo $val['nombres_cli'] ?></td>
                    </tr>
                    <tr>
                        <td colspan="3"><b>FECHA FLETE:</b> <?php echo $val['fecha_contrato'] ?></td>
                        <td colspan="3"><b>VALOR FLETE:</b> <?php echo $val['valor'] ?></td>
                    </tr>
                    <tr>
                        <td colspan="3"><b>FECHA SALIDA:</b> <?php echo $val['fecha_salida'] ?></td>
                        <td colspan="3"><b>RUTA:</b> <?php echo $val['ruta_completa'] ?></td>
                    </tr>
                </table>
                <table>
                    <tr style="background-color: #c1d5e0;">
                        <th style="text-align: center; border-bottom: #c1d5e0;" colspan="2"><b>INGRESOS</b></td>
                        <th style="text-align: center; border-bottom: #c1d5e0;" colspan="2"><b>EGRESOS</b></td>
                    </tr>
                    <tr style="background-color: #c1d5e0;">
                        <td style="text-align: center;"><b>DESCRIPCIÓN</b></td>
                        <td style="text-align: center;"><b>VALOR</b></td>
                        <td style="text-align: center;"><b>DESCRIPCIÓN</b></td>
                        <td style="text-align: center;"><b>VALOR</b></td>
                    </tr>
                    <?php
                    $count = 0;
                    $ingegr = ingresosEgresosContrato($val['id_flete']);
                    if (count($ingegr[0]) > count($ingegr[1])) {
                        $count = count($ingegr[0]);
                    }
                    if (count($ingegr[0]) < count($ingegr[1])) {
                        $count = count($ingegr[1]);
                    }
                    if (count($ingegr[0]) == count($ingegr[1])) {
                        $count = count($ingegr[1]);
                    }
                    if ($count > 0) {
                        $totali = 0;
                        $totale = 0;
                        $totalie=0;
                        for ($i = 0; $i < $count; $i++) {
                            ?>
                            <tr>
                                <?php
                                if (count($ingegr[0]) > $i) {
                                    echo "<td>" . $ingegr[0][$i]['descripcion'] . "</td>";
                                    echo "<td>" . $ingegr[0][$i]['valor'] . "</td>";
                                    $totali += $ingegr[0][$i]['valor'];
                                } else {
                                    echo "<td></td>";
                                    echo "<td></td>";
                                }
                                if (count($ingegr[1]) > $i) {
                                    echo "<td>" . $ingegr[1][$i]['descripcion'] . "</td>";
                                    echo "<td>" . $ingegr[1][$i]['valor'] . "</td>";
                                    $totale += $ingegr[1][$i]['valor'];
                                   
                                } else {
                                    echo "<td></td>";
                                    echo "<td></td>";
                                }
                                 $totalie=$totali-$totale;
                                ?>
                            </tr>
                            <?php
                        }
                        echo "<tr style='background-color: #c1d5e0;'><td style='text-align:right'><b>TOTAL INGRESOS:</b></td><td><b>$totali</b></td><td style='text-align:right'><b>TOTAL EGRESOS:</b></td><td><b>$totale</b></td></tr>";
                          echo "<td><td ><td  style='background-color: #c1d5e0;'><b >TOTAL INGRESO - EGRESO:</b></td><td style='background-color: #c1d5e0;'><b>$totalie</b></td></tr></td></tr>";
                    }
                    ?>
                </table>
                <?php } ?>
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
$dompdf->stream('reporte_flete_fechas' . '.pdf', array('Attachment' => 0));
?>