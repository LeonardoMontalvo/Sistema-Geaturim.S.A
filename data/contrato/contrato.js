$(document).ready(inicio);

var dialogos_categoria =
{
    autoOpen: false,
    resizable: false,
    width: 230,
    height: 180,
    modal: true
};

var dialogos_contrato =
{
    autoOpen: false,
    resizable: false,
    width: 860,
    height: 350,
    modal: true
};

var vehiculos_conductor = [],
    operaciones = [];
var select2Cliente,
    select2Vehiculo,
    select2Conductor,
    select2Lugar1,
    select2Lugar2;
var gridVehiculos,
    gridContratos,
    gridOperaciones;
var id_contrato = 0;

function inicio() {
    inicializarSelects();
    tablaRegistroVehiculos();
    tablaBusquedaContrato();
    tablaRegistroOperaciones();

    var fechaActual = new Date();
    var fechaTexto = fechaActual.getFullYear() + "-" + ("0" + (fechaActual.getMonth() + 1)).slice(-2) + "-" + ("0" + fechaActual.getDate()).slice(-2);
    $("#fecha_contrato").val(fechaTexto);

    $("#lugar").dialog(dialogos_categoria);
    $("#contratos").dialog(dialogos_contrato);

    obtenerNroContrato().then(function (data) {
        $("#nro_contrato").val(data);
    });

    $("#btnAgregarVehiculo").click(function (e) {
        e.preventDefault();
        agregarVehiculoConductor();
    });
    $("#btnNuevo").click(function (e) {
        e.preventDefault();
        location.reload();
    });
    $("#btnBuscar").click(function (e) {
        e.preventDefault();
        $("#contratos").dialog("open");
    });
    $('#btnGuardar').click(function (e) {
        e.preventDefault();
        guardarContrato();
    });
    $("#btnModificar").click(function (e) {
        e.preventDefault();
        modificarContrato();
    }).hide();
    $("#btnEliminar").click(function (e) {
        e.preventDefault();
        eliminarContrato();
    }).hide();
    $("#btnImprimirC").click(function (e) {
        e.preventDefault();
        //imprimir("../../reportes/contrato.php", "nro_contrato=" + $("#nro_contrato").val());
    }).hide();
    $("#btnImprimirH").click(function (e) {
        e.preventDefault();
        //imprimir("../../reportes/hoja_de_ruta.php", "nro_contrato=" + $("#nro_contrato").val());
    }).hide();
    $("#btnAgregarOperacion").click(function (e) {
        e.preventDefault();
        guardarOperacion();
    });

    $("#collapseVehiculo").on("shown.bs.collapse", function () {
        gridVehiculos.jqGrid('setGridWidth', parseInt($('#div_tabla_v')[0].offsetWidth) - 30);
    });

    $('.nav-tabs a[href="#tab_operacion"]').on("shown.bs.tab", function () {
        gridOperaciones.jqGrid('setGridWidth', parseInt($('#div_tabla_o')[0].offsetWidth) - 30);
        gridOperaciones.trigger("reloadGrid");
    });

    $('#nombre_lugar').keydown(function (e) {
        if (e.keyCode == 13) {
            $('#btnGuardarLugar').click();
        }
    });

    $('#descripcion').keydown(function (e) {
        if (e.keyCode == 13) {
            $('#btnAgregarOperacion').click();
        }
    });

    $('#btnAgregarLugarO').click(function (e) {
        e.preventDefault();
        $("#lugar").dialog('open');
        $('#btnGuardarLugar').off('click');
        $('#btnGuardarLugar').click(function (e) {
            e.preventDefault();
            enviarForm('form_lugar', 'add', 'procesosLugar.php').then(function (data) {
                if (data > 0) {
                    cargarOpcionSelectLugar('id_lugar_origen', data);
                }
            });
        });
    });
    $('#btnAgregarLugarD').click(function (e) {
        e.preventDefault();
        $("#lugar").dialog('open');
        $('#btnGuardarLugar').off('click');
        $('#btnGuardarLugar').click(function (e) {
            e.preventDefault();
            enviarForm('form_lugar', 'add', 'procesosLugar.php').then(function (data) {
                if (data > 0) {
                    cargarOpcionSelectLugar('id_lugar_destino', data);
                }
            });
        });
    });

    $('#form_contrato').submit(function (e) {
        e.preventDefault();
    });
    $('#form_vehiculo').submit(function (e) {
        e.preventDefault();
    });
    $('#form_ruta').submit(function (e) {
        e.preventDefault();
    });
    $('#form_lugar').submit(function (e) {
        e.preventDefault();
    });
    $('#form_operacion').submit(function (e) {
        e.preventDefault();
    });

    $("#fecha_salida").change(function (e) {
        $("#fecha_retorno").attr("min", $(this).val());
        $("#nro_dias").val(calcularDias() + 1);
    });

    $("#fecha_retorno").change(function (e) {
        $("#nro_dias").val(calcularDias() + 1);
    });

    $("#valor").change(function (e) {
        $("#restante").val(obtenerSaldoRestante());
    });

}

