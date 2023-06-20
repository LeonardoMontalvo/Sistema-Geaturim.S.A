<?php
session_start();
include('../menu/app.php');
include '../../procesos/base.php';
conectarse();
error_reporting(0);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
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
        <link href="../../dist/css/jquery-ui-1.10.4.custom.css" rel="stylesheet" type="text/css"/>            
        <link href="../../dist/css/ui.jqgrid.css" rel="stylesheet" type="text/css"/> 
    </head>
    <body class="skin-blue">
        <div class="wrapper">
            <?php banner_1(); ?>
            <?php menu_lateral_1(); ?>
            <section class="content">
                <div class="content-wrapper">
                    <section class="content-header">
                        <h1>
                            Promociones Ventas
                        </h1>
                        <ol class="breadcrumb">
                            <li><a href=""><i class="fa fa-dashboard"></i> Ingresos</a></li>
                            <li class="active">Clientes</li>
                        </ol>
                    </section>

                    <!-- Main content -->
                    <section class="content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="nav-tabs-custom">

                                    <div class="box-body">
                                        <div class="row">
                                            <form id="nomina_form" name="nomina_form" method="post">
                                                <div class="tab-content">

                                                    <div class="box-body">
                                                        <span style="font-size: 2.2rem; font-weight: bold; color:#37474F;">PROMOCIONES</span>
                                                        <div class="row">
                                                            <div class="col-md-4" style="border: solid 1px; padding: 15px;">
                                                                <div class="form-group">
                                                                    <label for="">Descripción promociones:</label>
                                                                    <input style="text-transform: uppercase;" id="desc_descripcion" placeholder="INGRESE DESCRIPCIÓN" class="form-control" type="text">
                                                                    <label for=""> Fecha Desde:</label>
                                                                    <input type="date" name="fecha_desde" id="fecha_desde" class="form-control timepicker" />
                                                                    <label for=""> Fecha Hasta:</label>
                                                                     <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control timepicker" />
                                                                    <label for=""> Categoría:</label>
                                                    
                                                                  <select class="form-control" name="categoria" id="categoria" required>
                                                                            <option selected="0" id="0" value="">..Todos..</option>
                                                                          
                                                                             <?php
                                                                        $consultapro = pg_query("select * from categoria order by id_categoria asc  ");
                                                                        while ($row = pg_fetch_row($consultapro)) {
                                                                            echo "<option id=$row[0] value=$row[0]>$row[1]</option>";
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                        </select>
                                                                    <input type="hidden" name="id_categoria" id="id_categoria" required class="form-control" />
                                                                    <label for="">Aplicar Porcentaje %:</label>
                                                                    <input min="0" max="100" id="porcentaje_promo" placeholder="INGRESE PORCENTAJE X" class="form-control" type="number">
<!--                                                                    <div id="div_sel_desc_prod" style="display: none;">
                                                                        <label for="">Productos con descuento:</label> <br>
                                                                        <button id="btn_sel_desc_prods" class="btn btn-primary btn-block" type="button"><i class="fa fa-list"></i> Seleccionar Categoria</button>
                                                                    </div>-->
                                                                    <div style="margin-top: 15px;" id="div_guardar_desc">
                                                                        <button type="button" id="btn_add_promocion" class="btn btn-success"><i class="fa fa-plus"></i> Agregar</button>
                                                                    </div>
                                                                    <div style="margin-top: 15px; display:none;" id="div_modificar_desc">
                                                                        <button type="button" id="btn_update_descuento" class="btn btn-success"><i class="fa fa-save"></i> Modificar</button>
                                                                        <button type="button" id="btn_cancel_update" class="btn btn-danger"><i class="fa fa-plus"></i> Cancelar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <table id="list_descuentos">
                                                                    <tr>
                                                                        <td></td>
                                                                    </tr>
                                                                </table>
                                                                <div id="pager_descuentos"></div>
                                                            </div>
                                                        </div>
                                                        <div id="dialogo_sel_prod_desc">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon"><b><i class="fa fa-search"></i> Buscar Categoria :</b></span>
                                                                        <input style="border: 1px solid;" id="buscar_prod_desc" class="form-control" type="text" placeholder="INGRESE NOMBRE O CÓDIGO DE BARRAS DEL ARTÍCULO">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <table id="list_det_descuentos">
                                                                        <tr>
                                                                            <td></td>
                                                                        </tr>
                                                                    </table>
                                                                    <div id="pager_det_descuentos"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </form>
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
    <script src="promocion_venta.js" type="text/javascript"></script>
    <script src="../../dist/js/app.min.js" type="text/javascript"></script>
    <script src="../../dist/js/validCampoFranz.js" type="text/javascript" ></script>
    <script src="../../dist/js/alertify.min.js" type="text/javascript"></script>
    <script src="../../dist/js/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>
    <script src="../../dist/js/jquery.jqGrid.src.js" type="text/javascript"></script>
    <script src="../../dist/js/grid.locale-es.js" type="text/javascript"></script>
    <link href="../../dist/css/style.css" rel="stylesheet" type="text/css"/>     
    <script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>
</body>
</html>