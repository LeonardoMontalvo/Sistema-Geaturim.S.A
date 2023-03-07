$(document).on("ready", inicio);
function evento(e) {
    e.preventDefault();
}

function openPDF() {
    window.open('../../ayudas/ayuda.pdf');
}

var dialogo =
{
    autoOpen: false,
    resizable: false,
    width: 530,
    height: 320,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind",
    Cancelar: function () {
        $(this).dialog("close");
        $('#list2').trigger('reloadGrid');
    },
    close: function () {
        $("#mostrar_pagadas")[0].checked = false;
    }
};

var dialogo3 =
{
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 350,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind"
};

var dialogo_cuenta = {
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 400,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind"
}

function scrollToBottom() {
    $('html, body').animate({
        scrollTop: $(document).height()
    }, 'slow');
}

function scrollToTop() {
    $('html, body').animate({
        scrollTop: 0
    }, 'slow');
}

function show() {
    var Digital = new Date();
    var hours = Digital.getHours();
    var minutes = Digital.getMinutes();
    var seconds = Digital.getSeconds();
    var dn = "AM";
    if (hours > 12) {
        dn = "PM";
        hours = hours - 12;
    }
    if (hours === 0)
        hours = 12;
    if (minutes <= 9)
        minutes = "0" + minutes;
    if (seconds <= 9)
        seconds = "0" + seconds;
    $("#hora_actual").val(hours + ":" + minutes + ":" + seconds + " " + dn);

    setTimeout("show()", 1000);
}

function enter(e) {
    if (e.which === 13 || e.keyCode === 13) {
        comprobar();
        return false;
    }
    return true;
}
function cargar_cuentas() {
    var id = $("#forma_pago").val();

    $("#list4").jqGrid('setGridParam', {
        url: 'xmlPlanCuentas.php?id=' + id,
        datatype: 'xml'
    }).trigger('reloadGrid');


}
function enter1(e) {
    if (e.which === 13 || e.keyCode === 13) {
        entrar();
        return false;
    }
    return true;
}

function comprobar() {
    if ($("#id_proveedor").val() === "") {
        $("#ruc_ci").focus();
        alertify.error("Ingrese un Proveedor");
    } else {
        if ($("#ruc_ci").val() === "") {
            $("#ruc_ci").focus();
            alertify.error("Identificación del Proveedor");
        } else {
            if ($("#forma_pago").val() === "") {
                $("#forma_pago").focus();
                alertify.error("Error... Seleccione forma de pago");
            }
        }
    }
}

function entrar() {
    if ($("#forma_pago").val() == 'TRANSFERENCIA' && $("#idCuenta").val() == "" && $("#forma_pago").val() == 'CHEQUE') {
        alertify.error("Error.. Debe seleccionar Cuenta contable");
    } else {
        if ($("#num_factura").val() === "") {
            alertify.error("Error... Seleccione una factura");
        } else {
            if ($("#valor_pagado").val() === "") {
                $("#valor_pagado").focus();
                alertify.error("Ingrese un valor");
            } else {
                if (parseFloat($("#valor_pagado").val()) <= parseFloat($("#saldo2").val())) {
                    $("#list").jqGrid("clearGridData", true);
                    var filas = jQuery("#list").jqGrid("getRowData");
                    var su = 0;
                    var saldo = 0;
                    var valor = parseFloat($("#valor_pagado").val());
                    var entero = ((valor).toFixed(2));
                    saldo = (parseFloat($("#saldo2").val()) - parseFloat($("#valor_pagado").val()));
                    var entero2 = ((saldo).toFixed(2));
                    if (filas.length === 0) {
                        var datarow = { ids_pagos: $("#ids").val(), num_factura: $("#num_factura").val(), tipo_factura: $("#tipo_factura").val(), fecha_factura: $("#fecha_factura").val(), totalcxc: $("#totalcxc").val(), valor_pagado: entero, saldo: entero2, compra_gasto: $("#compra_gasto").val() };
                        su = jQuery("#list").jqGrid('addRowData', $("#num_factura").val(), datarow);
                        ////////limpiar///////////
                        $("#ids").val("");
                        $("#num_factura").val("");
                        $("#tipo_factura").val("");
                        $("#fecha_factura").val("");
                        $("#totalcxc").val("");
                        $("#valor_pagado").val("");
                        $("#saldo2").val("");
                        $("#compra_gasto").val("");
                        ///////////////////////////
                    }
                } else {
                    alertify.error("Error... Valor excedió al saldo");
                }
            }
        }
    }
}

function cargar_facturas() {
    var id = $("#id_proveedor").val();
    if (id === "") {
        $("#num_factura").val("");
        $("#ruc_ci").focus();
        alertify.error("Error... Seleccione un proveedor");
    } else {
        $("#list2").jqGrid('setGridParam', {
            url: 'xmlFacturas_compra.php?id_proveedor=' + id + '&tipo=' + $("#tipo_pago").val(),
            datatype: 'xml'
        }).trigger('reloadGrid');
        $("#buscar_facturas").dialog("open");
    }
}

