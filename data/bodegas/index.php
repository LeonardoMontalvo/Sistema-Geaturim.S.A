<?php
session_start();
include('../menu/app.php');
include '../../procesos/base.php';

$formatosF = obtenerFormatos(1);
$formatosN = obtenerFormatos(2);
$formatosNC = obtenerFormatos(3);
$formatosFC = obtenerFormatos(4);
$formatosRC = obtenerFormatos(5);
$formatosRG = obtenerFormatos(6);
$formatosDC = obtenerFormatos(7);
$formatosProf = obtenerFormatos(8);

function obtenerFormatos($tipoformato) {
    $sqlFormatos = pg_query("select*from parametros_formatos_impresion where id_tipo_formato=$tipoformato order by id_formato asc");
    $formatos = pg_fetch_all($sqlFormatos);
    if (empty($formatos)) {
        $formatos = [];
    }
    return $formatos;
}
?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>BODEGAS</title>
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
        <style>
            #tab_3 .ui-jqgrid-title {
                font-size: 1.4rem;
            }

            .ui-jqgrid-sortable {
                white-space: normal !important;
                height: auto !important;
            }

            .btn-conf {
                border-radius: 15px;
                padding: 2px;
                outline: 1px solid gray;
                outline-offset: 2px;
            }

            .fixed-dialog {
                position: fixed;
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
                        Registro Sucursales
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Ingresos</a></li>
                        <li class="active">Bodegas</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab">Registro Bodega</a></li>
                            <li><a href="#tab_2" data-toggle="tab">Parómetros Bodega</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box box-primary">
                                            <div class="box-body">
                                                <div class="tabbable" id="centro">
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
                            <div class="tab-pane" id="tab_2">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="tabbable" id="centro_1">
                                            <table id="list_parametros"></table>
                                            <div id="pager_parametros"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </section>
                <div id="dialog_formatos_imp">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <form id="form_formatos_imp">
                                    <label for="">Formato factura de venta:</label>
                                    <select name="formato_imperesion_factura" id="formato_imperesion_factura" class="form-control">
                                        <option value="">--Seleccionar--</option>
                                        <?php foreach ($formatosF as $val) { ?>
                                            <option value="<?php echo $val["id_formato"] ?>"><?php echo $val["nombre_formato"]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <label for="">Formato nota de venta:</label>
                                    <select name="formato_imperesion_nota" id="formato_imperesion_nota" class="form-control">
                                        <option value="">--Seleccionar--</option>
                                        <?php foreach ($formatosN as $val) { ?>
                                            <option value="<?php echo $val["id_formato"] ?>"><?php echo $val["nombre_formato"]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <label for="">Formato nota de crédito:</label>
                                    <select name="formato_imperesion_nota_credito" id="formato_imperesion_nota_credito" class="form-control">
                                        <option value="">--Seleccionar--</option>
                                        <?php foreach ($formatosNC as $val) { ?>
                                            <option value="<?php echo $val["id_formato"] ?>"><?php echo $val["nombre_formato"]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <label for="">Formato factura de compra:</label>
                                    <select name="formato_imperesion_factura_compra" id="formato_imperesion_factura_compra" class="form-control">
                                        <option value="">--Seleccionar--</option>
                                        <?php foreach ($formatosFC as $val) { ?>
                                            <option value="<?php echo $val["id_formato"] ?>"><?php echo $val["nombre_formato"]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <label for="">Formato retención de compra:</label>
                                    <select name="formato_imperesion_retencion_compra" id="formato_imperesion_retencion_compra" class="form-control">
                                        <option value="">--Seleccionar--</option>
                                        <?php foreach ($formatosRC as $val) { ?>
                                            <option value="<?php echo $val["id_formato"] ?>"><?php echo $val["nombre_formato"]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <label for="">Formato retención de gasto:</label>
                                    <select name="formato_imperesion_retencion_gasto" id="formato_imperesion_retencion_gasto" class="form-control">
                                        <option value="">--Seleccionar--</option>
                                        <?php foreach ($formatosRG as $val) { ?>
                                            <option value="<?php echo $val["id_formato"] ?>"><?php echo $val["nombre_formato"]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <label for="">Formato Diario caja:</label>
                                    <select name="formato_imperesion_diario_caja" id="formato_imperesion_diario_caja" class="form-control">
                                        <option value="">--Seleccionar--</option>
                                        <?php foreach ($formatosDC as $val) { ?>
                                            <option value="<?php echo $val["id_formato"] ?>"><?php echo $val["nombre_formato"]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <label for="">Formato Proforma:</label>
                                    <select name="formato_imperesion_proforma" id="formato_imperesion_proforma" class="form-control">
                                        <option value="">--Seleccionar--</option>
                                        <?php foreach ($formatosProf as $val) { ?>
                                            <option value="<?php echo $val["id_formato"] ?>"><?php echo $val["nombre_formato"]; ?></option>
                                        <?php } ?>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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
        <script src="bodegas.js" type="text/javascript"></script>
        <link href="../../dist/css/style.css" rel="stylesheet" type="text/css" />
        <script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>
    </body>

</html>