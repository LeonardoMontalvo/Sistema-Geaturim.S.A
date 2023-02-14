$(document).on("ready", inicio);
function evento(e) {
    e.preventDefault();
}

function openPDF() {
    window.open('../../ayudas/ayuda.pdf');
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

function ValidNum() {
    if (event.keyCode < 48 || event.keyCode > 57) {
        event.returnValue = false;
    }
    return true;
}
var boton = 0;
var dialogos =
        {
            autoOpen: false,
            resizable: false,
            width: 860,
            height: 350,
            modal: true
        };

var dialogo3 =
        {
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

var dialogo5 = {
    autoOpen: false,
    resizable: false,
    width: 500,
    height: 400,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind"
}
function dialogoAnular() {
    var dialogo3 = {
        autoOpen: false,
        resizable: false,
        width: 500,
        height: 250,
        modal: true,
        show: "explode",
        hide: "blind"
    }
    $("#clave_permiso").dialog(dialogo3);
    $("#btnAnular").attr('disabled', true);
}

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

var dialogo_conciliacion = {
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 400,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind"
}


function abrirCuenta() {
    $("#cuentas").dialog("open");
}


function confirmar() {
    $("#clave_permiso").dialog("open");
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
                    alertify.error("Error... La clave es incorrecta ingrese nuevamente");
                } else {
                    if (val == 1) {
                        $("#seguro").dialog("open");
                    }
                }
            }
        });
    }
}
function abrir_pdf_unido() {


    window.open("../../reportes/conciliacion.php?hoja=A4&inicio=" + $("#fecha_inicio").val() + "&fin=" + $("#fecha_fin").val() + "&id_plan=" + $("#id_plan").val() + "&comprobante=" + $("#comprobante").val() + "&id_plan1=" + $("#descripcion").val(), '_blank');
    setTimeout(function () {
        location.reload();
    }, 3000);

}



function cancelar() {
    $("#seguro").dialog("close");
    $("#clave_permiso").dialog("close");
    $("#clave").val("");
}

function cancelar_acceso() {
    $("#clave_permiso").dialog("close");
    $("#clave").val("");
}


