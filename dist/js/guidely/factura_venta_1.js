$(document).on("ready", inicio);

function evento(e) {
    e.preventDefault();
}

$(function() {
    $('#main-menu').smartmenus({
        subMenusSubOffsetX: 1,
        subMenusSubOffsetY: -8
    });
});

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

function show() {
    var Digital = new Date();
    var hours = Digital.getHours();
    var minutes = Digital.getMinutes();
    var seconds = Digital.getSeconds();
    var dn = "AM";    
    if (hours > 12) {
        dn = "PM";
        hours = hours - 12;
    }
    if (hours === 0)
        hours = 12;
    if (minutes <= 9)
        minutes = "0" + minutes;
    if (seconds <= 9)
        seconds = "0" + seconds;
    $("#hora_actual").val(hours + ":" + minutes + ":" + seconds + " " + dn);

    setTimeout("show()", 1000);
}

var dialogo =
{
    autoOpen: false,
    resizable: false,
    width: 600,
    height: 420,
    modal: true
};

var dialogo2 =
{
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 350,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind"    
}

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
    width: 800,
    height: 350,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind"
    
}

function ValidNum(e) {
    if (e.keyCode < 48 || e.keyCode > 57) {
        e.returnValue = false;
    }
    return true;
}

function enter(e) {
    if (e.which === 13 || e.keyCode === 13) {
        entrar();
        return false;
    }
    return true;
}

function enter1(e) {
    if (e.which === 13 || e.keyCode === 13) {
        entrar2();
        return false;
    }
    return true;
}

function enter2(e) {
    if (e.which === 13 || e.keyCode === 13) {
        entrar3();
        return false;
    }
    return true;
}

function enter3(e) {
    if (e.which === 13 || e.keyCode === 13) {
        comprobar();
        return false;
    }
    return true;
}

function enter4(e) {
    if (e.which === 13 || e.keyCode === 13) {
        comprobar1();
        return false;
    }
    return true;
}

function enter5(e) {
    if (e.which === 13 || e.keyCode === 13) {
        comprobar2();
        return false;
    }
    return true;
}

function entrar() {
    if ($("#cod_producto").val() === "") {
        $("#codigo").focus();
        alertify.alert("Ingrese un producto");
    } else {
        if ($("#codigo").val() === "") {
            $("#codigo").focus();
            alertify.alert("Ingrese un producto");
        } else {
            if ($("#producto").val() === "") {
                $("#producto").focus();
                alertify.alert("Ingrese un producto");
            } else {
                if ($("#cantidad").val() === "") {
                    $("#cantidad").focus();
                } else {
                    $("#p_venta").focus();
                }
            }
        }
    }
}

function entrar2() {
    if ($("#cod_producto").val() === "") {
        $("#codigo").focus();
        alertify.alert("Ingrese un producto");
    } else {
        if ($("#codigo").val() === "") {
            $("#codigo").focus();
            alertify.alert("Ingrese un producto");
        } else {
            if ($("#producto").val() === "") {
                $("#producto").focus();
                alertify.alert("Ingrese un producto");
            } else {
                if ($("#cantidad").val() === "") {
                    $("#cantidad").focus();
                } else {
                    if ($("#p_venta").val() === "") {
                        $("#p_venta").focus();
                        alertify.alert("Ingrese precio venta");
                    } else {
                        $("#descuento").focus();
                    }
                }
            }
        }
    }
}

function entrar3() {
    if ($("#cod_producto").val() === "") {
        $("#codigo").focus();
        alertify.alert("Ingrese un producto");
    } else {
        if ($("#codigo").val() === "") {
            $("#codigo").focus();
            alertify.alert("Ingrese un producto");
        } else {
            if ($("#producto").val() === "") {
                $("#producto").focus();
                alertify.alert("Ingrese un producto");
            } else {
                if ($("#cantidad").val() === "") {
                    $("#cantidad").focus();
                } else {
                    if ($("#p_venta").val() === "") {
                        $("#p_venta").focus();
                        alertify.alert("Ingrese un precio");
                    } else {
                        if (parseInt($("#cantidad").val()) > parseInt($("#disponibles").val())) {
                            $("#cantidad").focus();
                            alertify.alert("Error.. La cantidad ingresada es mayor a la disponible");
                        }else {
                            var filas = jQuery("#list").jqGrid("getRowData");
                            var descuento = 0;
                            var total = 0;
                            var su = 0;
                            var desc = 0;
                            var precio = 0;
                            var multi = 0;
                            var flotante = 0;
                            var resultado = 0;
                            var repe = 0;
                            var suma = 0;    
                            if (filas.length === 0) {
                                if ($("#descuento").val() !== "") {
                                    desc = $("#descuento").val();
                                    precio = (parseFloat($("#p_venta").val())).toFixed(2);
                                    multi = (parseFloat($("#cantidad").val()) * parseFloat($("#p_venta").val())).toFixed(2);
                                    descuento = ((multi * parseFloat($("#descuento").val())) / 100);
                                    flotante = parseFloat(descuento);
                                    resultado = (Math.round(flotante * Math.pow(10,2)) / Math.pow(10,2)).toFixed(2);
                                    total = (multi - resultado).toFixed(2);
                                } else {
                                    desc = 0;
                                    precio = (parseFloat($("#p_venta").val())).toFixed(2);
                                    total = (parseFloat($("#cantidad").val()) * precio).toFixed(2);
                                }
                                
                                var datarow = {
                                    cod_producto: $("#cod_producto").val(), 
                                    codigo: $("#codigo").val(), 
                                    detalle: $("#producto").val(), 
                                    cantidad: $("#cantidad").val(), 
                                    precio_u: precio, 
                                    descuento: desc, 
                                    cal_des: resultado,
                                    total: total, 
                                    iva: $("#iva_producto").val(), 
                                    pendiente: 0
                                };
                                su = jQuery("#list").jqGrid('addRowData', $("#cod_producto").val(), datarow);
                                $("#cod_producto").val("");
                                $("#codigo_barras").val("");
                                $("#codigo").val("");
                                $("#producto").val("");
                                $("#cantidad").val("");
                                $("#p_venta").val("");
                                $("#descuento").val("");
                                $("#disponibles").val("");
                                $('#combobox').children().remove().end();
                            }
                            else {
                                for (var i = 0; i < filas.length; i++) {
                                    var id = filas[i];
                                    var can = id['cantidad'];
                                    if (id['cod_producto'] === $("#cod_producto").val()) {
                                        repe = 1;
                                    }
                                }
                                if (repe === 1) {
                                    suma = parseInt(can) + parseInt($("#cantidad").val());
                                    if(suma > parseInt($("#disponibles").val())){
                                        $("#cantidad").focus();
                                        alertify.alert("Error.. La cantidad ingresada es mayor a la disponible");  
                                    }else{
                                        if ($("#descuento").val() !== "") {
                                            desc = $("#descuento").val();
                                            precio = (parseFloat($("#p_venta").val())).toFixed(2);
                                            multi = (parseFloat(suma) * parseFloat($("#p_venta").val())).toFixed(2);
                                            descuento = ((multi * parseFloat($("#descuento").val())) / 100);
                                            flotante = parseFloat(descuento) ;
                                            resultado = (Math.round(flotante * Math.pow(10,2)) / Math.pow(10,2)).toFixed(2);
                                            total = (multi - resultado).toFixed(2);
                                        } else {
                                            desc = 0;
                                            precio = (parseFloat($("#p_venta").val())).toFixed(2);
                                            total = (parseFloat($("#cantidad").val()) * precio).toFixed(2);
                                        }
                                    
                                        datarow = {
                                            cod_producto: $("#cod_producto").val(), 
                                            codigo: $("#codigo").val(), 
                                            detalle: $("#producto").val(), 
                                            cantidad: suma, 
                                            precio_u: precio, 
                                            descuento: desc, 
                                            cal_des: resultado,
                                            total: total, 
                                            iva: $("#iva_producto").val(), 
                                            pendiente: 0
                                        };
                                    
                                        su = jQuery("#list").jqGrid('setRowData', $("#cod_producto").val(), datarow);
                                        $("#cod_producto").val("");
                                        $("#codigo_barras").val("");
                                        $("#codigo").val("");
                                        $("#producto").val("");
                                        $("#cantidad").val("");
                                        $("#p_venta").val("");
                                        $("#descuento").val("");
                                        $("#disponibles").val("");
                                        $('#combobox').children().remove().end();
                                    }
                                }
                                else {
                                    if(filas.length < 25){
                                        if ($("#descuento").val() !== "") {
                                            desc = $("#descuento").val();
                                            precio = (parseFloat($("#p_venta").val())).toFixed(2);
                                            multi = (parseFloat($("#cantidad").val()) * parseFloat($("#p_venta").val())).toFixed(2);
                                            descuento = ((multi * parseFloat($("#descuento").val())) / 100);
                                            flotante = parseFloat(descuento) ;
                                            resultado = (Math.round(flotante * Math.pow(10,2)) / Math.pow(10,2)).toFixed(2);
                                            total = (multi - resultado).toFixed(2);
                                        } else {
                                            desc = 0;
                                            precio = (parseFloat($("#p_venta").val())).toFixed(2);
                                            total = (parseFloat($("#cantidad").val()) * precio).toFixed(2);
                                        }
                                    
                                        datarow = {
                                            cod_producto: $("#cod_producto").val(), 
                                            codigo: $("#codigo").val(), 
                                            detalle: $("#producto").val(), 
                                            cantidad: $("#cantidad").val(), 
                                            precio_u: precio, 
                                            descuento: desc, 
                                            cal_des: resultado,
                                            total: total, 
                                            iva: $("#iva_producto").val(), 
                                            pendiente: 0
                                        };
                                        su = jQuery("#list").jqGrid('addRowData', $("#cod_producto").val(), datarow);
                                        $("#cod_producto").val("");
                                        $("#codigo_barras").val("");
                                        $("#codigo").val("");
                                        $("#producto").val("");
                                        $("#cantidad").val("");
                                        $("#p_venta").val("");
                                        $("#descuento").val("");
                                        $("#disponibles").val("");
                                        $('#combobox').children().remove().end();
                                    }else{
                                        alertify.alert("Error... Alcanzo el limite máximo de Items");
                                    }
                                }
                            }

                            ////////////calcular valores//////////////////
                            var subtotal = 0;
                            var iva = 0;
                            var t_fc = 0;
                            var sub = 0;
                            var descu_total = 0;                        
                            if ($("#iva_producto").val() === "Si") {
                                var fil = jQuery("#list").jqGrid("getRowData");
                                for (var t = 0; t < fil.length; t++) {
                                    var dd = fil[t];
                                    if (dd['iva'] === "Si") {
                                        subtotal = (parseFloat(subtotal) + parseFloat(dd['total'])).toFixed(2);
                                        sub = (parseFloat((subtotal / 1.12))).toFixed(3);
                                        iva = (sub * 0.12).toFixed(3);
                                        descu_total = (parseFloat(descu_total) + parseFloat(dd['cal_des'])).toFixed(2);
                                        t_fc = ((parseFloat(sub) + (parseFloat(iva)) + parseFloat($("#total_p").val()))).toFixed(2);
                                        $("#iva_producto").val("");
                                    }
                                }
                                $("#total_p2").val(sub);
                                $("#iva").val(iva);
                                $("#desc").val(descu_total);
                                $("#tot").val(t_fc);
                            } else {
                                if ($("#iva_producto").val() === "No") {
                                    fil = jQuery("#list").jqGrid("getRowData");
                                    for (t = 0; t < fil.length; t++) {
                                        dd = fil[t];
                                        if (dd['iva'] === "No") {
                                            subtotal = (parseFloat(subtotal) + parseFloat(dd['total']));
                                            sub = parseFloat(subtotal).toFixed(2);
                                            descu_total = (parseFloat($("#desc").val()) + parseFloat(dd['cal_des'])).toFixed(2);
                                            t_fc = ((parseFloat(sub)) + parseFloat($("#tot").val())).toFixed(2);
                                            $("#iva_producto").val("");
                                        }
                                    }
                                    $("#total_p").val(sub);
                                    $("#desc").val(descu_total);
                                    $("#tot").val(t_fc);
                                }
                            }
                            $("#codigo_barras").focus();
                        }
                    }
                }
            }
        }
    }
}

function abrirDialogo() {
    var cod = $("#cod_producto").val();
    
    if (cod === "") {
        alertify.alert("Error... Seleccione un producto");
    } else {
        $("#combobox").append('<option></option>');
        $.getJSON('../procesos/retornar_series.php?cod=' + cod, function(data) {
            var tama = data.length;
            if (tama == 0) {
                alertify.alert("Series no ingresadas"); 
            }else{
                if($("#cantidad").val() == ""){
                    $("#cantidad").focus();
                    alertify.alert("Error... Indique una cantidad");
                }else{
                    $('#combobox').children().remove().end();
                    $("#series").dialog("open");
                    $("#combobox").append('<option></option>');
                    for (var i = 0; i < tama; i = i + 1)
                    {
                        $("#combobox").append('<option value='+data[i]+' >'+data[i]+'</option>');
                    }
                    $.widget( "custom.combobox", {
                        _create: function() {
                            this.wrapper = $( "<span>" )
                            .addClass( "custom-combobox" )
                            .insertAfter( this.element );

                            this.element.hide();
                            this._createAutocomplete();
                            this._createShowAllButton();
                        },

                        _createAutocomplete: function() {
                            var selected = this.element.children( ":selected" ),
                            value = selected.val() ? selected.text() : "";

                            this.input = $( "<input>" )
                            .appendTo( this.wrapper )
                            .val( value )
                            .attr( "title", "" )
                            .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
                            .autocomplete({
                                delay: 0,
                                minLength: 0,
                                source: $.proxy( this, "_source" )
                            })
                            .tooltip({
                                tooltipClass: "ui-state-highlight"
                            });

                            this._on( this.input, {
                                autocompleteselect: function( event, ui ) {
                                    ui.item.option.selected = true;
                                    this._trigger( "select", event, {
                                        item: ui.item.option
                                    });
                                },

                                autocompletechange: "_removeIfInvalid"
                            });
                        },
                        
                        _createShowAllButton: function() {
                            var input = this.input,
                            wasOpen = false;

                            $( "<a>" )
                            .attr( "tabIndex", -1 )
                            .attr( "title", "Todas las series" )
                            .tooltip()
                            .appendTo( this.wrapper )
                            .button({
                                icons: {
                                    primary: "ui-icon-triangle-1-s"
                                },
                                text: false
                            })
                            .removeClass( "ui-corner-all" )
                            .addClass( "custom-combobox-toggle ui-corner-right" )
                            .mousedown(function() {
                                wasOpen = input.autocomplete( "widget" ).is( ":visible" );
                            })
                            .click(function() {
                                input.focus();

                                if ( wasOpen ) {
                                    return;
                                }
                                input.autocomplete( "search", "" );
                            });
                        },

                        _source: function( request, response ) {
                            var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
                            response( this.element.children( "option" ).map(function() {
                                var text = $( this ).text();
                                if ( this.value && ( !request.term || matcher.test(text) ) )
                                    return {
                                        label: text,
                                        value: text,
                                        option: this
                                    };
                            }) );
                        },

                        _removeIfInvalid: function( event, ui ) {
                            if ( ui.item ) {
                                return;
                            }
                            var value = this.input.val(),
                            valueLowerCase = value.toLowerCase(),
                            valid = false;
                            this.element.children( "option" ).each(function() {
                                if ( $( this ).text().toLowerCase() === valueLowerCase ) {
                                    this.selected = valid = true;
                                    return false;
                                }
                            });
                            if ( valid ) {
                                return;
                            }
                            this.input
                            .val( "" )
                            .attr( "title", value + " La serie no existe" )
                            .tooltip( "open" );
                            this.element.val( "" );
                            this._delay(function() {
                                this.input.tooltip( "close" ).attr( "title", "" );
                            }, 2500 );
                            this.input.autocomplete( "instance" ).term = "";
                        },
                        _destroy: function() {
                            this.wrapper.remove();
                            this.element.show();
                        }
                    });
                    $("#combobox" ).combobox();
                }
            }
        });
    }
}

