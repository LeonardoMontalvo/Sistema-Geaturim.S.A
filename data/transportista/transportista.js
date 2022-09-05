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

var dialogo =
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

var dialogo4 = {
    autoOpen: false,
    resizable: false,
    width: 240,
    height: 150,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind"
}



function abrirDialogo() {
    $("#transportista").dialog("open");
}

function guardar_transportista() {
    var iden = $("#identificacion").val();
    var expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if ($("#tipo_docu").val() === "Seleccione tipo Documento") {
        $("#tipo_docu").focus();
        alertify.error("Seleccione un tipo de documento ");
    } else {
        if ($("#tipo_docu").val() === '2' && iden.length < 10) {
            $("#identificacion").focus();
            alertify.error("Error.. Mínimo 10 dígitos ");
        } else {
            if ($("#tipo_docu").val() === '1' && iden.length < 13) {
                $("#identificacion").focus();
                alertify.error("Error.. Mínimo 13 dígitos ");
            } else {
           if ($("#identificacion").val() === "") {
               $("#identificacion").focus();
               alertify.error("Indique nombre de la empresa");
              } else {                                                                            
                    if ($("#nombres_trans").val() === "") {
                       $("#nombres_trans").focus();
                       alertify.error("Ingrese Nombre");
                        } else {
                              if ($("#direccion_trans").val() === "") {
                                  $("#direccion_trans").focus();
                                  alertify.error("Ingrese Dirección");
                                   } else {
                                         if ($("#celular").val() === "") {
                                             $("#celular").focus();
                                             alertify.error("Ingrese Celular");
                                                }else{
                                                     if ($("#num_placa").val() === "") {
                                                       $("#num_placa").focus();
                                                       alertify.error("Ingrese Celular");
                                                     }else{
                                                    $.ajax({
                                                    type: "POST",
                                                    url: "guardar_transportista.php",
                                                    data: "&identificacion=" + $("#identificacion").val() +"&nombres_trans=" + $("#nombres_trans").val()+   "&direccion_trans=" + $("#direccion_trans").val()+ "&telefono="  + $("#telefono").val() +   "&celular=" + $("#celular").val() +     "&num_placa=" +  $("#num_placa").val()+ "&tipo_docu=" + $("#tipo_docu").val(),
                                                       success: function(data) {
                                                        var val = data;
                                                        if (val == 1) {
                                                            alertify.success('Datos Agregados Correctamente');						    		
                                                            setTimeout(function() {
                                                            location.reload();
                                                            }, 1000);
                                                       }
                                                    }
                                                }); 
                                            }}
                                        }
                            }
              }}}
    }
}


function modificar_transportista() {
   
    var iden = $("#identificacion").val();
    var expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    var correo = $("#correo").val();
    
    if ($("#identificacion").val() === "") {
        alertify.error("Seleccione un vendedor");
    } else {
        
        
                    if ($("#nombres_trans").val() === "") {
                        $("#nombres_trans").focus();
                        alertify.error("Indique Nombre");
                    } else {
                        if ($("#direccion_trans").val() === "") {
                            $("#direccion_trans").focus();
                            alertify.error("Indique la dirección");
                        } else {
                            
                                    if ($("#celular").val() === "") {
                                        $("#celular").focus();
                                        alertify.error("Indique Número Celular");
                                    } else {
                                         
                                         
                                                    $.ajax({
                                                        type: "POST",
                                                        url: "modificar_transportista.php",
                                                        data:  "&identificacion=" + $("#identificacion").val()
                                                        +"&nombres_trans=" + $("#nombres_trans").val() 
                                                        + "&direccion_trans=" + $("#direccion_trans").val() 
                                                        + "&celular=" + $("#celular").val() 
                                                        + "&nro_telefono=" + $("#nro_telefono").val() 
                                                       
                                                        + "&correo=" + $("#correo").val(),
                                                        success: function(data) {
                                                            var val = data;
                                                            if (val == 1) {
                                                                alertify.success('Datos Modificados Correctamente');						    		
                                                                setTimeout(function() {
                                                                    location.reload();
                                                                }, 1000);
                                                            }
                                                        }
                                                    });
                                                
                                            
                                       
                                  
                                
                            }
                        }
                    }
                
            }
        
    
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
                    alertify.error("Error... La clave es incorrecta, ingrese nuevamente");
                }else {
                   
                    if (val == 1) {
                        $("#seguro_ven").dialog("open");   
                    }
                }
            }
        });
    }   
}

function aceptar(){
    $.ajax({
        type: "POST",
        url: "eliminar_transportista.php",
        data: "identificacion=" + $("#identificacion").val(),
        success: function(data) {
            var val = data;
            if (val == 1) {
                alertify.error('Error.. El Vendedor tiene movimientos en el sistema');						    		
                setTimeout(function() {
                    location.reload();
                }, 1000);
            }else{
                alertify.success('Vendedor Eliminado Correctamente');						    		
                setTimeout(function() {
                    location.reload();
                }, 1000); 
            }
        }
    }); 
}