function selectLayout(idsel, url, oper, id, text, textTemplate = null) {
    var select = $("#" + idsel)
        .select2({
            language: "es",
            minimumInputLength: 1,
            placeholder: "Seleccione opción",
            width: "resolve",
            dropdownAutoWidth: true,
            allowClear: true,
            ajax: {
                url: url,
                type: "POST",
                dataType: "json",
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1,
                        oper: oper
                    };

                    // Query parameters will be ?search=[term]&page=[page]
                    return query;
                },
                processResults: function (data, params) {
                    // Tranforms the top-level key of the response object from 'items' to 'results'
                    //paginación
                    params.page = params.page || 1;
                    data.registros.map(function (el) {
                        el.id = el[id];
                        if (textTemplate) {
                            el.text = textTemplate(el)
                        } else {
                            el.text = el[text];
                        }
                        return el;
                    });
                    return {
                        results: data.registros,
                        pagination: {
                            more: params.page * 10 < data.totalRegistros
                        }
                    };
                }
            }
        });
    return select;
}

function inicializarSelects() {
    select2Cliente = selectLayout(
        "id_cliente",
        "procesosCliente.php",
        'buscartodopaginado',
        'id_cliente',
        'nombres_cli');
    select2Vehiculo = selectLayout(
        "id_vehiculo",
        "../contrato_vehiculo/procesosVehiculo.php",
        'buscartodopaginado',
        'id_vehiculo',
        'placa');
    select2Lugar1 = selectLayout(
        "id_lugar_origen",
        "procesosLugar.php",
        'buscartodopaginado',
        'id_lugar',
        'nombre');
    select2Lugar2 = selectLayout(
        "id_lugar_destino",
        "procesosLugar.php",
        'buscartodopaginado',
        'id_lugar',
        'nombre');
    select2Conductor = selectLayout(
        "id_conductor",
        "../contrato_conductor/procesosConductor.php",
        'buscartodopaginado',
        'id_conductor',
        '',
        function (el) {
            return el.nombres + ' ' + el.apellidos;
        });
}

function validarForm(formId, callback) {
    var form = document.getElementById(formId);
    var val = form.checkValidity();
    if (val) {
        return true;
    } else {
        if (typeof callback == 'function') {
            callback();
        }
        $("#submit_" + formId).click();
    }
    return false;
}

function enviarForm(formId, oper, url, adicionales) {
    if (!validarForm(formId)) {
        return;
    }
    var formd = new FormData(document.getElementById(formId));
    formd.append('oper', oper);

    if (adicionales) {
        let aux = Object.keys(adicionales);
        if (aux.length > 0) {
            aux.forEach(function (el) {
                formd.append(el, adicionales[el]);
            });
        }
    }

    return $.ajax({
        url: url,
        type: "POST",
        dataType: "JSON",
        cache: false,
        processData: false,
        contentType: false,
        data: formd,
    });
}

