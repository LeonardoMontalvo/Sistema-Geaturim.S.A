$(document).ready(inicio);
var cmpAddCliente;
var tiposGasto = [];
var flatTiposGasto = [];
var idGastoSeleccionado = 0;
var iSelectedRow = null;
var iSelectedCol = null;
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
            addProducto(punitario, descto, cantidad, iva, nombrep, tipogasto, bienservicio, tipogasto);
        }
    });

    $("#btnGuardar").click(function (e) {
        e.preventDefault();
        let form = $("#gastosp_form")[0];
        if (!form.reportValidity()) {
            return;
        }
        if (!validarItemsTabla()) {
            return;
        }
        guardarGasto(obtenerDatosFacturaGuardar());
    });
    $("#btnBuscar").click(function (e) {
        $("#buscar_gastos").dialog("open");
    });
    $("#btnAtras").click(function (e) {
        flechaAtras();
    });
    $("#btnAdelante").click(function (e) {
        flechaAdelante();
    });
    $("#btnNuevo").click(function (e) {
        location.reload();
    });
    $("#btnEliminar").click(function (e) {
        if (idGastoSeleccionado == 0) {
            alertify.alert("<b>Debe seleccionar un gasto para eliminar.</b>");
            return;
        }
        $("#clave_permiso").dialog("open");
    });
    $("#btnAcceder").click(function (e) {
        validar_acceso();
    });
    $("#btnSalir").click(function (e) {
        $("#seguro").dialog("close");
    });
    $("#btnAceptar").click(function (e) {
        aceptarEliminar();
    });

    show();
    addCliente();
    initTablaDocs();
    initTablaBusgarGastos();
    initDialogBuscarGasto();
    initDialogClavePermiso();
    initDialogSeguro();
}

