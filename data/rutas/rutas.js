$(document).on("ready", inicio);

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

function scrollToTop() {
    $('html, body').animate({
        scrollTop: 0
    }, 'slow');
}
var dialogo = {
    autoOpen: false,
    resizable: false,
    width: 860,
    height: 350,
    modal: true
};
var dialogo3 = {
    autoOpen: false,
    resizable: false,
    width: 400,
    height: 210,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind"
}
var dialogo4 = {
    autoOpen: false,
    resizable: false,
    width: 240,
    height: 150,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind"
}

function abrirDialogo() {
    $("#rutas").dialog("open");
}
function guardar_rutas() {

    if ($("#nombre_ruta").val() === "") {
        $("#nombre_ruta").focus();
        alertify.error("Ingrese Cèdula");
    } else {
        var nombre_ruta = ($("#nombre_ruta").val());

        $.ajax({
            type: "POST",
            url: "comparar_rutas.php",
            data: "nombre_ruta=" + nombre_ruta,
            success: function (data) {
                var val = data;
                if (val != 0) {
                    alertify.error("Error.... La ruta ya esta creada");
                } else {
//                    if ($("#nombre").val() === "") {
//                        $("#nombre").focus();
//                        alertify.error("Ingrese Nombre");
//                    } else {
                    if ($("#id_vendedor").val() === "") {
                        $("#id_vendedor").focus();
                        alertify.error("Ingrese un Vendedor");
                    } else {
                        $("#btnGuardar").attr("disabled", true);
                        $.ajax({
                            type: "POST",
                            url: "guardar_rutas.php",
                            data: "&nombre=" + $("#nombre").val() + "&nombre_ruta=" + $("#nombre_ruta").val() + "&id_vendedor=" + $("#id_vendedor").val() + "&frecuencia=" + $("#frecuencia").val(),
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

//                    }
                }
            }

        });
    }

}


function modificar_rutas() {

    var iden = $("#nombre_ruta").val();
    var expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

    if ($("#nombre_ruta").val() === "") {
        alertify.error("Seleccione un vendedor");
    } else {
//        if ($("#nombre").val() === "") {
//            $("#nombre").focus();
//            alertify.error("Indique Nombre");
//        } else {
        if ($("#id_vendedor").val() === "") {
            $("#id_vendedor").focus();
            alertify.error("Ingrese un Vendedor");
        } else {
            $("#btnModificar").attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "modificar_rutas.php",
                data: "&nombre_ruta=" + $("#nombre_ruta").val() +
                        "&nombre=" + $("#nombre").val() +
                        "&id_vendedor=" + $("#id_vendedor").val() + "&id_rutas=" + $("#id_ruta").val() + "&frecuencia=" + $("#frecuencia").val(),

                success: function (data) {
                    var val = data;
                    if (val == 1) {
                        alertify.success('Datos Modificados Correctamente');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                }
            });
        }
//        }
    }
}

function eliminar_rutas() {

    if ($("#nombre_ruta").val() === "") {
        alertify.error("Seleccione una Ruta");
    } else {
        $("#clave_permiso_ven").dialog("open");
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
                    alertify.error("Error... La clave es incorrecta, ingrese nuevamente");
                } else {

                    if (val == 1) {
                        $("#seguro_ven").dialog("open");
                    }
                }
            }
        });
    }
}

function aceptar() {
    $.ajax({
        type: "POST",
        url: "eliminar_rutas.php",
        data: "id_ruta=" + $("#id_ruta").val(),
        success: function (data) {
            var val = data;
            if (val == 1) {
                alertify.error('Error.. La Ruta tiene movimientos en el sistema');
                setTimeout(function () {
                    location.reload();
                }, 1000);
            } else {
                alertify.success('RutaEliminado Correctamente');
                setTimeout(function () {
                    location.reload();
                }, 1000);
            }
        }
    });
}

function cancelar() {
    $("#seguro_ven").dialog("close");
    $("#clave_permiso_ven").dialog("close");
    $("#clave").val("");
}

function cancelar_acceso() {
    $("#clave_permiso_ven").dialog("close");
    $("#clave").val("");
}

