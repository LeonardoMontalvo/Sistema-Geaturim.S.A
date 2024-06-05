$(document).on("ready", inicio);

var selectedUsuarioPv;

function openPDF() {
    window.open('../../ayudas/ayuda.pdf');
}

function numeros(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla == 8)
        return true;
    patron = /\d/;
    te = String.fromCharCode(tecla);
    return patron.test(te);
}
/*
 function fechaReg(el) {
 jQuery(el).datetimepicker({
 dateFormat: 'yy-mm-dd',
 timeFormat: 'hh:mm:ss A',
 changeYear: true,
 changeMonth: true,
 numberOfMonths: 1,
 timeOnlyTitle: 'Seleccione Horario',
 timeText: 'Hora seleccionada',
 hourText: 'Hora',
 minuteText: 'Minuto',
 secondText: 'Segundo',
 millisecText: 'Milisegundo',
 currentText: 'Ahora',
 closeText: 'Listo',
 ampm: false
 }).datetimepicker("setDate", new Date());
 }*/
let animarfila = false;
function recargarTabla() {
    jQuery("#list_acciones").jqGrid("clearGridData");
    jQuery("#list_acciones").jqGrid("setGridParam", {
        page: 1,
    });
    jQuery("#list_acciones").trigger("reloadGrid");
}
function cambiarUmPorDefecto(idumprod, pordefecto, idprod) {
    console.log("sii usuario");    
    let ids = $("#tabla_esquemas").jqGrid("getDataIDs");
    
       if (animarfila) {
                    $("#" + ids[0]).css({ "animation": "anim_fondo 4s" });
                    animarfila = false;
                }
    $.ajax({
        url: "editar_por_defecto.php",
        method: "POST",
        dataType: "json",
        data: {
            id: idumprod,
            por_defecto: pordefecto

        },
        success: function (data) {
            recargarTabla();
            animarfila = true;
            $("#alertify-logs").empty();
            alertify.success("Cambio guardado");
        }
    });
}
function inicio() {

    initDalogAddPv();
    initPuntosVenta();

    function fechaReg(elem) {
        jQuery(elems).timepicker({
            timeFormat: 'h:i:s A',
            timeOnlyTitle: 'Seleccione Horario',
            timeText: 'Hora seleccionada',
            hourText: 'Hora',
            minuteText: 'Minuto',
            secondText: 'Segundo',
            currentText: 'Ahora',
            closeText: 'Listo',
            ampm: false
        });
    }

    $("#btnGuardarPermisos").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardarPermisos").attr("disabled", true);
    $("#btnGuardarPermisos").on("click", guardar_permisos);
    $("#btnBuscarPermiso").on("click", llenar_check);
    $("#btnLimpiarPermiso").on("click", limpiar);
/////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////

    $(window).bind('resize', function () {
        jQuery("#list_acciones").setGridWidth($('#centro'));
    }).trigger('resize');

    jQuery("#list_acciones").jqGrid({
        url: 'xmlUsuario_acciones.php',
        datatype: 'xml',
        colNames: ['Cód. Usuario', 'CI Usuario', 'Nombres ', 'Apellidos ', 'Usuario', 'Apertura Caja'],
        colModel: [

            {name: 'id_usuario', index: 'id_usuario', editable: true, align: 'center', width: '40', search: false, frozen: true, editoptions: {readonly: 'readonly'}},
            {name: 'ci_usuario', index: 'ci_usuario', editable: true, align: 'center', width: '100', size: '10', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}, editoptions: {maxlength: 10, size: 20, dataInit: function (elem) {
                        $(elem).bind("keypress", function (e) {
                            return numeros(e)
                        })
                    }}},
            {name: 'nombre_usuario', index: 'nombre_usuario', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'apellido_usuario', index: 'apellido_usuario', editable: true, align: 'center', width: '140', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'user', index: 'user', editable: true, align: 'center', width: '140', search: false},
            {
                name: 'por_defecto',
                index: 'por_defecto',
                width: 90,
                align: 'center',
                formatter: function (cellvalue, options, rowObject) {
                    let checked = '';
                    if (cellvalue == 't') {
                        checked = 'checked';
                    }
                    return `<input id='um_por_defecto_${options.rowId}'  ${checked} type="checkbox">`;
                }
            },
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        width: null,
        height: 400,
        pager: jQuery('#pager_acciones'),
        editurl: "procesosUsuarios.php",
        sortname: 'id_usuario',
        shrinkToFit: false,
        sortordezr: 'asc',
        caption: 'Lista Usuarios',
        viewrecords: true,
        afterInsertRow: function (rowid, rowdata, rowelem) {
            $("#um_por_defecto_" + rowid).change(function (e) {
                if (e.target.checked) {
                    cambiarUmPorDefecto(rowdata.id_usuario, 't');
                } else {
                    cambiarUmPorDefecto(rowdata.id_usuario, 'f');
                }
            });
        }
    }).jqGrid('navGrid', '#pager',
            {
                add: true,
                edit: true,
                del: true,
                refresh: true,
                search: true,
                view: true,
                addtext: "Nuevo",
                edittext: "Modificar",
                deltext: "Eliminar"
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



    /* // jQuery("#list").setGridWidth($('#centro').width() - 10);
     //////tabla de usuarios para permisos////////
     $(window).bind('resize', function() {
     jQuery("#list1").setGridWidth($('#izquierda'));
     }).trigger('resize');
     
     jQuery("#list1").jqGrid({
     url: 'xmlUsuarioPermisos.php',
     datatype: 'xml',
     colNames: ['Cod.', 'CI Usuario', 'Nombres Usuario', 'Apellidos Usuario', 'Cargo'],
     colModel: [
     {name: 'id_usuario', index: 'id_usuario', editable: true, align: 'center', width: '50', search: false, frozen: true, editoptions: {readonly: 'readonly'}},
     {name: 'ci_usuario', index: 'ci_usuario', editable: true, align: 'center', width: '100', size: '10', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}, editoptions:{maxlength: 10, size:20,dataInit: function(elem){$(elem).bind("keypress", function(e) {return numeros(e)})}}}, 
     {name: 'nombre_usuario', index: 'nombre_usuario', editable: true, align: 'center', width: '140', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
     {name: 'apellido_usuario', index: 'apellido_usuario', editable: true, align: 'center', width: '140', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
     {name: 'cargo_usuario', index: 'cargo_usuario', width:'100',search: false, align: 'center', editable: true, edittype: "select", editoptions: {value: "1:Administrador;2:Vendedor"}},
     ],
     rowNum: 6,
     rowList: [10, 20, 30],
     width: null,
     height: 400,
     pager: jQuery('#pager1'),
     sortname: 'id_usuario',
     shrinkToFit: false,
     sortordezr: 'asc',
     caption: 'Lista Usuarios',
     loadonce:true,
     scrollrows: true,
     viewrecords: true,
     onSelectRow:llenar_check
     });*/

///////////////////////////////////////////////
    $(window).bind('resize', function () {
        jQuery("#list").setGridWidth($('#centro'));
    }).trigger('resize');

    jQuery("#list").jqGrid({
        url: 'xmlUsuario.php',
        datatype: 'xml',
        colNames: ["Puntos Venta", 'Cód. Usuario', 'CI Usuario', 'Nombres ', 'Apellidos ', 'Dirección', 'Teléfono', 'Celular', 'E-maiL', 'User', 'Clave', 'Cargo', 'Hora Entrada', 'Hora Salida'],
        colModel: [
            {
                name: 'puntos_venta',
                index: 'puntos_venta',
                align: 'center',
                formatter: function (cellvalue, options, rowObject) {
                    return `<button id="add_pv_${cellvalue}" class="btn btn-success btn-xs" type="button"><i class="fa fa-plus"></i></button>`;
                },
                width: 40
            },
            {name: 'id_usuario', index: 'id_usuario', editable: true, align: 'center', width: '40', search: false, frozen: true, editoptions: {readonly: 'readonly'}},
            {name: 'ci_usuario', index: 'ci_usuario', editable: true, align: 'center', width: '80', size: '10', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}, editoptions: {maxlength: 10, size: 20, dataInit: function (elem) {
                        $(elem).bind("keypress", function (e) {
                            return numeros(e)
                        })
                    }}},
            {name: 'nombre_usuario', index: 'nombre_usuario', editable: true, align: 'center', width: '80', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'apellido_usuario', index: 'apellido_usuario', editable: true, align: 'center', width: '80', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'direccion_usuario', index: 'direccion_usuario', editable: true, align: 'center', width: '70', search: false},
            {name: 'telefono_usuario', index: 'telefono_usuario', editable: true, align: 'center', width: '80', search: false, editrules: {required: false}, editoptions: {maxlength: 10, size: 20,
            dataInit: function (elem) {
                        $(elem).bind("keypress", function (e) {
                            return numeros(e)
                        })
                    }}},
            {name: 'celular_usuario', index: 'celular_usuario', editable: true, align: 'center', width: '100', search: false, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}, editoptions: {maxlength: 10, size: 20, dataInit: function (elem) {
                        $(elem).bind("keypress", function (e) {
                            return numeros(e)
                        })
                    }}},
            {name: 'email_usuario', index: 'email_usuario', editable: true, align: 'center', width: '100', search: false, formatter: 'email'},
            {name: 'user', index: 'user', editable: true, align: 'center', width: '80', search: false, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'password_usuario', index: 'password_usuario', editable: true, align: 'center', width: '140', search: false, formoptions: {elmsuffix: " (*)"}, editrules: {edithidden: true, required: true}, edittype: "password", hidden: true},
            {name: 'cargo_usuario', index: 'cargo_usuario', width: '100', search: false, align: 'center', editable: true, edittype: "select", editoptions: {dataUrl: 'retornar_cargos_usuario.php'}},
            {name: 'hora_entrada', index: 'hora_entrada', editable: true, align: 'center', width: '100', search: false, edittype: "select", editoptions: {dataUrl: 'retornar_horas.php'}},
            {name: 'hora_salida', index: 'hora_salida', editable: true, align: 'center', width: '100', search: false, edittype: "select", editoptions: {dataUrl: 'retornar_horas.php'}},
                    //            {name: 'id_empresa', index: 'id_empresa', editable: false, align: 'center', width: '140', search: false, edittype: "select",  editoptions:{ dataUrl: 'retornar_horas.php'}}



        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        width: null,
        height: 400,
        pager: jQuery('#pager'),
        editurl: "procesosUsuarios.php",
        sortname: 'id_usuario',
        shrinkToFit: false,
        sortordezr: 'asc',
        caption: 'Lista Usuarios',
        viewrecords: true,
        afterInsertRow: function (rowid, rowdata, rowelem) {
            $("#add_pv_" + rowid).click(function (e) {
                selectedUsuarioPv = rowid;
                $("#asignar_puntos_venta").dialog("open");
            });
        }
    }).jqGrid('navGrid', '#pager',
            {
                add: true,
                edit: true,
                del: true,
                refresh: true,
                search: true,
                view: true,
                addtext: "Nuevo",
                edittext: "Modificar",
                deltext: "Eliminar"
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



    /* // jQuery("#list").setGridWidth($('#centro').width() - 10);
     //////tabla de usuarios para permisos////////
     $(window).bind('resize', function() {
     jQuery("#list1").setGridWidth($('#izquierda'));
     }).trigger('resize');
     
     jQuery("#list1").jqGrid({
     url: 'xmlUsuarioPermisos.php',
     datatype: 'xml',
     colNames: ['Cod.', 'CI Usuario', 'Nombres Usuario', 'Apellidos Usuario', 'Cargo'],
     colModel: [
     {name: 'id_usuario', index: 'id_usuario', editable: true, align: 'center', width: '50', search: false, frozen: true, editoptions: {readonly: 'readonly'}},
     {name: 'ci_usuario', index: 'ci_usuario', editable: true, align: 'center', width: '100', size: '10', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}, editoptions:{maxlength: 10, size:20,dataInit: function(elem){$(elem).bind("keypress", function(e) {return numeros(e)})}}}, 
     {name: 'nombre_usuario', index: 'nombre_usuario', editable: true, align: 'center', width: '140', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
     {name: 'apellido_usuario', index: 'apellido_usuario', editable: true, align: 'center', width: '140', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
     {name: 'cargo_usuario', index: 'cargo_usuario', width:'100',search: false, align: 'center', editable: true, edittype: "select", editoptions: {value: "1:Administrador;2:Vendedor"}},
     ],
     rowNum: 6,
     rowList: [10, 20, 30],
     width: null,
     height: 400,
     pager: jQuery('#pager1'),
     sortname: 'id_usuario',
     shrinkToFit: false,
     sortordezr: 'asc',
     caption: 'Lista Usuarios',
     loadonce:true,
     scrollrows: true,
     viewrecords: true,
     onSelectRow:llenar_check
     });*/


    $("#busca_usuario").autocomplete({
        source: "buscar_usuario.php?term=" + $("#busca_usuario").val(),
        minLength: 1,
        focus: function (event, ui) {
            $("#busca_usuario").val(ui.item.nombre);
            $("#id_usuarioC").val(ui.item.id_usuario);
            return false;
        },
        select: function (event, ui) {
            $("#busca_usuario").val(ui.item.nombre);
            $("#id_usuarioC").val(ui.item.id_usuario);
            return false;
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<a>" + item.nombre + "</a>")
                .appendTo(ul);
    };
}

function llenar_check() {

    if ($("#busca_usuario").val() == "Admin Admin") {

        $("#ingresoUsu").prop("checked", true);
        $("#permisoUsu").prop("checked", true);
        $("#ingresosUsuarios").prop("checked", true);
        $("#usuarios").prop("checked", true);

        $("#ingresoUsu").prop("disabled", true);
        $("#permisoUsu").prop("disabled", true);
        $("#ingresosUsuarios").prop("disabled", true);
        $("#usuarios").prop("disabled", true);



        $("#btnGuardarPermisos").attr("disabled", false);
        var id_usuario = $("#id_usuarioC").val();
        //limpiar();
        $.ajax({
            type: "POST",
            url: "extraerPermisosUsuarios.php",
            data: "id_usuario=" + id_usuario,
            success: function (data) {
                var val = data;
                if (val != 1) {
                    datos = val.split("*");
                    $("#general input[type=checkbox]").each(function () {





                        for (var i = 0; i < datos.length; i++) {
                            if ($(this).val() == datos[i]) {
                                $(this).attr("checked", true);
                                break;
                            }
                        }
                    });

                } else {
                    alertify.alert("Error");
                }
            }
        });
    } else {

        $("#ingresoUsu").prop("disabled", false);
        $("#permisoUsu").prop("disabled", false);
        $("#ingresosUsuarios").prop("disabled", false);
        $("#usuarios").prop("disabled", false);

        $("#btnGuardarPermisos").attr("disabled", false);
        var id_usuario = $("#id_usuarioC").val();
        //limpiar();
        $.ajax({
            type: "POST",
            url: "extraerPermisosUsuarios.php",
            data: "id_usuario=" + id_usuario,
            success: function (data) {
                var val = data;
                if (val != 1) {
                    datos = val.split("*");
                    $("#general input[type=checkbox]").each(function () {





                        for (var i = 0; i < datos.length; i++) {
                            if ($(this).val() == datos[i]) {
                                $(this).attr("checked", true);
                                break;
                            }
                        }
                    });

                } else {
                    alertify.alert("Error");
                }
            }
        });
    }
}

function limpiar() {
    location.reload();
    /*$("#general input[type=checkbox]").each(function(){
     $(this).attr("checked",false);
     });*/
}

function guardar_permisos() {
    var cadena = "";
    var id = $("#id_usuarioC").val();
    $("input[type=checkbox]:checked").each(function () {
        cadena = cadena + ($(this).val()) + "*";
    });
    $.ajax({
        type: "POST",
        url: "insertarPermisosUsuarios.php",
        data: "id_usuario=" + id + "&permisos=" + cadena + "",
        success: function (data) {
            var val = data;
            if (val != 1) {
                alertify.alert("Permisos guardados correctamente", function () {
                    location.reload();
                });
            } else {
                alertify.alert("Error");
            }
        }
    });
}

function Defecto(e) {
    e.preventDefault();
}


$('ci_usuario').keyup(function () {
    $.ajax({
        type: "POST",
        url: "procesosUsuarios.php",
        data: "cedula=" + $("#ci_usuario").val(),
        success: function (data) {
            var val = data;
            if (val == 1) {
                $('ci_usuario').val("");
                $('ci_usuario').focus();
                alertify.error("Error... El cliente esta registrado");
            } else {
                var numero = $('ci_usuario').val();
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

                if ($("#tipo_docu option:selected").text() === "Cedula") {
                    if (numero.length === 10) {
                        if (nat == true) {
                            if (digitoVerificador != d10) {
                                alertify.error('El número de cédula es incorrecto.');
                                $('ci_usuario').val("");
                            } else {
                                if ($('ci_usuario').val() === "0000000000") {
                                    alertify.error('El número de cédula es incorrecto.');
                                    $('ci_usuario').val("");
                                } else {
                                    alertify.success('El número de cédula es correcto.');
                                }
                            }
                        }
                    }
                }
            }
        }
    });
});

$("#menuParametros").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuParametros").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#parametros").prop("checked", false);
    } else {
        $("#parametros").prop("checked", true);
    }
});

$("#parametros").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuParametros input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuParametros input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

/*$("#generales").change(function () {
 if ($(this).is(':checked')) {
 //$("input[type=checkbox]").prop('checked', true); //todos los check
 $("#menuGenerales input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
 } else {
 //$("input[type=checkbox]").prop('checked', false);//todos los check
 $("#menuGenerales input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
 }
 });*/


$("#menuInventario").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuInventario").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#inventario").prop("checked", false);
    } else {
        $("#inventario").prop("checked", true);
    }
});

