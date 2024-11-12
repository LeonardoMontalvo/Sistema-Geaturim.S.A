<?php
session_start();
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');

require_once '../dompdf1/autoload.inc.php';

use Dompdf\Dompdf;
$nroContrato=$_GET['nro_contrato'];

$contrato;
$vehiculos;
$vehiculosPlacas='';
$vehiculosConductores='';
$timesFechaSalida;
$timesHoraSalida;
$meses = [
    'enero', 'febrero',
    'marzo', 'abril',
    'mayo', 'junio',
    'julio', 'agosto',
    'septiembre', 'octubre',
    'noviembre', 'diciembre'
];

$sqlct = "SELECT cavt.id_contrato, fecha_contrato, fecha_salida, fecha_retorno, nro_dias, 
            nro_personas, valor, nro_contrato, cr.hora_salida, cr.comentario, cr.ruta_completa,
            c.nombres_cli, c.identificacion,cl1.nombre origen,cl2.nombre destino, c.telefono, c.celular
            FROM 
            contrato_alquiler_vehiculo_trasporte cavt inner join clientes c
            on cavt.id_cliente=c.id_cliente inner join contrato_ruta cr
            on cr.id_contrato =cavt.id_contrato inner join contrato_lugar cl1
            on cr.id_lugar_origen=cl1.id_lugar inner join contrato_lugar cl2
            on cr.id_lugar_destino=cl2.id_lugar
            where cavt.nro_contrato=$nroContrato and cavt.estado='Activo'";
$consultact = pg_query($sqlct);
if ($consultact) {
    $contrato = pg_fetch_all($consultact);
    $timesFechaSalida = strtotime($contrato[0]['fecha_salida']);
    $timesHoraSalida=strtotime($contrato[0]['hora_salida']);

    $sqlv="SELECT id_contrato, cv.placa, 
	cc.nombres||' '||cc.apellidos nombre_conductor
	FROM contrato_alquiler_vehiculo_vehiculo cavv inner join contrato_conductor cc
	on cavv.id_conductor=cc.id_conductor inner join contrato_vehiculo cv
	on cavv.id_vehiculo=cv.id_vehiculo
    where id_contrato={$contrato[0]['id_contrato']}";
    $consultav = pg_query($sqlv);
    if ($consultav) {
        $vehiculos = pg_fetch_all($consultav);
        foreach($vehiculos as $key=>$ve){
            $vehiculosPlacas.=$ve['placa'].', ';
            $vehiculosConductores.=$ve['nombre_conductor'].', ';
        }
        $vehiculosPlacas=substr($vehiculosPlacas,0,-2);
        $vehiculosConductores=substr($vehiculosConductores,0,-2);
    }
}

function saldoContrato($idcontrato){
	$sql="select sum(valor) from contrato_operacion
	where id_contrato=$idcontrato and accion='a'
	and estado='Activo'
	group by accion";
	$consulta=pg_query($sql);
	if(pg_num_rows($consulta)>0){
		return pg_fetch_row($consulta)[0];
	}
	return 0;
}

ob_start();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Hoja de ruta</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman", Times, serif';
            font-size: 9pt;
        }

        body {
            height: 100%;
            width: 100%;
            padding-top: 5cm;
        }

        table {
            width: 100%;
        }

        table td:first-child {
            width: 150px;
        }

        p {
            margin-top: 0.3cm;
            margin-bottom: 0.3cm;
        }

        .main {
            margin-bottom: 2.54cm;
            margin-left: 2.54cm;
            margin-right: 1.9cm;
            text-align: justify;
        }

        .header {
            position: fixed;
            padding-top: 2.54cm;
            padding-left: 2.54cm;
            padding-right: 1.9cm;
        }
    </style>
</head>

