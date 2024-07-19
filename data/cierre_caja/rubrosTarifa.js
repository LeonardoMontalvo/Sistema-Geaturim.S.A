$(document).on("ready", inicio);
$(document).keydown(function (e) {
    var e = e || event;
    var keycode = e.which || e.keyCode;
    var obj = e.target || e.srcElement;

    if (keycode == 45) {
        abrirDialogo()
    }
    // Tecla Control Cliente
    //   if(keycode == 13) { agregar()}
    //   if(keycode == 39) { guardar_serie()}
    if (keycode == 27) {
        cancelar()
    }

});
function evento(e) {
    e.preventDefault();
}
var dialogo2 = {
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 355,
    modal: true,
    // position: "top",
    show: "explode",
    hide: "blind"
}

var dialogo3 = {
    autoOpen: false,
    resizable: false,
    width: 500,
    height: 250,
    modal: true,
    show: "explode",
    hide: "blind"
}
var dialogo4 = {
    autoOpen: false,
    resizable: false,
    width: 300,
    height: 150,
    modal: true,
    show: "explode",
    hide: "blind"

}

var dialogo6 = {
    autoOpen: false,
    resizable: false,
    width: 350,
    height: 180,
    modal: true,
    // position: "top",
    show: "explode",
    hide: "blind"
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
var dialogo = {
    autoOpen: false,
    resizable: false,
    width: 1000,
    height: 500,
    modal: true
};
function dialogoBuscar() {
    var dialogo2 = {
        autoOpen: false,
        resizable: false,
        width: 800,
        height: 355,
        modal: true,
        // position: "top",
        show: "explode",
        hide: "blind"
    }
    $("#buscar_inventario").dialog(dialogo2);
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
    if (hours == 0)
        hours = 12;
    if (minutes <= 9)
        minutes = "0" + minutes;
    if (seconds <= 9)
        seconds = "0" + seconds;
    $("#hora_actual").val(hours + ":" + minutes + ":" + seconds + " " + dn);
    setTimeout("show()", 1000);
}

//function enter(e) {
//    if (e.which == 13 || e.keyCode === 13) {
//        entrar();
//        return false;
//    }
//    return true;
//}

function enter2(e) {
    if (e.which == 13 || e.keyCode === 13) {
        entrar2();
        return false;
    }
    return true;
}
function enter3(e) {
    if (e.which == 13 || e.keyCode === 13) {
        entrar3();
        return false;
    }
    return true;
}
function datePicker(id) {
    $("#" + id).datepicker({
        dateFormat: 'yy-mm-dd',

    });
    //.datepicker('setDate', 'today');
}
function datePicker1(id) {
    $("#" + id).datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: 1
    });
    //.datepicker('setDate', 'today');
}