$("#inventario").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuInventario input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuInventario input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});
$("#parametrosContables").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuparametrosContables input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuparametrosContables input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});


$("#menuIngresos").children().find(":checkbox").click(function () {

    if ($("#busca_usuario").val() == "Admin Admin") {

        $("#ingresoUsu").prop("disabled", true);
        $("#permisoUsu").prop("disabled", true);
        $("#ingresosUsuarios").prop("disabled", true);
        $("#usuarios").prop("disabled", true);
    } else {
        var cont = 0;
        $("#menuIngresos").children().find(":checkbox").each(function () {
            if ($(this).is(':checked')) {
                cont++;
            }
        });
        if (cont == 0) {
            $("#ingresosUsuarios").prop("checked", false);
        } else {
            $("#ingresosUsuarios").prop("checked", true);
        }
    }
});



$("#ingresosUsuarios").change(function () {

    if ($("#busca_usuario").val() == "Admin Admin") {

        $("#ingresoUsu").prop("disabled", true);
        $("#permisoUsu").prop("disabled", true);
        $("#ingresosUsuarios").prop("disabled", true);
        $("#usuarios").prop("disabled", true);
    } else {


        if ($(this).is(':checked')) {
            //$("input[type=checkbox]").prop('checked', true); //todos los check
            $("#menuIngresos input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
        } else {
            //$("input[type=checkbox]").prop('checked', false);//todos los check
            $("#menuIngresos input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
        }
    }
});

$("#menuUsuarios").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuUsuarios").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#usuarios").prop("checked", false);
    } else {
        $("#usuarios").prop("checked", true);
    }
});