function guardar_pagos() {
    var tam = jQuery("#list").jqGrid("getRowData");

    if ($("#id_proveedor").val() === "") {
        $("#tipo_docu").focus();
        alertify.error("Ingrese un Proveedor");
    } else {
        if ($("#ruc_ci").val() === "") {
            $("#ruc_ci").focus();
            alertify.error("Identificación del Proveedor");
        } else {
            if ($("#forma_pago").val() === "0") {
                $("#forma_pago").focus();
                alertify.error("Error... Seleccione forma de pago");
            } else {
                if ($("#tipo_pago").val() === "") {
                    $("#tipo_pago").focus();
                    alertify.error("Error... Seleccione tipo de pago");
                } else {
                    if ($("#idCuenta").val() == "") {
                        $("#cuenta_contable").focus();
                        alertify.error("Error.. Debe seleccionar Cuenta contable");
                    } else {
                        if (tam.length === 0) {
                            alertify.error("Error... Ingrese un pago");
                        } else {
                            var v1 = new Array();
                            var v2 = new Array();
                            var v3 = new Array();
                            var v4 = new Array();
                            var v5 = new Array();
                            var v6 = new Array();
                            var v7 = new Array();
                            var v8 = new Array();
                            var string_v1 = "";
                            var string_v2 = "";
                            var string_v3 = "";
                            var string_v4 = "";
                            var string_v5 = "";
                            var string_v6 = "";
                            var string_v7 = "";
                            var string_v8 = "";
                            var fil = jQuery("#list").jqGrid("getRowData");
                            for (var i = 0; i < fil.length; i++) {
                                var datos = fil[i];
                                v1[i] = datos['ids_pagos'];
                                v2[i] = datos['num_factura'];
                                v3[i] = datos['tipo_factura'];
                                v4[i] = datos['fecha_factura'];
                                v5[i] = datos['totalcxc'];
                                v6[i] = datos['valor_pagado'];
                                v7[i] = datos['saldo'];
                                v8[i] = datos['compra_gasto'];
                            }
                            for (i = 0; i < fil.length; i++) {
                                string_v1 = string_v1 + "|" + v1[i];
                                string_v2 = string_v2 + "|" + v2[i];
                                string_v3 = string_v3 + "|" + v3[i];
                                string_v4 = string_v4 + "|" + v4[i];
                                string_v5 = string_v5 + "|" + v5[i];
                                string_v6 = string_v6 + "|" + v6[i];
                                string_v7 = string_v7 + "|" + v7[i];
                                string_v8 = string_v8 + "|" + v8[i];
                            }
                            $("#btnGuardar").attr("disabled", true);
                            $.ajax({
                                type: "POST",
                                url: "guardar_pagos_pagar.php",
                                data: "id_proveedor=" + $("#id_proveedor").val() + "&comprobante=" + $("#comprobante").val() + "&fecha_actual=" + $("#fecha_actual").val() + "&hora_actual=" + $("#hora_actual").val() + "&forma_pago=" + $("#forma_pago").val() + "&tipo_pago=" + $("#tipo_pago").val() + "&observaciones=" + $("#observaciones").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&campo6=" + string_v6 + "&campo7=" + string_v7 + "&campo8=" + string_v8 + "&cheque_tarjeta=" + $("#cheque_tarjeta").val() + "&bancos=" + $("#banco").val() + "&cuenta_cheque=" + $("#idCuenta").val(),
                                success: function (data) {
                                    var val = data;
                                    if (val == 1) {
                                        if ($("#tipo_pago").val() == "EXTERNA") {
                                            window.open("../../reportes/reporte_cxp.php?tipo_pago=" + $("#tipo_pago").val() + "&id=" + v2[0] + "&comprobante=" + $("#comprobante").val() + "&proveedor=" + $("#id_proveedor").val(), '_blank');
                                        } else {
                                            window.open("../../reportes/reporte_cxp.php?tipo_pago=" + $("#tipo_pago").val() + "&id=" + v2[0] + "&comprobante=" + $("#comprobante").val() + "&temp2=" + v6[0] + "&temp3=" + v7[0] + "&proveedor=" + $("#id_proveedor").val(), '_blank');
                                        }
                                        alertify.alert("Pago Guardado correctamente", function () {
                                            //                                        location.reload();
                                        });
                                    }
                                }
                            });
                        }
                    }
                }
            }
        }
    }
}

function flecha_atras() {
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "pagos_pagar" + "&id_tabla=" + "id_cuentas_pagar" + "&tipo=" + 1,
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#comprobante").val(val);
                var valor = $("#comprobante").val();

                ///////////////////////////////////////////////
                $("#btnGuardar").attr("disabled", true);
                $("#btnModificar").attr("disabled", true);
                $("#btnfacturas").attr("disabled", "disabled");
                $("#valor_pagado").attr("disabled", "disabled");
                $("#ruc_ci").attr("disabled", "disabled");
                $("#observaciones").attr("disabled", "disabled");
                $("#id_proveedor").val("");
                $("#ruc_ci").val("");
                $("#empresa").val("");
                $("#forma_pago").val(0);
                $('#tipo_pago').children().remove().end();
                $("#list").jqGrid("clearGridData", true);


                $.getJSON('retornar_pagos_compra.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 10) {
                            $("#fecha_actual").val(data[i]);
                            $("#hora_actual").val(data[i + 1]);
                            $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                            $("#id_proveedor").val(data[i + 4]);
                            $("#tipo_docu").val(data[i + 5]);
                            $("#ruc_ci").val(data[i + 6]);
                            $("#empresa").val(data[i + 7]);
                            $("#forma_pago").val(data[i + 8]);
                            $("#tipo_pago").append('<option value=' + data[i + 9] + ' selected>' + data[i + 9] + '</option>');
                        }
                    }
                });

                $.getJSON('retornar_pagos_compra2.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 8) {
                            var datarow = { ids_pagos: data[i], num_factura: data[i + 1], tipo_factura: data[i + 2], fecha_factura: data[i + 3], totalcxc: data[i + 4], valor_pagado: data[i + 5], saldo: data[i + 6] };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                            $("#observaciones").val(data[i + 7]);
                        }
                    }
                });
            } else {
                alertify.alert("No hay más registros posteriores!!");
            }
        }
    });
}

