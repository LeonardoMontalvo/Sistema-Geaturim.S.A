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

var dialogo_conciliacion ={
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
        data: "ivacompra=" + $("#idIvaC").val()+"&ivaventa=" + $("#idIvaV").val()+"&cgeneral=" + $("#idCajaGeneral").val()+"&cchica=" + $("#idCajaChica").val()+"&ccobrar=" + $("#idCxc").val()+"&cpagar=" + $("#idCxp").val()+"&dcobrar=" + $("#idDxc").val()+"&valoriva=" + $("#valorIva").val()+"&mercaderia=" + $("#mercaderia").val(),
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

///////////////////DEPOSITOS EN TRANSITO/////////////
function entrar1(){
    if($("#des_deposito").val()=="" || $("#des_deposito").val().length <20){
        alertify.alert("La descripción tiene un valor menor a 20 caracteres")
    }else{
        if($("#val_deposito").val()>0 && $("#val_deposito").val()!="0.000"){
            //alertify.alert("Correcto");
            if($("#saldo_estado").val()!="" && $("#saldo_estado").val()>0){
                var filas = jQuery("#list_deposito").jqGrid("getRowData");
                if (filas.length == 0) {
                    var datarow = {
                        descripcion: $("#des_deposito").val(), 
                        valor: parseFloat($("#val_deposito").val()).toFixed(2)
                    };

                    su = jQuery("#list_deposito").jqGrid('addRowData', 1, datarow);
                    if($("#saldo_estado_fin").val()=="0.000"){
                        $("#saldo_estado_fin").val((parseFloat($("#saldo_estado").val())+parseFloat($("#val_deposito").val())).toFixed(2));
                    }else{
                        $("#saldo_estado_fin").val((parseFloat($("#saldo_estado_fin").val())+parseFloat($("#val_deposito").val())).toFixed(2));
                    }
                    $("#val_deposito").val("0.000");
                    $("#des_deposito").val("");
                }else{
                    var id = filas.length;
                    var datarow = {
                        descripcion: $("#des_deposito").val(), 
                        valor: parseFloat($("#val_deposito").val()).toFixed(2)
                    };

                    su = jQuery("#list_deposito").jqGrid('addRowData', id, datarow);
                    if($("#saldo_estado_fin").val()=="0.000"){
                        $("#saldo_estado_fin").val((parseFloat($("#saldo_estado").val())+parseFloat($("#val_deposito").val())).toFixed(2));
                    }else{
                        $("#saldo_estado_fin").val((parseFloat($("#saldo_estado_fin").val())+parseFloat($("#val_deposito").val())).toFixed(2));
                    }
                    $("#val_deposito").val("0.000");
                    $("#des_deposito").val("");
                }
            }else{
                alertify.alert("Ingrese el Saldo de Estado de Cuenta", function(){
                    $("#saldo_estado").focus();
                });
            }
        }else{
            alertify.alert("Ingrese un valor correcto");
        }
    }
}

////////////////CHEQUES GIRADOS Y NO COBRADOS////////////

function entrar2(){
    if($("#des_cheques").val()=="" || $("#des_cheques").val().length <20){
        alertify.alert("La descripción tiene un valor menor a 20 caracteres")
    }else{
        if($("#val_cheques").val()>0 && $("#val_cheques").val()!="0.000"){
            //alertify.alert("Correcto");
            if($("#saldo_estado").val()!="" && $("#saldo_estado").val()>0){
                var filas = jQuery("#list_cheques").jqGrid("getRowData");
                if (filas.length == 0) {
                    var datarow = {
                        descripcion: $("#des_cheques").val(), 
                        valor: (parseFloat($("#val_cheques").val())*(-1)).toFixed(2)
                    };

                    su = jQuery("#list_cheques").jqGrid('addRowData', 1, datarow);
                    if($("#saldo_estado_fin").val()=="0.000"){
                        $("#saldo_estado_fin").val((parseFloat($("#saldo_estado").val())+(parseFloat($("#val_cheques").val())*(-1))).toFixed(2));
                    }else{
                        $("#saldo_estado_fin").val((parseFloat($("#saldo_estado_fin").val())+(parseFloat($("#val_cheques").val())*(-1))).toFixed(2));
                    }
                    $("#val_cheques").val("0.000");
                    $("#des_cheques").val("");
                }else{
                    var id = filas.length;
                    var datarow = {
                        descripcion: $("#des_cheques").val(), 
                        valor: (parseFloat($("#val_cheques").val())*(-1)).toFixed(2)
                    };

                    su = jQuery("#list_cheques").jqGrid('addRowData', id, datarow);
                    if($("#saldo_estado_fin").val()=="0.000"){
                        $("#saldo_estado_fin").val((parseFloat($("#saldo_estado").val())+(parseFloat($("#val_cheques").val())*(-1))).toFixed(2));
                    }else{
                        $("#saldo_estado_fin").val((parseFloat($("#saldo_estado_fin").val())+(parseFloat($("#val_cheques").val())*(-1))).toFixed(2));
                    }
                    $("#val_cheques").val("0.000");
                    $("#des_cheques").val("");

                }
            }else{
                alertify.alert("Ingrese el Saldo de Estado de Cuenta", function(){
                    $("#saldo_estado").focus();
                });
            }
        }else{
            alertify.alert("Ingrese un valor correcto");
        }
    }
}

///////////////////OTROS POSITIVOS/////////////
function entrar3(){
    if($("#des_otros").val()=="" || $("#des_otros").val().length <20){
        alertify.alert("La descripción tiene un valor menor a 20 caracteres")
    }else{
        if($("#pos_otros").val()>0 && $("#pos_otros").val()!="0.000"){
            //alertify.alert("Correcto");
            if($("#saldo_estado").val()!="" && $("#saldo_estado").val()>0){
                var filas = jQuery("#list_otros").jqGrid("getRowData");
                if (filas.length == 0) {
                    var datarow = {
                        descripcion: $("#des_otros").val(), 
                        valor: parseFloat($("#pos_otros").val()).toFixed(2)
                    };

                    su = jQuery("#list_otros").jqGrid('addRowData', 1, datarow);
                    if($("#saldo_estado_fin").val()=="0.000"){
                        $("#saldo_estado_fin").val((parseFloat($("#saldo_estado").val())+parseFloat($("#pos_otros").val())).toFixed(2));
                    }else{
                        $("#saldo_estado_fin").val((parseFloat($("#saldo_estado_fin").val())+parseFloat($("#pos_otros").val())).toFixed(2));
                    }
                    $("#pos_otros").val("0.000");
                    $("#des_otros").val("");
                }else{
                    var id = filas.length;
                    var datarow = {
                        descripcion: $("#des_otros").val(), 
                        valor: parseFloat($("#pos_otros").val()).toFixed(2)
                    };

                    su = jQuery("#list_otros").jqGrid('addRowData', id, datarow);
                    if($("#saldo_estado_fin").val()=="0.000"){
                        $("#saldo_estado_fin").val((parseFloat($("#saldo_estado").val())+parseFloat($("#pos_otros").val())).toFixed(2));
                    }else{
                        $("#saldo_estado_fin").val((parseFloat($("#saldo_estado_fin").val())+parseFloat($("#pos_otros").val())).toFixed(2));
                    }
                    $("#pos_otros").val("0.000");
                    $("#des_otros").val("");

                }
            }else{
                alertify.alert("Ingrese el Saldo de Estado de Cuenta", function(){
                    $("#saldo_estado").focus();
                });
            }
        }else{
            alertify.alert("Ingrese un valor correcto");
        }
    }
}

////////////////OTROS NEGATIVOS////////////

function entrar4(){
    if($("#des_otros").val()=="" || $("#des_otros").val().length <20){
        alertify.alert("La descripción tiene un valor menor a 20 caracteres")
    }else{
        if($("#neg_otros").val()>0 && $("#neg_otros").val()!="0.000"){
            //alertify.alert("Correcto");
            if($("#saldo_estado").val()!="" && $("#saldo_estado").val()>0){
                var filas = jQuery("#list_otros").jqGrid("getRowData");
                if (filas.length == 0) {
                    var datarow = {
                        descripcion: $("#des_otros").val(), 
                        valor: (parseFloat($("#neg_otros").val())*(-1)).toFixed(2)
                    };

                    su = jQuery("#list_otros").jqGrid('addRowData', 1, datarow);
                    if($("#saldo_estado_fin").val()=="0.000"){
                        $("#saldo_estado_fin").val((parseFloat($("#saldo_estado").val())+(parseFloat($("#neg_otros").val())*(-1))).toFixed(2));
                    }else{
                        $("#saldo_estado_fin").val((parseFloat($("#saldo_estado_fin").val())+(parseFloat($("#neg_otros").val())*(-1))).toFixed(2));
                    }
                    $("#neg_otros").val("0.000");
                    $("#des_otros").val("");
                }else{
                    var id = filas.length;
                    var datarow = {
                        descripcion: $("#des_otros").val(), 
                        valor: (parseFloat($("#neg_otros").val())*(-1)).toFixed(2)
                    };

                    su = jQuery("#list_otros").jqGrid('addRowData', id, datarow);
                    if($("#saldo_estado_fin").val()=="0.000"){
                        $("#saldo_estado_fin").val((parseFloat($("#saldo_estado").val())+(parseFloat($("#neg_otros").val())*(-1))).toFixed(2));
                    }else{
                        $("#saldo_estado_fin").val((parseFloat($("#saldo_estado_fin").val())+(parseFloat($("#neg_otros").val())*(-1))).toFixed(2));
                    }
                    $("#neg_otros").val("0.000");
                    $("#des_otros").val("");

                }
            }else{
                alertify.alert("Ingrese el Saldo de Estado de Cuenta", function(){
                    $("#saldo_estado").focus();
                });
            }
        }else{
            alertify.alert("Ingrese un valor correcto");
        }
    }
}

///////////////////VALORES ACREDITADOS/////////////
function entrar5(){
    if($("#des_acreditado").val()=="" || $("#des_acreditado").val().length <20){
        alertify.alert("La descripción tiene un valor menor a 20 caracteres")
    }else{
        if($("#val_acreditado").val()>0 && $("#val_acreditado").val()!="0.000"){
            //alertify.alert("Correcto");
            if($("#saldo_libro").val()!="" && $("#saldo_libro").val()>0){
                var filas = jQuery("#list_acreditados").jqGrid("getRowData");
                if (filas.length == 0) {
                    var datarow = {
                        descripcion: $("#des_acreditado").val(), 
                        valorx: parseFloat($("#val_acreditado").val()).toFixed(2)
                    };

                    su = jQuery("#list_acreditados").jqGrid('addRowData', 1, datarow);
                    if($("#saldo_libro_fin").val()=="0.000"){
                        $("#saldo_libro_fin").val((parseFloat($("#saldo_libro").val())+parseFloat($("#val_acreditado").val())).toFixed(2));
                    }else{
                        $("#saldo_libro_fin").val((parseFloat($("#saldo_libro_fin").val())+parseFloat($("#val_acreditado").val())).toFixed(2));
                    }
                    $("#val_acreditado").val("0.000");
                    $("#des_acreditado").val("");
                }else{
                    var id = filas.length;
                    var datarow = {
                        descripcion: $("#des_acreditado").val(), 
                        valorx: parseFloat($("#val_acreditado").val()).toFixed(2)
                    };

                    su = jQuery("#list_acreditados").jqGrid('addRowData', id, datarow);
                    if($("#saldo_libro_fin").val()=="0.000"){
                        $("#saldo_libro_fin").val((parseFloat($("#saldo_libro").val())+parseFloat($("#val_acreditado").val())).toFixed(2));
                    }else{
                        $("#saldo_libro_fin").val((parseFloat($("#saldo_libro_fin").val())+parseFloat($("#val_acreditado").val())).toFixed(2));
                    }
                    $("#val_acreditado").val("0.000");
                    $("#des_acreditado").val("");

                }
            }else{
                alertify.alert("Ingrese el Saldo de Libros de Bancos", function(){
                    $("#saldo_libro").focus();
                });
            }
        }else{
            alertify.alert("Ingrese un valor correcto");
        }
    }
}

////////////////VALORES DEBITADOS////////////

function entrar6(){
    if($("#des_debitados").val()=="" || $("#des_debitados").val().length <20){
        alertify.alert("La descripción tiene un valor menor a 20 caracteres")
    }else{
        if($("#val_debitados").val()>0 && $("#val_debitados").val()!="0.000"){
            //alertify.alert("Correcto");
            if($("#saldo_libro").val()!="" && $("#saldo_libro").val()>0){
                var filas = jQuery("#list_debitados").jqGrid("getRowData");
                if (filas.length == 0) {
                    var datarow = {
                        descripcion: $("#des_debitados").val(), 
                        valorx: (parseFloat($("#val_debitados").val())*(-1)).toFixed(2)
                    };

                    su = jQuery("#list_debitados").jqGrid('addRowData', 1, datarow);
                    if($("#saldo_libro_fin").val()=="0.000"){
                        $("#saldo_libro_fin").val((parseFloat($("#saldo_libro").val())+(parseFloat($("#val_debitados").val())*(-1))).toFixed(2));
                    }else{
                        $("#saldo_libro_fin").val((parseFloat($("#saldo_libro_fin").val())+(parseFloat($("#val_debitados").val())*(-1))).toFixed(2));
                    }
                    $("#val_debitados").val("0.000");
                    $("#des_debitados").val("");
                }else{
                    var id = filas.length;
                    var datarow = {
                        descripcion: $("#des_debitados").val(), 
                        valorx: (parseFloat($("#val_debitados").val())*(-1)).toFixed(2)
                    };

                    su = jQuery("#list_debitados").jqGrid('addRowData', id, datarow);
                    if($("#saldo_libro_fin").val()=="0.000"){
                        $("#saldo_libro_fin").val((parseFloat($("#saldo_libro").val())+(parseFloat($("#val_debitados").val())*(-1))).toFixed(2));
                    }else{
                        $("#saldo_libro_fin").val((parseFloat($("#saldo_libro_fin").val())+(parseFloat($("#val_debitados").val())*(-1))).toFixed(2));
                    }
                    $("#val_debitados").val("0.000");
                    $("#des_debitados").val("");

                }
            }else{
                alertify.alert("Ingrese el Saldo de Libros de Bancos", function(){
                    $("#saldo_libro").focus();
                });
            }
        }else{
            alertify.alert("Ingrese un valor correcto");
        }
    }
}

function enter(e) {
    if (e.which === 13 || e.keyCode === 13) {
        entrar1();
        return false;
    }
    return true;
}

function enter2(e) {
    if (e.which === 13 || e.keyCode === 13) {
        entrar2();
        return false;
    }
    return true;
}

function enter3(e) {
    if (e.which === 13 || e.keyCode === 13) {
        entrar3();
        return false;
    }
    return true;
}

function enter4(e) {
    if (e.which === 13 || e.keyCode === 13) {
        entrar4();
        return false;
    }
    return true;
}

function enter5(e) {
    if (e.which === 13 || e.keyCode === 13) {
        entrar5();
        return false;
    }
    return true;
}

function enter6(e) {
    if (e.which === 13 || e.keyCode === 13) {
        entrar6();
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

function guardar_conciliacion(){
    if($("#idCuenta").val()=="" || $("#idCuenta").val()<=0){
        alertify.alert("Por favor seleccione una cuenta bancaria", function(){
            abrirCuenta();
        })
    }else{
        if($("#saldo_estado").val()=="" || $("#saldo_estado").val()<=0){
            alertify.alert("Por favor ingrese el saldo de estado de cuenta", function(){
                $("#saldo_estado").focus();
            });
        }else{
            if($("#saldo_libro").val()=="" || $("#saldo_libro").val()<=0){
                alertify.alert("Por favor ingrese el saldo de libros de bancos", function(){
                    $("#saldo_libro").focus();
                });
            }else{
                if($("#saldo_libro_fin").val()!=$("#saldo_estado_fin").val()){
                    alertify.alert("Los saldos no coinciden");
                }else{
                    var filas_deposito=jQuery("#list_deposito").jqGrid("getRowData");
                    var filas_cheques=jQuery("#list_cheques").jqGrid("getRowData");
                    var filas_otros=jQuery("#list_otros").jqGrid("getRowData");
                    var filas_acreditados=jQuery("#list_acreditados").jqGrid("getRowData");
                    var filas_debitados=jQuery("#list_debitados").jqGrid("getRowData");
                    var descripcion_depo="";
                    var descripcion_cheq="";
                    var descripcion_otr="";
                    var descripcion_acre="";
                    var descripcion_debi="";
                    var valor_depo="";
                    var valor_cheq="";
                    var valor_otr="";
                    var valor_acre="";
                    var valor_debi="";
                    var repe=0;
                    if(filas_deposito.length>0){
                        for (var i = 0; i < filas_deposito.length; i++) {
                            id=filas_deposito[i];
                            if(repe==0){
                                descripcion_depo=descripcion_depo+id['descripcion'];
                                valor_depo=valor_depo+id['valor'];
                                repe=1;
                            }else{
                                descripcion_depo=descripcion_depo+"**"+id['descripcion'];
                                valor_depo=valor_depo+"**"+id['valor'];
                            }
                        };
                    }
                    repe=0;
                    if(filas_cheques.length>0){
                        for (var i = 0; i < filas_cheques.length; i++) {
                            id=filas_cheques[i];
                            if(repe==0){
                                descripcion_cheq=descripcion_cheq+id['descripcion'];
                                valor_cheq=valor_cheq+id['valor'];
                                repe=1;
                            }else{
                                descripcion_cheq=descripcion_cheq+"**"+id['descripcion'];
                                valor_cheq=valor_cheq+"**"+id['valor'];
                            }
                        };
                    }
                    repe=0;
                    if(filas_otros.length>0){
                        for (var i = 0; i < filas_otros.length; i++) {
                            id=filas_otros[i];
                            if(repe==0){
                                descripcion_otr=descripcion_otr+id['descripcion'];
                                valor_otr=valor_otr+id['valor'];
                                repe=1;
                            }else{
                                descripcion_otr=descripcion_otr+"**"+id['descripcion'];
                                valor_otr=valor_otr+"**"+id['valor'];
                            }
                        };
                    }
                    repe=0;
                    if(filas_acreditados.length>0){
                        for (var i = 0; i < filas_acreditados.length; i++) {
                            id=filas_acreditados[i];
                            if(repe==0){
                                descripcion_acre=descripcion_acre+id['descripcion'];
                                valor_acre=valor_acre+id['valorx'];
                                repe=1;
                            }else{
                                descripcion_acre=descripcion_acre+"**"+id['descripcion'];
                                valor_acre=valor_acre+"**"+id['valorx'];
                            }
                        };
                    }
                    repe=0;
                    if(filas_debitados.length>0){
                        for (var i = 0; i < filas_debitados.length; i++) {
                            id=filas_debitados[i];
                            if(repe==0){
                                descripcion_debi=descripcion_debi+id['descripcion'];
                                valor_debi=valor_debi+id['valorx'];
                                repe=1;
                            }else{
                                descripcion_debi=descripcion_debi+"**"+id['descripcion'];
                                valor_debi=valor_debi+"**"+id['valorx'];
                            }
                        };
                    }
                    alertify.alert("idCuenta="+$("#idCuenta").val()+"&mes="+$("#mes").val()+"&anio="+$("#anio").val()+"&saldo_estado="+$("#saldo_estado").val()+"&saldo_libro="+$("#saldo_libro").val()+"&des_depo="+descripcion_depo+"&val_depo="+valor_depo+"&des_cheq="+descripcion_cheq+"&val_cheq="+valor_cheq+"&des_otr="+descripcion_otr+"&val_otr="+valor_otr+"&des_acre="+descripcion_acre+"&val_acre="+valor_acre+"&des_debi="+descripcion_debi+"&val_debi="+valor_debi+"&estado_fin="+$("#saldo_estado_fin").val()+"&libro_fin="+$("#saldo_libro_fin").val());
                    $.ajax({
                        type: "POST",
                        url: "guardar_conciliacion.php",
                        data: "idCuenta="+$("#idCuenta").val()+"&mes="+$("#mes").val()+"&anio="+$("#anio").val()+"&saldo_estado="+$("#saldo_estado").val()+"&saldo_libro="+$("#saldo_libro").val()+"&des_depo="+descripcion_depo+"&val_depo="+valor_depo+"&des_cheq="+descripcion_cheq+"&val_cheq="+valor_cheq+"&des_otr="+descripcion_otr+"&val_otr="+valor_otr+"&des_acre="+descripcion_acre+"&val_acre="+valor_acre+"&des_debi="+descripcion_debi+"&val_debi="+valor_debi+"&estado_fin="+$("#saldo_estado_fin").val()+"&libro_fin="+$("#saldo_libro_fin").val(),
                        success: function(data) {
                            var  val = data;
                            if (val != 0) {
                                alertify.alert("Conciliación Guardada Correctamente", function(){
                                    window.open("../../reportes/conciliacion_bancaria.php?hoja=A4&id="+$("#idConciliacion").val(),'_blank');
                                    location.reload();
                                });
                            }
                        }
                    });
                }
            }
        }
    }
}

function eliminar_conciliacion(){
    if($("#idConciliacion").val()!="" && $("#idConciliacion").val()>0){
        $.ajax({
            type: "POST",
            url: "eliminar_conciliacion.php",
            data: "idConciliacion="+$("#idConciliacion").val(),
            success: function(data) {
                var  val = data;
                if (val != 0) {
                    alertify.alert("Conciliación Eliminada Correctamente", function(){
                        location.reload();
                    });
                }
            }
        });
    }else{
        alertify.alert("La Conciliación Bancaria no se encuentra Guardada");
    }
}

function flecha_atras(){
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "conciliacion_bancaria" + "&id_tabla=" + "id_conciliacion_bancaria" + "&tipo=" + 1,
        success: function(data) {
            var val = data;
            if(val != ""){
                $("#idConciliacion").val(val);
                $("#comprobante").val(val);
                var valor = $("#idConciliacion").val();
                // llamar datos Factura Compra
                $("#btnGuardar").attr("disabled", true);
                $("#list_deposito").jqGrid("clearGridData", true);
                $("#list_cheques").jqGrid("clearGridData", true);
                $("#list_otros").jqGrid("clearGridData", true);
                $("#list_acreditados").jqGrid("clearGridData", true);
                $("#list_debitados").jqGrid("clearGridData", true);
                $("#list_deposito").jqGrid("clearGridData", true);
                $("#saldo_estado").val("0.000");
                $("#saldo_libro").val("0.000");
                $("#saldo_estado_fin").val("0.000");
                $("#saldo_libro_fin").val("0.000");
                $("#estado h3").remove();
                var descripciones="";
                var valores="";
                var x=0;
                $.getJSON('retornar_conciliacion_bancaria.php?com=' + valor, function(data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 19) {
                            $("#idConciliacion").val(data[i]);
                            $("#idCuenta").val(data[i + 1]);
                            $("#mes").val(data[i + 2]);
                            $("#anio").val(data[i + 3]);
                            $("#saldo_estado").val(data[i + 4]);
                            $("#saldo_libro").val(data[i + 5]);
                            
                            $("#saldo_estado_fin").val(data[i + 16]);
                            $("#saldo_libro_fin").val(data[i + 17]); 
                            descripciones=(data[i+6]).split("**");
                            valores=(data[i+7]).split("**");
                            x=descripciones.length;
                            for(var j=0; j<x; j++){
                                var datarow = {
                                    descripcion: descripciones[j],
                                    valor: valores[j]
                                }
                                var su = jQuery("#list_deposito").jqGrid('addRowData', data[j], datarow);
                            }
                            descripciones=(data[i+8]).split("**");
                            valores=(data[i+9]).split("**");
                            x=descripciones.length;
                            for(var j=0; j<x; j++){
                                var datarow = {
                                    descripcion: descripciones[j],
                                    valor: valores[j]
                                }
                                var su = jQuery("#list_cheques").jqGrid('addRowData', data[j], datarow);
                            }
                            descripciones=(data[i+10]).split("**");
                            valores=(data[i+11]).split("**");
                            x=descripciones.length;
                            for(var j=0; j<x; j++){
                                var datarow = {
                                    descripcion: descripciones[j],
                                    valor: valores[j]
                                }
                                var su = jQuery("#list_otros").jqGrid('addRowData', data[j], datarow);
                            }
                            descripciones=(data[i+12]).split("**");
                            valores=(data[i+13]).split("**");
                            x=descripciones.length;
                            for(var j=0; j<x; j++){
                                var datarow = {
                                    descripcion: descripciones[j],
                                    valorx: valores[j]
                                }
                                var su = jQuery("#list_acreditados").jqGrid('addRowData', data[j], datarow);
                            }
                            descripciones=(data[i+14]).split("**");
                            valores=(data[i+15]).split("**");
                            x=descripciones.length;
                            for(var j=0; j<x; j++){
                                var datarow = {
                                    descripcion: descripciones[j],
                                    valorx: valores[j]
                                }
                                var su = jQuery("#list_debitados").jqGrid('addRowData', data[j], datarow);
                            }

                            if(data[i + 18 ] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulada"));
                                $("#estado h3").css("color","red");
                                $("#btnEliminar").attr("disabled", "disabled");
                                $("#btnModificar").attr("disabled", true);
                            } else {
                                $("#estado h3").remove();
                                $("#btnEliminar").attr("disabled", "disabled");
                                $("#btnEliminar").attr("disabled", false);
                                $("#btnModificar").attr("disabled", false);
                            }
                        }
                    }
                });
                // Fin
            } else {
                alertify.alert("No hay más registros posteriores!!");
            }
        }
    });
}

