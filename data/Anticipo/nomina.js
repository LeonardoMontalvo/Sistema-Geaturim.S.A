$(document).on("ready", inicio);
function evento(e) {
    e.preventDefault();
}
function scrollToBottom() {
    $('html, body').animate({
        scrollTop: $(document).height()
    }, 'slow');
}

function openPDF() {
    window.open('../../ayudas/ayuda.pdf');
}

function scrollToTop() {
    $('html, body').animate({
        scrollTop: 0
    }, 'slow');
}
var dialogoTipo_documento =
        {
            autoOpen: false,
            resizable: false,
            width: 860,
            height: 350,
            modal: true
        };
var dialogoTipo_aporte_iess =
        {
            autoOpen: false,
            resizable: false,
            width: 860,
            height: 350,
            modal: true
        };
var dialogo =
        {
            autoOpen: false,
            resizable: false,
            width: 860,
            height: 350,
            modal: true
        };

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
    width: 380,
    height: 150,
    modal: true,
    position: "center",
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

function abrirDialogo() {
    $("#nominas").dialog("open");
}
function guardar_serie(fun) {

    if (document.getElementById('mixto2Anticipo').checked == true) {
        var tam2 = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
        if (document.getElementById("mixto2Anticipo").checked) {
            if ($("#valor_factura_saldo").val() != "0.00") {
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


                    }
//                if (repe == 1 && $("#fecha_dias").val() == "") {
//                    alertify.error("DEBE SELECCIONAR FECHA DE VENCIMIENTO");
//                    $("#validar_guardar").val("");
//                } else {
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
                                $("#fecha_registro").val(),
                        success: function (data) {
                            var val = data;
                            fun();
                            if (val == 1) {

                                alertify.success(" Guardado Correctamente");
                                $("#listPagoreten_mixto").jqGrid("clearGridData", true);
                                $("#cantidad_mixto").val() == "";

                                $("#btnGuardarRetenciones_mixto").attr("disabled", true);
                            }
                        },
                    });
//                }
                }
            }
        }
    } else {
        fun();
    }
}
function abrirCuenta() {
    $("#cuentas").dialog("open");
}
function limpiar_input() {
//    $("#fecha_registro").val("");
    $("#descripcion").val("");
    $("#valor").val("");
}

function enter(e) {
    if (e.which == 13 || e.keyCode === 13) {
        entrar();
        return false;
    }
    return true;
}
var valor_chec = 0;
var valor_chec_decimos = 0;
function controlarCheckbox50porcientoPF(e) {
    if (e.target.checked) {
        valor_chec = e.target.value;
    }
    var checks = $("[name=pf_check_50]");
    $.each(checks, function (index, value) {
        if (e.target.checked) {
            if (e.target.id != value.id) {

                value.checked = false;
            }
        }
    });
}
function controlarCheckbox50porcientoPFs(e) {
    if (e.target.checked) {
        valor_chec_decimos = e.target.value;
    }
    var checks = $("[name=pf_check_50s]");
    $.each(checks, function (index, value) {
        if (e.target.checked) {
            if (e.target.id != value.id) {

                value.checked = false;
            }
        }
    });
}

function entrar() {
    if ($("#fecha_registro").val() == "") {
        $("#fecha_registro").focus();
        alertify.error("Ingrese un producto");
    } else {
        if ($("#descripcion").val() == "") {
            $("#descripcion").focus();
        } else {

            $("#valor").focus();
        }
    }
}


function enter2h(e) {
    if (e.which == 13 || e.keyCode === 13) {
        entrar2h();
        return false;
    }
    return true;
}






function enter2(e) {
    if (e.which == 13 || e.keyCode === 13) {
        entrar2();
        return false;
    }
    return true;
}
function entrar2() {
    if ($("#select_mes").val() == "0") {
        $("#select_mes").focus();
        alertify.error("Ingrese un mes ");
    } else {
        if ($("#fecha_registro").val() == "") {
            $("#fecha_registro").focus();
            alertify.error("Ingrese Fecha Registro ");
        } else {
            if ($("#descripcion").val() == "") {
                $("#descripcion").focus();
                alertify.error("Ingrese la Descripcion ");
            } else {
                if ($("#valor").val() == "") {
                    $("#valor").focus();
                    alertify.error("Ingrese el Valor ");
                } else {
                    if ($("#id_empleadoa").val() == "") {
                        $("#id_empleadoa").focus();
                        alertify.error("Buscar Nomina ");
                    } else {
                        if (parseFloat($("#valor_total").val()) >=  parseFloat($("#salario_empleado").val())) {
                            $("#valor").focus();
                            alertify.error("El total Anticipo no debe superar el Sueldo ");
                        } else {

                            var filas = jQuery("#list_anticipo").jqGrid("getRowData");
                            var datarow = {

                                id_empleado: $("#id_empleadoa").val(),
                                fecha_registro: $("#fecha_registro").val(),
                                descripcion: $("#descripcion").val().toUpperCase(),
                                monto: $("#valor").val()


                            };
                            su = jQuery("#list_anticipo").jqGrid('addRowData', $("#id_empleadoa").val(), datarow);
                            console.log("CONSULTA" + $("#valor").val());
                            $("#valor_factura").val($("#valor").val());
                            limpiar_input();
                            $("#descripcion").focus();
                            var su;
                            var count = 0;
                            var subtotal = 0;
                            var sub1 = 0;
                            var fil = jQuery("#list_anticipo").jqGrid("getRowData");
                            for (var t = 0; t < fil.length; t++) {
                                var dd = fil[t];
                                subtotal = (subtotal + (parseFloat(dd['monto'])));
                            }
                            $("#valor_total").val(subtotal);

                        }
                    }
                }
            }
        }


    }
}

///////////////////////////////////////////////////////////////////////////////

