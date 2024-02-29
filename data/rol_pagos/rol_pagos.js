$(document).on("ready", inicio);
var calculoIVA = 0;
var t;
$(document).keydown(function (e) {
    var e = e || event;
    var keycode = e.which || e.keyCode;
    var obj = e.target || e.srcElement;

});

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
var dialogo2 = {
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 350,
    modal: true,
    show: "explode",
    hide: "blind"
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
var formatoFecha1 = {

    dateFormat: "yy-mm-dd",
    showAnim: 'slide'
};

var dialogos =
        {
            autoOpen: false,
            resizable: false,
            width: 760,
            height: 400,
            modal: true
        };

var dialogo_categoria =
        {
            autoOpen: false,
            resizable: false,
            width: 250,
            height: 180,
            modal: true
        };

var dialogo_marca =
        {
            autoOpen: false,
            resizable: false,
            width: 230,
            height: 180,
            modal: true
        };

var dialogo_color =
        {
            autoOpen: false,
            resizable: false,
            width: 230,
            height: 180,
            modal: true
        };



function enter1(e) {
    if (e.which == 13 || e.keyCode == 13) {
        entrar2();
        return false;
    }
    return true;
}
//function abrir_pdf_unido() {
////    $('#list_rol input[type=checkbox]').prop("checked", true).trigger("change");
//
//
//    var grid = $("#list_rol");
//    var rowKey = grid.getGridParam("selrow");
//
//
//    if (!rowKey)
//        alertify.alert("NO HA SELECCIONADO NINGUNA FILA");
//    else {
//        var selectedIDs = grid.getGridParam("selarrrow");
//
//        var myWindow = window.open("../../reportes/imprimir_rol_pagos_unido.php?hoja=A4&id=" + selectedIDs, '_blank');
//        myWindow.focus();
//        myWindow.print();
//    }
//}
function abrir_pdf_unido() {
//    $('#list_rol input[type=checkbox]').prop("checked", true).trigger("change");


    var grid = $("#list_rol");
    var rowKey = grid.getGridParam("selrow");


//    if (!rowKey)
//        alertify.alert("NO HA SELECCIONADO NINGUNA FILA");
//    else {
    var selectedIDs = grid.getGridParam("selarrrow");

    var myWindow = window.open("../../reportes/imprimir_rol_pagos_unido.php?hoja=A4&id=" + selectedIDs, '_blank');
    myWindow.focus();
    myWindow.print();
//    }
}

function entrar2() {
    if ($("#id_empleado").val() == "") {
//        $("#cedula_empleado").focus();
        alertify.error("Buscar Nomina");
    } else {
        if ($("#select_mes").val() == "0") {
            $("#select_mes").focus();
            alertify.error("Seleccionar Mes");
        } else {
            if ($("#sueldo_percivido").val() == "0.00") {
                $("#sueldo_percivido").focus();
                alertify.error("Buscar Nomina ");
            } else {
                if ($("#total_nomina").val() == "0.00") {
                    $("#total_nomina").focus();
                } else {
                    if ($("#aporte_individual").val() == "0.00" && $("#esta_afiliado").val() == "SI") {
                        $("#aporte_individual").focus();
                        alertify.error("Aporte Individual no tiene Valor");
                    } else {
                        if ($("#aporte_patronal").val() == "0.00" && $("#esta_afiliado").val() == "SI") {
                            $("#aporte_patronal").focus();
                            alertify.error("Aporte Patronal no tiene Valor");
                        } else {
//                            if ($("#total_deduccion").val() == "0.00") {
//                                $("#total_deduccion").focus();
//                                alertify.error("Calcular Total Deduccion");
//                            } else {
                            if ($("#neto_recibir").val() != "0.00") {
                                $("#neto_recibir").focus();
//                                     entrar3();
                            }
//                            }

                        }
                    }
                }
            }
        }
    }
}
function cargar_mes_guardado() {

    if ($("#id_empleado").val() == "") {

        var select_mes = $("#select_mes").val();
        $("#list_rol").jqGrid('setGridParam', {
            url: 'retornar_rol_pagos_b.php?id_clase=' + select_mes + "&anio=" + $("#slct_anio_cf").val(),
            datatype: 'xml',
        }).trigger('reloadGrid');
        var su;
        var count = 0;
        var id_rol = 0;
        var sub1 = 0;
        var fil = jQuery("#list2").jqGrid("getRowData");
        for (var t = 0; t < fil.length; t++) {
            var dd = fil[t];
            id_rol = dd['id_rol_pagos'];
        }
        $("#id_rol").val(id_rol);


        ///////////////////////////////////////////////////

        var sueldo_percividot = 0;
        var horas_extrast = 0;
        var otros_ingresost = 0;
        var fondos_recervat = 0;
        var aporte_patronalt = 0;
        var tercer_sueldot = 0;
        var cuarto_sueldot = 0;
        var total_nominat = 0;
        var aporte_individualt = 0;
        var anticipos_consumost = 0;
        var faltantes_cajat = 0;
        var multast = 0;
        var prestamos_qui_iesst = 0;
        var credito_personalt = 0;
        var otros_descuentost = 0;
        var total_deducciont = 0;
        var neto_recibirt = 0;

        var total_sueldo_percividot = 0;
        var total_horas_extrast = 0;
        var total_otros_ingresost = 0;
        var total_fondos_recervat = 0;
        var total_aporte_patronalt = 0;
        var total_tercer_sueldot = 0;
        var total_cuarto_sueldot = 0;
        var total_total_nominat = 0;
        var total_aporte_individualt = 0;
        var total_anticipos_consumost = 0;
        var total_faltantes_cajat = 0;
        var total_multast = 0;
        var total_prestamos_qui_iesst = 0;
        var total_credito_personalt = 0;
        var total_otros_descuentost = 0;
        var total_total_deducciont = 0;
        var total_neto_recibirt = 0;
        var repe = 0;

        var fil = jQuery("#list_rol").jqGrid("getRowData");
        for (var t = 0; t < fil.length; t++) {
            var dd = fil[t];
            sueldo_percividot = dd['sueldo_percivido'];
            total_sueldo_percividot = parseFloat(total_sueldo_percividot) + parseFloat(sueldo_percividot);

            aporte_patronalt = dd['aporte_patronal'];
            total_aporte_patronalt = parseFloat(total_aporte_patronalt) + parseFloat(aporte_patronalt);

            horas_extrast = dd['horas_extras'];
            total_horas_extrast = parseFloat(total_horas_extrast) + parseFloat(horas_extrast);

            tercer_sueldot = dd['tercer_sueldo'];
            total_tercer_sueldot = parseFloat(total_tercer_sueldot) + parseFloat(tercer_sueldot);

            otros_ingresost = dd['otros_ingresos'];
            total_otros_ingresost = parseFloat(total_otros_ingresost) + parseFloat(otros_ingresost);

            cuarto_sueldot = dd['cuarto_sueldo'];
            total_cuarto_sueldot = parseFloat(total_cuarto_sueldot) + parseFloat(cuarto_sueldot);

            fondos_recervat = dd['fondos_recerva'];
            total_fondos_recervat = parseFloat(total_fondos_recervat) + parseFloat(fondos_recervat);

            total_nominat = dd['total_nomina'];
            total_total_nominat = parseFloat(total_total_nominat) + parseFloat(total_nominat);

            ////////////////////////////////////////

            aporte_individualt = dd['aporte_individual'];
            total_aporte_individualt = parseFloat(total_aporte_individualt) + parseFloat(aporte_individualt);

            prestamos_qui_iesst = dd['prestamos_qui_iess'];
            total_prestamos_qui_iesst = parseFloat(total_prestamos_qui_iesst) + parseFloat(prestamos_qui_iesst);

            neto_recibirt = dd['neto_recibir'];
            total_neto_recibirt = parseFloat(total_neto_recibirt) + parseFloat(neto_recibirt);

            anticipos_consumost = dd['anticipos_consumos'];
            total_anticipos_consumost = parseFloat(total_anticipos_consumost) + parseFloat(anticipos_consumost);

            credito_personalt = dd['credito_personal'];
            total_credito_personalt = parseFloat(total_credito_personalt) + parseFloat(credito_personalt);

            faltantes_cajat = dd['faltantes_caja'];
            total_faltantes_cajat = parseFloat(total_faltantes_cajat) + parseFloat(faltantes_cajat);

            otros_descuentost = dd['otros_descuentos'];
            total_otros_descuentost = parseFloat(total_otros_descuentost) + parseFloat(otros_descuentost);

            total_deducciont = dd['total_deduccion'];
            total_total_deducciont = parseFloat(total_total_deducciont) + parseFloat(total_deducciont);

        }

        $("#sueldo_percividot").val(total_sueldo_percividot.toFixed(4));
        $("#aporte_patronalt").val(total_aporte_patronalt.toFixed(4));
        $("#horas_extrast").val(total_horas_extrast.toFixed(4));
        $("#tercer_sueldot").val(total_tercer_sueldot.toFixed(4));
        $("#otros_ingresost").val(total_otros_ingresost.toFixed(4));
        $("#cuarto_sueldot").val(total_cuarto_sueldot.toFixed(4));
        $("#fondos_recervat").val(total_fondos_recervat.toFixed(2));
        $("#total_nominat").val(total_total_nominat.toFixed(4));


        $("#aporte_individualt").val(total_aporte_individualt.toFixed(4));
        $("#prestamos_qui_iesst").val(total_prestamos_qui_iesst.toFixed(4));
        $("#neto_recibirt").val(total_neto_recibirt.toFixed(4));
        $("#anticipos_consumost").val(total_anticipos_consumost.toFixed(4));
        $("#credito_personalt").val(total_credito_personalt.toFixed(4));
        $("#faltantes_cajat").val(total_faltantes_cajat.toFixed(2));
        $("#otros_descuentost").val(total_otros_descuentost.toFixed(4));
        $("#multast").val(total_multast.toFixed(4));
        $("#total_deducciont").val(total_total_deducciont.toFixed(4));
//        $("#cedula_empleado").focus();

    }

}
function limpiar_campos() {
    $("#cedula_empleado").val("");
    $("#id_empleado").val("");
    $("#cargo_empleado").val("");
    $("#salario_empleado").val("");
    $("#dias_trabajados").val("30");
    $("#esta_afiliado").val("");
    $("#sueldo_percivido").val("0.00");
    $("#fondos_recerva").val("0.00");
    $("#otros_ingresos").val("0.00");
    $("#horas_extras").val("0.00");
    $("#tercer_sueldo").val("0.00");
    $("#total_nomina").val("0.00");
    $("#aporte_patronal").val("0.00");

    $("#aporte_individual").val("0.00");
    $("#anticipos_consumos").val("0.00");
    $("#otros_descuentos").val("0.00");
    $("#prestamos_qui_iess").val("0.00");
    $("#faltantes_caja").val("0.00");
    $("#total_deduccion").val("0.00");
    $("#credito_personal").val("0.00");
    $("#multas").val("0.00");
    $("#neto_recibir").val("0.00");
    $("#nombres_empleado").val("");
}

function entrar3() {
    $("#clic_agregar").val("1");




//    $('#list_rol input[type=checkbox]').prop("checked", true).trigger("change");
    if ($("#forma_pago").val() != '0') {
        console.log("if1");
        if ($("#sueldo_percivido").val() != "NaN" || $("#total_nomina").val() != "NaN" || $("#dias_trabajados").val() != "") {
            console.log("if2");
            if (document.getElementById('nomina_mes').checked == true && $("#id_rol").val() != '') {
                console.log("if3");
                $.ajax({
                    type: "POST",
                    url: "consulta_existe_id_emple.php",
                    data: "slct_anio_cf=" + $("#slct_anio_cf").val() + "&select_mes=" + $("#select_mes").val() + "&id_empleado=" + $("#id_empleado").val(),
                    success: function (data) {
                        var val = data;
                        if (val == 13) {
                            alertify.error("El empleado ya se ecuentra registrado en el mes seleccionado");
                        }
                        if (val == 12) {
                            console.log("if4");
                            $.ajax({
                                type: "POST",
                                url: "consulta_existe_rol.php",
                                data: "slct_anio_cf=" + $("#slct_anio_cf").val() + "&select_mes=" + $("#select_mes").val(),
                                success: function (data) {
                                    var val = data;
                                    if ($("#neto_recibir").val() != "0.00") {
                                        var filas = jQuery("#list_rol").jqGrid("getRowData");
                                        var sueldo_percividot = 0;
                                        var horas_extrast = 0;
                                        var otros_ingresost = 0;
                                        var fondos_recervat = 0;
                                        var aporte_patronalt = 0;
                                        var tercer_sueldot = 0;
                                        var cuarto_sueldot = 0;
                                        var total_nominat = 0;
                                        var aporte_individualt = 0;
                                        var anticipos_consumost = 0;
                                        var faltantes_cajat = 0;
                                        var multast = 0;
                                        var prestamos_qui_iesst = 0;
                                        var credito_personalt = 0;
                                        var otros_descuentost = 0;
                                        var total_deducciont = 0;
                                        var neto_recibirt = 0;

                                        var total_sueldo_percividot = 0;
                                        var total_horas_extrast = 0;
                                        var total_otros_ingresost = 0;
                                        var total_fondos_recervat = 0;
                                        var total_aporte_patronalt = 0;
                                        var total_tercer_sueldot = 0;
                                        var total_cuarto_sueldot = 0;
                                        var total_total_nominat = 0;
                                        var total_aporte_individualt = 0;
                                        var total_anticipos_consumost = 0;
                                        var total_faltantes_cajat = 0;
                                        var total_multast = 0;
                                        var total_prestamos_qui_iesst = 0;
                                        var total_credito_personalt = 0;
                                        var total_otros_descuentost = 0;
                                        var total_total_deducciont = 0;
                                        var total_neto_recibirt = 0;
                                        var repe = 0;
                                        var filas = jQuery("#list_rol").jqGrid("getRowData");
                                        console.log("1 ingreso grid");
                                        if (filas.length == 0) {
                                            console.log("if5");
                                            var datarow = {
                                                id_empleado: $("#id_empleado").val(),
                                                cedula_empleado: $("#cedula_empleado").val(),
                                                nombres_empleado: $("#nombres_empleado").val(),
                                                cargo_empleado: $("#cargo_empleado").val(),
                                                dias_trabajados: $("#dias_trabajados").val(),
                                                salario_empleado: $("#salario_empleado").val(),
                                                sueldo_percivido: $("#sueldo_percivido").val(),
                                                horas_extras: $("#horas_extras").val(),
                                                otros_ingresos: $("#otros_ingresos").val(),
                                                fondos_recerva: $("#fondos_recerva").val(),
                                                aporte_patronal: $("#aporte_patronal").val(),
                                                tercer_sueldo: $("#tercer_sueldo").val(),
                                                cuarto_sueldo: $("#cuarto_sueldo").val(),
                                                total_nomina: $("#total_nomina").val(),
                                                aporte_individual: $("#aporte_individual").val(),
                                                anticipos_consumos: $("#anticipos_consumos").val(),
                                                faltantes_caja: $("#faltantes_caja").val(),
                                                multas: $("#multas").val(),
                                                prestamos_qui_iess: $("#prestamos_qui_iess").val(),
                                                credito_personal: $("#credito_personal").val(),
                                                otros_descuentos: $("#otros_descuentos").val(),
                                                total_deduccion: $("#total_deduccion").val(),
                                                neto_recibir: $("#neto_recibir").val()
                                            };

                                            var su = jQuery("#list_rol").jqGrid('addRowData', $("#id_empleado").val(), datarow);
                                            limpiar_campos();
                                        } else {

                                        }
                                        var fil = jQuery("#list_rol").jqGrid("getRowData");
                                        for (var t = 0; t < fil.length; t++) {
                                            var dd = fil[t];
                                            sueldo_percividot = dd['sueldo_percivido'];
                                            total_sueldo_percividot = parseFloat(total_sueldo_percividot) + parseFloat(sueldo_percividot);

                                            aporte_patronalt = dd['aporte_patronal'];
                                            total_aporte_patronalt = parseFloat(total_aporte_patronalt) + parseFloat(aporte_patronalt);

                                            horas_extrast = dd['horas_extras'];
                                            total_horas_extrast = parseFloat(total_horas_extrast) + parseFloat(horas_extrast);

                                            tercer_sueldot = dd['tercer_sueldo'];
                                            total_tercer_sueldot = parseFloat(total_tercer_sueldot) + parseFloat(tercer_sueldot);

                                            otros_ingresost = dd['otros_ingresos'];
                                            total_otros_ingresost = parseFloat(total_otros_ingresost) + parseFloat(otros_ingresost);

                                            cuarto_sueldot = dd['cuarto_sueldo'];
                                            total_cuarto_sueldot = parseFloat(total_cuarto_sueldot) + parseFloat(cuarto_sueldot);

                                            fondos_recervat = dd['fondos_recerva'];
                                            total_fondos_recervat = parseFloat(total_fondos_recervat) + parseFloat(fondos_recervat);

                                            total_nominat = dd['total_nomina'];
                                            total_total_nominat = parseFloat(total_total_nominat) + parseFloat(total_nominat);

                                            ////////////////////////////////////////

                                            aporte_individualt = dd['aporte_individual'];
                                            total_aporte_individualt = parseFloat(total_aporte_individualt) + parseFloat(aporte_individualt);

                                            prestamos_qui_iesst = dd['prestamos_qui_iess'];
                                            total_prestamos_qui_iesst = parseFloat(total_prestamos_qui_iesst) + parseFloat(prestamos_qui_iesst);

                                            neto_recibirt = dd['neto_recibir'];
                                            total_neto_recibirt = parseFloat(total_neto_recibirt) + parseFloat(neto_recibirt);

                                            anticipos_consumost = dd['anticipos_consumos'];
                                            total_anticipos_consumost = parseFloat(total_anticipos_consumost) + parseFloat(anticipos_consumost);

                                            credito_personalt = dd['credito_personal'];
                                            total_credito_personalt = parseFloat(total_credito_personalt) + parseFloat(credito_personalt);

                                            faltantes_cajat = dd['faltantes_caja'];
                                            total_faltantes_cajat = parseFloat(total_faltantes_cajat) + parseFloat(faltantes_cajat);

                                            otros_descuentost = dd['otros_descuentos'];
                                            total_otros_descuentost = parseFloat(total_otros_descuentost) + parseFloat(otros_descuentost);

                                            total_deducciont = dd['total_deduccion'];
                                            total_total_deducciont = parseFloat(total_total_deducciont) + parseFloat(total_deducciont);

                                        }

                                        $("#sueldo_percividot").val(total_sueldo_percividot.toFixed(4));
                                        $("#aporte_patronalt").val(total_aporte_patronalt.toFixed(4));
                                        $("#horas_extrast").val(total_horas_extrast.toFixed(4));
                                        $("#tercer_sueldot").val(total_tercer_sueldot.toFixed(4));
                                        $("#otros_ingresost").val(total_otros_ingresost.toFixed(4));
                                        $("#cuarto_sueldot").val(total_cuarto_sueldot.toFixed(4));
                                        $("#fondos_recervat").val(total_fondos_recervat.toFixed(2));
                                        $("#total_nominat").val(total_total_nominat.toFixed(4));

                                        $("#aporte_individualt").val(total_aporte_individualt.toFixed(4));
                                        $("#prestamos_qui_iesst").val(total_prestamos_qui_iesst.toFixed(4));
                                        $("#neto_recibirt").val(total_neto_recibirt.toFixed(4));
                                        $("#anticipos_consumost").val(total_anticipos_consumost.toFixed(4));
                                        $("#credito_personalt").val(total_credito_personalt.toFixed(4));
                                        $("#faltantes_cajat").val(total_faltantes_cajat.toFixed(2));
                                        $("#otros_descuentost").val(total_otros_descuentost.toFixed(4));
                                        $("#multast").val(total_multast.toFixed(4));
                                        $("#total_deducciont").val(total_total_deducciont.toFixed(4));
//                                    $("#cedula_empleado").focus();
                                    } else {
                                        alertify.error("BUCAR NOMINA");
                                    }
                                }
                            });
                        }
                    }
                });
            } else {
                console.log("if6");
                $.ajax({
                    type: "POST",
                    url: "consulta_existe_rol.php",
                    data: "slct_anio_cf=" + $("#slct_anio_cf").val() + "&select_mes=" + $("#select_mes").val() + "&id_empleado=" + $("#id_empleado").val(),
                    success: function (data) {
                        var val = data;

                        if (val == 1) {
                            console.log("if7");
                            if ($("#neto_recibir").val() != "0.00") {
                                console.log("if8");
                                var filas = jQuery("#list_rol").jqGrid("getRowData");
                                var sueldo_percividot = 0;
                                var horas_extrast = 0;
                                var otros_ingresost = 0;
                                var fondos_recervat = 0;
                                var aporte_patronalt = 0;
                                var tercer_sueldot = 0;
                                var cuarto_sueldot = 0;
                                var total_nominat = 0;
                                var aporte_individualt = 0;
                                var anticipos_consumost = 0;
                                var faltantes_cajat = 0;
                                var multast = 0;
                                var prestamos_qui_iesst = 0;
                                var credito_personalt = 0;
                                var otros_descuentost = 0;
                                var total_deducciont = 0;
                                var neto_recibirt = 0;

                                var total_sueldo_percividot = 0;
                                var total_horas_extrast = 0;
                                var total_otros_ingresost = 0;
                                var total_fondos_recervat = 0;
                                var total_aporte_patronalt = 0;
                                var total_tercer_sueldot = 0;
                                var total_cuarto_sueldot = 0;
                                var total_total_nominat = 0;
                                var total_aporte_individualt = 0;
                                var total_anticipos_consumost = 0;
                                var total_faltantes_cajat = 0;
                                var total_multast = 0;
                                var total_prestamos_qui_iesst = 0;
                                var total_credito_personalt = 0;
                                var total_otros_descuentost = 0;
                                var total_total_deducciont = 0;
                                var total_neto_recibirt = 0;
                                var repe = 0;
                                var filas = jQuery("#list_rol").jqGrid("getRowData");
                                console.log("2 grid aki");
                                if (filas.length == 0) {
                                    console.log("if8..");
                                    var datarow = {
                                        id_empleado: $("#id_empleado").val(),
                                        cedula_empleado: $("#cedula_empleado").val(),
                                        nombres_empleado: $("#nombres_empleado").val(),
                                        cargo_empleado: $("#cargo_empleado").val(),
                                        dias_trabajados: $("#dias_trabajados").val(),
                                        salario_empleado: $("#salario_empleado").val(),
                                        sueldo_percivido: $("#sueldo_percivido").val(),
                                        horas_extras: $("#horas_extras").val(),
                                        otros_ingresos: $("#otros_ingresos").val(),
                                        fondos_recerva: $("#fondos_recerva").val(),
                                        aporte_patronal: $("#aporte_patronal").val(),
                                        tercer_sueldo: $("#tercer_sueldo").val(),
                                        cuarto_sueldo: $("#cuarto_sueldo").val(),
                                        total_nomina: $("#total_nomina").val(),
                                        aporte_individual: $("#aporte_individual").val(),
                                        anticipos_consumos: $("#anticipos_consumos").val(),
                                        faltantes_caja: $("#faltantes_caja").val(),
                                        multas: $("#multas").val(),
                                        prestamos_qui_iess: $("#prestamos_qui_iess").val(),
                                        credito_personal: $("#credito_personal").val(),
                                        otros_descuentos: $("#otros_descuentos").val(),
                                        total_deduccion: $("#total_deduccion").val(),
                                        neto_recibir: $("#neto_recibir").val()
                                    };

                                    var su = jQuery("#list_rol").jqGrid('addRowData', $("#id_empleado").val(), datarow);
                                    limpiar_campos();
                                } else {
                                    for (var i = 0; i < filas.length; i++) {
                                        var id = filas[i];
                                        if (id['id_empleado'] == $("#id_empleado").val()) {
                                            repe = 1;
                                        }
                                    }
                                    if (repe == 1) {
                                        alertify.error("YA SE ENCUENTRA AGREGADO")
                                        limpiar_campos();
                                    } else {
                                        alertify.error("Error.... solo puede agregar un empleado")
                                    }

                                }
                                var fil = jQuery("#list_rol").jqGrid("getRowData");
                                for (var t = 0; t < fil.length; t++) {
                                    var dd = fil[t];
                                    sueldo_percividot = dd['sueldo_percivido'];
                                    total_sueldo_percividot = parseFloat(total_sueldo_percividot) + parseFloat(sueldo_percividot);

                                    aporte_patronalt = dd['aporte_patronal'];
                                    total_aporte_patronalt = parseFloat(total_aporte_patronalt) + parseFloat(aporte_patronalt);

                                    horas_extrast = dd['horas_extras'];
                                    total_horas_extrast = parseFloat(total_horas_extrast) + parseFloat(horas_extrast);

                                    tercer_sueldot = dd['tercer_sueldo'];
                                    total_tercer_sueldot = parseFloat(total_tercer_sueldot) + parseFloat(tercer_sueldot);

                                    otros_ingresost = dd['otros_ingresos'];
                                    total_otros_ingresost = parseFloat(total_otros_ingresost) + parseFloat(otros_ingresost);

                                    cuarto_sueldot = dd['cuarto_sueldo'];
                                    total_cuarto_sueldot = parseFloat(total_cuarto_sueldot) + parseFloat(cuarto_sueldot);

                                    fondos_recervat = dd['fondos_recerva'];
                                    total_fondos_recervat = parseFloat(total_fondos_recervat) + parseFloat(fondos_recervat);

                                    total_nominat = dd['total_nomina'];
                                    total_total_nominat = parseFloat(total_total_nominat) + parseFloat(total_nominat);

                                    ////////////////////////////////////////

                                    aporte_individualt = dd['aporte_individual'];
                                    total_aporte_individualt = parseFloat(total_aporte_individualt) + parseFloat(aporte_individualt);

                                    prestamos_qui_iesst = dd['prestamos_qui_iess'];
                                    total_prestamos_qui_iesst = parseFloat(total_prestamos_qui_iesst) + parseFloat(prestamos_qui_iesst);

                                    neto_recibirt = dd['neto_recibir'];
                                    total_neto_recibirt = parseFloat(total_neto_recibirt) + parseFloat(neto_recibirt);

                                    anticipos_consumost = dd['anticipos_consumos'];
                                    total_anticipos_consumost = parseFloat(total_anticipos_consumost) + parseFloat(anticipos_consumost);

                                    credito_personalt = dd['credito_personal'];
                                    total_credito_personalt = parseFloat(total_credito_personalt) + parseFloat(credito_personalt);

                                    faltantes_cajat = dd['faltantes_caja'];
                                    total_faltantes_cajat = parseFloat(total_faltantes_cajat) + parseFloat(faltantes_cajat);

                                    otros_descuentost = dd['otros_descuentos'];
                                    total_otros_descuentost = parseFloat(total_otros_descuentost) + parseFloat(otros_descuentost);

                                    total_deducciont = dd['total_deduccion'];
                                    total_total_deducciont = parseFloat(total_total_deducciont) + parseFloat(total_deducciont);

                                }

                                $("#sueldo_percividot").val(total_sueldo_percividot.toFixed(4));
                                $("#aporte_patronalt").val(total_aporte_patronalt.toFixed(4));
                                $("#horas_extrast").val(total_horas_extrast.toFixed(4));
                                $("#tercer_sueldot").val(total_tercer_sueldot.toFixed(4));
                                $("#otros_ingresost").val(total_otros_ingresost.toFixed(4));
                                $("#cuarto_sueldot").val(total_cuarto_sueldot.toFixed(4));
                                $("#fondos_recervat").val(total_fondos_recervat.toFixed(2));
                                $("#total_nominat").val(total_total_nominat.toFixed(4));

                                $("#aporte_individualt").val(total_aporte_individualt.toFixed(4));
                                $("#prestamos_qui_iesst").val(total_prestamos_qui_iesst.toFixed(4));
                                $("#neto_recibirt").val(total_neto_recibirt.toFixed(4));
                                $("#anticipos_consumost").val(total_anticipos_consumost.toFixed(4));
                                $("#credito_personalt").val(total_credito_personalt.toFixed(4));
                                $("#faltantes_cajat").val(total_faltantes_cajat.toFixed(2));
                                $("#otros_descuentost").val(total_otros_descuentost.toFixed(4));
                                $("#multast").val(total_multast.toFixed(4));
                                $("#total_deducciont").val(total_total_deducciont.toFixed(4));
//                            $("#cedula_empleado").focus();
                            } else {
                                alertify.error("BUCAR NOMINA");
                            }

                        }

                        if (val == 11) {
                            alertify.error("EL MES SELECCIONADO YA ESTA GUARDADO");
                        }
                    }
                });

            }
        } else {
            alertify.error("ERROR... REVICE LOS CAMPOS");
        }
    } else {
        alertify.error("Error... Debe seleccionar una forma de Pago")
    }
}

function guardarRegistro() {
//    $('#list_rol input[type=checkbox]').prop("checked", true).trigger("change");


    var grid = $("#list_rol");
    var rowKey = grid.getGridParam("selrow");


//    if (!rowKey)
//        alertify.alert("NO HA SELECCIONADO NINGUNA FILA");
//    else {


    var tam = jQuery("#list_rol").jqGrid("getRowData");
    if (tam.length == 0) {
        $("#list_rol").focus();
        alertify.error("Error... Ingrese productos en el inventario");
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
        var v11 = new Array();
        var v12 = new Array();
        var v13 = new Array();
        var v14 = new Array();

        var v15 = new Array();
        var v16 = new Array();
        var v17 = new Array();
        var v18 = new Array();
        var v19 = new Array();

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
        var string_v15 = "";
        var string_v16 = "";
        var string_v17 = "";
        var string_v18 = "";
        var string_v19 = "";

        var fil = jQuery("#list_rol").jqGrid("getRowData");
        for (var i = 0; i < fil.length; i++) {
            var datos = fil[i];
            v1[i] = datos['id_empleado'];
            v2[i] = datos['dias_trabajados'];
            v3[i] = datos['sueldo_percivido'];
            v4[i] = datos['horas_extras'];
            v5[i] = datos['otros_ingresos'];
            v6[i] = datos['fondos_recerva'];
            v7[i] = datos['aporte_patronal'];
            v8[i] = datos['tercer_sueldo'];
            v9[i] = datos['cuarto_sueldo'];
            v10[i] = datos['total_nomina'];
            v11[i] = datos['aporte_individual'];
            v12[i] = datos['anticipos_consumos'];
            v13[i] = datos['faltantes_caja'];
            v14[i] = datos['multas'];
            v15[i] = datos['prestamos_qui_iess'];
            v16[i] = datos['credito_personal'];
            v17[i] = datos['otros_descuentos'];
            v18[i] = datos['total_deduccion'];
            v19[i] = datos['neto_recibir'];
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
            string_v13 = string_v13 + "|" + v13[i];
            string_v14 = string_v14 + "|" + v14[i];
            string_v15 = string_v15 + "|" + v15[i];
            string_v16 = string_v16 + "|" + v16[i];
            string_v17 = string_v17 + "|" + v17[i];
            string_v18 = string_v18 + "|" + v18[i];
            string_v19 = string_v19 + "|" + v19[i];

        }
//        $("#btnGuardar").attr("disabled", true);
        $.ajax({
            type: "POST",
            url: "guardar_rol_pagos.php",
            data: "slct_anio_cf=" + $("#slct_anio_cf").val() + "&select_mes=" + $("#select_mes").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&campo6=" + string_v6 + "&campo7=" + string_v7 + "&campo8=" + string_v8 + "&campo9=" + string_v9 + "&campo10=" + string_v10 + "&campo11=" + string_v11 + "&campo12=" + string_v12 + "&campo13=" + string_v13 + "&campo14=" + string_v14 + "&campo15=" + string_v15 + "&campo16=" + string_v16 + "&campo17=" + string_v17 + "&campo18=" + string_v18 + "&campo19=" + string_v19 + "&neto_recibirt=" + $("#neto_recibirt").val() + "&fecha_actual=" + $("#fecha_registro").val() + "&nomina_mes=" + $("#nomina_mes").val() + "&id_rol=" + $("#id_rol").val() + "&forma_pago=" + $("#forma_pago").val() + "&idCuenta=" + $("#idCuenta").val() + "&decimo_rol=" + $("#decimo_rol").val() + "&fondos_acu_mensual=" + $("#fondos_acu_mensual").val() + "&cheque_tarjeta=" + $("#cheque_tarjeta").val(),
            success: function (data) {


                var val = data;
                if (val != 0) {
                    alertify.alert("Guardado correctamente", function () {
                        imprimirRol(val);
                        setTimeout(function () {
                            location.reload();
                        }, 8000);
                    });
                } else if (val == '60') {
                    alertify.error("Error....Ocurrio un error en guardar asiento")
                }


                if (val == 11) {
                    alertify.error("EL MES SELECCIONADO YA ESTA GUARDADO");
//                    $("#btnGuardar").attr("disabled", false);
                }
            }
        });
    }
//    }
}
function inicializarSelectAnioFuncio() {
    var currentyearf = new Date().getFullYear();
    for (var i = currentyearf; i > currentyearf - 50; i--) {
        var opt = new Option(i, i, false, false);
        $("#slct_anio_cf")[0].append(opt);
    }
}
function enterpvpmi(e) {
    if (e.which == 13 || e.keyCode == 13) {
        sueldo_percibido();
        return false;
    }
    return true;
}

function enteraportepatronal(e) {
    if (e.which == 13 || e.keyCode == 13) {
        aporte_patronal();
        return false;
    }
    return true;
}
function total_nomina(e) {
    if (e.which == 13 || e.keyCode == 13) {
        funcion_total_nomina();
        return false;
    }
    return true;
}
function aporte_individual(e) {
    if (e.which == 13 || e.keyCode == 13) {
        funcion_aporte_individual();
        return false;
    }
    return true;
}
function total_decimo_tercero(e) {
    if (e.which == 13 || e.keyCode == 13) {
        funcion_decimo_tercero();
        return false;
    }
    return true;
}
function total_fondo_reserva(e) {
    if (e.which == 13 || e.keyCode == 13) {
        funcion_fondo_reserva();
        return false;
    }
    return true;
}
function total_decimo_cuarto(e) {
    if (e.which == 13 || e.keyCode == 13) {
        funcion_decimo_cuarto();
        return false;
    }
    return true;
}
function total_deduccion(e) {
    if (e.which == 13 || e.keyCode == 13) {
        funcion_total_deduccion();
        return false;
    }
    return true;
}
function neto_recibir(e) {
    if (e.which == 13 || e.keyCode == 13) {
        funcion_neto_recibir();
        return false;
    }
    return true;
}
function limpiar_campo_todo() {
    if ($("#dias_trabajados").val() == "") {
        $("#sueldo_percivido").val("0.00");
        $("#fondos_recerva").val("0.00");
        $("#otros_ingresos").val("0.00");
        $("#horas_extras").val("0.00");
        $("#total_nomina").val("0.00");
        $("#aporte_patronal").val("0.00");
        $("#cuarto_sueldo").val("0.00");
        $("#aporte_individual").val("0.00");
        $("#anticipos_consumos").val("0.00");
        $("#otros_descuentos").val("0.00");
        $("#prestamos_qui_iess").val("0.00");
        $("#total_deduccion").val("0.00");
        $("#credito_personal").val("0.00");
        $("#multas").val("0.00");
        $("#neto_recibir").val("0.00");
    }
}
function cedula_empleado(e) {
    if (e.which == 13 || e.keyCode == 13) {
        cedula_empleado();
        return false;
    }
    return true;
}
function imprimirRol(id) {
    var select_mes = $("#select_mes").val();
//    console.log("dataas" + tipo_tarifa);
//    $.ajax({
//        type: "POST",
//        url: "xmlBuscarRolImpri.php?select_mes=" + select_mes + "&anio=" + $("#slct_anio_cf").val(),
//        data: "",
//        success: function (data) {
//            var val = data;
//            var valores;
//            valores = val.split("*");
//            $("#id_rol").val(valores[0]);
//            if ($("#id_rol").val() != "") {

    var myWindow = window.open("../../reportes/imprimir_rol_pagos.php?hoja=A4&id=" + id, '_blank');
    myWindow.focus();
    myWindow.print();
//            } else {
//                alertify.error("No esta guardado");
//                setTimeout(function () {
//
//                }, 1000);
//            }
//        }
//    });


}
function cedula_empleado() {
    if ($("#cedula_empleado").val() == "") {
//        $("#cedula_empleado").focus();
//        alertify.error("Buscar Nomina");
    } else {
        $("#dias_trabajados").focus();
    }
}
function dias_trabajados() {
    if ($("#dias_trabajados").val() == "" || $("#select_mes").val() == '0') {
        $("#dias_trabajados").focus();
        alertify.error("Seleccionar el Mes ");
    } else {
        $("#sueldo_percivido").focus();
    }
}
function sueldo_percivido() {
    if ($("#sueldo_percivido").val() == "") {
        $("#sueldo_percivido").focus();
//        alertify.error("Seleccionar el Mes ");
    } else {
        $("#horas_extras").focus();
    }
}
function horas_extras() {
    if ($("#horas_extras").val() == "") {
        $("#horas_extras").focus();
//        alertify.error("Seleccionar el Mes ");
    } else {
        $("#aporte_patronal").focus();
    }
}
function aporte_patronalkey() {
    if ($("#aporte_patronal").val() == "") {
        $("#aporte_patronal").focus();
//        alertify.error("Seleccionar el Mes ");
    } else {
        $("#fondos_recerva").focus();
    }
}
function fondos_recerva() {
    if ($("#fondos_recerva").val() == "") {
        $("#fondos_recerva").focus();
//        alertify.error("Seleccionar el Mes ");
    } else {
        $("#tercer_sueldo").focus();
    }
}
function tercer_sueldo() {
    if ($("#tercer_sueldo").val() == "") {
        $("#tercer_sueldo").focus();
//        alertify.error("Seleccionar el Mes ");
    } else {

        $("#cuarto_sueldo").focus();
    }
}
function cuarto_sueldo() {
    if ($("#cuarto_sueldo").val() == "") {
        $("#cuarto_sueldo").focus();
//        alertify.error("Seleccionar el Mes ");
    } else {
        $("#otros_ingresos").focus();
    }
}

function total_nominakey() {
    if ($("#total_nomina").val() == "") {
        $("#total_nomina").focus();
//        alertify.error("Seleccionar el Mes ");
    } else {
        $("#aporte_individual").focus();
    }
}
function aporte_individualkey() {
    if ($("#aporte_individual").val() == "") {
        $("#aporte_individual").focus();
//        alertify.error("Seleccionar el Mes ");
    } else {
        $("#prestamos_qui_iess").focus();
    }
}
function prestamos_qui_iess() {
    if ($("#prestamos_qui_iess").val() == "") {
        $("#prestamos_qui_iess").focus();
//        alertify.error("Seleccionar el Mes ");
    } else {
        $("#credito_personal").focus();
    }
}
function funcion_otros_ingresos() {
    if ($("#otros_ingresos").val() == "") {
        $("#otros_ingresos").focus();
//        alertify.error("Seleccionar el Mes ");
    } else {
        $("#total_nomina").focus();
    }
}
function credito_personal() {
    console.log("si credito personal");
    if ($("#credito_personal").val() == "") {
        $("#credito_personal").focus();
//        alertify.error("Seleccionar el Mes ");
    } else {
        $("#anticipos_consumos").focus();
    }
}
function anticipos_consumoskey() {
    if ($("#anticipos_consumos").val() == "") {
        $("#anticipos_consumos").focus();
//        alertify.error("Seleccionar el Mes ");
    } else {
        $("#otros_descuentos").focus();
    }
}
function faltantes_caja() {
    if ($("#faltantes_caja").val() == "") {
        $("#faltantes_caja").focus();
//        alertify.error("Seleccionar el Mes ");
    } else {
        $("#otros_descuentos").focus();
    }
}
function anticipos_consumos() {
    if ($("#anticipos_consumos").val() == "") {
        $("#anticipos_consumos").focus();
//        alertify.error("Seleccionar el Mes ");
    } else {
        $("#otros_descuentos").focus();
    }
}
function multas() {
    if ($("#multas").val() == "") {
        $("#multas").focus();
//        alertify.error("Seleccionar el Mes ");
    } else {
        $("#otros_descuentos").focus();
    }
}
function otros_descuentos() {
    if ($("#otros_descuentos").val() == "") {
        $("#otros_descuentos").focus();
//        alertify.error("Seleccionar el Mes ");
    } else {
        $("#total_deduccion").focus();
    }
}
function total_deduccionkey() {
    if ($("#total_deduccion").val() == "") {
        $("#total_deduccion").focus();
//        alertify.error("Seleccionar el Mes ");
    } else {
        $("#neto_recibir").focus();
    }
}
//function entrar() {
//    console.log("ffd");
//    if ($("#cedula_empleado").val() == "") {
//        $("#cedula_empleado").focus();
//        alertify.error("Ingrese un producto");
//    } else {
//        console.log("1");
//        if ($("#nombres_empleado").val() == "") {
//            $("#nombres_empleado").focus();
//            alertify.error("Ingrese un producto");
//        } else {
//            if ($("#dias_trabajados").val() == "") {
//                $("#dias_trabajados").focus();
//                alertify.error("Ingrese un producto");
//            } else {
//                if ($("#sueldo_percivido").val() == "0.00") {
//                    $("#sueldo_percivido").focus();
//                } else {
//                    if ($("#aporte_patronal").val() == "0.00" && $("#esta_afiliado").val() == "SI") {
//                        $("#aporte_patronal").focus();
//                    } else {
//                        if ($("#tercer_sueldo").val() == "") {
//                            $("#tercer_sueldo").focus();
//                        } else {
//                            if ($("#cuarto_sueldo").val() == "") {
//                                $("#cuarto_sueldo").focus();
//                            } else {
//                                if ($("#total_nomina").val() == "0.00") {
//                                    $("#total_nomina").focus();
//                                } else {
//                                    if ($("#aporte_individual").val() == "0.00" && $("#esta_afiliado").val() == "SI") {
//                                        $("#aporte_individual").focus();
//                                    } else {
//                                        if ($("#total_deduccion").val() == "0.00") {
//                                            $("#total_deduccion").focus();
//                                        } else {
//                                            if ($("#neto_recibir").val() == "0.00") {
//                                                $("#neto_recibir").focus();
//                                            }
//                                        }
//                                    }
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//        }
//    }
//}


function cargar_multa(e) {
    if (e.which == 13 || e.keyCode == 13) {
        funcion_cargar_multa();
        return false;
    }
    return true;
}
function cargar_anticipo(e) {
    if (e.which == 13 || e.keyCode == 13) {
        funcion_cargar_anticipos();
        return false;
    }
    return true;
}
function cargar_anticipo_anti(e) {
    if (e.which == 13 || e.keyCode == 13) {
        funcion_cargar_anticipos_anti();
        return false;
    }
    return true;
}
function horas_extras_fun(e) {
    if (e.which == 13 || e.keyCode == 13) {
        funcion_cargar_horas_extras();
        return false;
    }
    return true;
}

function funcion_cargar_multa() {

    var cedula = $("#cedula_empleado").val();
    var anio = $("#slct_anio_cf").val();
    var mes = $("#select_mes").val();
    if ($("#select_mes").val() != 0)
    {
        $.getJSON('buscar_cliente_multa.php?com=' + $("#id_empleado").val() + "&anio=" + anio + "&mes=" + mes, function (data) {
            var tama = data.length;
            if (tama !== 0) {
                for (var i = 0; i < tama; i = i + 1) {
                    $("#multas").val(data[i]);


                }
            }
        });
    } else {
        alertify.error("SELECCIONAR MES");
    }
}
function funcion_cargar_anticipos_anti() {

    if ($("#id_empleado").val() != "") {
        var cedula = $("#cedula_empleado").val();
        var anio = $("#slct_anio_cf").val();
        var mes = $("#select_mes").val();
        $.getJSON('buscar_cliente_anticipo.php?com=' + $("#id_empleado").val() + "&anio=" + anio + "&mes=" + mes, function (data) {
            var tama = data.length;
            console.log(tama + "anti1");
            if (tama != '0') {
                for (var i = 0; i < tama; i = i + 1) {
                    $("#anticipos_consumos").val(data[i]);

                }
                $("#faltantes_caja").focus();
            } else
            {
                $("#faltantes_caja").val("0.00");
            }
        });


    } else {
        $('#messi').prop('selected', true);
        alertify.error("Buscar nomina2");
    }
}
function funcion_cargar_horas_extras() {

    if ($("#id_empleado").val() != "") {
        var cedula = $("#cedula_empleado").val();
        var anio = $("#slct_anio_cf").val();
        var mes = $("#select_mes").val();
        $.getJSON('buscar_cliente_hora_extra.php?com=' + $("#id_empleado").val() + "&anio=" + anio + "&mes=" + mes, function (data) {
            var tama = data.length;
            if (tama != '0') {
                for (var i = 0; i < tama; i = i + 1) {
                    $("#horas_extras").val(data[i]);
                }
                $("#aporte_patronal").focus();
            } else
            {
//            $("#horas_extras").val("0.00");
            }
        });


    } else {
        $('#messi').prop('selected', true);
        alertify.error("Buscar nomina2");
    }
}
function funcion_cargar_anticipos() {

//    if ($("#id_empleado").val() != "") {

    console.log("jjj" + $("#select_mes").val());
    var cedula = $("#cedula_empleado").val();
    var anio = $("#slct_anio_cf").val();
    var mes = $("#select_mes").val();

    $("#dias_trabajados").focus();
    console.log("si anti2" + $("#select_mes").val());
    $.getJSON('buscar_cliente_anticipo.php?com=' + $("#id_empleado").val() + "&anio=" + anio + "&mes=" + mes, function (data) {
        var tama = data.length;
        console.log(tama + "bbb3");
        if (tama != '0') {
            for (var i = 0; i < tama; i = i + 1) {
                $("#anticipos_consumos").val(data[i]);
            }
            $("#cedula_empleado").focus();
        } else
        {
            $("#anticipos_consumos").val("0.00");
        }
    });



//    } else {
//        $('#messi').prop('selected', true);
//        alertify.error("Buscar nomina1");
//    }
}
function funcion_neto_recibir() {

    if ($("#salario_empleado").val() != "") {
        var var_aportes_personal = parseFloat($("#total_nomina").val());
        var var_anticipos_sueldos = parseFloat($("#total_deduccion").val());

        var val = var_aportes_personal - var_anticipos_sueldos;
        console.log(val);

        var resulente = val.toFixed(2);
        $("#neto_recibir").val(resulente);
//        $("#neto_recibir").focus();
        $("#btnAgregar").focus();
        $("#btnAgregar").select();

    } else
    {
//        alertify.error("BUSCAR NOMINA")
    }
}
function funcion_decimo_cuarto() {
    if ($("#esta_afiliado").val() == "SI" && $("#decimo_si_no").val() == "SI" && $("#sueldo_percivido").val() != "0.00") {

        if ($("#sueldo_percivido").val() == "") {
            $("#sueldo_percivido").val("0.00");
        }

        if ($("#cedula_empleado").val() != "") {
            var var_sueldo_percivido = parseFloat($("#sueldo_basico").val());
            var val = var_sueldo_percivido / 12;
            var resulente = val / 30;
            $("#cuarto_sueldo").val((resulente * $("#dias_trabajados").val()).toFixed(2));
        }

    } else if ($("#esta_afiliado").val() == "SI" && $("#decimo_si_no").val() == "NO" && $("#sueldo_percivido").val() != "0.00") {
        $("#cuarto_sueldo").val("0.00");
    }
}
function funcion_fondo_reserva() {
    if ($("#fondo_reserva").val() == "SI" && $("#sueldo_percivido").val() != "0.00") {
        if ($("#sueldo_percivido").val() == "") {
            $("#sueldo_percivido").val("0.00");
        }
        if ($("#horas_extras").val() == "") {
            $("#horas_extras").val("0.00");
        }
        if ($("#cedula_empleado").val() != "") {
            var var_sueldo_percivido = parseFloat($("#sueldo_percivido").val());
            var var_horas_extras = parseFloat($("#horas_extras").val());
            var var_otros_ingresos = parseFloat($("#otros_ingresos").val());
            var val = (var_sueldo_percivido + var_horas_extras + var_otros_ingresos);
            var resulente = (val * 8.33) / 100;
            $("#fondos_recerva").val(resulente.toFixed(2));
        }
    } else
    {
//        alertify.error("DEBE CALCULAR EL APORTE INDIVIDUAL");
    }

}
function funcion_decimo_tercero() {
    if ($("#esta_afiliado").val() == "SI" && $("#decimo_si_no").val() == "SI" && $("#sueldo_percivido").val() != "0.00") {
        if ($("#sueldo_percivido").val() == "") {
            $("#sueldo_percivido").val("0.00");
        }
        if ($("#horas_extras").val() == "") {
            $("#horas_extras").val("0.00");
        }
        if ($("#cedula_empleado").val() != "") {
            var var_sueldo_percivido = parseFloat($("#sueldo_percivido").val());
            var var_horas_extras = parseFloat($("#horas_extras").val());
            var val = (var_sueldo_percivido + var_horas_extras) / 12;
            var resulente = val.toFixed(2);
            $("#tercer_sueldo").val(resulente);
        }
    } else if ($("#esta_afiliado").val() == "SI" && $("#decimo_si_no").val() == "NO" && $("#sueldo_percivido").val() != "0.00") {
        {

            $("#tercer_sueldo").val("0.00");

        }

    }
}
function funcion_total_deduccion() {
    if ($("#esta_afiliado").val() == "SI") {

        if ($("#faltantes_caja").val() == "") {
            $("#faltantes_caja").val("0.00");
        }
        if ($("#prestamos_qui_iess").val() == "") {
            $("#prestamos_qui_iess").val("0.00");
        }

        if ($("#credito_personal").val() == "") {
            $("#credito_personal").val("0.00");
        }
        if ($("#anticipos_consumos").val() == "") {
            $("#anticipos_consumos").val("0.00");
        }
        if ($("#multas").val() == "") {
            $("#multas").val("0.00");
        }
        if ($("#otros_descuentos").val() == "") {
            $("#otros_descuentos").val("0.00");
        }

        if ($("#cedula_empleado").val() != "") {

            var var_aportes_personal = parseFloat($("#aporte_individual").val());
            var var_anticipos_sueldos = parseFloat($("#anticipos_consumos").val());
//            var var_faltante_caja = parseFloat($("#faltantes_caja").val());
//            var var_multa = parseFloat($("#multas").val());
            var var_prestamos_iess = parseFloat($("#prestamos_qui_iess").val());
            var var_otros_descuentos = parseFloat($("#otros_descuentos").val());
            var var_credito_personal = parseFloat($("#credito_personal").val());

            var val = var_aportes_personal + var_anticipos_sueldos + var_prestamos_iess + var_otros_descuentos + var_credito_personal;

            var resulente = val.toFixed(2);
            $("#total_deduccion").val(resulente);
        }

    } else {
        if ($("#faltantes_caja").val() == "") {
            $("#faltantes_caja").val("0.00");
        }
        if ($("#prestamos_qui_iess").val() == "") {
            $("#prestamos_qui_iess").val("0.00");
        }
        if ($("#credito_personal").val() == "") {
            $("#credito_personal").val("0.00");
        }
        if ($("#anticipos_consumos").val() == "") {
            $("#anticipos_consumos").val("0.00");
        }
        if ($("#multas").val() == "") {
            $("#multas").val("0.00");
        }
        if ($("#otros_descuentos").val() == "") {
            $("#otros_descuentos").val("0.00");
        }

        if ($("#cedula_empleado").val() != "") {


            var var_anticipos_sueldos = parseFloat($("#anticipos_consumos").val());
//            var var_faltante_caja = parseFloat($("#faltantes_caja").val());
//            var var_multa = parseFloat($("#multas").val());
            var var_prestamos_iess = parseFloat($("#prestamos_qui_iess").val());
            var var_otros_descuentos = parseFloat($("#otros_descuentos").val());
            var var_credito_persoanl = parseFloat($("#credito_personal").val());
            var var_credito_personal = parseFloat($("#credito_personal").val());

            var val = var_anticipos_sueldos + var_prestamos_iess + var_otros_descuentos + var_credito_persoanl + var_credito_personal;

            var resulente = val.toFixed(2);
            $("#total_deduccion").val(resulente);
        }

    }

}
function funcion_aporte_individual() {
    var var_salario_empleado = 0;
    if ($("#salario_empleado").val() != "") {
        $.ajax({
            type: "POST",
            url: "xmlBuscarAporte_iess_per.php",
            data: "",
            success: function (data) {
                var val = data;
                console.log("dataas" + val);
                if (val != "") {
                    var_salario_empleado = val;
                    var var_dias_laborados = (parseFloat($("#sueldo_percivido").val()) + parseFloat($("#horas_extras").val()));

                    if ($("#esta_afiliado").val() == 'SI') {
                        var val1 = var_dias_laborados * (var_salario_empleado / 100);
                        console.log(val1);
                        var resulente = val1.toFixed(2);
                        $("#aporte_individual").val(resulente);
                    } else {
                        var_salario_empleado = '0.00';
                        var val2 = var_dias_laborados * (var_salario_empleado / 100);
                        console.log(val2);

                        var resulente = val2.toFixed(2);
                        $("#aporte_individual").val(resulente);
                    }
                }
            }
        });
    } else
    {
        alertify.error("BUSCAR NOMINA")
    }
}
function funcion_total_nomina() {

    if ($("#otros_ingresos").val() == "" || $("#horas_extras").val() == "" || $("#fondos_recerva").val() == "" || $("#tercer_sueldo").val() == "" || $("#cuarto_sueldo").val() == "") {
        if ($("#otros_ingresos").val() == "")
        {
            $("#otros_ingresos").val('0.00');
        }
        if ($("#horas_extras").val() == "")
        {
            $("#horas_extras").val('0.00');
        }
        if ($("#fondos_recerva").val() == "")
        {
            $("#fondos_recerva").val('0.00');
        }
        if ($("#tercer_sueldo").val() == "")
        {
            $("#tercer_sueldo").val('0.00');
        }
        if ($("#cuarto_sueldo").val() == "")
        {
            $("#cuarto_sueldo").val('0.00');
        }


    } else {
        if ($("#esta_afiliado").val() == "SI" && $("#decimo_si_no").val() == "SI" && $("#fondo_reserva").val() == "SI" && $("#aporte_patronal").val() != "0.00" && $("#select_mes").val() != "0") {
            if ($("#salario_empleado").val() != "") {
                var var_sueldo_presibido = parseFloat($("#sueldo_percivido").val());
                var var_horas_extras = parseFloat($("#horas_extras").val());
                var var_otros = parseFloat($("#otros_ingresos").val());
                var var_fondos_recerva = parseFloat($("#fondos_recerva").val());
//                var var_aporte_patronal = parseFloat($("#aporte_patronal").val());
                var var_tercer_sueldo = parseFloat($("#tercer_sueldo").val());
                var var_cuarto_sueldo = parseFloat($("#cuarto_sueldo").val());
                //SI esta_afiliado SI  decimo_rol MENSUAL Y fondos_acu_mensual MENSUAL
                if ($("#decimo_rol").val() == "mensual" && $("#fondos_acu_mensual").val() == "fondos_mensual") {
                    var val = var_sueldo_presibido + var_horas_extras + var_otros + var_fondos_recerva + var_tercer_sueldo + var_cuarto_sueldo;
                } else if ($("#decimo_rol").val() == "acumulado" && $("#fondos_acu_mensual").val() == "fondos_acumulado") {
                    var val = var_sueldo_presibido + var_horas_extras + var_otros;
                } else if ($("#decimo_rol").val() == "mensual" && $("#fondos_acu_mensual").val() == "fondos_acumulado") {
                    var val = var_sueldo_presibido + var_horas_extras + var_otros + var_tercer_sueldo + var_cuarto_sueldo;
                } else if ($("#decimo_rol").val() == "acumulado" && $("#fondos_acu_mensual").val() == "fondos_mensual") {
                    var val = var_sueldo_presibido + var_horas_extras + var_otros + var_fondos_recerva;
                }
                console.log("j" + val);
                var resulente = val.toFixed(2);
                $("#total_nomina").val(resulente);
            }
        } else if ($("#esta_afiliado").val() == "NO" && $("#fondo_reserva").val() == "NO" && $("#select_mes").val() != "0") {

            var var_sueldo_presibido = parseFloat($("#sueldo_percivido").val());
            var var_horas_extras = parseFloat($("#horas_extras").val());
            var var_otros = parseFloat($("#otros_ingresos").val());
            //SI esta_afiliado SI  decimo_rol MENSUAL Y fondos_acu_mensual MENSUAL
            console.log(var_otros + "var_otros");
            var val = var_sueldo_presibido + var_horas_extras + var_otros;
            var resulente = val.toFixed(2);
            $("#total_nomina").val(resulente);
        } else if ($("#esta_afiliado").val() == "SI" && $("#decimo_si_no").val() == "SI" && $("#fondo_reserva").val() == "NO" && $("#select_mes").val() != "0") {
            var var_sueldo_presibido = parseFloat($("#sueldo_percivido").val());
            var var_horas_extras = parseFloat($("#horas_extras").val());
            var var_otros = parseFloat($("#otros_ingresos").val());

//          var var_aporte_patronal = parseFloat($("#aporte_patronal").val());
            var var_tercer_sueldo = parseFloat($("#tercer_sueldo").val());
            var var_cuarto_sueldo = parseFloat($("#cuarto_sueldo").val());

            //SI esta_afiliado SI  decimo_rol MENSUAL Y fondos_acu_mensual MENSUAL
            if ($("#decimo_rol").val() == "mensual") {
                var val = var_sueldo_presibido + var_horas_extras + var_otros + var_tercer_sueldo + var_cuarto_sueldo;
            } else if ($("#decimo_rol").val() == "acumulado") {
                var val = var_sueldo_presibido + var_horas_extras + var_otros;
            }
            var resulente = val.toFixed(2);
            $("#total_nomina").val(resulente);

        } else if ($("#esta_afiliado").val() == "SI" && $("#decimo_si_no").val() == "NO" && $("#fondo_reserva").val() == "NO" && $("#select_mes").val() != "0") {
            var var_sueldo_presibido = parseFloat($("#sueldo_percivido").val());
            var var_horas_extras = parseFloat($("#horas_extras").val());
            var var_otros = parseFloat($("#otros_ingresos").val());

//          var var_aporte_patronal = parseFloat($("#aporte_patronal").val());
//            var var_tercer_sueldo = parseFloat($("#tercer_sueldo").val());
//            var var_cuarto_sueldo = parseFloat($("#cuarto_sueldo").val());

            //SI esta_afiliado SI  decimo_rol MENSUAL Y fondos_acu_mensual MENSUAL

            var val = var_sueldo_presibido + var_horas_extras + var_otros;

            var resulente = val.toFixed(2);
            $("#total_nomina").val(resulente);

        }
    }
}
function totales() {

    ///////////////////////////////////////////////////

    var sueldo_percividot = 0;
    var horas_extrast = 0;
    var otros_ingresost = 0;
    var fondos_recervat = 0;
    var aporte_patronalt = 0;
    var tercer_sueldot = 0;
    var cuarto_sueldot = 0;
    var total_nominat = 0;
    var aporte_individualt = 0;
    var anticipos_consumost = 0;
    var faltantes_cajat = 0;
    var multast = 0;
    var prestamos_qui_iesst = 0;
    var credito_personalt = 0;
    var otros_descuentost = 0;
    var total_deducciont = 0;
    var neto_recibirt = 0;

    var total_sueldo_percividot = 0;
    var total_horas_extrast = 0;
    var total_otros_ingresost = 0;
    var total_fondos_recervat = 0;
    var total_aporte_patronalt = 0;
    var total_tercer_sueldot = 0;
    var total_cuarto_sueldot = 0;
    var total_total_nominat = 0;
    var total_aporte_individualt = 0;
    var total_anticipos_consumost = 0;
    var total_faltantes_cajat = 0;
    var total_multast = 0;
    var total_prestamos_qui_iesst = 0;
    var total_credito_personalt = 0;
    var total_otros_descuentost = 0;
    var total_total_deducciont = 0;
    var total_neto_recibirt = 0;
    var repe = 0;

    var fil = jQuery("#list_rol").jqGrid("getRowData");
    for (var t = 0; t < fil.length; t++) {
        var dd = fil[t];
        sueldo_percividot = dd['sueldo_percivido'];
        console.log(sueldo_percividot);
        total_sueldo_percividot = parseFloat(total_sueldo_percividot) + parseFloat(sueldo_percividot);

        aporte_patronalt = dd['aporte_patronal'];
        total_aporte_patronalt = parseFloat(total_aporte_patronalt) + parseFloat(aporte_patronalt);

        horas_extrast = dd['horas_extras'];
        total_horas_extrast = parseFloat(total_horas_extrast) + parseFloat(horas_extrast);

        tercer_sueldot = dd['tercer_sueldo'];
        total_tercer_sueldot = parseFloat(total_tercer_sueldot) + parseFloat(tercer_sueldot);

        otros_ingresost = dd['otros_ingresos'];
        total_otros_ingresost = parseFloat(total_otros_ingresost) + parseFloat(otros_ingresost);

        cuarto_sueldot = dd['cuarto_sueldo'];
        total_cuarto_sueldot = parseFloat(total_cuarto_sueldot) + parseFloat(cuarto_sueldot);

        fondos_recervat = dd['fondos_recerva'];
        total_fondos_recervat = parseFloat(total_fondos_recervat) + parseFloat(fondos_recervat);

        total_nominat = dd['total_nomina'];
        total_total_nominat = parseFloat(total_total_nominat) + parseFloat(total_nominat);

        ////////////////////////////////////////

        aporte_individualt = dd['aporte_individual'];
        total_aporte_individualt = parseFloat(total_aporte_individualt) + parseFloat(aporte_individualt);

        prestamos_qui_iesst = dd['prestamos_qui_iess'];
        total_prestamos_qui_iesst = parseFloat(total_prestamos_qui_iesst) + parseFloat(prestamos_qui_iesst);

        neto_recibirt = dd['neto_recibir'];
        total_neto_recibirt = parseFloat(total_neto_recibirt) + parseFloat(neto_recibirt);

        anticipos_consumost = dd['anticipos_consumos'];
        total_anticipos_consumost = parseFloat(total_anticipos_consumost) + parseFloat(anticipos_consumost);

        credito_personalt = dd['credito_personal'];
        total_credito_personalt = parseFloat(total_credito_personalt) + parseFloat(credito_personalt);

        faltantes_cajat = dd['faltantes_caja'];
        total_faltantes_cajat = parseFloat(total_faltantes_cajat) + parseFloat(faltantes_cajat);

        otros_descuentost = dd['otros_descuentos'];
        total_otros_descuentost = parseFloat(total_otros_descuentost) + parseFloat(otros_descuentost);

        total_deducciont = dd['total_deduccion'];
        total_total_deducciont = parseFloat(total_total_deducciont) + parseFloat(total_deducciont);

    }

    $("#sueldo_percividot").val(total_sueldo_percividot.toFixed(4));
    $("#aporte_patronalt").val(total_aporte_patronalt.toFixed(4));
    $("#horas_extrast").val(total_horas_extrast.toFixed(4));
    $("#tercer_sueldot").val(total_tercer_sueldot.toFixed(4));
    $("#otros_ingresost").val(total_otros_ingresost.toFixed(4));
    $("#cuarto_sueldot").val(total_cuarto_sueldot.toFixed(4));
    $("#fondos_recervat").val(total_fondos_recervat.toFixed(2));
    $("#total_nominat").val(total_total_nominat.toFixed(4));


    $("#aporte_individualt").val(total_aporte_individualt.toFixed(4));
    $("#prestamos_qui_iesst").val(total_prestamos_qui_iesst.toFixed(4));
    $("#neto_recibirt").val(total_neto_recibirt.toFixed(4));
    $("#anticipos_consumost").val(total_anticipos_consumost.toFixed(4));
    $("#credito_personalt").val(total_credito_personalt.toFixed(4));
    $("#faltantes_cajat").val(total_faltantes_cajat.toFixed(2));
    $("#otros_descuentost").val(total_otros_descuentost.toFixed(4));
    $("#multast").val(total_multast.toFixed(4));
    $("#total_deducciont").val(total_total_deducciont.toFixed(4));
//    $("#cedula_empleado").focus();

}
function aporte_patronal() {
    if ($("#horas_extras").val() == "") {
        $("#horas_extras").val('0.00');
        console.log($("#salario_empleado").val());



    } else {
        if ($("#salario_empleado").val() != "") {
            $.ajax({
                type: "POST",
                url: "xmlBuscarAporte_iess_patro.php",
                data: "",
                success: function (data) {
                    var val = data;
                    if (val != "") {
                        var var_salario_empleado = val;
                        var var_aporte_patronal = (parseFloat($("#sueldo_percivido").val()) + parseFloat($("#horas_extras").val()) + parseFloat($("#otros_ingresos").val()));
                        if ($("#esta_afiliado").val() == 'SI') {
                            var val3 = var_aporte_patronal * (var_salario_empleado / 100);
                            var resulente = val3.toFixed(2);
                            $("#aporte_patronal").val(resulente);
                        } else {

                            $("#aporte_patronal").val("0.00");
                        }
                    }
                }
            });

        } else
        {
//        alertify.error("SUELDO PERCIBIDO1: Ya tiene valor")
        }
    }
}

function sueldo_percibido() {
    if ($("#salario_empleado").val() != "" && $("#dias_trabajados").val() != "") {
        var var_dias_laborados = parseFloat($("#dias_trabajados").val());
        var var_salario_empleado = parseFloat($("#salario_empleado").val());
        var val = var_salario_empleado / 30;
        var entero = val;
        var resulente = entero * var_dias_laborados;
        var resulente = resulente.toFixed(2);
        $("#sueldo_percivido").val(resulente);
    } else
    {
        alertify.error("Buscar Nomina3")
    }

}

function modificarRegistro(e) {
    if ($("#txtClienteId").val() === "") {
        $("#txtCliente").focus();
        alertify.error("Ingrese un registro");
    } else {
        if ($("#categoria").val() === "") {
            $("#categoria").focus();
            alertify.error("Ingrese el tipo de equipo");
        } else {
            if ($("#txtModelo").val() === "") {
                $("#txtModelo").focus();
                alertify.error("Ingrese un modelo");
            } else {
                if ($("#txtSerie").val() === "") {
                    $("#txtSerie").focus();
                    alertify.error("Ingrese la serie");
                } else {
                    if ($("#marca").val() === "") {
                        $("#marca").focus();
                        alertify.error("Ingrese un marca");
                    } else {
                        if ($("#colores").val() === "") {
                            $("#colores").focus();
                            alertify.error("Ingrese una color");
                        } else {
                            $.ajax({
                                type: "POST",
                                url: "procesosRegistroEquipo.php",
                                data: $("#registro_form").serialize() + "&tipo=" + "m",
                                success: function (data) {
                                    var val = data;
                                    if (val == 0) {
                                        alertify.alert("Datos Modificados", function () {
                                            $("#txtRegistro").val("");
                                            location.reload();
                                        });
                                    }
                                    if (val == 1) {
                                        alertify.alert("Error.. durante el proceso");
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
        data: "comprobante=" + $("#txtRegistro").val() + "&tabla=" + "registro_equipo" + "&id_tabla=" + "id_registro" + "&tipo=" + 1,
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#txtRegistro").val(val);
                var valor = $("#txtRegistro").val();

                ///////////////////llamar proforma flechas primera parte/////
                $("#btnGuardar").attr("disabled", true);

                $.getJSON('retornar_registro_equipo.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 14) {
                            $("#txtClienteId").val(data[i]);
                            $("#txtCliente").val(data[i + 1]);
                            $("#categoria").val(data[i + 2]);
                            $("#txtIngreso").val(data[i + 4]);
                            $("#txtSalida").val(data[i + 5]);
                            $("#txtModelo").val(data[i + 6]);
                            $("#txtSerie").val(data[i + 7]);
                            $("#marca").val(data[i + 8]);
                            $("#colores").val(data[i + 10]);
                            $("#txtObservaciones").val(data[i + 12]);
                            $("#txtAccesorios").val(data[i + 13]);
                        }
                    }
                });
            } else {
                alertify.alert("No hay mas registros posteriores!!");
            }
        }
    });
}

function flecha_siguiente() {
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: "comprobante=" + $("#txtRegistro").val() + "&tabla=" + "registro_equipo" + "&id_tabla=" + "id_registro" + "&tipo=" + 2,
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#txtRegistro").val(val);
                var valor = $("#txtRegistro").val();

                ///////////////////llamar proforma flechas primera parte/////
                $("#btnGuardar").attr("disabled", true);

                $.getJSON('retornar_registro_equipo.php?com=' + valor, function (data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 14) {
                            $("#txtClienteId").val(data[i]);
                            $("#txtCliente").val(data[i + 1]);
                            $("#categoria").val(data[i + 2]);
                            $("#txtIngreso").val(data[i + 4]);
                            $("#txtSalida").val(data[i + 5]);
                            $("#txtModelo").val(data[i + 6]);
                            $("#txtSerie").val(data[i + 7]);
                            $("#marca").val(data[i + 8]);
                            $("#colores").val(data[i + 10]);
                            $("#txtObservaciones").val(data[i + 12]);
                            $("#txtAccesorios").val(data[i + 13]);
                        }
                    }
                });
            } else {
                alertify.alert("No hay mas registros superiores!!");
            }
        }
    });
}

