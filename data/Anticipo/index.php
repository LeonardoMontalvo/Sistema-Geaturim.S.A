<?php
session_start();
include('../menu/app.php');
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$consultaforma = pg_query("SELECT id_cargo, nombre_cargo, sueldo_base, estado FROM cargo; ");
while ($row = pg_fetch_row($consultaforma)) {
    $campo_nombre_forma = $row[0];
}
$cont1 = 0;
$consulta2 = pg_query("select max(id_anticipos) from anticipos");
while ($row = pg_fetch_row($consulta2)) {
    $cont1 = $row[0];
}
$cont1++;
$mes = date("n");
$mesmenos = $mes - 2;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>NOMINA..</title>
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
                        Registro Anticipos
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
                                <ul class="nav nav-tabs">
                                    <!--<li class="active"><a href="#tab_1" data-toggle="tab">Anticipos</a></li>-->                                  

                                </ul>  

                                <div class="box-body">
                                    <div class="row">
                                        <form id="nomina_form" name="nomina_form" method="post">
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab_1">    

                                                    <div class="col-mx-8"> 
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Cedula: <font color="red">*</font></label>
 
                                                                <input name="cedula_empleado"  id="cedula_empleado" placeholder="Buscar...."  class="form-control" />

                                                                <input type="hidden" name="id_empleadoa"  id="id_empleadoa" readonly class="form-control">
                                                                <input type="hidden" name="id_anticipo"  id="id_anticipo" readonly class="form-control" value="<?php echo $cont1 ?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-md-3">
                                                                <label>Nombres Nomina: <font color="red">*</font></label>
                                                                <input name="nombres_empleado"  id="nombres_empleado" readonly=""  placeholder="Buscar...." class="form-control" />
                                                            </div>
                                                        </div> 
                                                        <div class="form-group">
                                                            <div class="col-md-3">
                                                                <label>Direccion Nomina: <font color="red">*</font></label>
                                                                <input name="direccion_empleado"  id="direccion_empleado" readonly=""  class="form-control" />
                                                            </div>
                                                        </div> 
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="col-md-5">Año:</label>
                                                                <div class="form-group col-md-7 no-padding">                                
                                                                    <select name="slct_anio_cf" 
                                                                            id="slct_anio_cf" 
                                                                            class="form-control">
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="col-md-5">Mes:</label>
                                                                <div class="form-group col-md-7 no-padding">                                
                                                                    <select class="form-control" name="select_mes" id="select_mes">
                                                                        <option value="0" >SELECCIONE MES... </option>


                                                                        <?php
                                                                        $consultapro = pg_query("select * from mes_actual ");
                                                                        while ($row = pg_fetch_row($consultapro)) {
                                                                            echo "<option id=$row[1] value=$row[1]>$row[1]</option>";
                                                                        }
                                                                        ?>     
                                                                    </select> 

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> 


                                                    <div class="col-mx-12">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Fecha Registro:</label>
                                                                <div class="input-group">
                                                                    <input type="date" name="fecha_registrobtnBuscarcargo"  id="fecha_registro"  class="form-control"/>

                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </div>
                                                                </div> 
                                                            </div> 
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Descripción</label>
                                                                <input type="text" name="descripcion"  id="descripcion"  style="text-transform: uppercase"  class="form-control" />

                                                            </div>  
                                                        </div>
                                                        <div class="col-md-1 ">
                                                            <div class="form-group">
                                                                <label>Valor</label>

                                                                <input type="text" name="valor"  id="valor" class="form-control" />

                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 ">
                                                            <div class="form-group">
                                                                <label>TOTAL MES</label>

                                                                <input type="text" name="valor_total" readonly="" id="valor_total" class="form-control" />

                                                            </div>
                                                        </div>
                                                          <div class="col-md-2 ">
                                                            <div class="form-group">
                                                                <label>SUELDO EMPLEADO:</label>

                                                                <input type="text" name="salario_empleado" readonly="" id="salario_empleado" class="form-control" />

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-mx-12">
                                                        <div class="col-md-5">

                                                            <label class="col-md-5">CONTADO</label>
                                                            <div class="form-group col-md-2 ">
                                                                <input type="radio" name="mixtoAnticipo" id="mixto1Anticipo" checked value="1">
                                                            </div>

                                                        </div>


                                                        <div class="col-md-5">

                                                            <label class="col-md-5">FORMAS PAGO</label>
                                                            <div class="form-group col-md-2 ">
                                                                <input type="radio" name="mixtoAnticipo" id="mixto2Anticipo" value="2">
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-mx-12">
                                                        <hr>

                                                    </div>    


                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <!--                                                            <div class="col-md-4">
                                                                                                                                    <div class="form-group">
                                                                                                                                        <label class="col-md-4">Adelanto:</label>
                                                                                                                                        <div class="form-group col-md-7 no-padding">
                                                                                                                                            <div class="input-group">
                                                                                                                                                <div class="input-group-addon">
                                                                                                                                                    <i class="glyphicon glyphicon-usd"></i>
                                                                                                                                                </div>-->
                                                            <input type="hidden" name="adelanto" id="adelanto" placeholder="0.00" class="form-control" />
                                                            <!--                                                                        </div>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                </div>-->

                                                            <!--                                                            <div class="col-md-3">
                                                                                                                                    <div class="form-group">
                                                                                                                                        <label class="col-md-4">Meses:</label>
                                                                                                                                        <div class="form-group col-md-8 no-padding">-->
                                                            <input type="hidden" name="meses" id="meses" required min="1" max="31" class="form-control" />
                                                            <!--                                                                    </div>
                                                                                                                                    </div>
                                                                                                                                </div>-->

                                                        </div>
                                                    </div>

                                                    <!--                                                    <div class="row">
                                                                                                                    <div class="col-md-12">
                                                                                                                        <div class="col-md-5">
                                                                                                                            <div style="margin-left: 10px; height: 100px; border: solid 0px">
                                                                                                                                <table id="tablaNuevo" style="width: 400px; margin-left: 20px"  class="table table-striped table-bordered"  >
                                                                                                                                    <thead>
                                                                                                                                        <tr>
                                                                                                                                            <th style="width: 200px; text-align: center">Fecha de Pago</th>
                                                                                                                                            <th style="width: 200px; text-align: center">Monto a Pagar</th>
                                                                                                                                        </tr>
                                                                                                                                    </thead>
                                                                                                                                    <tbody>
                                                                                                                                        <tr></tr>
                                                                                                                                    </tbody>
                                                                                                                                </table>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>-->

                                                    <!--                                                    <div class="row">
                                                                                                                    <div class="col-mx-12">
                                                                                                                        <div class="col-md-3">
                                                                                                                            <div class="form-group">
                                                                                                                                <label class="col-md-5">Forma Pago:</label>
                                                                                                                                <div class="form-group col-md-5 no-padding">
                                                                                                                                    <select class="form-control" name="formaspago_mixto" id="formaspago_mixto" disabled>
                                                                                                                                        <option value="Contado">Contado</option>
                                                                                                                                        <option value="Credito">Crédito</option>
                                                                                                                                        <option value="Cheque">Cheque</option>
                                                                                                                                        <option value="TCredito">Tarjeta de Crédito</option>
                                                                                                                                        <option value="Transferencias">Transferencias</option>
                                                                                                                                    </select>
                                                                                                                                    <br/>                               
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-3">
                                                                                                                            <div class="form-group">
                                                                                                                                <label class="col-md-4">Valor </label>
                                                                                                                                <div class="form-group col-md-4 no-padding">
                                                                                                                                    <input type="text" name="valor_formas" id="valor_formas" required class="form-control"  />
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-4">
                                                                                                                            <div class="form-group">
                                                                                                                                <label class="col-md-4">Total Factura  </label>
                                                                                                                                <div class="form-group col-md-3 no-padding">
                                                                                                                                    <input type="text" name="valor_factura" id="valor_factura" readonly required class="form-control"  />
                                                            
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </div>
                                                            
                                                                                                                        <div class="col-md-3">
                                                                                                                            <div class="form-group">
                                                                                                                                <label class="col-md-4">V.Restante </label>
                                                                                                                                <div class="form-group col-md-3 no-padding">
                                                                                                                                    <input type="text" name="valor_factura_saldo" id="valor_factura_saldo" readonly required class="form-control"  />
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </div>
                                                            
                                                                                                                    </div>
                                                                                                                </div>-->


                                                    <div class="col-md-12">
                                                        <div id="grid_container">
                                                            <table id="list_anticipo"></table>
                                                            <div id="pager_anticipo"></div>  
                                                        </div>
                                                    </div> 

                                                    <div class="row" id="mixto_anti" disable>
                                                        <div class="col-mx-12">
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label>Forma Pago:</label>
                                                                    <select class="form-control" name="formaspago_mixto" id="formaspago_mixto" disabled>
                                                                        <option value="Contado">Contado</option>                                                                      
                                                                        <option value="Cheque">Cheque</option>                                                                     
                                                                        <option value="Transferencias">Transferencias</option>                                                                      
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label>Valor:</label>
                                                                    <input type="text" name="valor_formas" id="valor_formas" class="form-control" />
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label>Valor anticipo:</label>
                                                                    <input type="text" name="valor_factura" id="valor_factura" readonly class="form-control" />
                                                                </div>
                                                            </div>



                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label>V.Restante :</label>
                                                                    <input type="text" name="valor_factura_saldo" id="valor_factura_saldo" readonly="" class="form-control" />
                                                                </div>
                                                            </div>


                                                        </div>
                                                    </div>

                                                    <div class="row" id="cuenta_anti_mixto" disabled>
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label class="col-md-4">Cta Contable: </label>
                                                                <div class="form-group col-md-4 no-padding">
                                                                    <input type="text" name="cuenta_contable" id="cuenta_contable" class="form-control" disabled="disabled" />
                                                                    <input type="hidden" name="idCuenta" id="idCuenta" />
                                                                </div>
                                                                <div class="form-group col-md-4 no-padding">
                                                                    <button class="btn btn-default" id="btnCuenta" name="btnCuenta" disabled="disabled">Seleccionar Cuenta</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-md-6">Num Documento:</label>
                                                                <div class="form-group col-md-6 no-padding">
                                                                    <input type="text" name="num_tarjeta" id="num_tarjeta" required class="form-control" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-mx-3">
                                                            <td><button class="btn btn-primary" id='btnAgregar_mixto' style="margin-top: -5px; margin-left: 50px"><i class="icon-list"></i> Agregar</button>
                                                        </div>
                                                    </div>

                                                    <!--                                                    <div id="fecha_vencimiento" class="col-md-4">
                                                                                                            <div class="form-group">
                                                                                                                <label class="col-md-5">Fecha Vencimiento:</label>
                                                                                                                <div class="form-group col-md-7 no-padding">
                                                                                                                    <input type="text" name="fecha_dias" id="fecha_dias"  required class="form-control " />
                                                                                                                    <input type="Date" name="fecha_dias" id="fecha_dias" class="form-control timepicker" />
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>-->



                                                    <div class="row" id="grid_mixto_agri" disable>
                                                        <div class="col-md-12">
                                                        </div>
                                                        <div class="col-md-12" id="grid_container_pago_reten">
                                                            <table id="listPagoreten_mixto"></table>
                                                            <div class="col-md-12" id="pagerP_reten"></div>
                                                        </div>

                                                        <div class="col-md-8">
                                                            <br />
                                                            <!--<center><button class="btn btn-primary" id='btnGuardarRetenciones_mixto'><i class="icon-save"></i> Guardar</button>-->
                                                            <button class="btn btn-primary" id='btnCancelarRetenciones_mixto'><i class="icon-remove-sign"></i> Cancelar</button>
                                                            <!--                              <button class="btn btn-primary" id='btnImprimirRetenciones'><i class="icon-print-sign"></i> Imprimir Retenciones</button>-->
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="col-md-4">Valor Total </label>
                                                                    <div class="form-group col-md-6 no-padding">
                                                                        <input type="text" name="cantidad_mixto" id="cantidad_mixto" readonly required class="form-control" />
                                                                        <input type="hidden" name="validar_guardar" id="validar_guardar" required class="form-control" />
                                                                        <input type="hidden" name="validar_guardar_grid" id="validar_guardar_grid" required class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>




                                                    <div class="row">
                                                        <div class="col-mx-12">
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

                                                    <div id="clave_permisoaa" title="PERMISOS">
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label class="col-md-6" >Ingrese la clave de seguridad</label>
                                                                <div class="form-group col-md-6 no-padding">                                
                                                                    <input type="password" name="claveaa"  id="claveaa" required class="form-control" />
                                                                </div> 
                                                            </div> 

                                                        </div>

                                                        <div class="form-actions" align="center">
                                                            <button class="btn btn-primary" id='btnAccederaa'><i class="icon-ok"></i> Acceder</button>
                                                            <button class="btn btn-primary" id='btnCancelaraa'><i class="icon-remove-sign"></i> Cancelar</button>
                                                        </div>
                                                    </div>
                                                    <div id="seguroaa">
                                                        <label>Esta seguro de Anular</label>
                                                        <br />
                                                        <div class="form-actions" align="center">
                                                            <button class="btn btn-primary" id='btnAceptaraa'><i class="icon-ok"></i> Aceptar</button>
                                                            <button class="btn btn-primary" id='btnSaliraa'><i class="icon-remove-sign"></i> Cancelar</button>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-mx-12">
                                                            <p>
                                                                <button class="btn bg-olive margin" id='btnGuardarant'><i class="fa fa-save"></i> Guardar</button>
                                                                <button class="btn bg-olive margin" id='btnModificarant'><i class="fa fa-save"></i> Modificar</button>
                                                                <button class="btn bg-olive margin" id='btnNuevoant'><i class="fa fa-pencil"></i> Nuevo</button>                                       
                                                                <button class="btn bg-olive margin" id='btnAnularant'><i class="fa fa-remove"></i> Eliminar</button>
                                                            </p> 
                                                        </div> 
                                                    </div>  
                                                </div>






                                            </div><!-- /.tab-pane -->
                                    </div>
                                    </form>
                                </div>

                                <div class="row">
                                    <div id="nominas" title="Búsqueda de Nomina" class="">
                                        <table id="list"><tr><td></td></tr></table>
                                        <div id="pager12"></div>
                                    </div>

                                </div>
                                <div id="cuentas" title="Búsqueda Plan de Cuentas" class="">
                                    <table id="list44">
                                        <tr>
                                            <td></td>
                                        </tr>
                                    </table>
                                    <div id="pager44"></div>
                                </div>
                                <div id="cuentas_reten" title="Búsqueda Plan de Cuentas" class="">
                                    <table id="list44_reten">
                                        <tr>
                                            <td></td>
                                        </tr>
                                    </table>
                                    <div id="pager44_reten"></div>
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
<script src="nomina.js" type="text/javascript"></script>
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