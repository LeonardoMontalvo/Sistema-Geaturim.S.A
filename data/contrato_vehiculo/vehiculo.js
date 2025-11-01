$(document).ready(inicio);

var dialogos_tv =
        {
            autoOpen: false,
            resizable: false,
            width: 310,
            height: 180,
            modal: true
        };

var dialogos_m =
        {
            autoOpen: false,
            resizable: false,
            width: 310,
            height: 180,
            modal: true
        };

function inicio() {
    tablaRegistro();
    obtenerTiposVehiculo();

    $("#tipo_vehiculo").dialog(dialogos_tv);
    $("#marca").dialog(dialogos_m);

    $('#form_tv').submit(function (e) {
        e.preventDefault();
    });
    $('#form_m').submit(function (e) {
        e.preventDefault();
    });

    $('#nombre_tv').keydown(function (e) {
        if (e.keyCode == 13) {
            $('#btnGuardarTV').click();
        }
    });
    $('#nombre_m').keydown(function (e) {
        if (e.keyCode == 13) {
            $('#btnGuardarM').click();
        }
    });

    $('#btnGuardarTV').click(function (e) {
        enviarFormTV('form_tv').then(function (data) {
            if ($('#id_tipo_vehiculo')) {
                $('#id_tipo_vehiculo').empty();
                obtenerTiposVehiculo().then(function (data1) {
                    data1.forEach(function (el) {
                        if (data == el.id_tipo_vehiculo) {
                            $('#id_tipo_vehiculo').append(`<option selected value="${el.id_tipo_vehiculo}" role="option">${el.nombre}</option>`);
                        } else {
                            $('#id_tipo_vehiculo').append(`<option value="${el.id_tipo_vehiculo}" role="option">${el.nombre}</option>`);
                        }
                    });
                });
            }
            $('#tipo_vehiculo').trigger('reset');
            $("#tipo_vehiculo").dialog('close');
        });
    });

    $('#btnGuardarM').click(function (e) {
        enviarFormM('form_m').then(function (data) {
            if ($('#id_marca')) {
                $('#id_marca').empty();
                obtenerMarcasVehiculo().then(function (data1) {
                    data1.forEach(function (el) {
                        if (data == el.id_marca) {
                            $('#id_marca').append(`<option selected value="${el.id_marca}" role="option">${el.nombre}</option>`);
                        } else {
                            $('#id_marca').append(`<option value="${el.id_marca}" role="option">${el.nombre}</option>`);
                        }
                    });
                });
            }
            $('#marca').trigger('reset');
            $("#marca").dialog('close');
        });
    });
}