function flecha_siguiente() {
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "pagos_pagar" + "&id_tabla=" + "id_cuentas_pagar" + "&tipo=" + 2,
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#comprobante").val(val);
                var valor = $("#comprobante").val();

                //////////////////////////////////////////////
                $("#btnGuardar").attr("disabled", true);
                $("#btnModificar").attr("disabled", true);
                $("#btnfacturas").attr("disabled", "disabled");
                $("#valor_pagado").attr("disabled", "disabled");
                $("#ruc_ci").attr("disabled", "disabled");
                $("#observaciones").attr("disabled", "disabled");
                $("#id_proveedor").val("");
                $("#ruc_ci").val("");
                $("#empresa").val("");
                $("#forma_pago").val(0);
                $('#tipo_pago').children().remove().end();
                $("#list").jqGrid("clearGridData", true);

                ///////////////////llamar pagos flechas primera parte/////
                $.getJSON('retornar_pagos_compra.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 10) {
                            $("#fecha_actual").val(data[i]);
                            $("#hora_actual").val(data[i + 1]);
                            $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                            $("#id_proveedor").val(data[i + 4]);
                            $("#tipo_docu").val(data[i + 5]);
                            $("#ruc_ci").val(data[i + 6]);
                            $("#empresa").val(data[i + 7]);
                            $("#forma_pago").val(data[i + 8]);
                            $("#tipo_pago").append('<option value=' + data[i + 9] + ' selected>' + data[i + 9] + '</option>');
                        }
                    }
                });

                $.getJSON('retornar_pagos_compra2.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 8) {
                            var datarow = { ids_pagos: data[i], num_factura: data[i + 1], tipo_factura: data[i + 2], fecha_factura: data[i + 3], totalcxc: data[i + 4], valor_pagado: data[i + 5], saldo: data[i + 6] };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                            $("#observaciones").val(data[i + 7]);
                        }
                    }
                });
            } else {
                alertify.alert("No hay más registros superiores!!");
            }
        }
    });
}

function limpiar_campo() {
    if ($("#ruc_ci").val() === "") {
        $("#id_proveedor").val("");
        $("#empresa").val("");
        $("#list_pagosr").jqGrid("clearGridData", true);
    }
}

function limpiar_cuenta() {
    location.reload();
}

function punto(e) {
    var key;
    if (window.event) {
        key = e.keyCode;
    } else if (e.which) {
        key = e.which;
    }

    if (key < 48 || key > 57) {
        if (key === 46 || key === 8) {
            return true;
        } else {
            return false;
        }
    }
    return true;
}

function abrirCuenta() {
    $("#cuentas").dialog("open");
}

