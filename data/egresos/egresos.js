$(document).on("ready", inicio);
function evento(e) {
    e.preventDefault();
}

function openPDF() {
    window.open('../../ayudas/ayuda.pdf');
}

var dialogo2 = {
    autoOpen: false,
    resizable: false,
    width: 800,
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
    if (hours === 0)
        hours = 12;
    if (minutes <= 9)
        minutes = "0" + minutes;
    if (seconds <= 9)
        seconds = "0" + seconds;
    $("#hora_actual").val(hours + ":" + minutes + ":" + seconds + " " + dn);
    setTimeout("show()", 1000);
}

function ValidNum(e) {
    if (e.keyCode < 48 || e.keyCode > 57) {
        e.returnValue = false;
    }
    return true;
}

function enter(event) {
    if (event.which === 13 || event.keyCode === 13) {
        entrar();
        return false;
    }
    return true;
}

function enter2(e) {
    if (e.which === 13 || e.keyCode === 13) {
        comprobar();
        return false;
    }
    return true;
}

function enter3(e) {
    if (e.which === 13 || e.keyCode === 13) {
        comprobar2();
        return false;
    }
    return true;
}

function entrar() {
    if ($("#cod_producto").val() == "") {
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
                $("#precio").focus();
            }
        }
    }
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
function comprobar() {
    if ($("#cod_producto").val() == "") {
        $("#codigo").focus();
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
                    if ($("#precio").val() == "") {
                        $("#precio").focus();
                        alertify.error("Ingrese un precio");
                    } else {
                        $("#p_venta").focus();
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
    $("#p_venta").val("");
    $("#descuento").val("");
    $("#iva_producto").val("");
    $("#incluye").val("");
    $("#disponibles").val("");
    $("#stock").val(0);
    $("#cantidad_unidad").val("");
    $("#unidad_medida").val("");
}

function comprobar2() {
    var subtotal0 = 0;
    var subtotal12 = 0;
    var iva12 = 0;
    var total_total = 0;
    var descu_total = 0;
    var cantidad_unidad = 0;
    var unidad_medida = "";
    if ($("#cod_producto").val() == "") {
        $("#codigo").focus();
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
                    if ($("#precio").val() == "") {
                        $("#precio").focus();
                        alertify.alert("Ingrese un precio");
                    } else {
                        if ($("#p_venta").val() == "") {
                            $("#p_venta").focus();
                            alertify.error("Ingrese un precio");
                        } else {
                            if ($("#disponibles").val() == "") {
                                parseInt($("#disponibles").val(0));
                            }
                            if (parseInt($("#cantidad").val()) > parseInt($("#disponibles").val())) {
                                $("#cantidad").focus();
                                alertify.error("Error.. Fuera de Stock, cantidad disponible: " + $("#disponibles").val());
                            } else {
                                if (validarStock($("#cod_producto").val(), "cantidad")) {
                                    var filas = jQuery("#list").jqGrid("getRowData");
                                    var descuento = 0;
                                    var total = 0;
                                    var su = 0;
                                    var precio = 0;
                                    var precio_venta = 0;
                                    var multi = 0;
                                    var flotante = 0;
                                    var resultado = 0;
                                    var repe = 0;
                                    var suma = 0;
                                    if (filas.length == 0) {
                                        if ($("#descuento").val() !== "") {
                                            desc = $("#descuento").val();
                                            precio = (parseFloat($("#precio").val())).toFixed(3);
                                            multi = (parseFloat($("#cantidad").val()) * parseFloat($("#precio").val())).toFixed(3);
                                            descuento = ((multi * parseFloat(desc)) / 100);
                                            flotante = parseFloat(descuento);
                                            resultado = (Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2)).toFixed(3);
                                            total = (multi - resultado).toFixed(3);
                                            precio_venta = parseFloat($("#p_venta").val()).toFixed(3);
                                        } else {
                                            desc = 0;
                                            precio = (parseFloat($("#precio").val())).toFixed(3);
                                            multi = (parseFloat($("#cantidad").val()) * parseFloat($("#precio").val())).toFixed(3);
                                            descuento = ((multi * parseFloat(desc)) / 100);
                                            flotante = parseFloat(descuento);
                                            resultado = (Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2)).toFixed(3);
                                            total = (parseFloat($("#cantidad").val()) * precio).toFixed(3);
                                            precio_venta = parseFloat($("#p_venta").val()).toFixed(3);
                                        }
                                        if ($("#cantidad_unidad").val() != "") {
                                            cantidad_unidad = parseFloat($("#cantidad_unidad").val()) * parseFloat($("#cantidad").val());
                                            unidad_medida = $("#unidad_medida")[0].selectedOptions[0].text;
                                            unidad_medida = unidad_medida.split("--");
                                            unidad_medida = unidad_medida[0];
                                        } else {
                                            cantidad_unidad = 0;
                                            unidad_medida = '';
                                        }
                                        console.log("ROW1");
                                        var datarow = {
                                            cod_producto: $("#cod_producto").val(),
                                            codigo: $("#codigo").val(),
                                            detalle: $("#producto").val(),
                                            cantidad: $("#cantidad").val(),
                                            precio_u: precio,
                                            descuento: desc,
                                            cal_des: resultado,
                                            total: total,
                                            precio_v: precio_venta,
                                            iva: $("#iva_producto").val(),
                                            incluye: $("#incluye").val(),
                                            cantidad_unidad: cantidad_unidad,
                                            unidad_medida: unidad_medida,
                                        };
                                        su = jQuery("#list").jqGrid('addRowData', $("#cod_producto").val(), datarow);
                                        limpiar_campos();
                                    } else {
                                        var repe = 0;
                                        for (var i = 0; i < filas.length; i++) {
                                            var id = filas[i];
                                            if (id['cod_producto'] == $("#cod_producto").val()) {
                                                repe = 1;
                                                var can = id['cantidad'];
                                            }
                                        }

                                        if (repe == 1) {
                                            suma = parseInt(can) + parseInt($("#cantidad").val());
                                            if (suma > parseInt($("#disponibles").val())) {
                                                $("#cantidad").focus();
                                                alertify.error("Error.. Fuera de Stock, cantidad disponible: " + $("#disponibles").val());
                                            } else {
                                                if ($("#descuento").val() !== "") {
                                                    desc = $("#descuento").val();
                                                    precio = (parseFloat($("#precio").val())).toFixed(3);
                                                    multi = (parseFloat(suma) * parseFloat($("#precio").val())).toFixed(3);
                                                    descuento = ((multi * parseFloat(desc)) / 100);
                                                    flotante = parseFloat(descuento);
                                                    resultado = (Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2)).toFixed(3);
                                                    total = (multi - resultado).toFixed(3);
                                                    precio_venta = parseFloat($("#p_venta").val()).toFixed(3);
                                                } else {
                                                    desc = 0;
                                                    precio = (parseFloat($("#precio").val())).toFixed(3);
                                                    multi = (parseFloat($("#cantidad").val()) * parseFloat($("#precio").val())).toFixed(3);
                                                    descuento = ((multi * parseFloat(desc)) / 100);
                                                    flotante = parseFloat(descuento);
                                                    resultado = (Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2)).toFixed(3);
                                                    total = (parseFloat(suma) * precio).toFixed(3);
                                                    precio_venta = parseFloat($("#p_venta").val()).toFixed(3);
                                                }
                                                if ($("#cantidad_unidad").val() != "") {
                                                    cantidad_unidad = parseFloat($("#cantidad_unidad").val()) * parseFloat($("#cantidad").val());
                                                    unidad_medida = $("#unidad_medida")[0].selectedOptions[0].text;
                                                    unidad_medida = unidad_medida.split("--");
                                                    unidad_medida = unidad_medida[0];
                                                } else {
                                                    cantidad_unidad = 0;
                                                    unidad_medida = '';
                                                }
                                                console.log("ROW2");
                                                var datarow = {
                                                    cod_producto: $("#cod_producto").val(),
                                                    codigo: $("#codigo").val(),
                                                    detalle: $("#producto").val(),
                                                    cantidad: suma,
                                                    precio_u: precio,
                                                    descuento: desc,
                                                    cal_des: resultado,
                                                    total: total,
                                                    precio_v: precio_venta,
                                                    iva: $("#iva_producto").val(),
                                                    incluye: $("#incluye").val(),
                                                    cantidad_unidad: cantidad_unidad,
                                                    unidad_medida: unidad_medida,
                                                };
                                                su = jQuery("#list").jqGrid('setRowData', $("#cod_producto").val(), datarow);
                                                limpiar_campos();
                                            }
                                        } else {
                                            if ($("#descuento").val() !== "") {
                                                desc = $("#descuento").val();
                                                precio = (parseFloat($("#precio").val())).toFixed(3);
                                                multi = (parseFloat($("#cantidad").val()) * parseFloat($("#precio").val())).toFixed(3);
                                                descuento = ((multi * parseFloat(desc)) / 100);
                                                flotante = parseFloat(descuento);
                                                resultado = (Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2)).toFixed(3);
                                                total = (multi - resultado).toFixed(3);
                                                precio_venta = parseFloat($("#p_venta").val()).toFixed(3);
                                            } else {
                                                desc = 0;
                                                precio = (parseFloat($("#precio").val())).toFixed(3);
                                                multi = (parseFloat($("#cantidad").val()) * parseFloat($("#precio").val())).toFixed(3);
                                                descuento = ((multi * parseFloat(desc)) / 100);
                                                flotante = parseFloat(descuento);
                                                resultado = (Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2)).toFixed(3);
                                                total = (parseFloat($("#cantidad").val()) * precio).toFixed(3);
                                                precio_venta = parseFloat($("#p_venta").val()).toFixed(3);
                                            }
                                            if ($("#cantidad_unidad").val() != "") {
                                                cantidad_unidad = parseFloat($("#cantidad_unidad").val()) * parseFloat($("#cantidad").val());
                                                unidad_medida = $("#unidad_medida")[0].selectedOptions[0].text;
                                                unidad_medida = unidad_medida.split("--");
                                                unidad_medida = unidad_medida[0];
                                            } else {
                                                cantidad_unidad = 0;
                                                unidad_medida = '';

                                            }
                                            console.log("ROW3");
                                            var datarow = {
                                                cod_producto: $("#cod_producto").val(),
                                                codigo: $("#codigo").val(),
                                                detalle: $("#producto").val(),
                                                cantidad: $("#cantidad").val(),
                                                precio_u: precio,
                                                descuento: desc,
                                                cal_des: resultado,
                                                total: total,
                                                precio_v: precio_venta,
                                                iva: $("#iva_producto").val(),
                                                incluye: $("#incluye").val(),
                                                cantidad_unidad: cantidad_unidad,
                                                unidad_medida: unidad_medida,
                                            };
                                            su = jQuery("#list").jqGrid('addRowData', $("#cod_producto").val(), datarow);
                                            limpiar_campos();
                                        }
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
                                        if (dd['iva'] === "Si") {
                                            console.log("nivel1");
                                            if (dd['incluye'] == "No") {
                                                subtotal = dd['total'];
                                                sub1 = subtotal;
                                                iva1 = (sub1 * 0.12).toFixed(3);
                                                subtotal0 = parseFloat(subtotal0) + 0;
                                                subtotal12 = parseFloat(subtotal12) + parseFloat(sub1);
                                                descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);
                                                iva12 = parseFloat(iva12) + parseFloat(iva1);
                                                subtotal0 = parseFloat(subtotal0).toFixed(3);
                                                subtotal12 = parseFloat(subtotal12).toFixed(3);
                                                iva12 = parseFloat(iva12).toFixed(3);
                                                descu_total = parseFloat(descu_total).toFixed(3);
                                            } else {
                                                if (dd['incluye'] == "Si") {

                                                    subtotal = dd['total'];
                                                    sub2 = (subtotal / 1.12).toFixed(3);
                                                    iva2 = (sub2 * 0.12).toFixed(3);
                                                    subtotal0 = parseFloat(subtotal0) + 0;
                                                    subtotal12 = parseFloat(subtotal12) + parseFloat(sub2);
                                                    iva12 = parseFloat(iva12) + parseFloat(iva2);
                                                    descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);
                                                    subtotal0 = parseFloat(subtotal0).toFixed(3);
                                                    subtotal12 = parseFloat(subtotal12).toFixed(3);
                                                    iva12 = parseFloat(iva12).toFixed(3);
                                                    descu_total = parseFloat(descu_total).toFixed(3);
                                                }
                                            }
                                        } else {
                                            if (dd['iva'] === "No") {
                                                console.log("nivel1");
                                                subtotal = dd['total'];
                                                sub = subtotal;
                                                subtotal0 = parseFloat(subtotal0) + parseFloat(sub);
                                                subtotal12 = parseFloat(subtotal12) + 0;
                                                iva12 = parseFloat(iva12) + 0;
                                                descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);
                                                subtotal0 = parseFloat(subtotal0).toFixed(2);
                                                subtotal12 = parseFloat(subtotal12).toFixed(2);
                                                iva12 = parseFloat(iva12).toFixed(3);
                                                descu_total = parseFloat(descu_total).toFixed(3);
                                            }
                                        }
                                    }
                                    total_total = parseFloat(total_total) + (parseFloat(subtotal0) + parseFloat(subtotal12));
                                    total_total = parseFloat(total_total).toFixed(3);
                                    $("#total_p").val(subtotal0);
                                    $("#total_p2").val(subtotal12);
                                    $("#iva").val(iva12);
                                    $("#desc").val(descu_total);
                                    $("#tot").val(total_total);
                                    $("#codigo_barras").focus();
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

