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
            $("#iva_pr").change(function () {
                if ($("#iva_pr").val() == "1") {

                    $("#tarifa_pr").val("2");
                    $("#tarifa_pr").attr("readOnly", false);
                } else {
                    if ($("#iva_pr").val() == "4") {

                        $("#tarifa_pr").val("1");
                        $("#tarifa_pr").attr("readOnly", false);
                    }
                }
            });
            $("#tarifa_pr").change(function () {
                if ($("#tarifa_pr").val() == "1") {

                    $("#iva_pr").val("4");
                    $("#iva_pr").attr("readOnly", false);
                } else {
                    if ($("#tarifa_pr").val() == "2") {

                        $("#iva_pr").val("1");
                        $("#iva_pr").attr("readOnly", false);
                    }
                }
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
            $("#tarifa_pr").val(val);
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