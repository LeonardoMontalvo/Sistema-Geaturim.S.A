<?php
require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();
class PDF extends FPDF
{
    var $widths;
    var $aligns;
    function SetWidths($w)
    {
        $this->widths = $w;
    }
}

class Reporte
{
    private $pdf;
    public function __construct()
    {
        $this->pdf = new PDF('P', 'mm', 'a4');
        $this->pdf->SetAutoPageBreak(false, 0);
        $this->pdf->AddPage();
        $this->pdf->SetMargins(0, 0, 0, 0);
        $this->pdf->AliasNbPages();
        $this->pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->pdf->SetFont('Amble-Regular', '', 10);
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetX(5);
        $this->pdf->SetFont('Amble-Regular', '', 9);

        $this->pdf->SetY(3);
        $this->pdf->SetX(3);
    }
    public function cabecera($para)
    {
        $fecha = date('Y-m-d', time());
        $empresa = $_SESSION['nombre_empresa'];
        $logo = "../images/".$_SESSION["parametros_empresa"]["logo_empresa"];
        $propietario = utf8_decode($_SESSION['propietario']);
        $telefono = utf8_decode($_SESSION['telefono']);
        $celular = utf8_decode($_SESSION['celular']);
        $pais = utf8_decode($_SESSION['pais_ciudad']);
        $direccion = utf8_decode($_SESSION['direccion']);
        $slogan = utf8_decode($_SESSION['slogan']);

        $this->pdf->Cell(20, 4, $fecha, 0, 0, 'C', 0);
        $this->pdf->Cell(180, 4, "$para", 0, 1, 'R', 0);
        $this->pdf->SetFont('Arial', 'B', 14);
        $this->pdf->Cell(210, 10, $empresa, 0, 1, 'C', 0);
        $this->pdf->Image($logo, 5, $this->pdf->GetY()-8, 50, 16);
        $this->pdf->SetFont('Amble-Regular', '', 10);
        $this->pdf->Cell(210, 4, "PROPIETARIO: " . maxCaracter($propietario, 55), 0, 1, 'C', 0);
        $this->pdf->Cell(105, 4, "TEL.: " . $telefono, 0, 0, 'R', 0);
        $this->pdf->Cell(105, 4, "CEL.: " . maxCaracter($celular, 15), 0, 0, 'L', 0);
        $this->pdf->Ln(4);
        $this->pdf->Cell(210, 4, "DIR.: " . $direccion, 0, 1, 'C', 0);
        $this->pdf->Cell(210, 4, maxCaracter($slogan, 60), 0, 1, 'C', 0);
        $this->pdf->Cell(210, 4, $pais, 0, 1, 'C', 0);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetLineWidth(0.4);
        $this->pdf->Ln(2);
        $this->pdf->Line(1, $this->pdf->GetY() - 1, 297, $this->pdf->GetY() - 1);
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->Cell(210, 5, utf8_decode("FORMULARIO DE INGRESO"), 0, 1, 'C', 0);
        $this->pdf->SetFont('Amble-Regular', '', 9);
    }

