$(document).on("ready", inicio);
var calculoIVA = 0;
var idRegistro;

function evento(e) {
    e.preventDefault();
}

var dialogos = {
    autoOpen: false,
    resizable: false,
    width: 860,
    height: 560,
    modal: true
};

var dialogo2 = {
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 350,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind"
}

var dialogo3 = {
    autoOpen: false,
    resizable: false,
    width: 1000,
    height: 350,
    modal: true,
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
    if (hours == 0)
        hours = 12;
    if (minutes <= 9)
        minutes = "0" + minutes;
    if (seconds <= 9)
        seconds = "0" + seconds;
    $("#hora_actual").val(hours + ":" + minutes + ":" + seconds + " " + dn);

    setTimeout("show()", 1000);
}

function enter(e) {
    if (e.which == 13 || e.keyCode == 13) {
        entrar();
        return false;
    }
    return true;
}

function enter1(e) {
    if (e.which == 13 || e.keyCode == 13) {
        entrar2();
        return false;
    }
    return true;
}

function enter2(e) {
    if (e.which == 13 || e.keyCode == 13) {
        comprobar2();
        return false;
    }
    return true;
}

function enter3(e) {
    if (e.which == 13 || e.keyCode == 13) {
        comprobar();
        return false;
    }
    return true;
}

function comprobar() {
    if ($("#id_cliente").val() == "") {
        $("#ruc_ci").focus();
        alertify.error("Ingrese un cliente");
    }
}

function entrar() {
    if ($("#cod_producto").val() == "") {
        $("#codigo_barras").focus();
        alertify.error("Ingrese un producto");
    } else {
//        if ($("#codigo").val() == "") {
//            $("#codigo").focus();
//            alertify.error("Ingrese un producto");
//        } else {
        if ($("#producto").val() == "") {
            $("#producto").focus();
            alertify.error("Ingrese un producto");
        } else {
            if ($("#cantidad").val() == "") {
                $("#cantidad").focus();
            } else {
                $("#p_venta").focus();
            }
        }
//        }
    }
}

function ValidNum() {
    if (event.keyCode < 48 || event.keyCode > 57) {
        event.returnValue = false;
    }
    return true;
}

function entrar2() {
    if ($("#cod_producto").val() == "") {
        $("#codigo_barras").focus();
        alertify.error("Ingrese un producto");
    } else {
//        if ($("#codigo").val() == "") {
//            $("#codigo").focus();
//            alertify.error("Ingrese un producto");
//        } else {
        if ($("#producto").val() == "") {
            $("#producto").focus();
            alertify.error("Ingrese un producto");
        } else {
            if ($("#cantidad").val() == "") {
                $("#cantidad").focus();
            } else {
                if ($("#p_venta").val() == "") {
                    $("#p_venta").focus();
                    alertify.error("Ingrese un precio");
                } else {
                    $("#descuento").focus();
                }
            }
        }
//        }
    }
}