<body>
    <div class="header">
        <table>
            <tr>
                <td>
                    <img src="<?php echo '../images/' . $_SESSION["parametros_empresa"]["logo_empresa"]?>" alt="" width="150"> 
                </td>
                <td style="text-align: right;">
                    <img src="../images/ecuador-logo.png" alt="" width="120">
                </td>
            </tr>
        </table>
    </div>
    <div class="main">
        <p style="text-align: center; font-size: 13pt">
            <b>HOJA DE RUTA</b>
        </p>
        <p style="font-size: 11pt;">
            Número Contrato: <b><?php echo $contrato[0]['nro_contrato']; ?></b>
        </p>
        <p>
            <table>
                <tr>
                    <td>FECHA DE SALIDA:</td>
                    <td><?php echo date('m-d-Y', $timesFechaSalida); ?></td>
                </tr>
                <tr>
                    <td>CONTRATISTA:</td>
                    <td>
                        <?php echo $contrato[0]['nombres_cli']; ?>
                    </td>
                    <td style="width: 150px;">TELÉFONO: <?php (empty($contrato[0]['celular'])?print($contrato[0]['telefono']):print($contrato[0]['celular'])); ?></td>
                </tr>
                <tr>
                    <td>ORIGEN:</td>
                    <td colspan="2"><?php echo $contrato[0]['origen'];?></td>
                </tr>
                <tr>
                    <td>DESTINO:</td>
                    <td colspan="2"><?php echo $contrato[0]['destino'];?></td>
                </tr>
                <tr>
                    <td>SALDO:</td>
                    <td colspan="2">$<?php $saldo = $contrato[0]['valor'] - saldoContrato($contrato[0]['id_contrato']);
                                        echo $saldo; ?></td>
                </tr>
                <tr>
                    <td>RUTA:</td>
                    <td colspan="2">
                        DÍA <?php echo date('j',$timesFechaSalida);?> DE <?php echo mb_strtoupper($meses[date('n',$timesFechaSalida)-1]);?> / 
                        <?php echo date('H\Hi',$timesHoraSalida);?> <?php echo mb_strtoupper($contrato[0]['comentario']);?><br>
                        RUTA: <?php echo $contrato[0]['ruta_completa'];?>
                    </td>
                </tr>
                <tr>
                    <td>NÚMERO DE DÍAS:</td>
                    <td colspan="2"><?php echo $contrato[0]['nro_dias']; ?></td>
                </tr>
                <tr>
                    <td>VEHÍCULOS:</td>
                    <td colspan="2"><?php echo mb_strtoupper($vehiculosPlacas);?></td>
                </tr>
                <tr>
                    <td>CONDUCTOR:</td>
                    <td colspan="2"><?php echo mb_strtoupper($vehiculosConductores);?></td>
                </tr>
                <tr>
                    <td>DINERO PARA GASTOS:</td>
                    <td colspan="2">$<?php for ($i = 0; $i < 78; $i++) {
                                            echo "_";
                                        } ?></td>
                </tr>
                <tr>
                    <td>POR COBRAR:</td>
                    <td colspan="2">$<?php for ($i = 0; $i < 78; $i++) {
                                            echo "_";
                                        } ?></td>
                </tr>
                <tr>
                    <td>TOTAL INGRESOS:</td>
                    <td colspan="2">$<?php for ($i = 0; $i < 78; $i++) {
                                            echo "_";
                                        } ?></td>
                </tr>
            </table>
        </p>
        <p style="text-align: center; font-size: 13pt; margin-top: 5cm;">
            <b>DETALLE EGRESOS</b>
        </p>
        <p>
            <table>
                <tr>
                    <td>GASTOS COMBUSTIBLE:</td>
                    <td>$<?php for ($i = 0; $i < 78; $i++) {
                                echo "_";
                            } ?></td>
                </tr>
                <tr>
                    <td>GASTOS PEAJES:</td>
                    <td>$<?php for ($i = 0; $i < 78; $i++) {
                                echo "_";
                            } ?></td>
                </tr>
                <tr>
                    <td>GASTOS ALIMENTACIÓN:</td>
                    <td>$<?php for ($i = 0; $i < 78; $i++) {
                                echo "_";
                            } ?></td>
                </tr>
                <tr>
                    <td>GASTOS HOSPEDAJE:</td>
                    <td>$<?php for ($i = 0; $i < 78; $i++) {
                                echo "_";
                            } ?></td>
                </tr>
                <tr>
                    <td>PAGO CONDUCTOR:</td>
                    <td>$<?php for ($i = 0; $i < 78; $i++) {
                                echo "_";
                            } ?></td>
                </tr>
                <tr>
                    <td>PAGO AYUDANTE:</td>
                    <td>$<?php for ($i = 0; $i < 78; $i++) {
                                echo "_";
                            } ?></td>
                </tr>
                <tr>
                    <td>VARIOS:</td>
                    <td>$<?php for ($i = 0; $i < 78; $i++) {
                                echo "_";
                            } ?></td>
                </tr>
                <tr>
                    <td>TOTAL EGRESOS:</td>
                    <td>$<?php for ($i = 0; $i < 78; $i++) {
                                echo "_";
                            } ?></td>
                </tr>
            </table>
        </p>
    </div>
    <script type="text/php">
        if ( isset($pdf) ) {
    // OLD 
    // $font = Font_Metrics::get_font("helvetica", "bold");
    // $pdf->page_text(72, 18, "{PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(255,0,0));
    // v.0.7.0 and greater
    //$x = 72;
    //$y = 810;
    $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
    //$font = $fontMetrics->get_font("helvetica", "bold_italic");
    $font = $fontMetrics->get_font("times new roman", "bold");
    $size = 8;
    $color = array(0,0,0);
    $word_space = 0.0;  //  default
    $char_space = 0.0;  //  default
    $angle = 0.0;   //  default
    $pdf->page_text(523, 820, $text, $font, $size, $color, $word_space, $char_space, $angle);
    }
</script>
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
$dompdf->stream('hojaderuta' . '.pdf', array('Attachment' => 0));
?>