function guardar_egreso() {
    var tam = jQuery("#list").jqGrid("getRowData");
    if (tam.length === 0) {
        $("#codigo_barras").focus();
        alertify.error("Error... Ingrese productos");
    } else {
        if ($('#slTransacciones').val() == 2) {
            if ($('#origen').val() != 0 && $('#destino').val() != 0) {
                GuardarEgresos();
            } else {
                if ($('#origen').val() == 0) {
                    $('#origen').focus();
                } else {
                    if ($('#destino').val() == 0) {
                        $('#destino').focus();
                    }
                }
                alertify.alert("Seleccione el origen y destino");
            }
        } else {
            GuardarEgresos();
        }
    }
}

function GuardarEgresos() {
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
    var fil = jQuery("#list").jqGrid("getRowData");
    for (var i = 0; i < fil.length; i++) {
        var datos = fil[i];
        v1[i] = datos['cod_producto'];
        v2[i] = datos['cantidad'];
        v3[i] = datos['precio_u'];
        v4[i] = datos['descuento'];
        v5[i] = datos['total'];
        v6[i] = datos["cantidad_unidad"];
        v7[i] = datos["unidad_medida"];

        string_v1 = string_v1 + "|" + v1[i];
        string_v2 = string_v2 + "|" + v2[i];
        string_v3 = string_v3 + "|" + v3[i];
        string_v4 = string_v4 + "|" + v4[i];
        string_v5 = string_v5 + "|" + v5[i];
        string_v6 = string_v6 + "|" + v6[i];
        string_v7 = string_v7 + "|" + v7[i];
    }

    var datos = {
        comprobante: $("#comprobante").val(),
        fecha_actual: $("#fecha_actual").val(),
        hora_actual: $("#hora_actual").val(),
        origen: $("#origen").val(), destino: $("#destino").val(),
        observaciones: $("#observaciones").val(),
        tarifa0: $("#total_p").val(), tarifa12: $("#total_p2").val(),
        iva: $("#iva").val(), desc: $("#desc").val(), tot: $("#tot").val(),
        campo1: string_v1, campo2: string_v2, campo3: string_v3, campo4: string_v4, campo5: string_v5, campo6: string_v6, campo7: string_v7
    };

    $.ajax({
        type: "POST",
        url: "guardar_egresos.php",
        data: datos,
        success: function (data) {
            var val = data;
            if (!Number.isNaN(parseFloat(val))) {
                window.open("../../reportes/reporteEgreso.php?hoja=A4&comprobante=" + val, '_blank');
                alertify.alert("Egreso Guardado Correctamente", function () {
                                        location.reload();
                });
            } else {
                alertify.error("Hubo un problema al guardar el egreso");
            }
        }
    });
}

