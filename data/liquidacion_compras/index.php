<?php
session_start();
include '../../procesos/base.php';
include('../menu/app.php');
conectarse();
error_reporting(0);

$consulta = pg_query("select max(num_serie) from retencion_fuente_factura_compra");
while ($row = pg_fetch_row($consulta)) {
    $num_factura = $row[0];
}
$consulta = pg_query("select max(num_factura) from liquidacion_compra");
while ($row = pg_fetch_row($consulta)) {
    $num_factura_factura = $row[0];
}
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
$consulta2 = pg_query("select max(id_liquidacion_compra) from liquidacion_compra");
while ($row = pg_fetch_row($consulta2)) {
    $cont1 = $row[0];
}
$cont1++;

$consulta3 = pg_query("select * from proveedores order by id_cliente desc");
while ($row = pg_fetch_row($consulta3)) {
    $campo_id_cliente = $row[0];
    $campo_identificacion_cliente = $row[2];
    $campo_nombre_cliente = $row[3];
    $campo_direccion_cliente = $row[5];
}
$consulta4 = pg_query("select max(id_liquidacion_compra) from liquidacion_compra");
while ($row = pg_fetch_row($consulta2)) {
    $cont1 = $row[0];
}
$consulta5 = pg_query("select num_items from empresa");
while ($row = pg_fetch_row($consulta5)) {
    $campo_num_items = $row[0];
}

$consulta9 = pg_query("select porcentaje_tarjeta from empresa");
while ($row = pg_fetch_row($consulta9)) {
    $campo_porcentaje_tc = $row[0];
}