function limpiar_input() {
    $("#cod_producto").val("");
    $("#codigo_barras").val("");
    $("#codigo").val("");
    $("#venta_iva").val("0.00");
    $("#producto").val("");
    $("#cantidad").val("");
    $("#p_venta").val("");
    $("#descuento").val("");
    $("#disponibles").val("");
    $("#iva_producto").val("");
    $("#des").val("");
    $("#incluye").val("");
}
function numFormatter(d) {
    return new Intl.NumberFormat("en-US", {
        minimumFractionDigits: d,
        maximumFractionDigits: d,
        useGrouping: false,
    });
}
function comprobar2() {
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

    var subtotal0 = 0;
    var subtotal12 = 0;
    var iva12 = 0;
    var total_total = 0;
    var descu_total = 0;

    if ($("#cod_producto").val() == "") {
        $("#codigo_barras").focus();
        alertify.error("Ingrese un producto");
    } else {
//        if ($("#codigo").val() == "") {
//            $("#codigo").focus();
//            alertify.error("Ingrese un producto");
//        } else {
        if ($("#producto").val() == "") {
            $("#producto").focus();
            alertify.error("Ingrese un producto");
        } else {
            if ($("#cantidad").val() == "") {
                $("#cantidad").focus();
            } else {
                if ($("#p_venta").val() == "") {
                    $("#p_venta").focus();
                    alertify.error("Ingrese un precio");
                } else {
                    if ($("#inventar").val() == "Si") {
                        if ($("#disponibles").val() == "") {
                            parseInt($("#disponibles").val(0));
                        }
                        if (parseInt($("#cantidad").val()) > parseInt($("#disponibles").val())) {
                            $("#cantidad").focus();
                            alertify.error(
                                    "Error.. Fuera de Stock cantidad disponible: " +
                                    $("#disponibles").val()
                                    );
                        } else {
                            var filas = jQuery("#list").jqGrid("getRowData");
                            var descuento = 0;
                            var total = 0;
                            var su = 0;
                            var desc = 0;
                            var precio = 0;
                            var multi = 0;
                            var flotante = 0;
                            var resultado = 0;
                            var repe = 0;
                            var suma = 0;

                            if (filas.length == 0) {
                                if ($("#descuento").val() != "") {
                                    desc = $("#descuento").val();
                                    precio = parseFloat($("#p_venta").val());
                                    multi = parseFloat($("#cantidad").val()) * parseFloat(precio);
                                    descuento = ((multi * parseFloat(desc)) / 100);
                                    flotante = parseFloat(descuento);
                                    resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                    total = multi - resultado;
                                } else {
                                    desc = 0;
                                    precio = parseFloat($("#p_venta").val());
                                    multi = parseFloat($("#cantidad").val()) * parseFloat(precio);
                                    descuento = ((multi * parseFloat(desc)) / 100);
                                    flotante = parseFloat(descuento);
                                    resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                    total = parseFloat(multi);
                                }

                                var datarow = {
                                    cod_producto: $("#cod_producto").val(),
                                    codigo: $("#codigo").val(),
                                    detalle: $("#producto").val(),
                                    cantidad: $("#cantidad").val(),
                                    precio_u: precio,
                                    descuento: desc,
                                    cal_des: resultado,
                                    total: total,
                                    precio_ux: precio.toFixed(4),
                                    descuentox: parseFloat(desc).toFixed(4),
                                    cal_desx: resultado.toFixed(4),
                                    totalx: total.toFixed(4),
                                    iva: $("#iva_producto").val(),
                                    pendiente: parseFloat($("#venta_iva").val()).toFixed(2),
                                    incluye: $("#incluye").val()
                                };

                                su = jQuery("#list").jqGrid('addRowData', $("#cod_producto").val(), datarow);
                                limpiar_input();
                            } else {
                                var result = 0;
                                var iva1 = 0;
                                var iva_pventa = 0;
                                for (var i = 0; i < filas.length; i++) {
                                    var id = filas[i];

                                    if (id['cod_producto'] == $("#cod_producto").val()) {
                                        repe = 1;
                                        var can = id["cantidad"];
                                        if (id["iva"] == "Si") {
                                            suma = parseFloat(can) + parseFloat($("#cantidad").val());
                                            suma = Number(suma.toFixed(2));
                                            iva1 = (id["precio_u"] * calculoIVA) / 100;
                                            iva_pventa = iva1 + parseFloat(id["precio_u"]);
                                            result = suma * numFormatter(2).format(iva_pventa);
                                        } else {
                                            suma = parseFloat(can) + parseFloat($("#cantidad").val());
                                            suma = Number(suma.toFixed(2));
                                            result = suma * numFormatter(2).format(iva_pventa);
                                        }
                                    }
                                }

                                if (repe == 1) {
                                    suma = parseInt(can) + parseInt($("#cantidad").val());

                                    if ($("#descuento").val() != "") {
                                        desc = $("#descuento").val();
                                        precio = parseFloat($("#p_venta").val());
                                        multi = parseFloat(suma) * parseFloat(precio);
                                        descuento = ((multi * parseFloat(desc)) / 100);
                                        flotante = parseFloat(descuento);
                                        resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                        total = multi - resultado;
                                    } else {
                                        desc = 0;
                                        precio = parseFloat($("#p_venta").val());
                                        multi = parseFloat($("#cantidad").val()) * parseFloat(precio);
                                        descuento = ((multi * parseFloat(desc)) / 100);
                                        flotante = parseFloat(descuento);
                                        resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                        total = parseFloat(multi);
                                    }

                                    datarow = {
                                        cod_producto: $("#cod_producto").val(),
                                        codigo: $("#codigo").val(),
                                        detalle: $("#producto").val(),
                                        cantidad: suma,
                                        precio_u: precio,
                                        descuento: desc,
                                        cal_des: resultado,
                                        total: total,
                                        precio_ux: precio.toFixed(4),
                                        descuentox: parseFloat(desc).toFixed(4),
                                        cal_desx: resultado.toFixed(4),
                                        totalx: total.toFixed(4),
                                        iva: $("#iva_producto").val(),
                                        pendiente: numFormatter(2).format(result),
                                        incluye: $("#incluye").val()
                                    };

                                    su = jQuery("#list").jqGrid('setRowData', $("#cod_producto").val(), datarow);
                                    limpiar_input();
                                } else {
                                    if (filas.length < 26) {
                                        if ($("#descuento").val() != "") {
                                            desc = $("#descuento").val();
                                            precio = parseFloat($("#p_venta").val());
                                            multi = parseFloat($("#cantidad").val()) * parseFloat(precio);
                                            descuento = ((multi * parseFloat(desc)) / 100);
                                            flotante = parseFloat(descuento);
                                            resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                            total = multi - resultado;
                                        } else {
                                            desc = 0;
                                            precio = parseFloat($("#p_venta").val());
                                            multi = parseFloat($("#cantidad").val()) * parseFloat(precio);
                                            descuento = ((multi * parseFloat(desc)) / 100);
                                            flotante = parseFloat(descuento);
                                            resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                            total = parseFloat(multi);
                                        }

                                        datarow = {
                                            cod_producto: $("#cod_producto").val(),
                                            codigo: $("#codigo").val(),
                                            detalle: $("#producto").val(),
                                            cantidad: $("#cantidad").val(),
                                            precio_u: precio,
                                            descuento: desc,
                                            cal_des: resultado,
                                            total: total,
                                            precio_ux: precio.toFixed(4),
                                            descuentox: parseFloat(desc).toFixed(4),
                                            cal_desx: resultado.toFixed(4),
                                            totalx: total.toFixed(4),
                                            iva: $("#iva_producto").val(),
                                            pendiente: parseFloat($("#venta_iva").val()).toFixed(2),
                                            incluye: $("#incluye").val()
                                        };

                                        su = jQuery("#list").jqGrid('addRowData', $("#cod_producto").val(), datarow);
                                        limpiar_input();
                                    } else {
                                        alertify.error("Error... Alcanzo el limite máximo de Items");
                                    }
                                }
                            }

                            // proceso incluye iva
                            var subtotal = 0;
                            var sub = 0;
                            var sub1 = 0;
                            var sub2 = 0;
                            var iva = 0;
                            var iva1 = 0;
                            var iva2 = 0;
                            // fin                                                     

                            var fil = jQuery("#list").jqGrid("getRowData");
                            console.log(fil);
                            for (var t = 0; t < fil.length; t++) {
                                var dd = fil[t];
                                if (dd['iva'] == "Si") {
                                    if (dd['incluye'] == "No") {
                                        subtotal = dd['total'];
                                        sub1 = subtotal;
                                        //iva1 = (sub1 * 0.12).toFixed(3); 
                                        iva1 = sub1 * (calculoIVA / 100);

                                        subtotal0 = parseFloat(subtotal0) + 0;
                                        subtotal12 = parseFloat(subtotal12) + parseFloat(sub1);
                                        descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);
                                        iva12 = parseFloat(iva12) + parseFloat(iva1);

                                        subtotal0 = parseFloat(subtotal0);
                                        subtotal12 = parseFloat(subtotal12);
                                        iva12 = parseFloat(iva12);
                                        descu_total = parseFloat(descu_total);
                                    } else {
                                        if (dd['incluye'] == "Si") {
                                            subtotal = dd['total'];
                                            //sub2 = (subtotal / 1.12).toFixed(3);
                                            //iva2 = (sub2 * 0.12).toFixed(3);
                                            sub2 = subtotal / ((calculoIVA / 100) + 1);
                                            iva2 = sub2 * (calculoIVA / 100);

                                            subtotal0 = parseFloat(subtotal0) + 0;
                                            subtotal12 = parseFloat(subtotal12) + parseFloat(sub2);
                                            iva12 = parseFloat(iva12) + parseFloat(iva2);
                                            descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);

                                            subtotal0 = parseFloat(subtotal0);
                                            subtotal12 = parseFloat(subtotal12);
                                            iva12 = parseFloat(iva12);
                                            descu_total = parseFloat(descu_total);
                                        }
                                    }
                                } else {
                                    if (dd['iva'] == "No") {
                                        subtotal = dd['total'];
                                        sub = subtotal;

                                        subtotal0 = parseFloat(subtotal0) + parseFloat(sub);
                                        subtotal12 = parseFloat(subtotal12) + 0;
                                        iva12 = parseFloat(iva12) + 0;
                                        descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);

                                        subtotal0 = parseFloat(subtotal0);
                                        subtotal12 = parseFloat(subtotal12);
                                        iva12 = parseFloat(iva12);
                                        descu_total = parseFloat(descu_total);
                                    }
                                }
                            }

                            total_total = parseFloat(total_total) + (parseFloat(subtotal0) + parseFloat(subtotal12) + parseFloat(iva12));
                            total_total = parseFloat(total_total);

                            $("#total_p").val(subtotal0);
                            $("#total_p2").val(subtotal12);
                            $("#iva").val(iva12);
                            $("#desc").val(descu_total);
                            $("#tot").val(total_total);
                            $("#total_px").val(subtotal0.toFixed(4));
                            $("#total_p2x").val(subtotal12.toFixed(4));
                            $("#ivax").val(iva12.toFixed(4));
                            $("#descx").val(descu_total.toFixed(4));
                            $("#totx").val(total_total.toFixed(2));
                            $("#codigo_barras").focus();
                        }
                    } else {

                        var filas = jQuery("#list").jqGrid("getRowData");
                        var descuento = 0;
                        var total = 0;
                        var su = 0;
                        var desc = 0;
                        var precio = 0;
                        var multi = 0;
                        var flotante = 0;
                        var resultado = 0;
                        var repe = 0;
                        var suma = 0;

                        if (filas.length == 0) {
                            if ($("#descuento").val() != "") {
                                desc = $("#descuento").val();
                                precio = parseFloat($("#p_venta").val());
                                multi = parseFloat($("#cantidad").val()) * parseFloat(precio);
                                descuento = ((multi * parseFloat(desc)) / 100);
                                flotante = parseFloat(descuento);
                                resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                total = multi - resultado;
                            } else {
                                desc = 0;
                                precio = parseFloat($("#p_venta").val());
                                multi = parseFloat($("#cantidad").val()) * parseFloat(precio);
                                descuento = ((multi * parseFloat(desc)) / 100);
                                flotante = parseFloat(descuento);
                                resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                total = parseFloat(multi);
                            }

                            var datarow = {
                                cod_producto: $("#cod_producto").val(),
                                codigo: $("#codigo").val(),
                                detalle: $("#producto").val(),
                                cantidad: $("#cantidad").val(),
                                precio_u: precio,
                                descuento: desc,
                                cal_des: resultado,
                                total: total,
                                precio_ux: precio.toFixed(4),
                                descuentox: parseFloat(desc).toFixed(4),
                                cal_desx: resultado.toFixed(4),
                                totalx: total.toFixed(4),
                                iva: $("#iva_producto").val(),
                                pendiente: parseFloat($("#venta_iva").val()).toFixed(2),
                                incluye: $("#incluye").val()
                            };

                            su = jQuery("#list").jqGrid('addRowData', $("#cod_producto").val(), datarow);
                            limpiar_input();
                        } else {
                            var result = 0;
                            var iva1 = 0;
                            var iva_pventa = 0;
                            for (var i = 0; i < filas.length; i++) {
                                var id = filas[i];

                                if (id['cod_producto'] == $("#cod_producto").val()) {
                                    repe = 1;
                                    var can = id["cantidad"];
                                    if (id["iva"] == "Si") {
                                        suma = parseFloat(can) + parseFloat($("#cantidad").val());
                                        suma = Number(suma.toFixed(2));
                                        iva1 = (id["precio_u"] * calculoIVA) / 100;
                                        iva_pventa = iva1 + parseFloat(id["precio_u"]);
                                        result = suma * numFormatter(2).format(iva_pventa);
                                    } else {
                                        suma = parseFloat(can) + parseFloat($("#cantidad").val());
                                        suma = Number(suma.toFixed(2));
                                        result = suma * numFormatter(2).format(iva_pventa);
                                    }
                                }
                            }

                            if (repe == 1) {
                                suma = parseInt(can) + parseInt($("#cantidad").val());

                                if ($("#descuento").val() != "") {
                                    desc = $("#descuento").val();
                                    precio = parseFloat($("#p_venta").val());
                                    multi = parseFloat(suma) * parseFloat(precio);
                                    descuento = ((multi * parseFloat(desc)) / 100);
                                    flotante = parseFloat(descuento);
                                    resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                    total = multi - resultado;
                                } else {
                                    desc = 0;
                                    precio = parseFloat($("#p_venta").val());
                                    multi = parseFloat($("#cantidad").val()) * parseFloat(precio);
                                    descuento = ((multi * parseFloat(desc)) / 100);
                                    flotante = parseFloat(descuento);
                                    resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                    total = parseFloat(multi);
                                }

                                datarow = {
                                    cod_producto: $("#cod_producto").val(),
                                    codigo: $("#codigo").val(),
                                    detalle: $("#producto").val(),
                                    cantidad: suma,
                                    precio_u: precio,
                                    descuento: desc,
                                    cal_des: resultado,
                                    total: total,
                                    precio_ux: precio.toFixed(4),
                                    descuentox: parseFloat(desc).toFixed(4),
                                    cal_desx: resultado.toFixed(4),
                                    totalx: total.toFixed(4),
                                    iva: $("#iva_producto").val(),
                                    pendiente: numFormatter(2).format(result),
                                    incluye: $("#incluye").val()
                                };

                                su = jQuery("#list").jqGrid('setRowData', $("#cod_producto").val(), datarow);
                                limpiar_input();
                            } else {
                                if (filas.length < 26) {
                                    if ($("#descuento").val() != "") {
                                        desc = $("#descuento").val();
                                        precio = parseFloat($("#p_venta").val());
                                        multi = parseFloat($("#cantidad").val()) * parseFloat(precio);
                                        descuento = ((multi * parseFloat(desc)) / 100);
                                        flotante = parseFloat(descuento);
                                        resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                        total = multi - resultado;
                                    } else {
                                        desc = 0;
                                        precio = parseFloat($("#p_venta").val());
                                        multi = parseFloat($("#cantidad").val()) * parseFloat(precio);
                                        descuento = ((multi * parseFloat(desc)) / 100);
                                        flotante = parseFloat(descuento);
                                        resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                        total = parseFloat(multi);
                                    }

                                    datarow = {
                                        cod_producto: $("#cod_producto").val(),
                                        codigo: $("#codigo").val(),
                                        detalle: $("#producto").val(),
                                        cantidad: $("#cantidad").val(),
                                        precio_u: precio,
                                        descuento: desc,
                                        cal_des: resultado,
                                        total: total,
                                        precio_ux: precio.toFixed(4),
                                        descuentox: parseFloat(desc).toFixed(4),
                                        cal_desx: resultado.toFixed(4),
                                        totalx: total.toFixed(4),
                                        iva: $("#iva_producto").val(),
                                        pendiente: parseFloat($("#venta_iva").val()).toFixed(2),
                                        incluye: $("#incluye").val()
                                    };

                                    su = jQuery("#list").jqGrid('addRowData', $("#cod_producto").val(), datarow);
                                    limpiar_input();
                                } else {
                                    alertify.error("Error... Alcanzo el limite máximo de Items");
                                }
                            }
                        }

                        // proceso incluye iva
                        var subtotal = 0;
                        var sub = 0;
                        var sub1 = 0;
                        var sub2 = 0;
                        var iva = 0;
                        var iva1 = 0;
                        var iva2 = 0;
                        // fin                                                     

                        var fil = jQuery("#list").jqGrid("getRowData");
                        console.log(fil);
                        for (var t = 0; t < fil.length; t++) {
                            var dd = fil[t];
                            if (dd['iva'] == "Si") {
                                if (dd['incluye'] == "No") {
                                    subtotal = dd['total'];
                                    sub1 = subtotal;
                                    //iva1 = (sub1 * 0.12).toFixed(3); 
                                    iva1 = sub1 * (calculoIVA / 100);

                                    subtotal0 = parseFloat(subtotal0) + 0;
                                    subtotal12 = parseFloat(subtotal12) + parseFloat(sub1);
                                    descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);
                                    iva12 = parseFloat(iva12) + parseFloat(iva1);

                                    subtotal0 = parseFloat(subtotal0);
                                    subtotal12 = parseFloat(subtotal12);
                                    iva12 = parseFloat(iva12);
                                    descu_total = parseFloat(descu_total);
                                } else {
                                    if (dd['incluye'] == "Si") {
                                        subtotal = dd['total'];
                                        //sub2 = (subtotal / 1.12).toFixed(3);
                                        //iva2 = (sub2 * 0.12).toFixed(3);
                                        sub2 = subtotal / ((calculoIVA / 100) + 1);
                                        iva2 = sub2 * (calculoIVA / 100);

                                        subtotal0 = parseFloat(subtotal0) + 0;
                                        subtotal12 = parseFloat(subtotal12) + parseFloat(sub2);
                                        iva12 = parseFloat(iva12) + parseFloat(iva2);
                                        descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);

                                        subtotal0 = parseFloat(subtotal0);
                                        subtotal12 = parseFloat(subtotal12);
                                        iva12 = parseFloat(iva12);
                                        descu_total = parseFloat(descu_total);
                                    }
                                }
                            } else {
                                if (dd['iva'] == "No") {
                                    subtotal = dd['total'];
                                    sub = subtotal;

                                    subtotal0 = parseFloat(subtotal0) + parseFloat(sub);
                                    subtotal12 = parseFloat(subtotal12) + 0;
                                    iva12 = parseFloat(iva12) + 0;
                                    descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);

                                    subtotal0 = parseFloat(subtotal0);
                                    subtotal12 = parseFloat(subtotal12);
                                    iva12 = parseFloat(iva12);
                                    descu_total = parseFloat(descu_total);
                                }
                            }
                        }

                        total_total = parseFloat(total_total) + (parseFloat(subtotal0) + parseFloat(subtotal12) + parseFloat(iva12));
                        total_total = parseFloat(total_total);

                        $("#total_p").val(subtotal0);
                        $("#total_p2").val(subtotal12);
                        $("#iva").val(iva12);
                        $("#desc").val(descu_total);
                        $("#tot").val(total_total);
                        $("#total_px").val(subtotal0.toFixed(4));
                        $("#total_p2x").val(subtotal12.toFixed(4));
                        $("#ivax").val(iva12.toFixed(4));
                        $("#descx").val(descu_total.toFixed(4));
                        $("#totx").val(total_total.toFixed(2));
                        $("#codigo_barras").focus();

                    }
                }
            }
