<?php
session_start();

include('../menu/app.php');


include '../../procesos/base.php';

conectarse();
error_reporting(0);


$consulta6 = pg_query("select * from tipo_documento order by id_tdocu asc");
while ($row = pg_fetch_row($consulta6)) {

    $campo_nombre_documento = $row[0];
}
?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>CLIENTES..</title>
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
        <link href="../../dist/css/jquery-ui-1.10.4.custom.css" rel="stylesheet" type="text/css" />
        <link href="../../dist/css/ui.jqgrid.css" rel="stylesheet" type="text/css" />
    </head>

    <body class="skin-blue">
        <div class="wrapper">
            <?php banner_1(); ?>
            <?php menu_lateral_1(); ?>
            <div class="content-wrapper">
                <section class="content-header">
                    <h1>
                        Registro Clientes
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


                                    <li class="active"><a href="#tab_1" data-toggle="tab">Registro Clientes</a></li>
                                    <li><a href="#tab_2" data-toggle="tab">Tipo Documento</a></li>

                                </ul>


                                <div class="box-body">
                                    <div class="row">
                                        <form id="clientes_form" name="clientes_form" method="post">
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab_1">
                                                    <div class="col-mx-12">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Tipo Documento: <font color="red">*</font></label>

                                                                <select class="form-control" name="tipo_docu" id="tipo_docu">
                                                                    <option>Seleccione tipo Documento </option>

                                                                    <?php
                                                                    $consultapro = pg_query("select * from tipo_documento ORDER BY id_tdocu  ASC");
                                                                    while ($row = pg_fetch_row($consultapro)) {
                                                                        echo "<option id=$row[0] value=$row[0]>$row[1]</option>";
                                                                    }
                                                                    ?>
                                                                </select>

                                                                <input type="hidden" name="id_cliente" id="id_cliente" readonly class="form-control">
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Nombres Completos: <font color="red">*</font></label>
                                                                <input type="text" name="nombres_cli" id="nombres_cli" placeholder="Nombres y Apellidos" class="form-control" />
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Teléfono:</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-phone"></i>
                                                                    </div>
                                                                    <input type="text" name="nro_telefono" id="nro_telefono" class="form-control" data-inputmask='"mask": "(999) 999-999"' data-mask />
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>País: <font color="red">*</font></label>
                                                                <input type="text" name="pais_cli" id="pais_cli" value="ECUADOR" placeholder="Ingrese un pais" class="form-control" />
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Dirección: <font color="red">*</font></label>
                                                                <input type="text" name="direccion_cli" id="direccion_cli" value="IBARRA" placeholder="Dirección cliente" class="form-control" />
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Comentarios:</label>
                                                                <textarea class="form-control" name="notas_cli" id="notas_cli" rows="1"></textarea>
                                                            </div>


                                                            <div class="form-group">
                                                            </div>

                                                        </div>
                                                        <div class="form-group">
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>RUC/CI: <font color="red">*</font></label>
                                                                <input type="text" name="ruc_ci" id="ruc_ci" class="form-control" />
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Celular:</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-mobile"></i>
                                                                    </div>
                                                                    <input type="text" name="nro_celular" id="nro_celular" class="form-control" data-inputmask='"mask": "(999) 999-9999"' data-mask />
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Ciudad: <font color="red">*</font></label>
                                                                <input type="text" name="ciudad_cli" id="ciudad_cli" value="IBARRA" class="form-control" />
                                                            </div>

                                                            <div class="form-group">
                                                                <label>E-mail:</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-envelope"></i>
                                                                    </div>
                                                                    <input type="text" name="email" id="email" placeholder="Email" class="form-control" />
                                                                </div>
                                                            </div>

                                                            <!--                            <div class="form-group">
                                                                                          <label>Cupo de Crédito: <font color="red">*</font></label>
                                                                                          <div class="input-group">
                                                                                            <div class="input-group-addon">
                                                                                              <i class="fa fa-money"></i>
                                                                                            </div>-->
<!--                                                            <input type="hidden" name="cupo_credito" id="cupo_credito" value="1000" placeholder="0.00" class="form-control" />-->
                                                            <!--                              </div>
                                                                                        </div>-->

