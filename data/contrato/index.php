<?php
session_start();
include('../menu/app.php');
?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>CONTRATOS</title>
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
        <link href="../../plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
        <link href="../../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
        <link href="../../dist/css/alertify.core.css" rel="stylesheet" />
        <link href="../../dist/css/alertify.default.css" id="toggleCSS" rel="stylesheet" />
        <link href="../../dist/css/jquery-ui-1.13.3.min.css" rel="stylesheet" type="text/css" />
        <link href="../../dist/css/ui.jqgrid.css" rel="stylesheet" type="text/css" />
        <!--Select 2-->
        <link href="../../plugins/select2/select2.min.css" rel="stylesheet" />
        <style>
            .form-group.required label:after {
                content: '*';
                color: red;
            }
            .ui-jqgrid tr.jqgrow td {
                word-wrap: break-word; /* IE 5.5+ and CSS3 */
                white-space: pre-wrap; /* CSS3 */
                white-space: -moz-pre-wrap; /* Mozilla, since 1999 */
                white-space: -pre-wrap; /* Opera 4-6 */
                white-space: -o-pre-wrap; /* Opera 7 */
                overflow: hidden;
                height: auto;
                vertical-align: middle;
                padding-top: 3px;
                padding-bottom: 3px
            }
        </style>
    </head>

    <body class="skin-blue">
        <div class="wrapper">
            <?php banner_1(); ?>
            <?php menu_lateral_1(); ?>
            <div class="content-wrapper">
                <section class="content-header">
                    <h1>
                        Registro Contratos
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Procesos</a></li>
                        <li class="active">Contratos</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab_contrato" data-toggle="tab">Contrato</a></li>
                                    <li><a href="#tab_operacion" data-toggle="tab">Operaciones del Contrato</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_contrato">
                                        <div>
                                            <form id="form_contrato" autocomplete="off">
                                                <div class="row">
                                                    <div class="col-md-4 col-md-offset-">
                                                        <div class="form-group required">
                                                            <label for="nro_contrato">Nro. Contrato</label>
                                                            <input id="nro_contrato" name="nro_contrato" type="number" class="form-control" min="1" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group required">
                                                            <label for="id_cliente">Cliente:</label>
                                                            <select name="id_cliente" id="id_cliente" style="width: 100%" class="form-control" required></select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group required">
                                                            <label for="fecha_contrato">Fecha del contrato:</label>
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </div>
                                                                <input id="fecha_contrato" name="fecha_contrato" type="date" class="form-control" class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group required">
                                                            <label for="fecha_salida">Fecha de inicio del viaje:</label>
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </div>
                                                                <input id="fecha_salida" name="fecha_salida" type="date" class="form-control" class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group required">
                                                            <label for="fecha_retorno">Fecha de retorno del viaje:</label>
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </div>
                                                                <input id="fecha_retorno" name="fecha_retorno" type="date" class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group required">
                                                            <label for="nro_dias">Dias asignados:</label>
                                                            <input id="nro_dias" name="nro_dias" type="number" class="form-control" min="1" value="0" required>
                                                        </div>
                                                        <div class="form-group required">
                                                            <label for="nro_personas">Número pasajeros:</label>
                                                            <input id="nro_personas" name="nro_personas" type="number" class="form-control" min="1" value="0" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="card">
                                                            <div class="card-header">Valores</div>
                                                            <div class="card-body">
                                                                <div class="col-md-6">
                                                                    <div class="form-group required">
                                                                        <label for="valor">Total contrato:</label>
                                                                        <div class="input-group">
                                                                            <div class="input-group-addon">
                                                                                <b>$</b>
                                                                            </div>
                                                                            <input id="valor" name="valor" value="0" min="0.01" step="0.01" type="number" class="form-control" required>
                                                                        </div>
                                                                    </div>
                                                                    <!--<div class="form-group required">
                                                                        <label for="abono">Abono contrato:</label>
                                                                        <div class="input-group">
                                                                            <div class="input-group-addon">
                                                                                <b>$</b>
                                                                            </div>
                                                                            <input id="abono" name="abono" value="0" min="1" type="number" class="form-control" min="1" required>
                                                                        </div>
                                                                    </div>-->
                                                                    <!--<div class="form-group">
                                                                        <label for="restante">Saldo restante:</label>
                                                                        <div class="input-group">
                                                                            <div class="input-group-addon">
                                                                                <b>$</b>
                                                                            </div>
                                                                            <input id="restante" value="0" type="number" readonly class="form-control" min="1">
                                                                        </div>
                                                                        <small id="error_restante" class="float-end" style="color: red; display: none">
                                                                            <b>
                                                                                Hay una inconsistencia en el saldo restante,
                                                                                revise el valor del contrato ingresado y las
                                                                                operaciones del contrato.
                                                                            </b>
                                                                        </small>
                                                                    </div>-->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="submit" id="submit_form_contrato" value="submit" hidden>
                                            </form>
                                            <div class="box-group" id="accordion">
                                                <div class="panel box box-primary">
                                                    <div class="box-header with-border">
                                                        <h4 class="box-title">
                                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseRuta">
                                                                Ruta Contrato
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapseRuta" class="panel-collapse collapse">
                                                        <div class="box-body">
                                                            <form id="form_ruta" autocomplete="off">
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <div class="form-group required">
                                                                            <label for="hora_salida">Hora de salida:</label>
                                                                            <input type="time" id="hora_salida" name="hora_salida" class="form-control" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group required">
                                                                            <label for="id_lugar_origen">Lugar de origen:</label>
                                                                            <div class="input-group input-group-sm">
                                                                                <select name="id_lugar_origen" id="id_lugar_origen" style="width:100%" class="form-control
                                                                                        required">
                                                                                </select>
                                                                                <div class="input-group-btn">
                                                                                    <button class="btn btn-primary" id="btnAgregarLugarO">
                                                                                        <i class="fa fa-plus"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group required">
                                                                            <label for="id_lugar_destino">Lugar de destino:</label>
                                                                            <div class="input-group input-group-sm">
                                                                                <select name="id_lugar_destino" id="id_lugar_destino" style="width:100%" class="form-control
                                                                                        required">
                                                                                </select>
                                                                                <div class="input-group-btn">
                                                                                    <button class="btn btn-primary" id="btnAgregarLugarD">
                                                                                        <i class="fa fa-plus"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group required">
                                                                            <label for="ruta_completa">Ruta:</label>
                                                                            <input style="text-transform: uppercase" type="text" name="ruta_completa" id="ruta_completa" class="form-control" required>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label for="comentario">Comentario:</label>
                                                                            <input style="text-transform: uppercase" type="text" name="comentario" id="comentario" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <input type="submit" id="submit_form_ruta" value="submit" hidden>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel box box-primary">
                                                    <div class="box-header with-border">
                                                        <h4 class="box-title">
                                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseVehiculo">
                                                                Vehículos Contrato
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapseVehiculo" class="panel-collapse collapse">
                                                        <div class="box-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <form id="form_vehiculo" autocomplete="off">
                                                                        <div class="form-group required">
                                                                            <label for="id_vehiculo">Vehículo:</label>
                                                                            <select name="id_vehiculo" id="id_vehiculo" style="width: 100%" class="form-control" required></select>
                                                                        </div>
                                                                        <div class="form-group required">
                                                                            <label for="id_conductor">Conductor:</label>
                                                                            <select name="id_conductor" id="id_conductor" style="width: 100%" class="form-control" required></select>
                                                                        </div>
                                                                        <input type="submit" id="submit_form_vehiculo" value="submit" hidden>
                                                                    </form>
                                                                    <button id="btnAgregarVehiculo" class="btn btn-primary"> <i class="fa fa-plus"></i> Agregar</button>
                                                                </div>
                                                                <div class="col-md-6" id="div_tabla_v" style="margin-bottom: 30px">
                                                                    <small class="text-danger" id="error_v"><b>Debe agregar almenos un vehiculo*</b></small>
                                                                    <div>
                                                                        <!-- <fieldset> -->
                                                                        <table id="list"></table>
                                                                        <div id="pager"></div>
                                                                        <!-- </fieldset>    -->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="btn-group">
                                                <button id="btnGuardar" class="btn btn-success"> <i class="fa fa-save"></i> Guardar</button>
                                                <button id="btnModificar" class="btn btn-success"> <i class="fa fa-save"></i> Modificar</button>
                                                <button id="btnEliminar" class="btn btn-success"> <i class="fa fa-save"></i> Eliminar</button>
                                                <button id="btnNuevo" class="btn btn-success"><i class="fa fa-file"></i> Nuevo</button>
                                                <button id="btnBuscar" class="btn btn-success"> <i class="fa fa-search"></i> Buscar</button>
                                            </div>
                                            <div class="btn-group">
                                                <button id="btnImprimirC" class="btn btn-success"><i class="fa fa-print"></i> Imprimir Contrato</button>
                                                <button id="btnImprimirH" class="btn btn-success"><i class="fa fa-print"></i> Imprimir Hoja de Ruta</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_operacion">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <form id="form_operacion">
                                                    <div class="form-group required">
                                                        <label>Motivo:</label><br>
                                                        <!--<select name="accion" id="accion" class="form-control">
                                                            <option value="i">INGRESO</option>
                                                            <option value="e">EGRESO</option>
                                                            <option value="a">ABONO</option>
                                                        </select>-->
                                                        <input name="accion" type="radio" value="i" required> INGRESO <br>
                                                        <input name="accion" type="radio" value="e"> EGRESO <br>
                                                        <input name="accion" type="radio" value="a"> ABONO <br>
                                                    </div>
                                                    <div class="form-group required">
                                                        <label>Valor:</label>
                                                        <input 
                                                            id="valor_o" 
                                                            name="valor_o" 
                                                            type="number" 
                                                            class="form-control"
                                                            step="0.01"
                                                            min="0.01" required>
                                                    </div>
                                                    <div class="form-group required">
                                                        <label>Tipo de documento:</label> <br>
                                                        <!--<select 
                                                            required
                                                            name="tipo_documento" 
                                                            id="tipo_documento" 
                                                            class="form-control">
                                                            <option value="f">FACTURA</option>
                                                            <option value="n">NOTA DE VENTA</option>
                                                            <option value="r">RECIBO</option>
                                                        </select>-->
                                                        <input name="tipo_documento" type="radio" value="f">FACTURA<br>
                                                        <input name="tipo_documento" type="radio" value="n">NOTA DE VENTA<br>
                                                        <input name="tipo_documento" type="radio" value="r">RECIBO<br>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Número de documento:</label>
                                                        <input
                                                            id="nro_documento"
                                                            name="nro_documento"
                                                            class="form-control"
                                                            type="text">
                                                    </div>
                                                    <div class="form-group required">
                                                        <label>Descripción de operación</label>
                                                        <input id="descripcion" 
                                                               name="descripcion" 
                                                               type="text" 
                                                               style="text-transform: uppercase"
                                                               class="form-control" required>
                                                    </div>
                                                    <input type="submit" id="submit_form_operacion" value="submit" hidden>
                                                </form>
                                                <button id="btnAgregarOperacion" class="btn btn-primary"> <i class="fa fa-plus"></i> Agregar</button>
                                            </div>
                                            <div class="col-md-8" id="div_tabla_o">
                                                <div>
                                                    <!-- <fieldset> -->
                                                    <table id="list_o"></table>
                                                    <div id="pager_o"></div>
                                                    <!-- </fieldset>    -->
                                                </div>
                                                <table class="table" style="width: 100%;">
                                                    <tr>
                                                        <td>TOTAL INGRESOS: <span id="total_ingresos"></span></td>
                                                        <td>VALOR CONTRATO: <span id="total_contrato"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border-bottom: #000 1px solid">TOTAL EGRESOS: <span id="total_egresos"></span></td>
                                                        <td style="border-bottom: #000 1px solid">TOTAL CANCELACIÓN: <span id="total_cancelacion"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>UTILIDAD: <span id="total_utilidad"></span></b></td>
                                                        <td><b>SALDO RESTANTE: <span id="total_restante"></span></b></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                            </div>
                        </div>
                    </div>
                </section>

            </div>
            <?php footer(); ?>
        </div>
        <div id="contratos" title="Búsqueda de Contratos" class="">
            <table id="listContratos">
                <tr>
                    <td></td>
                </tr>
            </table>
            <div id="pagerContratos"></div>
        </div>
        <div id="lugar" title="AGREGAR LUGAR">
            <form id="form_lugar">
                <div class="form-group required">
                    <div class="control-group">
                        <label class="form-label" for="nombre_categoria">Nombre Lugar: </label>
                        <div class="controls">
                            <input type="text" name="nombre_lugar" id="nombre_lugar" class="campo" placeholder="Lugar" required />
                        </div>
                    </div>
                </div>
                <input type="submit" id="submit_form_lugar" value="submit" hidden>
            </form>
            <button class="btn btn-primary" id='btnGuardarLugar'>Guardar</button>
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
        <script src="contrato.js?v=1.00" type="text/javascript"></script>
        <link href="../../dist/css/style.css" rel="stylesheet" type="text/css" />
        <script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>

        <!--Select 2-->
        <script src="../../plugins/select2/select2.full.min.js" type="text/javascript"></script>
        <script src="../../plugins/select2/i18n/es.js" type="text/javascript"></script>
    </body>

</html>