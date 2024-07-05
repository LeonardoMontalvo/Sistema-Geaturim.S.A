$(document).on("ready", inicio);

function openPDF(){
window.open('../../ayudas/ayuda.pdf');
}

function numeros(e) { 
tecla = (document.all) ? e.keyCode : e.which;
if (tecla==8) return true;
patron = /\d/;
te = String.fromCharCode(tecla);
return patron.test(te);
}

function inicio() {
    $(window).bind('resize', function() {
        jQuery("#list").setGridWidth($('#centro'));
    }).trigger('resize');


    initTablaParametros();
    jQuery("#list").jqGrid({
        url: 'xmlBodega.php',
        datatype: 'xml',
        colNames: ['Cód.', 'Nombre Bodega', 'Estado', 'Fecha', 'Hora', 'Ubicaciòn','Telèfono','Usuario'],
        colModel: [
            {name: 'id_punto_venta', index: 'id_punto_venta', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'nombre_punto', index: 'nombre_punto', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'estado', index: 'estado', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}, edittype: 'select', editoptions: {
                value: 'Activo:Activo;Inactivo:Inactivo'
            }},
            {name: 'fecha_actual_punto', index: 'fecha_actual_punto', editable: false, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " "}, editrules: {required: true}},
            {name: 'hora_actual_punto', index: 'hora_actual_punto', editable: false, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " "}, editrules: {required: true}},
           
            {name: 'ubicacion', index: 'ubicacion', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'telefono', index: 'telefono', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'usuario', index: 'usuario', editable: false, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " "}, editrules: {required: true}}
       
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        height: 255,
        pager: jQuery('#pager'),
        editurl: "procesoBodegas.php",
        sortname: 'id_punto_venta',
        shrinkToFit: false,
        sortordezr: 'asc',
        caption: 'Lista Categorías',
        viewrecords: true
    }).jqGrid('navGrid', '#pager',
            {
                add: true,
                edit: true,
                del: false,
                refresh: true,
                search: true,
                view: true,
                addtext: "Nuevo",
                edittext: "Modificar",
                refreshtext: "Recargar",
                viewtext: "Consultar"
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
    jQuery("#list").setGridWidth($('#centro').width());

    initDialogFormatosImp();
}
function Defecto(e) {
    e.preventDefault();
}

///parámetros

function initTablaParametros() {
    jQuery("#list_parametros").jqGrid({
        url: 'xmlBodega_parametros.php',
        datatype: 'xml',
        colNames: ['Cód.', 'Nombre Bodega', 'Formatos de impresión'],
        colModel: [
            { name: 'id_punto_venta', index: 'id_punto_venta', search: false, hidden: true },
            { name: 'nombre_punto', index: 'nombre_punto', search: false },
            {
                name: 'formatos_imp',
                index: 'id_punto_venta',
                search: false,
                formatter: function (cellvalue, options, rowObject) {
                    return `<div style="text-align:center; padding:4px;"><button id="btn_form_imp_${options.rowId}" class="btn btn-primary btn-block btn-sm btn-conf"><i class="fa fa-gear"></i> Configurar</button></div>`;
                },
                width: 150,
            },
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        height: 255,
        pager: jQuery('#pager_parametros'),
        sortname: 'id_punto_venta',
        shrinkToFit: false,
        sortordezr: 'asc',
        caption: 'Lista Párametros',
        viewrecords: true,
        afterInsertRow: function (rowid, rowdata, rowelem) {
            $("#btn_form_imp_" + rowid).click(function (e) {
                $("#dialog_formatos_imp").dialog("open");
                obtenerParamFormatosImp(rowid).done((data) => {
                    for (let key in data) {
                        $(`#${key}`).val(data[key]);
                    }
                });
                let selects = $("#form_formatos_imp select").get();
                selects.forEach(el => {
                    $(el).off('change');
                });
                selects.forEach(el => {
                    $(el).change(function (e) {
                        cambiarParametroPv(rowdata.id_punto_venta, e.target.id, e.target.value);
                    });
                });

            });
        }
    }).jqGrid('navGrid', '#pager_parametros',
        {
            add: false,
            edit: false,
            del: false,
            refresh: true,
            search: true,
            view: false,
            addtext: "Nuevo",
            edittext: "Modificar",
            refreshtext: "Recargar",
            viewtext: "Consultar"
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
    /*  jQuery("#list_parametros").setGridWidth($('#centro_1').width()); */
}

function cambiarParametroPv(id_pv, nombreparam, valparam) {

    $.ajax({
        url: "guardar_parametro_pv.php",
        method: "POST",
        dataType: "json",
        data: {
            id_pv,
            nombre_param: nombreparam,
            valor: valparam

        },
        success: function (data) {
            recargarTablaParametros();
            animarfila = true;
            $("#alertify-logs").empty();
            alertify.success("Cambio guardado");
        }
    });
}

function recargarTablaParametros() {
    jQuery("#list_parametros").jqGrid("clearGridData");
    jQuery("#list_parametros").jqGrid("setGridParam", {
        page: 1,
    });
    jQuery("#list_parametros").trigger("reloadGrid");
}

function obtenerPuntosVenta() {
    $.ajax({
        url: "obtener_puntos_venta.php",
        method: "GET",
        dataType: "json",
        success: function (data) {
            $("#puntos_venta").empty();
            $("#puntos_venta").append(`<option value="">--SELECCIONE BODEGA--</option>`);
            data.forEach(el => {
                $("#puntos_venta").append(`<option value="${el.id_punto_venta}">${el.nombre_punto}</option>`);
            });
        }
    });
}
function initDialogFormatosImp() {
    $("#dialog_formatos_imp").dialog({
        modal: true,
        width: 400,
        height: 450,
        autoOpen: false,
        title: "ESTABLECER FORMATOS DE IMPRESIÓN",
        dialogClass: 'fixed-dialog',
        open: function (event, ui) {
        },
        close: function (event, ui) {
            $("#form_formatos_imp")[0].reset();
        },
    });
}

function obtenerParamFormatosImp(id_pv) {
    return $.ajax({
        url: "obtener_parametros_formatos_imp.php",
        dataType: "json",
        data: {
            id_pv
        }
    });
}