function resetearFormulario() {
    $("#form_contrato")[0].reset();
    $("#form_ruta")[0].reset();
    $("#form_vehiculo")[0].reset();
    select2Cliente.val(null).trigger('change');
    select2Vehiculo.val(null).trigger('change');
    select2Conductor.val(null).trigger('change');
    select2Lugar1.val(null).trigger('change');
    select2Lugar2.val(null).trigger('change');
    vehiculos_conductor = [];
    gridVehiculos.jqGrid("clearGridData");
    tablaRegistroVehiculos();
    $('#error_v').css('display', '');
    if ($('#collapseVehiculo').hasClass('in')) {
        $('#collapseVehiculo').collapse('hide');
    }
    if ($('#collapseRuta').hasClass('in')) {
        $('#collapseRuta').collapse('hide');
    }
    id_contrato = 0;
    obtenerNroContrato().then(function (data) {
        $("#nro_contrato").val(data);
    });
    $("#nro_contrato").prop("disabled", false);
    $("#btnModificar").hide();
    $("#btnEliminar").hide();
    $("#btnImprimirC").hide();
    $("#btnImprimirH").hide();
    $("#btnGuardar").show();
    var fechaActual = new Date();
    $("#fecha_contrato").val(fechaActual.getFullYear() + "-" + fechaActual.getMonth() + "-" + fechaActual.getDate());
    gridContratos.trigger("reloadGrid");
}

function cargarOpcionSelectLugar(idselect, idlugar) {
    $.ajax({
        url: 'procesosLugar.php',
        type: 'POST',
        dataType: 'JSON',
        data: {
            oper: 'buscarxid',
            id_lugar: idlugar
        },
        success: function (data, textStatus, jqXHR) {
            var option = new Option(data[0].nombre, data[0].id_lugar, true, true);
            $("#" + idselect)
                .append(option)
                .trigger("change");

            // manually trigger the `select2:select` event
            $("#" + idselect).trigger({
                type: "select2:select",
                params: {
                    data: data[0]
                }
            });
        },
        complete: function (data, textStatus) {
            $('#form_lugar').trigger('reset');
            $("#lugar").dialog('close');
        }
    });
}

function tablaRegistroVehiculos() {
    gridVehiculos = jQuery("#list").jqGrid({
        datatype: "local",
        data: vehiculos_conductor,
        colNames: ['', '#', 'Vehículo', 'Conductor', 'id_vehiculo', 'id_conductor'],
        colModel: [
            {
                name: 'myac',
                width: 50,
                fixed: true,
                sortable: false,
                resize: false,
                formatter: 'actions',
                formatoptions: { keys: false, delbutton: true, editbutton: false }
            },
            { name: 'id', index: 'id', width: 60, sortable: false },
            { name: 'vehiculo', index: 'vehiculo', width: 90, sortable: false },
            { name: 'conductor', index: 'conductor', width: 100, sortable: false },
            { name: 'id_vehiculo', index: 'id_vehiculo', width: 100, hidden: true },
            { name: 'id_conductor', index: 'id_conductor', width: 100, hidden: true },
        ],
        caption: "Lista de vehículos",
        width: $('#div_tabla_v')[0].offsetWidth - 30,
        height: null,
        rowNum: 10,
        pager: jQuery('#pager'),
        viewrecords: true,
        delOptions: {
            modal: true,
            jqModal: true,
            onclickSubmit: quitarVehiculoConductor,
            processing: true
        }
    });
}

function agregarVehiculoConductor() {

    if (!validarForm("form_vehiculo")) {
        return;
    }
    var dataVehiculo = select2Vehiculo.select2("data")[0];
    var dataConductor = select2Conductor.select2("data")[0];

    var tmpv = vehiculos_conductor.find(function (el) {
        return (el.id_vehiculo == dataVehiculo.id) && (el.id_conductor == dataConductor.id);
    });

    if (tmpv) {
        select2Vehiculo.val(null).trigger('change');
        select2Conductor.val(null).trigger('change');

        return;
    }

    var obj = {
        id: vehiculos_conductor.length + 1,
        vehiculo: "Placa: " + dataVehiculo.text + "<br>Capacidad P.: " + dataVehiculo.capacidad_pasajeros + "<br>Año: " + dataVehiculo.anio,
        conductor: "Nombre: " + dataConductor.text + "<br>CI: " + dataConductor.dni,
        id_vehiculo: dataVehiculo.id,
        id_conductor: dataConductor.id
    }

    vehiculos_conductor.push(obj);

    gridVehiculos.jqGrid('setGridParam',
        {
            datatype: 'local',
            data: vehiculos_conductor
        })
        .trigger("reloadGrid");

    select2Vehiculo.val(null).trigger('change');
    select2Conductor.val(null).trigger('change');

    if (vehiculos_conductor.length > 0) {
        $('#error_v').css('display', 'none');
    } else {
        $('#error_v').css('display', '');
    }
}