$("#usuarios").change(function () {

    if ($("#busca_usuario").val() == "Admin Admin") {

        $("#ingresoUsu").prop("disabled", true);
        $("#permisoUsu").prop("disabled", true);
        $("#ingresosUsuarios").prop("disabled", true);
        $("#usuarios").prop("disabled", true);
    } else {


        if ($(this).is(':checked')) {
            //$("input[type=checkbox]").prop('checked', true); //todos los check
            $("#menuUsuarios input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
        } else {
            //$("input[type=checkbox]").prop('checked', false);//todos los check
            $("#menuUsuarios input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
        }


    }
});

$("#menuCostos").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuCostos").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#costos").prop("checked", false);
    } else {
        $("#costos").prop("checked", true);
    }
});


$("#costos").change(function () {

    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuCostos input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuCostos input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

$("#menuProcesos").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuProcesos").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#procesos").prop("checked", false);
    } else {
        $("#procesos").prop("checked", true);
    }
});


$("#procesos").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuProcesos input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuProcesos input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

$("#menuCompras").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuCompras").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#compras").prop("checked", false);
    } else {
        $("#compras").prop("checked", true);
    }
});

$("#compras").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuCompras input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuCompras input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

$("#menuVentas").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuVentas").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#ventas").prop("checked", false);
    } else {
        $("#ventas").prop("checked", true);
    }
});