function Valida_punto() {
    var key;
    if (window.event) {
        key = event.keyCode;
    } else if (event.which) {
        key = event.which;
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

function ValidNum() {
    if (event.keyCode < 48 || event.keyCode > 57) {
        event.returnValue = false;
    }
    return true;
}


function enter(e) {
    if (e.which === 13 || e.keyCode === 13) {
        entrar1();
        return false;
    }
    return true;
}

function enter2(e) {
    if (e.which === 13 || e.keyCode === 13) {
        entrar2();
        return false;
    }
    return true;
}

function enter3(e) {
    if (e.which === 13 || e.keyCode === 13) {
        entrar3();
        return false;
    }
    return true;
}

function enter4(e) {
    if (e.which === 13 || e.keyCode === 13) {
        entrar4();
        return false;
    }
    return true;
}

function enter5(e) {
    if (e.which === 13 || e.keyCode === 13) {
        entrar5();
        return false;
    }
    return true;
}

function enter6(e) {
    if (e.which === 13 || e.keyCode === 13) {
        entrar6();
        return false;
    }
    return true;
}

// function porcenta(){
//     var resta = parseFloat($("#precio_minorista").val() - $("#precio_compra").val());
//     var entero = resta * 100;
//     var val = Math.round(entero / parseFloat($("#precio_compra").val()));
//    $("#utilidad_minorista").val(val); 
// }

// function porcenta2(){
//     var resta = parseFloat($("#precio_mayorista").val() - $("#precio_compra").val());
//     var entero = resta * 100;
//     var val = Math.round(entero / parseFloat($("#precio_compra").val()));
//     $("#utilidad_mayorista").val(val);    
// }
function modificar_conciliacion() {

    var tam = jQuery("#list7").jqGrid("getRowData");
    if (tam.length == 0) {
        $("#descripcion").focus();
        alertify.error("Error... Buscar un plan de cuentas bancos");
    } else {
        $("#btnGuardar").attr("disabled", true);
        var v1 = new Array();
        var v2 = new Array();
        var v3 = new Array();
        var v4 = new Array();
        var v5 = new Array();
        var v6 = new Array();
        var v7 = new Array();

        var string_v1 = "";
        var string_v2 = "";
        var string_v3 = "";
        var string_v4 = "";
        var string_v5 = "";
        var string_v6 = "";
        var string_v7 = "";

        var fil = jQuery("#list7").jqGrid("getRowData");

        for (var i = 0; i < fil.length; i++) {
            var datos = fil[i];
            v1[i] = datos['id_transacciones'];
            v2[i] = datos['fecha'];
            v3[i] = datos['comprobante'];
            v4[i] = datos['t_transaccion'];
            v5[i] = datos['monto'];
            v6[i] = datos['orden'];
            v7[i] = datos['debe'];

        }
        for (i = 0; i < fil.length; i++) {
            string_v1 = string_v1 + "|" + v1[i];
            string_v2 = string_v2 + "|" + v2[i];
            string_v3 = string_v3 + "|" + v3[i];
            string_v4 = string_v4 + "|" + v4[i];
            string_v5 = string_v5 + "|" + v5[i];
            string_v6 = string_v6 + "|" + v6[i];
            string_v7 = string_v7 + "|" + v7[i];

        }
        var grid = $("#list7");
        var rowKey = grid.getGridParam("selrow");
        if (!rowKey) {

        } else {
            var selectedIDs = grid.getGridParam("selarrrow");

        }
        $.ajax({
            type: "POST",
            url: "modificar_conciliacion_bancaria.php",
            data: "id_plan=" + $("#id_plan").val() + "&fecha_inicio=" + $("#fecha_inicio").val() + "&fecha_fin=" + $("#fecha_fin").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&campo6=" + string_v6 + "&campo7=" + string_v7 + "&observacion=" + $("#observacion").val() + "&fecha_actual=" + $("#fecha_actual").val() + "&hora_actual=" + $("#hora_actual").val() + "&ids=" + selectedIDs + "&comprobante=" + $("#comprobante").val(),
            success: function (data) {
                var val = data;
                if (val == "00" || val == "0") {

                    alertify.alert("ERROR...LO SELECCIONADO YA SE ENCUANTRA GUARDADO");
                } else {
                    abrir_pdf_unido();
                }
            }
        });
    }
}

function guardar_conciliacion() {
    var tam = jQuery("#list7").jqGrid("getRowData");
    if (tam.length == 0) {
        $("#descripcion").focus();
        alertify.error("Error... Buscar un plan de cuentas bancos");
    } else {
        $("#btnGuardar").attr("disabled", true);
        var v1 = new Array();
        var v2 = new Array();
        var v3 = new Array();
        var v4 = new Array();
        var v5 = new Array();
        var v6 = new Array();
        var v7 = new Array();

        var string_v1 = "";
        var string_v2 = "";
        var string_v3 = "";
        var string_v4 = "";
        var string_v5 = "";
        var string_v6 = "";
        var string_v7 = "";

        var fil = jQuery("#list7").jqGrid("getRowData");

        for (var i = 0; i < fil.length; i++) {
            var datos = fil[i];
            v1[i] = datos['id_transacciones'];
            v2[i] = datos['fecha'];
            v3[i] = datos['comprobante'];
            v4[i] = datos['t_transaccion'];
            v5[i] = datos['monto'];
            v6[i] = datos['orden'];
            v7[i] = datos['debe'];

        }
        for (i = 0; i < fil.length; i++) {
            string_v1 = string_v1 + "|" + v1[i];
            string_v2 = string_v2 + "|" + v2[i];
            string_v3 = string_v3 + "|" + v3[i];
            string_v4 = string_v4 + "|" + v4[i];
            string_v5 = string_v5 + "|" + v5[i];
            string_v6 = string_v6 + "|" + v6[i];
            string_v7 = string_v7 + "|" + v7[i];

        }
        var grid = $("#list7");
        var rowKey = grid.getGridParam("selrow");
        if (!rowKey) {

        } else {
            var selectedIDs = grid.getGridParam("selarrrow");

        }
        $.ajax({
            type: "POST",
            url: "guardar_conciliacion.php",
            data: "id_plan=" + $("#id_plan").val() + "&fecha_inicio=" + $("#fecha_inicio").val() + "&fecha_fin=" + $("#fecha_fin").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&campo6=" + string_v6 + "&campo7=" + string_v7 + "&observacion=" + $("#observacion").val() + "&fecha_actual=" + $("#fecha_actual").val() + "&hora_actual=" + $("#hora_actual").val() + "&ids=" + selectedIDs,
            success: function (data) {
                var val = data;
                if (val == "00" || val == "0") {

                    alertify.alert("ERROR...LO SELECCIONADO YA SE ENCUENTRA GURADADO");
                } else {
                    abrir_pdf_unido();
                }
            }
        });
    }
}
function aceptar() {
    if ($("#comprobante").val() != "" && $("#comprobante").val() > 0) {
        $.ajax({
            type: "POST",
            url: "eliminar_conciliacion.php",
            data: "idConciliacion=" + $("#comprobante").val(),
            success: function (data) {
                var val = data;
                if (val != 0) {
                    alertify.alert("Conciliación Eliminada Correctamente", function () {
                        location.reload();
                    });
                }
            }
        });
    } else {
        alertify.alert("La Conciliación Bancaria no se encuentra Guardada");
    }
}

function flecha_atras() {
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "conciliacion" + "&id_tabla=" + "id_conciliacion" + "&tipo=" + 1,
        success: function (data) {
            var val = data;
            if (val != "") {

                $("#comprobante").val(val);


                $("#btnGuardar").attr("disabled", true);

                $("#estado h3").remove();
                var descripciones = "";
                var valores = "";
                var x = 0;
                $.getJSON('retornar_conciliacion_bancaria.php?com=' + val, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 19) {
                            $("#id_plan").val(data[i]);
                            $("#codigo_plan").val(data[i + 1]);
                            $("#descripcion").val(data[i + 2]);

                            $("#fecha_inicio").val(data[i + 3]);
                            $("#fecha_fin").val(data[i + 4]);


                            if (data[i + 5 ] == "Pasivo") {
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

                $.getJSON('retornar_conciliacion_bancaria_grid.php?com=' + val, function (data) {
                    $("#list7").jqGrid("clearGridData", true);
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 8) {
                            var datarow = {
                                id_transacciones: data[i],
                                fecha: data[i + 1],
                                comprobante: data[i + 2],
                                t_transaccion: data[i + 3],
                                debe: data[i + 4],
                                monto: data[i + 5],
                                orden: data[i + 6],
                                estado: data[i + 7],
                            };


                            var su = jQuery("#list7").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                    var total_nominat = 0;
                    var total_total_nominat = 0;
                    var fil = jQuery("#list7").jqGrid("getRowData");
                    for (var t = 0; t < fil.length; t++) {
                        var dd = fil[t];

                        total_nominat = dd['debe'];
                        total_total_nominat = parseFloat(total_total_nominat) + parseFloat(total_nominat);
                    }

                    $("#debe").val(total_total_nominat.toFixed(4));
                    /////////////////////////////MONTO
                    var total_nominat_m = 0;
                    var total_total_nominat_m = 0;
                    var fil = jQuery("#list7").jqGrid("getRowData");
                    for (var t = 0; t < fil.length; t++) {
                        var dd = fil[t];

                        total_nominat_m = dd['monto'];
                        total_total_nominat_m = parseFloat(total_total_nominat_m) + parseFloat(total_nominat_m);
                    }

                    $("#haber").val(total_total_nominat_m.toFixed(4));
                });

                // Fin
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
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "conciliacion" + "&id_tabla=" + "id_conciliacion" + "&tipo=" + 2,
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#comprobante").val(val);


                $("#btnGuardar").attr("disabled", true);

                $("#estado h3").remove();
                var descripciones = "";
                var valores = "";
                var x = 0;
                $.getJSON('retornar_conciliacion_bancaria.php?com=' + val, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 6) {
                            $("#id_plan").val(data[i]);
                            $("#codigo_plan").val(data[i + 1]);
                            $("#descripcion").val(data[i + 2]);

                            $("#fecha_inicio").val(data[i + 3]);
                            $("#fecha_fin").val(data[i + 4]);

                            if (data[i + 5 ] == "Pasivo") {
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
                $.getJSON('retornar_conciliacion_bancaria_grid.php?com=' + val, function (data) {
                    $("#list7").jqGrid("clearGridData", true);
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 8) {
                            var datarow = {
                                id_transacciones: data[i],
                                fecha: data[i + 1],
                                comprobante: data[i + 2],
                                t_transaccion: data[i + 3],
                                debe: data[i + 4],
                                monto: data[i + 5],
                                orden: data[i + 6],
                                estado: data[i + 7],
                            };


                            var su = jQuery("#list7").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });
                // Fin
            } else {
                alertify.alert("No hay más registros superiores!!");
            }
        }
    });
}

function cargar_conciliacion() {
    var id = $("#descripcion").val();
    var f1 = $("#fecha_inicio").val();
    var f2 = $("#fecha_fin").val();
    var id_plan = $("#id_plan").val();
    if (id == "") {
        alertify.error("SELECCIONE CUENTA CONTABLE");
    } else {
        if (f1 == "") {
            alertify.error("SELECCIONE FECHA INICIO");
        } else {
            if (f2 == "") {
                alertify.error("SELECCIONE FECHA FIN");
            } else {
                $("#list7").jqGrid('setGridParam', {
                    url: 'xmlBuscarConciliacion_generada.php?id=' + id + "&f1=" + f1 + "&f2=" + f2 + "&id_plan=" + id_plan + "&comprobante=" + $("#comprobante").val(),
//                    datatype: 'xml',
//
//                    editable: false,
//                    colModel: [{name: 'id_transacciones', index: 'id_transacciones', editable: false, search: false, hidden: true, editrules: {edithidden: false}, align: 'center', frozen: true, width: 50},
//                        {name: 'fecha', index: 'fecha', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 20},
//                        {name: 'comprobante', index: 'comprobante', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 7},
//                        {name: 't_transaccion', index: 't_transaccion', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 10},
//                        {name: 'monto', index: 'monto', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 10},
//                        {name: 'orden', index: 'orden', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'left', frozen: true, width: 100},
//                    ],
                }).trigger('reloadGrid');

                totales();
            }

        }
    }







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
    if (hours == 0)
        hours = 12;
    if (minutes <= 9)
        minutes = "0" + minutes;
    if (seconds <= 9)
        seconds = "0" + seconds;
    $("#hora_actual").val(hours + ":" + minutes + ":" + seconds + " " + dn);

    setTimeout("show()", 1000);
}
function totales() {
    var debe = 0;
    var haber = 0;

    var total_debe = 0;
    var total_haber = 0;
    var fil = jQuery("#list7").jqGrid("getRowData");

    for (var t = 0; t < fil.length; t++) {
        var dd = fil[t];
        debe = dd['debe'];

        total_debe = parseFloat(total_debe) + parseFloat(debe);

        haber = dd['monto'];
        total_haber = parseFloat(total_haber) + parseFloat(haber);



    }

    $("#debe").val(total_debe.toFixed(2));
    $("#haber").val(total_haber.toFixed(2));

}
function confirmarAnulacion() {
    var dialogo4 = {
        autoOpen: false,
        resizable: false,
        width: 300,
        height: 150,
        modal: true,
        show: "explode",
        hide: "blind"
    }
    $("#seguro").dialog(dialogo4);
}



function marcarId() {
    var fil = jQuery("#list7").jqGrid("getRowData");
    for (var t = 0; t < fil.length; t++) {
        var dd = fil[t];
        var valor_si = dd['estado_val'];
        console.log("si2" + valor_si);
        if (valor_si == '0') {
            jQuery("#list7").jqGrid("setSelection", dd['id_transacciones']);
        }
    }
}
function inicio() {

    dialogoAnular();
    confirmarAnulacion();
    $("#btnImprimir").click(function (e) {
        e.preventDefault();
    });

    $("#btnImprimir").on("click", abrir_pdf_unido);


    // buscar producto codigo barras 
    $("#codigo_plan").autocomplete({
        source: "search.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#codigo_plan").val(ui.item.codigo_plan);
            $("#descripcion").val(ui.item.descripcion);
            $("#id_plan").val(ui.item.id_plan_cuentas);
            return false;
        },
        select: function (event, ui) {
            $("#codigo_plan").val(ui.item.codigo_plan);
            $("#descripcion").val(ui.item.descripcion);
            $("#id_plan").val(ui.item.id_plan_cuentas);
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<a>" + item.codigo_plan + "</a>")
                .appendTo(ul);
    };
    // fin


    // buscar producto articulo
    $("#descripcion").autocomplete({
        source: "buscar_producto.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#codigo_plan").val(ui.item.codigo_plan);
            $("#descripcion").val(ui.item.descripcion);
            $("#id_plan").val(ui.item.id_plan_cuentas);
            return false;
        },
        select: function (event, ui) {
            $("#codigo_plan").val(ui.item.codigo_plan);
            $("#descripcion").val(ui.item.descripcion);
            $("#id_plan").val(ui.item.id_plan_cuentas);
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<a>" + item.descripcion + "</a>")
                .appendTo(ul);
    };
    // fin
    alertify.set({
        delay: 1000
    });
    show();
    // cambiar idioma
    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);
    // fin
    $("#fecha_actual").datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');

    /////////////cambiar idioma///////
    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);

    function getDoc(frame) {
        var doc = null;

        try {
            if (frame.contentWindow) {
                doc = frame.contentWindow.document;
            }
        } catch (err) {
        }
        if (doc) {
            return doc;
        }
        try {
            doc = frame.contentDocument ? frame.contentDocument : frame.document;
        } catch (err) {

            doc = frame.document;
        }
        return doc;
    }


    $("#btnGuardar").click(function (e) {
        e.preventDefault();
    });
    $("#btnEliminar").click(function (e) {
        e.preventDefault();
        $("#clave_permiso").dialog("open");
    });
//    $("#btnImprimir").click(function (e) {
//        e.preventDefault();
//    });
    $("#btnCuenta").click(function (e) {
        e.preventDefault();
    });
    $("#btnAtras").click(function (e) {
        e.preventDefault();
    });
    $("#btnSiguiente").click(function (e) {
        e.preventDefault();
    });
    $("#btnNuevo").click(function (e) {
        e.preventDefault();
    });
    $("#btnBuscar").click(function (e) {
        e.preventDefault();
    });
    $("#btnBuscar_consi").click(function (e) {
        e.preventDefault();
    });

    $("#buscar_conciliacion").dialog(dialogo_conciliacion);
    $("#btnBuscar").click(function () {
        $("#buscar_conciliacion").dialog("open");
    });
    $("#btnModificar").click(function (e) {
        e.preventDefault();
    });
    $("#btnAceptar").click(function (e) {
        e.preventDefault();
        aceptar();
    });
    $("#btnGuardar").on("click", guardar_conciliacion);
//    $("#btnEliminar").on("click", eliminar_conciliacion);
    $("#btnAtras").on("click", flecha_atras);
    $("#btnSiguiente").on("click", flecha_siguiente);

    $("#btnSalir").on("click", cancelar);
    $("#btnAcceder").on("click", validar_acceso);
    $("#btnCancelar").on("click", cancelar_acceso);
    $("#btnCuenta").on("click", abrirCuenta);
    $("#btnBuscar_consi").on("click", cargar_conciliacion);
    $("#btnModificar").on("click", modificar_conciliacion);

//    $("#btnImprimir").on("click", function () {
//        $.ajax({
//            type: "POST",
//            url: "../../procesos/validacion.php",
//            data: "comprobante=" + $("#idConciliacion").val() + "&tabla=" + "conciliacion_bancaria" + "&id_tabla=" + "id_conciliacion_bancaria" + "&tipo=" + 1,
//            success: function (data) {
//                var val = data;
//                if (val != "") {
//                    window.open("../../reportes/conciliacion_bancaria.php?hoja=A4&id=" + $("#idConciliacion").val(), '_blank');
//                } else {
//                    alertify.alert("Conciliación no creada!!");
//                }
//            }
//        });
//    });
    $("#btnNuevo").on("click", function () {
        location.reload();
    });

    $("#clave_permiso").dialog(dialogo3);
    $("#seguro").dialog(dialogo4);
    $("#cuentas").dialog(dialogo_cuenta);

    $("#fecha_creacion").datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');

    $("#val_deposito").on("keypress", enter);
    $("#val_cheques").on("keypress", enter2);
    $("#pos_otros").on("keypress", enter3);
    $("#neg_otros").on("keypress", enter4);
    $("#val_acreditado").on("keypress", enter5);
    $("#val_debitados").on("keypress", enter6);

    ////////////cambio evento/////////////
    $("#iva").change(function () {
        if ($("#iva").val() == "Si") {
            $("#incluye").val("Si");
            $("#incluye").attr("readOnly", false);
        } else {
            if ($("#iva").val() == "No") {
                $("#incluye").val("No");
                $("#incluye").attr("readOnly", true);
            }
        }
    });
    /////////////////////////////////////


    $(window).bind('resize', function () {
        jQuery("#list2").setGridWidth($('#pager2').width());
    }).trigger('resize');
    jQuery("#list2").jqGrid({
        url: 'xmlCuentas_Bancos.php',
        datatype: 'xml',
        colNames: ['Id Cuenta', 'Número Cuenta', 'Banco', 'Código Plan'],
        colModel: [
            {name: 'id_cuenta_banco', index: 'id_cuenta_banco', editable: true, align: 'left', width: '100', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'numero_cuenta', index: 'numero_cuenta', editable: true, align: 'center', width: '150', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'banco', index: 'banco', editable: true, align: 'center', width: '250', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'plan_cuentas', index: 'plan_cuentas', editable: true, align: 'center', width: '150', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}}
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        height: 255,
        pager: jQuery('#pager2'),
        sortname: 'id_cuenta_banco',
        shrinkToFit: false,
        sortordezr: 'asc',
        caption: 'Cuentas Bancarias',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
            jQuery('#list2').jqGrid('restoreRow', id);
            var ret = jQuery("#list2").jqGrid('getRowData', id);
            var ccuenta = jQuery("#list2").jqGrid('getCell', id, 1) + "  -  " + jQuery("#list2").jqGrid('getCell', id, 2);
            $("#idCuenta").val(id);
            $("#cuenta").val(ccuenta);
            document.getElementById("cuenta").readOnly = true;
            $("#cuentas").dialog("close");
        }
    }).jqGrid('navGrid', '#pager2',
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
    jQuery("#list2").setGridWidth($('#pager2').width());
    //////////////////////////////////////////////


    ////////////////////////////////NO AUTORIZADO////////////////////////////////
    jQuery("#list7").jqGrid({
        url: 'xmlBuscarConciliacion_generada.php',
        datatype: 'xml',
        colNames: ['ID', 'FECHA', 'COMPROBANTE', 'T.TRANSACCION', 'DEBE', 'HABER', 'ORDEN', 'CONCILIADO', 'valor'],
        colModel: [
            {name: 'id_transacciones', index: 'id_transacciones', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'left', frozen: true, width: 4},
            {name: 'fecha', index: 'fecha', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'left', frozen: true, width: 5},
            {name: 'comprobante', index: 'comprobante', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'left', frozen: true, width: 2},
            {name: 't_transaccion', index: 't_transaccion', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'left', frozen: true, width: 2},
            {name: 'debe', index: 'debe', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'right', frozen: true, width: 2},
            {name: 'monto', index: 'monto', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'right', frozen: true, width: 2},
            {name: 'orden', index: 'orden', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'left', frozen: true, width: 30},
            {name: 'estado',
                index: 'estado',
                editable: false,
                search: false,
                frozen: true,
                hidden: false,
                align: "center",
                formatter: function (cellvalue, options, rowObject) {
                    console.log(cellvalue);
                    if (cellvalue == 1) {
                        console.log(":1:");
                        return '<div style="background-color: red; color: white">No<div>';
                    } else if (cellvalue == '0') {
                        console.log(":2:");
                        return '<div style="background-color: green; color: white">Si<div>';
                    } else if (cellvalue == '00') {
                        console.log(":3:");
                        return '<div style="background-color: transparent; color: white">SIN<div>';
                    }

                },
                width: 3


            },
            {name: 'estado_val',
                index: 'estado_val',
                editable: false,
                search: false,
                frozen: true,
                hidden: true,
                align: "center",

                width: 3


            },
        ],

        rowNum: 1000,
        width: 1270,
        height: 550,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager7'),
        sortname: 'id_transacciones',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'clientArray',
        shrinkToFit: true,
        loadComplete: function (data) {
            ///////////////////////////////////////////////////
            var debe = 0;
            var haber = 0;
            var total_debe = 0;
            var total_haber = 0;
            var id_transaccion = 0;
            var grid = $("#list7");

            var fil = jQuery("#list7").jqGrid("getRowData");
            console.log(fil);
            for (var t = 0; t < fil.length; t++) {
                var dd = fil[t];
                id_transaccion = dd['id_transacciones'];
                if (dd['debe'] == '-') {
                    dd['debe'] = '0';
                }
                debe = dd['debe'];
                total_debe = parseFloat(total_debe) + parseFloat(debe);
                if (dd['monto'] == '-') {
                    console.log("monto" + dd['monto']);
                    dd['monto'] = '0';
                }
                haber = dd['monto'];
                total_haber = parseFloat(total_haber) + parseFloat(haber);





            }
            console.log("si1");
            marcarId();
            $("#debe").val(total_debe.toFixed(2));
            $("#haber").val(total_haber.toFixed(2));
        },
        ondblClickRow: function (rowid) {
            var id = jQuery("#list7").jqGrid('getGridParam', 'selrow');
            jQuery('#list7').jqGrid('restoreRow', id);

            //  $("#buscar_no_autorizados").dialog("close");

        },
        multiselect: true,
        caption: "Datos Retornados"
    }).jqGrid('navGrid', '#pager7', {
        add: false,
        edit: false,
        del: false,
        refresh: true,
        search: false,
        view: false
    }, {
        recreateForm: true,
        closeAfterEdit: true,
        checkOnUpdate: true,
        reloadAfterSubmit: true,
        closeOnEscape: true
    }, {
        reloadAfterSubmit: true,
        closeAfterAdd: true,
        checkOnUpdate: true,
        closeOnEscape: true,
        bottominfo: "Todos los campos son obligatorios"
    }, {
        width: 300,
        closeOnEscape: true
    }, {
        closeOnEscape: true,
        multipleSearch: false,
        overlay: false
    }, {}, {
        closeOnEscape: true
    });
    //     jQuery("#list7").jqGrid('navGrid','#pager7',{edit:false,add:false,del:false,refresh:false,searchtext:"Find"});
    //      jQuery("#list7").jqGrid('filterToolbar',{searchOperators : true});
    /////////////////	


    //Search Toolbar 
    //    jQuery("#list7").filterToolbar({stringResult: true, searchOperators: true, enableClear: true, searchOnEnter: true,
    //        defaultSearch: "eq"});

    //    jQuery("#list7").jqGrid('navButtonAdd', '#pager7', {caption: "Reeviar",
    //        onClickButton: function () {
    //            var id = jQuery("#list7").jqGrid('getGridParam', 'selrow');
    //            jQuery('#list7').jqGrid('restoreRow', id);
    //            if (id) {
    //                var ret = jQuery("#list7").jqGrid('getRowData', id);
    //            }
    //
    //        }
    //
    //    });


/////////////////////////////////////
    jQuery("#list_deposito").jqGrid({
        datatype: "local",
        colNames: ['', 'Descripción', 'Valor', ''],
        colModel: [
            {name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: {keys: false, delbutton: true, editbutton: false}},
            {name: 'descripcion', index: 'descripcion', editable: true, align: 'left', width: '800', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'valor', index: 'valor', editable: true, align: 'center', width: '150', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'valorx', index: 'valorx', editable: true, align: 'center', width: '150', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}}
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        sortable: true,
        height: 100,
        pager: jQuery('#pager'),
        sortname: 'descripcion',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'depositoArray',
        shrinkToFit: true
    });
    jQuery("#list_deposito").setGridWidth($('#pager_dep').width());

    /////////////////////////////////
    jQuery("#list_cheques").jqGrid({
        datatype: "local",
        colNames: ['', 'Descripción', 'Valor', ''],
        colModel: [
            {name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: {keys: false, delbutton: true, editbutton: false}},
            {name: 'descripcion', index: 'descripcion', editable: true, align: 'left', width: '800', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'valor', index: 'valor', editable: true, align: 'center', width: '150', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'valorx', index: 'valorx', editable: true, align: 'center', width: '150', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}}
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        sortable: true,
        height: 100,
        pager: jQuery('#pager'),
        sortname: 'descripcion',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'depositoArray',
        shrinkToFit: true
    });
    jQuery("#list_cheques").setGridWidth($('#pager').width());

    /////////////////////////////////
    jQuery("#list_otros").jqGrid({
        datatype: "local",
        colNames: ['', 'Descripción', 'Valor', ''],
        colModel: [
            {name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: {keys: false, delbutton: true, editbutton: false}},
            {name: 'descripcion', index: 'descripcion', editable: true, align: 'left', width: '800', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'valor', index: 'valor', editable: true, align: 'center', width: '150', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'valorx', index: 'valorx', editable: true, align: 'center', width: '150', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}}
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        sortable: true,
        height: 100,
        pager: jQuery('#pager'),
        sortname: 'descripcion',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'depositoArray',
        shrinkToFit: true
    });
    jQuery("#list_otros").setGridWidth($('#pager').width());

    /////////////////////////////////
    jQuery("#list_acreditados").jqGrid({
        datatype: "local",
        colNames: ['', 'Descripción', 'Valor', ''],
        colModel: [
            {name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: {keys: false, delbutton: true, editbutton: false}},
            {name: 'descripcion', index: 'descripcion', editable: true, align: 'left', width: '800', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'valor', index: 'valor', editable: true, align: 'center', width: '150', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'valorx', index: 'valorx', editable: true, align: 'center', width: '150', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}}
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        sortable: true,
        height: 100,
        pager: jQuery('#pager'),
        sortname: 'descripcion',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'depositoArray',
        shrinkToFit: true
    });
    jQuery("#list_acreditados").setGridWidth($('#pager').width());

    /////////////////////////////////
    jQuery("#list_debitados").jqGrid({
        datatype: "local",
        colNames: ['', 'Descripción', 'Valor', ''],
        colModel: [
            {name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: {keys: false, delbutton: true, editbutton: false}},
            {name: 'descripcion', index: 'descripcion', editable: true, align: 'left', width: '800', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'valor', index: 'valor', editable: true, align: 'center', width: '150', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'valorx', index: 'valorx', editable: true, align: 'center', width: '150', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}}
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        sortable: true,
        height: 100,
        pager: jQuery('#pager'),
        sortname: 'descripcion',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'depositoArray',
        shrinkToFit: true
    });
    jQuery("#list_debitados").setGridWidth($('#pager').width());

    /*jQuery(window).bind('resize', function () {
     jQuery("#list_deposito").setGridWidth(jQuery('#grid_deposito').width(), false);
     }).trigger('resize');*/

    // buscador facturas compra 
    jQuery("#list3").jqGrid({
        url: 'xmlBuscarConciliacionBancaria.php',
        datatype: 'xml',
        colNames: ['ID', 'ID CUENTA', 'CUENTA', 'BANCO', 'MES', 'AÑO', 'ESTADO CUENTA', 'LIBRO BANCOS'],
        colModel: [
            {name: 'id_conciliacion', index: 'id_conciliacion', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 50},
            {name: 'id_cuenta_banco', index: 'id_cuenta_banco', editable: false, search: true, hidden: true, editrules: {edithidden: false}, align: 'center', frozen: true, width: 120},
            {name: 'cuenta', index: 'cuenta', editable: true, search: true, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 200},
            {name: 'banco', index: 'banco', editable: true, search: true, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 200},
            {name: 'mes', index: 'mes', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 100},
            {name: 'anio', index: 'anio', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 100},
            {name: 'estado_cuenta', index: 'estado_cuenta', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 135},
            {name: 'libro_bancos', index: 'libro_bancos', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 135}
        ],
        rowNum: 30,
        width: 750,
        height: 220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager3'),
        sortname: 'id_conciliacion',
        shrinkToFit: true,
        sortorder: 'asc',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list3").jqGrid('getGridParam', 'selrow');
            jQuery('#list3').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list3").jqGrid('getRowData', id);
                var valor = ret.id_conciliacion;

//                $("#idConciliacion").val(valor);
                $("#comprobante").val(valor);
                // llamar datos Factura Compra
                $("#btnGuardar").attr("disabled", true);
                $("#list_deposito").jqGrid("clearGridData", true);
                $("#list_cheques").jqGrid("clearGridData", true);
                $("#list_otros").jqGrid("clearGridData", true);
                $("#list_acreditados").jqGrid("clearGridData", true);
                $("#list_debitados").jqGrid("clearGridData", true);
                $("#list_deposito").jqGrid("clearGridData", true);
                $("#saldo_estado").val("0.000");
                $("#saldo_libro").val("0.000");
                $("#saldo_estado_fin").val("0.000");
                $("#saldo_libro_fin").val("0.000");
                $("#estado h3").remove();
                var descripciones = "";
                var valores = "";
                var x = 0;
                $.getJSON('retornar_conciliacion_bancaria.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 6) {
                            $("#id_plan").val(data[i]);
                            $("#codigo_plan").val(data[i + 1]);
                            $("#descripcion").val(data[i + 2]);

                            $("#fecha_inicio").val(data[i + 3]);
                            $("#fecha_fin").val(data[i + 4]);

                            if (data[i + 5 ] == "Pasivo") {
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
                $.getJSON('retornar_conciliacion_bancaria_grid.php?com=' + valor, function (data) {
                    $("#list7").jqGrid("clearGridData", true);
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 8) {
                            var datarow = {
                                id_transacciones: data[i],
                                fecha: data[i + 1],
                                comprobante: data[i + 2],
                                t_transaccion: data[i + 3],
                                debe: data[i + 4],
                                monto: data[i + 5],
                                orden: data[i + 6],
                                estado: data[i + 7],
                            };


                            var su = jQuery("#list7").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });
                var total_nominat = 0;
                var total_total_nominat = 0;
                var fil = jQuery("#list7").jqGrid("getRowData");
                for (var t = 0; t < fil.length; t++) {
                    var dd = fil[t];

                    total_nominat = dd['debe'];
                    total_total_nominat = parseFloat(total_total_nominat) + parseFloat(total_nominat);
                }

                $("#debe").val(total_total_nominat.toFixed(4));


                $("#buscar_conciliacion").dialog("close");
            } else {
                alertify.alert("Seleccione");
            }

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
}