function quitarVehiculoConductor(options, rowid) {
    var grid_p = gridVehiculos[0].p;
    var newpage = grid_p.page;
    var grid_id = $.jgrid.jqID(gridVehiculos[0].id);

    options.processing = true;

    if (rowid != null) {
        gridVehiculos.delRowData(rowid);

        vehiculos_conductor.splice(0, 1);
        vehiculos_conductor = vehiculos_conductor.map(function (el, i) {
            el.id = (i + 1);
            return el;
        });

        if (vehiculos_conductor.length > 0) {
            $('#error_v').css('display', 'none');
        } else {
            $('#error_v').css('display', '');
        }

        $.jgrid.hideModal("#delmod" + grid_id,
            {
                gb: "#gbox_" + grid_id,
                jqm: options.jqModal, onClose: options.onClose
            });
    } else {
        alert("Please Select Row to delete!");
    }
    if (grid_p.lastpage > 1) {// on the multipage grid reload the grid
        if (grid_p.reccount === 0 && newpage === grid_p.lastpage) {
            // if after deliting there are no rows on the current page
            // which is the last page of the grid
            newpage--; // go to the previous page
        }
    }
    // reload grid to make the row from the next page visable.
    gridVehiculos.jqGrid('setGridParam',
        {
            datatype: 'local',
            data: vehiculos_conductor
        })
        .trigger("reloadGrid", [{ page: newpage }]);
    return true;
}

function guardarContrato() {
    if (!validarForm('form_contrato')) {
        return;
    }
    let showRutas = () => {
        if ($('#collapseVehiculo').hasClass('in')) {
            $('#collapseVehiculo').collapse('hide');
        }
        $('#collapseRuta').collapse('show');

    };
    if (!validarForm('form_ruta', showRutas)) {
        return;
    }
    if (vehiculos_conductor.length <= 0) {
        if ($('#collapseRuta').hasClass('in')) {
            $('#collapseRuta').collapse('hide');
        }
        $('#collapseVehiculo').collapse('show');
        return;
    }

    var formr = Object.fromEntries(new FormData($('#form_ruta')[0]));
    var formc = Object.fromEntries(new FormData($('#form_contrato')[0]));
    objenv = Object.assign(formr, formc);
    objenv['detalles'] = vehiculos_conductor;
    objenv['oper'] = 'add';
    if ($("#nro_contrato").prop("disabled")) {
        objenv['nro_contrato'] = $("#nro_contrato").val();
    }
    $.ajax({
        url: 'procesosContrato.php',
        type: 'POST',
        dataType: 'JSON',
        data: objenv
    })
        .then(function (data) {
            if (Object.keys(data).length > 0) {
                if (data.tipo == 'contrato') {
                    if (data.res == 0) {
                        alertify.error("No se pudo guardar el contrato.");
                    } else if (data.res < 0) {
                        alertify.error("El número de contrato indicado ya existe.");
                    }
                }
                if (data.tipo == 'ruta') {
                    if (data.res == 0) {
                        alertify.error("No se pudo guardar el contrato, problema al guardar la ruta.");
                    }
                }
                if (data.tipo == 'vehiculo_contrato') {
                    if (data.res == 0) {
                        alertify.error("No se pudo guardar el contrato, problema al asignar los vehículos.");
                    }
                }
                if (data.res > 0) {
                    alertify.alert("Contrato guardado correctamente.", function (e) {
                        if (e) {
                            //location.reload();
                            resetearFormulario();
                            cargarContrato(data.res);
                        }
                    });
                }
            }
        })
        .always(function (jqXHR, textStatus, errorThrown) {
            $('#btnGuardar').prop('disabled', false);
        });
}