function inicio() {
    iniRadiosEntidades();

    iniTablaPagosRealizados();
    iniDialogosPermisos();
    $("#mostrar_pagadas").change(function (e) {
        if (e.target.checked) {
            cargarTablaFacturas($("#id_proveedor").val());
        } else {
            cargar_facturas();
        }
    });
    alertify.set({  delay: 1000  });
    //////////////para hora///////////
    show();
    ///////////////////

    //////////////botones//////////
    $("#btnfacturas").click(function (e) {
        e.preventDefault();
    });

    $("#btnGuardar").click(function (e) {
        e.preventDefault();
    });

    $("#btnModificar").click(function (e) {
        e.preventDefault();
    });

    $("#btnBuscar").click(function (e) {
        e.preventDefault();
        $("#buscar_cuentas_pagar").dialog("open");
    });

    $("#btnNuevo").click(function (e) {
        e.preventDefault();
    });

    $("#btnImprimir").click(function () {
        var temp = 0;
        var temp2 = 0;
        var temp3 = 0;
        var fil = jQuery("#list").jqGrid("getRowData");
        for (var i = 0; i < fil.length; i++) {
            var datos = fil[i];
            temp = datos['num_factura'];
            temp2 = datos['valor_pagado'];
            temp3 = datos['saldo'];
        }
        $.ajax({
            type: "POST",
            url: "../../procesos/validacion.php",
            data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "pagos_pagar" + "&id_tabla=" + "id_cuentas_pagar" + "&tipo=" + 1,
            success: function (data) {
                var val = data;
                if (val != "") {
                    if ($("#tipo_pago").val() == "EXTERNA") {
                        window.open("../../reportes/reporte_cxp.php?tipo_pago=" + $("#tipo_pago").val() + "&id=" + temp + "&comprobante=" + $("#comprobante").val() + "&proveedor=" + $("#id_proveedor").val(), '_blank');
                    } else {
                        window.open("../../reportes/reporte_cxp.php?tipo_pago=" + $("#tipo_pago").val() + "&id=" + temp + "&comprobante=" + $("#comprobante").val() + "&temp2=" + temp2 + "&temp3=" + temp3 + "&proveedor=" + $("#id_proveedor").val(), '_blank');
                    }
                } else {
                    alertify.alert("Cuenta no creada!!");
                }
            }
        });
    });

    $("#btnAtras").click(function (e) {
        e.preventDefault();
    });

    $("#btnAdelante").click(function (e) {
        e.preventDefault();
    });

    //////////atributos////////////
    $("#ruc_ci").attr("disabled", "disabled");
    $("#empresa").attr("disabled", "disabled");
    /////////////////////////////////

    $("#btnfacturas").on("click", cargar_facturas);
    $("#btnGuardar").on("click", guardar_pagos);
    $("#btnNuevo").on("click", limpiar_cuenta);
    $("#btnAtras").on("click", flecha_atras);
    $("#btnAdelante").on("click", flecha_siguiente);


    $("#ruc_ci").on("keyup", limpiar_campo);
    $("#buscar_facturas").dialog(dialogo);
    $("#buscar_cuentas_pagar").dialog(dialogo3);

    //////////////para valor////////
    $("#valor_pagado").on("keypress", punto);
    ////////////////////////////////

    $("#btnCuenta").click(function (e) {
        e.preventDefault();
    });
    $("#btnCuenta").on("click", abrirCuenta);
    //////////////////////////////
    $("#forma_pago").on("change", function () {
        if ($("#forma_pago").val() == "TRANSFERENCIA" || $("#forma_pago").val() == "CHEQUE" || $("#forma_pago").val() == "TARJETA") {
            cargar_cuentas();
            $("#cuenta_contable").attr("disabled", false);
            $("#btnCuenta").attr("disabled", false);
            $("#cheque_tarjeta").attr("disabled", false);
            $("#banco").attr("disabled", false);
        } else if ($("#forma_pago").val() == "EFECTIVO") {
            cargar_cuentas();
            $("#cuenta_contable").attr("disabled", false);
            $("#btnCuenta").attr("disabled", false);
            $("#cuenta_contable").val("");
            //            $("#idCuenta").val("");
            $('#fecha_vencimiento').hide();
            $("#cheque_tarjeta").attr("disabled", true);
            //            $("#banco").attr("disabled", true);

        }
        //        else if ($("#forma_pago").val() == "TARJETA") {
        //            cargar_cuentas();
        //            $("#cuenta_contable").attr("disabled", true);
        //            $("#btnCuenta").attr("disabled", true);
        //            $("#cuenta_contable").val("");
        //            $("#idCuenta").val("");
        //            $('#fecha_vencimiento').hide();
        //            $("#cheque_tarjeta").attr("disabled", false);
        //            $("#banco").attr("disabled", false);
        ////            $("#cheque_tarjeta").attr("disabled", true);
        ////            $("#banco").attr("disabled", true);
        //
        //        }
    });
    $("#cuentas").dialog(dialogo_cuenta);


    //////////////validaciones////////////
    $("#ruc_ci").on("keypress", enter);
    $("#valor_pagado").on("keypress", enter1);

    //////////////buscar proveedor///////////////////
    $("#tipo_docu").change(function () {
        var tipo = $("#tipo_docu").val();
        if (tipo === "Cedula") {
            $("#ruc_ci").validCampoFranz("0123456789");
            $("#ruc_ci").removeAttr("disabled");
            $("#ruc_ci").attr("maxlength", "10");
            $("#ruc_ci").autocomplete({
                source: "buscar_empresa.php?tipo_docu=" + tipo + "&entidad=" + tipoentidad,
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
                    var id = $('#id_proveedor').val();
                    $('#tipo_pago').load('cargar_tipo_pago2.php?cod=' + id);
                    return false;
                }

            }).data("ui-autocomplete")._renderItem = function (ul, item) {
                return $("<li>")
                    .append("<a>" + item.value + "</a>")
                    .appendTo(ul);
            };
            //////////////////////////////
            $("#ruc_ci").val("");
            $("#empresa").val("");
            $("#id_proveedor").val("");
        } else {
            if (tipo === "Ruc") {
                $("#ruc_ci").validCampoFranz("0123456789");
                $("#ruc_ci").removeAttr("disabled");
                $("#ruc_ci").removeAttr("maxlength");
                $("#ruc_ci").attr("maxlength", "13");
                $("#ruc_ci").autocomplete({
                    source: "buscar_empresa.php?tipo_docu=" + tipo + "&entidad=" + tipoentidad,
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
                        var id = $('#id_proveedor').val();
                        $('#tipo_pago').load('cargar_tipo_pago2.php?cod=' + id);
                        return false;
                    }

                }).data("ui-autocomplete")._renderItem = function (ul, item) {
                    return $("<li>")
                        .append("<a>" + item.value + "</a>")
                        .appendTo(ul);
                };
                //////////////////////////////
                $("#ruc_ci").val("");
                $("#empresa").val("");
                $("#id_proveedor").val("");
            } else {
                if (tipo === "Pasaporte") {
                    $("#ruc_ci").unbind("keypress");
                    $("#ruc_ci").removeAttr("disabled");
                    $("#ruc_ci").attr("maxlength", "30");
                    $("#ruc_ci").autocomplete({
                        source: "buscar_empresa.php?tipo_docu=" + tipo + "&entidad=" + tipoentidad,
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
                            var id = $('#id_proveedor').val();
                            $('#tipo_pago').load('cargar_tipo_pago2.php?cod=' + id);
                            return false;
                        }

                    }).data("ui-autocomplete")._renderItem = function (ul, item) {
                        return $("<li>")
                            .append("<a>" + item.value + "</a>")
                            .appendTo(ul);
                    };
                    //////////////////////////////
                    $("#ruc_ci").val("");
                    $("#empresa").val("");
                    $("#id_proveedor").val("");
                }
            }
        }
    });


    $(window).bind('resize', function () {
        jQuery("#list4").setGridWidth($('#pager4').width());
    }).trigger('resize');
    jQuery("#list4").jqGrid({
        url: 'xmlPlanCuentas.php',
        datatype: 'xml',
        colNames: ['Cod. Cuenta', 'Descripcion', 'Cuenta'],
        colModel: [
            { name: 'idcontable', index: 'idcontable', editable: true, align: 'left', width: '120', search: true, frozen: true, formoptions: { elmsuffix: " (*)" }, editrules: { required: true } },
            { name: 'ccontable', index: 'ccontable', editable: true, align: 'center', width: '490', search: true, frozen: true, formoptions: { elmsuffix: " (*)" }, editrules: { required: true } },
            { name: 'cuenta', index: 'cuenta', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: { elmsuffix: " (*)" }, editrules: { required: true } }
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        height: 255,
        pager: jQuery('#pager4'),
        sortname: 'codigo_plan',
        shrinkToFit: false,
        sortordezr: 'asc',
        caption: 'Plan de Cuentas',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list4").jqGrid('getGridParam', 'selrow');
            jQuery('#list4').jqGrid('restoreRow', id);
            var ret = jQuery("#list4").jqGrid('getRowData', id);
            var ccuenta = jQuery("#list4").jqGrid('getCell', id, 0) + "  -  " + jQuery("#list4").jqGrid('getCell', id, 1);
            $("#idCuenta").val(id);
            $("#cuenta_contable").val(ccuenta);

            var string = ccuenta;
            var string1 = string.split("-");
            console.log(string1);
            var part1 = string1[1]; // 123


            $("#banco").val(part1);
            document.getElementById("cuenta_contable").readOnly = true;
            $("#cuentas").dialog("close");
        }
    }).jqGrid('navGrid', '#pager4',
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
    jQuery("#list4").setGridWidth($('#pager4').width());


    $('#fecha_actual').datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');

    var can;
    jQuery("#list").jqGrid({
        datatype: "local",
        colNames: ['', 'id', 'Factura a Pagar', 'Tipo Factura', 'Fecha Factura', 'Total CxC', 'Valor a Pagar', 'Saldo', 'G/C'],
        colModel: [
            {
                name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions',
                formatoptions: { keys: false, delbutton: true, editbutton: false }
            },
            { name: 'ids_pagos', index: 'ids_pagos', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },
            { name: 'num_factura', index: 'num_factura', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'tipo_factura', index: 'tipo_factura', editable: false, frozen: true, editrules: { required: true }, align: 'center', width: 150 },
            { name: 'fecha_factura', index: 'fecha_factura', editable: false, frozen: true, editrules: { required: true }, align: 'center', width: 100 },
            { name: 'totalcxc', index: 'totalcxc', editable: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: 110 },
            { name: 'valor_pagado', index: 'valor_pagado', editable: false, frozen: true, editrules: { required: true }, align: 'center', width: 100 },
            { name: 'saldo', index: 'saldo', editable: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: 100 },
            { name: 'compra_gasto', index: 'compra_gasto', editable: false, search: false, frozen: true, hidden: false, editrules: { required: true }, align: 'center', width: 110 },
        ],
        rowNum: 30,
        width: 750,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager'),
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'clientArray',
        shrinkToFit: true,
        delOptions: {
            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {
                var id = jQuery("#list").jqGrid('getGridParam', 'selrow');
                jQuery('#list').jqGrid('restoreRow', id);
                var ret = jQuery("#list").jqGrid('getRowData', id);
                rp_ge.processing = true;
                var su = jQuery("#list").jqGrid('delRowData', rowid);
                $(".ui-icon-closethick").trigger('click');
                return true;
            },
            processing: true
        }
    });

    jQuery("#list2").jqGrid({
        url: 'xmlFacturas_compra.php',
        datatype: 'xml',
        colNames: ['ID', 'Factura a Pagar', 'Tipo Factura', 'Fecha Factura', 'Total CxC', 'Valor a Pagar', 'Saldo', 'COMPRAS/GASTO'],
        colModel: [
            {
                name: 'ids', index: 'ids', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'center',
                frozen: true, width: 50
            },
            {
                name: 'num_factura', index: 'num_factura', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center',
                frozen: true, width: 180
            },
            { name: 'tipo_factura', index: 'tipo_factura', editable: false, frozen: true, hidden: true, editrules: { required: true }, align: 'center', width: 250 },
            { name: 'fecha_factura', index: 'fecha_factura', editable: true, frozen: true, hidden: true, editrules: { required: true }, align: 'center', width: 180 },
            { name: 'totalcxc', index: 'totalcxc', editable: true, search: false, frozen: true, hidden: true, editrules: { required: true }, align: 'center', width: 110 },
            { name: 'valor_pagado', index: 'valor_pagado', editable: true, frozen: true, hidden: true, editrules: { required: true }, align: 'center', width: 120 },
            { name: 'saldo', index: 'saldo', editable: false, search: false, frozen: true, hidden: false, editrules: { required: true }, align: 'center', width: 110 },
            { name: 'compra_gasto', index: 'compra_gasto', editable: false, search: false, frozen: true, hidden: false, editrules: { required: true }, align: 'center', width: 110 },
        ],
        rowNum: 10,
        width: 500,
        rowList: [10, 20, 30],
        pager: jQuery('#pager2'),
        shrinkToFit: true,
        sortorder: 'asc',
        caption: 'Lista de Facturas',

        viewrecords: true,
        ondblClickRow: function (rowid) {
            var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
            jQuery('#list2').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list2").jqGrid('getRowData', id);
                $("#ids").val(ret.ids);
                $("#num_factura").val(ret.num_factura);
                $("#tipo_factura").val(ret.tipo_factura);
                $("#fecha_factura").val(ret.fecha_factura);
                $("#totalcxc").val(ret.totalcxc);
                $("#saldo2").val(ret.saldo);
                $("#compra_gasto").val(ret.compra_gasto);
                //////////////////////
                $("#buscar_facturas").dialog("close");
                $("#valor_pagado").focus();
                $("#list").jqGrid("clearGridData", true);
                cargarTablaPagosRealizados($("#tipo_pago").val(), $("#num_factura").val(), $("#compra_gasto").val());
            } else {
                alertify.alert("Seleccione una Cuenta");
            }
        }
    }).jqGrid('navGrid', '#pager2', {
        add: false,
        edit: false,
        del: false,
        refresh: true,
        search: false,
        view: true
    });
    /////////////////	

    jQuery("#list2").jqGrid('navButtonAdd', '#pager2', {
        caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
            jQuery('#list2').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list2").jqGrid('getRowData', id);
                $("#ids").val(ret.ids);
                $("#num_factura").val(ret.num_factura);
                $("#tipo_factura").val(ret.tipo_factura);
                $("#fecha_factura").val(ret.fecha_factura);
                $("#totalcxc").val(ret.totalcxc);
                $("#saldo2").val(ret.saldo);

                //////////////////////
                $("#buscar_facturas").dialog("close");
                $("#valor_pagado").focus();
                $("#list").jqGrid("clearGridData", true);
            } else {
                alertify.alert("Seleccione una Cuenta");
            }
        }
    });

    jQuery("#list3").jqGrid({
        url: 'xmlBuscarCuentasPagar.php',
        datatype: 'xml',
        colNames: ['ID', 'IDENTIFICACIÓN', 'PROVEEDOR', 'FACTURA NRO.', 'MONTO TOTAL', 'FECHA'],
        colModel: [
            { name: 'id_cuentas_pagar', index: 'id_cuentas_pagar', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },
            { name: 'identificacion_pro', index: 'identificacion_pro', editable: false, search: true, hidden: false, editrules: { edithidden: false }, align: 'left', frozen: true, width: 150 },
            { name: 'empresa_pro', index: 'empresa_pro', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'left', frozen: true, width: 200 },
            { name: 'num_factura', index: 'num_factura', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'left', frozen: true, width: 200 },
            { name: 'saldo_factura', index: 'saldo_factura', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'right', frozen: true, width: 100 },
            { name: 'fecha_factura', index: 'fecha_factura', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
        ],
        rowNum: 10,
        width: 760,
        height: 220,
        rowList: [10, 20, 30],
        pager: jQuery('#pager3'),
        sortname: 'id_cuentas_pagar',
        shrinkToFit: true,
        sortorder: 'asc',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list3").jqGrid('getGridParam', 'selrow');
            jQuery('#list3').jqGrid('restoreRow', id);

            var ret = jQuery("#list3").jqGrid('getRowData', id);
            var valor = ret.id_cuentas_pagar;

            $("#comprobante").val(valor);
            $("#btnGuardar").attr("disabled", true);
            $("#btnModificar").attr("disabled", true);
            $("#btnfacturas").attr("disabled", "disabled");
            $("#valor_pagado").attr("disabled", "disabled");
            $("#ruc_ci").attr("disabled", "disabled");
            $("#observaciones").attr("disabled", "disabled");
            $("#id_proveedor").val("");
            $("#ruc_ci").val("");
            $("#empresa").val("");
            $("#forma_pago").val(0);
            $('#tipo_pago').children().remove().end();
            $("#list").jqGrid("clearGridData", true);

            ////////////////////llamar pagos primera parte ///////////////

            $.getJSON('retornar_pagos_compra.php?com=' + valor, function (data) {
                var tama = data.length;
                if (tama !== 0) {
                    for (var i = 0; i < tama; i = i + 10) {
                        $("#fecha_actual").val(data[i]);
                        $("#hora_actual").val(data[i + 1]);
                        $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                        $("#id_proveedor").val(data[i + 4]);
                        $("#tipo_docu").val(data[i + 5]);
                        $("#ruc_ci").val(data[i + 6]);
                        $("#empresa").val(data[i + 7]);
                        $("#forma_pago").val(data[i + 8]);
                        $("#tipo_pago").append('<option value=' + data[i + 9] + ' selected>' + data[i + 9] + '</option>');
                    }
                }
            });

            $.getJSON('retornar_pagos_compra2.php?com=' + valor, function (data) {
                var tama = data.length;
                if (tama !== 0) {
                    for (var i = 0; i < tama; i = i + 8) {
                        var datarow = { ids_pagos: data[i], num_factura: data[i + 1], tipo_factura: data[i + 2], fecha_factura: data[i + 3], totalcxc: data[i + 4], valor_pagado: data[i + 5], saldo: data[i + 6] };
                        var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        $("#observaciones").val(data[i + 7]);
                    }
                }
            });
            /////////////////////////////////////////////////// 
            $("#buscar_cuentas_pagar").dialog("close");
        }
    }).jqGrid('navGrid', '#pager3',
        {
            add: false,
            edit: false,
            del: false,
            refresh: true,
            search: true,
            view: true
        },
        {
            recreateForm: true, closeAfterEdit: true, checkOnUpdate: true, reloadAfterSubmit: true, closeOnEscape: true
        },
        {
            reloadAfterSubmit: true, closeAfterAdd: true, checkOnUpdate: true, closeOnEscape: true,
            bottominfo: "Todos los campos son obligatorios son obligatorios"
        },
        {
            width: 300, closeOnEscape: true
        },
        {
            closeOnEscape: true,
            multipleSearch: false, overlay: false
        },
        {
        },
        {
            closeOnEscape: true
        }
    );

    jQuery("#list3").jqGrid('navButtonAdd', '#pager3', {
        caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#list3").jqGrid('getGridParam', 'selrow');
            jQuery('#list3').jqGrid('restoreRow', id);

            if (id) {
                var ret = jQuery("#list3").jqGrid('getRowData', id);
                var valor = ret.id_cuentas_pagar;

                $("#comprobante").val(valor);
                $("#btnGuardar").attr("disabled", true);
                $("#btnModificar").attr("disabled", true);
                $("#btnfacturas").attr("disabled", "disabled");
                $("#valor_pagado").attr("disabled", "disabled");
                $("#ruc_ci").attr("disabled", "disabled");
                $("#observaciones").attr("disabled", "disabled");
                $("#id_proveedor").val("");
                $("#ruc_ci").val("");
                $("#empresa").val("");
                $("#forma_pago").val(0);
                $('#tipo_pago').children().remove().end();
                $("#list").jqGrid("clearGridData", true);

                ////////////////////llamar pagos primera parte ///////////////

                $.getJSON('retornar_pagos_compra.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 10) {
                            $("#fecha_actual").val(data[i]);
                            $("#hora_actual").val(data[i + 1]);
                            $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                            $("#id_proveedor").val(data[i + 4]);
                            $("#tipo_docu").val(data[i + 5]);
                            $("#ruc_ci").val(data[i + 6]);
                            $("#empresa").val(data[i + 7]);
                            $("#forma_pago").val(data[i + 8]);
                            $("#tipo_pago").append('<option value=' + data[i + 9] + ' selected>' + data[i + 9] + '</option>');
                        }
                    }
                });

                $.getJSON('retornar_pagos_compra2.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 8) {
                            var datarow = { ids_pagos: data[i], num_factura: data[i + 1], tipo_factura: data[i + 2], fecha_factura: data[i + 3], totalcxc: data[i + 4], valor_pagado: data[i + 5], saldo: data[i + 6] };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                            $("#observaciones").val(data[i + 7]);
                        }
                    }
                });
                /////////////////////////////////////////////////// 
                $("#buscar_cuentas_pagar").dialog("close");
            } else {
                alertify.alert("Seleccione una Cuenta a Pagar");
            }
        }
    });

    jQuery(window).bind('resize', function () {
        jQuery("#list").setGridWidth(jQuery('#grid_container').width(), true);
    }).trigger('resize');

}


