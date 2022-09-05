$(document).on("ready", inicio);

function openPDF(){
window.open('../../ayudas/ayuda.pdf');
}

function inicio() {
    $(window).bind('resize', function() {
      jQuery("#list").setGridWidth($('#centro').width());
    }).trigger('resize');
    jQuery("#list").jqGrid({
        url: 'xmlAplicacion.php',
        datatype: 'xml',
        colNames: ['Cod. Aplicaciòn', 'Nombre Aplicaciòn'],
        colModel: [
            {name: 'id_aplicacion', index: 'id_aplicacion', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'nombre_aplicacion', index: 'nombre_aplicacion', editable: true, align: 'center', width: '690', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}}
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        height: 255,
        pager: jQuery('#pager'),
        editurl: "procesoAplicacion.php",
        sortname: 'id_aplicacion',
        shrinkToFit: false,
        sortordezr: 'asc',
        caption: 'Lista Aplicaciòn',
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
        reloadAfterSubmit: false, closeAfterAdd: true, checkOnUpdate: true, closeOnEscape: true,
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