function agregar() {
    if ($("#combobox").val() !== "") {
        var filas2 = jQuery("#list3").jqGrid("getRowData");
        var su;
        var count = 0;
        var canti = $("#cantidad").val();
        
        if (filas2.length < canti) {
            if (filas2.length === 0) {
                var datarow = {
                    id_serie: count = count + 1, 
                    serie: $("#combobox").val()
                };
                su = jQuery("#list3").jqGrid('addRowData', count, datarow);
                $("#combobox").val("");
            } else {
                var repe = 0;
                for (var i = 0; i < filas2.length; i++) {
                    var id = filas2[i];
                    if (id['serie'] === $("#combobox").val()) {
                        repe = 1;
                    }
                }
                if (repe === 0) {
                    datarow = {
                        id_serie: count = count + 1, 
                        serie: $("#combobox").val()
                    };
                    su = jQuery("#list3").jqGrid('addRowData', count, datarow);
                    $("#combobox").val("");
                } else {
                    $("#combobox").val("");
                    alertify.alert("Error... Serie ingresada");
                }
            }
        } else {
            alertify.alert("Error... Alcanzo el limite máximo");
        }
    } else {
        $("#combobox").focus();
        alertify.alert("Error... En la serie");
    }
}

function countfactura() {
    var temp2 = "";
    var serie = $("#num_factura").val();
    for (var i = serie.length; i < 5; i++) {
        temp2 = temp2 + "0";
    }
    return temp2;
}

function autocompletar() {
    var temp = "";
    var serie = $("#num_factura").val();
    for (var i = serie.length; i < 9; i++) {
        temp = temp + "0";
    }
    return temp;
}

function comprobar() {
    if ($("#num_factura").val() === "") {
        $("#num_factura").focus();
        alertify.success("Ingrese número de factura");
    } else {
        var a = autocompletar($("#num_factura").val());
        $("#num_factura").val(a + "" + $("#num_factura").val());
        $("#ruc_ci").focus();
    }
}

function nuevo_cliente(){
    alertify.confirm("Desea registrar un nuevo cliente", function (e) {
        if (e) {
            //////////////verificar si esxiste cliente/////////////
            $.ajax({
                type: "POST",
                url: "../procesos/comparar_cedulas.php",
                data: "cedula=" + $("#ruc_ci").val(),
                success: function(data) {
                    var val = data;
                    if (val == 1) {
                        $("#ruc_ci").val("");
                        $("#ruc_ci").focus();
                        alertify.error('Error... El cliente esta registrado');
                    }else{
                        if ($("#ruc_ci").val().length !== 10 && $("#ruc_ci").val().length !== 13) {
                            alertify.error('Error... Ingrese una Identificación valida');
                        }else{
                            ////////////////////validar cedula ruc/////////////////////
                            var numero = $("#ruc_ci").val();
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

                            /* Aqui almacenamos los digitos de la cedula en variables. */
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

                            /* El tercer digito es: */                           
                            /* 9 para sociedades privadas y extranjeros   */         
                            /* 6 para sociedades publicas */         
                            /* menor que 6 (0,1,2,3,4,5) para personas naturales */ 

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
                            if (numero.length === 10) {
                                if(nat == true){
                                    if (digitoVerificador != d10){                          
                                        alertify.error('El número de cédula es incorrecto.');
                                        $("#direccion_cliente").attr("disabled", "disabled");
                                        $("#telefono_cliente").attr("disabled", "disabled");
                                        $("#correo").attr("disabled", "disabled");
                                    }else{
                                        alertify.success('El número de cédula es correcto.');
                                        $("#nombre_cliente").focus();
                                        $("#direccion_cliente").removeAttr("disabled");
                                        $("#telefono_cliente").removeAttr("disabled");
                                        $("#correo").removeAttr("disabled");
                                    }
                                }
                            }else{
                                var ruc = numero.substr(10,13);
                                var digito3 = numero.substring(2,3);
                                if(ruc == "001" ){
                                    if(digito3 < 6){ 
                                        if(nat == true){
                                            if (digitoVerificador != d10){                          
                                                alertify.error('El ruc persona natural es incorrecto.');
                                                $("#direccion_cliente").attr("disabled", "disabled");
                                                $("#telefono_cliente").attr("disabled", "disabled");
                                                $("#correo").attr("disabled", "disabled");
                                            }else{
                                                alertify.success('El ruc persona natural es correcto.');
                                                $("#nombre_cliente").focus();
                                                $("#direccion_cliente").removeAttr("disabled");
                                                $("#telefono_cliente").removeAttr("disabled");
                                                $("#correo").removeAttr("disabled");
                                            } 
                                        }
                                    }else{
                                        if(digito3 == 6){ 
                                            if (pub==true){  
                                                if (digitoVerificador != d9){                          
                                                    alertify.error('El ruc público es incorrecto.');
                                                    $("#direccion_cliente").attr("disabled", "disabled");
                                                    $("#telefono_cliente").attr("disabled", "disabled");
                                                    $("#correo").attr("disabled", "disabled");
                                                }else{
                                                    alertify.success('El ruc público es correcto.');
                                                    $("#nombre_cliente").focus();
                                                    $("#direccion_cliente").removeAttr("disabled");
                                                    $("#telefono_cliente").removeAttr("disabled");
                                                    $("#correo").removeAttr("disabled");
                                                } 
                                            }
                                        }else{
                                            if(digito3 == 9){
                                                if(pri == true){
                                                    if (digitoVerificador != d10){                          
                                                        alertify.error('El ruc privado es incorrecto.');
                                                        $("#direccion_cliente").attr("disabled", "disabled");
                                                        $("#telefono_cliente").attr("disabled", "disabled");
                                                        $("#correo").attr("disabled", "disabled");
                                                    }else{
                                                        alertify.success('El ruc privado es correcto.');
                                                        $("#nombre_cliente").focus();
                                                        $("#direccion_cliente").removeAttr("disabled");
                                                        $("#telefono_cliente").removeAttr("disabled");
                                                        $("#correo").removeAttr("disabled");
                                                    } 
                                                }
                                            } 
                                        }
                                    }  
                                }else{
                                    if(numero.length === 13){
                                        alertify.error('El ruc es incorrecto.');
                                        $("#direccion_cliente").attr("disabled", "disabled");
                                        $("#telefono_cliente").attr("disabled", "disabled");
                                        $("#correo").attr("disabled", "disabled");
                                    }
                                }
                            }
                        }
                    }
                }
            });
        } else {
            $("#ruc_ci").val("");
            $("#direccion_cliente").attr("disabled", "disabled");
            $("#telefono_cliente").attr("disabled", "disabled");
            $("#correo").attr("disabled", "disabled");
        }
    });
}

function comprobar1() {
    if ($("#num_factura").val() === "") {
        $("#num_factura").focus();
        alertify.success("Ingrese número de factura");
    } else {
        if ($("#id_cliente").val() === "") {
            $("#ruc_ci").focus();
            alertify.success("Indique un cliente");
        } else {
            if ($("#ruc_ci").val() === "") {
                $("#ruc_ci").focus();
                alertify.success("Indique un cliente");
            } 
        } 
    }
}

function comprobar2(){
    if ($("#ruc_ci").val() === "") {
        $("#ruc_ci").focus();
        alertify.alert("Indique un cliente");
    }else {
        if ($("#nombre_cliente").val() === "") {
            $("#nombre_cliente").focus();
            alertify.alert("Nombres del cliente");
        }else{
            if ($("#direccion_cliente").val() === "") {
                $("#direccion_cliente").focus();
                alertify.alert("Dirección del cliente");
            }else{
                if ($("#telefono_cliente").val() === "") {
                    $("#telefono_cliente").focus();
                    alertify.alert("Telefóno del cliente");
                }else{
                    $("#correo").focus();
                } 
            }  
        } 
    }    
}

function agregar_proforma() {
    if ($("#proforma").val() === "") {
        $("#proforma").focus();
        alertify.alert("Error... Ingrese número de la proforma");
    } else {
        var id_proforma = $("#proforma").val();
        /////////////////llamado datos personales/////////////
        $.getJSON('../procesos/retornar_proforma_clientes.php?id1=' + id_proforma, function(data) {
            var tama2 = data.length;
            for (var i = 0; i < tama2; i = i + 7)
            {
                $("#id_cliente").val(data[i]);
                $("#ruc_ci").val(data[i + 1 ]);
                $("#nombre_cliente").val(data[i + 2]);
                $("#direccion_cliente").val(data[i + 3]);
                $("#telefono_cliente").val(data[i + 4]);
                $("#saldo").val(data[i + 5]);
                $("#tipo_precio").val(data[i + 6]);
            }
        });
        ////////////////////////////////////////////////////
        //
        //////////////llamado detalle factura//////////////
        $.getJSON('../procesos/retornar_proforma.php?id2=' + id_proforma, function(data) {
            var tama = data.length;
            if (tama === 0) {
                alertify.alert("Error... La proforma no existe", function(){
                    location.reload();
                });
            } else {
                $("#list").jqGrid("clearGridData", true);
                var descuento = 0;
                var multi = 0;
                var total = 0;
                for (var i = 0; i < tama; i = i + 9) {
                    var temp = 0;
                    var temp1 = 0;
                    if(parseInt(data[i + 3]) < 0){
                        temp = 0; 
                        temp1 = data[i + 4] ;
                    }else{
                        if(parseInt(data[i + 4]) > parseInt(data[i + 3])){
                            temp = data[i + 3]; 
                            temp1 = data[i + 4] - data[i + 3];
                        }else{
                            temp = data[i + 4];   
                            temp1 = 0;   
                        }
                    }

                    multi = (temp * data[i + 5]).toFixed(2);
                    descuento = ((multi * parseFloat(data[i + 6])) / 100);
                    total = (multi - descuento).toFixed(2);

                    var datarow = {
                        cod_producto: data[i], 
                        codigo: data[i + 1], 
                        detalle: data[i + 2], 
                        cantidad: temp, 
                        precio_u: data[i + 5], 
                        descuento: data[i + 6], 
                        total: total, 
                        iva: data[i + 8], 
                        pendiente: temp1
                    };
                    var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                    var ivas = data[i + 8];

                    ////////////////calcular valores////////////
                    var fil = jQuery("#list").jqGrid("getRowData");
                    var subtotal = 0;
                    var iva = 0;
                    var t_fc = 0;
                    var mu = 0;
                    var des = 0;
                    var descu = 0;
                    if (ivas === "Si") {
                        fil = jQuery("#list").jqGrid("getRowData");
                        subtotal = 0;
                        iva = 0;
                        t_fc = 0;
                        mu = 0;
                        des = 0;
                        descu = 0;
                        
                        for (var t = 0; t < fil.length; t++) {
                            var dd = fil[t];
                            if (dd['iva'] === "Si") {
                                subtotal = (subtotal + parseFloat(dd['total']));
                                var sub = parseFloat(subtotal).toFixed(2);
                               
                                mu = (dd['cantidad'] * dd['precio_u']).toFixed(2);
                                des = ((mu * dd['descuento'])/100).toFixed(2);
                                descu = (parseFloat(descu) + parseFloat(des)).toFixed(2);
                                $("#iva_producto").val("");
                            }
                        }
                        iva = ((subtotal * 12) / 100).toFixed(2);
                        t_fc = ((parseFloat(subtotal) + parseFloat(iva)) + parseFloat($("#total_p").val())).toFixed(2);
                        $("#total_p2").val(sub);
                        $("#iva").val(iva);
                        $("#desc").val(descu);
                        $("#tot").val(t_fc);
                    } else {
                        if (ivas === "No") {
                            fil = jQuery("#list").jqGrid("getRowData");
                            subtotal = 0;
                            t_fc = 0;
                            iva = 0;
                            for (t = 0; t < fil.length; t++) {
                                dd = fil[t];
                                if (dd['iva'] === "No") {
                                    subtotal = (subtotal + parseFloat(dd['total']));
                                    sub = parseFloat(subtotal).toFixed(2);
                                    
                                    mu = (dd['cantidad'] * dd['precio_u']).toFixed(2);
                                    des = ((mu * dd['descuento'])/100).toFixed(2);
                                    descu = (parseFloat(descu) + parseFloat(des)).toFixed(2);
                                    $("#iva_producto").val("");
                                }
                            }
                            
                            iva = parseFloat($("#iva").val());
                            t_fc = ((parseFloat(subtotal) + parseFloat(iva)) + parseFloat($("#total_p2").val())).toFixed(2);
                            $("#total_p").val(sub);
                            $("#desc").val(descu);
                            $("#tot").val(t_fc);
                        }
                    }
                }
            }
        });
    }
}