function modificarContrato() {
    if (id_contrato <= 0) {
        alertify.alert("Seleccione un contrato para poder continuar.");
        return;
    }
    if (!validarForm('form_contrato')) {
        return;
    }
    let showRutas = function () {
        if ($('#collapseVehiculo').hasClass('in')) {
            $('#collapseVehiculo').collapse('hide');
        }
        $('#collapseRuta').collapse('show');

    };
    if (!validarForm('form_ruta', showRutas)) {
        return;
    }
    if (vehiculos_conductor.length <= 0) {
        if ($('#collapseRuta').hasClass('in')) {
            $('#collapseRuta').collapse('hide');
        }
        $('#collapseVehiculo').collapse('show');
        return;
    }

    var formr = Object.fromEntries(new FormData($('#form_ruta')[0]));
    var formc = Object.fromEntries(new FormData($('#form_contrato')[0]));
    objenv = Object.assign(formr, formc);
    objenv['detalles'] = vehiculos_conductor;
    objenv['oper'] = 'edit';
    if ($("#nro_contrato").prop("disabled")) {
        objenv['nro_contrato'] = $("#nro_contrato").val();
    }
    objenv['id_contrato'] = id_contrato;
    $.ajax({
        url: 'procesosContrato.php',
        type: 'POST',
        dataType: 'JSON',
        data: objenv
    })
        .then(function (data) {
            if (Object.keys(data).length > 0) {
                if (data.tipo == 'contrato') {
                    if (data.res == 0) {
                        alertify.error("No se pudo guardar el contrato.");
                    } else if (data.res < 0) {
                        alertify.error("El número de contrato indicado ya existe.");
                    }
                }
                if (data.tipo == 'ruta') {
                    if (data.res == 0) {
                        alertify.error("No se pudo guardar el contrato, problema al guardar la ruta.");
                    }
                }
                if (data.tipo == 'vehiculo_contrato') {
                    if (data.res == 0) {
                        alertify.error("No se pudo guardar el contrato, problema al asignar los vehículos.");
                    }
                }
                if (data.res > 0) {

                    alertify.alert("Contrato modificado correctamente.");
                }
            }
        })
        .always(function (jqXHR, textStatus, errorThrown) {
            $('#btnModificar').prop('disabled', false);
        });
}

function eliminarContrato() {
    if (id_contrato <= 0) {
        alertify.alert("Seleccione un contrato para poder continuar.");
        return;
    }
    alertify.confirm("¿Está segur@ de eliminar este contrato?", function (e) {
        if (e) {
            $.ajax({
                url: "procesosContrato.php",
                type: "POST",
                data: { oper: "del", id_contrato: id_contrato }
            }).then(function (data) {
                if (data > 0) {
                    alertify.alert("Contrato eliminado.", function (e) {
                        if (e) {
                            location.reload();
                        }
                    });
                }
            });
        }
    });

}

function obtenerNroContrato() {
    return $.ajax({
        url: "procesosContrato.php",
        type: "POST",
        dataType: "JSON",
        data: { oper: 'siguientenrocontrato' }
    });
}

