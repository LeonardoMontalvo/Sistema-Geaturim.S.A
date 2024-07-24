$(document).on("ready", inicio);
$(document).keydown(function (e) {
    var e = e || event;
    var keycode = e.which || e.keyCode;
    var obj = e.target || e.srcElement;

    if (keycode == 45) {
        abrirDialogo()
    }
    // Tecla Control Cliente
    //   if(keycode == 13) { agregar()}
    //   if(keycode == 39) { guardar_serie()}
    if (keycode == 27) {
        cancelar()
    }

});
function evento(e) {
    e.preventDefault();
}
var dialogo2 = {
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 355,
    modal: true,
    // position: "top",
    show: "explode",
    hide: "blind"
}

var dialogo3 = {
    autoOpen: false,
    resizable: false,
    width: 500,
    height: 250,
    modal: true,
    show: "explode",
    hide: "blind"
}
var dialogo4 = {
    autoOpen: false,
    resizable: false,
    width: 300,
    height: 150,
    modal: true,
    show: "explode",
    hide: "blind"

}

var dialogo6 = {
    autoOpen: false,
    resizable: false,
    width: 350,
    height: 180,
    modal: true,
    // position: "top",
    show: "explode",
    hide: "blind"
}
function openPDF() {
    window.open('../../ayudas/ayuda.pdf');
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
var dialogo = {
    autoOpen: false,
    resizable: false,
    width: 1000,
    height: 500,
    modal: true
};
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
    if (hours == 0)
        hours = 12;
    if (minutes <= 9)
        minutes = "0" + minutes;
    if (seconds <= 9)
        seconds = "0" + seconds;
    $("#hora_actual").val(hours + ":" + minutes + ":" + seconds + " " + dn);
    setTimeout("show()", 1000);
}