function guardar_serie() {
    var tam2 = jQuery("#list3").jqGrid("getRowData");

    if (tam2.length > 0) {
        $("#combobox").append('<option></option>');
        var v1 = new Array();
        var string_v1 = "";
        var fil = jQuery("#list3").jqGrid("getRowData");

        for (var i = 0; i < fil.length; i++) {
            var datos = fil[i];
            v1[i] = datos['serie'];
        }

        for (i = 0; i < fil.length; i++) {
            string_v1 = string_v1 + "|" + v1[i];
        }
        $.ajax({
            type: "POST",
            url: "../procesos/guardar_series2.php",
            data: "cod_producto=" + $("#cod_producto").val() + "&campo1=" + string_v1+ "&comprobante=" + $("#comprobante").val(),
            success: function(data) {
                var val = data;
                if (val == 1) {
                    $("#list3").jqGrid("clearGridData", true);
                    $("#series").dialog("close");
                    $("#descuento").focus();  
                }
            }
        });
    } else {
        alertify.alert("Error... Ingrese las series");
    }
}

function guardar_factura() {
    var tam = jQuery("#list").jqGrid("getRowData");
    if ($("#num_factura").val() === "") {
        $("#num_factura").focus();
        alertify.alert("Indique número de la factura");
    } else {
        var num_factu = ("001" + "-" + "001" + "-" + $("#num_factura").val());
        $.ajax({
            type: "POST",
            url: "../procesos/comparar_num_venta.php",
            data: "num_fac=" + num_factu,
            success: function(data) {
                var val = data;
                if (val == 1) {
                    $("#num_factura").val("");
                    $("#num_factura").focus();
                    alertify.alert("Error... El número de factura ya existe");
                }else{
                    if ($("#id_cliente").val() === "") {
                        $("#ruc_ci").focus();
                        alertify.alert("Indique un cliente");
                    }else {
                        if($("#ruc_ci").val().length !== 10 && $("#ruc_ci").val().length !== 13) {
                            $("#ruc_ci").val("");
                            $("#ruc_ci").focus();
                            alertify.alert("Error... Ingrese una Identificación valida");
                        }else{
                            if ($("#nombre_cliente").val() === "") {
                                $("#nombre_cliente").focus();
                                alertify.alert("Nombres del cliente");
                            }else{
                                if ($("#direccion_cliente").val() === "") {
                                    $("#direccion_cliente").focus();
                                    alertify.alert("Dirección del cliente");
                                }else{
                                    if ($("#tipo_precio").val() === "") {
                                        $("#tipo_precio").focus();
                                        alertify.alert("Seleccione un tipo de precio");
                                    } else {
                                        if (tam.length === 0) {
                                            $("#codigo").focus();
                                            alertify.alert("Error... Llene productos a la factura");
                                        } else {
                                            if ($("#formas").val() === "Credito" && $("#meses").val() === "") {
                                                $("#meses").focus();
                                                alertify.alert("Meses a diferir");
                                            } else {
                                                var v1 = new Array();
                                                var v2 = new Array();
                                                var v3 = new Array();
                                                var v4 = new Array();
                                                var v5 = new Array();
                                                var v6 = new Array();
                                                var string_v1 = "";
                                                var string_v2 = "";
                                                var string_v3 = "";
                                                var string_v4 = "";
                                                var string_v5 = "";
                                                var string_v6 = "";
                                                var fil = jQuery("#list").jqGrid("getRowData");
                                                for (var i = 0; i < fil.length; i++) {
                                                    var datos = fil[i];
                                                    v1[i] = datos['cod_producto'];
                                                    v2[i] = datos['cantidad'];
                                                    v3[i] = datos['precio_u'];
                                                    v4[i] = datos['descuento'];
                                                    v5[i] = datos['total'];
                                                    v6[i] = datos['pendiente'];
                                                }
                                                for (i = 0; i < fil.length; i++) {
                                                    string_v1 = string_v1 + "|" + v1[i];
                                                    string_v2 = string_v2 + "|" + v2[i];
                                                    string_v3 = string_v3 + "|" + v3[i];
                                                    string_v4 = string_v4 + "|" + v4[i];
                                                    string_v5 = string_v5 + "|" + v5[i];
                                                    string_v6 = string_v6 + "|" + v6[i];
                                                }
                                                
                                                var a = autocompletar($("#num_factura").val());
                                                var seriee = ("001" + "-" + "001" + "-" + a + "" + $("#num_factura").val());
                                                $.ajax({
                                                    type: "POST",
                                                    url: "../procesos/guardar_factura_venta.php",
                                                    data: "id_cliente=" + $("#id_cliente").val() + "&comprobante=" + $("#comprobante").val() + "&num_factura=" + seriee + "&fecha_actual=" + $("#fecha_actual").val() + "&hora_actual=" + $("#hora_actual").val() + "&proforma=" + $("#proforma").val() + "&cancelacion=" + $("#cancelacion").val() + "&tipo_precio=" + $("#tipo_precio").val() + "&formas=" + $("#formas").val() + "&adelanto=" + $("#adelanto").val() + "&meses=" + $("#meses").val() + "&autorizacion=" + $("#autorizacion").val()+ "&fecha_auto=" + $("#fecha_auto").val()+ "&fecha_caducidad=" + $("#fecha_caducidad").val() + "&tarifa0=" + $("#total_p").val() + "&tarifa12=" + $("#total_p2").val() + "&iva=" + $("#iva").val() + "&desc=" + $("#desc").val() + "&tot=" + $("#tot").val() + "&ruc_ci=" + $("#ruc_ci").val() + "&nombre_cliente=" + $("#nombre_cliente").val() + "&direccion_cliente=" + $("#direccion_cliente").val() + "&telefono_cliente=" + $("#telefono_cliente").val() + "&correo=" + $("#correo").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5+ "&campo6=" + string_v6+ "&tipo_venta=" + $("#tipo_venta").val(),
                                                    success: function(data) {
                                                        var val = data;
                                                        if($("#tipo_venta").val() == "FACTURA"){
                                                           if (val != 0) {
                                                                alertify.alert("Factura Guardada correctamente", function(){
                                                                    window.open("../reportes_sistema/factura_venta.php?hoja=A4&id="+val,'_blank');
                                                                    location.reload();
                                                                });
                                                            }
                                                        }else{
                                                            if($("#tipo_venta").val() == "NOTA"){
                                                                if (val != 0) {
                                                                    alertify.alert("Factura Guardada correctamente", function(){
                                                                        window.open("../reportes_sistema/nota_venta.php?hoja=A4&id="+val,'_blank');
                                                                        location.reload();
                                                                    });
                                                                }
                                                            }
                                                        }
                                                    }
                                                });
                                            }
                                        }
                                    }
                                }
                            }
                        } 
                    } 
                }
            }
        });
    }
}

function flecha_atras(){
    $.ajax({
        type: "POST",
        url: "../procesos/flechas.php",
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "factura_venta" + "&id_tabla=" + "id_factura_venta" + "&tipo=" + 1,
        success: function(data) {
            var val = data;
            if(val != ""){
                $("#comprobante").val(val);
                var valor = $("#comprobante").val();
                ///////////////////llamar factura flechas primera parte/////
                $("#btnGuardar").attr("disabled", true);
                $("#btnModificar").attr("disabled", true);
                $("#num_factura").attr("disabled", "disabled");
                $("#ruc_ci").attr("disabled", "disabled");
                $("#nombre_cliente").attr("disabled", "disabled");
                $("#direccion_cliente").attr("disabled", "disabled");
                $("#telefono_cliente").attr("disabled", "disabled");
                $("#correo").attr("disabled", "disabled");
                $("#formas").attr("disabled", true);
                $("#ruc_ci").val("");
                $("#nombre_cliente").val("");
                $("#telefono_cliente").val("");
                $("#correo").val("");
                $("#codigo").attr("disabled", "disabled");
                $("#producto").attr("disabled", "disabled");
                $("#cantidad").attr("disabled", "disabled");
                $("#p_venta").attr("disabled", "disabled");
                $("#btncargar").attr("disabled", "disabled");
                $("#autorizacion").attr("disabled", "disabled");
                $("#estado h3").remove();
                $("#formas").val("Contado");
                $("#adelanto").val("");
                $("#meses").val("");
                $('#cuotas').children().remove().end();
                $("#cuotas").attr("disabled", true); 
                $("#list").jqGrid("clearGridData", true);
                $("#total_p").val("0.00");
                $("#total_p2").val("0.00");
                $("#iva").val("0.00");
                $("#desc").val("0.00");
                $("#tot").val("0.00");

                ///////////////////llamar factura flechas primera parte/////
                $.getJSON('../procesos/retornar_factura_venta.php?com=' + valor, function(data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 19) {
                            $("#fecha_actual").val(data[i]);
                            $("#hora_actual").val(data[i + 1 ]);
                            $("#digitador").val(data[i + 2 ] + " " + data[i + 3 ] );
                            var num = data[i + 4]; 
                            var res = num.substr(8, 20)
                            $("#num_factura").val(res);
                            $("#id_cliente").val(data[i + 5]);
                            $("#ruc_ci").val(data[i + 6]);
                            $("#nombre_cliente").val(data[i + 7]);
                            $("#direccion_cliente").val(data[i + 8]);
                            $("#telefono_cliente").val(data[i + 9]);
                            $("#correo").val(data[i + 10]);
                            $("#cancelacion").val(data[i + 11]);

                            $("#tipo_precio").val(data[i + 12]);
                            if(data[ i+ 13 ] == "Pasivo"){
                                $("#estado").append($("<h3>").text("Anulada"));
                                $("#estado h3").css("color","red");
                                $("#btnAnular").attr("disabled", "disabled");
                            }else{
                                $("#estado h3").remove();
                                $("#btnAnular").attr("disabled", "disabled");
                                $("#btnAnular").attr("disabled", false);
                            }

                            $("#total_p").val(data[i + 14]);
                            $("#total_p2").val(data[i + 15]);
                            $("#iva").val(data[i + 16]);
                            $("#desc").val(data[i + 17]);
                            $("#tot").val(data[i + 18]);
                        }
                    }
                });
                ///////////////////////////////////////////////////   

                ///////////////////llamar factura flechas segunda parte/////
                $.getJSON('../procesos/retornar_factura_venta_credito.php?com=' + valor, function(data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 4)
                        {
                            $("#formas").val(data[i]);
                            $("#adelanto").val(data[i + 1 ]);
                            $("#meses").val(data[i + 2 ]);

                            //////////calcular meses//////////
                            if (data[i + 2 ] > 1) {
                                $("#cuotas").attr("disabled", false); 
                                for (var j = 1; j <= data[i + 2 ] - 1; j++) {
                                    var calcu = data[i + 3] / (data[i + 2]);
                                    var entero = Math.floor(calcu).toFixed(2);
                                    $("#cuotas").append('<option>'+entero+'</option>'); 
                                }
                                var calcu1 = entero * (data[i + 2 ] - 1);
                                var sal = data[i + 3] - calcu1;
                                var entero2 = sal.toFixed(2);
                                $("#cuotas").append('<option>'+entero2+'</option>'); 

                            }else{
                                $("#cuotas").attr("disabled", false); 
                                $("#cuotas").append('<option>'+data[i + 3]+'</option>');  
                            }
                        }
                    }
                });
                /////////////////////////////////////////////////////////

                ////////////////////llamar factura flechas tercera parte/////
                $.getJSON('../procesos/retornar_factura_venta2.php?com=' + valor, function(data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 9)
                        {
                            var datarow = {
                                cod_producto: data[i], 
                                codigo: data[i + 1], 
                                detalle: data[i + 2], 
                                cantidad: data[i + 3], 
                                precio_u: data[i + 4], 
                                descuento: data[i + 5], 
                                total: data[i + 6], 
                                iva: data[i + 7],
                                pendiente: data[i + 8]
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });
            }else{
                alertify.alert("No hay mas registros posteriores!!");
            }
        }
    });
} 