function nuevo_rutas(e) {
    location.reload();
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

function reset() {
    $("#toggleCSS").attr("href", "../../css/alertify.default.css");
    alertify.set({
        labels: {
            ok: "OK",
            cancel: "Cancel"
        },
        delay: 5000,
        buttonReverse: false,
        buttonFocus: "ok"
    });
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
    // buscar clientes identificacion
    $("#ruc_ci_cli").autocomplete({
        source: "buscar_vendedor.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#ruc_ci_cli").val(ui.item.value);
            $("#id_vendedor").val(ui.item.id_vendedor);
            $("#nombre_vendedor").val(ui.item.nombre_vendedor);

            return false;
        },
        select: function (event, ui) {
            $("#ruc_ci_cli").val(ui.item.value);
            $("#id_vendedor").val(ui.item.id_vendedor);
            $("#nombre_vendedor").val(ui.item.nombre_vendedor);
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
    };
    // fin

    // buscar clientes nombres

    $("#nombre_vendedor").autocomplete({
        source: "buscar_vendedor_nombre.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#nombre_vendedor").val(ui.item.value);
            $("#id_vendedor").val(ui.item.id_vendedor);
            $("#ruc_ci_cli").val(ui.item.ruc_ci_cli);

            return false;
        },
        select: function (event, ui) {
            $("#nombre_vendedor").val(ui.item.value);
            $("#id_vendedor").val(ui.item.id_vendedor);
            $("#ruc_ci_cli").val(ui.item.ruc_ci_cli);

            return false;
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
    };
    // fin

    $("[data-mask]").inputmask();
    alertify.set({
        delay: 1000
    });

    $("#nro_telefono").validCampoFranz("0123456789");
//    $("#nro_celular").validCampoFranz("0123456789");



    $("#btnGuardar").click(function (e) {
        e.preventDefault();
    });
    $("#btnBuscar").click(function (e) {
        e.preventDefault();
    });
    $("#btnModificar").click(function (e) {
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
    $("#btnGuardar").on("click", guardar_rutas);
    $("#btnModificar").on("click", modificar_rutas);
    $("#btnEliminar").on("click", eliminar_rutas);
    $("#btnNuevo").on("click", nuevo_rutas);
    $("#btnAceptar").on("click", aceptar);
    $("#btnSalir").on("click", cancelar);
    $("#btnAcceder").on("click", validar_acceso);
    $("#btnCancelar").on("click", cancelar_acceso);
    $("#btnBuscar").on("click", abrirDialogo);
    $("#rutas").dialog(dialogo);
    $("#clave_permiso_ven").dialog(dialogo3);
    $("#seguro_ven").dialog(dialogo4);


    jQuery("#list").jqGrid({
        url: 'datos_rutas.php',
        datatype: 'xml',
        colNames: ['id', 'id_vendedor buscar', 'Nombre Ruta', 'Descripción Ruta', 'Cédula', 'Nombre Vendedor', 'Frecuencia'],
        colModel: [
//            {name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: {keys: false, delbutton: true, editbutton: false}},
            {name: 'id_ruta', index: 'id_ruta', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'left', frozen: true, width: 30},
            {name: 'id_vendedor', index: 'id_vendedor', editable: false, search: false, hidden: true, editrules: {edithidden: false}, align: 'left', frozen: true, width: 100},
            {name: 'nombre_ruta', index: 'nombre_ruta', editable: false, search: false, hidden: false, editrules: {required: true}, align: 'left', frozen: true, width: 100},
            {name: 'nombre', index: 'nombre', editable: false, search: false, hidden: false, editrules: {required: true}, align: 'left', frozen: true, width: 100},
            {name: 'ruc_ci_cli', index: 'ruc_ci_cli', editable: false, search: false, hidden: false, editrules: {required: true}, align: 'left', frozen: true, width: 100},
            {name: 'nombre_vendedor', index: 'nombre_vendedor', editable: false, search: false, hidden: false, editrules: {required: true}, align: 'left', frozen: true, width: 150},
            {name: 'frecuencia', index: 'frecuencia', editable: false, search: false, hidden: false, editrules: {required: true}, align: 'left', frozen: true, width: 150},
        ],
        rowNum: 10,
        width: 830,
        height: 200,
        rowList: [10, 20, 30],
        pager: jQuery('#pager'),
        sortname: 'id_ruta',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'clientArray',
        shrinkToFit: true,
        ondblClickRow: function () {
            var id = jQuery("#list").jqGrid('getGridParam', 'selrow');
            jQuery('#list').jqGrid('restoreRow', id);
            jQuery("#list").jqGrid('GridToForm', id, "#rutas_form");
            $("#btnGuardar").attr("disabled", true);
            $("#rutas").dialog("close");
        }
    }).jqGrid('navGrid', '#pager', {
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
        bottominfo: "Todos los campos son obligatorios son obligatorios"
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
    jQuery("#list_grid_cargo").jqGrid({

        url: 'datos_rutas.php',
        colNames: ['id_ruta', 'id_vendedor ', 'Nombre Ruta', 'Descripcion Ruta', 'Ced/Vendedor', 'Nombre Vendedor', 'Frecuencia' ],
        colModel: [
//            {name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: {keys: false, delbutton: true, editbutton: false}},
            {name: 'id_ruta', index: 'id_ruta', editable: false, search: false, hidden: true, editrules: {edithidden: false}, align: 'left', frozen: true, width: 3},
            {name: 'id_vendedor', index: 'id_vendedor', editable: false, search: false, hidden: true, editrules: {edithidden: false}, align: 'left', frozen: true, width: 200},
            {name: 'nombre_ruta', index: 'nombre_ruta', editable: false, search: false, hidden: false, editrules: {required: true}, align: 'left', frozen: true, width: 200},
            {name: 'nombre', index: 'nombre', editable: false, search: false, hidden: false, editrules: {required: true}, align: 'left', frozen: true, width: 300},
            {name: 'ruc_ci_cli', index: 'ruc_ci_cli', editable: false, search: false, hidden: false, editrules: {required: true}, align: 'left', frozen: true, width: 200},
            {name: 'nombre_vendedor', index: 'nombre_vendedor', editable: false, search: false, hidden: false, editrules: {required: true}, align: 'left', frozen: true, width: 200},
            {name: 'frecuencia', index: 'frecuencia', editable: false, search: false, hidden: false, editrules: {required: true}, align: 'left', frozen: true, width: 200},
        ],
        rowNum: 30,
        width: 700,
        height: 400,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager_grid_cargo'),
        sortname: 'id_ruta',
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
    jQuery("#list").jqGrid('navButtonAdd', '#pager', {
        caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#list").jqGrid('getGridParam', 'selrow');
            jQuery('#list').jqGrid('restoreRow', id);
            var ret = jQuery("#list").jqGrid('getRowData', id);
            if (id) {
                jQuery("#list").jqGrid('GridToForm', id, "#rutas_form");
                $("#btnGuardar").attr("disabled", true);
                $("#rutas").dialog("close");
            } else {
                alertify.alert("Seleccione un fila");
            }
        }
    });
}