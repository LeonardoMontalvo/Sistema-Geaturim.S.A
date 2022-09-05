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


    jQuery("#list").jqGrid({
        url: 'xmlBodega.php',
        datatype: 'xml',
        colNames: ['Cód.', 'Nombre Bodega', 'Estado', 'Fecha', 'Hora', 'Ubicaciòn','Telèfono','Usuario'],
        colModel: [
            {name: 'id_punto_venta', index: 'id_punto_venta', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'nombre_punto', index: 'nombre_punto', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'estado', index: 'estado', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
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
}
function Defecto(e) {
    e.preventDefault();
}

