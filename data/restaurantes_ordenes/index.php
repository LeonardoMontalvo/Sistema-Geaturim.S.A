<?php
session_start();
include '../../procesos/base.php';
include '../menu/app.php';
conectarse();
error_reporting(0);


$consulta7 = pg_query("select * from punto_venta_empresa  left join punto_venta  on punto_venta_empresa.id_punto_venta=punto_venta.id_punto_venta  where  
punto_venta_empresa.id_usuario='$_SESSION[id]' ORDER BY id_punto_venta_empresa DESC LIMIT 1");
while ($row = pg_fetch_row($consulta7)) {
    $campo_punto_venta = $row[6];
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>RESTAURANTES - ORDENES</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css" integrity="sha512-Velp0ebMKjcd9RiCoaHhLXkR1sFoCCWXNp6w4zj1hfMifYB5441C+sKeBl/T/Ka6NjBiRfBBQRaQq65ekYz3UQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="orden.css">
    <style>
        .ui-dialog {
            z-index: 1030 !important;
        }

        .ui-dialog .ui-dialog-title {
            white-space: normal;
        }

        textarea {
            resize: none;
        }

        /* .list-group-item.active{
            background-color:#009688;
            border-color:#000
        } */
    </style>

<body class="skin-blue">
    <div class="wrapper">
        <?php banner_1(); ?>
        <?php menu_lateral_1(); ?>
        <div class="content-wrapper">
            <!-- <section class="content-header">
                    <h1>
                        RESERVACIONES
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Procesos</a></li>
                        <li class="active">Reservaciones</li>
                    </ol>
                </section> -->

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12" style="max-height: 80vh;">
                        <div class="box box-primary">
                            <div class="box-body" style=" background: #CFD8DC; height: 88vh;">
                                <div id="app">
                                    <div style="background-color: #37474F; height: 6vh; display: flex; padding: 0 15px; flex:1">
                                        <div style="color: white; display: flex; align-items: center;">
                                            <span style="font-size: 1.8rem; font-weight: bold; padding-right: 5px;"><i class="fa fa-calendar"></i></span>
                                            <span style="font-size: 1.5rem; font-weight: bold;" v-cloak>{{fechaActual}}</span>
                                            <span style="font-size: 1.8rem; font-weight: bold; padding-left: 10px; padding-right: 5px;"><i class="fa fa-clock-o"></i></span>
                                            <span style="font-size: 1.5rem; font-weight: bold;" v-cloak>{{horaActual}}</span>
                                            <span style="font-size: 1.8rem; font-weight: bold; padding-left: 10px; padding-right: 5px;">P.E:</span>
                                            <span style="font-size: 1.5rem; font-weight: bold;" v-cloak>{{puntoEmision}}</span>
                                            <span style="font-size: 1.8rem; font-weight: bold; padding-left: 10px; padding-right: 5px;">P.V:</span>
                                            <span style="font-size: 1.5rem; font-weight: bold;" v-cloak><?php echo $campo_punto_venta; ?></span>
                                        </div>
                                        <div style="color: white; display: flex; align-items: center; justify-content: flex-end; flex:1">
                                            <span style="font-size: 1.8rem; font-weight: bold; padding-right: 5px;"> <i class="fa fa-user"></i> </span>
                                            <span style="font-size: 1.5rem; font-weight: bold;"><?php echo $_SESSION['nombres'] ?></span>
                                            <span style="padding-left: 10px;">
                                                <div class="dropdown">
                                                    <button style="background: #00000000; border:none;" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                    </button>

                                                    <ul class="dropdown-menu pull-right dropdown_ordenes">
                                                        <li>
                                                            <a @click="openDialogListaOrdenes()" href="#">
                                                                <i class="fa fa-list" aria-hidden="true"></i>
                                                                Lista de ordenes
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <!-- <i class="fa fa-ellipsis-v" aria-hidden="true"></i> -->
                                            </span>
                                        </div>
                                    </div>
                                    <div id="ordenes">
                                        <pantalla-orden :key="keyPantallaOrden" @ir-pagar="onIrPagar($event)"></pantalla-orden>
                                    </div>
                                    <div id="pago" style="display: none;">
                                        <div class="row" style="margin-top: 15px;">
                                            <div class="col-md-12">
                                                <button @click="volverOrden()" class="btn btn-primary btn-lg"><i class="fa fa-chevron-left"></i> VOLVER A ORDEN</button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6" style="text-align: left;">
                                                <h3 style="color:red; font-weight: bold;">{{tipoDocumento=='NOTA'?'NOTA DE VENTA':tipoDocumento}}</h3>
                                            </div>
                                            <div class="col-md-6" style="text-align: right;">
                                                <h3 style="color:red; font-weight: bold;">TOTAL A PAGAR: ${{totalVenta.toFixed(2)}}</h3>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-bottom: 6px;">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon" style="font-size: 1.6rem; font-weight: bold; background-color: #FFCA28;">MESA ORDEN:</i></span>
                                                    <input v-model="mesa" id="nro_mesa" type="text" class="form-control input-lg" placeholder="INGRESE MESA" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                        </div>
                                        <cargar-cliente :key="keyCargarCliente" @select-cliente="cargarCliente($event)"></cargar-cliente>
                                        <pantalla-pago @pagar="onPagar($event)" :cliente="cliente" :total-venta="totalVenta.toFixed(2)"></pantalla-pago>
                                    </div>
                                    <div class="loader" v-if="loading">
                                        <i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
                                        <span>Guardando...</span>
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <div id="dialog_lista_ordenes">
                                        <lista-ordenes :key="keyListaOrdenes" @reimprimir-orden="onReimprimirOrden($event)" @reimprimir-orden-cocina="onReimprimirOrdenCocia($event)"></lista-ordenes>
                                    </div>
                                </div>
                            </div>
                        </div><!-- nav-tabs-custom -->
                    </div>
                </div>
            </section>
        </div>
        <?php //footer();
        ?>
    </div>
    <div id="overlay_pantalla"></div>


    <script src="../../plugins/jQuery/jQuery-2.1.3.min.js"></script>
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
    <script src="../../dist/js/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>
    <script src="../../dist/js/jquery.jqGrid.src.js" type="text/javascript"></script>
    <script src="../../dist/js/grid.locale-es.js" type="text/javascript"></script>
    <script src="../../plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <link href="../../dist/css/style.css" rel="stylesheet" type="text/css" />
    <script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>
    <script type="importmap">
        {
            "imports": {
            "vue": "./vue.esm-browser.js"
            }
            }
        </script>
    <script type="module" src="./vue/main.js"></script>
    <script src="index.js"></script>

    <script type="text/html" id="pantalla_orden">
        <div style="display:flex;">
            <div class="panel_items">
                <div style="height: 5vh; width: 100%;">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-search " aria-hidden="true"></i></span>
                        <input id="buscar_productos" type="text" class="form-control" placeholder="Buscar" style="text-transform: uppercase;">
                        <span id="limpiar_busqueda" class="input-group-addon btn_clear_cliente"> <i class="fa fa-close" aria-hidden="true"></i> </span>
                    </div>
                </div>
                <div style="display: flex;  min-height: 6vh; width: 100%;">
                    <div style="padding-top: 5px; background-color: #B0BEC5;">
                        <div class="btn-group container_categorias" style="margin-bottom: 5px;">
                            <a @click="onClickCategoria($event, 0)" :class="{active:categoriaSeleccionada==0}" class="btn btn-info" href="javascript:void(0)">TODO</a>
                            <a @click="onClickCategoria($event, cate.id_categoria)" v-for="cate of categorias" :class="{active:categoriaSeleccionada==cate.id_categoria}" class="btn btn-info" href="javascript:void(0)">{{cate.nombre_categoria}}</a>
                            <a @click="onClickCategoria($event, -1)" :class="{active:categoriaSeleccionada==-1}" class="btn btn-info" href="javascript:void(0)">SIN CATEGORIA</a>
                        </div>
                    </div>
                </div>
                <div style="display: flex; max-height: 59vh;">
                    <div class="container_items" style="overflow-y: auto;">
                        <!-- <div>
                                <img src="./img/ui-anim_basic_16x16.gif" alt="">
                            </div> -->
                        <!-- <div class="item" v-for="item of productos" @click="onClickItem($event, item)">
                                <div class="imagen" :style="{'background-image':`url(./../productos/fotos_productos/${item.imagen ? item.imagen :'placeholder.png'})`}">
                                </div>
                                <div class="descripcion">
                                    {{item.articulo}}
                                </div>
                                <div class="precio">${{calcularPrecioIva(item.precio).toFixed(2)}}</div>
                            </div> -->
                        <div class="item" v-for="item of productos" @click="onClickItem($event, item)">
                            <div class="imagen" :style="{'background-image':`url(./../productos/fotos_productos/${item.imagen ? item.imagen :'placeholder.png'})`}">
                            </div>
                            <div class="descripcion">
                                {{item.articulo}}
                            </div>
                            <div class="precio">${{calcularPrecioIva(item).toFixed(2)}}</div>
                            <div class="overlay-stock" v-if="!verificarStock(item.inventariable, item.stock, item.cod_producto)">
                                SIN STOCK
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel_items_orden">
                <button style="padding:2px; font-size:1.5rem; font-weight:bold;" class="btn btn-primary btn-sm" @click="llenarTablaProdPromociones()">
                    <i class="fa fa-list"></i>
                    Ver producto de promoción de la orden <span class="badge badge-light" style="font-size:1.3rem">{{this.cantidadProductosPromoOrden||0}}</span>
                </button>
                <div style="height: 48vh;">
                    <table id="lista_items">
                    </table>
                </div>
                <div style="height: 18vh; flex-direction: row; display: flex;  align-items:start;">
                    <div style="flex: 0 0 50%; text-align: left; font-weight: bold; font-size: 3.5rem; color: red; height:100%; display:flex; align-items:center;">
                        <div>
                            TOTAL: $<span id="total_orden">{{totalVenta.toFixed(2)}}</span>
                        </div>
                    </div>
                    <div style="flex: 0 0 50%;   font-weight:bold; font-size:1.2rem; color: black; display:flex; height:100%">
                        <table style="width: 100%;">
                            <tr>
                                <td>TOTAL IVA 12%:</td>
                                <td style="padding-left:5px;">$<span>{{totalTarifa12.toFixed(2)}}</span></td>
                            </tr>
                            <!-- <tr>
                                <td>TOTAL IVA 12 Promo:</td>
                                <td style="padding-left:5px;">$<span>{{totalTarifa12Promo.toFixed(2)}}</span></td>
                            </tr> -->
                            <tr>
                                <td>TOTAL IVA 0%:</td>
                                <td style="padding-left:5px;">$<span>{{totalTarifa0.toFixed(2)}}</span></td>
                            </tr>
                            <!-- <tr>
                                <td>TOTAL IVA 0 Promo:</td>
                                <td style="padding-left:5px;">$<span>{{totalTarifa0Promo.toFixed(2)}}</span></td>
                            </tr> -->
                            <tr>
                                <td>TOTAL DESCUENTO:</td>
                                <td style="padding-left:5px;">$<span>{{totalDescuento.toFixed(2)}}</span></td>
                            </tr>
                            <tr>
                                <td>SUBTOTAL:</td>
                                <td style="padding-left:5px;">$<span>{{subtotalVenta.toFixed(2)}}</span></td>
                            </tr>
                            <tr>
                                <td>IVA:</td>
                                <td style="padding-left:5px;">$<span>{{totalIva.toFixed(2)}}</span></td>
                            </tr>
                        </table>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12" style="display: flex; aling-items:flex-start;">
                        <div style="display: flex; width: 100%;">
                            <button @click="irPagar('FACTURA')" class="btn btn-primary" style="width: 50%; background:#3d9970;"><i class="fa fa-file-text "></i> <br>FACTURA</button>
                            <button @click="irPagar('NOTA')" class="btn btn-primary" style="width: 50%; background:#3d9970;"><i class="fa fa-file "></i> <br>NOTA DE VENTA</button>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button id="btn_anular_orden" class="btn btn-danger btn-lg btn-block"><i class="fa fa-times "></i> ANULAR ORDEN</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="dialog_cantidad">
            <form action="for_d_cantidad">
                <div class="row">
                    <div class="col-md-12">
                        <div style="display: flex; align-items: center;">
                            <label for="" style="flex-basis: 250px; text-align: right; margin-right: 15px;">NUEVA CANTIDAD:</label>
                            <input id="po_diag_cantidad" style="background-color: #D7CCC8; color: black;" class="form-control" type="number">
                        </div>
                    </div>
                </div>
            </form>
            <div class="row">
                <div style="display: flex; justify-content: center; padding: 15px 15px">
                    <button id="po_diag_aceptar" type="button" class="btn btn-success btn-block"><i class="fa fa-check"></i> Aceptar</button>
                </div>
            </div>
        </div>

        <div id="dialog_caract_prod" class="modal" role="dialog">
            <div class="modal-dialog modal-sm">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
                        <div style="display:flex; justify-content: space-between;">
                            <div>
                                <h4 class="modal-title" v-if="productoSeleccionado">{{productoSeleccionado.articulo}}</h4>
                            </div>
                            <button type="button" data-dismiss="modal" class="btn btn-default btn-sm" style="align-self:start;">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <span style="font-size:2rem; font-weight:bold;">Seleccione características:</span>
                        <div class="row">
                            <div class="col-md-12" style="height:325px; overflow-y:scroll;">
                                <ul class="list-group">
                                    <li class="list-group-item" v-for=" (item,i) in caracteristicas">

                                        <label style="width:100%" :for="item.cod_prod+'_crt'+i"><input :id="item.cod_prod+'_crt'+i" @change="onChangeCaracteristica($event,item.id_caracteristica)" type="checkbox">
                                            {{item.nombre}}</label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button @click="addCaracteristicasProducto()" class="btn btn-success btn-block">Aceptar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="dialog_prmo_prod" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
                        <div style="display:flex; justify-content: space-between;">
                            <div>
                                <h4 class="modal-title">Productos De Promoción En La Orden</h4>
                            </div>
                            <button type="button" data-dismiss="modal" class="btn btn-default btn-sm" style="align-self:start;">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div :style="{display:(productosPromocion.length>0?'':'none')}">
                            <table id="lista_promo_prods">
                            </table>
                        </div>
                        <div :style="{display:(productosPromocion.length==0?'':'none')}" style="font-size:2rem; text-align:center;">
                            LA ORDEN NO TIENE PRODUCTOS DE PROMOCIÓN
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="text/html" id="cargar_cliente">
        <div class="row">
            <div class="col-md-12">
                <div class="input-group">
                    <span class="input-group-addon" style="font-size: 2rem;"><i class="fa fa-users" aria-hidden="true"></i></span>
                    <input id="buscar_clientes" type="text" class="form-control input-lg" placeholder="Buscar" style="text-transform: uppercase;">

                    <span class="input-group-addon btn_clear_cliente" @click="limpiarCliente($event);"><i class="fa fa-times"></i></span>
                    <span id="nuevo_cliente" class="input-group-addon" style="font-size: 2rem; cursor: pointer; background:#388E3C; color:#000"><i class="fa fa-user-plus" aria-hidden="true"></i> </span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">
                    <div>
                        <label style="font-size:1.6rem">RUC:</label> <span style="font-size:1.6rem">{{rucCliente}}</span>
                    </div>
                    <div>
                        <label style="font-size:1.6rem">CORREO E.:</label> <span style="font-size:1.6rem">{{correoCliente}}</span>
                    </div>
                    <div>
                        <label style="font-size:1.6rem">TELÉFONO:</label> <span style="font-size:1.6rem">{{telCliente}}</span>
                    </div>
                    <div>
                        <label style="font-size:1.6rem">DIRECCIÓN:</label> <span style="font-size:1.6rem">{{dirCliente}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div id="dialog_form_cliente">
            <div id="form_cliente">
            </div>
        </div>
    </script>

    <script type="text/html" id="pantalla_pago">
        <div>
            <div class="row">
                <div class="col-md-12">
                    <h4 style="font-weight: bold;">FORMAS DE PAGO</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <button id="btn_fp_contado" type="button" class="btn btn-primary btn-lg" style="width: 100%; background-color: #4CAF50;">
                            <i class="fa fa-money"></i> CONTADO
                        </button>
                        <button id="btn_fp_tarjeta" type="button" class="btn btn-primary btn-lg" style="width: 100%; background-color: #8BC34A;">
                            <i class="fa fa-credit-card "></i> TARJETA
                        </button>
                        <button id="btn_fp_transferencia" type="button" class="btn btn-primary btn-lg" style="width: 100%; background-color: #009688;">
                            <i class="fa  fa-exchange"></i> TRANSFERENCIA
                        </button>
                        <button id="btn_fp_mixto" type="button" class="btn btn-primary btn-lg" style="width: 100%; background-color: #FF5722;">
                            <i class="fa  fa-random"></i> MIXTO
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div id="dialog_fp">
            <form id="form_fp">
                <div class="row">
                    <div class="col-md-12">
                        <div style="display: flex; align-items: center;">
                            <label for="" style="flex-basis: 250px; text-align: right; margin-right: 15px;">TOTAL A PAGAR:</label>
                            <div class="input-group" style="width: 100%;">
                                <input :value="totalVenta" style="background-color: #D7CCC8; color: black;" readonly class="form-control" type="number">
                                <span class="input-group-addon"> <i class="fa fa-usd"></i> </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px;">
                    <div class="col-md-12">
                        <div style="display: flex; align-items: center;">
                            <label for="" style="flex-basis: 250px; text-align: right;  margin-right: 15px;">VALOR RECIBIDO:</label>
                            <div class="input-group" style="width: 100%;">
                                <input @keypress.enter=" onEnterValorRecibido($event)" :min="totalVenta" required step="any" id="valor_recibido_fp" v-model="valorFormaPago" placeholder="0.00" style="color: black;" class="form-control" type="number">
                                <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px;">
                    <div class="col-md-12">
                        <div style="display: flex; align-items: center;">
                            <label for="" style="flex-basis: 250px; text-align: right;  margin-right: 15px;">CAMBIO:</label>
                            <div class="input-group" style="width: 100%;">
                                <input readonly :value="cambio" placeholder="0.00" style="background-color: #D7CCC8; color: black;" class="form-control" type="number">
                                <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px;" id="nro_documento_fp_div">
                    <div class="col-md-12">
                        <div style="display: flex; align-items: center;">
                            <label for="" style="flex-basis: 250px; text-align: right;  margin-right: 15px;">NRO. DOCUMENTO:</label>
                            <input @keypress.enter="onEnterNroDocumento($event)" v-model="nroDocTransferencia" id="nro_documento_fp" placeholder="0.00" style="color: black;" class="form-control" type="text">
                        </div>
                    </div>
                </div>
            </form>
            <div class="row">
                <div style="display: flex; justify-content: center; padding: 15px 15px">
                    <button type="button" @click="onAceptarFP($event)" class="btn btn-success btn-block"><i class="fa fa-check"></i> Aceptar</button>
                </div>
            </div>
        </div>
        <div id="dialog_fp_mixto">
            <form id="form_mixto_fp">
                <div class="row">
                    <div class="col-md-12">
                        <div style="display: flex; align-items: center;">
                            <label for="" style="flex-basis: 250px; text-align: right; margin-right: 15px;">TOTAL A PAGAR:</label>
                            <div class="input-group" style="width: 100%;">
                                <input :value="totalVenta" style="background-color: #D7CCC8; color: black;" readonly class="form-control" type="number">
                                <span class="input-group-addon"> <i class="fa fa-usd"></i> </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px;">
                    <div class="col-md-12">
                        <div style="display: flex; align-items: center;">
                            <label for="" style="flex-basis: 250px; text-align: right; margin-right: 15px;">CONTADO:</label>
                            <div class="input-group" style="width: 100%;">
                                <input step="any" @keypress.enter="onEnterValorContado" id="valor_contado_fp" v-model="valorMixtoContado" placeholder="0.00" style="color: black;" class="form-control" type="number">
                                <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px;">
                    <div class="col-md-12">
                        <div style="display: flex; align-items: center;">
                            <label for="" style="flex-basis: 250px; text-align: right; margin-right: 15px;">TARJETA:</label>
                            <div class="input-group" style="width: 100%;">
                                <input step="any" @keypress.enter="onEnterValorTarjeta($event)" id="valor_tarjeta_fp" v-model="valorMixtoTarjeta" placeholder="0.00" style="color: black;" class="form-control" type="number">
                                <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px;">
                    <div class="col-md-12">
                        <div style="display: flex; align-items: center;">
                            <label for="" style="flex-basis: 250px; text-align: right; margin-right: 15px;">TRANSFERENCIA:</label>
                            <div class="input-group" style="width: 100%;">
                                <input step="any" @input="onInputValorTransferencia($event)" @keypress.enter="onEnterValorTransferencia($event)" id="valor_transferencia_fp" v-model="valorMixtoTransferencia" placeholder="0.00" style="color: black;" class="form-control" type="number">
                                <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px; display: none;" id="nro_documento_mixto_fp_div">
                    <div class="col-md-12">
                        <div style="display: flex; align-items: center;">
                            <label for="" style="flex-basis: 250px; text-align: right;  margin-right: 15px;">NRO. DOC. TRANSF.:</label>
                            <input @keypress.enter="onEnterNroDocumentoMixto($event)" v-model="nroDocTransferenciaMixto" id="nro_documento_mixto_fp" placeholder="0.00" style="color: black;" class="form-control" type="text">
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px;">
                    <div class="col-md-12">
                        <div style="display: flex; align-items: center;">
                            <label for="" style="flex-basis: 250px; text-align: right; margin-right: 15px;">CREDITO:</label>
                            <div class="input-group" style="width: 100%;">
                                <input step="any" @input="onInputValorCredito($event)" @keypress.enter="onEnterValorCredito($event)" id="valor_credito_fp" v-model="valorMixtoCredito" placeholder="0.00" style="color: black;" class="form-control" type="number">
                                <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px; display: none;" id="fecha_vence_mixto_fp_div">
                    <div class="col-md-12">
                        <div style="display: flex; align-items: center;">
                            <label for="" style="flex-basis: 250px; text-align: right;  margin-right: 15px;">VENCIMIENTO CRÉDITO:</label>
                            <input @keypress.enter="onEnterNroDocumentoMixto($event)" v-model="fechaVenceCreditoMixto" id="fecha_vence_mixto_fp" placeholder="0.00" style="color: black;" class="form-control" type="date">
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px;">
                    <div class="col-md-12">
                        <div style="display: flex; align-items: center;">
                            <label for="" style="flex-basis: 250px; text-align: right; margin-right: 15px;">RESTANTE:</label>
                            <div class="input-group" style="width: 100%;">
                                <input readonly :value="restante" placeholder="0.00" style="background-color: #D7CCC8; color: black;" class="form-control" type="number">
                                <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row">
                <div style="display: flex; justify-content: center; padding: 15px 15px">
                    <button type="button" @click="onAceptarFPMixto($event)" class="btn btn-success btn-block"><i class="fa fa-check"></i> Aceptar</button>
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
    </script>

    <script type="text/html" id="lista_ordenes">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Fecha Inicio:</label>
                    <input v-model="fechaInicio" type="date" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Fecha Fin:</label>
                    <input v-model="fechaFin" type="date" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="" style="color:#fff">label</label>
                    <button @click="reloadGrid()" type="button" class="btn btn-success btn-block"><i class="fa fa-search"></i> Buscar</button>
                </div>
            </div>
        </div>
        <div>
            <table id="lo_lista"></table>
            <div id="lo_pager"></div>
        </div>
    </script>
</body>

</html>