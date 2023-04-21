<?php
session_start();
include '../../procesos/base.php';
include('../menu/app.php');
conectarse();
error_reporting(0);

$consulta8 = pg_query("select * from punto_venta_empresa  left join punto_venta  on punto_venta_empresa.id_punto_venta=punto_venta.id_punto_venta  where  
punto_venta_empresa.id_usuario='$_SESSION[id]' ORDER BY id_punto_venta_empresa ASC");
while ($row = pg_fetch_row($consulta8)) {
    $campo_punto_ventaid = $row[5];
}
$consulta7 = pg_query("select * from punto_venta_empresa  left join punto_venta  on punto_venta_empresa.id_punto_venta=punto_venta.id_punto_venta  where  
punto_venta_empresa.id_usuario='$_SESSION[id]' ORDER BY id_punto_venta_empresa ASC");
while ($row = pg_fetch_row($consulta7)) {
    $campo_punto_venta = $row[6];
}
$cont1 = 0;
$consulta = pg_query("select max(id_egresos) from egresos");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;

$idcargousuario = getIdCargoUsuario();
function getIdCargoUsuario()
{
    $idusuario = $_SESSION["id"];
    $sql = "
    select id_cargo_usuario from usuario
    where id_usuario=$idusuario
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_assoc($res);
    if (empty($rows)) {
        return 0;
    }
    return $rows["id_cargo_usuario"];
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>EGRESOS</title>
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
        .ui-jqgrid tr.jqgrow td {
            white-space: normal !important;
        }
    </style>
</head>

<body class="skin-blue">
    <div class="wrapper">
        <?php banner_1(); ?>
        <?php menu_lateral_1(); ?>
        <div class="content-wrapper">
            <section class="content-header">
                <h1>EGRESOS</h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Procesos</a></li>
                    <li class="active">Egresos</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#egresos">EGRESOS</a></li>
                        <li><a data-toggle="tab" href="#transferencias_p">TRANSFERENCIAS PENDIENTES</a></li>
                        <li><a data-toggle="tab" href="#transferencias">HISTORIAL DE TRANSFERENCIAS</a></li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div id="egresos" class="tab-pane fade in active">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box box-primary">
                                    <div class="box-body">
                                        <div class="rows">
                                            <div class="col-mx-12">
                                                <form id="ingresos_form" name="ingresos_form" method="post">
                                                    <div class="row">
                                                        <div class="col-mx-12">
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label>Fecha Actual:</label>
                                                                    <div class="input-group">
                                                                        <input type="text" name="fecha_actual" id="fecha_actual" readonly class="form-control" />
                                                                        <!--<input type="hidden" name="comprobante" id="comprobante" readonly class="form-control" value="<?php echo $cont1 ?>" />-->
                                                                        <input type="hidden" name="id_egreso" id="id_egreso" />
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
                                                                    <div id="estado"></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label>NUM COMPROBANTE</label>

                                                                    <input type="text" name="comprobante" id="comprobante" readonly class="form-control" value="<?php echo $cont1 ?>" />
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <br />
                                                    <div class="row">
                                                        <div class="col-md-12">

                                                            <div class="col-md-3">
                                                                <label>Tipo de transacción:</label>
                                                                <div class="input-group">
                                                                    <select class="form-control" id="slTransacciones" name="slTransacciones"></select>
                                                                </div>
                                                            </div>

                                                            <div id="origDest">

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <!-- <label class="col-md-4">Ingrese un Origen:</label> -->
                                                                        <div class="form-group col-md-8 no-padding">
                                                                            <div class="col-md-12">
                                                                                <!-- <label>Punto de Venta: </label> -->
                                                                                <label>Ingrese un Origen:</label>
                                                                                <div class="input-group">
                                                                                    <select class="form-control" name="origen" id="origen">
                                                                                        <option value="">Puntos de venta...</option>
                                                                                        <?php
                                                                                        //var_dump("select * from punto_venta  where id_punto_venta= $_SESSION[PV] order by id_punto_venta");
                                                                                        $consulta = pg_query("select * from punto_venta where id_punto_venta= $_SESSION[PV] order by id_punto_venta");
                                                                                        while ($row = pg_fetch_row($consulta)) {
                                                                                            echo "<option selected id=$row[0] value=$row[0]>$row[1]</option>";
                                                                                        }
                                                                                        ?>
                                                                                    </select>
                                                                                    <span class="input-group-btn">
                                                                                        <!--<button class="btn btn-primary" type="button" id="btnPuntoventa">Agregar</button>-->
                                                                                        <div class="col-xs-12">
                                                                                            <!--<button type="submit" class="btn btn-primary btn-block btn-flat" id="btnIngreso" >INGRESAR</button>-->
                                                                                        </div>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <!-- <label class="col-md-3">Ingrese un Destino:</label> -->
                                                                        <div class="form-group col-md-8 no-padding">
                                                                            <div class="col-md-12">
                                                                                <!-- <label>Punto de Venta: </label> -->
                                                                                <label>Ingrese un Destino:</label>
                                                                                <div class="input-group">
                                                                                    <select class="form-control" name="destino" id="destino">
                                                                                        <option value="">Puntos de venta...</option>
                                                                                        <?php
                                                                                        $consulta = pg_query("select * from punto_venta where id_punto_venta<>$_SESSION[PV] order by id_punto_venta  ");
                                                                                        while ($row = pg_fetch_row($consulta)) {
                                                                                            echo "<option id=$row[0] value=$row[0]>$row[1]</option>";
                                                                                        }
                                                                                        ?>
                                                                                    </select>
                                                                                    <span class="input-group-btn">
                                                                                        <!--<button class="btn btn-primary" type="button" id="btnPuntoventa">Agregar</button>-->
                                                                                        <div class="col-xs-12">
                                                                                            <!--<button type="submit" class="btn btn-primary btn-block btn-flat" id="btnIngreso" >INGRESAR</button>-->
                                                                                        </div>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                                    <hr />
                                                    <h3 class="box-title">Detalle Egresos</h3>
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
                                                            <div class="col-md-1">
                                                                <label>U.MEDIDA: </label>
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
                                                                    <input type="text" name="cantidad" id="cantidad" class="form-control" placeholder="0.00" />
                                                                    <input type="hidden" name="cantidad_unidad" id="cantidad_unidad" readonly="" class="form-control" min="1" />
                                                                </div>
                                                            </div>

                                                            <?php
                                                            if ($idcargousuario == 1) {
                                                                echo '<div class="col-md-1">';
                                                            } else {
                                                                echo '<div class="col-md-1" style="display:none;">';
                                                            }
                                                            ?>
                                                            <div class="form-group">
                                                                <label>P.COSTO</label>
                                                                <input type="text" name="precio" id="precio" readonly class="form-control" placeholder="0.0000" />
                                                            </div>
                                                        </div>

                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>P.VENTA</label>
                                                                <input type="text" name="p_venta" id="p_venta" readonly class="form-control" placeholder="0.0000" />
                                                            </div>
                                                        </div>

                                                        <div class="col-md-1" style="display: none;">
                                                            <div class="form-group">
                                                                <label>DESC.</label>
                                                                <input type="number" name="descuento" id="descuento" min="0" readonly placeholder="%" class="form-control" />
                                                                <input type="hidden" name="iva_producto" id="iva_producto" readonly class="form-control" />
                                                                <input type="hidden" name="cod_producto" id="cod_producto" readonly class="form-control" />
                                                                <input type="hidden" name="incluye" id="incluye" readonly class="form-control" />
                                                                <input type="hidden" name="disponibles" id="disponibles" readonly class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>STOCK</label>
                                                                <input readonly class="form-control" type="text" id="stock" value="0">
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>

                                            <div class="col-mx-12">
                                                <div id="grid_container">
                                                    <table id="list"></table>
                                                </div>
                                            </div>

                                            <div class="row">
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
                                                    <?php
                                                    if ($idcargousuario == 1) {
                                                        echo '<div class="col-md-3">';
                                                    } else {
                                                        echo '<div class="col-md-3" style="display:none">';
                                                    }
                                                    ?>
                                                    <div class="form-group">
                                                        <label class="col-md-5">Tarifa 0:</label>
                                                        <div class="form-group col-md-7 no-padding">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="glyphicon glyphicon-usd"></i>
                                                                </div>
                                                                <input type="text" name="total_p" id="total_p" value="0.000" readonly class="form-control" />
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
                                                                <input type="text" name="total_p2" id="total_p2" value="0.000" readonly class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!--                                                                    <div class="form-group">
                                                                                                                                            <label class="col-md-5">... %Iva:</label>
                                                                                                                                            <div class="form-group col-md-7 no-padding">
                                                                                                                                                <div class="input-group">
                                                                                                                                                    <div class="input-group-addon">
                                                                                                                                                        <i class="glyphicon glyphicon-usd"></i>
                                                                                                                                                    </div>-->
                                                    <input type="hidden" name="iva" id="iva" value="0.000" readonly class="form-control" />
                                                    <!--                                                                            </div>
                                                                                                                                            </div>
                                                                                                                                        </div>-->

                                                    <!--                                                                    <div class="form-group">
                                                                                                                                            <label class="col-md-5">Descuento:</label>
                                                                                                                                            <div class="form-group col-md-7 no-padding">
                                                                                                                                                <div class="input-group">
                                                                                                                                                    <div class="input-group-addon">
                                                                                                                                                        <i class="glyphicon glyphicon-usd"></i>
                                                                                                                                                    </div>-->
                                                    <input type="hidden" name="desc" id="desc" value="0.000" readonly class="form-control" />
                                                    <!--                                                                            </div>
                                                                                                                                            </div>
                                                                                                                                        </div>-->

                                                    <div class="form-group">
                                                        <label class="col-md-5">Total:</label>
                                                        <div class="form-group col-md-7 no-padding">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="glyphicon glyphicon-usd"></i>
                                                                </div>
                                                                <input type="text" name="tot" id="tot" value="0.000" readonly class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </form>
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
                                            <button class="btn bg-olive margin" id='btnAtras'><i class="fa fa-backward"></i> Atras</button>
                                            <button class="btn bg-olive margin" id='btnAdelante'>Adelante <i class="fa fa-forward"></i></button>
                                            <button style="display: none;" class="btn bg-olive margin" id='btnAnular'><i class="fa fa-remove"></i> Anular</button>
                                        </p>
                                    </div>
                                    <div id="buscar_ingresos" title="BUSCAR INGRESOS">
                                        <table id="list2">
                                            <tr>
                                                <td></td>
                                            </tr>
                                        </table>
                                        <div id="pager2"></div>
                                    </div>
                                </div>

                                <!--****** ANULAR ******-->
                                <div id="clave_permiso" title="PERMISOS">
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-md-6">Ingrese la clave de seguridad</label>
                                            <div class="form-group col-md-6 no-padding">
                                                <input type="password" name="clave" id="clave" required class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3">Comentario</label>
                                            <div class="form-group col-md-9">
                                                <textarea id="anulacionComentario" name="anulacionComentario" class="form-control" required maxlength="50"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions" align="center">
                                        <button class="btn btn-primary" id='btnAcceder'><i class="icon-ok"></i> Acceder</button>
                                        <button class="btn btn-primary" id='btnCancelar'><i class="icon-remove-sign"></i> Cancelar</button>
                                    </div>
                                </div>

                                <!--****** CONFIRMAR ANULACION******-->
                                <div id="seguro">
                                    <label>Esta seguro de Anular la factura</label>
                                    <br />
                                    <div class="form-actions" align="center">
                                        <button class="btn btn-primary" id='btnAceptar'><i class="icon-ok"></i> Aceptar</button>
                                        <button class="btn btn-primary" id='btnSalir'><i class="icon-remove-sign"></i> Cancelar</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <div id="transferencias_p" class="tab-pane fade">
            <div class="row">
                <div class="col-md-12">
                    <table id="table_trp" style="width: 100%;"></table>
                    <div id="pager_trp"></div>
                </div>
            </div>
        </div>
        <div id="transferencias" class="tab-pane fade">
            <div class="row">
                <div class="col-md-12">
                    <table id="table_tr" style="width: 100%;"></table>
                    <div id="pager_tr"></div>
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
    <script>
        var idCargoUsuario = <?php echo $idcargousuario ?>
    </script>
    <script src="egresos.js" type="text/javascript"></script>
    <script src="../../dist/js/decimales.js" type="text/javascript"></script>
    <link href="../../dist/css/style.css" rel="stylesheet" type="text/css" />
    <script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>
    <script src="transferencias/transferencias.js"></script>
</body>

</html>