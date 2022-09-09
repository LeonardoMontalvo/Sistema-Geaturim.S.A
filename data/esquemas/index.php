<?php
session_start();
include('../menu/app.php');
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>EMPRESAS</title>
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
    <link href="../../dist/css/jquery-ui-1.10.4.custom.css" rel="stylesheet" type="text/css" />
    <link href="../../dist/css/ui.jqgrid.css" rel="stylesheet" type="text/css" />
    <style>
        .ui-autocomplete {
            max-height: 200px;
            overflow-y: auto;
            /* prevent horizontal scrollbar */
            overflow-x: hidden;
        }

        input:required {
            border: 1px dashed red;
        }

        select:required {
            border: 1px dashed red;
        }

        .ui-jqgrid tr.jqgrow td {
            white-space: normal !important;
        }

        textarea {
            resize: none;
        }

        .titulo_formulario {
            font-size: 24px;
            background: #3c8dbc;
            padding-left: 5px;
            margin-bottom: 5px;
            color: #fff;
        }

        .boton {
            background: red;
        }

        @keyframes anim_fondo {
            0% {
                background: #FFEE58;
            }

            50% {
                background: #55FFEE58;
            }

            100% {
                background: #00FFEE58;
            }
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
                    Empresas BD
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Empresas BD</a></li>
                    <!-- <li class="active">Clientes</li> -->
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab_1" data-toggle="tab">Empresas</a></li>
                                <!-- <li><a href="#tab_2" data-toggle="tab">Usuarios</a></li> -->
                            </ul>
                            <div class="box-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table id="tabla_esquemas"></table>
                                                <div id="pager_esquemas"></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button id="crear_empresa" class="btn bg-olive"><span class="glyphicon glyphicon-plus"></span> Crear Empresa</button>
                                                <button id="duplicar_empresa" class="btn bg-olive"><span class="glyphicon glyphicon-duplicate"></span> Duplicar Empresa</button>
                                                <button id="duplicar_maestros_empresa" class="btn bg-olive"><span class="glyphicon glyphicon-duplicate"></span> Duplicar Maestros Empresa</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_2" style="height: 300px">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table id="tabla_esquemas_usuarios"></table>
                                                <div id="pager_esquemas_usuarios"></div>
                                            </div>
                                        </div>
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

    <div id="dialog_empresa">
        <form id="crear_empresa_form">
            <div class="titulo_formulario">
                <b> Base de Datos</b>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Nombre:</label>
                        <input style="text-transform: uppercase;" id="nombre_esquema" name="nombre_esquema" required type="text" class="form-control input-sm">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Descripción:</label>
                        <textarea style="text-transform: uppercase;" class="form-control" id="descripcion_esquema" name="descripcion_esquema" type="text" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Usuario Administrador:</label>
                        <input class="form-control" id="usuario_admin" name="usuario_admin" type="text">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Color:</label>
                        <input id="color_esquema" name="color_esquema" type="color">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group" style="background: #B0BEC5; padding-top: 5px;">
                        <label for="">Crear empresa con información de prueba: <input id="datos_prueba" name="datos_prueba" type="checkbox"></label>
                    </div>
                </div>
            </div>
            <hr style="border: black 1px solid;">
            <div id="info_empresa">
                <div class="titulo_formulario">
                    <b> Datos de la empresa</b>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">RUC Empresa:</label>
                            <input minlength="13" maxlength="13" id="ruc_empresa" name="ruc_empresa" required type="text" class="form-control input-sm">
                        </div>

                        <div class="form-group">
                            <label for="">Nombre Empresa:</label>
                            <input style="text-transform: uppercase;" id="nombre_empresa" name="nombre_empresa" required type="text" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Propietario Empresa:</label>
                            <input style="text-transform: uppercase;" id="propietario_empresa" name="propietario_empresa" required type="text" class="form-control input-sm">
                        </div>
                        <div class="form-group">
                            <label for="">Nombre Comercial:</label>
                            <input style="text-transform: uppercase;" id="nombre_comercial" name="nombre_comercial" required type="text" class="form-control input-sm">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Dirección Empresa:</label>
                            <input style="text-transform: uppercase;" id="direccion_empresa" name="direccion_empresa" required type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">País:</label>
                            <input value="ECUADOR" style="text-transform: uppercase;" id="pais" name="pais" required type="text" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Ciudad:</label>
                            <input value="IBARRA" style="text-transform: uppercase;" id="ciudad" name="ciudad" required type="text" class="form-control input-sm">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Teléfono Empresa:</label>
                            <input data-inputmask='"mask": "(999) 999-999"' data-mask id="telefono" name="telefono" required type="text" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Celular Empresa:</label>
                            <input data-inputmask='"mask": "(999) 9999-999"' data-mask id="celular" name="celular" required type="text" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Email Empresa:</label>
                            <input id="email" name="email" type="text" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Página Web Empresa:</label>
                            <input id="pagina_web" name="pagina_web" type="text" class="form-control input-sm">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Obligación:</label>
                            <select required name="obligacion" id="obligacion" class="form-control input-sm">
                                <option value="NO">NO</option>
                                <option value="SI">SI</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Contribuyente Especial:</label>
                            <input id="contribuyente" name="contribuyente" value="" type="text" class="form-control input-sm">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="">Establecimiento:</label>
                        <input minlength="3" maxlength="3" id="establecimiento" name="establecimiento" required value="001" type="text" class="form-control input-sm">
                    </div>
                    <div class="col-md-6">
                        <label for="">Punto Emisión:</label>
                        <input minlength="3" maxlength="3" id="p_emision" name="p_emision" required value="001" type="text" class="form-control input-sm">
                    </div>
                </div>
                <!--  <div class="row">
                                        <div class="col-md-6">
                                            <label for="">Token:</label>
                                            <input required value="214" required type="text" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">Clave Token:</label>
                                            <input required value="214" required type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="">Porcentaje para Tarjetas de Crédito:</label>
                                            <input type="text" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">Num Items:</label>
                                            <input required value="30" required type="text" class="form-control">
                                        </div>
                                    </div> -->
                <hr style="border: black 1px solid;">
                <div class="titulo_formulario" style="margin-top: 15px;">
                    <b> Punto de Venta Principal</b>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Nombre:</label>
                            <input style="text-transform: uppercase;" id="punto_venta_nombre" name="punto_venta_nombre" required value="" type="text" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Dirección:</label>
                            <input style="text-transform: uppercase;" id="punto_venta_direccion" name="punto_venta_direccion" required value="" type="text" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Teléfono: </label>
                            <input id="punto_venta_telefono" name="punto_venta_telefono" value="" type="text" class="form-control input-sm">
                        </div>
                    </div>
                </div>
            </div>
            <input hidden type="submit" id="crear_empresa_form_submit">
        </form>
    </div>
    <div id="dialog_duplicar">
        <form id="duplicar_empresa_form">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">¿Qué empresa quiere duplicar? </label>
                        <select required class="form-control" name="esquema_origen" id="esquema_origen">
                            <option value="">--Seleccione Origen--</option>
                        </select>
                    </div>
                </div>
                <!-- <div class="col-md-1">
                <span class="glyphicon glyphicon-arrow-right"></span>
            </div> -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">¿Dónde duplicar?</label>
                        <select required class="form-control" name="esquema_destino" id="esquema_destino">
                            <option value="">--Seleccione Destino--</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label style="color:#fff" for="">...</label><br>
                        <button id="duplicar" type="button" class=" btn bg-olive"><span class="glyphicon glyphicon-play"></span> Duplicar</button>
                    </div>
                </div>
            </div>
            <input id="duplicar_empresa_submit" type="submit" hidden>
        </form>
    </div>
    <div id="dialog_validar_acceso">
        <div class="col-md-12">
            <div class="form-group">
                <label for="">Ingrese la clave de seguridad para confirmar la acción:</label>
                <span style="display:none; color:red;" id="clave_invalida">Clave Invalida.</span>
                <input class="form-control" type="password" name="clave_seguridad" id="clave_seguridad">
            </div>
        </div>
    </div>
    <!-- <div id="dialog_crear_usuario">
        <form id="crear_usuario_form">
            <div class="titulo_formulario">
                <b> Usuario</b>
            </div>
        </form>
    </div> -->

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
    <script src="esquemas.js" type="text/javascript"></script>
    <script src="esquemas_usuarios.js" type="text/javascript"></script>
    <link href="../../dist/css/style.css" rel="stylesheet" type="text/css" />
    <script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>
</body>

</html>