$(document).on("ready", inicio);
var calculoIVA = 0;
var t;
var num_serie_liq = "";

$(document).keydown(function (e) {
    var e = e || event;
    var keycode = e.which || e.keyCode;
    var obj = e.target || e.srcElement;
    // No activar el evento si estamos en un formulario
    //if(obj.tagName.toLowerCase()=="textarea") { return; }
    //if(obj.tagName.toLowerCase()=="input") { return; }
    // Guardar Factura
    //    if(keycode == 118) { guardar_factura()}
    //    if(keycode == 119) { ingresar_cambio()}
    // Tecla Control Cliente
    //    if(keycode == 17) { $("#ruc_ci").select() }

    // Tecla Control Cliente
    if (keycode == 40) {
        agregar()
    }
    if (keycode == 39) {
        guardar_serie()
    }
    if (keycode == 27) {
        cancelar()
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
                num_serie_liq = val;
            }
        },
    });
}

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

var dialogo2 = {
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 350,
    modal: true,
    show: "explode",
    hide: "blind"
}

var dialogo3 = {
    autoOpen: false,
    resizable: false,
    width: 420,
    height: 180,
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

var dialogo5 = {
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 350,
    modal: true,
    show: "explode",
    hide: "blind"
}
var dialogo10 = {
    autoOpen: false,
    resizable: false,
    width: 1000,
    height: 350,
    modal: true,
    show: "explode",
    hide: "blind"
}
var dialogo77 = {
    autoOpen: false,
    resizable: false,
    width: 1000,
    height: 350,
    modal: true,
    show: "explode",
    hide: "blind"
}
var dialogotecnico = {
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 350,
    modal: true,
    show: "explode",
    hide: "blind"
}

var dialogo6 = {
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 350,
    modal: true,
    show: "explode",
    hide: "blind"
}

var dialogo7 = {
    autoOpen: false,
    resizable: false,
    width: 300,
    height: 200,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind"
}
var dialogo8 = {
    autoOpen: false,
    resizable: false,
    width: 420,
    height: 250,
    modal: true,
    show: "explode",
    hide: "blind"
}


function ValidNum(e) {
    if (e.keyCode < 48 || e.keyCode > 57) {
        e.returnValue = false;
    }
    return true;
}
function enter_liqui(e) {
    if (e.which == 13 || e.keyCode == 13) {
        entrar_liqui();
        return false;
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

function enter1(e) {
    if (e.which == 13 || e.keyCode == 13) {
        entrar2();
        return false;
    }
    return true;
}

function enter2(e) {
    if (e.which == 13 || e.keyCode == 13) {
        entrar3();
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

function enter4(e) {
    if (e.which == 13 || e.keyCode == 13) {
        comprobar1();
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

function enter6(e) {
    if (e.which == 13 || e.keyCode == 13) {
        comprobar3();
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

function enter8(e) {
    if (e.which == 13 || e.keyCode == 13) {
        guardar_cambio();
        return false;
    }
    return true;
}
function enter9(e) {
    if (e.which == 13 || e.keyCode == 13) {
        guardar_factura();
        return false;
    }
    return true;
}

function entrar() {


    var expreg = /^[0-9]+([.])?([0-9]+)?$/;

    if (expreg.test($("#cantidad").val())) {

        if ($("#cod_producto").val() == "") {
            $("#codigo_barras").focus();
            alertify.error("Ingrese un producto");
        } else {
            if ($("#codigo").val() == "") {
                $("#codigo").focus();
                alertify.error("Ingrese un producto");
            } else {
                if ($("#producto").val() == "") {
                    $("#producto").focus();
                    alertify.error("Ingrese un producto");
                } else {
                    //                    if ($("#num_liquidacion").val() == "") {
                    //                        $("#num_liquidacion").focus();
                    //                        alertify.error("Ingrese num LiquidaciIIon");
                    //                    } else {

                    if ($("#cantidad").val() == "") {
                        $("#cantidad").focus();
                    } else {
                        $("#p_venta").focus();
                    }
                    //                    }
                }
            }
        }

    } else {
        alertify.error("Debe estar un nùmero antes del punto");
    }
}
function entrar_liqui() {
    $("#cantidad").select();

    var expreg = /^[0-9]+([.])?([0-9]+)?$/;

    if (expreg.test($("#cantidad").val())) {

        if ($("#cod_producto").val() == "") {
            $("#codigo_barras").focus();
            alertify.error("Ingrese un producto");
        } else {
            if ($("#codigo").val() == "") {
                $("#codigo").focus();
                alertify.error("Ingrese un producto");
            } else {
                if ($("#producto").val() == "") {
                    $("#producto").focus();
                    alertify.error("Ingrese un producto");
                } else {
                    //                    if ($("#num_liquidacion").val() == "") {
                    //                        $("#num_liquidacion").focus();
                    //                        alertify.error("Ingrese num LiquidaciIIon");
                    //                    } else {

                    if ($("#cantidad").val() == "" || $("#cantidad").val() == 1) {
                        $("#cantidad").focus();
                    } else {
                        $("#p_venta").focus();
                    }
                    //                    }
                }
            }
        }

    } else {
        alertify.error("Debe estar un nùmero antes del punto");
    }
}
function entrar2() {
    if ($("#cod_producto").val() == "") {
        $("#codigo_barras").focus();
        alertify.error("Ingrese un producto");
    } else {
        if ($("#codigo").val() == "") {
            $("#codigo").focus();
            alertify.error("Ingrese un producto");
        } else {
            if ($("#producto").val() == "") {
                $("#producto").focus();
                alertify.error("Ingrese un producto");
            } else {
                //                if ($("#num_liquidacion").val() == "") {
                //                    $("#num_liquidacion").focus();
                //                    alertify.error("Ingrese num Liquidacion");
                //                } else {
                if ($("#cantidad").val() == "") {
                    $("#cantidad").focus();
                } else {
                    //                       $("#serie_campos").focus();
                    //                        abrirDialogo();
                    if ($("#p_venta").val() == "") {
                        $("#p_venta").focus();
                        alertify.error("Ingrese precio venta");
                    } else {
                        //                        $("#serie_campos").focus();                        
                        //$("#descuento").focus();                      
                        $('#mino').prop('selected', true);
                        entrar3();
                    }
                }
                //                }
            }
        }
    }
}

function limpiar_campos() {
    $("#cod_producto").val("");
    $("#codigo_barras").val("");
    $("#codigo").val("");
    $("#producto").val("");
    //    $("#num_liquidacion").val("");
    $("#cantidad").val("");
    $("#p_venta").val("");
    $("#descuento").val("");
    $("#des").val("");
    $("#disponibles").val("");
    $("#incluye").val("");
    $("#carga_series").val("");
}

function entrar3() {

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
    var subtotal_total = 0;
    var iva12 = 0;
    var total_total = 0;
    var descu_total = 0;

    if ($("#cod_producto").val() == "") {
        $("#codigo_barras").focus();
        alertify.error("Ingrese un producto");
    } else {
        if ($("#codigo").val() == "") {
            $("#codigo").focus();
            alertify.error("Ingrese un producto");
        } else {
            if ($("#producto").val() == "") {
                $("#producto").focus();
                alertify.error("Ingrese un producto");
            } else {
                //                if ($("#num_liquidacion").val() == "") {
                //                    $("#num_liquidacion").focus();
                //                    alertify.error("Ingrese num liquidacion");
                //                } else {
                if ($("#cantidad").val() == "") {
                    $("#cantidad").focus();
                } else {
                    if ($("#p_venta").val() == "") {
                        $("#p_venta").focus();
                        alertify.error("Ingrese un precio");
                    } else {
                        if ($("#inventar").val() == "Si") {
                            //                                if (parseInt($("#cantidad").val()) > parseInt($("#disponibles").val())) {
                            //                                    $("#cantidad").focus();
                            //                                    alertify.error("Error.. Fuera de Stock cantidad disponible: " + $("#disponibles").val());
                            //                                } else {
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
                                    precio_ux: precio.toFixed(2),
                                    descuentox: parseFloat(desc).toFixed(2),
                                    cal_desx: resultado.toFixed(2),
                                    totalx: total.toFixed(2),
                                    iva: $("#iva_producto").val(),
                                    pendiente: 0,
                                    incluye: $("#incluye").val()
                                };
                                addCentroCostoRowData(datarow);

                                su = jQuery("#list").jqGrid('addRowData', $("#cod_producto").val(), datarow);
                                limpiar_campos();
                            } else {
                                for (var i = 0; i < filas.length; i++) {
                                    var id = filas[i];

                                    if ((id['cod_producto'] == $("#cod_producto").val()) && (id['id_centro_costo'] == $("#sel_centro_costo").val())) {
                                        repe = 1;
                                        var can = id['cantidad'];
                                    }
                                }

                                if (repe == 1) {
                                    suma = parseFloat(can) + parseFloat($("#cantidad").val());
                                    suma = Number(suma.toFixed(2));
                                    if (suma > parseInt($("#disponibles").val())) {
                                        $("#cantidad").focus();
                                        alertify.error("Error.. Fuera de Stock cantidad disponible: " + $("#disponibles").val());
                                    } else {
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
                                            multi = parseFloat(suma) * parseFloat(precio);
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
                                            precio_ux: precio.toFixed(2),
                                            descuentox: parseFloat(desc).toFixed(2),
                                            cal_desx: resultado.toFixed(2),
                                            totalx: total.toFixed(2),
                                            iva: $("#iva_producto").val(),
                                            pendiente: 0,
                                            incluye: $("#incluye").val()
                                        };
                                        addCentroCostoRowData(datarow);

                                        su = jQuery("#list").jqGrid('setRowData', $("#cod_producto").val() + "" + $("#sel_centro_costo").val(), datarow);
                                        limpiar_campos();
                                    }
                                } else {
                                    if (filas.length < $("#num_items").val()) {
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
                                            precio_ux: precio.toFixed(2),
                                            descuentox: parseFloat(desc).toFixed(2),
                                            cal_desx: resultado.toFixed(2),
                                            totalx: total.toFixed(2),
                                            iva: $("#iva_producto").val(),
                                            pendiente: 0,
                                            incluye: $("#incluye").val()
                                        };
                                        addCentroCostoRowData(datarow);

                                        su = jQuery("#list").jqGrid('addRowData', $("#cod_producto").val(), datarow);
                                        limpiar_campos();
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
                            var suma_total = 0;
                            // fin                                                                                   
                            var fil = jQuery("#list").jqGrid("getRowData");
                            for (var t = 0; t < fil.length; t++) {
                                var dd = fil[t];
                                if (dd['iva'] == "Si") {
                                    if (dd['incluye'] == "No") {
                                        subtotal = dd['total'];
                                        sub1 = subtotal;
                                        iva1 = sub1 * calculoIVA / 100;


                                        subtotal0 = parseFloat(subtotal0) + 0;
                                        subtotal12 = parseFloat(subtotal12) + parseFloat(sub1);
                                        subtotal_total = parseFloat(subtotal0) + parseFloat(subtotal12);
                                        descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);
                                        iva12 = parseFloat(iva12) + parseFloat(iva1);

                                        subtotal0 = parseFloat(subtotal0);
                                        subtotal12 = parseFloat(subtotal12);
                                        subtotal_total = parseFloat(subtotal_total);
                                        iva12 = parseFloat(iva12);
                                        descu_total = parseFloat(descu_total);
                                        suma_total = suma_total + parseFloat(dd['cantidad']);
                                    } else {
                                        if (dd['incluye'] == "Si") {
                                            subtotal = dd['total'];
                                            sub2 = subtotal / ((calculoIVA / 100) + 1);
                                            iva2 = sub2 * (calculoIVA / 100);

                                            subtotal0 = parseFloat(subtotal0) + 0;
                                            subtotal12 = parseFloat(subtotal12) + parseFloat(sub2);
                                            subtotal_total = parseFloat(subtotal0) + parseFloat(subtotal12);
                                            iva12 = parseFloat(iva12) + parseFloat(iva2);
                                            descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);

                                            subtotal0 = parseFloat(subtotal0);
                                            subtotal12 = parseFloat(subtotal12);
                                            subtotal_total = parseFloat(subtotal_total);
                                            iva12 = parseFloat(iva12);
                                            descu_total = parseFloat(descu_total);
                                            suma_total = suma_total + parseFloat(dd['cantidad']);
                                        }
                                    }
                                } else {
                                    if (dd['iva'] == "No") {
                                        subtotal = dd['total'];
                                        sub = subtotal;

                                        subtotal0 = parseFloat(subtotal0) + parseFloat(sub);
                                        subtotal12 = parseFloat(subtotal12) + 0;
                                        subtotal_total = parseFloat(subtotal0) + parseFloat(subtotal12);
                                        iva12 = parseFloat(iva12) + 0;
                                        descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);

                                        subtotal0 = parseFloat(subtotal0);
                                        subtotal12 = parseFloat(subtotal12);
                                        subtotal_total = parseFloat(subtotal_total);
                                        iva12 = parseFloat(iva12);
                                        descu_total = parseFloat(descu_total);
                                        suma_total = suma_total + parseFloat(dd['cantidad']);
                                    }
                                }
                            }
                            total_total = parseFloat(total_total) + (parseFloat(subtotal0) + parseFloat(subtotal12) + parseFloat(iva12));
                            total_total = parseFloat(total_total);

                            var item = filas.length + 1;
                            $("#total_p").val(subtotal0);
                            $("#total_p2").val(subtotal12);
                            $("#sub").val(subtotal_total);
                            $("#iva").val(iva12);
                            $("#desc").val(descu_total);
                            $("#tot").val(total_total);
                            $("#total_px").val(subtotal0.toFixed(2));
                            $("#total_p2x").val(subtotal12.toFixed(2));
                            $("#subx").val(subtotal_total.toFixed(2));
                            $("#ivax").val(iva12.toFixed(2));
                            $("#descx").val(descu_total.toFixed(2));
                            $("#totx").val(total_total.toFixed(2));
                            $("#items").val(item);
                            $("#num").val(suma_total);
                            $("#codigo_barras").focus();
                            //                                }
                        } else {
                            if ($("#inventar").val() == "No") {
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
                                        precio_ux: precio.toFixed(2),
                                        descuentox: parseFloat(desc).toFixed(2),
                                        cal_desx: resultado.toFixed(2),
                                        totalx: total.toFixed(2),
                                        iva: $("#iva_producto").val(),
                                        pendiente: 0,
                                        incluye: $("#incluye").val()
                                    };
                                    addCentroCostoRowData(datarow);

                                    su = jQuery("#list").jqGrid('addRowData', $("#cod_producto").val(), datarow);
                                    limpiar_campos();
                                } else {
                                    for (var i = 0; i < filas.length; i++) {
                                        var id = filas[i];

                                        if (id['cod_producto'] == $("#cod_producto").val()) {
                                            repe = 1;
                                            var can = id['cantidad'];
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
                                            multi = parseFloat(suma) * parseFloat(precio);
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
                                            precio_ux: precio.toFixed(2),
                                            descuentox: parseFloat(desc).toFixed(2),
                                            cal_desx: resultado.toFixed(2),
                                            totalx: total.toFixed(2),
                                            iva: $("#iva_producto").val(),
                                            pendiente: 0,
                                            incluye: $("#incluye").val()
                                        };
                                        addCentroCostoRowData(datarow);

                                        su = jQuery("#list").jqGrid('setRowData', $("#cod_producto").val() + "" + $("#sel_centro_costo").val(), datarow);
                                        limpiar_campos();
                                    } else {
                                        if (filas.length < $("#num_items").val()) {
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
                                                precio_ux: precio.toFixed(2),
                                                descuentox: parseFloat(desc).toFixed(2),
                                                cal_desx: resultado.toFixed(2),
                                                totalx: total.toFixed(2),
                                                iva: $("#iva_producto").val(),
                                                pendiente: 0,
                                                incluye: $("#incluye").val()
                                            };
                                            addCentroCostoRowData(datarow);
                                            su = jQuery("#list").jqGrid('addRowData', $("#cod_producto").val(), datarow);
                                            limpiar_campos();
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
                                var suma_total = 0;
                                // fin                                                   

                                var fil = jQuery("#list").jqGrid("getRowData");
                                for (var t = 0; t < fil.length; t++) {
                                    var dd = fil[t];
                                    if (dd['iva'] == "Si") {
                                        if (dd['incluye'] == "No") {
                                            subtotal = dd['total'];
                                            sub1 = subtotal;
                                            iva1 = sub1 * (calculoIVA / 100);

                                            subtotal0 = parseFloat(subtotal0) + 0;
                                            subtotal12 = parseFloat(subtotal12) + parseFloat(sub1);
                                            subtotal_total = parseFloat(subtotal0) + parseFloat(subtotal12);
                                            descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);
                                            iva12 = parseFloat(iva12) + parseFloat(iva1);

                                            subtotal0 = parseFloat(subtotal0);
                                            subtotal12 = parseFloat(subtotal12);
                                            subtotal_total = parseFloat(subtotal_total);
                                            iva12 = parseFloat(iva12);
                                            descu_total = parseFloat(descu_total);
                                            suma_total = suma_total + parseFloat(dd['cantidad']);
                                        } else {
                                            if (dd['incluye'] == "Si") {
                                                subtotal = dd['total'];
                                                sub2 = subtotal / ((calculoIVA / 100) + 1);
                                                iva2 = sub2 * (calculoIVA / 100);

                                                subtotal0 = parseFloat(subtotal0) + 0;
                                                subtotal12 = parseFloat(subtotal12) + parseFloat(sub2);
                                                subtotal_total = parseFloat(subtotal0) + parseFloat(subtotal12);
                                                iva12 = parseFloat(iva12) + parseFloat(iva2);
                                                descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);

                                                subtotal0 = parseFloat(subtotal0);
                                                subtotal12 = parseFloat(subtotal12);
                                                subtotal_total = parseFloat(subtotal_total);
                                                iva12 = parseFloat(iva12);
                                                descu_total = parseFloat(descu_total);
                                                suma_total = suma_total + parseFloat(dd['cantidad']);
                                            }
                                        }
                                    } else {
                                        if (dd['iva'] == "No") {
                                            subtotal = dd['total'];
                                            sub = subtotal;

                                            subtotal0 = parseFloat(subtotal0) + parseFloat(sub);
                                            subtotal12 = parseFloat(subtotal12) + 0;
                                            subtotal_total = parseFloat(subtotal0) + parseFloat(subtotal12);
                                            iva12 = parseFloat(iva12) + 0;
                                            descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);

                                            subtotal0 = parseFloat(subtotal0);
                                            subtotal12 = parseFloat(subtotal12);
                                            subtotal_total = parseFloat(subtotal_total);
                                            iva12 = parseFloat(iva12);
                                            descu_total = parseFloat(descu_total);
                                            suma_total = suma_total + parseFloat(dd['cantidad']);
                                        }
                                    }
                                }

                                total_total = parseFloat(total_total) + (parseFloat(subtotal0) + parseFloat(subtotal12) + parseFloat(iva12));
                                total_total = parseFloat(total_total);

                                var item = filas.length + 1;
                                $("#total_p").val(subtotal0);
                                $("#total_p2").val(subtotal12);
                                $("#sub").val(subtotal_total);
                                $("#iva").val(iva12);
                                $("#desc").val(descu_total);
                                $("#tot").val(total_total);
                                $("#total_px").val(subtotal0.toFixed(2));
                                $("#total_p2x").val(subtotal12.toFixed(2));
                                $("#subx").val(subtotal_total.toFixed(2));
                                $("#ivax").val(iva12.toFixed(2));
                                $("#descx").val(descu_total.toFixed(2));
                                $("#totx").val(total_total.toFixed(2));
                                $("#items").val(item);
                                $("#num").val(suma_total);
                                $("#codigo_barras").focus();
                            }
                        }
                    }
                }
                //                }
            }
        }
    }
}

function agregar() {
    if ($("#combobox").val() != "") {
        var filas2 = jQuery("#list3").jqGrid("getRowData");
        var su;
        var count = 0;
        var canti = $("#cantidad").val();
        var valo_combo = '';
        valo_combo = $("#combobox").val();
        console.log(valo_combo);
        if (filas2.length < canti) {
            if (filas2.length == 0) {
                var datarow = {
                    id_serie: count = count + 1,
                    serie: valo_combo
                };
                su = jQuery("#list3").jqGrid('addRowData', count, datarow);
                $("#series_area").val($("#series_area").val() + $("#combobox").val() + "\n");
                $("#combobox").val("");
            } else {
                var repe = 0;
                for (var i = 0; i < filas2.length; i++) {
                    var id = filas2[i];
                    if (id['serie'] === valo_combo) {
                        repe = 1;
                    }
                }
                if (repe == 0) {
                    datarow = {
                        id_serie: count = count + 1,
                        serie: valo_combo
                    };
                    su = jQuery("#list3").jqGrid('addRowData', count, datarow);
                    $("#series_area").val($("#series_area").val() + $("#combobox").val() + "\n");
                    $("#combobox").val("");
                } else {
                    $("#combobox").val("");
                    alertify.success("Error... Serie ingresada");
                }
            }
        } else {
            $("#btnAgregar").attr("disabled", "disabled");
            alertify.success("Error... Alcanzo el limite máximo");
        }
    } else {
        $("#combobox").focus();
        alertify.success("Error... En la serie");
    }
}



function countfactura() {
    var temp2 = "";
    var serie = $("#num_factura").val();
    for (var i = serie.length; i < 5; i++) {
        temp2 = temp2 + "0";
    }
    return temp2;
}

function autocompletar_factura() {
    var temp = "";
    var serie = $("#num_factura").val();
    for (var i = serie.length; i < 9; i++) {
        temp = temp + "0";
    }
    return temp;
}


function comprobar() {
    if ($("#num_factura").val() == "") {
        $("#num_factura").focus();
        alertify.error("Ingrese número de factura");
    } else {
        var a = autocompletar($("#num_factura").val());
        $("#num_factura").val(a + "" + $("#num_factura").val());
        $("#ruc_ci").focus();
    }
}

function nuevo_cliente() {
    alertify.confirm("Desea registrar un nuevo cliente", function (e) {
        if (e) {
            // verificar si esxiste cliente
            $.ajax({
                type: "POST",
                url: "comparar_cedulas.php",
                data: "cedula=" + $("#ruc_ci").val(),
                success: function (data) {
                    var val = data;
                    if (val == 1) {
                        $("#ruc_ci").val("");
                        $("#ruc_ci").focus();
                        alertify.error("Error... El cliente esta registrado");
                    } else {
                        if (
                            $("#ruc_ci").val().length != 10 &&
                            $("#ruc_ci").val().length !== 13
                        ) {
                            alertify.error("Error... Ingrese una Identificación valida");
                        } else {
                            // validar cedula ruc
                            var numero = $("#ruc_ci").val();
                            var suma = 0;
                            var residuo = 0;
                            var pri = false;
                            var pub = false;
                            var nat = false;
                            var modulo = 11;
                            var p1;
                            var p2;
                            var p3;
                            var p4;
                            var p5;
                            var p6;
                            var p7;
                            var p8;
                            var p9;

                            /* Aqui almacenamos los digitos de la cedula en variables. */
                            var d1 = numero.substr(0, 1);
                            var d2 = numero.substr(1, 1);
                            var d3 = numero.substr(2, 1);
                            var d4 = numero.substr(3, 1);
                            var d5 = numero.substr(4, 1);
                            var d6 = numero.substr(5, 1);
                            var d7 = numero.substr(6, 1);
                            var d8 = numero.substr(7, 1);
                            var d9 = numero.substr(8, 1);
                            var d10 = numero.substr(9, 1);

                            if (d3 < 6) {
                                nat = true;
                                p1 = d1 * 2;
                                if (p1 >= 10)
                                    p1 -= 9;
                                p2 = d2 * 1;
                                if (p2 >= 10)
                                    p2 -= 9;
                                p3 = d3 * 2;
                                if (p3 >= 10)
                                    p3 -= 9;
                                p4 = d4 * 1;
                                if (p4 >= 10)
                                    p4 -= 9;
                                p5 = d5 * 2;
                                if (p5 >= 10)
                                    p5 -= 9;
                                p6 = d6 * 1;
                                if (p6 >= 10)
                                    p6 -= 9;
                                p7 = d7 * 2;
                                if (p7 >= 10)
                                    p7 -= 9;
                                p8 = d8 * 1;
                                if (p8 >= 10)
                                    p8 -= 9;
                                p9 = d9 * 2;
                                if (p9 >= 10)
                                    p9 -= 9;
                                modulo = 10;
                            } else if (d3 == 6) {
                                pub = true;
                                p1 = d1 * 3;
                                p2 = d2 * 2;
                                p3 = d3 * 7;
                                p4 = d4 * 6;
                                p5 = d5 * 5;
                                p6 = d6 * 4;
                                p7 = d7 * 3;
                                p8 = d8 * 2;
                                p9 = 0;
                            } else if (d3 == 9) {
                                pri = true;
                                p1 = d1 * 4;
                                p2 = d2 * 3;
                                p3 = d3 * 2;
                                p4 = d4 * 7;
                                p5 = d5 * 6;
                                p6 = d6 * 5;
                                p7 = d7 * 4;
                                p8 = d8 * 3;
                                p9 = d9 * 2;
                            }

                            suma = p1 + p2 + p3 + p4 + p5 + p6 + p7 + p8 + p9;
                            residuo = suma % modulo;

                            var digitoVerificador = residuo == 0 ? 0 : modulo - residuo;
                            if (numero.length === 10) {
                                if (nat == true) {
                                    if (digitoVerificador != d10) {
                                        alertify.error("El número de cédula es incorrecto.");
                                        $("#direccion_cliente").attr("disabled", "disabled");
                                        $("#telefono_cliente").attr("disabled", "disabled");
                                        $("#correo").attr("disabled", "disabled");
                                    } else {
                                        if ($("#ruc_ci").val() == "0000000000") {
                                            alertify.error("El número de cédula es incorrecto.");
                                            $("#direccion_cliente").attr("disabled", "disabled");
                                            $("#telefono_cliente").attr("disabled", "disabled");
                                            $("#correo").attr("disabled", "disabled");
                                        } else {
                                            alertify.success("El número de cédula es correcto.");
                                            $("#nombre_cliente").focus();
                                            $("#direccion_cliente").removeAttr("disabled");
                                            $("#telefono_cliente").removeAttr("disabled");
                                            $("#correo").removeAttr("disabled");
                                        }
                                    }
                                }
                            } else {
                                var ruc = numero.substr(10, 13);
                                var digito3 = numero.substring(2, 3);
                                if (ruc == "001") {
                                    if (digito3 < 6) {
                                        if (nat == true) {
                                            if (digitoVerificador != d10) {
                                                alertify.error("El ruc persona natural3 es incorrecto.");
                                                $("#direccion_cliente").attr("disabled", "disabled");
                                                $("#telefono_cliente").attr("disabled", "disabled");
                                                $("#correo").attr("disabled", "disabled");
                                            } else {
                                                alertify.success("El ruc persona natural3 es correcto.");
                                                $("#nombre_cliente").focus();
                                                $("#direccion_cliente").removeAttr("disabled");
                                                $("#telefono_cliente").removeAttr("disabled");
                                                $("#correo").removeAttr("disabled");
                                            }
                                        }
                                    } else {
                                        if (digito3 == 6) {
                                            if (pub == true) {
                                                if (digitoVerificador != d9) {
                                                    alertify.error("El ruc público es incorrecto.");
                                                    $("#direccion_cliente").attr("disabled", "disabled");
                                                    $("#telefono_cliente").attr("disabled", "disabled");
                                                    $("#correo").attr("disabled", "disabled");
                                                } else {
                                                    alertify.success("El ruc público es correcto.");
                                                    $("#nombre_cliente").focus();
                                                    $("#direccion_cliente").removeAttr("disabled");
                                                    $("#telefono_cliente").removeAttr("disabled");
                                                    $("#correo").removeAttr("disabled");
                                                }
                                            }
                                        } else {
                                            if (digito3 == 9) {
                                                if (pri == true) {
                                                    if (digitoVerificador != d10) {

                                                        if (d10 == 4 || d10 == 6) {
                                                            alertify.success('El ruc de sociedad privado es correcto.');
                                                        } else {
                                                            alertify.error("El ruc privado es incorrecto.");
                                                            $("#direccion_cliente").attr("disabled", "disabled");
                                                            $("#telefono_cliente").attr("disabled", "disabled");
                                                            $("#correo").attr("disabled", "disabled");
                                                        }

                                                    } else {
                                                        alertify.success("El ruc privado es correcto.");
                                                        $("#nombre_cliente").focus();
                                                        $("#direccion_cliente").removeAttr("disabled");
                                                        $("#telefono_cliente").removeAttr("disabled");
                                                        $("#correo").removeAttr("disabled");
                                                    }
                                                }
                                            } else {
                                                if (d3 == 7 || d3 == 8) {
                                                    alertify.error(
                                                        "El tercer dígito ingresado es inválido"
                                                    );
                                                } else {
                                                    if (numero.substr(10, 3) != "001") {
                                                        alertify.error(
                                                            "El ruc de la empresa del sector privado debe terminar con 001"
                                                        );
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    if (numero.length == 13) {
                                        alertify.error("El ruc es incorrecto.");
                                        $("#direccion_cliente").attr("disabled", "disabled");
                                        $("#telefono_cliente").attr("disabled", "disabled");
                                        $("#correo").attr("disabled", "disabled");
                                    }
                                }
                            }
                        }
                    }
                },
            });
        } else {
            $("#ruc_ci").val("");
            $("#direccion_cliente").attr("disabled", "disabled");
            $("#telefono_cliente").attr("disabled", "disabled");
            $("#correo").attr("disabled", "disabled");
        }
    });
}

function comprobar1() {
    if ($("#num_factura").val() == "") {
        $("#num_factura").focus();
        alertify.error("Ingrese número de factura");
    } else {
        if ($("#id_proveedor").val() == "" && $("#ruc_ci").val() != "") {
            nuevo_cliente();
        } else {
            if ($("#ruc_ci").val() == "") {
                $("#ruc_ci").focus();
                alertify.error("Indique un cliente");
            }
        }
    }
}

function comprobar2() {
    if ($("#ruc_ci").val() == "") {
        $("#ruc_ci").focus();
        alertify.error("Indique un cliente");
    } else {
        if ($("#nombre_cliente").val() == "") {
            $("#nombre_cliente").focus();
            alertify.error("Nombres del cliente");
        } else {
            if ($("#direccion_cliente").val() == "") {
                $("#direccion_cliente").focus();
                alertify.error("Dirección del cliente");
            } else {
                $("#telefono_cliente").focus();
            }
        }
    }
}

function comprobar3() {
    if ($("#ruc_ci").val() == "") {
        $("#ruc_ci").focus();
        alertify.error("Indique un cliente");
    } else {
        if ($("#nombre_cliente").val() == "") {
            $("#nombre_cliente").focus();
            alertify.error("Nombres del cliente");
        } else {
            if ($("#direccion_cliente").val() == "") {
                $("#direccion_cliente").focus();
                alertify.error("Dirección del cliente");
            } else {
                $("#correo").focus();
            }
        }
    }
}
function calculo_cambio() {

    if ($("#valor_recibo").val() != "") {
        var cambio = (parseFloat($("#valor_recibo").val()) - parseFloat($("#tot").val()))
        $("#valor_cambio").val('0');
        $("#valor_cambio").val(cambio.toFixed(2));
        $("#valor_cambio").select();

    } else {
        false
    }
}

function guardar_serie() {
    var tam2 = jQuery("#list3").jqGrid("getRowData");

    if (tam2.length > 0) {
        $("#combobox").append('<option></option>');
        var v1 = new Array();
        var string_v1 = "";
        var fil = jQuery("#list3").jqGrid("getRowData");

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
                    alertify.success("Series Guardado Correctamente");
                    $("#list3").jqGrid("clearGridData", true);
                    $("#series").dialog("close");
                    $("#descuento").focus();
                }
            }
        });

    } else {
        alertify.alert("Error... Ingrese las series");
    }
}

function guardar_cambio() {
    var cambio = 0;
    if ($(id_liquidacion_compra).val() == "") {

        if ($("#valor_cambio").val() > 0) {
            $("#valor_cambio").val("");
            cambio = (parseFloat($("#valor_recibo").val()) - parseFloat($("#tot").val()))
            $.ajax({
                type: "POST",
                url: "guardar_cambio.php",
                data: "valor_recibo=" + $("#valor_recibo").val() + "&valor_cambio=" + cambio,

                success: function (data) {
                    var val = data;
                    if (val != 0) {
                        alertify.alert("Cambio Guardado");
                        $("#valor_cambioid").dialog("close");

                    }
                }
            });
        } else {
            false
        }
    } else {
        alertify.alert("Error... Ingrese la factura");
    }
}
function guardar_factura() {
    $("#valor_cambioid").dialog("close");
    var tam = jQuery("#list").jqGrid("getRowData");

    if ($("#num_factura").val() == "") {
        $("#num_factura").focus();
        alertify.error("Ingrese nùmero de la factura");
    } else {
        var num_factu = (num_serie_liq + "-" + $("#num_factura").val());
        $.ajax({
            type: "POST",
            url: "comparar_num_venta.php",
            data: "num_fac=" + num_factu,
            success: function (data) {
                var val = data;
                if (val != 0) {
                    $("#num_factura").val("");
                    $("#num_factura").focus();
                    alertify.error("Error... La liquidación ya existe, favor verificar el nùmero que corresponda");
                    var res1 = parseInt(val.substr(8, 16));
                    res1 = res1 + 1;

                    $("#num_factura").val(res1);
                    var a1 = autocompletar(res1);
                    var validado = a1 + "" + res1;
                    $("#num_factura").val(validado);
                } else {
                    if ($("#ruc_ci").val() == "") {
                        var a = autocompletar($("#num_factura").val());
                        $("#num_factura").val(a + "" + $("#num_factura").val());
                        $("#ruc_ci").focus();
                        alertify.error("Indique un cliente");
                    } else {
                        if ($("#nombre_cliente").val() == "") {
                            $("#nombre_cliente").focus();
                            alertify.error("Nombres del cliente");
                        } else {
                            if ($("#tipo_precio_venta").val() == "") {
                                $("#tipo_precio_venta").focus();
                                alertify.alert("Seleccione un tipo de precio");
                            } else {
                                //                                if ($("#num_liquidacion").val() == "") {
                                //                                    $("#num_liquidacion").focus();
                                //                                    alertify.error("Ingrese num LiquidaciIIon");
                                //                                } else {
                                if (tam.length == 0) {
                                    $("#codigo_barras").focus();
                                    alertify.error("Error... Ingrese productos a la factura");
                                } else {


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
                                    var fil = jQuery("#list").jqGrid("getRowData");

                                    for (var i = 0; i < fil.length; i++) {
                                        var datos = fil[i];
                                        v1[i] = datos['cod_producto'];
                                        v2[i] = datos['cantidad'];
                                        v3[i] = datos['precio_u'];
                                        v4[i] = datos['descuento'];
                                        v5[i] = datos['total'];
                                        v6[i] = datos['pendiente'];
                                        v7[i] = datos['id_centro_costo'];
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

                                    var a = autocompletar($("#num_factura").val());
                                    var seriee = (num_serie_liq + "-" + a + "" + $("#num_factura").val());



                                    var seriee_guia = "000000000";

                                    var cambio = (parseFloat($("#valor_recibo").val()) - parseFloat($("#tot").val()));
                                    $("#btnGuardar").attr("disabled", true);
                                    $.ajax({
                                        type: "POST",
                                        url: "guardar_liquidacion_compra.php",
                                        data: "id_fac=" + $("#id_liquidacion_compra").val()
                                            + "&id_proveedor=" + $("#id_proveedor").val()
                                            + "&comprobante=" + $("#comprobante").val()
                                            + "&num_factura=" + seriee
                                            + "&fecha_actual=" + $("#fecha_actual").val()
                                            + "&hora_actual=" + $("#hora_actual").val()
                                            + "&proforma=" + $("#proforma").val()
                                            + "&cancelacion=" + $("#cancelacion").val()
                                            + "&tipo_precio=" + $("#tipo_precio_venta").val()
                                            + "&adelanto=" + $("#adelanto").val()
                                            + "&meses=" + $("#meses").val()
                                            + "&autorizacion=" + $("#autorizacion").val()
                                            + "&fecha_auto=" + $("#fecha_auto").val()
                                            + "&fecha_caducidad=" + $("#fecha_caducidad").val()
                                            + "&tarifa0=" + $("#total_p").val()
                                            + "&tarifa12=" + $("#total_p2").val()
                                            + "&iva=" + $("#iva").val()
                                            + "&desc=" + $("#desc").val()
                                            + "&tot=" + $("#tot").val()
                                            + "&ruc_ci=" + $("#ruc_ci").val()
                                            + "&nombre_cliente=" + $("#nombre_cliente").val()
                                            + "&direccion_cliente=" + $("#direccion_cliente").val()
                                            + "&telefono_cliente=" + $("#telefono_cliente").val()
                                            + "&correo=" + $("#correo").val()
                                            + "&campo1=" + string_v1
                                            + "&campo2=" + string_v2
                                            + "&campo3=" + string_v3
                                            + "&campo4=" + string_v4
                                            + "&campo5=" + string_v5
                                            + "&campo6=" + string_v6
                                            + "&campo7=" + string_v7
                                            + "&tipo_venta=" + $("#tipo_venta").val()
                                            + "&valor_recibo=" + $("#valor_recibo").val()
                                            + "&valor_cambio=" + cambio
                                            + "&id_vendedor=" + $("#vendedor").val()
                                            + "&fecha_dias=" + $("#fecha_dias").val()
                                            + "&num_guia_remision=" + seriee_guia
                                            + "&marca_vehiculo=" + $("#num_liquidacion").val()
                                            + "&placa_fac=" + $("#placa_fac").val()
                                            + "&propiedad=" + $("#propiedad").val()
                                            + "&num_reclamo=" + $("#num_reclamo").val()
                                            + "&num_chasis=" + $("#num_chasis").val()
                                            + "&comentario=" + $("#series_area").val()
                                            + "&formas=" + $("#formas").val(),
                                        dataType: "json",
                                        success: function (data) {
                                            if (data.estado == null) {

                                                alertify.confirm("TIEMPO DE ESPERA AGOTADO DEL SRI, VUELVA A  ENVIAR DESDE ESTADOS LIQUIDACION¿Desea Imprimir Comprobante?",
                                                    function (e) {
                                                        if (e) {

                                                            window.open("generarPDF_1.php?hoja=A5&id=" + data.id, '_blank');
                                                            location.reload();
                                                        } else {

                                                            location.reload();
                                                        }
                                                    });
                                            }
                                            if (data.estado == 60) {
                                                alertify.alert("NO SE GUARDO LA FACTURA, REVICE LOS DATOS")
                                                $("#btnGuardar").attr("disabled", false);
                                            }
                                            var val = data;
                                            if ($("#tipo_venta").val() == "FACTURA") {
                                                if ($("#formaspago").val() == 'Credito' || $("#formaspago").val() == 'Cheque' || $("#formaspago").val() == 'Tarjeta de Credito') {
                                                    if (data.estado == 2) {

                                                        alertify.confirm("AUTORIZADO¿Desea ingresar retenciones?",
                                                            function (e) {
                                                                if (e) {
                                                                    //                                                                        reenviar(data.id);
                                                                    $("#id_liquidacion_compra").val(data.id);
                                                                    $('.nav-tabs a[href="#tab_2"]').tab('show');
                                                                    $("#retencionF2").focus();
                                                                } else {
                                                                    //                                                                        reenviar(data.id);
                                                                    window.open("generarPDF_1.php?hoja=A5&id=" + data.id, '_blank');
                                                                    location.reload();
                                                                }
                                                            });

                                                    }
                                                } else {
                                                    window.open("generarPDF_1.php?hoja=A5&id=" + data.id, '_blank');
                                                    if (data.estado == 2) {



                                                        alertify.confirm("AUTORIZADO¿Desea ingresar retenciones?",
                                                            function (e) {
                                                                if (e) {
                                                                    //                                                                        reenviar(data.id);
                                                                    $("#id_liquidacion_compra").val(data.id);
                                                                    $('.nav-tabs a[href="#tab_2"]').tab('show');
                                                                    $("#retencionF2").focus();
                                                                } else {
                                                                    //                                                                        reenviar(data.id);
                                                                    window.open("generarPDF_1.php?hoja=A5&id=" + data.id, '_blank');
                                                                    location.reload();
                                                                }
                                                            });
                                                    } else {
                                                        if (data.estado == 7) {
                                                            alertify.alert("Factura Guardada  No Autorizada", function () {
                                                                location.reload();
                                                            });
                                                        }
                                                    }
                                                }
                                            } else {
                                                if ($("#tipo_venta").val() == "NOTA") {
                                                    if (val != 0) {
                                                        alertify.alert("Nota Venta Guardada correctamente", function () {
                                                            location.reload();
                                                        });
                                                    }
                                                }
                                            }
                                        }
                                    });

                                }
                            }
                        }
                        //                        }
                    }
                }
            }
        });
    }
}

//
//function guardar_factura_temporal() {
//    var tam = jQuery("#list").jqGrid("getRowData");
//
//    if ($("#num_factura").val() == "") {
//        $("#num_factura").focus();
//        alertify.error("Ingrese nÃºmero de la factura");
//    } else {
//        var num_factu = ("001" + "-" + "001" + "-" + $("#num_factura").val());
//        $.ajax({
//            type: "POST",
//            url: "comparar_num_venta.php",
//            data: "num_fac=" + num_factu,
//            success: function(data) {
//                var val = data;
//                if (val != 0) {
//                    $("#num_factura").val("");
//                    $("#num_factura").focus();
//                    alertify.error("Error... La factura ya existe, favor verificar el nÃºmero que corresponda");
//                    var res1 = parseInt(val.substr(8, 16));
//                    res1 = res1 + 1;
//                    
//                    $("#num_factura").val(res1);
//                    var a1 = autocompletar(res1);
//                    var validado = a1 + "" + res1;
//                    $("#num_factura").val(validado);
//                } else {
//                    if ($("#ruc_ci").val() == "") {
//                        var a = autocompletar($("#num_factura").val());
//                        $("#num_factura").val(a + "" + $("#num_factura").val());
//                        $("#ruc_ci").focus();
//                        alertify.error("Indique un cliente");
//                    } else {
//                        if ($("#nombre_cliente").val() == "") {
//                            $("#nombre_cliente").focus();
//                            alertify.error("Nombres del cliente");
//                        } else {
//                            if ($("#tipo_precio").val() == "") {
//                                $("#tipo_precio").focus();
//                                alertify.alert("Seleccione un tipo de precio");
//                            } else {
//                                if (tam.length == 0) {
//                                    $("#codigo_barras").focus();
//                                    alertify.error("Error... Ingrese productos a la factura");
//                                } else {
//                                    if ($("#formas").val() == "Credito" && $("#meses").val() == "") {
//                                        $("#meses").focus();
//                                        alertify.error("Meses a diferir");
//                                    } else {
//                                        $("#btnGuardar").attr("disabled", true);
//                                        var v1 = new Array();
//                                        var v2 = new Array();
//                                        var v3 = new Array();
//                                        var v4 = new Array();
//                                        var v5 = new Array();
//                                        var v6 = new Array();
//
//                                        var string_v1 = "";
//                                        var string_v2 = "";
//                                        var string_v3 = "";
//                                        var string_v4 = "";
//                                        var string_v5 = "";
//                                        var string_v6 = "";
//                                        var fil = jQuery("#list").jqGrid("getRowData");
//                                        
//                                        for (var i = 0; i < fil.length; i++) {
//                                            var datos = fil[i];
//                                            v1[i] = datos['cod_producto'];
//                                            v2[i] = datos['cantidad'];
//                                            v3[i] = datos['precio_u'];
//                                            v4[i] = datos['descuento'];
//                                            v5[i] = datos['total'];
//                                            v6[i] = datos['pendiente'];
//                                        }
//                                        
//                                        for (i = 0; i < fil.length; i++) {
//                                            string_v1 = string_v1 + "|" + v1[i];
//                                            string_v2 = string_v2 + "|" + v2[i];
//                                            string_v3 = string_v3 + "|" + v3[i];
//                                            string_v4 = string_v4 + "|" + v4[i];
//                                            string_v5 = string_v5 + "|" + v5[i];
//                                            string_v6 = string_v6 + "|" + v6[i];
//                                        }
//                                        
//                                        var a = autocompletar($("#num_factura").val());
//                                        var seriee = ("001" + "-" + "001" + "-" + a + "" + $("#num_factura").val());
//                                        $.ajax({
//                                            type: "POST",
//                                            url: "guardar_factura_venta_temporal.php",
//                                            data: "id_proveedor=" + $("#id_proveedor").val() + "&comprobante=" + $("#comprobante").val() + "&num_factura=" + seriee + "&fecha_actual=" + $("#fecha_actual").val() + "&hora_actual=" + $("#hora_actual").val() + "&proforma=" + $("#proforma").val() + "&cancelacion=" + $("#cancelacion").val() + "&tipo_precio=" + $("#tipo_precio").val() + "&formas=" + $("#formas").val() + "&adelanto=" + $("#adelanto").val() + "&meses=" + $("#meses").val() + "&autorizacion=" + $("#autorizacion").val()+ "&fecha_auto=" + $("#fecha_auto").val()+ "&fecha_caducidad=" + $("#fecha_caducidad").val() + "&tarifa0=" + $("#total_p").val() + "&tarifa12=" + $("#total_p2").val() + "&iva=" + $("#iva").val() + "&desc=" + $("#desc").val() + "&tot=" + $("#tot").val() + "&ruc_ci=" + $("#ruc_ci").val() + "&nombre_cliente=" + $("#nombre_cliente").val() + "&direccion_cliente=" + $("#direccion_cliente").val() + "&telefono_cliente=" + $("#telefono_cliente").val() + "&correo=" + $("#correo").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5+ "&campo6=" + string_v6+ "&tipo_venta=" + $("#tipo_venta").val() + "&tarjetas=" + $("#tarjetas").val(),
//                                            success: function(data) {
//                                                var val = data;
//                                                if($("#tipo_venta").val() == "FACTURA") {
//                                                   if (val != 0) {
//
//                                                        alertify.alert("Factura Temporal Guardada Correctamente", function(){
//                                                            window.open("../../reportes/factura_venta.php?hoja=A4&id="+val,'_blank');    
//                                                            location.reload();
//                                                        }); 
//                                                        /*alertify.confirm("Â¿Desea ingresar retenciones?",
//                                                        function(e){ //callbak al pulsar botón positivo  
//                                                           // var current_tab = $('.tab-pane.active').attr('id');
//                                                            //if(current_tab=="tab_1"){
//                                                                if(e){
//                                                                    $("#id_factura_venta").val(val);
//                                                                    $('.nav-tabs a[href="#tab_2"]').tab('show')
//                                                                    //$("#tab_1").removeClass('active');
//                                                                    //$("#tab_2").addClass('active');
//                                                                }else{
//                                                                    window.open("../../reportes/factura_venta.php?hoja=A4&id="+val,'_blank');    
//                                                                    location.reload();
//                                                                }
//                                                            //}
//                                                        }//, 
//                                                        //function(){ //callbak al pulsar botón negativo
//                                                            //window.open("../../reportes/factura_cayambe.php?hoja=A4&id="+val,'_blank');    
//                                                            //location.reload();
//                                                        //}
//                                                        ); */
//                                                    }
//                                                } else {
//                                                    if($("#tipo_venta").val() == "NOTA") {
//                                                        if (val != 0) {
//                                                            alertify.alert("Nota Venta Guardada correctamente", function(){
//                                                                // var myWindow = window.open("../../reportes/nota_venta.php?hoja=A4&id="+val,'_blank');
//                                                                // myWindow.focus();
//                                                                // myWindow.print();          
//                                                                location.reload();
//                                                            });
//                                                        }
//                                                    }
//                                                }
//                                            }
//                                        });
//                                    }
//                                }
//                            }
//                        } 
//                    } 
//                }
//            }
//        });
//    }
//}

function guardar_imprimir_factura() {
    var tam = jQuery("#list").jqGrid("getRowData");

    if ($("#num_factura").val() == "") {
        $("#num_factura").focus();
        alertify.error("Ingrese número de la factura");
    } else {
        var num_factu = (num_serie_liq + "-" + $("#num_factura").val());
        $.ajax({
            type: "POST",
            url: "comparar_num_venta.php",
            data: "num_fac=" + num_factu,
            success: function (data) {
                var val = data;
                if (val != 0) {
                    $("#num_factura").val("");
                    $("#num_factura").focus();
                    alertify.error("Error... La factura ya existe, favor verificar el número que corresponda");
                    var res1 = parseInt(val.substr(8, 16));
                    res1 = res1 + 1;

                    $("#num_factura").val(res1);
                    var a1 = autocompletar(res1);
                    var validado = a1 + "" + res1;
                    $("#num_factura").val(validado);
                } else {
                    if ($("#ruc_ci").val() == "") {
                        var a = autocompletar($("#num_factura").val());
                        $("#num_factura").val(a + "" + $("#num_factura").val());
                        $("#ruc_ci").focus();
                        alertify.error("Indique un cliente");
                    } else {
                        if ($("#nombre_cliente").val() == "") {
                            $("#nombre_cliente").focus();
                            alertify.error("Nombres del cliente");
                        } else {
                            if ($("#tipo_precio_venta").val() == "") {
                                $("#tipo_precio_venta").focus();
                                alertify.alert("Seleccione un tipo de precio");
                            } else {
                                if (tam.length == 0) {
                                    $("#codigo_barras").focus();
                                    alertify.error("Error... Ingrese productos a la factura");
                                } else {
                                    if ($("#formaspago").val() == "Credito" && $("#meses").val() == "") {

                                        $("#fecha_dias").focus();
                                        alertify.error("Selecciones fecha");
                                    } else {
                                        $("#btnGuardar").attr("disabled", true);
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

                                        var fil = jQuery("#list").jqGrid("getRowData");
                                        for (var i = 0; i < fil.length; i++) {
                                            var datos = fil[i];
                                            v1[i] = datos['cod_producto'];
                                            v2[i] = datos['cantidad'];
                                            v3[i] = datos['precio_u'];
                                            v4[i] = datos['descuento'];
                                            v5[i] = datos['total'];
                                            v6[i] = datos['pendiente'];
                                        }

                                        for (i = 0; i < fil.length; i++) {
                                            string_v1 = string_v1 + "|" + v1[i];
                                            string_v2 = string_v2 + "|" + v2[i];
                                            string_v3 = string_v3 + "|" + v3[i];
                                            string_v4 = string_v4 + "|" + v4[i];
                                            string_v5 = string_v5 + "|" + v5[i];
                                            string_v6 = string_v6 + "|" + v6[i];
                                        }

                                        var a = autocompletar($("#num_factura").val());
                                        var seriee = (num_serie_liq + "-" + a + "" + $("#num_factura").val());
                                        $.ajax({
                                            type: "POST",
                                            url: "guardar_liquidacion_compra.php",
                                            data: "id_proveedor=" + $("#id_proveedor").val() + "&comprobante=" + $("#comprobante").val() + "&num_factura=" + seriee + "&fecha_actual=" + $("#fecha_actual").val() + "&hora_actual=" + $("#hora_actual").val() + "&proforma=" + $("#proforma").val() + "&cancelacion=" + $("#cancelacion").val() + "&tipo_precio=" + $("#tipo_precio_venta").val() + "&formaspago=" + $("#formaspago").val() + "&adelanto=" + $("#adelanto").val() + "&meses=" + $("#meses").val() + "&autorizacion=" + $("#autorizacion").val() + "&fecha_auto=" + $("#fecha_auto").val() + "&fecha_caducidad=" + $("#fecha_caducidad").val() + "&tarifa0=" + $("#total_p").val() + "&tarifa12=" + $("#total_p2").val() + "&iva=" + $("#iva").val() + "&desc=" + $("#desc").val() + "&tot=" + $("#tot").val() + "&ruc_ci=" + $("#ruc_ci").val() + "&nombre_cliente=" + $("#nombre_cliente").val() + "&direccion_cliente=" + $("#direccion_cliente").val() + "&telefono_cliente=" + $("#telefono_cliente").val() + "&correo=" + $("#correo").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&campo6=" + string_v6 + "&tipo_venta=" + $("#tipo_venta").val(),
                                            success: function (data) {
                                                var val = data;
                                                if ($("#tipo_venta").val() == "FACTURA") {
                                                    if (val != 0) {
                                                        alertify.alert("Factura Guardada correctamente", function () {
                                                            var myWindow = window.open("../../reportes/liquidacion_compra.php?hoja=A4&id=" + val, '_blank');
                                                            myWindow.focus();
                                                            myWindow.print();
                                                            location.reload();
                                                        });
                                                    }
                                                } else {
                                                    if ($("#tipo_venta").val() == "NOTA") {
                                                        if (val != 0) {
                                                            alertify.alert("Nota Venta Guardada correctamente", function () {
                                                                var myWindow = window.open("../../reportes/nota_venta.php?hoja=A4&id=" + val, '_blank');
                                                                myWindow.focus();
                                                                myWindow.print();
                                                                location.reload();
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
        });
    }
}
//
//function modificar_factura() {
//    var tam = jQuery("#list").jqGrid("getRowData");
//
//    if ($("#id_factura_venta").val() == "") {
//        alertify.error("Seleccione una factura");
//        $("#tipo_busqueda").dialog("open"); 
//    } else {
//        if ($("#num_factura").val() == "") {
//            $("#num_factura").focus();
//            alertify.error("Ingrese nÃºmero de la factura");
//        } else {
//            if ($("#ruc_ci").val() == "") {
//                var a = autocompletar($("#num_factura").val());
//                $("#num_factura").val(a + "" + $("#num_factura").val());
//                $("#ruc_ci").focus();
//                alertify.error("Indique un cliente");
//            } else {
//                if ($("#nombre_cliente").val() == "") {
//                    $("#nombre_cliente").focus();
//                    alertify.error("Nombres del cliente");
//                } else {
//                    if ($("#tipo_precio").val() == "") {
//                        $("#tipo_precio").focus();
//                        alertify.alert("Seleccione un tipo de precio");
//                    } else {
//                        if (tam.length == 0) {
//                            $("#codigo").focus();
//                            alertify.error("Error... Ingrese productos a la factura");
//                        } else {
//                            if ($("#formas").val() == "Credito" && $("#meses").val() == "") {
//                                $("#meses").focus();
//                                alertify.error("Meses a diferir");
//                            } else {
//                                $("#btnModificar").attr("disabled", true);
//                                var v1 = new Array();
//                                var v2 = new Array();
//                                var v3 = new Array();
//                                var v4 = new Array();
//                                var v5 = new Array();
//                                var v6 = new Array();
//
//                                var string_v1 = "";
//                                var string_v2 = "";
//                                var string_v3 = "";
//                                var string_v4 = "";
//                                var string_v5 = "";
//                                var string_v6 = "";
//                                var fil = jQuery("#list").jqGrid("getRowData");
//
//                                for (var i = 0; i < fil.length; i++) {
//                                    var datos = fil[i];
//                                    v1[i] = datos['cod_producto'];
//                                    v2[i] = datos['cantidad'];
//                                    v3[i] = datos['precio_u'];
//                                    v4[i] = datos['descuento'];
//                                    v5[i] = datos['total'];
//                                    v6[i] = datos['pendiente'];
//                                }
//                                
//                                for (i = 0; i < fil.length; i++) {
//                                    string_v1 = string_v1 + "|" + v1[i];
//                                    string_v2 = string_v2 + "|" + v2[i];
//                                    string_v3 = string_v3 + "|" + v3[i];
//                                    string_v4 = string_v4 + "|" + v4[i];
//                                    string_v5 = string_v5 + "|" + v5[i];
//                                    string_v6 = string_v6 + "|" + v6[i];
//                                }
//
//                                var a = autocompletar($("#num_factura").val());
//                                var seriee = ("001" + "-" + "001" + "-" + a + "" + $("#num_factura").val());
//                                
//                                $.ajax({
//                                    type: "POST",
//                                    url: "modificar_factura_venta.php",
//                                    data: "id_factura_venta=" + $("#id_factura_venta").val() + "&id_proveedor=" + $("#id_proveedor").val() + "&comprobante=" + $("#comprobante").val() + "&num_factura=" + seriee + "&fecha_actual=" + $("#fecha_actual").val() + "&hora_actual=" + $("#hora_actual").val() + "&proforma=" + $("#proforma").val() + "&cancelacion=" + $("#cancelacion").val() + "&tipo_precio=" + $("#tipo_precio").val() + "&formas=" + $("#formas").val() + "&adelanto=" + $("#adelanto").val() + "&meses=" + $("#meses").val() + "&autorizacion=" + $("#autorizacion").val()+ "&fecha_auto=" + $("#fecha_auto").val()+ "&fecha_caducidad=" + $("#fecha_caducidad").val() + "&tarifa0=" + $("#total_p").val() + "&tarifa12=" + $("#total_p2").val() + "&iva=" + $("#iva").val() + "&desc=" + $("#desc").val() + "&tot=" + $("#tot").val() + "&ruc_ci=" + $("#ruc_ci").val() + "&nombre_cliente=" + $("#nombre_cliente").val() + "&direccion_cliente=" + $("#direccion_cliente").val() + "&telefono_cliente=" + $("#telefono_cliente").val() + "&correo=" + $("#correo").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5+ "&campo6=" + string_v6+ "&tipo_venta=" + $("#tipo_venta").val(),
//                                    success: function(data) {
//                                        var val = data;
//                                        if($("#tipo_venta").val() == "FACTURA") {
//                                           if (val != 0) {
//                                                alertify.alert("Factura Modificada correctamente", function(){
//                                                    var myWindow = window.open("../../reportes/factura_venta.php?hoja=A4&id="+val,'_blank');
//                                                    myWindow.focus();
//                                                    myWindow.print();                                                                      
//                                                    location.reload();
//                                                });
//                                            }
//                                        } else {
//                                            if($("#tipo_venta").val() == "NOTA") {
//                                                if (val != 0) {
//                                                    alertify.alert("Nota Venta Modifcada correctamente", function(){
//                                                        var myWindow = window.open("../reportes_sistema/nota_venta.php?hoja=A4&id="+val,'_blank');
//                                                        myWindow.focus();
//                                                        myWindow.print();          
//                                                        location.reload();
//                                                    });
//                                                }
//                                            }
//                                        }
//                                    }
//                                });
//                            }
//                        }
//                    }
//                } 
//            }           
//        }
//    }    
//}

function flecha_atras() {
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "liquidacion_compra" + "&id_tabla=" + "id_liquidacion_compra" + "&tipo=" + 1,
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#comprobante").val(val);
                var valor = $("#comprobante").val();
                // llamar factura venta
                $("#btnGuardar").attr("disabled", true);
                //                $("#btnGuardarTemporal").attr("disabled", true);
                // $("#num_factura").attr("disabled", true);
                $("#id_proveedor").val("");
                $("#ruc_ci").val("");
                $("#nombre_cliente").val("");
                $("#telefono_cliente").val("");
                $("#correo").val("");

                $("#codigo_barras").attr("disabled", true);
                $("#codigo").attr("disabled", true);
                $("#producto").attr("disabled", true);
                $("#cantidad").attr("disabled", true);
                $("#p_venta").attr("disabled", true);
                $("#descuento").attr("disabled", true);

                $("#formaspago").attr("disabled", true);
                $("#tipo_venta").val("FACTURA");

                $("#estado h3").remove();
                $("#formaspago").val("Contado");
                $("#adelanto").val("");
                $("#meses").val("");


                $('#cuotas').children().remove().end();

                $("#list").jqGrid("clearGridData", true);
                $("#total_p").val("0.000");
                $("#total_p2").val("0.000");
                $("#iva").val("0.000");
                $("#desc").val("0.000");
                $("#tot").val("0.000");
                $("#total_px").val("0.000");
                $("#total_p2x").val("0.000");
                $("#ivax").val("0.000");
                $("#descx").val("0.000");
                $("#totx").val("0.000");

                $.getJSON('retornar_liquidacion_compra.php?com=' + valor, function (data) {
                    var tama = data.length;
                    t = data[23];
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 25) {
                            $("#id_liquidacion_compra").val(data[i]);
                            $("#fecha_actual").val(data[i + 1]);
                            $("#hora_actual").val(data[i + 2]);
                            $("#digitador").val(data[i + 3] + " " + data[i + 4]);
                            var num = data[i + 5];
                            var res = num.substr(8, 20)
                            $("#num_factura").val(res);
                            $("#id_proveedor").val(data[i + 6]);
                            $("#ruc_ci").val(data[i + 7]);
                            $("#nombre_cliente").val(data[i + 8]);
                            $("#direccion_cliente").val(data[i + 9]);
                            $("#telefono_cliente").val(data[i + 10]);
                            $("#correo").val(data[i + 11]);
                            $("#autorizacion").val(data[i + 12]);
                            $("#fecha_auto").val(data[i + 13]);
                            $("#fecha_caducidad").val(data[i + 14]);
                            $("#cancelacion").val(data[i + 15]);
                            $("#tipo_precio_venta").val(data[i + 16]);

                            if (data[i + 17] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulada"));
                                $("#estado h3").css("color", "red");
                                $("#btnAnular").attr("disabled", "disabled");
                                $("#btnModificar").attr("disabled", true);
                            } else {
                                $("#estado h3").remove();
                                $("#btnAnular").attr("disabled", "disabled");
                                $("#btnAnular").attr("disabled", false);
                                $("#btnModificar").attr("disabled", false);
                                if (data[i + 23] == '0') {
                                    $("#btnModificar").attr("disabled", false);
                                    $("#btnGuardar").attr("disabled", false);
                                    $("#codigo_barras").attr("disabled", false);
                                    $("#codigo").attr("disabled", false);
                                    $("#producto").attr("disabled", false);
                                    $("#cantidad").attr("disabled", false);
                                    $("#p_venta").attr("disabled", false);
                                    $("#descuento").attr("disabled", false);

                                    $("#formaspago").attr("disabled", false);
                                } else if (data[i + 23] == '1') {
                                    $("#btnModificar").attr("disabled", "disabled");
                                }
                            }

                            $("#total_p").val(data[i + 18]);
                            $("#total_p2").val(data[i + 19]);
                            $("#sub").val(parseFloat(data[i + 18]) + parseFloat(data[i + 19]));
                            $("#iva").val(data[i + 20]);
                            $("#desc").val(data[i + 21]);
                            $("#tot").val(data[i + 22]);
                            $("#total_px").val(parseFloat(data[i + 18]).toFixed(2));
                            $("#total_p2x").val(parseFloat(data[i + 19]).toFixed(2));
                            $("#subx").val((parseFloat(data[i + 18]) + parseFloat(data[i + 19])).toFixed(2));
                            $("#ivax").val(parseFloat(data[i + 20]).toFixed(2));
                            $("#descx").val(parseFloat(data[i + 21]).toFixed(2));
                            $("#totx").val(parseFloat(data[i + 22]).toFixed(2));
                            $("#num_liquidacion").val(data[i + 24]);
                        }
                        //                        volver_rf();
                        //                        volver_ri();
                    }
                });



                $.getJSON('retornar_liquidacion_compra2.php?com=' + valor, function (data) {
                    var tama = data.length;
                    var descuento = 0;
                    var total = 0;
                    var su = 0;
                    var precio = 0;
                    var multi = 0;
                    var flotante = 0;
                    var resultado = 0;
                    var suma_total = 0;

                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 10) {
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
                                pendiente: data[i + 8],
                                incluye: data[i + 9]
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                            suma_total = suma_total + parseFloat(data[i + 3]);
                        }
                        var fila = jQuery("#list").jqGrid("getRowData");
                        $("#items").val(fila.length);
                        $("#num").val(suma_total);
                    }
                });
                // Fin
            } else {
                alertify.alert("No hay mÃ¡s registros posteriores!!");
            }
        }
    });
}
function enviarCorreos() {

    $.ajax({
        type: "POST",
        url: "enviar_correo.php",
        data: "id_liquidacion_compra=" + $("#id_liquidacion_compra").val(),
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
function comprobar2reten() {
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
            var calculoserviva = $("#calculobieniva").val() * toFixedDown((calculoIVA / 100), 3);
            if (filas.length == 0) {

                var datarow = {

                    base_imponible: parseFloat(calculoserviva).toFixed(2),
                    impuesto: impuesto,
                    porcent_reten: $("#porcent_iva").val(),
                    valor_retenido: $("#calculoRetencionI").val(),
                    id_retenciones_ser: xsid_iva

                };
                su = jQuery("#listPagoreten").jqGrid('addRowData', parseFloat(calculoserviva).toFixed(2), datarow);
                //                                limpiar_campos();
            } else {
                for (var i = 0; i < filas.length; i++) {
                    var id = filas[i];
                    repe = 1;
                }
                if (repe != 1) {

                    datarow = {
                        base_imponible: parseFloat(calculoserviva).toFixed(2),
                        impuesto: impuesto,
                        porcent_reten: $("#porcent_iva").val(),
                        valor_retenido: $("#calculoRetencionI").val(),
                        id_retenciones_ser: xsid_iva
                    };
                    su = jQuery("#listPagoreten").jqGrid('addRowData', parseFloat(calculoserviva).toFixed(2), datarow);
                    //                                    limpiar_campos();
                } else {

                    datarow = {
                        base_imponible: parseFloat(calculoserviva).toFixed(2),
                        impuesto: impuesto,
                        porcent_reten: $("#porcent_iva").val(),
                        valor_retenido: $("#calculoRetencionI").val(),
                        id_retenciones_ser: xsid_iva
                    };
                    su = jQuery("#listPagoreten").jqGrid('addRowData', parseFloat(calculoserviva).toFixed(2), datarow);
                    //                limpiar_campos();
                }
            }

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
            var calculoservivaS = $("#calculoservivas").val() * toFixedDown((calculoIVA / 100), 3);

            if (filas.length == 0) {
                var datarow = {
                    base_imponible: parseFloat(calculoservivaS).toFixed(2),
                    impuesto: impuesto,
                    porcent_reten: $("#porcent_ivas").val(),
                    valor_retenido: $("#calculoRetencionIs").val(),
                    id_retenciones_ser: xsid_ivas

                };
                su = jQuery("#listPagoreten").jqGrid('addRowData', parseFloat(calculoservivaS).toFixed(2), datarow);
                //                                limpiar_campos();
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
                    //                limpiar_campos();
                }
            }

        }
        $('#retencionI1s').prop('checked', true);
    }

}
/////////////////////////////////////////////////
function flecha_siguiente() {
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "liquidacion_compra" + "&id_tabla=" + "id_liquidacion_compra" + "&tipo=" + 2,
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#comprobante").val(val);
                var valor = $("#comprobante").val();
                // llamar factura venta
                $("#btnGuardar").attr("disabled", true);
                //                $("#btnGuardarTemporal").attr("disabled", true);

                // $("#num_factura").attr("disabled", true);
                $("#id_proveedor").val("");
                $("#ruc_ci").val("");
                $("#nombre_cliente").val("");
                $("#telefono_cliente").val("");
                $("#correo").val("");

                $("#codigo_barras").attr("disabled", true);
                $("#codigo").attr("disabled", true);
                $("#producto").attr("disabled", true);
                $("#cantidad").attr("disabled", true);
                $("#p_venta").attr("disabled", true);
                $("#descuento").attr("disabled", true);

                $("#formaspago").attr("disabled", true);
                $("#tipo_venta").val("FACTURA");

                $("#estado h3").remove();
                $("#formaspago").val("Contado");
                $("#adelanto").val("");
                $("#meses").val("");
                $('#cuotas').children().remove().end();

                $("#list").jqGrid("clearGridData", true);
                $("#total_p").val("0.000");
                $("#total_p2").val("0.000");
                $("#iva").val("0.000");
                $("#desc").val("0.000");
                $("#tot").val("0.000");
                $("#total_px").val("0.000");
                $("#total_p2x").val("0.000");
                $("#ivax").val("0.000");
                $("#descx").val("0.000");
                $("#totx").val("0.000");

                $.getJSON('retornar_liquidacion_compra.php?com=' + valor, function (data) {
                    var tama = data.length;
                    t = data[23];
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 24) {
                            $("#id_liquidacion_compra").val(data[i]);
                            $("#fecha_auto").val(data[i + 1]);
                            $("#hora_actual").val(data[i + 2]);
                            $("#digitador").val(data[i + 3] + " " + data[i + 4]);
                            var num = data[i + 5];
                            var res = num.substr(8, 20)
                            $("#num_factura").val(res);
                            $("#id_proveedor").val(data[i + 6]);
                            $("#ruc_ci").val(data[i + 7]);
                            $("#nombre_cliente").val(data[i + 8]);
                            $("#direccion_cliente").val(data[i + 9]);
                            $("#telefono_cliente").val(data[i + 10]);
                            $("#correo").val(data[i + 11]);
                            $("#autorizacion").val(data[i + 12]);
                            $("#fecha_auto").val(data[i + 13]);
                            $("#fecha_caducidad").val(data[i + 14]);
                            $("#cancelacion").val(data[i + 15]);
                            $("#tipo_precio_venta").val(data[i + 16]);

                            if (data[i + 17] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulada"));
                                $("#estado h3").css("color", "red");
                                $("#btnAnular").attr("disabled", "disabled");
                                $("#btnModificar").attr("disabled", true);
                            } else {
                                $("#estado h3").remove();
                                $("#btnAnular").attr("disabled", "disabled");
                                $("#btnAnular").attr("disabled", false);
                                $("#btnModificar").attr("disabled", false);
                                if (data[i + 23] == '0') {
                                    $("#btnModificar").attr("disabled", false);
                                    $("#btnGuardar").attr("disabled", false);
                                    $("#codigo_barras").attr("disabled", false);
                                    $("#codigo").attr("disabled", false);
                                    $("#producto").attr("disabled", false);
                                    $("#cantidad").attr("disabled", false);
                                    $("#p_venta").attr("disabled", false);
                                    $("#descuento").attr("disabled", false);

                                    $("#formaspago").attr("disabled", false);
                                } else if (data[i + 23] == '1') {
                                    $("#btnModificar").attr("disabled", "disabled");
                                }
                            }

                            $("#total_p").val(data[i + 18]);
                            $("#total_p2").val(data[i + 19]);
                            $("#sub").val(parseFloat(data[i + 18]) + parseFloat(data[i + 19]));
                            $("#iva").val(data[i + 20]);
                            $("#desc").val(data[i + 21]);
                            $("#tot").val(data[i + 22]);
                            $("#total_px").val(parseFloat(data[i + 18]).toFixed(2));
                            $("#total_p2x").val(parseFloat(data[i + 19]).toFixed(2));
                            $("#subx").val((parseFloat(data[i + 18]) + parseFloat(data[i + 19])).toFixed(2));
                            $("#ivax").val(parseFloat(data[i + 20]).toFixed(2));
                            $("#descx").val(parseFloat(data[i + 21]).toFixed(2));
                            $("#totx").val(parseFloat(data[i + 22]).toFixed(2));
                        }
                        //                        volver_rf();
                        //                        volver_ri();
                    }
                });


                $.getJSON('retornar_liquidacion_compra2.php?com=' + valor, function (data) {
                    var tama = data.length;
                    var descuento = 0;
                    var total = 0;
                    var su = 0;
                    var precio = 0;
                    var multi = 0;
                    var flotante = 0;
                    var resultado = 0;
                    var suma_total = 0;

                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 10) {
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
                                pendiente: data[i + 8],
                                incluye: data[i + 9]
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                            suma_total = suma_total + parseFloat(data[i + 3]);
                        }
                        var fila = jQuery("#list").jqGrid("getRowData");
                        $("#items").val(fila.length);
                        $("#num").val(suma_total.toFixed(2));
                    }
                });
                // Fin
            } else {
                if ($("#id_liquidacion_compra").val() != "") {
                    $("#comprobante").val($("#comprobante").val());
                }
                alertify.alert("No hay más registros superiores!!");
            }
        }
    });
}
function enviar_correo() {



}

function limpiar_campo() {
    if ($("#ruc_ci").val() == "") {
        $("#id_proveedor").val("");
        $("#nombre_cliente").val("");
        $("#direccion_cliente").val("");
        $("#telefono_cliente").val("");
        $("#correo").val("");
        $("#nombre_director").val("");
        $("#direccion_cliente").attr("disabled", "disabled");
        //       $("#telefono_cliente").attr("disabled", "disabled");
        //        $("#correo").attr("disabled", "disabled");
    }
}

function limpiar_campo2() {
    if ($("#nombre_cliente").val() == "") {
        $("#id_proveedor").val("");
        $("#ruc_ci").val("");
        $("#direccion_cliente").val("");
        $("#telefono_cliente").val("");
        $("#correo").val("");
        $("#nombre_director").val("");
        $("#direccion_cliente").attr("disabled", "disabled");
        //        $("#telefono_cliente").attr("disabled", "disabled");
        //        $("#correo").attr("disabled", "disabled");
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
        $("#disponibles").val("");
        $("#iva_producto").val("");
        $("#carga_series").val("");
        $("#des").val("");
        $("#incluye").val("");
        $("#inventar").val("");
    }
}

function limpiar_campo4() {
    if ($("#producto").val() == "") {
        $("#codigo_barras").val("");
        $("#cod_producto").val("");
        $("#codigo").val("");
        $("#cantidad").val("");
        $("#p_venta").val("");
        $("#descuento").val("");
        $("#disponibles").val("");
        $("#iva_producto").val("");
        $("#carga_series").val("");
        $("#des").val("");
        $("#incluye").val("");
        $("#inventar").val("");
    }
}

function limpiar_factura() {
    location.reload();
}

function anular_factura() {
    $("#clave_permiso").dialog("open");
}
function ingresar_cambio() {
    if ($("#id_liquidacion_compra").val() == "") {


        if ($("#formaspago").val() == "Contado") {
            $("#valor_recibo").select();
            $("#valor_recibo").focus();
            var tam = jQuery("#list").jqGrid("getRowData");
            if (tam.length == 0) {
                $("#codigo_barras").focus();
                alertify.error("Error... Ingrese productos a la factura");
            } else {
                $("#valor_recibo").select();

                $("#valor_recibo").select();
                $("#valor_recibo").focus();
                $("#valor_cambioid").dialog("open");
                $("#valor_recibo").select();
                $("#valor_recibo").focus();
            }

            $("#total_venta").val($("#totx").val());
        } else {

            if ($("#formas").val() == "Contado") {

                $("#valor_recibo").select();

                $("#valor_recibo").focus();
                //                       $('#valor_tarjetaid').show();
                //                       $('#resultado_tar_totalid').show();
                //                       $('#calculo_porcentajeid').show();

                $("#valor_cambioid").dialog("open");
                $("#valor_recibo").select();

                $("#valor_recibo").focus();
                $("#total_venta").val($("#totx").val());
            } else {
                guardar_factura();
            }
        }
    } else {
        alertify.error("Generar Nueva Factura");
    }
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

function aceptar() {
    var v1 = new Array();
    var v2 = new Array();
    var string_v1 = "";
    var string_v2 = "";
    var fil = jQuery("#list").jqGrid("getRowData");

    for (var i = 0; i < fil.length; i++) {
        var datos = fil[i];
        v1[i] = datos['cod_producto'];
        v2[i] = datos['cantidad'];
    }
    for (i = 0; i < fil.length; i++) {
        string_v1 = string_v1 + "|" + v1[i];
        string_v2 = string_v2 + "|" + v2[i];
    }
    $.ajax({
        type: "POST",
        url: "anular_liquidacion_compra.php",
        data: "comprobante=" + $("#comprobante").val() + "&tipo_venta=" + $("#tipo_venta").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&fecha_anulacion=" + $("#fecha_actual").val(),
        success: function (data) {
            var val = data;
            if (val == 1) {
                alertify.alert("Factura Anulada Correctamente", function () {
                    location.reload();
                });
            }
        }
    });
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
function cancelar_cambio() {
    $("#valor_cambioid").dialog("close");

}
function guardar_retenciones_factura_compra() {

    var tam = jQuery("#listPagoreten").jqGrid("getRowData");
    var y = document.getElementById("tipoRetencionesI").selectedIndex;
    if (y == '0') {
        var y = document.getElementById("tipoRetencionesIs").selectedIndex;
    }

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
        var num_retencion = (num_serie_liq + "-" + $("#serie_retencion").val());
        $.ajax({
            type: "POST",
            url: "comparar_num_retencion.php",
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
                                var seriee = (num_serie_liq + "-" + a + "" + $("#serie_retencion").val());
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
                                if ($("#calculoRetencionI").val() == '0.000') {
                                    var calculoretencionii = $("#calculoRetencionIs").val();
                                }
                                $("#btnGuardarRetenciones").attr("disabled", true);
                                if (document.getElementById('retencionF_prima').checked == true) {
                                    if ($("#calculobienprima2").val() != "") {
                                        guardar_retenciones_factura_compra_directo_c();
                                    }
                                }
                                //                                conaole.log("si vino");
                                $.ajax({
                                    type: "POST",
                                    url: "guardar_ret_fuente_fact_compra.php",
                                    data: "id_factura=" + $("#id_liquidacion_compra").val() + "&id_retencion_fuente=" + x + "&fecha_actual=" + $("#fecha_actual").val() + "&valor_factura=" + $("#sub").val() + "&iva_factura=" + $("#iva").val() + "&valor_retencion=" + $("#calculoRetencionF").val() + "&autorizacion_ret=" + $("#autorizacion_retencion").val() + "&serie_retencion=" + seriee + "&porcent_reten=" + $("#porcent_reten").val() + "&id_retencion_iva=" + y + "&valor_facturaiva=" + $("#tot").val() + "&valor_retencioni=" + calculoretencionii + "&valor_seleccion_iva=" + xx + "&porcent_iva=" + $("#porcent_iva").val() + "&id_retencion_fuentes=" + xs + "&valor_retencions=" + $("#calculoRetencionFS").val() + "&porcent_retens=" + $("#porcent_retens").val() + "&valor_seleccion_si_no=" + xxs + "&campo1reten=" + string_v1 + "&campo2reten=" + string_v2 + "&campo3reten=" + string_v3 + "&campo4reten=" + string_v4 + "&campo5reten=" + string_v5 + "&campo6reten=" + string_v6,
                                    dataType: "json",
                                    success: function (data) {
                                        var val = data;
                                        if (data.estado == 2) {
                                            window.open("generarPDFReten.php?hoja=A5&id=" + data.id, '_blank');
                                            alertify.alert("AUTORIZADO",
                                                function (e) {
                                                    $("#btnGuardarRetenciones").attr("disabled", false);
                                                    if (e) {
                                                        reenviar(data.id);

                                                        location.reload();
                                                    } else {
                                                        reenviar(data.id);
                                                        location.reload();
                                                    }
                                                });

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

function buscar_servicio_producto() {
    var mora = 0;
    if (document.getElementById('retencionF_prima').checked == true) {
        $.ajax({
            type: "POST",
            url: "buscar_bienservicio_producto_prima.php",
            data: "id=" + $("#id_liquidacion_compra").val(),
            success: function (data) {
                var valSUM = data;
                mora = Math.round((valSUM * 0.1) * 100) / 100;
                $("#calculoserv").val(mora);
            }
        });
    } else {
        $.ajax({
            type: "POST",
            url: "buscar_servicio_producto.php",
            data: "id=" + $("#id_liquidacion_compra").val(),
            success: function (data) {
                var valSUMs = data;
                $("#calculoserv").val(valSUMs);

            }
        });
    }
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
            $("#calculoRetencionFS").val(valor);
            $("#porcent_retens").val(calculoRET);
            $("#calculoRetencionFS").focus();
        }
    });
}
function buscar_bienservicio_producto() {
    var mora = 0;
    if (document.getElementById('retencionF_prima').checked == true) {
        $.ajax({
            type: "POST",
            url: "buscar_bienservicio_producto_prima.php",
            data: "id=" + $("#id_liquidacion_compra").val(),
            success: function (data) {
                var valSUM = data;
                mora = Math.round((valSUM * 0.1) * 100) / 100;
                $("#calculobien").val(mora);
            }
        });
    } else {
        $.ajax({
            type: "POST",
            url: "buscar_bienservicio_producto.php",
            data: "id=" + $("#id_liquidacion_compra").val(),
            success: function (data) {
                var valSUM = data;
                $("#calculobien").val(valSUM);
            }
        });
    }
}
function buscar_bienservicio_producto_iva() {

    $.ajax({
        type: "POST",
        url: "buscar_bienservicio_producto_iva.php",
        data: "id=" + $("#id_liquidacion_compra").val(),
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
        data: "id=" + $("#id_liquidacion_compra").val(),
        success: function (data) {
            var valSUMs = data;
            $("#calculoservivas").val(valSUMs);

        }
    });
}


function autocompletar() {
    var temp = "";
    var serie = $("#serie_retencion").val();
    for (var i = serie.length; i < 9; i++) {
        temp = temp + "0";
    }
    return temp;
}
function autocompletarsin() {
    var temp = "";
    var serie = $("#serie_sinretencion").val();
    for (var i = serie.length; i < 9; i++) {
        temp = temp + "0";
    }
    return temp;
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

function comprobar1() {
    if ($("#serie_retencion").val() == "") {
        $("#serie_retencion").focus();
        alertify.error("Ingrese número de Retenciòn");
    } else {
        if ($("#id_proveedor").val() == "" && $("#ruc_ci").val() != "") {
            nuevo_cliente();
        } else {
            if ($("#ruc_ci").val() == "") {
                $("#ruc_ci").focus();
                alertify.error("Indique un cliente");
            }
        }
    }
}
function inicio() {
    llenarCentrosCosto();
    buscar_servicio_producto();
    buscar_bienservicio_producto();
    buscar_servicio_iva();
    buscar_bienservicio_producto_iva();
    if ($("#num_oculto").val() == "") {
        $("#serie_retencion").val("");
    } else {
        var str = $("#num_oculto").val();
        var res = parseInt(str.substr(8, 16));
        res = res + 1;

        $("#serie_retencion").val(res);
        var a = autocompletar(res);
        var validado = a + "" + res;
        $("#serie_retencion").val(validado);
    }
    $('#transportistaguia').hide();

    $('#btnActualizartrans').hide();
    $('#trans').hide();
    $('#translabel').hide();
    $('#num_labelg').hide();
    $("#ruc_ci").change(function () {
        if ($("#id_cliente").val() == 1 || $("#id_cliente").val() == "") {
            if ($("#ruc_ci").val() != "") {
                // verificar si esxiste cliente
                $.ajax({
                    type: "POST",
                    url: "comparar_cedulas.php",
                    data: "cedula=" + $("#ruc_ci").val(),
                    success: function (data) {
                        var val = data;
                        if (val == 1) {
                            $("#ruc_ci").val("");
                            $("#ruc_ci").focus();
                            alertify.error("Error... El cliente esta registrado");
                        } else {
                            $("#direccion_cliente").val("");
                            $("#nombre_cliente").val("");
                            $("#telefono_cliente").val("");
                            $("#correo").val("");
                            $("#id_cliente").val("");
                            if ($("#ruc_ci").val().length != 10 && $("#ruc_ci").val().length !== 13) {
                                alertify.error("Error... Ingrese una Identificación valida");
                            } else {
                                $("#direccion_cliente").attr("");
                                $("#telefono_cliente").attr("");
                                $("#correo").attr("");
                                // validar cedula ruc
                                var numero = $("#ruc_ci").val();
                                var suma = 0;
                                var residuo = 0;
                                var pri = false;
                                var pub = false;
                                var nat = false;
                                var modulo = 11;
                                var p1;
                                var p2;
                                var p3;
                                var p4;
                                var p5;
                                var p6;
                                var p7;
                                var p8;
                                var p9;

                                /* Aqui almacenamos los digitos de la cedula en variables. */
                                var d1 = numero.substr(0, 1);
                                var d2 = numero.substr(1, 1);
                                var d3 = numero.substr(2, 1);
                                var d4 = numero.substr(3, 1);
                                var d5 = numero.substr(4, 1);
                                var d6 = numero.substr(5, 1);
                                var d7 = numero.substr(6, 1);
                                var d8 = numero.substr(7, 1);
                                var d9 = numero.substr(8, 1);
                                var d10 = numero.substr(9, 1);

                                /* El tercer digito es: */
                                /* 9 para sociedades privadas y extranjeros   */
                                /* 6 para sociedades publicas */
                                /* menor que 6 (0,1,2,3,4,5) para personas naturales */

                                if (d3 < 6) {
                                    nat = true;
                                    p1 = d1 * 2;
                                    if (p1 >= 10)
                                        p1 -= 9;
                                    p2 = d2 * 1;
                                    if (p2 >= 10)
                                        p2 -= 9;
                                    p3 = d3 * 2;
                                    if (p3 >= 10)
                                        p3 -= 9;
                                    p4 = d4 * 1;
                                    if (p4 >= 10)
                                        p4 -= 9;
                                    p5 = d5 * 2;
                                    if (p5 >= 10)
                                        p5 -= 9;
                                    p6 = d6 * 1;
                                    if (p6 >= 10)
                                        p6 -= 9;
                                    p7 = d7 * 2;
                                    if (p7 >= 10)
                                        p7 -= 9;
                                    p8 = d8 * 1;
                                    if (p8 >= 10)
                                        p8 -= 9;
                                    p9 = d9 * 2;
                                    if (p9 >= 10)
                                        p9 -= 9;
                                    modulo = 10;
                                } else if (d3 == 6) {
                                    pub = true;
                                    p1 = d1 * 3;
                                    p2 = d2 * 2;
                                    p3 = d3 * 7;
                                    p4 = d4 * 6;
                                    p5 = d5 * 5;
                                    p6 = d6 * 4;
                                    p7 = d7 * 3;
                                    p8 = d8 * 2;
                                    p9 = 0;
                                } else if (d3 == 9) {
                                    pri = true;
                                    p1 = d1 * 4;
                                    p2 = d2 * 3;
                                    p3 = d3 * 2;
                                    p4 = d4 * 7;
                                    p5 = d5 * 6;
                                    p6 = d6 * 5;
                                    p7 = d7 * 4;
                                    p8 = d8 * 3;
                                    p9 = d9 * 2;
                                }

                                suma = p1 + p2 + p3 + p4 + p5 + p6 + p7 + p8 + p9;
                                residuo = suma % modulo;

                                var digitoVerificador = residuo == 0 ? 0 : modulo - residuo;
                                if (numero.length === 10) {
                                    if (nat == true) {
                                        if (digitoVerificador != d10) {
                                            alertify.error("El número de cédula es incorrecto.");
                                            $("#direccion_cliente").attr("disabled", "disabled");
                                            $("#telefono_cliente").attr("disabled", "disabled");
                                            $("#correo").attr("disabled", "disabled");
                                        } else {
                                            if ($("#ruc_ci").val() == "0000000000") {
                                                alertify.error("El número de cédula es incorrecto.");
                                                $("#direccion_cliente").attr("disabled", "disabled");
                                                $("#telefono_cliente").attr("disabled", "disabled");
                                                $("#correo").attr("disabled", "disabled");
                                            } else {
                                                alertify.success("El número de cédula es correcto.");
                                                $("#nombre_cliente").val("");
                                                $("#direccion_cliente").val("");
                                                $("#telefono_cliente").val("");
                                                $("#correo").val("");
                                                $("#id_cliente").val("");
                                                $("#nombre_cliente").focus();
                                                $("#direccion_cliente").removeAttr("disabled");
                                                $("#telefono_cliente").removeAttr("disabled");
                                                $("#correo").removeAttr("disabled");
                                            }
                                        }
                                    }
                                } else {
                                    var ruc = numero.substr(10, 13);
                                    var digito3 = numero.substring(2, 3);
                                    if (ruc == "001") {
                                        if (digito3 < 6) {
                                            if (nat == true) {
                                                if (digitoVerificador != d10) {
                                                    alertify.error("El ruc persona natural1 es incorrecto.");
                                                    $("#direccion_cliente").attr("disabled", "disabled");
                                                    $("#telefono_cliente").attr("disabled", "disabled");
                                                    $("#correo").attr("disabled", "disabled");
                                                } else {
                                                    alertify.success("El ruc persona natural1 es correcto.");
                                                    $("#nombre_cliente").val("");
                                                    $("#direccion_cliente").val("");
                                                    $("#telefono_cliente").val("");
                                                    $("#correo").val("");
                                                    $("#id_cliente").val("");
                                                    $("#nombre_cliente").focus();
                                                    $("#direccion_cliente").removeAttr("disabled");
                                                    $("#telefono_cliente").removeAttr("disabled");
                                                    $("#correo").removeAttr("disabled");
                                                }
                                            }
                                        } else {
                                            if (digito3 == 6) {
                                                if (pub == true) {
                                                    if (digitoVerificador != d9) {
                                                        alertify.error("El ruc público es incorrecto.");
                                                        $("#direccion_cliente").attr("disabled", "disabled");
                                                        $("#telefono_cliente").attr("disabled", "disabled");
                                                        $("#correo").attr("disabled", "disabled");
                                                    } else {
                                                        alertify.success("El ruc público es correcto.");
                                                        $("#nombre_cliente").val("");
                                                        $("#direccion_cliente").val("");
                                                        $("#telefono_cliente").val("");
                                                        $("#correo").val("");
                                                        $("#id_cliente").val("");
                                                        $("#nombre_cliente").focus();
                                                        $("#direccion_cliente").removeAttr("disabled");
                                                        $("#telefono_cliente").removeAttr("disabled");
                                                        $("#correo").removeAttr("disabled");
                                                    }
                                                }
                                            } else {
                                                if (digito3 == 9) {
                                                    if (pri == true) {
                                                        if (digitoVerificador != d10) {
                                                            if (d10 == 4 || d10 == 6) {
                                                                alertify.success('El ruc de sociedad privado es correcto.');
                                                            } else {
                                                                alertify.error("El ruc privado es incorrecto.");
                                                                $("#direccion_cliente").attr("disabled", "disabled");
                                                                $("#telefono_cliente").attr("disabled", "disabled");
                                                                $("#correo").attr("disabled", "disabled");
                                                            }




                                                        } else {
                                                            alertify.success("El ruc privado es correcto.");
                                                            $("#nombre_cliente").val("");
                                                            $("#direccion_cliente").val("");
                                                            $("#telefono_cliente").val("");
                                                            $("#correo").val("");
                                                            $("#id_cliente").val("");
                                                            $("#nombre_cliente").focus();
                                                            $("#direccion_cliente").removeAttr("disabled");
                                                            $("#telefono_cliente").removeAttr("disabled");
                                                            $("#correo").removeAttr("disabled");
                                                        }
                                                    }
                                                } else {
                                                    if (d3 == 7 || d3 == 8) {
                                                        alertify.error(
                                                            "El tercer dígito ingresado es inválido"
                                                        );
                                                    } else {
                                                        if (numero.substr(10, 3) != "001") {
                                                            alertify.error(
                                                                "El ruc de la empresa del sector privado debe terminar con 001"
                                                            );
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    } else {
                                        if (numero.length == 13) {
                                            alertify.error("El ruc es incorrecto.");
                                            $("#direccion_cliente").attr("disabled", "disabled");
                                            $("#telefono_cliente").attr("disabled", "disabled");
                                            $("#correo").attr("disabled", "disabled");
                                        }
                                    }
                                }
                            }
                        }
                    },
                });
                //           nuevo_cliente();
            }
        }
    });
    //////////////////////////////////////
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
    $("#formaspago").change(function (e) {
        porcentaje();

    });

    function porcentaje() {
        if ($("#formaspago").val() == "Credito") {
            $('#valor_tarjetaid').show();
            $('#resultado_tar_totalid').show();
            $('#calculo_porcentajeid').show();
            $('#valor_reciboid').hide();
            $('#total_ventaid').hide();
            $('#valor_cambioitemid').hide();

            $("#valor_recibo").attr("disabled", "disabled");
            $("#total_venta").attr("disabled", "disabled");
            $("#valor_cambio").attr("disabled", "disabled");
            var total_tarjeta = parseFloat($("#tot").val()) * (parseFloat($("#valor_tarjeta").val()) / 100);
            var total_cobrar = total_tarjeta + parseFloat($("#tot").val());
            $("#resultado_tar_total").val('0');
            $("#resultado_tar_total").val(total_cobrar.toFixed(2));


            $("#calculo_porcentaje").val('0');
            $("#calculo_porcentaje").val(total_tarjeta.toFixed(2));
        } else {

            if ($("#formaspago").val() == "Contado") {

                $("#valor_recibo").focus();
                $('#valor_reciboid').show();
                $('#total_ventaid').show();
                $('#valor_cambioitemid').show();
                $("#valor_recibo").attr("disabled", false);

                $('#valor_tarjeta').hide();
                $('#resultado_tar_total').hide();

            }

        }

    }
    //    $("#cantidad").keyup(function() {
    //   $.ajax({
    //   type: "POST",
    //   url:  "buscar_cant_descu.php",
    //   data:  "id="+ $("#codigo").val(),
    //   success: function(data) {
    //   var numericaInt = parseInt(data);               
    //   var cantidad = $("#cantidad").val();      
    //   var filas = jQuery("#list").jqGrid("getRowData");
    //   for (var i = 0; i < filas.length; i++) {
    //   var id = filas[i]; 
    //        if (id['cod_producto'] == $("#cod_producto").val()) {
    //                var repe = 1;
    //	        var can = id['cantidad'];		
    //                  }
    //             }
    //           if (repe == 1) {                                                                             
    //              var  suma = parseInt(can) + parseInt($("#cantidad").val());
    //                    if(suma >= numericaInt){
    //                              $("#p_venta").val("");
    //                              $("#tipo_precio").append('<option selected="" value="MAYORISTA">MAYORISTA</option>');                                            
    //                              mayorista();
    //                              
    //                          }
    //                 }
    //                else{                
    //                if(cantidad >= numericaInt)
    //                {   
    //                 $("#p_venta").val("");
    //                 $("#tipo_precio").append('<option selected="" value="MAYORISTA">MAYORISTA</option>');                                            
    //                 mayorista();
    //                 }else{
    //                     $("#tipo_precio").find("option[value='MAYORISTA']").remove(); 
    //                     $("#tipo_precio").append('<option selected="" value="MINORISTA">MINORISTA</option>');  
    //                     mayorista();                                
    //                     }           
    //                  } 
    //              }
    //        });  
    //    });   
    //    

    alertify.set({ delay: 1000 });
    show();
    // cambiar idioma
    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'MiÃ©rcoles', 'Jueves', 'Viernes', 'SÃ¡bado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'MiÃ©', 'Juv', 'Vie', 'SÃ¡b'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'SÃ¡'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);
    // fin
    function combo(tipo) {
        $.ajax({
            type: "POST",
            url: "buscar_producto9.php?tipo_precio_venta=" + tipo,
            success: function (resp) {
                combo_1 = JSON.parse(resp);
            }
        });
        return combo_1;
    }

    function combo1(tipo) {
        $.ajax({
            type: "POST",
            url: "buscar_producto10.php",
            success: function (resp) {
                combo_2 = JSON.parse(resp);
            }
        });
        return combo_2;
    }
    if ($("#num_oculto_factura").val() == "") {
        $("#num_factura").val("");
    } else {
        var str = $("#num_oculto_factura").val();
        var res = parseInt(str.substr(8, 16));
        res = res + 1;

        $("#num_factura").val(res);
        var a = autocompletar_factura(res);
        var validado = a + "" + res;
        $("#num_factura").val(validado);
    }




    $("#btncargar").click(function (e) {
        e.preventDefault();
    });
    $("#btnAgregar").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardarSeries").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardar").click(function (e) {
        e.preventDefault();
    });


    //    $("#btnCorreos").click(function(e) {
    //        e.preventDefault();
    //    });
    $("#btnActualizar").click(function (e) {
        e.preventDefault();
    });
    $("#btnActualizartrans").click(function (e) {
        e.preventDefault();
    });
    ////    $("#btnGuardarTemporal").click(function(e) {
    //        e.preventDefault();
    //    });
    $("#btnModificar").click(function (e) {
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
    $("#btnSalir").click(function (e) {
        e.preventDefault();
    });
    $("#btnAcceder").click(function (e) {
        e.preventDefault();
    });
    $("#btnCancelar").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardarV").click(function (e) {
        e.preventDefault();
    });
    $("#btnCancelarV").click(function (e) {
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
    $("#btnCorreo").click(function (e) {
        e.preventDefault();
    });
    $("#btnEstado").click(function (e) {
        e.preventDefault();
    });
    $("#btnCancelarRetenciones").click(function (e) {
        e.preventDefault();
    });

    $("#btnGuardarRetenciones").click(function (e) {
        e.preventDefault();
    });

    ///////////

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
    $("#tipoRetencionesF").on("change", calculo_ret_fuente);
    $("#tipoRetencionesFS").on("change", buscar_servicio_producto);
    $("#tipoRetencionesFS").on("change", calculo_ret_fuenteS);
    $("#tipoRetencionesI").on("change", calculo_ret_iva);
    $("#retencionF_prima").on("change", buscar_bien_prima_result);
    $("#tipoRetencionesIs").on("change", calculo_ret_ivas);
    /////
    $("#btnAgregar").on("click", agregar);
    $("#btnGuardarSeries").on("click", guardar_serie);
    $("#btnGuardar").on("click", ingresar_cambio);
    //      $("#btnCorreos").on("click", enviarCorreos);
    //    $("#btnGuardar").on("click", ingresar_cambio);
    //    $("#btnGuardarTemporal").on("click", guardar_factura_temporal);
    //    $("#btnModificar").on("click", modificar_factura);
    $("#btnNuevo").on("click", limpiar_factura);
    $("#btnAnular").on("click", anular_factura);
    $("#btnAceptar").on("click", aceptar);
    $("#btnSalir").on("click", cancelar);
    $("#btnAcceder").on("click", validar_acceso);
    $("#btnCancelar").on("click", cancelar_acceso);
    $("#btnGuardarV").on("click", guardar_factura);

    $("#btnCancelarV").on("click", cancelar_cambio);

    $("#btnGuardarRetenciones").on("click", guardar_retenciones_factura_compra);
    $("#btnAtras").on("click", flecha_atras);
    $("#btnAdelante").on("click", flecha_siguiente);
    $("#btnCorreo").on("click", enviar_correo);

    //    $("#btnCancelarRetenciones").on("click", function (e) {
    //        location.reload();
    //    });

    $("#ruc_ci").on("keyup", limpiar_campo);
    $("#codigo").on("keyup", limpiar_campo3);
    $("#producto").on("keyup", limpiar_campo4);




    $("#codigo").on("keypress", enter);
    $("#producto").on("keypress", enter);
    $("#num_liquidacion").on("keypress", enter_liqui);
    $("#cantidad").on("keypress", enter);
    $("#p_venta").on("keypress", enter1);
    $("#descuento").on("keypress", enter2);
    $("#num_factura").on("keypress", enter3);
    $("#ruc_ci").on("keypress", enter4);
    $("#nombre_cliente").on("keypress", enter5);
    $("#direccion_cliente").on("keypress", enter5);
    $("#telefono_cliente").on("keypress", enter6);
    $("#calculoRetencionF").on("keypress", enter7);
    $("#calculoRetencionFS").on("keypress", enter7);
    $("#calculoRetencionI").on("keypress", enter7);
    $("#calculoRetencionIs").on("keypress", enter7);
    $("#btnGuardarV").on("keypress", enter8);
    $("#btnGuardarV").on("keypress", enter9);
    $("#valor_cambio").on("keypress", enter9);

    $("#direccion_cliente").attr("disabled", "disabled");
    //    $("#tarjetas").attr("disabled", "disabled");
    // $("#telefono_cliente").attr("disabled", "disabled");
    // $("#correo").attr("disabled", "disabled");
    $("#autorizacion").attr("disabled", "disabled");
    $("#btnAnular").attr("disabled", true);

    $("#telefono_cliente").validCampoFranz("0123456789");
    $("#telefono_cliente").attr("maxlength", "10");
    $("#ruc_ci").attr("maxlength", "13");

    $("#series").dialog(dialogo);
    $("#buscar_facturas_venta").dialog(dialogo2);
    $("#buscar_proformas").dialog(dialogo5);
    $("#buscar_estados").dialog(dialogo10);

    $("#buscar_proformas_tecnico").dialog(dialogotecnico);
    $("#clave_permiso").dialog(dialogo3);
    $("#valor_cambioid").dialog(dialogo8);
    $("#seguro").dialog(dialogo4);
    $("#buscar_notas_venta").dialog(dialogo6);
    $("#tipo_busqueda").dialog(dialogo7);


    $("#retencionF1").on("change", cambio_ret_fuente);
    $("#retencionF2").on("change", cambio_ret_fuente);

    $("#retencionI1").on("change", cambio_ret_iva);
    $("#retencionI2").on("change", cambio_ret_iva);

    $("#tipoRetencionesF").on("change", calculo_ret_fuente);
    $("#tipoRetencionesI").on("change", calculo_ret_iva);


    $("#btnImprimir").click(function () {
        $.ajax({
            type: "POST",
            url: "../../procesos/validacion.php",
            data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "liquidacion_compra" + "&id_tabla=" + "id_liquidacion_compra" + "&tipo=" + 1,
            success: function (data) {
                var val = data;
                if (val != "") {
                    if ($("#tipo_venta").val() == "FACTURA") {
                        var myWindow = window.open("generarPDF_1.php?hoja=A5&id=" + $("#comprobante").val(), '_blank');


                        myWindow.focus();
                        myWindow.print();
                    } else {
                        if ($("#tipo_venta").val() == "NOTA") {
                            var myWindow = window.open("../../reportes/nota_venta.php?hoja=A4&id=" + $("#comprobante").val(), '_blank');
                            myWindow.focus();
                            myWindow.print();
                        }
                    }
                } else {
                    guardar_imprimir_factura();
                }
            }
        });
    });


    $("#btnBuscar").click(function () {
        $("#tipo_busqueda").dialog("open");
    });

    $("#btnTipoBuscar").click(function () {
        if ($("#tipo_venta_busqueda").val() == "FACTURA") {
            $("#buscar_facturas_venta").dialog("open");
        } else {
            if ($("#tipo_venta_busqueda").val() == "NOTA") {
                $("#buscar_notas_venta").dialog("open");
            }
        }
    });

    $("#btnProforma").click(function () {
        $("#buscar_proformas").dialog("open");
    });
    $("#btnEstados").click(function () {
        $("#buscar_estados").dialog("open");
    });




    $("#btnMantenimiento").click(function () {
        $("#buscar_proformas_tecnico").dialog("open");
    });

    // para precio
    $("#p_venta").on("keypress", punto);
    $("#precio").on("keypress", punto);
    $("#adelanto").on("keypress", punto);
    // FIN

    //       function mayorista() {
    //        var precio = $("#tipo_precio").val(); 
    //        var codigo = $("#codigo_barras").val();
    //        
    //        if (precio == "MINORISTA") {
    //            $.getJSON('search.php?codigo_barras=' + codigo + '&precio=' + precio, function(data) {
    //                var tama = data.length;
    //                if (tama != 0) {
    //                    for (var i = 0; i < tama; i = i + 10) {
    //                        $("#codigo").val(data[i]);
    //                        $("#producto").val(data[i + 1]);
    //                        $("#p_venta").val(data[i + 2]);
    //                        $("#descuento").attr("max",data[i + 7]);
    //                        $("#disponibles").val(data[i + 3]);
    //                        $("#iva_producto").val(data[i + 4]);
    //                        $("#carga_series").val(data[i + 5]);
    //                        $("#cod_producto").val(data[i + 6]);
    //                        $("#des").val(data[i + 7]);
    //                        $("#inventar").val(data[i + 8]);
    //                        $("#incluye").val(data[i + 9]);
    ////                        $("#cantidad").val("1");
    ////                        $("#cantidad").select();
    //                    }
    //                } else {
    //                    $("#codigo").val("");
    //                    $("#producto").val("");
    //                    $("#p_venta").val("");
    //                    $("#descuento").val("");
    //                    $("#disponibles").val("");
    //                    $("#iva_producto").val("");
    //                    $("#carga_series").val("");
    //                    $("#cod_producto").val("");
    //                    $("#des").val("");
    //                    $("#inventar").val("");
    //                    $("#incluye").val("");
    //                    alertify.error("Producto no ingresado");
    //                    $("#codigo_barras").val("");
    //                }
    //            });
    //        } else {
    //            if (precio == "MAYORISTA") {
    //                $.getJSON('search.php?codigo_barras=' + codigo + '&precio=' + precio, function(data) {
    //                    var tama = data.length;
    //                    if (tama != 0) {
    //                        for (var i = 0; i < tama; i = i + 10) {
    //                            $("#codigo").val(data[i]);
    //                            $("#producto").val(data[i + 1]);
    //                            $("#p_venta").val(data[i + 2]);
    //                            $("#descuento").attr("max",data[i + 7]);
    //                            $("#disponibles").val(data[i + 3]);
    //                            $("#iva_producto").val(data[i + 4]);
    //                            $("#carga_series").val(data[i + 5]);
    //                            $("#cod_producto").val(data[i + 6]);
    //                            $("#des").val(data[i + 7]);
    //                            $("#inventar").val(data[i + 8]);
    //                            $("#incluye").val(data[i + 9]);
    ////                            $("#cantidad").val("1");
    ////                            $("#cantidad").select();
    //                        }
    //                    } else {
    //                        $("#codigo").val("");
    //                        $("#producto").val("");
    //                        $("#p_venta").val("");
    //                        $("#descuento").val("");
    //                        $("#disponibles").val("");
    //                        $("#iva_producto").val("");
    //                        $("#carga_series").val("");
    //                        $("#cod_producto").val("");
    //                        $("#des").val("");
    //                        $("#inventar").val("");
    //                        $("#incluye").val("");
    //                        alertify.error("Producto no ingresado");
    //                        $("#codigo_barras").val("");
    //                    }
    //                });
    //            } else {
    //                if (precio == "NEGOCIO") {
    //                $.getJSON('search.php?codigo_barras=' + codigo + '&precio=' + precio, function(data) {
    //                    var tama = data.length;
    //                    if (tama != 0) {
    //                        for (var i = 0; i < tama; i = i + 10) {
    //                            $("#codigo").val(data[i]);
    //                            $("#producto").val(data[i + 1]);
    //                            $("#p_venta").val(data[i + 2]);
    //                            $("#descuento").attr("max",data[i + 7]);
    //                            $("#disponibles").val(data[i + 3]);
    //                            $("#iva_producto").val(data[i + 4]);
    //                            $("#carga_series").val(data[i + 5]);
    //                            $("#cod_producto").val(data[i + 6]);
    //                            $("#des").val(data[i + 7]);
    //                            $("#inventar").val(data[i + 8]);
    //                            $("#incluye").val(data[i + 9]);
    ////                            $("#cantidad").val("1");
    ////                            $("#cantidad").select();
    //                        }
    //                    } else {
    //                        $("#codigo").val("");
    //                        $("#producto").val("");
    //                        $("#p_venta").val("");
    //                        $("#descuento").val("");
    //                        $("#disponibles").val("");
    //                        $("#iva_producto").val("");
    //                        $("#carga_series").val("");
    //                        $("#cod_producto").val("");
    //                        $("#des").val("");
    //                        $("#inventar").val("");
    //                        $("#incluye").val("");
    //                        alertify.error("Producto no ingresado");
    //                        $("#codigo_barras").val("");
    //                    }
    //                });
    //            }
    //            }
    //        }   
    //    }
    //    
    /////////////TIPO PRECIO VENTA///////////7

    // buscar productos codigo barras





    // fin




    /////////FIN////////



    // buscar productos codigo barras
    $("#codigo_barras").change(function (e) {
        barras();
    });

    function barras() {

        var precio_tipo = $("#tipo_precio_venta").val();
        var codigo = $("#codigo_barras").val();

        if (precio_tipo == "MINORISTA") {
            $.getJSON('search.php?codigo_barras=' + codigo + '&precio=' + precio_tipo, function (data) {
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
                        $("#cantidad").val("1");
                        $("#num_liquidacion").select()
                    }
                } else {
                    //                    $("#codigo").val("");
                    //                    $("#producto").val("");
                    $("#p_venta").val("");
                    $("#descuento").val("");
                    $("#disponibles").val("");
                    $("#iva_producto").val("");
                    $("#carga_series").val("");
                    //                    $("#cod_producto").val("");
                    $("#des").val("");
                    $("#inventar").val("");
                    $("#incluye").val("");
                    alertify.error("Producto no ingresado");
                    $("#codigo_barras").val("");
                }
            });
        } else {
            if (precio_tipo == "MAYORISTA") {
                $.getJSON('search.php?codigo_barras=' + codigo + '&precio=' + precio_tipo, function (data) {
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
                            $("#cantidad").val("1");
                            $("#num_liquidacion").select()
                        }
                    } else {
                        //                        $("#codigo").val("");
                        //                        $("#producto").val("");
                        $("#p_venta").val("");
                        $("#descuento").val("");
                        $("#disponibles").val("");
                        $("#iva_producto").val("");
                        $("#carga_series").val("");
                        //                        $("#cod_producto").val("");
                        $("#des").val("");
                        $("#inventar").val("");
                        $("#incluye").val("");
                        alertify.error("Producto no ingresado");
                        $("#codigo_barras").val("");
                    }
                });
            } else {
                if (precio_tipo == "NEGOCIO") {
                    $.getJSON('search.php?codigo_barras=' + codigo + '&precio=' + precio_tipo, function (data) {
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
                                $("#cantidad").val("1");
                                $("#num_liquidacion").select()
                            }
                        } else {
                            //                        $("#codigo").val("");
                            //                        $("#producto").val("");
                            $("#p_venta").val("");
                            $("#descuento").val("");
                            $("#disponibles").val("");
                            $("#iva_producto").val("");
                            $("#carga_series").val("");
                            //                        $("#cod_producto").val("");
                            $("#des").val("");
                            $("#inventar").val("");
                            $("#incluye").val("");
                            alertify.error("Producto no ingresado");
                            $("#codigo_barras").val("");
                        }
                    });
                }
            }
        }
    }
    // fin
    //
    //    // buscar productos codigo
    //    $("#codigo").keyup(function(e) {
    //        var precio = $("#tipo_precio").val(); 
    //        var res = combo(precio); 
    //
    //        if (precio == "MINORISTA") {
    //            $("#codigo").autocomplete({
    //                source: function (req, response) {                    
    //                    var results = $.ui.autocomplete.filter(res, req.term);                    
    //                    response(results.slice(0, 20));
    //                },
    //                minLength: 1,
    //                focus: function(event, ui) {
    //                $("#codigo_barras").val(ui.item.codigo_barras);
    //                $("#codigo").val(ui.item.value);
    //                $("#producto").val(ui.item.producto);
    //                $("#p_venta").val(ui.item.p_venta);
    //                $("#descuento").attr("max",ui.item.descuento);
    //                $("#disponibles").val(ui.item.disponibles);
    //                $("#iva_producto").val(ui.item.iva_producto);
    //                $("#carga_series").val(ui.item.carga_series);
    //                $("#cod_producto").val(ui.item.cod_producto);
    //                $("#des").val(ui.item.des);
    //                $("#inventar").val(ui.item.inventar);
    //                $("#incluye").val(ui.item.incluye);
    //                $("#cantidad").val("1");
    //                if((ui.item.iva_producto)=='Si'){
    //                var calivaprecio=0;
    //                var calivaprecioRESULT=0;
    //                var p_ventaresult=(ui.item.p_venta);
    //                calivaprecio=((ui.item.p_venta) * (calculoIVA/100));
    //                calivaprecioRESULT = parseFloat(calivaprecio) + parseFloat(p_ventaresult);                    
    //                $("#calculoivaprecio").val(calivaprecioRESULT);
    //                 
    //                    }
    //                    else{
    //                        $("#calculoivaprecio").val("");
    //                        
    //                        
    //                    }
    //                return false;
    //                },
    //                select: function(event, ui) {
    //                $("#codigo_barras").val(ui.item.codigo_barras);
    //                $("#codigo").val(ui.item.value);
    //                $("#producto").val(ui.item.producto); 
    //           
    //     
    //                $("#p_venta").val(   parseFloat(ui.item.p_venta).toFixed(3));
    //                $("#descuento").attr("max",ui.item.descuento);
    //                $("#disponibles").val(ui.item.disponibles);
    //                $("#iva_producto").val(ui.item.iva_producto);
    //                $("#carga_series").val(ui.item.carga_series);
    //                $("#cod_producto").val(ui.item.cod_producto);
    //                $("#des").val(ui.item.des);
    //                $("#inventar").val(ui.item.inventar);
    //                $("#incluye").val(ui.item.incluye);
    //                $("#cantidad").val("1");
    //                $("#cantidad").select();
    //                if((ui.item.iva_producto)=='Si'){
    //                var calivaprecio=0;
    //                var calivaprecioRESULT=0;
    //                var p_ventaresult=(ui.item.p_venta);
    //                calivaprecio=((ui.item.p_venta) * (calculoIVA/100));
    //                calivaprecioRESULT = parseFloat(calivaprecio) + parseFloat(p_ventaresult);                    
    //                $("#calculoivaprecio").val(calivaprecioRESULT);
    //                 
    //                    }
    //                    else{
    //                        $("#calculoivaprecio").val("");
    //                        
    //                        
    //                    }
    //                return false;
    //                }
    //
    //                }).data("ui-autocomplete")._renderItem = function(ul, item) {
    //                return $("<li>")
    //                .append("<a>" + item.value + "</a>")
    //                .appendTo(ul);
    //            };
    //        } else {            
    //            if (precio == "MAYORISTA") {
    //                $("#codigo").autocomplete({
    //                    source: function (req, response) {                    
    //                    var results = $.ui.autocomplete.filter(res, req.term);                    
    //                        response(results.slice(0, 20));
    //                    },
    //                    minLength: 1,
    //                    focus: function(event, ui) {
    //                    $("#codigo_barras").val(ui.item.codigo_barras);
    //                    $("#codigo").val(ui.item.value);
    //                    $("#producto").val(ui.item.producto);
    //                    $("#p_venta").val(ui.item.p_venta);
    //                    $("#descuento").attr("max",ui.item.descuento);
    //                    $("#disponibles").val(ui.item.disponibles);
    //                    $("#iva_producto").val(ui.item.iva_producto);
    //                    $("#carga_series").val(ui.item.carga_series);
    //                    $("#cod_producto").val(ui.item.cod_producto);
    //                    $("#des").val(ui.item.des);
    //                    $("#inventar").val(ui.item.inventar);
    //                    $("#incluye").val(ui.item.incluye);
    //                    $("#cantidad").val("1");
    //                    if((ui.item.iva_producto)=='Si'){
    //                var calivaprecio=0;
    //                var calivaprecioRESULT=0;
    //                var p_ventaresult=(ui.item.p_venta);
    //                calivaprecio=((ui.item.p_venta) * (calculoIVA/100));
    //                calivaprecioRESULT = parseFloat(calivaprecio) + parseFloat(p_ventaresult);                    
    //                $("#calculoivaprecio").val(calivaprecioRESULT);
    //                 
    //                    }
    //                    else{
    //                        $("#calculoivaprecio").val("");
    //                        
    //                        
    //                    }
    //                    return false;
    //                    },
    //                    select: function(event, ui) {
    //                    $("#codigo_barras").val(ui.item.codigo_barras);
    //                    $("#codigo").val(ui.item.value);
    //                    $("#producto").val(ui.item.producto);
    //                    $("#p_venta").val(ui.item.p_venta);
    //                    $("#descuento").attr("max",ui.item.descuento);
    //                    $("#disponibles").val(ui.item.disponibles);
    //                    $("#iva_producto").val(ui.item.iva_producto);
    //                    $("#carga_series").val(ui.item.carga_series);
    //                    $("#cod_producto").val(ui.item.cod_producto);
    //                    $("#des").val(ui.item.des);
    //                    $("#inventar").val(ui.item.inventar);
    //                    $("#incluye").val(ui.item.incluye);
    //                    $("#cantidad").val("1");
    //                    $("#cantidad").select();
    //                     if((ui.item.iva_producto)=='Si'){
    //                var calivaprecio=0;
    //                var calivaprecioRESULT=0;
    //                var p_ventaresult=(ui.item.p_venta);
    //                calivaprecio=((ui.item.p_venta) * (calculoIVA/100));
    //                calivaprecioRESULT = parseFloat(calivaprecio) + parseFloat(p_ventaresult);                    
    //                $("#calculoivaprecio").val(calivaprecioRESULT);
    //                 
    //                    }
    //                    else{
    //                        $("#calculoivaprecio").val("");
    //                        
    //                        
    //                    }
    //                    return false;
    //                    }
    //                    }).data("ui-autocomplete")._renderItem = function(ul, item) {
    //                    return $("<li>")
    //                    .append("<a>" + item.value + "</a>")
    //                    .appendTo(ul);
    //                };
    //            } else {
    //                if (precio == "NEGOCIO") {
    //                    $("#codigo").autocomplete({
    //                        source: function (req, response) {                    
    //                        var results = $.ui.autocomplete.filter(res, req.term);                    
    //                            response(results.slice(0, 20));
    //                        },
    //                        minLength: 1,
    //                        focus: function(event, ui) {
    //                        $("#codigo_barras").val(ui.item.codigo_barras);
    //                        $("#codigo").val(ui.item.value);
    //                        $("#producto").val(ui.item.producto);
    //                        $("#p_venta").val(ui.item.p_venta);
    //                        $("#descuento").attr("max",ui.item.descuento);
    //                        $("#disponibles").val(ui.item.disponibles);
    //                        $("#iva_producto").val(ui.item.iva_producto);
    //                        $("#carga_series").val(ui.item.carga_series);
    //                        $("#cod_producto").val(ui.item.cod_producto);
    //                        $("#des").val(ui.item.des);
    //                        $("#inventar").val(ui.item.inventar);
    //                        $("#incluye").val(ui.item.incluye);
    //                        $("#cantidad").val("1");
    //                         if((ui.item.iva_producto)=='Si'){
    //                var calivaprecio=0;
    //                var calivaprecioRESULT=0;
    //                var p_ventaresult=(ui.item.p_venta);
    //                calivaprecio=((ui.item.p_venta) * (calculoIVA/100));
    //                calivaprecioRESULT = parseFloat(calivaprecio) + parseFloat(p_ventaresult);                    
    //                $("#calculoivaprecio").val(calivaprecioRESULT);
    //                 
    //                    }
    //                    else{
    //                        $("#calculoivaprecio").val("");
    //                        
    //                        
    //                    }
    //                        return false;
    //                        },
    //                        select: function(event, ui) {
    //                        $("#codigo_barras").val(ui.item.codigo_barras);
    //                        $("#codigo").val(ui.item.value);
    //                        $("#producto").val(ui.item.producto);
    //                        $("#p_venta").val(ui.item.p_venta);
    //                        $("#descuento").attr("max",ui.item.descuento);
    //                        $("#disponibles").val(ui.item.disponibles);
    //                        $("#iva_producto").val(ui.item.iva_producto);
    //                        $("#carga_series").val(ui.item.carga_series);
    //                        $("#cod_producto").val(ui.item.cod_producto);
    //                        $("#des").val(ui.item.des);
    //                        $("#inventar").val(ui.item.inventar);
    //                        $("#incluye").val(ui.item.incluye);
    //                        $("#cantidad").val("1");
    //                        $("#cantidad").select();
    //                         if((ui.item.iva_producto)=='Si'){
    //                var calivaprecio=0;
    //                var calivaprecioRESULT=0;
    //                var p_ventaresult=(ui.item.p_venta);
    //                calivaprecio=((ui.item.p_venta) * (calculoIVA/100));
    //                calivaprecioRESULT = parseFloat(calivaprecio) + parseFloat(p_ventaresult);                    
    //                $("#calculoivaprecio").val(calivaprecioRESULT);
    //                 
    //                    }
    //                    else{
    //                        $("#calculoivaprecio").val("");
    //                        
    //                        
    //                    }
    //                        return false;
    //                        }
    //                        }).data("ui-autocomplete")._renderItem = function(ul, item) {
    //                        return $("<li>")
    //                        .append("<a>" + item.value + "</a>")
    //                        .appendTo(ul);
    //                    };
    //                }
    //            }
    //        }
    //    });
    //    // fin

    // busqueda productos articulos
    $("#producto").keyup(function (e) {


        var precio = $("#tipo_precio_venta").val();
        var res = combo1(precio);


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
                $("#iva_producto").val(ui.item.iva_producto);
                $("#carga_series").val(ui.item.carga_series);
                $("#cod_producto").val(ui.item.cod_producto);
                $("#des").val(ui.item.des);
                $("#inventar").val(ui.item.inventar);
                $("#incluye").val(ui.item.incluye);
                $("#cantidad_descuento_campo").val(ui.item.cantidad_descuento_campo);
                $("#cantidad").val("1");
                if ((ui.item.iva_producto) == 'Si') {
                    var calivaprecio = 0;
                    var calivaprecioRESULT = 0;
                    var p_ventaresult = (ui.item.p_venta);
                    calivaprecio = ((ui.item.p_venta) * (calculoIVA / 100));
                    calivaprecioRESULT = parseFloat(calivaprecio) + parseFloat(p_ventaresult);
                    $("#calculoivaprecio").val(calivaprecioRESULT);

                } else {
                    $("#calculoivaprecio").val("");


                }

                return false;
            },
            select: function (event, ui) {
                $("#codigo_barras").val(ui.item.codigo_barras);
                $("#producto").val(ui.item.value);
                $("#codigo").val(ui.item.codigo);
                $("#p_venta").val(parseFloat(ui.item.p_venta).toFixed(3));

                $("#descuento").attr("max", ui.item.descuento);
                $("#disponibles").val(ui.item.disponibles);
                $("#iva_producto").val(ui.item.iva_producto);
                $("#carga_series").val(ui.item.carga_series);
                $("#cod_producto").val(ui.item.cod_producto);
                $("#des").val(ui.item.des);
                $("#inventar").val(ui.item.inventar);
                $("#incluye").val(ui.item.incluye);
                $("#cantidad_descuento_campo").val(ui.item.cantidad_descuento_campo);
                $("#cantidad").val("1");
                if ((ui.item.iva_producto) == 'Si') {
                    var calivaprecio = 0;
                    var calivaprecioRESULT = 0;
                    var p_ventaresult = (ui.item.p_venta);
                    calivaprecio = ((ui.item.p_venta) * (calculoIVA / 100));
                    calivaprecioRESULT = parseFloat(calivaprecio) + parseFloat(p_ventaresult);
                    $("#calculoivaprecio").val(calivaprecioRESULT);
                } else {
                    $("#calculoivaprecio").val("");

                }

                $("#num_liquidacion").select();
                return false;
            }

        }).data("ui-autocomplete")._renderItem = function (ul, item) {
            return $("<li>")
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
        };

    });
    // fin
    // accion limpiar
    //    $("#tipo_precio_venta").change(function() {
    ////       $("#codigo_barras").val("");
    ////       $("#cod_producto").val("");
    ////       $("#codigo").val("");
    ////       $("#producto").val("");
    ////       $("#cantidad").val("");
    //       $("#p_venta").val("");
    //       $("#descuento").val("");
    //       $("#disponibles").val("");
    //       $("#inventar").val("");
    //       $("#des").val("");
    //       $("#iva_producto").val("");
    //       $("#carga_series").val("");   
    //       $("#incluye").val("");
    //    });
    // fin
    $("#tipo_precio_venta").change(function () {

        var precio_tipo = $("#tipo_precio_venta").val();


        var producto_venta = $("#producto").val();
        producto_venta = producto_venta.replace('+', '%2b')


        if (precio_tipo == "MINORISTA") {
            $.getJSON('search_precio_venta.php?producto_venta=' + producto_venta + '&precio_tipo=' + precio_tipo, function (data) {
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
                        //                        $("#cantidad").val("1");
                        //                        $("#cantidad").select();
                    }
                } else {
                    //                    $("#codigo").val("");
                    //                    $("#producto").val("");
                    $("#p_venta").val("");
                    $("#descuento").val("");
                    $("#disponibles").val("");
                    $("#iva_producto").val("");
                    $("#carga_series").val("");
                    //                    $("#cod_producto").val("");
                    $("#des").val("");
                    $("#inventar").val("");
                    $("#incluye").val("");
                    alertify.error("Producto no ingresado");
                    $("#codigo_barras").val("");
                }
            });
        } else {
            if (precio_tipo == "MAYORISTA") {
                $.getJSON('search_precio_venta.php?producto_venta=' + producto_venta + '&precio_tipo=' + precio_tipo, function (data) {
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
                            //                            $("#cantidad").val("1");
                            //                            $("#cantidad").select();
                        }
                    } else {
                        //                        $("#codigo").val("");
                        //                        $("#producto").val("");
                        $("#p_venta").val("");
                        $("#descuento").val("");
                        $("#disponibles").val("");
                        $("#iva_producto").val("");
                        $("#carga_series").val("");
                        //                        $("#cod_producto").val("");
                        $("#des").val("");
                        $("#inventar").val("");
                        $("#incluye").val("");
                        alertify.error("Producto no ingresado");
                        $("#codigo_barras").val("");
                    }
                });
            } else {
                if (precio_tipo == "NEGOCIO") {
                    $.getJSON('search_precio_venta.php?producto_venta=' + producto_venta + '&precio_tipo=' + precio_tipo, function (data) {
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
                                //                            $("#cantidad").val("1");
                                //                            $("#cantidad").select();
                            }
                        } else {
                            //                        $("#codigo").val("");
                            //                        $("#producto").val("");
                            $("#p_venta").val("");
                            $("#descuento").val("");
                            $("#disponibles").val("");
                            $("#iva_producto").val("");
                            $("#carga_series").val("");
                            //                      $("#cod_producto").val("");
                            $("#des").val("");
                            $("#inventar").val("");
                            $("#incluye").val("");
                            alertify.error("Producto no ingresado");
                            $("#codigo_barras").val("");
                        }
                    });
                }
            }
        }
    });
    // buscar clientes identificacion
    $("#ruc_ci").autocomplete({
        source: "buscar_cliente.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#ruc_ci").val(ui.item.value);
            $("#id_proveedor").val(ui.item.id_proveedor);
            $("#nombre_cliente").val(ui.item.nombre_cliente);
            $("#direccion_cliente").val(ui.item.direccion_cliente);
            $("#telefono_cliente").val(ui.item.telefono_cliente);
            $("#correo").val(ui.item.correo);
            return false;
        },
        select: function (event, ui) {
            $("#ruc_ci").val(ui.item.value);
            $("#id_proveedor").val(ui.item.id_proveedor);
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

    // buscar clientes nombres
    $("#nombre_cliente").autocomplete({
        source: "buscar_cliente_nombre.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#nombre_cliente").val(ui.item.value);
            $("#id_proveedor").val(ui.item.id_proveedor);
            $("#ruc_ci").val(ui.item.ruc_ci);
            $("#direccion_cliente").val(ui.item.direccion_cliente);
            $("#telefono_cliente").val(ui.item.telefono_cliente);
            $("#correo").val(ui.item.correo);
            return false;
        },
        select: function (event, ui) {
            $("#nombre_cliente").val(ui.item.value);
            $("#id_proveedor").val(ui.item.id_proveedor);
            $("#ruc_ci").val(ui.item.ruc_ci);
            $("#direccion_cliente").val(ui.item.direccion_cliente);
            $("#telefono_cliente").val(ui.item.telefono_cliente);
            $("#correo").val(ui.item.correo);
            $("#direccion_cliente").attr("disabled", "disabled");
            //       $("#telefono_cliente").attr("disabled", "disabled");
            //        $("#correo").attr("disabled", "disabled");
            return false;
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };
    // fin

    /////////////////////////////////
    // $("#cantidad").on("keypress", punto);

    $("#cantidad").on("keypress", enter);
    $("#descuento").validCampoFranz("0123456789");
    $("#num_factura").validCampoFranz("0123456789");
    $("#num_factura").attr("maxlength", "9");
    $("#ruc_ci").validCampoFranz("0123456789");
    /////////////////////////////////////
    // atributos
    $("#adelanto").attr("disabled", "disabled");
    $("#meses").attr("disabled", "disabled");
    $("#cuotas").attr("disabled", "disabled");
    // fin
    $('.ui-spinner-button').click(function () {
        $(this).siblings('input').change();
    });
    //    $(function() {
    //    $("#fecha_dias").datepicker({ minDate: 0 });
    //    });
    $("#fecha_dias").change(function () {
        if ($("#adelanto").val() !== "") {

            resta = ($("#tot").val() - $("#adelanto").val());
            var redo = parseFloat(resta).toFixed(2);
            var dias = $("#fecha_dias").val();
            $("#tablaNuevo tbody").append("<tr>" +
                "<td align=center >" + dias + "</td>" +
                "<td align=center >" + redo + "</td>" +
                "<tr>");

            // $("#cuotas").append('<option>'+redo+'</option>');  
        } else {
            to = $("#tot").val();
            redo = parseFloat(to).toFixed(2);
            var dias = $("#fecha_dias").val();
            $("#tablaNuevo tbody").append("<tr>" +
                "<td align=center >" + dias + "</td>" +
                "<td align=center >" + redo + "</td>" +
                "<tr>");
            // $("#cuotas").append('<option>'+redo+'</option>'); 
        }
    });
    $("#adelanto").change(function () {
        if ($("#adelanto").val() !== "") {

            resta = ($("#tot").val() - $("#adelanto").val());
            var redo = parseFloat(resta).toFixed(2);
            var dias = $("#fecha_dias").val();
            $("#tablaNuevo tbody").append("<tr>" +
                "<td align=center >" + dias + "</td>" +
                "<td align=center >" + redo + "</td>" +
                "<tr>");

            // $("#cuotas").append('<option>'+redo+'</option>');  
        }
    });
    // calcular meses
    $("#meses").change(function () {
        var meses = $("#meses").val();
        var fecha = new Date();
        var dd = fecha.getDate();
        var mm = fecha.getMonth() + 2; //hoy es 0!
        var yyyy = fecha.getFullYear();
        if (dd < 10) {
            dd = '0' + dd
        }
        if (mm < 10) {
            mm = '0' + mm
        }
        fecha = yyyy + '-' + mm + '-' + dd;
        // $('#cuotas').children().remove().end();
        $("#tablaNuevo tbody").empty();
        if ($("#formaspago").val() === "Credito") {
            if (meses > 1) {
                var fecha = new Date();
                var dd = fecha.getDate();
                var mm = fecha.getMonth() + 3; //hoy es 0!
                var yyyy = fecha.getFullYear();
                if (dd < 10) {
                    dd = '0' + dd
                }
                if (mm < 10) {
                    mm = '0' + mm
                }
                fecha = yyyy + '-' + mm + '-' + dd;
                if ($("#adelanto").val() !== "") {
                    var resta = ($("#tot").val() - $("#adelanto").val());
                    for (var i = 1; i <= meses - 1; i++) {
                        var calcu = resta / (meses);
                        var entero = Math.floor(calcu).toFixed(2);
                        $("#tablaNuevo tbody").append("<tr>" +
                            "<td align=center >" + fecha + "</td>" +
                            "<td align=center>" + fecha + "</td>" +
                            "<tr>");
                        // $("#cuotas").append('<option>'+entero+'</option>'); 
                    }
                    var calcu1 = entero * (meses - 1);
                    var sal = resta - calcu1;
                    var entero2 = sal.toFixed(2);
                    $("#tablaNuevo tbody").append("<tr>" +
                        "<td align=center >" + fecha + "</td>" +
                        "<td align=center >" + entero2 + "</td>" +
                        "<tr>");
                    // $("#cuotas").append('<option>'+entero2+'</option>'); 
                } else {
                    var to = $("#tot").val();
                    for (i = 1; i <= meses - 1; i++) {
                        calcu = to / (meses);
                        entero = Math.floor(calcu).toFixed(2);
                        $("#tablaNuevo tbody").append("<tr>" +
                            "<td align=center >" + fecha + "</td>" +
                            "<td align=center >" + entero + "</td>" +
                            "<tr>");
                        //  $("#cuotas").append('<option>'+entero+'</option>'); 
                    }
                    calcu1 = entero * (meses - 1);
                    sal = to - calcu1;
                    entero2 = sal.toFixed(2);
                    $("#tablaNuevo tbody").append("<tr>" +
                        "<td align=center >" + fecha + "</td>" +
                        "<td align=center >" + entero2 + "</td>" +
                        "<tr>");
                    // $("#cuotas").append('<option>'+entero2+'</option>'); 
                }
            } else {
                if ($("#adelanto").val() !== "") {
                    resta = ($("#tot").val() - $("#adelanto").val());
                    var redo = parseFloat(resta).toFixed(2);
                    $("#tablaNuevo tbody").append("<tr>" +
                        "<td align=center >" + fecha + "</td>" +
                        "<td align=center >" + redo + "</td>" +
                        "<tr>");
                    // $("#cuotas").append('<option>'+redo+'</option>');  
                } else {
                    to = $("#tot").val();
                    redo = parseFloat(to).toFixed(2);
                    $("#tablaNuevo tbody").append("<tr>" +
                        "<td align=center >" + fecha + "</td>" +
                        "<td align=center >" + redo + "</td>" +
                        "<tr>");
                    // $("#cuotas").append('<option>'+redo+'</option>'); 
                }
            }
            // }
        }
    });
    $("#descuento").change(function () {
        if ($("#cod_producto").val() == "") {
            $("#descuento").val(0);
            $("#codigo").val("");
            //            alertify.alert("Error...Seleccione un producto");
        }
    });

    $("#formaspago").change(function () {
        var tam2 = jQuery("#list").jqGrid("getRowData");
        if ($("#formaspago").val() == "Contado") {
            $("#adelanto").attr("disabled", "disabled");
            $("#adelanto").val("");
            $("#meses").attr("disabled", "disabled");
            $("#meses").val("");
            $("#cuotas").attr("disabled", "disabled");
            $('#cuotas').children().remove().end();
        } else {
            if ($("#formaspago").val() == "Credito") {
                if (tam2.length > 0) {
                    $("#adelanto").removeAttr("disabled");
                    $("#meses").removeAttr("disabled");
                    $("#cuotas").removeAttr("disabled");
                } else {
                    $("#formaspago option[value=" + 'Contado' + "]").attr("selected", true);
                    alertify.alert("Error...Ingrese un monto a la factura");
                }
            } else {
                if ($("#formaspago").val() == "TCredito") {
                    //                    $("#tarjetas").attr("disabled", false);
                }
            }
        }
    });

    $("#fecha_actual").datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');


    $("#fecha_dias").datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: 0
    }).datepicker('setDate', 'today');

    $("#cancelacion").datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');
    $("#fecha_auto").datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');
    $("#fecha_caducidad").datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');

    // datos tabla
    var can;
    jQuery("#list").jqGrid({
        datatype: "local",
        colNames: ['', 'ID', 'Còdigo', 'Producto', 'Cantidad', 'PVPx', 'Descuentox', 'Calculadox', 'Totalx', 'PVP', 'Descuento', 'Calculado', 'Total', 'Iva', 'Pendientes', 'Incluye', 'C. Costo', 'id_c_costo'],
        colModel: [
            { name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: { keys: false, delbutton: true, editbutton: false } },
            { name: 'cod_producto', index: 'cod_producto', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },
            { name: 'codigo', index: 'codigo', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'detalle', index: 'detalle', editable: false, frozen: true, editrules: { required: true }, align: 'center', width: 290 },

            { name: 'cantidad', index: 'cantidad', editable: true, frozen: true, editrules: { required: true }, align: 'center', width: 70 },
            {
                name: 'precio_u', index: 'precio_u', hidden: true, editable: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: 110, editoptions: {
                    maxlength: 10, size: 15, dataInit: function (elem) {
                        $(elem).bind("keypress", function (e) {
                            return punto(e)
                        })
                    }
                }
            },
            { name: 'descuento', index: 'descuento', hidden: true, editable: false, frozen: true, editrules: { required: true }, align: 'center', width: 90 },
            { name: 'cal_des', index: 'cal_des', hidden: true, editable: false, hidden: true, frozen: true, editrules: { required: true }, align: 'center', width: 90 },
            { name: 'total', index: 'total', hidden: true, editable: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: 150 },
            {
                name: 'precio_ux', index: 'precio_ux', editable: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: 110, editoptions: {
                    maxlength: 10, size: 15, dataInit: function (elem) {
                        $(elem).bind("keypress", function (e) {
                            return punto(e)
                        })
                    }
                }
            },
            { name: 'descuentox', index: 'descuentox', editable: false, frozen: true, editrules: { required: true }, align: 'center', width: 90 },
            { name: 'cal_desx', index: 'cal_desx', editable: false, hidden: true, frozen: true, editrules: { required: true }, align: 'center', width: 90 },
            { name: 'totalx', index: 'totalx', editable: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: 150 },
            { name: 'iva', index: 'iva', align: 'center', width: 100, hidden: true },
            { name: 'pendiente', index: 'pendiente', editable: false, frozen: true, editrules: { required: true }, align: 'center', width: 90 },
            { name: 'incluye', index: 'incluye', editable: false, hidden: true, frozen: true, editrules: { required: true }, align: 'center', width: 90 },
            {
                name: "centro_costo", index: "centro_costo", search: false, frozen: true
            },
            {
                name: "id_centro_costo", index: "id_centro_costo", search: false, frozen: true, hidden: true
            }
        ],
        rowNum: 30,
        width: null,
        height: 300,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager'),
        sortname: 'cod_producto',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'clientArray',
        shrinkToFit: true,
        editoptions: {
            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {
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
                var suma_total = 0;

                var fil = jQuery("#list").jqGrid("getRowData");
                for (var t = 0; t < fil.length; t++) {
                    if (ret.iva == "Si") {
                        if (ret.incluye == "No") {
                            subtotal = ret.total;
                            sub1 = subtotal;
                            iva1 = sub1 * (calculoIVA / 100);

                            subtotal0 = parseFloat($("#total_p").val()) + 0;
                            subtotal12 = parseFloat($("#total_p2").val()) - parseFloat(sub1);
                            subtotal_total = parseFloat($("#sub").val()) - parseFloat(sub1);
                            iva12 = parseFloat($("#iva").val()) - parseFloat(iva1);
                            descu_total = parseFloat($("#desc").val()) - parseFloat(ret.cal_des);

                            subtotal0 = parseFloat(subtotal0);
                            subtotal12 = parseFloat(subtotal12);
                            subtotal_total = parseFloat(subtotal_total);
                            iva12 = parseFloat(iva12);
                            descu_total = parseFloat(descu_total);
                            suma_total = parseFloat($("#num").val()) - parseFloat(ret.cantidad);
                        } else {
                            if (ret.incluye == "Si") {
                                subtotal = ret.total;
                                sub2 = subtotal / ((calculoIVA / 100) + 1);
                                iva2 = sub2 * (calculoIVA / 100);

                                subtotal0 = parseFloat($("#total_p").val()) + 0;
                                subtotal12 = parseFloat($("#total_p2").val()) - parseFloat(sub2);
                                subtotal_total = parseFloat($("#sub").val()) - parseFloat(sub2);
                                iva12 = parseFloat($("#iva").val()) - parseFloat(iva2);
                                descu_total = parseFloat($("#desc").val()) - parseFloat(ret.cal_des);

                                subtotal0 = parseFloat(subtotal0);
                                subtotal12 = parseFloat(subtotal12);
                                subtotal_total = parseFloat(subtotal_total);
                                iva12 = parseFloat(iva12);
                                descu_total = parseFloat(descu_total);
                                suma_total = parseFloat($("#num").val()) - parseFloat(ret.cantidad);
                            }
                        }
                    } else {
                        if (ret.iva == "No") {
                            subtotal = ret.total;
                            sub = subtotal;

                            subtotal0 = parseFloat($("#total_p").val()) - parseFloat(sub);
                            subtotal12 = parseFloat($("#total_p2").val()) + 0;
                            subtotal_total = parseFloat($("#sub").val()) - parseFloat(sub);
                            iva12 = parseFloat($("#iva").val()) + 0;
                            descu_total = parseFloat($("#desc").val()) - parseFloat(ret.cal_des);

                            subtotal0 = parseFloat(subtotal0);
                            subtotal12 = parseFloat(subtotal12);
                            subtotal_total = parseFloat(subtotal_total);
                            iva12 = parseFloat(iva12);
                            descu_total = parseFloat(descu_total);
                            suma_total = parseFloat($("#num").val()) - parseFloat(ret.cantidad);
                        }
                    }
                }

                total_total = parseFloat(total_total) + (parseFloat(subtotal0) + parseFloat(subtotal12) + parseFloat(iva12));
                total_total = parseFloat(total_total);

                var item = fil.length - 1;
                $("#total_p").val(subtotal0);
                $("#total_p2").val(subtotal12);
                $("#sub").val(subtotal_total);
                $("#iva").val(iva12);
                $("#desc").val(descu_total);
                $("#tot").val(total_total);
                $("#total_px").val(subtotal0.toFixed(2));
                $("#total_p2x").val(subtotal12.toFixed(2));
                $("#subx").val(subtotal_total.toFixed(2));
                $("#ivax").val(iva12.toFixed(2));
                $("#descx").val(descu_total.toFixed(2));
                $("#totx").val(total_total.toFixed(2));
                $("#items").val(item);
                $("#num").val(suma_total);

                var su = jQuery("#list").jqGrid('delRowData', rowid);
                if (su === true) {
                    rp_ge.processing = true;
                    $(".ui-icon-closethick").trigger('click');
                }
                return true;
            },
            processing: true
        },
        delOptions: {
            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {
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
                var suma_total = 0;

                var fil = jQuery("#list").jqGrid("getRowData");
                for (var t = 0; t < fil.length; t++) {
                    if (ret.iva == "Si") {
                        if (ret.incluye == "No") {
                            subtotal = ret.total;
                            sub1 = subtotal;
                            iva1 = sub1 * (calculoIVA / 100);

                            subtotal0 = parseFloat($("#total_p").val()) + 0;
                            subtotal12 = parseFloat($("#total_p2").val()) - parseFloat(sub1);
                            subtotal_total = parseFloat($("#sub").val()) - parseFloat(sub1);
                            iva12 = parseFloat($("#iva").val()) - parseFloat(iva1);
                            descu_total = parseFloat($("#desc").val()) - parseFloat(ret.cal_des);

                            subtotal0 = parseFloat(subtotal0);
                            subtotal12 = parseFloat(subtotal12);
                            subtotal_total = parseFloat(subtotal_total);
                            iva12 = parseFloat(iva12);
                            descu_total = parseFloat(descu_total);
                            suma_total = parseFloat($("#num").val()) - parseFloat(ret.cantidad);
                        } else {
                            if (ret.incluye == "Si") {
                                subtotal = ret.total;
                                sub2 = subtotal / ((calculoIVA / 100) + 1);
                                iva2 = sub2 * (calculoIVA / 100);

                                subtotal0 = parseFloat($("#total_p").val()) + 0;
                                subtotal12 = parseFloat($("#total_p2").val()) - parseFloat(sub2);
                                subtotal_total = parseFloat($("#sub").val()) - parseFloat(sub2);
                                iva12 = parseFloat($("#iva").val()) - parseFloat(iva2);
                                descu_total = parseFloat($("#desc").val()) - parseFloat(ret.cal_des);

                                subtotal0 = parseFloat(subtotal0);
                                subtotal12 = parseFloat(subtotal12);
                                subtotal_total = parseFloat(subtotal_total);
                                iva12 = parseFloat(iva12);
                                descu_total = parseFloat(descu_total);
                                suma_total = parseFloat($("#num").val()) - parseFloat(ret.cantidad);
                            }
                        }
                    } else {
                        if (ret.iva == "No") {
                            subtotal = ret.total;
                            sub = subtotal;

                            subtotal0 = parseFloat($("#total_p").val()) - parseFloat(sub);
                            subtotal12 = parseFloat($("#total_p2").val()) + 0;
                            subtotal_total = parseFloat($("#sub").val()) - parseFloat(sub);
                            iva12 = parseFloat($("#iva").val()) + 0;
                            descu_total = parseFloat($("#desc").val()) - parseFloat(ret.cal_des);

                            subtotal0 = parseFloat(subtotal0);
                            subtotal12 = parseFloat(subtotal12);
                            subtotal_total = parseFloat(subtotal_total);
                            iva12 = parseFloat(iva12);
                            descu_total = parseFloat(descu_total);
                            suma_total = parseFloat($("#num").val()) - parseFloat(ret.cantidad);
                        }
                    }
                }

                total_total = parseFloat(total_total) + (parseFloat(subtotal0) + parseFloat(subtotal12) + parseFloat(iva12));
                total_total = parseFloat(total_total);

                var item = fil.length - 1;
                $("#total_p").val(subtotal0);
                $("#total_p2").val(subtotal12);
                $("#sub").val(subtotal_total);
                $("#iva").val(iva12);
                $("#desc").val(descu_total);
                $("#tot").val(total_total);
                $("#total_px").val(subtotal0.toFixed(2));
                $("#total_p2x").val(subtotal12.toFixed(2));
                $("#subx").val(subtotal_total.toFixed(2));
                $("#ivax").val(iva12.toFixed(2));
                $("#descx").val(descu_total.toFixed(2));
                $("#totx").val(total_total.toFixed(2));
                $("#items").val(item);
                $("#num").val(suma_total.toFixed(2));

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
                    precio = (parseFloat(precio_grid));
                    multi = (parseFloat(val) * parseFloat(precio));
                    descuento = ((multi * parseFloat(desc)) / 100);
                    flotante = parseFloat(descuento);
                    resultado = (Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2));
                    total = (multi - resultado);
                    jQuery("#list").jqGrid('setRowData', rowid, { total: total, cal_des: resultado });
                } else {
                    desc = descuento_grid;
                    precio = (parseFloat(precio_grid));
                    multi = (parseFloat(val) * parseFloat(precio));
                    descuento = ((multi * parseFloat(desc)) / 100);
                    flotante = parseFloat(descuento);
                    resultado = (Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2));
                    total = (parseFloat(multi));
                    jQuery("#list").jqGrid('setRowData', rowid, { total: total });
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
                    jQuery("#list").jqGrid('setRowData', rowid, { total: total, cal_des: resultado });
                } else {
                    desc = descuento_grid;
                    precio = parseFloat(val);
                    multi = parseFloat(cantidad_grid) * parseFloat(precio);
                    descuento = ((multi * parseFloat(desc)) / 100);
                    flotante = parseFloat(descuento);
                    resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                    total = parseFloat(multi);
                    jQuery("#list").jqGrid('setRowData', rowid, { total: total });
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

        }
    });

    // buscador facturas ventas  
    jQuery("#list2").jqGrid({
        url: 'xmlBuscarliquidacion_compra.php',
        datatype: 'xml',
        colNames: ['ID', 'IDENTIFICACIÒN', 'CLIENTE', 'FACTURA NRO.', 'MONTO TOTAL', 'FECHA'],
        colModel: [

            { name: 'id_liquidacion_compra', index: 'id_liquidacion_compra', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },

            { name: 'identificacion', index: 'identificacion', editable: false, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 150 },
            { name: 'nombres_cli', index: 'nombres_cli', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 200 },
            { name: 'num_factura', index: 'num_factura', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 200 },
            { name: 'total_venta', index: 'total_venta', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'fecha_actual', index: 'fecha_actual', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
        ],
        rowNum: 30,
        width: 750,
        height: 220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager2'),
        sortname: 'id_liquidacion_compra',
        sortorder: 'asc',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
            jQuery('#list2').jqGrid('restoreRow', id);

            if (id) {
                var ret = jQuery("#list2").jqGrid('getRowData', id);
                var valor = ret.id_liquidacion_compra;

                /////////////agregregar datos factura////////
                $("#comprobante").val(valor);
                $("#btnGuardar").attr("disabled", true);
                //            $("#btnGuardarTemporal").attr("disabled", true);

                // $("#num_factura").attr("disabled", true);
                $("#id_proveedor").val("");
                $("#ruc_ci").val("");
                $("#nombre_cliente").val("");
                $("#telefono_cliente").val("");
                $("#correo").val("");

                $("#codigo_barras").attr("disabled", true);
                $("#codigo").attr("disabled", true);
                $("#producto").attr("disabled", true);
                $("#cantidad").attr("disabled", true);
                $("#p_venta").attr("disabled", true);
                $("#descuento").attr("disabled", true);

                $("#tipo_venta").val("FACTURA");
                $("#estado h3").remove();
                $("#formaspago").val("Contado");
                $("#adelanto").val("");
                $("#meses").val("");
                $('#cuotas').children().remove().end();

                $("#list").jqGrid("clearGridData", true);
                $("#total_p").val("0.000");
                $("#total_p2").val("0.000");
                $("#iva").val("0.000");
                $("#desc").val("0.000");
                $("#tot").val("0.000");
                $("#total_px").val("0.000");
                $("#total_p2x").val("0.000");
                $("#ivax").val("0.000");
                $("#descx").val("0.000");
                $("#totx").val("0.000");

                $.getJSON('retornar_liquidacion_compra.php?com=' + valor, function (data) {
                    var tama = data.length;
                    t = data[23];
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 25) {
                            $("#id_liquidacion_compra").val(data[i]);

                            $("#fecha_auto").val(data[i + 1]);
                            $("#hora_actual").val(data[i + 2]);
                            $("#digitador").val(data[i + 3] + " " + data[i + 4]);
                            var num = data[i + 5];
                            var res = num.substr(8, 20)
                            $("#num_factura").val(res);
                            $("#id_proveedor").val(data[i + 6]);
                            $("#ruc_ci").val(data[i + 7]);
                            $("#nombre_cliente").val(data[i + 8]);
                            $("#direccion_cliente").val(data[i + 9]);
                            $("#telefono_cliente").val(data[i + 10]);
                            $("#correo").val(data[i + 11]);
                            $("#autorizacion").val(data[i + 12]);
                            $("#fecha_auto").val(data[i + 13]);
                            $("#fecha_caducidad").val(data[i + 14]);
                            $("#cancelacion").val(data[i + 15]);
                            $("#tipo_precio_venta").val(data[i + 16]);

                            if (data[i + 17] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulada"));
                                $("#estado h3").css("color", "red");
                                $("#btnAnular").attr("disabled", "disabled");
                                $("#btnModificar").attr("disabled", true);
                            } else {
                                $("#estado h3").remove();
                                $("#btnAnular").attr("disabled", "disabled");
                                $("#btnAnular").attr("disabled", false);
                                $("#btnModificar").attr("disabled", false);
                                if (data[i + 23] == '0') {
                                    $("#btnModificar").attr("disabled", false);
                                    $("#btnGuardar").attr("disabled", false);
                                    $("#codigo_barras").attr("disabled", false);
                                    $("#codigo").attr("disabled", false);
                                    $("#producto").attr("disabled", false);
                                    $("#cantidad").attr("disabled", false);
                                    $("#p_venta").attr("disabled", false);
                                    $("#descuento").attr("disabled", false);

                                    $("#formaspago").attr("disabled", false);
                                } else if (data[i + 23] == '1') {
                                    $("#btnModificar").attr("disabled", "disabled");
                                }
                            }

                            $("#total_p").val(data[i + 18]);
                            $("#total_p2").val(data[i + 19]);
                            $("#sub").val(parseFloat(data[i + 18]) + parseFloat(data[i + 19]));
                            $("#iva").val(data[i + 20]);
                            $("#desc").val(data[i + 21]);
                            $("#tot").val(data[i + 22]);
                            $("#total_px").val(parseFloat(data[i + 18]).toFixed(2));
                            $("#total_p2x").val(parseFloat(data[i + 19]).toFixed(2));
                            $("#subx").val((parseFloat(data[i + 18]) + parseFloat(data[i + 19])).toFixed(2));
                            $("#ivax").val(parseFloat(data[i + 20]).toFixed(2));
                            $("#descx").val(parseFloat(data[i + 21]).toFixed(2));
                            $("#totx").val(parseFloat(data[i + 22]).toFixed(2));
                            $("#num_liquidacion").val(data[i + 24]);
                        }
                        //                    volver_rf();
                        //                    volver_ri();
                    }
                });

                $.getJSON('retornar_liquidacion_compra2.php?com=' + valor, function (data) {
                    var tama = data.length;
                    var descuento = 0;
                    var total = 0;
                    var su = 0;
                    var precio = 0;
                    var multi = 0;
                    var flotante = 0;
                    var resultado = 0;
                    var suma_total = 0;

                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 10) {
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
                                pendiente: data[i + 8],
                                incluye: data[i + 9]
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                            suma_total = suma_total + parseFloat(data[i + 3]);
                        }
                        var fila = jQuery("#list").jqGrid("getRowData");
                        $("#items").val(fila.length);
                        $("#num").val(suma_total);
                    }
                });
                $("#buscar_facturas_venta").dialog("close");
                $("#tipo_busqueda").dialog("close");
            } else {
                alertify.alert("Seleccione una Factura");
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
        caption: "AÃ±adir",
        onClickButton: function () {
            var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
            jQuery('#list2').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list2").jqGrid('getRowData', id);
                var valor = ret.id_liquidacion_compra;
                /////////////agregregar datos factura////////
                $("#comprobante").val(valor);
                $("#btnGuardar").attr("disabled", true);

                // $("#num_factura").attr("disabled", true);
                $("#id_proveedor").val("");
                $("#ruc_ci").val("");
                $("#nombre_cliente").val("");
                $("#telefono_cliente").val("");
                $("#correo").val("");

                $("#codigo_barras").attr("disabled", true);
                $("#codigo").attr("disabled", true);
                $("#producto").attr("disabled", true);
                $("#cantidad").attr("disabled", true);
                $("#p_venta").attr("disabled", true);
                $("#descuento").attr("disabled", true);

                $("#tipo_venta").val("FACTURA");
                $("#estado h3").remove();
                $("#formaspago").val("Contado");
                $("#adelanto").val("");
                $("#meses").val("");
                $('#cuotas').children().remove().end();

                $("#list").jqGrid("clearGridData", true);
                $("#total_p").val("0.000");
                $("#total_p2").val("0.000");
                $("#iva").val("0.000");
                $("#desc").val("0.000");
                $("#tot").val("0.000");
                $("#total_px").val("0.000");
                $("#total_p2x").val("0.000");
                $("#ivax").val("0.000");
                $("#descx").val("0.000");
                $("#totx").val("0.000");

                $.getJSON('retornar_liquidacion_compra.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 25) {
                            $("#id_liquidacion_compra").val(data[i]);
                            $("#fecha_auto").val(data[i + 1]);
                            $("#hora_actual").val(data[i + 2]);
                            $("#digitador").val(data[i + 3] + " " + data[i + 4]);
                            var num = data[i + 5];
                            var res = num.substr(8, 20)
                            $("#num_factura").val(res);
                            $("#id_proveedor").val(data[i + 6]);
                            $("#ruc_ci").val(data[i + 7]);
                            $("#nombre_cliente").val(data[i + 8]);
                            $("#direccion_cliente").val(data[i + 9]);
                            $("#telefono_cliente").val(data[i + 10]);
                            $("#correo").val(data[i + 11]);
                            $("#autorizacion").val(data[i + 12]);
                            $("#fecha_auto").val(data[i + 13]);
                            $("#fecha_caducidad").val(data[i + 14]);
                            $("#cancelacion").val(data[i + 15]);
                            $("#tipo_precio_venta").val(data[i + 16]);

                            if (data[i + 17] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulada"));
                                $("#estado h3").css("color", "red");
                                $("#btnAnular").attr("disabled", "disabled");
                                $("#btnModificar").attr("disabled", true);
                            } else {
                                $("#estado h3").remove();
                                $("#btnAnular").attr("disabled", "disabled");
                                $("#btnAnular").attr("disabled", false);
                                $("#btnModificar").attr("disabled", false);
                            }

                            $("#total_p").val(data[i + 18]);
                            $("#total_p2").val(data[i + 19]);
                            $("#sub").val(parseFloat(data[i + 18]) + parseFloat(data[i + 19]));
                            $("#iva").val(data[i + 20]);
                            $("#desc").val(data[i + 21]);
                            $("#tot").val(data[i + 22]);
                            $("#total_px").val(parseFloat(data[i + 18]).toFixed(2));
                            $("#total_p2x").val(parseFloat(data[i + 19]).toFixed(2));
                            $("#subx").val((parseFloat(data[i + 18]) + parseFloat(data[i + 19])).toFixed(2));
                            $("#ivax").val(parseFloat(data[i + 20]).toFixed(2));
                            $("#descx").val(parseFloat(data[i + 21]).toFixed(2));
                            $("#totx").val(parseFloat(data[i + 22]).toFixed(2));
                            $("#num_liquidacion").val(data[i + 24]);
                        }
                    }
                });



                $.getJSON('retornar_liquidacion_compra2.php?com=' + valor, function (data) {
                    var tama = data.length;
                    var descuento = 0;
                    var total = 0;
                    var su = 0;
                    var precio = 0;
                    var multi = 0;
                    var flotante = 0;
                    var resultado = 0;
                    var suma_total = 0;

                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 10) {
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
                                pendiente: data[i + 8],
                                incluye: data[i + 9]
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                            suma_total = suma_total + parseFloat(data[i + 3]);
                        }
                        var fila = jQuery("#list").jqGrid("getRowData");
                        $("#items").val(fila.length);
                        $("#num").val(suma_total);
                    }
                });
                $("#buscar_facturas_venta").dialog("close");
                $("#tipo_busqueda").dialog("close");
            } else {
                alertify.alert("Seleccione una Factura");
            }
        }
    });
    // fin tabla

    jQuery("#list5").jqGrid('navButtonAdd', '#pager5', {
        caption: "AÃ±adir",
        onClickButton: function () {
            var id = jQuery("#list5").jqGrid('getGridParam', 'selrow');
            jQuery('#list5').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list5").jqGrid('getRowData', id);
                var valor = ret.id_facturas_novalidas;

                // agregregar datos nota venta
                $("#comprobante").val(valor);
                $("#btnGuardar").attr("disabled", true);
                $("#btnModificar").attr("disabled", true);

                // $("#num_factura").attr("disabled", "disabled");
                $("#ruc_ci").attr("disabled", "disabled");
                $("#nombre_cliente").attr("disabled", "disabled");
                $("#direccion_cliente").attr("disabled", "disabled");
                //      $("#telefono_cliente").attr("disabled", "disabled");
                //      $("#correo").attr("disabled", "disabled");
                $("#formaspago").attr("disabled", true);
                $("#ruc_ci").val("");
                $("#nombre_cliente").val("");
                $("#telefono_cliente").val("");
                $("#correo").val("");

                $("#tipo_venta").val("NOTA");
                $("#codigo").attr("disabled", "disabled");
                $("#producto").attr("disabled", "disabled");
                $("#cantidad").attr("disabled", "disabled");
                $("#p_venta").attr("disabled", "disabled");
                $("#btncargar").attr("disabled", "disabled");
                $("#autorizacion").attr("disabled", "disabled");
                $("#estado h3").remove();
                $("#formaspago").val("Contado");
                $("#adelanto").val("");
                $("#meses").val("");
                $('#cuotas').children().remove().end();
                $("#cuotas").attr("disabled", true);

                $("#list").jqGrid("clearGridData", true);
                $("#total_p").val("0.000");
                $("#total_p2").val("0.000");
                $("#iva").val("0.000");
                $("#desc").val("0.000");
                $("#tot").val("0.000");
                $("#total_px").val("0.000");
                $("#total_p2x").val("0.000");
                $("#ivax").val("0.000");
                $("#descx").val("0.000");
                $("#totx").val("0.000");

                // fin 

                // llamar facturas no validas

                $("#buscar_notas_venta").dialog("close");
                $("#tipo_busqueda").dialog("close");
            } else {
                alertify.alert("Seleccione una Factura");
            }
        }
    });
    // fin

    ////////////tabla series//////////////////////////////
    jQuery("#list3").jqGrid({
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
        pager: jQuery('#pager3'),
        sortname: 'id_series',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'clientArray',
        shrinkToFit: true,
        delOptions: {
            onclickSubmit: function (rp_ge, rowid) {
                rp_ge.processing = true;
                var su = jQuery("#list3").jqGrid('delRowData', rowid);
                if (su === true) {
                    $("#delmodlist3").hide();
                    $(".ui-icon-closethick").trigger('click');
                }
                return true;
            },
            processing: true
        }
    }).jqGrid('navGrid', '#pager3',
        {
            add: false,
            edit: false,
            del: false,
            refresh: true,
            search: true,
            view: true
        });
    ///////////////////////////////////////////


    jQuery("#list4").jqGrid('navButtonAdd', '#pager4', {
        caption: "AÃ±adir",
        onClickButton: function () {
            var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
            jQuery('#list4').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list2").jqGrid('getRowData', id);
                var valor = ret.id_liquidacion_compra;
                /////////////agregregar datos factura////////
                $("#comprobante").val(valor);
                $("#btnGuardar").attr("disabled", true);
                // $("#num_factura").attr("disabled", "disabled");
                $("#ruc_ci").attr("disabled", "disabled");
                $("#nombre_cliente").attr("disabled", "disabled");
                $("#direccion_cliente").attr("disabled", "disabled");
                //        $("#telefono_cliente").attr("disabled", "disabled");
                //        $("#correo").attr("disabled", "disabled");
                $("#formaspago").attr("disabled", true);
                $("#ruc_ci").val("");
                $("#nombre_cliente").val("");
                $("#telefono_cliente").val("");
                $("#correo").val("");
                $("#codigo").attr("disabled", "disabled");
                $("#producto").attr("disabled", "disabled");
                $("#cantidad").attr("disabled", "disabled");
                $("#p_venta").attr("disabled", "disabled");
                $("#btncargar").attr("disabled", "disabled");
                $("#autorizacion").attr("disabled", "disabled");
                $("#estado h3").remove();
                $("#formaspago").val("Contado");
                $("#adelanto").val("");
                $("#meses").val("");
                $('#cuotas').children().remove().end();
                $("#cuotas").attr("disabled", true);
                $("#list").jqGrid("clearGridData", true);
                $("#total_p").val("0.000");
                $("#total_p2").val("0.000");
                $("#iva").val("0.000");
                $("#desc").val("0.000");
                $("#tot").val("0.000");
                $("#total_px").val("0.000");
                $("#total_p2x").val("0.000");
                $("#ivax").val("0.000");
                $("#descx").val("0.000");
                $("#totx").val("0.000");

                $.getJSON('../procesos/retornar_liquidacion_compra.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 19) {
                            $("#fecha_actual").val(data[i]);
                            $("#hora_actual").val(data[i + 1]);
                            $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                            var num = data[i + 4];
                            var res = num.substr(8, 20)
                            $("#num_factura").val(res);
                            $("#id_proveedor").val(data[i + 5]);
                            $("#ruc_ci").val(data[i + 6]);
                            $("#nombre_cliente").val(data[i + 7]);
                            $("#direccion_cliente").val(data[i + 8]);
                            $("#telefono_cliente").val(data[i + 9]);
                            $("#correo").val(data[i + 10]);
                            $("#cancelacion").val(data[i + 11]);

                            $("#tipo_precio_venta").val(data[i + 12]);
                            if (data[i + 13] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulada"));
                                $("#estado h3").css("color", "red");
                                $("#btnAnular").attr("disabled", "disabled");
                            } else {
                                $("#estado h3").remove();
                                $("#btnAnular").attr("disabled", "disabled");
                                $("#btnAnular").attr("disabled", false);
                            }

                            $("#total_p").val(data[i + 14]);
                            $("#total_p2").val(data[i + 15]);
                            $("#iva").val(data[i + 16]);
                            $("#desc").val(data[i + 17]);
                            $("#tot").val(data[i + 18]);
                            $("#total_px").val(parseFloat(data[i + 14]).toFixed(2));
                            $("#total_p2x").val(parseFloat(data[i + 15]).toFixed(2));
                            $("#ivax").val(parseFloat(data[i + 16]).toFixed(2));
                            $("#descx").val(parseFloat(data[i + 17]).toFixed(2));
                            $("#totx").val(parseFloat(data[i + 18]).toFixed(2));
                        }
                    }
                });
                ///////////////////////////////////////////////////   

                ////////////////////llamar facturas flechas tercera parte/////
                $.getJSON('../procesos/retornar_liquidacion_compra2.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 9) {
                            var datarow = {
                                cod_producto: data[i],
                                codigo: data[i + 1],
                                detalle: data[i + 2],
                                cantidad: data[i + 3],
                                precio_u: data[i + 4],
                                descuento: data[i + 5],
                                total: data[i + 6],
                                precio_ux: parseFloat(data[i + 4]).toFixed(2),
                                descuentox: parseFloat(data[i + 5]).toFixed(2),
                                totalx: parseFloat(data[i + 6]).toFixed(2),
                                iva: data[i + 7],
                                pendiente: data[i + 8]
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });
                $("#buscar_facturas_venta").dialog("close");
            } else {
                alertify.alert("Seleccione una Factura");
            }
        }
    });

    jQuery("#list6").jqGrid('navButtonAdd', '#pager6', {
        caption: "Aniadir",
        onClickButton: function () {
            var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
            jQuery('#list6').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list2").jqGrid('getRowData', id);
                var valor = ret.id_liquidacion_compra;
                /////////////agregregar datos factura////////
                $("#comprobante").val(valor);
                $("#btnGuardar").attr("disabled", true);
                // $("#num_factura").attr("disabled", "disabled");
                $("#ruc_ci").attr("disabled", "disabled");
                $("#nombre_cliente").attr("disabled", "disabled");
                $("#direccion_cliente").attr("disabled", "disabled");
                //            $("#telefono_cliente").attr("disabled", "disabled");
                //            $("#correo").attr("disabled", "disabled");
                $("#formaspago").attr("disabled", true);
                $("#ruc_ci").val("");
                $("#nombre_cliente").val("");
                $("#telefono_cliente").val("");
                $("#correo").val("");
                $("#codigo").attr("disabled", "disabled");
                $("#producto").attr("disabled", "disabled");
                $("#cantidad").attr("disabled", "disabled");
                $("#p_venta").attr("disabled", "disabled");
                $("#btncargar").attr("disabled", "disabled");
                $("#autorizacion").attr("disabled", "disabled");
                $("#estado h3").remove();
                $("#formaspago").val("Contado");
                $("#adelanto").val("");
                $("#meses").val("");
                $('#cuotas').children().remove().end();
                $("#cuotas").attr("disabled", true);
                $("#list").jqGrid("clearGridData", true);
                $("#total_p").val("0.000");
                $("#total_p2").val("0.000");
                $("#iva").val("0.000");
                $("#desc").val("0.000");
                $("#tot").val("0.000");
                $("#total_px").val("0.000");
                $("#total_p2x").val("0.000");
                $("#ivax").val("0.000");
                $("#descx").val("0.000");
                $("#totx").val("0.000");

                $.getJSON('../procesos/retornar_liquidacion_compra.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 19) {
                            $("#fecha_actual").val(data[i]);
                            $("#hora_actual").val(data[i + 1]);
                            $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                            var num = data[i + 4];
                            var res = num.substr(8, 20)
                            $("#num_factura").val(res);
                            $("#id_proveedor").val(data[i + 5]);
                            $("#ruc_ci").val(data[i + 6]);
                            $("#nombre_cliente").val(data[i + 7]);
                            $("#direccion_cliente").val(data[i + 8]);
                            $("#telefono_cliente").val(data[i + 9]);
                            $("#correo").val(data[i + 10]);
                            $("#cancelacion").val(data[i + 11]);

                            $("#tipo_precio_venta").val(data[i + 12]);
                            if (data[i + 13] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulada"));
                                $("#estado h3").css("color", "red");
                                $("#btnAnular").attr("disabled", "disabled");
                            } else {
                                $("#estado h3").remove();
                                $("#btnAnular").attr("disabled", "disabled");
                                $("#btnAnular").attr("disabled", false);
                            }

                            $("#total_p").val(data[i + 14]);
                            $("#total_p2").val(data[i + 15]);
                            $("#iva").val(data[i + 16]);
                            $("#desc").val(data[i + 17]);
                            $("#tot").val(data[i + 18]);
                            $("#total_px").val(parseFloat(data[i + 14]).toFixed(2));
                            $("#total_p2x").val(parseFloat(data[i + 15]).toFixed(2));
                            $("#ivax").val(parseFloat(data[i + 16]).toFixed(2));
                            $("#descx").val(parseFloat(data[i + 17]).toFixed(2));
                            $("#totx").val(parseFloat(data[i + 18]).toFixed(2));
                        }
                    }
                });
                ///////////////////////////////////////////////////   

                ///////////////////llamar facturas flechas segunda parte/////

                /////////////////////////////////////////////////////////

                ////////////////////llamar facturas flechas tercera parte/////
                $.getJSON('../procesos/retornar_liquidacion_compra2.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 9) {
                            var datarow = {
                                cod_producto: data[i],
                                codigo: data[i + 1],
                                detalle: data[i + 2],
                                cantidad: data[i + 3],
                                precio_u: data[i + 4],
                                descuento: data[i + 5],
                                total: data[i + 6],
                                precio_ux: parseFloat(data[i + 4]).toFixed(2),
                                descuentox: parseFloat(data[i + 5]).toFixed(2),
                                totalx: parseFloat(data[i + 6]).toFixed(2),
                                iva: data[i + 7],
                                pendiente: data[i + 8]
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });
                $("#buscar_facturas_venta").dialog("close");
            } else {
                alertify.alert("Seleccione una Factura");
            }
        }
    });

    jQuery(window).bind('resize', function () {
        jQuery("#list").setGridWidth(jQuery('#grid_container').width(), true);
    }).trigger('resize');

    $("#codigo_barras").focus();

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


    ////////////////////buscador proformas tecnico/////////////////////////
    jQuery("#list7").jqGrid({
        url: 'xmlBuscarEstados.php',
        datatype: 'xml',
        colNames: ['ID', 'NUM AUTORIZACIÒN', 'FECHA EMISIÒN', 'RAZÒN SOCIAL', 'CORREO ', 'FECHA AUTORIZACIÒN', 'TOTAL', 'ESTADO', 'ENVIO CORREO', 'ENVIO XML', 'CONSULTA COMPROBANTE'],
        colModel: [
            { name: 'id_liquidacion_compra', index: 'id_liquidacion_compra', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },
            { name: 'autorizacion', index: 'autorizacion', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'fecha_emision', index: 'fecha_emision', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'razon_social', index: 'razon_social', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'correo', index: 'correo', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'fecha_autorizacion', index: 'fecha_autorizacion', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'total', index: 'total', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },
            { name: 'estado', index: 'estado', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },
            { name: 'accion', index: 'accion', editable: false, hidden: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: '80px' },
            { name: 'envio', index: 'envio', editable: false, hidden: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: '80px' },
            { name: 'reenvio', index: 'reenvio', editable: false, hidden: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: '80px' },
        ],
        rowNum: 30,
        width: 1000,
        height: 220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager7'),
        sortname: 'id_liquidacion_compra',
        sortorder: 'desc',
        viewrecords: true,
        gridComplete: function () {
            var ids = jQuery("#list7").jqGrid('getDataIDs');

            for (var i = 0; i < ids.length; i++) {
                var ids = jQuery("#list7").getDataIDs();

                for (var i = 0; i < ids.length; i++) {
                    var id_factura = ids[i];

                    be = "<a  onclick=\"reenviar('" + id_factura + "')\" title='Reenviar Correo' ><i class='fa fa-envelope-o' style='cursor:pointer; cursor: hand'> CORREO</i></a>";
                    jQuery("#list7").jqGrid('setRowData', ids[i], { accion: be });

                }
            }

            for (var i = 0; i < ids.length; i++) {
                var ids = jQuery("#list7").getDataIDs();

                for (var i = 0; i < ids.length; i++) {
                    var id_factura = ids[i];


                    be = "<a  onclick=\"enviarXml('" + id_factura + "')\" title='Reenviar Xml' ><i class='fa fa-arrow-circle-right' style='cursor:pointer; cursor: hand'> Envio Xml</i></a>";
                    jQuery("#list7").jqGrid('setRowData', ids[i], { envio: be });

                }
            }
            for (var i = 0; i < ids.length; i++) {
                var ids = jQuery("#list7").getDataIDs();

                for (var i = 0; i < ids.length; i++) {
                    var id_factura = ids[i];
                    be = "<a  onclick=\"reenviarXml('" + id_factura + "')\" title='Reenviar Xml' ><i class='fa fa-repeat' style='cursor:pointer; cursor: hand'> Consulta Xml</i></a>";
                    jQuery("#list7").jqGrid('setRowData', ids[i], { reenvio: be });
                }
            }
        },
        ondblClickRow: function () {
            var id = jQuery("#list7").jqGrid('getGridParam', 'selrow');
            jQuery('#list7').jqGrid('restoreRow', id);

        },
    }).jqGrid('navGrid', '#pager7',
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



    jQuery("#list77").jqGrid('navButtonAdd', '#pager77', {
        caption: "Reeviar",
        onClickButton: function () {
            var id = jQuery("#list77").jqGrid('getGridParam', 'selrow');
            jQuery('#list77').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list77").jqGrid('getRowData', id);
            }

        }
    });

    obtenerNumSerieRet();
}

