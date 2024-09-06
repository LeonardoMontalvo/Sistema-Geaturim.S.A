$(document).on("ready", inicio);
function evento(e) {
    e.preventDefault();
}

function openPDF() {
    window.open('../../ayudas/ayuda.pdf');
}

var dialogo =
{
    autoOpen: false,
    resizable: false,
    width: 530,
    height: 320,
    modal: true,
    // position: "top",
    show: "explode",
    hide: "blind",
    Cancelar: function () {
        $(this).dialog("close");
        $('#list2').trigger('reloadGrid');
    },
    close: function () {
        $("#mostrar_pagadas")[0].checked = false;
    }
};
var dialogo3 =
{
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 350,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind"
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
    if (hours >= 12) {
        dn = "PM";
        if (hours > 12) {
            hours = hours - 12;
        }
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

function enter(e) {
    if (e.which === 13 || e.keyCode === 13) {
        comprobar();
        return false;
    }
    return true;
}

function enter1(e) {
    if (e.which === 13 || e.keyCode === 13) {
        entrar();
        return false;
    }
    return true;
}

function comprobar() {
    if ($("#id_cliente").val() === "") {
        $("#ruc_ci").focus();
        alertify.error("Ingrese un cliente");
    } else {
        if ($("#ruc_ci").val() === "") {
            $("#ruc_ci").focus();
            alertify.error("Identificación del cliente");
        } else {
            if ($("#forma_pago").val() === "") {
                $("#forma_pago").focus();
                alertify.error("Error... Seleccione forma de pago");
            }
        }
    }
}

function getValorRestante() {
    let filas = jQuery("#list_fp").jqGrid("getRowData");
    let totalfp = 0;
    filas.forEach(el => {
        totalfp += Number(el.valor);
    });

    let total = getTotalPagado();
    return Number((total - totalfp).toFixed(2));
}
function entrar() {

    if ($("#forma_pago").val() == 'TRANSFERENCIA' && $("#idCuenta").val() == "") {
        alertify.error("Error.. Debe seleccionar Cuenta contable");
    } else {
        if ($("#num_factura").val() === "") {
            alertify.error("Error... Seleccione una factura");
        } else {
            if ($("#valor_pagado").val() === "") {
                $("#valor_pagado").focus();
                alertify.error("Ingrese un valor");
            } else {
                //Comprobar si la factura está repetida en list
                var repe = 0;
                var filas = jQuery("#list").jqGrid("getRowData");
                for (var i = 0; i < filas.length; i++) {
                    var id = filas[i];
                    if (id['num_factura'] == $("#num_factura").val()) {
                        repe = 1;
                    }
                }

                if (repe == 1) {

                    alertify.error("Error... Ya existe una factura");
                } else {

                    if (parseFloat($("#valor_pagado").val()) <= parseFloat($("#saldo2").val())) {
                        //$("#list").jqGrid("clearGridData", true);
                        var filas = jQuery("#list").jqGrid("getRowData");
                        var su = 0;
                        var saldo = 0;
                        var valor = parseFloat($("#valor_pagado").val());
                        var entero = ((valor).toFixed(2));
                        saldo = (parseFloat($("#saldo2").val()) - parseFloat($("#valor_pagado").val()));
                        var entero2 = ((saldo).toFixed(2));
                        //  if (filas.length === 0) {
                        var datarow = {
                            ids_pagos: $("#ids").val(),
                            num_factura: $("#num_factura").val(),
                            tipo_factura: $("#tipo_factura").val(),
                            fecha_factura: $("#fecha_factura").val(),
                            totalcxc: $("#totalcxc").val(),
                            valor_pagado: entero,
                            saldo: entero2
                        };
                        su = jQuery("#list").jqGrid('addRowData', $("#num_factura").val(), datarow);
                        ////////limpiar///////////
                        $("#ids").val("");
                        $("#num_factura").val("");
                        $("#tipo_factura").val("");
                        $("#fecha_factura").val("");
                        $("#totalcxc").val("");
                        $("#valor_pagado").val("");
                        $("#saldo2").val("");
                           $("#total_pagado").text(getTotalPagado().toFixed(2));

                    $("#list_fp").jqGrid("clearGridData", true);
                        ///////////////////////////
                        // }
                    } else {
                        alertify.alert("Error... Valor excedió al saldo");
                    }
                }
            }
        }
    }
     $("#valor_res_fp").val(getValorRestante().toFixed(2));
}

function cargar_facturas() {
    var id = $("#id_cliente").val();
    var tipo_docu = $("#tipo_docu").val();
    if (id === "" || tipo_docu == "0") {
        $("#num_factura").val("");
        $("#ruc_ci").focus();
        alertify.error("Error... Seleccione un cliente y tipo documento");
    } else {
        $("#list2").jqGrid('setGridParam', {
            url: 'xmlFacturas_venta.php?id_cliente=' + id + '&tipo=' + $("#tipo_pago").val() + '&fact_nota=' + $("#tipo_docu").val(),
            datatype: 'xml'
        }).trigger('reloadGrid');
        $("#buscar_facturas").dialog("open");
    }
}
//reiniciar el grid
//     }).trigger('reloadGrid');
function guardar_pagos() {
    var tam = jQuery("#list").jqGrid("getRowData");
    if ($("#estado_autorizado").val() === "NO AUTORIZADA") {
        alertify.error("ERROR... LA FACTURA NO ESTA AUTORIZADA");
    } else {
        if ($("#id_cliente").val() === "") {
            $("#ruc_ci").focus();
            alertify.error("Ingrese un cliente");
        } else {
            if ($("#ruc_ci").val() === "") {
                $("#ruc_ci").focus();
                alertify.error("Identificación del cliente");
            } else {
                if ($("#forma_pago").val() === "0") {
                    $("#forma_pago").focus();
                    alertify.error("Error... Seleccione forma de pago");
                } else {
                    if ($("#tipo_pago").val() === "") {
                        $("#tipo_pago").focus();
                        alertify.error("Error... Seleccione tipo de pago");
                    } else {
                        if (tam.length === 0) {
                            alertify.error("Error... Ingrese un pago");
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
                                v1[i] = datos['ids_pagos'];
                                v2[i] = datos['num_factura'];
                                v3[i] = datos['tipo_factura'];
                                v4[i] = datos['fecha_factura'];
                                v5[i] = datos['totalcxc'];
                                v6[i] = datos['valor_pagado'];
                                v7[i] = datos['saldo'];
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

                            let stringvnc = "";
                            if (valoresNotaCredito.length > 0) {
                                let arrnc = valoresNotaCredito.filter(el => el.status);
                                arrnc = arrnc.map(el => el.id_formas_pago_mixto_nv);
                                stringvnc = arrnc.join(",");
                            }
                            
                                 let fomraspago = jQuery("#list_fp").jqGrid("getRowData");
                            if (fomraspago.length == 0) {
                                window.scrollTo(0, 0);
                                $('.nav-tabs a[href="#tab_fpago"]').tab('show');
                                return;
                            } else if (getValorRestante() != 0) {
                                alertify.alert(`<b>La suma de total formas de pago no es igual al total del pago (${getTotalPagado()})</b>`);
                                return;
                            }
                            

                            $("#btnGuardar").attr("disabled", true);
                            $.ajax({
                                type: "POST",
                                url: "guardar_pagos_cobrar.php",
                                data: "id_cliente="
                                    + $("#id_cliente").val()
                                    + "&comprobante="
                                    + $("#comprobante").val()
                                    + "&fecha_actual="
                                    + $("#fecha_actual").val()
                                    + "&hora_actual="
                                    + $("#hora_actual").val()
                                    + "&forma_pago="
                                    + $("#forma_pago").val()
                                    + "&tipo_pago="
                                    + $("#tipo_pago").val()
                                    + "&observaciones="
                                    + $("#observaciones").val()
                                    + "&campo1="
                                    + string_v1
                                    + "&campo2="
                                    + string_v2
                                    + "&campo3="
                                    + string_v3
                                    + "&campo4="
                                    + string_v4
                                    + "&campo5="
                                    + string_v5
                                    + "&campo6="
                                    + string_v6
                                    + "&campo7="
                                    + string_v7
                                    + "&cheque_tarjeta="
                                    + $("#cheque_tarjeta").val()
                                    + "&bancos="
                                    + $("#banco").val()
                                    + "&cuenta_cheque="
                                    + $("#idCuenta").val()
                                    + "&camponc=" + stringvnc 
                            
                                    + "&total_pagado="
                                    + $("#total_pagado").text()
                            
                                    + "&formas_pago=" + JSON.stringify(fomraspago),
                                success: function (data) {

                                    var val = data;
                                    if (val != "") {
                                        alertify.alert("Pago Guardado correctamente", function () {
                                            location.reload();//Activar
                                        });
                                        if ($("#tipo_pago").val() == "EXTERNA") {
                                            window.open("../../reportes/reporte_cxc.php?tipo_pago=" + $("#tipo_pago").val() + "&id=" + v2[0] + "&comprobante=" + $("#comprobanteE").val(), '_blank');
                                        } else {
                                            // console.log("comprobante:  "+ $("#comprobante").val());
                                            // console.log("comprobante 2:  "+ $("#comprobanteE").val());
                                            // console.log("comprobante 3:  "+ parseInt($("#comprobanteE").val())+1);

//                                            window.open("../../reportes/transacciones_cxc.php?hoja=A5&id=" + $("#comprobante").val(), '_blank');
                                            window.open("../../reportes/reporte_cxc.php?tipo_pago=" + $("#tipo_pago").val() + "&id=" + v2[0] + "&comprobante=" + $("#comprobante").val() + "&temp2=" + v6[0] + "&temp3=" + v7[0], '_blank');
                                        }
                                        //alertify.alert("Pago Guardado correctamente", function(){location.reload();});
                                        alertify.alert("Pago Guardado correctamente", function () {
                                            location.reload();//Activar
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


function flecha_atras() {



    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "pagos_cobrar" + "&id_tabla=" + "id_pagos_cobrar" + "&tipo=" + 1,
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#comprobante").val(val);
                var valor = $("#comprobante").val();
                ////////////////////////////////////////////////

                ///////////////////////////////////////////////////
                $("#btnGuardar").attr("disabled", true);
                $("#btnModificar").attr("disabled", true);
                $("#btnfacturas").attr("disabled", "disabled");
                $("#valor_pagado").attr("disabled", "disabled");
                $("#ruc_ci").attr("disabled", "disabled");
                $("#nombres_completos").attr("disabled", "disabled");
                $("#observaciones").attr("disabled", "disabled");
                $("#id_cliente").val("");
                $("#ruc_ci").val("");
                $("#nombres_completos").val("");
                $("#saldo").val("");
                $("#forma_pago").val(0);
                $('#tipo_pago').children().remove().end();

                $("#tablaNuevo").css('display', 'none');
                $("#list").jqGrid("clearGridData", true);
                ///////////////////llamar cuentas flechas primera parte/////

                $.getJSON('retornar_pagos_venta.php?com=' + valor, function (data) {
                    var tama = data.length;
                    //var tamaR =val(data[tama]);

                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 11) {

                            $("#fecha_actual").val(data[i]);
                            $("#hora_actual").val(data[i + 1]);
                            $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                            $("#id_cliente").val(data[i + 4]);
                            $("#ruc_ci").val(data[i + 5]);
                            $("#nombres_completos").val(data[i + 6]);
                            $("#forma_pago").val(data[i + 7]);
                            $("#tipo_pago").append('<option value=' + data[i + 8] + ' selected>' + data[i + 8] + '</option>');
                            $("#tipo_docu").val(data[i + 9]);
                            $("#banco").val(data[i + 10]);
                        }
                    }

                });


                $.getJSON('retornar_pagos_venta2.php?com=' + valor, function (data) {
                    var tama = data.length;

                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 9) {

                            var datarow = {
                                ids_pagos: data[i],
                                num_factura: data[i + 1],
                                tipo_factura: data[i + 2],
                                fecha_factura: data[i + 3],
                                totalcxc: data[i + 4],
                                valor_pagado: data[i + 5],
                                saldo: data[i + 6],
                                compro: data[i + 8]
                            };

                            $("#comprobanteI").val(data[i + 8]);
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                            $("#observaciones").val(data[i + 7]);

                        }
                    }
                     $("#total_pagado").text(getTotalPagado().toFixed(2));
            cargarFormasPagoCom(valor);
                });
            } else {
                alertify.alert("No hay más registros superiores!!");
            }
        }
    });
}

function flecha_siguiente() {
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "pagos_cobrar" + "&id_tabla=" + "id_pagos_cobrar" + "&tipo=" + 2,
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#comprobante").val(val);
                var valor = $("#comprobante").val();
                ////////////////////////////////////////////////
                $("#btnGuardar").attr("disabled", true);
                $("#btnModificar").attr("disabled", true);
                $("#btnfacturas").attr("disabled", "disabled");
                $("#valor_pagado").attr("disabled", "disabled");
                $("#ruc_ci").attr("disabled", "disabled");
                $("#nombres_completos").attr("disabled", "disabled");
                $("#observaciones").attr("disabled", "disabled");
                $("#id_cliente").val("");
                $("#ruc_ci").val("");
                $("#nombres_completos").val("");
                $("#saldo").val("");
                $("#forma_pago").val(0);
                $('#tipo_pago').children().remove().end();
                $("#list").jqGrid("clearGridData", true);
                $("#tablaNuevo").css('display', 'none');

                $.getJSON('retornar_pagos_venta.php?com=' + valor, function (data) {
                    var tama = data.length;
                    //var tamaR =val(data[tama]);

                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 11) {

                            $("#fecha_actual").val(data[i]);
                            $("#hora_actual").val(data[i + 1]);
                            $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                            $("#id_cliente").val(data[i + 4]);
                            $("#ruc_ci").val(data[i + 5]);
                            $("#nombres_completos").val(data[i + 6]);
                            $("#forma_pago").val(data[i + 7]);
                            $("#tipo_pago").append('<option value=' + data[i + 8] + ' selected>' + data[i + 8] + '</option>');
                            $("#tipo_docu").val(data[i + 9]);
                            $("#banco").val(data[i + 10]);
                        }
                    }

                });
                $.getJSON('retornar_pagos_venta2.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 9) {
                            var datarow = {
                                ids_pagos: data[i],
                                num_factura: data[i + 1],
                                tipo_factura: data[i + 2],
                                fecha_factura: data[i + 3],
                                totalcxc: data[i + 4],
                                valor_pagado: data[i + 5],
                                saldo: data[i + 6],
                                compro: data[i + 8]
                            };

                            $("#comprobanteI").val(data[i + 8]);
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                            $("#observaciones").val(data[i + 7]);
                        }
                    }
                     $("#total_pagado").text(getTotalPagado().toFixed(2));
            cargarFormasPagoCom(valor);
                });
            } else {
                alertify.alert("No hay más registros superiores!!");
            }
        }
    });
}



