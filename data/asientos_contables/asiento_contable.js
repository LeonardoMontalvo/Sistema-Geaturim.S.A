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

var dialogo = {
    autoOpen: false,
    resizable: false,
    width: 600,
    height: 420,
    modal: true
};
var dialogo11 =
{
    autoOpen: false,
    resizable: false,
    width: 640,
    height: 320,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind",
    Cancelar: function () {
        $(this).dialog("close");
        $('#list22').trigger('reloadGrid');
    }
};
var dialogo1122 =
{
    autoOpen: false,
    resizable: false,
    width: 640,
    height: 320,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind",
    Cancelar: function () {
        $(this).dialog("close");
        $('#list22').trigger('reloadGrid');
    }
};
var dialogo2 = {
    autoOpen: false,
    resizable: false,
    width: 1300,
    height: 400,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind"
}
var dialogo3 =
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

var dialogo4 = {
    autoOpen: false,
    resizable: false,
    width: 400,
    height: 150,
    modal: true,
    position: "center",
    show: "explode",
    hide: "blind"
}

/*$("#ruc_ci").autocomplete({
 source: "buscar_cliente2.php",
 minLength: 1,
 focus: function(event, ui) {
 $("#ruc_ci").val(ui.item.value);
 $("#nombres_completos").val(ui.item.nombres_completos);
 $("#id_cliente").val(ui.item.id_cliente);
 //$("#saldo").val(ui.item.saldo);
 return false;
 },
 select: function(event, ui) {
 $("#ruc_ci").val(ui.item.value);
 $("#nombres_completos").val(ui.item.nombres_completos);
 $("#id_cliente").val(ui.item.id_cliente);
 //$("#saldo").val(ui.item.saldo);
 //        var id = $('#id_cliente').val();
 //        $('#tipo_pago').load('cargar_tipo_pago.php?cod=' + id);
 return false;
 }
 }).data("ui-autocomplete")._renderItem = function(ul, item) {
 return $("<li>")
 .append("<a>" + item.value + "</a>")
 .appendTo(ul);
 };
 */



//
//$("#ruc_ci").autocomplete({
//    source: "buscar_proveedor.php",
//    minLength: 1,
//    focus: function (event, ui) {
//        $("#ruc_ci").val(ui.item.value);
//        $("#nombres_completos").val(ui.item.empresa_pro);
//        $("#id_cliente").val(ui.item.id_proveedor);
//        //$("#saldo").val(ui.item.saldo);
//        console.log("medina");
//        return false;
//    },
//    select: function (event, ui) {
//        $("#ruc_ci").val(ui.item.value);
//        $("#nombres_completos").val(ui.item.empresa_pro);
//        $("#id_cliente").val(ui.item.id_proveedor);
//        //$("#saldo").val(ui.item.saldo);
//        //        var id = $('#id_cliente').val();
//        //        $('#tipo_pago').load('cargar_tipo_pago.php?cod=' + id);
//        return false;
//    }
//}).data("ui-autocomplete")._renderItem = function (ul, item) {
//    return $("<li>")
//        .append("<a>" + item.value + "</a>")
//        .appendTo(ul);
//};

//    $("#banco").autocomplete({
//        source: "buscar_bancos.php",
//        minLength: 1,
//        focus: function(event, ui) {
//        $("#id_bancos").val(ui.item.value);
//        $("#banco").val(ui.item.descripcion);
//        //$("#saldo").val(ui.item.saldo);
//        return false;
//        },
//        select: function(event, ui) {
//        $("#id_bancos").val(ui.item.value);
//        $("#banco").val(ui.item.descripcion);
//        //$("#saldo").val(ui.item.saldo);
////        var id = $('#id_cliente').val();
////        $('#tipo_pago').load('cargar_tipo_pago.php?cod=' + id);
//        return false;
//        }
//        }).data("ui-autocomplete")._renderItem = function(ul, item) {
//        return $("<li>")
//        .append("<a>" + item.value + "</a>")
//        .appendTo(ul);
//    };

function abrirDialogo() {
    if ($("#carga_series").val() == "") {
        alertify.error("Error... Seleccione un producto");
    } else {
        if ($("#carga_series").val() == "No") {
            $("#descuento").focus();
            alertify.error("Error... El producto no contiene series");
        } else {
            if ($("#cantidad").val() != "") {
                $("#series").dialog("open");
            } else {
                $("#cantidad").focus();
                alertify.error("Error... Indique una cantidad");
            }
        }
    }
}

function ValidNum(e) {
    if (e.keyCode < 48 || e.keyCode > 57) {
        e.returnValue = false;
    }
    return true;
}

function enter(e) {
    if (e.which == 13 || e.keyCode == 13) {
        entrar();
        return false;
    }
    return true;
}


function enter2(e) {
    if (e.which == 13 || e.keyCode == 13) {
        entrar2();
        return false;
    }
    return true;
}

/*function enter2(e) {
 if (e.which == 13 || e.keyCode == 13) {
 comprobar();
 return false;
 }
 return true;
 }*/

function enter3(e) {
    if (e.which == 13 || e.keyCode == 13) {
        comprobar2();
        return false;
    }
    return true;
}

function enter5(e) {
    if (e.which == 13 || e.keyCode == 13) {
        comprobar2();
        return false;
    }
    return true;
}


function enter4(e) {
    if (e.which == 13 || e.keyCode == 13) {
        comprobar3();
        return false;
    }
    return true;
}

function enter5(e) {
    if (e.which == 13 || e.keyCode == 13) {
        comprobar4();
        return false;
    }
    return true;
}