var tipoentidad = "proveedor";
function iniRadiosEntidades() {

    let funchange = function (e) {
        $("#ruc_ci").val("");
        $("#empresa").val("");
        $("#id_proveedor").val("");
        let checkautoc = $("#ruc_ci").data('ui-autocomplete');
        let source = "";
        if (!!checkautoc) {
            source = $("#ruc_ci").autocomplete("option", "source");
            console.log(source);
        }
        if (e.target.id == "entidad_pro") {
            tipoentidad = "proveedor";
            source = source.replace("cliente", tipoentidad);
        } else if (e.target.id == "entidad_cli") {
            tipoentidad = "cliente";
            source = source.replace("proveedor", tipoentidad);
        }
        if (!!source) {
            $("#ruc_ci").autocomplete({
                source: source
            });
        }

    }
    $("#entidad_pro").change(funchange);
    $("#entidad_cli").change(funchange);
}
//function comprobar2(){
//    if(parseFloat($("#valor_pagado").val())<= parseFloat($("#saldo2").val())){
//
//    }else{
//        alert("Error.. el valor supero el saldo");
//        $("#valor_pagado").val("");
//        $("#valor_pagado").focus();
//    }
//}

var mostrandoAnularDialog = false;
var formaPagoAnular = "";

function iniTablaPagosRealizados() {
    jQuery("#list_pagosr").jqGrid({
        url: 'xmlBuscarPagosRealizados.php',
        datatype: 'xml',
        colNames: ['ID', 'FECHA PAGO', 'FORMA PAGO', 'VALOR PAGADO', 'OBSERVACIONES', ''],
        colModel: [
            { name: 'id_pagos_cobrar', index: 'id_pagos_cobrar', editable: false, search: false, align: 'center', frozen: true, width: 50 },
            { name: 'fecha_actual', index: 'fecha_actual', editable: false, search: false, align: 'left', frozen: true, width: 100 },
            { name: 'forma_pago', index: 'forma_pago', editable: true, search: false, align: 'left', frozen: true, width: 100 },
            { name: 'valor_pagado', index: 'valor_pagado', editable: true, search: false, align: 'right', frozen: true, width: 100 },
            { name: 'observaciones', index: 'observaciones', editable: true, search: false, align: 'left', frozen: true, width: 200 },
            {
                name: 'anular',
                index: 'id_pagos_cobrar',
                search: false,
                frozen: true,
                width: 80,
                formatter: function (cellvalue, options, rowObject) {
                    return `<div><button id="anular_pagor_${cellvalue}" type="button" class="btn btn-danger"><i class="fa fa-times"></i> Anular</button></div>`;
                }
            }
        ],
        rowNum: 10,
        width: 760,
        height: 220,
        rowList: [10, 20, 30],
        pager: jQuery('#pager_pagosr'),
        sortname: 'fecha_actual',
        shrinkToFit: true,
        sortorder: 'asc',
        viewrecords: true,
        ondblClickRow: function () {
        },
        afterInsertRow: function (rowid, rowdata, rowelem) {
            let idpago = rowid;
            let idcxc = $("#ids").val();
            $(`#anular_pagor_${rowid}`).click(function (é) {
                let formap = rowdata["forma_pago"];
                formaPagoAnular = formap;
                if (formap !== 'CONTADO' && formap != 'CHEQUE') {
                    alertify.alert("<b>Solo puede anular pagos realizados con las formas de pago CHEQUE y CONTADO.</b>");
                    $("#alertify-ok").css({ "background-color": "red" });
                    return;
                }

                $("#clave_permiso").dialog("option", "height", 335);

                $("#clave_permiso").dialog("open");
                $("#btnAceptar").off("click");
                $("#btnAceptar").click(function (e) {
                    anularPago(
                        idcxc,
                        idpago,
                        rowdata["valor_pagado"],
                        $("#tipo_pago").val(),
                        $("#otros_val_p").val(),
                        $("#fecha_anulado").val(),
                        formap
                    );
                });
            });
        }
    }).jqGrid('navGrid', '#pager_pagosr',
        {
            add: false,
            edit: false,
            del: false,
            refresh: true,
            search: false,
            view: false
        },
        {
            recreateForm: true, closeAfterEdit: true, checkOnUpdate: true, reloadAfterSubmit: true, closeOnEscape: true
        },
        {
            reloadAfterSubmit: true, closeAfterAdd: true, checkOnUpdate: true, closeOnEscape: true,
            bottominfo: "Todos los campos son obligatorios son obligatorios"
        },
        {
            width: 300, closeOnEscape: true
        },
        {
            closeOnEscape: true,
            multipleSearch: false, overlay: false
        },
        {
        },
        {
            closeOnEscape: true
        }
    );
    /* $(window).bind('resize', function () {
        jQuery("#list_pagosr").setGridWidth($('#pager_pagosr').width());
    }).trigger('resize'); */
}