$("#ventas").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuVentas input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuVentas input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

$("#menuCartera").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuCartera").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#cartera").prop("checked", false);
    } else {
        $("#cartera").prop("checked", true);
    }
});

$("#cartera").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuCartera input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuCartera input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

$("#menuTransferencias").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuTransferencias").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#transferencias").prop("checked", false);
    } else {
        $("#transferencias").prop("checked", true);
    }
});

$("#transferencias").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuTransferencias input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuTransferencias input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

$("#menuReportes").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuReportes").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#reportes").prop("checked", false);
    } else {
        $("#reportes").prop("checked", true);
    }
});

$("#reportes").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuReportes input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuReportes input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

$("#menuRepProductos").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuRepProductos").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#repProductos").prop("checked", false);
    } else {
        $("#repProductos").prop("checked", true);
    }
});

$("#menuRepCentCostos").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuRepCentCostos").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#repCentCostos").prop("checked", false);
    } else {
        $("#repCentCostos").prop("checked", true);
    }
});

$("#repProductos").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuRepProductos input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuRepProductos input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});
$("#repCentCostos").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuRepCentCostos input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuRepCentCostos input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

$("#menuRepCompras").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuRepCompras").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#repCompras").prop("checked", false);
    } else {
        $("#repCompras").prop("checked", true);
    }
});

