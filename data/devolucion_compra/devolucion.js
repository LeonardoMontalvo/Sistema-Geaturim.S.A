var calculoIVA = 0;
var codImpuesto = 0;
var codTarifa = 0;

$(document).on("ready", inicio);

$(document).keydown(function (e) {
    var keycode = e.which || e.keyCode;
    if (keycode == 13) {
        if ($("#formaspago").val() == "otros") {
            //agregar_mixto();
            $("#btnAgregar_mixto").click();
        }
    }
});
function evento(e) {
    e.preventDefault();
}
var calculoIVA = 0;
function scrollToBottom() {
    $('html, body').animate({ scrollTop: $(document).height() }, 'slow');
}

function scrollToTop() {
    $('html, body').animate({ scrollTop: 0 }, 'slow');
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

var dialogo2 = {
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 350,
    modal: true,
    // position: "top",
    show: "explode",
    hide: "blind"
}

var dialogo3 = {
    autoOpen: false,
    resizable: false,
    width: 400,
    height: 210,
    modal: true,
    // position: "top",
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
var dialogo22 =
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
                llenarValoresPagosCxp();
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
        cargarTablaCuentasCxp();
    },
    close: function (event, ui) {
        $(document).keydown(function (e) {
            var keycode = e.which || e.keyCode;
            if (keycode == 13) {
                if ($("#formaspago").val() == "otros") {
                    //agregar_mixto();
                    $("#btnAgregar_mixto").click();
                }
            }
        });

        $("#formaspago_mixto").val("");
        $("#formaspago_mixto").change();
    }

};
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
        entrar(e);
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

