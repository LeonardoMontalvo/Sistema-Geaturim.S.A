<?php
session_start();
include '../../procesos/base.php';
include('../menu/app.php');
conectarse();
error_reporting(0);
$cont1 = 0;
$consulta = pg_query("select max(id_gastos) from gastos");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;
$cont5 = 0;
$consulta = pg_query("select max(id_retencion_fuente_factura_compra) from retencion_fuente_factura_compra");
while ($row = pg_fetch_row($consulta)) {
    $cont5 = $row[0];
}
$cont5++;
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
$consulta = pg_query("select max(num_serie) from retencion_fuente_factura_compra  where   estado <> 'g'");
while ($row = pg_fetch_row($consulta)) {
    $num_factura = $row[0];
}
//$consulta = pg_query("select max(num_serie) from retencion_fuente_factura_compra where clave='c'");
//while ($row = pg_fetch_row($consulta)) {
//    $num_factura_sinreten = $row[0];
//}
$consulta = pg_query("select max(num_serie) from retencion_fuente_factura_compra where estado='g'");
while ($row = pg_fetch_row($consulta)) {
    $num_factura_sinreten = $row[0];
}

function getTiposGasto()
{
    $sql = "select * from tipo_gasto where estado='Activo'";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        $rows = [];
    }
    return $rows;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>REGISTRO GASTOS</title>
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
                    Registro Gastos
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Procesos</a></li>
                    <li class="active">Registro Gastos</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab_1" data-toggle="tab">Generales</a></li>
                                <li><a href="#tab_3" data-toggle="tab">Bancarización</a></li>
                                <li><a href="#tab_4" data-toggle="tab">Formas de Pago </a></li>
                                <li id="tab2"><a href="#tab_2" data-toggle="tab">Retenciones</a></li>
                            </ul>
                            <div class="box-body">
                                <div class="rows">
                                    <div class="col-mx-12">
                                        <div class="tab-content" id="mitab">
                                            <div class="tab-pane active" id="tab_1">
                                                <form id="clientes_form" name="clientes_form" method="post">
                                                    <div class="row">
                                                        <div class="col-mx-12">
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
                                                                            <div class="input-group" style="flex-basis: 80%;">
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
                                                                            <div style="margin-left: 5px;">
                                                                                <input style="display: none;" type="file" name="facutaxml" id="facutaxml">
                                                                                <button id="btn_subir_xml" class="btn btn-primary" type="button">
                                                                                    <i class="fa fa-upload" aria-hidden="true"></i> Cargar XML
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- <div style="flex-basis: 100%; margin-top: 15px;">
                                                                            <button id="btn_cargar_prods" class="btn btn-success" type="button"><i class="fa fa-list-alt" aria-hidden="true"></i> Cargar Productos</button>
                                                                        </div> -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="col-md-5">Num. Factura:<font color="red">*</font></label>
                                                                        <div class="form-group col-md-7 no-padding">
                                                                            <input type="text" name="factura" id="factura" required class="form-control" data-inputmask='"mask": "999-999-999999999"' data-mask />

                                                                        </div><!-- /.input group -->
                                                                    </div><!-- /.form group -->
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="col-md-5">Autorización: <font color="red">*</font></label>
                                                                        <div class="form-group col-md-7 no-padding">
                                                                            <input type="text" name="autorizacion" id="autorizacion" required class="form-control" />
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="col-md-5">Tipo comprobante: <font color="red">*</font></label>
                                                                        <div class="form-group col-md-5 no-padding">
                                                                            <select class="form-control" name="tipo_comprobante" id="tipo_comprobante">
                                                                                <option value="">........Seleccione........</option>
                                                                                <option value="FACTURA" selected>FACTURA</option>
                                                                                <option value="NOTA VENTA">NOTA VENTA</option>
                                                                                <option value="LIQUIDACION COMPRA">LIQUIDACION COMPRA/SERVICIOS</option>
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
                                                                        <label class="col-md-5">Proveedor: <font color="red">*</font></label>
                                                                        <div class="input-group">
                                                                            <select class="form-control" name="tipo_docu" id="tipo_docu">
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
                                                        <div class="col-mx-12">


                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">Fecha Emisión:</label>
                                                                    <div class="form-group col-md-7 no-padding">
                                                                        <input type="date" name="fecha_emision" id="fecha_emision" class="form-control timepicker" />
                                                                    </div><!-- /.input group -->
                                                                </div><!-- /.form group -->
                                                            </div>







                                                        </div>
                                                        <div class="col-mx-12">

                                                            <div class="col-md-4" style="display: none">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">Tipo Pago:<font color="red">*</font></label>
                                                                    <div class="form-group col-md-7 no-padding">
                                                                        <select class="form-control" name="forma_pago" disabled="" id="forma_pago">
                                                                            <!--                                                                                <option value="0">........SELECCIONE........</option>-->
                                                                            <option id="contado_form" value="EFECTIVO">CONTADO</option>
                                                                            <option id="otros_form" value="otros">Formas de Pago</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4" style="display: none">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">Nro. Cheque:</label>
                                                                    <div class="form-group col-md-7 no-padding">
                                                                        <input type="text" name="cheque_tarjeta" id="cheque_tarjeta" class="form-control" disabled="disabled" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4" style="display: none">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">Deposito: </label>
                                                                    <div class="form-group col-md-7 no-padding">
                                                                        <input type="text" name="deposito" id="deposito" required class="form-control" disabled="disabled" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4" style="display: none">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">Banco:</label>
                                                                    <div class="input-group col-md-7 no-padding">
                                                                        <input type="text" name="banco" id="banco" required class="form-control" disabled="disabled" />

                                                                    </div>
                                                                </div><!-- /.form group -->
                                                            </div>

                                                            <div class="col-md-4" style="display: none">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">Cuenta N:</label>
                                                                    <div class="input-group col-md-7 no-padding">
                                                                        <input type="text" name="cuentanum" id="cuentanum" class="form-control" disabled="disabled" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </form>
                                                <hr />

                                                <div class="row">
                                                    <div class="col-md-12">

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label for="">CENTRO COSTOS</label>
                                                                        <select class="form-control" name="sel_centro_costo" id="sel_centro_costo"></select>

                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>CONCEPTO</label>
                                                                        <input type="text" name="concepto" id="concepto" style="text-transform:uppercase;" class="form-control" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label>BIEN./SERVI..</label>
                                                                        <select class="form-control" name="bien_servicio" id="bien_servicio">
                                                                            <option value="B" selected="">Bienes</option>
                                                                            <option value="S">Servicio</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label>TIPO GASTO</label>
                                                                        <?php
                                                                        $tiposg = getTiposGasto();
                                                                        $select = "<select id='tipo_gasto' class='form-control'>";
                                                                        foreach ($tiposg as $key => $value) {
                                                                            $select .= "<option value='$value[id_plan_cuentas]'>$value[nombre_tipo_gasto]</option>";
                                                                        }
                                                                        $select .= "<option value='0'>OTRO</option>";
                                                                        $select .= "</select>";
                                                                        echo $select;
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2" id="div_cuenta_contable" style="display: none;">
                                                                    <div class="form-group">
                                                                        <label>CUENTA CONTABLE</label>
                                                                        <input type="text" name="codigo_plan" id="codigo_plan" placeholder="Buscar..." class="form-control" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label>IVA:</label>
                                                                    <select class="form-control" name="tipo_iva" id="tipo_iva">
                                                                        <?php
                                                                        $consultatarifa = pg_query("select id_taimpuesto, codigo_taimpuesto, valor, codigo_timpu,nombre_taimpuesto from tarifa_impuesto inner join tipo_impuesto using(id_timpu) where estado='Activo' ORDER BY valor  desc");
                                                                        while ($row = pg_fetch_assoc($consultatarifa)) {
                                                                            $opt = "<option data-valor='$row[valor]' data-codimp='$row[codigo_timpu]' data-codtarifa='$row[codigo_taimpuesto]' value='$row[id_taimpuesto]'>$row[nombre_taimpuesto]</option>";
                                                                            echo $opt;
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <div class="form-group">
                                                                        <label>VALOR</label>
                                                                        <input type="text" name="valor" id="valor" value="" class="form-control" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <button style="display: none;" id="btn_cargar_prods" class="btn btn-success" type="button"><i class="fa fa-list-alt" aria-hidden="true"></i> Obtener Valor de Factura Cargada</button>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <div class="form-group">
                                                                        <input type="hidden" name="id_plan" id="id_plan" readonly class="form-control" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2" style="display: none;">
                                                                    <label>CENTRO COSTO:</label>
                                                                    <select class="form-control" name="centro_costo" id="centro_costo">
                                                                        <option value="Administrativos" selected>Administrativos</option>
                                                                        <option value="Servicios">Servicios</option>
                                                                        <option value="Productivos">Productivos</option>
                                                                        <option value="Ventas">Ventas</option>
                                                                        <option value="GastosCaja">Gastos de Caja</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <input type="hidden" name="descuento" id="descuento" min="0" placeholder="%" class="form-control" />
                                                        <input type="hidden" name="iva_producto" id="iva_producto" readonly class="form-control" />
                                                        <input type="hidden" name="carga_series" id="carga_series" readonly class="form-control" />
                                                        <input type="hidden" name="incluye" id="incluye" readonly class="form-control" />
                                                        <input type="hidden" name="cod_producto" id="cod_producto" readonly class="form-control" />
                                                        <!--                                                                </div>  
                                                                                                                                </div> -->
                                                    </div>
                                                </div>

                                                <!-- <div class="row"> -->
                                                <!-- <div class="row"> -->

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div id="grid_container">
                                                            <table id="list"></table>
                                                            <!--<div id="pager"></div>-->
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- </div> -->
                                                <!-- </div> -->

                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <label class="col-md-2">Descripción:</label>
                                                            <div class="input-group col-md-4 no-padding">
                                                                <textarea type="text" name="comentario" id="comentario" class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div style="display: flex; flex-wrap: wrap;" id="div_totales_tarifas">
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
                                                            <div class="form-group col-md-6">
                                                                <label>Descuento:</label>
                                                                <input type="text" name="descx" id="descx" value="0.000" readonly class="form-control" />
                                                                <input type="hidden" name="desc" id="desc" value="0.000" readonly class="form-control" />
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
                                            <div class="tab-pane" id="tab_2" name="tab_2" style="height: 854px">
                                                <div class="col-md-2">
                                                    <input type="radio" name="elegirretencionF" id="elegirretencionF2" checked value="2"><span>Con Retenciòn </span><br />

                                                </div>
                                                <div class="col-md-2">

                                                    <input type="radio" name="elegirretencionF" id="elegirretencionF1" value="1"><span></span> Sin Retenciòn </span><br />
                                                </div>
                                                   <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-md-4">Comprobante Retención: <font color="red">*</font></label>
                                                        <div class="form-group col-md-4 no-padding">
                                                            <input type="text" name="comprobante_serie" id="comprobante_serie" readonly="" class="form-control" value="<?php echo $cont5 ?>" />
                                                        </div>
                                                        <div id="estado_reten" style="margin-top: -10px">
                                                            <h3></h3>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                <br>
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <label class="col-md-4">Nro. Serie Retención: 001-001 <font color="red">*</font></label>
                                                        <div class="form-group col-md-4 no-padding">
                                                            <input type="text" name="serie_retencion" id="serie_retencion" maxlength="9" required class="form-control" />
                                                            <input type="hidden" name="num_oculto" id="num_oculto" required class="form-control" value="<?php echo $num_factura ?>" />
                                                            <input type="text" name="serie_sinretencion" id="serie_sinretencion" maxlength="9" required class="form-control" />
                                                            <input type="hidden" name="num_oculto_sinreten" id="num_oculto_sinreten" required class="form-control" value="<?php echo $num_factura_sinreten ?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="mostrar_retenciones" class="col-md-12">


                                                    <div class="col-md-3">
                                                        <div class="form-group">

                                                            <label>RETENCIÓN EN LA FUENTE BIENES</label><BR />

                                                            <input type="radio" name="retencionF" id="retencionF1" checked value="1"><span></span> NO </span><br />
                                                            <input type="radio" name="retencionF" id="retencionF2" value="2"><span> SI</span><br /><br />
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
                                                            <!--                                                                <button class="btn btn-primary" id='btnCancelarRetenciones'><i class="icon-remove-sign"></i> Cancelar</button>-->
                                                            <!--                              <button class="btn btn-primary" id='btnImprimirRetenciones'><i class="icon-print-sign"></i> Imprimir Retenciones</button>-->
                                                            <div class="col-md-4">
                                                                <label class="col-md-7">Total Reten.: </label>
                                                                <div class="form-group col-md-4 no-padding">
                                                                    <input type="text" name="total_retencion" id="total_retencion" readonly="" class="form-control" />
                                                                </div>
                                                            </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="tab-pane" id="tab_3" name="tab_3" style="height: 854px">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>FORMAS DE PAGO</label><BR />
                                                                <span>Elija Forma de Pago: </span>
                                                                <select name="formasPago" id="formasPago" class="form-control">
                                                                    <option value="0">Seleccione una opción...</option>
                                                                    <?php
                                                                    $consulta2 = pg_query("select * from forma_pagos order by id_forma_pago");
                                                                    while ($row = pg_fetch_row($consulta2)) {
                                                                        $cadena = "'" . $row[0] . "#" . $row[1] . "#" . $row[2] . "'";
                                                                        echo "<option id=$row[0] value=" . $cadena . ">$row[1]" . "-" . "$row[2]</option>";
                                                                    }
                                                                    ?>
                                                                </select><br />
                                                                <input type="hidden" id="detalle_pago" name="detalle_pago">
                                                                <input type="hidden" id="id_forma_pago" name="id_forma_pago">
                                                                <input type="hidden" id="codigo_pago" name="codigo_pago">
                                                                <input type="hidden" id="descripcion_pago" name="descripcion_pago">
                                                                <center><button class="btn btn-primary" id='btnAnadirForma'><i class="icon-save"></i> Añadir</button></center>
                                                            </div>
                                                            <div class="col-md-12" id="grid_container_pago">
                                                                <table id="listPago"></table>
                                                                <div class="col-md-12" id="pagerP"></div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>OBSERVACIONES: <font color="red">*</font></label><BR />
                                                                <textarea class="form-control" name="observacionPago" id="observacionPago" rows="3" required></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_4" name="tab_4" style="height: 854px">
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
                                                                <select class="form-control" name="formaspago_mixto" id="formaspago_mixto" disabled>
                                                                    <option value="" disabled selected>--Selecione--</option>
                                                                    <option value="Contado">Contado</option>
                                                                    <option value="Credito">Crédito</option>
                                                                    <option value="Cheque">Cheque</option>
                                                                    <option value="TCredito">Tarjeta de Crédito/Debito</option>
                                                                    <option value="Transferencias">Transferencias</option>
                                                                    <option value="pagoiess">Pago IESS Rep. Legal</option>
                                                                    <option value="anticipo_proveedores">Anticipo Proveedores</option>

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
                                                                <label>Total Factura:</label>
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
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Num Documento:</label>
                                                                <div class="form-group no-padding">
                                                                    <input type="text" name="num_tarjeta" id="num_tarjeta" required class="form-control" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Seleccione Cta Contable: </label>
                                                                <div class="input-group">
                                                                    <input type="text" name="cuenta_contable" id="cuenta_contable" class="form-control" disabled="disabled" />
                                                                    <input type="hidden" name="idCuenta" id="idCuenta" />
                                                                    <span class="input-group-btn">
                                                                        <button class="btn btn-default" id="btnCuenta" name="btnCuenta" disabled="disabled">Seleccionar Cuenta</button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="fecha_vencimiento" class="col-md-5">
                                                            <!-- <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Fecha Vencimiento:</label>
                                                                        <div class="form-group no-padding">
                                                                            <input type="Date" name="fecha_dias" id="fecha_dias" class="form-control timepicker" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Días de plazo:</label>
                                                                        <div class="form-group no-padding">
                                                                            <input type="text" id="fecha_numero_dias" name="fecha_numero_dias" class="form-control" min="0">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> -->
                                                            <div style="text-align: center;"><label for="">Fecha Vencimiento / Días de plazo:</label></div>
                                                            <div style="display: flex;">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <div class="form-group no-padding">
                                                                            <input type="Date" name="fecha_dias" id="fecha_dias" class="form-control timepicker" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <div class="form-group no-padding">
                                                                            <input type="text" id="fecha_numero_dias" name="fecha_numero_dias" class="form-control" min="0" placeholder="Nro. Dìas">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-mx-12">
                                                            <td><button class="btn btn-primary" id='btnAgregar_mixto' style="margin-top: -5px; margin-left: 50px"><i class="icon-list"></i> Agregar</button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12" id="grid_container_pago_reten_anti" disabled>
                                                    <table id="listPagoreten_mixto_anti" disabled></table>
                                                    <div class="col-md-12" id="pagerP_reten_anti"></div>
                                                </div>
                                                <div class="row">

                                                    <div class="col-md-12" id="grid_container_pago_reten">
                                                        <table id="listPagoreten_mixto"></table>
                                                        <div class="col-md-12" id="pagerP_reten"></div>
                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">

                                                        <div class="col-md-3">
                                                            <button class="btn btn-primary" id='btnGuardarRetenciones_mixto'><i class="icon-save"></i> Guardar</button>
                                                            <button class="btn btn-primary" id='btnCancelarRetenciones_mixto'><i class="icon-remove-sign"></i> Cancelar</button>
                                                            <!--                              <button class="btn btn-primary" id='btnImprimirRetenciones'><i class="icon-print-sign"></i> Imprimir Retenciones</button>-->
                                                        </div>


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
                                                    <button class="btn bg-olive margin" id='btnEstados'><i class="fa fa-table"> Estados Retenciones</i></button>
                                                </p>
                                            </div>
                                        </div>
                                        <div id="buscar_estados" title="BUSCAR ESTADOS RETENCIONES">
                                            <table id="list7">
                                                <tr>
                                                    <td></td>
                                                </tr>
                                            </table>
                                            <div id="pager7"></div>
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
                                        <div id="cuentas" title="Búsqueda Plan de Cuentas" class="">
                                            <table id="list4">
                                                <tr>
                                                    <td></td>
                                                </tr>
                                            </table>
                                            <div id="pager4"></div>
                                        </div>
                                        <div id="buscar_anticipo" title="BUSCAR ANTICIPO CLIENTE">
                                            <fieldset>
                                                <table id="list22">
                                                    <tr>
                                                        <td></td>
                                                    </tr>
                                                </table>
                                                <div id="pager22"></div>
                                            </fieldset>
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
    <script src="gastos.js" type="text/javascript"></script>
    <link href="../../dist/css/style.css" rel="stylesheet" type="text/css" />
    <script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>
    <script src="subirfactura/subirfacutra.js" type="text/javascript"></script>

</body>

</html>