function enter(e) {
    if (e.which == 13 || e.keyCode === 13) {
        entrar();
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
function enter3(e) {
    if (e.which == 13 || e.keyCode === 13) {
        entrar3();
        return false;
    }
    return true;
}
function datePicker(id) {
    $("#" + id).datepicker({
        dateFormat: 'yy-mm-dd',

    });
    //.datepicker('setDate', 'today');
}
function datePicker1(id) {
    $("#" + id).datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: 1
    });
    //.datepicker('setDate', 'today');
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
function anular_factura() {
    $("#clave_permiso").dialog("open");
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





function entrar() {
    if ($("#descripcion").val() == "") {
        $("#descripcion").focus();
        alertify.error("Ingrese un producto");
    } else {
        if ($("#mxm3").val() == "") {
            $("#mxm3").val("0");
            $("#mxm3").focus();

        } else {
            if ($("#base").val() == "") {
                $("#base").val("0");
                $("#base").focus();


            } else {
                if ($("#minimo").val() == "") {
                    $("#minimo").val("0");
                    $("#minimo").focus();
                } else {

                    $("#maximo").focus();

                }
            }
        }
    }
}
function activar_boton() {
    var tipo_tarifa = $("#tipo_tarifa").val();
    //         console.log("dataas"+tipo_tarifa);
    $.ajax({
        type: "POST",
        url: "xmlBuscarRubro_consult.php?id_clase=" + tipo_tarifa,
        data: "",
        success: function (data) {
            var val = data;
            console.log("dataas" + val);
            if (val != "") {
                $("#id_tipo_tarifa").val(val)
                $("#btnGuardar").attr("disabled", true);
            } else {
                $("#id_tipo_tarifa").val("")
                $("#btnGuardar").attr("disabled", false);
            }
        }
    });

}
function cargar_rubro_tarifa() {

    var tipo_tarifa = $("#tipo_tarifa").val();

    $("#list").jqGrid('setGridParam', {
        url: 'xmlBuscarRubro.php?id_clase=' + tipo_tarifa,
        datatype: 'xml',
    }).trigger('reloadGrid');

    activar_boton();


}

function limpiar_input() {
    $("#descripcion").val("");
    $("#mxm3").val("");
    $("#base").val("");
    $("#minimo").val("");
    $("#maximo").val("");


}

function ValidNum() {
    if (event.keyCode < 48 || event.keyCode > 57) {
        event.returnValue = false;
    }
    return true;
}


function entrar2() {
    if ($("#descripcion").val() == "") {
        $("#descripcion").focus();
        alertify.error("Ingrese ");
    } else {
        if ($("#mxm3").val() == "") {
            $("#mxm3").focus();
            alertify.error("Ingrese ");
        } else {
            if ($("#base").val() == "") {
                $("#base").focus();
                alertify.error("Ingrese ");
            } else {
                if ($("#minimo").val() == "") {
                    $("#minimo").focus();
                } else {
                    if ($("#maximo").val() == "") {
                        $("#maximo").focus();
                        alertify.error("Ingrese ");
                    } else {

                        var filas = jQuery("#list").jqGrid("getRowData");

                        var datarow = {
                            id_servicio: $("#cod_descripcion").val(),
                            descripcion: $("#descripcion").val(),
                            mxm3: $("#mxm3").val(),
                            base: $("#base").val(),
                            minimo: $("#minimo").val(),
                            maximo: $("#maximo").val()

                        };
                        su = jQuery("#list").jqGrid('addRowData', $("#cod_descripcion").val(), datarow);
                        limpiar_input();

                        $("#descripcion").focus();
                    }
                }

            }
        }
    }
}
function entrar3() {
    agregar();

}
function guardar_rubro_tarifa() {
    var tam = jQuery("#list").jqGrid("getRowData");

    if (tam.length == 0) {
        $("#descripcion").focus();
        alertify.error("Error... Ingrese productos en el inventario");
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
            v1[i] = datos['id_servicio'];
            v2[i] = datos['descripcion'];
            v3[i] = datos['mxm3'];
            v4[i] = datos['base'];
            v5[i] = datos['minimo'];
            v6[i] = datos['maximo'];

        }
        for (i = 0; i < fil.length; i++) {
            string_v1 = string_v1 + "|" + v1[i];
            string_v2 = string_v2 + "|" + v2[i];
            string_v3 = string_v3 + "|" + v3[i];
            string_v4 = string_v4 + "|" + v4[i];
            string_v5 = string_v5 + "|" + v5[i];
            string_v6 = string_v6 + "|" + v6[i];

        }

        $.ajax({
            type: "POST",
            url: "guardar_rubro_tarifa.php",
            data: "tipo_tarifa=" + $("#tipo_tarifa").val() + "&fecha_actual=" + $("#fecha_actual").val() + "&hora_actual=" + $("#hora_actual").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&campo6=" + string_v6,
            success: function (data) {
                var val = data;
                if (val == 1) {
                    alertify.alert("Rubro Tarifa Guardado correctamente", function () {



                    });
                }
            }
        });
    }
}

function modificar_rubro_tarifa() {


    if ($("#tipo_tarifa").val() != '0') {
        $("#btnGuardar").attr("disabled", true);
        console.log("sasa" + $("#tipo_tarifa").val());
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


        var valor3 = "";
        var valor4 = "";
        var valor5 = "";
        var valor6 = "";


        var fil = jQuery("#list").jqGrid("getRowData");

        for (var i = 0; i < fil.length; i++) {
            var datos = fil[i];
            v1[i] = datos['id_servicio'];
            v2[i] = datos['descripcion'];
            v3[i] = datos['mxm3'];
            v4[i] = datos['base'];
            v5[i] = datos['minimo'];
            v6[i] = datos['maximo'];

            var cadena3 = v3[i];
            var result3 = cadena3.substr(7, 4);

            var cadena4 = v4[i];
            var result4 = cadena4.substr(7, 4);
            console.log("result3" + result4);
            var cadena5 = v5[i];
            var result5 = cadena5.substr(7, 4);

            var cadena6 = v6[i];
            var result6 = cadena6.substr(7, 4);

            if (result3 == 'type') {
                valor3 = true;
            }
            if (result4 == 'type') {
                valor4 = true;
            }
            if (result5 == 'type') {
                valor5 = true;
            }
            if (result6 == 'type') {
                valor6 = true;
            }
        }
        for (i = 0; i < fil.length; i++) {
            string_v1 = string_v1 + "|" + v1[i];
            string_v2 = string_v2 + "|" + v2[i];
            string_v3 = string_v3 + "|" + v3[i];
            string_v4 = string_v4 + "|" + v4[i];
            string_v5 = string_v5 + "|" + v5[i];
            string_v6 = string_v6 + "|" + v6[i];

        }

        if (valor3 == true) {
            alertify.error('Hacer click Enter ');
        } else {
            if (valor4 == true) {
                alertify.error('Hacer click  Enter ');
            } else {

                if (valor5 == true) {
                    alertify.error('Hacer click  Enter ');
                } else {
                    if (valor6 == true) {
                        alertify.error('Hacer click  Enter ');
                    } else {


                        console.log("valor3" + valor3);
                        $.ajax({
                            type: "POST",
                            url: "modificar_rubro_tarifa.php",
                            data: "tipo_tarifa=" + $("#tipo_tarifa").val() + "&fecha_actual=" + $("#fecha_actual").val() + "&hora_actual=" + $("#hora_actual").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5 + "&campo6=" + string_v6,
                            success: function (data) {
                                var val = data;
                                if (val == 1) {
                                    alertify.alert("Rubro Tarifa Modificado correctamente", function () {



                                    });
                                }
                            }
                        });

                    }
                }
            }
        }


    } else {
        alertify.error("Error... Selecciones una Tarifa");
    }
}



function nuevo() {
    location.reload();
}
function cancelar() {
    $("#list2").jqGrid("clearGridData", true);
    $("#series").dialog("close");

    $("#btnAgregar").attr("disabled", false);
}

function limpiar_campo1() {
    $("#descripcion").val("");
    $("#mxm3").val("");
    $("#base").val("");
    $("#minimo").val("");
    $("#maximo").val("");
}
function inicio() {

    show();
    // fin
    $("#series").dialog(dialogo);
    $("#clave_permiso").dialog(dialogo3);

    $("#descripcion").autocomplete({
        source: "buscar_producto.php",
        minLength: 1,
        focus: function (event, ui) {

            $("#descripcion").val(ui.item.value);

            $("#cod_descripcion").val(ui.item.articulo);

            //          $("#punto_venta_inv").val(ui.item.punto_venta_inv);
            return false;
        },
        select: function (event, ui) {

            $("#descripcion").val(ui.item.value);

            $("#cod_descripcion").val(ui.item.articulo);
            //                $("#punto_venta_inv").val(ui.item.punto_venta_inv);
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };


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

    //////Botones//////////
    $("#btnGuardar").click(function (e) {
        e.preventDefault();
    });
    $("#btnBuscar").click(function (e) {
        e.preventDefault();
    });
    $("#btnAgregar").click(function (e) {
        e.preventDefault();
    });
    $("#btnCancelarSeries").click(function (e) {
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

    $("#btnAtras").click(function (e) {
        e.preventDefault();
    });
    $("#btnAcceder").click(function (e) {
        e.preventDefault();
    });
    $("#btnAdelante").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardarSeries").click(function (e) {
        e.preventDefault();
    });
    //     $("#btnAnular").attr("disabled", true);
    $("#btnGuardar").on("click", guardar_rubro_tarifa);
    $("#btnModificar").on("click", modificar_rubro_tarifa);

    // $(document).bind('keydown', 'F7', guardar_inventario);
    // $('input').bind('keydown', 'F7', guardar_inventario);
    $("#btnNuevo").on("click", nuevo);

    $("#btnCancelarSeries").on("click", cancelar);

    //////inmput////////

    $("#cantidad").on("keypress", punto);
    $("#tipo_tarifa").on("change", cargar_rubro_tarifa);

    $("#descripcion").on("keypress", enter);
    $("#mxm3").on("keypress", enter);
    $("#base").on("keypress", enter);
    $("#minimo").on("keypress", enter);
    $("#maximo").on("keypress", enter2);
    $("#btnAnular").on("click", anular_factura);
    $('#serie_campos').on("keypress", enter3);
    $("#seguro").dialog(dialogo4);
    //    $('#felabo').on("keypress", enter3);
    //    $('#fExpira').on("keypress", enter3);

    ///////////////////
    $("#btnAcceder").on("click", validar_acceso);

    $("#buscar_rubro").dialog(dialogo2);

    $("#btnBuscar").click(function (e) {
        e.preventDefault();
        $("#buscar_rubro").dialog("open");
    });




    $('#fecha_actual').datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');


    jQuery("#list").jqGrid({

        datatype: "local",
        colNames: ['', 'ID RUBRO', 'ID SERVICIO', 'Descripcion', 'M_X_M3', 'Base', 'Minimo', 'Maximo'],
        colModel: [
            { name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: { keys: false, delbutton: true, editbutton: false } },
            { name: 'id_rubro', index: 'id_rubro', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'center', frozen: true, width: 3 },
            { name: 'id_servicio', index: 'id_servicio', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'center', frozen: true, width: 3 },
            { name: 'descripcion', index: 'descripcion', editable: false, search: false, hidden: false, editrules: { required: true }, align: 'center', frozen: true, width: 20 },
            { name: 'mxm3', index: 'mxm3', editable: true, frozen: true, editrules: { required: true }, align: 'center', width: 10 },
            { name: 'base', index: 'base', editable: true, frozen: true, search: false, hidden: false, editrules: { required: true }, align: 'center', frozen: true, width: 10 },
            { name: 'minimo', index: 'minimo', editable: true, search: false, hidden: false, editrules: { required: true }, align: 'center', frozen: true, width: 10 },
            { name: 'maximo', index: 'maximo', editable: true, search: false, hidden: false, editrules: { required: true }, align: 'center', frozen: true, width: 10 }
        ],
        rowNum: 30,
        width: 700,
        height: 200,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager'),
        sortname: 'id_rubro',
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
                var id = jQuery("#list").jqGrid('getGridParam', 'selrow');
                jQuery('#list').jqGrid('restoreRow', id);
                var ret = jQuery("#list").jqGrid('getRowData', id);
                var fil = jQuery("#list").jqGrid("getRowData");
                var su = jQuery("#list").jqGrid('delRowData', rowid);
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

            var id = jQuery("#list").jqGrid('getGridParam', 'selrow');
            jQuery('#list').jqGrid('restoreRow', id);
            var ret = jQuery("#list").jqGrid('getRowData', id);

        },
        delOptions: {
            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {
                var id = jQuery("#list").jqGrid('getGridParam', 'selrow');
                jQuery('#list').jqGrid('restoreRow', id);
                var ret = jQuery("#list").jqGrid('getRowData', id);


                var su = jQuery("#list").jqGrid('delRowData', rowid);
                if (su === true) {
                    rp_ge.processing = true;
                    $(".ui-icon-closethick").trigger('click');
                }
                return true;
            },
            processing: true
        }

    });

    //    jQuery(window).bind('resize', function () {
    //        jQuery("#list").setGridWidth(jQuery('#grid_container').width(), true);
    //    }).trigger('resize');

}


