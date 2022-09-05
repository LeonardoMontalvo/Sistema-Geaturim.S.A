$(document).on("ready", inicio);

function openPDF(){
window.open('../../ayudas/ayuda.pdf');
}

function inicio() {
    $(window).bind('resize', function() {
        jQuery("#list1").setGridWidth($('#centro').width());
    }).trigger('resize');
    jQuery("#list1").jqGrid({
        url: 'xmlRetencionIva_r.php',
        datatype: 'xml',
        colNames: ['Cód. Retención', 'Descripción', 'Valor','Código Formulario','Cuenta Débito', 'Cuenta Crédito'],
        colModel: [
            {name: 'id_retencion_iva_r', index: 'id_retencion_iva_r', editable: true, align: 'center', width: '20', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'descripcion_r', index: 'descripcion_r', editable: true, align: 'center', width: '450', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'valor_r', index: 'valor_r', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'codigo_formulario_r', index: 'codigo_formulario_r', editable: true, align: 'center', width: '130', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'cuenta_debito', index: 'cuenta_debito', editable: true, align: 'center', width: '200', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}, edittype: 'select', editoptions:{dataUrl: 'retornar_plan_cuentas.php'}},
            {name: 'cuenta_credito', index: 'cuenta_credito', editable: true, align: 'center', width: '200', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}, edittype: 'select', editoptions:{dataUrl: 'retornar_plan_cuentas.php'}}
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        height: 255,
        pager: jQuery('#pager1'),
        editurl: "procesosRetencionIva_r.php",
        sortname: 'id_retencion_iva_R',
        shrinkToFit: false,
        sortordezr: 'asc',
        caption: 'Lista Retenciones de IVA',
        viewrecords: true
    }).jqGrid('navGrid', '#pager1',
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
    jQuery("#list1").setGridWidth($('#centro').width());
    
    
    
    
    
    ////////////////////RETENCIONES IVA
    ////////////////////////
    ///////////////////////
    ////////////////
      $(window).bind('resize', function() {
        jQuery("#list").setGridWidth($('#centro').width());
    }).trigger('resize');
    jQuery("#list").jqGrid({
        url: 'xmlRetencionIva.php',
        datatype: 'xml',
        colNames: ['Cód. Retención', 'Descripción', 'Valor','Código Formulario','Cuenta Débito', 'Cuenta Crédito'],
        colModel: [
            {name: 'id_retencion_iva', index: 'id_retencion_iva', editable: true, align: 'center', width: '20', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'descripcion', index: 'descripcion', editable: true, align: 'center', width: '450', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'valor', index: 'valor', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'codigo_formulario', index: 'codigo_formulario', editable: true, align: 'center', width: '130', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'cuenta_debito', index: 'cuenta_debito', editable: true, align: 'center', width: '200', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}, edittype: 'select', editoptions:{dataUrl: 'retornar_plan_cuentas.php'}},
            {name: 'cuenta_credito', index: 'cuenta_credito', editable: true, align: 'center', width: '200', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}, edittype: 'select', editoptions:{dataUrl: 'retornar_plan_cuentas.php'}}
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        height: 255,
        pager: jQuery('#pager'),
        editurl: "procesosRetencionIva.php",
        sortname: 'id_retencion_iva',
        shrinkToFit: false,
        sortordezr: 'asc',
        caption: 'Lista Retenciones de IVA',
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