function enter3(e) {
    if (e.which == 13 || e.keyCode == 13) {
        comprobar();
        return false;
    }
    return true;
}
function countfactura() {
    var temp2 = "";
    var serie = $("#num_nota_debito").val();
    for (var i = serie.length; i < 5; i++) {
        temp2 = temp2 + "0";
    }
    return temp2;
}
function autocompletar() {
    var temp = "";
    var serie = $("#num_nota_debito").val();
    for (var i = serie.length; i < 9; i++) {
        temp = temp + "0";
    }
    return temp;
}
function modificar_factura() {
    var tam = jQuery("#list").jqGrid("getRowData");

    if ($("#comprobante").val() == "") {
        alertify.error("Selccione una Devolucion");
        $("#buscar_devolucion_compras").dialog("open");
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

                    if (tam.length == 0) {
                        $("#codigo_barras").focus();
                        alertify.error("Error... Ingrese productos a la factura");
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

                        var seriee = $("#serie").val();
                        $.ajax({
                            type: "POST",
                            url: "modificar_devolucion_compra.php",
                            data: "id_devolucion_compra=" + $("#id_devolucion_compra").val() + "&id_proveedor=" + $("#id_proveedor").val() + "&comprobante=" + $("#comprobante").val() + "&fecha_actual=" + $("#fecha_actual").val() + "&fecha_registro_nc=" + $("#fecha_registro_nc").val() + "&hora_actual=" + $("#hora_actual").val() + "&observaciones=" + $("#observaciones").val() + "&fecha_registro_nc=" + $("#fecha_registro_nc").val() + "&tipo_comprobante=" + $("#tipo_comprobante").val() + "&serie=" + seriee + "&autorizacion=" + $("#autorizacion").val() + "&autorizacion_nc=" + $("#autorizacion_nc").val() + "&secuencial=" + $("#secuencial").val() + "&autorizacion_credito=" + $("#autorizacion_credito").val() + "&tarifa0=" + $("#total_p").val() + "&tarifa12=" + $("#total_p2").val() + "&iva=" + $("#iva").val() + "&desc=" + $("#desc").val() + "&tot=" + $("#tot").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&secuencial_nc=" + $("#secuencial_nc").val() + "&id_factura_compra=" + $("#id_factura_compra").val() + "&fecha_emision_nc=" + $("#fecha_emision_nc").val(),
                            success: function (data) {
                                var val = data;
                                if (val != 0) {
                                    alertify.alert("Factura Modificada correctamente", function () {
                                        window.open("../../reportes/devolucion_compra.php?id=" + val, '_blank');
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
function entrar(event = null) {
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
                        alertify.error("Ingrese una cantidad válida");
                    } else {
                        if ($("#id_factura_compra").val() != "" && $("#si_no_factura").val() == 1) {
                            if ((Number($("#cantidad").val()) > Number($("#canti").val())) && $("#descuentof2")[0].checked) {
                                $("#cantidad").focus();
                                alertify.error("Error.. La cantidad ingresada es mayor a la de compra, límite:" + $("#canti").val());
                            } else {
                                if (event.target.id == 'precio') {
                                    $("#descuento").focus();
                                } else if (event.target.id == 'descuento') {
                                    $("#concepto").focus();
                                } else {
                                    $("#precio").focus();
                                }
                            }
                        } else if ($("#id_factura_compra").val() == "" && $("#si_no_factura").val() == 2) {
                            if ($("#precio").val() == "") {
                                $("#precio").focus();
                            } else if ($("#precio").val() < 0) {
                                $("#precio").focus();
                                alertify.error("Ingrese una cantidad válida");
                            } else {
                                if (event.target.id == 'precio') {
                                    $("#descuento").focus();
                                } else if (event.target.id == 'descuento') {
                                    $("#concepto").focus();
                                } else {
                                    $("#precio").focus();
                                }
                            }
                        }

                    }
                }
            }
        }
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
function cargar_productos_factura() {
    //AQUI
    if (document.getElementById("descuentof2").checked) {

        var idf = $("#id_factura_compra").val();
        let tipo = $("#tipo_comprobante").val();
        if (idf == "") {
            $("#id_factura_compra").focus();
            $("#serie").val("");
            alertify.error("Error... Seleccione una Factura");
        } else {

            $.getJSON('retornar_proforma.php?id2=' + idf + "&tipo=" + tipo, function (data) {
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

                    for (var i = 0; i < tama; i = i + 17) {
                        var temp = 0;
                        var temp1 = 0;
                        if (data[i + 10] == "Si") {
                            if (Number(data[i + 3]) < 0) {
                                temp = 0;
                                temp1 = data[i + 4];
                            } else {
                                if (Number(data[i + 4]) > Number(data[i + 3])) {
                                    temp = data[i + 4];
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
                        resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                        total = multi - resultado;

                        var datarow = {
                            cod_producto: data[i],
                            codigo: data[i + 1],
                            detalle: data[i + 2],
                            cantidad: temp,
                            precio_u: precio,
                            descuento: desc,
                            cal_des: resultado,
                            total: data[i + 7],
                            precio_ux: precio.toFixed(2),
                            descuentox: parseFloat(desc).toFixed(2),
                            cal_desx: resultado.toFixed(2),
                            totalx: data[i + 7],
                            iva: data[i + 8],
                            pendiente: temp1,
                            incluye: data[i + 9],
                            cantidad_unidad: data[i + 11],
                            unidad_medida: data[i + 12],
                            inventariable: data[i + 10],
                            valor_iva: (data[i + 16] ? data[i + 16] : datacalcularIva(Number(total))),
                            tarifa: data[i + 15],
                            cod_impuesto: data[i + 13],
                            cod_tarifa: data[i + 14],
                        };
                        var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        var ivas = data[i + 8];
                    }

                    /*var subtotal = 0;
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
                            if (dd['incluye'] == "No") {
                                subtotal = dd['total'];
                                sub1 = subtotal;
                                iva1 = (sub1 * calculoIVA) / 100;

                                subtotal0 = parseFloat(subtotal0) + 0;
                                subtotal12 = parseFloat(subtotal12) + parseFloat(sub1);
                                descu_total = parseFloat(descu_total) + dd['cal_des'];
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
                                    descu_total = parseFloat(descu_total) + dd['cal_des'];

                                    subtotal0 = parseFloat(subtotal0);
                                    subtotal12 = parseFloat(subtotal12);
                                    iva12 = parseFloat(iva12);
                                    descu_total = parseFloat(descu_total);
                                }
                            }
                        } else {
                            if (dd['iva'] === "No") {
                                subtotal = dd['total'];
                                sub = subtotal;

                                subtotal0 = parseFloat(subtotal0) + parseFloat(sub);
                                subtotal12 = parseFloat(subtotal12) + 0;
                                iva12 = parseFloat(iva12) + 0;
                                descu_total = parseFloat(descu_total) + dd['cal_des'];

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
                    $("#totx").val(total_total.toFixed(2));*/
                    calcularTotalesTablaProductos();
                    $("#codigo_barras").focus();
                }
            });
        }
    }
}
function aceptarEliminar() {
    $("#btnAceptar").attr("disabled", true);
    if ($("#id_devolucion_compra").val() == "") {
        alertify.error("Seleccione una factura");
        $("#buscar_devolucion_compras").dialog("open");
    } else {
        $.ajax({
            type: "POST",
            url: "eliminar_factura_compra.php",
            data: "id_devolucion_compra=" + $("#id_devolucion_compra").val() + "&comprobante=" + $("#comprobante").val() + "&tipo_comprobante=" + $("#tipo_comprobante").val(),
            success: function (data) {
                var val = data;
                if (val == 1) {
                    alertify.alert(" Eliminada Correctamente", function () {
                        location.reload();
                    });
                } else {
                    if (val == -1) {
                        alertify.alert("<b>No puede eliminar la nota de crédito tiene valores cruzados.</b>", function (e) {
                            $("#seguro").dialog("close");
                            $("#clave_permiso").dialog("close");
                            $("#clave").val("");
                        });
                        $("#alertify-ok").css({ "background": "red" });
                    }
                    //alertify.alert(val);
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

function eliminar_factura() {
    if ($("#id_devolucion_compra").val() == "") {
        alertify.error("Seleccione una factura");
        $("#buscar_devolucion_compras").dialog("open");
    } else {
        $("#clave_permiso").dialog("open");
    }
}

function limpiar_input() {
    $("#codigo_barras").val("");
    $("#cod_producto").val("");
    $("#codigo").val("");
    $("#producto").val("");
    $("#cantidad").val("");
    $("#canti").val("");
    $("#precio").val("");
    $("#carga_series").val("");
    $("#descuento").val("");
    $("#incluye").val("");
    $("#concepto").val("");
    $("#unidad_medida").empty();
    $("#iva_producto")[0][0].selected = true;
}

async function entrar2() {

    let tivadata = $("#iva_producto")[0].selectedOptions[0].dataset;
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
                } else {
                    if ($("#cantidad").val() == "0") {
                        $("#cantidad").focus();
                        alertify.alert("Ingrese una cantidad válida");
                    } else {

                        if ($("#precio").val() == "") {
                            $("#precio").focus();
                            alertify.alert("Ingrese un precio");
                        } else {
                            var filas = jQuery("#list").jqGrid("getRowData");
                            var descuento = 0;
                            var total = 0;
                            var su = 0;
                            var precio = 0;
                            var multi = 0;
                            var flotante = 0;
                            var resultado = desc = Number($("#descuento").val()) || 0;
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
                                if ($("#descuento").val() != 0) {
                                    //desc = Number($("#descuento").val());
                                    multi = cantidadu * precio;
                                    total = multi - desc;
                                } else {
                                    //desc = 0;
                                    total = cantidadu * precio;
                                }

                                $("#alertify-logs").empty();
                                let resp = await obtenerStockProducto2($("#cod_producto").val());

                                stock = Number(resp.stock);

                                inventariable = "No";
                                if (stock >= 0) {
                                    inventariable = "Si";
                                    if (unidad_medida.trim() == "") {
                                        if (stock < $("#cantidad").val()) {
                                            alertify.error("No hay stock suficiente");
                                            return;
                                        }
                                    } else {
                                        if (stock < cantidad_unidad) {
                                            alertify.error("No hay stock suficiente");
                                            return;
                                        }
                                    }
                                }

                                if ($("#descuentof1")[0].checked) {
                                    if (inventariable == 'Si') {
                                        alertify.alert("<b>Para descuento debe seleccionar un producto no invetariable.<b>");
                                        $("#alertify-ok").css({ "background": "red" });
                                        return;
                                    }
                                }

                                var datarow = {
                                    cod_producto: $("#cod_producto").val(),
                                    codigo: $("#codigo").val(),
                                    detalle: $("#concepto").val() || $("#producto").val(),
                                    cantidad: $("#cantidad").val(),
                                    precio_u: precio,
                                    descuento: desc,
                                    cal_des: resultado,
                                    total: total,
                                    precio_ux: precio.toFixed(4),
                                    descuentox: parseFloat(desc).toFixed(4),
                                    cal_desx: resultado.toFixed(4),
                                    totalx: total.toFixed(4),
                                    iva: (Number(calculoIVA) == 0 ? 'No' : 'Si'),
                                    incluye: $("#incluye").val(),
                                    cantidad_unidad: cantidad_unidad,
                                    unidad_medida: unidad_medida,
                                    inventariable: inventariable,
                                    valor_iva: calcularIva(Number(total)),
                                    tarifa: calculoIVA,
                                    cod_impuesto: codImpuesto,
                                    cod_tarifa: codTarifa,
                                };
                                su = jQuery("#list").jqGrid('addRowData', $("#cod_producto").val(), datarow);
                                limpiar_input();
                            } else {
                                for (var i = 0; i < filas.length; i++) {
                                    var id = filas[i];

                                    if (id['cod_producto'] == $("#cod_producto").val()) {
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



                                    if ((suma > Number($("#canti").val()))) {
                                        $("#cantidad").focus();
                                        alertify.error("Error.. La cantidad ingresada es mayor a la de compra límite:" + $("#canti").val());
                                    } else {

                                        precio = parseFloat($("#precio").val());
                                        cantidadu = parseFloat($("#cantidad").val());
                                        if (!!cantidad_unidad) {
                                            precio = precio / cantidad_cu_medida;
                                            cantidadu = cantidad_unidad;
                                        }

                                        if ($("#descuento").val() != "") {
                                            //desc = Number($("#descuento").val());
                                            total = multi - desc;
                                        } else {
                                            //desc = 0;
                                            total = parseFloat(suma) * precio;
                                        }

                                        $("#alertify-logs").empty();
                                        let resp = await obtenerStockProducto2($("#cod_producto").val());
                                        stock = Number(resp.stock);

                                        inventariable = "No";
                                        if (stock >= 0) {
                                            inventariable = "Si";
                                            if (unidad_medida.trim() == "") {
                                                if (stock < $("#cantidad").val()) {
                                                    alertify.error("No hay stock suficiente");
                                                    return;
                                                }
                                            } else {
                                                if (stock < cantidad_unidad) {
                                                    alertify.error("No hay stock suficiente");
                                                    return;
                                                }
                                            }
                                        }

                                        datarow = {
                                            cod_producto: $("#cod_producto").val(),
                                            codigo: $("#codigo").val(),
                                            detalle: $("#concepto").val() || $("#producto").val(),
                                            cantidad: suma,
                                            precio_u: precio,
                                            descuento: desc,
                                            cal_des: resultado,
                                            total: total,
                                            precio_ux: precio.toFixed(4),
                                            descuentox: parseFloat(desc).toFixed(4),
                                            cal_desx: resultado.toFixed(4),
                                            totalx: total.toFixed(4),
                                            iva: (Number(calculoIVA) == 0 ? 'No' : 'Si'),
                                            incluye: $("#incluye").val(),
                                            cantidad_unidad: cantidad_unidad,
                                            unidad_medida: unidad_medida,
                                            inventariable: inventariable,
                                            valor_iva: calcularIva(Number(total)),
                                            tarifa: calculoIVA,
                                            cod_impuesto: codImpuesto,
                                            cod_tarifa: codTarifa,
                                        };
                                        su = jQuery("#list").jqGrid('setRowData', $("#cod_producto").val(), datarow);
                                        limpiar_input();
                                    }
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
                                        //desc = Number($("#descuento").val());
                                        multi = cantidadu * precio;
                                        total = multi - desc;
                                    } else {
                                        //desc = 0;
                                        multi = parseFloat($("#cantidad").val()) * precio;
                                        descuento = ((multi * parseFloat(desc)) / 100);
                                        flotante = parseFloat(descuento);
                                        resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                                        total = cantidadu * precio;
                                    }

                                    $("#alertify-logs").empty();
                                    let resp = await obtenerStockProducto2($("#cod_producto").val());

                                    stock = Number(resp.stock);

                                    inventariable = "No";
                                    if (stock >= 0) {
                                        inventariable = "Si";
                                        if (unidad_medida.trim() == "") {
                                            if (stock < $("#cantidad").val()) {
                                                alertify.error("No hay stock suficiente");
                                                return;
                                            }
                                        } else {
                                            if (stock < cantidad_unidad) {
                                                alertify.error("No hay stock suficiente");
                                                return;
                                            }
                                        }
                                    }

                                    datarow = {
                                        cod_producto: $("#cod_producto").val(),
                                        codigo: $("#codigo").val(),
                                        detalle: $("#concepto").val() || $("#producto").val(),
                                        cantidad: $("#cantidad").val(),
                                        precio_u: precio,
                                        descuento: desc,
                                        cal_des: resultado,
                                        total: total,
                                        precio_ux: precio.toFixed(4),
                                        descuentox: parseFloat(desc).toFixed(4),
                                        cal_desx: resultado.toFixed(4),
                                        totalx: total.toFixed(4),
                                        iva: (Number(calculoIVA) == 0 ? 'No' : 'Si'),
                                        incluye: $("#incluye").val(),
                                        cantidad_unidad: cantidad_unidad,
                                        unidad_medida: unidad_medida,
                                        inventariable: inventariable,
                                        valor_iva: calcularIva(Number(total)),
                                        tarifa: calculoIVA,
                                        cod_impuesto: codImpuesto,
                                        cod_tarifa: codTarifa,
                                    };
                                    su = jQuery("#list").jqGrid('addRowData', $("#cod_producto").val(), datarow);
                                    limpiar_input();
                                }
                            }

                            calcularTotalesTablaProductos();
                            $("#codigo_barras").focus();
                        }

                    }
                }
            }
        }
    }
}

function comprobar() {
    if ($("#tipo_docu").val() == "") {
        $("#tipo_docu").focus();
        alertify.error("Seleccione tipo documento");
    } else {
        if ($("#empresa").val() == "") {
            $("#ruc_ci").focus();
            alertify.error("Indique una empresa");
        } else {
            if ($("#tipo_comprobante").val() == "") {
                $("#tipo_comprobante").focus();
                alertify.error("Seleccione tipo comprobante");
            } else {
                if ($("#id_factura_compra").val() == "") {
                    $("#serie").focus();
                    alertify.error("Seleccione una factura");
                } else {
                    $("#codigo").focus();
                }
            }
        }
    }
}

function agregar() {
    if ($("#combobox").val() != "") {
        var filas2 = jQuery("#list2").jqGrid("getRowData");
        var su;
        var count = 0;
        var canti = $("#cantidad").val();

        if (filas2.length < canti) {
            if (filas2.length == 0) {
                var datarow = {
                    id_serie: count = count + 1,
                    serie: $("#combobox").val()
                };
                su = jQuery("#list2").jqGrid('addRowData', count, datarow);
                $("#combobox").val("");
            } else {
                var repe = 0;
                for (var i = 0; i < filas2.length; i++) {
                    var id = filas2[i];
                    if (id['serie'] == $("#combobox").val()) {
                        repe = 1;
                    }
                }
                if (repe == 0) {
                    datarow = {
                        id_serie: count = count + 1,
                        serie: $("#combobox").val()
                    };
                    su = jQuery("#list2").jqGrid('addRowData', count, datarow);
                    $("#combobox").val("");
                } else {
                    $("#combobox").val("");
                    alertify.alert("Error... Serie ingresada");
                }
            }
        } else {
            alertify.alert("Error... Alcanzo el límite máximo");
        }
    } else {
        $("#combobox").focus();
        $("#combobox").val("");
        alertify.alert("Error... En la serie");
    }
}


function guardar_serie() {
    var tam2 = jQuery("#list2").jqGrid("getRowData");

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
            data: "cod_producto=" + $("#cod_producto").val() + "&campo1=" + string_v1,
            success: function (data) {
                var val = data;
                if (val == 1) {
                    $("#series").dialog("close");
                    $("#precio").focus();
                }
            }
        });
    } else {
        alertify.alert("Error... Ingrese las series");
    }
}

function abrirDialogo() {
    var cod_pro = $("#cod_producto").val();
    var num_fact = $("#id_factura_compra").val();

    if (cod_pro == "") {
        alertify.alert("Error... Seleccione un producto");
    } else {
        $("#combobox").append('<option></option>');
        $.getJSON('retornar_series.php?cod=' + cod_pro + '&num=' + num_fact, function (data) {
            var tama = data.length;
            if (tama == 0) {
                alertify.alert("Series no ingresadas");
            } else {
                if ($("#cantidad").val() == "") {
                    $("#cantidad").focus();
                    alertify.alert("Ingrese una cantidad");
                } else {
                    if ((Number($("#cantidad").val()) > Number($("#canti").val()))/*  && $("#descuentof2")[0].checked */) {
                        $("#cantidad").val("");
                        $("#cantidad").focus();
                        alertify.alert("Error.. La cantidad ingresada es mayor a la de compra");
                    } else {
                        $('#combobox').children().remove().end();
                        $("#series").dialog("open");
                        $("#combobox").append('<option></option>');
                        for (var i = 0; i < tama; i = i + 1) {
                            $("#combobox").append('<option value=' + data[i] + ' >' + data[i] + '</option>');
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
                                    .addClass("custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left")
                                    .autocomplete({
                                        delay: 0,
                                        minLength: 0,
                                        source: $.proxy(this, "_source")
                                    })
                                    .tooltip({
                                        tooltipClass: "ui-state-highlight"
                                    });

                                this._on(this.input, {
                                    autocompleteselect: function (event, ui) {
                                        ui.item.option.selected = true;
                                        this._trigger("select", event, {
                                            item: ui.item.option
                                        });
                                    },

                                    autocompletechange: "_removeIfInvalid"
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
                                            primary: "ui-icon-triangle-1-s"
                                        },
                                        text: false
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
                                var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
                                response(this.element.children("option").map(function () {
                                    var text = $(this).text();
                                    if (this.value && (!request.term || matcher.test(text)))
                                        return {
                                            label: text,
                                            value: text,
                                            option: this
                                        };
                                }));
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
                            }

                        });
                        $("#combobox").combobox();
                    }
                }
            }
        });
    }
}

function guardar_devolucion() {
    if ($("#secuencial").val() != '') {
        var strs = $("#secuencial").val();
        var resS = strs.split("-");
        var ele2s = resS[2];
        var ele22s = ele2s.substring(8, 9);
    } else {
        ele22s = '000000000';
    }
    if ($("#secuencial_nc").val() != '') {
        var str = $("#secuencial_nc").val();
        var res = str.split("-");
        var ele2 = res[2];
        var ele22 = ele2.substring(8, 9);
    } else {
        ele22 = '000000000';
    }


    if (ele22 == '_' || ele22s == '_') {
        console.log("entroooorr");
        $("#serie").focus();
        $("#secuencial").val();
        alertify.error("Complete con Ceros a la Izquierda 000000000");
    } else {
        var tam = jQuery("#list").jqGrid("getRowData");
        if ($("#tipo_docu").val() == "") {
            $("#tipo_docu").focus();
            alertify.error("Seleccione tipo documento");
        } else {
            if ($("#empresa").val() == "") {
                $("#ruc_ci").focus();
                alertify.error("Indique una empresa");
            } else {
                if ($("#tipo_comprobante").val() == "") {
                    $("#tipo_comprobante").focus();
                    alertify.error("Seleccione tipo comprobante");
                } else {
                    if ($("#secuencial_nc").val() == "") {
                        $("#secuencial_nc").focus();
                        alertify.error("Ingrese la autorización");
                    } else {
                        if ($("#secuencial_nc").val() == "") {
                            $("#secuencial_nc").focus();
                            alertify.error("Ingrese la autorización");
                        } else {
                            if ($("#autorizacion_nc").val() == "") {
                                $("#autorizacion_nc").focus();
                                alertify.error("Ingrese la autorización de la nota de crédito");
                                return;
                            }
                            if ($("#fecha_registro_nc").val() == '') {
                                $("#fecha_registro_nc").focus();
                                alertify.error("Ingrese la fecha de autorización de la nóta de crédito");
                                return;
                            }
                            if ($("#fecha_emision_nc").val() == '') {
                                $("#fecha_emision_nc").focus();
                                alertify.error("Ingrese la fecha de emisión de la nóta de crédito");
                                return;
                            }
                            if ($("#si_no_factura").val() == 1) {
                                if ($("#serie").val() == "") {
                                    $("#serie").focus()
                                    alertify.error("Ingrese la serie de la factura");
                                    return;
                                }
                                let num = $("#serie").val().split("-").join("");
                                if (Number.isNaN(Number(num))) {
                                    $("#serie").focus()
                                    alertify.error("Ingrese la serie de la factura");
                                    return;
                                }
                                if ($("#autorizacion").val() == "") {
                                    $("#autorizacion").focus()
                                    alertify.error("Ingrese la autorización de la factura");
                                    return;
                                }
                            } else if ($("#si_no_factura").val() == 2) {
                                if ($("#secuencial").val() == "") {
                                    $("#secuencial").focus()
                                    alertify.error("Ingrese la serie de la factura");
                                    return;
                                }
                                let num = $("#serie").val().split("-").join("");
                                if (Number.isNaN(Number(num))) {
                                    $("#serie").focus()
                                    alertify.error("Ingrese la serie de la factura");
                                    return;
                                }
                                if ($("#autorizacion_credito").val() == "") {
                                    $("#autorizacion_credito").focus()
                                    alertify.error("Ingrese la autorización de la factura");
                                    return;
                                }
                            } else {
                                $("#si_no_factura").focus()
                                alertify.error("Seleccione el estado de registro de la factura");
                                return;
                            }

                            if (tam.length == 0) {
                                $("#codigo_barras").focus();
                                alertify.error("Error... Llene productos a la Devolución Compra");
                            } else {


                                $("#btnModificar").attr("disabled", true);
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

                                var fil = jQuery("#list").jqGrid("getRowData");
                                var ga = 0;
                                for (var i = 0; i < fil.length; i++) {
                                    var datos = fil[i];
                                    v1[i] = datos['cod_producto'];
                                    v2[i] = datos['cantidad'];
                                    v3[i] = datos['precio_u'];
                                    v4[i] = datos['descuento'];
                                    v5[i] = datos['total'];
                                    v6[i] = datos["cantidad_unidad"];
                                    v7[i] = datos["unidad_medida"];
                                    v8[i] = datos["detalle"];
                                    v9[i] = datos['tarifa'];
                                    v10[i] = datos['valor_iva'];
                                    v11[i] = datos['cod_impuesto'];
                                    v12[i] = datos['cod_tarifa'];
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
                                    string_v9 = string_v9 + "|" + v9[i];
                                    string_v10 = string_v10 + "|" + v10[i];
                                    string_v11 = string_v11 + "|" + v11[i];
                                    string_v12 = string_v12 + "|" + v12[i];

                                }

                                let rowData = jQuery("#list").jqGrid('getRowData');
                                let stockv = rowData.some(el => el.status_stock == 0);

                                if (stockv) {
                                    alertify.alert("<div style='text-align:left'><b>No puede continuar. Hay productos sin stock disponible para hacer la nota de crédito.<b></div>");
                                    $("#alertify-ok").css({ "background": "red" });
                                    $('.nav-tabs a[href="#tab_1"]').tab("show");
                                    return;
                                }

                                guardar_serie_otros(() => {
                                    $.ajax({
                                        type: "POST",
                                        url: "guardar_devolucion_compra.php",
                                        data: "id_proveedor=" + $("#id_proveedor").val()
                                            + "&comprobante=" + $("#comprobante").val()
                                            + "&id_factura_compra=" + $("#id_factura_compra").val()
                                            + "&fecha_actual=" + $("#fecha_actual").val()
                                            + "&hora_actual=" + $("#hora_actual").val()
                                            + "&tipo_comprobante=" + $("#tipo_comprobante").val()
                                            + "&serie=" + $("#serie").val()
                                            + "&autorizacion=" + $("#autorizacion").val()
                                            + "&tarifa0=" + $("#total_p").val()
                                            + "&tarifa12=" + $("#total_p2").val()
                                            + "&iva=" + $("#iva").val()
                                            + "&desc=" + $("#desc").val()
                                            + "&tot=" + $("#tot").val()
                                            + "&observaciones=" + $("#observaciones").val()
                                            + "&campo1=" + string_v1
                                            + "&campo2=" + string_v2
                                            + "&campo3=" + string_v3
                                            + "&campo4=" + string_v4
                                            + "&campo5=" + string_v5
                                            + "&clave=" + $("#num_nota_debito").val()
                                            + "&secuencial=" + $("#secuencial").val()
                                            + "&fecha_registro_credito=" + $("#fecha_registro_credito").val()
                                            + "&autorizacion_credito=" + $("#autorizacion_credito").val()
                                            + "&secuencial_nc=" + $("#secuencial_nc").val()
                                            + "&fecha_registro_nc=" + $("#fecha_registro_nc").val()
                                            + "&autorizacion_nc=" + $("#autorizacion_nc").val()
                                            + "&factura_crusada=" + $("#factura_crusada").val()
                                            + "&campo6=" + string_v6
                                            + "&campo7=" + string_v7
                                            + "&op_descuento=" + ($("#descuentof1")[0].checked ? "1" : "")
                                            + "&fecha_emision_nc=" + $("#fecha_emision_nc").val() +
                                            "&tipo_devolucion=" + $("#tipo_devolucion").val()
                                            + "&campo8=" + string_v8
                                            + "&tarifas=" + string_v9
                                            + "&vlores_iva=" + string_v10
                                            + "&cods_impuesto=" + string_v11
                                            + "&cods_tarifa=" + string_v12,
                                        success: function (data) {
                                            var val = data;
                                            if (val > 0) {
                                                alertify.alert("Devolución Guardada correctamente", function () {
                                                    window.open("../../reportes/devolucion_compra.php?id=" + val, '_blank');
                                                    location.reload();
                                                });
                                            }
                                        }
                                    });
                                });
                            }
                        }
                    }
                }
            }
        }

    }
}

function flecha_atras() {
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "devolucion_compra" + "&id_tabla=" + "id_devolucion_compra" + "&tipo=" + 1,
        success: function (data) {
            var val = data;
            if (val != "") {
                cargarFacturaDblclick(data);
            } else {
                alertify.alert("No hay más registros posteriores!!");
            }
        }
    });
}

function cambio_descuentosi() {
    $("#secuencial").attr("disabled", false);
    $("#autorizacion_credito").attr("disabled", false);
    $("#secuencial").val("");
    $("#secuencial").attr("disabled", true);
    $("#autorizacion_credito").val("");
    $("#autorizacion_credito").attr("disabled", true);
    $("#id_factura_compra").val("");
    $("#autorizacion").val("");
    $("#serie").attr("disabled", true);
    $("#serie").val("");

}

function cambio_descuentono() {
    $("#serie").attr("disabled", false);
    $("#serie").val("");
    $("#autorizacion").attr("disabled", false);
    $("#id_factura_compra").val("");
    $("#autorizacion").val("");
    $("#secuencial").val("");
    $("#secuencial").attr("disabled", true);
    $("#autorizacion_credito").attr("disabled", true);
}

function flecha_siguiente() {
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "devolucion_compra" + "&id_tabla=" + "id_devolucion_compra" + "&tipo=" + 2,
        success: function (data) {
            var val = data;
            if (val != "") {
               cargarFacturaDblclick(val);
            } else {
                alertify.alert("No hay más registros superiores!!");
            }
        }
    });
}

function limpiar_devolucion() {
    location.reload();
}

function limpiar_campo1() {
    if ($("#codigo").val() == "") {
        $("#codigo_barras").val("");
        $("#producto").val("");
        $("#cantidad").val("");
        $("#precio").val("");
        $("#canti").val("");
        $("#descuento").val("");
        $("#cod_producto").val("");
        $("#carga_series").val("");
        $("#incluye").val("");
        $("#concepto").val("");
        $("#unidad_medida").empty();
        $("#iva_producto")[0][0].selected = true;
    }
}

function limpiar_campo2() {
    if ($("#producto").val() == "") {
        $("#codigo_barras").val("");
        $("#codigo").val("");
        $("#cantidad").val("");
        $("#precio").val("");
        $("#canti").val("");
        $("#descuento").val("");
        $("#cod_producto").val("");
        $("#carga_series").val("");
        $("#incluye").val("");
        $("#concepto").val("");
        $("#unidad_medida").empty();
        $("#iva_producto")[0][0].selected = true;
    }
}

function limpiar_campo3() {
    if ($("#ruc_ci").val() == "") {
        $("#empresa").val("");
        $("#id_proveedor").val("");
    }
}

function limpiar_campo4() {
    if ($("#serie").val() == "") {
        $("#autorizacion").val("");
        $("#id_factura_compra").val("");
        $("#codigo_barras").val("");
        $("#codigo").val("");
        $("#producto").val("");
        $("#cantidad").val("");
        $("#precio").val("");
        $("#canti").val("");
        $("#descuento").val("");
        $("#cod_producto").val("");
        $("#carga_series").val("");
        $("#incluye").val("");
        $("#concepto").val("");
        $("#unidad_medida").empty();
        $("#list").jqGrid("clearGridData");
        $("#iva_producto")[0][0].selected = true;
    }
}

function cancelar_serie() {
    $("#series").dialog("close");
    $("#precio").focus();
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

function limpiar_datos() {
    $("#ruc_ci").val("");
    $("#empresa").val("");
    $("#id_proveedor").val("");
    $("#serie").val("");
    $("#autorizacion").val("");
    $("#id_factura_compra").val("");
}

function abrirCuenta() {
    $("#cuentas").dialog("open");
}
function inicio() {
    $("#dialog_form_cliente").dialog({
        modal: true,
        width: window.innerWidth - 180,
        height: window.innerHeight - 150,
        minHeight: 600,
        minHeight: 700,
        autoOpen: false,
        title: "REGISTRAR PROVEEDORES",
        close: function (event, ui) {
            infofac = null;
        }
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
    $("#si_no_factura").change(function (e) {
        if ($(this).val() == 1) {
            cambiarEstadoConFactura();
        } else if ($(this).val() == 2) {
            cambiarEstadoSinFactura();
        }
    });
    $("#tipo_comprobante").change(function (e) {
        cambiarEstadoFacturaNoSeleccionado();
    });

    tabChange();
    formaPagoCambio();
    formasMixtoCambio();
    disableFormasMixtoForm();
    $("#descuentof2").change(function () {
        limpiarTablaProductos();
        limpiar_input();

        $("#div_concepto").hide();

        $("#precio")[0].readOnly = false;
        $("#descuento")[0].readOnly = false;
    });
    $("#descuentof1").change(function () {
        limpiarTablaProductos();
        limpiar_input();

        $("#div_concepto").show();
    });
    $("#btnAgregar_mixto").click(function (e) {
        e.preventDefault();
        agregar_mixto();
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
    listaPagoRetencion();
    $("#btnCuenta").click(function (e) {
        e.preventDefault();
    });
    $("#btnCuenta").on("click", abrirCuenta);
    $('#grid_container_pago_reten_anti').hide();
    /*  $("#forma_pago").change(function () {
         if ($("#forma_pago").val() == "CXP") {
             $("#cuenta_contable").attr("disabled", false);
             $("#btnCuenta").attr("disabled", false);
             $("#cuenta_contable").val("");
             $("#idCuenta").val("");
             $("#list22").jqGrid('setGridParam', {
                 url: 'xmlFacturas_compra.php?id_proveedor=' + $("#id_proveedor").val() + '&tipo=' + "INTERNA" + '&canceladas=true',
                 datatype: 'xml'
             }).trigger('reloadGrid');
             $("#buscar_anticipo").dialog("open");
             $('#grid_container_pago_reten_anti').show();
         }
     }); */
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
                    let precioc = data[2];
                    let preciovmin = data[4];

                    $("#precio_v").val(preciovmin);
                    $("#cantidad_unidad").val(cantidadu);
                    $("#precio").val(cantidadu * $("#precio").val());


                }
            );
            $("#cantidad").focus();
        }
    });

    /* $("#secuencial").attr("disabled", true);
    $("#autorizacion_credito").attr("disabled", true); */

    $("[data-mask]").inputmask();
    alertify.set({ delay: 3000 });

    show();

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
    $("#btnAceptar").click(function (e) {
        e.preventDefault();
    });
    $("#btnModificar").click(function (e) {
        e.preventDefault();
    });
    $("#btnModificar").on("click", modificar_factura);
    $("#btnCancelar").click(function (e) {
        e.preventDefault();
    });
    $("#btnNuevo").click(function (e) {
        e.preventDefault();
    });
    $("#btnEliminar").click(function (e) {
        e.preventDefault();
    });
    $("#btnAcceder").click(function (e) {
        e.preventDefault();
    });
    $("#btnProductos_factura").click(function (e) {
        e.preventDefault();
    });
    $("#btnProductos_factura").on("click", cargar_productos_factura);
    $("#btnAceptar").on("click", aceptarEliminar);
    $("#btnEliminar").on("click", eliminar_factura);
    $("#btnImprimir").click(function () {
        $.ajax({
            type: "POST",
            url: "../../procesos/validacion.php",
            data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "devolucion_compra" + "&id_tabla=" + "id_devolucion_compra" + "&tipo=" + 1,
            success: function (data) {
                var val = data;
                if (val != "") {
                    window.open("../../reportes/devolucion_compra.php?id=" + $("#comprobante").val(), '_blank');
                } else {
                    alertify.alert("Devolución no creada!!");
                }
            }
        });
    });

    $("#btncargar").on("click", abrirDialogo);
    $("#btnAgregar").on("click", agregar);
    $("#btnGuardarSeries").on("click", guardar_serie);
    $("#btnCancelarSeries").on("click", cancelar_serie);
    $("#btnGuardar").on("click", guardar_devolucion);
    $("#btnNuevo").on("click", limpiar_devolucion);
    $("#btnAtras").on("click", flecha_atras);
    $("#btnAdelante").on("click", flecha_siguiente);
    //$("#descuentof1").on("change", cambio_descuentosi);
    //$("#descuentof2").on("change", cambio_descuentono);
    $("#btnAcceder").on("click", validar_acceso);
    $("#codigo").on("keyup", limpiar_campo1);
    $("#producto").on("keyup", limpiar_campo2);
    $("#ruc_ci").on("keyup", limpiar_campo3);
    $("#serie").on("keyup", limpiar_campo4);
    $("#series").dialog(dialogo);
    $("#buscar_devolucion_compras").dialog(dialogo2);
    $("#clave_permiso").dialog(dialogo3);
    $("#seguro").dialog(dialogo4);
    $("#buscar_anticipo").dialog(dialogo22);
    $("#btnBuscar").click(function (e) {
        e.preventDefault();
        $("#buscar_devolucion_compras").dialog("open");
    });
    $("#cuentas").dialog(dialogo_cuenta);

    $("#cantidad").validCampoFranz("0123456789");
    $("#autorizacion").validCampoFranz("0123456789");
    $("#serie").validCampoFranz("0123456789");
    $("#serie").attr("disabled", "disabled");
    $("#serie").attr("maxlength", "17");
    $("#autorizacion").attr("disabled", "disabled");
    $("#descuento").validCampoFranz("0123456789");
    $("#secuencial").attr("disabled", "disabled");
    $("#autorizacion_credito").attr("disabled", "disabled");


    $("#codigo").on("keypress", enter);
    $("#producto").on("keypress", enter);
    $("#cantidad").on("keyup", enter);
    $("#precio").on("keyup", enter);
    $("#ruc_ci").on("keypress", enter3);
    $("#empresa").on("keypress", enter3);
    $("#serie").on("keypress", enter3);
    $("#precio").on("keypress", punto);

    $("#descuento").on("keypress", (e) => {
        if ($("#descuentof2")[0].checked) {
            enter2(e);
        } else {
            enter(e);
        }
    });
    $("#concepto").on("keypress", enter2);

    $("#ruc_ci").attr("disabled", "disabled");
    $("#empresa").attr("disabled", "disabled");
    $("#adelanto").attr("disabled", "disabled");
    $("#meses").attr("disabled", "disabled");
    $("#cuotas").attr("disabled", "disabled");

    $("#ruc_ci").blur(function (e) {
        if ($("#id_proveedor").val() == "") {
            $("#empresa").val("");
            $("#ruc_ci").val("");
            $("#tipo_docu").change();
        }
    });

    $("#tipo_docu").change(function () {
        var tipo = $("#tipo_docu").val();
        if (tipo == "Cedula") {
            $("#ruc_ci").validCampoFranz("0123456789");
            $("#ruc_ci").removeAttr("disabled");
            $("#serie").removeAttr("disabled");
            $("#secuencial").removeAttr("disabled");
            $("#autorizacion_credito").removeAttr("disabled");
            $("#ruc_ci").attr("maxlength", "10");
        } else {
            if (tipo == "Ruc") {
                $("#ruc_ci").validCampoFranz("0123456789");
                $("#ruc_ci").removeAttr("disabled");
                $("#serie").removeAttr("disabled");
                $("#secuencial").removeAttr("disabled");
                $("#autorizacion_credito").removeAttr("disabled");
                $("#ruc_ci").removeAttr("maxlength");
                $("#ruc_ci").attr("maxlength", "13");
            } else {
                if (tipo == "Pasaporte") {
                    $("#ruc_ci").unbind("keypress");
                    $("#ruc_ci").removeAttr("disabled");
                    $("#serie").removeAttr("disabled");
                    $("#secuencial").removeAttr("disabled");
                    $("#autorizacion_credito").removeAttr("disabled");
                    $("#ruc_ci").attr("maxlength", "30");
                }
            }
        }

        $("#ruc_ci").autocomplete({
            source: function (request, response) {
                $("#empresa").val("");
                $("#id_proveedor").val("");

                var data = { term: request.term };
                $.get(
                    "buscar_empresa.php?tipo_docu=" + tipo,
                    data,
                    response,
                    "json"
                );
            },
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
        limpiar_datos();

        /* if ($("#descuentof1")[0].checked) {
            $("#serie")[0].disabled = true;
        } */
    });
    // Fin
    if ($("#num_oculto").val() == "") {
        $("#num_nota_debito").val("");
    } else {
        var str = $("#num_oculto").val();
        var res = Number(str.substr(4, 16));
        res = res + 1;
        $("#num_nota_debito").val(res);
        var a = autocompletar(res);
        var validado = a + "" + res;
        $("#num_nota_debito").val(validado);
    }
    // buscar facturas
    $("#serie").keyup(function (e) {
        var id = $("#id_proveedor").val();

        if (id == "") {
            alert("Error... Seleccione un proveedor");
            $("#ruc_ci").focus();
            $("#serie").val("");
        } else {
            $("#serie").autocomplete({
                source: "buscar_facturas.php?id=" + id + "&tipo_doc=" + $("#tipo_devolucion").val(),
                minLength: 1,
                focus: function (event, ui) {
                    $("#serie").val(ui.item.value);
                    $("#autorizacion").val(ui.item.autorizacion);
                    $("#id_factura_compra").val(ui.item.id_factura_compra);
                    return false;
                },
                select: function (event, ui) {
                    $("#serie").val(ui.item.value);
                    $("#autorizacion").val(ui.item.autorizacion);
                    $("#id_factura_compra").val(ui.item.id_factura_compra);
                    $("#list").jqGrid("clearGridData");
                    return false;
                }

            }).data("ui-autocomplete")._renderItem = function (ul, item) {
                return $("<li>")
                    .append("<a>" + item.value + "</a>")
                    .appendTo(ul);
            }
        }
    });

    $("#codigo_barras").change(function (e) {
        var codigo = $("#codigo_barras").val();
        var cod = $("#codigo_barras").val();
        var ids = $("#id_factura_compra").val();
        if ($("#id_factura_compra").val() != "" && $("#si_no_factura").val() == 1) {
            if ($("#descuentof2")[0].checked) {
                $.getJSON('search.php?codigo_barras=' + codigo + '&ids=' + ids + "&cod=" + cod, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 13) {
                            $("#cod_producto").val(data[i]);
                            $("#codigo").val(data[i + 1]);
                            $("#producto").val(data[i + 3]);
                            $("#precio").val(data[i + 4]);
                            $("#canti").val(data[i + 5]);
                            $("#descuento").val(data[i + 6]);
                            $("#iva_producto").val(data[i + 7]);
                            $("#carga_series").val(data[i + 8]);
                            $("#incluye").val(data[i + 10]);
                            $("#cantidad").focus();
                            $("#iva_producto").val(data[i + 12]);
                            abrirDialogo_unidad();
                        }
                    } else {
                        $("#codigo").val("");
                        $("#producto").val("");
                        $("#precio").val("");
                        $("#descuento").val("");
                        $("#canti").val("");
                        $("#iva_producto").val("Si");
                        $("#carga_series").val("");
                        $("#cod_producto").val("");
                        $("#incluye").val("");
                        $("#cantidad").val("");
                        alertify.error("Producto no ingresado");
                        $("#codigo_barras").val("");
                        $("#concepto").val("");
                        $("#unidad_medida").empty();
                        $("#iva_producto")[0][0].selected = true;
                    }
                });
            } else {
                $.getJSON('search_sinid.php?codigo_barras=' + codigo + '&ids=' + ids + "&cod=" + cod, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 12) {
                            console.log(data, "data");
                            $("#cod_producto").val(data[i]);
                            $("#codigo").val(data[i + 1]);
                            $("#producto").val(data[i + 3]);
                            $("#precio").val(data[i + 4]);
                            $("#canti").val(data[i + 5]);
                            $("#descuento").val(data[i + 6]);
                            $("#iva_producto").val(data[i + 7]);
                            $("#carga_series").val(data[i + 8]);
                            $("#incluye").val(data[i + 10]);
                            $("#cantidad").focus();
                            $("#iva_producto").val(data[i + 11]);
                            abrirDialogo_unidad();
                        }
                    } else {
                        $("#codigo").val("");
                        $("#producto").val("");
                        $("#precio").val("");
                        $("#descuento").val("");
                        $("#canti").val("");
                        $("#iva_producto").val("Si");
                        $("#carga_series").val("");
                        $("#cod_producto").val("");
                        $("#incluye").val("");
                        $("#cantidad").val("");
                        alertify.error("Producto no ingresado");
                        $("#codigo_barras").val("");
                        $("#concepto").val("");
                        $("#unidad_medida").empty();
                        $("#iva_producto")[0][0].selected = true;
                    }
                });
            }
        } else if ($("#id_factura_compra").val() == "" && $("#si_no_factura").val() == 2) {
            $.getJSON('search_sinid.php?codigo_barras=' + codigo + '&ids=' + ids + "&cod=" + cod, function (data) {
                var tama = data.length;
                if (tama != 0) {
                    for (var i = 0; i < tama; i = i + 12) {
                        $("#cod_producto").val(data[i]);
                        $("#codigo").val(data[i + 1]);
                        $("#producto").val(data[i + 3]);
                        $("#precio").val(data[i + 4]);
                        $("#canti").val(data[i + 5]);
                        $("#descuento").val(data[i + 6]);
                        $("#iva_producto").val(data[i + 7]);
                        $("#carga_series").val(data[i + 8]);
                        $("#incluye").val(data[i + 10]);
                        $("#cantidad").focus();
                        $("#iva_producto").val(data[i + 11]);
                        abrirDialogo_unidad();
                    }
                } else {
                    $("#codigo").val("");
                    $("#producto").val("");
                    $("#precio").val("");
                    $("#descuento").val("");
                    $("#canti").val("");
                    $("#iva_producto").val("");
                    $("#carga_series").val("");
                    $("#cod_producto").val("");
                    $("#incluye").val("");
                    $("#cantidad").val("");
                    alertify.error("Producto no ingresado");
                    $("#codigo_barras").val("");
                    $("#unidad_medida").empty();
                    $("#iva_producto")[0][0].selected = true;
                }
            });
        }
    });

    $("#codigo").keyup(function () {
        if ($("#id_factura_compra").val() != "" && $("#si_no_factura").val() == 1) {
            if ($("#descuentof2")[0].checked) {
                $("#codigo").autocomplete({
                    source: "buscar_codigo.php?ids=" + $("#id_factura_compra").val(),
                    minLength: 1,
                    focus: function (event, ui) {
                        $("#codigo_barras").val(ui.item.codigo_barras);
                        $("#codigo").val(ui.item.value);
                        $("#producto").val(ui.item.producto);
                        $("#precio").val(ui.item.precio);
                        $("#canti").val(ui.item.canti);
                        $("#descuento").val(ui.item.descuento);
                        $("#iva_producto").val(ui.item.iva_producto);
                        $("#carga_series").val(ui.item.carga_series);
                        $("#cod_producto").val(ui.item.cod_producto);
                        $("#incluye").val(ui.item.incluye);
                        abrirDialogo_unidad();
                        return false;
                    },
                    select: function (event, ui) {
                        $("#codigo_barras").val(ui.item.codigo_barras);
                        $("#codigo").val(ui.item.value);
                        $("#producto").val(ui.item.producto);
                        $("#precio").val(ui.item.precio);
                        $("#canti").val(ui.item.canti);
                        $("#descuento").val(ui.item.descuento);
                        $("#iva_producto").val(ui.item.iva_producto);
                        $("#carga_series").val(ui.item.carga_series);
                        $("#cod_producto").val(ui.item.cod_producto);
                        $("#incluye").val(ui.item.incluye);
                        abrirDialogo_unidad();
                        return false;
                    }

                }).data("ui-autocomplete")._renderItem = function (ul, item) {
                    return $("<li>")
                        .append("<a>" + item.value + "</a>")
                        .appendTo(ul);
                };
            } else {
                $("#codigo").autocomplete({
                    source: "buscar_codigo_sinid.php?ids=" + $("#id_factura_compra").val(),
                    minLength: 1,
                    focus: function (event, ui) {
                        $("#codigo_barras").val(ui.item.codigo_barras);
                        $("#codigo").val(ui.item.value);
                        $("#producto").val(ui.item.producto);
                        $("#precio").val(ui.item.precio);
                        $("#canti").val(ui.item.canti);
                        $("#descuento").val(ui.item.descuento);
                        $("#iva_producto").val(ui.item.iva_producto);
                        $("#carga_series").val(ui.item.carga_series);
                        $("#cod_producto").val(ui.item.cod_producto);
                        $("#incluye").val(ui.item.incluye);
                        abrirDialogo_unidad();
                        return false;
                    },
                    select: function (event, ui) {
                        $("#codigo_barras").val(ui.item.codigo_barras);
                        $("#codigo").val(ui.item.value);
                        $("#producto").val(ui.item.producto);
                        $("#precio").val(ui.item.precio);
                        $("#canti").val(ui.item.canti);
                        $("#descuento").val(ui.item.descuento);
                        $("#iva_producto").val(ui.item.iva_producto);
                        $("#carga_series").val(ui.item.carga_series);
                        $("#cod_producto").val(ui.item.cod_producto);
                        $("#incluye").val(ui.item.incluye);
                        abrirDialogo_unidad();
                        return false;
                    }

                }).data("ui-autocomplete")._renderItem = function (ul, item) {
                    return $("<li>")
                        .append("<a>" + item.value + "</a>")
                        .appendTo(ul);
                };
            }
        } else if ($("#id_factura_compra").val() == "" && $("#si_no_factura").val() == 2) {
            $("#codigo").autocomplete({
                source: "buscar_codigo_sinid.php?ids=" + $("#id_factura_compra").val(),
                minLength: 1,
                focus: function (event, ui) {
                    $("#codigo_barras").val(ui.item.codigo_barras);
                    $("#codigo").val(ui.item.value);
                    $("#producto").val(ui.item.producto);
                    $("#precio").val(ui.item.precio);
                    $("#canti").val(ui.item.canti);
                    $("#descuento").val(ui.item.descuento);
                    $("#iva_producto").val(ui.item.iva_producto);
                    $("#carga_series").val(ui.item.carga_series);
                    $("#cod_producto").val(ui.item.cod_producto);
                    $("#incluye").val(ui.item.incluye);
                    abrirDialogo_unidad();
                    return false;
                },
                select: function (event, ui) {
                    $("#codigo_barras").val(ui.item.codigo_barras);
                    $("#codigo").val(ui.item.value);
                    $("#producto").val(ui.item.producto);
                    $("#precio").val(ui.item.precio);
                    $("#canti").val(ui.item.canti);
                    $("#descuento").val(ui.item.descuento);
                    $("#iva_producto").val(ui.item.iva_producto);
                    $("#carga_series").val(ui.item.carga_series);
                    $("#cod_producto").val(ui.item.cod_producto);
                    $("#incluye").val(ui.item.incluye);
                    abrirDialogo_unidad();
                    return false;
                }

            }).data("ui-autocomplete")._renderItem = function (ul, item) {
                return $("<li>")
                    .append("<a>" + item.value + "</a>")
                    .appendTo(ul);
            };
        }
    });

    $("#producto").keyup(function () {
        if ($("#id_factura_compra").val() != "" && $("#si_no_factura").val() == 1) {
            if ($("#descuentof2")[0].checked) {
                $("#producto").autocomplete({
                    source: "buscar_producto.php?ids=" + $("#id_factura_compra").val(),
                    minLength: 1,
                    focus: function (event, ui) {
                        /*  $("#codigo_barras").val(ui.item.codigo_barras);
                         $("#producto").val(ui.item.value);
                         $("#codigo").val(ui.item.codigo);
                         $("#precio").val(ui.item.precio);
                         $("#canti").val(ui.item.canti);
                         $("#descuento").val(ui.item.descuento);
                         $("#iva_producto").val(ui.item.iva_producto);
                         $("#carga_series").val(ui.item.carga_series);
                         $("#cod_producto").val(ui.item.cod_producto);
                         $("#incluye").val(ui.item.incluye);
                         abrirDialogo_unidad(); */
                        return false;
                    },
                    select: function (event, ui) {
                        $("#codigo_barras").val(ui.item.codigo_barras);
                        $("#producto").val(ui.item.value);
                        $("#codigo").val(ui.item.codigo);
                        $("#precio").val(ui.item.precio);
                        $("#canti").val(ui.item.canti);
                        $("#descuento").val(ui.item.descuento);
                        $("#iva_producto").val(ui.item.iva_producto);
                        $("#carga_series").val(ui.item.carga_series);
                        $("#cod_producto").val(ui.item.cod_producto);
                        $("#incluye").val(ui.item.incluye);
                        $("#iva_producto").val(ui.item.id_taimpuesto);
                        abrirDialogo_unidad();
                        return false;
                    }

                }).data("ui-autocomplete")._renderItem = function (ul, item) {
                    return $("<li>")
                        .append("<a>" + item.value + "</a>")
                        .appendTo(ul);
                };


            } else {
                $("#producto").autocomplete({
                    source: "buscar_producto_sinid.php?",
                    minLength: 1,
                    focus: function (event, ui) {
                        /*  $("#codigo_barras").val(ui.item.codigo_barras);
                         $("#producto").val(ui.item.value);
                         $("#codigo").val(ui.item.codigo);
                         $("#precio").val(ui.item.precio);
                         $("#canti").val(ui.item.canti);
                         $("#descuento").val(ui.item.descuento);
                         $("#iva_producto").val(ui.item.iva_producto);
                         $("#carga_series").val(ui.item.carga_series);
                         $("#cod_producto").val(ui.item.cod_producto);
                         $("#incluye").val(ui.item.incluye);
                         abrirDialogo_unidad(); */
                        return false;
                    },
                    select: function (event, ui) {
                        $("#codigo_barras").val(ui.item.codigo_barras);
                        $("#producto").val(ui.item.value);
                        $("#codigo").val(ui.item.codigo);
                        $("#precio").val(ui.item.precio);
                        $("#canti").val(ui.item.canti);
                        $("#descuento").val(ui.item.descuento);
                        $("#iva_producto").val(ui.item.iva_producto);
                        $("#carga_series").val(ui.item.carga_series);
                        $("#cod_producto").val(ui.item.cod_producto);
                        $("#incluye").val(ui.item.incluye);
                        $("#iva_producto").val(ui.item.id_taimpuesto);
                        abrirDialogo_unidad();
                        return false;
                    }

                }).data("ui-autocomplete")._renderItem = function (ul, item) {
                    return $("<li>")
                        .append("<a>" + item.value + "</a>")
                        .appendTo(ul);
                };
            }
        } else if ($("#id_factura_compra").val() == "" && $("#si_no_factura").val() == 2) {
            $("#producto").autocomplete({
                source: "buscar_producto_sinid.php?",
                minLength: 1,
                focus: function (event, ui) {
                    /*  $("#codigo_barras").val(ui.item.codigo_barras);
                     $("#producto").val(ui.item.value);
                     $("#codigo").val(ui.item.codigo);
                     $("#precio").val(ui.item.precio);
                     $("#canti").val(ui.item.canti);
                     $("#descuento").val(ui.item.descuento);
                     $("#iva_producto").val(ui.item.iva_producto);
                     $("#carga_series").val(ui.item.carga_series);
                     $("#cod_producto").val(ui.item.cod_producto);
                     $("#incluye").val(ui.item.incluye);
                     abrirDialogo_unidad(); */
                    return false;
                },
                select: function (event, ui) {
                    $("#codigo_barras").val(ui.item.codigo_barras);
                    $("#producto").val(ui.item.value);
                    $("#codigo").val(ui.item.codigo);
                    $("#precio").val(ui.item.precio);
                    $("#canti").val(ui.item.canti);
                    $("#descuento").val(ui.item.descuento);
                    $("#iva_producto").val(ui.item.iva_producto);
                    $("#carga_series").val(ui.item.carga_series);
                    $("#cod_producto").val(ui.item.cod_producto);
                    $("#incluye").val(ui.item.incluye);
                    $("#iva_producto").val(ui.item.id_taimpuesto);
                    abrirDialogo_unidad();
                    return false;
                }

            }).data("ui-autocomplete")._renderItem = function (ul, item) {
                return $("<li>")
                    .append("<a>" + item.value + "</a>")
                    .appendTo(ul);
            };
        }

    });

    $('#fecha_actual').datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');

    $('#fecha_registro_credito').datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');
    //    $('#fecha_registro_nc').datepicker({
    //        dateFormat: 'yy-mm-dd'
    //    }).datepicker('setDate', 'today');
    // tabla 




    $(window).bind('resize', function () {
        jQuery("#list4").setGridWidth($('#pager4').width());
    }).trigger('resize');
    jQuery("#list4").jqGrid({
        url: 'xmlPlanCuentas.php',
        datatype: 'xml',
        colNames: ['Cod. Cuenta', 'Descripcion', 'Cuenta'],
        colModel: [
            { name: 'idcontable', index: 'idcontable', editable: true, align: 'left', width: '120', search: true, frozen: true, formoptions: { elmsuffix: " (*)" }, editrules: { required: true } },
            { name: 'ccontable', index: 'ccontable', editable: true, align: 'center', width: '490', search: true, frozen: true, formoptions: { elmsuffix: " (*)" }, editrules: { required: true } },
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
            $("#cuenta_contable").val(ccuenta);
            //            console.log(ccuenta);
            var string = ccuenta;
            var string1 = string.split("-");

            var part1 = string1[1]; // 123
            $("#banco").val(part1);
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
    ///////////////////////////////
    jQuery("#list").jqGrid({
        datatype: "local",
        colNames: [
            '',
            'ID',
            'Código',
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
            'C. Unidad',
            'U. Medida',
            'Stock Disp.',
            'status_stock',
            'inventariable',
            'tarifa',
            'valor_iva',
            'cod_impuesto',
            'cod_tarifa'
        ],
        colModel: [
            { name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: { keys: false, delbutton: true, editbutton: false } },
            { name: 'cod_producto', index: 'cod_producto', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },
            { name: 'codigo', index: 'codigo', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'detalle', index: 'detalle', editable: false, frozen: true, editrules: { required: true }, align: 'center', width: 290 },
            { name: 'cantidad', index: 'cantidad', editable: false, frozen: true, editrules: { required: true }, align: 'center', width: 70 },
            { name: 'precio_u', index: 'precio_u', hidden: true, editable: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: 110 },
            { name: 'descuento', index: 'descuento', hidden: true, editable: false, frozen: true, editrules: { required: true }, align: 'center', width: 70 },
            { name: 'cal_des', index: 'cal_des', hidden: true, editable: false, hidden: true, frozen: true, editrules: { required: true }, align: 'center', width: 90 },
            { name: 'total', index: 'total', hidden: true, editable: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: 110 },
            { name: 'precio_ux', index: 'precio_ux', editable: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: 110 },
            { name: 'descuentox', index: 'descuentox', editable: false, frozen: true, editrules: { required: true }, align: 'center', width: 70 },
            { name: 'cal_desx', index: 'cal_desx', editable: false, hidden: true, frozen: true, editrules: { required: true }, align: 'center', width: 90 },
            { name: 'totalx', index: 'totalx', editable: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: 110 },
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
                name: "stock_disp",
                index: "stock_disp",
                editable: false,
                frozen: true,
                align: "center",
                width: 90,
                formatter: function (cellvalue, options, rowObject) {
                    return `<div id="control_stock_${options.rowId}"></div>`;
                }
            },
            {
                name: "status_stock",
                index: "status_stock",
                hidden: true
            },
            {
                name: "inventariable",
                index: "inventariable",
                hidden: true
            },
            { name: "tarifa", index: "tarifa", hidden: true, },
            { name: "valor_iva", index: "valor_iva", hidden: true, },
            { name: "cod_impuesto", index: "cod_impuesto", hidden: true, },
            { name: "cod_tarifa", index: "cod_tarifa", hidden: true, },
        ],
        rowNum: 30,
        height: 250,
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
                    limpiar_campos_mixto();
                    rp_ge.processing = true;
                    $(".ui-icon-closethick").trigger('click');
                }
                return true;
            },
            processing: true
        },
        gridComplete: function () {
            let data = $("#list").jqGrid("getRowData");
            data.forEach(el => {
                if (el.unidad_medida == "") {
                    comprobarStockTabla(el.cod_producto, el.inventariable, el.cantidad, el.cod_producto);
                } else {
                    console.log("um");
                    comprobarStockTabla(el.cod_producto, el.inventariable, el.cantidad_unidad, el.cod_producto);
                }
            });
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
    /////////////////////
    ////////////////////buscador proformas tecnico/////////////////////////
    jQuery("#listPagoreten_mixto_anti").jqGrid({
        datatype: "local",
        colNames: ['', 'ID', 'Factura a Pagar', 'Tipo Factura', 'Fecha Factura', 'Total CxC', 'Valor a Pagar', 'Saldo', 'COMPRAS/GASTO'],
        colModel: [
            { name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: { keys: false, delbutton: true, editbutton: false } },
            { name: 'ids', index: 'ids', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },
            { name: 'num_factura', index: 'num_factura', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 180 },
            { name: 'tipo_factura', index: 'tipo_factura', editable: false, frozen: true, hidden: false, editrules: { required: true }, align: 'center', width: 250 },
            { name: 'fecha_factura', index: 'fecha_factura', editable: true, frozen: true, hidden: false, editrules: { required: true }, align: 'center', width: 180 },
            { name: 'totalcxc', index: 'totalcxc', editable: true, search: false, frozen: true, hidden: false, editrules: { required: true }, align: 'center', width: 110 },
            { name: 'valor_pagado', index: 'valor_pagado', editable: true, frozen: true, hidden: false, editrules: { required: true }, align: 'center', width: 120 },
            { name: 'saldo', index: 'saldo', editable: false, search: false, frozen: true, hidden: false, editrules: { required: true }, align: 'center', width: 110 },
            { name: 'compra_gasto', index: 'compra_gasto', editable: false, search: false, frozen: true, hidden: false, editrules: { required: true }, align: 'center', width: 110 },
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        height: 120,
        sortable: true,
        pager: jQuery('#pagerP_reten_anti'),
        sortname: 'ids',
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
    ////////////////////////////////
    //////////BUSCAR CUENTAS PAGAR////////
    ////////////////////////////////////////
    ////////////////////////////////////////
    jQuery("#list22").jqGrid({
        datatype: 'local',
        colNames: ['Num Docu', 'Fecha Registro', 'Saldo Pendiente', 'Valor Pago'],
        colModel: [
            {
                name: 'num_factura', index: 'num_factura', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'left',
                frozen: true, width: 150
            },
            { name: 'fecha_credito', index: 'fecha_credito', editable: false, frozen: true, hidden: false, editrules: { required: true }, align: 'left', width: 150 },
            { name: 'saldo', index: 'saldo', editable: true, frozen: true, hidden: false, editrules: { required: true }, align: 'left', width: 100 },
            {
                name: 'valor_pago',
                index: 'valor_pago',
                editable: true,
                frozen: true,
                hidden: false,
                editrules: { required: true },
                align: 'left',
                width: 100,
                formatter: function (cellvalue, options, rowObject) {
                    return `<div style="text-align:center;"><input id="valor_pago_${options.rowId}" style="width:100px;" type="text" value="${cellvalue}"/></div>`;
                }
            }
        ],
        rowNum: 10,
        width: 600,
        rowList: [10, 20, 30],
        pager: jQuery('#pager22'),
        shrinkToFit: true,
        sortorder: 'asc',
        caption: 'Lista de Pagos Pendientes',
        viewrecords: true,
        afterInsertRow: function (rowid, rowdata, rowelem) {
            $("#valor_pago_" + rowid).change(function (e) {

                let fac = facturasCobrar.find((el) => el.id_pagos_compra == rowid);
                fac.valor_pago = $(this).val();
            });
            $("#valor_pago_" + rowid).on("keypress", punto);
            $("#valor_pago_" + rowid)[0].addEventListener("input", function (e) {
                if (Number($("#valor_pago_" + rowid).val()) > Number(rowdata.saldo)) {
                    $("#valor_pago_" + rowid).val("0");
                    $("#valor_pago_" + rowid).select();
                    $("#alertify-logs").empty();
                    alertify.error("El VALOR DEL PAGO DEBE SER MENOR A SALDO PENDIENTE.");
                }
            });
        },
    }).jqGrid('navGrid', '#pager22', {
        add: false,
        edit: false,
        del: false,
        refresh: false,
        search: false,
        view: false
    });
    $(window).bind('resize', function () {
        jQuery("#list22").setGridWidth($('#pager22').width());
    }).trigger('reloadGrid');

    /*jQuery("#list22").jqGrid({
        url: 'xmlFacturas_compra.php',
        datatype: 'xml',
        colNames: ['ID', 'Factura a Pagar', 'Tipo Factura', 'Fecha Factura', 'Total CxC', 'Valor a Pagar', 'Saldo', 'COMPRAS/GASTO'],
        colModel: [
            { name: 'ids', index: 'ids', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },
            { name: 'num_factura', index: 'num_factura', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 180 },
            { name: 'tipo_factura', index: 'tipo_factura', editable: false, frozen: true, hidden: true, editrules: { required: true }, align: 'center', width: 250 },
            { name: 'fecha_factura', index: 'fecha_factura', editable: true, frozen: true, hidden: true, editrules: { required: true }, align: 'center', width: 180 },
            { name: 'totalcxc', index: 'totalcxc', editable: true, search: false, frozen: true, hidden: true, editrules: { required: true }, align: 'center', width: 110 },
            { name: 'valor_pagado', index: 'valor_pagado', editable: true, frozen: true, hidden: true, editrules: { required: true }, align: 'center', width: 120 },
            { name: 'saldo', index: 'saldo', editable: false, search: false, frozen: true, hidden: false, editrules: { required: true }, align: 'center', width: 110 },
            { name: 'compra_gasto', index: 'compra_gasto', editable: false, search: false, frozen: true, hidden: false, editrules: { required: true }, align: 'center', width: 110 },
        ],
        rowNum: 10,
        width: 500,
        rowList: [10, 20, 30],
        pager: jQuery('#pager22'),
        shrinkToFit: true,
        sortorder: 'asc',
        caption: 'Lista',
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
                    if (dd['num_factura'] == ret.num_factura) {
                        repe = 1;
                    }
                }

                if ($("#factura_crusada").val() == "1") {
                    alertify.error("Error...ya esta seleccionada la factura");
                } else {
                    var datarow = {
                        ids: ret.ids,
                        num_factura: ret.num_factura,
                        tipo_factura: ret.tipo_factura,
                        fecha_factura: ret.fecha_factura,
                        totalcxc: ret.totalcxc,
                        saldo: ret.saldo
                    };
                    $("#factura_crusada").val("1");
                }

                var su = jQuery("#listPagoreten_mixto_anti").jqGrid('addRowData', count, datarow);
                var subtotal = 0;
                var sub1 = 0;
                var fil = jQuery("#listPagoreten_mixto_anti").jqGrid("getRowData");
                for (var t = 0; t < fil.length; t++) {
                    var dd = fil[t];
                    //                    subtotal = (subtotal + (parseFloat(dd['monto'])));
                }

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
    }).trigger('reloadGrid');*/
    ///////////////////////////////////////
    jQuery("#list3").jqGrid({
        url: 'xmlBuscarDevolucionCompra.php',
        datatype: 'xml',
        colNames: ['ID', 'IDENTIFICACIÓN', 'EMPRESA', 'FACTURA NRO.', 'MONTO TOTAL', 'FECHA'],
        colModel: [
            { name: 'id_devolucion_compra', index: 'id_devolucion_compra', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },
            { name: 'identificacion_pro', index: 'identificacion_pro', editable: false, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 150 },
            { name: 'empresa_pro', index: 'empresa_pro', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 200 },
            { name: 'num_serie', index: 'num_serie', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 200 },
            { name: 'total_devolucion', index: 'total_devolucion', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'fecha_devolucion', index: 'fecha_devolucion', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
        ],
        rowNum: 30,
        width: 750,
        height: 220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager3'),
        sortname: 'id_devolucion_compra',
        sortorder: 'desc',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list3").jqGrid('getGridParam', 'selrow');
            jQuery('#list3').jqGrid('restoreRow', id);
            var ret = jQuery("#list3").jqGrid('getRowData', id);
            var valor = ret.id_devolucion_compra;
            if (id) {
                cargarFacturaDblclick(valor);
                $("#buscar_devolucion_compras").dialog("close");
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

    jQuery("#list3").jqGrid('navButtonAdd', '#pager3', {
        caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#list3").jqGrid('getGridParam', 'selrow');
            jQuery('#list3').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list3").jqGrid('getRowData', id);
                var valor = ret.id_devolucion_compra;

                $("#comprobante").val(ret.id_devolucion_compra);
                $("#btnGuardar").attr("disabled", true);
                $("#btnModificar").attr("disabled", true);
                $("#btncargar").attr("disabled", true);

                $("#ruc_ci").attr("disabled", "disabled");
                $("#serie").attr("disabled", "disabled");
                $("#autorizacion").attr("disabled", "disabled");
                $("#observaciones").attr("disabled", "disabled");
                $("#list").jqGrid("clearGridData", true);
                $("#total_p").val("0.000");
                $("#total_p2").val("0.000");
                $("#iva").val("0.000");
                $("#tot").val("0.000");

                $.getJSON('retornar_devolucion_compra.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 17) {

                            $("#fecha_actual").val(data[i]);
                            $("#hora_actual").val(data[i + 1]);
                            $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                            $("#id_proveedor").val(data[i + 4]);
                            $("#tipo_docu").val(data[i + 5]);
                            $("#ruc_ci").val(data[i + 6]);
                            $("#empresa").val(data[i + 7]);
                            $("#tipo_comprobante").val(data[i + 8]);
                            $("#serie").val(data[i + 9]);
                            $("#autorizacion").val(data[i + 10]);
                            $("#observaciones").val(data[i + 11]);
                            $("#total_p").val(data[i + 12]);
                            $("#total_p2").val(data[i + 13]);
                            $("#iva").val(data[i + 14]);
                            $("#desc").val(data[i + 15]);
                            $("#tot").val(data[i + 16]);
                            $("#total_px").val(parseFloat(data[i + 12]).toFixed(2));
                            $("#total_p2x").val(parseFloat(data[i + 13]).toFixed(2));
                            $("#ivax").val(parseFloat(data[i + 14]).toFixed(2));
                            $("#descx").val(parseFloat(data[i + 15]).toFixed(2));
                            $("#totx").val(parseFloat(data[i + 16]).toFixed(2));
                        }
                    }
                });

                $.getJSON('retornar_devolucion_compra2.php?com=' + valor, function (data) {
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
                            desc = parseFloat(data[i + 5]);
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
                                total: data[i + 6],
                                precio_ux: precio.toFixed(2),
                                descuentox: desc.toFixed(2),
                                cal_desx: resultado.toFixed(2),
                                totalx: parseFloat(data[i + 6]).toFixed(2),
                                iva: data[i + 7],
                                incluye: data[i + 8]
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });
                $("#buscar_devolucion_compras").dialog("close");
            } else {
                alertify.alert("Seleccione una Factura");
            }
        }
    });

    jQuery(window).bind('resize', function () {
        jQuery("#list").setGridWidth(jQuery('#grid_container').width(), true);
    }).trigger('resize');

    $("#num_nota_debito")[0].readOnly = true;
    if (Number($("#comprobante").val()) == 1) {
        $("#num_nota_debito").val($("#comprobante").val().padStart(9, '0'));
    }

    $("#tipo_devolucion").change(function (e) {
        if (e.target.value == "C") {
            $("#descuentof2")[0].disabled = false;
            $("#descuentof2")[0].checked = true;
            $("#descuentof2").trigger("change");
        } else if (e.target.value == "G") {
            $("#descuentof2")[0].disabled = true;
            $("#descuentof1")[0].checked = true;
            $("#descuentof1").trigger("change");
        }
        $("#tipo_comprobante").val("FACTURA");
        $("#tipo_comprobante").trigger("change");
        $("#tipo_devolucion").focus();
    });
}

///formas pago mixto
function formaPagoCambio() {
    $("#formaspago").change(function () {
        var tam2 = jQuery("#list").jqGrid("getRowData");
        if ($("#formaspago").val() == "") {
            disableFormasMixtoForm();
            $("#adelanto").attr("disabled", "disabled");
            $("#adelanto").val("");
            $("#meses").attr("disabled", "disabled");
            $("#meses").val("");
            $("#cuotas").attr("disabled", "disabled");
            $("#cuotas").children().remove().end();
        } else {
            if ($("#formaspago").val() == "otros") {
                enableFormasMixtoForm();
                if (tam2.length > 0 && $("#id_proveedor").val() != "") {
                    console.log("abc");
                    $('.nav-tabs a[href="#tab_3"]').tab("show");
                    $("#formaspago_mixto").attr("disabled", false);
                    $("#formaspago_mixto").focus();
                    $("#valor_factura").val($("#totx").val());
                    $("#valor_formas")[0].disabled = true;
                } else {
                    disableFormasMixtoForm();
                    $("#contado_form").prop("selected", true);
                    alertify.error(
                        "Error..Ingrese Productos"
                    );
                }
            }
        }
    });
}
function enableFormasMixtoForm() {
    $("#formaspago_mixto").val("");
    $("#btnCuenta")[0].disabled = false;
    $("#formaspago_mixto")[0].disabled = false;
    $("#valor_formas")[0].disabled = false;
    $("#btnAgregar_mixto")[0].disabled = false;
    $("#num_tarjeta")[0].disabled = false;
}
function disableFormasMixtoForm() {
    $("#formaspago_mixto").val("");
    $("#btnCuenta")[0].disabled = true;
    $("#formaspago_mixto")[0].disabled = true;
    $("#valor_formas")[0].disabled = true;
    $("#btnAgregar_mixto")[0].disabled = true;
    $("#num_tarjeta")[0].disabled = true;
    limpiar_campos_mixto();
}
function limpiar_campos_mixto() {
    $("#adelanto").val("0");
    $("#meses").val("");
    $("#valor_formas").val("");
    $("#num_tarjeta").val("");
    $("#listPagoreten_mixto").jqGrid("clearGridData", true).trigger("realoadGrid");
    $("#cantidad_mixto").val() == "";
    $("#validar_guardar").val("");
    $("#btnGuardarRetenciones_mixto").attr("disabled", true);
    $("#cantidad_mixto").val("");
    $("#valor_factura_saldo").val("");
    $("#valor_factura").val($("#totx").val());
    $("#cuenta_contable").val("");
    $("#idCuenta").val("");
}
function agregar_mixto() {
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
                                    let formp = $("#formaspago_mixto").val();
                                    if (formp == 'Contado' || formp == 'Cheque' || formp == 'Transferencias') {
                                        if ($("#idCuenta").val() == "") {
                                            $("#cuenta_contable").focus();
                                            alertify.error("Error.. Debe seleccionar Cuenta contable");
                                            return;
                                        }
                                    }

                                    var filas2 = jQuery("#listPagoreten_mixto").jqGrid(
                                        "getRowData"
                                    );
                                    var su;
                                    var count = 0;
                                    var canti = $("#valor_formas").val();
                                    //                    if (filas2.length < canti) {

                                    if (filas2.length == 0) {
                                        //                            alertify.alert("dddd1");


                                        var datarow = {
                                            id_f_v_mix: (count = count + 1),
                                            id_factura_venta: $("#comprobante").val(),
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
                                            return;
                                        } else {

                                            datarow = {
                                                id_f_v_mix: (count = count + filas2.length),
                                                id_factura_venta: $("#comprobante").val(),
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

                                    $("#idCuenta").val("");
                                    $("#cuenta_contable").val("");

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
function formasMixtoCambio() {
    $("#formaspago_mixto").change(function () {
        var tam2 = jQuery("#list").jqGrid("getRowData");

        if ($("#formaspago_mixto").val() == "Contado"
            || $("#formaspago_mixto").val() == "Cheque"
            || $("#formaspago_mixto").val() == "Transferencias"
            || $("#formaspago_mixto").val() == "VALOR_FAVOR_EMPRESA"

        ) {
            if ($("#formaspago_mixto").val() == "Cheque"
                || $("#formaspago_mixto").val() == "Transferencias"
                || $("#formaspago_mixto").val() == "Contado"
            ) {
                $("#btnCuenta").attr("disabled", false);
            } else {
                $("#btnCuenta").attr("disabled", true);
            }
            $("#valor_formas").attr("disabled", false);
            $("#adelanto").removeAttr("disabled");
            $("#meses").attr("disabled", "disabled");
            $("#meses").val("");
            $("#cuotas").attr("disabled", "disabled");
            $("#cuotas").children().remove().end();
            var tam2 = jQuery("#listPagoreten_mixto_anti").jqGrid("getRowData");
            if (tam2.length == 0) {
                $('#grid_container_pago_reten_anti').hide();
            }
            $("#idCuenta").val("4");

            $("#num_tarjeta").attr("disabled", false);
        } else {
            if ($("#formaspago_mixto").val() == "CXP") {
                $("#cuenta_contable").attr("disabled", true);
                $("#btnCuenta").attr("disabled", true);
                $("#cuenta_contable").val("");
                $("#idCuenta").val("");
                $('#fecha_vencimiento').hide();
                $("#valor_formas").attr("disabled", true);
                $("#num_tarjeta").attr("disabled", true);
                $("#buscar_anticipo").dialog("open");
            }

        }

        $("#list44").jqGrid("setGridParam", {
            url: `xmlPlanCuentas.php?cuenta=` + $("#formaspago_mixto").val(),
            page: 1,
        }).trigger("reloadGrid");

        $("#idCuenta").val("");
        $("#cuenta_contable").val("");
    });
}
async function cargarTablaCuentasCxp() {
    try {
        jQuery("#list22").jqGrid("clearGridData");
        if (!!$("#id_proveedor").val()) {
            let cuentasc = await obtenerCxpEmpresa($("#id_proveedor").val());
            facturasCobrar = cuentasc.map((fac) => {
                fac["valor_pago"] = 0;
                return fac;
            });
            let fil = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
            fil = fil.filter(el => el.forma_pago_mixto == "CXP");
            if (fil.length > 0) {
                fil.forEach(el => {
                    let find = facturasCobrar.find(f => f.id_pagos_compra == el.num_documento);
                    find.valor_pago = el.valor;
                });
            }
            cuentasc.forEach((el) => {
                jQuery("#list22").jqGrid('addRowData', el.id_pagos_compra, el);
            });
        }
    } catch (error) {
        console.error(error);
    }
}
function obtenerCxpEmpresa(idproveedor) {
    return $.ajax({
        url: "cxp_empresa_nc.php",
        method: "GET",
        data: {
            id_proveedor: idproveedor
        },
        dataType: "json"
    }).done(function (data) {
        return data;
    });
}
function llenarValoresPagosCxp() {
    $("#validar_guardar_grid").val("1");
    let totalcxc = 0;
    facturasCobrar.forEach(el => totalcxc += Number(el.valor_pago));

    let fil = jQuery("#listPagoreten_mixto").jqGrid("getRowData");

    let filsicxc = fil.filter(el => el.forma_pago_mixto != "CXP");
    let totalgrid = 0;
    for (let t = 0; t < filsicxc.length; t++) {
        let dd = filsicxc[t];
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
    let cxcfil = fil.filter(el => el.forma_pago_mixto == "CXP");
    cxcfil.forEach(el => {
        jQuery("#listPagoreten_mixto").jqGrid("delRowData", el.id_f_v_mix);
    });
    jQuery("#listPagoreten_mixto").trigger('reloadGrid');

    let filas2 = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
    count = filas2.length;

    facturasCobrar = facturasCobrar.filter(el => el.valor_pago > 0);
    facturasCobrar.forEach(el => {
        count++;
        let datarow = {
            id_f_v_mix: count,
            id_factura_venta: $("#comprobante").val(),
            fecha: $("#fecha_actual").val(),
            forma_pago_mixto: $("#formaspago_mixto").val(),
            tarjeta_credito: $("#tarjetas").val(),
            num_documento: el.id_pagos_compra,//$("#num_tarjeta").val(),
            valor: el.valor_pago,//$("#valor_formas").val(),
            id_cuenta: "",//$("#idCuenta").val(),
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
    $("#buscar_anticipo").dialog("close");
}
function guardar_serie_otros(fun) {
    if ($("#formaspago").val() == '') {
        $('.nav-tabs a[href="#tab_3"]').tab('show')
        $("#valor_formas").focus();
        alertify.error("Seleccione una forma de pago.");
        return;
    }
    var tam2 = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
    if ($("#formaspago").val() == "otros") {
        if (
            $("#formaspago").val() == "otros" &&
            $("#valor_factura_saldo").val() != "0.00"
        ) {
            alertify.error("Ingrese Valor ");
            $('.nav-tabs a[href="#tab_3"]').tab('show')
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
                            "id_devolucion_venta=" +
                            $("#comprobante").val() +
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
                            "&tipo_comprobante=" +
                            $("#tipo_comprobante").val() +
                            "&id_cliente=" + $("#id_cliente").val(),
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
        //fun();
    }
}

function tabChange() {
    $(".nav-tabs a").on('shown.bs.tab', function (event) {
        let tabtext = $(event.target).text();         // active tab
        if (tabtext == 'Formas de Pago') {
            $("#formaspago").val("otros");
            $("#formaspago").trigger("change");
        }
    });
}

function abrirDialogo_unidad() {
    var cod = $("#cod_producto").val();
    var tipo_comprobante = $("#tipo_comprobante").val();
    let num_fact_venta = $("#serie").val();
    if (cod == "") {
        alertify.alert("Error... Seleccione un producto");
    } else {
        //$("#unidad_medida").append("<option></option>");
        $.getJSON("retornar_series_unidad_sinid.php?cod=" + cod +
            "&tipo_comprobante=" +
            tipo_comprobante +
            "&num_fac_venta=" +
            num_fact_venta, function (data) {
                var tama = data.length;
                if (tama == 0) {
                    //                alertify.alert("Series no ingresadas");
                } else {
                    if ($("#cod_producto").val() == "") {
                        $("#cod_producto").focus();
                        alertify.alert("Error... Indique una cantidad");

                    } else {
                        $("#unidad_medida").children().remove().end();

                        $("#unidad_medida").append(`<option value="">---Seleccione---</option>`);
                        for (var i = 0; i < tama; i = i + 2) {
                            $("#unidad_medida").append(
                                "<option value=" + data[i] + " >" + data[i + 1] + "</option>"
                            );
                            $("#unidad_medida").change();
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

function limpiarTablaProductos() {
    $("#total_p").val("0.00");
    $("#total_p2").val("0.00");
    $("#iva").val("0.00");
    $("#desc").val("0.00");
    $("#tot").val("0.00");
    $("#total_px").val("0.00");
    $("#total_p2x").val("0.00");
    $("#ivax").val("0.00");
    $("#descx").val("0.00");
    $("#totx").val("0.00");
    $("#codigo_barras").focus();
    $("#list").jqGrid("clearGridData").trigger("reloadGrid");
}

function obtenerStockProducto($idprod) {
    return $.ajax({
        url: "consultar_stock.php",
        method: "GET",
        dataType: "json",
        data: { id_producto: $idprod }
    });
}

function comprobarStockTabla(idprod, inventariable, cantidad, rowid) {
    if (inventariable == "") {
        return;
    }
    if (inventariable == 'Si') {
        obtenerStockProducto(idprod).then(function (data) {
            $(`#control_stock_${rowid}`).empty();
            $(`#control_stock_${rowid}`).text("0");
            $(`#control_stock_${rowid}`).css({ 'background': 'red', 'color': 'white' });
            jQuery("#list").jqGrid('setCell', rowid, "status_stock", '0');
            if (!!data) {
                if (Number(data.stock) >= Number(cantidad)) {
                    $(`#control_stock_${rowid}`).empty();
                    $(`#control_stock_${rowid}`).text(data.stock);
                    $(`#control_stock_${rowid}`).css({ 'background': 'green', 'color': 'white' });
                    jQuery("#list").jqGrid('setCell', rowid, "status_stock", '1');
                } else {
                    $(`#control_stock_${rowid}`).text(data.stock);
                }
            }
        });
    } else {
        $(`#control_stock_${rowid}`).empty();
        $(`#control_stock_${rowid}`).text("NO INV.");
        $(`#control_stock_${rowid}`).css({ 'background': 'green', 'color': 'white' });
        jQuery("#list").jqGrid('setCell', rowid, "status_stock", '1');
    }
}

async function comprobarStock(cantidad) {
    let resp = await obtenerStockProducto2($("#cod_producto").val());
    stock = Number(resp.stock);
    if (stock >= 0) {
        if (stock < cantidad) {
            return false;
        }
    }
    return -1;
}

function obtenerStockProducto2($idprod) {
    return $.ajax({
        url: "consultar_stock_2.php",
        method: "GET",
        dataType: "json",
        data: { id_producto: $idprod }
    });
}

function cambiarEstadoFacturaNoSeleccionado() {
    limpiarInfoFactura();

    $("#div_serie").hide();
    $("#div_autorizacion").hide();
    $("#div_secuencial").hide();
    $("#div_autorizacion_credito").hide();
    $("#si_no_factura").val("0");
    $("#si_no_factura").trigger("change");
}

function cambiarEstadoConFactura() {
    limpiarInfoFactura();
    limpiar_input();
    limpiarTablaProductos();
    $("#div_serie").show();
    $("#div_autorizacion").show();
    $("#div_secuencial").hide();
    $("#div_autorizacion_credito").hide();
}

function cambiarEstadoSinFactura() {
    limpiarInfoFactura();
    limpiar_input();
    limpiarTablaProductos();
    $("#div_secuencial").show();
    $("#div_autorizacion_credito").show();
    $("#div_serie").hide();
    $("#div_autorizacion").hide();
}

function limpiarInfoFactura() {
    $("#serie").val("");
    $("#autorizacion").val("");
    $("#secuencial").val("");
    $("#autorizacion_credito").val("");
    $("#id_factura_compra").val("");
}

function limpiarInfoNota() {
    $("#secuencial_nc").val("");
    $("#autorizacion_nc").val("");
    $("#fecha_registro_nc").val("");
    $("#fecha_emision_nc").val("");
}

function addCliente() {
    $.getScript("../proveedores/proveedores_ui_util/proveedores.js", function () {
        cmpAddCliente = new AddCliente();
        cmpAddCliente.contenedor = $("#form_cliente");
        cmpAddCliente.onGuardar = function (data) {
            if (!!data) {
                if (!!infofac) {
                    $("#btn_buscar_clave").click();
                }
                $("#dialog_form_cliente").dialog("close")
                buscarCliente(data).done(function (data) {
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

function obtenerCentrosCostos() {
    return $.ajax({
        url: "../centro_costos/retornar_centros_costos.php",
        method: "GET",
        dataType: "json"
    });
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
}

async function cargarFacturaDblclick(id) {
    valor = id;
    // agregar devolucion compra
    $("#comprobante").val(valor);
    $("#btnGuardar").attr("disabled", true);
    $("#btnModificar").attr("disabled", false);
    $("#btncargar").attr("disabled", true);
    $("#ruc_ci").attr("disabled", "disabled");
    $("#serie").attr("disabled", "disabled");
    $("#autorizacion").attr("disabled", "disabled");
    $("#observaciones").attr("disabled", "disabled");

    $("#list").jqGrid("clearGridData", true);
    $("#total_p").val("0.000");
    $("#total_p2").val("0.000");
    $("#iva").val("0.000");
    $("#tot").val("0.000");
    $("#total_px").val("0.000");
    $("#total_p2x").val("0.000");
    $("#ivax").val("0.000");
    $("#totx").val("0.000");
    $("#serie").val("");
    $("#autorizacion").val("");
    $("#secuencial").val("");
    $("#autorizacion_credito").val("");

    await $.getJSON('retornar_devolucion_compra2.php?com=' + valor, function (data) {
        var tama = data.length;
        var descuento = 0;
        var total = 0;
        var su = 0;
        var precio = 0;
        var multi = 0;
        var flotante = 0;
        var resultado = 0;

        if (tama != 0) {
            for (var i = 0; i < tama; i = i + 14) {
                desc = parseFloat(data[i + 5]);
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
                    total: data[i + 6],
                    precio_ux: precio.toFixed(2),
                    descuentox: desc.toFixed(2),
                    cal_desx: resultado.toFixed(2),
                    totalx: parseFloat(data[i + 6]).toFixed(2),
                    iva: data[i + 7],
                    incluye: data[i + 8],
                    valor_iva: data[i + 10],
                    tarifa: data[i + 9],
                    cod_impuesto: data[i + 11],
                    cod_tarifa: data[i + 12],
                };
                var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
            }
        }
        calcularTotalesTablaProductos();
    });

    await $.getJSON('retornar_devolucion_compra.php?com=' + valor, function (data) {
        var tama = data.length;
        if (tama != 0) {
            for (var i = 0; i < tama; i = i + 25) {
                $("#fecha_actual").val(data[i]);
                $("#hora_actual").val(data[i + 1]);
                $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                $("#id_proveedor").val(data[i + 4]);
                $("#tipo_docu").val(data[i + 5]);
                $("#ruc_ci").val(data[i + 6]);
                $("#empresa").val(data[i + 7]);
                $("#tipo_comprobante").val(data[i + 8]);
                $("#observaciones").val(data[i + 11]);
                /*   $("#total_p").val(data[i + 12]);
                  $("#total_p2").val(data[i + 13]); */
                $("#iva").val(data[i + 14]);
                $("#desc").val(data[i + 15]);
                /*  $("#total_px").val(parseFloat(data[i + 12]).toFixed(2));
                 $("#total_p2x").val(parseFloat(data[i + 13]).toFixed(2)); */
                $("#ivax").val(parseFloat(data[i + 14]).toFixed(2));
                $("#descx").val(parseFloat(data[i + 15]).toFixed(2));
                $("#tot").val(data[i + 16]);
                $("#totx").val(parseFloat(data[i + 16]).toFixed(2));
                $("#fecha_registro_nc").val(data[i + 17]);
                $("#secuencial_nc").val((data[i + 18]));
                $("#autorizacion_nc").val((data[i + 19]));
                $("#num_nota_debito").val((data[i + 21]));
                $("#id_devolucion_compra").val(data[i + 22]);

                $("#fecha_emision_nc").val(data[i + 24]);

                $("#si_no_factura")[0].disabled = true;
                if (data[i + 20] == "Si") {
                    $("#si_no_factura").val(1);
                    /*  $("#si_no_factura").trigger("change"); */

                    $("#serie").val(data[i + 9]);
                    $("#autorizacion").val(data[i + 10]);
                    $("#serie").attr("disabled", false);
                    $("#autorizacion").attr("disabled", false);

                } else {
                    $("#si_no_factura").val(2);
                    /* $("#si_no_factura").trigger("change"); */

                    $("#secuencial").val(data[i + 9]);
                    $("#autorizacion_credito").val(data[i + 10]);
                    $("#secuencial").attr("disabled", false);
                    $("#autorizacion_credito").attr("disabled", false);
                }
                if (data[i + 23] == "Pasivo") {
                    $("#estado").append($("<h3>").text("Anulada"));
                    $("#estado h3").css("color", "red");
                    $("#btnEliminar").attr("disabled", "disabled");
                    $("#btnModificar").attr("disabled", false);
                } else {
                    $("#estado h3").remove();
                    $("#btnEliminar").attr("disabled", "disabled");
                    $("#btnEliminar").attr("disabled", false);
                    $("#btnModificar").attr("disabled", false);
                }
            }
        }
    });

    await $.getJSON(
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
}