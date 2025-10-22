<?php
session_start();
include '../../procesos/base.php';
include('../menu/app.php'); 
conectarse();
error_reporting(0);

$cont1 = 0;
$consulta = pg_query("select max(id_conciliacion_bancaria) from conciliacion_bancaria");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>CONCILIACION BANCARIA</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />    
    <link href="../../font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />        
    <link href="../../plugins/icon/ionicons.min.css" rel="stylesheet" type="text/css" />    
    <link href="../../dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <link href="../../dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    
    <link href="../../plugins/morris/morris.css" rel="stylesheet" type="text/css" />
    <link href="../../plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <link href="../../plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
    <link href="../../plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
    <link href="../../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
    <link href="../../dist/css/alertify.core.css" rel="stylesheet" />
    <link href="../../dist/css/alertify.default.css" id="toggleCSS" rel="stylesheet" />
    <link href="../../dist/css/jquery-ui-1.13.3.min.css" rel="stylesheet" type="text/css"/>            
    <link href="../../dist/css/ui.jqgrid.css" rel="stylesheet" type="text/css"/> 
    <link href="../../plugins/iCheck/flat/blue.css" rel="stylesheet" type="text/css" />
  </head>
  <body class="skin-blue">
    <div class="wrapper">
      <?php banner_1(); ?>
      <?php menu_lateral_1(); ?>
      <div class="content-wrapper">
        <section class="content-header">
          <h1>
            Registro de Conciliación Bancaria
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Ingresos</a></li>
            <li class="active">Conciliacion Bancaria</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                <div class="box-body">
                  <div class="row">
                      <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">                        
                            <div class="col-mx-12"> 
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="col-md-4 " >Cuenta Bancaria:<font color="red">*</font></label>
                                  <div class="form-group col-md-5 p-0">                                
                                    <input type="text" name="cuenta"  id="cuenta"  class="form-control" />
                                    <input type="hidden" name="idCuenta"  id="idCuenta" class="form-control" />
                                    <input type="hidden" name="idConciliacion"  id="idConciliacion" class="form-control" />
                                    <input type="hidden" name="comprobante"  id="comprobante" class="form-control" value="<?php echo $cont1 ?>" />
                                  </div>
                                  <div class="form-group col-md-3 p-0">
                                    <button class="btn btn-secondary" id="btnCuenta" name="btnCuenta">Buscar</button>
                                  </div> 
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label class="col-md-4 ">Mes: <font color="red">*</font></label>
                                  <div class="form-group col-md-8 p-0"> 
                                    <select name="mes"  id="mes"  class="form-control">
                                      <option value="ENERO">ENERO</option>
                                      <option value="FEBRERO">FEBRERO</option>
                                      <option value="MARZO">MARZO</option>
                                      <option value="ABRIL">ABRIL</option>
                                      <option value="MAYO">MAYO</option>
                                      <option value="JUNIO">JUNIO</option>
                                      <option value="JULIO">JULIO</option>
                                      <option value="AGOSTO">AGOSTO</option>
                                      <option value="SEPTIEMBRE">SEPTIEMBRE</option>
                                      <option value="OCTUBRE">OCTUBRE</option>
                                      <option value="NOVIEMBRE">NOVIEMBRE</option>
                                      <option value="DICIEMBRE">DICIEMBRE</option>
                                    </select>                               
                                  </div> 
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label class="col-md-4 ">Año: <font color="red">*</font></label>
                                  <div class="form-group col-md-8 p-0"> 
                                  <select name="anio"  id="anio"  class="form-control">
                                    <?php
                                      date_default_timezone_set('America/Guayaquil');
                                      $anio = date('Y', time());
                                      for ($i=$anio-12; $i <=$anio ; $i++) { 
                                        echo "<option value='$i'>$i</option>";
                                      }
                                    ?> 
                                  </select>
                                  </div> 
                                </div>
                              </div>
                              <div class="col-md-12">
                                <label class="col-md-6 ">SALDO SEGÚN ESTADO DE CUENTA: <font color="red">*</font></label>
                                <div class="form-group col-md-3 p-0"> 
                                  <div class="form-group col-md-6 p-0">   
                                    <input type="text" name="saldo_estado1"  id="saldo_estado1"  class="form-control" style="visibility:hidden"/>
                                  </div>
                                  <div class="form-group col-md-6 p-0">                                
                                    <input type="text" name="saldo_estado"  id="saldo_estado"  class="form-control" />
                                  </div>
                                </div>
                                <div class="form-group col-md-3">  
                                </div> 
                              </div>
                              <div class="col-md-12 ">
                                <label class="col-md-6 ">SALDO LIBRO BANCOS: <font color="red">*</font></label>
                                <div class="form-group col-md-3 p-0">  
                                </div>
                                <div class="form-group col-md-3 p-0"> 
                                  <div class="form-group col-md-6 p-0">   
                                    <input type="text" name="saldo_libro1"  id="saldo_libro1"  class="form-control" style="visibility:hidden"/>
                                  </div>
                                  <div class="form-group col-md-6 p-0">                                
                                    <input type="text" name="saldo_libro"  id="saldo_libro"  class="form-control" />
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-mx-12">
                              <div class="col-md-12">
                                <h4>DEPÓSITOS EN TRÁNSITO</h4>
                                <div class="form-group col-md-8">
                                  <label class="col-md-2">Descripción: </label>
                                  <div class="form-group col-md-10 p-0">                                
                                    <input type="text" name="des_deposito"  id="des_deposito"  class="form-control" />
                                  </div> 
                                </div>
                                <div class="form-group col-md-3">
                                  <label class="col-md-3">Valor: </label>
                                  <div class="form-group col-md-6 p-0">                                
                                    <input type="text" name="val_deposito"  id="val_deposito"  class="form-control" value="0.000" />
                                  </div> 
                                </div>
                              </div>
                            </div>

                              <div class="col-mx-12">
                              <hr /><hr /><hr /><hr /><hr /><hr /><hr /><hr /><hr /><hr /><hr /><hr /><hr /><hr />
                                <div id="grid_deposito">
                                  <table id="list_deposito"></table>
                                  <!--<div id="pager_dep" name="pager_dep"></div>  --> 
                                </div>
                              </div>


                            <div class="col-mx-12">
                              <div class="col-md-12">
                                <h4>CHEQUES GIRADOS Y NO COBRADOS</h4>
                                <div class="form-group col-md-8">
                                  <label class="col-md-2">Descripción: </label>
                                  <div class="form-group col-md-10 p-0">                                
                                    <input type="text" name="des_cheques"  id="des_cheques"  class="form-control" />
                                  </div> 
                                </div>
                                <div class="form-group col-md-3">
                                  <label class="col-md-3">Valor: </label>
                                  <div class="form-group col-md-6 p-0">                                
                                    <input type="text" name="val_cheques"  id="val_cheques"  class="form-control" value="0.000" />
                                  </div> 
                                </div>
                              </div>
                            </div>
                            <div class="col-mx-12">
                            <hr /><hr /><hr /><hr />
                              <div id="grid_cheques">
                                <table id="list_cheques"></table>
                                    <!--<div id="pager"></div>-->   
                              </div>
                            </div>
                            <div class="col-mx-12">
                              <div class="col-md-12">
                                <h4>OTROS</h4>
                                <div class="form-group col-md-6">
                                  <label class="col-md-3">Descripción: </label>
                                  <div class="form-group col-md-9 p-0">                                
                                    <input type="text" name="des_otros"  id="des_otros"  class="form-control" />
                                  </div> 
                                </div>
                                <div class="form-group col-md-3">
                                  <label class="col-md-3">(+): </label>
                                  <div class="form-group col-md-6 p-0">                                
                                    <input type="text" name="pos_otros"  id="pos_otros"  class="form-control" value="0.000" />
                                  </div> 
                                </div>
                                <div class="form-group col-md-3">
                                  <label class="col-md-3">(-): </label>
                                  <div class="form-group col-md-6 p-0">                                
                                    <input type="text" name="neg_otros"  id="neg_otros"  class="form-control" value="0.000" />
                                  </div> 
                                </div>
                              </div>
                            </div>
                            <div class="col-mx-12">
                            <hr /><hr /><hr /><hr />
                              <div id="grid_otros">
                                <table id="list_otros"></table>
                                    <!--<div id="pager"></div>-->   
                              </div>
                            </div>
                            <div class="col-mx-12">
                              <div class="col-md-12">
                                <h4>VALORES ACRÉDITADOS</h4>
                                <div class="form-group col-md-8">
                                  <label class="col-md-2">Descripción: </label>
                                  <div class="form-group col-md-10 p-0">                                
                                    <input type="text" name="des_acreditado"  id="des_acreditado"  class="form-control" />
                                  </div> 
                                </div>
                                <div class="form-group col-md-3">
                                  <label class="col-md-3">Valor: </label>
                                  <div class="form-group col-md-6 p-0">                                
                                    <input type="text" name="val_acreditado"  id="val_acreditado"  class="form-control" value="0.000" />
                                  </div> 
                                </div>
                              </div>
                            </div>
                            <div class="col-mx-12">
                              <hr /><hr /><hr /><hr />
                              <div id="grid_acreditados">
                                <table id="list_acreditados"></table>
                                    <!--<div id="pager"></div>-->   
                              </div>
                            </div>
                            <div class="col-mx-12">
                              <div class="col-md-12">
                                <h4>VALORES DÉBITADOS</h4>
                                <div class="form-group col-md-8">
                                  <label class="col-md-2">Descripción: </label>
                                  <div class="form-group col-md-10 p-0">                                
                                    <input type="text" name="des_debitados"  id="des_debitados"  class="form-control" />
                                  </div> 
                                </div>
                                <div class="form-group col-md-3">
                                  <label class="col-md-3">Valor: </label>
                                  <div class="form-group col-md-6 p-0">                                
                                    <input type="text" name="val_debitados"  id="val_debitados"  class="form-control" value="0.000" />
                                  </div> 
                                </div>
                              </div>
                            </div>
                            <div class="col-mx-12">
                              <hr /><hr /><hr /><hr />
                              <div id="grid_debitados">
                                <table id="list_debitados"></table>
                                    <!--<div id="pager"></div>-->   
                              </div>
                            </div>
                            <div class="col-mx-12">
                              <div class="col-md-12 ">
                                <div>
                                  <div class="form-group col-md-5 p-0"> 
                                  </div>
                                  <label class="col-md-1 ">SALDOS: </label>
                                  <div class="form-group col-md-3 p-0"> 
                                    <div class="form-group col-md-6 p-0">   
                                      <input type="text" name="estadox"  id="estadox"  class="form-control" style="visibility:hidden"/>
                                    </div>
                                    <div class="form-group col-md-6 p-0">                                
                                      <input type="text" name="saldo_estado_fin"  id="saldo_estado_fin"  class="form-control" value="0.000" />
                                    </div>
                                  </div>
                                  <div class="form-group col-md-3 p-0">  
                                    <div class="form-group col-md-6 p-0">   
                                      <input type="text" name="librox"  id="librox"  class="form-control" style="visibility:hidden"/>
                                    </div>
                                    <div class="form-group col-md-6 p-0">                                
                                      <input type="text" name="saldo_libro_fin"  id="saldo_libro_fin"  class="form-control"  value="0.000" />
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div><!-- /.tab-pane -->
                      </div><!-- /.tab-content -->
                  </div>  
                  <div class="row">
                   <div class="col-mx-12">
                      <button class="btn bg-olive margin" id='btnGuardar'><i class="fa fa-save"></i> Guardar</button>
                      <button class="btn bg-olive margin" id='btnNuevo'><i class="fa fa-pencil"></i> Nuevo</button>
                      <button class="btn bg-olive margin" id='btnBuscar'><i class="fa fa-search"></i> Buscar</button>
                      <button class="btn bg-olive margin" id='btnImprimir'><i class="fa fa-print"></i> Imprimir</button>
                      <button class="btn bg-olive margin" id='btnEliminar'><i class="fa fa-trash"></i> Eliminar</button>
                      <button class="btn bg-olive margin" id='btnAtras'><i class="fa fa-backward"></i> Atrás</button>
                      <button class="btn bg-olive margin" id='btnSiguiente'><i class="fa fa-forward"></i> Siguiente</button>
                   </div>                           
                  </div>
                </div>

                <div id="cuentas" title="Búsqueda Cuentas Bancarias" class="">
                    <table id="list2"><tr><td></td></tr></table>
                    <div id="pager2"></div>
                </div>
                <div id="buscar_conciliacion" title="Búsqueda Conciliación Bancaria" class="">
                    <table id="list3"><tr><td></td></tr></table>
                    <div id="pager3"></div>
                </div>

                <div id="clave_permiso" title="PERMISOS">
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

                <div id="seguro">
                    <label>¿Está seguro de que desea guardar los cambios?</label>  
                    <br />
                    <button class="btn btn-primary" id='btnAceptar'><i class="icon-ok"></i> Aceptar</button>
                    <button class="btn btn-primary" id='btnSalir'><i class="icon-remove-sign"></i> Cancelar</button>
                </div>
              </div><!-- nav-tabs-custom -->
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
    
    <script src='../../plugins/fastclick/fastclick.min.js'></script>
    
    <script src="../../dist/js/app.min.js" type="text/javascript"></script>
    <script src="../../dist/js/validCampoFranz.js" type="text/javascript" ></script>
    <script src="../../dist/js/alertify.min.js" type="text/javascript"></script>
    <script src="../../dist/js/jquery-ui-1.13.3.min.js" type="text/javascript"></script>
    <script src="../../dist/js/jquery.jqGrid.src.js" type="text/javascript"></script>
    <script src="../../dist/js/grid.locale-es.js" type="text/javascript"></script>
    <script src="../../plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script src="conciliacion.js" type="text/javascript"></script>
    <link href="../../dist/css/style.css" rel="stylesheet" type="text/css"/>     
    <script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>
   
  </body>

</html>