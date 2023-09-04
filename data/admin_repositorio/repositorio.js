$(document).on("ready", inicio);

function evento(e) {
    e.preventDefault();
}

function scrollToBottom() {
    $('html, body').animate({
        scrollTop: $(document).height()
    }, 'slow');
}
var sumC = 0;
var t;

function scrollToTop() {
    $('html, body').animate({
        scrollTop: 0
    }, 'slow');
}

function toFixedDown(value, digits) {
    if (isNaN(value))
        return 0;
    var n = value - Math.pow(10, -digits) / 2;
    n += n / Math.pow(2, 53);
    if (n < 0)
        n = 0.000;
    return n.toFixed(digits);
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

/*
 * 
 * @returns {undefined}
 * FUNCION BUSCAR HTML
 */
function myFunction() {
    // Declare variables 
    var input, filter, table, tr, td, i;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("list11");
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];
        if (td) {
            if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

function numeros(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla == 8)
        return true;
    patron = /\d/;
    te = String.fromCharCode(tecla);
    return patron.test(te);
}

function punto(e) {
    var key;
    if (window.event) {
        key = e.keyCode;
    } else if (e.which) {
        key = e.which;
    }

    if (key < 48 || key > 57) {
        if (key == 46 || key == 8) {
            return true;
        } else {
            return false;
        }
    }
    return true;
}

var combo_1 = '';
var combo_2 = '';
var calculoIVA = 0;

function reenviar(id) {
    $.ajax({
        type: "POST",
        url: "../../data/factura_venta/guardar_factura_venta.php",
        data: {
            reenviarcorreo: 'reenviarcorreo',
            id: id
        },
        dataType: "json",
        success: function (data) {
            if (data.estado == 1) {
                alertify.alert("Enviado");
                window.onload = timedRefresh(10000);

            } else {
                alertify.error("Error al enviar");
                window.onload = timedRefresh(10000);
            }
        }
    });
}

function reenviarXml(id) {
    $.ajax({
        type: "POST",
        url: "../../data/factura_venta/guardar_factura_venta.php",
        data: {
            reenviarxml: 'reenviarxml',
            id: id
        },
        dataType: "json",
        success: function (data) {

            if (data.estado == 2) {
                alertify.alert("AUTORIZADO: ");
                window.onload = timedRefresh(10000);

            } else {
                alertify.error("NO AUTORIZADO: ");
                window.onload = timedRefresh(10000);
            }
        }
    });
}
function enviar_xmls() {


    var id = jQuery("#list7").jqGrid('getGridParam', 'selarrrow');
    for (var i = 0; i < id.length; i++) {
        var id = jQuery("#list7").getGridParam();
        for (var i = 0; i < id.length; i++) {
            var id_factura_venta = id[i];

            //          enviarXml('75');

        }

    }

}
function enviarXml(id) {

    $.ajax({
        type: "POST",
        url: "../../data/factura_venta/guardar_factura_venta.php",
        data: {
            enviarxml: 'enviarxml',
            id: id
        },
        dataType: "json",
        success: function (data) {

            if (data.estado == 2) {
                alertify.alert("AUTORIZADO");
                window.onload = timedRefresh(10000);
            } else {
                alertify.alert("NO AUTORIZADO");
                window.onload = timedRefresh(10000);
            }
        }
    });

}


/*
 * 
 * @return NO AUTORIZADO
 */
function nueva_busqueda() {
    $("#id_cliente").val("");
    $("#ruc_ci").val("");
    $("#nombre_cliente").val("");
    $("#fecha_emision").val("");
    $("#fecha_caducidad").val("");
    $("#btnBuscar").click();
}
function nueva_busqueda_auto() {
    $("#nombre_cliente_auto").val("");
    $("#ruc_ci_auto").val("");
    $("#id_cliente_auto").val("");
    $("#serie1_auto").val("");
    $("#serie2_auto").val("");
    $("#fecha_emision_a").val("");
    $("#fecha_caducidad_a").val("");

    $("#list8").jqGrid("clearGridData", true);
}

function abrir_pdf_unido() {

    var grid = $("#list8");
    var rowKey = grid.getGridParam("selrow");
    if (!rowKey)
        alertify.alert("NO HA SELECCIONADO NINGUNA FILA");
    else {
        var selectedIDs = grid.getGridParam("selarrrow");
        var myWindow = window.open("../../data/factura_venta/generarPDF_unido.php?hoja=A4&id=" + selectedIDs, '_blank');
    }

}


function abrir_pdf() {

    var grid = $("#list8");
    var rowKey = grid.getGridParam("selrow");
    if (!rowKey)
        alertify.alert("NO HA SELECCIONADO NINGUNA FILA");
    else {
        var selectedIDs = grid.getGridParam("selarrrow");
        var result = "";
        for (var i = 0; i < selectedIDs.length; i++) {

            result = selectedIDs[i];
            var myWindow = window.open("../../data/factura_venta/generarPDF_UNO_UNO.php?hoja=A4&id=" + result, '_blank');
        }
    }
}


function nueva_busqueda_auto_envi() {
    $("#nombre_cliente_ae").val("");
    $("#ruc_ci_ae").val("");
    $("#id_cliente_ae").val("");
    $("#serie1_ae").val("");
    $("#serie2_ae").val("");
    $("#fecha_emision_ae").val("");
    $("#fecha_caducidad_ae").val("");
    $("#list9").jqGrid("clearGridData", true);
}
function nueva_busqueda_error() {
    $("#ruc_ci_error").val("");
    $("#nombre_cliente_error").val("");
    $("#serie1_error").val("");
    $("#serie2_error").val("");
    $("#fecha_emision_error").val("");
    $("#fecha_caducidad_error").val("");
    $("#id_cliente_error").val("");

    $("#list10").jqGrid("clearGridData", true);
}
function factura_no_autorizada() {
    var id = $("#id_cliente").val();
    var s1 = $("#serie1").val();
    var s2 = $("#serie2").val();
    var f1 = $("#fecha_emision").val();
    var f2 = $("#fecha_caducidad").val();

    ////////todo vacio
    if (id == "" && s1 == "" && s2 == "" && f1 == "" && f2 == "") {

        $("#list7").jqGrid('setGridParam', {
            url: 'xmlBuscarRetencionNoAutorizada.php?id=' + id + "&s1=" + s1 + "&s2=" + s2 + "&f1=" + f1 + "&f2=" + f2,
            datatype: 'xml'
        }).trigger('reloadGrid');
    }

    ///Busqueda solo cliente
    if (id != "") {

        $("#list7").jqGrid('setGridParam', {
            url: 'xmlBuscarRetencionNoAutorizada.php?id=' + id + "&s1=" + s1 + "&s2=" + s2 + "&f1=" + f1 + "&f2=" + f2,
            datatype: 'xml'
        }).trigger('reloadGrid');
    }
    ///Busqueda  por fecha y cliente
    if (f1 != "" && f2 != "" && id != "") {

        $("#list7").jqGrid('setGridParam', {
            url: 'xmlBuscarRetencionNoAutorizada.php?id=' + id + "&f1=" + f1 + "&f2=" + f2 + "&s1=" + s1 + "&s2=" + s2,
            datatype: 'xml'
        }).trigger('reloadGrid');
    }
    ///Busqueda  solo por fecha
    if (f1 != "" && f2 != "" && id == "") {

        $("#list7").jqGrid('setGridParam', {
            url: 'xmlBuscarRetencionNoAutorizada.php?id=' + id + "&f1=" + f1 + "&f2=" + f2 + "&s1=" + s1 + "&s2=" + s2,
            datatype: 'xml'
        }).trigger('reloadGrid');
    }

    ///Busqueda solo por serie
    if (s1 != "" && s2 != "") {

        $("#list7").jqGrid('setGridParam', {
            url: 'xmlBuscarRetencionNoAutorizada.php?id=' + id + "&s1=" + s1 + "&s2=" + s2 + "&f1=" + f1 + "&f2=" + f2,
            datatype: 'xml'
        }).trigger('reloadGrid');
    }

}
/*
 * 
 * @returns factura_autorizada
 */
function factura_autorizada() {
    var id = $("#id_cliente_auto").val();
    var s1 = $("#serie1_auto").val();
    var s2 = $("#serie2_auto").val();
    var f1 = $("#fecha_emision_a").val();
    var f2 = $("#fecha_caducidad_a").val();


    ////////todo vacio
    if (id == "" && s1 == "" && s2 == "" && f1 == "" && f2 == "") {

        $("#list8").jqGrid('setGridParam', {
            url: 'xmlBuscarRetencionAutorizada.php?id=' + id + "&s1=" + s1 + "&s2=" + s2 + "&f1=" + f1 + "&f2=" + f2,
            datatype: 'xml'
        }).trigger('reloadGrid');
    }

    ///Busqueda solo cliente
    if (id != "") {

        $("#list8").jqGrid('setGridParam', {
            url: 'xmlBuscarRetencionAutorizada.php?id=' + id + "&s1=" + s1 + "&s2=" + s2 + "&f1=" + f1 + "&f2=" + f2,
            datatype: 'xml'
        }).trigger('reloadGrid');
    }
    ///Busqueda  por fecha y cliente
    if (f1 != "" && f2 != "" && id != "") {

        $("#list8").jqGrid('setGridParam', {
            url: 'xmlBuscarRetencionAutorizada.php?id=' + id + "&f1=" + f1 + "&f2=" + f2 + "&s1=" + s1 + "&s2=" + s2,
            datatype: 'xml'
        }).trigger('reloadGrid');
    }
    ///Busqueda  solo por fecha
    if (f1 != "" && f2 != "" && id == "") {

        $("#list8").jqGrid('setGridParam', {
            url: 'xmlBuscarRetencionAutorizada.php?id=' + id + "&f1=" + f1 + "&f2=" + f2 + "&s1=" + s1 + "&s2=" + s2,
            datatype: 'xml'
        }).trigger('reloadGrid');
    }

    ///Busqueda solo por serie
    if (s1 != "" && s2 != "") {

        $("#list8").jqGrid('setGridParam', {
            url: 'xmlBuscarRetencionAutorizada.php?id=' + id + "&s1=" + s1 + "&s2=" + s2 + "&f1=" + f1 + "&f2=" + f2,
            datatype: 'xml'
        }).trigger('reloadGrid');
    }
}
//fin
/*
 * 
 * @returns factura_autorizada_enviada
 */
function factura_autorizada_enviada() {
    var id = $("#id_cliente_ae").val();
    var s1 = $("#serie1_ae").val();
    var s2 = $("#serie2_ae").val();
    var f1 = $("#fecha_emision_ae").val();
    var f2 = $("#fecha_caducidad_ae").val();



    ////////todo vacio
    if (id == "" && s1 == "" && s2 == "" && f1 == "" && f2 == "") {

        $("#list9").jqGrid('setGridParam', {
            url: 'xmlBuscarRetencionAutorizadaEnv.php?id=' + id + "&s1=" + s1 + "&s2=" + s2 + "&f1=" + f1 + "&f2=" + f2,
            datatype: 'xml'
        }).trigger('reloadGrid');
    }

    ///Busqueda solo cliente
    if (id != "") {

        $("#list9").jqGrid('setGridParam', {
            url: 'xmlBuscarRetencionAutorizadaEnv.php?id=' + id + "&s1=" + s1 + "&s2=" + s2 + "&f1=" + f1 + "&f2=" + f2,
            datatype: 'xml'
        }).trigger('reloadGrid');
    }
    ///Busqueda  por fecha y cliente
    if (f1 != "" && f2 != "" && id != "") {

        $("#list9").jqGrid('setGridParam', {
            url: 'xmlBuscarRetencionAutorizadaEnv.php?id=' + id + "&f1=" + f1 + "&f2=" + f2 + "&s1=" + s1 + "&s2=" + s2,
            datatype: 'xml'
        }).trigger('reloadGrid');
    }
    ///Busqueda  solo por fecha
    if (f1 != "" && f2 != "" && id == "") {

        $("#list9").jqGrid('setGridParam', {
            url: 'xmlBuscarRetencionAutorizadaEnv.php?id=' + id + "&f1=" + f1 + "&f2=" + f2 + "&s1=" + s1 + "&s2=" + s2,
            datatype: 'xml'
        }).trigger('reloadGrid');
    }

    ///Busqueda solo por serie
    if (s1 != "" && s2 != "") {

        $("#list9").jqGrid('setGridParam', {
            url: 'xmlBuscarRetencionAutorizadaEnv.php?id=' + id + "&s1=" + s1 + "&s2=" + s2 + "&f1=" + f1 + "&f2=" + f2,
            datatype: 'xml'
        }).trigger('reloadGrid');
    }
}
/*
 * 
 * @returns factura_errorWeb_retorno
 */
function factura_errorWeb_retorno() {
    var id = $("#id_cliente_error").val();
    var s1 = $("#serie1_error").val();
    var s2 = $("#serie2_error").val();
    var f1 = $("#fecha_emision_error").val();
    var f2 = $("#fecha_caducidad_error").val();



    ////////todo vacio
    if (id == "" && s1 == "" && s2 == "" && f1 == "" && f2 == "") {

        $("#list10").jqGrid('setGridParam', {
            url: 'xmlBuscarRetencionErrorWeb.php?id=' + id + "&s1=" + s1 + "&s2=" + s2 + "&f1=" + f1 + "&f2=" + f2,
            datatype: 'xml'
        }).trigger('reloadGrid');
    }

    ///Busqueda solo cliente
    if (id != "") {

        $("#list10").jqGrid('setGridParam', {
            url: 'xmlBuscarRetencionErrorWeb.php?id=' + id + "&s1=" + s1 + "&s2=" + s2 + "&f1=" + f1 + "&f2=" + f2,
            datatype: 'xml'
        }).trigger('reloadGrid');
    }
    ///Busqueda  por fecha y cliente
    if (f1 != "" && f2 != "" && id != "") {

        $("#list10").jqGrid('setGridParam', {
            url: 'xmlBuscarRetencionErrorWeb.php?id=' + id + "&f1=" + f1 + "&f2=" + f2 + "&s1=" + s1 + "&s2=" + s2,
            datatype: 'xml'
        }).trigger('reloadGrid');
    }
    ///Busqueda  solo por fecha
    if (f1 != "" && f2 != "" && id == "") {

        $("#list10").jqGrid('setGridParam', {
            url: 'xmlBuscarRetencionErrorWeb.php?id=' + id + "&f1=" + f1 + "&f2=" + f2 + "&s1=" + s1 + "&s2=" + s2,
            datatype: 'xml'
        }).trigger('reloadGrid');
    }

    ///Busqueda solo por serie
    if (s1 != "" && s2 != "") {

        $("#list10").jqGrid('setGridParam', {
            url: 'xmlBuscarRetencionErrorWeb.php?id=' + id + "&s1=" + s1 + "&s2=" + s2 + "&f1=" + f1 + "&f2=" + f2,
            datatype: 'xml'
        }).trigger('reloadGrid');
    }
}

/*
 * 
 * @return AUTORIZADO
 */

function inicio() {
    //     $("#fecha_emision").datepicker({
    //        dateFormat: 'yy-mm-dd'
    //    }).datepicker('setDate', 'today');


    //         $("#fecha_caducidad").datepicker({
    //        dateFormat: 'yy-mm-dd'
    //    }).datepicker('setDate', 'today');
    //    

    //    $("#fecha_emision_a").datepicker({
    //        dateFormat: 'yy-mm-dd'
    //    }).datepicker('setDate', 'today');
    //
    //
    //    $("#fecha_caducidad_a").datepicker({
    //        dateFormat: 'yy-mm-dd'
    //    }).datepicker('setDate', 'today');


    $('#fecha_actual').datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');
    $('#fecha_actual_auto').datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');
    $('#fecha_actual_ae').datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');
    $('#fecha_actual_error').datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');
    $('#fecha_registro').datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');

    $("#cancelacion").datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');


    $("[data-mask]").inputmask();
    alertify.set({
        delay: 1000
    });

    $("[data-mask]").inputmask();
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


    ///////////////////////////////////////

    $("#btncargar").click(function (e) {
        e.preventDefault();
    });
    $("#btnAgregar").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardarSeries").click(function (e) {
        e.preventDefault();
    });
    $("#btnCancelarSeries").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardar").click(function (e) {
        e.preventDefault();
    });

    $("#btnModificar").click(function (e) {
        e.preventDefault();
    });
    $("#btnCancelar").click(function (e) {
        e.preventDefault();
    });
    $("#btnNuevo").click(function (e) {
        e.preventDefault();
    });
    $("#btnContabilizar").click(function (e) {
        e.preventDefault();
    });
    $("#btnImprimir").click(function () {
        $.ajax({
            type: "POST",
            url: "../../procesos/validacion.php",
            data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "factura_compra" + "&id_tabla=" + "id_factura_compra" + "&tipo=" + 1,
            success: function (data) {
                var val = data;
                if (val != "") {
                    window.open("../../reportes/factura_compra.php?hoja=A4&id=" + $("#comprobante").val(), '_blank');
                } else {
                    alertify.alert("Factura no creada!!");
                }
            }
        });
    });
    $("#btnEliminar").click(function (e) {
        e.preventDefault();
    });
    $("#btnAceptar").click(function (e) {
        e.preventDefault();
    });
    $("#btnAnadirForma").click(function (e) {
        e.preventDefault();
    });
    $("#btnSalir").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardarRetenciones").click(function (e) {
        e.preventDefault();
    });
    $("#btnCancelarRetenciones").click(function (e) {
        e.preventDefault();
    });
    $("#btnImprimirRetenciones").click(function () {
        window.open("../../reportes/retenciones.php?hoja=A4&id=" + $("#comprobante").val(), '_blank');
    });

    $("#btnBuscar").click(function (e) {
        e.preventDefault();
    });


    $("#btnEnviar_xml").click(function (e) {
        e.preventDefault();
    });

    $("#btnBuscar_actua").click(function (e) {
        e.preventDefault();
    });

    $("#btnBuscarAutorizado").click(function (e) {
        e.preventDefault();
    });
    $("#btnBuscarAutoEnviado").click(function (e) {
        e.preventDefault();
    });
    $("#btnBuscarErrorWeb").click(function (e) {
        e.preventDefault();
    });

    $("#btnReenviar").click(function (e) {
        e.preventDefault();
    });

    $("#btnCancelarReenvio").click(function (e) {
        e.preventDefault();
    });


    $("#btnBuscar").on("click", factura_no_autorizada);
    $("#btnEnviar_xml").on("click", enviar_xmls);

    $("#btnBuscar_actua").on("click", nueva_busqueda);
    $("#btnNuevaBusqueda").on("click", nueva_busqueda_auto);
    $("#btnNuevaBusqueda_pdf").on("click", abrir_pdf);
    $("#btnNuevaBusqueda_pdf_unido").on("click", abrir_pdf_unido);


    $("#btnNuevaBusqueda_autori_envia").on("click", nueva_busqueda_auto_envi);
    $("#btnNuevaBusqueda_error").on("click", nueva_busqueda_error);
    $("#btnBuscarAutorizado").on("click", factura_autorizada);
    $("#btnBuscarAutoEnviado").on("click", factura_autorizada_enviada);
    $("#btnBuscarErrorWeb").on("click", factura_errorWeb_retorno);



    $("#btnCancelarRetenciones").on("click", function (e) {
        location.reload();
    });

    $("#cantidad").validCampoFranz("0123456789");
    $("#autorizacion").validCampoFranz("0123456789");
    $("#descuento").validCampoFranz("0123456789");


    // buscar clientes identificacion
    $("#ruc_ci").autocomplete({

        source: "buscar_cliente.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#ruc_ci").val(ui.item.value);
            $("#id_cliente").val(ui.item.id_cliente);
            $("#nombre_cliente").val(ui.item.nombre_cliente);
            $("#direccion_cliente").val(ui.item.direccion_cliente);
            $("#telefono_cliente").val(ui.item.telefono_cliente);
            $("#correo").val(ui.item.correo);
            return false;
        },
        select: function (event, ui) {
            $("#ruc_ci").val(ui.item.value);
            $("#id_cliente").val(ui.item.id_cliente);
            $("#nombre_cliente").val(ui.item.nombre_cliente);
            $("#direccion_cliente").val(ui.item.direccion_cliente);
            $("#telefono_cliente").val(ui.item.telefono_cliente);
            $("#correo").val(ui.item.correo);
            $("#direccion_cliente").attr("disabled", "disabled");
            //        $("#telefono_cliente").attr("disabled", "disabled");
            //        $("#correo").attr("disabled", "disabled");
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };



    // fin

    // buscar NUM SERIE1 NO AUTORIZADOS
    $("#serie1").autocomplete({
        source: "buscar_serie.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#serie1").val(ui.item.value);
            return false;
        },
        select: function (event, ui) {
            $("#serie1").val(ui.item.value);
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };
    // fin

    // buscar NUM SERIE1 NO AUTORIZADOS
    $("#serie2").autocomplete({
        source: "buscar_serie.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#serie2").val(ui.item.value);
            return false;
        },
        select: function (event, ui) {
            $("#serie2").val(ui.item.value);
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };
    // fin



    // buscar NUM SERIE1  AUTORIZADOS
    $("#serie1_auto").autocomplete({
        source: "buscar_serie.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#serie1_auto").val(ui.item.value);
            return false;
        },
        select: function (event, ui) {
            $("#serie1_auto").val(ui.item.value);
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };
    // fin





    // buscar NUM SERIE1  AUTORIZADOS
    $("#serie2_auto").autocomplete({
        source: "buscar_serie.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#serie2_auto").val(ui.item.value);
            return false;
        },
        select: function (event, ui) {
            $("#serie2_auto").val(ui.item.value);
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };
    // fin

    // buscar NUM SERIE1  AUTORIZADOS
    $("#serie1_ae").autocomplete({
        source: "buscar_serie.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#serie1_ae").val(ui.item.value);
            return false;
        },
        select: function (event, ui) {
            $("#serie1_ae").val(ui.item.value);
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };
    // fin

    // buscar NUM SERIE1  AUTORIZADOS
    $("#serie2_ae").autocomplete({
        source: "buscar_serie.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#serie2_ae").val(ui.item.value);
            return false;
        },
        select: function (event, ui) {
            $("#serie2_ae").val(ui.item.value);
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };
    // fin

    // buscar NUM SERIE1  AUTORIZADOS
    $("#serie1_error").autocomplete({
        source: "buscar_serie.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#serie1_error").val(ui.item.value);
            return false;
        },
        select: function (event, ui) {
            $("#serie1_error").val(ui.item.value);
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };
    // fin

    // buscar NUM SERIE1  AUTORIZADOS
    $("#serie2_error").autocomplete({
        source: "buscar_serie.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#serie2_error").val(ui.item.value);
            return false;
        },
        select: function (event, ui) {
            $("#serie2_error").val(ui.item.value);
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };
    // fin

    // buscar clientes identificacion
    $("#ruc_ci_auto").autocomplete({

        source: "buscar_cliente.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#ruc_ci_auto").val(ui.item.value);
            $("#id_cliente_auto").val(ui.item.id_cliente);
            $("#nombre_cliente_auto").val(ui.item.nombre_cliente);

            return false;
        },
        select: function (event, ui) {
            $("#ruc_ci_auto").val(ui.item.value);
            $("#id_cliente_auto").val(ui.item.id_cliente);
            $("#nombre_cliente_auto").val(ui.item.nombre_cliente);

            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };
    // fin

    /*
     *  // buscar clientes nombres
     */
    $("#nombre_cliente").autocomplete({
        source: "buscar_cliente_nombre.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#nombre_cliente").val(ui.item.value);
            $("#id_cliente").val(ui.item.id_cliente);
            $("#ruc_ci").val(ui.item.ruc_ci);
            $("#direccion_cliente").val(ui.item.direccion_cliente);
            $("#telefono_cliente").val(ui.item.telefono_cliente);
            $("#correo").val(ui.item.correo);
            return false;
        },
        select: function (event, ui) {
            $("#nombre_cliente").val(ui.item.value);
            $("#id_cliente").val(ui.item.id_cliente);
            $("#ruc_ci").val(ui.item.ruc_ci);
            $("#direccion_cliente").val(ui.item.direccion_cliente);
            $("#telefono_cliente").val(ui.item.telefono_cliente);
            $("#correo").val(ui.item.correo);
            $("#direccion_cliente").attr("disabled", "disabled");

            return false;
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };

    // fin
    /*
     *  // buscar clientes nombres autorizado
     */
    $("#nombre_cliente_auto").autocomplete({
        source: "buscar_cliente_nombre.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#nombre_cliente_auto").val(ui.item.value);
            $("#id_cliente_auto").val(ui.item.id_cliente);
            $("#ruc_ci_auto").val(ui.item.ruc_ci);

            return false;
        },
        select: function (event, ui) {
            $("#nombre_cliente_auto").val(ui.item.value);
            $("#id_cliente_auto").val(ui.item.id_cliente);
            $("#ruc_ci_auto").val(ui.item.ruc_ci);
            return false;
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };


    /*
     *  // buscar clientes nombres autorizado enviado
     */
    $("#nombre_cliente_ae").autocomplete({
        source: "buscar_cliente_nombre.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#nombre_cliente_ae").val(ui.item.value);
            $("#id_cliente_ae").val(ui.item.id_cliente);
            $("#ruc_ci_ae").val(ui.item.ruc_ci);

            return false;
        },
        select: function (event, ui) {
            $("#nombre_cliente_ae").val(ui.item.value);
            $("#id_cliente_ae").val(ui.item.id_cliente);
            $("#ruc_ci_ae").val(ui.item.ruc_ci);
            return false;
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };
    // fin



    /*
     *  // buscar clientes nombres error
     */
    $("#nombre_cliente_error").autocomplete({
        source: "buscar_cliente_nombre.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#nombre_cliente_error").val(ui.item.value);
            $("#id_cliente_error").val(ui.item.id_cliente);
            $("#ruc_ci_error").val(ui.item.ruc_ci);
            return false;
        },
        select: function (event, ui) {
            $("#nombre_cliente_error").val(ui.item.value);
            $("#id_cliente_error").val(ui.item.id_cliente);
            $("#ruc_ci_error").val(ui.item.ruc_ci);
            return false;
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };
    // buscar clientes identificacion
    $("#ruc_ci_error").autocomplete({

        source: "buscar_cliente.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#ruc_ci_error").val(ui.item.value);
            $("#id_cliente_error").val(ui.item.id_cliente);
            $("#nombre_cliente_error").val(ui.item.nombre_cliente);

            return false;
        },
        select: function (event, ui) {
            $("#ruc_ci_error").val(ui.item.value);
            $("#id_cliente_error").val(ui.item.id_cliente);
            $("#nombre_cliente_error").val(ui.item.nombre_cliente);

            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };
    // fin
    ////////////////////////////////NO AUTORIZADO////////////////////////////////
    jQuery("#list7").jqGrid({
        url: 'xmlBuscarRetencionNoAutorizada.php',
        datatype: 'xml',
        colNames: ['ID', 'FECHA', 'NRO. FACTURA', 'CLIENTE', 'N° AUTORIZACIÓN', 'VALOR TOTAL', 'ESTADO', 'ENVIO XML', 'CONSULTA COMPROBANTE'],
        colModel: [{
            name: 'id_factura_venta',
            index: 'id_factura_venta',
            editable: false,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 50
        },
        {
            name: 'fecha',
            index: 'fecha',
            editable: false,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 100
        },
        {
            name: 'num_factura',
            index: 'num_factura',
            editable: false,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 100
        },
        {
            name: 'cliente',
            index: 'cliente',
            editable: false,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 100
        },
        {
            name: 'autorizacion',
            index: 'autorizacion',
            editable: false,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 100
        },
        {
            name: 'total',
            index: 'total',
            editable: false,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 50
        },
        {
            name: 'estado',
            index: 'estado',
            editable: false,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 50
        },
        {
            name: 'envio',
            index: 'envio',
            editable: false,
            hidden: false,
            search: false,
            frozen: true,
            editrules: {
                required: true
            },
            align: 'center',
            width: '80px'
        },
        {
            name: 'reenvio',
            index: 'reenvio',
            editable: false,
            hidden: false,
            search: false,
            frozen: true,
            editrules: {
                required: true
            },
            align: 'center',
            width: '80px'
        },
        ],

        rowNum: 30,
        width: 1000,
        height: 220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager7'),
        sortname: 'id_factura_venta',
        sortorder: 'desc',
        viewrecords: true,
        cellEdit: false,
        shrinkToFit: true,
        multiselect: false,
        caption: "Datos Retornados",
        toolbar: [true, "top"],
        gridComplete: function () {
            let ids = jQuery("#list7").jqGrid('getDataIDs');
            for (let i = 0; i < ids.length; i++) {
                let id_factura = ids[i];
                let be = `<a id="enviar_fc_${id_factura}" title='Enviar Xml' ><i class='fa fa-arrow-circle-right' style='cursor:pointer; cursor: hand'> Envio Xml</i></a>`;
                jQuery("#list7").jqGrid('setRowData', ids[i], {
                    envio: be
                });

                let be1 = `<a id="consultar_fc_${id_factura}" title='Consultar Xml' ><i class='fa fa-repeat' style='cursor:pointer; cursor: hand'> Consulta Xml</i></a>`;
                jQuery("#list7").jqGrid('setRowData', ids[i], {
                    reenvio: be1
                });
            }
            ids.forEach(el => {
                $(`#enviar_fc_${el}`).click(function (e) {
                    alertify.set({ delay: 3000 })
                    consultarFactura(el, "enviar")
                        .then(
                            res => {
                                if (res.estado == 2) {
                                    alertify.success("Factura autorizada");
                                    $('#list7').trigger('reloadGrid');
                                } else {
                                    alertify.error("Factura no autorizada");
                                }
                            },
                            err => {
                                alertify.error("Hubo un problema al enviar el XML.");
                            }
                        )
                    e.preventDefault();
                });
                $(`#consultar_fc_${el}`).click(function (e) {
                    alertify.set({ delay: 3000 })
                    consultarFactura(el, "consultar")
                        .then(
                            res => {
                                if (res.estado == 2) {
                                    alertify.success("Factura autorizada");
                                    $('#list7').trigger('reloadGrid');
                                } else {
                                    alertify.error("Factura no autorizada");
                                }
                            },
                            err => {
                                alertify.error("Hubo un problema al consultar el XML.");
                            }
                        )
                    e.preventDefault();
                });
            });

            /* for (var i = 0; i < ids.length; i++) {
                var ids = jQuery("#list7").getDataIDs();
        
                for (var i = 0; i < ids.length; i++) {
                    var id_factura = ids[i];
                    be = "<a  onclick=\"reenviar('" + id_factura + "')\" title='Reenviar Correo' ><i class='fa fa-envelope-o' style='cursor:pointer; cursor: hand'> CORREO</i></a>";
                    jQuery("#list7").jqGrid('setRowData', ids[i], {
                        accion: be
                    });
                }
            } */
        },
        ondblClickRow: function (rowid) {


        },
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
    $("#t_list7")
        .css({ height: "40px", "text-align": "right" })
        .append(`<button id="btn_autorizar_facturas" class="btn btn-primary" type="button"><b>Autorizar Facturas Encontradas</b> <i class="fa fa-play" aria-hidden="true"></i></button>`);

    $("#btn_autorizar_facturas").click(function (e) {
        let id = $("#id_cliente").val();
        /*  var s1 = $("#serie1").val();
         var s2 = $("#serie2").val(); */
        let f1 = $("#fecha_emision").val();
        let f2 = $("#fecha_caducidad").val();
        autorizarFacturas(id, f1, f2);
        e.preventDefault();
    });

    //$("#t_list7").css({ height: "40px" });
    //$("#t_list7").append("<input class='btn btn-primary' type='button' value='Click Me' style='height:20px;font-size:-3'/>");

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




    //////////////////////////AUTORIZADO//////////////////////////////////////////////////
    jQuery("#list8").jqGrid({
        url: 'xmlBuscarRetencionAutorizada.php',
        datatype: 'xml',
        colNames: ['ID', 'FECHA', 'CLIENTE', 'N° AUTORIZACIÓN', 'VALOR TOTAL', 'ESTADO', 'ACCIÓN'],
        colModel: [{
            name: 'id_factura_venta',
            index: 'id_factura_venta',
            editable: false,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 50
        },
        {
            name: 'fecha',
            index: 'fecha',
            editable: false,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 100
        },
        {
            name: 'cliente',
            index: 'cliente',
            editable: true,
            search: true,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 100
        },
        {
            name: 'autorizacion',
            index: 'autorizacion',
            editable: true,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 100
        },
        {
            name: 'total',
            index: 'total',
            editable: true,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 50
        },
        {
            name: 'estado',
            index: 'estado',
            editable: true,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 50
        },
        {
            name: 'accion',
            index: 'accion',
            editable: false,
            hidden: false,
            search: false,
            frozen: true,
            editrules: {
                required: true
            },
            align: 'center',
            width: '80px'
        },
        ],
        rowNum: 30,
        width: 1000,
        height: 220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager8'),
        sortname: 'id_factura_venta',
        sortorder: 'desc',
        caption: 'Lista de Facturas Autorizados',
        viewrecords: true,

        gridComplete: function () {
            var ids = jQuery("#list8").jqGrid('getDataIDs');
            for (var i = 0; i < ids.length; i++) {
                var ids = jQuery("#list8").getDataIDs();

                for (var i = 0; i < ids.length; i++) {
                    var id_factura = ids[i];
                    be = "<a  onclick=\"reenviar('" + id_factura + "')\" title='Reenviar Correo' ><i class='fa fa-envelope-o' style='cursor:pointer; cursor: hand'> CORREO</i></a>";
                    jQuery("#list8").jqGrid('setRowData', ids[i], {
                        accion: be
                    });
                }
            }
            for (var i = 0; i < ids.length; i++) {
                var ids = jQuery("#list8").getDataIDs();

                for (var i = 0; i < ids.length; i++) {
                    var id_factura = ids[i];


                    be = "<a  onclick=\"enviarXml('" + id_factura + "')\" title='Reenviar Xml' ><i class='fa fa-arrow-circle-right' style='cursor:pointer; cursor: hand'> Envio Xml</i></a>";
                    jQuery("#list8").jqGrid('setRowData', ids[i], {
                        envio: be
                    });

                }
            }
            for (var i = 0; i < ids.length; i++) {
                var ids = jQuery("#list8").getDataIDs();
                for (var i = 0; i < ids.length; i++) {
                    var id_factura = ids[i];
                    be = "<a  onclick=\"reenviarXml('" + id_factura + "')\" title='Reenviar Xml' ><i class='fa fa-repeat' style='cursor:pointer; cursor: hand'> Consulta Xml</i></a>";
                    jQuery("#list8").jqGrid('setRowData', ids[i], {
                        reenvio: be
                    });
                }
            }
        },
        ondblClickRow: function (rowid) {
            var id = jQuery("#list8").jqGrid('getGridParam', 'selrow');
            jQuery('#list8').jqGrid('restoreRow', id);
            $("#list8").jqGrid("clearGridData", true);
            //  $("#buscar_no_autorizados").dialog("close");

        },
        multiselect: true,
        caption: "Datos Retornados"
    }).jqGrid('navGrid', '#pager8', {
        add: false,
        edit: false,
        del: false,
        refresh: true,
        search: false,
        // multipleSearch: true,
        view: true
    });

    //    jQuery("#list8").jqGrid('navButtonAdd', '#pager8', {caption: "Reeviar",
    //        onClickButton: function () {
    //            var id = jQuery("#list8").jqGrid('getGridParam', 'selrow');
    //            jQuery('#list8').jqGrid('restoreRow', id);
    //            if (id) {
    //                var ret = jQuery("#list8").jqGrid('getRowData', id);
    //
    //            }
    //        onSelectRow: function() {
    //                jQuery("#list8").jqGrid('editRow',id,true,"","", reloadTable())
    //        }    
    //        }

    //    });


    ////////////////////////////////AUTORIZADO - ENVIADO////////////////////////////////////////////
    jQuery("#list9").jqGrid({
        url: 'xmlBuscarRetencionAutorizadaEnv.php',
        datatype: 'xml',
        colNames: ['ID', 'FECHA', 'CLIENTE', 'N° AUTORIZACIÓN', 'VALOR TOTAL', 'ESTADO', 'ACCIÓN'],
        colModel: [{
            name: 'id_factura_venta',
            index: 'id_factura_venta',
            editable: false,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 50
        },
        {
            name: 'fecha',
            index: 'fecha',
            editable: false,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 100
        },
        {
            name: 'cliente',
            index: 'cliente',
            editable: true,
            search: true,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 100
        },
        {
            name: 'autorizacion',
            index: 'autorizacion',
            editable: true,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 100
        },
        {
            name: 'total',
            index: 'total',
            editable: true,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 50
        },
        {
            name: 'estado',
            index: 'estado',
            editable: true,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 50
        },
        {
            name: 'accion',
            index: 'accion',
            editable: false,
            hidden: false,
            search: false,
            frozen: true,
            editrules: {
                required: true
            },
            align: 'center',
            width: '80px'
        },
        ],
        rowNum: 30,
        width: 900,
        height: 220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager9'),
        sortname: 'id_factura_venta',
        caption: 'Lista de Facturas Autorizadas y Enviadas',
        sortorder: 'desc',
        viewrecords: true,

        gridComplete: function () {
            var ids = jQuery("#list9").jqGrid('getDataIDs');
            for (var i = 0; i < ids.length; i++) {
                var ids = jQuery("#list9").getDataIDs();

                for (var i = 0; i < ids.length; i++) {
                    var id_factura = ids[i];
                    be = "<a  onclick=\"reenviar('" + id_factura + "')\" title='Reenviar Correo' ><i class='fa fa-envelope-o' style='cursor:pointer; cursor: hand'> CORREO</i></a>";
                    jQuery("#list9").jqGrid('setRowData', ids[i], {
                        accion: be
                    });
                }
            }
            for (var i = 0; i < ids.length; i++) {
                var ids = jQuery("#list9").getDataIDs();

                for (var i = 0; i < ids.length; i++) {
                    var id_factura = ids[i];


                    be = "<a  onclick=\"enviarXml('" + id_factura + "')\" title='Reenviar Xml' ><i class='fa fa-arrow-circle-right' style='cursor:pointer; cursor: hand'> Envio Xml</i></a>";
                    jQuery("#list9").jqGrid('setRowData', ids[i], {
                        envio: be
                    });

                }
            }
            for (var i = 0; i < ids.length; i++) {
                var ids = jQuery("#list9").getDataIDs();
                for (var i = 0; i < ids.length; i++) {
                    var id_factura = ids[i];
                    be = "<a  onclick=\"reenviarXml('" + id_factura + "')\" title='Reenviar Xml' ><i class='fa fa-repeat' style='cursor:pointer; cursor: hand'> Consulta Xml</i></a>";
                    jQuery("#list9").jqGrid('setRowData', ids[i], {
                        reenvio: be
                    });
                }
            }
        },
        ondblClickRow: function (rowid) {
            var id = jQuery("#list9").jqGrid('getGridParam', 'selrow');
            jQuery('#list9').jqGrid('restoreRow', id);
            $("#list9").jqGrid("clearGridData", true);
            //  $("#buscar_no_autorizados").dialog("close");

        },
        // multiselect: true,
        //    caption: "Datos Retornados"
    }).jqGrid('navGrid', '#pager9', {
        add: false,
        edit: false,
        del: false,
        refresh: true,
        search: false,
        // multipleSearch: true,
        view: true
    });


    //    jQuery(window).bind('resize', function () {
    //        jQuery("#list").setGridWidth(jQuery('#grid_container').width(), true);
    //    }).trigger('resize');
    jQuery(window).bind('resize', function () {
        jQuery("#list9").setGridWidth(jQuery('#grid_container').width(), true);
    }).trigger('resize');

    /*
     * PROCESO WEBSERVICE
     */
    jQuery("#list10").jqGrid({
        url: 'xmlBuscarRetencionErrorWeb.php',
        datatype: 'xml',
        colNames: ['ID', 'FECHA', 'CLIENTE', 'N° AUTORIZACIÓN', 'VALOR TOTAL', 'ESTADO', 'ENVIO XML', 'CONSULTA COMPROBANTE'],
        colModel: [{
            name: 'id_factura_venta',
            index: 'id_factura_venta',
            editable: false,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 50
        },
        {
            name: 'fecha',
            index: 'fecha',
            editable: false,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 100
        },
        {
            name: 'cliente',
            index: 'cliente',
            editable: true,
            search: true,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 100
        },
        {
            name: 'autorizacion',
            index: 'autorizacion',
            editable: true,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 100
        },
        {
            name: 'total',
            index: 'total',
            editable: true,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 50
        },
        {
            name: 'estado',
            index: 'estado',
            editable: true,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 50
        },
        {
            name: 'envio',
            index: 'envio',
            editable: false,
            hidden: false,
            search: false,
            frozen: true,
            editrules: {
                required: true
            },
            align: 'center',
            width: '80px'
        },
        {
            name: 'reenvio',
            index: 'reenvio',
            editable: false,
            hidden: false,
            search: false,
            frozen: true,
            editrules: {
                required: true
            },
            align: 'center',
            width: '80px'
        },
        ],
        rowNum: 30,
        width: 1000,
        height: 220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager10'),
        sortname: 'id_factura_venta',
        caption: 'Lista de Error en el Servicio Web',
        sortorder: 'desc',
        viewrecords: true,

        gridComplete: function () {
            var ids = jQuery("#list10").jqGrid('getDataIDs');
            for (var i = 0; i < ids.length; i++) {
                var ids = jQuery("#list10").getDataIDs();

                for (var i = 0; i < ids.length; i++) {
                    var id_factura = ids[i];
                    be = "<a  onclick=\"reenviar('" + id_factura + "')\" title='Reenviar Correo' ><i class='fa fa-envelope-o' style='cursor:pointer; cursor: hand'> CORREO</i></a>";
                    jQuery("#list10").jqGrid('setRowData', ids[i], {
                        accion: be
                    });
                }
            }
            for (var i = 0; i < ids.length; i++) {
                var ids = jQuery("#list10").getDataIDs();

                for (var i = 0; i < ids.length; i++) {
                    var id_factura = ids[i];


                    be = "<a  onclick=\"enviarXml('" + id_factura + "')\" title='Reenviar Xml' ><i class='fa fa-arrow-circle-right' style='cursor:pointer; cursor: hand'> Envio Xml</i></a>";
                    jQuery("#list10").jqGrid('setRowData', ids[i], {
                        envio: be
                    });

                }
            }
            for (var i = 0; i < ids.length; i++) {
                var ids = jQuery("#list10").getDataIDs();
                for (var i = 0; i < ids.length; i++) {
                    var id_factura = ids[i];
                    be = "<a  onclick=\"reenviarXml('" + id_factura + "')\" title='Reenviar Xml' ><i class='fa fa-repeat' style='cursor:pointer; cursor: hand'> Consulta Xml</i></a>";
                    jQuery("#list10").jqGrid('setRowData', ids[i], {
                        reenvio: be
                    });
                }
            }
        },
        ondblClickRow: function (rowid) {
            var id = jQuery("#list10").jqGrid('getGridParam', 'selrow');
            jQuery('#list10').jqGrid('restoreRow', id);
            $("#list10").jqGrid("clearGridData", true);
            //  $("#buscar_no_autorizados").dialog("close");

        },
        // multiselect: true,
        //    caption: "Datos Retornados"
    }).jqGrid('navGrid', '#pager10', {
        add: false,
        edit: false,
        del: false,
        refresh: true,
        search: false,
        // multipleSearch: true,
        view: true
    });

    /*
     * PROCESO RETORNADO
     */
    jQuery("#list11").jqGrid({
        url: 'xmlBuscarRetencionRetornado.php',
        datatype: 'xml',
        colNames: ['ID', 'FECHA', 'CLIENTE', 'N° AUTORIZACIÓN', 'VALOR TOTAL', 'ESTADO', 'ACCIÓN'],
        colModel: [{
            name: 'id_factura_venta',
            index: 'id_factura_venta',
            editable: true,
            search: true,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 50
        },
        {
            name: 'fecha',
            index: 'fecha',
            editable: false,
            search: true,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 100
        },
        {
            name: 'cliente',
            index: 'cliente',
            editable: true,
            search: true,
            hidden: false,
            sorttype: "int",
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 100
        },
        {
            name: 'autorizacion',
            index: 'autorizacion',
            editable: false,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 100
        },
        {
            name: 'total',
            index: 'total',
            editable: true,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 50
        },
        {
            name: 'estado',
            index: 'estado',
            editable: true,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 50
        },
        {
            name: 'accion',
            index: 'accion',
            editable: false,
            hidden: false,
            search: false,
            frozen: true,
            editrules: {
                required: true
            },
            align: 'center',
            width: '80px'
        },
        ],
        rowNum: 30,
        width: 1000,
        height: 220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager11'),
        sortname: 'id_factura_venta',
        sortorder: 'desc',
        viewrecords: true,
        gridComplete: function () {
            var ids = jQuery("#list11").jqGrid('getDataIDs');
            for (var i = 0; i < ids.length; i++) {
                var ids = jQuery("#list11").getDataIDs();

                for (var i = 0; i < ids.length; i++) {
                    var id_factura = ids[i];
                    be = "<a  onclick=\"reenviar('" + id_factura + "')\" title='Reenviar Correo' ><i class='fa fa-envelope-o' style='cursor:pointer; cursor: hand'> CORREO</i></a>";
                    jQuery("#list11").jqGrid('setRowData', ids[i], {
                        accion: be
                    });
                }
            }
            for (var i = 0; i < ids.length; i++) {
                var ids = jQuery("#list11").getDataIDs();

                for (var i = 0; i < ids.length; i++) {
                    var id_factura = ids[i];


                    be = "<a  onclick=\"enviarXml('" + id_factura + "')\" title='Reenviar Xml' ><i class='fa fa-arrow-circle-right' style='cursor:pointer; cursor: hand'> Envio Xml</i></a>";
                    jQuery("#list11").jqGrid('setRowData', ids[i], {
                        envio: be
                    });

                }
            }
            for (var i = 0; i < ids.length; i++) {
                var ids = jQuery("#list11").getDataIDs();
                for (var i = 0; i < ids.length; i++) {
                    var id_factura = ids[i];
                    be = "<a  onclick=\"reenviarXml('" + id_factura + "')\" title='Reenviar Xml' ><i class='fa fa-repeat' style='cursor:pointer; cursor: hand'> Consulta Xml</i></a>";
                    jQuery("#list11").jqGrid('setRowData', ids[i], {
                        reenvio: be
                    });
                }
            }
        },
        ondblClickRow: function (rowid) {
            var id = jQuery("#list11").jqGrid('getGridParam', 'selrow');
            jQuery('#list11').jqGrid('restoreRow', id);


        },
        multiselect: false,
        caption: "Datos Retornados"

    }).jqGrid('navGrid', '#pager11', {
        add: false,
        edit: false,
        del: false,
        refresh: true,
        multipleSearch: true,
        view: true
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

    //Search Toolbar 
    //    jQuery("#list11").filterToolbar({stringResult: true, searchOperators: true, enableClear: true, searchOnEnter: true,
    //        defaultSearch: "eq"});
    jQuery("#list11").jqGrid('filterToolbar', {
        autosearch: true,
        stringResult: true,
        searchOperators: true,
        enableClear: true,
        searchOnEnter: true,
        defaultSearch: "eq"
    });
    //Paginador
    jQuery("#list11").jqGrid('navButtonAdd', '#pager11', {
        caption: "Reeviar",
        onClickButton: function () {
            var id = jQuery("#list11").jqGrid('getGridParam', 'selrow');
            jQuery('#list11').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list11").jqGrid('getRowData', id);
            }

        }
    });
    /*
     * PROCESO PRUEBA
     */
    jQuery("#list12").jqGrid({
        url: 'xmlBuscarRetencionRetornado.php',
        datatype: 'xml',
        colNames: ['ID', 'FECHA', 'CLIENTE', 'N° AUTORIZACIÓN', 'VALOR TOTAL', 'ESTADO', 'ACCIÓN'],
        colModel: [{
            name: 'id_factura_venta',
            index: 'id_factura_venta',
            editable: true,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 50
        },
        {
            name: 'fecha',
            index: 'fecha',
            editable: false,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 100
        },
        {
            name: 'cliente',
            index: 'cliente',
            editable: true,
            search: false,
            hidden: false,
            sorttype: "int",
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 100
        },
        {
            name: 'autorizacion',
            index: 'autorizacion',
            editable: false,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 100
        },
        {
            name: 'total',
            index: 'total',
            editable: true,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 50
        },
        {
            name: 'estado',
            index: 'estado',
            editable: true,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 50
        },
        {
            name: 'accion',
            index: 'accion',
            editable: false,
            hidden: false,
            search: false,
            frozen: true,
            editrules: {
                required: true
            },
            align: 'center',
            width: '80px'
        },
        ],
        rowNum: 30,
        width: 1000,
        height: 220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager11'),
        sortname: 'id_factura_venta',
        sortorder: 'desc',
        viewrecords: true,
        gridComplete: function () {
            var ids = jQuery("#list12").jqGrid('getDataIDs');
            for (var i = 0; i < ids.length; i++) {
                var ids = jQuery("#list12").getDataIDs();

                for (var i = 0; i < ids.length; i++) {
                    var id_factura = ids[i];
                    be = "<a  onclick=\"reenviar('" + id_factura + "')\" title='Reenviar Correo' ><i class='fa fa-envelope-o' style='cursor:pointer; cursor: hand'> CORREO</i></a>";
                    jQuery("#list12").jqGrid('setRowData', ids[i], {
                        accion: be
                    });
                }
            }
            for (var i = 0; i < ids.length; i++) {
                var ids = jQuery("#list12").getDataIDs();

                for (var i = 0; i < ids.length; i++) {
                    var id_factura = ids[i];


                    be = "<a  onclick=\"enviarXml('" + id_factura + "')\" title='Reenviar Xml' ><i class='fa fa-arrow-circle-right' style='cursor:pointer; cursor: hand'> Envio Xml</i></a>";
                    jQuery("#list12").jqGrid('setRowData', ids[i], {
                        envio: be
                    });

                }
            }
            for (var i = 0; i < ids.length; i++) {
                var ids = jQuery("#list12").getDataIDs();
                for (var i = 0; i < ids.length; i++) {
                    var id_factura = ids[i];
                    be = "<a  onclick=\"reenviarXml('" + id_factura + "')\" title='Reenviar Xml' ><i class='fa fa-repeat' style='cursor:pointer; cursor: hand'> Consulta Xml</i></a>";
                    jQuery("#list12").jqGrid('setRowData', ids[i], {
                        reenvio: be
                    });
                }
            }
        },
        ondblClickRow: function (rowid) {
            var id = jQuery("#list12").jqGrid('getGridParam', 'selrow');
            jQuery('#list12').jqGrid('restoreRow', id);


        },
        multiselect: true,
        caption: "Datos Retornados"

    }).jqGrid('navGrid', '#pager12', {
        add: false,
        edit: false,
        del: false,
        refresh: true,
        multipleSearch: true,
        view: true
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

    //Search Toolbar 
    //    jQuery("#list11").filterToolbar({stringResult: true, searchOperators: true, enableClear: true, searchOnEnter: true,
    //        defaultSearch: "eq"});
    jQuery("#list12").jqGrid('filterToolbar', {
        autosearch: true,
        stringResult: true,
        searchOperators: true,
        enableClear: true,
        searchOnEnter: true,
        defaultSearch: "eq"
    });
    //Paginador
    jQuery("#list11").jqGrid('navButtonAdd', '#pager11', {
        caption: "Reeviar",
        onClickButton: function () {
            var id = jQuery("#list12").jqGrid('getGridParam', 'selrow');
            jQuery('#list12').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list12").jqGrid('getRowData', id);
            }

        }
    });


    $("#ruc_ci").on("autocompletechange", function (event, ui) {
        if (ui.item == null) {
            $("#id_cliente").val("");
            $("#nombre_cliente").val("");
            $("#ruc_ci").val("");
        }
    });
    $("#nombre_cliente").on("autocompletechange", function (event, ui) {
        if (ui.item == null) {
            $("#id_cliente").val("");
            $("#ruc_ci").val("");
            $("#nombre_cliente").val("");
        }
    });

    $("#fecha_emision").change(function (e) {
        $("#fecha_caducidad")[0].min = $(this).val();
        $("#fecha_caducidad").val("");
    });
}

///consultar factura
function consultarFactura(idfactura, operacion) {
    showLoader();
    return $.ajax({
        url: "consultar_factura.php",
        method: "POST",
        dataType: "json",
        data: {
            op: operacion,
            id_factura: idfactura
        }
    }).always(() => {
        hideLoader();
    });
}

function autorizarFacturas(idcliente, fecha1, fecha2) {
    showLoader();
    return $.ajax({
        url: "autorizar_facturas.php",
        method: "POST",
        dataType: "json",
        data: {
            id: idcliente,
            f1: fecha1,
            f2: fecha2
        }
    }).always(() => {
        hideLoader();
        $('#list7').trigger('reloadGrid');
    });
}

function showLoader() {
    $(".loader_factura").css({ visibility: "visible" });
}
function hideLoader() {
    $(".loader_factura").css({ visibility: "hidden" });
}