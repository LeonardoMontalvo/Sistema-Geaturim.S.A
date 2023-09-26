<?php
session_start();
include '../../procesos/base.php';
include('../menu/app.php');
include_once __DIR__ . "/../apertura_caja/consultar_caja.php";
conectarse();
error_reporting(0);

$cajaabierta = cajaAbiertaDiaActual();

$consulta8 = pg_query("select * from punto_venta_empresa  left join punto_venta  on punto_venta_empresa.id_punto_venta=punto_venta.id_punto_venta  where  
punto_venta_empresa.id_usuario='$_SESSION[id]' ORDER BY id_punto_venta_empresa DESC limit 1");
while ($row = pg_fetch_row($consulta8)) {
    $campo_punto_ventaid = $row[5];
}
$consulta = pg_query("select max(num_factura)  from factura_venta,  punto_venta_empresa where    
factura_venta.id_empresa=$campo_punto_ventaid and num_chasis='1' ");
while ($row = pg_fetch_row($consulta)) {
    $num_factura = $row[0];
}
$consulta_guia = pg_query("select max(num_guia_remision) from guia_remision,  punto_venta_empresa where    
guia_remision.id_empresa=$campo_punto_ventaid  and punto_venta_empresa.id_usuario='$_SESSION[id]' ");
while ($row = pg_fetch_row($consulta_guia)) {
    $num_guia = $row[0];
}

$cont1 = 0;
$consulta2 = pg_query("select max(id_factura_venta) from factura_venta");
while ($row = pg_fetch_row($consulta2)) {
    $cont1 = $row[0];
}
$cont1++;
$cont1_nota = 0;
$consulta2 = pg_query("select max(id_facturas_novalidas) from facturas_novalidas");
while ($row = pg_fetch_row($consulta2)) {
    $cont1_nota = $row[0];
}
$cont1_nota++;
$consulta_nv = pg_query("select max(id_facturas_novalidas) from facturas_novalidas nv, punto_venta_empresa pe where    
nv.id_empresa=$campo_punto_ventaid  and pe.id_usuario='$_SESSION[id]';");
while ($row = pg_fetch_row($consulta_nv)) {
    $num_nota = $row[0];
}
$consulta3 = pg_query("select id_cliente,identificacion,nombres_cli,direccion_cli,correo,nombre_vendedor,vendedores.id_vendedor
from clientes inner join rutas on rutas.id_ruta=clientes.credito_cupo
left join vendedores on vendedores.id_vendedor=rutas.id_vendedor
where clientes.estado='Activo' and rutas.estado='Activo' order by id_cliente asc limit 1");
while ($row = pg_fetch_row($consulta3)) {
    $campo_id_cliente = $row[0];
    $campo_identificacion_cliente = $row[1];
    $campo_nombre_cliente = $row[2];
    $campo_direccion_cliente = $row[3];
    $campo_correo = $row[4];
    $campo_vendedor = $row[5];
    $campo_id_vendedor = $row[6];
}

$consulta4 = pg_query("select max(id_factura_venta) from factura_venta");
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

$consulta10 = pg_query("select porcentaje_tarjeta from empresa");
while ($row = pg_fetch_row($consulta10)) {
    $campo_porsentaje_tarjeta = $row[0];
}

$consulta7 = pg_query("select * from punto_venta_empresa  left join punto_venta  on punto_venta_empresa.id_punto_venta=punto_venta.id_punto_venta  where  
punto_venta_empresa.id_usuario='$_SESSION[id]' ORDER BY id_punto_venta_empresa DESC LIMIT 1");
while ($row = pg_fetch_row($consulta7)) {
    $campo_punto_venta = $row[6];
}

$consulta6 = pg_query("select * from vendedores order by id_vendedor desc");
while ($row = pg_fetch_row($consulta6)) {
    $campo_nombre_vendedor = $row[0];
}
$consultaforma = pg_query("select id_forma_pago from forma_pagos where id_forma_pago=1");
while ($row = pg_fetch_row($consultaforma)) {

    $campo_nombre_forma = $row[0];
}

$consulta = pg_query("select max(num_serie) from retencion_fuente_factura_venta  ");
while ($row = pg_fetch_row($consulta)) {
    $num_factura_reten = $row[0];
}
?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>FACTURA VENTA</title>
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
        <link href="../../dist/css/jquery-ui-1.10.4.custom.css" rel="stylesheet" type="text/css" />
        <link href="../../dist/css/ui.jqgrid.css" rel="stylesheet" type="text/css" />
        <link href="../../plugins/iCheck/flat/blue.css" rel="stylesheet" type="text/css" />
        <style>
            .loader_factura {
                position: fixed;
                /* Sit on top of the page content */
                /* display: none; */
                /* Hidden by default */
                width: 100%;
                /* Full width (cover the whole page) */
                height: 100%;
                /* Full height (cover the whole page) */
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                /* Black background with opacity */
                z-index: 1000;
                /* Specify a stack order in case you're using a different order for other elements */
                cursor: pointer;
                /* Add a pointer on hover */
                display: flex;
                justify-content: center;
                align-items: center;
                color: white;
                flex-direction: column;
                visibility: hidden;
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
        <div class="loader_factura">
            <i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
            <span>Procesando...</span>
            <span class="sr-only">Loading...</span>
        </div>
        <div class="wrapper">
            <?php banner_1(); ?>
            <?php menu_lateral_1(); ?>
            <div class="content-wrapper">
                <section class="content-header">
                    <h1>
                        FACTURA VENTA
                    </h1>

                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Procesos</a></li>
                        <li class="active">Factura Venta</li>
                    </ol>
                </section>
                <div id="conteiner_apertura" style="display: <?php echo ($cajaabierta == 1 ? "none" : "") ?>;"></div>
                <!-- Main content -->
                <section class="content" style="display: <?php echo ($cajaabierta == 1 ? "" : "none") ?>;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab_1" data-toggle="tab">Generales</a></li>
                                    <li><a href="#tab_2" data-toggle="tab">Adicionales</a></li>
                                    <li><a href="#tab_3" data-toggle="tab">Formas de Pago</a></li>
                                </ul>
                                <form id="productos_form" name="productos_form" method="post">
                                    <div class="box-body">
                                        <div class="rows">
                                            <div class="tab-content">
                                                <!-- tab-content-->
                                                <div class="tab-pane active" id="tab_1">
                                                    <!-- tab-pane -->
                                                    <div class="row ">
                                                        <div class="col-md-12">
                                                            <div class="col-md-12">
                                                                <div id="debe" style="display: none; height: 2px; padding-top:  2px;" class="alert alert-danger">
                                                                    <strong></strong> CLIENTE TIENE CUENTAS POR COBRAR PENDIENTES.
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div id="debe1" style="display: none; height: 2px; padding-top:  2px;" class="alert alert-danger">
                                                                    <strong></strong> EL PRODUCTO TIENE DESCUENTO DE PROMOCIÓN EN ESTE DÍA.
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <!--<label>Fecha:</label>-->
                                                                    <div class="input-group">
                                                                        <input type="date" name="fecha_actual" id="fecha_actual" readonly class="form-control timepicker" />
                                                                        <input type="hidden" name="proforma" id="proforma" readonly class="form-control" />
                                                                        <input type="hidden" name="id_factura_venta" id="id_factura_venta" readonly class="form-control" />
                                                                        <input type="hidden" name="comprobante_nota" id="comprobante_nota" readonly class="form-control" value="<?php echo $cont1_nota ?>" />
                                                                        <div class="input-group-addon">
                                                                            <i class="fa fa-calendar"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <div class="bootstrap-timepicker">
                                                                    <div class="form-group">
                                                                        <!--                                                                                                                                    <label>Hora Actual:</label>-->
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
                                                                    <!--<label>Digitad@r:</label>-->
                                                                    <input type="text" name="digitador" id="digitador" readonly class="form-control" />
                                                                    <input type="hidden" name="comprobante2" id="comprobante2" readonly class="form-control">
                                                                </div>
                                                            </div>

                                                            <!--                                                            <div class="col-md-2">
                                                                                                                            <div class="form-group">-->
                                                            <!--<label>Punto de Venta:</label>-->
                                                            <input type="hidden" name="punto_venta" id="punto_venta" required readonly class="form-control" value="<?php echo $campo_punto_venta ?>" />
                                                            <input type="hidden" name="punto_ventaid" id="punto_ventaid" required readonly class="form-control" value="<?php echo $campo_punto_ventaid ?>" />
                                                            <input type="hidden" name="buscar_pv" id="buscar_pv" required class="form-control" />
                                                            <!--                                                                </div>
                                                                                                                        </div>-->
                                                            <div class="col-md-1">
                                                                <div class="form-group">
                                                                    <!--<label>Punto de Venta:</label>-->
                                                                    <input type="text" name="comprobante" id="comprobante" readonly class="form-control" value="<?php echo $cont1 ?>" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">

                                                                <div class="form-group">
                                                                    <input type="text" name="num_items" id="num_items" required readonly class="form-control" value="<?php echo $campo_num_items ?>" />

                                                                    <input type="hidden" name="porcentaje_tc" id="porcentaje_tc" required readonly class="form-control" value="<?php echo $campo_porcentaje_tc ?>" />

                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">


                                                                <input type="text" name="nombre_vendedor" id="nombre_vendedor" disabled="" required class="form-control" value="<?php echo "Vende.:" . "  " . $campo_vendedor ?>" />
                                                                <input type="hidden" name="vendedor" id="vendedor" value="1" placeholder="Buscar..." class="form-control" value="<?php echo $campo_id_vendedor ?>" />


                                                            </div>

                                                            <!--                                                            <label>Vendedor: </label>
                                                                                                                                            <div class="input-group">
                                                                                                                                                <select class="form-control" name="vendedor" id="vendedor">
                                                                                                                                                    <option   value="<?php //echo $campo_nombre_vendedor;                                               
            ?>" >Vendedor1 </option>
                                                                                                                                                     <option   value="<?php //echo $campo_nombre_vendedor;                                               
            ?>" >VENDEDOR1 </option> 
                                                            <?php
                                                            $consultapro = pg_query("select * from vendedores ");
                                                            while ($row = pg_fetch_row($consultapro)) {
                                                                if ($row[0] == 1) {
                                                                    echo "<option id=$row[0] value=$row[0] selected>$row[1]</option>";
                                                                } else {
                                                                    echo "<option id=$row[0] value=$row[0]>$row[1]</option>";
                                                                }
                                                            }
                                                            ?>
                                                                                                                                                </select>
                                                                                                                                                <span class="input-group-btn">
                                                                                                                                                    <button class="btn btn-primary" id='btnActualizar'>Actualizar</button>
                                                                                                                                                    <input type ='button' class="btn btn-primary" value = 'Vendedor' onclick="window.open('../vendedores/index.php', 'width=800,height=600');"/>
                                                                                                                                                </span>
                                                                                                                                            </div>-->



                                                            <br/> 
                                                            <br/>      <br/> 
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="col-md-4">No Factura: <font color="red">*</font></label>
                                                                    <div class="form-group col-md-8 no-padding">
                                                                        <input type="text" name="num_factura" id="num_factura" readonly="" required class="form-control" />
                                                                        <input type="hidden" name="num_oculto" id="num_oculto" required class="form-control" value="<?php echo $num_factura ?>" />
                                                                        <input type="hidden" name="num_oculto_nv" id="num_oculto_nv" required class="form-control" value="<?php echo $num_nota ?>" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="col-md-3">Tipo de Venta:</label>
                                                                    <div class="form-group col-md-6 no-padding">
                                                                        <select class="form-control" name="tipo_venta" id="tipo_venta">
                                                                            <option value="FACTURA" selected>FACTURA</option>
                                                                            <option value="NOTA">NOTA VENTA</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="col-md-4">Tipo de Precio:</label>
                                                                    <div class="form-group col-md-7 no-padding">
                                                                        <select disabled class="form-control" name="tipo_precio" id="tipo_precio">
                                                                            <option id="mino" value="MINORISTA">MINORISTA</option>
                                                                            <option id="mayo" value="MAYORISTA">MAYORISTA</option>
                                                                            <option id="nego" value="NEGOCIO">NEGOCIO</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        <!--                                                                                                                    <input type="hidden" name="retencionFSguia" id="retencionF1Sguia" checked value="1"><span></span> </span><br/>
                                                            <input type="hidden" name="retencionFSguia" id="retencionF2Sguia" value="2"><span>  </span><br/><br/>-->



                                                            <!-- <div class="col-md-3">
                                                                                    <div class="form-group">
                                                                                        <label class="col-md-5">Beneficiario: </label>
                                                                                        <div class="form-group col-md-7 no-padding">
                                                                                            <input type="text" name="ruc_ci_bene"  id="ruc_ci_bene" placeholder="Buscar Cèdula Beneficiario....." required class="form-control" value=""  />
                                                                                            <input type="hidden" name="id_beneficiario"  id="id_beneficiario" placeholder="Buscar....." required class="form-control" value="0" />
                                                                                        </div>
                                                                                    </div>
                                                                                </div> -->

                                                            <!-- <div class="col-md-3">
                                                                                    <div class="form-group">
                                                                                        <div class="form-group col-md-9 no-padding">
                                                                                            <input type="text" name="nombre_cliente_bene"  id="nombre_cliente_bene" placeholder=" Buscar Nombre Beneficiario....."  required class="form-control" value=""  />
                                                                                        </div>
                                                                                    </div>
                                                                                </div> -->



                                                            <div class="row ">
                                                                <div class="col-md-12">
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label class="col-md-4">Ced/RUC: <font color="red">*</font></label>
                                                                            <div class="form-group col-md-8 no-padding">
                                                                                <input type="text" name="ruc_ci" id="ruc_ci" placeholder="Buscar....." required class="form-control" value="<?php echo $campo_identificacion_cliente ?>" />

                                                                                <input type="hidden" name="id_cliente" id="id_cliente" placeholder="Buscar....." required class="form-control" value="<?php echo $campo_id_cliente ?>" />
                                                                                <input type="hidden" name="id_tdocu" id="id_tdocu" class="form-control" />

                                                                    <!--<button class="btn bg-olive margin" id='btnBuscar_cliente'><i class="fa fa-search"></i> Buscar_cliente</button>-->
                                                                            </div>
                                                                            <!--                                                                    <button id="btnBuscar_cliente" style="font-size: 14px;" class="btn btn-primary" type="button">
                                                                                                                                                        <i class="fa fa-search" aria-hidden="true" id="icono_buscar1"></i>
                                                                                                                                                        <div id="icono_buscando1" style="display: none;"><i class="fa fa-circle-o-notch fa-spin" style="font-size: small;"></i>
                                                                                                                                                            <span class="sr-only">Loading...</span>
                                                                                                                                                        </div>
                                                                                                                                                    </button>-->
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label class="col-md-4">Nombres:</label>
                                                                            <div class="form-group col-md-8 no-padding">
                                                                                <input type="text" name="nombre_cliente" id="nombre_cliente" style="text-transform: uppercase" required class="form-control" value="<?php echo $campo_nombre_cliente ?>" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label class="col-md-6">Formas de Pago:</label>
                                                                            <div class="form-group col-md-6 no-padding">
                                                                                <div>
                                                                                    <select class="form-control" name="formaspago" id="formaspago">
                                                                                        <option id="contado_form" value="Contado">Contado</option>
                                                                                        <option value="otros">Formas de Pago </option>

                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label class="col-md-3">F.P.Electró.</label>
                                                                            <div class="form-group col-md-9 no-padding">
                                                                                <select class="form-group col-md-11 no-padding" name="formas" id="formas">
                                                                                    <option   value="<?php echo $campo_nombre_forma ?>" >SIN UTILIZACION DEL SISTEMA FINANCIERO </option>
                                                                                    <?php
                                                                                    $consultapro = pg_query("select * from  forma_pagos ");
                                                                                    while ($row = pg_fetch_row($consultapro)) {
                                                                                        echo "<option id=$row[0] value=$row[0]>$row[2]</option>";
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>  

                                                                </div>

                                                            </div>
                                                        </div>




                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="col-md-4">Dirección: <font color="red">*</font></label>
                                                                <div class="form-group col-md-8 no-padding">
                                                                    <input type="text" name="direccion_cliente" id="direccion_cliente" style="text-transform: uppercase" required class="form-control" value="<?php echo $campo_direccion_cliente ?>" />
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-md-3">Teléfono:</label>
                                                                <div class="form-group col-md-6 no-padding">
                                                                    <input type="text" name="telefono_cliente" id="telefono_cliente" class="form-control" />
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label class="col-md-3">Correo:</label>
                                                                <div class="form-group col-md-6 no-padding">
                                                                    <input type="text" name="correo" id="correo" class="form-control" style="text-transform: lowercase" value="<?php echo $campo_correo ?>" />
                                                                </div>
                                                            </div>
                                                        </div>



                                                        <div class="col-md-6" style="display: none">
                                                            <label class="col-md-5">SIN GUÍA DE REMISIÓN</label>
                                                            <div class="form-group col-md-2 ">
                                                                <input type="radio" name="retencionFSguia" id="retencionF1Sguia" checked value="1">
                                                            </div>
                                                        </div>


                                                        <div class="col-md-6" style="display: none">
                                                            <label class="col-md-5">CON GUÍA DE REMISIÓN</label>
                                                            <div class="form-group col-md-2 ">
                                                                <input type="radio" name="retencionFSguia" id="retencionF2Sguia" value="2">
                                                            </div>
                                                        </div>

                                                        <!-- <div class="form-group"> -->
                                                        <div id="estado" style="margin-top: -10px">
                                                            <h3></h3>
                                                        </div>
                                                        <!-- </div> -->


                                                        <!--<div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label class="col-md-4">Autorización:</label>
                                                                                    <div class="form-group col-md-7 no-padding">
                                                                                        <input type="hidden" name="autorizacion" id="autorizacion"  class="form-control" />-->
                                                        <input type="text" name="autorizacion1" id="autorizacion1" required class="form-control" style="visibility:hidden" />
                                                        <!--</div>
                                                                        </div>
                                                                    </div>-->

                                                        <!--<div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label class="col-md-5">Fecha autorización:</label>
                                                                                    <div class="form-group col-md-7 no-padding">-->
                                                        <input type="hidden" name="fecha_auto" id="fecha_auto" readonly required class="form-control" />
                                                        <!--</div>
                                                                                </div>
                                                                            </div>-->

                                                        <!--<div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label class="col-md-5">Fecha caducidad:</label>
                                                                                    <div class="form-group col-md-7 no-padding">-->
                                                        <input type="hidden" name="fecha_caducidad" id="fecha_caducidad" readonly required class="form-control" />

                                                    <!--                                                            <input type="hidden" name="formaspago"  id="formaspago" value="Contado" placeholder="Buscar..." class="form-control" />-->

                                                        <input type="hidden" name="id_beneficiario" id="id_beneficiario" placeholder="Buscar....." required class="form-control" value="1" />
                                                        <!--<input type="hidden" name="formas" id="formas" placeholder="Buscar....." required class="form-control" value="1" />-->
                                                        <!--                                                                   
                                                                          </div>
                                                                  </div>
                                                              </div>-->

                                                    </div>
                                                    <!--                                                    </div>-->


                                                    <!--                                                    <div class="row">
                                                                                                                                <div class="col-md-12">
                                                                        
                                                                                                                                    <div class="col-md-3">
                                                                                                                                        <div class="form-group">
                                                                                                                                            <label>Fecha Emisión:</label>
                                                                                                                                            <div>
                                                                                                                                                <input type="date" name="cancelacion"  id="cancelacion"  class="form-control timepicker"/>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                        
                                                                                                                                    <div class="col-md-3">
                                                                                                                                        <div class="form-group">
                                                                                                                                            <label>Tipo de Precio:</label>
                                                                                                                                            <div>
                                                                                                                                                <select class="form-control" name="tipo_precio" id="tipo_precio">
                                                                                                                                                    <option value="MINORISTA">MINORISTA</option>
                                                                                                                                                    <option   value="MAYORISTA">MAYORISTA</option>
                                                                                                                                                    <option value="NEGOCIO">NEGOCIO</option>
                                                                                                                                                </select>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                        
                                                                                                                                    <div class="col-md-3">
                                                                                                                                        <div class="form-group">
                                                                                                                                            <label>Formas de Pago:</label>
                                                                                                                                            <div>
                                                                                                                                                <select class="form-control" name="formaspago" id="formaspago">
                                                                                                                                                    <option  id="contado_form" value="Contado">Contado</option>
                                                                                                                                                    <option value="otros">Formas de Pago </option>
                                                                                                                                                                                        <option value="Cheque">Cheque</option>
                                                                                                                                                                                        <option value="TCredito">Tarjeta de Crédito</option>
                                                                                                                                                </select>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                    </div>-->

                                                    <!--                                                    <div class="col-md-3">
                                                                                                            <div class="form-group">
                                                                                                                <label class="col-md-5">Tarjeta de Crédito:</label>
                                                                                                                <div class="form-group col-md-7 no-padding">                                
                                                                                                                    <select class="form-control" name="tarjetas" id="tarjetas">
                                                                                                                        <option value="">Seleccione una opción</option>
                                                                                                                        <option value="Mastercard">Mastercard</option>
                                                                                                                        <option value="Pacificard">Pacificard</option>
                                                                                                                        <option value="Pacificard">Diners Club</option>
                                                                                                                        <option value="Visa">Visa</option>
                                                                                                                        <option value="Otra">Otra...</option>
                                                                                                                    </select>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>-->

                                                    <!--                                                    <div class="col-md-3">
                                                                                                            <div class="form-group">
                                                                                                                <label>Formas de Pago Electrònico:</label>
                                                                                                                <div>
                                                                                                                    <select class="form-control" name="formas" id="formas">
                                                                                                                        <option   value="<?php echo $campo_nombre_forma ?>" >SIN UTILIZACION DEL SISTEMA FINANCIERO </option>
                                                    <?php
                                                    $consultapro = pg_query("select * from  forma_pagos ");
                                                    while ($row = pg_fetch_row($consultapro)) {
                                                        echo "<option id=$row[0] value=$row[0]>$row[2]</option>";
                                                    }
                                                    ?>
                                                                                                                    </select>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>-->

                                                    <!--                                                                                                                            </div>
                                                                                                                                                                            </div>-->

                                                    <div class="row" style="display: none">
                                                        <div class="col-md-12">
                                                            <div class="col-md-6">
                                                                <label id="translabel">Transportista: </label>
                                                                <div class="input-group">
                                                                    <select class="form-control" name="transportistaguia" id="transportistaguia" disabled>
                                                                        <?php
                                                                        $consultapro = pg_query("select * from transportista ");
                                                                        while ($row = pg_fetch_row($consultapro)) {
                                                                            echo "<option id=$row[0] value=$row[0]>$row[2]-$row[1]</option>";
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                    <span class="input-group-btn">
                                                                        <button class="btn btn-primary" id='btnActualizartrans'>Actualizar</button>
                                                                        <input type='button' class="btn btn-primary" id="trans" value='Transportista' onclick="window.open('../Transportista/index.php', 'width=800,height=600');" />
                                                                </div>
                                                            </div>

                                                            <div class="input-group">
                                                                <label class="col-md-6" id="num_labelg">Num. Guia</label>
                                                                <input type="text" name="num_serie_guia" id="num_serie_guia" required maxlength="9" class="form-control" disabled />
                                                                <input type="hidden" name="num_oculto_guia" id="num_oculto_guia" required class="form-control" value="<?php echo $num_guia ?>" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--                                                    <hr />
                                                            <h3 class="box-title" style="margin-left: 15px">Detalle Factura</h3>-->

                                                    <div class="row">
                                                        <div class="col-md-12">

                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label>CÓDIGO BARRAS</label>
                                                                    <input type="text" style="text-transform: uppercase" name="codigo_barras" style="text-transform: uppercase" id="codigo_barras" placeholder="Buscar..." class="form-control" />
                                                                </div>
                                                            </div>

                                                            <!--                                                            <div class="col-md-2">
                                                                                                                                                <div class="form-group">
                                                                                                                                                    <label>CÓDIGO</label>-->
                                                            <input type="hidden" name="codigo" id="codigo" placeholder="Buscar..." class="form-control" />
                                                            <!--                                                                </div>
                                                                                                                                            </div>-->

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>PRODUCTO</label>
                                                                    <input type="text" name="producto" id="producto" style="text-transform: uppercase" placeholder="Buscar..." class="form-control" />
                                                                </div>
                                                            </div>

                                                            <div class="col-md-1">
                                                                <label>U.MED: </label>
                                                                <div class="form-group">
                                                                    <select class="form-control" name="unidad_medida" id="unidad_medida">
                                                                    </select>

                                                                    <!--<button class="btn btn-primary" id='btnActualizarum'>↺</button>-->
                                                                    <!--<input type='button' class="btn btn-primary" value='+' onclick="window.open('../medida/index.php', 'width=800,height=600');" />-->
                                                                </div>
                                                            </div>


                                                            <!--<div class="col-md-1">
                                                                                                                                              <div class="form-group">
                                                                                                                                                <label>PU.VENTA</label>
                                                                                                                                                 <input type="text" name="punto_venta_inv"  id="punto_venta_inv" readonly class="form-control" />
                                                                                                                                              </div>
                                                                                                                                            </div>
                                                            -->

                                                            <div class="col-md-1">
                                                                <div class="form-group">
                                                                    <label>CANTIDAD</label>
                                                                    <input type="text" name="cantidad" id="cantidad" class="form-control" placeholder="0.00" />
                                                                    <input type="hidden" name="cantidad_unidad" id="cantidad_unidad" readonly="" class="form-control" min="1" />
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2" style="display: none;">
                                                                <div class="form-group">
                                                                    <label>PRECIO CON IVA:</label>
                                                                    <input type="text" name="venta_iva" id="venta_iva" class="form-control" disabled />
                                                                </div>
                                                            </div>

                                                            <div class="col-md-1">
                                                                <div class="form-group">
                                                                    <label>SIN IVA</label>
                                                                    <input type="text" name="p_venta" id="p_venta" class="form-control" placeholder="0.0000" />
                                                                </div>
                                                            </div>

                                                            <div class="col-md-1">
                                                                <div class="form-group">
                                                                    <label>P.FINAL:</label>
                                                                    <input type="text" name="venta_iva_1" id="venta_iva_1" class="form-control" placeholder="0.0000" />
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
                                                                    <input type="text" name="disponibles" id="disponibles" readonly class="form-control" placeholder="0.0000" />
                                                                    <input type="hidden" name="iva_producto" id="iva_producto" readonly class="form-control" />
                                                                    <input type="hidden" name="carga_series" id="carga_series" readonly class="form-control" />
                                                                    <input type="hidden" name="cod_producto" id="cod_producto" readonly class="form-control" />
                                                                    <input type="hidden" name="cod_producto_tem" id="cod_producto_tem" readonly class="form-control" />
                                                                    <input type="hidden" name="cod_producto_promo" id="cod_producto_promo" readonly class="form-control" />
                                                                    <input type="hidden" name="cantidad_producto_promo" id="cantidad_producto_promo" readonly class="form-control" />
                                                                    <input type="hidden" name="des" id="des" readonly class="form-control" />
                                                                    <input type="hidden" name="incluye" id="incluye" readonly class="form-control" />
                                                                    <input type="hidden" name="inventar" id="inventar" readonly class="form-control" />
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="row" style="display: none">
                                                        <div class="col-md-12">
                                                            <div class="col-md-5">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">CENTRO DE COSTOS</label>
                                                                    <div class="form-group col-md-4 no-padding">
                                                                        <select class="form-control" name="sel_centro_costo" id="sel_centro_costo"></select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-18">

                                                                <div class="col-md-6">
                                                                    <label class="col-md-3">DESCRIPCIÓN</label>
                                                                    <div class="form-group col-md-9 no-padding">

                                                                        <textarea class="form-control" name="descripocion_prod" id="descripocion_prod" rows="1" placeholder="INGRESE DESCRIPCIÓN DEL PRODUCTO"></textarea>
                                                                    </div>
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
                                                            <!--                                                            <div class="col-md-6">
                                                                                                                                            </div>-->
                                                            <!--                                                            <div class="col-md-5">
                                                                                                                                                <div class="form-group">
                                                                                                                                                    <label class="col-md-2" >SERIES: </label>                                                              
                                                                                                                                                    <textarea class="form-control" name="series_area" id="series_area" rows="3" readonly required></textarea>
                                                                                                                                                </div>                    
                                                                                                                                              </div>-->
                                                           
                                                            
                                                            
                                                            <div class="col-md-1"> 
                                                            <div class="form-group">
                                                                <label>No Items:</label>
                                                                <div class="form-group no-padding">
                                                                    <input type="text" name="items" id="items" value="0" readonly class="form-control" />
                                                                </div>
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
                                                                <label>Desc. Porc. %</label>
                                                                <div class="input-group">
                                                                    <input style="width: 100px"  min="0" max="100" type="number" name="descxa" id="descxa" value="0" class="form-control" />
                                                                   
                                                                </div>
                                                                <!-- <input type="number" name="descxa" id="descxa" value="0" class="form-control" /> -->
                                                                <!-- <input type="number" name="descxa_v" id="descxa_v" value="0" class="form-control" />$ -->
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>Desc. Valor. $</label>
                                                                <div class="input-group">
                                                                    <input style="width: 100px"   step="0.01" type="number" name="descxa_v" id="descxa_v" value="0" class="form-control" />
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>Result.Des.:</label>
                                                                <input style="width:80px;height:30px;" type="hidden" name="descxax" id="descxax" readonly="" value="0" class="form-control" />
                                                                <input style="width:80px;height:30px;" type="number" name="desctotal" id="desctotal" readonly="" value="0" class="form-control" />
                                                            </div>
                                                        </div>

                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>Iva....%:</label>
                                                                <input style="width:80px;height:30px;" type="text" name="ivax" id="ivax" value="0.000" readonly class="form-control" />
                                                                <input type="hidden" name="iva" id="iva" value="0.000" readonly class="form-control" />
                                                            </div>
                                                        </div>

                                                        <!--                                                                <div class="form-group">
                                                                                                                                                    <label class="col-md-5">Descuento:</label>
                                                                                                                                                    <div class="form-group col-md-7 no-padding">
                                                                                                                                                        <div class="input-group">
                                                                                                                                                            <div class="input-group-addon">
                                                                                                                                                                <i class="glyphicon glyphicon-usd"></i>
                                                                                                                                                            </div>-->
                                                        <input type="hidden" name="descx" id="descx" value="0.000" readonly class="form-control" />
                                                        <input type="hidden" name="desc" id="desc" value="0.000" readonly class="form-control" />
                                                        <!--                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                </div>-->


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
                                                    <div class="col-md-12">
                                                        <div class="col-md-2">

                                                            <!--                                                                <div class="form-group">
                                                                                        <label class="col-md-5">No Items: max</label>
                                                                                        <div class="form-group col-md-7 no-padding">
                                                                                            <input type="text" name="num_items" id="num_items" required readonly class="form-control" value="<?php echo $campo_num_items ?>"  />
                                                                                        </div>
                                                                                        <input type="hidden" name="porcentaje_tc" id="porcentaje_tc" required readonly class="form-control" value="<?php echo $campo_porcentaje_tc ?>"  />
                                                                                    </div>-->

                                                            <!--                                                                <div class="form-group">
                                                                                        <label class="col-md-5">No Productos:</label>
                                                                                        <div class="form-group col-md-7 no-padding">-->
                                                            <input type="hidden" name="num" id="num" value="0" readonly class="form-control" />
                                                            <!--<button class="btn bg-olive margin" id='btnGuardarTemporal'><i class="fa fa-save"></i> Factura Temporal</button>-->
                                                            <!--                                                                    </div>
                                                                                    </div>-->
                                                        </div>
                                                    </div>
                                                </div>

                                            </div><!-- tab-pane -->

                                            <div class="tab-pane" id="tab_2" name="tab_2" style="height: 854px">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <!--                                                            <h3 style="margin: 0;">Buscar Retención Electrónica:</h3>-->
                                                        <div style="margin-bottom: 25px; border: 1px solid black; border-radius:5px; padding:15px; display:flex; flex-direction: column;">
                                                            <div class="row" style="flex-basis: 100%;">
                                                                <div class="col-md-12" style="display: flex;">
                                                                    <label style="flex-basis: 12%; align-self: center;" for="">Clave de Acceso:</label>
                                                                    <div class="input-group" style="flex-basis: 85%;">
                                                                        <input placeholder="INGRESE LA CLAVE DE ACCESO DE LA RETENCIÓN" class="form-control" id="clavefactura" type="search">
                                                                        <span class="input-group-btn">
                                                                            <button id="btn_buscar_clave" style="font-size: 14px;" class="btn btn-primary" type="button">
                                                                                <i class="fa fa-search" aria-hidden="true" id="icono_buscar"></i>
                                                                                <div id="icono_buscando" style="display: none;"><i class="fa fa-circle-o-notch fa-spin" style="font-size: small;"></i>
                                                                                    <span class="sr-only">Loading...</span>
                                                                                </div>
                                                                            </button>
                                                                        </span>
                                                                    </div>

                                                                    <button id="btn_cargar_valores" class="btn btn-success" type="button">
                                                                        <i class="fa fa-list-alt" aria-hidden="true"></i> Cargar Valores Retención
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <!-- <div style="flex-basis: 100%; margin-top: 15px;">
                                                                        <button id="btn_cargar_prods" class="btn btn-success" type="button"><i class="fa fa-list-alt" aria-hidden="true"></i> Cargar Productos</button>
                                                                    </div> -->
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Nro. de Factura Cargada para Registro de Retención: </label>
                                                                <div class="form-group no-padding">
                                                                    <input id="nro_factura_retencion" style="background-color: rgb(66, 165, 245); font-weight: bold; color:black" class="form-control" readonly />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Nro. Serie Retención: <font color="red">*</font></label>
                                                        <input type="text" name="serie_retencion" id="serie_retencion" required class="form-control" data-inputmask='"mask": "999-999-999999999"' data-mask />
                                                        <input type="hidden" name="num_oculto_reten" id="num_oculto_reten" required class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Núm. Autorización: <font color="red">*</font></label>
                                                        <input required type="text" name="autorizacion_retencion" id="autorizacion_retencion" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Fecha Autorizacion Retención: <font color="red">*</font></label>
                                                        <input required type="date" name="fecha_aut_retencion" id="fecha_aut_retencion" class="form-control timepicker" />
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Fecha Registro Retención: <font color="red">*</font></label>
                                                        <input type="date" name="fecha_retencion" id="fecha_retencion" class="form-control timepicker" />
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>RETENCIÓN EN LA FUENTE BIENES</label><BR />

                                                            <input type="radio" name="retencionF" id="retencionF1" checked value="1"><span></span> NO </span><br />
                                                            <input type="radio" name="retencionF" id="retencionF2" value="2"><span> SI</span><br /><br />
                                                            <input type="hidden" name="porcent_reten" id="porcent_reten" class="form-control" />
                                                            <span>Elija Retención: </span>
                                                            <select name="tipoRetencionesF" id="tipoRetencionesF" class="form-control" disabled>
                                                                <option value="0">Seleccione una opción...</option>
                                                                <?php
                                                                $consulta2 = pg_query("select * from retencion_fuentes_r order by id_retencion_fuentes_r");
                                                                while ($row = pg_fetch_row($consulta2)) {
                                                                    echo "<option id=$row[0] value=$row[0]>$row[4]" . " -" . "$row[2]" . " % " . "$row[1]</option>";
                                                                }
                                                                ?>
                                                            </select><br />
                                                            <span>Valor de Retención:</span> <input type="text" name="calculoRetencionF" id="calculoRetencionF" value="0.000" readonly class="form-control" />
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
                                                                $consulta2 = pg_query("select * from retencion_fuentes_r order by id_retencion_fuentes_r");
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
                                                                $consulta2 = pg_query("select * from retencion_iva_r order by id_retencion_iva_r");
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
                                                                $consulta2 = pg_query("select * from retencion_iva_r order by id_retencion_iva_r");
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
                                                    <div class="col-md-12"></div>
                                                    <div class="col-md-12" id="grid_container_pago_reten">
                                                        <table id="listPagoreten"></table>
                                                        <div class="col-md-12" id="pagerP_reten"></div>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <br />

                                                        <div class="col-md-6">
                                                            <label class="col-md-6">Forma Pago Retenciones:</label>
                                                            <div class="form-group col-md-4 no-padding">
                                                                <select class="form-control" name="formaspago_mixto_reten" id="formaspago_mixto_reten" disabled>
                                                                    <option value="" disabled selected>--Selecione--</option>
                                                                    <option value="Contado_reten">Contado</option>
                                                                    <option value="Cheque_reten">Cheque</option>
                                                                    <option value="TCredito_reten">Tarjeta de Crédito</option>
                                                                    <option value="Transferencias_reten">Transferencias/Debito</option>
                                                                    <option value="cxc">Cuenta Cobrar</option>
                                                                    <option value="cxp">Cuenta Pagar</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">

                                                                <div class="form-group col-md-4 no-padding">

                                                                    <input type="text" name="cuenta_contable_reten" id="cuenta_contable_reten" class="form-control" disabled="disabled" />
                                                                    <input type="hidden" name="idCuenta_reten" id="idCuenta_reten" />

                                                                </div>
                                                                <div class="form-group col-md-4 no-padding">
                                                                    <button class="btn btn-default" id="btnCuenta_reten" name="btnCuenta_reten" disabled="disabled">Seleccionar Cuenta</button>
                                                                </div>


                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <br />
                                                        <center><button class="btn btn-primary" id='btnGuardarRetenciones'><i class="icon-save"></i> Guardar</button>
                                                            <button class="btn btn-primary" id='btnCancelarRetenciones'><i class="icon-remove-sign"></i> Cancelar</button>
                                                            <!--<button class="btn btn-primary" id='btnImprimirRetenciones'><i class="icon-print-sign"></i> Imprimir Retenciones</button></center>-->

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
                                                                    <option value="Contado">Contado</option>
                                                                    <option value="Credito">Crédito</option>
                                                                    <option value="Cheque">Cheque</option>
                                                                    <option value="TCredito">Tarjeta de Crédito/Debito</option>
                                                                    <option value="Transferencias">Transferencias</option>
                                                                    <option value="CPosfechado">Cheque Posfechado</option>
                                                                    <option value="NOTA_CREDITO">Valor Nota de Crédito</option>
                                                                    <option value="Cupon">Cupon</option>
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


                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="col-md-2">Seleccione Cta Contable: </label>
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
                                            </div><!-- /.tab-pane -->

                                        </div><!-- /.tab-content -->
                                    </div>

                                    <div class="row">
                                        <div class="col-mx-12">
                                            <p>
                                                <button class="btn bg-olive margin" id='btnGuardar'><i class="fa fa-save"></i> Guardar</button>
                                                <!--                                                    <button class="btn bg-olive margin" id='btnModificar'><i class="fa fa-edit"></i> Modificar</button>-->
                                                <button class="btn bg-olive margin" id='btnBuscar'><i class="fa fa-search"></i> Buscar</button>
                                                <button class="btn bg-olive margin" id='btnNuevo'><i class="fa fa-pencil"></i> Nuevo</button>
                                                <button class="btn bg-olive margin" id='btnAnular'><i class="fa fa-remove"></i> Anular</button>
                                                <button class="btn bg-olive margin" id='btnImprimir'><i class="fa fa-print"></i> Imprimir</button>
                                                <button class="btn bg-olive margin" id='btnProforma'>Proformas</button>
                                                <button class="btn bg-olive margin" id='btnMantenimiento' style="display: none">Mantenimiento</button>
                                                <button class="btn bg-olive margin" id='btnAtras'><i class="fa fa-backward"></i> Atras</button>
                                                <button class="btn bg-olive margin" id='btnAdelante'>Adelante <i class="fa fa-forward"></i></button>
                                                <button class="btn bg-olive margin" id='btnGuiaRemision' style="display:none"><i class="fa fa-save"></i> Guardar Guìa Remisiòn</button>
                                                <button class="btn bg-olive margin" id='btnEstados'><i class="fa fa-check"></i> Estados Facturación</button>
                                                <button class="btn bg-olive margin" id='btnEstados2' style="display:none"><i class="fa fa-check"></i> Facturas Autorizadas No Enviadas Al Correo</button>
                                                <button class="btn bg-olive margin" id='btnImprimirGuia' style="display:none"><i class="fa fa-print"></i> Imprimir Guía Remisiòn</button>
                                                <button class="btn bg-olive margin" id='btnEstadosguia' style="display:none"><i class="fa fa-check"></i> Estados Guía Remisiòn</button>
                                                <button style="display: <?php echo $_SESSION["id"] == 1 ? "" : "none" ?>;" class="btn bg-olive margin" id='btnActualizarClave'><i class="fa fa-check"></i> Actualizar Clave</button>
                                            </p>
                                        </div>
                                    </div>

                            </div>
                            </form>
                        </div>
                    </div>

                    <div id="series" title="AGREGAR SERIES">
                        <table cellpadding="2" border="0" style="margin-left: 10px">
                            <tr>
                                <td><label>Series: <font color="red">*</font></label></td>
                                <td>
                                    <div class="ui-widget">
                                        <select name="combobox" id="combobox" class="campo">
                                            <option value=""></option>
                                        </select>
                                    </div>
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
                    <div id="tipo_busqueda" title="TIPO BUSQUEDA">
                        <table cellpadding="2" border="0" style="margin-left: 10px">
                            <tr>
                                <td><label>Buscar por:</label></td>
                                <td>
                                    <select id="tipo_venta_busqueda" name="tipo_venta_busqueda" style="width: 180px">
                                        <option value="FACTURA">FACTURA</option>
                                        <option value="NOTA">NOTA VENTA</option>
                                    </select>
                                </td>
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

                    <div id="productos" title="Búsqueda de Productos" class="">
                        <table id="listp">
                            <tr>
                                <td></td>
                            </tr>
                        </table>
                        <div id="pagerp"></div>
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
                                <div class="form-group col-md-6 no-padding">
                                    <input type="password" name="clave" id="clave" required class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Comentario</label>
                            <div class="form-group col-md-9">
                                <textarea id="anulacionComentario" name="anulacionComentario" class="form-control" required maxlength="50"></textarea>
                            </div>
                        </div>
                        <div class="form-actions" align="center">
                            <button class="btn btn-primary" id='btnAcceder'><i class="icon-ok"></i> Acceder</button>
                            <button class="btn btn-primary" id='btnCancelar'><i class="icon-remove-sign"></i> Cancelar</button>
                        </div>

                        <div id="valor_cambioid" title="CAMBIO">
                            <div class="row">
                                <div class="form-group">

                                    <!--                        <div  id="valor_tarjetaid" class="form-group" > 
                                                                                 <label class="col-md-6" >% de cobro por T. Crédito:</label>
                                                                                <div class="form-group col-md-6 no-padding">                          
                                                                               <input type="text" name="valor_tarjeta"  id="valor_tarjeta" readonly  class="form-control "  value="<?php echo $campo_porsentaje_tarjeta ?>"/>
                                                                               </div> 
                                                                                 </div> 
                                                                                
                                                                                
                                                                                
                                                                                 <div  id="calculo_porcentajeid" class="form-group" > 
                                                                                 <label class="col-md-6" >Resultado Cobro T.Crédito:</label>
                                                                               <div class="form-group col-md-6 no-padding">                          
                                                                               <input type="text" name="calculo_porcentaje"  id="calculo_porcentaje" readonly  class="form-control "  />
                                                                               </div> 
                                                                                 </div> 
                                                                                                       
                                                                                  <div  id="resultado_tar_totalid" class="form-group" > 
                                                                                 <label class="col-md-6" >Cobro total:</label>
                                                                               <div class="form-group col-md-6 no-padding">                          
                                                                               <input type="text" name="resultado_tar_total"  id="resultado_tar_total" readonly  class="form-control "  />
                                                                               </div> 
                                                                                 </div> -->

                                    <div id="valor_reciboid" class="form-group">
                                        <label class="col-md-6">Valor Recibido:</label>
                                        <div class="form-group col-md-6 no-padding">
                                            <input type="text" name="valor_recibo" id="valor_recibo" required class="form-control " />
                                        </div>
                                    </div>

                                    <div id="total_ventaid" class="form-group">
                                        <label class="col-md-6">Total Factura:</label>
                                        <div class="form-group col-md-6 no-padding">
                                            <input type="text" name="total_venta" id="total_venta" readonly class="form-control" />
                                        </div>
                                    </div>


                                    <div id="valor_cambioitemid" class="form-group">
                                        <label class="col-md-6">Cambio:</label>
                                        <div class="form-group col-md-6 no-padding">
                                            <input type="text" name="valor_cambio" id="valor_cambio" readonly class="form-control " />
                                        </div>
                                    </div>

                                </div>
                                <div align="center">
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

                            <div id="buscar_proformas_tecnico" title="BUSCAR PROFORMAS TECNICO">
                                <table id="list6">
                                    <tr>
                                        <td></td>
                                    </tr>
                                </table>
                                <div id="pager6"></div>
                            </div>

                            <div id="buscar_val_nc" title="BUSCAR VALORES DE NOTAS DE CREDITO CLIENTES">
                                <fieldset>
                                    <table id="list22">
                                        <tr>
                                            <td></td>
                                        </tr>
                                    </table>
                                    <div id="pager22"></div>
                                </fieldset>
                            </div>

                        </div><!-- nav-tabs-custom -->
                    </div>

            </div>
        </section>
    </div>
    <div id="dialog_form_cliente">
        <div id="form_cliente">
        </div>
    </div>

    <div id="cargar_valores_retencion">
        <div class="row">
            <div class="col-md-12" style="font-size: 13px;">
                <table style="width: 100%; border-collapse: collapse; border: solid 1px black;" id="tabla_cargar_retenciones">
                </table>
            </div>
        </div>
    </div>

    <?php footer(); ?>
</div>

<script src="../../plugins/jQuery/jQuery-2.1.3.min.js"></script>
<script src="../../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="../../plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="../../plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
<script src="../../plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
<script src="../../plugins/input-mask/jquery.inputmask.numeric.extensions.js" type="text/javascript"></script>
<script src="../../plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<script src="../../plugins/colorpicker/bootstrap-colorpicker.min.js" type="text/javascript"></script>
<script src="../../plugins/timepicker/bootstrap-timepicker.min.js" type="text/javascript"></script>
<script src="../../plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src='../../plugins/fastclick/fastclick.min.js'></script>
<script src="../../dist/js/app.min.js" type="text/javascript"></script>
<script src="../../dist/js/validCampoFranz.js" type="text/javascript"></script>
<script src="../../dist/js/alertify.min.js" type="text/javascript"></script>
<script src="../../dist/js/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>
<script src="../../dist/js/jquery.jqGrid.src.js" type="text/javascript"></script>
<script src="../../dist/js/grid.locale-es.js" type="text/javascript"></script>
<script src="../../plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<script src="factura_venta.js" type="text/javascript"></script>
<script src="../../dist/js/decimales.js" type="text/javascript"></script>
<link href="../../dist/css/style.css" rel="stylesheet" type="text/css" />
<script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>
<script src="../../dist/js/refrescar_session.js" type="text/javascript"></script>
<script src="../../dist/js/validar_identificacion.js"></script>
<script src="subirfactura/subirfacutra.js"></script>


<script>
                                                                    $(document).ready(function () {
                                                                        comprabarNroFactura();
                                                                    });

                                                                    function comprabarNroFactura() {
                                                                        if ($("#tipo_venta").val() == "FACTURA") {
                                                                            if (<?php echo $cont1 ?> > 1) {
                                                                                $("#num_factura")[0].readOnly = true;
                                                                            } else {
                                                                                $("#num_factura")[0].readOnly = false;
                                                                            }
                                                                        } else if ($("#tipo_venta").val() == "NOTA") {
                                                                            if (<?php echo $cont1_nota ?> > 1) {
                                                                                $("#num_factura")[0].readOnly = true;
                                                                            } else {
                                                                                $("#num_factura")[0].readOnly = false;
                                                                            }
                                                                        }
                                                                    }
</script>

</body>

</html>