<?php
session_start();
include '../../procesos/base.php';
include('../menu/app.php');
conectarse();
error_reporting(0);

$cont1 = 0;
$consulta = pg_query("select max(id_devolucion_venta) from devolucion_venta");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;

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
$consulta = pg_query("select max(num_nota_credito) from devolucion_venta");
while ($row = pg_fetch_row($consulta)) {
    $num_nota_credito = $row[0];
}
$consulta = pg_query("select max(num_nota_credito)  from devolucion_venta,  punto_venta_empresa where    
devolucion_venta.id_empresa=$campo_punto_ventaid  and punto_venta_empresa.id_usuario='$_SESSION[id]'  ");
while ($row = pg_fetch_row($consulta)) {
    $num_nota_credito = $row[0];
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
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab_1" data-toggle="tab">Generales</a></li>
                                <li><a href="#tab_3" data-toggle="tab">Formas de Pago</a></li>
                            </ul>
                            <form id="productos_form" name="productos_form" method="post">
                                <div class="box-body">
                                    <div class="rows">
                                        <div class="tab-content"><!-- tab-content-->
                                            <div class="tab-pane active" id="tab_1"><!-- tab-pane -->

                                                <div class="row">
                                                    <div class="col-mx-12">
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Nro Nota de Crèdito 001-001 </label>
                                                                <input type="text" name="num_nota_credito" id="num_nota_credito" maxlength="9" required class="form-control" />
                                                                <input type="hidden" name="num_oculto" id="num_oculto" required class="form-control" value="<?php echo $num_nota_credito ?>" />
                                                                <input type="hidden" name="id_devolucion_venta" id="id_devolucion_venta" readonly class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Fecha Actual:</label>
                                                                <div class="input-group">
                                                                    <input type="text" name="fecha_actual" id="fecha_actual" readonly class="form-control" />
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
                                                            </div>
                                                        </div>


                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Punto de Venta:</label>
                                                                <input type="text" name="punto_venta" id="punto_venta" required readonly class="form-control" value="<?php echo $campo_punto_venta ?>" />
                                                                <input type="hidden" name="punto_ventaid" id="punto_ventaid" required readonly class="form-control" value="<?php echo $campo_punto_ventaid ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label></label>
                                                                <input type="text" name="comprobante" id="comprobante" readonly class="form-control" value="<?php echo $cont1 ?>" />
                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                                <br />
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-md-5">Cliente: <font color="red">*</font></label>
                                                                <div class="form-group col-md-7 no-padding">
                                                                    <select class="form-control" name="tipo_docu" id="tipo_docu">
                                                                        <option value="">......Seleccione......</option>
                                                                        <option value="Cedula">Cedula</option>
                                                                        <option value="Ruc">Ruc</option>
                                                                        <option value="Pasaporte">Pasaporte</option>
                                                                        <option value="idext">Identificación del Exterior</option>
                                                                    </select>
                                                                    <input type="hidden" name="id_cliente" id="id_cliente" required class="form-control" />
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
                                                                    <input type="text" name="nombre_cli" id="nombre_cli" required readonly class="form-control" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div class="row">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label class="col-md-4">Dirección: </label>
                                                                <div class="form-group col-md-8 no-padding">
                                                                    <input type="text" name="direccion_cli" id="direccion_cli" required readonly class="form-control" />
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="col-md-4">Teléfono: </label>
                                                                <div class="form-group col-md-8 no-padding">
                                                                    <input type="text" name="telefono_cli" id="telefono_cli" required readonly class="form-control" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-md-5">Correo:</label>
                                                                <div class="form-group col-md-7 no-padding">
                                                                    <input type="text" name="correo" id="correo" readonly required class="form-control" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
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
                                                                <label class="col-md-5">Formas de Pago:</label>
                                                                <div class="form-group col-md-4 no-padding">
                                                                    <div>
                                                                        <select class="form-control" name="formaspago" id="formaspago">
                                                                            <option id="contado_form" value="Contado">Contado</option>
                                                                            <option value="otros">Formas de Pago </option>

                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label class="col-md-4">Nro. de serie: <font color="red">*</font></label>
                                                                <div class="form-group col-md-8 no-padding">
                                                                    <input type="text" name="serie" id="serie" required placeholder="Buscar..." class="form-control" data-inputmask='"mask": "999999999"' data-mask />
                                                                    <input type="hidden" name="id_factura_venta" id="id_factura_venta" required class="form-control" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5">

                                                            <label class="col-md-4">Motivo: <font color="red">*</font></label>
                                                            <div class="form-group col-md-8 no-padding">

                                                                <input type="text" name="tipo_motivo" id="tipo_motivo" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="col-md-2">TIPO DE OPERACIÓN: </label>
                                                                <div>
                                                                    <input type="radio" name="descuentof" id="descuentof2" checked value="2"><span> DEVOLUCIÓN DE INVENTARIO</span><br />
                                                                    <input type="radio" name="descuentof" id="descuentof1" value="1"><span></span> DESCUENTO</span><br />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="mensaje_anulado" style="color:red; display:none;">
                                                    <h3>ANULADA</h3>
                                                </div>
                                                <hr />
                                                <h3 class="box-title">Detalle Nota Crédito</h3>

                                                <div class="row">
                                                    <div class="col-mx-12">
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>CÓDIGO BARRAS</label>
                                                                <input type="text" style="text-transform: uppercase" name="codigo_barras" id="codigo_barras" placeholder="Buscar..." class="form-control" />
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>CÓDIGO</label>
                                                                <input type="text" name="codigo" id="codigo" placeholder="Buscar..." class="form-control" />
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>PRODUCTO</label>
                                                                <input type="text" name="producto" id="producto" placeholder="Buscar..." class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>UNIDAD MEDIDA: </label>
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
                                                                <input type="text" name="precio" id="precio" class="form-control" />
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
                                                                <button class="btn btn-primary" id='btnCancelar'><i class="icon-remove-sign"></i> Cancelar</button>
                                                            </div>
                                                        </div>
                                                        <div id="seguro">
                                                            <label>Esta seguro de Anular la factura</label>
                                                            <br />
                                                            <div class="form-actions" align="center">
                                                                <button class="btn btn-primary" id='btnAceptar'><i class="icon-ok"></i> Aceptar</button>
                                                                <button class="btn btn-primary" id='btnSalir'><i class="icon-remove-sign"></i> Cancelar</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>DESC</label>
                                                                <input type="text" name="descuento" id="descuento" min="0" placeholder="%" readonly class="form-control" />
                                                                <input type="hidden" name="canti" id="canti" readonly class="form-control" />
                                                                <input type="hidden" name="iva_producto" id="iva_producto" readonly class="form-control" />
                                                                <input type="hidden" name="carga_series" id="carga_series" readonly class="form-control" />
                                                                <input type="hidden" name="cod_producto" id="cod_producto" readonly class="form-control" />
                                                                <input type="hidden" name="estado" id="estado" readonly class="form-control" />
                                                                <input type="hidden" name="incluye" id="incluye" readonly class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- <div class="row"> -->
                                                <div class="col-mx-12" style="padding-bottom: 16px;">
                                                    <div id="grid_container">
                                                        <table id="list"></table>
                                                        <div id="pager"></div>
                                                    </div>
                                                </div>
                                                <!-- </div> -->

                                                <!-- <div class="row">
                                                    <div class="col-mx-12">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-md-3">Observaciones:</label>
                                                                <div class="form-group col-md-9 no-padding">
                                                                    <textarea class="form-control" name="observaciones" id="observaciones" rows="3"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3"></div>
                                                        <! -- <div class="col-md-2"></div> -- >
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="col-md-5">Tarifa 0:</label>
                                                                <div class="form-group col-md-7 no-padding">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon">
                                                                            <i class="glyphicon glyphicon-usd"></i>
                                                                        </div>
                                                                        <input type="text" name="total_px" id="total_px" value="0.000" readonly class="form-control" />
                                                                        <input type="hidden" name="total_p" id="total_p" value="0.000" readonly class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-5">Tarifa IVA:</label>
                                                                <div class="form-group col-md-7 no-padding">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon">
                                                                            <i class="glyphicon glyphicon-usd"></i>
                                                                        </div>
                                                                        <input type="text" name="total_p2x" id="total_p2x" value="0.000" readonly class="form-control" />
                                                                        <input type="hidden" name="total_p2" id="total_p2" value="0.000" readonly class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-5">... %Iva:</label>
                                                                <div class="form-group col-md-7 no-padding">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon">
                                                                            <i class="glyphicon glyphicon-usd"></i>
                                                                        </div>
                                                                        <input type="text" name="ivax" id="ivax" value="0.000" readonly class="form-control" />
                                                                        <input type="hidden" name="iva" id="iva" value="0.000" readonly class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-5">Descuento:</label>
                                                                <div class="form-group col-md-7 no-padding">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon">
                                                                            <i class="glyphicon glyphicon-usd"></i>
                                                                        </div>
                                                                        <input type="text" name="descx" id="descx" value="0.000" readonly class="form-control" />
                                                                        <input type="hidden" name="desc" id="desc" value="0.000" readonly class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-5">Total:</label>
                                                                <div class="form-group col-md-7 no-padding">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon">
                                                                            <i class="glyphicon glyphicon-usd"></i>
                                                                        </div>
                                                                        <input type="text" name="totx" id="totx" value="0.000" readonly class="form-control" />
                                                                        <input type="hidden" name="tot" id="tot" value="0.000" readonly class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-9">
                                                            <div class="form-group">
                                                                <label>Observaciones:</label>
                                                                <div class="form-group no-padding">
                                                                    <textarea class="form-control" name="observaciones" id="observaciones" rows="3"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div style="display: flex; flex-wrap: wrap;" id="div_totales_tarifas">
                                                                <div class="form-group col-md-6">
                                                                    <label>Descuento:</label>
                                                                    <input type="text" name="descx" id="descx" value="0.000" readonly class="form-control" />
                                                                    <input type="hidden" name="desc" id="desc" value="0.000" readonly class="form-control" />
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label>Subtotal:</label>
                                                                    <input type="text" name="subx" id="subx" value="0.000" readonly class="form-control" />
                                                                    <input type="hidden" name="sub" id="sub" value="0.000" readonly class="form-control" />
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label>Iva....%:</label>
                                                                    <input type="text" name="ivax" id="ivax" value="0.000" readonly class="form-control" />
                                                                    <input type="hidden" name="iva" id="iva" value="0.000" readonly class="form-control" />
                                                                </div>
                                                            </div>
                                                            <div style="display: flex; align-items: center;">
                                                                <label class="col-md-4" style="color:red;font-size:25px">Total:</label>
                                                                <div class="form-group col-md-8 no-padding">
                                                                    <input style="width:150px;height:70px; color:red; font-size:38px" type="text" name="totx" id="totx" value="0.000" readonly class="form-control" />
                                                                    <input type="hidden" name="tot" id="tot" value="0.000" readonly class="form-control" />

                                                                </div>
                                                            </div>
                                                        </div>
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
                                                                    <option value="Contado">Contado</option>
                                                                    <!--option value="Credito">Crédito</option>-->
                                                                    <option value="Cheque">Cheque</option>
                                                                    <!--  <option value="TCredito">Tarjeta de Crédito/Debito</option> -->
                                                                    <option value="Transferencias">Transferencias</option>
                                                                    <!--<option value="CPosfechado">Cheque Posfechado</option>-->
                                                                    <option value="CXC">Cuentas por Cobrar</option>
                                                                    <option value="VALOR_FAVOR_CLIENTE">Valor a Favor del Cliente</option>

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


                                                    </div>
                                                </div>


                                                <div class="col-md-12">
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

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-md-4">Num Documento:</label>
                                                        <div class="form-group col-md-6 no-padding">
                                                            <input type="text" name="num_tarjeta" id="num_tarjeta" required class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="fecha_vencimiento" class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-md-5">Fecha Vencimiento:</label>
                                                        <div class="form-group col-md-7 no-padding">
                                                            <!--<input type="text" name="fecha_dias" id="fecha_dias"  required class="form-control " />-->
                                                            <input type="Date" name="fecha_dias" id="fecha_dias" class="form-control timepicker" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-mx-12">
                                                        <td><button class="btn btn-primary" id='btnAgregar_mixto' style="margin-top: -5px; margin-left: 50px"><i class="icon-list"></i> Agregar</button>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                    </div>
                                                    <div class="col-md-12" id="grid_container_pago_reten_anti" style="display: none;">
                                                        <table id="listPagoreten_mixto_anti" disabled></table>
                                                        <div class="col-md-12" id="pagerP_reten_anti"></div>
                                                    </div>

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



                                        </div>


                                    </div><!-- nav-tabs-custom -->

                                    <div class="row">
                                        <div class="col-md-12">
                                            <p>
                                                <button class="btn bg-olive margin" id='btnGuardar'><i class="fa fa-save"></i> Guardar</button>
                                                <!--<button class="btn bg-olive margin" id='btnModificar'><i class="fa fa-edit"></i> Modificar</button>-->
                                                <button type="button" class="btn bg-olive margin" id='btnBuscar'><i class="fa fa-search"></i> Buscar</button>
                                                <button type="button" class="btn bg-olive margin" id='btnNuevo'><i class="fa fa-pencil"></i> Nuevo</button>
                                                <button type="button" class="btn bg-olive margin" id='btnImprimir'><i class="fa fa-print"></i> Imprimir</button>
                                                <button type="button" class="btn bg-olive margin" id='btnAtras'><i class="fa fa-backward"></i> Atrás</button>
                                                <button type="button" class="btn bg-olive margin" id='btnAdelante'>Adelante <i class="fa fa-forward"></i></button>
                                                <button type="button" class="btn bg-olive margin" id='btnEstados'>Estados Facturaciòn <i class="fa fa-forward"></i></button>
                                                <button type="button" class="btn bg-olive margin" id='btnProductos_factura'><i class="fa fa-search"></i>Productos Factura</button>
                                                <button type="button" class="btn bg-olive margin" id='btnAnular'><i class="fa fa-remove"></i> Anular</button>
                                            </p>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                    <div id="buscar_notas_credito" title="BUSCAR NOTAS DE CRÉDITO">
                        <table id="list2">
                            <tr>
                                <td></td>
                            </tr>
                        </table>
                        <div id="pager2"></div>
                    </div>
                    <div id="buscar_anticipo" title="BUSCAR CUENTAS POR COBRAR CLIENTE">
                        <fieldset>
                            <table id="list22">
                                <tr>
                                    <td></td>
                                </tr>
                            </table>
                            <div id="pager22"></div>
                        </fieldset>
                    </div>
                    <div id="buscar_estados" title="BUSCAR ESTADOS  DE NOTAS DE CRÈDITO">
                        <table id="list7">
                            <tr>
                                <td></td>
                            </tr>
                        </table>
                        <div id="pager7"></div>
                    </div>
                    <div id="cuentas" title="Búsqueda Plan de Cuentas" class="">
                        <table id="list44">
                            <tr>
                                <td></td>
                            </tr>
                        </table>
                        <div id="pager44"></div>
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
    <script src="notas_credito.js?v=1.00" type="text/javascript"></script>
    <link href="../../dist/css/style.css" rel="stylesheet" type="text/css" />
    <script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>
</body>

</html>