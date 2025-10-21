<?php
session_start();
include '../../procesos/base.php';
include('../menu/app.php');
conectarse();
error_reporting(0);

$cont1 = 0;
$consulta = pg_query("select max(id_conciliacion) from conciliacion");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;

$consulta7 = pg_query("select * from punto_venta_empresa  left join punto_venta  on punto_venta_empresa.id_punto_venta=punto_venta.id_punto_venta  where  
punto_venta_empresa.id_usuario='$_SESSION[id]' ORDER BY id_punto_venta_empresa ASC");
while ($row = pg_fetch_row($consulta7)) {
    $campo_punto_venta = $row[6];
}

$consulta8 = pg_query("select * from punto_venta_empresa  left join punto_venta  on punto_venta_empresa.id_punto_venta=punto_venta.id_punto_venta  where  
punto_venta_empresa.id_usuario='$_SESSION[id]' ORDER BY id_punto_venta_empresa ASC");
while ($row = pg_fetch_row($consulta8)) {
    $campo_punto_ventaid = $row[5];
}
?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>CONCILIACION BANCARIA</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../../font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="../../plugins/icon/ionicons.min.css" rel="stylesheet" type="text/css" />
        <link href="../../dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
        <link href="../../dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
        <link href="../../plugins/morris/morris.css" rel="stylesheet" type="text/css" />
        <link href="../../plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
        <link href="../../plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
        <link href="../../plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
        <link href="../../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
        <link href="../../dist/css/alertify.core.css" rel="stylesheet" />
        <link href="../../dist/css/alertify.default.css" id="toggleCSS" rel="stylesheet" />
        <link href="../../dist/css/jquery-ui-1.10.4.custom.css" rel="stylesheet" type="text/css" />
        <link href="../../dist/css/ui.jqgrid.css" rel="stylesheet" type="text/css" />
        <link href="../../plugins/iCheck/flat/blue.css" rel="stylesheet" type="text/css" />
    </head>

    <body class="skin-blue">
        <div class="wrapper">
            <?php banner_1(); ?>
            <?php menu_lateral_1(); ?>
            <div class="content-wrapper">
                <section class="content-header">
                    <h1>
                        Registro de Conciliación Bancaria
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Ingresos</a></li>
                        <li class="active">Conciliacion Bancaria</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="nav-tabs-custom">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab_1">
                                                <div class="row ">
                                                    <div class="col-md-12">
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Fecha Actual:</label>
                                                                <div class="input-group">
                                                                    <input type="text" name="fecha_actual" id="fecha_actual" readonly class="form-control timepicker" />
                                                                    <input type="hidden" name="proforma" id="proforma" readonly class="form-control" />
                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="bootstrap-timepicker">
                                                                <div class="form-group">
                                                                    <label>Hora Actual:</label>
                                                                    <div class="input-group">
                                                                        <input type="text" name="hora_actual" id="hora_actual" readonly class="form-control timepicker" />
                                                                        <div class="input-group-addon">
                                                                            <i class="fa fa-clock-o"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Digitad@r:</label>
                                                                <input type="text" name="digitador" id="digitador" readonly value="<?php echo $_SESSION['nombres'] ?>" class="form-control" />
                                                                <input type="hidden" name="comprobante2" id="comprobante2" readonly class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Punto de Venta:</label>
                                                                <input type="text" name="punto_venta" id="punto_venta" required readonly class="form-control" value="<?php echo $campo_punto_venta ?>" />
                                                                <input type="hidden" name="punto_ventaid" id="punto_ventaid" required readonly class="form-control" value="<?php echo $campo_punto_ventaid ?>" />
                                                                <div id="estado"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Comprobante:</label>
                                                                <input type="text" name="comprobante" id="comprobante" readonly class="form-control" value="<?php echo $cont1 ?>" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>CÓDIGO CUENTA CONTABLE</label>
                                                                <input type="text" name="codigo_plan" id="codigo_plan" placeholder="Buscar..." class="form-control" />
                                                                <input type="hidden" name="id_plan" id="id_plan" readonly class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label>DESCRIPCIÓN</label>
                                                                <input type="text" name="descripcion" id="descripcion" placeholder="Buscar..." class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-md-5">Fecha Inicio:</label>
                                                                <div class="form-group col-md-7 p-0">
                                                                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control timepicker" />
                                                                </div><!-- /.input group -->
                                                            </div><!-- /.form group -->
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-md-5">Fecha Fin:</label>
                                                                <div class="form-group col-md-7 p-0">
                                                                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control timepicker" />
                                                                </div><!-- /.input group -->
                                                            </div><!-- /.form group -->
                                                        </div>
                                                      
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <button class="btn bg-olive form-control" id='btnBuscar_consi'><i class="fa fa-search"></i>Cargar Datos</button>
                                                                </div>
                                                            </div>
                                                       
                                                    </div>
                                                </div>

                                            </div><!-- /.tab-pane -->
                                        </div><!-- /.tab-content -->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div id="grid_container">
                                                <table id="list7"></table>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="ff" id="ff" class="form-control timepicker" />
                                    <div class="col-md-2">
                                        <label> DEBE TOTAL: </label>

                                        <input name="debe" id="debe" readonly="" value="0.00" class="form-control" />
                                    </div>
                                    <input type="hidden" name="fff" id="fff" class="form-control timepicker" />
                                    <div class="col-md-2">
                                        <label> HABER TOTAL:</label>
                                        <input name="haber" id="haber" readonly="" value="0.00" class="form-control" />
                                    </div>
                                    <!--                                     <div class="col-md-3">
                                                                                <label>      DEBE: </label>
                                                                                <input name="debe_select"  id="debe_select"  readonly="" value="0.00" class="form-control" />
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <label>     HABER:</label>
                                                                                <input name="haber_select"  id="haber_select" readonly="" value="0.00"  class="form-control" />
                                        
                                        
                                                                            </div>-->
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Observaciones:</label>
                                            <textarea name="observacion" id="observacion" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn bg-olive margin" id='btnGuardar'><i class="fa fa-save"></i> Guardar</button>
                                            <button class="btn bg-olive margin" id='btnModificar'><i class="fa fa-save"></i> Modificar</button>
                                            <button class="btn bg-olive margin" id='btnNuevo'><i class="fa fa-pencil"></i> Nuevo</button>
                                            <button class="btn bg-olive margin" id='btnBuscar'><i class="fa fa-search"></i> Buscar</button>
                                            <button class="btn bg-olive margin" id='btnImprimir'><i class="fa fa-print"></i> Imprimir</button>
                                            <button class="btn bg-olive margin" id='btnEliminar'><i class="fa fa-trash"></i> Eliminar</button>
                                            <button class="btn bg-olive margin" id='btnAtras'><i class="fa fa-backward"></i> Atrás</button>
                                            <button class="btn bg-olive margin" id='btnSiguiente'><i class="fa fa-forward"></i> Siguiente</button>
                                        </div>
                                    </div>
                                </div>

                                <div id="cuentas" title="Búsqueda Cuentas Bancarias" class="">
                                    <table id="list2">
                                        <tr>
                                            <td></td>
                                        </tr>
                                    </table>
                                    <div id="pager2"></div>
                                </div>
                                <div id="buscar_conciliacion" title="Búsqueda Conciliación Bancaria" class="">
                                    <table id="list3">
                                        <tr>
                                            <td></td>
                                        </tr>
                                    </table>
                                    <div id="pager3"></div>
                                </div>

                                <div id="clave_permiso" title="PERMISOS">
                                    <table border="0">
                                        <tr>
                                            <td><label>Ingrese la clave de seguridad</label></td>
                                            <td><input type="password" name="clave" id="clave" class="campo"></td>
                                        </tr>
                                    </table>
                                    <div class="form-actions" align="center">
                                        <button class="btn btn-primary" id='btnAcceder'><i class="icon-ok"></i> Acceder</button>
                                        <button class="btn btn-primary" id='btnCancelar'><i class="icon-remove-sign"></i> Cancelar</button>
                                    </div>
                                </div>

                                <div id="seguro">
                                    <label>¿Está seguro de que desea guardar los cambios?</label>
                                    <br />
                                    <button class="btn btn-primary" id='btnAceptar'><i class="icon-ok"></i> Aceptar</button>
                                    <button class="btn btn-primary" id='btnSalir'><i class="icon-remove-sign"></i> Cancelar</button>
                                </div>
                            </div><!-- nav-tabs-custom -->
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
        <script src='../../plugins/fastclick/fastclick.min.js'></script>
        <script src="../../dist/js/app.min.js" type="text/javascript"></script>
        <script src="../../dist/js/validCampoFranz.js" type="text/javascript"></script>
        <script src="../../dist/js/alertify.min.js" type="text/javascript"></script>
        <script src="../../dist/js/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>
        <script src="../../dist/js/jquery.jqGrid.src.js" type="text/javascript"></script>
        <script src="../../dist/js/grid.locale-es.js" type="text/javascript"></script>
        <script src="../../plugins/iCheck/icheck.min.js" type="text/javascript"></script>
        <script src="conciliacion.js" type="text/javascript"></script>
        <link href="../../dist/css/style.css" rel="stylesheet" type="text/css" />
        <script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>

    </body>

</html>