function cargarTablaPagosRealizados(tipopago, numfactura, copraog) {
    let params = {
        page: 1,
        url: `xmlBuscarPagosRealizados.php?tipo_pago=${tipopago}&num_factura=${numfactura}&compra_gasto=${copraog}`
    };
    jQuery("#list_pagosr").jqGrid("clearGridData");
    jQuery("#list_pagosr").jqGrid("setGridParam", params);
    jQuery("#list_pagosr").trigger("reloadGrid");
}

function iniDialogosPermisos() {
    let dialogo3 = {
        autoOpen: false,
        resizable: false,
        width: 420,
        height: 220,
        modal: true,
        show: "explode",
        hide: "blind",
        close: function (event, ui) {
            limpiarDialogoPermisos();
        },
        open: function (event, ui) {
            mostrandoAnularDialog = true;
            $("#fecha_anulado").val(new Date().toLocaleDateString("fr-CA"));
        },
        buttons: [
            {
                text: "Anular",
                //icon: "ui-icon-heart",
                click: function () {
                    //$(this).dialog("close");
                    validar_acceso();
                },
                showText: false
            },
            {
                text: "Cancelar",
                //icon: "ui-icon-heart",
                click: function () {
                    $(this).dialog("close");
                },
                showText: false
            }
        ]
    };
    let dialogo4 = {
        autoOpen: false,
        resizable: false,
        width: 300,
        height: 150,
        modal: true,
        show: "explode",
        hide: "blind",
    };

    $("#clave_permiso").dialog(dialogo3);
    $("#seguro").dialog(dialogo4);

    $("#btnSalir").click(function (e) {
        cerrarDialogosAnularPago();
    });
    $("#fecha_anulado").val(new Date().toLocaleDateString("fr-CA"));
}