function reenviar(id) {

    $.ajax({
        type: "POST",
        url: "guardar_liquidacion_compra.php",
        data: { reenviarcorreo: 'reenviarcorreo', id: id },
        //        data: "id="+x,
        dataType: "json",
        success: function (data) {

            if (data.estado == 1) {

                alertify.alert("Enviado al Correo: ");

            } else {
                alertify.alert("Error al enviar: ");
            }



        }
    });
}
function reenviarXml(id) {
    $.ajax({
        type: "POST",
        url: "guardar_liquidacion_compra.php",
        data: { reenviarxml: 'reenviarxml', id: id },
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
        url: "guardar_liquidacion_compra.php",
        data: { enviarxml: 'enviarxml', id: id },
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
function cambio_ret_fuenteS() {
    $('#retencionF1').prop('checked', true);

    $('#retencionI1').prop('checked', true);


    if (document.getElementById('retencionF2S').checked) {


        if ($("#id_liquidacion_compra").val() != "") {
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

function cambio_ret_fuente() {
    $('#retencionF1S').prop('checked', true);
    $('#retencionI1').prop('checked', true);

    if (document.getElementById('retencionF2').checked) {
        if ($("#id_liquidacion_compra").val() != "") {
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

function calculo_ret_fuente() {
    document.getElementById("tipoRetencionesFS").selectedIndex = 0;
    $("#calculoRetencionFS").val("0.000");

    document.getElementById("tipoRetencionesI").selectedIndex = 0;
    $("#calculoRetencionI").val("0.000");

    buscar_bienservicio_producto();
    buscar_bien_prima_result();

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
            $("#calculoRetencionF").val(valor);
            $("#porcent_reten").val(calculoRET);
            $("#calculoRetencionF").focus();
        }
    });
}
function cambio_ret_ivas() {
    $('#retencionF1').prop('checked', true);
    $('#retencionI1').prop('checked', true);
    $('#retencionF1S').prop('checked', true);
    if (document.getElementById('retencionI2s').checked) {
        if ($("#id_liquidacion_compra").val() != "") {
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
function cambio_ret_iva() {

    if (document.getElementById('retencionI2').checked) {
        if ($("#id_liquidacion_compra").val() != "") {
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
function calculo_ret_ivas() {
    //    document.getElementById("tipoRetencionesF").selectedIndex = 0;
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

                var calculoservivas = $("#calculoservivas").val() * toFixedDown((calculoIVA / 100), 3);
                //                alertify.alert("El porcentaje de retención es del: " + calculoRET + "%");
                var valor = toFixedDown((((calculoservivas) * calculoRET) / 100), 3);
                $("#calculoRetencionIs").val(valor);
                $("#porcent_ivas").val(calculoRET);
                $("#calculoRetencionIs").focus();
            }
        }
    });
}
function buscar_bien_prima_result() {
    var mora = 0;
    if (document.getElementById('retencionF_prima').checked == true) {
        $.ajax({
            type: "POST",
            url: "buscar_bienservicio_producto_prima.php",
            data: "id=" + $("#id_liquidacion_compra").val(),
            success: function (data) {
                if (data != "") {
                    var valSUM = data;
                    mora = Math.round((valSUM * 0.9) * 100) / 100;
                    $("#calculobienprima1").val(mora);
                } else {

                    alertify.error("NO EXISTE EL PRODUCTO PRIMA");
                }
            }
        });

        $.ajax({
            type: "POST",
            url: "buscar_bienservicio_producto.php",
            data: "id=" + $("#id_liquidacion_compra").val(),
            success: function (data) {
                var valSUM = data;
                $("#calculobienprima2").val(valSUM);
            }
        });

    }
    var result1 = 0;
    var result2 = 0;
    var result_total = 0;
    var calculobienprima_sum = 0;
    result1 = $("#calculobienprima1").val();
    result2 = $("#calculobienprima2").val();
    result_total = parseFloat(result1) + parseFloat(result2);
    console.log(result_total);
    $("#calculobienprima_sum").val(result_total);
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

                var calculoserviva = $("#calculobieniva").val() * toFixedDown((calculoIVA / 100), 3);
                //                alertify.alert("El porcentaje de retención es del: " + calculoRET + "%");
                var valor = toFixedDown((((calculoserviva) * calculoRET) / 100), 3);


                //                alertify.alert("El porcentaje de retención es del: " + calculoRET + "%");

                $("#calculoRetencionI").val(valor);
                $("#porcent_iva").val(calculoRET);
                $("#calculoRetencionI").focus();
            }
        }
    });
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

function addCentroCostoRowData(row) {
    if ($("#sel_centro_costo").val() > 0) {
        row["id_centro_costo"] = $("#sel_centro_costo").val();
        row["centro_costo"] = $("#sel_centro_costo")[0].options[$("#sel_centro_costo")[0].selectedIndex].text;
    }
}