function flecha_siguiente(){
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "conciliacion_bancaria" + "&id_tabla=" + "id_conciliacion_bancaria" + "&tipo=" + 2,
        success: function(data) {
            var val = data;
            if(val != ""){
                $("#idConciliacion").val(val);
                $("#comprobante").val(val);
                var valor = $("#idConciliacion").val();
                // llamar datos Factura Compra
                $("#btnGuardar").attr("disabled", true);
                $("#list_deposito").jqGrid("clearGridData", true);
                $("#list_cheques").jqGrid("clearGridData", true);
                $("#list_otros").jqGrid("clearGridData", true);
                $("#list_acreditados").jqGrid("clearGridData", true);
                $("#list_debitados").jqGrid("clearGridData", true);
                $("#list_deposito").jqGrid("clearGridData", true);
                $("#saldo_estado").val("0.000");
                $("#saldo_libro").val("0.000");
                $("#saldo_estado_fin").val("0.000");
                $("#saldo_libro_fin").val("0.000");
                $("#estado h3").remove();
                var descripciones="";
                var valores="";
                var x=0;
                $.getJSON('retornar_conciliacion_bancaria.php?com=' + valor, function(data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 19) {
                            $("#idConciliacion").val(data[i]);
                            $("#idCuenta").val(data[i + 1]);
                            $("#mes").val(data[i + 2]);
                            $("#anio").val(data[i + 3]);
                            $("#saldo_estado").val(data[i + 4]);
                            $("#saldo_libro").val(data[i + 5]);
                            
                            $("#saldo_estado_fin").val(data[i + 16]);
                            $("#saldo_libro_fin").val(data[i + 17]); 
                            descripciones=(data[i+6]).split("**");
                            valores=(data[i+7]).split("**");
                            x=descripciones.length;
                            for(var j=0; j<x; j++){
                                var datarow = {
                                    descripcion: descripciones[j],
                                    valor: valores[j]
                                }
                                var su = jQuery("#list_deposito").jqGrid('addRowData', data[j], datarow);
                            }
                            descripciones=(data[i+8]).split("**");
                            valores=(data[i+9]).split("**");
                            x=descripciones.length;
                            for(var j=0; j<x; j++){
                                var datarow = {
                                    descripcion: descripciones[j],
                                    valor: valores[j]
                                }
                                var su = jQuery("#list_cheques").jqGrid('addRowData', data[j], datarow);
                            }
                            descripciones=(data[i+10]).split("**");
                            valores=(data[i+11]).split("**");
                            x=descripciones.length;
                            for(var j=0; j<x; j++){
                                var datarow = {
                                    descripcion: descripciones[j],
                                    valor: valores[j]
                                }
                                var su = jQuery("#list_otros").jqGrid('addRowData', data[j], datarow);
                            }
                            descripciones=(data[i+12]).split("**");
                            valores=(data[i+13]).split("**");
                            x=descripciones.length;
                            for(var j=0; j<x; j++){
                                var datarow = {
                                    descripcion: descripciones[j],
                                    valorx: valores[j]
                                }
                                var su = jQuery("#list_acreditados").jqGrid('addRowData', data[j], datarow);
                            }
                            descripciones=(data[i+14]).split("**");
                            valores=(data[i+15]).split("**");
                            x=descripciones.length;
                            for(var j=0; j<x; j++){
                                var datarow = {
                                    descripcion: descripciones[j],
                                    valorx: valores[j]
                                }
                                var su = jQuery("#list_debitados").jqGrid('addRowData', data[j], datarow);
                            }

                            if(data[i + 18 ] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulada"));
                                $("#estado h3").css("color","red");
                                $("#btnEliminar").attr("disabled", "disabled");
                                $("#btnModificar").attr("disabled", true);
                            } else {
                                $("#estado h3").remove();
                                $("#btnEliminar").attr("disabled", "disabled");
                                $("#btnEliminar").attr("disabled", false);
                                $("#btnModificar").attr("disabled", false);
                            }
                        }
                    }
                });
                // Fin
            } else {
                alertify.alert("No hay más registros superiores!!");
            }
        }
    });
}

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
    $("#btnEliminar").click(function(e) {
        e.preventDefault();
    });
    $("#btnImprimir").click(function(e) {
        e.preventDefault();
    });
    $("#btnCuenta").click(function(e) {
        e.preventDefault();
    });
    $("#btnAtras").click(function(e) {
        e.preventDefault();
    });
    $("#btnSiguiente").click(function(e) {
        e.preventDefault();
    });
    $("#btnNuevo").click(function(e) {
        e.preventDefault();
    });
    $("#btnBuscar").click(function(e) {
        e.preventDefault();
    });

    $("#buscar_conciliacion").dialog(dialogo_conciliacion);
    $("#btnBuscar").click(function (){
        $("#buscar_conciliacion").dialog("open");   
    });

    $("#btnGuardar").on("click", guardar_conciliacion);
    $("#btnEliminar").on("click", eliminar_conciliacion);
    $("#btnAtras").on("click", flecha_atras);
    $("#btnSiguiente").on("click", flecha_siguiente);
    $("#btnAceptar").on("click", aceptar);
    $("#btnSalir").on("click", cancelar);
    $("#btnAcceder").on("click", validar_acceso);
    $("#btnCancelar").on("click", cancelar_acceso);
    $("#btnCuenta").on("click", abrirCuenta)
    $("#btnImprimir").on("click", function(){
        $.ajax({
            type: "POST",
            url: "../../procesos/validacion.php",
            data: "comprobante=" + $("#idConciliacion").val() + "&tabla=" + "conciliacion_bancaria" + "&id_tabla=" + "id_conciliacion_bancaria" + "&tipo=" + 1,
            success: function(data) {
                var val = data;
                if(val != "") {
                    window.open("../../reportes/conciliacion_bancaria.php?hoja=A4&id="+$("#idConciliacion").val(),'_blank');  
                } else {
                  alertify.alert("Conciliación no creada!!");
                }   
            }
        });
    });
    $("#btnNuevo").on("click", function(){
        location.reload();
    });
    
    $("#clave_permiso").dialog(dialogo3);
    $("#seguro").dialog(dialogo4);
    $("#cuentas").dialog(dialogo_cuenta);
    
    $("#fecha_creacion").datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');

    $("#val_deposito").on("keypress", enter);
    $("#val_cheques").on("keypress", enter2);
    $("#pos_otros").on("keypress", enter3);
    $("#neg_otros").on("keypress", enter4);
    $("#val_acreditado").on("keypress", enter5);
    $("#val_debitados").on("keypress", enter6);

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
        url: 'xmlCuentas_Bancos.php',
        datatype: 'xml',
        colNames: ['Id Cuenta', 'Número Cuenta', 'Banco', 'Código Plan'],
        colModel: [            
            {name: 'id_cuenta_banco', index: 'id_cuenta_banco', editable: true, align: 'left', width: '100', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'numero_cuenta', index: 'numero_cuenta', editable: true, align: 'center', width: '150', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'banco', index: 'banco', editable: true, align: 'center', width: '250', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'plan_cuentas', index: 'plan_cuentas', editable: true, align: 'center', width: '150', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}}
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        height: 255,
        pager: jQuery('#pager2'),
        sortname: 'id_cuenta_banco',
        shrinkToFit: false,
        sortordezr: 'asc',
        caption: 'Cuentas Bancarias',
        viewrecords: true,
        ondblClickRow: function(){
            var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
            jQuery('#list2').jqGrid('restoreRow', id);   
            var ret = jQuery("#list2").jqGrid('getRowData', id);
            var ccuenta = jQuery("#list2").jqGrid('getCell', id, 1)+"  -  "+jQuery("#list2").jqGrid('getCell', id, 2);
            $("#idCuenta").val(id);
            $("#cuenta").val(ccuenta);
            document.getElementById("cuenta").readOnly = true;
            $("#cuentas").dialog("close");  
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

/////////////////////////////////////
    jQuery("#list_deposito").jqGrid({
        datatype: "local",
        colNames: ['','Descripción', 'Valor', ''],
        colModel: [     
            {name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: {keys: false, delbutton: true, editbutton: false}},       
            {name: 'descripcion', index: 'descripcion', editable: true, align: 'left', width: '800', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'valor', index: 'valor', editable: true, align: 'center', width: '150', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'valorx', index: 'valorx', editable: true, align: 'center', width: '150', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}}
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        sortable: true,
        height: 100,
        pager: jQuery('#pager'),
        sortname: 'descripcion',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'depositoArray',
        shrinkToFit: true
    });  
    jQuery("#list_deposito").setGridWidth($('#pager_dep').width()); 

    /////////////////////////////////
    jQuery("#list_cheques").jqGrid({
        datatype: "local",
        colNames: ['','Descripción', 'Valor', ''],
        colModel: [     
            {name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: {keys: false, delbutton: true, editbutton: false}},       
            {name: 'descripcion', index: 'descripcion', editable: true, align: 'left', width: '800', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'valor', index: 'valor', editable: true, align: 'center', width: '150', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'valorx', index: 'valorx', editable: true, align: 'center', width: '150', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}}
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        sortable: true,
        height: 100,
        pager: jQuery('#pager'),
        sortname: 'descripcion',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'depositoArray',
        shrinkToFit: true
    });  
    jQuery("#list_cheques").setGridWidth($('#pager').width()); 

    /////////////////////////////////
    jQuery("#list_otros").jqGrid({
        datatype: "local",
        colNames: ['','Descripción', 'Valor', ''],
        colModel: [     
            {name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: {keys: false, delbutton: true, editbutton: false}},       
            {name: 'descripcion', index: 'descripcion', editable: true, align: 'left', width: '800', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'valor', index: 'valor', editable: true, align: 'center', width: '150', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'valorx', index: 'valorx', editable: true, align: 'center', width: '150', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}}
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        sortable: true,
        height: 100,
        pager: jQuery('#pager'),
        sortname: 'descripcion',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'depositoArray',
        shrinkToFit: true
    });  
    jQuery("#list_otros").setGridWidth($('#pager').width()); 

    /////////////////////////////////
    jQuery("#list_acreditados").jqGrid({
        datatype: "local",
        colNames: ['','Descripción', 'Valor', ''],
        colModel: [     
            {name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: {keys: false, delbutton: true, editbutton: false}},       
            {name: 'descripcion', index: 'descripcion', editable: true, align: 'left', width: '800', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'valor', index: 'valor', editable: true, align: 'center', width: '150', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'valorx', index: 'valorx', editable: true, align: 'center', width: '150', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}}
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        sortable: true,
        height: 100,
        pager: jQuery('#pager'),
        sortname: 'descripcion',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'depositoArray',
        shrinkToFit: true
    });  
    jQuery("#list_acreditados").setGridWidth($('#pager').width()); 

    /////////////////////////////////
    jQuery("#list_debitados").jqGrid({
        datatype: "local",
        colNames: ['','Descripción', 'Valor', ''],
        colModel: [     
            {name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: {keys: false, delbutton: true, editbutton: false}},       
            {name: 'descripcion', index: 'descripcion', editable: true, align: 'left', width: '800', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'valor', index: 'valor', editable: true, align: 'center', width: '150', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'valorx', index: 'valorx', editable: true, align: 'center', width: '150', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}}
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        sortable: true,
        height: 100,
        pager: jQuery('#pager'),
        sortname: 'descripcion',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'depositoArray',
        shrinkToFit: true
    });  
    jQuery("#list_debitados").setGridWidth($('#pager').width()); 

    /*jQuery(window).bind('resize', function () {
        jQuery("#list_deposito").setGridWidth(jQuery('#grid_deposito').width(), false);
    }).trigger('resize');*/

    // buscador facturas compra 
        jQuery("#list3").jqGrid({
        url: 'xmlBuscarConciliacionBancaria.php',
        datatype: 'xml',
        colNames: ['ID','ID CUENTA','CUENTA', 'BANCO','MES','AÑO', 'ESTADO CUENTA', 'LIBRO BANCOS'],
        colModel: [
            {name: 'id_conciliacion_bancaria', index: 'id_conciliacion_bancaria', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 50},
            {name: 'id_cuenta_banco', index: 'id_cuenta_banco', editable: false, search: true, hidden: true, editrules: {edithidden: false}, align: 'center',frozen: true, width: 120},
            {name: 'cuenta', index: 'cuenta', editable: true, search: true, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 200},
            {name: 'banco', index: 'banco', editable: true, search: true, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 200},
            {name: 'mes', index: 'mes', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 100},
            {name: 'anio', index: 'anio', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 100},
            {name: 'estado_cuenta', index: 'estado_cuenta', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 135},
            {name: 'libro_bancos', index: 'libro_bancos', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 135}
        ],
        rowNum: 30,
        width: 750,
        height:220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager3'),
        sortname: 'id_conciliacion_bancaria',
        sortorder: 'asc',
        viewrecords: true,              
        ondblClickRow: function(){
            var id = jQuery("#list3").jqGrid('getGridParam', 'selrow');
            jQuery('#list3').jqGrid('restoreRow', id);
            if (id) {
                var ret = jQuery("#list3").jqGrid('getRowData', id);
                var valor = ret.id_conciliacion_bancaria;

                $("#idConciliacion").val(valor);
                $("#comprobante").val(valor);
                // llamar datos Factura Compra
                $("#btnGuardar").attr("disabled", true);
                $("#list_deposito").jqGrid("clearGridData", true);
                $("#list_cheques").jqGrid("clearGridData", true);
                $("#list_otros").jqGrid("clearGridData", true);
                $("#list_acreditados").jqGrid("clearGridData", true);
                $("#list_debitados").jqGrid("clearGridData", true);
                $("#list_deposito").jqGrid("clearGridData", true);
                $("#saldo_estado").val("0.000");
                $("#saldo_libro").val("0.000");
                $("#saldo_estado_fin").val("0.000");
                $("#saldo_libro_fin").val("0.000");
                $("#estado h3").remove();
                var descripciones="";
                var valores="";
                var x=0;
                $.getJSON('retornar_conciliacion_bancaria.php?com=' + valor, function(data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 19) {
                            $("#idConciliacion").val(data[i]);
                            $("#idCuenta").val(data[i + 1]);
                            $("#mes").val(data[i + 2]);
                            $("#anio").val(data[i + 3]);
                            $("#saldo_estado").val(data[i + 4]);
                            $("#saldo_libro").val(data[i + 5]);
                            
                            $("#saldo_estado_fin").val(data[i + 16]);
                            $("#saldo_libro_fin").val(data[i + 17]); 
                            descripciones=(data[i+6]).split("**");
                            valores=(data[i+7]).split("**");
                            x=descripciones.length;
                            for(var j=0; j<x; j++){
                                var datarow = {
                                    descripcion: descripciones[j],
                                    valor: valores[j]
                                }
                                var su = jQuery("#list_deposito").jqGrid('addRowData', data[j], datarow);
                            }
                            descripciones=(data[i+8]).split("**");
                            valores=(data[i+9]).split("**");
                            x=descripciones.length;
                            for(var j=0; j<x; j++){
                                var datarow = {
                                    descripcion: descripciones[j],
                                    valor: valores[j]
                                }
                                var su = jQuery("#list_cheques").jqGrid('addRowData', data[j], datarow);
                            }
                            descripciones=(data[i+10]).split("**");
                            valores=(data[i+11]).split("**");
                            x=descripciones.length;
                            for(var j=0; j<x; j++){
                                var datarow = {
                                    descripcion: descripciones[j],
                                    valor: valores[j]
                                }
                                var su = jQuery("#list_otros").jqGrid('addRowData', data[j], datarow);
                            }
                            descripciones=(data[i+12]).split("**");
                            valores=(data[i+13]).split("**");
                            x=descripciones.length;
                            for(var j=0; j<x; j++){
                                var datarow = {
                                    descripcion: descripciones[j],
                                    valorx: valores[j]
                                }
                                var su = jQuery("#list_acreditados").jqGrid('addRowData', data[j], datarow);
                            }
                            descripciones=(data[i+14]).split("**");
                            valores=(data[i+15]).split("**");
                            x=descripciones.length;
                            for(var j=0; j<x; j++){
                                var datarow = {
                                    descripcion: descripciones[j],
                                    valorx: valores[j]
                                }
                                var su = jQuery("#list_debitados").jqGrid('addRowData', data[j], datarow);
                            }

                            if(data[i + 18 ] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulada"));
                                $("#estado h3").css("color","red");
                                $("#btnEliminar").attr("disabled", "disabled");
                                $("#btnModificar").attr("disabled", true);
                            } else {
                                $("#estado h3").remove();
                                $("#btnEliminar").attr("disabled", "disabled");
                                $("#btnEliminar").attr("disabled", false);
                                $("#btnModificar").attr("disabled", false);
                            }
                        }
                    }
                });
                $("#buscar_conciliacion").dialog("close");
            }

        }
    });
}