$consultaforma = pg_query("select id_forma_pago from forma_pagos where id_forma_pago=1");
while ($row = pg_fetch_row($consultaforma)) {

    $campo_nombre_forma = $row[0];
}
$consulta = pg_query("select max(num_serie) from retencion_fuente_factura_compra where clave='c'");
while ($row = pg_fetch_row($consulta)) {
    $num_factura_sinreten = $row[0];
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>LIQUIDACIÓN DE COMPRA DE BIENES Y PRESTACIÓN DE SERVICIOS</title>
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
    <link href="../../dist/css/jquery-ui-1.13.3.min.css" rel="stylesheet" type="text/css" />
    <link href="../../dist/css/ui.jqgrid.css" rel="stylesheet" type="text/css" />
    <link href="../../plugins/iCheck/flat/blue.css" rel="stylesheet" type="text/css" />
</head>

<body class="skin-blue">
    <div class="wrapper">
        <?php banner_1(); ?>
        <?php menu_lateral_1(); ?>
        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                    LIQUIDACIÓN DE COMPRA DE BIENES Y PRESTACIÓN DE SERVICIOS
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Procesos</a></li>
                    <li class="active">Liquidacion Compra</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab_1" data-toggle="tab">Generales</a></li>
                                <li><a href="#tab_2" data-toggle="tab">Adicionales</a></li>
                                <li><a href="#tab_3" data-toggle="tab">Formas de Pago</a></li>

                            </ul>
                            <div class="box-body">
                                <div class="row">
                                    <form id="productos_form" name="productos_form" method="post">
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab_1">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <!--<label>Fecha Actual:</label>-->
                                                                <div class="input-group">

                                                                    <input type="text" name="fecha_actual" id="fecha_actual" readonly class="form-control timepicker" />
                                                                    <input type="hidden" name="comprobante" id="comprobante" readonly class="form-control" value="<?php echo $cont1 ?>" />
                                                                    <input type="hidden" name="proforma" id="proforma" readonly class="form-control" />
                                                                    <input type="hidden" name="id_liquidacion_compra" id="id_liquidacion_compra" readonly class="form-control" />
                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="bootstrap-timepicker">
                                                                <div class="form-group">
                                                                    <!--<label>Hora Actual:</label>-->
                                                                    <div class="input-group">
                                                                        <input type="text" name="hora_actual" id="hora_actual" readonly class="form-control timepicker" />
                                                                        <div class="input-group-addon">
                                                                            <i class="fa fa-clock-o"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <!--<label>Punto de Venta:</label>-->
                                                                <input type="label" name="punto_venta" id="punto_venta" required readonly class="form-control" value="<?php echo $campo_punto_venta ?>" />
                                                                <input type="hidden" name="punto_ventaid" id="punto_ventaid" required readonly class="form-control" value="<?php echo $campo_punto_ventaid ?>" />
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <!--<label>Digitad@r:</label>-->
                                                                <input type="text" name="digitador" id="digitador" readonly value="<?php echo "Digitad@r:" . "  " . $_SESSION['nombres'] ?>" class="form-control" />
                                                                <input type="hidden" name="comprobante2" id="comprobante2" readonly class="form-control">
                                                            </div>
                                                        </div>






                                                    </div>
                                                </div>
                                                <br />
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-md-5">Nro de Factura Preimpresa: 001-001 </label>
                                                                <div class="form-group col-md-5 p-0">
                                                                    <input type="text" name="num_factura" id="num_factura" required class="form-control" />
                                                                    <input type="hidden" name="num_oculto_factura" id="num_oculto_factura" required class="form-control" value="<?php echo $num_factura_factura ?>" />
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="col-md-5">Tipo de Venta:</label>
                                                                <div class="form-group col-md-7 p-0">
                                                                    <select class="form-control" name="tipo_venta" id="tipo_venta">
                                                                        <option value="FACTURA" selected>FACTURA</option>
                                                                        <!--<option value="NOTA">NOTA VENTA</option>-->

                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="col-md-5">Formas de Pago Electrònico:</label>
                                                                <div class="form-group col-md-7 p-0">
                                                                    <select class="form-control" name="formas" id="formas">
                                                                        <option value="<?php echo $campo_nombre_forma ?>">SIN UTILIZACION DEL SISTEMA FINANCIERO </option>

                                                                        <?php
                                                                        $consultapro = pg_query("select * from  forma_pagos  ");
                                                                        while ($row = pg_fetch_row($consultapro)) {
                                                                            echo "<option id=$row[0] value=$row[0]>$row[2]</option>";
                                                                        }
                                                                        ?>
                                                                    </select>


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




                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-md-4">Ced/RUC: <font color="red">*</font></label>
                                                                <div class="form-group col-md-4 p-0">
                                                                    <input type="text" name="ruc_ci" id="ruc_ci" placeholder="Buscar....." required class="form-control" />
                                                                    <input type="hidden" name="id_proveedor" id="id_proveedor" placeholder="Buscar....." required class="form-control" />
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label class="col-md-4">Nombres Completos:</label>
                                                                <div class="form-group col-md-5 p-0">
                                                                    <input type="text" name="nombre_cliente" id="nombre_cliente" required class="form-control" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class=" col-md-3 ">
                                                            <div class="form-group">
                                                                <label class="col-md-4">Tipo Precio:</label>
                                                                <select class="form-group col-md-6 p-0" name="tipo_precio_venta" id="tipo_precio_venta">
                                                                    <option id="mino" value="MINORISTA">MINORISTA</option>
                                                                    <option value="MAYORISTA">MAYORISTA</option>
                                                                    <option value="NEGOCIO">NEGOCIO</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label class="col-md-4">Dirección: <font color="red">*</font></label>
                                                                <div class="form-group col-md-8 p-0">
                                                                    <input type="text" name="direccion_cliente" id="direccion_cliente" required class="form-control" value="<?php echo $campo_direccion_cliente ?>" />
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="col-md-4">Teléfono:</label>
                                                                <div class="form-group col-md-8 p-0">
                                                                    <input type="text" name="telefono_cliente" id="telefono_cliente" readonly required class="form-control" />
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-md-3">Correo:</label>
                                                                <div class="form-group col-md-9 p-0">
                                                                    <input type="text" name="correo" id="correo" readonly required class="form-control" />
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12"></div>

                                                        <div class="col-md-6">
                                                            <input type="hidden" name="retencionFSYY" id="retencionF1SYY" checked value="1"><span></span></span><br />
                                                            <input type="hidden" name="retencionFSYY" id="retencionF2SYY" value="2"><span> </span><br /><br />

                                                        </div>
                                                    </div>
                                                </div>
                                                <!--
                                                    
                                                    
                                                    
                                                                                                        <h3 class="box-title" style="margin-left: 15px">Detalle</h3>-->
                                                <div class="row" style="margin-bottom: 15px; display: none;" >
                                                    <div class="col-md-12">
                                                        <div class="col-md-2">
                                                            <label for="">CENTRO DE COSTOS</label>
                                                            <select class="form-control" name="sel_centro_costo" id="sel_centro_costo"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-12">
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

                                                        <!--                                                            <div class="col-md-2">
                                                                                                                            <div class="form-group">
                                                                                                                                <label>NRO. LIQUIDACIÒN</label>-->
                                                        <input type="hidden" name="num_liquidacion" id="num_liquidacion" placeholder="Ingresar..." class="form-control" />
                                                        <!--                                                                </div>  
                                                                                                                        </div>-->




                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>CANTIDAD</label>

                                                                <input type="text" name="cantidad" id="cantidad" class="form-control" />

                                                            </div>
                                                        </div>


                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>PRECIO</label>
                                                                <input type="text" name="p_venta" id="p_venta" class="form-control" />
                                                            </div>
                                                        </div>

                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>DESC.</label>
                                                                <input type="number" name="descuento" id="descuento" min="0" placeholder="%" class="form-control" />
                                                            </div>
                                                        </div>

                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>STOCK</label>
                                                                <input type="text" name="disponibles" id="disponibles" readonly class="form-control" />
                                                                <input type="hidden" name="iva_producto" id="iva_producto" readonly class="form-control" />
                                                                <input type="hidden" name="carga_series" id="carga_series" readonly class="form-control" />
                                                                <input type="hidden" name="cod_producto" id="cod_producto" readonly class="form-control" />
                                                                <input type="hidden" name="des" id="des" readonly class="form-control" />
                                                                <input type="hidden" name="incluye" id="incluye" readonly class="form-control" />
                                                                <input type="hidden" name="inventar" id="inventar" readonly class="form-control" />
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <!-- <div class="row"> -->
                                                <div class="col-md-12">
                                                    <div id="grid_container">
                                                        <table id="list"></table>
                                                        <div id="pager"></div>
                                                    </div>
                                                </div>
                                                <!-- </div> -->

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-3">

                                                            <div class="col-md-30">

                                                                <div class="col-md-5">
                                                                    <div class="form-group">
                                                                        <label class="col-md-10">Items: </label>

                                                                        <input type="text" name="items" id="items" value="0" readonly class="form-control" />
                                                                    </div>

                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="col-md-11">Iva: </label>

                                                                        <input type="text" name="calculoivaprecio" id="calculoivaprecio" required class="form-control" />
                                                                    </div>

                                                                </div>



                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-5">No Items: max</label>
                                                                <div class="form-group col-md-7 p-0">



                                                                    <input type="text" name="num_items" id="num_items" required readonly class="form-control" value="<?php echo $campo_num_items ?>" />

                                                                </div>
                                                                <input type="hidden" name="porcentaje_tc" id="porcentaje_tc" required readonly class="form-control" value="<?php echo $campo_porcentaje_tc ?>" />

                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-5">No Productos:</label>
                                                                <div class="form-group col-md-7 p-0">
                                                                    <input type="text" name="num" id="num" value="0" readonly class="form-control" />
                                                                    <!--<button class="btn bg-olive margin" id='btnGuardarTemporal'><i class="fa fa-save"></i> Factura Temporal</button>-->
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">

                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label class="col-md-2">Comentario: </label>
                                                                <textarea class="form-control" name="series_area" id="series_area" rows="3" required></textarea>
                                                            </div>
                                                        </div>

                                                        <!-- <div class="col-md-2"></div> -->
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="col-md-5">Tarifa 0:</label>
                                                                <div class="form-group col-md-7 p-0">
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
                                                                <div class="form-group col-md-7 p-0">
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
                                                                <label class="col-md-5">Subtotal:</label>
                                                                <div class="form-group col-md-7 p-0">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon">
                                                                            <i class="glyphicon glyphicon-usd"></i>
                                                                        </div>
                                                                        <input type="text" name="subx" id="subx" value="0.000" readonly class="form-control" />
                                                                        <input type="hidden" name="sub" id="sub" value="0.000" readonly class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-5">Iva....%:</label>
                                                                <div class="form-group col-md-7 p-0">
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
                                                                <div class="form-group col-md-7 p-0">
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
                                                                <div class="form-group col-md-7 p-0">
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
                                                </div>
                                            </div><!-- /.tab-pane -->


                                            <div class="tab-pane" id="tab_2" name="tab_2" style="height: 854px">
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <label class="col-md-4">Nro. Serie Retención: 001-001 <font color="red">*</font></label>
                                                        <div class="form-group col-md-4 p-0">
                                                            <input type="text" name="serie_retencion" id="serie_retencion" required class="form-control" maxlength="9" />
                                                            <input type="hidden" name="num_oculto" id="num_oculto" required class="form-control" value="<?php echo $num_factura ?>" />
                                                            Sin Retenciòn:<input type="text" name="serie_sinretencion" id="serie_sinretencion" maxlength="9" required class="form-control" />
                                                            <input type="hidden" name="num_oculto_sinreten" id="num_oculto_sinreten" required class="form-control" value="<?php echo $num_factura_sinreten ?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">


                                                    <div class="col-md-3">
                                                        <div class="form-group">

                                                            <label>RETENCIÓN EN LA FUENTE BIENES</label><BR />

                                                            <input type="radio" name="retencionF" id="retencionF1" checked value="1"><span></span> NO </span><br />
                                                            <input type="radio" name="retencionF" id="retencionF2" value="2"><span> SI</span><br /><br />
                                                            <input type="checkbox" name="retencionF_prima" id="retencionF_prima"><span> PRIMA 10%</span><br /><br />
                                                            <input type="hidden" name="porcent_reten" id="porcent_reten" class="form-control" />
                                                            <span>Elija Retención:</span>
                                                            <select name="tipoRetencionesF" id="tipoRetencionesF" class="form-control" disabled>
                                                                <option value="0">Seleccione una opción...</option>
                                                                <?php
                                                                $consulta2 = pg_query("select * from retencion_fuentes order by id_retencion_fuentes");
                                                                while ($row = pg_fetch_row($consulta2)) {
                                                                    echo "<option id=$row[0] value=$row[0]>$row[4]" . " -" . "$row[2]" . " % " . "$row[1]</option>";
                                                                }
                                                                ?>
                                                            </select><br />
                                                            <span>Valor de Retención Bienes:</span> <input type="text" name="calculoRetencionF" id="calculoRetencionF" value="0.000" readonly class="form-control" />
                                                            <input type="hidden" name="calculobien" id="calculobien" value="0.000" readonly class="form-control" />
                                                            <input type="hidden" name="calculobienprima1" id="calculobienprima1" value="0.000" readonly class="form-control" />
                                                            <input type="hidden" name="calculobienprima2" id="calculobienprima2" value="0.000" readonly class="form-control" />
                                                            <input type="hidden" name="calculobienprima_sum" id="calculobienprima_sum" value="0.000" readonly class="form-control" />


                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">

                                                            <label>RETENCIÓN EN LA FUENTE SERVICIOS</label><BR />

                                                            <input type="radio" name="retencionFS" id="retencionF1S" checked value="1"><span></span> NO SERVICIO</span><br />
                                                            <input type="radio" name="retencionFS" id="retencionF2S" value="2"><span>SI SERVICIO </span><br /><br />
                                                            <input type="hidden" name="porcent_retens" id="porcent_retens" class="form-control" />
                                                            <span>Elija Retención:</span>
                                                            <select name="tipoRetencionesFS" id="tipoRetencionesFS" class="form-control" disabled>
                                                                <option value="0">Seleccione una opción...</option>
                                                                <?php
                                                                $consulta2 = pg_query("select * from retencion_fuentes order by id_retencion_fuentes");
                                                                while ($row = pg_fetch_row($consulta2)) {
                                                                    echo "<option id=$row[0] value=$row[0]>$row[4]" . "-" . "$row[2]" . " % " . "$row[1]</option>";
                                                                }
                                                                ?>
                                                            </select><br />
                                                            <span>Valor de Retención:</span> <input type="text" name="calculoRetencionFS" id="calculoRetencionFS" value="0.000" readonly class="form-control" />
                                                            <input type="hidden" name="calculoserv" id="calculoserv" value="0.000" readonly class="form-control" />

                                                        </div>


                                                    </div>



                                                    <div class="col-md-3">
                                                        <div class="form-group">

                                                            <label>RETENCIÓN DEL IVA BIENES</label><BR />

                                                            <input type="radio" name="retencionI" id="retencionI1" checked value="1"><span></span> NO</span><br />
                                                            <input type="radio" name="retencionI" id="retencionI2" value="2"><span> SI</span><br /><br />
                                                            <input type="hidden" name="porcent_iva" id="porcent_iva" class="form-control" />
                                                            <span>Elija Retención: </span>
                                                            <select name="tipoRetencionesI" id="tipoRetencionesI" class="form-control" disabled>
                                                                <option value="0">Seleccione una opción...</option>
                                                                <?php
                                                                $consulta2 = pg_query("select * from retencion_iva order by id_retencion_iva");
                                                                while ($row = pg_fetch_row($consulta2)) {
                                                                    echo "<option id=$row[0] value=$row[0]>$row[2]" . " % " . "$row[1]</option>";
                                                                }
                                                                ?>
                                                            </select><br />
                                                            <span>Valor de Retención:</span> <input type="text" name="calculoRetencionI" id="calculoRetencionI" value="0.000" readonly class="form-control" />
                                                            <input type="hidden" name="calculobieniva" id="calculobieniva" value="0.000" readonly class="form-control" />

                                                        </div>
                                                    </div>




                                                    <div class="col-md-3">
                                                        <div class="form-group">

                                                            <label>RETENCIÓN DEL IVA SERVICIOS</label><BR />

                                                            <input type="radio" name="retencionIs" id="retencionI1s" checked value="1"><span></span> NO</span><br />
                                                            <input type="radio" name="retencionIs" id="retencionI2s" value="2"><span> SI</span><br /><br />
                                                            <input type="hidden" name="porcent_ivas" id="porcent_ivas" class="form-control" />
                                                            <span>Elija Retención: </span>
                                                            <select name="tipoRetencionesIs" id="tipoRetencionesIs" class="form-control" disabled>
                                                                <option value="0">Seleccione una opción...</option>
                                                                <?php
                                                                $consulta2 = pg_query("select * from retencion_iva order by id_retencion_iva");
                                                                while ($row = pg_fetch_row($consulta2)) {
                                                                    echo "<option id=$row[0] value=$row[0]>$row[2]" . " % " . "$row[1]</option>";
                                                                }
                                                                ?>
                                                            </select><br />
                                                            <span>Valor de Retención:</span> <input type="text" name="calculoRetencionIs" id="calculoRetencionIs" value="0.000" readonly class="form-control" />

                                                            <input type="hidden" name="calculoservivas" id="calculoservivas" value="0.000" readonly class="form-control" />

                                                        </div>
                                                    </div>



                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">


                                                    </div>





                                                    <div class="col-md-12" id="grid_container_pago_reten">
                                                        <table id="listPagoreten"></table>
                                                        <div class="col-md-12" id="pagerP_reten"></div>
                                                    </div>

                                                    <div class="col-md-9">
                                                        <br />
                                                        <center><button class="btn btn-primary" id='btnGuardarRetenciones'><i class="icon-save"></i> Guardar</button>
                                                            <button class="btn btn-primary" id='btnCancelarRetenciones'><i class="icon-remove-sign"></i> Cancelar</button>
                                                            <!--                              <button class="btn btn-primary" id='btnImprimirRetenciones'><i class="icon-print-sign"></i> Imprimir Retenciones</button>-->
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="tab-pane" id="tab_3" style="height: 854px">




                                                <div class="tab-pane" id="tab_3" style="height: 854px">
                                                    <div class="row">
                                                        <div class="col-md-12">

                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Fecha Actual:</label>
                                                                    <div class="input-group">

                                                                        <input type="text" name="fecha_actualguia" id="fecha_actualguia" readonly class="form-control timepicker" />

                                                                        <input type="hidden" name="proformaguia" id="proformaguia" readonly class="form-control" />

                                                                        <div class="input-group-addon">
                                                                            <i class="fa fa-calendar"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="bootstrap-timepicker">
                                                                    <div class="form-group">
                                                                        <label>Hora Actual:</label>
                                                                        <div class="input-group">
                                                                            <input type="text" name="hora_actualguia" id="hora_actualguia" readonly class="form-control timepicker" />
                                                                            <div class="input-group-addon">
                                                                                <i class="fa fa-clock-o"></i>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <label>Transportista: <font color="red">*</font></label>
                                                            <div class="input-group">

                                                                <select class="form-control" name="transportistaguia" id="transportistaguia">
                                                                    <option value="">Seleccione una opción</option>
                                                                    <?php
                                                                    $consultapro = pg_query("select * from transportista ");
                                                                    while ($row = pg_fetch_row($consultapro)) {
                                                                        echo "<option id=$row[0] value=$row[0]>$row[2]-$row[1]</option>";
                                                                    }
                                                                    ?>
                                                                </select>

                                                                <span class="input-group-btn">

                                                                    <button class="btn btn-primary" id='btnActualizar'>Actualizar</button>

                                                                    <input type='button' class="btn btn-primary" value='Transportista' onclick="window.open('../Transportista/index.php', 'width=800,height=600');" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br />

                                                    <div class="col-md-12">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Nro Serie: 001-001 <font color="red">*</font> </label>

                                                                <input type="text" name="num_serie_guia" id="num_serie_guia" required class="form-control" />
                                                                <input type="hidden" name="num_oculto_guia" id="num_oculto_guia" required class="form-control" value="<?php echo $num_guia_remision ?>" />

                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Fecha Inicio Traslado: <font color="red">*</font></label>
                                                                <div class="input-group">

                                                                    <input type="text" name="fecha_actualguia_tras" id="fecha_actualguia_tras" class="form-control timepicker" />


                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <div class="bootstrap-timepicker">
                                                                <div class="form-group">
                                                                    <label>Fecha Fin Traslado:<font color="red">*</font></label>
                                                                    <div class="input-group">
                                                                        <input type="text" name="fecha_fin_guia" id="fecha_fin_guia" class="form-control timepicker" />
                                                                        <div class="input-group-addon">
                                                                            <i class="fa fa-calendar"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Hora Salida:<font color="red">*</font></label>
                                                                <div class="input-group">

                                                                    <input type="text" name="hora_salida_guia" id="hora_salida_guia" class="form-control timepicker" />


                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-clock-o"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <div class="bootstrap-timepicker">
                                                                <div class="form-group">
                                                                    <label>Hora Llegada:<font color="red">*</font></label>
                                                                    <div class="input-group">
                                                                        <input type="text" name="hora_llegada_guia" id="hora_llegada_guia" class="form-control timepicker" />
                                                                        <div class="input-group-addon">
                                                                            <i class="fa fa-clock-o"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br />
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="col-md-5">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">Punto Partida: <font color="red">*</font></label>
                                                                    <div class="form-group col-md-7 p-0">
                                                                        <input type="text" name="punto_partida_guia" id="punto_partida_guia" placeholder="Buscar....." required class="form-control" />

                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-7">
                                                                <div class="form-group">
                                                                    <label class="col-md-4">Punto Llegada: <font color="red">*</font></label>
                                                                    <div class="form-group col-md-8 p-0">
                                                                        <input type="text" name="punto_llegada_guia" id="punto_llegada_guia" required class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="col-md-3">Ruta: <font color="red">*</font></label>
                                                                    <div class="form-group col-md-8 p-0">
                                                                        <input type="text" name="ruta_guia" id="ruta_guia" required class="form-control" value="<?php echo $campo_direccion_cliente ?>" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="col-md-4">Còdigo Estable.:</label>
                                                                    <div class="form-group col-md-8 p-0">
                                                                        <input type="text" name="codigo_estable_guia" id="codigo_estable_guia" required class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="col-md-3">Numero Aduanero:</label>
                                                                    <div class="form-group col-md-9 p-0">
                                                                        <input type="text" name="numero_aduanero_guia" id="numero_aduanero_guia" required class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="col-md-4">Autorización:</label>
                                                                    <div class="form-group col-md-7 p-0">
                                                                        <input type="text" name="autorizacion" id="autorizacion" class="form-control" />
                                                                        <input type="text" name="autorizacion1" id="autorizacion1" required class="form-control" style="visibility:hidden" />
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="col-md-4">Motivo:<font color="red">*</font></label>
                                                                    <div class="form-group col-md-8 p-0">
                                                                        <select class="form-control" name="motivo" id="motivo">
                                                                            <option value="">Seleccione una opción</option>
                                                                            <option value="venta">Venta</option>
                                                                            <option value="Compra">Compra</option>
                                                                            <option value="transformacion">transformaciòn</option>
                                                                            <option value="consignacion">Consignaciòn</option>
                                                                            <option value="trasladoestablecimiento">Traslado entre establecimientos de una misma empresa</option>
                                                                            <option value="trasladoemisoritinerante">traslado por emisor itinerante de Comprobantes de venta</option>
                                                                            <option value="devolucion">Devoluciòn</option>
                                                                            <option value="importacion">Importaciòn</option>
                                                                            <option value="exportacion">Exportaciòn</option>
                                                                            <option value="otros">Otros</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">Cliente: <font color="red">*</font></label>
                                                                    <div class="form-group col-md-7 p-0">
                                                                        <select class="form-control" name="tipo_docu_guia" id="tipo_docu_guia">
                                                                            <option value="">......Seleccione......</option>
                                                                            <option value="Cedula">Cedula</option>
                                                                            <option value="Ruc">Ruc</option>
                                                                            <option value="Pasaporte">Pasaporte</option>
                                                                        </select>
                                                                        <input type="hidden" name="id_cliente_guia" id="id_cliente_guia" required class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-5">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">Identificación: <font color="red">*</font></label>
                                                                    <div class="form-group col-md-7 p-0">
                                                                        <input type="text" name="ruc_ci_guia" id="ruc_ci_guia" required placeholder="Buscar....." class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <div class="form-group ">
                                                                        <input type="text" name="nombre_cli_guia" id="nombre_cli_guia" required readonly class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-5">
                                                                <div class="form-group">
                                                                    <label class="col-md-4">Dirección: </label>
                                                                    <div class="form-group col-md-8 p-0">
                                                                        <input type="text" name="direccion_cli_guia" id="direccion_cli_guia" required readonly class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="col-md-4">Teléfono: </label>
                                                                    <div class="form-group col-md-8 p-0">
                                                                        <input type="text" name="telefono_cli_guia" id="telefono_cli_guia" required readonly class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">Correo:</label>
                                                                    <div class="form-group col-md-7 p-0">
                                                                        <input type="text" name="correo_guia" id="correo_guia" readonly required class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <div class="col-md-5">

                                                                <label class="col-md-4">Nro. de serie: <font color="red">*</font></label>
                                                                <div class="form-group col-md-8 p-0">

                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>

                                                    <hr />
                                                    <h3 class="box-title" style="margin-left: 15px">Detalle Detalle Guìa Remisiòn</h3>

                                                    <div class="row">
                                                        <div class="col-md-12">


                                                        </div>
                                                    </div>

                                                    <!-- <div class="row"> -->
                                                    <div class="col-md-12">
                                                        <div id="grid_container">
                                                            <table id="list_guia"></table>
                                                            <!--                                <div id="pager_guia"></div>  -->
                                                        </div>
                                                    </div>
                                                    <!-- </div> -->


                                                    <div class="col-mx-12">
                                                        <p>
                                                            <button class="btn bg-olive margin" id='btnGuardar_guia'><i class="fa fa-save"></i> Guardar</button>


                                                            <button class="btn bg-olive margin" id='btnBuscar_guia'><i class="fa fa-search"></i> Buscar</button>
                                                            <button class="btn bg-olive margin" id='btnNuevo_guia'><i class="fa fa-pencil"></i> Nuevo</button>
                                                        </p>
                                                    </div>


                                                </div><!-- /.tab-pane -->
                                            </div><!-- /.tab-content -->
                                    </form>
                                </div>
                                <div class="row">
                                    <div class="col-mx-12">
                                        <p>
                                            <button class="btn bg-olive margin" id='btnGuardar'><i class="fa fa-save"></i> Guardar</button>
                                            <!--<button class="btn bg-olive margin" id='btnModificar'><i class="fa fa-edit"></i> Modificar</button>-->
                                            <button class="btn bg-olive margin" id='btnBuscar'><i class="fa fa-search"></i> Buscar</button>
                                            <button class="btn bg-olive margin" id='btnNuevo'><i class="fa fa-pencil"></i> Nuevo</button>
                                            <button class="btn bg-olive margin" id='btnAnular'><i class="fa fa-remove"></i> Anular</button>
                                            <button class="btn bg-olive margin" id='btnImprimir'><i class="fa fa-print"></i> Imprimir</button>

                                            <button class="btn bg-olive margin" id='btnMantenimiento'>Mantenimiento</button>
                                            <button class="btn bg-olive margin" id='btnAtras'><i class="fa fa-backward"></i> Atras</button>
                                            <button class="btn bg-olive margin" id='btnAdelante'>Adelante <i class="fa fa-forward"></i></button>

                                            <button class="btn bg-olive margin" id='btnEstados'>Estados Liquidaciòn <i class="fa fa-forward"></i></button>


                                            <!--<!                     <button class="btn bg-olive margin" id='btnCorreos' >Envio Correos <i class="fa fa-envelope-o"></i></button>-->
                                        </p>
                                    </div>
                                </div>
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
                                    <table id="list3">
                                        <tr>
                                            <td></td>
                                        </tr>
                                    </table>
                                    <div class="form-actions">
                                        <button class="btn btn-primary" id='btnGuardarSeries'><i class="icon-save"></i> Guardar</button>
                                        <button class="btn btn-primary" id='btnCancelarSeries'><i class="icon-remove-sign"></i> Cancelar</button>
                                    </div>
                                </div>
                            </div>

                            <div id="tipo_busqueda" title="TIPO BUSQUEDA">
                                <table cellpadding="2" border="0" style="margin-left: 10px">
                                    <tr>
                                        <td><label>Buscar por:</label></td>
                                        <td><select id="tipo_venta_busqueda" name="tipo_venta_busqueda" style="width: 180px">
                                                <option value="FACTURA">FACTURA</option>
                                                <option value="NOTA">NOTA VENTA</option>
                                            </select></td>
                                    </tr>
                                </table>
                                <br />
                                <button class="btn btn-primary" id='btnTipoBuscar'><i class="icon-ok"></i> Buscar</button>
                            </div>

                            <div id="buscar_facturas_venta" title="BUSCAR FACTURAS VENTAS">
                                <table id="list2">
                                    <tr>
                                        <td></td>
                                    </tr>
                                </table>
                                <div id="pager2"></div>
                            </div>


                            <div id="buscar_notas_venta" title="BUSCAR NOTAS VENTAS">
                                <table id="list5">
                                    <tr>
                                        <td></td>
                                    </tr>
                                </table>
                                <div id="pager5"></div>
                            </div>

                            <div id="clave_permiso" title="PERMISOS">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-md-6">Ingrese la clave de seguridad</label>
                                        <div class="form-group col-md-6 p-0">
                                            <input type="password" name="clave" id="clave" required class="form-control" />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions" align="center">
                                    <button class="btn btn-primary" id='btnAcceder'><i class="icon-ok"></i> Acceder</button>
                                    <button class="btn btn-primary" id='btnCancelar'><i class="icon-remove-sign"></i> Cancelar</button>
                                </div>

                                <div id="valor_cambioid" title="CAMBIO">
                                    <div class="row">
                                        <div class="form-group">


                                            <div id="valor_reciboid" class="form-group">
                                                <label class="col-md-6">Valor Recibido:</label>
                                                <div class="form-group col-md-6 p-0">
                                                    <input type="text" name="valor_recibo" id="valor_recibo" required class="form-control " />
                                                </div>
                                            </div>

                                            <div id="total_ventaid" class="form-group">
                                                <label class="col-md-6">Total Factura:</label>
                                                <div class="form-group col-md-6 p-0">
                                                    <input type="text" name="total_venta" id="total_venta" readonly class="form-control" />
                                                </div>
                                            </div>


                                            <div id="valor_cambioitemid" class="form-group">
                                                <label class="col-md-6">Cambio:</label>
                                                <div class="form-group col-md-6 p-0">
                                                    <input type="text" name="valor_cambio" id="valor_cambio" readonly class="form-control " />
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-actions" align="center">
                                            <button class="btn btn-primary" id='btnGuardarV'><i class="icon-ok"></i> Guardar</button>
                                            <!--                        <button class="btn btn-primary" id='btnCancelarV'><i class="icon-remove-sign"></i> Cancelar</button>-->



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

                                    <div id="buscar_proformas" title="BUSCAR PROFORMAS">
                                        <table id="list4">
                                            <tr>
                                                <td></td>
                                            </tr>
                                        </table>
                                        <div id="pager4"></div>
                                    </div>
                                    <div id="buscar_estados" title="BUSCAR ESTADOS FACTURACIÒN">
                                        <table id="list7">
                                            <tr>
                                                <td></td>
                                            </tr>
                                        </table>
                                        <div id="pager7"></div>
                                    </div>

                                    <div id="buscar_estadosguia" title="BUSCAR ESTADOS GUÍA REMISIÓN">
                                        <table id="list77">
                                            <tr>
                                                <td></td>
                                            </tr>
                                        </table>
                                        <div id="pager77"></div>
                                    </div>

                                    <div id="buscar_proformas_tecnico" title="BUSCAR PROFORMAS TECNICO">
                                        <table id="list6">
                                            <tr>
                                                <td></td>
                                            </tr>
                                        </table>
                                        <div id="pager6"></div>
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
    <script src="../../dist/js/validCampoFranz.js" type="text/javascript"></script>
    <script src="../../dist/js/alertify.min.js" type="text/javascript"></script>
    <script src="../../dist/js/jquery-ui-1.13.3.min.js" type="text/javascript"></script>
    <script src="../../dist/js/jquery.jqGrid.src.js" type="text/javascript"></script>
    <script src="../../dist/js/grid.locale-es.js" type="text/javascript"></script>
    <script src="../../plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script src="liquidacion_compra.js" type="text/javascript"></script>
    <link href="../../dist/css/style.css" rel="stylesheet" type="text/css" />
    <script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>
</body>

</html>