function tablaBusquedaContrato() {
    gridContratos = jQuery("#listContratos").jqGrid({
        url: 'xmlContrato.php',
        datatype: 'xml',
        colNames: ['NRO. VIAJE', 'NOMBRE CLIENTE', 'ID. CLIENTE', 'FECHA CONTRATO', 'VALOR FLETE'],
        colModel: [
            { name: 'nro_viaje', index: 'nro_viaje', width: 110, sortable: false, align: 'center', searchoptions: { sopt: ["eq"] } },
            { name: 'nombres_cli', index: 'nombres_cli', width: 200, sortable: false, align: 'center', searchoptions: { sopt: ["cn", "eq"] } },
            { name: 'identificacion', index: 'identificacion', width: 120, sortable: false, align: 'center', searchoptions: { sopt: ["cn", "eq"] } },
            { name: 'fecha_contrato', index: 'fecha_contrato', width: 120, sortable: false, align: 'center', search: false },
            { name: 'valor', index: 'valor', width: 120, sortable: false, align: 'center', search: false },
        ],
        rowNum: 10,
        width: 830,
        height: 200,
        rowList: [10, 20, 30],
        pager: jQuery('#pagerContratos'),
        sortname: 'nro_viaje',
        shrinkToFit: false,
        sortorder: 'desc',
        caption: 'Lista de Fletes',
        viewrecords: true,
        ondblClickRow: function (rowid, iRow, iCol, e) {
            cargarContrato(rowid);
            $('#contratos').dialog("close");
        }
    }).jqGrid('navGrid', '#pagerContratos',
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
        }
    );
}

function cargarContrato(id) {
    $.ajax({
        url: 'procesosContrato.php',
        type: 'POST',
        dataType: 'JSON',
        data: { 'oper': 'buscarxid', 'id_contrato': id }
    }).then(function (data) {
        if (data.length > 0) {
            cargarInfoContrato(data[0]);
            cargarInfoRutaContrato(data[0]);
            cargarVehiculosContrato(data[0]);
            id_contrato = id;
            alertify.success('Información del contrato cardada correctamente.');

            $("#btnModificar").show();
            $("#btnEliminar").show();
            // $("#btnImprimirC").show();
            // $("#btnImprimirH").show();
            $("#btnGuardar").hide();
            $("#btnBuscar").hide();
            gridOperaciones.jqGrid('setGridParam', { url: "xmlContratoOperaciones.php?id_contrato=" + id_contrato, page: 1 }).trigger("reloadGrid");

        }
    });
}

function cargarInfoContrato(data) {
    var nodelist = $("#form_contrato")[0].elements;
    for (var item in data) {
        if (nodelist.namedItem(item)) {
            nodelist.namedItem(item).value = data[item];
            $(nodelist.namedItem(item)).trigger("change");
        }
    }
    $("#nro_contrato").prop("disabled", true);
    $.ajax({
        url: 'procesosCliente.php',
        type: 'POST',
        dataType: 'JSON',
        data: { 'oper': 'buscarxid', 'id_cliente': data.id_cliente }
    }).then(function (data) {
        if (data.length > 0) {
            var obj = data[0];
            var opt = new Option(obj.nombres_cli, obj.id_cliente, true, true);
            select2Cliente.append(opt).trigger('change');
            /*select2Cliente.trigger({
             type: 'select2:select',
             params: {
             data: {text:'abc',id:1}
             }
             });*/
        }
    });
}

function cargarInfoRutaContrato(data) {
    $.ajax({
        url: 'procesosRuta.php',
        type: 'POST',
        dataType: 'JSON',
        data: { 'oper': 'buscarxidcontrato', 'id_contrato': data.id_flete }
    }).then(function (data) {
        if (data.length > 0) {
            var obj = data[0];
            var nodelist = $("#form_ruta")[0].elements;
            for (var item in obj) {
                if (nodelist.namedItem(item)) {
                    nodelist.namedItem(item).value = obj[item];
                    $(nodelist.namedItem(item)).trigger("change");
                }
            }
            $.ajax({
                url: 'procesosLugar.php',
                type: 'POST',
                dataType: 'JSON',
                data: { 'oper': 'buscarxid', 'id_lugar': obj.id_lugar_origen }
            }).then(function (data) {
                if (data.length > 0) {
                    var obj = data[0];
                    var opt = new Option(obj.nombre, obj.id_lugar, true, true);
                    select2Lugar1.append(opt).trigger('change');
                }
            });
            $.ajax({
                url: 'procesosLugar.php',
                type: 'POST',
                dataType: 'JSON',
                data: { 'oper': 'buscarxid', 'id_lugar': obj.id_lugar_destino }
            }).then(function (data) {
                if (data.length > 0) {
                    var obj = data[0];
                    var opt = new Option(obj.nombre, obj.id_lugar, true, true);
                    select2Lugar2.append(opt).trigger('change');
                }
            });
        }

    });
}

