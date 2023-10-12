$(document).on("ready", inicio);
var calculoIVA = 0;
var t;
var idProformaTecnico = 0;

var formatoFactura = "";
var formatoNotaVenta = "";
var autorizarFacAuto = "";

var loaderFactura = $(".loader_factura");
var loadingFactura = false;
var loadingAnular = false;

var aperturaForm;

var cajaAbierta = new Proxy({value: false}, {
    get: function (target, prop, receiver) {
        return target[prop]
    },
    set: function (target, prop, nwval) {
        target[prop] = nwval;
        //mostrarAbrirCaja();
    }
});


function obtenerParametrosEmpresa() {
    fetch("obtener_parametros_empresa.php")
            .then(function (d) {
                return d.json();
            })
            .then(function (json) {
                formatoFactura = json["formato_imperesion_factura"];
                formatoNotaVenta = json["formato_imperesion_nota"];
                autorizarFacAuto = json["autorizar_fac_auto"];
            });
}

$(document).keydown(function (e) {

    if (e.target.id == 'clavefactura') {
        if (e.key == "Enter") {
            e.preventDefault();
            return;
        }
    }

    var e = e || event;
    var keycode = e.which || e.keyCode;
    var obj = e.target || e.srcElement;

    if (e.target.id == "descxa_v" || e.target.id == "descxa") {
        if (keycode == 13) {
            return false;
        }
    }

    // No activar el evento si estamos en un formulario
    //if(obj.tagName.toLowerCase()=="textarea") { return; }
    //if(obj.tagName.toLowerCase()=="input") { return; }
    // Guardar Factura
    //    if(keycode == 118) { guardar_factura()}
    //    if (keycode == 17) {
    //        abrirDialogop()
    //    }
    //    if (keycode == 38) {
    //        seleccion_row()
    //    }
    // Tecla Control Cliente
    /*if (keycode == 17) {
     $("#ruc_ci").select()
     }*/
    if (keycode == 119) {
        ingresar_cambio(e);
    }
    if (keycode == 13) {
        if ($("#formaspago").val() == "otros") {
            agregar();
        }
    }
    // Tecla Control Cliente
    //    if (keycode == 40) {
    //        agregar()
    //    }
    /*if (keycode == 39) {
     guardar_serie()
     }*/
    if (keycode == 27) {
        cancelar();
    }
});

function evento(e) {
    e.preventDefault();
}

function openPDF() {
    window.open("../../ayudas/ayuda.pdf");
}
function toFixedDown(value, digits) {
    if (isNaN(value))
        return 0;
    var n = value - Math.pow(10, -digits) / 2;
    n += n / Math.pow(2, 53);
    if (n < 0)
        n = 0.0;
    return n.toFixed(digits);
}
function scrollToBottom() {
    $("html, body").animate(
            {
                scrollTop: $(document).height(),
            },
            "slow"
            );
}

function scrollToTop() {
    $("html, body").animate({scrollTop: 0, },
            "slow"
            );
}
function mostrar_num_doc() {
    if ($("#num_oculto").val() == "") {
        $("#num_factura").val("");
    } else if ($("#tipo_venta").val() == "NOTA") {
        $("#btnAdelante").attr("disabled", true);
        $("#btnAtras").attr("disabled", true);
        let str = $("#num_oculto_nv").val();
        let res = parseInt(str);
        res++;
        $("#num_factura").val("" + res);
        let a = autocompletar(res);
        let validado = a + "" + res;
        $("#num_factura").val(validado);
    } else if ($("#tipo_venta").val() == "FACTURA") {
        $("#btnAdelante").attr("disabled", false);
        $("#btnAtras").attr("disabled", false);
        let str = $("#num_oculto").val();
        let res = parseInt(str.substr(4, 16));
        res = res + 1;
        $("#num_factura").val(res);
        let a = autocompletar(res);
        let validado = a + "" + res;
        $("#num_factura").val(validado);
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

var dialogos = {
    autoOpen: false,
    resizable: false,
    width: 900,
    height: 350,
    modal: true,
};

var dialogo = {
    autoOpen: false,
    resizable: false,
    width: 600,
    height: 420,
    modal: true,
};

var dialogo2 = {
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 350,
    modal: true,
    show: "explode",
    hide: "blind",
};
var dialogo_cuenta = {
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 400,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind",
};
var dialogo3 = {
    autoOpen: false,
    resizable: false,
    width: 420,
    height: 180,
    modal: true,
    show: "explode",
    hide: "blind",
};

var dialogo4 = {
    autoOpen: false,
    resizable: false,
    width: 300,
    height: 150,
    modal: true,
    show: "explode",
    hide: "blind",
};

var dialogo5 = {
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 350,
    modal: true,
    show: "explode",
    hide: "blind",
};

var dialogotecnico = {
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 350,
    modal: true,
    show: "explode",
    hide: "blind",
};

var dialogo6 = {
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 350,
    modal: true,
    show: "explode",
    hide: "blind",
};

var dialogo7 = {
    autoOpen: false,
    resizable: false,
    width: 300,
    height: 200,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind",
};

var dialogo8 = {
    autoOpen: false,
    resizable: false,
    width: 420,
    height: 260,
    modal: true,
    show: "explode",
    hide: "blind",
};

var dialogo9 = {
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 350,
    modal: true,
    show: "explode",
    hide: "blind",
};

var dialogo10 = {
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 350,
    modal: true,
    show: "explode",
    hide: "blind",
};

var dialogo1010 = {
    autoOpen: false,
    resizable: false,
    width: 1000,
    height: 350,
    modal: true,
    show: "explode",
    hide: "blind",
};

function ValidNum(e) {
    if (e.keyCode < 48 || e.keyCode > 57) {
        e.returnValue = false;
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

function enter(e) {
    var prod = $("#cod_producto").val();
    $.getJSON("comprobar_pvp_editar.php?prod=" + prod, function (data) {
        data = 1;
        if (data != null) {
            console.log("si editar pvp p_venta ");
            if (e.which == 13 || e.keyCode == 13) {
                entrar();
                return false;
            }
            return true;
        } else {
            console.log(" editar pvp descuento ");
            if (e.which == 13 || e.keyCode == 13) {
                entrarpvpf_editar_pvpv();
                return false;
            }
            return true;
        }
    });
}

function enterpvsi(e) {
    if (e.which == 13 || e.keyCode == 13) {
        entrarpvsi();
        return false;
    }
    return true;
}

function enterpvpf(e) {
    if (e.which == 13 || e.keyCode == 13) {
        entrarpvpf();
        return false;
    }
    return true;
}
function enterdscto(e) {
    if (e.which == 13 || e.keyCode == 13) {
        entrardscto();
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
function enter77(e) {
    if (e.which == 13 || e.keyCode == 13) {
        comprobar2reten();
        return false;
    }
    return true;
}
function enter4(e) {
    if (e.which == 13 || e.keyCode == 13) {
        $("#ruc_ci").blur();
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
function cargar_cuentas_reten() {
    var id = $("#formaspago_mixto_reten").val();
    if ($("#formaspago_mixto_reten").val() == "cxc") {
        $("#btnCuenta_reten").attr("disabled", true);
        $("#cuenta_contable_reten").attr("disabled", true);

        $.getJSON("xmlPlanCuentas_reten_cxc.php?id=" + id, function (data) {
            var tama = data.length;
            t = data[4];
            if (tama != 0) {
                for (var i = 0; i < tama; i = i + 4) {
                    $("#idCuenta_reten").val(data[i]);
                    $("#cuenta_contable_reten").val(data[i + 2]);
                    //                            $("#cantidad_mixto").val(data[i + 2]);
                }
            }
        });
    } else {
        $("#btnCuenta_reten").attr("disabled", false);
        $("#cuenta_contable_reten").attr("disabled", false);
        $("#list44_reten")
                .jqGrid("setGridParam", {
                    url: "xmlPlanCuentas_reten.php?id=" + id,
                    datatype: "xml",
                })
                .trigger("reloadGrid");
    }
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
        calculo_cambio();
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
    if (loadingFactura) {
        e.preventDefault();
        e.stopPropagation();
        return false;
    }
    if (e.which == 13 || e.keyCode == 13) {

        //                       guardar_factura();

        $("#btnGuardarV").focus();
        return false;
    }
    return true;
}
function autocompletar_reten() {
    var temp = "";
    var serie = $("#serie_retencion").val();
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
function entrar() {
    var expreg = /^[0-9]+([.])?([0-9]+)?$/;
    if (expreg.test($("#cantidad").val())) {
        if ($("#cod_producto").val() == "") {
            $("#cod_producto").focus();
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
                        //$("#mino").prop("selected", true);
                        $("#p_venta").focus();
                    }
                }
            }
        }
    } else {
        //        alertify.success("Debe estar un número antes del punto");
        alertify.success(
                "Debe estar un número antes del punto",
                "success",
                1000,
                function () {
                    console.log("dismissed");
                }
        );
    }
}
function entrarpvsi() {
    var expreg = /^[0-9]+([.])?([0-9]+)?$/;
    if (expreg.test($("#cantidad").val())) {
        if ($("#cod_producto").val() == "") {
            $("#cod_producto").focus();
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
                        //$("#mino").prop("selected", true);
                        if ($("#p_venta").val() == "") {
                            $("#p_venta").focus();
                        } else {
                            $("#venta_iva_1").focus();
                        }

                    }
                }
            }
        }
    } else {
        //        alertify.success("Debe estar un número antes del punto");
        alertify.success(
                "Debe estar un número antes del punto",
                "success",
                1000,
                function () {
                    console.log("dismissed");
                }
        );
    }
}
function entrarpvpf_editar_pvpv() {

    var expreg = /^[0-9]+([.])?([0-9]+)?$/;
    if (expreg.test($("#cantidad").val())) {
        if ($("#cod_producto").val() == "") {
            $("#cod_producto").focus();
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
                        //$("#mino").prop("selected", true);
                        if ($("#p_venta").val() == "") {
                            $("#p_venta").focus();
                        } else {
                            $("#descuento").focus();

                        }

                    }
                }
            }
        }
    } else {
        //        alertify.success("Debe estar un número antes del punto");
        alertify.success(
                "Debe estar un número antes del punto",
                "success",
                1000,
                function () {
                    console.log("dismissed");
                }
        );
    }
}
function entrarpvpf() {

    var expreg = /^[0-9]+([.])?([0-9]+)?$/;
    if (expreg.test($("#cantidad").val())) {
        if ($("#cod_producto").val() == "") {
            $("#cod_producto").focus();
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
                        //$("#mino").prop("selected", true);
                        if ($("#p_venta").val() == "") {
                            $("#p_venta").focus();
                        } else {
                            if ($("#venta_iva_1").val() == "") {
                                $("#venta_iva_1").focus();
                            } else {
                                $("#descuento").focus();
                            }
                        }

                    }
                }
            }
        }
    } else {
        //        alertify.success("Debe estar un número antes del punto");
        alertify.success(
                "Debe estar un número antes del punto",
                "success",
                1000,
                function () {
                    console.log("dismissed");
                }
        );
    }
}

function entrardscto() {
    var expreg = /^[0-9]+([.])?([0-9]+)?$/;
    if (expreg.test($("#cantidad").val())) {
        if ($("#cod_producto").val() == "") {
            $("#cod_producto").focus();
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
                        //$("#mino").prop("selected", true);
                        if ($("#p_venta").val() == "") {
                            $("#p_venta").focus();
                        } else {
                            if ($("#venta_iva_1").val() == "") {
                                $("#venta_iva_1").focus();
                            } else {
                                $("#descripocion_prod").focus()
                            }
                        }

                    }
                }
            }
        }
    } else {
        //        alertify.success("Debe estar un número antes del punto");
        alertify.success(
                "Debe estar un número antes del punto",
                "success",
                1000,
                function () {
                    console.log("dismissed");
                }
        );
    }
}


function enter_liqui(e) {
    if (e.which == 13 || e.keyCode == 13) {
        entrar_liqui();
        return false;
    }
    return true;
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
function entrar222() {
    var filas = jQuery("#list").jqGrid("getRowData");
    var subtotal0 = 0;
    var subtotal12 = 0;
    var subtotal_total = 0;
    var iva12 = 0;
    var total_total = 0;
    var descu_total = 0;
    var descuento = 0;
    var total = 0;
    var su = 0;
    var promo = 0;
    var desc = 0;
    var precio = 0;
    var multi = 0;
    var flotante = 0;
    var resultado = 0;
    var repe = 0;
    var suma = 0;
    $.ajax({
        type: "POST",
        url: "comprobar_promo_cant.php?cod_producto=" + $("#cod_producto_tem").val(),
        data: "valor",
        success: function (data) {
            var val = data;
            var valores;
            if (val != "") {
                valores = val.split("*");
                $("#cantidad_producto_promo").val(valores[1]);
            }
        },
    });
    // proceso incluye iva
}
function entrar22() {
    console.log("entrar22()");
    var filas = jQuery("#list").jqGrid("getRowData");
    var subtotal0 = 0;
    var subtotal12 = 0;
    var subtotal_total = 0;
    var iva12 = 0;
    var total_total = 0;
    var descu_total = 0;
    var descuento = 0;
    var total = 0;
    var su = 0;
    var promo = 0;
    var desc = 0;
    var precio = 0;
    var multi = 0;
    var flotante = 0;
    var resultado = 0;
    var repe = 0;
    var suma = 0;
    var can = 0;

    var iva_pventa = 0;
    var cantidad = parseFloat($("#cantidad").val());
    var filas = jQuery("#list").jqGrid("getRowData");
    for (var i = 0; i < filas.length; i++) {
        var id = filas[i];
        console.log("entro1" + id["cod_producto"]);
        if (id["cod_producto"] == $("#cod_producto").val()) {
            console.log("entro1");
            repe = 1;
            can = id["cantidad"];
        }
    }
    if (repe == 1) {
        var suma = parseFloat(can) + parseFloat($("#cantidad").val());
        suma = Number(suma.toFixed(2));
        if (can >= parseFloat($("#cantidad_producto_promo").val())) {
            $.getJSON("comprobar_promo.php?cod_producto=" + $("#cod_producto_tem").val(), function (data) {
                if (data != null) {
                    var val = data.length;
                    var can1 = 0;
                    var result = 0;
                    var iva1 = 0;
                    if (val != "") {
                        for (var i = 0; i < val; i = i + 8) {
                            multi = data[i + 3] * parseFloat(data[i + 4]);
                            total = parseFloat(multi);
                            $("#cantidad_producto_promo").val(data[i + 7]);
                            //////var filas jquiery
                            var filas = jQuery("#list").jqGrid("getRowData");
                            for (var t = 0; t < filas.length; t++) {
                                var id = filas[t];
                                var can1 = 0;
                                var result = 0;
                                var iva1 = 0;
                                console.log("entro14" + " " + data[i]);
                                if (id["cod_producto"] == data[i]) {
                                    var repe1 = 1;
                                    can1 = id["cantidad"];
                                    if (data[i + 5] == "Si") {
                                        suma = parseFloat(can1);
                                        suma = Number(suma.toFixed(2));
                                        multi = can1 * parseFloat(data[i + 4]);
                                        total = parseFloat(multi);
                                        iva1 = (total * calculoIVA) / 100;
                                        iva_pventa = iva1 + parseFloat(total);
                                        result = numFormatter(2).format(iva_pventa);
                                    } else {
                                        console.log("entro6");
                                        suma = parseFloat(can1);
                                        suma = Number(suma.toFixed(2));
                                        result = numFormatter(2).format(iva_pventa);
                                    }
                                } else {
                                    if (data[i + 5] == "Si") {
                                        console.log("entro5");
                                        suma = parseFloat(data[i + 3]);
                                        suma = Number(suma.toFixed(2));
                                        multi = data[i + 3] * parseFloat(data[i + 4]);
                                        total = parseFloat(multi);
                                        iva1 = (total * calculoIVA) / 100;
                                        iva_pventa = iva1 + parseFloat(total);
                                        result = numFormatter(2).format(iva_pventa);
                                    } else {
                                        console.log("entro6");
                                        suma = parseFloat(can1);
                                        suma = Number(suma.toFixed(2));
                                        result = numFormatter(2).format(iva_pventa);
                                    }
                                }
                            }
                            if (repe1 == 1) {
                                console.log("entro17");
                                var cantidades = parseFloat(can);
                                var suma_promo = cantidades / parseFloat($("#cantidad_producto_promo").val());
                                var serul_suma_promo = parseInt(suma_promo) * parseFloat(data[i + 3]);
                                suma = parseFloat(serul_suma_promo);
                                suma = Number(suma.toFixed(2));

                                multi = serul_suma_promo * parseFloat(data[i + 4]);
                                total = parseFloat(multi);
                                iva1 = (total * calculoIVA) / 100;
                                iva_pventa = iva1 + parseFloat(total);
                                result = numFormatter(2).format(iva_pventa);
                                console.log("datarrow/");

                                var item1 = 0;
                                filas.map((prod) => {
                                    if (prod.cod_producto == data[i])
                                        item1 = prod.id_list;
                                });
                                var datarow = {
                                    id_list: item1,
                                    cod_producto: data[i],
                                    codigo: data[i + 1],
                                    detalle: data[i + 2],
                                    cantidad: serul_suma_promo.toFixed(2),
                                    precio_u: total,
                                    descuento: desc,
                                    cal_des: resultado,
                                    total: total,
                                    precio_ux: data[i + 4],
                                    descuentox: parseFloat(desc).toFixed(4),
                                    cal_desx: resultado.toFixed(4),
                                    totalx: total.toFixed(4),
                                    iva: data[i + 5],

                                    pendiente: numFormatter(2).format(result),
                                    incluye: data[i + 6],
                                };


                                promo = jQuery("#list").jqGrid("setRowData", item1, datarow);

                                limpiar_campos();
                            } else {
                                console.log("entro8" + " " + can);
                                var cantidades = parseFloat(can);
                                console.log("PROMO COD_PRO REPE1=1" + can);
                                var suma_promo = cantidades / parseFloat($("#cantidad_producto_promo").val());

                                var serul_suma_promo =
                                        parseInt(suma_promo) * parseFloat(data[i + 3]);
                                suma = parseFloat(serul_suma_promo);
                                //                            console.log("entro15" + serul_suma_promo);
                                suma = Number(suma.toFixed(2));
                                multi = serul_suma_promo * parseFloat(data[i + 4]);
                                //                            console.log("entro15" + multi);
                                total = parseFloat(multi);
                                iva1 = (total * calculoIVA) / 100;
                                iva_pventa = iva1 + parseFloat(total);
                                //                            console.log("entro15" + iva_pventa);
                                result = numFormatter(2).format(iva_pventa);
                                console.log("PROMO COD_PRO REPE1 DIFERENTE 1");
                                //var item1 = filas.length + 1;
                                let filastmp = $("#list").jqGrid("getRowData");
                                let maxid = filastmp[filastmp.length - 1]["id_list"];
                                var item1 = +maxid + 1;

                                var datarow = {
                                    id_list: item1,
                                    cod_producto: data[i],
                                    codigo: data[i + 1],
                                    detalle: data[i + 2],
                                    cantidad: serul_suma_promo.toFixed(2),
                                    precio_u: total,
                                    descuento: desc,
                                    cal_des: resultado,
                                    total: total,
                                    precio_ux: data[i + 4],
                                    descuentox: parseFloat(desc).toFixed(4),
                                    cal_desx: resultado.toFixed(4),
                                    totalx: total.toFixed(4),
                                    iva: data[i + 5],

                                    pendiente: numFormatter(2).format(result),
                                    incluye: data[i + 6],
                                };

                                promo = jQuery("#list").jqGrid("addRowData", item1, datarow);
                                //                            $("#cantidad_producto_promo").val("0");
                                limpiar_campos();
                            }
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
                                var dd = fil[t];

                                if (dd["iva"] == "Si") {

                                    if (dd["incluye"] == "No") {
                                        subtotal = dd["total"];
                                        sub1 = subtotal;
                                        iva1 = (sub1 * calculoIVA) / 100;

                                        subtotal0 = parseFloat(subtotal0) + 0;
                                        subtotal12 = parseFloat(subtotal12) + parseFloat(sub1);
                                        subtotal_total = parseFloat(subtotal0) + parseFloat(subtotal12);
                                        descu_total = parseFloat(descu_total) + parseFloat(dd["cal_des"]);
                                        iva12 = parseFloat(iva12) + parseFloat(iva1);

                                        subtotal0 = parseFloat(subtotal0);
                                        subtotal12 = parseFloat(subtotal12);
                                        subtotal_total = parseFloat(subtotal_total);
                                        iva12 = parseFloat(iva12);
                                        descu_total = parseFloat(descu_total);
                                        suma_total = suma_total + dd["cantidad"];
                                    } else {
                                        if (dd["incluye"] == "Si") {
                                            subtotal = dd["total"];
                                            sub2 = subtotal / (calculoIVA / 100 + 1);
                                            iva2 = sub2 * (calculoIVA / 100);

                                            subtotal0 = parseFloat(subtotal0) + 0;
                                            subtotal12 = parseFloat(subtotal12) + parseFloat(sub2);
                                            subtotal_total = parseFloat(subtotal0) + parseFloat(subtotal12);
                                            iva12 = parseFloat(iva12) + parseFloat(iva2);
                                            descu_total = parseFloat(descu_total) + dd["cal_des"];

                                            subtotal0 = parseFloat(subtotal0);
                                            subtotal12 = parseFloat(subtotal12);
                                            subtotal_total = parseFloat(subtotal_total);
                                            iva12 = parseFloat(iva12);
                                            descu_total = parseFloat(descu_total);
                                            suma_total = suma_total + dd["cantidad"];
                                        }
                                    }
                                } else {
                                    if (dd["iva"] == "No") {
                                        subtotal = dd["total"];
                                        sub = subtotal;
                                        subtotal0 = parseFloat(subtotal0) + parseFloat(sub);
                                        subtotal12 = parseFloat(subtotal12) + 0;
                                        subtotal_total = parseFloat(subtotal0) + parseFloat(subtotal12);
                                        iva12 = parseFloat(iva12) + 0;
                                        descu_total = parseFloat(descu_total) + parseFloat(dd["cal_des"]);
                                        subtotal0 = parseFloat(subtotal0);
                                        subtotal12 = parseFloat(subtotal12);
                                        subtotal_total = parseFloat(subtotal_total);
                                        iva12 = parseFloat(iva12);
                                        descu_total = parseFloat(descu_total);
                                        suma_total = suma_total + dd["cantidad"];
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
                            $("#total_px").val(subtotal0.toFixed(4));
                            $("#total_p2x").val(subtotal12.toFixed(4));
                            $("#subx").val(subtotal_total.toFixed(4));
                            $("#ivax").val(iva12.toFixed(4));
                            $("#descxax").val(descu_total.toFixed(4));
                            $("#totx").val(total_total.toFixed(2));
                            $("#items").val(item);
                            $("#num").val(suma_total);
                            $("#codigo_barras").focus();
                            $("#valor_factura").val(total_total.toFixed(2));
                        }
                    }
                }
            });
        }
    } else {
        if (cantidad >= parseInt($("#cantidad_producto_promo").val())) {
            console.log("cantidad >= cantidad producto");
            $.getJSON("comprobar_promo.php?cod_producto=" + $("#cod_producto_tem").val(), function (data) {
                console.log("entro1");
                if (data != null) {
                    var val = data.length;
                    if (val != "") {
                        console.log("entro2");
                        var can1 = 0;
                        var result = 0;
                        var iva1 = 0;
                        for (var i = 0; i < val; i = i + 8) {
                            console.log("entro3" + data[i + 3]);
                            multi = data[i + 3] * parseFloat(data[i + 4]);
                            total = parseFloat(multi);

                            var filas = jQuery("#list").jqGrid("getRowData");
                            for (var j = 0; j < filas.length; j++) {
                                var id = filas[i];

                                if (id["cod_producto"] == data[i]) {
                                    console.log("entro4");
                                    var repe1 = 1;
                                    can1 = id["cantidad"];
                                    if (id["iva"] == "Si") {
                                        console.log("entro5");
                                        suma = parseFloat(can1) + parseFloat($("#cantidad").val());
                                        suma = Number(suma.toFixed(2));
                                        iva1 = (id["precio_u"] * calculoIVA) / 100;
                                        iva_pventa = iva1 + parseFloat(id["precio_u"]);
                                        result = suma * numFormatter(2).format(iva_pventa);
                                    } else {
                                        console.log("entro6");
                                        suma = parseFloat(can1) + parseFloat($("#cantidad").val());
                                        suma = Number(suma.toFixed(2));
                                        result = suma * numFormatter(2).format(iva_pventa);
                                    }
                                } else {
                                    if (data[i + 5] == "Si") {
                                        console.log("entro7" + data[i + 5]);
                                        suma = parseFloat(data[i + 3]);
                                        suma = Number(suma.toFixed(2));
                                        multi = data[i + 3] * parseFloat(data[i + 4]);

                                        total = parseFloat(multi);
                                        iva1 = (total * calculoIVA) / 100;

                                        iva_pventa = iva1 + parseFloat(total);

                                        result = numFormatter(2).format(iva_pventa);
                                    } else {
                                        console.log("entro8 sin nada");
                                        suma = parseFloat(can1);
                                        suma = Number(suma.toFixed(2));
                                        result = numFormatter(2).format(iva_pventa);
                                    }
                                }
                            }


                            var suma_promo = parseFloat($("#cantidad").val()) / parseFloat($("#cantidad_producto_promo").val());
                            var serul_suma_promo = parseInt(suma_promo) * parseFloat(data[i + 3]);
                            console.log("ENTRO " + serul_suma_promo);
                            suma = parseFloat(serul_suma_promo);
                            //                            console.log("entro15" + serul_suma_promo);
                            suma = Number(suma.toFixed(2));
                            multi = serul_suma_promo * parseFloat(data[i + 4]);

                            total = parseFloat(multi);
                            iva1 = (total * calculoIVA) / 100;
                            iva_pventa = iva1 + parseFloat(total);
                            //                            console.log("entro15" + iva_pventa);
                            result = numFormatter(2).format(iva_pventa);

                            //var item1 = val.length + 1;
                            let filastmp = $("#list").jqGrid("getRowData");
                            let maxid = filastmp[filastmp.length - 1]["id_list"];
                            var item1 = +maxid + 1;
                            console.log("entro9" + data[i + 2]);
                            var datarow = {
                                id_list: item1,
                                cod_producto: data[i],
                                codigo: data[i + 1],
                                detalle: data[i + 2],
                                cantidad: serul_suma_promo.toFixed(2),
                                precio_u: total,
                                descuento: desc,
                                cal_des: resultado,
                                total: total,
                                precio_ux: data[i + 4],
                                descuentox: parseFloat(desc).toFixed(4),
                                cal_desx: resultado.toFixed(4),
                                totalx: total.toFixed(4),
                                iva: data[i + 5],

                                pendiente: numFormatter(2).format(result),
                                incluye: data[i + 6],
                            };

                            promo = jQuery("#list").jqGrid("addRowData", item1, datarow);
                            limpiar_campos();
                            //                        $("#cantidad_producto_promo").val("0");
                        }
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

                            if (dd["iva"] == "Si") {

                                if (dd["incluye"] == "No") {
                                    subtotal = dd["total"];
                                    sub1 = subtotal;
                                    iva1 = (sub1 * calculoIVA) / 100;

                                    subtotal0 = parseFloat(subtotal0) + 0;
                                    subtotal12 = parseFloat(subtotal12) + parseFloat(sub1);
                                    subtotal_total =
                                            parseFloat(subtotal0) + parseFloat(subtotal12);
                                    descu_total =
                                            parseFloat(descu_total) + parseFloat(dd["cal_des"]);
                                    iva12 = parseFloat(iva12) + parseFloat(iva1);

                                    subtotal0 = parseFloat(subtotal0);
                                    subtotal12 = parseFloat(subtotal12);
                                    subtotal_total = parseFloat(subtotal_total);
                                    iva12 = parseFloat(iva12);
                                    descu_total = parseFloat(descu_total);
                                    suma_total = suma_total + dd["cantidad"];
                                } else {
                                    if (dd["incluye"] == "Si") {
                                        subtotal = dd["total"];
                                        sub2 = subtotal / (calculoIVA / 100 + 1);
                                        iva2 = sub2 * (calculoIVA / 100);

                                        subtotal0 = parseFloat(subtotal0) + 0;
                                        subtotal12 = parseFloat(subtotal12) + parseFloat(sub2);
                                        subtotal_total =
                                                parseFloat(subtotal0) + parseFloat(subtotal12);
                                        iva12 = parseFloat(iva12) + parseFloat(iva2);
                                        descu_total = parseFloat(descu_total) + dd["cal_des"];

                                        subtotal0 = parseFloat(subtotal0);
                                        subtotal12 = parseFloat(subtotal12);
                                        subtotal_total = parseFloat(subtotal_total);
                                        iva12 = parseFloat(iva12);
                                        descu_total = parseFloat(descu_total);
                                        suma_total = suma_total + dd["cantidad"];
                                    }
                                }
                            } else {
                                if (dd["iva"] == "No") {

                                    subtotal = dd["total"];
                                    sub = subtotal;

                                    subtotal0 = parseFloat(subtotal0) + parseFloat(sub);
                                    subtotal12 = parseFloat(subtotal12) + 0;
                                    subtotal_total =
                                            parseFloat(subtotal0) + parseFloat(subtotal12);
                                    iva12 = parseFloat(iva12) + 0;
                                    descu_total =
                                            parseFloat(descu_total) + parseFloat(dd["cal_des"]);

                                    subtotal0 = parseFloat(subtotal0);
                                    subtotal12 = parseFloat(subtotal12);
                                    subtotal_total = parseFloat(subtotal_total);
                                    iva12 = parseFloat(iva12);
                                    descu_total = parseFloat(descu_total);
                                    suma_total = suma_total + dd["cantidad"];
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
                        $("#total_px").val(subtotal0.toFixed(4));
                        $("#total_p2x").val(subtotal12.toFixed(4));
                        $("#subx").val(subtotal_total.toFixed(4));
                        $("#ivax").val(iva12.toFixed(4));
                        $("#descxax").val(descu_total.toFixed(4));
                        $("#totx").val(total_total.toFixed(2));
                        $("#items").val(item);
                        $("#num").val(suma_total);
                        $("#codigo_barras").focus();
                        $("#valor_factura").val(total_total.toFixed(2));
                    }

                } else {
                    limpiar_campos();
                }
            });
        } else {
            limpiar_campos();
        }
    }

    // proceso incluye iva
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
                if ($("#cantidad").val() == "") {
                    $("#cantidad").focus();
                } else {
                    if ($("#p_venta").val() == "") {
                        $("#p_venta").focus();
                        alertify.error("Ingrese precio venta");
                    } else {
                        //$("#descuento").focus();
                        entrar3();
                    }
                }
            }
        }
    }
}

function limpiar_campos() {
    //    console.log("si vino");
    $("#cod_producto").val("");
    $("#codigo_barras").val("");
    $("#codigo").val("");
    $("#producto").val("");
    $("#cantidad").val("");
     $("#precio").val("");
    $("#p_venta").val("");
    $("#venta_iva_1").val("");
    $("#venta_iva").val("0.00");
    $("#descuento").val("0");
    $("#des").val("");
    $("#disponibles").val("");
    $("#incluye").val("");
    $("#carga_series").val("");
    $("#unidad_medida").val("");
    $("#cantidad_unidad").val("");
}

function abrirDialogop() {
    $("#productos").select();
    var id = $("#producto").val();
    if (id === "") {
        alertify.error("Error... Ingrese un producto");
    } else {
        $("#listp")
                .jqGrid("setGridParam", {
                    url: "datos_productos.php?texto=" + id,
                    datatype: "xml",
                })
                .trigger("reloadGrid");
        $("#productos").focus();
        $("#productos").select();
        $("#productos").dialog("open");
        $("#productos").select();
    }
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
        },
    });

    var subtotal0 = 0;
    var subtotal12 = 0;
    var subtotal_total = 0;
    var iva12 = 0;
    var total_total = 0;
    var descu_total = 0;
    var cantidad_unidad = 0;
    var unidad_medida = "";

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
                    if ($("#p_venta").val() == "") {
                        $("#p_venta").focus();
                        alertify.error("Ingrese un precio");
                    } else {
                        if ($("#inventar").val() == "Si") {
                            if ($("#disponibles").val() == "") {
                                parseInt($("#disponibles").val(0));
                            }

                            if ($("#cantidad_unidad").val() != "") {


                                if (parseFloat($("#cantidad_unidad").val()) * parseFloat($("#cantidad").val()) > parseFloat($("#disponibles").val())) {
                                    $("#cantidad").focus();
                                    alertify.error("Error.. Fuera de Stock cantidad disponible: " + $("#disponibles").val());
                                } else {///cambio 1
                                    var filas = jQuery("#list").jqGrid("getRowData");
                                    var descuento = 0;
                                    var total = 0;
                                    var su = 0;
                                    var promo = 0;
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
                                            multi = $("#cantidad").val() * parseFloat(precio);
                                            descuento = (multi * parseFloat(desc)) / 100;
                                            flotante = parseFloat(descuento);
                                            resultado =
                                                    Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                            total = multi - resultado;
                                        } else {
                                            desc = 0;
                                            precio = parseFloat($("#p_venta").val());
                                            multi = $("#cantidad").val() * parseFloat(precio);
                                            descuento = (multi * parseFloat(desc)) / 100;
                                            flotante = parseFloat(descuento);
                                            resultado =
                                                    Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                            total = parseFloat(multi);
                                        }
                                        console.log("INVENTAR SI, FILAS 0");
                                        var item1 = filas.length + 1;
                                        if ($("#cantidad_unidad").val() != "") {
                                            cantidad_unidad = parseFloat($("#cantidad_unidad").val()) * parseFloat($("#cantidad").val());
                                            unidad_medida = $("#unidad_medida")[0].selectedOptions[0].text;
                                            unidad_medida = unidad_medida.split("--");
                                            unidad_medida = unidad_medida[0];

                                        } else {
                                            cantidad_unidad = 0;
                                            unidad_medida = '';
                                        }
                                        var datarow = {
                                            id_list: item1,
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
                                            pendiente: parseFloat($("#venta_iva").val()).toFixed(2),
                                            incluye: $("#incluye").val(),
                                            cantidad_unidad: cantidad_unidad,
                                            unidad_medida: unidad_medida,
                                            detalle_producto: $("#descripocion_prod").val()
                                        };
                                        entrar22();
                                        su = jQuery("#list").jqGrid("addRowData", item1, datarow);
                                        $("#mino").prop("selected", true);

                                        //                                    limpiar_campos();
                                    } else {
                                        var result = 0;
                                        var iva1 = 0;
                                        var iva_pventa = 0;

                                        for (var i = 0; i < filas.length; i++) {
                                            var id = filas[i];
                                            if (id["cod_producto"] == $("#cod_producto").val()) {
                                                repe = 1;
                                                var can = id["cantidad"];
                                                if (id["iva"] == "Si") {
                                                    suma = parseFloat(can) + parseFloat($("#cantidad").val());
                                                    suma = Number(suma.toFixed(2));
                                                    iva1 = (id["precio_u"] * calculoIVA) / 100;
                                                    iva_pventa = iva1 + parseFloat(id["precio_u"]);
                                                    result = suma * numFormatter(2).format(iva_pventa);
                                                    console.log("aqui/" + id["precio_u"]);
                                                } else {
                                                    suma = parseFloat(can) + parseFloat($("#cantidad").val());
                                                    suma = Number(suma.toFixed(2));
                                                    result = suma * numFormatter(2).format(iva_pventa);
                                                }
                                            }
                                        }

                                        if (repe == 1) {
                                            suma = parseFloat(can) + parseFloat($("#cantidad").val());
                                            suma = Number(suma.toFixed(2));
                                            if (suma > parseInt($("#disponibles").val())) {
                                                $("#cantidad").focus();
                                                alertify.error(
                                                        "Error.. Fuera de Stock cantidad disponible: " +
                                                        $("#disponibles").val()
                                                        );
                                            } else {
                                                if ($("#descuento").val() != "") {
                                                    desc = $("#descuento").val();
                                                    precio = parseFloat($("#p_venta").val());
                                                    multi = parseFloat(suma) * parseFloat(precio);
                                                    descuento = (multi * parseFloat(desc)) / 100;
                                                    flotante = parseFloat(descuento);
                                                    resultado =
                                                            Math.round(flotante * Math.pow(10, 2)) /
                                                            Math.pow(10, 2);
                                                    total = multi - resultado;
                                                } else {
                                                    desc = 0;
                                                    precio = parseFloat($("#p_venta").val());
                                                    multi = suma * parseFloat(precio);
                                                    descuento = (multi * parseFloat(desc)) / 100;
                                                    flotante = parseFloat(descuento);
                                                    resultado =
                                                            Math.round(flotante * Math.pow(10, 2)) /
                                                            Math.pow(10, 2);
                                                    total = parseFloat(multi);
                                                }
                                                console.log("inventar si CODIGO PRODUCTO ES = REPE =1");
                                                var item1 = 0;
                                                // encuentra el mismo id
                                                filas.map((prod) => {
                                                    if (prod.cod_producto == $("#cod_producto").val())
                                                        item1 = prod.id_list;
                                                });
                                                if ($("#cantidad_unidad").val() != "") {
                                                    cantidad_unidad = parseFloat($("#cantidad_unidad").val()) * parseFloat($("#cantidad").val());
                                                    unidad_medida = $("#unidad_medida")[0].selectedOptions[0].text;
                                                    unidad_medida = unidad_medida.split("--");
                                                    unidad_medida = unidad_medida[0];
                                                } else {
                                                    cantidad_unidad = 0;
                                                    unidad_medida = '';
                                                }
                                                datarow = {
                                                    id_list: item1,
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
                                                    pendiente: numFormatter(2).format(result),
                                                    incluye: $("#incluye").val(),
                                                    cantidad_unidad: cantidad_unidad,
                                                    unidad_medida: unidad_medida,
                                                    detalle_producto: $("#descripocion_prod").val()
                                                };

                                                su = jQuery("#list").jqGrid("setRowData", item1, datarow);
                                                entrar22();
                                                limpiar_campos();
                                            }
                                        } else {
                                            if (filas.length < $("#num_items").val()) {
                                                if ($("#descuento").val() != "") {
                                                    desc = $("#descuento").val();
                                                    precio = parseFloat($("#p_venta").val());
                                                    multi = $("#cantidad").val() * parseFloat(precio);
                                                    descuento = (multi * parseFloat(desc)) / 100;
                                                    flotante = parseFloat(descuento);
                                                    resultado =
                                                            Math.round(flotante * Math.pow(10, 2)) /
                                                            Math.pow(10, 2);
                                                    total = multi - resultado;
                                                } else {
                                                    desc = 0;
                                                    precio = parseFloat($("#p_venta").val());
                                                    multi = $("#cantidad").val() * parseFloat(precio);
                                                    descuento = (multi * parseFloat(desc)) / 100;
                                                    flotante = parseFloat(descuento);
                                                    resultado =
                                                            Math.round(flotante * Math.pow(10, 2)) /
                                                            Math.pow(10, 2);
                                                    total = parseFloat(multi);
                                                }
                                                console.log(
                                                        "inventar si CODIGO PRODUCTO ES = REPE ES DIFERENTE 1"
                                                        );
                                                var item1 = filas.length + 1;
                                                if ($("#cantidad_unidad").val() != "") {
                                                    cantidad_unidad = parseFloat($("#cantidad_unidad").val()) * parseFloat($("#cantidad").val());
                                                    unidad_medida = $("#unidad_medida")[0].selectedOptions[0].text;
                                                    unidad_medida = unidad_medida.split("--");
                                                    unidad_medida = unidad_medida[0];
                                                } else {

                                                    cantidad_unidad = 0;
                                                    unidad_medida = '';

                                                }
                                                datarow = {
                                                    id_list: item1,
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
                                                    pendiente: parseFloat($("#venta_iva").val()).toFixed(2),
                                                    incluye: $("#incluye").val(),
                                                    cantidad_unidad: cantidad_unidad,
                                                    unidad_medida: unidad_medida,
                                                    detalle_producto: $("#descripocion_prod").val()
                                                };
                                                entrar22();
                                                su = jQuery("#list").jqGrid("addRowData", item1, datarow);
                                                $("#mino").prop("selected", true);

                                                //                                            limpiar_campos();
                                            } else {
                                                alertify.error(
                                                        "Error... Alcanzo el limite máximo de Items1"
                                                        );
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

                                        if (dd["iva"] == "Si") {
                                            if (dd["incluye"] == "No") {
                                                subtotal = dd["total"];
                                                sub1 = subtotal;
                                                iva1 = (sub1 * calculoIVA) / 100;

                                                subtotal0 = parseFloat(subtotal0) + 0;
                                                subtotal12 = parseFloat(subtotal12) + parseFloat(sub1);
                                                subtotal_total =
                                                        parseFloat(subtotal0) + parseFloat(subtotal12);
                                                descu_total =
                                                        parseFloat(descu_total) + parseFloat(dd["cal_des"]);
                                                iva12 = parseFloat(iva12) + parseFloat(iva1);

                                                subtotal0 = parseFloat(subtotal0);
                                                subtotal12 = parseFloat(subtotal12);
                                                subtotal_total = parseFloat(subtotal_total);
                                                iva12 = parseFloat(iva12);
                                                descu_total = parseFloat(descu_total);
                                                suma_total = suma_total + dd["cantidad"];
                                            } else {
                                                if (dd["incluye"] == "Si") {
                                                    subtotal = dd["total"];
                                                    sub2 = subtotal / (calculoIVA / 100 + 1);
                                                    iva2 = sub2 * (calculoIVA / 100);

                                                    subtotal0 = parseFloat(subtotal0) + 0;
                                                    subtotal12 = parseFloat(subtotal12) + parseFloat(sub2);
                                                    subtotal_total =
                                                            parseFloat(subtotal0) + parseFloat(subtotal12);
                                                    iva12 = parseFloat(iva12) + parseFloat(iva2);
                                                    descu_total = parseFloat(descu_total) + dd["cal_des"];

                                                    subtotal0 = parseFloat(subtotal0);
                                                    subtotal12 = parseFloat(subtotal12);
                                                    subtotal_total = parseFloat(subtotal_total);
                                                    iva12 = parseFloat(iva12);
                                                    descu_total = parseFloat(descu_total);
                                                    suma_total = suma_total + dd["cantidad"];
                                                }
                                            }
                                        } else {
                                            if (dd["iva"] == "No") {
                                                subtotal = dd["total"];
                                                sub = subtotal;

                                                subtotal0 = parseFloat(subtotal0) + parseFloat(sub);
                                                subtotal12 = parseFloat(subtotal12) + 0;
                                                subtotal_total =
                                                        parseFloat(subtotal0) + parseFloat(subtotal12);
                                                iva12 = parseFloat(iva12) + 0;
                                                descu_total =
                                                        parseFloat(descu_total) + parseFloat(dd["cal_des"]);

                                                subtotal0 = parseFloat(subtotal0);
                                                subtotal12 = parseFloat(subtotal12);
                                                subtotal_total = parseFloat(subtotal_total);
                                                iva12 = parseFloat(iva12);
                                                descu_total = parseFloat(descu_total);
                                                suma_total = suma_total + dd["cantidad"];
                                            }
                                        }
                                    }
                                    total_total =
                                            parseFloat(total_total) +
                                            (parseFloat(subtotal0) +
                                                    parseFloat(subtotal12) +
                                                    parseFloat(iva12));
                                    total_total = parseFloat(total_total);

                                    var item = filas.length + 1;
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
                                    $("#descxax").val(descu_total.toFixed(4));
                                    $("#totx").val(total_total.toFixed(2));
                                    $("#items").val(item);
                                    $("#num").val(suma_total);
                                    $("#codigo_barras").focus();
                                    $("#valor_factura").val(total_total.toFixed(2));
                                }

                            } else {
                                //poner codigo aqui
                                if (parseInt($("#cantidad").val()) > parseInt($("#disponibles").val())) {
                                    $("#cantidad").focus();
                                    alertify.error("Error.. Fuera de Stock cantidad disponible: " + $("#disponibles").val());
                                } else {///cambio 1
                                    var filas = jQuery("#list").jqGrid("getRowData");
                                    var descuento = 0;
                                    var total = 0;
                                    var su = 0;
                                    var promo = 0;
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
                                            multi = $("#cantidad").val() * parseFloat(precio);
                                            descuento = (multi * parseFloat(desc)) / 100;
                                            flotante = parseFloat(descuento);
                                            resultado =
                                                    Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                            total = multi - resultado;
                                        } else {
                                            desc = 0;
                                            precio = parseFloat($("#p_venta").val());
                                            multi = $("#cantidad").val() * parseFloat(precio);
                                            descuento = (multi * parseFloat(desc)) / 100;
                                            flotante = parseFloat(descuento);
                                            resultado =
                                                    Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                            total = parseFloat(multi);
                                        }
                                        console.log("INVENTAR SI, FILAS 0");
                                        var item1 = filas.length + 1;
                                        if ($("#cantidad_unidad").val() != "") {
                                            cantidad_unidad = parseFloat($("#cantidad_unidad").val()) * parseFloat($("#cantidad").val());
                                            unidad_medida = $("#unidad_medida")[0].selectedOptions[0].text;
                                            unidad_medida = unidad_medida.split("--");
                                            unidad_medida = unidad_medida[0];

                                        } else {
                                            cantidad_unidad = 0;
                                            unidad_medida = '';
                                        }
                                        var datarow = {
                                            id_list: item1,
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
                                            pendiente: parseFloat($("#venta_iva").val()).toFixed(2),
                                            incluye: $("#incluye").val(),
                                            cantidad_unidad: cantidad_unidad,
                                            unidad_medida: unidad_medida,
                                            detalle_producto: $("#descripocion_prod").val()
                                        };
                                        entrar22();
                                        su = jQuery("#list").jqGrid("addRowData", item1, datarow);

                                        //                                    limpiar_campos();
                                    } else {
                                        var result = 0;
                                        var iva1 = 0;
                                        var iva_pventa = 0;

                                        for (var i = 0; i < filas.length; i++) {
                                            var id = filas[i];
                                            if (id["cod_producto"] == $("#cod_producto").val()) {
                                                repe = 1;
                                                var can = id["cantidad"];
                                                if (id["iva"] == "Si") {
                                                    suma = parseFloat(can) + parseFloat($("#cantidad").val());
                                                    suma = Number(suma.toFixed(2));
                                                    iva1 = (id["precio_u"] * calculoIVA) / 100;
                                                    iva_pventa = iva1 + parseFloat(id["precio_u"]);
                                                    result = suma * numFormatter(2).format(iva_pventa);
                                                    console.log("aqui/" + id["precio_u"]);
                                                } else {
                                                    suma = parseFloat(can) + parseFloat($("#cantidad").val());
                                                    suma = Number(suma.toFixed(2));
                                                    result = suma * numFormatter(2).format(iva_pventa);
                                                }
                                            }
                                        }

                                        if (repe == 1) {
                                            suma = parseFloat(can) + parseFloat($("#cantidad").val());
                                            suma = Number(suma.toFixed(2));
                                            if (suma > parseInt($("#disponibles").val())) {
                                                $("#cantidad").focus();
                                                alertify.error(
                                                        "Error.. Fuera de Stock cantidad disponible: " +
                                                        $("#disponibles").val()
                                                        );
                                            } else {
                                                if ($("#descuento").val() != "") {
                                                    desc = $("#descuento").val();
                                                    precio = parseFloat($("#p_venta").val());
                                                    multi = parseFloat(suma) * parseFloat(precio);
                                                    descuento = (multi * parseFloat(desc)) / 100;
                                                    flotante = parseFloat(descuento);
                                                    resultado =
                                                            Math.round(flotante * Math.pow(10, 2)) /
                                                            Math.pow(10, 2);
                                                    total = multi - resultado;
                                                } else {
                                                    desc = 0;
                                                    precio = parseFloat($("#p_venta").val());
                                                    multi = suma * parseFloat(precio);
                                                    descuento = (multi * parseFloat(desc)) / 100;
                                                    flotante = parseFloat(descuento);
                                                    resultado =
                                                            Math.round(flotante * Math.pow(10, 2)) /
                                                            Math.pow(10, 2);
                                                    total = parseFloat(multi);
                                                }
                                                console.log("inventar si CODIGO PRODUCTO ES = REPE =1");
                                                var item1 = 0;
                                                // encuentra el mismo id
                                                filas.map((prod) => {
                                                    if (prod.cod_producto == $("#cod_producto").val())
                                                        item1 = prod.id_list;
                                                });
                                                if ($("#cantidad_unidad").val() != "") {
                                                    cantidad_unidad = parseFloat($("#cantidad_unidad").val()) * parseFloat($("#cantidad").val());
                                                    unidad_medida = $("#unidad_medida")[0].selectedOptions[0].text;
                                                    unidad_medida = unidad_medida.split("--");
                                                    unidad_medida = unidad_medida[0];
                                                } else {
                                                    cantidad_unidad = 0;
                                                    unidad_medida = '';
                                                }
                                                datarow = {
                                                    id_list: item1,
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
                                                    pendiente: numFormatter(2).format(result),
                                                    incluye: $("#incluye").val(),
                                                    cantidad_unidad: cantidad_unidad,
                                                    unidad_medida: unidad_medida,
                                                    detalle_producto: $("#descripocion_prod").val()
                                                };

                                                su = jQuery("#list").jqGrid("setRowData", item1, datarow);
                                                $("#mino").prop("selected", true);
                                                entrar22();
                                                limpiar_campos();
                                            }
                                        } else {
                                            if (filas.length < $("#num_items").val()) {
                                                if ($("#descuento").val() != "") {
                                                    desc = $("#descuento").val();
                                                    precio = parseFloat($("#p_venta").val());
                                                    multi = $("#cantidad").val() * parseFloat(precio);
                                                    descuento = (multi * parseFloat(desc)) / 100;
                                                    flotante = parseFloat(descuento);
                                                    resultado =
                                                            Math.round(flotante * Math.pow(10, 2)) /
                                                            Math.pow(10, 2);
                                                    total = multi - resultado;
                                                } else {
                                                    desc = 0;
                                                    precio = parseFloat($("#p_venta").val());
                                                    multi = $("#cantidad").val() * parseFloat(precio);
                                                    descuento = (multi * parseFloat(desc)) / 100;
                                                    flotante = parseFloat(descuento);
                                                    resultado =
                                                            Math.round(flotante * Math.pow(10, 2)) /
                                                            Math.pow(10, 2);
                                                    total = parseFloat(multi);
                                                }
                                                console.log(
                                                        "inventar si CODIGO PRODUCTO ES = REPE ES DIFERENTE 1"
                                                        );
                                                //var item1 = filas.length + 1;
                                                let filastmp = $("#list").jqGrid("getRowData");
                                                let maxid = filastmp[filastmp.length - 1]["id_list"];
                                                var item1 = +maxid + 1;

                                                if ($("#cantidad_unidad").val() != "") {
                                                    cantidad_unidad = parseFloat($("#cantidad_unidad").val()) * parseFloat($("#cantidad").val());
                                                    unidad_medida = $("#unidad_medida")[0].selectedOptions[0].text;
                                                    unidad_medida = unidad_medida.split("--");
                                                    unidad_medida = unidad_medida[0];
                                                } else {

                                                    cantidad_unidad = 0;
                                                    unidad_medida = '';

                                                }
                                                datarow = {
                                                    id_list: item1,
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
                                                    pendiente: parseFloat($("#venta_iva").val()).toFixed(2),
                                                    incluye: $("#incluye").val(),
                                                    cantidad_unidad: cantidad_unidad,
                                                    unidad_medida: unidad_medida,
                                                    detalle_producto: $("#descripocion_prod").val()
                                                };
                                                entrar22();
                                                su = jQuery("#list").jqGrid("addRowData", item1, datarow);

                                                //                                            limpiar_campos();
                                            } else {
                                                alertify.error(
                                                        "Error... Alcanzo el limite máximo de Items1"
                                                        );
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

                                        if (dd["iva"] == "Si") {
                                            if (dd["incluye"] == "No") {
                                                subtotal = dd["total"];
                                                sub1 = subtotal;
                                                iva1 = (sub1 * calculoIVA) / 100;

                                                subtotal0 = parseFloat(subtotal0) + 0;
                                                subtotal12 = parseFloat(subtotal12) + parseFloat(sub1);
                                                subtotal_total =
                                                        parseFloat(subtotal0) + parseFloat(subtotal12);
                                                descu_total =
                                                        parseFloat(descu_total) + parseFloat(dd["cal_des"]);
                                                iva12 = parseFloat(iva12) + parseFloat(iva1);

                                                subtotal0 = parseFloat(subtotal0);
                                                subtotal12 = parseFloat(subtotal12);
                                                subtotal_total = parseFloat(subtotal_total);
                                                iva12 = parseFloat(iva12);
                                                descu_total = parseFloat(descu_total);
                                                suma_total = suma_total + dd["cantidad"];
                                            } else {
                                                if (dd["incluye"] == "Si") {
                                                    subtotal = dd["total"];
                                                    sub2 = subtotal / (calculoIVA / 100 + 1);
                                                    iva2 = sub2 * (calculoIVA / 100);

                                                    subtotal0 = parseFloat(subtotal0) + 0;
                                                    subtotal12 = parseFloat(subtotal12) + parseFloat(sub2);
                                                    subtotal_total =
                                                            parseFloat(subtotal0) + parseFloat(subtotal12);
                                                    iva12 = parseFloat(iva12) + parseFloat(iva2);
                                                    descu_total = parseFloat(descu_total) + dd["cal_des"];

                                                    subtotal0 = parseFloat(subtotal0);
                                                    subtotal12 = parseFloat(subtotal12);
                                                    subtotal_total = parseFloat(subtotal_total);
                                                    iva12 = parseFloat(iva12);
                                                    descu_total = parseFloat(descu_total);
                                                    suma_total = suma_total + dd["cantidad"];
                                                }
                                            }
                                        } else {
                                            if (dd["iva"] == "No") {
                                                subtotal = dd["total"];
                                                sub = subtotal;

                                                subtotal0 = parseFloat(subtotal0) + parseFloat(sub);
                                                subtotal12 = parseFloat(subtotal12) + 0;
                                                subtotal_total =
                                                        parseFloat(subtotal0) + parseFloat(subtotal12);
                                                iva12 = parseFloat(iva12) + 0;
                                                descu_total =
                                                        parseFloat(descu_total) + parseFloat(dd["cal_des"]);

                                                subtotal0 = parseFloat(subtotal0);
                                                subtotal12 = parseFloat(subtotal12);
                                                subtotal_total = parseFloat(subtotal_total);
                                                iva12 = parseFloat(iva12);
                                                descu_total = parseFloat(descu_total);
                                                suma_total = suma_total + dd["cantidad"];
                                            }
                                        }
                                    }
                                    total_total =
                                            parseFloat(total_total) +
                                            (parseFloat(subtotal0) +
                                                    parseFloat(subtotal12) +
                                                    parseFloat(iva12));
                                    total_total = parseFloat(total_total);

                                    var item = filas.length + 1;
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
                                    $("#descxax").val(descu_total.toFixed(4));
                                    $("#totx").val(total_total.toFixed(2));
                                    $("#items").val(item);
                                    $("#num").val(suma_total);
                                    $("#codigo_barras").focus();
                                    $("#valor_factura").val(total_total.toFixed(2));
                                }


                                //fin de codigo nuevo
                            }



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
                                        multi = $("#cantidad").val() * parseFloat(precio);
                                        descuento = (multi * parseFloat(desc)) / 100;
                                        flotante = parseFloat(descuento);
                                        resultado =
                                                Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                        total = multi - resultado;
                                    } else {
                                        desc = 0;
                                        precio = parseFloat($("#p_venta").val());
                                        multi = $("#cantidad").val() * parseFloat(precio);
                                        descuento = (multi * parseFloat(desc)) / 100;
                                        flotante = parseFloat(descuento);
                                        resultado =
                                                Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                        total = parseFloat(multi);
                                    }
                                    console.log("INVENTARIO ES NO filas 0");
                                    var item1 = filas.length + 1;
                                    if ($("#cantidad_unidad").val() != "") {
                                        cantidad_unidad = parseFloat($("#cantidad_unidad").val()) * parseFloat($("#cantidad").val());
                                        unidad_medida = $("#unidad_medida")[0].selectedOptions[0].text;
                                        unidad_medida = unidad_medida.split("--");
                                        unidad_medida = unidad_medida[0];
                                    } else {
                                        cantidad_unidad = 0;
                                        unidad_medida = '';
                                    }
                                    var datarow = {
                                        id_list: item1,
                                        cod_producto: $("#cod_producto").val(),
                                        codigo: $("#codigo").val(),
                                        detalle: $("#producto").val(),
                                        cantidad: parseFloat($("#cantidad").val()).toFixed(2),
                                        precio_u: precio,
                                        descuento: desc,
                                        cal_des: resultado,
                                        total: total,
                                        precio_ux: precio.toFixed(2),
                                        descuentox: parseFloat(desc).toFixed(2),
                                        cal_desx: resultado.toFixed(2),
                                        totalx: total.toFixed(2),
                                        iva: $("#iva_producto").val(),
                                        pendiente: parseFloat($("#venta_iva").val()).toFixed(2),
                                        incluye: $("#incluye").val(),
                                        cantidad_unidad: cantidad_unidad,
                                        unidad_medida: unidad_medida,
                                        detalle_producto: $("#descripocion_prod").val()
                                    };
                                    entrar22();
                                    su = jQuery("#list").jqGrid("addRowData", item1, datarow);
                                    $("#mino").prop("selected", true);

                                    //                                    limpiar_campos();
                                } else {
                                    for (var i = 0; i < filas.length; i++) {
                                        var id = filas[i];
                                        if (id["cod_producto"] == $("#cod_producto").val()) {
                                            repe = 1;
                                            var can = id["cantidad"];
                                        }
                                    }

                                    if (repe == 1) {
                                        suma = parseFloat(can) + parseFloat($("#cantidad").val());
                                        suma = Number(suma.toFixed(2));
                                        console.log(suma);
                                        if ($("#descuento").val() != "") {
                                            desc = $("#descuento").val();
                                            precio = parseFloat($("#p_venta").val());
                                            multi = suma * parseFloat(precio);
                                            descuento = (multi * parseFloat(desc)) / 100;
                                            flotante = parseFloat(descuento);
                                            resultado =
                                                    Math.round(flotante * Math.pow(10, 2)) /
                                                    Math.pow(10, 2);
                                            total = multi - resultado;
                                        } else {
                                            desc = 0;
                                            precio = parseFloat($("#p_venta").val());
                                            multi = suma * parseFloat(precio);
                                            descuento = (multi * parseFloat(desc)) / 100;
                                            flotante = parseFloat(descuento);
                                            resultado =
                                                    Math.round(flotante * Math.pow(10, 2)) /
                                                    Math.pow(10, 2);
                                            total = parseFloat(multi);
                                        }
                                        console.log("CODIGO PRODUCTO ES = REPE =1 INVEN NO");
                                        var item1 = 0;
                                        // encuentra el mismo id
                                        filas.map((prod) => {
                                            if (prod.cod_producto == $("#cod_producto").val())
                                                item1 = prod.id_list;
                                        });
                                        if ($("#cantidad_unidad").val() != "") {
                                            cantidad_unidad = parseFloat($("#cantidad_unidad").val()) * parseFloat($("#cantidad").val());
                                            unidad_medida = $("#unidad_medida")[0].selectedOptions[0].text;
                                            unidad_medida = unidad_medida.split("--");
                                            unidad_medida = unidad_medida[0];
                                        } else {
                                            cantidad_unidad = 0;
                                            unidad_medida = '';
                                        }
                                        datarow = {
                                            id_list: item1,
                                            cod_producto: $("#cod_producto").val(),
                                            codigo: $("#codigo").val(),
                                            detalle: $("#producto").val(),
                                            cantidad: suma.toFixed(2),
                                            precio_u: precio,
                                            descuento: desc,
                                            cal_des: resultado,
                                            total: total,
                                            precio_ux: precio.toFixed(2),
                                            descuentox: parseFloat(desc).toFixed(2),
                                            cal_desx: resultado.toFixed(2),
                                            totalx: total.toFixed(2),
                                            iva: $("#iva_producto").val(),
                                            pendiente: parseFloat($("#venta_iva").val()).toFixed(2),
                                            incluye: $("#incluye").val(),
                                            cantidad_unidad: cantidad_unidad,
                                            unidad_medida: unidad_medida,
                                            detalle_producto: $("#descripocion_prod").val()
                                        };

                                        su = jQuery("#list").jqGrid("setRowData", item1, datarow);
                                        entrar22();
                                        limpiar_campos();
                                    } else {
                                        if (filas.length < $("#num_items").val()) {
                                            if ($("#descuento").val() != "") {
                                                desc = $("#descuento").val();
                                                precio = parseFloat($("#p_venta").val());
                                                multi = $("#cantidad").val() * parseFloat(precio);
                                                descuento = (multi * parseFloat(desc)) / 100;
                                                flotante = parseFloat(descuento);
                                                resultado =
                                                        Math.round(flotante * Math.pow(10, 2)) /
                                                        Math.pow(10, 2);
                                                total = multi - resultado;
                                            } else {
                                                desc = 0;
                                                precio = parseFloat($("#p_venta").val());
                                                multi = $("#cantidad").val() * parseFloat(precio);
                                                descuento = (multi * parseFloat(desc)) / 100;
                                                flotante = parseFloat(descuento);
                                                resultado =
                                                        Math.round(flotante * Math.pow(10, 2)) /
                                                        Math.pow(10, 2);
                                                total = parseFloat(multi);
                                            }
                                            console.log(
                                                    "CODIGO PRODUCTO ES = REPE DIFERENTE 1 INVEN NO"
                                                    );
                                            //var item1 = filas.length + 1;
                                            let filastmp = $("#list").jqGrid("getRowData");
                                            let maxid = filastmp[filastmp.length - 1]["id_list"];
                                            var item1 = +maxid + 1;

                                            if ($("#cantidad_unidad").val() != "") {
                                                cantidad_unidad = parseFloat($("#cantidad_unidad").val()) * parseFloat($("#cantidad").val());
                                                unidad_medida = $("#unidad_medida")[0].selectedOptions[0].text;
                                                unidad_medida = unidad_medida.split("--");
                                                unidad_medida = unidad_medida[0];
                                            } else {
                                                cantidad_unidad = 0;
                                                unidad_medida = '';

                                            }
                                            datarow = {
                                                id_list: item1,
                                                cod_producto: $("#cod_producto").val(),
                                                codigo: $("#codigo").val(),
                                                detalle: $("#producto").val(),
                                                cantidad: parseFloat($("#cantidad").val()).toFixed(2),
                                                precio_u: precio,
                                                descuento: desc,
                                                cal_des: resultado,
                                                total: total,
                                                precio_ux: precio.toFixed(2),
                                                descuentox: parseFloat(desc).toFixed(2),
                                                cal_desx: resultado.toFixed(2),
                                                totalx: total.toFixed(2),
                                                iva: $("#iva_producto").val(),
                                                pendiente: parseFloat($("#venta_iva").val()).toFixed(2),
                                                incluye: $("#incluye").val(),
                                                cantidad_unidad: cantidad_unidad,
                                                unidad_medida: unidad_medida,
                                                detalle_producto: $("#descripocion_prod").val()
                                            };
                                            entrar22();
                                            su = jQuery("#list").jqGrid("addRowData", item1, datarow);
                                            $("#mino").prop("selected", true);

                                            //                                            limpiar_campos();
                                        } else {
                                            alertify.error(
                                                    "Error... Alcanzo el limite máximo de Items"
                                                    );
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
                                    if (dd["iva"] == "Si") {
                                        if (dd["incluye"] == "No") {
                                            subtotal = dd["total"];
                                            sub1 = subtotal;
                                            iva1 = sub1 * (calculoIVA / 100);

                                            subtotal0 = parseFloat(subtotal0) + 0;
                                            subtotal12 = parseFloat(subtotal12) + parseFloat(sub1);
                                            subtotal_total =
                                                    parseFloat(subtotal0) + parseFloat(subtotal12);
                                            descu_total =
                                                    parseFloat(descu_total) + parseFloat(dd["cal_des"]);
                                            iva12 = parseFloat(iva12) + parseFloat(iva1);

                                            subtotal0 = parseFloat(subtotal0);
                                            subtotal12 = parseFloat(subtotal12);
                                            subtotal_total = parseFloat(subtotal_total);
                                            iva12 = parseFloat(iva12);
                                            descu_total = parseFloat(descu_total);
                                            suma_total = suma_total + dd["cantidad"];
                                        } else {
                                            if (dd["incluye"] == "Si") {
                                                subtotal = dd["total"];
                                                sub2 = subtotal / (calculoIVA / 100 + 1);
                                                iva2 = sub2 * (calculoIVA / 100);

                                                subtotal0 = parseFloat(subtotal0) + 0;
                                                subtotal12 = parseFloat(subtotal12) + parseFloat(sub2);
                                                subtotal_total =
                                                        parseFloat(subtotal0) + parseFloat(subtotal12);
                                                iva12 = parseFloat(iva12) + parseFloat(iva2);
                                                descu_total =
                                                        parseFloat(descu_total) + parseFloat(dd["cal_des"]);

                                                subtotal0 = parseFloat(subtotal0);
                                                subtotal12 = parseFloat(subtotal12);
                                                subtotal_total = parseFloat(subtotal_total);
                                                iva12 = parseFloat(iva12);
                                                descu_total = parseFloat(descu_total);
                                                suma_total = suma_total + dd["cantidad"];
                                            }
                                        }
                                    } else {
                                        if (dd["iva"] == "No") {
                                            subtotal = dd["total"];
                                            sub = subtotal;

                                            subtotal0 = parseFloat(subtotal0) + parseFloat(sub);
                                            subtotal12 = parseFloat(subtotal12) + 0;
                                            subtotal_total =
                                                    parseFloat(subtotal0) + parseFloat(subtotal12);
                                            iva12 = parseFloat(iva12) + 0;
                                            descu_total =
                                                    parseFloat(descu_total) + parseFloat(dd["cal_des"]);

                                            subtotal0 = parseFloat(subtotal0);
                                            subtotal12 = parseFloat(subtotal12);
                                            subtotal_total = parseFloat(subtotal_total);
                                            iva12 = parseFloat(iva12);
                                            descu_total = parseFloat(descu_total);
                                            suma_total = suma_total + dd["cantidad"];
                                        }
                                    }
                                }

                                total_total =
                                        parseFloat(total_total) +
                                        (parseFloat(subtotal0) +
                                                parseFloat(subtotal12) +
                                                parseFloat(iva12));
                                total_total = parseFloat(total_total);
                                var item = filas.length + 1;
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
                                $("#descxax").val(descu_total.toFixed(4));
                                $("#totx").val(total_total.toFixed(2));
                                $("#items").val(item);
                                $("#num").val(suma_total);
                                $("#codigo_barras").focus();
                                $("#valor_factura").val(total_total.toFixed(2));
                            }
                        }
                    }
                }
            }
        }
    }
    funcion_descuento_factura(false);
}
function abrirDialogo_unidad() {
    var cod = $("#cod_producto").val();

    if (cod == "") {
        alertify.alert("Error... Seleccione un producto");
    } else {
        $("#unidad_medida").append("<option></option>");
        $.getJSON("retornar_series_unidad.php?cod=" + cod, function (data) {
            var tama = data.length;
            if (tama == 0) {
                //                alertify.alert("Series no ingresadas");
            } else {
                if ($("#cod_producto").val() == "") {
                    $("#cod_producto").focus();
                    alertify.alert("Error... Indique una cantidad");

                } else {
                    $("#unidad_medida").children().remove().end();

                    $("#unidad_medida").append("<option></option>");
                    for (var i = 0; i < tama; i = i + 2) {
                        $("#unidad_medida").append(
                                "<option value=" + data[i] + " >" + data[i + 1] + "</option>"
                                );
                    }
                    $.widget("custom.combobox", {
                        _create: function () {
                            this.wrapper = $("<span>")
                                    .addClass("custom-combobox")
                                    .insertAfter(this.element);
                            this.element.hide();
                            this._createAutocomplete();
                            this._createShowAllButton();
                        },
                        _createAutocomplete: function () {
                            var selected = this.element.children(":selected"),
                                    value = selected.val() ? selected.text() : "";
                            this.input = $("<input>")
                                    .appendTo(this.wrapper)
                                    .val(value)
                                    .attr("title", "")
                                    .addClass(
                                            "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left"
                                            )
                                    .autocomplete({
                                        delay: 0,
                                        minLength: 0,
                                        source: $.proxy(this, "_source"),
                                    })
                                    .tooltip({
                                        tooltipClass: "ui-state-highlight",
                                    });

                            this._on(this.input, {
                                autocompleteselect: function (event, ui) {
                                    ui.item.option.selected = true;
                                    this._trigger("select", event, {
                                        item: ui.item.option,
                                    });
                                },
                                autocompletechange: "_removeIfInvalid",
                            });
                        },

                        _createShowAllButton: function () {
                            var input = this.input,
                                    wasOpen = false;
                            $("<a>")
                                    .attr("tabIndex", -1)
                                    .attr("title", "Todas las series")
                                    .tooltip()
                                    .appendTo(this.wrapper)
                                    .button({
                                        icons: {
                                            primary: "ui-icon-triangle-1-s",
                                        },
                                        text: false,
                                    })
                                    .removeClass("ui-corner-all")
                                    .addClass("custom-combobox-toggle ui-corner-right")
                                    .mousedown(function () {
                                        wasOpen = input.autocomplete("widget").is(":visible");
                                    })
                                    .click(function () {
                                        input.focus();

                                        if (wasOpen) {
                                            return;
                                        }
                                        input.autocomplete("search", "");
                                    });
                        },

                        _source: function (request, response) {
                            var matcher = new RegExp(
                                    $.ui.autocomplete.escapeRegex(request.term),
                                    "i"
                                    );
                            response(
                                    this.element.children("option").map(function () {
                                var text = $(this).text();
                                if (this.value && (!request.term || matcher.test(text)))
                                    return {
                                        label: text,
                                        value: text,
                                        option: this,
                                    };
                            })
                                    );
                        },

                        _removeIfInvalid: function (event, ui) {
                            if (ui.item) {
                                return;
                            }
                            var value = this.input.val(),
                                    valueLowerCase = value.toLowerCase(),
                                    valid = false;
                            this.element.children("option").each(function () {
                                if ($(this).text().toLowerCase() === valueLowerCase) {
                                    this.selected = valid = true;
                                    return false;
                                }
                            });
                            if (valid) {
                                return;
                            }
                            this.input
                                    .val("")
                                    .attr("title", value + " La serie no existe")
                                    .tooltip("open");
                            this.element.val("");
                            this._delay(function () {
                                this.input.tooltip("close").attr("title", "");
                            }, 2500);
                            this.input.autocomplete("instance").term = "";
                        },
                        _destroy: function () {
                            this.wrapper.remove();
                            this.element.show();
                        },
                    });
                    $("#combobox").combobox();
                }
            }
        });
    }
}
function abrirDialogo() {
    var cod = $("#cod_producto").val();

    if (cod == "") {
        alertify.alert("Error... Seleccione un producto");
    } else {
        $("#combobox").append("<option></option>");
        $.getJSON("retornar_series.php?cod=" + cod, function (data) {
            var tama = data.length;
            if (tama == 0) {
                alertify.alert("Series no ingresadas");
            } else {
                if ($("#cantidad").val() == "") {
                    $("#cantidad").focus();
                    alertify.alert("Error... Indique una cantidad");
                } else {
                    $("#combobox").children().remove().end();
                    $("#series").dialog("open");
                    $("#combobox").append("<option></option>");
                    for (var i = 0; i < tama; i = i + 1) {
                        $("#combobox").append(
                                "<option value=" + data[i] + " >" + data[i] + "</option>"
                                );
                    }
                    $.widget("custom.combobox", {
                        _create: function () {
                            this.wrapper = $("<span>")
                                    .addClass("custom-combobox")
                                    .insertAfter(this.element);
                            this.element.hide();
                            this._createAutocomplete();
                            this._createShowAllButton();
                        },
                        _createAutocomplete: function () {
                            var selected = this.element.children(":selected"),
                                    value = selected.val() ? selected.text() : "";
                            this.input = $("<input>")
                                    .appendTo(this.wrapper)
                                    .val(value)
                                    .attr("title", "")
                                    .addClass(
                                            "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left"
                                            )
                                    .autocomplete({
                                        delay: 0,
                                        minLength: 0,
                                        source: $.proxy(this, "_source"),
                                    })
                                    .tooltip({
                                        tooltipClass: "ui-state-highlight",
                                    });

                            this._on(this.input, {
                                autocompleteselect: function (event, ui) {
                                    ui.item.option.selected = true;
                                    this._trigger("select", event, {
                                        item: ui.item.option,
                                    });
                                },
                                autocompletechange: "_removeIfInvalid",
                            });
                        },
                        _createShowAllButton: function () {
                            var input = this.input,
                                    wasOpen = false;
                            $("<a>")
                                    .attr("tabIndex", -1)
                                    .attr("title", "Todas las series")
                                    .tooltip()
                                    .appendTo(this.wrapper)
                                    .button({
                                        icons: {
                                            primary: "ui-icon-triangle-1-s",
                                        },
                                        text: false,
                                    })
                                    .removeClass("ui-corner-all")
                                    .addClass("custom-combobox-toggle ui-corner-right")
                                    .mousedown(function () {
                                        wasOpen = input.autocomplete("widget").is(":visible");
                                    })
                                    .click(function () {
                                        input.focus();

                                        if (wasOpen) {
                                            return;
                                        }
                                        input.autocomplete("search", "");
                                    });
                        },
                        _source: function (request, response) {
                            var matcher = new RegExp(
                                    $.ui.autocomplete.escapeRegex(request.term),
                                    "i"
                                    );
                            response(
                                    this.element.children("option").map(function () {
                                var text = $(this).text();
                                if (this.value && (!request.term || matcher.test(text)))
                                    return {
                                        label: text,
                                        value: text,
                                        option: this,
                                    };
                            })
                                    );
                        },
                        _removeIfInvalid: function (event, ui) {
                            if (ui.item) {
                                return;
                            }
                            var value = this.input.val(),
                                    valueLowerCase = value.toLowerCase(),
                                    valid = false;
                            this.element.children("option").each(function () {
                                if ($(this).text().toLowerCase() === valueLowerCase) {
                                    this.selected = valid = true;
                                    return false;
                                }
                            });
                            if (valid) {
                                return;
                            }
                            this.input
                                    .val("")
                                    .attr("title", value + " La serie no existe")
                                    .tooltip("open");
                            this.element.val("");
                            this._delay(function () {
                                this.input.tooltip("close").attr("title", "");
                            }, 2500);
                            this.input.autocomplete("instance").term = "";
                        },
                        _destroy: function () {
                            this.wrapper.remove();
                            this.element.show();
                        },
                    });
                    $("#combobox").combobox();
                }
            }
        });
    }
}

function comprobar2reten() {
    $("#cuenta_contable_reten").attr("disabled", false);
    $("#btnCuenta_reten").attr("disabled", false);
    $("#formaspago_mixto_reten").attr("disabled", false);
    var subtotal = 0;
    var xsid_bienes = document.getElementById("tipoRetencionesF").selectedIndex;
    var xsid_servicio =
            document.getElementById("tipoRetencionesFS").selectedIndex;
    var xsid_iva = document.getElementById("tipoRetencionesI").selectedIndex;
    var xsid_ivas = document.getElementById("tipoRetencionesIs").selectedIndex;

    if (document.getElementById("retencionF2").checked) {
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
                impuesto = "RENTA BIENES";
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
                        tipo_ret: "b",
                    };
                    su = jQuery("#listPagoreten").jqGrid(
                            "addRowData",
                            $("#calculobien").val(),
                            datarow
                            );
                } else {
                    for (var i = 0; i < filas.length; i++) {
                        var id = filas[i];
                        if (id["impuesto"] == impuesto) {
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
                            tipo_ret: "b",
                        };
                        su = jQuery("#listPagoreten").jqGrid("addRowData", $("#calculobien").val(), datarow);
                        //                                    limpiar_campos();
                    } else {
                        datarow = {
                            base_imponible: $("#calculobien").val(),
                            impuesto: impuesto,
                            porcent_reten: $("#porcent_reten").val(),
                            valor_retenido: $("#calculoRetencionF").val(),
                            id_retenciones_ser: xsid_bienes,
                            tipo_ret: "b",
                        };
                        su = jQuery("#listPagoreten").jqGrid(
                                "addRowData",
                                $("#calculobien").val(),
                                datarow
                                );
                        limpiar_campos();
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
                        tipo_ret: "b",
                    };
                    su = jQuery("#listPagoreten").jqGrid("addRowData", $("#calculobien").val(), datarow);
                } else {
                    for (var i = 0; i < filas.length; i++) {
                        var id = filas[i];
                        if (id["impuesto"] == impuesto) {
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
                            tipo_ret: "b",
                        };
                        su = jQuery("#listPagoreten").jqGrid("addRowData", $("#calculobien").val(), datarow);
                        //                                    limpiar_campos();
                    } else {
                        datarow = {
                            base_imponible: $("#calculobien").val(),
                            impuesto: impuesto,
                            porcent_reten: $("#porcent_reten").val(),
                            valor_retenido: $("#calculoRetencionF").val(),
                            id_retenciones_ser: xsid_bienes,
                            tipo_ret: "b",
                        };
                        su = jQuery("#listPagoreten").jqGrid("addRowData", $("#calculobien").val(), datarow);
                        limpiar_campos();
                    }
                }
            } else {
                alertify.error("Error....el valor debe ser diferente de 0");
            }

            var fil = jQuery("#listPagoreten").jqGrid("getRowData");
            for (var t = 0; t < fil.length; t++) {
                var dd = fil[t];

                subtotal = subtotal + parseFloat(dd["valor_retenido"]);
            }

            $("#total_retencion").val(subtotal.toFixed(2));
        }
        $("#retencionF1").prop("checked", true);
    }
    if (document.getElementById("retencionF2S").checked) {
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
                impuesto = "RENTA SERVICIOS";
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
                        tipo_ret: "s",
                    };
                    su = jQuery("#listPagoreten").jqGrid(
                            "addRowData",
                            $("#calculoserv").val(),
                            datarow
                            );
                    // limpiar_campos();
                } else {
                    for (var i = 0; i < filas.length; i++) {
                        var id = filas[i];
                        if (id["impuesto"] == impuesto) {
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
                            tipo_ret: "s",
                        };
                        su = jQuery("#listPagoreten").jqGrid(
                                "addRowData",
                                $("#calculoserv").val(),
                                datarow
                                );
                        //limpiar_campos();
                    } else {
                        datarow = {
                            base_imponible: $("#calculoserv").val(),
                            impuesto: impuesto,
                            porcent_reten: $("#porcent_retens").val(),
                            valor_retenido: $("#calculoRetencionFS").val(),
                            id_retenciones_ser: xsid_servicio,
                            tipo_ret: "s",
                        };
                        su = jQuery("#listPagoreten").jqGrid("addRowData", $("#calculoserv").val(), datarow);
                        //                limpiar_campos();
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
                        tipo_ret: "s",
                    };
                    su = jQuery("#listPagoreten").jqGrid("addRowData", $("#calculoserv").val(), datarow);
                    // limpiar_campos();
                } else {
                    for (var i = 0; i < filas.length; i++) {
                        var id = filas[i];
                        if (id["impuesto"] == impuesto) {
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
                            tipo_ret: "s",
                        };
                        su = jQuery("#listPagoreten").jqGrid("addRowData", $("#calculoserv").val(), datarow);
                        //limpiar_campos();
                    } else {
                        datarow = {
                            base_imponible: $("#calculoserv").val(),
                            impuesto: impuesto,
                            porcent_reten: $("#porcent_retens").val(),
                            valor_retenido: $("#calculoRetencionFS").val(),
                            id_retenciones_ser: xsid_servicio,
                            tipo_ret: "s",
                        };
                        su = jQuery("#listPagoreten").jqGrid("addRowData", $("#calculoserv").val(), datarow);
                        //                limpiar_campos();
                    }
                }
            } else {
                alertify.error("Error....el valor debe ser diferente de 0");
            }

            var fil = jQuery("#listPagoreten").jqGrid("getRowData");
            for (var t = 0; t < fil.length; t++) {
                var dd = fil[t];

                subtotal = subtotal + parseFloat(dd["valor_retenido"]);
            }

            $("#total_retencion").val(subtotal.toFixed(2));
        }
        $("#retencionF1S").prop("checked", true);
    }

    if (document.getElementById("tipoRetencionesI").selectedIndex != "0") {
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
                impuesto = "IVA";
            }
            if ($("#calculoRetencionI").val() != "0.00") {
                if (filas.length == 0) {
                    var datarow = {
                        base_imponible: parseFloat($("#iva").val()).toFixed(2),
                        impuesto: impuesto,
                        porcent_reten: $("#porcent_iva").val(),
                        valor_retenido: $("#calculoRetencionI").val(),
                        id_retenciones_ser: xsid_iva,
                        tipo_ret: "b",
                    };
                    su = jQuery("#listPagoreten").jqGrid(
                            "addRowData",
                            parseFloat($("#iva").val()).toFixed(2),
                            datarow
                            );
                    //                                limpiar_campos();
                } else {
                    for (var i = 0; i < filas.length; i++) {
                        var id = filas[i];
                        if (id["impuesto"] == impuesto) {
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
                            tipo_ret: "b",
                        };
                        su = jQuery("#listPagoreten").jqGrid("addRowData", parseFloat($("#iva").val()).toFixed(2), datarow);
                        //                                    limpiar_campos();
                    } else {
                        datarow = {
                            base_imponible: parseFloat($("#iva").val()).toFixed(2),
                            impuesto: impuesto,
                            porcent_reten: $("#porcent_iva").val(),
                            valor_retenido: $("#calculoRetencionI").val(),
                            id_retenciones_ser: xsid_iva,
                            tipo_ret: "b",
                        };
                        su = jQuery("#listPagoreten").jqGrid("addRowData", parseFloat($("#iva").val()).toFixed(2), datarow);
                        //                limpiar_campos();
                    }
                }
            } else {
                alertify.error("Error....el valor debe ser diferente de 0");
            }

            var fil = jQuery("#listPagoreten").jqGrid("getRowData");
            for (var t = 0; t < fil.length; t++) {
                var dd = fil[t];

                subtotal = subtotal + parseFloat(dd["valor_retenido"]);
            }

            $("#total_retencion").val(subtotal.toFixed(2));
        }
        $("#retencionI1").prop("checked", true);
    }
    if (document.getElementById("tipoRetencionesIs").selectedIndex != "0") {
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
                impuesto = "IVA SERVICIOS";
            }
            var calculoservivaS =
                    $("#calculoservivas").val() * toFixedDown(12 / 100, 3);
            if ($("#calculoRetencionIs").val() != "0.00") {
                if (filas.length == 0) {
                    var datarow = {
                        base_imponible: parseFloat(calculoservivaS).toFixed(2),
                        impuesto: impuesto,
                        porcent_reten: $("#porcent_ivas").val(),
                        valor_retenido: $("#calculoRetencionIs").val(),
                        id_retenciones_ser: xsid_ivas,
                        tipo_ret: "s",
                    };
                    su = jQuery("#listPagoreten").jqGrid("addRowData", parseFloat(calculoservivaS).toFixed(2), datarow);
                } else {
                    for (var i = 0; i < filas.length; i++) {
                        var id = filas[i];
                        if (id["impuesto"] == impuesto) {
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
                            tipo_ret: "s",
                        };
                        su = jQuery("#listPagoreten").jqGrid("addRowData", parseFloat(calculoservivaS).toFixed(2), datarow);
                        //                                    limpiar_campos();
                    } else {
                        datarow = {
                            base_imponible: parseFloat(calculoservivaS).toFixed(2),
                            impuesto: impuesto,
                            porcent_reten: $("#porcent_ivas").val(),
                            valor_retenido: $("#calculoRetencionIs").val(),
                            id_retenciones_ser: xsid_ivas,
                            tipo_ret: "s",
                        };
                        su = jQuery("#listPagoreten").jqGrid("addRowData", parseFloat(calculoservivaS).toFixed(2), datarow);
                    }
                }
            } else {
                alertify.error("Error....El valor debe ser diferente de 0");
            }

            var fil = jQuery("#listPagoreten").jqGrid("getRowData");
            for (var t = 0; t < fil.length; t++) {
                var dd = fil[t];

                subtotal = subtotal + parseFloat(dd["valor_retenido"]);
            }

            $("#total_retencion").val(subtotal.toFixed(2));
        }
        $("#retencionI1s").prop("checked", true);
    }
}
/////////////////////////////////////////////////
function cambio_ret_fuente() {
    $("#retencionF1S").prop("checked", true);
    $("#retencionI1").prop("checked", true);

    if (document.getElementById("retencionF2").checked) {
        if ($("#id_factura_venta").val() != "") {
            if (t == 0) {
                alertify.alert("La factura es una factura temporal");
                document.getElementById("retencionF1").checked = true;
            } else {
                $("#tipoRetencionesF").attr("disabled", false);
            }
        } else {
            alertify.alert("Error, debe seleccionar una factura");
            document.getElementById("retencionF1").checked = true;
        }
    } else if (document.getElementById("retencionF1").checked) {
        document.getElementById("tipoRetencionesF").selectedIndex = 0;
        $("#tipoRetencionesF").attr("disabled", true);
        $("#calculoRetencionF").attr("disabled", false);
        $("#calculoRetencionF").val("0.000");
    }
}
function buscar_bienservicio_producto() {
    console.log("id_factu " + $("#id_factura_venta").val());
    $.ajax({
        type: "POST",
        url: "buscar_bienservicio_producto.php",
        data: "id=" + $("#id_factura_venta").val(),
        success: function (data) {
            var valSUM = data;
            $("#calculobien").val(valSUM);
        },
    });
}
function cambio_ret_ivas() {
    $("#retencionF1").prop("checked", true);
    $("#retencionI1").prop("checked", true);
    $("#retencionF1S").prop("checked", true);
    if (document.getElementById("retencionI2s").checked) {
        if ($("#id_factura_venta").val() != "") {
            if (t == 0) {
                alertify.alert("La factura es una factura temporal");
                document.getElementById("retencionI1s").checked = true;
            } else {
                $("#tipoRetencionesIs").attr("disabled", false);
            }
        } else {
            alertify.alert("Error, debe seleccionar una factura");
            document.getElementById("retencionI1s").checked = true;
        }
    } else if (document.getElementById("retencionI1").checked) {
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
        url: "buscar_ret_fuente.php",
        data: "id=" + x,
        success: function (data) {
            var val = data;
            calculoRET = val;
            $("#calculoRetencionF").val("");
            var valor = toFixedDown(($("#calculobien").val() * calculoRET) / 100, 3);
            $("#calculoRetencionF").val(numFormatter(2).format(valor));
            $("#porcent_reten").val(calculoRET);
            $("#calculoRetencionF").focus();
        },
    });
}
function buscar_servicio_producto() {
    $.ajax({
        type: "POST",
        url: "buscar_servicio_producto.php",
        data: "id=" + $("#id_factura_venta").val(),
        success: function (data) {
            var valSUMs = data;
            $("#calculoserv").val(valSUMs);
        },
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
        url: "buscar_ret_fuente.php",
        data: "id=" + x,
        success: function (data) {
            var val = data;
            $("#calculoRetencionFS").val("");
            calculoRET = val;
            var valor = toFixedDown(($("#calculoserv").val() * calculoRET) / 100, 3);
            $("#calculoRetencionFS").val(numFormatter(2).format(valor));
            $("#porcent_retens").val(calculoRET);
            $("#calculoRetencionFS").focus();
        },
    });
}
function cambio_ret_fuenteS() {
    $("#retencionF1").prop("checked", true);
    $("#retencionI1").prop("checked", true);
    if (document.getElementById("retencionF2S").checked) {
        if ($("#id_factura_venta").val() != "") {
            if (t == 0) {
                alertify.alert("La factura es una factura temporal");
                document.getElementById("retencionF1S").checked = true;
            } else {
                $("#tipoRetencionesFS").attr("disabled", false);
            }
        } else {
            alertify.alert("Error, debe seleccionar una factura");
            document.getElementById("retencionF1S").checked = true;
        }
    } else if (document.getElementById("retencionF1S").checked) {
        document.getElementById("tipoRetencionesFS").selectedIndex = 0;
        $("#tipoRetencionesFS").attr("disabled", true);
        $("#calculoRetencionFS").attr("disabled", false);
        $("#calculoRetencionFS").val("0.000");
    }
}
function cambio_ret_iva() {
    $("#retencionF1").prop("checked", true);
    $("#retencionF1S").prop("checked", true);
    if (document.getElementById("retencionI2").checked) {
        if ($("#id_factura_venta").val() != "") {
            if (t == 0) {
                alertify.alert("La factura es una factura temporal");
                document.getElementById("retencionI1").checked = true;
            } else {
                $("#tipoRetencionesI").attr("disabled", false);
            }
        } else {
            alertify.alert("Error, debe seleccionar una factura");
            document.getElementById("retencionI1").checked = true;
        }
    } else if (document.getElementById("retencionI1").checked) {
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
        url: "../../procesos/buscar_ret_iva_r.php",
        data: "id=" + x,
        success: function (data) {
            var val = data;
            if (val != 0) {
                calculoRET = val;
                var calculoserviva =
                        $("#calculobieniva").val() * toFixedDown(12 / 100, 3);
                var valor = toFixedDown((calculoserviva * calculoRET) / 100, 3);
                $("#calculoRetencionI").val(numFormatter(2).format(valor));
                $("#porcent_iva").val(calculoRET);
                $("#calculoRetencionI").focus();
            }
        },
    });
}
function buscar_bienservicio_producto_iva() {
    $.ajax({
        type: "POST",
        url: "buscar_bienservicio_producto_iva.php",
        data: "id=" + $("#id_factura_venta").val(),
        success: function (data) {
            var valSUM = data;
            $("#calculobieniva").val(valSUM);
        },
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
        url: "../../procesos/buscar_ret_iva_r.php",
        data: "id=" + x,
        success: function (data) {
            var val = data;
            if (val != 0) {
                calculoRET = val;
                var calculoservivas =
                        $("#calculoservivas").val() * toFixedDown(12 / 100, 3);
                var valor = toFixedDown((calculoservivas * calculoRET) / 100, 3);
                $("#calculoRetencionIs").val(numFormatter(2).format(valor));
                $("#porcent_ivas").val(calculoRET);
                $("#calculoRetencionIs").focus();
            }
        },
    });
}
function guardar_retenciones_factura_venta() {
    console.log($("#formaspago_mixto_reten").val());
    if ($("#idCuenta_reten").val() == "") {
        $("#formaspago_mixto_reten").focus();
        alertify.error(
                "Error.. Debe seleccionar la forma de pago o la Cuenta contable"
                );
    } else {
        if (
                $("#formaspago_mixto_reten").val() == "Transferencias_reten" &&
                $("#idCuenta_reten").val() == ""
                ) {
            $("#cuenta_contable_reten").focus();
            alertify.error("Error.. Debe seleccionar Cuenta contable");
        } else {
            if (
                    $("#formaspago_mixto_reten").val() == "Cheque_reten" &&
                    $("#idCuenta_reten").val() == ""
                    ) {
                $("#cuenta_contable_reten").focus();
                alertify.error("Error.. Debe seleccionar Cuenta contable");
            } else {
                if (
                        $("#formaspago_mixto_reten").val() == "TCredito_reten" &&
                        $("#idCuenta_reten").val() == ""
                        ) {
                    $("#cuenta_contable_reten").focus();
                    alertify.error("Error.. Debe seleccionar Cuenta contable");
                } else {
                    if (
                            $("#formaspago_mixto_reten").val() == "Contado_reten" &&
                            $("#idCuenta_reten").val() == ""
                            ) {
                        $("#cuenta_contable_reten").focus();
                        alertify.error("Error.. Debe seleccionar Cuenta contable");
                    } else {
                        if (
                                $("#formaspago_mixto_reten").val() == "cxc" &&
                                $("#idCuenta_reten").val() == ""
                                ) {
                            $("#cuenta_contable_reten").focus();
                            alertify.error("Error.. Debe seleccionar Cuenta contable");
                        } else {
                            if (
                                    $("#formaspago_mixto_reten").val() == "cxp" &&
                                    $("#idCuenta_reten").val() == ""
                                    ) {
                                $("#cuenta_contable_reten").focus();
                                alertify.error("Error.. Debe seleccionar Cuenta contable");
                            } else {
                            }

                            var tam = jQuery("#listPagoreten").jqGrid("getRowData");
                            var y = document.getElementById("tipoRetencionesI").selectedIndex;

                            if (document.getElementById("retencionI2").checked == true) {
                                var xx = 1;
                            } else {
                                var xx = 0;
                            }
                            if (document.getElementById("retencionF2S").checked == true) {
                                var xxs = 1;
                            } else {
                                var xxs = 0;
                            }

                            var x = document.getElementById("tipoRetencionesF").selectedIndex;
                            var xs =
                                    document.getElementById("tipoRetencionesFS").selectedIndex;
                            if ($("#serie_retencion").val() == "") {
                                $("#serie_retencion").focus();
                                alertify.error("Ingrese número de la Retención");
                            } else {

                                if ($("#autorizacion_retencion").val() == "") {
                                    $("#autorizacion_retencion").focus();
                                    alertify.error("Ingrese número de autorizción");
                                    return;
                                }
                                if ($("#fecha_aut_retencion").val() == "") {
                                    $("#fecha_aut_retencion").focus();
                                    alertify.error("Ingrese fecha de autorizción");
                                    return;
                                }

                                var num_retencion =
                                        "001" + "-" + "001" + "-" + $("#serie_retencion").val();
                                var a = autocompletar_reten($("#serie_retencion").val());
                                //        if ($("#punto_ventaid").val() == 1) {
                                var seriee = $("#serie_retencion").val();
                                //        }
                                //        if ($("#punto_ventaid").val() == 2) {
                                //            var seriee = ("001" + "-" + "003" + "-" + a + "" + $("#serie_retencion").val());
                                //        }
                                //        if ($("#punto_ventaid").val() == 3) {
                                //            var seriee = ("001" + "-" + "001" + "-" + a + "" + $("#serie_retencion").val());
                                //        }
                                //        if ($("#punto_ventaid").val() == 4) {
                                //            var seriee = ("003" + "-" + "001" + "-" + a + "" + $("#serie_retencion").val());
                                //        }
                                //        if ($("#punto_ventaid").val() == 5) {
                                //            var seriee = ("005" + "-" + "001" + "-" + a + "" + $("#serie_retencion").val());
                                //        }
                                $.ajax({
                                    type: "POST",
                                    url: "comparar_num_retencion.php",
                                    data: "num_reten=" + seriee,
                                    success: function (data) {
                                        var val = data;
                                        if (val != 0) {
                                            $("#serie_retencion").val("");
                                            $("#serie_retencion").focus();
                                            alertify.error(
                                                    "Error... La Retenciòn ya existe, favor verificar el nùmero que corresponda"
                                                    );
                                        } else {
                                            if ($("#ruc_ci").val() == "") {
                                                $("#ruc_ci").focus();
                                                alertify.error("Indique un cliente");
                                            } else {
                                                if ($("#fecha_retencion").val() == "") {
                                                    $("#fecha_retencion").focus();
                                                    alertify.error("Seleccione una fecha");
                                                } else {
                                                    if (tam.length == 0) {
                                                        alertify.error("Error... Ingrese Retenciones");
                                                    } else {
                                                        if ($("#autorizacion_retencion").val() != "") {
                                                            var a = autocompletar_reten(
                                                                    $("#serie_retencion").val()
                                                                    );
                                                            //                                if ($("#punto_ventaid").val() == 1) {
                                                            var seriee = $("#serie_retencion").val();
                                                            //                                }
                                                            //                                if ($("#punto_ventaid").val() == 2) {
                                                            //                                    var seriee = ("001" + "-" + "003" + "-" + a + "" + $("#serie_retencion").val());
                                                            //                                }
                                                            //                                if ($("#punto_ventaid").val() == 3) {
                                                            //                                    var seriee = ("001" + "-" + "001" + "-" + a + "" + $("#serie_retencion").val());
                                                            //                                }
                                                            //                                if ($("#punto_ventaid").val() == 4) {
                                                            //                                    var seriee = ("003" + "-" + "001" + "-" + a + "" + $("#serie_retencion").val());
                                                            //                                }
                                                            //                                if ($("#punto_ventaid").val() == 5) {
                                                            //                                    var seriee = ("005" + "-" + "001" + "-" + a + "" + $("#serie_retencion").val());
                                                            //                                }

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

                                                            var fil =
                                                                    jQuery("#listPagoreten").jqGrid("getRowData");
                                                            for (var i = 0; i < fil.length; i++) {
                                                                var datos = fil[i];
                                                                v1[i] = datos["base_imponible"];
                                                                v2[i] = datos["impuesto"];
                                                                v3[i] = datos["porcent_reten"];
                                                                v4[i] = datos["valor_retenido"];
                                                                v5[i] = datos["valor_retenido"];
                                                                v6[i] = datos["id_retenciones_ser"];
                                                                v7[i] = datos["tipo_ret"];
                                                                v8[i] = datos["codigo_ret"];
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

                                                            let valor_retencioni = $("#calculoRetencionI").val();

                                                            let itemc2 = fil.find(el => el.codigo_imp == 2);
                                                            if (itemc2) {
                                                                calculoretencionii = itemc2.id_retenciones_ser;
                                                                valor_retencioni = itemc2.valor_retenido;
                                                            } else {
                                                                if ($("#calculoRetencionI").val() == "0.000") {
                                                                    var calculoretencionii =
                                                                            document.getElementById(
                                                                                    "tipoRetencionesIs"
                                                                                    ).selectedIndex;
                                                                } else {
                                                                    calculoretencionii = y;
                                                                }
                                                            }

                                                            $("#btnGuardarRetenciones").attr(
                                                                    "disabled",
                                                                    true
                                                                    );
                                                            $.ajax({
                                                                type: "POST",
                                                                url: "guardar_ret_fuente_fact_venta.php",
                                                                data:
                                                                        "id_factura=" +
                                                                        $("#id_factura_venta").val() +
                                                                        "&id_retencion_fuente=" +
                                                                        x +
                                                                        "&fecha_actual=" +
                                                                        $("#fecha_actual").val() +
                                                                        "&hora_actual=" +
                                                                        $("#hora_actual").val() +
                                                                        "&valor_factura=" +
                                                                        $("#sub").val() +
                                                                        "&iva_factura=" +
                                                                        $("#iva").val() +
                                                                        "&valor_retencion=" +
                                                                        $("#calculoRetencionF").val() +
                                                                        "&autorizacion_ret=" +
                                                                        $("#autorizacion_retencion").val() +
                                                                        "&serie_retencion=" +
                                                                        seriee +
                                                                        "&porcent_reten=" +
                                                                        $("#porcent_reten").val() +
                                                                        "&id_retencion_iva=" +
                                                                        calculoretencionii +
                                                                        "&valor_facturaiva=" +
                                                                        $("#tot").val() +
                                                                        "&valor_retencioni=" +
                                                                        valor_retencioni +
                                                                        "&valor_seleccion_iva=" +
                                                                        xx +
                                                                        "&porcent_iva=" +
                                                                        $("#porcent_iva").val() +
                                                                        "&id_retencion_fuentes=" +
                                                                        xs +
                                                                        "&valor_retencions=" +
                                                                        $("#calculoRetencionFS").val() +
                                                                        "&porcent_retens=" +
                                                                        $("#porcent_retens").val() +
                                                                        "&valor_seleccion_si_no=" +
                                                                        xxs +
                                                                        "&campo1reten=" +
                                                                        string_v1 +
                                                                        "&campo2reten=" +
                                                                        string_v2 +
                                                                        "&campo3reten=" +
                                                                        string_v3 +
                                                                        "&campo4reten=" +
                                                                        string_v4 +
                                                                        "&campo5reten=" +
                                                                        string_v5 +
                                                                        "&campo6reten=" +
                                                                        string_v6 +
                                                                        "&campo7reten=" +
                                                                        string_v7 +
                                                                        "&campo8reten=" +
                                                                        string_v8 +
                                                                        "&sub=" +
                                                                        $("#sub").val() +
                                                                        "&id_cliente=" +
                                                                        $("#id_cliente").val() +
                                                                        "&num_factura=" +
                                                                        $("#num_factura").val() +
                                                                        "&total_reten_iva=" +
                                                                        $("#total_retencion").val() +
                                                                        "&formaspago_mixto_reten=" +
                                                                        $("#formaspago_mixto_reten").val() +
                                                                        "&idCuenta_reten=" +
                                                                        $("#idCuenta_reten").val() +
                                                                        "&fecha_retencion=" +
                                                                        $("#fecha_retencion").val() +
                                                                        "&fecha_aut_retencion=" +
                                                                        $("#fecha_aut_retencion").val(),
                                                                dataType: "json",
                                                                success: function (data) {
                                                                    var val = data;

                                                                    if (data == 1) {
                                                                        alertify.alert(
                                                                                "Retenciones guardadas correctamente",
                                                                                function () {
                                                                                    location.reload();
                                                                                }
                                                                        );
                                                                    } else if (data == 2) {
                                                                        alertify.alert(
                                                                                "La Factura ya tiene retenciones en la fuente"
                                                                                );
                                                                    } else {
                                                                        alertify.alert(val);
                                                                    }
                                                                },
                                                            });
                                                        } else {
                                                            alertify.alert(
                                                                    "Ingrese Nùmero de Autorizaciòn de la Retenciòn"
                                                                    );
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    },
                                });
                            }
                        }
                    }
                }
            }
        }
    }
}
function buscar_servicio_iva() {
    console.log("entro iva servicios");
    $.ajax({
        type: "POST",
        url: "buscar_ret_iva_servicio.php",
        data: "id=" + $("#id_factura_venta").val(),
        success: function (data) {
            var valSUMs = data;
            $("#calculoservivas").val(valSUMs);
        },
    });
}
function agregar() {
    if ($("#formaspago").val() == "otros") {
        $("#validar_guardar_grid").val("1");

        var subtotal_adelanto = 0;
        var subtotal_adelanto1 = 0;
        if ($("#formaspago_mixto").val() == "Credito") {
            $('.nav-tabs a[href="#tab_3"]').tab("show");
            $("#meses").val(1);

            $("#fecha_dias").focus();
            $("#fecha_dias").select();
            subtotal_adelanto =
                    parseFloat($("#totx").val()) - parseFloat($("#valor_formas").val());
        }

        $("#adelanto").val(subtotal_adelanto.toFixed(2));
        var subtotal1 = 0;
        var subtotal11 = 0;
        var fil = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
        for (var t = 0; t < fil.length; t++) {
            var dd = fil[t];
            subtotal1 =
                    parseFloat($("#valor_formas").val()) + parseFloat(dd["valor"]);
        }

        subtotal11 =
                parseFloat($("#valor_formas").val()) +
                parseFloat($("#cantidad_mixto").val());
        console.log("DDD" + subtotal11.toFixed(2));
        if (parseFloat(subtotal11.toFixed(2)) > parseFloat($("#totx").val())) {
            alertify.error(
                    "Error1.. La suma supera el total de la Factura " + $("#totx").val()
                    );
        } else {
            if (
                    parseFloat($("#cantidad_mixto").val()) > parseFloat($("#totx").val())
                    ) {
                alertify.error(
                        "Error2.. La suma supera el total de la Factura " + $("#totx").val()
                        );
            } else {
                if (parseFloat(subtotal1.toFixed(2)) > parseFloat($("#totx").val())) {
                    alertify.error(
                            "Error3.. La suma supera el total de la Factura " + $("#totx").val()
                            );
                } else {
                    if (
                            parseFloat($("#valor_formas").val()) > parseFloat($("#totx").val())
                            ) {
                        alertify.error(
                                "Error4.. La suma supera el total de la Factura " +
                                $("#totx").val()
                                );
                    } else {
                        if ($("#valor_formas").val() == "") {
                            $("#valor_formas").focus();
                            alertify.error("Error... Ingrese la cantidad");
                        } else {
                            if (
                                    $("#formaspago_mixto").val() == "Credito" &&
                                    $("#fecha_dias").val() == ""
                                    ) {
                                $("#fecha_dias").focus();
                                alertify.error("Error.. Debe seleccionar Fecha de Vencimiento");
                            } else {
                                if (
                                        $("#formaspago_mixto").val() == "Transferencias" &&
                                        $("#idCuenta").val() == ""
                                        ) {
                                    $("#cuenta_contable").focus();
                                    alertify.error("Error.. Debe seleccionar Cuenta contable");
                                } else {
                                    var filas2 = jQuery("#listPagoreten_mixto").jqGrid(
                                            "getRowData"
                                            );
                                    var su;
                                    var count = 0;
                                    var canti = $("#valor_formas").val();
                                    //                    if (filas2.length < canti) {

                                    if (filas2.length == 0) {
                                        //                            alertify.alert("dddd1");
                                        var id_factura_nota = "";
                                        if ($("#tipo_venta").val() == "FACTURA")
                                            id_factura_nota = $("#comprobante").val();
                                        else {
                                            id_factura_nota = $("#comprobante_nota").val();
                                        }

                                        var datarow = {
                                            id_f_v_mix: (count = count + 1),
                                            id_factura_venta: id_factura_nota,
                                            fecha: $("#fecha_actual").val(),
                                            forma_pago_mixto: $("#formaspago_mixto").val(),
                                            tarjeta_credito: $("#tarjetas").val(),
                                            num_documento: $("#num_tarjeta").val(),
                                            valor: $("#valor_formas").val(),
                                            id_cuenta: $("#idCuenta").val(),
                                            fecha_vencimiento: $("#fecha_dias").val(),
                                        };

                                        su = jQuery("#listPagoreten_mixto").jqGrid("addRowData", count, datarow);
                                        //                            console.log("dddffd"+filas2.length);
                                        var subtotal = 0;
                                        var sub1 = 0;
                                        var fil = jQuery("#listPagoreten_mixto").jqGrid(
                                                "getRowData"
                                                );
                                        for (var t = 0; t < fil.length; t++) {
                                            var dd = fil[t];
                                            subtotal = subtotal + parseFloat(dd["valor"]);
                                        }

                                        $("#cantidad_mixto").val(subtotal.toFixed(2));
                                        var subtotal_adelanto1 =
                                                parseFloat($("#valor_factura").val()) -
                                                parseFloat($("#cantidad_mixto").val());

                                        $("#valor_factura_saldo").val(
                                                subtotal_adelanto1.toFixed(2)
                                                );
                                        $("#valor_formas").val("");
                                        $("#tarjetas").val("");
                                        $("#num_tarjeta").val("");
                                        $("#formaspago_mixto").focus();
                                    } else {
                                        count = 1;
                                        var repe = 0;
                                        var fil = jQuery("#listPagoreten_mixto").jqGrid(
                                                "getRowData"
                                                );
                                        for (var t = 0; t < fil.length; t++) {
                                            var dd = fil[t];
                                            //                    console.log($("#formaspago_mixto").val());
                                            //                     console.log(dd['forma_pago_mixto']);
                                            if (dd["forma_pago_mixto"] == $("#formaspago_mixto").val()) {
                                                repe = 1;
                                            }
                                        }

                                        console.log("RRRTRT" + repe);
                                        if (repe == 1) {
                                            alertify.error("FORMA DE PAGO YA EXISTE");
                                        } else {
                                            var id_factura_nota = "";
                                            if ($("#tipo_venta").val() == "FACTURA")
                                                id_factura_nota = $("#comprobante").val();
                                            else {
                                                id_factura_nota = $("#comprobante_nota").val();
                                            }
                                            datarow = {
                                                id_f_v_mix: (count = count + filas2.length),
                                                id_factura_venta: id_factura_nota,
                                                fecha: $("#fecha_actual").val(),
                                                forma_pago_mixto: $("#formaspago_mixto").val(),
                                                tarjeta_credito: $("#tarjetas").val(),
                                                num_documento: $("#num_tarjeta").val(),
                                                valor: $("#valor_formas").val(),
                                                id_cuenta: $("#idCuenta").val(),
                                                fecha_vencimiento: $("#fecha_dias").val(),
                                            };
                                            su = jQuery("#listPagoreten_mixto").jqGrid("addRowData", count, datarow);
                                            var subtotal = 0;
                                            var sub1 = 0;
                                            var fil = jQuery("#listPagoreten_mixto").jqGrid(
                                                    "getRowData"
                                                    );
                                            for (var t = 0; t < fil.length; t++) {
                                                var dd = fil[t];
                                                subtotal = subtotal + parseFloat(dd["valor"]);
                                            }
                                            $("#cantidad_mixto").val(subtotal.toFixed(2));
                                            var subtotal_adelanto1 =
                                                    parseFloat($("#valor_factura").val()) -
                                                    parseFloat($("#cantidad_mixto").val());

                                            $("#valor_factura_saldo").val(
                                                    subtotal_adelanto1.toFixed(2)
                                                    );
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
    } else {
        alertify.error("Error... DEBE SELECCIONAR FORMA DE PAGO");
        $("#formaspago").focus();
    }
}

/*function agregar() {
 if ($("#combobox").val() != "") {
 var filas2 = jQuery("#list3").jqGrid("getRowData");
 var su;
 var count = 0;
 var canti = $("#cantidad").val();
 
 if (filas2.length < canti) {
 if (filas2.length == 0) {
 var datarow = {
 id_serie: count = count + 1,
 serie: $("#combobox").val()
 };
 su = jQuery("#list3").jqGrid('addRowData', count, datarow);
 $("#combobox").val("");
 } else {
 var repe = 0;
 for (var i = 0; i < filas2.length; i++) {
 var id = filas2[i];
 if (id['serie'] === $("#combobox").val()) {
 repe = 1;
 }
 }
 if (repe == 0) {
 datarow = {
 id_serie: count = count + 1,
 serie: $("#combobox").val()
 };
 su = jQuery("#list3").jqGrid('addRowData', count, datarow);
 $("#combobox").val("");
 } else {
 $("#combobox").val("");
 alertify.alert("Error... Serie ingresada");
 }
 }
 } else {
 alertify.alert("Error... Alcanzo el limite máximo");
 }
 } else {
 $("#combobox").focus();
 alertify.alert("Error... En la serie");
 }
 }*/

//function actualizar_vendedor() {
//    $("#vendedor").load("vendedor_combos.php");
//}
function actualizar_um() {
    $("#unidad_medida").load("um_combo.php");
}
function seleccion_row() {
    var id_max = $("#producto").val();
    $.ajax({
        url: "datos_productos_max.php",
        type: "POST",
        data: "texto_max=" + id_max,
        success: function (data) {
            var val = data;
            if (val != 0) {
                var valor_num = val;
                jQuery("#listp").jqGrid("setSelection", valor_num);

                $("#listp").focus();
            } else {
                alertify.error("No Existe");
            }
        },
    });
}

function countfactura() {
    var temp2 = "";
    var serie = $("#num_factura").val();
    for (var i = serie.length; i < 5; i++) {
        temp2 = temp2 + "0";
    }
    return temp2;
}

function autocompletar() {
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

//function nuevo_cliente() {
//    alertify.confirm("Desea registrar un nuevo cliente", function (e) {
//        if (e) {
//            // verificar si esxiste cliente
//            $.ajax({
//                type: "POST",
//                url: "comparar_cedulas.php",
//                data: "cedula=" + $("#ruc_ci").val(),
//                success: function (data) {
//                    var val = data;
//                    if (val == 1) {
//                        $("#ruc_ci").val("");
//                        $("#ruc_ci").focus();
//                        alertify.error("Error... El cliente esta registrado");
//                    } else {
//                        if (
//                            $("#ruc_ci").val().length != 10 &&
//                            $("#ruc_ci").val().length !== 13
//                        ) {
//                            alertify.error("Error... Ingrese una Identificación valida");
//                        } else {
//                            // validar cedula ruc
//                            var numero = $("#ruc_ci").val();
//                            var suma = 0;
//                            var residuo = 0;
//                            var pri = false;
//                            var pub = false;
//                            var nat = false;
//                            var modulo = 11;
//                            var p1;
//                            var p2;
//                            var p3;
//                            var p4;
//                            var p5;
//                            var p6;
//                            var p7;
//                            var p8;
//                            var p9;
//
//                            /* Aqui almacenamos los digitos de la cedula en variables. */
//                            var d1 = numero.substr(0, 1);
//                            var d2 = numero.substr(1, 1);
//                            var d3 = numero.substr(2, 1);
//                            var d4 = numero.substr(3, 1);
//                            var d5 = numero.substr(4, 1);
//                            var d6 = numero.substr(5, 1);
//                            var d7 = numero.substr(6, 1);
//                            var d8 = numero.substr(7, 1);
//                            var d9 = numero.substr(8, 1);
//                            var d10 = numero.substr(9, 1);
//
//                            if (d3 < 6) {
//                                nat = true;
//                                p1 = d1 * 2;
//                                if (p1 >= 10)
//                                    p1 -= 9;
//                                p2 = d2 * 1;
//                                if (p2 >= 10)
//                                    p2 -= 9;
//                                p3 = d3 * 2;
//                                if (p3 >= 10)
//                                    p3 -= 9;
//                                p4 = d4 * 1;
//                                if (p4 >= 10)
//                                    p4 -= 9;
//                                p5 = d5 * 2;
//                                if (p5 >= 10)
//                                    p5 -= 9;
//                                p6 = d6 * 1;
//                                if (p6 >= 10)
//                                    p6 -= 9;
//                                p7 = d7 * 2;
//                                if (p7 >= 10)
//                                    p7 -= 9;
//                                p8 = d8 * 1;
//                                if (p8 >= 10)
//                                    p8 -= 9;
//                                p9 = d9 * 2;
//                                if (p9 >= 10)
//                                    p9 -= 9;
//                                modulo = 10;
//                            } else if (d3 == 6) {
//                                pub = true;
//                                p1 = d1 * 3;
//                                p2 = d2 * 2;
//                                p3 = d3 * 7;
//                                p4 = d4 * 6;
//                                p5 = d5 * 5;
//                                p6 = d6 * 4;
//                                p7 = d7 * 3;
//                                p8 = d8 * 2;
//                                p9 = 0;
//                            } else if (d3 == 9) {
//                                pri = true;
//                                p1 = d1 * 4;
//                                p2 = d2 * 3;
//                                p3 = d3 * 2;
//                                p4 = d4 * 7;
//                                p5 = d5 * 6;
//                                p6 = d6 * 5;
//                                p7 = d7 * 4;
//                                p8 = d8 * 3;
//                                p9 = d9 * 2;
//                            }
//
//                            suma = p1 + p2 + p3 + p4 + p5 + p6 + p7 + p8 + p9;
//                            residuo = suma % modulo;
//
//                            var digitoVerificador = residuo == 0 ? 0 : modulo - residuo;
//                            if (numero.length === 10) {
//                                if (nat == true) {
//                                    if (digitoVerificador != d10) {
//                                        alertify.error("El número de cédula es incorrecto.");
//                                        $("#direccion_cliente").attr("disabled", "disabled");
//                                        $("#telefono_cliente").attr("disabled", "disabled");
//                                        $("#correo").attr("disabled", "disabled");
//                                    } else {
//                                        if ($("#ruc_ci").val() == "0000000000") {
//                                            alertify.error("El número de cédula es incorrecto.");
//                                            $("#direccion_cliente").attr("disabled", "disabled");
//                                            $("#telefono_cliente").attr("disabled", "disabled");
//                                            $("#correo").attr("disabled", "disabled");
//                                        } else {
//                                            alertify.success("El número de cédula es correcto.");
//                                            $("#nombre_cliente").focus();
//                                            $("#direccion_cliente").removeAttr("disabled");
//                                            $("#telefono_cliente").removeAttr("disabled");
//                                            $("#correo").removeAttr("disabled");
//                                        }
//                                    }
//                                }
//                            } else {
//                                var ruc = numero.substr(10, 13);
//                                var digito3 = numero.substring(2, 3);
//                                if (ruc == "001") {
//                                    if (digito3 < 6) {
//                                        if (nat == true) {
//                                            if (digitoVerificador != d10) {
//                                                alertify.error("El ruc persona natural3 es incorrecto.");
//                                                $("#direccion_cliente").attr("disabled", "disabled");
//                                                $("#telefono_cliente").attr("disabled", "disabled");
//                                                $("#correo").attr("disabled", "disabled");
//                                            } else {
//                                                alertify.success("El ruc persona natural3 es correcto.");
//                                                $("#nombre_cliente").focus();
//                                                $("#direccion_cliente").removeAttr("disabled");
//                                                $("#telefono_cliente").removeAttr("disabled");
//                                                $("#correo").removeAttr("disabled");
//                                            }
//                                        }
//                                    } else {
//                                        if (digito3 == 6) {
//                                            if (pub == true) {
//                                                if (digitoVerificador != d9) {
//
//                                                    validarIdentificacion($("#ruc_ci"), "ruc",
//                                                        function () {
//                                                            alertify.success("El ruc público es correcto.");
//                                                            $("#nombre_cliente").focus();
//                                                            $("#direccion_cliente").removeAttr("disabled");
//                                                            $("#telefono_cliente").removeAttr("disabled");
//                                                            $("#correo").removeAttr("disabled");
//                                                        },
//                                                        function () {
//                                                            alertify.error("El ruc público es incorrecto.");
//                                                            $("#direccion_cliente").attr("disabled", "disabled");
//                                                            $("#telefono_cliente").attr("disabled", "disabled");
//                                                            $("#correo").attr("disabled", "disabled");
//                                                        });
//
//                                                    alertify.error("El ruc público es incorrecto.");
//                                                    $("#direccion_cliente").attr("disabled", "disabled");
//                                                    $("#telefono_cliente").attr("disabled", "disabled");
//                                                    $("#correo").attr("disabled", "disabled");
//                                                } else {
//                                                    alertify.success("El ruc público es correcto.");
//                                                    $("#nombre_cliente").focus();
//                                                    $("#direccion_cliente").removeAttr("disabled");
//                                                    $("#telefono_cliente").removeAttr("disabled");
//                                                    $("#correo").removeAttr("disabled");
//                                                }
//                                            }
//                                        } else {
//                                            if (digito3 == 9) {
//                                                if (pri == true) {
//                                                    if (digitoVerificador != d10) {
//
//                                                        //TODO validar ruc
//                                                        validarIdentificacion($("#ruc_ci"), "ruc",
//                                                            function () {
//                                                                alertify.success("El ruc privado es correcto.");
//                                                                $("#nombre_cliente").focus();
//                                                                $("#direccion_cliente").removeAttr("disabled");
//                                                                $("#telefono_cliente").removeAttr("disabled");
//                                                                $("#correo").removeAttr("disabled");
//                                                            },
//                                                            function () {
//                                                                alertify.error("El ruc privado es incorrecto.");
//                                                                $("#direccion_cliente").attr("disabled", "disabled");
//                                                                $("#telefono_cliente").attr("disabled", "disabled");
//                                                                $("#correo").attr("disabled", "disabled");
//                                                            });
//
//                                                    } else {
//                                                        alertify.success("El ruc privado es correcto.");
//                                                        $("#nombre_cliente").focus();
//                                                        $("#direccion_cliente").removeAttr("disabled");
//                                                        $("#telefono_cliente").removeAttr("disabled");
//                                                        $("#correo").removeAttr("disabled");
//                                                    }
//                                                }
//                                            } else {
//                                                if (d3 == 7 || d3 == 8) {
//                                                    alertify.error(
//                                                        "El tercer dígito ingresado es inválido"
//                                                    );
//                                                } else {
//                                                    if (numero.substr(10, 3) != "001") {
//                                                        alertify.error(
//                                                            "El ruc de la empresa del sector privado debe terminar con 001"
//                                                        );
//                                                    }
//                                                }
//                                            }
//                                        }
//                                    }
//                                } else {
//                                    if (numero.length == 13) {
//                                        alertify.error("El ruc es incorrecto.");
//                                        $("#direccion_cliente").attr("disabled", "disabled");
//                                        $("#telefono_cliente").attr("disabled", "disabled");
//                                        $("#correo").attr("disabled", "disabled");
//                                    }
//                                }
//                            }
//                        }
//                    }
//                },
//            });
//        } else {
//            $("#ruc_ci").val("");
//            $("#direccion_cliente").attr("disabled", "disabled");
//            $("#telefono_cliente").attr("disabled", "disabled");
//            $("#correo").attr("disabled", "disabled");
//        }
//    });
//}

function comprobar1(valciruc) {
    if ($("#num_factura").val() == "") {
        $("#num_factura").focus();
        alertify.error("Ingrese número de factura");
    } else {
        $.ajax({
            type: "POST",
            url: "comparar_cedulas.php",
            data: "cedula=" + valciruc,
            success: function (data) {
                if (data == 1) {
                    buscarClienteAutocomplete(valciruc);
                } else {
                    funcion_buscar_cliente();

                }
            }
        });
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
        var cambio =
                parseFloat($("#valor_recibo").val()) - parseFloat($("#tot").val());
        $("#valor_cambio").val("0");
        $("#valor_cambio").val(cambio.toFixed(2));
        //          setTimeout(function () {
        //                       $("#valor_cambio").select();
        //                    }, 3000);

        $("#valor_cambio").select();


    } else {
        false;
    }
}

function guardar_serie(fun) {
    var tam2 = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
    if ($("#formaspago").val() == "otros") {
        if (
                $("#formaspago").val() == "otros" &&
                $("#valor_factura_saldo").val() != "0.00"
                ) {
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
                var v8 = new Array();
                var string_v1 = "";
                var string_v2 = "";
                var string_v3 = "";
                var string_v4 = "";
                var string_v5 = "";
                var string_v6 = "";
                var string_v7 = "";
                var string_v8 = "";

                var fil = jQuery("#listPagoreten_mixto").jqGrid("getRowData");

                for (var i = 0; i < fil.length; i++) {
                    var datos = fil[i];
                    v1[i] = datos["id_f_v_mix"];
                    v2[i] = datos["id_factura_venta"];
                    v3[i] = datos["forma_pago_mixto"];
                    v4[i] = datos["tarjeta_credito"];
                    v5[i] = datos["num_documento"];
                    v6[i] = datos["valor"];
                    v7[i] = datos["id_cuenta"];
                    v8[i] = datos["fecha_vencimiento"];
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
                var repe = 0;
                var filas = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
                for (var i = 0; i < filas.length; i++) {
                    var id = filas[i];

                    if (id["forma_pago_mixto"] == "Credito") {
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
                        data:
                                "id_factura_venta=" +
                                $("#id_factura_venta").val() +
                                "&campo1=" +
                                string_v1 +
                                "&campo2=" +
                                string_v2 +
                                "&campo3=" +
                                string_v3 +
                                "&campo4=" +
                                string_v4 +
                                "&campo5=" +
                                string_v5 +
                                "&campo6=" +
                                string_v6 +
                                "&campo7=" +
                                string_v7 +
                                "&campo8=" +
                                string_v8 +
                                "&comprobante=" +
                                $("#comprobante").val() +
                                "&formaspago_mixto=" +
                                $("#formaspago_mixto").val() +
                                "&tarjetas=" +
                                $("#tarjetas").val() +
                                "&num_tarjeta=" +
                                $("#num_tarjeta").val() +
                                "&fecha_actual=" +
                                $("#fecha_actual").val() +
                                "&tipo_venta=" +
                                $("#tipo_venta").val(),
                        success: function (data) {
                            var val = data;
                            if (val == 1) {
                                fun();
                                alertify.success(" Guardado Correctamente");
                                $("#listPagoreten_mixto").jqGrid("clearGridData", true);
                                $("#cantidad_mixto").val() == "";
                                $("#validar_guardar").val("1");
                                $("#btnGuardarRetenciones_mixto").attr("disabled", true);
                            }
                        },
                    });
                }
            }
        }
    } else {
        fun();
    }
}

function guardar_cambio() {
    var cambio = 0;
    if ($("#valor_recibo").val() != "") {
        if ($("#id_factura_venta").val() == "") {
            if ($("#valor_cambio").val() > 0) {
                $("#valor_cambio").val("");
                cambio =
                        parseFloat($("#valor_recibo").val()) - parseFloat($("#tot").val());
                $.ajax({
                    type: "POST",
                    url: "guardar_cambio.php",
                    data:
                            "valor_recibo=" +
                            $("#valor_recibo").val() +
                            "&valor_cambio=" +
                            cambio,
                    success: function (data) {
                        var val = data;
                        if (val != 0) {
                            alertify.alert("Cambio Guardado");
                            $("#valor_cambioid").dialog("close");
                        }
                    },
                });
            } else {
                false;
            }
        } else {
            alertify.alert("Error... Ingrese la factura");
        }
    } else {
        $("#valor_recibo").val($("#totx").val());
        alertify.error("Debe ingresar un Valor Recibido");
    }
}

function agregar_punto_venta() {
    $("#tab_3").dialog(dialogo10);
    $("#codigo_barras").attr("disabled", false);
    $("#codigo").attr("disabled", false);
    $("#producto").attr("disabled", false);
    $("#cantidad").attr("disabled", false);
    var punto = document.getElementById("punto_ventaini").selectedIndex;
    if (punto != 0) {
        $.ajax({
            type: "POST",
            url: "guardar_punto_venta.php",
            data: "id=" + punto,
            success: function (data) {
                var val = data;
                if (val == 1) {
                    alertify.alert(
                            "Los datos se han Modificado Correctamente",
                            function () { }
                    );
                } else {
                    alertify.alert("Error en el proceso, revise los datos por favor");
                }
            },
        });
    } else {
        alertify.alert("Seleccione Punto de Venta");
    }
    $("#codigo_barras").focus();
    numeroGuia();
}

function numeroGuia() {
    if ($("#num_oculto_guia").val() == "") {
        $("#num_serie_guia").val("");
    } else {
        var str = $("#num_oculto_guia").val();
        var res = parseInt(str.substr(4, 16));
        res = res + 1;
        $("#num_serie_guia").val(res);
        var a = autocompletar_guia(res);
        var validado = a + "" + res;
        $("#num_serie_guia").val(validado);
    }
}

function autocompletar_guia() {
    var temp = "";
    var serie = $("#num_serie_guia").val();
    for (var i = serie.length; i < 9; i++) {
        temp = temp + "0";
    }
    return temp;
}
function guardar_factura() {
    var mayor_stock = 1;
    var fil = jQuery("#list").jqGrid("getRowData");
    var arreglo = new Array();
    for (var i = 0; i < fil.length; i++) {
        var datos1 = fil[i];
        var promesa = $.ajax({
            dataType: "json",
            url: "obtener_stock_producto.php",
            method: "GET",
            data: {id: datos1["cod_producto"]}
        }).then(
                function (data) {

                    var val = data;
                    var valores;

                    if (val != "") {
                        valores = val.split(",");
                        var item111 = 0;
                        fil.map((prod) => {
                            console.log("PROD_COD_P= VALORES1", Number(prod.cod_producto) == Number(valores[1]));
                            console.log("PROD_COD_PRODCUTO", Number(prod.cod_producto));
                            console.log("VALORES 1", Number(valores[1]));
                            if (Number(prod.cod_producto) == Number(valores[1])) {
                                item111 = prod.cantidad;
                                console.log("GUARDAR INVENTARIO SI", valores[2]);
                                if (valores[2] == 'Si') {

                                    console.log("MAYOR", Number(item111) > Number(valores[0]));
                                    if (Number(item111) > Number(valores[0])) {
                                        console.log("ES > QUE");
                                        mayor_stock = 0;
                                        $("#btnGuardar").attr("disabled", false);
                                        $("#list").jqGrid('editCell', prod.id_list, 5, true);
                                        alertify.error("Error.. Fuera de Stock cantidad disponible: " + Number(valores[0]) + "  " + " " + prod.detalle);
                                    } else {
                                        console.log("ES < QUE");
                                        mayor_stock = 1;
                                    }

                                } else {
                                    console.log("INVENTARIO NO");
                                    mayor_stock = 1;
                                }




                            }
                        });
                    }
                    return mayor_stock;
                }
        )
        arreglo.push(promesa);
    }
    Promise.all(arreglo).then(function (data) {
        if (data.some(e => e == 0)) {
            return;
        }
        setTimeout(() => {
            guardar_factura1();
        }, 100);
    })
}
function guardar_factura1() {

    if ($("#ruc_ci").val() == "9999999999999") {
        $("#id_cliente").val("1");
    }

    if (loadingFactura) {
        return;
    }
    if (document.getElementById("retencionF2Sguia").checked) {//si guia de remision
        console.log("si con guia");

        if ($("#num_serie_guia").val() == "") {
            $("#num_serie_guia").focus();
            alertify.alert("Debe ingresar el secuencial de la Guia de Remision");
        } else {

            if ($("#formaspago").val() == "otros" && $("#validar_guardar_grid").val() == "") {
                alertify.error("Ingrese Valor ");
                $("#valor_formas").focus();
            } else {
                if ($("#formaspago").val() == "otros" && $("#valor_factura_saldo").val() != "0.00") {
                    alertify.error("Ingrese Valor ");
                    $("#valor_formas").focus();
                } else {
                    $("#valor_cambioid").dialog("close");
                    var tam = jQuery("#list").jqGrid("getRowData");
                    if ($("#num_factura").val() == "") {
                        $("#num_factura").focus();
                        alertify.error("Ingrese nùmero de la factura");
                    } else {
                        if ($("#cancelacion").val() == "") {
                            $("#cancelacion").focus();
                            alertify.alert("Seleccione Fecha de Emisión");
                        } else {
                            var num_factu = $("#num_factura").val();
                            let tipo = $("#tipo_venta").val();
                            procesarFacturaUI();
                            $.ajax({
                                type: "POST",
                                url: "comparar_num_venta.php",
                                data: "num_fac=" + num_factu + "&tipo_venta=" + tipo,
                                success: function (data) {
                                    var val = data;
                                    console.log("::" + val);
                                    val = val.split("-");
                                    if (val[0] != 0) {
                                        pararProcesarFacturaUI();
                                        $("#num_factura").val("");
                                        var res1 = parseInt(val[0].substr(4, 16));
                                        res1 = res1 + 1;
                                        //FACTURA VENTA
                                        var res3 = parseInt(val[1]);
                                        res3 = res3 + 1;
                                        //nota venta
                                        var res2 = parseInt(val[1]);
                                        res2 = res2 + 1;
                                        alertify.success("Se Asignó un nuevo num de factura" + res1);
                                        $("#num_factura").val(res1);
                                        //nota venta
                                        var filas = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
                                        if ($("#tipo_venta").val() == "FACTURA") {
                                            $("#comprobante").val(res3);
                                            for (var i = 0; i < filas.length; i++) {
                                                var id = filas[i];
                                                var id_mix = id["id_f_v_mix"];
                                                console.log("f2::" + id_mix);
                                                jQuery("#listPagoreten_mixto").jqGrid("setRowData", id_mix, {
                                                    id_factura_venta: res2,
                                                });
                                            }
                                        } else {
                                            $("#comprobante_nota").val(res2);
                                            for (var i = 0; i < filas.length; i++) {
                                                var id = filas[i];
                                                var id_mix = id["id_f_v_mix"];
                                                console.log("f1::" + id_mix);
                                                jQuery("#listPagoreten_mixto").jqGrid("setRowData", id_mix, {
                                                    id_factura_venta: res2,
                                                });
                                            }
                                        }
                                        var a1 = autocompletar(res1);
                                        var validado = a1 + "" + res1;
                                        $("#num_factura").val(validado);

                                    } else {
                                        if ($("#ruc_ci").val() == "") {
                                            pararProcesarFacturaUI();

                                            var a = autocompletar($("#num_factura").val());
                                            $("#num_factura").val(a + "" + $("#num_factura").val());

                                            $("#ruc_ci").focus();
                                            alertify.error("Indique un cliente");
                                        } else {
                                            if ($("#nombre_cliente").val() == "") {
                                                pararProcesarFacturaUI();

                                                $("#nombre_cliente").focus();
                                                alertify.error("Nombres del cliente");
                                            } else {
                                                if ($("#direccion_cliente").val() == "") {
                                                    pararProcesarFacturaUI();

                                                    $("#direccion_cliente").focus();
                                                    alertify.error("Ingrese la Direcciòn");
                                                } else {
                                                    if ($("#tipo_precio").val() == "") {
                                                        pararProcesarFacturaUI();

                                                        $("#tipo_precio").focus();
                                                        alertify.alert("Seleccione un tipo de precio");
                                                    } else {
                                                        if ($("#cancelacion").val() == "") {
                                                            pararProcesarFacturaUI();

                                                            $("#cancelacion").focus();
                                                            alertify.alert("Seleccione Fecha de Emisión");
                                                        } else {
                                                            if (tam.length == 0) {
                                                                pararProcesarFacturaUI();

                                                                $("#codigo_barras").focus();
                                                                alertify.error(
                                                                        "Error... Ingrese productos a la factura"
                                                                        );
                                                            } else {
                                                                $("#btnGuardar").attr("disabled", true);
                                                                var v1 = new Array();
                                                                var v2 = new Array();
                                                                var v3 = new Array();
                                                                var v4 = new Array();
                                                                var v5 = new Array();
                                                                var v6 = new Array();
                                                                var v7 = new Array();
                                                                var v8 = new Array();
                                                                var v9 = new Array();
                                                                var v10 = new Array();
                                                                var string_v1 = "";
                                                                var string_v2 = "";
                                                                var string_v3 = "";
                                                                var string_v4 = "";
                                                                var string_v5 = "";
                                                                var string_v6 = "";
                                                                var string_v8 = "";
                                                                var string_v9 = "";
                                                                var string_v10 = "";
                                                                var valor2 = "";
                                                                var valor7 = "";
                                                                var fil = jQuery("#list").jqGrid("getRowData");
                                                                for (var i = 0; i < fil.length; i++) {
                                                                    var datos = fil[i];
                                                                    v1[i] = datos["cod_producto"];
                                                                    v2[i] = datos["cantidad"];
                                                                    v3[i] = datos["precio_u"];
                                                                    v4[i] = datos["descuento"];
                                                                    v5[i] = datos["total"];
                                                                    v6[i] = datos["pendiente"];
                                                                    v7[i] = datos["precio_ux"];
                                                                    v8[i] = datos["cantidad_unidad"];
                                                                    v9[i] = datos["unidad_medida"];
                                                                    v10[i] = datos["detalle_producto"];
                                                                    var cadena2 = v2[i];
                                                                    var result2 = cadena2.substr(7, 4);
                                                                    console.log("v2" + v2[i]);
                                                                    if (result2 == 'type' || v2[i] == "") {
                                                                        valor2 = true;
                                                                    }

                                                                    string_v1 = string_v1 + "|" + v1[i];
                                                                    string_v2 = string_v2 + "|" + v2[i];
                                                                    string_v3 = string_v3 + "|" + v3[i];
                                                                    string_v4 = string_v4 + "|" + v4[i];
                                                                    string_v5 = string_v5 + "|" + v5[i];
                                                                    string_v6 = string_v6 + "|" + v6[i];
                                                                    string_v8 = string_v8 + "|" + v8[i];
                                                                    string_v9 = string_v9 + "|" + v9[i];
                                                                    string_v10 = string_v10 + "|" + encodeURIComponent(v9[i]);
                                                                    var cadena7 = v7[i];
                                                                    var result7 = cadena7.substr(7, 4);
                                                                    console.log("v7" + v7[i]);
                                                                    if (result7 == 'type' || v7[i] == "") {
                                                                        valor7 = true;
                                                                    }
                                                                }

                                                                var a = autocompletar($("#num_factura").val());
                                                                var num_serie = $("#buscar_pv").val();
                                                                //TODO borrar comentado
                                                                /* if ($("#punto_ventaid").val() == 1) {
                                                                 var num_serie = $("#buscar_pv").val();
                                                                 }
                                                                 if ($("#punto_ventaid").val() == 2) {
                                                                 var num_serie = $("#buscar_pv").val();
                                                                 }
                                                                 if ($("#punto_ventaid").val() == 3) {
                                                                 var num_serie = $("#buscar_pv").val();
                                                                 }
                                                                 if ($("#punto_ventaid").val() == 4) {
                                                                 var num_serie = $("#buscar_pv").val();
                                                                 }
                                                                 if ($("#punto_ventaid").val() == 5) {
                                                                 var num_serie = $("#buscar_pv").val();
                                                                 } */
                                                                var seriee = a + "" + $("#num_factura").val();
                                                                if (document.getElementById("retencionF2Sguia").checked) {
                                                                    var guia = autocompletar_guia($("#num_serie_guia").val());
                                                                    //TODO borrar comentado
                                                                    /*  if ($("#punto_ventaid").val() == 1) {
                                                                     var seriee_guia = $("#buscar_pv").val();
                                                                     }
                                                                     if ($("#punto_ventaid").val() == 2) {
                                                                     var seriee_guia = $("#buscar_pv").val();
                                                                     } */
                                                                    var seriee_guia = $("#buscar_pv").val();
                                                                    seriee_guia = seriee_guia + "-" + $("#num_serie_guia").val();
                                                                } else {
                                                                    var seriee_guia = "000000000";
                                                                }
                                                                var cambio =
                                                                        parseFloat($("#valor_recibo").val()) -
                                                                        parseFloat($("#tot").val());
                                                                var repe = 0;
                                                                var filas = jQuery("#listPagoreten_mixto").jqGrid(
                                                                        "getRowData"
                                                                        );
                                                                for (var i = 0; i < filas.length; i++) {
                                                                    var id = filas[i];
                                                                    if (id["forma_pago_mixto"] == "Credito") {
                                                                        repe = 1;
                                                                    }
                                                                }
                                                                if (valor2 == true) {
                                                                    pararProcesarFacturaUI();
                                                                    alertify.error('Error...debe hacer enter en cantidad ');
                                                                    $("#btnGuardar").attr("disabled", false);
                                                                } else {
                                                                    if (valor7 == true) {
                                                                        pararProcesarFacturaUI();
                                                                        alertify.error('Error... debe hacer enter en precio unitario ');
                                                                        $("#btnGuardar").attr("disabled", false);
                                                                    } else {
                                                                        //              
                                                                        //                                                        
                                                                        $("#fecha_dias").val($("#fecha_actual").val());
                                                                        $("#fecha_dias").val($("#fecha_actual").val());
                                                                        if (repe == 1 && $("#fecha_dias").val() == "") {
                                                                            //
                                                                            pararProcesarFacturaUI();
                                                                            $("#btnGuardar").attr("disabled", false);
                                                                            alertify.error(
                                                                                    "DEBE SELECCIONAR FECHA DE VENCIMIENTO"
                                                                                    );
                                                                            $("#validar_guardar").val("");
                                                                        } else {
                                                                            guardar_serie();
                                                                            funcion_descuento_factura(false);
                                                                            $.ajax({
                                                                                type: "POST",
                                                                                url: "guardar_factura_venta.php",
                                                                                data:
                                                                                        "id_fac=" +
                                                                                        $("#id_factura_venta").val() +
                                                                                        "&id_cliente=" +
                                                                                        $("#id_cliente").val() +
                                                                                        "&comprobante=" +
                                                                                        $("#comprobante").val() +
                                                                                        "&num_factura=" +
                                                                                        seriee +
                                                                                        "&fecha_actual=" +
                                                                                        $("#fecha_actual").val() +
                                                                                        "&hora_actual=" +
                                                                                        $("#hora_actual").val() +
                                                                                        "&proforma=" +
                                                                                        $("#proforma").val() +
                                                                                        "&cancelacion=" +
                                                                                        $("#cancelacion").val() +
                                                                                        "&tipo_precio=" +
                                                                                        $("#tipo_precio").val() +
                                                                                        "&formaspago=" +
                                                                                        $("#formaspago").val() +
                                                                                        "&adelanto=" +
                                                                                        $("#adelanto").val() +
                                                                                        "&meses=" +
                                                                                        $("#meses").val() +
                                                                                        "&autorizacion=" +
                                                                                        $("#autorizacion").val() +
                                                                                        "&fecha_auto=" +
                                                                                        $("#fecha_auto").val() +
                                                                                        "&fecha_caducidad=" +
                                                                                        $("#fecha_caducidad").val() +
                                                                                        "&tarifa0=" +
                                                                                        //$("#total_p").val() +
                                                                                        //valt0 +
                                                                                        enviartarifa0 +
                                                                                        "&tarifa12=" +
                                                                                        //$("#total_p2").val() +
                                                                                        //valt12 +
                                                                                        enviartarifa12 +
                                                                                        "&iva=" +
                                                                                        $("#iva").val() +
                                                                                        "&desc=" +
                                                                                        (envdescprod + envdescfact) +
                                                                                        //$("#descxax").val() +
                                                                                        "&tot=" +
                                                                                        $("#tot").val() +
                                                                                        "&ruc_ci=" +
                                                                                        $("#ruc_ci").val() +
                                                                                        "&nombre_cliente=" +
                                                                                        $("#nombre_cliente").val() +
                                                                                        "&direccion_cliente=" +
                                                                                        $("#direccion_cliente").val() +
                                                                                        "&telefono_cliente=" +
                                                                                        $("#telefono_cliente").val() +
                                                                                        "&correo=" +
                                                                                        $("#correo").val().toLowerCase() +
                                                                                        "&campo1=" +
                                                                                        string_v1 +
                                                                                        "&campo2=" +
                                                                                        string_v2 +
                                                                                        "&campo3=" +
                                                                                        string_v3 +
                                                                                        "&campo4=" +
                                                                                        string_v4 +
                                                                                        "&campo5=" +
                                                                                        string_v5 +
                                                                                        "&campo6=" +
                                                                                        string_v6 +
                                                                                        "&campo8=" +
                                                                                        string_v8 +
                                                                                        "&campo9=" +
                                                                                        string_v9 +
                                                                                        "&campo10=" +
                                                                                        string_v10 +
                                                                                        "&tipo_venta=" +
                                                                                        $("#tipo_venta").val() +
                                                                                        "&tarjetas=" +
                                                                                        $("#tarjetas").val() +
                                                                                        "&valor_recibo=" +
                                                                                        $("#valor_recibo").val() +
                                                                                        "&valor_cambio=" +
                                                                                        cambio +
                                                                                        "&id_vendedor=" +
                                                                                        $("#vendedor").val() +
                                                                                        "&fecha_dias=" +
                                                                                        $("#fecha_dias").val() +
                                                                                        "&num_guia_remision=" +
                                                                                        seriee_guia +
                                                                                        "&marca_vehiculo=" +
                                                                                        $("#num_liquidacion").val() +
                                                                                        "&placa_fac=" +
                                                                                        $("#placa_fac").val() +
                                                                                        "&propiedad=" +
                                                                                        $("#propiedad").val() +
                                                                                        "&num_reclamo=" +
                                                                                        $("#num_reclamo").val() +
                                                                                        "&num_chasis=" +
                                                                                        $("#num_chasis").val() +
                                                                                        "&formas=" +
                                                                                        $("#formas").val() +
                                                                                        "&num_tarjeta=" +
                                                                                        $("#num_tarjeta").val() +
                                                                                        "&reservacion=" +
                                                                                        $("#reservacion").val() +
                                                                                        "&num_serie=" +
                                                                                        num_serie +
                                                                                        "&id_proforma_tecnico=" +
                                                                                        idProformaTecnico +
                                                                                        "&cuenta_cheque=" +
                                                                                        $("#idCuenta").val() +
                                                                                        "&descprod=" + envdescprod +
                                                                                        "&descfact=" + envdescfact +
                                                                                        "&id_centro_costo=" +
                                                                                        $("#sel_centro_costo").val() +
                                                                                        "&id_tdocu=" +
                                                                                        $("#id_tdocu").val(),
                                                                                dataType: "json",
                                                                                success: function (data) {
                                                                                    pararProcesarFacturaUI();
                                                                                    var val = data;
                                                                                    if ($("#tipo_venta").val() == "FACTURA") {
                                                                                        if ($("#formaspago").val() == "otros" || $("#formaspago").val() == "Cheque" || $("#formaspago").val() == "TCredito") {
                                                                                            $("#contado_form").prop("selected", true);

                                                                                            if (Number(data.id) > 0) {
                                                                                                if (autorizarFacAuto == 1) {
                                                                                                    autorizarFactura(data.id, data.clave);
                                                                                                }
                                                                                                var myWindow = window.open(formatoFactura + "?hoja=A5&id=" + data.id, "_blank");
                                                                                                myWindow.focus();
                                                                                                myWindow.print();
                                                                                                alertify.alert("FACTURA GUARDADA");
                                                                                                alertify.confirm("¿Desea ingresar retenciones?",
                                                                                                        function (e) {
                                                                                                            if (e) {
                                                                                                                $("#id_factura_venta").val(data.id);
                                                                                                                $("#id_factura_venta").trigger("change");
                                                                                                                $('.nav-tabs a[href="#tab_2"]').tab("show");
                                                                                                                $("#retencionF2").focus();
                                                                                                            } else {
                                                                                                                guardar_guia_remision();
                                                                                                            }
                                                                                                            //}
                                                                                                        } //,
                                                                                                );
                                                                                            } else {
                                                                                                alertify.error("Error.....OCURRIO UN ERROR AL GUARDAR LA FACTURA ");
                                                                                                $("#btnGuardar").attr("disabled", false);
                                                                                            }
                                                                                        } else {

                                                                                            if (Number(data.id) > 0) {
                                                                                                if (autorizarFacAuto == 1) {
                                                                                                    autorizarFactura(data.id, data.clave);
                                                                                                }
                                                                                                var myWindow = window.open(formatoFactura + "?hoja=A5&id=" + data.id, "_blank");
                                                                                                myWindow.focus();
                                                                                                myWindow.print();
                                                                                                setTimeout(guardar_guia_remision, 500);
                                                                                                alertify.alert("FACTURA GUARDADA", function () {
                                                                                                    location.reload()
                                                                                                });
                                                                                            } else {
                                                                                                alertify.error("Error.....OCURRIO UN ERROR AL GUARDAR LA FACTURA ");
                                                                                                $("#btnGuardar").attr("disabled", false);
                                                                                            }
                                                                                        }
                                                                                        insertar_cliente();
                                                                                    } else {
                                                                                        insertar_cliente();
                                                                                        if ($("#tipo_venta").val() == "NOTA") {
                                                                                            if (data.estado == 22) {
                                                                                                alertify.alert("Nota Venta Guardada Correctamente",
                                                                                                        function () {
                                                                                                            var myWindow = window.open(formatoNotaVenta + "?hoja=A2&id=" + data.id, "_blank");
                                                                                                            myWindow.focus();
                                                                                                            myWindow.print();
                                                                                                            location.reload();
                                                                                                        }
                                                                                                );
                                                                                            } else {
                                                                                                if (data.estado == "60") {
                                                                                                    alertify.error("Error.....OCURRIO UN ERROR DE CONEXIÓN ");
                                                                                                    $("#btnGuardar").attr("disabled", false);
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                    idProformaTecnico = 0;
                                                                                },
                                                                            })
                                                                                    .fail(function () {
                                                                                        pararProcesarFacturaUI();
                                                                                    })
                                                                                    .always(function () {
                                                                                        pararProcesarFacturaUI();
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
                            })
                                    .fail(function () {
                                        pararProcesarFacturaUI();
                                    });

                        }
                    }
                }
            }
        }
    } else if (document.getElementById("retencionF1Sguia").checked) { //no guia

        if ($("#formaspago").val() == "otros" && $("#validar_guardar_grid").val() == "") {
            alertify.error("Ingrese Valor ");
            $("#valor_formas").focus();
        } else {
            if ($("#formaspago").val() == "otros" && $("#valor_factura_saldo").val() != "0.00") {
                alertify.error("Ingrese Valor ");
                $("#valor_formas").focus();
            } else {
                $("#valor_cambioid").dialog("close");
                var tam = jQuery("#list").jqGrid("getRowData");
                if ($("#num_factura").val() == "") {
                    $("#num_factura").focus();
                    alertify.error("Ingrese nùmero de la factura");
                } else {
                    if ($("#cancelacion").val() == "") {
                        $("#cancelacion").focus();
                        alertify.alert("Seleccione Fecha de Emisión");
                    } else {
                        var num_factu = $("#num_factura").val();
                        let tipo = $("#tipo_venta").val();
                        procesarFacturaUI();

                        $.ajax({
                            type: "POST",
                            url: "comparar_num_venta.php",
                            data: "num_fac=" + num_factu + "&tipo_venta=" + tipo,
                            success: function (data) {
                                var val = data;
                                console.log("::" + val);
                                val = val.split("-");
                                if (val[0] != 0) {
                                    pararProcesarFacturaUI();

                                    $("#num_factura").val("");
                                    var res1 = parseInt(val[0].substr(4, 16));
                                    res1 = res1 + 1;
                                    //FACTURA VENTA
                                    var res3 = parseInt(val[1]);
                                    res3 = res3 + 1;
                                    //nota venta
                                    var res2 = parseInt(val[1]);
                                    res2 = res2 + 1;
                                    alertify.success("Se Asignó un nuevo num de factura" + res1);
                                    $("#num_factura").val(res1);
                                    //nota venta
                                    var filas = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
                                    if ($("#tipo_venta").val() == "FACTURA") {
                                        $("#comprobante").val(res3);
                                        for (var i = 0; i < filas.length; i++) {
                                            var id = filas[i];
                                            var id_mix = id["id_f_v_mix"];
                                            console.log("f2::" + id_mix);
                                            jQuery("#listPagoreten_mixto").jqGrid("setRowData", id_mix, {
                                                id_factura_venta: res2,
                                            });
                                        }
                                    } else {
                                        $("#comprobante_nota").val(res2);
                                        for (var i = 0; i < filas.length; i++) {
                                            var id = filas[i];
                                            var id_mix = id["id_f_v_mix"];
                                            console.log("f1::" + id_mix);
                                            jQuery("#listPagoreten_mixto").jqGrid("setRowData", id_mix, {
                                                id_factura_venta: res2,
                                            });
                                        }
                                    }
                                    var a1 = autocompletar(res1);
                                    var validado = a1 + "" + res1;
                                    $("#num_factura").val(validado);

                                } else {
                                    if ($("#ruc_ci").val() == "") {
                                        pararProcesarFacturaUI();

                                        var a = autocompletar($("#num_factura").val());
                                        $("#num_factura").val(a + "" + $("#num_factura").val());

                                        $("#ruc_ci").focus();
                                        alertify.error("Indique un cliente");
                                    } else {
                                        if ($("#nombre_cliente").val() == "") {
                                            pararProcesarFacturaUI();

                                            $("#nombre_cliente").focus();
                                            alertify.error("Nombres del cliente");
                                        } else {
                                            if ($("#direccion_cliente").val() == "") {
                                                pararProcesarFacturaUI();

                                                $("#direccion_cliente").focus();
                                                alertify.error("Ingrese la Direcciòn");
                                            } else {
                                                if ($("#tipo_precio").val() == "") {
                                                    pararProcesarFacturaUI();

                                                    $("#tipo_precio").focus();
                                                    alertify.alert("Seleccione un tipo de precio");
                                                } else {
                                                    if ($("#cancelacion").val() == "") {
                                                        pararProcesarFacturaUI();

                                                        $("#cancelacion").focus();
                                                        alertify.alert("Seleccione Fecha de Emisión");
                                                    } else {
                                                        if (tam.length == 0) {
                                                            pararProcesarFacturaUI();

                                                            $("#codigo_barras").focus();
                                                            alertify.error(
                                                                    "Error... Ingrese productos a la factura"
                                                                    );
                                                        } else {
                                                            $("#btnGuardar").attr("disabled", true);
                                                            var v1 = new Array();
                                                            var v2 = new Array();
                                                            var v3 = new Array();
                                                            var v4 = new Array();
                                                            var v5 = new Array();
                                                            var v6 = new Array();
                                                            var v7 = new Array();
                                                            var v8 = new Array();
                                                            var v9 = new Array();
                                                            var v10 = new Array();
                                                            var string_v1 = "";
                                                            var string_v2 = "";
                                                            var string_v3 = "";
                                                            var string_v4 = "";
                                                            var string_v5 = "";
                                                            var string_v6 = "";
                                                            var string_v8 = "";
                                                            var string_v9 = "";
                                                            var string_v10 = "";
                                                            var valor2 = "";
                                                            var valor7 = "";
                                                            var fil = jQuery("#list").jqGrid("getRowData");
                                                            for (var i = 0; i < fil.length; i++) {
                                                                var datos = fil[i];
                                                                v1[i] = datos["cod_producto"];
                                                                v2[i] = datos["cantidad"];
                                                                v3[i] = datos["precio_u"];
                                                                v4[i] = datos["descuento"];
                                                                v5[i] = datos["total"];
                                                                v6[i] = datos["pendiente"];
                                                                v7[i] = datos["precio_ux"];
                                                                v8[i] = datos["cantidad_unidad"];
                                                                v9[i] = datos["unidad_medida"];
                                                                v10[i] = datos["detalle_producto"];
                                                                var cadena2 = v2[i];
                                                                var result2 = cadena2.substr(7, 4);
                                                                console.log("v2" + v2[i]);
                                                                if (result2 == 'type' || v2[i] == "") {
                                                                    valor2 = true;
                                                                }

                                                                string_v1 = string_v1 + "|" + v1[i];
                                                                string_v2 = string_v2 + "|" + v2[i];
                                                                string_v3 = string_v3 + "|" + v3[i];
                                                                string_v4 = string_v4 + "|" + v4[i];
                                                                string_v5 = string_v5 + "|" + v5[i];
                                                                string_v6 = string_v6 + "|" + v6[i];
                                                                string_v8 = string_v8 + "|" + v8[i];
                                                                string_v9 = string_v9 + "|" + v9[i];
                                                                string_v10 = string_v10 + "|" + encodeURIComponent(v10[i]);
                                                                var cadena7 = v7[i];
                                                                var result7 = cadena7.substr(7, 4);
                                                                console.log("v7" + v7[i]);
                                                                if (result7 == 'type' || v7[i] == "") {
                                                                    valor7 = true;
                                                                }
                                                            }

                                                            var a = autocompletar($("#num_factura").val());
                                                            var num_serie = $("#buscar_pv").val();
                                                            //TODO borrar comentado
                                                            /*  if ($("#punto_ventaid").val() == 1) {
                                                             var num_serie = $("#buscar_pv").val();
                                                             }
                                                             if ($("#punto_ventaid").val() == 2) {
                                                             var num_serie = $("#buscar_pv").val();
                                                             }
                                                             if ($("#punto_ventaid").val() == 3) {
                                                             var num_serie = $("#buscar_pv").val();
                                                             }
                                                             if ($("#punto_ventaid").val() == 4) {
                                                             var num_serie = $("#buscar_pv").val();
                                                             }
                                                             if ($("#punto_ventaid").val() == 5) {
                                                             var num_serie = $("#buscar_pv").val();
                                                             } */
                                                            var seriee = a + "" + $("#num_factura").val();
                                                            if (
                                                                    document.getElementById("retencionF2Sguia")
                                                                    .checked
                                                                    ) {
                                                                var guia = autocompletar_guia(
                                                                        $("#num_serie_guia").val()
                                                                        );
                                                                //TODO borrar comentado
                                                                /* if ($("#punto_ventaid").val() == 1) {
                                                                 var seriee_guia = $("#buscar_pv").val();
                                                                 }
                                                                 if ($("#punto_ventaid").val() == 2) {
                                                                 var seriee_guia = $("#buscar_pv").val();
                                                                 } */
                                                                var seriee_guia = $("#buscar_pv").val();
                                                                seriee_guia =
                                                                        seriee_guia + "-" + $("#num_serie_guia").val();
                                                            } else {
                                                                var seriee_guia = "000000000";
                                                            }
                                                            var cambio =
                                                                    parseFloat($("#valor_recibo").val()) -
                                                                    parseFloat($("#tot").val());
                                                            var repe = 0;
                                                            var filas = jQuery("#listPagoreten_mixto").jqGrid(
                                                                    "getRowData"
                                                                    );
                                                            for (var i = 0; i < filas.length; i++) {
                                                                var id = filas[i];
                                                                if (id["forma_pago_mixto"] == "Credito") {
                                                                    repe = 1;
                                                                }
                                                            }
                                                            if (valor2 == true) {
                                                                pararProcesarFacturaUI();

                                                                alertify.error('Error...debe hacer enter en cantidad ');
                                                                $("#btnGuardar").attr("disabled", false);
                                                            } else {
                                                                if (valor7 == true) {
                                                                    pararProcesarFacturaUI();

                                                                    alertify.error('Error... debe hacer enter en precio unitario ');
                                                                    $("#btnGuardar").attr("disabled", false);
                                                                } else {
                                                                    //              
                                                                    //                                                        
                                                                    //$("#fecha_dias").val($("#fecha_actual").val());
                                                                    //$("#fecha_dias").val($("#fecha_actual").val());
                                                                    if (repe == 1 && $("#fecha_dias").val() == "") {
                                                                        pararProcesarFacturaUI();

                                                                        //                                                            $("#btnGuardar").attr("disabled", false);
                                                                        alertify.error(
                                                                                "DEBE SELECCIONAR FECHA DE VENCIMIENTO"
                                                                                );
                                                                        $("#validar_guardar").val("");
                                                                    } else {
                                                                        guardar_serie(() => {
                                                                            funcion_descuento_factura(false);
                                                                            $.ajax({
                                                                                type: "POST",
                                                                                url: "guardar_factura_venta.php",
                                                                                data:
                                                                                        "id_fac=" +
                                                                                        $("#id_factura_venta").val() +
                                                                                        "&id_cliente=" +
                                                                                        $("#id_cliente").val() +
                                                                                        "&comprobante=" +
                                                                                        $("#comprobante").val() +
                                                                                        "&num_factura=" +
                                                                                        seriee +
                                                                                        "&fecha_actual=" +
                                                                                        $("#fecha_actual").val() +
                                                                                        "&hora_actual=" +
                                                                                        $("#hora_actual").val() +
                                                                                        "&proforma=" +
                                                                                        $("#proforma").val() +
                                                                                        "&cancelacion=" +
                                                                                        $("#cancelacion").val() +
                                                                                        "&tipo_precio=" +
                                                                                        $("#tipo_precio").val() +
                                                                                        "&formaspago=" +
                                                                                        $("#formaspago").val() +
                                                                                        "&adelanto=" +
                                                                                        $("#adelanto").val() +
                                                                                        "&meses=" +
                                                                                        $("#meses").val() +
                                                                                        "&autorizacion=" +
                                                                                        $("#autorizacion").val() +
                                                                                        "&fecha_auto=" +
                                                                                        $("#fecha_auto").val() +
                                                                                        "&fecha_caducidad=" +
                                                                                        $("#fecha_caducidad").val() +
                                                                                        "&tarifa0=" +
                                                                                        //$("#total_p").val() +
                                                                                        //valt0 +
                                                                                        enviartarifa0 +
                                                                                        "&tarifa12=" +
                                                                                        //$("#total_p2").val() +
                                                                                        //valt12 +
                                                                                        enviartarifa12 +
                                                                                        "&iva=" +
                                                                                        $("#iva").val() +
                                                                                        "&desc=" +
                                                                                        (envdescprod + envdescfact) +
                                                                                        //$("#descxax").val() +
                                                                                        "&tot=" +
                                                                                        $("#tot").val() +
                                                                                        "&ruc_ci=" +
                                                                                        $("#ruc_ci").val() +
                                                                                        "&nombre_cliente=" +
                                                                                        $("#nombre_cliente").val() +
                                                                                        "&direccion_cliente=" +
                                                                                        $("#direccion_cliente").val() +
                                                                                        "&telefono_cliente=" +
                                                                                        $("#telefono_cliente").val() +
                                                                                        "&correo=" +
                                                                                        $("#correo").val().toLowerCase() +
                                                                                        "&campo1=" +
                                                                                        string_v1 +
                                                                                        "&campo2=" +
                                                                                        string_v2 +
                                                                                        "&campo3=" +
                                                                                        string_v3 +
                                                                                        "&campo4=" +
                                                                                        string_v4 +
                                                                                        "&campo5=" +
                                                                                        string_v5 +
                                                                                        "&campo6=" +
                                                                                        string_v6 +
                                                                                        "&campo8=" +
                                                                                        string_v8 +
                                                                                        "&campo9=" +
                                                                                        string_v9 +
                                                                                        "&campo10=" +
                                                                                        string_v10 +
                                                                                        "&tipo_venta=" +
                                                                                        $("#tipo_venta").val() +
                                                                                        "&tarjetas=" +
                                                                                        $("#tarjetas").val() +
                                                                                        "&valor_recibo=" +
                                                                                        $("#valor_recibo").val() +
                                                                                        "&valor_cambio=" +
                                                                                        cambio +
                                                                                        "&id_vendedor=" +
                                                                                        $("#vendedor").val() +
                                                                                        "&fecha_dias=" +
                                                                                        $("#fecha_dias").val() +
                                                                                        "&num_guia_remision=" +
                                                                                        seriee_guia +
                                                                                        "&marca_vehiculo=" +
                                                                                        $("#num_liquidacion").val() +
                                                                                        "&placa_fac=" +
                                                                                        $("#placa_fac").val() +
                                                                                        "&propiedad=" +
                                                                                        $("#propiedad").val() +
                                                                                        "&num_reclamo=" +
                                                                                        $("#num_reclamo").val() +
                                                                                        "&num_chasis=" +
                                                                                        $("#num_chasis").val() +
                                                                                        "&formas=" +
                                                                                        $("#formas").val() +
                                                                                        "&num_tarjeta=" +
                                                                                        $("#num_tarjeta").val() +
                                                                                        "&reservacion=" +
                                                                                        $("#reservacion").val() +
                                                                                        "&num_serie=" +
                                                                                        num_serie +
                                                                                        "&id_proforma_tecnico=" +
                                                                                        idProformaTecnico +
                                                                                        "&cuenta_cheque=" +
                                                                                        $("#idCuenta").val() +
                                                                                        "&id_centro_costo=" +
                                                                                        $("#sel_centro_costo").val() +
                                                                                        "&descprod=" + envdescprod +
                                                                                        "&descfact=" + envdescfact +
                                                                                        "&id_tdocu=" +
                                                                                        $("#id_tdocu").val(),
                                                                                dataType: "json",
                                                                                success: function (data) {
                                                                                    pararProcesarFacturaUI();
                                                                                    var val = data;
                                                                                    if ($("#tipo_venta").val() == "FACTURA") {
                                                                                        if ($("#formaspago").val() == "otros" || $("#formaspago").val() == "Cheque" || $("#formaspago").val() == "TCredito") {
                                                                                            $("#contado_form").prop("selected", true);

                                                                                            if (Number(data.id) > 0) {
                                                                                                if (autorizarFacAuto == 1) {
                                                                                                    autorizarFactura(data.id, data.clave);
                                                                                                }
                                                                                                var myWindow = window.open(formatoFactura + "?hoja=A5&id=" + data.id, "_blank");
                                                                                                myWindow.focus();
                                                                                                myWindow.print();
                                                                                                alertify.alert("FACTURA GUARDADA");
                                                                                                //                                                                                                alertify.confirm("¿Desea ingresar retenciones2?",
                                                                                                //                                                                                                        function (e) {
                                                                                                //                                                                                                            if (e) {
                                                                                                //                                                                                                                $("#id_factura_venta").val(data.id);
                                                                                                //                                                                                                                $('.nav-tabs a[href="#tab_2"]').tab("show");
                                                                                                //                                                                                                                $("#retencionF2").focus();
                                                                                                //                                                                                                                //$("#tab_1").removeClass('active');
                                                                                                //                                                                                                                //$("#tab_2").addClass('active');
                                                                                                //                                                                                                            } else {
                                                                                                //                                                                                                                location.reload();
                                                                                                //                                                                                                            }
                                                                                                //                                                                                                            //}
                                                                                                //                                                                                                        } //,
                                                                                                //                                                                                                );

                                                                                                location.reload();
                                                                                            } else {
                                                                                                alertify.error("Error.....OCURRIO UN ERROR AL GUARDAR LA FACTURA ");
                                                                                                $("#btnGuardar").attr("disabled", false);
                                                                                            }


                                                                                            /* if (data.id != 0) {
                                                                                             if (data.estado == "60") {
                                                                                             alertify.error("Error.....OCURRIO UN ERROR AL GUARDAR LA FACTURA ");
                                                                                             $("#btnGuardar").attr("disabled", false);
                                                                                             return;
                                                                                             }
                                                                                             var myWindow = window.open(formatoFactura + "?hoja=A5&id=" + data.id, "_blank");
                                                                                             myWindow.focus();
                                                                                             myWindow.print();
                                                                                             if (data.estado == 2) {
                                                                                             alertify.alert("AUTORIZADO",
                                                                                             function (e) {
                                                                                             reenviar(data.id);
                                                                                             }
                                                                                             );
                                                                                             } else {
                                                                                             alertify.alert("Factura Guardada, NO AUTORIZADA");
                                                                                             }
                                                                                             
                                                                                             
                                                                                             alertify.confirm("¿Desea ingresar retenciones?",
                                                                                             function (e) {
                                                                                             if (e) {
                                                                                             $("#id_factura_venta").val(data.id);
                                                                                             $('.nav-tabs a[href="#tab_2"]').tab("show");
                                                                                             $("#retencionF2").focus();
                                                                                             //$("#tab_1").removeClass('active');
                                                                                             //$("#tab_2").addClass('active');
                                                                                             } else {
                                                                                             location.reload();
                                                                                             }
                                                                                             //}
                                                                                             } //,
                                                                                             );
                                                                                             } */
                                                                                        } else {
                                                                                            if (Number(data.id) > 0) {
                                                                                                if (autorizarFacAuto == 1) {
                                                                                                    autorizarFactura(data.id, data.clave);
                                                                                                }
                                                                                                var myWindow = window.open(formatoFactura + "?hoja=A5&id=" + data.id, "_blank");
                                                                                                myWindow.focus();
                                                                                                myWindow.print();
                                                                                                alertify.alert("FACTURA GUARDADA", function () {
                                                                                                    location.reload()
                                                                                                });
                                                                                            } else {
                                                                                                alertify.error("Error.....OCURRIO UN ERROR AL GUARDAR LA FACTURA ");
                                                                                                $("#btnGuardar").attr("disabled", false);
                                                                                            }

                                                                                        }
                                                                                        insertar_cliente();
                                                                                    } else {
                                                                                        insertar_cliente();
                                                                                        if ($("#tipo_venta").val() == "NOTA") {
                                                                                            if (data.estado == 22) {
                                                                                                alertify.alert(
                                                                                                        "Nota Venta Guardada Correctamente",
                                                                                                        function () {
                                                                                                            var myWindow = window.open(formatoNotaVenta + "?hoja=A2&id=" + data.id, "_blank");
                                                                                                            myWindow.focus();
                                                                                                            myWindow.print();
                                                                                                            location.reload();
                                                                                                        }
                                                                                                );
                                                                                            } else {
                                                                                                if (data.estado == "60") {
                                                                                                    alertify.error("Error.....OCURRIO UN ERROR AL GUARDAR LA FACTURA ");
                                                                                                    $("#btnGuardar").attr("disabled", false);
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                    idProformaTecnico = 0;
                                                                                },
                                                                            })
                                                                                    .fail(function () {
                                                                                        pararProcesarFacturaUI();
                                                                                    })
                                                                                    .always(function () {
                                                                                        pararProcesarFacturaUI();
                                                                                    });



                                                                        });

                                                                    }//desde_aqui
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            },
                        })
                                .fail(function () {
                                    pararProcesarFacturaUI();
                                });
                    }
                }
            }
        }

    }

}
//
//function guardar_factura_temporal() {
//    var tam = jQuery("#list").jqGrid("getRowData");
//
//    if ($("#num_factura").val() == "") {
//        $("#num_factura").focus();
//        alertify.error("Ingrese número de la factura");
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
//                    alertify.error("Error... La factura ya existe, favor verificar el número que corresponda");
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
//                                            data: "id_cliente=" + $("#id_cliente").val() + "&comprobante=" + $("#comprobante").val() + "&num_factura=" + seriee + "&fecha_actual=" + $("#fecha_actual").val() + "&hora_actual=" + $("#hora_actual").val() + "&proforma=" + $("#proforma").val() + "&cancelacion=" + $("#cancelacion").val() + "&tipo_precio=" + $("#tipo_precio").val() + "&formas=" + $("#formas").val() + "&adelanto=" + $("#adelanto").val() + "&meses=" + $("#meses").val() + "&autorizacion=" + $("#autorizacion").val()+ "&fecha_auto=" + $("#fecha_auto").val()+ "&fecha_caducidad=" + $("#fecha_caducidad").val() + "&tarifa0=" + $("#total_p").val() + "&tarifa12=" + $("#total_p2").val() + "&iva=" + $("#iva").val() + "&desc=" + $("#desc").val() + "&tot=" + $("#tot").val() + "&ruc_ci=" + $("#ruc_ci").val() + "&nombre_cliente=" + $("#nombre_cliente").val() + "&direccion_cliente=" + $("#direccion_cliente").val() + "&telefono_cliente=" + $("#telefono_cliente").val() + "&correo=" + $("#correo").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5+ "&campo6=" + string_v6+ "&tipo_venta=" + $("#tipo_venta").val() + "&tarjetas=" + $("#tarjetas").val(),
//                                            success: function(data) {
//                                                var val = data;
//                                                if($("#tipo_venta").val() == "FACTURA") {
//                                                   if (val != 0) {
//
//                                                        alertify.alert("Factura Temporal Guardada Correctamente", function(){
//                                                            window.open("../../reportes/factura_venta.php?hoja=A4&id="+val,'_blank');
//                                                            location.reload();
//                                                        });
//                                                        /*alertify.confirm("¿Desea ingresar retenciones?",
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
        var num_factu = "001" + "-" + "001" + "-" + $("#num_factura").val();
        $.ajax({
            type: "POST",
            url: "comparar_num_venta.php",
            data: "num_fac=" + num_factu,
            success: function (data) {
                var val = data;
                if (val != 0) {
                    $("#num_factura").val("");
                    $("#num_factura").focus();
                    //                    alertify.error("Error... La factura ya existe, favor verificar el número que corresponda" );
                    var res1 = parseInt(val.substr(4, 16));
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
                            if ($("#tipo_precio").val() == "") {
                                $("#tipo_precio").focus();
                                alertify.alert("Seleccione un tipo de precio");
                            } else {
                                if (tam.length == 0) {
                                    $("#codigo_barras").focus();
                                    alertify.error("Error... Ingrese productos a la factura");
                                } else {
                                    if (
                                            $("#formaspago").val() == "Credito" &&
                                            $("#meses").val() == ""
                                            ) {
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
                                            v1[i] = datos["cod_producto"];
                                            v2[i] = datos["cantidad"];
                                            v3[i] = datos["precio_u"];
                                            v4[i] = datos["descuento"];
                                            v5[i] = datos["total"];
                                            v6[i] = datos["pendiente"];
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
                                        var seriee =
                                                "001" +
                                                "-" +
                                                "001" +
                                                "-" +
                                                a +
                                                "" +
                                                $("#num_factura").val();
                                        $.ajax({
                                            type: "POST",
                                            url: "guardar_factura_venta.php",
                                            data:
                                                    "id_cliente=" +
                                                    $("#id_cliente").val() +
                                                    "&comprobante=" +
                                                    $("#comprobante").val() +
                                                    "&num_factura=" +
                                                    seriee +
                                                    "&fecha_actual=" +
                                                    $("#fecha_actual").val() +
                                                    "&hora_actual=" +
                                                    $("#hora_actual").val() +
                                                    "&proforma=" +
                                                    $("#proforma").val() +
                                                    "&cancelacion=" +
                                                    $("#cancelacion").val() +
                                                    "&tipo_precio=" +
                                                    $("#tipo_precio").val() +
                                                    "&formaspago=" +
                                                    $("#formaspago").val() +
                                                    "&adelanto=" +
                                                    $("#adelanto").val() +
                                                    "&meses=" +
                                                    $("#meses").val() +
                                                    "&autorizacion=" +
                                                    $("#autorizacion").val() +
                                                    "&fecha_auto=" +
                                                    $("#fecha_auto").val() +
                                                    "&fecha_caducidad=" +
                                                    $("#fecha_caducidad").val() +
                                                    "&tarifa0=" +
                                                    $("#total_p").val() +
                                                    "&tarifa12=" +
                                                    $("#total_p2").val() +
                                                    "&iva=" +
                                                    $("#iva").val() +
                                                    "&desc=" +
                                                    $("#desc").val() +
                                                    "&tot=" +
                                                    $("#tot").val() +
                                                    "&ruc_ci=" +
                                                    $("#ruc_ci").val() +
                                                    "&nombre_cliente=" +
                                                    $("#nombre_cliente").val() +
                                                    "&direccion_cliente=" +
                                                    $("#direccion_cliente").val() +
                                                    "&telefono_cliente=" +
                                                    $("#telefono_cliente").val() +
                                                    "&correo=" +
                                                    $("#correo").val().toLowerCase() +
                                                    "&campo1=" +
                                                    string_v1 +
                                                    "&campo2=" +
                                                    string_v2 +
                                                    "&campo3=" +
                                                    string_v3 +
                                                    "&campo4=" +
                                                    string_v4 +
                                                    "&campo5=" +
                                                    string_v5 +
                                                    "&campo6=" +
                                                    string_v6 +
                                                    "&tipo_venta=" +
                                                    $("#tipo_venta").val(),
                                            success: function (data) {
                                                var val = data;
                                                if ($("#tipo_venta").val() == "FACTURA") {
                                                    if (val != 0) {
                                                        alertify.alert(
                                                                "Factura Guardada correctamente",
                                                                function () {
                                                                    var myWindow = window.open(
                                                                            "../../reportes/factura_venta.php?hoja=A4&id=" +
                                                                            val,
                                                                            "_blank"
                                                                            );
                                                                    myWindow.focus();
                                                                    myWindow.print();
                                                                    location.reload();
                                                                }
                                                        );
                                                    }
                                                } else {
                                                    if ($("#tipo_venta").val() == "NOTA") {
                                                        if (val != 0) {
                                                            alertify.alert(
                                                                    "Nota Venta Guardada correctamente",
                                                                    function () {
                                                                        var myWindow = window.open(
                                                                                formatoNotaVenta + "?hoja=A4&id=" +
                                                                                val,
                                                                                "_blank"
                                                                                );
                                                                        myWindow.focus();
                                                                        myWindow.print();
                                                                        location.reload();
                                                                    }
                                                            );
                                                        }
                                                    }
                                                }
                                            },
                                        });
                                    }
                                }
                            }
                        }
                    }
                }
            },
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
//            alertify.error("Ingrese número de la factura");
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
//                                    data: "id_factura_venta=" + $("#id_factura_venta").val() + "&id_cliente=" + $("#id_cliente").val() + "&comprobante=" + $("#comprobante").val() + "&num_factura=" + seriee + "&fecha_actual=" + $("#fecha_actual").val() + "&hora_actual=" + $("#hora_actual").val() + "&proforma=" + $("#proforma").val() + "&cancelacion=" + $("#cancelacion").val() + "&tipo_precio=" + $("#tipo_precio").val() + "&formas=" + $("#formas").val() + "&adelanto=" + $("#adelanto").val() + "&meses=" + $("#meses").val() + "&autorizacion=" + $("#autorizacion").val()+ "&fecha_auto=" + $("#fecha_auto").val()+ "&fecha_caducidad=" + $("#fecha_caducidad").val() + "&tarifa0=" + $("#total_p").val() + "&tarifa12=" + $("#total_p2").val() + "&iva=" + $("#iva").val() + "&desc=" + $("#desc").val() + "&tot=" + $("#tot").val() + "&ruc_ci=" + $("#ruc_ci").val() + "&nombre_cliente=" + $("#nombre_cliente").val() + "&direccion_cliente=" + $("#direccion_cliente").val() + "&telefono_cliente=" + $("#telefono_cliente").val() + "&correo=" + $("#correo").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5+ "&campo6=" + string_v6+ "&tipo_venta=" + $("#tipo_venta").val(),
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
        data:
                "comprobante=" +
                $("#comprobante").val() +
                "&tabla=" +
                "factura_venta" +
                "&id_tabla=" +
                "id_factura_venta" +
                "&tipo=" +
                1,
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#comprobante").val(val);
                var valor = $("#comprobante").val();
                // llamar factura venta
                $("#btnGuardar").attr("disabled", true);
                //                                $("#btnGuardarRetenciones").attr("disabled", true);
                // $("#num_factura").attr("disabled", true);
                $("#id_cliente").val("");
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
                $("#cuotas").children().remove().end();
                $("#list").jqGrid("clearGridData", true);
                $("#total_p").val("0.000");
                $("#total_p2").val("0.000");
                $("#iva").val("0.000");
                $("#desc").val("0.000");
                $("#tot").val("0.000");
                $("#descxa").val("0");
                $("#total_px").val("0.000");
                $("#total_p2x").val("0.000");
                $("#ivax").val("0.000");
                $("#descxax").val("0.000");
                $("#totx").val("0.000");
                $.getJSON("retornar_factura_venta.php?com=" + valor, function (data) {
                    var tama = data.length;
                    t = data[23];
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 24) {
                            obtenerCentroCosoTransaccion(data[i], 'FACTURA');
                            $("#id_factura_venta").val(data[i]);

                            $("#fecha_actual").val(data[i + 1]);
                            $("#hora_actual").val(data[i + 2]);
                            $("#digitador").val(data[i + 3] + " " + data[i + 4]);
                            var num = data[i + 5];
                            var res = num;
                            $("#num_factura").val(res);
                            $("#id_cliente").val(data[i + 6]);
                            $("#ruc_ci").val(data[i + 7]);
                            $("#nombre_cliente").val(data[i + 8]);
                            $("#direccion_cliente").val(data[i + 9]);
                            $("#telefono_cliente").val(data[i + 10]);
                            $("#correo").val(data[i + 11]);
                            $("#autorizacion").val(data[i + 12]);
                            $("#fecha_auto").val(data[i + 13]);
                            $("#fecha_caducidad").val(data[i + 14]);
                            $("#cancelacion").val(data[i + 15]);
                            $("#tipo_precio").val(data[i + 16]);
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
                                if (data[i + 23] == "0") {
                                    $("#btnModificar").attr("disabled", false);
                                    $("#btnGuardar").attr("disabled", false);
                                    $("#codigo_barras").attr("disabled", false);
                                    $("#codigo").attr("disabled", false);
                                    $("#producto").attr("disabled", false);
                                    $("#cantidad").attr("disabled", false);
                                    $("#p_venta").attr("disabled", false);
                                    $("#descuento").attr("disabled", false);
                                    $("#formaspago").attr("disabled", false);
                                } else if (data[i + 23] == "1") {
                                    $("#btnModificar").attr("disabled", "disabled");
                                }
                            }

                            $("#total_p").val(data[i + 18]);
                            $("#total_p2").val(data[i + 19]);
                            $("#sub").val(
                                    parseFloat(data[i + 18]) + parseFloat(data[i + 19])
                                    );
                            $("#iva").val(data[i + 20]);
                            $("#desc").val(data[i + 21]);
                            $("#tot").val(data[i + 22]);
                            $("#total_px").val(parseFloat(data[i + 18]).toFixed(2));
                            $("#total_p2x").val(parseFloat(data[i + 19]).toFixed(2));
                            $("#subx").val(
                                    (parseFloat(data[i + 18]) + parseFloat(data[i + 19])).toFixed(2)
                                    );
                            $("#ivax").val(parseFloat(data[i + 20]).toFixed(2));
                            $("#descxax").val(parseFloat(data[i + 21]).toFixed(2));
                            $("#totx").val(parseFloat(data[i + 22]).toFixed(2));

                            $("#id_factura_venta").trigger("change");
                        }
                        volver_rf();
                        volver_ri();

                    }
                });
                $.getJSON(
                        "retornar_factura_venta_credito.php?com=" + valor,
                        function (data) {
                            var tama = data.length;
                            if (tama != 0) {
                                for (var i = 0; i < tama; i = i + 4) {
                                    $("#formaspago").val(data[i]);
                                    $("#adelanto").val(data[i + 1]);
                                    $("#meses").val(data[i + 2]);
                                    // calcular meses
                                    if (data[i + 2] > 1) {
                                        $("#cuotas").attr("disabled", false);
                                        for (var j = 1; j <= data[i + 2] - 1; j++) {
                                            var calcu = data[i + 3] / data[i + 2];
                                            var entero = Math.floor(calcu);
                                            $("#cuotas").append("<option>" + entero + "</option>");
                                        }
                                        var calcu1 = entero * (data[i + 2] - 1);
                                        var sal = data[i + 3] - calcu1;
                                        var entero2 = sal;
                                        $("#cuotas").append("<option>" + entero2 + "</option>");
                                    } else {
                                        $("#cuotas").attr("disabled", false);
                                        $("#cuotas").append("<option>" + data[i + 3] + "</option>");
                                    }
                                }
                            }
                        }
                );
                $.getJSON("retornar_factura_venta2.php?com=" + valor, function (data) {
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
                        for (var i = 0; i < tama; i = i + 13) {
                            desc = data[i + 5];
                            precio = parseFloat(data[i + 4]);
                            multi = parseFloat(data[i + 3]) * parseFloat(data[i + 4]);
                            descuento = (multi * parseFloat(desc)) / 100;
                            flotante = parseFloat(descuento);
                            resultado =
                                    Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
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
                                incluye: data[i + 9],
                                cantidad_unidad: data[i + 10],
                                unidad_medida: data[i + 11],
                                detalle_producto: data[i + 12],
                            };
                            var su = jQuery("#list").jqGrid("addRowData", data[i], datarow);
                            suma_total = suma_total + parseFloat(data[i + 3]);
                        }
                        var fila = jQuery("#list").jqGrid("getRowData");
                        $("#items").val(fila.length);
                        $("#num").val(suma_total);
                    }
                });
                $.getJSON(
                        "retornar_formas_mixto_grid.php?com=" + valor,
                        function (data) {
                            $("#listPagoreten_mixto").jqGrid("clearGridData", true);
                            var tama = data.length;
                            if (tama != 0) {
                                for (var i = 0; i < tama; i = i + 6) {
                                    var datarow = {
                                        forma_pago_mixto: data[i],
                                        tarjeta_credito: data[i + 1],
                                        num_documento: data[i + 2],
                                        valor: data[i + 3],
                                        id_cuenta: data[i + 4],
                                        fecha_vencimiento: data[i + 5],
                                    };
                                    var su = jQuery("#listPagoreten_mixto").jqGrid(
                                            "addRowData",
                                            data[i],
                                            datarow
                                            );
                                }
                            }
                        }
                );
                // Fin
                $.getJSON("retornar_formas_mixto.php?com=" + valor, function (data) {
                    var tama = data.length;
                    t = data[4];
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 5) {
                            $("#adelanto").val(data[i]);
                            $("#meses").val(data[i + 1]);
                            //                            $("#cantidad_mixto").val(data[i + 2]);
                            $("#fecha_dias").val(data[i + 3]);
                        }
                    }
                });
                $("#serie_retencion").val("");
                $.getJSON(
                        "retornar_retenciones_grid.php?com=" + valor,
                        function (data) {
                            $("#listPagoreten").jqGrid("clearGridData", true);
                            var tama = data.length;
                            if (tama != 0) {
                                for (var i = 0; i < tama; i = i + 7) {
                                    $("#btnGuardarRetenciones").attr("disabled", true);
                                    var datarow = {
                                        base_imponible: data[i],
                                        impuesto: data[i + 1],
                                        porcent_reten: data[i + 2],
                                        valor_retenido: data[i + 3],
                                        codigo_ret: data[i + 6],
                                    };
                                    var num = data[i + 5];
                                    var res = num.substr(8, 20);
                                    $("#serie_retencion").val(num);
                                    var su = jQuery("#listPagoreten").jqGrid(
                                            "addRowData",
                                            data[i],
                                            datarow
                                            );
                                }
                            } else {
                                $("#btnGuardarRetenciones").attr("disabled", false);
                            }
                        }
                );

                $("#clavefactura").val("");
                $("#total_retencion").val("");
                $("#formaspago_mixto_reten").val("");
                $("#cuenta_contable_reten").val("");
                $("#idCuenta_reten").val("");
                $("#formaspago_mixto_reten")[0].disabled = true;
                $("#btnCuenta_reten")[0].disabled = true;
                limpiarCamposRetencion();
            } else {
                alertify.alert("No hay más registros posteriores!!");
            }
        },
    });
}

function volver_rf() {
    $.ajax({
        type: "POST",
        url: "retornar_retencion_fuente.php",
        data: "id_factura_venta=" + $("#id_factura_venta").val(),
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
        },
    });
}

function volver_ri() {
    $.ajax({
        type: "POST",
        url: "retornar_retencion_iva.php",
        data: "id_factura_venta=" + $("#id_factura_venta").val(),
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
        },
    });
}

function flecha_siguiente() {
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data:
                "comprobante=" +
                $("#comprobante").val() +
                "&tabla=" +
                "factura_venta" +
                "&id_tabla=" +
                "id_factura_venta" +
                "&tipo=" +
                2,
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#comprobante").val(val);
                var valor = $("#comprobante").val();
                // llamar factura venta
                $("#btnGuardar").attr("disabled", true);
                //                                $("#btnGuardarRetenciones").attr("disabled", true);

                // $("#num_factura").attr("disabled", true);
                $("#id_cliente").val("");
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
                $("#cuotas").children().remove().end();
                $("#list").jqGrid("clearGridData", true);
                $("#total_p").val("0.000");
                $("#total_p2").val("0.000");
                $("#iva").val("0.000");
                $("#desc").val("0.000");
                $("#tot").val("0.000");
                $("#descxa").val("0");
                $("#total_px").val("0.000");
                $("#total_p2x").val("0.000");
                $("#ivax").val("0.000");
                $("#descxax").val("0.000");
                $("#totx").val("0.000");
                $.getJSON("retornar_factura_venta.php?com=" + valor, function (data) {
                    var tama = data.length;
                    t = data[23];
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 24) {
                            obtenerCentroCosoTransaccion(data[i], 'FACTURA');
                            $("#id_factura_venta").val(data[i]);

                            $("#fecha_actual").val(data[i + 1]);
                            $("#hora_actual").val(data[i + 2]);
                            $("#digitador").val(data[i + 3] + " " + data[i + 4]);
                            var num = data[i + 5];
                            var res = num;
                            $("#num_factura").val(res);
                            $("#id_cliente").val(data[i + 6]);
                            $("#ruc_ci").val(data[i + 7]);
                            $("#nombre_cliente").val(data[i + 8]);
                            $("#direccion_cliente").val(data[i + 9]);
                            $("#telefono_cliente").val(data[i + 10]);
                            $("#correo").val(data[i + 11]);
                            $("#autorizacion").val(data[i + 12]);
                            $("#fecha_auto").val(data[i + 13]);
                            $("#fecha_caducidad").val(data[i + 14]);
                            $("#cancelacion").val(data[i + 15]);
                            $("#tipo_precio").val(data[i + 16]);
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
                                if (data[i + 23] == "0") {
                                    $("#btnModificar").attr("disabled", false);
                                    $("#btnGuardar").attr("disabled", false);
                                    $("#codigo_barras").attr("disabled", false);
                                    $("#codigo").attr("disabled", false);
                                    $("#producto").attr("disabled", false);
                                    $("#cantidad").attr("disabled", false);
                                    $("#p_venta").attr("disabled", false);
                                    $("#descuento").attr("disabled", false);
                                    $("#formaspago").attr("disabled", false);
                                } else if (data[i + 23] == "1") {
                                    $("#btnModificar").attr("disabled", "disabled");
                                }
                            }

                            $("#total_p").val(data[i + 18]);
                            $("#total_p2").val(data[i + 19]);
                            $("#sub").val(
                                    parseFloat(data[i + 18]) + parseFloat(data[i + 19])
                                    );
                            $("#iva").val(data[i + 20]);
                            $("#desc").val(data[i + 21]);
                            $("#tot").val(data[i + 22]);
                            $("#total_px").val(parseFloat(data[i + 18]).toFixed(2));
                            $("#total_p2x").val(parseFloat(data[i + 19]).toFixed(2));
                            $("#subx").val(
                                    (parseFloat(data[i + 18]) + parseFloat(data[i + 19])).toFixed(2)
                                    );
                            $("#ivax").val(parseFloat(data[i + 20]).toFixed(2));
                            $("#descxax").val(parseFloat(data[i + 21]).toFixed(2));
                            $("#totx").val(parseFloat(data[i + 22]).toFixed(2));

                            $("#id_factura_venta").trigger("change");
                        }
                        volver_rf();
                        volver_ri();

                    }
                });
                $.getJSON(
                        "retornar_factura_venta_credito.php?com=" + valor,
                        function (data) {
                            var tama = data.length;
                            if (tama != 0) {
                                for (var i = 0; i < tama; i = i + 4) {
                                    $("#formaspago").val(data[i]);
                                    $("#adelanto").val(data[i + 1]);
                                    $("#meses").val(data[i + 2]);
                                    // calcular meses
                                    if (data[i + 2] > 1) {
                                        $("#cuotas").attr("disabled", false);
                                        for (var j = 1; j <= data[i + 2] - 1; j++) {
                                            var calcu = data[i + 3] / data[i + 2];
                                            var entero = Math.floor(calcu).toFixed(2);
                                            $("#cuotas").append("<option>" + entero + "</option>");
                                        }
                                        var calcu1 = entero * (data[i + 2] - 1);
                                        var sal = data[i + 3] - calcu1;
                                        var entero2 = sal.toFixed(2);
                                        $("#cuotas").append("<option>" + entero2 + "</option>");
                                    } else {
                                        $("#cuotas").attr("disabled", false);
                                        $("#cuotas").append("<option>" + data[i + 3] + "</option>");
                                    }
                                }
                            }
                        }
                );
                $.getJSON("retornar_factura_venta2.php?com=" + valor, function (data) {
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
                        for (var i = 0; i < tama; i = i + 13) {
                            desc = data[i + 5];
                            precio = parseFloat(data[i + 4]);
                            multi = parseFloat(data[i + 3]) * parseFloat(data[i + 4]);
                            descuento = (multi * parseFloat(desc)) / 100;
                            flotante = parseFloat(descuento);
                            resultado =
                                    Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
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
                                incluye: data[i + 9],
                                cantidad_unidad: data[i + 10],
                                unidad_medida: data[i + 11],
                                detalle_producto: data[i + 12]
                            };
                            var su = jQuery("#list").jqGrid("addRowData", data[i], datarow);
                            suma_total = suma_total + parseFloat(data[i + 3]);
                        }
                        var fila = jQuery("#list").jqGrid("getRowData");
                        $("#items").val(fila.length);
                        $("#num").val(suma_total.toFixed(2));
                    }
                });
                // Fin
                $.getJSON(
                        "retornar_formas_mixto_grid.php?com=" + valor,
                        function (data) {
                            $("#listPagoreten_mixto").jqGrid("clearGridData", true);
                            var tama = data.length;
                            if (tama != 0) {
                                for (var i = 0; i < tama; i = i + 6) {
                                    var datarow = {
                                        forma_pago_mixto: data[i],
                                        tarjeta_credito: data[i + 1],
                                        num_documento: data[i + 2],
                                        valor: data[i + 3],
                                        id_cuenta: data[i + 4],
                                        fecha_vencimiento: data[i + 5],
                                    };
                                    var su = jQuery("#listPagoreten_mixto").jqGrid("addRowData",
                                            data[i],
                                            datarow
                                            );
                                }
                            }
                        }
                );
                $("#serie_retencion").val("");
                $.getJSON(
                        "retornar_retenciones_grid.php?com=" + valor,
                        function (data) {
                            $("#listPagoreten").jqGrid("clearGridData", true);
                            var tama = data.length;
                            if (tama != 0) {
                                $("#btnGuardarRetenciones").attr("disabled", true);
                                for (var i = 0; i < tama; i = i + 7) {
                                    var datarow = {
                                        base_imponible: data[i],
                                        impuesto: data[i + 1],
                                        porcent_reten: data[i + 2],
                                        valor_retenido: data[i + 3],
                                        codigo_ret: data[i + 6],
                                    };
                                    var num = data[i + 5];
                                    var res = num.substr(8, 20);
                                    $("#serie_retencion").val(num);
                                    var su = jQuery("#listPagoreten").jqGrid("addRowData", data[i], datarow);
                                }
                            } else {
                                $("#btnGuardarRetenciones").attr("disabled", false);

                            }
                        }
                );

                $("#clavefactura").val("");
                $("#total_retencion").val("");
                $("#formaspago_mixto_reten").val("");
                $("#cuenta_contable_reten").val("");
                $("#idCuenta_reten").val("");
                $("#formaspago_mixto_reten")[0].disabled = true;
                $("#btnCuenta_reten")[0].disabled = true;
                limpiarCamposRetencion();
            } else {
                if ($("#id_factura_venta").val() != "") {
                    $("#comprobante").val($("#comprobante").val());
                }
                alertify.alert("No hay más registros superiores!!");
            }
        },
    });
}

function limpiar_campo5() {
    if ($("#ruc_ci_bene").val() == "") {
        $("#id_beneficiario").val("");
        $("#nombre_cliente_bene").val("");
    }
}
function limpiar_campo() {
    if ($("#ruc_ci").val() == "") {
        $("#id_cliente").val("");
        $("#nombre_cliente").val("");
        $("#direccion_cliente").val("");
        $("#telefono_cliente").val("");
        $("#correo").val("");
        $("#nombre_director").val("");
        $("#direccion_cliente").attr("disabled", "disabled");
        //    $("#telefono_cliente").attr("disabled", "disabled");
        //    $("#correo").attr("disabled", "disabled");
    }
}

function limpiar_campo2() {
    if ($("#nombre_cliente").val() == "") {
        $("#id_cliente").val("");
        $("#ruc_ci").val("");
        $("#direccion_cliente").val("");
        $("#telefono_cliente").val("");
        $("#correo").val("");
        $("#nombre_director").val("");
        $("#direccion_cliente").attr("disabled", "disabled");
        //    $("#telefono_cliente").attr("disabled", "disabled");
        //    $("#correo").attr("disabled", "disabled");
    }
}

function limpiar_campo3() {
    if ($("#codigo").val() == "") {
        $("#codigo_barras").val("");
        $("#cod_producto").val("");
        $("#producto").val("");
        //        $("#cantidad").val("");
        $("#p_venta").val("");
          $("#precio").val("");
        $("#descuento").val("");
        $("#venta_iva").val("0.00");
        $("#disponibles").val("");
        $("#iva_producto").val("");
        $("#carga_series").val("");
        $("#des").val("");
        $("#incluye").val("");
        $("#inventar").val("");
        $("#venta_iva_1").val("");
        $("#cantidad_unidad").val("");
        $("#unidad_medida").val("");
    }
}

function limpiar_campo4() {
    if ($("#producto").val() == "") {
        $("#codigo_barras").val("");
        $("#cod_producto").val("");
        $("#codigo").val("");
        //        $("#cantidad").val("");
        $("#p_venta").val("");
           $("#precio").val("");
        $("#venta_iva").val("0.00");
        //        $("#descuento").val("");
        $("#disponibles").val("");
        $("#iva_producto").val("");
        $("#carga_series").val("");
        $("#des").val("");
        $("#incluye").val("");
        $("#inventar").val("");
        $("#venta_iva_1").val("");
        $("#cantidad_unidad").val("");
        $("#unidad_medida").val("");
    }
}

function limpiar_factura() {
    location.reload();
}

function anular_factura(e) {
    if (e.originalEvent.pointerType != "") {
        $("#clave_permiso").dialog("open");
    }
}
function validarValorFacturaCliente() {
    if ($("#tipo_venta").val() == "FACTURA") {
        if (Number($("#totx").val()) >= 50) {
            if ($("#id_cliente") == 1 || $("#ruc_ci").val() == "9999999999999") {
                alertify.alert("<b>La factura es igual o superior a 50 dólares debe seleccionar un cliente registrado diferente de Consumidor Final.</b>", function (e) {
                    $("#ruc_ci").focus()
                });
                return false;
            }
        }
    }
    return true;
}
function ingresar_cambio(e) {
    if (!validarValorFacturaCliente()) {
        return;
    }
    if (loadingFactura) {
        e.preventDefault();
        e.stopPropagation();
        return false;
    }
    $("#valor_recibo").val($("#totx").val());
    if ($("#valor_recibo").val() != "") {
        var num_factu = $("#num_factura").val();
        let tipo = $("#tipo_venta").val();
        $.ajax({
            type: "POST",
            url: "comparar_num_venta.php",
            data: "num_fac=" + num_factu + "&tipo_venta=" + tipo,
            success: function (data) {
                var val = data;
                console.log("::" + val);
                val = val.split("-");
                if (val[0] != 0) {
                    $("#num_factura").val("");
                    var res1 = parseInt(val[0].substr(4, 16));
                    res1 = res1 + 1;
                    //FACTURA VENTA
                    var res3 = parseInt(val[1]);
                    res3 = res3 + 1;
                    //nota venta
                    var res2 = parseInt(val[1]);
                    res2 = res2 + 1;
                    alertify.success("Se Asignó un nuevo num de factura" + res1);
                    $("#num_factura").val(res1);
                    //nota venta
                    var filas = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
                    if ($("#tipo_venta").val() == "FACTURA") {
                        $("#comprobante").val(res3);
                        for (var i = 0; i < filas.length; i++) {
                            var id = filas[i];
                            var id_mix = id["id_f_v_mix"];
                            console.log("f2::" + id_mix);
                            jQuery("#listPagoreten_mixto").jqGrid("setRowData", id_mix, {
                                id_factura_venta: res2,
                            });
                        }
                    } else {
                        $("#comprobante_nota").val(res2);
                        for (var i = 0; i < filas.length; i++) {
                            var id = filas[i];
                            var id_mix = id["id_f_v_mix"];
                            console.log("f1::" + id_mix);
                            jQuery("#listPagoreten_mixto").jqGrid("setRowData", id_mix, {
                                id_factura_venta: res2,
                            });
                        }
                    }
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
                        if ($("#id_factura_venta").val() == "") {
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
                                $("#valor_recibo").val($("#totx").val());
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
                                    $("#valor_recibo").val($("#totx").val());
                                    $("#total_venta").val($("#totx").val());
                                } else {
                                    guardar_factura();
                                }
                            }
                        } else {
                            alertify.error("Generar Nueva Factura");
                        }
                    }

                }
            },
        });
    } else {
        alertify.error("Debe ingresar un Valor Recibido");
    }
}

function validar_acceso() {
    if ($("#clave").val() == "") {
        $("#clave").focus();
        alertify.alert("Ingrese la clave");
    } else {
        $.ajax({
            url: "validar_acceso.php",
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

function aceptar() {
    if (loadingAnular) {
        return;
    }

    var v1 = new Array();
    var v2 = new Array();
    var string_v1 = "";
    var string_v2 = "";
    var fil = jQuery("#list").jqGrid("getRowData");
    for (var i = 0; i < fil.length; i++) {
        var datos = fil[i];
        v1[i] = datos["cod_producto"];
        v2[i] = datos["cantidad"];
    }
    for (i = 0; i < fil.length; i++) {
        string_v1 = string_v1 + "|" + v1[i];
        string_v2 = string_v2 + "|" + v2[i];
    }
    anularFacturaUI();
    $.ajax({
        type: "POST",
        url: "anular_factura_venta.php",
        data:
                "comprobante=" +
                $("#comprobante").val() +
                "&tipo_venta=" +
                $("#tipo_venta").val() +
                "&campo1=" +
                string_v1 +
                "&campo2=" +
                string_v2 +
                "&fecha_anulacion=" +
                $("#fecha_actual").val() +
                "&num_factura=" +
                $("#num_factura").val(),
        success: function (data) {
            $("#seguro").dialog("close");
            $("#clave_permiso").dialog("close");
            var val = data;
            if (val == 1) {
                alertify.alert("Factura Anulada Correctamente", function () {
                    location.reload();
                });
            }
        },
    })
            .fail(function () {
                pararAnularFacturaUI();
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

function numeros(e) {
    tecla = document.all ? e.keyCode : e.which;
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
var combo_1 = "";
var combo_2 = "";

var enviartarifa0 = 0;
var enviartarifa12 = 0;
var envdescprod = 0;
var envdescfact = 0;

function obtenerTotalFacturaSinDescuentoFactura() {
    let valordesc = 0;
    let t0 = Number.isNaN(parseFloat($("#total_p").val()))
            ? 0
            : parseFloat($("#total_p").val());
    let t12 = Number.isNaN(parseFloat($("#total_p2").val()))
            ? 0
            : parseFloat($("#total_p2").val());
    let dt0 = t0 * (valordesc / 100);
    let dt12 = t12 * (valordesc / 100);
    let nt0 = t0 - dt0;
    let nt12 = t12 - dt12;
    let nsub = nt0 + nt12;
    let niva = (nt12 * calculoIVA) / 100;
    let ntot = nsub + niva;

    return ntot;
}

function funcion_descuento_factura(updatevaldesc = true) {
    let valordesc = Number.isNaN(parseFloat($("#descxa").val()))
            ? 0
            : parseFloat($("#descxa").val());
    let t0 = Number.isNaN(parseFloat($("#total_p").val()))
            ? 0
            : parseFloat($("#total_p").val());
    let t12 = Number.isNaN(parseFloat($("#total_p2").val()))
            ? 0
            : parseFloat($("#total_p2").val());
    let dt0 = t0 * (valordesc / 100);
    let dt12 = t12 * (valordesc / 100);
    let nt0 = t0 - dt0;
    let nt12 = t12 - dt12;
    let nsub = nt0 + nt12;
    let niva = (nt12 * calculoIVA) / 100;
    let ntot = nsub + niva;
    let n1iva = numFormatter(2).format(niva);
    let n1tot = numFormatter(2).format(ntot);
    let descfac = dt0 + dt12;
    let descprod = $("#descxax").val();

    let totdesc = Number(descprod) + Number(descfac);
    let totalt0 = t0 - dt0;
    let totalt12 = t12 - dt12;
    let totalsub = totalt0 + totalt12;

    envdescprod = Number(descprod);
    envdescfact = Number(descfac);
    $("#desctotal").val(numFormatter(2).format(totdesc));
    $("#total_px").val(numFormatter(2).format(totalt0));
    enviartarifa0 = totalt0;
    $("#total_p2x").val(numFormatter(2).format(totalt12));
    enviartarifa12 = totalt12;
    $("#subx").val(numFormatter(2).format(totalsub));

    if (updatevaldesc) {
        $("#descxa_v").val(numFormatter(2).format(dt0 + dt12 + (dt12 * (calculoIVA / 100))));
    }

    $("#iva").val(niva);
    $("#tot").val(ntot);
    $("#ivax").val(n1iva);
    $("#totx").val(n1tot);
    /* var valor_descuento = parseFloat($("#descxa").val());
     var valor_resultado_subtotal = (parseFloat($("#sub").val()) * parseFloat($("#descxa").val())) / 100;
     $("#descxax").val(valor_resultado_subtotal.toFixed(2));
     
     valor_resultado_subtotal = parseFloat($("#sub").val()) - valor_resultado_subtotal;
     console.log(valor_resultado_subtotal + "rrrr");
     var resultado_descu_iva = (valor_resultado_subtotal * 12) / 100;
     var total_con_descu = valor_resultado_subtotal + resultado_descu_iva;
     console.log(total_con_descu + "rrr");
     $("#iva").val(resultado_descu_iva);
     $("#tot").val(total_con_descu);
     $("#ivax").val(resultado_descu_iva.toFixed(4));
     $("#totx").val(total_con_descu.toFixed(2)); */
}
function abrirCuenta() {
    $("#cuentas").dialog("open");
}
function abrirCuenta_reten() {
    $("#cuentas_reten").dialog("open");
}
function actualizar_clave() {
    $.ajax({
        type: "POST",
        url: "guardar_factura_venta.php",
        data: {
            actualizar_clave_acceso: 'actualizar_clave_acceso',
            id: $("#comprobante").val()
        },
        dataType: "json",
        success: function (data) {
            if (data.estado == 1) {
                alertify.alert("actualizado clave Acceso: ");
            } else {
                alertify.alert("Error ..... " + data);
            }
        }
    });
}
function comprobar_cuentas(prod) {
    // Comprobar si el prod lleva iva antes del ingreso
    $.ajax({
        type: "POST",
        url: "comprobar_cuenta.php",
        data: "prod=" + prod,
        success: function (data) {
            var val = data;

            if (val == 1) {
                $("#debe").css("display", "");
                //                alertify.success("CUENTA POR COBRAR PENDIENTE")
            } else {
                $("#debe").css("display", "none");
            }
        },
    });
}
function comprobar_cuentas_promo(prod) {
    $.getJSON("comprobar_cuenta_promo.php?prod=" + prod + "&fecha_actual=" + $("#fecha_actual").val(), function (data) {
        console.log("valor data" + data);
        if (data != null) {
            var tama = data.length;
            $("#debe1").css("display", "");
            for (var i = 0; i < tama; i = i + 5) {
                $("#descuento").val(data[i]);
            }
        } else {
            $("#debe1").css("display", "none");
        }
    });
}
function comprobar_pvp_editable(prod) {
    $.getJSON("comprobar_pvp_editar.php?prod=" + prod, function (data) {
        data = 1;
        if (data != null) {
            $("#p_venta").removeAttr("disabled");
            $("#venta_iva_1").removeAttr("disabled");



        } else {
            $("#venta_iva_1").attr("disabled", "disabled");
            $("#p_venta").attr("disabled", "disabled");



        }
    });
}
function funcion_buscar_cliente() {
    $("#id_cliente").val("");
    console.log("entro a la funcion");
    if ($("#id_cliente").val() == "") {
        console.log("si existe local es vacio");
        $.ajax({
            url: "http://181.188.216.198:81/clientes/data/clientes/buscar_cliente_ser.php?term=" + $("#ruc_ci").val(),
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                var val = data;
                if (val != null) {
                    console.log("entro a la funcion buscar cliente..");
                    //                console.log(val[0].value);
                    $("#ruc_ci").val(val[0].value);
                    $("#id_cliente").val("");
                    $("#nombre_cliente").val(val[0].nombre_cliente);
                    $("#direccion_cliente").val(val[0].direccion_cliente);
                    $("#telefono_cliente").val(val[0].telefono_cliente);
                    $("#correo").val(val[0].correo);
                    $("#id_tdocu").val(val[0].id_tdocu);

                } else {
                    nuevo_cliente($("#ruc_ci").val());

                }
            },
        });
    }
}
function insertar_cliente() {
    console.log("entro a la funcion insert");
    $.ajax({
        url: "http://181.188.216.198:81/clientes/data/clientes/guardar_clientes_ser.php",
        type: "POST",
        data: "ruc_ci=" + $("#ruc_ci").val()
                + "&nombre_cliente=" + $("#nombre_cliente").val()
                + "&direccion_cliente=" + $("#direccion_cliente").val()
                + "&telefono_cliente=" + $("#telefono_cliente").val()
                + "&correo=" + $("#correo").val().toLowerCase()
                + "&id_tdocu=" + $("#id_tdocu").val(),
        success: function (data) {
            var val = data;
            if (val == 1) {
                //                alertify.success("Cliente guardado correctamente en servidor");
            } else {
                //                alertify.success("Cliente ya existe en servidor");
            }
        },
    });
}
var cmpAddCliente;
function initAddCliente() {
    $("#dialog_form_cliente").dialog({
        modal: true,
        width: 800,
        height: 500,
        minHeight: 600,
        minHeight: 500,
        autoOpen: false,
        title: "REGISTRAR CLIENTE",
        close: function (event, ui) {
            $("#form_cmp")[0].reset();
            $(".ui-dialog-content").dialog("close");
        }
    });

    $.getScript("../clientes/clientes_ui_util/clientes.js", function () {
        cmpAddCliente = new AddCliente();
        cmpAddCliente.contenedor = $("#form_cliente");
        cmpAddCliente.onGuardar = function (data) {
            if (!!data) {
                buscarClienteAutocomplete(data);
            }
        };
        cmpAddCliente.init();
    });

    $("#nuevo_cliente").click(function (e) {
        $("#dialog_form_cliente").dialog("open");
    });
}
function nuevo_cliente(valciruc) {
    alertify.confirm("<b>El cliente no esta registrado</b>", function (e) {
        if (e) {
            cmpAddCliente.setIdentificacion(valciruc);
            $("#dialog_form_cliente").dialog("open");
        }
    });
    $("#alertify-ok").text("Registrar");
    $("#alertify-ok").css({'background': "#1E88E5"});

    $("#ruc_ci").val("");
    $("#direccion_cliente").val("");
    $("#nombre_cliente").val("");
    $("#telefono_cliente").val("");
    $("#correo").val("");
    $("#id_cliente").val("");
}
function buscarClienteAutocomplete(data) {
    $("#ruc_ci").autocomplete("search", data);
    $("#ruc_ci").autocomplete({
        response: function (event, ui) {
            let res = ui.content[0];
            $("#ruc_ci").val(res.value);
            $("#id_cliente").val(res.id_cliente);
            $("#nombre_cliente").val(res.nombre_cliente);
            $("#direccion_cliente").val(res.direccion_cliente);
            $("#telefono_cliente").val(res.telefono_cliente);
            $("#correo").val(res.correo);
            $("#nombre_vendedor").val(res.nombre_vendedor);
            $("#vendedor").val(res.id_vendedor);
            $("#id_tdocu").val(res.id_tdocu);
            //            comprobar_cuentas($("#ruc_ci").val());
            $("#ruc_ci").blur();
            $("#dialog_form_cliente").dialog("close");
            $("#alertify-logs").empty();
            alertify.success("Cliente cargado correctamente");
            $("#ruc_ci").autocomplete({
                response: function (event, ui) { }
            })
        }
    });
}

function inicio() {


    $("#productos_form").submit(function (e) {
        e.preventDefault();
        e.stopPropagation();
        return false;
    });

    $("#ruc_ci").change(function (e) {
        if ($(this).val().trim() == "" && $("#id_cliente").val() == "") {
            return;
        }
        comprobar1($("#ruc_ci").val());
    });
    $("#ruc_ci").click((e) => {
        e.preventDefault();
        $("#id_cliente").val("");
    });
    initAddCliente();
    //    $("#btnBuscar_cliente").click(function (e) {
    //        e.preventDefault();
    //    });
    //    $("#btnBuscar_cliente").on("click", funcion_buscar_cliente);
    document.getElementById("descxa_v").addEventListener("input", function (e) {
        let val = $(this).val();
        let tot = obtenerTotalFacturaSinDescuentoFactura();
        let prcdesc = (val * 100) / +tot;
        $("#descxa").val(prcdesc.toFixed(2));
        funcion_descuento_factura(false);
    });
    document.getElementById("descxa").addEventListener("input", function (e) {
        funcion_descuento_factura();
    });
    $("#id_factura_venta").change(function (e) {
        if ($(this).val() != "") {
            $("#nro_factura_retencion").val($("#num_factura").val());
        } else {
            $("#nro_factura_retencion").val("");
        }
    });

    $.getScript("../apertura_caja/apertura_ui_util/apertura.js", function () {
        aperturaForm = new AperturaForm();
        aperturaForm.contenedor = $("#conteiner_apertura");
        aperturaForm.init();
        aperturaForm.estaCajaAbierta().then(rs => {
            if (rs == 1) {
                cajaAbierta.value = true;
            } else {
                cajaAbierta.value = false;
            }
        })
        aperturaForm.onGuardarApertura = function (param) {
            if (param > 0) {
                var myWindow = window.open("../../reportes/apertura_caja_ant.php?id=" + param, '_blank');
                myWindow.focus();
                myWindow.print();
                aperturaForm.estaCajaAbierta().then(rs => {
                    if (rs == 1) {
                        cajaAbierta.value = true;
                        location.reload();
                    } else {
                        cajaAbierta.value = false;
                    }
                })
            }
        }
    });

    llenarCentrosCosto();
    $("#btnActualizarClave").on("click", actualizar_clave);
    iniDialogValoresNotasC();

    $("#venta_iva_1").keyup(function (e) {
        if (e.key == 'Enter') {
            return;
        }
        let precioci = Number(e.target.value);
        let preciosi = precioci / (1 + (calculoIVA / 100));
        if ($("#iva_producto").val() == 'Si') {
            $("#p_venta").val(preciosi);
        } else {
            $("#p_venta").val(precioci);
        }
    });



    $("[data-mask]").inputmask();
    $("#fecha_vencimiento").hide();
    $("#formaspago_mixto_reten").on("change", function () {
        cargar_cuentas_reten();
    });
    $("#formaspago_mixto").on("change", function () {
        if (
                $("#formaspago_mixto").val() == "Credito" ||
                $("#formaspago_mixto").val() == "CPosfechado"
                ) {
            $("#cuenta_contable").attr("disabled", true);
            $("#fecha_vencimiento").show();
            $("#idCuenta").val("");
            $("#btnCuenta").attr("disabled", true);
            $("#num_tarjeta").attr("disabled", false);
            //            $("#cheque_tarjeta").attr("disabled", false);
            //            $("#banco").attr("disabled", false);
        } else if ($("#formaspago_mixto").val() == "Transferencias") {
            $("#cuenta_contable").attr("disabled", false);
            $("#btnCuenta").attr("disabled", false);
            $("#cuenta_contable").val("");
            $("#idCuenta").val("");
            $("#fecha_vencimiento").hide();
            $("#num_tarjeta").attr("disabled", false);
            //            $("#cheque_tarjeta").attr("disabled", true);
            //            $("#banco").attr("disabled", true);
        } else if (
                $("#formaspago_mixto").val() == "Contado" ||
                $("#formaspago_mixto").val() == "Cheque" ||
                $("#formaspago_mixto").val() == "Cupon" ||
                $("#formaspago_mixto").val() == "TCredito"
                ) {
            $("#cuenta_contable").attr("disabled", true);
            $("#btnCuenta").attr("disabled", true);
            $("#cuenta_contable").val("");
            $("#idCuenta").val("");
            $("#fecha_vencimiento").hide();
            $("#num_tarjeta").attr("disabled", false);
            //            $("#cheque_tarjeta").attr("disabled", true);
            //            $("#banco").attr("disabled", true);
        } else if ($("#formaspago_mixto").val() == "NOTA_CREDITO") {
            $("#cuenta_contable").attr("disabled", true);
            $("#btnCuenta").attr("disabled", true);
            $("#cuenta_contable").val("");
            $("#idCuenta").val("");
            $("#fecha_vencimiento").hide();
            $("#buscar_val_nc").dialog("open");
            $("#num_tarjeta").attr("disabled", true);
        }
    });
    $("#cuentas").dialog(dialogo_cuenta);
    $("#cuentas_reten").dialog(dialogo_cuenta);
    $("#btnCuenta").on("click", abrirCuenta);
    $("#btnCuenta_reten").on("click", abrirCuenta_reten);
    buscar_servicio_producto();
    buscar_bienservicio_producto();
    buscar_servicio_iva();
    buscar_bienservicio_producto_iva();
    // Catgar Num nota venta
    mostrar_num_doc();
    $("#tipo_venta").change((e) => {
        comprabarNroFactura();
        mostrar_num_doc();

    });
    //    if ($("#num_oculto_reten").val() == "") {
    //        $("#serie_retencion").val("");
    //    } else {
    //        var str = $("#num_oculto_reten").val();
    //        var res = parseInt(str.substr(8, 16));
    //        res = res + 1;
    //
    //        $("#serie_retencion").val(res);
    //        var a = autocompletar_reten(res);
    //        var validado = a + "" + res;
    //        $("#serie_retencion").val(validado);
    //    }
    //    $("#ruc_ci").change(function () {
    //        if ($("#id_cliente").val() == 1 || $("#id_cliente").val() == "") {
    //            if ($("#ruc_ci").val() != "") {
    //                // verificar si esxiste cliente
    //                $.ajax({
    //                    type: "POST",
    //                    url: "comparar_cedulas.php",
    //                    data: "cedula=" + $("#ruc_ci").val(),
    //                    success: function (data) {
    //                        var val = data;
    //                        if (val == 1) {
    //                            $("#ruc_ci").val("");
    //                            $("#ruc_ci").focus();
    //                            alertify.error("Error... El cliente esta registrado");
    //                        } else {
    //                            $("#direccion_cliente").val("");
    //                            $("#nombre_cliente").val("");
    //                            $("#telefono_cliente").val("");
    //                            $("#correo").val("");
    //                            $("#id_cliente").val("");
    //                            if (
    //                                    $("#ruc_ci").val().length != 10 &&
    //                                    $("#ruc_ci").val().length !== 13
    //                                    ) {
    //                                alertify.error("Error... Ingrese una Identificación valida");
    //                            } else {
    //                                $("#direccion_cliente").attr("");
    //                                $("#telefono_cliente").attr("");
    //                                $("#correo").attr("");
    //                                // validar cedula ruc
    //                                var numero = $("#ruc_ci").val();
    //                                var suma = 0;
    //                                var residuo = 0;
    //                                var pri = false;
    //                                var pub = false;
    //                                var nat = false;
    //                                var modulo = 11;
    //                                var p1;
    //                                var p2;
    //                                var p3;
    //                                var p4;
    //                                var p5;
    //                                var p6;
    //                                var p7;
    //                                var p8;
    //                                var p9;
    //                                /* Aqui almacenamos los digitos de la cedula en variables. */
    //                                var d1 = numero.substr(0, 1);
    //                                var d2 = numero.substr(1, 1);
    //                                var d3 = numero.substr(2, 1);
    //                                var d4 = numero.substr(3, 1);
    //                                var d5 = numero.substr(4, 1);
    //                                var d6 = numero.substr(5, 1);
    //                                var d7 = numero.substr(6, 1);
    //                                var d8 = numero.substr(7, 1);
    //                                var d9 = numero.substr(8, 1);
    //                                var d10 = numero.substr(9, 1);
    //                                /* El tercer digito es: */
    //                                /* 9 para sociedades privadas y extranjeros   */
    //                                /* 6 para sociedades publicas */
    //                                /* menor que 6 (0,1,2,3,4,5) para personas naturales */
    //
    //                                if (d3 < 6) {
    //                                    nat = true;
    //                                    p1 = d1 * 2;
    //                                    if (p1 >= 10)
    //                                        p1 -= 9;
    //                                    p2 = d2 * 1;
    //                                    if (p2 >= 10)
    //                                        p2 -= 9;
    //                                    p3 = d3 * 2;
    //                                    if (p3 >= 10)
    //                                        p3 -= 9;
    //                                    p4 = d4 * 1;
    //                                    if (p4 >= 10)
    //                                        p4 -= 9;
    //                                    p5 = d5 * 2;
    //                                    if (p5 >= 10)
    //                                        p5 -= 9;
    //                                    p6 = d6 * 1;
    //                                    if (p6 >= 10)
    //                                        p6 -= 9;
    //                                    p7 = d7 * 2;
    //                                    if (p7 >= 10)
    //                                        p7 -= 9;
    //                                    p8 = d8 * 1;
    //                                    if (p8 >= 10)
    //                                        p8 -= 9;
    //                                    p9 = d9 * 2;
    //                                    if (p9 >= 10)
    //                                        p9 -= 9;
    //                                    modulo = 10;
    //                                } else if (d3 == 6) {
    //                                    pub = true;
    //                                    p1 = d1 * 3;
    //                                    p2 = d2 * 2;
    //                                    p3 = d3 * 7;
    //                                    p4 = d4 * 6;
    //                                    p5 = d5 * 5;
    //                                    p6 = d6 * 4;
    //                                    p7 = d7 * 3;
    //                                    p8 = d8 * 2;
    //                                    p9 = 0;
    //                                } else if (d3 == 9) {
    //                                    pri = true;
    //                                    p1 = d1 * 4;
    //                                    p2 = d2 * 3;
    //                                    p3 = d3 * 2;
    //                                    p4 = d4 * 7;
    //                                    p5 = d5 * 6;
    //                                    p6 = d6 * 5;
    //                                    p7 = d7 * 4;
    //                                    p8 = d8 * 3;
    //                                    p9 = d9 * 2;
    //                                }
    //
    //                                suma = p1 + p2 + p3 + p4 + p5 + p6 + p7 + p8 + p9;
    //                                residuo = suma % modulo;
    //                                var digitoVerificador = residuo == 0 ? 0 : modulo - residuo;
    //                                if (numero.length === 10) {
    //                                    if (nat == true) {
    //                                        if (digitoVerificador != d10) {
    //                                            alertify.error("El número de cédula es incorrecto.");
    //                                            $("#direccion_cliente").attr("disabled", "disabled");
    //                                            $("#telefono_cliente").attr("disabled", "disabled");
    //                                            $("#correo").attr("disabled", "disabled");
    //                                        } else {
    //                                            if ($("#ruc_ci").val() == "0000000000") {
    //                                                alertify.error("El número de cédula es incorrecto.");
    //                                                $("#direccion_cliente").attr("disabled", "disabled");
    //                                                $("#telefono_cliente").attr("disabled", "disabled");
    //                                                $("#correo").attr("disabled", "disabled");
    //                                            } else {
    //                                                alertify.success("El número de cédula es correcto.");
    //                                                $("#nombre_cliente").val("");
    //                                                $("#direccion_cliente").val("");
    //                                                $("#telefono_cliente").val("");
    //                                                $("#correo").val("");
    //                                                $("#id_cliente").val("");
    //                                                $("#nombre_cliente").focus();
    //                                                $("#direccion_cliente").removeAttr("disabled");
    //                                                $("#telefono_cliente").removeAttr("disabled");
    //                                                $("#correo").removeAttr("disabled");
    //                                            }
    //                                        }
    //                                    }
    //                                } else {
    //                                    var ruc = numero.substr(10, 13);
    //                                    var digito3 = numero.substring(2, 3);
    //                                    if (ruc == "001") {
    //                                        if (digito3 < 6) {
    //                                            if (nat == true) {
    //                                                if (digitoVerificador != d10) {
    //                                                    alertify.error("El ruc persona natural1 es incorrecto.");
    //                                                    $("#direccion_cliente").attr("disabled", "disabled");
    //                                                    $("#telefono_cliente").attr("disabled", "disabled");
    //                                                    $("#correo").attr("disabled", "disabled");
    //                                                } else {
    //                                                    alertify.success("El ruc persona natural1 es correcto.");
    //                                                    $("#nombre_cliente").val("");
    //                                                    $("#direccion_cliente").val("");
    //                                                    $("#telefono_cliente").val("");
    //                                                    $("#correo").val("");
    //                                                    $("#id_cliente").val("");
    //                                                    $("#nombre_cliente").focus();
    //                                                    $("#direccion_cliente").removeAttr("disabled");
    //                                                    $("#telefono_cliente").removeAttr("disabled");
    //                                                    $("#correo").removeAttr("disabled");
    //                                                }
    //                                            }
    //                                        } else {
    //                                            if (digito3 == 6) {
    //                                                if (pub == true) {
    //                                                    if (digitoVerificador != d9) {
    //                                                        validarIdentificacion($("#ruc_ci"), "ruc",
    //                                                                function () {
    //                                                                    alertify.success("El ruc público es correcto.");
    //                                                                    $("#nombre_cliente").val("");
    //                                                                    $("#direccion_cliente").val("");
    //                                                                    $("#telefono_cliente").val("");
    //                                                                    $("#correo").val("");
    //                                                                    $("#id_cliente").val("");
    //                                                                    $("#nombre_cliente").focus();
    //                                                                    $("#direccion_cliente").removeAttr("disabled");
    //                                                                    $("#telefono_cliente").removeAttr("disabled");
    //                                                                    $("#correo").removeAttr("disabled");
    //                                                                },
    //                                                                function () {
    //                                                                    alertify.error("El ruc público es incorrecto.");
    //                                                                    $("#direccion_cliente").attr("disabled", "disabled");
    //                                                                    $("#telefono_cliente").attr("disabled", "disabled");
    //                                                                    $("#correo").attr("disabled", "disabled");
    //                                                                });
    //
    //
    //                                                    } else {
    //                                                        alertify.success("El ruc público es correcto.");
    //                                                        $("#nombre_cliente").val("");
    //                                                        $("#direccion_cliente").val("");
    //                                                        $("#telefono_cliente").val("");
    //                                                        $("#correo").val("");
    //                                                        $("#id_cliente").val("");
    //                                                        $("#nombre_cliente").focus();
    //                                                        $("#direccion_cliente").removeAttr("disabled");
    //                                                        $("#telefono_cliente").removeAttr("disabled");
    //                                                        $("#correo").removeAttr("disabled");
    //                                                    }
    //                                                }
    //                                            } else {
    //                                                if (digito3 == 9) {
    //                                                    if (pri == true) {
    //                                                        if (digitoVerificador != d10) {
    //                                                            //TODO validar ruc
    //                                                            validarIdentificacion($("#ruc_ci"), "ruc",
    //                                                                    function () {
    //                                                                        alertify.success("El ruc privado es correcto.");
    //                                                                        $("#nombre_cliente").val("");
    //                                                                        $("#direccion_cliente").val("");
    //                                                                        $("#telefono_cliente").val("");
    //                                                                        $("#correo").val("");
    //                                                                        $("#id_cliente").val("");
    //                                                                        $("#nombre_cliente").focus();
    //                                                                        $("#direccion_cliente").removeAttr("disabled");
    //                                                                        $("#telefono_cliente").removeAttr("disabled");
    //                                                                        $("#correo").removeAttr("disabled");
    //                                                                    },
    //                                                                    function () {
    //                                                                        alertify.error("El ruc privado es incorrecto.");
    //                                                                        $("#direccion_cliente").attr("disabled", "disabled");
    //                                                                        $("#telefono_cliente").attr("disabled", "disabled");
    //                                                                        $("#correo").attr("disabled", "disabled");
    //                                                                    });
    //                                                        } else {
    //                                                            alertify.success("El ruc privado es correcto.");
    //                                                            $("#nombre_cliente").val("");
    //                                                            $("#direccion_cliente").val("");
    //                                                            $("#telefono_cliente").val("");
    //                                                            $("#correo").val("");
    //                                                            $("#id_cliente").val("");
    //                                                            $("#nombre_cliente").focus();
    //                                                            $("#direccion_cliente").removeAttr("disabled");
    //                                                            $("#telefono_cliente").removeAttr("disabled");
    //                                                            $("#correo").removeAttr("disabled");
    //                                                        }
    //                                                    }
    //                                                } else {
    //                                                    if (d3 == 7 || d3 == 8) {
    //                                                        alertify.error(
    //                                                                "El tercer dígito ingresado es inválido"
    //                                                                );
    //                                                    } else {
    //                                                        if (numero.substr(10, 3) != "001") {
    //                                                            alertify.error(
    //                                                                    "El ruc de la empresa del sector privado debe terminar con 001"
    //                                                                    );
    //                                                        }
    //                                                    }
    //                                                }
    //                                            }
    //                                        }
    //                                    } else {
    //                                        if (numero.length == 13) {
    //                                            alertify.error("El ruc es incorrecto.");
    //                                            $("#direccion_cliente").attr("disabled", "disabled");
    //                                            $("#telefono_cliente").attr("disabled", "disabled");
    //                                            $("#correo").attr("disabled", "disabled");
    //                                        }
    //                                    }
    //                                }
    //                            }
    //                        }
    //                    },
    //                });
    //                //           nuevo_cliente();
    //            }
    //        }
    //    });
    /*$("#ruc_ci_bene").autocomplete({
     source: "buscar_beneficiario.php",
     minLength: 1,
     focus: function (event, ui) {
     $("#ruc_ci_bene").val(ui.item.value);
     $("#id_beneficiario").val(ui.item.id_beneficiario);
     $("#nombre_cliente_bene").val(ui.item.nombre_cliente_bene);
     
     return false;
     },
     select: function (event, ui) {
     $("#ruc_ci_cli").val(ui.item.value);
     $("#id_beneficiario").val(ui.item.id_beneficiario);
     $("#nombre_cliente_bene").val(ui.item.nombre_cliente_bene);
     return false;
     }
     
     }).data("ui-autocomplete")._renderItem = function (ul, item) {
     return $("<li>")
     .append("<a>" + item.value + "</a>")
     .appendTo(ul);
     };*/
    // fin

    // buscar clientes nombres
    /*$("#nombre_cliente_bene").autocomplete({
     source: "buscar_beneficiario_nombre.php",
     minLength: 1,
     focus: function (event, ui) {
     $("#nombre_cliente_bene").val(ui.item.value);
     $("#id_beneficiario").val(ui.item.id_beneficiario);
     $("#ruc_ci_bene").val(ui.item.ruc_ci_bene);
     
     return false;
     },
     select: function (event, ui) {
     $("#nombre_cliente_bene").val(ui.item.value);
     $("#id_beneficiario").val(ui.item.id_beneficiario);
     $("#ruc_ci_bene").val(ui.item.ruc_ci_bene);
     
     return false;
     }
     }).data("ui-autocomplete")._renderItem = function (ul, item) {
     return $("<li>")
     .append("<a>" + item.value + "</a>")
     .appendTo(ul);
     };*/
    // fin
    $("#tabpunto_3").dialog(dialogo9);
    $("#punto_ventaini").focus();
    $("#tab_3").prop("disabled", true);
    $.ajax({
        type: "POST",
        url: "buscar_iva.php",
        data: "",
        success: function (data) {
            var val = data;
            if (val != 1) {
                calculoIVA = val;
            }
        },
    });
    $.ajax({
        type: "POST",
        url: "../../procesos/buscar_p_emision.php",
        data: "",
        success: function (data) {
            var val = data;
            if (val != 1) {
                $("#buscar_pv").val(val);
                $("#digitador").val("P.E." + "  " + val);
            }
        },
    });
    $("#formaspago").change(function (e) {
        porcentaje();
    });
    ///////////////////////////////////


    $("#p_venta").keyup(function (e) {

        if ($("#p_venta").val() == "") {
            $("#venta_iva").val("0.00");
        }



        if ($("#iva_producto").val() == "Si") {
            if ($("#p_venta").val() == "") {
                $("#venta_iva").val("0.00");
            }
            $("#venta_iva").val("");
            var iva1 = ($("#p_venta").val() * calculoIVA) / 100;
            var iva_pventa = iva1 + parseFloat($("#p_venta").val());
            var total_iva_cantidad =
                    numFormatter(2).format(iva_pventa) * $("#cantidad").val();
            $("#venta_iva").val(numFormatter(2).format(total_iva_cantidad));
        } else {
            $("#venta_iva").val("0.00");
        }
        var cantidad = parseFloat($("#cantidad").val());
        var filas = jQuery("#list").jqGrid("getRowData");
        for (var i = 0; i < filas.length; i++) {
            var id = filas[i];
            if (id["cod_producto"] == $("#cod_producto").val()) {
                var repe = 1;
                var can = id["cantidad"];
            }
        }

        let preciosi = Number(this.value);
        let precioci = preciosi * (1 + (calculoIVA / 100));
        if ($("#iva_producto").val() == 'Si') {
            $("#venta_iva_1").val(precioci);
        } else {
            $("#venta_iva_1").val(preciosi);
        }
    });

    //////////////////////////////////77
    $("#cantidad").keyup(function () {
        if ($("#p_venta").val() == "") {
            $("#venta_iva").val("0.00");
        }
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
        //        var cantidad = parseFloat($("#cantidad").val());
        //        var filas = jQuery("#list").jqGrid("getRowData");
        //        for (var i = 0; i < filas.length; i++) {
        //            var id = filas[i];
        //            if (id["cod_producto"] == $("#cod_producto").val()) {
        //                var repe = 1;
        //                var can = id["cantidad"];
        //            }
        //        }
        $.ajax({
            type: "POST",
            url: "buscar_cant_mayo_nego.php",
            data: "id=" + $("#codigo").val(),
            success: function (data) {
                var val = data;
                if (val != "") {
                    var valores;
                    valores = val.split(",");
                    var numericaMayo = 0;
                    numericaMayo = parseInt(valores[1]);
                    console.log("numericMayo" + numericaMayo);

                    var numericaNego = 0;
                    numericaNego = parseInt(valores[2]);
                    console.log("numericaNego" + numericaNego);
                    var cantidad = parseInt($("#cantidad").val());
                    var filas = jQuery("#list").jqGrid("getRowData");
                    var can = 0;
                    for (var i = 0; i < filas.length; i++) {
                        var id = filas[i];
                        if (id['cod_producto'] == $("#cod_producto").val()) {
                            var repe = 1;
                            can = id['cantidad'];
                        }
                    }
                    if (numericaMayo > 0 && numericaNego > 0) {
                        if (repe == 1) {
                            var suma = parseFloat(can) + parseFloat($("#cantidad").val());
                            suma = Number(suma.toFixed(2));
                            if (suma < numericaMayo && suma < numericaNego) {
                                console.log("< < mino1" + suma);
                                $("#p_venta").val("");
                                $("#mino").prop("selected", true);
                                mayorista();
                            } else if (suma >= numericaMayo && suma < numericaNego) {
                                console.log("> < mayo1" + suma);
                                $("#p_venta").val("");
                                $("#mayo").prop("selected", true);
                                mayorista();
                            } else if (suma >= numericaMayo && suma >= numericaNego) {
                                console.log("> > nego1" + suma);
                                $("#p_venta").val("");
                                $("#nego").prop("selected", true);
                                mayorista();
                            }
                        } else {
                            if (cantidad < numericaMayo && cantidad < numericaNego) {
                                console.log("3 MINO" + cantidad);
                                $("#p_venta").val("");
                                $("#mino").prop("selected", true);
                                mayorista();

                            } else if (cantidad >= numericaMayo && cantidad < numericaNego) {

                                console.log("2 MAYO" + cantidad);
                                $("#p_venta").val("");
                                $("#mayo").prop("selected", true);
                                mayorista();
                            } else if (cantidad >= numericaMayo && cantidad >= numericaNego) {
                                console.log("1 NEGO" + cantidad);
                                $("#p_venta").val("");
                                $("#nego").prop("selected", true);
                                mayorista();
                            }
                        }
                    } else if (numericaMayo > 0) {

                        if (repe == 1) {
                            var suma = parseFloat(can) + parseFloat($("#cantidad").val());
                            suma = Number(suma.toFixed(2));
                            if (suma < numericaMayo) {
                                console.log("< < mino11:" + suma);
                                $("#p_venta").val("");
                                $("#mino").prop("selected", true);
                                mayorista();
                            } else if (suma >= numericaMayo) {
                                console.log("> < mayo11:" + suma);
                                $("#p_venta").val("");
                                $("#mayo").prop("selected", true);
                                mayorista();
                            }
                        } else {
                            if (cantidad < numericaMayo) {
                                console.log("3 MINO1:" + cantidad);
                                $("#p_venta").val("");
                                $("#mino").prop("selected", true);
                                mayorista();

                            } else if (cantidad >= numericaMayo) {

                                console.log("2 MAYO1:" + cantidad);
                                $("#p_venta").val("");
                                $("#mayo").prop("selected", true);
                                mayorista();
                            }
                        }






                    }
                }
            }
        });

        obtenerDescuentoProducto($("#codigo_barras").val()).then(data => {
            if (!!data.descuento) {
                var filas = jQuery("#list").jqGrid("getRowData");
                let cant = Number($("#cantidad").val());
                if (filas.length > 0) {
                    filas.forEach(el => {
                        cant += Number(el.cantidad);
                    });

                }
                if (Number(cant) >= data.cantidad_descuento) {
                    $("#descuento").val(data.descuento);
                } else {
                    $("#descuento").val("0");
                }
                console.log(filas);
            }
        });
    });
    alertify.set({
        delay: 6000,
    });
    show();
    // cambiar idioma
    $.datepicker.regional["es"] = {
        closeText: "Cerrar",
        prevText: "<Ant",
        nextText: "Sig>",
        currentText: "Hoy",
        monthNames: [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre",
        ],
        monthNamesShort: [
            "Ene",
            "Feb",
            "Mar",
            "Abr",
            "May",
            "Jun",
            "Jul",
            "Ago",
            "Sep",
            "Oct",
            "Nov",
            "Dic",
        ],
        dayNames: [
            "Domingo",
            "Lunes",
            "Martes",
            "Miércoles",
            "Jueves",
            "Viernes",
            "Sábado",
        ],
        dayNamesShort: ["Dom", "Lun", "Mar", "Mié", "Juv", "Vie", "Sáb"],
        dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
        weekHeader: "Sm",
        dateFormat: "dd/mm/yy",
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: "",
    };
    $.datepicker.setDefaults($.datepicker.regional["es"]);
    // fin
    function combo(tipo) {
        $.ajax({
            type: "POST",
            url: "buscar_producto9.php?tipo_precio=" + tipo,
            success: function (resp) {
                combo_1 = JSON.parse(resp);
            },
        });
        return combo_1;
    }

    function combo1(tipo) {
        $.ajax({
            type: "POST",
            url: "buscar_producto10.php?tipo_precio=" + tipo + "&articulo=" + $("#producto").val(),
            success: function (resp) {
                combo_2 = JSON.parse(resp);
            },
        });
        return combo_2;
    }
    //    if ($("#num_oculto").val() == "") {
    //        $("#num_factura").val("");
    //    } else {
    //        var str = $("#num_oculto").val();
    //        var res = parseInt(str.substr(4, 16));
    //        res = res + 1;
    //
    //        $("#num_factura").val(res);
    //        var a = autocompletar(res);
    //        var validado = a + "" + res;
    //        $("#num_factura").val(validado);
    //    }
    if ($("#num_oculto_guia").val() == "") {
        $("#num_serie_guia").val("");
    } else {
        var str = $("#num_oculto_guia").val();
        var res = parseInt(str.substr(4, 16));
        res = res + 1;
        $("#num_serie_guia").val(res);
        var a = autocompletar_guia(res);
        var validado = a + "" + res;
        $("#num_serie_guia").val(validado);
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
    $("#btnEstados").click(function () {
        $("#list7").setGridParam({
            url: 'xmlBuscarEstados.php?estado_fac=general',
            page: 1
        }).trigger("reloadGrid");
        $("#buscar_estados").dialog("open");
    });
    $("#btnEstados2").click(function () {
        $("#list7").setGridParam({
            url: 'xmlBuscarEstados.php?estado_fac=no_enviado_correo',
            page: 1
        }).trigger("reloadGrid");
        $("#buscar_estados").dialog("open");
    });
    $("#btnGuardar").click(function (e) {
        e.preventDefault();
    });
    $("#btnActualizar").click(function (e) {
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
    $("#btnImprimirguia").click(function (e) {
        e.preventDefault();
    });
    $("#btnAtras").click(function (e) {
        e.preventDefault();
    });
    $("#btnAdelante").click(function (e) {
        e.preventDefault();
    });
    $("#btnSeleccion").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardarRetenciones").click(function (e) {
        e.preventDefault();
    });
    $("#btnCancelarRetenciones").click(function (e) {
        e.preventDefault();
    });
    $("#btnPuntoventa").click(function (e) {
        e.preventDefault();
    });
    $("#btnBuscarp").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardarRetenciones").on("click", guardar_retenciones_factura_venta);
    $("#btnCancelarRetenciones").on("click", function (e) {
        location.reload();
    });
    $("#num_liquidacion").on("keypress", enter_liqui);
    $("#btnBuscarp").on("click", abrirDialogop);
    $("#btncargar").on("click", abrirDialogo);
    $("#btnAgregar").on("click", agregar);
    //$("#btnGuardarSeries").on("click", guardar_serie);
    $("#btnGuardar").on("click", ingresar_cambio);
    //    $("#btnGuardar").on("click", ingresar_cambio);
    //    $("#btnGuardarTemporal").on("click", guardar_factura_temporal);
    //    $("#btnModificar").on("click", modificar_factura);
    $("#btnNuevo").on("click", limpiar_factura);
    $("#btnAnular").on("click", anular_factura);
    $("#btnAceptar").on("click", function () {
        if (loadingAnular) {
            return;
        }
        setTimeout(function () {
            aceptar();
        }, 100);
    });
    $("#btnSalir").on("click", cancelar);
    $("#btnAcceder").on("click", validar_acceso);
    $("#btnCancelar").on("click", cancelar_acceso);
    $("#btnGuardarV").on("click", guardar_factura);
    $("#btnCancelarV").on("click", cancelar_cambio);
    //    $("#btnActualizar").on("click", actualizar_vendedor);
    $("#btnSeleccion").on("click", seleccion_row);
    $("#btnPuntoventa").on("click", agregar_punto_venta);
    $("#btnAtras").on("click", flecha_atras);
    $("#btnAdelante").on("click", flecha_siguiente);
    //    $("#btnGuardarRetenciones").on("click", guardar_retenciones_factura_venta);
    $("#btnCancelarRetenciones").on("click", function (e) {
        location.reload();
    });
    $("#ruc_ci_bene").on("keyup", limpiar_campo5);
    $("#ruc_ci").on("keyup", limpiar_campo);
    $("#codigo").on("keyup", limpiar_campo3);
    $("#producto").on("keyup", limpiar_campo4);
    $("#codigo").on("keypress", enter);
    $("#producto").on("keypress", enter);
    $("#cantidad").on("keypress", enter);
    $("#p_venta").on("keypress", enterpvsi);
    $("#venta_iva_1").on("keypress", enterpvpf);
    $("#descuento").on("keypress", enter2);
    //    $("#descripocion_prod").on("keypress", enter2);
    $("#num_factura").on("keypress", enter3);
    $("#ruc_ci").on("keypress", enter4);
    $("#nombre_cliente").on("keypress", enter5);
    $("#direccion_cliente").on("keypress", enter5);
    $("#telefono_cliente").on("keypress", enter6);
    $("#valor_recibo").on("keypress", enter7);
    //    $("#btnGuardarV").on("keypress", enter8);
    //    $("#btnGuardarV").on("keypress", enter9);
    $("#valor_cambio").on("keyup", enter9);
    //    $("#direccion_cliente").attr("disabled", "disabled");


    //    $("#p_venta").attr("disabled", "disabled");
    //    $("#venta_iva_1").attr("disabled", "disabled");


    $("#tarjetas").attr("disabled", "disabled");
    //  $("#telefono_cliente").attr("disabled", "disabled");
    //  $("#correo").attr("disabled", "disabled");
    $("#btnAnular").attr("disabled", true);
    $("#telefono_cliente").validCampoFranz("0123456789");
    $("#telefono_cliente").attr("maxlength", "10");
    $("#ruc_ci").attr("maxlength", "13");
    $("#productos").dialog(dialogos);
    $("#series").dialog(dialogo);
    $("#buscar_facturas_venta").dialog(dialogo2);
    $("#buscar_proformas").dialog(dialogo5);
    $("#buscar_proformas_tecnico").dialog(dialogotecnico);
    $("#clave_permiso").dialog(dialogo3);
    $("#valor_cambioid").dialog(dialogo8);
    $("#seguro").dialog(dialogo4);
    $("#buscar_notas_venta").dialog(dialogo6);
    $("#buscar_estados").dialog(dialogo1010);
    $("#tipo_busqueda").dialog(dialogo7);
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
    $("#calculoRetencionF").on("keypress", enter77);
    $("#calculoRetencionFS").on("keypress", enter77);
    $("#calculoRetencionI").on("keypress", enter77);
    $("#calculoRetencionIs").on("keypress", enter77);
    $("#btnImprimir").click(function () {
        if ($("#tipo_venta").val() == "FACTURA") {
            $.ajax({
                type: "POST",
                url: "../../procesos/validacion.php",
                data:
                        "comprobante=" +
                        $("#comprobante").val() +
                        "&tabla=" +
                        "factura_venta" +
                        "&id_tabla=" +
                        "id_factura_venta" +
                        "&tipo=" +
                        1,
                success: function (data) {
                    var val = data;
                    if (val != "") {
                        var myWindow = window.open(
                                formatoFactura + "?hoja=A4&id=" + $("#comprobante").val(),
                                "_blank"
                                );
                        myWindow.focus();
                        myWindow.print();
                    }
                },
            });

        } else if (
                $("#tipo_venta").val() == "NOTA" &&
                $("#comprobante").val() != ""
                ) {
            $.ajax({
                type: "POST",
                url: "../../procesos/validacion.php",
                data:
                        "comprobante=" +
                        $("#comprobante").val() +
                        "&tabla=" +
                        "facturas_novalidas" +
                        "&id_tabla=" +
                        "id_facturas_novalidas" +
                        "&tipo=" +
                        1,
                success: function (data) {
                    var val = data;
                    if (val != "") {
                        var myWindow = window.open(
                                formatoNotaVenta + "?hoja=A4&id=" +
                                $("#comprobante").val(),
                                "_blank"
                                );
                        myWindow.focus();
                        myWindow.print();
                    }
                },
            });
        } else {
            alertify.error("Comprobante invalido");
        }

    });
    $("#btnBuscar").click(function () {
        $("#tipo_busqueda").dialog("open");
    });
    $("#btnTipoBuscar").click(function () {
        if ($("#tipo_venta_busqueda").val() == "FACTURA") {
            $("#list2").trigger("reloadGrid");
            $("#buscar_facturas_venta").dialog("open");
        } else {
            if ($("#tipo_venta_busqueda").val() == "NOTA") {
                $("#list5").trigger("reloadGrid");
                $("#buscar_notas_venta").dialog("open");
            }
        }
    });
    $("#btnProforma").click(function () {
        $("#buscar_proformas").dialog("open");
    });
    $("#btnMantenimiento").click(function () {
        $("#list6").trigger("reloadGrid");
        $("#buscar_proformas_tecnico").dialog("open");
    });
    // para precio
    $("#p_venta").on("keypress", punto);
    $("#precio").on("keypress", punto);
    $("#adelanto").on("keypress", punto);
    // FIN

    function mayorista() {
        var precio = $("#tipo_precio").val();
        var codigo = $("#codigo_barras").val();
        var cod = $("#codigo_barras").val();
        if (precio == "MINORISTA") {
            $.getJSON(
                    "search.php?codigo_barras=" +
                    codigo +
                    "&precio=" +
                    precio +
                    "&cod=" +
                    cod,
                    function (data) {
                        var tama = data.length;
                        if (tama != 0) {
                            for (var i = 0; i < tama; i = i + 11) {
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
                                  $("#precio").val(data[i + 10]);
                                if ($("#iva_producto").val() == "Si") {
                                    $("#venta_iva").val("");
                                    var iva1 = ($("#p_venta").val() * calculoIVA) / 100;
                                    var iva_pventa = iva1 + parseFloat($("#p_venta").val());
                                    $("#venta_iva").val(numFormatter(2).format(iva_pventa));
                                } else {
                                    $("#venta_iva").val("");
                                }
                                //                        $("#cantidad").val("1");
                                //                        $("#cantidad").select();
                            }
                        } else {
                            $("#codigo").val("");
                            $("#producto").val("");
                            $("#p_venta").val("");
                            $("#venta_iva").val("");
                            //                    $("#descuento").val("");
                            $("#disponibles").val("");
                            $("#iva_producto").val("");
                            $("#carga_series").val("");
                            $("#cod_producto").val("");
                            $("#des").val("");
                            $("#inventar").val("");
                            $("#incluye").val("");
                            alertify.error("Producto no ingresado");
                            $("#codigo_barras").val("");
                            $("#venta_iva_1").val("");
                        }
                    }
            );
        } else {
            if (precio == "MAYORISTA") {
                $.getJSON(
                        "search.php?codigo_barras=" + codigo + "&precio=" + precio,
                        function (data) {
                            var tama = data.length;
                            if (tama != 0) {
                                for (var i = 0; i < tama; i = i + 11) {
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
                                     $("#precio").val(data[i + 10]);
                                    //                            $("#cantidad").val("1");
                                    //                            $("#cantidad").select();
                                    if ($("#iva_producto").val() == "Si") {
                                        $("#venta_iva").val("");
                                        var iva1 = ($("#p_venta").val() * calculoIVA) / 100;
                                        var iva_pventa = iva1 + parseFloat($("#p_venta").val());
                                        $("#venta_iva").val(numFormatter(2).format(iva_pventa));
                                    } else {
                                        $("#venta_iva").val("");
                                    }
                                }
                            } else {
                                $("#codigo").val("");
                                $("#producto").val("");
                                $("#p_venta").val("");
                                $("#venta_iva").val("");
                                //                        $("#descuento").val("");
                                $("#disponibles").val("");
                                $("#iva_producto").val("");
                                $("#carga_series").val("");
                                $("#cod_producto").val("");
                                $("#des").val("");
                                $("#inventar").val("");
                                $("#incluye").val("");
                                alertify.error("Producto no ingresado");
                                $("#codigo_barras").val("");
                                $("#venta_iva_1").val("");
                            }
                        }
                );
            } else {
                if (precio == "NEGOCIO") {
                    $.getJSON(
                            "search.php?codigo_barras=" + codigo + "&precio=" + precio,
                            function (data) {
                                var tama = data.length;
                                if (tama != 0) {
                                    for (var i = 0; i < tama; i = i + 11) {
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
                                         $("#precio").val(data[i + 10]);
                                        if ($("#iva_producto").val() == "Si") {
                                            $("#venta_iva").val("");
                                            var iva1 = ($("#p_venta").val() * calculoIVA) / 100;
                                            var iva_pventa = iva1 + parseFloat($("#p_venta").val());
                                            $("#venta_iva").val(numFormatter(2).format(iva_pventa));
                                        } else {
                                            $("#venta_iva").val("");
                                        }
                                        //                            $("#cantidad").val("1");
                                        //                            $("#cantidad").select();
                                    }
                                } else {
                                    $("#codigo").val("");
                                    $("#producto").val("");
                                    $("#p_venta").val("");
                                    $("#venta_iva").val("");
                                    //                            $("#descuento").val("");
                                    $("#disponibles").val("");
                                    $("#iva_producto").val("");
                                    $("#carga_series").val("");
                                    $("#cod_producto").val("");
                                    $("#des").val("");
                                    $("#inventar").val("");
                                    $("#incluye").val("");
                                    alertify.error("Producto no ingresado");
                                    $("#codigo_barras").val("");
                                    $("#venta_iva_1").val("");
                                }
                            }
                    );
                }
            }
        }
    }

    // buscar productos codigo barras
    $("#codigo_barras").change(function (e) {
        $("#mino").prop("selected", true);
        barras();
    });
    function barras() {
        var num_factu = $("#num_factura").val();
        let tipo = $("#tipo_venta").val();
        $.ajax({
            type: "POST",
            url: "comparar_num_venta.php",
            data: "num_fac=" + num_factu + "&tipo_venta=" + tipo,
            success: function (data) {
                var val = data;
                console.log("::" + val);
                val = val.split("-");
                if (val[0] != 0) {
                    $("#num_factura").val("");
                    $("#num_factura").focus();
                    //                    alertify.error("Error... La factura ya existe, favor verificar el número que corresponda" );
                    var res1 = parseInt(val[0].substr(4, 16));
                    res1 = res1 + 1;
                    //FACTURA VENTA
                    var res3 = parseInt(val[1]);
                    res3 = res3 + 1;
                    //nota venta
                    var res2 = parseInt(val[1]);
                    res2 = res2 + 1;
                    alertify.success("Nuevo Num Factura... " + res1);
                    $("#num_factura").val(res1);
                    //nota venta
                    var filas = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
                    if ($("#tipo_venta").val() == "FACTURA") {
                        $("#comprobante").val(res3);
                        for (var i = 0; i < filas.length; i++) {
                            var id = filas[i];
                            var id_mix = id["id_f_v_mix"];
                            console.log("f2::" + id_mix);
                            jQuery("#listPagoreten_mixto").jqGrid("setRowData", id_mix, {
                                id_factura_venta: res2,
                            });
                        }
                    } else {
                        $("#comprobante_nota").val(res2);
                        for (var i = 0; i < filas.length; i++) {
                            var id = filas[i];
                            var id_mix = id["id_f_v_mix"];
                            console.log("f1::" + id_mix);
                            jQuery("#listPagoreten_mixto").jqGrid("setRowData", id_mix, {
                                id_factura_venta: res2,
                            });
                        }
                    }
                    var a1 = autocompletar(res1);
                    var validado = a1 + "" + res1;
                    $("#num_factura").val(validado);

                }
            },
        });
        var precio = $("#tipo_precio").val();
        var codigo = $("#codigo_barras").val();
        if (precio == "MINORISTA") {
            var precio = $("#tipo_precio").val();
            var codigo = $("#codigo_barras").val();
            var cod = $("#codigo_barras").val();
            $.getJSON(
                    "search.php?codigo_barras=" +
                    codigo +
                    "&precio=" +
                    precio +
                    "&cod=" +
                    cod,
                    function (data) {
                        //            $.getJSON('search.php?codigo_barras=' + codigo + '&precio=' + precio, function (data) {
                        var tama = data.length;
                        if (tama != 0) {
                            for (var i = 0; i < tama; i = i + 11) {
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
                                 $("#precio").val(data[i + 10]);
                                $("#cantidad").val("1");
                                $("#venta_iva_1").val("");
                                $("#cantidad").select();
                                abrirDialogo_unidad();
                                comprobar_cuentas_promo($("#cod_producto").val());
                                comprobar_pvp_editable($("#cod_producto").val());
                                if ($("#iva_producto").val() == "Si") {
                                    $("#venta_iva").val("");
                                    var iva1 = ($("#p_venta").val() * calculoIVA) / 100;
                                    var iva_pventa = iva1 + parseFloat($("#p_venta").val());
                                    $("#venta_iva").val(numFormatter(2).format(iva_pventa));
                                } else {
                                    $("#venta_iva").val("");
                                }
                            }
                        } else {
                            $("#codigo").val("");
                            $("#producto").val("");
                            $("#p_venta").val("");
                            $("#venta_iva").val("");
                            //                    $("#descuento").val("");
                            $("#disponibles").val("");
                            $("#iva_producto").val("");
                            $("#carga_series").val("");
                            $("#cod_producto").val("");
                            $("#des").val("");
                            $("#inventar").val("");
                            $("#incluye").val("");
                            alertify.error("Producto no ingresado");
                            $("#codigo_barras").val("");
                            $("#cantidad").val("");
                            $("#venta_iva_1").val("");
                        }
                    }
            );
        } else {
            if (precio == "MAYORISTA") {
                $.getJSON(
                        "search.php?codigo_barras=" + codigo + "&precio=" + precio,
                        function (data) {
                            var tama = data.length;
                            if (tama != 0) {
                                for (var i = 0; i < tama; i = i + 11) {
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
                                     $("#precio").val(data[i + 10]);
                                    //  $("#cantidad").val("1");
                                    $("#cantidad").select();
                                    abrirDialogo_unidad();
                                    comprobar_cuentas_promo($("#cod_producto").val());
                                    comprobar_pvp_editable($("#cod_producto").val());
                                    if ($("#iva_producto").val() == "Si") {
                                        $("#venta_iva").val("");
                                        var iva1 = ($("#p_venta").val() * calculoIVA) / 100;
                                        var iva_pventa = iva1 + parseFloat($("#p_venta").val());
                                        $("#venta_iva").val(numFormatter(2).format(iva_pventa));
                                    } else {
                                        $("#venta_iva").val("");
                                    }
                                }
                            } else {
                                $("#codigo").val("");
                                $("#producto").val("");
                                $("#p_venta").val("");
                                $("#venta_iva").val("");
                                //                        $("#descuento").val("");
                                $("#disponibles").val("");
                                $("#iva_producto").val("");
                                $("#carga_series").val("");
                                $("#cod_producto").val("");
                                $("#des").val("");
                                $("#inventar").val("");
                                $("#incluye").val("");
                                alertify.error("Producto no ingresado");
                                $("#codigo_barras").val("");
                                $("#cantidad").val("");
                                $("#venta_iva_1").val("");
                            }
                        }
                );
            } else {
                if (precio == "NEGOCIO") {
                    $.getJSON(
                            "search.php?codigo_barras=" + codigo + "&precio=" + precio,
                            function (data) {
                                var tama = data.length;
                                if (tama != 0) {
                                    for (var i = 0; i < tama; i = i + 11) {
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
                                         $("#precio").val(data[i + 10]);
                                        //  $("#cantidad").val("1");
                                        $("#cantidad").select();
                                        abrirDialogo_unidad();
                                        comprobar_cuentas_promo($("#cod_producto").val());
                                        comprobar_pvp_editable($("#cod_producto").val());
                                        if ($("#iva_producto").val() == "Si") {
                                            $("#venta_iva").val("");
                                            var iva1 = ($("#p_venta").val() * calculoIVA) / 100;
                                            var iva_pventa = iva1 + parseFloat($("#p_venta").val());
                                            $("#venta_iva").val(numFormatter(2).format(iva_pventa));
                                        } else {
                                            $("#venta_iva").val("");
                                        }
                                    }
                                } else {
                                    $("#codigo").val("");
                                    $("#producto").val("");
                                    $("#p_venta").val("");
                                    $("#venta_iva").val("");
                                    //                            $("#descuento").val("");
                                    $("#disponibles").val("");
                                    $("#iva_producto").val("");
                                    $("#carga_series").val("");
                                    $("#cod_producto").val("");
                                    $("#des").val("");
                                    $("#inventar").val("");
                                    $("#incluye").val("");
                                    alertify.error("Producto no ingresado");
                                    $("#codigo_barras").val("");
                                    $("#cantidad").val("");
                                    $("#venta_iva_1").val("");
                                }
                            }
                    );
                }
            }
        }
    }
    // fin

    // buscar productos codigo
    $("#codigo").keyup(function (e) {
        var num_factu = $("#num_factura").val();
        let tipo = $("#tipo_venta").val();
        $.ajax({
            type: "POST",
            url: "comparar_num_venta.php",
            data: "num_fac=" + num_factu + "&tipo_venta=" + tipo,
            success: function (data) {
                var val = data;
                console.log("::" + val);
                val = val.split("-");
                if (val[0] != 0) {
                    $("#num_factura").val("");
                    $("#num_factura").focus();
                    //                    alertify.error( "Error... La factura ya existe, favor verificar el número que corresponda");
                    var res1 = parseInt(val[0].substr(4, 16));
                    res1 = res1 + 1;
                    //FACTURA VENTA
                    var res3 = parseInt(val[1]);
                    res3 = res3 + 1;
                    //nota venta
                    var res2 = parseInt(val[1]);
                    res2 = res2 + 1;
                    alertify.success("Nuevo Num Factura... " + res1);
                    $("#num_factura").val(res1);
                    //nota venta
                    var filas = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
                    if ($("#tipo_venta").val() == "FACTURA") {
                        $("#comprobante").val(res3);
                        for (var i = 0; i < filas.length; i++) {
                            var id = filas[i];
                            var id_mix = id["id_f_v_mix"];
                            console.log("f2::" + id_mix);
                            jQuery("#listPagoreten_mixto").jqGrid("setRowData", id_mix, {
                                id_factura_venta: res2,
                            });
                        }
                    } else {
                        $("#comprobante_nota").val(res2);
                        for (var i = 0; i < filas.length; i++) {
                            var id = filas[i];
                            var id_mix = id["id_f_v_mix"];
                            console.log("f1::" + id_mix);
                            jQuery("#listPagoreten_mixto").jqGrid("setRowData", id_mix, {
                                id_factura_venta: res2,
                            });
                        }
                    }
                    var a1 = autocompletar(res1);
                    var validado = a1 + "" + res1;
                    $("#num_factura").val(validado);

                }
            },
        });
        var precio = $("#tipo_precio").val();
        var res = combo(precio);
        if (precio == "MINORISTA") {
            $("#codigo")
                    .autocomplete({
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
                            $("#iva_producto").val(ui.item.iva_producto);
                            $("#carga_series").val(ui.item.carga_series);
                            $("#cod_producto").val(ui.item.cod_producto);
                            $("#des").val(ui.item.des);
                            $("#inventar").val(ui.item.inventar);
                            $("#incluye").val(ui.item.incluye);
                            $("#cantidad").val("1");
                             $("#precio").val(ui.item.precio);
                            //                 $("#punto_venta_inv").val(ui.item.punto_venta);
                            return false;
                        },
                        select: function (event, ui) {
                            $("#codigo_barras").val(ui.item.codigo_barras);
                            $("#codigo").val(ui.item.value);
                            $("#producto").val(ui.item.producto);
                            $("#p_venta").val(ui.item.p_venta);
                            $("#descuento").attr("max", ui.item.descuento);
                            $("#disponibles").val(ui.item.disponibles);
                            $("#iva_producto").val(ui.item.iva_producto);
                            $("#carga_series").val(ui.item.carga_series);
                            $("#cod_producto").val(ui.item.cod_producto);
                            $("#des").val(ui.item.des);
                            $("#inventar").val(ui.item.inventar);
                            $("#incluye").val(ui.item.incluye);
                            $("#cantidad").val("1");
                            $("#cantidad").select();
                             $("#precio").val(ui.item.precio);
                            //                   $("#punto_venta_inv").val(ui.item.punto_venta);
                            return false;
                        },
                    })
                    .data("ui-autocomplete")._renderItem = function (ul, item) {
                return $("<li>")
                        .append("<a>" + item.value + "</a>")
                        .appendTo(ul);
            };
        } else {
            if (precio == "MAYORISTA") {
                $("#codigo")
                        .autocomplete({
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
                                $("#iva_producto").val(ui.item.iva_producto);
                                $("#carga_series").val(ui.item.carga_series);
                                $("#cod_producto").val(ui.item.cod_producto);
                                $("#des").val(ui.item.des);
                                $("#inventar").val(ui.item.inventar);
                                $("#incluye").val(ui.item.incluye);
                                $("#cantidad").val("1");
                                 $("#precio").val(ui.item.precio);
                                //                     $("#punto_venta_inv").val(ui.item.punto_venta);
                                return false;
                            },
                            select: function (event, ui) {
                                $("#codigo_barras").val(ui.item.codigo_barras);
                                $("#codigo").val(ui.item.value);
                                $("#producto").val(ui.item.producto);
                                $("#p_venta").val(ui.item.p_venta);
                                $("#descuento").attr("max", ui.item.descuento);
                                $("#disponibles").val(ui.item.disponibles);
                                $("#iva_producto").val(ui.item.iva_producto);
                                $("#carga_series").val(ui.item.carga_series);
                                $("#cod_producto").val(ui.item.cod_producto);
                                $("#des").val(ui.item.des);
                                $("#inventar").val(ui.item.inventar);
                                $("#incluye").val(ui.item.incluye);
                                $("#cantidad").val("1");
                                 $("#precio").val(ui.item.precio);
                                //                    $("#punto_venta_inv").val(ui.item.punto_venta);
                                $("#cantidad").select();
                                return false;
                            },
                        })
                        .data("ui-autocomplete")._renderItem = function (ul, item) {
                    return $("<li>")
                            .append("<a>" + item.value + "</a>")
                            .appendTo(ul);
                };
            } else {
                if (precio == "NEGOCIO") {
                    $("#codigo")
                            .autocomplete({
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
                                    $("#iva_producto").val(ui.item.iva_producto);
                                    $("#carga_series").val(ui.item.carga_series);
                                    $("#cod_producto").val(ui.item.cod_producto);
                                    $("#des").val(ui.item.des);
                                    $("#inventar").val(ui.item.inventar);
                                    $("#incluye").val(ui.item.incluye);
                                    $("#cantidad").val("1");
                                     $("#precio").val(ui.item.precio);
                                    //                         $("#punto_venta_inv").val(ui.item.punto_venta);
                                    return false;
                                },
                                select: function (event, ui) {
                                    $("#codigo_barras").val(ui.item.codigo_barras);
                                    $("#codigo").val(ui.item.value);
                                    $("#producto").val(ui.item.producto);
                                    $("#p_venta").val(ui.item.p_venta);
                                    $("#descuento").attr("max", ui.item.descuento);
                                    $("#disponibles").val(ui.item.disponibles);
                                    $("#iva_producto").val(ui.item.iva_producto);
                                    $("#carga_series").val(ui.item.carga_series);
                                    $("#cod_producto").val(ui.item.cod_producto);
                                    $("#des").val(ui.item.des);
                                    $("#inventar").val(ui.item.inventar);
                                    $("#incluye").val(ui.item.incluye);
                                    $("#cantidad").val("1");
                                     $("#precio").val(ui.item.precio);
                                    //                          $("#punto_venta_inv").val(ui.item.punto_venta);
                                    $("#cantidad").select();
                                    return false;
                                },
                            })
                            .data("ui-autocomplete")._renderItem = function (ul, item) {
                        return $("<li>")
                                .append("<a>" + item.value + "</a>")
                                .appendTo(ul);
                    };
                }
            }
        }
    });
    // fin
    // busqueda productos articulos
    $("#producto").keyup(function (e) {
        $("#mino").prop("selected", true);
        var num_factu = $("#num_factura").val();
        let tipo = $("#tipo_venta").val();
        $.ajax({
            type: "POST",
            url: "comparar_num_venta.php",
            data: "num_fac=" + num_factu + "&tipo_venta=" + tipo,
            success: function (data) {
                var val = data;
                console.log("::" + val);
                val = val.split("-");
                if (val[0] != 0) {
                    $("#num_factura").val("");
                    $("#num_factura").focus();
                    //                    alertify.error( "Error... La factura ya existe, favor verificar el número que corresponda");
                    var res1 = parseInt(val[0].substr(4, 16));
                    res1 = res1 + 1;
                    //FACTURA VENTA
                    var res3 = parseInt(val[1]);
                    res3 = res3 + 1;
                    //nota venta
                    var res2 = parseInt(val[1]);
                    res2 = res2 + 1;
                    alertify.success("Nuevo Num Factura... " + res1);
                    $("#num_factura").val(res1);
                    //nota venta
                    if ($("#tipo_venta").val() == "FACTURA")
                        $("#comprobante").val(res3);
                    else {
                        $("#comprobante_nota").val(res2);
                    }
                    var a1 = autocompletar(res1);
                    var validado = a1 + "" + res1;
                    $("#num_factura").val(validado);
                    $("#producto").focus();

                }
            },
        });
        var precio = $("#tipo_precio").val();
        var res = combo1(precio);
        if (precio == "MINORISTA") {
            $("#producto").autocomplete({
                source: function (req, response) {
                    var results = $.ui.autocomplete.filter(res, "");
                    response(results.slice(0, 20));
                },
                minLength: 1,
                focus: function (event, ui) {
                    $("#codigo_barras").val(ui.item.codigo_barras);//.trigger("change");
                    $("#producto").val(ui.item.value);
                    $("#codigo").val(ui.item.codigo);
                    $("#p_venta").val(ui.item.p_venta);
                    $("#descuento").attr("max", ui.item.descuento);
                    $("#disponibles").val(ui.item.disponibles);
                    $("#iva_producto").val(ui.item.iva_producto);
                    $("#venta_iva_1").val("");
                    $("#carga_series").val(ui.item.carga_series);
                    $("#cod_producto").val(ui.item.cod_producto);
                    $("#cod_producto_tem").val(ui.item.cod_producto);
                    $("#des").val(ui.item.des);
                    $("#inventar").val(ui.item.inventar);
                    $("#incluye").val(ui.item.incluye);
                    $("#cantidad_descuento_campo").val(
                            ui.item.cantidad_descuento_campo
                            );
                    entrar222();
                    $("#cantidad").val("1");
                    abrirDialogo_unidad();
                    comprobar_cuentas_promo($("#cod_producto").val());
                    comprobar_pvp_editable($("#cod_producto").val());
                     $("#precio").val(ui.item.precio);
                    //                 $("#punto_venta_inv").val(ui.item.punto_venta);
                    return false;
                },
                select: function (event, ui) {
                    $("#codigo_barras").val(ui.item.codigo_barras);//.trigger("change");
                    $("#producto").val(ui.item.value);
                    $("#codigo").val(ui.item.codigo);
                    $("#p_venta").val(ui.item.p_venta);
                    $("#descuento").attr("max", ui.item.descuento);
                    $("#disponibles").val(ui.item.disponibles);
                    $("#iva_producto").val(ui.item.iva_producto);
                    $("#venta_iva_1").val("");
                    $("#carga_series").val(ui.item.carga_series);
                    $("#cod_producto").val(ui.item.cod_producto);
                    $("#cod_producto_tem").val(ui.item.cod_producto);
                    $("#des").val(ui.item.des);
                    $("#inventar").val(ui.item.inventar);
                    $("#incluye").val(ui.item.incluye);
                    $("#cantidad_descuento_campo").val(
                            ui.item.cantidad_descuento_campo
                            );
                    entrar222();
                    $("#cantidad").val("1");
                    $("#cantidad").select();
                    abrirDialogo_unidad();
                    comprobar_cuentas_promo($("#cod_producto").val());
                    comprobar_pvp_editable($("#cod_producto").val());
                     $("#precio").val(ui.item.precio);
                    if ($("#iva_producto").val() == "Si") {
                        $("#venta_iva").val("");
                        var iva1 = ($("#p_venta").val() * calculoIVA) / 100;
                        var iva_pventa = iva1 + parseFloat($("#p_venta").val());
                        $("#venta_iva").val(numFormatter(2).format(iva_pventa));
                    } else {
                        $("#venta_iva").val("0.00");
                    }
                    //                   $("#punto_venta_inv").val(ui.item.punto_venta);
                    return false;
                },
            })
                    .data("ui-autocomplete")._renderItem = function (ul, item) {
                return $("<li>")
                        .append("<a>" + item.value + "</a>")
                        .appendTo(ul);
            };
        } else {
            if (precio == "MAYORISTA") {
                $("#producto")
                        .autocomplete({
                            source: function (req, response) {
                                var results = $.ui.autocomplete.filter(res, "");
                                response(results.slice(0, 20));
                            },
                            minLength: 1,
                            focus: function (event, ui) {
                                $("#codigo_barras").val(ui.item.codigo_barras);//.trigger("change");
                                $("#producto").val(ui.item.value);
                                $("#codigo").val(ui.item.codigo);
                                $("#p_venta").val(ui.item.p_venta);
                                $("#descuento").attr("max", ui.item.descuento);
                                $("#disponibles").val(ui.item.disponibles);
                                $("#iva_producto").val(ui.item.iva_producto);
                                $("#venta_iva_1").val("");
                                $("#carga_series").val(ui.item.carga_series);
                                $("#cod_producto").val(ui.item.cod_producto);
                                $("#cod_producto_tem").val(ui.item.cod_producto);
                                $("#des").val(ui.item.des);
                                $("#inventar").val(ui.item.inventar);
                                $("#incluye").val(ui.item.incluye);
                                if ($("#iva_producto").val() == "Si") {
                                    $("#venta_iva").val("");
                                    var iva1 = ($("#p_venta").val() * calculoIVA) / 100;
                                    var iva_pventa = iva1 + parseFloat($("#p_venta").val());
                                    $("#venta_iva").val(numFormatter(2).format(iva_pventa));
                                } else {
                                    $("#venta_iva").val("0.00");
                                }
                                $("#cantidad").val("1");
                                abrirDialogo_unidad();
                                comprobar_cuentas_promo($("#cod_producto").val());
                                comprobar_pvp_editable($("#cod_producto").val());
                                 $("#precio").val(ui.item.precio);
                                //                     $("#punto_venta_inv").val(ui.item.punto_venta);
                                return false;
                            },
                            select: function (event, ui) {
                                $("#codigo_barras").val(ui.item.codigo_barras);//.trigger("change");
                                $("#producto").val(ui.item.value);
                                $("#codigo").val(ui.item.codigo);
                                $("#p_venta").val(ui.item.p_venta);
                                $("#descuento").attr("max", ui.item.descuento);
                                $("#disponibles").val(ui.item.disponibles);
                                $("#iva_producto").val(ui.item.iva_producto);
                                $("#venta_iva_1").val("");
                                $("#carga_series").val(ui.item.carga_series);
                                $("#cod_producto").val(ui.item.cod_producto);
                                $("#cod_producto_tem").val(ui.item.cod_producto);
                                $("#des").val(ui.item.des);
                                $("#inventar").val(ui.item.inventar);
                                $("#incluye").val(ui.item.incluye);
                                $("#cantidad").val("1");
                                $("#cantidad").select();
                                abrirDialogo_unidad();
                                comprobar_cuentas_promo($("#cod_producto").val());
                                comprobar_pvp_editable($("#cod_producto").val());
                                 $("#precio").val(ui.item.precio);
                                if ($("#iva_producto").val() == "Si") {
                                    $("#venta_iva").val("");
                                    var iva1 = ($("#p_venta").val() * calculoIVA) / 100;
                                    var iva_pventa = iva1 + parseFloat($("#p_venta").val());
                                    $("#venta_iva").val(numFormatter(2).format(iva_pventa));
                                } else {
                                    $("#venta_iva").val("0.00");
                                }
                                //                       $("#punto_venta_inv").val(ui.item.punto_venta);
                                return false;
                            },
                        })
                        .data("ui-autocomplete")._renderItem = function (ul, item) {
                    return $("<li>")
                            .append("<a>" + item.value + "</a>")
                            .appendTo(ul);
                };
            } else {
                if (precio == "NEGOCIO") {
                    $("#producto")
                            .autocomplete({
                                source: function (req, response) {
                                    var results = $.ui.autocomplete.filter(res, "");
                                    response(results.slice(0, 20));
                                },
                                minLength: 1,
                                focus: function (event, ui) {
                                    $("#codigo_barras").val(ui.item.codigo_barras);//.trigger("change");
                                    $("#producto").val(ui.item.value);
                                    $("#codigo").val(ui.item.codigo);
                                    $("#p_venta").val(ui.item.p_venta);
                                    $("#descuento").attr("max", ui.item.descuento);
                                    $("#disponibles").val(ui.item.disponibles);
                                    $("#iva_producto").val(ui.item.iva_producto);
                                    $("#venta_iva_1").val("");
                                    $("#carga_series").val(ui.item.carga_series);
                                    $("#cod_producto").val(ui.item.cod_producto);
                                    $("#cod_producto_tem").val(ui.item.cod_producto);
                                    $("#des").val(ui.item.des);
                                    $("#inventar").val(ui.item.inventar);
                                    $("#incluye").val(ui.item.incluye);
                                    if ($("#iva_producto").val() == "Si") {
                                        $("#venta_iva").val("");
                                        var iva1 = ($("#p_venta").val() * calculoIVA) / 100;
                                        var iva_pventa = iva1 + parseFloat($("#p_venta").val());
                                        $("#venta_iva").val(numFormatter(2).format(iva_pventa));
                                    } else {
                                        $("#venta_iva").val("0.00");
                                    }
                                    $("#cantidad").val("1");
                                    abrirDialogo_unidad();
                                    comprobar_cuentas_promo($("#cod_producto").val());
                                    comprobar_pvp_editable($("#cod_producto").val());
                                     $("#precio").val(ui.item.precio);
                                    //                         $("#punto_venta_inv").val(ui.item.punto_venta);
                                    return false;
                                },
                                select: function (event, ui) {
                                    $("#codigo_barras").val(ui.item.codigo_barras);//.trigger("change");
                                    $("#producto").val(ui.item.value);
                                    $("#codigo").val(ui.item.codigo);
                                    $("#p_venta").val(ui.item.p_venta);
                                    $("#descuento").attr("max", ui.item.descuento);
                                    $("#disponibles").val(ui.item.disponibles);
                                    $("#iva_producto").val(ui.item.iva_producto);
                                    $("#venta_iva_1").val("");
                                    $("#carga_series").val(ui.item.carga_series);
                                    $("#cod_producto").val(ui.item.cod_producto);
                                    $("#cod_producto_tem").val(ui.item.cod_producto);
                                    $("#des").val(ui.item.des);
                                    $("#inventar").val(ui.item.inventar);
                                    $("#incluye").val(ui.item.incluye);
                                    $("#cantidad").val("1");
                                    $("#cantidad").select();
                                    abrirDialogo_unidad();
                                    comprobar_cuentas_promo($("#cod_producto").val());
                                    comprobar_pvp_editable($("#cod_producto").val());
                                     $("#precio").val(ui.item.precio);
                                    if ($("#iva_producto").val() == "Si") {
                                        $("#venta_iva").val("");
                                        var iva1 = ($("#p_venta").val() * calculoIVA) / 100;
                                        var iva_pventa = iva1 + parseFloat($("#p_venta").val());
                                        $("#venta_iva").val(numFormatter(2).format(iva_pventa));
                                    } else {
                                        $("#venta_iva").val("0.00");
                                    }
                                    //                           $("#punto_venta_inv").val(ui.item.punto_venta);
                                    return false;
                                },
                            })
                            .data("ui-autocomplete")._renderItem = function (ul, item) {
                        return $("<li>")
                                .append("<a>" + item.value + "</a>")
                                .appendTo(ul);
                    };
                }
            }
        }
    });
    // fin
    // accion limpiar
    $("#tipo_precio").change(function () {
        $("#codigo_barras").val("");
        $("#cod_producto").val("");
        $("#codigo").val("");
        $("#producto").val("");
         $("#precio").val("");
        $("#cantidad").val("");
        $("#p_venta").val("");
        $("#venta_iva").val("");
        //        $("#descuento").val("");
        $("#disponibles").val("");
        $("#inventar").val("");
        $("#des").val("");
        $("#iva_producto").val("");
        $("#carga_series").val("");
        $("#incluye").val("");
        $("#venta_iva_1").val("");
    });
    // fin
    $("#unidad_medida").change(() => {
        if ($("#cod_producto").val() !== "") {
            let cod_producto = $("#cod_producto").val();
            let unidad_medida = $("#unidad_medida").val();
            let precio = $("#tipo_precio").val();
            $.getJSON(
                    "search_um.php?cod_producto=" +
                    cod_producto +
                    "&unidad_medida=" +
                    unidad_medida +
                    "&precio=" +
                    precio,
                    (data) => {
                $("#p_venta").val(data[2]);
                //                $("#pvp").val(data[3]);

                $("#cantidad_unidad").val(data[1]);
            }
            );
            $("#cantidad").focus();
        }
    });
    // fin
    // buscar clientes identificacion
    $("#ruc_ci")
            .autocomplete({
                source: "buscar_cliente.php",
                minLength: 1,
                focus: function (event, ui) {
                    $("#ruc_ci").val(ui.item.value);
                    $("#id_cliente").val(ui.item.id_cliente);
                    $("#nombre_cliente").val(ui.item.nombre_cliente);
                    $("#direccion_cliente").val(ui.item.direccion_cliente);
                    $("#telefono_cliente").val(ui.item.telefono_cliente);
                    $("#correo").val(ui.item.correo);
                    $("#nombre_vendedor").val(ui.item.nombre_vendedor);
                    $("#vendedor").val(ui.item.id_vendedor);
                    $("#id_tdocu").val(ui.item.id_tdocu);
                    comprobar_cuentas($("#ruc_ci").val());
                    return false;
                },
                select: function (event, ui) {
                    $("#ruc_ci").val(ui.item.value);
                    $("#id_cliente").val(ui.item.id_cliente);
                    $("#nombre_cliente").val(ui.item.nombre_cliente);
                    $("#direccion_cliente").val(ui.item.direccion_cliente);
                    $("#telefono_cliente").val(ui.item.telefono_cliente);
                    $("#correo").val(ui.item.correo);
                    //                    $("#direccion_cliente").attr("disabled", "disabled");
                    //        $("#telefono_cliente").attr("disabled", "disabled");
                    //        $("#correo").attr("disabled", "disabled");
                    $("#nombre_vendedor").val(ui.item.nombre_vendedor);
                    $("#vendedor").val(ui.item.id_vendedor);
                    $("#id_tdocu").val(ui.item.id_tdocu);
                    return false;
                },
            })
            .data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
    };
    // fin

    // buscar clientes nombres
    $("#nombre_cliente")
            .autocomplete({
                source: "buscar_cliente_nombre.php",
                minLength: 1,
                focus: function (event, ui) {
                    $("#nombre_cliente").val(ui.item.value);
                    $("#id_cliente").val(ui.item.id_cliente);
                    $("#ruc_ci").val(ui.item.ruc_ci);
                    $("#direccion_cliente").val(ui.item.direccion_cliente);
                    $("#telefono_cliente").val(ui.item.telefono_cliente);
                    $("#correo").val(ui.item.correo);
                    $("#nombre_vendedor").val(ui.item.nombre_vendedor);
                    $("#vendedor").val(ui.item.id_vendedor);
                    $("#id_tdocu").val(ui.item.id_tdocu);
                    return false;
                },
                select: function (event, ui) {
                    $("#nombre_cliente").val(ui.item.value);
                    $("#id_cliente").val(ui.item.id_cliente);
                    $("#ruc_ci").val(ui.item.ruc_ci);
                    $("#direccion_cliente").val(ui.item.direccion_cliente);
                    $("#telefono_cliente").val(ui.item.telefono_cliente);
                    $("#correo").val(ui.item.correo);
                    //                    $("#direccion_cliente").attr("disabled", "disabled");
                    //        $("#telefono_cliente").attr("disabled", "disabled");
                    //        $("#correo").attr("disabled", "disabled");
                    $("#nombre_vendedor").val(ui.item.nombre_vendedor);
                    $("#vendedor").val(ui.item.id_vendedor);
                    $("#id_tdocu").val(ui.item.id_tdocu);
                    return false;
                },
            })
            .data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
    };
    // fin

    /////////////////////////////////
    // $("#cantidad").on("keypress", punto);

    $("#cantidad").on("keypress", enter);
    // $("#descuento").validCampoFranz("0123456789");
    $("#num_factura").validCampoFranz("0123456789");
    $("#num_factura").attr("maxlength", "9");
    //    $("#ruc_ci").validCampoFranz("0123456789");
    /////////////////////////////////////
    // atributos
    $("#adelanto").attr("disabled", "disabled");
    $("#meses").attr("disabled", "disabled");
    $("#cuotas").attr("disabled", "disabled");
    // fin
    $(".ui-spinner-button").click(function () {
        $(this).siblings("input").change();
    });
    //        $(function() {
    //        $("#fecha_dias").datepicker({ minDate: 0 });
    //        });
    $("#fecha_dias").change(function () {
        //        if ($("#adelanto").val() !== "") {
        //            resta = ($("#tot").val() - $("#adelanto").val());
        //            var redo = parseFloat(resta).toFixed(2);
        //            var dias = $("#fecha_dias").val();
        //            $("#tablaNuevo tbody").append("<tr>" +
        //                    "<td align=center >" + dias + "</td>" +
        //                    "<td align=center >" + redo + "</td>" +
        //                    "<tr>");
        //            // $("#cuotas").append('<option>'+redo+'</option>');
        //        } else {
        var to = $("#tot").val();
        var redo = parseFloat(to).toFixed(2);
        var dias = $("#fecha_dias").val();
        $("#tablaNuevo tbody").append(
                "<tr>" +
                "<td align=center >" +
                dias +
                "</td>" +
                "<td align=center >" +
                redo +
                "</td>" +
                "<tr>"
                );
        // $("#cuotas").append('<option>'+redo+'</option>');
        //        }
    });
    $("#adelanto").change(function () {
        if ($("#adelanto").val() !== "") {
            resta = $("#tot").val() - $("#adelanto").val();
            var redo = parseFloat(resta).toFixed(2);
            var dias = $("#fecha_dias").val();
            $("#tablaNuevo tbody").append(
                    "<tr>" +
                    "<td align=center >" +
                    dias +
                    "</td>" +
                    "<td align=center >" +
                    redo +
                    "</td>" +
                    "<tr>"
                    );
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
            dd = "0" + dd;
        }
        if (mm < 10) {
            mm = "0" + mm;
        }
        fecha = yyyy + "-" + mm + "-" + dd;
        // $('#cuotas').children().remove().end();
        $("#tablaNuevo tbody").empty();
        if ($("#formas").val() === "Credito") {
            if (meses > 1) {
                var fecha = new Date();
                var dd = fecha.getDate();
                var mm = fecha.getMonth() + 3; //hoy es 0!
                var yyyy = fecha.getFullYear();
                if (dd < 10) {
                    dd = "0" + dd;
                }
                if (mm < 10) {
                    mm = "0" + mm;
                }
                fecha = yyyy + "-" + mm + "-" + dd;
                if ($("#adelanto").val() !== "") {
                    var resta = $("#tot").val() - $("#adelanto").val();
                    for (var i = 1; i <= meses - 1; i++) {
                        var calcu = resta / meses;
                        var entero = Math.floor(calcu).toFixed(2);
                        $("#tablaNuevo tbody").append(
                                "<tr>" +
                                "<td align=center >" +
                                fecha +
                                "</td>" +
                                "<td align=center>" +
                                fecha +
                                "</td>" +
                                "<tr>"
                                );
                        // $("#cuotas").append('<option>'+entero+'</option>');
                    }
                    var calcu1 = entero * (meses - 1);
                    var sal = resta - calcu1;
                    var entero2 = sal.toFixed(2);
                    $("#tablaNuevo tbody").append(
                            "<tr>" +
                            "<td align=center >" +
                            fecha +
                            "</td>" +
                            "<td align=center >" +
                            entero2 +
                            "</td>" +
                            "<tr>"
                            );
                    // $("#cuotas").append('<option>'+entero2+'</option>');
                } else {
                    var to = $("#tot").val();
                    for (i = 1; i <= meses - 1; i++) {
                        calcu = to / meses;
                        entero = Math.floor(calcu).toFixed(2);
                        $("#tablaNuevo tbody").append(
                                "<tr>" +
                                "<td align=center >" +
                                fecha +
                                "</td>" +
                                "<td align=center >" +
                                entero +
                                "</td>" +
                                "<tr>"
                                );
                        //  $("#cuotas").append('<option>'+entero+'</option>');
                    }
                    calcu1 = entero * (meses - 1);
                    sal = to - calcu1;
                    entero2 = sal.toFixed(2);
                    $("#tablaNuevo tbody").append(
                            "<tr>" +
                            "<td align=center >" +
                            fecha +
                            "</td>" +
                            "<td align=center >" +
                            entero2 +
                            "</td>" +
                            "<tr>"
                            );
                    // $("#cuotas").append('<option>'+entero2+'</option>');
                }
            } else {
                if ($("#adelanto").val() !== "") {
                    resta = $("#tot").val() - $("#adelanto").val();
                    var redo = parseFloat(resta).toFixed(2);
                    $("#tablaNuevo tbody").append(
                            "<tr>" +
                            "<td align=center >" +
                            fecha +
                            "</td>" +
                            "<td align=center >" +
                            redo +
                            "</td>" +
                            "<tr>"
                            );
                    // $("#cuotas").append('<option>'+redo+'</option>');
                } else {
                    to = $("#tot").val();
                    redo = parseFloat(to).toFixed(2);
                    $("#tablaNuevo tbody").append(
                            "<tr>" +
                            "<td align=center >" +
                            fecha +
                            "</td>" +
                            "<td align=center >" +
                            redo +
                            "</td>" +
                            "<tr>"
                            );
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
            alertify.alert("Error...Seleccione un producto");
        }
    });
    $("#formaspago_mixto").change(function () {
        var tam2 = jQuery("#list").jqGrid("getRowData");
        if ($("#formaspago_mixto").val() == "Contado" || $("#formaspago_mixto").val() == "Cupon") {
            $("#adelanto").removeAttr("disabled");
            $("#meses").attr("disabled", "disabled");
            $("#meses").val("");
            $("#cuotas").attr("disabled", "disabled");
            $("#cuotas").children().remove().end();
        } else {
            if (
                    $("#formaspago_mixto").val() == "Credito" ||
                    $("#formaspago_mixto").val() == "CPosfechado"
                    ) {
                if (tam2.length > 0) {
                    $("#adelanto").removeAttr("disabled");
                    $("#meses").removeAttr("disabled");
                    $("#cuotas").removeAttr("disabled");
                } else {
                    $("#formaspago_mixto option[value=" + "Contado" + "]").attr(
                            "selected",
                            true
                            );
                    alertify.alert("Error...Ingrese un monto a la factura");
                }
            } else {
                if (
                        $("#formaspago_mixto").val() == "TCredito" ||
                        $("#formaspago_mixto").val() == "Transferencias" ||
                        $("#formaspago_mixto").val() == "Cheque"
                        ) {
                    $("#tarjetas").attr("disabled", false);
                }
            }
        }
    });
    $("#formaspago").change(function () {
        var num_factu = $("#num_factura").val();
        let tipo = $("#tipo_venta").val();
        $.ajax({
            type: "POST",
            url: "comparar_num_venta.php",
            data: "num_fac=" + num_factu + "&tipo_venta=" + tipo,
            success: function (data) {
                var val = data;
                console.log("::" + val);
                val = val.split("-");
                if (val[0] != 0) {
                    $("#num_factura").val("");
                    $("#num_factura").focus();
                    //                    alertify.error("Error... La factura ya existe, favor verificar el número que corresponda" );
                    var res1 = parseInt(val[0].substr(4, 16));
                    res1 = res1 + 1;
                    //FACTURA VENTA
                    var res3 = parseInt(val[1]);
                    res3 = res3 + 1;
                    //nota venta
                    var res2 = parseInt(val[1]);
                    res2 = res2 + 1;
                    alertify.success("Nuevo Num Factura... " + res1);
                    $("#num_factura").val(res1);
                    //nota venta
                    var filas = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
                    if ($("#tipo_venta").val() == "FACTURA") {
                        $("#comprobante").val(res3);
                        for (var i = 0; i < filas.length; i++) {
                            var id = filas[i];
                            var id_mix = id["id_f_v_mix"];
                            console.log("f2::" + id_mix);
                            jQuery("#listPagoreten_mixto").jqGrid("setRowData", id_mix, {
                                id_factura_venta: res2,
                            });
                        }
                    } else {
                        $("#comprobante_nota").val(res2);
                        for (var i = 0; i < filas.length; i++) {
                            var id = filas[i];
                            var id_mix = id["id_f_v_mix"];
                            console.log("f1::" + id_mix);
                            jQuery("#listPagoreten_mixto").jqGrid("setRowData", id_mix, {
                                id_factura_venta: res2,
                            });
                        }
                    }
                    var a1 = autocompletar(res1);
                    var validado = a1 + "" + res1;
                    $("#num_factura").val(validado);

                }
            },
        });
        var tam2 = jQuery("#list").jqGrid("getRowData");
        if ($("#formaspago").val() == "Contado") {
            $("#adelanto").attr("disabled", "disabled");
            $("#adelanto").val("");
            $("#valor_factura").val("");
            $("#meses").attr("disabled", "disabled");
            $("#meses").val("");
            $("#cuotas").attr("disabled", "disabled");
            $("#cuotas").children().remove().end();
        } else {
            if ($("#formaspago").val() == "otros") {
                if (tam2.length > 0 && $("#ruc_ci").val() != "9999999999999") {
                    $('.nav-tabs a[href="#tab_3"]').tab("show");
                    $("#formaspago_mixto").attr("disabled", false);
                    $("#valor_factura").val($("#totx").val());
                } else {
                    $("#contado_form").prop("selected", true);
                    alertify.error(
                            "Error..Ingrese Productos y el Ruc debe ser diferente a consumidor final"
                            );
                }
            }
        }
    });
    //document.getElementById( $("#fecha_actual")).disabled = true;
    $("#fecha_actual").val(new Date().toLocaleDateString("fr-CA"));
    $("#fecha_actual").datepicker({
        dateFormat: 'yy-mm-dd'
    });
    //    $("#fecha_dias").datepicker({
    //        dateFormat: 'yy-mm-dd',
    //        minDate: 0
    //    }).datepicker('setDate', 'today');
    $("#cancelacion")
            .datepicker({
                dateFormat: "yy-mm-dd",
            })
            .datepicker("setDate", "today");
    $("#fecha_auto")
            .datepicker({
                dateFormat: "yy-mm-dd",
            })
            .datepicker("setDate", "today");
    $("#fecha_caducidad")
            .datepicker({
                dateFormat: "yy-mm-dd",
            })
            .datepicker("setDate", "today");
    // datos tabla
    var can;
    jQuery("#list").jqGrid({
        datatype: "local",
        colNames: [
            "",
            "ID",
            "id list",
            "Código",
            "Producto",
            "Cantidad",
            "PVPx",
            "Descuentox",
            "Calculadox",
            "Totalx",
            "PVP",
            "Descuento",
            "Calculado",
            "Total",
            "Iva",
            "Total con Iva",
            "Incluye",
            "C.Unidad",
            "U.Medida",
            "Desc. Prod."
        ],
        colModel: [
            {
                name: "myac",
                width: 50,
                fixed: true,
                sortable: false,
                resize: false,
                formatter: "actions",
                formatoptions: {
                    keys: false,
                    delbutton: true,
                    editbutton: false,
                },
            },
            {
                name: "id_list",
                index: "id_list",
                editable: false,
                search: false,
                hidden: true,
                editrules: {
                    edithidden: false,
                },
                align: "center",
                frozen: true,
                width: 50,
            },
            {
                name: "cod_producto",
                index: "cod_producto",
                editable: false,
                search: false,
                hidden: true,
                editrules: {
                    edithidden: false,
                },
                align: "left",
                frozen: true,
                width: 50,
            },
            {
                name: "codigo",
                index: "codigo",
                editable: false,
                search: false,
                hidden: false,
                editrules: {
                    edithidden: false,
                },
                align: "left",
                frozen: true,
                width: 100,
            },
            {
                name: "detalle",
                index: "detalle",
                editable: false,
                frozen: true,
                editrules: {
                    required: true,
                },
                align: "left",
                width: 290,
            },
            {
                name: "cantidad",
                index: "cantidad",
                editable: true,
                frozen: true,
                editrules: {
                    required: true,
                },
                align: "right",
                width: 70,
            },
            {
                name: "precio_u",
                index: "precio_u",
                hidden: true,
                editable: false,
                search: false,
                frozen: true,
                editrules: {
                    required: true,
                },
                align: "center",
                width: 110,
                editoptions: {
                    maxlength: 10,
                    size: 15,
                    dataInit: function (elem) {
                        $(elem).bind("keypress", function (e) {
                            return punto(e);
                        });
                    },
                },
            },
            {
                name: "descuento",
                index: "descuento",
                hidden: true,
                editable: false,
                frozen: true,
                editrules: {
                    required: true,
                },
                align: "center",
                width: 90,
            },
            {
                name: "cal_des",
                index: "cal_des",
                hidden: true,
                editable: false,
                hidden: true,
                frozen: true,
                editrules: {
                    required: true,
                },
                align: "right",
                width: 90,
            },
            {
                name: "total",
                index: "total",
                hidden: true,
                editable: false,
                search: false,
                frozen: true,
                editrules: {
                    required: true,
                },
                align: "right",
                width: 150,
            },
            {
                name: "precio_ux",
                index: "precio_ux",
                editable: true,
                search: false,
                frozen: true,
                editrules: {
                    required: true,
                },
                align: "right",
                width: 110,
                editoptions: {
                    maxlength: 10,
                    size: 15,
                    dataInit: function (elem) {
                        $(elem).bind("keypress", function (e) {
                            return punto(e);
                        });
                    },
                },
            },
            {
                name: "descuentox",
                index: "descuentox",
                editable: false,
                frozen: true,
                editrules: {
                    required: true,
                },
                align: "right",
                width: 90,
            },
            {
                name: "cal_desx",
                index: "cal_desx",
                editable: false,
                hidden: true,
                frozen: true,
                editrules: {
                    required: true,
                },
                align: "right",
                width: 90,
            },
            {
                name: "totalx",
                index: "totalx",
                editable: false,
                search: false,
                frozen: true,
                editrules: {
                    required: true,
                },
                align: "right",
                width: 150,
            },
            {
                name: "iva",
                index: "iva",
                align: "center",
                width: 100,
                hidden: false,
            },
            {
                name: "pendiente",
                index: "pendiente",
                editable: false,
                frozen: true,
                hidden: true,
                editrules: {
                    required: true,
                },
                align: "right",
                width: 90,
            },
            {
                name: "incluye",
                index: "incluye",
                editable: false,
                hidden: true,
                frozen: true,
                editrules: {
                    required: true,
                },
                align: "right",
                width: 90,
            },
            {
                name: "cantidad_unidad",
                index: "cantidad_unidad",
                editable: false,
                hidden: false,
                frozen: true,
                editrules: {
                    required: true,
                },
                align: "center",
                width: 90,
            },
            {
                name: "unidad_medida",
                index: "unidad_medida",
                editable: false,
                hidden: false,
                frozen: true,
                editrules: {
                    required: true,
                },
                align: "center",
                width: 90,
            },
            {
                name: "detalle_producto",
                index: "detalle_producto",
                editable: false,
                serarch: false
            },
        ],
        rowNum: 30,
        width: null,
        height: 300,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery("#pager"),
        sortname: "id_list",
        sortorder: "asc",
        viewrecords: true,
        cellEdit: true,
        cellsubmit: "clientArray",
        shrinkToFit: true,
        editoptions: {
            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {
                var id = jQuery("#list").jqGrid("getGridParam", "selrow");
                jQuery("#list").jqGrid("restoreRow", id);
                var ret = jQuery("#list").jqGrid("getRowData", id);
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
                            descu_total =
                                    parseFloat($("#desc").val()) - parseFloat(ret.cal_des);
                            subtotal0 = parseFloat(subtotal0);
                            subtotal12 = parseFloat(subtotal12);
                            subtotal_total = parseFloat(subtotal_total);
                            iva12 = parseFloat(iva12);
                            descu_total = parseFloat(descu_total);
                            suma_total =
                                    parseFloat($("#num").val()) - parseFloat(ret.cantidad);
                        } else {
                            if (ret.incluye == "Si") {
                                subtotal = ret.total;
                                sub2 = subtotal / (calculoIVA / 100 + 1);
                                iva2 = sub2 * (calculoIVA / 100);
                                subtotal0 = parseFloat($("#total_p").val()) + 0;
                                subtotal12 =
                                        parseFloat($("#total_p2").val()) - parseFloat(sub2);
                                subtotal_total = parseFloat($("#sub").val()) - parseFloat(sub2);
                                iva12 = parseFloat($("#iva").val()) - parseFloat(iva2);
                                descu_total =
                                        parseFloat($("#desc").val()) - parseFloat(ret.cal_des);
                                subtotal0 = parseFloat(subtotal0);
                                subtotal12 = parseFloat(subtotal12);
                                subtotal_total = parseFloat(subtotal_total);
                                iva12 = parseFloat(iva12);
                                descu_total = parseFloat(descu_total);
                                suma_total =
                                        parseFloat($("#num").val()) - parseFloat(ret.cantidad);
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
                            descu_total =
                                    parseFloat($("#desc").val()) - parseFloat(ret.cal_des);
                            subtotal0 = parseFloat(subtotal0);
                            subtotal12 = parseFloat(subtotal12);
                            subtotal_total = parseFloat(subtotal_total);
                            iva12 = parseFloat(iva12);
                            descu_total = parseFloat(descu_total);
                            suma_total =
                                    parseFloat($("#num").val()) - parseFloat(ret.cantidad);
                        }
                    }
                }

                total_total =
                        parseFloat(total_total) +
                        (parseFloat(subtotal0) + parseFloat(subtotal12) + parseFloat(iva12));
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
                $("#descxax").val(descu_total.toFixed(2));
                $("#totx").val(total_total.toFixed(2));
                $("#items").val(item);
                $("#num").val(suma_total);
                var su = jQuery("#list").jqGrid("delRowData", rowid);
                if (su === true) {
                    rp_ge.processing = true;
                    $(".ui-icon-closethick").trigger("click");
                }
                return true;
            },
            processing: true,
        },
        delOptions: {
            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {
                var id = jQuery("#list").jqGrid("getGridParam", "selrow");
                jQuery("#list").jqGrid("restoreRow", id);
                var ret = jQuery("#list").jqGrid("getRowData", id);
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
                            descu_total =
                                    parseFloat($("#desc").val()) - parseFloat(ret.cal_des);
                            subtotal0 = parseFloat(subtotal0);
                            subtotal12 = parseFloat(subtotal12);
                            subtotal_total = parseFloat(subtotal_total);
                            iva12 = parseFloat(iva12);
                            descu_total = parseFloat(descu_total);
                            suma_total =
                                    parseFloat($("#num").val()) - parseFloat(ret.cantidad);
                        } else {
                            if (ret.incluye == "Si") {
                                subtotal = ret.total;
                                sub2 = subtotal / (calculoIVA / 100 + 1);
                                iva2 = sub2 * (calculoIVA / 100);
                                subtotal0 = parseFloat($("#total_p").val()) + 0;
                                subtotal12 =
                                        parseFloat($("#total_p2").val()) - parseFloat(sub2);
                                subtotal_total = parseFloat($("#sub").val()) - parseFloat(sub2);
                                iva12 = parseFloat($("#iva").val()) - parseFloat(iva2);
                                descu_total =
                                        parseFloat($("#desc").val()) - parseFloat(ret.cal_des);
                                subtotal0 = parseFloat(subtotal0);
                                subtotal12 = parseFloat(subtotal12);
                                subtotal_total = parseFloat(subtotal_total);
                                iva12 = parseFloat(iva12);
                                descu_total = parseFloat(descu_total);
                                suma_total =
                                        parseFloat($("#num").val()) - parseFloat(ret.cantidad);
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
                            descu_total =
                                    parseFloat($("#desc").val()) - parseFloat(ret.cal_des);
                            subtotal0 = parseFloat(subtotal0);
                            subtotal12 = parseFloat(subtotal12);
                            subtotal_total = parseFloat(subtotal_total);
                            iva12 = parseFloat(iva12);
                            descu_total = parseFloat(descu_total);
                            suma_total =
                                    parseFloat($("#num").val()) - parseFloat(ret.cantidad);
                        }
                    }
                }

                total_total =
                        parseFloat(total_total) +
                        (parseFloat(subtotal0) + parseFloat(subtotal12) + parseFloat(iva12));
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
                $("#descxax").val(descu_total.toFixed(2));
                $("#totx").val(total_total.toFixed(2));
                $("#items").val(item);
                $("#num").val(suma_total.toFixed(2));
                var su = jQuery("#list").jqGrid("delRowData", rowid);
                funcion_descuento_factura(false);
                if (su === true) {
                    rp_ge.processing = true;
                    $(".ui-icon-closethick").trigger("click");
                }
                return true;
            },
            processing: true,
        },
        afterSaveCell: function (rowid, name, val, iRow, iCol) {
            var subtotal0 = 0;
            var subtotal12 = 0;
            var iva12 = 0;
            var total_total = 0;
            var descu_total = 0;
            var result = 0;
            var iva1 = 0;
            var iva_pventa = 0;
            var subtotal_total = 0;
            var id = jQuery("#list").jqGrid("getGridParam", "selrow");
            jQuery("#list").jqGrid("restoreRow", id);
            var ret = jQuery("#list").jqGrid("getRowData", id);
            var cantidad = parseFloat(ret.cantidad);
            var total_con_iva = parseFloat(ret.pendiente);
            var cantidad = parseFloat(ret.cantidad);
            var total_con_iva = parseFloat(ret.pendiente);
            if (name == "cantidad") {
                var precio_grid = jQuery("#list").jqGrid("getCell", rowid, iCol + 1);
                var descuento_grid = jQuery("#list").jqGrid("getCell", rowid, iCol + 2);
                var precio = 0;
                var descuento = 0;
                var multi = 0;
                var total = 0;
                var desc = 0;
                var flotante = 0;
                var resultado = 0;
                $.ajax({
                    url: "obtener_stock_producto.php",
                    method: "GET",
                    data: {id: ret.cod_producto},
                    dataType: "json",
                    success: function (data) {
                        var valores2;
                        valores2 = data.split(",");
                        $("#disponibles").val(Number(valores2[0]));
                        console.log(" 1 INVENTARIO ES SI", valores2[2]);
                        if (valores2[2].trim() == 'Si') {
                            console.log(" 2 INVENTARIO ES SI" + val);
                            if (parseInt(val) > parseInt($("#disponibles").val())) {
                                console.log(" 3 CANTIDAD > DISPONIBLE");
                                alertify.error("Error.. Fuera de Stock cantidad disponible: " + $("#disponibles").val());
                                $("#list").jqGrid('editCell', iRow, iCol, true);
                            } else {
                                console.log("4 CANTIDAD < DISPONIBLE");
                                ////editar cantidad grid
                                let cod_prod = jQuery("#list").jqGrid("getCell", rowid, iCol - 2);
                                $.getJSON("buscar_cant_descu.php?id=" + cod_prod, (data) => {
                                    let cant_mayo = parseFloat(data[0]);
                                    let cant_nego = parseFloat(data[1]);
                                    let precio_tipo;

                                    if (+ret.descuento == 0) {
                                        if (cant_nego !== 0 && cantidad >= cant_nego) {
                                            $("#nego").prop("selected", true);
                                            precio_tipo = "NEGOCIO";
                                        } else if (
                                                cant_mayo !== 0 &&
                                                cant_nego !== 0 &&
                                                cantidad >= cant_mayo &&
                                                cantidad < cant_nego
                                                ) {
                                            $("#mayo").prop("selected", true);
                                            precio_tipo = "MAYORISTA";
                                        } else if (cant_mayo !== 0 && cantidad >= cant_mayo) {
                                            $("#mayo").prop("selected", true);
                                            precio_tipo = "MAYORISTA";
                                        } else {
                                            $("#mino").prop("selected", true);
                                            precio_tipo = "MINORISTA";
                                        }
                                    } else {
                                        $("#mino").prop("selected", true);
                                        precio_tipo = "MINORISTA";
                                    }


                                    console.log("nivel1::");
                                    $.getJSON(
                                            "search_grid.php?codigo_barras=" +
                                            cod_prod +
                                            "&cod=" +
                                            cod_prod +
                                            "&precio=" +
                                            precio_tipo,
                                            function (data) {
                                                let tama = data.length;
                                                if (tama != 0) {
                                                    let disponibles = parseFloat(data[4]);
                                                    if (cantidad > disponibles) {
                                                        alertify.alert(
                                                                "Fuera de Stock!<br>Cantidad disponible: " +
                                                                "<strong>" +
                                                                disponibles +
                                                                "</strong>"
                                                                );
                                                        jQuery("#list").jqGrid("setRowData", rowid, {
                                                            cantidad: disponibles,
                                                        });
                                                    } else {
                                                        for (let i = 0; i < tama; i = i + 12) {
                                                            pvp_u = data[i + 3];
                                                            pvp_ux = data[i + 2];
                                                            pvp_ux = parseFloat(pvp_ux).toFixed(4);
                                                            /*   pvp_u = parseFloat(pvp_u).toFixed(4);
                                                             */

                                                            console.log("pvp_u" + pvp_u);
                                                            console.log("pvp_ux" + pvp_ux);

                                                            jQuery("#list").jqGrid("setRowData", rowid, {
                                                                precio_u: pvp_u,
                                                                precio_ux: pvp_ux,
                                                            });
                                                            var precio = 0;
                                                            var pvp = 0;
                                                            var descuento = 0;
                                                            var descuentox = 0;
                                                            var descuentox = 0;
                                                            var multi = 0;
                                                            var multix = 0;
                                                            var total = 0;
                                                            var totalx = 0;
                                                            var desc = 0;
                                                            var flotante = 0;
                                                            var flotantex = 0;
                                                            var flotantex = 0;
                                                            var resultado = 0;
                                                            var resultadox = 0;
                                                            var resultadox = 0;
                                                            var precio_grid = jQuery("#list").jqGrid(
                                                                    "getCell",
                                                                    rowid,
                                                                    iCol + 1
                                                                    );
                                                            var descuento_grid = jQuery("#list").jqGrid(
                                                                    "getCell",
                                                                    rowid,
                                                                    iCol + 2
                                                                    );
                                                            var precio_gridx = jQuery("#list").jqGrid(
                                                                    "getCell",
                                                                    rowid,
                                                                    iCol + 4
                                                                    );

                                                            if (descuento_grid != "0") {
                                                                var ret = jQuery("#list").jqGrid("getRowData", id);

                                                                console.log(" 5 SIGUE");
                                                                desc = descuento_grid;
                                                                precio = parseFloat(precio_grid);
                                                                multi = parseFloat(val) * parseFloat(precio);
                                                                descuento = (multi * parseFloat(desc)) / 100;
                                                                flotante = parseFloat(descuento);
                                                                resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                                                total = multi - resultado;
                                                                if (ret.iva == "Si") {
                                                                    iva1 = (ret.precio_ux * calculoIVA) / 100;
                                                                    iva_pventa = iva1 + parseFloat(ret.precio_ux);
                                                                    result = ret.cantidad * numFormatter(2).format(iva_pventa);
                                                                } else {
                                                                    result = 0;
                                                                }
                                                                console.log("total::" + total);
                                                                jQuery("#list").jqGrid("setRowData", rowid, {
                                                                    totalx: numFormatter(2).format(total),
                                                                    total: total,
                                                                    pendiente: numFormatter(2).format(result),
                                                                    pvpuiva: 0,
                                                                    //                                                                    precio_u: numFormatter(2).format(ret.precio_ux),

                                                                    cal_des: resultado,
                                                                });
                                                                $("#codigo_barras").focus();
                                                            } else {
                                                                var ret = jQuery("#list").jqGrid("getRowData", id);
                                                                desc = descuento_grid;
                                                                precio = parseFloat(precio_grid);

                                                                console.log("precio" + precio);

                                                                multi = parseFloat(val) * parseFloat(precio);
                                                                descuento = (multi * parseFloat(desc)) / 100;
                                                                flotante = parseFloat(descuento);
                                                                resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                                                total = parseFloat(multi);
                                                                console.log("precio_u" + ret.precio_u);
                                                                if (ret.iva == "Si") {
                                                                    iva1 = (ret.precio_u * calculoIVA) / 100;
                                                                    iva_pventa = iva1 + parseFloat(ret.precio_u);
                                                                    result = ret.cantidad * numFormatter(2).format(iva_pventa);
                                                                } else {
                                                                    result = 0;
                                                                }
                                                                console.log("total1::" + total);
                                                                jQuery("#list").jqGrid("setRowData", rowid, {
                                                                    totalx: numFormatter(2).format(total),
                                                                    total: total,
                                                                    //                                                                    precio_u: numFormatter(2).format(ret.precio_ux),
                                                                    pendiente: numFormatter(2).format(result),
                                                                    pvpuiva: 0
                                                                });
                                                                $("#codigo_barras").focus();
                                                                $("#codigo_barras").select();
                                                                console.log("EEEE1");
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
                                                                if (dd["iva"] == "Si") {
                                                                    if (dd["incluye"] == "No") {
                                                                        subtotal = dd["total"];
                                                                        sub1 = subtotal;
                                                                        iva1 = sub1 * (calculoIVA / 100);
                                                                        subtotal0 = parseFloat(subtotal0) + 0;
                                                                        subtotal12 = parseFloat(subtotal12) + parseFloat(sub1);
                                                                        subtotal_total = parseFloat(subtotal0) + parseFloat(subtotal12);
                                                                        descu_total = parseFloat(descu_total) + parseFloat(dd["cal_des"]);
                                                                        iva12 = parseFloat(iva12) + parseFloat(iva1);
                                                                        subtotal0 = parseFloat(subtotal0);
                                                                        subtotal12 = parseFloat(subtotal12);
                                                                        subtotal_total = parseFloat(subtotal_total);
                                                                        iva12 = parseFloat(iva12);
                                                                        descu_total = parseFloat(descu_total);
                                                                    } else {
                                                                        if (dd["incluye"] == "Si") {
                                                                            subtotal = dd["total"];
                                                                            sub2 = subtotal / (calculoIVA / 100 + 1);
                                                                            iva2 = sub2 * (calculoIVA / 100);
                                                                            subtotal0 = parseFloat(subtotal0) + 0;
                                                                            subtotal12 = parseFloat(subtotal12) + parseFloat(sub2);
                                                                            subtotal_total = parseFloat(subtotal0) + parseFloat(subtotal12);
                                                                            iva12 = parseFloat(iva12) + parseFloat(iva2);
                                                                            descu_total =
                                                                                    parseFloat(descu_total) + parseFloat(dd["cal_des"]);
                                                                            subtotal0 = parseFloat(subtotal0);
                                                                            subtotal12 = parseFloat(subtotal12);
                                                                            subtotal_total = parseFloat(subtotal_total);
                                                                            iva12 = parseFloat(iva12);
                                                                            descu_total = parseFloat(descu_total);
                                                                        }
                                                                    }
                                                                } else {
                                                                    if (dd["iva"] == "No") {
                                                                        subtotal = dd["total"];
                                                                        sub = subtotal;
                                                                        subtotal0 = parseFloat(subtotal0) + parseFloat(sub);
                                                                        subtotal12 = parseFloat(subtotal12) + 0;
                                                                        subtotal_total = parseFloat(subtotal0) + parseFloat(subtotal12);
                                                                        iva12 = parseFloat(iva12) + 0;
                                                                        descu_total = parseFloat(descu_total) + parseFloat(dd["cal_des"]);
                                                                        subtotal0 = parseFloat(subtotal0);
                                                                        subtotal12 = parseFloat(subtotal12);
                                                                        subtotal_total = parseFloat(subtotal_total);
                                                                        iva12 = parseFloat(iva12);
                                                                        descu_total = parseFloat(descu_total);
                                                                    }
                                                                }
                                                            }

                                                            total_total =
                                                                    parseFloat(total_total) +
                                                                    (parseFloat(subtotal0) + parseFloat(subtotal12) + parseFloat(iva12));
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
                                                            $("#sub").val(subtotal_total);
                                                            $("#subx").val(subtotal_total.toFixed(2));

                                                        }
                                                    }
                                                }
                                            }
                                    );
                                });

                            }

                        } else {
                            console.log("r INVENTARIO ES NO");
                            let cod_prod = jQuery("#list").jqGrid("getCell", rowid, iCol - 2);
                            $.getJSON("buscar_cant_descu.php?id=" + cod_prod, (data) => {
                                let cant_mayo = parseFloat(data[0]);
                                let cant_nego = parseFloat(data[1]);
                                let precio_tipo;
                                if (cant_nego !== 0 && cantidad >= cant_nego) {
                                    $("#nego").prop("selected", true);
                                    precio_tipo = "NEGOCIO";
                                } else if (
                                        cant_mayo !== 0 &&
                                        cant_nego !== 0 &&
                                        cantidad >= cant_mayo &&
                                        cantidad < cant_nego
                                        ) {
                                    $("#mayo").prop("selected", true);
                                    precio_tipo = "MAYORISTA";
                                } else if (cant_mayo !== 0 && cantidad >= cant_mayo) {
                                    $("#mayo").prop("selected", true);
                                    precio_tipo = "MAYORISTA";
                                } else {
                                    $("#mino").prop("selected", true);
                                    precio_tipo = "MINORISTA";
                                }
                                $.getJSON(
                                        "search_grid.php?codigo_barras=" +
                                        cod_prod +
                                        "&codigo=" +
                                        cod_prod +
                                        "&precio=" +
                                        precio_tipo,
                                        function (data) {
                                            let tama = data.length;
                                            if (tama != 0) {
                                                let disponibles = parseFloat(data[4]);
                                                if (cantidad > disponibles) {
                                                    alertify.alert(
                                                            "Fuera de Stock!<br>Cantidad disponible: " +
                                                            "<strong>" +
                                                            disponibles +
                                                            "</strong>"
                                                            );
                                                    jQuery("#list").jqGrid("setRowData", rowid, {
                                                        cantidad: disponibles,
                                                    });
                                                } else {
                                                    for (let i = 0; i < tama; i = i + 12) {
                                                        pvp_u = data[i + 3];
                                                        pvp_ux = data[i + 2];
                                                        pvp_ux = parseFloat(pvp_ux).toFixed(4);
                                                        /*  pvp_u = parseFloat(pvp_u).toFixed(4);
                                                         */
                                                        jQuery("#list").jqGrid("setRowData", rowid, {
                                                            precio_u: pvp_u,
                                                            precio_ux: pvp_ux,
                                                        });
                                                        var precio = 0;
                                                        var pvp = 0;
                                                        var descuento = 0;
                                                        var descuentox = 0;
                                                        var descuentox = 0;
                                                        var multi = 0;
                                                        var multix = 0;
                                                        var total = 0;
                                                        var totalx = 0;
                                                        var desc = 0;
                                                        var flotante = 0;
                                                        var flotantex = 0;
                                                        var flotantex = 0;
                                                        var resultado = 0;
                                                        var resultadox = 0;
                                                        var resultadox = 0;
                                                        var precio_grid = jQuery("#list").jqGrid(
                                                                "getCell",
                                                                rowid,
                                                                iCol + 1
                                                                );
                                                        var descuento_grid = jQuery("#list").jqGrid(
                                                                "getCell",
                                                                rowid,
                                                                iCol + 2
                                                                );
                                                        var precio_gridx = jQuery("#list").jqGrid(
                                                                "getCell",
                                                                rowid,
                                                                iCol + 4
                                                                );


                                                        if (descuento_grid != "0") {
                                                            var ret = jQuery("#list").jqGrid("getRowData", id);

                                                            desc = descuento_grid;
                                                            precio = parseFloat(precio_grid);
                                                            multi = parseFloat(val) * parseFloat(precio);
                                                            descuento = (multi * parseFloat(desc)) / 100;
                                                            flotante = parseFloat(descuento);
                                                            resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                                            total = multi - resultado;
                                                            if (ret.iva == "Si") {
                                                                iva1 = (ret.precio_ux * calculoIVA) / 100;
                                                                iva_pventa = iva1 + parseFloat(ret.precio_ux);
                                                                result = ret.cantidad * numFormatter(2).format(iva_pventa);
                                                            } else {
                                                                result = 0;
                                                            }

                                                            jQuery("#list").jqGrid("setRowData", rowid, {
                                                                totalx: numFormatter(2).format(total),
                                                                total: total,
                                                                pendiente: numFormatter(2).format(result),
                                                                pvpuiva: 0,
                                                                //                                                                precio_u: numFormatter(2).format(ret.precio_ux),
                                                                cal_des: resultado,
                                                            });
                                                            $("#codigo_barras").focus();
                                                        } else {
                                                            var ret = jQuery("#list").jqGrid("getRowData", id);

                                                            desc = descuento_grid;
                                                            precio = parseFloat(precio_grid);
                                                            multi = parseFloat(val) * parseFloat(precio);
                                                            descuento = (multi * parseFloat(desc)) / 100;
                                                            flotante = parseFloat(descuento);
                                                            resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                                            total = parseFloat(multi);
                                                            console.log(ret.precio_u);
                                                            if (ret.iva == "Si") {
                                                                iva1 = (ret.precio_u * calculoIVA) / 100;
                                                                iva_pventa = iva1 + parseFloat(ret.precio_u);
                                                                result = ret.cantidad * numFormatter(2).format(iva_pventa);
                                                            } else {
                                                                result = 0;
                                                            }
                                                            jQuery("#list").jqGrid("setRowData", rowid, {
                                                                totalx: numFormatter(2).format(total),
                                                                total: total,
                                                                //                                                                precio_u: numFormatter(2).format(ret.precio_ux),
                                                                pendiente: numFormatter(2).format(result),
                                                                pvpuiva: 0,
                                                            });
                                                            $("#codigo_barras").focus();
                                                            $("#codigo_barras").select();
                                                            console.log("EEEE1");
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
                                                            if (dd["iva"] == "Si") {
                                                                if (dd["incluye"] == "No") {
                                                                    subtotal = dd["total"];
                                                                    sub1 = subtotal;
                                                                    iva1 = sub1 * (calculoIVA / 100);
                                                                    subtotal0 = parseFloat(subtotal0) + 0;
                                                                    subtotal12 = parseFloat(subtotal12) + parseFloat(sub1);
                                                                    subtotal_total = parseFloat(subtotal0) + parseFloat(subtotal12);
                                                                    descu_total = parseFloat(descu_total) + parseFloat(dd["cal_des"]);
                                                                    iva12 = parseFloat(iva12) + parseFloat(iva1);
                                                                    subtotal0 = parseFloat(subtotal0);
                                                                    subtotal12 = parseFloat(subtotal12);
                                                                    subtotal_total = parseFloat(subtotal_total);
                                                                    iva12 = parseFloat(iva12);
                                                                    descu_total = parseFloat(descu_total);
                                                                } else {
                                                                    if (dd["incluye"] == "Si") {
                                                                        subtotal = dd["total"];
                                                                        sub2 = subtotal / (calculoIVA / 100 + 1);
                                                                        iva2 = sub2 * (calculoIVA / 100);
                                                                        subtotal0 = parseFloat(subtotal0) + 0;
                                                                        subtotal12 = parseFloat(subtotal12) + parseFloat(sub2);
                                                                        subtotal_total = parseFloat(subtotal0) + parseFloat(subtotal12);
                                                                        iva12 = parseFloat(iva12) + parseFloat(iva2);
                                                                        descu_total =
                                                                                parseFloat(descu_total) + parseFloat(dd["cal_des"]);
                                                                        subtotal0 = parseFloat(subtotal0);
                                                                        subtotal12 = parseFloat(subtotal12);
                                                                        subtotal_total = parseFloat(subtotal_total);
                                                                        iva12 = parseFloat(iva12);
                                                                        descu_total = parseFloat(descu_total);
                                                                    }
                                                                }
                                                            } else {
                                                                if (dd["iva"] == "No") {
                                                                    subtotal = dd["total"];
                                                                    sub = subtotal;
                                                                    subtotal0 = parseFloat(subtotal0) + parseFloat(sub);
                                                                    subtotal12 = parseFloat(subtotal12) + 0;
                                                                    subtotal_total = parseFloat(subtotal0) + parseFloat(subtotal12);
                                                                    iva12 = parseFloat(iva12) + 0;
                                                                    descu_total = parseFloat(descu_total) + parseFloat(dd["cal_des"]);
                                                                    subtotal0 = parseFloat(subtotal0);
                                                                    subtotal12 = parseFloat(subtotal12);
                                                                    subtotal_total = parseFloat(subtotal_total);
                                                                    iva12 = parseFloat(iva12);
                                                                    descu_total = parseFloat(descu_total);
                                                                }
                                                            }
                                                        }

                                                        total_total =
                                                                parseFloat(total_total) +
                                                                (parseFloat(subtotal0) + parseFloat(subtotal12) + parseFloat(iva12));
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
                                                        $("#sub").val(subtotal_total);
                                                        $("#subx").val(subtotal_total.toFixed(2));

                                                        //poner codigo
                                                    }
                                                }
                                            }
                                        }
                                );
                            });






                        }



                    }


                });
            }

            if (name == "precio_ux") {
                var cantidad_grid = jQuery("#list").jqGrid("getCell", rowid, iCol - 5);
                var descuento_grid = jQuery("#list").jqGrid("getCell", rowid, iCol - 3);
                var precio = 0;
                var descuento = 0;
                var multi = 0;
                var total = 0;
                var desc = 0;
                var flotante = 0;
                var resultado = 0;
                if (descuento_grid != "0") {
                    desc = descuento_grid;
                    precio = parseFloat(val);
                    multi = parseFloat(cantidad_grid) * parseFloat(precio);
                    descuento = (multi * parseFloat(desc)) / 100;
                    flotante = parseFloat(descuento);
                    resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                    total = multi - resultado;
                    if (ret.iva == "Si") {
                        iva1 = (precio * calculoIVA) / 100;
                        iva_pventa = iva1 + parseFloat(precio);
                        result = ret.cantidad * numFormatter(2).format(iva_pventa);
                    } else {
                        result = 0;
                    }
                    jQuery("#list").jqGrid("setRowData", rowid, {
                        totalx: numFormatter(2).format(total),
                        total: total,
                        pendiente: numFormatter(2).format(result),
                        //precio_u: numFormatter(2).format(ret.precio_ux),
                        cal_des: resultado,
                    });
                } else {
                    desc = descuento_grid;
                    precio = parseFloat(val);
                    multi = parseFloat(cantidad_grid) * parseFloat(precio);
                    descuento = (multi * parseFloat(desc)) / 100;
                    flotante = parseFloat(descuento);
                    resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                    total = parseFloat(multi);
                    if (ret.iva == "Si") {
                        iva1 = (precio * calculoIVA) / 100;
                        iva_pventa = iva1 + parseFloat(precio);
                        result = ret.cantidad * numFormatter(2).format(iva_pventa);
                    } else {
                        result = 0;
                    }

                    jQuery("#list").jqGrid("setRowData", rowid, {
                        totalx: numFormatter(2).format(total),
                        total: total,
                        //precio_u: numFormatter(2).format(ret.precio_ux),
                        pendiente: numFormatter(2).format(result),
                    });
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
                    if (dd["iva"] == "Si") {
                        if (dd["incluye"] == "No") {
                            subtotal = dd["total"];
                            sub1 = subtotal;
                            iva1 = sub1 * (calculoIVA / 100);
                            subtotal0 = parseFloat(subtotal0) + 0;
                            subtotal12 = parseFloat(subtotal12) + parseFloat(sub1);
                            subtotal_total = parseFloat(subtotal0) + parseFloat(subtotal12);
                            descu_total = parseFloat(descu_total) + parseFloat(dd["cal_des"]);
                            iva12 = parseFloat(iva12) + parseFloat(iva1);
                            subtotal0 = parseFloat(subtotal0);
                            subtotal12 = parseFloat(subtotal12);
                            subtotal_total = parseFloat(subtotal_total);
                            iva12 = parseFloat(iva12);
                            descu_total = parseFloat(descu_total);
                        } else {
                            if (dd["incluye"] == "Si") {
                                subtotal = dd["total"];
                                sub2 = subtotal / (calculoIVA / 100 + 1);
                                iva2 = sub2 * (calculoIVA / 100);
                                subtotal0 = parseFloat(subtotal0) + 0;
                                subtotal12 = parseFloat(subtotal12) + parseFloat(sub2);
                                subtotal_total = parseFloat(subtotal0) + parseFloat(subtotal12);
                                iva12 = parseFloat(iva12) + parseFloat(iva2);
                                descu_total =
                                        parseFloat(descu_total) + parseFloat(dd["cal_des"]);
                                subtotal0 = parseFloat(subtotal0);
                                subtotal12 = parseFloat(subtotal12);
                                subtotal_total = parseFloat(subtotal_total);
                                iva12 = parseFloat(iva12);
                                descu_total = parseFloat(descu_total);
                            }
                        }
                    } else {
                        if (dd["iva"] == "No") {
                            subtotal = dd["total"];
                            sub = subtotal;
                            subtotal0 = parseFloat(subtotal0) + parseFloat(sub);
                            subtotal12 = parseFloat(subtotal12) + 0;
                            subtotal_total = parseFloat(subtotal0) + parseFloat(subtotal12);
                            iva12 = parseFloat(iva12) + 0;
                            descu_total = parseFloat(descu_total) + parseFloat(dd["cal_des"]);
                            subtotal0 = parseFloat(subtotal0);
                            subtotal12 = parseFloat(subtotal12);
                            subtotal_total = parseFloat(subtotal_total);
                            iva12 = parseFloat(iva12);
                            descu_total = parseFloat(descu_total);
                        }
                    }
                }

                total_total =
                        parseFloat(total_total) +
                        (parseFloat(subtotal0) + parseFloat(subtotal12) + parseFloat(iva12));
                total_total = parseFloat(total_total);
                $("#total_p").val(subtotal0);
                $("#total_p2").val(subtotal12);
                $("#iva").val(iva12);
                $("#desc").val(descu_total);
                $("#tot").val(total_total);
                $("#total_px").val(subtotal0.toFixed(2));
                $("#total_p2x").val(subtotal12.toFixed(2));
                $("#ivax").val(iva12.toFixed(2));
                $("#descxax").val(descu_total.toFixed(2));
                $("#totx").val(total_total.toFixed(2));
                $("#descx").val(descu_total.toFixed(2));
                $("#totx").val(total_total.toFixed(2));
                $("#codigo_barras").focus();
                $("#sub").val(subtotal_total);
                $("#subx").val(subtotal_total.toFixed(2));
                //                $("#codigo_barras").focus();
            }
        },
    });
    // buscador facturas ventas
    jQuery("#list2")
            .jqGrid({
                url: "xmlBuscarFacturaVenta.php",
                datatype: "xml",
                colNames: [
                    "ID",
                    "IDENTIFICACIÓN",
                    "CLIENTE",
                    "FACTURA NRO.",
                    "MONTO TOTAL",
                    "FECHA",
                ],
                colModel: [
                    {
                        name: "id_factura_venta",
                        index: "id_factura_venta",
                        editable: false,
                        search: false,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "left",
                        frozen: true,
                        width: 50,
                    },
                    {
                        name: "identificacion",
                        index: "identificacion",
                        editable: false,
                        search: true,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "left",
                        frozen: true,
                        width: 150,
                    },
                    {
                        name: "nombres_cli",
                        index: "nombres_cli",
                        editable: true,
                        search: true,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "left",
                        frozen: true,
                        width: 200,
                    },
                    {
                        name: "num_factura",
                        index: "num_factura",
                        editable: true,
                        search: true,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "right",
                        frozen: true,
                        width: 200,
                    },
                    {
                        name: "total_venta",
                        index: "total_venta",
                        editable: true,
                        search: false,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "right",
                        frozen: true,
                        width: 100,
                    },
                    {
                        name: "fecha_actual",
                        index: "fecha_actual",
                        editable: true,
                        search: false,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "right",
                        frozen: true,
                        width: 100,
                    },
                ],
                rowNum: 30,
                width: 750,
                height: 220,
                sortable: true,
                rowList: [10, 20, 30],
                pager: jQuery("#pager2"),
                sortname: "id_factura_venta",
                sortorder: "desc",
                viewrecords: true,
                ondblClickRow: function () {
                    var id = jQuery("#list2").jqGrid("getGridParam", "selrow");
                    jQuery("#list2").jqGrid("restoreRow", id);
                    var valor = null;
                    if (id) {
                        var ret = jQuery("#list2").jqGrid("getRowData", id);
                        valor = ret.id_factura_venta;
                    }
                    $("#clavefactura").val("");
                    limpiarCamposRetencion();

                    cargarFacturaDblclick(valor);
                    /* var id = jQuery("#list2").jqGrid("getGridParam", "selrow");
                     jQuery("#list2").jqGrid("restoreRow", id);
                     if (id) {
                     var ret = jQuery("#list2").jqGrid("getRowData", id);
                     var valor = ret.id_factura_venta;
                     obtenerCentroCosoTransaccion(valor, 'FACTURA');
                     /////////////agregregar datos factura////////
                     $("#comprobante").val(valor);
                     $("#btnGuardar").attr("disabled", true);
                     //            $("#btnGuardarTemporal").attr("disabled", true);
                     
                     // $("#num_factura").attr("disabled", true);
                     $("#id_cliente").val("");
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
                     $("#cuotas").children().remove().end();
                     $("#list").jqGrid("clearGridData", true);
                     $("#total_p").val("0.000");
                     $("#total_p2").val("0.000");
                     $("#iva").val("0.000");
                     $("#desc").val("0.000");
                     $("#tot").val("0.000");
                     $("#descxa").val("0");
                     $("#total_px").val("0.000");
                     $("#total_p2x").val("0.000");
                     $("#ivax").val("0.000");
                     $("#descxax").val("0.000");
                     $("#totx").val("0.000");
                     $.getJSON("retornar_factura_venta.php?com=" + valor, function (data) {
                     var tama = data.length;
                     t = data[23];
                     if (tama !== 0) {
                     for (var i = 0; i < tama; i = i + 24) {
                     $("#id_factura_venta").val(data[i]);
                     $("#fecha_actual").val(data[i + 1]);
                     $("#hora_actual").val(data[i + 2]);
                     $("#digitador").val(data[i + 3] + " " + data[i + 4]);
                     var num = data[i + 5];
                     var res = num;
                     $("#num_factura").val(res);
                     $("#id_cliente").val(data[i + 6]);
                     $("#ruc_ci").val(data[i + 7]);
                     $("#nombre_cliente").val(data[i + 8]);
                     $("#direccion_cliente").val(data[i + 9]);
                     $("#telefono_cliente").val(data[i + 10]);
                     $("#correo").val(data[i + 11]);
                     $("#autorizacion").val(data[i + 12]);
                     $("#fecha_auto").val(data[i + 13]);
                     $("#fecha_caducidad").val(data[i + 14]);
                     $("#cancelacion").val(data[i + 15]);
                     $("#tipo_precio").val(data[i + 16]);
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
                     if (data[i + 23] == "0") {
                     $("#btnModificar").attr("disabled", false);
                     $("#btnGuardar").attr("disabled", false);
                     $("#codigo_barras").attr("disabled", false);
                     $("#codigo").attr("disabled", false);
                     $("#producto").attr("disabled", false);
                     $("#cantidad").attr("disabled", false);
                     $("#p_venta").attr("disabled", false);
                     $("#descuento").attr("disabled", false);
                     $("#formaspago").attr("disabled", false);
                     } else if (data[i + 23] == "1") {
                     $("#btnModificar").attr("disabled", "disabled");
                     }
                     }
                     
                     $("#total_p").val(data[i + 18]);
                     $("#total_p2").val(data[i + 19]);
                     $("#sub").val(
                     parseFloat(data[i + 18]) + parseFloat(data[i + 19])
                     );
                     $("#iva").val(data[i + 20]);
                     $("#desc").val(data[i + 21]);
                     $("#tot").val(data[i + 22]);
                     $("#total_px").val(parseFloat(data[i + 18]).toFixed(2));
                     $("#total_p2x").val(parseFloat(data[i + 19]).toFixed(2));
                     $("#subx").val(
                     (parseFloat(data[i + 18]) + parseFloat(data[i + 19])).toFixed(
                     2
                     )
                     );
                     $("#ivax").val(parseFloat(data[i + 20]).toFixed(2));
                     $("#descxax").val(parseFloat(data[i + 21]).toFixed(2));
                     $("#totx").val(parseFloat(data[i + 22]).toFixed(2));
                     }
                     volver_rf();
                     volver_ri();
                     }
                     });
                     $.getJSON(
                     "retornar_factura_venta_credito.php?com=" + valor,
                     function (data) {
                     var tama = data.length;
                     if (tama != 0) {
                     for (var i = 0; i < tama; i = i + 4) {
                     $("#formaspago").val(data[i]);
                     $("#adelanto").val(data[i + 1]);
                     $("#meses").val(data[i + 2]);
                     //////////calcular meses//////////
                     if (data[i + 2] > 1) {
                     $("#cuotas").attr("disabled", false);
                     for (var j = 1; j <= data[i + 2] - 1; j++) {
                     var calcu = data[i + 3] / data[i + 2];
                     var entero = Math.floor(calcu).toFixed(2);
                     $("#cuotas").append("<option>" + entero + "</option>");
                     }
                     var calcu1 = entero * (data[i + 2] - 1);
                     var sal = data[i + 3] - calcu1;
                     var entero2 = sal.toFixed(2);
                     $("#cuotas").append("<option>" + entero2 + "</option>");
                     } else {
                     $("#cuotas").attr("disabled", false);
                     $("#cuotas").append("<option>" + data[i + 3] + "</option>");
                     }
                     }
                     }
                     }
                     );
                     $.getJSON(
                     "retornar_formas_mixto_grid.php?com=" + valor,
                     function (data) {
                     $("#listPagoreten_mixto").jqGrid("clearGridData", true);
                     var tama = data.length;
                     if (tama != 0) {
                     for (var i = 0; i < tama; i = i + 6) {
                     var datarow = {
                     forma_pago_mixto: data[i],
                     tarjeta_credito: data[i + 1],
                     num_documento: data[i + 2],
                     valor: data[i + 3],
                     id_cuenta: data[i + 4],
                     fecha_vencimiento: data[i + 5],
                     };
                     var su = jQuery("#listPagoreten_mixto").jqGrid("addRowData", data[i], datarow);
                     }
                     }
                     }
                     );
                     $.getJSON(
                     "retornar_retenciones_grid.php?com=" + valor,
                     function (data) {
                     $("#listPagoreten").jqGrid("clearGridData", true);
                     var tama = data.length;
                     if (tama != 0) {
                     $("#btnGuardarRetenciones").attr("disabled", true);
                     for (var i = 0; i < tama; i = i + 6) {
                     var datarow = {
                     base_imponible: data[i],
                     impuesto: data[i + 1],
                     porcent_reten: data[i + 2],
                     valor_retenido: data[i + 3],
                     };
                     var num = data[i + 5];
                     var res = num.substr(8, 20);
                     $("#serie_retencion").val(num);
                     var su = jQuery("#listPagoreten").jqGrid("addRowData", data[i], datarow);
                     }
                     }
                     }
                     );
                     $.getJSON(
                     "retornar_factura_venta2.php?com=" + valor,
                     function (data) {
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
                     for (var i = 0; i < tama; i = i + 13) {
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
                     incluye: data[i + 9],
                     cantidad_unidad: data[i + 10],
                     unidad_medida: data[i + 11],
                     detalle_producto: data[i + 12]
                     };
                     var su = jQuery("#list").jqGrid("addRowData", data[i], datarow);
                     suma_total = suma_total + parseFloat(data[i + 3]);
                     }
                     var fila = jQuery("#list").jqGrid("getRowData");
                     $("#items").val(fila.length);
                     $("#num").val(suma_total);
                     }
                     }
                     );
                     $("#buscar_facturas_venta").dialog("close");
                     $("#tipo_busqueda").dialog("close");
                     } else {
                     alertify.alert("Seleccione una Factura");
                     } */
                },
            })
            .jqGrid(
                    "navGrid",
                    "#pager2",
                    {
                        add: false,
                        edit: false,
                        del: false,
                        refresh: true,
                        search: true,
                        view: true,
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
    jQuery("#list2").jqGrid("navButtonAdd", "#pager2", {
        caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#list2").jqGrid("getGridParam", "selrow");
            jQuery("#list2").jqGrid("restoreRow", id);
            if (id) {
                var ret = jQuery("#list2").jqGrid("getRowData", id);
                var valor = ret.id_factura_venta;
                /////////////agregregar datos factura////////
                //                $("#comprobante").val(valor);
                $("#btnGuardar").attr("disabled", true);
                // $("#num_factura").attr("disabled", true);
                $("#id_cliente").val("");
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
                $("#cuotas").children().remove().end();
                $("#list").jqGrid("clearGridData", true);
                $("#total_p").val("0.000");
                $("#total_p2").val("0.000");
                $("#iva").val("0.000");
                $("#desc").val("0.000");
                $("#descxa").val("0");
                $("#total_px").val("0.000");
                $("#total_p2x").val("0.000");
                $("#ivax").val("0.000");
                $("#descxax").val("0.000");
                $("#totx").val("0.000");
                $.getJSON("retornar_factura_venta.php?com=" + valor, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 23) {
                            $("#id_factura_venta").val(data[i]);

                            $("#fecha_actual").val(data[i + 1]);
                            $("#hora_actual").val(data[i + 2]);
                            $("#digitador").val(data[i + 3] + " " + data[i + 4]);
                            var num = data[i + 5];
                            var res = num.substr(8, 20);
                            $("#num_factura").val(res);

                            $("#id_cliente").val(data[i + 6]);
                            $("#ruc_ci").val(data[i + 7]);
                            $("#nombre_cliente").val(data[i + 8]);
                            $("#direccion_cliente").val(data[i + 9]);
                            $("#telefono_cliente").val(data[i + 10]);
                            $("#correo").val(data[i + 11]);
                            $("#autorizacion").val(data[i + 12]);
                            $("#fecha_auto").val(data[i + 13]);
                            $("#fecha_caducidad").val(data[i + 14]);
                            $("#cancelacion").val(data[i + 15]);
                            $("#tipo_precio").val(data[i + 16]);
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
                            $("#sub").val(
                                    parseFloat(data[i + 18]) + parseFloat(data[i + 19])
                                    );
                            $("#iva").val(data[i + 20]);
                            $("#desc").val(data[i + 21]);
                            $("#tot").val(data[i + 22]);
                            $("#total_px").val(parseFloat(data[i + 18]).toFixed(2));
                            $("#total_p2x").val(parseFloat(data[i + 19]).toFixed(2));
                            $("#subx").val(
                                    (parseFloat(data[i + 18]) + parseFloat(data[i + 19])).toFixed(2)
                                    );
                            $("#ivax").val(parseFloat(data[i + 20]).toFixed(2));
                            $("#descxax").val(parseFloat(data[i + 21]).toFixed(2));
                            $("#totx").val(parseFloat(data[i + 22]).toFixed(2));

                            $("#id_factura_venta").trigger("change");
                        }
                    }
                });
                $.getJSON(
                        "retornar_factura_venta_credito.php?com=" + valor,
                        function (data) {
                            var tama = data.length;
                            if (tama != 0) {
                                for (var i = 0; i < tama; i = i + 4) {
                                    $("#formaspago").val(data[i]);
                                    $("#adelanto").val(data[i + 1]);
                                    $("#meses").val(data[i + 2]);
                                    //////////calcular meses//////////
                                    if (data[i + 2] > 1) {
                                        $("#cuotas").attr("disabled", false);
                                        for (var j = 1; j <= data[i + 2] - 1; j++) {
                                            var calcu = data[i + 3] / data[i + 2];
                                            var entero = Math.floor(calcu).toFixed(2);
                                            $("#cuotas").append("<option>" + entero + "</option>");
                                        }
                                        var calcu1 = entero * (data[i + 2] - 1);
                                        var sal = data[i + 3] - calcu1;
                                        var entero2 = sal.toFixed(2);
                                        $("#cuotas").append("<option>" + entero2 + "</option>");
                                    } else {
                                        $("#cuotas").attr("disabled", false);
                                        $("#cuotas").append("<option>" + data[i + 3] + "</option>");
                                    }
                                }
                            }
                        }
                );
                $.getJSON(
                        "retornar_formas_mixto_grid.php?com=" + valor,
                        function (data) {
                            $("#listPagoreten_mixto").jqGrid("clearGridData", true);
                            var tama = data.length;
                            if (tama != 0) {
                                for (var i = 0; i < tama; i = i + 6) {
                                    var datarow = {
                                        forma_pago_mixto: data[i],
                                        tarjeta_credito: data[i + 1],
                                        num_documento: data[i + 2],
                                        valor: data[i + 3],
                                        id_cuenta: data[i + 4],
                                        fecha_vencimiento: data[i + 5],
                                    };
                                    var su = jQuery("#listPagoreten_mixto").jqGrid("addRowData", data[i], datarow);
                                }
                            }
                        }
                );
                $.getJSON("retornar_factura_venta2.php?com=" + valor, function (data) {
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
                        for (var i = 0; i < tama; i = i + 13) {
                            desc = data[i + 5];
                            precio = parseFloat(data[i + 4]);
                            multi = parseFloat(data[i + 3]) * parseFloat(data[i + 4]);
                            descuento = (multi * parseFloat(desc)) / 100;
                            flotante = parseFloat(descuento);
                            resultado =
                                    Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
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
                                incluye: data[i + 9],
                                cantidad_unidad: data[i + 10],
                                unidad_medida: data[i + 11],
                                detalle_producto: data[i + 12]
                            };
                            var su = jQuery("#list").jqGrid("addRowData", data[i], datarow);
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
        },
    });
    // fin tabla

    // buscador notas ventas
    jQuery("#list5")
            .jqGrid({
                url: "xmlBuscarNotaVenta.php",
                datatype: "xml",
                colNames: ["ID", "IDENTIFICACIÓN", "CLIENTE", "MONTO TOTAL", "FECHA"],
                colModel: [
                    {
                        name: "id_facturas_novalidas",
                        index: "id_facturas_novalidas",
                        editable: false,
                        search: false,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "left",
                        frozen: true,
                        width: 50,
                    },
                    {
                        name: "identificacion",
                        index: "identificacion",
                        editable: false,
                        search: true,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "left",
                        frozen: true,
                        width: 150,
                    },
                    {
                        name: "nombres_cli",
                        index: "nombres_cli",
                        editable: true,
                        search: true,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "left",
                        frozen: true,
                        width: 200,
                    },
                    {
                        name: "total_venta",
                        index: "total_venta",
                        editable: true,
                        search: false,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "right",
                        frozen: true,
                        width: 100,
                    },
                    {
                        name: "fecha_actual",
                        index: "fecha_actual",
                        editable: true,
                        search: false,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "right",
                        frozen: true,
                        width: 100,
                    },
                ],
                rowNum: 30,
                width: 750,
                height: 220,
                sortable: true,
                rowList: [10, 20, 30],
                pager: jQuery("#pager5"),
                sortname: "id_facturas_novalidas",
                sortorder: "desc",
                viewrecords: true,
                ondblClickRow: function () {
                    var id = jQuery("#list5").jqGrid("getGridParam", "selrow");
                    jQuery("#list5").jqGrid("restoreRow", id);
                    if (id) {
                        var ret = jQuery("#list5").jqGrid("getRowData", id);
                        var valor = ret.id_facturas_novalidas;
                        obtenerCentroCosoTransaccion(valor, 'NOTA');
                        // agregregar datos factura
                        $("#comprobante").val(valor);
                        $("#btnGuardar").attr("disabled", true);
                        $("#btnModificar").attr("disabled", true);
                        // $("#num_factura").attr("disabled", "disabled");
                        $("#ruc_ci").attr("disabled", "disabled");
                        $("#nombre_cliente").attr("disabled", "disabled");
                        $("#direccion_cliente").attr("disabled", "disabled");
                        $("#telefono_cliente").attr("disabled", "disabled");
                        //          $("#correo").attr("disabled", "disabled");
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
                        $("#cuotas").children().remove().end();
                        $("#cuotas").attr("disabled", true);
                        $("#list").jqGrid("clearGridData", true);
                        $("#total_p").val("0.0000");
                        $("#total_p2").val("0.0000");
                        $("#iva").val("0.0000");
                        $("#desc").val("0.0000");
                        $("#tot").val("0.0000");
                        $("#total_px").val("0.0000");
                        $("#total_p2x").val("0.0000");
                        $("#ivax").val("0.0000");
                        $("#descxax").val("0.0000");
                        $("#totx").val("0.0000");
                        $.getJSON("retornar_nota_venta.php?com=" + valor, function (data) {
                            var tama = data.length;
                            if (tama != 0) {
                                for (var i = 0; i < tama; i = i + 17) {
                                    $("#fecha_actual").val(data[i]);
                                    $("#hora_actual").val(data[i + 1]);
                                    $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                                    $("#id_cliente").val(data[i + 4]);
                                    $("#ruc_ci").val(data[i + 5]);
                                    $("#nombre_cliente").val(data[i + 6]);
                                    $("#direccion_cliente").val(data[i + 7]);
                                    $("#telefono_cliente").val(data[i + 8]);
                                    $("#correo").val(data[i + 9]);
                                    $("#tipo_precio").val(data[i + 10]);
                                    if (data[i + 11] == "Pasivo") {
                                        $("#estado").append($("<h3>").text("Anulada"));
                                        $("#estado h3").css("color", "red");
                                        $("#btnAnular").attr("disabled", "disabled");
                                    } else {
                                        $("#estado h3").remove();
                                        $("#btnAnular").attr("disabled", "disabled");
                                        $("#btnAnular").attr("disabled", false);
                                    }

                                    $("#total_p").val(data[i + 12]);
                                    $("#total_p2").val(data[i + 13]);
                                    $("#iva").val(data[i + 14]);
                                    $("#desc").val(data[i + 15]);
                                    $("#tot").val(data[i + 16]);
                                    $("#total_px").val(parseFloat(data[i + 12]).toFixed(4));
                                    $("#total_p2x").val(parseFloat(data[i + 13]).toFixed(4));
                                    $("#ivax").val(parseFloat(data[i + 14]).toFixed(4));
                                    $("#descxax").val(parseFloat(data[i + 15]));
                                    $("#totx").val(parseFloat(data[i + 16]).toFixed(2));
                                }
                            }
                        });
                        // fin

                        // llamar detalle facturas no validas
                        $.getJSON("retornar_nota_venta2.php?com=" + valor, function (data) {
                            var tama = data.length;
                            if (tama !== 0) {
                                for (var i = 0; i < tama; i = i + 12) {
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
                                        pendiente: data[i + 8],
                                        cantidad_unidad: data[i + 9],
                                        unidad_medida: data[i + 10],
                                        detalle_producto: data[i + 11],
                                    };
                                    var su = jQuery("#list").jqGrid("addRowData", data[i], datarow);
                                }
                            }
                        });
                        // fin
                        $.getJSON("retornar_mixto_grid_nota.php?com=" + valor,
                                function (data) {
                                    $("#listPagoreten_mixto").jqGrid("clearGridData", true);
                                    var tama = data.length;
                                    if (tama != 0) {
                                        for (var i = 0; i < tama; i = i + 5) {
                                            var datarow = {
                                                forma_pago_mixto: data[i],
                                                tarjeta_credito: data[i + 1],
                                                num_documento: data[i + 2],
                                                valor: data[i + 3],
                                                id_cuenta: data[i + 4],
                                            };
                                            var su = jQuery("#listPagoreten_mixto").jqGrid(
                                                    "addRowData",
                                                    data[i],
                                                    datarow
                                                    );
                                        }
                                    }
                                }
                        );

                        $("#buscar_notas_venta").dialog("close");
                        $("#tipo_busqueda").dialog("close");
                    } else {
                        alertify.alert("Seleccione una Factura");
                    }
                },
            })
            .jqGrid(
                    "navGrid",
                    "#pager5",
                    {
                        add: false,
                        edit: false,
                        del: false,
                        refresh: true,
                        search: true,
                        view: true,
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
    jQuery("#list5").jqGrid("navButtonAdd", "#pager5", {
        caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#list5").jqGrid("getGridParam", "selrow");
            jQuery("#list5").jqGrid("restoreRow", id);
            if (id) {
                var ret = jQuery("#list5").jqGrid("getRowData", id);
                var valor = ret.id_facturas_novalidas;
                // agregregar datos nota venta
                //                $("#comprobante").val(valor);
                $("#btnGuardar").attr("disabled", true);
                $("#btnModificar").attr("disabled", true);
                // $("#num_factura").attr("disabled", "disabled");
                $("#ruc_ci").attr("disabled", "disabled");
                $("#nombre_cliente").attr("disabled", "disabled");
                $("#direccion_cliente").attr("disabled", "disabled");
                $("#telefono_cliente").attr("disabled", "disabled");
                //        $("#correo").attr("disabled", "disabled");
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
                $("#cuotas").children().remove().end();
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
                $("#descxax").val("0.000");
                $("#totx").val("0.000");
                $.getJSON("retornar_nota_venta.php?com=" + valor, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 17) {
                            $("#fecha_actual").val(data[i]);
                            $("#hora_actual").val(data[i + 1]);
                            $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                            $("#id_cliente").val(data[i + 4]);
                            $("#ruc_ci").val(data[i + 5]);
                            $("#nombre_cliente").val(data[i + 6]);
                            $("#direccion_cliente").val(data[i + 7]);
                            $("#telefono_cliente").val(data[i + 8]);
                            $("#correo").val(data[i + 9]);
                            $("#tipo_precio").val(data[i + 10]);
                            if (data[i + 11] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulada"));
                                $("#estado h3").css("color", "red");
                                $("#btnAnular").attr("disabled", "disabled");
                            } else {
                                $("#estado h3").remove();
                                $("#btnAnular").attr("disabled", "disabled");
                                $("#btnAnular").attr("disabled", false);
                            }

                            $("#total_p").val(data[i + 12]);
                            $("#total_p2").val(data[i + 13]);
                            $("#iva").val(data[i + 14]);
                            $("#desc").val(data[i + 15]);
                            $("#tot").val(data[i + 16]);
                            $("#total_px").val(parseFloat(data[i + 12]).toFixed(2));
                            $("#total_p2x").val(parseFloat(data[i + 13]).toFixed(2));
                            $("#ivax").val(parseFloat(data[i + 14]).toFixed(2));
                            $("#descxax").val(parseFloat(data[i + 15]).toFixed(2));
                            $("#totx").val(parseFloat(data[i + 16]).toFixed(2));
                        }
                    }
                });
                // fin

                // llamar facturas no validas
                $.getJSON("retornar_nota_venta2.php?com=" + valor, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 12) {
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
                                pendiente: data[i + 8],
                                cantidad_unidad: data[i + 9],
                                unidad_medida: data[i + 10],
                                detalle_producto: data[i + 11],
                            };
                            var su = jQuery("#list").jqGrid("addRowData", data[i], datarow);
                        }
                    }
                });
                $("#buscar_notas_venta").dialog("close");
                $("#tipo_busqueda").dialog("close");
            } else {
                alertify.alert("Seleccione una Factura");
            }
        },
    });
    // fin

    ////////////tabla series//////////////////////////////
    jQuery("#list3")
            .jqGrid({
                datatype: "local",
                colNames: ["", "cod_serie", "Series"],
                colModel: [
                    {
                        name: "myac",
                        width: 50,
                        fixed: true,
                        sortable: false,
                        resize: false,
                        formatter: "actions",
                        formatoptions: {
                            keys: false,
                            delbutton: true,
                            editbutton: false,
                        },
                    },
                    {
                        name: "id_series",
                        index: "id_series",
                        editable: false,
                        search: false,
                        hidden: true,
                        editrules: {
                            edithidden: false,
                        },
                        align: "center",
                        frozen: true,
                        width: 50,
                    },
                    {
                        name: "serie",
                        index: "serie",
                        editable: false,
                        search: false,
                        hidden: false,
                        editrules: {
                            edithidden: true,
                        },
                        align: "center",
                        frozen: true,
                        width: 100,
                    },
                ],
                rowNum: 30,
                width: 450,
                sortable: true,
                rowList: [10, 20, 30],
                pager: jQuery("#pager3"),
                sortname: "id_series",
                sortorder: "asc",
                viewrecords: true,
                cellEdit: true,
                cellsubmit: "clientArray",
                shrinkToFit: true,
                delOptions: {
                    onclickSubmit: function (rp_ge, rowid) {
                        rp_ge.processing = true;
                        var su = jQuery("#list3").jqGrid("delRowData", rowid);
                        if (su === true) {
                            $("#delmodlist3").hide();
                            $(".ui-icon-closethick").trigger("click");
                        }
                        return true;
                    },
                    processing: true,
                },
            })
            .jqGrid("navGrid", "#pager3", {
                add: false,
                edit: false,
                del: false,
                refresh: true,
                search: true,
                view: true,
            });
    ///////////////////////////////////////////
    //
    //
    //
    //

    $(window).bind("resize", function () {
        jQuery("#list44_reten").setGridWidth($("#pager44_reten").width());
    })
            .trigger("resize");
    jQuery("#list44_reten").jqGrid({
        url: "xmlPlanCuentas_reten.php",
        datatype: "xml",
        colNames: ["Cod. Cuenta", "Descripcion", "Cuenta"],
        colModel: [
            {
                name: "idcontable",
                index: "idcontable",
                editable: true,
                align: "left",
                width: "120",
                search: true,
                frozen: true,
                formoptions: {elmsuffix: " (*)"},
                editrules: {required: true},
            },
            {
                name: "ccontable",
                index: "ccontable",
                editable: true,
                align: "left",
                width: "490",
                search: true,
                frozen: true,
                formoptions: {elmsuffix: " (*)"},
                editrules: {required: true},
            },
            {
                name: "cuenta",
                index: "cuenta",
                editable: true,
                align: "left",
                width: "120",
                search: true,
                frozen: true,
                formoptions: {elmsuffix: " (*)"},
                editrules: {required: true},
            },
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        height: 255,
        pager: jQuery("#pager44_reten"),
        sortname: "codigo_plan",
        shrinkToFit: false,
        sortordezr: "asc",
        caption: "Plan de Cuentas",
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list44_reten").jqGrid("getGridParam", "selrow");
            jQuery("#list44_reten").jqGrid("restoreRow", id);
            var ret = jQuery("#list44_reten").jqGrid("getRowData", id);
            var ccuenta =
                    jQuery("#list44_reten").jqGrid("getCell", id, 0) +
                    "  -  " +
                    jQuery("#list44_reten").jqGrid("getCell", id, 1);
            $("#idCuenta_reten").val(id);
            $("#cuenta_contable_reten").val(ccuenta);
            //            console.log(ccuenta);
            var string = ccuenta;
            var string1 = string.split("-");
            console.log(string1);
            var part1 = string1[1]; // 123
            //            $("#banco").val(part1);
            document.getElementById("cuenta_contable_reten").readOnly = true;
            $("#cuentas_reten").dialog("close");
        },
    })
            .jqGrid(
                    "navGrid",
                    "#pager44_reten",
                    {
                        add: false,
                        edit: false,
                        del: false,
                        refresh: true,
                        search: true,
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
                        bottominfo: "Los campos marcados con (*) son obligatorios",
                        width: 350,
                        checkOnSubmit: false,
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
                    {
                        closeOnEscape: true,
                        width: 400,
                    },
                    {
                        closeOnEscape: true,
                    }
            );
    jQuery("#list44_reten").setGridWidth($("#pager44_reten").width());
    /////////////44/////

    $(window).bind("resize", function () {
        jQuery("#list44").setGridWidth($("#pager44").width());
    }).trigger("resize");
    jQuery("#list44").jqGrid({
        url: "xmlPlanCuentas.php",
        datatype: "xml",
        colNames: ["Cod. Cuenta", "Descripcion", "Cuenta"],
        colModel: [
            {
                name: "idcontable",
                index: "idcontable",
                editable: true,
                align: "left",
                width: "120",
                search: true,
                frozen: true,
                formoptions: {elmsuffix: " (*)"},
                editrules: {required: true},
            },
            {
                name: "ccontable",
                index: "ccontable",
                editable: true,
                align: "left",
                width: "490",
                search: true,
                frozen: true,
                formoptions: {elmsuffix: " (*)"},
                editrules: {required: true},
            },
            {
                name: "cuenta",
                index: "cuenta",
                editable: true,
                align: "left",
                width: "120",
                search: true,
                frozen: true,
                formoptions: {elmsuffix: " (*)"},
                editrules: {required: true},
            },
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        height: 255,
        pager: jQuery("#pager44"),
        sortname: "codigo_plan",
        shrinkToFit: false,
        sortordezr: "asc",
        caption: "Plan de Cuentas",
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list44").jqGrid("getGridParam", "selrow");
            jQuery("#list44").jqGrid("restoreRow", id);
            var ret = jQuery("#list44").jqGrid("getRowData", id);
            var ccuenta =
                    jQuery("#list44").jqGrid("getCell", id, 0) +
                    "  -  " +
                    jQuery("#list44").jqGrid("getCell", id, 1);
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
        },
    })
            .jqGrid(
                    "navGrid",
                    "#pager44",
                    {
                        add: false,
                        edit: false,
                        del: false,
                        refresh: true,
                        search: true,
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
                        bottominfo: "Los campos marcados con (*) son obligatorios",
                        width: 350,
                        checkOnSubmit: false,
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
                    {
                        closeOnEscape: true,
                        width: 400,
                    },
                    {
                        closeOnEscape: true,
                    }
            );
    jQuery("#list44").setGridWidth($("#pager44").width());
    ////////////////////buscador proformas/////////////////////////
    jQuery("#list4")
            .jqGrid({
                url: "xmlBuscarProformas.php",
                datatype: "xml",
                colNames: [
                    "ID",
                    "IDENTFICACIÓN",
                    "CLIENTE",
                    "MONTO TOTAL",
                    "FECHA PROFORMA",
                ],
                colModel: [
                    {
                        name: "id_proforma",
                        index: "id_factura_venta",
                        editable: false,
                        search: false,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "center",
                        frozen: true,
                        width: 50,
                    },
                    {
                        name: "identificacion",
                        index: "identificacion",
                        editable: false,
                        search: false,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "center",
                        frozen: true,
                        width: 80,
                    },
                    {
                        name: "nombres_cli",
                        index: "nombres_cli",
                        editable: true,
                        search: true,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "center",
                        frozen: true,
                        width: 300,
                    },
                    {
                        name: "total_proforma",
                        index: "total_venta",
                        editable: true,
                        search: false,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "center",
                        frozen: true,
                        width: 80,
                    },
                    {
                        name: "fecha_actual",
                        index: "fecha_actual",
                        editable: true,
                        search: false,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "center",
                        frozen: true,
                        width: 80,
                    },
                ],
                rowNum: 30,
                width: 750,
                height: 220,
                sortable: true,
                rowList: [10, 20, 30],
                pager: jQuery("#pager4"),
                sortname: "id_proforma",
                sortorder: "asc",
                viewrecords: true,
                ondblClickRow: function () {
                    var id = jQuery("#list4").jqGrid("getGridParam", "selrow");
                    jQuery("#list4").jqGrid("restoreRow", id);
                    if (id) {
                        var subtotal0 = 0;
                        var subtotal12 = 0;
                        var iva12 = 0;
                        var total_total = 0;
                        var descu_total = 0;
                        var ret = jQuery("#list4").jqGrid("getRowData", id);
                        var valor = ret.id_proforma;
                        // llamado datos personales/////////////
                        $("#proforma").val(valor);
                        $.getJSON(
                                "retornar_proforma_clientes.php?id1=" + valor,
                                function (data) {
                                    var tama2 = data.length;
                                    for (var i = 0; i < tama2; i = i + 8) {
                                        $("#id_cliente").val(data[i]);
                                        $("#ruc_ci").val(data[i + 1]);
                                        $("#nombre_cliente").val(data[i + 2]);
                                        $("#direccion_cliente").val(data[i + 3]);
                                        $("#telefono_cliente").val(data[i + 4]);
                                        $("#correo").val(data[i + 5]);
                                        $("#tipo_precio").val(data[i + 6]);
                                        $("#nombre_director").val(data[i + 7]);
                                    }
                                }
                        );
                        ////////////////////////////////////////////////////

                        $.getJSON("retornar_proforma.php?id2=" + valor, function (data) {
                            var tama = data.length;
                            if (tama === 0) {
                                alertify.alert("Error... La proforma no existe", function () {
                                    location.reload();
                                });
                            } else {
                                $("#list").jqGrid("clearGridData", true);
                                var descuento = 0;
                                var total = 0;
                                var su = 0;
                                var precio = 0;
                                var multi = 0;
                                var flotante = 0;
                                var resultado = 0;
                                for (var i = 0; i < tama; i = i + 11) {
                                    var temp = 0;
                                    var temp1 = 0;
                                    if (data[i + 10] == "Si") {
                                        if (parseInt(data[i + 3]) < 0) {
                                            temp = 0;
                                            temp1 = data[i + 4];
                                        } else {
                                            if (parseInt(data[i + 4]) > parseInt(data[i + 3])) {
                                                temp = data[i + 3];
                                                temp1 = data[i + 4] - data[i + 3];
                                            } else {
                                                temp = data[i + 4];
                                                temp1 = 0;
                                            }
                                        }
                                    } else {
                                        temp = data[i + 4];
                                        temp1 = 0;
                                    }

                                    desc = data[i + 6];
                                    precio = parseFloat(data[i + 5]);
                                    multi = temp * parseFloat(data[i + 5]);
                                    descuento = (multi * parseFloat(data[i + 6])) / 100;
                                    flotante = parseFloat(descuento);
                                    resultado =
                                            Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                    total = multi - resultado;
                                    var datarow = {
                                        cod_producto: data[i],
                                        codigo: data[i + 1],
                                        detalle: data[i + 2],
                                        cantidad: temp,
                                        precio_u: precio,
                                        descuento: desc,
                                        cal_des: resultado,
                                        total: total,
                                        precio_ux: precio.toFixed(2),
                                        descuentox: parseFloat(desc).toFixed(2),
                                        cal_desx: resultado.toFixed(2),
                                        totalx: total.toFixed(2),
                                        iva: data[i + 8],
                                        pendiente: temp1,
                                        incluye: data[i + 9],
                                    };
                                    var su = jQuery("#list").jqGrid("addRowData", data[i], datarow);
                                    var ivas = data[i + 8];
                                }
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
                                    if (dd["iva"] === "Si") {
                                        if (dd["incluye"] == "No") {
                                            subtotal = dd["total"];
                                            sub1 = subtotal;
                                            iva1 = (sub1 * 12) / 100;
                                            subtotal0 = parseFloat(subtotal0) + 0;
                                            subtotal12 = parseFloat(subtotal12) + parseFloat(sub1);
                                            descu_total = parseFloat(descu_total) + dd["cal_des"];
                                            iva12 = parseFloat(iva12) + parseFloat(iva1);
                                            subtotal0 = parseFloat(subtotal0);
                                            subtotal12 = parseFloat(subtotal12);
                                            iva12 = parseFloat(iva12);
                                            descu_total = parseFloat(descu_total);
                                        } else {
                                            if (dd["incluye"] == "Si") {
                                                subtotal = dd["total"];
                                                sub2 = subtotal / (calculoIVA / 100 + 1);
                                                iva2 = sub2 * (calculoIVA / 100);
                                                subtotal0 = parseFloat(subtotal0) + 0;
                                                subtotal12 = parseFloat(subtotal12) + parseFloat(sub2);
                                                iva12 = parseFloat(iva12) + parseFloat(iva2);
                                                descu_total = parseFloat(descu_total) + dd["cal_des"];
                                                subtotal0 = parseFloat(subtotal0);
                                                subtotal12 = parseFloat(subtotal12);
                                                iva12 = parseFloat(iva12);
                                                descu_total = parseFloat(descu_total);
                                            }
                                        }
                                    } else {
                                        if (dd["iva"] === "No") {
                                            subtotal = dd["total"];
                                            sub = subtotal;
                                            subtotal0 = parseFloat(subtotal0) + parseFloat(sub);
                                            subtotal12 = parseFloat(subtotal12) + 0;
                                            iva12 = parseFloat(iva12) + 0;
                                            descu_total = parseFloat(descu_total) + dd["cal_des"];
                                            subtotal0 = parseFloat(subtotal0);
                                            subtotal12 = parseFloat(subtotal12);
                                            iva12 = parseFloat(iva12);
                                            descu_total = parseFloat(descu_total);
                                        }
                                    }
                                }
                                total_total =
                                        parseFloat(total_total) +
                                        (parseFloat(subtotal0) +
                                                parseFloat(subtotal12) +
                                                parseFloat(iva12));
                                total_total = parseFloat(total_total);
                                $("#total_p").val(subtotal0);
                                $("#total_p2").val(subtotal12);
                                $("#iva").val(iva12);
                                $("#desc").val(descu_total);
                                $("#tot").val(total_total);
                                $("#total_px").val(subtotal0.toFixed(2));
                                $("#total_p2x").val(subtotal12.toFixed(2));
                                $("#ivax").val(iva12.toFixed(2));
                                $("#descxax").val(descu_total.toFixed(2));
                                $("#totx").val(total_total.toFixed(2));
                                //                        $("#codigo_barras").focus();

                            }
                        });
                        $("#buscar_proformas").dialog("close");
                    } else {
                        alertify.alert("Seleccione una Factura");
                    }
                },
            })
            .jqGrid(
                    "navGrid",
                    "#pager4",
                    {
                        add: false,
                        edit: false,
                        del: false,
                        refresh: true,
                        search: true,
                        view: true,
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
    jQuery("#list4").jqGrid("navButtonAdd", "#pager4", {
        caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#list2").jqGrid("getGridParam", "selrow");
            jQuery("#list4").jqGrid("restoreRow", id);
            if (id) {
                var ret = jQuery("#list2").jqGrid("getRowData", id);
                var valor = ret.id_factura_venta;
                /////////////agregregar datos factura////////
                //                $("#comprobante").val(valor);
                $("#btnGuardar").attr("disabled", true);
                // $("#num_factura").attr("disabled", "disabled");
                $("#ruc_ci").attr("disabled", "disabled");
                $("#nombre_cliente").attr("disabled", "disabled");
                $("#direccion_cliente").attr("disabled", "disabled");
                $("#telefono_cliente").attr("disabled", "disabled");
                $("#correo").attr("disabled", "disabled");
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
                $("#cuotas").children().remove().end();
                $("#cuotas").attr("disabled", true);
                $("#list").jqGrid("clearGridData", true);
                $("#total_p").val("0.000");
                $("#total_p2").val("0.000");
                $("#iva").val("0.000");
                $("#desc").val("0.000");
                $("#desc").val("0.000");
                $("#tot").val("0.000");
                $("#total_px").val("0.000");
                $("#total_p2x").val("0.000");
                $("#ivax").val("0.000");
                $("#descxax").val("0.000");
                $("#totx").val("0.000");
                $.getJSON(
                        "../procesos/retornar_factura_venta.php?com=" + valor,
                        function (data) {
                            var tama = data.length;
                            if (tama !== 0) {
                                for (var i = 0; i < tama; i = i + 19) {
                                    $("#fecha_actual").val(data[i]);
                                    $("#hora_actual").val(data[i + 1]);
                                    $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                                    var num = data[i + 4];
                                    var res = num.substr(8, 20);
                                    $("#num_factura").val(res);

                                    $("#id_cliente").val(data[i + 5]);
                                    $("#ruc_ci").val(data[i + 6]);
                                    $("#nombre_cliente").val(data[i + 7]);
                                    $("#direccion_cliente").val(data[i + 8]);
                                    $("#telefono_cliente").val(data[i + 9]);
                                    $("#correo").val(data[i + 10]);
                                    $("#cancelacion").val(data[i + 11]);
                                    $("#tipo_precio").val(data[i + 12]);
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
                                    $("#descxax").val(parseFloat(data[i + 17]).toFixed(2));
                                    $("#totx").val(parseFloat(data[i + 18]).toFixed(2));
                                }
                            }
                        }
                );
                ///////////////////////////////////////////////////

                ///////////////////llamar facturas flechas segunda parte/////
                $.getJSON(
                        "../procesos/retornar_factura_venta_credito.php?com=" + valor,
                        function (data) {
                            var tama = data.length;
                            if (tama !== 0) {
                                for (var i = 0; i < tama; i = i + 4) {
                                    $("#formaspago").val(data[i]);
                                    $("#adelanto").val(data[i + 1]);
                                    $("#meses").val(data[i + 2]);
                                    //////////calcular meses//////////
                                    if (data[i + 2] > 1) {
                                        $("#cuotas").attr("disabled", false);
                                        for (var j = 1; j <= data[i + 2] - 1; j++) {
                                            var calcu = data[i + 3] / data[i + 2];
                                            var entero = Math.floor(calcu).toFixed(2);
                                            $("#cuotas").append("<option>" + entero + "</option>");
                                        }
                                        var calcu1 = entero * (data[i + 2] - 1);
                                        var sal = data[i + 3] - calcu1;
                                        var entero2 = sal.toFixed(2);
                                        $("#cuotas").append("<option>" + entero2 + "</option>");
                                    } else {
                                        $("#cuotas").attr("disabled", false);
                                        $("#cuotas").append("<option>" + data[i + 3] + "</option>");
                                    }
                                }
                            }
                        }
                );
                /////////////////////////////////////////////////////////

                ////////////////////llamar facturas flechas tercera parte/////
                $.getJSON(
                        "../procesos/retornar_factura_venta2.php?com=" + valor,
                        function (data) {
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
                                        pendiente: data[i + 8],
                                    };
                                    var su = jQuery("#list").jqGrid("addRowData", data[i], datarow);
                                }
                            }
                        }
                );
                $("#buscar_facturas_venta").dialog("close");
            } else {
                alertify.alert("Seleccione una Factura");
            }
        },
    });
    jQuery("#listp")
            .jqGrid({
                url: "datos_productos.php",
                datatype: "xml",
                colNames: [
                    "ID",
                    "CÓDIGO",
                    "CÓDIGO BARRAS",
                    "ARTICULO",
                    "IVA",
                    "SERIES",
                    "P. COMPRA",
                    "UTI. MINORISTA",
                    "P. MINORISTA",
                    "UTILIDAD MAYORISTA",
                    "P. MAYORISTA",
                    "FAMILIAS",
                    "LABORA.",
                    "DESCUENTO",
                    "STOCK",
                    "MÌNIMO",
                    "MÀXIMO",
                    "FECHA COMPRA",
                    "NOM. GENÈ.",
                    "APLICACION",
                    "ESTADO",
                    "INVENTARIABLE",
                    "IMAGEN",
                    "",
                    "BODEGA",
                    "INCLUYE IVA",
                    "PRECIO NEGOCIO",
                    "ID PLAN CUENTAS",
                    "CUENTA CONTABLE",
                    "PROVEEDOR",
                    "CANTIDAD DESCUENTO",
                ],
                colModel: [
                    {
                        name: "cod_productos",
                        index: "cod_productos",
                        editable: true,
                        hidden: true,
                        align: "center",
                        width: "25",
                        search: false,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "cod_prod",
                        index: "cod_prod",
                        editable: true,
                        align: "center",
                        width: "70",
                        search: false,
                        frozen: true,
                        formoptions: {elmsuffix: " (*)"},
                        editrules: {required: true},
                    },
                    {
                        name: "cod_barras",
                        index: "cod_barras",
                        editable: true,
                        hidden: true,
                        align: "center",
                        width: "100",
                        search: true,
                        frozen: true,
                        formoptions: {elmsuffix: " (*)"},
                        editrules: {required: true},
                    },
                    {
                        name: "nombre_art",
                        index: "nombre_art",
                        editable: true,
                        align: "center",
                        width: "150",
                        search: true,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "iva",
                        index: "iva",
                        editable: true,
                        align: "center",
                        width: "30",
                        search: false,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "series",
                        index: "series",
                        editable: true,
                        hidden: true,
                        align: "center",
                        width: "50",
                        search: false,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "precio_compra",
                        index: "precio_compra",
                        hidden: true,
                        editable: true,
                        align: "center",
                        width: "70",
                        search: false,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "utilidad_minorista",
                        index: "utilidad_minorista",
                        editable: true,
                        hidden: true,
                        align: "center",
                        width: "100",
                        search: false,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "precio_minorista",
                        index: "precio_minorista",
                        editable: true,
                        align: "center",
                        width: "70",
                        search: false,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "utilidad_mayorista",
                        index: "utilidad_mayorista",
                        editable: true,
                        hidden: true,
                        align: "center",
                        width: "100",
                        search: false,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "precio_mayorista",
                        index: "precio_mayorista",
                        hidden: true,
                        editable: true,
                        align: "center",
                        width: "60",
                        search: false,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "categoria",
                        index: "categoria",
                        editable: true,
                        align: "center",
                        width: "80",
                        search: false,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "marca",
                        index: "marca",
                        editable: true,
                        align: "center",
                        width: "80",
                        search: true,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "descuento",
                        index: "descuento",
                        editable: true,
                        hidden: true,
                        align: "center",
                        width: "120",
                        search: false,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "stock",
                        index: "stock",
                        editable: true,
                        align: "center",
                        width: "45",
                        search: false,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "minimo",
                        index: "minimo",
                        editable: true,
                        hidden: true,
                        align: "center",
                        width: "120",
                        search: false,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "maximo",
                        index: "maximo",
                        editable: true,
                        hidden: true,
                        align: "center",
                        width: "120",
                        search: false,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "fecha_creacion",
                        index: "fecha_creacion",
                        hidden: true,
                        editable: true,
                        align: "center",
                        width: "120",
                        search: false,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "modelo",
                        index: "modelo",
                        editable: true,
                        align: "center",
                        width: "90",
                        search: true,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "aplicacion",
                        index: "aplicacion",
                        editable: true,
                        align: "center",
                        width: "95",
                        search: false,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "vendible",
                        index: "vendible",
                        editable: true,
                        hidden: true,
                        align: "center",
                        hidden: true,
                        width: "120",
                        search: false,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "inventario",
                        index: "inventario",
                        editable: true,
                        hidden: true,
                        align: "center",
                        hidden: true,
                        width: "120",
                        search: false,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "imagen",
                        index: "imagen",
                        editable: true,
                        align: "center",
                        hidden: true,
                        width: "120",
                        search: false,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "bodegas",
                        index: "bodegas",
                        hidden: true,
                        editable: true,
                        align: "center",
                        width: "120",
                        search: false,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "descripcion",
                        index: "descripcion",
                        hidden: false,
                        hidden: true,
                        editable: true,
                        align: "center",
                        width: "120",
                        search: false,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "incluye",
                        index: "incluye",
                        hidden: false,
                        editable: true,
                        hidden: true,
                        align: "center",
                        width: "120",
                        search: false,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "precio_negocio",
                        index: "precio_negocio",
                        hidden: true,
                        editable: true,
                        align: "center",
                        width: "120",
                        search: false,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "idcontable",
                        index: "incluye",
                        hidden: true,
                        editable: true,
                        hidden: true,
                        align: "center",
                        width: "120",
                        search: false,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "ccontable",
                        index: "incluye",
                        hidden: false,
                        editable: true,
                        hidden: true,
                        align: "center",
                        width: "120",
                        search: false,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "proveedor",
                        index: "incluye",
                        editable: true,
                        align: "center",
                        width: "120",
                        search: false,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                    {
                        name: "cantidad_descuento",
                        index: "incluye",
                        editable: true,
                        hidden: true,
                        align: "center",
                        width: "120",
                        search: false,
                        frozen: true,
                        editoptions: {readonly: "readonly"},
                        formoptions: {elmprefix: ""},
                    },
                ],
                rowNum: 10,
                width: 860,
                height: 200,
                rowList: [10, 20, 30],
                pager: jQuery("#pagerp"),
                sortname: "cod_productos",
                shrinkToFit: false,
                sortorder: "asc",
                caption: "Lista de Productos",
                viewrecords: true,
                ondblClickRow: function () {
                    var id = jQuery("#listp").jqGrid("getGridParam", "selrow");
                    jQuery("#listp").jqGrid("restoreRow", id);
                    var ret = jQuery("#listp").jqGrid("getRowData", id);
                    if (id) {
                        var valor = ret.cod_productos;
                        var tipo_precio = $("#tipo_precio").val();
                        $.getJSON(
                                "retornar_productos.php?com=" + valor,
                                "tipo_precio=" + tipo_precio,
                                function (data) {
                                    var tama = data.length;
                                    t = data[11];
                                    if (tama !== 0) {
                                        jQuery("#listp").jqGrid("GridToForm", id, "#productos_form");
                                        for (var i = 0; i < tama; i = i + 12) {
                                            $("#cod_producto").val(data[i]);
                                            $("#codigo_barras").val(data[i + 1]);
                                            $("#p_venta").val(data[i + 2]);
                                            //$("#descuento").val(5);
                                            $("#disponibles").val(data[i + 4]);
                                            $("#iva_producto").val(data[i + 5]);
                                            $("#carga_series").val(data[i + 6]);
                                            $("#codigo").val(data[i + 7]);
                                            $("#des").val(data[i + 8]);
                                            $("#inventar").val(data[i + 9]);
                                            $("#incluye").val(data[i + 10]);
                                            $("#producto").val(data[i + 11]);
                                            $("#cantidad").focus();
                                        }
                                    }
                                }
                        );
                        $("#productos").dialog("close");
                    } else {
                        alertify.alert("Seleccione una Factura");
                    }

                    if (ret.vendible == "Pasivo") {
                        $("#btnEliminar").attr("disabled", "disabled");
                        $("#btnModificar").attr("disabled", "disabled");
                        $("#btnActivar").attr("disabled", false);
                    } else {
                        $("#btnActivar").attr("disabled", "disabled");
                        $("#btnModificar").attr("disabled", false);
                        $("#btnEliminar").attr("disabled", false);
                    }
                },
            })
            .jqGrid(
                    "navGrid",
                    "#pagerp",
                    {
                        add: false,
                        edit: false,
                        del: false,
                        refresh: true,
                        search: true,
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
            )
            .jqGrid("bindKeys", {
                onEnter: function () {
                    var id = jQuery("#listp").jqGrid("getGridParam", "selrow");
                    jQuery("#listp").jqGrid("restoreRow", id);
                    var ret = jQuery("#listp").jqGrid("getRowData", id);
                    if (id) {
                        var valor = ret.cod_productos;
                        var tipo_precio = $("#tipo_precio").val();
                        $.getJSON(
                                "retornar_productos.php?com=" + valor,
                                "tipo_precio=" + tipo_precio,
                                function (data) {
                                    var tama = data.length;
                                    t = data[11];
                                    if (tama !== 0) {
                                        jQuery("#listp").jqGrid("GridToForm", id, "#productos_form");
                                        for (var i = 0; i < tama; i = i + 12) {
                                            $("#cod_producto").val(data[i]);
                                            $("#codigo_barras").val(data[i + 1]);
                                            $("#p_venta").val(data[i + 2]);
                                            //$("#descuento").val(5);
                                            $("#disponibles").val(data[i + 4]);
                                            $("#iva_producto").val(data[i + 5]);
                                            $("#carga_series").val(data[i + 6]);
                                            $("#codigo").val(data[i + 7]);
                                            $("#des").val(data[i + 8]);
                                            $("#inventar").val(data[i + 9]);
                                            $("#incluye").val(data[i + 10]);
                                            $("#producto").val(data[i + 11]);
                                            $("#cantidad").focus();
                                        }
                                    }
                                }
                        );
                        $("#productos").dialog("close");
                    } else {
                        alertify.alert("Seleccione una Factura");
                    }

                    if (ret.vendible == "Pasivo") {
                        $("#btnEliminar").attr("disabled", "disabled");
                        $("#btnModificar").attr("disabled", "disabled");
                        $("#btnActivar").attr("disabled", false);
                    } else {
                        $("#btnActivar").attr("disabled", "disabled");
                        $("#btnModificar").attr("disabled", false);
                        $("#btnEliminar").attr("disabled", false);
                    }
                },
            });
    ////////////////////buscador proformas tecnico/////////////////////////
    jQuery("#listPagoreten")
            .jqGrid({
                datatype: "local",
                colNames: [
                    "",
                    "Base Imponible",
                    "Impuesto",
                    "% Retenciòn ",
                    "Valor Retenido ",
                    "Id_retenciones ",
                    "tipo_ret",
                    "codigo_imp",
                    "Código Retención",
                ],
                colModel: [
                    {
                        name: "myac",
                        width: 50,
                        fixed: true,
                        sortable: false,
                        resize: false,
                        formatter: "actions",
                        formatoptions: {
                            keys: false,
                            delbutton: true,
                            editbutton: false,
                        },
                    },
                    {
                        name: "base_imponible",
                        index: "base_imponible",
                        editable: true,
                        align: "center",
                        width: "180",
                        search: false,
                        frozen: true,
                        editoptions: {
                            readonly: "readonly",
                        },
                        formoptions: {
                            elmprefix: "",
                        },
                    },
                    {
                        name: "impuesto",
                        index: "impuesto",
                        editable: true,
                        align: "center",
                        width: "180",
                        search: false,
                        frozen: true,
                        editoptions: {
                            readonly: "readonly",
                        },
                        formoptions: {
                            elmprefix: "",
                        },
                    },
                    {
                        name: "porcent_reten",
                        index: "porcent_reten",
                        editable: true,
                        align: "center",
                        width: "180",
                        search: true,
                        frozen: true,
                        formoptions: {
                            elmsuffix: " (*)",
                        },
                        editrules: {
                            required: true,
                        },
                    },
                    {
                        name: "valor_retenido",
                        index: "valor_retenido",
                        editable: true,
                        align: "center",
                        width: "180",
                        search: true,
                        frozen: true,
                        formoptions: {
                            elmsuffix: " (*)",
                        },
                        editrules: {
                            required: true,
                        },
                    },
                    {
                        name: "id_retenciones_ser",
                        index: "id_retenciones_ser",
                        hidden: true,
                        editable: true,
                        align: "center",
                        width: "180",
                        search: true,
                        frozen: true,
                        formoptions: {
                            elmsuffix: " (*)",
                        },
                        editrules: {
                            required: true,
                        },
                    },
                    {
                        name: "tipo_ret",
                        index: "tipo_ret",
                        hidden: true,
                        align: "center",
                        width: "180",
                        frozen: true,
                    },
                    {
                        name: "codigo_imp",
                        index: "codigo_imp",
                        hidden: true,
                    },
                    {
                        name: "codigo_ret",
                        index: "codigo_ret",
                        align: "center",
                    },
                ],
                rowNum: 10,
                rowList: [10, 20, 30],
                height: 100,
                pager: jQuery("#pagerP_reten"),
                sortname: "base_imponible",
                shrinkToFit: true,
                sortorder: "asc",
                caption: "Datos",
                viewrecords: true,
                delOptions: {
                    onclickSubmit: function (rp_ge, rowid) {
                        rp_ge.processing = true;
                        var su = jQuery("#listPagoreten").jqGrid("delRowData", rowid);
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
                            subtotal = subtotal + parseFloat(dd["valor_retenido"]);
                        }

                        $("#total_retencion").val(subtotal.toFixed(2));
                    },
                    processing: true,
                },
            })
            .jqGrid("navGrid", "#pagerP_reten", {
                add: false,
                edit: false,
                del: false,
                refresh: true,
                search: true,
                view: true,
            });
    ////////////////////buscador proformas tecnico/////////////////////////
    jQuery("#list7")
            .jqGrid({
                url: "xmlBuscarEstados.php",
                datatype: "xml",
                colNames: [
                    "ID",
                    "NUM. FACTURA",
                    "N° AUTORIZACIÓN",
                    "FECHA EMISIÓN",
                    "RAZÒN SOCIAL",
                    "CORREO ",
                    "FECHA AUTORIZACIÓN",
                    "TOTAL",
                    "ESTADO",
                    "ENVIO CORREO",
                    "ENVIO XML",
                    "CONSULTA COMPROBANTE",
                ],
                colModel: [
                    {
                        name: "id_factura_venta",
                        index: "id_factura_venta",
                        editable: false,
                        search: false,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "center",
                        frozen: true,
                        width: 80,
                    },
                    {
                        name: "num_factura",
                        index: "num_factura",
                        editable: false,
                        search: false,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "center",
                        frozen: true,
                        width: 100,
                    },
                    {
                        name: "autorizacion",
                        index: "autorizacion",
                        editable: false,
                        search: false,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "center",
                        frozen: true,
                        width: 100,
                    },
                    {
                        name: "fecha_emision",
                        index: "fecha_emision",
                        editable: true,
                        search: true,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "center",
                        frozen: true,
                        width: 100,
                    },
                    {
                        name: "razon_social",
                        index: "razon_social",
                        editable: true,
                        search: false,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "center",
                        frozen: true,
                        width: 300,
                    },
                    {
                        name: "correo",
                        index: "correo",
                        editable: true,
                        search: false,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "center",
                        frozen: true,
                        width: 100,
                    },
                    {
                        name: "fecha_autorizacion",
                        index: "fecha_autorizacion",
                        editable: true,
                        search: false,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "center",
                        frozen: true,
                        width: 100,
                    },
                    {
                        name: "total",
                        index: "total",
                        editable: true,
                        search: false,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "center",
                        frozen: true,
                        width: 50,
                    },
                    {
                        name: "estado",
                        index: "estado",
                        editable: true,
                        search: false,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "center",
                        frozen: true,
                        width: 100,
                    },
                    {
                        name: "accion",
                        index: "accion",
                        editable: false,
                        hidden: false,
                        search: false,
                        frozen: true,
                        editrules: {
                            required: true,
                        },
                        align: "center",
                        width: 100,
                    },
                    {
                        name: "envio",
                        index: "envio",
                        editable: false,
                        hidden: false,
                        search: false,
                        frozen: true,
                        editrules: {
                            required: true,
                        },
                        align: "center",
                        width: 100,
                    },
                    {
                        name: "reenvio",
                        index: "reenvio",
                        editable: false,
                        hidden: false,
                        search: false,
                        frozen: true,
                        editrules: {
                            required: true,
                        },
                        align: "center",
                        width: 100,
                    },
                ],
                rowNum: 30,
                width: 1250,
                //shrinkToFit: true,
                height: 220,
                sortable: true,
                rowList: [10, 20, 30],
                pager: jQuery("#pager7"),
                sortname: "id_factura_venta",
                sortorder: "desc",
                viewrecords: true,
                gridComplete: function () {
                    var ids = jQuery("#list7").jqGrid("getDataIDs");
                    for (var i = 0; i < ids.length; i++) {
                        var ids = jQuery("#list7").getDataIDs();
                        for (var i = 0; i < ids.length; i++) {
                            var id_factura = ids[i];
                            var datosr = jQuery('#list7').getRowData(id_factura);

                            if (datosr.estado == "NO AUTORIZADO") {
                                be =
                                        "<i class='fa fa-envelope-o' style='cursor:not-allowed;' title='Para enviar el correo primero debe autorizar la factura'> CORREO</i>";

                                jQuery("#list7").jqGrid("setRowData", ids[i], {
                                    accion: be,
                                });
                            } else {
                                be =
                                        "<a  onclick=\"reenviar('" +
                                        id_factura +
                                        "')\" title='Reenviar Correo' ><i class='fa fa-envelope-o' style='cursor:pointer; cursor: hand'> CORREO</i></a>";
                                jQuery("#list7").jqGrid("setRowData", ids[i], {
                                    accion: be,
                                });
                            }

                        }
                    }

                    for (var i = 0; i < ids.length; i++) {
                        var ids = jQuery("#list7").getDataIDs();
                        for (var i = 0; i < ids.length; i++) {
                            var id_factura = ids[i];
                            be =
                                    "<a  onclick=\"enviarXml('" +
                                    id_factura +
                                    "')\" title='Enviar Xml' ><i class='fa fa-arrow-circle-right' style='cursor:pointer; cursor: hand'> Envio Xml</i></a>";
                            jQuery("#list7").jqGrid("setRowData", ids[i], {
                                envio: be,
                            });
                        }
                    }
                    for (var i = 0; i < ids.length; i++) {
                        var ids = jQuery("#list7").getDataIDs();
                        for (var i = 0; i < ids.length; i++) {
                            var id_factura = ids[i];
                            be =
                                    "<a  onclick=\"reenviarXml('" +
                                    id_factura +
                                    "')\" title='Reenviar Xml' ><i class='fa fa-repeat' style='cursor:pointer; cursor: hand'> Consulta Xml</i></a>";
                            jQuery("#list7").jqGrid("setRowData", ids[i], {
                                reenvio: be,
                            });
                        }
                    }
                },
                ondblClickRow: function () {
                    var id = jQuery("#list7").jqGrid("getGridParam", "selrow");
                    jQuery("#list7").jqGrid("restoreRow", id);
                    var valor = null;
                    if (id) {
                        var ret = jQuery("#list7").jqGrid("getRowData", id);
                        valor = ret.id_factura_venta;
                    }
                    $("#clavefactura").val("");
                    limpiarCamposRetencion();
                    console.log("hola si entroo//");
                    cargarFacturaDblclick(valor);
                },

            })
            .jqGrid(
                    "navGrid",
                    "#pager7",
                    {
                        add: false,
                        edit: false,
                        del: false,
                        refresh: true,
                        search: true,
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
     jQuery("#list7").jqGrid("navButtonAdd", "#pager7", {
        caption: "GENERAL",
        onClickButton: function () {
            $("#list7").setGridParam({
                url: 'xmlBuscarEstados.php?estado_fac=general',
                page: 1
            }).trigger("reloadGrid");
            $("#buscar_estados").dialog("open");
        },
    });
 
    jQuery("#list7").jqGrid("navButtonAdd", "#pager7", {
        caption: "NO AUTORIZADAS",
        onClickButton: function () {
            $("#list7").setGridParam({
                url: 'xmlBuscarEstados.php?estado_fac=autorizado',
                page: 1
            }).trigger("reloadGrid");
            $("#buscar_estados").dialog("open");
        },
    });
       jQuery("#list7").jqGrid("navButtonAdd", "#pager7", {
        caption: "NO ENVIADAS AL CORREO:",
        onClickButton: function () {
            $("#list7").setGridParam({
                url: 'xmlBuscarEstados.php?estado_fac=no_enviado_correo',
                page: 1
            }).trigger("reloadGrid");
            $("#buscar_estados").dialog("open");
        },
    });
    seleccion_row();
    jQuery("#list6")
            .jqGrid({
                url: "xmlBuscarProformasTecnico.php",
                datatype: "xml",
                colNames: [
                    "N°. REG.",
                    "ID_PROFORMA",
                    "IDENTFICACIÓN",
                    "CLIENTE",
                    "MONTO TOTAL",
                    "FECHA REGISTRO",
                ],
                colModel: [
                    {
                        name: "id_registro",
                        index: "r.id_registro",
                        search: true,
                        width: 50,
                        align: "center",
                    },
                    {
                        name: "id_proforma",
                        index: "pt.id_proforma",
                        editable: false,
                        search: false,
                        hidden: true,
                        editrules: {
                            edithidden: false,
                        },
                        align: "center",
                        frozen: true,
                        width: 50,
                    },
                    {
                        name: "identificacion",
                        index: "c.identificacion",
                        editable: false,
                        search: true,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "center",
                        frozen: true,
                        width: 80,
                        searchoptions: {sopt: ["eq"]},
                    },
                    {
                        name: "nombres_cli",
                        index: "c.nombres_cli",
                        editable: true,
                        search: true,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "center",
                        frozen: true,
                        width: 200,
                        searchoptions: {sopt: ["cn"]},
                    },
                    {
                        name: "total_proforma",
                        index: "pt.total_proforma",
                        editable: true,
                        search: false,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "center",
                        frozen: true,
                        width: 80,
                    },
                    {
                        name: "fecha_ingreso",
                        index: "r.fecha_ingreso",
                        editable: true,
                        search: false,
                        hidden: false,
                        editrules: {
                            edithidden: false,
                        },
                        align: "center",
                        frozen: true,
                        width: 100,
                    },
                ],
                rowNum: 30,
                width: 750,
                height: 220,
                sortable: true,
                rowList: [10, 20, 30],
                pager: jQuery("#pager6"),
                sortname: "id_proforma",
                sortorder: "asc",
                viewrecords: true,
                ondblClickRow: function () {
                    var id = jQuery("#list6").jqGrid("getGridParam", "selrow");
                    jQuery("#list6").jqGrid("restoreRow", id);
                    if (id) {
                        //guardar id de proforma en idProformaTecnico
                        var ret = jQuery("#list6").jqGrid("getRowData", id);
                        var valor = ret.id_proforma;
                        idProformaTecnico = valor;
                        // llamado datos personales/////////////
                        //$("#proforma").val(valor);
                        $.getJSON(
                                "retornar_proforma_clientes_tecnico.php?id1=" + valor,
                                function (data) {
                                    var tama2 = data.length;
                                    for (var i = 0; i < tama2; i = i + 8) {
                                        $("#id_cliente").val(data[i]);
                                        $("#ruc_ci").val(data[i + 1]);
                                        $("#nombre_cliente").val(data[i + 2]);
                                        $("#direccion_cliente").val(data[i + 3]);
                                        $("#telefono_cliente").val(data[i + 4]);
                                        $("#correo").val(data[i + 5]);
                                        $("#tipo_precio").val(data[i + 6]);
                                        $("#nombre_director").val(data[i + 7]);
                                    }
                                }
                        );
                        ////////////////////////////////////////////////////
                        $.getJSON(
                                "retornar_proforma_tecnico.php?id2=" + valor,
                                function (data) {
                                    var tama = data.length;
                                    if (tama === 0) {
                                        alertify.alert("Error... La proforma no existe", function () {
                                            location.reload();
                                        });
                                    } else {
                                        $("#list").jqGrid("clearGridData", true);
                                        for (var i = 0; i < tama; i = i + 11) {
                                            var temp = 0;
                                            var temp1 = 0;
                                            if (data[i + 10] == "Si") {
                                                if (parseInt(data[i + 3]) < 0) {
                                                    temp = 0;
                                                    temp1 = data[i + 4];
                                                } else {
                                                    if (parseInt(data[i + 4]) > parseInt(data[i + 3])) {
                                                        temp = data[i + 3];
                                                        temp1 = data[i + 4] - data[i + 3];
                                                    } else {
                                                        temp = data[i + 4];
                                                        temp1 = 0;
                                                    }
                                                }
                                            } else {
                                                temp = data[i + 4];
                                                temp1 = 0;
                                            }

                                            var datarow = {
                                                cod_producto: parseFloat(data[i]),
                                                codigo: data[i + 1],
                                                detalle: data[i + 2],
                                                cantidad: parseFloat(data[i + 4]),
                                                precio_u: parseFloat(data[i + 5]),
                                                descuento: parseFloat(data[i + 6]),
                                                cal_des: Math.round(parseFloat(data[i + 6]) * Math.pow(10, 2)) / Math.pow(10, 2),
                                                total: parseFloat(data[i + 7]),
                                                precio_ux: parseFloat(data[i + 5]).toFixed(4),
                                                descuentox: parseFloat(data[i + 6]).toFixed(4),
                                                cal_desx: parseFloat(data[i + 6]).toFixed(4),
                                                totalx: parseFloat(data[i + 7]).toFixed(4),
                                                iva: data[i + 8],
                                                pendiente: temp1,
                                                incluye: data[i + 9],
                                            };
                                            var su = jQuery("#list").jqGrid("addRowData", data[i], datarow);
                                        }
                                        calcularTotalTabla();
                                    }
                                }
                        );
                        $("#buscar_proformas_tecnico").dialog("close");
                    } else {
                        alertify.alert("Seleccione una Factura");
                    }
                },
            })
            .jqGrid(
                    "navGrid",
                    "#pager6",
                    {
                        add: false,
                        edit: false,
                        del: false,
                        refresh: true,
                        search: true,
                        view: true,
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
    jQuery("#list6").jqGrid("navButtonAdd", "#pager6", {
        caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#list2").jqGrid("getGridParam", "selrow");
            jQuery("#list6").jqGrid("restoreRow", id);
            if (id) {
                var ret = jQuery("#list2").jqGrid("getRowData", id);
                var valor = ret.id_factura_venta;
                /////////////agregregar datos factura////////
                //                $("#comprobante").val(valor);
                $("#btnGuardar").attr("disabled", true);
                // $("#num_factura").attr("disabled", "disabled");
                $("#ruc_ci").attr("disabled", "disabled");
                $("#nombre_cliente").attr("disabled", "disabled");
                $("#direccion_cliente").attr("disabled", "disabled");
                $("#telefono_cliente").attr("disabled", "disabled");
                $("#correo").attr("disabled", "disabled");
                $("#formas").attr("disabled", true);
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
                $("#formas").val("Contado");
                $("#adelanto").val("");
                $("#meses").val("");
                $("#cuotas").children().remove().end();
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
                $("#descxax").val("0.000");
                $("#totx").val("0.000");
                $.getJSON(
                        "../procesos/retornar_factura_venta.php?com=" + valor,
                        function (data) {
                            var tama = data.length;
                            if (tama !== 0) {
                                for (var i = 0; i < tama; i = i + 19) {
                                    $("#fecha_actual").val(data[i]);
                                    $("#hora_actual").val(data[i + 1]);
                                    $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                                    var num = data[i + 4];
                                    var res = num.substr(8, 20);
                                    $("#num_factura").val(res);

                                    $("#id_cliente").val(data[i + 5]);
                                    $("#ruc_ci").val(data[i + 6]);
                                    $("#nombre_cliente").val(data[i + 7]);
                                    $("#direccion_cliente").val(data[i + 8]);
                                    $("#telefono_cliente").val(data[i + 9]);
                                    $("#correo").val(data[i + 10]);
                                    $("#cancelacion").val(data[i + 11]);
                                    $("#tipo_precio").val(data[i + 12]);
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
                                    $("#descxax").val(parseFloat(data[i + 17]).toFixed(2));
                                    $("#totx").val(parseFloat(data[i + 18]).toFixed(2));
                                }
                            }
                        }
                );
                ///////////////////////////////////////////////////

                ///////////////////llamar facturas flechas segunda parte/////
                $.getJSON(
                        "../procesos/retornar_factura_venta_credito.php?com=" + valor,
                        function (data) {
                            var tama = data.length;
                            if (tama !== 0) {
                                for (var i = 0; i < tama; i = i + 4) {
                                    $("#formas").val(data[i]);
                                    $("#adelanto").val(data[i + 1]);
                                    $("#meses").val(data[i + 2]);
                                    //////////calcular meses//////////
                                    if (data[i + 2] > 1) {
                                        $("#cuotas").attr("disabled", false);
                                        for (var j = 1; j <= data[i + 2] - 1; j++) {
                                            var calcu = data[i + 3] / data[i + 2];
                                            var entero = Math.floor(calcu).toFixed(2);
                                            $("#cuotas").append("<option>" + entero + "</option>");
                                        }
                                        var calcu1 = entero * (data[i + 2] - 1);
                                        var sal = data[i + 3] - calcu1;
                                        var entero2 = sal.toFixed(2);
                                        $("#cuotas").append("<option>" + entero2 + "</option>");
                                    } else {
                                        $("#cuotas").attr("disabled", false);
                                        $("#cuotas").append("<option>" + data[i + 3] + "</option>");
                                    }
                                }
                            }
                        }
                );
                /////////////////////////////////////////////////////////

                ////////////////////llamar facturas flechas tercera parte/////
                $.getJSON(
                        "../procesos/retornar_factura_venta2.php?com=" + valor,
                        function (data) {
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
                                        pendiente: data[i + 8],
                                    };
                                    var su = jQuery("#list").jqGrid("addRowData", data[i], datarow);
                                }
                            }
                        }
                );
                $("#buscar_facturas_venta").dialog("close");
            } else {
                alertify.alert("Seleccione una Factura");
            }
        },
    });
    jQuery(window)
            .bind("resize", function () {
                jQuery("#list").setGridWidth(jQuery("#grid_container").width(), true);
            })
            .trigger("resize");
    //    $("#codigo_barras").focus();

    inputmaskDecimal("#cantidad", true, 2);
    inputmaskDecimal("#p_venta", true, 4);
    inputmaskDecimal("#venta_iva_1", true, 4);
    inputmaskDecimal("#stock", true, 2);
    modalBuscarEstados();
    modalBuscarEstadosGuia();
    crearlLista77();
    $("#btnGuiaRemision").click(function (e) {
        e.preventDefault();
        guardar_guia_remision();
    });
    $("#retencionF1Sguia").on("change", cambio_ret_fuenteSguia);
    $("#retencionF2Sguia").on("change", cambio_ret_fuenteSguia);
    $("#transportistaguia").hide();
    $("#num_serie_guia").hide();
    $("#btnActualizartrans").hide();
    $("#trans").hide();
    $("#translabel").hide();
    $("#num_labelg").hide();
    $("#formaspago").change(function (e) {
        porcentaje();
    });
    $("#btnImprimirGuia").click(function () {
        var myWindow = window.open("generarPDFGuia_1.php?hoja=A4&id=" + $("#comprobante").val(), "_blank");
        myWindow.focus();
        myWindow.print();
    });
    $("#btnEstadosguia").click(function () {
        $("#buscar_estadosguia").dialog("open");
    });
    $("#btnEstadosguia").click(function () {
        $("#buscar_estadosguia").dialog("open");
    });
    formaPagoCambio();
    listaPagoRetencion();
    $("#btnAgregar_mixto").click(function (e) {
        e.preventDefault();
        agregar();
    });
    $("#btnCancelarRetenciones_mixto").click(function (e) {
        e.preventDefault();
        alertify.confirm("¿Esta Seguro?", function (e) {
            if (e) {
                $('.nav-tabs a[href="#tab_1"]').tab("show");
                $("#formaspago option[value=" + "Contado" + "]").attr("selected", true);
                limpiar_campos_mixto();
            } else {
            }
        });
    });
    obtenerParametrosEmpresa();

    if (localStorage.getItem('load_retencion_tab') == 1) {
        $(".nav-tabs a[href='#tab_2']").tab("show");
        localStorage.clear();
    }
}

function actualizar_transportista() {
    $("#transportistaguia").load("transportista_combos.php");
}

function reenviar(id) {
    loaderFactura.css({"visibility": "visible"});
    $.ajax({
        type: "POST",
        url: "guardar_factura_venta.php",
        data: {
            reenviarcorreo: "reenviarcorreo",
            id: id,
        },
        //        data: "id="+x,
        dataType: "json",
        success: function (data) {
            if (data.estado == 1) {
                alertify.alert("Enviado al Correo: ");
            } else {
                alertify.alert("Error al enviar: ");
            }
        },
    })
            .always(function () {
                loaderFactura.css({"visibility": "hidden"});
            });
}

function reenviarXml(id) {
    loaderFactura.css({"visibility": "visible"});
    $.ajax({
        type: "POST",
        url: "guardar_factura_venta.php",
        data: {
            reenviarxml: "reenviarxml",
            id: id,
        },
        //        data: "id="+x,
        dataType: "json",
        success: function (data) {
            if (data.estado == 2) {
                alertify.alert("AUTORIZADO: ");
            } else {
                alertify.alert("NO AUTORIZADO: ");
            }
        },
    })
            .always(function () {
                loaderFactura.css({"visibility": "hidden"});
            });
}

function enviarXml(id) {
    loaderFactura.css({"visibility": "visible"});
    $.ajax({
        type: "POST",
        url: "guardar_factura_venta.php",
        data: {
            enviarxml: "enviarxml",
            id: id,
        },
        //        data: "id="+x,
        dataType: "json",
        success: function (data) {
            if (data.estado == 2) {
                alertify.alert("AUTORIZADO: ");
                reenviar(id);
            } else {
                alertify.alert("NO AUTORIZADO: ");
            }
        },
    })
            .always(function () {
                loaderFactura.css({"visibility": "hidden"});
            });
    ;
}

function reenviarXmlguia(id) {
    $.ajax({
        type: "POST",
        url: "guardar_factura_venta.php",
        data: {reenviarxmlguia: "reenviarxmlguia", id: id},
        //        data: "id="+x,
        dataType: "json",
        success: function (data) {
            if (data.estado == 2) {
                alertify.alert("AUTORIZADO: ");
            } else {
                alertify.alert("NO AUTORIZADO: ");
            }
        },
    });
}
function enviarXmlguia(id) {
    $.ajax({
        type: "POST",
        url: "guardar_factura_venta.php",
        data: {enviarxmlguia: "enviarxmlguia", id: id},
        //        data: "id="+x,
        dataType: "json",
        success: function (data) {
            if (data.estado == 2) {
                alertify.alert("AUTORIZADO: ");
            } else {
                alertify.alert("NO AUTORIZADO: ");
            }
        },
    });
}
//function cambio_ret_fuente() {
//    if (document.getElementById('retencionF2').checked || document.getElementById('retencionF1').checked) {
//        if ($("#id_factura_venta").val() != "") {
//            if (t == 0) {
//                alertify.alert("La factura es una factura temporal");
//                document.getElementById('retencionF1').checked = true;
//            } else {
//                $("#tipoRetencionesF").attr("disabled", false);
//            }
//        } else {
//            alertify.alert("Error, debe seleccionar una factura");
//            document.getElementById('retencionF1').checked = true;
//        }
//    } else if (document.getElementById('retencionF1').checked) {
//        document.getElementById("tipoRetencionesF").selectedIndex = 0;
//        $("#tipoRetencionesF").attr("disabled", true);
//        $("#calculoRetencionF").attr("disabled", false);
//        $("#calculoRetencionF").val("0.000");
//    }
//}

//function calculo_ret_fuente() {
//    var calculoRET = 0;
//    var x = document.getElementById("tipoRetencionesF").selectedIndex;
//    $.ajax({
//        type: "POST",
//        url: "../../procesos/buscar_ret_fuente.php",
//        data: "id=" + x,
//        success: function (data) {
//            var val = data;
//            if (val != 0) {
//                calculoRET = val;
//                alertify.alert("El porcentaje de retención es del: " + calculoRET + "%");
//                var valor = ((($("#sub").val()) * calculoRET) / 100).toFixed(3);
//                $("#calculoRetencionF").val(valor);
//            }
//        }
//    });
//}

//function cambio_ret_iva() {
//    if (document.getElementById('retencionI2').checked) {
//        if ($("#id_factura_venta").val() != "") {
//            if (t == 0) {
//                alertify.alert("La factura es una factura temporal");
//                document.getElementById('retencionI1').checked = true;
//            } else {
//                $("#tipoRetencionesI").attr("disabled", false);
//            }
//        } else {
//            alertify.alert("Error, debe seleccionar una factura");
//            document.getElementById('retencionI1').checked = true;
//        }
//    } else if (document.getElementById('retencionI1').checked) {
//        document.getElementById("tipoRetencionesI").selectedIndex = 0;
//        $("#tipoRetencionesI").attr("disabled", true);
//        $("#calculoRetencionI").attr("disabled", false);
//        $("#calculoRetencionI").val("0.000");
//    }
//}

//function calculo_ret_iva() {
//    var calculoRET = 0;
//    var x = document.getElementById("tipoRetencionesI").selectedIndex;
//    $.ajax({
//        type: "POST",
//        url: "../../procesos/buscar_ret_iva.php",
//        data: "id=" + x,
//        success: function (data) {
//            var val = data;
//            if (val != 0) {
//                calculoRET = val;
//                alertify.alert("El porcentaje de retención es del: " + calculoRET + "%");
//                var valor = (($("#iva").val()) * calculoRET) / 100;
//                $("#calculoRetencionI").val(valor);
//            }
//        }
//    });
//}

//function guardar_retenciones_factura_venta() {
//    var x = document.getElementById("tipoRetencionesF").selectedIndex;
//    if ($("#autorizacion_retencion").val() != "") {
//        if (x != 0) {
//            //if($("#calculoRetencionF").val()!= 0.000 || $("#calculoRetencionF").val()!= 0){
//            $.ajax({
//                type: "POST",
//                url: "guardar_ret_fuente_fact_venta.php",
//                data: "id_factura=" + $("#id_factura_venta").val() + "&id_retencion_fuente=" + x + "&valor_factura=" + $("#tot").val() + "&iva_factura=" + $("#iva").val() + "&valor_retencion=" + $("#calculoRetencionF").val() + "&autorizacion_ret=" + $("#autorizacion_retencion").val(),
//                success: function (data) {
//                    var val = data;
//                    if (val == 1) {
//                        alertify.alert("Retenciones guardadas correctamente", function () {
//                            location.reload();
//                        });
//                    } else if (val == 2) {
//                        alertify.alert("La Factura ya tiene retenciones en la fuente");
//                    } else {
//                        alertify.alert(val);
//                    }
//                }
//            });
//        }
//        var y = document.getElementById("tipoRetencionesI").selectedIndex;
//        if (y != 0) {
//            //if($("#calculoRetencionI").val()!= 0.000 || $("#calculoRetencionI").val()!= 0){
//            $.ajax({
//                type: "POST",
//                url: "guardar_ret_iva_fact_venta.php",
//                data: "id_factura=" + $("#id_factura_venta").val() + "&id_retencion_iva=" + y + "&valor_factura=" + $("#tot").val() + "&iva_factura=" + $("#iva").val() + "&valor_retencion=" + $("#calculoRetencionI").val() + "&autorizacion_ret=" + $("#autorizacion_retencion").val(),
//                success: function (data) {
//                    var val1 = data;
//                    if (val1 == 1) {
//                        alertify.alert("Retenciones guardadas correctamente", function () {
//                            location.reload();
//                        });
//                    } else if (val1 == 2) {
//                        alertify.alert("La factura ya tiene retenciones de IVA");
//                    } else {
//                        alertify.alert(val1);
//                    }
//                }
//            });
//        }
//    } else {
//        alertify.alert("Ingrese Número de Autorización de la Retención");
//    }
//}

function guardar_guia_remision() {
    console.log("GUARDAR GUIA");
    $("#btnGuiaRemision").attr("disabled", "disabled");
    if ($("#comprobante").val() != "") {
        if ($("#num_serie_guia").val() == "") {
            $("#num_serie_guia").focus();
            alertify.error("Ingrese número de la Guía de Remisión");
        } else {
            var serie_guia = $("#num_serie_guia").val();
            $.ajax({
                type: "POST",
                url: "comparar_num_guia.php",
                data: "serie_guia=" + serie_guia,
                success: function (data) {
                    var val = data;
                    if (val < 0) {
                        $("#num_serie_guia").val("");
                        $("#num_serie_guia").focus();
                        //                        alertify.error("Error... La factura ya existe, favor verificar el nùmero que corresponda");
                        var res1 = parseInt(val.substr(4, 16));
                        res1 = res1 + 1;
                        $("#num_serie_guia").val(res1);
                        var a1 = autocompletar_guia(res1);
                        var validado = a1 + "" + res1;
                        $("#num_serie_guia").val(validado);
                    } else {
                        $("#valor_cambioid").dialog("close");
                        var a = autocompletar($("#num_serie_guia").val());
                        var serie_guia = $("#buscar_pv").val();
                        //TODO borrar comentado
                        /* if ($("#punto_ventaid").val() == 1) {
                         var serie_guia = "001" + "-" + "001";
                         }
                         if ($("#punto_ventaid").val() == 2) {
                         var serie_guia = "001" + "-" + "003";
                         }
                         if ($("#punto_ventaid").val() == 3) {
                         var serie_guia = "001" + "-" + "001";
                         }
                         if ($("#punto_ventaid").val() == 4) {
                         var serie_guia = "003" + "-" + "001";
                         }
                         if ($("#punto_ventaid").val() == 5) {
                         var serie_guia = "005" + "-" + "001";
                         } */
                        var serieg = a + "" + $("#num_serie_guia").val();
                        $.ajax({
                            type: "POST",
                            url: "guardar_factura_venta.php",
                            data:
                                    "id_fac=" +
                                    $("#comprobante").val() +
                                    "&fecha_actual=" +
                                    $("#fecha_actual").val() +
                                    "&transportistaguia=" +
                                    $("#transportistaguia").val() +
                                    "&hora_actual=" +
                                    $("#hora_actual").val() +
                                    "&num_guia=" +
                                    serie_guia +
                                    "&direccion_cliente=" +
                                    $("#direccion_cliente").val() +
                                    "&comprobante_quia=" +
                                    $("#comprobante").val() +
                                    "&num_guia_remision=" +
                                    serieg,
                            dataType: "json",
                            success: function (data) {
                                //var data1 = '2';
                                var myWindow = window.open("generarPDFGuia.php?hoja=A4&id=" + $("#comprobante").val(), "_blank");
                                myWindow.focus();
                                myWindow.print();
                                /* if (data.estado == 2) {
                                 alertify.confirm(
                                 "AUTORIZADO¿Desea Imprimir Comprobante?",
                                 function (e) {
                                 if (e) {
                                 location.reload();
                                 } else {
                                 location.reload();
                                 }
                                 }
                                 );
                                 } else {
                                 if (data.estado == 7) {
                                 
                                 var myWindow = window.open("generarPDFGuia.php?hoja=A4&id=" + $("#comprobante").val(), "_blank");
                                 myWindow.focus();
                                 myWindow.print();
                                 alertify.alert(
                                 "Factura Guardada  No Autorizada",
                                 function () {
                                 location.reload();
                                 }
                                 );
                                 }
                                 } */
                            },
                        });
                    }
                },
            });
        }
    } else {
        alertify.error("Debe Seleccionar la Factura");
    }
}

function cambio_ret_fuenteSguia() {
    if (document.getElementById("retencionF2Sguia").checked) {

        $("#transportistaguia").show();
        $("#num_serie_guia").show();
        $("#btnActualizartrans").show();
        $("#trans").show();
        $("#translabel").show();
        $("#num_labelg").show();
        //        $("#btnGuiaRemision").show();
        $("#btnImprimirGuia").show();
        $("#btnEstadosguia").show();
        $("#transportistaguia").attr("disabled", false);
        $("#num_serie_guia").attr("disabled", false);
    } else if (document.getElementById("retencionF1Sguia").checked) {
        $("#transportistaguia").hide();
        $("#num_serie_guia").hide();
        $("#btnActualizartrans").hide();
        $("#trans").hide();
        $("#translabel").hide();
        $("#num_labelg").hide();
        $("#btnGuiaRemision").hide();
        $("#btnImprimirGuia").hide();
        $("#btnEstadosguia").hide();
    }
}

function formaPagoCambio() {
    $("#formaspago").change(function () {
        var tam2 = jQuery("#list").jqGrid("getRowData");
        if ($("#formaspago").val() == "Contado") {
            $("#adelanto").attr("disabled", "disabled");
            $("#adelanto").val("");
            $("#valor_factura").val("");
            $("#meses").attr("disabled", "disabled");
            $("#meses").val("");
            $("#cuotas").attr("disabled", "disabled");
            $("#cuotas").children().remove().end();
        } else {
            if ($("#formaspago").val() == "otros") {
                if (tam2.length > 0) {
                    $('.nav-tabs a[href="#tab_3"]').tab("show");
                    $("#formaspago_mixto").attr("disabled", false);
                } else {
                    alertify.error("Ingrese Productos");
                }
            }
        }
    });
}

function porcentaje() {
    if ($("#formaspago").val() == "Credito") {
        $("#valor_tarjetaid").show();
        $("#resultado_tar_totalid").show();
        $("#calculo_porcentajeid").show();
        $("#valor_reciboid").hide();
        $("#total_ventaid").hide();
        $("#valor_cambioitemid").hide();
        $("#valor_recibo").attr("disabled", "disabled");
        $("#total_venta").attr("disabled", "disabled");
        $("#valor_cambio").attr("disabled", "disabled");
        var total_tarjeta =
                parseFloat($("#tot").val()) *
                (parseFloat($("#valor_tarjeta").val()) / 100);
        var total_cobrar = total_tarjeta + parseFloat($("#tot").val());
        $("#resultado_tar_total").val("0");
        $("#resultado_tar_total").val(total_cobrar.toFixed(2));
        $("#calculo_porcentaje").val("0");
        $("#calculo_porcentaje").val(total_tarjeta.toFixed(2));
    } else {
        if ($("#formaspago").val() == "Contado") {
            $("#valor_recibo").focus();
            $("#valor_reciboid").show();
            $("#total_ventaid").show();
            $("#valor_cambioitemid").show();
            $("#valor_recibo").attr("disabled", false);
            $("#valor_tarjeta").hide();
            $("#resultado_tar_total").hide();
        }
    }
}

function listaPagoRetencion() {
    jQuery("#listPagoreten_mixto")
            .jqGrid({
                datatype: "local",
                colNames: [
                    "",
                    "ID",
                    "ID F",
                    "Forma Pago",
                    "Tarjeta Credito",
                    "Num Documento",
                    "Valor",
                    "Cuenta Banco",
                    "Fecha Vencimiento",
                ],
                colModel: [
                    {
                        name: "myac",
                        width: 50,
                        fixed: true,
                        sortable: false,
                        resize: false,
                        formatter: "actions",
                        formatoptions: {
                            keys: false,
                            delbutton: true,
                            editbutton: false,
                        },
                    },
                    {
                        name: "id_f_v_mix",
                        index: "id_f_v_mix",
                        editable: false,
                        align: "center",
                        width: "180",
                        search: false,
                        frozen: true,
                        hidden: true,
                        editoptions: {
                            readonly: "readonly",
                        },
                        formoptions: {
                            elmprefix: "",
                        },
                    },
                    {
                        name: "id_factura_venta",
                        index: "id_factura_venta",
                        editable: false,
                        align: "center",
                        width: "50",
                        search: false,
                        frozen: true,
                        hidden: false,
                        editoptions: {
                            readonly: "readonly",
                        },
                        formoptions: {
                            elmprefix: "",
                        },
                    },
                    {
                        name: "forma_pago_mixto",
                        index: "forma_pago_mixto",
                        editable: false,
                        align: "center",
                        width: "180",
                        search: false,
                        frozen: true,
                        editoptions: {
                            readonly: "readonly",
                        },
                        formoptions: {
                            elmprefix: "",
                        },
                    },
                    {
                        name: "tarjeta_credito",
                        index: "tarjeta_credito",
                        editable: false,
                        align: "center",
                        width: "180",
                        search: false,
                        frozen: true,
                        hidden: true,
                        editoptions: {
                            readonly: "readonly",
                        },
                        formoptions: {
                            elmprefix: "",
                        },
                    },
                    {
                        name: "num_documento",
                        index: "num_documento",
                        editable: false,
                        align: "center",
                        width: "180",
                        search: false,
                        frozen: true,
                        editoptions: {
                            readonly: "readonly",
                        },
                        formoptions: {
                            elmprefix: "",
                        },
                    },
                    {
                        name: "valor",
                        index: "valor",
                        editable: false,
                        align: "center",
                        width: "180",
                        search: false,
                        frozen: true,
                        editoptions: {
                            readonly: "readonly",
                        },
                        formoptions: {
                            elmprefix: "",
                        },
                    },
                    {
                        name: "id_cuenta",
                        index: "id_cuenta",
                        editable: false,
                        align: "center",
                        width: "180",
                        search: false,
                        frozen: true,
                        hidden: false,
                        editoptions: {
                            readonly: "readonly",
                        },
                        formoptions: {
                            elmprefix: "",
                        },
                    },
                    {
                        name: "fecha_vencimiento",
                        index: "fecha_vencimiento",
                        editable: false,
                        align: "center",
                        width: "180",
                        search: false,
                        frozen: true,
                        hidden: false,
                        editoptions: {
                            readonly: "readonly",
                        },
                        formoptions: {
                            elmprefix: "",
                        },
                    },
                ],
                rowNum: 10,
                rowList: [10, 20, 30],
                height: 120,
                sortable: true,
                pager: jQuery("#pagerP_reten"),
                sortname: "id_f_v_mix",
                sortorder: "asc",
                viewrecords: true,
                cellEdit: true,
                cellsubmit: "clientArray",
                shrinkToFit: true,
                delOptions: {
                    modal: true,
                    jqModal: true,
                    onclickSubmit: function (rp_ge, rowid) {
                        var id = jQuery("#listPagoreten_mixto").jqGrid(
                                "getGridParam",
                                "selrow"
                                );
                        jQuery("#listPagoreten_mixto").jqGrid("restoreRow", id);
                        var ret = jQuery("#listPagoreten_mixto").jqGrid("getRowData", id);
                        rp_ge.processing = true;
                        var su = jQuery("#listPagoreten_mixto").jqGrid("delRowData", rowid);
                        var total_venta = 0;
                        var valor_restante = 0;
                        var valor_total = 0;
                        console.log("" + ret.valor);
                        if (su === true) {
                            total_venta = (
                                    parseFloat($("#cantidad_mixto").val()) - ret.valor
                                    ).toFixed(2);
                            $("#cantidad_mixto").val(total_venta);
                            valor_total = (
                                    parseFloat($("#valor_factura").val()) -
                                    parseFloat($("#cantidad_mixto").val())
                                    ).toFixed(2);
                            $("#valor_factura_saldo").val(valor_total);
                        }
                        $(".ui-icon-closethick").trigger("click");
                        return true;
                    },
                    processing: true,
                },
            })
            .jqGrid("navGrid", "#pagerP_reten", {
                add: false,
                edit: false,
                del: false,
                refresh: false,
                search: true,
                view: true,
            });
}

function limpiar_campos_mixto() {
    $("#adelanto").val("0");
    $("#meses").val("");
    $("#valor_formas").val("");
    $("#num_tarjeta").val("");
    $("#listPagoreten_mixto").jqGrid("clearGridData", true);
    $("#cantidad_mixto").val() == "";
    $("#validar_guardar").val("");
    $("#btnGuardarRetenciones_mixto").attr("disabled", true);
    $("#cantidad_mixto").val("");
}

function modalBuscarEstados() {
    var dialogo10 = {
        autoOpen: false,
        resizable: false,
        width: 1300,
        height: 350,
        modal: true,
        show: "explode",
        hide: "blind",
    };
    $("#buscar_estados").dialog(dialogo10);
}

function modalBuscarEstadosGuia() {
    var dialogo77 = {
        autoOpen: false,
        resizable: false,
        width: 1040,
        height: 350,
        modal: true,
        show: "explode",
        hide: "blind",
    };
    $("#buscar_estadosguia").dialog(dialogo77);
}

function crearlLista77() {
    jQuery("#list77")
            .jqGrid({
                url: "xmlBuscarEstadosguia.php",
                datatype: "xml",
                colNames: [
                    "ID",
                    "N° AUTORIZACON",
                    "FECHA EMISION",
                    "RAZóN SOCIAL",
                    "CORREO ",
                    "FECHA AUTORIZACION",
                    "TRANSPORTISTA",
                    "ESTADO",
                    "ENVIO XML",
                    "CONSULTA COMPROBANTE",
                ],
                colModel: [
                    {
                        name: "id_guia_remision",
                        index: "id_guia_remision",
                        editable: false,
                        search: false,
                        hidden: false,
                        editrules: {edithidden: false},
                        align: "center",
                        frozen: true,
                        width: 50,
                    },
                    {
                        name: "autorizacion",
                        index: "autorizacion",
                        editable: false,
                        search: false,
                        hidden: false,
                        editrules: {edithidden: false},
                        align: "center",
                        frozen: true,
                        width: 100,
                    },
                    {
                        name: "fecha_emision",
                        index: "fecha_emision",
                        editable: true,
                        search: true,
                        hidden: false,
                        editrules: {edithidden: false},
                        align: "center",
                        frozen: true,
                        width: 100,
                    },
                    {
                        name: "razon_social",
                        index: "razon_social",
                        editable: true,
                        search: false,
                        hidden: false,
                        editrules: {edithidden: false},
                        align: "center",
                        frozen: true,
                        width: 100,
                    },
                    {
                        name: "correo",
                        index: "correo",
                        editable: true,
                        search: false,
                        hidden: false,
                        editrules: {edithidden: false},
                        align: "center",
                        frozen: true,
                        width: 100,
                    },
                    {
                        name: "fecha_autorizacion",
                        index: "fecha_autorizacion",
                        editable: true,
                        search: false,
                        hidden: false,
                        editrules: {edithidden: false},
                        align: "center",
                        frozen: true,
                        width: 100,
                    },
                    {
                        name: "total",
                        index: "total",
                        editable: true,
                        search: false,
                        hidden: false,
                        editrules: {edithidden: false},
                        align: "center",
                        frozen: true,
                        width: 50,
                    },
                    {
                        name: "estado",
                        index: "estado",
                        editable: true,
                        search: false,
                        hidden: false,
                        editrules: {edithidden: false},
                        align: "center",
                        frozen: true,
                        width: 50,
                    },
                    //            {name:'accion', index:'accion', editable: false, hidden: false, search:false, frozen: true, editrules: {required: true}, align: 'center', width: '80px'},
                    {
                        name: "envioguia",
                        index: "envioguia",
                        editable: false,
                        hidden: false,
                        search: false,
                        frozen: true,
                        editrules: {required: true},
                        align: "center",
                        width: "80px",
                    },
                    {
                        name: "reenvioguia",
                        index: "reenvioguia",
                        editable: false,
                        hidden: false,
                        search: false,
                        frozen: true,
                        editrules: {required: true},
                        align: "center",
                        width: "80px",
                    },
                ],
                rowNum: 30,
                width: 1000,
                height: 220,
                sortable: true,
                rowList: [10, 20, 30],
                pager: jQuery("#pager77"),
                sortname: "id_guia_remision",
                sortorder: "desc",
                viewrecords: true,
                gridComplete: function () {
                    var ids = jQuery("#list77").jqGrid("getDataIDs");
                    for (var i = 0; i < ids.length; i++) {
                        var ids = jQuery("#list77").getDataIDs();
                        for (var i = 0; i < ids.length; i++) {
                            var id_guia_remision = ids[i];
                            be =
                                    "<a  onclick=\"enviarXmlguia('" +
                                    id_guia_remision +
                                    "')\" title='Reenviar Xml' ><i class='fa fa-arrow-circle-right' style='cursor:pointer; cursor: hand'> Envio Xml</i></a>";
                            jQuery("#list77").jqGrid("setRowData", ids[i], {envioguia: be});
                        }
                    }
                    for (var i = 0; i < ids.length; i++) {
                        var ids = jQuery("#list77").getDataIDs();
                        for (var i = 0; i < ids.length; i++) {
                            var id_guia_remision = ids[i];
                            be =
                                    "<a  onclick=\"reenviarXmlguia('" +
                                    id_guia_remision +
                                    "')\" title='Reenviar Xml' ><i class='fa fa-repeat' style='cursor:pointer; cursor: hand'> Consulta Xml</i></a>";
                            jQuery("#list77").jqGrid("setRowData", ids[i], {reenvioguia: be});
                        }
                    }
                },
                ondblClickRow: function () {
                    var id = jQuery("#list77").jqGrid("getGridParam", "selrow");
                    jQuery("#list77").jqGrid("restoreRow", id);
                },
            })
            .jqGrid(
                    "navGrid",
                    "#pager77",
                    {
                        add: false,
                        edit: false,
                        del: false,
                        refresh: true,
                        search: true,
                        view: true,
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
    jQuery("#list77").jqGrid("navButtonAdd", "#pager77", {
        caption: "Reeviar",
        onClickButton: function () {
            var id = jQuery("#list77").jqGrid("getGridParam", "selrow");
            jQuery("#list77").jqGrid("restoreRow", id);
            if (id) {
                var ret = jQuery("#list77").jqGrid("getRowData", id);
            }
        },
    });
}

function numFormatter(d) {
    return new Intl.NumberFormat("en-US", {
        minimumFractionDigits: d,
        maximumFractionDigits: d,
        useGrouping: false,
    });
}

function calcularTotalTabla() {
    var subtotal0 = 0;
    var subtotal12 = 0;
    var iva12 = 0;
    var total_total = 0;
    var descu_total = 0;
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
        if (dd["iva"] == "Si") {
            if (dd["incluye"] == "No") {
                subtotal = dd["total"];
                sub1 = subtotal;
                iva1 = sub1 * (calculoIVA / 100);
                subtotal0 = parseFloat(subtotal0) + 0;
                subtotal12 = parseFloat(subtotal12) + parseFloat(sub1);
                subtotal_total = parseFloat(subtotal0) + parseFloat(subtotal12);
                descu_total = parseFloat(descu_total) + parseFloat(dd["cal_des"]);
                iva12 = parseFloat(iva12) + parseFloat(iva1);
                subtotal0 = parseFloat(subtotal0);
                subtotal12 = parseFloat(subtotal12);
                subtotal_total = parseFloat(subtotal_total);
                iva12 = parseFloat(iva12);
                descu_total = parseFloat(descu_total);
                suma_total = suma_total + dd["cantidad"];
            } else {
                if (dd["incluye"] == "Si") {
                    subtotal = dd["total"];
                    sub2 = subtotal / (calculoIVA / 100 + 1);
                    iva2 = sub2 * (calculoIVA / 100);
                    subtotal0 = parseFloat(subtotal0) + 0;
                    subtotal12 = parseFloat(subtotal12) + parseFloat(sub2);
                    subtotal_total = parseFloat(subtotal0) + parseFloat(subtotal12);
                    iva12 = parseFloat(iva12) + parseFloat(iva2);
                    descu_total = parseFloat(descu_total) + parseFloat(dd["cal_des"]);
                    subtotal0 = parseFloat(subtotal0);
                    subtotal12 = parseFloat(subtotal12);
                    subtotal_total = parseFloat(subtotal_total);
                    iva12 = parseFloat(iva12);
                    descu_total = parseFloat(descu_total);
                    suma_total = suma_total + dd["cantidad"];
                }
            }
        } else {
            if (dd["iva"] == "No") {
                subtotal = dd["total"];
                sub = subtotal;
                subtotal0 = parseFloat(subtotal0) + parseFloat(sub);
                subtotal12 = parseFloat(subtotal12) + 0;
                subtotal_total = parseFloat(subtotal0) + parseFloat(subtotal12);
                iva12 = parseFloat(iva12) + 0;
                descu_total = parseFloat(descu_total) + parseFloat(dd["cal_des"]);
                subtotal0 = parseFloat(subtotal0);
                subtotal12 = parseFloat(subtotal12);
                subtotal_total = parseFloat(subtotal_total);
                iva12 = parseFloat(iva12);
                descu_total = parseFloat(descu_total);
                suma_total = suma_total + dd["cantidad"];
            }
        }
    }

    total_total =
            parseFloat(total_total) +
            (parseFloat(subtotal0) + parseFloat(subtotal12) + parseFloat(iva12));
    total_total = parseFloat(total_total);
    var item = fil.length + 1;
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
    $("#descxax").val(descu_total.toFixed(4));
    $("#totx").val(total_total.toFixed(2));
    $("#items").val(item);
    $("#num").val(suma_total);
    $("#codigo_barras").focus();
    $("#valor_factura").val(total_total.toFixed(2));
}
//subir 1409 17/17

function obtenerDescuentoProducto(codprod) {
    return $.ajax({
        url: "obtener_descuento.php",
        method: "GET",
        dataType: "json",
        data: {cod_prod: codprod}
    });
}

function procesarFacturaUI() {
    loaderFactura.css({"visibility": "visible"});
    loadingFactura = true;
}
function pararProcesarFacturaUI() {
    loaderFactura.css({"visibility": "hidden"});
    loadingFactura = false;
}
function anularFacturaUI() {
    loaderFactura.css({"visibility": "visible"});
    loadingAnular = true;
}
function pararAnularFacturaUI() {
    loaderFactura.css({"visibility": "hidden"});
    loadingAnular = false;
}

function autorizarFactura(idfact, clave) {
    $.ajax({
        method: "POST",
        url: "../../procesos/autorizacion_documentos/autorizar_factura.php",
        data: {id_factura: idfact, clave_acceso: clave},
        dataType: "json"
    });
}

///FORMA PAGO NOTA CREDITO
var valoresNotaCredito = [];

function iniDialogValoresNotasC() {
    let dialogo22 =
            {
                autoOpen: false,
                resizable: false,
                width: 640,
                height: 360,
                modal: true,
                // position: "top",
                show: "explode",
                hide: "blind",
                buttons: [
                    {
                        text: "Aceptar",
                        //"class": 'cancelButtonClass',
                        click: function () {
                            llenarValoresPagosNC();
                        }
                    },
                    {
                        text: "cancelar",
                        //"class": 'saveButtonClass',
                        click: function () {
                            $(this).dialog("close");
                        }
                    }
                ],
                open: function (event, ui) {
                    $(document).off("keydown");
                    cargarTablaValoresNcClientes();
                },
                close: function (event, ui) {

                    $(document).keydown(function (e) {

                        if (e.target.id == 'clavefactura') {
                            console.log(e.target.id, 1);
                            return;
                        }
                        var e = e || event;
                        var keycode = e.which || e.keyCode;
                        var obj = e.target || e.srcElement;
                        // No activar el evento si estamos en un formulario
                        //if(obj.tagName.toLowerCase()=="textarea") { return; }
                        //if(obj.tagName.toLowerCase()=="input") { return; }
                        // Guardar Factura
                        //    if(keycode == 118) { guardar_factura()}
                        //    if (keycode == 17) {
                        //        abrirDialogop()
                        //    }
                        //    if (keycode == 38) {
                        //        seleccion_row()
                        //    }
                        // Tecla Control Cliente
                        /*if (keycode == 17) {
                         $("#ruc_ci").select()
                         }*/
                        if (keycode == 119) {
                            ingresar_cambio(e);
                        }
                        if (keycode == 13) {
                            if ($("#formaspago").val() == "otros") {
                                agregar();
                            }
                        }
                        // Tecla Control Cliente
                        //    if (keycode == 40) {
                        //        agregar()
                        //    }
                        /*if (keycode == 39) {
                         guardar_serie()
                         }*/
                        if (keycode == 27) {
                            cancelar();
                        }
                    });

                    $("#formaspago_mixto").val("Contado");
                    $("#formaspago_mixto").change();
                }

            };
    $("#buscar_val_nc").dialog(dialogo22);
    initTablaValoresNotasC();
}

function initTablaValoresNotasC() {
    jQuery("#list22").jqGrid({
        datatype: 'local',
        colNames: ['Num Docu', 'Fecha Registro', 'Valor'],
        colModel: [
            {
                name: 'num_nota', index: 'num_nota', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'left',
                frozen: true, width: 150
            },
            {name: 'fecha_actual', index: 'fecha_actual', editable: false, frozen: true, hidden: false, editrules: {required: true}, align: 'left', width: 150},
            {name: 'valor', index: 'valor', editable: true, frozen: true, hidden: false, editrules: {required: true}, align: 'left', width: 100},
        ],
        rowNum: 10,
        width: 600,
        rowList: [10, 20, 30],
        pager: jQuery('#pager22'),
        shrinkToFit: true,
        sortorder: 'asc',
        caption: 'Lista de Valores a Favor de Clientes por Notas de Crédito',
        viewrecords: true,
        multiselect: true,
        onSelectRow: function (rowid, status, e) {
            let find = valoresNotaCredito.find(el => el.id_formas_pago_mixto_nv == rowid);
            find.status = status;
        },
        onSelectAll: function (aRowids, status) {
            aRowids.forEach(el => {
                let find = valoresNotaCredito.find(f => f.id_formas_pago_mixto_nv == el);
                find.status = status;
            });
        }
    }).jqGrid('navGrid', '#pager22', {
        add: false,
        edit: false,
        del: false,
        refresh: false,
        search: false,
        view: false
    });
}

function obtenerValoresNcClientes(idcliente) {
    return $.ajax({
        url: "valores_nc_cliente.php",
        method: "GET",
        data: {
            id_cliente: idcliente
        },
        dataType: "json"
    }).done(function (data) {
        return data;
    });
}

async function cargarTablaValoresNcClientes() {
    try {
        let selvalues = [];
        jQuery("#list22").jqGrid("clearGridData");
        if (!!$("#id_cliente").val()) {
            let valoresnc = await obtenerValoresNcClientes($("#id_cliente").val());
            valoresNotaCredito = valoresnc.map(el => {
                el.status = false;
                return el;
            });
            let fil = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
            fil = fil.filter(el => el.forma_pago_mixto == "NOTA_CREDITO");
            if (fil.length > 0) {
                fil.forEach(el => {
                    let find = valoresNotaCredito.find(f => f.id_formas_pago_mixto_nv == el.num_documento);
                    selvalues.push(find.id_formas_pago_mixto_nv);
                });
            }
            valoresnc.forEach((el) => {
                jQuery("#list22").jqGrid('addRowData', el.id_formas_pago_mixto_nv, el);
            });
            selvalues.forEach(el => {
                jQuery("#list22").jqGrid("setSelection", [el], true);
            });
        }
    } catch (error) {
        console.error(error);
    }
}

function llenarValoresPagosNC() {
    $("#validar_guardar_grid").val("1");
    let totalcxc = 0;
    valoresNotaCredito.forEach(el => {
        if (el.status) {
            totalcxc += Number(el.valor);
        }
    });

    let fil = jQuery("#listPagoreten_mixto").jqGrid("getRowData");

    let filsinc = fil.filter(el => el.forma_pago_mixto != "NOTA_CREDITO");
    let totalgrid = 0;
    for (let t = 0; t < filsinc.length; t++) {
        let dd = filsinc[t];
        totalgrid = totalgrid + parseFloat(dd["valor"]);
    }
    let total = totalgrid + totalcxc;

    if (Number($("#valor_factura").val()) < total) {
        $("#alertify-logs").empty();
        alertify.error(
                "Error.. La suma supera el total de la Factura " + $("#totx").val()
                );
        return;
    }

    let cxcfil = fil.filter(el => el.forma_pago_mixto == "NOTA_CREDITO");
    cxcfil.forEach(el => {
        jQuery("#listPagoreten_mixto").jqGrid("delRowData", el.id_f_v_mix);
    });
    jQuery("#listPagoreten_mixto").trigger('reloadGrid');

    let filas2 = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
    count = filas2.length;

    valoresNotaCreditoSel = valoresNotaCredito.filter(el => el.status);
    valoresNotaCreditoSel.forEach(el => {
        count++;
        let datarow = {
            id_f_v_mix: count,
            id_factura_venta: $("#comprobante").val(),
            fecha: $("#fecha_actual").val(),
            forma_pago_mixto: $("#formaspago_mixto").val(),
            tarjeta_credito: $("#tarjetas").val(),
            num_documento: el.id_formas_pago_mixto_nv, //$("#num_tarjeta").val(),
            valor: el.valor, //$("#valor_formas").val(),
            id_cuenta: "", //$("#idCuenta").val(),
            fecha_vencimiento: ""//$("#fecha_dias").val(),
        };
        su = jQuery("#listPagoreten_mixto").jqGrid("addRowData", count, datarow);
    });

    var subtotal = 0;
    var sub1 = 0;
    fil = jQuery("#listPagoreten_mixto").jqGrid(
            "getRowData"
            );
    for (var t = 0; t < fil.length; t++) {
        var dd = fil[t];
        subtotal = subtotal + parseFloat(dd["valor"]);
    }

    $("#cantidad_mixto").val(subtotal.toFixed(2));
    var subtotal_adelanto1 =
            parseFloat($("#valor_factura").val()) -
            parseFloat($("#cantidad_mixto").val());

    $("#valor_factura_saldo").val(
            subtotal_adelanto1.toFixed(2)
            );
    $("#buscar_val_nc").dialog("close");
}
//francis 7/2/2023

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

function obtenerCentroCosoTransaccion(idtransaccion, tipodoc) {
    return $.ajax({
        url: "retornar_centro_costo.php",
        method: "GET",
        dataType: "json",
        data: {id_transaccion: idtransaccion, tipodoc: tipodoc},
        success: function (data) {
            if (!!data.id_centro_costo) {
                $("#sel_centro_costo").val(data.id_centro_costo);
            } else {
                $("#sel_centro_costo").val("");
            }
        }
    });
}

function cargarFacturaDblclick(id) {
    if (id) {
        var valor = id;
        obtenerCentroCosoTransaccion(valor, 'FACTURA');
        /////////////agregregar datos factura////////
        $("#comprobante").val(valor);
        $("#btnGuardar").attr("disabled", true);
        //            $("#btnGuardarTemporal").attr("disabled", true);

        // $("#num_factura").attr("disabled", true);
        $("#id_cliente").val("");
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
        $("#cuotas").children().remove().end();
        $("#list").jqGrid("clearGridData", true);
        $("#total_p").val("0.000");
        $("#total_p2").val("0.000");
        $("#iva").val("0.000");
        $("#desc").val("0.000");
        $("#tot").val("0.000");
        $("#descxa").val("0");
        $("#total_px").val("0.000");
        $("#total_p2x").val("0.000");
        $("#ivax").val("0.000");
        $("#descxax").val("0.000");
        $("#totx").val("0.000");
        $.getJSON("retornar_factura_venta.php?com=" + valor, function (data) {
            var tama = data.length;
            t = data[23];
            if (tama !== 0) {
                for (var i = 0; i < tama; i = i + 24) {
                    $("#id_factura_venta").val(data[i]);

                    $("#fecha_actual").val(data[i + 1]);
                    $("#hora_actual").val(data[i + 2]);
                    $("#digitador").val(data[i + 3] + " " + data[i + 4]);
                    var num = data[i + 5];
                    var res = num;
                    $("#num_factura").val(res);

                    $("#id_cliente").val(data[i + 6]);
                    $("#ruc_ci").val(data[i + 7]);
                    $("#nombre_cliente").val(data[i + 8]);
                    $("#direccion_cliente").val(data[i + 9]);
                    $("#telefono_cliente").val(data[i + 10]);
                    $("#correo").val(data[i + 11]);
                    $("#autorizacion").val(data[i + 12]);
                    $("#fecha_auto").val(data[i + 13]);
                    $("#fecha_caducidad").val(data[i + 14]);
                    $("#cancelacion").val(data[i + 15]);
                    $("#tipo_precio").val(data[i + 16]);
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
                        if (data[i + 23] == "0") {
                            $("#btnModificar").attr("disabled", false);
                            $("#btnGuardar").attr("disabled", false);
                            $("#codigo_barras").attr("disabled", false);
                            $("#codigo").attr("disabled", false);
                            $("#producto").attr("disabled", false);
                            $("#cantidad").attr("disabled", false);
                            $("#p_venta").attr("disabled", false);
                            $("#descuento").attr("disabled", false);
                            $("#formaspago").attr("disabled", false);
                        } else if (data[i + 23] == "1") {
                            $("#btnModificar").attr("disabled", "disabled");
                        }
                    }

                    $("#total_p").val(data[i + 18]);
                    $("#total_p2").val(data[i + 19]);
                    $("#sub").val(
                            parseFloat(data[i + 18]) + parseFloat(data[i + 19])
                            );
                    $("#iva").val(data[i + 20]);
                    $("#desc").val(data[i + 21]);
                    $("#tot").val(data[i + 22]);
                    $("#total_px").val(parseFloat(data[i + 18]).toFixed(2));
                    $("#total_p2x").val(parseFloat(data[i + 19]).toFixed(2));
                    $("#subx").val(
                            (parseFloat(data[i + 18]) + parseFloat(data[i + 19])).toFixed(
                            2
                            )
                            );
                    $("#ivax").val(parseFloat(data[i + 20]).toFixed(2));
                    $("#descxax").val(parseFloat(data[i + 21]).toFixed(2));
                    $("#totx").val(parseFloat(data[i + 22]).toFixed(2));
                    $("#id_factura_venta").trigger("change");
                }
                volver_rf();
                volver_ri();
            }
        });
        $.getJSON(
                "retornar_factura_venta_credito.php?com=" + valor,
                function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 4) {
                            $("#formaspago").val(data[i]);
                            $("#adelanto").val(data[i + 1]);
                            $("#meses").val(data[i + 2]);
                            //////////calcular meses//////////
                            if (data[i + 2] > 1) {
                                $("#cuotas").attr("disabled", false);
                                for (var j = 1; j <= data[i + 2] - 1; j++) {
                                    var calcu = data[i + 3] / data[i + 2];
                                    var entero = Math.floor(calcu).toFixed(2);
                                    $("#cuotas").append("<option>" + entero + "</option>");
                                }
                                var calcu1 = entero * (data[i + 2] - 1);
                                var sal = data[i + 3] - calcu1;
                                var entero2 = sal.toFixed(2);
                                $("#cuotas").append("<option>" + entero2 + "</option>");
                            } else {
                                $("#cuotas").attr("disabled", false);
                                $("#cuotas").append("<option>" + data[i + 3] + "</option>");
                            }
                        }
                    }
                }
        );
        $.getJSON(
                "retornar_formas_mixto_grid.php?com=" + valor,
                function (data) {
                    $("#listPagoreten_mixto").jqGrid("clearGridData", true);
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 6) {
                            var datarow = {
                                forma_pago_mixto: data[i],
                                tarjeta_credito: data[i + 1],
                                num_documento: data[i + 2],
                                valor: data[i + 3],
                                id_cuenta: data[i + 4],
                                fecha_vencimiento: data[i + 5],
                            };
                            var su = jQuery("#listPagoreten_mixto").jqGrid("addRowData", data[i], datarow);
                        }
                    }
                }
        );
        $.getJSON(
                "retornar_retenciones_grid.php?com=" + valor,
                function (data) {
                    $("#listPagoreten").jqGrid("clearGridData", true);
                    var tama = data.length;
                    if (tama != 0) {
                        $("#btnGuardarRetenciones").attr("disabled", true);
                        for (var i = 0; i < tama; i = i + 7) {
                            var datarow = {
                                base_imponible: data[i],
                                impuesto: data[i + 1],
                                porcent_reten: data[i + 2],
                                valor_retenido: data[i + 3],
                                codigo_ret: data[i + 6],
                            };
                            var num = data[i + 5];
                            var res = num.substr(8, 20);
                            $("#serie_retencion").val(num);
                            var su = jQuery("#listPagoreten").jqGrid("addRowData", data[i], datarow);
                        }
                    }
                }
        );
        $.getJSON(
                "retornar_factura_venta2.php?com=" + valor,
                function (data) {
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
                        for (var i = 0; i < tama; i = i + 13) {
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
                                incluye: data[i + 9],
                                cantidad_unidad: data[i + 10],
                                unidad_medida: data[i + 11],
                                detalle_producto: data[i + 12]
                            };
                            var su = jQuery("#list").jqGrid("addRowData", data[i], datarow);
                            suma_total = suma_total + parseFloat(data[i + 3]);
                        }
                        var fila = jQuery("#list").jqGrid("getRowData");
                        $("#items").val(fila.length);
                        $("#num").val(suma_total);
                    }
                }
        );
        $("#total_retencion").val("");
        $("#formaspago_mixto_reten").val("");
        $("#cuenta_contable_reten").val("");
        $("#idCuenta_reten").val("");
        $("#formaspago_mixto_reten")[0].disabled = true;
        $("#btnCuenta_reten")[0].disabled = true;


        $("#buscar_facturas_venta").dialog("close");
        $("#buscar_estados").dialog("close");
        $("#tipo_busqueda").dialog("close");
    } else {
        alertify.alert("Seleccione una Factura");
    }
}

function mostrarAbrirCaja() {
    console.log("mostrar caja");
    console.log(cajaAbierta.value);
    if (cajaAbierta.value) {
        $("#conteiner_apertura").css({display: "none"});
        $(".content").css({display: ""});
    } else {
        $("#conteiner_apertura").css({display: ""});
        $(".content").css({display: "none"});
    }
}

window.showTabRetenciones = function () {
    $(".nav-tabs a[href='#tab_2']").tab("show");
};