function flecha_atras() {
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "egresos" + "&id_tabla=" + "id_egresos" + "&tipo=" + 1,
        success: function (data) {
            //var val = data;
            //if (val != "") {
            if (data != "") {
                $("#comprobante").val(data);
                var valor = data;
                // agregregar egresos
                //$("#comprobante").val(valor);
                $("#btnGuardar").attr("disabled", true);
                $("#btnModificar").attr("disabled", true);
                $("#list").jqGrid("clearGridData", true);
                $("#total_p").val("0.000");
                $("#total_p2").val("0.000");
                $("#iva").val("0.000");
                $("#tot").val("0.000");
                $.getJSON('retornar_egreso.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 12) {
                            $("#fecha_actual").val(data[i]);
                            $("#hora_actual").val(data[i + 1]);
                            $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                            $("#origen").val(data[i + 4]);
                            $("#destino").val(data[i + 5]);
                            $("#observaciones").val(data[i + 6]);
                            $("#total_p").val(data[i + 7]);
                            $("#total_p2").val(data[i + 8]);
                            $("#iva").val(data[i + 9]);
                            $("#desc").val(data[i + 10]);
                            $("#tot").val(data[i + 11]);
                        }
                    }
                });
                $.getJSON('retornar_egresos2.php?com=' + valor, function (data) {
                    var tama = data.length;
                    var descuento = 0;
                    var total = 0;
                    var su = 0;
                    var precio = 0;
                    var multi = 0;
                    var flotante = 0;
                    var resultado = 0;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 12) {
                            desc = data[i + 5];
                            precio = (parseFloat(data[i + 4])).toFixed(3);
                            multi = (parseFloat(data[i + 3]) * parseFloat(data[i + 4])).toFixed(3);
                            descuento = ((multi * parseFloat(desc)) / 100).toFixed(3);
                            flotante = parseFloat(descuento);
                            resultado = (Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2)).toFixed(3);
                            total = (multi - resultado).toFixed(3);
                            precio_venta = parseFloat(data[i + 7]).toFixed(3);
                            var datarow = {
                                cod_producto: data[i],
                                codigo: data[i + 1],
                                detalle: data[i + 2],
                                cantidad: data[i + 3],
                                precio_u: precio,
                                descuento: desc,
                                cal_des: resultado,
                                total: data[i + 6],
                                precio_v: precio_venta,
                                iva: data[i + 8],
                                incluye: data[i + 9],
                                cantidad_unidad: data[i + 10],
                                unidad_medida: data[i + 11],
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });
                habilitarAnulacion();
            } else {
                alertify.alert("No hay más registros posteriores!!");
                //$('#comprobante').val("");
                habilitarAnulacion();
            }
        }
    });
}

