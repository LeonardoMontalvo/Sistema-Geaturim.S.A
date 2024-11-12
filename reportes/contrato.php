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
$nroVehiculos;
$timesFechaContrato;
$timesFechaSalida;
$timesFechaRetorno;

$meses = [
    'enero', 'febrero',
    'marzo', 'abril',
    'mayo', 'junio',
    'julio', 'agosto',
    'septiembre', 'octubre',
    'noviembre', 'diciembre'
];

$sqlct = "SELECT cavt.id_contrato, fecha_contrato, fecha_salida, fecha_retorno, nro_dias, 
            nro_personas, valor, nro_contrato,
            c.nombres_cli, c.identificacion,cl1.nombre origen,cl2.nombre destino, cr.ruta_completa
            FROM 
            contrato_alquiler_vehiculo_trasporte cavt inner join clientes c
            on cavt.id_cliente=c.id_cliente inner join contrato_ruta cr
            on cr.id_contrato =cavt.id_contrato inner join contrato_lugar cl1
            on cr.id_lugar_origen=cl1.id_lugar inner join contrato_lugar cl2
            on cr.id_lugar_destino=cl2.id_lugar
            where cavt.nro_contrato=$nroContrato and cavt.estado='Activo'";
//var_dump($sqlct);
$consultact = pg_query($sqlct);
if ($consultact) {
    $contrato = pg_fetch_all($consultact);
    $timesFechaContrato = strtotime($contrato[0]['fecha_contrato']);
    $timesFechaSalida = strtotime($contrato[0]['fecha_salida']);
    $timesFechaRetorno = strtotime($contrato[0]['fecha_retorno']);

    $sqlv = "SELECT count(*) FROM contrato_alquiler_vehiculo_vehiculo where id_contrato={$contrato[0]['id_contrato']};";
    $consultav = pg_query($sqlv);
    if ($consultav) {
        $nroVehiculos = pg_fetch_row($consultav)[0];
    }
}

