<?php
require_once __DIR__ . "/../../procesos/configuracion.php";

function cabeceraRide(
    &$pdf,
    $razonsocial,
    $dirmatriz,
    $obligado,
    $contrespecial,
    $nombredoc,
    $rucempresa,
    $numdoc,
    $numautorizacion,
    $ambiente,
    $emision,
    $fechaaut,
    $claveacceso,
    $logoempresa,
    $dirsucursal,
    $cellheight
) {


    $config = new Configuracion();

    $valrimpe = $config->getParametroEmpresa("val_rimpe");

    $totalw = $pdf->GetCurrentWidth();
    $halfw = $totalw / 2;
    //imagen
    if (!empty($logoempresa)) {
        $pdf->Image($logoempresa, 30, 9, 35);
    }

    $pdf->SetXY(0, 50);

    //información factura
    $pdf->SetX(($halfw) + 2);
    $pdf->SetY(2);

    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell($halfw, $cellheight, "", 0, 0);
    $pdf->Cell($halfw, $cellheight, "RUC: " . $rucempresa, 0, 1);

    $pdf->Cell($halfw, $cellheight, "", 0, 0);
    $pdf->MultiCell($halfw, $cellheight, utf8_decode($nombredoc), 0);

    $pdf->Ln(1);
    $pdf->SetFont('Arial', '', 9);

    $pdf->Cell($halfw, $cellheight, "", 0, 0);
    $pdf->Cell($halfw, $cellheight, "Nro. " . $numdoc, 0, 1);

    $pdf->Cell($halfw, $cellheight, "", 0, 0);
    $pdf->Cell($halfw, $cellheight, utf8_decode("Número de Autorización:"), 0, 1);

    $pdf->Cell($halfw, $cellheight, "", 0, 0);
    $pdf->Cell($halfw, $cellheight, $numautorizacion, 0, 1);

    $pdf->Cell($halfw, $cellheight, "", 0, 0);
    $pdf->Cell($halfw, $cellheight, utf8_decode("Ambiente: " . mb_strtoupper(getAmbiente($ambiente))), 0, 1);

    $pdf->Cell($halfw, $cellheight, "", 0, 0);
    $pdf->Cell($halfw, $cellheight, utf8_decode("Emisión: " . mb_strtoupper(getTipoEmision($emision))), 0, 1);

    $pdf->Cell($halfw, $cellheight, "", 0, 0);
    $pdf->Cell($halfw, $cellheight, utf8_decode("Fecha y Hora de Autorización: " . $fechaaut), 0, 1);

    $pdf->Cell($halfw, $cellheight, "", 0, 0);
    $pdf->Cell($halfw, $cellheight, utf8_decode("Clave de Acceso: "), 0, 1);

    new barCodeGenrator($claveacceso, 1, 'temp.gif', 470, 60, true); /// img codigo barras	
    $pdf->Image('temp.gif', ($halfw) + 3, $pdf->GetY(), 96, 15);

    //información empresa
    $pdf->SetXY(2, 45);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->MultiCell($halfw, $cellheight, $razonsocial);
    $pdf->Ln(1);
    $pdf->SetFont('Arial', '', 9);
    $pdf->MultiCell($halfw, $cellheight, "Dir. Matriz: " . utf8_decode($dirmatriz));
    if (!empty($dirsucursal)) {
        $pdf->MultiCell($halfw, $cellheight, "Dir. Sucursal: " . utf8_decode($dirsucursal));
    }
    $pdf->Cell($halfw, $cellheight, "Obligado a llevar contabilidad: $obligado", 0, 1);
    if (!empty($contrespecial)) {
        $pdf->Cell($halfw, $cellheight, "Contribuyente especial: $contrespecial", 0, 1);
    }

    $pdf->Cell($halfw, $cellheight, utf8_decode($valrimpe), 0, 1);

    $pdf->Ln(3);


    //lìnea divisora
    $pdf->Line(2, $pdf->GetY(), $totalw + 2, $pdf->GetY());
    $pdf->Ln(2);
}

function getAmbiente($idambiente)
{

    $consulta_ambiente = pg_query("select nombre_ambi from ambiente where  estado_ambi='Activo'");
    $ambiente = "";
    while ($row = pg_fetch_row($consulta_ambiente)) {
        $ambiente = $row[0];
    }
    return $ambiente;
}
function getTipoEmision($idtipoemi)
{
    $consulta_emision = pg_query("select nombre_temision from tipo_emision where id_temision=$idtipoemi");
    $emision = "";
    while ($row = pg_fetch_row($consulta_emision)) {
        $emision = $row[0];
    }
    return $emision;
}