function limpiar_campo() {
    if ($("#ruc_ci").val() === "") {
        $("#id_cliente").val("");
        $("#nombres_completos").val("");
        $("#saldo").val("");
        $("#forma_pago").val(0);
        $("#tipo_pago").val("");
        $("#list2").jqGrid("clearGridData", true);
        $("#list_pagosr").jqGrid("clearGridData", true);
           limpiarCuenta();
    }
}

function limpiar_campo2() {
    if ($("#nombres_completos").val() === "") {
        $("#id_cliente").val("");
        $("#ruc_ci").val("");
        $("#saldo").val("");
        $("#forma_pago").val(0);
        $("#tipo_pago").val("");
        $("#list2").jqGrid("clearGridData", true);
        $("#list_pagosr").jqGrid("clearGridData", true);
         limpiarCuenta();
    }
}

function limpiar_cuenta() {
    location.reload();
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
function abrirCuenta() {
       cargar_cuentas();
    $("#cuentas").dialog("open");
}
function getTotalPagado() {
    let filas = jQuery("#list").jqGrid("getRowData");
    let total = 0;
    filas.forEach(el => {
        total += Number(el.valor_pagado);
    });
    return Number((total).toFixed(2));
}
function initFormasPago() {
    initTablaFpago();
    $("#valor_fp").keypress(function (e) {
        if (e.key == 'Enter') {
            if (!Number($("#valor_fp").val())) {
                $("#valor_fp").focus();
                alertify.error("Ingrese un valor");
                return;
            }
            $("#nro_doc_fp").focus();
        }
    });
    $("#nro_doc_fp").keypress(function (e) {
        if (e.key == 'Enter') {
            $("#fecha_fp").focus();
        }
    });
    $("#fecha_fp").keypress(function (e) {
        if (e.key == 'Enter') {
            if (!Number($("#idCuenta").val()) && $("#forma_pago").val() != "NOTA_CREDITO") {
                $("#btnCuenta").focus();
                alertify.alert("Seleccione una cuenta", function (e) {
                    $("#btnCuenta").click();
                });
                return;
            }
            addFpago();
        }
    });
    $("#btn_agregar").click(function (e) {
        addFpago();
    });

    $('.nav-tabs a[href="#tab_fpago"]').on('show.bs.tab', function () {
        let data = $('#list_fp').jqGrid('getRowData');
        if (data.length == 0) {
            $("#valor_fp").val(getTotalPagado());
        }
        setTimeout(() => $("#valor_fp").focus(), 100);
    });
}
function initTablaFpago() {
    jQuery("#list_fp").jqGrid({
        datatype: "local",
        colNames: ['', 'Forma Pago', 'Nro. Documento', 'Valor', "Fecha", 'id_cuenta'],
        colModel: [
            {
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
                name: 'forma_pago',
                index: 'forma_pago',
                editable: false,
                align: 'center',
                width: '180',
                search: false,
                frozen: true,
            },
            {
                name: 'nro_documento',
                index: 'nro_documento',
                editable: false,
                align: 'center',
                width: '180',
                search: false,
                frozen: true,
            },
            {
                name: 'valor',
                index: 'valor',
                editable: false,
                align: 'center',
                width: '180',
                search: false,
                frozen: true,
            },
            {
                name: 'fecha_forma',
                index: 'fecha_forma',
                editable: false,
                align: 'center',
                width: '180',
                search: false,
                frozen: true,
            },
            {
                name: 'id_cuenta',
                index: 'id_cuenta',
                editable: false,
                align: 'center',
                width: '180',
                search: false,
                frozen: true,
                hidden: true
            },
        ],
        height: 150,
        viewrecords: true,
        shrinkToFit: true,
        delOptions: {
            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {
                var su = jQuery("#list_fp").jqGrid('delRowData', rowid);
                if (su === true) {
                    $("#valor_res_fp").val(getValorRestante().toFixed(2));
                }
                $(".ui-icon-closethick").trigger('click');
                return true;
            },
            processing: true
        },
    });
}
function limpiarCuenta() {
    $("#list").jqGrid("clearGridData", true);
    $("#list_fp").jqGrid("clearGridData", true);
    $("#tipo_pago").empty();
    $("#total_pagado").text(getTotalPagado().toFixed(2));
    
    $("#valor_res_fp").val(getValorRestante().toFixed(2));

    limpiarInputsFpago();
}
function cargar_cuentas() {
    var id = $("#forma_pago").val();
    console.log("id", id);
    console.log("forma pago", $("#forma_pago").val());

    $("#list4").jqGrid('setGridParam', {
        url: 'xmlPlanCuentas.php?id=' + id,
        datatype: 'xml'
    }).trigger('reloadGrid');


}
function cargarCuenta(id) {
    var ccuenta = jQuery("#list4").jqGrid('getCell', id, 0) + "  -  " + jQuery("#list4").jqGrid('getCell', id, 1);
    $("#idCuenta").val(id);
    $("#cuenta_contable").val(ccuenta);

    var string = ccuenta;
    var string1 = string.split("-");
    var part1 = string1[1]; // 123


    /* $("#banco").val(part1); *///TODO nombre banco
    document.getElementById("cuenta_contable").readOnly = true;
}
function addFpago(nrodoc = null, valor = null, formapago = null, idcuenta = null, fecha = null) {
    if (!nrodoc) {
        nrodoc = $("#nro_doc_fp").val();
    }
    if (!valor) {
        valor = $("#valor_fp").val();
    }
    if (!formapago) {
        formapago = $("#forma_pago").val();
    }
    if (!idcuenta) {
        idcuenta = $("#idCuenta").val();
    }
    if (!fecha) {
        fecha = $("#fecha_fp").val();
    }
    if (!Number(valor)) {
        $("#valor_fp").focus();
        alertify.error("Ingrese un valor válido");
        return;
    }
    let ids = $('#list_fp').jqGrid('getDataIDs');
    let id = 0;
    if (ids.length > 0) {
        id = ids[ids.lengh - 1];
    }
    id += 1;

    let rowdata = {
        forma_pago: formapago,
        nro_documento: nrodoc,
        valor: valor,
        id_cuenta: idcuenta,
        fecha_forma: fecha
    };
console.log("//",getValorRestante());
console.log("//valor",Number(valor));
    let restante = getValorRestante() - Number(valor);
    if (restante < 0) {
        alertify.alert(`<b>La suma de formas de pago no debe superar el total del pago (${getTotalPagado()})</b>`);
        $("#valor_fp").focus();
        return
    }

    $("#list_fp").jqGrid('addRowData', id, rowdata);
    limpiarInputsFpago();
    $("#valor_fp").focus();

    $("#valor_res_fp").val(getValorRestante().toFixed(2));
}
function limpiarInputsFpago() {
    $("#forma_pago").val("CONTADO").change();
    $("#nro_doc_fp").val("")
    $("#fecha_fp").val("")
    $("#valor_fp").val("");
    $("#idCuenta").val("");
    $("#cuenta_contable").val("");
}
function cargarFormasPagoCom(comprobante) {
    $.ajax({
        url: "obtener_formas_pago.php",
        method: "GET",
        dataType: "json",
        data: {
            comprobante
        },
        success: function (data) {
            console.log(data);
            data.forEach(el => {
                addFpago(el.numero_documento, el.valor, el.forma_pago, el.id_cuenta);
            });
        }
    });
}
function inicio() {
       initFormasPago();
    iniDialogValoresNotasC();
    iniTablaPagosRealizados();
    iniDialogosPermisos();
    $("#mostrar_pagadas").change(function (e) {
        if (e.target.checked) {
            cargarTablaFacturas($("#id_cliente").val());
        } else {
            cargar_facturas();
        }
    });
    $("#cuentas").dialog(dialogo_cuenta);

    $("#btnCuenta").on("click", abrirCuenta);
    alertify.set({ delay: 5000 });
    //////////////para hora///////////
    show();
    ///////////////////

    //////////////botones//////////
    $("#buscar_cuentas_cobrar").dialog(dialogo3);

    $("#btnBuscar").click(function () {
        $("#buscar_cuentas_cobrar").dialog("open");
    })
    $("#btnfacturas").click(function (e) {
        e.preventDefault();
    });

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
        var temp = 0;
        var temp2 = 0;
        var temp3 = 0;
        var fil = jQuery("#list").jqGrid("getRowData");
        for (var i = 0; i < fil.length; i++) {
            var datos = fil[i];
            temp = datos['num_factura'];
            temp2 = datos['valor_pagado'];
            temp3 = datos['saldo'];
        }
        if ($("#tipo_pago").val() == "EXTERNA") {
            window.open("../../reportes/reporte_cxc.php?tipo_pago=" + $("#tipo_pago").val() + "&id=" + temp + "&comprobante=" + $("#comprobante").val(), '_blank');
        } else {
//            window.open("../../reportes/transacciones_cxc.php?hoja=A5&id=" + $("#comprobante").val(), '_blank');
            window.open("../../reportes/reporte_cxc.php?tipo_pago=" + $("#tipo_pago").val() + "&id=" + temp + "&comprobante=" + $("#comprobante").val() + "&temp2=" + temp2 + "&temp3=" + temp3, '_blank');
        }
    });

    $("#btnAtras").click(function (e) {
        e.preventDefault();
    });
    $("#btnCuenta").click(function (e) {
        e.preventDefault();
    });

    $("#btnAdelante").click(function (e) {
        e.preventDefault();
    });

    $("#btnfacturas").on("click", cargar_facturas);
    $("#btnGuardar").on("click", guardar_pagos);
    $("#btnNuevo").on("click", limpiar_cuenta);
    $("#btnAtras").on("click", flecha_atras);
    $("#btnAdelante").on("click", flecha_siguiente);


    $("#ruc_ci").on("keyup", limpiar_campo);
    $("#nombres_completos").on("keyup", limpiar_campo2);
    $("#buscar_facturas").dialog(dialogo);

    //////////////para valor////////
    $("#valor_pagado").on("keypress", punto);
    $("#otros_val_p").on("keypress", punto);
    ////////////////////////////////

    //    $("#forma_pago").on("change", function () {
    //        if ($("#forma_pago").val() == "TRANSFERENCIA") {
    //            $("#cuenta_contable").attr("disabled", false);
    //            $("#btnCuenta").attr("disabled", false);
    //            $("#cheque_tarjeta").attr("disabled", true);
    //            $("#banco").attr("disabled", false);
    //
    //        } else if ($("#forma_pago").val() == "CHEQUE") {
    //            $("#cheque_tarjeta").attr("disabled", false);
    //            $("#cuenta_contable").attr("disabled", true);
    //            $("#btnCuenta").attr("disabled", true);
    //        } else {
    //            $("#cheque_tarjeta").attr("disabled", true);
    //
    //            $("#banco").attr("disabled", true);
    //        }
    //    })
    $("#forma_pago").on("change", function () {
        
        if ($("#forma_pago").val() == "TRANSFERENCIA") {
              cargar_cuentas();
            $("#cuenta_contable").attr("disabled", false);
            $("#btnCuenta").attr("disabled", false);
            $("#cuenta_contable").val("");
            $("#idCuenta").val("");
            $('#fecha_vencimiento').hide();

            //            $("#cheque_tarjeta").attr("disabled", true);
            //            $("#banco").attr("disabled", true);
        } else if ($("#forma_pago").val() == "CONTADO" || $("#forma_pago").val() == "TARJETA") {
              cargar_cuentas();
            $("#cuenta_contable").attr("disabled", true);
            $("#btnCuenta").attr("disabled", true);
            $("#cuenta_contable").val("");
            $("#idCuenta").val("");
            $('#fecha_vencimiento').hide();
            $("#cheque_tarjeta").attr("disabled", true);
            $("#banco").attr("disabled", true);

        } else if ($("#forma_pago").val() == "CHEQUE") {
              cargar_cuentas();
            console.log("ddaqui1:");
            $("#cuenta_contable").attr("disabled", true);
            $("#btnCuenta").attr("disabled", true);
            $("#cheque_tarjeta").attr("disabled", false);
            $("#banco").attr("disabled", false);
            $("#cuenta_contable").val("");
            $("#idCuenta").val("");
            $('#fecha_vencimiento').hide();

        } else if ($("#forma_pago").val() == "NOTA_CREDITO") {
            $("#cuenta_contable").attr("disabled", true);
            $("#btnCuenta").attr("disabled", true);
            $("#cuenta_contable").val("");
            $("#idCuenta").val("");
            $('#fecha_vencimiento').hide();
            $("#cheque_tarjeta").attr("disabled", true);
            $("#banco").attr("disabled", true);
            $("#buscar_val_nc").dialog("open");
        }

        if ($("#forma_pago").val() != "NOTA_CREDITO") {
            if (valoresNotaCredito.length > 0) {
                $("#list").jqGrid("clearGridData", true);
            }
            valoresNotaCredito = [];
        }
    })

    //////////////validaciones////////////
    $("#ruc_ci").on("keypress", enter);
    $("#valor_pagado").on("keypress", enter1);

    /////buscador clientes ci///// 
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
            var id = $('#id_cliente').val();
            $('#tipo_pago').load('cargar_tipo_pago.php?cod=' + id);
            return false;
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };
    //////////////////////////////

    /////buscador clientes nombres///// 
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
            var id = $('#id_cliente').val();
            $('#tipo_pago').load('cargar_tipo_pago.php?cod=' + id);
            return false;
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };
    //////////////////////////////

    ///////////calendarios/////
    $('#fecha_actual').datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');

    ///////////tabla local/////////////   
    var can;
    jQuery("#list").jqGrid({
        datatype: "local",
        colNames: ['', 'id', 'Factura a Pagar', 'Tipo Factura', 'Fecha Factura', 'Total CxC', 'Valor a Pagar', 'Saldo', 'Comprobante'],
        colModel: [
            {
                name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions',
                formatoptions: { keys: false, delbutton: true, editbutton: false }
            },
            { name: 'ids_pagos', index: 'ids_pagos', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },
            { name: 'num_factura', index: 'num_factura', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
            { name: 'tipo_factura', index: 'tipo_factura', editable: false, frozen: true, editrules: { required: true }, align: 'center', width: 150 },
            { name: 'fecha_factura', index: 'fecha_factura', editable: false, frozen: true, editrules: { required: true }, align: 'center', width: 100 },
            { name: 'totalcxc', index: 'totalcxc', editable: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: 110 },
            { name: 'valor_pagado', index: 'valor_pagado', editable: false, frozen: true, editrules: { required: true }, align: 'center', width: 100 },
            { name: 'saldo', index: 'saldo', editable: false, search: false, frozen: true, editrules: { required: true }, align: 'center', width: 100 },
            { name: 'compro', index: 'compro', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },
        ],
        rowNum: 30,
        width: 750,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager'),
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
                rp_ge.processing = true;
                var su = jQuery("#list").jqGrid('delRowData', rowid);
                
                  var total_venta = 0;
                var valor_restante = 0;
                var valor_total = 0;
//                console.log("" + ret.valor_pagado);
                if (su === true) {
                    total_venta = (parseFloat($("#total_pagado").text()) - (parseFloat(ret.valor_pagado))).toFixed(2);
                    console.log("//",ret.valor_pagado);

                     $("#total_pagado").text(total_venta);
                   
                }
                
                $(".ui-icon-closethick").trigger('click');
                return true;
            },
            processing: true
        }
    });

    //////////busqueda facturas////////
    jQuery("#list2").jqGrid({
        url: 'xmlFacturas_compra.php',
        datatype: 'xml',
        colNames: ['ID', 'Factura a Pagar', 'Tipo Factura', 'Fecha Factura', 'Total CxC', 'Valor a Pagar', 'Saldo', 'Estado Factura'],
        colModel: [
            {
                name: 'ids', index: 'ids', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'center',
                frozen: true, width: 50
            },
            {
                name: 'num_factura', index: 'num_factura', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center',
                frozen: true, width: 180
            },
            { name: 'tipo_factura', index: 'tipo_factura', editable: false, frozen: true, hidden: true, editrules: { required: true }, align: 'center', width: 250 },
            { name: 'fecha_factura', index: 'fecha_factura', editable: true, frozen: true, hidden: true, editrules: { required: true }, align: 'center', width: 180 },
            { name: 'totalcxc', index: 'totalcxc', editable: true, search: false, frozen: true, hidden: true, editrules: { required: true }, align: 'center', width: 110 },
            { name: 'valor_pagado', index: 'valor_pagado', editable: true, frozen: true, hidden: true, editrules: { required: true }, align: 'center', width: 120 },
            { name: 'saldo', index: 'saldo', editable: false, search: false, frozen: true, hidden: false, editrules: { required: true }, align: 'center', width: 110 },
            { name: 'compro', index: 'compro', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },
        ],
        rowNum: 10,
        width: 500,
        rowList: [10, 20, 30],
        pager: jQuery('#pager2'),
        shrinkToFit: true,
        sortorder: 'asc',
        caption: 'Lista de Facturas',
        viewrecords: true,
        ondblClickRow: function (rowid) {
            var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
            jQuery('#list2').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list2").jqGrid('getRowData', id);
                $("#ids").val(ret.ids);
                $("#num_factura").val(ret.num_factura);
                $("#tipo_factura").val(ret.tipo_factura);
                $("#fecha_factura").val(ret.fecha_factura);
                $("#totalcxc").val(ret.totalcxc);
                     $("#valor_pagado").val(ret.saldo);
                $("#saldo2").val(ret.saldo);
                $("#estado_autorizado").empty();
                if (ret.compro == "undefined") {
                    $("#estado_autorizado").append($("<h3>").text("NO AUTORIZADA"));
                    $("#estado_autorizado h3").css("color", "red");
                    $("#estado_autorizado").val("NO AUTORIZADA");
                } else {
                    $("#estado_autorizado").append($("<h3>").text("AUTORIZADA"));
                    $("#estado_autorizado h3").css("color", "green");
                    $("#estado_autorizado").val("AUTORIZADA");
                }
                //////////////////////
                $("#buscar_facturas").dialog("close");
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
                cargarTablaPagosRealizados($("#tipo_pago").val(), $("#num_factura").val(), $("#tipo_factura").val());
            } else {
                alertify.alert("Seleccione una Cuenta");
            }
        }
    }).jqGrid('navGrid', '#pager2', {
        add: false,
        edit: false,
        del: false,
        refresh: true,
        search: false,
        view: true
    });
    /////////////////	

    jQuery("#list2").jqGrid('navButtonAdd', '#pager2', {
        caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
            jQuery('#list2').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list2").jqGrid('getRowData', id);
                $("#ids").val(ret.ids);
                $("#num_factura").val(ret.num_factura);
                $("#tipo_factura").val(ret.tipo_factura);
                $("#fecha_factura").val(ret.fecha_factura);
                $("#totalcxc").val(ret.totalcxc);
                $("#saldo2").val(ret.saldo);

                //////////////////////
                $("#buscar_facturas").dialog("close");
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


    jQuery("#list3").jqGrid({
        url: 'xmlBuscarCuentasCobrar.php',
        datatype: 'xml',
        colNames: ['ID', 'IDENTIFICACIÓN', 'COMPROBANTE', 'CLIENTE', 'FACTURA NRO.', 'MONTO TOTAL', 'FECHA'],
        colModel: [
            { name: 'id_pagos_cobrar', index: 'id_pagos_cobrar', editable: false, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 50 },
            { name: 'identificacion', index: 'identificacion', editable: false, search: true, hidden: false, editrules: { edithidden: false }, align: 'right', frozen: true, width: 150 },
            { name: 'comprobante', index: 'comprobante', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 150 },
            { name: 'nombres_cli', index: 'nombres_cli', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'left', frozen: true, width: 200 },
            { name: 'num_factura', index: 'num_factura', editable: true, search: true, hidden: false, editrules: { edithidden: false }, align: 'right', frozen: true, width: 200 },
            { name: 'total_venta', index: 'total_venta', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'right', frozen: true, width: 100 },
            { name: 'fecha_actual', index: 'fecha_actual', editable: true, search: false, hidden: false, editrules: { edithidden: false }, align: 'center', frozen: true, width: 100 },
        ],
        rowNum: 10,
        width: 760,
        height: 220,
        rowList: [10, 20, 30],
        pager: jQuery('#pager3'),
        sortname: 'id_pagos_cobrar',
        shrinkToFit: true,
        sortorder: 'desc',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list3").jqGrid('getGridParam', 'selrow');
            jQuery('#list3').jqGrid('restoreRow', id);

            if (id) {
                var ret = jQuery("#list3").jqGrid('getRowData', id);
                var valor = ret.comprobante;

                //var valor = ret.comprobante;
                $("#comprobante").val(valor);
                $("#btnGuardar").attr("disabled", true);
                $("#btnModificar").attr("disabled", true);
                $("#btnfacturas").attr("disabled", "disabled");
                $("#valor_pagado").attr("disabled", "disabled");
                $("#ruc_ci").attr("disabled", "disabled");
                $("#nombres_completos").attr("disabled", "disabled");
                $("#observaciones").attr("disabled", "disabled");
                $("#id_cliente").val("");
                $("#ruc_ci").val("");
                $("#nombres_completos").val("");
                $("#saldo").val("");
                $("#forma_pago").val(0);
                $('#tipo_pago').children().remove().end();
                $("#list").jqGrid("clearGridData", true);
                $("#tablaNuevo").css('display', 'none');

                $.getJSON('retornar_pagos_venta.php?com=' + valor, function (data) {
                    var tama = data.length;
                    //var tamaR =val(data[tama]);

                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 11) {

                            $("#fecha_actual").val(data[i]);
                            $("#hora_actual").val(data[i + 1]);
                            $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                            $("#id_cliente").val(data[i + 4]);
                            $("#ruc_ci").val(data[i + 5]);
                            $("#nombres_completos").val(data[i + 6]);
                            $("#forma_pago").val(data[i + 7]);
                            $("#tipo_pago").append('<option value=' + data[i + 8] + ' selected>' + data[i + 8] + '</option>');
                            $("#tipo_docu").val(data[i + 9]);
                            $("#banco").val(data[i + 10]);
                        }
                    }

                });

                $.getJSON('retornar_pagos_venta2.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 9) {
                            var datarow = {
                                ids_pagos: data[i],
                                num_factura: data[i + 1],
                                tipo_factura: data[i + 2],
                                fecha_factura: data[i + 3],
                                totalcxc: data[i + 4],
                                valor_pagado: data[i + 5],
                                saldo: data[i + 6],
                                compro: data[i + 8]
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                            $("#observaciones").val(data[i + 7]);
                        }
                    }
                     $("#total_pagado").text(getTotalPagado().toFixed(2));
            cargarFormasPagoCom(valor);
                });
                $("#buscar_cuentas_cobrar").dialog("close");
            } else {
                alertify.alert("Seleccione una Cuenta a pagar");
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
        },
        {
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
        }
    );
    $(window).bind('resize', function () {
        jQuery("#list4").setGridWidth($('#pager4').width());
    }).trigger('resize');
    jQuery("#list4").jqGrid({
          url: 'xmlPlanCuentas.php?id=CONTADO',
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
        ondblClickRow: function (rowid) {
             cargarCuenta(rowid);
            setTimeout(() => $("#fecha_fp").focus(), 200);
            var id = jQuery("#list4").jqGrid('getGridParam', 'selrow');
            var ret = jQuery("#list4").jqGrid('getRowData', id);
            jQuery('#list4').jqGrid('restoreRow', id);
            var ccuenta = jQuery("#list4").jqGrid('getCell', id, 0) + "  -  " + jQuery("#list4").jqGrid('getCell', id, 1);
            if (mostrandoAnularDialog) {
                $("#cuenta_cheque_desc").val(ccuenta);
                ctaBancoAnular = id;
            } else {
                $("#idCuenta").val(id);
                $("#cuenta_contable").val(ccuenta);
                //            console.log(ccuenta);
                var string = ccuenta;
                var string1 = string.split("-");
                console.log(string1);
                var part1 = string1[1]; // 123
                $("#banco").val(part1);
                document.getElementById("cuenta_contable").readOnly = true;
            }
            $("#cuentas").dialog("close");
        },  gridComplete: function () {
            let idslist4 = $('#list4').jqGrid('getDataIDs')
            if (idslist4.length > 0) {
                cargarCuenta(idslist4[0]);
            }
        },
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


    $('#fecha_actual').datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');
    jQuery("#list3").jqGrid('navButtonAdd', '#pager3', {
        caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#list3").jqGrid('getGridParam', 'selrow');
            jQuery('#list3').jqGrid('restoreRow', id);

            if (id) {
                var ret = jQuery("#list3").jqGrid('getRowData', id);
                var valor = ret.id_pagos_cobrar;
                //  var valor = ret.comprobante;

                $("#comprobante").val(valor);

                $("#btnGuardar").attr("disabled", true);
                $("#btnModificar").attr("disabled", true);
                $("#btnfacturas").attr("disabled", "disabled");
                $("#valor_pagado").attr("disabled", "disabled");
                $("#ruc_ci").attr("disabled", "disabled");
                $("#nombres_completos").attr("disabled", "disabled");
                $("#observaciones").attr("disabled", "disabled");
                $("#id_cliente").val("");
                $("#ruc_ci").val("");
                $("#nombres_completos").val("");
                $("#saldo").val("");
                $("#forma_pago").val(0);
                $('#tipo_pago').children().remove().end();
                $("#list").jqGrid("clearGridData", true);
                $("#tablaNuevo").css('display', 'none');

                $.getJSON('retornar_pagos_venta.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 9) {
                            $("#fecha_actual").val(data[i]);
                            $("#hora_actual").val(data[i + 1]);
                            $("#digitador").val(data[i + 2] + " " + data[i + 3]);
                            $("#id_cliente").val(data[i + 4]);
                            $("#ruc_ci").val(data[i + 5]);
                            $("#nombres_completos").val(data[i + 6]);
                            $("#forma_pago").val(data[i + 7]);
                            $("#tipo_pago").append('<option value=' + data[i + 8] + ' selected>' + data[i + 8] + '</option>');
                        }
                    }
                });

                $.getJSON('retornar_pagos_venta2.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 9) {
                            var datarow = {
                                ids_pagos: data[i],
                                num_factura: data[i + 1],
                                tipo_factura: data[i + 2],
                                fecha_factura: data[i + 3],
                                totalcxc: data[i + 4],
                                valor_pagado: data[i + 5],
                                saldo: data[i + 6],
                                compro: data[i + 8]
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                            $("#observaciones").val(data[i + 7]);
                        }
                    }
                });
                $("#buscar_cuentas_cobrar").dialog("close");
            } else {
                alertify.alert("Seleccione una Cuenta a Pagar ");
            }
        }
    });

    jQuery(window).bind('resize', function () {
        jQuery("#list").setGridWidth(jQuery('#grid_container').width(), true);
    }).trigger('resize');
}



