<?php
session_start();
include '../../procesos/base.php';
include('../menu/app.php');
conectarse();
error_reporting(0);

$cont1 = 0;
$consulta = pg_query("select max(id_proforma) from proforma_tecnico");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;
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
                        PROFORMA TÉCNICO
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Procesos</a></li>
                        <li class="active">Proformas Técnico</li>
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
                                                                    <!--<input type="hidden" name="comprobante" id="comprobante" readonly class="form-control" value="" />-->
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

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Digitad@r:</label>
                                                                <input type="text" name="digitador" id="digitador" readonly value="<?php echo $_SESSION['nombres'] ?>" class="form-control" />
                                                                <input type="hidden" name="comprobante2" id="comprobante2" readonly class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Num Comprobante:</label>
                                                                <input type="text" name="comprobante" id="comprobante" readonly class="form-control" value="<?php echo $cont1 ?>" />

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
                                                                <div class="form-group col-md-8 p-0">
                                                                    <input type="text" name="ruc_ci" id="ruc_ci" required placeholder="Buscar....." readonly class="form-control" />
                                                                    <input type="hidden" name="id_cliente" id="id_cliente" class="form-control" />
                                                                    <input type="hidden" name="id_proforma" id="id_proforma" class="form-control" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-3">Nombres:</label>
                                                                <div class="form-group col-md-9 p-0">
                                                                    <input type="text" name="nombres_completos" id="nombres_completos" required readonly placeholder="Buscar....." class="form-control" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-3">Datos:</label>
                                                                <div class="form-group col-md-9 p-0">
                                                                    <textarea type="text" name="datos" id="datos" required readonly rows="3" class="form-control"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <br>
                                                                <br>

                                                            </div>
                                                            <div class="form-group">

                                                                <input type="hidden" name="saldo" id="saldo" value="0.00" readonly class="form-control" />
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-3">Observaciones:</label>
                                                                <div class="form-group col-md-9 p-0">
                                                                    <textarea type="text" name="observaciones" id="observaciones" required readonly rows="2" class="form-control"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-3">Accesorios:</label>
                                                                <div class="form-group col-md-9 p-0">
                                                                    <textarea type="text" name="accesorios" id="accesorios" required readonly rows="2" class="form-control"></textarea>
                                                                </div>
                                                            </div>




                                                        </div>
                                                        <div class="col-md-6">                       
                                                            <label class="col-md-2">Cedula Vendedor: </label>
                                                            <div class="form-group col-md-9 p-0">             
                                                                <input type="text" name="ruc_ci_cli"  id="ruc_ci_cli" placeholder="Buscar....." required class="form-control" value=""  />
                                                                <input type="hidden" name="id_vendedor"  id="id_vendedor" placeholder="Buscar....." required class="form-control" value="" />
                                                            </div>                                

                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="col-md-3">Nombre Vendedor:</label>
                                                            <div class="form-group col-md-9 p-0">
                                                                <input type="text" name="nombre_vendedor"  id="nombre_vendedor" placeholder="Buscar....."  required class="form-control" value=""  />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="tipo_precio" id="tipo_precio" placeholder="Buscar..." class="form-control" />

                                                <!--                      <div class="row">
                                                                        <div class="col-md-12">
                                                                          <div class="col-md-6">
                                                                            <div class="form-group">
                                                                              <label class="col-md-3">Tipo de Precio:</label>
                                                                              <div class="form-group col-md-9 p-0">
                                                                                <select class="form-control" name="tipo_precio" value="MINORISTA"  id="tipo_precio">
                                                                                  <option value="MINORISTA" selected>MINORISTA</option>
                                                                                  <option value="MAYORISTA">MAYORISTA</option>
                                                                                </select>
                                                                              </div>
                                                                            </div>
                                                                          </div>
                                                                        </div>
                                                                      </div>-->
                                                <hr />
                                                <h3 class="box-title">Detalle Proforma</h3>
                                                <div class="row">
                                                    <div class="col-mx-12">
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>CÓDIGO BARRAS</label>
                                                                <input type="text" name="codigo_barras" id="codigo_barras" placeholder="Buscar..." class="form-control" />
                                                            </div>
                                                        </div>

                                                        <!--                                                        <div class="col-md-2">
                                                                                                                    <div class="form-group">
                                                                                                                        <label>CÓDIGO</label>-->
                                                        <input type="hidden" name="codigo" id="codigo" placeholder="Buscar..." class="form-control" />
                                                        <!--                                                            </div>
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
                                                                <input type="text" name="cantidad" id="cantidad" class="form-control" onKeyPress="return ValidNum(event)" />
                                                            </div>
                                                        </div>

                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>PRECIO</label>
                                                                <input type="text" name="p_venta" id="p_venta" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>PREC.Iva:</label>
                                                                <input type="text" name="venta_iva"  id="venta_iva" disabled=""  class="form-control" />
                                                            </div>  
                                                        </div>
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>DESC.</label>
                                                                <input type="number" name="descuento" id="descuento" min="0" placeholder="%" class="form-control" />
                                                                <input type="hidden" name="cod_producto" id="cod_producto" readonly class="form-control" />
                                                                <!--<input type="hidden" name="disponibles" id="disponibles" readonly class="form-control" />-->
                                                                <input type="hidden" name="iva_producto" id="iva_producto" readonly class="form-control" />
                                                                <input type="hidden" name="des" id="des" readonly class="form-control" />
                                                                <input type="hidden" name="incluye" id="incluye" readonly class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>STOCK</label>
                                                                <input type="text" name="disponibles" id="disponibles" readonly class="form-control" placeholder="0.0000"/>

                                                                <input type="hidden" name="inventar"  id="inventar" readonly class="form-control" />
                                                            </div>
                                                        </div> 
                                                    </div>
                                                </div>

                                                <div class="col-mx-12">
                                                    <div id="grid_container">
                                                        <table id="list"></table>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-mx-12">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-md-3">Observaciones:</label>
                                                                <div class="form-group col-md-9 p-0">
                                                                    <textarea class="form-control" name="observacionesp" id="observacionesp" rows="3"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <input type="checkbox" name="tipo_entrega" id="tipo_entrega" ><span>Entrega sin Factura</span><br/><br/>


                                                        <div class="col-md-3"></div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="col-md-5">Tarifa 0:</label>
                                                                <div class="form-group col-md-7 p-0">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon">
                                                                            <i class="glyphicon glyphicon-usd"></i>
                                                                        </div>
                                                                        <input type="text" name="total_px" id="total_px" value="0.000" readonly class="form-control" />
                                                                        <input type="hidden" name="total_p" id="total_p" value="0.000" readonly class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-5">Tarifa IVA:</label>
                                                                <div class="form-group col-md-7 p-0">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon">
                                                                            <i class="glyphicon glyphicon-usd"></i>
                                                                        </div>
                                                                        <input type="text" name="total_p2x" id="total_p2x" value="0.000" readonly class="form-control" />
                                                                        <input type="hidden" name="total_p2" id="total_p2" value="0.000" readonly class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-5">... %Iva:</label>
                                                                <div class="form-group col-md-7 p-0">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon">
                                                                            <i class="glyphicon glyphicon-usd"></i>
                                                                        </div>
                                                                        <input type="text" name="ivax" id="ivax" value="0.000" readonly class="form-control" />
                                                                        <input type="hidden" name="iva" id="iva" value="0.000" readonly class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-5">Descuento:</label>
                                                                <div class="form-group col-md-7 p-0">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon">
                                                                            <i class="glyphicon glyphicon-usd"></i>
                                                                        </div>
                                                                        <input type="text" name="descx" id="descx" value="0.000" readonly class="form-control" />
                                                                        <input type="hidden" name="desc" id="desc" value="0.000" readonly class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-5">Total:</label>
                                                                <div class="form-group col-md-7 p-0">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon">
                                                                            <i class="glyphicon glyphicon-usd"></i>
                                                                        </div>
                                                                        <input type="text" name="totx" id="totx" value="0.000" readonly class="form-control" />
                                                                        <input type="hidden" name="tot" id="tot" value="0.000" readonly class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="row" id="alert_eliminado" style="display: none;">
                                        <div class="col-md-12">
                                            <div class="alert alert-danger">
                                                EL REGISTRO ACTUAL SE ENCUENTRA EN ESTADO ELIMINADO
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <p>
                                                <button class="btn bg-olive margin" id='btnGuardar'><i class="fa fa-save"></i> Guardar</button>
                                                <button class="btn bg-olive margin" id='btnModificar'><i class="fa fa-edit"></i> Modificar</button>
                                                <button class="btn bg-olive margin" id='btnBuscar'><i class="fa fa-search"></i> Buscar</button>
                                               <!-- <button class="btn bg-olive margin" id='btnNuevo'><i class="fa fa-pencil"></i> Nuevo</button> -->
                                                <button class="btn bg-olive margin" id='btnImprimir'><i class="fa fa-print"></i> Imprimir</button>
                                                <button class="btn bg-olive margin" id='btnAtras' style="display: none;"><i class="fa fa-backward" ></i> Atrás</button>
                                                <button class="btn bg-olive margin" id='btnAdelante'>Adelante <i class="fa fa-forward"></i></button>
                                                <button class="btn bg-olive margin" id='btnBuscarIngreso'><i class="fa fa-search"></i> Buscar Ingreso Equipo</button>
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

                                        <div id="buscar_ingresos" title="BUSCAR INGRESOS">
                                            <table id="list3">
                                                <tr>
                                                    <td></td>
                                                </tr>
                                            </table>
                                            <div id="pager3"></div>
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
        <script src="proforma.js" type="text/javascript"></script>
        <link href="../../dist/css/style.css" rel="stylesheet" type="text/css" />
        <script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>
    </body>

</html>