function autocompletar() {
    var temp = "";
    var serie = $("#serie3").val();
    for (var i = serie.length; i < 9; i++) {
        temp = temp + "0";
    }
    return temp;
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

function aceptarEliminar() {
    if ($("#id_asiento_contable").val() == "") {
        alertify.error("Seleccione una Asiento Contable");
        $("#buscar_asiento").dialog("open");
    } else {
        $.ajax({
            type: "POST",
            url: "eliminar_asiento_contable.php",
            data: "id_transacciones=" + $("#id_asiento_contable").val(),
            success: function (data) {
                var val = data;
                if (val == 1) {
                    alertify.alert("Asiento Eliminado correctamente", function () {
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

//function entrarIE() {
//    if ($("#tipo_transaccion").val() == "2") {
//        $("#credito").focus();
//        var filas = jQuery("#list").jqGrid("getRowData");
//        var debito = 0;
//        var credito = 0;
//        var diferencia = 0;
//        var repe = 0;
//        var suma = 0;
//        var suma1 = 0;
//
//        if (filas.length == 0) {
//            if ($("#forma_pago").val() == "EFECTIVO") {
//                var datarow = {
//                    id_plan: '5',
//                    codigo_plan: '1.1.01.01.01',
//                    descripcion: 'CAJA GENERAL',
//                    debex: (parseFloat($("#valor_pagado").val())).toFixed(4),
//                    haberx: '0.000',
//                    debe: (parseFloat($("#valor_pagado").val())).toFixed(4),
//                    haber: '0.000'
//
//                };
//
//                su = jQuery("#list").jqGrid('addRowData', '5', datarow);
//                datarow = {
//                    id_plan: '26',
//                    codigo_plan: '1.1.02.04.01',
//                    descripcion: 'Cuentas por Cobrar Datafast',
//                    debex: '0.000',
//                    haberx: (parseFloat($("#valor_pagado").val())).toFixed(4),
//                    debe: '0.000',
//                    haber: (parseFloat($("#valor_pagado").val())).toFixed(4)
//                };
//                su = jQuery("#list").jqGrid('addRowData', '26', datarow);
//                limpiar_campos();
//            } else {
//
////            var datarow = {
////                id_plan: '5',
////                codigo_plan: '1.1.01.01.01',
////                descripcion: 'CAJA GENERAL',
////                debex: (parseFloat($("#valor_pagado").val())).toFixed(4),
////                haberx: '0.000',
////                debe: (parseFloat($("#valor_pagado").val())).toFixed(4),
////                haber: '0.000'
////
////            };
////
////            su = jQuery("#list").jqGrid('addRowData', '5', datarow);
//
//                if ($("#valor_pagado").val() != "") {
//                    datarow = {
//                        id_plan: '26',
//                        codigo_plan: '1.1.02.04.01',
//                        descripcion: 'Cuentas por Cobrar Datafast',
//                        debex: '0.000',
//                        haberx: (parseFloat($("#valor_pagado").val())).toFixed(4),
//                        debe: '0.000',
//                        haber: (parseFloat($("#valor_pagado").val())).toFixed(4)
//                    };
//                    su = jQuery("#list").jqGrid('addRowData', '26', datarow);
//                    limpiar_campos();
//
//                } else
//                {
//                    alertify.error("INGRECE UN VALOR")
//
//
//                }
//
//
//
//            }
//        } else {
////            for (var i = 0; i < filas.length; i++) {
////                var id = filas[i];
////
////                if (id['codigo_plan'] == $("#codigo_plan").val()) {
////                    repe = 1;
////                    var can = id['debe'];
////                    var can1 = id['haber'];
////                }
////            }
//
//        }            // calcular valores
//
//        var fil = jQuery("#list").jqGrid("getRowData");
//        for (var t = 0; t < fil.length; t++) {
//            var dd = fil[t];
//            debito = parseFloat(debito) + parseFloat(dd['debe']);
//            credito = parseFloat(credito) + parseFloat(dd['haber']);
//            diferencia = parseFloat(debito - credito);
//        }
//        $("#total_debito").val(debito);
//        $("#total_credito").val(credito);
//        $("#diferencia").val(diferencia);
//        $("#total_debitox").val(debito.toFixed(4));
//        $("#total_creditox").val(credito.toFixed(4));
//        $("#diferenciax").val(diferencia.toFixed(4));
//    } else if ($("#tipo_transaccion").val() == "3") {
//
//        $("#credito").focus();
//        var filas = jQuery("#list").jqGrid("getRowData");
//        var debito = 0;
//        var credito = 0;
//        var diferencia = 0;
//        var repe = 0;
//        var suma = 0;
//        var suma1 = 0;
//
//        if (filas.length == 0) {
//            if ($("#forma_pago").val() == "EFECTIVO") {
//                var datarow = {
//                    id_plan: '135',
//                    codigo_plan: '2.1.03.01.01',
//                    descripcion: 'LOCALES NO RELACIONADOS',
//                    debex: (parseFloat($("#valor_pagado").val())).toFixed(4),
//                    haberx: '0.000',
//                    debe: (parseFloat($("#valor_pagado").val())).toFixed(4),
//                    haber: '0.000'
//
//                };
//
//                su = jQuery("#list").jqGrid('addRowData', '5', datarow);
//                datarow = {
//                    id_plan: '5',
//                    codigo_plan: '1.1.01.01.01',
//                    descripcion: 'CAJA GENERAL',
//                    debex: '0.000',
//                    haberx: (parseFloat($("#valor_pagado").val())).toFixed(4),
//                    debe: '0.000',
//                    haber: (parseFloat($("#valor_pagado").val())).toFixed(4)
//
//                };
//
//                su = jQuery("#list").jqGrid('addRowData', '5', datarow);
//
//                limpiar_campos();
//            } else {
//
////            var datarow = {
////                id_plan: '5',
////                codigo_plan: '1.1.01.01.01',
////                descripcion: 'CAJA GENERAL',
////                debex: (parseFloat($("#valor_pagado").val())).toFixed(4),
////                haberx: '0.000',
////                debe: (parseFloat($("#valor_pagado").val())).toFixed(4),
////                haber: '0.000'
////
////            };
////
////            su = jQuery("#list").jqGrid('addRowData', '5', datarow);
//                if ($("#valor_pagado").val() != "") {
//                    datarow = {
//                        id_plan: '135',
//                        codigo_plan: '2.1.03.01.01',
//                        descripcion: 'LOCALES NO RELACIONADOS',
//                        debex: (parseFloat($("#valor_pagado").val())).toFixed(4),
//                        haberx: '0.000',
//                        debe: (parseFloat($("#valor_pagado").val())).toFixed(4),
//                        haber: '0.000'
//
//                    };
//
//                    su = jQuery("#list").jqGrid('addRowData', '5', datarow);
//
//
//                    limpiar_campos();
//
//
//                } else {
//                    alertify.error("INGRECE UN VALOR")
//
//
//                }
//            }
//
//
//        } else {
////            for (var i = 0; i < filas.length; i++) {
////                var id = filas[i];
////
////                if (id['codigo_plan'] == $("#codigo_plan").val()) {
////                    repe = 1;
////                    var can = id['debe'];
////                    var can1 = id['haber'];
////                }
////            }
//
//        }            // calcular valores
//
//        var fil = jQuery("#list").jqGrid("getRowData");
//        for (var t = 0; t < fil.length; t++) {
//            var dd = fil[t];
//            debito = parseFloat(debito) + parseFloat(dd['debe']);
//            credito = parseFloat(credito) + parseFloat(dd['haber']);
//            diferencia = parseFloat(debito - credito);
//
//        }
//
//        $("#total_debito").val(debito);
//        $("#total_credito").val(credito);
//        $("#diferencia").val(diferencia);
//        $("#total_debitox").val(debito.toFixed(4));
//        $("#total_creditox").val(credito.toFixed(4));
//        $("#diferenciax").val(diferencia.toFixed(4));
//
//
//    }
//
//}
function entrar() {

    $("#codigo_plan").focus();
    $("#valorconcepto").val($("#valor_pagado").val());


}
function limpiar_campos_mixto() {

    $("#concepto").val("");
    $("#nro_transaccion").val("");
    $("#ruc_ci").val("");

    $("#nombres_completos").val("");

    $("#tipo_transaccion").val(0);
    $("#tipo_persona").val(0);

    $("#fecha_registro").val(0);


}
function entrar2() {
    if ($("#codigo_plan").val() == "") {
        $("#codigo_plan").focus();
        //        alertify.error("Ingrese una cuenta");
    } else {
        if ($("#descripcion").val() == "") {
            $("#descripcion").focus();
            //            alertify.error("Ingrese una cuenta");
        } else {
            if ($("#debito").val() == "0.000" && $("#credito").val() == "0.000") {
                //if ($("#debito").val() == "0.000") {
                $("#debito").focus();
                $("#debito").select();
                //  
                //                alertify.error("Ingrese valores.");
            } else {
                if ($("#debito").val() == "" && $("#credito").val() == "") {
                    //if ($("#debito").val() == "0.000") {
                    $("#debito").focus();
                    //  
                    //                    alertify.error("Ingrese valores:");
                } else {
                    if ($("#debito").val() == "" && $("#credito").val() == "") {
                        //if ($("#debito").val() == "0.000") {
                        $("#credito").focus();
                        //  
                        //                        alertify.error("Ingrese valores::");
                    } else {
                        if ($("#debito").val() == "" || $("#credito").val() == "") {
                            //if ($("#debito").val() == "0.000") {
                            $("#credito").focus();
                            //                            alertify.error("Ingrese valores:::");
                        } else {
                            //Cuando ingrese dos valores en la misma cuenta
                            var comprobar = 0;
                            var debito = 0;
                            var credito = 0;
                            if ((parseFloat($("#debito").val())) === 0) {
                                debito = 0;
                            } else {
                                debito = 1;
                            }
                            if ((parseFloat($("#credito").val())) === 0) {
                                credito = 0;
                            } else {
                                credito = 1;
                            }
                            console.log("debito " + parseInt($("#debito").val()));
                            console.log("credito " + parseInt($("#credito").val()));
                            //console.log("suma "+ comprobar);
                            suma = debito + credito;
                            console.log("suma " + comprobar);
                            if (suma == 2) {
                                alertify.error("Error...  La cuenta está con valores en DEBITO y CREDITO");
                                $("#credito").focus();

                            }
                            /* debito=parseFloat(($("#debito").val())).toFixed(3);
                             credito=parseFloat(($("#credito").val())).toFixed(3);
                             console.log("debito "+ debito);
                             console.log("credito "+ credito);
                             comprobar=parseFloat(debito+credito).toFixed(3);  
                             console.log("suma "+ comprobar);
                             
                             if (comprobar !=debito || comprobar!=credito) {
                             //if ($("#debito").val() == "0.000") {
                             alertify.error("Error la cuenta está con valores DEBE y HABER");  
                             $("#credito").focus();
                             } */
                            else {

                                $("#descripcion").focus();
                                var filas = jQuery("#list").jqGrid("getRowData");
                                var debito = 0;
                                var credito = 0;
                                var diferencia = 0;
                                var repe = 0;
                                var suma = 0;
                                var suma1 = 0;

                                if (filas.length == 0) {
                                    var datarow = {
                                        codigo_plan: $("#codigo_plan").val(),
                                        descripcion: $("#descripcion").val(),
                                        debex: (parseFloat($("#debito").val())).toFixed(4),
                                        haberx: (parseFloat($("#credito").val())).toFixed(4),
                                        debe: $("#debito").val(),
                                        haber: $("#credito").val(),
                                        id_plan: $("#id_plan").val()
                                    };

                                    su = jQuery("#list").jqGrid('addRowData', $("#codigo_plan").val(), datarow);
                                    limpiar_campos();
                                } else {
                                    //                                    for (var i = 0; i < filas.length; i++) {
                                    //                                        var id = filas[i];
                                    //
                                    //                                        if (id['codigo_plan'] == $("#codigo_plan").val()) {
                                    //                                            repe = 1;
                                    //                                            var can = id['debe'];
                                    //                                            var can1 = id['haber'];
                                    //                                        }
                                    //                                    }
                                    //
                                    //                                    if (repe == 1) {
                                    //
                                    //                                        alertify.error("Error... Ya existe una Cuenta ingresada");
                                    //                                        limpiar_campos();
                                    //                                 
                                    //                                    } else {
                                    console.log("OPCION1");
                                    //                                        suma = parseInt(can) + parseInt($("#debito").val());
                                    //                                        suma1 = parseInt(can1) + parseInt($("#credito").val());
                                    datarow = {
                                        codigo_plan: $("#codigo_plan").val(),
                                        descripcion: $("#descripcion").val(),
                                        debe: $("#debito").val(),
                                        haber: $("#credito").val(),
                                        debex: (parseFloat($("#debito").val())).toFixed(4),
                                        haberx: (parseFloat($("#credito").val())).toFixed(4),
                                        id_plan: $("#id_plan").val()
                                    };
                                    su = jQuery("#list").jqGrid('addRowData', $("#id_plan").val(), datarow);
                                    limpiar_campos();
                                    //                                    }

                                }            // calcular valores

                                var fil = jQuery("#list").jqGrid("getRowData");
                                for (var t = 0; t < fil.length; t++) {
                                    var dd = fil[t];
                                    debito = parseFloat(debito) + parseFloat(dd['debe']);
                                    credito = parseFloat(credito) + parseFloat(dd['haber']);
                                    diferencia = parseFloat(debito - credito);

                                }

                                $("#total_debito").val(debito);
                                $("#total_credito").val(credito);
                                //TODO redondear diferencia a dos decimales
                                $("#diferencia").val(diferencia.toFixed(2));
                                $("#total_debitox").val(debito.toFixed(2));
                                $("#total_creditox").val(credito.toFixed(2));
                                $("#diferenciax").val(diferencia.toFixed(2));
                            }
                        }
                    }
                }
            }
        }
    }

}

/*function comprobar() {
 if ($("#codigo_plan").val() == "") {
 $("#codigo_plan").focus();
 alertify.error("Ingrese una cuenta");
 } else {
 if ($("#descripcion").val() == "") {
 $("#descripcion").focus();
 alertify.error("Ingrese una cuenta");
 } else {
 if ($("#debito").val() == "0.000" && $("#credito").val() == "0.000") {
 $("#debito").focus();
 alertify.error("Ingrese valores");
 } else {
 $("#credito").focus();
 }
 }
 }
 }*/

function limpiar_campos() {
    $("#id_plan").val("");
    $("#codigo_plan").val("");
    $("#descripcion").val("");
    $("#debito").val("0.000");
    $("#credito").val("0.000");
}

function comprobar2() {
    if ($("#codigo_plan").val() == "") {
        $("#codigo_plan").focus();
        alertify.error("Ingrese una cuenta");
    } else {
        if ($("#descripcion").val() == "") {
            $("#descripcion").focus();
            alertify.error("Ingrese una cuenta");
        } else {
            if ($("#debito").val() == "0.000" && $("#credito").val() == "0.000") {
                //if ($("#debito").val() == "0.000") {

                $("#credito").focus();
                $("#credito").select();
                //  
                //alertify.error("Ingrese valores");
            } else {
                if ($("#debito").val() == "" && $("#credito").val() == "") {
                    //if ($("#debito").val() == "0.000") {
                    $("#credito").focus();
                    //  
                    //alertify.error("Ingrese valores");
                } else {
                    if ($("#debito").val() == "" || $("#credito").val() == "") {
                        //if ($("#debito").val() == "0.000") {
                        $("#credito").focus();
                        //    

                    } else {
                        if ($("#credito").val() == "") {
                            $("#credito").val() == "0.000";
                            $("#credito").focus();
                            //  
                            //alertify.error("Ingrese valores");
                        } else {

                            $("#credito").focus();

                        }
                    }
                }
            }
            /*
             
             else {
             
             $("#credito").focus(); 
             var filas = jQuery("#list").jqGrid("getRowData");
             var debito = 0;
             var credito = 0;
             var diferencia = 0; 
             var repe = 0;
             var suma = 0;
             var suma1 = 0;
             
             if (filas.length == 0) {
             var datarow = {
             codigo_plan: $("#codigo_plan").val(), 
             descripcion: $("#descripcion").val(), 
             debex: (parseFloat($("#debito").val())).toFixed(2), 
             haberx: (parseFloat($("#credito").val())).toFixed(2),
             debe: $("#debito").val(), 
             haber: $("#credito").val(),
             id_plan: $("#id_plan").val()
             };
             
             su = jQuery("#list").jqGrid('addRowData', $("#codigo_plan").val(), datarow);
             limpiar_campos();
             } else {
             for (var i = 0; i < filas.length; i++) {
             var id = filas[i];
             
             if (id['codigo_plan'] == $("#codigo_plan").val()) {
             repe = 1;
             var can = id['debe'];
             var can1 = id['haber'];
             }
             }
             
             if (repe == 1) {
             suma = parseInt(can) + parseInt($("#debito").val());
             suma1 = parseInt(can1) + parseInt($("#credito").val());
             
             datarow = {
             codigo_plan: $("#codigo_plan").val(), 
             descripcion: $("#descripcion").val(), 
             debe: suma, 
             haber: suma1,
             debex: (parseFloat(suma)).toFixed(3), 
             haberx: (parseFloat(suma1)).toFixed(3),
             id_plan: $("#id_plan").val()
             };
             
             su = jQuery("#list").jqGrid('setRowData', $("#id_plan").val(), datarow);
             limpiar_campos();
             } else {
             datarow = {
             codigo_plan: $("#codigo_plan").val(), 
             descripcion: $("#descripcion").val(), 
             debe: (parseFloat($("#debito").val())).toFixed(2), 
             haber: (parseFloat($("#credito").val())).toFixed(2),
             id_plan: $("#id_plan").val()
             };
             su = jQuery("#list").jqGrid('addRowData', $("#id_plan").val(), datarow);
             limpiar_campos();      
             }   
             
             }            // calcular valores
             
             var fil = jQuery("#list").jqGrid("getRowData");
             for (var t = 0; t < fil.length; t++) {
             var dd = fil[t];
             debito= parseFloat(debito)+parseFloat(dd['debe']);
             credito= parseFloat(credito)+parseFloat(dd['haber']);
             diferencia=parseFloat(debito-credito);
             
             }                                           
             
             $("#total_debito").val(debito);
             $("#total_credito").val(credito);
             $("#diferencia").val(diferencia);   
             $("#total_debitox").val(debito.toFixed(2));
             $("#total_creditox").val(credito.toFixed(2));
             $("#diferenciax").val(diferencia.toFixed(2));               
             }*/
        }
    }
}

function comprobar3() {
    if ($("#serie").val() == "") {
        $("#serie").focus();
        alertify.error("Ingrese una serie");
    } else {
        if ($("#tipo_comprobante").val() == "") {
            $("#tipo_comprobante").focus();
            alertify.error("Seleccione tipo documento");
        } else {
            if ($("#empresa").val() == "") {
                $("#ruc_ci").focus();
                alertify.error("Indique un Proveedor");
            } else {
                $("#autorizacion").focus();
            }
        }
    }
}

function comprobar4() {
    if ($("#serie").val() == "") {
        $("#serie").focus();
        alertify.error("Ingrese una serie");
    } else {
        if ($("#tipo_comprobante").val() == "") {
            $("#tipo_comprobante").focus();
            alertify.error("Seleccione tipo documento");
        } else {
            if ($("#empresa").val() == "") {
                $("#ruc_ci").focus();
                alertify.error("Indique un Proveedor");
            } else {
                if ($("#empresa").val() == "") {
                    $("#ruc_ci").focus();
                    alertify.error("Indique un Proveedor");
                } else {
                    $("#codigo_barras").focus();
                }
            }
        }
    }
}

function agregar() {
    if ($("#serie").val() != "") {
        var filas2 = jQuery("#list2").jqGrid("getRowData");
        var su;
        var count = 0;
        var canti = $("#cantidad").val();

        $.ajax({
            type: "POST",
            url: "comparar_series.php",
            data: "serie=" + $("#serie").val(),
            success: function (data) {
                var val = data;
                if (val == 1) {
                    $("#serie").val("");
                    $("#serie").focus();
                    alertify.alert("Error... Serie ya registrada");
                } else {
                    if (filas2.length < canti) {
                        if (filas2.length == 0) {
                            var datarow = {
                                id_serie: count = count + 1,
                                serie: $("#serie").val()
                            };
                            su = jQuery("#list2").jqGrid('addRowData', count, datarow);
                            $("#serie").val("");
                            $("#serie").focus();
                        } else {
                            var repe = 0;
                            for (var i = 0; i < filas2.length; i++) {
                                var id = filas2[i];
                                if (id['serie'] == $("#serie").val()) {
                                    repe = 1;
                                }
                            }
                            if (repe == 0) {
                                datarow = {
                                    id_serie: count = count + 1,
                                    serie: $("#serie").val()
                                };
                                su = jQuery("#list2").jqGrid('addRowData', count, datarow);
                                $("#serie").val("");
                                $("#serie").focus();
                            } else {
                                $("#serie").val("");
                                $("#serie").focus();
                                alertify.alert("Error... Serie ingresada");
                            }
                        }
                    } else {
                        $("#serie").val("");
                        $("#btnAgregar").attr("disabled", "disabled");
                        alertify.alert("Error... Alcanzo el limite máximo");
                    }
                }
            }
        });
    } else {
        $("#serie").focus();
        alertify.alert("Error... Indique una serie");
    }
}

function cambio_ret_fuente() {

    if (document.getElementById('retencionF2').checked) {
        if ($("#id_factura_compra").val() != "") {
            $("#tipoRetencionesF").attr("disabled", false);
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

function calculo_ret_fuente() {
    var calculoRET = 0;
    var x = document.getElementById("tipoRetencionesF").selectedIndex;
    $.ajax({
        type: "POST",
        url: "../../procesos/buscar_ret_fuente.php",
        data: "id=" + x,
        success: function (data) {
            var val = data;
            if (val != 0) {
                calculoRET = val;
                alertify.alert("El porcentaje de retención es del: " + calculoRET + "%");
                var valor = toFixedDown(((($("#sub").val()) * calculoRET) / 100), 3);
                $("#calculoRetencionF").val(valor);
            }
        }
    });
}

function cambio_ret_iva() {

    if (document.getElementById('retencionI2').checked) {
        if ($("#id_factura_compra").val() != "") {
            $("#tipoRetencionesI").attr("disabled", false);
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

function calculo_ret_iva() {
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
                alertify.alert("El porcentaje de retención es del: " + calculoRET + "%");
                var valor = toFixedDown(((($("#iva").val()) * calculoRET) / 100), 3);
                $("#calculoRetencionI").val(valor);
            }
        }
    });
}

function guardar_retenciones_factura_compra() {
    //sumC=0;
    var x = document.getElementById("tipoRetencionesF").selectedIndex;
    if ($("#calculoRetencionF").val() != 0.000 || $("#calculoRetencionF").val() != 0) {
        $.ajax({
            type: "POST",
            url: "guardar_ret_fuente_fact_compra.php",
            data: "id_factura=" + $("#id_factura_compra").val() + "&id_retencion_fuente=" + x + "&valor_factura=" + $("#tot").val() + "&iva_factura=" + $("#iva").val() + "&valor_retencion=" + $("#calculoRetencionF").val(),
            success: function (data) {
                var val = data;
                if (val == 1) {
                    alertify.alert("Retenciones guardadas correctamente", function () {
                        location.reload();
                    });
                } else if (val == 2) {
                    alertify.alert("La Factura ya tiene retenciones en la fuente");
                } else {
                    alertify.alert(val);
                }
            }
        });
    }
    var y = document.getElementById("tipoRetencionesI").selectedIndex;
    if ($("#calculoRetencionI").val() != 0.000 || $("#calculoRetencionI").val() != 0) {
        $.ajax({
            type: "POST",
            url: "guardar_ret_iva_fact_compra.php",
            data: "id_factura=" + $("#id_factura_compra").val() + "&id_retencion_iva=" + y + "&valor_factura=" + $("#tot").val() + "&iva_factura=" + $("#iva").val() + "&valor_retencion=" + $("#calculoRetencionI").val(),
            success: function (data) {
                var val1 = data;
                if (val == 1) {
                    alertify.alert("Retenciones guardadas correctamente", function () {
                        location.reload();
                    });
                } else if (val == 2) {
                    alertify.alert("La Factura ya tiene retenciones en la fuente");
                } else {
                    alertify.alert(val);
                }
            }
        });
    }
    /*if(sumC!=0){
     alertify.alert("Los datos se han guardado correctamente", function(){
     location.reload();
     });        
     }else{
     alertify.alert(sumC);
     }*/
}

function guardar_serie() {
    var tam2 = jQuery("#list2").jqGrid("getRowData");

    if ($("#cod_producto").val() == "") {
        alertify.alert("Error... Seleccione un producto");
    } else {
        if (tam2.length > 0) {
            var v1 = new Array();
            var string_v1 = "";
            var fil = jQuery("#list2").jqGrid("getRowData");

            for (var i = 0; i < fil.length; i++) {
                var datos = fil[i];
                v1[i] = datos['serie'];
            }

            for (i = 0; i < fil.length; i++) {
                string_v1 = string_v1 + "|" + v1[i];
            }
            $.ajax({
                type: "POST",
                url: "guardar_series.php",
                data: "cod_producto=" + $("#cod_producto").val() + "&campo1=" + string_v1 + "&comprobante=" + $("#comprobante").val(),
                success: function (data) {
                    var val = data;
                    if (val == 1) {
                        $("#list2").jqGrid("clearGridData", true);
                        $("#series").dialog("close");
                        $("#descuento").focus();
                    }
                }
            });
        } else {
            alertify.alert("Error... Ingrese las series");
        }
    }
}
function cargar_facturascxc() {
    var id = $("#id_cliente").val();
    var ids = $("#num_factura").val();
    var tipo_docu = $("#tipo_docu").val();
    console.log($("#tipo_persona").val() + "444");
    if (id == "" || $("#tipo_persona").val() == '0') {
        $("#num_factura").val("");
        $("#ruc_ci").focus();
        alertify.error("Error... Seleccione un cliente y tipo documento");
    } else {
        console.log('33')
        $("#list222").jqGrid('setGridParam', {
            url: 'xmlFacturas_venta.php?id_cliente=' + id + '&tipo=' + $("#tipo_pago").val() + '&fact_nota=' + 'factura' + '&ids=' + ids,
            datatype: 'xml'
        }).trigger('reloadGrid');
        $("#buscar_facturas222").dialog("open");
    }
}
function cargar_facturas() {
    var id = $("#id_cliente").val();
    var ids = $("#num_factura").val();
    var tipo_docu = $("#tipo_docu").val();
    console.log($("#tipo_persona").val() + "444");
    if (id == "" || $("#tipo_persona").val() == '0') {
        //        $("#num_factura").val("");
        $("#ruc_ci").focus();
        alertify.error("Error... Seleccione un proveedor");
    } else {
        $("#list22").jqGrid('setGridParam', {
            url: 'xmlFacturas_compra.php?id_proveedor=' + id + '&tipo=' + $("#tipo_pago").val() + '&fact_nota=' + 'factura' + '&ids=' + ids,
            datatype: 'xml'
        }).trigger('reloadGrid');
        $("#buscar_facturas").dialog("open");
    }
}

function guardar_asiento() {
    var tam = jQuery("#list").jqGrid("getRowData");

    if ($("#tipo_transaccion").val() == 0) {
        $("#tipo_transaccion").focus();
        alertify.alert("Elija el tipo de transacción");
    } else {
        if ($("#concepto").val() == "") {
            $("#concepto").focus();
            alertify.alert("Escriba un concepto de transacción");
        } else {
            if ($("#concepto").val().length < 5) {
                alertify.alert("El concepto debe tener mínimo 5 caracteres");
            } else {
                if ($("#total_debito").val() == "0.000" && $("#total_credito").val() == "0.000") {
                    $("#codigo_plan").focus();
                    alertify.alert("Ingrese datos en la tabla");
                } else {
                    //TODO cast float diferencia 
                    if (parseFloat($("#diferencia").val()) != 0) {
                        $("#codigo_plan").focus();
                        alertify.alert("Los valores de débito y crédito no coinciden");
                    } else {
                        if ($("#fecha_registro").val() == "") {
                            $("#fecha_registro").focus();
                            alertify.error("Debe seleccionar ");
                        } else {
                            //alertify.alert("Datos Correctos");

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
                                v1[i] = datos['id_plan'];
                                v2[i] = datos['codigo_plan'];
                                v3[i] = datos['descripcion'];
                                v4[i] = datos['debe'];
                                v5[i] = datos['haber'];
                                v5[i] = datos['haber'];
                            }

                            for (i = 0; i < fil.length; i++) {
                                string_v1 = string_v1 + "|" + v1[i];
                                string_v2 = string_v2 + "|" + v2[i];
                                string_v3 = string_v3 + "|" + v3[i];
                                string_v4 = string_v4 + "|" + v4[i];
                                string_v5 = string_v5 + "|" + v5[i];
                            }

                            var identificador = "";
                            var conceptoc = "";
                            if ($("#tipo_persona").val() == 0) {
                                $("#tipo_persona").focus();
                                alertify.alert("Debe seleccionar el Tipo de Documento");
                            } else {
                                if ($("#tipo_persona").val() == "1") {
                                    //$("#identificador_cli_pro").val("ING");  
                                    identificador = "ING";
                                    conceptoc = "COMPROBANTE DE INGRESO: " + $("#concepto").val();
                                } else {
                                    if ($("#tipo_persona").val() == "2") {
                                        //$("#identificador_cli_pro").val("EGR") ;
                                        identificador = "EGR";
                                        conceptoc = "COMPROBANTE DE EGRESO: " + $("#concepto").val();
                                    } else {
                                        if ($("#tipo_persona").val() == "3") {
                                            //$("#identificador_cli_pro").val("NC"); 
                                            identificador = "NC";
                                            conceptoc = "COMPROBANTE DE NOTA CREDITO: " + $("#concepto").val();
                                        }
                                    }
                                }



                                console.log("identificador   ->" + identificador);
                                if ($("#tipo_transaccion").val() == "2") {
                                    //$("#identificador_cli_pro").val("ING");  
                                    identificador = "ING";
                                    conceptoc = "COMPROBANTE DE INGRESO: " + $("#concepto").val();
                                } else {
                                    if ($("#tipo_transaccion").val() == "3") {
                                        //$("#identificador_cli_pro").val("EGR") ;
                                        identificador = "EGR";
                                        conceptoc = "COMPROBANTE DE EGRESO: " + $("#concepto").val();
                                    } else {
                                        if ($("#tipo_transaccion").val() == "4") {
                                            //$("#identificador_cli_pro").val("NC"); 
                                            identificador = "NC";
                                            conceptoc = "COMPROBANTE DE NOTA CREDITO: " + $("#concepto").val();
                                        } else {
                                            if ($("#tipo_transaccion").val() == "5") {
                                                //$("#identificador_cli_pro").val("ND");  
                                                identificador = "ND";
                                                conceptoc = "COMPROBANTE DE NOTA DEBITO: " + $("#concepto").val();
                                            } else {
                                                if ($("#tipo_transaccion").val() == "6") {
                                                    //$("#identificador_cli_pro").val("NV");
                                                    identificador = "NV";
                                                    conceptoc = "COMPROBANTE DE NOTA VENTA: " + $("#concepto").val();
                                                } else {
                                                    if ($("#tipo_transaccion").val() == "1") {
                                                        console.log("" + $("#tipo_transaccion"));
                                                        if ($("#tipo_persona").val() == "1") {
                                                            //$("#identificador_cli_pro").val("ING");  
                                                            identificador = "VEN";
                                                            conceptoc = "COMPROBANTE DE DIARIO: " + $("#concepto").val();
                                                        } else {
                                                            if ($("#tipo_persona").val() == "2") {
                                                                //$("#identificador_cli_pro").val("EGR") ;
                                                                identificador = "COM";
                                                                conceptoc = "COMPROBANTE DE DIARIO: " + $("#concepto").val();
                                                            } else {
                                                                if ($("#tipo_persona").val() == "3") {
                                                                    //$("#identificador_cli_pro").val("NC"); 
                                                                    identificador = "OTRO";
                                                                    conceptoc = "COMPROBANTE DE DIARIO: " + $("#concepto").val();
                                                                }
                                                            }
                                                        }




                                                        //$("#identificador_cli_pro").val("NV");
                                                        //identificador="VEN";   
                                                        //conceptoc="COMPROBANTE DE DIARIO: "+$("#concepto").val();
                                                    }
                                                    console.log("identificador de Combo tipo transacción -> " + identificador);
                                                    console.log("Conepto -> " + conceptoc);
                                                }
                                            }
                                        }
                                    }
                                }
                                $("#btnGuardar").attr("disabled", true);
                                $.ajax({
                                    type: "POST",
                                    url: "guardar_asiento_contable.php",
                                    data: "fecha_actual=" + $("#fecha_actual").val()
                                        + "&hora_actual=" + $("#hora_actual").val()
                                        + "&concepto=" + conceptoc
                                        + "&total_debe=" + $("#total_debito").val()
                                        + "&total_haber=" + $("#total_credito").val()
                                        + "&diferencia=" + $("#diferencia").val()
                                        + "&id_tipo_transaccion=" + $("#tipo_transaccion").val()
                                        + "&num_transaccion=" + $("#nro_transaccion").val()
                                        + "&id_cliente=" + $("#id_cliente").val()
                                        + "&deposito=" + $("#deposito").val()
                                        + "&cuentanum=" + $("#cuentanum").val()
                                        + "&banco=" + $("#banco").val()
                                        + "&observaciones=" + $("#observaciones").val()
                                        + "&identificador_cli_pro=" + identificador
                                        + "&valorconcepto=" + $("#valorconcepto").val()
                                        + "&campo1=" + string_v1
                                        + "&campo2=" + string_v2
                                        + "&campo3=" + string_v3
                                        + "&campo4=" + string_v4
                                        + "&campo5=" + string_v5
                                        + "&forma_pago=" + $("#forma_pago").val()
                                        + "&tipo_pago=" + $("#tipo_pago").val()
                                        + "&num_factura=" + $("#num_factura").val()
                                        + "&fecha_factura=" + $("#fecha_factura").val()
                                        + "&totalcxc=" + $("#totalcxc").val()
                                        + "&valor_pagado=" + $("#valor_pagado").val()
                                        + "&saldo2=" + $("#saldo2").val()
                                        + "&ids_fac_cp=" + $("#ids").val()
                                        + "&fecha_registro=" + $("#fecha_registro").val()
                                        + "&id_centro_costo=" + $("#sel_centro_costo").val(),
                                    success: function (data) {
                                        var val = data;
                                        if (val > 0) {
                                            alertify.alert("Asiento Contable Guardado correctamente", function () {
                                                window.open("../../reportes/transacciones.php?id=" + val, '_blank');
                                                location.reload();
                                            });
                                        } else {
                                            $("#btnGuardar")[0].disabled = false;
                                            ;
                                            alertify.alert(val);
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
function cambio_ingreso() {
    console.log($("#tipo_transaccion").val() + "tipo");
    if ($("#tipo_transaccion").val() == '2') {
        $('#btnfacturascxp').hide();
        $('#btnfacturascxc').show();
        $('#num_factura').show();
        $('#tipo_factura').show();
        $('#fecha_factura').show();
        $('#totalcxc').show();
        $('#valor_pagado').show();
        $('#saldo2').show();
        $('#forma_pago').show();
        $('#cheque_tarjeta').show();
        $('#tipo_pago').show();

        $('#factura_pagar').show();
        $('#tipo_factura_label').show();
        $('#fechadefactura').show();
        $('#totalcxp').show();
        $('#valor_pagadola').show();
        $('#Saldo').show();
        $('#formadepago_label').show();

        $('#numero_cheque').show();
        $('#pago_label').show();
    } else if ($("#tipo_transaccion").val() == '3') {

        $('#btnfacturascxc').hide();
        $('#btnfacturascxp').show();
        $('#num_factura').show();
        $('#tipo_factura').show();
        $('#fecha_factura').show();
        $('#totalcxc').show();
        $('#valor_pagado').show();
        $('#saldo2').show();
        $('#forma_pago').show();
        $('#cheque_tarjeta').show();
        $('#tipo_pago').show();

        $('#factura_pagar').show();
        $('#tipo_factura_label').show();
        $('#fechadefactura').show();
        $('#totalcxp').show();
        $('#valor_pagadola').show();
        $('#Saldo').show();
        $('#formadepago_label').show();

        $('#numero_cheque').show();
        $('#pago_label').show();
    } else {
        $('#btnfacturascxc').hide();
        $('#btnfacturascxp').hide();
        $('#num_factura').hide();
        $('#tipo_factura').hide();
        $('#fecha_factura').hide();
        $('#totalcxc').hide();
        $('#valor_pagado').hide();
        $('#saldo2').hide();
        $('#forma_pago').hide();
        $('#cheque_tarjeta').hide();
        $('#tipo_pago').hide();




        $('#factura_pagar').hide();
        $('#tipo_factura_label').hide();
        $('#fechadefactura').hide();
        $('#totalcxp').hide();
        $('#valor_pagadola').hide();
        $('#Saldo').hide();
        $('#formadepago_label').hide();

        $('#numero_cheque').hide();
        $('#pago_label').hide();
    }

}
function cambio_egreso() {

    if ($("#tipo_transaccion").val() == '3') {

        $('#btnfacturascxp').show();
        $('#num_factura').show();
        $('#tipo_factura').show();
        $('#fecha_factura').show();
        $('#totalcxc').show();
        $('#valor_pagado').show();
        $('#saldo2').show();
        $('#forma_pago').show();
        $('#cheque_tarjeta').show();
        $('#tipo_pago').show();

        $('#factura_pagar').show();
        $('#tipo_factura_label').show();
        $('#fechadefactura').show();
        $('#totalcxp').show();
        $('#valor_pagadola').show();
        $('#Saldo').show();
        $('#formadepago_label').show();

        $('#numero_cheque').show();
        $('#pago_label').show();
    } else {
        $('#btnfacturascxp').hide();
        $('#num_factura').hide();
        $('#tipo_factura').hide();
        $('#fecha_factura').hide();
        $('#totalcxc').hide();
        $('#valor_pagado').hide();
        $('#saldo2').hide();
        $('#forma_pago').hide();
        $('#cheque_tarjeta').hide();
        $('#tipo_pago').hide();




        $('#factura_pagar').hide();
        $('#tipo_factura_label').hide();
        $('#fechadefactura').hide();
        $('#totalcxp').hide();
        $('#valor_pagadola').hide();
        $('#Saldo').hide();
        $('#formadepago_label').hide();

        $('#numero_cheque').hide();
        $('#pago_label').hide();
    }
}
function modificar_asiento() {
    var tam = jQuery("#list").jqGrid("getRowData");

    if ($("#tipo_transaccion").val() == 0) {
        $("#tipo_transaccion").focus();
        alertify.alert("Elija el tipo de transacción");
    } else {
        if ($("#concepto").val() == "") {
            $("#concepto").focus();
            alertify.alert("Escriba un concepto de transacción");
        } else {
            if ($("#concepto").val().length < 20) {
                alertify.alert("El concepto debe tener mínimo 20 caracteres");
            } else {
                if ($("#total_debito").val() == "0.000" && $("#total_credito").val() == "0.000") {
                    $("#codigo_plan").focus();
                    alertify.alert("Ingrese datos en la tabla");
                } else {
                    //TODO cast float diferencia 
                    if (parseFloat($("#diferencia").val()) != 0) {
                        $("#codigo_plan").focus();
                        alertify.alert("Los valores de débito y crédito no coinciden");
                    } else {
                        //alertify.alert("Datos Correctos");
                        $("#btnGuardar").attr("disabled", true);
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
                            v1[i] = datos['id_plan'];
                            v2[i] = datos['codigo_plan'];
                            v3[i] = datos['descripcion'];
                            v4[i] = datos['debe'];
                            v5[i] = datos['haber'];
                        }

                        for (i = 0; i < fil.length; i++) {
                            string_v1 = string_v1 + "|" + v1[i];
                            string_v2 = string_v2 + "|" + v2[i];
                            string_v3 = string_v3 + "|" + v3[i];
                            string_v4 = string_v4 + "|" + v4[i];
                            string_v5 = string_v5 + "|" + v5[i];
                        }

                        var seriee = $("#serie").val();
                        $.ajax({
                            type: "POST",
                            url: "modificar_asiento_contable.php",
                            data: "id_asiento_contable=" + $("#id_asiento_contable").val()
                                + "&comprobante=" + $("#comprobante").val()
                                + "&fecha_actual=" + $("#fecha_actual").val()
                                + "&hora_actual=" + $("#hora_actual").val()
                                + "&concepto=" + $("#concepto").val()
                                + "&total_debe=" + $("#total_debito").val()
                                + "&total_haber=" + $("#total_credito").val()
                                + "&diferencia=" + $("#diferencia").val()
                                + "&id_tipo_transaccion=" + $("#tipo_transaccion").val()
                                + "&num_transaccion=" + $("#nro_transaccion").val()
                                + "&campo1=" + string_v1
                                + "&campo2=" + string_v2
                                + "&campo3=" + string_v3
                                + "&campo4=" + string_v4
                                + "&campo5=" + string_v5
                                + "&fecha_registro=" + $("#fecha_registro").val()
                                + "&id_centro_costo=" + $("#sel_centro_costo").val(),
                            success: function (data) {
                                var val = data;
                                if (val > 0) {
                                    alertify.alert("Asiento Contable Modificado Correctamente", function () {
                                        window.open("../../reportes/transacciones.php?hoja=A4&id=" + val, '_blank');
                                        location.reload();
                                    });
                                } else {
                                    alertify.alert(val);
                                }
                            }
                        });
                    }
                }
            }
        }
    }

}

function eliminar_asiento() {
    if ($("#id_asiento_contable").val() == "") {
        alertify.error("Seleccione una asiento contable");
        $("#buscar_asiento").dialog("open");
    } else {
        $("#clave_permiso").dialog("open");
    }
}

function flecha_atras() {

    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "transacciones" + "&id_tabla=" + "id_transacciones" + "&tipo=" + 3,
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#comprobante").val(val);
                var valor = $("#comprobante").val();
                // llamar datos Factura Compra
                $("#btnGuardar").attr("disabled", true);

                $("#id_asiento_contable").val(valor);
                $("#btnGuardar").attr("disabled", true);

                $("#list").jqGrid("clearGridData", true);
                $("#total_debito").val("0.000");
                $("#total_credito").val("0.000");
                $("#diferencia").val("0.000");
                $("#total_debitox").val("0.000");
                $("#total_creditox").val("0.000");
                $("#diferenciax").val("0.000");
                $("#estado h3").remove();

                obtenerCentroCosoTransaccion(valor);

                //limpiar bacos
                //  $("#banco").val("0.000"); 

                $.getJSON('retornar_asiento_contable.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 19) {
                            $("#id_asiento_contable").val(data[i]);
                            $("#digitador").val(data[i + 1]);
                            //$("#fecha_actual").val(data[i + 2]);
                            $("#concepto").val(data[i + 3]);
                            $("#total_debito").val(data[i + 4]);
                            $("#total_credito").val(data[i + 5]);
                            $("#diferencia").val(data[i + 6]);
                            $("#total_debitox").val(parseFloat(data[i + 4]).toFixed(2));
                            $("#total_creditox").val(parseFloat(data[i + 5]).toFixed(2));
                            $("#diferenciax").val(parseFloat(data[i + 6]).toFixed(2));
                            $("#tipo_transaccion").val(data[i + 7]);
                            $("#nro_transaccion").val(data[i + 8]);

                            //$("#banco").val(data[i + 10]);
                            //$("#banco").val(data[i + 11]);
                            $("#deposito").val(data[i + 11]);
                            $("#observaciones").val(data[i + 12]);
                            $("#cuentanum").val(data[i + 13]);
                            $("#banco").val(data[i + 14]);
                            $("#valorconcepto").val(data[i + 15]);
                            $("#ruc_ci").val(data[i + 16]);
                            $("#nombres_completos").val(data[i + 17]);
                            $("#fecha_registro").val(data[i + 18]);

                            console.log(" VALOR CONCEPTO: ->  " + $("#valorconcepto").val());

                            if (data[i + 9] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulado"));
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
                    } else {
                        limpiar_campos_mixto();
                    }
                });

                $.getJSON('retornar_asiento_contable2.php?com=' + valor, function (data) {
                    var tama = data.length;

                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 6) {

                            var datarow = {
                                id_plan: data[i + 1],
                                codigo_plan: data[i + 2],
                                descripcion: data[i + 3],
                                debe: data[i + 4],
                                haber: data[i + 5],
                                debex: parseFloat(data[i + 4]).toFixed(2),
                                haberx: parseFloat(data[i + 5]).toFixed(2)
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });

                /*  $.getJSON('retornar_asiento_contable3.php?com=' + valor, function(data) {
                 var tama = data.length;
                 
                 if (tama != 0) {
                 for (var i = 0; i < tama; i = i + 6) {
                 
                 var datarow = {
                 id_plan: data[i + 1], 
                 codigo_plan: data[i + 2], 
                 descripcion: data[i + 3], 
                 debe: data[i + 4], 
                 haber: data[i + 5],
                 debex: parseFloat(data[i + 4]).toFixed(2), 
                 haberx: parseFloat(data[i + 5]).toFixed(2)
                 };
                 var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                 }
                 }
                 });
                 */

                // Fin
            } else {
                alertify.alert("No hay mas registros posteriores!!");
            }
        }
    });
}


function flecha_siguiente() {
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "transacciones" + "&id_tabla=" + "id_transacciones" + "&tipo=" + 2,
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#comprobante").val(val);
                var valor = $("#comprobante").val();
                // llamar datos Factura Compra
                $("#btnGuardar").attr("disabled", true);

                $("#id_asiento_contable").val(valor);
                $("#btnGuardar").attr("disabled", true);

                $("#list").jqGrid("clearGridData", true);
                $("#total_debito").val("0.000");
                $("#total_credito").val("0.000");
                $("#diferencia").val("0.000");
                $("#total_debitox").val("0.000");
                $("#total_creditox").val("0.000");
                $("#diferenciax").val("0.000");
                $("#estado h3").remove();

                obtenerCentroCosoTransaccion(valor);

                $.getJSON('retornar_asiento_contable.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 19) {
                            $("#id_asiento_contable").val(data[i]);
                            $("#digitador").val(data[i + 1]);
                            //$("#fecha_actual").val(data[i + 2]);
                            $("#concepto").val(data[i + 3]);
                            $("#total_debito").val(data[i + 4]);
                            $("#total_credito").val(data[i + 5]);
                            $("#diferencia").val(data[i + 6]);
                            $("#total_debitox").val(parseFloat(data[i + 4]).toFixed(2));
                            $("#total_creditox").val(parseFloat(data[i + 5]).toFixed(2));
                            $("#diferenciax").val(parseFloat(data[i + 6]).toFixed(2));
                            $("#tipo_transaccion").val(data[i + 7]);
                            $("#nro_transaccion").val(data[i + 8]);


                            $("#deposito").val(data[i + 11]);
                            $("#observaciones").val(data[i + 12]);
                            $("#cuentanum").val(data[i + 13]);
                            $("#banco").val(data[i + 14]);
                            $("#valorconcepto").val(data[i + 15]);
                            $("#ruc_ci").val(data[i + 16]);
                            $("#nombres_completos").val(data[i + 17]);
                            $("#fecha_registro").val(data[i + 18]);


                            if (data[i + 9] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulado"));
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
                    } else {
                        limpiar_campos_mixto();
                    }
                });

                $.getJSON('retornar_asiento_contable2.php?com=' + valor, function (data) {
                    var tama = data.length;

                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 6) {

                            var datarow = {
                                id_plan: data[i + 1],
                                codigo_plan: data[i + 2],
                                descripcion: data[i + 3],
                                debe: data[i + 4],
                                haber: data[i + 5],
                                debex: parseFloat(data[i + 4]).toFixed(2),
                                haberx: parseFloat(data[i + 5]).toFixed(2)
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });

                /*  $.getJSON('retornar_asiento_contable3.php?com=' + valor, function(data) {
                 var tama = data.length;
                 
                 if (tama != 0) {
                 for (var i = 0; i < tama; i = i + 6) {
                 
                 var datarow = {
                 id_plan: data[i + 1], 
                 codigo_plan: data[i + 2], 
                 descripcion: data[i + 3], 
                 debe: data[i + 4], 
                 haber: data[i + 5],
                 debex: parseFloat(data[i + 4]).toFixed(2), 
                 haberx: parseFloat(data[i + 5]).toFixed(2)
                 };
                 var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                 }
                 }
                 });*/
                // fin
            } else {
                alertify.alert("No hay mas registros superiores!!");
            }
        }
    });
}

function cancelar() {
    $("#list2").jqGrid("clearGridData", true);
    $("#series").dialog("close");
    $("#descuento").focus();
    $("#btnAgregar").attr("disabled", false);
}

function limpiar_asiento() {
    location.reload();
}

function limpiar_campo() {
    if ($("#ruc_ci").val() == "") {
        $("#id_proveedor").val("");
        $("#empresa").val("");
    }
}

function limpiar_campo2() {
    if ($("#codigo_plan").val() == "") {
        $("#id_plan").val("");
        $("#codigo_plan").val("");
        $("#descripcion").val("");
        $("#debito").val("0.000");
        $("#credito").val("0.000");
    }
}

function limpiar_campo3() {
    if ($("#descripcion").val() == "") {
        $("#id_plan").val("");
        $("#codigo_plan").val("");
        $("#descripcion").val("");
        $("#debito").val("0.000");
        $("#credito").val("0.000");
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

function retornoTransaccion() {
    $.ajax({
        url: 'buscar_numero_transaccion.php',
        type: 'POST',
        data: "id_tipo_transaccion=" + $("#tipo_transaccion").val() + "&id_asiento=" + $("#id_asiento_contable").val(),
        success: function (data) {
            var val = data;
            if (val > 0) {
                $("#nro_transaccion").val(val);
                console.log("identificador   ->" + $("#tipo_transaccion").val());

            } else {
                $("#nro_transaccion").val("");
            }
        }
    });
}

//Retornar cliente o proveedor



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


function validar_tipo_documento() {
    console.log($("#tipo_persona").val());
    if ($("#tipo_persona").val() != "0") {


    } else {
        alertify.error("Error.. Debe seleccionar el tipo Documento");
        $("#ruc_ci").val("");
    }
}
function funcion_debito() {
    if ($("#debito").val() == "") {
        $("#debito").val('0.000');

    }
}
function funcion_credito() {


    if ($("#credito").val() == "") {
        $("#credito").val('0.000');

    }
}
function inicio() {
    llenarCentrosCosto();
    $("#btn_ventana_cuentas").click(function () {
        abrirVentanaCuentasContables();
    });
    $.ajax({
        type: "POST",
        url: "../../procesos/buscar_p_emision.php",
        data: "",
        success: function (data) {
            var val = data;
            if (val != 1) {
                //        $("#buscar_pv").val(val);
                $("#digitador").val("P.E." + "  " + val);
            }
        },
    });

    //    $("#debito").click(function () {
    //        $("#debito").val("");
    //    });
    $("#credito").click(function () {
        $("#credito").val("");
    });



    //    $("#debito").select(function () {
    //        $("#debito").val("");
    //    });
    $("#credito").select(function () {
        $("#credito").val("");
    });
    //    $("#debito").mousemove(function () {
    //        funcion_debito();
    //    });
    //    $("#credito").mousemove(function () {
    //        funcion_credito();
    //    });


    $("#ruc_ci").keyup(function (e) {
        validar_tipo_documento(e);
    });
    $("#tipo_persona").change(function () {
        var tipo = $("#tipo_persona").val();

        //Seleccione
        if (tipo == "0") {
            $("#ruc_ci").val("");
            $("#ruc_ci").attr("disabled", true);
            $("#nombres_completos").val("");
            $("#id_cliente").val(0);
            //console.log($("#id_cliente").val(""));
        } else {
            //Cliente
            if (tipo == "1") {
                $("#ruc_ci").val("");
                $("#ruc_ci").attr("disabled", false);
                $("#nombres_completos").val("");
                $("#ruc_ci").autocomplete({
                    source: "buscar_cliente2.php",
                    minLength: 1,
                    focus: function (event, ui) {
                        $("#ruc_ci").val(ui.item.value);
                        $("#nombres_completos").val(ui.item.nombres_completos);
                        $("#id_cliente").val(ui.item.id_cliente);
                        //$("#saldo").val(ui.item.saldo);
                        return false;
                    },
                    select: function (event, ui) {
                        $("#ruc_ci").val(ui.item.value);
                        $("#nombres_completos").val(ui.item.nombres_completos);
                        $("#id_cliente").val(ui.item.id_cliente);
                        var id = $('#id_cliente').val();
                        $('#tipo_pago').load('cargar_tipo_pago.php?cod=' + id);
                        //$("#saldo").val(ui.item.saldo);
                        //        var id = $('#id_cliente').val();
                        //        $('#tipo_pago').load('cargar_tipo_pago.php?cod=' + id);
                        return false;
                    }
                }).data("ui-autocomplete")._renderItem = function (ul, item) {
                    return $("<li>")
                        .append("<a>" + item.value + "</a>")
                        .appendTo(ul);
                };


            } else {
                //Proveedor
                if (tipo == "2") {
                    $("#ruc_ci").val("");
                    $("#ruc_ci").attr("disabled", false);
                    $("#nombres_completos").val("");
                    $("#ruc_ci").autocomplete({

                        source: "buscar_proveedor.php",
                        minLength: 1,
                        focus: function (event, ui) {
                            $("#ruc_ci").val(ui.item.value);
                            $("#nombres_completos").val(ui.item.empresa_pro);
                            $("#id_cliente").val(ui.item.id_proveedor);
                            //$("#saldo").val(ui.item.saldo);
                            //console.log("medina");
                            return false;
                        },
                        select: function (event, ui) {
                            $("#ruc_ci").val(ui.item.value);
                            $("#nombres_completos").val(ui.item.empresa_pro);
                            $("#id_cliente").val(ui.item.id_proveedor);
                            var id = $('#id_cliente').val();
                            $('#tipo_pago').load('cargar_tipo_pago2.php?cod=' + id);




                            //$("#saldo").val(ui.item.saldo);
                            //        var id = $('#id_cliente').val();
                            //        $('#tipo_pago').load('cargar_tipo_pago.php?cod=' + id);
                            return false;
                        }
                    }).data("ui-autocomplete")._renderItem = function (ul, item) {
                        return $("<li>")
                            .append("<a>" + item.value + "</a>")
                            .appendTo(ul);
                    };


                } else {
                    //Otros
                    if (tipo == "3") {
                        $("#id_cliente").val(0);
                        console.log("Este el el id_cliente_" + $("#id_cliente").val());
                        //$("#id_cliente").val("");
                        $("#ruc_ci").val("");
                        $("#ruc_ci").attr("disabled", true);
                        $("#nombres_completos").val("");
                    }
                }
            }
        }
    });

    //    $("#forma_pago").on("change", entrarIE);
    //////////////////////////////
    $("#forma_pago").on("change", function () {
        if ($("#forma_pago").val() == "CHEQUE" || $("#forma_pago").val() == "TARJETA" || $("#forma_pago").val() == "TRANSFERENCIA") {

            $("#cheque_tarjeta").attr("disabled", false);

        } else {
            $("#cheque_tarjeta").attr("disabled", true);
        }
    });
    $('#btnfacturascxc').hide();
    $('#btnfacturascxp').hide();
    $('#num_factura').hide();
    $('#tipo_factura').hide();
    $('#fecha_factura').hide();
    $('#totalcxc').hide();
    $('#valor_pagado').hide();
    $('#saldo2').hide();
    $('#forma_pago').hide();
    $('#cheque_tarjeta').hide();
    $('#tipo_pago').hide();




    $('#factura_pagar').hide();
    $('#tipo_factura_label').hide();
    $('#fechadefactura').hide();
    $('#totalcxp').hide();
    $('#valor_pagadola').hide();
    $('#Saldo').hide();
    $('#formadepago_label').hide();

    $('#numero_cheque').hide();
    $('#pago_label').hide();







    // $("#tipo_transaccion").on("change", cambio_egreso);
    $("#tipo_transaccion").on("change", cambio_ingreso);






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

    $("[data-mask]").inputmask();
    alertify.set({ delay: 4000 });
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
    $("#btnImprimir").click(function () {
        if ($("#id_asiento_contable").val() != "") {
            window.open("../../reportes/transacciones.php?id=" + $("#id_asiento_contable").val(), '_blank');
            location.reload();
        } else {
            alertify.alert("Asiento Contable No Guardado")
        }
    });
    $("#btnEliminar").click(function (e) {
        e.preventDefault();
    });
    $("#btnAceptar").click(function (e) {
        e.preventDefault();
    });
    $("#btnSalir").click(function (e) {
        e.preventDefault();
    });
    $("#btnAcceder").click(function (e) {
        e.preventDefault();
    });

    $("#btnfacturascxp").click(function (e) {
        e.preventDefault();
    });
    $("#btnfacturascxc").click(function (e) {
        e.preventDefault();
    });
    $("#btnfacturascxp").on("click", cargar_facturas);
    $("#btnfacturascxc").on("click", cargar_facturascxc);
    $("#btnGuardar").on("click", guardar_asiento);
    $("#btnModificar").on("click", modificar_asiento);
    $("#btnEliminar").on("click", eliminar_asiento);
    $("#btnNuevo").on("click", limpiar_asiento);
    $("#btnAtras").on("click", flecha_atras);
    $("#btnAdelante").on("click", flecha_siguiente);
    $("#btnAceptar").on("click", aceptarEliminar);
    $("#btnSalir").on("click", cancelarEliminar);
    $("#btnAcceder").on("click", validar_acceso);


    $("#buscar_asiento").dialog(dialogo2);
    $("#clave_permiso").dialog(dialogo3);
    $("#seguro").dialog(dialogo4);


    $("#btnBuscar").click(function () {
        $("#buscar_asiento").dialog("open");
    });

    $("#buscar_facturas").dialog(dialogo11);
    $("#buscar_facturas222").dialog(dialogo1122);
    /*$("#codigo_plan").on("keyup", limpiar_campo2);
     $("#debito").on("blur", function(){
     if($("#debito").val() == "" ) {
     $("#debito").val("0.000");
     }
     });*/
    /*$("#credito").on("blur", function(){
     if($("#credito").val() == "" ) {
     $("#credito").val("0.000");
     }
     });*/
    $("#descripcion").on("keyup", limpiar_campo3);
    $("#descripcion").on("keypress", enter2);
    $("#codigo_plan").on("keypress", enter2);
    $("#valor_pagado").on("keypress", enter);

    //

    $("#debito").on("keypress", enter3);
    $("#credito").on("keypress", enter2);


    //Validación para no letras
    $("#debito").on("keypress", punto);
    $("#credito").on("keypress", punto);
    $("#valorconcepto").on("keypress", punto);

    // atributos
    $("#tipo_transaccion").on("change", retornoTransaccion);

    // para precio
    $("#precio").on("keypress", punto);

    $("#identificador_cli_pro").val("");

    //No editar nombras completos
    $("#nombres_completos").attr("disabled", true);

    $('.ui-spinner-button').click(function () {
        $(this).siblings('input').change();
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

    $('#fecha_actual').datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');
    //    $('#fecha_registro').datepicker({
    //        dateFormat: 'yy-mm-dd'
    //    }).datepicker('setDate', 'today');
    $("#fecha_emision").datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');
    $("#fecha_caducidad").datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');
    $("#cancelacion").datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');

    // datos tabla
    jQuery("#list").jqGrid({
        datatype: "local",
        colNames: ['', 'ID Plan', 'Codigo Cuenta', 'Descripción', 'Debex', 'Haberx', 'Debee', 'Haber'],
        colModel: [
            { name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: { keys: false, delbutton: true, editbutton: false } },
            { name: 'id_plan', index: 'id_plan', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'right', frozen: true, width: 70 },
            { name: 'codigo_plan', index: 'codigo_plan', editable: false, search: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 70 },
            { name: 'descripcion', index: 'descripcion', editable: false, frozen: true, editrules: { required: true }, align: 'left', width: 290 },
            { name: 'debe', index: 'debe', hidden: true, editable: false, frozen: true, editrules: { required: true }, align: 'center', width: 90 },
            { name: 'haber', index: 'haber', hidden: true, editable: false, search: false, frozen: true, editrules: { required: true }, align: 'right', width: 90 },
            { name: 'debex', index: 'debex', editable: false, frozen: true, editrules: { required: true }, align: 'right', width: 90 },
            { name: 'haberx', index: 'haberx', editable: false, search: false, frozen: true, editrules: { required: true }, align: 'right', width: 90 },
        ],
        rowNum: 30,
        height: 220,
        width:1320,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager'),
        sortname: 'id_plan',
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
                var deb = 0;
                var cred = 0;
                var dif = 0;
                var debito1 = 0;
                var credito1 = 0;
                var diferencia1 = 0;
                var fil = jQuery("#list").jqGrid("getRowData");
                for (var t = 0; t < fil.length; t++) {
                    deb = ret.debe;
                    cred = ret.haber;
                    debito1 = parseFloat($("#total_debito").val()) - parseFloat(deb);
                    credito1 = parseFloat($("#total_credito").val()) - parseFloat(cred);

                }

                diferencia1 = debito1 - credito1;
                $("#total_debito").val(debito1);
                $("#total_credito").val(credito1);
                //TODO redondear diferencia a dos decimales
                $("#diferencia").val(diferencia1.toFixed(2));
                $("#total_debitox").val(debito1.toFixed(2));
                $("#total_creditox").val(credito1.toFixed(2));
                $("#diferenciax").val(diferencia1.toFixed(2));

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

            if (name == 'cantidad') {
                var precio = jQuery("#list").jqGrid('getCell', rowid, iCol + 1);
                var descuento = jQuery("#list").jqGrid('getCell', rowid, iCol + 2);

                var operacion = (parseFloat(val) * parseFloat(precio));
                cal = ((operacion * descuento) / 100);
                tot = (operacion - cal);

                jQuery("#list").jqGrid('setRowData', rowid, { precio_t: tot });

                if (ret.iva === "Si") {
                    var fil = jQuery("#list").jqGrid("getRowData");
                    for (var t = 0; t < fil.length; t++) {
                        var dd = fil[t];
                        if (dd['iva'] === "Si") {
                            subtotal = (subtotal + parseFloat(dd['precio_t']));
                            var sub = parseFloat(subtotal);
                            iva = ((subtotal * 15) / 100);
                            mu = (dd['cantidad'] * dd['precio_u']);
                            des = ((mu * dd['descuento']) / 100);
                            descu = (parseFloat(descu) + parseFloat(des));
                            t_fc = ((parseFloat(subtotal) + parseFloat(iva)) + parseFloat($("#total_p").val()));
                            $("#iva_producto").val("");
                        }
                    }

                    $("#total_p2").val(sub);
                    $("#iva").val(iva);
                    $("#desc").val(descu);
                    $("#tot").val(t_fc);
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
                            mu = (dd['cantidad'] * dd['precio_u']);
                            des = ((mu * dd['descuento']) / 100);
                            descu = (parseFloat(descu) + parseFloat(des));
                            t_fc = ((parseFloat(subtotal) + parseFloat(iva)) + parseFloat($("#total_p2").val()));
                            $("#iva_producto").val("");
                        }
                    }
                    $("#total_p").val(sub);
                    $("#desc").val(descu);
                    $("#tot").val(t_fc);
                }
            }

            if (name == 'precio_u') {
                var cantidad = jQuery("#list").jqGrid('getCell', rowid, iCol - 1);
                var descuento2 = jQuery("#list").jqGrid('getCell', rowid, iCol + 1);

                var operacion2 = (parseFloat(cantidad) * parseFloat(val));
                cal2 = ((operacion2 * descuento2) / 100);
                tot = (operacion2 - cal2);

                jQuery("#list").jqGrid('setRowData', rowid, { precio_t: tot });

                if (ret.iva === "Si") {
                    fil = jQuery("#list").jqGrid("getRowData");
                    for (t = 0; t < fil.length; t++) {
                        dd = fil[t];
                        if (dd['iva'] === "Si") {
                            subtotal = (subtotal + parseFloat(dd['precio_t']));
                            sub = parseFloat(subtotal);
                            iva = ((subtotal * 15) / 100);
                            mu = (dd['cantidad'] * dd['precio_u']);
                            des = ((mu * dd['descuento']) / 100);
                            descu = (parseFloat(descu) + parseFloat(des));
                            t_fc = ((parseFloat(subtotal) + parseFloat(iva)) + parseFloat($("#total_p").val()));
                            $("#iva_producto").val("");
                        }
                    }
                    $("#total_p2").val(sub);
                    $("#iva").val(iva);
                    $("#desc").val(descu);
                    $("#tot").val(t_fc);
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
                            sub = parseFloat(subtotal).toFixed(2);
                            iva = parseFloat($("#iva").val());
                            mu = (dd['cantidad'] * dd['precio_u']);
                            des = ((mu * dd['descuento']) / 100);
                            descu = (parseFloat(descu) + parseFloat(des));
                            t_fc = ((parseFloat(subtotal) + parseFloat(iva)) + parseFloat($("#total_p2").val()));
                            $("#iva_producto").val("");
                        }
                    }
                    $("#total_p").val(sub);
                    $("#desc").val(descu);
                    $("#tot").val(t_fc);
                }
            }
        }
    });
    //////////busqueda facturas////////
    jQuery("#list222").jqGrid({
        url: 'xmlFacturas_compra_1.php',
        datatype: 'xml',
        colNames: ['ID', 'Factura a Pagar', 'Tipo Factura', 'Fecha Factura', 'Total CxC', 'Valor a Pagar', 'Total F', 'Comprobante', 'Total Retenciones'],
        colModel: [
            {
                name: 'ids', index: 'ids', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'center',
                frozen: true, width: 50
            },
            {
                name: 'num_factura', index: 'num_factura', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center',
                frozen: true, width: 250
            },
            { name: 'tipo_factura', index: 'tipo_factura', editable: false, frozen: true, hidden: true, editrules: { required: true }, align: 'center', width: 250 },
            { name: 'fecha_factura', index: 'fecha_factura', editable: true, frozen: true, hidden: true, editrules: { required: true }, align: 'center', width: 180 },
            { name: 'totalcxc', index: 'totalcxc', editable: true, search: false, frozen: true, hidden: false, editrules: { required: true }, align: 'center', width: 130 },
            { name: 'valor_pagado', index: 'valor_pagado', editable: true, frozen: true, hidden: true, editrules: { required: true }, align: 'center', width: 120 },
            { name: 'saldo', index: 'saldo', editable: false, search: false, frozen: true, hidden: false, editrules: { required: true }, align: 'center', width: 130 },
            { name: 'compro', index: 'compro', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },
            { name: 'total_retenciones', index: 'total_retenciones', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 120 },
        ],
        rowNum: 10,
        width: 610,
        rowList: [10, 20, 30],
        pager: jQuery('#pager222'),
        shrinkToFit: true,
        sortorder: 'asc',
        caption: 'Lista de Facturas',
        viewrecords: true,
        ondblClickRow: function (rowid) {
            var id = jQuery("#list222").jqGrid('getGridParam', 'selrow');
            jQuery('#list222').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list222").jqGrid('getRowData', id);
                $("#ids").val(ret.ids);
                $("#num_factura").val(ret.num_factura);
                $("#tipo_factura").val(ret.tipo_factura);
                $("#fecha_factura").val(ret.fecha_factura);
                $("#totalcxc").val(ret.totalcxc);
                $("#saldo2").val(ret.saldo);
                //                $("#debito").val(ret.saldo);


                //////////////////////
                $("#buscar_facturas222").dialog("close");
                if ($("#tipo_pago").val() == "INTERNA") {
                    $("#tablaNuevo tbody").empty();
                    $.ajax({
                        type: "POST",
                        url: "buscar_pagos.php",
                        data: "id=" + ret.ids,
                        dataType: 'json',
                        success: function (response) {
                            $("#tablaNuevo").css('display', 'inline-table');
                            for (var i = 0; i < response.length; i = i + 3) {
                                $("#tablaNuevo tbody").append("<tr>" +
                                    "<td align=center >" + response[i + 0] + "</td>" +
                                    "<td align=center>" + response[i + 1] + "</td>" +
                                    "<td align=center>" + response[i + 2] + "</td>" +
                                    "<tr>");
                            }
                        }
                    });
                } else {
                    $("#tablaNuevo").css('display', 'none');
                }
                $("#valor_pagado").focus();
                // $("#list").jqGrid("clearGridData", true);
            } else {
                alertify.alert("Seleccione una Cuenta");
            }
        }
    }).jqGrid('navGrid', '#pager222', {
        add: false,
        edit: false,
        del: false,
        refresh: true,
        search: false,
        view: true
    });
    /////////////////	

    jQuery("#list222").jqGrid('navButtonAdd', '#pager222', {
        caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#list222").jqGrid('getGridParam', 'selrow');
            jQuery('#list222').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list222").jqGrid('getRowData', id);
                $("#ids").val(ret.ids);
                $("#num_factura").val(ret.num_factura);
                $("#tipo_factura").val(ret.tipo_factura);
                $("#fecha_factura").val(ret.fecha_factura);
                $("#totalcxc").val(ret.totalcxc);
                $("#saldo2").val(ret.saldo);


                //////////////////////
                $("#buscar_facturas222").dialog("close");
                if ($("#tipo_pago").val() == "INTERNA") {
                    $("#tablaNuevo tbody").empty();
                    $.ajax({
                        type: "POST",
                        url: "buscar_pagos.php",
                        data: "id=" + ret.ids,
                        dataType: 'json',
                        success: function (response) {
                            $("#tablaNuevo").css('display', 'inline-table');
                            for (var i = 0; i < response.length; i = i + 3) {
                                $("#tablaNuevo tbody").append("<tr>" +
                                    "<td align=center >" + response[i + 0] + "</td>" +
                                    "<td align=center>" + response[i + 1] + "</td>" +
                                    "<td align=center>" + response[i + 2] + "</td>" +
                                    "<tr>");
                            }
                        }
                    });
                } else {
                    $("#tablaNuevo").css('nodisplay', 'none');
                }
                $("#valor_pagado").focus();
                //$("#list").jqGrid("clearGridData", true);




            } else {
                alertify.alert("Seleccione una Cuenta");
            }
        }
    });

    jQuery("#list22").jqGrid({
        url: 'xmlFacturas_compra.php',
        datatype: 'xml',
        colNames: ['ID', 'Factura a Pagar', 'Tipo Factura', 'Fecha Factura', 'Total CxC', 'Valor a Pagar', 'Total F', 'Comprobante', 'Total Retenciones'],
        colModel: [
            {
                name: 'ids', index: 'ids', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'center',
                frozen: true, width: 50
            },
            {
                name: 'num_factura', index: 'num_factura', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center',
                frozen: true, width: 250
            },
            { name: 'tipo_factura', index: 'tipo_factura', editable: false, frozen: true, hidden: false, editrules: { required: true }, align: 'center', width: 250 },
            { name: 'fecha_factura', index: 'fecha_factura', editable: true, frozen: true, hidden: false, editrules: { required: true }, align: 'center', width: 190 },
            { name: 'totalcxc', index: 'totalcxc', editable: true, search: false, frozen: true, hidden: false, editrules: { required: true }, align: 'center', width: 130 },
            { name: 'valor_pagado', index: 'valor_pagado', editable: true, frozen: true, hidden: false, editrules: { required: true }, align: 'center', width: 150 },
            { name: 'saldo', index: 'saldo', editable: false, search: false, frozen: true, hidden: true, editrules: { required: true }, align: 'center', width: 110 },
            { name: 'compro', index: 'compro', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },

            { name: 'total_retenciones', index: 'total_retenciones', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 120 },
        ],
        rowNum: 10,
        width: 600,
        rowList: [10, 20, 30],
        pager: jQuery('#pager22'),
        shrinkToFit: true,
        sortorder: 'asc',
        caption: 'Lista de Facturas',

        viewrecords: true,
        ondblClickRow: function (rowid) {
            var id = jQuery("#list22").jqGrid('getGridParam', 'selrow');
            jQuery('#list22').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list22").jqGrid('getRowData', id);
                $("#ids").val(ret.ids);
                $("#num_factura").val(ret.num_factura);
                $("#tipo_factura").val(ret.tipo_factura);
                $("#fecha_factura").val(ret.fecha_factura);
                $("#totalcxc").val(ret.totalcxc);
                $("#saldo2").val(ret.saldo);
                $("#credito").val(ret.saldo);

                //////////////////////
                $("#buscar_facturas").dialog("close");
                $("#valor_pagado").focus();
                $("#list").jqGrid("clearGridData", true);
            } else {
                alertify.alert("Seleccione una Cuenta");
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
    /////////////////	

    jQuery("#list22").jqGrid('navButtonAdd', '#pager2', {
        caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#list22").jqGrid('getGridParam', 'selrow');
            jQuery('#list22').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list22").jqGrid('getRowData', id);
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

    // tabla series
    jQuery("#list2").jqGrid({
        datatype: "local",
        colNames: ['', 'cod_serie', 'Series'],
        colModel: [
            {
                name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions',
                formatoptions: { keys: false, delbutton: true, editbutton: false }
            },
            {
                name: 'id_series', index: 'id_series', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'center',
                frozen: true, width: 50
            },
            {
                name: 'serie', index: 'serie', editable: false, search: false, hidden: false, editrules: { edithidden: true }, align: 'center',
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

    // buscador asientos contables
    jQuery("#list3").jqGrid({
        url: 'xmlBuscarAsientoContable.php',
        datatype: 'xml',
        colNames: ['ID', 'USUARIO', 'FECHA ACTUAL', 'CONCEPTO', 'TOTAL DEBE', 'TOTAL HABER', 'TIPO TRANSACCION', 'ASIENTO NRO:', 'DEPOSITO', 'OBSERVACION', 'NUM CUENTA', 'BANCO', 'IDENTIFICADOR', 'VAL. CONCEPTO'],
        colModel: [
            { name: 'id_transacciones', index: 'id_transacciones', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },
            { name: 'usuario', index: 'usuario', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 200 },
            { name: 'fecha_actual', index: 'fecha_actual', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 150 },
            { name: 'concepto', index: 'concepto', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 300 },
            { name: 'total_debe', index: 'total_debe', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'total_haber', index: 'total_haber', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 110 },
            { name: 'descripcion', index: 'descripcion', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 150 },
            { name: 'num_transaccion', index: 'num_transaccion', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 160 },
            { name: 'deposito', index: 'deposito', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 160 },
            { name: 'observacion', index: 'observacion', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 160 },
            { name: 'num_cuenta', index: 'num_cuenta', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 160 },
            { name: 'banco', index: 'banco', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 160 },
            { name: 'identificador_cli_pro', index: 'identificador_cli_pro', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 160 },
            { name: 'valor_concepto', index: 'valor_concepto', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 160 },

        ],
        rowNum: 30,
        width: 1220,
        height: 280,
        sortable: true,
        rowList: [10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
        pager: jQuery('#pager3'),
        sortname: 'id_transacciones',
        sortorder: 'desc',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list3").jqGrid('getGridParam', 'selrow');
            jQuery('#list3').jqGrid('restoreRow', id);

            if (id) {
                var ret = jQuery("#list3").jqGrid('getRowData', id);
                var valor = ret.id_transacciones;

                // seleccionar asiento contable

                $("#id_asiento_contable").val(valor);
                $("#btnGuardar").attr("disabled", true);

                $("#list").jqGrid("clearGridData", true);
                $("#total_debito").val("0.000");
                $("#total_credito").val("0.000");
                $("#diferencia").val("0.000");
                $("#total_debitox").val("0.000");
                $("#total_creditox").val("0.000");
                $("#diferenciax").val("0.000");
                obtenerCentroCosoTransaccion(valor);

                $.getJSON('retornar_asiento_contable.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 19) {
                            $("#id_asiento_contable").val(data[i]);
                            $("#digitador").val(data[i + 1]);
                            //$("#fecha_actual").val(data[i + 2]);
                            $("#concepto").val(data[i + 3]);
                            $("#total_debito").val(data[i + 4]);
                            $("#total_credito").val(data[i + 5]);
                            $("#diferencia").val(data[i + 6]);
                            $("#total_debitox").val(parseFloat(data[i + 4]).toFixed(2));
                            $("#total_creditox").val(parseFloat(data[i + 5]).toFixed(2));
                            $("#diferenciax").val(parseFloat(data[i + 6]).toFixed(2));
                            $("#tipo_transaccion").val(data[i + 7]);
                            $("#nro_transaccion").val(data[i + 8]);

                            //$("#banco").val(data[i + 10]);
                            //$("#banco").val(data[i + 11]);
                            $("#deposito").val(data[i + 11]);
                            $("#observaciones").val(data[i + 12]);
                            $("#cuentanum").val(data[i + 13]);
                            $("#banco").val(data[i + 14]);
                            $("#valorconcepto").val(data[i + 15]);
                            $("#ruc_ci").val(data[i + 16]);
                            $("#nombres_completos").val(data[i + 17]);
                            $("#fecha_registro").val(data[i + 18]);

                            console.log(" VALOR CONCEPTO: ->  " + $("#valorconcepto").val());

                            if (data[i + 9] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulado"));
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
                    } else {

                        limpiar_campos_mixto();
                    }
                });

                $.getJSON('retornar_asiento_contable2.php?com=' + valor, function (data) {
                    var tama = data.length;

                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 6) {

                            var datarow = {
                                id_plan: data[i + 1],
                                codigo_plan: data[i + 2],
                                descripcion: data[i + 3],
                                debe: data[i + 4],
                                haber: data[i + 5],
                                debex: parseFloat(data[i + 4]).toFixed(2),
                                haberx: parseFloat(data[i + 5]).toFixed(2)
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });

                $("#buscar_asiento").dialog("close");

            } else {
                alertify.alert("Seleccione una Asiento Contable");
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
        }, {
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
        });

    jQuery("#list3").jqGrid('navButtonAdd', '#pager3', {
        caption: "AñadirAS",
        onClickButton: function () {
            var id = jQuery("#list3").jqGrid('getGridParam', 'selrow');
            jQuery('#list3').jqGrid('restoreRow', id);

            if (id) {
                var ret = jQuery("#list3").jqGrid('getRowData', id);
                var valor = ret.id_transacciones;

                // seleccionar asiento contable

                $("#id_asiento_contable").val(valor);
                $("#btnGuardar").attr("disabled", true);

                $("#list").jqGrid("clearGridData", true);
                $("#total_debito").val("0.000");
                $("#total_credito").val("0.000");
                $("#diferencia").val("0.000");
                $("#total_debitox").val("0.000");
                $("#total_creditox").val("0.000");
                $("#diferenciax").val("0.000");

                $.getJSON('retornar_asiento_contable.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 19) {
                            $("#id_asiento_contable").val(data[i]);
                            $("#digitador").val(data[i + 1]);
                            //$("#fecha_actual").val(data[i + 2]);
                            $("#concepto").val(data[i + 3]);
                            $("#total_debito").val(data[i + 4]);
                            $("#total_credito").val(data[i + 5]);
                            $("#diferencia").val(data[i + 6]);
                            $("#total_debitox").val(parseFloat(data[i + 4]).toFixed(2));
                            $("#total_creditox").val(parseFloat(data[i + 5]).toFixed(2));
                            $("#diferenciax").val(parseFloat(data[i + 6]).toFixed(2));
                            $("#tipo_transaccion").val(data[i + 7]);
                            $("#nro_transaccion").val(data[i + 8]);
                            $("#estado h3").remove();
                            $("#deposito").val(data[i + 11]);
                            $("#observaciones").val(data[i + 12]);
                            $("#cuentanum").val(data[i + 13]);
                            $("#banco").val(data[i + 14]);
                            $("#valorconcepto").val(data[i + 15]);
                            $("#ruc_ci").val(data[i + 16]);
                            $("#nombres_completos").val(data[i + 17]);
                            $("#fecha_registro").val(data[i + 18]);
                            if (data[i + 9] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulado"));
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
                    } else {
                        limpiar_campos_mixto();
                    }
                });

                $.getJSON('retornar_asiento_contable2.php?com=' + valor, function (data) {
                    var tama = data.length;

                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 6) {

                            var datarow = {
                                id_plan: data[i + 1],
                                codigo_plan: data[i + 2],
                                descripcion: data[i + 3],
                                debe: data[i + 4],
                                haber: data[i + 5],
                                debex: parseFloat(data[i + 4]).toFixed(2),
                                haberx: parseFloat(data[i + 5]).toFixed(2)
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });
                $.getJSON('retornar_asiento_contable3.php?com=' + valor, function (data) {
                    var tama = data.length;

                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 6) {

                            var datarow = {
                                id_plan: data[i + 1],
                                codigo_plan: data[i + 2],
                                descripcion: data[i + 3],
                                debe: data[i + 4],
                                haber: data[i + 5],
                                debex: parseFloat(data[i + 4]).toFixed(2),
                                haberx: parseFloat(data[i + 5]).toFixed(2)
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });

                $("#buscar_asiento").dialog("close");
            } else {
                alertify.alert("Seleccione un Asiento Contable");
            }
        }
    });

//    jQuery(window).bind('resize', function () {
//        jQuery("#list").setGridWidth(jQuery('#grid_container').width(), true);
//    }).trigger('resize');
}

function abrirVentanaCuentasContables() {
    let params = `scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,
    width=1300,height=600,left=50,top=100`;

    let win = open('../plan_cuentas/', 'PLAN DE CUENTAS', params);
    win.locationbar = false;
    win.onload = function () {
        let toggleel = win.document.getElementsByClassName("sidebar-toggle")[0];
        let header = win.document.getElementsByTagName("header")[0];
        toggleel.click()
        header.style.display = 'none';
    };

}

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

function obtenerCentroCosoTransaccion(idtransaccion) {
    return $.ajax({
        url: "retornar_centro_costo.php",
        method: "GET",
        dataType: "json",
        data: { id_transaccion: idtransaccion },
        success: function (data) {
            if (!!data.id_centro_costo) {
                $("#sel_centro_costo").val(data.id_centro_costo);
            } else {
                $("#sel_centro_costo").val("");
            }
        }
    });
}