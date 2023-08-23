$(document).ready(inicio);
var cmpAddCliente;
var tiposGasto = [];
var flatTiposGasto = [];
obtenerTiposGato();

function inicio() {
    $("#ruc_ci").validCampoFranz("0123456789");
    $("#descuento_producto").validCampoFranz("0123456789.");
    $("#precio_unitario").validCampoFranz("0123456789.");
    $("#cantidad").validCampoFranz("0123456789.");

    $('#fecha_actual').val(new Date().toLocaleDateString("fr-CA"));

    $("#ruc_ci").autocomplete({
        minLength: 1,
        focus: function (event, ui) {
            $("#ruc_ci").val(ui.item.value);
            $("#empresa").val(ui.item.empresa);
            $("#id_proveedor").val(ui.item.id_proveedor);
            return false;
        },
        select: function (event, ui) {
            $("#ruc_ci").val(ui.item.value);
            $("#empresa").val(ui.item.empresa);
            $("#id_proveedor").val(ui.item.id_proveedor);
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };

    $("#tipo_docu").change(function () {
        var tipo = $("#tipo_docu").val();
        $("#ruc_ci").autocomplete({
            source: "buscar_empresa.php?tipo_docu=" + tipo,
        });
        $("#ruc_ci").val("");
        $("#empresa").val("");
        $("#id_proveedor").val("");
        if (tipo == "Cedula") {
            $("#ruc_ci").attr("maxlength", "10");
        } else if (tipo == "Ruc") {
            $("#ruc_ci").attr("maxlength", "13");
        } else if (tipo == "Pasaporte") {
            $("#ruc_ci").attr("maxlength", "30");
        }
    });

    $("#dialog_form_cliente").dialog({
        modal: true,
        width: window.innerWidth - 180,
        height: window.innerHeight - 150,
        minHeight: 600,
        minHeight: 700,
        autoOpen: false,
        title: "REGISTRAR PROVEEDORES"
    });

    $("#btnClientes").click(function (e) {
        cmpAddCliente.resetForm();
        if (!!infofac) {
            cmpAddCliente.tipoDocu = "1";
            cmpAddCliente.rucCi = infofac.ruc;
            cmpAddCliente.empresa = infofac.razonSocial;
            cmpAddCliente.repLegal = infofac.razonSocial;
            cmpAddCliente.direccion = infofac.dirMatriz;
        }
        if ($("#ruc_ci").val() != "") {
            cmpAddCliente.resetForm();
        }
        $("#dialog_form_cliente").dialog("open")
    });

    $("#producto").keypress(function (e) {
        if (e.key == 'Enter') {
            if ($(this).val().trim() == "") {
                $(this).focus()
                alertify.error("Ingrese el nombre del producto");
                return;
            }
            $("#descuento_producto").focus();
        }
    });
    $("#descuento_producto").keypress(function (e) {
        if (e.key == 'Enter') {
            if ($("#producto").val().trim() == "") {
                $("#producto").focus()
                alertify.error("Ingrese el nombre del producto");
                return;
            }
            $("#precio_unitario").focus();
        }
    });
    $("#precio_unitario").keypress(function (e) {
        if (e.key == 'Enter') {
            if ($("#producto").val().trim() == "") {
                $("#producto").focus()
                alertify.error("Ingrese el nombre del producto");
                return;
            }
            if ($(this).val().trim() == "") {
                $(this).focus()
                alertify.error("Ingrese el precio unitario");
                return;
            }
            $("#cantidad").focus();
        }
    });
    $("#cantidad").keypress(function (e) {
        if (e.key == 'Enter') {
            if ($("#producto").val().trim() == "") {
                $("#producto").focus()
                alertify.error("Ingrese el nombre del producto");
                return;
            }
            if ($("#precio_unitario").val().trim() == "") {
                $("#precio_unitario").focus()
                alertify.error("Ingrese el prcio unitario del producto");
                return;
            }
            if ($(this).val().trim() == "") {
                $(this).focus()
                alertify.error("Ingrese el precio unitario");
                return;
            }

            let iva = Number($("#iva").val());
            let punitario = Number($("#precio_unitario").val());
            let descto = Number($("#descuento_producto").val());
            let cantidad = Number($("#cantidad").val());
            let nombrep = $("#producto").val().toUpperCase();
            let tipogasto = $("#tipo_gasto").val();
            let bienservicio = $("#bien_servicio").val();
            addProducto(punitario, descto, cantidad, iva, nombrep, tipogasto, bienservicio);
        }
    });

    show();
    addCliente();
    initTablaDocs();
}

function show() {
    var Digital = new Date();
    var hours = Digital.getHours();
    var minutes = Digital.getMinutes();
    var seconds = Digital.getSeconds();
    if (hours <= 9)
        hours = "0" + hours;
    if (minutes <= 9)
        minutes = "0" + minutes;
    if (seconds <= 9)
        seconds = "0" + seconds;
    $("#hora_actual").val(hours + ":" + minutes + ":" + seconds);

    setTimeout("show()", 1000);
}
function addCliente() {
    $.getScript("../proveedores/proveedores_ui_util/proveedores.js", function () {
        cmpAddCliente = new AddCliente();
        cmpAddCliente.contenedor = $("#form_cliente");
        cmpAddCliente.onGuardar = function (data) {
            if (!!data) {
                $("#dialog_form_cliente").dialog("close")
                buscarCliente(data).done(function (data) {
                    console.log(data);
                    $("#empresa").val(data[0].value);
                    $("#id_proveedor").val(data[0].label);
                    $("#ruc_ci").val(data[0].label1);
                });

            }
        };
        cmpAddCliente.init();
    });
}

function obtenerTiposGato() {
    $.ajax({
        url: "obtener_tipos_gasto_personal.php",
        method: "GET",
        dataType: "json",
        success: function (data) {
            tiposGasto = data;
            flatTiposGasto = [];
            for (let group in tiposGasto) {
                let items = tiposGasto[group];
                items.forEach(el => {
                    flatTiposGasto.push(el);
                });

            }
            llenarSelectTiposGasto($("#tipo_gasto"));
        }
    });
}

function llenarSelectTiposGasto(select, insertaropvacio = false, valseleccionado = null) {
    let selecttg = select;
    selecttg.empty();
    if (insertaropvacio) {
        let option = $(`<option ${valseleccionado == "" ? "selected" : ""} value="">--SELECCIONAR-</option>`);
        selecttg.append(option);
    }
    for (let group in tiposGasto) {
        let gselect = $(`<optgroup label="${group}"></optgroup>`);
        let items = tiposGasto[group];
        items.forEach(el => {
            let option = $(`<option ${valseleccionado == el.id_tipo_gasto ? "selected" : ""} value="${el.id_tipo_gasto}">${el.nombre}</option>`);
            gselect.append(option);
        });
        selecttg.append(gselect);
    }
}

function initTablaDocs() {
    $("#list").jqGrid({
        dataType: "local",
        colNames: [
            "",
            "PRODUCTO",
            "BIEN/SERVICIO",
            "IVA %",
            "DESCUENTO",
            "P.UNITARIO",
            "CANTIDAD",
            "TOTAL",
            "TIPO GASTO",
            "id_tipo_gasto"
        ],
        colModel: [
            { name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: { keys: false, delbutton: true, editbutton: false } },
            {
                name: "producto",
                index: "producto",
                width: 200
            },
            {
                name: "bien_serivicio",
                index: "bien_serivicio",
                width: 100,
                editable: true,
                edittype: 'custom',
                editoptions: {
                    custom_element: selectBienservicioTabla,
                    custom_value: valueselectBienservicioTabla
                },
            },
            {
                name: "iva",
                index: "iva",
                width: 100
            },
            {
                name: "descuento",
                index: "descuento"
            },
            {
                name: "p_unitario",
                index: "p_unitario",
                width: 100
            },
            {
                name: "cantidad",
                index: "cantidad",
                width: 100
            },
            {
                name: "total",
                index: "total",
            },
            {
                name: "tipo_gasto",
                index: "tipo_gasto",
                editable: true,
                edittype: 'custom',
                editoptions: {
                    custom_element: selectTipoGastoTabla,
                    custom_value: valueselectTipoGastoTabla
                },
                formatter: function (cellvalue, options, rowObject) {
                    if (cellvalue == "" || cellvalue == "undefined") {
                        return "";
                    }
                    let tg = flatTiposGasto.find(el => el.id_tipo_gasto == cellvalue);
                    let nombre = "";
                    if (!!tg) {
                        nombre = tg.nombre;
                    }
                    return nombre;
                }
            },
            {
                name: "id_tipo_gasto",
                index: "id_tipo_gasto",
                hidden: true
            }
        ],
        width: (window.innerWidth - 300 < 600) ? 600 : window.innerWidth - 300,
        rownumbers: true,
        height: 300,
        afterSaveCell: function (rowid, cellname, value, iRow, iCol) {
            console.log(value);
        },
        delOptions: {
            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {

                var id = jQuery("#list").jqGrid('getGridParam', 'selrow');
                jQuery('#list').jqGrid('restoreRow', id);
                var ret = jQuery("#list").jqGrid('getRowData', id);

                var su = jQuery("#list").jqGrid('delRowData', rowid);
                if (su == true) {
                    calcularTotales();
                    rp_ge.processing = true;
                    $(".ui-icon-closethick").trigger('click');
                }
                return true;
            },
            processing: true
        },
        cellEdit: true,
        cellsubmit: 'clientArray',
        onSelectRow: function (id) {
            if (id && id !== lastsel) {
                jQuery('#list').jqGrid('restoreRow', lastsel);
                jQuery('#list').jqGrid('editRow', id, true);
                lastsel = id;
            }
        },
        afterSaveCell: function (rowid, cellname, value, iRow, iCol) {
            if (cellname == "tipo_gasto") {
                jQuery("#list").jqGrid('setRowData', rowid, {
                    id_tipo_gasto: value
                });
            }
        },
        /*  pager: jQuery('#pager_docs'), */
    })
    /* .jqGrid('navGrid', '#pager_docs', {
        add: false,
        edit: false,
        del: false,
        refresh: true,
        search: false,
        // multipleSearch: true,
        view: false
    }); */
    $(window).off('resize');
    $(window).on('resize', function () {

        $('#list').jqGrid('setGridWidth', (window.innerWidth - 300 < 600) ? 600 : window.innerWidth - 300);
    }).trigger('resize');
}

function addProducto(punitario, descto, cantidad, iva, nombrep, tipogasto, bienservicio) {
    punitario = Number(punitario);
    descto = Number(descto);
    cantidad = Number(cantidad);
    iva = Number(iva);

    let total = punitario * cantidad;
    if (descto > 0) {
        total -= descto;
    }

    let row = {
        producto: nombrep,
        bien_serivicio: bienservicio,
        iva: iva,
        descuento: descto,
        p_unitario: punitario,
        cantidad: cantidad,
        total: total,
        tipo_gasto: tipogasto
    };
    let id = 1;
    let ids = $("#list").jqGrid('getDataIDs');

    if (ids.length > 0) {
        id = Number(ids[ids.length - 1]) + 1;
    }
    $("#list").jqGrid('addRowData', id, row);
    $("#producto").focus();
    limpiarCamposProducto();
    calcularTotales();
}

function limpiarCamposProducto() {
    $("#producto").val("");
    $("#bien_servicio").val("B");
    $("#descuento_producto").val("");
    $("#precio_unitario").val("");
    $("#cantidad").val("");
    $("#iva").val("0");
    $("#tipo_gasto").val($("#tipo_gasto")[0].options[0].value);
}

function calcularTotales() {
    let datos = jQuery("#list").jqGrid('getRowData');
    let tarifa0 = 0;
    let tarifa12 = 0;
    let subtotal = 0;
    let iva = 0;
    let descuento = 0;
    datos.forEach(el => {
        if (el.iva > 0) {
            tarifa12 += Number(el.total);
            iva += calculoIva(el.total, el.iva);
        }
        if (el.iva == 0) {
            tarifa0 += Number(el.total);
        }
        if (el.descuento > 0) {
            descuento += Number(el.descuento);
        }
        subtotal += Number(el.total);
    });
    let total = subtotal + iva;

    $("#total_p").val(tarifa0);
    $("#total_p2").val(tarifa12);
    $("#sub").val(subtotal);
    $("#iva").val(iva);
    $("#des").val(descuento);
    $("#tot").val(total);

    $("#total_px").val(tarifa0.toFixed(2));
    $("#total_p2x").val(tarifa12.toFixed(2));
    $("#subx").val(subtotal.toFixed(2));
    $("#ivax").val(iva.toFixed(2));
    $("#descx").val(descuento.toFixed(2));
    $("#totx").val(total.toFixed(2));
}

function calculoIva(valor, iva) {
    iva = Number(iva);
    valor = Number(valor);

    let viva = 0;
    if (iva > 0) {
        viva = valor * (iva / 100);
    }
    return viva;
}

function selectBienservicioTabla(value, options) {
    let control = $(`<select style="width:100%"></select>`);
    let option1 = $(`<option ${value == "" ? "selected" : ""} value="">--SELECCIONAR--</option>`);
    let option2 = $(`<option ${value == "B" ? "selected" : ""} value="B">Bien</option>`);
    let option3 = $(`<option ${value == "S" ? "selected" : ""} value="S">Servicio</option>`);
    control.append([option1, option2, option3]);
    return control;
}
function valueselectBienservicioTabla(value) {
    return value.val();
}
function selectTipoGastoTabla(value, options) {
    let control = $(`<select style="width:100%"></select>`);
    llenarSelectTiposGasto(control, true, value);
    return control;
}
function valueselectTipoGastoTabla(value) {
    return value.val();
}