function flecha_siguiente() {
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "egresos" + "&id_tabla=" + "id_egresos" + "&tipo=" + 2,
        success: function (data) {
            //var val = data;
            //if (val != "") {
            if (data != "") {
                $("#comprobante").val(data);
                var valor = data;
                // agregregar egresos
                //$("#comprobante").val(valor);
                $("#btnGuardar").attr("disabled", true);
                $("#btnModificar").attr("disabled", true);
                $("#list").jqGrid("clearGridData", true);
                $("#total_p").val("0.000");
                $("#total_p2").val("0.000");
                $("#iva").val("0.000");
                $("#tot").val("0.000");
                $.getJSON('retornar_egreso.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 12) {
                            $("#fecha_actual").val(data[i]);
                            $("#hora_actual").val(data[i + 1]);
                            $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                            $("#origen").val(data[i + 4]);
                            $("#destino").val(data[i + 5]);
                            $("#observaciones").val(data[i + 6]);
                            $("#total_p").val(data[i + 7]);
                            $("#total_p2").val(data[i + 8]);
                            $("#iva").val(data[i + 9]);
                            $("#desc").val(data[i + 10]);
                            $("#tot").val(data[i + 11]);
                        }
                    }
                });
                $.getJSON('retornar_egresos2.php?com=' + valor, function (data) {
                    var tama = data.length;
                    var descuento = 0;
                    var total = 0;
                    var su = 0;
                    var precio = 0;
                    var multi = 0;
                    var flotante = 0;
                    var resultado = 0;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 10) {
                            desc = data[i + 5];
                            precio = (parseFloat(data[i + 4])).toFixed(3);
                            multi = (parseFloat(data[i + 3]) * parseFloat(data[i + 4])).toFixed(3);
                            descuento = ((multi * parseFloat(desc)) / 100).toFixed(3);
                            flotante = parseFloat(descuento);
                            resultado = (Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2)).toFixed(3);
                            total = (multi - resultado).toFixed(3);
                            precio_venta = parseFloat(data[i + 7]).toFixed(3);
                            var datarow = {
                                cod_producto: data[i],
                                codigo: data[i + 1],
                                detalle: data[i + 2],
                                cantidad: data[i + 3],
                                precio_u: precio,
                                descuento: desc,
                                cal_des: resultado,
                                total: data[i + 6],
                                precio_v: precio_venta,
                                iva: data[i + 8],
                                incluye: data[i + 9]
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });
                habilitarAnulacion();
            } else {
                alertify.alert("No hay más registros superiores!!");
                //$('#comprobante').val("");
                habilitarAnulacion();
            }
        }
    });

}

function limpiar_ingreso() {
    location.reload();
}

function limpiar_campo1() {
    if ($("#codigo").val() == "") {
        $("#cod_producto").val("");
        $("#codigo_barras").val("");
        $("#producto").val("");
        $("#cantidad").val("");
        $("#precio").val("");
        $("#p_venta").val("");
        $("#descuento").val("");
        $("#iva_producto").val("");
        $("#incluye").val("");
        $("#disponibles").val("");
    }
}