function extraer_totalh() {
    var select_mesh = $("#select_mesh").val();
//         console.log("dataas"+tipo_tarifa);
    $.ajax({
        type: "POST",
        url: "xmlBuscarAnticipo_consult_1h.php?select_mesh=" + select_mesh + "&id_empleadoh=" + $("#id_empleadoh").val() + "&anio=" + $("#slct_anio_cfh").val(),
        data: "",
        success: function (data) {
            var val = data;
            console.log("dataas" + val);
            if (val != "") {
                var valores;
                valores = val.split("*");
                $("#valor_totalh").val(valores[0]);
                $("#id_anticipoh").val(valores[1]);
                $("#btnGuardaranth").attr("disabled", true);
            } else {
//                $("#id_empleado").val("")

                $("#valor_totalh").val("");
                $("#id_anticipoh").val("");

                $("#btnGuardaranth").attr("disabled", false);
            }
        }

    });
}



function activar_botonh() {
    var select_mesh = $("#select_mesh").val();
//         console.log("dataas"+tipo_tarifa);
    $.ajax({
        type: "POST",
        url: 'xmlBuscarAnticipo_consulth.php?select_mesh=' + select_mesh + "&id_empleadoh=" + $("#id_empleadoh").val() + "&anio=" + $("#select_mesh").val(),
        data: "",
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#id_empleadoh").val(val)
                $("#btnGuardaranth").attr("disabled", true);
            } else {
//                $("#id_empleado").val("")
                $("#btnGuardaranth").attr("disabled", false);
            }
        }
    });
}


///////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
//NO BORRAR
function guardar_anticipo() {
    if (document.getElementById('mixto1Anticipo').checked == true) {
        var xx = "CONTADO";
    } else {
        var xx = "OTROS";
    }
    var tam = jQuery("#list_anticipo").jqGrid("getRowData");
    if ($("#select_mes").val() == "0") {
        $("#select_mes").focus();
        alertify.error("Debe Seleccionar el mes");
    } else {
        if (tam.length == 0) {
            $("#list_anticipo").focus();
            alertify.error("Error... No tiene Anticipos");
        } else {
            $("#btnGuardarant").attr("disabled", true);
            var v1 = new Array();
            var v2 = new Array();
            var v3 = new Array();
            var v4 = new Array();
            var string_v1 = "";
            var string_v2 = "";
            var string_v3 = "";
            var string_v4 = "";
            var fil = jQuery("#list_anticipo").jqGrid("getRowData");
            for (var i = 0; i < fil.length; i++) {
                var datos = fil[i];
                v1[i] = datos['id_empleado'];
                v2[i] = datos['fecha_registro'];
                v3[i] = datos['descripcion'];
                v4[i] = datos['monto'];
            }
            for (i = 0; i < fil.length; i++) {
                string_v1 = string_v1 + "|" + v1[i];
                string_v2 = string_v2 + "|" + v2[i];
                string_v3 = string_v3 + "|" + v3[i];
                string_v4 = string_v4 + "|" + v4[i];
            }
            guardar_serie(() => {
                $.ajax({
                    type: "POST",
                    url: "guardar_anticipo.php",
                    data: "slct_anio_cf=" + $("#slct_anio_cf").val() + "&select_mes=" + $("#select_mes").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&valor_total=" + $("#valor_total").val() + "&fecha_actual=" + $("#fecha_actual").val() + "&mixtoAnticipo=" + xx + "&id_empleadoa=" + $("#id_empleadoa").val(),
                    success: function (data) {
                        var val = data;
                        if (val != 0) {
                            alertify.alert("Guardado correctamente", function () {
                                window.open("../../reportes/transacciones_an.php?hoja=A5&id=" + val, '_blank');
                                setTimeout(function () {
                                    location.reload();
                                }, 4000);

                            });
                        }
                    }

                });//FIN AJAX
            });
        }
    }
}
////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////

//NO BORRAR
function extraer_total() {
    var select_mes = $("#select_mes").val();
    $.ajax({
        type: "POST",
        url: "xmlBuscarAnticipo_consult_1.php?select_mes=" + select_mes + "&id_empleado=" + $("#id_empleadoa").val() + "&anio=" + $("#slct_anio_cf").val(),
        data: "",
        success: function (data) {
            var val = data;
            var valores;
            valores = val.split("*");
            if (val != "") {
                $("#valor_total").val(valores[0]);
                $("#id_anticipo").val(valores[1]);
            } else {
                $("#btnGuardarant").attr("disabled", false);
                $("#btnModificarant").attr("disabled", false);
            }
        }
    });
}
//NO BORRAR
function extraer_activo() {
    var select_mes = $("#select_mes").val();
//         console.log("dataas"+tipo_tarifa);
    $.ajax({
        type: "POST",
        url: "xmlBuscarAnticipo_consult_act.php?select_mes=" + select_mes + "&id_empleado=" + $("#id_empleadoa").val() + "&anio=" + $("#slct_anio_cf").val(),
        data: "",
        success: function (data) {
            var val = data;
            var valores;
            valores = val.split("*");
            if (val != "") {
                if (valores[1] == "Activo") {


                    $("#btnGuardarant").attr("disabled", true);
                    $("#btnModificarant").attr("disabled", false);
                } else {
//                     $("#valor_total").val(valores[0]);
//                    $("#id_anticipo").val(valores[1]);
                    $("#btnGuardarant").attr("disabled", true);
                    $("#btnModificarant").attr("disabled", true);
                }
            } else {
                $("#btnGuardarant").attr("disabled", false);
                $("#btnModificarant").attr("disabled", false);
            }



        }
    });
}
//NO BORRAR
function activar_boton() {
    var select_mes = $("#select_mes").val();
//         console.log("dataas"+tipo_tarifa);
    $.ajax({
        type: "POST",
        url: 'xmlBuscarAnticipo_consult.php?select_mes=' + select_mes + "&id_empleado=" + $("#id_empleadoa").val() + "&anio=" + $("#slct_anio_cf").val(),
        data: "",
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#id_empleadoa").val(val)
                $("#btnGuardarant").attr("disabled", true);
            } else {
//                $("#id_empleado").val("")
                $("#btnGuardarant").attr("disabled", false);
            }
        }
    });
}
function cargar_rubro_tarifa() {


    if ($("#id_empleadoa").val() != "") {
        var select_mes = $("#select_mes").val();
        $("#list_anticipo").jqGrid('setGridParam', {
            url: 'xmlBuscarAnticipo.php?id_clase=' + select_mes + "&id_empleado=" + $("#id_empleadoa").val() + "&anio=" + $("#slct_anio_cf").val(),
            datatype: 'xml',
        }).trigger('reloadGrid');
        activar_boton();
        extraer_total();
        extraer_activo();
    } else {
        alertify.error("Seleccione Nomina");
    }

}
function guardar_aporte_iess() {

    if ($("#descripcion_iess").val() === "") {
        $("#descripcion_iess").focus();
        alertify.error("Ingrese");
    } else {
        if ($("#valor_aporte").val() === "") {
            $("#valor_aporte").focus();
            alertify.error("Ingrese");
        } else {
            $("#btnGuardarva").attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "../parametros_iess/guardar_parametros_iess.php",
                data: "descripcion_iess=" + $("#descripcion_iess").val() + "&valor_aporte=" + $("#valor_aporte").val(),
                success: function (data) {
                    var val = data;
                    if (val == 1) {
                        alertify.success('Datos Agregados Correctamente');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        if (val == 11) {
                            alertify.error('DESCRIPCION YA EXISTE');
                            $("#btnGuardarva").attr("disabled", false);
                        }
                    }
                }
            });
            // }
        }

    }

}
function guardar_cargo() {
    if ($("#nombre_cargo").val() === "") {
        $("#nombre_cargo").focus();
        alertify.error("Ingrese");
    } else {
        if ($("#sueldo_base").val() === "") {
            $("#sueldo_base").focus();
            alertify.error("Ingrese");
        } else {
            if ($("#codigo_sectorial").val() === "") {
                $("#codigo_sectorial").focus();
                alertify.error("Ingrese");
            } else {
                $("#btnGuardarcargo").attr("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "../cargo/guardar_cargo.php",
                    data: "nombre_cargo=" + $("#nombre_cargo").val() + "&sueldo_base=" + $("#sueldo_base").val() + "&codigo_sectorial=" + $("#codigo_sectorial").val(),
                    success: function (data) {
                        var val = data;
                        if (val == 1) {
                            alertify.success('Datos Agregados Correctamente');
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                        } else {
                            if (val == 11) {
                                alertify.error('NOMBRE CARGO YA EXISTE');
                                $("#btnGuardarcargo").attr("disabled", false);
                            }
                        }
                    }
                });
                // }
            }
        }

    }

}