function cargarVehiculosContrato(data) {
    $.ajax({
        url: 'procesosContratoVehiculo.php',
        type: 'POST',
        dataType: 'JSON',
        data: { oper: 'buscarxidcontrato', 'id_contrato': data.id_flete }
    }).then(function (data) {
        if (data.length > 0) {
            data.forEach(function (el) {
                var obj = {
                    id: vehiculos_conductor.length + 1,
                    vehiculo: $(`<input type="text">`).html(el.data_vehiculo).text(),
                    conductor: $(`<input type="text">`).html(el.data_conductor).text(),
                    id_vehiculo: el.id_vehiculo,
                    id_conductor: el.id_conductor
                }
                vehiculos_conductor.push(obj);
            });
            gridVehiculos.jqGrid('setGridParam',
                {
                    datatype: 'local',
                    data: vehiculos_conductor
                })
                .trigger("reloadGrid");
        }
    });
}

function imprimir(url, data) {
    var win = window.open(url + '?' + data);
    win.print();
}

function calcularDias() {
    var salida = new Date($("#fecha_salida").val());
    var retorno = new Date($("#fecha_retorno").val());
    var dif = retorno.getTime() - salida.getTime();
    var dias = Math.round(dif / (1000 * 60 * 60 * 24));
    return dias;
}

function tablaRegistroOperaciones() {
    gridOperaciones = jQuery("#list_o").jqGrid({
        url: 'xmlContratoOperaciones.php',
        datatype: 'xml',
        colNames: ['', 'Accion', 'Tipo doc.', 'Valor', 'Descripción', 'Doc. financiero', 'Fecha', "accion_1"],
        colModel: [
            {
                name: 'myac',
                width: 50,
                fixed: true,
                sortable: false,
                resize: false,
                formatter: 'actions',
                formatoptions: { keys: false, delbutton: true, editbutton: false }
            },
            { name: 'accion', index: 'accion', width: 10, sortable: false, align: "center", },
            { name: 'tipo_documento', index: 'tipo_documento', align: "center", width: 10, sortable: false },
            { name: 'valor', index: 'valor', width: 10, sortable: false, align: "center", },
            { name: 'descripcion', index: 'descripcion', width: 10, sortable: false, align: "center", },
            { name: 'nro_documento', index: 'nro_documento', align: "center", width: 10, sortable: false },
            { name: 'fecha_creacion', index: 'fecha_creacion', width: 10, sortable: false, align: "center", },
            { name: 'accion_1', index: 'accion_1', width: 10, sortable: false, align: "center", hidden: true },
        ],
        caption: "Lista de operaciones",
        width: $('#div_tabla_o')[0].offsetWidth - 30,
        height: null,
        rowNum: 5,
        pager: jQuery('#pager_o'),
        viewrecords: true,
        loadComplete: function (data) {
            $("#restante").val(obtenerSaldoRestante());
            var datos = gridOperaciones.jqGrid("getRowData");
            var ingresos = 0;
            var egresos = 0;
            datos.forEach(function (el) {
                if (el["accion_1"] == "a" || el["accion_1"] == "i") {
                    ingresos += +el.valor;
                } else if (el["accion_1"] == "e") {
                    egresos += +el.valor;
                }
            });
            obtenerTotalOperaciones().then(function (data) {
                console.log(data)
                var totalIngresos = +data.ingresos + +data.abonos;
                var totalEgresos = +data.egresos;
                var utilidad = totalIngresos - totalEgresos;
                // var totalCancelacion = +data.abonos;
                var restante = +$("#valor").val() - +data.abonos;
                var totalCancelacion = utilidad + restante;
                /*$("#total_ingresos").text(+data.ingresos + +data.abonos);
                 $("#total_egresos").text(+data.egresos);*/
                $("#total_ingresos").text(totalIngresos);
                $("#total_egresos").text(totalEgresos);
                $("#total_cancelacion").text(totalCancelacion);
                if (restante == 0) {
                    $("#total_restante").parent("td").removeClass("bg-danger")
                    $("#total_restante").parent("td").addClass("bg-success");
                } else {
                    $("#total_restante").parent("td").removeClass("bg-success")
                    $("#total_restante").parent("td").addClass("bg-danger");
                }
                $("#total_restante").text(restante);
                $("#total_utilidad").text(utilidad);
            });
            $("#total_contrato").text($("#valor").val());
        },
        delOptions: {
            modal: true,
            jqModal: true,
            onclickSubmit: quitarOperacion,
            processing: true
        }
    }).jqGrid('navGrid', '#pager_o',
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
        }
    );
}

