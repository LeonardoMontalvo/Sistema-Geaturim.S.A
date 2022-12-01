$(document).on("ready", inicio);

var formatoFC = "";
var formatoRC = "";
var num_serie_ret = "";

function obtenerParametrosEmpresa() {
    fetch("obtener_parametros_empresa.php")
        .then(function (d) {
            return d.json();
        })
        .then(function (json) {
            formatoFC = json["formato_imperesion_factura_compra"];
            formatoRC = json["formato_imperesion_retencion_compra"];
        });
}

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

$(document).keydown(function (e) {
    var e = e || event;
    var keycode = e.which || e.keyCode;
    var obj = e.target || e.srcElement;
    // No activar el evento si estamos en un formulario
    //if(obj.tagName.toLowerCase()=="textarea") { return; }
    //if(obj.tagName.toLowerCase()=="input") { return; }
    // Guardar Factura
    //    if(keycode == 118) { guardar_factura()}
    if (keycode == 17) {
        abrirDialogo()
    }
    //    // Tecla Control Cliente
    //   if(keycode == 40) { agregar()}
    //   if(keycode == 39) { guardar_serie()}
    if (keycode == 27) {
        cancelar()
    }
    if (keycode == 13) {
        if ($("#formas").val() == "otros") {
            agregar1()
        }
    }
    // Tecla Control Cliente
    if (keycode == 40) {
        agregar1()
    }
});
$("#btnEstados").click(function () {
    $("#buscar_estados").dialog("open");
});

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

var dialogo = {
    autoOpen: false,
    resizable: false,
    width: 600,
    height: 420,
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
    width: 420,
    height: 220,
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

var dialogo10 = {
    autoOpen: false,
    resizable: false,
    width: 1000,
    height: 350,
    modal: true,
    show: "explode",
    hide: "blind"
}

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
        comprobar();
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
function enter7(e) {
    if (e.which == 13 || e.keyCode == 13) {
        comprobar2reten();
        return false;
    }
    return true;
}
function enter_precio_v(e) {
    if (e.which == 13 || e.keyCode == 13) {
        comprobar_precio_v();
        return false;
    }
    return true;
}
function autocompletar() {
    var temp = "";
    var serie = $("#serie_retencion").val();
    for (var i = serie.length; i < 9; i++) {
        temp = temp + "0";
    }
    return temp;
}
function comprobar_precio_v() {
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
                if ($("#cantidad").val() == "") {
                    $("#cantidad").focus();
                } else {
                    if ($("#cantidad").val() == "0") {
                        $("#cantidad").focus();
                        alertify.error("Ingrese una cantidad vÃ¡lida");
                    } else {
                        if ($("#precio").val() == "") {
                            $("#precio").focus();
                            alertify.error("Ingrese un precio");
                        } else {
                            if ($("#precio_v").val() == "") {
                                $("#precio_v").focus();
                                alertify.error("Ingrese un precio venta");
                            } else {
                                $("#descuento").focus();
                            }
                        }
                    }
                }
            }
        }
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
function funcion_ice_factura() {

    let ice = parseFloat($("#icex").val());
    let irb = parseFloat($("#irbpx").val());
    let nt0 = ice + irb;
    let n1iva = parseFloat($("#tot").val()) + parseFloat(nt0);
    $("#totx").val(numFormatter(2).format(n1iva));
}
function validar_acceso() {
    if ($("#clave").val() == "") {
        $("#clave").focus();
        alertify.alert("Ingrese la clave");
    } else if ($("#observacion").val() == "") {
        $("#observacion").focus();
        alertify.alert("Ingrese la observación");
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
    var datos = {
        id_factura_compra: $("#id_factura_compra").val(),
        observacion: $("#observacion").val()
    }
    if ($("#comprobante").val() == "") {
        alertify.error("Seleccione una factura");
        $("#buscar_facturas_compras").dialog("open");
    } else {
        $.ajax({
            type: "POST",
            url: "eliminar_factura_compra.php",
            data: datos,
            success: function (data) {
                var val = data;
                if (val == 1) {
                    alertify.alert("Factura Eliminada Correctamente", function () {
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

function entrar() {
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
                if ($("#cantidad").val() == "") {
                    $("#cantidad").focus();
                } else {
                    if ($("#cantidad").val() == "0") {
                        $("#cantidad").focus();
                        alertify.error("Ingrese una cantidad vÃ¡lida");
                    } else {
                        $("#precio").focus();
                    }
                }
            }
        }
    }
}

function comprobar1() {
    if ($("#serie_retencion").val() == "") {
        $("#serie_retencion").focus();
        alertify.error("Ingrese nÃºmero de RetenciÃ²n");
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

function comprobar() {
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
                if ($("#cantidad").val() == "") {
                    $("#cantidad").focus();
                } else {
                    if ($("#cantidad").val() == "0") {
                        $("#cantidad").focus();
                        alertify.error("Ingrese una cantidad vÃ¡lida");
                    } else {
                        if ($("#precio").val() == "") {
                            $("#precio").focus();
                            alertify.error("Ingrese un precio");
                        } else {
                            $("#precio_v").focus();
                        }
                    }
                }
            }
        }
    }
}

function limpiar_campos() {
    $("#cod_producto").val("");
    $("#codigo_barras").val("");
    $("#codigo").val("");
    $("#producto").val("");
    $("#cantidad").val("");
    $("#precio").val("");
    $("#descuento").val("");
    $("#iva_producto").val("");
    $("#carga_series").val("");
    $("#incluye").val("");
    $("#tipo_iva").val("Si");
}

function agregarForma() {
    var repe = 0;
    var filas = jQuery("#listPago").jqGrid("getRowData");
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
            alertify.alert("La forma de pago ya estÃ¡ ingresada");
        }
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
                if ($("#cantidad").val() == "") {
                    $("#cantidad").focus();
                    alertify.error("Ingrese una cantidad");
                } else {
                    if ($("#cantidad").val() == "0") {
                        $("#cantidad").focus();
                    } else {
                        if ($("#precio").val() == "") {
                            $("#precio").focus();
                            alertify.error("Ingrese un precio");
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
                                    precio = parseFloat($("#precio").val());
                                    multi = parseFloat($("#cantidad").val()) * parseFloat($("#precio").val());
                                    descuento = ((multi * parseFloat(desc)) / 100);
                                    flotante = parseFloat(descuento);
                                    resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                    total = multi - resultado;
                                } else {
                                    desc = 0;
                                    precio = parseFloat($("#precio").val());
                                    multi = parseFloat($("#cantidad").val()) * parseFloat($("#precio").val());
                                    descuento = ((multi * parseFloat(desc)) / 100);
                                    flotante = parseFloat(descuento);
                                    resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                    total = parseFloat($("#cantidad").val()) * precio;
                                }

                                var datarow = {
                                    cod_producto: $("#cod_producto").val(),
                                    codigo: $("#codigo").val(),
                                    detalle: $("#producto").val(),
                                    cantidad: parseFloat($("#cantidad").val()).toFixed(2),
                                    precio_u: precio,
                                    descuento: desc,
                                    cal_des: resultado,
                                    total: total,
                                    precio_ux: precio.toFixed(4),
                                    descuentox: parseFloat(desc).toFixed(4),
                                    cal_desx: resultado.toFixed(4),
                                    totalx: total.toFixed(4),
                                    iva: $("#iva_producto").val(),
                                    incluye: $("#incluye").val(),
                                    precio_v: $("#precio_v").val()
                                };

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
                                        precio = parseFloat($("#precio").val());
                                        multi = parseFloat(suma) * parseFloat($("#precio").val());
                                        descuento = ((multi * parseFloat(desc)) / 100);
                                        flotante = parseFloat(descuento);
                                        resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                        total = multi - resultado;
                                    } else {
                                        desc = 0;
                                        precio = parseFloat($("#precio").val());
                                        multi = parseFloat($("#cantidad").val()) * parseFloat($("#precio").val());
                                        descuento = ((multi * parseFloat(desc)) / 100);
                                        flotante = parseFloat(descuento);
                                        resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                        total = parseFloat(suma) * precio;
                                    }

                                    datarow = {
                                        cod_producto: $("#cod_producto").val(),
                                        codigo: $("#codigo").val(),
                                        detalle: $("#producto").val(),
                                        cantidad: parseFloat(suma).toFixed(2),
                                        precio_u: precio,
                                        descuento: desc,
                                        cal_des: resultado,
                                        total: total,
                                        precio_ux: precio.toFixed(4),
                                        descuentox: parseFloat(desc).toFixed(4),
                                        cal_desx: resultado.toFixed(4),
                                        totalx: total.toFixed(4),
                                        iva: $("#iva_producto").val(),
                                        incluye: $("#incluye").val(),
                                        precio_v: $("#precio_v").val()
                                    };

                                    su = jQuery("#list").jqGrid('setRowData', $("#cod_producto").val(), datarow);
                                    limpiar_campos();
                                } else {
                                    if ($("#descuento").val() != "") {
                                        desc = $("#descuento").val();
                                        precio = parseFloat($("#precio").val());
                                        multi = parseFloat($("#cantidad").val()) * parseFloat($("#precio").val());
                                        descuento = ((multi * parseFloat(desc)) / 100);
                                        flotante = parseFloat(descuento);
                                        resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                        total = multi - resultado;
                                    } else {
                                        desc = 0;
                                        precio = parseFloat($("#precio").val());
                                        multi = parseFloat($("#cantidad").val()) * parseFloat($("#precio").val());
                                        descuento = ((multi * parseFloat(desc)) / 100);
                                        flotante = parseFloat(descuento);
                                        resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                        total = parseFloat($("#cantidad").val()) * precio;
                                    }

                                    datarow = {
                                        cod_producto: $("#cod_producto").val(),
                                        codigo: $("#codigo").val(),
                                        detalle: $("#producto").val(),
                                        cantidad: parseFloat($("#cantidad").val()).toFixed(2),
                                        precio_u: precio,
                                        descuento: desc,
                                        cal_des: resultado,
                                        total: total,
                                        precio_ux: precio.toFixed(4),
                                        descuentox: parseFloat(desc).toFixed(4),
                                        cal_desx: resultado.toFixed(4),
                                        totalx: total.toFixed(4),
                                        iva: $("#iva_producto").val(),
                                        incluye: $("#incluye").val(),
                                        precio_v: $("#precio_v").val()
                                    };
                                    su = jQuery("#list").jqGrid('addRowData', $("#cod_producto").val(), datarow);
                                    limpiar_campos();
                                }
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
                                    subtotal = dd['total'];
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
                                    descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);
                                    iva12 = parseFloat(iva12) + parseFloat(iva1);

                                    subtotal0 = parseFloat(subtotal0);
                                    subtotal12 = parseFloat(subtotal12);
                                    subtotal_total = parseFloat(subtotal_total);
                                    iva12 = parseFloat(iva12);
                                    descu_total = parseFloat(descu_total);
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
                            $("#tot").val(total_total);
                            $("#total_px").val(subtotal0.toFixed(2));
                            $("#total_p2x").val(subtotal12.toFixed(2));
                            $("#subx").val(subtotal_total.toFixed(2));
                            $("#ivax").val(iva12.toFixed(2));
                            $("#descx").val(descu_total.toFixed(2));
                            $("#totx").val(total_total.toFixed(2));
                            $("#valor_factura").val(total_total.toFixed(2));
                            $("#codigo_barras").focus();
                        }
                    }
                }
            }
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
    if ($("#serie_campos").val() != "") {
        var filas2 = jQuery("#list2").jqGrid("getRowData");
        var su;
        var count = 0;
        var canti = $("#cantidad").val();

        $.ajax({
            type: "POST",
            url: "comparar_series.php",
            data: "serie=" + $("#serie_campos").val(),
            success: function (data) {
                var val = data;
                if (val == 1) {
                    $("#serie_campos").val("");
                    $("#serie_campos").focus();
                    alertify.alert("Error... Serie ya registrada");
                } else {
                    if (filas2.length < canti) {
                        if (filas2.length == 0) {
                            var datarow = {
                                id_serie: count = count + 1,
                                serie_campos: $("#serie_campos").val()
                            };
                            su = jQuery("#list2").jqGrid('addRowData', count, datarow);



                            $("#series_area").val($("#series_area").val() + $("#serie_campos").val() + "\n");
                            $("#serie_campos").val("");
                            $("#serie_campos").focus();
                        } else {
                            var repe = 0;
                            for (var i = 0; i < filas2.length; i++) {
                                var id = filas2[i];
                                if (id['serie_campos'] == $("#serie_campos").val()) {
                                    repe = 1;
                                }
                            }
                            if (repe == 0) {
                                datarow = {
                                    id_serie: count = count + 1,
                                    serie_campos: $("#serie_campos").val()
                                };
                                su = jQuery("#list2").jqGrid('addRowData', count, datarow);

                                $("#series_area").val($("#series_area").val() + $("#serie_campos").val() + "\n");
                                $("#serie_campos").val("");
                                $("#serie_campos").focus();
                            } else {
                                $("#serie_campos").val("");
                                $("#serie_campos").focus();
                                alertify.success("Error... Serie ingresada");
                            }
                        }
                    } else {
                        $("#serie_campos").val("");
                        $("#btnAgregar").attr("disabled", "disabled");
                        alertify.success("Error... Alcanzo el límite máximo");
                    }
                }
            }
        });
    } else {
        $("#serie_campos").focus();
        alertify.alert("Error... Indique una series");
    }
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

                if ($("#calculoRetencionF").val() != "0.00" && x != 4) {
                    if (filas.length == 0) {
                        var datarow = {
                            base_imponible: $("#calculobien").val(),
                            impuesto: impuesto,
                            porcent_reten: $("#porcent_reten").val(),
                            valor_retenido: $("#calculoRetencionF").val(),
                            id_retenciones_ser: xsid_bienes,
                            tipo_ret: 'b'
                        };
                        su = jQuery("#listPagoreten").jqGrid('addRowData', $("#calculobien").val(), datarow);
                    } else {
                        for (var i = 0; i < filas.length; i++) {
                            var id = filas[i];
                            if (id['impuesto'] == impuesto) {
                                repe = 1;
                            }

                        }

                        if (repe != 1) {
                            datarow = {
                                base_imponible: $("#calculobien").val(),
                                impuesto: impuesto,
                                porcent_reten: $("#porcent_reten").val(),
                                valor_retenido: $("#calculoRetencionF").val(),
                                id_retenciones_ser: xsid_bienes,
                                tipo_ret: 'b'
                            };
                            su = jQuery("#listPagoreten").jqGrid('addRowData', $("#calculobien").val(), datarow);
                            limpiar_campos();
                        } else {
                            console.log("ss2");
                            alertify.error("Error....la retencion ya esta ingresada");
                        }
                    }
                } else if ($("#calculoRetencionF").val() == "0.00" && x == 4) {
                    if (filas.length == 0) {
                        var datarow = {
                            base_imponible: $("#calculobien").val(),
                            impuesto: impuesto,
                            porcent_reten: $("#porcent_reten").val(),
                            valor_retenido: $("#calculoRetencionF").val(),
                            id_retenciones_ser: xsid_bienes,
                            tipo_ret: 'b'
                        };
                        su = jQuery("#listPagoreten").jqGrid('addRowData', $("#calculobien").val(), datarow);
                    } else {
                        for (var i = 0; i < filas.length; i++) {
                            var id = filas[i];
                            if (id['impuesto'] == impuesto) {
                                repe = 1;
                            }

                        }

                        if (repe != 1) {
                            datarow = {
                                base_imponible: $("#calculobien").val(),
                                impuesto: impuesto,
                                porcent_reten: $("#porcent_reten").val(),
                                valor_retenido: $("#calculoRetencionF").val(),
                                id_retenciones_ser: xsid_bienes,
                                tipo_ret: 'b'
                            };
                            su = jQuery("#listPagoreten").jqGrid('addRowData', $("#calculobien").val(), datarow);
                            limpiar_campos();
                        } else {
                            console.log("ss2");
                            alertify.error("Error....la retencion ya esta ingresada");
                        }
                    }


                } else {
                    alertify.error("Error....el valor debe ser diferente de 0");
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

                if ($("#calculoRetencionFS").val() != "0.00" && x != 4) {
                    if (filas.length == 0) {
                        var datarow = {
                            base_imponible: $("#calculoserv").val(),
                            impuesto: impuesto,
                            porcent_reten: $("#porcent_retens").val(),
                            valor_retenido: $("#calculoRetencionFS").val(),
                            id_retenciones_ser: xsid_servicio,
                            tipo_ret: 's'
                        };
                        su = jQuery("#listPagoreten").jqGrid('addRowData', $("#calculoserv").val(), datarow);
                        // limpiar_campos();
                    } else {
                        for (var i = 0; i < filas.length; i++) {
                            var id = filas[i];
                            if (id['impuesto'] == impuesto) {
                                repe = 1;
                            }
                        }
                        if (repe != 1) {

                            datarow = {
                                base_imponible: $("#calculoserv").val(),
                                impuesto: impuesto,
                                porcent_reten: $("#porcent_retens").val(),
                                valor_retenido: $("#calculoRetencionFS").val(),
                                id_retenciones_ser: xsid_servicio,
                                tipo_ret: 's'
                            };
                            su = jQuery("#listPagoreten").jqGrid('addRowData', $("#calculoserv").val(), datarow);
                            //limpiar_campos();
                        } else {
                            alertify.error("Error....la retencion ya esta ingresada");
                        }
                    }
                } else if ($("#calculoRetencionFS").val() == "0.00" && x == 4) {
                    if (filas.length == 0) {
                        var datarow = {
                            base_imponible: $("#calculoserv").val(),
                            impuesto: impuesto,
                            porcent_reten: $("#porcent_retens").val(),
                            valor_retenido: $("#calculoRetencionFS").val(),
                            id_retenciones_ser: xsid_servicio,
                            tipo_ret: 's'
                        };
                        su = jQuery("#listPagoreten").jqGrid('addRowData', $("#calculoserv").val(), datarow);
                        // limpiar_campos();
                    } else {
                        for (var i = 0; i < filas.length; i++) {
                            var id = filas[i];
                            if (id['impuesto'] == impuesto) {
                                repe = 1;
                            }
                        }
                        if (repe != 1) {

                            datarow = {
                                base_imponible: $("#calculoserv").val(),
                                impuesto: impuesto,
                                porcent_reten: $("#porcent_retens").val(),
                                valor_retenido: $("#calculoRetencionFS").val(),
                                id_retenciones_ser: xsid_servicio,
                                tipo_ret: 's'
                            };
                            su = jQuery("#listPagoreten").jqGrid('addRowData', $("#calculoserv").val(), datarow);
                            //limpiar_campos();
                        } else {
                            alertify.error("Error....la retencion ya esta ingresada");
                        }
                    }
                } else {
                    alertify.error("Error....el valor debe ser diferente de 0");
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
                if ($("#calculoRetencionI").val() != "0.00") {
                    if (filas.length == 0) {
                        var datarow = {
                            base_imponible: parseFloat($("#iva").val()).toFixed(2),
                            impuesto: impuesto,
                            porcent_reten: $("#porcent_iva").val(),
                            valor_retenido: $("#calculoRetencionI").val(),
                            id_retenciones_ser: xsid_iva,
                            tipo_ret: 'b'

                        };
                        su = jQuery("#listPagoreten").jqGrid('addRowData', parseFloat($("#iva").val()).toFixed(2), datarow);
                        //                                limpiar_campos();
                    } else {
                        for (var i = 0; i < filas.length; i++) {
                            var id = filas[i];
                            if (id['impuesto'] == impuesto) {
                                repe = 1;
                            }
                        }
                        if (repe != 1) {
                            datarow = {
                                base_imponible: parseFloat($("#iva").val()).toFixed(2),
                                impuesto: impuesto,
                                porcent_reten: $("#porcent_iva").val(),
                                valor_retenido: $("#calculoRetencionI").val(),
                                id_retenciones_ser: xsid_iva,
                                tipo_ret: 'b'
                            };
                            su = jQuery("#listPagoreten").jqGrid('addRowData', parseFloat($("#iva").val()).toFixed(2), datarow);
                            //                                    limpiar_campos();
                        } else {
                            alertify.error("Error....la retencion ya esta ingresada");
                        }
                    }
                } else {
                    alertify.error("Error....el valor debe ser diferente de 0");
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
                if ($("#calculoRetencionIs").val() != "0.00") {
                    if (filas.length == 0) {
                        var datarow = {
                            base_imponible: parseFloat(calculoservivaS).toFixed(2),
                            impuesto: impuesto,
                            porcent_reten: $("#porcent_ivas").val(),
                            valor_retenido: $("#calculoRetencionIs").val(),
                            id_retenciones_ser: xsid_ivas,
                            tipo_ret: 's'
                        };
                        su = jQuery("#listPagoreten").jqGrid('addRowData', parseFloat(calculoservivaS).toFixed(2), datarow);
                    } else {
                        for (var i = 0; i < filas.length; i++) {
                            var id = filas[i];
                            if (id['impuesto'] == impuesto) {
                                repe = 1;
                            }
                        }
                        if (repe != 1) {
                            datarow = {
                                base_imponible: parseFloat(calculoservivaS).toFixed(2),
                                impuesto: impuesto,
                                porcent_reten: $("#porcent_ivas").val(),
                                valor_retenido: $("#calculoRetencionIs").val(),
                                id_retenciones_ser: xsid_ivas,
                                tipo_ret: 's'
                            };
                            su = jQuery("#listPagoreten").jqGrid('addRowData', parseFloat(calculoservivaS).toFixed(2), datarow);
                            //                                    limpiar_campos();
                        } else {
                            alertify.error("Error....la retencion ya esta ingresada");
                        }
                    }
                } else {
                    alertify.error("Error....El valor debe ser diferente de 0");
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
/////////////////////////////////////////////////

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

function buscar_bienservicio_producto() {
    console.log("id_factu " + $("#id_factura_compra").val());
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
function calculo_ret_fuenteS() {
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
            var valor = toFixedDown(((($("#calculoserv").val()) * calculoRET) / 100), 3);
            $("#calculoRetencionFS").val(numFormatter(2).format(valor));
            $("#porcent_retens").val(calculoRET);
            $("#calculoRetencionFS").focus();
        }
    });
}
function numFormatter(d) {
    return new Intl.NumberFormat("en-US", {
        minimumFractionDigits: d,
        maximumFractionDigits: d,
        useGrouping: false,
    });
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

function countfactura() {
    var temp2 = "";
    var serie = $("#serie_retencion").val();
    for (var i = serie.length; i < 5; i++) {
        temp2 = temp2 + "0";
    }
    return temp2;
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

function calculo_ret_iva() {
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
                var calculoserviva = $("#calculobieniva").val() * toFixedDown((12 / 100), 3);
                var valor = toFixedDown((((calculoserviva) * calculoRET) / 100), 3);
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
                        $('#otros_form').prop('selected', true);
                        $('.nav-tabs a[href="#tab_4"]').tab('show')
                        $("#formaspago_mixto").attr("disabled", false);
                    } else {
                        $('#contado_form').prop('selected', true);
                        guardar_asiento_contable();
                    }
                }
            );
        }
    }
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
                v1[i] = datos['serie_campos'];
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