function validar_fecha_registro() {


    var fecha = $("#fecha_registro").val();
    var fecha_split = fecha.split('-');
// seteo la fecha con los datos del string
    var nueva_fecha = fecha_split[1];
    if (nueva_fecha == '01') {
        nueva_fecha = 'Enero';
    }
    if (nueva_fecha == '02') {
        nueva_fecha = 'Febrero';
    }
    if (nueva_fecha == '03') {
        nueva_fecha = 'Marzo';
    }
    if (nueva_fecha == '04') {
        nueva_fecha = 'Abril';
    }
    if (nueva_fecha == '05') {
        nueva_fecha = 'Mayo';
    }
    if (nueva_fecha == '06') {
        nueva_fecha = 'Junio';
    }
    if (nueva_fecha == '07') {
        nueva_fecha = 'Julio';
    }
    if (nueva_fecha == '08') {
        nueva_fecha = 'Agosto';
    }
    if (nueva_fecha == '09') {
        nueva_fecha = 'Septiembre';
    }
    if (nueva_fecha == '10') {
        nueva_fecha = 'Octubre';
    }
    if (nueva_fecha == '11') {
        nueva_fecha = 'Noviembre';
    }
    if (nueva_fecha == '12') {
        nueva_fecha = 'Diciembre';
    }
    if (nueva_fecha == $("#select_mes").val()) {
        alertify.success("fecha correcta");
    } else {
        $("#fecha_registro").val("")
        alertify.error("LA FECHA INGRESADA NO CORRESPONDE AL MES SELECCIONADO");
    }




}

function eliminar_nomina() {
    if ($("#id_empleadon").val() === "") {
        alertify.error("Seleccione  nómina");
    } else {
        $("#clave_permison").dialog("open");
    }
}
function eliminar_documento() {
    if ($("#id_cargo").val() === "") {
        alertify.error("Seleccione Tipo de Documento");
    } else {
        $("#clave_permisocc").dialog("open");
    }
}
function eliminar_anticipo() {
    if ($("#id_anticipo").val() === "") {
        alertify.error("Seleccione Tipo de Documento");
    } else {
        $("#clave_permisoaa").dialog("open");
    }
}

function eliminar_multa() {
    if ($("#id_anticipom").val() === "") {
        alertify.error("Seleccione Tipo de Documento");
    } else {
        $("#clave_permisom").dialog("open");
    }
}
function calculo100() {
    if ($("#sueldoh").val() != 0 && $("#hora_extra").val() != 0) {
        var val3 = parseFloat($("#sueldoh").val() / 30);
        var sueldo_hora = val3 / 8;
        console.log(sueldo_hora + "ven");
        var resulente = sueldo_hora.toFixed(2);
        $("#valor_horat").val(resulente);
        var result_hora_extra = resulente * parseFloat($("#hora_extra").val());
        console.log(result_hora_extra + "ven");
        var total_result_hora_extra = result_hora_extra * 2;
        $("#valorh").val(total_result_hora_extra.toFixed(2));
    } else {
        alertify.error("ERROR..INGRESAR HORA EXTRA O SALARIO MENSUAL  ")
    }
}
function calculo50() {

    if ($("#sueldoh").val() != 0 && $("#hora_extra").val() != 0) {
        var val3 = parseFloat($("#sueldoh").val() / 30);
        var sueldo_hora = val3 / 8;
        console.log(sueldo_hora + "ven");
        var resulente = sueldo_hora.toFixed(2);
        $("#valor_horat").val(resulente);
        var result_hora_extra = resulente * parseFloat($("#hora_extra").val());
        console.log(result_hora_extra + "ven");
        var total_result_hora_extra = result_hora_extra * 1.50;
        $("#valorh").val(total_result_hora_extra.toFixed(2));
    } else {
        alertify.error("ERROR..INGRESAR HORA EXTRA O SALARIO MENSUAL ")
    }
}

////////////////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////////////////