function limpiar_campo2() {
    if ($("#producto").val() == "") {
        $("#cod_producto").val("");
        $("#codigo_barras").val("");
        $("#codigo").val("");
        $("#cantidad").val("");
        $("#precio").val("");
        $("#p_venta").val("");
        $("#descuento").val("");
        $("#iva_producto").val("");
        $("#incluye").val("");
        $("#disponibles").val("");
    }
}

function punto(e) {
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
}

function inicio() {
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
                    $("#precio").val(data[2]);


                    $("#cantidad_unidad").val(data[1]);
                }
            );
            $("#cantidad").focus();
        }
    });
    alertify.set({ delay: 1000 });
    // para hora
    show();
    // 
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
    // botones
    $("#btnGuardar").click(function (e) {
        e.preventDefault();
    });
    $("#btnModificar").click(function (e) {
        e.preventDefault();
    });
    $("#btnNuevo").click(function (e) {
        e.preventDefault();
    });
    $("#btnImprimir").click(function (e) {
        $.ajax({
            type: "POST",
            url: "../../procesos/validacion.php",
            data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "egresos" + "&id_tabla=" + "id_egresos" + "&tipo=" + 1,
            success: function (data) {
                var val = data;
                if (val != "") {
                    window.open("../../reportes/reporteEgreso.php?hoja=A4&comprobante=" + $("#comprobante").val(), '_blank');
                } else {
                    alertify.alert("Ingreso no creado!!");
                }
            }
        });
    });
    $("#btnAtras").click(function (e) {
        e.preventDefault();
    });
    $("#btnAdelante").click(function (e) {
        e.preventDefault();
    });
    // 

    $("#btnGuardar").on("click", guardar_egreso);
    $("#btnNuevo").on("click", limpiar_ingreso);
    $("#btnAtras").on("click", flecha_atras);
    $("#btnAdelante").on("click", flecha_siguiente);
    /////////////////////////////////
    $("#cantidad").validCampoFranz("0123456789");
    $("#descuento").validCampoFranz("0123456789");
    $("#descuento").attr("maxlength", "3");
    /////////////////////////////////////

    // eventos
    $("#codigo").on("keyup", limpiar_campo1);
    $("#producto").on("keyup", limpiar_campo2);
    $("#codigo").on("keypress", enter);
    $("#producto").on("keypress", enter);
    $("#cantidad").on("keypress", function (e) {
        if (idCargoUsuario == 1) {
            enter(e);
        } else {
            enter2(e);
        }

    });
    $("#precio").on("keypress", enter2);
    $("#p_venta").on("keypress", enter3);
    // fin

    $("#buscar_ingresos").dialog(dialogo2);
    $("#btnBuscar").click(function (e) {
        e.preventDefault();
        $("#buscar_ingresos").dialog("open");
    });
    // buscar producto codigo barras 
    $("#codigo_barras").change(function (e) {
        var codigo = $("#codigo_barras").val();
        var cod = $("#codigo_barras").val();
        $.getJSON('search.php?codigo_barras=' + codigo + "&cod=" + cod, function (data) {
            var tama = data.length;
            if (tama !== 0) {
                for (var i = 0; i < tama; i = i + 8) {
                    $("#codigo").val(data[i]);
                    $("#producto").val(data[i + 1]);
                    $("#precio").val(data[i + 2]);
                    $("#p_venta").val(data[i + 3]);
                    $("#iva_producto").val(data[i + 4]);
                    $("#cod_producto").val(data[i + 5]);
                    $("#incluye").val(data[i + 6]);
                    $("#disponibles").val(data[i + 7]);
                    $("#cantidad").focus();
                    abrirDialogo_unidad();
                }
            } else {
                $("#codigo").val("");
                $("#producto").val("");
                $("#precio").val("");
                $("#p_venta").val("");
                $("#iva_producto").val("");
                $("#cod_producto").val("");
                $("#incluye").val("");
                $("#disponibles").val("");
                alertify.error("Producto no ingresado");
                $("#codigo_barras").val("");
            }
            obtenerStockProducto($("#cod_producto").val());
        });
    });
    // fin

    // buscador productos codigo
    $("#codigo").autocomplete({
        source: "buscar_codigo.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#codigo").val(ui.item.value);
            $("#codigo_barras").val(ui.item.codigo_barras);
            $("#producto").val(ui.item.producto);
            $("#precio").val(ui.item.precio);
            $("#p_venta").val(ui.item.p_venta);
            $("#iva_producto").val(ui.item.iva_producto);
            $("#cod_producto").val(ui.item.cod_producto);
            $("#incluye").val(ui.item.incluye);
            $("#disponibles").val(ui.item.disponibles);
            obtenerStockProducto($("#cod_producto").val());
            abrirDialogo_unidad();
            return false;
        },
        select: function (event, ui) {
            $("#codigo").val(ui.item.value);
            $("#codigo_barras").val(ui.item.codigo_barras);
            $("#producto").val(ui.item.producto);
            $("#precio").val(ui.item.precio);
            $("#p_venta").val(ui.item.p_venta);
            $("#iva_producto").val(ui.item.iva_producto);
            $("#cod_producto").val(ui.item.cod_producto);
            $("#incluye").val(ui.item.incluye);
            $("#disponibles").val(ui.item.disponibles);
            obtenerStockProducto($("#cod_producto").val());
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };
    // fin

    // buscador productos nombre
    $("#producto").autocomplete({
        source: "buscar_producto.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#producto").val(ui.item.value);
            $("#codigo_barras").val(ui.item.codigo_barras);
            $("#codigo").val(ui.item.codigo);
            $("#precio").val(ui.item.precio);
            $("#p_venta").val(ui.item.p_venta);
            $("#iva_producto").val(ui.item.iva_producto);
            $("#cod_producto").val(ui.item.cod_producto);
            $("#incluye").val(ui.item.incluye);
            $("#disponibles").val(ui.item.disponibles);
            abrirDialogo_unidad();
            obtenerStockProducto($("#cod_producto").val());
            return false;
        },
        select: function (event, ui) {
            $("#producto").val(ui.item.value);
            $("#codigo_barras").val(ui.item.codigo_barras);
            $("#codigo").val(ui.item.codigo);
            $("#precio").val(ui.item.precio);
            $("#p_venta").val(ui.item.p_venta);
            $("#iva_producto").val(ui.item.iva_producto);
            $("#cod_producto").val(ui.item.cod_producto);
            $("#incluye").val(ui.item.incluye);
            $("#disponibles").val(ui.item.disponibles);
            obtenerStockProducto($("#cod_producto").val());
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };
    // fin

    // calendarios
    $("#fecha_actual").datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');
    //    

    // tabla detalle
    jQuery("#list").jqGrid({
        datatype: "local",
        colNames: ['', 'ID', 'Código', 'Producto', 'Cantidad', 'Precio Costo', 'Descuento', 'Calculado', 'Total', 'Precio Venta', 'Iva', 'Incluye', "Cantidad Unidad", "Unidad Medida"],
        colModel: [
            {
                name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions',
                formatoptions: { keys: false, delbutton: true, editbutton: false }
            },
            {
                name: 'cod_producto', index: 'cod_producto', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'center',
                frozen: true, width: 50
            },
            {
                name: 'codigo', index: 'codigo', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center',
                frozen: true, width: 100
            },
            { name: 'detalle', index: 'detalle', editable: false, frozen: true, editrules: { required: true }, align: 'center', width: 290 },
            { name: 'cantidad', index: 'cantidad', editable: false, frozen: true, editrules: { required: true }, align: 'center', width: 70 },
            { name: 'precio_u', index: 'precio_u', editable: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: 110, hidden: idCargoUsuario != 1 },
            { name: 'descuento', index: 'descuento', editable: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: 110 },
            { name: 'cal_des', index: 'cal_des', editable: false, hidden: true, frozen: true, editrules: { required: true }, align: 'center', width: 90 },
            { name: 'total', index: 'total', editable: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: 110, hidden: idCargoUsuario != 1 },
            { name: 'precio_v', index: 'precio_v', editable: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: 110 },
            { name: 'iva', index: 'iva', align: 'center', width: 100, hidden: true },
            { name: 'incluye', index: 'incluye', editable: false, hidden: true, frozen: true, editrules: { required: true }, align: 'center', width: 90 },
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
        ],
        rowNum: 30,
        width: 885,
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
                            iva1 = (sub1 * 0.12).toFixed(3);

                            subtotal0 = parseFloat($("#total_p").val()) + 0;
                            subtotal12 = parseFloat($("#total_p2").val()) - parseFloat(sub1);
                            iva12 = parseFloat($("#iva").val()) - parseFloat(iva1);
                            descu_total = parseFloat($("#desc").val()) - parseFloat(ret.cal_des);

                            subtotal0 = parseFloat(subtotal0).toFixed(3);
                            subtotal12 = parseFloat(subtotal12).toFixed(3);
                            iva12 = parseFloat(iva12).toFixed(3);
                            descu_total = parseFloat(descu_total).toFixed(3);
                        } else {
                            if (ret.incluye == "Si") {
                                subtotal = ret.total;
                                sub2 = (subtotal / 1.12).toFixed(3);
                                iva2 = (sub2 * 0.12).toFixed(3);

                                subtotal0 = parseFloat($("#total_p").val()) + 0;
                                subtotal12 = parseFloat($("#total_p2").val()) - parseFloat(sub2);
                                iva12 = parseFloat($("#iva").val()) - parseFloat(iva2);
                                descu_total = parseFloat($("#desc").val()) - parseFloat(ret.cal_des);

                                subtotal0 = parseFloat(subtotal0).toFixed(3);
                                subtotal12 = parseFloat(subtotal12).toFixed(3);
                                iva12 = parseFloat(iva12).toFixed(3);
                                descu_total = parseFloat(descu_total).toFixed(3);
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

                            subtotal0 = parseFloat(subtotal0).toFixed(3);
                            subtotal12 = parseFloat(subtotal12).toFixed(3);
                            iva12 = parseFloat(iva12).toFixed(3);
                            descu_total = parseFloat(descu_total).toFixed(3);
                        }
                    }
                }

                total_total = parseFloat(total_total) + (parseFloat(subtotal0) + parseFloat(subtotal12));
                total_total = parseFloat(total_total).toFixed(3);
                $("#total_p").val(subtotal0);
                $("#total_p2").val(subtotal12);
                $("#iva").val(iva12);
                $("#desc").val(descu_total);
                $("#tot").val(total_total);
                var su = jQuery("#list").jqGrid('delRowData', rowid);
                if (su === true) {
                    rp_ge.processing = true;
                    //$(".ui-icon-closethick").trigger('click');
                    $('#delmodlist').find(".ui-jqdialog-titlebar-close").click();
                }
                return true;
            },
            processing: true
        },
        afterSaveCell: function (rowid, name, val, iRow, iCol) {
        }
    });
    jQuery("#list2").jqGrid({
        url: 'xmlBuscarEgresos.php',
        datatype: 'xml',
        colNames: ['ID', 'FECHA', 'ORIGEN', 'DESTINO', 'id_origen', 'id_destino', 'NOMBRE', 'APELLIDO', 'ESTADO'],
        colModel: [
            { name: 'id_egresos', index: 'id_egresos', editable: false, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'fecha_actual', index: 'fecha_actual', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 150 },
            { name: 'origenNombre', index: 'origenNombre', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 150 },
            { name: 'destinoNombre', index: 'destinoNombre', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 150 },
            { name: 'origen', index: 'origen', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'center', frozen: true, width: 150 },
            { name: 'destino', index: 'destino', editable: true, search: false, hidden: true, editrules: { edithidden: false }, align: 'center', frozen: true, width: 150 },
            { name: 'nombre_usuario', index: 'nombre_usuario', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'apellido_usuario', index: 'apellido_usuario', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'estado', index: 'estado', editable: true, search: true, hidden: true, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
        ],
        rowNum: 30,
        width: 750,
        height: 220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager2'),
        sortname: 'id_egresos',
        sortorder: 'desc',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
            jQuery('#list2').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list2").jqGrid('getRowData', id);
                var valor = ret.id_egresos;
                // agregregar ingresos
                $("#comprobante").val(valor);
                $("#btnGuardar").attr("disabled", true);
                $("#btnModificar").attr("disabled", true);
                $("#list").jqGrid("clearGridData", true);
                $("#total_p").val("0.000");
                $("#total_p2").val("0.000");
                $("#iva").val("0.000");
                $("#tot").val("0.000");
                console.log("ESTADO: " + ret.estado);
                if (ret.estado === "Pasivo") {
                    $("#estado").append($("<h3>").text("Anulada"));
                    $("#estado h3").css("color", "red");
                    $("#btnAnular").attr("disabled", "disabled");
                } else {
                    $("#estado h3").remove();
                    $("#btnAnular").attr("disabled", "disabled");
                    $("#btnAnular").attr("disabled", false);
                }
                if (ret.origen != '' && ret.destino != '') {
                    $('#slTransacciones').val(2).change();
                    $('#origen').val(ret.origen).change();
                    $('#origen').attr('disabled', true);
                    $('#destino').val(ret.destino).change();
                    $('#destino').attr('disabled', true);
                } else {
                    $('#slTransacciones').val(1).change();
                    $('#origen').val('').change();
                    $('#origen').attr('disabled', false);
                    $('#destino').val('').change();
                    $('#destino').attr('disabled', false);
                }
                $.getJSON('retornar_egreso.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 12) {
                            $("#fecha_actual").val(data[i]);
                            $("#hora_actual").val(data[i + 1]);
                            $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                            $("#origen").val(data[i + 4]);
                            $("#destino").val(data[i + 5]);
                            $("#observaciones").val(data[i + 6]);
                            $("#total_p").val(data[i + 7]);
                            $("#total_p2").val(data[i + 8]);
                            $("#iva").val(data[i + 9]);
                            $("#desc").val(data[i + 10]);
                            $("#tot").val(data[i + 11]);
                        }
                    }
                });
                $.getJSON('retornar_egresos2.php?com=' + valor, function (data) {
                    var tama = data.length;
                    var descuento = 0;
                    var total = 0;
                    var su = 0;
                    var precio = 0;
                    var multi = 0;
                    var flotante = 0;
                    var resultado = 0;

                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 12) {
                            desc = data[i + 5];
                            precio = (parseFloat(data[i + 4])).toFixed(3);
                            multi = (parseFloat(data[i + 3]) * parseFloat(data[i + 4])).toFixed(3);
                            descuento = ((multi * parseFloat(desc)) / 100).toFixed(3);
                            flotante = parseFloat(descuento);
                            resultado = (Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2)).toFixed(3);
                            total = (multi - resultado).toFixed(3);
                            precio_venta = parseFloat(data[i + 7]).toFixed(3);

                            var datarow = {
                                cod_producto: data[i],
                                codigo: data[i + 1],
                                detalle: data[i + 2],
                                cantidad: data[i + 3],
                                precio_u: precio,
                                descuento: desc,
                                cal_des: resultado,
                                total: data[i + 6],
                                precio_v: precio_venta,
                                iva: data[i + 8],
                                incluye: data[i + 9],
                                cantidad_unidad: data[i + 10],
                                unidad_medida: data[i + 11],
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });
                $("#buscar_ingresos").dialog("close");
                habilitarAnulacion();
            } else {
                alertify.alert("Seleccione un Ingreso");
                //$('#comprobante').val("");
                habilitarAnulacion();
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
            jQuery('#list2').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list2").jqGrid('getRowData', id);
                var valor = ret.id_egresos;
                // agregregar ingresos
                $("#comprobante").val(valor);
                $("#btnGuardar").attr("disabled", true);
                $("#btnModificar").attr("disabled", true);
                $("#list").jqGrid("clearGridData", true);
                $("#total_p").val("0.000");
                $("#total_p2").val("0.000");
                $("#iva").val("0.000");
                $("#tot").val("0.000");
                $.getJSON('retornar_egreso.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 12) {
                            $("#fecha_actual").val(data[i]);
                            $("#hora_actual").val(data[i + 1]);
                            $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                            $("#origen").val(data[i + 4]);
                            $("#destino").val(data[i + 5]);
                            $("#observaciones").val(data[i + 6]);
                            $("#total_p").val(data[i + 7]);
                            $("#total_p2").val(data[i + 8]);
                            $("#iva").val(data[i + 9]);
                            $("#desc").val(data[i + 10]);
                            $("#tot").val(data[i + 11]);
                        }
                    }
                });
                $.getJSON('retornar_egresos2.php?com=' + valor, function (data) {
                    var tama = data.length;
                    var descuento = 0;
                    var total = 0;
                    var su = 0;
                    var precio = 0;
                    var multi = 0;
                    var flotante = 0;
                    var resultado = 0;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 12) {
                            desc = data[i + 5];
                            precio = (parseFloat(data[i + 4])).toFixed(3);
                            multi = (parseFloat(data[i + 3]) * parseFloat(data[i + 4])).toFixed(3);
                            descuento = ((multi * parseFloat(desc)) / 100).toFixed(3);
                            flotante = parseFloat(descuento);
                            resultado = (Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2)).toFixed(3);
                            total = (multi - resultado).toFixed(3);
                            precio_venta = parseFloat(data[i + 7]).toFixed(3);
                            var datarow = {
                                cod_producto: data[i],
                                codigo: data[i + 1],
                                detalle: data[i + 2],
                                cantidad: data[i + 3],
                                precio_u: precio,
                                descuento: desc,
                                cal_des: resultado,
                                total: data[i + 6],
                                precio_v: precio_venta,
                                iva: data[i + 8],
                                incluye: data[i + 9],
                                cantidad_unidad: data[i + 10],
                                unidad_medida: data[i + 11],
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });
                $("#buscar_ingresos").dialog("close");
            } else {
                alertify.alert("Seleccione un Ingreso");
            }
        }
    });
    jQuery(window).bind('resize', function () {
        jQuery("#list").setGridWidth(jQuery('#grid_container').width(), true);
    }).trigger('resize');

    slTransacciones();
    habilitarSeccion();
    inputmaskDecimal('#cantidad', true, 2);
    inputmaskDecimal('#precio', true, 4);
    inputmaskDecimal('#p_venta', true, 4);
    habilitarAnulacion();
    anular();
}