var mostrandoAnularDialog = false;
var ctaBancoAnular = 0;
var formaPagoAnular = "";

function iniTablaPagosRealizados() {
    jQuery("#list_pagosr").jqGrid({
        url: 'xmlBuscarPagosRealizados.php',
        datatype: 'xml',
        colNames: ['ID', 'FECHA PAGO', 'FORMA PAGO', 'VALOR PAGADO', 'OBSERVACIONES', ''],
        colModel: [
            { name: 'id_pagos_cobrar', index: 'id_pagos_cobrar', editable: false, search: false, align: 'center', frozen: true, width: 50 },
            { name: 'fecha_actual', index: 'fecha_actual', editable: false, search: false, align: 'left', frozen: true, width: 100 },
            { name: 'forma_pago', index: 'forma_pago', editable: true, search: false, align: 'left', frozen: true, width: 100 },
            { name: 'valor_pagado', index: 'valor_pagado', editable: true, search: false, align: 'right', frozen: true, width: 100 },
            { name: 'observaciones', index: 'observaciones', editable: true, search: false, align: 'left', frozen: true, width: 200 },
            {
                name: 'anular',
                index: 'id_pagos_cobrar',
                search: false,
                frozen: true,
                width: 80,
                formatter: function (cellvalue, options, rowObject) {
                    return `<div><button id="anular_pagor_${cellvalue}" type="button" class="btn btn-danger"><i class="fa fa-times"></i> Anular</button></div>`;
                }
            }
        ],
        rowNum: 10,
        width: 760,
        height: 220,
        rowList: [10, 20, 30],
        pager: jQuery('#pager_pagosr'),
        sortname: 'fecha_actual',
        shrinkToFit: true,
        sortorder: 'asc',
        viewrecords: true,
        ondblClickRow: function () {
        },
        afterInsertRow: function (rowid, rowdata, rowelem) {
            let idpago = rowid;
            let idcxc = $("#ids").val();
            $(`#anular_pagor_${rowid}`).click(function (é) {
                let formap = rowdata["forma_pago"];
                formaPagoAnular = formap;
                if (formap !== 'CONTADO' && formap != 'CHEQUE') {
                    alertify.alert("<b>Solo puede anular pagos realizados con las formas de pago CHEQUE y CONTADO.</b>");
                    $("#alertify-ok").css({ "background-color": "red" });
                    return;
                }
                if (formap == 'CHEQUE') {
                    $("#div_btn_cuenta_cheque").show();
                    $("#clave_permiso").dialog("option", "height", 400);
                } else {
                    $("#div_btn_cuenta_cheque").hide();
                    $("#clave_permiso").dialog("option", "height", 335);
                }

                $("#clave_permiso").dialog("open");
                $("#btnAceptar").off("click");
                $("#btnAceptar").click(function (e) {
                    anularPago(
                        idcxc,
                        idpago,
                        rowdata["valor_pagado"],
                        $("#tipo_pago").val(),
                        $("#otros_val_p").val(),
                        ctaBancoAnular,
                        $("#fecha_anulado").val(),
                        formap
                    );
                });
            });
        }
    }).jqGrid('navGrid', '#pager_pagosr',
        {
            add: false,
            edit: false,
            del: false,
            refresh: true,
            search: false,
            view: false
        },
        {
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
        }
    );
    /* $(window).bind('resize', function () {
        jQuery("#list_pagosr").setGridWidth($('#pager_pagosr').width());
    }).trigger('resize'); */
}