function validar_accesocc() {
    if ($("#clavecc").val() == "") {
        $("#clavecc").focus();
        alertify.error("Ingrese la clave");
    } else {

        $.ajax({
            url: '../../procesos/validar_acceso.php',
            type: 'POST',
            data: "clave=" + $("#clavecc").val(),
            success: function (data) {
                var val = data;
                if (val == 0) {
                    $("#clavecc").val("");
                    $("#clavecc").focus();
                    alertify.error("Error... La clave es incorrecta ingrese nuevamente");
                } else {
                    if (val == 1) {
                        $("#segurocc").dialog("open");
                    }
                }
            }
        });
    }
}
function validar_accesoaa() {
    if ($("#claveaa").val() == "") {
        $("#claveaa").focus();
        alertify.error("Ingrese la clave");
    } else {

        $.ajax({
            url: '../../procesos/validar_acceso.php',
            type: 'POST',
            data: "clave=" + $("#claveaa").val(),
            success: function (data) {
                var val = data;
                if (val == 0) {
                    $("#claveaa").val("");
                    $("#claveaa").focus();
                    alertify.error("Error... La clave es incorrecta ingrese nuevamente");
                } else {
                    if (val == 1) {
                        $("#seguroaa").dialog("open");
                    }
                }
            }
        });
    }
}




function aceptarcc() {
    $.ajax({
        type: "POST",
        url: "eliminar_cargo.php",
        data: "id_cargo=" + $("#id_cargo").val(),
        success: function (data) {
            var val = data;
            if (val == 1) {
                alertify.error('Error.. Tiene movimientos en el sistema');
                setTimeout(function () {
                    location.reload();
                }, 4000);
            } else {
                alertify.success('Eliminado Correctamente');
                setTimeout(function () {
                    location.reload();
                }, 4000);
            }
        }
    });
}


function aceptaraa() {
    $.ajax({
        type: "POST",
        url: "eliminar_anticipo.php",
        data: "id_anticipo=" + $("#id_anticipo").val() + "&id_empleado=" + $("#id_empleadoa").val() + "&anio=" + $("#slct_anio_cf").val() + "&select_mes=" + $("#select_mes").val(),
        success: function (data) {
            var val = data;

            alertify.success('Eliminado Correctamente');
            setTimeout(function () {
                location.reload();
            }, 4000);

        }
    });
}



function cancelaraa() {
    $("#seguroaa").dialog("close");
    $("#clave_permisoaa").dialog("close");
    $("#claveaa").val("");
}

function abrirDialogo_aporte_iess() {
    $("#aporte_iess").dialog("open");
}

function nuevo_nomina() {
    location.reload();
}
// NO BORRAR
function modificar_anticipo() {
    if (document.getElementById('mixto1Anticipo').checked == true) {
        var xx = "CONTADO";
    } else {
        var xx = "OTROS";
    }

    if ($("#id_empleadoa").val() != '') {
        $("#btnGuardarant").attr("disabled", true);
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

        var fil = jQuery("#list_anticipo").jqGrid("getRowData");
        for (var i = 0; i < fil.length; i++) {
            var datos = fil[i];
            v1[i] = datos['id_empleado'];
            v2[i] = datos['fecha_registro'];
            v3[i] = datos['descripcion'];
            v4[i] = datos['monto'];
            v5[i] = datos['estado'];
        }
        for (i = 0; i < fil.length; i++) {
            string_v1 = string_v1 + "|" + v1[i];
            string_v2 = string_v2 + "|" + v2[i];
            string_v3 = string_v3 + "|" + v3[i];
            string_v4 = string_v4 + "|" + v4[i];
            string_v5 = string_v5 + "|" + v5[i];
        }
        console.log("modifico1");
        guardar_serie(() => {
            $.ajax({
                type: "POST",
                url: "modificar_anticipo.php",
                data: "slct_anio_cf=" + $("#slct_anio_cf").val() + "&select_mes=" + $("#select_mes").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&valor_total=" + $("#valor_total").val() + "&fecha_actual=" + $("#fecha_registro").val() + "&id_empleado=" + $("#id_empleadoa").val() + "&mixtoAnticipo=" + xx,
                success: function (data) {
                    var val = data;
                    if (val != 0) {
                        alertify.alert(" Modificado correctamente", function () {
                            window.open("../../reportes/transacciones_an.php?hoja=A5&id=" + val, '_blank');
                            setTimeout(function () {
                                location.reload();
                            }, 4000);

                        });
                    }
                }
            });
        });
    } else {
        alertify.error("Error... Selecciones una Tarifa");
    }
}



function ValidNum() {
    if (event.keyCode < 48 || event.keyCode > 57) {
        event.returnValue = false;
    }
    return true;
}

