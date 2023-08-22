$(document).ready(inicio);
var cmpAddCliente;

function inicio() {
    $("#ruc_ci").validCampoFranz("0123456789");
    $("#descuento_producto").validCampoFranz("0123456789");
    $("#precio_unitario").validCampoFranz("0123456789.");

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
            addProducto();
        }
    });

    show();
    addCliente();
    llenarTiposGasto();
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

function llenarTiposGasto() {
    $.ajax({
        url: "obtener_tipos_gasto_personal.php",
        method: "GET",
        dataType: "json",
        success: function (data) {
            let selecttg = $("#tipo_gasto");
            selecttg.empty();
            for (let group in data) {
                let gselect = $(`<optgroup label="${group}"></optgroup>`);
                let items = data[group];
                items.forEach(el => {
                    let option = $(`<option value="${el.id_tipo_gasto}">${el.nombre}</option>`);
                    gselect.append(option);
                });
                selecttg.append(gselect);
            }
        }
    });
}

function initTablaDocs() {
    $("#list").jqGrid({
        dataType: "local",
        colNames: [
            "PRODUCTO",
            "BIEN/SERVICIO",
            "IVA",
            "DESCUENTO",
            "P.UNITARIO",
            "TOTAL",
            "TIPO GASTO"
        ],
        colModel: [
            {
                name: "producto",
                index: "producto",
                width: 200
            },
            {
                name: "bien_serivicio",
                index: "bien_serivicio",
                width: 100
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
                name: "total",
                index: "total",
            },
            {
                name: "tipo_gasto",
                index: "tipo_gasto"
            },
        ],
        width: (window.innerWidth - 300 < 600) ? 600 : window.innerWidth - 300,
        rownumbers: true,
        height: 300,
        afterInsertRow: function (rowid, rowdata, rowelem) {

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

function addProducto() {
    let piva = Number($("#iva").val());
    let punitario = Number($("#precio_unitario").val());
    let descto = Number($("#descuento_producto").val());
    let viva = 0;
    if (piva > 0) {
        viva = Number($("#precio_unitario").val()) * (piva / 100);
    }


    let row = {
        producto: $("#producto").val(),
        bien_serivicio: $("#bien_servicio").val(),
        iva: $("#iva").val(),
        descuento: $("#descuento_producto").val(),
        p_unitario: $("#precio_unitario").val(),
        tipo_gasto: $("#tipo_gasto").val()
    };
    let ids = $("#list").jqGrid('getDataIDs');
    let id = 1;
    if (ids.length > 0) {
        id = ids[ids.length] + 1;
    }
    $("#list").jqGrid('addRowData', id, row);
    $("#producto").focus();
    limpiarCamposProducto();
}

function limpiarCamposProducto() {
    $("#producto").val("");
    $("#bien_servicio").val("");
    $("#descuento_producto").val("");
    $("#precio_unitario").val("");
    $("#tipo_gasto").val("")
}

function calcularTotales() {
    $("$total_px").val();
    $("$total_p2x").val();
    $("$subx").val();
    $("$ivax").val();
    $("$tot").val();
}