function flecha_siguiente(){
    $.ajax({
        type: "POST",
        url: "../procesos/flechas.php",
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "factura_venta" + "&id_tabla=" + "id_factura_venta" + "&tipo=" + 2,
        success: function(data) {
            var val = data;
            if(val != ""){   
                $("#comprobante").val(val);
                var valor = $("#comprobante").val();
                ///////////////////llamar factura flechas primera parte/////
                $("#btnGuardar").attr("disabled", true);
                $("#btnModificar").attr("disabled", true);
                $("#num_factura").attr("disabled", "disabled");
                $("#ruc_ci").attr("disabled", "disabled");
                $("#nombre_cliente").attr("disabled", "disabled");
                $("#direccion_cliente").attr("disabled", "disabled");
                $("#telefono_cliente").attr("disabled", "disabled");
                $("#correo").attr("disabled", "disabled");
                $("#formas").attr("disabled", true);
                $("#ruc_ci").val("");
                $("#nombre_cliente").val("");
                $("#telefono_cliente").val("");
                $("#correo").val("");
                $("#codigo").attr("disabled", "disabled");
                $("#producto").attr("disabled", "disabled");
                $("#cantidad").attr("disabled", "disabled");
                $("#p_venta").attr("disabled", "disabled");
                $("#btncargar").attr("disabled", "disabled");
                $("#autorizacion").attr("disabled", "disabled");
                $("#estado h3").remove();
                $("#formas").val("Contado");
                $("#adelanto").val("");
                $("#meses").val("");
                $('#cuotas').children().remove().end();
                $("#cuotas").attr("disabled", true); 
                $("#list").jqGrid("clearGridData", true);
                $("#total_p").val("0.00");
                $("#total_p2").val("0.00");
                $("#iva").val("0.00");
                $("#desc").val("0.00");
                $("#tot").val("0.00");
            
                $.getJSON('../procesos/retornar_factura_venta.php?com=' + valor, function(data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 19) {
                            $("#fecha_actual").val(data[i]);
                            $("#hora_actual").val(data[i + 1 ]);
                            $("#digitador").val(data[i + 2 ] + " " + data[i + 3 ] );
                            var num = data[i + 4]; 
                            var res = num.substr(8, 20)
                            $("#num_factura").val(res);
                            $("#id_cliente").val(data[i + 5]);
                            $("#ruc_ci").val(data[i + 6]);
                            $("#nombre_cliente").val(data[i + 7]);
                            $("#direccion_cliente").val(data[i + 8]);
                            $("#telefono_cliente").val(data[i + 9]);
                            $("#correo").val(data[i + 10]);
                            $("#cancelacion").val(data[i + 11]);

                            $("#tipo_precio").val(data[i + 12]);
                            if(data[ i+ 13 ] == "Pasivo"){
                                $("#estado").append($("<h3>").text("Anulada"));
                                $("#estado h3").css("color","red");
                                $("#btnAnular").attr("disabled", "disabled");
                            }else{
                                $("#estado h3").remove();
                                $("#btnAnular").attr("disabled", "disabled");
                                $("#btnAnular").attr("disabled", false);
                            }

                            $("#total_p").val(data[i + 14]);
                            $("#total_p2").val(data[i + 15]);
                            $("#iva").val(data[i + 16]);
                            $("#desc").val(data[i + 17]);
                            $("#tot").val(data[i + 18]);
                        }
                    }
                });
                ///////////////////////////////////////////////////   
    
                ///////////////////llamar facturas flechas segunda parte/////
                $.getJSON('../procesos/retornar_factura_venta_credito.php?com=' + valor, function(data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 4)
                        {
                            $("#formas").val(data[i]);
                            $("#adelanto").val(data[i + 1 ]);
                            $("#meses").val(data[i + 2 ]);
                    
                            //////////calcular meses//////////
                            if (data[i + 2 ] > 1) {
                                $("#cuotas").attr("disabled", false); 
                                for (var j = 1; j <= data[i + 2 ] - 1; j++) {
                                    var calcu = data[i + 3] / (data[i + 2]);
                                    var entero = Math.floor(calcu).toFixed(2);
                                    $("#cuotas").append('<option>'+entero+'</option>'); 
                                }
                                var calcu1 = entero * (data[i + 2 ] - 1);
                                var sal = data[i + 3] - calcu1;
                                var entero2 = sal.toFixed(2);
                                $("#cuotas").append('<option>'+entero2+'</option>'); 

                            }else{
                                $("#cuotas").attr("disabled", false); 
                                $("#cuotas").append('<option>'+data[i + 3]+'</option>');  
                            }
                        }
                    }
                });
                /////////////////////////////////////////////////////////
    
                ////////////////////llamar facturas flechas tercera parte/////
                $.getJSON('../procesos/retornar_factura_venta2.php?com=' + valor, function(data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 9)
                        {
                            var datarow = {
                                cod_producto: data[i], 
                                codigo: data[i + 1], 
                                detalle: data[i + 2], 
                                cantidad: data[i + 3], 
                                precio_u: data[i + 4], 
                                descuento: data[i + 5], 
                                total: data[i + 6], 
                                iva: data[i + 7],
                                pendiente: data[i + 8]
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });
            }else{
                alertify.alert("No hay mas registros superiores!!");
            }
        }
    });
} 

function limpiar_campo(){
    if($("#ruc_ci").val() === ""){
        $("#id_cliente").val("");
        $("#nombre_cliente").val("");
        $("#direccion_cliente").val("");
        $("#telefono_cliente").val("");
        $("#correo").val("");
        $("#nombre_director").val("");
        $("#direccion_cliente").attr("disabled", "disabled");
        $("#telefono_cliente").attr("disabled", "disabled");
        $("#correo").attr("disabled", "disabled");
    }
}

function limpiar_campo2(){
    if($("#nombre_cliente").val() === ""){
        $("#id_cliente").val("");
        $("#ruc_ci").val("");
        $("#direccion_cliente").val("");
        $("#telefono_cliente").val("");
        $("#correo").val("");
        $("#nombre_director").val("");
        $("#direccion_cliente").attr("disabled", "disabled");
        $("#telefono_cliente").attr("disabled", "disabled");
        $("#correo").attr("disabled", "disabled");
    }
}

function limpiar_campo3(){
    if($("#codigo").val() === ""){
        $("#codigo_barras").val("");
        $("#cod_producto").val("");
        $("#producto").val("");
        $("#cantidad").val("");
        $("#p_venta").val("");
        $("#descuento").val("");
        $("#disponibles").val("");
    }
}

function limpiar_campo4(){
    if($("#producto").val() === ""){
        $("#codigo_barras").val("");
        $("#cod_producto").val("");
        $("#codigo").val("");
        $("#cantidad").val("");
        $("#p_venta").val("");
        $("#descuento").val("");
        $("#disponibles").val("");
    }
}

function limpiar_factura(){
    location.reload(); 
}

function anular_factura(){
    $("#clave_permiso").dialog("open");      
}

function validar_acceso(){
    if($("#clave").val() == "") {
        $("#clave").focus();
        alertify.alert("Ingrese la clave");
    }else{
        $.ajax({
            url: '../procesos/validar_acceso.php',
            type: 'POST',
            data: "clave=" + $("#clave").val(),
            success: function(data) {
                var val = data;
                if (val == 0) {
                    $("#clave").val("");
                    $("#clave").focus();
                    alertify.alert("Error... La clave es incorrecta ingrese nuevamente");
                }else{
                    if (val == 1) {
                        $("#seguro").dialog("open");   
                    }
                }
            }
        });
    }   
}

