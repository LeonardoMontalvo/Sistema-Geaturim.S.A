$(document).on("ready", inicio);

function openPDF(){
window.open('../../ayudas/ayuda.pdf');
}

function inicio() {
    $(window).on("resize", function() {
        jQuery("#list").setGridWidth($('#pager').width());
    }).trigger('resize');
    jQuery("#list").jqGrid({
        url: 'xmlCuentasBancos.php',
        datatype: 'xml',
        colNames: ['Id Cuenta', 'Numero Cuenta', 'Banco','Cuenta Contable'],
        colModel: [
            {name: 'id_cuenta_banco', index: 'id_cuenta_banco', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'numero_cuenta', index: 'numero_cuenta', editable: true, align: 'center', width: '450', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'banco', index: 'banco', editable: true, align: 'center', width: '130', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}, edittype: 'select', editoptions:{dataUrl: 'retornar_bancos.php'}},
            {name: 'cuenta', index: 'cuenta', editable: true, align: 'center', width: '130', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}, edittype: 'select', editoptions:{dataUrl: 'retornar_plan_cuentas.php'}}
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        height: 255,
        pager: jQuery('#pager'),
        editurl: "procesosCuentasBancos.php",
        sortname: 'id_cuenta_banco',
        shrinkToFit: false,
        sortordezr: 'asc',
        caption: 'Lista Cuentas de Banco',
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
                searchtext: "Buscar"
            },
    {
        recreateForm: true, closeAfterEdit: true, checkOnUpdate: true, reloadAfterSubmit: true, width: 480, closeOnEscape: true
    },
    {
        reloadAfterSubmit: true, closeAfterAdd: true, checkOnUpdate: true, closeOnEscape: true,
        bottominfo: "Los campos marcados con (*) son obligatorios", width: 480, checkOnSubmit: false
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
    jQuery("#list").setGridWidth($('#pager').width());
}
function Defecto(e) {
    e.preventDefault();
}