function validar_acceso() {
    if ($("#clave").val() == "") {
        $("#clave").focus();
        alertify.alert("Ingrese la clave");
    } else {
        if (!validarDatosAnulacion()) {
            return;
        }
        $.ajax({
            url: "../../procesos/validar_acceso.php",
            type: "POST",
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
            },
        });
    }
}

function limpiarDialogoPermisos() {
    $("#clave").val("");
    $("#anulacionComentario").val("");
    $("#otros_val_p").val("");
    $("#fecha_anulado").val("");
    mostrandoAnularDialog = false;
    formaPagoAnular = "";
}

function anularPago(idcxp, idpago, valorpago, tipop, otrosval, fecanulado, formap) {
    return $.ajax({
        method: "POST",
        url: "anular_pago.php",
        dataType: "JSON",
        data: {
            id_cxp: idcxp,
            id_pago: idpago,
            valor_p: valorpago,
            tipo_p: tipop,
            otros_val: otrosval,
            fecha_anulado: fecanulado,
            forma_pago: formap
        },
        success: function (data) {
            if (data == 1) {
                cargarTablaPagosRealizados($("#tipo_pago").val(), $("#num_factura").val(), $("#tipo_factura").val());
                cargarTablaFacturas($("#id_proveedor").val());
                $("#list").jqGrid('delRowData', $("#num_factura").val());
                alertify.success("Pago anulado correctamente.");
            } else {
                alertify.error("No se pudo anular el pago.");
            }
            cerrarDialogosAnularPago();
        }
    });
}