function aceptar(){
    var v1 = new Array();
    var v2 = new Array();
    var string_v1 = "";
    var string_v2 = "";
    var fil = jQuery("#list").jqGrid("getRowData");
    for (var i = 0; i < fil.length; i++) {
        var datos = fil[i];
        v1[i] = datos['cod_producto'];
        v2[i] = datos['cantidad'];
    }
    for (i = 0; i < fil.length; i++) {
        string_v1 = string_v1 + "|" + v1[i];
        string_v2 = string_v2 + "|" + v2[i];
    }

    $.ajax({
        type: "POST",
        url: "../procesos/anular_factura_venta.php",
        data: "comprobante=" + $("#comprobante").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2+ "&fecha_anulacion=" + $("#fecha_actual").val(),
        success: function(data) {
            var val = data;
            if (val == 1) {
                alertify.alert("Factura Anulada correctamente", function(){
                    location.reload();
                });
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

function numeros(e) { 
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla==8) return true;
    patron = /\d/;
    te = String.fromCharCode(tecla);
    return patron.test(te);
}

function punto(e){
    var key;
    if (window.event) {
        key = e.keyCode;
    } else if (e.which) {
        key = e.which;
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

function inicio() {
    alertify.set({ delay: 1000 });
    jQuery().UItoTop({
        easingType: 'easeOutQuart'
    });

    //////////////para hora///////////
    show();
    ///////////////////

    //////////validacion ruc cedula/////////////
    $("#ruc_ci").validarCedulaEC({
        strict: false
    });
    ///////////////////////////////// 
    ///////////////////////////////////
    //
    /////////////validador factura//////
    if ($("#num_oculto").val() === "") {
        $("#num_factura").val("");
    } else {
        var str = $("#num_oculto").val();
        var res = parseInt(str.substr(8, 16));
        res = res + 1;
        
        $("#num_factura").val(res);
        var a = autocompletar(res);
        var validado = a + "" + res;
        $("#num_factura").val(validado);
    }
    /////////////////////////////////// 
    //
    ///////botones /////////////////// 
    $("#btncargar").click(function(e) {
        e.preventDefault();
    });
    $("#btnAgregar").click(function(e) {
        e.preventDefault();
    });
    $("#btnGuardarSeries").click(function(e) {
        e.preventDefault();
    });
    $("#btnGuardar").click(function(e) {
        e.preventDefault();
    });
    $("#btnModificar").click(function(e) {
        e.preventDefault();
    });
    $("#btnImprimir").click(function (){  
        $.ajax({
            type: "POST",
            url: "../procesos/validacion.php",
            data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "factura_venta" + "&id_tabla=" + "id_factura_venta" + "&tipo=" + 1,
            success: function(data) {
                var val = data;
                if(val != "") {
                    window.open("../reportes_sistema/factura_venta.php?hoja=A4&id="+$("#comprobante").val(),'_blank');
                } else {
                    alertify.alert("Factura no creada!!");
                }   
            }
        });
    });
    $("#btnNuevo").click(function(e) {
        e.preventDefault();
    });
    $("#btnAnular").click(function(e) {
        e.preventDefault();
    });
    $("#btnAceptar").click(function(e) {
        e.preventDefault();
    });
    $("#btnSalir").click(function(e) {
        e.preventDefault();
    });
    $("#btnAcceder").click(function(e) {
        e.preventDefault();
    });
    $("#btnCancelar").click(function(e) {
        e.preventDefault();
    });
    $("#btnImprimir").click(function(e) {
        e.preventDefault();
    });
    $("#btnAtras").click(function(e) {
        e.preventDefault();
    });
    $("#btnAdelante").click(function(e) {
        e.preventDefault();
    });

    $("#btncargar").on("click", abrirDialogo);
    $("#btnAgregar").on("click", agregar);
    $("#btnGuardarSeries").on("click", guardar_serie);
    $("#btnGuardar").on("click", guardar_factura);
    $("#btnNuevo").on("click", limpiar_factura);
    $("#btnAnular").on("click", anular_factura);
    $("#btnAceptar").on("click", aceptar);
    $("#btnSalir").on("click", cancelar);
    $("#btnAcceder").on("click", validar_acceso);
    $("#btnCancelar").on("click", cancelar_acceso);
    $("#btnAtras").on("click", flecha_atras);
    $("#btnAdelante").on("click", flecha_siguiente);
    
    $("#ruc_ci").on("keyup", limpiar_campo);
    $("#nombre_cliente").on("keyup", limpiar_campo2);
    $("#codigo").on("keyup", limpiar_campo3);
    $("#producto").on("keyup", limpiar_campo4);
    
    /////////////////////////////
    $("#codigo").on("keypress", enter);
    $("#producto").on("keypress", enter);
    $("#cantidad").on("keypress", enter);
    $("#p_venta").on("keypress", enter1);
    $("#descuento").on("keypress", enter2);
    $("#num_factura").on("keypress", enter3);
    $("#ruc_ci").on("keypress", enter4);
    $("#nombre_cliente").on("keypress", enter5);
    $("#direccion_cliente").on("keypress", enter5);
    $("#telefono_cliente").on("keypress", enter5);
    
    $("#proforma").on("keyup", agregar_proforma);
    /////////////////////////////////////////

    $("#direccion_cliente").attr("disabled", "disabled");
    $("#telefono_cliente").attr("disabled", "disabled");
    $("#correo").attr("disabled", "disabled");
    $("#btnAnular").attr("disabled", true);
    $("#btnModificar").attr("disabled", true);
    
    $("#telefono_cliente").validCampoFranz("0123456789");
    $("#telefono_cliente").attr("maxlength", "10");
    $("#ruc_ci").attr("maxlength", "13");
      
    $("#series").dialog(dialogo);
    $("#buscar_facturas_venta").dialog(dialogo2);
    $("#buscar_proformas").dialog(dialogo5);
    $("#clave_permiso").dialog(dialogo3);
    $("#seguro").dialog(dialogo4);

    $("#btnBuscar").click(function (){
        $("#buscar_facturas_venta").dialog("open");   
    });

    $("#btnProforma").click(function (){
        $("#buscar_proformas").dialog("open");   
    });
    
    //////////////para precio////////
    $("#p_venta").on("keypress",punto);
    $("#precio").on("keypress",punto);
    $("#adelanto").on("keypress",punto);
    ////////////////////////////////
    
    //////////////////buscar productos codigo//////////////// 
    $("#codigo_barras").keyup(function(e) {
        var precio = $("#tipo_precio").val(); 
        var codigo = $("#codigo_barras").val();
        if (precio === "MINORISTA") {
            $.getJSON('../procesos/search.php?codigo_barras=' + codigo + '&precio=' + precio, function(data) {
                var tama = data.length;
                if (tama !== 0) {
                    for (var i = 0; i < tama; i = i + 9) {
                        $("#codigo").val(data[i]);
                        $("#producto").val(data[i + 1]);
                        $("#p_venta").val(data[i + 2]);
                        $("#descuento").val(data[i + 7]);
                        $("#disponibles").val(data[i + 3]);
                        $("#iva_producto").val(data[i + 4]);
                        $("#carga_series").val(data[i + 5]);
                        $("#cod_producto").val(data[i + 6]);
                        $("#des").val(data[i + 7]);
                        $("#inventar").val(data[i + 8]);
                        $("#cantidad").focus();
                    }
                }else{
                    $("#codigo").val("");
                    $("#producto").val("");
                    $("#p_venta").val("");
                    $("#descuento").val("");
                    $("#disponibles").val("");
                    $("#iva_producto").val("");
                    $("#carga_series").val("");
                    $("#cod_producto").val("");
                    $("#des").val("");
                    $("#inventar").val("");
                }
            });
        }else{
            if (precio === "MAYORISTA") {
                $.getJSON('../procesos/search.php?codigo_barras=' + codigo + '&precio=' + precio, function(data) {
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 9) {
                            $("#codigo").val(data[i]);
                            $("#producto").val(data[i + 1]);
                            $("#p_venta").val(data[i + 2]);
                            $("#descuento").val(data[i + 7]);
                            $("#disponibles").val(data[i + 3]);
                            $("#iva_producto").val(data[i + 4]);
                            $("#carga_series").val(data[i + 5]);
                            $("#cod_producto").val(data[i + 6]);
                            $("#des").val(data[i + 7]);
                            $("#inventar").val(data[i + 8]);
                            $("#cantidad").focus();
                        }
                    }else{
                        $("#codigo").val("");
                        $("#producto").val("");
                        $("#p_venta").val("");
                        $("#descuento").val("");
                        $("#disponibles").val("");
                        $("#iva_producto").val("");
                        $("#carga_series").val("");
                        $("#cod_producto").val("");
                        $("#des").val("");
                        $("#inventar").val("");
                    }
                });
            }
        }
    });
    ///////////////////////////////////////////////////////
      
    //////////////////buscar productos codigo////////////////
    $("#codigo").keyup(function(e) {
        var precio = $("#tipo_precio").val(); 
        if (precio === "MINORISTA") {
            $("#codigo").autocomplete({
                source: "../procesos/buscar_producto9.php?tipo_precio=" + precio,
                minLength: 1,
                focus: function(event, ui) {
                $("#codigo_barras").val(ui.item.codigo_barras);
                $("#codigo").val(ui.item.value);
                $("#producto").val(ui.item.producto);
                $("#p_venta").val(ui.item.p_venta);
                $("#descuento").val(ui.item.descuento);
                $("#disponibles").val(ui.item.disponibles);
                $("#iva_producto").val(ui.item.iva_producto);
                $("#carga_series").val(ui.item.carga_series);
                $("#cod_producto").val(ui.item.cod_producto);
                $("#des").val(ui.item.des);
                $("#inventar").val(ui.item.inventar);
                return false;
                },
                select: function(event, ui) {
                $("#codigo_barras").val(ui.item.codigo_barras);
                $("#codigo").val(ui.item.value);
                $("#producto").val(ui.item.producto);
                $("#p_venta").val(ui.item.p_venta);
                $("#descuento").val(ui.item.descuento);
                $("#disponibles").val(ui.item.disponibles);
                $("#iva_producto").val(ui.item.iva_producto);
                $("#carga_series").val(ui.item.carga_series);
                $("#cod_producto").val(ui.item.cod_producto);
                $("#des").val(ui.item.des);
                $("#inventar").val(ui.item.inventar);
                return false;
                }

                }).data("ui-autocomplete")._renderItem = function(ul, item) {
                return $("<li>")
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
            };
        } else {
            if (precio === "MAYORISTA") {
                $("#codigo").autocomplete({
                    source: "../procesos/buscar_producto9.php?tipo_precio=" + precio,
                    minLength: 1,
                    focus: function(event, ui) {
                    $("#codigo_barras").val(ui.item.codigo_barras);
                    $("#codigo").val(ui.item.value);
                    $("#producto").val(ui.item.producto);
                    $("#p_venta").val(ui.item.p_venta);
                    $("#descuento").val(ui.item.descuento);
                    $("#disponibles").val(ui.item.disponibles);
                    $("#iva_producto").val(ui.item.iva_producto);
                    $("#carga_series").val(ui.item.carga_series);
                    $("#cod_producto").val(ui.item.cod_producto);
                    $("#des").val(ui.item.des);
                    $("#inventar").val(ui.item.inventar);
                    return false;
                    },
                    select: function(event, ui) {
                    $("#codigo_barras").val(ui.item.codigo_barras);
                    $("#codigo").val(ui.item.value);
                    $("#producto").val(ui.item.producto);
                    $("#p_venta").val(ui.item.p_venta);
                    $("#descuento").val(ui.item.descuento);
                    $("#disponibles").val(ui.item.disponibles);
                    $("#iva_producto").val(ui.item.iva_producto);
                    $("#carga_series").val(ui.item.carga_series);
                    $("#cod_producto").val(ui.item.cod_producto);
                    $("#des").val(ui.item.des);
                    $("#inventar").val(ui.item.inventar);
                    return false;
                    }
                    }).data("ui-autocomplete")._renderItem = function(ul, item) {
                    return $("<li>")
                    .append("<a>" + item.value + "</a>")
                    .appendTo(ul);
                };
            }
        }
    });
    /////////////////////////////////////////////////////////
    
    //////////////////buscar productos articulo////////////////
    $("#producto").keyup(function(e) {
        var precio = $("#tipo_precio").val();
        if (precio === "MINORISTA") {
            $("#producto").autocomplete({
                source: "../procesos/buscar_producto10.php?tipo_precio=" + precio,
                minLength: 1,
                focus: function(event, ui) {
                $("#codigo_barras").val(ui.item.codigo_barras);
                $("#producto").val(ui.item.value);
                $("#codigo").val(ui.item.codigo);
                $("#p_venta").val(ui.item.p_venta);
                $("#descuento").val(ui.item.descuento);
                $("#disponibles").val(ui.item.disponibles);
                $("#iva_producto").val(ui.item.iva_producto);
                $("#carga_series").val(ui.item.carga_series);
                $("#cod_producto").val(ui.item.cod_producto);
                $("#des").val(ui.item.des);
                $("#inventar").val(ui.item.inventar);
                return false;
                },
                select: function(event, ui) {
                $("#codigo_barras").val(ui.item.codigo_barras);
                $("#producto").val(ui.item.value);
                $("#codigo").val(ui.item.codigo);
                $("#p_venta").val(ui.item.p_venta);
                $("#descuento").val(ui.item.descuento);
                $("#disponibles").val(ui.item.disponibles);
                $("#iva_producto").val(ui.item.iva_producto);
                $("#carga_series").val(ui.item.carga_series);
                $("#cod_producto").val(ui.item.cod_producto);
                $("#des").val(ui.item.des);
                $("#inventar").val(ui.item.inventar);
                return false;
                }

                }).data("ui-autocomplete")._renderItem = function(ul, item) {
                return $("<li>")
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
            };
        } else {
            if (precio === "MAYORISTA") {
                $("#producto").autocomplete({
                    source: "../procesos/buscar_producto10.php?tipo_precio=" + precio,
                    minLength: 1,
                    focus: function(event, ui) {
                    $("#codigo_barras").val(ui.item.codigo_barras);
                    $("#producto").val(ui.item.value);
                    $("#codigo").val(ui.item.codigo);
                    $("#p_venta").val(ui.item.p_venta);
                    $("#descuento").val(ui.item.descuento);
                    $("#disponibles").val(ui.item.disponibles);
                    $("#iva_producto").val(ui.item.iva_producto);
                    $("#carga_series").val(ui.item.carga_series);
                    $("#cod_producto").val(ui.item.cod_producto);
                    $("#des").val(ui.item.des);
                    $("#inventar").val(ui.item.inventar);
                    return false;
                    },
                    select: function(event, ui) {
                    $("#codigo_barras").val(ui.item.codigo_barras);
                    $("#producto").val(ui.item.value);
                    $("#codigo").val(ui.item.codigo);
                    $("#p_venta").val(ui.item.p_venta);
                    $("#descuento").val(ui.item.descuento);
                    $("#disponibles").val(ui.item.disponibles);
                    $("#iva_producto").val(ui.item.iva_producto);
                    $("#carga_series").val(ui.item.carga_series);
                    $("#cod_producto").val(ui.item.cod_producto);
                    $("#des").val(ui.item.des);
                    $("#inventar").val(ui.item.inventar);
                    return false;
                    }
                    }).data("ui-autocomplete")._renderItem = function(ul, item) {
                    return $("<li>")
                    .append("<a>" + item.value + "</a>")
                    .appendTo(ul);
                };
            }
        }
    });
    
    ///////////////////limpiar combo//////////
    $("#tipo_precio").change(function() {
        $("#cod_producto").val("");
        $("#codigo_barras").val("");
        $("#codigo").val("");
        $("#producto").val("");
        $("#cantidad").val("");
        $("#p_venta").val("");
        $("#descuento").val("");
        $("#disponibles").val("");
        $("#inventar").val(""); 
    });
    /////////////////////////////////////////
 
    /////buscador cliente identificacion///// 
    $("#ruc_ci").autocomplete({
        source: "../procesos/buscar_cliente.php",
        minLength: 1,
        focus: function(event, ui) {
        $("#ruc_ci").val(ui.item.value);
        $("#id_cliente").val(ui.item.id_cliente);
        $("#nombre_cliente").val(ui.item.nombre_cliente);
        $("#direccion_cliente").val(ui.item.direccion_cliente);
        $("#telefono_cliente").val(ui.item.telefono_cliente);
        $("#correo").val(ui.item.correo);
        $("#nombre_director").val(ui.item.nombre_director);
        return false;
        },
        select: function(event, ui) {
        $("#ruc_ci").val(ui.item.value);
        $("#id_cliente").val(ui.item.id_cliente);
        $("#nombre_cliente").val(ui.item.nombre_cliente);
        $("#direccion_cliente").val(ui.item.direccion_cliente);
        $("#telefono_cliente").val(ui.item.telefono_cliente);
        $("#correo").val(ui.item.correo);
        $("#nombre_director").val(ui.item.nombre_director);
        $("#direccion_cliente").attr("disabled", "disabled");
        $("#telefono_cliente").attr("disabled", "disabled");
        $("#correo").attr("disabled", "disabled");
        return false;
        }

        }).data("ui-autocomplete")._renderItem = function(ul, item) {
        return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
    ////////////////////////////////////////////////
    
    /////buscador cliente nombres///// 
    $("#nombre_cliente").autocomplete({
        source: "../procesos/buscar_cliente_nombre.php",
        minLength: 1,
        focus: function(event, ui) {
        $("#nombre_cliente").val(ui.item.value);
        $("#id_cliente").val(ui.item.id_cliente);
        $("#ruc_ci").val(ui.item.ruc_ci);
        $("#direccion_cliente").val(ui.item.direccion_cliente);
        $("#telefono_cliente").val(ui.item.telefono_cliente);
        $("#correo").val(ui.item.correo);
        $("#nombre_director").val(ui.item.nombre_director);
        return false;
        },
        select: function(event, ui) {
        $("#nombre_cliente").val(ui.item.value);
        $("#id_cliente").val(ui.item.id_cliente);
        $("#ruc_ci").val(ui.item.ruc_ci);
        $("#direccion_cliente").val(ui.item.direccion_cliente);
        $("#telefono_cliente").val(ui.item.telefono_cliente);
        $("#correo").val(ui.item.correo);
        $("#nombre_director").val(ui.item.nombre_director);
        $("#direccion_cliente").attr("disabled", "disabled");
        $("#telefono_cliente").attr("disabled", "disabled");
        $("#correo").attr("disabled", "disabled");
        return false;
        }
        }).data("ui-autocomplete")._renderItem = function(ul, item) {
        return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
    ////////////////////////////////////////////////

    /////////////////////////////////
    $("#cantidad").validCampoFranz("0123456789");
    $("#descuento").validCampoFranz("0123456789");
    $("#num_factura").validCampoFranz("0123456789");
    $("#num_factura").attr("maxlength", "9");
    $("#ruc_ci").validCampoFranz("0123456789");
    /////////////////////////////////////

    //////////atributos////////////
    $("#adelanto").attr("disabled", "disabled");
    $("#meses").attr("disabled", "disabled");
    $("#cuotas").attr("disabled", "disabled");
    /////////////////////////////////

    $('.ui-spinner-button').click(function() {
        $(this).siblings('input').change();
    });

    //////////////////calcular meses/////////
    $("#meses").change(function() {
        var meses = $("#meses").val();
        $('#cuotas').children().remove().end();
        if ($("#formas").val() === "Contado") {
            $("#meses").val("");
            alertify.alert("Error...No se puede diferer");
        } else {
            if ($("#formas").val() === "Credito") {
                if (meses > 1) {
                    if($("#adelanto").val() !== "")
                    {
                        var resta = ($("#tot").val() - $("#adelanto").val());
                        for ( var i = 1; i <= meses - 1; i++) {
                            var calcu = resta / (meses);
                            var entero = Math.floor(calcu).toFixed(2);
                            $("#cuotas").append('<option>'+entero+'</option>'); 
                        }
                        var calcu1 = entero * (meses - 1);
                        var sal = resta - calcu1;
                        var entero2 = sal.toFixed(2);
                        $("#cuotas").append('<option>'+entero2+'</option>'); 
                    }else{
                        var to = $("#tot").val();
                        for (i = 1; i <= meses - 1; i++) {
                            calcu = to / (meses);
                            entero = Math.floor(calcu).toFixed(2);
                            $("#cuotas").append('<option>'+entero+'</option>'); 
                        }
                        calcu1 = entero * (meses - 1);
                        sal = to - calcu1;
                        entero2 = sal.toFixed(2);
                        $("#cuotas").append('<option>'+entero2+'</option>'); 
                    }
                }else{
                    if($("#adelanto").val() !== "")
                    {
                        resta = ($("#tot").val() - $("#adelanto").val());
                        var redo = parseFloat(resta).toFixed(2);
                        $("#cuotas").append('<option>'+redo+'</option>');  
                    }else{
                        to = $("#tot").val();
                        redo = parseFloat(to).toFixed(2);
                        $("#cuotas").append('<option>'+redo+'</option>'); 
                    }
                }
            }
        }
    });
    /////////////////////////////////////////

    //////////////////calcular descuento/////////
    $("#descuento").change(function() {
        if ($("#cod_producto").val() === "") {
            $("#descuento").val(0);
            $("#codigo").val("");
            alertify.alert("Error...Seleccione un producto");
        }
    });
    /////////////////////////////////////////

    /////////series//////////////
    $("#formas").change(function() {
        var tam2 = jQuery("#list").jqGrid("getRowData");
        if ($("#formas").val() === "Contado") {
            $("#adelanto").attr("disabled", "disabled");
            $("#adelanto").val("");
            $("#meses").attr("disabled", "disabled");
            $("#meses").val("");
            $("#cuotas").attr("disabled", "disabled");
            $('#cuotas').children().remove().end();
        } else {
            if ($("#formas").val() === "Credito") {
                if (tam2.length > 0) {
                    $("#adelanto").removeAttr("disabled");
                    $("#meses").removeAttr("disabled");
                    $("#cuotas").removeAttr("disabled");
                } else {
                    $("#formas option[value=" + 'Contado' + "]").attr("selected", true);
                    alertify.alert("Error...Ingrese un monto a la factura");
                }
            }
        }
    });
    ///////////////////////////////////

    ///////////calendarios////////////
    $("#fecha_actual").datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');
    $("#cancelacion").datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');
    $("#fecha_auto").datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');
    $("#fecha_caducidad").datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');
//////////////////////////////////

///////////tabla local/////////////   
    var can;
    jQuery("#list").jqGrid({
        datatype: "local",
        colNames: ['', 'ID', 'Código', 'Producto', 'Cantidad', 'PVP', 'Descuento','Calculado', 'Total', 'Iva', 'Pendientes'],
        colModel: [
            {name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions',
                formatoptions: {keys: false, delbutton: true, editbutton: false}
            },
            {name: 'cod_producto', index: 'cod_producto', editable: false, search: false, hidden: true, editrules: {edithidden: false}, align: 'center',
                frozen: true, width: 50},
            {name: 'codigo', index: 'codigo', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',
                frozen: true, width: 100},
            {name: 'detalle', index: 'detalle', editable: false, frozen: true, editrules: {required: true}, align: 'center', width: 290},
            {name: 'cantidad', index: 'cantidad', editable: false, frozen: true, editrules: {required: true}, align: 'center', width: 70},
            {name: 'precio_u', index: 'precio_u', editable: false, search: false, frozen: true, editrules: {required: true}, align: 'center', width: 110, editoptions:{maxlength: 10, size:15,dataInit: function(elem){$(elem).bind("keypress", function(e) {return punto(e)})}}}, 
            {name: 'descuento', index: 'descuento', editable: false, frozen: true, editrules: {required: true}, align: 'center', width: 90},
            {name: 'cal_des', index: 'cal_des', editable: false, hidden: true, frozen: true, editrules: {required: true}, align: 'center', width: 90},
            {name: 'total', index: 'total', editable: false, search: false, frozen: true, editrules: {required: true}, align: 'center', width: 150},
            {name: 'iva', index: 'iva', align: 'center', width: 100, hidden: true},
            {name: 'pendiente', index: 'pendiente', editable: false, frozen: true, editrules: {required: true}, align: 'center', width: 90}
        ],
        rowNum: 30,
        width: 885,
        height: 300,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager'),
        sortname: 'id_productos',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'clientArray',
        shrinkToFit: true,
        delOptions: {
            modal: true,
            jqModal: true,
            onclickSubmit: function(rp_ge, rowid) {
                var id = jQuery("#list").jqGrid('getGridParam', 'selrow');
                jQuery('#list').jqGrid('restoreRow', id);
                var ret = jQuery("#list").jqGrid('getRowData', id);
                var subtotal = 0;
                var subtotal2 = 0;
                var sub = 0;
                var iva = 0;
                var t_fc = 0;
                var t_fc2 = 0;
                var descu_total = 0;
                var fil = jQuery("#list").jqGrid("getRowData"); 
                if (ret.iva === "Si") {
                   for (var t = 0; t < fil.length; t++) {
                       subtotal = (parseFloat($("#tot").val()) - parseFloat(ret.total)).toFixed(2);
                       sub = parseFloat((subtotal / 1.12)).toFixed(3);
                       iva = (sub * 0.12).toFixed(3);
                       descu_total = (parseFloat($("#desc").val()) - ret.cal_des).toFixed(2);
                       t_fc = ((parseFloat(sub) + parseFloat(iva)) + parseFloat($("#total_p").val())).toFixed(2);
                   }
                     $("#total_p2").val(sub);
                     $("#iva").val(iva);
                     $("#desc").val(descu_total);
                     $("#tot").val(t_fc);
                }else{
                    if (ret.iva === "No") {
                        for (t = 0; t < fil.length; t++) {
                        subtotal2 = (parseFloat($("#total_p").val()) - parseFloat(ret.total)).toFixed(2);
                        descu_total = (parseFloat($("#desc").val()) - ret.cal_des).toFixed(2);
                        t_fc2 = ((parseFloat($("#tot").val()) - parseFloat(ret.total))).toFixed(2);
                        }
                    }
                    $("#total_p").val(subtotal2);
                    $("#desc").val(descu_total);
                    $("#tot").val(t_fc2);
                }
                var su = jQuery("#list").jqGrid('delRowData', rowid);
                   if (su === true) {
                   rp_ge.processing = true;
                   $(".ui-icon-closethick").trigger('click'); 
                   }
                return true;
            },
            processing: true
        },
        afterSaveCell : function(rowid,name,val,iRow,iCol) {
//            var subtotal = 0;
//            var iva = 0;
//            var t_fc = 0;
//            var mu = 0;
//            var des = 0;
//            var descu = 0;
//            var cal = 0;
//            var cal2 = 0;
//            var tot = 0;
//            var descu_total = 0; 
//            var sub = 0;
//            
//            var id = jQuery("#list").jqGrid('getGridParam', 'selrow');
//            jQuery('#list').jqGrid('restoreRow', id);
//            var ret = jQuery("#list").jqGrid('getRowData', id);
            
//            if(name == 'cantidad') {
//               var precio = jQuery("#list").jqGrid('getCell',rowid,iCol+1);
//               var descuento = jQuery("#list").jqGrid('getCell',rowid,iCol+2);
//               
//               var operacion = (parseFloat(val)* parseFloat(precio)).toFixed(2); 
//               cal = ((operacion * descuento)/100).toFixed(2);
//               tot = (operacion - cal).toFixed(2);
//               
//               jQuery("#list").jqGrid('setRowData',rowid,{total: tot });
//               
//               if (ret.iva === "Si") {
//                   var fil = jQuery("#list").jqGrid("getRowData");
//                   for (var t = 0; t < fil.length; t++) {
//                        var dd = fil[t];
//                        if (dd['iva'] === "Si") {
//                            subtotal = (subtotal + parseFloat(dd['total']));
//                            iva = parseFloat((subtotal / 1.12)).toFixed(2);
//                            var sub = (parseFloat(subtotal) - parseFloat(iva)).toFixed(2);                                    
//                            mu = (dd['cantidad'] * dd['precio_u']).toFixed(2);
//                            des = ((mu * dd['descuento'])/100).toFixed(2);
//                            descu = (parseFloat(descu) + parseFloat(des)).toFixed(2);
//                            t_fc = ((parseFloat(sub) + parseFloat(iva)) + parseFloat($("#total_p").val())).toFixed(2);
//                            $("#iva_producto").val("");
//                        }
//                    }
//                    $("#total_p2").val(iva);
//                    $("#iva").val(sub);
//                    $("#desc").val(descu);
//                    $("#tot").val(t_fc);
//               }else{
//                    fil = jQuery("#list").jqGrid("getRowData");
//                    subtotal = 0;
//                    t_fc = 0;
//                    iva = 0;
//                    mu = 0;
//                    des = 0;
//                    descu = 0;
//                    for (t = 0; t < fil.length; t++) {
//                    dd = fil[t];
//                    if (dd['iva'] === "No") {
//                        subtotal = (subtotal + parseFloat(dd['total']));
//                        sub = parseFloat(subtotal).toFixed(2);
//                        mu = (dd['cantidad'] * dd['precio_u']).toFixed(2);
//                        des = ((mu * dd['descuento'])/100).toFixed(2);
//                        descu = (parseFloat(descu) + parseFloat(des)).toFixed(2);
//                        $("#iva_producto").val("");
//                    }
//                }
//                iva = parseFloat($("#iva").val());
//                t_fc = ((parseFloat(subtotal) + parseFloat(iva)) + parseFloat($("#total_p2").val())).toFixed(2);
//                $("#total_p").val(sub);
//                $("#desc").val(descu);
//                $("#tot").val(t_fc);
//               }
//            }
            
//            if(name == 'precio_u') {
//               var cantidad = jQuery("#list").jqGrid('getCell',rowid,iCol-1);
//               var descuento2 = jQuery("#list").jqGrid('getCell',rowid,iCol+1);
//               
//               var operacion2 = (parseFloat(cantidad) * parseFloat(val)).toFixed(2); 
//               cal2 = ((operacion2 * descuento2)/100).toFixed(2);
//               tot = (operacion2 - cal2).toFixed(2);
//               
//               jQuery("#list").jqGrid('setRowData',rowid,{total: tot});
//               
//               if (ret.iva === "Si") {
//                   var fil = jQuery("#list").jqGrid("getRowData");
//                   for (var t = 0; t < fil.length; t++) {
//                        var dd = fil[t];
//                        if (dd['iva'] === "Si") {
//                            subtotal = (parseFloat(subtotal) + parseFloat(dd['total'])).toFixed(2);
//                            sub = (parseFloat((subtotal / 1.12))).toFixed(3);
//                            iva = (sub * 0.12).toFixed(3);
//                            descu_total = (parseFloat(descu_total) + parseFloat(dd['cal_des'])).toFixed(2);
//                            t_fc = ((parseFloat(sub) + (parseFloat(iva)) + parseFloat($("#total_p").val()))).toFixed(2);
//                            $("#iva_producto").val("");
//                            
////                            
////                            subtotal = (subtotal + parseFloat(dd['total']));
////                            iva =  parseFloat((subtotal / 1.12)).toFixed(2);
////                            sub = (parseFloat(subtotal) - parseFloat(iva)).toFixed(2);
////                            mu = (dd['cantidad'] * dd['precio_u']).toFixed(2);
////                            des = ((mu * dd['descuento'])/100).toFixed(2);
////                            descu = (parseFloat(descu) + parseFloat(des)).toFixed(2);
////                            t_fc = ((parseFloat(sub) + parseFloat(iva)) + parseFloat($("#total_p").val())).toFixed(2);
////                            $("#iva_producto").val("");
//                        }
//                    }
//                    $("#total_p2").val(sub);
//                    $("#iva").val(iva);
//                    $("#desc").val(descu);
//                    $("#tot").val(t_fc);
//               }else{
//                    fil = jQuery("#list").jqGrid("getRowData");
//                    subtotal = 0;
//                    t_fc = 0;
//                    iva = 0;
//                    mu = 0;
//                    des = 0;
//                    descu = 0;
//                    for (t = 0; t < fil.length; t++) {
//                    dd = fil[t];
//                    if (dd['iva'] === "No") {
//                        subtotal = (subtotal + parseFloat(dd['total']));
//                        sub = parseFloat(subtotal).toFixed(2);
//                        mu = (dd['cantidad'] * dd['precio_u']).toFixed(2);
//                        des = ((mu * dd['descuento'])/100).toFixed(2);
//                        descu = (parseFloat(descu) + parseFloat(des)).toFixed(2);
//                        $("#iva_producto").val("");
//                    }
//                }
//                iva = parseFloat($("#iva").val());
//                t_fc = ((parseFloat(subtotal) + parseFloat(iva)) + parseFloat($("#total_p2").val())).toFixed(2);
//                $("#total_p").val(sub);
//                $("#desc").val(descu);
//                $("#tot").val(t_fc);
//               }
//            }
        }
  });
    //////////////////////////////////////

     ////////////////////buscador facturas vetas/////////////////////////
        jQuery("#list2").jqGrid({
        url: '../xml/xmlBuscarFacturaVenta.php',
        datatype: 'xml',
        colNames: ['ID','IDENTIFICACIÓN','CLIENTE', 'FACTURA NRO.','MONTO TOTAL','FECHA'],
        colModel: [
            {name: 'id_factura_venta', index: 'id_factura_venta', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 50},
            {name: 'identificacion', index: 'identificacion', editable: false, search: true, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 150},
            {name: 'nombres_cli', index: 'nombres_cli', editable: true, search: true, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 200},
            {name: 'num_factura', index: 'num_factura', editable: true, search: true, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 200},
            {name: 'total_venta', index: 'total_venta', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 100},
            {name: 'fecha_actual', index: 'fecha_actual', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 100},
        ],
        rowNum: 30,
        width: 750,
        height:220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager2'),
        sortname: 'id_factura_venta',
        sortorder: 'asc',
        viewrecords: true,              
        ondblClickRow: function(){
        var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
        jQuery('#list2').jqGrid('restoreRow', id);
        
        if (id) {
           var ret = jQuery("#list2").jqGrid('getRowData', id);
           var valor = ret.id_factura_venta;
         /////////////agregregar datos factura////////
            $("#comprobante").val(valor);
            $("#btnGuardar").attr("disabled", true);
            $("#btnModificar").attr("disabled", true);
            $("#num_factura").attr("disabled", "disabled");
            $("#ruc_ci").attr("disabled", "disabled");
            $("#nombre_cliente").attr("disabled", "disabled");
            $("#direccion_cliente").attr("disabled", "disabled");
            $("#telefono_cliente").attr("disabled", "disabled");
            $("#correo").attr("disabled", "disabled");
            $("#formas").attr("disabled", true);
            $("#ruc_ci").val("");
            $("#nombre_cliente").val("");
            $("#telefono_cliente").val("");
            $("#correo").val("");
            $("#codigo").attr("disabled", "disabled");
            $("#producto").attr("disabled", "disabled");
            $("#cantidad").attr("disabled", "disabled");
            $("#p_venta").attr("disabled", "disabled");
            $("#btncargar").attr("disabled", "disabled");
            $("#autorizacion").attr("disabled", "disabled");
            $("#estado h3").remove();
            $("#formas").val("Contado");
            $("#adelanto").val("");
            $("#meses").val("");
            $('#cuotas').children().remove().end();
            $("#cuotas").attr("disabled", true); 
            $("#list").jqGrid("clearGridData", true);
            $("#total_p").val("0.00");
            $("#total_p2").val("0.00");
            $("#iva").val("0.00");
            $("#desc").val("0.00");
            $("#tot").val("0.00");
            
            $.getJSON('../procesos/retornar_factura_venta.php?com=' + valor, function(data) {
                var tama = data.length;
                if (tama !== 0) {
                    for (var i = 0; i < tama; i = i + 19) {
                    $("#fecha_actual").val(data[i]);
                    $("#hora_actual").val(data[i + 1 ]);
                    $("#digitador").val(data[i + 2 ] + " " + data[i + 3 ] );
                    var num = data[i + 4]; 
                    var res = num.substr(8, 20)
                    $("#num_factura").val(res);
                    $("#id_cliente").val(data[i + 5]);
                    $("#ruc_ci").val(data[i + 6]);
                    $("#nombre_cliente").val(data[i + 7]);
                    $("#direccion_cliente").val(data[i + 8]);
                    $("#telefono_cliente").val(data[i + 9]);
                    $("#correo").val(data[i + 10]);
                    $("#cancelacion").val(data[i + 11]);

                    $("#tipo_precio").val(data[i + 12]);
                    if(data[ i+ 13 ] == "Pasivo"){
                        $("#estado").append($("<h3>").text("Anulada"));
                        $("#estado h3").css("color","red");
                        $("#btnAnular").attr("disabled", "disabled");
                    }else{
                        $("#estado h3").remove();
                        $("#btnAnular").attr("disabled", "disabled");
                        $("#btnAnular").attr("disabled", false);
                    }

                    $("#total_p").val(data[i + 14]);
                    $("#total_p2").val(data[i + 15]);
                    $("#iva").val(data[i + 16]);
                    $("#desc").val(data[i + 17]);
                    $("#tot").val(data[i + 18]);
                   }
                }
            });
            ///////////////////////////////////////////////////   
    
            ///////////////////llamar facturas flechas segunda parte/////
            $.getJSON('../procesos/retornar_factura_venta_credito.php?com=' + valor, function(data) {
                var tama = data.length;
                if (tama !== 0) {
                    for (var i = 0; i < tama; i = i + 4)
                {
                    $("#formas").val(data[i]);
                    $("#adelanto").val(data[i + 1 ]);
                    $("#meses").val(data[i + 2 ]);
                    
                    //////////calcular meses//////////
                    if (data[i + 2 ] > 1) {
                        $("#cuotas").attr("disabled", false); 
                    for (var j = 1; j <= data[i + 2 ] - 1; j++) {
                         var calcu = data[i + 3] / (data[i + 2]);
                         var entero = Math.floor(calcu).toFixed(2);
                         $("#cuotas").append('<option>'+entero+'</option>'); 
                    }
                    var calcu1 = entero * (data[i + 2 ] - 1);
                    var sal = data[i + 3] - calcu1;
                    var entero2 = sal.toFixed(2);
                    $("#cuotas").append('<option>'+entero2+'</option>'); 

                }else{
                  $("#cuotas").attr("disabled", false); 
                  $("#cuotas").append('<option>'+data[i + 3]+'</option>');  
                }
               }
              }
            });
            /////////////////////////////////////////////////////////
    
            ////////////////////llamar facturas flechas tercera parte/////
            $.getJSON('../procesos/retornar_factura_venta2.php?com=' + valor, function(data) {
                var tama = data.length;
                if (tama !== 0) {
                    for (var i = 0; i < tama; i = i + 9)
                    {
                        var datarow = {
                            cod_producto: data[i], 
                            codigo: data[i + 1], 
                            detalle: data[i + 2], 
                            cantidad: data[i + 3], 
                            precio_u: data[i + 4], 
                            descuento: data[i + 5], 
                            total: data[i + 6], 
                            iva: data[i + 7],
                            pendiente: data[i + 8]
                            };
                        var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                    }
                }
            });
            $("#buscar_facturas_venta").dialog("close");
        } else {
          alertify.alert("Seleccione una Factura");
        }
    }
        
        }).jqGrid('navGrid', '#pager2',
        {
            add: false,
            edit: false,
            del: false,
            refresh: true,
            search: true,
            view: true
        },{
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
        });
        
       jQuery("#list2").jqGrid('navButtonAdd', '#pager2', {caption: "Añadir",
       onClickButton: function() {
        var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
        jQuery('#list2').jqGrid('restoreRow', id);
        if (id) {
        var ret = jQuery("#list2").jqGrid('getRowData', id);
        var valor = ret.id_factura_venta;
        /////////////agregregar datos factura////////
        $("#comprobante").val(valor);
        $("#btnGuardar").attr("disabled", true);
        $("#btnModificar").attr("disabled", true);
        $("#num_factura").attr("disabled", "disabled");
        $("#ruc_ci").attr("disabled", "disabled");
        $("#nombre_cliente").attr("disabled", "disabled");
        $("#direccion_cliente").attr("disabled", "disabled");
        $("#telefono_cliente").attr("disabled", "disabled");
        $("#correo").attr("disabled", "disabled");
        $("#formas").attr("disabled", true);
        $("#ruc_ci").val("");
        $("#nombre_cliente").val("");
        $("#telefono_cliente").val("");
        $("#correo").val("");
        $("#codigo").attr("disabled", "disabled");
        $("#producto").attr("disabled", "disabled");
        $("#cantidad").attr("disabled", "disabled");
        $("#p_venta").attr("disabled", "disabled");
        $("#btncargar").attr("disabled", "disabled");
        $("#autorizacion").attr("disabled", "disabled");
        $("#estado h3").remove();
        $("#formas").val("Contado");
        $("#adelanto").val("");
        $("#meses").val("");
        $('#cuotas').children().remove().end();
        $("#cuotas").attr("disabled", true); 
        $("#list").jqGrid("clearGridData", true);
        $("#total_p").val("0.00");
        $("#total_p2").val("0.00");
        $("#iva").val("0.00");
        $("#desc").val("0.00");
        $("#tot").val("0.00");
                
            $.getJSON('../procesos/retornar_factura_venta.php?com=' + valor, function(data) {
                var tama = data.length;
                if (tama !== 0) {
                    for (var i = 0; i < tama; i = i + 19) {
                    $("#fecha_actual").val(data[i]);
                    $("#hora_actual").val(data[i + 1 ]);
                    $("#digitador").val(data[i + 2 ] + " " + data[i + 3 ] );
                    var num = data[i + 4]; 
                    var res = num.substr(8, 20)
                    $("#num_factura").val(res);
                    $("#id_cliente").val(data[i + 5]);
                    $("#ruc_ci").val(data[i + 6]);
                    $("#nombre_cliente").val(data[i + 7]);
                    $("#direccion_cliente").val(data[i + 8]);
                    $("#telefono_cliente").val(data[i + 9]);
                    $("#correo").val(data[i + 10]);
                    $("#cancelacion").val(data[i + 11]);

                    $("#tipo_precio").val(data[i + 12]);
                    if(data[ i+ 13 ] == "Pasivo"){
                        $("#estado").append($("<h3>").text("Anulada"));
                        $("#estado h3").css("color","red");
                        $("#btnAnular").attr("disabled", "disabled");
                    }else{
                        $("#estado h3").remove();
                        $("#btnAnular").attr("disabled", "disabled");
                        $("#btnAnular").attr("disabled", false);
                    }

                    $("#total_p").val(data[i + 14]);
                    $("#total_p2").val(data[i + 15]);
                    $("#iva").val(data[i + 16]);
                    $("#desc").val(data[i + 17]);
                    $("#tot").val(data[i + 18]);
                   }
                }
            });
            ///////////////////////////////////////////////////   
    
            ///////////////////llamar facturas flechas segunda parte/////
            $.getJSON('../procesos/retornar_factura_venta_credito.php?com=' + valor, function(data) {
                var tama = data.length;
                if (tama !== 0) {
                    for (var i = 0; i < tama; i = i + 4)
                {
                    $("#formas").val(data[i]);
                    $("#adelanto").val(data[i + 1 ]);
                    $("#meses").val(data[i + 2 ]);
                    
                    //////////calcular meses//////////
                    if (data[i + 2 ] > 1) {
                        $("#cuotas").attr("disabled", false); 
                    for (var j = 1; j <= data[i + 2 ] - 1; j++) {
                         var calcu = data[i + 3] / (data[i + 2]);
                         var entero = Math.floor(calcu).toFixed(2);
                         $("#cuotas").append('<option>'+entero+'</option>'); 
                    }
                    var calcu1 = entero * (data[i + 2 ] - 1);
                    var sal = data[i + 3] - calcu1;
                    var entero2 = sal.toFixed(2);
                    $("#cuotas").append('<option>'+entero2+'</option>'); 
                }else{
                  $("#cuotas").attr("disabled", false); 
                  $("#cuotas").append('<option>'+data[i + 3]+'</option>');  
                }
               }
              }
            });
            /////////////////////////////////////////////////////////
    
            ////////////////////llamar facturas flechas tercera parte/////
            $.getJSON('../procesos/retornar_factura_venta2.php?com=' + valor, function(data) {
                var tama = data.length;
                if (tama !== 0) {
                     for (var i = 0; i < tama; i = i + 9)
                         {
                        var datarow = {
                            cod_producto: data[i], 
                            codigo: data[i + 1], 
                            detalle: data[i + 2], 
                            cantidad: data[i + 3], 
                            precio_u: data[i + 4], 
                            descuento: data[i + 5], 
                            total: data[i + 6], 
                            iva: data[i + 7],
                            pendiente: data[i + 8]
                            };
                        var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                    }
                }
            });
            $("#buscar_facturas_venta").dialog("close");
        } else {
          alertify.alert("Seleccione una Factura");
        }
    }
});

 //////////////////////series factura venta/////////////////////////
    jQuery("#list3").jqGrid({
        datatype: "local",
        colNames: ['', 'cod_serie', 'Series'],
        colModel: [
            {name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions',
                formatoptions: {keys: false, delbutton: true, editbutton: false}
            },
            {name: 'id_series', index: 'id_series', editable: false, search: false, hidden: true, editrules: {edithidden: false}, align: 'center',
                frozen: true, width: 50},
            {name: 'serie', index: 'serie', editable: false, search: false, hidden: false, editrules: {edithidden: true}, align: 'center',
                frozen: true, width: 100}
        ],
        rowNum: 30,
        width: 450,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager3'),
        sortname: 'id_series',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'clientArray',
        shrinkToFit: true,
        delOptions: {
            onclickSubmit: function(rp_ge, rowid) {
                rp_ge.processing = true;
                var su = jQuery("#list3").jqGrid('delRowData', rowid);
                if (su === true) {
                    $("#delmodlist3").hide();
                   $(".ui-icon-closethick").trigger('click'); 
                }
                return true;
            },
            processing: true
        }
    }).jqGrid('navGrid', '#pager3',
            {
                add: false,
                edit: false,
                del: false,
                refresh: true,
                search: true,
                view: true
            });


     ////////////////////buscador proformas/////////////////////////
        jQuery("#list4").jqGrid({
        url: '../xml/xmlBuscarProforma2.php',
        datatype: 'xml',
        colNames: ['ID','CLIENTE','MONTO TOTAL','FECHA PROFORMA','IDENTIFICACIÓN','DIRECTOR@'],
        colModel: [
            {name: 'id_proforma', index: 'id_factura_venta', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 50},
            {name: 'nombres_cli', index: 'nombres_cli', editable: true, search: true, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 200},
            {name: 'total_proforma', index: 'total_venta', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 100},
            {name: 'fecha_actual', index: 'fecha_actual', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 100},
            {name: 'identificacion', index: 'identificacion', editable: false, search: true, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 150},
            {name: 'nombres', index: 'nombres', editable: true, search: true, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 250},
        ],
        rowNum: 30,
        width: 750,
        height:220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager4'),
        sortname: 'id_proforma',
        sortorder: 'asc',
        viewrecords: true,              
        ondblClickRow: function(){
        var id = jQuery("#list4").jqGrid('getGridParam', 'selrow');
        jQuery('#list4').jqGrid('restoreRow', id);
        
        if (id) {
           var ret = jQuery("#list4").jqGrid('getRowData', id);
           var valor = ret.id_proforma;
           // alert(valor);

           /////////////////llamado datos personales/////////////
        $.getJSON('../procesos/retornar_proforma_clientes.php?id1=' + valor, function(data) {
            var tama2 = data.length;
            for (var i = 0; i < tama2; i = i + 8)
            {
                $("#id_cliente").val(data[i]);
                $("#ruc_ci").val(data[i + 1 ]);
                $("#nombre_cliente").val(data[i + 2]);
                $("#direccion_cliente").val(data[i + 3]);
                $("#telefono_cliente").val(data[i + 4]);
                $("#saldo").val(data[i + 5]);
                $("#tipo_precio").val(data[i + 6]);
                $("#nombre_director").val(data[i + 7]);
            }
        });
        ////////////////////////////////////////////////////

        $.getJSON('../procesos/retornar_proforma.php?id2=' + valor, function(data) {
            var tama = data.length;
            if (tama === 0) {
                alertify.alert("Error... La proforma no existe", function(){
                    location.reload();
                });
            } else {
                $("#list").jqGrid("clearGridData", true);
                var descuento = 0;
                var precio = 0;
                var multi = 0;
                var total = 0;
                var flotante = 0;
                var resultado = 0;

                for (var i = 0; i < tama; i = i + 9) {
                    var temp = 0;
                    var temp1 = 0;
                    if(parseInt(data[i + 3]) < 0){
                        temp = 0; 
                        temp1 = data[i + 4] ;
                    }else{
                        if(parseInt(data[i + 4]) > parseInt(data[i + 3])){
                            temp = data[i + 3]; 
                            temp1 = data[i + 4] - data[i + 3];
                        }else{
                            temp = data[i + 4];   
                            temp1 = 0;   
                        }
                    }

                    // precio = (parseFloat(data[i + 5])).toFixed(2);
                    // multi = (temp * parseFloat(data[i + 5])).toFixed(2);
                    // descuento = ((multi * parseFloat(data[i + 6])) / 100);
                    // flotante = parseFloat(descuento);
                    // resultado = (Math.round(flotante * Math.pow(10,2)) / Math.pow(10,2)).toFixed(2);
                    // total = (parseFloat(multi - resultado)).toFixed(2);

                    multi = (temp * parseFloat(data[i + 5])).toFixed(2);
                    descuento = ((multi * parseFloat(data[i + 6])) / 100);
                    flotante = parseFloat(descuento);
                    resultado = (Math.round(flotante * Math.pow(10,2)) / Math.pow(10,2)).toFixed(2);
                    total = (parseFloat(multi - resultado)).toFixed(2);


                    // total = (multi - descuento).toFixed(2);

                    var datarow = {
                        cod_producto: data[i], 
                        codigo: data[i + 1], 
                        detalle: data[i + 2], 
                        cantidad: temp, 
                        precio_u: data[i + 5], 
                        descuento: data[i + 6],
                        cal_des: resultado, 
                        total: total, 
                        iva: data[i + 8], 
                        pendiente: temp1
                    };
                    var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                    var ivas = data[i + 8];
                   
                    ////////////////calcular valores////////////
                    // var fil = jQuery("#list").jqGrid("getRowData");
                    // var subtotal = 0;
                    // var iva = 0;
                    // var t_fc = 0;
                    // var mu = 0;
                    // var des = 0;
                    // var descu = 0;

                    var subtotal = 0;
                    var iva = 0;
                    var t_fc = 0;
                    var sub = 0;
                    var descu_total = 0; 

                    if (ivas === "Si") {
                        var fil = jQuery("#list").jqGrid("getRowData");
                        for (var t = 0; t < fil.length; t++) {
                            var dd = fil[t];
                            if (dd['iva'] === "Si") {
                                subtotal = (parseFloat(subtotal) + parseFloat(dd['total'])).toFixed(2);
                                sub = (parseFloat((subtotal / 1.12))).toFixed(3);
                                iva = (sub * 0.12).toFixed(3);
                                descu_total = (parseFloat(descu_total) + parseFloat(dd['cal_des'])).toFixed(2);
                                t_fc = ((parseFloat(sub) + (parseFloat(iva)) + parseFloat($("#total_p").val()))).toFixed(2);
                                $("#iva_producto").val("");
                            }
                        }
                        $("#total_p2").val(sub);
                        $("#iva").val(iva);
                        $("#desc").val(descu_total);
                        $("#tot").val(t_fc);
                        // fil = jQuery("#list").jqGrid("getRowData");
                        // subtotal = 0;
                        // iva = 0;
                        // t_fc = 0;
                        // mu = 0;
                        // des = 0;
                        // descu = 0;
                        
                        // for (var t = 0; t < fil.length; t++) {
                        //     var dd = fil[t];
                        //     if (dd['iva'] === "Si") {
                        //         subtotal = (subtotal + parseFloat(dd['total']));
                        //         var sub = parseFloat(subtotal).toFixed(2);
                               
                        //         mu = (dd['cantidad'] * dd['precio_u']).toFixed(2);
                        //         des = ((mu * dd['descuento'])/100).toFixed(2);
                        //         descu = (parseFloat(descu) + parseFloat(des)).toFixed(2);
                        //         $("#iva_producto").val("");
                        //     }
                        // }
                        // iva = ((subtotal * 12) / 100).toFixed(2);
                        // t_fc = ((parseFloat(subtotal) + parseFloat(iva)) + parseFloat($("#total_p").val())).toFixed(2);
                        // $("#total_p2").val(sub);
                        // $("#iva").val(iva);
                        // $("#desc").val(descu);
                        // $("#tot").val(t_fc);
                    } else {
                        if (ivas === "No") {
                            fil = jQuery("#list").jqGrid("getRowData");
                            subtotal = 0;
                            t_fc = 0;
                            iva = 0;
                            for (t = 0; t < fil.length; t++) {
                                dd = fil[t];
                                if (dd['iva'] === "No") {
                                    subtotal = (subtotal + parseFloat(dd['total']));
                                    sub = parseFloat(subtotal).toFixed(2);
                                    
                                    mu = (dd['cantidad'] * dd['precio_u']).toFixed(2);
                                    des = ((mu * dd['descuento'])/100).toFixed(2);
                                    descu = (parseFloat(descu) + parseFloat(des)).toFixed(2);
                                    $("#iva_producto").val("");
                                }
                            }
                            
                            iva = parseFloat($("#iva").val());
                            t_fc = ((parseFloat(subtotal) + parseFloat(iva)) + parseFloat($("#total_p2").val())).toFixed(2);
                            $("#total_p").val(sub);
                            $("#desc").val(descu);
                            $("#tot").val(t_fc);
                        }
                    }
                }
            }
        });

         /////////////agregregar datos factura////////
            // $("#comprobante").val(valor);
            // $("#btnGuardar").attr("disabled", true);
            // $("#btnModificar").attr("disabled", true);
            // $("#num_factura").attr("disabled", "disabled");
            // $("#ruc_ci").attr("disabled", "disabled");
            // $("#nombre_cliente").attr("disabled", "disabled");
            // $("#direccion_cliente").attr("disabled", "disabled");
            // $("#telefono_cliente").attr("disabled", "disabled");
            // $("#correo").attr("disabled", "disabled");
            // $("#formas").attr("disabled", true);
            // $("#ruc_ci").val("");
            // $("#nombre_cliente").val("");
            // $("#telefono_cliente").val("");
            // $("#correo").val("");
            // $("#codigo").attr("disabled", "disabled");
            // $("#producto").attr("disabled", "disabled");
            // $("#cantidad").attr("disabled", "disabled");
            // $("#p_venta").attr("disabled", "disabled");
            // $("#btncargar").attr("disabled", "disabled");
            // $("#autorizacion").attr("disabled", "disabled");
            // $("#estado h3").remove();
            // $("#formas").val("Contado");
            // $("#adelanto").val("");
            // $("#meses").val("");
            // $('#cuotas').children().remove().end();
            // $("#cuotas").attr("disabled", true); 
            // $("#list").jqGrid("clearGridData", true);
            // $("#total_p").val("0.00");
            // $("#total_p2").val("0.00");
            // $("#iva").val("0.00");
            // $("#desc").val("0.00");
            // $("#tot").val("0.00");
            
            // $.getJSON('../procesos/retornar_factura_venta.php?com=' + valor, function(data) {
            //     var tama = data.length;
            //     if (tama !== 0) {
            //         for (var i = 0; i < tama; i = i + 19) {
            //         $("#fecha_actual").val(data[i]);
            //         $("#hora_actual").val(data[i + 1 ]);
            //         $("#digitador").val(data[i + 2 ] + " " + data[i + 3 ] );
            //         var num = data[i + 4]; 
            //         var res = num.substr(8, 20)
            //         $("#num_factura").val(res);
            //         $("#id_cliente").val(data[i + 5]);
            //         $("#ruc_ci").val(data[i + 6]);
            //         $("#nombre_cliente").val(data[i + 7]);
            //         $("#direccion_cliente").val(data[i + 8]);
            //         $("#telefono_cliente").val(data[i + 9]);
            //         $("#correo").val(data[i + 10]);
            //         $("#cancelacion").val(data[i + 11]);

            //         $("#tipo_precio").val(data[i + 12]);
            //         if(data[ i+ 13 ] == "Pasivo"){
            //             $("#estado").append($("<h3>").text("Anulada"));
            //             $("#estado h3").css("color","red");
            //             $("#btnAnular").attr("disabled", "disabled");
            //         }else{
            //             $("#estado h3").remove();
            //             $("#btnAnular").attr("disabled", "disabled");
            //             $("#btnAnular").attr("disabled", false);
            //         }

            //         $("#total_p").val(data[i + 14]);
            //         $("#total_p2").val(data[i + 15]);
            //         $("#iva").val(data[i + 16]);
            //         $("#desc").val(data[i + 17]);
            //         $("#tot").val(data[i + 18]);
            //        }
            //     }
            // });
            // ///////////////////////////////////////////////////   
    
            // ///////////////////llamar facturas flechas segunda parte/////
            // $.getJSON('../procesos/retornar_factura_venta_credito.php?com=' + valor, function(data) {
            //     var tama = data.length;
            //     if (tama !== 0) {
            //         for (var i = 0; i < tama; i = i + 4)
            //     {
            //         $("#formas").val(data[i]);
            //         $("#adelanto").val(data[i + 1 ]);
            //         $("#meses").val(data[i + 2 ]);
                    
            //         //////////calcular meses//////////
            //         if (data[i + 2 ] > 1) {
            //             $("#cuotas").attr("disabled", false); 
            //         for (var j = 1; j <= data[i + 2 ] - 1; j++) {
            //              var calcu = data[i + 3] / (data[i + 2]);
            //              var entero = Math.floor(calcu).toFixed(2);
            //              $("#cuotas").append('<option>'+entero+'</option>'); 
            //         }
            //         var calcu1 = entero * (data[i + 2 ] - 1);
            //         var sal = data[i + 3] - calcu1;
            //         var entero2 = sal.toFixed(2);
            //         $("#cuotas").append('<option>'+entero2+'</option>'); 

            //     }else{
            //       $("#cuotas").attr("disabled", false); 
            //       $("#cuotas").append('<option>'+data[i + 3]+'</option>');  
            //     }
            //    }
            //   }
            // });
            // /////////////////////////////////////////////////////////
    
            // ////////////////////llamar facturas flechas tercera parte/////
            // $.getJSON('../procesos/retornar_factura_venta2.php?com=' + valor, function(data) {
            //     var tama = data.length;
            //     if (tama !== 0) {
            //         for (var i = 0; i < tama; i = i + 9)
            //         {
            //             var datarow = {
            //                 cod_producto: data[i], 
            //                 codigo: data[i + 1], 
            //                 detalle: data[i + 2], 
            //                 cantidad: data[i + 3], 
            //                 precio_u: data[i + 4], 
            //                 descuento: data[i + 5], 
            //                 total: data[i + 6], 
            //                 iva: data[i + 7],
            //                 pendiente: data[i + 8]
            //                 };
            //             var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
            //         }
            //     }
            // });
            $("#buscar_proformas").dialog("close");
        } else {
          alertify.alert("Seleccione una Factura");
        }
    }
        
        }).jqGrid('navGrid', '#pager4',
        {
            add: false,
            edit: false,
            del: false,
            refresh: true,
            search: true,
            view: true
        },{
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
        });
        
       jQuery("#list4").jqGrid('navButtonAdd', '#pager4', {caption: "Añadir",
       onClickButton: function() {
        var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
        jQuery('#list4').jqGrid('restoreRow', id);
        if (id) {
        var ret = jQuery("#list2").jqGrid('getRowData', id);
        var valor = ret.id_factura_venta;
        /////////////agregregar datos factura////////
        $("#comprobante").val(valor);
        $("#btnGuardar").attr("disabled", true);
        $("#btnModificar").attr("disabled", true);
        $("#num_factura").attr("disabled", "disabled");
        $("#ruc_ci").attr("disabled", "disabled");
        $("#nombre_cliente").attr("disabled", "disabled");
        $("#direccion_cliente").attr("disabled", "disabled");
        $("#telefono_cliente").attr("disabled", "disabled");
        $("#correo").attr("disabled", "disabled");
        $("#formas").attr("disabled", true);
        $("#ruc_ci").val("");
        $("#nombre_cliente").val("");
        $("#telefono_cliente").val("");
        $("#correo").val("");
        $("#codigo").attr("disabled", "disabled");
        $("#producto").attr("disabled", "disabled");
        $("#cantidad").attr("disabled", "disabled");
        $("#p_venta").attr("disabled", "disabled");
        $("#btncargar").attr("disabled", "disabled");
        $("#autorizacion").attr("disabled", "disabled");
        $("#estado h3").remove();
        $("#formas").val("Contado");
        $("#adelanto").val("");
        $("#meses").val("");
        $('#cuotas').children().remove().end();
        $("#cuotas").attr("disabled", true); 
        $("#list").jqGrid("clearGridData", true);
        $("#total_p").val("0.00");
        $("#total_p2").val("0.00");
        $("#iva").val("0.00");
        $("#desc").val("0.00");
        $("#tot").val("0.00");
                
            $.getJSON('../procesos/retornar_factura_venta.php?com=' + valor, function(data) {
                var tama = data.length;
                if (tama !== 0) {
                    for (var i = 0; i < tama; i = i + 19) {
                    $("#fecha_actual").val(data[i]);
                    $("#hora_actual").val(data[i + 1 ]);
                    $("#digitador").val(data[i + 2 ] + " " + data[i + 3 ] );
                    var num = data[i + 4]; 
                    var res = num.substr(8, 20)
                    $("#num_factura").val(res);
                    $("#id_cliente").val(data[i + 5]);
                    $("#ruc_ci").val(data[i + 6]);
                    $("#nombre_cliente").val(data[i + 7]);
                    $("#direccion_cliente").val(data[i + 8]);
                    $("#telefono_cliente").val(data[i + 9]);
                    $("#correo").val(data[i + 10]);
                    $("#cancelacion").val(data[i + 11]);

                    $("#tipo_precio").val(data[i + 12]);
                    if(data[ i+ 13 ] == "Pasivo"){
                        $("#estado").append($("<h3>").text("Anulada"));
                        $("#estado h3").css("color","red");
                        $("#btnAnular").attr("disabled", "disabled");
                    }else{
                        $("#estado h3").remove();
                        $("#btnAnular").attr("disabled", "disabled");
                        $("#btnAnular").attr("disabled", false);
                    }

                    $("#total_p").val(data[i + 14]);
                    $("#total_p2").val(data[i + 15]);
                    $("#iva").val(data[i + 16]);
                    $("#desc").val(data[i + 17]);
                    $("#tot").val(data[i + 18]);
                   }
                }
            });
            ///////////////////////////////////////////////////   
    
            ///////////////////llamar facturas flechas segunda parte/////
            $.getJSON('../procesos/retornar_factura_venta_credito.php?com=' + valor, function(data) {
                var tama = data.length;
                if (tama !== 0) {
                    for (var i = 0; i < tama; i = i + 4)
                {
                    $("#formas").val(data[i]);
                    $("#adelanto").val(data[i + 1 ]);
                    $("#meses").val(data[i + 2 ]);
                    
                    //////////calcular meses//////////
                    if (data[i + 2 ] > 1) {
                        $("#cuotas").attr("disabled", false); 
                    for (var j = 1; j <= data[i + 2 ] - 1; j++) {
                         var calcu = data[i + 3] / (data[i + 2]);
                         var entero = Math.floor(calcu).toFixed(2);
                         $("#cuotas").append('<option>'+entero+'</option>'); 
                    }
                    var calcu1 = entero * (data[i + 2 ] - 1);
                    var sal = data[i + 3] - calcu1;
                    var entero2 = sal.toFixed(2);
                    $("#cuotas").append('<option>'+entero2+'</option>'); 
                }else{
                  $("#cuotas").attr("disabled", false); 
                  $("#cuotas").append('<option>'+data[i + 3]+'</option>');  
                }
               }
              }
            });
            /////////////////////////////////////////////////////////
    
            ////////////////////llamar facturas flechas tercera parte/////
            $.getJSON('../procesos/retornar_factura_venta2.php?com=' + valor, function(data) {
                var tama = data.length;
                if (tama !== 0) {
                     for (var i = 0; i < tama; i = i + 9)
                         {
                        var datarow = {
                            cod_producto: data[i], 
                            codigo: data[i + 1], 
                            detalle: data[i + 2], 
                            cantidad: data[i + 3], 
                            precio_u: data[i + 4], 
                            descuento: data[i + 5], 
                            total: data[i + 6], 
                            iva: data[i + 7],
                            pendiente: data[i + 8]
                            };
                        var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                    }
                }
            });
            $("#buscar_facturas_venta").dialog("close");
        } else {
          alertify.alert("Seleccione una Factura");
        }
    }
});



}




