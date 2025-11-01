$(document).on("ready", inicio);
function evento(e) {
    e.preventDefault();
}

function openPDF() {
    window.open('../../ayudas/ayuda.pdf');
}

var dialogos =
{
    autoOpen: false,
    resizable: false,
    width: 860,
    height: 560,
    modal: true
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
var dialogo3 = {
    autoOpen: false,
    resizable: false,
    width: 400,
    height: 210,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind"
}
var dialogo4 = {
    autoOpen: false,
    resizable: false,
    width: 240,
    height: 150,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind"
}
var dialogo2 =
{
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 350,
    modal: true,
    // position: "top",
    show: "explode",
    hide: "blind"
}

function abrirDialogo(e) {
    e.preventDefault();
    $("#prod").dialog("open");

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
    if (hours >= 12) {
        dn = "PM";
        if (hours > 12) {
            hours = hours - 12;
        }
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
function eliminar_anticipo() {

    if ($("#ruc_ci").val() === "") {
        alertify.error("Debe buscar un anticipo");
    } else {
        $("#clave_permiso_ven").dialog("open");
    }
}
function validar_acceso() {

    if ($("#clave").val() == "") {
        $("#clave").focus();
        alertify.error("Ingrese la clave");
    } else {
        $.ajax({
            url: '../../procesos/validar_acceso.php',
            type: 'POST',
            data: "clave=" + $("#clave").val(),
            success: function (data) {
                var val = data;
                if (val == 0) {
                    $("#clave").val("");
                    $("#clave").focus();
                    alertify.error("Error... La clave es incorrecta, ingrese nuevamente");
                } else {
                    if (val == 1) {
                        $("#seguro_ven").dialog("open");
                    }
                }
            }
        });
    }
}
function cancelar() {
    $("#seguro_ven").dialog("close");
    $("#clave_permiso_ven").dialog("close");
    $("#clave").val("");
}

function cancelar_acceso() {
    $("#clave_permiso_ven").dialog("close");
    $("#clave").val("");
}
function comprobar() {
    if ($("#id_cliente").val() === "") {
        $("#ruc_ci").focus();
        alertify.error("Ingrese un cliente");
    } else {
        if ($("#secuencial").val() === "") {
            $("#secuencial").focus();
            alertify.error("Ingrese num comprobante");
        } else {
            if ($("#formaspago_mixto").val() === "") {
                $("#formaspago_mixto").focus();
                alertify.error("Seleccione Forma Pago");
            } else {
                if ($("#monto").val() === "") {
                    $("#monto").focus();
                    alertify.error("Ingrese el monto de anticipo");
                }
            }
        }
    }
}
function autocompletar_num() {
    var temp = "";
    var str = $("#secuencial").val();
    var res = str.split("-");

    var ele1 = res[0];
    var ele2 = res[1];
    var ele3 = res[2];

    var ele22 = ele3.substring(8, 9);
    var serie = ele3;

    var res_serie = serie.split("_");
    var sesult_serie = res_serie[0];
    for (var i = sesult_serie.length; i < 9; i++) {
        temp = temp + "0";
    }
    return temp;
}
function countfactura() {
    var temp2 = "";
    var serie = $("#secuencial").val();
    for (var i = serie.length; i < 5; i++) {
        temp2 = temp2 + "0";
    }
    return temp2;
}
function autocompletar() {
    var temp = "";
    var serie = $("#secuencial").val();
    for (var i = serie.length; i < 9; i++) {
        temp = temp + "0";
    }
    return temp;
}
function modificar_anticipo() {


    if ($("#id_cliente").val() === "") {
        $("#ruc_ci").focus();
        alertify.error("Ingrese un cliente");
    } else {
        if ($("#secuencial").val() === "") {
            $("#secuencial").focus();
            alertify.error("Ingrese Factura Preimpresa");
        } else {
            if ($("#fecha_registro").val() == "") {
                $("#fecha_registro").focus();
                alertify.error("Seleccione la Fecha Registro");
            } else {

                if ($("#formaspago_mixto").val() === "") {
                    $("#formaspago_mixto").focus();
                    alertify.error("Seleccione Forma Pago");
                } else {
                    if ($("#monto").val() === "") {
                        $("#monto").focus();
                        alertify.error("Ingrese el monto de la factura");
                    } else {
                        let monto = parseFloat($("#monto").val());


                        $("#btnModificar").attr("disabled", true);
                        $.ajax({
                            type: "POST",
                            url: "modificar_anticipo_cliente.php",
                            data: "id_cliente=" + $("#id_cliente").val() + "&comprobante=" + $("#comprobante").val() + "&fecha_actual=" + $("#fecha_actual").val() + "&hora_actual=" + $("#hora_actual").val() + "&secuencial=" + $("#secuencial").val() + "&monto=" + monto + "&formaspago_mixto=" + $("#formaspago_mixto").val() + "&fecha_registro=" + $("#fecha_registro").val() + "&idCuenta=" + $("#idCuenta").val() + "&comentario=" + $("#comentario").val(),
                            success: function (data) {
                                var val = data;
                                if (val == 0) {
                                    alertify.alert("Registro Guardado correctamente",
                                        function () {

                                            window.open("../../reportes/recibo_anticipo_c.php?hoja=A5&id=" + $("#comprobante").val(), '_blank');
                                            location.reload();
                                        });
                                } else {
                                    alertify.alert("Error, El anticipo ya tiene movimientos",
                                        function () {
                                            location.reload();
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
function guardar_cuenta() {


    if ($("#id_cliente").val() === "") {
        $("#ruc_ci").focus();
        alertify.error("Ingrese un cliente");
    } else {
        if ($("#secuencial").val() === "") {
            $("#secuencial").focus();
            alertify.error("Ingrese Factura Preimpresa");
        } else {
            if ($("#fecha_registro").val() == "") {
                $("#fecha_registro").focus();
                alertify.error("Seleccione la Fecha Registro");
            } else {
                $.ajax({
                    type: "POST",
                    url: "comparar_num_venta.php",
                    data: "num_fac=" + $("#secuencial").val(),
                    success: function (data) {
                        var val = data;
                        if (val == 1) {
                            $("#secuencial").val("");
                            $("#secuencial").focus();
                            alertify.error("Error... El número de factura ya existe");
                        } else {
                            if ($("#formaspago_mixto").val() === "") {
                                $("#formaspago_mixto").focus();
                                alertify.error("Seleccione Forma Pago");
                            } else {
                                if ($("#monto").val() === "") {
                                    $("#monto").focus();
                                    alertify.error("Ingrese el monto de la factura");
                                } else {
                                    let monto = parseFloat($("#monto").val());
                                    console.log(monto);

                                    $("#btnGuardar").attr("disabled", true);
                                    $.ajax({
                                        type: "POST",
                                        url: "guardar_anticipo_cliente.php",
                                        data: "id_cliente=" + $("#id_cliente").val() + "&comprobante=" + $("#comprobante").val() + "&fecha_actual=" + $("#fecha_actual").val() + "&hora_actual=" + $("#hora_actual").val() + "&secuencial=" + $("#secuencial").val() + "&monto=" + monto + "&formaspago_mixto=" + $("#formaspago_mixto").val() + "&fecha_registro=" + $("#fecha_registro").val() + "&idCuenta=" + $("#idCuenta").val() + "&comentario=" + $("#comentario").val(),
                                        success: function (data) {
                                            val = data;
                                            if (val == 1) {
                                                alertify.alert("Registro Guardado correctamente",
                                                    function () {
                                                        window.open("../../reportes/recibo_anticipo_c.php?hoja=A5&id=" + $("#comprobante").val(), '_blank');
                                                        location.reload();
                                                    });
                                            }
                                        }
                                    });
                                }
                            }
                        }
                    }
                });
            }
        }
    }

}

function flecha_atras() {
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "anticipo_clientes" + "&id_tabla=" + "id_anticipo_clientes" + "&tipo=" + 1,
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#comprobante").val(val);
                var valor = $("#comprobante").val();

                /////////////////////////////////////////////////
                $("#btnGuardar").attr("disabled", true);
                //                $("#btnModificar").attr("disabled", true);
                //                $("#ruc_ci").attr("disabled", "disabled");
                //                $("#nombres_completos").attr("disabled", "disabled");
                //                $("#secuencial").attr("disabled", "disabled");
                //                $("#monto").attr("disabled", "disabled");
                $("#id_cliente").val("");
                $("#ruc_ci").val("");
                $("#nombres_completos").val("");
                $("#secuencial").val("");
                $("#monto").val("");

                ///////////////////llamar cuentas flechas primera parte/////
                $.getJSON('retornar_anticipo_clientes.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 12) {
                            $("#fecha_actual").val(data[i]);
                            $("#hora_actual").val(data[i + 1]);
                            $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                            $("#id_cliente").val(data[i + 4]);
                            $("#ruc_ci").val(data[i + 5]);
                            $("#nombres_completos").val(data[i + 6]);
                            $("#secuencial").val(data[i + 7]);
                            $("#formaspago_mixto").val(data[i + 8]);
                            $("#monto").val(data[i + 9]);
                            $("#comentario").val(data[i + 10]);
                            if (data[i + 11] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulada"));
                                $("#estado h3").css("color", "red");
                                $("#btnEliminar").attr("disabled", "disabled");
                                $("#btnModificar").attr("disabled", true);
                            } else {
                                $("#estado h3").remove();
                                $("#btnEliminar").attr("disabled", "disabled");
                                $("#btnEliminar").attr("disabled", false);
                                $("#btnModificar").attr("disabled", false);

                            }
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
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "anticipo_clientes" + "&id_tabla=" + "id_anticipo_clientes" + "&tipo=" + 2,
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#comprobante").val(val);
                var valor = $("#comprobante").val();

                //////////////////////////////////////////////////
                $("#btnGuardar").attr("disabled", true);
                $("#btnModificar").attr("disabled", true);
                $("#ruc_ci").attr("disabled", "disabled");
                $("#nombres_completos").attr("disabled", "disabled");
                $("#secuencial").attr("disabled", "disabled");
                $("#monto").attr("disabled", "disabled");
                $("#id_cliente").val("");
                $("#ruc_ci").val("");
                $("#nombres_completos").val("");
                $("#secuencial").val("");
                $("#monto").val("");

                $.getJSON('retornar_anticipo_clientes.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 12) {
                            $("#fecha_actual").val(data[i]);
                            $("#hora_actual").val(data[i + 1]);
                            $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                            $("#id_cliente").val(data[i + 4]);
                            $("#ruc_ci").val(data[i + 5]);
                            $("#nombres_completos").val(data[i + 6]);
                            $("#secuencial").val(data[i + 7]);
                            $("#formaspago_mixto").val(data[i + 8]);
                            $("#monto").val(data[i + 9]);
                            $("#comentario").val(data[i + 10]);
                            if (data[i + 11] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulada"));
                                $("#estado h3").css("color", "red");
                                $("#btnEliminar").attr("disabled", "disabled");
                                $("#btnModificar").attr("disabled", true);
                            } else {
                                $("#estado h3").remove();
                                $("#btnEliminar").attr("disabled", "disabled");
                                $("#btnEliminar").attr("disabled", false);
                                $("#btnModificar").attr("disabled", false);

                            }
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
        $("#id_cliente").val("");
        $("#nombres_completos").val("");
        $("#saldo").val("");
    }
}

function limpiar_campo2() {
    if ($("#nombres_completos").val() === "") {
        $("#id_cliente").val("");
        $("#ruc_ci").val("");
        $("#saldo").val("");
    }
}

function limpiar_cuenta() {
    location.reload();
}
function aceptar() {
    $.ajax({
        type: "POST",
        url: "eliminar_anticipo.php",
        data: "id=" + $("#comprobante").val(),
        success: function (data) {
            var val = data;
            if (val == 1) {
                alertify.error('Error.. El Anticipo tiene movimientos en el sistema');
                setTimeout(function () {
                    location.reload();
                }, 1000);
            } else {
                alertify.success('Anticipo Eliminado Correctamente');
                setTimeout(function () {
                    location.reload();
                }, 1000);
            }
        }
    });
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
    if ($("#num_oculto").val() == "") {
        $("#secuencial").val("");
    } else {
        var str = $("#num_oculto").val();
        var res = parseInt(str.substr(4, 16));
        res = res + 1;

        $("#secuencial").val(res);
        var a = autocompletar(res);
        var validado = a + "" + res;
        $("#secuencial").val(validado);
    }
    $("#formaspago_mixto").on("change", function () {
        if ($("#formaspago_mixto").val() == "Transferencias") {

            $("#cuenta_contable").attr("disabled", false);
            $("#btnCuenta").attr("disabled", false);
            $("#cuenta_contable").val("");
            $("#idCuenta").val("");

            //            $("#cheque_tarjeta").attr("disabled", true);
            //            $("#banco").attr("disabled", true);
        } else if ($("#formaspago_mixto").val() == "Contado" || $("#formaspago_mixto").val() == "Cheque") {

            $("#cuenta_contable").attr("disabled", true);
            $("#btnCuenta").attr("disabled", true);
            $("#cuenta_contable").val("");
            $("#idCuenta").val("");

            //            $("#cheque_tarjeta").attr("disabled", true);
            //            $("#banco").attr("disabled", true);

        }
    })
    alertify.set({ delay: 5000 });
    $("[data-mask]").inputmask();
    show();

    $("#btnGuardar").click(function (e) {
        e.preventDefault();
    });
    $("#btnModificar").click(function (e) {
        e.preventDefault();
    });
    $("#btnNuevo").click(function (e) {
        e.preventDefault();
    });
    $("#btnImprimir").click(function (e) {
        e.preventDefault();
    });
    $("#btnAtras").click(function (e) {
        e.preventDefault();
    });
    $("#btnAdelante").click(function (e) {
        e.preventDefault();
    });
    $("#btnEliminar").click(function (e) {
        e.preventDefault();
    });
    $("#btnEliminar").on("click", eliminar_anticipo);
    $("#cuentas").dialog(dialogo_cuenta);
    $("#btnModificar").on("click", modificar_anticipo);
    $("#btnCuenta").on("click", abrirCuenta);
    $("#btnGuardar").on("click", guardar_cuenta);
    $("#btnNuevo").on("click", limpiar_cuenta);
    $("#btnAtras").on("click", flecha_atras);
    $("#btnAdelante").on("click", flecha_siguiente);

    /////////////////////////// 
    $("#buscar_anticipo_cliente").dialog(dialogo2);
    $("#clave_permiso_ven").dialog(dialogo3);
    $("#seguro_ven").dialog(dialogo4);
    $("#btnBuscar").click(function (e) {
        e.preventDefault();
        $("#buscar_anticipo_cliente").dialog("open");
    });

    $("#ruc_ci").on("keyup", limpiar_campo);
    $("#nombres_completos").on("keyup", limpiar_campo2);
    $("#ruc_ci").on("keypress", enter);
    $("#secuencial").on("keypress", enter);
    $("#monto").on("keypress", enter);

    $("#btnImprimir").click(function () {
        //        window.open("../../reportes/comprobante_ingreso.php?hoja=A5&id=" + $("#comprobante").val(), '_blank');
        window.open("../../reportes/recibo_anticipo_c.php?hoja=A5&id=" + $("#comprobante").val(), '_blank');
    });
    $("#secuencial").attr("maxlength", "20");

    $("#monto").on("keypress", punto);
    $("#btnAceptar").on("click", aceptar);
    $("#btnSalir").on("click", cancelar);
    $("#btnAcceder").on("click", validar_acceso);
    $("#btnCancelar").on("click", cancelar_acceso);
    $("#ruc_ci").autocomplete({
        source: "buscar_anti_cliente2.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#ruc_ci").val(ui.item.value);
            $("#nombres_completos").val(ui.item.nombres_completos);
            $("#id_cliente").val(ui.item.id_cliente);
            //            $("#monto").val(ui.item.saldo);
            return false;
        },
        select: function (event, ui) {
            $("#ruc_ci").val(ui.item.value);
            $("#nombres_completos").val(ui.item.nombres_completos);
            $("#id_cliente").val(ui.item.id_cliente);
            //            $("#monto").val(ui.item.saldo);
            return false;
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };

    $("#nombres_completos").autocomplete({
        source: "buscar_anti_cliente_pagos.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#nombres_completos").val(ui.item.value);
            $("#ruc_ci").val(ui.item.ruc_ci);
            $("#id_cliente").val(ui.item.id_cliente);
            $("#monto").val(ui.item.saldo);
            return false;
        },
        select: function (event, ui) {
            $("#nombres_completos").val(ui.item.value);
            $("#ruc_ci").val(ui.item.ruc_ci);
            $("#id_cliente").val(ui.item.id_cliente);
            $("#monto").val(ui.item.saldo);
            return false;
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };

    $('#fecha_actual').datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');
    /////////////44/////

    $(window).on("resize", function () {
        jQuery("#list44").setGridWidth($('#pager44').width());
    }).trigger('resize');
    jQuery("#list44").jqGrid({
        url: 'xmlPlanCuentas.php',
        datatype: 'xml',
        colNames: ['Cod. Cuenta', 'Descripcion', 'Cuenta'],
        colModel: [
            { name: 'idcontable', index: 'idcontable', editable: true, align: 'left', width: '120', search: true, frozen: true, formoptions: { elmsuffix: " (*)" }, editrules: { required: true } },
            { name: 'ccontable', index: 'ccontable', editable: true, align: 'left', width: '490', search: true, frozen: true, formoptions: { elmsuffix: " (*)" }, editrules: { required: true } },
            { name: 'cuenta', index: 'cuenta', editable: true, align: 'left', width: '120', search: true, frozen: true, formoptions: { elmsuffix: " (*)" }, editrules: { required: true } }
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        height: 255,
        pager: jQuery('#pager44'),
        sortname: 'codigo_plan',
        shrinkToFit: false,
        sortordezr: 'asc',
        caption: 'Plan de Cuentas',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list44").jqGrid('getGridParam', 'selrow');
            jQuery('#list44').jqGrid('restoreRow', id);
            var ret = jQuery("#list44").jqGrid('getRowData', id);
            var ccuenta = jQuery("#list44").jqGrid('getCell', id, 0) + "  -  " + jQuery("#list44").jqGrid('getCell', id, 1);
            $("#idCuenta").val(id);
            $("#cuenta_contable").val(ccuenta);
            //            console.log(ccuenta);
            var string = ccuenta;
            var string1 = string.split("-");
            console.log(string1);
            var part1 = string1[1]; // 123
            //            $("#banco").val(part1);
            document.getElementById("cuenta_contable").readOnly = true;
            $("#cuentas").dialog("close");
        }
    }).jqGrid('navGrid', '#pager44',
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
    jQuery("#list44").setGridWidth($('#pager44').width());

    jQuery("#list2").jqGrid({
        url: 'xmlBuscarAnticipoClientes.php',
        datatype: 'xml',
        colNames: ['ID', 'IDENTIFICACIÓN', 'CLIENTE', 'FACTURA NRO.', 'MONTO TOTAL', 'FECHA', 'FECHA EMISION'],
        colModel: [
            { name: 'id_c_cobrarexternas', index: 'id_c_cobrarexternas', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },
            { name: 'identificacion', index: 'identificacion', editable: false, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 150 },
            { name: 'nombres_cli', index: 'nombres_cli', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 200 },
            { name: 'secuencial', index: 'secuencial', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 200 },
            { name: 'monto', index: 'monto', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'fecha_nota', index: 'fecha_nota', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'fecha_registro', index: 'fecha_registro', editable: true, search: false, hidden: true, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
        ],
        rowNum: 30,
        width: 750,
        height: 220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager2'),
        sortname: 'id_anticipo_clientes',
        sortorder: 'asc',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
            jQuery('#list2').jqGrid('restoreRow', id);

            if (id) {
                var ret = jQuery("#list2").jqGrid('getRowData', id);
                var valor = ret.id_c_cobrarexternas;
                /////////////agregregar cuentas cobrar////////
                $("#comprobante").val(valor);
                $("#btnGuardar").attr("disabled", true);
                $("#btnModificar").attr("disabled", true);
                $("#ruc_ci").attr("disabled", "disabled");
                $("#nombres_completos").attr("disabled", "disabled");
                $("#secuencial").attr("disabled", "disabled");
                $("#monto").attr("disabled", "disabled");
                $("#id_cliente").val("");
                $("#ruc_ci").val("");
                $("#nombres_completos").val("");
                $("#secuencial").val("");
                $("#monto").val("");
                $.getJSON('retornar_anticipo_clientes.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 12) {
                            $("#fecha_actual").val(data[i]);
                            $("#hora_actual").val(data[i + 1]);
                            $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                            $("#id_cliente").val(data[i + 4]);
                            $("#ruc_ci").val(data[i + 5]);
                            $("#nombres_completos").val(data[i + 6]);
                            $("#secuencial").val(data[i + 7]);
                            $("#formaspago_mixto").val(data[i + 8]);
                            $("#monto").val(data[i + 9]);

                            $("#comentario").val(data[i + 10]);
                            if (data[i + 11] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulada"));
                                $("#estado h3").css("color", "red");
                                $("#btnEliminar").attr("disabled", "disabled");
                                $("#btnModificar").attr("disabled", true);
                            } else {
                                $("#estado h3").remove();
                                $("#btnEliminar").attr("disabled", "disabled");
                                $("#btnEliminar").attr("disabled", false);
                                $("#btnModificar").attr("disabled", false);

                            }
                        }
                    }
                });
                $("#buscar_anticipo_cliente").dialog("close");
            } else {
                alertify.alert("Seleccione una Cuenta");
            }
        }

    }).jqGrid('navGrid', '#pager2',
        {
            add: false,
            edit: false,
            del: false,
            refresh: true,
            search: true,
            view: true
        }, {
        recreateForm: true,
        closeAfterEdit: true,
        checkOnUpdate: true,
        reloadAfterSubmit: true,
        closeOnEscape: true
    },
        {
            reloadAfterSubmit: true,
            closeAfterAdd: true,
            checkOnUpdate: true,
            closeOnEscape: true,
            bottominfo: "Todos los campos son obligatorios"
        },
        {
            width: 300,
            closeOnEscape: true
        },
        {
            closeOnEscape: true,
            multipleSearch: false,
            overlay: false
        },
        {
        },
        {
            closeOnEscape: true
        });

    jQuery("#list2").jqGrid('navButtonAdd', '#pager2', {
        caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
            jQuery('#list2').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list2").jqGrid('getRowData', id);
                var valor = ret.id_c_cobrarexternas;
                /////////////agregregar cuentas cobrar////////
                $("#comprobante").val(valor);
                $("#btnGuardar").attr("disabled", true);
                $("#btnModificar").attr("disabled", true);
                $("#ruc_ci").attr("disabled", "disabled");
                $("#nombres_completos").attr("disabled", "disabled");
                $("#secuencial").attr("disabled", "disabled");
                $("#monto").attr("disabled", "disabled");
                $("#id_cliente").val("");
                $("#ruc_ci").val("");
                $("#nombres_completos").val("");
                $("#secuencial").val("");
                $("#monto").val("");
                $.getJSON('retornar_anticipo_clientes.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 12) {
                            $("#fecha_actual").val(data[i]);
                            $("#hora_actual").val(data[i + 1]);
                            $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                            $("#id_cliente").val(data[i + 4]);
                            $("#ruc_ci").val(data[i + 5]);
                            $("#nombres_completos").val(data[i + 6]);
                            $("#secuencial").val(data[i + 7]);
                            $("#formaspago_mixto").val(data[i + 8]);
                            $("#monto").val(data[i + 9]);

                            $("#comentario").val(data[i + 10]);
                            if (data[i + 11] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulada"));
                                $("#estado h3").css("color", "red");
                                $("#btnEliminar").attr("disabled", "disabled");
                                $("#btnModificar").attr("disabled", true);
                            } else {
                                $("#estado h3").remove();
                                $("#btnEliminar").attr("disabled", "disabled");
                                $("#btnEliminar").attr("disabled", false);
                                $("#btnModificar").attr("disabled", false);

                            }
                        }
                    }
                });
                $("#buscar_anticipo_cliente").dialog("close");
            } else {
                alertify.alert("Seleccione una Cuenta");
            }
        }
    });
}