function limpiarDatos() {
    $("input").val("");
    $("textarea").val("");
}

function abrirDialogo(e) {
    $("#buscar_rol_pagos").dialog("open");
    $("#list2").trigger("reloadGrid");
}

function limpiar_campo() {
    if ($("#txtCliente").val() === "") {
        $("#txtClienteId").val("");
    }
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
function abrirCuenta() {
    $("#cuentas").dialog("open");
}
function inicio() {
    $("#cedula_empleado").validCampoFranz("0123456789");
    $("#cedula_empleado").keyup(function () {
        if ($("#select_mes").val() == "0") {
            alertify.error("DEBE SELECCIONAR EL MES");
            $("#select_mes").focus();
        }
    });

    $("#select_mes").focus();
    $("#cuentas").dialog(dialogo_cuenta);
    $("#btnCuenta").click(function (e) {
        e.preventDefault();
    });

    $("#btnCuenta").on("click", abrirCuenta);
    $("#forma_pago").on("change", function () {
        if ($("#forma_pago").val() == "TRANSFERENCIA") {
            $("#cuenta_contable").attr("disabled", false);
            $("#btnCuenta").attr("disabled", false);
            $("#cuenta_contable").val("");
            $("#idCuenta").val("");
            $("#cheque_tarjeta").attr("disabled", false);

        } else if ($("#forma_pago").val() == "CONTADO") {
            $("#cuenta_contable").attr("disabled", true);
            $("#btnCuenta").attr("disabled", true);
            $("#cuenta_contable").val("");
            $("#idCuenta").val("");
            $("#cheque_tarjeta").attr("disabled", true);
            $("#banco").attr("disabled", true);

        } else if ($("#forma_pago").val() == "CHEQUE") {
            $("#cheque_tarjeta").attr("disabled", false);
            $("#cuenta_contable").attr("disabled", false);
            $("#btnCuenta").attr("disabled", false);
            $("#cuenta_contable").val("");
            $("#idCuenta").val("");
        } else if ($("#forma_pago").val() == "CXP") {

            $("#cuenta_contable").attr("disabled", true);
            $("#btnCuenta").attr("disabled", true);
            $("#cheque_tarjeta").attr("disabled", true);
            $("#banco").attr("disabled", false);
            $("#cuenta_contable").val("");
            $("#idCuenta").val("");
        }
    })

//    totales();
//    $('#list_rol input[type=checkbox]').prop("checked", true).trigger("change");

//   $("#list_rol").change(function () {
//        $('#list_rol input[type=checkbox]').prop("checked", true).trigger("change");
//    });

    /////////////////////////////////////////////////////////////////////////
    $("#otros_ingresos").change(function () {
        funcion_total_nomina();
    });
    $("#aporte_patronal").change(function () {
        aporte_patronal();
    });
    $("#sueldo_percivido").change(function () {
        sueldo_percibido();
    });
    $("#total_nomina").change(function () {
        funcion_total_nomina();
    });
    $("#neto_recibir").change(function () {
        funcion_neto_recibir();
    });

    $("#total_deduccion").change(function () {
        funcion_total_deduccion();
    });


    $("#tercer_sueldo").change(function () {
        funcion_decimo_tercero();
    });
    $("#cuarto_sueldo").change(function () {
        funcion_decimo_cuarto();
    });
    ///////////////////////////////////////////////////////////////////////////
    $("#aporte_patronal").mousemove(function () {
        aporte_patronal();
    });
    $("#total_nomina").mousemove(function () {
        funcion_total_nomina();
    });
    $("#neto_recibir").mousemove(function () {
        funcion_neto_recibir();
    });
    $("#total_deduccion").mousemove(function () {
        funcion_total_deduccion();
    });
    $("#tercer_sueldo").mousemove(function () {
        funcion_decimo_tercero();
    });
    $("#cuarto_sueldo").mousemove(function () {
        funcion_decimo_cuarto();
    });
    //////////////////////////////////////////////////////////////////////////////////////////
    $("#otros_ingresos").select(function () {
        $("#otros_ingresos").val("");
    });



    $("#fondos_recerva").select(function () {
        $("#fondos_recerva").val("");
    });
    $("#faltantes_caja").select(function () {
        $("#faltantes_caja").val("");
    });
//    $("#otros_descuentos").select(function () {
//        $("#otros_descuentos").val("");
//    });




    /////////////////////////////////////
//    $("#dias_trabajados").keyup(function () {
//        if ($("#salario_empleado").val() == "") {
//            $("#dias_trabajados").val("");
//            $("#salario_empleado").focus();
//            alertify.error("Error... ");
//        } else {
//            if ($("#dias_trabajados").val() == "") {
//                $("#sueldo_percivido").val("");
//            }
//        }
//    });
//    

    inicializarSelectAnioFuncio();
    /////////////cambiar idioma///////
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

    //////////////////////////////////////
    alertify.set({delay: 1000});
    $("#txtCliente").focus();

    $("#btnGuardar").click(function (e) {
        e.preventDefault();
    });

    $("#btnBuscar").click(function (e) {
        e.preventDefault();
    });

    $("#btnModificar").click(function (e) {
        e.preventDefault();
    });

    $("#btnNuevo").click(function (e) {
        location.reload();
    });

    $("#btnAtras").click(function (e) {
        e.preventDefault();
    });
    $("#btnAdelante").click(function (e) {
        e.preventDefault();
    });
    $("#btnOmpriR").click(function (e) {
        e.preventDefault();
    });
    $("#btnImprimir").click(function () {
        $.ajax({
            type: "POST",
            url: "../../procesos/validacion.php",
            data: "comprobante=" + $("#txtRegistro").val() + "&tabla=" + "registro_equipo" + "&id_tabla=" + "id_registro" + "&tipo=" + 1,
            success: function (data) {
                var val = data;
                if (val != "") {
                    window.open("../../reportes/reporteRegistro.php?id=" + $("#txtRegistro").val());
                } else {
                    alertify.alert("Ingreso no creado!!");
                }
            }
        });
    });


    $("#btnAgregar").click(function (e) {
        e.preventDefault();
    });
    $("#btnBuscar").on("click", abrirDialogo);
    $("#btnAgregar").on("click", entrar3);
    $("#btnGuardar").on("click", guardarRegistro);
    $("#btnModificar").on('click', modificarRegistro);
    $("#btnAtras").on("click", flecha_atras);
    $("#btnAdelante").on("click", flecha_siguiente);
    $("#btnNuevo").on('click', limpiarDatos);

    $("#txtCliente").on("keyup", limpiar_campo);

    $("#dias_trabajados").on("keypress", enterpvpmi);
    $("#sueldo_percivido").on("keypress", enterpvpmi);
    $("#sueldo_percivido").on("keypress", enteraportepatronal);



    $("#otros_ingresos").on("keypress", total_nomina);
    $("#aporte_individual").on("keypress", aporte_individual);
    $("#total_nomina").on("keypress", funcion_total_nomina);
    $("#total_deduccion").on("keypress", total_deduccion);

    $("#tercer_sueldo").on("keypress", total_decimo_tercero);
    $("#cuarto_sueldo").on("keypress", total_decimo_cuarto);
    $("#fondos_recerva").on("keypress", total_fondo_reserva);

    $("#neto_recibir").on("keypress", neto_recibir);
    $("#multas").on("keypress", cargar_multa);
    $("#anticipos_consumos").on("keypress", anticipos_consumoskey);
    $("#anticipos_consumos").on("keypress", cargar_anticipo_anti);

//    $("#select_mes").on("change", funcion_cargar_anticipos);
//    $("#select_mes").on("change", cargar_mes_guardado);
    $("#buscar_rol_pagos").dialog(dialogo2);
    $("#sueldo_percivido").validCampoFranz("0123456789.");

    $("#aporte_patronal").validCampoFranz("0123456789.");

    $("#aporte_patronal").validCampoFranz("0123456789.");

    $("#horas_extras").validCampoFranz("0123456789.");

    $("#tercer_sueldo").validCampoFranz("0123456789.");

    $("#total_nomina").validCampoFranz("0123456789.");

    $("#fondos_recerva").validCampoFranz("0123456789.");

    $("#cuarto_sueldo").validCampoFranz("0123456789.");

    $("#aporte_individual").validCampoFranz("0123456789.");

    $("#anticipos_consumos").validCampoFranz("0123456789.");

    $("#otros_descuentos").validCampoFranz("0123456789.");

    $("#prestamos_qui_iess").validCampoFranz("0123456789.");

    $("#total_deduccion").validCampoFranz("0123456789.");

    $("#credito_personal").validCampoFranz("0123456789.");

    $("#multas").validCampoFranz("0123456789.");

    $("#neto_recibir").validCampoFranz("0123456789.");

    $("#faltantes_caja").validCampoFranz("0123456789.");
    $("#btnOmpriR").on("click", abrir_pdf_unido);

    $("#neto_recibir").on("keypress", enter1);

    $("#cedula_empleado").on("keypress", cedula_empleado);
    $("#dias_trabajados").on("keypress", dias_trabajados);
    $("#sueldo_percivido").on("keypress", sueldo_percivido);
    $("#horas_extras").on("keypress", horas_extras);
    $("#horas_extras").on("keypress", horas_extras_fun);
    $("#aporte_patronal").on("keypress", aporte_patronalkey);
    $("#fondos_recerva").on("keypress", fondos_recerva);
    $("#fondos_recerva").on("keypress", funcion_decimo_tercero);
    $("#tercer_sueldo").on("keypress", tercer_sueldo);
    $("#fondos_recerva").on("keypress", funcion_decimo_cuarto);
    $("#cuarto_sueldo").on("keypress", cuarto_sueldo);
    $("#aporte_patronal").on("keypress", funcion_fondo_reserva);


    $("#total_nomina").on("keypress", total_nominakey);
    $("#aporte_individual").on("keypress", aporte_individualkey);
    $("#prestamos_qui_iess").on("keypress", prestamos_qui_iess);
    $("#otros_ingresos").on("keypress", funcion_otros_ingresos);
    $("#credito_personal").on("keypress", credito_personal);
//    $("#anticipos_consumos").on("keypress", anticipos_consumos);
    $("#faltantes_caja").on("keypress", faltantes_caja);




    $("#multas").on("keypress", multas);
    $("#otros_descuentos").on("keypress", otros_descuentos);
    $("#total_deduccion").on("keypress", total_deduccionkey);

//   $("#neto_recibir").on("keypress", entrar3);


//    $("#nombres_empleado").on("keypress", enter);

//    $("#sueldo_percivido").on("keypress", enter);
//    $("#horas_extras").on("keypress", enter);

//    $("#otros_ingresos").on("keypress", enter);
//    $("#total_nomina").on("keypress", enter);
//    $("#aporte_individual").on("keypress", enter);
//    $("#aporte_patronal").on("keypress", enter);
    $("#aporte_patronal").on("keypress", aporte_patronal);
    $("#dias_trabajados").on("keyup", limpiar_campo_todo);
//    $("#total_deduccion").on("keypress", enter);

    $("#nombres_empleado").autocomplete({
        source: "buscar_cliente_nombre.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#nombres_empleado").val(ui.item.value);
            $("#id_empleado").val(ui.item.id_cliente);
            $("#cedula_empleado").val(ui.item.identificacion);
            $("#direccion_empleado").val(ui.item.direccion_cliente);
            $("#cargo_empleado").val(ui.item.cargo_empleado);
            $("#salario_empleado").val(ui.item.salario_empleado);
            $("#dias_trabajados").val(ui.item.dias_trabajados);
            $("#esta_afiliado").val(ui.item.esta_afiliado);

            return false;
        },
        select: function (event, ui) {
            $("#nombres_empleado").val(ui.item.value);
            $("#id_empleado").val(ui.item.id_cliente);
            $("#cedula_empleado").val(ui.item.identificacion);
            $("#direccion_empleado").val(ui.item.direccion_cliente);
            $("#cargo_empleado").val(ui.item.cargo_empleado);
            $("#salario_empleado").val(ui.item.salario_empleado);
            $("#dias_trabajados").val(ui.item.dias_trabajados);


            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
    };


    //////////////////BUSCADORES////////////////////
    $("#cedula_empleado").autocomplete({
        source: "buscar_cliente.php",
        minLength: 1,
//        focus: function (event, ui) {
//            $("#cedula_empleado").val(ui.item.value);
//            $("#id_empleado").val(ui.item.id_cliente);
//            $("#nombres_empleado").val(ui.item.nombre_cliente);
//            $("#direccion_empleado").val(ui.item.direccion_cliente);
//            $("#cargo_empleado").val(ui.item.cargo_empleado);
//            $("#salario_empleado").val(ui.item.salario_empleado);
//            $("#dias_trabajados").val(ui.item.dias_trabajados);
//            $("#esta_afiliado").val(ui.item.esta_afiliado);
//            $("#decimo_rol").val(ui.item.decimo);
//            funcion_cargar_anticipos();
//            cargar_mes_guardado();
//            $("#dias_trabajados").focus();
//            return false;
//        },
        select: function (event, ui) {
            $("#cedula_empleado").val(ui.item.value);
            $("#id_empleado").val(ui.item.id_cliente);
            $("#nombres_empleado").val(ui.item.nombre_cliente);
            $("#direccion_empleado").val(ui.item.direccion_cliente);
            $("#cargo_empleado").val(ui.item.cargo_empleado);
            $("#salario_empleado").val(ui.item.salario_empleado);
            $("#dias_trabajados").val(ui.item.dias_trabajados);
            $("#esta_afiliado").val(ui.item.esta_afiliado);
            $("#decimo_rol").val(ui.item.decimo);
            $("#decimo_si_no").val(ui.item.decimo_si_no);
            $("#fondo_reserva").val(ui.item.tiene_fondos);
            $("#fondos_acu_mensual").val(ui.item.acumula_fondos);
            $("#fecha_ingreso").val(ui.item.fecha_ingreso);
            funcion_cargar_anticipos();
            cargar_mes_guardado();
            $("#dias_trabajados").focus();
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
    };

    // $("#txtTipoEquipo").autocomplete({
    //     source: "busquedaEquipo.php",
    //     minLength: 1,
    //     focus: function(event, ui) {
    //     $("#txtTipoEquipo").val(ui.item.value);
    //     $("#txtTipoEquipoId").val(ui.item.label);
    //     return false;
    //     },
    //     select: function(event, ui) {
    //     $("#txtTipoEquipo").val(ui.item.value);
    //     $("#txtTipoEquipoId").val(ui.item.label);
    //     return false;
    //     }
    //     }).data("ui-autocomplete")._renderItem = function(ul, item) {
    //     return $("<li>")
    //     .append("<a>" + item.value + "</a>")
    //     .appendTo(ul);
    // };

    // $("#txtColor").autocomplete({
    //     source: "busquedaColor.php",
    //     minLength: 1,
    //     focus: function(event, ui) {
    //     $("#txtColor").val(ui.item.value);
    //     $("#txtColorId").val(ui.item.label);
    //     return false;
    //     },
    //     select: function(event, ui) {
    //     $("#txtColor").val(ui.item.value);
    //     $("#txtColorId").val(ui.item.label);
    //     return false;
    //     }
    //     }).data("ui-autocomplete")._renderItem = function(ul, item) {
    //     return $("<li>")
    //     .append("<a>" + item.value + "</a>")
    //     .appendTo(ul);
    // };

    // $("#txtMarca").autocomplete({
    //     source: "busquedaMarca.php",
    //     minLength: 1,
    //     focus: function(event, ui) {
    //     $("#txtMarca").val(ui.item.value);
    //     $("#txtMarcaId").val(ui.item.label);
    //     return false;
    //     },
    //     select: function(event, ui) {
    //     $("#txtMarca").val(ui.item.value);
    //     $("#txtMarcaId").val(ui.item.label);
    //     return false;
    //     }
    //     }).data("ui-autocomplete")._renderItem = function(ul, item) {
    //     return $("<li>")
    //     .append("<a>" + item.value + "</a>")
    //     .appendTo(ul);
    // };

    ////////
    $.ajax({
        type: "POST",
        url: "contadorRegistro.php",
        success: function (data) {
            var val = data;
            $("#txtRegistro").val(val);
        }
    });

    //cargar fechas/////
    $("#fecha_registro").datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');
    jQuery("#list_rol").jqGrid({
        datatype: "local",
        colNames: ['', '', 'Nro.', 'id_emple', 'Identificacion', 'Nombres', 'Cargo', 'Dias Laborados', 'Sueldo/Contrato', 'Sueldo Percibido', 'Horas Extras', 'Otros', 'Fondo Reserva', 'Aporte Patronal', 'XIII Sueldo', 'XIV Sueldo', 'TOTAL NOMINA', 'Aporte Personal', 'Anticipos Sueldos', 'Faltante Caja', 'Multas', 'Prestamos Iess', 'Credito Personal', 'Otros Descuentos', 'Total Deduccion', 'NETO RECIBIR'],
        colModel: [
            {name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: {keys: false, delbutton: true, editbutton: false}},
            {
                name: 'imprimir',
                index: 'imprimir',
                editable: false,
                hidden: false,
                search: false,
                frozen: true,
                editrules: {
                    required: true
                },
                align: 'center',
                width: '150px'
            },
            {name: 'id_rol_pagos', index: 'id_rol_pagos', search: false, editable: false, hidden: true, editrules: {edithidden: false}, align: 'center', frozen: true, width: 80},
            {name: 'id_empleado', index: 'id_empleado', search: false, editable: false, hidden: true, editrules: {edithidden: false}, align: 'center', frozen: true, width: 80},

            {name: 'cedula_empleado', index: 'cedula_empleado', search: false, editable: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 250},
            {name: 'nombres_empleado', index: 'nombres_empleado', search: false, editable: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 700},
            {name: 'cargo_empleado', index: 'cargo_empleado', search: false, editable: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 250},
            {name: 'dias_trabajados', index: 'dias_trabajados', search: false, editable: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 150},
            {name: 'salario_empleado', index: 'salario_empleado', search: false, editable: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 150},

            {name: 'sueldo_percivido', index: 'sueldo_percivido', search: false, editable: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 150},
            {name: 'horas_extras', index: 'horas_extras', search: false, editable: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 150},
            {name: 'otros_ingresos', index: 'otros_ingresos', search: false, editable: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 150},
            {name: 'fondos_recerva', index: 'fondos_recerva', search: false, editable: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 150},
            {name: 'aporte_patronal', index: 'aporte_patronal', search: false, editable: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 150},
            {name: 'tercer_sueldo', index: 'tercer_sueldo', search: false, editable: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 150},
            {name: 'cuarto_sueldo', index: 'cuarto_sueldo', search: false, editable: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 150},
            {name: 'total_nomina', index: 'total_nomina', search: false, editable: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 150},

            {name: 'aporte_individual', index: 'aporte_individual', search: false, editable: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 150},
            {name: 'anticipos_consumos', index: 'anticipos_consumos', search: false, editable: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 150},
            {name: 'faltantes_caja', index: 'faltantes_caja', search: false, editable: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 150},
            {name: 'multas', index: 'multas', search: false, editable: false, hidden: true, editrules: {edithidden: false}, align: 'center', frozen: true, width: 80},
            {name: 'prestamos_qui_iess', index: 'prestamos_qui_iess', search: false, editable: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 150},
            {name: 'credito_personal', index: 'credito_personal', search: false, editable: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 150},
            {name: 'otros_descuentos', index: 'otros_descuentos', search: false, editable: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 150},
            {name: 'total_deduccion', index: 'total_deduccion', search: false, editable: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 150},
            {name: 'neto_recibir', index: 'neto_recibir', search: false, editable: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 150},
        ],
        rowNum: 30,
        width: 1070,
        height: 200,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager_rol'),
        sortname: 'id_rol_pagos',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'clientArray',
        shrinkToFit: true,

        viewrecords: true,
        multiselect: true,
        loadComplete: function (data) {
            ///////////////////////////////////////////////////

            var sueldo_percividot = 0;
            var horas_extrast = 0;
            var otros_ingresost = 0;
            var fondos_recervat = 0;
            var aporte_patronalt = 0;
            var tercer_sueldot = 0;
            var cuarto_sueldot = 0;
            var total_nominat = 0;
            var aporte_individualt = 0;
            var anticipos_consumost = 0;
            var faltantes_cajat = 0;
            var multast = 0;
            var prestamos_qui_iesst = 0;
            var credito_personalt = 0;
            var otros_descuentost = 0;
            var total_deducciont = 0;
            var neto_recibirt = 0;

            var total_sueldo_percividot = 0;
            var total_horas_extrast = 0;
            var total_otros_ingresost = 0;
            var total_fondos_recervat = 0;
            var total_aporte_patronalt = 0;
            var total_tercer_sueldot = 0;
            var total_cuarto_sueldot = 0;
            var total_total_nominat = 0;
            var total_aporte_individualt = 0;
            var total_anticipos_consumost = 0;
            var total_faltantes_cajat = 0;
            var total_multast = 0;
            var total_prestamos_qui_iesst = 0;
            var total_credito_personalt = 0;
            var total_otros_descuentost = 0;
            var total_total_deducciont = 0;
            var total_neto_recibirt = 0;
            var repe = 0;

            var fil = jQuery("#list_rol").jqGrid("getRowData");
            for (var t = 0; t < fil.length; t++) {
                var dd = fil[t];
                sueldo_percividot = dd['sueldo_percivido'];
                console.log(sueldo_percividot);
                total_sueldo_percividot = parseFloat(total_sueldo_percividot) + parseFloat(sueldo_percividot);

                aporte_patronalt = dd['aporte_patronal'];
                total_aporte_patronalt = parseFloat(total_aporte_patronalt) + parseFloat(aporte_patronalt);

                horas_extrast = dd['horas_extras'];
                total_horas_extrast = parseFloat(total_horas_extrast) + parseFloat(horas_extrast);

                tercer_sueldot = dd['tercer_sueldo'];
                total_tercer_sueldot = parseFloat(total_tercer_sueldot) + parseFloat(tercer_sueldot);

                otros_ingresost = dd['otros_ingresos'];
                total_otros_ingresost = parseFloat(total_otros_ingresost) + parseFloat(otros_ingresost);

                cuarto_sueldot = dd['cuarto_sueldo'];
                total_cuarto_sueldot = parseFloat(total_cuarto_sueldot) + parseFloat(cuarto_sueldot);

                fondos_recervat = dd['fondos_recerva'];
                total_fondos_recervat = parseFloat(total_fondos_recervat) + parseFloat(fondos_recervat);

                total_nominat = dd['total_nomina'];
                total_total_nominat = parseFloat(total_total_nominat) + parseFloat(total_nominat);

                ////////////////////////////////////////

                aporte_individualt = dd['aporte_individual'];
                total_aporte_individualt = parseFloat(total_aporte_individualt) + parseFloat(aporte_individualt);

                prestamos_qui_iesst = dd['prestamos_qui_iess'];
                total_prestamos_qui_iesst = parseFloat(total_prestamos_qui_iesst) + parseFloat(prestamos_qui_iesst);

                neto_recibirt = dd['neto_recibir'];
                total_neto_recibirt = parseFloat(total_neto_recibirt) + parseFloat(neto_recibirt);

                anticipos_consumost = dd['anticipos_consumos'];
                total_anticipos_consumost = parseFloat(total_anticipos_consumost) + parseFloat(anticipos_consumost);

                credito_personalt = dd['credito_personal'];
                total_credito_personalt = parseFloat(total_credito_personalt) + parseFloat(credito_personalt);

                faltantes_cajat = dd['faltantes_caja'];
                total_faltantes_cajat = parseFloat(total_faltantes_cajat) + parseFloat(faltantes_cajat);

                otros_descuentost = dd['otros_descuentos'];
                total_otros_descuentost = parseFloat(total_otros_descuentost) + parseFloat(otros_descuentost);

                total_deducciont = dd['total_deduccion'];
                total_total_deducciont = parseFloat(total_total_deducciont) + parseFloat(total_deducciont);

            }

            $("#sueldo_percividot").val(total_sueldo_percividot.toFixed(4));
            $("#aporte_patronalt").val(total_aporte_patronalt.toFixed(4));
            $("#horas_extrast").val(total_horas_extrast.toFixed(4));
            $("#tercer_sueldot").val(total_tercer_sueldot.toFixed(4));
            $("#otros_ingresost").val(total_otros_ingresost.toFixed(4));
            $("#cuarto_sueldot").val(total_cuarto_sueldot.toFixed(4));
            $("#fondos_recervat").val(total_fondos_recervat.toFixed(2));
            $("#total_nominat").val(total_total_nominat.toFixed(4));


            $("#aporte_individualt").val(total_aporte_individualt.toFixed(4));
            $("#prestamos_qui_iesst").val(total_prestamos_qui_iesst.toFixed(4));
            $("#neto_recibirt").val(total_neto_recibirt.toFixed(4));
            $("#anticipos_consumost").val(total_anticipos_consumost.toFixed(4));
            $("#credito_personalt").val(total_credito_personalt.toFixed(4));
            $("#faltantes_cajat").val(total_faltantes_cajat.toFixed(2));
            $("#otros_descuentost").val(total_otros_descuentost.toFixed(4));
            $("#multast").val(total_multast.toFixed(4));
            $("#total_deducciont").val(total_total_deducciont.toFixed(4));
//            $("#cedula_empleado").focus();
        },
        gridComplete: function () {
            var ids = jQuery("#list_rol").jqGrid('getDataIDs');
            for (var i = 0; i < ids.length; i++) {
                var ids = jQuery("#list_rol").getDataIDs();
                for (var i = 0; i < ids.length; i++) {
                    var id_rol = ids[i];
                    if ($("#clic_agregar").val() == "1") {
                        be ="<i class='fa fa-print' style='cursor:not-allowed;' title='Para enviar el correo primero debe autorizar la factura'> </i>";
                        jQuery("#list_rol").jqGrid('setRowData', ids[i], {
                            imprimir: be
                        });
                    } else {

                        be = "<a  onclick=\"imprimirRol('" + id_rol + "')\" title='' ><i class='fa fa-print' style='cursor:pointer; cursor: hand'></i></a>";
                        jQuery("#list_rol").jqGrid('setRowData', ids[i], {
                            imprimir: be
                        });


                    }
                }
            }
        },
        editoptions: {
            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {
//                console.log("entroooaww111");
                var id = jQuery("#list_rol").jqGrid('getGridParam', 'selrow');
                jQuery('#list_rol').jqGrid('restoreRow', id);
                var ret = jQuery("#list_rol").jqGrid('getRowData', id);
                var fil = jQuery("#list_rol").jqGrid("getRowData");
                var su = jQuery("#list_rol").jqGrid('delRowData', rowid);
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

            var id = jQuery("#list_rol").jqGrid('getGridParam', 'selrow');
            jQuery('#list_rol').jqGrid('restoreRow', id);
            var ret = jQuery("#list_rol").jqGrid('getRowData', id);
        },
        delOptions: {
            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {
                var id = jQuery("#list_rol").jqGrid('getGridParam', 'selrow');
                jQuery('#list_rol').jqGrid('restoreRow', id);
                var ret = jQuery("#list_rol").jqGrid('getRowData', id);
                var su = jQuery("#list_rol").jqGrid('delRowData', rowid);
                var sueldo_percividot = 0;
                var horas_extrast = 0;
                var otros_ingresost = 0;
                var fondos_recervat = 0;
                var aporte_patronalt = 0;
                var tercer_sueldot = 0;
                var cuarto_sueldot = 0;
                var total_nominat = 0;
                var aporte_individualt = 0;
                var anticipos_consumost = 0;
                var faltantes_cajat = 0;
                var multast = 0;
                var prestamos_qui_iesst = 0;
                var credito_personalt = 0;
                var otros_descuentost = 0;
                var total_deducciont = 0;
                var neto_recibirt = 0;

                var total_sueldo_percividot = 0;
                var total_horas_extrast = 0;
                var total_otros_ingresost = 0;
                var total_fondos_recervat = 0;
                var total_aporte_patronalt = 0;
                var total_tercer_sueldot = 0;
                var total_cuarto_sueldot = 0;
                var total_total_nominat = 0;
                var total_aporte_individualt = 0;
                var total_anticipos_consumost = 0;
                var total_faltantes_cajat = 0;
                var total_multast = 0;
                var total_prestamos_qui_iesst = 0;
                var total_credito_personalt = 0;
                var total_otros_descuentost = 0;
                var total_total_deducciont = 0;
                var total_neto_recibirt = 0;
                var repe = 0;

                var fil = jQuery("#list_rol").jqGrid("getRowData");
                for (var t = 0; t < fil.length; t++) {
                    var dd = fil[t];
                    sueldo_percividot = dd['sueldo_percivido'];
                    total_sueldo_percividot = parseFloat(total_sueldo_percividot) + parseFloat(sueldo_percividot);

                    aporte_patronalt = dd['aporte_patronal'];
                    total_aporte_patronalt = parseFloat(total_aporte_patronalt) + parseFloat(aporte_patronalt);

                    horas_extrast = dd['horas_extras'];
                    total_horas_extrast = parseFloat(total_horas_extrast) + parseFloat(horas_extrast);

                    tercer_sueldot = dd['tercer_sueldo'];
                    total_tercer_sueldot = parseFloat(total_tercer_sueldot) + parseFloat(tercer_sueldot);

                    otros_ingresost = dd['otros_ingresos'];
                    total_otros_ingresost = parseFloat(total_otros_ingresost) + parseFloat(otros_ingresost);

                    cuarto_sueldot = dd['cuarto_sueldo'];
                    total_cuarto_sueldot = parseFloat(total_cuarto_sueldot) + parseFloat(cuarto_sueldot);

                    fondos_recervat = dd['fondos_recerva'];
                    total_fondos_recervat = parseFloat(total_fondos_recervat) + parseFloat(fondos_recervat);

                    total_nominat = dd['total_nomina'];
                    total_total_nominat = parseFloat(total_total_nominat) + parseFloat(total_nominat);

                    ////////////////////////////////////////

                    aporte_individualt = dd['aporte_individual'];
                    total_aporte_individualt = parseFloat(total_aporte_individualt) + parseFloat(aporte_individualt);

                    prestamos_qui_iesst = dd['prestamos_qui_iess'];
                    total_prestamos_qui_iesst = parseFloat(total_prestamos_qui_iesst) + parseFloat(prestamos_qui_iesst);

                    neto_recibirt = dd['neto_recibir'];
                    total_neto_recibirt = parseFloat(total_neto_recibirt) + parseFloat(neto_recibirt);

                    anticipos_consumost = dd['anticipos_consumos'];
                    total_anticipos_consumost = parseFloat(total_anticipos_consumost) + parseFloat(anticipos_consumost);

                    credito_personalt = dd['credito_personal'];
                    total_credito_personalt = parseFloat(total_credito_personalt) + parseFloat(credito_personalt);

                    faltantes_cajat = dd['faltantes_caja'];
                    total_faltantes_cajat = parseFloat(total_faltantes_cajat) + parseFloat(faltantes_cajat);

                    otros_descuentost = dd['otros_descuentos'];
                    total_otros_descuentost = parseFloat(total_otros_descuentost) + parseFloat(otros_descuentost);

                    total_deducciont = dd['total_deduccion'];
                    total_total_deducciont = parseFloat(total_total_deducciont) + parseFloat(total_deducciont);

                }

                $("#sueldo_percividot").val(total_sueldo_percividot.toFixed(4));
                $("#aporte_patronalt").val(total_aporte_patronalt.toFixed(4));
                $("#horas_extrast").val(total_horas_extrast.toFixed(4));
                $("#tercer_sueldot").val(total_tercer_sueldot.toFixed(4));
                $("#otros_ingresost").val(total_otros_ingresost.toFixed(4));
                $("#cuarto_sueldot").val(total_cuarto_sueldot.toFixed(4));
                $("#fondos_recervat").val(total_fondos_recervat.toFixed(2));
                $("#total_nominat").val(total_total_nominat.toFixed(4));

                $("#aporte_individualt").val(total_aporte_individualt.toFixed(4));
                $("#prestamos_qui_iesst").val(total_prestamos_qui_iesst.toFixed(4));
                $("#neto_recibirt").val(total_neto_recibirt.toFixed(4));
                $("#anticipos_consumost").val(total_anticipos_consumost.toFixed(4));
                $("#credito_personalt").val(total_credito_personalt.toFixed(4));
                $("#faltantes_cajat").val(total_faltantes_cajat.toFixed(2));
                $("#otros_descuentost").val(total_otros_descuentost.toFixed(4));
                $("#multast").val(total_multast.toFixed(4));
                $("#total_deducciont").val(total_total_deducciont.toFixed(4));
//                $("#cedula_empleado").focus();
                if (su === true) {
                    rp_ge.processing = true;
                    $(".ui-icon-closethick").trigger('click');
                }
                return true;
            },
            processing: true
        }

    });
    $(window).bind('resize', function () {
        jQuery("#list4").setGridWidth($('#pager4').width());
    }).trigger('resize');
    jQuery("#list4").jqGrid({
        url: 'xmlPlanCuentas.php',
        datatype: 'xml',
        colNames: ['Cod. Cuenta', 'Descripcion', 'Cuenta'],
        colModel: [
            {name: 'idcontable', index: 'idcontable', editable: true, align: 'left', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'ccontable', index: 'ccontable', editable: true, align: 'center', width: '490', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'cuenta', index: 'cuenta', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}}
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
            console.log(string1);
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

    // buscador facturas ventas  
    jQuery("#list2").jqGrid({
        url: 'xmlBuscarRolPagos.php',
        datatype: 'xml',
        colNames: ['ID', 'MES', 'AÑO', 'MONTO TOTAL', 'FECHA'],
        colModel: [{
                name: 'id_rol_pagos',
                index: 'id_rol_pagos',
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
                name: 'mes',
                index: 'mes',
                editable: false,
                search: true,
                hidden: false,
                editrules: {
                    edithidden: false
                },
                align: 'center',
                frozen: true,
                width: 150
            },
            {
                name: 'anio',
                index: 'anio',
                editable: true,
                search: true,
                hidden: false,
                editrules: {
                    edithidden: false
                },
                align: 'center',
                frozen: true,
                width: 200
            },

            {
                name: 'total_venta',
                index: 'total_venta',
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
                name: 'fecha_actual',
                index: 'fecha_actual',
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
        ],
        rowNum: 30,
        width: 750,
        height: 220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager2'),
        sortname: 'id_rol_pagos',
        sortorder: 'desc',
        viewrecords: true,

        ondblClickRow: function () {
            var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
            jQuery('#list2').jqGrid('restoreRow', id);
            if (id) {
                $("#clic_agregar").val("") ;
                var ret = jQuery("#list2").jqGrid('getRowData', id);
                var valor = ret.mes;
                $("#btnGuardar").attr("disabled", true);

//                $.getJSON('retornar_rol_pagos.php?com=' + valor, function (data) {
//                var tama = data.length;


//                    if (tama != 0) {
                $("#list_rol").jqGrid('setGridParam', {
                    url: 'retornar_rol_pagos.php?com=' + valor + "&anio=" + $("#slct_anio_cf").val(), datatype: 'xml'
                }).trigger('reloadGrid');

                var su;
                var count = 0;
                var id_rol = 0;
                var mes = 0;
                var anio = 0;
                var sub1 = 0;
                var fil = jQuery("#list2").jqGrid("getRowData");
                for (var t = 0; t < fil.length; t++) {
                    var dd = fil[t];
                    id_rol = dd['id_rol_pagos'];
                    mes = dd['mes'];
                    anio = dd['anio'];
                }
                $("#id_rol").val(id_rol);
                $("#select_mes").val(mes);
                $("#slct_anio_cf").val(anio);
                totales();

                ///////////////////////////////////////////////////

                var sueldo_percividot = 0;
                var horas_extrast = 0;
                var otros_ingresost = 0;
                var fondos_recervat = 0;
                var aporte_patronalt = 0;
                var tercer_sueldot = 0;
                var cuarto_sueldot = 0;
                var total_nominat = 0;
                var aporte_individualt = 0;
                var anticipos_consumost = 0;
                var faltantes_cajat = 0;
                var multast = 0;
                var prestamos_qui_iesst = 0;
                var credito_personalt = 0;
                var otros_descuentost = 0;
                var total_deducciont = 0;
                var neto_recibirt = 0;

                var total_sueldo_percividot = 0;
                var total_horas_extrast = 0;
                var total_otros_ingresost = 0;
                var total_fondos_recervat = 0;
                var total_aporte_patronalt = 0;
                var total_tercer_sueldot = 0;
                var total_cuarto_sueldot = 0;
                var total_total_nominat = 0;
                var total_aporte_individualt = 0;
                var total_anticipos_consumost = 0;
                var total_faltantes_cajat = 0;
                var total_multast = 0;
                var total_prestamos_qui_iesst = 0;
                var total_credito_personalt = 0;
                var total_otros_descuentost = 0;
                var total_total_deducciont = 0;
                var total_neto_recibirt = 0;
                var repe = 0;

                var fil = jQuery("#list_rol").jqGrid("getRowData");
                for (var t = 0; t < fil.length; t++) {
                    var dd = fil[t];
                    sueldo_percividot = dd['sueldo_percivido'];
                    console.log(sueldo_percividot);
                    total_sueldo_percividot = parseFloat(total_sueldo_percividot) + parseFloat(sueldo_percividot);

                    aporte_patronalt = dd['aporte_patronal'];
                    total_aporte_patronalt = parseFloat(total_aporte_patronalt) + parseFloat(aporte_patronalt);

                    horas_extrast = dd['horas_extras'];
                    total_horas_extrast = parseFloat(total_horas_extrast) + parseFloat(horas_extrast);

                    tercer_sueldot = dd['tercer_sueldo'];
                    total_tercer_sueldot = parseFloat(total_tercer_sueldot) + parseFloat(tercer_sueldot);

                    otros_ingresost = dd['otros_ingresos'];
                    total_otros_ingresost = parseFloat(total_otros_ingresost) + parseFloat(otros_ingresost);

                    cuarto_sueldot = dd['cuarto_sueldo'];
                    total_cuarto_sueldot = parseFloat(total_cuarto_sueldot) + parseFloat(cuarto_sueldot);

                    fondos_recervat = dd['fondos_recerva'];
                    total_fondos_recervat = parseFloat(total_fondos_recervat) + parseFloat(fondos_recervat);

                    total_nominat = dd['total_nomina'];
                    total_total_nominat = parseFloat(total_total_nominat) + parseFloat(total_nominat);

                    ////////////////////////////////////////

                    aporte_individualt = dd['aporte_individual'];
                    total_aporte_individualt = parseFloat(total_aporte_individualt) + parseFloat(aporte_individualt);

                    prestamos_qui_iesst = dd['prestamos_qui_iess'];
                    total_prestamos_qui_iesst = parseFloat(total_prestamos_qui_iesst) + parseFloat(prestamos_qui_iesst);

                    neto_recibirt = dd['neto_recibir'];
                    total_neto_recibirt = parseFloat(total_neto_recibirt) + parseFloat(neto_recibirt);

                    anticipos_consumost = dd['anticipos_consumos'];
                    total_anticipos_consumost = parseFloat(total_anticipos_consumost) + parseFloat(anticipos_consumost);

                    credito_personalt = dd['credito_personal'];
                    total_credito_personalt = parseFloat(total_credito_personalt) + parseFloat(credito_personalt);

                    faltantes_cajat = dd['faltantes_caja'];
                    total_faltantes_cajat = parseFloat(total_faltantes_cajat) + parseFloat(faltantes_cajat);

                    otros_descuentost = dd['otros_descuentos'];
                    total_otros_descuentost = parseFloat(total_otros_descuentost) + parseFloat(otros_descuentost);

                    total_deducciont = dd['total_deduccion'];
                    total_total_deducciont = parseFloat(total_total_deducciont) + parseFloat(total_deducciont);

                }

                $("#sueldo_percividot").val(total_sueldo_percividot.toFixed(4));
                $("#aporte_patronalt").val(total_aporte_patronalt.toFixed(4));
                $("#horas_extrast").val(total_horas_extrast.toFixed(4));
                $("#tercer_sueldot").val(total_tercer_sueldot.toFixed(4));
                $("#otros_ingresost").val(total_otros_ingresost.toFixed(4));
                $("#cuarto_sueldot").val(total_cuarto_sueldot.toFixed(4));
                $("#fondos_recervat").val(total_fondos_recervat.toFixed(2));
                $("#total_nominat").val(total_total_nominat.toFixed(4));


                $("#aporte_individualt").val(total_aporte_individualt.toFixed(4));
                $("#prestamos_qui_iesst").val(total_prestamos_qui_iesst.toFixed(4));
                $("#neto_recibirt").val(total_neto_recibirt.toFixed(4));
                $("#anticipos_consumost").val(total_anticipos_consumost.toFixed(4));
                $("#credito_personalt").val(total_credito_personalt.toFixed(4));
                $("#faltantes_cajat").val(total_faltantes_cajat.toFixed(2));
                $("#otros_descuentost").val(total_otros_descuentost.toFixed(4));
                $("#multast").val(total_multast.toFixed(4));
                $("#total_deducciont").val(total_total_deducciont.toFixed(4));
//                $("#cedula_empleado").focus();


//                        for (var i = 0; i < tama; i = i + 24) {
//
//                            var datarow = {
//
//                                id_rol_pagos: data[i],
//                                id_empleado: data[i + 1],
//
//                                cedula_empleado: data[i + 2],
//                                nombres_empleado: data[i + 3],
//                                cargo_empleado: data[i + 4],
//                                dias_trabajados: data[i + 5],
//                                salario_empleado: data[i + 6],
//
//                                sueldo_percivido: data[i + 7],
//                                horas_extras: data[i + 8],
//                                otros_ingresos: data[i + 9],
//                                fondos_recerva: data[i + 10],
//                                aporte_patronal: data[i + 11],
//                                tercer_sueldo: data[i + 12],
//                                cuarto_sueldo: data[i + 13],
//                                total_nomina: data[i + 14],
//                                aporte_individual: data[i + 15],
//                                anticipos_consumos: data[i + 16],
//                                faltantes_caja: data[i + 17],
//                                multas: data[i + 18],
//                                prestamos_qui_iess: data[i + 19],
//                                credito_personal: data[i + 20],
//                                otros_descuentos: data[i + 21],
//                                total_deduccion: data[i + 22],
//                                neto_recibir: data[i + 23]
//                            };
//                            var su = jQuery("#list_rol").jqGrid('addRowData', data[i], datarow);
////                            suma_total = suma_total + parseFloat(data[i + 3]);
//                        }

//                    }
//                });
                $("#buscar_rol_pagos").dialog("close");

            } else {
                alertify.alert("Seleccione una Factura");
            }
        }
    }).jqGrid('navGrid', '#pager2', {
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

    jQuery("#list2").jqGrid('navButtonAdd', '#pager2', {
        caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
            jQuery('#list2').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list2").jqGrid('getRowData', id);
                var valor = ret.id_factura_venta;
                /////////////agregregar datos factura////////
                $("#list_rol").jqGrid('setGridParam', {
                    url: 'retornar_rol_pagos.php?com=' + valor + "&anio=" + $("#slct_anio_cf").val(),
                    datatype: 'xml'
                }).trigger('reloadGrid');
                var su;
                var count = 0;
                var id_rol = 0;
                var sub1 = 0;
                var fil = jQuery("#list2").jqGrid("getRowData");
                for (var t = 0; t < fil.length; t++) {
                    var dd = fil[t];
                    id_rol = dd['id_rol_pagos'];
                }
                $("#id_rol").val(id_rol);
                totales();
                ///////////////////////////////////////////////////

                var sueldo_percividot = 0;
                var horas_extrast = 0;
                var otros_ingresost = 0;
                var fondos_recervat = 0;
                var aporte_patronalt = 0;
                var tercer_sueldot = 0;
                var cuarto_sueldot = 0;
                var total_nominat = 0;
                var aporte_individualt = 0;
                var anticipos_consumost = 0;
                var faltantes_cajat = 0;
                var multast = 0;
                var prestamos_qui_iesst = 0;
                var credito_personalt = 0;
                var otros_descuentost = 0;
                var total_deducciont = 0;
                var neto_recibirt = 0;

                var total_sueldo_percividot = 0;
                var total_horas_extrast = 0;
                var total_otros_ingresost = 0;
                var total_fondos_recervat = 0;
                var total_aporte_patronalt = 0;
                var total_tercer_sueldot = 0;
                var total_cuarto_sueldot = 0;
                var total_total_nominat = 0;
                var total_aporte_individualt = 0;
                var total_anticipos_consumost = 0;
                var total_faltantes_cajat = 0;
                var total_multast = 0;
                var total_prestamos_qui_iesst = 0;
                var total_credito_personalt = 0;
                var total_otros_descuentost = 0;
                var total_total_deducciont = 0;
                var total_neto_recibirt = 0;
                var repe = 0;

                var fil = jQuery("#list_rol").jqGrid("getRowData");
                for (var t = 0; t < fil.length; t++) {
                    var dd = fil[t];
                    sueldo_percividot = dd['sueldo_percivido'];
                    total_sueldo_percividot = parseFloat(total_sueldo_percividot) + parseFloat(sueldo_percividot);

                    aporte_patronalt = dd['aporte_patronal'];
                    total_aporte_patronalt = parseFloat(total_aporte_patronalt) + parseFloat(aporte_patronalt);

                    horas_extrast = dd['horas_extras'];
                    total_horas_extrast = parseFloat(total_horas_extrast) + parseFloat(horas_extrast);

                    tercer_sueldot = dd['tercer_sueldo'];
                    total_tercer_sueldot = parseFloat(total_tercer_sueldot) + parseFloat(tercer_sueldot);

                    otros_ingresost = dd['otros_ingresos'];
                    total_otros_ingresost = parseFloat(total_otros_ingresost) + parseFloat(otros_ingresost);

                    cuarto_sueldot = dd['cuarto_sueldo'];
                    total_cuarto_sueldot = parseFloat(total_cuarto_sueldot) + parseFloat(cuarto_sueldot);

                    fondos_recervat = dd['fondos_recerva'];
                    total_fondos_recervat = parseFloat(total_fondos_recervat) + parseFloat(fondos_recervat);

                    total_nominat = dd['total_nomina'];
                    total_total_nominat = parseFloat(total_total_nominat) + parseFloat(total_nominat);

                    ////////////////////////////////////////

                    aporte_individualt = dd['aporte_individual'];
                    total_aporte_individualt = parseFloat(total_aporte_individualt) + parseFloat(aporte_individualt);

                    prestamos_qui_iesst = dd['prestamos_qui_iess'];
                    total_prestamos_qui_iesst = parseFloat(total_prestamos_qui_iesst) + parseFloat(prestamos_qui_iesst);

                    neto_recibirt = dd['neto_recibir'];
                    total_neto_recibirt = parseFloat(total_neto_recibirt) + parseFloat(neto_recibirt);

                    anticipos_consumost = dd['anticipos_consumos'];
                    total_anticipos_consumost = parseFloat(total_anticipos_consumost) + parseFloat(anticipos_consumost);

                    credito_personalt = dd['credito_personal'];
                    total_credito_personalt = parseFloat(total_credito_personalt) + parseFloat(credito_personalt);

                    faltantes_cajat = dd['faltantes_caja'];
                    total_faltantes_cajat = parseFloat(total_faltantes_cajat) + parseFloat(faltantes_cajat);

                    otros_descuentost = dd['otros_descuentos'];
                    total_otros_descuentost = parseFloat(total_otros_descuentost) + parseFloat(otros_descuentost);

                    total_deducciont = dd['total_deduccion'];
                    total_total_deducciont = parseFloat(total_total_deducciont) + parseFloat(total_deducciont);

                }

                $("#sueldo_percividot").val(total_sueldo_percividot.toFixed(4));
                $("#aporte_patronalt").val(total_aporte_patronalt.toFixed(4));
                $("#horas_extrast").val(total_horas_extrast.toFixed(4));
                $("#tercer_sueldot").val(total_tercer_sueldot.toFixed(4));
                $("#otros_ingresost").val(total_otros_ingresost.toFixed(4));
                $("#cuarto_sueldot").val(total_cuarto_sueldot.toFixed(4));
                $("#fondos_recervat").val(total_fondos_recervat.toFixed(2));
                $("#total_nominat").val(total_total_nominat.toFixed(4));


                $("#aporte_individualt").val(total_aporte_individualt.toFixed(4));
                $("#prestamos_qui_iesst").val(total_prestamos_qui_iesst.toFixed(4));
                $("#neto_recibirt").val(total_neto_recibirt.toFixed(4));
                $("#anticipos_consumost").val(total_anticipos_consumost.toFixed(4));
                $("#credito_personalt").val(total_credito_personalt.toFixed(4));
                $("#faltantes_cajat").val(total_faltantes_cajat.toFixed(2));
                $("#otros_descuentost").val(total_otros_descuentost.toFixed(4));
                $("#multast").val(total_multast.toFixed(4));
                $("#total_deducciont").val(total_total_deducciont.toFixed(4));
//                $("#cedula_empleado").focus();
//                $.getJSON('retornar_rol_pagos.php?com=' + valor, function (data) {
//                    var tama = data.length;
//
//                    if (tama != 0) {
//                        for (var i = 0; i < tama; i = i + 10) {
//
//                            var datarow = {
//                                id_rol_pagos: data[i],
//                                id_empleado: data[i + 1],
//
//                                cedula_empleado: data[i + 2],
//                                nombres_empleado: data[i + 3],
//                                cargo_empleado: data[i + 4],
//                                dias_trabajados: data[i + 5],
//                                salario_empleado: data[i + 6],
//
//                                sueldo_percivido: data[i + 7],
//                                horas_extras: data[i + 8],
//                                otros_ingresos: data[i + 9],
//                                fondos_recerva: data[i + 10],
//                                aporte_patronal: data[i + 11],
//                                tercer_sueldo: data[i + 12],
//                                cuarto_sueldo: data[i + 13],
//                                total_nomina: data[i + 14],
//                                aporte_individual: data[i + 15],
//                                anticipos_consumos: data[i + 16],
//                                faltantes_caja: data[i + 17],
//                                multas: data[i + 18],
//                                prestamos_qui_iess: data[i + 19],
//                                credito_personal: data[i + 20],
//                                otros_descuentos: data[i + 21],
//                                total_deduccion: data[i + 22],
//                                neto_recibir: data[i + 23]
//                            };
//                            var su = jQuery("#list_rol").jqGrid('addRowData', data[i], datarow);
////                            suma_total = suma_total + parseFloat(data[i + 3]);
//                        }
//
//                    }
//                });
                $("#buscar_rol_pagos").dialog("close");

            } else {
                alertify.alert("Seleccione ");
            }
        }
    });
    // fin tabla

    jQuery(window).bind('resize', function () {
        jQuery("#list_rol").setGridWidth(jQuery('#grid_container').width(), true);
    }).trigger('resize');
/////////////////////
}