    public function datosIngreso()
    {
        $sql = "
        select * from registro_equipo,
        color,
        marcas,
        clientes,
        usuario,
        tipo_equipo where registro_equipo.id_color=color.id_color and
        registro_equipo.id_marca=marcas.id_marca and
        registro_equipo.id_cliente=clientes.id_cliente and
        registro_equipo.id_usuario=usuario.id_usuario and
        registro_equipo.id_tipo_equipo=tipo_equipo.id_tipo_equipo and
        registro_equipo.id_registro=$_GET[id]
        ";
        $sql = pg_query($sql);
        $rows = pg_fetch_all($sql);
        if ($rows) {
            foreach ($rows as $row) {
                $this->pdf->Ln(1);
                $this->pdf->SetX(3);
                $this->pdf->Cell(30, 5, "REGISTRO NRO.: ", 0, 0, 'L', 0);
                $this->pdf->Cell(90, 5, maxCaracter(utf8_decode($row["id_registro"]), 10), 0, 0, 'L', 0);
                $this->pdf->Cell(30, 5, "FECHA ENTRADA: ", 0, 0, 'L', 0);
                $this->pdf->Cell(52, 5, maxCaracter(utf8_decode($row["fecha_ingreso"]), 10), 0, 1, 'L', 0);

                $this->pdf->SetX(3);
                $this->pdf->Cell(30, 5, "CLIENTE.: ", 0, 0, 'L', 0);
                $this->pdf->Cell(90, 5, maxCaracter(utf8_decode($row["nombres_cli"]) . "(" . $row["identificacion"] . ")", 40), 0, 0, 'L', 0);
                $this->pdf->Cell(30, 5, "FECHA SALIDA: ", 0, 0, 'L', 0);
                $this->pdf->Cell(52, 5, maxCaracter(utf8_decode($row["fecha_salida"]), 10), 0, 1, 'L', 0);

                $this->pdf->SetX(3);
                $this->pdf->Cell(30, 5, "TEL.: ", 0, 0, 'L', 0);
                $this->pdf->Cell(90, 5, maxCaracter(utf8_decode($row["telefono"]), 40), 0, 0, 'L', 0);
                $this->pdf->Cell(30, 5, "MARCA: ", 0, 0, 'L', 0);
                $this->pdf->Cell(52, 5, maxCaracter(utf8_decode($row["nombre_marca"]), 40), 0, 1, 'L', 0);

                $this->pdf->SetX(3);
                $this->pdf->Cell(30, 5, "CEL.: ", 0, 0, 'L', 0);
                $this->pdf->Cell(90, 5, maxCaracter(utf8_decode($row["celular"]), 40), 0, 0, 'L', 0);
                $this->pdf->Cell(30, 5, "NRO SERIE: ", 0, 0, 'L', 0);
                $this->pdf->Cell(52, 5, maxCaracter(utf8_decode($row["nro_serie"]), 40), 0, 1, 'L', 0);

                $this->pdf->SetX(3);
                $this->pdf->Cell(30, 5, "DIR..: ", 0, 0, 'L', 0);
                $this->pdf->Cell(90, 5, maxCaracter(utf8_decode($row["direccion_cli"]), 40), 0, 0, 'L', 0);
                $this->pdf->Cell(30, 5, "MODELO.: ", 0, 0, 'L', 0);
                $this->pdf->Cell(52, 5, maxCaracter(utf8_decode($row["modelo"]), 40), 0, 1, 'L', 0);

                $this->pdf->SetX(3);
                $this->pdf->Cell(30, 5, "TIPO EQUIPO.: ", 0, 0, 'L', 0);
                $this->pdf->Cell(90, 5, maxCaracter(utf8_decode($row["descripcion"]), 40), 0, 0, 'L', 0);
                $this->pdf->Cell(30, 5, "COLOR.: ", 0, 0, 'L', 0);
                $this->pdf->Cell(52, 5, maxCaracter(utf8_decode($row["nombre_color"]), 40), 0, 1, 'L', 0);

                $this->pdf->SetX(3);
                $this->pdf->Cell(30, 5, "REGISTRADO.: ", 0, 0, 'L', 0);
                $this->pdf->Cell(90, 5, maxCaracter($row["nombre_usuario"] . "(" . $row["ci_usuario"] . ")", 40), 0, 0, 'L', 0);
                $this->pdf->Cell(30, 5, "TEL/CEL.: ", 0, 0, 'L', 0);
                $this->pdf->Cell(52, 5, maxCaracter($row["telefono_usuario"] . "/" . $row["celular_usuario"], 22), 0, 1, 'L', 0);

                $this->pdf->Ln(2);
                $this->pdf->SetX(3);
                $this->pdf->Cell(30, 5, "ACCESORIOS: ", 0, 0, 'L', 0);
                $this->pdf->SetX(33);
                $this->pdf->MultiCell(170, 4, maxCaracter(utf8_decode($row["detalles"]), 280), 0, 'L', 0);

                $this->pdf->Ln(5);
                $this->pdf->SetX(3);
                $this->pdf->Cell(30, 5, "OBSERVACIONES: ", 0, 1, 'L', 0);

                $this->pdf->SetY($this->pdf->GetY() - 4);
                $this->pdf->SetX(33);

                $this->pdf->MultiCell(170, 4, maxCaracter(utf8_decode($row["observaciones"]), 280), 0, 'L', 0);
            }
            $this->pdf->Line(1, $this->pdf->GetY(), 297, $this->pdf->GetY());
        }
    }

    public function pie()
    {
        $this->pdf->SetX(3);
        $this->pdf->Cell(30, 5, "NOTAS: ", 0, 1, 'L', 0);
        $this->pdf->SetX(33);
        $this->pdf->MultiCell(170, 4, maxCaracter(utf8_decode("El diagnóstico del equipo tiene un precio mínimo de $10.00 (Diez Dólares) \nToda máquina reparada y no retirada en tres meses será subastada \nFavor revisar que en su orden consten todos los componentes que usted deja. No se admitiran reclamos posteriores\n".$_SESSION['nombre_empresa']." no se responsabiliza por la pérdida de la información"), 400), 0, 'L', 0);
        $this->pdf->Ln(5);
        $this->pdf->SetX(3);
        $this->pdf->Cell(100, 5, "__________________________________________", 0, 0, 'C', 0);
        $this->pdf->Cell(102, 5, "__________________________________________", 0, 1, 'C', 0);
        $this->pdf->SetX(3);
        $this->pdf->Cell(100, 5, utf8_decode("ENTREGÉ CONFORME"), 0, 0, 'C', 0);
        $this->pdf->Cell(102, 5, utf8_decode("RECIBÍ CONFORME"), 0, 1, 'C', 0);
    }

    public function imprimir()
    {
        $this->cabecera("RESPONSABLE"); 
        $this->datosIngreso();
        $this->pdf->Ln(5);
        $this->pie();

        $this->pdf->SetY(148.5);
        //$this->pdf->Ln(5);

        $this->cabecera("CLIENTE");
        $this->datosIngreso();
        $this->pdf->Ln(5);
        $this->pie();
        $this->pdf->Output();
    }
}

$reporte = new Reporte();
$reporte->imprimir();