$("#repCompras").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuRepCompras input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuRepCompras input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

$("#menuRepComprasLocales").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuRepComprasLocales").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#repComprasLocales").prop("checked", false);
    } else {
        $("#repComprasLocales").prop("checked", true);
    }
});

$("#repComprasLocales").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuRepComprasLocales input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuRepComprasLocales input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

$("#menuRepVentas").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuRepVentas").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#repVentas").prop("checked", false);
    } else {
        $("#repVentas").prop("checked", true);
    }
});

$("#repVentas").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuRepVentas input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuRepVentas input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

$("#menuRepFlujoCaja").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuRepFlujoCaja").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#repFlujoCaja").prop("checked", false);
    } else {
        $("#repFlujoCaja").prop("checked", true);
    }
});

$("#repFlujoCaja").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuRepFlujoCaja input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuRepFlujoCaja input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

$("#menuRepResumenDe").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuRepResumenDe").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#repResumenDe").prop("checked", false);
    } else {
        $("#repResumenDe").prop("checked", true);
    }
});

$("#repResumenDe").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuRepResumenDe input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuRepResumenDe input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

$("#menuRepAutorizaciones").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuRepAutorizaciones").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#repAutorizaciones").prop("checked", false);
    } else {
        $("#repAutorizaciones").prop("checked", true);
    }
});

