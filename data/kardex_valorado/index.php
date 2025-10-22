<?php
session_start();
include '../../procesos/base.php';
include('../menu/app.php');
conectarse();
error_reporting(0);

$cont1 = 0;
$consulta = pg_query("select max(id_inventario) from inventario");
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
        <title>KARDEX VALORADO</title>
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
        <link href="../../plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet"/>
        <link href="../../plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
        <link href="../../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
        <link href="../../dist/css/alertify.core.css" rel="stylesheet" />
        <link href="../../dist/css/alertify.default.css" id="toggleCSS" rel="stylesheet" />
        <link href="../../dist/css/jquery-ui-1.13.3.min.css" rel="stylesheet" type="text/css"/>            
        <link href="../../dist/css/ui.jqgrid.css" rel="stylesheet" type="text/css"/>  

    </head>
    <body class="skin-blue">
        <div class="wrapper">
            <?php banner_1(); ?>
            <?php menu_lateral_1(); ?>
            <div class="content-wrapper">
                <section class="content-header">
                    <h1>
                        Kardex valorado
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Procesos</a></li>
                        <li class="active">Kardex Valorado</li>
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
                                                                    <input type="text" name="fecha_actual"  id="fecha_actual" readonly class="form-control"/>
                                                                    <input type="hidden" name="comprobante"  id="comprobante" readonly class="form-control" value="<?php echo $cont1 ?>"/>
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
                                                                        <input type="text" name="hora_actual"  id="hora_actual" readonly  class="form-control timepicker"/>
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
                                                                <input type="text" name="digitador"  id="digitador" readonly value="<?php echo $_SESSION['nombres'] ?>" class="form-control" />
                                                                <input type="hidden" name="comprobante2"  id="comprobante2" readonly class="form-control">
                                                            </div>  
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Punto de Venta:</label>
                                                                <input type="text" name="punto_venta"  id="punto_venta" required readonly class="form-control" value="<?php echo $campo_punto_venta ?>"  /> 
                                                                <input type="hidden" name="punto_ventaid"  id="punto_ventaid"   required readonly class="form-control" value="<?php echo $campo_punto_ventaid ?>"  /> 

                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <h3 class="box-title">Productos</h3>

                                                <div class="row">
                                                    <div class="col-mx-12">
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>CÓDIGO BARRAS</label>
                                                                <input type="text" style="text-transform: uppercase"  name="codigo_barras"  id="codigo_barras" placeholder="Buscar..." class="form-control" />
                                                                <input type="hidden" name="cod_producto"  id="cod_producto" class="form-control" />
                                                            </div>  
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>CÓDIGO</label>
                                                                <input type="text" name="codigo"  id="codigo" placeholder="Buscar..." class="form-control" />
                                                            </div>  
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>PRODUCTO</label>
                                                                <input type="text" name="producto"  id="producto" placeholder="Buscar..." class="form-control" />
                                                            </div>  
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-mx-12">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>FECHA INICIO:</label>
                                                                <input type="text" name="fecha_inicio"  id="fecha_inicio" readonly class="form-control" />
                                                            </div>  
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>FECHA FIN:</label>
                                                                <input type="text" name="fecha_fin"  id="fecha_fin" readonly class="form-control" />
                                                            </div>  
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <button class="btn bg-olive margin" id='btnGenerar'><i class="fa fa-save"></i> Generar</button>  
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
        <script src="../../dist/js/validCampoFranz.js" type="text/javascript" ></script>
        <script src="../../dist/js/alertify.min.js" type="text/javascript"></script>
        <script src="../../dist/js/jquery-ui-1.13.3.min.js" type="text/javascript"></script>
        <script src="../../dist/js/jquery.jqGrid.src.js" type="text/javascript"></script>
        <script src="../../dist/js/grid.locale-es.js" type="text/javascript"></script>
        <script src="../../dist/js/jquery.hotkeys.js" type="text/javascript"></script>
        <script src="kardex.js" type="text/javascript"></script>
        <link href="../../dist/css/style.css" rel="stylesheet" type="text/css"/>     
        <script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>
    </body>
</html>