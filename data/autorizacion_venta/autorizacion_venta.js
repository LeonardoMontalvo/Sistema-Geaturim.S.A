$(document).on("ready", inicio);

function openPDF(){
window.open('../../ayudas/ayuda.pdf');
}

function inicio() {
    $(window).on("resize", function() {
        jQuery("#list").setGridWidth($('#centro').width());
    }).trigger('resize');
    jQuery("#list").jqGrid({
        url: 'xmlAutorizacion.php',
        datatype: 'xml',
        colNames: ['Id Autorizacion', 'Factura Inicio', 'Factura Fin', 'Autorizacion'],
        colModel: [
            {name: 'id_autorizacion_venta', index: 'id_autorizacion_venta', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'factura_inicio', index: 'factura_inicio', editable: true, align: 'center', width: '300', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'factura_fin', index: 'factura_fin', editable: true, align: 'center', width: '300', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'autorizacion', index: 'autorizacion', editable: true, align: 'center', width: '300', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}}
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        height: 255,
        pager: jQuery('#pager'),
        editurl: "procesoAutorizacion.php",
        sortname: 'id_autorizacion_venta',
        shrinkToFit: false,
        sortordezr: 'asc',
        caption: 'Lista Factureros',
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
                viewtext: "Consultar",
                searchtext:"Buscar"
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
}
function Defecto(e) {
    e.preventDefault();
}