function validar_acceso() {
    if ($("#clave").val() == "") {
        $("#clave").focus();
        alertify.alert("Ingrese la clave");
    } else {

        $.ajax({
            url: 'validar_acceso.php',
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
function anular_factura() {
    $("#clave_permiso").dialog("open");
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





//function entrar() {
//    if ($("#denominacion").val() == "") {
//        $("#denominacion").focus();
//        alertify.error("Ingrese denominacion");
//    } else {
//        $("#cantidad").focus();
//    }
//}
function activar_boton() {
    var tipo_tarifa = $("#tipo_tarifa").val();
    //         console.log("dataas"+tipo_tarifa);
    $.ajax({
        type: "POST",
        url: "xmlBuscarRubro_consult.php?id_clase=" + tipo_tarifa,
        data: "",
        success: function (data) {
            var val = data;
            console.log("dataas" + val);
            if (val != "") {
                $("#id_tipo_tarifa").val(val)
                $("#btnGuardar").attr("disabled", true);
            } else {
                $("#id_tipo_tarifa").val("")
                $("#btnGuardar").attr("disabled", false);
            }
        }
    });

}
//function cargar_rubro_tarifa() {
//
//    var tipo_tarifa = $("#tipo_tarifa").val();
//
//    $("#list").jqGrid('setGridParam', {
//        url: 'xmlCierreCaja.php?id_clase=' + tipo_tarifa,
//        datatype: 'xml',
//    }).trigger('reloadGrid');
//
//    activar_boton();
//
//
//}

function limpiar_input() {
    $("#denominacion").val("");
    $("#cantidad").val("");
    $("#valor").val("");
}

function ValidNum() {
    if (event.keyCode < 48 || event.keyCode > 57) {
        event.returnValue = false;
    }
    return true;
}

function numFormatter(d) {
    return new Intl.NumberFormat("en-US", {
        minimumFractionDigits: d,
        maximumFractionDigits: d,
        useGrouping: false,
    });
}
function entrar2() {
    var suma_cien = 0;
    var suma_cincuenta = 0;
    var suma_veinte = 0;
    var suma_diez = 0;
    var suma_cinco = 0;
    var suma_uno = 0;
    var suma_cero_cincuenta = 0;
    var suma_cero_veinticinco = 0;
    var suma_cero_diez = 0;
    var suma_cero_cinco = 0;
    var suma_cero_uno = 0;

    if ($("#cantidad_cien").val() != "") {
        suma_cien = parseFloat($("#cantidad_cien").val()) * parseFloat($("#valor_cien").val());
        $("#total_cien").val(numFormatter(2).format(suma_cien));
    }
    if ($("#cantidad_cincuenta").val() != "") {
        suma_cincuenta = parseFloat($("#cantidad_cincuenta").val()) * parseFloat($("#valor_cincuenta").val());
        $("#total_cincuenta").val(numFormatter(2).format(suma_cincuenta));
    }
    if ($("#cantidad_veinte").val() != "") {
        suma_veinte = parseFloat($("#cantidad_veinte").val()) * parseFloat($("#valor_veinte").val());
        $("#total_veinte").val(numFormatter(2).format(suma_veinte));
    }

    if ($("#cantidad_diez").val() != "") {
        suma_diez = parseFloat($("#cantidad_diez").val()) * parseFloat($("#valor_diez").val());
        $("#total_diez").val(numFormatter(2).format(suma_diez));
    }
    if ($("#cantidad_cinco").val() != "") {
        suma_cinco = parseFloat($("#cantidad_cinco").val()) * parseFloat($("#valor_cinco").val());
        $("#total_cinco").val(numFormatter(2).format(suma_cinco));
    }

    if ($("#cantidad_uno").val() != "") {
        suma_uno = parseFloat($("#cantidad_uno").val()) * parseFloat($("#valor_uno").val());
        $("#total_uno").val(numFormatter(2).format(suma_uno));
    }
    if ($("#cantidad_cero_cincuenta").val() != "") {
        suma_cero_cincuenta = parseFloat($("#cantidad_cero_cincuenta").val()) * parseFloat($("#valor_cero_cincuenta").val());
        $("#total_cero_cincuenta").val(numFormatter(2).format(suma_cero_cincuenta));
    }

    if ($("#cantidad_cero_veinticinco").val() != "") {
        suma_cero_veinticinco = parseFloat($("#cantidad_cero_veinticinco").val()) * parseFloat($("#valor_cero_veinticinco").val());
        $("#total_cero_veinticinco").val(numFormatter(2).format(suma_cero_veinticinco));
    }
    if ($("#cantidad_cero_diez").val() != "") {
        suma_cero_diez = parseFloat($("#cantidad_cero_diez").val()) * parseFloat($("#valor_cero_diez").val());
        $("#total_cero_diez").val(numFormatter(2).format(suma_cero_diez));
    }

    if ($("#cantidad_cero_cinco").val() != "") {
        suma_cero_cinco = parseFloat($("#cantidad_cero_cinco").val()) * parseFloat($("#valor_cero_cinco").val());
        $("#total_cero_cinco").val(numFormatter(2).format(suma_cero_cinco));
    }

    if ($("#cantidad_cero_uno").val() != "") {
        suma_cero_uno = parseFloat($("#cantidad_cero_uno").val()) * parseFloat($("#valor_cero_uno").val());
        $("#total_cero_uno").val(numFormatter(2).format(suma_cero_uno));
    }

    $("#total_valor").val(parseFloat(suma_cien + suma_cincuenta + suma_veinte + suma_diez + suma_cinco + suma_uno + suma_cero_cincuenta + suma_cero_veinticinco + suma_cero_diez + suma_cero_cinco + suma_cero_uno /*+ parseFloat($("#monto_apertura").val())*/));

}

function entrar3() {
    agregar();
}
function aceptar() {
    $.ajax({
        type: "POST",
        url: "eliminar_cierre_caja.php",
        data: "comprobante=" + $("#comprobante").val(),
        success: function (data) {
            var val = data;
            if (val == 0) {
                alertify.error('Eliminado correctamente');
                setTimeout(function () {
                    location.reload();
                }, 1000);
            }
        }
    });
}
function actualizar_Proveedor() {
    $.ajax({
        type: "POST",
        url: 'comprobar_diario_caja.php',

        data: "valor",
        success: function (data) {
            var val = data;

            if (val != "") {
                //                var valor_dj = parseFloat(val).toFixed(4);
                $("#diario_caja_text").val(val);



            }
        }
    });
}
function guardar_cierre_caja() {

    if (comprobante == 0) {
        alertify.alert("<b>Seleccione una caja abierta para hacer el cierre.</b>", function () {
            $("#btn_buscar_cajas_abiertas").focus();
        });
        return;
    }

    if ($("#total_valor").val() == "0.000") {
        alertify.error("Error...debe registrar algun valor");
        $("#cantidad_cien").focus();
    } else {
        if ($("#total_cien").val() == "" && $("#cantidad_cien").val() != "") {
            alertify.error("Error...debe hacer enter en denominacion CIEN");
            $("#cantidad_cien").focus();
        } else {
            if ($("#total_cincuenta").val() == "" && $("#cantidad_cincuenta").val() != "") {
                alertify.error("Error...debe hacer enter en denominacion CINCUENTA");
                $("#cantidad_cincuenta").focus();
            } else {
                if ($("#total_veinte").val() == "" && $("#cantidad_veinte").val() != "") {
                    alertify.error("Error...debe hacer enter en denominacion veinte");
                    $("#cantidad_veinte").focus();
                } else {
                    if ($("#total_diez").val() == "" && $("#cantidad_diez").val() != "") {
                        alertify.error("Error...debe hacer enter en denominacion diez");
                        $("#cantidad_diez").focus();
                    } else {

                        if ($("#total_cinco").val() == "" && $("#cantidad_cinco").val() != "") {
                            alertify.error("Error...debe hacer enter en denominacion _cinco");
                            $("#cantidad_cinco").focus();
                        } else {

                            if ($("#total_uno").val() == "" && $("#cantidad_uno").val() != "") {
                                alertify.error("Error...debe hacer enter en denominacion _uno");
                                $("#cantidad_uno").focus();
                            } else {

                                if ($("#total_cero_cincuenta").val() == "" && $("#cantidad_cero_cincuenta").val() != "") {
                                    alertify.error("Error...debe hacer enter en denominacion _cero_cincuenta");
                                    $("#cantidad_cero_cincuenta").focus();
                                } else {

                                    if ($("#total_cero_veinticinco").val() == "" && $("#cantidad_cero_veinticinco").val() != "") {
                                        alertify.error("Error...debe hacer enter en denominacion _cero_veinticinco");
                                        $("#cantidad_cero_veinticinco").focus();
                                    } else {

                                        if ($("#total_cero_diez").val() == "" && $("#cantidad_cero_diez").val() != "") {
                                            alertify.error("Error...debe hacer enter en denominacion _cero_diez");
                                            $("#cantidad_cero_diez").focus();
                                        } else {

                                            if ($("#total_cero_cinco").val() == "" && $("#cantidad_cero_cinco").val() != "") {
                                                alertify.error("Error...debe hacer enter en denominacion _cero_cinco");
                                                $("#cantidad_cero_cinco").focus();
                                            } else {

                                                if ($("#total_cero_uno").val() == "" && $("#cantidad_cero_uno").val() != "") {
                                                    alertify.error("Error...debe hacer enter en denominacion _cero_uno");
                                                    $("#cantidad_cero_uno").focus();
                                                } else {

                                                    if ($("#observaciones").val() == "") {
                                                        alertify.error("Error...debe llenar las observaciones");
                                                        $("#observaciones").focus();
                                                        return;
                                                    }

                                                    $("#btnGuardar").attr("disabled", true);
                                                    $.ajax({
                                                        type: "POST",
                                                        url: "guardar_diario_caja.php",
                                                        dataType: "json",
                                                        data: "fecha_actual=" + $("#fecha_actual").val() + "&hora_actual=" + $("#hora_actual").val() + "&observaciones=" + $("#observaciones").val() + "&total_valor=" + $("#total_valor").val() + "&diario_caja_text=" + $("#diario_caja_text").val() +
                                                            "&denominacion_cien=" + $("#denominacion_cien").val() +
                                                            "&cantidad_cien=" + $("#cantidad_cien").val() +
                                                            "&valor_cien=" + $("#valor_cien").val() +
                                                            "&total_cien=" + $("#total_cien").val() +
                                                            "&denominacion_cincuenta=" + $("#denominacion_cincuenta").val() +
                                                            "&cantidad_cincuenta=" + $("#cantidad_cincuenta").val() +
                                                            "&valor_cincuenta=" + $("#valor_cincuenta").val() +
                                                            "&total_cincuenta=" + $("#total_cincuenta").val() +
                                                            "&denominacion_veinte=" + $("#denominacion_veinte").val() +
                                                            "&cantidad_veinte=" + $("#cantidad_veinte").val() +
                                                            "&valor_veinte=" + $("#valor_veinte").val() +
                                                            "&total_veinte=" + $("#total_veinte").val() +
                                                            "&denominacion_diez=" + $("#denominacion_diez").val() +
                                                            "&cantidad_diez=" + $("#cantidad_diez").val() +
                                                            "&valor_diez=" + $("#valor_diez").val() +
                                                            "&total_diez=" + $("#total_diez").val() +
                                                            "&denominacion_cinco=" + $("#denominacion_cinco").val() +
                                                            "&cantidad_cinco=" + $("#cantidad_cinco").val() +
                                                            "&valor_cinco=" + $("#valor_cinco").val() +
                                                            "&total_cinco=" + $("#total_cinco").val() +
                                                            "&denominacion_uno=" + $("#denominacion_uno").val() +
                                                            "&cantidad_uno=" + $("#cantidad_uno").val() +
                                                            "&valor_uno=" + $("#valor_uno").val() +
                                                            "&total_uno=" + $("#total_uno").val() +
                                                            "&denominacion_cero_cincuenta=" + $("#denominacion_cero_cincuenta").val() +
                                                            "&cantidad_cero_cincuenta=" + $("#cantidad_cero_cincuenta").val() +
                                                            "&valor_cero_cincuenta=" + $("#valor_cero_cincuenta").val() +
                                                            "&total_cero_cincuenta=" + $("#total_cero_cincuenta").val() +
                                                            "&denominacion_cero_veinticinco=" + $("#denominacion_cero_veinticinco").val() +
                                                            "&cantidad_cero_veinticinco=" + $("#cantidad_cero_veinticinco").val() +
                                                            "&valor_cero_veinticinco=" + $("#valor_cero_veinticinco").val() +
                                                            "&total_cero_veinticinco=" + $("#total_cero_veinticinco").val() +
                                                            "&denominacion_cero_diez=" + $("#denominacion_cero_diez").val() +
                                                            "&cantidad_cero_diez=" + $("#cantidad_cero_diez").val() +
                                                            "&valor_cero_diez=" + $("#valor_cero_diez").val() +
                                                            "&total_cero_diez=" + $("#total_cero_diez").val() +
                                                            "&denominacion_cero_cinco=" + $("#denominacion_cero_cinco").val() +
                                                            "&cantidad_cero_cinco=" + $("#cantidad_cero_cinco").val() +
                                                            "&valor_cero_cinco=" + $("#valor_cero_cinco").val() +
                                                            "&total_cero_cinco=" + $("#total_cero_cinco").val() +
                                                            "&denominacion_cero_uno=" + $("#denominacion_cero_uno").val() +
                                                            "&cantidad_cero_uno=" + $("#cantidad_cero_uno").val() +
                                                            "&valor_cero_uno=" + $("#valor_cero_uno").val() +
                                                            "&total_cero_uno=" + $("#total_cero_uno").val() +
                                                            "&monto_apertura=" + $("#monto_apertura").val() +
                                                            "&comprobante=" + comprobante +
                                                            "&valor_transferencia=" + $("#valor_transferencia").val()
                                                        ,
                                                        success: function (data) {
                                                            var val = data;
                                                            if (val > 0) {
                                                                alertify.alert("Cierre de caja Guardado correctamente", function () {
                                                                    var myWindow = window.open("../../reportes/cierre_caja_ant.php?hoja=A4&id=" + $("#comprobante").val(), '_blank');
                                                                    myWindow.focus();
                                                                    myWindow.print();

                                                                    /* var myWindow = window.open("../../reportes/prod_rel_cantidad.php?id=" + $("#comprobante").val(), '_blank'); */
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
                        }
                    }
                }
            }
        }
    }
}

function modificar_rubro_tarifa() {


    if ($("#tipo_tarifa").val() != '0') {
        $("#btnGuardar").attr("disabled", true);
        console.log("sasa" + $("#tipo_tarifa").val());
        var v1 = new Array();
        var v2 = new Array();
        var v3 = new Array();
        var v4 = new Array();


        var string_v1 = "";
        var string_v2 = "";
        var string_v3 = "";
        var string_v4 = "";



        var valor3 = "";
        var valor4 = "";



        var fil = jQuery("#list").jqGrid("getRowData");

        for (var i = 0; i < fil.length; i++) {
            var datos = fil[i];
            v1[i] = datos['id_denominacion'];
            v2[i] = datos['denominacion'];
            v3[i] = datos['cantidad'];
            v4[i] = datos['valor'];


            var cadena3 = v3[i];
            var result3 = cadena3.substr(7, 4);

            var cadena4 = v4[i];
            var result4 = cadena4.substr(7, 4);
            console.log("result3" + result4);
            var cadena5 = v3[i];
            var result5 = cadena5.substr(7, 4);

            var cadena6 = v3[i];
            var result6 = cadena6.substr(7, 4);

            if (result3 == 'type') {
                valor3 = true;
            }
            if (result4 == 'type') {
                valor4 = true;
            }

        }
        for (i = 0; i < fil.length; i++) {
            string_v1 = string_v1 + "|" + v1[i];
            string_v2 = string_v2 + "|" + v2[i];
            string_v3 = string_v3 + "|" + v3[i];
            string_v4 = string_v4 + "|" + v4[i];

        }

        if (valor3 == true) {
            alertify.error('Hacer click Enter ');
        } else {
            if (valor4 == true) {
                alertify.error('Hacer click  Enter ');
            } else {

                if (valor5 == true) {
                    alertify.error('Hacer click  Enter ');
                } else {
                    if (valor6 == true) {
                        alertify.error('Hacer click  Enter ');
                    } else {


                        console.log("valor3" + valor3);
                        $.ajax({
                            type: "POST",
                            url: "modificar_cierre_caja.php",
                            data: "fecha_actual=" + $("#fecha_actual").val() + "&hora_actual=" + $("#hora_actual").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4,
                            success: function (data) {
                                var val = data;
                                if (val == 1) {
                                    alertify.alert(" Modificado correctamente", function () {
                                    });
                                }
                            }
                        });

                    }
                }
            }
        }


    } else {
        alertify.error("Error... ");
    }
}



function nuevo() {
    location.reload();
}
function cancelar() {
    $("#list2").jqGrid("clearGridData", true);
    $("#series").dialog("close");

    $("#btnAgregar").attr("disabled", false);
}

function limpiar_campo1() {
    $("#denominacion").val("");
    $("#cantidad").val("");
    $("#valor").val("");

}
function cargar_rubro_tarifa() {


    console.log("ffdfdsfgdafgdsfg");
    $("#list").jqGrid('setGridParam', {
        url: "xmlBuscarRubro.php",
        datatype: 'xml',
    }).trigger('reloadGrid');

    activar_boton();


}
function activar_boton() {

    $.ajax({
        type: "POST",
        url: 'xmlBuscarRubro_consult.php',
        data: "",
        success: function (data) {
            var val = data;
            console.log("dataas" + val);
            if (val != "") {
                $("#comprobante").val(val);
                $("#btnGuardar").attr("disabled", true);
            } else {

                $("#btnGuardar").attr("disabled", false);
            }
        }
    });

}
function inicializarCierreCaja() {
    //    $("#diario_caja").val("");
    //1
    $.ajax({
        type: "POST",
        url: 'comprobar_diario_caja.php',

        data: "valor",
        success: function (data) {
            var val = data;

            if (val != "") {
                console.log(val + "nn");
                //                    valores = val.split("*");
                $("#diario_caja").val(val);



            }
        }
    });
}
function listaBuscar(list) {
    jQuery(list).jqGrid({
        url: 'xmlBuscarInventario.php',
        datatype: 'xml',
        colNames: ['ID CIERRE.', 'COMPROBANTE', 'USUARIO', 'FECHA', 'HORA', 'TOTAL DINERO EN CAJA', 'TOTAL DIARIO CAJA', 'OBSERVACION CIERRE'],
        colModel: [
            { name: 'id_cierre', index: 'id_cierre', editable: false, search: true, hidden: true, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },
            { name: 'comprobante', index: 'comprobante', editable: false, search: true, hidden: true, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'usuario', index: 'usuario', editable: false, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'fecha_cierre', index: 'fecha_cierre', editable: false, search: true, hidden: false, editrules: { edithidden: false }, align: 'left', frozen: true, width: 100 },
            { name: 'hora_cierre', index: 'hora_cierre', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'diario_caja', index: 'diario_caja', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 200 },

            { name: 'total', index: 'total', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'observacion_cierre', index: 'observacion_cierre', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 }
        ],
        rowNum: 30,
        width: 750,
        height: 220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#listPager'),
        sortname: 'id_cierre',
        sortorder: 'desc',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery(list).jqGrid('getGridParam', 'selrow');
            jQuery(list).jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery(list).jqGrid('getRowData', id);
                var valor = ret.comprobante;
                $("#comprobante").val(valor);
                var valor_costo = 0;
                var valor_venta = 0;
                var valor = $("#comprobante").val();
                //llamar inventario primera parte/////
                $("#btnGuardar").attr("disabled", true);
                $("#list").jqGrid("clearGridData", true);

                mostrarFormularioCierre();
                $.getJSON("retornar_inventario2.php?com=" + valor, function (data) {
                    var tama = data.length;
                    t = data[48];
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 50) {
                            $("#denominacion_cien").val(data[i]);
                            $("#cantidad_cien").val(data[i + 1]);
                            $("#valor_cien").val(data[i + 2]);
                            $("#total_cien").val(data[i + 3]);

                            $("#denominacion_cincuenta").val(data[i + 4]);
                            $("#cantidad_cincuenta").val(data[i + 5]);
                            $("#valor_cincuenta").val(data[i + 6]);
                            $("#total_cincuenta").val(data[i + 7]);

                            $("#denominacion_veinte").val(data[i + 8]);
                            $("#cantidad_veinte").val(data[i + 9]);
                            $("#valor_veinte").val(data[i + 10]);
                            $("#total_veinte").val(data[i + 11]);

                            $("#denominacion_diez").val(data[i + 12]);
                            $("#cantidad_diez").val(data[i + 13]);
                            $("#valor_diez").val(data[i + 14]);
                            $("#total_diez").val(data[i + 15]);

                            $("#denominacion_cinco").val(data[i + 16]);
                            $("#cantidad_cinco").val(data[i + 17]);
                            $("#valor_cinco").val(data[i + 18]);
                            $("#total_cinco").val(data[i + 19]);

                            $("#denominacion_uno").val(data[i + 20]);
                            $("#cantidad_uno").val(data[i + 21]);
                            $("#valor_uno").val(data[i + 22]);
                            $("#total_uno").val(data[i + 23]);

                            $("#denominacion_cero_cincuenta").val(data[i + 24]);
                            $("#cantidad_cero_cincuenta").val(data[i + 25]);
                            $("#valor_cero_cincuenta").val(data[i + 26]);
                            $("#total_cero_cincuenta").val(data[i + 27]);

                            $("#denominacion_cero_veinticinco").val(data[i + 28]);
                            $("#cantidad_cero_veinticinco").val(data[i + 29]);
                            $("#valor_cero_veinticinco").val(data[i + 30]);
                            $("#total_cero_veinticinco").val(data[i + 31]);

                            $("#denominacion_cero_diez").val(data[i + 32]);
                            $("#cantidad_cero_diez").val(data[i + 33]);
                            $("#valor_cero_diez").val(data[i + 34]);
                            $("#total_cero_diez").val(data[i + 35]);

                            $("#denominacion_cero_cinco").val(data[i + 36]);
                            $("#cantidad_cero_cinco").val(data[i + 37]);
                            $("#valor_cero_cinco").val(data[i + 38]);
                            $("#total_cero_cinco").val(data[i + 39]);

                            $("#denominacion_cero_uno").val(data[i + 40]);
                            $("#cantidad_cero_uno").val(data[i + 41]);
                            $("#valor_cero_uno").val(data[i + 42]);
                            $("#total_cero_uno").val(data[i + 43]);

                            $("#total_valor").val(data[i + 44]);
                            $("#diario_caja_text").val(data[i + 45]);
                            $("#observaciones").val(data[i + 46]);
                            if (data[i + 47] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulada"));
                                $("#estado h3").css("color", "red");
                                $("#btnAnular").attr("disabled", "disabled");
                                $("#btnModificar").attr("disabled", true);
                            } else {
                                $("#estado h3").remove();
                                $("#btnAnular").attr("disabled", "disabled");
                                $("#btnAnular").attr("disabled", false);
                                $("#btnModificar").attr("disabled", false);
                                $("#btnImprimir").attr("disabled", false);

                            }
                            $("#monto_apertura").val(data[i + 48]);
                            $("#valor_transferencia").val(data[i + 49]);
                        }

                    }
                });
                $("#buscar_inventario").dialog("close");
            } else {
                ocultarFormularioCierre();
                alertify.alert("Seleccione un Ingreso");
            }
            comprobante = 0;
        }
    }).jqGrid('navGrid', '#listPager',
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
}
function inicio() {
    initTablaCajasAbiertas();
    initDialogCajasAbiertas();
    $("#btn_buscar_cajas_abiertas").click(function (e) {
        $("#buscar_cajas_abiertas").dialog("open");
    });
    $.ajax({
        type: "POST",
        url: "comprobar_usuario.php",
        data: "",
        success: (data) => {
            var val = data;
            if (val == 1) {


                //                $("#diario_usuario").show();
                //                $("#btnActualizar").show();

            }
        },
    });
    $("#btnActualizar").click(function (e) {
        e.preventDefault();
    });
    dialogoBuscar();
    $("#btnBuscar").click(function (e) {
        e.preventDefault();
        $("#buscar_inventario").dialog("open");
    });
    //     $("#diario_caja").val(20);

    $("#denominacion").on("change", cargar_rubro_tarifa);



    show();
    // fin
    $("#series").dialog(dialogo);
    $("#clave_permiso").dialog(dialogo3);

    //    $("#denominacion").autocomplete({
    //        source: "buscar_producto.php",
    //        minLength: 1,
    //        focus: function (event, ui) {
    //
    //            $("#denominacion").val(ui.item.value);
    //
    //            $("#cod_denominacion").val(ui.item.cod_denominacion);
    //
    //            $("#valor").val(ui.item.valor);
    //            return false;
    //        },
    //        select: function (event, ui) {
    //
    //            $("#denominacion").val(ui.item.value);
    //
    //            $("#cod_denominacion").val(ui.item.cod_denominacion);
    //            $("#valor").val(ui.item.valor);
    //            return false;
    //        }
    //
    //    }).data("ui-autocomplete")._renderItem = function (ul, item) {
    //        return $("<li>")
    //                .append("<a>" + item.value + "</a>")
    //                .appendTo(ul);
    //    };
    $.ajax({
        type: "POST",
        url: 'comprobar_diario_caja.php',

        data: "valor",
        success: function (data) {
            var val = data;

            if (val != "") {

                $("#diario_caja_text").val(val);



            }
        }
    });
    $.ajax({
        type: "POST",
        url: 'comprobar_apertura_caja.php',

        data: "valor",
        success: function (data) {
            var val = data;
            var valores;
            if (val != "") {
                var valor_dj = parseFloat(val).toFixed(2);
                $("#monto_apertura").val(valor_dj);



            }
        }
    });

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

    //////Botones//////////
    $("#btnGuardar").click(function (e) {
        e.preventDefault();
    });
    $("#btnBuscar").click(function (e) {
        e.preventDefault();
    });
    $("#btnAgregar").click(function (e) {
        e.preventDefault();
    });
    $("#btnCancelarSeries").click(function (e) {
        e.preventDefault();
    });
    $("#btnNuevo").click(function (e) {
        e.preventDefault();
    });
    $("#btnAnular").click(function (e) {
        e.preventDefault();
    });
    $("#btnAceptar").click(function (e) {
        e.preventDefault();
    });
    $("#btnAceptar").on("click", aceptar);
    $("#btnAtras").click(function (e) {
        e.preventDefault();
    });
    $("#btnAcceder").click(function (e) {
        e.preventDefault();
    });
    $("#btnAdelante").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardarSeries").click(function (e) {
        e.preventDefault();
    });
    $("#btnActualizar").on("click", actualizar_Proveedor);
    //     $("#btnAnular").attr("disabled", true);
    $("#btnGuardar").on("click", guardar_cierre_caja);
    $("#btnModificar").on("click", modificar_rubro_tarifa);

    // $(document).bind('keydown', 'F7', guardar_inventario);
    // $('input').bind('keydown', 'F7', guardar_inventario);
    $("#btnNuevo").on("click", nuevo);

    $("#btnCancelarSeries").on("click", cancelar);

    //////inmput////////

    $("#cantidad").on("keypress", punto);
    //    $("#tipo_tarifa").on("change", cargar_rubro_tarifa);

    //    $("#denominacion").on("keypress", enter);


    $("#monto_apertura").on("keypress", enter2);

    $("#cantidad_cien").on("keypress", enter2);
    $("#cantidad_cincuenta").on("keypress", enter2);
    $("#cantidad_veinte").on("keypress", enter2);
    $("#cantidad_diez").on("keypress", enter2);
    $("#cantidad_cinco").on("keypress", enter2);
    $("#cantidad_uno").on("keypress", enter2);
    $("#cantidad_cero_cincuenta").on("keypress", enter2);
    $("#cantidad_cero_veinticinco").on("keypress", enter2);
    $("#cantidad_cero_diez").on("keypress", enter2);
    $("#cantidad_cero_cinco").on("keypress", enter2);
    $("#cantidad_cero_uno").on("keypress", enter2);







    $("#btnAnular").on("click", anular_factura);
    $('#serie_campos').on("keypress", enter3);
    $("#seguro").dialog(dialogo4);
    //    $('#felabo').on("keypress", enter3);
    //    $('#fExpira').on("keypress", enter3);

    ///////////////////
    $("#btnAcceder").on("click", validar_acceso);

    $("#buscar_rubro").dialog(dialogo2);

    $("#btnBuscar").click(function (e) {
        e.preventDefault();
        $("#buscar_rubro").dialog("open");
    });


    $("#btnImprimir").click(function () {

        var myWindow = window.open("../../reportes/cierre_caja_ant.php?hoja=A4&id=" + $("#comprobante").val(), '_blank');
        myWindow.focus();
        myWindow.print();

        /* var myWindow = window.open("../../reportes/prod_rel_cantidad.php?id=" + $("#comprobante").val(), '_blank'); */


    });

    $('#fecha_actual').datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');


    jQuery("#list").jqGrid({

        datatype: "local",
        colNames: ['', 'ID cierre', 'ID denomina', 'Denominacion', 'Cantidad', 'Valor'],
        colModel: [
            { name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: { keys: false, delbutton: true, editbutton: false } },
            { name: 'id_cierre', index: 'id_cierre', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'center', frozen: true, width: 3 },
            { name: 'id_denominacion', index: 'id_denominacion', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'center', frozen: true, width: 3 },
            { name: 'denominacion', index: 'denominacion', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 20 },
            { name: 'cantidad', index: 'cantidad', editable: false, search: false, hidden: false, editrules: { required: true }, align: 'center', frozen: true, width: 20 },
            { name: 'valor', index: 'valor', editable: false, search: false, hidden: false, editrules: { required: true }, align: 'center', frozen: true, width: 20 },
        ],
        rowNum: 30,
        width: 700,
        height: 200,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager'),
        sortname: 'id_denominacion',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'clientArray',
        shrinkToFit: true,
        editoptions: {

            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {
                console.log("entroooaww111");
                var id = jQuery("#list").jqGrid('getGridParam', 'selrow');
                jQuery('#list').jqGrid('restoreRow', id);
                var ret = jQuery("#list").jqGrid('getRowData', id);
                var fil = jQuery("#list").jqGrid("getRowData");
                var su = jQuery("#list").jqGrid('delRowData', rowid);
                if (su === true) {
                    rp_ge.processing = true;
                    $(".ui-icon-closethick").trigger('click');
                }
                return true;
            },
            processing: true
        },

        afterSaveCell: function (rowid, name, val, iRow, iCol) {
            console.log("entroooaww");

            var id = jQuery("#list").jqGrid('getGridParam', 'selrow');
            jQuery('#list').jqGrid('restoreRow', id);
            var ret = jQuery("#list").jqGrid('getRowData', id);

        },
        delOptions: {
            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {
                var id = jQuery("#list").jqGrid(
                    "getGridParam",
                    "selrow"
                );
                jQuery("#list").jqGrid("restoreRow", id);
                var ret = jQuery("#list").jqGrid("getRowData", id);
                rp_ge.processing = true;
                var su = jQuery("#list").jqGrid("delRowData", rowid);
                var total_venta = 0;
                var valor_restante = 0;
                var valor_total = 0;
                console.log("" + ret.valor);
                if (su === true) {
                    total_venta = (
                        parseFloat($("#total_valor").val()) - ret.valor
                    ).toFixed(2);
                    $("#total_valor").val(total_venta);


                }
                $(".ui-icon-closethick").trigger("click");
                return true;
            },
            processing: true
        }

    });

    //    jQuery(window).bind('resize', function () {
    //        jQuery("#list").setGridWidth(jQuery('#grid_container').width(), true);
    //    }).trigger('resize');
    listaBuscar("#listBuscar");
}

let comprobante = 0;
function initTablaCajasAbiertas() {
    jQuery("#listCajaAbierta")
        .jqGrid({
            url: `xmlCajasAbiertas.php`,
            datatype: "xml",
            colNames: [
                'ID',
                'USUAIO',
                'FECHA APERTURA',
                'HORA APERTURA',
                'MONTO APERTURA',
                'OBSERVACIONES',
                'IMPRIMIR',
            ],
            colModel: [
                {
                    name: 'id_cierre_caja',
                    index: 'id_cierre_caja',
                    width: 120,
                    hidden: true
                },
                {
                    name: 'usuario',
                    index: 'usuario',
                    width: 120
                },
                {
                    name: 'fecha_actual',
                    index: 'fecha_actual',
                    width: 120
                },
                {
                    name: 'hora_actual',
                    index: 'hora_actual',
                    width: 120
                },
                {
                    name: 'monto_apertura',
                    index: 'monto_apertura',
                    width: 100
                },
                {
                    name: 'observacion',
                    index: 'observacion',
                    width: 180
                },
                {
                    name: 'imprimir',
                    index: 'imprimir',
                    width: 80,
                    formatter: function myformatter(cellvalue, options, rowObject) {
                        return `<div style="text-align: center"><button id="btn_imp_ap_${options.rowId}" type="button"><span class="glyphicon glyphicon-print"></span></button></div>`;
                    }
                },
            ],
            rowNum: 30,
            width: null,
            shrinkToFit: false,
            height: 220,
            sortable: true,
            rowList: [10, 20, 30],
            pager: jQuery("#listPagerCajaAbierta"),
            //rownumbers: true,
            sortname: "cc.fecha_actual",
            sortorder: "desc",
            ondblClickRow: function (rowid, iRow, iCol, e) {
                let rdata = $("#listCajaAbierta").jqGrid("getRowData", rowid);
                comprobante = comprobante = rdata["id_cierre_caja"];
                $("#comprobante").val(comprobante);
                $("#monto_apertura").val(rdata["monto_apertura"]);
                $("#buscar_cajas_abiertas").dialog("close");
                $("#btnGuardar")[0].disabled = false;
                mostrarFormularioCierre();
            },
            afterInsertRow: function (rowid, rowdata, rowelem) {
                $("#btn_imp_ap_" + rowid).click(function (e) {
                    var myWindow = window.open("../../reportes/apertura_caja_ant.php?id=" + rowid, '_blank');
                    myWindow.focus();
                    myWindow.print();
                });
            }
        })
        .jqGrid(
            "navGrid",
            "#listPagerCajaAbierta",
            {
                add: false,
                edit: false,
                del: false,
                refresh: true,
                search: false,
                view: false,
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
function initDialogCajasAbiertas() {
    let dialogo = {
        autoOpen: false,
        resizable: false,
        width: 800,
        height: 355,
        modal: true,
        // position: "top",
        show: "explode",
        hide: "blind",
        open: function (event, ui) {
            jQuery("#listCajaAbierta").trigger("reloadGrid");
        }
    }
    $("#buscar_cajas_abiertas").dialog(dialogo);
}

function ocultarFormularioCierre() {
    $("#btnAnular")[0].disabled = true;
    $("#btnImprimir")[0].disabled = true;
    $("#btn_buscar_cajas_abiertas")[0].disabled = false;
    $("#container_form_cierre").hide();
}
function mostrarFormularioCierre() {
    $("#btnAnular")[0].disabled = true;
    $("#btnImprimir")[0].disabled = true;
    $("#btn_buscar_cajas_abiertas")[0].disabled = true;
    $("#container_form_cierre").show();
}