//            }
        }
    }
}

///////

function guardar_proforma() {
    var tam = jQuery("#list").jqGrid("getRowData");

    if ($("#id_cliente").val() == "") {
        $("#ruc_ci").focus();
        alertify.error("Ingrese un cliente");
    } else {
        if ($("#tipo_precio").val() == "") {
            $("#tipo_precio").focus();
            alertify.error("Error... Seleccione tipo de precio");
        } else {
            if (tam.length == 0) {
                alertify.alert("Error... Llene productos en la proforma");
            } else {
                if ($("#id_vendedor").val() === "") {
                    $("#ruc_ci_cli").focus();
                    alertify.error("Ingrese un Vendedor");
                } else {
                    $("#btnGuardar").attr("disabled", true);

                    $.ajax({
                        type: "POST",
                        url: "guardar_proforma.php",
                        data: datosEnviar(),
                        dataType: "json",
                        success: function (data) {
                            if (data == 0) {
                                alertify.alert(
                                        `<b>Error.. No se pudo guardar.</b>`,
                                        function (e) {
                                            cerrarDialogosPermiso();
                                        }
                                );
                                $("#alertify-ok").css("background-color", "red");
                                return;
                            }
                            alertify.alert("Proforma Guardada Correctamente", function () {
                                window.open("../../reportes/proforma_tecnico.php?id=" + data, '_blank');
                                location.reload();
                            });
                        }
                    });
                }
            }
        }
    }
}

function modificar_proforma() {
    var tam = jQuery("#list").jqGrid("getRowData");

    if ($("#id_cliente").val() == "") {
        $("#ruc_ci").focus();
        alertify.error("Ingrese un cliente");
    } else {
        if ($("#tipo_precio").val() == "") {
            $("#tipo_precio").focus();
            alertify.error("Error... Seleccione tipo de precio");
        } else {
            if (tam.length == 0) {
                alertify.alert("Error... Llene productos en la proforma");
            } else {
                $("#btnModificar").attr("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "modificar_proforma.php",
                    data: datosEnviar(),
                    dataType: "json",
                    success: function (data) {
                        if (data == 0) {
                            alertify.alert(
                                    `<b>Error.. No se pudo guardar.</b>`,
                                    function (e) {
                                        cerrarDialogosPermiso();
                                    }
                            );
                            $("#alertify-ok").css("background-color", "red");
                            return;
                        }
                        alertify.alert("Proforma Guardada Correctamente", function () {
                            window.open("../../reportes/proforma_tecnico.php?id=" + data, '_blank');
                            location.reload();
                        });
                    }
                });
            }
        }
    }
}

function productosEnviar() {
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

    campos = "campo1=" + string_v1
            + "&campo2=" + string_v2
            + "&campo3=" + string_v3
            + "&campo4=" + string_v4
            + "&campo5=" + string_v5;

    return campos;

}

function datosEnviar() {
    var id_chec = '';
    if (document.getElementById('tipo_entrega').checked == true) {
        id_chec = '1';
    } else
    {
        id_chec = '0';
    }
    return "id_cliente=" + $("#id_cliente").val()
            + "&comprobante=" + $("#comprobante").val()
            + "&fecha_actual=" + $("#fecha_actual").val()
            + "&hora_actual=" + $("#hora_actual").val()
            + "&tipo_precio=" + $("#tipo_precio").val()
            + "&tarifa0=" + $("#total_p").val()
            + "&tarifa12=" + $("#total_p2").val()
            + "&iva=" + $("#iva").val()
            + "&desc=" + $("#desc").val()
            + "&tot=" + $("#tot").val()
            + "&" + productosEnviar()
            + "&observacionesp=" + $("#observacionesp").val()
            + "&datos=" + $("#datos").val()
            + "&tipo_entrega=" + id_chec
            + "&id_registro=" + idRegistro + "&id_vendedor=" + $("#id_vendedor").val();
}

function cargarDatosProforma(idproforma) {
    $.getJSON('retornar_proforma_venta.php?com=' + idproforma, function (data) {
        var tama = data.length;
        
        if (tama != 0) {
            idRegistro = data[data.length - 4];
            console.log(idRegistro);
            for (var i = 0; i < tama; i = i + 20) {
                $("#fecha_actual").val(data[i]);
                $("#hora_actual").val(data[i + 1]);
                $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                $("#id_cliente").val(data[i + 4]);
                $("#ruc_ci").val(data[i + 5]);
                $("#nombres_completos").val(data[i + 6]);
                $("#saldo").val(data[i + 7]);
                $("#tipo_precio").val(data[i + 8]);
                $("#total_p").val(data[i + 9]);
                $("#total_p2").val(data[i + 10]);
                $("#iva").val(data[i + 11]);
                $("#desc").val(data[i + 12]);
                $("#tot").val(data[i + 13]);
                $("#total_px").val(parseFloat(data[i + 9]).toFixed(2));
                $("#total_p2x").val(parseFloat(data[i + 10]).toFixed(2));
                $("#ivax").val(parseFloat(data[i + 11]).toFixed(2));
                $("#descx").val(parseFloat(data[i + 12]).toFixed(2));
                $("#totx").val(parseFloat(data[i + 13]).toFixed(2));
                $("#observacionesp").val(data[i + 14]);
                $("#datos").val(data[i + 15]);
                $("#id_vendedor").val(data[i + 17]);
                $("#ruc_ci_cli").val(data[i + 18]);
                $("#nombre_vendedor").val(data[i + 19]);
                
            }
        }
    });
}

function cargarProductosProforma(idproforma) {
    limpiarListaProductos();
    $.getJSON('retornar_proforma_venta2.php?com=' + idproforma, function (data) {
        var tama = data.length;
        var descuento = 0;
        var total = 0;
        var su = 0;
        var precio = 0;
        var multi = 0;
        var flotante = 0;
        var resultado = 0;

        if (tama != 0) {
            for (var i = 0; i < tama; i = i + 9) {
                desc = data[i + 5];
                precio = parseFloat(data[i + 4]);
                multi = parseFloat(data[i + 3]) * parseFloat(data[i + 4]);
                descuento = (multi * parseFloat(desc)) / 100;
                flotante = parseFloat(descuento);
                resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                total = multi - resultado;

                var datarow = {
                    cod_producto: data[i],
                    codigo: data[i + 1],
                    detalle: data[i + 2],
                    cantidad: data[i + 3],
                    precio_u: precio,
                    descuento: desc,
                    cal_des: resultado,
                    total: total,
                    precio_ux: precio.toFixed(2),
                    descuentox: parseFloat(desc).toFixed(2),
                    cal_desx: resultado.toFixed(2),
                    totalx: total.toFixed(2),
                    iva: data[i + 7],
                    incluye: data[i + 8]
                };
                var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
            }
        }
    });

}

function cargarProforma(idproforma) {
    $("#comprobante").val(idproforma);
    cargarProductosProforma(idproforma);
    cargarDatosProforma(idproforma);
}