function cerrarDialogosAnularPago() {
    $("#clave_permiso").dialog("close");
    $("#seguro").dialog("close");
}

function cargarTablaFacturas(idproveedor) {
    $("#list2").jqGrid('setGridParam', {
        url: 'xmlFacturas_compra.php?id_proveedor=' + idproveedor + '&tipo=' + $("#tipo_pago").val() + '&canceladas=true',
        datatype: 'xml',
        gridComplete: function () {
            cargarDatosFacturaCargada();
            $("#list2").jqGrid('setGridParam', {
                gridComplete: function () { }
            });
        }
    }).trigger('reloadGrid');
}

function cargarDatosFacturaCargada() {
    if (!!$("#ids").val()) {
        let ret = jQuery("#list2").jqGrid('getRowData', $("#ids").val());
        $("#ids").val(ret.ids);
        $("#num_factura").val(ret.num_factura);
        $("#tipo_factura").val(ret.tipo_factura);
        $("#fecha_factura").val(ret.fecha_factura);
        $("#totalcxc").val(ret.totalcxc);
        $("#saldo2").val(ret.saldo);
    }
}

function validarDatosAnulacion() {
    if ($("#fecha_anulado").val() == "") {
        alertify.alert("<b>Indique la fecha de anulación.</b>", function () {
            $("#fecha_anulado").focus();
        });
        return false;
    }
    return true;
}