function tablaRegistro() {
    jQuery("#list").jqGrid({
        url: 'xmlVehiculo.php',
        datatype: 'xml',
        colNames: ['id_vehiculo', 'Placa', 'Tipo', 'Marca', 'Año', 'Modelo', 'Capacidad Pasajeros'],
        colModel: [
            {
                name: 'id_vehiculo',
                index: 'id_vehiculo',
                editable: true,
                align: 'center',
                width: '100',
                hidden: true
            },
            {
                name: 'placa',
                index: 'placa',
                editable: true,
                align: 'center',
                width: '100',
                search: true,
                frozen: true,
                formoptions: {elmsuffix: " (*)"},
                editrules: {required: true},
                searchoptions: {sopt: ["cn", "eq"]}
            },
            {
                name: 'id_tipo_vehiculo',
                index: 'id_tipo_vehiculo',
                editable: true,
                align: 'center',
                width: '100',
                search: false,
                frozen: true,
                formoptions: {elmsuffix: " (*)"},
                editrules: {required: true},
                //searchoptions: {sopt: ["cn", "eq"]},
                edittype: "select",
                editoptions: {
                    dataInit: function (elem) {
                        console.log(elem);
                        obtenerTiposVehiculo().then(function (data) {
                            var btnAgregarTV = $(`<button style="margin-left:5px" class="btn btn-primary" id="btnAgregarTV"><i class="fa fa-plus"></i></button>`);
                            btnAgregarTV.off('click');
                            btnAgregarTV.click(function (e) {
                                e.preventDefault();
                                $("#tipo_vehiculo").dialog('open');
                            });
                            $(elem).parent().append(btnAgregarTV);
                            $(elem).attr('id', 'id_tipo_vehiculo');
                            $(elem).attr('name', 'id_tipo_vehiculo');
                            data.forEach(function (el) {
                                $(elem).append(`<option value="${el.id_tipo_vehiculo}" role="option">${el.nombre}</option>`);
                            });
                        });
                    }
                }
            },
            {
                name: 'id_marca',
                index: 'id_marca',
                editable: true,
                align: 'center',
                width: '100',
                search: false,
                frozen: true,
                formoptions: {elmsuffix: " (*)"},
                editrules: {required: true},
                //searchoptions: {sopt: ["cn", "eq"]},
                edittype: "select",
                editoptions: {
                    dataInit: function (elem) {
                        console.log(elem);
                        obtenerMarcasVehiculo().then(function (data) {
                            var btnAgregarTV = $(`<button style="margin-left:5px" class="btn btn-primary" id="btnAgregarM"><i class="fa fa-plus"></i></button>`);
                            btnAgregarTV.off('click');
                            btnAgregarTV.click(function (e) {
                                e.preventDefault();
                                $("#marca").dialog('open');
                            });
                            $(elem).parent().append(btnAgregarTV);
                            $(elem).attr('id', 'id_marca');
                            $(elem).attr('name', 'id_marca');
                            data.forEach(function (el) {
                                $(elem).append(`<option value="${el.id_marca}" role="option">${el.nombre}</option>`);
                            });
                        });
                    }
                }
            },
            {
                name: 'anio',
                index: 'anio',
                editable: true,
                align: 'center',
                width: '50',
                search: false,
                frozen: true,
                editoptions: {
                    dataInit: function (elem) {
                        $(elem).on("keypress", function (e) {
                            return numeros(e)
                        })
                    }
                },
            },
            {
                name: 'modelo',
                index: 'modelo',
                editable: true,
                align: 'center',
                width: '100',
                search: false,
                frozen: true
            },
            {
                name: 'capacidad_pasajeros',
                index: 'capacidad_pasajeros',
                editable: true,
                align: 'center',
                width: '180',
                frozen: true,
                search: false,
                editoptions: {
                    dataInit: function (elem) {
                        $(elem).on("keypress", function (e) {
                            return numeros(e)
                        })
                    }
                },
            },
        ],
        rowNum: 20,
        rowList: [10, 20, 30],
        width: null,
        height: 400,
        pager: jQuery('#pager'),
        editurl: "procesosVehiculo.php",
        sortname: 'id_vehiculo',
        shrinkToFit: false,
        sortordezr: 'asc',
        caption: 'Lista Vehículos',
        viewrecords: true
    }).jqGrid('navGrid', '#pager',
            {
                add: true,
                edit: true,
                del: true,
                refresh: true,
                search: true,
                view: false,
                addtext: "Nuevo",
                edittext: "Modificar",
                deltext: "Eliminar"
            },
            {
                recreateForm: true,
                closeAfterEdit: true,
                checkOnUpdate: true,
                reloadAfterSubmit: true,
                closeOnEscape: true
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
}

function numeros(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla == 8)
        return true;
    patron = /\d/;
    te = String.fromCharCode(tecla);
    return patron.test(te);
}

function obtenerTiposVehiculo() {
    return $.ajax({
        url: "procesosTipoVehiculo.php",
        type: 'post',
        data: {oper: 'buscartodo'},
        dataType: 'JSON'
    });
}

function obtenerMarcasVehiculo() {
    return $.ajax({
        url: "procesosMarcaVehiculo.php",
        type: 'post',
        data: {oper: 'buscartodo'},
        dataType: 'JSON'
    });
}

function validarForm(formId) {
    var form = document.getElementById(formId);
    var val = form.checkValidity();
    if (val) {
        return true;
    } else {
        $("#submit_" + formId).click();
    }
    return false;
}

function enviarFormTV(formId) {
    if (!validarForm(formId)) {
        return;
    }
    var formd = new FormData(document.getElementById(formId));
    formd.append('oper', 'add');
    return $.ajax({
        url: "procesosTipoVehiculo.php",
        type: "POST",
        dataType: "JSON",
        cache: false,
        processData: false,
        contentType: false,
        data: formd,
    });
}

function enviarFormM(formId) {
    if (!validarForm(formId)) {
        return;
    }
    var formd = new FormData(document.getElementById(formId));
    formd.append('oper', 'add');
    return $.ajax({
        url: "procesosMarcaVehiculo.php",
        type: "POST",
        dataType: "JSON",
        cache: false,
        processData: false,
        contentType: false,
        data: formd,
    });
}