<!--                                                            <div class="form-group">
                                                                <label>cupo_credito:</label>
                                                                <select class="form-control" name="tipo_cli" id="tipo_cli">
                                                                    <option value="Persona Natural" selected>Persona Natural</option>
                                                                    <option value="Persona Jurídica">Persona Jurídica</option>
                                                                </select>
                                                            </div>-->

                                                            <div class="form-group">
                                                                <label>Cupo de Crédito: <font color="red">*</font></label>
                                                                <div class="input-group">
                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-money"></i>
                                                                    </div>
                                                                    <input type="text" name="cupo_credito" id="cupo_credito" placeholder="0.00" class="form-control"/>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Ruta: </label>
                                                                <input type="text" name="nombre_ruta" id="nombre_ruta" placeholder="Buscar...." required class="form-control" disabled/>
                                                                <input type="hidden" name="id_ruta" id="id_ruta"/>
                                                                <button class="btn btn-default" id="btnCuenta1" name="btnCuenta1" style="visibility:hidden"></button>
                                                                <button class="btn btn-default" id="btnCuenta" name="btnCuenta">Seleccionar Ruta</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <br>  <br>
                                                    <div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-mx-12">
                                                            <p>
                                                                <button class="btn bg-olive margin" id='btnGuardar'><i class="fa fa-save"></i> Guardar</button>
                                                                <button class="btn bg-olive margin" id='btnModificar'><i class="fa fa-edit"></i> Modificar</button>
                                                                <button class="btn bg-olive margin" id='btnEliminar'><i class="fa fa-remove"></i> Eliminar</button>
                                                                <button class="btn bg-olive margin" id='btnBuscar'><i class="fa fa-search"></i> Buscar</button>
                                                                <button class="btn bg-olive margin" id='btnNuevo'><i class="fa fa-pencil"></i> Nuevo</button>
                                                            </p>
                                                        </div>

                                                    </div>
                                                </div>


                                                <div class="tab-pane" id="tab_2" style="height: 300px">
                                                    <div class="col-md-12">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Nombre: </label>
                                                                <input type="text" name="nombre_tipo_documento" id="nombre_tipo_documento" class="form-control" />
                                                                <input type="hidden" name="id_tdocu" id="id_tdocu" readonly class="form-control">


                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Código : </label>
                                                                <input type="number" name="codigo_tipo_documento" id="codigo_tipo_documento" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-mx-12">
                                                        <p>
                                                            <button class="btn bg-olive margin" id='btnGuardartipo_documento'><i class="fa fa-save"></i> Guardar</button>
                                                            <button class="btn bg-olive margin" id='btnModificartipo_documento'><i class="fa fa-edit"></i> Modificar</button>
                                                            <button class="btn bg-olive margin" id='btnEliminartipo_documento'><i class="fa fa-remove"></i> Eliminar</button>
                                                            <button class="btn bg-olive margin" id='btnBuscartipo_documento'><i class="fa fa-search"></i> Buscar</button>
                                                            <button class="btn bg-olive margin" id='btnNuevotipo_documento'><i class="fa fa-pencil"></i> Nuevo</button>
                                                        </p>
                                                    </div>
                                                    <div id="tipo_documento" title="Búsqueda de Tipos de Documentos" class="">
                                                        <table id="listtipo_documento">
                                                            <tr>
                                                                <td></td>
                                                            </tr>
                                                        </table>
                                                        <div id="pager"></div>
                                                    </div>
                                                </div><!-- /.tab-pane -->



                                            </div>
                                        </form>
                                    </div>
                                    <div id="cuentas" title="Búsqueda Rutas" class="">
                                        <table id="list2"><tr><td></td></tr></table>
                                        <div id="pager2"></div>
                                    </div>
                                    <div class="row">


                                        <div id="clientes" title="Búsqueda de Clientes" class="">
                                            <table id="list">
                                                <tr>
                                                    <td></td>
                                                </tr>
                                            </table>
                                            <div id="pager12"></div>
                                        </div>
                                        <div name="clave_permiso" id="clave_permiso" title="VERIFICACIÓN">
                                            <label>Ingrese contraseña: </label>
                                            <input type="password" name="clave" id="clave" value="" /><br />
                                            <center><button class="btn bg-olive margin" name="btnAcceder" id="btnAcceder"><i class="fa fa-user"></i> Acceder</button></center>
                                        </div>
                                        <div id="seguro" name="clave_permiso" title="ADVERTENCIA">
                                            <label>¿Está seguro que desea eliminar al cliente?</label>
                                            <center><button class="btn bg-olive margin" type="button" name="btnAceptar" id="btnAceptar"><i class="fa fa-arrow-right"></i> Aceptar</button>
                                                <button class="btn bg-olive margin" type="button" name="btnSalir" id="btnSalir"><i class="fa fa-undo"></i> Salir</button>
                                            </center>
                                        </div>
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
<script src="clientes.js" type="text/javascript"></script>
<script src="../../dist/js/app.min.js" type="text/javascript"></script>
<script src="../../dist/js/validCampoFranz.js" type="text/javascript"></script>
<script src="../../dist/js/alertify.min.js" type="text/javascript"></script>
<script src="../../dist/js/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>
<script src="../../dist/js/jquery.jqGrid.src.js" type="text/javascript"></script>
<script src="../../dist/js/grid.locale-es.js" type="text/javascript"></script>
<link href="../../dist/css/style.css" rel="stylesheet" type="text/css" />
<script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>

</body>

</html>