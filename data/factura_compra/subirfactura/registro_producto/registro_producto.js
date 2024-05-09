function RegistroProducto(contenedor) {

    const agregar_categoria = () => {
        if ($("#nombre_categoria").val() == "") {
            $("#nombre_categoria").focus();
            alertify.error("Nombre Categoria");
        } else {
            $.ajax({
                type: "POST",
                url: "../productos/guardar_categoria.php",
                data: "nombre_categoria=" + $("#nombre_categoria").val(),
                success: function (data) {
                    var val = data;
                    if (val == 1) {
                        $("#nombre_categoria").val("");
                    } else {
                        $("#nombre_categoria").val("");
                        alertify.error("Error.... La categoría ya existe");
                    }
                }
            });
        }
    }
    const agregar_marca = () => {
        if ($("#nombre_marca").val() == "") {
            $("#nombre_marca").focus();
            alertify.error("Nombre Laboratorio");
        } else {
            $.ajax({
                type: "POST",
                url: "../productos/guardar_marca.php",
                data: "nombre_marca=" + $("#nombre_marca").val(),
                success: function (data) {
                    var val = data;
                    if (val == 1) {
                        $("#nombre_marca").val("");
                    } else {
                        $("#nombre_marca").val("");
                        alertify.error("Error.... El Laboratorio ya existe");
                    }
                }
            });
        }
    }
    const validarFormulario = () => {
        let form = $("#form_producto")[0];
        return form.reportValidity()
    }
    const init = () => {
        contenedor.load("subirfactura/registro_producto/formulario.php", function () {
            $.when(
                $.getScript("../../plugins/input-mask/jquery.inputmask.js"),
                $.getScript("../../plugins/input-mask/jquery.inputmask.date.extensions.js"),
                $.getScript("../../plugins/input-mask/jquery.inputmask.extensions.js"),
                $.getScript("../../dist/js/decimales.js"),
                $.Deferred(function (deferred) {
                    $(deferred.resolve);
                }))
                .then(function () {
                    inputmaskDecimal("#precio_compra", false, 4);
                    inputmaskDecimal("#precio_minorista", false, 4);
                    inputmaskDecimal("#precio_mayorista", false, 4);
                    inputmaskDecimal("#precio_negocio", false, 4);
                    inputmaskDecimal("#utilidad_minorista", false, 4);
                    inputmaskDecimal("#utilidad_mayorista", false, 4);
                    inputmaskDecimal("#utilidad_negocio", false, 4);
                    inputmaskDecimal("#minimo", false, 4);
                    inputmaskDecimal("#maximo", false, 4);

                    let valtarifa = $("#tarifa_pr")[0].selectedOptions[0].dataset.valor;
                    calculoIVA = valtarifa;

                    $("#tarifa_pr").change(function () {
                        $("#precio_minorista_final").val("");
                        $("#precio_mayorista_final").val("");
                        let valtarifa = $("#tarifa_pr")[0].selectedOptions[0].dataset.valor;
                        calculoIVA = valtarifa;
                    });
                });

            var dialogo_cuenta = {
                autoOpen: false,
                resizable: false,
                width: 800,
                height: 400,
                modal: true,
                show: "explode",
                hide: "blind"
            }

            tablaPlanCuentas();

            $("#form_producto").submit(function (e) {
                e.preventDefalut();
                e.stopPropagation();
            });
            $("#cuentasPr").dialog(dialogo_cuenta);
            $("#dialog_categoria").dialog({
                modal: true,
                width: 350,
                height: 200,
                autoOpen: false,
                title: "REGISTRAR CATEGORÍA",
                buttons: [
                    {
                        text: "Guardar",
                        icon: "ui-icon-heart",
                        style: "background:#4CAF50; color:#fff",
                        type: "button",
                        click: function () {
                            agregar_categoria();
                            $(this).dialog("close");
                        }

                        // Uncommenting the following line would hide the text,
                        // resulting in the label being used as a tooltip
                        //showText: false
                    }
                ],
            });
            $("#dialog_marca").dialog({
                modal: true,
                width: 350,
                height: 200,
                autoOpen: false,
                title: "REGISTRAR MARCA",
                buttons: [
                    {
                        text: "Guardar",
                        icon: "ui-icon-heart",
                        style: "background:#4CAF50; color:#fff",
                        type: "button",
                        click: function () {
                            agregar_marca();
                            $(this).dialog("close");
                        }

                        // Uncommenting the following line would hide the text,
                        // resulting in the label being used as a tooltip
                        //showText: false
                    }
                ],
            });
            $("#btnMarca").click(function (e) {
                $("#dialog_marca").dialog("open");
            });
            $("#btnCategoria").click(function (e) {
                $("#dialog_categoria").dialog("open");
            });
            $("#btnMarca").click(function (e) {
                $("#dialog_marca").dialog("open");
            });

            $("#precio_minorista_final").keyup(function (e) {
                if (e.key == 'Enter') {
                    return;
                }
                let precioci = Number(e.target.value);
                let preciosi = precioci / (1 + (calculoIVA / 100));
                console.log("pvpfinal");
                $("#precio_minorista").val(preciosi);

            });
            $("#precio_mayorista_final").keyup(function (e) {
                if (e.key == 'Enter') {
                    return;
                }
                let precioci = Number(e.target.value);
                let preciosi = precioci / (1 + (calculoIVA / 100));

                $("#precio_mayorista").val(preciosi);

            });
            $("#btnCuentaPr").click(function (e) {
                $("#cuentasPr").dialog("open");
            });
            $.ajax({
                type: "POST",
                url: "../productos/extraer_cuenta_producto.php",
                data: "",
                success: function (data) {
                    var val = data;
                    if (val != "") {
                        var vec = val.split("/");
                        $("#idcontable").val(vec[0]);
                        $("#ccontable").val(vec[1] + "  -  " + vec[2]);
                    }
                }
            });
            $("#categoria").autocomplete({
                source: "../productos/buscar_categoria.php",
                minLength: 1,
                focus: function (event, ui) {
                    $("#categoria").val(ui.item.value);
                    $("#id_categoria").val(ui.item.id_categoria);
                    return false;
                },
                select: function (event, ui) {
                    $("#categoria").val(ui.item.value);
                    $("#id_categoria").val(ui.item.id_categoria);
                    return false;
                }
            }).data("ui-autocomplete")._renderItem = function (ul, item) {
                return $("<li>")
                    .append("<a>" + item.value + "</a>")
                    .appendTo(ul);
            };
            $("#marca").autocomplete({
                source: "../productos/buscar_marca.php",
                minLength: 1,
                focus: function (event, ui) {
                    $("#marca").val(ui.item.value);
                    $("#id_marca").val(ui.item.id_marca);
                    return false;
                },
                select: function (event, ui) {
                    $("#marca").val(ui.item.value);
                    $("#id_marca").val(ui.item.id_marca);
                    return false;
                }
            }).data("ui-autocomplete")._renderItem = function (ul, item) {
                return $("<li>")
                    .append("<a>" + item.value + "</a>")
                    .appendTo(ul);
            };

            $("#precio_minorista").change(function () {
                porcentamino();
            });
            $("#precio_mayorista").change(function () {
                porcentamayo();
            });
            $("#precio_negocio").change(function () {
                porcentanego();
            });
            $("#utilidad_minorista").change(function () {
                porcenta();
            });
            $("#utilidad_mayorista").change(function () {
                porcenta2();
            });
            $("#utilidad_negocio").change(function () {
                porcenta3();
            });
        });
    }
    const validarRegistroProducto = async (codprod, codbarras) => {
        return await $.ajax({
            url: "./subirfactura/registro_producto/validar_registro_producto.php",
            method: "POST",
            dataType: "json",
            data: {
                "cod_prod": codprod,
                "cod_barras": codbarras
            }
        });
    };
    const guardarProducto = async () => {
        const date = new Date();
        const formdata = new FormData($("#form_producto")[0]);
        formdata.set("cod_prod", formdata.get("cod_prod").toUpperCase());
        formdata.set("cod_barras", formdata.get("cod_barras").toUpperCase());
        formdata.set("fecha_creacion", date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate())
        formdata.set("proveedor", $("#id_proveedor").val());

        const validar = await validarRegistroProducto(formdata.get("cod_prod"), formdata.get("cod_barras"));

        alertify.set({ delay: 2000 });
        if (validar == -1) {
            alertify.error("El código de producto ya está registrado.");
            $("#cod_prod").focus();
            return;
        }
        if (validar == -2) {
            alertify.error("El código de barras está registrado.");
            $("#cod_barras").focus();
            return;
        }

        return await fetch("../productos/guardar_productos.php", {
            method: "POST",
            body: formdata
        }).then(res => {
            return res.json();
        }).then(json => {
            console.log(json);
            return formdata.get("cod_barras");
        });
    };
    const limpiarFormulario = () => {
        $("#form_producto")[0].reset();
        $.ajax({
            type: "POST",
            url: "../productos/extraer_cuenta_producto.php",
            data: "",
            success: function (data) {
                var val = data;
                if (val != "") {
                    var vec = val.split("/");
                    $("#idcontable").val(vec[0]);
                    $("#ccontable").val(vec[1] + "  -  " + vec[2]);
                }
            }
        });
    }

    function porcentamino() {
        if ($("#utilidad_minorista").val() == "") {
            var var_precio_compra = parseFloat($("#precio_compra").val());
            var var_utili_mino = parseFloat($("#precio_minorista").val());
            var multi = var_precio_compra;
            var val = var_utili_mino / multi;
            var entero = val.toFixed(2);
            var resulente = entero * 100 - 100;
            var resulente = resulente.toFixed(2);
            $("#utilidad_minorista").val(resulente);
        } else {
            alertify.error("UTILIDAD MINORISTA: Ya tiene valor")
        }
    }

    function porcentamayo() {
        if ($("#utilidad_mayorista").val() == "") {
            var var_precio_compra = parseFloat($("#precio_compra").val());
            var var_utili_mino = parseFloat($("#precio_mayorista").val());
            var multi = var_precio_compra
            var val = var_utili_mino / multi;
            var entero = val.toFixed(2);
            var resulente = entero * 100 - 100;
            var resulente = resulente.toFixed(2);
            $("#utilidad_mayorista").val(resulente);
        } else {
            alertify.error("UTILIDAD MAYORISTA: Ya tiene valor")
        }
    }

    function porcentanego() {
        if ($("#utilidad_negocio").val() == "") {
            var var_precio_compra = parseFloat($("#precio_compra").val());
            var var_utili_mino = parseFloat($("#precio_negocio").val());
            var multi = var_precio_compra
            var val = var_utili_mino / multi;
            var entero = val.toFixed(2);
            var resulente = entero * 100 - 100;
            var resulente = resulente.toFixed(2);
            $("#utilidad_negocio").val(resulente);
        } else {
            alertify.error("UTILIDAD NEGOCIO: Ya tiene valor")
        }
    }

    function porcenta() {
        if ($("#precio_minorista").val() == "") {
            var var_precio_compra = parseFloat($("#precio_compra").val());
            var var_utili_mino = parseFloat($("#utilidad_minorista").val());
            var var_iva = parseFloat($("#valor_iva_pro").val());
            var cal_porcent = (var_utili_mino + 100) / 100;
            var cal_iva = (var_iva + 100) / 100;
            var val = var_precio_compra * cal_porcent;
            var entero = val.toFixed(4);
            $("#precio_minorista").val(entero);
        } else {
            alertify.error("PVP Minorista: Ya tiene valor")
        }
    }

    function porcenta2() {
        if ($("#precio_mayorista").val() == "") {
            var var_precio_compra_may = parseFloat($("#precio_compra").val());
            var var_utili_mino_may = parseFloat($("#utilidad_mayorista").val());
            var var_iva_may = parseFloat($("#valor_iva_pro").val());
            var cal_porcent_may = (var_utili_mino_may + 100) / 100;
            var cal_iva_may = (var_iva_may + 100) / 100;
            var val_may = var_precio_compra_may * cal_porcent_may;
            var entero_may = val_may.toFixed(4);
            $("#precio_mayorista").val(entero_may);
        } else {
            alertify.error("PVP Mayorista: Ya tiene valor")
        }

    }

    function porcenta3() {
        if ($("#precio_negocio").val() == "") {
            var var_precio_compra_may = parseFloat($("#precio_compra").val());
            var var_utili_mino_may = parseFloat($("#utilidad_negocio").val());
            var var_iva_may = parseFloat($("#valor_iva_pro").val());
            var cal_porcent_may = (var_utili_mino_may + 100) / 100;
            var cal_iva_may = (var_iva_may + 100) / 100;
            var val_may = var_precio_compra_may * cal_porcent_may;
            var entero_may = val_may.toFixed(4);
            $("#precio_negocio").val(entero_may);
        } else {
            alertify.error("PVP Negocio: Ya tiene valor")
        }
    }

    init();
    return {
        validarFormulario,
        guardarProducto,
        limpiarFormulario,
        set codProducto(val) {
            $("#cod_prod").val(val);
        },
        set codBarraProcuto(val) {
            $("#cod_barras").val(val);
        },
        set nombreProducto(val) {
            $("#nombre_art").val(val);
        },
        set precioProductoSinIva(val) {
            $("#precio_compra").val(val);
        },
        set tarifaIvaProducto(val) {
            for (let el of $("#tarifa_pr")[0].options) {
                if (el.dataset.cod == val) {
                    el.selected = true;
                }
            }
        }
    }
}

