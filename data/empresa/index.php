<?php
session_start();
include '../../procesos/base.php';
include('../menu/app.php');
$consulta5 = pg_query("select porcentaje_tarjeta from empresa");
while ($row = pg_fetch_row($consulta5)) {
    $campo_porcentaje = $row[0];
}

$formatosF = obtenerFormatos(1);
$formatosN = obtenerFormatos(2);
$formatosNC = obtenerFormatos(3);
$formatosFC = obtenerFormatos(4);
$formatosRC = obtenerFormatos(5);

function obtenerFormatos($tipoformato) {
    $sqlFormatos = pg_query("select*from parametros_formatos_impresion where id_tipo_formato=$tipoformato order by id_formato asc");
    $formatos = pg_fetch_all($sqlFormatos);
    if (empty($formatos)) {
        $formatos = [];
    }
    return $formatos;
}
?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>EMPRESA</title>
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
            input {
                margin-bottom: 15px;
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
                        Registro de Empresa
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Parametros</a></li>
                        <li class="active">Empresa</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab_1" data-toggle="tab">Registro Empresa</a></li>
                                    <li><a href="#tab_2" data-toggle="tab">Ambiente</a></li>
                                    <li><a href="#tab_3" data-toggle="tab">Tipo Comprobante</a></li>
                                    <li><a href="#tab_4" data-toggle="tab">Tipo Emisión</a></li>
                                    <li><a href="#tab_5" data-toggle="tab">Tipo Impuesto</a></li>
                                    <li><a href="#tab_6" data-toggle="tab">Formas de Pago</a></li>
                                    <li><a href="#tab_7" data-toggle="tab">Tarifa Impuesto</a></li>
                                    <li><a href="#tab_8" data-toggle="tab">Parámetros Empresa</a></li>
                                </ul>
                                <div class="box-body">
                                    <div class="row">
                                        <form id="parametros_form" name="parametros_form" method="post">
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab_1">
                                                    <div class="col-md-12">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Razòn Social: <font color="red">*</font></label>
                                                                <input type="text" name="nombre" id="nombre" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Nombre Comercial: <font color="red">*</font></label>
                                                                <input type="text" name="nombre_comercial" id="nombre_comercial" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Representante: <font color="red">*</font></label>
                                                                <input type="text" name="representante" id="representante" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>RUC: <font color="red">*</font></label>
                                                                <input type="text" name="ruc" id="ruc" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Dirección: <font color="red">*</font></label>
                                                                <input type="text" name="direccion" id="direccion" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Teléfono:<font color="red">*</font></label>
                                                                <input type="text" name="telefono" id="telefono" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Celular: <font color="red">*</font></label>
                                                                <input type="text" name="celular" id="celular" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Fax: </label>
                                                                <input type="text" name="fax" id="fax" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>País:<font color="red">*</font></label>
                                                                <input type="text" name="pais" id="pais" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Ciudad:<font color="red">*</font> </label>
                                                                <input type="text" name="ciudad" id="ciudad" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>E-mail: </label>
                                                                <input type="text" name="email" id="email" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Página Web: </label>
                                                                <input type="text" name="pagina" id="pagina" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Descripción:</label>
                                                                <textarea type="text" name="descripcion" id="descripcion" class="form-control"></textarea>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Obligación:<font color="red">*</font></label>
                                                                <select name="obligacion" id="obligacion" class="form-control">
                                                                    <option value="NO">NO</option>
                                                                    <option value="SI">SI</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Contribuyente Especial:</label>
                                                                <input type="text" name="contribuyente_espe" id="contribuyente_espe" placeholder="Ej. 214" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">

                                                        <div class="col-md-4" style="display: none;">
                                                            <div class="form-group">
                                                                <label>Token: <font color="red">*</font></label>
                                                                <input type="text" name="token" id="token" placeholder=".p12" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4" style="display: none;">
                                                            <div class="form-group">
                                                                <label>clave Token:<font color="red">*</font></label>
                                                                <input type="password" name="claveToken " id="claveToken" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Establecimiento: <font color="red">*</font></label>
                                                                <input type="text" name="establecimiento" id="establecimiento" placeholder="Ej. 001" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Punto Emisión: <font color="red">*</font></label>
                                                                <input type="text" name="punto_emision" id="punto_emision" placeholder="Ej. 001" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Num Items:</label>
                                                                <input type="text" name="num_items" id="num_items" value="30" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Porcentaje para Tarjetas de Crédito:</label>
                                                                <input type="number" name="porcen_tc" id="porcen_tc" class="form-control" value="<?php echo $campo_porcentaje ?>" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <button class="btn bg-olive margin" id='btnGuardar'><i class="fa fa-save"></i> Guardar</button>
                                                            <button class="btn bg-olive margin" id='btnModificar'><i class="fa fa-pencil"></i> Modificar</button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tab-pane" id="tab_2" style="height: 300px">
                                                    <div class="col-md-12">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Tipo de Ambiente: </label>
                                                                <select class="form-control" name="tipo_ambiente" id="tipo_ambiente">
                                                                    <option value="">Seleccione una opción</option>
                                                                    <option value="Producción">Producción</option>
                                                                    <option value="Pruebas">Pruebas</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>Código : </label>
                                                                <input type="number" name="codigo_ambi" id="codigo_ambi" maxlength="2" max="2" min="1" class="form-control" />
                                                                <input type="hidden" name="id_ambi" id="id_ambi" readonly class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Estado: </label>
                                                                <select class="form-control" name="estado_tipo_ambiente" id="estado_tipo_ambiente">
                                                                    <option value="Activo">Activo</option>
                                                                    <option value="Inactivo">Inactivo</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-12">
                                                    </div>
                                                    <div class="col-mx-12">
                                                        <p>
                                                            <button class="btn bg-olive margin" id='btnGuardarAmbi'><i class="fa fa-save"></i> Guardar</button>
                                                            <button class="btn bg-olive margin" id='btnModificarAmbi'><i class="fa fa-edit"></i> Modificar</button>
                                                            <button class="btn bg-olive margin" id='btnEliminarAmbi'><i class="fa fa-remove"></i> Eliminar</button>
                                                            <button class="btn bg-olive margin" id='btnBuscarAmbi'><i class="fa fa-search"></i> Buscar</button>
                                                            <button class="btn bg-olive margin" id='btnNuevoAmbi'><i class="fa fa-pencil"></i> Nuevo</button>
                                                        </p>
                                                    </div>

                                                    <div id="ambientes" title="Búsqueda de Tipo de Ambiente" class="">
                                                        <table id="list">
                                                            <tr>
                                                                <td></td>
                                                            </tr>
                                                        </table>
                                                        <div id="pagerl"></div>
                                                    </div>
                                                </div><!-- /.tab-pane -->

                                                <div class="tab-pane" id="tab_3" style="height: 300px">
                                                    <div class="col-md-12">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Nombre: </label>
                                                                <input type="text" name="nombre_tipo_compro" id="nombre_tipo_compro" class="form-control" />
                                                                <input type="hidden" name="id_tipo_comprobante" id="id_tipo_comprobante" readonly class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Abreviatura: </label>
                                                                <input type="text" name="abreviatura_tipo_compro" id="abreviatura_tipo_compro" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>Código : </label>
                                                                <input type="number" name="codigo_tipo_compro" id="codigo_tipo_compro" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-mx-12">
                                                        <p>
                                                            <button class="btn bg-olive margin" id='btnGuardartipo_compro'><i class="fa fa-save"></i> Guardar</button>
                                                            <button class="btn bg-olive margin" id='btnModificartipo_compro'><i class="fa fa-edit"></i> Modificar</button>
                                                            <button class="btn bg-olive margin" id='btnEliminartipo_compro'><i class="fa fa-remove"></i> Eliminar</button>
                                                            <button class="btn bg-olive margin" id='btnBuscartipo_compro'><i class="fa fa-search"></i> Buscar</button>
                                                            <button class="btn bg-olive margin" id='btnNuevotipo_compro'><i class="fa fa-pencil"></i> Nuevo</button>
                                                        </p>
                                                    </div>
                                                    <div id="tipo_comprobante" title="Búsqueda de Tipo Comprobante" class="">
                                                        <table id="listTipo">
                                                            <tr>
                                                                <td></td>
                                                            </tr>
                                                        </table>
                                                        <div id="pagert"></div>
                                                    </div>
                                                </div><!-- /.tab-pane -->

                                                <div class="tab-pane" id="tab_4" style="height: 300px">
                                                    <div class="col-md-12">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Nombre: </label>
                                                                <input type="text" name="nombre_tipo_emision" id="nombre_tipo_emision" class="form-control" />
                                                                <input type="hidden" name="id_temision" id="id_temision" readonly class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Código : </label>
                                                                <input type="number" name="codigo_tipo_emision" id="codigo_tipo_emision" maxlength="2" max="2" min="1" class="form-control" />
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Estado: </label>
                                                                <select class="form-control" name="estado_tipo_emision" id="estado_tipo_emision">
                                                                    <option value="Activo">Activo</option>
                                                                    <option value="Inactivo">Inactivo</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-mx-12">
                                                        <p>
                                                            <button class="btn bg-olive margin" id='btnGuardartipo_emision'><i class="fa fa-save"></i> Guardar</button>
                                                            <button class="btn bg-olive margin" id='btnModificartipo_emision'><i class="fa fa-edit"></i> Modificar</button>
                                                            <button class="btn bg-olive margin" id='btnEliminartipo_emision'><i class="fa fa-remove"></i> Eliminar</button>
                                                            <button class="btn bg-olive margin" id='btnBuscartipo_emision'><i class="fa fa-search"></i> Buscar</button>
                                                            <button class="btn bg-olive margin" id='btnNuevotipo_emision'><i class="fa fa-pencil"></i> Nuevo</button>
                                                        </p>
                                                    </div>
                                                    <div id="tipo_emision" title="Búsqueda de Tipo Emisión" class="">
                                                        <table id="listTipo_emision">
                                                            <tr>
                                                                <td></td>
                                                            </tr>
                                                        </table>
                                                        <div id="pagere"></div>
                                                    </div>
                                                </div><!-- /.tab-pane -->

                                                <div class="tab-pane" id="tab_5" style="height: 300px">
                                                    <div class="col-md-12">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Nombre: </label>
                                                                <input type="text" name="nombre_timpu" id="nombre_timpu" class="form-control" />
                                                                <input type="hidden" name="id_timpu" id="id_timpu" readonly class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Código : </label>
                                                                <input type="number" name="codigo_timpu" id="codigo_timpu" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                    </div>
                                                    <div class="col-mx-12">
                                                        <p>
                                                            <button class="btn bg-olive margin" id='btnGuardartipo_impuesto'><i class="fa fa-save"></i> Guardar</button>
                                                            <button class="btn bg-olive margin" id='btnModificartipo_impuesto'><i class="fa fa-edit"></i> Modificar</button>
                                                            <button class="btn bg-olive margin" id='btnEliminartipo_impuesto'><i class="fa fa-remove"></i> Eliminar</button>
                                                            <button class="btn bg-olive margin" id='btnBuscartipo_impuesto'><i class="fa fa-search"></i> Buscar</button>
                                                            <button class="btn bg-olive margin" id='btnNuevotipo_impuesto'><i class="fa fa-pencil"></i> Nuevo</button>
                                                        </p>
                                                    </div>
                                                    <div id="tipo_impuestoList" title="Búsqueda de Tipo Impuestos" class="">
                                                        <table id="listTipo_impuesto">
                                                            <tr>
                                                                <td></td>
                                                            </tr>
                                                        </table>
                                                        <div id="pageri"></div>
                                                    </div>
                                                </div><!-- /.tab-pane -->

                                                <div class="tab-pane" id="tab_6" style="height: 300px">
                                                    <div class="col-md-12">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Moneda: </label>
                                                                <input type="text" name="moneda_form_pagos" id="moneda_form_pagos" class="form-control" />
                                                                <input type="hidden" name="id_form_pagos" id="id_form_pagos" readonly class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Tipo : </label>
                                                                <input type="text" name="tipo_form_pagos" id="tipo_form_pagos" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-mx-12">
                                                        <p>
                                                            <button class="btn bg-olive margin" id='btnGuardarForma_pagos'><i class="fa fa-save"></i> Guardar</button>
                                                            <button class="btn bg-olive margin" id='btnModificarForma_pagos'><i class="fa fa-edit"></i> Modificar</button>
                                                            <button class="btn bg-olive margin" id='btnEliminarForma_pagos'><i class="fa fa-remove"></i> Eliminar</button>
                                                            <button class="btn bg-olive margin" id='btnBuscarForma_pagos'><i class="fa fa-search"></i> Buscar</button>
                                                            <button class="btn bg-olive margin" id='btnNuevoForma_pagos'><i class="fa fa-pencil"></i> Nuevo</button>
                                                        </p>
                                                    </div>
                                                    <div id="tipo_forma_pagos" title="Búsqueda de Forma de Pago" class="">
                                                        <table id="listForma_pagos">
                                                            <tr>
                                                                <td></td>
                                                            </tr>
                                                        </table>
                                                        <div id="pagerf"></div>
                                                    </div>
                                                </div><!-- /.tab-pane -->

                                                <div class="tab-pane" id="tab_7" style="height: 300px">
                                                    <div class="col-md-12">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Tipo Impuesto: </label>
                                                                <select class="form-control" name="tipo_impuesto" id="tipo_impuesto">
                                                                    <option value=" ">Seleccione el Tipo de Impuesto</option>
                                                                    <?php
                                                                    $consultapro = pg_query("select * from tipo_impuesto ");
                                                                    while ($row = pg_fetch_row($consultapro)) {
                                                                        echo "<option id=$row[0] value=$row[0]>$row[1]</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Nombre: </label>
                                                                <input type="text" name="nombre_tarifa_impuesto" id="nombre_tarifa_impuesto" class="form-control" />
                                                                <input type="hidden" name="id_taimpuesto" id="id_taimpuesto" readonly class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Descripción : </label>
                                                                <input type="text" name="descripcion_tarifa_impuesto" id="descripcion_tarifa_impuesto" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Código : </label>
                                                                <input type="number" name="codigo_tarifa_impuesto" id="codigo_tarifa_impuesto" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-mx-12">
                                                        <p>
                                                            <button class="btn bg-olive margin" id='btnGuardartarifa_impuesto'><i class="fa fa-save"></i> Guardar</button>
                                                            <button class="btn bg-olive margin" id='btnModificartarifa_impuesto'><i class="fa fa-edit"></i> Modificar</button>
                                                            <button class="btn bg-olive margin" id='btnEliminartarifa_impuesto'><i class="fa fa-remove"></i> Eliminar</button>
                                                            <button class="btn bg-olive margin" id='btnBuscartarifa_impuesto'><i class="fa fa-search"></i> Buscar</button>
                                                            <button class="btn bg-olive margin" id='btnNuevotarifa_impuesto'><i class="fa fa-pencil"></i> Nuevo</button>
                                                        </p>
                                                    </div>
                                                    <div id="tipo_tarifa_impuesto" title="Búsqueda de Forma de Pago" class="">
                                                        <table id="listTarifa_impuesto">
                                                            <tr>
                                                                <td></td>
                                                            </tr>
                                                        </table>
                                                        <div id="pager"></div>
                                                    </div>
                                                </div><!-- /.tab-pane -->

                                                <div class="tab-pane" id="tab_8" style="min-height: 300px;">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="">Logo de la empresa:</label>
                                                                    <div style="display: flex; justify-content: flex-start; align-items: center;">
                                                                        <input id="logo_empresa" type="file">
                                                                        <div id="mostrar_logo_empresa" style="border: 2px solid black; display:none">
                                                                        </div>
                                                                        <i id="btn_quiar_logo" class="fa fa-times" title="Quitar" style="font-size: 20px; color:red; cursor: pointer;"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr style="border: 1px solid black; margin-top: 8px;">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="col-md-4">
                                                                <h4>FIRMA ELECTRÓNICA</h4>
                                                                <div class="form-group">
                                                                    <!-- <label for="">Ubicación Aplicación Para Firmar:</label>
                                                                    <input class="form-control" id="app_firma" type="text"> -->
                                                                    <label for="">Archivo P12:</label>
                                                                    <input style="border: 1px solid blue" type="file" id="archivo_p12" name="archivo_p12">
                                                                    <div style="display: none;" id="mostrar_nombre_archivo_p12">
                                                                        <span id="nombre_archivo_p12" style="font-weight: bold; color:#455A64;  box-shadow: 0px 0px 4px 2px;"></span>
                                                                        <i id="btn_quiar_p12" class="fa fa-times" title="Quitar" style="font-size: 20px; color:red; cursor: pointer;"></i>
                                                                    </div>
                                                                    <label for="" style="margin-top: 15px;">Clave Firma:</label>
                                                                    <input class="form-control" type="password" id="clave_firma" name="clave_firma">
                                                                </div>
                                                                <h4>OPCIONES DE CORREO</h4>
                                                                <div class="form-group">
                                                                    <label for="">Host:</label>
                                                                    <input class="form-control" id="host_correo" type="text">
                                                                    <label for="">Usuario:</label>
                                                                    <input class="form-control" id="user_correo" type="text">
                                                                    <label for="">Clave:</label>
                                                                    <input class="form-control" id="pass_correo" type="password">
                                                                    <label for="">Puerto:</label>
                                                                    <input class="form-control" id="port_correo" type="text">
                                                                    <label for="">Seguridad SMTP:</label>
                                                                    <input class="form-control" id="secure_correo" type="text">
                                                                    <label for="">Correo Copia:</label>
                                                                    <input class="form-control" id="copia_correo" type="text">

                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <h4>FORMATO IMPRESIÓN FACTURA</h4>
                                                                <div class="form-group">
                                                                    <select name="formato_imperesion_factura" id="formato_imperesion_factura" class="form-control">
                                                                        <?php foreach ($formatosF as $val) { ?>
                                                                            <option value="<?php echo $val["id_formato"] ?>"><?php echo $val["nombre_formato"]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <h4>FORMATO IMPRESIÓN NOTA DE VENTA</h4>
                                                                <div class="form-group">
                                                                    <select name="formato_imperesion_nota" id="formato_imperesion_nota" class="form-control">
                                                                        <?php foreach ($formatosN as $val) { ?>
                                                                            <option value="<?php echo $val["id_formato"] ?>"><?php echo $val["nombre_formato"]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <h4>FORMATO IMPRESIÓN NOTA DE CRÉDITO</h4>
                                                                <div class="form-group">
                                                                    <select name="formato_imperesion_nota_credito" id="formato_imperesion_nota_credito" class="form-control">
                                                                        <?php foreach ($formatosNC as $val) { ?>
                                                                            <option value="<?php echo $val["id_formato"] ?>"><?php echo $val["nombre_formato"]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <h4>FORMATO IMPRESIÓN FACTURA COMPRA</h4>
                                                                <div class="form-group">
                                                                    <select name="formato_imperesion_factura_compra" id="formato_imperesion_factura_compra" class="form-control">
                                                                        <?php foreach ($formatosFC as $val) { ?>
                                                                            <option value="<?php echo $val["id_formato"] ?>"><?php echo $val["nombre_formato"]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <h4>FORMATO IMPRESIÓN RETENCIÓN COMPRA</h4>
                                                                <div class="form-group">
                                                                    <select name="formato_imperesion_retencion_compra" id="formato_imperesion_retencion_compra" class="form-control">
                                                                        <?php foreach ($formatosRC as $val) { ?>
                                                                            <option value="<?php echo $val["id_formato"] ?>"><?php echo $val["nombre_formato"]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                        <h4>FORMATO IMPRESIÓN RETENCIÓN GASTO</h4>
                                                                <div class="form-group">
                                                                    <select name="formato_imperesion_retencion_gasto" id="formato_imperesion_retencion_gasto" class="form-control">
                                                                        <?php foreach ($formatosRC as $val) { ?>
                                                                            <option value="<?php echo $val["id_formato"] ?>"><?php echo $val["nombre_formato"]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>


                                                                <!--                                                            ////////////
                                                                -->
                                                                <h4>TIPO CONTRIBUYENTE</h4>
                                                                <div class="col-md2">

                                                                    <select class="form-control" name="val_rimpe" id="val_rimpe">
                                                                        <option value="REGIMEN GENERAL">REGIMEN GENERAL</option>
                                                                        <option value="CONTRIBUYENTE RÉGIMEN RIMPE">CONTRIBUYENTE RÉGIMEN RIMPE</option>
                                                                        <option value="CONTRIBUYENTE NEGOCIO POPULAR - RÉGIMEN RIMPE">CONTRIBUYENTE NEGOCIO POPULAR - RÉGIMEN RIMPE</option>
                                                                    </select>

                                                                </div>
                                                                <div class="col-md4">
                                                                    <h4>AGENTE DE RETENCIÓN: <input type="checkbox" id="check_agente_reten"></h4>
                                                                </div>
                                                                <div id="id_agente_reten">
                                                                    <h4>CÓDIGO DE AGENTE DE RETENCIÓN</h4>
                                                                    <div class="col-md2">
                                                                        <input class="form-control" id="agente_reten" value="1"  type="text">

                                                                    </div>
                                                                </div>
                                                                <div id="id_agente_reten_resolucion">
                                                                    <h4>NÚMERO DE RESOLUCIÓN AGENTE DE RETENCIÓN</h4>
                                                                    <div class="col-md2">
                                                                        <input class="form-control" id="agente_reten_resolucion" value="Agente de Retención Mediante Resolución Nro. NAC-DNCRASC20-00000001"  type="text">
                                                                    </div>
                                                                </div>
                                                                <div id="id_agente_reten_resolucion">
                                                                    <h4>VALOR IVA%</h4>
                                                                    <div class="col-md2">
                                                                        <input class="form-control" id="valor_iva"   type="text">
                                                                    </div>
                                                                </div>
                                                                <button id="btn_guardar_parametrose_correo" type="button" class="btn bg-olive"><i class="fa fa-save"></i> Guardar</button>
                                                            </div>
                                                            <div class="col-md4">
                                                                <h4>AUTORIZAR FACTURA AUTOMÁTICAMENTE: <input type="checkbox" id="autorizar_fac_auto"></h4>
                                                            </div>
                                                            <div class="col-md4">
                                                                <h4>USAR APERTURA CAJA: <input type="checkbox" id="apertura_caja"></h4>
                                                            </div>


                                                            <div >
                                                                <h4>AL CREAR PRODUCTO:</h4>
                                                                <input type="radio" name="defecto_iva" id="defecto_iva1" ><span></span> IVA SI</span><br/>
                                                                <input type="radio" name="defecto_iva" id="defecto_iva2" ><span> IVA NO </span><br/><br/>
                                                            </div>


                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </form>
                                    </div><!-- /.tab-pane -->
                                </div><!-- /.tab-content -->

                            </div>
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

                        <div id="codigo_activacion" title="ACTIVACIÓN">
                            <table border="0">
                                <tr>
                                    <td><label>Ingrese el codigo de activación: </label></td>
                                    <td><input type="password" name="codigo" id="codigo" class="campo"></td>
                                </tr>
                            </table>
                            <div class="form-actions" align="center">
                                <button class="btn btn-primary" id='btnActivar'><i class="icon-ok"></i> Activar</button>
                                <button class="btn btn-primary" id='btnCancelarAct'><i class="icon-remove-sign"></i> Cancelar</button>
                            </div>
                        </div>
                        <div id="codigo_empresa" title="CÓDIGO">
                            <table border="0">
                                <tr>
                                    <td><label>Ingrese el codigo de la empresa: </label></td>
                                    <td><input type="password" name="codigo_emp" id="codigo_emp" class="campo"></td>
                                </tr>
                            </table>
                            <div class="form-actions" align="center">
                                <button class="btn btn-primary" id='btnActivarCod'><i class="icon-ok"></i> Activar</button>
                            </div>
                        </div>

                        <div id="seguro">
                            <label>¿Está seguro de que desea guardar los cambios?</label>
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
<script src="empresa.js" type="text/javascript"></script>
<script src="parametros_empresa.js" type="text/javascript"></script>
<link href="../../dist/css/style.css" rel="stylesheet" type="text/css" />
<script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>

</body>

</html>