function cancelar(){
    $("#seguro_ven").dialog("close");   
    $("#clave_permiso_ven").dialog("close");    
    $("#clave").val("");    
}

function cancelar_acceso(){
    $("#clave_permiso_ven").dialog("close");     
    $("#clave").val("");
}

function nuevo_transportista(e) {
    location.reload();
}

function ValidNum() {
    if (event.keyCode < 48 || event.keyCode > 57) {
        event.returnValue = false;
    }
    return true;
}

function Num_Let() {
    if ((event.keyCode !== 32) && (event.keyCode < 65) || (event.keyCode > 90) && (event.keyCode < 97) || (event.keyCode > 122)) {
        event.returnValue = false;
    }
    return true;
}

function reset () {
    $("#toggleCSS").attr("href", "../../css/alertify.default.css");
    alertify.set({
        labels : {
            ok     : "OK",
            cancel : "Cancel"
        },
        delay : 5000,
        buttonReverse : false,
        buttonFocus   : "ok"
    });
}

function punto(e){
    var key;
    if (window.event) {
        key = e.keyCode;
    }
    else if (e.which) {
        key = e.which;
    }

    if (key < 48 || key > 57) {
        if (key === 46 || key === 8)     {
            return true;
        } else {
            return false;
        }
    }
    return true;   
}

