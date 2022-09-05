$(document).on("ready", inicio);
var calculoIVA=0;
var t;
$(document).keydown(function(e) {
    var e = e || event;
    var keycode = e.which || e.keyCode;
    var obj = e.target || e.srcElement;
    // Guardar Reservación
    if(keycode == 119) { guardar_receta()}
    // Tecla Control Cliente
});
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
    if (hours == 0)
        hours = 12;
    if (minutes <= 9)
        minutes = "0" + minutes;
    if (seconds <= 9)
        seconds = "0" + seconds;
    $("#hora_actual").val(hours + ":" + minutes + ":" + seconds + " " + dn);

    setTimeout("show()", 1000);
}

var dialogo = {
    autoOpen: false,
    resizable: false,
    width: 600,
    height: 420,
    modal: true
};

var dialogo2 = {
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 350,
    modal: true,
    show: "explode",
    hide: "blind"    
}

var dialogo3 = {
    autoOpen: false,
    resizable: false,
    width: 420,
    height: 180,
    modal: true,
    show: "explode",
    hide: "blind"    
}

var dialogo4 = {
    autoOpen: false,
    resizable: false,
    width: 300,
    height: 200,
    modal: true,
    show: "explode",
    hide: "blind"
    
}

var dialogo5 = {
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 350,
    modal: true,
    show: "explode",
    hide: "blind"   
}

var dialogotecnico = {
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 350,
    modal: true,
    show: "explode",
    hide: "blind"   
}

var dialogo6 = {
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 350,
    modal: true,
    show: "explode",
    hide: "blind"
}

var dialogo7 = {
    autoOpen: false,
    resizable: false,
    width: 300,
    height: 200,
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
    if (e.which == 13 || e.keyCode == 13) {
        entrar();
        return false;
    }
    return true;
}

function enter1(e) {
    if (e.which == 13 || e.keyCode == 13) {
        entrar2();
        return false;
    }
    return true;
}

function enter2(e) {
    if (e.which == 13 || e.keyCode == 13) {
        entrar3();
        return false;
    }
    return true;
}

function enter3(e) {
    if (e.which == 13 || e.keyCode == 13) {
        comprobar();
        return false;
    }
    return true;
}

function enter4(e) {
    if (e.which == 13 || e.keyCode == 13) {
        comprobar1();
        return false;
    }
    return true;
}

function enter5(e) {
    if (e.which == 13 || e.keyCode == 13) {
        comprobar2();
        return false;
    }
    return true;
}

function enter6(e) {
    if (e.which == 13 || e.keyCode == 13) {
        comprobar3();
        return false;
    }
    return true;
}

function entrar() {
    if ($("#cod_producto").val() == "") {
        $("#codigo_barras").focus();
        alertify.error("Ingrese un producto");
    } else {
        if ($("#codigo").val() == "") {
            $("#codigo").focus();
            alertify.error("Ingrese un producto");
        } else {
            if ($("#producto").val() == "") {
                $("#producto").focus();
                alertify.error("Ingrese un producto");
            } else {
                if ($("#cantidad").val() == "") {
                    $("#cantidad").focus();
                } else {
                    $("#p_venta").focus();
                }
            }
        }
    }
}

function entrar2() {
    if ($("#cod_producto").val() == "") {
        $("#codigo_barras").focus();
        alertify.error("Ingrese un producto");
    } else {
        if ($("#codigo").val() == "") {
            $("#codigo").focus();
            alertify.error("Ingrese un producto");
        } else {
            if ($("#producto").val() == "") {
                $("#producto").focus();
                alertify.error("Ingrese un producto");
            } else {
                if ($("#cantidad").val() == "") {
                    $("#cantidad").focus();
                } else {
                    if ($("#p_venta").val() == "") {
                        $("#p_venta").focus();
                        alertify.error("Ingrese precio venta");
                    } else {
                        //$("#descuento").focus();
                        entrar3();
                    }
                }
            }
        }
    }
}

function limpiar_campos () {
    $("#cod_producto").val("");
    $("#codigo_barras").val("");
    $("#codigo").val("");
    $("#producto").val("");
    $("#cantidad").val("");
    $("#p_venta").val("");
    $("#descuento").val("");
    $("#des").val("");
    $("#disponibles").val("");
    $("#incluye").val("");
    $("#carga_series").val("");
} 

function entrar3() {

    $.ajax({
        type: "POST",
        url: "buscar_iva.php",
        data: "",
        success: function(data) {
            var  val = data;
            if (val != 1) {
                calculoIVA=val; 
            }
        }
    });

    if ($("#cod_producto").val() == "") {
        $("#codigo_barras").focus();
        alertify.error("Ingrese un producto");
    } else {
        if ($("#codigo").val() == "") {
            $("#codigo").focus();
            alertify.error("Ingrese un producto");
        } else {
            if ($("#producto").val() == "") {
                $("#producto").focus();
                alertify.error("Ingrese un producto");
            } else {
                if ($("#cantidad").val() == "") {
                    $("#cantidad").focus();
                } else {
                    if ($("#p_venta").val() == "") {
                        $("#p_venta").focus();
                        alertify.error("Ingrese un precio");
                    } else {
                            if (parseInt($("#cantidad").val()) > parseInt($("#disponibles").val())) {
                                $("#cantidad").focus();
                                alertify.error("Error.. Fuera de Stock cantidad disponible: " +$("#disponibles").val());
                            } else {
                                var filas = jQuery("#list").jqGrid("getRowData");
                                var total = 0;
                                var su = 0;
                                var desc = 0;
                                var precio = 0;
                                var multi = 0;
                                var flotante = 0;
                                var resultado = 0;
                                var repe = 0;
                                var suma = 0; 
                                var unidad=($("#unidad").val()).split("-");

                                if (filas.length == 0) {
                                    precio = parseFloat($("#p_venta").val());
                                    multi = parseFloat($("#cantidad").val()) * parseFloat(precio);
                                    total = parseFloat(multi);
                                    
                                    var datarow = {
                                        cod_producto: $("#cod_producto").val(), 
                                        codigo: $("#codigo").val(), 
                                        detalle: $("#producto").val(), 
                                        cantidad: $("#cantidad").val(), 
                                        precio_u: precio, 
                                        unidad: unidad[1], 
                                        unidadx: unidad[0], 
                                        total: total.toFixed(2)
                                    };
                                    $("#tot").val(parseFloat($("#tot").val())+total);
                                    $("#totx").val((parseFloat($("#totx").val())+total).toFixed(2));
                                    su = jQuery("#list").jqGrid('addRowData', $("#cod_producto").val(), datarow);
                                    limpiar_campos();
                                } else {
                                    for (var i = 0; i < filas.length; i++) {
                                         var id = filas[i];
                                        
                                        if (id['cod_producto'] == $("#cod_producto").val()) {
                                            repe = 1;
				                            var can = id['cantidad'];		
                                        }
                                    }

                                    if (repe == 1) {
                                        suma = parseInt(can) + parseInt($("#cantidad").val());


                                            precio = parseFloat($("#p_venta").val());
                                            multi = parseFloat(suma) * parseFloat(precio);
                                            total = parseFloat(multi);
                                            var ante=can*precio;
                                            datarow = {
                                                cod_producto: $("#cod_producto").val(), 
                                                codigo: $("#codigo").val(), 
                                                detalle: $("#producto").val(), 
                                                cantidad: suma, 
                                                precio_u: precio,  
                                                unidad: unidad[1], 
                                                unidadx: unidad[0], 
                                                total: total.toFixed(2)
                                            };
                                            $("#tot").val(parseFloat($("#tot").val())+total-ante);
                                            $("#totx").val((parseFloat($("#totx").val())+total-ante).toFixed(2));
                                            su = jQuery("#list").jqGrid('setRowData', $("#cod_producto").val(), datarow);
                                            limpiar_campos();                             
                                    } else {
                                        if(filas.length < 18) {
                                            precio = parseFloat($("#p_venta").val());
                                            multi = parseFloat($("#cantidad").val()) * parseFloat(precio);
                                            total = parseFloat(multi);
                                            
                                        
                                            datarow = {
                                                cod_producto: $("#cod_producto").val(), 
                                                codigo: $("#codigo").val(), 
                                                detalle: $("#producto").val(), 
                                                cantidad: $("#cantidad").val(), 
                                                precio_u: precio,  
                                                unidad: unidad[1], 
                                                unidadx: unidad[0], 
                                                total: total.toFixed(2)
                                            };
                                            $("#tot").val(parseFloat($("#tot").val())+total);
                                            $("#totx").val((parseFloat($("#totx").val())+total).toFixed(2));
                                            su = jQuery("#list").jqGrid('addRowData', $("#cod_producto").val(), datarow);
                                            limpiar_campos();                                       
                                        } else {
                                            alertify.error("Error... Alcanzo el limite máximo de Items");
                                        }
                                    }
                                }
                            }
                    }
                }
            }
        }
    }
}