function slTransacciones() {
    //var sl = {ob: {id: 1, valor: 'Egreso Loca'}, ob: {id: 2, valor: 'Transferencia'}};
    var sl = [{ id: 1, descripcion: 'Egreso Local', selected: true }, { id: 2, descripcion: 'Transferencia', selected: false }];
    $.each(sl, function (i, item) {
        var op = new Option(item.descripcion, item.id, item.selected);
        $('#slTransacciones').append(op);
    });
    if ($('#slTransacciones').val() == 1) {
        $('#origDest').hide();
    }
}

function habilitarSeccion() {
    $('#slTransacciones').on('change', function () {
        if ($(this).val() == 1) {
            $('#origDest').hide();
        } else {
            $('#origDest').show();
        }
    })
}

function validarStock(producto, cantidad) {
    var respusta = false;
    datos = { op: 1, producto: producto, cantidad: $('#' + cantidad).val() };
    $.ajax({
        type: "POST",
        url: "../../procesos/Egresos/egresosControl.php",
        data: datos,
        dataType: 'json',
        async: false,
        success: function (data) {
            //json = $.parseJSON(data);
            if (data.estado) {
                respusta = data.estado;
            } else {
                $('#' + cantidad).focus();
                alertify.alert("Stock insuficiente");
            }
        }
    });
    return respusta;
}

