<?php
session_start();
include '../../procesos/base.php';
include('../menu/app.php');
conectarse();
error_reporting(0);
$cont1 = 0;
$consulta = pg_query("select max(id_rubro) from rubro");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;

$consultaforma = pg_query("select id_clase,CLASE from  clase order by id_clase desc  ");
while ($row = pg_fetch_row($consultaforma)) {
    $campo_nombre_forma = $row[0];
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>TASAS POR TIPO TARIFA</title>
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
        <link href="../../dist/css/jquery-ui-1.10.4.custom.css" rel="stylesheet" type="text/css"/>            
        <link href="../../dist/css/ui.jqgrid.css" rel="stylesheet" type="text/css"/>  

    </head>
    <body class="skin-blue">
        <div class="wrapper">
            <?php banner_1(); ?>
            <?php menu_lateral_1(); ?>
            <div class="content-wrapper">
                <section class="content-header">
                    <h1>
                        Registro Tasas por Tipo Tarifa
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Procesos</a></li>
                        <li class="active">TASAS POR TIPO TARIFA</li>
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
                                                                <label>Fecha Registro:</label>
                                                                <div class="input-group">
                                                                    <input type="text" name="fecha_actual"  id="fecha_actual" readonly class="form-control"/>

                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </div>
                                                                </div> 
                                                            </div> 
                                                        </div>
                                                    </div>
                                                    <div id="estado" ></div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Tarifa:</label>
                                                        <select class="form-control" name="tipo_tarifa" id="tipo_tarifa">
                                                            <option    value="<?php echo $campo_nombre_forma ?>"  >SELECCIONE...</option>
                                                            <?php
                                                            $consultapro = pg_query("select id_clase,clase from  clase order by id_clase desc  ");
                                                            while ($row = pg_fetch_row($consultapro)) {
                                                                echo "<option id=$row[0] value=$row[0]>$row[1]</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <input type="hidden" name="id_tipo_tarifa"  id="id_tipo_tarifa" readonly class="form-control" />
                                                </div>                                                 
                                        </div>                                       
                                        <br>
                                        <br>
                                        <br>
                                        <h3 class="box-title">Rubros a Cobrar en Categoria</h3>

                                        <div class="row">
                                            <div class="col-mx-12">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Descripcion</label>
                                                        <input type="text" name="descripcion"  id="descripcion" placeholder="buscar..." class="form-control" />
                                                        <input type="hidden" name="cod_descripcion"  id="cod_descripcion" readonly class="form-control" />
                                                    </div>  
                                                </div>

                                                <div class="col-md-1 ">
                                                    <div class="form-group">
                                                        <label>M_X_M3</label>

                                                        <input type="text" name="mxm3"  id="mxm3" class="form-control" />
                                                        
                                                    </div>
                                                </div>

                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label>Base</label>
                                                        <input type="text" name="base"  id="base" class="form-control" />
                                                    </div> 
                                                </div>

                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label>Minimo</label>
                                                        <input type="text" name="minimo"  id="minimo"  class="form-control" />

                                                    </div>  
                                                </div> 
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label>Maximo</label>
                                                        <input type="text" name="maximo"  id="maximo"  class="form-control" />

                                                    </div>  
                                                </div> 
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div id="grid_container">
                                                <table id="list"></table>
                                                <div id="pager"></div>  
                                            </div>
                                        </div>   

                                        <div class="row">
                                            <div class="col-mx-12">
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                                <div id="buscar_inventario" title="BUSCAR INVENTARIO">
                                    <table id="list22">
                                        <tr>
                                            <td></td>
                                        </tr>
                                    </table>
                                    <div id="pager22"></div>
                                </div>

                                <div id="clave_permiso" title="PERMISOS">
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-md-6" >Ingrese la clave de seguridad</label>
                                            <div class="form-group col-md-6 no-padding">                                
                                                <input type="password" name="clave"  id="clave" required class="form-control" />
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
                                            <button class="btn bg-olive margin" id='btnModificar'><i class="fa fa-save"></i> Modificar</button>
                                            <button class="btn bg-olive margin" id='btnNuevo'><i class="fa fa-pencil"></i> Nuevo</button>                                       
                                            <button class="btn bg-olive margin" id='btnAnular'><i class="fa fa-remove"></i> Anular</button>
                                        </p> 
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
<script src="../../dist/js/validCampoFranz.js" type="text/javascript" ></script>
<script src="../../dist/js/alertify.min.js" type="text/javascript"></script>
<script src="../../dist/js/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>
<script src="../../dist/js/jquery.jqGrid.src.js" type="text/javascript"></script>
<script src="../../dist/js/grid.locale-es.js" type="text/javascript"></script>
<script src="../../dist/js/jquery.hotkeys.js" type="text/javascript"></script>
<script src="rubrosTarifa.js" type="text/javascript"></script>
<link href="../../dist/css/style.css" rel="stylesheet" type="text/css"/>     
<script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>
<script src="../../dist/js/menu.js" type="text/javascript"></script>
</body>
</html>