//TODO cargar datos registro
function cargarRegistro(idregistro) {
    $.getJSON("retornar_registro.php?id=" + idregistro, function (data) {
        if (data.length == 0) {
            return;
        }

        idRegistro = data["id_registro"];
        var cedula = data["identificacion"];
        var nombres = data["nombres_cli"];
        var id_cliente = data["id_cliente"];
        var datos = "Numero Serie: " + data["nro_serie"];
        var observaciones = data["observacione"];
        var accesorios = data["accesorios"];

        datos = datos + "\nModelo: " + data["modelo"];
        datos = datos + "\nFecha Ingreso: " + data["fecha_ingreso"];

        $("#observaciones").val(observaciones);
        $("#accesorios").val(accesorios);

        $("#ruc_ci").val(cedula);
        $("#nombres_completos").val(nombres);
        $("#id_cliente").val(id_cliente);
        $("#datos").val(datos);
        console.log(data["estado"]);
        if (!!data["id_proforma"]) {
            $("#btnGuardar").attr("disabled", true);
            $("#btnModificar").attr("disabled", false);
            cargarProforma(data["id_proforma"]);
        } else {
            limpiarListaProductos();
            $("#btnGuardar").attr("disabled", false);
            $("#btnModificar").attr("disabled", true);
        }

        if (data["estado"] != "Activo") {
            $("#alert_eliminado").css({display: ""});
            $("#btnGuardar").attr("disabled", true);
            $("#btnModificar").attr("disabled", true);
        } else {
            $("#alert_eliminado").css({display: "none"});
            $("#btnGuardar").attr("disabled", false);
            $("#btnModificar").attr("disabled", false);
        }

    });
}

function limpiarListaProductos() {
    $("#list").jqGrid("clearGridData", true);
    $("#total_p").val("0.000");
    $("#total_p2").val("0.000");
    $("#desc").val("0.000");
    $("#iva").val("0.000");
    $("#tot").val("0.000");
    $("#total_px").val("0.000");
    $("#total_p2x").val("0.000");
    $("#descx").val("0.000");
    $("#ivax").val("0.000");
    $("#totx").val("0.000");
}

//////

/* function flecha_atras() {
 $.ajax({
 type: "POST",
 url: "../../procesos/flechas.php",
 data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "proforma" + "&id_tabla=" + "id_proforma" + "&tipo=" + 1,
 success: function (data) {
 var val = data;
 if (val != "") {
 $("#comprobante").val(val);
 var valor = $("#comprobante").val();
 
 /////////llamar proforma flechas primera parte/////
 $("#btnGuardar").attr("disabled", true);
 
 $("#list").jqGrid("clearGridData", true);
 $("#total_p").val("0.000");
 $("#total_p2").val("0.000");
 $("#desc").val("0.000");
 $("#iva").val("0.000");
 $("#tot").val("0.000");
 $("#total_px").val("0.000");
 $("#total_p2x").val("0.000");
 $("#desxc").val("0.000");
 $("#ivax").val("0.000");
 $("#totx").val("0.000");
 
 $.getJSON('retornar_proforma_venta.php?com=' + valor, function (data) {
 
 var tama = data.length;
 if (tama != 0) {
 for (var i = 0; i < tama; i = i + 16) {
 $("#fecha_actual").val(data[i]);
 $("#hora_actual").val(data[i + 1]);
 $("#digitador").val(data[i + 2] + " " + data[i + 3]);
 $("#id_cliente").val(data[i + 4]);
 $("#ruc_ci").val(data[i + 5]);
 $("#nombres_completos").val(data[i + 6]);
 $("#saldo").val(data[i + 7]);
 $("#tipo_precio").val(data[i + 8]);
 $("#total_p").val(data[i + 9]);
 $("#total_p2").val(data[i + 10]);
 $("#iva").val(data[i + 11]);
 $("#desc").val(data[i + 12]);
 $("#tot").val(data[i + 13]);
 $("#total_px").val(parseFloat(data[i + 9]).toFixed(2));
 $("#total_p2x").val(parseFloat(data[i + 10]).toFixed(2));
 $("#ivax").val(parseFloat(data[i + 11]).toFixed(2));
 $("#descx").val(parseFloat(data[i + 12]).toFixed(2));
 $("#totx").val(parseFloat(data[i + 13]).toFixed(2));
 $("#observaciones").val(parseFloat(data[i + 14]).toFixed(2));
 $("#datos").val(data[i + 15]);
 }
 }
 });
 
 $.getJSON('retornar_proforma_venta2.php?com=' + valor, function (data) {
 var tama = data.length;
 var descuento = 0;
 var total = 0;
 var su = 0;
 var precio = 0;
 var multi = 0;
 var flotante = 0;
 var resultado = 0;
 
 if (tama != 0) {
 for (var i = 0; i < tama; i = i + 9) {
 desc = data[i + 5];
 precio = parseFloat(data[i + 4]);
 multi = parseFloat(data[i + 3]) * parseFloat(data[i + 4]);
 descuento = (multi * parseFloat(desc)) / 100;
 flotante = parseFloat(descuento);
 resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
 total = multi - resultado;
 
 var datarow = {
 cod_producto: data[i],
 codigo: data[i + 1],
 detalle: data[i + 2],
 cantidad: data[i + 3],
 precio_u: precio,
 descuento: desc,
 cal_des: resultado,
 total: total,
 precio_ux: precio.toFixed(2),
 descuentox: parseFloat(desc).toFixed(2),
 cal_desx: resultado.toFixed(2),
 totalx: total.toFixed(2),
 iva: data[i + 7],
 incluye: data[i + 8]
 };
 var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
 }
 }
 });
 } else {
 alertify.alert("No hay mas registros posteriores!!");
 }
 }
 });
 }
 */
/* function flecha_siguiente() {
 $.ajax({
 type: "POST",
 url: "../../procesos/flechas.php",
 data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "proforma" + "&id_tabla=" + "id_proforma" + "&tipo=" + 2,
 success: function (data) {
 var val = data;
 if (val != "") {
 $("#comprobante").val(val);
 var valor = $("#comprobante").val();
 ////////////llamar facturas flechas primera parte/////
 $("#btnGuardar").attr("disabled", true);
 
 $("#list").jqGrid("clearGridData", true);
 $("#total_p").val("0.000");
 $("#total_p2").val("0.000");
 $("#desc").val("0.000");
 $("#iva").val("0.000");
 $("#tot").val("0.000");
 $("#total_px").val("0.000");
 $("#total_p2x").val("0.000");
 $("#descx").val("0.000");
 $("#ivax").val("0.000");
 $("#totx").val("0.000");
 
 $.getJSON('retornar_proforma_venta.php?com=' + valor, function (data) {
 var tama = data.length;
 if (tama != 0) {
 for (var i = 0; i < tama; i = i + 16) {
 $("#fecha_actual").val(data[i]);
 $("#hora_actual").val(data[i + 1]);
 $("#digitador").val(data[i + 2] + " " + data[i + 3]);
 $("#id_cliente").val(data[i + 4]);
 $("#ruc_ci").val(data[i + 5]);
 $("#nombres_completos").val(data[i + 6]);
 $("#saldo").val(data[i + 7]);
 $("#tipo_precio").val(data[i + 8]);
 $("#total_p").val(data[i + 9]);
 $("#total_p2").val(data[i + 10]);
 $("#iva").val(data[i + 11]);
 $("#desc").val(data[i + 12]);
 $("#tot").val(data[i + 13]);
 $("#total_px").val(parseFloat(data[i + 9]).toFixed(2));
 $("#total_p2x").val(parseFloat(data[i + 10]).toFixed(2));
 $("#ivax").val(parseFloat(data[i + 11]).toFixed(2));
 $("#descx").val(parseFloat(data[i + 12]).toFixed(2));
 $("#totx").val(parseFloat(data[i + 13]).toFixed(2));
 $("#observaciones").val(data[i + 14]);
 $("#datos").val(data[i + 15]);
 }
 }
 });
 
 $.getJSON('retornar_proforma_venta2.php?com=' + valor, function (data) {
 var tama = data.length;
 var descuento = 0;
 var total = 0;
 var su = 0;
 var precio = 0;
 var multi = 0;
 var flotante = 0;
 var resultado = 0;
 
 if (tama != 0) {
 for (var i = 0; i < tama; i = i + 9) {
 desc = data[i + 5];
 precio = parseFloat(data[i + 4]);
 multi = parseFloat(data[i + 3]) * parseFloat(data[i + 4]);
 descuento = (multi * parseFloat(desc)) / 100;
 flotante = parseFloat(descuento);
 resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
 total = multi - resultado;
 
 var datarow = {
 cod_producto: data[i],
 codigo: data[i + 1],
 detalle: data[i + 2],
 cantidad: data[i + 3],
 precio_u: precio,
 descuento: desc,
 cal_des: resultado,
 total: total,
 precio_ux: precio.toFixed(2),
 descuentox: parseFloat(desc).toFixed(2),
 cal_desx: resultado.toFixed(2),
 totalx: total.toFixed(2),
 iva: data[i + 7],
 incluye: data[i + 8]
 };
 var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
 }
 }
 });
 } else {
 alertify.alert("No hay mas registros superiores!!");
 }
 }
 });
 }
 */
function limpiar_proforma() {
    location.reload();
}

function limpiar_campo() {
    if ($("#ruc_ci").val() == "") {
        $("#id_cliente").val("");
        $("#nombres_completos").val("");
        $("#saldo").val("");
    }
}

function limpiar_campo2() {
    if ($("#nombres_completos").val() == "") {
        $("#id_cliente").val("");
        $("#ruc_ci").val("");
        $("#saldo").val("");
    }
}

function limpiar_campo3() {
    if ($("#codigo").val() == "") {
        $("#codigo_barras").val("");
        $("#cod_producto").val("");
        $("#producto").val("");
        $("#cantidad").val("");
        $("#p_venta").val("");
        $("#descuento").val("");
        $("#iva_producto").val("");
        $("#venta_iva").val("0.00");
        $("#disponibles").val("");
        $("#des").val("");
        $("#incluye").val("");

    }
}

