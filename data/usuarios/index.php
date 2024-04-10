<?php
session_start();
include('../menu/app.php');
?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>USUARIOS</title>
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
        <link href="../../plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="../../dist/css/alertify.core.css" rel="stylesheet" />
        <link href="../../dist/css/alertify.default.css" id="toggleCSS" rel="stylesheet" />
        <link href="../../dist/css/jquery-ui-1.10.4.custom.css" rel="stylesheet" type="text/css" />
        <link href="../../dist/css/ui.jqgrid.css" rel="stylesheet" type="text/css" />
        <style>
            .fixed-dialog {
                position: fixed;
                z-index: 1030 !important;
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
                        Registro Usuarios
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Ingresos</a></li>
                        <li class="active">Usuarios</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="nav-tabs-custom">

                                <ul class="nav nav-tabs">

                                    <li class="active"><a href="#tab_1" data-toggle="tab">Registro Usuarios</a></li>
                                    <li><a href="#tab_2" data-toggle="tab">Asignar Funciones</a></li>


                                </ul>   





                                <div class="box-body">
                                    <div class="row">

                                        <form id="clientes_form" name="clientes_form" method="post">
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab_1">
                                                    <div class="tabbable" id="centro">
                                                        <!-- <fieldset> -->
                                                        <table id="list"></table>
                                                        <div id="pager"></div>
                                                        <!-- </fieldset>    -->
                                                    </div>
                                                </div>



                                                <div class="tab-pane" id="tab_2" style="height: 300px">
                                                    <div class="tabbable" id="centro_acciones">
                                                        <!-- <fieldset> -->
                                                        <table id="list_acciones"></table>
                                                        <div id="pager_acciones"></div>
                                                        <!-- </fieldset>    -->
                                                    </div>


                                                </div>
                                            </div>
                                        </form> 


                                    </div>
                                </div>
                            </div>
                            </section>
                            <div id="asignar_puntos_venta">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="">Seleccione los puntos de venta:</label>
                                        <select style="width: 100%;" class="form-control" name="puntos_venta[]" id="puntos_venta" multiple>
                                        </select>
                                    </div>
                                </div>
                            </div>
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
                    <script src='../../plugins/select2/select2.full.min.js'></script>
                    <script src="../../dist/js/app.min.js" type="text/javascript"></script>
                    <script src="../../dist/js/validCampoFranz.js" type="text/javascript"></script>
                    <script src="../../dist/js/alertify.min.js" type="text/javascript"></script>
                    <script src="../../dist/js/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>
                    <script src="../../dist/js/jquery.jqGrid.src.js" type="text/javascript"></script>
                    <script src="../../dist/js/grid.locale-es.js" type="text/javascript"></script>
                    <script src="usuarios.js" type="text/javascript"></script>
                    <link href="../../dist/css/style.css" rel="stylesheet" type="text/css" />
                    <script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>

                    </body>

                    </html>