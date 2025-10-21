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
    $(window).on("resize", function() {
        jQuery("#list").setGridWidth($('#centro'));
    }).trigger('resize');

    jQuery("#list").jqGrid({
        url: 'xmlAuditoria.php',
        datatype: 'xml',
        colNames: ['Cód.', 'Usuario', 'Fecha', 'Hora', 'Concepto', 'Tipo','No. Trans.'],
        colModel: [
            {name: 'id_transacciones', index: 'id_transacciones', editable: true, align: 'center', width: '70', search: false, frozen: true}, 
            {name: 'usuario', index: 'id_usuario', editable: true, align: 'center', width: '100', search: false, frozen: true},
            {name: 'fecha', index: 'fecha_actual', editable: true, align: 'center', width: '100', search: true, frozen: true},
            {name: 'hora', index: 'hora_actual', editable: false, align: 'center', width: '100', search: true, frozen: true},
            {name: 'concepto', index: 'concepto', editable: false, align: 'left', width: '420', search: true, frozen: true},
            {name: 'tipo', index: 'id_tipo_transaccion', editable: true, align: 'center', width: '70', search: false, frozen: true},
            {name: 'num_transaccion', index: 'num_transaccion', editable: true, align: 'center', width: '100', search: true, frozen: true},
       
        ],
        rowNum: 20,
        rowList: [10, 20, 30],
        height: 400,
        pager: jQuery('#pager'),
        sortname: 'id_transacciones',
        shrinkToFit: true,
        sortorder: 'desc',
        caption: 'Registros',
        viewrecords: true
    }).jqGrid('navGrid', '#pager',
            {
                add: false,
                edit: false,
                del: false,
                refresh: true,
                search: true,
                view: true,
                refreshtext: "Recargar",
                viewtext: "Ver Registro",
                searchtext: "Buscar"
            },
    {
        recreateForm: true, closeAfterEdit: true, checkOnUpdate: true, reloadAfterSubmit: true, closeOnEscape: true
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