function cargarTablaPagosRealizados(tipopago, numfactura, tipodocu) {
    let params = {
        page: 1,
        url: `xmlBuscarPagosRealizados.php?tipo_pago=${tipopago}&num_factura=${numfactura}&tipo_docu=${tipodocu}`
    };
    jQuery("#list_pagosr").jqGrid("clearGridData");
    jQuery("#list_pagosr").jqGrid("setGridParam", params);
    jQuery("#list_pagosr").trigger("reloadGrid");
}

function iniDialogosPermisos() {
    let dialogo3 = {
        autoOpen: false,
        resizable: false,
        width: 420,
        height: 270,
        modal: true,
        show: "explode",
        hide: "blind",
        close: function (event, ui) {
            limpiarDialogoPermisos();
        },
        open: function (event, ui) {
            mostrandoAnularDialog = true;
            $("#fecha_anulado").val(new Date().toLocaleDateString("fr-CA"));
        },
        buttons: [
            {
                text: "Anular",
                //icon: "ui-icon-heart",
                click: function () {
                    //$(this).dialog("close");
                    validar_acceso();
                },
                showText: false
            },
            {
                text: "Cancelar",
                //icon: "ui-icon-heart",
                click: function () {
                    $(this).dialog("close");
                },
                showText: false
            }
        ]
    };
    let dialogo4 = {
        autoOpen: false,
        resizable: false,
        width: 300,
        height: 150,
        modal: true,
        show: "explode",
        hide: "blind",
    };

    $("#clave_permiso").dialog(dialogo3);
    $("#seguro").dialog(dialogo4);

    $("#btnSalir").click(function (e) {
        cerrarDialogosAnularPago();
    });
    $("#btn_cuenta_cheque").click(abrirCuenta);
    $("#fecha_anulado").val(new Date().toLocaleDateString("fr-CA"));
}

