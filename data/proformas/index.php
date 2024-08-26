<?php
session_start();
include '../../procesos/base.php';
include('../menu/app.php');
conectarse();
error_reporting(0);

$cont1 = 0;
$consulta = pg_query("select max(id_proforma) from proforma");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;

$consulta8 = pg_query("select * from punto_venta_empresa  left join punto_venta  on punto_venta_empresa.id_punto_venta=punto_venta.id_punto_venta  where  punto_venta_empresa.id_usuario='$_SESSION[id]' ORDER BY id_punto_venta_empresa ASC");
while ($row = pg_fetch_row($consulta8)) {
    $campo_punto_ventaid = $row[5];
}
$consulta7 = pg_query("select * from punto_venta_empresa  left join punto_venta  on punto_venta_empresa.id_punto_venta=punto_venta.id_punto_venta  where  punto_venta_empresa.id_usuario='$_SESSION[id]' ORDER BY id_punto_venta_empresa ASC");
while ($row = pg_fetch_row($consulta7)) {
    $campo_punto_venta = $row[6];
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>PROFORMAS</title>
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
    <link href="../../dist/css/jquery-ui-1.10.4.custom.css" rel="stylesheet" type="text/css" />
    <link href="../../dist/css/ui.jqgrid.css" rel="stylesheet" type="text/css" />

</head>

<body class="skin-blue">
    <div class="wrapper">
        <?php banner_1(); ?>
        <?php menu_lateral_1(); ?>
        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                    PROFORMA
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Procesos</a></li>
                    <li class="active">Proformas</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="rows">
                                    <div class="col-mx-12">
                                        <form id="clientes_form" name="clientes_form" method="post">
                                            <div class="row">
                                                <div class="col-mx-12">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Fecha Actual:</label>
                                                            <div class="input-group">
                                                                <input type="text" name="fecha_actual" id="fecha_actual" readonly class="form-control" />
                                                                <!--<input type="hidden" name="comprobante"  id="comprobante" readonly class="form-control" value="<?php echo $cont1 ?>"/>-->
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </div>
                                                            </div><!-- /.input group -->
                                                        </div><!-- /.form group -->
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
                                                            <label>NUM COMPROBANTE</label>

                                                            <input type="text" name="comprobante" id="comprobante" readonly class="form-control" value="<?php echo $cont1 ?>" />
                                                            <input type="hidden" name="id_proforma" id="id_proforma" class="form-control" value="" />
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Digitad@r:</label>
                                                            <input type="text" name="digitador" id="digitador" readonly value="<?php echo $_SESSION['nombres'] ?>" class="form-control" />
                                                            <input type="hidden" name="comprobante2" id="comprobante2" readonly class="form-control">
                                                        </div>
                                                    </div>


                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Punto de Venta:</label>
                                                            <input type="text" name="punto_venta" id="punto_venta" required readonly class="form-control" value="<?php echo $campo_punto_venta ?>" />
                                                            <input type="hidden" name="punto_ventaid" id="punto_ventaid" required readonly class="form-control" value="<?php echo $campo_punto_ventaid ?>" />

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <br />
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="col-md-4 ">CI. Identidad/RUC: <font color="red">*</font></label>
                                                            <div class="form-group col-md-8 no-padding">
                                                                <input type="text" name="ruc_ci" id="ruc_ci" required placeholder="Buscar....." class="form-control" />
                                                                <input type="hidden" name="id_cliente" id="id_cliente" class="form-control" />
                                                                <input type="hidden" name="id_proforma" id="id_proforma" class="form-control" />
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-4">Saldo Disponible:</label>
                                                            <div class="form-group col-md-8 no-padding">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon">
                                                                        <i class="glyphicon glyphicon-usd"></i>
                                                                    </div>
                                                                    <input type="text" name="saldo" id="saldo" value="0.00" readonly class="form-control" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="col-md-3">Nombres:</label>
                                                            <div class="form-group col-md-9 no-padding">
                                                                <input type="text" name="nombres_completos" id="nombres_completos" required placeholder="Buscar....." class="form-control" />
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-3">Tipo de Precio:</label>
                                                            <div class="form-group col-md-9 no-padding">
                                                                <select class="form-control" name="tipo_precio" id="tipo_precio">
                                                                    <option value="MINORISTA" selected>MINORISTA</option>
                                                                    <option value="MAYORISTA">MAYORISTA</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <h3 class="box-title">Detalle Proforma</h3>
                                            <div class="row">
                                                <div class="col-mx-12">
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>CÓDIGO BARRAS</label>
                                                            <input type="text" style="text-transform: uppercase" name="codigo_barras" id="codigo_barras" placeholder="Buscar..." class="form-control" />
                                                        </div>
                                                    </div>

                                                    <!--                            <div class="col-md-2">
                                                                                      <div class="form-group">
                                                                                        <label>CÓDIGO</label>-->
                                                    <input type="hidden" name="codigo" id="codigo" placeholder="Buscar..." class="form-control" />
                                                    <!--                              </div>  
                                                                                    </div>-->

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>PRODUCTO</label>
                                                            <input type="text" name="producto" id="producto" placeholder="Buscar..." class="form-control" />
                                                        </div>
                                                    </div>




                                                    <div class="col-md-1">
                                                        <div class="form-group">
                                                            <label>CANTIDAD</label>
                                                            <!--<input type="text" name="cantidad"  id="cantidad" class="form-control" onKeyPress="return ValidNum(event)"/>-->
                                                            <input type="text" name="cantidad" id="cantidad" class="form-control" />

                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <div class="form-group">
                                                            <label>P.COSTO</label>
                                                            <input type="text" name="precio" id="precio" readonly="" class="form-control" placeholder="0.0000" />
                                                            <input type="hidden" name="precio_ori" id="precio_ori" readonly="" class="form-control" placeholder="0.0000" />
                                                        </div>
                                                    </div>
                                                    <!--                                                         <div class="col-md-2">
                                                                                                                    <div class="form-group">
                                                                                                                        <label>PU.VENTA</label>-->
                                                    <input type="hidden" name="punto_venta_inv" id="punto_venta_inv" readonly class="form-control" />

                                                    <!--                                                            </div>  
                                                                                                                </div>-->

                                                    <div class="col-md-1">
                                                        <div class="form-group">
                                                            <label>PRECIO</label>
                                                            <input type="text" name="p_venta" id="p_venta" class="form-control" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <div class="form-group">
                                                            <label>PVP+IVA:</label>
                                                            <input type="text" name="venta_iva" id="venta_iva" class="form-control" />
                                                        </div>
                                                    </div>

                                                    <div class="col-md-1">
                                                        <div class="form-group">
                                                            <label>DESC.</label>
                                                            <input type="number" name="descuento" id="descuento" min="0" placeholder="%" class="form-control" />
                                                            <input type="hidden" name="cod_producto" id="cod_producto" readonly class="form-control" />
                                                            <input type="hidden" name="disponibles" id="disponibles" readonly class="form-control" />
                                                            <input type="hidden" name="iva_producto" id="iva_producto" readonly class="form-control" />
                                                            <input type="hidden" name="des" id="des" readonly class="form-control" />
                                                            <input type="hidden" name="incluye" id="incluye" readonly class="form-control" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <div class="form-group">
                                                            <label>STOCK</label>
                                                            <input readonly class="form-control" type="text" id="stock" value="0">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-mx-12">
                                                <div id="grid_container">
                                                    <table id="list"></table>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Observaciones:</label>
                                        <textarea class="form-control" name="observaciones" id="observaciones" rows="3"></textarea>
                                    </div>
                                    <div class="col-md-8">
                                        <div style="display: flex; justify-content: right;">
                                            <div style="display: flex;" id="div_totales_tarifas"></div>
                                            <div>
                                                <label>Subtotal:</label>
                                                <input type="text" name="subx" id="subx" value="0.000" readonly class="form-control" />
                                                <input type="hidden" name="sub" id="sub" value="0.000" readonly class="form-control" />
                                            </div>
                                            <div>
                                                <label>Descuento:</label>
                                                <input type="text" name="descx" id="descx" value="0.000" readonly class="form-control" />
                                                <input type="hidden" name="desc" id="desc" value="0.000" readonly class="form-control" />
                                            </div>
                                            <div>
                                                <label>Iva....%:</label>
                                                <input type="text" name="ivax" id="ivax" value="0.000" readonly class="form-control" />
                                                <input type="hidden" name="iva" id="iva" value="0.000" readonly class="form-control" />
                                            </div>
                                            <div style="display: flex; align-items: center;">
                                                <label class="col-md-4" style="color:red;font-size:25px">Total:</label>
                                                <div class="form-group col-md-8 no-padding">
                                                    <input style="width:150px;height:70px; color:red; font-size:38px" type="text" name="totx" id="totx" value="0.000" readonly class="form-control" />
                                                    <input type="hidden" name="tot" id="tot" value="0.000" readonly class="form-control" />

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h3 id="estado_proforma" style="display: none; color: red; font-weight: bold;">ANULADA</h3>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <p>
                                <button class="btn bg-olive margin" id='btnGuardar'><i class="fa fa-save"></i> Guardar</button>
                                <button class="btn bg-olive margin" id='btnModificar'><i class="fa fa-edit"></i> Modificar</button>
                                <button class="btn bg-olive margin" id='btnBuscar'><i class="fa fa-search"></i> Buscar</button>
                                <button class="btn bg-olive margin" id='btnNuevo'><i class="fa fa-pencil"></i> Nuevo</button>
                                <button class="btn bg-olive margin" id='btnImprimir'><i class="fa fa-print"></i> Imprimir</button>
                                <button class="btn bg-olive margin" id='btnAtras'><i class="fa fa-backward"></i> Atrás</button>
                                <button class="btn bg-olive margin" id='btnAdelante'>Adelante <i class="fa fa-forward"></i></button>
                            </p>
                        </div>
                        <div id="buscar_proformas" title="BUSCAR PROFORMAS">
                            <table id="list2">
                                <tr>
                                    <td></td>
                                </tr>
                            </table>
                            <div id="pager2"></div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    </div>
    </section>
    </div>
    <?php footer(); ?>
    </div>

    <script src="../../plugins/jQuery/jQuery-2.1.3.min.js"></script>
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
    <script src="../../dist/js/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>
    <script src="../../dist/js/jquery.jqGrid.src.js" type="text/javascript"></script>
    <script src="../../dist/js/grid.locale-es.js" type="text/javascript"></script>
    <script src="proforma.js?v=1" type="text/javascript"></script>
    <link href="../../dist/css/style.css" rel="stylesheet" type="text/css" />
    <script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>
</body>

</html>