ob_start();
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Contrato</title>
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
                padding-top: 5cm;
            }
            .main{
                margin-bottom: 2.54cm;
                margin-left: 2.54cm; 
                margin-right: 1.9cm;
                text-align: justify;
            }
            .header{
                position: fixed;
                padding-top: 2.54cm; 
                padding-left: 2.54cm; 
                padding-right: 1.9cm;
            }
            .subtitulo{
                font-size: 10pt;
            }
            .firma{
                margin-top: 3cm;
            }
            table{
                width: 100%;
            }
            .firma>table tr{
                text-align: center;
            }
            .firma>table td{
                word-wrap: break-word;
            }
            p{
                margin-top: 0.3cm;
                margin-bottom: 0.3cm;
            }
            ul{
                list-style: none;
                padding-left: 0;
            }
            li{
                margin-bottom: 0.3cm;
            }
            li::before{
                content: "-"
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
                <b>COMPAÑÍA DE TRANSPORTE TURISTICO GEATURIM S.A</b>
            </p>
            <p class="subtitulo"><b>CONTRATO DE TRANSPORTE.</b></p>
            <p>Número Contrato: <?php echo $contrato[0]['nro_contrato'] ?></p>
            <p>
                En la ciudad de IBARRA a los <?php echo date('j', $timesFechaContrato); ?> días del mes de <?php echo mb_strtoupper($meses[date('n', $timesFechaContrato) - 1]); ?> del <?php echo date('Y', $timesFechaContrato); ?>, se otorga el presente
                contrato entre el Sr. BRILMO MONTALVO FRANCO con número de identificación 100178888-2 como
                representante legal de la Empresa GEATURIM S.A y <?php echo mb_strtoupper($contrato[0]['nombres_cli']); ?> con
                número de identificación <?php echo mb_strtoupper($contrato[0]['identificacion']); ?> celebran el presente contrato de Alquiler de Vehículo de
                Transporte Turístico con Servicio de Chofer Profesional, al tenor de las siguientes cláusulas.
            </p>
            <p class="subtitulo"><b>PRIMERA.- COMPARECINETES</b></p>
            <p>
                Comparecen a la celebración del presente contrato por una parte BRILMO MONTALVO. A quien en
                adelante y para efectos de este contrato se le denominará "TRANSPORTISTA" y <?php echo mb_strtoupper($contrato[0]['nombres_cli']); ?> A quien en adelante y para efectos de este contrato se le denominará 
                "CONTRATISTA". 
            </p>
            <p class="subtitulo"><b>SEGUNDA.- ANTECEDENTES.</b></p>
            <p>
                El CONTRATISTA requiere <?php echo $nroVehiculos; ?> vehículo(s) de transporte turístico para el traslado de <?php echo $contrato[0]['nro_personas']; ?> personas
                aproximadamente. Para realizar el viaje: <?php echo $contrato[0]['ruta_completa']; ?> y viceversa. El viaje se llevará a
                cabo el <?php echo date('d-m-Y', $timesFechaSalida); ?> y el retorno será el <?php echo date('d-m-Y', $timesFechaRetorno); ?>.
            </p>
            <p class="subtitulo"><b>TERCERA.- OBJETO DEL CONTRATO.</b></p>
            <p>
                Con los antecedentes expuestos El TRANSPORTISTA renta a favor del CONTRATISTA <?php echo $nroVehiculos; ?> vehículo(s) de
                transporte turístico en óptimas condiciones, con servicio de chofer profesional, y/o ayudante a fin de
                que conduzca en forma exclusiva el vehículo de servicio turístico, para transportar a <?php echo $contrato[0]['nro_personas']; ?> personas
                aproximadamente.
            </p>
            <p class="subtitulo"><b>CUARTA.- RUTA O RECORRIDO.</b></p>
            <p>
                Las partes convienen en que el vehículo de transporte turístico alquilado descrito en la cláusula
                anterior realicen el recorrido bajo las actividades previstas para el efecto. El vehículo no podrá ir a
                otros lugares distintos de los estipulados en esta cláusula a no ser que el CONTRATISTA pague en
                forma adicional al TRANSPORTISTA el costo del tramo o lugar increnentado en la ruta o recorrido
                contratado.
            </p>
            <p class="subtitulo"><b>QUINTA.- CAMBIO DE RUTA O RECORRIDO.</b></p>
            <p>
                El CONTRATISTA podrá cambiar la ruta o recorrido siempre y cuando de aviso al TRANSPORTISTA
                en un plazo no menor a 48 horas antes del inicio del viaje.
            </p>
            <p class="subtitulo"><b>SEXTA.- UTILIZACIÓN DE VIAS Y VELOCIDAD.</b></p>
            <p>
                Las partes acuerdan que para el desplazamiento del vehículos alquilado, el chofer profesional lo
                conducirá por la autopista panamericana o vía completamente habilitada a una velocidad dentro de los
                límites de la ley.
            </p>
            <div style="page-break-after: always;"></div>
            <p><b>SÉPTIMA.- OBLIGACIONES.</b></p>
            <p>
                <b>Obligaciones del TRANSPORTISTA:</b>
            <ul>
                <li>
                    Suministro para la ruta o recorrido que requiere realizar el CONTRATISTA un vehículo en perfecto
                    estado de funcionamiento.
                </li>
                <li>
                    Proveer para la conducción del vehículo alquilado choferes profesionales.
                </li>
                <li>
                    Abastecer a los vehículos durante la ruta o recorrido contratado de combustible y lubricantes las veces
                    que sea necesario.
                </li>
            </ul>
            <b>Obligaciones del CONTRATISTA:</b>
            <ul>
                <li>
                    Realizar la cancelación total del servicio, previo la salida del recorrido en la fecha antes mencionada.
                </li>
                <li>
                    Dar la alimentación y hospedaje para el conductor y ayudante. Aplica{} No aplica{}
                </li>
                <li>
                    Cuidar de sus objetos o pertenencias de valor, la compañía no se responsabilizará de nigún artículo o 
                    dinero cuyo valor no haya sido declarado o informado al conductor.
                </li>
                <li>
                    Cuidar del buen uso de asientos y demás accesorios del vehículo, caso de presentarse algún 
                    inconveniente, tales como roturas, quemaduras de asientos, el CONTRATISTA pagará o repondrá el
                    valor correspondiente.
                </li>
            </ul>
        </p>
        <p><b>OCTAVA.- PROHIBICIÓN PARA EL CONTRATISTA.</b></p>
        <p>
            Está prohibido para el CONTRATISTA y a sus acompañantes de viaje:
        <ul>
            <li>
                Fumar e ingerir alcohol en el interior del vehículo alquilado, durante todo el trayecto de la ruta o
                recorrido contratado.
            </li>
            <li>
                Distraer al chofer profesional durante el viaje.
            </li>
            <li>
                Utilizar el vehículo alquilado para usos distintos al estipulado.
            </li>
            <li>
                Dañar, romper asientos y demás accesorios del vehículo, en caso de presentarse este inconveniente el
                CONTRATISTA deberá cancelar el valor que el daño ocacione.
            </li>
        </ul>
    </p>
    <p><b>NOVENA.- PRECIO Y FORMA DE PAGO.</b></p>
    <p>
        El valor convenido entre las partes es de $. <?php echo $contrato[0]['valor']; ?> dólares de los Estados Unidos de América, por <?php echo $contrato[0]['nro_dias'] ?> días
        de recorrido.
    </p>
    <p><b>DECIMA.- CANCELACIONES.</b></p>
    <p>
        El CONTRATISTA no podrá Suspender el viaje, o cambiar de fecha y en caso de hacerlo pagará al 
        TRANSPORTISTA una multa equivalente al 50% del valor del anticipo por motivo de incumplimiento
        del presente contrato.
    </p>
    <div style="page-break-after: always"></div>
    <p><b>DECIMA PRIMERA.- PROHIBICIÓN DE SESIÓN DE CONTRATO.</b></p>
    <p>
        Las partes de común acuerdo, determinan los derechos adquiridos por este contrato con el recorrido
        establecido.
    </p>
    <p><b>DECIMA SEGUNDA.- JURISDICCIÓN.</b></p>
    <p>
        En caso de controversia o incumplimiento las partes se someterán a la jurisdicción y competencia de
        los Jueces de los Civil de Imbabura, siguiendo el trámite verbal sumario.
    </p>
    <br>
    <p>
        Para constancia y conformidad en todo lo estipulado en este documento el Sr. BRILMO MONTALVO Y 
        <?php echo mb_strtoupper($contrato[0]['nombres_cli']); ?>, firman el presente Contrato en un original y copia de 
        igual tenor en la ciudad y fecha al principio indicados.
    </p>
    <div class="firma">
        <table>
            <tr>
                <td>_______________________</td>
                <td>_______________________</td>
            </tr>
            <tr>
                <td>
                    Sr. BRILMO MONTALVO
                </td>
                <td>
                    <?php echo mb_strtoupper($contrato[0]['nombres_cli']); ?>
                </td>
            </tr>
            <tr>
                <td>GERENTE GEATURIM S.A</td>
                <td>CONTRATISTA</td>
            </tr>
        </table>
    </div>
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
$dompdf->stream('contrato' . '.pdf', array('Attachment' => 0));
?>