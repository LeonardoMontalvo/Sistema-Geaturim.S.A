$(document).on("ready", inicio);
var calculoIVA = 0;
var t;
var idProformaTecnico = 0;
var num_serie_ret = ""
var cmpAddCliente;
$(document).keydown(function (e) {
    var e = e || event;
    var keycode = e.which || e.keyCode;
    var obj = e.target || e.srcElement;


    if (keycode == 13) {
        if ($("#forma_pago").val() == "otros") {
            agregar()
        }
    }
    // Tecla Control Cliente
    if (keycode == 40) {
        agregar()
    }




});
function obtenerNumSerieRet() {
    return $.ajax({
        type: "POST",
        url: "../../procesos/buscar_p_emision.php",
        data: "",
        success: function (data) {
            var val = data;
            if (val != 1) {
                num_serie_ret = val;
            }
        },
    });
}
function evento(e) {
    e.preventDefault();
}
$("#btnEstados").click(function () {
    $("#buscar_estados").dialog("open");
});
function este() {
    window.open('../../fpdf/ayuda_general.pdf');
}
$("[data-mask]").inputmask();
alertify.set({delay: 1000});
show();


var t;
function toFixedDown(value, digits) {
    if (isNaN(value))
        return 0;
    var n = value - Math.pow(10, -digits) / 2;
    n += n / Math.pow(2, 53);
    if (n < 0)
        n = 0.000;
    return n.toFixed(digits);
}
function cargar_cuentas() {
    var id = $("#formaspago_mixto").val();

    $("#list4").jqGrid('setGridParam', {
        url: 'xmlPlanCuentas_btn.php?id=' + id,
        datatype: 'xml'
    }).trigger('reloadGrid');


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

var dialogo =
        {
            autoOpen: false,
            resizable: false,
            width: 400,
            height: 220,
            modal: true
        };

var dialogo2 =
        {
            autoOpen: false,
            resizable: false,
            width: 830,
            height: 350,
            modal: true,
            // position: "top",
            show: "explode",
            hide: "blind"
        }
var dialogo22 =
        {
            autoOpen: false,
            resizable: false,
            width: 530,
            height: 320,
            modal: true,
            // position: "top",
            show: "explode",
            hide: "blind",

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
    width: 225,
    height: 150,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind"

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

var dialogo10 = {
    autoOpen: false,
    resizable: false,
    width: 1350,
    height: 350,
    modal: true,
    show: "explode",
    hide: "blind"
}
var dialogo12 = {
    autoOpen: false,
    resizable: false,
    width: 420,
    height: 150,
    modal: true,
    position: "center",
    show: "explode",
    hide: "blind"
}

var dialogo14 = {
    autoOpen: false,
    resizable: false,
    width: 400,
    height: 150,
    modal: true,
    position: "center",
    show: "explode",
    hide: "blind"
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
function countfactura() {
    var temp2 = "";
    var serie = $("#serie_retencion").val();
    for (var i = serie.length; i < 5; i++) {
        temp2 = temp2 + "0";
    }
    return temp2;
}

function autocompletar() {
    var temp = "";
    var serie = $("#serie_retencion").val();
    for (var i = serie.length; i < 9; i++) {
        temp = temp + "0";
    }
    return temp;
}

function enter2(e) {
    if (e.which == 13 || e.keyCode == 13) {
        comprobar();
        return false;
    }
    return true;
}
function enter6(e) {
    if (e.which == 13 || e.keyCode == 13) {
        reten();
        return false;
    }
    return true;
}
function enter7(e) {
    if (e.which == 13 || e.keyCode == 13) {
        comprobar2reten();
        return false;
    }
    return true;
}
function buscar_bienservicio_producto() {

    $.ajax({
        type: "POST",
        url: "buscar_bienservicio_producto.php",
        data: "id=" + $("#comprobante").val(),
        success: function (data) {
            var valSUM = data;
            $("#calculobien").val(valSUM);
        }
    });
}
function cambio_ret_ivas() {
    $('#retencionF1').prop('checked', true);
    $('#retencionI1').prop('checked', true);
    $('#retencionF1S').prop('checked', true);
    if (document.getElementById('retencionI2s').checked) {
        if ($("#comprobante").val() != "") {
            if (t == 0) {
                alertify.alert("La factura es una factura temporal");
                document.getElementById('retencionI1s').checked = true;
            } else {
                $("#tipoRetencionesIs").attr("disabled", false);
            }
        } else {
            alertify.alert("Error, debe seleccionar una factura");
            document.getElementById('retencionI1s').checked = true;
        }
    } else if (document.getElementById('retencionI1').checked) {
        document.getElementById("tipoRetencionesIs").selectedIndex = 0;
        $("#tipoRetencionesIs").attr("disabled", true);
        $("#calculoRetencionIs").attr("disabled", false);
        $("#calculoRetencionIs").val("0.000");
        $("#tipoRetencionesI").attr("disabled", true);
        $("#calculoRetencionI").attr("disabled", false);
        $("#calculoRetencionI").val("0.000");
    }
}
function reten() {
    if ($("#serie_retencion").val() == "") {
        $("#serie_retencion").focus();
        alertify.error("Ingrese número de factura");
    } else {
        var a = autocompletar($("#serie_retencion").val());
        $("#serie_retencion").val(a + "" + $("#serie_retencion").val());
        $("#ruc_ci").focus();
    }
}
function comprobar() {
    if ($("#concepto").val() == "") {
        $("#concepto").focus();
        alertify.error("Ingrese un producto");
    } else {
        if ($("#id_plan").val() == "") {
            $("#id_plan").focus();
            alertify.error("Seleccione una Cuenta contable");
        } else {
            if ($("#valor").val() == "") {
                $("#valor").focus();
                alertify.error("Ingrese un valor");
            } else {
                comprobar2();
            }
        }
    }
}

function cambio_ret_fuenteS() {
    $('#retencionF1').prop('checked', true);
    $('#retencionI1').prop('checked', true);
    if (document.getElementById('retencionF2S').checked) {
        if ($("#comprobante").val() != "") {
            if (t == 0) {
                alertify.alert("La factura es una factura temporal");
                document.getElementById('retencionF1S').checked = true;
            } else {
                $("#tipoRetencionesFS").attr("disabled", false);
            }
        } else {
            alertify.alert("Error, debe seleccionar una factura");
            document.getElementById('retencionF1S').checked = true;
        }
    } else if (document.getElementById('retencionF1S').checked) {
        document.getElementById("tipoRetencionesFS").selectedIndex = 0;
        $("#tipoRetencionesFS").attr("disabled", true);
        $("#calculoRetencionFS").attr("disabled", false);
        $("#calculoRetencionFS").val("0.000");
    }
}

function buscar_bienservicio_producto() {

    $.ajax({
        type: "POST",
        url: "buscar_bienservicio_producto.php",
        data: "id=" + $("#comprobante").val(),
        success: function (data) {
            var valSUM = data;
            $("#calculobien").val(valSUM);
        }
    });
}
function calculo_ret_fuenteS() {
    //    document.getElementById("tipoRetencionesF").selectedIndex = 0;
    $("#calculoRetencionF").val("0.000");

    document.getElementById("tipoRetencionesI").selectedIndex = 0;
    $("#calculoRetencionI").val("0.000");

    buscar_servicio_producto();

    var calculoRET = 0;
    var x = document.getElementById("tipoRetencionesFS").selectedIndex;
    $.ajax({
        type: "POST",
        url: "../../procesos/buscar_ret_fuente.php",
        data: "id=" + x,
        success: function (data) {
            var val = data;
            $("#calculoRetencionFS").val("");
            calculoRET = val;
            //            alertify.alert("El porcentaje de retención es del: " + calculoRET + "%");
            var valor = toFixedDown(((($("#calculoserv").val()) * calculoRET) / 100), 3);
            $("#calculoRetencionFS").val(numFormatter(2).format(valor));
            $("#porcent_retens").val(calculoRET);
            $("#calculoRetencionFS").focus();
        }
    });
}
function comprobar2reten() {
    if ($("#serie_retencion").val() == "") {
        $("#serie_retencion").focus();
        alertify.error("Debe Ingresar Num de retención");
    } else {
        var subtotal = 0;
        var xsid_bienes = document.getElementById("tipoRetencionesF").selectedIndex;
        var xsid_servicio = document.getElementById("tipoRetencionesFS").selectedIndex;
        var xsid_iva = document.getElementById("tipoRetencionesI").selectedIndex;
        var xsid_ivas = document.getElementById("tipoRetencionesIs").selectedIndex;
        if (document.getElementById('retencionF2').checked) {
            if ($("#calculobien").val() != "") {
                var filas = jQuery("#listPagoreten").jqGrid("getRowData");
                var descuento = 0;
                var total = 0;
                var su = 0;
                var precio = 0;
                var multi = 0;
                var flotante = 0;
                var resultado = 0;
                var repe = 0;
                var impuesto = 1;
                if (impuesto == 1) {
                    impuesto = "RENTA BIENES"
                }
                var x = document.getElementById("tipoRetencionesF").selectedIndex;

                if ($("#calculoRetencionF").val() == "0.000" && x == 4) {
                    if (filas.length == 0) {
                        var datarow = {
                            base_imponible: $("#calculobien").val(),
                            impuesto: impuesto,
                            porcent_reten: $("#porcent_reten").val(),
                            valor_retenido: $("#calculoRetencionF").val(),
                            id_retenciones_ser: xsid_bienes
                        };
                        su = jQuery("#listPagoreten").jqGrid('addRowData', $("#calculobien").val(), datarow);
                        //            su = jQuery("#listPagoreten").jqGrid('addRowData', $("#cod_producto").val(), datarow);
                        //                                limpiar_campos();
                    } else {
                        for (var i = 0; i < filas.length; i++) {
                            var id = filas[i];
                            repe = 1;
                        }
                        if (repe != 1) {

                            datarow = {
                                base_imponible: $("#calculobien").val(),
                                impuesto: impuesto,
                                porcent_reten: $("#porcent_reten").val(),
                                valor_retenido: $("#calculoRetencionF").val(),
                                id_retenciones_ser: xsid_bienes
                            };
                            su = jQuery("#listPagoreten").jqGrid('addRowData', $("#calculobien").val(), datarow);
                            //                                    limpiar_campos();
                        } else {
                            datarow = {
                                base_imponible: $("#calculobien").val(),
                                impuesto: impuesto,
                                porcent_reten: $("#porcent_reten").val(),
                                valor_retenido: $("#calculoRetencionF").val(),
                                id_retenciones_ser: xsid_bienes
                            };
                            su = jQuery("#listPagoreten").jqGrid('addRowData', $("#calculobien").val(), datarow);
                            limpiar_campos();
                        }
                    }
                } else if ($("#calculoRetencionF").val() == "0.000" || $("#calculoRetencionF").val() == "0.00" && x != 4) {
                    alertify.error("El resultado debe ser diferente de 0");
                } else if ($("#calculoRetencionF").val() != "0.000" && x != 4) {
                    if (filas.length == 0) {
                        var datarow = {
                            base_imponible: $("#calculobien").val(),
                            impuesto: impuesto,
                            porcent_reten: $("#porcent_reten").val(),
                            valor_retenido: $("#calculoRetencionF").val(),
                            id_retenciones_ser: xsid_bienes
                        };
                        su = jQuery("#listPagoreten").jqGrid('addRowData', $("#calculobien").val(), datarow);
                        //            su = jQuery("#listPagoreten").jqGrid('addRowData', $("#cod_producto").val(), datarow);
                        //                                limpiar_campos();
                    } else {
                        for (var i = 0; i < filas.length; i++) {
                            var id = filas[i];
                            repe = 1;
                        }
                        if (repe != 1) {

                            datarow = {
                                base_imponible: $("#calculobien").val(),
                                impuesto: impuesto,
                                porcent_reten: $("#porcent_reten").val(),
                                valor_retenido: $("#calculoRetencionF").val(),
                                id_retenciones_ser: xsid_bienes
                            };
                            su = jQuery("#listPagoreten").jqGrid('addRowData', $("#calculobien").val(), datarow);
                            //                                    limpiar_campos();
                        } else {
                            datarow = {
                                base_imponible: $("#calculobien").val(),
                                impuesto: impuesto,
                                porcent_reten: $("#porcent_reten").val(),
                                valor_retenido: $("#calculoRetencionF").val(),
                                id_retenciones_ser: xsid_bienes
                            };
                            su = jQuery("#listPagoreten").jqGrid('addRowData', $("#calculobien").val(), datarow);
                            limpiar_campos();
                        }
                    }
                }
                var fil = jQuery("#listPagoreten").jqGrid("getRowData");
                for (var t = 0; t < fil.length; t++) {
                    var dd = fil[t];

                    subtotal = (subtotal + parseFloat(dd['valor_retenido']));


                }

                $("#total_retencion").val(subtotal.toFixed(2));

            }
            $('#retencionF1').prop('checked', true);
        }
        if (document.getElementById('retencionF2S').checked) {

            if ($("#calculoserv").val() != "") {
                console.log($("#calculoserv").val());
                var filas = jQuery("#listPagoreten").jqGrid("getRowData");
                var descuento = 0;
                var total = 0;
                var su = 0;
                var precio = 0;
                var multi = 0;
                var flotante = 0;
                var resultado = 0;
                var repe = 0;
                var impuesto = 3;
                if (impuesto == 3) {
                    impuesto = "RENTA SERVICIOS"
                }

                var x = document.getElementById("tipoRetencionesFS").selectedIndex;

                if ($("#calculoRetencionFS").val() == "0.000" && x == 4) {



                    if (filas.length == 0) {
                        var datarow = {
                            base_imponible: $("#calculoserv").val(),
                            impuesto: impuesto,
                            porcent_reten: $("#porcent_retens").val(),
                            valor_retenido: $("#calculoRetencionFS").val(),
                            id_retenciones_ser: xsid_servicio
                        };
                        su = jQuery("#listPagoreten").jqGrid('addRowData', $("#calculoserv").val(), datarow);
                        // limpiar_campos();
                    } else {
                        for (var i = 0; i < filas.length; i++) {
                            var id = filas[i];
                            repe = 1;
                        }
                        if (repe != 1) {

                            datarow = {
                                base_imponible: $("#calculoserv").val(),
                                impuesto: impuesto,
                                porcent_reten: $("#porcent_retens").val(),
                                valor_retenido: $("#calculoRetencionFS").val(),
                                id_retenciones_ser: xsid_servicio
                            };
                            su = jQuery("#listPagoreten").jqGrid('addRowData', $("#calculoserv").val(), datarow);
                            //limpiar_campos();
                        } else {
                            datarow = {
                                base_imponible: $("#calculoserv").val(),
                                impuesto: impuesto,
                                porcent_reten: $("#porcent_retens").val(),
                                valor_retenido: $("#calculoRetencionFS").val(),
                                id_retenciones_ser: xsid_servicio
                            };
                            su = jQuery("#listPagoreten").jqGrid('addRowData', $("#calculoserv").val(), datarow);
                            //                limpiar_campos();
                        }
                    }
                } else if ($("#calculoRetencionFS").val() == "0.000" && x != 4) {
                    alertify.error("El resultado debe ser diferente de 0");
                } else if ($("#calculoRetencionFS").val() != "0.000" && x != 4) {



                    if (filas.length == 0) {
                        var datarow = {
                            base_imponible: $("#calculoserv").val(),
                            impuesto: impuesto,
                            porcent_reten: $("#porcent_retens").val(),
                            valor_retenido: $("#calculoRetencionFS").val(),
                            id_retenciones_ser: xsid_servicio
                        };
                        su = jQuery("#listPagoreten").jqGrid('addRowData', $("#calculoserv").val(), datarow);
                        // limpiar_campos();
                    } else {
                        for (var i = 0; i < filas.length; i++) {
                            var id = filas[i];
                            repe = 1;
                        }
                        if (repe != 1) {

                            datarow = {
                                base_imponible: $("#calculoserv").val(),
                                impuesto: impuesto,
                                porcent_reten: $("#porcent_retens").val(),
                                valor_retenido: $("#calculoRetencionFS").val(),
                                id_retenciones_ser: xsid_servicio
                            };
                            su = jQuery("#listPagoreten").jqGrid('addRowData', $("#calculoserv").val(), datarow);
                            //limpiar_campos();
                        } else {
                            datarow = {
                                base_imponible: $("#calculoserv").val(),
                                impuesto: impuesto,
                                porcent_reten: $("#porcent_retens").val(),
                                valor_retenido: $("#calculoRetencionFS").val(),
                                id_retenciones_ser: xsid_servicio
                            };
                            su = jQuery("#listPagoreten").jqGrid('addRowData', $("#calculoserv").val(), datarow);
                            //                limpiar_campos();
                        }
                    }



                }


                var fil = jQuery("#listPagoreten").jqGrid("getRowData");
                for (var t = 0; t < fil.length; t++) {
                    var dd = fil[t];

                    subtotal = (subtotal + parseFloat(dd['valor_retenido']));


                }

                $("#total_retencion").val(subtotal.toFixed(2));

            }
            $('#retencionF1S').prop('checked', true);
        }

        if (document.getElementById("tipoRetencionesI").selectedIndex != '0') {



            if ($("#calculoRetencionI").val() != "") {
                var filas = jQuery("#listPagoreten").jqGrid("getRowData");
                var descuento = 0;
                var total = 0;
                var su = 0;
                var precio = 0;
                var multi = 0;
                var flotante = 0;
                var resultado = 0;
                var repe = 0;
                var impuesto = 3;

                if (impuesto == 3) {
                    impuesto = "IVA"
                }


                if ($("#calculoRetencionI").val() != "0.000") {




                    if (filas.length == 0) {
                        var datarow = {
                            base_imponible: parseFloat($("#iva").val()).toFixed(2),
                            impuesto: impuesto,
                            porcent_reten: $("#porcent_iva").val(),
                            valor_retenido: $("#calculoRetencionI").val(),
                            id_retenciones_ser: xsid_iva

                        };
                        su = jQuery("#listPagoreten").jqGrid('addRowData', parseFloat($("#iva").val()).toFixed(2), datarow);
                        //                                limpiar_campos();
                    } else {
                        for (var i = 0; i < filas.length; i++) {
                            var id = filas[i];
                            repe = 1;
                        }
                        if (repe != 1) {

                            datarow = {
                                base_imponible: parseFloat($("#iva").val()).toFixed(2),
                                impuesto: impuesto,
                                porcent_reten: $("#porcent_iva").val(),
                                valor_retenido: $("#calculoRetencionI").val(),
                                id_retenciones_ser: xsid_iva
                            };
                            su = jQuery("#listPagoreten").jqGrid('addRowData', parseFloat($("#iva").val()).toFixed(2), datarow);
                            //                                    limpiar_campos();
                        } else {
                            datarow = {
                                base_imponible: parseFloat($("#iva").val()).toFixed(2),
                                impuesto: impuesto,
                                porcent_reten: $("#porcent_iva").val(),
                                valor_retenido: $("#calculoRetencionI").val(),
                                id_retenciones_ser: xsid_iva
                            };
                            su = jQuery("#listPagoreten").jqGrid('addRowData', parseFloat($("#iva").val()).toFixed(2), datarow);
                            //                limpiar_campos();
                        }
                    }
                } else if ($("#calculoRetencionI").val() == "0.000") {
                    alertify.error("El resultado debe ser diferente de 0");
                }


                var fil = jQuery("#listPagoreten").jqGrid("getRowData");
                for (var t = 0; t < fil.length; t++) {
                    var dd = fil[t];

                    subtotal = (subtotal + parseFloat(dd['valor_retenido']));


                }

                $("#total_retencion").val(subtotal.toFixed(2));

            }
            $('#retencionI1').prop('checked', true);
        }

        if (document.getElementById("tipoRetencionesIs").selectedIndex != '0') {
            if ($("#calculoservivas").val() != "") {
                console.log($("#calculoservivas").val());
                var filas = jQuery("#listPagoreten").jqGrid("getRowData");
                var descuento = 0;
                var total = 0;
                var su = 0;
                var precio = 0;
                var multi = 0;
                var flotante = 0;
                var resultado = 0;
                var repe = 0;
                var impuesto = 3;

                if (impuesto == 3) {
                    impuesto = "IVA SERVICIOS"
                }
                var calculoservivaS = $("#calculoservivas").val() * toFixedDown((12 / 100), 3);


                if ($("#calculoRetencionIs").val() != "0.000") {


                    if (filas.length == 0) {
                        var datarow = {
                            base_imponible: parseFloat(calculoservivaS).toFixed(2),
                            impuesto: impuesto,
                            porcent_reten: $("#porcent_ivas").val(),
                            valor_retenido: $("#calculoRetencionIs").val(),
                            id_retenciones_ser: xsid_ivas

                        };
                        su = jQuery("#listPagoreten").jqGrid('addRowData', parseFloat(calculoservivaS).toFixed(2), datarow);
                    } else {
                        for (var i = 0; i < filas.length; i++) {
                            var id = filas[i];
                            repe = 1;
                        }
                        if (repe != 1) {
                            datarow = {
                                base_imponible: parseFloat(calculoservivaS).toFixed(2),
                                impuesto: impuesto,
                                porcent_reten: $("#porcent_ivas").val(),
                                valor_retenido: $("#calculoRetencionIs").val(),
                                id_retenciones_ser: xsid_ivas

                            };
                            su = jQuery("#listPagoreten").jqGrid('addRowData', parseFloat(calculoservivaS).toFixed(2), datarow);
                            //                                    limpiar_campos();
                        } else {
                            datarow = {
                                base_imponible: parseFloat(calculoservivaS).toFixed(2),
                                impuesto: impuesto,
                                porcent_reten: $("#porcent_ivas").val(),
                                valor_retenido: $("#calculoRetencionIs").val(),
                                id_retenciones_ser: xsid_ivas

                            };
                            su = jQuery("#listPagoreten").jqGrid('addRowData', parseFloat(calculoservivaS).toFixed(2), datarow);
                        }
                    }
                } else if ($("#calculoRetencionIs").val() == "0.000") {
                    alertify.error("El resultado debe ser diferente de 0");
                }



                var fil = jQuery("#listPagoreten").jqGrid("getRowData");
                for (var t = 0; t < fil.length; t++) {
                    var dd = fil[t];

                    subtotal = (subtotal + parseFloat(dd['valor_retenido']));


                }

                $("#total_retencion").val(subtotal.toFixed(2));

            }
            $('#retencionI1s').prop('checked', true);
        }
    }

}

function limpiar_campos() {
    $("#cod_producto").val("");
    //    $("#codigo_barras").val("");
    //    $("#codigo").val("");
    //    $("#producto").val("");
    //    $("#cantidad").val("");
    $("#precio").val("");
    //    $("#descuento").val("");
    $("#iva_producto").val("");
    //    $("#carga_series").val("");
    //    $("#incluye").val("");

    $("#concepto").val("");
    $("#id_plan").val("");
    $("#codigo_plan").val("");
    $("#valor").val("");

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
function ValidNum(e) {
    if (e.keyCode < 48 || e.keyCode > 57) {
        e.returnValue = false;
    }
    return true;
}

function nuevo_gasto() {
    location.reload();
}
function entercantidad(e) {
    if (e.which == 13 || e.keyCode == 13) {
        entrar();
        return false;
    }
    return true;
}
function enter3(e) {
    if (e.which == 13 || e.keyCode == 13) {
        comprobar2();
        return false;
    }
    return true;
}
function comprobar2() {
    $.ajax({
        type: "POST",
        url: "buscar_iva.php",
        data: "cod_producto",
        success: function (data) {
            var val = data;
            if (val != 1) {
                calculoIVA = val;
            }
        }
    });
    var subtotal0 = 0;
    var subtotal12 = 0;
    var subtotal_total = 0;
    var iva12 = 0;
    var total_total = 0;
    var descu_total = 0;

    if ($("#concepto").val() == "") {
        $("#concepto").focus();
        alertify.error("Ingrese ");
    } else {
        if ($("#id_plan").val() == "") {
            $("#id_plan").focus();
            alertify.error("Ingrese cuenta contable");
        } else {
            if ($("#valor").val() == "") {
                $("#valor").focus();
                alertify.error("Ingrese un Valor");
            } else {

                var filas = jQuery("#list").jqGrid("getRowData");
                var descuento = 0;
                var total = 0;
                var su = 0;
                var precio = 0;
                var multi = 0;
                var flotante = 0;
                var resultado = 0;
                var repe = 0;
                var suma = 0;

                if (filas.length == 0) {
                    if ($("#descuento").val() != "") {
                        desc = $("#descuento").val();
                        precio = parseFloat($("#valor").val());
                        //                        multi = parseFloat($("#cantidad").val()) * parseFloat($("#precio").val());
                        //                        descuento = ((multi * parseFloat(desc)) / 100);
                        //                        flotante = parseFloat(descuento);
                        //                        resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                        //                        total = multi - resultado;
                    } else {
                        desc = 0;
                        precio = parseFloat($("#valor").val());
                        //                        multi = parseFloat($("#cantidad").val()) * parseFloat($("#precio").val());
                        //                        descuento = ((multi * parseFloat(desc)) / 100);
                        //                        flotante = parseFloat(descuento);
                        //                        resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                        //                        total = parseFloat($("#cantidad").val()) * precio;
                    }

                    var datarow = {
                        concepto: $("#concepto").val(),
                        id_plan: $("#id_plan").val(),
                        cuenta_contable: $("#codigo_plan").val(),
                        iva: $("#tipo_iva").val(),
                        centro_costo: $("#centro_costo").val(),
                        valor: precio,
                        bien_servicio: $("#bien_servicio").val()

                    };
                    addCentroCostoRowData(datarow);

                    su = jQuery("#list").jqGrid('addRowData', $("#concepto").val(), datarow);
                    limpiar_campos();
                } else {
                    for (var i = 0; i < filas.length; i++) {
                        var id = filas[i];

                        if (id['cuenta_contable'] == $("#codigo_plan").val()) {
                            repe = 1;
                            //                            var can = id['cantidad'];
                        }
                    }

                    //                    if (repe == 1) {
                    ////                        suma = parseInt(can) + parseInt($("#cantidad").val());
                    //
                    //                        if ($("#descuento").val() != "") {
                    ////                            desc = $("#descuento").val();
                    //                            precio = parseFloat($("#valor").val());
                    ////                            multi = parseFloat(suma) * parseFloat($("#precio").val());
                    ////                            descuento = ((multi * parseFloat(desc)) / 100);
                    ////                            flotante = parseFloat(descuento);
                    ////                            resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                    ////                            total = multi - resultado;
                    //                        } else {
                    //                            desc = 0;
                    //                            precio = parseFloat($("#valor").val());
                    //
                    //                        }
                    //
                    //                        datarow = {
                    //                            concepto: $("#concepto").val(),
                    //                            id_plan: $("#id_plan").val(),
                    //                            cuenta_contable: $("#codigo_plan").val(),
                    //                            iva: $("#tipo_iva").val(),
                    //                            centro_costo: $("#centro_costo").val(),
                    //                            valor: precio,
                    //                            bien_servicio: $("#bien_servicio").val()
                    //                        };
                    //
                    //                        su = jQuery("#list").jqGrid('setRowData', $("#concepto").val(), datarow);
                    //                        limpiar_campos();
                    //                    } else {
                    if ($("#descuento").val() != "") {
                        //                            desc = $("#descuento").val();
                        precio = parseFloat($("#valor").val());
                        //                            multi = parseFloat($("#cantidad").val()) * parseFloat($("#precio").val());
                        //                            descuento = ((multi * parseFloat(desc)) / 100);
                        //                            flotante = parseFloat(descuento);
                        //                            resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                        //                            total = multi - resultado;
                    } else {
                        desc = 0;
                        precio = parseFloat($("#valor").val());
                        //                            multi = parseFloat($("#cantidad").val()) * parseFloat($("#precio").val());
                        //                            descuento = ((multi * parseFloat(desc)) / 100);
                        //                            flotante = parseFloat(descuento);
                        //                            resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                        //                            total = parseFloat($("#cantidad").val()) * precio;
                    }

                    datarow = {
                        concepto: $("#concepto").val(),
                        id_plan: $("#id_plan").val(),
                        cuenta_contable: $("#codigo_plan").val(),
                        iva: $("#tipo_iva").val(),
                        centro_costo: $("#centro_costo").val(),
                        valor: precio,
                        bien_servicio: $("#bien_servicio").val()
                    };

                    addCentroCostoRowData(datarow);
                    su = jQuery("#list").jqGrid('addRowData', $("#concepto").val(), datarow);
                    limpiar_campos();
                    //                    }
                }

                // calcular valores
                var subtotal = 0;
                var sub = 0;
                var sub1 = 0;
                var sub2 = 0;
                var iva = 0;
                var iva1 = 0;
                var iva2 = 0;

                var fil = jQuery("#list").jqGrid("getRowData");
                for (var t = 0; t < fil.length; t++) {
                    var dd = fil[t];
                    if (dd['iva'] == "Si") {

                        // if(dd['incluye'] == "No"){
                        subtotal = dd['valor'];
                        sub1 = subtotal;
                        /*$.ajax({
                         type: "POST",
                         url: "buscar_iva.php",
                         data: "cod_producto",
                         success: function(data) {
                         var  val = data;
                         if (val != 1) {
                         calculoIVA=val;
                         alertify.alert(calculoIVA);
                         }
                         }
                         });*/
                        iva1 = sub1 * toFixedDown((calculoIVA / 100), 3);
                        subtotal0 = parseFloat(subtotal0) + 0;
                        subtotal12 = parseFloat(subtotal12) + parseFloat(sub1);
                        subtotal_total = parseFloat(subtotal0) + parseFloat(subtotal12);
                        //                        descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);
                        iva12 = parseFloat(iva12) + parseFloat(iva1);

                        subtotal0 = parseFloat(subtotal0);
                        subtotal12 = parseFloat(subtotal12);
                        subtotal_total = parseFloat(subtotal_total);
                        iva12 = parseFloat(iva12);
                        //                        descu_total = parseFloat(descu_total);
                        // } else {
                        //     if(dd['incluye'] == "Si"){

                        //         subtotal = dd['total'];
                        //         sub2 = (subtotal / 1.12).toFixed(3);
                        //         iva2 = (sub2 * 0.12).toFixed(3);

                        //         subtotal0 = parseFloat(subtotal0) + 0;
                        //         subtotal12 = parseFloat(subtotal12) + parseFloat(sub2);
                        //         subtotal_total = parseFloat(subtotal0) + parseFloat(subtotal12);
                        //         iva12 = parseFloat(iva12) + parseFloat(iva2);
                        //         descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);

                        //         subtotal0 = parseFloat(subtotal0).toFixed(3);
                        //         subtotal12 = parseFloat(subtotal12).toFixed(3);
                        //         subtotal_total = parseFloat(subtotal_total).toFixed(3);
                        //         iva12 = parseFloat(iva12).toFixed(3);
                        //         descu_total = parseFloat(descu_total).toFixed(3);
                        //     }
                        // }
                    } else {
                        if (dd['iva'] == "No") {
                            subtotal = dd['valor'];
                            sub = subtotal;

                            subtotal0 = parseFloat(subtotal0) + parseFloat(sub);
                            subtotal12 = parseFloat(subtotal12) + 0;
                            subtotal_total = parseFloat(subtotal0) + parseFloat(subtotal12);
                            iva12 = parseFloat(iva12) + 0;
                            //                            descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);

                            subtotal0 = parseFloat(subtotal0);
                            subtotal12 = parseFloat(subtotal12);
                            subtotal_total = parseFloat(subtotal_total);
                            iva12 = parseFloat(iva12);
                            //                            descu_total = parseFloat(descu_total);
                        }
                    }
                }
                total_total = parseFloat(total_total) + (parseFloat(subtotal0) + parseFloat(subtotal12) + parseFloat(iva12));
                total_total = parseFloat(total_total);

                $("#total_p").val(subtotal0);
                $("#total_p2").val(subtotal12);
                $("#sub").val(subtotal_total);
                $("#iva").val(iva12);
                $("#desc").val(descu_total);
                $("#tot").val(total_total.toFixed(2));
                $("#total_px").val(subtotal0.toFixed(2));
                $("#total_p2x").val(subtotal12.toFixed(2));
                $("#subx").val(subtotal_total.toFixed(2));
                $("#ivax").val(iva12.toFixed(2));
                //                $("#descx").val(descu_total.toFixed(2));
                $("#totx").val(total_total.toFixed(2));
                $("#valor_factura").val(total_total.toFixed(2));
                $("#codigo_barras").focus();
            }
        }
    }



}
function modificar_factura() {
    var tam = jQuery("#list").jqGrid("getRowData");
    if ($("#comprobante").val() == "") {
        alertify.error("Selccione un Gasto");
        $("#buscar_gastos").dialog("open");
    } else {
        if ($("#id_proveedor").val() == "") {
            $("#ruc_ci").focus();
            alertify.error("Indique un Proveedor");
        } else {
            if ($("#fecha_emision").val() == "") {
                $("#fecha_emision").focus();
                alertify.error("Seleccione la Fecha Emision");
            } else {
                if ($("#factura").val() == "") {
                    $("#factura").focus();
                    alertify.error("Seleccione Num Factura");
                } else {
                    if ($("#autorizacion").val() == "") {
                        $("#autorizacion").focus();
                        alertify.error("Igrese un Num Autorizacion");
                    } else {

                        $("#btnModificar").attr("disabled", false);
                        var v1 = new Array();
                        var v2 = new Array();
                        var v3 = new Array();
                        var v4 = new Array();
                        var v5 = new Array();

                        var string_v1 = "";
                        var string_v2 = "";
                        var string_v3 = "";
                        var string_v4 = "";
                        var string_v5 = "";

                        var fil = jQuery("#list").jqGrid("getRowData");
                        for (var i = 0; i < fil.length; i++) {
                            var datos = fil[i];
                            v1[i] = datos['cod_producto'];
                            v2[i] = datos['cantidad'];
                            v3[i] = datos['precio_u'];
                            v4[i] = datos['descuento'];
                            v5[i] = datos['total'];
                        }
                        for (i = 0; i < fil.length; i++) {
                            string_v1 = string_v1 + "|" + v1[i];
                            string_v2 = string_v2 + "|" + v2[i];
                            string_v3 = string_v3 + "|" + v3[i];
                            string_v4 = string_v4 + "|" + v4[i];
                            string_v5 = string_v5 + "|" + v5[i];
                        }

                        $.ajax({
                            type: "POST",
                            url: "modificar_gastos.php",
                            data: "comprobante=" + $("#comprobante").val() + "&id_proveedor=" + $("#id_proveedor").val() + "&fecha_emision=" + $("#fecha_emision").val() + "&factura=" + $("#factura").val() + "&autorizacion=" + $("#autorizacion").val() + "&tarifa0=" + $("#total_p").val() + "&tarifa12=" + $("#total_p2x").val() + "&iva=" + $("#iva").val() + "&desc=" + $("#desc").val() + "&tot=" + $("#tot").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&valor=" + $("#totx").val() + "&subtotal=" + $("#subx").val() + "&iva=" + $("#ivax").val() + "&descripcion=" + $("#descripcion").val() + "&banco=" + $("#tipo_gasto").val(),
                            success: function (data) {
                                var val = data;
                                if (val != 0) {
                                    alertify.alert("Gasto Modificado correctamente", function () {

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
function entrar() {
    if ($("#concepto").val() == "") {
        $("#concepto").focus();
        alertify.error("Ingrese un producto");
    } else {
        if ($("#id_plan").val() == "") {
            $("#codigo_plan").focus();
            alertify.error("Ingrese Cuenta contable");
        } else {
            $("#valor").focus();
        }
    }
}
function enter(e) {
    if (e.which == 13 || e.keyCode == 13) {
        porcenta();
        return false;
    }
    return true;
}
function porcenta() {

    var var_utili_mino = parseFloat($("#subtotal").val());
    var var_iva = parseFloat($("#valor_iva_pro").val());
    var cal_porcent = (var_utili_mino + 100) / 100;
    var cal_iva = var_iva / 100;
    var val = var_utili_mino * cal_iva;
    var entero = parseFloat(val.toFixed(2));
    var vartotal = var_utili_mino + entero;
    $("#iva").val(entero);
    $("#valor").val(vartotal);

}
function cambio_ret_fuente() {
    $('#retencionF1S').prop('checked', true);
    $('#retencionI1').prop('checked', true);

    if (document.getElementById('retencionF2').checked) {
        if ($("#comprobante").val() != "") {
            if (t == 0) {
                alertify.alert("La factura es una factura temporal");
                document.getElementById('retencionF1').checked = true;
            } else {
                $("#tipoRetencionesF").attr("disabled", false);
            }
        } else {
            alertify.alert("Error, debe seleccionar una factura");
            document.getElementById('retencionF1').checked = true;
        }
    } else if (document.getElementById('retencionF1').checked) {
        document.getElementById("tipoRetencionesF").selectedIndex = 0;
        $("#tipoRetencionesF").attr("disabled", true);
        $("#calculoRetencionF").attr("disabled", false);
        $("#calculoRetencionF").val("0.000");
    }
}

function eliminar_factura() {
    if ($("#comprobante").val() == "") {
        alertify.error("Seleccione un Gasto");
        $("#buscar_gastos").dialog("open");
    } else {
        $("#clave_permiso").dialog("open");
    }
}

function cambio_ret_iva() {

    $('#retencionF1').prop('checked', true);
    $('#retencionF1S').prop('checked', true);
    if (document.getElementById('retencionI2').checked) {
        if ($("#comprobante").val() != "") {
            if (t == 0) {
                alertify.alert("La factura es una factura temporal");
                document.getElementById('retencionI1').checked = true;
            } else {
                $("#tipoRetencionesI").attr("disabled", false);
            }
        } else {
            alertify.alert("Error, debe seleccionar una factura");
            document.getElementById('retencionI1').checked = true;
        }
    } else if (document.getElementById('retencionI1').checked) {
        document.getElementById("tipoRetencionesI").selectedIndex = 0;
        $("#tipoRetencionesI").attr("disabled", true);
        $("#calculoRetencionI").attr("disabled", false);
        $("#calculoRetencionI").val("0.000");
    }


}
function numFormatter(d) {
    return new Intl.NumberFormat("en-US", {
        minimumFractionDigits: d,
        maximumFractionDigits: d,
        useGrouping: false,
    });
}
function calculo_ret_fuente() {
    document.getElementById("tipoRetencionesFS").selectedIndex = 0;
    $("#calculoRetencionFS").val("0.000");

    document.getElementById("tipoRetencionesI").selectedIndex = 0;
    $("#calculoRetencionI").val("0.000");



    buscar_bienservicio_producto();

    var calculoRET = 0;
    var x = document.getElementById("tipoRetencionesF").selectedIndex;
    $.ajax({
        type: "POST",
        url: "../../procesos/buscar_ret_fuente.php",
        data: "id=" + x,
        success: function (data) {
            var val = data;

            calculoRET = val;
            $("#calculoRetencionF").val("");
            //            alertify.alert("El porcentaje de retención es del: " + calculoRET + "%");

            var valor = toFixedDown(((($("#calculobien").val()) * calculoRET) / 100), 3);
            $("#calculoRetencionF").val(numFormatter(2).format(valor));
            $("#porcent_reten").val(calculoRET);
            $("#calculoRetencionF").focus();
        }
    });
}
function buscar_servicio_producto() {

    $.ajax({
        type: "POST",
        url: "buscar_servicio_producto.php",
        data: "id=" + $("#comprobante").val(),
        success: function (data) {
            var valSUMs = data;
            $("#calculoserv").val(valSUMs);

        }
    });
}
function calculo_ret_iva() {
    //    document.getElementById("tipoRetencionesF").selectedIndex = 0;
    $("#calculoRetencionF").val("0.000");

    document.getElementById("tipoRetencionesFS").selectedIndex = 0;
    $("#calculoRetencionFS").val("0.000");

    buscar_bienservicio_producto_iva();
    var calculoRET = 0;
    var x = document.getElementById("tipoRetencionesI").selectedIndex;
    $.ajax({
        type: "POST",
        url: "../../procesos/buscar_ret_iva.php",
        data: "id=" + x,
        success: function (data) {
            var val = data;
            if (val != 0) {

                calculoRET = val;
                var calculo_serviva = $("#calculobieniva").val() * toFixedDown((12 / 100), 3);
                //                alertify.alert("El porcentaje de retención es del: " + calculoRET + "%");
                var valor = toFixedDown((((calculo_serviva) * calculoRET) / 100), 3);
                $("#calculoRetencionI").val(numFormatter(2).format(valor));
                $("#porcent_iva").val(calculoRET);
                $("#calculoRetencionI").focus();
            }
        }
    });
}

function buscar_bienservicio_producto_iva() {

    $.ajax({
        type: "POST",
        url: "buscar_bienservicio_producto_iva.php",
        data: "id=" + $("#comprobante").val(),
        success: function (data) {
            var valSUM = data;
            $("#calculobieniva").val(valSUM);
        }
    });
}

function buscar_servicio_iva() {
    console.log("entro iva servicios");
    $.ajax({
        type: "POST",
        url: "buscar_ret_iva_servicio.php",
        data: "id=" + $("#comprobante").val(),
        success: function (data) {
            var valSUMs = data;
            $("#calculoservivas").val(valSUMs);
        }
    });
}

function calculo_ret_ivas() {
    $("#calculoRetencionF").val("0.000");
    document.getElementById("tipoRetencionesFS").selectedIndex = 0;
    document.getElementById("tipoRetencionesI").selectedIndex = 0;
    $("#calculoRetencionFS").val("0.000");
    $("#calculoRetencionI").val("0.000");
    buscar_servicio_iva();

    var calculoRET = 0;
    var x = document.getElementById("tipoRetencionesIs").selectedIndex;
    $.ajax({
        type: "POST",
        url: "../../procesos/buscar_ret_iva.php",
        data: "id=" + x,
        success: function (data) {
            var val = data;
            if (val != 0) {
                calculoRET = val;
                var calculoservivas = $("#calculoservivas").val() * toFixedDown((12 / 100), 3);
                var valor = toFixedDown((((calculoservivas) * calculoRET) / 100), 3);
                $("#calculoRetencionIs").val(numFormatter(2).format(valor));
                $("#porcent_ivas").val(calculoRET);
                $("#calculoRetencionIs").focus();
            }
        }
    });
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
function buscarCliente(term) {
    return $.ajax({
        url: "busquedaCliente.php",
        dataType: "json",
        method: "GET",
        data: {term: term}
    });
}
var calculoIVA = 0;

function listaPagoRetencion() {
    jQuery("#listPagoreten_mixto").jqGrid({
        datatype: "local",
        colNames: ['', 'ID', 'ID F', 'Forma Pago', 'Tarjeta Credito', 'Num Documento', 'Valor', 'Cuenta Bancos'],
        colModel: [{
                name: 'myac',
                width: 50,
                fixed: true,
                sortable: false,
                resize: false,
                formatter: 'actions',
                formatoptions: {
                    keys: false,
                    delbutton: true,
                    editbutton: false
                }
            },
            {
                name: 'id_f_v_mix',
                index: 'id_f_v_mix',
                editable: false,
                align: 'center',
                width: '180',
                search: false,
                frozen: true,
                hidden: true,
                editoptions: {
                    readonly: 'readonly'
                },
                formoptions: {
                    elmprefix: ""
                }
            },
            {
                name: 'id_factura_venta',
                index: 'id_factura_venta',
                editable: false,
                align: 'center',
                width: '180',
                search: false,
                frozen: true,
                hidden: true,
                editoptions: {
                    readonly: 'readonly'
                },
                formoptions: {
                    elmprefix: ""
                }
            },
            {
                name: 'forma_pago_mixto',
                index: 'forma_pago_mixto',
                editable: false,
                align: 'center',
                width: '180',
                search: false,
                frozen: true,
                editoptions: {
                    readonly: 'readonly'
                },
                formoptions: {
                    elmprefix: ""
                }
            },
            {
                name: 'tarjeta_credito',
                index: 'tarjeta_credito',
                editable: false,
                align: 'center',
                width: '180',
                search: false,
                frozen: true,
                hidden: true,
                editoptions: {
                    readonly: 'readonly'
                },
                formoptions: {
                    elmprefix: ""
                }
            },
            {
                name: 'num_documento',
                index: 'num_documento',
                editable: false,
                align: 'center',
                width: '180',
                search: false,
                frozen: true,
                editoptions: {
                    readonly: 'readonly'
                },
                formoptions: {
                    elmprefix: ""
                }
            },
            {
                name: 'valor',
                index: 'valor',
                editable: false,
                align: 'center',
                width: '180',
                search: false,
                frozen: true,
                editoptions: {
                    readonly: 'readonly'
                },
                formoptions: {
                    elmprefix: ""
                }
            },
            {
                name: 'id_cuenta',
                index: 'id_cuenta',
                editable: false,
                align: 'center',
                width: '180',
                search: false,
                frozen: true,
                hidden: false,
                editoptions: {
                    readonly: 'readonly'
                },
                formoptions: {
                    elmprefix: ""
                }
            },
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        height: 120,
        sortable: true,
        pager: jQuery('#pagerP_reten'),
        sortname: 'id_f_v_mix',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'clientArray',
        shrinkToFit: true,
        delOptions: {
            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {
                var id = jQuery("#listPagoreten_mixto").jqGrid('getGridParam', 'selrow');
                jQuery('#listPagoreten_mixto').jqGrid('restoreRow', id);
                var ret = jQuery("#listPagoreten_mixto").jqGrid('getRowData', id);
                rp_ge.processing = true;
                var su = jQuery("#listPagoreten_mixto").jqGrid('delRowData', rowid);
                var total_venta = 0;
                var valor_restante = 0;
                var valor_total = 0;

                console.log("" + ret.valor);
                if (su === true) {
                    total_venta = (parseFloat($("#cantidad_mixto").val()) - (ret.valor)).toFixed(2);
                    $("#cantidad_mixto").val(total_venta);



                    valor_total = (parseFloat($("#valor_factura").val()) - parseFloat($("#cantidad_mixto").val())).toFixed(2);
                    $("#valor_factura_saldo").val(valor_total);



                }
                $(".ui-icon-closethick").trigger('click');
                return true;
            },
            processing: true
        },
    }).jqGrid('navGrid', '#pagerP_reten', {
        add: false,
        edit: false,
        del: false,
        refresh: false,
        search: true,
        view: true

    });
}
function agregar() {

    if (!!!$("#formaspago_mixto").val()) {
        alertify.error("Seleccióne una forma de pago.");
        $("#formaspago_mixto").focus();
        return;
    }

    $("#validar_guardar_grid").val('1');

    var subtotal_adelanto = 0;
    var subtotal_adelanto1 = 0;
    if ($("#formaspago_mixto").val() == "Credito") {
        $('.nav-tabs a[href="#tab_4"]').tab('show');
        $("#meses").val(1);

        $("#fecha_dias").focus();
        $("#fecha_dias").select();
        subtotal_adelanto = (parseFloat($("#valor_factura").val()) - parseFloat($("#valor_formas").val()));
    }

    $("#adelanto").val(subtotal_adelanto.toFixed(2));
    var subtotal1 = 0;
    var subtotal11 = 0;
    var fil = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
    for (var t = 0; t < fil.length; t++) {
        var dd = fil[t];
        subtotal1 = (parseFloat($("#valor_formas").val()) + (parseFloat(dd['valor'])));
    }

    subtotal11 = (parseFloat($("#valor_formas").val()) + parseFloat($("#cantidad_mixto").val()));
    console.log("DDD" + subtotal11.toFixed(2));
    if (parseFloat(subtotal11.toFixed(2)) > parseFloat($("#valor_factura").val())) {
        alertify.error("Error1.. La suma supera el total de la Factura " + $("#totx").val());
    } else {
        if (parseFloat($("#cantidad_mixto").val()) > parseFloat($("#valor_factura").val())) {
            alertify.error("Error2.. La suma supera el total de la Factura " + $("#totx").val());
        } else {
            if (parseFloat(subtotal1.toFixed(2)) > parseFloat($("#valor_factura").val())) {
                alertify.error("Error3.. La suma supera el total de la Factura " + $("#totx").val());
            } else {
                if (parseFloat($("#valor_formas").val()) > parseFloat($("#valor_factura").val())) {
                    alertify.error("Error4.. La suma supera el total de la Factura " + $("#totx").val());
                } else {

                    if ($("#valor_formas").val() == "") {
                        $("#valor_formas").focus();
                        alertify.error("Error... Ingrese la cantidad");
                    } else {

                        if ($("#formaspago_mixto").val() == 'Credito' && $("#fecha_dias").val() == "") {
                            $("#fecha_dias").focus();
                            alertify.error("Error.. Debe seleccionar Fecha de Vencimiento");
                        } else {

                            if ($("#formaspago_mixto").val() == 'Transferencias' && $("#idCuenta").val() == "") {
                                $("#cuenta_contable").focus();
                                alertify.error("Error.. Debe seleccionar Cuenta contable");
                            } else {
                                if ($("#formaspago_mixto").val() == 'Cheque' && $("#idCuenta").val() == "") {
                                    $("#cuenta_contable").focus();
                                    alertify.error("Error.. Debe seleccionar Cuenta contable");
                                } else {
                                    if ($("#formaspago_mixto").val() == 'TCredito' && $("#idCuenta").val() == "") {
                                        $("#cuenta_contable").focus();
                                        alertify.error("Error.. Debe seleccionar Cuenta contable");
                                    } else if ($("#formaspago_mixto").val() == 'Contado' && $("#idCuenta").val() == "") {
                                        $("#cuenta_contable").focus();
                                        alertify.error("Error.. Debe seleccionar Cuenta contable");
                                    } else {


                                        var filas2 = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
                                        var su;
                                        var count = 0;
                                        var canti = $("#valor_formas").val();
                                        //                    if (filas2.length < canti) {


                                        if (filas2.length == 0) {
                                            //                            alertify.alert("dddd1");
                                            var id_factura_nota = '';

                                            id_factura_nota = $("#comprobante").val();



                                            var datarow = {
                                                id_f_v_mix: count = count + 1,
                                                id_factura_venta: id_factura_nota,
                                                fecha: $("#fecha_actual").val(),
                                                forma_pago_mixto: $("#formaspago_mixto").val(),
                                                tarjeta_credito: $("#tarjetas").val(),
                                                num_documento: $("#num_tarjeta").val(),
                                                valor: $("#valor_formas").val(),
                                                id_cuenta: $("#idCuenta").val()

                                            };

                                            su = jQuery("#listPagoreten_mixto").jqGrid('addRowData', count, datarow);
                                            //                            console.log("dddffd"+filas2.length);
                                            var subtotal = 0;
                                            var sub1 = 0;
                                            var fil = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
                                            for (var t = 0; t < fil.length; t++) {
                                                var dd = fil[t];
                                                subtotal = (subtotal + (parseFloat(dd['valor'])));

                                            }

                                            $("#cantidad_mixto").val(subtotal.toFixed(2));
                                            var subtotal_adelanto1 = (parseFloat($("#valor_factura").val()) - parseFloat($("#cantidad_mixto").val()));

                                            $("#valor_factura_saldo").val(subtotal_adelanto1.toFixed(2));
                                            $("#valor_formas").val("");
                                            $("#tarjetas").val("");
                                            $("#num_tarjeta").val("");
                                            $("#formaspago_mixto").focus();
                                        } else {
                                            count = 1;
                                            var repe = 0;
                                            var fil = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
                                            for (var t = 0; t < fil.length; t++) {
                                                var dd = fil[t];
                                                //                    console.log($("#formaspago_mixto").val());
                                                //                     console.log(dd['forma_pago_mixto']);
                                                if (dd['forma_pago_mixto'] == $("#formaspago_mixto").val()) {
                                                    repe = 1;
                                                }
                                            }

                                            console.log("RRRTRT" + repe);
                                            if (repe == 1) {
                                                alertify.error("FORMA DE PAGO YA EXISTE");
                                            } else {
                                                var id_factura_nota = '';

                                                id_factura_nota = $("#comprobante").val();

                                                datarow = {
                                                    id_f_v_mix: count = count + filas2.length,
                                                    id_factura_venta: id_factura_nota,
                                                    fecha: $("#fecha_actual").val(),
                                                    forma_pago_mixto: $("#formaspago_mixto").val(),
                                                    tarjeta_credito: $("#tarjetas").val(),
                                                    num_documento: $("#num_tarjeta").val(),
                                                    valor: $("#valor_formas").val(),
                                                    id_cuenta: $("#idCuenta").val()
                                                };
                                                su = jQuery("#listPagoreten_mixto").jqGrid('addRowData', count, datarow);
                                                var subtotal = 0;
                                                var sub1 = 0;
                                                var fil = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
                                                for (var t = 0; t < fil.length; t++) {
                                                    var dd = fil[t];
                                                    subtotal = (subtotal + (parseFloat(dd['valor'])));
                                                }
                                                $("#cantidad_mixto").val(subtotal.toFixed(2));
                                                var subtotal_adelanto1 = (parseFloat($("#valor_factura").val()) - parseFloat($("#cantidad_mixto").val()));

                                                $("#valor_factura_saldo").val(subtotal_adelanto1.toFixed(2));
                                                $("#valor_formas").val("");
                                                $("#tarjetas").val("");
                                                $("#num_tarjeta").val("");
                                                $("#formaspago_mixto").focus();

                                            }
                                        }
                                        //                    } else {
                                        //                        $("#serie_campos").val("");
                                        //                        $("#btnAgregar").attr("disabled", "disabled");
                                        //                        alertify.success("Error... Alcanzo el límite máximo");
                                        //                    }

                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

}
function guardar_serie() {

    var tam2 = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
    if ($("#forma_pago").val() == "otros") {
        if ($("#forma_pago").val() == "otros" && $("#valor_factura_saldo").val() != "0.00") {
            alertify.error("Ingrese Valor ");
            $("#valor_formas").focus();
        } else {
            if (tam2.length > 0) {
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

                var fil = jQuery("#listPagoreten_mixto").jqGrid("getRowData");

                for (var i = 0; i < fil.length; i++) {
                    var datos = fil[i];
                    v1[i] = datos['id_f_v_mix'];
                    v2[i] = datos['id_factura_venta'];
                    v3[i] = datos['forma_pago_mixto'];
                    v4[i] = datos['tarjeta_credito'];
                    v5[i] = datos['num_documento'];
                    v6[i] = datos['valor'];
                    v7[i] = datos['id_cuenta'];

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
                var repe = 0;
                var filas = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
                for (var i = 0; i < filas.length; i++) {
                    var id = filas[i];

                    if (id['forma_pago_mixto'] == 'Credito') {
                        repe = 1;
                    }
                }
                if (repe == 1 && $("#fecha_dias").val() == "") {

                    alertify.error("DEBE SELECCIONAR FECHA DE VENCIMIENTO");
                    $("#validar_guardar").val("");

                } else {


                    $.ajax({
                        type: "POST",
                        url: "guardar_forma_mixto.php",
                        data: "id_factura_venta=" + $("#id_factura_venta").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&campo6=" + string_v6 + "&campo7=" + string_v7 + "&comprobante=" + $("#comprobante").val() + "&formaspago_mixto=" + $("#formaspago_mixto").val() + "&tarjetas=" + $("#tarjetas").val() + "&num_tarjeta=" + $("#num_tarjeta").val() + "&fecha_actual=" + $("#fecha_actual").val() + "&fecha_dias=" + $("#fecha_dias").val(),
                        success: function (data) {
                            var val = data;
                            if (val == 1) {
                                guardar_asiento_contable();
                                guardar_cobro_anticipo_proveedor();

                                alertify.success(" Guardado Correctamente");
                                //                                $("#listPagoreten_mixto").jqGrid("clearGridData", true);
                                $("#cantidad_mixto").val() == "";
                                $("#validar_guardar").val('1');
                                $("#btnGuardarRetenciones_mixto").attr("disabled", true);


                            }
                        }
                    });

                }
            }
        }
    }

}

function cambio_mostrar() {

    if (document.getElementById('elegirretencionF2').checked == true) {
        console.log("gg");
        $('#mostrar_retenciones').show();
        $('#serie_sinretencion').hide();

    }
    if (document.getElementById('elegirretencionF1').checked == true) {

        $('#mostrar_retenciones').hide();
        $('#serie_sinretencion').show();


    }
}
function autocompletarsin() {
    var temp = "";
    var serie = $("#serie_sinretencion").val();
    for (var i = serie.length; i < 9; i++) {
        temp = temp + "0";
    }
    return temp;
}
function cambio_ret_fuenteSguia() {

    if ($("#tipo_comprobante").val() == "FACTURA") {

        $('#iva_si').prop('selected', true);

    } else if ($("#tipo_comprobante").val() == "NOTA VENTA") {
        $('#iva_no').prop('selected', true);

    }
}
function guardar_cobro_anticipo_proveedor() {
    var tam2 = jQuery("#listPagoreten_mixto_anti").jqGrid("getRowData");
    if ($("#forma_pago").val() == "otros") {
        if ($("#forma_pago").val() == "otros" && $("#valor_factura_saldo").val() != "0.00") {
            alertify.error("Ingrese Valor ");
            $("#valor_formas").focus();
        } else {
            if (tam2.length > 0) {
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

                var fil = jQuery("#listPagoreten_mixto_anti").jqGrid("getRowData");

                for (var i = 0; i < fil.length; i++) {
                    var datos = fil[i];
                    v1[i] = datos['id_cobro_anticipo'];
                    v2[i] = datos['id_anticipo_proveedores'];
                    v3[i] = datos['id_factura_venta'];
                    v4[i] = datos['id_cliente'];
                    v5[i] = datos['forma_pago'];
                    v6[i] = datos['comprobante'];
                    v7[i] = datos['monto'];

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

                console.log("cobro_anticipo");

                $.ajax({
                    type: "POST",
                    url: "guardar_cobro_anticipo_pro.php",
                    data: "id_factura_compra=" + $("#id_factura_compra").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&campo6=" + string_v6 + "&campo7=" + string_v7 + "&fecha_actual=" + $("#fecha_actual").val(),
                    success: function (data) {
                        var val = data;
                        if (val == 1) {
                            alertify.success(" Guardado Correctamente");
                            $("#listPagoreten_mixto_anti").jqGrid("clearGridData", true);
                            //                                $("#cantidad_mixto").val() == "";
                            //                                $("#validar_guardar").val('1');
                            //                                $("#btnGuardarRetenciones_mixto").attr("disabled", true);
                        }
                    }
                });

            }
        }
    }

}

function limpiar_campos_mixto() {
    $("#adelanto").val("0.00");
    $("#meses").val("");
    $("#valor_formas").val("");
    $("#num_tarjeta").val("");
    $("#listPagoreten_mixto").jqGrid("clearGridData", true);
    $("#cantidad_mixto").val() == "";
    $("#validar_guardar").val("");
    $("#btnGuardarRetenciones_mixto").attr("disabled", true);
    $("#cantidad_mixto").val("");
}
function inicio() {

    serie_inicial_reten();
    llenarCentrosCosto();

    $('#grid_container_pago_reten_anti').hide();
    $("#buscar_anticipo").dialog(dialogo22);

    $("#tipo_comprobante").on("change", cambio_ret_fuenteSguia);



    $('#serie_sinretencion').hide();
    $("#elegirretencionF1").on("change", cambio_mostrar);
    $("#elegirretencionF2").on("change", cambio_mostrar);
    $('#mostrar_retenciones').show();
    ///////////SIN RETENCION////////////
    if ($("#num_oculto_sinreten").val() == "") {
        $("#serie_sinretencion").val("");
    } else {
        var str = $("#num_oculto_sinreten").val();
        var res = parseInt(str.substr(8, 16));
        res = res + 1;

        $("#serie_sinretencion").val(res);
        var a = autocompletarsin(res);
        var validado = a + "" + res;
        $("#serie_sinretencion").val(validado);
    }

    var num_retencion = ("001" + "-" + "001" + "-" + $("#serie_retencion").val());
    $("#serie_retencion").change(function () {
        $.ajax({
            type: "POST",
            url: "../registro_gastos/comparar_num_retencion.php",
            data: "num_reten=" + num_serie_ret + "-" + $("#serie_retencion").val(),
            success: function (data) {
                var val = data;
                if (val != 0) {

                    $("#serie_retencion").focus();
                    alertify.error("Error... El numero de retencion ya ésta registrado");
                }

            }
        });
    });
    $("#btnCancelarRetenciones_mixto").click(function (e) {
        e.preventDefault();
        alertify.confirm("¿Esta Seguro?",
                function (e) {
                    if (e) {
                        $('.nav-tabs a[href="#tab_1"]').tab('show')
                        $('#contado_form').prop('selected', true);
                        limpiar_campos_mixto();
                        //                        guardar_retenciones_factura_compra_g();

                    } else {
                    }
                }
        );
    });
    $('#fecha_vencimiento').hide();
    $("#formaspago_mixto").on("change", function () {
        if ($("#formaspago_mixto").val() == "Credito") {
            $("#valor_formas").attr("disabled", false);
            $("#cuenta_contable").attr("disabled", true);
            $('#fecha_vencimiento').show();
            $("#btnCuenta").attr("disabled", true);
            $("#cuenta_contable").val("");
            $("#idCuenta").val("");
            //            $("#cheque_tarjeta").attr("disabled", false);
            //            $("#banco").attr("disabled", false);
            var tam2 = jQuery("#listPagoreten_mixto_anti").jqGrid("getRowData");
            if (tam2.length == 0) {
                $('#grid_container_pago_reten_anti').hide();
            }
        } else if ($("#formaspago_mixto").val() == "Transferencias" || $("#formaspago_mixto").val() == "Cheque" || $("#formaspago_mixto").val() == "TCredito") {
            $("#valor_formas").attr("disabled", false);
            cargar_cuentas();
            $("#cuenta_contable").attr("disabled", false);
            $("#btnCuenta").attr("disabled", false);
            $("#cuenta_contable").val("");
            $("#idCuenta").val("");
            $('#fecha_vencimiento').hide();
            //            $("#cheque_tarjeta").attr("disabled", true);
            //            $("#banco").attr("disabled", true);
            var tam2 = jQuery("#listPagoreten_mixto_anti").jqGrid("getRowData");

            if (tam2.length == 0) {

                $('#grid_container_pago_reten_anti').hide();

            }
        } else if ($("#formaspago_mixto").val() == "Contado") {
            $("#valor_formas").attr("disabled", false);
            var tam2 = jQuery("#listPagoreten_mixto_anti").jqGrid("getRowData");
            if (tam2.length == 0) {
                $('#grid_container_pago_reten_anti').hide();
            }
            /*  $("#cuenta_contable").attr("disabled", true);
             $("#btnCuenta").attr("disabled", true);
             $("#cuenta_contable").val("");
             $("#idCuenta").val("");
             $('#fecha_vencimiento').hide(); */

            cargar_cuentas();
            $("#cuenta_contable").attr("disabled", false);
            $("#btnCuenta").attr("disabled", false);
            $("#cuenta_contable").val("");
            $("#idCuenta").val("");
            $('#fecha_vencimiento').hide();


            //            $("#cheque_tarjeta").attr("disabled", true);
            //            $("#banco").attr("disabled", true); cargar_cuentas();

        } else if ($("#formaspago_mixto").val() == "pagoiess") {
            $("#valor_formas").attr("disabled", false);
            /*  $("#cuenta_contable").attr("disabled", true);
             $("#btnCuenta").attr("disabled", true);
             $("#cuenta_contable").val("");
             $("#idCuenta").val("");
             $('#fecha_vencimiento').hide(); */
            //
            //            cargar_cuentas();
            $("#cuenta_contable").attr("disabled", true);
            $("#btnCuenta").attr("disabled", true);
            $("#cuenta_contable").val("APORTE PERSONAL IESS POR PAGAR");
            $("#idCuenta").val("81");
            $('#fecha_vencimiento').hide();


            //            $("#cheque_tarjeta").attr("disabled", true);
            //            $("#banco").attr("disabled", true); cargar_cuentas();

        } else if ($("#formaspago_mixto").val() == "anticipo_proveedores") {
            $("#cuenta_contable").attr("disabled", true);
            $("#btnCuenta").attr("disabled", true);
            $("#cuenta_contable").val("");
            $("#idCuenta").val("");
            $('#fecha_vencimiento').hide();
            $("#valor_formas").attr("disabled", true);

            $("#list22").jqGrid('setGridParam', {
                url: 'xmlFacturas_venta.php?id_proveedor=' + $("#id_proveedor").val(),
                datatype: 'xml'
            }).trigger('reloadGrid');
            $("#buscar_anticipo").dialog("open");
            $('#grid_container_pago_reten_anti').show();

        }
    })
    $("#tab_4").prop("disabled", true);
    $("#forma_pago").change(function () {
        var tam2 = jQuery("#list").jqGrid("getRowData");
        if ($("#forma_pago").val() == "Contado") {
            $("#adelanto").attr("disabled", "disabled");
            $("#adelanto").val("");
            $("#valor_factura").val("");
            $("#meses").attr("disabled", "disabled");
            $("#meses").val("");
            $("#cuotas").attr("disabled", "disabled");
            $('#cuotas').children().remove().end();
        } else {
            if ($("#forma_pago").val() == "otros") {
                if (tam2.length > 0) {
                    $('.nav-tabs a[href="#tab_4"]').tab('show')
                    $("#formaspago_mixto").attr("disabled", false);
                } else {
                    $('#contado_form').prop('selected', true);
                    alertify.error("Ingrese Productos");
                }
            }
        }
    });
    listaPagoRetencion();
    $("#btnAgregar_mixto").click(function (e) {
        e.preventDefault();
        agregar();
    });
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




    $("#dialog_form_cliente").dialog({
        modal: true,
        width: window.innerWidth - 180,
        height: window.innerHeight - 150,
        minHeight: 600,
        minHeight: 700,
        autoOpen: false,
        title: "REGISTRAR PROVEEDORES"
    });
    addCliente();

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



    $("#forma_pago").on("change", function () {
        if ($("#forma_pago").val() == "CHEQUE") {
            $("#cheque_tarjeta").attr("disabled", false);
            $("#banco").attr("disabled", false);
            $("#deposito").attr("disabled", false);
            $("#cuentanum").attr("disabled", false);
        } else {
            if ($("#forma_pago").val() == "EFECTIVO") {
                $("#cheque_tarjeta").attr("disabled", true);
                $("#banco").attr("disabled", true);
                $("#deposito").attr("disabled", true);
                $("#cuentanum").attr("disabled", true);
            } else {
                if ($("#forma_pago").val() == "TRANSFERENCIA") {
                    $("#cheque_tarjeta").attr("disabled", false);
                    $("#banco").attr("disabled", false);
                    $("#deposito").attr("disabled", false);
                    $("#cuentanum").attr("disabled", false);
                } else {

                    if ($("#forma_pago").val() == "Credito") {
                        if (tam2.length > 0) {
                            $("#cheque_tarjeta").attr("disabled", true);
                            $("#banco").attr("disabled", true);
                            $("#deposito").attr("disabled", true);
                            $("#cuentanum").attr("disabled", true);

                        } else {
                            alertify.alert("Error...Ingrese un monto a la factura");
                            $("#forma_pago option[value=" + 'EFECTIVO' + "]").attr("selected", true);
                        }
                    }
                }
            }
        }
    })


    buscar_servicio_producto();
    buscar_bienservicio_producto();
    buscar_servicio_iva();
    buscar_bienservicio_producto_iva();
    if ($("#num_oculto").val() == "") {
        $("#serie_retencion").val("000000001");
    } else {
        var str = $("#num_oculto").val();
        var res = parseInt(str.substr(8, 16));
        res = res + 1;

        $("#serie_retencion").val(res);
        var a = autocompletar(res);
        var validado = a + "" + res;
        $("#serie_retencion").val(validado);
    }
    $("[data-mask]").inputmask();
    alertify.set({
        delay: 1000
    });

    $.ajax({
        type: "POST",
        url: "extraer_cuenta_producto.php",
        data: "id_gasto=" + $("#id_gasto").val(),
        success: function (data) {
            var val = data;
            if (val != "") {
                var vec = val.split("/");
                $("#idCuenta").val(vec[0]);
                $("#cuenta_contable").val(vec[1] + "  -  " + vec[2]);
            }
        }
    });

    $.ajax({
        type: "POST",
        url: "buscar_iva.php",
        data: "",
        success: function (data) {
            var val = data;
            if (val != 1) {
                calculoIVA = val;
            }
        }
    });

    $("#subtotal").keyup(function () {

        if ($("#subtotal").val() == "") {
            $("#iva").val("");
            $("#valor").val("");

        }

    });
    show();

    $("#btnGuardarRetenciones").click(function (e) {
        e.preventDefault();
    });

    $("#btnAnadirForma").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardar").click(function (e) {
        e.preventDefault();
    });
    $("#btnImprimir").click(function () {
        $.ajax({
            type: "POST",
            url: "buscar_retencion.php",
            data: "comprobante=" + $("#comprobante").val(),
            success: function (data) {
                var val = data;
                console.log(val);
                //                if (val != "") {
                //                    window.open("generarPdfRetenGAS_impri.php?hoja=A4&id=" + $("#comprobante").val(), '_blank');
                //                } else {
                window.open("../../reportes/reporte_registo_gasto.php?hoja=A5&id=" + $("#comprobante").val(), '_blank');
                window.open("../../reportes/transacciones_1.php?hoja=A5&id=" + $("#comprobante").val() + '&gas=' + 'GAS', '_blank');
                //                }
            }
        });


    });
    $("#btnNuevo").click(function (e) {
        e.preventDefault();
    });
    $("#btnEliminar").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardarRetenciones_mixto").click(function (e) {
        e.preventDefault();
    });
    $("#btnSalir").click(function (e) {
        e.preventDefault();
    });
    $("#btnAcceder").click(function (e) {
        e.preventDefault();
    });
    $("#btnCancelar").click(function (e) {
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

    $("#buscar_gastos").dialog(dialogo2);

    $("#btnBuscar").click(function () {
        $("#buscar_gastos").dialog("open");
    });
    $("#btnCuenta").click(function (e) {
        e.preventDefault();
    });
    $("#btnCuenta").on("click", abrirCuenta);

    $("#cuentas").dialog(dialogo_cuenta);
    $("#valor").keypress(function (e) {
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
    });
    $("#btnNuevo").on("click", nuevo_gasto);
    $("#btnGuardar").on("click", guardar_gasto);
    $("#btnAtras").on("click", flecha_atras);
    $("#btnAdelante").on("click", flecha_siguiente);
    $("#btnAnadirForma").on("click", agregarForma);
    $("#formasPago").on("change", cambioForma);
    $("#subtotal").on("keypress", enter);
    $("#btnGuardarRetenciones").on("click", guardar_retenciones_factura_compra);
    $("#btnGuardarRetenciones_mixto").on("click", guardar_serie);
    $("#btnAcceder").on("click", validar_acceso);
    $("#btnAceptar").on("click", aceptarEliminar);
    $("#btnModificar").on("click", modificar_factura);
    $("#retencionF1").on("change", cambio_ret_fuente);
    $("#retencionF2").on("change", cambio_ret_fuente);
    $("#retencionF1S").on("change", cambio_ret_fuenteS);
    $("#retencionF2S").on("change", cambio_ret_fuenteS);
    $("#retencionI1").on("change", cambio_ret_iva);
    $("#retencionI2").on("change", cambio_ret_iva);
    $("#retencionI1s").on("change", cambio_ret_ivas);
    $("#retencionI2s").on("change", cambio_ret_ivas);
    $("#tipoRetencionesF").on("change", buscar_bienservicio_producto);
    $("#tipoRetencionesI").on("change", buscar_bienservicio_producto_iva);
    $("#btnEliminar").on("click", eliminar_factura);
    $("#tipoRetencionesF").on("change", calculo_ret_fuente);
    $("#tipoRetencionesI").on("change", calculo_ret_iva);
    $("#tipoRetencionesIs").on("change", calculo_ret_ivas);
    //    $("#cantidad").on("keypress", entercantidad);
    $("#concepto").on("keypress", entercantidad);
    $("#codigo_plan").on("keypress", enter3);
    $("#valor").on("keypress", punto);
    $("#valor").on("keypress", enter2);


    $("#tipoRetencionesFS").on("change", calculo_ret_fuenteS);
    $("#buscar_estados").dialog(dialogo10);

    $("#calculoRetencionF").on("keypress", enter7);
    $("#calculoRetencionFS").on("keypress", enter7);
    $("#calculoRetencionI").on("keypress", enter7);
    $("#calculoRetencionIs").on("keypress", enter7);

    $("#serie_retencion").on("keypress", enter6);

    $("#descuento").validCampoFranz("0123456789");
    $("#autorizacion").validCampoFranz("0123456789");
    $("#cuentas").dialog(dialogo_cuenta);
    $("#buscar_estados").dialog(dialogo10);
    $("#clave_permiso").dialog(dialogo12);
    $("#seguro").dialog(dialogo14);

    $("#tipo_docu").change(function () {
        var tipo = $("#tipo_docu").val();
        if (tipo == "Cedula") {
            $("#ruc_ci").validCampoFranz("0123456789");
            $("#ruc_ci").removeAttr("disabled");
            $("#ruc_ci").attr("maxlength", "10");
            $("#ruc_ci").autocomplete({
                source: "buscar_empresa.php?tipo_docu=" + tipo,
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
            //document.getElementById("tipo_comprobante").selectedIndex=0;
            $("#ruc_ci").val("");
            $("#empresa").val("");
            $("#id_proveedor").val("");
        } else {
            if (tipo == "Ruc") {
                $("#ruc_ci").validCampoFranz("0123456789");
                $("#ruc_ci").removeAttr("disabled");
                $("#ruc_ci").removeAttr("maxlength");
                $("#ruc_ci").attr("maxlength", "13");
                $("#ruc_ci").autocomplete({
                    source: "buscar_empresa.php?tipo_docu=" + tipo,
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

                $("#ruc_ci").val("");
                $("#empresa").val("");
                $("#id_proveedor").val("");
            } else {
                if (tipo == "Pasaporte") {
                    $("#ruc_ci").unbind("keypress");
                    $("#ruc_ci").removeAttr("disabled");
                    $("#ruc_ci").attr("maxlength", "30");
                    $("#ruc_ci").autocomplete({
                        source: "buscar_empresa.php?tipo_docu=" + tipo,
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

                    $("#ruc_ci").val("");
                    $("#empresa").val("");
                    $("#id_proveedor").val("");
                }
            }
        }
    });
    // buscar producto articulo
    //    $("#producto").autocomplete({
    //        source: "buscar_producto.php",
    //        minLength: 1,
    //        focus: function (event, ui) {
    //            $("#codigo_barras").val(ui.item.codigo_barras);
    //            $("#producto").val(ui.item.value);
    //            $("#codigo").val(ui.item.codigo);
    //            $("#precio").val(ui.item.precio);
    //            $("#iva_producto").val(ui.item.iva_producto);
    //            $("#carga_series").val(ui.item.carga_series);
    //            $("#cod_producto").val(ui.item.cod_producto);
    //            $("#incluye").val(ui.item.incluye);
    //            return false;
    //        },
    //        select: function (event, ui) {
    //            $("#codigo_barras").val(ui.item.codigo_barras);
    //            $("#producto").val(ui.item.value);
    //            $("#codigo").val(ui.item.codigo);
    //            $("#precio").val(ui.item.precio);
    //            $("#iva_producto").val(ui.item.iva_producto);
    //            $("#carga_series").val(ui.item.carga_series);
    //            $("#cod_producto").val(ui.item.cod_producto);
    //            $("#incluye").val(ui.item.incluye);
    //            return false;
    //        }
    //
    //    }).data("ui-autocomplete")._renderItem = function (ul, item) {
    //        return $("<li>")
    //                .append("<a>" + item.value + "</a>")
    //                .appendTo(ul);
    //    };
    // fin
    ///////////calendarios////////////
    /* $("#fecha_actual").datepicker({
     dateFormat: 'yy-mm-dd'
     }).datepicker('setDate', 'today'); */
    $('#fecha_actual').val(new Date().toLocaleDateString("fr-CA"));

    // $("#fecha_emision").datepicker({
    //    dateFormat: 'yy-mm-dd'
    // }).datepicker('setDate', 'today');
    // datos tabla



    jQuery("#listPagoreten_mixto_anti").jqGrid({
        datatype: "local",
        colNames: ['', 'ID', 'ID anti', 'id factura venta', 'id cliente', 'Forma Pago', 'Comprobante', 'Valor Anticipo'],
        colModel: [{
                name: 'myac',
                width: 50,
                fixed: true,
                sortable: false,
                resize: false,
                formatter: 'actions',
                formatoptions: {
                    keys: false,
                    delbutton: true,
                    editbutton: false
                }
            },
            {
                name: 'id_cobro_anticipo',
                index: 'id_cobro_anticipo',
                editable: false,
                align: 'center',
                width: '80',
                search: false,
                frozen: true,
                hidden: true,
                editoptions: {
                    readonly: 'readonly'
                },
                formoptions: {
                    elmprefix: ""
                }
            },
            {
                name: 'id_anticipo_proveedores',
                index: 'id_anticipo_proveedores',
                editable: false,
                align: 'center',
                width: '80',
                search: false,
                frozen: true,
                hidden: true,
                editoptions: {
                    readonly: 'readonly'
                },
                formoptions: {
                    elmprefix: ""
                }
            },
            {
                name: 'id_factura_venta',
                index: 'id_factura_venta',
                editable: false,
                align: 'center',
                width: '80',
                search: false,
                frozen: true,
                hidden: true,
                editoptions: {
                    readonly: 'readonly'
                },
                formoptions: {
                    elmprefix: ""
                }
            },
            {
                name: 'id_cliente',
                index: 'id_cliente',
                editable: false,
                align: 'center',
                width: '80',
                search: false,
                frozen: true,
                hidden: true,
                editoptions: {
                    readonly: 'readonly'
                },
                formoptions: {
                    elmprefix: ""
                }
            },
            {
                name: 'forma_pago',
                index: 'forma_pago',
                editable: false,
                align: 'center',
                width: '120',
                search: false,
                frozen: true,
                hidden: false,
                editoptions: {
                    readonly: 'readonly'
                },
                formoptions: {
                    elmprefix: ""
                }
            },
            {
                name: 'comprobante',
                index: 'comprobante',
                editable: false,
                align: 'center',
                width: '120',
                search: false,
                frozen: true,
                hidden: false,
                editoptions: {
                    readonly: 'readonly'
                },
                formoptions: {
                    elmprefix: ""
                }
            },
            {
                name: 'monto',
                index: 'monto',
                editable: false,
                align: 'center',
                width: '120',
                search: false,
                frozen: true,
                hidden: false,
                editoptions: {
                    readonly: 'readonly'
                },
                formoptions: {
                    elmprefix: ""
                }
            },
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        height: 120,
        sortable: true,
        pager: jQuery('#pagerP_reten_anti'),
        sortname: 'id_cobro_anticipo',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'clientArray',
        shrinkToFit: true,
        delOptions: {
            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {
                var id = jQuery("#listPagoreten_mixto_anti").jqGrid('getGridParam', 'selrow');
                jQuery('#listPagoreten_mixto_anti').jqGrid('restoreRow', id);
                var ret = jQuery("#listPagoreten_mixto_anti").jqGrid('getRowData', id);
                rp_ge.processing = true;
                var su = jQuery("#listPagoreten_mixto_anti").jqGrid('delRowData', rowid);
                var total_venta = 0;
                var valor_formas = 0;
                var valor_restante = 0;
                var valor_total = 0;

                if (su === true) {

                    valor_formas = (parseFloat($("#valor_formas").val()) - (ret.monto)).toFixed(2);
                    $("#valor_formas").val(valor_formas);



                }
                $(".ui-icon-closethick").trigger('click');
                return true;
            },
            processing: true
        },

    }).jqGrid('navGrid', '#pagerP_reten_anti', {
        add: false,
        edit: false,
        del: false,
        refresh: false,
        search: true,
        view: true

    });




    jQuery("#listPagoreten").jqGrid({
        datatype: "local",
        colNames: ['', 'Base Imponible', 'Impuesto', '% Retenciòn ', 'Valor Retenido ', 'Id_retenciones '],
        colModel: [{
                name: 'myac',
                width: 50,
                fixed: true,
                sortable: false,
                resize: false,
                formatter: 'actions',
                formatoptions: {
                    keys: false,
                    delbutton: true,
                    editbutton: false
                }
            },
            {
                name: 'base_imponible',
                index: 'base_imponible',
                editable: true,
                align: 'center',
                width: '180',
                search: false,
                frozen: true,
                editoptions: {
                    readonly: 'readonly'
                },
                formoptions: {
                    elmprefix: ""
                }
            },
            {
                name: 'impuesto',
                index: 'impuesto',
                editable: true,
                align: 'center',
                width: '180',
                search: false,
                frozen: true,
                editoptions: {
                    readonly: 'readonly'
                },
                formoptions: {
                    elmprefix: ""
                }
            },
            {
                name: 'porcent_reten',
                index: 'porcent_reten',
                editable: true,
                align: 'center',
                width: '180',
                search: true,
                frozen: true,
                formoptions: {
                    elmsuffix: " (*)"
                },
                editrules: {
                    required: true
                }
            },
            {
                name: 'valor_retenido',
                index: 'valor_retenido',
                editable: true,
                align: 'center',
                width: '180',
                search: true,
                frozen: true,
                formoptions: {
                    elmsuffix: " (*)"
                },
                editrules: {
                    required: true
                }
            },
            {
                name: 'id_retenciones_ser',
                index: 'id_retenciones_ser',
                hidden: true,
                editable: true,
                align: 'center',
                width: '180',
                search: true,
                frozen: true,
                formoptions: {
                    elmsuffix: " (*)"
                },
                editrules: {
                    required: true
                }
            }

        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        height: 100,
        pager: jQuery('#pagerP_reten'),
        sortname: 'base_imponible',
        shrinkToFit: true,
        sortorder: 'asc',
        caption: 'Datos',
        viewrecords: true,
        delOptions: {
            onclickSubmit: function (rp_ge, rowid) {
                rp_ge.processing = true;
                var su = jQuery("#listPagoreten").jqGrid('delRowData', rowid);
                var e = "";
                var nuevo = "";

                var ob = "";
                var valor = "";
                if (rowid.length == 1) {
                    e = "0" + rowid;
                } else {
                    e = rowid;
                }
                var subtotal = 0;
                var fil = jQuery("#listPagoreten").jqGrid("getRowData");
                for (var t = 0; t < fil.length; t++) {
                    var dd = fil[t];

                    subtotal = (subtotal + parseFloat(dd['valor_retenido']));


                }

                $("#total_retencion").val(subtotal.toFixed(2));
                //$(".ui-icon-closethick").trigger('click');
            },
            processing: true
        }
    }).jqGrid('navGrid', '#pagerP_reten', {
        add: false,
        edit: false,
        del: false,
        refresh: true,
        search: true,
        view: true
    });
    //jQuery("#listPago").setGridWidth($('#pagerP').width());

    //////////busqueda facturas////////
    jQuery("#list22").jqGrid({
        url: 'xmlFacturas_venta.php',
        datatype: 'xml',
        colNames: ['ID', 'Num Docu', 'Fecha Registro', 'Forma Pago', 'Observacion', 'Monto'],
        colModel: [
            {
                name: 'ids', index: 'ids', editable: false, search: false, hidden: true, editrules: {edithidden: false}, align: 'center',
                frozen: true, width: 50
            },
            {
                name: 'num_documento', index: 'num_documento', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',
                frozen: true, width: 180
            },
            {name: 'fecha_actual', index: 'fecha_actual', editable: false, frozen: true, hidden: false, editrules: {required: true}, align: 'center', width: 250},
            {name: 'forma_pago', index: 'forma_pago', editable: true, frozen: true, hidden: false, editrules: {required: true}, align: 'center', width: 180},
            {name: 'observacion', index: 'observacion', editable: true, search: false, frozen: true, hidden: false, editrules: {required: true}, align: 'center', width: 200},
            {name: 'monto', index: 'monto', editable: true, frozen: true, hidden: false, editrules: {required: true}, align: 'center', width: 120},
        ],
        rowNum: 10,
        width: 500,
        rowList: [10, 20, 30],
        pager: jQuery('#pager22'),
        shrinkToFit: true,
        sortorder: 'asc',
        caption: 'Lista de Anticipos',
        viewrecords: true,
        ondblClickRow: function (rowid) {
            var id = jQuery("#list22").jqGrid('getGridParam', 'selrow');
            jQuery('#list22').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list22").jqGrid('getRowData', id);
                var count = 0;



                var repe = 0;
                var fil = jQuery("#listPagoreten_mixto_anti").jqGrid("getRowData");
                for (var t = 0; t < fil.length; t++) {
                    var dd = fil[t];
                    //                    console.log($("#formaspago_mixto").val());
                    //                     console.log(dd['forma_pago_mixto']);
                    if (dd['forma_pago'] == ret.forma_pago) {
                        repe = 1;
                    }
                }
                $("#cuenta_contable").val("ANTICIPO PROVEEDORES");
                $("#idCuenta").val("34");
                console.log("RRRTRT" + repe);
                if (repe == 1) {
                    alertify.error("FORMA DE PAGO YA EXISTE");
                } else {
                    var datarow = {
                        id_cobro_anticipo: count = count + 1,
                        id_anticipo_proveedores: ret.ids,
                        id_factura_venta: $("#comprobante").val(),
                        id_cliente: $("#id_cliente").val(),
                        forma_pago: ret.forma_pago,
                        comprobante: ret.num_documento,
                        monto: ret.monto

                    };
                }

                var su = jQuery("#listPagoreten_mixto_anti").jqGrid('addRowData', count, datarow);

                var subtotal = 0;
                var sub1 = 0;
                var fil = jQuery("#listPagoreten_mixto_anti").jqGrid("getRowData");
                for (var t = 0; t < fil.length; t++) {
                    var dd = fil[t];
                    subtotal = (subtotal + (parseFloat(dd['monto'])));
                }
                $("#valor_formas").val(subtotal.toFixed(2));
                $("#buscar_anticipo").dialog("close");

                // $("#list").jqGrid("clearGridData", true);
            } else {
                alertify.alert("Seleccione ");
            }
        }
    }).jqGrid('navGrid', '#pager22', {
        add: false,
        edit: false,
        del: false,
        refresh: true,
        search: false,
        view: true
    });

    $(window).bind('resize', function () {
        jQuery("#list22").setGridWidth($('#pager22').width());
    }).trigger('reloadGrid');
    //    jQuery("#list22").jqGrid('navButtonAdd', '#pager22', {caption: "Añadir",
    //        onClickButton: function () {
    //            var id = jQuery("#list22").jqGrid('getGridParam', 'selrow');
    //            jQuery('#list22').jqGrid('restoreRow', id);
    //            if (id) {
    //                var ret = jQuery("#list22").jqGrid('getRowData', id);
    //
    //                $("#ids").val(ret.ids);
    //                $("#num_factura").val(ret.num_documento);
    //                $("#tipo_factura").val(ret.fecha_actual);
    //                $("#fecha_factura").val(ret.forma_pago);
    //                $("#totalcxc").val(ret.observacion);
    //                $("#saldo2").val(ret.monto);
    //
    //                //////////////////////
    //                $("#buscar_anticipos").dialog("close");
    //
    //            } else {
    //                alertify.alert("Seleccione una Cuenta");
    //            }
    //        }
    //    });


    jQuery("#list").jqGrid({
        datatype: "local",
        colNames: ['', 'Concepto', 'Id Plan', 'Cuenta Contable', 'Iva', 'Centro Costo', 'Valor', 'B/S', 'C. Costo', 'id_c_costo'],
        colModel: [
            {name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: {keys: false, delbutton: true, editbutton: false}},
            {name: 'concepto', index: 'concepto', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 120},
            {name: 'id_plan', index: 'id_plan', editable: false, search: false, hidden: true, editrules: {edithidden: false}, align: 'center', frozen: true, width: 150},
            {name: 'cuenta_contable', index: 'cuenta_contable', editable: false, frozen: true, editrules: {required: true}, align: 'center', width: 150},
            {
                name: 'iva', index: 'iva', editable: false, frozen: true, editrules: {required: true}, align: 'center', width: 20, editoptions: {
                    maxlength: 10, size: 15, dataInit: function (elem) {

                    }
                }
            },
            {
                name: 'centro_costo', index: 'centro_costo', hidden: true, editable: false, search: false, frozen: true, editrules: {required: true}, align: 'center', width: 110, editoptions: {
                    maxlength: 10, size: 15, dataInit: function (elem) {

                    }, hidden: true
                }
            },
            {name: 'valor', index: 'valor', hidden: false, editable: false, frozen: true, editrules: {required: true}, align: 'center', width: 30},
            {name: 'bien_servicio', index: 'bien_servicio', hidden: false, editable: false, frozen: true, editrules: {required: true}, align: 'center', width: 30},
            {
                name: "centro_costo_1", index: "centro_costo_1", search: false, frozen: true, width: 30
            },
            {
                name: "id_centro_costo", index: "id_centro_costo", search: false, frozen: true, hidden: true, width: 30
            }
        ],
        rowNum: 30,
        height: 300,
        width: 1270,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager'),
        sortname: 'concepto',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'clientArray',
        shrinkToFit: true,
        delOptions: {
            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {

                prodFactSelConcepto = prodFactSelConcepto.filter(el => el.idConcepto != rowid);
                var id = jQuery("#list").jqGrid('getGridParam', 'selrow');
                jQuery('#list').jqGrid('restoreRow', id);
                var ret = jQuery("#list").jqGrid('getRowData', id);
                var subtotal0 = 0;
                var subtotal12 = 0;
                var subtotal_total = 0;
                var iva12 = 0;
                var total_total = 0;
                var descu_total = 0;

                var subtotal = 0;
                var sub = 0;
                var sub1 = 0;
                var sub2 = 0;
                var iva = 0;
                var iva1 = 0;
                var iva2 = 0;


                var fil = jQuery("#list").jqGrid("getRowData");
                for (var t = 0; t < fil.length; t++) {
                    if (ret.iva == "Si") {
                        // if(ret.incluye == "No") {
                        console.log("44");
                        subtotal = ret.valor;
                        sub1 = subtotal;

                        iva1 = sub1 * toFixedDown((calculoIVA / 100), 3);

                        subtotal0 = parseFloat($("#total_p").val()) + 0;
                        subtotal12 = parseFloat($("#total_p2").val()) - parseFloat(sub1);
                        subtotal_total = parseFloat($("#sub").val()) - parseFloat(sub1);
                        iva12 = parseFloat($("#iva").val()) - parseFloat(iva1);
                        //                        descu_total = parseFloat($("#desc").val()) - parseFloat(ret.cal_des);

                        subtotal0 = parseFloat(subtotal0);
                        subtotal12 = parseFloat(subtotal12);
                        subtotal_total = parseFloat(subtotal_total);
                        iva12 = parseFloat(iva12);
                        //                        descu_total = parseFloat(descu_total);

                        // } else {
                        //       if(ret.incluye == "Si") {
                        //         subtotal = ret.total;
                        //         sub2 = (subtotal / 1.12).toFixed(3);
                        //         iva2 = (sub2 * 0.12).toFixed(3);

                        //         subtotal0 = parseFloat($("#total_p").val()) + 0;
                        //         subtotal12 = parseFloat($("#total_p2").val()) - parseFloat(sub2);
                        //         subtotal_total = parseFloat($("#sub").val()) - parseFloat(sub2);
                        //         iva12 = parseFloat($("#iva").val()) - parseFloat(iva2);
                        //         descu_total = parseFloat($("#desc").val()) - parseFloat(ret.cal_des);

                        //         subtotal0 = parseFloat(subtotal0).toFixed(3);
                        //         subtotal12 = parseFloat(subtotal12).toFixed(3);
                        //         subtotal_total = parseFloat(subtotal_total).toFixed(3);
                        //         iva12 = parseFloat(iva12).toFixed(3);
                        //         descu_total = parseFloat(descu_total).toFixed(3);
                        //       }
                        //   }
                    } else {
                        if (ret.iva == "No") {
                            subtotal = ret.valor;
                            sub = subtotal;

                            subtotal0 = parseFloat($("#total_p").val()) - parseFloat(sub);
                            subtotal12 = parseFloat($("#total_p2").val()) + 0;
                            subtotal_total = parseFloat($("#sub").val()) - parseFloat(sub);
                            iva12 = parseFloat($("#iva").val()) + 0;
                            //                            descu_total = parseFloat($("#desc").val()) - parseFloat(ret.cal_des);

                            subtotal0 = parseFloat(subtotal0);
                            subtotal12 = parseFloat(subtotal12);
                            subtotal_total = parseFloat(subtotal_total);
                            iva12 = parseFloat(iva12);
                            //                            descu_total = parseFloat(descu_total);
                        }
                    }
                }

                total_total = parseFloat(total_total) + (parseFloat(subtotal0) + parseFloat(subtotal12) + parseFloat(iva12));
                total_total = parseFloat(total_total);

                $("#total_p").val(subtotal0);
                $("#total_p2").val(subtotal12);
                $("#sub").val(subtotal_total);
                $("#iva").val(iva12);
                $("#desc").val(descu_total);
                $("#tot").val(total_total.toFixed(2));
                $("#total_px").val(subtotal0.toFixed(2));
                $("#total_p2x").val(subtotal12.toFixed(2));
                $("#subx").val(subtotal_total.toFixed(2));
                $("#ivax").val(iva12.toFixed(2));
                $("#descx").val(descu_total.toFixed(2));
                $("#totx").val(total_total.toFixed(2));
                $("#valor_factura").val(total_total.toFixed(2));


                var su = jQuery("#list").jqGrid('delRowData', rowid);
                if (su == true) {
                    rp_ge.processing = true;
                    $(".ui-icon-closethick").trigger('click');
                }
                return true;
            },
            processing: true
        },
        afterSaveCell: function (rowid, name, val, iRow, iCol) {
            var subtotal = 0;
            var iva = 0;
            var t_fc = 0;
            var mu = 0;
            var des = 0;
            var descu = 0;
            var cal = 0;
            var cal2 = 0;
            var tot = 0;

            var id = jQuery("#list").jqGrid('getGridParam', 'selrow');
            jQuery('#list').jqGrid('restoreRow', id);
            var ret = jQuery("#list").jqGrid('getRowData', id);

            if (name == 'valor') {
                var precio = jQuery("#list").jqGrid('getCell', rowid, iCol + 1);
                var descuento = jQuery("#list").jqGrid('getCell', rowid, iCol + 2);

                var operacion = parseFloat(val) * parseFloat(precio);
                cal = (operacion * descuento) / 100;
                tot = operacion - cal;

                jQuery("#list").jqGrid('setRowData', rowid, {precio_t: tot});

                if (ret.iva === "Si") {
                    var fil = jQuery("#list").jqGrid("getRowData");
                    for (var t = 0; t < fil.length; t++) {
                        var dd = fil[t];
                        if (dd['iva'] === "Si") {
                            subtotal = (subtotal + parseFloat(dd['precio_t']));
                            var sub = parseFloat(subtotal);
                            iva = (subtotal * 12) / 100;
                            mu = dd['cantidad'] * dd['precio_u'];
                            des = (mu * dd['descuento']) / 100;
                            descu = parseFloat(descu) + parseFloat(des);
                            t_fc = (parseFloat(subtotal) + parseFloat(iva)) + parseFloat($("#total_p").val());
                            $("#iva_producto").val("");
                        }
                    }

                    $("#total_p2").val(sub);
                    $("#iva").val(iva);
                    $("#desc").val(descu);
                    $("#tot").val(t_fc.toFixed(2));
                    $("#total_p2x").val(sub.toFixed(2));
                    $("#ivax").val(iva.toFixed(2));
                    $("#descx").val(descu.toFixed(2));
                    $("#totx").val(t_fc.toFixed(2));
                    $("#valor_factura").val(t_fc.toFixed(2));
                } else {
                    fil = jQuery("#list").jqGrid("getRowData");
                    subtotal = 0;
                    t_fc = 0;
                    iva = 0;
                    mu = 0;
                    des = 0;
                    descu = 0;
                    for (t = 0; t < fil.length; t++) {
                        dd = fil[t];
                        if (dd['iva'] === "No") {
                            subtotal = (subtotal + parseFloat(dd['precio_t']));
                            sub = parseFloat(subtotal);
                            iva = parseFloat($("#iva").val());
                            mu = dd['cantidad'] * dd['precio_u'];
                            des = (mu * dd['descuento']) / 100;
                            descu = parseFloat(descu) + parseFloat(des);
                            t_fc = (parseFloat(subtotal) + parseFloat(iva)) + parseFloat($("#total_p2").val());
                            $("#iva_producto").val("");
                        }
                    }
                    $("#total_p").val(sub);
                    $("#desc").val(descu);
                    $("#tot").val(t_fc.toFixed(2));
                    $("#total_px").val(sub.toFixed(2));
                    $("#descx").val(descu.toFixed(2));
                    $("#totx").val(t_fc.toFixed(2));
                    $("#valor_factura").val(t_fc.toFixed(2));
                }
            }

            if (name == 'valor') {
                var cantidad = jQuery("#list").jqGrid('getCell', rowid, iCol - 1);
                var descuento2 = jQuery("#list").jqGrid('getCell', rowid, iCol + 1);

                var operacion2 = parseFloat(cantidad) * parseFloat(val);
                cal2 = (operacion2 * descuento2) / 100;
                tot = operacion2 - cal2;

                jQuery("#list").jqGrid('setRowData', rowid, {precio_t: tot});

                if (ret.iva === "Si") {
                    fil = jQuery("#list").jqGrid("getRowData");
                    for (t = 0; t < fil.length; t++) {
                        dd = fil[t];
                        if (dd['iva'] === "Si") {
                            subtotal = (subtotal + parseFloat(dd['precio_t']));
                            sub = parseFloat(subtotal);
                            iva = (subtotal * 12) / 100;
                            mu = dd['cantidad'] * dd['precio_u'];
                            des = (mu * dd['descuento']) / 100;
                            descu = parseFloat(descu) + parseFloat(des);
                            t_fc = (parseFloat(subtotal) + parseFloat(iva)) + parseFloat($("#total_p").val());
                            $("#iva_producto").val("");
                        }
                    }
                    $("#total_p2").val(sub);
                    $("#iva").val(iva);
                    $("#desc").val(descu);
                    $("#tot").val(t_fc.toFixed(2));
                    $("#total_p2x").val(sub.toFixed(2));
                    $("#ivax").val(iva.toFixed(2));
                    $("#descx").val(descu.toFixed(2));
                    $("#totx").val(t_fc.toFixed(2));
                    $("#valor_factura").val(t_fc.toFixed(2));
                } else {
                    fil = jQuery("#list").jqGrid("getRowData");
                    subtotal = 0;
                    t_fc = 0;
                    iva = 0;
                    mu = 0;
                    des = 0;
                    descu = 0;
                    for (t = 0; t < fil.length; t++) {
                        dd = fil[t];
                        if (dd['iva'] === "No") {
                            subtotal = (subtotal + parseFloat(dd['precio_t']));
                            sub = parseFloat(subtotal);
                            iva = parseFloat($("#iva").val());
                            mu = dd['cantidad'] * dd['precio_u'];
                            des = (mu * dd['descuento']) / 100;
                            descu = parseFloat(descu) + parseFloat(des);
                            t_fc = (parseFloat(subtotal) + parseFloat(iva)) + parseFloat($("#total_p2").val());
                            $("#iva_producto").val("");
                        }
                    }
                    $("#total_p").val(sub);
                    $("#desc").val(descu);
                    $("#tot").val(t_fc.toFixed(2));
                    $("#total_px").val(sub.toFixed(2));
                    $("#descx").val(descu.toFixed(2));
                    $("#totx").val(t_fc.toFixed(2));
                    $("#valor_factura").val(t_fc.toFixed(2));
                }
            }
        },
        afterInsertRow: function (rowid, rowdata, rowelem) {
            prodFactSelConcepto.push({idConcepto: rowid, productos: productosFactSelec});
        }
    });
    ///////BUSQUEDA DE GASTOS//////////
    jQuery("#list2").jqGrid({
        url: 'xmlBuscarFacturaVenta2.php',
        datatype: 'xml',
        colNames: ['ID', 'FACTURA', 'FECHA INGRESO', 'FECHA EMISION', 'DESCRIPCION', 'TOTAL'],
        colModel: [
            {name: 'id_gastos', index: 'id_gastos', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 50},
            {name: 'num_factura', index: 'num_factura', editable: false, search: true, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 110},
            {name: 'fecha_actual', index: 'fecha_actual', editable: true, search: true, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 100},
            {name: 'fecha_emision', index: 'fecha_emision', editable: true, search: true, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 100},
            {name: 'descripcion', index: 'descripcion', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 150},
            {name: 'total', index: 'total', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 80}
        ],
        rowNum: 30,
        width: 800,
        height: 220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager2'),
        sortname: 'id_gastos',
        sortorder: 'desc',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
            jQuery('#list2').jqGrid('restoreRow', id);
            var ret = jQuery("#list2").jqGrid('getRowData', id);

            var valor = ret.id_gastos;
            // llamar datos Factura Compra
            $("#btnGuardar").attr("disabled", true);
            //$("#estado h3").remove();
            $.getJSON('buscar_gastos.php?com=' + valor, function (data) {
                var tama = data.length;
                t = data[23];
                if (tama !== 0) {
                    for (var i = 0; i < tama; i = i + 24) {
                        $("#comprobante").val(data[i]);
                        $("#factura").val(data[i + 1]);
                        $("#comprobante2").val(data[i + 2]);
                        $("#fecha_actual").val(data[i + 3]);
                        $("#hora_actual").val(data[i + 4]);
                        $("#fecha_emision").val(data[i + 5]);
                        $("#descripcion").val(data[i + 6]);
                        $("#subtotal").val(data[i + 7]);
                        $("#iva").val(data[i + 8]);
                        //                        $("#valor").val(data[i + 9]);

                        if (data[i + 10] == "Pasivo") {
                            $("#estado").append($("<h3>").text("Anulada"));
                            $("#estado h3").css("color", "red");
                        } else {
                            $("#estado h3").remove();
                        }
                        $("#id_proveedor").val(data[i + 11]);
                        $("#tipo_docu").val(data[i + 12]);
                        $("#ruc_ci").val(data[i + 13]);
                        $("#empresa").val(data[i + 14]);
                        $("#deposito").val(data[i + 15]);
                        $("#banco").val(data[i + 16]);
                        $("#cuentanum").val(data[i + 17]);
                        $("#autorizacion").val(data[i + 18]);
                        $("#total_px").val(parseFloat(data[i + 19]).toFixed(2));
                        $("#total_p2x").val(parseFloat(data[i + 20]).toFixed(2));
                        $("#subx").val((parseFloat(data[i + 19]) + parseFloat(data[i + 20])).toFixed(2));
                        $("#ivax").val(parseFloat(data[i + 21]).toFixed(2));
                        $("#descx").val(parseFloat(data[i + 22]).toFixed(2));
                        $("#totx").val(parseFloat(data[i + 23]).toFixed(2));
                        $("#buscar_gastos").dialog("close");



                    }
                }
            });

            $("#list").jqGrid("clearGridData", true);
            $.getJSON('buscar_gastos2.php?com=' + valor, function (data) {
                var tama = data.length;
                var descuento = 0;
                var total = 0;
                var su = 0;
                var precio = 0;
                var multi = 0;
                var flotante = 0;
                var resultado = 0;

                if (tama != 0) {
                    for (var i = 0; i < tama; i = i + 7) {
                        //                        desc = data[i + 5];
                        precio = parseFloat(data[i + 5]);
                        //                        multi = parseFloat(data[i + 3]) * parseFloat(data[i + 4]);
                        //                        descuento = (multi * parseFloat(desc)) / 100;
                        //                        flotante = parseFloat(descuento);
                        //                        resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                        //                        total = multi - resultado;

                        var datarow = {
                            concepto: data[i],
                            id_plan: data[i + 1],
                            cuenta_contable: data[i + 2],
                            iva: data[i + 3],
                            centro_costo: data[i + 4],
                            valor: data[i + 5],
                            bien_servicio: data[i + 6]

                        };

                        var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                    }
                }
            });

            $.getJSON('retornar_formas_mixto_grid.php?com=' + valor, function (data) {
                $("#listPagoreten_mixto").jqGrid("clearGridData", true);
                var tama = data.length;
                if (tama != 0) {
                    for (var i = 0; i < tama; i = i + 5) {
                        var datarow = {
                            forma_pago_mixto: data[i],
                            tarjeta_credito: data[i + 1],
                            num_documento: data[i + 2],
                            valor: data[i + 3],
                            id_cuenta: data[i + 4]
                        };
                        var su = jQuery("#listPagoreten_mixto").jqGrid('addRowData', data[i], datarow);
                    }
                }
            });

            $.getJSON('retornar_retenciones_grid.php?com=' + valor, function (data) {
                var tama = data.length;
                if (tama != 0) {
                    for (var i = 0; i < tama; i = i + 6) {

                        var datarow = {
                            base_imponible: data[i],
                            impuesto: data[i + 1],
                            porcent_reten: data[i + 2],
                            valor_retenido: data[i + 3]

                        };

                        var num = data[i + 5];
                        var res = num.substr(8, 20)
                        $("#serie_retencion").val(res);

                        var su = jQuery("#listPagoreten").jqGrid('addRowData', data[i], datarow);
                    }
                }
            });




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
        recreateForm: true, closeAfterEdit: true, checkOnUpdate: true, reloadAfterSubmit: true, closeOnEscape: true
    },
            {
                reloadAfterSubmit: true, closeAfterAdd: true, checkOnUpdate: true, closeOnEscape: true,
                bottominfo: "Todos los campos son obligatorios"
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
            });







    jQuery("#list2").jqGrid('navButtonAdd', '#pager2', {
        caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
            jQuery('#list2').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list2").jqGrid('getRowData', id);
                if (parseFloat(ret.total_gasto) <= parseFloat(0.00)) {
                    alertify.alert("Factura al limite");
                } else {
                    $("#ingreso_gastos").dialog("open");
                }
            } else {
                alertify.alert("Seleccione una Factura");
            }
        }
    });


    jQuery("#listPago").jqGrid({
        datatype: "local",
        colNames: ['', 'Id Forma', 'Codigo', 'Descripcion'],
        colModel: [
            {
                name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions',
                formatoptions: {keys: false, delbutton: true, editbutton: false}
            },
            {name: 'id_forma', index: 'id_forma', hidden: true, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'codigo', index: 'codigo', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'descripcion', index: 'descripcion', editable: true, align: 'center', width: '690', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}}
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        height: 255,
        pager: jQuery('#pagerP'),
        sortname: 'id_forma',
        shrinkToFit: true,
        sortorder: 'asc',
        caption: 'Formas de Pago',
        viewrecords: true,
        delOptions: {
            onclickSubmit: function (rp_ge, rowid) {
                rp_ge.processing = true;
                var su = jQuery("#listPago").jqGrid('delRowData', rowid);
                var e = "";
                var nuevo = "";
                var deta = $("#detalle_pago").val();
                var a = deta.split("*");
                var con = a.length;
                var ob = "";
                var valor = "";
                if (rowid.length == 1) {
                    e = "0" + rowid;
                } else {
                    e = rowid;
                }
                if (su == true) {
                    for (var i = 0; i < con; i++) {
                        if (a[i] != e) {
                            $.ajax({
                                url: 'buscar_forma_pago.php',
                                type: 'POST',
                                data: "id=" + a[i],
                                success: function (data) {
                                    if (ob.length == 0) {
                                        ob = ob + data + ":";
                                        $("#observacionPago").val(ob);
                                    } else {
                                        ob = ob + "\n" + data + ":";
                                        $("#observacionPago").val(ob);
                                    }
                                }
                            });
                            if (nuevo.length == 0) {
                                nuevo = nuevo + a[i];
                            } else {
                                nuevo = nuevo + "*" + a[i];
                            }
                        }
                    }
                    $("#detalle_pago").val(nuevo);
                    $("#delmodlistPago").hide();
                    return true;
                }
                //$(".ui-icon-closethick").trigger('click');
            },
            processing: true
        }
    }).jqGrid('navGrid', '#pagerP',
            {
                add: false,
                edit: false,
                del: false,
                refresh: true,
                search: true,
                view: true
            });
    //jQuery("#listPago").setGridWidth($('#pagerP').width());

    // tabla series
    jQuery("#list2").jqGrid({
        datatype: "local",
        colNames: ['', 'cod_serie', 'Series'],
        colModel: [
            {
                name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions',
                formatoptions: {keys: false, delbutton: true, editbutton: false}
            },
            {
                name: 'id_series', index: 'id_series', editable: false, search: false, hidden: true, editrules: {edithidden: false}, align: 'center',
                frozen: true, width: 50
            },
            {
                name: 'serie', index: 'serie', editable: false, search: false, hidden: false, editrules: {edithidden: true}, align: 'center',
                frozen: true, width: 100
            }
        ],
        rowNum: 30,
        width: 450,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager2'),
        sortname: 'id_series',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'clientArray',
        shrinkToFit: true,
        delOptions: {
            onclickSubmit: function (rp_ge, rowid) {
                rp_ge.processing = true;
                var su = jQuery("#list2").jqGrid('delRowData', rowid);
                if (su == true) {
                    $("#delmodlist2").hide();
                    return true;
                }
                //$(".ui-icon-closethick").trigger('click');
            },
            processing: true
        }
    }).jqGrid('navGrid', '#pager2',
            {
                add: false,
                edit: false,
                del: false,
                refresh: true,
                search: true,
                view: true
            });
    // Fin
    $(window).bind('resize', function () {
        jQuery("#list4").setGridWidth($('#pager4').width());
    }).trigger('resize');

    jQuery("#list4").jqGrid({
        url: 'xmlPlanCuentas_btn.php',
        datatype: 'xml',
        colNames: ['Cod. Cuenta', 'Descripcion', 'Cuenta'],
        colModel: [
            {name: 'idcontable', index: 'idcontable', editable: true, align: 'left', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'descripcion', index: 'descripcion', editable: true, align: 'center', width: '490', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'cuenta', index: 'cuenta', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}}
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
            console.log($("#idCuenta").val());
            $("#cuenta_contable").val(ccuenta);
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
    obtenerNumSerieRet();
}
function aceptarEliminar() {
    if ($("#comprobante").val() == "") {
        alertify.error("Seleccione un Gasto");
        $("#buscar_gastos").dialog("open");
    } else {
        $.ajax({
            type: "POST",
            url: "eliminar_gastos.php",
            data: "comprobante=" + $("#comprobante").val(),
            success: function (data) {
                var val = data;
                if (val == 1) {
                    alertify.alert("Gasto Eliminado Correctamente", function () {
                        location.reload();
                    });
                } else {
                    alertify.alert(val);
                }
            }
        });
    }
}

function cancelarEliminar() {
    $("#seguro").dialog("close");
    $("#clave_permiso").dialog("close");
    $("#clave").val("");
}

function cancelar_acceso() {
    $("#clave_permiso").dialog("close");
    $("#clave").val("");
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
function guardar_retenciones_factura_compra_g() {
    var tam = jQuery("#listPagoreten").jqGrid("getRowData");
    var y = document.getElementById("tipoRetencionesI").selectedIndex;

    if (document.getElementById('retencionI2').checked == true) {
        var xx = 1;
    } else {
        var xx = 0;
    }
    if (document.getElementById('retencionF2S').checked == true) {
        var xxs = 1;
    } else {
        var xxs = 0;
    }
    //sumC=0;
    var x = document.getElementById("tipoRetencionesF").selectedIndex;
    var xs = document.getElementById("tipoRetencionesFS").selectedIndex;
    if ($("#serie_retencion").val() == "") {
        $("#serie_retencion").focus();
        alertify.error("Ingrese número de la Retenciòn");
    } else {
        var num_retencion = (num_serie_ret + "-" + $("#serie_retencion").val());
        $.ajax({
            type: "POST",
            url: "../registro_gastos/comparar_num_retencion.php",
            data: "num_reten=" + num_retencion,
            success: function (data) {
                var val = data;
                if (val != 0) {
                    $("#serie_retencion").val("");
                    $("#serie_retencion").focus();
                    alertify.error("Error... La Retenciòn ya existe, favor verificar el número que corresponda");
                    var res1 = parseInt(val.substr(8, 16));
                    res1 = res1 + 1;

                    $("#serie_retencion").val(res1);
                    var a1 = autocompletar(res1);
                    var validado = a1 + "" + res1;
                    $("#serie_retencion").val(validado);
                } else {
                    if ($("#ruc_ci").val() == "") {
                        var a = autocompletar($("#serie_retencion").val());
                        $("#serie_retencion").val(a + "" + $("#serie_retencion").val());
                        $("#ruc_ci").focus();
                        alertify.error("Indique un cliente");
                    } else {
                        if (tam.length == 0) {

                            alertify.error("Error... Ingrese Retenciones");
                        } else {
                            if ($("#autorizacion_retencion").val() != "") {

                                var a = autocompletar($("#serie_retencion").val());
                                var seriee = num_serie_ret + "-" + a + "" + $("#serie_retencion").val();
                                //TODO borrar comentado
                                /*  if ($("#punto_ventaid").val() == 1) {
                                 var seriee = ("001" + "-" + "001" + "-" + a + "" + $("#serie_retencion").val());
                                 }
                                 if ($("#punto_ventaid").val() == 2) {
                                 var seriee = ("001" + "-" + "003" + "-" + a + "" + $("#serie_retencion").val());
                                 }
                                 if ($("#punto_ventaid").val() == 3) {
                                 var seriee = ("001" + "-" + "001" + "-" + a + "" + $("#serie_retencion").val());
                                 }
                                 if ($("#punto_ventaid").val() == 4) {
                                 var seriee = ("003" + "-" + "001" + "-" + a + "" + $("#serie_retencion").val());
                                 }
                                 if ($("#punto_ventaid").val() == 5) {
                                 var seriee = ("005" + "-" + "001" + "-" + a + "" + $("#serie_retencion").val());
                                 } */
                                //if($("#calculoRetencionF").val()!= 0.000 || $("#calculoRetencionF").val()!= 0){
                                var v1 = new Array();
                                var v2 = new Array();
                                var v3 = new Array();
                                var v4 = new Array();
                                var v5 = new Array();
                                var v6 = new Array();

                                var string_v1 = "";
                                var string_v2 = "";
                                var string_v3 = "";
                                var string_v4 = "";
                                var string_v5 = "";
                                var string_v6 = "";
                                //alertify.alert("hola");
                                var fil = jQuery("#listPagoreten").jqGrid("getRowData");
                                for (var i = 0; i < fil.length; i++) {
                                    var datos = fil[i];
                                    v1[i] = datos['base_imponible'];
                                    v2[i] = datos['impuesto'];
                                    v3[i] = datos['porcent_reten'];
                                    v4[i] = datos['valor_retenido'];
                                    v5[i] = datos['valor_retenido'];
                                    v6[i] = datos['id_retenciones_ser'];
                                }

                                for (i = 0; i < fil.length; i++) {
                                    string_v1 = string_v1 + "|" + v1[i];
                                    string_v2 = string_v2 + "|" + v2[i];
                                    string_v3 = string_v3 + "|" + v3[i];
                                    string_v4 = string_v4 + "|" + v4[i];
                                    string_v5 = string_v5 + "|" + v5[i];
                                    string_v6 = string_v6 + "|" + v6[i];
                                }
                                $("#btnGuardarRetenciones").attr("disabled", true);

                                $.ajax({
                                    type: "POST",
                                    url: "guardar_ret_fuente_fact_compra.php",
                                    data: "id_factura=" + $("#comprobante").val() + "&id_retencion_fuente=" + x + "&fecha_actual=" + $("#fecha_actual").val() + "&valor_factura=" + $("#sub").val() + "&iva_factura=" + $("#iva").val() + "&valor_retencion=" + $("#calculoRetencionF").val() + "&autorizacion_ret=" + $("#autorizacion_retencion").val() + "&serie_retencion=" + seriee + "&porcent_reten=" + $("#porcent_reten").val() + "&id_retencion_iva=" + y + "&valor_facturaiva=" + $("#tot").val() + "&valor_retencioni=" + $("#calculoRetencionI").val() + "&valor_seleccion_iva=" + xx + "&porcent_iva=" + $("#porcent_iva").val() + "&id_retencion_fuentes=" + xs + "&valor_retencions=" + $("#calculoRetencionFS").val() + "&porcent_retens=" + $("#porcent_retens").val() + "&valor_seleccion_si_no=" + xxs + "&campo1reten=" + string_v1 + "&campo2reten=" + string_v2 + "&campo3reten=" + string_v3 + "&campo4reten=" + string_v4 + "&campo5reten=" + string_v5 + "&campo6reten=" + string_v6 + "&total_reten_iva=" + $("#total_retencion").val() + "&formascc=" + $("#forma_pago").val(),
                                    dataType: "json",
                                    success: function (data) {
                                        var val = data;
                                        if (data.estado == 2) {

                                            $("#guardado_reten").val("1");


                                            //                                            alertify.confirm("¿Desea ingresar formas de pago?",
                                            //                                                    function (e) {
                                            //                                                        if (e) {
                                            //                                                            var subtotal_adelanto1 = (parseFloat($("#tot").val()) - parseFloat($("#total_retencion").val()));
                                            //
                                            //                                                            $("#valor_factura").val(subtotal_adelanto1.toFixed(2));
                                            //                                                            //                                                                            $("#comprobante").val(val);
                                            //                                                            $('#otros_form').prop('selected', true);
                                            //                                                            $('.nav-tabs a[href="#tab_4"]').tab('show')
                                            //                                                            $("#formaspago_mixto").attr("disabled", false);
                                            //
                                            //                                                        } else {
                                            //                                                            $('#contado_form').prop('selected', true);
                                            //                                                            location.reload();
                                            //                                                        }
                                            //
                                            //                                                    }
                                            //
                                            //                                            );
                                            alertify.confirm("AUTORIZADO¿Desea Imprimir Comprobante?",
                                                    function (e) {
                                                        $("#btnGuardarRetenciones").attr("disabled", false);
                                                        if (e) {
                                                            reenviar(data.id);
                                                            window.open("generarPDFRetenGAS.php?hoja=A4&id=" + data.id, '_blank');
                                                            location.reload();
                                                        } else {
                                                            reenviar(data.id);
                                                            location.reload();
                                                        }
                                                    });



                                            //                                            alertify.alert("Guardado Correctamente");
                                            //                                            window.open("../../reportes/reporte_registo_gasto.php?hoja=A5&id=" + data.id, '_blank');
                                            //                                            window.open("../../reportes/transacciones_1.php?hoja=A5&id=" + data.id + '&gas=' + 'GAS', '_blank');
                                            //                                            location.reload();

                                        } else {
                                            $("#guardado_reten").val("2");
                                            alertify.error("Error....Retencion no Autorizado");
                                        }


                                        if (data.estado == 7) {
                                            alertify.alert("Factura Guardada  No Autorizada", function () {
                                                location.reload();
                                            });
                                        } else {
                                            alertify.alert(val);
                                        }
                                    }
                                });





                            } else {
                                alertify.alert("Ingrese Número de Autorización de la Retención");
                            }

                            /*if(sumC!=0){
                             alertify.alert("Los datos se han guardado correctamente", function(){
                             location.reload();
                             });        
                             }else{
                             alertify.alert(sumC);
                             }*/
                        }
                    }
                }
            }
        });


    }

}
function guardar_retenciones_factura_compra_directo_c() {

    //sumC=0;
    var x = 4;

    if (x != 0) {
        if ($("#serie_sinretencion").val() == "") {
            $("#serie_sinretencion").focus();
            alertify.error("Ingrese número de la Retenciòn");
        } else {
            var num_retencion = (num_serie_ret + "-" + $("#serie_sinretencion").val());
            $.ajax({
                type: "POST",
                url: "comparar_num_retencion_directo.php",
                data: "num_reten=" + num_retencion,
                success: function (data) {
                    var val = data;
                    if (val != 0) {
                        $("#serie_sinretencion").val("");
                        $("#serie_sinretencion").focus();
                        alertify.error("Error... La Retenciòn ya existe, favor verificar el número que corresponda");
                        var res1 = parseInt(val.substr(8, 16));
                        res1 = res1 + 1;
                    } else {
                        //if($("#autorizacion_retencion").val()!=""){  
                        var a = autocompletarsin($("#serie_sinretencion").val());
                        var seriee = (num_serie_ret + "-" + a + "" + $("#serie_sinretencion").val());



                        $.ajax({
                            type: "POST",
                            url: "guardar_ret_fuente_fact_directo.php",
                            data: "id_factura=" + $("#comprobante").val() + "&id_retencion_fuente=" + x + "&fecha_actual=" + $("#fecha_actual").val() + "&iva_factura=" + $("#iva").val() + "&valor_retencion=" + $("#calculoRetencionF").val() + "&autorizacion_ret=" + $("#autorizacion_retencion").val() + "&serie_sinretencion=" + seriee + "&valor_factura=" + $("#sub").val(),
                            success: function (data) {
                                var val = data;
                                if (val == 1) {
                                    $("#guardado_reten").val("1");
                                    alertify.alert("Retenciones guardadas correctamente", function () {
                                        alertify.alert("Guardado Correctamente");
                                        window.open("../../reportes/reporte_registo_gasto.php?hoja=A5&id=" + $("#comprobante").val(), '_blank');
                                        //                                        window.open("../../reportes/transacciones_1.php?hoja=A5&id=" + $("#comprobante").val() + '&gas=' + 'GAS', '_blank');
                                        location.reload();
                                    });

                                } else if (val == 2) {
                                    alertify.alert("La Factura ya tiene retenciones en la fuente");
                                } else {
                                    alertify.alert(val);
                                    location.reload();
                                }
                            }
                        });

                    }
                }
            });

        }

    }
    //    var y = document.getElementById("tipoRetencionesI").selectedIndex;
    //    if (y != 0) {
    //        //if($("#calculoRetencionI").val()!= 0.000 || $("#calculoRetencionI").val()!= 0){
    //        $.ajax({
    //            type: "POST",
    //            url: "guardar_ret_iva_fact_compra.php",
    //            data: "id_factura=" + $("#comprobante").val() + "&id_retencion_iva=" + y + "&valor_factura=" + $("#tot").val() + "&iva_factura=" + $("#iva").val() + "&valor_retencion=" + $("#calculoRetencionI").val() + "&autorizacion_ret=" + $("#autorizacion_retencion").val(),
    //            success: function(data) {
    //                var val1 = data;
    //                if (val == 1) {
    //                    alertify.alert("Retenciones guardadas correctamente", function() {
    //                        //                            window.open("../../reportes/retenciones_registro_gastos.php?hoja=A4&id="+$("#comprobante").val(),'_blank');    
    //                        location.reload();
    //                    });
    //                } else if (val == 2) {
    //                    alertify.alert("La Factura ya tiene retenciones en la fuente");
    //                } else {
    //                    alertify.alert(val);
    //                }
    //            }
    //        });
    //    }

}
function guardar_retenciones_factura_compra() {


    if (document.getElementById('elegirretencionF1').checked == true) {
        if ($("#serie_sinretencion").val() == "") {
            $("#serie_sinretencion").focus();
            alertify.error("Debe Ingresar num sin Retencion");
        } else {
            alertify.confirm("¿Desea ingresar Formas de Pago sin Retencion?",
                    function (e) {
                        if (e) {
                            var subtotal_adelanto1 = (parseFloat($("#tot").val()));

                            $("#valor_factura").val(subtotal_adelanto1.toFixed(2));
                            //                                                                            $("#comprobante").val(val);
                            $('#otros_form').prop('selected', true);
                            $('.nav-tabs a[href="#tab_4"]').tab('show')
                            $("#formaspago_mixto").attr("disabled", false);

                        } else {

                            $('#contado_form').prop('selected', true);

                            guardar_asiento_contable();







                            //                        location.reload();
                        }

                    }


            );


        }


    } else {


        var tam = jQuery("#listPagoreten").jqGrid("getRowData");


        if (tam.length == 0) {

            alertify.error("Error... Ingrese Retenciones");
        } else {
            alertify.confirm("¿Desea ingresar Formas de Pago?",
                    function (e) {
                        if (e) {
                            var subtotal_adelanto1 = (parseFloat($("#tot").val()) - parseFloat($("#total_retencion").val()));

                            $("#valor_factura").val(subtotal_adelanto1.toFixed(2));
                            //                                                                            $("#comprobante").val(val);
                            $('#otros_form').prop('selected', true);
                            $('.nav-tabs a[href="#tab_4"]').tab('show')
                            $("#formaspago_mixto").attr("disabled", false);

                        } else {

                            $('#contado_form').prop('selected', true);
                            guardar_asiento_contable();


                            //                        location.reload();
                        }

                    }


            );
        }
    }

}
function agregarForma() {
    var repe = 0;
    var filas = jQuery("#listPago").jqGrid("getRowData");
    var fp_co = document.getElementById("formasPago").selectedIndex;

    if (fp_co != "0") {
        if (filas.length == 0) {
            var datarow = {
                id_forma: $("#id_forma_pago").val(),
                codigo: $("#codigo_pago").val(),
                descripcion: $("#descripcion_pago").val()
            };
            su = jQuery("#listPago").jqGrid('addRowData', $("#id_forma_pago").val(), datarow);
            $("#observacionPago").val($("#observacionPago").val() + $("#descripcion_pago").val() + ":");
            $("#detalle_pago").val($("#detalle_pago").val() + $("#codigo_pago").val())
        } else {
            for (var i = 0; i < filas.length; i++) {
                var id = filas[i];

                if (id['id_forma'] == $("#id_forma_pago").val()) {
                    repe = 1;
                }
            }
            if (repe != 1) {
                var datarow = {
                    id_forma: $("#id_forma_pago").val(),
                    codigo: $("#codigo_pago").val(),
                    descripcion: $("#descripcion_pago").val()
                };
                su = jQuery("#listPago").jqGrid('addRowData', $("#id_forma_pago").val(), datarow);
                $("#observacionPago").val($("#observacionPago").val() + "\n" + $("#descripcion_pago").val() + ":");
                $("#detalle_pago").val($("#detalle_pago").val() + "*" + $("#codigo_pago").val())
            } else {
                alertify.alert("La forma de pago ya está ingresada");
            }
        }
    } else {
        alertify.error("Error.... debe seleccionar la forma de pago");
    }
    //alertify.alert("Correcto");
}

function cambioForma() {
    //alertify.alert("inicio");
    var x = document.getElementById("formasPago").selectedIndex;
    //alertify.alert(x+"-");
    var porNombre = document.getElementById("formasPago")[x].value;
    //alertify.alert(porNombre+"*");
    var formas = porNombre.split("#");
    $("#id_forma_pago").val(formas[0]);
    $("#codigo_pago").val(formas[1]);
    $("#descripcion_pago").val(formas[2]);

}
function abrirCuenta() {
    $("#cuentas").dialog("open");
}
function reenviar(id) {
    $.ajax({
        type: "POST",
        url: "guardar_ret_fuente_fact_compra.php",
        data: {
            reenviarcorreo: 'reenviarcorreo',
            id: id
        },
        //        data: "id="+x,
        dataType: "json",
        success: function (data) {

            if (data.estado == 1) {

                alertify.alert("Enviado al Correo: ");

            } else {
                alertify.alert("Error al enviar: " + data);
            }

        }
    });
}
function reenviarXml(id) {
    $.ajax({
        type: "POST",
        url: "guardar_ret_fuente_fact_compra.php",
        data: {
            reenviarxml: 'reenviarxml',
            id: id
        },
        //        data: "id="+x,
        dataType: "json",
        success: function (data) {

            if (data.estado == 2) {

                alertify.alert("AUTORIZADO: ");

            } else {
                alertify.alert("NO AUTORIZADO: ");
            }

        }
    });
}
function enviarXml(id) {
    $.ajax({
        type: "POST",
        url: "guardar_ret_fuente_fact_compra.php",
        data: {
            enviarxml: 'enviarxml',
            id: id
        },
        //        data: "id="+x,
        dataType: "json",
        success: function (data) {

            if (data.estado == 2) {

                alertify.alert("AUTORIZADO: ");

            } else {
                alertify.alert("NO AUTORIZADO: ");
            }

        }
    });
}
function autocompletar_num() {
    var temp = "";
    var str = $("#factura").val();
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
function guardar_asiento_contable() {

    //  if ($("#guardado_reten").val() == "1") {
    var observa = "Ninguna";
    var forma_p = "";
    var bien_ser = "";
    var pago_ats = "";
    var str = $("#factura").val();
    var res = str.split("-");

    var ele1 = res[0];
    var ele2 = res[1];
    var ele3 = res[2];
    var ele22 = ele3.substring(8, 9);

    var tam = jQuery("#list").jqGrid("getRowData");

    //                            guardar_serie();
    var num_fac = $("#factura").val();


    ///////////////guardar gastos///////////////////
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
    //alertify.alert("hola");
    var fil = jQuery("#list").jqGrid("getRowData");
    for (var i = 0; i < fil.length; i++) {
        var datos = fil[i];
        v1[i] = datos['concepto'];
        v2[i] = datos['id_plan'];
        v3[i] = datos['cuenta_contable'];
        v4[i] = datos['iva'];
        v5[i] = datos['centro_costo'];
        v6[i] = datos['valor'];
        v7[i] = datos['bien_servicio'];
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
    var seriee = $("#factura").val();
    observa = "Ninguna";
    $("#btnGuardar").attr("disabled", true);
    $.ajax({
        type: "POST",
        url: "guardar_asiento_contable.php",
        data: "id_gastos=" + $("#comprobante").val() + "&num_factura=" + $("#factura").val() + "&comprobante=" + $("#comprobante").val() + "&fecha_actual=" + $("#fecha_actual").val() + "&fecha_emision=" + $("#fecha_emision").val() + "&hora_actual=" + $("#hora_actual").val() + "&descripcion=" + $("#descripcion").val() + "&valor=" + $("#totx").val() + "&subtotal=" + $("#subx").val() + "&iva=" + $("#ivax").val() + "&proveedor=" + $("#id_proveedor").val() + "&deposito=" + $("#deposito").val() + "&banco=" + $("#banco").val() + "&num_cuenta=" + $("#cuentanum").val() + "&num_autorizacion=" + $("#autorizacion").val() + "&campo1=" + string_v1 + "&idCuenta=" + $("#idCuenta").val() + "&fecha_caducidad=" + $("#fecha_caducidad").val() + "&tipo_comprobante=" + $("#tipo_comprobante").val() + "&serie=" + seriee + "&autorizacion=" + $("#autorizacion").val() + "&cancelacion=" + $("#cancelacion").val() + "&formas=" + forma_p + "&tarifa0=" + $("#total_p").val() + "&tarifa12=" + $("#total_p2").val() + "&iva=" + $("#iva").val() + "&desc=" + $("#desc").val() + "&tot=" + $("#tot").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&campo6=" + string_v6 + "&observaciones=" + observa + "&pago_ats=" + pago_ats + "&bien_servi=" + bien_ser + "&idCuenta=" + $("#idCuenta").val() + "&formascc=" + $("#forma_pago").val() + "&bien_servicio=" + $("#bien_servicio").val() + "&campo7=" + string_v7 + "&descripcion=" + $("#comentario").val(),
        success: function (data) {
            var val = data;
            if (val != 0) {

                if (document.getElementById('elegirretencionF1').checked == true) {
                    guardar_retenciones_factura_compra_directo_c();
                } else {
//                    if ($("#valor_reten").val() == "") {
//
//                        guardar_retenciones_factura_compra_g();
//                    } else {
                        window.open("../../reportes/reporte_registo_gasto.php?hoja=A5&id=" + val, '_blank');
                        window.open("../../reportes/transacciones_1.php?hoja=A5&id=" + val + '&gas=' + 'GAS', '_blank');
//                        location.reload();
//                    }
                }
//                alertify.success("Gasto Guardado correctamente");
                //              location.reload();
                //                alertify.confirm("¿Desea ingresar retenciones?",
                //                        function (e) {
                //                            if (e) {
                ////                                                                        $("#comprobante").val(val);
                //                                $("#tipoRetencionesF").attr("disabled", false);
                //                                $('.nav-tabs a[href="#tab_2"]').tab('show')
                //
                //                            } else {
                //                                window.open("../../reportes/reporte_registo_gasto.php?hoja=A5&id=" + val, '_blank');
                //                               
                //                            }
                //
                //                        }
                //
                //                );
            }
        }
    });

    //  }else{
    //      alertify.error("Error... no se guardo la Retención");
    //  }
}
function guardar_gasto() {

    var observa = "Ninguna";
    var forma_p = "";
    var bien_ser = "";
    var pago_ats = "";
    var str = $("#factura").val();
    if ($("#factura").val() == "") {
        $("#factura").focus();
        alertify.error("Ingrese num de factura");
    } else {
        var res = str.split("-");
        var ele1 = res[0];
        var ele2 = res[1];
        var ele3 = res[2];
        var ele22 = ele3.substring(8, 9);
    }
    var tam = jQuery("#list").jqGrid("getRowData");
    if ($("#forma_pago").val() == "otros" && $("#validar_guardar_grid").val() == "") {
        alertify.error("Ingrese Valor ");
        $("#valor_formas").focus();
    } else {

        if ($("#forma_pago").val() == "otros" && $("#valor_factura_saldo").val() != "0.00") {
            alertify.error("Ingrese Valor ");
            $("#valor_formas").focus();
        } else {

            if ($("#id_proveedor").val() == "") {
                $("#ruc_ci").focus();
                alertify.error("Ingrese la Cédula");
            } else {
                if (ele22 == '_') {

                    var a = autocompletar_num();
                    var validado = a;
                    var ele31 = res[2];
                    var serie = ele31;

                    var res_serie1 = serie.split("_");
                    var sesult_serie1 = res_serie1[0];
                    $("#factura").val(ele1 + "-" + ele2 + "-" + validado + "" + sesult_serie1);
                    $("#factura").focus();
                }
                if ($("#autorizacion").val() == "") {
                    $("#autorizacion").focus();
                    alertify.error("Ingrese la Autorización");
                } else {
                    if ($("#factura").val() == "") {
                        $("#factura").focus();
                        alertify.error("Ingrese num de factura");
                    } else {

                        if ($("#fecha_emision").val() == "") {
                            $("#fecha_emision").focus();
                            alertify.error("Ingrese la Fecha emision");
                        } else {

                            if ($("#serie_retencion").val() == "") {
                                $("#serie_retencion").val("000000001")
                                alertify.error("Ingrese número de la Retenciòn");
                            } else {
                                //                            guardar_serie();
                                var num_fac = $("#factura").val();
                                $.ajax({
                                    type: "POST",
                                    url: "comparar_num_compra.php",
                                    data: "num_fac=" + num_fac + "&id_proveedor=" + $("#id_proveedor").val(),
                                    success: function (data) {
                                        var val = data;
                                        if (val != 0) {
                                            $("#factura").focus();
                                            alertify.error("Error... El número de factura ya existe");
                                        } else {
                                            if (tam.length == 0) {
                                                $("#codigo_barras").focus();
                                                alertify.error("Error... Ingrese Servicio");
                                            } else {

                                                console.log("entro2");
                                                if ($("#tot").val() > 1000.000) {
                                                    $("#tipoRetencionesF").attr("disabled", false);
                                                    if ($("#observacionPago").val() == "") {
                                                        alertify.alert("Debe ingresar formas de pago", function () {
                                                            $('.nav-tabs a[href="#tab_3"]').tab('show')
                                                            $("#tab_1").removeClass('active');
                                                            $("#tab_3").addClass('active');
                                                        });
                                                    } else {
                                                        ///////////////guardar gastos///////////////////
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

                                                        //alertify.alert("hola");
                                                        var fil = jQuery("#list").jqGrid("getRowData");
                                                        for (var i = 0; i < fil.length; i++) {
                                                            var datos = fil[i];
                                                            v1[i] = datos['concepto'];
                                                            v2[i] = datos['id_plan'];
                                                            v3[i] = datos['cuenta_contable'];
                                                            v4[i] = datos['iva'];
                                                            v5[i] = datos['centro_costo'];
                                                            v6[i] = datos['valor'];
                                                            v7[i] = datos['bien_servicio'];
                                                            v8[i] = datos['id_centro_costo'];
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
                                                        var seriee = $("#factura").val();
                                                        forma_p = $("#formasPago").val();
                                                        pago_ats = $("#detalle_pago").val();
                                                        observa = $("#observacionPago").val();

                                                        $("#btnGuardar").attr("disabled", true);
                                                        $.ajax({
                                                            type: "POST",
                                                            url: "guardar_gastos.php",
                                                            data: "id_gastos=" + $("#comprobante").val() + "&num_factura=" + $("#factura").val() + "&comprobante=" + $("#comprobante").val()
                                                                    + "&fecha_actual=" + $("#fecha_actual").val() + "&fecha_emision=" + $("#fecha_emision").val()
                                                                    + "&hora_actual=" + $("#hora_actual").val() + "&descripcion=" + $("#descripcion").val()
                                                                    + "&valor=" + $("#totx").val() + "&subtotal=" + $("#subx").val() + "&iva=" + $("#ivax").val()
                                                                    + "&proveedor=" + $("#id_proveedor").val() + "&deposito=" + $("#deposito").val() + "&banco=" + $("#banco").val()
                                                                    + "&num_cuenta=" + $("#cuentanum").val() + "&num_autorizacion=" + $("#autorizacion").val()
                                                                    + "&campo1=" + string_v1 + "&idCuenta=" + $("#idCuenta").val()
                                                                    + "&fecha_caducidad=" + $("#fecha_caducidad").val() + "&tipo_comprobante=" + $("#tipo_comprobante").val()
                                                                    + "&serie=" + seriee + "&autorizacion=" + $("#autorizacion").val() + "&cancelacion=" + $("#cancelacion").val()
                                                                    + "&formas=" + forma_p + "&formascc=" + $("#forma_pago").val() + "&tarifa0=" + $("#total_p").val() + "&tarifa12=" + $("#total_p2").val()
                                                                    + "&iva=" + $("#iva").val() + "&desc=" + $("#desc").val() + "&tot=" + $("#tot").val() + "&campo1=" + string_v1
                                                                    + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&campo6=" + string_v6 + "&campo7=" + string_v7 + "&campo8=" + string_v8
                                                                    + "&observaciones=" + observa + "&pago_ats=" + pago_ats + "&bien_servi=" + bien_ser + "&idCuenta=" + $("#idCuenta").val() + "&tipo_comprobante=" + $("#tipo_comprobante").val(),
                                                            success: function (data) {
                                                                var val = data;
                                                                if (!Number.isNaN(Number(val))) {
                                                                    if (Number(val) != 0) {
                                                                        $("#comprobante").val(Number(val));
                                                                    }
                                                                }
                                                                if (val != 0) {
                                                                    alertify.alert("Gasto Guardado correctamente");
                                                                    //                                                                    alertify.confirm("¿Desea ingresar retenciones?",
                                                                    //                                                                            function (e) {
                                                                    //                                                                                if (e) {
                                                                    //                                                                                    //                                                                            $("#comprobante").val(val);
                                                                    //
                                                                    //                                                                                    $("#tipoRetencionesF").attr("disabled", false);
                                                                    //                                                                                    $('.nav-tabs a[href="#tab_2"]').tab('show');
                                                                    //                                                                                    $("#valor_reten").val("");
                                                                    //
                                                                    //                                                                                } else {
                                                                    alertify.confirm("¿Desea ingresar formas de pago?",
                                                                            function (e) {
                                                                                if (e) {

                                                                                    var subtotal_adelanto1 = (parseFloat($("#tot").val()));

                                                                                    $("#valor_factura").val(subtotal_adelanto1.toFixed(2));
                                                                                    $("#valor_reten").val("1");
                                                                                    //                                                                            $("#comprobante").val(val);
                                                                                    $('#otros_form').prop('selected', true);
                                                                                    $('.nav-tabs a[href="#tab_4"]').tab('show')
                                                                                    $("#formaspago_mixto").attr("disabled", false);

                                                                                } else {
                                                                                    guardar_asiento_contable();
                                                                                    $('#contado_form').prop('selected', true);
                                                                                    //                                                                                                guardar_retenciones_factura_compra_g();
//                                                                                    window.open("../../reportes/reporte_registo_gasto.php?hoja=A5&id=" + val, '_blank');
//                                                                                    window.open("../../reportes/transacciones_1.php?hoja=A5&id=" + val + '&gas=' + 'GAS', '_blank');
//                                                                                    location.reload();
                                                                                }

                                                                            }

                                                                    );





                                                                    //                                                                                }
                                                                    //
                                                                    //                                                                            }
                                                                    //
                                                                    //                                                                    );
                                                                }
                                                            }
                                                        });


                                                    }

                                                } else {

                                                    ///////////////guardar gastos///////////////////
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
                                                    //alertify.alert("hola");
                                                    var fil = jQuery("#list").jqGrid("getRowData");
                                                    for (var i = 0; i < fil.length; i++) {
                                                        var datos = fil[i];
                                                        v1[i] = datos['concepto'];
                                                        v2[i] = datos['id_plan'];
                                                        v3[i] = datos['cuenta_contable'];
                                                        v4[i] = datos['iva'];
                                                        v5[i] = datos['centro_costo'];
                                                        v6[i] = datos['valor'];
                                                        v7[i] = datos['bien_servicio'];
                                                        v8[i] = datos['id_centro_costo'];
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
                                                    var seriee = $("#factura").val();
                                                    observa = "Ninguna";
                                                    $("#btnGuardar").attr("disabled", true);
                                                    $.ajax({
                                                        type: "POST",
                                                        url: "guardar_gastos.php",
                                                        data: "id_gastos=" + $("#comprobante").val()
                                                                + "&num_factura=" + $("#factura").val()
                                                                + "&comprobante=" + $("#comprobante").val()
                                                                + "&fecha_actual=" + $("#fecha_actual").val()
                                                                + "&fecha_emision=" + $("#fecha_emision").val()
                                                                + "&hora_actual=" + $("#hora_actual").val()
                                                                + "&descripcion=" + $("#descripcion").val()
                                                                + "&valor=" + $("#totx").val()
                                                                + "&subtotal=" + $("#subx").val()
                                                                + "&iva=" + $("#ivax").val()
                                                                + "&proveedor=" + $("#id_proveedor").val()
                                                                + "&deposito=" + $("#deposito").val()
                                                                + "&banco=" + $("#banco").val()
                                                                + "&num_cuenta=" + $("#cuentanum").val()
                                                                + "&num_autorizacion=" + $("#autorizacion").val()
                                                                + "&campo1=" + string_v1
                                                                + "&idCuenta=" + $("#idCuenta").val()
                                                                + "&fecha_caducidad=" + $("#fecha_caducidad").val()
                                                                + "&tipo_comprobante=" + $("#tipo_comprobante").val()
                                                                + "&serie=" + seriee
                                                                + "&autorizacion=" + $("#autorizacion").val()
                                                                + "&cancelacion=" + $("#cancelacion").val()
                                                                + "&formas=" + forma_p
                                                                + "&tarifa0=" + $("#total_p").val()
                                                                + "&tarifa12=" + $("#total_p2").val()
                                                                + "&iva=" + $("#iva").val()
                                                                + "&desc=" + $("#desc").val()
                                                                + "&tot=" + $("#tot").val()
                                                                + "&campo1=" + string_v1
                                                                + "&campo2=" + string_v2
                                                                + "&campo3=" + string_v3
                                                                + "&campo4=" + string_v4
                                                                + "&campo5=" + string_v5
                                                                + "&campo6=" + string_v6
                                                                + "&observaciones=" + observa
                                                                + "&pago_ats=" + pago_ats
                                                                + "&bien_servi=" + bien_ser
                                                                + "&idCuenta=" + $("#idCuenta").val()
                                                                + "&formascc=" + $("#forma_pago").val()
                                                                + "&bien_servicio=" + $("#bien_servicio").val()
                                                                + "&campo7=" + string_v7
                                                                + "&campo8=" + string_v8
                                                                + "&tipo_comprobante=" + $("#tipo_comprobante").val(),
                                                        success: function (data) {
                                                            var val = data;
                                                            if (!Number.isNaN(Number(val))) {
                                                                if (Number(val) != 0) {
                                                                    $("#comprobante").val(Number(val));
                                                                }
                                                            }
                                                            if (val != 0) {
                                                                alertify.alert("Gasto Guardado correctamente");
                                                                //                                                                alertify.confirm("¿Desea ingresar retenciones?",
                                                                //                                                                        function (e) {
                                                                //                                                                            if (e) {
                                                                //
                                                                //                                                                                //                                                                        $("#comprobante").val(val);
                                                                //                                                                                $("#tipoRetencionesF").attr("disabled", false);
                                                                //                                                                                $('.nav-tabs a[href="#tab_2"]').tab('show');
                                                                //                                                                                $("#valor_reten").val("");
                                                                //
                                                                //                                                                            } else {
                                                                alertify.confirm("¿Desea ingresar formas de pago?",
                                                                        function (e) {
                                                                            if (e) {
                                                                                var subtotal_adelanto1 = (parseFloat($("#tot").val()));

                                                                                $("#valor_factura").val(subtotal_adelanto1.toFixed(2));
                                                                                $("#valor_reten").val("1");
                                                                                //                                                                            $("#comprobante").val(val);
                                                                                $('#otros_form').prop('selected', true);
                                                                                $('.nav-tabs a[href="#tab_4"]').tab('show')
                                                                                $("#formaspago_mixto").attr("disabled", false);
                                                                            } else {
                                                                                guardar_asiento_contable();
                                                                                $('#contado_form').prop('selected', true);
                                                                                //                                                                                                guardar_retenciones_factura_compra_g();
//                                                                                window.open("../../reportes/reporte_registo_gasto.php?hoja=A5&id=" + val, '_blank');
//                                                                                window.open("../../reportes/transacciones_1.php?hoja=A5&id=" + val + '&gas=' + 'GAS', '_blank');
//                                                                                location.reload();
                                                                            }

                                                                        }

                                                                );

                                                                //                                                                            }
                                                                //
                                                                //                                                                        }
                                                                //
                                                                //                                                                );
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
            }
        }
    }


}

function volver_rf() {
    $.ajax({
        type: "POST",
        url: "retornar_retencion_fuente.php",
        data: "id_gastos=" + $("#comprobante").val(),
        success: function (data) {
            var val = data;
            val = val.split("-");
            if (val[0] != "") {
                $("#tipoRetencionesF").val(val[1]);
                $("#calculoRetencionF").val(val[2]);
                $("#retencionF2").attr("disabled", true);
            } else {
                $("#tipoRetencionesF").val("0");
                $("#calculoRetencionF").val("0.000");
                $("#retencionF2").attr("disabled", false);
            }
        }
    });
}
function volver_ri() {
    $.ajax({
        type: "POST",
        url: "retornar_retencion_iva.php",
        data: "id_gastos=" + $("#comprobante").val(),
        success: function (data) {
            var val = data;
            val = val.split("-");
            if (val[0] != "") {
                $("#tipoRetencionesI").val(val[1]);
                $("#calculoRetencionI").val(val[2]);
                $("#retencionI2").attr("disabled", true);
            } else {
                $("#tipoRetencionesI").val("0");
                $("#calculoRetencionI").val("0.000");
                $("#retencionI2").attr("disabled", false);
            }
        }
    });
}
function flecha_atras() {
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "gastos" + "&id_tabla=" + "id_gastos" + "&tipo=" + 1,
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#comprobante").val(val);
                $("#comprobante2").val(val);
                var valor = $("#comprobante").val();
                // llamar datos Factura Compra
                $("#btnGuardar").attr("disabled", true);
                //                $("#btnGuardarRetenciones").attr("disabled", true);
                $("#list").jqGrid("clearGridData", true);
                $("#listPagoreten").jqGrid("clearGridData", true);
                //$("#estado h3").remove();
                $.getJSON('buscar_gastos.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 24) {
                            $("#comprobante").val(data[i]);
                            $("#factura").val(data[i + 1]);
                            $("#comprobante2").val(data[i + 2]);
                            $("#fecha_actual").val(data[i + 3]);
                            $("#hora_actual").val(data[i + 4]);
                            $("#fecha_emision").val(data[i + 5]);

                            $("#descripcion").val(data[i + 6]);
                            $("#subtotal").val(data[i + 7]);
                            $("#iva").val(data[i + 8]);
                            //                            $("#valor").val(data[i + 9]);

                            if (data[i + 10] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulada"));
                                $("#estado h3").css("color", "red");
                            } else {
                                $("#estado h3").remove();
                            }
                            $("#id_proveedor").val(data[i + 11]);
                            $("#tipo_docu").val(data[i + 12]);
                            $("#ruc_ci").val(data[i + 13]);
                            $("#empresa").val(data[i + 14]);
                            $("#deposito").val(data[i + 15]);
                            $("#banco").val(data[i + 16]);
                            $("#cuentanum").val(data[i + 17]);
                            $("#autorizacion").val(data[i + 18]);

                            // $("#total_px").val(parseFloat(data[i + 17]).toFixed(2));
                            $("#total_px").val(parseFloat(data[i + 19]).toFixed(2));
                            $("#total_p2x").val(parseFloat(data[i + 20]).toFixed(2));
                            $("#subx").val((parseFloat(data[i + 19]) + parseFloat(data[i + 20])).toFixed(2));
                            $("#ivax").val(parseFloat(data[i + 21]).toFixed(2));
                            $("#descx").val(parseFloat(data[i + 22]).toFixed(2));
                            $("#totx").val(parseFloat(data[i + 23]).toFixed(2));


                        }
                        volver_rf();
                        volver_ri();
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "extraer_cuenta_producto.php",
                    data: "id_gasto=" + valor,
                    success: function (data) {
                        var val = data;
                        if (val != "") {
                            var vec = val.split("/");
                            $("#idCuenta").val(vec[0]);
                            $("#cuenta_contable").val(vec[1] + "  -  " + vec[2]);
                        }
                    }
                });



                // Fin

                $("#list").jqGrid("clearGridData", true);
                $.getJSON('buscar_gastos2.php?com=' + valor, function (data) {
                    var tama = data.length;
                    var descuento = 0;
                    var total = 0;
                    var su = 0;
                    var precio = 0;
                    var multi = 0;
                    var flotante = 0;
                    var resultado = 0;

                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 7) {
                            //                            desc = data[i + 5];
                            precio = parseFloat(data[i + 5]);
                            //                            multi = parseFloat(data[i + 3]) * parseFloat(data[i + 4]);
                            descuento = (multi * parseFloat(desc)) / 100;
                            //                            flotante = parseFloat(descuento);
                            //                            resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                            //                            total = multi - resultado;

                            var datarow = {
                                concepto: data[i],
                                id_plan: data[i + 1],
                                cuenta_contable: data[i + 2],
                                iva: data[i + 3],
                                centro_costo: data[i + 4],
                                valor: data[i + 5],
                                bien_servicio: data[i + 6]
                            };

                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });

                $.getJSON('retornar_retenciones_grid.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 6) {

                            var datarow = {
                                base_imponible: data[i],
                                impuesto: data[i + 1],
                                porcent_reten: data[i + 2],
                                valor_retenido: data[i + 3]

                            };

                            var num = data[i + 5];
                            var res = num.substr(8, 20)
                            $("#serie_retencion").val(res);

                            var su = jQuery("#listPagoreten").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });
                $.getJSON('retornar_formas_mixto_grid.php?com=' + valor, function (data) {
                    $("#listPagoreten_mixto").jqGrid("clearGridData", true);
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 5) {
                            var datarow = {
                                forma_pago_mixto: data[i],
                                tarjeta_credito: data[i + 1],
                                num_documento: data[i + 2],
                                valor: data[i + 3],
                                id_cuenta: data[i + 4]
                            };
                            var su = jQuery("#listPagoreten_mixto").jqGrid('addRowData', data[i], datarow);
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
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "gastos" + "&id_tabla=" + "id_gastos" + "&tipo=" + 2,
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#comprobante").val(val);
                $("#comprobante2").val(val);
                var valor = $("#comprobante").val();
                // llamar datos Factura Compra
                $("#btnGuardar").attr("disabled", true);
                //                $("#btnGuardarRetenciones").attr("disabled", true);
                $("#list").jqGrid("clearGridData", true);
                $("#listPagoreten").jqGrid("clearGridData", true);
                //$("#estado h3").remove();
                $.getJSON('buscar_gastos.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 24) {
                            $("#comprobante").val(data[i]);
                            $("#factura").val(data[i + 1]);
                            $("#comprobante2").val(data[i + 2]);
                            $("#fecha_actual").val(data[i + 3]);
                            $("#hora_actual").val(data[i + 4]);
                            $("#fecha_emision").val(data[i + 5]);

                            $("#descripcion").val(data[i + 6]);
                            $("#subtotal").val(data[i + 7]);
                            $("#iva").val(data[i + 8]);
                            //                            $("#valor").val(data[i + 9]);

                            if (data[i + 10] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulada"));
                                $("#estado h3").css("color", "red");
                            } else {
                                $("#estado h3").remove();
                            }

                            $("#id_proveedor").val(data[i + 11]);
                            $("#tipo_docu").val(data[i + 12]);
                            $("#ruc_ci").val(data[i + 13]);
                            $("#empresa").val(data[i + 14]);
                            $("#deposito").val(data[i + 15]);
                            $("#banco").val(data[i + 16]);
                            $("#cuentanum").val(data[i + 17]);
                            $("#autorizacion").val(data[i + 18]);
                            $("#total_px").val(parseFloat(data[i + 19]).toFixed(2));
                            $("#total_p2x").val(parseFloat(data[i + 20]).toFixed(2));
                            $("#subx").val((parseFloat(data[i + 19]) + parseFloat(data[i + 20])).toFixed(2));
                            $("#ivax").val(parseFloat(data[i + 21]).toFixed(2));
                            $("#descx").val(parseFloat(data[i + 22]).toFixed(2));
                            $("#totx").val(parseFloat(data[i + 23]).toFixed(2));

                        }
                        volver_rf();
                        volver_ri();
                    }
                });
                $("#list").jqGrid("clearGridData", true);
                $.getJSON('buscar_gastos2.php?com=' + valor, function (data) {
                    var tama = data.length;
                    var descuento = 0;
                    var total = 0;
                    var su = 0;
                    var precio = 0;
                    var multi = 0;
                    var flotante = 0;
                    var resultado = 0;

                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 7) {
                            //                            desc = data[i + 5];
                            precio = parseFloat(data[i + 5]);
                            //                            multi = parseFloat(data[i + 3]) * parseFloat(data[i + 4]);
                            //                            descuento = (multi * parseFloat(desc)) / 100;
                            //                            flotante = parseFloat(descuento);
                            //                            resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                            //                            total = multi - resultado;

                            var datarow = {
                                concepto: data[i],
                                id_plan: data[i + 1],
                                cuenta_contable: data[i + 2],
                                iva: data[i + 3],
                                centro_costo: data[i + 4],
                                valor: data[i + 5],
                                bien_servicio: data[i + 6]
                            };

                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });
                $.getJSON('retornar_retenciones_grid.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 6) {

                            var datarow = {
                                base_imponible: data[i],
                                impuesto: data[i + 1],
                                porcent_reten: data[i + 2],
                                valor_retenido: data[i + 3]

                            };

                            var num = data[i + 5];
                            var res = num.substr(8, 20)
                            $("#serie_retencion").val(res);

                            var su = jQuery("#listPagoreten").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });
                $.getJSON('retornar_formas_mixto_grid.php?com=' + valor, function (data) {
                    $("#listPagoreten_mixto").jqGrid("clearGridData", true);
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 5) {
                            var datarow = {
                                forma_pago_mixto: data[i],
                                tarjeta_credito: data[i + 1],
                                num_documento: data[i + 2],
                                valor: data[i + 3],
                                id_cuenta: data[i + 4]
                            };
                            var su = jQuery("#listPagoreten_mixto").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });

                // Fin
            } else {
                alertify.alert("No hay más registros superiores!!");
            }
        }
    });
    //    jQuery(window).bind('resize', function () {
    //    jQuery("#list").setGridWidth(jQuery('#grid_container').width(), true);
    //}).trigger('resize');
}
////////////////////buscador proformas tecnico/////////////////////////
jQuery("#list7").jqGrid({
    url: 'xmlBuscarEstadosRetencion.php',
    datatype: 'xml',
    colNames: ['ID', 'NUM_GASTO', 'NUM SERIE RETEN.', 'FECHA', 'PROVEEDOR', 'N° AUTORIZACIÓN', 'TOTAL', 'ESTADO', 'ACCIÓN', 'ENVIO XML', 'CONSULTA COMPROBANTE'],
    colModel: [
        {name: 'id_retencion_fuente_factura_compra', index: 'id_retencion_fuente_factura_compra', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 50},
        {name: 'num_factura', index: 'num_factura', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 100},
        {name: 'num_serie', index: 'num_serie', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 100},

        {name: 'fecha', index: 'fecha', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 100},
        {name: 'proveedor', index: 'proveedor', editable: true, search: true, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 100},
        {name: 'autorizacion', index: 'autorizacion', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 100},
        {name: 'total', index: 'total', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 50},
        {name: 'estado', index: 'estado', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 80},
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
    width: 1300,
    height: 220,
    sortable: true,
    rowList: [10, 20, 30],
    pager: jQuery('#pager7'),
    sortname: 'id_retencion_fuente_factura_compra',
    sortorder: 'desc',
    viewrecords: true,
    gridComplete: function () {
        var ids = jQuery("#list7").jqGrid('getDataIDs');
        for (var i = 0; i < ids.length; i++) {
            var ids = jQuery("#list7").getDataIDs();
            for (var i = 0; i < ids.length; i++) {
                var id_factura = ids[i];
                var datosr = jQuery('#list7').getRowData(id_factura);
                if (datosr.estado == "NO AUTORIZADO") {
                    be = "<i class='fa fa-envelope-o' style='cursor:not-allowed;' title='Para enviar el correo primero debe autorizar la retención'> CORREO</i>";
                    jQuery("#list7").jqGrid('setRowData', ids[i], {
                        accion: be
                    });
                } else {
                    be = "<a  onclick=\"reenviar('" + id_factura + "')\" title='Reenviar Correo' ><i class='fa fa-envelope-o' style='cursor:pointer; cursor: hand'> CORREO</i></a>";
                    jQuery("#list7").jqGrid('setRowData', ids[i], {
                        accion: be
                    });
                }

            }
        }
        for (var i = 0; i < ids.length; i++) {
            var ids = jQuery("#list7").getDataIDs();

            for (var i = 0; i < ids.length; i++) {
                var id_factura = ids[i];
                be = "<a  onclick=\"enviarXml('" + id_factura + "')\" title='Reenviar Xml' ><i class='fa fa-arrow-circle-right' style='cursor:pointer; cursor: hand'> Envio Xml</i></a>";
                jQuery("#list7").jqGrid('setRowData', ids[i], {
                    envio: be
                });
            }
        }

        for (var i = 0; i < ids.length; i++) {
            var ids = jQuery("#list7").getDataIDs();

            for (var i = 0; i < ids.length; i++) {
                var id_factura = ids[i];
                be = "<a  onclick=\"reenviarXml('" + id_factura + "')\" title='Reenviar Xml' ><i class='fa fa-repeat' style='cursor:pointer; cursor: hand'> Consulta Xml</i></a>";
                jQuery("#list7").jqGrid('setRowData', ids[i], {
                    reenvio: be
                });
            }
        }
    },
    ondblClickRow: function () {
        var id = jQuery("#list7").jqGrid('getGridParam', 'selrow');
        jQuery('#list7').jqGrid('restoreRow', id);

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
jQuery("#list7").jqGrid('navButtonAdd', '#pager7', {
    caption: "Reeviar",
    onClickButton: function () {
        var id = jQuery("#list7").jqGrid('getGridParam', 'selrow');
        jQuery('#list7').jqGrid('restoreRow', id);
        if (id) {
            var ret = jQuery("#list7").jqGrid('getRowData', id);
        }

    }
});

function obtenerCentrosCostos() {
    return $.ajax({
        url: "../centro_costos/retornar_centros_costos.php",
        method: "GET",
        dataType: "json"
    });
}

function llenarCentrosCosto() {
    $("#sel_centro_costo").empty();
    $("#sel_centro_costo").append(`<option value="">---Seleccione---</option>`);
    obtenerCentrosCostos().then(function (data) {
        data.forEach(el => {
            $("#sel_centro_costo").append(`<option value="${el.id_centro_costo}">${el.nombre}</option>`);
        });
    });
}
function serie_inicial_reten() {
    if ($("#serie_retencion").val() == "") {
        console.log("en");
        $("#serie_retencion").val("000000001");
        //        alertify.error("Ingrese número de la Retenciòn");
    }
}
function addCentroCostoRowData(row) {
    if ($("#sel_centro_costo").val() > 0) {
        row["id_centro_costo"] = $("#sel_centro_costo").val();
        row["centro_costo_1"] = $("#sel_centro_costo")[0].options[$("#sel_centro_costo")[0].selectedIndex].text;
    }
}