function abrirDialogo() {
    var cod = $("#cod_producto").val();
    
    if (cod == "") {
        alertify.alert("Error... Seleccione un producto");
    } else {
        $("#combobox").append('<option></option>');
        $.getJSON('retornar_series.php?cod=' + cod, function(data) {
            var tama = data.length;
            if (tama == 0) {
                alertify.alert("Series no ingresadas"); 
            } else {
                if($("#cantidad").val() == ""){
                    $("#cantidad").focus();
                    alertify.alert("Error... Indique una cantidad");
                } else {
                    $('#combobox').children().remove().end();
                    $("#series").dialog("open");
                    $("#combobox").append('<option></option>');
                    for (var i = 0; i < tama; i = i + 1) {
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
    if ($("#combobox").val() != "") {
        var filas2 = jQuery("#list3").jqGrid("getRowData");
        var su;
        var count = 0;
        var canti = $("#cantidad").val();
        
        if (filas2.length < canti) {
            if (filas2.length == 0) {
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
                if (repe == 0) {
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
    if ($("#num_factura").val() == "") {
        $("#num_factura").focus();
        alertify.error("Ingrese número de factura");
    } else {
        var a = autocompletar($("#num_factura").val());
        $("#num_factura").val(a + "" + $("#num_factura").val());
        $("#ruc_ci").focus();
    }
}


function comprobar1() {
    if ($("#num_factura").val() == "") {
        $("#num_factura").focus();
        alertify.error("Ingrese número de factura");
    } else {
        if ($("#id_cliente").val() == "" && $("#ruc_ci").val() != "") {
            nuevo_cliente();
        } else {
            if ($("#ruc_ci").val() == "") {
                $("#ruc_ci").focus();
                alertify.error("Indique un cliente");
            } 
        } 
    }
}

function comprobar2(){
    if ($("#ruc_ci").val() == "") {
        $("#ruc_ci").focus();
        alertify.error("Indique un cliente");
    } else {
        if ($("#nombre_cliente").val() == "") {
            $("#nombre_cliente").focus();
            alertify.error("Nombres del cliente");
        } else {
            if ($("#direccion_cliente").val() == "") {
                $("#direccion_cliente").focus();
                alertify.error("Dirección del cliente");
            } else {
                $("#telefono_cliente").focus();
            }  
        } 
    }    
}

function comprobar3(){
    if ($("#ruc_ci").val() == "") {
        $("#ruc_ci").focus();
        alertify.error("Indique un cliente");
    } else {
        if ($("#nombre_cliente").val() == "") {
            $("#nombre_cliente").focus();
            alertify.error("Nombres del cliente");
        } else{
            if ($("#direccion_cliente").val() == "") {
                $("#direccion_cliente").focus();
                alertify.error("Dirección del cliente");
            } else {
                $("#correo").focus();
            }  
        } 
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
            url: "guardar_series.php",
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

function generar(){
    if($("#cod_productos2").val()==""){
        alertify.alert("Elija un producto");
    }else{
        if($("#cantidad").val()==""){
            alertify.alert("Ingrese una cantidad válida");
        }else{
            var valor=$("#id_receta2").val();
            $.getJSON('buscar_receta.php?com=' + valor, function(data) {
                var tama = data.length;
                var cant;
                var contador=0;
                if (tama != 0) {
                    $("#list").jqGrid("clearGridData", true);
                    for (var i = 0; i < tama; i = i + 8) {
                        cant=data[i + 3]*$("#cantidad").val();
                        if(cant>data[i+4]){
                            contador=contador+1;
                        }
                        var datarow = {
                            cod_producto: data[i], 
                            codigo: data[i + 1],
                            detalle: data[i + 2],
                            precio_u: data[i + 7], 
                            cantidad: cant, 
                            stock: data[i + 4], 
                            unidad: data[i + 6],
                            unidadx: data[i + 5],
                           total: (data[i + 3]*$("#cantidad").val()) * data[i + 7]
                        };
                        var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        
                        var costo_total=$("#costo_producto").val()*$("#cantidad").val();
                        $("#tot").val(costo_total);
                        $("#totx").val(parseFloat(costo_total).toFixed(2));
                        if(contador>0){
                            $("#mensaje").val("Algunas cantidades necesarias sobrepasan el stock");
                            $("#btnGuardar").attr("disabled","disabled");
                        }else{
                            $("#mensaje").val("");
                            if($("#id_orden").val()=="")
                                $("#btnGuardar").attr("disabled",false);
                        }
                    }
                }
            });
        }
    }
}

function guardar_orden() {
    var tam = jQuery("#list").jqGrid("getRowData");

    if ($("#producto2").val() == "") {
        $("#producto2").focus();
        alertify.error("Indique un producto");
    } else {
        if (tam.length == 0) {
            $("#codigo_barras").focus();
            alertify.error("Error... Genere la orden de producción");
        } else {
            $("#btnGuardar").attr("disabled", true);
            var v1 = new Array();
            var v2 = new Array();
            var v3 = new Array();
            var v4 = new Array();
            var v5 = new Array();

            var string_v1 = "";
            var string_v2 = "";
            var string_v3 = "";
            var string_v4 = "";
            var string_v5 = "";
            var fil = jQuery("#list").jqGrid("getRowData");
                
            for (var i = 0; i < fil.length; i++) {
                var datos = fil[i];
                v1[i] = datos['cod_producto'];
                v2[i] = datos['precio_u'];
                v3[i] = datos['cantidad'];
                v4[i] = datos['unidadx'];
                v5[i] = datos['total'];
            }
                                
            for (i = 0; i < fil.length; i++) {
                string_v1 = string_v1 + "|" + v1[i];
                string_v2 = string_v2 + "|" + v2[i];
                string_v3 = string_v3 + "|" + v3[i];
                string_v4 = string_v4 + "|" + v4[i];
                string_v5 = string_v5 + "|" + v5[i];
            }
            
            $.ajax({
                type: "POST",
                url: "guardar_orden.php",
                data: "id_receta=" + $("#id_receta2").val() + "&comprobante=" + $("#comprobante").val() + "&fecha_actual=" + $("#fecha_actual").val() + "&hora_actual=" + $("#hora_actual").val() + "&costo=" + $("#tot").val() + "&campo1=" + string_v1 + "&campo2=" + string_v2 + "&campo3=" + string_v3 + "&campo4=" + string_v4 + "&campo5=" + string_v5+"&cantidad=" + $("#cantidad").val(),
                success: function(data) {
                    var val = data;
                    if (val != 0) {
						alertify.alert("Orden de Producción Guardada Correctamente", function(){
                            window.open("../../reportes/orden_produccion.php?id="+val,'_blank');    
                            location.reload();
                        }); 
                    }
                }
            });
        }
    }   
}

function modificar_orden() {
    var tam = jQuery("#list").jqGrid("getRowData");

    if ($("#producto2").val() == "") {
        $("#producto2").focus();
        alertify.error("Indique un producto");
    } else {
        if (tam.length == 0) {
            $("#codigo_barras").focus();
            alertify.error("Error... Genere la orden de producción");
        } else {
            $("#btnModificar").attr("disabled", true);
                           
            $.ajax({
                type: "POST",
                url: "modificar_ordenes.php",
                data: "id_orden="+$("#id_orden").val()+"&id_receta=" + $("#id_receta2").val() + "&comprobante=" + $("#comprobante").val() + "&fecha_actual=" + $("#fecha_actual").val() + "&hora_actual=" + $("#hora_actual").val() + "&costo=" + $("#tot").val() +"&cantidad=" + $("#cantidad").val(),
                success: function(data) {
                    var val = data;
                    if (val != 0) {
                        alertify.alert("Orden de Producción Modificada Correctamente", function(){
                            //var myWindow = window.open("../../reportes/factura_venta.php?hoja=A4&id="+val,'_blank');
                            //myWindow.focus();
                            //myWindow.print();                                                                      
                            location.reload();
                        });
                    }
                }
            }); 
        } 
    }   
}

function flecha_atras() {
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "ordenes_produccion" + "&id_tabla=" + "id_ordenes" + "&tipo=" + 1,
        success: function(data) {
            var val = data;
            if(val != "") {
                $("#comprobante").val(val);
                var valor = $("#comprobante").val();
                // llamar factura venta
                
                $("#btnGuardar").attr("disabled", true);
                $("#producto2").attr("disabled", true);
                $("#btnImprimir").attr("disabled", false);

                $("#estado h3").remove();
                
                $("#list").jqGrid("clearGridData", true);
                $("#tot").val("0.000");
                $("#totx").val("0.000");

                $.getJSON('retornar_orden.php?com=' + valor, function(data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 11) {
                            $("#id_receta2").val(data[i]);
                            $("#fecha_actual").val(data[i + 1]);
                            $("#digitador").val(data[i + 2]);
                            $("#costo_producto").val(data[i + 4]);
                            $("#producto2").val(data[i + 5]);
                            $("#cod_productos2").val(data[i + 6]);
                            $("#id_orden").val(data[i + 7]);
                            $("#cantidad").val(data[i + 8]);

                            if(data[i + 3 ] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulada"));
                                $("#estado h3").css("color","red");
                                $("#btnAnular").attr("disabled", "disabled");
                                $("#btnModificar").attr("disabled", true);
                            } else {
                                $("#estado h3").remove();
                                $("#btnAnular").attr("disabled", false);
                                $("#btnModificar").attr("disabled", false);
                                if(data[10] == 0){
                                    $("#btnModificar").attr("disabled", false);
                                    $("#btnAnular").attr("disabled", false);
                                }else{
                                    $("#btnModificar").attr("disabled", true);
                                    $("#btnAnular").attr("disabled", true);
                                }
                            }
                            $("#tot").val(data[i + 9]);
                            $("#totx").val(parseFloat(data[i + 9]).toFixed(2));

                        }
                    }
                });

                $.getJSON('retornar_orden2.php?com=' + valor, function(data) {
                    var tama = data.length;
                    var descuento = 0;
                    var total = 0;
                    var su = 0;
                    var precio = 0;
                    var multi = 0;
                    var flotante = 0;
                    var resultado = 0;
                    var suma_total = 0;

                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 9) {
                            var datarow = {
                                cod_producto: data[i], 
                                codigo: data[i + 1],
                                detalle: data[i + 2], 
                                precio_u: data[i + 3], 
                                cantidad: data[i + 4]*$("#cantidad").val(), 
                                stock: data[i + 8],
                                unidad: data[i + 5],
                                unidadx: data[i + 6],  
                                total: data[i + 7]*$("#cantidad").val()
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
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


function flecha_siguiente() {
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data: "comprobante=" + $("#comprobante").val() + "&tabla=" + "ordenes_produccion" + "&id_tabla=" + "id_ordenes" + "&tipo=" + 2,
        success: function(data) {
            var val = data;
            if(val != "") {
                $("#comprobante").val(val);
                var valor = $("#comprobante").val();
                // llamar factura venta
                $("#btnGuardar").attr("disabled", true);
                $("#producto2").attr("disabled", true);
                $("#btnImprimir").attr("disabled", false);

                $("#estado h3").remove();
                
                $("#list").jqGrid("clearGridData", true);
                $("#tot").val("0.000");
                $("#totx").val("0.000");

                $.getJSON('retornar_orden.php?com=' + valor, function(data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 11) {
                            $("#id_receta2").val(data[i]);
                            $("#fecha_actual").val(data[i + 1]);
                            $("#digitador").val(data[i + 2]);
                            $("#costo_producto").val(data[i + 4]);
                            $("#producto2").val(data[i + 5]);
                            $("#cod_productos2").val(data[i + 6]);
                            $("#id_orden").val(data[i + 7]);
                            $("#cantidad").val(data[i + 8]);

                            if(data[i + 3 ] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulada"));
                                $("#estado h3").css("color","red");
                                $("#btnAnular").attr("disabled", "disabled");
                                $("#btnModificar").attr("disabled", true);
                            } else {
                                $("#estado h3").remove();
                                $("#btnAnular").attr("disabled", false);
                                $("#btnModificar").attr("disabled", false);
                                if(data[10] == 0){
                                    $("#btnModificar").attr("disabled", false);
                                    $("#btnAnular").attr("disabled", false);
                                }else{
                                    $("#btnModificar").attr("disabled", true);
                                    $("#btnAnular").attr("disabled", true);
                                }
                            }
                            $("#tot").val(data[i + 9]);
                            $("#totx").val(parseFloat(data[i + 9]).toFixed(2));

                        }
                    }
                });

                $.getJSON('retornar_orden2.php?com=' + valor, function(data) {
                    var tama = data.length;
                    var descuento = 0;
                    var total = 0;
                    var su = 0;
                    var precio = 0;
                    var multi = 0;
                    var flotante = 0;
                    var resultado = 0;
                    var suma_total = 0;

                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 9) {
                            var datarow = {
                                cod_producto: data[i], 
                                codigo: data[i + 1],
                                detalle: data[i + 2], 
                                precio_u: data[i + 3], 
                                cantidad: data[i + 4]*$("#cantidad").val(), 
                                stock: data[i + 8],
                                unidad: data[i + 5],
                                unidadx: data[i + 6],  
                                total: data[i + 7]*$("#cantidad").val()
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });
                // Fin
            } else {
                if($("#id_receta").val()!=""){
                    $("#comprobante").val($("#comprobante").val());
                }
                alertify.alert("No hay más registros superiores!!");
            }
        }
    });
} 

function limpiar_campo() {
    if($("#ruc_ci").val() == "") {
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

function limpiar_campo2() {
    if($("#nombre_cliente").val() == "") {
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

function limpiar_campo3() {
    if($("#codigo").val() == "") {
        $("#codigo_barras").val("");
        $("#cod_producto").val("");
        $("#producto").val("");
        $("#cantidad").val("");
        $("#p_venta").val("");
        $("#descuento").val("");
        $("#disponibles").val("");
        $("#iva_producto").val("");
        $("#carga_series").val("");
        $("#des").val("");
        $("#incluye").val("");
        $("#inventar").val("");
    }
}

function limpiar_campo4() {
    if($("#producto").val() == "") {
        $("#codigo_barras").val("");
        $("#cod_producto").val("");
        $("#codigo").val("");
        $("#cantidad").val("");
        $("#p_venta").val("");
        $("#descuento").val("");
        $("#disponibles").val("");
        $("#iva_producto").val("");
        $("#carga_series").val("");
        $("#des").val("");
        $("#incluye").val("");
        $("#inventar").val("");
    }
}

function limpiar_factura() {
    location.reload(); 
}

function anular_orden() {
    $("#clave_permiso").dialog("open");      
}

function validar_acceso() {
    if($("#clave").val() == "") {
        $("#clave").focus();
        alertify.alert("Ingrese la clave");
    } else {
        $.ajax({
            url: 'validar_acceso.php',
            type: 'POST',
            data: "clave=" + $("#clave").val(),
            success: function(data) {
                var val = data;
                if (val == 0) {
                    $("#clave").val("");
                    $("#clave").focus();
                    alertify.alert("Error... La clave es incorrecta ingrese nuevamente");
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
        url: "anular_orden.php",
        data: "comprobante=" + $("#id_orden").val() + "&fecha_anulacion=" + $("#fecha_actual").val(),
        success: function(data) {
            var val = data;
            if (val == 1) {
                    alertify.alert("Orden de Producción Suspendida Correctamente", function(){
                    location.reload();
                });
            }
        }
    });
}


function cancelar() {
    $("#seguro").dialog("close");   
    $("#clave_permiso").dialog("close");    
    $("#clave").val("");    
}

function cancelar_acceso() {
    $("#clave_permiso").dialog("close");     
    $("#clave").val("");
}

function numeros(e) { 
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla == 8) return true;
    patron = /\d/;
    te = String.fromCharCode(tecla);
    return patron.test(te);
}

function punto(e) {
    var key;
    if (window.event) {
        key = e.keyCode;
    } else if (e.which) {
        key = e.which;
    }

    if (key < 48 || key > 57) {
        if (key == 46 || key == 8) {
            return true;
        } else {
            return false;
        }
    }
    return true;   
}

var combo_1 = '';
var combo_2 = '';

function inicio() {

    $.ajax({
        type: "POST",
        url: "buscar_iva.php",
        data: "",
        success: function(data) {
            var  val = data;
            if (val != 1) {
                calculoIVA=val; 
            }
        }
    });

    alertify.set({ delay: 1000 });

    show();

    // cambiar idioma
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
    // fin
    
    function combo(tipo) {
        $.ajax({
            type: "POST",
            url: "buscar_producto9.php?tipo_precio="+ tipo,        
            success: function(resp) {             
                combo_1 = JSON.parse(resp);          
            }
        });    
        return combo_1;
    }

    function combo1(tipo) {
        $.ajax({
            type: "POST",
            url: "buscar_producto10.php?tipo_precio="+tipo,        
            success: function(resp) {             
                combo_2 = JSON.parse(resp);          
            }
        });    
        return combo_2;
    }
    
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
    $("#btnGenerar").click(function(e) {
        e.preventDefault();
    });


    $("#btncargar").on("click", abrirDialogo);
    $("#btnAgregar").on("click", agregar);
    $("#btnGuardarSeries").on("click", guardar_serie);
    $("#btnGuardar").on("click", guardar_orden);
    $("#btnModificar").on("click", modificar_orden);
    $("#btnNuevo").on("click", limpiar_factura);
    $("#btnAnular").on("click", anular_orden);
    $("#btnAceptar").on("click", aceptar);
    $("#btnSalir").on("click", cancelar);
    $("#btnAcceder").on("click", validar_acceso);
    $("#btnCancelar").on("click", cancelar_acceso);
    $("#btnAtras").on("click", flecha_atras);
    $("#btnAdelante").on("click", flecha_siguiente);
    $("#btnGenerar").on("click", generar);
    
    $("#ruc_ci").on("keyup", limpiar_campo);
    $("#codigo").on("keyup", limpiar_campo3);
    $("#producto").on("keyup", limpiar_campo4);
    
    $("#codigo").on("keypress", enter);
    $("#producto").on("keypress", enter);
    $("#cantidad").on("keypress", enter);
    $("#p_venta").on("keypress", enter1);
    $("#descuento").on("keypress", enter2);
    $("#ruc_ci").on("keypress", enter4);

    
    $("#btnAnular").attr("disabled", true);
    $("#btnGuardar").attr("disabled", true);
    $("#btnModificar").attr("disabled", true);
    $("#btnImprimir").attr("disabled", true);
 
    $("#ruc_ci").attr("maxlength", "13");
      
    $("#series").dialog(dialogo);
    $("#buscar_recetas").dialog(dialogo2);
    $("#buscar_proformas").dialog(dialogo5);
    $("#buscar_proformas_tecnico").dialog(dialogotecnico);
    $("#clave_permiso").dialog(dialogo3);
    $("#seguro").dialog(dialogo4);
    $("#buscar_notas_venta").dialog(dialogo6);
    $("#tipo_busqueda").dialog(dialogo7);



    $("#btnImprimir").click(function () {  
        if($("#id_orden").val()==""){
            alertify.alert("Orden de Producción no Creada");
        }else{
            window.open("../../reportes/orden_produccion.php?id="+$("#id_orden").val(),'_blank');
        }
    });

    $("#btnBuscar").click(function () {
        $("#buscar_recetas").dialog("open");   
    });

    // para precio
    $("#p_venta").on("keypress",punto);
    $("#precio").on("keypress",punto);
    $("#adelanto").on("keypress",punto);
    // FIN

    // buscar productos codigo barras
    $("#codigo_barras").change(function(e) {
        barras();
    });

    function barras() {
        var precio = "MINORISTA"; 
        var codigo = $("#codigo_barras").val();
        
        if (precio == "MINORISTA") {
            $.getJSON('search.php?codigo_barras=' + codigo + '&precio=' + precio, function(data) {
                var tama = data.length;
                if (tama != 0) {
                    for (var i = 0; i < tama; i = i + 10) {
                        $("#codigo").val(data[i]);
                        $("#producto").val(data[i + 1]);
                        $("#p_venta").val(data[i + 2]);
                        $("#descuento").attr("max",data[i + 7]);
                        $("#disponibles").val(data[i + 3]);
                        $("#iva_producto").val(data[i + 4]);
                        $("#carga_series").val(data[i + 5]);
                        $("#cod_producto").val(data[i + 6]);
                        $("#des").val(data[i + 7]);
                        $("#inventar").val(data[i + 8]);
                        $("#incluye").val(data[i + 9]);
                        $("#cantidad").val("1");
                        $("#cantidad").select();
                    }
                } else {
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
                    $("#incluye").val("");
                    alertify.error("Producto no ingresado");
                    $("#codigo_barras").val("");
                }
            });
        }   
    } 
    // fin

    // buscar productos codigo
    $("#codigo").keyup(function(e) {
        var precio = "MINORISTA"; 
        var res = combo(precio); 

        if (precio == "MINORISTA") {
            $("#codigo").autocomplete({
                source: function (req, response) {                    
                    var results = $.ui.autocomplete.filter(res, req.term);                    
                    response(results.slice(0, 20));
                },
                minLength: 1,
                focus: function(event, ui) {
                $("#codigo_barras").val(ui.item.codigo_barras);
                $("#codigo").val(ui.item.value);
                $("#producto").val(ui.item.producto);
                $("#p_venta").val(ui.item.p_venta);
                $("#descuento").attr("max",ui.item.descuento);
                $("#disponibles").val(ui.item.disponibles);
                $("#iva_producto").val(ui.item.iva_producto);
                $("#carga_series").val(ui.item.carga_series);
                $("#cod_producto").val(ui.item.cod_producto);
                $("#des").val(ui.item.des);
                $("#inventar").val(ui.item.inventar);
                $("#incluye").val(ui.item.incluye);
                $("#cantidad").val("1");
                return false;
                },
                select: function(event, ui) {
                $("#codigo_barras").val(ui.item.codigo_barras);
                $("#codigo").val(ui.item.value);
                $("#producto").val(ui.item.producto);
                $("#p_venta").val(ui.item.p_venta);
                $("#descuento").attr("max",ui.item.descuento);
                $("#disponibles").val(ui.item.disponibles);
                $("#iva_producto").val(ui.item.iva_producto);
                $("#carga_series").val(ui.item.carga_series);
                $("#cod_producto").val(ui.item.cod_producto);
                $("#des").val(ui.item.des);
                $("#inventar").val(ui.item.inventar);
                $("#incluye").val(ui.item.incluye);
                $("#cantidad").val("1");
                $("#cantidad").select();
                return false;
                }

                }).data("ui-autocomplete")._renderItem = function(ul, item) {
                return $("<li>")
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
            };
        }
    });
    // fin

    // busqueda productos articulos
    $("#producto").keyup(function(e) {
        var precio = "MINORISTA";
        var res = combo1(precio);

        if (precio == "MINORISTA") {
            $("#producto").autocomplete({
                source: function (req, response) {                    
                var results = $.ui.autocomplete.filter(res, req.term);                    
                    response(results.slice(0, 20));
                },
                minLength: 1,
                focus: function(event, ui) {
                $("#codigo_barras").val(ui.item.codigo_barras);
                $("#producto").val(ui.item.value);
                $("#codigo").val(ui.item.codigo);
                $("#p_venta").val(ui.item.p_venta);
                $("#descuento").attr("max",ui.item.descuento);
                $("#disponibles").val(ui.item.disponibles);
                $("#iva_producto").val(ui.item.iva_producto);
                $("#carga_series").val(ui.item.carga_series);
                $("#cod_producto").val(ui.item.cod_producto);
                $("#des").val(ui.item.des);
                $("#inventar").val(ui.item.inventar);
                $("#incluye").val(ui.item.incluye);
                $("#cantidad").val("1");
                return false;
                },
                select: function(event, ui) {
                $("#codigo_barras").val(ui.item.codigo_barras);
                $("#producto").val(ui.item.value);
                $("#codigo").val(ui.item.codigo);
                $("#p_venta").val(ui.item.p_venta);
                $("#descuento").attr("max",ui.item.descuento);
                $("#disponibles").val(ui.item.disponibles);
                $("#iva_producto").val(ui.item.iva_producto);
                $("#carga_series").val(ui.item.carga_series);
                $("#cod_producto").val(ui.item.cod_producto);
                $("#des").val(ui.item.des);
                $("#inventar").val(ui.item.inventar);
                $("#incluye").val(ui.item.incluye);
                $("#cantidad").val("1");
                $("#cantidad").select();
                return false;
                }

                }).data("ui-autocomplete")._renderItem = function(ul, item) {
                return $("<li>")
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
            };
        } else {
            if (precio == "MAYORISTA") {
                $("#producto").autocomplete({
                    source: function (req, response) {                    
                    var results = $.ui.autocomplete.filter(res, req.term);                    
                        response(results.slice(0, 20));
                    },
                    minLength: 1,
                    focus: function(event, ui) {
                    $("#codigo_barras").val(ui.item.codigo_barras);
                    $("#producto").val(ui.item.value);
                    $("#codigo").val(ui.item.codigo);
                    $("#p_venta").val(ui.item.p_venta);
                    $("#descuento").attr("max",ui.item.descuento);
                    $("#disponibles").val(ui.item.disponibles);
                    $("#iva_producto").val(ui.item.iva_producto);
                    $("#carga_series").val(ui.item.carga_series);
                    $("#cod_producto").val(ui.item.cod_producto);
                    $("#des").val(ui.item.des);
                    $("#inventar").val(ui.item.inventar);
                    $("#incluye").val(ui.item.incluye);
                    $("#cantidad").val("1");
                    return false;
                    },
                    select: function(event, ui) {
                    $("#codigo_barras").val(ui.item.codigo_barras);
                    $("#producto").val(ui.item.value);
                    $("#codigo").val(ui.item.codigo);
                    $("#p_venta").val(ui.item.p_venta);
                    $("#descuento").attr("max",ui.item.descuento);
                    $("#disponibles").val(ui.item.disponibles);
                    $("#iva_producto").val(ui.item.iva_producto);
                    $("#carga_series").val(ui.item.carga_series);
                    $("#cod_producto").val(ui.item.cod_producto);
                    $("#des").val(ui.item.des);
                    $("#inventar").val(ui.item.inventar);
                    $("#incluye").val(ui.item.incluye);
                    $("#cantidad").val("1");
                    $("#cantidad").select();
                    return false;
                    }
                    }).data("ui-autocomplete")._renderItem = function(ul, item) {
                    return $("<li>")
                    .append("<a>" + item.value + "</a>")
                    .appendTo(ul);
                };
            } else {
                if (precio == "NEGOCIO") {
                    $("#producto").autocomplete({
                        source: function (req, response) {                    
                        var results = $.ui.autocomplete.filter(res, req.term);                    
                            response(results.slice(0, 20));
                        },
                        minLength: 1,
                        focus: function(event, ui) {
                        $("#codigo_barras").val(ui.item.codigo_barras);
                        $("#producto").val(ui.item.value);
                        $("#codigo").val(ui.item.codigo);
                        $("#p_venta").val(ui.item.p_venta);
                        $("#descuento").attr("max",ui.item.descuento);
                        $("#disponibles").val(ui.item.disponibles);
                        $("#iva_producto").val(ui.item.iva_producto);
                        $("#carga_series").val(ui.item.carga_series);
                        $("#cod_producto").val(ui.item.cod_producto);
                        $("#des").val(ui.item.des);
                        $("#inventar").val(ui.item.inventar);
                        $("#incluye").val(ui.item.incluye);
                        $("#cantidad").val("1");
                        return false;
                        },
                        select: function(event, ui) {
                        $("#codigo_barras").val(ui.item.codigo_barras);
                        $("#producto").val(ui.item.value);
                        $("#codigo").val(ui.item.codigo);
                        $("#p_venta").val(ui.item.p_venta);
                        $("#descuento").attr("max",ui.item.descuento);
                        $("#disponibles").val(ui.item.disponibles);
                        $("#iva_producto").val(ui.item.iva_producto);
                        $("#carga_series").val(ui.item.carga_series);
                        $("#cod_producto").val(ui.item.cod_producto);
                        $("#des").val(ui.item.des);
                        $("#inventar").val(ui.item.inventar);
                        $("#incluye").val(ui.item.incluye);
                        $("#cantidad").val("1");
                        $("#cantidad").select();
                        return false;
                        }
                        }).data("ui-autocomplete")._renderItem = function(ul, item) {
                        return $("<li>")
                        .append("<a>" + item.value + "</a>")
                        .appendTo(ul);
                    };
                }
            } 
        }
    });
    // fin

    // busqueda productos articulos
    $("#producto2").keyup(function(e) {
        var precio = "MINORISTA";
        var res = combo1(precio);

        if (precio == "MINORISTA") {
            $("#producto2").autocomplete({
                source: function (req, response) {                    
                var results = $.ui.autocomplete.filter(res, req.term);                    
                    response(results.slice(0, 20));
                },
                minLength: 1,
                focus: function(event, ui) {
                    $("#producto2").val(ui.item.value);
                    $("#cod_productos2").val(ui.item.cod_producto);
                    $("#id_receta2").val(ui.item.id_receta);
                    $("#costo_producto").val(ui.item.costo_producto);
                return false;
                },
                select: function(event, ui) {
                    $("#producto2").val(ui.item.value);
                    $("#cod_productos2").val(ui.item.cod_producto);
                    $("#id_receta2").val(ui.item.id_receta);
                    $("#costo_producto").val(ui.item.costo_producto);
                return false;
                }

                }).data("ui-autocomplete")._renderItem = function(ul, item) {
                return $("<li>")
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
            };
        }
    });
    //fin2
    

    /////////////////////////////////
    $("#cantidad").validCampoFranz("0123456789");
    $("#descuento").validCampoFranz("0123456789");
    $("#num_factura").validCampoFranz("0123456789");
    $("#num_factura").attr("maxlength", "9");
    $("#ruc_ci").validCampoFranz("0123456789");
    /////////////////////////////////////


    $('.ui-spinner-button').click(function() {
        $(this).siblings('input').change();
    });

    
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

    // datos tabla
    var can;
    jQuery("#list").jqGrid({
        datatype: "local",
        colNames: [ 'ID', 'Código', 'Producto', 'P. Unitario', 'Cantidad Necesaria', 'Stock', 'Unidad', 'Unidadx', 'Total'],
        colModel: [
            {name: 'cod_producto', index: 'cod_producto', editable: false, search: false, hidden: true, editrules: {edithidden: false}, align: 'center', frozen: true, width: 50},
            {name: 'codigo', index: 'codigo', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'center', frozen: true, width: 100},
            {name: 'detalle', index: 'detalle', editable: false, frozen: true, editrules: {required: true}, align: 'center', width: 290},
            {name: 'precio_u', index: 'precio_u', editable: false, frozen: true, editrules: {required: true}, align: 'center', width: 80},
            {name: 'cantidad', index: 'cantidad', editable: false, frozen: true, editrules: {required: true}, align: 'center', width: 80},
            {name: 'stock', index: 'stock', hidden: false, editable: false, search: false, frozen: true, editrules: {required: true}, align: 'center', width: 80, editoptions:{maxlength: 10, size:15,dataInit: function(elem){$(elem).bind("keypress", function(e) {return punto(e)})}}}, 
            {name: 'unidad', index: 'unidad', hidden: false, editable: false, frozen: true, editrules: {required: true}, align: 'center', width: 90},
            {name: 'unidadx', index: 'unidadx', hidden: true, editable: false, frozen: true, editrules: {required: true}, align: 'center', width: 90},
            {name: 'total', index: 'total', editable: false, frozen: true, editrules: {required: true}, align: 'center', width: 80},
        ],
        rowNum: 30,
        width: null,
        height: 300,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager'),
        sortname: 'cod_producto',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'clientArray',
        shrinkToFit: true
  });

   // buscador ordenes de produccion
    jQuery("#list2").jqGrid({
        url: 'xmlBuscarOrden.php',
        datatype: 'xml',
        colNames: ['ID','PRODUCTO', 'COSTO TOTAL','FECHA'],
        colModel: [
            {name: 'id_orden', index: 'id_orden', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 50},
            {name: 'producto', index: 'producto', editable: false, search: true, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 150},
            {name: 'costo', index: 'costo', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 100},
            {name: 'fecha_actual', index: 'fecha_actual', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 100},
        ],
        rowNum: 30,
        width: 750,
        height:220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager2'),
        sortname: 'id_ordenes',
        sortorder: 'asc',
        viewrecords: true,              
        ondblClickRow: function(){
        var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
        jQuery('#list2').jqGrid('restoreRow', id);
        
        if (id) {
            var ret = jQuery("#list2").jqGrid('getRowData', id);
            var valor = ret.id_orden;

            /////////////agregregar datos factura////////
            $("#comprobante").val(valor);
                // llamar factura venta
            $("#btnGuardar").attr("disabled", true);
            $("#producto2").attr("disabled", true);
            $("#btnImprimir").attr("disabled", false);

                $("#estado h3").remove();
                
                $("#list").jqGrid("clearGridData", true);
                $("#tot").val("0.000");
                $("#totx").val("0.000");

                $.getJSON('retornar_orden.php?com=' + valor, function(data) {
                    var tama = data.length;
                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 11) {
                            $("#id_receta2").val(data[i]);
                            $("#fecha_actual").val(data[i + 1]);
                            $("#digitador").val(data[i + 2]);
                            $("#costo_producto").val(data[i + 4]);
                            $("#producto2").val(data[i + 5]);
                            $("#cod_productos2").val(data[i + 6]);
                            $("#id_orden").val(data[i + 7]);
                            $("#cantidad").val(data[i + 8]);

                            if(data[i + 3 ] == "Pasivo") {
                                $("#estado").append($("<h3>").text("Anulada"));
                                $("#estado h3").css("color","red");
                                $("#btnAnular").attr("disabled", "disabled");
                                $("#btnModificar").attr("disabled", true);
                            } else {
                                $("#estado h3").remove();
                                $("#btnAnular").attr("disabled", false);
                                $("#btnModificar").attr("disabled", false);
                                if(data[10] == 0){
                                    $("#btnModificar").attr("disabled", false);
                                    $("#btnAnular").attr("disabled", false);
                                }else{
                                    $("#btnModificar").attr("disabled", true);
                                    $("#btnAnular").attr("disabled", true);
                                }
                            }
                            $("#tot").val(data[i + 9]);
                            $("#totx").val(parseFloat(data[i + 9]).toFixed(2));

                        }
                    }
                });

                $.getJSON('retornar_orden2.php?com=' + valor, function(data) {
                    var tama = data.length;
                    var descuento = 0;
                    var total = 0;
                    var su = 0;
                    var precio = 0;
                    var multi = 0;
                    var flotante = 0;
                    var resultado = 0;
                    var suma_total = 0;

                    if (tama != 0) {
                        for (var i = 0; i < tama; i = i + 9) {
                            var datarow = {
                                cod_producto: data[i], 
                                codigo: data[i + 1],
                                detalle: data[i + 2], 
                                precio_u: data[i + 3], 
                                cantidad: data[i + 4]*$("#cantidad").val(), 
                                stock: data[i + 8],
                                unidad: data[i + 5],
                                unidadx: data[i + 6],  
                                total: data[i + 7]*$("#cantidad").val()
                            };
                            var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        }
                    }
                });
            $("#buscar_recetas").dialog("close");
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
            bottominfo: "Todos los campos son obligatorios"
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
        
// fin tabla

// buscador notas ventas
    jQuery("#list5").jqGrid({
    url: 'xmlBuscarNotaVenta.php',
    datatype: 'xml',
    colNames: ['ID','IDENTIFICACIÓN','CLIENTE','MONTO TOTAL','FECHA'],
    colModel: [
        {name: 'id_facturas_novalidas', index: 'id_facturas_novalidas', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 50},
        {name: 'identificacion', index: 'identificacion', editable: false, search: true, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 150},
        {name: 'nombres_cli', index: 'nombres_cli', editable: true, search: true, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 200},
        {name: 'total_venta', index: 'total_venta', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 100},
        {name: 'fecha_actual', index: 'fecha_actual', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 100},
    ],
    rowNum: 30,
    width: 750,
    height:220,
    sortable: true,
    rowList: [10, 20, 30],
    pager: jQuery('#pager5'),
    sortname: 'id_facturas_novalidas',
    sortorder: 'asc',
    viewrecords: true,              
    ondblClickRow: function(){
    var id = jQuery("#list5").jqGrid('getGridParam', 'selrow');
    jQuery('#list5').jqGrid('restoreRow', id);
    
    if (id) {
       var ret = jQuery("#list5").jqGrid('getRowData', id);
       var valor = ret.id_facturas_novalidas;
       
        // agregregar datos factura
        $("#comprobante").val(valor);
        $("#btnGuardar").attr("disabled", true);
        $("#btnModificar").attr("disabled", true);
        // $("#num_factura").attr("disabled", "disabled");
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
        
        $("#tipo_venta").val("NOTA");
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
        $("#total_p").val("0.000");
        $("#total_p2").val("0.000");
        $("#iva").val("0.000");
        $("#desc").val("0.000");
        $("#tot").val("0.000");
        $("#total_px").val("0.000");
        $("#total_p2x").val("0.000");
        $("#ivax").val("0.000");
        $("#descx").val("0.000");
        $("#totx").val("0.000");
        
        $.getJSON('retornar_nota_venta.php?com=' + valor, function(data) {
            var tama = data.length;
            if (tama != 0) {
                for (var i = 0; i < tama; i = i + 17) {
                    $("#fecha_actual").val(data[i]);
                    $("#hora_actual").val(data[i + 1 ]);
                    $("#digitador").val(data[i + 2 ] + " " + data[i + 3 ] );
                    $("#id_cliente").val(data[i + 4]);
                    $("#ruc_ci").val(data[i + 5]);
                    $("#nombre_cliente").val(data[i + 6]);
                    $("#direccion_cliente").val(data[i + 7]);
                    $("#telefono_cliente").val(data[i + 8]);
                    $("#correo").val(data[i + 9]);
                    $("#tipo_precio").val(data[i + 10]);

                    if(data[ i+ 11 ] == "Pasivo") {
                        $("#estado").append($("<h3>").text("Anulada"));
                        $("#estado h3").css("color","red");
                        $("#btnAnular").attr("disabled", "disabled");
                    } else { 
                        $("#estado h3").remove();
                        $("#btnAnular").attr("disabled", false);
                    }

                    $("#total_p").val(data[i + 12]);
                    $("#total_p2").val(data[i + 13]);
                    $("#iva").val(data[i + 14]);
                    $("#desc").val(data[i + 15]);
                    $("#tot").val(data[i + 16]);
                    $("#total_px").val(parseFloat(data[i + 12]).toFixed(2));
                    $("#total_p2x").val(parseFloat(data[i + 13]).toFixed(2));
                    $("#ivax").val(parseFloat(data[i + 14]).toFixed(2));
                    $("#descx").val(parseFloat(data[i + 15]));
                    $("#totx").val(parseFloat(data[i + 16]).toFixed(2));
                }
            }
        });
        // fin

        // llamar detalle facturas no validas
        $.getJSON('retornar_nota_venta2.php?com=' + valor, function(data) {
            var tama = data.length;
            if (tama !== 0) {
                for (var i = 0; i < tama; i = i + 9) {
                    var datarow = {
                        cod_producto: data[i], 
                        codigo: data[i + 1], 
                        detalle: data[i + 2], 
                        cantidad: data[i + 3], 
                        precio_u: data[i + 4], 
                        descuento: data[i + 5], 
                        total: data[i + 6],
                        precio_ux: parseFloat(data[i + 4]).toFixed(2), 
                        descuentox: parseFloat(data[i + 5]).toFixed(2), 
                        totalx: parseFloat(data[i + 6]).toFixed(2), 
                        iva: data[i + 7],
                        pendiente: data[i + 8]
                        };
                    var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                }
            }
        });
        // fin

        $("#buscar_notas_venta").dialog("close");
        $("#tipo_busqueda").dialog("close");
    } else {
      alertify.alert("Seleccione una Factura");
    }
}
    
    }).jqGrid('navGrid', '#pager5',
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
        bottominfo: "Todos los campos son obligatorios"
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
    
       jQuery("#list5").jqGrid('navButtonAdd', '#pager5', {caption: "Añadir",
       onClickButton: function() {
        var id = jQuery("#list5").jqGrid('getGridParam', 'selrow');
        jQuery('#list5').jqGrid('restoreRow', id);
        if (id) {
        var ret = jQuery("#list5").jqGrid('getRowData', id);
        var valor = ret.id_facturas_novalidas;

        // agregregar datos nota venta
        $("#comprobante").val(valor);
        $("#btnGuardar").attr("disabled", true);
        $("#btnModificar").attr("disabled", true);

        // $("#num_factura").attr("disabled", "disabled");
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

        $("#tipo_venta").val("NOTA");
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
        $("#total_p").val("0.000");
        $("#total_p2").val("0.000");
        $("#iva").val("0.000");
        $("#desc").val("0.000");
        $("#tot").val("0.000");
        $("#total_px").val("0.000");
        $("#total_p2x").val("0.000");
        $("#ivax").val("0.000");
        $("#descx").val("0.000");
        $("#totx").val("0.000");
                
        $.getJSON('retornar_nota_venta.php?com=' + valor, function(data) {
            var tama = data.length;
            if (tama != 0) {
                for (var i = 0; i < tama; i = i + 17) {
                $("#fecha_actual").val(data[i]);
                $("#hora_actual").val(data[i + 1 ]);
                $("#digitador").val(data[i + 2 ] + " " + data[i + 3 ] );
                $("#id_cliente").val(data[i + 4]);
                $("#ruc_ci").val(data[i + 5]);
                $("#nombre_cliente").val(data[i + 6]);
                $("#direccion_cliente").val(data[i + 7]);
                $("#telefono_cliente").val(data[i + 8]);
                $("#correo").val(data[i + 9]);

                $("#tipo_precio").val(data[i + 10]);
                if(data[ i+ 11 ] == "Pasivo"){
                    $("#estado").append($("<h3>").text("Anulada"));
                    $("#estado h3").css("color","red");
                    $("#btnAnular").attr("disabled", "disabled");
                } else {
                    $("#estado h3").remove();
                    $("#btnAnular").attr("disabled", false);
                }

                $("#total_p").val(data[i + 12]);
                $("#total_p2").val(data[i + 13]);
                $("#iva").val(data[i + 14]);
                $("#desc").val(data[i + 15]);
                $("#tot").val(data[i + 16]);
                $("#total_px").val(parseFloat(data[i + 12]).toFixed(2));
                $("#total_p2x").val(parseFloat(data[i + 13]).toFixed(2));
                $("#ivax").val(parseFloat(data[i + 14]).toFixed(2));
                $("#descx").val(parseFloat(data[i + 15]).toFixed(2));
                $("#totx").val(parseFloat(data[i + 16]).toFixed(2));
               }
            }
        });
        // fin 
    
        // llamar facturas no validas
        $.getJSON('retornar_nota_venta2.php?com=' + valor, function(data) {
            var tama = data.length;
            if (tama != 0) {
                 for (var i = 0; i < tama; i = i + 9) {
                      var datarow = {
                        cod_producto: data[i], 
                        codigo: data[i + 1], 
                        detalle: data[i + 2], 
                        cantidad: data[i + 3], 
                        precio_u: data[i + 4], 
                        descuento: data[i + 5], 
                        total: data[i + 6], 
                        precio_ux: parseFloat(data[i + 4]).toFixed(2), 
                        descuentox: parseFloat(data[i + 5]).toFixed(2), 
                        totalx: parseFloat(data[i + 6]).toFixed(2), 
                        iva: data[i + 7],
                        pendiente: data[i + 8]
                        };
                    var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                }
            }
        });
        $("#buscar_notas_venta").dialog("close");
        $("#tipo_busqueda").dialog("close");
        } else {
          alertify.alert("Seleccione una Factura");
        }
    }
});
// fin

////////////tabla series//////////////////////////////
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
    ///////////////////////////////////////////

    ////////////////////buscador proformas/////////////////////////
    jQuery("#list4").jqGrid({
    url: 'xmlBuscarProformas.php',
    datatype: 'xml',
    colNames: ['ID','IDENTFICACIÓN','CLIENTE','MONTO TOTAL','FECHA PROFORMA'],
    colModel: [
        {name: 'id_proforma', index: 'id_factura_venta', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 50},
        {name: 'identificacion', index: 'identificacion', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 80},
        {name: 'nombres_cli', index: 'nombres_cli', editable: true, search: true, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 300},
        {name: 'total_proforma', index: 'total_venta', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 80},
        {name: 'fecha_actual', index: 'fecha_actual', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 80},
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
           var subtotal0 = 0;
           var subtotal12 = 0;
           var iva12 = 0;
           var total_total = 0;
           var descu_total = 0; 
           var ret = jQuery("#list4").jqGrid('getRowData', id);
           var valor = ret.id_proforma;
        
           // llamado datos personales/////////////
           $("#proforma").val(valor);
           $.getJSON('retornar_proforma_clientes.php?id1=' + valor, function(data) {
           var tama2 = data.length;
            for (var i = 0; i < tama2; i = i + 8) {
                $("#id_cliente").val(data[i]);
                $("#ruc_ci").val(data[i + 1 ]);
                $("#nombre_cliente").val(data[i + 2]);
                $("#direccion_cliente").val(data[i + 3]);
                $("#telefono_cliente").val(data[i + 4]);
                $("#correo").val(data[i + 5]);
                $("#tipo_precio").val(data[i + 6]);
                $("#nombre_director").val(data[i + 7]);
            }
        });
        ////////////////////////////////////////////////////

        $.getJSON('retornar_proforma.php?id2=' + valor, function(data) {
            var tama = data.length;
            if (tama === 0) {
                alertify.alert("Error... La proforma no existe", function(){
                    location.reload();
                });
            } else {
                $("#list").jqGrid("clearGridData", true);
                var descuento = 0;
                var total = 0;
                var su = 0;
                var precio = 0;
                var multi = 0;
                var flotante = 0;
                var resultado = 0;

                for (var i = 0; i < tama; i = i + 10) {
                    var temp = 0;
                    var temp1 = 0;
                    if(parseInt(data[i + 3]) < 0){
                        temp = 0; 
                        temp1 = data[i + 4] ;
                    } else {
                        if(parseInt(data[i + 4]) > parseInt(data[i + 3])){
                            temp = data[i + 3]; 
                            temp1 = data[i + 4] - data[i + 3];
                        } else {
                            temp = data[i + 4];   
                            temp1 = 0;   
                        }
                    }

                    desc = data[i + 6];
                    precio = parseFloat(data[i + 5]);
                    multi = temp * parseFloat(data[i + 5]);
                    descuento = (multi * parseFloat(data[i + 6])) / 100;
                    flotante = parseFloat(descuento);
                    resultado = Math.round(flotante * Math.pow(10,2)) / Math.pow(10,2);
                    total = multi - resultado;

                    var datarow = {
                        cod_producto: data[i], 
                        codigo: data[i + 1], 
                        detalle: data[i + 2], 
                        cantidad: temp, 
                        precio_u: precio, 
                        descuento: desc,
                        cal_des: resultado, 
                        total: total, 
                        precio_ux: precio.toFixed(2), 
                        descuentox: parseFloat(desc).toFixed(2),
                        cal_desx: resultado.toFixed(2), 
                        totalx: total.toFixed(2), 
                        iva: data[i + 8], 
                        pendiente: temp1,
                        incluye: data[i + 9],
                    };
                    var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                    var ivas = data[i + 8];

                    var subtotal = 0;
                    var sub = 0;
                    var sub1 = 0;
                    var sub2 = 0;
                    var iva = 0;
                    var iva1 = 0;
                    var iva2 = 0;

                    var fil = jQuery("#list").jqGrid("getRowData");
                        for (var t = 0; t < fil.length; t++) {
                            var dd = fil[t];
                            if (dd['iva'] === "Si") {
                                if(dd['incluye'] == "No"){
                                    subtotal = dd['total'];
                                    sub1 = subtotal;
                                    iva1 = (sub1 * 12) / 100;                                          

                                    subtotal0 = parseFloat(subtotal0) + 0;
                                    subtotal12 = parseFloat(subtotal12) + parseFloat(sub1);
                                    descu_total = parseFloat(descu_total) + dd['cal_des'];
                                    iva12 = parseFloat(iva12) + parseFloat(iva1);
                                
                                    subtotal0 = parseFloat(subtotal0);
                                    subtotal12 = parseFloat(subtotal12);
                                    iva12 = parseFloat(iva12);
                                    descu_total = parseFloat(descu_total);
                                } else {
                                    if(dd['incluye'] == "Si"){
                                        subtotal = dd['total'];
                                        sub2 = subtotal / ((calculoIVA/100)+1);
                                        iva2 = sub2 * (calculoIVA/100);

                                        subtotal0 = parseFloat(subtotal0) + 0;
                                        subtotal12 = parseFloat(subtotal12) + parseFloat(sub2);
                                        iva12 = parseFloat(iva12) + parseFloat(iva2);
                                        descu_total = parseFloat(descu_total) + dd['cal_des'];

                                        subtotal0 = parseFloat(subtotal0);
                                        subtotal12 = parseFloat(subtotal12);
                                        iva12 = parseFloat(iva12);
                                        descu_total = parseFloat(descu_total);
                                    }
                                }
                            } else {
                                if (dd['iva'] === "No") {                                               
                                    subtotal = dd['total'];
                                    sub = subtotal;

                                    subtotal0 = parseFloat(subtotal0) + parseFloat(sub);
                                    subtotal12 = parseFloat(subtotal12) + 0;
                                    iva12 = parseFloat(iva12) + 0;
                                    descu_total = parseFloat(descu_total) + dd['cal_des'];
                                    
                                    subtotal0 = parseFloat(subtotal0);
                                    subtotal12 = parseFloat(subtotal12);
                                    iva12 = parseFloat(iva12);
                                    descu_total = parseFloat(descu_total);                                  
                                }       
                            }
                        }                                                          
                        total_total = parseFloat(total_total) + (parseFloat(subtotal0) + parseFloat(subtotal12) + parseFloat(iva12));
                        total_total = parseFloat(total_total);

                        $("#total_p").val(subtotal0);
                        $("#total_p2").val(subtotal12);
                        $("#iva").val(iva12);
                        $("#desc").val(descu_total);
                        $("#tot").val(total_total);
                        $("#total_px").val(subtotal0.toFixed(2));
                        $("#total_p2x").val(subtotal12.toFixed(2));
                        $("#ivax").val(iva12.toFixed(2));
                        $("#descx").val(descu_total.toFixed(2));
                        $("#totx").val(total_total.toFixed(2));
                        $("#codigo_barras").focus();

                }
            }
        });
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
            bottominfo: "Todos los campos son obligatorios"
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
        // $("#num_factura").attr("disabled", "disabled");
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
        $("#total_p").val("0.000");
        $("#total_p2").val("0.000");
        $("#iva").val("0.000");
        $("#desc").val("0.000");
        $("#tot").val("0.000");
        $("#total_px").val("0.000");
        $("#total_p2x").val("0.000");
        $("#ivax").val("0.000");
        $("#descx").val("0.000");
        $("#totx").val("0.000");
                
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
                if(data[ i+ 13 ] == "Pasivo") {
                    $("#estado").append($("<h3>").text("Anulada"));
                    $("#estado h3").css("color","red");
                    $("#btnAnular").attr("disabled", "disabled");
                } else {
                    $("#estado h3").remove();
                    $("#btnAnular").attr("disabled", "disabled");
                    $("#btnAnular").attr("disabled", false);
                }

                $("#total_p").val(data[i + 14]);
                $("#total_p2").val(data[i + 15]);
                $("#iva").val(data[i + 16]);
                $("#desc").val(data[i + 17]);
                $("#tot").val(data[i + 18]);
                $("#total_px").val(parseFloat(data[i + 14]).toFixed(2));
                $("#total_p2x").val(parseFloat(data[i + 15]).toFixed(2));
                $("#ivax").val(parseFloat(data[i + 16]).toFixed(2));
                $("#descx").val(parseFloat(data[i + 17]).toFixed(2));
                $("#totx").val(parseFloat(data[i + 18]).toFixed(2));
               }
            }
        });
        ///////////////////////////////////////////////////   
    
        ///////////////////llamar facturas flechas segunda parte/////
        $.getJSON('../procesos/retornar_factura_venta_credito.php?com=' + valor, function(data) {
            var tama = data.length;
            if (tama !== 0) {
                for (var i = 0; i < tama; i = i + 4) {
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
                 for (var i = 0; i < tama; i = i + 9) {
                    var datarow = {
                        cod_producto: data[i], 
                        codigo: data[i + 1], 
                        detalle: data[i + 2], 
                        cantidad: data[i + 3], 
                        precio_u: data[i + 4], 
                        descuento: data[i + 5], 
                        total: data[i + 6], 
                        precio_ux: parseFloat(data[i + 4]).toFixed(2), 
                        descuentox: parseFloat(data[i + 5]).toFixed(2), 
                        totalx: parseFloat(data[i + 6]).toFixed(2),
                        iva: data[i + 7],
                        pendiente: data[i + 8]
                        };
                    var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                }
            }
        });
            $("#buscar_recetas").dialog("close");
        } else {
          alertify.alert("Seleccione una Factura");
        }
    }
});

////////////////////buscador proformas tecnico/////////////////////////
    jQuery("#list6").jqGrid({
        url: 'xmlBuscarProformasTecnico.php',
        datatype: 'xml',
        colNames: ['ID','IDENTFICACIÓN','CLIENTE','MONTO TOTAL','FECHA PROFORMA'],
        colModel: [
            {name: 'id_proforma', index: 'id_factura_venta', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 50},
            {name: 'identificacion', index: 'identificacion', editable: false, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 80},
            {name: 'nombres_cli', index: 'nombres_cli', editable: true, search: true, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 300},
            {name: 'total_proforma', index: 'total_venta', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 80},
            {name: 'fecha_actual', index: 'fecha_actual', editable: true, search: false, hidden: false, editrules: {edithidden: false}, align: 'center',frozen: true, width: 80},
        ],
            rowNum: 30,
            width: 750,
            height:220,
            sortable: true,
            rowList: [10, 20, 30],
            pager: jQuery('#pager6'),
            sortname: 'id_proforma',
            sortorder: 'asc',
            viewrecords: true,              
            ondblClickRow: function(){
            var id = jQuery("#list6").jqGrid('getGridParam', 'selrow');
            jQuery('#list6').jqGrid('restoreRow', id);
            
            if (id) {
               var subtotal0 = 0;
               var subtotal12 = 0;
               var iva12 = 0;
               var total_total = 0;
               var descu_total = 0; 
               var ret = jQuery("#list6").jqGrid('getRowData', id);
               var valor = ret.id_proforma;
            
               // llamado datos personales/////////////
               $("#proforma").val(valor);
               $.getJSON('retornar_proforma_clientes_tecnico.php?id1=' + valor, function(data) {
               var tama2 = data.length;
                for (var i = 0; i < tama2; i = i + 8) {
                    $("#id_cliente").val(data[i]);
                    $("#ruc_ci").val(data[i + 1 ]);
                    $("#nombre_cliente").val(data[i + 2]);
                    $("#direccion_cliente").val(data[i + 3]);
                    $("#telefono_cliente").val(data[i + 4]);
                    $("#correo").val(data[i + 5]);
                    $("#tipo_precio").val(data[i + 6]);
                    $("#nombre_director").val(data[i + 7]);
                }
            });
            ////////////////////////////////////////////////////

            $.getJSON('retornar_proforma_tecnico.php?id2=' + valor, function(data) {
                var tama = data.length;
                if (tama === 0) {
                    alertify.alert("Error... La proforma no existe", function(){
                        location.reload();
                    });
                } else {
                    $("#list").jqGrid("clearGridData", true);
                    var descuento = 0;
                    var total = 0;
                    var su = 0;
                    var precio = 0;
                    var multi = 0;
                    var flotante = 0;
                    var resultado = 0;

                    for (var i = 0; i < tama; i = i + 10) {
                        var temp = 0;
                        var temp1 = 0;
                        if(parseInt(data[i + 3]) < 0){
                            temp = 0; 
                            temp1 = data[i + 4] ;
                        } else {
                            if(parseInt(data[i + 4]) > parseInt(data[i + 3])){
                                temp = data[i + 3]; 
                                temp1 = data[i + 4] - data[i + 3];
                            } else {
                                temp = data[i + 4];   
                                temp1 = 0;   
                            }
                        }

                        desc = data[i + 6];
                        precio = parseFloat(data[i + 5]);
                        multi = temp * parseFloat(data[i + 5]);
                        descuento = (multi * parseFloat(data[i + 6])) / 100;
                        flotante = parseFloat(descuento);
                        resultado = Math.round(flotante * Math.pow(10,2)) / Math.pow(10,2);
                        total = multi - resultado;

                        var datarow = {
                            cod_producto: data[i], 
                            codigo: data[i + 1], 
                            detalle: data[i + 2], 
                            cantidad: temp, 
                            precio_u: precio, 
                            descuento: desc,
                            cal_des: resultado, 
                            total: total, 
                            precio_ux: precio.toFixed(2), 
                            descuentox: parseFloat(desc).toFixed(2),
                            cal_desx: resultado.toFixed(2), 
                            totalx: total.toFixed(2), 
                            iva: data[i + 8], 
                            pendiente: temp1,
                            incluye: data[i + 9],
                        };
                        var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                        var ivas = data[i + 8];

                        var subtotal = 0;
                        var sub = 0;
                        var sub1 = 0;
                        var sub2 = 0;
                        var iva = 0;
                        var iva1 = 0;
                        var iva2 = 0;

                        var fil = jQuery("#list").jqGrid("getRowData");
                            for (var t = 0; t < fil.length; t++) {
                                var dd = fil[t];
                                if (dd['iva'] === "Si") {
                                    if(dd['incluye'] == "No"){
                                        subtotal = dd['total'];
                                        sub1 = subtotal;
                                        iva1 = (sub1 * 12) / 100;                                          

                                        subtotal0 = parseFloat(subtotal0) + 0;
                                        subtotal12 = parseFloat(subtotal12) + parseFloat(sub1);
                                        descu_total = parseFloat(descu_total) + dd['cal_des'];
                                        iva12 = parseFloat(iva12) + parseFloat(iva1);
                                    
                                        subtotal0 = parseFloat(subtotal0);
                                        subtotal12 = parseFloat(subtotal12);
                                        iva12 = parseFloat(iva12);
                                        descu_total = parseFloat(descu_total);
                                    } else {
                                        if(dd['incluye'] == "Si"){
                                            subtotal = dd['total'];
                                            sub2 = subtotal / ((calculoIVA/100)+1);
                                            iva2 = sub2 * (calculoIVA/100);

                                            subtotal0 = parseFloat(subtotal0) + 0;
                                            subtotal12 = parseFloat(subtotal12) + parseFloat(sub2);
                                            iva12 = parseFloat(iva12) + parseFloat(iva2);
                                            descu_total = parseFloat(descu_total) + dd['cal_des'];

                                            subtotal0 = parseFloat(subtotal0);
                                            subtotal12 = parseFloat(subtotal12);
                                            iva12 = parseFloat(iva12);
                                            descu_total = parseFloat(descu_total);
                                        }
                                    }
                                } else {
                                    if (dd['iva'] === "No") {                                               
                                        subtotal = dd['total'];
                                        sub = subtotal;

                                        subtotal0 = parseFloat(subtotal0) + parseFloat(sub);
                                        subtotal12 = parseFloat(subtotal12) + 0;
                                        iva12 = parseFloat(iva12) + 0;
                                        descu_total = parseFloat(descu_total) + dd['cal_des'];
                                        
                                        subtotal0 = parseFloat(subtotal0);
                                        subtotal12 = parseFloat(subtotal12);
                                        iva12 = parseFloat(iva12);
                                        descu_total = parseFloat(descu_total);                                  
                                    }       
                                }
                            }                                                          
                            total_total = parseFloat(total_total) + (parseFloat(subtotal0) + parseFloat(subtotal12) + parseFloat(iva12));
                            total_total = parseFloat(total_total);

                            $("#total_p").val(subtotal0);
                            $("#total_p2").val(subtotal12);
                            $("#iva").val(iva12);
                            $("#desc").val(descu_total);
                            $("#tot").val(total_total);
                            $("#total_px").val(subtotal0.toFixed(2));
                            $("#total_p2x").val(subtotal12.toFixed(2));
                            $("#ivax").val(iva12.toFixed(2));
                            $("#descx").val(descu_total.toFixed(2));
                            $("#totx").val(total_total.toFixed(2));
                            $("#codigo_barras").focus();

                    }
                }
            });
            $("#buscar_proformas_tecnico").dialog("close");
            } else {
              alertify.alert("Seleccione una Factura");
            }
        }
            
            }).jqGrid('navGrid', '#pager6',
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
                bottominfo: "Todos los campos son obligatorios"
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
        
        jQuery("#list6").jqGrid('navButtonAdd', '#pager6', {caption: "Añadir",
        onClickButton: function() {
            var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
            jQuery('#list6').jqGrid('restoreRow', id);
            if (id) {
            var ret = jQuery("#list2").jqGrid('getRowData', id);
            var valor = ret.id_factura_venta;
            /////////////agregregar datos factura////////
            $("#comprobante").val(valor);
            $("#btnGuardar").attr("disabled", true);
            // $("#num_factura").attr("disabled", "disabled");
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
            $("#total_p").val("0.000");
            $("#total_p2").val("0.000");
            $("#iva").val("0.000");
            $("#desc").val("0.000");
            $("#tot").val("0.000");
            $("#total_px").val("0.000");
            $("#total_p2x").val("0.000");
            $("#ivax").val("0.000");
            $("#descx").val("0.000");
            $("#totx").val("0.000");
                    
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
                    if(data[ i+ 13 ] == "Pasivo") {
                        $("#estado").append($("<h3>").text("Anulada"));
                        $("#estado h3").css("color","red");
                        $("#btnAnular").attr("disabled", "disabled");
                    } else {
                        $("#estado h3").remove();
                        $("#btnAnular").attr("disabled", "disabled");
                        $("#btnAnular").attr("disabled", false);
                    }

                    $("#total_p").val(data[i + 14]);
                    $("#total_p2").val(data[i + 15]);
                    $("#iva").val(data[i + 16]);
                    $("#desc").val(data[i + 17]);
                    $("#tot").val(data[i + 18]);
                    $("#total_px").val(parseFloat(data[i + 14]).toFixed(2));
                    $("#total_p2x").val(parseFloat(data[i + 15]).toFixed(2));
                    $("#ivax").val(parseFloat(data[i + 16]).toFixed(2));
                    $("#descx").val(parseFloat(data[i + 17]).toFixed(2));
                    $("#totx").val(parseFloat(data[i + 18]).toFixed(2));
                   }
                }
            });
            ///////////////////////////////////////////////////   
        
            ///////////////////llamar facturas flechas segunda parte/////
            $.getJSON('../procesos/retornar_factura_venta_credito.php?com=' + valor, function(data) {
                var tama = data.length;
                if (tama !== 0) {
                    for (var i = 0; i < tama; i = i + 4) {
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
                     for (var i = 0; i < tama; i = i + 9) {
                        var datarow = {
                            cod_producto: data[i], 
                            codigo: data[i + 1], 
                            detalle: data[i + 2], 
                            cantidad: data[i + 3], 
                            precio_u: data[i + 4], 
                            descuento: data[i + 5], 
                            total: data[i + 6], 
                            precio_ux: parseFloat(data[i + 4]).toFixed(2), 
                            descuentox: parseFloat(data[i + 5]).toFixed(2), 
                            totalx: parseFloat(data[i + 6]).toFixed(2),
                            iva: data[i + 7],
                            pendiente: data[i + 8]
                            };
                        var su = jQuery("#list").jqGrid('addRowData', data[i], datarow);
                    }
                }
            });
                $("#buscar_recetas").dialog("close");
            } else {
              alertify.alert("Seleccione una Factura");
            }
        }
    });

    jQuery(window).bind('resize', function () {
    jQuery("#list").setGridWidth(jQuery('#grid_container').width(), true);
    }).trigger('resize');

    $("#codigo_barras").focus();
}