$("#repAutorizaciones").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuRepAutorizaciones input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuRepAutorizaciones input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

$("#menuRepCartera").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuRepCartera").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#repCartera").prop("checked", false);
    } else {
        $("#repCartera").prop("checked", true);
    }
});

$("#repCartera").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuRepCartera input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuRepCartera input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

$("#menuRepCuentasCobrar").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuRepCuentasCobrar").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#repCuentasCobrar").prop("checked", false);
    } else {
        $("#repCuentasCobrar").prop("checked", true);
    }
});

$("#repCuentasCobrar").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuRepCuentasCobrar input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuRepCuentasCobrar input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

$("#menuRepCuentasPagar").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuRepCuentasPagar").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#repCuentasPagar").prop("checked", false);
    } else {
        $("#repCuentasPagar").prop("checked", true);
    }
});

$("#repCuentasPagar").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuRepCuentasPagar input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuRepCuentasPagar input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

$("#menuRepGastos").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuRepGastos").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#repGastos").prop("checked", false);
    } else {
        $("#repGastos").prop("checked", true);
    }
});

$("#repGastos").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuRepGastos input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuRepGastos input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

$("#menuExternas").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuExternas").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#externas").prop("checked", false);
    } else {
        $("#externas").prop("checked", true);
    }
});

$("#externas").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuExternas input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuExternas input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

$("#menuRepFCBuscarRet").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuRepFCBuscarRet").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#repFCBuscarRet").prop("checked", false);
    } else {
        $("#repFCBuscarRet").prop("checked", true);
    }
});

$("#repFCBuscarRet").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuRepFCBuscarRet input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuRepFCBuscarRet input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

$("#menuRepRetFactVenta").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuRepRetFactVenta").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#repRetFactVenta").prop("checked", false);
    } else {
        $("#repRetFactVenta").prop("checked", true);
    }
});

$("#repRetFactVenta").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuRepRetFactVenta input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuRepRetFactVenta input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

$("#menuRepTransferencias").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuRepTransferencias").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#repTransferencias").prop("checked", false);
    } else {
        $("#repTransferencias").prop("checked", true);
    }
});

$("#repTransferencias").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuRepTransferencias input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuRepTransferencias input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

$("#menuRepReservaciones").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuRepReservaciones").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#repReservaciones").prop("checked", false);
    } else {
        $("#repReservaciones").prop("checked", true);
    }
});

$("#repReservaciones").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuRepReservaciones input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuRepReservaciones input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

