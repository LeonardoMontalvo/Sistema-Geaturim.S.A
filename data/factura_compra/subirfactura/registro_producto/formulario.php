<?php
session_start();
include_once __DIR__ . "/../../../../procesos/base.php";
include_once __DIR__ . "/../../../../procesos/configuracion.php";
$conf = new Configuracion();
$defecto_iva = $conf->getParametroEmpresa("defecto_iva");
?>
<style>
    #form_producto *:required {
        border: 1px dashed red;
    }

    #cod_barras {
        text-transform: uppercase;
    }
</style>
<div>
    <form action="" id="form_producto">
        <div class="row">
            <div class="col-md-4">
                <label for="cod_prod">Código Producto:</label>
                <input required class="form-control" type="text" id="cod_prod" name="cod_prod">
            </div>
            <div class="col-md-4">
                <label for="cod_barras">Código Barras:</label>
                <input required class="form-control" type="text" id="cod_barras" name="cod_barras">
            </div>
            <div class="col-md-4">
                <label for="minimo">Stock Mínimo:</label>
                <input value="1" required class="form-control" type="text" id="minimo" name="minimo">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label for="nombre_art">Nombre Artículo:</label>
                <input style="text-transform: uppercase;" required class="form-control" type="text" id="nombre_art" name="nombre_art">
            </div>
            <div class="col-mx-8">
                <div class="col-md-2">
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
                <div class="col-md-2">
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
            <div class="col-md-4">
                <label for="maximo">Stock Máximo:</label>
                <input value="1" required class="form-control" type="text" id="maximo" name="maximo">
            </div>
        </div>
        <div class="row">

            <div class="col-mx-8">
                <div class="col-md-2">
                    <label for="precio_minorista">PVP Minorista:</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <b>$</b>
                        </div>
                        <input required class="form-control" type="text" id="precio_minorista" name="precio_minorista" placeholder="0.0000">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="precio_minorista_final">PVP Minorista final: </label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-usd"></i>
                            </div>
                            <input type="text" name="precio_minorista_final" id="precio_minorista_final" placeholder="0.0000" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-mx-8">
                <div class="col-md-2">
                    <label for="precio_mayorista">PVP Mayorista:</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <b>$</b>
                        </div>
                        <input required class="form-control" type="text" id="precio_mayorista" name="precio_mayorista" placeholder="0.0000">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="precio_mayorista_final">PVP Mayorista final: </label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-usd"></i>
                            </div>
                            <input type="text" name="precio_mayorista_final" id="precio_mayorista_final" class="form-control" placeholder="0.0000" />
                        </div>
                    </div>
                </div>

            </div>


            <div class="col-md-4">
                <label for="precio_negocio">PVP Negocio:</label>
                <div class="input-group">
                    <div class="input-group-addon">
                        <b>$</b>
                    </div>
                    <input class="form-control" type="text" id="precio_negocio" name="precio_negocio" placeholder="0.0000">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label for="utilidad_minorista">Utilidad Minorista:</label>
                <div class="input-group">
                    <div class="input-group-addon">
                        <b>%</b>
                    </div>
                    <input class="form-control" type="text" id="utilidad_minorista" name="utilidad_minorista" placeholder="0.00">
                </div>
            </div>
            <div class="col-md-4">
                <label for="utilidad_mayorista">Utilidad Mayorista:</label>
                <div class="input-group">
                    <div class="input-group-addon">
                        <b>%</b>
                    </div>
                    <input class="form-control" type="text" id="utilidad_mayorista" name="utilidad_mayorista" placeholder="0.00">
                </div>
            </div>
            <div class="col-md-4">
                <label for="utilidad_negocio">Utilidad Negocio:</label>
                <div class="input-group">
                    <div class="input-group-addon">
                        <b>%</b>
                    </div>
                    <input class="form-control" type="text" id="utilidad_negocio" name="utilidad_negocio" placeholder="0.00">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label>Cantidad para Mayorista: </label>
                <input type="number" name="cantidad_mayorista" id="cantidad_mayorista" value="0" class="form-control" value="0" />
            </div>
            <div class="col-md-4">
                <label>Cantidad para Negocio: </label>
                <input type="number" name="cantidad_negocio" id="cantidad_negocio" value="0" class="form-control" value="0" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label>Categoría:</label>
                <div class="input-group">
                    <input type="text" name="categoria" id="categoria" placeholder="Buscar....." class="form-control" value="" />
                    <input type="hidden" name="id_categoria" id="id_categoria" required class="form-control" />
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" id="btnCategoria" style="font-size: medium;">Agregar</button>
                    </span>
                </div>
            </div>
            <div class="col-md-4">
                <label>Marca: </label>
                <div class="input-group">
                    <input type="text" name="marca" id="marca" placeholder="Buscar....." class="form-control" value="" />
                    <input type="hidden" name="id_marca" id="id_marca" required class="form-control" />
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" id="btnMarca" style="font-size: medium;">Agregar</button>
                    </span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label> Precio Venta Contiene Iva:</label>
                    <select class="form-control" name="iva" id="iva_pr">
                        <?php
                        echo 'defecto//' . $defecto_iva;
                        $consultaimpu = pg_query("select * from tipo_impuesto where id_timpu=1 ORDER BY id_timpu  ASC");
                        while ($row = pg_fetch_row($consultaimpu)) {
                            echo "<option selected id='optimpu_$row[0]'  value=$row[0]>$row[1]</option>";
                        }
                        ?>
                    </select>
                    <select class="form-control" name="tarifa" id="tarifa_pr">
                        <?php
                        $consultatarifa = pg_query("select * from tarifa_impuesto where estado='Activo' ORDER BY id_taimpuesto  ASC");
                        while ($row = pg_fetch_assoc($consultatarifa)) {
                            $opt = "<option data-cod='$row[codigo_taimpuesto]' data-valor='$row[valor]' value='$row[id_taimpuesto]'>$row[nombre_taimpuesto]</option>";
                            if (trim($defecto_iva) == "No" && $row["id_taimpuesto"] == 1) {
                                $opt = "<option data-cod='$row[codigo_taimpuesto]' data-valor='$row[valor]' selected value='$row[id_taimpuesto]'>$row[nombre_taimpuesto]</option>";
                            } else if (trim($defecto_iva) == "Si" && $row["id_taimpuesto"] == 6) {
                                $opt = "<option data-cod='$row[codigo_taimpuesto]' data-valor='$row[valor]' selected value='$row[id_taimpuesto]'>$row[nombre_taimpuesto]</option>";
                            }
                            echo $opt;
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label>BIENES/SERVICIOS.</label>
                <select class="form-control" name="bien_servicio" id="bien_servicio">
                    <option value="B" selected="">Bienes</option>
                    <option value="S">Servicio</option>
                </select>
            </div>
            <div class="col-md-4">
                <label>Cuenta Contable: *</label>
                <input type="text" name="ccontable" id="ccontable" placeholder="Buscar...." required class="form-control" disabled />
                <input required type="hidden" name="idcontable" id="idcontable" />
                <button type="button" class="btn btn-default" id="btnCuentaPr1" name="btnCuentaPr1" style="visibility:hidden"></button>
                <button type="button" class="btn btn-default" id="btnCuentaPr" name="btnCuentaPr">Seleccionar
                    Cuenta</button>
            </div>
            <div class="col-md-4">
                <label>Inventariable:</label>
                <select class="form-control" name="inventario" id="inventario">
                    <option id="inven_si" value="Si" selected>Si</option>
                    <option id="inven_no" value="No">No</option>
                </select>
            </div>
        </div>
        <input type="hidden" name="series" id="series" placeholder="buscar..." value="No" class="form-control" />
    </form>
    <div id="dialog_categoria">
        <div class="row">
            <div class="col-md-12">
                <label for="">Nombre Categoria:</label>
                <input required id="nombre_categoria" class="form-control" type="text">
            </div>
        </div>
    </div>

    <div id="dialog_marca">
        <div class="row">
            <div class="col-md-12">
                <label for="">Nombre Marca:</label>
                <input required id="nombre_marca" class="form-control" type="text">
            </div>
        </div>
    </div>

    <div id="cuentasPr" title="Búsqueda Plan de Cuentas" class="">
        <table id="tblpcuentas"></table>
        <div id="pagertblcuentas"></div>
    </div>
</div>