function validar_acceso() {
    if ($("#clave").val() == "") {
        $("#clave").focus();
        alertify.alert("<b>Ingrese la clave</b>");
    } else {
        if (!validarDatosAnulacion(formaPagoAnular)) {
            return;
        }
        $.ajax({
            url: "../../procesos/validar_acceso.php",
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

function limpiarDialogoPermisos() {
    $("#clave").val("");
    $("#anulacionComentario").val("");
    $("#otros_val_p").val("");
    $("#fecha_anulado").val("");
    mostrandoAnularDialog = false;
    ctaBancoAnular = 0;
    formaPagoAnular = "";
}

function anularPago(idcxc, idpago, valorpago, tipop, otrosval, ctabanco, fecanulado, formap) {

    return $.ajax({
        method: "POST",
        url: "anular_pago.php",
        dataType: "JSON",
        data: {
            id_cxc: idcxc,
            id_pago: idpago,
            valor_p: valorpago,
            tipo_p: tipop,
            otros_val: otrosval,
            cuenta_banco: ctabanco,
            fecha_anulado: fecanulado,
            forma_pago: formap
        },
        success: function (data) {
            if (data == 1) {
                cargarTablaPagosRealizados($("#tipo_pago").val(), $("#num_factura").val(), $("#tipo_factura").val());
                cargarTablaFacturas($("#id_cliente").val());
                $("#list").jqGrid('delRowData', $("#num_factura").val());
                alertify.success("Pago anulado correctamente.");
            } else {
                alertify.error("No se pudo anular el pago.");
            }
            cerrarDialogosAnularPago();
        }
    });
}

function cerrarDialogosAnularPago() {
    $("#clave_permiso").dialog("close");
    $("#seguro").dialog("close");
}

function cargarTablaFacturas(idcliente) {
    $("#list2").jqGrid('setGridParam', {
        url: 'xmlFacturas_venta.php?id_cliente=' + idcliente + '&tipo=' + $("#tipo_pago").val() + '&fact_nota=' + $("#tipo_docu").val() + '&canceladas=true',
        datatype: 'xml',
        gridComplete: function () {
            cargarDatosFacturaCargada();
            $("#list2").jqGrid('setGridParam', {
                gridComplete: function () { }
            });
        }
    }).trigger('reloadGrid');
}

function cargarDatosFacturaCargada() {
    if (!!$("#ids").val()) {
        let ret = jQuery("#list2").jqGrid('getRowData', $("#ids").val());
        $("#ids").val(ret.ids);
        $("#num_factura").val(ret.num_factura);
        $("#tipo_factura").val(ret.tipo_factura);
        $("#fecha_factura").val(ret.fecha_factura);
        $("#totalcxc").val(ret.totalcxc);
        $("#saldo2").val(ret.saldo);
    }

}

function validarDatosAnulacion(formap) {
    if ($("#fecha_anulado").val() == "") {
        alertify.alert("<b>Indique la fecha de anulación.</b>", function () {
            $("#fecha_anulado").focus();
        });
        return false;
    }
    if (formap == 'CHEQUE') {
        if (ctaBancoAnular == 0) {
            alertify.alert("<b>Seleccione una cuenta.</b>", function () {
                $("#fecha_anulado").focus();
            });
            return false;
        }
    }
    return true;
}

function limpiarDatosCuentaAnteriro() {
    $("#list_pagosr").jqGrid("clearGridData", true);

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
            cargarTablaValoresNcClientes();
        },
        close: function (event, ui) {
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
            /*   let fil = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
              fil = fil.filter(el => el.forma_pago_mixto == "NOTA_CREDITO");
              if (fil.length > 0) {
                  fil.forEach(el => {
                      let find = valoresNotaCredito.find(f => f.id_formas_pago_mixto_nv == el.num_documento);
                      selvalues.push(find.id_formas_pago_mixto_nv);
                  });
              } */
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
    var repe = 0;
    var filas = jQuery("#list").jqGrid("getRowData");
    for (var i = 0; i < filas.length; i++) {
        var id = filas[i];
        if (id['num_factura'] == $("#num_factura").val()) {
            repe = 1;
        }
    }

    if (repe == 1) {
        alertify.error("Error... Ya existe una factura");
        return;
    }

    let totalcxc = 0;
    valoresNotaCredito.forEach(el => {
        if (el.status) {
            totalcxc += Number(el.valor);
        }
    });

    if (totalcxc <= parseFloat($("#saldo2").val())) {
        var filas = jQuery("#list").jqGrid("getRowData");
        var su = 0;
        var saldo = 0;
        var valor = totalcxc;
        var entero = ((valor).toFixed(2));
        saldo = (parseFloat($("#saldo2").val()) - totalcxc);
        var entero2 = ((saldo).toFixed(2));

        var datarow = {
            ids_pagos: $("#ids").val(),
            num_factura: $("#num_factura").val(),
            tipo_factura: $("#tipo_factura").val(),
            fecha_factura: $("#fecha_factura").val(),
            totalcxc: $("#totalcxc").val(),
            valor_pagado: entero,
            saldo: entero2
        };
        su = jQuery("#list").jqGrid('addRowData', $("#num_factura").val(), datarow);
        ////////limpiar///////////
        $("#ids").val("");
        $("#num_factura").val("");
        $("#tipo_factura").val("");
        $("#fecha_factura").val("");
        $("#totalcxc").val("");
        $("#valor_pagado").val("");
        $("#saldo2").val("");
    } else {
        alertify.alert("Error... Valor excedió al saldo");
    }
    $("#buscar_val_nc").dialog("close");
    return;
}