$("#menuMantenimiento").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuMantenimiento").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#mantenimiento").prop("checked", false);
    } else {
        $("#mantenimiento").prop("checked", true);
    }
});

$("#mantenimiento").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuMantenimiento input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuMantenimiento input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});
$("#nomina").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menunomina input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menunomina input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});


$("#menuKardex").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuKardex").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#kardex").prop("checked", false);
    } else {
        $("#kardex").prop("checked", true);
    }
});

$("#kardex").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuKardex input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuKardex input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

$("#menuRepBalances").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuRepBalances").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#repBalances").prop("checked", false);
    } else {
        $("#repBalances").prop("checked", true);
    }
});

$("#menuRepConta").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuRepConta").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#repConta").prop("checked", false);
    } else {
        $("#repConta").prop("checked", true);
    }
});

$("#repConta").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuRepConta input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuRepConta input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

$("#repBalances").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuRepBalances input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuRepBalances input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

$("#menuReservaciones").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuReservaciones").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#reservaciones").prop("checked", false);
    } else {
        $("#reservaciones").prop("checked", true);
    }
});

$("#reservaciones").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuReservaciones input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuReservaciones input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});


$("#menuRepOrdenes").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuRepOrdenes").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#repOrdenes").prop("checked", false);
    } else {
        $("#repOrdenes").prop("checked", true);
    }
});

$("#repOrdenes").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuRepOrdenes input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuRepOrdenes input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});

///#repMantenimiento///
$("#menuRepMantenimiento").children().find(":checkbox").click(function () {
    var cont = 0;
    $("#menuRepMantenimiento").children().find(":checkbox").each(function () {
        if ($(this).is(':checked')) {
            cont++;
        }
    });
    if (cont == 0) {
        $("#repMantenimientos").prop("checked", false);
    } else {
        $("#repMantenimientos").prop("checked", true);
    }
});

$("#repMantenimientos").change(function () {
    if ($(this).is(':checked')) {
        //$("input[type=checkbox]").prop('checked', true); //todos los check
        $("#menuRepMantenimiento input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
    } else {
        //$("input[type=checkbox]").prop('checked', false);//todos los check
        $("#menuRepMantenimiento input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
    }
});
///#repMantenimiento///

///puntos venta usuarios
function initDalogAddPv() {
    $("#asignar_puntos_venta").dialog({
        autoOpen: false,
        resizable: false,
        width: 650,
        height: 170,
        modal: true,
        position: "center",
        open: function (event, ui) {
            obtenerPuntosVentaUsuario(selectedUsuarioPv);
        },
        close: function (event, ui) {
            selectedUsuarioPv = null;
        },
        dialogClass: 'fixed-dialog',
        title: "Asignar Pundos de Venta"
    });
}

function obtenerPuntosVenta() {
    $.ajax({
        url: "obtener_puntos_venta.php",
        method: "GET",
        dataType: "json",
        success: function (data) {
            $("#puntos_venta").empty();
            data.forEach(el => {
                $("#puntos_venta").append(`<option value="${el.id_punto_venta}">${el.nombre_punto}</option>`);
            });
        }
    });
}

function guardarPuntosVenta(id_usuario, id_pv) {
    $.ajax({
        url: "gurdar_punto_venta_usuario.php",
        method: "POST",
        data: {
            id_usuario,
            id_pv
        },
        success: function (data) {
            alertify.success("Guardado");
        }
    });
}

function borrarPuntosVenta(id_usuario, id_pv) {
    $.ajax({
        url: "borrar_punto_venta_usuario.php",
        method: "POST",
        data: {
            id_usuario,
            id_pv
        },
        success: function (data) {
            alertify.success("Borrado");
        }
    });
}

function obtenerPuntosVentaUsuario(id_usuario) {
    $.ajax({
        url: "obtener_puntos_venta_usuario.php",
        method: "GET",
        dataType: "json",
        data: {
            id_usuario
        },
        success: function (data) {
            $("#puntos_venta").val(data.map(el => el.id_punto_venta));
            $('#puntos_venta').trigger('change');
        }
    });
}

function initPuntosVenta() {
    obtenerPuntosVenta();
    $("#puntos_venta").select2();
    $('#puntos_venta').on('select2:select', function (e) {
        guardarPuntosVenta(selectedUsuarioPv, e.params.data.id);
    });
    $('#puntos_venta').on('select2:unselect', function (e) {
        borrarPuntosVenta(selectedUsuarioPv, e.params.data.id);
    });

}