$(document).on("ready", inicio);
function evento(e) {
    e.preventDefault();
}

function openPDF(){
window.open('../../ayudas/ayuda.pdf');
}

function scrollToBottom() {
    $('html, body').animate({
        scrollTop: $(document).height()
    }, 'slow');
}

function scrollToTop() {
    $('html, body').animate({
        scrollTop: 0
    }, 'slow');
}

function ValidNum() {
    if (event.keyCode < 48 || event.keyCode > 57) {
        event.returnValue = false;
    }
    return true;
}
var boton=0;
var dialogos =
{
    autoOpen: false,
    resizable: false,
    width: 860,
    height: 350,
    modal: true
};

var dialogo3 =
{
    autoOpen: false,
    resizable: false,
    width: 400,
    height: 210,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind"    
}

var dialogo4 ={
    autoOpen: false,
    resizable: false,
    width: 240,
    height: 150,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind"
}

var dialogo5 ={
    autoOpen: false,
    resizable: false,
    width: 500,
    height: 400,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind"
}

var dialogo_cuenta ={
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 400,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind"
}


function abrirCuenta() {
    $("#cuentas").dialog("open");
}


function confirmar() {
        $("#clave_permiso").dialog("open");  
}

function validar_acceso(){
    if($("#clave").val() == ""){
        $("#clave").focus();
        alertify.error("Ingrese la clave");
    }else{
        $.ajax({
            url: '../../procesos/validar_acceso.php',
            type: 'POST',
            data: "clave=" + $("#clave").val(),
            success: function(data) {
                var val = data;
                if (val == 0) {
                    $("#clave").val("");
                    $("#clave").focus();
                    alertify.error("Error... La clave es incorrecta ingrese nuevamente");
                } else {
                    if (val == 1) {
                        $("#seguro").dialog("open");   
                    }
                }
            }
        });
    }   
}

function aceptar() {
    $.ajax({
        type: "POST",
        url: "guardar_parametros.php",
        data: "ivacompra=" + $("#idIvaC").val()+"&ivaventa=" + $("#idIvaV").val()+"&cgeneral=" + $("#idCajaGeneral").val()+"&cchica=" + $("#idCajaChica").val()+"&ccobrar=" + $("#idCxc").val()+"&cpagar=" + $("#idCxp").val()+"&dcobrar=" + $("#idDxc").val()+"&valoriva=" + $("#valorIva").val(),
        success: function(data) {
            var val = data;
            if (val == 1) {
                alertify.success('Datos Guardados Correctamente');						    		
                setTimeout(function() {
                    location.reload();
                },1000);
            }
        }
    }); 
}

function cancelar(){
    $("#seguro").dialog("close");   
    $("#clave_permiso").dialog("close");    
    $("#clave").val("");    
}

function cancelar_acceso(){
    $("#clave_permiso").dialog("close");     
    $("#clave").val("");
}


function Valida_punto() {
    var key;
    if (window.event) {
        key = event.keyCode;
    } else if (event.which) {
        key = event.which;
    }

    if (key < 48 || key > 57) {
        if (key === 46 || key === 8) {
            return true;
        } else {
            return false;
        }
    }
    return true;
}

function ValidNum() {
    if (event.keyCode < 48 || event.keyCode > 57) {
        event.returnValue = false;
    }
    return true;
}

function enter(e) {
    if (e.which === 13 || e.keyCode === 13) {
        porcenta();
        return false;
    }
    return true;
}

function enter2(e) {
    if (e.which === 13 || e.keyCode === 13) {
        porcenta2();
        return false;
    }
    return true;
}

// function porcenta(){
//     var resta = parseFloat($("#precio_minorista").val() - $("#precio_compra").val());
//     var entero = resta * 100;
//     var val = Math.round(entero / parseFloat($("#precio_compra").val()));
//    $("#utilidad_minorista").val(val); 
// }