function limpiar_campo4() {
    if ($("#producto").val() == "") {
        $("#codigo_barras").val("");
        $("#cod_producto").val("");
        $("#codigo").val("");
        $("#cantidad").val("");
        $("#p_venta").val("");
        $("#venta_iva").val("0.00");
        $("#descuento").val("");
        $("#iva_producto").val("");
        $("#disponibles").val("");
        $("#des").val("");
        $("#incluye").val("");
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

function inicio() {

    $("#cantidad").keyup(function () {
        if ($("#iva_producto").val() == "Si") {
            $("#venta_iva").val("");
            var iva1 = ($("#p_venta").val() * calculoIVA) / 100;
            var iva_pventa = iva1 + parseFloat($("#p_venta").val());
            var total_iva_cantidad =
                    numFormatter(2).format(iva_pventa) * $("#cantidad").val();
            $("#venta_iva").val(numFormatter(2).format(total_iva_cantidad));
        } else {
            $("#venta_iva").val("0.00");
        }


    });
    $("#p_venta").keyup(function () {

        if ($("#iva_producto").val() == "Si") {
            $("#venta_iva").val("");
            var iva1 = ($("#p_venta").val() * calculoIVA) / 100;
            var iva_pventa = iva1 + parseFloat($("#p_venta").val());
            var total_iva_cantidad =
                    numFormatter(2).format(iva_pventa) * $("#cantidad").val();
            $("#venta_iva").val(numFormatter(2).format(total_iva_cantidad));
        } else {
            $("#venta_iva").val("0.00");
        }

    });
    $("#ruc_ci_cli").autocomplete({
        source: "buscar_vendedor.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#ruc_ci_cli").val(ui.item.value);
            $("#id_vendedor").val(ui.item.id_vendedor);
            $("#nombre_vendedor").val(ui.item.nombre_vendedor);

            return false;
        },
        select: function (event, ui) {
            $("#ruc_ci_cli").val(ui.item.value);
            $("#id_vendedor").val(ui.item.id_vendedor);
            $("#nombre_vendedor").val(ui.item.nombre_vendedor);
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
    };
    // fin

    // buscar clientes nombres

    $("#nombre_vendedor").autocomplete({
        source: "buscar_vendedor_nombre.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#nombre_vendedor").val(ui.item.value);
            $("#id_vendedor").val(ui.item.id_vendedor);
            $("#ruc_ci_cli").val(ui.item.ruc_ci_cli);

            return false;
        },
        select: function (event, ui) {
            $("#nombre_vendedor").val(ui.item.value);
            $("#id_vendedor").val(ui.item.id_vendedor);
            $("#ruc_ci_cli").val(ui.item.ruc_ci_cli);

            return false;
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
    };
    // fin
    $("#tipo_precio").val("MINORISTA")


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

    alertify.set({delay: 1000});

    $(".timepicker").timepicker({
        showInputs: false
    });

    // para hora
    show();
    // Fin

    // ambiar idioma
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

    function combo(tipo) {
        $.ajax({
            type: "POST",
            url: "buscar_codigo.php?tipo_precio=" + tipo,
            success: function (resp) {
                combo_1 = JSON.parse(resp);
            }
        });
        return combo_1;
    }

    function combo1(tipo) {
        $.ajax({
            type: "POST",
            url: "buscar_producto.php?tipo_precio=" + tipo,
            success: function (resp) {
                combo_2 = JSON.parse(resp);
            }
        });
        return combo_2;
    }

    // botones
    $("#btnGuardar").click(function (e) {
        e.preventDefault();
    });
    $("#btnModificar").click(function (e) {
        e.preventDefault();
        modificar_proforma();
    });

    $("#btnNuevo").click(function (e) {
        e.preventDefault();
    });
    $("#btnImprimir").click(function (e) {
        if (Number.isNaN(parseFloat($("#comprobante").val()))) {
            alertify.alert("No se puede imprimir la proforma, está vacía o no ha seleccionado un registro.");
            $("#alertify-ok").css("background-color", "red");
            return;
        }
        window.open("../../reportes/proforma_tecnico.php?id=" + $("#comprobante").val(), '_blank');
    });
    /* $("#btnAtras").click(function (e) {
     e.preventDefault();
     }); */
    $("#btnAdelante").click(function (e) {
        e.preventDefault();
    });

    $("#btnGuardar").on("click", guardar_proforma);
    // $(document).bind('keydown', 'F7', guardar_proforma);
    // $('input').unbind('keydown', 'F7', guardar_proforma);
    $("#btnNuevo").on("click", limpiar_proforma);
    /* $("#btnAtras").on("click", flecha_atras); */
    /* $("#btnAdelante").on("click", flecha_siguiente); */

    ////////////////////////////////
    $("#buscar_proformas").dialog(dialogo2);
    $("#buscar_ingresos").dialog(dialogo3);

    $("#btnBuscar").click(function (e) {
        e.preventDefault();
        $("#buscar_proformas").dialog("open");
    });
    $("#btnBuscarIngreso").click(function (e) {
        e.preventDefault();
//         $('#list3').trigger('reloadGrid');
        $("#buscar_ingresos").dialog("open");
    });
    ///////////////////////////////////
// $("#btnBuscarIngreso").on("click", cargar_list);
    $("#cantidad").validCampoFranz("0123456789");
    $("#descuento").validCampoFranz("0123456789");
    $("#descuento").attr("maxlength", "3");

    // validaciones
    $("#ruc_ci").on("keyup", limpiar_campo);
    $("#nombres_completos").on("keyup", limpiar_campo2);
    $("#codigo").on("keyup", limpiar_campo3);
    $("#producto").on("keyup", limpiar_campo4);
    $("#codigo").on("keypress", enter);
    $("#producto").on("keypress", enter);
    $("#cantidad").on("keypress", enter);
    $("#p_venta").on("keypress", enter1);
    $("#descuento").on("keypress", enter2);
    $("#ruc_ci").on("keypress", enter3);
    $("#nombres_completos").on("keypress", enter3);
    // fin

    $("#p_venta").on("keypress", punto);

    // buscar productos codigo barras
    $("#codigo_barras").change(function (e) {
        var precio = $("#tipo_precio").val();
        var codigo = $("#codigo_barras").val();

        if (precio == "MINORISTA") {
            $.getJSON('search.php?codigo_barras=' + codigo + '&precio=' + precio, function (data) {
                var tama = data.length;
                if (tama != 0) {
                    for (var i = 0; i < tama; i = i + 10) {
                        $("#codigo").val(data[i]);
                        $("#producto").val(data[i + 1]);
                        $("#p_venta").val(data[i + 2]);
                        $("#descuento").attr("max", data[i + 7]);
                        $("#disponibles").val(data[i + 3]);
                        $("#iva_producto").val(data[i + 4]);
                        $("#carga_series").val(data[i + 5]);
                        $("#cod_producto").val(data[i + 6]);
                        $("#des").val(data[i + 7]);
                        $("#inventar").val(data[i + 8]);
                        $("#incluye").val(data[i + 9]);
                        $("#cantidad").focus();
                        if ($("#iva_producto").val() == "Si") {
                            $("#venta_iva").val("");
                            var iva1 = ($("#p_venta").val() * calculoIVA) / 100;
                            var iva_pventa = iva1 + parseFloat($("#p_venta").val());
                            $("#venta_iva").val(numFormatter(2).format(iva_pventa));
                        } else {
                            $("#venta_iva").val("0.00");
                        }
                    }
                } else {
                    $("#codigo").val("");
                    $("#producto").val("");
                    $("#p_venta").val("");
                    $("#descuento").val("");
                    $("#disponibles").val("");
                    $("#iva_producto").val("");
                    $("#carga_series").val("");
                    $("#cod_producto").val("");
                    $("#des").val("");
                    $("#inventar").val("");
                    $("#incluye").val("");
                    alertify.error("Producto no ingresado");
                    $("#codigo_barras").val("");
                }
            });
        } else {
            if (precio == "MAYORISTA") {
                $.getJSON('search.php?codigo_barras=' + codigo + '&precio=' + precio, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 10) {
                            $("#codigo").val(data[i]);
                            $("#producto").val(data[i + 1]);
                            $("#p_venta").val(data[i + 2]);
                            $("#descuento").attr("max", data[i + 7]);
                            $("#disponibles").val(data[i + 3]);
                            $("#iva_producto").val(data[i + 4]);
                            $("#carga_series").val(data[i + 5]);
                            $("#cod_producto").val(data[i + 6]);
                            $("#des").val(data[i + 7]);
                            $("#inventar").val(data[i + 8]);
                            $("#incluye").val(data[i + 9]);
                            $("#cantidad").focus();
                            if ($("#iva_producto").val() == "Si") {
                                $("#venta_iva").val("");
                                var iva1 = ($("#p_venta").val() * calculoIVA) / 100;
                                var iva_pventa = iva1 + parseFloat($("#p_venta").val());
                                $("#venta_iva").val(numFormatter(2).format(iva_pventa));
                            } else {
                                $("#venta_iva").val("0.00");
                            }
                        }
                    } else {
                        $("#codigo").val("");
                        $("#producto").val("");
                        $("#p_venta").val("");
                        $("#descuento").val("");
                        $("#disponibles").val("");
                        $("#iva_producto").val("");
                        $("#carga_series").val("");
                        $("#cod_producto").val("");
                        $("#des").val("");
                        $("#inventar").val("");
                        $("#incluye").val("");
                        alertify.error("Producto no ingresado");
                        $("#codigo_barras").val("");
                    }
                });
            }
        }
    });
    // Fin

    // buscar productos codigo
    $("#codigo").keyup(function (e) {
        var precio = $("#tipo_precio").val();
        var res = combo(precio);

        if (precio == "MINORISTA") {
            $("#codigo").autocomplete({
                source: function (req, response) {
                    var results = $.ui.autocomplete.filter(res, req.term);
                    response(results.slice(0, 20));
                },
                minLength: 1,
                focus: function (event, ui) {
                    $("#codigo_barras").val(ui.item.codigo_barras);
                    $("#codigo").val(ui.item.value);
                    $("#producto").val(ui.item.producto);
                    $("#p_venta").val(ui.item.p_venta);
                    $("#descuento").attr("max", ui.item.descuento);
                    $("#disponibles").val(ui.item.disponibles);
                    $("#des").val(ui.item.des);
                    $("#iva_producto").val(ui.item.iva_producto);
                    $("#cod_producto").val(ui.item.cod_producto);
                    $("#incluye").val(ui.item.incluye);
                    $("#inventar").val(ui.item.inventar);
                    return false;
                },
                select: function (event, ui) {
                    $("#codigo_barras").val(ui.item.codigo_barras);
                    $("#codigo").val(ui.item.value);
                    $("#producto").val(ui.item.producto);
                    $("#p_venta").val(ui.item.p_venta);
                    $("#descuento").attr("max", ui.item.descuento);
                    $("#disponibles").val(ui.item.disponibles);
                    $("#des").val(ui.item.des);
                    $("#iva_producto").val(ui.item.iva_producto);
                    $("#cod_producto").val(ui.item.cod_producto);
                    $("#incluye").val(ui.item.incluye);
                    return false;
                }

            }).data("ui-autocomplete")._renderItem = function (ul, item) {
                return $("<li>")
                        .append("<a>" + item.value + "</a>")
                        .appendTo(ul);
            };
        } else {
            if (precio == "MAYORISTA") {
                $("#codigo").autocomplete({
                    source: function (req, response) {
                        var results = $.ui.autocomplete.filter(res, req.term);
                        response(results.slice(0, 20));
                    },
                    minLength: 1,
                    focus: function (event, ui) {
                        $("#codigo_barras").val(ui.item.codigo_barras);
                        $("#codigo").val(ui.item.value);
                        $("#producto").val(ui.item.producto);
                        $("#p_venta").val(ui.item.p_venta);
                        $("#descuento").attr("max", ui.item.descuento);
                        $("#disponibles").val(ui.item.disponibles);
                        $("#des").val(ui.item.des);
                        $("#iva_producto").val(ui.item.iva_producto);
                        $("#cod_producto").val(ui.item.cod_producto);
                        $("#incluye").val(ui.item.incluye);
                        return false;
                    },
                    select: function (event, ui) {
                        $("#codigo_barras").val(ui.item.codigo_barras);
                        $("#codigo").val(ui.item.value);
                        $("#producto").val(ui.item.producto);
                        $("#p_venta").val(ui.item.p_venta);
                        $("#descuento").attr("max", ui.item.descuento);
                        $("#disponibles").val(ui.item.disponibles);
                        $("#des").val(ui.item.des);
                        $("#iva_producto").val(ui.item.iva_producto);
                        $("#cod_producto").val(ui.item.cod_producto);
                        $("#incluye").val(ui.item.incluye);
                        return false;
                    }

                }).data("ui-autocomplete")._renderItem = function (ul, item) {
                    return $("<li>")
                            .append("<a>" + item.value + "</a>")
                            .appendTo(ul);
                };
            }
        }
    });
    // fin

    // busqueda productos articulos
    $("#producto").keyup(function (e) {
        var precio = $("#tipo_precio").val();
        var res = combo1(precio);

        if (precio == "MINORISTA") {
            $("#producto").autocomplete({
                source: function (req, response) {
                    var results = $.ui.autocomplete.filter(res, req.term);
                    response(results.slice(0, 20));
                },
                minLength: 1,
                focus: function (event, ui) {
                    $("#codigo_barras").val(ui.item.codigo_barras);
                    $("#producto").val(ui.item.value);
                    $("#codigo").val(ui.item.codigo);
                    $("#p_venta").val(ui.item.p_venta);
                    $("#descuento").attr("max", ui.item.descuento);
                    $("#disponibles").val(ui.item.disponibles);
                    $("#des").val(ui.item.des);
                    $("#iva_producto").val(ui.item.iva_producto);
                    $("#cod_producto").val(ui.item.cod_producto);
                    $("#incluye").val(ui.item.incluye);
                    $("#inventar").val(ui.item.inventar);
                    if ($("#iva_producto").val() == "Si") {
                        $("#venta_iva").val("");
                        var iva1 = ($("#p_venta").val() * calculoIVA) / 100;
                        var iva_pventa = iva1 + parseFloat($("#p_venta").val());
                        $("#venta_iva").val(numFormatter(2).format(iva_pventa));
                    } else {
                        $("#venta_iva").val("0.00");
                    }
                    return false;
                },
                select: function (event, ui) {
                    $("#codigo_barras").val(ui.item.codigo_barras);
                    $("#producto").val(ui.item.value);
                    $("#codigo").val(ui.item.codigo);
                    $("#p_venta").val(ui.item.p_venta);
                    $("#descuento").attr("max", ui.item.descuento);
                    $("#disponibles").val(ui.item.disponibles);
                    $("#des").val(ui.item.des);
                    $("#iva_producto").val(ui.item.iva_producto);
                    $("#cod_producto").val(ui.item.cod_producto);
                    $("#incluye").val(ui.item.incluye);
                    $("#inventar").val(ui.item.inventar);
                    if ($("#iva_producto").val() == "Si") {
                        $("#venta_iva").val("");
                        var iva1 = ($("#p_venta").val() * calculoIVA) / 100;
                        var iva_pventa = iva1 + parseFloat($("#p_venta").val());
                        $("#venta_iva").val(numFormatter(2).format(iva_pventa));
                    } else {
                        $("#venta_iva").val("0.00");
                    }
                    return false;
                }

            }).data("ui-autocomplete")._renderItem = function (ul, item) {
                return $("<li>")
                        .append("<a>" + item.value + "</a>")
                        .appendTo(ul);
            };
        } else {
            if (precio == "MAYORISTA") {
                $("#producto").autocomplete({
                    source: function (req, response) {
                        var results = $.ui.autocomplete.filter(res, req.term);
                        response(results.slice(0, 20));
                    },
                    minLength: 1,
                    focus: function (event, ui) {
                        $("#codigo_barras").val(ui.item.codigo_barras);
                        $("#producto").val(ui.item.value);
                        $("#codigo").val(ui.item.codigo);
                        $("#p_venta").val(ui.item.p_venta);
                        $("#descuento").attr("max", ui.item.descuento);
                        $("#disponibles").val(ui.item.disponibles);
                        $("#des").val(ui.item.des);
                        $("#iva_producto").val(ui.item.iva_producto);
                        $("#cod_producto").val(ui.item.cod_producto);
                        $("#incluye").val(ui.item.incluye);
                        $("#inventar").val(ui.item.inventar);
                        if ($("#iva_producto").val() == "Si") {
                            $("#venta_iva").val("");
                            var iva1 = ($("#p_venta").val() * calculoIVA) / 100;
                            var iva_pventa = iva1 + parseFloat($("#p_venta").val());
                            $("#venta_iva").val(numFormatter(2).format(iva_pventa));
                        } else {
                            $("#venta_iva").val("0.00");
                        }
                        return false;
                    },
                    select: function (event, ui) {
                        $("#codigo_barras").val(ui.item.codigo_barras);
                        $("#producto").val(ui.item.value);
                        $("#codigo").val(ui.item.codigo);
                        $("#p_venta").val(ui.item.p_venta);
                        $("#descuento").attr("max", ui.item.descuento);
                        $("#disponibles").val(ui.item.disponibles);
                        $("#des").val(ui.item.des);
                        $("#iva_producto").val(ui.item.iva_producto);
                        $("#cod_producto").val(ui.item.cod_producto);
                        $("#incluye").val(ui.item.incluye);
                        $("#inventar").val(ui.item.inventar);
                        if ($("#iva_producto").val() == "Si") {
                            $("#venta_iva").val("");
                            var iva1 = ($("#p_venta").val() * calculoIVA) / 100;
                            var iva_pventa = iva1 + parseFloat($("#p_venta").val());
                            $("#venta_iva").val(numFormatter(2).format(iva_pventa));
                        } else {
                            $("#venta_iva").val("0.00");
                        }
                        return false;
                    }

                }).data("ui-autocomplete")._renderItem = function (ul, item) {
                    return $("<li>")
                            .append("<a>" + item.value + "</a>")
                            .appendTo(ul);
                };
            }
        }
    });
    // fin

    // accion limpiar/
    $("#tipo_precio").change(function () {
        $("#codigo_barras").val("");
        $("#cod_producto").val("");
        $("#codigo").val("");
        $("#producto").val("");
        $("#cantidad").val("");
        $("#p_venta").val("");
        $("#descuento").val("");
        $("#disponibles").val("");
        $("#inventar").val("");
        $("#des").val("");
        $("#iva_producto").val("");
        $("#carga_series").val("");
        $("#incluye").val("");
    });
    // fin

    // buscar clientes identificacion
    $("#ruc_ci").autocomplete({
        source: "buscar_cliente2.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#ruc_ci").val(ui.item.value);
            $("#nombres_completos").val(ui.item.nombres_completos);
            $("#id_cliente").val(ui.item.id_cliente);
            $("#saldo").val(ui.item.saldo);
            return false;
        },
        select: function (event, ui) {
            $("#ruc_ci").val(ui.item.value);
            $("#nombres_completos").val(ui.item.nombres_completos);
            $("#id_cliente").val(ui.item.id_cliente);
            $("#saldo").val(ui.item.saldo);
            return false;
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
    };
    // fin

    // buscador clientes nombres
    $("#nombres_completos").autocomplete({
        source: "buscar_cliente_pagos.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#nombres_completos").val(ui.item.value);
            $("#ruc_ci").val(ui.item.ruc_ci);
            $("#id_cliente").val(ui.item.id_cliente);
            $("#saldo").val(ui.item.saldo);
            return false;
        },
        select: function (event, ui) {
            $("#nombres_completos").val(ui.item.value);
            $("#ruc_ci").val(ui.item.ruc_ci);
            $("#id_cliente").val(ui.item.id_cliente);
            $("#saldo").val(ui.item.saldo);
            return false;
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
    };
    // fin

    // calendarios
    $('#fecha_actual').datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');

    // tabla local
    var can;
    jQuery("#list").jqGrid({
        datatype: "local",
        colNames: ['', 'ID', 'Código', 'Producto', 'Cantidad', 'PVPx', 'Descuentox', 'Calculadox', 'Totalx', 'PVP', 'Descuento', 'Calculado', 'Total', 'Iva', "Total con IVA", 'Incluye'],
        colModel: [
            {name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: {keys: false, delbutton: true, editbutton: false}},
            {name: 'cod_producto', index: 'cod_producto', editable: false, search: false, hidden: true, editrules: {edithidden: false}, align: 'center', frozen: true, width: 50},
            {name: 'codigo', index: 'codigo', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 100},
            {name: 'detalle', index: 'detalle', editable: false, frozen: true, editrules: {required: true}, align: 'center', width: 290},
            {name: 'cantidad', index: 'cantidad', editable: false, frozen: false, editrules: {required: true}, align: 'center', width: 70, editoptions: {maxlength: 10, size: 15, dataInit: function (elem) {
                        $(elem).bind("keypress", function (e) {
                            return numeros(e)
                        })
                    }}},
            {name: 'precio_u', index: 'precio_u', hidden: true, editable: false, search: false, frozen: true, editrules: {required: true}, align: 'center', width: 110, editoptions: {maxlength: 10, size: 15, dataInit: function (elem) {
                        $(elem).bind("keypress", function (e) {
                            return punto(e)
                        })
                    }}},
            {name: 'descuento', index: 'descuento', hidden: true, editable: false, frozen: true, editrules: {required: true}, align: 'center', width: 70},
            {name: 'cal_des', index: 'cal_des', hidden: true, editable: false, hidden: true, frozen: true, editrules: {required: true}, align: 'center', width: 90},
            {name: 'total', index: 'total', hidden: true, editable: false, search: false, frozen: true, editrules: {required: true}, align: 'center', width: 110},
            {name: 'precio_ux', index: 'precio_ux', editable: false, search: false, frozen: true, editrules: {required: true}, align: 'center', width: 110, editoptions: {maxlength: 10, size: 15, dataInit: function (elem) {
                        $(elem).bind("keypress", function (e) {
                            return punto(e)
                        })
                    }}},
            {name: 'descuentox', index: 'descuentox', editable: false, frozen: true, editrules: {required: true}, align: 'center', width: 70},
            {name: 'cal_desx', index: 'cal_desx', editable: false, hidden: true, frozen: true, editrules: {required: true}, align: 'center', width: 90},
            {name: 'totalx', index: 'totalx', editable: false, search: false, frozen: true, editrules: {required: true}, align: 'center', width: 110},
            {name: 'iva', index: 'iva', align: 'center', width: 100, hidden: true},
            {name: 'pendiente', index: 'pendiente', editable: false, hidden: false, frozen: true, editrules: {required: true}, align: 'center', width: 90},
            {name: 'incluye', index: 'incluye', editable: false, hidden: true, frozen: true, editrules: {required: true}, align: 'center', width: 90}
        ],
        rowNum: 30,
        width: 810,
        height: 300,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager'),
        sortname: 'id_productos',
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
                var subtotal0 = 0;
                var subtotal12 = 0;
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
                        if (ret.incluye == "No") {
                            subtotal = ret.total;
                            sub1 = subtotal;
                            //iva1 = (sub1 * 0.12).toFixed(3);
                            iva1 = sub1 * (calculoIVA / 100);

                            subtotal0 = parseFloat($("#total_p").val()) + 0;
                            subtotal12 = parseFloat($("#total_p2").val()) - parseFloat(sub1);
                            iva12 = parseFloat($("#iva").val()) - parseFloat(iva1);
                            descu_total = parseFloat($("#desc").val()) - parseFloat(ret.cal_des);

                            subtotal0 = parseFloat(subtotal0);
                            subtotal12 = parseFloat(subtotal12);
                            iva12 = parseFloat(iva12);
                            descu_total = parseFloat(descu_total);
                        } else {
                            if (ret.incluye == "Si") {
                                subtotal = ret.total;
                                //sub2 = (subtotal / 1.12).toFixed(3);
                                //iva2 = (sub2 * 0.12).toFixed(3);
                                sub2 = subtotal / ((calculoIVA / 100) + 1);
                                iva2 = sub2 * (calculoIVA / 100);

                                subtotal0 = parseFloat($("#total_p").val()) + 0;
                                subtotal12 = parseFloat($("#total_p2").val()) - parseFloat(sub2);
                                iva12 = parseFloat($("#iva").val()) - parseFloat(iva2);
                                descu_total = parseFloat($("#desc").val()) - parseFloat(ret.cal_des);

                                subtotal0 = parseFloat(subtotal0);
                                subtotal12 = parseFloat(subtotal12);
                                iva12 = parseFloat(iva12);
                                descu_total = parseFloat(descu_total);
                            }
                        }
                    } else {
                        if (ret.iva == "No") {
                            subtotal = ret.total;
                            sub = subtotal;

                            subtotal0 = parseFloat($("#total_p").val()) - parseFloat(sub);
                            subtotal12 = parseFloat($("#total_p2").val()) + 0;
                            iva12 = parseFloat($("#iva").val()) + 0;
                            descu_total = parseFloat($("#desc").val()) - parseFloat(ret.cal_des);

                            subtotal0 = parseFloat(subtotal0);
                            subtotal12 = parseFloat(subtotal12);
                            iva12 = parseFloat(iva12);
                            descu_total = parseFloat(descu_total);
                        }
                    }
                }

                total_total = parseFloat(total_total) + (parseFloat(subtotal0) + parseFloat(subtotal12) + parseFloat(iva12));
                total_total = parseFloat(total_total);

                $("#total_p").val(subtotal0);
                $("#total_p2").val(subtotal12);
                $("#iva").val(iva12);
                $("#desc").val(descu_total);
                $("#tot").val(total_total);
                $("#total_px").val(subtotal0.toFixed(2));
                $("#total_p2x").val(subtotal12.toFixed(2));
                $("#ivax").val(iva12.toFixed(2));
                $("#descx").val(descu_total.toFixed(2));
                $("#totx").val(total_total.toFixed(2));

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
            var subtotal0 = 0;
            var subtotal12 = 0;
            var iva12 = 0;
            var total_total = 0;
            var descu_total = 0;

            var id = jQuery("#list").jqGrid('getGridParam', 'selrow');
            jQuery('#list').jqGrid('restoreRow', id);
            var ret = jQuery("#list").jqGrid('getRowData', id);

            if (name == 'cantidad') {
                var precio_grid = jQuery("#list").jqGrid('getCell', rowid, iCol + 1);
                var descuento_grid = jQuery("#list").jqGrid('getCell', rowid, iCol + 2);
                var precio = 0;
                var descuento = 0;
                var multi = 0;
                var total = 0;
                var desc = 0;
                var flotante = 0;
                var resultado = 0;

                if (descuento_grid != '0') {
                    desc = descuento_grid;
                    precio = parseFloat(precio_grid);
                    multi = parseFloat(val) * parseFloat(precio);
                    descuento = ((multi * parseFloat(desc)) / 100);
                    flotante = parseFloat(descuento);
                    resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                    total = multi - resultado;
                    jQuery("#list").jqGrid('setRowData', rowid, {total: total, cal_des: resultado});
                } else {
                    desc = descuento_grid;
                    precio = parseFloat(precio_grid);
                    multi = parseFloat(val) * parseFloat(precio);
                    descuento = ((multi * parseFloat(desc)) / 100);
                    flotante = parseFloat(descuento);
                    resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                    total = parseFloat(multi);
                    jQuery("#list").jqGrid('setRowData', rowid, {total: total});
                }

                // proceso incluye iva
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
                        if (dd['incluye'] == "No") {
                            subtotal = dd['total'];
                            sub1 = subtotal;
                            //iva1 = (sub1 * 0.12).toFixed(3);
                            iva1 = sub1 * (calculoIVA / 100);

                            subtotal0 = parseFloat(subtotal0) + 0;
                            subtotal12 = parseFloat(subtotal12) + parseFloat(sub1);
                            descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);
                            iva12 = parseFloat(iva12) + parseFloat(iva1);

                            subtotal0 = parseFloat(subtotal0);
                            subtotal12 = parseFloat(subtotal12);
                            iva12 = parseFloat(iva12);
                            descu_total = parseFloat(descu_total);
                        } else {
                            if (dd['incluye'] == "Si") {
                                subtotal = dd['total'];
                                //sub2 = (subtotal / 1.12).toFixed(3);
                                //iva2 = (sub2 * 0.12).toFixed(3);
                                sub2 = subtotal / ((calculoIVA / 100) + 1);
                                iva2 = sub2 * (calculoIVA / 100);

                                subtotal0 = parseFloat(subtotal0) + 0;
                                subtotal12 = parseFloat(subtotal12) + parseFloat(sub2);
                                iva12 = parseFloat(iva12) + parseFloat(iva2);
                                descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);

                                subtotal0 = parseFloat(subtotal0);
                                subtotal12 = parseFloat(subtotal12);
                                iva12 = parseFloat(iva12);
                                descu_total = parseFloat(descu_total);
                            }
                        }
                    } else {
                        if (dd['iva'] == "No") {
                            subtotal = dd['total'];
                            sub = subtotal;

                            subtotal0 = parseFloat(subtotal0) + parseFloat(sub);
                            subtotal12 = parseFloat(subtotal12) + 0;
                            iva12 = parseFloat(iva12) + 0;
                            descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);

                            subtotal0 = parseFloat(subtotal0);
                            subtotal12 = parseFloat(subtotal12);
                            iva12 = parseFloat(iva12);
                            descu_total = parseFloat(descu_total);
                        }
                    }
                }

                total_total = parseFloat(total_total) + (parseFloat(subtotal0) + parseFloat(subtotal12) + parseFloat(iva12));
                total_total = parseFloat(total_total);

                $("#total_p").val(subtotal0);
                $("#total_p2").val(subtotal12);
                $("#iva").val(iva12);
                $("#desc").val(descu_total);
                $("#tot").val(total_total);
                $("#total_px").val(subtotal0.toFixed(2));
                $("#total_p2x").val(subtotal12.toFixed(2));
                $("#ivax").val(iva12.toFixed(2));
                $("#descx").val(descu_total.toFixed(2));
                $("#totx").val(total_total.toFixed(2));
                $("#codigo_barras").focus();
            }

            if (name == 'precio_u') {
                var cantidad_grid = jQuery("#list").jqGrid('getCell', rowid, iCol - 1);
                var descuento_grid = jQuery("#list").jqGrid('getCell', rowid, iCol + 1);
                var precio = 0;
                var descuento = 0;
                var multi = 0;
                var total = 0;
                var desc = 0;
                var flotante = 0;
                var resultado = 0;

                if (descuento_grid != '0') {
                    desc = descuento_grid;
                    precio = parseFloat(val);
                    multi = parseFloat(cantidad_grid) * parseFloat(precio);
                    descuento = ((multi * parseFloat(desc)) / 100);
                    flotante = parseFloat(descuento);
                    resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                    total = multi - resultado;
                    jQuery("#list").jqGrid('setRowData', rowid, {total: total, cal_des: resultado});
                } else {
                    desc = descuento_grid;
                    precio = parseFloat(val);
                    multi = parseFloat(cantidad_grid) * parseFloat(precio);
                    descuento = ((multi * parseFloat(desc)) / 100);
                    flotante = parseFloat(descuento);
                    resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                    total = parseFloat(multi);
                    jQuery("#list").jqGrid('setRowData', rowid, {total: total});
                }

                // proceso incluye iva
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
                        if (dd['incluye'] == "No") {
                            subtotal = dd['total'];
                            sub1 = subtotal;
                            //iva1 = (sub1 * 0.12).toFixed(3);     
                            iva1 = sub1 * (calculoIVA / 100);

                            subtotal0 = parseFloat(subtotal0) + 0;
                            subtotal12 = parseFloat(subtotal12) + parseFloat(sub1);
                            descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);
                            iva12 = parseFloat(iva12) + parseFloat(iva1);

                            subtotal0 = parseFloat(subtotal0);
                            subtotal12 = parseFloat(subtotal12);
                            iva12 = parseFloat(iva12);
                            descu_total = parseFloat(descu_total);
                        } else {
                            if (dd['incluye'] == "Si") {
                                subtotal = dd['total'];
                                //sub2 = (subtotal / 1.12).toFixed(3);
                                //iva2 = (sub2 * 0.12).toFixed(3);
                                sub2 = subtotal / ((calculoIVA / 100) + 1);
                                iva2 = sub2 * (calculoIVA / 100);

                                subtotal0 = parseFloat(subtotal0) + 0;
                                subtotal12 = parseFloat(subtotal12) + parseFloat(sub2);
                                iva12 = parseFloat(iva12) + parseFloat(iva2);
                                descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);

                                subtotal0 = parseFloat(subtotal0);
                                subtotal12 = parseFloat(subtotal12);
                                iva12 = parseFloat(iva12);
                                descu_total = parseFloat(descu_total);
                            }
                        }
                    } else {
                        if (dd['iva'] == "No") {
                            subtotal = dd['total'];
                            sub = subtotal;

                            subtotal0 = parseFloat(subtotal0) + parseFloat(sub);
                            subtotal12 = parseFloat(subtotal12) + 0;
                            iva12 = parseFloat(iva12) + 0;
                            descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);

                            subtotal0 = parseFloat(subtotal0);
                            subtotal12 = parseFloat(subtotal12);
                            iva12 = parseFloat(iva12);
                            descu_total = parseFloat(descu_total);
                        }
                    }
                }

                total_total = parseFloat(total_total) + (parseFloat(subtotal0) + parseFloat(subtotal12) + parseFloat(iva12));
                total_total = parseFloat(total_total);

                $("#total_p").val(subtotal0);
                $("#total_p2").val(subtotal12);
                $("#iva").val(iva12);
                $("#desc").val(descu_total);
                $("#tot").val(total_total);
                $("#total_p").val(subtotal0.toFixed(2));
                $("#total_p2").val(subtotal12.toFixed(2));
                $("#iva").val(iva12.toFixed(2));
                $("#desc").val(descu_total.toFixed(2));
                $("#tot").val(total_total.toFixed(2));
                $("#codigo_barras").focus();

            }
        }
    });

    // tabla proformas  
    jQuery("#list2").jqGrid({
        url: 'xmlBuscarProformas.php',
        datatype: 'xml',
        colNames: ['ID', 'IDENTIFICACIÓN', 'CLIENTE', 'MONTO TOTAL', 'FECHA', 'ID_REGISTRO', 'ESTADO FACTURA', 'ESTADO NOTA VENTA', 'ESTADO ENTREGA'],
        colModel: [
            {name: 'id_proforma', index: 'P.id_factura_venta', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 50},
            {name: 'identificacion', index: 'C.identificacion', editable: false, search: true, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 150, searchoptions: {sopt: ["eq"]}},
            {name: 'nombres_cli', index: 'C.nombres_cli', editable: true, search: true, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 200, searchoptions: {sopt: ["cn"]}},
            {name: 'total_proforma', index: 'total_proforma', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 100},
            {name: 'fecha_proforma', index: 'fecha_proforma', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 120},
            {name: 'id_registro', index: 'P.id_registro', hidden: true, search: false},

            {name: 'estado',
                index: 'estado',
                editable: false,
                search: false,
                frozen: true,
                hidden: false,
                align: "center",
                formatter: function (cellvalue, options, rowObject) {
                    if (cellvalue == 1) {
                        return '<div style="background-color: green; color: white">CON FACTURA<div>';
                    }
                    return '<div style="background-color: white; color: white">SIN FACTURA<div>';
                },
                width: 200


            },
            {name: 'estado_nota',
                index: 'estado',
                editable: false,
                search: false,
                frozen: true,
                hidden: false,
                align: "center",
                formatter: function (cellvalue, options, rowObject) {
                    if (cellvalue == 1) {
                        return '<div style="background-color: green; color: white">CON NOTA VENTA<div>';
                    }
                    return '<div style="background-color: white; color: white">SIN NOTA VENTA<div>';
                },
                width: 200


            },
            {name: 'estado_tipo',
                index: 'estado',
                editable: false,
                search: false,
                frozen: true,
                hidden: false,
                align: "center",
                formatter: function (cellvalue, options, rowObject) {
                    if (cellvalue == 1) {
                        return '<div style="background-color: green; color: white">ENTREGADO SIN F<div>';
                    }
                    return '<div style="background-color: white; color: white">SIN ENTREGA<div>';
                },
                width: 200


            }
        ],
        rowNum: 30,
        width: 750,
        height: 220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager2'),
        sortname: 'id_proforma',
        sortorder: 'asc',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
            jQuery('#list2').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list2").jqGrid('getRowData', id);
                var valor = ret.id_proforma;
                idRegistro = ret.id_registro;
                /////////////agregregar datos proforma////////
                $("#comprobante").val(valor);
                $("#btnGuardar").attr("disabled", true);

                $("#list").jqGrid("clearGridData", true);
                $("#total_p").val("0.000");
                $("#total_p2").val("0.000");
                $("#desc").val("0.000");
                $("#iva").val("0.000");
                $("#tot").val("0.000");
                $("#total_px").val("0.000");
                $("#total_p2x").val("0.000");
                $("#descx").val("0.000");
                $("#ivax").val("0.000");
                $("#totx").val("0.000");

                cargarProforma(valor);
                $("#buscar_proformas").dialog("close");
            } else {
                alertify.alert("Seleccione una Proforma");
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
            if (!!id) {
                cargarRegistro(id);
                $("#buscar_proformas").dialog("close");
            } else {
                alertify.alert("Seleccione una Proforma");
            }
        }
    });

    //TODO refacorizar cargar registro
    // tabla proformas  
    jQuery("#list3").jqGrid({
        url: 'xmlBuscarRegistros.php',
        datatype: 'xml',
        colNames: ['N°. REG',
            'IDENTIFICACIÓN',
            'CLIENTE',
            'NRO. SERIE',
            'MODELO',
            'FECHA INGRESO',
            'ID_CLIENTE',
            'ID_PROFORMA',
            'OBSERVACIONES',
            'ACCESORIOS',
            'TIPO EQUIPO'],

        colModel: [
            {name: 'id_registro', index: 'P.id_registro', editable: false, search: true, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 50, searchoptions: {sopt: ["eq"]}},
            {name: 'identificacion', index: 'C.identificacion', editable: false, search: true, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 150, searchoptions: {sopt: ["eq"]}},
            {name: 'nombres_cli', index: 'C.nombres_cli', editable: true, search: true, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 220, searchoptions: {sopt: ["cn"]}},
            {name: 'nro_serie', index: 'P.nro_serie', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 100, searchoptions: {sopt: ["cn"]}},
            {name: 'modelo', index: 'P.modelo', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 100, searchoptions: {sopt: ["cn"]}},
            {name: 'fecha', index: 'fecha', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 120},
            {name: 'id_cliente', index: 'C.id_cliente', editable: true, search: false, hidden: true, editrules: {edithidden: false}, align: 'center', frozen: true, width: 120},
            {name: 'id_proforma', index: 'PF.id_proforma', search: false, hidden: true},
            {name: 'observacines', index: 'P.observacines', search: false, hidden: true},
            {name: 'detalles', index: 'P.detalles', search: false, hidden: true},
            {name: 'descripcion', index: 'TE.descripcion', search: false, hidden: false},
        ],
        rowNum: 30,
        width: 950,
        height: 220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager3'),
        sortname: 'P.id_registro',
        sortorder: 'asc',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list3").jqGrid('getGridParam', 'selrow');
            jQuery('#list3').jqGrid('restoreRow', id);

            let idproforma = jQuery("#list3").jqGrid('getCell', id, 7)
            if (!!idproforma) {
                $("#btnGuardar").attr("disabled", true);
                $("#btnModificar").attr("disabled", false);
                cargarProforma(idproforma);
            } else {
                limpiarListaProductos();
               
                $("#btnGuardar").attr("disabled", false);
                $("#btnModificar").attr("disabled", true);
            }

            idRegistro = id;
            //var ret = jQuery("#list3").jqGrid('getRowData', id);
            var cedula = jQuery("#list3").jqGrid('getCell', id, 1);
            var nombres = jQuery("#list3").jqGrid('getCell', id, 2);
            var id_cliente = jQuery("#list3").jqGrid('getCell', id, 6);
            var datos = "Numero Serie: " + jQuery("#list3").jqGrid('getCell', id, 3);
            var observaciones = jQuery("#list3").jqGrid('getCell', id, 8);
            var accesorios = jQuery("#list3").jqGrid('getCell', id, 9);

            datos = datos + "\nModelo: " + jQuery("#list3").jqGrid('getCell', id, 4);
            datos = datos + "\nFecha Ingreso: " + jQuery("#list3").jqGrid('getCell', id, 5);

            $("#observaciones").val(observaciones);
            $("#accesorios").val(accesorios);

            $("#ruc_ci").val(cedula);
            $("#nombres_completos").val(nombres);
            $("#id_cliente").val(id_cliente);
            $("#datos").val(datos);
            $("#buscar_ingresos").dialog("close");
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

    jQuery(window).bind('resize', function () {
        jQuery("#list").setGridWidth(jQuery('#grid_container').width(), true);
    }).trigger('resize');


    $("#btnAtras").click(fecha_atras);
    $("#btnAdelante").click(fecha_siguiente);
}


function fecha_atras(e) {
    e.preventDefault();
    $("#btnAdelante")[0].disabled = false;
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: {
            "id_tabla": "id_registro",
            "tabla": "registro_equipobtnAtras",
            "comprobante": idRegistro || 2,
            "tipo": 1
        }
    })
            .done(function (data) {
                let val = data;
                if (!!val) {
                    idRegistro = val;
                } else {
                    $("#btnAtras")[0].disabled = true;
                }
                cargarRegistro(data);
            });
}

function fecha_siguiente(e) {
    e.preventDefault();
    $("#btnAtras")[0].disabled = false;
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: {
            "id_tabla": "id_registro",
            "tabla": "registro_equipo",
            "tipo": 2,
            "comprobante": idRegistro || 0
        }
    })
            .done(function (data) {
                let val = data;
                if (!!data) {
                    idRegistro = val;
                } else {
                    $("#btnAdelante")[0].disabled = true;
                }
                cargarRegistro(data);
            });
}
