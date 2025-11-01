$(document).on("ready", inicio);

function openPDF(){
window.open('../../ayudas/ayuda.pdf');
}

function inicio() {
    $(window).on("resize", function() {
        jQuery("#list").setGridWidth($('#centro').width());
    }).trigger('resize');
    jQuery("#list").jqGrid({
        url: 'xmlAnulada.php',
        datatype: 'xml',
        colNames: ['Id. Anulacion', 'Factura Inicio', 'Factura Fin', 'Fecha Anulacion'],
        colModel: [
            {name: 'id_facturas_anuladas', index: 'id_facturas_anuladas', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'factura_inicio', index: 'factura_inicio', editable: true, align: 'center', width: '250', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'factura_fin', index: 'factura_fin', editable: true, align: 'center', width: '250', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'fecha_anulacion', index: 'fecha_anulacion', editable: true, align: 'center', width: '250', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}}
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        height: 255,
        pager: jQuery('#pager'),
        editurl: "procesosAnuladas.php",
        sortname: 'id_facturas_anuladas',
        shrinkToFit: false,
        sortorder: 'asc',
        caption: 'Lista Facturas Anuladas',
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
}
function Defecto(e) {
    e.preventDefault();
}