function show() {
    if (idGastoSeleccionado > 0) {
        return;
    }
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
                buscarCliente(data);

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
            "IVA %",
            "DESCUENTO",
            "P.UNITARIO",
            "CANTIDAD",
            "TOTAL",
            "TIPO GASTO",
            "BIEN/SERVICIO",
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
                name: "iva",
                index: "iva",
                width: 50,
                align: "right"
            },
            {
                name: "descuento",
                index: "descuento",
                align: "right"
            },
            {
                name: "p_unitario",
                index: "p_unitario",
                width: 100,
                align: "right"
            },
            {
                name: "cantidad",
                index: "cantidad",
                width: 100,
                align: "right"
            },
            {
                name: "total",
                index: "total",
                align: "right"
            },
            {
                name: "tipo_gasto",
                index: "tipo_gasto",
                editable: true,
                edittype: 'custom',
                editoptions: {
                    custom_element: selectTipoGastoTabla,
                    custom_value: valueselectTipoGastoTabla,
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
                },
                align: "center"
            },
            {
                name: "bien_serivicio",
                index: "bien_serivicio",
                width: 100,
                editable: true,
                edittype: 'custom',
                editoptions: {
                    custom_element: selectBienservicioTabla,
                    custom_value: valueselectBienservicioTabla,
                },
                align: "center"
            },
            {
                name: "id_tipo_gasto",
                index: "id_tipo_gasto",
                hidden: true
            },
        ],
        width: (window.innerWidth - 300 < 600) ? 600 : window.innerWidth - 300,
        rownumbers: true,
        height: 300,
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
        /* onSelectRow: function (id) {
            if (id && id !== lastsel) {
                jQuery('#list').jqGrid('restoreRow', lastsel);
                jQuery('#list').jqGrid('editRow', id, true);
                lastsel = id;
            }
        }, */
        afterEditCell: function (rowid, cellname, value, iRow, iCol) {
            iSelectedCol = iCol;
            iSelectedRow = iRow;
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
function initTablaBusgarGastos() {
    $("#list2")
        .jqGrid({
            url: `json_lista_gastos.php`,
            datatype: "json",
            colNames: [
                'ID',
                'FACTURA',
                'TIPO',
                'FECHA EMISIÓN',
                'RUC/CI COMPRADOR',
                'COMPRADOR',
                'TOTAL',
            ],
            colModel: [
                {
                    name: 'id_gastos_personales',
                    index: 'id_gastos_personales',
                    width: 50,
                    search: false,
                },
                {
                    name: 'num_factura',
                    index: 'num_factura',
                    width: 150,
                    searchoptions: { sopt: ["eq"] },
                },
                {
                    name: 'tipo_comprobante',
                    index: 'tipo_comprobante',
                    width: 100,
                    search: false
                },
                {
                    name: 'fecha_emision',
                    index: 'fecha_emision',
                    width: 120,
                    search: false
                },
                {
                    name: 'identificacion_comprador',
                    index: 'identificacion_comprador',
                    width: 150,
                    searchoptions: { sopt: ["eq"] },
                },
                {
                    name: 'razon_social_comprador',
                    index: 'razon_social_comprador',
                    width: 200,
                    searchoptions: { sopt: ["cn"] },
                },
                {
                    name: 'total',
                    index: 'total',
                    width: 80,
                    search: false
                }
            ],
            rowNum: 30,
            width: 800,
            height: 220,
            sortable: true,
            rowList: [10, 20, 30],
            pager: jQuery("#pager2"),
            sortname: "id_gastos_personales",
            sortorder: "desc",
            ondblClickRow: function (rowid, iRow, iCol, e) {
                let row = $("#list2").jqGrid("getRowData", rowid);
                obtenerGasto(row.id_gastos_personales);
            }
        })
        .jqGrid(
            "navGrid",
            "#pager2",
            {
                add: false,
                edit: false,
                del: false,
                refresh: true,
                search: true,
                view: true,
            },
            {
                recreateForm: true,
                closeAfterEdit: true,
                checkOnUpdate: true,
                reloadAfterSubmit: true,
                closeOnEscape: true,
            },
            {
                reloadAfterSubmit: true,
                closeAfterAdd: true,
                checkOnUpdate: true,
                closeOnEscape: true,
                bottominfo: "Todos los campos son obligatorios",
            },
            {
                width: 300,
                closeOnEscape: true,
            },
            {
                closeOnEscape: true,
                multipleSearch: false,
                overlay: false,
            },
            {},
            {
                closeOnEscape: true,
            }
        );
}
function initDialogBuscarGasto() {
    $("#buscar_gastos").dialog({
        autoOpen: false,
        resizable: false,
        width: 830,
        height: 350,
        modal: true,
        // position: "top",
        show: "explode",
        hide: "blind",
        open: function (event, ui) {
            $("#list2").trigger("reloadGrid");
        }
    });

}
function initDialogClavePermiso() {
    $("#clave_permiso").dialog(
        {
            autoOpen: false,
            resizable: false,
            width: 420,
            height: 150,
            modal: true,
            position: "center",
            show: "explode",
            hide: "blind"
        }
    );

}
function initDialogSeguro() {
    $("#seguro").dialog(
        {
            autoOpen: false,
            resizable: false,
            width: 400,
            height: 150,
            modal: true,
            position: "center",
            show: "explode",
            hide: "blind",
            close: function (event, ui) {
                $(".ui-dialog-content").dialog("close");
            }
        }
    );

}

function addProducto(punitario, descto, cantidad, iva, nombrep, tipogasto, bienservicio, idtipogasto = "") {
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
        tipo_gasto: tipogasto,
        id_tipo_gasto: idtipogasto
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
    $("#iva_fac").val(iva);
    $("#des").val(descuento);
    $("#tot").val(total);

    $("#total_px").val(tarifa0.toFixed(2));
    $("#total_p2x").val(tarifa12.toFixed(2));
    $("#subx").val(subtotal.toFixed(2));
    $("#iva_facx").val(iva.toFixed(2));
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
    control.blur(function (e) {
        guardarCeldaSeleccionada();
    });
    control.change(function (e) {
        guardarCeldaSeleccionada();
    });
    let option1 = $(`<option ${value == "" ? "selected" : ""} value="">--SELECCIONAR--</option>`);
    let option2 = $(`<option ${value == "B" ? "selected" : ""} value="B">Bien</option>`);
    let option3 = $(`<option ${value == "S" ? "selected" : ""} value="S">Servicio</option>`);
    control.append([option1, option2, option3]);
    return control;
}
function valueselectBienservicioTabla(elem) {
    return elem.val();
}
function selectTipoGastoTabla(value, options) {
    let control = $(`<select style="width:100%"></select>`);
    control.blur(function (e) {
        guardarCeldaSeleccionada();
    });
    control.change(function (e) {
        guardarCeldaSeleccionada();
    });

    let id = options.id.split("_")[0];
    let row = $("#list").jqGrid("getRowData", id);
    llenarSelectTiposGasto(control, true, row.id_tipo_gasto);
    return control;
}
function valueselectTipoGastoTabla(elem, operation, value) {
    return elem.val();
}

function obtenerDatosFacturaGuardar() {
    guardarCeldaSeleccionada();

    let cabecera = {
        id_proveedor: $("#id_proveedor").val(),
        identificacion_comprador: $("#iden_comprador").val(),
        razon_social_comprador: $("#rs_comprador").val().toUpperCase(),
        num_factura: $("#factura").val(),
        num_autorizacion: $("#autorizacion").val(),
        fecha_emision: $("#fecha_emision").val(),
        descuento: $("#desc").val(),
        subtotal: $("#sub").val(),
        tarifa0: $("#total_p").val(),
        tarifa12: $("#total_p2").val(),
        iva: $("#iva_fac").val(),
        total: $("#tot").val(),
        tipo_comprobante: $("#tipo_comprobante").val()
    }

    let detallestmp = $("#list").jqGrid("getRowData");
    let detalles = detallestmp.map(el => {
        return {
            producto: el.producto,
            bien_serivicio: el.bien_serivicio,
            cantidad: el.cantidad,
            descuento: el.descuento,
            id_tipo_gasto: el.id_tipo_gasto,
            iva: el.iva,
            p_unitario: el.p_unitario,
            total: el.total
        }
    });
    return {
        cabecera, detalles
    };
}

function validarItemsTabla() {
    let datos = $("#list").jqGrid("getRowData");
    $("#alertify-logs").empty();
    if (datos.length == 0) {
        alertify.error("Ingrese productos");
        document.getElementById("gview_list").scrollIntoView();
        return false;
    }
    let bienserv = datos.some(el => el.bien_serivicio == "" || el.bien_serivicio == undefined);
    if (bienserv) {
        alertify.error("Debe indicar si el producto es un Bien o un Servicio");
        document.getElementById("gview_list").scrollIntoView();
        return false;
    }
    let tipogasto = datos.some(el => el.id_tipo_gasto == "" || el.id_tipo_gasto == undefined);
    if (tipogasto) {
        alertify.error("Debe indicar el tipo de gasto correspondiente a cada producto");
        document.getElementById("gview_list").scrollIntoView();
        return false;
    }

    return true;
}

function guardarGasto(datos) {
    $.ajax({
        url: "guardar_gasto.php",
        method: "POST",
        dataType: "json",
        data: datos,
        success: function (data) {
            if (data == 1) {
                alertify.alert("<b>Gasto guardado correctamente</b>", function () {
                    location.reload();
                });
            } else if (data == -1) {
                alertify.alert(`<b>No se puede guardar esta factura, ya se encuentra registrada en el sistema.</b>`);
            } else {
                alertify.error("No se pudo guardar el gasto");
            }
        }
    })
        .fail((err) => {
            alertify.error("Hubo un problema guardar el gasto");
        });
}

function resetPantalla() {
    window.scrollTo(0, 0);
    $("#gastosp_form")[0].reset();
    $("#list").jqGrid("clearGridData");
    calcularTotales();
    restoreFormDatosFactura();
    $("#clavefactura").val("");
    idGastoSeleccionado = 0;
    $("#btnGuardar")[0].disabled = false;
    $("#btnClientes")[0].disabled = false;
}

function buscarCliente(term) {
    return $.ajax({
        url: "busquedaCliente.php",
        dataType: "json",
        method: "GET",
        data: { term: term },
        success: function (data) {
            $("#empresa").val(data[0].value);
            $("#id_proveedor").val(data[0].label);
            $("#ruc_ci").val(data[0].label1);
        }
    });
}

function obtenerGasto(id) {
    return $.ajax({
        url: "obtener_gasto.php",
        dataType: "json",
        method: "GET",
        data: { id_gasto: id },
        success: function (data) {
            readOnlyForm();
            idGastoSeleccionado = id;
            let cabecera = data.cabecera;
            let detalles = data.detalles;
            $("#comprobante").val(id);
            $("#fecha_actual").val(cabecera["fecha_actual"]);
            $("#hora_actual").val(cabecera["hora_actual"]);
            $("#tipo_comprobante").val(cabecera["tipo_comprobante"]);
            $("#factura").val(cabecera["num_factura"]);
            $("#autorizacion").val(cabecera["num_autorizacion"]);
            $("#fecha_emision").val(cabecera["fecha_emision"]);
            $("#iden_comprador").val(cabecera["identificacion_comprador"]);
            $("#rs_comprador").val(cabecera["razon_social_comprador"]);
            $("#tipo_docu").val(cabecera["tipo_documento"]).trigger("change");
            $("#ruc_ci").autocomplete({
                response: function (event, ui) {
                    $("#ruc_ci").autocomplete({
                        response: function (event, ui) {

                        }
                    });
                    if (ui.content.length == 0) {
                        alertify.alert(`El proveedor <b><i>${infofac.razonSocial}</i></b> no esta registrado. Por favor registre el proveedor.`,
                            function (e) {
                                $("#btnClientes").click();
                            });
                        return;
                    }
                    let item = ui.content[0];
                    $("#ruc_ci").val(item.value);
                    $("#empresa").val(item.empresa);
                    $("#id_proveedor").val(item.id_proveedor);

                    $("#ruc_ci").autocomplete("close");
                }
            });
            $("#ruc_ci").autocomplete("search", cabecera["identificacion_pro"]);
            $("#list").jqGrid("clearGridData");
            detalles.forEach(el => {
                addProducto(
                    Number(el.precio_u),
                    Number(el.valor_descuento),
                    Number(el.cantidad),
                    Number(el.iva),
                    el.producto,
                    el.id_tipo_gasto,
                    el.bien_servicio,
                    el.id_tipo_gasto,
                );
            });

            $("#buscar_gastos").dialog("close");
            $("#list").setColProp('tipo_gasto', { editable: false });
            $("#list").setColProp('bien_serivicio', { editable: false });
            $("#list").setColProp('myac', { formatoptions: { keys: false, delbutton: false, editbutton: false } });
            console.log(`${cabecera["nombre_usuario"]} ${cabecera["apellido_usuario"]} doe`);
            /* $("#digitador").val(`${cabecera["nombre_usuario"]} ${cabecera["apellido_usuario"]}`); */
            /* document.getElementById("clavefactura").scrollIntoView(); */
        }
    })
        .fail(err => {
            idGastoSeleccionado = 0;
        });
}

function flechaAtras() {
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "gastos_personales" + "&id_tabla=" + "id_gastos_personales" + "&tipo=" + 1,
        success: function (data) {
            if (data == "") {
                alertify.alert("No hay más registros posteriores!!");
                return;
            }
            obtenerGasto(data);
        }

    });
}
function flechaAdelante() {
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "gastos_personales" + "&id_tabla=" + "id_gastos_personales" + "&tipo=" + 2,
        success: function (data) {
            if (data == "") {
                alertify.alert("No hay más registros superiores!!");
                return;
            }
            obtenerGasto(data);
        }
    });
}