function tablaPlanCuentas() {
    jQuery("#tblpcuentas").jqGrid({
        url: '../productos/xmlPlanCuentas.php',
        datatype: 'xml',
        colNames: ['Cod. Cuenta', 'Descripcion', 'Cuenta'],
        colModel: [
            { name: 'id_plan_cuentas', index: 'id_plan_cuentas', editable: true, align: 'left', width: '120', search: true, frozen: true, formoptions: { elmsuffix: " (*)" }, editrules: { required: true } },
            { name: 'descripcion', index: 'descripcion', editable: true, align: 'left', width: '490', search: true, frozen: true, formoptions: { elmsuffix: " (*)" }, editrules: { required: true } },
            { name: 'cuenta', index: 'cuenta', editable: true, align: 'left', width: '120', search: true, frozen: true, formoptions: { elmsuffix: " (*)" }, editrules: { required: true } }
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        height: 255,
        pager: jQuery('#pagertblcuentas'),
        sortname: 'cuenta',
        shrinkToFit: false,
        sortordezr: 'asc',
        caption: 'Plan de Cuentas',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#tblpcuentas").jqGrid('getGridParam', 'selrow');
            jQuery('#tblpcuentas').jqGrid('restoreRow', id);
            var ret = jQuery("#tblpcuentas").jqGrid('getRowData', id);
            var ccuenta = jQuery("#tblpcuentas").jqGrid('getCell', id, 0) + "  -  " + jQuery("#tblpcuentas").jqGrid('getCell', id, 1);
            $("#idcontable").val(id);
            $("#ccontable").val(ccuenta);
            document.getElementById("idcontable").readOnly = true;
            $("#cuentasPr").dialog("close");
        }
    }).jqGrid('navGrid', '#pagertblcuentas',
        {
            add: false,
            edit: false,
            del: false,
            refresh: true,
            search: true,
            view: false
        },
        {
            recreateForm: true, closeAfterEdit: true, checkOnUpdate: true, reloadAfterSubmit: true, closeOnEscape: true
        },
        {
            reloadAfterSubmit: true, closeAfterAdd: true, checkOnUpdate: true, closeOnEscape: true,
            bottominfo: "Los campos marcados con (*) son obligatorios", width: 350, checkOnSubmit: false
        },
        {
            width: 300, closeOnEscape: true
        },
        {
            closeOnEscape: true,
            multipleSearch: false, overlay: false
        },
        {
            closeOnEscape: true,
            width: 400
        },
        {
            closeOnEscape: true
        });
}