$(document).on("ready", inicio);

var formatoFC = "";
var formatoRC = "";
var num_serie_ret = "";
//var retenciones = "";
var check_retenciones = "";
var cmpAddCliente;

var calculoIVA = 0;
var codImpuesto = 0;
var codTarifa = 0;

function obtenerParametrosEmpresa() {
    fetch("obtener_parametros_empresa.php")
        .then(function (d) {
            return d.json();
        })
        .then(function (json) {
            formatoFC = json["formato_imperesion_factura_compra"];
            formatoRC = json["formato_imperesion_retencion_compra"];
            //                retenciones = json["agente_reten"];
            check_retenciones = json["check_agente_reten"];
            if (check_retenciones == 1) {
                $("#tab2").show();
                $("#btnEstados").attr("disabled", false);

            } else {

                $("#btnEstados").attr("disabled", true);
                $("#tab2").hide();
            }
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
    //    if (keycode == 17) {
    //        abrirDialogo()
    //    }
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
$("#btnBuscarRetenciones").click(function () {
    seleccion_row();

});
function seleccion_row() {


    var id_fac = $("#id_factura_compra").val();
    if (id_fac === "") {
        alertify.error("Error... Seleccione una factura");
    } else {
        $("#list77").jqGrid('setGridParam', {
            url: 'xmlBuscarRetenciones.php?id=' + id_fac,
            datatype: 'xml'
        }).trigger('reloadGrid');
        $("#buscar_retenciones").dialog("open");
    }


}
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
var dialogo22 = {
    autoOpen: false,
    resizable: false,
    width: 900,
    height: 350,
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
var dialogo2 = {
    autoOpen: false,
    resizable: false,
    width: 900,
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
    width: 1350,
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
function aceptarEliminar_reten() {
    $("#btnAceptar_reten").attr("disabled", true);
    var datos = {
        id_comprobante_serie: $("#id_comprobante_serie").val(),
        id_factura_compra: $("#id_factura_compra").val(),
        observacion: $("#observacion").val(),
        total_retencion_oculto: $("#total_retencion_oculto").val()
    }
    if ($("#id_comprobante_serie").val() == "") {
        alertify.error("Seleccione una factura");
        $("#buscar_facturas_compras").dialog("open");
    } else {
        $.ajax({
            type: "POST",
            url: "eliminar_retenciones.php",
            data: datos,
            success: function (data) {
                var val = data;
                if (val == 1) {
                    alertify.success("Retención Eliminada Correctamente");
                    setTimeout(function () {
                        location.reload();
                    }, 3000);
                } else {
                    alertify.alert(val);
                }
            }
        });
    }
}
function aceptarEliminar() {
    $("#btnAceptar").attr("disabled", true);
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
                    alertify.success("Factura Eliminada Correctamente");
                    setTimeout(function () {
                        location.reload();
                    }, 3000);
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
    $("#cod_producto").val("").change();
    $("#codigo_barras").val("");
    $("#codigo").val("");
    $("#producto").val("");
    $("#cantidad").val("");
    $("#precio").val("");
    $("#precio_v").val("");
    $("#descuento").val("");
    $("#iva_producto").val("");
    $("#carga_series").val("");
    $("#incluye").val("");
    if ($("#tipo_comprobante").val() == 'NOTA') {
        $("#tipo_iva").val("1");
    } else {
        $("#tipo_iva")[0][0].selected = true
    }
    $("#stock").val("");
    $("#cantidad_unidad").val("");
    $("#unidad_medida").val("");
    $("#unidad_medida").empty();
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
                alertify.alert("La forma de pago ya estÃ¡ ingresada");
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
    $("#observacionPago").val(formas[2]);
    $("#detalle_pago").val(formas[1]);




}
async function comprobar2() {

    let tivadata = $("#tipo_iva")[0].selectedOptions[0].dataset;
    calculoIVA = Number(tivadata.valor);
    codImpuesto = Number(tivadata.codimp);
    codTarifa = Number(tivadata.codtarifa);


    var subtotal0 = 0;
    var subtotal12 = 0;
    var subtotal_total = 0;
    var iva12 = 0;
    var total_total = 0;
    var descu_total = 0;
    var cantidad_unidad = 0;
    var unidad_medida = "";
    var cantidad_cu_medida = "";

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
                                if ($("#cantidad_unidad").val() != "") {
                                    cantidad_cu_medida = parseFloat($("#cantidad_unidad").val());
                                    cantidad_unidad = parseFloat($("#cantidad_unidad").val()) * parseFloat($("#cantidad").val());
                                    unidad_medida = $("#unidad_medida")[0].selectedOptions[0].text;
                                    unidad_medida = unidad_medida.split("----");
                                    unidad_medida = unidad_medida[0];
                                } else {
                                    cantidad_unidad = 0;
                                }

                                precio = parseFloat($("#precio").val());
                                cantidadu = parseFloat($("#cantidad").val());
                                if (!!cantidad_unidad) {
                                    precio = precio / cantidad_cu_medida;
                                    cantidadu = cantidad_unidad;
                                }

                                if ($("#descuento").val() != "") {
                                    desc = $("#descuento").val();
                                    multi = cantidadu * precio;
                                    descuento = ((multi * parseFloat(desc)) / 100);
                                    flotante = parseFloat(descuento);
                                    resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                    total = multi - resultado;
                                } else {
                                    desc = 0;
                                    multi = cantidadu * precio;
                                    descuento = ((multi * parseFloat(desc)) / 100);
                                    flotante = parseFloat(descuento);
                                    resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                    total = cantidadu * precio;
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
                                    precio_v: $("#precio_v").val(),
                                    cantidad_unidad: cantidad_unidad,
                                    unidad_medida: unidad_medida,
                                    id_plan: $("#id_plan").val(),
                                    valor_iva: calcularIva(Number(total)),
                                    tarifa: calculoIVA,
                                    cod_impuesto: codImpuesto,
                                    cod_tarifa: codTarifa,
                                };
                                addCentroCostoRowData(datarow);

                                su = jQuery("#list").jqGrid('addRowData', $("#cod_producto").val() + "" + $("#sel_centro_costo").val(), datarow);
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
                                    suma = Number(can) + Number($("#cantidad").val());

                                    if ($("#cantidad_unidad").val() != "") {
                                        cantidad_cu_medida = parseFloat($("#cantidad_unidad").val());
                                        cantidad_unidad = parseFloat($("#cantidad_unidad").val()) * suma;
                                        unidad_medida = $("#unidad_medida")[0].selectedOptions[0].text;
                                        unidad_medida = unidad_medida.split("----");
                                        unidad_medida = unidad_medida[0];
                                    } else {
                                        cantidad_unidad = 0;
                                        unidad_medida = '';
                                    }


                                    precio = parseFloat($("#precio").val());
                                    cantidadu = parseFloat($("#cantidad").val());
                                    if (!!cantidad_unidad) {
                                        precio = precio / cantidad_cu_medida;
                                        cantidadu = cantidad_unidad;
                                    }

                                    if ($("#descuento").val() != "") {
                                        desc = $("#descuento").val();
                                        multi = parseFloat(suma) * precio;
                                        descuento = ((multi * parseFloat(desc)) / 100);
                                        flotante = parseFloat(descuento);
                                        resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                        total = multi - resultado;
                                    } else {
                                        desc = 0;
                                        multi = cantidadu * precio;
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
                                        precio_v: $("#precio_v").val(),
                                        cantidad_unidad: cantidad_unidad,
                                        unidad_medida: unidad_medida,
                                        id_plan: $("#id_plan").val(),
                                        valor_iva: calcularIva(Number(total)),
                                        tarifa: calculoIVA,
                                        cod_impuesto: codImpuesto,
                                        cod_tarifa: codTarifa,
                                    };
                                    addCentroCostoRowData(datarow);

                                    su = jQuery("#list").jqGrid('setRowData', $("#cod_producto").val() + "" + $("#sel_centro_costo").val(), datarow);
                                    limpiar_campos();
                                } else {
                                    if ($("#cantidad_unidad").val() != "") {
                                        cantidad_cu_medida = parseFloat($("#cantidad_unidad").val());
                                        cantidad_unidad = parseFloat($("#cantidad_unidad").val()) * parseFloat($("#cantidad").val());
                                        unidad_medida = $("#unidad_medida")[0].selectedOptions[0].text;
                                        unidad_medida = unidad_medida.split("----");
                                        unidad_medida = unidad_medida[0];
                                    } else {
                                        cantidad_unidad = 0;
                                        unidad_medida = '';
                                    }

                                    precio = parseFloat($("#precio").val());
                                    cantidadu = parseFloat($("#cantidad").val());
                                    if (!!cantidad_unidad) {
                                        precio = precio / cantidad_cu_medida;
                                        cantidadu = cantidad_unidad;
                                    }

                                    if ($("#descuento").val() != "") {
                                        desc = $("#descuento").val();
                                        multi = cantidadu * precio;
                                        descuento = ((multi * parseFloat(desc)) / 100);
                                        flotante = parseFloat(descuento);
                                        resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                        total = multi - resultado;
                                    } else {
                                        desc = 0;
                                        multi = parseFloat($("#cantidad").val()) * precio;
                                        descuento = ((multi * parseFloat(desc)) / 100);
                                        flotante = parseFloat(descuento);
                                        resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                        total = cantidadu * precio;
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
                                        precio_v: $("#precio_v").val(),
                                        cantidad_unidad: cantidad_unidad,
                                        unidad_medida: unidad_medida,
                                        id_plan: $("#id_plan").val(),
                                        valor_iva: calcularIva(Number(total)),
                                        tarifa: calculoIVA,
                                        cod_impuesto: codImpuesto,
                                        cod_tarifa: codTarifa,
                                    };
                                    addCentroCostoRowData(datarow);
                                    su = jQuery("#list").jqGrid('addRowData', $("#cod_producto").val() + "" + $("#sel_centro_costo").val(), datarow);
                                    limpiar_campos();
                                }
                            }
                            calcularTotalesTablaProductos();
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

                var f = document.getElementById("tipoRetencionesF").selectedIndex;
                if ($("#calculoRetencionF").val() == "0" || $("#calculoRetencionF").val() == "0.000") {
                    $("#calculoRetencionF").val("0");
                }
                if (f != "4" && $("#calculoRetencionF").val() == "0") {

                    alertify.error("Error..... Valor de Retencion debe ser distinto a 0");


                } else if (f != "4" && $("#calculoRetencionF").val() != "0") {


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
                } else if (f == "4" && $("#calculoRetencionF").val() == "0") {
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

                var fs = document.getElementById("tipoRetencionesFS").selectedIndex;
                if ($("#calculoRetencionFS").val() == "0" || $("#calculoRetencionFS").val() == "0.000") {
                    $("#calculoRetencionFS").val("0");
                }
                if (fs != "4" && $("#calculoRetencionFS").val() == "0") {
                    alertify.error("Error..... Valor de Retencion debe ser distinto a 0");
                } else if (fs != "4" && $("#calculoRetencionFS").val() != "0") {
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
                } else if (fs == "4" && $("#calculoRetencionFS").val() == "0") {
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
                var calculoserviva = $("#calculobieniva").val();

                if ($("#calculoRetencionI").val() == "0" || $("#calculoRetencionI").val() == "0.000" || $("#calculoRetencionI").val() == "0.00") {

                    alertify.error("Error..... Valor de Retencion debe ser distinto a 0");

                } else if ($("#calculoRetencionI").val() != "0" || $("#calculoRetencionI").val() != "0.000" || $("#calculoRetencionI").val() == "0.00") {
                    if (filas.length == 0) {
                        var datarow = {
                            base_imponible: parseFloat(calculoserviva).toFixed(2),
                            impuesto: impuesto,
                            porcent_reten: $("#porcent_iva").val(),
                            valor_retenido: $("#calculoRetencionI").val(),
                            id_retenciones_ser: xsid_iva,
                            tipo_ret: 'b'

                        };
                        su = jQuery("#listPagoreten").jqGrid('addRowData', parseFloat(calculoserviva).toFixed(2), datarow);
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
                                base_imponible: parseFloat(calculoserviva).toFixed(2),
                                impuesto: impuesto,
                                porcent_reten: $("#porcent_iva").val(),
                                valor_retenido: $("#calculoRetencionI").val(),
                                id_retenciones_ser: xsid_iva,
                                tipo_ret: 'b'
                            };
                            su = jQuery("#listPagoreten").jqGrid('addRowData', parseFloat(calculoserviva).toFixed(2), datarow);
                            //                                    limpiar_campos();
                        } else {

                            datarow = {
                                base_imponible: parseFloat(calculoserviva).toFixed(2),
                                impuesto: impuesto,
                                porcent_reten: $("#porcent_iva").val(),
                                valor_retenido: $("#calculoRetencionI").val(),
                                id_retenciones_ser: xsid_iva,
                                tipo_ret: 'b'
                            };
                            su = jQuery("#listPagoreten").jqGrid('addRowData', parseFloat(calculoserviva).toFixed(2), datarow);
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
                var calculoservivaS = $("#calculoservivas").val();
                if ($("#calculoRetencionIs").val() == "0" || $("#calculoRetencionIs").val() == "0.000" || $("#calculoRetencionIs").val() == "0.00") {
                    alertify.error("Error..... Valor de Retencion debe ser distinto a 0");

                } else if ($("#calculoRetencionIs").val() != "0" || $("#calculoRetencionIs").val() != "0.000" || $("#calculoRetencionIs").val() == "0.00") {
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

function buscar_bienservicio_producto(fun_bsp) {

    $.ajax({
        type: "POST",
        url: "buscar_bienservicio_producto.php",
        data: "id=" + $("#comprobante").val(),
        success: function (data) {
            fun_bsp();
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

    var calculoRET = 0;
    var x = document.getElementById("tipoRetencionesF").selectedIndex;
    buscar_bienservicio_producto(() => {
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
    });
}
function buscar_servicio_producto(fun_sp) {

    $.ajax({
        type: "POST",
        url: "buscar_servicio_producto.php",
        data: "id=" + $("#comprobante").val(),
        success: function (data) {
            fun_sp();
            var valSUMs = data;
            $("#calculoserv").val(valSUMs);

        }
    });
}
function calculo_ret_fuenteS() {
    //    document.getElementById("tipoRetencionesF").selectedIndex = 0;
    $("#calculoRetencionF").val("0.000");

    document.getElementById("tipoRetencionesI").selectedIndex = 0;
    $("#calculoRetencionI").val("0.000");

    var calculoRET = 0;
    var x = document.getElementById("tipoRetencionesFS").selectedIndex;
    buscar_servicio_producto(() => {
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
    //    document.getElementById("tipoRetencionesF").selectedIndex = 0;
    $("#calculoRetencionF").val("0.000");

    document.getElementById("tipoRetencionesFS").selectedIndex = 0;
    $("#calculoRetencionFS").val("0.000");

    var calculoRET = 0;
    var x = document.getElementById("tipoRetencionesI").selectedIndex;
    buscar_bienservicio_producto_iva(() => {
        $.ajax({
            type: "POST",
            url: "../../procesos/buscar_ret_iva.php",
            data: "id=" + x,
            success: function (data) {
                var val = data;
                if (val != 0) {
                    calculoRET = val;
                    //                alertify.alert("El porcentaje de retención es del: " + calculoRET + "%");
                    var calculoserviva = $("#calculobieniva").val();
                    var valor = toFixedDown((((calculoserviva) * calculoRET) / 100), 3);
                    $("#calculoRetencionI").val(valor);
                    $("#porcent_iva").val(calculoRET);
                    $("#calculoRetencionI").focus();
                }
            }
        });
    });
}

function buscar_bienservicio_producto_iva(fun_iva) {

    $.ajax({
        type: "POST",
        url: "buscar_bienservicio_producto_iva.php",
        data: "id=" + $("#comprobante").val(),
        success: function (data) {
            fun_iva();
            var valSUM = data;
            $("#calculobieniva").val(valSUM);
        }
    });
}

function buscar_servicio_iva(fun) {

    console.log("fun2/");
    $.ajax({
        type: "POST",
        url: "buscar_ret_iva_servicio.php",
        data: "id=" + $("#comprobante").val(),
        success: function (data) {
            fun();
            var valSUMs = data;
            $("#calculoservivas").val(valSUMs);

        }
    });


}
function validar_acceso_reten() {
    if ($("#clave_reten").val() == "") {
        $("#clave_reten").focus();
        alertify.alert("Ingrese la clave");
    } else if ($("#observacion_reten").val() == "") {
        $("#observacion_reten").focus();
        alertify.alert("Ingrese la observación");
    } else {
        $.ajax({
            url: 'validar_acceso.php',
            type: 'POST',
            data: "clave=" + $("#clave_reten").val(),
            success: function (data) {
                var val = data;
                if (val == 0) {
                    $("#clave_reten").val("");
                    $("#clave_reten").focus();
                    alertify.alert("Error... La clave es incorrecta ingrese nuevamente");
                } else {
                    if (val == 1) {
                        $("#seguro_reten").dialog("open");
                    }
                }
            }
        });
    }
}
//////////retenciones///////////////
function cancelarEliminar_reten() {
    $("#seguro_reten").dialog("close");
    $("#clave_permiso_reten").dialog("close");
    $("#clave_reten").val("");
}

function calculo_ret_ivas() {
    //    document.getElementById("tipoRetencionesF").selectedIndex = 0;
    $("#calculoRetencionF").val("0.000");
    document.getElementById("tipoRetencionesFS").selectedIndex = 0;
    document.getElementById("tipoRetencionesI").selectedIndex = 0;
    $("#calculoRetencionFS").val("0.000");
    $("#calculoRetencionI").val("0.000");

    var calculoRET = 0;
    var x = document.getElementById("tipoRetencionesIs").selectedIndex;
    buscar_servicio_iva(() => {
        console.log("fun1/");
        $.ajax({
            type: "POST",
            url: "../../procesos/buscar_ret_iva.php",
            data: "id=" + x,
            success: function (data) {
                var val = data;
                if (val != 0) {

                    calculoRET = val;
                    var calculoservivas = $("#calculoservivas").val();
                    var valor = toFixedDown((((calculoservivas) * calculoRET) / 100), 3);
                    $("#calculoRetencionIs").val(valor);
                    $("#porcent_ivas").val(calculoRET);
                    $("#calculoRetencionIs").focus();
                }

            }
        });
    });

}
function guardar_retenciones_factura_compra() {

    if (document.getElementById('elegirretencionF1').checked == true) {
        if ($("#serie_sinretencion").val() == "") {
            $("#serie_sinretencion").focus();
            alertify.error("Debe Ingresar num sin Retencion");
        } else {
            alertify.confirm("¿Desea ingresar Formas de Pago sin Retencion?.....",
                function (e) {
                    if (e) {
                        var subtotal_adelanto1 = (parseFloat($("#tot").val()));
                        $("#valor_factura").val(subtotal_adelanto1.toFixed(2));
                        $("#valor_formas").val(subtotal_adelanto1.toFixed(2));
                        //$("#comprobante").val(val);
                        $('#otros_form').prop('selected', true);
                        $('.nav-tabs a[href="#tab_4"]').tab('show')
                        $("#formaspago_mixto").attr("disabled", false);
                    } else {
                        $('#contado_form').prop('selected', true);
                        guardar_asiento_contable();
                        //location.reload();
                    }
                }
            );
        }
    } else {
        var tam = jQuery("#listPagoreten").jqGrid("getRowData");
        if (tam.length == 0) {

            alertify.error("Error... Ingrese Retenciones");
        } else {
            if ($("#total_retencion").val() != "") {
                guardar_retenciones_factura_compra_g();
            }
            $('#pendiente_form').prop('selected', true);
        }
    }
}
function guardar_serie_p() {
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
                                    if ($("#fecha_emision").val() == '') {
                                        $("#fecha_emision").focus();
                                        alertify.error("Ingrese fecha de emisión");
                                        return;
                                    }
                                    if ($("#autorizacion").val() == "") {
                                        $("#autorizacion").focus();
                                        alertify.error("Ingrese la autorizaciòn");
                                    } else {
                                        if (tam.length == 0) {
                                            $("#codigo_barras").focus();
                                            alertify.error("Error... Ingrese productos a la factura");
                                        } else {
                                            if ($("#tot").val() > 500.000) {
                                                if ($("#observacionPago").val() == "") {
                                                    alertify.alert("Debe ingresar formas de Bancarización", function () {
                                                        //                                                        $('.nav-tabs a[href="#tab_3"]').tab('show')
                                                        //                                                        $("#tab_1").removeClass('active');
                                                        //                                                        $("#tab_3").addClass('active');
                                                        $("#formasPago").focus();
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
                                                    var v7 = new Array();
                                                    var v8 = new Array();
                                                    var v9 = new Array();
                                                    var v10 = new Array();
                                                    var v11 = new Array();
                                                    var v12 = new Array();
                                                    var v13 = new Array();
                                                    var v14 = new Array();

                                                    var string_v1 = "";
                                                    var string_v2 = "";
                                                    var string_v3 = "";
                                                    var string_v4 = "";
                                                    var string_v5 = "";
                                                    var string_v6 = "";
                                                    var string_v7 = "";
                                                    var string_v8 = "";
                                                    var string_v9 = "";
                                                    var string_v10 = "";
                                                    var string_v11 = "";
                                                    var string_v12 = "";
                                                    var string_v13 = "";
                                                    var string_v14 = "";

                                                    var fil = jQuery("#list").jqGrid("getRowData");
                                                    for (var i = 0; i < fil.length; i++) {
                                                        var datos = fil[i];
                                                        v1[i] = datos['cod_producto'];
                                                        v2[i] = datos['cantidad'];
                                                        v3[i] = datos['precio_u'];
                                                        v4[i] = datos['descuento'];
                                                        v5[i] = datos['total'];
                                                        v6[i] = datos['precio_v'];
                                                        v7[i] = datos['cantidad_unidad'];
                                                        v8[i] = datos['unidad_medida'];
                                                        v9[i] = datos['id_centro_costo'];
                                                        v10[i] = datos['id_plan'];
                                                        v11[i] = datos['tarifa'];
                                                        v12[i] = datos['valor_iva'];
                                                        v13[i] = datos['cod_impuesto'];
                                                        v14[i] = datos['cod_tarifa'];

                                                        string_v1 = string_v1 + "|" + v1[i];
                                                        string_v2 = string_v2 + "|" + v2[i];
                                                        string_v3 = string_v3 + "|" + v3[i];
                                                        string_v4 = string_v4 + "|" + v4[i];
                                                        string_v5 = string_v5 + "|" + v5[i];
                                                        string_v6 = string_v6 + "|" + v6[i];
                                                        string_v7 = string_v7 + "|" + v7[i];
                                                        string_v8 = string_v8 + "|" + v8[i];
                                                        string_v9 = string_v9 + "|" + v9[i];
                                                        string_v10 = string_v10 + "|" + v10[i];
                                                        string_v11 = string_v11 + "|" + v11[i];
                                                        string_v12 = string_v12 + "|" + v12[i];
                                                        string_v13 = string_v13 + "|" + v13[i];
                                                        string_v14 = string_v14 + "|" + v14[i];
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
                                                        campo6: string_v6, campo10: string_v10

                                                    };
                                                    $.ajax({
                                                        type: "POST",
                                                        url: "guardar_factura_compra.php",
                                                        data: "id_fac=" + $("#id_factura_compra").val() +
                                                            "&id_proveedor=" + $("#id_proveedor").val()
                                                            + "&comprobante=" + $("#comprobante").val()
                                                            + "&fecha_actual=" + $("#fecha_actual").val()
                                                            + "&hora_actual=" + $("#hora_actual").val()
                                                            + "&fecha_registro=" + $("#fecha_registro").val()
                                                            + "&fecha_emision=" + $("#fecha_emision").val()
                                                            + "&fecha_caducidad=" + $("#fecha_caducidad").val()
                                                            + "&tipo_comprobante=" + $("#tipo_comprobante").val()
                                                            + "&serie=" + seriee
                                                            + "&autorizacion=" + $("#autorizacion").val()
                                                            + "&cancelacion=" + $("#cancelacion").val()
                                                            + "&formas=" + forma_p
                                                            + "&tarifa0=" + $("#total_p").val()
                                                            + "&tarifa12=" + $("#total_p2").val()
                                                            + "&iva=" + $("#iva").val() + "&desc="
                                                            + $("#desc").val()
                                                            + "&tot=" + $("#tot").val()
                                                            + "&campo1=" + string_v1
                                                            + "&campo2=" + string_v2
                                                            + "&campo3=" + string_v3
                                                            + "&campo4=" + string_v4
                                                            + "&campo5=" + string_v5
                                                            + "&observaciones=" + observa
                                                            + "&pago_ats=" + pago_ats
                                                            + "&bien_servi=" + bien_ser
                                                            + "&campo6=" + string_v6
                                                            + "&campo7=" + string_v7
                                                            + "&campo8=" + string_v8
                                                            + "&ice=" + $("#icex").val()
                                                            + "&irbp=" + $("#irbpx").val()
                                                            + "&campo9=" + string_v9
                                                            + "&campo10=" + string_v10
                                                            + "&tarifas=" + string_v11
                                                            + "&vlores_iva=" + string_v12
                                                            + "&cods_impuesto=" + string_v13
                                                            + "&cods_tarifa=" + string_v14,
                                                        success: function (data) {
                                                            var val = data;
                                                            if (!Number.isNaN(Number(val))) {
                                                                if (Number(val) != 0) {
                                                                    $("#comprobante").val(Number(val));
                                                                }
                                                            }
                                                            if ($("#tipo_comprobante").val() != "" && $("#tipo_comprobante").val() != undefined) {
                                                                if (val != 0) {
                                                                    alertify.alert("Factura Guardada correctamente.");
                                                                    if (check_retenciones == 1) {
                                                                        alertify.confirm("¿Desea ingresar formas de pago?.",
                                                                            function (e) {
                                                                                if (e) {
                                                                                    var subtotal_adelanto1 = (parseFloat($("#tot").val()));
                                                                                    $("#valor_factura").val(subtotal_adelanto1.toFixed(2));
                                                                                    $("#valor_formas").val(subtotal_adelanto1.toFixed(2));
                                                                                    $("#valor_reten").val("1");

                                                                                    $('#otros_form').prop('selected', true);
                                                                                    $('.nav-tabs a[href="#tab_4"]').tab('show')
                                                                                    $("#formaspago_mixto").attr("disabled", false);
                                                                                } else {
                                                                                    alertify.confirm("¿Desea ingresar retenciones.? ",
                                                                                        function (e) {
                                                                                            if (e) {
                                                                                                $("#id_factura_compra").val(val);
                                                                                                $("#tipoRetencionesF").attr("disabled", false);
                                                                                                $('.nav-tabs a[href="#tab_2"]').tab('show');
                                                                                                $("#valor_reten").val("");
                                                                                            } else {
                                                                                                $('#pendiente_form').prop('selected', true);
                                                                                                window.open(formatoFC + "?hoja=A4&id=" + val, '_blank');
                                                                                                location.reload();
                                                                                            }
                                                                                        }
                                                                                    );
                                                                                }
                                                                            }
                                                                        );

                                                                    } else {

                                                                        alertify.confirm("¿Desea ingresar formas de pago?..",
                                                                            function (e) {
                                                                                if (e) {
                                                                                    var subtotal_adelanto1 = (parseFloat($("#tot").val()));
                                                                                    $("#valor_factura").val(subtotal_adelanto1.toFixed(2));
                                                                                    $("#valor_formas").val(subtotal_adelanto1.toFixed(2));
                                                                                    $("#valor_reten").val("1");
                                                                                    $('#otros_form').prop('selected', true);
                                                                                    $('.nav-tabs a[href="#tab_4"]').tab('show')
                                                                                    $("#formaspago_mixto").attr("disabled", false);

                                                                                } else {
                                                                                    $('#pendiente_form').prop('selected', true);
                                                                                    window.open(formatoFC + "?hoja=A4&id=" + val, '_blank');
                                                                                    location.reload();
                                                                                }

                                                                            }


                                                                        );

                                                                    }

                                                                }
                                                            } /* else {
                                                             if ($("#tipo_comprobante").val() == "NOTA") {
                                                             if (val != 0) {
                                                             alertify.alert("Nota Venta Guardada correctamente", function () {
                                                             location.reload();
                                                             });
                                                             }
                                                             }
                                                             }*/
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
                                                var v7 = new Array();
                                                var v8 = new Array();
                                                var v9 = new Array();
                                                var v10 = new Array();
                                                var v11 = new Array();
                                                var v12 = new Array();
                                                var v13 = new Array();
                                                var v14 = new Array();

                                                var string_v1 = "";
                                                var string_v2 = "";
                                                var string_v3 = "";
                                                var string_v4 = "";
                                                var string_v5 = "";
                                                var string_v6 = "";
                                                var string_v7 = "";
                                                var string_v9 = "";
                                                var string_v10 = "";
                                                var string_v11 = "";
                                                var string_v12 = "";
                                                var string_v13 = "";
                                                var string_v14 = "";

                                                var fil = jQuery("#list").jqGrid("getRowData");
                                                for (var i = 0; i < fil.length; i++) {
                                                    var datos = fil[i];
                                                    v1[i] = datos['cod_producto'];
                                                    v2[i] = datos['cantidad'];
                                                    v3[i] = datos['precio_u'];
                                                    v4[i] = datos['descuento'];
                                                    v5[i] = datos['total'];
                                                    v6[i] = datos['precio_v'];
                                                    v7[i] = datos['cantidad_unidad'];
                                                    v8[i] = datos['unidad_medida'];
                                                    v9[i] = datos['id_centro_costo'];
                                                    v10[i] = datos['id_plan'];
                                                    v11[i] = datos['tarifa'];
                                                    v12[i] = datos['valor_iva'];
                                                    v13[i] = datos['cod_impuesto'];
                                                    v14[i] = datos['cod_tarifa'];

                                                    string_v1 = string_v1 + "|" + v1[i];
                                                    string_v2 = string_v2 + "|" + v2[i];
                                                    string_v3 = string_v3 + "|" + v3[i];
                                                    string_v4 = string_v4 + "|" + v4[i];
                                                    string_v5 = string_v5 + "|" + v5[i];
                                                    string_v6 = string_v6 + "|" + v6[i];
                                                    string_v7 = string_v7 + "|" + v7[i];
                                                    string_v8 = string_v8 + "|" + v8[i];
                                                    string_v9 = string_v9 + "|" + v9[i];
                                                    string_v10 = string_v10 + "|" + v10[i];
                                                    string_v11 = string_v11 + "|" + v11[i];
                                                    string_v12 = string_v12 + "|" + v12[i];
                                                    string_v13 = string_v13 + "|" + v13[i];
                                                    string_v14 = string_v14 + "|" + v14[i];
                                                }
                                                var seriee = $("#serie").val();
                                                $.ajax({
                                                    type: "POST",
                                                    url: "guardar_factura_compra.php",
                                                    data: "id_fac=" + $("#id_factura_compra").val()
                                                        + "&id_proveedor=" + $("#id_proveedor").val()
                                                        + "&comprobante=" + $("#comprobante").val()
                                                        + "&fecha_actual=" + $("#fecha_actual").val()
                                                        + "&hora_actual=" + $("#hora_actual").val()
                                                        + "&fecha_registro=" + $("#fecha_registro").val()
                                                        + "&fecha_emision=" + $("#fecha_emision").val()
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
                                                        + "&observaciones=" + observa
                                                        + "&pago_ats=" + pago_ats
                                                        + "&campo6=" + string_v6
                                                        + "&campo7=" + string_v7
                                                        + "&campo8=" + string_v8
                                                        + "&ice=" + $("#icex").val()
                                                        + "&irbp=" + $("#irbpx").val()
                                                        + "&campo9=" + string_v9
                                                        + "&campo10=" + string_v10
                                                        + "&tarifas=" + string_v11
                                                        + "&vlores_iva=" + string_v12
                                                        + "&cods_impuesto=" + string_v13
                                                        + "&cods_tarifa=" + string_v14,
                                                    success: function (data) {
                                                        var val = data;
                                                        if (!Number.isNaN(Number(val))) {
                                                            if (Number(val) != 0) {
                                                                $("#comprobante").val(Number(val));
                                                            }
                                                        }
                                                        if ($("#tipo_comprobante").val() != "" && $("#tipo_comprobante").val() != undefined) {
                                                            if (val != 0) {
                                                                alertify.alert("Factura Guardada correctamente..");
                                                                if (check_retenciones == 1) {

                                                                    alertify.confirm("¿Desea ingresar formas de pago...?",
                                                                        function (e) {
                                                                            if (e) {
                                                                                var subtotal_adelanto1 = (parseFloat($("#tot").val()));
                                                                                $("#valor_factura").val(subtotal_adelanto1.toFixed(2));
                                                                                $("#valor_formas").val(subtotal_adelanto1.toFixed(2));
                                                                                $("#valor_reten").val("1");
                                                                                $('#otros_form').prop('selected', true);
                                                                                $('.nav-tabs a[href="#tab_4"]').tab('show')
                                                                                $("#formaspago_mixto").attr("disabled", false);

                                                                            } else {

                                                                                alertify.confirm("¿Desea ingresar retenciones..? ",
                                                                                    function (e) {
                                                                                        if (e) {
                                                                                            $("#id_factura_compra").val(val);
                                                                                            $("#tipoRetencionesF").attr("disabled", false);
                                                                                            $('.nav-tabs a[href="#tab_2"]').tab('show');
                                                                                            $("#valor_reten").val("");
                                                                                        } else {
                                                                                            $('#pendiente_form').prop('selected', true);
                                                                                            window.open(formatoFC + "?hoja=A4&id=" + val, '_blank');
                                                                                            location.reload();

                                                                                        }
                                                                                    }
                                                                                );
                                                                            }
                                                                        }
                                                                    );

                                                                } else {

                                                                    alertify.confirm("¿Desea ingresar formas de pago?....",
                                                                        function (e) {
                                                                            if (e) {
                                                                                var subtotal_adelanto1 = (parseFloat($("#tot").val()));
                                                                                $("#valor_factura").val(subtotal_adelanto1.toFixed(2));
                                                                                $("#valor_formas").val(subtotal_adelanto1.toFixed(2));
                                                                                $("#valor_reten").val("1");
                                                                                $('#otros_form').prop('selected', true);
                                                                                $('.nav-tabs a[href="#tab_4"]').tab('show')
                                                                                $("#formaspago_mixto").attr("disabled", false);
                                                                            } else {
                                                                                //CANCELAR
                                                                                $('#pendiente_form').prop('selected', true);

                                                                                window.open(formatoFC + "?hoja=A4&id=" + val, '_blank');
                                                                                location.reload();

                                                                            }

                                                                        }

                                                                    );

                                                                }

                                                            }
                                                        } /* else {
                                                         if ($("#tipo_comprobante").val() == "NOTA") {
                                                         if (val != 0) {
                                                         alertify.alert("Nota Venta Guardada correctamente", function () {
                                                         location.reload();
                                                         });
                                                         }
                                                         }
                                                         } */
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
                cargarFacturaDblclick(val);
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
                cargarFacturaDblclick(val);
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
        $("#cod_producto").val("").change();
        $("#codigo_barras").val("");
        $("#producto").val("");
        $("#cantidad").val("");
        $("#precio").val("");
        $("#precio_v").val("");
        $("#descuento").val("");
        $("#iva_producto").val("");
        $("#carga_series").val("");
        $("#incluye").val("");
        $("#stock").val("");
        $("#cantidad_unidad").val("");
        $("#unidad_medida").val("");
        $("#id_plan").val("");
        $("#unidad_medida").empty();
    }
}

function limpiar_campo3() {
    if ($("#producto").val() == "") {
        $("#cod_producto").val("").change();
        $("#codigo_barras").val("");
        $("#codigo").val("");
        $("#cantidad").val("");
        $("#precio").val("");
        $("#precio_v").val("");
        $("#descuento").val("");
        $("#iva_producto").val("");
        $("#carga_series").val("");
        $("#incluye").val("");
        $("#stock").val("");
        $("#cantidad_unidad").val("");
        $("#unidad_medida").val("");
        $("#id_plan").val("");
        $("#unidad_medida").empty();
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
var codImpuesto = 0;
var codTarifa = 0;
function actualizar_clave() {
    $.ajax({
        type: "POST",
        url: "guardar_ret_fuente_fact_compra.php",
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
                                if (check_retenciones == 1) {
                                    alertify.confirm("¿Desea ingresar retenciones...? ",
                                        function (e) {
                                            if (e) {
                                                guardar_asiento_contable();
                                                alertify.success(" Guardado Correctamente");
                                                $("#id_factura_compra").val(val);
                                                $("#cantidad_mixto").val() == "";
                                                $("#validar_guardar").val('1');
                                                $("#btnGuardarRetenciones_mixto").attr("disabled", true);
                                                $("#tipoRetencionesF").attr("disabled", false);
                                                $('.nav-tabs a[href="#tab_2"]').tab('show');
                                                $("#valor_reten").val("");
                                            } else {
                                                //                                                      
                                                guardar_asiento_contable();
                                                alertify.success(" Guardado Correctamente");

                                                $("#cantidad_mixto").val() == "";
                                                $("#validar_guardar").val('1');
                                                $("#btnGuardarRetenciones_mixto").attr("disabled", true);
                                                //                                                    location.reload();
                                            }
                                        }
                                    );

                                } else {

                                    guardar_asiento_contable();
                                    alertify.success(" Guardado Correctamente");

                                    $("#cantidad_mixto").val() == "";
                                    $("#validar_guardar").val('1');
                                    $("#btnGuardarRetenciones_mixto").attr("disabled", true);
                                    //                                    location.reload();

                                }
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
                        //                        alertify.error("Error... Ingrese la cantidad");
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
    var v6 = new Array();


    var string_v1 = "";
    var string_v2 = "";
    var string_v3 = "";
    var string_v4 = "";
    var string_v5 = "";
    var string_v6 = "";

    //alertify.alert("hola");
    var fil = jQuery("#list").jqGrid("getRowData");
    for (var i = 0; i < fil.length; i++) {
        var datos = fil[i];
        v1[i] = datos['cod_producto'];
        v2[i] = datos['cantidad'];
        v3[i] = datos['precio_u'];
        v4[i] = datos['descuento'];
        v5[i] = datos['total'];
        v6[i] = datos['id_plan'];
    }
    for (i = 0; i < fil.length; i++) {
        string_v1 = string_v1 + "|" + v1[i];
        string_v2 = string_v2 + "|" + v2[i];
        string_v3 = string_v3 + "|" + v3[i];
        string_v4 = string_v4 + "|" + v4[i];
        string_v5 = string_v5 + "|" + v5[i];
        string_v6 = string_v6 + "|" + v6[i];

    }
    var seriee = $("#serie").val();
    observa = "Ninguna";
    //    $("#btnGuardar").attr("disabled", true);
    $.ajax({
        type: "POST",
        url: "guardar_asiento_contable.php",
        data: "id_gastos=" + $("#comprobante").val()
            + "&num_factura=" + $("#serie").val()
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
            + "&observaciones=" + observa
            + "&pago_ats=" + pago_ats
            + "&bien_servi=" + bien_ser
            + "&idCuenta=" + $("#idCuenta").val()
            + "&formascc=" + $("#formas").val()
            + "&bien_servicio=" + $("#bien_servicio").val()
            + "&descripcion=" + $("#comentario").val()
            + "&campo6=" + string_v6,
        success: function (data) {
            var val = data;
            if (val != 0) {

                if (check_retenciones == 1) {

                    //                window.open(formatoFC + "?hoja=A4&id=" + val, '_blank');
                    //                    window.open("../../reportes/transacciones_2.php?hoja=A5&id=" + val, '_blank');
                    //                location.reload();
                    if (document.getElementById('elegirretencionF1').checked == true) {
                        guardar_retenciones_factura_compra_directo_c();
                    } else {

                        if ($("#total_retencion").val() != "") {
                            guardar_retenciones_factura_compra_g();
                        }


                    }





                } else {
                    window.open(formatoFC + "?hoja=A4&id=" + val, '_blank');
                    location.reload();
                }
            }
        }
    });

}

function guardar_retenciones_factura_compra_g() {

    console.log("entro1::");
    if ($("#idCuenta_reten").val() == "") {
        $("#formaspago_mixto_reten").focus();
        alertify.error("Error.. Debe seleccionar la forma de pago o la Cuenta contable");
    } else {
        if ($("#formaspago_mixto_reten").val() == "Transferencias_reten" && $("#idCuenta_reten").val() == "") {
            $("#cuenta_contable_reten").focus();
            alertify.error("Error.. Debe seleccionar Cuenta contable");
        } else {
            if ($("#formaspago_mixto_reten").val() == "Cheque_reten" && $("#idCuenta_reten").val() == "") {
                $("#cuenta_contable_reten").focus();
                alertify.error("Error.. Debe seleccionar Cuenta contable");
            } else {
                if ($("#formaspago_mixto_reten").val() == "TCredito_reten" && $("#idCuenta_reten").val() == "") {
                    $("#cuenta_contable_reten").focus();
                    alertify.error("Error.. Debe seleccionar Cuenta contable");
                } else {
                    if ($("#formaspago_mixto_reten").val() == "Contado_reten" && $("#idCuenta_reten").val() == "") {
                        $("#cuenta_contable_reten").focus();
                        alertify.error("Error.. Debe seleccionar Cuenta contable");
                    } else {
                        if ($("#formaspago_mixto_reten").val() == "cxc" && $("#idCuenta_reten").val() == "") {
                            $("#cuenta_contable_reten").focus();
                            alertify.error("Error.. Debe seleccionar Cuenta contable");
                        } else {
                            if ($("#formaspago_mixto_reten").val() == "cxp" && $("#idCuenta_reten").val() == "") {
                                $("#cuenta_contable_reten").focus();
                                alertify.error("Error.. Debe seleccionar Cuenta contable");
                            }



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
                                                if ($("#fecha_retencion").val() == "") {
                                                    $("#fecha_retencion").focus();
                                                    alertify.error("Seleccione una fecha");
                                                } else {
                                                    if (tam.length == 0) {

                                                        alertify.error("Error... Ingrese Retenciones");
                                                    } else {
                                                        if ($("#autorizacion_retencion").val() != "") {

                                                            var a = autocompletar($("#serie_retencion").val());
                                                            var seriee = num_serie_ret + "-" + a + "" + $("#serie_retencion").val();

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
                                                                data: "id_factura=" + $("#comprobante").val() + "&id_retencion_fuente=" + x + "&fecha_actual=" + $("#fecha_actual").val() + "&valor_factura=" + $("#sub").val() + "&iva_factura=" + $("#iva").val() + "&valor_retencion=" + $("#calculoRetencionF").val() + "&autorizacion_ret=" + $("#autorizacion_retencion").val() + "&serie_retencion=" + seriee + "&porcent_reten=" + $("#porcent_reten").val() + "&id_retencion_iva=" + y + "&valor_facturaiva=" + $("#tot").val() + "&valor_retencioni=" + $("#calculoRetencionI").val() + "&valor_seleccion_iva=" + xx + "&porcent_iva=" + $("#porcent_iva").val() + "&id_retencion_fuentes=" + xs + "&valor_retencions=" + $("#calculoRetencionFS").val() + "&porcent_retens=" + $("#porcent_retens").val() + "&valor_seleccion_si_no=" + xxs + "&campo1reten=" + string_v1 + "&campo2reten=" + string_v2 + "&campo3reten=" + string_v3 + "&campo4reten=" + string_v4 + "&campo5reten=" + string_v5 + "&campo6reten=" + string_v6 + "&campo7reten=" + string_v7 + "&total_reten_iva=" + $("#total_retencion").val() + "&formascc=" + $("#formas").val() + "&fecha_retencion=" + $("#fecha_retencion").val(),
                                                                dataType: "json",
                                                                success: function (data) {
                                                                    var val = data;

                                                                    window.open(formatoRC + "?hoja=A4&id=" + data.id, '_blank');

                                                                    if (data.estado == 2) {
                                                                        //                                                                        $("#guardado_reten").val("1");
                                                                        alertify.confirm("AUTORIZADO¿Desea Imprimir Comprobante?",
                                                                            function (e) {
                                                                                $("#btnGuardarRetenciones").attr("disabled", false);
                                                                                if (e) {
                                                                                    reenviar(data.id);
                                                                                    window.open("../../reportes/factura_compra.php?hoja=A4&id=" + data.id, '_blank');
                                                                                    location.reload();
                                                                                } else {
                                                                                    reenviar(data.id);
                                                                                    location.reload();
                                                                                }
                                                                            });


                                                                    } else {
                                                                        $("#guardado_reten").val("2");
                                                                        alertify.error("Error....Retencion no Autorizado");
                                                                    }

                                                                    if (data.estado == 7) {
                                                                        alertify.alert("Factura Guardada  No Autorizada", function () {
                                                                            location.reload();
                                                                        });
                                                                    } else {

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
        data: { term: term }
    });
}
function eliminar_retencion() {
    if ($("#id_comprobante_serie").val() == "") {
        alertify.error("Seleccione una factura");
        $("#buscar_facturas_compras").dialog("open");
    } else {
        $("#clave_permiso_reten").dialog("open");
    }
}
function totalMayor() {
    console.log("entro funcion" + $("#totx").val());

    if (parseFloat($("#totx").val()) >= 500.000) {
        if ($("#observacionPago").val() == "") {
            console.log(">500");
            $("#bancarizacion").show();
        }
    } else {
        $("#bancarizacion").hide();
        $("#detalle_pago").val("");
        $("#observacionPago").val("");
    }
}

async function cargarFacturaDblclick(id, contabilizar = false) {
    if (id) {
        var valor = id;
        // agregar factura compra
        $("#comprobante").val(valor);
        $("#btnGuardar").attr("disabled", true);
        //            $("#btnGuardarTemporal").attr("disabled", true);

        $("#ruc_ci").attr("disabled", "disabled");
        //        $("#formas").val("Contado");
        $("#serie").attr("disabled", "disabled");
        $("#list").jqGrid("clearGridData", true);
        $("#listPagoreten").jqGrid("clearGridData", true);
        /*  $("#total_p").val("0.000");
         $("#total_p2").val("0.000"); */
        $("#iva").val("0.000");
        $("#desc").val("0.000");
        $("#tot").val("0.000");
        /*  $("#total_px").val("0.000");
         $("#total_p2x").val("0.000"); */
        $("#ivax").val("0.000");
        $("#descx").val("0.000");
        $("#totx").val("0.000");
        if (contabilizar) {
            $('#otros_form').prop('selected', true);
        }

        await $.getJSON('retornar_factura_compra2.php?com=' + valor, function (data) {
            var tama = data.length;
            var descuento = 0;
            var total = 0;
            var su = 0;
            var precio = 0;
            var multi = 0;
            var flotante = 0;
            var resultado = 0;
            if (tama != 0) {
                for (var i = 0; i < tama; i = i + 16) {
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
                        //precio_v: data[i + 9],

                        cantidad_unidad: data[i + 9],
                        unidad_medida: data[i + 10],
                        valor_iva: data[i + 12],
                        tarifa: data[i + 11],
                        cod_impuesto: data[i + 13],
                        cod_tarifa: data[i + 14],

                    };
                    var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                }
            }
            calcularTotalesTablaProductos();
        });
        await $.getJSON('retornar_factura_compra.php?com=' + valor, function (data) {
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
                    $("#fecha_retencion").val(data[i + 11]);
                    $("#fecha_caducidad").val(data[i + 12]);
                    $("#serie").val(data[i + 13]);
                    $("#autorizacion").val(data[i + 14]);
                    $("#cancelacion").val(data[i + 15]);
                    if (!contabilizar) {
                        $("#formas").val(data[i + 16]);
                    }

                    $("#iva").val(data[i + 19]);
                    $("#desc").val(data[i + 20]);
                    $("#tot").val(data[i + 21]);

                    $("#ivax").val(parseFloat(data[i + 19]).toFixed(4));
                    $("#descx").val(parseFloat(data[i + 20]).toFixed(4));
                    $("#totx").val(parseFloat(data[i + 21]).toFixed(2));
                    $("#estado h3").remove();


                    /* $("#total_p").val(data[i + 17]);
                    $("#total_p2").val(data[i + 18]);
                    $("#sub").val((parseFloat(data[i + 17]) + parseFloat(data[i + 18])));
                    $("#total_px").val(parseFloat(data[i + 17]).toFixed(4));
                    $("#total_p2x").val(parseFloat(data[i + 18]).toFixed(4));
                    $("#subx").val((parseFloat(data[i + 17]) + parseFloat(data[i + 18])).toFixed(4)); */

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
                if (contabilizar) {
                    retornar_retar_tot_reten();
                } else {
                    volver_rf();
                    volver_ri();
                }

            }
        });
        await $.getJSON('retornar_ice.php?com=' + valor, function (data) {
            var tama = data.length;
            if (tama != 0) {
                for (var i = 0; i < tama; i = i + 2) {
                    $("#icex").val(data[i]);
                    $("#irbpx").val(data[i + 1]);
                }
            }
        });
        await $.getJSON('retornar_retenciones_grid.php?com=' + valor, function (data) {
            var tama = data.length;

            if (tama != 0) {
                var res_total_retencio = 0;
                console.log("boton inactivo");
                //$("#btnGuardarRetenciones").attr("disabled", true);
                for (var i = 0; i < tama; i = i + 8) {
                    if (data[i + 7] == "Pasivo") {
                       /*  $("#estado_reten").append($("<h3>").text("Anulada"));
                        $("#estado_reten h3").css("color", "red");
                        $("#listPagoreten").jqGrid("clearGridData", true); */
                    } else {
                        $("#btnGuardarRetenciones").attr("disabled", true);
                        if (data[i + 7] == "Activo" || data[i + 7] == "") {
                            $("#estado_reten h3").remove();
                            var datarow = {
                                base_imponible: data[i],
                                impuesto: data[i + 1],
                                porcent_reten: data[i + 2],
                                valor_retenido: data[i + 3]
                            };
                            var su = jQuery("#listPagoreten").jqGrid('addRowData', data[i], datarow);
                            $("#comprobante_serie").val(data[i + 6]);
                            $("#id_comprobante_serie").val(data[i + 6]);
                            var num = data[i + 5];
                            var res = num.substr(8, 20)
                            $("#serie_retencion").val(res);
                            res_total_retencio = parseFloat(res_total_retencio) + parseFloat(data[i + 3]);
                            $("#total_retencion_oculto").val(res_total_retencio.toFixed(2));
                            retornar_retar_tot_reten();
                        }
                    }
                }

            } else {
                actualizarReten();
                console.log("boton activo");
                $("#btnGuardarRetenciones").attr("disabled", false);
            }
        });
        await $.getJSON('retornar_formas_mixto_grid.php?com=' + valor, function (data) {
            $("#listPagoreten_mixto").jqGrid("clearGridData", true);
            var tama = data.length;
            if (tama != 0) {
                if (contabilizar) {
                    $("#btnGuardarRetenciones_mixto").attr("disabled", true);
                    $("#formaspago_mixto").attr("disabled", false);
                }
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
            } else if (contabilizar) {
                $("#btnGuardarRetenciones_mixto").attr("disabled", false);
                $("#formaspago_mixto").attr("disabled", false);
            }
        });
        if (contabilizar) {
            $("#buscar_facturas_compras_conta").dialog("close");
        } else {
            $("#buscar_facturas_compras").dialog("close");
            $("#buscar_estados").dialog("close");
        }


    } else {
        alertify.alert("Seleccione una Factura");
    }
}
//function retornar_retar_tot_reten() {
//    var total_reten_ocul = parseFloat($("#total_retencion_oculto").val());
//    if (total_reten_ocul != "") {
//        var subtotal_adelanto1 = parseFloat($("#totx").val()) - parseFloat(total_reten_ocul);
//        $("#valor_factura").val(subtotal_adelanto1.toFixed(2));
//        console.log("top1/" + $("#totx").val());
//    }
//    var subtotal_adelanto1 = parseFloat($("#totx").val());
//    $("#valor_factura").val(subtotal_adelanto1.toFixed(2));
//    console.log("top2/" + $("#totx").val());
//}



function actualizarReten() {
    console.log("se actualizo la retencion");
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
}

function cargar_cuentas_reten() {
    var id = $("#formaspago_mixto_reten").val();
    if ($("#formaspago_mixto_reten").val() == "cxp") {
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
function abrirCuenta_reten() {
    $("#cuentas_reten").dialog("open");
}
function retornar_retar_tot_reten() {

    var total_reten_ocul = parseFloat($("#total_retencion_oculto").val());

    if (total_reten_ocul != "") {


        var subtotal_adelanto1 = parseFloat($("#totx").val()) - parseFloat(total_reten_ocul);
        $("#valor_factura").val(subtotal_adelanto1.toFixed(2));
        console.log("top1/" + $("#totx").val());
    }


    var subtotal_adelanto1 = parseFloat($("#totx").val());
    $("#valor_factura").val(subtotal_adelanto1.toFixed(2));
    $("#valor_formas").val(subtotal_adelanto1.toFixed(2));
    console.log("top2/" + $("#totx").val());
}

function inicio() {
    /* $("#cod_producto").change(function (e) {
        buscarIva($(this).val());
    }); */

    $("#buscar_facturas_compras_conta").dialog(dialogo22);
    $("#btnBuscar_pendientes").click(function () {
        $("#buscar_facturas_compras_conta").dialog("open");
    });
    $("#cuentas_reten").dialog(dialogo_cuenta);
    $("#btnCuenta_reten").on("click", abrirCuenta_reten);
    $("#formaspago_mixto_reten").on("change", function () {
        cargar_cuentas_reten();
    });
    $("#fecha_emision").change(function () {
        $("#fecha_retencion").val($("#fecha_emision").val());
    });
    $("#bancarizacion").hide();
    initCambiarPvp();
    iniDialogValoresNotasC();
    llenarCentrosCosto();

    $("#tipo_comprobante").change(function (e) {
        limpiarTablaCompra();
        if ($(this).val() == 'NOTA') {
            $("#tipo_iva").val("1");
            $("#tipo_iva").trigger("change");
            $("#tipo_iva")[0].disabled = true;
        } else {
            $("#tipo_iva").trigger("change");
            $("#tipo_iva")[0].disabled = false;
            $("#tipo_iva")[0][0].selected = true
        }
    });

    $("#unidad_medida").change(() => {
        if ($("#cod_producto").val() !== "") {
            let cod_producto = $("#cod_producto").val();
            let unidad_medida = $("#unidad_medida").val();
            let precio = "MINORISTA";
            $.getJSON(
                "search_um.php?cod_producto=" +
                cod_producto +
                "&unidad_medida=" +
                unidad_medida +
                "&precio=" +
                precio,
                (data) => {

                    let cantidadu = data[1];
                    let precioc = data[3];
                    let preciovmin = data[4];

                    if (cantidadu == "") {
                        cantidadu = 1;

                    } else {
                        cantidadu = data[1];
                    }
                    console.log(cantidadu + "cantidadu");
                    console.log(precioc + "precioc");

                    $("#precio_v").val(preciovmin);
                    $("#cantidad_unidad").val(data[1]);
                    $("#precio").val(cantidadu * precioc);


                }
            );
            $("#cantidad").focus();
        }
    });

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
            $("#valor_formas").val("");
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
        } else if ($("#formaspago_mixto").val() == "NOTA_CREDITO") {
            cargar_cuentas();
            $("#cuenta_contable").attr("disabled", false);
            $("#btnCuenta").attr("disabled", false);
            $("#cuenta_contable").val("");
            $("#idCuenta").val("");
            $('#fecha_vencimiento').hide();
            $("#buscar_val_nc").dialog("open");
        }
    })
    listaPagoRetencion();
    $("#btnAgregar_mixto").click(function (e) {
        e.preventDefault();
        agregar1();
    });
    //    buscar_servicio_producto();
    //    buscar_bienservicio_producto();
    //    buscar_servicio_iva();
    //    buscar_bienservicio_producto_iva();
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

    $("[data-mask]").inputmask();
    alertify.set({ delay: 6000 });
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
                    window.open("../../reportes/factura_compra.php?hoja=A4&id=" + $("#comprobante").val(), '_blank');
                } else {
                    //                      window.open("../../reportes/factura_compra.php?hoja=A4&id=" + $("#comprobante").val(), '_blank');
                    window.open(formatoFC + "?hoja=A4&id=" + $("#comprobante").val(), '_blank');
                    //                    window.open("../../reportes/factura_compra.php?hoja=A4&id=" + $("#comprobante").val(), '_blank');
                    //                    window.open("../../reportes/transacciones_2.php?hoja=A5&id=" + $("#comprobante").val(), '_blank');
                }

                //                location.reload();
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
        //        window.open("../../reportes/retenciones.php?hoja=A4&id=" + $("#comprobante").val(), '_blank');
        window.open(formatoRC + "?hoja=A4&id=" + $("#comprobante").val(), '_blank');

    });
    $("#btnAcceder").click(function (e) {
        e.preventDefault();
    });
    $("#btnAnularRetenciones").on("click", eliminar_retencion);
    $("#btnActualizarClave").click(function (e) {
        e.preventDefault();
    });
    $("#buscar_estados").dialog(dialogo10);
    $("#buscar_retenciones").dialog(dialogo10);
    $("#btncargar").on("click", abrirDialogo);
    //    $("#btnAgregar").on("click", agregar);
    //$("#btnAnadirForma").on("click", agregarForma);
    $("#btnGuardarSeries").on("click", guardar_serie_p);
    $("#btnCancelarSeries").on("click", cancelar);
    $("#btnGuardar").on("click", guardar_factura);
    //    $("#btnGuardarTemporal").on("click", guardar_factura_temporal);
    $("#btnModificar").on("click", modificar_factura);
    $("#btnEliminar").on("click", eliminar_factura);
    $("#btnNuevo").on("click", limpiar_factura);
    $("#btnAtras").on("click", flecha_atras);
    $("#btnAdelante").on("click", flecha_siguiente);
    $("#btnActualizarClave").on("click", actualizar_clave);
    $("#btnAceptar").on("click", aceptarEliminar);
    $("#btnAceptar_reten").on("click", aceptarEliminar_reten);

    $("#btnSalir").on("click", cancelarEliminar);
    $("#btnAcceder").on("click", validar_acceso);

    $("#btnSalir_reten").on("click", cancelarEliminar_reten);
    $("#btnAcceder_reten").on("click", validar_acceso_reten);
    $("#btnContabilizar").on("click", contabilizar);
    $("#btnGuardarRetenciones").on("click", guardar_retenciones_factura_compra);
    $("#btnCancelarRetenciones").on("click", function (e) {
        location.reload();
    });
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
    $("#clave_permiso_reten").dialog(dialogo3);
    $("#seguro").dialog(dialogo4);
    $("#seguro_reten").dialog(dialogo4);
    $("#btnBuscar").click(function () {
        $("#buscar_facturas_compras").dialog("open");
    });

    $("#cantidad").validCampoFranz("0123456789");
    $("#autorizacion").validCampoFranz("0123456789");
    $("#descuento").validCampoFranz("0123456789.");

    $("#pvp_minorista").validCampoFranz("0123456789.");
    $("#pvp_mayorista").validCampoFranz("0123456789.");
    $("#pvp_negocio").validCampoFranz("0123456789.");
    $("#util_minorista").validCampoFranz("0123456789.");
    $("#util_mayorista").validCampoFranz("0123456789.");
    $("#util_negocio").validCampoFranz("0123456789.");

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

            //$("#tipo_comprobante").val("NOTA");
            $("#tipo_comprobante").trigger("change");
            //$("#tipo_comprobante").attr("disabled", "disabled");
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

                        if (!!infofac) {
                            return false;
                        }

                        $("#ruc_ci").val(ui.item.value);
                        $("#empresa").val(ui.item.empresa);
                        $("#correo").val(ui.item.correo);
                        $("#id_proveedor").val(ui.item.id_proveedor);
                        return false;
                    },
                    select: function (event, ui) {
                        if (!!infofac) {
                            if (ui.item.value !== infofac.ruc) {
                                alertify.alert("<b>El proveedor seleccionado no coincide con el proveedor de la factura cargada.</b>");
                                $("#ruc_ci").val("");
                                $("#empresa").val("");
                                $("#correo").val("");
                                $("#id_proveedor").val("");
                                return false;
                            }
                        }

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

                /* $("#tipo_comprobante").val("FACTURA");*/
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
                for (var i = 0; i < tama; i = i + 10) {
                    $("#codigo").val(data[i]);
                    $("#producto").val(data[i + 1]);
                    $("#precio").val(data[i + 2]);
                    $("#iva_producto").val(data[i + 3]);
                    if ($("#tipo_comprobante").val() == 'NOTA') {
                        $("#tipo_iva").val("1");
                    } else {
                        $("#tipo_iva").val(data[i + 3]);
                    }
                    $("#carga_series").val(data[i + 4]);
                    $("#cod_producto").val(data[i + 5]).change();
                    $("#incluye").val(data[i + 6]);
                    $("#precio_v").val(data[i + 7]);
                    $("#stock").val(data[i + 8]);
                    $("#cantidad").focus();
                    $("#id_plan").val(data[i + 9]);
                    abrirDialogo_unidad();
                }
            } else {
                $("#codigo").val("");
                $("#producto").val("");
                $("#precio").val("");
                $("#iva_producto").val("");
                $("#carga_series").val("");
                $("#cod_producto").val("").change();
                $("#incluye").val("");
                $("#cantidad").val("");
                alertify.error("Producto no ingresado");
                $("#codigo_barras").val("");
                $("#precio_v").val("");
                if ($("#tipo_comprobante").val() == 'NOTA') {
                    $("#tipo_iva").val("1");
                } else {
                    $("#tipo_iva")[0][0].selected = true
                }

                $("#stock").val("");
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
            if ($("#tipo_comprobante").val() == 'NOTA') {
                $("#tipo_iva").val("1");
            } else {
                $("#tipo_iva").val(ui.item.iva_producto);
            }
            $("#carga_series").val(ui.item.carga_series);
            $("#cod_producto").val(ui.item.cod_producto).change();
            $("#incluye").val(ui.item.incluye);
            $("#precio_v").val(ui.item.iva_minorista);
            $("#id_plan").val(ui.item.id_plan);
            //         $("#punto_venta_inv").val(ui.item.punto_venta);
            abrirDialogo_unidad();
            return false;
        },
        select: function (event, ui) {
            $("#codigo_barras").val(ui.item.codigo_barras);
            $("#codigo").val(ui.item.value);
            $("#producto").val(ui.item.producto);
            $("#precio").val(ui.item.precio);
            $("#iva_producto").val(ui.item.iva_producto);
            $("#carga_series").val(ui.item.carga_series);
            $("#cod_producto").val(ui.item.cod_producto).change();
            $("#incluye").val(ui.item.incluye);
            $("#precio_v").val(ui.item.iva_minorista);
            $("#id_plan").val(ui.item.id_plan);
            if ($("#tipo_comprobante").val() == 'NOTA') {
                $("#tipo_iva").val("1");
            } else {
                $("#tipo_iva").val(ui.item.iva_producto);
            }
            //         $("#punto_venta_inv").val(ui.item.punto_venta);
            abrirDialogo_unidad();
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
            $("#cod_producto").val(ui.item.cod_producto).change();
            $("#incluye").val(ui.item.incluye);
            $("#precio_v").val(ui.item.iva_minorista);
            $("#id_plan").val(ui.item.id_plan);
            if ($("#tipo_comprobante").val() == 'NOTA') {
                $("#tipo_iva").val("1");
            } else {
                $("#tipo_iva").val(ui.item.iva_producto);
            }
            $("#stock").val(ui.item.stock);
            //         $("#punto_venta_inv").val(ui.item.punto_venta);
            abrirDialogo_unidad();
            return false;
        },
        select: function (event, ui) {
            $("#codigo_barras").val(ui.item.codigo_barras);
            $("#producto").val(ui.item.value);
            $("#codigo").val(ui.item.codigo);
            $("#precio").val(ui.item.precio);
            $("#iva_producto").val(ui.item.iva_producto);
            $("#carga_series").val(ui.item.carga_series);
            $("#cod_producto").val(ui.item.cod_producto).change();
            $("#incluye").val(ui.item.incluye);
            $("#precio_v").val(ui.item.iva_minorista);
            $("#id_plan").val(ui.item.id_plan);
            if ($("#tipo_comprobante").val() == 'NOTA') {
                $("#tipo_iva").val("1");
            } else {
                $("#tipo_iva").val(ui.item.iva_producto);
            }
            $("#stock").val(ui.item.stock);
            //         $("#punto_venta_inv").val(ui.item.punto_venta);
            abrirDialogo_unidad();
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
    $("#fecha_emision")[0].max = $("#fecha_actual").val();
    /*  $("#fecha_emision").datepicker({
     dateFormat: 'yy-mm-dd'
     }).datepicker('setDate', 'today'); */
    $("#fecha_caducidad").datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');
    $("#cancelacion").datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');

    // datos tabla
    jQuery("#list").jqGrid({
        datatype: "local",
        colNames: [
            '',
            'ID',
            'Còdigo',
            'Detalle',
            'Cantidad',
            'Precio. Ux',
            'Descuentox',
            'Calculadox',
            'Totalx',
            'Precio. U',
            'Descuento',
            'Calculado',
            'Total',
            'Iva',
            'Incluye',
            'Precio V.',
            'C. Unidad',
            'U. Medida',
            'C. Costo',
            'id_c_costo',
            'Id Plan',
            "",
            'tarifa',
            'valor_iva',
            'cod_impuesto',
            'cod_tarifa'],
        colModel: [
            { name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: { keys: false, delbutton: true, editbutton: false } },
            { name: 'cod_producto', index: 'cod_producto', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'left', frozen: true, width: 50 },
            { name: 'codigo', index: 'codigo', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'left', frozen: true, width: 100 },
            { name: 'detalle', index: 'detalle', editable: false, frozen: true, editrules: { required: true }, align: 'left', width: 290 },
            {
                name: 'cantidad', index: 'cantidad', editable: true, frozen: true, editrules: { required: true }, align: 'right', width: 70, editoptions: {
                    maxlength: 10, size: 15, dataInit: function (elem) {
                        $(elem).bind("keypress", function (e) {
                            return numeros(e)
                        })
                    }
                }
            },
            {
                name: 'precio_u', index: 'precio_u', hidden: true, editable: false, search: false, frozen: true, editrules: { required: true }, align: 'right', width: 110, editoptions: {
                    maxlength: 10, size: 15, dataInit: function (elem) {
                        $(elem).bind("keypress", function (e) {
                            return punto(e)
                        })
                    }
                }
            },
            { name: 'descuento', index: 'descuento', hidden: true, editable: false, frozen: true, editrules: { required: true }, align: 'right', width: 70 },
            { name: 'cal_des', index: 'cal_des', hidden: true, editable: false, hidden: true, frozen: true, editrules: { required: true }, align: 'right', width: 90 },
            { name: 'total', index: 'total', hidden: true, editable: false, search: false, frozen: true, editrules: { required: true }, align: 'right', width: 110 },
            {
                name: 'precio_ux', index: 'precio_ux', editable: true, search: false, frozen: true, editrules: { required: true }, align: 'right', width: 110, editoptions: {
                    maxlength: 10, size: 15, dataInit: function (elem) {
                        $(elem).bind("keypress", function (e) {
                            return punto(e)
                        })
                    }
                }
            },
            { name: 'descuentox', index: 'descuentox', editable: false, frozen: true, editrules: { required: true }, align: 'right', width: 70 },
            { name: 'cal_desx', index: 'cal_desx', editable: false, hidden: true, frozen: true, editrules: { required: true }, align: 'right', width: 90 },
            { name: 'totalx', index: 'totalx', editable: false, search: false, frozen: true, editrules: { required: true }, align: 'right', width: 110 },
            { name: 'iva', index: 'iva', align: 'center', width: 100, hidden: true },
            { name: 'incluye', index: 'incluye', editable: false, hidden: true, frozen: true, editrules: { required: true }, align: 'right', width: 90 },
            { name: 'precio_v', index: 'precio_v', editable: false, hidden: false, width: 100, align: 'right' },
            {
                name: "cantidad_unidad",
                index: "cantidad_unidad",
                editable: false,
                hidden: false,
                frozen: true,
                editrules: {
                    required: true,
                },
                align: "right",
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
                name: "centro_costo", index: "centro_costo", search: false, frozen: true
            },
            {
                name: "id_centro_costo", index: "id_centro_costo", search: false, frozen: true, hidden: true
            },
            { name: 'id_plan', index: 'id_plan', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            {
                name: "mdf_producto",
                index: "mdf_producto",
                formatter: function (cellvalue, options, rowObject) {
                    let rowid = options.rowId;
                    return `<button class="btn btn-default" type="button" id="btn_cb_pvp_${rowid}"><i class="fa fa-pencil" aria-hidden="true"></i> Cambiar PVP</button>`;
                }
            },
            { name: "tarifa", index: "tarifa", hidden: true, },
            { name: "valor_iva", index: "valor_iva", hidden: true, },
            { name: "cod_impuesto", index: "cod_impuesto", hidden: true, },
            { name: "cod_tarifa", index: "cod_tarifa", hidden: true, },
        ],
        rowNum: 30,
        height: 200,
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
                var su = jQuery("#list").jqGrid('delRowData', rowid);
                if (su == true) {
                    calcularTotalesTablaProductos();
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
            var result = 0;
            var iva1 = 0;
            var iva_pventa = 0;
            var subtotal_total = 0;
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
                    jQuery("#list").jqGrid('setRowData', rowid, { total: total, cal_des: resultado });
                    jQuery("#list").jqGrid("setRowData", rowid, {
                        totalx: numFormatter(2).format(total),
                        total: Number(total),
                        precio_u: numFormatter(2).format(ret.precio_ux),
                        cal_des: resultado,
                        valor_iva: calcularIva(Number(total)),
                    });
                } else {
                    desc = descuento_grid;
                    precio = parseFloat(precio_grid);
                    multi = parseFloat(val) * parseFloat(precio);
                    descuento = ((multi * parseFloat(desc)) / 100);
                    flotante = parseFloat(descuento);
                    resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                    total = parseFloat(multi);
                    jQuery("#list").jqGrid('setRowData', rowid, { total: total });
                    jQuery("#list").jqGrid("setRowData", rowid, {
                        totalx: numFormatter(2).format(total),
                        total: Number(total),
                        precio_u: numFormatter(2).format(ret.precio_ux),
                        valor_iva: calcularIva(Number(total)),
                    });
                }
                calcularTotalesTablaProductos();

            }

            if (name == 'precio_ux') {
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
                        total: Number(total),
                        precio_u: numFormatter(2).format(ret.precio_ux),
                        cal_des: resultado,
                        valor_iva: calcularIva(Number(total)),
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
                        total: Number(total),
                        precio_u: numFormatter(2).format(ret.precio_ux),
                        valor_iva: calcularIva(Number(total)),
                    });
                }
                calcularTotalesTablaProductos();

            }
        },
        afterInsertRow: function (rowid, rowdata, rowelem) {
            obtenerPvpProducto(rowdata.cod_producto)
                .then(el => {
                    let pc = Number(el.precio_compra).toFixed(8);
                    let pu = Number(rowdata.precio_u).toFixed(8);
                    if (Number(pc) != Number(pu)) {
                        $(`#btn_cb_pvp_${rowid}`)[0].classList.remove("btn-default");
                        $(`#btn_cb_pvp_${rowid}`)[0].classList.add("btn-danger");
                    }
                });

            $(`#btn_cb_pvp_${rowid}`).click(function () {
                $("#precio_compra_factura_modi").val("");
                $("#dialog_cambiar_pvp_producto").dialog("open");
                obtenerPvpProducto(rowdata.cod_producto)
                    .then(el => {
                        llenarDatosProducto(
                            el.precio_compra,
                            el.iva_minorista,
                            el.iva_mayorista,
                            el.iva_negocio,
                            el.utilidad_minorista,
                            el.utilidad_mayorista,
                            el.utilidad_negocio,
                            rowdata.cod_producto,
                            rowdata.detalle + " (Cod. " + rowdata.codigo + ")",
                            rowdata.precio_u,
                            rowdata.unidad_medida.trim()
                        );
                    })
            });
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
    jQuery("#list33").jqGrid({
        url: 'xmlBuscarFacturaCompra_sinc.php',
        datatype: 'xml',
        colNames: ['COMPROBANTE', 'IDENTIFICACIÒN', 'EMPRESA', 'FACTURA NRO.', 'MONTO TOTAL', 'FECHA EMISIÓN'],
        colModel: [
            { name: 'id_factura_compra', index: 'id_factura_compra', editable: false, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },
            { name: 'identificacion_pro', index: 'identificacion_pro', editable: false, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 90 },
            { name: 'empresa_pro', index: 'empresa_pro', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'left', frozen: true, width: 260 },
            { name: 'num_serie', index: 'num_serie', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 130 },
            { name: 'total_compra', index: 'total_compra', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 80 },
            { name: 'fecha_compra', index: 'fecha_compra', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
        ],
        rowNum: 30,
        width: 850,
        height: 220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager33'),
        sortname: 'id_factura_compra',
        sortorder: 'desc',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list33").jqGrid('getGridParam', 'selrow');
            jQuery('#list33').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list33").jqGrid('getRowData', id);
                var valor = ret.id_factura_compra;

            }
            cargarFacturaDblclick(valor, true);
        }
    }).jqGrid('navGrid', '#pager33',
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
    jQuery("#list33").jqGrid('navButtonAdd', '#pager33', {
        caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#list33").jqGrid('getGridParam', 'selrow');
            jQuery('#list33').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list33").jqGrid('getRowData', id);
                var valor = ret.id_factura_compra;

            }
            cargarFacturaDblclick(valor, true);
        }
    });

    // buscador facturas compra 
    jQuery("#list3").jqGrid({
        url: 'xmlBuscarFacturaCompra.php',
        datatype: 'xml',
        colNames: ['COMPROBANTE', 'IDENTIFICACIÒN', 'EMPRESA', 'FACTURA NRO.', 'MONTO TOTAL', 'FECHA EMISIÓN'],
        colModel: [
            { name: 'id_factura_compra', index: 'id_factura_compra', editable: false, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },
            { name: 'identificacion_pro', index: 'identificacion_pro', editable: false, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 150 },
            { name: 'empresa_pro', index: 'empresa_pro', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'left', frozen: true, width: 200 },
            { name: 'num_serie', index: 'num_serie', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 200 },
            { name: 'total_compra', index: 'total_compra', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'fecha_compra', index: 'fecha_compra', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
        ],
        rowNum: 30,
        width: 850,
        height: 220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager3'),
        sortname: 'id_factura_compra',
        sortorder: 'desc',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list3").jqGrid('getGridParam', 'selrow');
            jQuery('#list3').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list3").jqGrid('getRowData', id);
                var valor = ret.id_factura_compra;
            }

            cargarFacturaDblclick(valor);
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
            }
            cargarFacturaDblclick(valor);
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
                formoptions: { elmsuffix: " (*)" },
                editrules: { required: true },
            },
            {
                name: "ccontable",
                index: "ccontable",
                editable: true,
                align: "left",
                width: "490",
                search: true,
                frozen: true,
                formoptions: { elmsuffix: " (*)" },
                editrules: { required: true },
            },
            {
                name: "cuenta",
                index: "cuenta",
                editable: true,
                align: "left",
                width: "120",
                search: true,
                frozen: true,
                formoptions: { elmsuffix: " (*)" },
                editrules: { required: true },
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

    //    ////////////////////buscador proformas tecnico/////////////////////////
    jQuery("#list77").jqGrid({
        url: 'xmlBuscarRetenciones.php',
        datatype: 'xml',

        colNames: ['ID', 'NUM FACTURA', 'NUM SERIE RETEN.', 'FECHA', 'PROVEEDOR', 'N° AUTORIZACIÒN', 'MONTO', 'ESTADO FAC.', 'ESTADO'],
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
        { name: 'num_factura', index: 'num_factura', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
        { name: 'num_serie', index: 'num_serie', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
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
            width: 60
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
            align: 'left',
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
            name: 'estado_fac',
            index: 'estado_fac',
            editable: true,
            search: false,
            hidden: false,
            editrules: {
                edithidden: false
            },
            align: 'left',
            frozen: true,
            width: 80
        },
        {
            name: 'estado',
            index: 'estado',
            editable: true,
            search: false,
            hidden: false,
            formatter: function (cellvalue, options, rowObject) {
                if (cellvalue == 'Pasivo') {
                    return '<div style="background-color: red; color: white">ANULADO<div>';
                }
                return '<div style="background-color: green; color: white">ACTIVO<div>';
            },
            editrules: {
                edithidden: false
            },
            align: 'left',
            frozen: true,
            width: 80
        },
        ],
        rowNum: 30,
        width: 1300,
        height: 220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager77'),
        sortname: 'id_retencion_fuente_factura_compra',
        sortorder: 'desc',
        viewrecords: true,
        gridComplete: function () {




        },
        ondblClickRow: function () {
            var id = jQuery("#list77").jqGrid('getGridParam', 'selrow');
            jQuery('#list77').jqGrid('restoreRow', id);
        },
    }).jqGrid('navGrid', '#pager77', {
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
    jQuery("#list77").jqGrid('navButtonAdd', '#pager7', {
        caption: "Reeviar",
        onClickButton: function () {
            var id = jQuery("#list77").jqGrid('getGridParam', 'selrow');
            jQuery('#list77').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list77").jqGrid('getRowData', id);
            }
        }
    });

    ////////////////////buscador proformas tecnico/////////////////////////
    jQuery("#list7").jqGrid({
        url: 'xmlBuscarEstadosRetencion.php',
        datatype: 'xml',
        colNames: ['ID', 'NUM FACTURA', 'NUM SERIE RETEN.', 'FECHA', 'PROVEEDOR', 'N° AUTORIZACIÒN', 'MONTO', 'ESTADO', 'ACCIÒN', 'ENVIO XML', 'CONSULTA COMPROBANTE', 'ID F.C'],
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
        { name: 'num_factura', index: 'num_factura', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
        { name: 'num_serie', index: 'num_serie', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },

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
        {
            name: 'id_factura_compra',
            index: 'id_factura_compra',
            editable: false,
            hidden: true,
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
            var valor = null;
            if (id) {
                var ret = jQuery("#list7").jqGrid("getRowData", id);
                valor = ret.id_factura_compra;
            }
            cargarFacturaDblclick(valor);
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

    //    jQuery("#list7").jqGrid('navButtonAdd', '#pager7', {
    //        caption: "Reeviar",
    //        onClickButton: function () {
    //            var id = jQuery("#list7").jqGrid('getGridParam', 'selrow');
    //            jQuery('#list7').jqGrid('restoreRow', id);
    //            if (id) {
    //                var ret = jQuery("#list7").jqGrid('getRowData', id);
    //            }
    //        }
    //    });

    jQuery(window).bind('resize', function () {
        jQuery("#list").setGridWidth(jQuery('#grid_container').width(), true);
    }).trigger('resize');

    inputmaskDecimal('#cantidad', true, 2);
    inputmaskDecimal('#precio', true, 4);
    inputmaskDecimal('#precio_v', true, 4);

    obtenerParametrosEmpresa();
    obtenerNumSerieRet();
    
    $("#formaspago_mixto_reten").val("cxp").change();
}
//compras

function abrirDialogo_unidad() {
    var cod = $("#cod_producto").val();
    $("#unidad_medida").empty();
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
                name: 'num_nota', index: 'num_nota', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'left',
                frozen: true, width: 150
            },
            { name: 'fecha_actual', index: 'fecha_actual', editable: false, frozen: true, hidden: false, editrules: { required: true }, align: 'left', width: 150 },
            { name: 'valor', index: 'valor', editable: true, frozen: true, hidden: false, editrules: { required: true }, align: 'left', width: 100 },
        ],
        rowNum: 10,
        width: 600,
        rowList: [10, 20, 30],
        pager: jQuery('#pager22'),
        shrinkToFit: true,
        sortorder: 'asc',
        caption: 'Lista de Cobros Pendientes',
        viewrecords: true,
        multiselect: true,
        onSelectRow: function (rowid, status, e) {
            let find = valoresNotaCredito.find(el => el.id_formas_pago_mixto_nc == rowid);
            find.status = status;
        },
        onSelectAll: function (aRowids, status) {
            aRowids.forEach(el => {
                let find = valoresNotaCredito.find(f => f.id_formas_pago_mixto_nc == el);
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

function obtenerValoresNcClientes(idproveedor) {
    return $.ajax({
        url: "valores_nc_proveedor.php",
        method: "GET",
        data: {
            id_proveedor: idproveedor
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
        if (!!$("#id_proveedor").val()) {
            let valoresnc = await obtenerValoresNcClientes($("#id_proveedor").val());
            valoresNotaCredito = valoresnc.map(el => {
                el.status = false;
                return el;
            });
            valoresnc.forEach((el) => {
                jQuery("#list22").jqGrid('addRowData', el.id_formas_pago_mixto_nc, el);
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
            num_documento: el.id_formas_pago_mixto_nc, //$("#num_tarjeta").val(),
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

function limpiarTablaCompra() {
    jQuery("#list").jqGrid("clearGridData");
    jQuery("#list").trigger("reloadGrid");
    //calcularTotales();
    calcularTotalesTablaProductos();
}
//git francis 18092023
//cambiar pvp producto

function calcularIva(valor) {
    return valor * (Number(calculoIVA) / 100)
}

function calcularTotalesTablaProductos() {
    let rows = $("#list").jqGrid("getRowData");

    let subtotal = 0;
    let total = 0;
    let totaliva = 0;
    let descuentoprods = 0;
    let valiva = {};
    rows.forEach(el => {
        subtotal += Number(el.total)
        total += Number(el.total) + Number(el.valor_iva);
        descuentoprods += Number(el.cal_des);
        totaliva += Number(el.valor_iva);

        if (!valiva[el.tarifa]) {
            valiva[el.tarifa] = { iva: 0, subtotal: 0 };
        }
        valiva[el.tarifa].iva += Number(el.valor_iva);
        valiva[el.tarifa].subtotal += Number(el.total);
    });

    let ctarifas = document.querySelectorAll('[id^="el_tarifa_"]');
    ctarifas = Array.from(ctarifas);
    ctarifas.forEach(el => {
        $(el).remove();
    });

    for (key in valiva) {
        let values = valiva[key];

        if (key != "") {
            $("#div_totales_tarifas").prepend(`<div class="form-group col-md-6" id="el_tarifa_${key}">
        <label>Tarifa ${key}%:</label>
        <input type="text" name="total_px_${key}" id="total_px_${key}" value="${values.subtotal.toFixed(2)}" readonly class="form-control" />
        <input type="hidden" name="total_p_${key}" id="total_p_${key}" value="${values.subtotal}" readonly class="form-control" />
        </div>`);
        }

    }

    $("#tot").val(total);
    $("#totx").val(total.toFixed(2));
    $("#sub").val(subtotal);
    $("#subx").val(subtotal.toFixed(2));
    $("#iva").val(totaliva);
    $("#ivax").val(totaliva.toFixed(2));
    $("#descx").val(descuentoprods.toFixed(2));
    $("#desc").val(descuentoprods.toFixed(2));

    totalMayor();
}


