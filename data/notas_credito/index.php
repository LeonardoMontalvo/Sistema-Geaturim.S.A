<?php
session_start();
include '../../procesos/base.php';
include('../menu/app.php');
conectarse();
error_reporting(0);

$cont1=0;
$consulta=pg_query("select max(id_devolucion_venta) from devolucion_venta");
while($row=pg_fetch_row($consulta))
 {
  $cont1=$row[0];
 }
$cont1++;

$consulta8=pg_query("select * from punto_venta_empresa  left join punto_venta  on punto_venta_empresa.id_punto_venta=punto_venta.id_punto_venta  where  
punto_venta_empresa.id_usuario='$_SESSION[id]'  ORDER BY id_punto_venta_empresa ASC");
while($row=pg_fetch_row($consulta8))
 {
  $campo_punto_ventaid=$row[5];
 }
$consulta7=pg_query("select * from punto_venta_empresa  left join punto_venta  on punto_venta_empresa.id_punto_venta=punto_venta.id_punto_venta  where  
punto_venta_empresa.id_usuario='$_SESSION[id]'  ORDER BY id_punto_venta_empresa ASC");
while($row=pg_fetch_row($consulta7))
 {
  $campo_punto_venta=$row[6];
 }
$consulta=pg_query("select max(num_nota_credito) from devolucion_venta");
while($row=pg_fetch_row($consulta))
 {
  $num_nota_credito=$row[0];
 }
$consulta=pg_query("select max(num_nota_credito)  from devolucion_venta,  punto_venta_empresa where    
devolucion_venta.id_empresa=$campo_punto_ventaid  and punto_venta_empresa.id_usuario='$_SESSION[id]'  ");
while($row=pg_fetch_row($consulta))
 {
   $num_nota_credito=$row[0];
 }
 
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>NOTA DE CRÉDITO</title>
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
            NOTA DE CRÉDITO
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Procesos</a></li>
            <li class="active">Notas de Crédito</li>
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
                      <form id="notas_form" name="notas_form" method="post">
                        <div class="row">
                          <div class="col-mx-12">
                               <div class="col-md-3">                          
                            <div class="form-group">
                                <label>Nro Nota de Crèdito 001-001 </label>
                                <input type="text" name="num_nota_credito"  id="num_nota_credito" maxlength="9" required class="form-control" />
                                  <input type="hidden" name="num_oculto"  id="num_oculto" required class="form-control" value="<?php echo $num_nota_credito ?>" />                             
                            <input type="hidden" name="id_devolucion_venta"  id="id_devolucion_venta" readonly class="form-control"/>
                            </div>
                            </div>
                            <div class="col-md-2">
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

                            <div class="col-md-2">
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
                                  <input type="text" name="punto_venta"  id="punto_venta" required readonly class="form-control" value="<?php  echo $campo_punto_venta ?>"  /> 
                                  <input type="hidden" name="punto_ventaid"  id="punto_ventaid"   required readonly class="form-control" value="<?php  echo $campo_punto_ventaid ?>"  /> 
                                 
                                  </div> 
                                </div>
                              
                              
                          </div>
                        </div>
                        <br />
                        <div class="row">
                          <div class="col-md-12">
                            <div class="col-md-4">
                              <div class="form-group">
                                <label class="col-md-5" >Cliente: <font color="red">*</font></label>
                                <div class="form-group col-md-7 no-padding">                                
                                  <select class="form-control" name="tipo_docu" id="tipo_docu">
                                    <option value="">......Seleccione......</option>
                                    <option value="Cedula">Cedula</option>
                                    <option value="Ruc">Ruc</option>
                                    <option value="Pasaporte">Pasaporte</option>
                                  </select>
                                  <input type="hidden" name="id_cliente"  id="id_cliente" required class="form-control" />
                                </div> 
                              </div>
                            </div>

                            <div class="col-md-4">
                              <div class="form-group">
                                <label class="col-md-5" >Identificación: <font color="red">*</font></label>
                                <div class="form-group col-md-7 no-padding">                                
                                  <input type="text" name="ruc_ci"  id="ruc_ci" required placeholder="Buscar....." class="form-control" />
                                </div> 
                              </div>  
                            </div>

                            <div class="col-md-4">
                              <div class="form-group">
                                <div class="form-group ">                                
                                  <input type="text" name="nombre_cli"  id="nombre_cli" required readonly class="form-control" />
                                </div>  
                              </div>  
                            </div>

                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="col-md-4">Dirección: </label>
                                <div class="form-group col-md-8 no-padding">                                
                                  <input type="text" name="direccion_cli"  id="direccion_cli" required readonly class="form-control" />
                                </div> 
                              </div>
                            </div>

                            <div class="col-md-3">
                              <div class="form-group">
                                <label class="col-md-4">Teléfono: </label>
                                <div class="form-group col-md-8 no-padding">                                
                                  <input type="text" name="telefono_cli"  id="telefono_cli" required readonly class="form-control" />
                                </div> 
                              </div>
                            </div>

                            <div class="col-md-4">
                              <div class="form-group">
                                <label class="col-md-5">Tipo de comprobante: <font color="red">*</font></label>
                                <div class="form-group col-md-7 no-padding">                                
                                  <select class="form-control" name="tipo_comprobante" id="tipo_comprobante">
                                    <option value="FACTURA" selected>FACTURA</option>
                                    <option value="NOTA">NOTA VENTA</option>
                                  </select>
                                </div> 
                              </div> 
                            </div>
 <div class="col-md-4">
                              <div class="form-group">
                                <label class="col-md-5">Correo:</label>
                                <div class="form-group col-md-7 no-padding">                                
                                  <input type="text" name="correo"  id="correo" readonly required class="form-control" />
                                </div> 
                              </div>
                            </div>
                                </div> 
                             <div class="col-md-12">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="col-md-4" >Nro. de serie: <font color="red">*</font></label>
                                <div class="form-group col-md-8 no-padding">                                
                                  <input type="text" name="serie"  id="serie" required placeholder="Buscar..." class="form-control" data-inputmask='"mask": "999999999"' data-mask />
                                  <input type="hidden" name="id_factura_venta"  id="id_factura_venta" required class="form-control" />
                                </div> 
                              </div>  
                            </div>
                        <div class="col-md-5">
                          
                                <label class="col-md-4" >Motivo: <font color="red">*</font></label>
                                <div class="form-group col-md-8 no-padding">                                
                                
                                   <input type="text" name="tipo_motivo"  id="tipo_motivo"  class="form-control" />
                                </div> 
                        
                            </div>
                                 
                        </div>
                               <button class="btn bg-olive margin" id='btnProductos_factura'><i class="fa fa-search"></i>Productos Factura</button>
                              
                        </div>
                        <hr />
                        <h3 class="box-title">Detalle Nota Crédito</h3>

                        <div class="row">
                         <div class="col-mx-12">
                            <div class="col-md-2">
                              <div class="form-group">
                                <label>CÓDIGO BARRAS</label>
                                <input type="text" style="text-transform: uppercase"  name="codigo_barras"  id="codigo_barras" placeholder="Buscar..." class="form-control" />
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

                            <div class="col-md-1">
                              <div class="form-group">
                                <label>CANTIDAD</label>
                                <input type="text" name="cantidad"  id="cantidad" class="form-control" />
                              </div>
                            </div>

                            <div class="col-md-1">
                              <div class="form-group">
                                <label>PRECIO</label>
                                <input type="text" name="precio"  id="precio" class="form-control" />
                              </div> 
                            </div>

                            <div class="col-md-1">
                              <div class="form-group">
                                <label>DESC</label>
                                <input type="text" name="descuento"  id="descuento"  min="0" placeholder="%" readonly class="form-control" />
                                <input type="hidden" name="canti"  id="canti" readonly class="form-control" />
                                <input type="hidden" name="iva_producto"  id="iva_producto" readonly class="form-control" />
                                <input type="hidden" name="carga_series"  id="carga_series" readonly class="form-control" />
                                <input type="hidden" name="cod_producto"  id="cod_producto" readonly class="form-control" />
                                <input type="hidden" name="estado"  id="estado" readonly class="form-control" />
                                <input type="hidden" name="incluye"  id="incluye" readonly class="form-control" />
                              </div>  
                            </div> 
                         </div>
                        </div>

                        <!-- <div class="row"> -->
                         <div class="col-mx-12">
                            <div id="grid_container">
                                <table id="list"></table>
                                <div id="pager"></div>  
                            </div>
                         </div>   
                        <!-- </div> -->

                        <div class="row">
                         <div class="col-mx-12">
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="col-md-3" >Observaciones:</label>
                                <div class="form-group col-md-9 no-padding">                                
                                  <textarea class="form-control" name="observaciones" id="observaciones" rows="3"></textarea>
                                </div> 
                              </div>
                            </div>

                            <div class="col-md-3"></div>
                            <!-- <div class="col-md-2"></div> -->
                            <div class="col-md-3">
                              <div class="form-group">
                                <label class="col-md-5" >Tarifa 0:</label>
                                <div class="form-group col-md-7 no-padding">
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="glyphicon glyphicon-usd"></i>
                                    </div>
                                    <input type="text" name="total_px" id="total_px" value="0.000" readonly class="form-control"/>
                                    <input type="hidden" name="total_p" id="total_p" value="0.000" readonly class="form-control"/>
                                  </div>                                
                                </div> 
                              </div>

                              <div class="form-group">
                                <label class="col-md-5" >Tarifa IVA:</label>
                                <div class="form-group col-md-7 no-padding">
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="glyphicon glyphicon-usd"></i>
                                    </div>
                                    <input type="text" name="total_p2x" id="total_p2x" value="0.000" readonly class="form-control"/>
                                    <input type="hidden" name="total_p2" id="total_p2" value="0.000" readonly class="form-control"/>
                                  </div>                                
                                </div> 
                              </div>

                              <div class="form-group">
                                <label class="col-md-5" >... %Iva:</label>
                                <div class="form-group col-md-7 no-padding">
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="glyphicon glyphicon-usd"></i>
                                    </div>
                                    <input type="text" name="ivax" id="ivax" value="0.000" readonly class="form-control"/>
                                    <input type="hidden" name="iva" id="iva" value="0.000" readonly class="form-control"/>
                                  </div>                                
                                </div> 
                              </div>

                              <div class="form-group">
                                <label class="col-md-5" >Descuento:</label>
                                <div class="form-group col-md-7 no-padding">
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="glyphicon glyphicon-usd"></i>
                                    </div>
                                    <input type="text" name="descx" id="descx" value="0.000" readonly class="form-control"/>
                                    <input type="hidden" name="desc" id="desc" value="0.000" readonly class="form-control"/>
                                  </div>                                
                                </div> 
                              </div> 

                              <div class="form-group">
                                <label class="col-md-5" >Total:</label>
                                <div class="form-group col-md-7 no-padding">
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="glyphicon glyphicon-usd"></i>
                                    </div>
                                    <input type="text" name="totx" id="totx" value="0.000" readonly class="form-control"/>
                                    <input type="hidden" name="tot" id="tot" value="0.000" readonly class="form-control"/>
                                  </div>                                
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
                        <!--<button class="btn bg-olive margin" id='btnModificar'><i class="fa fa-edit"></i> Modificar</button>-->
                        <button class="btn bg-olive margin" id='btnBuscar'><i class="fa fa-search"></i> Buscar</button>
                        <button class="btn bg-olive margin" id='btnNuevo'><i class="fa fa-pencil"></i> Nuevo</button>
                        <button class="btn bg-olive margin" id='btnImprimir'><i class="fa fa-print"></i> Imprimir</button>
                        <button class="btn bg-olive margin" id='btnAtras'><i class="fa fa-backward"></i> Atrás</button>
                        <button class="btn bg-olive margin" id='btnAdelante'>Adelante <i class="fa fa-forward"></i></button>
                        <button class="btn bg-olive margin" id='btnEstados' >Estados Facturaciòn <i class="fa fa-forward"></i></button>
                      </p> 
                    </div>
                    <div id="buscar_notas_credito" title="BUSCAR NOTAS DE CRÉDITO">
                        <table id="list2"><tr><td></td></tr></table>
                        <div id="pager2"></div>
                    </div> 
                       <div id="buscar_estados" title="BUSCAR ESTADOS  DE NOTAS DE CRÈDITO">
                     <table id="list7"><tr><td></td></tr></table>
                    <div id="pager7"></div>
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
    <script src="notas_credito.js" type="text/javascript"></script>
    <link href="../../dist/css/style.css" rel="stylesheet" type="text/css"/>     
    <script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>
  </body>
</html>