function habilitarAnulacion() {
    console.log("COMPROBANTE: " + $('#comprobante').val());
    if ($('#comprobante').val() != "") {
        $('#btnAnular').attr('disabled', false);
    } else {
        $('#btnAnular').attr('disabled', true);
    }
}

function anular() {
    dialogoAnular();
    accederAnulacion();
    cancelarAnular();
    confirmarAnulacion();
    $("#btnAnular").click(function (e) {
        e.preventDefault();
        $("#clave_permiso").dialog("open");
    });
    $("#btnAceptar").click(function (e) {
        e.preventDefault();
        aceptar();
    });
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

function accederAnulacion() {
    $("#btnAcceder").click(function (e) {
        e.preventDefault();
        validar_acceso();
    });
}

function cancelarAnular() {
    $("#btnCancelar, #btnSalir").click(function (e) {
        e.preventDefault();
        location.reload();
    });
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

function validar_acceso() {
    if ($("#clave").val() == "") {
        $("#clave").focus();
        alertify.alert("Ingrese la clave");
    } else {
        if ($('#anulacionComentario').val() == "") {
            $('#anulacionComentario').focus();
            alertify.alert("Ingrese el comentario");
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
}

function aceptar() {
    $("#btnAceptar").attr("disabled", true);
    var datos = {
        op: 2,
        comprobante: $("#comprobante").val(),
        anulacionComentario: $("#anulacionComentario").val()
    };
    $.ajax({
        type: "POST",
        url: "../../procesos/Egresos/egresosControl.php",
        data: datos,
        dataType: 'json',
        success: function (data) {
            if (data.estado) {
                alertify.alert("Inventario Anulado Correctamente", function () {
                    var parafd = $('#anulacionComentario').val().replace(/%/g, '%25');
                    parafd = parafd.replace(/&/g, '%26');
                    data = { id: $("#comprobante").val(), comentarioanul: parafd };
                    abrirReporte("../../reportes/reporteEgreso.php", data);
                    location.reload();
                });
                $("#seguro").dialog("close");
                $("#clave_permiso").dialog("close");
            }
        }
    });
}

function obtenerStockProducto(idproducto) {
    $.ajax({
        url: "../egresos/obtener_stock_producto.php",
        method: "GET",
        data: { id: idproducto },
        success: function (data) {
            $("#stock").val(data);
        }
    });
}