function inicio() {
    
       $("#tipo_docu").change(function() {
      
        if ($("#tipo_docu").val() === '2') {
            $("#identificacion").val("");
            $("#identificacion").keypress(ValidNum);
            $("#identificacion").removeAttr("disabled");
            $("#identificacion").attr("maxlength", "10");

        } else {
            if ($("#tipo_docu").val() === '1') {
              
                $("#identificacion").val("");
                $("#identificacion").keypress(ValidNum);
                $("#identificacion").removeAttr("disabled");
                $("#identificacion").removeAttr("maxlength");
                $("#identificacion").attr("maxlength", "13");
            } else {
                if ($("#tipo_docu").val() === '3') {
                    $("#identificacion").val("");
                    $("#identificacion").unbind("keypress");
                    $("#identificacion").removeAttr("disabled");
                    $("#identificacion").attr("maxlength", "30");
                }
            }
        }
    });
    
    
    
    $("[data-mask]").inputmask();
    alertify.set({ delay: 1000 });    
    $("#identificacion").focus();
    $("#identificacion").attr("maxlength", "13");
    $("#identificacion").keypress(ValidNum);
    $("#nro_telefono").validCampoFranz("0123456789");
    $("#celular").validCampoFranz("0123456789");    
      

  
    
    $("#identificacion").keyup(function() {
        $.ajax({
            type: "POST",
            url: "comparar_cedulas.php",
            data: "cedula=" + $("#identificacion").val(),
            success: function(data) {
                var val = data;
                if (val == 1) {
                    $("#identificacion").val("");
                    $("#identificacion").focus();
                    alertify.error("Error... El Transportista ya ésta registrado");
                }else{
                    var numero = $("#identificacion").val();
                    var suma = 0;      
                    var residuo = 0;      
                    var pri = false;      
                    var pub = false;            
                    var nat = false;                     
                    var modulo = 11;
                    var p1;
                    var p2;
                    var p3;
                    var p4;
                    var p5;
                    var p6;
                    var p7;
                    var p8;            
                    var p9; 
                    var d1  = numero.substr(0,1);         
                    var d2  = numero.substr(1,1);         
                    var d3  = numero.substr(2,1);         
                    var d4  = numero.substr(3,1);         
                    var d5  = numero.substr(4,1);         
                    var d6  = numero.substr(5,1);         
                    var d7  = numero.substr(6,1);         
                    var d8  = numero.substr(7,1);         
                    var d9  = numero.substr(8,1);         
                    var d10 = numero.substr(9,1);  

                    if (d3 < 6){           
                        nat = true;            
                        p1 = d1 * 2;
                        if (p1 >= 10) p1 -= 9;
                        p2 = d2 * 1;
                        if (p2 >= 10) p2 -= 9;
                        p3 = d3 * 2;
                        if (p3 >= 10) p3 -= 9;
                        p4 = d4 * 1;
                        if (p4 >= 10) p4 -= 9;
                        p5 = d5 * 2;
                        if (p5 >= 10) p5 -= 9;
                        p6 = d6 * 1;
                        if (p6 >= 10) p6 -= 9; 
                        p7 = d7 * 2;
                        if (p7 >= 10) p7 -= 9;
                        p8 = d8 * 1;
                        if (p8 >= 10) p8 -= 9;
                        p9 = d9 * 2;
                        if (p9 >= 10) p9 -= 9;             
                        modulo = 10;
                    } else if(d3 == 6){           
                        pub = true;             
                        p1 = d1 * 3;
                        p2 = d2 * 2;
                        p3 = d3 * 7;
                        p4 = d4 * 6;
                        p5 = d5 * 5;
                        p6 = d6 * 4;
                        p7 = d7 * 3;
                        p8 = d8 * 2;            
                        p9 = 0;            
                    } else if(d3 == 9) {          
                        pri = true;                                   
                        p1 = d1 * 4;
                        p2 = d2 * 3;
                        p3 = d3 * 2;
                        p4 = d4 * 7;
                        p5 = d5 * 6;
                        p6 = d6 * 5;
                        p7 = d7 * 4;
                        p8 = d8 * 3;
                        p9 = d9 * 2;            
                    }

                    suma = p1 + p2 + p3 + p4 + p5 + p6 + p7 + p8 + p9;                
                    residuo = suma % modulo;                                         

                    var digitoVerificador = residuo==0 ? 0: modulo - residuo; 
                    ////////////verificamos del tipo cedula o ruc////////////////////
                   
                        if (numero.length === 10) {
                            if(nat == true){
                                if (digitoVerificador != d10){                          
                                    alertify.error('El número de cédula es incorrecto.');
                                    $("#identificacion").val("");
                                }else{
                                     if($("#identificacion").val() === "0000000000"){
                                        alertify.error('El número de cédula es incorrecto.');
                                        $("#identificacion").val("");
                                        }else{
                                            alertify.success('El número de cédula es correcto.');
                                    }
                                }
                            }
                        }
                 
                       
                    
                }
            }
        });
    });
    
    $("#btnGuardar").click(function(e) {
        e.preventDefault();
    });
    $("#btnBuscar").click(function(e) {
        e.preventDefault();
    });
    $("#btnModificar").click(function(e) {
        e.preventDefault();
    });
   
    $("#btnNuevo").click(function(e) {
        e.preventDefault();
    });
    $("#btnCuenta").click(function(e) {
        e.preventDefault();
    });
      
    $("#btnGuardar").on("click", guardar_transportista);
    $("#btnModificar").on("click", modificar_transportista);
   
    $("#btnNuevo").on("click", nuevo_transportista);
    $("#btnAceptar").on("click", aceptar);
    $("#btnSalir").on("click", cancelar);
    $("#btnAcceder").on("click", validar_acceso);
    $("#btnCancelar").on("click", cancelar_acceso);
    $("#btnBuscar").on("click", abrirDialogo);
      
     $("#transportista").dialog(dialogo);
    $("#clave_permiso_ven").dialog(dialogo3);
   
    $("#seguro_ven").dialog(dialogo4);
    
  
  
    jQuery("#list").jqGrid({
        url: 'datos_transportista.php',
        datatype: 'xml',
        colNames: ['Codigo',  'Cédula', 'Nombres',  'Direcciòn',  'Telèfono','Celular', 'Num Placa' ],
        colModel: [
            {name: 'id_transportista', index: 'id_transportista', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'identificacion', index: 'identificacion', editable: true, align: 'center', width: '120', search: true, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'nombres_trans', index: 'nombres_trans', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'direccion_trans', index: 'direccion_trans', editable: true, align: 'center', width: '120', search: false, frozen: false, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'telefono', index: 'telefono', editable: true, align: 'center', width: '120', search: false, frozen: false, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},       
            {name: 'celular', index: 'celular', editable: true, align: 'center', width: '120', search: false, frozen: false, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'num_placa', index: 'num_placa', editable: true, align: 'center', width: '120', search: false, frozen: false, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}}

        ],
        rowNum: 10,
        width: 830,
        height: 200,
        rowList: [10, 20, 30],
        pager: jQuery('#pager'),
        sortname: 'id_transportista',
        shrinkToFit: false,
        sortorder: 'asc',
        caption: 'Lista de Transportista',        
        viewrecords: true,
        ondblClickRow: function(){
        var id = jQuery("#list").jqGrid('getGridParam', 'selrow');
        jQuery('#list').jqGrid('restoreRow', id);
        jQuery("#list").jqGrid('GridToForm', id, "#transportista_form");
        $("#btnGuardar").attr("disabled", true);
        $("#transportista").dialog("close");    
        }
    }).jqGrid('navGrid', '#pager',
            {
                add: false,
                edit: false,
                del: false,
                refresh: true,
                search: true,
                view: true
            },
    {
        recreateForm: true, closeAfterEdit: true, checkOnUpdate: true, reloadAfterSubmit: true, closeOnEscape: true
    },
    {
        reloadAfterSubmit: true, closeAfterAdd: true, checkOnUpdate: true, closeOnEscape: true,
        bottominfo: "Todos los campos son obligatorios son obligatorios"
    },
    {
        width: 300, closeOnEscape: true
    },
    {
        closeOnEscape: true,        
        multipleSearch: false, overlay: false

    },
    {
    },
            {
                closeOnEscape: true
            }
    );    
   
    jQuery("#list").jqGrid('navButtonAdd', '#pager', {caption: "Añadir",
        onClickButton: function() {
            var id = jQuery("#list").jqGrid('getGridParam', 'selrow');
            jQuery('#list').jqGrid('restoreRow', id);
            var ret = jQuery("#list").jqGrid('getRowData', id);
            if (id) {
                jQuery("#list").jqGrid('GridToForm', id, "#transportista_form");
                $("#btnGuardar").attr("disabled", true);
                $("#transportista").dialog("close");
            } else {
              alertify.alert("Seleccione un fila");
            }
        }
    });    
}


