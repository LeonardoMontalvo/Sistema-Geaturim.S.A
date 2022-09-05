<?php
session_start();
include('../menu/app.php');
include '../../procesos/base.php';
conectarse();
$consulta3 = pg_query("SELECT id_vendedor, ci_vendedor,nombre_vendedor,   direccion_vendedor FROM vendedores where estado ='Activo' order by id_vendedor desc");
while ($row = pg_fetch_row($consulta3)) {
    $campo_id_cliente = $row[0];
    $campo_identificacion_cliente = $row[1];
    $campo_nombre_cliente = $row[2];
    $campo_direccion_cliente = $row[3];
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>RUTAS</title>
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
            <div class="content-wrapper">
                <section class="content-header">
                    <h1>
                        Registro Rutas
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Ingresos</a></li>
                        <li class="active">Rutas</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-body">
                                    <div class="row">
                                        <form id="rutas_form" name="rutas_form" method="post">
                                            <div class="col-mx-12">                    
                                                <div class="col-md-4">                       

                                                    <div class="form-group">
                                                        <label>Nombre Ruta: <font color="red">*</font></label>
                                                        <input type="text" name="nombre_ruta"  id="nombre_ruta" class="form-control" />
                                                        <input type="hidden" name="id_ruta"  id="id_ruta"  required class="form-control" value="" />
                                                    </div>                          

                                                </div>

                                                <div class="col-md-4">                        

                                                    <div class="form-group">                               
                                                        <label>Descripcion Ruta:</label>
                                                        <input type="text" name="nombre" id="nombre"  class="form-control" />
                                                    </div>
                                                </div>


                                                <div class="input-group-addon">                              
                                                    <label class="col-md-2">Cedula Vendedor: </label>
                                                    <div class="form-group">                       
                                                        <input type="text" name="ruc_ci_cli"  id="ruc_ci_cli" placeholder="Buscar....." required class="form-control" value=""  />
                                                        <input type="hidden" name="id_vendedor"  id="id_vendedor" placeholder="Buscar....." required class="form-control" value="" />
                                                    </div>                                
                                                    <label class="col-md-2" >Nombre Vendedor:</label>
                                                    <div class="form-group">                 
                                                        <input type="text" name="nombre_vendedor"  id="nombre_vendedor" placeholder="Buscar....."  required class="form-control" value=""  />
                                                    </div>                             
                                                </div>

                                                <div class="col-md-12">
                                                    <!-- <fieldset> -->
                                                    <table id="list_grid_cargo"></table>
                                                    <div id="pager_grid_cargo"></div>
                                                    <!-- </fieldset>    -->
                                                </div> 
                                            </div>
                                        </form>
                                    </div>

                                    <div class="row">
                                        <div class="col-mx-12">
                                            <p>
                                                <button class="btn bg-olive margin" id='btnGuardar'><i class="fa fa-save"></i> Guardar</button>
                                                <button class="btn bg-olive margin" id='btnModificar'><i class="fa fa-edit"></i> Modificar</button>
                                                <!--<button class="btn bg-olive margin" id='btnEliminar'><i class="fa fa-remove"></i> Eliminar</button>-->
                                                <button class="btn bg-olive margin" id='btnBuscar'><i class="fa fa-search"></i> Buscar</button>
                                                <button class="btn bg-olive margin" id='btnNuevo'><i class="fa fa-pencil"></i> Nuevo</button>
                                            </p> 
                                        </div> 
                                        <div id="rutas" title="Búsqueda de Rutas" class="">
                                            <table id="list"><tr><td></td></tr></table>
                                            <div id="pager"></div>
                                        </div>                  

                                        <div id="clave_permiso_ven" title="PERMISOS">
                                            <table border="0" >
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

                                        <div id="seguro_ven">
                                            <label>¿Está seguro de eliminar Rutas?</label>  
                                            <br />
                                            <button class="btn btn-primary" id='btnAceptar'><i class="icon-ok"></i> Aceptar</button>
                                            <button class="btn btn-primary" id='btnSalir'><i class="icon-remove-sign"></i> Cancelar</button>
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
        <script src="rutas.js" type="text/javascript"></script>
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