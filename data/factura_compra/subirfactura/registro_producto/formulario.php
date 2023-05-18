<?php
session_start();
include_once __DIR__ . "/../../../../procesos/base.php";
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
            <div class="col-md-4">
                <label for="precio_compra">Precio Compra:</label>
                <div class="input-group">
                    <div class="input-group-addon">
                        <b>$</b>
                    </div>
                    <input required class="form-control" type="text" id="precio_compra" name="precio_compra" placeholder="0.0000">
                </div>

            </div>
            <div class="col-md-4">
                <label for="maximo">Stock Máximo:</label>
                <input value="1" required class="form-control" type="text" id="maximo" name="maximo">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label for="precio_minorista">PVP Minorista:</label>
                <div class="input-group">
                    <div class="input-group-addon">
                        <b>$</b>
                    </div>
                    <input required class="form-control" type="text" id="precio_minorista" name="precio_minorista" placeholder="0.0000">
                </div>
            </div>
            <div class="col-md-4">
                <label for="precio_mayorista">PVP Mayorista:</label>
                <div class="input-group">
                    <div class="input-group-addon">
                        <b>$</b>
                    </div>
                    <input required class="form-control" type="text" id="precio_mayorista" name="precio_mayorista" placeholder="0.0000">
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
                    <label> Precio Venta Contiene Iva (SI/12%||NO/0%):</label>
                    <select class="form-control" name="iva" id="iva_pr">
                        <?php
                        $consultaimpu = pg_query("select * from tipo_impuesto where id_timpu=1 or id_timpu=4 ORDER BY id_timpu  ASC");
                        while ($row = pg_fetch_row($consultaimpu)) {
                            if ($row[0] == 1) {
                                echo "<option selected value=$row[0]>$row[1]</option>";
                            } else {
                                echo "<option value=$row[0]>$row[1]</option>";
                            }
                        }
                        ?>
                    </select>
                    <select class="form-control" name="tarifa" id="tarifa_pr">
                        <?php
                        $consultatarifa = pg_query("select * from tarifa_impuesto where id_taimpuesto=1 or id_taimpuesto=2 ORDER BY id_taimpuesto  ASC");
                        while ($row = pg_fetch_row($consultatarifa)) {
                            if ($row[0] == 2) {
                                echo "<option selected value=$row[0]>$row[3]</option>";
                            } else {
                                echo "<option value=$row[0]>$row[3]</option>";
                            }
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
                <button class="btn btn-default" id="btnCuentaPr1" name="btnCuentaPr1" style="visibility:hidden"></button>
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