function validar_acceso() {
    if ($("#clave").val() == "") {
        $("#clave").focus();
        alertify.alert("Ingrese la clave");
    } else {

        $.ajax({
            url: "../../procesos/validar_acceso.php",
            type: 'POST',
            data: "clave=" + $("#clave").val(),
            success: function (data) {
                var val = data;
                if (val == 0) {
                    $("#clave").val("");
                    $("#clave").focus();
                    alertify.alert("Error... La clave es incorrecta ingrese nuevamente");
                } else {
                    if (val == 1) {
                        $("#seguro").dialog("open");
                    }
                }
            }
        });
    }
}

function aceptarEliminar() {
    $.ajax({
        type: "POST",
        url: "eliminar_gasto.php",
        data: { id_gasto: idGastoSeleccionado },
        success: function (data) {
            var val = data;
            if (val == 1) {
                alertify.alert("<b>Gasto Eliminado Correctamente</b>", function () {
                    location.reload();
                });
            } else {
                alertify.alert("<b>No se pudo eliminar el gasto.</b>");
            }
        }
    });
}

function readOnlyForm() {
    let form = $("#gastosp_form")[0];
    let elements = Array.from(form.elements);
    elements.forEach(el => {
        el.readOnly = true;
    });
    $("#clavefactura").val("");
    $("#clavefactura")[0].disabled = true;
    $("#btn_buscar_clave")[0].disabled = true;
    $("#btnGuardar")[0].disabled = true;
    $("#btnClientes")[0].disabled = true;
    $("#factura")[0].style.background = null;
    $("#factura")[0].style["font-weight"] = null;
    $("#factura")[0].style.color = null;
}

function guardarCeldaSeleccionada() {
    $("#list").jqGrid("saveCell", iSelectedRow, iSelectedCol);
}