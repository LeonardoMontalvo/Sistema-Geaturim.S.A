<?php
session_start();
include '../../procesos/base.php';
include('../menu/app.php');
conectarse();
error_reporting(0);
$cont1 = 0;
$consulta = pg_query("select max(id_devolucion_compra) from devolucion_compra");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;

$consulta = pg_query("select max(clave) from devolucion_compra");
while ($row = pg_fetch_row($consulta)) {
    $num_nota_debito = $row[0];
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>NOTA DE CRÉDITO COMPRA</title>
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

</head>

<body class="skin-blue">
    <div class="wrapper">
        <?php banner_1(); ?>
        <?php menu_lateral_1(); ?>
        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                    NOTA DE CRÉDITO COMPRA
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Procesos</a></li>
                    <li class="active">Devolución Compra</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-body">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab_1" data-toggle="tab">Generales</a></li>
                                    <li><a href="#tab_3" data-toggle="tab">Formas de Pago</a></li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1"><!-- tab-pane -->
                                        <div class="rows">
                                            <div class="col-mx-12">
                                                <form id="clientes_form" name="clientes_form" method="post">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Nro Registro </label>
                                                                <input type="text" name="num_nota_debito" id="num_nota_debito" maxlength="9" required class="form-control" />
                                                                <input type="hidden" name="num_oculto" id="num_oculto" required class="form-control" value="<?php echo $num_nota_debito ?>" />
                                                                <input type="hidden" name="id_devolucion_compra" id="id_devolucion_compra" readonly class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Fecha Registro:</label>
                                                                <div class="input-group">
                                                                    <input type="text" name="fecha_actual" id="fecha_actual" class="form-control" />
                                                                    <!--<input type="hidden" name="comprobante"  id="comprobante" readonly class="form-control" value="<?php echo $cont1 ?>"/>-->
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
                                                                <input type="hidden" name="factura_crusada" id="factura_crusada" readonly class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Num Comprobante:</label>
                                                                <input type="text" name="comprobante" id="comprobante" readonly class="form-control" value="<?php echo $cont1 ?>" />

                                                            </div>
                                                        </div>

                                                    </div>
                                                    <BR />
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">Proveedor: <font color="red">*</font></label>
                                                                    <div class="form-group col-md-7 no-padding">
                                                                        <select class="form-control" name="tipo_docu" id="tipo_docu">
                                                                            <option value="">......Seleccione......</option>
                                                                            <option value="Cedula">Cedula</option>
                                                                            <option value="Ruc">Ruc</option>
                                                                            <option value="Pasaporte">Pasaporte</option>
                                                                        </select>
                                                                        <input type="hidden" name="id_proveedor" id="id_proveedor" required class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">Identificación: <font color="red">*</font></label>
                                                                    <div class="form-group col-md-7 no-padding">
                                                                        <input type="text" name="ruc_ci" id="ruc_ci" required placeholder="Buscar....." class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <div class="form-group ">
                                                                        <input type="text" name="empresa" id="empresa" required class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">Tipo Comprobante: <font color="red">*</font></label>
                                                                    <div class="form-group col-md-7 no-padding">
                                                                        <select class="form-control" name="tipo_comprobante" id="tipo_comprobante">
                                                                            <option value="">........Seleccione........</option>
                                                                            <option value="FACTURA" selected>FACTURA</option>
                                                                            <option value="NOTA VENTA">NOTA VENTA</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">Nro. de serie: <font color="red">*</font></label>
                                                                    <div class="form-group col-md-7 no-padding">
                                                                        <input type="text" name="serie" id="serie" placeholder="Buscar..." required class="form-control" data-inputmask='"mask": "999-999-999999999"' data-mask />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">Nro. Autorización: <font color="red">*</font></label>
                                                                    <div class="form-group col-md-7 no-padding">
                                                                        <input type="text" name="autorizacion" id="autorizacion" required class="form-control" />
                                                                        <input type="hidden" name="id_factura_compra" id="id_factura_compra" required readonly class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">TIPO DE OPERACIÓN: </label>
                                                                    <input type="radio" name="descuentof" id="descuentof2" checked value="2"><span> DEVOLUCIÓN DE INVENTARIO</span><br />
                                                                    <input type="radio" name="descuentof" id="descuentof1" value="1"><span></span> DESCUENTO</span><br />
                                                                </div>
                                                            </div>

                                                            <hr width="100%px" />

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">Nro. de serie: <font color="red">*</font></label>
                                                                    <div class="form-group col-md-7 no-padding">
                                                                        <input type="text" name="secuencial" id="secuencial" required class="form-control" data-inputmask='"mask": "999-999-999999999"' data-mask />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">Autorización Documento: <font color="red">*</font></label>
                                                                    <div class="form-group col-md-7 no-padding">
                                                                        <input type="text" name="autorizacion_credito" id="autorizacion_credito" required class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr width="100%px" />
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">Nro. de serie Nota Crédito: <font color="red">*</font></label>
                                                                    <div class="form-group col-md-7 no-padding">
                                                                        <input type="text" name="secuencial_nc" id="secuencial_nc" required class="form-control" data-inputmask='"mask": "999-999-999999999"' data-mask />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">Fecha del Documento:</label>
                                                                    <div class="form-group col-md-7 no-padding">
                                                                        <input type="date" name="fecha_registro_nc" id="fecha_registro_nc" required class="form-control timepicker" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">Autorización Documento: <font color="red">*</font></label>
                                                                    <div class="form-group col-md-7 no-padding">
                                                                        <input type="text" name="autorizacion_nc" id="autorizacion_nc" required class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <hr />
                                                    <div class="col-md-4" style="display: none;">
                                                        <div class="form-group">
                                                            <label class="col-md-5">Formas de Pago:</label>
                                                            <div class="form-group col-md-4 no-padding">
                                                                <div>
                                                                    <select class="form-control" name="formaspago" id="formaspago">
                                                                        <option id="contado_form" value="">...Seleccione...</option>
                                                                        <option value="otros">Formas de Pago </option>

                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--  <div class="row">
                                                        <div class="col-mx-12">
                                                            <div class="col-md-5">
                                                                <label class="col-md-4">Forma pago:<font color="red">*</font></label>
                                                                <div class="form-group col-md-5 no-padding">
                                                                    <select class="form-control" name="forma_pago" id="forma_pago">
                                                                        <option value="0">...SELECCIONE..</option>
                                                                        <option value="CXP">CUENTAR POR PAGAR</option>
                                                                    </select>
                                                                </div>
                                                            </div> -->


                                                    <!--                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-md-4">Seleccione Cta Contable: </label>
                                                                <div class="form-group col-md-4 no-padding">-->
                                                    <!-- <input type="hidden" name="cuenta_contable" id="cuenta_contable" class="form-control" disabled="disabled" />
                                                            <input type="hidden" name="idCuenta" id="idCuenta" /> -->
                                                    <!--                                                                </div>
                                                                <div class="form-group col-md-4 no-padding">
                                                                    <button class="btn btn-default" id="btnCuenta" name="btnCuenta" disabled="disabled">Seleccionar Cuenta</button>
                                                                </div>
                                                            </div>
                                                        </div> -->
                                                    <!--                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-md-4">Nro. Docu:</label>
                                                                <div class="form-group col-md-4 no-padding">-->
                                                    <!-- <input type="hidden" name="cheque_tarjeta" id="cheque_tarjeta" class="form-control" disabled="disabled" /> -->
                                                    <!--                                                                </div>
                                                            </div>
                                                        </div>-->
                                                    <!--       <div class="col-md-12" id="grid_container_pago_reten_anti" disabled>
                                                                <table id="listPagoreten_mixto_anti" disabled></table>
                                                                <div class="col-md-12" id="pagerP_reten_anti"></div>
                                                            </div>

                                                        </div>

                                                    </div> -->
                                                    <hr />
                                                    <div id="estado" style="margin-top: -10px">
                                                        <h3></h3>
                                                    </div>
                                                    <h3 class="box-title">Detalle Devolución</h3>
                                                    <div class="row">
                                                        <div class="col-mx-12">
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label>CÓDIGO BARRAS</label>
                                                                    <input type="text" name="codigo_barras" id="codigo_barras" placeholder="Buscar..." class="form-control" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label>CÓDIGO</label>
                                                                    <input type="text" name="codigo" id="codigo" placeholder="Buscar..." class="form-control" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>PRODUCTO</label>
                                                                    <input type="text" name="producto" id="producto" placeholder="Buscar..." class="form-control" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <label>U. MED: </label>
                                                                <div class="form-group">
                                                                    <select class="form-control" name="unidad_medida" id="unidad_medida">
                                                                    </select>

                                                                    <!--<button class="btn btn-primary" id='btnActualizarum'>↺</button>-->
                                                                    <!--<input type='button' class="btn btn-primary" value='+' onclick="window.open('../medida/index.php', 'width=800,height=600');" />-->
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="form-group">
                                                                    <label>CANTIDAD</label>
                                                                    <input type="text" name="cantidad" id="cantidad" class="form-control" />
                                                                    <input type="hidden" name="cantidad_unidad" id="cantidad_unidad" readonly="" class="form-control" min="1" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="form-group">
                                                                    <label>PRECIO</label>
                                                                    <input readonly type="text" name="precio" id="precio" class="form-control" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="form-group">
                                                                    <label>DESC</label>
                                                                    <input type="text" name="descuento" id="descuento" readonly min="0" placeholder="%" class="form-control" />
                                                                    <input type="hidden" name="canti" id="canti" readonly class="form-control" />
                                                                    <input type="hidden" name="iva_producto" id="iva_producto" readonly class="form-control" />
                                                                    <input type="hidden" name="carga_series" id="carga_series" readonly class="form-control" />
                                                                    <input type="hidden" name="cod_producto" id="cod_producto" readonly class="form-control" />
                                                                    <input type="hidden" name="incluye" id="incluye" readonly class="form-control" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div id="grid_container">
                                                            <table id="list"></table>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="col-md-7">
                                                                <div class="form-group">
                                                                    <label class="col-md-3">Observaciones:</label>
                                                                    <div class="form-group col-md-9 no-padding">
                                                                        <textarea class="form-control" name="observaciones" id="observaciones" rows="3"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <div class="col-md-1">
                                                                <div class="form-group">
                                                                    <label>Tarifa 0:</label>
                                                                    <input type="text" name="total_px" id="total_px" value="0.000" readonly class="form-control" />
                                                                    <input type="hidden" name="total_p" id="total_p" value="0.000" readonly class="form-control" />
                                                                </div>
                                                            </div>

                                                            <div class="col-md-1">
                                                                <label>Tarifa IVA:</label>
                                                                <input type="text" name="total_p2x" id="total_p2x" value="0.000" readonly class="form-control" />
                                                                <input type="hidden" name="total_p2" id="total_p2" value="0.000" readonly class="form-control" />
                                                            </div>

                                                            <div class="col-md-1">
                                                                <div class="form-group ">
                                                                    <label>... %Iva:</label>
                                                                    <input type="text" name="ivax" id="ivax" value="0.000" readonly class="form-control" />
                                                                    <input type="hidden" name="iva" id="iva" value="0.000" readonly class="form-control" />
                                                                </div>
                                                            </div>

                                                            <div class="col-md-1">
                                                                <div class="form-group ">
                                                                    <label>Descuento:</label>

                                                                    <input type="text" name="descx" id="descx" value="0.000" readonly class="form-control" />
                                                                    <input type="hidden" name="desc" id="desc" value="0.000" readonly class="form-control" />
                                                                </div>
                                                            </div>

                                                            <div class="col-md-1">
                                                                <div class="form-group">
                                                                    <label>Total:</label>
                                                                    <input type="text" name="totx" id="totx" value="0.000" readonly class="form-control" />
                                                                    <input type="hidden" name="tot" id="tot" value="0.000" readonly class="form-control" />
                                                                </div>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_3" name="tab_3" style="height: 854px">
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
                                        <div class="row">
                                            <div class="col-mx-12">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>Forma Pago:</label>
                                                        <select class="form-control" name="formaspago_mixto" id="formaspago_mixto">
                                                            <option value="">......Seleccione......</option>
                                                            <option value="CXP">Cuentas por Pagar</option>
                                                            <option value="VALOR_FAVOR_EMPRESA">Valor a Favor de la empresa</option>

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
                                                        <label>Total Nota credito:</label>
                                                        <input type="text" name="valor_factura" id="valor_factura" readonly class="form-control" />
                                                    </div>
                                                </div>



                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>V.Restante :</label>
                                                        <input type="text" name="valor_factura_saldo" id="valor_factura_saldo" readonly="" class="form-control" />
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <button type="button" class="btn btn-primary" id='btnAgregar_mixto' style="margin-top: 25px; margin-left: 50px"><i class="icon-list"></i> Agregar</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>


                                        <div class="col-md-12" style="display:none">
                                            <div class="form-group">
                                                <label class="col-md-2">Seleccione Cta Contable: </label>
                                                <div class="form-group col-md-4 no-padding">
                                                    <input type="text" name="cuenta_contable" id="cuenta_contable" class="form-control" disabled="disabled" />
                                                    <input type="hidden" name="idCuenta" id="idCuenta" />
                                                </div>
                                                <div class="form-group col-md-4 no-padding">
                                                    <button type="button" class="btn btn-default" id="btnCuenta" name="btnCuenta" disabled="disabled">Seleccionar Cuenta</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4" style="display: none;">
                                            <div class="form-group">
                                                <label class="col-md-4">Num Documento:</label>
                                                <div class="form-group col-md-6 no-padding">
                                                    <input type="text" name="num_tarjeta" id="num_tarjeta" required class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                        <div id="fecha_vencimiento" class="col-md-4" style="display: none;">
                                            <div class="form-group">
                                                <label class="col-md-5">Fecha Vencimiento:</label>
                                                <div class="form-group col-md-7 no-padding">
                                                    <!--<input type="text" name="fecha_dias" id="fecha_dias"  required class="form-control " />-->
                                                    <input type="Date" name="fecha_dias" id="fecha_dias" class="form-control timepicker" />
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="row">
                                            <div class="col-mx-12">
                                                <td><button class="btn btn-primary" id='btnAgregar_mixto' style="margin-top: -5px; margin-left: 50px"><i class="icon-list"></i> Agregar</button>
                                            </div>
                                        </div> -->
                                        <div class="row">

                                            <div class="col-md-12" id="grid_container_pago_reten">
                                                <table id="listPagoreten_mixto"></table>
                                                <div class="col-md-12" id="pagerP_reten"></div>
                                            </div>

                                            <div class="col-md-8">
                                                <br />
                                                <!--<center><button class="btn btn-primary" id='btnGuardarRetenciones_mixto'><i class="icon-save"></i> Guardar</button>-->
                                                <button type="button" class="btn btn-primary" id='btnCancelarRetenciones_mixto'><i class="icon-remove-sign"></i> Cancelar</button>
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
                                    </div><!-- /.tab-pane -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p>
                                                <button type="button" class="btn bg-olive margin" id='btnGuardar'><i class="fa fa-save"></i> Guardar</button>
                                                <button type="button" class="btn bg-olive margin" id='btnModificar'><i class="fa fa-edit"></i> Modificar</button>
                                                <button type="button" class="btn bg-olive margin" id='btnBuscar'><i class="fa fa-search"></i> Buscar</button>
                                                <button type="button" class="btn bg-olive margin" id='btnNuevo'><i class="fa fa-pencil"></i> Nuevo</button>
                                                <button type="button" class="btn bg-olive margin" id='btnImprimir'><i class="fa fa-print"></i> Imprimir</button>
                                                <button type="button" class="btn bg-olive margin" id='btnAtras'><i class="fa fa-backward"></i> Atrás</button>
                                                <button type="button" class="btn bg-olive margin" id='btnAdelante'>Adelante <i class="fa fa-forward"></i></button>
                                                <button type="button" class="btn bg-olive margin" id='btnProductos_factura'><i class="fa fa-search"></i>Productos Factura</button>
                                                <button type="button" class="btn bg-olive margin" id='btnEliminar'><i class="fa fa-trash"></i> Eliminar</button>
                                            </p>
                                        </div>

                                        <div id="buscar_devolucion_compras" title="BUSCAR DEVOLUCIONES COMPRAS">
                                            <table id="list3">
                                                <tr>
                                                    <td></td>
                                                </tr>
                                            </table>
                                            <div id="pager3"></div>
                                        </div>

                                        <div id="series" title="AGREGAR SERIES">
                                            <table cellpadding="2" border="0" style="margin-left: 10px">
                                                <tr>
                                                    <td><label>Series: <font color="red">*</font></label></td>
                                                    <td>
                                                        <div class="ui-widget"><select name="combobox" id="combobox" class="campo">
                                                                <option value=""></option>
                                                            </select> </div>
                                                    </td>
                                                    <td><button class="btn btn-primary" id='btnAgregar' style="margin-top: -5px; margin-left: 50px"><i class="icon-list"></i> Agregar</button></td>
                                                </tr>
                                            </table>
                                            <hr style="color: #0056b2;" />
                                            <div align="center">
                                                <table id="list2">
                                                    <tr>
                                                        <td></td>
                                                    </tr>
                                                </table>
                                                <div class="form-actions">
                                                    <button class="btn btn-primary" id='btnGuardarSeries'><i class="icon-save"></i> Guardar</button>
                                                    <button class="btn btn-primary" id='btnCancelarSeries'><i class="icon-remove-sign"></i> Cancelar</button>
                                                </div>
                                            </div>

                                            <div id="buscar_anticipo" title="BUSCAR CUENTAS PAGAR">
                                                <fieldset>
                                                    <table id="list22">
                                                        <tr>
                                                            <td></td>
                                                        </tr>
                                                    </table>
                                                    <div id="pager22"></div>
                                                </fieldset>
                                            </div>
                                            <div id="cuentas" title="Búsqueda Plan de Cuentas" class="">
                                                <table id="list4">
                                                    <tr>
                                                        <td></td>
                                                    </tr>
                                                </table>
                                                <div id="pager4"></div>
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
    <script src="../../dist/js/app.min.js" type="text/javascript"></script>
    <script src="../../dist/js/validCampoFranz.js" type="text/javascript"></script>
    <script src="../../dist/js/alertify.min.js" type="text/javascript"></script>
    <script src="../../dist/js/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>
    <script src="../../dist/js/jquery.jqGrid.src.js" type="text/javascript"></script>
    <script src="../../dist/js/grid.locale-es.js" type="text/javascript"></script>
    <script src="devolucion.js" type="text/javascript"></script>
    <link href="../../dist/css/style.css" rel="stylesheet" type="text/css" />
    <script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>
</body>

</html>