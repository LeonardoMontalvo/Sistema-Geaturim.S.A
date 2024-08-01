<?php
session_start();
include '../../procesos/base.php';
include('../menu/app.php');
include '../../procesos/configuracion.php';
$consulta6 = pg_query("select * from proveedores order by id_proveedor desc");
while ($row = pg_fetch_row($consulta6)) {

    $campo_nombre_proveedor = $row[0];
}
$conf = new Configuracion();
$defecto_iva = $conf->getParametroEmpresa("defecto_iva");


$consulta10 = pg_query("select * from parametros");
while ($row = pg_fetch_row($consulta10)) {
    $campo_valor_iva = $row[2];
}
$consulta1 = pg_query("select nombre_taimpuesto from tarifa_impuesto where id_taimpuesto=2 and id_timpu= 1 order by id_taimpuesto desc");
while ($row = pg_fetch_row($consulta1)) {

    $campo_nombre_tarifa = $row[0];
}

$consulta2 = pg_query("select * from tipo_impuesto  order by id_timpu desc");
while ($row = pg_fetch_row($consulta2)) {

    $campo_nombre_iva = $row[0];
}
?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>PRODUCTOS</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../../font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="../../fontawesome-free-5.12.1/css/all.css" rel="stylesheet" type="text/css" />
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

        <script type="text/javascript" src="../../dist/js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="../../dist/js/bootstrap.js"></script>
        <script type="text/javascript" src="../../dist/js/jquery-loader.js"></script>
        <script type="text/javascript" src="../../dist/js/jquery-1.10.2.js"></script>
        <script type="text/javascript" src="../../dist/js/jquery-ui-1.10.4.custom.min.js"></script>
        <script type="text/javascript" src="../../dist/js/grid.locale-es.js"></script>
        <script type="text/javascript" src="../../dist/js/jquery.jqGrid.src.js"></script>
        <!--<script type="text/javascript" src="../../dist/js/buttons.js"></script>-->
        <script type="text/javascript" src="../../dist/js/validCampoFranz.js"></script>
        <script type="text/javascript" src="../../dist/js/datosUser.js"></script>
        <script type="text/javascript" src="../../dist/js/archivo_excel.js"></script>
        <script type="text/javascript" src="../../dist/js/ventana_reporte.js"></script>
        <script type="text/javascript" src="../../dist/js/guidely/guidely.min.js"></script>
        <script type="text/javascript" src="../../dist/js/easing.js"></script>
        <script type="text/javascript" src="../../dist/js/jquery.ui.totop.js"></script>
        <script type="text/javascript" src="../../dist/js/jquery.smartmenus.js"></script>
        <script type="text/javascript" src="../../dist/js/alertify.min.js"></script>
    </head>

    <body class="skin-blue">
        <div class="wrapper">
            <?php banner_1(); ?>
            <?php menu_lateral_1(); ?>
            <div class="content-wrapper">
                <section class="content-header">
                    <h1>
                        Registro Productos
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Ingresos</a></li>
                        <li class="active">Productos</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab_1" data-toggle="tab">Generales</a></li>
                                    <li><a href="#tab_editar_productos" data-toggle="tab">Editar Lista Productos</a></li>
                                    <li><a href="#tab_2" data-toggle="tab">Adicionales</a></li>

                                    <!--<li><a href="#tab_3" data-toggle="tab">Cargar Productos</a></li>-->
                                    <li><a href="../productos/archivosExcel.php" target="_blank"><i class="fa "></i>Cargar Productos</a></li>
                                    <li><a href="#tab_4" data-toggle="tab">Promociones</a></li>
                                    <li><a href="#tab_5" data-toggle="tab">Características</a></li>
                                    <li><a href="#tab_6" data-toggle="tab">Descuentos</a></li>
                                    <li><a href="#tab_33" data-toggle="tab">Unidad Medida</a></li>
                                    <li><a href="#tab_333" data-toggle="tab">Editar Pvp en Factura Venta</a></li>

                                    <!-- <li><a href="#tab_5" data-toggle="tab">Promociones</a></li> -->
                                </ul>
                                <div class="box-body">
                                    <div class="row">
                                        <form id="productos_form" name="productos_form" novalidate>
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab_1">

                                                    <div class="row" style="margin-bottom: 18px; border-bottom:2px solid; padding-bottom:15px;">
                                                        <div class="col-md-12">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><b><i class="fa fa-search"></i> Buscar Artículo :</b></span>
                                                                <input style="border: 1px solid;" id="input_buscar_articulo_nombre" class="form-control" type="text" placeholder="INGRESE NOMBRE O CÓDIGO DE BARRAS DEL ARTÍCULO">
                                                                <input type="hidden" id="input_buscar_articulo_nombre_id" class="form-control" type="text">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-mx-12">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Código Producto: <font color="red">*</font></label>
                                                                <input type="text" name="cod_prod" id="cod_prod" placeholder="El código debe ser único" class="form-control" />
                                                                <input type="hidden" name="cod_productos" id="cod_productos" readonly class="form-control">
                                                                <input type="text" name="cod_barras2" id="cod_barras2" required placeholder="El código debe ser único" class="form-control" style="display:none" />
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Nombre Artículo: <font color="red">*</font></label>
                                                                <input type="text" name="nombre_art" id="nombre_art" placeholder="Usb 0000x" class="form-control" />
                                                            </div>
                                                            <div class="col-mx-8">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>PVP Minorista Sin Iva: <font color="red">*</font></label>
                                                                        <div class="input-group">
                                                                            <div class="input-group-addon">
                                                                                <i class="fa fa-usd"></i>
                                                                            </div>
                                                                            <input type="text" name="precio_minorista" id="precio_minorista" placeholder="0.0000" class="form-control" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>PVP Minorista final: </label>
                                                                        <div class="input-group">
                                                                            <div class="input-group-addon">
                                                                                <i class="fa fa-usd"></i>
                                                                            </div>
                                                                            <input type="text" name="precio_minorista_final" id="precio_minorista_final" placeholder="0.0000" class="form-control" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <div class="form-group">
                                                                <label>Utilidad Minorista:</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-addon">
                                                                        <i class="fas fa-percent"></i>
                                                                    </div>
                                                                    <input type="text" name="utilidad_minorista" id="utilidad_minorista" placeholder="0.00" class="form-control" />
                                                                </div>
                                                            </div>

                                                            <!-- <label>Familia:</label> -->
                                                            <label>Categoría:</label>
                                                            <div class="input-group">
                                                                <input type="text" name="categoria" id="categoria" placeholder="Buscar....." required class="form-control" value="" />
                                                                <!-- <input type="hidden" name="id_categoria"  id="id_categoria" value="1" required class="form-control" /> -->
                                                                <input type="hidden" name="id_categoria" id="id_categoria" required class="form-control" />
                                                                <span class="input-group-btn">
                                                                    <button class="btn btn-primary" type="button" id="btnCategoria">Agregar</button>
                                                                </span>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Descuento:</label>
                                                                <input type="number" name="descuento" id="descuento" value="0" min="0" class="form-control" />
                                                            </div>

                                                            <!--                                                            <label>Aplicación: </label>
                                                                                                                                            <div class="input-group">-->
                                                            <input type="hidden" name="aplicacion" id="aplicacion" placeholder="Buscar....." required class="form-control" value="" />
                                                            <!-- <input type="hidden" name="id_aplicacion"  id="id_aplicacion" value="1" required class="form-control" /> -->
                                                            <input type="hidden" name="id_aplicacion" id="id_aplicacion" required class="form-control" />
                                                            <!--                                                                <span class="input-group-btn">
                                                                                    <button class="btn btn-primary" type="button" id="btnAplicacion">Agregar</button>
                                                                                </span>
                                                                            </div>-->

                                                            <div class="form-group">
                                                                <label>Cantidad para Descuento: </label>
                                                                <input type="number" name="cantidad_descuento" id="cantidad_descuento" class="form-control" value="0" />
                                                            </div>
                                                            <div class="form-group">
                                                                <label>BIENES/SERVICIOS.</label>
                                                                <select class="form-control" name="bien_servicio" id="bien_servicio">
                                                                    <option value="B" selected="">Bienes</option>
                                                                    <option value="S">Servicio</option>
                                                                </select>
                                                            </div>

                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Código Barras:<font color="red">*</font></label>
                                                                <input type="text" style="text-transform: uppercase" name="cod_barras" id="cod_barras" placeholder="El código debe ser único" class="form-control" />
                                                                <input type="text" name="cod_barras1" id="cod_barras1" required placeholder="El código debe ser único" class="form-control" style="display:none" />
                                                            </div>

                                                       
                                                            
                                                            
                                                              <div class="col-mx-8">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Precio Compra Sin Iva: <font color="red">*</font></label>
                                                                        <div class="input-group">
                                                                            <div class="input-group-addon">
                                                                                <i class="fa fa-usd"></i>
                                                                            </div>
                                                                            <input type="text" name="precio_compra" id="precio_compra" placeholder="0.0000" class="form-control" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Precio Compra final: </label>
                                                                        <div class="input-group">
                                                                            <div class="input-group-addon">
                                                                                <i class="fa fa-usd"></i>
                                                                            </div>
                                                                            <input type="text" name="precio_compra_final" id="precio_compra_final" placeholder="0.0000" class="form-control" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-mx-8">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>PVP Mayorista Sin Iva: <font color="red">*</font></label>
                                                                        <div class="input-group">
                                                                            <div class="input-group-addon">
                                                                                <i class="fa fa-usd"></i>
                                                                            </div>
                                                                            <input type="text" name="precio_mayorista" id="precio_mayorista" class="form-control" placeholder="0.0000" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>PVP Mayorista final: </label>
                                                                        <div class="input-group">
                                                                            <div class="input-group-addon">
                                                                                <i class="fa fa-usd"></i>
                                                                            </div>
                                                                            <input type="text" name="precio_mayorista_final" id="precio_mayorista_final" class="form-control" placeholder="0.0000" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Utilidad Mayorista:</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-addon">
                                                                        <i class="fas fa-percent"></i>
                                                                    </div>
                                                                    <input type="text" name="utilidad_mayorista" id="utilidad_mayorista" placeholder="0.00" class="form-control" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Cantidad para Mayorista: </label>
                                                                <input type="number" name="cantidad_mayorista" id="cantidad_mayorista" value="0" class="form-control" value="0" />
                                                            </div>



                                                            <label>Marca: </label>
                                                            <div class="input-group">
                                                                <input type="text" name="marca" id="marca" placeholder="Buscar....." required class="form-control" value="" />
                                                                <!-- <input type="hidden" name="id_marca"  id="id_marca" value="1" required class="form-control" /> -->
                                                                <input type="hidden" name="id_marca" id="id_marca" required class="form-control" />
                                                                <span class="input-group-btn">
                                                                    <button class="btn btn-primary" type="button" id="btnMarcas">Agregar</button>
                                                                </span>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Cantidad para Promoción:</label>
                                                                <input type="text" name="stock" id="stock" min="0" placeholder="0.00" class="form-control" />
                                                            </div>

                                                            <!--                                                            <label> Nombre Genérico: </label>
                                                                                                                                            <div class="input-group">-->
                                                            <input type="hidden" name="modelo" id="modelo" placeholder="Buscar....." required class="form-control" value="" />
                                                            <!-- <input type="hidden" name="id_modelo"  id="id_modelo" value="1" required class="form-control" /> -->
                                                            <input type="hidden" name="id_modelo" id="id_modelo" required class="form-control" />
                                                            <!--                                                                <span class="input-group-btn">
                                                                                    <button class="btn btn-primary" type="button" id="btnGenerico">Agregar</button>
                                                                                </span>
                                                                            </div>-->

                                                            <div class="form-group">
                                                                <label>Cuenta Contable: <font color="red">*</font></label>
                                                                <input type="text" name="ccontable" id="ccontable" placeholder="Buscar...." required class="form-control" disabled />
                                                                <input type="hidden" name="idcontable" id="idcontable" />
                                                                <button class="btn btn-default" id="btnCuenta1" name="btnCuenta1" style="display:none"></button>
                                                                <button class="btn btn-default" id="btnCuenta" name="btnCuenta">Seleccionar Cuenta</button>
                                                            </div>
                                                            <input type="hidden" name="valor_iva_pro" id="valor_iva_pro" readonly class="form-control " value="<?php echo $campo_valor_iva ?>" />
                                                       <input type="hidden" name="valor_iva" id="valor_iva" readonly class="form-control "  />
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Stock Mínimo:<font color="red">*</font></label>
                                                                <input type="number" name="minimo" id="minimo" value="1" class="form-control" min="0" />
                                                                <input type="text" name="cod_barras3" id="cod_barras3" required placeholder="El código debe ser único" class="form-control" style="display:none" />
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Stock Máximo: <font color="red">*</font></label>
                                                                <input type="number" name="maximo" id="maximo" value="1" min="0" class="form-control" />
                                                            </div>

                                                            <div class="form-group">
                                                                <label>PVP Negocio:</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-usd"></i>
                                                                    </div>
                                                                    <input type="text" name="precio_negocio" id="precio_negocio" placeholder="0.0000" class="form-control" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Utilidad Negocio:</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-addon">
                                                                        <i class="fas fa-percent"></i>
                                                                    </div>
                                                                    <input type="text" name="utilidad_negocio" id="utilidad_negocio" placeholder="0.00" class="form-control" />
                                                                </div>
                                                            </div>

                                                            <div class="form-group" style="display:none">
                                                                <label>Fecha Creación:<font color="red">*</font></label>
                                                                <input type="text" name="fecha_creacion" id="fecha_creacion" class="form-control" readonly />
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Cantidad para Negocio: </label>
                                                                <input type="number" name="cantidad_negocio" id="cantidad_negocio" value="0" class="form-control" value="0" />
                                                            </div>



                                                            <div class="form-group">
                                                                <label> Precio Venta Contiene Iva (SI/15%||NO/0%)::</label>
                                                                <select class="form-control" name="iva" id="iva">
                                                                    <?php
                                                                    $consultaimpu = pg_query("select * from tipo_impuesto where id_timpu=1 ORDER BY id_timpu  ASC");
                                                                    while ($row = pg_fetch_row($consultaimpu)) {
                                                                        echo "<option selected id='optimpu_$row[0]'  value=$row[0]>$row[1]</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <select class="form-control" name="tarifa" id="tarifa">
                                                                    <?php
                                                                    $consultatarifa = pg_query("select * from tarifa_impuesto where estado='Activo' ORDER BY id_taimpuesto  ASC");
                                                                    while ($row = pg_fetch_assoc($consultatarifa)) {
                                                                        $opt = "<option data-valor='$row[valor]' value='$row[id_taimpuesto]'>$row[nombre_taimpuesto]</option>";
                                                                        if (trim($defecto_iva) == "No" && $row["id_taimpuesto"] == 1) {
                                                                            $opt = "<option data-valor='$row[valor]' selected value='$row[id_taimpuesto]'>$row[nombre_taimpuesto]</option>";
                                                                        } else if (trim($defecto_iva) == "Si" && $row["id_taimpuesto"] == 6) {
                                                                            $opt = "<option data-valor='$row[valor]' selected value='$row[id_taimpuesto]'>$row[nombre_taimpuesto]</option>";
                                                                        }
                                                                        echo $opt;
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <!--<input type="hidden" name="series" id="series" placeholder="buscar..." value="No" class="form-control" />-->
                                                                                                                       <div class="form-group">
                                                                                <label>Añadir Fila en Venta:</label>
                                                                                <select class="form-control" name="series" id="series">
                                                                                    <option value="Si">Si</option>
                                                                                    <option value="No" selected>No</option>
                                                                                </select>
                                                                            </div>


                                                            <label>Proveedor: </label>
                                                            <div class="input-group">
                                                                <select class="form-control" name="proveedor" id="proveedor">
                                                                    <!-- <option   value="<?php //echo $campo_nombre_proveedor               
                                                                    ?>" >PROVEEDOR1 </option> -->
                                                                    <?php
                                                                    $consultapro = pg_query("select * from proveedores ");
                                                                    while ($row = pg_fetch_row($consultapro)) {
                                                                        echo "<option id=$row[0] value=$row[0]>$row[3]</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <span class="input-group-btn">
                                                                    <button class="btn btn-primary" id='btnActualizar'>Actualizar</button>
                                                                    <input type='button' class="btn btn-primary" value='Proveedor' onclick="window.open('../proveedores/index.php', 'width=800,height=600');" />
                                                            </div>

                                                            <!--                                                            <div class="form-group">
                                                                                                                                                <label>Incluye Iva (SI/15%||NO/0%):</label>
                                                                                                                                                <select class="form-control" name="incluye" id="incluye">
                                                                                                                                                    <option value="Si" >Si</option>
                                                                                                                                                    <option value="No" selected>No</option>
                                                                                                                                                </select>
                                                                                                                                            </div>-->

                                                            <div class="form-group">
                                                                <label>Inventariable:</label>
                                                                <select class="form-control" name="inventario" id="inventario">
                                                                    <option id="inven_si" value="Si" selected>Si</option>
                                                                    <option id="inven_no" value="No">No</option>
                                                                </select>
                                                            </div>
                                                            <br>
                                                            <br>

                                                            <!--                                <div class="form-group">
                                                                                                                  <label>Bodegas: <font color="red">*</font></label>
                                                                                                                  <select class="form-control" name="bodegas" id="bodegas">
                                                            <?php
                                                            /* $consulta = pg_query("select * from bodegas order by id_bodega asc");
                                                              while ($row = pg_fetch_row($consulta)) {
                                                              echo "<option id=$row[0] value=$row[0]>$row[1]</option>";
                                                              } */
                                                            ?>     
                                                                                                                  </select>
                                                                                                                </div>      -->
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-mx-12">
                                                            <p>
                                                                <button class="btn bg-olive margin" id='btnGuardar'><i class="fa fa-save"></i> Guardar</button>
                                                                <button class="btn bg-olive margin" id='btnModificar'><i class="fa fa-edit"></i> Modificar</button>
                                                                <button class="btn bg-olive margin" id='btnEliminar'><i class="fa fa-remove"></i> Eliminar</button>
                                                                <button class="btn bg-olive margin" id='btnActivar'><i class="fa fa-check"></i> Activar</button>
                                                                <button class="btn bg-olive margin" id='btnBuscar'><i class="fa fa-search"></i> Buscar</button>
                                                                <button class="btn bg-olive margin" id='btnNuevo'><i class="fa fa-pencil"></i> Nuevo</button>

                                                                <button style="display: <?php echo $_SESSION["id"] == 1 ? "" : "none" ?>;" class="btn bg-olive margin" id='btnstock'><i class="fa fa-check"></i> ACTUALIZAR STOCK</button>

                                                                <button style="display: <?php echo $_SESSION["id"] == 1 ? "" : "none" ?>;" class="btn bg-olive margin" id='btnkardex'><i class="fa fa-check"></i> INSERT KARDEX</button>

                                                            </p>
                                                        </div>
                                                    </div>
                                                </div><!-- /.tab-pane -->
                                                  <div class="tab-pane" id="tab_editar_productos" style="height: 854px">
                                                      <div class="row" style="margin-bottom: 18px; border-bottom:2px solid; padding-bottom:15px;">
                                                        <div class="col-md-12">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><b><i class="fa fa-search"></i> Buscar Artículo :</b></span>
                                                                <input style="border: 1px solid;" id="input_buscar_articulo_nombre_lista" class="form-control" type="text" placeholder="INGRESE NOMBRE O CÓDIGO DE BARRAS DEL ARTÍCULO">
                                                                <input type="hidden" id="input_buscar_articulo_nombre_id_lista" class="form-control" type="text">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <fieldset>
                                                        <table id="listproductos">
                                                            <tr>
                                                                <td></td>
                                                            </tr>
                                                        </table>
                                                        <div id="pager7"></div>
                                                    </fieldset>
                                                </div>
                                                <div class="tab-pane" id="tab_2" style="height: 854px">
                                                    <div class="col-mx-12">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="archivo">Imagen</label>
                                                                <input type="file" name="archivo" id="archivo" onchange='Test.UpdatePreview(this)' accept="image/*">
                                                            </div>

                                                            <div class="form-group">
                                                                <div style="width: 200px; height: 250px;" align="center" title="LOGO">
                                                                    <img id="foto" name="foto" style="width: 100%; height: 100%" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">

                                                        </div>
                                                    </div>
                                                </div><!-- /.tab-pane -->

                                                <div class="tab-pane" id="tab_3" style="height: 854px">
                                                    <div class="widget ">
                                                        <div class="widget-header">
                                                            <i class="icon-upload"></i>
                                                            <h3>ARCHIVOS EXCEL</h3>
                                                        </div> <!-- /widget-header -->

                                                        <div class="widget-content">
                                                            <div class="alert alert-info">
                                                                <h4>Recomendaciones</h4>
                                                                <br />
                                                                <strong>Poner tipos numéricos y texto en las celdas que corresponde caso contrario saldra error de sintaxis.</strong>
                                                                <br />
                                                                <strong>Tener en cuenta de no repetir los articulos.</strong>
                                                            </div>

                                                            <div class="tabbable" id="centro">
                                                                <form id="formulario_excel" name="formulario_excel" method="post" class="form">
                                                                    <fieldset>
                                                                        <table cellpadding="2" border="0" style="margin-left: 10px;">
                                                                            <tr>
                                                                                <td><label for="archivo_excel" style="width: 20%">Seleccione: </label></td>
                                                                                <td><input type="file" name="archivo_excel" id="archivo_excel" class="campo" readonly style="width: 500px" /></td>
                                                                                <td><button class="btn btn-primary" id='btnGuardarCargar'><i class="icon-save"></i> Guardar y Cargar</button></td>
                                                                            </tr>
                                                                        </table>
                                                                    </fieldset>
                                                                </form>
                                                            </div>
                                                            <div style="width:100%;height:300px;border:solid 0px;border-color:rgb(204, 201, 201);overflow:scroll;">
                                                                <table style="width:100%;" class="table table-bordered table-hover table-condensed" id="tabla_excel">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width:20%;">Codigo</th>
                                                                            <th style="width:40%;">Articulo</th>
                                                                            <th style="width:35%;">Estado</th>
                                                                            <th style="width:5%;"></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                        </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- /.tab-pane -->
                                                <div class="tab-pane" id="tab_4" style="height: 854px">


                                                    <div class="box-body">
                                                        <div class="rows">
                                                            <div class="col-mx-12">
                                                                <form id="clientes_form" name="clientes_form" method="post">

                                                                    <div id="estado"></div>


                                                            </div>


                                                            <div class="row">
                                                                <div class="col-mx-12">

                                                                    <div class="col-md-2">
                                                                        <div class="form-group">
                                                                            <label>Cod Barras Promo.<font color="red">*</font></label>
                                                                            <input type="text" name="promocion_cod_barras" id="promocion_cod_barras" placeholder="buscar..." class="form-control" />

                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>Codigo Producto Promo<font color="red">*</font></label>
                                                                            <input type="text" name="promocion_codigo" id="promocion_codigo" placeholder="buscar..." class="form-control" />

                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>Nombre Producto Promo<font color="red">*</font></label>
                                                                            <input type="text" name="promocion_pro" id="promocion_pro" placeholder="buscar..." class="form-control" />
                                                                            <input type="hidden" name="id_promocion_pro" id="id_promocion_pro" readonly class="form-control" />
                                                                            <input type="hidden" name="cod_producto_p" id="cod_producto_p" readonly class="form-control" />
                                                                            <input type="hidden" name="id_promociones_modulo" id="id_promociones_modulo" readonly class="form-control" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <div class="form-group">
                                                                            <label>Canti. Promoción<font color="red">*</font></label>
                                                                            <input type="text" name="cantidad_promocion" id="cantidad_promocion" class="form-control" />

                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <div class="form-group">
                                                                            <label>Pvp Promoción</label>
                                                                            <input type="text" name="pvp_promocion" id="pvp_promocion" class="form-control" />

                                                                        </div>
                                                                    </div>


                                                                </div>
                                                            </div>





                                                        </div>
                                                    </div>




                                                    <div class="col-md-12">
                                                        <div id="grid_container">
                                                            <table id="list_promocion"></table>
                                                            <div id="pager_promocion"></div>
                                                        </div>
                                                    </div>
                                                    <div id="buscar_promo" title="Búsqueda de Rutas" class="">
                                                        <table id="list_promo">
                                                            <tr>
                                                                <td></td>
                                                            </tr>
                                                        </table>
                                                        <div id="pager_promo"></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-mx-12">
                                                            <p>
                                                                <button type="button" class="btn bg-olive margin" id='btnGuardarum'><i class="fa fa-save"></i> Guardar</button>
                                                                <button type="button" class="btn bg-olive margin" id='btnModificarum'><i class="fa fa-save"></i> Modificar</button>
                                                                <button type="button" class="btn bg-olive margin" id='btnNuevoum'><i class="fa fa-pencil"></i> Nuevo</button>

                                                                <button type="button" class="btn bg-olive margin" id='btnBuscar_promo'><i class="fa fa-search"></i> Buscar</button>
                                                                <button type="button" class="btn bg-olive margin" id='btnAnularum'><i class="fa fa-remove"></i> Eliminar</button>
                                                            </p>
                                                        </div>
                                                    </div>

                                                </div><!-- /.tab-pane -->
                                                <div class="tab-pane" id="tab_5">
                                                    <div class="box-body">
                                                        <span style="font-size: 2.2rem; font-weight: bold; color:#37474F;">Producto</span>
                                                        <div style="border: 1px solid; padding: 15px;">
                                                            <div class="row">
                                                                <div id="info_prod_caracteristicas" style="display: none;">
                                                                    <div class="col-md-3">
                                                                        <label for="">Cod. Barras:</label> <br>
                                                                        <!-- <input class="form-control" id="cod_barras_prod_caracteristicas" readonly type="text"> -->
                                                                        <span id="cod_barras_prod_caracteristicas" style="font-size: 2rem; font-weight: bold;"></span>
                                                                    </div>
                                                                    <div class="col-md-9">
                                                                        <label for="">Nombre Producto:</label> <br>
                                                                        <!-- <input class="form-control" id="nombre_prod_caracteristicas" readonly type="text"> -->
                                                                        <span id="nombre_prod_caracteristicas" style="font-size: 2rem; font-weight: bold;"></span>
                                                                    </div>
                                                                </div>
                                                                <div style="text-align: center;" id="empty_prod_caracteristicas">
                                                                    <h3>Selecione un producto para añadir características</h3>
                                                                </div>
                                                            </div>
                                                            <div class="row" style="margin-top: 15px;">
                                                                <div class="col-md-3">
                                                                    <!-- TODO Caracteristicas -->
                                                                    <button id="btn_prod_caracteristicas" type="button" class="btn btn-primary"><i class="fa fa-search"></i> Buscar Producto</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <span style="font-size: 2.2rem; font-weight: bold; color:#37474F;">Características del Producto</span>
                                                        <div style="border: 1px solid; padding: 15px;">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <label for="">Nombre Característica:</label>
                                                                    <input style="text-transform: uppercase;" placeholder="INGRESE NOMBRE" id="nombre_caracteristica" class="form-control" type="text">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="" style="color: #fff;">...</label><br>
                                                                    <button type="button" id="btn_add_caracteristica" class="btn btn-success"><i class="fa fa-plus"></i> Agregar</button>
                                                                </div>
                                                            </div>
                                                            <div class="row" style="margin-top: 15px;">
                                                                <div class="col-md-12">
                                                                    <table id="list_caracteristicas">
                                                                        <tr>
                                                                            <td></td>
                                                                        </tr>
                                                                    </table>
                                                                    <div id="pager_caracteristicas"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- diálogo buscar producto -->
                                                    <div id="dialogo_prod_caracteristicas">
                                                        <table id="list_prod_caracteristicas">
                                                            <tr>
                                                                <td></td>
                                                            </tr>
                                                        </table>
                                                        <div id="pager_prod_caracteristicas"></div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="tab_6">
                                                    <div class="box-body">
                                                        <span style="font-size: 2.2rem; font-weight: bold; color:#37474F;">Descuentos</span>
                                                        <div class="row">
                                                            <div class="col-md-4" style="border: solid 1px; padding: 15px;">
                                                                <div class="form-group">
                                                                    <label for="">Descripción descuento:</label>
                                                                    <input style="text-transform: uppercase;" id="desc_descripcion" placeholder="INGRESE DESCRIPCIÓN" class="form-control" type="text">
                                                                    <label for=""> En la compra del producto número N:</label>
                                                                    <input min="1" id="desc_nro_prod" placeholder="INGRESE N" class="form-control" type="number">
                                                                    <label for="">Aplicar un descuendo de X%:</label>
                                                                    <input min="0" max="100" id="desc_porcentaje" placeholder="INGRESE PORCENTAJE X" class="form-control" type="number">
                                                                    <div id="div_sel_desc_prod" style="display: none;">
                                                                        <label for="">Productos con descuento:</label> <br>
                                                                        <button id="btn_sel_desc_prods" class="btn btn-primary btn-block" type="button"><i class="fa fa-list"></i> Seleccionar Productos</button>
                                                                    </div>
                                                                    <div style="margin-top: 15px;" id="div_guardar_desc">
                                                                        <button type="button" id="btn_add_descuento" class="btn btn-success"><i class="fa fa-plus"></i> Agregar</button>
                                                                    </div>
                                                                    <div style="margin-top: 15px; display:none;" id="div_modificar_desc">
                                                                        <button type="button" id="btn_update_descuento" class="btn btn-success"><i class="fa fa-save"></i> Modificar</button>
                                                                        <button type="button" id="btn_cancel_update" class="btn btn-danger"><i class="fa fa-plus"></i> Cancelar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <table id="list_descuentos">
                                                                    <tr>
                                                                        <td></td>
                                                                    </tr>
                                                                </table>
                                                                <div id="pager_descuentos"></div>
                                                            </div>
                                                        </div>
                                                        <div id="dialogo_sel_prod_desc">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon"><b><i class="fa fa-search"></i> Buscar Producto :</b></span>
                                                                        <input style="border: 1px solid;" id="buscar_prod_desc" class="form-control" type="text" placeholder="INGRESE NOMBRE O CÓDIGO DE BARRAS DEL ARTÍCULO">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <table id="list_det_descuentos">
                                                                        <tr>
                                                                            <td></td>
                                                                        </tr>
                                                                    </table>
                                                                    <div id="pager_det_descuentos"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="tab_33" style="height: 854px">

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="box box-primary">
                                                                <div class="box-body">
                                                                    <div class="rows">
                                                                        <div class="col-mx-12">
                                                                            <form id="clientes_form" name="clientes_form" method="post">

                                                                                <div id="estado"></div>


                                                                        </div>


                                                                        <div class="row">
                                                                            <div class="col-mx-12">
                                                                                <div class="col-md-2">
                                                                                    <div class="form-group">
                                                                                        <label>U. Medida <font color="red">*</font></label>
                                                                                        <input type="text" name="unidad_medida" id="unidad_medida" placeholder="buscar..." class="form-control" />
                                                                                        <input type="hidden" name="id_unidad_medida" id="id_unidad_medida" readonly class="form-control" />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-1 ">
                                                                                    <div class="form-group">
                                                                                        <label>Cantidad</label>

                                                                                        <input type="text" name="cantidad_unidad" id="cantidad_unidad" readonly class="form-control" />

                                                                                    </div>
                                                                                </div>





                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-mx-12">


                                                                                <div class="col-md-1 ">
                                                                                    <div class="form-group">
                                                                                        <label>Pvp. mino <font color="red">*</font></label>

                                                                                        <input type="text" name="pvpmino" id="pvpmino" class="form-control" />

                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-2">
                                                                                    <div class="form-group">
                                                                                        <label>PVP MINORISTA IVA: </label>
                                                                                        <div class="input-group">
                                                                                            <div class="input-group-addon">
                                                                                                <i class="fa fa-usd"></i>
                                                                                            </div>
                                                                                            <input type="text" name="precio_minorista_final_u" id="precio_minorista_final_u" placeholder="0.0000" class="form-control" />
                                                                                        </div>
                                                                                    </div>
                                                                                </div>





                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-mx-12">




                                                                                <div class="col-md-1">
                                                                                    <div class="form-group">
                                                                                        <label>Pvp. mayo <font color="red">*</font></label>
                                                                                        <input type="text" name="pvpmayo" id="pvpmayo" class="form-control" />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-2">
                                                                                    <div class="form-group">
                                                                                        <label>PVP MAYORISTA IVA: </label>
                                                                                        <div class="input-group">
                                                                                            <div class="input-group-addon">
                                                                                                <i class="fa fa-usd"></i>
                                                                                            </div>
                                                                                            <input type="text" name="precio_mayorista_final_u" id="precio_mayorista_final_u" class="form-control" placeholder="0.0000" />
                                                                                        </div>
                                                                                    </div>
                                                                                </div>



                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-mx-12">


                                                                                <div class="col-md-1">
                                                                                    <div class="form-group">
                                                                                        <label>Pvp. negocio <font color="red">*</font></label>
                                                                                        <input type="text" name="pvpnego" id="pvpnego" class="form-control" />

                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-2">
                                                                                    <div class="form-group">
                                                                                        <label>PVP NEGOCIO IVA: </label>
                                                                                        <div class="input-group">
                                                                                            <div class="input-group-addon">
                                                                                                <i class="fa fa-usd"></i>
                                                                                            </div>
                                                                                            <input type="text" name="precio_negocio_final_u" id="precio_negocio_final_u" placeholder="0.0000" class="form-control" />
                                                                                        </div>
                                                                                    </div>
                                                                                </div>


                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-mx-12">


                                                                                <div class="col-md-1">
                                                                                    <div class="form-group">
                                                                                        <label>Cant. Mayo: <font color="red">*</font></label>
                                                                                        <input type="number" name="cantidad_mayorista_unidad" id="cantidad_mayorista_unidad" class="form-control" />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-2">
                                                                                    <div class="form-group">
                                                                                        <label>Cant. Nego: <font color="red">*</font></label>
                                                                                        <input type="number" name="cantidad_negocio_unidad" id="cantidad_negocio_unidad" class="form-control" value="0" />
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-12">
                                                                            <div id="grid_container">
                                                                                <table id="list_unidad"></table>
                                                                                <div id="pager_unidad"></div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="col-mx-12">
                                                                            </div>
                                                                        </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                                <div id="buscar_inventario" title="BUSCAR INVENTARIO">
                                                                    <table id="list22">
                                                                        <tr>
                                                                            <td></td>
                                                                        </tr>
                                                                    </table>
                                                                    <div id="pager22"></div>
                                                                </div>



                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <p>
                                                                            <button style="display: none;" type="button" class="btn bg-olive margin" id='btnGuardarum1'><i class="fa fa-save"></i> Guardar</button>
                                                                            <button style="display: none;" type="button" class="btn bg-olive margin" id='btnModificarum1'><i class="fa fa-save"></i> Modificar</button>
                                                                            <!-- <button type="button" class="btn bg-olive margin" id='btnNuevoum1'><i class="fa fa-pencil"></i> Nuevo</button> -->
                                                                            <!--<button class="btn bg-olive margin" id='btnAnularum'><i class="fa fa-remove"></i> Anular</button>-->
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div><!-- /.tab-pane -->
                                                <div class="tab-pane" id="tab_333" style="height: 854px">

                                                    <div class="box-body">
                                                        <span style="font-size: 2.2rem; font-weight: bold; color:#37474F;">Producto</span>
                                                        <div style="border: 1px solid; padding: 15px;">
                                                            <div class="row">
                                                                <div id="info_prod_caracteristicas_pvpv" style="display: none;">
                                                                    <div class="col-md-3">
                                                                        <label for="">Cod. Barras:</label> <br>
                                                                        <!-- <input class="form-control" id="cod_barras_prod_caracteristicas" readonly type="text"> -->
                                                                        <span id="cod_barras_prod_caracteristicas_pvpv" style="font-size: 2rem; font-weight: bold;"></span>
                                                                    </div>
                                                                    <div class="col-md-9">
                                                                        <label for="">Nombre Producto:</label> <br>
                                                                        <!-- <input class="form-control" id="nombre_prod_caracteristicas" readonly type="text"> -->
                                                                        <span id="nombre_prod_caracteristicas_pvpv" style="font-size: 2rem; font-weight: bold;"></span>
                                                                    </div>
                                                                </div>
                                                                <div style="text-align: center;" id="empty_prod_caracteristicas_pvpv">
                                                                    <h3>Selecione un producto para añadir características</h3>
                                                                </div>
                                                            </div>
                                                            <div class="row" style="margin-top: 15px;">
                                                                <div class="col-md-3">
                                                                    <!-- TODO Caracteristicas -->
                                                                    <button id="btn_prod_caracteristicas_pvpv" type="button" class="btn btn-primary"><i class="fa fa-search"></i> Buscar Producto</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <!--                                                        <span style="font-size: 2.2rem; font-weight: bold; color:#37474F;">Características del Producto</span>-->
                                                        <div style="border: 1px solid; padding: 15px;">
                                                            <div class="row">
                                                                <!--                                                                <div class="col-md-4">
                                                                                                                                                                                                        <label for="">Nombre Característica:</label>
                                                                                                                                                                                                        <input style="text-transform: uppercase;" placeholder="INGRESE NOMBRE" id="nombre_caracteristica_pvpv" class="form-control" type="text">
                                                                                                                                                                                                        </div>-->
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>EDITAR PVP EN FACTURA DE VENTA:</label>
                                                                        <input type="text" name="nombre_caracteristica_pvpv" id="nombre_caracteristica_pvpv" value="SI" readonly="" class="form-control" />
                                                                        <!--                                                                        <select class="form-control" name="nombre_caracteristica_pvpv" id="nombre_caracteristica_pvpv">
                                                                                                                                                                                                    <option value="0" selected>Seleccione una opción...</option>
                                                                                                                                                                                                    <option  value="Si" selected>Si</option>
                                                                                                                                                                                                    <option id="pvp_no" value="No">No</option>
                                                                                                                                                                                                </select>-->
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <label for="" style="color: #fff;">...</label><br>
                                                                    <button type="button" id="btn_add_caracteristica_pvpv" class="btn btn-success"><i class="fa fa-plus"></i> Agregar</button>
                                                                </div>
                                                            </div>
                                                            <div class="row" style="margin-top: 15px;">
                                                                <div class="col-md-12">
                                                                    <table id="list_caracteristicas_pvpv">
                                                                        <tr>
                                                                            <td></td>
                                                                        </tr>
                                                                    </table>
                                                                    <div id="pager_caracteristicas_pvpv"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- diálogo buscar producto -->
                                                    <div id="dialogo_prod_caracteristicas_pvpv">
                                                        <table id="list_prod_caracteristicas_pvpv">
                                                            <tr>
                                                                <td></td>
                                                            </tr>
                                                        </table>
                                                        <div id="pager_prod_caracteristicas_pvpv"></div>
                                                    </div>

                                                </div><!-- /.tab-pane -->
                                            </div><!-- /.tab-content -->
                                        </form>
                                    </div>



                                </div>
                                <div id="productos" title="Búsqueda de Productos" class="">
                                    <table id="list">
                                        <tr>
                                            <td></td>
                                        </tr>
                                    </table>
                                    <div id="pager"></div>
                                </div>

                                <div id="cuentas" title="Búsqueda Plan de Cuentas" class="">
                                    <table id="list2"></table>
                                    <div id="pager2"></div>
                                </div>
                                <div id="categorias" title="AGREGAR CATEGORÍA">
                                    <div class="control-group">
                                        <label class="control-label" for="nombre_categoria">Nombre Categoría: <font color="red">*</font></label>
                                        <div class="controls">
                                            <input type="text" name="nombre_categoria" id="nombre_categoria" class="campo" placeholder="Categoría" required />
                                        </div>
                                    </div>
                                    <button class="btn btn-primary" id='btnGuardarCategoria'>Guardar</button>
                                </div>


                                <div id="marcas" title="AGREGAR MARCA">
                                    <div class="form-group">
                                        <!-- <label>Nombre Laboratorio: <font color="red">*</font></label> -->
                                        <label>Marca: <font color="red">*</font></label>
                                        <input type="text" name="nombre_marca" id="nombre_marca" placeholder="Ingrese MARCA" class="form-control" />
                                    </div>
                                    <button class="btn btn-primary" id='btnGuardarMarca'>Guardar</button>
                                </div>


                                <div id="generico" title="AGREGAR GENÉRICO">
                                    <div class="form-group">
                                        <label>Nombre Genérico: <font color="red">*</font></label>
                                        <input type="text" name="nombre_generico" id="nombre_generico" placeholder="Ingrese Genérico" class="form-control" />
                                    </div>
                                    <button class="btn btn-primary" id='btnGuardarGenerico'>Guardar</button>
                                </div>

                                <div id="aplicacionlist" title="AGREGAR APLICACIÓN">
                                    <div class="form-group">
                                        <label>Nombre Aplicación: <font color="red">*</font></label>
                                        <input type="text" name="nombre_aplicacion" id="nombre_aplicacion" placeholder="Ingrese Aplicación" class="form-control" />
                                    </div>
                                    <button class="btn btn-primary" id='btnGuardarAplicacion'>Guardar</button>
                                </div>

                                <div id="proveedores" title="AGREGAR PROVEEDOR">
                                    <div class="form-group">
                                        <label>Tipo Documento: <font color="red">*</font></label>
                                        <select class="form-control" name="tipo_docu" id="tipo_docu">
                                            <option value="Cedula">Cedula</option>
                                            <option value="Ruc">Ruc</option>
                                            <option value="Pasaporte">Pasaporte</option>
                                        </select>
                                        <input type="hidden" name="id_proveedor" id="id_proveedor" readonly class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>Empresa: <font color="red">*</font></label>
                                        <input name="empresa_pro" id="empresa_pro" placeholder="Nombre de la Empresa" class="form-control" />
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
                                        <input type="text" name="pais_pro" id="pais_pro" placeholder="Ingrese un país" class="form-control" />
                                    </div>

                                    <div class="form-group">
                                        <label>RUC/CI: <font color="red">*</font></label>
                                        <input type="text" name="ruc_ci" id="ruc_ci" class="form-control" />
                                    </div>

                                    <div class="form-group">
                                        <label>Dirección: <font color="red">*</font></label>
                                        <input type="text" name="direccion_pro" id="direccion_pro" placeholder="Dirección" class="form-control" />
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
                                        <input type="text" name="ciudad_pro" id="ciudad_pro" class="form-control" />
                                    </div>

                                    <div class="form-group">
                                        <label>Forma de Pago:</label>
                                        <select class="form-control" name="forma_pago" id="forma_pago">
                                            <option value="Contado" selected>Contado</option>
                                            <option value="Credito">Credito</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Proveedor Principal: <font color="red">*</font></label>
                                        <select class="form-control" name="principal_pro" id="principal_pro">
                                            <option value="Si" selected>Si</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Tipo:</label>
                                        <select class="form-control" name="tipo_pro" id="tipo_pro">
                                            <option value="Persona Natural" selected>Persona Natural</option>
                                            <option value="Persona Jurídica">Persona Jurídica</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Comentarios:</label>
                                        <textarea class="form-control" name="observaciones_pro" id="observaciones_pro" rows="3"></textarea>
                                    </div>
                                    <button class="btn btn-primary" id='btnGuardarProveedor'>Guardar</button>
                                </div>

                                <div id="clave_permiso" title="PERMISOS">
                                    <table border="0">
                                        <tr>
                                            <td><label>Ingrese la clave de seguridad</label></td>
                                            <td><input type="password" name="clave" id="clave" class="campo"></td>
                                        </tr>
                                    </table>
                                    <div class="form-actions" align="center">
                                        <button class="btn btn-primary" id='btnAcceder'><i class="icon-ok"></i> Acceder</button>
                                        <button class="btn btn-primary" id='btnCancelar'><i class="icon-remove-sign"></i> Cancelar</button>
                                    </div>
                                </div>

                                <div id="seguro">
                                    <label>¿Está seguro de eliminar al producto?</label>
                                    <br />
                                    <button class="btn btn-primary" id='btnAceptar'><i class="icon-ok"></i> Aceptar</button>
                                    <button class="btn btn-primary" id='btnSalir'><i class="icon-remove-sign"></i> Cancelar</button>
                                </div>

                            </div><!-- nav-tabs-custom -->
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
        <script src="productos.js" type="text/javascript"></script>
        <script src="caracteristicas_prod.js" type="text/javascript"></script>
        <script src="editar_pvp_venta.js" type="text/javascript"></script>
        <script src="descuentos_prod.js" type="text/javascript"></script>
        <script src="../../dist/js/decimales.js" type="text/javascript"></script>
        <link href="../../dist/css/style.css" rel="stylesheet" type="text/css" />
        <script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>
        <script type="text/javascript" src="../../dist/js/base.js"></script>
        <script type="text/javascript" src="../../dist/js/jquery.ui.datepicker-es.js"></script>
    </body>

</html>