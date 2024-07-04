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
    width: 530,
    height: 350,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind"
}

function abrirDialogo() {
    $("#nominas").dialog("open");
}

function abrirCuenta() {
    $("#cuentas").dialog("open");
}
function limpiar_input() {
    $("#fecha_registro").val("");
    $("#descripcion").val("");
    $("#valor").val("");
}
function limpiar_inputh() {
    $("#fecha_registroh").val("");

    $("#valorh").val("");
}
function limpiar_inputm() {
    $("#fecha_registrom").val("");
    $("#descripcionm").val("");
    $("#valorm").val("");
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
function enterm(e) {
    if (e.which == 13 || e.keyCode === 13) {
        entrarm();
        return false;
    }
    return true;
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

function entrarm() {
    if ($("#fecha_registrom").val() == "") {
        $("#fecha_registrom").focus();
        alertify.error("Ingrese un producto");
    } else {
        if ($("#descripcionm").val() == "") {
            $("#descripcionm").focus();
        } else {

            $("#valorm").focus();
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
function entrar2h() {
    if ($("#id_empleadoh").val() == "") {
        $("#id_empleadoh").focus();
        alertify.error("Ingrese Fecha registro ");
    } else {
        if ($("#fecha_registroh").val() == "") {
            $("#fecha_registroh").focus();
            alertify.error("Sin Calcular horas Extras");
        } else {
            if ($("#id_empleadoh").val() == "") {
                $("#id_empleadoh").focus();
                alertify.error("Ingrese4 ");
            } else {
                if ($("#periodo").val() == "0") {
                    $("#periodo").focus();
                    alertify.error("Ingrese Período ");
                } else {
                    if ($("#hora_extrah").val() == "") {
                        $("#hora_extrah").focus();
                        alertify.error("Ingrese Hora Extra ");
                    } else {
                        if ($("#valorh").val() == "") {
                            $("#valorh").focus();
                            alertify.error("Ingrese el Total ");
                        } else {
                            if ($("#valor_horat").val() == "") {
                                $("#valor_horat").focus();
                                alertify.error("Ingrese Calcule Hora Trabajo ");
                            } else {



                                var filas = jQuery("#list_anticipoh").jqGrid("getRowData");
                                var datarow = {

                                    id_empleadoh: $("#id_empleadoh").val(),
                                    fecha_registroh: $("#fecha_registroh").val(),
                                    sueldo_persividoh: $("#sueldoh").val(),
                                    periodoh: $("#periodo").val(),
                                    hora_extrah: $("#hora_extra").val(),
                                    porsentajeh: valor_chec,
                                    montoh: $("#valorh").val()


                                };
                                su = jQuery("#list_anticipoh").jqGrid('addRowData', $("#id_empleadoh").val(), datarow);
                                limpiar_inputh();
                                $("#fecha_registroh").focus();
                                var su;
                                var count = 0;
                                var subtotal = 0;
                                var sub1 = 0;
                                var fil = jQuery("#list_anticipoh").jqGrid("getRowData");
                                for (var t = 0; t < fil.length; t++) {
                                    var dd = fil[t];
                                    subtotal = (subtotal + (parseFloat(dd['montoh'])));
                                }
                                $("#valor_totalh").val(subtotal);
                            }
                        }
                    }
                }
            }
        }




    }
}
function enter2s(e) {
    if (e.which == 13 || e.keyCode === 13) {
        entrar2s();
        return false;
    }
    return true;
}
function entrar2s() {
    if ($("#id_empleados").val() == "") {
        $("#id_empleados").focus();
        alertify.error("Ingrese Fecha registro ");
    } else {
        if ($("#fecha_registros").val() == "") {
            $("#fecha_registros").focus();
            alertify.error("Sin Calcular horas Extras");
        } else {
            if ($("#hora_extras").val() == "") {
                $("#hora_extras").focus();
                alertify.error("Ingrese Hora Extra ");
            } else {
                if ($("#tercer_sueldo").val() == "") {
                    $("#tercer_sueldo").focus();
                    alertify.error("Ingrese el Total ");
                } else {
                    if ($("#cuarto_sueldo").val() == "") {
                        $("#cuarto_sueldo").focus();
                        alertify.error("Ingrese Calcule Hora Trabajo ");
                    } else {



                        var filas = jQuery("#list_anticipos").jqGrid("getRowData");
                        var datarow = {

                            id_empleados: $("#id_empleados").val(),
                            fecha_registros: $("#fecha_registros").val(),
                            fecha_inicio: $("#fecha_inicio").val(),
                            fecha_hasta: $("#fecha_hasta").val(),
                            sualdo: $("#sueldos").val(),
                            hora_extra: $("#hora_extras").val(),
                            tercer_sueldo: $("#tercer_sueldo").val(),
                            cuarto_sueldo: $("#cuarto_sueldo").val(),
                            tipo_decimo: valor_chec_decimos


                        };
                        su = jQuery("#list_anticipos").jqGrid('addRowData', $("#id_empleados").val(), datarow);
                        limpiar_inputh();
                        $("#fecha_registros").focus();
                        var su;
                        var count = 0;
                        var subtotal = 0;
                        var sub1 = 0;
//                                var fil = jQuery("#list_anticipoh").jqGrid("getRowData");
//                                for (var t = 0; t < fil.length; t++) {
//                                    var dd = fil[t];
//                                    subtotal = (subtotal + (parseFloat(dd['montoh'])));
//                                }
//                                $("#valor_totalh").val(subtotal);
                    }
                }
            }


        }




    }
}
function enter2m(e) {
    if (e.which == 13 || e.keyCode === 13) {
        entrar2m();
        return false;
    }
    return true;
}
function entrar2m() {
    if ($("#fecha_registrom").val() == "") {
        $("#fecha_registrom").focus();
        alertify.error("Ingrese1 ");
    } else {
        if ($("#descripcionm").val() == "") {
            $("#descripcionm").focus();
            alertify.error("Ingrese2 ");
        } else {
            if ($("#valorm").val() == "") {
                $("#valorm").focus();
                alertify.error("Ingrese3 ");
            } else {
                if ($("#id_empleadom").val() == "") {
                    $("#id_empleadom").focus();
                    alertify.error("Ingrese4 ");
                } else {

                    var filas = jQuery("#list_anticipom").jqGrid("getRowData");
                    var datarow = {

                        id_empleadom: $("#id_empleadom").val(),
                        fecha_registrom: $("#fecha_registrom").val(),
                        descripcionm: $("#descripcionm").val(),
                        montom: $("#valorm").val()


                    };
                    su = jQuery("#list_anticipom").jqGrid('addRowData', $("#id_empleadom").val(), datarow);
                    limpiar_inputm();
                    $("#fecha_registrom").focus();
                    var su;
                    var count = 0;
                    var subtotal = 0;
                    var sub1 = 0;
                    var fil = jQuery("#list_anticipom").jqGrid("getRowData");
                    for (var t = 0; t < fil.length; t++) {
                        var dd = fil[t];
                        subtotal = (subtotal + (parseFloat(dd['montom'])));
                    }
                    $("#valor_totalm").val(subtotal);
                }
            }
        }



    }
}

function enter2(e) {
    if (e.which == 13 || e.keyCode === 13) {
        entrar2();
        return false;
    }
    return true;
}
function entrar2() {
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

                    var filas = jQuery("#list_anticipo").jqGrid("getRowData");
                    var datarow = {

                        id_empleado: $("#id_empleadoa").val(),
                        fecha_registro: $("#fecha_registro").val(),
                        descripcion: $("#descripcion").val(),
                        monto: $("#valor").val()


                    };
                    su = jQuery("#list_anticipo").jqGrid('addRowData', $("#id_empleadoa").val(), datarow);
                    limpiar_input();
                    $("#fecha_registro").focus();
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
function guardar_nomina() {
    var iden = $("#ruc_ci").val();
    if ($("#ruc_ci").val() === "") {
        $("#ruc_ci").focus();
        alertify.error("Ingrese Cédula");
    } else {
        console.log($("#fecha_nacimiento").val());
        if ($("#fecha_nacimiento").val() === "") {
            $("#fecha_nacimiento").focus();
            alertify.error("Ingrese Fecha de Nacimiento");
        } else {
            if ($("#direccion_nomina").val() === "") {
                $("#direccion_nomina").focus();
                alertify.error("Ingrese Dirección");
            } else {
                if ($("#pais_nomina").val() === "") {
                    $("#pais_nomina").focus();
                    alertify.error("Ingrese un país");
                } else {
                    if ($("#ciudad_nomina").val() === "") {
                        $("#ciudad_nomina").focus();
                        alertify.error("Ingrese una ciudad");
                    } else {
                        if ($("#nombres_nomina").val() === "") {
                            $("#nombres_nomina").focus();
                            alertify.error("Ingrese  Nombres");
                        } else {
                            if ($("#fecha_ingreso").val() === "") {
                                $("#fecha_ingreso").focus();
                                alertify.error("Ingrese  Fecha Ingreso");
                            } else {
                                if ($("#tipo_cargo").val() === "0") {
                                    $("#tipo_cargo").focus();
                                    alertify.error("Ingrese Cargo ");
                                } else {
                                    if ($("#referencia_nomina").val() === "") {
                                        $("#referencia_nomina").focus();
                                        alertify.error("Ingrese Referencia Personal");
                                    } else {
                                        if ($("#tele_referencia_nomina").val() === "") {
                                            $("#tele_referencia_nomina").focus();
                                            alertify.error("Ingrese Telefono Referencia Personal");
                                        } else {
                                            if ($("#etnia").val() === "0") {
                                                $("#etnia").focus();
                                                alertify.error("Ingrese Etnia ");
                                            } else {

                                                if ($("#genero").val() === "0") {
                                                    $("#genero").focus();
                                                    alertify.error("Ingrese Género ");
                                                } else {
                                                    if ($("#afiliado").val() === "0") {
                                                        $("#afiliado").focus();
                                                        alertify.error("Ingrese Estado Afiliación");
                                                    } else {
                                                        if ($("#decimo_si_no").val() === "0") {
                                                            $("#decimo_si_no").focus();
                                                            alertify.error("Ingrese una opcion");
                                                        } else {
                                                            if ($("#decimo_si_no").val() === "SI" && $("#decimo").val() === "0") {
                                                                $("#decimo").focus();
                                                                alertify.error("Ingrese alguna opcion");
                                                            } else {
                                                                if ($("#fondos_reserva").val() === "0") {
                                                                    $("#fondos_reserva").focus();
                                                                    alertify.error("Ingrese alguna opcion");
                                                                } else {
                                                                    if ($("#fondos_reserva").val() === "SI" && $("#fondos_acu_mensual").val() === "0") {
                                                                        $("#fondos_acu_mensual").focus();
                                                                        alertify.error("Ingrese alguna opcion");
                                                                    } else {
                                                                        $("#btnGuardar").attr("disabled", true);
                                                                        $.ajax({
                                                                            type: "POST",
                                                                            url: "guardar_nomina.php",
                                                                            data: "ruc_ci=" + $("#ruc_ci").val() +
                                                                                    "&nombres_nomina=" + $("#nombres_nomina").val() +
                                                                                    "&direccion_nomina=" + $("#direccion_nomina").val() +
                                                                                    "&nro_telefono=" + $("#nro_telefono").val() +
                                                                                    "&nro_celular=" + $("#nro_celular").val() +
                                                                                    "&pais_nomina=" + $("#pais_nomina").val() +
                                                                                    "&ciudad_nomina=" + $("#ciudad_nomina").val() +
                                                                                    "&email=" + $("#email").val() +
                                                                                    "&fecha_actual=" + $("#fecha_actual").val() +
                                                                                    "&fecha_nacimiento=" + $("#fecha_nacimiento").val() +
                                                                                    "&notas_nomina=" + $("#notas_nomina").val() +
                                                                                    "&id_plan_cuentas=" + $("#id_plan_cuentas").val() +
                                                                                    "&tipo_cargo=" + $("#tipo_cargo").val() +
                                                                                    "&referencia_nomina=" + $("#referencia_nomina").val() +
                                                                                    "&etnia=" + $("#etnia").val() +
                                                                                    "&genero=" + $("#genero").val() +
                                                                                    "&afiliado=" + $("#afiliado").val() +
                                                                                    "&fecha_ingreso=" + $("#fecha_ingreso").val() +
                                                                                    "&fecha_salida=" + $("#fecha_salida").val() +
                                                                                    "&tele_referencia_nomina=" + $("#tele_referencia_nomina").val() +
                                                                                    "&decimo=" + $("#decimo").val() +
                                                                                    "&fondos_reserva=" + $("#fondos_reserva").val() +
                                                                                    "&fondos_acu_mensual=" + $("#fondos_acu_mensual").val() +
                                                                                    "&decimo_si_no=" + $("#decimo_si_no").val()
                                                                            ,
                                                                            success: function (data) {
                                                                                var val = data;
                                                                                if (val == 1) {
                                                                                    alertify.success('Datos Agregados Correctamente');
                                                                                    setTimeout(function () {
                                                                                        location.reload();
                                                                                    }, 1000);
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
///////////////////////////////////////////////////////////////////////////////
function guardar_anticipom() {
    var tam = jQuery("#list_anticipom").jqGrid("getRowData");
    if (tam.length == 0) {
        $("#list_anticipom").focus();
        alertify.error("Error... Ingrese productos en el inventario");
    } else {
        $("#btnGuardarantm").attr("disabled", true);
        var v1 = new Array();
        var v2 = new Array();
        var v3 = new Array();
        var v4 = new Array();
        var string_v1 = "";
        var string_v2 = "";
        var string_v3 = "";
        var string_v4 = "";
        var fil = jQuery("#list_anticipom").jqGrid("getRowData");
        for (var i = 0; i < fil.length; i++) {
            var datos = fil[i];
            v1[i] = datos['id_empleadom'];
            v2[i] = datos['fecha_registrom'];
            v3[i] = datos['descripcionm'];
            v4[i] = datos['montom'];
        }
        for (i = 0; i < fil.length; i++) {
            string_v1 = string_v1 + "|" + v1[i];
            string_v2 = string_v2 + "|" + v2[i];
            string_v3 = string_v3 + "|" + v3[i];
            string_v4 = string_v4 + "|" + v4[i];
        }

        $.ajax({
            type: "POST",
            url: "guardar_anticipom.php",
            data: "slct_anio_cfm=" + $("#slct_anio_cfm").val() + "&select_mesm=" + $("#select_mesm").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&valor_totalm=" + $("#valor_totalm").val() + "&fecha_registrom=" + $("#fecha_registrom").val() + "&fecha_actual=" + $("#fecha_actual").val(),
            success: function (data) {
                var val = data;
                if (val == 1) {
                    alertify.alert("Guardado correctamente", function () {



                    });
                }
            }
        });
    }
}
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
function extraer_totalm() {
    var select_mesm = $("#select_mesm").val();
//         console.log("dataas"+tipo_tarifa);
    $.ajax({
        type: "POST",
        url: "xmlBuscarAnticipo_consult_1m.php?select_mesm=" + select_mesm + "&id_empleadom=" + $("#id_empleadom").val() + "&anio=" + $("#slct_anio_cf").val(),
        data: "",
        success: function (data) {
            var val = data;
            console.log("dataas" + val);
            var valores;
            valores = val.split("*");
            if (val != "") {
                if (valores[8] == "Activo" || valores[8] == "") {
                    console.log("Aki1");
                    $("#valor_totalm").val(valores[0]);
                    $("#id_anticipom").val(valores[1]);
                    $("#btnGuardarantm").attr("disabled", false);
                    $("#btnModificarantm").attr("disabled", false);
                } else {

                    $("#valor_totalm").val("");
                    $("#id_anticipom").val("");

                    $("#btnGuardarantm").attr("disabled", true);
                    $("#btnModificarantm").attr("disabled", true);
                }
            } else {
                $("#btnGuardarantm").attr("disabled", false);
                $("#btnModificarantm").attr("disabled", false);


            }
        }

    });
}
//function extraer_totald() {
//    var select_mesm = $("#select_mess").val();
////         console.log("dataas"+tipo_tarifa);
//    $.ajax({
//        type: "POST",
//        url: "xmlBuscarAnticipo_consult_1d.php?select_mesd=" + select_mesd + "&id_empleadod=" + $("#id_empleadod").val() + "&anio=" + $("#slct_anio_cfs").val(),
//        data: "",
//        success: function (data) {
//            var val = data;
//            console.log("dataas" + val);
//            if (val != "") {
//                var valores;
//                valores = val.split("*");
//                $("#valor_totals").val(valores[0]);
//                $("#id_anticipoh").val(valores[1]);
//                $("#btnGuardaranth").attr("disabled", true);
//            } else {
////                $("#id_empleado").val("")
//                $("#btnGuardaranth").attr("disabled", false);
//            }
//        }
//
//    });
//}
function activar_botonm() {
    var select_mesm = $("#select_mesm").val();
//         console.log("dataas"+tipo_tarifa);
    $.ajax({
        type: "POST",
        url: 'xmlBuscarAnticipo_consultm.php?select_mesm=' + select_mesm + "&id_empleadom=" + $("#id_empleadom").val() + "&anio=" + $("#slct_anio_cf").val(),
        data: "",
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#id_empleadom").val(val)
                $("#btnGuardarantm").attr("disabled", true);
            } else {
//                $("#id_empleado").val("")
                $("#btnGuardarantm").attr("disabled", false);
            }
        }
    });
}
function activar_botond() {
    var select_mess = $("#select_mess").val();
//         console.log("dataas"+tipo_tarifa);
    $.ajax({
        type: "POST",
        url: 'xmlBuscarAnticipo_consults.php?select_mess=' + select_mess + "&id_empleados=" + $("#id_empleados").val() + "&anio=" + $("#slct_anio_cfs").val(),
        data: "",
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#id_empleados").val(val)
                $("#btnGuardarantd").attr("disabled", true);
            } else {
//                $("#id_empleado").val("")
                $("#btnGuardarantd").attr("disabled", false);
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
function cargar_rubro_tarifam() {
    if ($("#id_empleadom").val() != "") {
        var select_mesm = $("#select_mesm").val();
        $("#list_anticipom").jqGrid('setGridParam', {
            url: 'xmlBuscarAnticipom.php?id_clasem=' + select_mesm + "&id_empleadom=" + $("#id_empleadom").val() + "&anio=" + $("#slct_anio_cfm").val(),
            datatype: 'xml',
        }).trigger('reloadGrid');
        activar_botonm();
        extraer_totalm();
    } else {
        alertify.error("Error.. Buscar Nomina");
    }

}
function cargar_rubro_tarifah() {
    if ($("#id_empleadoh").val() != "") {
        var select_mesh = $("#select_mesh").val();
        $("#list_anticipoh").jqGrid('setGridParam', {
            url: 'xmlBuscarAnticipoh.php?id_claseh=' + select_mesh + "&id_empleadoh=" + $("#id_empleadoh").val() + "&anio=" + $("#slct_anio_cfh").val(),
            datatype: 'xml',
        }).trigger('reloadGrid');
        activar_botonh();
        extraer_totalh();
    } else {
        alertify.error("Error.. Buscar Nomina");
    }

}
function cargar_rubro_tarifad() {
    if ($("#id_empleados").val() != "") {
        var select_mess = $("#select_mess").val();
        $("#list_anticipos").jqGrid('setGridParam', {
            url: 'xmlBuscarAnticipod.php?id_clases=' + select_mess + "&id_empleados=" + $("#id_empleados").val() + "&anio=" + $("#slct_anio_cfs").val(),
            datatype: 'xml',
        }).trigger('reloadGrid');
        activar_botond();
//        extraer_totald();
    } else {
        alertify.error("Error.. Buscar Nomina");
    }

}
///////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
function guardar_anticipo() {
    var tam = jQuery("#list_anticipo").jqGrid("getRowData");
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

        $.ajax({
            type: "POST",
            url: "guardar_anticipo.php",
            data: "slct_anio_cf=" + $("#slct_anio_cf").val() + "&select_mes=" + $("#select_mes").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&valor_total=" + $("#valor_total").val() + "&fecha_actual=" + $("#fecha_actual").val(),
            success: function (data) {
                var val = data;
                if (val == 1) {
                    alertify.alert("Guardado correctamente", function () {

                        setTimeout(function () {
                            location.reload();
                        }, 1000);

                    });
                }
            }
        });
    }
}
////////////////////////////////////////////////////////////////////////////////////////
function guardar_anticipos() {
    var tam = jQuery("#list_anticipos").jqGrid("getRowData");
    if (tam.length == 0) {
        $("#list_anticipos").focus();
        alertify.error("Error... ");
    } else {
        $("#btnGuardaranth").attr("disabled", true);
        var v1 = new Array();
        var v2 = new Array();
        var v3 = new Array();
        var v4 = new Array();
        var v5 = new Array();
        var v6 = new Array();
        var v7 = new Array();
        var v8 = new Array();
        var v9 = new Array();
        var string_v1 = "";
        var string_v2 = "";
        var string_v3 = "";
        var string_v4 = "";
        var string_v5 = "";
        var string_v6 = "";
        var string_v7 = "";
        var string_v8 = "";
        var string_v9 = "";
        var fil = jQuery("#list_anticipos").jqGrid("getRowData");
        for (var i = 0; i < fil.length; i++) {
            var datos = fil[i];
            v1[i] = datos['id_empleados'];
            v2[i] = datos['fecha_registros'];
            v3[i] = datos['fecha_inicio'];
            v4[i] = datos['fecha_hasta'];
            v5[i] = datos['sualdo'];
            v6[i] = datos['hora_extra'];
            v7[i] = datos['tercer_sueldo'];
            v8[i] = datos['cuarto_sueldo'];
            v9[i] = datos['tipo_decimo'];
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
        }
        $.ajax({
            type: "POST",
            url: "guardar_anticipos.php",
            data: "slct_anio_cfs=" + $("#slct_anio_cfs").val() + "&select_mess=" + $("#select_mess").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&campo6=" + string_v6 + "&campo7=" + string_v7 + "&campo8=" + string_v8 + "&campo9=" + string_v9 + "&fecha_actual=" + $("#fecha_actual").val(),
            success: function (data) {
                var val = data;
                if (val == 1) {
                    alertify.alert("Guardado correctamente", function () {
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    });
                }
            }
        });
    }
}
////////////////////////////////////////////////////////////////////////////////////////
function guardar_anticipoh() {
    var tam = jQuery("#list_anticipoh").jqGrid("getRowData");
    if (tam.length == 0) {
        $("#list_anticipoh").focus();
        alertify.error("Error... Ingrese productos en el inventario");
    } else {
        $("#btnGuardaranth").attr("disabled", true);
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
        var fil = jQuery("#list_anticipoh").jqGrid("getRowData");
        for (var i = 0; i < fil.length; i++) {
            var datos = fil[i];
            v1[i] = datos['id_empleadoh'];
            v2[i] = datos['fecha_registroh'];
            v3[i] = datos['sueldo_persividoh'];
            v4[i] = datos['periodoh'];
            v5[i] = datos['hora_extrah'];
            v6[i] = datos['porsentajeh'];
            v7[i] = datos['montoh'];
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
        $.ajax({
            type: "POST",
            url: "guardar_anticipoh.php",
            data: "slct_anio_cfh=" + $("#slct_anio_cfh").val() + "&select_mesh=" + $("#select_mesh").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&campo6=" + string_v6 + "&campo7=" + string_v7 + "&valor_totalh=" + $("#valor_totalh").val() + "&fecha_actual=" + $("#fecha_actual").val(),
            success: function (data) {
                var val = data;
                if (val == 1) {
                    alertify.alert("Guardado correctamente", function () {
                        setTimeout(function () {
                            location.reload();
                        }, 1000);

                    });
                }
            }
        });
    }
}
function extraer_total() {
    var select_mes = $("#select_mes").val();
//         console.log("dataas"+tipo_tarifa);
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
        alertify.error("Ingrese Nombre Cargo");
    } else {
        if ($("#sueldo_base").val() === "") {
            $("#sueldo_base").focus();
            alertify.error("Ingrese Sueldo");
        } else {
            if ($("#codigo_sectorial").val() === "") {
                $("#codigo_sectorial").focus();
                alertify.error("Ingrese Codigo Sectorial");
            } else {
                if ($("#salario_basico_unificado").val() === "") {
                    $("#salario_basico_unificado").focus();
                    alertify.error("Ingrese S.B.U");
                } else {
                    $("#btnGuardarcargo").attr("disabled", true);
                    $.ajax({
                        type: "POST",
                        url: "../cargo/guardar_cargo.php",
                        data: "nombre_cargo=" + $("#nombre_cargo").val() + "&sueldo_base=" + $("#sueldo_base").val() + "&codigo_sectorial=" + $("#codigo_sectorial").val() + "&salario_basico_unificado=" + $("#salario_basico_unificado").val(),
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

}
function modificar_nomina() {
    var iden = $("#ruc_ci").val();
    if ($("#ruc_ci").val() === "") {
        $("#ruc_ci").focus();
        alertify.error("Ingrese Nombres completos");
    } else {
        if ($("#fecha_nacimiento").val() === "") {
            $("#fecha_nacimiento").focus();
            alertify.error("Ingrese");
        } else {
            if ($("#direccion_nomina").val() === "") {
                $("#direccion_nomina").focus();
                alertify.error("Ingrese una dirección");
            } else {
                if ($("#pais_nomina").val() === "") {
                    $("#pais_nomina").focus();
                    alertify.error("Ingrese un país");
                } else {
                    if ($("#ciudad_nomina").val() === "") {
                        $("#ciudad_nomina").focus();
                        alertify.error("Ingrese una ciudad");
                    } else {
                        if ($("#nombres_nomina").val() === "") {
                            $("#nombres_nomina").focus();
                            alertify.error("Ingrese ");
                        } else {
                            if ($("#tipo_cargo").val() === "0") {
                                $("#tipo_cargo").focus();
                                alertify.error("Ingrese ");
                            } else {
                                if ($("#referencia_nomina").val() === "") {
                                    $("#referencia_nomina").focus();
                                    alertify.error("Ingrese ");
                                } else {
                                    if ($("#etnia").val() === "0") {
                                        $("#etnia").focus();
                                        alertify.error("Ingrese ");
                                    } else {

                                        if ($("#genero").val() === "0") {
                                            $("#genero").focus();
                                            alertify.error("Ingrese ");
                                        } else {
                                            if ($("#afiliado").val() === "0") {
                                                $("#afiliado").focus();
                                                alertify.error("Ingrese Estado Afiliación");
                                            } else {
                                                if ($("#decimo_si_no").val() === "0") {
                                                    $("#decimo_si_no").focus();
                                                    alertify.error("Ingrese una opcion");
                                                } else {
                                                    if ($("#decimo_si_no").val() === "SI" && $("#decimo").val() === "0") {
                                                        $("#decimo").focus();
                                                        alertify.error("Ingrese alguna opcion");
                                                    } else {
                                                        if ($("#fondos_reserva").val() === "0") {
                                                            $("#fondos_reserva").focus();
                                                            alertify.error("Ingrese alguna opcion");
                                                        } else {
                                                            if ($("#fondos_reserva").val() === "SI" && $("#fondos_acu_mensual").val() === "0") {
                                                                $("#fondos_acu_mensual").focus();
                                                                alertify.error("Ingrese alguna opcion");
                                                            } else {
                                                                $.ajax({
                                                                    type: "POST",
                                                                    url: "modificar_nomina.php",
                                                                    data: "ruc_ci=" + $("#ruc_ci").val() +
                                                                            "&nombres_nomina=" + $("#nombres_nomina").val() +
                                                                            "&direccion_nomina=" + $("#direccion_nomina").val() +
                                                                            "&nro_telefono=" + $("#nro_telefono").val() +
                                                                            "&nro_celular=" + $("#nro_celular").val() +
                                                                            "&pais_nomina=" + $("#pais_nomina").val() +
                                                                            "&ciudad_nomina=" + $("#ciudad_nomina").val() +
                                                                            "&email=" + $("#email").val() +
                                                                            "&fecha_actual=" + $("#fecha_actual").val() +
                                                                            "&fecha_nacimiento=" + $("#fecha_nacimiento").val() +
                                                                            "&notas_nomina=" + $("#notas_nomina").val() +
                                                                            "&id_plan_cuentas=" + $("#id_plan_cuentas").val() +
                                                                            "&tipo_cargo=" + $("#tipo_cargo").val() +
                                                                            "&referencia_nomina=" + $("#referencia_nomina").val() +
                                                                            "&etnia=" + $("#etnia").val() +
                                                                            "&genero=" + $("#genero").val() +
                                                                            "&afiliado=" + $("#afiliado").val() +
                                                                            "&id_empleado=" + $("#id_empleadon").val() +
                                                                            "&fecha_ingreso=" + $("#fecha_ingreso").val() +
                                                                            "&fecha_salida=" + $("#fecha_salida").val() +
                                                                            "&tele_referencia_nomina=" + $("#tele_referencia_nomina").val() +
                                                                            "&decimo=" + $("#decimo").val() +
                                                                            "&fondos_reserva=" + $("#fondos_reserva").val() +
                                                                            "&fondos_acu_mensual=" + $("#fondos_acu_mensual").val() +
                                                                            "&decimo_si_no=" + $("#decimo_si_no").val(),
                                                                    success: function (data) {
                                                                        var val = data;
                                                                        if (val == 1) {
                                                                            alertify.success('Datos Agregados Correctamente');
                                                                            setTimeout(function () {
                                                                                location.reload();
                                                                            }, 1000);
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
                            }
                        }
                    }
                }
            }

        }
    }
}
function modificar_parametros_iess() {

    $.ajax({
        type: "POST",
        url: "../parametros_iess/modificar_parametros_iess.php",
        data: "descripcion_iess=" + $("#descripcion_iess").val() + "&valor_aporte=" + $("#valor_aporte").val() + "&id_parametro_iess=" + $("#id_parametro_iess").val(),
        success: function (data) {
            var val = data;
            if (val == 1) {
                alertify.alert("Los datos se han Modificado Correctamente", function () {
                    location.reload();
                })
            } else {
                alertify.alert("Error en el proceso, revise los datos por favor");
            }
        }
    });
}

function modificar_cargo() {

    $.ajax({
        type: "POST",
        url: "../cargo/modificar_cargo.php",
        data: "nombre_cargo=" + $("#nombre_cargo").val() + "&sueldo_base=" + $("#sueldo_base").val() + "&id_cargo=" + $("#id_cargo").val() + "&codigo_sectorial=" + $("#codigo_sectorial").val() + "&salario_basico_unificado=" + $("#salario_basico_unificado").val(),
        success: function (data) {
            var val = data;
            if (val == 1) {
                alertify.alert("Los datos se han Modificado Correctamente", function () {
                    location.reload();
                })
            } else {
                alertify.alert("Error en el proceso, revise los datos por favor");
            }
        }
    });
}
function validar_fecha_registrom() {


    var fecha = $("#fecha_registrom").val();
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
    if (nueva_fecha == $("#select_mesm").val()) {
        alertify.success("fecha correcta");
    } else {
        $("#fecha_registrom").val("")
        alertify.error("LA FECHA INGRESADA NO CORRESPONDE AL MES SELECCIONADO");
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
function validar_fecha_registroh() {


    var fecha = $("#fecha_registroh").val();
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
    if (nueva_fecha == $("#select_mesh").val()) {
        alertify.success("fecha correcta");
    } else {
        $("#fecha_registroh").val("")
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
function eliminar_anticipoh() {
    if ($("#id_anticipoh").val() === "") {
        alertify.error("Seleccione Tipo de Documento");
    } else {
        $("#clave_permisoh").dialog("open");
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

function calculo25() {
    if ($("#sueldoh").val() != 0 && $("#hora_extra").val() != 0) {
        var val3 = parseFloat($("#sueldoh").val() / 30);
        var sueldo_hora = val3 / 8;
        console.log(sueldo_hora + "ven");
        var resulente = sueldo_hora.toFixed(2);
        $("#valor_horat").val(resulente);
        var result_hora_extra = resulente * parseFloat($("#hora_extra").val());
        console.log(result_hora_extra + "ven");
        var total_result_hora_extra = result_hora_extra * 1.25;
        $("#valorh").val(total_result_hora_extra.toFixed(2));
    } else {
        alertify.error("ERROR..INGRESAR HORA EXTRA O SALARIO MENSUAL ")
    }
}
////////////////////////////////////////////////////////////////////////////////////////////
function calculodivic() {
    if ($("#afiliados").val() == "SI") {
        if ($("#sueldos").val() != "" && $("#hora_extras").val() != "") {
            var val3 = parseFloat($("#sueldos").val());
            var result_hora_extra = val3 + parseFloat($("#hora_extras").val());
            var total_result_hora_extra = result_hora_extra / 12;
            total_result_hora_extra = total_result_hora_extra / 12
            $("#cuarto_sueldo").val(total_result_hora_extra.toFixed(2));

        } else {

        }
    } else {
        alertify.error("ERROR..INGRESAR HORA EXTRA O SALARIO MENSUAL ")
    }
}
function calculoacuc() {
    if ($("#afiliados").val() == "SI") {
        if ($("#sueldos").val() != "" && $("#hora_extras").val() != "") {
            var val3 = parseFloat($("#sueldos").val());
            var result_hora_extra = val3 + parseFloat($("#hora_extras").val());
            var total_result_hora_extra = result_hora_extra;
            $("#cuarto_sueldo").val(total_result_hora_extra.toFixed(2));

        } else {

        }
    } else {
        alertify.error("ERROR..INGRESAR HORA EXTRA O SALARIO MENSUAL ")
    }
}
////////////////////////////////////////////////////////////////////////////////////////////
function calculodivi() {
    if ($("#afiliados").val() == "SI") {
        if ($("#sueldos").val() != "" && $("#hora_extras").val() != "") {
            var val3 = parseFloat($("#sueldos").val());
            var result_hora_extra = val3 + parseFloat($("#hora_extras").val());
            var total_result_hora_extra = result_hora_extra / 12;
            total_result_hora_extra = total_result_hora_extra / 12
            $("#tercer_sueldo").val(total_result_hora_extra.toFixed(2));
            calculodivic();
            calculoacuc();

        } else {

        }
    } else {
        alertify.error("ERROR..INGRESAR HORA EXTRA O SALARIO MENSUAL ")
    }
}
function calculoacu() {
    if ($("#afiliados").val() == "SI") {
        if ($("#sueldos").val() != "" && $("#hora_extras").val() != "") {
            var val3 = parseFloat($("#sueldos").val());
            var result_hora_extra = val3 + parseFloat($("#hora_extras").val());
            var total_result_hora_extra = result_hora_extra / 12;
            $("#tercer_sueldo").val(total_result_hora_extra.toFixed(2));
            calculodivic();
            calculoacuc();
        } else {

        }
    } else {
        alertify.error("ERROR..INGRESAR HORA EXTRA O SALARIO MENSUAL ")
    }
}
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
function validar_acceso() {
    if ($("#clave").val() == "") {
        $("#clave").focus();
        alertify.error("Ingrese la clave");
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
                    alertify.error("Error... La clave es incorrecta ingrese nuevamente");
                } else {
                    if (val == 1) {
                        $("#seguro").dialog("open");
                    }
                }
            }
        });
    }
}
function validar_accesom() {
    if ($("#clavem").val() == "") {
        $("#clavem").focus();
        alertify.error("Ingrese la clave");
    } else {

        $.ajax({
            url: '../../procesos/validar_acceso.php',
            type: 'POST',
            data: "clave=" + $("#clavem").val(),
            success: function (data) {
                var val = data;
                if (val == 0) {
                    $("#clavem").val("");
                    $("#clavem").focus();
                    alertify.error("Error... La clave es incorrecta ingrese nuevamente");
                } else {
                    if (val == 1) {
                        $("#segurom").dialog("open");
                    }
                }
            }
        });
    }
}
function validar_accesoh() {
    if ($("#claveh").val() == "") {
        $("#claveh").focus();
        alertify.error("Ingrese la clave");
    } else {

        $.ajax({
            url: '../../procesos/validar_acceso.php',
            type: 'POST',
            data: "clave=" + $("#claveh").val(),
            success: function (data) {
                var val = data;
                if (val == 0) {
                    $("#claveh").val("");
                    $("#claveh").focus();
                    alertify.error("Error... La clave es incorrecta ingrese nuevamente");
                } else {
                    if (val == 1) {
                        $("#seguroh").dialog("open");
                    }
                }
            }
        });
    }
}
function validar_acceson() {
    if ($("#claven").val() == "") {
        $("#claven").focus();
        alertify.error("Ingrese la clave");
    } else {

        $.ajax({
            url: '../../procesos/validar_acceso.php',
            type: 'POST',
            data: "clave=" + $("#claven").val(),
            success: function (data) {
                var val = data;
                if (val == 0) {
                    $("#claven").val("");
                    $("#claven").focus();
                    alertify.error("Error... La clave es incorrecta ingrese nuevamente");
                } else {
                    if (val == 1) {
                        $("#seguron").dialog("open");
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
                }, 1000);
            } else {
                alertify.success('Eliminado Correctamente');
                setTimeout(function () {
                    location.reload();
                }, 1000);
            }
        }
    });
}

function aceptar() {
    $.ajax({
        type: "POST",
        url: "eliminar_nomina.php",
        data: "id_nomina=" + $("#id_empleadon").val(),
        success: function (data) {
            var val = data;

            alertify.success('Eliminado Correctamente');
            setTimeout(function () {
                location.reload();
            }, 1000);
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
            }, 1000);

        }
    });
}
function aceptarm() {
    $.ajax({
        type: "POST",
        url: "eliminar_multa.php",
        data: "id_anticipo=" + $("#id_anticipom").val() + "&id_empleado=" + $("#id_empleadom").val() + "&anio=" + $("#slct_anio_cfm").val() + "&select_mes=" + $("#select_mesm").val(),
        success: function (data) {
            var val = data;
            if (val == '0') {
                alertify.success('Eliminado Correctamente');
                setTimeout(function () {
                    location.reload();
                }, 1000);
            }
        }
    });
}
function aceptard() {
    $.ajax({
        type: "POST",
        url: "eliminar_decimo.php",
        data: "id_anticipo=" + $("#id_anticipos").val() + "&id_empleado=" + $("#id_empleados").val() + "&anio=" + $("#slct_anio_cfs").val() + "&select_mes=" + $("#select_mess").val(),
        success: function (data) {
            var val = data;
            if (val == '0') {
                alertify.success('Eliminado Correctamente');
                setTimeout(function () {
                    location.reload();
                }, 1000);
            }
        }
    });
}
function aceptarh() {
    $.ajax({
        type: "POST",
        url: "eliminar_horas_extras.php",
        data: "id_anticipo=" + $("#id_anticipoh").val() + "&id_empleado=" + $("#id_empleadoh").val() + "&anio=" + $("#slct_anio_cfh").val() + "&select_mes=" + $("#select_mesh").val(),
        success: function (data) {
            var val = data;
            if (val == '0') {
                alertify.success('Eliminado Correctamente');
                setTimeout(function () {
                    location.reload();
                }, 1000);
            }
        }
    });
}
function cancelaraa() {
    $("#seguroaa").dialog("close");
    $("#clave_permisoaa").dialog("close");
    $("#claveaa").val("");
}
function cancelarcc() {
    $("#segurocc").dialog("close");
    $("#clave_permisocc").dialog("close");
    $("#clavecc").val("");
}
function cancelar() {
    $("#seguro").dialog("close");
    $("#clave_permiso").dialog("close");
    $("#clave").val("");
}
function cancelarm() {
    $("#segurom").dialog("close");
    $("#clave_permisom").dialog("close");
    $("#clavem").val("");
}
function cancelarn() {
    $("#seguron").dialog("close");
    $("#clave_permison").dialog("close");
    $("#claven").val("");
}
function cancelar_accesocc() {
    $("#clave_permisocc").dialog("close");
    $("#clavecc").val("");
}
function cancelar_accesoaa() {
    $("#clave_permisocc").dialog("close");
    $("#clavecc").val("");
}
function cancelar_acceso() {
    $("#clave_permiso").dialog("close");
    $("#clave").val("");
}
function abrirDialogo_aporte_iess() {
    $("#aporte_iess").dialog("open");
}
function abrirDialogo_cargo() {
    $("#cargo").dialog("open");
}
function nuevo_nomina() {
    location.reload();
}
function modificar_anticipo() {


    if ($("#id_empleadoa").val() != '') {
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
        console.log("entro");
        $.ajax({
            type: "POST",
            url: "modificar_anticipo.php",
            data: "slct_anio_cf=" + $("#slct_anio_cf").val() + "&select_mes=" + $("#select_mes").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&valor_total=" + $("#valor_total").val() + "&fecha_actual=" + $("#fecha_actual").val() + "&id_empleado=" + $("#id_empleadoa").val(),
            success: function (data) {
                var val = data;
                if (val != 0) {
                    alertify.alert(" Modificado correctamente", function () {

                        setTimeout(function () {
                            location.reload();
                        }, 1000);

                    });
                }
            }
        });
    } else {
        alertify.error("Error... Selecciones una Tarifa");
    }
}
function modificar_anticipom() {


    if ($("#id_empleadom").val() != '') {
        $("#btnGuardarantm").attr("disabled", true);
        var v1 = new Array();
        var v2 = new Array();
        var v3 = new Array();
        var v4 = new Array();
        var string_v1 = "";
        var string_v2 = "";
        var string_v3 = "";
        var string_v4 = "";
        var fil = jQuery("#list_anticipom").jqGrid("getRowData");
        for (var i = 0; i < fil.length; i++) {
            var datos = fil[i];
            v1[i] = datos['id_empleadom'];
            v2[i] = datos['fecha_registrom'];
            v3[i] = datos['descripcionm'];
            v4[i] = datos['montom'];
        }
        for (i = 0; i < fil.length; i++) {
            string_v1 = string_v1 + "|" + v1[i];
            string_v2 = string_v2 + "|" + v2[i];
            string_v3 = string_v3 + "|" + v3[i];
            string_v4 = string_v4 + "|" + v4[i];
        }
        console.log("entro");
        $.ajax({
            type: "POST",
            url: "modificar_anticipom.php",
            data: "slct_anio_cfm=" + $("#slct_anio_cfm").val() + "&select_mesm=" + $("#select_mesm").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&valor_totalm=" + $("#valor_totalm").val() + "&fecha_actualm=" + $("#fecha_actual").val() + "&id_empleadom=" + $("#id_empleadom").val(),
            success: function (data) {
                var val = data;
                if (val == 1) {
                    alertify.alert(" Modificado correctamente", function () {
                        setTimeout(function () {
                            location.reload();
                        }, 1000);


                    });
                }
            }
        });
    } else {
        alertify.error("Error... Selecciones una Tarifa");
    }
}
function modificar_anticipod() {


    if ($("#id_empleados").val() != '') {
        $("#btnGuardarantd").attr("disabled", true);
        var v1 = new Array();
        var v2 = new Array();
        var v3 = new Array();
        var v4 = new Array();
        var v5 = new Array();
        var v6 = new Array();
        var v7 = new Array();
        var v8 = new Array();
        var v9 = new Array();
        var string_v1 = "";
        var string_v2 = "";
        var string_v3 = "";
        var string_v4 = "";
        var string_v5 = "";
        var string_v6 = "";
        var string_v7 = "";
        var string_v8 = "";
        var string_v9 = "";
        var fil = jQuery("#list_anticipos").jqGrid("getRowData");
        for (var i = 0; i < fil.length; i++) {
            var datos = fil[i];
            v1[i] = datos['id_empleados'];
            v2[i] = datos['fecha_registros'];
            v3[i] = datos['fecha_inicio'];
            v4[i] = datos['fecha_hasta'];
            v5[i] = datos['sualdo'];
            v6[i] = datos['hora_extra'];
            v7[i] = datos['tercer_sueldo'];
            v8[i] = datos['cuarto_sueldo'];
            v9[i] = datos['tipo_decimo'];
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
        }
        $.ajax({
            type: "POST",
            url: "modificar_anticipod.php",
            data: "slct_anio_cfs=" + $("#slct_anio_cfs").val() + "&select_mess=" + $("#select_mess").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&campo6=" + string_v6 + "&campo7=" + string_v7 + "&campo8=" + string_v8 + "&campo9=" + string_v9 + "&fecha_actual=" + $("#fecha_actual").val(),
            success: function (data) {
                var val = data;
                if (val == 1) {
                    alertify.alert("Modificado correctamente", function () {
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    });
                }
            }
        });
    }
}
function modificar_anticipoh() {


    if ($("#id_empleadoh").val() != '') {
        $("#btnGuardaranth").attr("disabled", true);
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
        var fil = jQuery("#list_anticipoh").jqGrid("getRowData");
        for (var i = 0; i < fil.length; i++) {
            var datos = fil[i];



            v1[i] = datos['id_empleadoh'];
            v2[i] = datos['fecha_registroh'];
            v3[i] = datos['sueldo_persividoh'];
            v4[i] = datos['periodoh'];
            v5[i] = datos['hora_extrah'];
            v6[i] = datos['porsentajeh'];
            v7[i] = datos['montoh'];
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
        console.log("entro");
        $.ajax({
            type: "POST",
            url: "modificar_anticipoh.php",
            data: "slct_anio_cfh=" + $("#slct_anio_cfh").val() + "&select_mesh=" + $("#select_mesh").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&campo6=" + string_v6 + "&campo7=" + string_v7 + "&valor_totalh=" + $("#valor_totalh").val() + "&fecha_actualh=" + $("#fecha_actual").val() + "&id_empleadoh=" + $("#id_empleadoh").val(),
            success: function (data) {
                var val = data;
                if (val == 1) {
                    alertify.alert(" Modificado correctamente", function () {
                        setTimeout(function () {
                            location.reload();
                        }, 1000);


                    });
                }
            }
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

function inicializarSelectAnioFuncios() {
    var currentyearf = new Date().getFullYear();
    for (var i = currentyearf; i > currentyearf - 50; i--) {
        var opt = new Option(i, i, false, false);
        $("#slct_anio_cfs")[0].append(opt);
    }
}
function inicializarSelectAnioFuncioh() {
    var currentyearf = new Date().getFullYear();
    for (var i = currentyearf; i > currentyearf - 50; i--) {
        var opt = new Option(i, i, false, false);
        $("#slct_anio_cfh")[0].append(opt);
    }
}
function inicializarSelectAnioFunciom() {
    var currentyearf = new Date().getFullYear();
    for (var i = currentyearf; i > currentyearf - 50; i--) {
        var opt = new Option(i, i, false, false);
        $("#slct_anio_cfm")[0].append(opt);
    }
}
function funcion_cargar_anticipos() {
    console.log("jjj" + $("#select_mess").val());
    var cedula = $("#cedula_empleados").val();
    var anio = $("#slct_anio_cfs").val();
    var mes = $("#select_mess").val();

    if ($("#select_mess").val() != 0 && $("#id_empleados").val() != '0')
    {


        $.getJSON('../../data/rol_pagos/buscar_cliente_hora_extra.php?com=' + cedula + "&anio=" + anio + "&mes=" + mes, function (data) {
            var tama = data.length;
            if (tama !== 0) {
                for (var i = 0; i < tama; i = i + 1) {
                    $("#hora_extras").val(data[i]);


                }
            }
        });



    } else {

        alertify.error("DEBE SELECCIONAR EL MES");

    }
}
function inicio() {

    $("#btnModificarcargo").attr("disabled", true);
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
    $("#select_mess").on("change", funcion_cargar_anticipos);
    //////////////////BUSCADORES////////////////////
    $("#cedula_empleados").autocomplete({
        source: "../../data/rol_pagos/buscar_cliente.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#cedula_empleados").val(ui.item.value);
            $("#id_empleados").val(ui.item.id_cliente);
            $("#nombres_empleados").val(ui.item.nombre_cliente);
            $("#direccion_empleados").val(ui.item.direccion_cliente);

            $("#sueldos").val(ui.item.salario_empleado);
            $("#dias_trabajados").val(ui.item.dias_trabajados);
            $("#afiliados").val(ui.item.esta_afiliado);
            return false;
        },
        select: function (event, ui) {
            $("#cedula_empleados").val(ui.item.value);
            $("#id_empleados").val(ui.item.id_cliente);
            $("#nombres_empleados").val(ui.item.nombre_cliente);
            $("#direccion_empleados").val(ui.item.direccion_cliente);

            $("#sueldos").val(ui.item.salario_empleado);
            $("#dias_trabajados").val(ui.item.dias_trabajados);
            $("#afiliados").val(ui.item.esta_afiliado);
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
    };


    inicializarSelectAnioFuncios()

    inicializarSelectAnioFunciom();
    inicializarSelectAnioFuncioh();
    // buscar clientes identificacion

    //////////////////////////////////HORAS EXTRAS////////////
    $("#cedula_empleadoh").autocomplete({
        source: "../../data/rol_pagos/buscar_cliente.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#cedula_empleadoh").val(ui.item.value);
            $("#id_empleadoh").val(ui.item.id_cliente);
            $("#nombres_empleadoh").val(ui.item.nombre_cliente);
            $("#direccion_empleadoh").val(ui.item.direccion_cliente);
            $("#sueldoh").val(ui.item.salario_empleado);
            return false;
        },
        select: function (event, ui) {
            $("#cedula_empleadoh").val(ui.item.value);
            $("#id_empleadoh").val(ui.item.id_cliente);
            $("#nombres_empleadoh").val(ui.item.nombre_cliente);
            $("#direccion_empleadoh").val(ui.item.direccion_cliente);
            $("#sueldoh").val(ui.item.salario_empleado);
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
    };
    // fin
    // buscar clientes identificacion
    $("#cedula_empleadom").autocomplete({
        source: "buscar_cliente.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#cedula_empleadom").val(ui.item.value);
            $("#id_empleadom").val(ui.item.id_cliente);
            $("#nombres_empleadom").val(ui.item.nombre_cliente);
            $("#direccion_empleadom").val(ui.item.direccion_cliente);
            return false;
        },
        select: function (event, ui) {
            $("#cedula_empleadom").val(ui.item.value);
            $("#id_empleadom").val(ui.item.id_cliente);
            $("#nombres_empleadom").val(ui.item.nombre_cliente);
            $("#direccion_empleadom").val(ui.item.direccion_cliente);
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
    };
    // buscar clientes identificacion



    $("#fecha_actual").datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');
    $("[data-mask]").inputmask();
    alertify.set({delay: 1000});

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
    $("#btnGuardar").click(function (e) {
        e.preventDefault();
    });
    $("#btnModificar").click(function (e) {
        e.preventDefault();
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
    $("#btnModificarcargo").click(function (e) {
        e.preventDefault();
    });
    $("#btnModificarva").click(function (e) {
        e.preventDefault();
    });
    $("#btnEliminarcargo").click(function (e) {
        e.preventDefault();
    });
    $("#btnBuscarcargo").click(function (e) {
        e.preventDefault();
    });
    $("#btnNuevocargo").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardarant").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardarant").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardarantm").click(function (e) {
        e.preventDefault();
    });
    $("#btnBuscarva").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardarants").click(function (e) {
        e.preventDefault();
    });
    $("#btnModificarant").click(function (e) {
        e.preventDefault();
    });
    $("#btnAnularant").click(function (e) {
        e.preventDefault();
    });
    $("#btnAnularanth").click(function (e) {
        e.preventDefault();
    });
    $("#btnAnularantm").click(function (e) {
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
    $("#btnGuardaranth").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardarantd").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardarcargo").on("click", guardar_cargo);
    $("#btnGuardarva").on("click", guardar_aporte_iess);
    $("#btnGuardarant").on("click", guardar_anticipo);
    $("#btnGuardaranth").on("click", guardar_anticipoh);
    $("#btnGuardarantd").on("click", guardar_anticipos);
    $("#btnGuardarantm").on("click", guardar_anticipom);
    $("#btnModificarcargo").on("click", modificar_cargo);
    $("#btnModificarva").on("click", modificar_parametros_iess);
    $("#btnEliminarcargo").on("click", eliminar_documento);
    $("#btnAnularant").on("click", eliminar_anticipo);
    $("#btnAnularanth").on("click", eliminar_anticipoh);
    $("#btnAnularantm").on("click", eliminar_multa);
    $("#btnBuscarcargo").on("click", abrirDialogo_cargo);
    $("#btnBuscarva").on("click", abrirDialogo_aporte_iess);
    $("#btnModificarant").on("click", modificar_anticipo);
    $("#btnModificarantm").on("click", modificar_anticipom);
    $("#btnModificarantd").on("click", modificar_anticipod);
    $("#btnModificaranth").on("click", modificar_anticipoh);
//    $("#btnNuevotipo_impuesto").on("click", nuevo_nominanomina);
    $("#fecha_registro").on("change", validar_fecha_registro);
    $("#fecha_registrom").on("change", validar_fecha_registrom);
    $("#fecha_registroh").on("change", validar_fecha_registroh);
    $("#descripcion").on("keypress", enter);
    $("#fecha_registrom").on("keypress", enterm);
    $("#descripcionm").on("keypress", enterm);
    $("#valor").validCampoFranz("0123456789.");
    $("#valor").on("keypress", enter2);
    $("#valorh").validCampoFranz("0123456789.");
    $("#valorh").on("keypress", enter2h);
    $("#cuarto_sueldo").on("keypress", enter2s);
    $("#valorm").on("keypress", enter2m);
    $("#btnGuardar").on("click", guardar_nomina);
    $("#btnModificar").on("click", modificar_nomina);
    $("#btnBuscar").on("click", abrirDialogo);
    $("#pf_check_unocinco").on("click", calculo25);
    $("#pf_check_dividido").on("click", calculodivi);
    $("#pf_check_acumulado").on("click", calculoacu);

    $("#pf_check_sincuenta").on("click", calculo50);
    $("#pf_check_dos").on("click", calculo100);
    $("#btnEliminar").on("click", eliminar_nomina);
    $("#btnAceptarcc").on("click", aceptarcc);
    $("#btnSalircc").on("click", cancelarcc);
    $("#btnAceptaraa").on("click", aceptaraa);
    $("#btnAceptarm").on("click", aceptarm);
    $("#btnAceptard").on("click", aceptard);
    $("#btnAceptarh").on("click", aceptarh);
    $("#btnSaliraa").on("click", cancelaraa);
    $("#btnAceptarn").on("click", aceptar);
    $("#btnSalir").on("click", cancelar);
    $("#btnSalirm").on("click", cancelarm);
    $("#btnSalirn").on("click", cancelarn);
    $("#btnAcceder").on("click", validar_acceso);
    $("#btnAccederm").on("click", validar_accesom);
    $("#btnAccederh").on("click", validar_accesoh);
    $("#btnAccedern").on("click", validar_acceson);
    $("#btnCancelar").on("click", cancelar_acceso);
    $("#btnCancelarn").on("click", cancelar_acceso);
    $("#btnAccedercc").on("click", validar_accesocc);
    $("#btnCancelarcc").on("click", cancelar_accesocc);
    $("#btnAccederaa").on("click", validar_accesoaa);
    $("#btnCancelaraa").on("click", cancelar_accesoaa);
    $("#btnNuevo").on("click", nuevo_nomina);
    $("#btnCuenta").on("click", abrirCuenta);
    $("#nominas").dialog(dialogo);
    $("#cuentas").dialog(dialogo_cuenta);
    $("#clave_permiso").dialog(dialogo3);
    $("#clave_permison").dialog(dialogo3);
    $("#clave_permisoh").dialog(dialogo3);
    $("#clave_permisocc").dialog(dialogo3);
    $("#clave_permisom").dialog(dialogo3);
    $("#clave_permisod").dialog(dialogo3);
    $("#clave_permisoaa").dialog(dialogo3);
    $("#seguro").dialog(dialogo4);
    $("#seguron").dialog(dialogo4);
    $("#segurod").dialog(dialogo4);
    $("#seguroh").dialog(dialogo4);
    $("#segurocc").dialog(dialogo4);
    $("#seguroaa").dialog(dialogo4);
    $("#segurom").dialog(dialogo4);
    $("#cargo").dialog(dialogoTipo_documento);
    $("#aporte_iess").dialog(dialogoTipo_aporte_iess);
    $("#select_mes").on("change", cargar_rubro_tarifa);
    $("#select_mesm").on("change", cargar_rubro_tarifam);
    $("#select_mesh").on("change", cargar_rubro_tarifah);
    $("#select_mess").on("change", cargar_rubro_tarifad);
    jQuery("#list").jqGrid({
        url: 'datos_nomina.php',
        datatype: 'xml',
        colNames: ['Código', 'Identificación', 'Nombres', 'Direccion', 'Móvil', 'cedular', 'Pais', 'Ciudad', 'Correo', 'ID_CARGO', 'Cargo', 'id_plan', 'fecha Actual', 'Fecha Nacimiento', 'Comentario', 'Referencia', 'etnia', 'Genero', 'Afiliado', 'Fecha Ingreso', 'Fecha Salida', 'Tele Nomina', 'Décimos ', 'Fondos Reserva', 'fondos acumula o no ', 'decimos si o no '],
        colModel: [
            {name: 'id_empleadon', index: 'id_empleadon', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'ruc_ci', index: 'ruc_ci', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'nombres_nomina', index: 'nombres_nomina', editable: true, align: 'center', width: '120', search: true, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'direccion_nomina', index: 'direccion_nomina', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'nro_telefono', index: 'nro_telefono', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'nro_celular', index: 'nro_celular', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'pais_nomina', index: 'pais_nomina', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'ciudad_nomina', index: 'ciudad_nomina', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'email', index: 'email', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'id_cargo', index: 'id_cargo', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'tipo_cargo', index: 'tipo_cargo', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'id_plan', index: 'id_plan', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'fecha_actual', index: 'fecha_actual', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'fecha_nacimiento', index: 'fecha_nacimiento', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'notas_nomina', index: 'notas_nomina', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'referencia_nomina', index: 'referencia_nomina', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'etnia', index: 'etnia', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'genero', index: 'genero', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'afiliado', index: 'afiliado', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'fecha_ingreso', index: 'fecha_ingreso', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'fecha_salida', index: 'fecha_salida', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'tele_referencia_nomina', index: 'tele_referencia_nomina', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'decimo', index: 'decimo', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'fondos_reserva', index: 'fondos_reserva', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'fondos_acu_mensual', index: 'fondos_acu_mensual', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'decimo_si_no', index: 'decimo_si_no', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
        ],
        rowNum: 10,
        width: 830,
        height: 200,
        rowList: [10, 20, 30],
        pager: jQuery('#pager12'),
        sortname: 'id_empleado',
        shrinkToFit: false,
        sortorder: 'asc',
        caption: 'Lista de Nomina',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list").jqGrid('getGridParam', 'selrow');
            jQuery('#list').jqGrid('restoreRow', id);
            jQuery("#list").jqGrid('GridToForm', id, "#nomina_form");
            $("#btnGuardar").attr("disabled", true);
            $("#nominas").dialog("close");
            var ret = jQuery("#list").jqGrid('getRowData', id);
            var valor = ret.id_empleadon;
            $.getJSON('retornar_cargo.php?com=' + valor, function (data) {
                var tama = data.length;
                if (tama !== 0) {
                    for (var i = 0; i < tama; i = i + 1) {
                        console.log(data[i]);
                        $("#tipo_cargo").val(data[i]);
                    }

                }
            });
        }
    }).jqGrid('navGrid', '#pager12',
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
    jQuery("#list").jqGrid('navButtonAdd', '#pager', {caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#list").jqGrid('getGridParam', 'selrow');
            jQuery('#list').jqGrid('restoreRow', id);
            if (id) {
                jQuery("#list").jqGrid('GridToForm', id, "#nominas_form");
                $("#btnGuardar").attr("disabled", true);
                $("#nominas").dialog("close");
            } else {
                alertify.alert("Seleccione un fila");
            }
        }
    });
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
/////////////////
    jQuery("#list_cargo").jqGrid({
        url: '../cargo/datos_cargo.php',
        datatype: 'xml',
        colNames: ['Código', 'Tipo Documento', 'Salario', 'Codigo Sectorial', 'S.B.U'],
        colModel: [
            {name: 'id_cargo', index: 'id_cargo', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'nombre_cargo', index: 'nombre_cargo', editable: true, align: 'center', width: '120', search: false, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'sueldo_base', index: 'sueldo_base', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'codigo_sectorial', index: 'codigo_sectorial', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'salario_basico_unificado', index: 'salario_basico_unificado', editable: true, align: 'center', width: '30', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}}
        ],
        rowNum: 10,
        width: 830,
        height: 200,
        rowList: [10, 20, 30],
        pager: jQuery('#pager'),
        sortname: 'id_cargo',
        shrinkToFit: false,
        sortorder: 'desc',
        caption: 'Lista ',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list_cargo").jqGrid('getGridParam', 'selrow');
            jQuery('#list_cargo').jqGrid('restoreRow', id);
            jQuery("#list_cargo").jqGrid('GridToForm', id, "#nomina_form");
            $("#btnGuardarcargo").attr("disabled", true);
            $("#btnModificarcargo").attr("disabled", false);
            $("#cargo").dialog("close");
        }
    }).jqGrid('navGrid', '#pager',
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
    jQuery("#list_cargo").jqGrid('navButtonAdd', '#pager', {caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#list_cargo").jqGrid('getGridParam', 'selrow');
            jQuery('#list_cargo').jqGrid('restoreRow', id);
            if (id) {
                jQuery("#list_cargo").jqGrid('GridToForm', id, "#nomina_form");
                $("#btnGuardarcargo").attr("disabled", true);
                $("#btnModificarcargo").attr("disabled", false);
                $("#cargo").dialog("close");
            } else {
                alertify.alert("Seleccione un fila");
            }
        }
    });
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    jQuery("#list_grid_cargo").jqGrid({

        url: '../cargo/datos_cargo_grid.php',
        colNames: ['id_cargo', 'Nombre Cargo', 'Sueldo Base', 'Codigo Sectorial', 'S.B.U'],
        colModel: [
//            {name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: {keys: false, delbutton: true, editbutton: false}},
            {name: 'id_cargo', index: 'id_cargo', editable: false, search: false, hidden: true, editrules: {edithidden: false}, align: 'center', frozen: true, width: 3},
            {name: 'nombre_cargo', index: 'nombre_cargo', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'left', frozen: true, width: 15},
            {name: 'sueldo_base', index: 'sueldo_base', editable: false, search: false, hidden: false, editrules: {required: true}, align: 'left', frozen: true, width: 3},
            {name: 'codigo_sectorial', index: 'codigo_sectorial', editable: false, search: false, hidden: false, editrules: {required: true}, align: 'left', frozen: true, width: 5},
            {name: 'salario_basico_unificado', index: 'salario_basico_unificado', editable: true, align: 'center', width: '6', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}}
        ],
        rowNum: 30,
        width: 500,
        height: 250,
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
        height: 200,
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
    jQuery("#list_anticipoh").jqGrid({

        datatype: "local",
        colNames: ['', 'ID ', 'ID EMPLEADO', 'Fecha', 'Sueldo', 'Periodo', 'Hora Extra', 'Porsentaje', 'Valor'],
        colModel: [
            {name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: {keys: false, delbutton: true, editbutton: false}},
            {name: 'id_horas_extrash', index: 'id_horas_extrash', editable: false, search: false, hidden: true, editrules: {edithidden: false}, align: 'center', frozen: true, width: 3},
            {name: 'id_empleadoh', index: 'id_empleadoh', editable: false, search: false, hidden: true, editrules: {edithidden: false}, align: 'center', frozen: true, width: 3},
            {name: 'fecha_registroh', index: 'fecha_registroh', editable: false, search: false, hidden: false, editrules: {required: true}, align: 'center', frozen: true, width: 20},
            {name: 'sueldo_persividoh', index: 'sueldo_persividoh', editable: false, hidden: true, frozen: true, editrules: {required: true}, align: 'center', width: 10},
            {name: 'periodoh', index: 'periodoh', editable: false, frozen: true, hidden: false, editrules: {required: true}, align: 'center', width: 10},
            {name: 'hora_extrah', index: 'hora_extrah', editable: false, frozen: true, hidden: false, editrules: {required: true}, align: 'center', width: 10},
            {name: 'porsentajeh', index: 'porsentajeh', editable: false, frozen: true, search: false, hidden: false, editrules: {required: true}, align: 'center', frozen: true, width: 10},
            {name: 'montoh', index: 'montoh', editable: false, frozen: true, search: false, hidden: false, editrules: {required: true}, align: 'center', frozen: true, width: 10}

        ],
        rowNum: 30,
        width: 700,
        height: 200,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager_anticipoh'),
        sortname: 'id_horas_extras',
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
                var id = jQuery("#list_anticipoh").jqGrid('getGridParam', 'selrow');
                jQuery('#list_anticipoh').jqGrid('restoreRow', id);
                var ret = jQuery("#list_anticipoh").jqGrid('getRowData', id);
                var fil = jQuery("#list_anticipoh").jqGrid("getRowData");
                var su = jQuery("#list_anticipoh").jqGrid('delRowData', rowid);
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
            var id = jQuery("#list_anticipoh").jqGrid('getGridParam', 'selrow');
            jQuery('#list_anticipoh').jqGrid('restoreRow', id);
            var ret = jQuery("#list_anticipoh").jqGrid('getRowData', id);
        },
        delOptions: {
            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {
                var id = jQuery("#list_anticipoh").jqGrid('getGridParam', 'selrow');
                jQuery('#list_anticipoh').jqGrid('restoreRow', id);
                var ret = jQuery("#list_anticipoh").jqGrid('getRowData', id);
                var su = jQuery("#list_anticipoh").jqGrid('delRowData', rowid);
                var su;
                var count = 0;
                var subtotal = 0;
                var sub1 = 0;
                var fil = jQuery("#list_anticipoh").jqGrid("getRowData");
                for (var t = 0; t < fil.length; t++) {
                    var dd = fil[t];
                    subtotal = (subtotal + (parseFloat(dd['montoh'])));
                }
                $("#valor_totalh").val(subtotal);
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
        colNames: ['', 'ID ', 'ID EMPLEADO', 'Fecha', 'Descrpcion', 'valor'],
        colModel: [
            {name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: {keys: false, delbutton: true, editbutton: false}},
            {name: 'id_anticipo', index: 'id_anticipo', editable: false, search: false, hidden: true, editrules: {edithidden: false}, align: 'center', frozen: true, width: 3},
            {name: 'id_empleado', index: 'id_empleado', editable: false, search: false, hidden: true, editrules: {edithidden: false}, align: 'center', frozen: true, width: 3},
            {name: 'fecha_registro', index: 'fecha_registro', editable: false, search: false, hidden: false, editrules: {required: true}, align: 'center', frozen: true, width: 20},
            {name: 'descripcion', index: 'descripcion', editable: false, frozen: true, editrules: {required: true}, align: 'center', width: 10},
            {name: 'monto', index: 'monto', editable: false, frozen: true, search: false, hidden: false, editrules: {required: true}, align: 'center', frozen: true, width: 10}

        ],
        rowNum: 30,
        width: 700,
        height: 200,
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
    jQuery("#list_anticipom").jqGrid({

        datatype: "local",
        colNames: ['', 'ID ', 'ID EMPLEADO', 'Fecha', 'Descrpcion', 'valor'],
        colModel: [
            {name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: {keys: false, delbutton: true, editbutton: false}},
            {name: 'id_anticipom', index: 'id_anticipom', editable: false, search: false, hidden: true, editrules: {edithidden: false}, align: 'center', frozen: true, width: 3},
            {name: 'id_empleadom', index: 'id_empleadom', editable: false, search: false, hidden: true, editrules: {edithidden: false}, align: 'center', frozen: true, width: 3},
            {name: 'fecha_registrom', index: 'fecha_registrom', editable: false, search: false, hidden: false, editrules: {required: true}, align: 'center', frozen: true, width: 20},
            {name: 'descripcionm', index: 'descripcionm', editable: false, frozen: true, editrules: {required: true}, align: 'center', width: 10},
            {name: 'montom', index: 'montom', editable: false, frozen: true, search: false, hidden: false, editrules: {required: true}, align: 'center', frozen: true, width: 10}

        ],
        rowNum: 30,
        width: 700,
        height: 200,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager_anticipom'),
        sortname: 'id_anticipom',
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
                var id = jQuery("#list_anticipom").jqGrid('getGridParam', 'selrow');
                jQuery('#list_anticipom').jqGrid('restoreRow', id);
                var ret = jQuery("#list_anticipom").jqGrid('getRowData', id);
                var fil = jQuery("#list_anticipom").jqGrid("getRowData");
                var su = jQuery("#list_anticipom").jqGrid('delRowData', rowid);
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
            var id = jQuery("#list_anticipom").jqGrid('getGridParam', 'selrow');
            jQuery('#list_anticipom').jqGrid('restoreRow', id);
            var ret = jQuery("#list_anticipom").jqGrid('getRowData', id);
        },
        delOptions: {
            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {
                var id = jQuery("#list_anticipom").jqGrid('getGridParam', 'selrow');
                jQuery('#list_anticipom').jqGrid('restoreRow', id);
                var ret = jQuery("#list_anticipom").jqGrid('getRowData', id);
                var su = jQuery("#list_anticipom").jqGrid('delRowData', rowid);
                var su;
                var count = 0;
                var subtotal = 0;
                var sub1 = 0;
                var fil = jQuery("#list_anticipom").jqGrid("getRowData");
                for (var t = 0; t < fil.length; t++) {
                    var dd = fil[t];
                    subtotal = (subtotal + (parseFloat(dd['montom'])));
                }
                $("#valor_totalm").val(subtotal);
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