function Num_Let() {
    if ((event.keyCode !== 32) && (event.keyCode < 65) || (event.keyCode > 90) && (event.keyCode < 97) || (event.keyCode > 122)) {
        event.returnValue = false;
    }
    return true;
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
function inicializarSelectAnioFuncio() {
    var currentyearf = new Date().getFullYear();
    for (var i = currentyearf; i > currentyearf - 50; i--) {
        var opt = new Option(i, i, false, false);
        $("#slct_anio_cf")[0].append(opt);
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
                        hidden: true,
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
function cambio_ret_fuenteSguia() {
    if (document.getElementById("mixto2Anticipo").checked) {
        $("#formaspago_mixto").attr("disabled", false);
        $("#cuenta_anti_mixto").show();
        $("#mixto_anti").show();
        $("#grid_mixto_agri").show();
    } else if (document.getElementById("mixto1Anticipo").checked) {
        $("#formaspago_mixto").attr("disabled", true);
        $("#mixto_anti").hide();
        $("#grid_mixto_agri").hide();
        $("#cuenta_anti_mixto").hide();
    }
}
function agregar() {
    if (document.getElementById("mixto2Anticipo").checked) {

        var subtotal_adelanto = 0;
        var subtotal_adelanto1 = 0;

//        $("#adelanto").val(subtotal_adelanto.toFixed(2));
        var subtotal1 = 0;
        var subtotal11 = 0;
//        var fil = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
//        for (var t = 0; t < fil.length; t++) {
//            var dd = fil[t];
//            subtotal1 =
//                    parseFloat($("#valor_formas").val()) + parseFloat(dd["valor"]);
//        }

        subtotal11 = parseFloat($("#valor_formas").val()) + parseFloat($("#cantidad_mixto").val());
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

                            if ($("#formaspago_mixto").val() == "Transferencias" && $("#idCuenta").val() == "") {
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


                                    var datarow = {
                                        id_f_v_mix: (count = count + 1),
                                        id_factura_venta: parseFloat($("#id_anticipo").val()) + 1,
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
                                            id_factura_venta: parseFloat($("#id_anticipo").val()) + 1,
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
                                        var fil = jQuery("#listPagoreten_mixto").jqGrid("getRowData");
                                        for (var t = 0; t < fil.length; t++) {
                                            var dd = fil[t];
                                            subtotal = subtotal + parseFloat(dd["valor"]);
                                        }
                                        $("#cantidad_mixto").val(subtotal.toFixed(2));
                                        var subtotal_adelanto1 = parseFloat($("#valor_factura").val()) - parseFloat($("#cantidad_mixto").val());

                                        $("#valor_factura_saldo").val(subtotal_adelanto1.toFixed(2));
                                        $("#valor_formas").val("");
                                        $("#tarjetas").val("");
                                        $("#num_tarjeta").val("");
                                        $("#formaspago_mixto").focus();
                                    }
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
function abrirCuenta() {
    $("#cuentas").dialog("open");
}
function abrirCuenta_reten() {
    $("#cuentas_reten").dialog("open");
}
function inicio() {

    $("#btnAgregar_mixto").click(function (e) {
        e.preventDefault();
        agregar();
    });
    $("#formaspago_mixto").on("change", function () {
        if ($("#formaspago_mixto").val() == "Transferencias") {
            $("#cuenta_contable").attr("disabled", false);
            $("#btnCuenta").attr("disabled", false);
            $("#cuenta_contable").val("");
            $("#idCuenta").val("");
            $("#fecha_vencimiento").hide();
            $("#num_tarjeta").attr("disabled", false);
        } else if ($("#formaspago_mixto").val() == "Contado") {
            $("#cuenta_contable").attr("disabled", true);
            $("#btnCuenta").attr("disabled", true);
            $("#cuenta_contable").val("");
            $("#idCuenta").val("");
            $("#fecha_vencimiento").hide();
            $("#num_tarjeta").attr("disabled", true);

        } else if ($("#formaspago_mixto").val() == "Cheque") {
            $("#cuenta_contable").attr("disabled", false);
            $("#btnCuenta").attr("disabled", false);
            $("#cuenta_contable").val("");
            $("#idCuenta").val("");
            $("#fecha_vencimiento").hide();
            $("#num_tarjeta").attr("disabled", false);

        }

    });
    $("#mixto_anti").hide();
    $("#cuenta_anti_mixto").hide();
    $("#grid_mixto_agri").hide();
    $("#mixto1Anticipo").on("change", cambio_ret_fuenteSguia);
    $("#mixto2Anticipo").on("change", cambio_ret_fuenteSguia);
    listaPagoRetencion();
    $("#fecha_registro").val(new Date().toLocaleDateString("fr-CA"));

    $("#list_grid_cargo").jqGrid('setGridParam', {
        url: '../cargo/datos_cargo_grid.php',
        datatype: 'xml',
    }).trigger('reloadGrid');


    $("#list_aporte").jqGrid('setGridParam', {
        url: '../parametros_iess/datos_parametros_grid.php',
        datatype: 'xml',
    }).trigger('reloadGrid');

    $("#pf_check_unocinco").change(function (e) {
        controlarCheckbox50porcientoPF(e);
    });
    $("#pf_check_sincuenta").change(function (e) {
        controlarCheckbox50porcientoPF(e);
    });
    $("#pf_check_dos").change(function (e) {
        controlarCheckbox50porcientoPF(e);
    });
    $("#pf_check_dividido").change(function (e) {
        controlarCheckbox50porcientoPFs(e);
    });
    $("#pf_check_acumulado").change(function (e) {
        controlarCheckbox50porcientoPFs(e);
    });
    inicializarSelectAnioFuncio();
    // buscar clientes identificacion
    $("#cedula_empleado").autocomplete({
        source: "buscar_cliente.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#cedula_empleado").val(ui.item.value);
            $("#id_empleadoa").val(ui.item.id_cliente);
            $("#nombres_empleado").val(ui.item.nombre_cliente);
            $("#direccion_empleado").val(ui.item.direccion_cliente);
            $("#salario_empleado").val(ui.item.salario_empleado);
            return false;
        },
        select: function (event, ui) {
            $("#cedula_empleado").val(ui.item.value);
            $("#id_empleadoa").val(ui.item.id_cliente);
            $("#nombres_empleado").val(ui.item.nombre_cliente);
            $("#direccion_empleado").val(ui.item.direccion_cliente);
            $("#salario_empleado").val(ui.item.salario_empleado);
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
    };
    // buscar clientes identificacion
    $("#nombres_empleado").autocomplete({
        source: "buscar_cliente_nombre.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#cedula_empleado").val(ui.item.ruc_ci);
            $("#id_empleadoa").val(ui.item.id_cliente);
            $("#nombres_empleado").val(ui.item.value);
            $("#direccion_empleado").val(ui.item.direccion_cliente);
            return false;
        },
        select: function (event, ui) {
            $("#cedula_empleado").val(ui.item.ruc_ci);
            $("#id_empleadoa").val(ui.item.id_cliente);
            $("#nombres_empleado").val(ui.item.value);
            $("#direccion_empleado").val(ui.item.direccion_cliente);
            return false;
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
    };
    // fin
    $("#fecha_actual").datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');
    $("[data-mask]").inputmask();
    alertify.set({delay: 4000});

    $("#ruc_ci").attr("maxlength", "10");
    $("#ruc_ci").keypress(ValidNum);
    $("#tele_referencia_nomina").attr("maxlength", "10");
    $("#tele_referencia_nomina").keypress(ValidNum);
    $("#nro_telefono").validCampoFranz("0123456789");
    $("#nro_celular").validCampoFranz("0123456789");
    $("#valor_aporte").validCampoFranz("0123456789.");
    $("#ruc_ci").keyup(function () {
        $.ajax({
            type: "POST",
            url: "comparar_cedulas.php",
            data: "cedula=" + $("#ruc_ci").val(),
            success: function (data) {
                var val = data;
                if (val == 1) {
                    $("#ruc_ci").val("");
                    $("#ruc_ci").focus();
                    alertify.error("Error... esta registrado");
                } else {

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
                                alertify.error('El número de cédula es incorrecto.');
                                $("#ruc_ci").val("");
                            } else {
                                if ($("#ruc_ci").val() === "0000000000") {
                                    alertify.error('El número de cédula es incorrecto.');
                                    $("#ruc_ci").val("");
                                } else {
                                    alertify.success('El número de cédula es correcto.');
                                }
                            }
                        }
                    }



                    var ruc = numero.substr(10, 13);
                    var digito3 = numero.substring(2, 3);
                    if (ruc == "001") {
                        if (digito3 < 6) {
                            if (nat == true) {
                                if (digitoVerificador != d10) {
                                    alertify.error('El ruc persona natural es incorrecto.');
                                    $("#ruc_ci").val("");
                                } else {
                                    alertify.success('El ruc persona natural es correcto.');
                                }
                            }
                        } else {
                            if (digito3 == 6) {
                                if (pub == true) {
                                    if (digitoVerificador != d9) {
                                        alertify.error('El ruc público es incorrecto.');
                                        $("#ruc_ci").val("");
                                    } else {
                                        alertify.success('El ruc público es correcto.');
                                    }
                                }
                            } else {
                                if (digito3 == 9) {
                                    if (pri == true) {
                                        if (digitoVerificador != d10) {
                                            if (d10 == 4 || d10 == 6 || d10 == 9) {
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
                        if (numero.length === 13) {
                            alertify.error('El ruc es incorrecto.');
                            $("#ruc_ci").val("");
                        }
                    }
                }


            }
        });
    });


    $("#btnBuscar").click(function (e) {
        e.preventDefault();
    });
    $("#btnEliminar").click(function (e) {
        e.preventDefault();
    });
    $("#btnNuevo").click(function (e) {
        e.preventDefault();
    });
    $("#btnCuenta").click(function (e) {
        e.preventDefault();
    });
    $("#btnAcceder").click(function (e) {
        e.preventDefault();
    });
    $("#btnAccederm").click(function (e) {
        e.preventDefault();
    });
    $("#btnAccederh").click(function (e) {
        e.preventDefault();
    });
    ///////
    $("#btnGuardarcargo").click(function (e) {
        e.preventDefault();
    });

    $("#btnModificarva").click(function (e) {
        e.preventDefault();
    });
    $("#btnEliminarcargo").click(function (e) {
        e.preventDefault();
    });

    $("#btnNuevocargo").click(function (e) {
        e.preventDefault();
    });
    //  NO BORRAR
    $("#btnGuardarant").click(function (e) {
        e.preventDefault();
    });

    $("#btnBuscarva").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardarants").click(function (e) {
        e.preventDefault();
    });
    //NO BORRAR
    $("#btnModificarant").click(function (e) {
        e.preventDefault();
    });
    //NO BORRAR
    $("#btnAnularant").click(function (e) {
        e.preventDefault();
    });


    $("#btnModificarantm").click(function (e) {
        e.preventDefault();
    });
    $("#btnModificarantd").click(function (e) {
        e.preventDefault();
    });
    $("#btnModificaranth").click(function (e) {
        e.preventDefault();
    });

    $("#btnGuardarantd").click(function (e) {
        e.preventDefault();
    });
    $("#cuentas").dialog(dialogo_cuenta);
    $("#cuentas_reten").dialog(dialogo_cuenta);
    $("#btnGuardarcargo").on("click", guardar_cargo);
    $("#btnGuardarva").on("click", guardar_aporte_iess);
    //NO BORRAR
    $("#btnGuardarant").on("click", guardar_anticipo);





    $("#btnEliminarcargo").on("click", eliminar_documento);
    //NO BORRAR
    $("#btnAnularant").on("click", eliminar_anticipo);


    $("#btnBuscarva").on("click", abrirDialogo_aporte_iess);
    //NO BORRAR
    $("#btnModificarant").on("click", modificar_anticipo);


    $("#fecha_registro").on("change", validar_fecha_registro);


    $("#descripcion").on("keypress", enter);


    $("#valor").validCampoFranz("0123456789.");
    $("#valor").on("keypress", enter2);





    $("#btnBuscar").on("click", abrirDialogo);


    $("#pf_check_sincuenta").on("click", calculo50);
    $("#pf_check_dos").on("click", calculo100);
    $("#btnEliminar").on("click", eliminar_nomina);
    $("#btnAceptarcc").on("click", aceptarcc);

    $("#btnAceptaraa").on("click", aceptaraa);

    $("#btnSaliraa").on("click", cancelaraa);



    $("#btnAccederaa").on("click", validar_accesoaa);

    $("#btnAccedercc").on("click", validar_accesocc);

    $("#btnNuevo").on("click", nuevo_nomina);
    $("#btnCuenta").on("click", abrirCuenta);
    $("#nominas").dialog(dialogo);
    $("#cuentas").dialog(dialogo_cuenta);


    $("#clave_permisoaa").dialog(dialogo3);



    $("#seguroaa").dialog(dialogo4);



    $("#cargo").dialog(dialogoTipo_documento);
    $("#aporte_iess").dialog(dialogoTipo_aporte_iess);
    $("#select_mes").on("change", cargar_rubro_tarifa);

    ////////////////////////////////////////////////

    ///////////////////
    jQuery("#list_va").jqGrid({
        url: '../parametros_iess/datos_parametros_iess.php',
        datatype: 'xml',
        colNames: ['Código', 'Aporte IESS', 'Valor Aporte'],
        colModel: [
            {name: 'id_parametro_iess', index: 'id_parametro_iess', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'descripcion_iess', index: 'descripcion_iess', editable: true, align: 'center', width: '120', search: false, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'valor_aporte', index: 'valor_aporte', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
        ],
        rowNum: 10,
        width: 830,
        height: 200,
        rowList: [10, 20, 30],
        pager: jQuery('#pagerva'),
        sortname: 'id_parametro_iess',
        shrinkToFit: false,
        sortorder: 'desc',
        caption: 'Lista ',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list_va").jqGrid('getGridParam', 'selrow');
            jQuery('#list_va').jqGrid('restoreRow', id);
            jQuery("#list_va").jqGrid('GridToForm', id, "#nomina_form");
            $("#btnGuardarva").attr("disabled", true);
            $("#aporte_iess").dialog("close");
        }
    }).jqGrid('navGrid', '#pagerva',
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
    jQuery("#list_va").jqGrid('navButtonAdd', '#pagerva', {caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#list_va").jqGrid('getGridParam', 'selrow');
            jQuery('#list_va').jqGrid('restoreRow', id);
            if (id) {
                jQuery("#list_va").jqGrid('GridToForm', id, "#nomina_form");
                $("#btnGuardarva").attr("disabled", true);
                $("#aporte_iess").dialog("close");
            } else {
                alertify.alert("Seleccione un fila");
            }
        }
    });

    ///////////////////
    //
    jQuery("#list_aporte").jqGrid({
        url: '../parametros_iess/datos_parametros_grid.php',
        datatype: 'xml',
        colNames: ['Código', 'Descripcion ', 'Aporte%'],
        colModel: [
            {name: 'id_parametro_iess', index: 'id_parametro_iess', editable: true, hidden: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'descripcion_iess', index: 'descripcion_iess', editable: true, align: 'center', width: '200', search: false, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'valor_aporte', index: 'valor_aporte', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
        ],
        rowNum: 10,
        width: 400,
        height: 100,
        rowList: [10, 20, 30],
        pager: jQuery('#pager_aporte'),
        sortname: 'id_parametro_iess',
        shrinkToFit: false,
        sortorder: 'desc',
        caption: 'Lista ',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list_aporte").jqGrid('getGridParam', 'selrow');
            jQuery('#list_aporte').jqGrid('restoreRow', id);
            jQuery("#list_aporte").jqGrid('GridToForm', id, "#nomina_form");
            $("#btnGuardarva").attr("disabled", true);
            $("#btnModificarva").attr("disabled", false);
            $("#aporte_iess").dialog("close");
        }
    }).jqGrid('navGrid', '#pager_aporte',
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
    jQuery("#list_aporte").jqGrid('navButtonAdd', '#pager', {caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#list_aporte").jqGrid('getGridParam', 'selrow');
            jQuery('#list_aporte').jqGrid('restoreRow', id);
            if (id) {
                jQuery("#list_aporte").jqGrid('GridToForm', id, "#nomina_form");
                $("#btnGuardarva").attr("disabled", true);
                $("#btnModificarva").attr("disabled", false);
                $("#aporte_iess").dialog("close");
            } else {
                alertify.alert("Seleccione un fila");
            }
        }
    });
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
/////////////////

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    jQuery("#list_grid_cargo").jqGrid({

        url: '../cargo/datos_cargo_grid.php',
        colNames: ['id_cargo', 'Nombre Cargo', 'Sueldo Base', 'Codigo Sectorial'],
        colModel: [
//            {name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: {keys: false, delbutton: true, editbutton: false}},
            {name: 'id_cargo', index: 'id_cargo', editable: false, search: false, hidden: true, editrules: {edithidden: false}, align: 'center', frozen: true, width: 3},
            {name: 'nombre_cargo', index: 'nombre_cargo', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'left', frozen: true, width: 15},
            {name: 'sueldo_base', index: 'sueldo_base', editable: false, search: false, hidden: false, editrules: {required: true}, align: 'left', frozen: true, width: 3},
            {name: 'codigo_sectorial', index: 'codigo_sectorial', editable: false, search: false, hidden: false, editrules: {required: true}, align: 'left', frozen: true, width: 5}

        ],
        rowNum: 30,
        width: 450,
        height: 200,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager_grid_cargo'),
        sortname: 'id_cargo',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'clientArray',
        shrinkToFit: true,
        editoptions: {

            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {
                console.log("entroooaww111");
                var id = jQuery("#list_grid_cargo").jqGrid('getGridParam', 'selrow');
                jQuery('#list_grid_cargo').jqGrid('restoreRow', id);
                var ret = jQuery("#list_grid_cargo").jqGrid('getRowData', id);
                var fil = jQuery("#list_grid_cargo").jqGrid("getRowData");
                var su = jQuery("#list_grid_cargo").jqGrid('delRowData', rowid);
                if (su === true) {
                    rp_ge.processing = true;
                    $(".ui-icon-closethick").trigger('click');
                }
                return true;
            },
            processing: true
        },
        afterSaveCell: function (rowid, name, val, iRow, iCol) {
            console.log("entroooaww");
            var id = jQuery("#list_grid_cargo").jqGrid('getGridParam', 'selrow');
            jQuery('#list_grid_cargo').jqGrid('restoreRow', id);
            var ret = jQuery("#list_grid_cargo").jqGrid('getRowData', id);
        },
        delOptions: {
            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {
                var id = jQuery("#list_grid_cargo").jqGrid('getGridParam', 'selrow');
                jQuery('#list_grid_cargo').jqGrid('restoreRow', id);
                var ret = jQuery("#list_grid_cargo").jqGrid('getRowData', id);
                var su = jQuery("#list_grid_cargo").jqGrid('delRowData', rowid);
                if (su === true) {
                    rp_ge.processing = true;
                    $(".ui-icon-closethick").trigger('click');
                }
                return true;
            },
            processing: true
        }

    });
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    jQuery("#list_anticipos").jqGrid({

        datatype: "local",
        colNames: ['', 'ID ', 'ID EMPLEADO', 'Fecha', 'Fecha Inicio', 'Fecha Hasta', 'SUELDO', 'Hora Extra', 'XIII SUELDO', 'XIV SUELDO', 'ACU./DIVIDIDO'],
        colModel: [
            {name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: {keys: false, delbutton: true, editbutton: false}},
            {name: 'id_decimos', index: 'id_decimos', editable: false, search: false, hidden: true, editrules: {edithidden: false}, align: 'center', frozen: true, width: 3},
            {name: 'id_empleados', index: 'id_empleados', editable: false, search: false, hidden: true, editrules: {edithidden: false}, align: 'center', frozen: true, width: 3},
            {name: 'fecha_registros', index: 'fecha_registros', editable: false, search: false, hidden: false, editrules: {required: true}, align: 'center', frozen: true, width: 10},
            {name: 'fecha_inicio', index: 'fecha_inicio', editable: false, hidden: false, frozen: true, editrules: {required: true}, align: 'center', width: 10},
            {name: 'fecha_hasta', index: 'fecha_hasta', editable: false, frozen: true, hidden: false, editrules: {required: true}, align: 'center', width: 10},
            {name: 'sualdo', index: 'sualdo', editable: false, frozen: true, hidden: false, editrules: {required: true}, align: 'center', width: 10},
            {name: 'hora_extra', index: 'hora_extra', editable: false, frozen: true, search: false, hidden: false, editrules: {required: true}, align: 'center', frozen: true, width: 10},
            {name: 'tercer_sueldo', index: 'tercer_sueldo', editable: false, frozen: true, hidden: false, editrules: {required: true}, align: 'center', width: 10},
            {name: 'cuarto_sueldo', index: 'cuarto_sueldo', editable: false, frozen: true, search: false, hidden: false, editrules: {required: true}, align: 'center', frozen: true, width: 10},
            {name: 'tipo_decimo', index: 'tipo_decimo', editable: false, frozen: true, search: false, hidden: false, editrules: {required: true}, align: 'center', frozen: true, width: 10}

        ],
        rowNum: 30,
        width: 700,
        height: 50,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager_anticipos'),
        sortname: 'id_decimos',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'clientArray',
        shrinkToFit: true,
        editoptions: {

            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {
                console.log("entroooaww111");
                var id = jQuery("#list_anticipos").jqGrid('getGridParam', 'selrow');
                jQuery('#list_anticipos').jqGrid('restoreRow', id);
                var ret = jQuery("#list_anticipos").jqGrid('getRowData', id);
                var fil = jQuery("#list_anticipos").jqGrid("getRowData");
                var su = jQuery("#list_anticipos").jqGrid('delRowData', rowid);
                if (su === true) {
                    rp_ge.processing = true;
                    $(".ui-icon-closethick").trigger('click');
                }
                return true;
            },
            processing: true
        },
        afterSaveCell: function (rowid, name, val, iRow, iCol) {
            console.log("entroooaww");
            var id = jQuery("#list_anticipos").jqGrid('getGridParam', 'selrow');
            jQuery('#list_anticipos').jqGrid('restoreRow', id);
            var ret = jQuery("#list_anticipos").jqGrid('getRowData', id);
        },
        delOptions: {
            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {
                var id = jQuery("#list_anticipos").jqGrid('getGridParam', 'selrow');
                jQuery('#list_anticipos').jqGrid('restoreRow', id);
                var ret = jQuery("#list_anticipos").jqGrid('getRowData', id);
                var su = jQuery("#list_anticipos").jqGrid('delRowData', rowid);
                if (su === true) {
                    rp_ge.processing = true;
                    $(".ui-icon-closethick").trigger('click');
                }
                return true;
            },
            processing: true
        }

    });

    jQuery("#list_anticipo").jqGrid({

        datatype: "local",
        colNames: ['', 'ID ', 'ID EMPLEADO', 'Fecha', 'Descripcion', 'valor', 'MARCADO'],
        colModel: [
            {name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: {keys: false, delbutton: true, editbutton: false}},
            {name: 'id_anticipo', index: 'id_anticipo', editable: false, search: false, hidden: true, editrules: {edithidden: false}, align: 'center', frozen: true, width: 3},
            {name: 'id_empleado', index: 'id_empleado', editable: false, search: false, hidden: true, editrules: {edithidden: false}, align: 'center', frozen: true, width: 3},
            {name: 'fecha_registro', index: 'fecha_registro', editable: false, search: false, hidden: false, editrules: {required: true}, align: 'center', frozen: true, width: 20},
            {name: 'descripcion', index: 'descripcion', editable: false, frozen: true, editrules: {required: true}, align: 'center', width: 10},
            {name: 'monto', index: 'monto', editable: false, frozen: true, search: false, hidden: false, editrules: {required: true}, align: 'center', frozen: true, width: 10},
            {name: 'estado', index: 'estado', editable: false, frozen: true, search: false, hidden: true, editrules: {required: true}, align: 'center', frozen: true, width: 10}
        ],
        rowNum: 30,
        width: 700,
        height: 100,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager_anticipo'),
        sortname: 'id_anticipo',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'clientArray',
        shrinkToFit: true,
        editoptions: {

            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {
                console.log("entroooaww111");
                var id = jQuery("#list_anticipo").jqGrid('getGridParam', 'selrow');
                jQuery('#list_anticipo').jqGrid('restoreRow', id);
                var ret = jQuery("#list_anticipo").jqGrid('getRowData', id);
                var fil = jQuery("#list_anticipo").jqGrid("getRowData");
                var su = jQuery("#list_anticipo").jqGrid('delRowData', rowid);
                if (su === true) {
                    rp_ge.processing = true;
                    $(".ui-icon-closethick").trigger('click');
                }
                return true;
            },
            processing: true
        },
        afterSaveCell: function (rowid, name, val, iRow, iCol) {
            console.log("entroooaww");
            var id = jQuery("#list_anticipo").jqGrid('getGridParam', 'selrow');
            jQuery('#list_anticipo').jqGrid('restoreRow', id);
            var ret = jQuery("#list_anticipo").jqGrid('getRowData', id);
        },
        delOptions: {
            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {
                var id = jQuery("#list_anticipo").jqGrid('getGridParam', 'selrow');
                jQuery('#list_anticipo').jqGrid('restoreRow', id);
                var ret = jQuery("#list_anticipo").jqGrid('getRowData', id);
                var su = jQuery("#list_anticipo").jqGrid('delRowData', rowid);


                var su;
                var count = 0;
                var subtotal = 0;
                var sub1 = 0;
                var fil = jQuery("#list_anticipo").jqGrid("getRowData");
                for (var t = 0; t < fil.length; t++) {
                    var dd = fil[t];
                    subtotal = (subtotal + (parseFloat(dd['monto'])));
                }
                $("#valor_total").val(subtotal);
                if (su === true) {
                    rp_ge.processing = true;
                    $(".ui-icon-closethick").trigger('click');
                }
                return true;
            },
            processing: true
        }

    });


}

