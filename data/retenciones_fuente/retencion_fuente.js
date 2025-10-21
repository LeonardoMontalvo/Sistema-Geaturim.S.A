var val=plancuentas();
var val_r=plancuentas_r();
$(document).on("ready", inicio);
var string="";
function openPDF(){
window.open('../../ayudas/ayuda.pdf');
}

function inicio() {
    $.ajax({
        type: "POST",
        url: "retornar_plan_cuentas_r.php",
        data: "",
        success: function(data) {
            val_r = data;
        }

    });

    //string=plancuentas();
//    $(window).on("resize", function() {
//        jQuery("#list1").setGridWidth($('#centro').width());
//    }).trigger('resize');
    jQuery("#list1").jqGrid({
        url: 'xmlRetencionFuente_r.php',
        datatype: 'xml',
        colNames: ['Cód. Re', 'Descripción', 'Valor','Código Formulario','Cuenta Débito', 'Cuenta Crédito'],
        colModel: [
            {name: 'id_retencion_fuentes_r', index: 'id_retencion_fuentes_r', editable: true, align: 'center', width: '20', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'descripcion_r', index: 'descripcion_r', editable: true, align: 'left', width: '300', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'valor_r', index: 'valor_r', editable: true, align: 'center', width: '30', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'codigo_formulario_r', index: 'codigo_formulario_r', editable: true, align: 'center', width: '50', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'cuenta_debito', index: 'cuenta_debito', editable: true, align: 'center', width: '300', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}, edittype: 'select', editoptions:{ dataUrl: 'retornar_plan_cuentas.php'}},
            {name: 'cuenta_credito', index: 'cuenta_credito', editable: true, align: 'center', width: '300', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}, edittype: 'select', editoptions:{dataUrl: 'retornar_plan_cuentas.php'}}
        ],
               rowNum: 20,
        rowList: [ 20],
        height: 500,
        pager: jQuery('#pager1'),
        editurl: "procesosRetencionFuente_r.php",
        sortname: 'id_retencion_fuentes_r',
        shrinkToFit: false,
        sortordezr: 'asc',
        caption: 'Lista Retenciones en la Fuente Recibidas',
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
        recreateForm: true, closeAfterEdit: true, checkOnUpdate: true, reloadAfterSubmit: true, width: 650, closeOnEscape: true
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
    
    
    /////////////////////////////////////
    //////////////////////////////
    /////////////////////////
    
    
    /////////////////////RETENCIONES  RECIBIDAS
     $.ajax({
        type: "POST",
        url: "retornar_plan_cuentas.php",
        data: "",
        success: function(data) {
            val = data;
        }

    });

    //string=plancuentas();
//    $(window).on("resize", function() {
//        jQuery("#list").setGridWidth($('#centro').width());
//    }).trigger('resize');
    jQuery("#list").jqGrid({
        url: 'xmlRetencionFuente.php',
        datatype: 'xml',
        colNames: ['Cód. Re', 'Descripción', 'Valor','Código Formulario','Cuenta Débito', 'Cuenta Crédito'],
        colModel: [
            {name: 'id_retencion_fuentes', index: 'id_retencion_fuentes', editable: true, align: 'center', width: '20', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'descripcion', index: 'descripcion', editable: true, align: 'left', width: '300', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'valor', index: 'valor', editable: true, align: 'center', width: '30', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'codigo_formulario', index: 'codigo_formulario', editable: true, align: 'center', width: '50', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'cuenta_debito', index: 'cuenta_debito', editable: true, align: 'left', width: '300', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}, edittype: 'select', editoptions:{ dataUrl: 'retornar_plan_cuentas.php'}},
            {name: 'cuenta_credito', index: 'cuenta_credito', editable: true, align: 'left', width: '300', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}, edittype: 'select', editoptions:{dataUrl: 'retornar_plan_cuentas.php'}}
        ],
        rowNum: 20,
        rowList: [ 20],
        height: 500,
        pager: jQuery('#pager'),
        editurl: "procesosRetencionFuente.php",
        sortname: 'id_retencion_fuentes',
        shrinkToFit: false,
        sortordezr: 'asc',
        caption: 'Lista Retenciones en la Fuente',
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
        recreateForm: true, closeAfterEdit: true, checkOnUpdate: true, reloadAfterSubmit: true, width: 650, closeOnEscape: true
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

function plancuentas(){
    $.ajax({
        type: "POST",
        url: "retornar_plan_cuentas.php",
        data: "",
        success: function(data) {
            var  val1 = data;
            if (val1 != "") {
                return val1; 
            }
        }

    });
}

function plancuentas_r(){
    $.ajax({
        type: "POST",
        url: "retornar_plan_cuentas_r.php",
        data: "",
        success: function(data) {
            var  val1 = data;
            if (val1 != "") {
                return val1; 
            }
        }

    });
}