function autocompletar_num() {
    var temp = "";
    var str = $("#serie").val();
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


function guardar_factura() {
    var observa = "Ninguna";
    var forma_p = "";
    var bien_ser = "";
    var pago_ats = "";
    var str = $("#serie").val();
    if ($("#serie").val() == "") {
        $("#serie").focus();
        alertify.error("Ingrese num de factura");
    } else {
        var res = str.split("-");
        var ele1 = res[0];
        var ele2 = res[1];
        var ele3 = res[2];
        var ele22 = ele3.substring(8, 9);
    }

    var tam = jQuery("#list").jqGrid("getRowData");
    var observa = "Ninguna";
    var forma_p = "";
    var bien_ser = "";
    var pago_ats = "";
    var str = $("#serie").val();
    var res = str.split("-");

    var ele1 = res[0];
    var ele2 = res[1];
    var ele3 = res[2];
    var ele22 = ele3.substring(8, 9);
    if ($("#formas").val() == "otros" && $("#validar_guardar_grid").val() == "") {
        alertify.error("Ingrese Valor ");
        $("#valor_formas").focus();
    } else {

        if ($("#formas").val() == "otros" && $("#valor_factura_saldo").val() != "0.00") {
            alertify.error("Ingrese Valor ");
            $("#valor_formas").focus();
        } else {

            if (ele22 == '_') {
                var a = autocompletar_num();
                var validado = a;
                var ele31 = res[2];
                var serie = ele31;

                var res_serie1 = serie.split("_");
                var sesult_serie1 = res_serie1[0];
                $("#serie").val(ele1 + "-" + ele2 + "-" + validado + "" + sesult_serie1);
                $("#serie").focus();

            }
            if ($("#tipo_comprobante").val() == "") {
                $("#tipo_comprobante").focus();
                alertify.error("Seleccione el comprobante");
            } else {
                if ($("#tipo_docu").val() == "") {
                    $("#tipo_docu").focus();
                    alertify.error("Seleccione tipo documento");
                } else {
                    if ($("#id_proveedor").val() == "") {
                        $("#ruc_ci").focus();
                        alertify.error("Indique un Proveedor");
                    } else {
                        var num_fac = $("#serie").val();
                        $.ajax({
                            type: "POST",
                            url: "comparar_num_compra.php",
                            data: "num_fac=" + num_fac + "&id_proveedor=" + $("#id_proveedor").val(),
                            success: function (data) {
                                var val = data;
                                if (val > 0) {
                                    $("#serie").focus();
                                    alertify.error("Error... El nùmero de factura ya existe");
                                } else {
                                    if ($("#autorizacion").val() == "") {
                                        $("#autorizacion").focus();
                                        alertify.error("Ingrese la autorizaciòn");
                                    } else {
                                        if (tam.length == 0) {
                                            $("#codigo_barras").focus();
                                            alertify.error("Error... Ingrese productos a la factura");
                                        } else {
                                            if ($("#tot").val() > 1000.000) {
                                                if ($("#observacionPago").val() == "") {
                                                    alertify.alert("Debe ingresar formas de pago", function () {
                                                        $('.nav-tabs a[href="#tab_3"]').tab('show')
                                                        $("#tab_1").removeClass('active');
                                                        $("#tab_3").addClass('active');
                                                    });
                                                } else {
                                                    forma_p = $("#formas").val();
                                                    bien_ser = $("#bien_servicio").val();
                                                    pago_ats = $("#detalle_pago").val();
                                                    observa = $("#observacionPago").val();
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
                                                        v6[i] = datos['precio_v'];

                                                        string_v1 = string_v1 + "|" + v1[i];
                                                        string_v2 = string_v2 + "|" + v2[i];
                                                        string_v3 = string_v3 + "|" + v3[i];
                                                        string_v4 = string_v4 + "|" + v4[i];
                                                        string_v5 = string_v5 + "|" + v5[i];
                                                        string_v6 = string_v6 + "|" + v6[i];
                                                    }
                                                    var seriee = $("#serie").val();
                                                    datos = {
                                                        id_fac: $("#id_factura_compra").val(),
                                                        id_proveedor: $("#id_proveedor").val(),
                                                        comprobante: $("#comprobante").val(),
                                                        fecha_actual: $("#fecha_actual").val(),
                                                        hora_actual: $("#hora_actual").val(),
                                                        fecha_registro: $("#fecha_registro").val(),
                                                        fecha_emision: $("#fecha_emision").val(),
                                                        fecha_caducidad: $("#fecha_caducidad").val(),
                                                        tipo_comprobante: $("#tipo_comprobante").val(),
                                                        serie: seriee, autorizacion: $("#autorizacion").val(),
                                                        cancelacion: $("#cancelacion").val(), formas: forma_p,
                                                        tarifa0: $("#total_p").val(), tarifa12: $("#total_p2").val(),
                                                        iva: $("#iva").val(), desc: $("#desc").val(), tot: $("#tot").val(),
                                                        campo1: string_v1, campo2: string_v2, campo3: string_v3, campo4: string_v4,
                                                        campo5: string_v5, observaciones: observa, pago_ats: pago_ats, bien_servi: bien_ser,
                                                        campo6: string_v6
                                                    };
                                                    guardar_serie();
                                                    $.ajax({
                                                        type: "POST",
                                                        url: "guardar_factura_compra.php",
                                                        data: "id_fac=" + $("#id_factura_compra").val() + "&id_proveedor=" + $("#id_proveedor").val() + "&comprobante=" + $("#comprobante").val() + "&fecha_actual=" + $("#fecha_actual").val() + "&hora_actual=" + $("#hora_actual").val() + "&fecha_registro=" + $("#fecha_registro").val() + "&fecha_emision=" + $("#fecha_emision").val() + "&fecha_caducidad=" + $("#fecha_caducidad").val() + "&tipo_comprobante=" + $("#tipo_comprobante").val() + "&serie=" + seriee + "&autorizacion=" + $("#autorizacion").val() + "&cancelacion=" + $("#cancelacion").val() + "&formas=" + forma_p + "&tarifa0=" + $("#total_p").val() + "&tarifa12=" + $("#total_p2").val() + "&iva=" + $("#iva").val() + "&desc=" + $("#desc").val() + "&tot=" + $("#tot").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&observaciones=" + observa + "&pago_ats=" + pago_ats + "&bien_servi=" + bien_ser + "&campo6=" + string_v6 + "&ice=" + $("#icex").val() + "&irbp=" + $("#irbpx").val(),
                                                        success: function (data) {
                                                            var val = data;
                                                            if ($("#tipo_comprobante").val() == "FACTURA") {
                                                                if (val != 0) {
                                                                    alertify.alert("Factura Guardada correctamente");
                                                                    alertify.confirm("Factura Guardada¿Desea ingresar retenciones?",
                                                                        function (e) {
                                                                            if (e) {
                                                                                //                                                                            $("#comprobante").val(val);
                                                                                $("#tipoRetencionesF").attr("disabled", false);
                                                                                $('.nav-tabs a[href="#tab_2"]').tab('show')
                                                                                $("#valor_reten").val("");
                                                                            } else {
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

                                                                                            window.open(formatoFC + "?hoja=A4&id=" + val, '_blank');
                                                                                            //                                                                                                    window.open("../../reportes/transacciones_2.php?hoja=A5&id=" + val, '_blank');
                                                                                            location.reload();
                                                                                        }

                                                                                    }


                                                                                );





                                                                            }

                                                                        }
                                                                    );
                                                                }
                                                            } else {
                                                                if ($("#tipo_comprobante").val() == "NOTA") {
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
                                            } else {
                                                forma_p = $("#formas").val();
                                                observa = "Ninguna";

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
                                                    v6[i] = datos['precio_v'];

                                                    string_v1 = string_v1 + "|" + v1[i];
                                                    string_v2 = string_v2 + "|" + v2[i];
                                                    string_v3 = string_v3 + "|" + v3[i];
                                                    string_v4 = string_v4 + "|" + v4[i];
                                                    string_v5 = string_v5 + "|" + v5[i];
                                                    string_v6 = string_v6 + "|" + v6[i];
                                                }
                                                var seriee = $("#serie").val();

                                                $.ajax({
                                                    type: "POST",
                                                    url: "guardar_factura_compra.php",
                                                    data: "id_fac=" + $("#id_factura_compra").val() + "&id_proveedor=" + $("#id_proveedor").val() + "&comprobante=" + $("#comprobante").val() + "&fecha_actual=" + $("#fecha_actual").val() + "&hora_actual=" + $("#hora_actual").val() + "&fecha_registro=" + $("#fecha_registro").val() + "&fecha_emision=" + $("#fecha_emision").val() + "&fecha_caducidad=" + $("#fecha_caducidad").val() + "&tipo_comprobante=" + $("#tipo_comprobante").val() + "&serie=" + seriee + "&autorizacion=" + $("#autorizacion").val() + "&cancelacion=" + $("#cancelacion").val() + "&formas=" + forma_p + "&tarifa0=" + $("#total_p").val() + "&tarifa12=" + $("#total_p2").val() + "&iva=" + $("#iva").val() + "&desc=" + $("#desc").val() + "&tot=" + $("#tot").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&observaciones=" + observa + "&pago_ats=" + pago_ats + "&campo6=" + string_v6 + "&ice=" + $("#icex").val() + "&irbp=" + $("#irbpx").val(),
                                                    success: function (data) {
                                                        var val = data;
                                                        if ($("#tipo_comprobante").val() == "FACTURA") {
                                                            if (val != 0) {
                                                                alertify.alert("Factura Guardada correctamente");
                                                                alertify.confirm("¿Desea ingresar retenciones?",
                                                                    function (e) {
                                                                        if (e) {

                                                                            //                                                                        $("#comprobante").val(val);
                                                                            $("#tipoRetencionesF").attr("disabled", false);
                                                                            $('.nav-tabs a[href="#tab_2"]').tab('show');
                                                                            $("#valor_reten").val("");

                                                                        } else {
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
                                                                                        window.open(formatoFC + "?hoja=A4&id=" + val, '_blank');
                                                                                        //                                                                                                window.open("../../reportes/transacciones_2.php?hoja=A5&id=" + val, '_blank');
                                                                                        //                                                                                                location.reload();
                                                                                    }

                                                                                }


                                                                            );

                                                                        }

                                                                    }
                                                                );
                                                            }
                                                        } else {
                                                            if ($("#tipo_comprobante").val() == "NOTA") {
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
                                }
                            }
                        });
                    }
                }
            }
        }

    }

}

//
//function guardar_factura_temporal() {
//var tam = jQuery("#list").jqGrid("getRowData");
//var observa="Ninguna";
//var forma_p="";
//var pago_ats="";
//
//if ($("#serie").val() == "") {
//    $("#serie").focus();
//    alertify.error("Ingrese una serie");
//    } else {
//        if ($("#tipo_comprobante").val() == "") {
//            $("#tipo_comprobante").focus();
//            alertify.error("Seleccione el comprobante");
//        } else {
//            if ($("#tipo_docu").val() == "") {
//                $("#tipo_docu").focus();
//                alertify.error("Seleccione tipo documento");
//            } else {
//                if ($("#id_proveedor").val() == "") {
//                    $("#ruc_ci").focus();
//                    alertify.error("Indique un Proveedor");
//                } else {
//                    var num_fac = $("#serie").val();
//                    $.ajax({
//                        type: "POST",
//                        url: "comparar_num_compra.php",
//                        data: "num_fac=" + num_fac + "&id_proveedor=" + $("#id_proveedor").val(),
//                        success: function(data) {
//                            var val = data;
//                            if (val == 1) {
//                                $("#serie").focus();
//                                alertify.error("Error... El nÃºmero de factura ya existe");
//                            } else {
//                                if ($("#autorizacion").val() == "") {
//                                    $("#autorizacion").focus();
//                                    alertify.error("Ingrese la autorizaciÃ³n");
//                                } else {
//                                    if (tam.length == 0) {
//                                        $("#codigo_barras").focus();
//                                        alertify.error("Error... Ingrese productos a la factura");
//                                    }else{
//                                        if($("#tot").val()>1000.000 ){
//                                            if($("#observacionPago").val()==""){
//                                                alertify.alert("Debe ingresar formas de pago", function(){
//                                                    $('.nav-tabs a[href="#tab_3"]').tab('show')
//                                                    //$("#tab_1").removeClass('active');
//                                                    //$("#tab_3").addClass('active');
//                                                });
//                                            }else{
//                                                forma_p = $("#formas").val();
//                                                pago_ats=$("#detalle_pago").val();
//                                                observa=$("#observacionPago").val();
//                                              
//                                                var v1 = new Array();
//                                                var v2 = new Array();
//                                                var v3 = new Array();
//                                                var v4 = new Array();
//                                                var v5 = new Array();
//
//                                                var string_v1 = "";
//                                                var string_v2 = "";
//                                                var string_v3 = "";
//                                                var string_v4 = "";
//                                                var string_v5 = "";
//                                                alertify.alert("hola");
//                                                var fil = jQuery("#list").jqGrid("getRowData");
//                                                for (var i = 0; i < fil.length; i++) {
//                                                    var datos = fil[i];
//                                                    v1[i] = datos['cod_producto'];
//                                                    v2[i] = datos['cantidad'];
//                                                    v3[i] = datos['precio_u'];
//                                                    v4[i] = datos['descuento'];
//                                                    v5[i] = datos['total'];
//                                                }
//
//                                                for (i = 0; i < fil.length; i++) {
//                                                    string_v1 = string_v1 + "|" + v1[i];
//                                                    string_v2 = string_v2 + "|" + v2[i];
//                                                    string_v3 = string_v3 + "|" + v3[i];
//                                                    string_v4 = string_v4 + "|" + v4[i];
//                                                    string_v5 = string_v5 + "|" + v5[i];
//                                                }
//                                                var seriee = $("#serie").val();
//                                                
//                                                alertify.alert(string_v1);
//                                                
//                                                $.ajax({
//                                                    type: "POST",
//                                                    url: "guardar_factura_compra_temporal.php",
//                                                    data: "id_proveedor=" + $("#id_proveedor").val() + "&comprobante=" + $("#comprobante").val() + "&fecha_actual=" + $("#fecha_actual").val() + "&hora_actual=" + $("#hora_actual").val() + "&fecha_registro=" + $("#fecha_registro").val() + "&fecha_emision=" + $("#fecha_emision").val() + "&fecha_caducidad=" + $("#fecha_caducidad").val() + "&tipo_comprobante=" + $("#tipo_comprobante").val() + "&serie=" + seriee + "&autorizacion=" + $("#autorizacion").val() + "&cancelacion=" + $("#cancelacion").val() + "&formas=" + forma_p + "&tarifa0=" + $("#total_p").val() + "&tarifa12=" + $("#total_p2").val() + "&iva=" + $("#iva").val() + "&desc=" + $("#desc").val() + "&tot=" + $("#tot").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&observaciones=" + observa+"&pago_ats="+pago_ats,
//                                                    success: function(data) {
//                                                        var  val = data;                                                       
//                                                        if (val != 0) {
//                                                            alertify.alert("Factura  Temporal Guardada Correctamente", function(e){ 
//                                                                window.open("../../reportes/factura_compra.php?hoja=A4&id="+$("#comprobante").val(),'_blank');    
//                                                                location.reload();
//                                                            });
//                                                        }else{
//                                                            alertify.alert("Datos Erroneos");
//                                                        }
//                                                    }
//                                                }); 
//                                            }
//                                        }else{
//                                            forma_p = $("#formas").val();
//                                            observa="Ninguna";
//                                        
//                                        
//                                            var v1 = new Array();
//                                            var v2 = new Array();
//                                            var v3 = new Array();
//                                            var v4 = new Array();
//                                            var v5 = new Array();
//
//                                            var string_v1 = "";
//                                            var string_v2 = "";
//                                            var string_v3 = "";
//                                            var string_v4 = "";
//                                            var string_v5 = "";
//                                            //alertify.alert("hola");
//                                            var fil = jQuery("#list").jqGrid("getRowData");
//                                            for (var i = 0; i < fil.length; i++) {
//                                                var datos = fil[i];
//                                                v1[i] = datos['cod_producto'];
//                                                v2[i] = datos['cantidad'];
//                                                v3[i] = datos['precio_u'];
//                                                v4[i] = datos['descuento'];
//                                                v5[i] = datos['total'];
//                                            }
//
//                                            for (i = 0; i < fil.length; i++) {
//                                                string_v1 = string_v1 + "|" + v1[i];
//                                                string_v2 = string_v2 + "|" + v2[i];
//                                                string_v3 = string_v3 + "|" + v3[i];
//                                                string_v4 = string_v4 + "|" + v4[i];
//                                                string_v5 = string_v5 + "|" + v5[i];
//                                            }
//                                            var seriee = $("#serie").val();
//                                            
//                                            //alertify.alert(string_v1);
//                                            
//                                            $.ajax({
//                                                type: "POST",
//                                                url: "guardar_factura_compra_temporal.php",
//                                                data: "id_proveedor=" + $("#id_proveedor").val() + "&comprobante=" + $("#comprobante").val() + "&fecha_actual=" + $("#fecha_actual").val() + "&hora_actual=" + $("#hora_actual").val() + "&fecha_registro=" + $("#fecha_registro").val() + "&fecha_emision=" + $("#fecha_emision").val() + "&fecha_caducidad=" + $("#fecha_caducidad").val() + "&tipo_comprobante=" + $("#tipo_comprobante").val() + "&serie=" + seriee + "&autorizacion=" + $("#autorizacion").val() + "&cancelacion=" + $("#cancelacion").val() + "&formas=" + forma_p + "&tarifa0=" + $("#total_p").val() + "&tarifa12=" + $("#total_p2").val() + "&iva=" + $("#iva").val() + "&desc=" + $("#desc").val() + "&tot=" + $("#tot").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&observaciones=" + observa+"&pago_ats="+pago_ats,
//                                                success: function(data) {
//                                                    var  val = data;                                                       
//                                                    if (val != 0) {
//                                                        alertify.alert("Factura Temporal Guardada Correctamente", function(e){ 
//                                                            window.open("../../reportes/factura_compra.php?hoja=A4&id="+$("#comprobante").val(),'_blank');    
//                                                            location.reload();
//                                                        }); 
//                                                    }else{
//                                                        alertify.alert("Datos Erroneos");
//                                                    }
//                                                }
//                                            }); 
//                                        } 
//                                    }
//                                }
//                            }
//                        }
//                    });
//                }
//            }    
//        }
//    }
//}

function modificar_factura() {
    var tam = jQuery("#list").jqGrid("getRowData");

    if ($("#comprobante").val() == "") {
        alertify.error("Selccione una factura");
        $("#buscar_facturas_compras").dialog("open");
    } else {
        if ($("#serie").val() == "") {
            $("#serie").focus();
            alertify.error("Ingrese una serie");
        } else {
            if ($("#tipo_comprobante").val() == "") {
                $("#tipo_comprobante").focus();
                alertify.error("Seleccione el comprobante");
            } else {
                if ($("#tipo_docu").val() == "") {
                    $("#tipo_docu").focus();
                    alertify.error("Seleccione tipo documento");
                } else {
                    if ($("#id_proveedor").val() == "") {
                        $("#ruc_ci").focus();
                        alertify.error("Indique un Proveedor");
                    } else {
                        if ($("#autorizacion").val() == "") {
                            $("#autorizacion").focus();
                            alertify.error("Ingrese la autorizaciÃ³n");
                        } else {
                            if (tam.length == 0) {
                                $("#codigo_barras").focus();
                                alertify.error("Error... Ingrese productos a la factura");
                            } else {
                                $("#btnModificar").attr("disabled", true);

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

                                var seriee = $("#serie").val();
                                $.ajax({
                                    type: "POST",
                                    url: "modificar_factura_compra.php",
                                    data: "id_factura_compra=" + $("#id_factura_compra").val() + "&id_proveedor=" + $("#id_proveedor").val() + "&comprobante=" + $("#comprobante").val() + "&fecha_actual=" + $("#fecha_actual").val() + "&hora_actual=" + $("#hora_actual").val() + "&fecha_registro=" + $("#fecha_registro").val() + "&fecha_emision=" + $("#fecha_emision").val() + "&fecha_caducidad=" + $("#fecha_caducidad").val() + "&tipo_comprobante=" + $("#tipo_comprobante").val() + "&serie=" + seriee + "&autorizacion=" + $("#autorizacion").val() + "&cancelacion=" + $("#cancelacion").val() + "&formas=" + $("#formas").val() + "&tarifa0=" + $("#total_p").val() + "&tarifa12=" + $("#total_p2").val() + "&iva=" + $("#iva").val() + "&desc=" + $("#desc").val() + "&tot=" + $("#tot").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5,
                                    success: function (data) {
                                        var val = data;
                                        if (val != 0) {
                                            alertify.alert("Factura Modificada correctamente", function () {
                                                window.open(formatoFC + "?hoja=A4&id=" + val, '_blank');
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

function eliminar_factura() {
    if ($("#id_factura_compra").val() == "") {
        alertify.error("Seleccione una factura");
        $("#buscar_facturas_compras").dialog("open");
    } else {
        $("#clave_permiso").dialog("open");
    }
}

function flecha_atras() {

    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "factura_compra" + "&id_tabla=" + "id_factura_compra" + "&tipo=" + 1,
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#comprobante").val(val);
                var valor = $("#comprobante").val();
                // llamar datos Factura Compra
                $("#btnGuardar").attr("disabled", true);
                //                $("#btnGuardarTemporal").attr("disabled", true);
                $("#ruc_ci").attr("disabled", "disabled");
                $("#serie").attr("disabled", "disabled");
                $("#formas").val("Contado");
                $("#list").jqGrid("clearGridData", true);
                $("#listPagoreten").jqGrid("clearGridData", true);

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
                $("#estado h3").remove();

                $.getJSON('retornar_factura_compra.php?com=' + valor, function (data) {
                    var tama = data.length;
                    t = data[23];
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 24) {
                            $("#id_factura_compra").val(data[i]);
                            $("#fecha_actual").val(data[i + 1]);
                            $("#hora_actual").val(data[i + 2]);
                            $("#digitador").val(data[i + 3] + " " + data[i + 4]);
                            $("#id_proveedor").val(data[i + 5]);
                            $("#tipo_docu").val(data[i + 6]);
                            $("#ruc_ci").val(data[i + 7]);
                            $("#empresa").val(data[i + 8]);
                            $("#tipo_comprobante").val(data[i + 9]);
                            $("#fecha_registro").val(data[i + 10]);
                            $("#fecha_emision").val(data[i + 11]);
                            $("#fecha_caducidad").val(data[i + 12]);
                            $("#serie").val(data[i + 13]);
                            $("#autorizacion").val(data[i + 14]);
                            $("#cancelacion").val(data[i + 15]);
                            $("#formas").val(data[i + 16]);
                            $("#total_p").val(data[i + 17]);
                            $("#total_p2").val(data[i + 18]);
                            $("#sub").val((parseFloat(data[i + 17]) + parseFloat(data[i + 18])));
                            $("#iva").val(data[i + 19]);
                            $("#desc").val(data[i + 20]);
                            $("#tot").val(data[i + 21]);
                            $("#total_px").val(parseFloat(data[i + 17]).toFixed(2));
                            $("#total_p2x").val(parseFloat(data[i + 18]).toFixed(2));
                            $("#subx").val((parseFloat(data[i + 17]) + parseFloat(data[i + 18])).toFixed(2));
                            $("#ivax").val(parseFloat(data[i + 19]).toFixed(2));
                            $("#descx").val(parseFloat(data[i + 20]).toFixed(2));
                            $("#totx").val(parseFloat(data[i + 21]).toFixed(2))

                            if (data[i + 22] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulada"));
                                $("#estado h3").css("color", "red");
                                $("#btnEliminar").attr("disabled", "disabled");
                                $("#btnModificar").attr("disabled", true);
                            } else {
                                $("#estado h3").remove();
                                $("#btnEliminar").attr("disabled", "disabled");
                                $("#btnEliminar").attr("disabled", false);
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

                                    $("#formas").attr("disabled", false);
                                } else if (data[i + 23] == '1') {
                                    $("#btnModificar").attr("disabled", "disabled");
                                }
                            }
                        }
                        volver_rf();
                        volver_ri();
                    }
                });

                $.getJSON('retornar_factura_compra2.php?com=' + valor, function (data) {
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
                                cantidad: parseFloat(data[i + 3]).toFixed(2),
                                precio_u: precio,
                                descuento: desc,
                                cal_des: resultado,
                                total: data[i + 6],
                                precio_ux: precio.toFixed(4),
                                descuentox: parseFloat(desc).toFixed(4),
                                cal_desx: resultado.toFixed(4),
                                totalx: parseFloat(data[i + 6]).toFixed(4),
                                iva: data[i + 7],
                                incluye: data[i + 8],
                                precio_v: data[i + 9]
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });
                $.getJSON('retornar_ice.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 2) {
                            $("#icex").val(data[i]);
                            $("#irbpx").val(data[i + 1]);
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
                $.getJSON('retornar_ice.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 2) {
                            $("#icex").val(data[i]);
                            $("#irbpx").val(data[i + 1]);
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
                alertify.alert("No hay màs registros posteriores!!");
            }
        }
    });
}

function volver_rf() {
    $.ajax({
        type: "POST",
        url: "retornar_retencion_fuente.php",
        data: "id_factura_compra=" + $("#id_factura_compra").val(),
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
        data: "id_factura_compra=" + $("#id_factura_compra").val(),
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

function flecha_siguiente() {
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "factura_compra" + "&id_tabla=" + "id_factura_compra" + "&tipo=" + 2,
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#comprobante").val(val);
                var valor = $("#comprobante").val();
                // llamar datos Factura Compra
                $("#btnGuardar").attr("disabled", true);
                //            $("#btnGuardarTemporal").attr("disabled", true);
                $("#ruc_ci").attr("disabled", "disabled");
                $("#serie").attr("disabled", "disabled");
                $("#formas").val("Contado");
                $("#adelanto").val("");
                $("#meses").val("");
                $("#cuotas").val("");
                $("#list").jqGrid("clearGridData", true);
                $("#listPagoreten").jqGrid("clearGridData", true);
                $("#total_p").val("0.000");
                $("#total_p2").val("0.000");
                $("#iva").val("0.000");
                $("#desc").val("0.000");
                $("#tot").val("0.000");
                $("#estado h3").remove();

                $.getJSON('retornar_factura_compra.php?com=' + valor, function (data) {
                    var tama = data.length;
                    t = data[23]
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 24) {
                            $("#id_factura_compra").val(data[i]);
                            $("#fecha_actual").val(data[i + 1]);
                            $("#hora_actual").val(data[i + 2]);
                            $("#digitador").val(data[i + 3] + " " + data[i + 4]);
                            $("#id_proveedor").val(data[i + 5]);
                            $("#tipo_docu").val(data[i + 6]);
                            $("#ruc_ci").val(data[i + 7]);
                            $("#empresa").val(data[i + 8]);
                            $("#tipo_comprobante").val(data[i + 9]);
                            $("#fecha_registro").val(data[i + 10]);
                            $("#fecha_emision").val(data[i + 11]);
                            $("#fecha_caducidad").val(data[i + 12]);
                            $("#serie").val(data[i + 13]);
                            $("#autorizacion").val(data[i + 14]);
                            $("#cancelacion").val(data[i + 15]);
                            $("#formas").val(data[i + 16]);
                            $("#total_p").val(data[i + 17]);
                            $("#total_p2").val(data[i + 18]);
                            $("#sub").val(parseFloat(data[i + 17]) + parseFloat(data[i + 18]));
                            $("#iva").val(data[i + 19]);
                            $("#desc").val(data[i + 20]);
                            $("#tot").val(data[i + 21]);
                            $("#total_px").val(parseFloat(data[i + 17]).toFixed(2));
                            $("#total_p2x").val(parseFloat(data[i + 18]).toFixed(2));
                            $("#subx").val((parseFloat(data[i + 17]) + parseFloat(data[i + 18])).toFixed(2));
                            $("#ivax").val(parseFloat(data[i + 19]).toFixed(2));
                            $("#descx").val(parseFloat(data[i + 20]).toFixed(2));
                            $("#totx").val(parseFloat(data[i + 21]).toFixed(2));

                            if (data[i + 22] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulada"));
                                $("#estado h3").css("color", "red");
                                $("#btnEliminar").attr("disabled", "disabled");
                                $("#btnModificar").attr("disabled", true);
                            } else {
                                $("#estado h3").remove();
                                $("#btnEliminar").attr("disabled", "disabled");
                                $("#btnEliminar").attr("disabled", false);
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
                                    $("#formas").attr("disabled", false);
                                } else if (data[i + 23] == '1') {
                                    $("#btnModificar").attr("disabled", "disabled");
                                }
                            }
                        }
                        volver_rf();
                        volver_ri();
                    }
                });

                $.getJSON('retornar_factura_compra2.php?com=' + valor, function (data) {
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
                                cantidad: parseFloat(data[i + 3]).toFixed(2),
                                precio_u: precio,
                                descuento: desc,
                                cal_des: resultado,
                                total: data[i + 6],
                                precio_ux: precio.toFixed(4),
                                descuentox: parseFloat(desc).toFixed(4),
                                cal_desx: resultado.toFixed(4),
                                totalx: parseFloat(data[i + 6]).toFixed(4),
                                iva: data[i + 7],
                                incluye: data[i + 8]
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });
                $.getJSON('retornar_ice.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 2) {
                            F
                            $("#icex").val(data[i]);
                            $("#irbpx").val(data[i + 1]);
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
                // fin
            } else {
                alertify.alert("No hay màs registros superiores!!");
            }
        }
    });
}

function contabilizar() {
    alertify.confirm("Desea contabilizar esta factura?",
        function (e) {
            if (e) {
                alertify.alert("Holii");
                alertify.alert("id_proveedor=" + $("#id_proveedor").val() + "&comprobante=" + $("#comprobante").val() + "&fecha_actual=" + $("#fecha_actual").val() + "&hora_actual=" + $("#hora_actual").val() + "&fecha_registro=" + $("#fecha_registro").val() + "&fecha_emision=" + $("#fecha_emision").val() + "&fecha_caducidad=" + $("#fecha_caducidad").val() + "&tipo_comprobante=" + $("#tipo_comprobante").val() + "&serie=" + seriee + "&autorizacion=" + $("#autorizacion").val() + "&cancelacion=" + $("#cancelacion").val() + "&formas=" + forma_p + "&tarifa0=" + $("#total_p").val() + "&tarifa12=" + $("#total_p2").val() + "&iva=" + $("#iva").val() + "&desc=" + $("#desc").val() + "&tot=" + $("#tot").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&observaciones=" + observa + "&pago_ats=" + pago_ats);
                $.ajax({
                    type: "POST",
                    url: "contabilizar_factura_compra.php",
                    data: "id_proveedor=" + $("#id_proveedor").val() + "&comprobante=" + $("#comprobante").val() + "&fecha_actual=" + $("#fecha_actual").val() + "&hora_actual=" + $("#hora_actual").val() + "&fecha_registro=" + $("#fecha_registro").val() + "&fecha_emision=" + $("#fecha_emision").val() + "&fecha_caducidad=" + $("#fecha_caducidad").val() + "&tipo_comprobante=" + $("#tipo_comprobante").val() + "&serie=" + seriee + "&autorizacion=" + $("#autorizacion").val() + "&cancelacion=" + $("#cancelacion").val() + "&formas=" + forma_p + "&tarifa0=" + $("#total_p").val() + "&tarifa12=" + $("#total_p2").val() + "&iva=" + $("#iva").val() + "&desc=" + $("#desc").val() + "&tot=" + $("#tot").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&observaciones=" + observa + "&pago_ats=" + pago_ats,
                    success: function (data) {
                        var val = data;
                        if (val != 0) {
                            alertify.alert("Factura Contabilizada correctamente");
                        } else {
                            alertify.alert("Datos Erroneosgg");
                        }
                    }
                });
            } else {
                alertify.success("Ha cancelado la transacciÃ³n");
            }
        }
    );
}

function cancelar() {
    $("#list2").jqGrid("clearGridData", true);
    $("#series").dialog("close");
    $("#descuento").focus();
    $("#btnAgregar").attr("disabled", false);
}

function limpiar_factura() {
    location.reload();
}

function limpiar_campo() {
    if ($("#ruc_ci").val() == "") {
        $("#id_proveedor").val("");
        $("#empresa").val("");
    }
}

function limpiar_campo2() {
    if ($("#codigo").val() == "") {
        $("#cod_producto").val("");
        $("#codigo_barras").val("");
        $("#producto").val("");
        $("#cantidad").val("");
        $("#precio").val("");
        $("#precio_v").val("");
        $("#descuento").val("");
        $("#iva_producto").val("");
        $("#carga_series").val("");
        $("#incluye").val("");
    }
}

function limpiar_campo3() {
    if ($("#producto").val() == "") {
        $("#cod_producto").val("");
        $("#codigo_barras").val("");
        $("#codigo").val("");
        $("#cantidad").val("");
        $("#precio").val("");
        $("#precio_v").val("");
        $("#descuento").val("");
        $("#iva_producto").val("");
        $("#carga_series").val("");
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
var calculoIVA = 0;

function reenviar(id) {
    $.ajax({
        type: "POST",
        url: "guardar_ret_fuente_fact_compra.php",
        data: {
            reenviarcorreo: 'reenviarcorreo',
            id: id
        },
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
function guardar_serie() {
    var tam2 = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
    if ($("#formas").val() == "otros") {
        if ($("#formas").val() == "otros" && $("#valor_factura_saldo").val() != "0.00") {
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
                    //                $('#contado_form').prop('selected', true);
                    $.ajax({
                        type: "POST",
                        url: "guardar_forma_mixto.php",
                        data: "id_factura_venta=" + $("#id_factura_venta").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&campo6=" + string_v6 + "&campo7=" + string_v7 + "&comprobante=" + $("#comprobante").val() + "&formaspago_mixto=" + $("#formaspago_mixto").val() + "&tarjetas=" + $("#tarjetas").val() + "&num_tarjeta=" + $("#num_tarjeta").val() + "&fecha_actual=" + $("#fecha_actual").val() + "&fecha_dias=" + $("#fecha_dias").val(),
                        success: function (data) {
                            var val = data;
                            if (val == 1) {
                                guardar_asiento_contable();

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
function agregar1() {
    if (!!!$("#formaspago_mixto").val()) {
        $("#alertify-logs").empty();
        alertify.error("Seleccione una forma de pago.");
        $("#formaspago_mixto").focus();
        return;
    }


    $("#validar_guardar_grid").val('1');

    var subtotal_adelanto = 0;
    var subtotal_adelanto1 = 0;



    var subtotal1 = 0;
    var subtotal11 = 0;
    var fil = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
    for (var t = 0; t < fil.length; t++) {
        var dd = fil[t];
        subtotal1 = (parseFloat($("#valor_formas").val()) + (parseFloat(dd['valor'])));
    }

    subtotal11 = (parseFloat($("#valor_formas").val()) + parseFloat($("#cantidad_mixto").val()));
    console.log("DDD" + subtotal11.toFixed(2));
    if (parseFloat(subtotal11.toFixed(2)) > parseFloat($("#totx").val())) {
        alertify.error("Error1.. La suma supera el total de la Factura " + $("#totx").val());
    } else {
        if (parseFloat($("#cantidad_mixto").val()) > parseFloat($("#totx").val())) {
            alertify.error("Error2.. La suma supera el total de la Factura " + $("#totx").val());
        } else {
            if (parseFloat(subtotal1.toFixed(2)) > parseFloat($("#totx").val())) {
                alertify.error("Error3.. La suma supera el total de la Factura " + $("#totx").val());
            } else {
                if (parseFloat($("#valor_formas").val()) > parseFloat($("#totx").val())) {
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
                                    } else {
                                        if ($("#formaspago_mixto").val() == 'TDebito' && $("#idCuenta").val() == "") {
                                            $("#cuenta_contable").focus();
                                            alertify.error("Error.. Debe seleccionar Cuenta contable");
                                        } else {
                                            if ($("#formaspago_mixto").val() == 'Contado' && $("#idCuenta").val() == "") {
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
    }

}
function abrirCuenta() {
    $("#cuentas").dialog("open");
}
function cargar_cuentas() {
    var id = $("#formaspago_mixto").val();

    $("#list4").jqGrid('setGridParam', {
        url: 'xmlPlanCuentas_btn.php?id=' + id,
        datatype: 'xml'
    }).trigger('reloadGrid');


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
function guardar_asiento_contable() {

    //  if ($("#guardado_reten").val() == "1") {
    var observa = "Ninguna";
    var forma_p = "";
    var bien_ser = "";
    var pago_ats = "";
    var str = $("#serie").val();
    var res = str.split("-");

    var ele1 = res[0];
    var ele2 = res[1];
    var ele3 = res[2];
    var ele22 = ele3.substring(8, 9);

    var tam = jQuery("#list").jqGrid("getRowData");

    //                            guardar_serie();
    var num_fac = $("#serie").val();


    ///////////////guardar gastos///////////////////
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

    //alertify.alert("hola");
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
    var seriee = $("#serie").val();
    observa = "Ninguna";
    $("#btnGuardar").attr("disabled", true);
    $.ajax({
        type: "POST",
        url: "guardar_asiento_contable.php",
        data: "id_gastos=" + $("#comprobante").val() + "&num_factura=" + $("#serie").val() + "&comprobante=" + $("#comprobante").val() + "&fecha_actual=" + $("#fecha_actual").val() + "&fecha_emision=" + $("#fecha_emision").val() + "&hora_actual=" + $("#hora_actual").val() + "&descripcion=" + $("#descripcion").val() + "&valor=" + $("#totx").val() + "&subtotal=" + $("#subx").val() + "&iva=" + $("#ivax").val() + "&proveedor=" + $("#id_proveedor").val() + "&deposito=" + $("#deposito").val() + "&banco=" + $("#banco").val() + "&num_cuenta=" + $("#cuentanum").val() + "&num_autorizacion=" + $("#autorizacion").val() + "&campo1=" + string_v1 + "&idCuenta=" + $("#idCuenta").val() + "&fecha_caducidad=" + $("#fecha_caducidad").val() + "&tipo_comprobante=" + $("#tipo_comprobante").val() + "&serie=" + seriee + "&autorizacion=" + $("#autorizacion").val() + "&cancelacion=" + $("#cancelacion").val() + "&formas=" + forma_p + "&tarifa0=" + $("#total_p").val() + "&tarifa12=" + $("#total_p2").val() + "&iva=" + $("#iva").val() + "&desc=" + $("#desc").val() + "&tot=" + $("#tot").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&observaciones=" + observa + "&pago_ats=" + pago_ats + "&bien_servi=" + bien_ser + "&idCuenta=" + $("#idCuenta").val() + "&formascc=" + $("#formas").val() + "&bien_servicio=" + $("#bien_servicio").val() + "&descripcion=" + $("#comentario").val(),
        success: function (data) {
            var val = data;
            if (val != 0) {

                if (document.getElementById('elegirretencionF1').checked == true) {
                    guardar_retenciones_factura_compra_directo_c();
                } else {
                    if ($("#valor_reten").val() == "") {

                        guardar_retenciones_factura_compra_g();
                    } else {
                        location.reload();
                    }

                }


                alertify.success("Gasto Guardado correctamente");
                //                 window.open("../../reportes/factura_compra.php?hoja=A4&id=" + $("#comprobante").val(), '_blank');
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
                                var seriee = num_serie_ret + "-" + a + "" + $("#serie_retencion").val();
                                //TODO borrar comentado
                                /* if ($("#punto_ventaid").val() == 1) {
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
                                var v7 = new Array();

                                var string_v1 = "";
                                var string_v2 = "";
                                var string_v3 = "";
                                var string_v4 = "";
                                var string_v5 = "";
                                var string_v6 = "";
                                var string_v7 = "";
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
                                    v7[i] = datos['tipo_ret'];

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
                                if ($("#calculoRetencionI").val() == '0.000') {
                                    var calculoretencionii = document.getElementById("tipoRetencionesIs").selectedIndex;
                                } else {
                                    calculoretencionii = y;
                                }
                                $("#btnGuardarRetenciones").attr("disabled", true);
                                $.ajax({
                                    type: "POST",
                                    url: "guardar_ret_fuente_fact_compra.php",
                                    data: "id_factura=" + $("#comprobante").val() + "&id_retencion_fuente=" + x + "&fecha_actual=" + $("#fecha_actual").val() + "&valor_factura=" + $("#sub").val() + "&iva_factura=" + $("#iva").val() + "&valor_retencion=" + $("#calculoRetencionF").val() + "&autorizacion_ret=" + $("#autorizacion_retencion").val() + "&serie_retencion=" + seriee + "&porcent_reten=" + $("#porcent_reten").val() + "&id_retencion_iva=" + y + "&valor_facturaiva=" + $("#tot").val() + "&valor_retencioni=" + $("#calculoRetencionI").val() + "&valor_seleccion_iva=" + xx + "&porcent_iva=" + $("#porcent_iva").val() + "&id_retencion_fuentes=" + xs + "&valor_retencions=" + $("#calculoRetencionFS").val() + "&porcent_retens=" + $("#porcent_retens").val() + "&valor_seleccion_si_no=" + xxs + "&campo1reten=" + string_v1 + "&campo2reten=" + string_v2 + "&campo3reten=" + string_v3 + "&campo4reten=" + string_v4 + "&campo5reten=" + string_v5 + "&campo6reten=" + string_v6 + "&campo7reten=" + string_v7 + "&total_reten_iva=" + $("#total_retencion").val() + "&formascc=" + $("#formas").val(),
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
                                            //                                            window.open("../../reportes/factura_compra.php?hoja=A5&id=" + data.id, '_blank');
                                            //                                            window.open("../../reportes/transacciones_2.php?hoja=A5&id=" + data.id, '_blank');
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
                                            //                                            alertify.alert(val);
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
            //var num_retencion = ("001" + "-" + "001" + "-" + $("#serie_sinretencion").val());
            //var num_retencion = ("001" + "-" + "001" + "-" + $("#serie_sinretencion").val());
            var num_retencion = num_serie_ret + "-" + $("#serie_retencion").val();
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
                        var seriee = num_serie_ret + "-" + $("#serie_retencion").val();



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
                                        //                                        window.open("../../reportes/transacciones_2.php?hoja=A5&id=" + $("#comprobante").val(), '_blank');
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
}
function addCliente() {
    $.getScript("../registro_gastos/proveedores/proveedores.js", function () {
        let cmpAddCliente = new AddCliente();
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
        data: { term: term }
    });
}

function inicio() {

    $("#cantidad").keyup(function () {
        if ($("#tipo_iva").val() == "Si") {
            $("#iva_producto").val("Si");
        } else {
            $("#iva_producto").val("No");
        }
    });
    if ($("#tipo_iva").val() == "Si") {
        $("#iva_producto").val("Si");
    } else {
        $("#iva_producto").val("No");
    }
    $("#tipo_iva").on("change", function () {
        if ($("#tipo_iva").val() == "Si") {
            $("#iva_producto").val("Si");
        } else {
            $("#iva_producto").val("No");
        }
    });


    $("#icex").change(function () {
        funcion_ice_factura();
    });
    $("#icex").mousemove(function () {
        funcion_ice_factura();
    });

    $("#irbpx").change(function () {
        funcion_ice_factura();
    });
    $("#irbpx").mousemove(function () {
        funcion_ice_factura();
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
    addCliente();
    $("#btnClientes").click(function (e) {
        $("#dialog_form_cliente").dialog("open")
    });
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

    var num_retencion = (num_serie_ret + "-" + $("#serie_retencion").val());
    $("#serie_retencion").change(function () {
        $.ajax({
            type: "POST",
            url: "../factura_compra/comparar_num_retencion.php",
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
    //    $("#formaspago_mixto").on("change", function () {
    //        if ($("#formaspago_mixto").val() == "Credito") {
    //            $("#cuenta_contable").attr("disabled", true);
    //            $('#fecha_vencimiento').show();
    //            $("#btnCuenta").attr("disabled", true);
    //            //            $("#cheque_tarjeta").attr("disabled", false);
    //            //            $("#banco").attr("disabled", false);
    //        } else if ($("#formaspago_mixto").val() == "Transferencias" || $("#formaspago_mixto").val() == "Cheque" || $("#formaspago_mixto").val() == "TCredito") {
    //            cargar_cuentas();
    //            $("#cuenta_contable").attr("disabled", false);
    //            $("#btnCuenta").attr("disabled", false);
    //            $("#cuenta_contable").val("");
    //            $("#idCuenta").val("");
    //            $('#fecha_vencimiento').hide();
    //            //            $("#cheque_tarjeta").attr("disabled", true);
    //            //            $("#banco").attr("disabled", true);
    //        } else if ($("#formaspago_mixto").val() == "Contado") {
    //
    //            /*  $("#cuenta_contable").attr("disabled", true);
    //             $("#btnCuenta").attr("disabled", true);
    //             $("#cuenta_contable").val("");
    //             $("#idCuenta").val("");
    //             $('#fecha_vencimiento').hide(); */
    //
    //            cargar_cuentas();
    //            $("#cuenta_contable").attr("disabled", false);
    //            $("#btnCuenta").attr("disabled", false);
    //            $("#cuenta_contable").val("");
    //            $("#idCuenta").val("");
    //            $('#fecha_vencimiento').hide();
    //
    //
    //            //            $("#cheque_tarjeta").attr("disabled", true);
    //            //            $("#banco").attr("disabled", true);
    //
    //        }
    //    })
    $("#tab_4").prop("disabled", true);
    $("#cuentas").dialog(dialogo_cuenta);
    $("#btnCuenta").click(function (e) {
        e.preventDefault();
    });
    $("#btnCuenta").on("click", abrirCuenta);

    $('#fecha_vencimiento').hide();
    $("#tab_4").prop("disabled", true);
    $("#formas").change(function () {
        var tam2 = jQuery("#list").jqGrid("getRowData");
        if ($("#formas").val() == "Contado") {
            $("#adelanto").attr("disabled", "disabled");
            $("#adelanto").val("");
            $("#valor_factura").val("");
            $("#meses").attr("disabled", "disabled");
            $("#meses").val("");
            $("#cuotas").attr("disabled", "disabled");
            $('#cuotas').children().remove().end();
        } else {
            if ($("#formas").val() == "otros") {
                if (tam2.length > 0) {
                    $('.nav-tabs a[href="#tab_4"]').tab('show')
                    $("#formaspago_mixto").attr("disabled", false);
                    //                      $("#valor_factura").val($("#totx").val());
                } else {
                    $('#contado_form').prop('selected', true);
                    alertify.error("Ingrese Productos");
                }
            }
        }
    });
    $("#formaspago_mixto").on("change", function () {
        if ($("#formaspago_mixto").val() == "Credito") {
            $("#cuenta_contable").attr("disabled", true);
            $('#fecha_vencimiento').show();
            $("#btnCuenta").attr("disabled", true);
            $("#cuenta_contable").val("");
            $("#idCuenta").val("");
        } else if ($("#formaspago_mixto").val() == "Transferencias" || $("#formaspago_mixto").val() == "Cheque" || $("#formaspago_mixto").val() == "TCredito" || $("#formaspago_mixto").val() == "TDebito") {
            cargar_cuentas();
            $("#cuenta_contable").attr("disabled", false);
            $("#btnCuenta").attr("disabled", false);
            $("#cuenta_contable").val("");
            $("#idCuenta").val("");
            $('#fecha_vencimiento').hide();
        } else if ($("#formaspago_mixto").val() == "Contado") {
            cargar_cuentas();
            $("#cuenta_contable").attr("disabled", false);
            $("#btnCuenta").attr("disabled", false);
            $("#cuenta_contable").val("");
            $("#idCuenta").val("");
            $('#fecha_vencimiento').hide();
        }
    })
    listaPagoRetencion();
    $("#btnAgregar_mixto").click(function (e) {
        e.preventDefault();
        agregar1();
    });
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

    $("[data-mask]").inputmask();
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

    function combo() {
        $.ajax({
            type: "POST",
            url: "buscar_producto.php",
            success: function (resp) {
                combo_1 = JSON.parse(resp);
            }
        });
        return combo_1;
    }

    function combo1() {
        $.ajax({
            type: "POST",
            url: "buscar_producto2",
            success: function (resp) {
                combo_2 = JSON.parse(resp);
            }
        });
        return combo_2;
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
    $("#btnCancelarSeries").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardar").click(function (e) {
        e.preventDefault();
    });
    //    $("#btnGuardarTemporal").click(function(e) {
    //        e.preventDefault();
    //    });
    $("#btnModificar").click(function (e) {
        e.preventDefault();
    });
    $("#btnCancelar").click(function (e) {
        e.preventDefault();
    });
    $("#btnNuevo").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardarRetenciones_mixto").click(function (e) {
        e.preventDefault();
    });
    $("#btnContabilizar").click(function (e) {
        e.preventDefault();
    });
    $("#btnImprimir").click(function () {

        $.ajax({
            type: "POST",
            url: "buscar_retencion.php",
            data: "comprobante=" + $("#comprobante").val(),
            success: function (data) {
                var val = data;
                if (val != "") {
                    window.open(formatoRC + "?hoja=A4&id=" + $("#comprobante").val(), '_blank');
                } else {
                    window.open(formatoFC + "?hoja=A4&id=" + $("#comprobante").val(), '_blank');
                    //                window.open("../../reportes/transacciones_2.php?hoja=A5&id=" + $("#comprobante").val(), '_blank');
                }
                //               window.open("../../reportes/factura_compra.php?hoja=A4&id=" + val, '_blank');
                location.reload();
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
    //    $("#btnCancelarRetenciones").click(function (e) {
    //        e.preventDefault();
    //    });
    $("#btnImprimirRetenciones").click(function () {
        window.open("../../reportes/retenciones.php?hoja=A4&id=" + $("#comprobante").val(), '_blank');
    });
    $("#btnAcceder").click(function (e) {
        e.preventDefault();
    });
    $("#buscar_estados").dialog(dialogo10);

    $("#btncargar").on("click", abrirDialogo);
    //    $("#btnAgregar").on("click", agregar);
    $("#btnAnadirForma").on("click", agregarForma);
    $("#btnGuardarSeries").on("click", guardar_serie);
    $("#btnCancelarSeries").on("click", cancelar);
    $("#btnGuardar").on("click", guardar_factura);
    //    $("#btnGuardarTemporal").on("click", guardar_factura_temporal);
    $("#btnModificar").on("click", modificar_factura);
    $("#btnEliminar").on("click", eliminar_factura);
    $("#btnNuevo").on("click", limpiar_factura);
    $("#btnAtras").on("click", flecha_atras);
    $("#btnAdelante").on("click", flecha_siguiente);
    $("#btnAceptar").on("click", aceptarEliminar);
    $("#btnSalir").on("click", cancelarEliminar);
    $("#btnAcceder").on("click", validar_acceso);
    $("#btnContabilizar").on("click", contabilizar);
    $("#btnGuardarRetenciones").on("click", guardar_retenciones_factura_compra);
    //    $("#btnCancelarRetenciones").on("click", function (e) {
    //        location.reload();
    //    });
    $("#btnGuardarRetenciones_mixto").on("click", guardar_serie);
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
    // $("#tipoRetencionesFS").on("change",buscar_servicio_producto);
    $("#tipoRetencionesFS").on("change", calculo_ret_fuenteS);
    $("#tipoRetencionesI").on("change", calculo_ret_iva);
    $("#tipoRetencionesIs").on("change", calculo_ret_ivas);

    $("#formasPago").on("change", cambioForma);
    $("#series").dialog(dialogo);
    $("#buscar_facturas_compras").dialog(dialogo2);
    $("#clave_permiso").dialog(dialogo3);
    $("#seguro").dialog(dialogo4);

    $("#btnBuscar").click(function () {
        $("#buscar_facturas_compras").dialog("open");
    });

    $("#cantidad").validCampoFranz("0123456789");
    $("#autorizacion").validCampoFranz("0123456789");
    $("#descuento").validCampoFranz("0123456789.");

    $("#ruc_ci").on("keyup", limpiar_campo);
    $("#codigo").on("keyup", limpiar_campo2);
    $("#producto").on("keyup", limpiar_campo3);
    $("#codigo").on("keypress", enter);
    $("#producto").on("keypress", enter);
    $("#cantidad").on("keypress", enter);
    $("#precio").on("keypress", enter2);
    $("#precio_v").on("keypress", enter_precio_v);
    $("#descuento").on("keypress", enter3);
    $("#ruc_ci").on("keypress", enter4);
    $("#empresa").on("keypress", enter4);
    $("#serie").on("keypress", enter4);
    $("#autorizacion").on("keypress", enter5);
    $("#calculoRetencionF").on("keypress", enter7);
    $("#calculoRetencionFS").on("keypress", enter7);
    $("#calculoRetencionI").on("keypress", enter7);
    $("#calculoRetencionIs").on("keypress", enter7);

    // atributos
    $("#ruc_ci").attr("disabled", "disabled");
    $("#empresa").attr("disabled", "disabled");
    $("#adelanto").attr("disabled", "disabled");
    $("#meses").attr("disabled", "disabled");
    $("#cuotas").attr("disabled", "disabled");

    // para precio
    $("#precio").on("keypress", punto);
    $("#precio_v").on("keypress", punto);

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
                    $("#correo").val(ui.item.correo);
                    $("#id_proveedor").val(ui.item.id_proveedor);
                    return false;
                },
                select: function (event, ui) {
                    $("#ruc_ci").val(ui.item.value);
                    $("#empresa").val(ui.item.empresa);
                    $("#correo").val(ui.item.correo);
                    $("#id_proveedor").val(ui.item.id_proveedor);
                    return false;
                }
            }).data("ui-autocomplete")._renderItem = function (ul, item) {
                return $("<li>")
                    .append("<a>" + item.value + "</a>")
                    .appendTo(ul);
            };

            $("#tipo_comprobante").val("NOTA VENTA");
            $("#tipo_comprobante").attr("disabled", "disabled");
            $("#ruc_ci").val("");
            $("#empresa").val("");
            $("#correo").val("");
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
                        $("#correo").val(ui.item.correo);
                        $("#id_proveedor").val(ui.item.id_proveedor);
                        return false;
                    },
                    select: function (event, ui) {
                        $("#ruc_ci").val(ui.item.value);
                        $("#empresa").val(ui.item.empresa);
                        $("#correo").val(ui.item.correo);
                        $("#id_proveedor").val(ui.item.id_proveedor);
                        return false;
                    }

                }).data("ui-autocomplete")._renderItem = function (ul, item) {
                    return $("<li>")
                        .append("<a>" + item.value + "</a>")
                        .appendTo(ul);
                };

                $("#tipo_comprobante").val("FACTURA");
                $("#tipo_comprobante").attr("disabled", false);

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
                            $("#correo").val(ui.item.correo);
                            $("#id_proveedor").val(ui.item.id_proveedor);
                            return false;
                        },
                        select: function (event, ui) {
                            $("#ruc_ci").val(ui.item.value);
                            $("#empresa").val(ui.item.empresa);
                            $("#correo").val(ui.item.correo);
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
                    $("#correo").val("");
                    $("#id_proveedor").val("");
                }
            }
        }
    });

    $('.ui-spinner-button').click(function () {
        $(this).siblings('input').change();
    });

    // calcular meses
    $("#meses").change(function () {
        $("#meses").val("");
        if ($("#formas").val() == "Contado") {
            alertify.alert("Error...No se puede diferir");
        } else {
            if ($("#formas").val() == "Credito") {
                var resta = ($("#tot").val() - $("#adelanto").val());
                var cuo = resta / $("#meses").val();
                var entero = parseFloat(cuo).toFixed(2);
                $("#cuotas").val(entero);
            }
        }
    });
    // fin

    // series
    $("#formas").change(function () {
        var tam2 = jQuery("#list").jqGrid("getRowData");

        if ($("#formas").val() == "Contado") {
            $("#adelanto").attr("disabled", "disabled");
            $("#adelanto").val("");
            $("#meses").attr("disabled", "disabled");
            $("#meses").val("");
            $("#cuotas").attr("disabled", "disabled");
            $("#cuotas").val("");
        } else {
            if ($("#formas").val() == "Credito") {
                if (tam2.length > 0) {
                    $("#adelanto").removeAttr("disabled");
                    $("#meses").removeAttr("disabled");
                    $("#cuotas").removeAttr("disabled");
                } else {
                    alertify.alert("Error...Ingrese un monto a la factura");
                    $("#formas option[value=" + 'Contado' + "]").attr("selected", true);
                }
            }
        }
    });
    // fin

    // buscar producto codigo barras 
    $("#codigo_barras").change(function (e) {
        var codigo = $("#codigo_barras").val();
        var cod = $("#codigo_barras").val();
        $.getJSON('search.php?codigo_barras=' + codigo + "&cod=" + cod, function (data) {
            var tama = data.length;
            if (tama != 0) {
                for (var i = 0; i < tama; i = i + 8) {
                    $("#codigo").val(data[i]);
                    $("#producto").val(data[i + 1]);
                    $("#precio").val(data[i + 2]);
                    $("#iva_producto").val(data[i + 3]);
                    $("#tipo_iva").val(data[i + 3]);
                    $("#carga_series").val(data[i + 4]);
                    $("#cod_producto").val(data[i + 5]);
                    $("#incluye").val(data[i + 6]);
                    $("#precio_v").val(data[i + 7]);
                    $("#cantidad").focus();
                }
            } else {
                $("#codigo").val("");
                $("#producto").val("");
                $("#precio").val("");
                $("#iva_producto").val("");
                $("#carga_series").val("");
                $("#cod_producto").val("");
                $("#incluye").val("");
                $("#cantidad").val("");
                alertify.error("Producto no ingresado");
                $("#codigo_barras").val("");
                $("#precio_v").val("");
                $("#tipo_iva").val("Si");
            }
        });
    });
    // fin

    // buscar producto codigo
    $("#codigo").autocomplete({
        source: "buscar_codigo.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#codigo_barras").val(ui.item.codigo_barras);
            $("#codigo").val(ui.item.value);
            $("#producto").val(ui.item.producto);
            $("#precio").val(ui.item.precio);
            $("#iva_producto").val(ui.item.iva_producto);
            $("#tipo_iva").val(ui.item.iva_producto);
            $("#carga_series").val(ui.item.carga_series);
            $("#cod_producto").val(ui.item.cod_producto);
            $("#incluye").val(ui.item.incluye);
            $("#precio_v").val(ui.item.iva_minorista);
            //         $("#punto_venta_inv").val(ui.item.punto_venta);
            return false;
        },
        select: function (event, ui) {
            $("#codigo_barras").val(ui.item.codigo_barras);
            $("#codigo").val(ui.item.value);
            $("#producto").val(ui.item.producto);
            $("#precio").val(ui.item.precio);
            $("#iva_producto").val(ui.item.iva_producto);
            $("#carga_series").val(ui.item.carga_series);
            $("#cod_producto").val(ui.item.cod_producto);
            $("#incluye").val(ui.item.incluye);
            $("#precio_v").val(ui.item.iva_minorista);
            $("#tipo_iva").val(ui.item.iva_producto);
            //         $("#punto_venta_inv").val(ui.item.punto_venta);
            return false;
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };
    // fin

    // buscar producto articulo
    $("#producto").autocomplete({
        source: "buscar_producto.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#codigo_barras").val(ui.item.codigo_barras);
            $("#producto").val(ui.item.value);
            $("#codigo").val(ui.item.codigo);
            $("#precio").val(ui.item.precio);
            $("#iva_producto").val(ui.item.iva_producto);
            $("#carga_series").val(ui.item.carga_series);
            $("#cod_producto").val(ui.item.cod_producto);
            $("#incluye").val(ui.item.incluye);
            $("#precio_v").val(ui.item.iva_minorista);
            $("#tipo_iva").val(ui.item.iva_producto);
            //         $("#punto_venta_inv").val(ui.item.punto_venta);
            return false;
        },
        select: function (event, ui) {
            $("#codigo_barras").val(ui.item.codigo_barras);
            $("#producto").val(ui.item.value);
            $("#codigo").val(ui.item.codigo);
            $("#precio").val(ui.item.precio);
            $("#iva_producto").val(ui.item.iva_producto);
            $("#carga_series").val(ui.item.carga_series);
            $("#cod_producto").val(ui.item.cod_producto);
            $("#incluye").val(ui.item.incluye);
            $("#precio_v").val(ui.item.iva_minorista);
            $("#tipo_iva").val(ui.item.iva_producto);
            //         $("#punto_venta_inv").val(ui.item.punto_venta);
            return false;
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };
    // fin

    /* $('#fecha_actual').datepicker({
     dateFormat: 'yy-mm-dd'
     }).datepicker('setDate', 'today'); */
    $('#fecha_actual').val(new Date().toLocaleDateString("fr-CA"));
    $('#fecha_registro').datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');
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
        colNames: ['', 'ID', 'Còdigo', 'Detalle', 'Cantidad', 'Precio. Ux', 'Descuentox', 'Calculadox', 'Totalx', 'Precio. U', 'Descuento', 'Calculado', 'Total', 'Iva', 'Incluye', 'Precio V.'],
        colModel: [
            { name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: { keys: false, delbutton: true, editbutton: false } },
            { name: 'cod_producto', index: 'cod_producto', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },
            { name: 'codigo', index: 'codigo', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'detalle', index: 'detalle', editable: false, frozen: true, editrules: { required: true }, align: 'center', width: 290 },
            {
                name: 'cantidad', index: 'cantidad', editable: false, frozen: true, editrules: { required: true }, align: 'center', width: 70, editoptions: {
                    maxlength: 10, size: 15, dataInit: function (elem) {
                        $(elem).bind("keypress", function (e) {
                            return numeros(e)
                        })
                    }
                }
            },
            {
                name: 'precio_u', index: 'precio_u', hidden: true, editable: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: 110, editoptions: {
                    maxlength: 10, size: 15, dataInit: function (elem) {
                        $(elem).bind("keypress", function (e) {
                            return punto(e)
                        })
                    }
                }
            },
            { name: 'descuento', index: 'descuento', hidden: true, editable: false, frozen: true, editrules: { required: true }, align: 'center', width: 70 },
            { name: 'cal_des', index: 'cal_des', hidden: true, editable: false, hidden: true, frozen: true, editrules: { required: true }, align: 'center', width: 90 },
            { name: 'total', index: 'total', hidden: true, editable: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: 110 },
            {
                name: 'precio_ux', index: 'precio_ux', editable: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: 110, editoptions: {
                    maxlength: 10, size: 15, dataInit: function (elem) {
                        $(elem).bind("keypress", function (e) {
                            return punto(e)
                        })
                    }
                }
            },
            { name: 'descuentox', index: 'descuentox', editable: false, frozen: true, editrules: { required: true }, align: 'center', width: 70 },
            { name: 'cal_desx', index: 'cal_desx', editable: false, hidden: true, frozen: true, editrules: { required: true }, align: 'center', width: 90 },
            { name: 'totalx', index: 'totalx', editable: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: 110 },
            { name: 'iva', index: 'iva', align: 'center', width: 100, hidden: true },
            { name: 'incluye', index: 'incluye', editable: false, hidden: true, frozen: true, editrules: { required: true }, align: 'center', width: 90 },
            { name: 'precio_v', index: 'precio_v', editable: false, hidden: false, width: 100 }
        ],
        rowNum: 30,
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


                var fil = jQuery("#list").jqGrid("getRowData");
                for (var t = 0; t < fil.length; t++) {
                    if (ret.iva == "Si") {
                        // if(ret.incluye == "No") {
                        subtotal = ret.total;
                        sub1 = subtotal;
                        iva1 = sub1 * toFixedDown((calculoIVA / 100), 3);

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
                $("#tot").val(total_total);
                $("#total_px").val(subtotal0.toFixed(4));
                $("#total_p2x").val(subtotal12.toFixed(4));
                $("#subx").val(subtotal_total.toFixed(4));
                $("#ivax").val(iva12.toFixed(4));
                $("#descx").val(descu_total.toFixed(4));
                $("#totx").val(total_total.toFixed(4));

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

                var operacion = parseFloat(val) * parseFloat(precio);
                cal = (operacion * descuento) / 100;
                tot = operacion - cal;

                jQuery("#list").jqGrid('setRowData', rowid, { precio_t: tot });

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
                    $("#tot").val(t_fc);
                    $("#total_p2x").val(sub.toFixed(4));
                    $("#ivax").val(iva.toFixed(4));
                    $("#descx").val(descu.toFixed(4));
                    $("#totx").val(t_fc.toFixed(4));
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
                    $("#tot").val(t_fc);
                    $("#total_px").val(sub.toFixed(4));
                    $("#descx").val(descu.toFixed(4));
                    $("#totx").val(t_fc.toFixed(4));
                }
            }

            if (name == 'precio_u') {
                var cantidad = jQuery("#list").jqGrid('getCell', rowid, iCol - 1);
                var descuento2 = jQuery("#list").jqGrid('getCell', rowid, iCol + 1);
                var operacion2 = parseFloat(cantidad) * parseFloat(val);
                cal2 = (operacion2 * descuento2) / 100;
                tot = operacion2 - cal2;

                jQuery("#list").jqGrid('setRowData', rowid, { precio_t: tot });

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
                    $("#tot").val(t_fc);
                    $("#total_p2x").val(sub.toFixed(2));
                    $("#ivax").val(iva.toFixed(2));
                    $("#descx").val(descu.toFixed(2));
                    $("#totx").val(t_fc.toFixed(2));
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
                    $("#tot").val(t_fc);
                    $("#total_px").val(sub.toFixed(2));
                    $("#descx").val(descu.toFixed(2));
                    $("#totx").val(t_fc.toFixed(2));
                }
            }
        }
    });

    jQuery("#listPago").jqGrid({
        datatype: "local",
        colNames: ['', 'Id Forma', 'Codigo', 'Descripcion'],
        colModel: [
            {
                name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions',
                formatoptions: { keys: false, delbutton: true, editbutton: false }
            },
            { name: 'id_forma', index: 'id_forma', hidden: true, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'codigo', index: 'codigo', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'descripcion', index: 'descripcion', editable: true, align: 'center', width: '690', search: true, frozen: true, formoptions: { elmsuffix: " (*)" }, editrules: { required: true } }
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
    jQuery("#listPagoreten").jqGrid({
        datatype: "local",
        colNames: ['', 'Base Imponible', 'Impuesto', '% Retenciòn ', 'Valor Retenido ', 'Id_retenciones ', 'tipo_ret'],
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
        },
        {
            name: 'tipo_ret',
            index: 'tipo_ret',
            hidden: true,
            align: 'center',
            width: '180',
            frozen: true,
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

    // tabla series
    // tabla series
    jQuery("#list2").jqGrid({
        datatype: "local",
        colNames: ['', 'cod_serie', 'Series'],
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
            name: 'id_serie',
            index: 'id_serie',
            editable: false,
            search: false,
            hidden: true,
            editrules: {
                edithidden: false
            },
            align: 'center',
            frozen: true,
            width: 50
        },
        {
            name: 'serie_campos',
            index: 'serie_campos',
            editable: false,
            search: false,
            hidden: false,
            editrules: {
                edithidden: true
            },
            align: 'center',
            frozen: true,
            width: 100
        }
        ],
        rowNum: 30,
        width: 450,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager2'),
        sortname: 'id_serie',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'clientArray',
        shrinkToFit: true,
        delOptions: {
            onclickSubmit: function (rp_ge, rowid) {
                rp_ge.processing = true;
                var su = jQuery("#list2").jqGrid('delRowData', rowid);
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
    }).jqGrid('navGrid', '#pager2', {
        add: false,
        edit: false,
        del: false,
        refresh: true,
        search: true,
        view: true
    });
    // Fin


    // buscador facturas compra 
    jQuery("#list3").jqGrid({
        url: 'xmlBuscarFacturaCompra.php',
        datatype: 'xml',
        colNames: ['ID', 'IDENTIFICACIÒN', 'EMPRESA', 'FACTURA NRO.', 'MONTO TOTAL', 'FECHA'],
        colModel: [
            { name: 'id_factura_compra', index: 'id_factura_compra', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },
            { name: 'identificacion_pro', index: 'identificacion_pro', editable: false, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 150 },
            { name: 'empresa_pro', index: 'empresa_pro', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 200 },
            { name: 'num_serie', index: 'num_serie', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 200 },
            { name: 'total_compra', index: 'total_compra', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'fecha_compra', index: 'fecha_compra', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
        ],
        rowNum: 30,
        width: 750,
        height: 220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager3'),
        sortname: 'id_factura_compra',
        sortorder: 'asc',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list3").jqGrid('getGridParam', 'selrow');
            jQuery('#list3').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list3").jqGrid('getRowData', id);
                var valor = ret.id_factura_compra;

                // agregar factura compra
                $("#comprobante").val(valor);
                $("#btnGuardar").attr("disabled", true);
                //            $("#btnGuardarTemporal").attr("disabled", true);

                $("#ruc_ci").attr("disabled", "disabled");
                $("#formas").val("Contado");
                $("#list").jqGrid("clearGridData", true);
                $("#listPagoreten").jqGrid("clearGridData", true);
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

                $.getJSON('retornar_factura_compra.php?com=' + valor, function (data) {
                    var tama = data.length;
                    t = data[23];
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 24) {
                            $("#id_factura_compra").val(data[i]);
                            $("#fecha_actual").val(data[i + 1]);
                            $("#hora_actual").val(data[i + 2]);
                            $("#digitador").val(data[i + 3] + " " + data[i + 4]);
                            $("#id_proveedor").val(data[i + 5]);
                            $("#tipo_docu").val(data[i + 6]);
                            $("#ruc_ci").val(data[i + 7]);
                            $("#empresa").val(data[i + 8]);
                            $("#tipo_comprobante").val(data[i + 9]);
                            $("#fecha_registro").val(data[i + 10]);
                            $("#fecha_emision").val(data[i + 11]);
                            $("#fecha_caducidad").val(data[i + 12]);
                            $("#serie").val(data[i + 13]);
                            $("#autorizacion").val(data[i + 14]);
                            $("#cancelacion").val(data[i + 15]);
                            $("#formas").val(data[i + 16]);
                            $("#total_p").val(data[i + 17]);
                            $("#total_p2").val(data[i + 18]);
                            $("#sub").val((parseFloat(data[i + 17]) + parseFloat(data[i + 18])));
                            $("#iva").val(data[i + 19]);
                            $("#desc").val(data[i + 20]);
                            $("#tot").val(data[i + 21]);
                            $("#total_px").val(parseFloat(data[i + 17]).toFixed(4));
                            $("#total_p2x").val(parseFloat(data[i + 18]).toFixed(4));
                            $("#subx").val((parseFloat(data[i + 17]) + parseFloat(data[i + 18])).toFixed(4));
                            $("#ivax").val(parseFloat(data[i + 19]).toFixed(4));
                            $("#descx").val(parseFloat(data[i + 20]).toFixed(4));
                            $("#totx").val(parseFloat(data[i + 21]).toFixed(4));
                            $("#estado h3").remove();

                            if (data[i + 22] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulada"));
                                $("#estado h3").css("color", "red");
                                $("#btnEliminar").attr("disabled", "disabled");
                                $("#btnModificar").attr("disabled", true);
                            } else {
                                $("#estado h3").remove();
                                $("#btnEliminar").attr("disabled", "disabled");
                                $("#btnEliminar").attr("disabled", false);
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
                                    $("#formas").attr("disabled", false);
                                } else if (data[i + 23] == '1') {
                                    $("#btnModificar").attr("disabled", "disabled");
                                }
                            }
                        }
                        volver_rf();
                        volver_ri();
                    }
                });

                $.getJSON('retornar_factura_compra2.php?com=' + valor, function (data) {
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
                                cantidad: parseFloat(data[i + 3]).toFixed(2),
                                precio_u: precio,
                                descuento: desc,
                                cal_des: resultado,
                                total: data[i + 6],
                                precio_ux: precio.toFixed(4),
                                descuentox: parseFloat(desc).toFixed(4),
                                cal_desx: resultado.toFixed(4),
                                totalx: parseFloat(data[i + 6]).toFixed(4),
                                iva: data[i + 7],
                                incluye: data[i + 8],
                                precio_v: data[i + 9]

                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });
                $.getJSON('retornar_ice.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 2) {
                            $("#icex").val(data[i]);
                            $("#irbpx").val(data[i + 1]);
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
                $("#buscar_facturas_compras").dialog("close");

            } else {
                alertify.alert("Seleccione una Factura");
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
        caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#list3").jqGrid('getGridParam', 'selrow');
            jQuery('#list3').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list3").jqGrid('getRowData', id);
                var valor = ret.id_factura_compra;
                /////////////agregregar factura compra////////
                $("#comprobante").val(valor);
                $("#btnGuardar").attr("disabled", true);
                $("#ruc_ci").attr("disabled", "disabled");
                $("#formas").val("Contado");
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

                $.getJSON('retornar_factura_compra.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 22) {
                            $("#id_factura_compra").val(data[i]);
                            $("#fecha_actual").val(data[i + 1]);
                            $("#hora_actual").val(data[i + 2]);
                            $("#digitador").val(data[i + 3] + " " + data[i + 4]);
                            $("#id_proveedor").val(data[i + 5]);
                            $("#tipo_docu").val(data[i + 6]);
                            $("#ruc_ci").val(data[i + 7]);
                            $("#empresa").val(data[i + 8]);
                            $("#tipo_comprobante").val(data[i + 9]);
                            $("#fecha_registro").val(data[i + 10]);
                            $("#fecha_emision").val(data[i + 11]);
                            $("#fecha_caducidad").val(data[i + 12]);
                            $("#serie").val(data[i + 13]);
                            $("#autorizacion").val(data[i + 14]);
                            $("#cancelacion").val(data[i + 15]);
                            $("#formas").val(data[i + 16]);
                            $("#total_p").val(data[i + 17]);
                            $("#total_p2").val(data[i + 18]);
                            $("#sub").val((parseFloat(data[i + 17]) + parseFloat(data[i + 18])));
                            $("#iva").val(data[i + 19]);
                            $("#desc").val(data[i + 20]);
                            $("#tot").val(data[i + 21]);
                            $("#total_px").val(parseFloat(data[i + 17]).toFixed(2));
                            $("#total_p2x").val(parseFloat(data[i + 18]).toFixed(2));
                            $("#subx").val((parseFloat(data[i + 17]) + parseFloat(data[i + 18])).toFixed(2));
                            $("#ivax").val(parseFloat(data[i + 19]).toFixed(2));
                            $("#descx").val(parseFloat(data[i + 20]).toFixed(2));
                            $("#totx").val(parseFloat(data[i + 21]).toFixed(2));
                        }
                    }
                });

                $.getJSON('retornar_factura_compra2.php?com=' + valor, function (data) {
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
                                cantidad: parseFloat(data[i + 3]).toFixed(2),
                                precio_u: precio,
                                descuento: desc,
                                cal_des: resultado,
                                total: data[i + 6],
                                precio_ux: precio.toFixed(4),
                                descuentox: parseFloat(desc).toFixed(4),
                                cal_desx: resultado.toFixed(4),
                                totalx: parseFloat(data[i + 6]).toFixed(4),
                                iva: data[i + 7],
                                incluye: data[i + 8]
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });
                $("#buscar_facturas_compras").dialog("close");
            } else {
                alertify.alert("Seleccione una Factura");
            }
        }
    });
    $(window).bind('resize', function () {
        jQuery("#list4").setGridWidth($('#pager4').width());
    }).trigger('resize');
    var id = $("#formaspago_mixto").val();
    jQuery("#list4").jqGrid({
        url: 'xmlPlanCuentas_btn.php?id=' + id,
        datatype: 'xml',
        colNames: ['Cod. Cuenta', 'Descripcion', 'Cuenta'],
        colModel: [
            { name: 'idcontable', index: 'idcontable', editable: true, align: 'left', width: '120', search: true, frozen: true, formoptions: { elmsuffix: " (*)" }, editrules: { required: true } },
            { name: 'descripcion', index: 'descripcion', editable: true, align: 'center', width: '490', search: true, frozen: true, formoptions: { elmsuffix: " (*)" }, editrules: { required: true } },
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




    ////////////////////buscador proformas tecnico/////////////////////////
    jQuery("#list7").jqGrid({
        url: 'xmlBuscarEstadosRetencion.php',
        datatype: 'xml',
        colNames: ['ID', 'FECHA', 'PROVEEDOR', 'N° AUTORIZACIÒN', 'TOTAL', 'ESTADO', 'ACCIÒN', 'ENVIO XML', 'CONSULTA COMPROBANTE'],
        colModel: [{
            name: 'id_retencion_fuente_factura_compra',
            index: 'id_retencion_fuente_factura_compra',
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
            name: 'proveedor',
            index: 'proveedor',
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
        sortname: 'id_retencion_fuente_factura_compra',
        sortorder: 'desc',
        viewrecords: true,
        gridComplete: function () {
            var ids = jQuery("#list7").jqGrid('getDataIDs');
            for (var i = 0; i < ids.length; i++) {
                var ids = jQuery("#list7").getDataIDs();
                for (var i = 0; i < ids.length; i++) {
                    var id_factura = ids[i];
                    be = "<a  onclick=\"reenviar('" + id_factura + "')\" title='Reenviar Correo' ><i class='fa fa-envelope-o' style='cursor:pointer; cursor: hand'> CORREO</i></a>";
                    jQuery("#list7").jqGrid('setRowData', ids[i], {
                        accion: be
                    });
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
        search: true,
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

    jQuery(window).bind('resize', function () {
        jQuery("#list").setGridWidth(jQuery('#grid_container').width(), true);
    }).trigger('resize');

    inputmaskDecimal('#cantidad', true, 2);
    inputmaskDecimal('#precio', true, 4);
    inputmaskDecimal('#precio_v', true, 4);

    obtenerParametrosEmpresa();
    obtenerNumSerieRet();

}
//compras