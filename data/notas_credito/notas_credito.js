$(document).on("ready", inicio);

var facturasCobrar = [];
var formatoNota = "";
var num_serie = "";

$(document).keydown(function (e) {
    var keycode = e.which || e.keyCode;
    console.log(keycode);
    if (keycode == 13) {
        if ($("#formaspago").val() == "otros") {
            //agregar_mixto();
            $("#btnAgregar_mixto").click();
        }
    }
});
function obtenerParametrosEmpresa() {
    fetch("obtener_parametros_empresa.php")
        .then(function (d) {
            return d.json();
        })
        .then(function (json) {
            formatoNota = json["formato_imperesion_nota_credito"];
        });
}

function evento(e) {
    e.preventDefault();
}
var calculoIVA = 0;
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

var dialogo = {
    autoOpen: false,
    resizable: false,
    width: 600,
    height: 420,
    modal: true
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
var dialogo3 = {
    autoOpen: false,
    resizable: false,
    width: 420,
    height: 180,
    modal: true,
    show: "explode",
    hide: "blind",
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
var dialogo22 =
{
    autoOpen: false,
    resizable: false,
    width: 630,
    height: 320,
    modal: true,
    // position: "top",
    show: "explode",
    hide: "blind",
    buttons: [
        {
            text: "Aceptar",
            //"class": 'cancelButtonClass',
            click: function () {
                llenarValoresPagosCxc();
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
        cargarTablaCuentasCxc();
    }

};
var dialogo10 = {
    autoOpen: false,
    resizable: false,
    width: 1000,
    height: 350,
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
function numFormatter(d) {
    return new Intl.NumberFormat("en-US", {
        minimumFractionDigits: d,
        maximumFractionDigits: d,
        useGrouping: false,
    });
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
    var serie = $("#num_nota_credito").val();
    for (var i = serie.length; i < 5; i++) {
        temp2 = temp2 + "0";
    }
    return temp2;
}
function autocompletar() {
    var temp = "";
    var serie = $("#num_nota_credito").val();
    for (var i = serie.length; i < 9; i++) {
        temp = temp + "0";
    }
    return temp;
}
function comprobar() {
    if ($("#num_nota_credito").val() == "") {
        $("#num_nota_credito").focus();
        alertify.error("Ingrese número de Nota de Credito");
    } else {
        var a = autocompletar($("#num_nota_credito").val());
        $("#num_nota_credito").val(a + "" + $("#num_nota_credito").val());
        $("#ruc_ci").focus();
    }
}
function comprobar1() {
    if ($("#num_nota_credito").val() == "") {
        $("#num_nota_credito").focus();
        alertify.error("Ingrese número de factura");
    } else {
        if ($("#id_cliente").val() == "" && $("#ruc_ci").val() != "") {
            nuevo_cliente();
        } else {
            if ($("#ruc_ci").val() == "") {
                $("#ruc_ci").focus();
                alertify.error("Indique un cliente");
            }
        }
    }
}
function guardar_serie_otros(fun) {
    var tam2 = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
    if ($("#formaspago").val() == "otros") {
        if (
            $("#formaspago").val() == "otros" &&
            $("#valor_factura_saldo").val() != "0.00"
        ) {
            alertify.error("Ingrese Valor ");
            console.log("cuatro");
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
        fun();
    }
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
                        alertify.error("Ingrese una cantidad válida");
                    } else {
                        if (parseInt($("#cantidad").val()) > parseInt($("#canti").val())) {
                            $("#cantidad").focus();
                            alertify.error("Error.. La cantidad ingresada es mayor a la de compra, límite:" + $("#canti").val());
                        } else {
                            $("#precio").focus();
                        }
                    }
                }
            }
        }
    }
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
function limpiar_input() {
    $("#codigo_barras").val("");
    $("#cod_producto").val("");
    $("#codigo").val("");
    $("#producto").val("");
    $("#cantidad").val("");
    $("#canti").val("");
    $("#precio").val("");
    $("#iva_producto").val("");
    $("#carga_series").val("");
    $("#descuento").val("");
    $("#incluye").val("");
    $("#estado").val("");
    $("#unidad_medida").val("");
    $("#cantidad_unidad").val("");
}

function entrar2() {

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
                    if ($("#cantidad").val() == "0") {
                        $("#cantidad").focus();
                        alertify.error("Ingrese una cantidad válida");
                    } else {
                        if (parseInt($("#cantidad").val()) > parseInt($("#canti").val())) {
                            $("#cantidad").focus();
                            alertify.error("Error.. La cantidad ingresada es mayor a la de compra, límite:" + $("#canti").val());
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
                                        incluye: $("#incluye").val(),
                                        cantidad_unidad: cantidad_unidad,
                                        unidad_medida: unidad_medida,
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
                                        suma = parseInt(can) + parseInt($("#cantidad").val());

                                        if (suma > parseInt($("#canti").val())) {
                                            $("#cantidad").focus();
                                            alertify.error("Error.. La cantidad ingresada es mayor a la de compra, límite:" + $("#canti").val());
                                        } else {
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
                                                incluye: $("#incluye").val(),
                                                cantidad_unidad: cantidad_unidad,
                                                unidad_medida: unidad_medida,

                                            };

                                            su = jQuery("#list").jqGrid('setRowData', $("#cod_producto").val(), datarow);
                                            limpiar_input();
                                        }
                                    } else {
                                        if (filas.length < 19) {
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
                                                incluye: $("#incluye").val(),
                                                cantidad_unidad: cantidad_unidad,
                                                unidad_medida: unidad_medida,
                                            };

                                            su = jQuery("#list").jqGrid('addRowData', $("#cod_producto").val(), datarow);
                                            limpiar_input();
                                        } else {
                                            alertify.error("Error... Alcanzo el límite máximo de Items");
                                        }
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
                                        if (dd['iva'] === "No") {
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
        if ($("#id_cliente").val() == "") {
            $("#ruc_ci").focus();
            alertify.error("Indique un cliente");
        } else {
            if ($("#tipo_comprobante").val() == "") {
                $("#tipo_comprobante").focus();
                alertify.error("Seleccione tipo comprobante");
            } else {
                if ($("#id_factura_venta").val() == "") {
                    $("#serie").focus();
                    alertify.error("Seleccione una factura");
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
        if (filas2.length < canti) {
            if (filas2.length == 0) {
                var datarow = {
                    id_serie: count = count + 1,
                    serie: $("#serie").val()
                };
                su = jQuery("#list2").jqGrid('addRowData', count, datarow);
                $("#serie").val("");
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
                } else {
                    $("#serie").val("");
                    alertify.alert("Error... La serie ya existe");
                }
            }
        } else {
            $("#serie").val("");
            $("#btnAgregar").attr("disabled", "disabled");
            alertify.alert("Error... Alcanzo el límite máximo");
        }
    } else {
        $("#serie").focus();
        alertify.alert("Error... Indique una serie");
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
                v1[i] = datos['serie'];
            }

            for (var i = 0; i < fil.length; i++) {
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
                        $("#descuento").focus();
                    }
                }
            });
        } else {
            alertify.alert("Error... Ingrese las series");
        }
    }
}

