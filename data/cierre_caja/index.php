<?php
session_start();
include '../../procesos/base.php';
include('../menu/app.php');
conectarse();
error_reporting(0);
/* $cont1 = 0;
$consulta = pg_query("select max(comprobante::int) from cierre_caja");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++; */
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>CIERRE DE CAJA</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../../font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="../../plugins/icon/ionicons.min.css" rel="stylesheet" type="text/css" />
    <link href="../../dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <link href="../../dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    <link href="../../plugins/iCheck/flat/blue.css" rel="stylesheet" type="text/css" />
    <link href="../../plugins/morris/morris.css" rel="stylesheet" type="text/css" />
    <link href="../../plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <link href="../../plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
    <link href="../../plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet" />
    <link href="../../plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
    <link href="../../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
    <link href="../../dist/css/alertify.core.css" rel="stylesheet" />
    <link href="../../dist/css/alertify.default.css" id="toggleCSS" rel="stylesheet" />
    <link href="../../dist/css/jquery-ui-1.13.3.min.css" rel="stylesheet" type="text/css" />
    <link href="../../dist/css/ui.jqgrid.css" rel="stylesheet" type="text/css" />

</head>

<body class="skin-blue">
    <div class="wrapper">
        <?php banner_1(); ?>
        <?php menu_lateral_1(); ?>
        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                    Cierre de Caja
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Procesos</a></li>
                    <li class="active">TASAS </li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="rows">
                                    <div class="row">
                                        <p>
                                            <button id="btn_buscar_cajas_abiertas" type="button" class="btn bg-olive"><i class="fa fa-search" aria-hidden="true"></i> Buscar Cajas Abiertas</button>
                                        </p>
                                    </div>
                                    <div class="row" style="margin-bottom: 30px;">
                                        <div class="col-md-12">
                                            <div id="buscar_cajas_abiertas" title="BUSCAR CAJAS ABIERTAS">
                                                <table id="listCajaAbierta">
                                                    <tr>
                                                        <td></td>
                                                    </tr>
                                                </table>
                                                <div id="listPagerCajaAbierta"></div>
                                            </div>
                                        </div>

                                    </div>

                                    <div id="container_form_cierre" style="display: none;">
                                        <div class="col-mx-12">
                                            <form id="clientes_form" name="clientes_form" method="post">
                                                <div class="row">
                                                    <div class="col-mx-12">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Fecha Registro:</label>
                                                                <div class="input-group">
                                                                    <input type="text" name="fecha_actual" id="fecha_actual" readonly class="form-control" />

                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="bootstrap-timepicker">
                                                                <div class="form-group">
                                                                    <label>Hora Actual:</label>
                                                                    <div class="input-group">
                                                                        <input type="text" name="hora_actual" id="hora_actual" readonly class="form-control timepicker" />
                                                                        <div class="input-group-addon">
                                                                            <i class="fa fa-clock-o"></i>
                                                                        </div>
                                                                    </div><!-- /.input group -->
                                                                </div><!-- /.form group -->
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Digitad@r:</label>
                                                                <input type="text" name="digitador" id="digitador" readonly value="<?php echo $_SESSION['nombres'] ?>" class="form-control" />
                                                                <input type="hidden" name="comprobante2" id="comprobante2" readonly class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>NUM COMPROBANTE</label>
                                                                <input type="text" name="comprobante" id="comprobante" readonly class="form-control" />

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="estado"></div>
                                                </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-mx-12">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Denominacion</label>
                                                        <input type="text" name="denominacion_cien" id="denominacion_cien" readonly="" placeholder="CIEN" value="CIEN" class="form-control" />
                                                        <!--                                                        <input type="hidden" name="cod_denominacion"  id="cod_denominacion" readonly class="form-control" />-->

                                                    </div>
                                                </div>

                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <label>Cantidad</label>

                                                        <input type="text" name="cantidad_cien" id="cantidad_cien" class="form-control" />

                                                    </div>
                                                </div>
                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <label>Valor</label>

                                                        <input type="text" name="valor_cien" id="valor_cien" value="100" readonly class="form-control" />

                                                    </div>
                                                </div>
                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <label>Total</label>

                                                        <input type="text" name="total_cien" id="total_cien" readonly class="form-control" />

                                                    </div>
                                                </div>


                                                <div class="col-md-2 ">
                                                    <div class="form-group">
                                                        <label>MONTO APERTURA</label>
                                                        <input readonly type="text" name="monto_apertura" id="monto_apertura" value="0.00" class="form-control" />


                                                    </div>
                                                </div>
                                                <div class="col-md-2 " id="diario_usuario" style="display: none;">
                                                    <div class="form-group">
                                                        <!--<label>DIARIO TOTAL CAJA</label>-->
                                                        <input type="hidden" name="diario_caja_text" id="diario_caja_text" readonly="" class="form-control" />

                                                        <!--<input type="text" name="monto_apertura"  id="monto_apertura" readonly="" value="0"  class="form-control" />-->
                                                    </div>

                                                </div>
                                                <button class="btn btn-primary" id='btnActualizar' style="display: none;">Actualizar</button>

                                                <div id="estado" style="margin-top: -10px">
                                                    <h3></h3>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-mx-12">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <!--<label>Denominacion</label>-->
                                                        <input type="text" name="denominacion_cincuenta" id="denominacion_cincuenta" readonly="" placeholder="CINCUENTA" value="CINCUENTA" class="form-control" />
                                                        <!--                                                        <input type="hidden" name="cod_denominacion"  id="cod_denominacion" readonly class="form-control" />-->

                                                    </div>
                                                </div>

                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Cantidad</label>-->

                                                        <input type="text" name="cantidad_cincuenta" id="cantidad_cincuenta" class="form-control" />

                                                    </div>
                                                </div>
                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Valor</label>-->

                                                        <input type="text" name="valor_cincuenta" id="valor_cincuenta" value="50" readonly class="form-control" />

                                                    </div>
                                                </div>
                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Total</label>-->
                                                        <input type="text" name="total_cincuenta" id="total_cincuenta" readonly class="form-control" />
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-mx-12">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <!--<label>Denominacion</label>-->
                                                        <input type="text" name="denominacion_veinte" id="denominacion_veinte" readonly="" placeholder="VEINTE" value="VEINTE" class="form-control" />
                                                        <!--                                                        <input type="hidden" name="cod_denominacion"  id="cod_denominacion" readonly class="form-control" />-->

                                                    </div>
                                                </div>

                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Cantidad</label>-->

                                                        <input type="text" name="cantidad_veinte" id="cantidad_veinte" class="form-control" />

                                                    </div>
                                                </div>
                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Valor</label>-->

                                                        <input type="text" name="valor_veinte" id="valor_veinte" value="20" readonly class="form-control" />

                                                    </div>
                                                </div>
                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Total</label>-->
                                                        <input type="text" name="total_veinte" id="total_veinte" readonly class="form-control" />
                                                    </div>
                                                </div>



                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-mx-12">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <!--<label>Denominacion</label>-->
                                                        <input type="text" name="denominacion_diez" id="denominacion_diez" readonly="" placeholder="DIEZ" value="DIEZ" class="form-control" />
                                                        <!--                                                        <input type="hidden" name="cod_denominacion"  id="cod_denominacion" readonly class="form-control" />-->

                                                    </div>
                                                </div>

                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Cantidad</label>-->

                                                        <input type="text" name="cantidad_diez" id="cantidad_diez" class="form-control" />

                                                    </div>
                                                </div>
                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Valor</label>-->

                                                        <input type="text" name="valor_diez" id="valor_diez" value="10" readonly class="form-control" />

                                                    </div>
                                                </div>
                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Total</label>-->
                                                        <input type="text" name="total_diez" id="total_diez" readonly class="form-control" />
                                                    </div>
                                                </div>



                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-mx-12">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <!--<label>Denominacion</label>-->
                                                        <input type="text" name="denominacion_cinco" id="denominacion_cinco" readonly="" placeholder="CINCO" value="CINCO" class="form-control" />
                                                        <!--                                                        <input type="hidden" name="cod_denominacion"  id="cod_denominacion" readonly class="form-control" />-->

                                                    </div>
                                                </div>

                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Cantidad</label>-->

                                                        <input type="text" name="cantidad_cinco" id="cantidad_cinco" class="form-control" />

                                                    </div>
                                                </div>
                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Valor</label>-->

                                                        <input type="text" name="valor_cinco" id="valor_cinco" value="5" readonly class="form-control" />

                                                    </div>
                                                </div>
                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Total</label>-->
                                                        <input type="text" name="total_cinco" id="total_cinco" readonly class="form-control" />
                                                    </div>
                                                </div>



                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-mx-12">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <!--<label>Denominacion</label>-->
                                                        <input type="text" name="denominacion_uno" id="denominacion_uno" readonly="" placeholder="UNO" value="UNO" class="form-control" />
                                                        <!--                                                        <input type="hidden" name="cod_denominacion"  id="cod_denominacion" readonly class="form-control" />-->

                                                    </div>
                                                </div>

                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Cantidad</label>-->

                                                        <input type="text" name="cantidad_uno" id="cantidad_uno" class="form-control" />

                                                    </div>
                                                </div>
                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Valor</label>-->

                                                        <input type="text" name="valor_uno" id="valor_uno" value="1" readonly class="form-control" />

                                                    </div>
                                                </div>
                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Total</label>-->
                                                        <input type="text" name="total_uno" id="total_uno" readonly class="form-control" />
                                                    </div>
                                                </div>



                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-mx-12">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <!--<label>Denominacion</label>-->
                                                        <input type="text" name="denominacion_cero_cincuenta" id="denominacion_cero_cincuenta" readonly="" value="CINCUENTA CENTAVOS" placeholder="CINCUENTA CENTAVOS" class="form-control" />
                                                        <!--                                                        <input type="hidden" name="cod_denominacion"  id="cod_denominacion" readonly class="form-control" />-->

                                                    </div>
                                                </div>

                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Cantidad</label>-->

                                                        <input type="text" name="cantidad_cero_cincuenta" id="cantidad_cero_cincuenta" class="form-control" />

                                                    </div>
                                                </div>
                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Valor</label>-->

                                                        <input type="text" name="valor_cero_cincuenta" id="valor_cero_cincuenta" value="0.50" readonly class="form-control" />

                                                    </div>
                                                </div>
                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Total</label>-->
                                                        <input type="text" name="total_cero_cincuenta" id="total_cero_cincuenta" readonly class="form-control" />
                                                    </div>
                                                </div>



                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-mx-12">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <!--<label>Denominacion</label>-->
                                                        <input type="text" name="denominacion_cero_veinticinco" id="denominacion_cero_veinticinco" readonly="" value="VEINTICINCO CENTAVOS" placeholder="VEINTICINCO CENTAVOS" class="form-control" />
                                                        <!--                                                        <input type="hidden" name="cod_denominacion"  id="cod_denominacion" readonly class="form-control" />-->

                                                    </div>
                                                </div>

                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Cantidad</label>-->

                                                        <input type="text" name="cantidad_cero_veinticinco" id="cantidad_cero_veinticinco" class="form-control" />

                                                    </div>
                                                </div>
                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Valor</label>-->

                                                        <input type="text" name="valor_cero_veinticinco" id="valor_cero_veinticinco" value="0.25" readonly class="form-control" />

                                                    </div>
                                                </div>
                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Total</label>-->
                                                        <input type="text" name="total_cero_veinticinco" id="total_cero_veinticinco" readonly class="form-control" />
                                                    </div>
                                                </div>



                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-mx-12">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <!--<label>Denominacion</label>-->
                                                        <input type="text" name="denominacion_cero_diez" id="denominacion_cero_diez" readonly="" placeholder="DIEZ CENTAVOS" value="DIEZ CENTAVOS" class="form-control" />
                                                        <!--                                                        <input type="hidden" name="cod_denominacion"  id="cod_denominacion" readonly class="form-control" />-->

                                                    </div>
                                                </div>

                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Cantidad</label>-->

                                                        <input type="text" name="cantidad_cero_diez" id="cantidad_cero_diez" class="form-control" />

                                                    </div>
                                                </div>
                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Valor</label>-->

                                                        <input type="text" name="valor_cero_diez" id="valor_cero_diez" value="0.10" readonly class="form-control" />

                                                    </div>
                                                </div>
                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Total</label>-->
                                                        <input type="text" name="total_cero_diez" id="total_cero_diez" readonly class="form-control" />
                                                    </div>
                                                </div>




                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-mx-12">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <!--<label>Denominacion</label>-->
                                                        <input type="text" name="denominacion_cero_cinco" id="denominacion_cero_cinco" readonly="" placeholder="CINCO CENTAVOS" value="CINCO CENTAVOS" class="form-control" />
                                                        <!--                                                        <input type="hidden" name="cod_denominacion"  id="cod_denominacion" readonly class="form-control" />-->

                                                    </div>
                                                </div>

                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Cantidad</label>-->

                                                        <input type="text" name="cantidad_cero_cinco" id="cantidad_cero_cinco" class="form-control" />

                                                    </div>
                                                </div>
                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Valor</label>-->

                                                        <input type="text" name="valor_cero_cinco" id="valor_cero_cinco" value="0.05" readonly class="form-control" />

                                                    </div>
                                                </div>
                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Total</label>-->
                                                        <input type="text" name="total_cero_cinco" id="total_cero_cinco" readonly class="form-control" />
                                                    </div>
                                                </div>



                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-mx-12">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <!--<label>Denominacion</label>-->
                                                        <input type="text" name="denominacion_cero_uno" id="denominacion_cero_uno" readonly="" placeholder="UN CENTAVO" value="UN CENTAVO" class="form-control" />
                                                        <!--                                                        <input type="hidden" name="cod_denominacion"  id="cod_denominacion" readonly class="form-control" />-->

                                                    </div>
                                                </div>

                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Cantidad</label>-->

                                                        <input type="text" name="cantidad_cero_uno" id="cantidad_cero_uno" class="form-control" />

                                                    </div>
                                                </div>
                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Valor</label>-->

                                                        <input type="text" name="valor_cero_uno" id="valor_cero_uno" value="0.01" readonly class="form-control" />

                                                    </div>
                                                </div>
                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <!--<label>Total</label>-->
                                                        <input type="text" name="total_cero_uno" id="total_cero_uno" readonly class="form-control" />
                                                    </div>
                                                </div>



                                            </div>
                                        </div>
                                        <div style="font-size: large; font-weight: bold; margin-bottom: 16px; width: 50%; border-bottom: 1px solid black;">OTROS VALORES</div>
                                        <div class="row" style="margin-bottom: 16px;">
                                            <div class="col-md-6">
                                                <label for="">Valor de transferencias</label>
                                                <input placeholder="INGRESE UN VALOR" id="valor_transferencia" class="form-control" type="text">
                                            </div>
                                        </div>
                                        <div style="margin-bottom: 16px; width: 50%; border-bottom: 1px solid black;"></div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Observaciones:<span style="color:red">*</span></label>
                                                <div class="form-group">
                                                    <textarea class="form-control" name="observaciones" id="observaciones" rows="3"></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-3">

                                                <label class="col-md-4" style="color:red;font-size:25px">Total:</label>
                                                <div class="form-group col-md-8 p-0">
                                                    <input style="width:150px;height:70px; color:red; font-size:38px" type="total_valor" name="total_valor" id="total_valor" value="0.000" readonly class="form-control" />


                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--                                        <div class="col-md-12">
                                                                                    <div id="grid_container">
                                                                                        <table id="list"></table>
                                                                                        <div id="pager"></div>  
                                                                                    </div>
                                                                                </div> -->
                                    <!--                                        <div class="col-md-2">
                                                                                    <div class="form-group">
                                                                                        <label>Total Cantidad</label>
                                                                                        <input type="text" name="total_cantidad"  id="total_cantidad" class="form-control" />
                                                                                    </div> 
                                                                                </div>-->




                                    <!--                                        <div class="col-md-1">
                                                                                    <div class="form-group">
                                                                                        <label>Totales</label>
                                                                                        <input type="text" name="totales"  id="totales"  class="form-control" />
                                        
                                                                                    </div>  
                                                                                </div> -->

                                    <div class="row">
                                        <div class="col-mx-12">
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <!--                                <div id="buscar_inventario" title="BUSCAR INVENTARIO">
                                                                    <table id="list22">
                                                                        <tr>
                                                                            <td></td>
                                                                        </tr>
                                                                    </table>
                                                                    <div id="pager22"></div>
                                                                </div>-->

                            <div id="clave_permiso" title="PERMISOS">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-md-6">Ingrese la clave de seguridad</label>
                                        <div class="form-group col-md-6 p-0">
                                            <input type="password" name="clave" id="clave" required class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3">Comentario</label>
                                        <div class="form-group col-md-9">
                                            <textarea id="anulacionComentario" name="anulacionComentario" class="form-control" required maxlength="50"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions" align="center">
                                    <button class="btn btn-primary" id='btnAcceder'><i class="icon-ok"></i> Acceder</button>
                                    <button class="btn btn-primary" id='btnCancelar'><i class="icon-remove-sign"></i> Cancelar</button>
                                </div>
                            </div>
                            <div id="seguro">
                                <label>Esta seguro de Anular</label>
                                <br />
                                <div class="form-actions" align="center">
                                    <button class="btn btn-primary" id='btnAceptar'><i class="icon-ok"></i> Aceptar</button>
                                    <button class="btn btn-primary" id='btnSalir'><i class="icon-remove-sign"></i> Cancelar</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-mx-12">
                                    <p>
                                        <button class="btn bg-olive margin" id='btnGuardar'><i class="fa fa-save"></i> Guardar</button>
                                        <!--<button class="btn bg-olive margin" id='btnModificar'><i class="fa fa-save"></i> Modificar</button>-->
                                        <button class="btn bg-olive margin" id='btnNuevo'><i class="fa fa-pencil"></i> Nuevo</button>
                                        <button disabled class="btn bg-olive margin" id='btnAnular'><i class="fa fa-remove"></i> Anular</button>
                                        <button class="btn bg-olive margin" id='btnBuscar'><i class="fa fa-search"></i> Buscar</button>
                                        <button disabled class="btn bg-olive margin" id='btnImprimir'><i class="fa fa-print"></i> Imprimir</button>
                                    </p>
                                </div>
                            </div>
                            <div id="buscar_inventario" title="BUSCAR CIERRE CAJA">
                                <table id="listBuscar">
                                    <tr>
                                        <td></td>
                                    </tr>
                                </table>
                                <div id="listPager"></div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </section>
    </div>
    <?php footer(); ?>
    </div>
    <script src="../../plugins/jQuery/jquery-3.7.1.min.js"></script>
    <script src="../../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../../plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
    <script src="../../plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
    <script src="../../plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
    <script src="../../plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
    <script src="../../plugins/colorpicker/bootstrap-colorpicker.min.js" type="text/javascript"></script>
    <script src="../../plugins/timepicker/bootstrap-timepicker.min.js" type="text/javascript"></script>
    <script src="../../plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="../../plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script src='../../plugins/fastclick/fastclick.min.js'></script>
    <script src="../../dist/js/app.min.js" type="text/javascript"></script>
    <script src="../../dist/js/validCampoFranz.js" type="text/javascript"></script>
    <script src="../../dist/js/alertify.min.js" type="text/javascript"></script>
    <script src="../../dist/js/jquery-ui-1.13.3.min.js" type="text/javascript"></script>
    <script src="../../dist/js/jquery.jqGrid.src.js" type="text/javascript"></script>
    <script src="../../dist/js/grid.locale-es.js" type="text/javascript"></script>
    <script src="../../dist/js/jquery.hotkeys.js" type="text/javascript"></script>
    <script src="rubrosTarifa.js?v=1" type="text/javascript"></script>
    <link href="../../dist/css/style.css" rel="stylesheet" type="text/css" />
    <script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>
    <!-- <script src="../../dist/js/menu.js" type="text/javascript"></script> -->
</body>

</html>