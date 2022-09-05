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
        jQuery("#list").setGridWidth($('#pager').width() - 10);
    }).trigger('resize');
    jQuery("#list").jqGrid({
        url: 'xmlBancos.php',
        datatype: 'xml',
        colNames: ['Cod. Bancos', 'Nombre Banco'],
        colModel: [
            {name: 'id_bancos', index: 'id_bancos', editable: true, align: 'center', width: '100', search: false, frozen: true, editoptions: {readonly: 'readonly'}},
            {name: 'descripcion', index: 'descripcion', editable: true, align: 'center', width: '240', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        width: null,
        height: 400,
        pager: jQuery('#pager'),
        editurl: "procesosBancos.php",
        sortname: 'id_bancos',
        shrinkToFit: false,
        sortordezr: 'asc',
        caption: 'Lista Bancos',
        viewrecords: true
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
    jQuery("#list").setGridWidth($('#pager').width() - 10);
}

function Defecto(e) {
    e.preventDefault();
}