function guardar_devolucion() {


    var tam = jQuery("#list").jqGrid("getRowData");
    if ($("#formaspago").val() == "otros" && $("#validar_guardar_grid").val() == "") {
        alertify.error("Ingrese Valor ");
        console.log("uno");
        $("#valor_formas").focus();
    } else {
        if ($("#formaspago").val() == "otros" && $("#valor_factura_saldo").val() != "0.00") {
            alertify.error("Ingrese Valor ");
            console.log("dos");
            $("#valor_formas").focus();
        } else {
            $("#valor_cambioid").dialog("close");
            if ($("#num_nota_credito").val() == "") {
                $("#num_nota_credito").focus();
                alertify.error("Ingrese número de Nota de Crèdito");
            } else {
                var num_notas = ($("#num_nota_credito").val());
                $.ajax({
                    type: "POST",
                    url: "comparar_num_nota_credito.php",
                    data: "num_nota=" + num_notas,
                    success: function (data) {
                        var val = data;
                        if (val != 0) {
                            $("#num_nota_credito").val("");
                            $("#num_nota_credito").focus();
                            alertify.error("Error... La Nota de Crèdito ya existe, favor verificar el número que corresponda");
                            var res1 = parseInt(val.substr(7, 9));
                            res1 = res1 + 1;
                            $("#num_nota_credito").val(res1);
                            var a1 = autocompletar(res1);
                            var validado = a1 + "" + res1;
                            $("#num_nota_credito").val(validado);
                        } else {
                            if ($("#ruc_ci").val() == "") {
                                var a = autocompletar($("#num_nota_credito").val());
                                $("#num_nota_credito").val(a + "" + $("#num_nota_credito").val());
                                $("#ruc_ci").focus();
                                alertify.error("Indique un cliente");
                            } else {

                                if ($("#tipo_docu").val() === "") {
                                    $("#tipo_docu").focus();
                                    alertify.error("Seleccione tipo documento");
                                } else {
                                    if ($("#id_cliente").val() === "") {
                                        $("#ruc_ci").focus();
                                        alertify.error("Indique un cliente");
                                    } else {
                                        if ($("#tipo_comprobante").val() === "") {
                                            $("#tipo_comprobante").focus();
                                            alertify.error("Seleccione tipo comprobante");
                                        } else {
                                            if ($("#id_factura_venta").val() == "") {
                                                $("#serie").focus();
                                                alertify.error("Ingrese una factura válida");
                                            } else {
                                                if ($("#autorizacion").val() === "") {
                                                    alertify.error("Ingrese la autorización");
                                                    $("#autorizacion").focus();
                                                } else {
                                                    if (tam.length == 0) {
                                                        $("#codigo_barras").focus();
                                                        alertify.error("Error... Ingrese productos a la Nota de Crédito");
                                                    } else {
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

                                                        var a = autocompletar($("#num_nota_credito").val());
                                                        //TODO borrar comentado
                                                        /*if ($("#punto_ventaid").val() == 1) {
                                                         var num_serie = ("001" + "-" + "001");
                                                         }
                                                         if ($("#punto_ventaid").val() == 2) {
                                                         var num_serie = ("001" + "-" + "003");
                                                         }
                                                         if ($("#punto_ventaid").val() == 3) {
                                                         var num_serie = ("001" + "-" + "001");
                                                         }
                                                         if ($("#punto_ventaid").val() == 4) {
                                                         var num_serie = ("003" + "-" + "001");
                                                         }
                                                         if ($("#punto_ventaid").val() == 5) {
                                                         var num_serie = ("005" + "-" + "001");
                                                         }*/
                                                        var seriee = (a + "" + $("#num_nota_credito").val());
                                                        /* guardar_cobro_anticipo_cliente();
                                                        guardar_serie();*/
                                                        guardar_serie_otros(() => {
                                                            $.ajax({
                                                                type: "POST",
                                                                url: "guardar_notas_credito.php",
                                                                data: "id_cliente=" + $("#id_cliente").val() + "&id_factura_venta=" + $("#id_factura_venta").val() + "&comprobante=" + $("#comprobante").val() + "&fecha_actual=" + $("#fecha_actual").val() + "&hora_actual=" + $("#hora_actual").val() + "&tipo_comprobante=" + $("#tipo_comprobante").val() + "&serie=" + $("#serie").val() + "&tarifa0=" + $("#total_p").val() + "&tarifa12=" + $("#total_p2").val() + "&iva=" + $("#iva").val() + "&desc=" + $("#desc").val() + "&tot=" + $("#tot").val() + "&observaciones=" + $("#observaciones").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&num_nota_credito=" + seriee + "&num_serie=" + num_serie + "&tipo_motivo=" + $("#tipo_motivo").val() + "&campo6=" +
                                                                    string_v6 +
                                                                    "&campo7=" +
                                                                    string_v7,
                                                                dataType: "json",
                                                                success: function (data) {
                                                                    var val = data;
                                                                    if (data.estado == 2) {
                                                                        alertify.confirm("AUTORIZADO¿Desea Imprimir Comprobante?",
                                                                            function (e) {
                                                                                if (e) {
                                                                                    reenviar(data.id);
                                                                                    window.open(formatoNota + "?hoja=A4&id=" + data.id, '_blank');
                                                                                    location.reload();
                                                                                } else {
                                                                                    reenviar(data.id);
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
                    }
                });
            }
        }
    }
}
function reenviar(id) {
    $.ajax({
        type: "POST",
        url: "guardar_notas_credito.php",
        data: { reenviarcorreo: 'reenviarcorreo', id: id },
        //data: "id="+x,
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
        url: "guardar_notas_credito.php",
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
        url: "guardar_notas_credito.php",
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
function flecha_atras() {
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "devolucion_venta" + "&id_tabla=" + "id_devolucion_venta" + "&tipo=" + 1,
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#comprobante").val(val);
                var valor = $("#comprobante").val();

                // llamar Notas Crédito
                $("#btnGuardar").attr("disabled", true);
                $("#btnModificar").attr("disabled", true);

                $("#num_factura").attr("disabled", "disabled");
                $("#ruc_ci").attr("disabled", "disabled");
                $("#nombre_cliente").attr("disabled", "disabled");

                $("#codigo_barras").attr("disabled", "disabled");
                $("#codigo").attr("disabled", "disabled");
                $("#producto").attr("disabled", "disabled");
                $("#cantidad").attr("disabled", "disabled");
                $("#precio").attr("disabled", "disabled");
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

                $.getJSON('retornar_notas.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 18) {
                            $("#fecha_actual").val(data[i]);
                            $("#hora_actual").val(data[i + 1]);
                            $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                            $("#id_cliente").val(data[i + 4]);
                            $("#tipo_docu").val(data[i + 5]);
                            $("#ruc_ci").val(data[i + 6]);
                            $("#nombre_cli").val(data[i + 7]);
                            $("#telefono_cli").val(data[i + 8]);
                            $("#direccion_cli").val(data[i + 9]);
                            $("#tipo_comprobante").val(data[i + 10]);
                            $("#serie").val(data[i + 11]);
                            $("#observaciones").val(data[i + 12]);
                            $("#total_p").val(data[i + 13]);
                            $("#total_p2").val(data[i + 14]);
                            $("#iva").val(data[i + 15]);
                            $("#desc").val(data[i + 16]);
                            $("#tot").val(data[i + 17]);
                            $("#total_px").val(parseFloat(data[i + 13]).toFixed(2));
                            $("#total_p2x").val(parseFloat(data[i + 14]).toFixed(2));
                            $("#ivax").val(parseFloat(data[i + 15]).toFixed(2));
                            $("#descx").val(parseFloat(data[i + 16]).toFixed(2));
                            $("#totx").val(parseFloat(data[i + 17]).toFixed(2));
                        }
                    }
                });

                $.getJSON('retornar_notas2.php?com=' + valor, function (data) {
                    var tama = data.length;
                    var descuento = 0;
                    var total = 0;
                    var su = 0;
                    var precio = 0;
                    var multi = 0;
                    var flotante = 0;
                    var resultado = 0;

                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 11) {
                            desc = data[i + 5];
                            precio = (parseFloat(data[i + 4]));
                            multi = (parseFloat(data[i + 3]) * parseFloat(data[i + 4]));
                            descuento = ((multi * parseFloat(desc)) / 100);
                            flotante = parseFloat(descuento);
                            resultado = (Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2));
                            total = (multi - resultado);

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
                                descuentox: parseFloat(desc).toFixed(2),
                                cal_desx: resultado.toFixed(2),
                                totalx: parseFloat(data[i + 6]).toFixed(2),
                                iva: data[i + 7],
                                incluye: data[i + 8],
                                cantidad_unidad: data[i + 9],
                                unidad_medida: data[i + 10],
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        }
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
                // fin 

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
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "devolucion_venta" + "&id_tabla=" + "id_devolucion_venta" + "&tipo=" + 2,
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#comprobante").val(val);
                var valor = $("#comprobante").val();

                // llamar Notas Crédito
                $("#btnGuardar").attr("disabled", true);
                $("#btnModificar").attr("disabled", true);

                $("#num_factura").attr("disabled", "disabled");
                $("#ruc_ci").attr("disabled", "disabled");
                $("#nombre_cliente").attr("disabled", "disabled");

                $("#codigo_barras").attr("disabled", "disabled");
                $("#codigo").attr("disabled", "disabled");
                $("#producto").attr("disabled", "disabled");
                $("#cantidad").attr("disabled", "disabled");
                $("#precio").attr("disabled", "disabled");
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

                $.getJSON('retornar_notas.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 18) {
                            $("#fecha_actual").val(data[i]);
                            $("#hora_actual").val(data[i + 1]);
                            $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                            $("#id_cliente").val(data[i + 4]);
                            $("#tipo_docu").val(data[i + 5]);
                            $("#ruc_ci").val(data[i + 6]);
                            $("#nombre_cli").val(data[i + 7]);
                            $("#telefono_cli").val(data[i + 8]);
                            $("#direccion_cli").val(data[i + 9]);
                            $("#tipo_comprobante").val(data[i + 10]);
                            $("#serie").val(data[i + 11]);
                            $("#observaciones").val(data[i + 12]);
                            $("#total_p").val(data[i + 13]);
                            $("#total_p2").val(data[i + 14]);
                            $("#iva").val(data[i + 15]);
                            $("#desc").val(data[i + 16]);
                            $("#tot").val(data[i + 17]);
                            $("#total_px").val(parseFloat(data[i + 13]).toFixed(2));
                            $("#total_p2x").val(parseFloat(data[i + 14]).toFixed(2));
                            $("#ivax").val(parseFloat(data[i + 15]).toFixed(2));
                            $("#descx").val(parseFloat(data[i + 16]).toFixed(2));
                            $("#totx").val(parseFloat(data[i + 17]).toFixed(2));
                        }
                    }
                });

                $.getJSON('retornar_notas2.php?com=' + valor, function (data) {
                    var tama = data.length;
                    var descuento = 0;
                    var total = 0;
                    var su = 0;
                    var precio = 0;
                    var multi = 0;
                    var flotante = 0;
                    var resultado = 0;

                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 11) {
                            desc = data[i + 5];
                            precio = (parseFloat(data[i + 4]));
                            multi = (parseFloat(data[i + 3]) * parseFloat(data[i + 4]));
                            descuento = ((multi * parseFloat(desc)) / 100);
                            flotante = parseFloat(descuento);
                            resultado = (Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2));
                            total = (multi - resultado);

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
                                descuentox: parseFloat(desc).toFixed(2),
                                cal_desx: resultado.toFixed(2),
                                totalx: parseFloat(data[i + 6]).toFixed(2),
                                iva: data[i + 7],
                                incluye: data[i + 8],
                                cantidad_unidad: data[i + 9],
                                unidad_medida: data[i + 10],
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        }
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
                // fin
            } else {
                alertify.alert("No hay más registros superiores!!");
            }
        }
    });
}

function limpiar_nota() {
    location.reload();
}
function abrirDialogo_unidad() {
    var cod = $("#cod_producto").val();
    var tipo_comprobante = $("#tipo_comprobante").val();
    let num_fact_venta = $("#serie").val();
    if (cod == "") {
        alertify.alert("Error... Seleccione un producto");
    } else {
        $("#unidad_medida").append("<option></option>");
        $.getJSON("retornar_series_unidad.php?cod=" + cod +
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
function cargar_productos_factura() {
    var idf = $("#id_factura_venta").val();
    let tipo = $("#tipo_comprobante").val();
    if (idf == "") {
        $("#id_factura_venta").focus();
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

                for (var i = 0; i < tama; i = i + 13) {
                    var temp = 0;
                    var temp1 = 0;
                    if (data[i + 10] == "Si") {
                        if (parseInt(data[i + 3]) < 0) {
                            temp = 0;
                            temp1 = data[i + 4];
                        } else {
                            if (parseInt(data[i + 4]) > parseInt(data[i + 3])) {
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
                        total: total,
                        precio_ux: precio.toFixed(2),
                        descuentox: parseFloat(desc).toFixed(2),
                        cal_desx: resultado.toFixed(2),
                        totalx: total.toFixed(2),
                        iva: data[i + 8],
                        pendiente: temp1,
                        incluye: data[i + 9],
                        cantidad_unidad: data[i + 11],
                        unidad_medida: data[i + 12],
                    };
                    var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
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
                    if (dd['iva'] === "Si") {
                        if (dd['incluye'] == "No") {
                            subtotal = dd['total'];
                            sub1 = subtotal;
                            iva1 = (sub1 * 12) / 100;

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
                $("#totx").val(total_total.toFixed(2));
                $("#codigo_barras").focus();
            }
        });
    }
}
function limpiar_campo1() {
    if ($("#ruc_ci").val() == "") {
        $("#id_cliente").val("");
        $("#nombre_cli").val("");
        $("#direccion_cli").val("");
        $("#telefono_cli").val("");
        $("#id_factura_venta").val("");
        $("#serie").val("");
        num_serie = "";
    }
}

function limpiar_campo2() {
    if ($("#serie").val() == "") {
        $("#id_factura_venta").val("");
        $("#cod_producto").val("");
        $("#codigo_barras").val("");
        $("#codigo").val("");
        $("#producto").val("");
        $("#cantidad").val("");
        $("#precio").val("");
        $("#canti").val("");
        $("#descuento").val("");
        $("#iva_producto").val("");
        $("#carga_series").val("");
        $("#estado").val("");
        $("#incluye").val("");
        $("#cantidad_unidad").val("");
        $("#unidad_medida").val("");
        num_serie = "";
    }
}
function limpiar_campo3() {
    if ($("#codigo").val() == "") {
        $("#codigo_barras").val("");
        $("#cod_producto").val("");
        $("#producto").val("");
        $("#cantidad").val("");
        $("#precio").val("");
        $("#canti").val("");
        $("#descuento").val("");
        $("#iva_producto").val("");
        $("#carga_series").val("");
        $("#estado").val("");
        $("#incluye").val("");
        $("#cantidad_unidad").val("");
        $("#unidad_medida").val("");
    }
}

function limpiar_campo4() {
    if ($("#producto").val() == "") {
        $("#codigo_barras").val("");
        $("#cod_producto").val("");
        $("#codigo").val("");
        $("#cantidad").val("");
        $("#precio").val("");
        $("#canti").val("");
        $("#descuento").val("");
        $("#iva_producto").val("");
        $("#carga_series").val("");
        $("#estado").val("");
        $("#incluye").val("");
    }
}
function anular_factura() {
    $("#clave_permiso").dialog("open");
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
    //    var v1 = new Array();
    //    var v2 = new Array();
    //    var string_v1 = "";
    //    var string_v2 = "";
    //    var fil = jQuery("#list").jqGrid("getRowData");
    //
    //    for (var i = 0; i < fil.length; i++) {
    //        var datos = fil[i];
    //        v1[i] = datos['cod_producto'];
    //        v2[i] = datos['cantidad'];
    //    }
    //    for (i = 0; i < fil.length; i++) {
    //        string_v1 = string_v1 + "|" + v1[i];
    //        string_v2 = string_v2 + "|" + v2[i];
    //    }
    $("#btnAceptar").attr("disabled", true);
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
    var fil = jQuery("#list").jqGrid("getRowData");
    for (var i = 0; i < fil.length; i++) {
        var datos = fil[i];
        v1[i] = datos["cod_producto"];
        v2[i] = datos["cantidad"];
        v3[i] = datos["precio_u"];
        v4[i] = datos["descuento"];
        v5[i] = datos["total"];
        v6[i] = datos["pendiente"];
        v7[i] = datos["cantidad_unidad"];
        v8[i] = datos["unidad_medida"];
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

    $.ajax({
        type: "POST",
        url: "anular_notas_credito.php",
        data:
            "comprobante=" +
            $("#comprobante").val() +
            "&tipo_venta=" +
            $("#tipo_comprobante").val() +
            "&campo1=" +
            string_v1 +
            "&campo2=" +
            string_v2 +
            "&fecha_anulacion=" +
            $("#fecha_actual").val() +
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
            "&id_cliente=" +
            $("#id_cliente").val() +
            "&num_factura=" +
            $("#serie").val() +
            "&anulacionComentario=" +
            $("#anulacionComentario").val(),

        success: function (data) {
            var val = data;
            if (val == 1) {
                alertify.alert("Factura Anulada Correctamente", function () {
                    var parafd = $("#anulacionComentario").val().replace(/%/g, "%25");
                    parafd = parafd.replace(/&/g, "%26");
                    //                    var myWindow = window.open("../../reportes/factura_venta_00_1.php?hoja=A4&id_factura=" + $('#id_factura_venta').val() + "&comentarioanul=" + parafd, '_blank');
                    myWindow.focus();
                    myWindow.print();
                    location.reload();
                });
                $("#seguro").dialog("close");
                $("#clave_permiso").dialog("close");
            }
        },
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
function limpiar_datos() {
    $("#ruc_ci").val("");
    $("#nombre_cli").val("");
    $("#telefono_cli").val("");
    $("#direccion_cli").val("");
    $("#id_cliente").val("");
    $("#serie").val("");
    $("#id_factura_venta").val("");
    num_serie = "";
}
function formaPagoCambio() {
    /*  $("#formaspago").change(function () {
         console.log($("#formaspago").val());
         var tam2 = jQuery("#list").jqGrid("getRowData");
         if ($("#formaspago").val() == "Contado") {
             disableFormasMixtoForm();
             $("#adelanto").attr("disabled", "disabled");
             $("#adelanto").val("");
             $("#valor_factura").val("");
             $("#meses").attr("disabled", "disabled");
             $("#meses").val("");
             $("#cuotas").attr("disabled", "disabled");
             $("#cuotas").children().remove().end();
         } else {
             if ($("#formaspago").val() == "otros") {
                 enableFormasMixtoForm();
                 if (tam2.length > 0) {
                     $('.nav-tabs a[href="#tab_3"]').tab("show");
                     $("#formaspago_mixto").attr("disabled", false);
                 } else {
                     disableFormasMixtoForm();
                     alertify.error("Ingrese Productos");
                 }
             }
         }
     }); */
    $("#formaspago").change(function () {
        var tam2 = jQuery("#list").jqGrid("getRowData");
        if ($("#formaspago").val() == "Contado") {
            disableFormasMixtoForm();
            $("#adelanto").attr("disabled", "disabled");
            $("#adelanto").val("");
            $("#valor_factura").val("");
            $("#meses").attr("disabled", "disabled");
            $("#meses").val("");
            $("#cuotas").attr("disabled", "disabled");
            $("#cuotas").children().remove().end();
        } else {
            if ($("#formaspago").val() == "otros") {
                enableFormasMixtoForm();
                if (tam2.length > 0 && $("#ruc_ci").val() != "9999999999999") {
                    $('.nav-tabs a[href="#tab_3"]').tab("show");
                    $("#formaspago_mixto").attr("disabled", false);
                    $("#valor_factura").val($("#totx").val());
                } else {
                    disableFormasMixtoForm();
                    $("#contado_form").prop("selected", true);
                    alertify.error(
                        "Error..Ingrese Productos y el Ruc debe ser diferente a consumidor final"
                    );
                }
            }
        }
    });
}
function guardar_cobro_anticipo_cliente() {
    var tam2 = jQuery("#listPagoreten_mixto_anti").jqGrid("getRowData");
    if ($("#formaspago").val() == "otros") {
        if ($("#formaspago").val() == "otros" && $("#valor_factura_saldo").val() != "0.00") {
            alertify.error("Ingrese Valor ");
            console.log("tres");
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
                    v2[i] = datos['id_anticipo_clientes'];
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
                    url: "guardar_cobro_anticipo_cli.php",
                    data: "id_factura_venta=" + $("#id_factura_venta").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&campo6=" + string_v6 + "&campo7=" + string_v7 + "&fecha_actual=" + $("#fecha_actual").val(),
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
function inicio() {
    iniDialogCuentas();
    disableFormasMixtoForm();
    $("#formaspago_mixto").change(function () {
        var tam2 = jQuery("#list").jqGrid("getRowData");

        if ($("#formaspago_mixto").val() == "Contado"
            || $("#formaspago_mixto").val() == "Cheque"
            || $("#formaspago_mixto").val() == "Transferencia"
            || $("#formaspago_mixto").val() == "CXP"

        ) {
            if ($("#formaspago_mixto").val() == "Cheque"
                || $("#formaspago_mixto").val() == "Transferencia"
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
        } else {
            if ($("#formaspago_mixto").val() == "CXC") {
                $("#cuenta_contable").attr("disabled", true);
                $("#btnCuenta").attr("disabled", true);
                $("#cuenta_contable").val("");
                $("#idCuenta").val("");
                $('#fecha_vencimiento').hide();
                $("#valor_formas").attr("disabled", true);
                $("#buscar_anticipo").dialog("open");
                $('#grid_container_pago_reten_anti').show();
            }

        }

        $("#list44").jqGrid("setGridParam", {
            url: `xmlPlanCuentas.php?cuenta=` + $("#formaspago_mixto").val(),
            page: 1,
        }).trigger("reloadGrid");
    });

    $("#fecha_vencimiento").hide();
    formaPagoCambio();
    listaPagoRetencion();
    $("#btnEstados").click(function () {
        $("#buscar_estados").dialog("open");
    });
    $("#unidad_medida").change(() => {
        if ($("#cod_producto").val() !== "") {
            let cod_producto = $("#cod_producto").val();
            let unidad_medida = $("#unidad_medida").val();
            let num_fact_venta = $("#serie").val();
            let tipo_comprobante = $("#tipo_comprobante").val();
            let precio = "MINORISTA";
            $.getJSON(
                "search_um.php?cod_producto=" +
                cod_producto +
                "&unidad_medida=" +
                unidad_medida +
                "&precio=" +
                precio +
                "&num_fac_venta=" +
                num_fact_venta +
                "&tipo_comprobante=" +
                tipo_comprobante,
                (data) => {
                    $("#precio").val(data[2]);


                    $("#cantidad_unidad").val(data[1]);
                }
            );
            $("#cantidad").focus();
        }
    });
    $("#btnEstados").click(function () {
        $("#buscar_estados").dialog("open");
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

    $("[data-mask]").inputmask();
    alertify.set({ delay: 5000 });

    // para hora
    show();
    // fin

    // botones
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
    $("#btnModificar").click(function (e) {
        e.preventDefault();
    });
    $("#btnProductos_factura").click(function (e) {
        e.preventDefault();
    });
    $("#btnCancelar").click(function (e) {
        e.preventDefault();
    });
    $("#btnNuevo").click(function (e) {
        e.preventDefault();
    });
    $("#btnAceptar").click(function (e) {
        e.preventDefault();
    });
    $("#btnAceptar").on("click", aceptar);
    $("#btnAcceder").click(function (e) {
        e.preventDefault();
    });
    $("#btnAgregar_mixto").click(function (e) {
        e.preventDefault();
        agregar_mixto();
    });

    $("#btnImprimir").click(function (e) {
        $.ajax({
            type: "POST",
            url: "../../procesos/validacion.php",
            data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "devolucion_venta" + "&id_tabla=" + "id_devolucion_venta" + "&tipo=" + 1,
            success: function (data) {
                var val = data;
                if (val != "") {

                    window.open(formatoNota + "?hoja=A4&id=" + $("#comprobante").val(), '_blank');
                } else {
                    alertify.alert("Nota de Crédito no creada!!");
                }
            }
        });
    });
    /* $("#formaspago").change(function () {
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
 */
    $("#btnAgregar").on("click", agregar);
    //    $("#btnGuardarSeries").on("click", guardar_serie);
    $("#btnGuardar").on("click", guardar_devolucion);
    $("#btnNuevo").on("click", limpiar_nota);
    $("#btnProductos_factura").on("click", cargar_productos_factura);
    $("#btnAtras").on("click", flecha_atras);
    $("#btnAdelante").on("click", flecha_siguiente);
    //////////////////////////
    /////////////////////////// 
    $("#buscar_notas_credito").dialog(dialogo2);
    $("#btnBuscar").click(function (e) {
        e.preventDefault();
        $("#buscar_notas_credito").dialog("open");
    });
    /////////////////////////// 
    /////////////////////////////////
    $("#cantidad").on("keypress", punto);
    //    $("#cantidad").validCampoFranz("0123456789");
    $("#autorizacion").validCampoFranz("0123456789");
    $("#serie").validCampoFranz("0123456789");
    $("#serie").attr("disabled", "disabled");
    $("#serie").attr("maxlength", "17");
    $("#autorizacion").attr("disabled", "disabled");
    $("#descuento").validCampoFranz("0123456789");
    $("#precio").on("keypress", punto);
    ///////////////////////////////////////////
    $("#btnAnular").on("click", anular_factura);
    $("#seguro").dialog(dialogo4);
    $("#clave_permiso").dialog(dialogo3);
    $("#btnAcceder").on("click", validar_acceso);
    // eventos
    $("#ruc_ci").on("keyup", limpiar_campo1);
    $("#serie").on("keyup", limpiar_campo2);
    $("#codigo").on("keyup", limpiar_campo3);
    $("#producto").on("keyup", limpiar_campo4);
    $("#codigo").on("keypress", enter);
    $("#producto").on("keypress", enter);
    $("#cantidad").on("keypress", enter);
    $("#precio").on("keypress", enter2);
    $("#ruc_ci").on("keypress", enter3);
    $("#empresa").on("keypress", enter3);
    $("#serie").on("keypress", enter3);
    // fin
    $("#buscar_estados").dialog(dialogo10);
    // atributos
    $("#ruc_ci").attr("disabled", "disabled");
    $("#empresa").attr("disabled", "disabled");
    $("#adelanto").attr("disabled", "disabled");
    $("#meses").attr("disabled", "disabled");
    $("#cuotas").attr("disabled", "disabled");
    $("#buscar_anticipo").dialog(dialogo22);
    // fin

    // buscar cliente
    $("#tipo_docu").change(function () {
        var tipo = $("#tipo_docu").val();

        if (tipo == "Cedula") {
            $("#ruc_ci").validCampoFranz("0123456789");
            $("#ruc_ci").removeAttr("disabled");
            $("#serie").removeAttr("disabled");
            $("#ruc_ci").attr("maxlength", "10");
            $("#ruc_ci").autocomplete({
                source: "buscar_cliente.php?tipo_docu=" + tipo,
                minLength: 1,
                focus: function (event, ui) {
                    $("#ruc_ci").val(ui.item.value);
                    $("#nombre_cli").val(ui.item.nombre_cli);
                    $("#telefono_cli").val(ui.item.telefono_cli);
                    $("#direccion_cli").val(ui.item.direccion_cli);
                    $("#id_cliente").val(ui.item.id_cliente);
                    $("#correo").val(ui.item.correo_cli);
                    return false;
                },
                select: function (event, ui) {
                    $("#ruc_ci").val(ui.item.value);
                    $("#nombre_cli").val(ui.item.nombre_cli);
                    $("#telefono_cli").val(ui.item.telefono_cli);
                    $("#direccion_cli").val(ui.item.direccion_cli);
                    $("#id_cliente").val(ui.item.id_cliente);
                    $("#correo").val(ui.item.correo_cli);
                    return false;
                }

            }).data("ui-autocomplete")._renderItem = function (ul, item) {
                return $("<li>")
                    .append("<a>" + item.value + "</a>")
                    .appendTo(ul);
            };
            limpiar_datos();
        } else {
            if (tipo == "Ruc") {
                $("#ruc_ci").validCampoFranz("0123456789");
                $("#ruc_ci").removeAttr("disabled");
                $("#serie").removeAttr("disabled");
                $("#ruc_ci").removeAttr("maxlength");
                $("#ruc_ci").attr("maxlength", "13");
                $("#ruc_ci").autocomplete({
                    source: "buscar_cliente.php?tipo_docu=" + tipo,
                    minLength: 1,
                    focus: function (event, ui) {
                        $("#ruc_ci").val(ui.item.value);
                        $("#nombre_cli").val(ui.item.nombre_cli);
                        $("#telefono_cli").val(ui.item.telefono_cli);
                        $("#direccion_cli").val(ui.item.direccion_cli);
                        $("#id_cliente").val(ui.item.id_cliente);
                        $("#correo").val(ui.item.correo_cli);
                        return false;
                    },
                    select: function (event, ui) {
                        $("#ruc_ci").val(ui.item.value);
                        $("#nombre_cli").val(ui.item.nombre_cli);
                        $("#telefono_cli").val(ui.item.telefono_cli);
                        $("#direccion_cli").val(ui.item.direccion_cli);
                        $("#id_cliente").val(ui.item.id_cliente);
                        $("#correo").val(ui.item.correo_cli);
                        return false;
                    }

                }).data("ui-autocomplete")._renderItem = function (ul, item) {
                    return $("<li>")
                        .append("<a>" + item.value + "</a>")
                        .appendTo(ul);
                };
                limpiar_datos();
            } else {
                if (tipo == "Pasaporte") {
                    $("#ruc_ci").unbind("keypress");
                    $("#ruc_ci").removeAttr("disabled");
                    $("#serie").removeAttr("disabled");
                    $("#ruc_ci").attr("maxlength", "30");
                    $("#ruc_ci").autocomplete({
                        source: "buscar_cliente.php?tipo_docu=" + tipo,
                        minLength: 1,
                        focus: function (event, ui) {
                            $("#ruc_ci").val(ui.item.value);
                            $("#nombre_cli").val(ui.item.nombre_cli);
                            $("#telefono_cli").val(ui.item.telefono_cli);
                            $("#direccion_cli").val(ui.item.direccion_cli);
                            $("#id_cliente").val(ui.item.id_cliente);
                            $("#correo").val(ui.item.correo_cli);
                            return false;
                        },
                        select: function (event, ui) {
                            $("#ruc_ci").val(ui.item.value);
                            $("#nombre_cli").val(ui.item.nombre_cli);
                            $("#telefono_cli").val(ui.item.telefono_cli);
                            $("#direccion_cli").val(ui.item.direccion_cli);
                            $("#id_cliente").val(ui.item.id_cliente);
                            $("#correo").val(ui.item.correo_cli);
                            return false;
                        }
                    }).data("ui-autocomplete")._renderItem = function (ul, item) {
                        return $("<li>")
                            .append("<a>" + item.value + "</a>")
                            .appendTo(ul);
                    };
                    limpiar_datos();
                }
            }
        }
    });
    // fin
    if ($("#num_oculto").val() == "") {
        $("#num_nota_credito").val("");
    } else {
        var str = $("#num_oculto").val();
        var res = parseInt(str.substr(4, 16));
        res = res + 1;

        $("#num_nota_credito").val(res);
        var a = autocompletar(res);
        var validado = a + "" + res;
        $("#num_nota_credito").val(validado);
    }
    // buscar facturas
    $("#serie").keyup(function (e) {
        var id = $("#id_cliente").val();

        if (id == "") {
            $("#ruc_ci").focus();
            $("#serie").val("");
            alertify.error("Error... Seleccione un cliente");
        } else {
            $("#serie").autocomplete({
                source: "buscar_facturas.php?id=" + id,
                minLength: 1,
                focus: function (event, ui) {
                    $("#serie").val(ui.item.value);
                    $("#id_factura_venta").val(ui.item.id_factura_venta);
                    num_serie = ui.item.num_serie;
                    return false;
                },
                select: function (event, ui) {
                    $("#serie").val(ui.item.value);
                    $("#id_factura_venta").val(ui.item.id_factura_venta);
                    num_serie = ui.item.num_serie;
                    return false;
                }

            }).data("ui-autocomplete")._renderItem = function (ul, item) {
                return $("<li>")
                    .append("<a>" + item.value + "</a>")
                    .appendTo(ul);
            };
        }
    });
    // Fin

    // buscar producto codigo barras
    $("#codigo_barras").change(function (e) {

        var num_notas = ($("#num_nota_credito").val());
        $.ajax({
            type: "POST",
            url: "comparar_num_nota_credito.php",
            data: "num_nota=" + num_notas,
            success: function (data) {
                var val = data;
                if (val != 0) {
                    $("#num_nota_credito").val("");
                    $("#num_nota_credito").focus();
                    alertify.error("Error... La Nota de Crèdito ya existe, favor verificar el número que corresponda");
                    var res1 = parseInt(val.substr(7, 9));
                    res1 = res1 + 1;
                    $("#num_nota_credito").val(res1);
                    var a1 = autocompletar(res1);
                    var validado = a1 + "" + res1;
                    $("#num_nota_credito").val(validado);

                }
            }
        });

        var codigo = $("#codigo_barras").val();
        var ids = $("#id_factura_venta").val();
        var cod = $("#codigo_barras").val();
        $.getJSON('search.php?codigo_barras=' + codigo + '&ids=' + ids + "&cod=" + cod, function (data) {
            var tama = data.length;
            if (tama != 0) {
                for (var i = 0; i < tama; i = i + 11) {
                    $("#cod_producto").val(data[i]);
                    $("#codigo").val(data[i + 1]);
                    $("#producto").val(data[i + 3]);
                    $("#precio").val(data[i + 4]);
                    $("#canti").val(data[i + 5]);
                    $("#descuento").val(data[i + 6]);
                    $("#iva_producto").val(data[i + 7]);
                    $("#carga_series").val(data[i + 8]);
                    $("#estado").val(data[i + 9]);
                    $("#incluye").val(data[i + 10]);
                    $("#cantidad").focus();
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
                $("#estado").val("");
                $("#cod_producto").val("");
                $("#incluye").val("");
                $("#cantidad").val("");
                alertify.error("Producto no ingresado");
                $("#codigo_barras").val("");
            }
        });
    });
    // Fin   

    // buscar productos codigo
    $("#codigo").keyup(function () {

        var num_notas = ($("#num_nota_credito").val());
        $.ajax({
            type: "POST",
            url: "comparar_num_nota_credito.php",
            data: "num_nota=" + num_notas,
            success: function (data) {
                var val = data;
                if (val != 0) {
                    $("#num_nota_credito").val("");
                    $("#num_nota_credito").focus();
                    alertify.error("Error... La Nota de Crèdito ya existe, favor verificar el número que corresponda");
                    var res1 = parseInt(val.substr(7, 9));
                    res1 = res1 + 1;
                    $("#num_nota_credito").val(res1);
                    var a1 = autocompletar(res1);
                    var validado = a1 + "" + res1;
                    $("#num_nota_credito").val(validado);

                }
            }
        });
        $("#codigo").autocomplete({
            source: "buscar_codigo.php?ids=" + $("#id_factura_venta").val(),
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
                $("#estado").val(ui.item.estado);
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
                $("#estado").val(ui.item.estado);
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
    });
    // Fin

    // buscar productos articulo
    $("#producto").keyup(function () {
        var num_notas = ($("#num_nota_credito").val());
        $.ajax({
            type: "POST",
            url: "comparar_num_nota_credito.php",
            data: "num_nota=" + num_notas,
            success: function (data) {
                var val = data;
                if (val != 0) {
                    $("#num_nota_credito").val("");
                    $("#num_nota_credito").focus();
                    alertify.error("Error... La Nota de Crèdito ya existe, favor verificar el número que corresponda");
                    var res1 = parseInt(val.substr(7, 9));
                    res1 = res1 + 1;
                    $("#num_nota_credito").val(res1);
                    var a1 = autocompletar(res1);
                    var validado = a1 + "" + res1;
                    $("#num_nota_credito").val(validado);

                }
            }
        });
        $("#producto").autocomplete({
            source: "buscar_producto.php?ids=" + $("#id_factura_venta").val(),
            minLength: 1,
            focus: function (event, ui) {
                $("#codigo_barras").val(ui.item.codigo_barras);
                $("#producto").val(ui.item.value);
                $("#codigo").val(ui.item.codigo);
                $("#precio").val(ui.item.precio);
                $("#canti").val(ui.item.canti);
                $("#descuento").val(ui.item.descuento);
                $("#iva_producto").val(ui.item.iva_producto);
                $("#carga_series").val(ui.item.carga_series);
                $("#estado").val(ui.item.estado);
                $("#cod_producto").val(ui.item.cod_producto);
                $("#incluye").val(ui.item.incluye);
                abrirDialogo_unidad();
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
                $("#estado").val(ui.item.estado);
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
    });
    // Fin 

    // fechas
    $('#fecha_actual').datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');

    // tabla local
    jQuery("#list").jqGrid({
        datatype: "local",
        colNames: ['', 'ID', 'Código', 'Detalle', 'Cantidad', 'Precio. Ux', 'Descuentox', 'Calculadox', 'Totalx', 'Precio. U', 'Descuento', 'Calculado', 'Total', 'Iva', 'Incluye', 'Cantidad Unidad', 'Unidad Medida'],
        colModel: [
            { name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: { keys: false, delbutton: true, editbutton: false } },
            { name: 'cod_producto', index: 'cod_producto', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },
            { name: 'codigo', index: 'codigo', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'detalle', index: 'detalle', editable: false, frozen: true, editrules: { required: true }, align: 'center', width: 290 },
            { name: 'cantidad', index: 'cantidad', editable: false, frozen: true, editrules: { required: true }, align: 'center', width: 70 },
            { name: 'precio_u', index: 'precio_u', hidden: true, editable: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: 110 },
            { name: 'descuento', index: 'descuento', hidden: true, editable: false, frozen: true, editrules: { required: true }, align: 'center', width: 70 },
            { name: 'cal_des', index: 'cal_des', hidden: true, editable: false, frozen: true, editrules: { required: true }, align: 'center', width: 90 },
            { name: 'total', index: 'total', hidden: true, editable: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: 110 },
            { name: 'precio_ux', index: 'precio_ux', editable: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: 110 },
            { name: 'descuentox', index: 'descuentox', editable: false, frozen: true, editrules: { required: true }, align: 'center', width: 70 },
            { name: 'cal_desx', index: 'cal_desx', editable: false, hidden: false, frozen: true, editrules: { required: true }, align: 'center', width: 90 },
            { name: 'totalx', index: 'totalx', editable: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: 110 },
            { name: 'iva', index: 'iva', align: 'center', width: 100, hidden: false },
            { name: 'incluye', index: 'incluye', editable: false, hidden: false, frozen: true, editrules: { required: true }, align: 'center', width: 90 },
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
        //FRANCIS//        
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



                if (descuento_grid != "0") {
                    console.log(":1:");
                    desc = descuento_grid;
                    precio = parseFloat(precio_grid);
                    multi = parseFloat(val) * parseFloat(precio);
                    descuento = (multi * parseFloat(desc)) / 100;
                    flotante = parseFloat(descuento);
                    resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                    total = multi - resultado;
                    if (ret.iva == "Si") {
                        console.log(":2:");
                        iva1 = (ret.precio_ux * calculoIVA) / 100;
                        iva_pventa = iva1 + parseFloat(ret.precio_ux);
                        result = ret.cantidad * numFormatter(2).format(iva_pventa);
                    } else {
                        console.log(":2:");
                        result = 0;
                    }

                    jQuery("#list").jqGrid("setRowData", rowid, {
                        totalx: numFormatter(2).format(total),
                        total: numFormatter(2).format(total),
                        precio_u: numFormatter(2).format(ret.precio_ux),
                        precio_ux: numFormatter(2).format(ret.precio_ux),
                        cal_des: resultado,
                        cal_desx: resultado,
                    });
                    $("#codigo_barras").focus();
                } else {
                    console.log(":3:");
                    desc = descuento_grid;
                    precio = parseFloat(precio_grid);
                    multi = parseFloat(val) * parseFloat(precio);
                    descuento = (multi * parseFloat(desc)) / 100;
                    flotante = parseFloat(descuento);
                    resultado = Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2);
                    total = parseFloat(multi);
                    console.log(ret.precio_u);
                    if (ret.iva == "Si") {
                        console.log(":4:");
                        iva1 = (ret.precio_u * calculoIVA) / 100;
                        iva_pventa = iva1 + parseFloat(ret.precio_u);
                        result = ret.cantidad * numFormatter(2).format(iva_pventa);
                    } else {
                        console.log(":4:");
                        result = 0;
                    }
                    jQuery("#list").jqGrid("setRowData", rowid, {
                        totalx: numFormatter(2).format(total),
                        total: numFormatter(2).format(total),
                        precio_u: numFormatter(2).format(ret.precio_ux),
                        precio_ux: numFormatter(2).format(ret.precio_ux),
                        cal_des: resultado,
                        cal_desx: resultado,
                    });
                    $("#codigo_barras").focus();
                    $("#codigo_barras").select();
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
                        console.log(":5:");
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
                            console.log(":6:");
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
                        console.log(":8:");
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
            }
        },
    });

    // buscar notas credito
    jQuery("#list2").jqGrid({
        url: 'xmlBuscarNotasCredito.php',
        datatype: 'xml',
        colNames: ['ID', 'IDENTIFICACIÓN', 'CLIENTE', 'FACTURA NRO.', 'MONTO TOTAL', 'FECHA'],
        colModel: [
            { name: 'id_devolucion_venta', index: 'id_factura_venta', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },
            { name: 'identificacion', index: 'identificacion', editable: false, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 150 },
            { name: 'nombres_cli', index: 'nombres_cli', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 200 },
            { name: 'num_serie', index: 'num_serie', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 200 },
            { name: 'total_venta', index: 'total_venta', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'fecha_nota', index: 'fecha_nota', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
        ],
        rowNum: 30,
        width: 750,
        height: 220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager2'),
        sortname: 'id_devolucion_venta',
        sortorder: 'asc',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
            jQuery('#list2').jqGrid('restoreRow', id);

            if (id) {
                var ret = jQuery("#list2").jqGrid('getRowData', id);
                var valor = ret.id_devolucion_venta;

                //agregregar notas credito
                $("#comprobante").val(valor);
                $("#btnGuardar").attr("disabled", true);
                $("#btnModificar").attr("disabled", true);

                $("#num_factura").attr("disabled", "disabled");
                $("#ruc_ci").attr("disabled", "disabled");
                $("#nombre_cliente").attr("disabled", "disabled");

                $("#codigo_barras").attr("disabled", "disabled");
                $("#codigo").attr("disabled", "disabled");
                $("#producto").attr("disabled", "disabled");
                $("#cantidad").attr("disabled", "disabled");
                $("#precio").attr("disabled", "disabled");
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

                $.getJSON('retornar_notas.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 18) {
                            $("#fecha_actual").val(data[i]);
                            $("#hora_actual").val(data[i + 1]);
                            $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                            $("#id_cliente").val(data[i + 4]);
                            $("#tipo_docu").val(data[i + 5]);
                            $("#ruc_ci").val(data[i + 6]);
                            $("#nombre_cli").val(data[i + 7]);
                            $("#telefono_cli").val(data[i + 8]);
                            $("#direccion_cli").val(data[i + 9]);
                            $("#tipo_comprobante").val(data[i + 10]);
                            $("#serie").val(data[i + 11]);
                            $("#observaciones").val(data[i + 12]);
                            $("#total_p").val(data[i + 13]);
                            $("#total_p2").val(data[i + 14]);
                            $("#iva").val(data[i + 15]);
                            $("#desc").val(data[i + 16]);
                            $("#tot").val(data[i + 17]);
                            $("#total_px").val(parseFloat(data[i + 13]).toFixed(2));
                            $("#total_p2x").val(parseFloat(data[i + 14]).toFixed(2));
                            $("#ivax").val(parseFloat(data[i + 15]).toFixed(2));
                            $("#descx").val(parseFloat(data[i + 16]).toFixed(2));
                            $("#totx").val(parseFloat(data[i + 17]).toFixed(2));
                        }
                    }
                });

                $.getJSON('retornar_notas2.php?com=' + valor, function (data) {
                    var tama = data.length;

                    var descuento = 0;
                    var total = 0;
                    var su = 0;
                    var precio = 0;
                    var multi = 0;
                    var flotante = 0;
                    var resultado = 0;


                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 11) {
                            desc = data[i + 5];
                            precio = parseFloat(data[i + 4]);

                            multi = parseFloat(data[i + 3]) * parseFloat(data[i + 4]);
                            descuento = (multi * parseFloat(desc)) / 100;
                            flotante = parseFloat(descuento);
                            resultado = (Math.round(flotante * Math.pow(10, 2)) / Math.pow(10, 2));
                            total = (multi - resultado);

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
                                descuentox: parseFloat(desc).toFixed(2),
                                cal_desx: resultado.toFixed(2),
                                totalx: parseFloat(data[i + 6]).toFixed(2),
                                iva: data[i + 7],
                                incluye: data[i + 8],
                                cantidad_unidad: data[i + 9],
                                unidad_medida: data[i + 10],
                            };

                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);

                        }
                    }
                });
                // Fin

                $("#buscar_notas_credito").dialog("close");
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

    jQuery("#list2").jqGrid('navButtonAdd', '#pager2', {
        caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
            jQuery('#list2').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list2").jqGrid('getRowData', id);
                var valor = ret.id_devolucion_venta;
                /////////////agregregar notas credito////////
                $("#comprobante").val(ret.id_devolucion_venta);
                $("#btnGuardar").attr("disabled", true);
                $("#btnModificar").attr("disabled", true);

                $("#num_factura").attr("disabled", "disabled");
                $("#ruc_ci").attr("disabled", "disabled");
                $("#nombre_cliente").attr("disabled", "disabled");

                $("#codigo_barras").attr("disabled", "disabled");
                $("#codigo").attr("disabled", "disabled");
                $("#producto").attr("disabled", "disabled");
                $("#cantidad").attr("disabled", "disabled");
                $("#precio").attr("disabled", "disabled");
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

                $.getJSON('retornar_notas.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 18) {
                            $("#fecha_actual").val(data[i]);
                            $("#hora_actual").val(data[i + 1]);
                            $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                            $("#id_cliente").val(data[i + 4]);
                            $("#tipo_docu").val(data[i + 5]);
                            $("#ruc_ci").val(data[i + 6]);
                            $("#nombre_cli").val(data[i + 7]);
                            $("#telefono_cli").val(data[i + 8]);
                            $("#direccion_cli").val(data[i + 9]);
                            $("#tipo_comprobante").val(data[i + 10]);
                            $("#serie").val(data[i + 11]);
                            $("#observaciones").val(data[i + 12]);
                            $("#total_p").val(data[i + 13]);
                            $("#total_p2").val(data[i + 14]);
                            $("#iva").val(data[i + 15]);
                            $("#desc").val(data[i + 16]);
                            $("#tot").val(data[i + 17]);
                            $("#total_px").val(parseFloat(data[i + 13]).toFixed(2));
                            $("#total_p2x").val(parseFloat(data[i + 14]).toFixed(2));
                            $("#ivax").val(parseFloat(data[i + 15]).toFixed(2));
                            $("#descx").val(parseFloat(data[i + 16]).toFixed(2));
                            $("#totx").val(parseFloat(data[i + 17]).toFixed(2));
                        }
                    }
                });

                $.getJSON('retornar_notas2.php?com=' + valor, function (data) {
                    var tama = data.length;
                    var descuento = 0;
                    var total = 0;
                    var su = 0;
                    var precio = 0;
                    var multi = 0;
                    var flotante = 0;
                    var resultado = 0;

                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 11) {
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
                                total: data[i + 6],
                                precio_ux: precio.toFixed(2),
                                descuentox: parseFloat(desc).toFixed(2),
                                cal_desx: resultado.toFixed(2),
                                totalx: parseFloat(data[i + 6]).toFixed(2),
                                iva: data[i + 7],
                                incluye: data[i + 8],
                                cantidad_unidad: data[i + 9],
                                unidad_medida: data[i + 10],
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });
                // Fin

                $("#buscar_notas_credito").dialog("close");
            } else {
                alertify.alert("Seleccione una Factura");
            }
        }
    });
    ////////////////////buscador proformas tecnico/////////////////////////
    jQuery("#list7").jqGrid({
        url: 'xmlBuscarEstados.php',
        datatype: 'xml',
        colNames: ['ID', 'N° AUTORIZACIÓN', 'FECHA EMISIÓN', 'RAZÒN SOCIAL', 'TOTAL', 'ESTADO', 'ENVIO CORREO', 'ENVIO XML', 'CONSULTA COMPROBANTE'],
        colModel: [
            { name: 'id_devolucion_venta', index: 'id_devolucion_venta', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },
            { name: 'autorizacion', index: 'autorizacion', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'fecha_emision', index: 'fecha_emision', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'razon_social', index: 'razon_social', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },

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
        sortname: 'id_devolucion_venta',
        sortorder: 'desc',
        viewrecords: true,
        gridComplete: function () {
            var ids = jQuery("#list7").jqGrid('getDataIDs');

            for (var i = 0; i < ids.length; i++) {
                var ids = jQuery("#list7").getDataIDs();

                for (var i = 0; i < ids.length; i++) {
                    var id_devolucion = ids[i];
                    var datosr = jQuery('#list7').getRowData(id_devolucion);

                    if (datosr.estado == "NO AUTORIZADO") {
                        be = "<i class='fa fa-envelope-o' style='cursor:not-allowed;' title='Para enviar el correo primero debe autorizar la nota de crédito'> CORREO</i>";
                        jQuery("#list7").jqGrid('setRowData', ids[i], { accion: be });
                    } else {
                        be = "<a  onclick=\"reenviar('" + id_devolucion + "')\" title='Reenviar Correo' ><i class='fa fa-envelope-o' style='cursor:pointer; cursor: hand'> CORREO</i></a>";
                        jQuery("#list7").jqGrid('setRowData', ids[i], { accion: be });
                    }


                }
            }

            for (var i = 0; i < ids.length; i++) {
                var ids = jQuery("#list7").getDataIDs();

                for (var i = 0; i < ids.length; i++) {
                    var id_devolucion = ids[i];


                    be = "<a  onclick=\"enviarXml('" + id_devolucion + "')\" title='Reenviar Xml' ><i class='fa fa-arrow-circle-right' style='cursor:pointer; cursor: hand'> Envio Xml</i></a>";
                    jQuery("#list7").jqGrid('setRowData', ids[i], { envio: be });

                }
            }
            for (var i = 0; i < ids.length; i++) {
                var ids = jQuery("#list7").getDataIDs();

                for (var i = 0; i < ids.length; i++) {
                    var id_devolucion = ids[i];
                    be = "<a  onclick=\"reenviarXml('" + id_devolucion + "')\" title='Reenviar Xml' ><i class='fa fa-repeat' style='cursor:pointer; cursor: hand'> Consulta Xml</i></a>";
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
    ////////////////////buscador proformas tecnico/////////////////////////
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
            name: 'id_anticipo_clientes',
            index: 'id_anticipo_clientes',
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
    //////////busqueda facturas////////
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
        caption: 'Lista de Cobros Pendientes',
        viewrecords: true,
        afterInsertRow: function (rowid, rowdata, rowelem) {
            console.log("rowdata", rowdata);
            console.log("rowelem", rowelem);
            $("#valor_pago_" + rowid).change(function (e) {
                let fac = facturasCobrar.find((el) => el.id_pagos_venta == rowid);
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
        refresh: true,
        search: false,
        view: true
    });
    /* jQuery("#list22").jqGrid({
        url: 'xmlFacturas_venta.php',
        datatype: 'xml',
        colNames: ['ID', 'Num Docu', 'Fecha Registro', 'Forma Pago', 'Observacion', 'Monto'],
        colModel: [
            {
                name: 'ids', index: 'ids', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'center',
                frozen: true, width: 50
            },
            {
                name: 'num_documento', index: 'num_documento', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center',
                frozen: true, width: 180
            },
            { name: 'fecha_actual', index: 'fecha_actual', editable: false, frozen: true, hidden: false, editrules: { required: true }, align: 'center', width: 250 },
            { name: 'forma_pago', index: 'forma_pago', editable: true, frozen: true, hidden: false, editrules: { required: true }, align: 'center', width: 180 },
            { name: 'observacion', index: 'observacion', editable: true, search: false, frozen: true, hidden: false, editrules: { required: true }, align: 'center', width: 200 },
            { name: 'monto', index: 'monto', editable: true, frozen: true, hidden: false, editrules: { required: true }, align: 'center', width: 120 },
        ],
        rowNum: 10,
        width: 500,
        rowList: [10, 20, 30],
        pager: jQuery('#pager22'),
        shrinkToFit: true,
        sortorder: 'asc',
        caption: 'Lista de Cobros Pendientes',
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
                $("#cuenta_contable").val("ANTICIPOS CLIENTES");
                $("#idCuenta").val("248");
                console.log("RRRTRT" + repe);
                //                if (repe == 1) {
                //                    alertify.error("FORMA DE PAGO YA EXISTE");
                //                } else {
                var datarow = {
                    id_cobro_anticipo: count = count + 1,
                    id_anticipo_clientes: ret.ids,
                    id_factura_venta: $("#comprobante").val(),
                    id_cliente: $("#id_cliente").val(),
                    forma_pago: ret.forma_pago,
                    comprobante: ret.num_documento,
                    monto: ret.monto

                };
                //                }

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
    }); */

    $(window).bind('resize', function () {
        jQuery("#list22").setGridWidth($('#pager22').width());
    }).trigger('reloadGrid');


    jQuery(window).bind('resize', function () {
        jQuery("#list").setGridWidth(jQuery('#grid_container').width(), true);
    }).trigger('resize');

    obtenerParametrosEmpresa();
}

function obtenerCxcCliente(idcliente) {
    return $.ajax({
        url: "cxc_cliente_nc.php",
        method: "GET",
        data: {
            id_cliente: idcliente
        },
        dataType: "json"
    }).done(function (data) {
        return data;
    });
}

function disableFormasMixtoForm() {
    $("#formaspago_mixto").val("Contado");
    $("#btnCuenta")[0].disabled = true;
    $("#formaspago_mixto")[0].disabled = true;
    $("#valor_formas")[0].disabled = true;
    $("#btnAgregar_mixto")[0].disabled = true;
    $("#num_tarjeta")[0].disabled = true;
}

function enableFormasMixtoForm() {
    $("#formaspago_mixto").val("Contado");
    $("#btnCuenta")[0].disabled = false;
    $("#formaspago_mixto")[0].disabled = false;
    $("#valor_formas")[0].disabled = false;
    $("#btnAgregar_mixto")[0].disabled = false;
    $("#num_tarjeta")[0].disabled = false;
}

function validarAddValoresCxc() {
    let totalcxc = 0;
    facturasCobrar.forEach(el => totalcxc += Number(el.valor_pago));

    if ($("#valor_factura_saldo").val() == "") {
        $("#valor_factura_saldo").val($("#valor_factura").val());
    }
    let valores = Number($("#valor_factura_saldo").val());
    return valores >= totalcxc;
}

async function cargarTablaCuentasCxc() {
    try {
        jQuery("#list22").jqGrid("clearGridData");
        if (!!$("#id_cliente").val()) {
            let cuentasc = await obtenerCxcCliente($("#id_cliente").val());
            facturasCobrar = cuentasc.map((fac) => {
                fac["valor_pago"] = 0;
                return fac;
            });
            let fil = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
            fil = fil.filter(el => el.forma_pago_mixto == "CXC");
            if (fil.length > 0) {
                fil.forEach(el => {
                    console.log(el);
                    let find = facturasCobrar.find(f => f.id_pagos_venta == el.num_documento);
                    find.valor_pago = el.valor;
                });
            }
            cuentasc.forEach((el) => {
                jQuery("#list22").jqGrid('addRowData', el.id_pagos_venta, el);
            });
        }
    } catch (error) {
        console.error(error);
    }
}

function llenarValoresPagosCxc() {
    $("#validar_guardar_grid").val("1");
    let totalcxc = 0;
    facturasCobrar.forEach(el => totalcxc += Number(el.valor_pago));

    let fil = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
    let cxcfil = fil.filter(el => el.forma_pago_mixto == "CXC");
    cxcfil.forEach(el => {
        jQuery("#listPagoreten_mixto").jqGrid("delRowData", el.id_f_v_mix);
    });
    jQuery("#listPagoreten_mixto").trigger('reloadGrid');
    fil = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
    let totalgrid = 0;
    for (let t = 0; t < fil.length; t++) {
        let dd = fil[t];
        totalgrid = totalgrid + parseFloat(dd["valor"]);
    }
    let total = totalgrid + totalcxc;

    if (Number($("#valor_factura").val()) < total) {
        alertify.error(
            "Error.. La suma supera el total de la Factura " + $("#totx").val()
        );
        $("#alertify-logs").empty();
        return;
    }

    let filas2 = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
    count = filas2.length;

    console.log("filas2", filas2);
    facturasCobrar = facturasCobrar.filter(el => el.valor_pago > 0);
    facturasCobrar.forEach(el => {
        count++;
        let datarow = {
            id_f_v_mix: count,
            id_factura_venta: $("#comprobante").val(),
            fecha: $("#fecha_actual").val(),
            forma_pago_mixto: $("#formaspago_mixto").val(),
            tarjeta_credito: $("#tarjetas").val(),
            num_documento: el.id_pagos_venta,//$("#num_tarjeta").val(),
            valor: el.valor_pago,//$("#valor_formas").val(),
            id_cuenta: "",//$("#idCuenta").val(),
            fecha_vencimiento: ""//$("#fecha_dias").val(),
        };
        su = jQuery("#listPagoreten_mixto").jqGrid("addRowData", count, datarow);
        /* let find = filas2.find(f => f.num_documento == el.id_pagos_venta);
        if (!!find) {
            var rowData = jQuery("#listPagoreten_mixto").jqGrid('getRowData', find.id_f_v_mix);
            rowData.valor = el.valor_pago;
            jQuery("#listPagoreten_mixto").jqGrid('setRowData', find.id_f_v_mix, rowData);
        } else {
            su = jQuery("#listPagoreten_mixto").jqGrid("addRowData", count, datarow);
        } */
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

function iniDialogCuentas() {
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

    $("#cuentas").dialog(dialogo_cuenta);
    $("#btnCuenta").click(function (e) {
        e.preventDefault();
        $("#cuentas").dialog("open");
    });

    $(window).bind("resize", function () {
        jQuery("#list44").setGridWidth($("#pager44").width());
    }).trigger("resize");
    jQuery("#list44").jqGrid({
        url: "xmlPlanCuentas.php?cuenta=Contado",
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
}