function guardarOperacion() {
    if (id_contrato <= 0) {
        alertify.alert("Seleccione un contrato para poder continuar.");
        return;
    }
    if (!validarForm("form_operacion")) {
        return;
    }

    var nodes = $("[name=accion]");
    var arrnodes = Array.from(nodes);
    var raccion = arrnodes.find(function (el) {
        return el.checked;
    });

    if (raccion.value == "a") {
        if (+$("#valor_o").val() > +$("#total_restante").text()) {
            alertify.alert("<b>El abono no puede ser mayor que el saldo restante.</b>");
            return;
        }
    }

    var formd = new FormData($("#form_operacion")[0]);
    formd.append("oper", "add");
    formd.append("id_contrato", id_contrato);
    $("#btnAgregarOperacion").prop("disabled", true);
    $.ajax({
        url: "procesosContratoOperacion.php",
        type: "POST",
        cache: false,
        processData: false,
        contentType: false,
        data: formd
    }).then(function (data) {
        if (data > 0) {
            $("#form_operacion")[0].reset();
            alertify.success("Operación guardada correctamente.");
            gridOperaciones.trigger("reloadGrid");
        }
        if (data.res == 0) {
            alertify.error("No se pudo guardar el contrato.");
        } else if (data.res < 0) {
            alertify.error("El número de contrato indicado ya existe.");
        }
    }).always(function () {
        $("#btnAgregarOperacion").prop("disabled", false);
    });
}

function quitarOperacion(options, rowid) {
    var grid_p = gridOperaciones[0].p;
    var newpage = grid_p.page;
    var grid_id = $.jgrid.jqID(gridOperaciones[0].id);

    options.processing = true;

    if (rowid != null) {
        $.ajax({
            url: "procesosContratoOperacion.php",
            type: "POST",
            data: { oper: 'del', "id_operacion": rowid }
        }).then(function (data) {
            if (data > 0) {
                if (grid_p.lastpage > 1) {// on the multipage grid reload the grid
                    if (grid_p.reccount === 0 && newpage === grid_p.lastpage) {
                        // if after deliting there are no rows on the current page
                        // which is the last page of the grid
                        newpage--; // go to the previous page
                    }
                }
                gridOperaciones.trigger("reloadGrid", [{ page: newpage }]);
            }
            $.jgrid.hideModal("#delmod" + grid_id,
                {
                    gb: "#gbox_" + grid_id,
                    jqm: options.jqModal, onClose: options.onClose
                });
            // reload grid to make the row from the next page visable.
        });
    } else {
        alert("Please Select Row to delete!");
    }

    return true;
}

function obtenerSaldoRestante() {
    var datos = gridOperaciones.jqGrid("getRowData");
    var totalabono = 0;
    datos.forEach(function (el) {
        if (el.accion == 'ABONO') {
            totalabono += +el.valor;
        }
    });
    var totalcontrato = $("#valor").val();
    var total = +totalcontrato - totalabono;
    if (total < 0) {
        $("#error_restante").css("display", "");
    } else {
        $("#error_restante").css("display", "none");
    }
    return total;
}

function obtenerTotalOperaciones() {
    return $.ajax({
        url: "procesosContratoOperacion.php",
        type: "POST",
        dataType: 'JSON',
        data: { "oper": "totaloperaciones", "id_contrato": id_contrato }
    });
}