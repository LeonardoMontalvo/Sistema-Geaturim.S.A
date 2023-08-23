<?php
session_start();
include '../../procesos/base.php';
include('../menu/app.php');
conectarse();
error_reporting(0);
$cont1 = 0;
$consulta = pg_query("select max(id_gastos_personales) from gastos_personales");
while ($row = pg_fetch_row($consulta)) {
  $cont1 = $row[0];
}
$cont1++;

$consulta10 = pg_query("select * from parametros");
while ($row = pg_fetch_row($consulta10)) {
  $campo_valor_iva = $row[2];
}
$consulta8 = pg_query("select * from punto_venta_empresa  left join punto_venta  on punto_venta_empresa.id_punto_venta=punto_venta.id_punto_venta  where  
punto_venta_empresa.id_usuario='$_SESSION[id]'  ORDER BY id_punto_venta_empresa ASC");
while ($row = pg_fetch_row($consulta8)) {
  $campo_punto_ventaid = $row[5];
}
$consulta7 = pg_query("select * from punto_venta_empresa  left join punto_venta  on punto_venta_empresa.id_punto_venta=punto_venta.id_punto_venta  where  
punto_venta_empresa.id_usuario='$_SESSION[id]' ORDER BY id_punto_venta_empresa ASC");
while ($row = pg_fetch_row($consulta7)) {
  $campo_punto_venta = $row[6];
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>GASTOS PERSONALES</title>
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
  <link href="../../plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet" />
  <link href="../../plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
  <link href="../../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
  <link href="../../dist/css/alertify.core.css" rel="stylesheet" />
  <link href="../../dist/css/alertify.default.css" id="toggleCSS" rel="stylesheet" />
  <link href="../../dist/css/jquery-ui-1.10.4.custom.css" rel="stylesheet" type="text/css" />
  <link href="../../dist/css/ui.jqgrid.css" rel="stylesheet" type="text/css" />

  <style>
    .ui-jqgrid tr.jqgrow td {
      white-space: normal !important;
    }

    input[type="search"]::-webkit-search-cancel-button {

      /* Remove default */
      -webkit-appearance: none;

      /* Now your own custom styles */
      height: 14px;
      width: 14px;
      display: block;
      background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAn0lEQVR42u3UMQrDMBBEUZ9WfQqDmm22EaTyjRMHAlM5K+Y7lb0wnUZPIKHlnutOa+25Z4D++MRBX98MD1V/trSppLKHqj9TTBWKcoUqffbUcbBBEhTjBOV4ja4l4OIAZThEOV6jHO8ARXD+gPPvKMABinGOrnu6gTNUawrcQKNCAQ7QeTxORzle3+sDfjJpPCqhJh7GixZq4rHcc9l5A9qZ+WeBhgEuAAAAAElFTkSuQmCC);
      /* setup all the background tweaks for our custom icon */
      background-repeat: no-repeat;

      /* icon size */
      background-size: 14px;

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
          Gastos Personales
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Procesos</a></li>
          <li class="active">Gastos Personales</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box box-primary">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">Generales</a></li>
              </ul>
              <div class="box-body">
                <div class="tab-content" id="mitab">
                  <div class="tab-pane active" id="tab_1">
                    <form id="clientes_form" name="clientes_form" method="post" style="padding: 5px;">
                      <div class="row">
                        <div class="col-md-2">
                          <div class="form-group">
                            <label>Fecha Actual:</label>
                            <div class="input-group">
                              <input type="text" name="fecha_actual" id="fecha_actual" readonly class="form-control timepicker" />
                              <input type="hidden" name="valor_reten" id="valor_reten" readonly class="form-control" />
                              <input type="hidden" name="guardado_reten" id="guardado_reten" readonly class="form-control" />
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
                                <input type="text" name="hora_actual" id="hora_actual" readonly class="form-control timepicker" />
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
                            <input type="text" name="digitador" id="digitador" readonly value="<?php echo $_SESSION['nombres'] ?>" class="form-control" />
                            <input type="hidden" name="comprobante2" id="comprobante2" readonly class="form-control">
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                            <label>Punto de Venta:</label>
                            <input type="text" name="punto_venta" id="punto_venta" required readonly class="form-control" value="<?php echo $campo_punto_venta ?>" />
                            <input type="hidden" name="punto_ventaid" id="punto_ventaid" required readonly class="form-control" value="<?php echo $campo_punto_ventaid ?>" />

                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                            <label>Num Comprobante:</label>
                            <input type="text" name="comprobante" id="comprobante" readonly class="form-control" value="<?php echo $cont1 ?>" />

                          </div>
                        </div>
                        <div class="col-md-2">
                          <!-- <div class="form-group"> -->
                          <div id="estado" style="margin-top: -10px">
                            <h3></h3>
                          </div>
                          <!-- </div> -->
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <!--                                                                    <h3 style="margin: 0;">Buscar Factura Electrónica:</h3>-->
                          <div style="margin-bottom: 25px; border: 1px solid black; border-radius:5px; padding:15px; display:flex; flex-direction: column;">
                            <div class="row" style="flex-basis: 100%;">
                              <div class="col-md-12" style="display: flex;">
                                <label style="flex-basis: 12%; align-self: center;" for="">Clave de Acceso:</label>
                                <div class="input-group" style="flex-basis: 90%;">
                                  <input placeholder="INGRESE LA CLAVE DE ACCESO DE LA FACTURA" class="form-control" id="clavefactura" type="search">
                                  <span class="input-group-btn">
                                    <button id="btn_buscar_clave" style="font-size: 14px;" class="btn btn-primary" type="button">
                                      <i class="fa fa-search" aria-hidden="true" id="icono_buscar"></i>
                                      <div id="icono_buscando" style="display: none;"><i class="fa fa-circle-o-notch fa-spin" style="font-size: small;"></i>
                                        <span class="sr-only">Loading...</span>
                                      </div>
                                    </button>
                                  </span>
                                </div>
                              </div>
                            </div>
                            <!-- <div style="flex-basis: 100%; margin-top: 15px;">
                                                                            <button id="btn_cargar_prods" class="btn btn-success" type="button"><i class="fa fa-list-alt" aria-hidden="true"></i> Cargar Productos</button>
                                                                        </div> -->
                          </div>
                        </div>
                      </div>

                      <!-- info factura -->
                      <div class="row">
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Tipo comprobante: <font color="red">*</font></label>
                            <select class="form-control" name="tipo_comprobante" id="tipo_comprobante" required>
                              <option value="">........Seleccione........</option>
                              <option value="FACTURA" selected>FACTURA</option>
                              <option value="NOTA VENTA">NOTA VENTA</option>
                              <option value="LIQUIDACION COMPRA">LIQUIDACION COMPRA/SERVICIOS</option>
                            </select>
                          </div>
                        </div>

                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Num. Factura:<font color="red">*</font></label>
                            <input type="text" name="factura" id="factura" required class="form-control" data-inputmask='"mask": "999-999-999999999"' data-mask />
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Autorización: <font color="red">*</font></label>
                            <input type="text" name="autorizacion" id="autorizacion" required class="form-control" />
                          </div>
                        </div>

                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Fecha Emisión: <font color="red">*</font></label>
                            <input required type="date" name="fecha_emision" id="fecha_emision" class="form-control timepicker" />
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Proveedor: <font color="red">*</font></label>
                            <div class="input-group">
                              <select required class="form-control" name="tipo_docu" id="tipo_docu">
                                <option value="">......Seleccione......</option>
                                <option value="Cedula">Cédula</option>
                                <option value="Ruc">Ruc</option>
                                <option value="Pasaporte">Pasaporte</option>
                              </select>
                              <span class="input-group-btn">
                                <button class="btn btn-primary" type="button" id="btnClientes">Agregar</button>
                              </span>
                              <input type="hidden" name="id_proveedor" id="id_proveedor" required class="form-control" />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Identificación Proveedor: <font color="red">*</font></label>
                            <div class="form-group">
                              <input type="text" name="ruc_ci" id="ruc_ci" required placeholder="Buscar....." class="form-control" />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="">Nombre Proveedor: <font color="red">*</font></label>
                            <input type="text" name="empresa" id="empresa" required class="form-control" />
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Identificacion Comprador: <font color="red">*</font></label>
                            <input required type="text" name="iden_comprador" id="iden_comprador" class="form-control" />
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Razón Social Comprador: <font color="red">*</font></label>
                            <input required type="text" name="rs_comprador" id="rs_comprador" class="form-control" />
                          </div>
                        </div>
                      </div>
                    </form>

                    <div class="row" style="padding: 5px;">
                      <div class="col-md-2">
                        <label for="">Tipo Gasto:</label>
                        <select class="form-control" name="tipo_gasto" id="tipo_gasto"></select>
                      </div>
                      <div class="col-md-2">
                        <label for="">Bien/Servicio:</label>
                        <select name="bien_servicio" id="bien_servicio" class="form-control">
                          <option value="B">Bien</option>
                          <option value="S">Servicio</option>
                        </select>
                      </div>
                      <div class="col-md-1">
                        <label for="">IVA:</label>
                        <select name="iva" id="iva" class="form-control">
                          <option value="0">0%</option>
                          <option value="8">8%</option>
                          <option value="12">12%</option>
                          <option value="14">14%</option>
                        </select>
                      </div>
                      <div class="col-md-2">
                        <label for="">Producto:</label>
                        <input style="text-transform: uppercase;" id="producto" type="text" class="form-control">
                      </div>
                      <div class="col-md-2">
                        <label for="">Valor Descuento:</label>
                        <input id="descuento_producto" type="text" class="form-control">
                      </div>
                      <div class="col-md-2">
                        <label for="">Precio U.:</label>
                        <input id="precio_unitario" type="text" class="form-control">
                      </div>
                      <div class="col-md-1">
                        <label for="">Cantidad:</label>
                        <input id="cantidad" type="text" class="form-control">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div id="grid_container" style="text-align: center;">
                  <table id="list"></table>
                  <!--<div id="pager"></div>-->
                </div>
              </div>
            </div>


            <div class="row">
              <div class="col-md-12">
                <div class="col-md-3">
                  <div class="form-group">
                    <label class="col-md-5">Descripción:</label>
                    <div class="input-group col-md-7 no-padding">
                      <textarea type="text" name="comentario" id="comentario" class="form-control"></textarea>
                    </div>
                  </div>
                </div>

                <div class="col-md-1">
                  <div class="form-group">
                    <label>Descuento:</label>

                    <input type="text" name="descx" id="descx" value="0.000" readonly class="form-control" />
                    <input type="hidden" name="desc" id="desc" value="0.000" readonly class="form-control" />
                  </div>
                </div>

                <div class="col-md-1">
                  <div class="form-group">
                    <label>Tarifa 0:</label>


                    <input style="width:80px;height:30px;" type="text" name="total_px" id="total_px" value="0.000" readonly class="form-control" />
                    <input type="hidden" name="total_p" id="total_p" value="0.000" readonly class="form-control" />

                  </div>
                </div>

                <div class="col-md-1">
                  <div class="form-group">
                    <label>Tarifa12:</label>
                    <input style="width:80px;height:30px;" type="text" name="total_p2x" id="total_p2x" value="0.000" readonly class="form-control" />
                    <input type="hidden" name="total_p2" id="total_p2" value="0.000" readonly class="form-control" />
                  </div>
                </div>

                <div class="col-md-1">
                  <div class="form-group">
                    <label>Subtotal:</label>
                    <input style="width:80px;height:30px;" type="text" name="subx" id="subx" value="0.000" readonly class="form-control" />
                    <input type="hidden" name="sub" id="sub" value="0.000" readonly class="form-control" />
                  </div>
                </div>

                <div class="col-md-1">
                  <div class="form-group">
                    <label>Iva....%:</label>
                    <input style="width:80px;height:30px;" type="text" name="ivax" id="ivax" value="0.000" readonly class="form-control" />
                    <input type="hidden" name="iva" id="iva" value="0.000" readonly class="form-control" />
                  </div>
                </div>

                <div class="col-md-3">

                  <label class="col-md-4" style="color:red;font-size:25px">Total:</label>
                  <div class="form-group col-md-8 no-padding">
                    <input style="width:150px;height:70px; color:red; font-size:38px" type="text" name="totx" id="totx" value="0.000" readonly class="form-control" />
                    <input type="hidden" name="tot" id="tot" value="0.000" readonly class="form-control" />

                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-mx-12">
                <p>
                  <button class="btn bg-olive margin" id='btnGuardar'><i class="fa fa-save"></i> Guardar</button>
                  <button class="btn bg-olive margin" id='btnNuevo'><i class="fa fa-pencil"></i> Nuevo</button>
                  <!--<button class="btn bg-olive margin" id='btnModificar'><i class="fa fa-pencil"></i> Modificar</button>-->
                  <button class="btn bg-olive margin" id='btnBuscar'><i class="fa fa-search"></i> Buscar</button>
                  <button class="btn bg-olive margin" id='btnImprimir'><i class="fa fa-print"></i> Imprimir</button>
                  <button class="btn bg-olive margin" id='btnEliminar'><i class="fa fa-trash"></i> Eliminar</button>
                  <button class="btn bg-olive margin" id='btnAtras'><i class="fa fa-backward"></i> Atrás</button>
                  <button class="btn bg-olive margin" id='btnAdelante'><i class="fa fa-forward"></i> Siguiente</button>
                  <!--<button class="btn bg-olive margin" id='btnEstados'><i class="fa fa-table"> Estados Retenciones</i></button>-->
                </p>
              </div>
            </div>

            <div id="clave_permiso" title="PERMISOS">
              <div class="row">
                <div class="form-group">
                  <label class="col-md-6">Ingrese la clave de seguridad</label>
                  <div class="form-group col-md-6 no-padding">
                    <input type="password" name="clave" id="clave" required class="form-control" />
                  </div>
                </div>
              </div>

              <div class="form-actions" align="center">
                <button class="btn btn-primary" id='btnAcceder'><i class="icon-ok"></i> Acceder</button>
              </div>
            </div>

            <div id="seguro" name="seguro" title="ADVERTENCIA">
              <label>¿Está seguro que desea eliminar la factura?</label>
              <center><button class="btn bg-olive margin" type="button" name="btnAceptar" id="btnAceptar"><i class="fa fa-arrow-right"></i> Aceptar</button>
                <button class="btn bg-olive margin" type="button" name="btnSalir" id="btnSalir"><i class="fa fa-undo"></i> Salir</button>
              </center>
            </div>

            <div id="buscar_gastos" title="BUSCAR GASTOS">
              <table id="list2">
                <tr>
                  <td></td>
                </tr>
              </table>
              <div id="pager2"></div>
            </div>
          </div>
        </div>
    </div>
  </div>
  </div>
  </div>
  <div id="dialog_form_cliente">
    <div id="form_cliente">

    </div>
  </div>
  <div id="dialog_subir_factura">
    <!--  <div class="row">
                                <div class="col-md-12" style="display: flex;">
                                    <label style="flex-basis: 15%; align-self: center;" for="">Clave de Acceso:</label>
                                    <div class="input-group" style="flex-basis: 55%;">
                                        <input placeholder="INGRESE LA CLAVE DE ACCESO DE LA FACTURA" class="form-control" id="clavefactura" type="search">
                                        <span class="input-group-btn">
                                            <button id="btn_buscar_clave" style="font-size: 14px;" class="btn btn-primary" type="button">
                                                <i class="fa fa-search" aria-hidden="true" id="icono_buscar"></i>
                                                <div id="icono_buscando" style="display: none;"><i class="fa fa-circle-o-notch fa-spin" style="font-size: small;"></i>
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div> -->
    <div class="row" style="display: none;">
      <div class="col-xs-12">
        <input id="facutaxml" type="file" class="form-control" accept="text/xml">
      </div>
    </div>
    <div class="row" style="padding-top: 5px;">
      <div class="col-md-12" id="loading_tabla_subir_fac" style="display:none">
        <div style="display:flex; justify-content: center;">
          <i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i>
          <span class="sr-only">Loading...</span>
        </div>
      </div>
      <div class="col-md-12" id="container_tabla_subir_fac">
        <table id="tabla_subir_fac">
          <tr>
            <td></td>
          </tr>
        </table>
        <div id="pager_subir_fac"></div>
      </div>
    </div>
  </div>

  <div id="dialog_form_registro_producto">
    <div id="form_registro_producto">

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
  <script src="../../dist/js/validCampoFranz.js" type="text/javascript"></script>
  <script src="../../dist/js/alertify.min.js" type="text/javascript"></script>
  <script src="../../dist/js/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>
  <script src="../../dist/js/jquery.jqGrid.src.js" type="text/javascript"></script>
  <script src="../../dist/js/grid.locale-es.js" type="text/javascript"></script>
  <script src="../../dist/js/jquery.hotkeys.js" type="text/javascript"></script>
  <script src="gastos_personales.js" type="text/javascript"></script>
  <link href="../../dist/css/style.css" rel="stylesheet" type="text/css" />
  <script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>
  <script src="subirfactura/subirfacutra.js" type="text/javascript"></script>

</body>

</html>