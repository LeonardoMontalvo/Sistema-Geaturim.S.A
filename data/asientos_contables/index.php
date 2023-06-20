<?php
session_start();
include '../../procesos/base.php';
include('../menu/app.php');
conectarse();
error_reporting(0);
$consulta7 = pg_query("select * from punto_venta_empresa  left join punto_venta  on punto_venta_empresa.id_punto_venta=punto_venta.id_punto_venta  where  
punto_venta_empresa.id_usuario='$_SESSION[id]' ORDER BY id_punto_venta_empresa DESC LIMIT 1");
while ($row = pg_fetch_row($consulta7)) {
    $campo_punto_venta = $row[6];
}
$consulta8 = pg_query("select * from punto_venta_empresa  left join punto_venta  on punto_venta_empresa.id_punto_venta=punto_venta.id_punto_venta  where  
punto_venta_empresa.id_usuario='$_SESSION[id]' ORDER BY id_punto_venta_empresa DESC limit 1");
while ($row = pg_fetch_row($consulta8)) {
    $campo_punto_ventaid = $row[5];
}
$cont1 = 0;
$consulta = pg_query("select max(id_transacciones) from transacciones");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>ASIENTOS CONTABLES</title>
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
                    CREACIÓN DE ASIENTOS CONTABLES
                </h1>
                <ol class="breadcrumb">
                    <li><a href=""><i class="fa fa-dashboard"></i> Procesos</a></li>
                    <li class="active">Asientos Contables</li>

                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <ul class="nav nav-tabs">
                            </ul>
                            <div class="box-body">
                                <div class="rows">
                                    <div class="col-mx-12">
                                        <div class="tab-content" id="mitab">
                                            <form id="clientes_form" name="clientes_form" method="post">
                                                <div class="row">
                                                    <div class="col-mx-12">
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Fecha Actual:</label>
                                                                <div class="input-group">
                                                                    <input type="text" name="fecha_actual" id="fecha_actual" readonly class="form-control" />

                                                                    <input type="hidden" name="id_asiento_contable" id="id_asiento_contable" readonly class="form-control" />
                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
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
                                                                <label>P.Emisión:</label>
                                                                <input type="text" name="digitador" id="digitador" readonly value="<?php echo $_SESSION['nombres'] ?>" class="form-control" />
                                                                <input type="hidden" name="comprobante2" id="comprobante2" readonly class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Punto de Venta:</label>
                                                                <input type="label" name="punto_venta" id="punto_venta" required readonly class="form-control" value="<?php echo $campo_punto_venta ?>" />
                                                                <input type="hidden" name="punto_ventaid" id="punto_ventaid" required readonly class="form-control" value="<?php echo $campo_punto_ventaid ?>" />
                                                                <!--<input type="hidden" name="buscar_pv"  id="buscar_pv"   required  class="form-control"  />-->
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Comprobante:</label>
                                                                <input type="text" name="comprobante" id="comprobante" readonly class="form-control" value="<?php echo $cont1 ?>" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br />

                                                <div class="row">

                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label class="col-md-5">Tipo de Transacción: <font color="red">*</font></label>
                                                            <div class="form-group col-md-7 no-padding">
                                                                <select class="form-control" name="tipo_transaccion" id="tipo_transaccion">
                                                                    <option value="0">........Seleccione........</option>
                                                                    <?php
                                                                    $consulta = pg_query("select * from tipo_transaccion where estado='Activo' ");
                                                                    while ($row = pg_fetch_row($consulta)) {
                                                                        echo "<option id=$row[0] value=$row[0]>$row[1]</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                                <label class="col-md-1">Asiento Nro: </label>
                                                            <div class="col-md-5">
                                                                <div class="form-group">
                                                                    <input type="text" name="nro_transaccion" id="nro_transaccion" disabled class="form-control" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-2">
                                                        <!-- <div class="form-group"> -->
                                                        <div id="estado" style="margin-top: -10px">
                                                            <h3></h3>
                                                        </div>
                                                        <!-- </div> -->
                                                    </div>





                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-md-5">Tipo: <font color="red"></font></label>
                                                            <div class="form-group col-md-7 no-padding">
                                                                <select class="form-control" name="tipo_persona" id="tipo_persona">
                                                                    <option value="0">..Seleccione..</option>
                                                                    <option value="1">Cliente</option>
                                                                    <option value="2">Proveedor</option>
                                                                    <option value="3">Otros</option>
                                                                </select>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-md-4 ">CI./RUC: <font color="red"></font></label>
                                                            <div class="form-group col-md-8 no-padding">
                                                                <input type="text" name="ruc_ci" id="ruc_ci" required placeholder="Buscar....." class="form-control" />
                                                                <input type="hidden" name="id_cliente" id="id_cliente" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">

                                                            <div class="form-group">
                                                                <input type="text" name="nombres_completos" id="nombres_completos" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="col-md-5">Fecha de Movimiento:</label>
                                                    <div class="form-group col-md-7 no-padding">
                                                        <input type="date" name="fecha_registro" id="fecha_registro" class="form-control timepicker" />
                                                    </div><!-- /.input group -->
                                                </div><!-- /.form group -->
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label class="col-md-3">Concepto: <font color="red">*</font></label>
                                                    <div class="form-group col-md-9 no-padding">
                                                        <div class="form-group">
                                                            <input type="text" name="concepto" id="concepto" class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <!--<label class="col-md-3">Valor: <font color="red">*</font></label>-->
                                                    <div class="form-group col-md-9 no-padding">
                                                        <div class="form-group">
                                                            <input type="hidden" name="valorconcepto" id="valorconcepto" class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <button class="btn bg-olive margin" id='btnfacturascxp'><i class="fa fa-new"></i> Buscar Facturas CXP</button>
                                        <button class="btn bg-olive margin" id='btnfacturascxc'><i class="fa fa-new"></i> Buscar Facturas CXC</button>


                                        <div class="row">
                                            <div class="col-mx-12">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label id="factura_pagar">factura Pagar:</label>
                                                        <input type="text" name="num_factura" id="num_factura" readonly class="form-control" />
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label id="tipo_factura_label">Tipo Factura:</label>
                                                        <input type="text" name="tipo_factura" id="tipo_factura" readonly class="form-control" />
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label id="fechadefactura">Fecha de Factura:</label>
                                                        <input type="text" name="fecha_factura" id="fecha_factura" readonly class="form-control" />
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label id="totalcxp">Total CxP:</label>
                                                        <input type="text" name="totalcxc" id="totalcxc" readonly class="form-control" />
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label id="valor_pagadola">Valor Pagado:</label>
                                                        <input type="text" name="valor_pagado" id="valor_pagado" class="form-control" />
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label id="Saldo">Saldo:</label>
                                                        <input type="text" name="saldo2" id="saldo2" readonly class="form-control" />
                                                        <input type="hidden" name="ids" id="ids" readonly class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-md-5" id="formadepago_label">Forma de pago:<font color="red">*</font></label>
                                                        <div class="form-group col-md-7 no-padding">
                                                            <select class="form-control" name="forma_pago" id="forma_pago">
                                                                <option value="0">........SELECCIONE........</option>
                                                                <option value="EFECTIVO">EFECTIVO</option>
                                                                <option value="CHEQUE">CHEQUE</option>
                                                                <option value="TARJETA">TARJETA</option>
                                                                <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label class="col-md-2" id="numero_cheque">Nro. Cheque/Tarjeta:</label>
                                                            <div class="form-group col-md-4 no-padding">
                                                                <input type="text" name="cheque_tarjeta" id="cheque_tarjeta" class="form-control" disabled="disabled" />
                                                            </div>
                                                        </div>
                                                        <!--                            <div class="form-group">
                                                                                          <label class="col-md-2" >Nombre Banco:</label>
                                                                                          <div class="form-group col-md-4 no-padding">                                
                                                                                            <input type="text" class="form-control" name="banco" id="banco" disabled="disabled">
                                                                                          </div> 
                                                                                        </div>-->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-md-3" id="pago_label">Pago:<font color="red">*</font></label>
                                                                <div class="form-group col-md-9 no-padding">
                                                                    <select class="form-control" name="tipo_pago" id="tipo_pago">
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row" style="margin-bottom: 15px;">
                                            <div class="col-md-12">
                                                <button type="button" class="btn bg-olive" id="btn_ventana_cuentas"><i class="fa fa-list"></i> Ver Plan de Cuentas</button>
                                            </div>
                                        </div>
                                        <!--                                            <div class="col-md-12" id="detalleAsiento">

                                                <h3 class="box-title">Detalle Asiento Contable</h3>
                                            </div>-->
                                        <div class="row" style="margin-bottom: 15px;">
                                            <div class="col-md-2">
                                                <label for="">CENTRO DE COSTOS</label>
                                                <select class="form-control" name="sel_centro_costo" id="sel_centro_costo"></select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-mx-12">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>CÓDIGO CUENTA CONTABLE</label>
                                                        <input type="text" name="codigo_plan" id="codigo_plan" placeholder="Buscar..." class="form-control" />
                                                    </div>
                                                </div>

                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label>DESCRIPCIÓN</label>
                                                        <input type="text" name="descripcion" id="descripcion" placeholder="Buscar..." class="form-control" />
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>DEBITO</label>
                                                        <input type="text" name="debito" id="debito" value="0.000" class="form-control" />
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>CREDITO</label>
                                                        <input type="text" name="credito" id="credito" value="0.000" class="form-control" />
                                                    </div>
                                                </div>

                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <input type="hidden" name="id_plan" id="id_plan" readonly class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="row"> -->
                                        <div class="col-mx-12">
                                            <div id="grid_container">
                                                <table id="list"></table>
                                                <!--<div id="pager"></div>-->
                                            </div>
                                        </div>
                                        <!-- </div> -->

                                        <div class="row">
                                            <div class="col-mx-12">
                                                <div class="col-md-7">

                                                </div>
                                                <!-- <div class="col-md-2"></div> -->
                                                <!--<div class="col-md-3">-->
                                                <div class="form-group">
                                                    <label class="col-md-1" align="right">TOTALES:</label>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <div class="form-group col-md-10 no-padding">
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="glyphicon glyphicon-usd"></i>
                                                            </div>
                                                            <input type="text" name="total_debitox" id="total_debitox" value="0.000" readonly class="form-control" />
                                                            <input type="hidden" name="total_debito" id="total_debito" value="0.000" readonly class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <div class="form-group col-md-10 no-padding">
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="glyphicon glyphicon-usd"></i>
                                                            </div>
                                                            <input type="text" name="total_creditox" id="total_creditox" value="0.000" readonly class="form-control" />
                                                            <input type="hidden" name="total_credito" id="total_credito" value="0.000" readonly class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--</div>-->
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-mx-12">
                                                <div class="col-md-8">

                                                </div>
                                                <!-- <div class="col-md-2"></div> -->
                                                <!--<div class="col-md-3">-->
                                                <div class="form-group">
                                                    <label class="col-md-2" align="right">DIFERENCIA:</label>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <div class="form-group col-md-10 no-padding">
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="glyphicon glyphicon-usd"></i>
                                                            </div>
                                                            <input type="text" name="diferenciax" id="diferenciax" value="0.000" readonly class="form-control" />
                                                            <input type="hidden" name="diferencia" id="diferencia" value="0.000" readonly class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--</div>-->
                                            </div>
                                        </div>
                                        </form>


                                        <div class="col-mx-12">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Depósito:</label>
                                                    <div class="form-group">
                                                        <input type="text" name="deposito" id="deposito" class="form-control timepicker" />

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Banco:</label>
                                                    <div class="form-group ">
                                                        <input type="text" name="banco" id="banco" required class="form-control" />
                                                        <input type="hidden" name="id_bancos" id="id_bancos" class="form-control" />
                                                    </div><!-- /.input group -->
                                                </div><!-- /.form group -->
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Cuenta N:</label>
                                                    <input type="text" name="cuentanum" id="cuentanum" class="form-control" />

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4">Observaciones:</label>

                                                    <textarea class="form-control" name="observaciones" id="observaciones" rows="3"></textarea>

                                                </div>
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
                                                    <button class="btn bg-olive margin" id='btnEliminar'><i class="fa fa-trash"></i> Eliminar</button>
                                                    <button class="btn bg-olive margin" id='btnAtras'><i class="fa fa-backward"></i> Atras</button>
                                                    <button class="btn bg-olive margin" id='btnAdelante'>Adelante <i class="fa fa-forward"></i></button>
                                                </p>
                                            </div>
                                        </div>
                                        <div id="buscar_facturas" title="BUSCAR FACTURAS">
                                            <fieldset>
                                                <table id="list22">
                                                    <tr>
                                                        <td></td>
                                                    </tr>
                                                </table>
                                                <div id="pager22"></div>
                                            </fieldset>
                                        </div>
                                        <div id="buscar_facturas222" title="BUSCAR FACTURAS">
                                            <fieldset>
                                                <table id="list222">
                                                    <tr>
                                                        <td></td>
                                                    </tr>
                                                </table>
                                                <div id="pager222"></div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div id="buscar_asiento" title="BUSCAR ASIENTO CONTABLE">
                                        <table id="list3">
                                            <tr>
                                                <td></td>
                                            </tr>
                                        </table>
                                        <div id="pager3"></div>
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
                                        <label>¿Está seguro que desea eliminar el asiento contable?</label>
                                        <center><button class="btn bg-olive margin" type="button" name="btnAceptar" id="btnAceptar"><i class="fa fa-arrow-right"></i> Aceptar</button>
                                            <button class="btn bg-olive margin" type="button" name="btnSalir" id="btnSalir"><i class="fa fa-undo"></i> Salir</button>
                                        </center>
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
    <script src="asiento_contable.js" type="text/javascript"></script>
    <link href="../../dist/css/style.css" rel="stylesheet" type="text/css" />
    <script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>
</body>

</html>