<?php
session_start();
include '../../procesos/base.php';
include('../menu/app.php');
conectarse();
error_reporting(0);
$consulta8 = pg_query("select * from punto_venta_empresa  left join punto_venta  on punto_venta_empresa.id_punto_venta=punto_venta.id_punto_venta  where  
punto_venta_empresa.id_usuario='$_SESSION[id]'  ORDER BY id_punto_venta_empresa ASC");
while ($row = pg_fetch_row($consulta8)) {
    $campo_punto_ventaid = $row[5];
}
$consulta7 = pg_query("select * from punto_venta_empresa  left join punto_venta  on punto_venta_empresa.id_punto_venta=punto_venta.id_punto_venta  where  
punto_venta_empresa.id_usuario='$_SESSION[id]'  ORDER BY id_punto_venta_empresa ASC");
while ($row = pg_fetch_row($consulta7)) {
    $campo_punto_venta = $row[6];
}
$cont1 = 0;
$consulta = pg_query("select max(id_anticipo_clientes) from anticipo_clientes");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;

$consulta = pg_query("select max(comprobante) from anticipo_clientes");
while ($row = pg_fetch_row($consulta)) {
    $num_nota_credito = $row[0];
}
$consulta = pg_query("select max(comprobante)  from anticipo_clientes,  punto_venta_empresa where    
anticipo_clientes.id_empresa=$campo_punto_ventaid  and punto_venta_empresa.id_usuario='$_SESSION[id]'  ");
while ($row = pg_fetch_row($consulta)) {
    $num_nota_credito = $row[0];
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>ANTICIPO CLIENTES</title>
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
                        ANTICIPO CLIENTES
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Procesos</a></li>
                        <li class="active">Anticipo Clientes</li>
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
                                                                    <input type="text" name="fecha_actual"  id="fecha_actual" readonly class="form-control timepicker"/>
                                                                    <!--<input type="hidden" name="comprobante"  id="comprobante" readonly class="form-control" value="<?php echo $cont1 ?>"/>-->
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
                                                                <label>Num Comprobante:</label>
                                                                <input type="text" name="comprobante" id="comprobante" readonly class="form-control" value="<?php echo $cont1 ?>" />
                                                                <input type="hidden" name="num_oculto"  id="num_oculto" required class="form-control" value="<?php echo $num_nota_credito ?>" />  
                                                            </div>
                                                        </div>
                                                    </div>
                                                      <div id="estado" style="margin-top: -10px"><h3></h3></div>
                                                </div>
                                                <br />
                                                <div class="row">
                                                    <div class="col-md-12">   
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-md-4" >Num Comprobante:<font color="red">*</font></label>
                                                                <div class="form-group col-md-8 p-0">
                                                                    <input type="text" name="secuencial"  id="secuencial" required data-inputmask='"mask": "999999999"' data-mask class="form-control" />                               
                                                                </div> 
                                                            </div> 

                                                            <div class="form-group">
                                                                <label class="col-md-4 " >CI. Identidad/RUC: <font color="red">*</font></label>
                                                                <div class="form-group col-md-8 p-0">                                
                                                                    <input type="text" name="ruc_ci"  id="ruc_ci" required placeholder="Buscar....." class="form-control" />
                                                                    <input type="hidden" name="id_cliente"  id="id_cliente" class="form-control" />
                                                                </div> 
                                                            </div> 


                                                            <div class="form-group">
                                                                <label class="col-md-4" >Monto Anticipo:<font color="red">*</font></label>
                                                                <div class="form-group col-md-8 p-0">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon">
                                                                            <i class="fa fa-money"></i>
                                                                        </div>
                                                                        <input type="text" name="monto" id="monto" placeholder="0.00" class="form-control"/>
                                                                    </div>
                                                                </div> 
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-md-3" >Nombres:<font color="red">*</font></label>
                                                                <div class="form-group col-md-9 p-0">                                
                                                                    <input type="text" name="nombres_completos"  id="nombres_completos" required placeholder="Buscar....." class="form-control" />
                                                                </div> 
                                                            </div> 


                                                            <div class="form-group">
                                                                <label class="col-md-3" >Forma Pago:<font color="red">*</font></label>
                                                                <div class="form-group col-md-8 p-0">                                
                                                                    <select class="form-control" name="formaspago_mixto" id="formaspago_mixto" >
                                                                        <option value="Contado">Contado</option>                                                                      
                                                                        <option value="Cheque">Cheque</option>                                                                     
                                                                        <option value="Transferencias">Transferencias</option>

                                                                    </select>
                                                                </div> 
                                                            </div> 

                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="col-md-3">Seleccione Cta Contable: </label>
                                                                    <div class="form-group col-md-4 p-0">
                                                                        <input type="text" name="cuenta_contable"  id="cuenta_contable"  class="form-control" disabled="disabled" />
                                                                        <input type="hidden" name="idCuenta"  id="idCuenta" />
                                                                    </div>
                                                                    <div class="form-group col-md-4 p-0">
                                                                        <button class="btn btn-secondary" id="btnCuenta" name="btnCuenta" disabled="disabled">Seleccionar Cuenta</button>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                        </div>



                                                        <!--                                                            <div class="col-md-7">
                                                                                                                        <div class="form-group">
                                                                                                                            <label class="col-md-5">Fecha Registro:</label>
                                                                                                                            <div class="form-group col-md-7 p-0">
                                                                                                                                <input type="date" name="fecha_registro"  id="fecha_registro"  class="form-control timepicker"/>
                                                                                                                            </div> /.input group 
                                                                                                                        </div> /.form group 
                                                                                                                    </div>-->
                                                        <div class="col-md-9">
                                                            <div class="form-group">
                                                                <label class="col-md-4" >Observación:<font color="red">*</font></label>
                                                                <div class="form-group col-md-9 p-0">                                
                                                                    <textarea type="text" name="comentario"  id="comentario" required  class="form-control" ></textarea>
                                                                </div> 
                                                            </div> 
                                                        </div> 

                                                    </div>  
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <p>
                                                <button class="btn bg-olive margin" id='btnGuardar'><i class="fa fa-save"></i> Guardar</button>
                                                <button class="btn bg-olive margin" id='btnModificar'><i class="fa fa-edit"></i> Modificar</button>
                                                <button class="btn bg-olive margin" id='btnBuscar'><i class="fa fa-search"></i> Buscar</button>
                                                <button class="btn bg-olive margin" id='btnNuevo'><i class="fa fa-pencil"></i> Nuevo</button>
                                                <button class="btn bg-olive margin" id='btnImprimir'><i class="fa fa-print"></i> Imprimir</button>
                                                <button class="btn bg-olive margin" id='btnAtras'><i class="fa fa-backward"></i> Atrás</button>
                                                <button class="btn bg-olive margin" id='btnAdelante'>Adelante <i class="fa fa-forward"></i></button>
                                                <button class="btn bg-olive margin" id='btnEliminar'><i class="fa fa-remove"></i> Eliminar</button>

                                            </p> 
                                        </div>
                                    </div>

                                    <div id="buscar_anticipo_cliente" title="BUSCAR ANTICIPO CLIENTES">
                                        <table id="list2"><tr><td></td></tr></table>
                                        <div id="pager2"></div>
                                    </div>
                                    <div id="cuentas" title="Búsqueda Plan de Cuentas" class="">
                                        <table id="list44"><tr><td></td></tr></table>
                                        <div id="pager44"></div>
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
                                        <label>¿Está seguro de eliminar Beneficiario?</label>  
                                        <br />
                                        <button class="btn btn-primary" id='btnAceptar'><i class="icon-ok"></i> Aceptar</button>
                                        <button class="btn btn-primary" id='btnSalir'><i class="icon-remove-sign"></i> Cancelar</button>
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
        <script src="anticipo_clientes.js" type="text/javascript"></script>
        <link href="../../dist/css/style.css" rel="stylesheet" type="text/css"/>     
        <script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>
    </body>
</html>