// function porcenta2(){
//     var resta = parseFloat($("#precio_mayorista").val() - $("#precio_compra").val());
//     var entero = resta * 100;
//     var val = Math.round(entero / parseFloat($("#precio_compra").val()));
//     $("#utilidad_mayorista").val(val);    
// }



function inicio() {
    /////////////cambiar idioma///////
     $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);
    
    function getDoc(frame) {
        var doc = null;     
     	
        try {
            if (frame.contentWindow) {
                doc = frame.contentWindow.document;
            }
        } catch(err) {
        }
        if (doc) { 
            return doc;
        }
        try { 
            doc = frame.contentDocument ? frame.contentDocument : frame.document;
        } catch(err) {
       
            doc = frame.document;
        }
        return doc;
    }
    
    
    $("#btnGuardar").click(function(e) {
        e.preventDefault();
    });
    $("#btnModificar").click(function(e) {
        e.preventDefault();
    });
    $("#btnCuenta1").click(function(e) {
        e.preventDefault();
    });
    $("#btnCuenta2").click(function(e) {
        e.preventDefault();
    });
    $("#btnCuenta3").click(function(e) {
        e.preventDefault();
    });
    $("#btnCuenta4").click(function(e) {
        e.preventDefault();
    });
    $("#btnCuenta5").click(function(e) {
        e.preventDefault();
    });
    $("#btnCuenta6").click(function(e) {
        e.preventDefault();
    });
    $("#btnCuenta7").click(function(e) {
        e.preventDefault();
    });
   
    $("#btnGuardar").on("click", confirmar);
    $("#btnCuenta1").on("click", uno);
    $("#btnCuenta1").on("click", abrirCuenta);
    $("#btnCuenta2").on("click", dos);
    $("#btnCuenta2").on("click", abrirCuenta);
    $("#btnCuenta3").on("click", tres);
    $("#btnCuenta3").on("click", abrirCuenta);
    $("#btnCuenta4").on("click", cuatro);
    $("#btnCuenta4").on("click", abrirCuenta);
    $("#btnCuenta5").on("click", cinco);
    $("#btnCuenta5").on("click", abrirCuenta);
    $("#btnCuenta6").on("click", seis);
    $("#btnCuenta6").on("click", abrirCuenta);
    $("#btnCuenta7").on("click", siete);
    $("#btnCuenta7").on("click", abrirCuenta);
    $("#btnAceptar").on("click", aceptar);
    $("#btnSalir").on("click", cancelar);
    $("#btnAcceder").on("click", validar_acceso);
    $("#btnCancelar").on("click", cancelar_acceso);
    
    $("#clave_permiso").dialog(dialogo3);
    $("#seguro").dialog(dialogo4);
    $("#cuentas").dialog(dialogo_cuenta);
    
    $("#fecha_creacion").datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');
  
    ////////////cambio evento/////////////
    $("#iva").change(function() {
       if($("#iva").val() == "Si") {
          $("#incluye").val("Si");
          $("#incluye").attr("readOnly", false);
       } else {
          if($("#iva").val() == "No") {
              $("#incluye").val("No");
              $("#incluye").attr("readOnly", true);
            }
       }
    });
    /////////////////////////////////////

    
    $(window).bind('resize', function() {
        jQuery("#list2").setGridWidth($('#pager2').width());
    }).trigger('resize');
    jQuery("#list2").jqGrid({
        url: 'xmlPlanCuentas.php',
        datatype: 'xml',
        colNames: ['Cod. Cuenta', 'Descripcion', 'Cuenta'],
        colModel: [            
            {name: 'idcontable', index: 'idcontable', editable: true, align: 'left', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'ccontable', index: 'ccontable', editable: true, align: 'center', width: '490', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'cuenta', index: 'cuenta', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}}
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        height: 255,
        pager: jQuery('#pager2'),
        sortname: 'codigo_plan',
        shrinkToFit: false,
        sortordezr: 'asc',
        caption: 'Plan de Cuentas',
        viewrecords: true,
        ondblClickRow: function(){
            if(boton==1){
                var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
                jQuery('#list2').jqGrid('restoreRow', id);   
                var ret = jQuery("#list2").jqGrid('getRowData', id);
                var ccuenta = jQuery("#list2").jqGrid('getCell', id, 0)+"  -  "+jQuery("#list2").jqGrid('getCell', id, 1);
                $("#idIvaC").val(id);
                $("#ivac").val(ccuenta);
                document.getElementById("ivac").readOnly = true;
                $("#cuentas").dialog("close");  
            }else  if(boton==2){
                var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
                jQuery('#list2').jqGrid('restoreRow', id);   
                var ret = jQuery("#list2").jqGrid('getRowData', id);
                var ccuenta = jQuery("#list2").jqGrid('getCell', id, 0)+"  -  "+jQuery("#list2").jqGrid('getCell', id, 1);
                $("#idIvaV").val(id);
                $("#ivav").val(ccuenta);
                document.getElementById("ivav").readOnly = true;
                $("#cuentas").dialog("close");  
            }else  if(boton==3){
                var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
                jQuery('#list2').jqGrid('restoreRow', id);   
                var ret = jQuery("#list2").jqGrid('getRowData', id);
                var ccuenta = jQuery("#list2").jqGrid('getCell', id, 0)+"  -  "+jQuery("#list2").jqGrid('getCell', id, 1);
                $("#idCajaGeneral").val(id);
                $("#cajaGeneral").val(ccuenta);
                document.getElementById("cajaGeneral").readOnly = true;
                $("#cuentas").dialog("close");  
            }else  if(boton==4){
                var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
                jQuery('#list2').jqGrid('restoreRow', id);   
                var ret = jQuery("#list2").jqGrid('getRowData', id);
                var ccuenta = jQuery("#list2").jqGrid('getCell', id, 0)+"  -  "+jQuery("#list2").jqGrid('getCell', id, 1);
                $("#idCajaChica").val(id);
                $("#cajaChica").val(ccuenta);
                document.getElementById("cajaChica").readOnly = true;
                $("#cuentas").dialog("close");  
            }else  if(boton==5){
                var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
                jQuery('#list2').jqGrid('restoreRow', id);   
                var ret = jQuery("#list2").jqGrid('getRowData', id);
                var ccuenta = jQuery("#list2").jqGrid('getCell', id, 0)+"  -  "+jQuery("#list2").jqGrid('getCell', id, 1);
                $("#idCxc").val(id);
                $("#cxc").val(ccuenta);
                document.getElementById("cxc").readOnly = true;
                $("#cuentas").dialog("close");  
            }else  if(boton==6){
                var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
                jQuery('#list2').jqGrid('restoreRow', id);   
                var ret = jQuery("#list2").jqGrid('getRowData', id);
                var ccuenta = jQuery("#list2").jqGrid('getCell', id, 0)+"  -  "+jQuery("#list2").jqGrid('getCell', id, 1);
                $("#idDxc").val(id);
                $("#dxc").val(ccuenta);
                document.getElementById("dxc").readOnly = true;
                $("#cuentas").dialog("close");  
            }else  if(boton==7){
                var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
                jQuery('#list2').jqGrid('restoreRow', id);   
                var ret = jQuery("#list2").jqGrid('getRowData', id);
                var ccuenta = jQuery("#list2").jqGrid('getCell', id, 0)+"  -  "+jQuery("#list2").jqGrid('getCell', id, 1);
                $("#idCxp").val(id);
                $("#cxp").val(ccuenta);
                document.getElementById("cxp").readOnly = true;
                $("#cuentas").dialog("close");  
            }
        }
    }).jqGrid('navGrid', '#pager2',
        {
            add: false,
            edit: false,
            del: false,
            refresh: true,
            search: true,
            view: false
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
    jQuery("#list2").setGridWidth($('#pager2').width());   
}


function uno(){
    boton=1;
}
function dos(){
    boton=2;
}
function tres(){
    boton=3;
}
function cuatro(){
    boton=4;
}
function cinco(){
    boton=5;
}
function seis(){
    boton=6;
}
function siete(){
    boton=7;
}