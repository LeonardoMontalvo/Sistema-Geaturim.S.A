$(document).on("ready", inicio);
function evento(e) {
    e.preventDefault();
}

function openPDF() {
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
var boton = 0;
var dialogos = {
    autoOpen: false,
    resizable: false,
    width: 860,
    height: 350,
    modal: true
};

var dialogo = {
    autoOpen: false,
    resizable: false,
    width: 860,
    height: 350,
    modal: true
};

var dialogoTipo_compro = {
    autoOpen: false,
    resizable: false,
    width: 860,
    height: 350,
    modal: true
};

var dialogoTipo_emision = {
    autoOpen: false,
    resizable: false,
    width: 860,
    height: 350,
    modal: true
};

var dialogoTipo_impuesto = {
    autoOpen: false,
    resizable: false,
    width: 860,
    height: 350,
    modal: true
};

var dialogoforma_pagos = {
    autoOpen: false,
    resizable: false,
    width: 860,
    height: 350,
    modal: true
};

var dialogotarifa_impuesto = {
    autoOpen: false,
    resizable: false,
    width: 860,
    height: 350,
    modal: true
};

var dialogo3 = {
    autoOpen: false,
    resizable: false,
    width: 400,
    height: 210,
    modal: true,
    position: "center",
    show: "explode",
    hide: "blind"
};

var dialogo4 = {
    autoOpen: false,
    resizable: false,
    width: 400,
    height: 150,
    modal: true,
    position: "center",
    show: "explode",
    hide: "blind"
};

var dialogo5 = {
    autoOpen: false,
    resizable: false,
    width: 500,
    height: 400,
    modal: true,
    position: "center",
    show: "explode",
    hide: "blind"
};

var dialogo_cuenta = {
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 400,
    modal: true,
    position: "center",
    show: "explode",
    hide: "blind"
};

var dialogo_codigo = {
    autoOpen: false,
    resizable: false,
    width: 440,
    height: 180,
    modal: true,
    position: "center",
    show: "explode",
    hide: "blind"
};

var dialogo_empresa = {
    autoOpen: false,
    resizable: false,
    width: 440,
    height: 180,
    modal: true,
    position: "center",
    show: "explode",
    hide: "blind"
};
function eliminar_ambiente() {
    if ($("#id_ambiente").val() === "") {
        alertify.error("Seleccione un tipo de Ambiente");
    } else {
        $("#clave_permiso").dialog("open");
    }
}
function eliminar_tipo_emision() {
    if ($("#id_temision").val() === "") {
        alertify.error("Seleccione un tipo de Ambiente");
    } else {
        $("#tipo_emision").dialog("open");
    }
}
function eliminar_Forma_pagos() {
    if ($("#id_form_pagos").val() === "") {
        alertify.error("Seleccione una Forma de pago");
    } else {
        $("#tipo_emision").dialog("open");
    }
}
function nuevo_cliente() {
    location.reload();
}
function abrirCuenta() {
    $("#cuentas").dialog("open");
}

function abrirDialogo() {
    $("#ambientes").dialog("open");
}
function abrirDialogo_tipo_compro() {
    $("#tipo_comprobante").dialog("open");
}

function abrirDialogo_tipo_emision() {
    $("#tipo_emision").dialog("open");
}

function abrirDialogo_tipo_impuesto() {

    $("#tipo_impuestoList").dialog("open");
}
function abrirDialogo_forma_pagos() {
    $("#tipo_forma_pagos").dialog("open");
}
function abrirDialogo_tarifa_impuesto() {
    $("#tipo_tarifa_impuesto").dialog("open");
}
function confirmar() {
    if ($("#nombre").val() == "") {
        alertify.alert("Por favor ingrese el nombre de la empresa", function () {
            $("#nombre").focus();
        });
    } else {
        if ($("#representante").val() == "") {
            alertify.alert("Por favor ingrese un representante", function () {
                $("#representante").focus();
            });
        } else {
            if ($("#ruc").val() == "") {
                alertify.alert("Por favor ingrese el ruc de la empresa", function () {
                    $("#ruc").focus();
                });
            } else {
                if ($("#direccion").val() == "") {
                    alertify.alert("Por favor ingrese la dirección de la empresa", function () {
                        $("#direccion").focus();
                    });
                } else {
                    if ($("#telefono").val() == "") {
                        alertify.alert("Por favor ingrese un número de teléfono", function () {
                            $("#telefono").focus();
                        });
                    } else {
                        if ($("#celular").val() == "") {
                            alertify.alert("Por favor ingrese un número celular", function () {
                                $("#celular").focus();
                            });
                        } else {
                            if ($("#pais").val() == "") {
                                alertify.alert("Por favor ingrese su país", function () {
                                    $("#pais").focus();
                                });

                            } else {
                                if ($("#ciudad").val() == "") {
                                    alertify.alert("Por favor ingrese su país", function () {
                                        //$("#ciudad").focus();
                                    });

                                } else {
                                    $("#clave_permiso").dialog("open");
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
function modificar_ambiente() {
    if ($("#tipo_ambiente").val() === "") {
        $("#tipo_ambiente").focus();
        alertify.error("Ingrese");
    } else {
        if ($("#codigo_ambi").val() === "") {
            $("#codigo_ambi").focus();
            alertify.error("Ingrese");
        } else {
            $.ajax({
                type: "POST",
                url: "../ambiente/modificar_ambiente.php",
                data: "nombre_ambi=" + $("#tipo_ambiente").val() + "&codigo_ambi=" + $("#codigo_ambi").val() + "&id_ambi=" + $("#id_ambi").val() + "&estado_tipo_ambiente=" + $("#estado_tipo_ambiente").val(),
                success: function (data) {
                    var val = data;

                    if (val == 1) {
                        alertify.success('Datos Agregados Modificados Correctamente');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                }
            });
        }
    }

}

function modificar_tipo_compro() {
    $.ajax({
        type: "POST",
        url: "../tipo_comprobante/modificar_tipo_compro.php",
        data: "descripcion=" + $("#nombre_tipo_compro").val() + "&abreviatura=" + $("#abreviatura_tipo_compro").val() + "&codigo=" + $("#codigo_tipo_compro").val() + "&id_tipo_comprobante=" + $("#id_tipo_comprobante").val(),
        success: function (data) {
            var val = data;
            if (val == 1) {
                alertify.alert("Los datos se han Modificado Correctamente", function () {
                    location.reload();
                })
            } else {
                alertify.alert("Error en el proceso, revise los datos por favor");
            }
        }
    });
}

function modificar_tipo_emision() {
    $.ajax({
        type: "POST",
        url: "../tipo_emision/modificar_tipo_emision.php",
        data: "nombre_temision=" + $("#nombre_tipo_emision").val() + "&codigo_temision=" + $("#codigo_tipo_emision").val() + "&id_temision=" + $("#id_temision").val() + "&estado_tipo_emision=" + $("#estado_tipo_emision").val(),
        success: function (data) {
            var val = data;
            if (val == 1) {
                alertify.alert("Los datos se han Modificado Correctamente", function () {
                    location.reload();
                })
            } else {
                alertify.alert("Error en el proceso, revise los datos por favor" + data);
            }
        }
    });
}

function modificar_tipo_impuesto() {
    $.ajax({
        type: "POST",
        url: "../tipo_impuesto/modificar_tipo_impuesto.php",
        data: "nombre_timpu=" + $("#nombre_timpu").val() + "&codigo_timpu=" + $("#codigo_timpu").val() + "&id_timpu=" + $("#id_timpu").val(),
        success: function (data) {
            var val = data;
            if (val == 1) {
                alertify.alert("Los datos se han Modificado Correctamente", function () {
                    location.reload();
                })
            } else {
                alertify.alert("Error en el proceso, revise los datos por favor");
            }
        }
    });
}

function modificar_Forma_pagos() {
    $.ajax({
        type: "POST",
        url: "../forma_pagos/modificar_forma_pagos.php",
        data: "moneda_form_pagos=" + $("#moneda_form_pagos").val() + "&tipo_form_pagos=" + $("#tipo_form_pagos").val() + "&id_form_pagos=" + $("#id_form_pagos").val(),
        success: function (data) {
            var val = data;
            if (val == 1) {
                alertify.alert("Los datos se han Modificado Correctamente", function () {
                    location.reload();
                })
            } else {
                alertify.alert("Error en el proceso, revise los datos por favor");
            }
        }
    });
}

function modificar_tarifa_impuesto() {
    $.ajax({
        type: "POST",
        url: "../tarifa_impuesto/modificar_tarifa_impuesto.php",
        data: "id_timpu=" + $("#tipo_impuesto").val() + "&codigo_taimpuesto=" + $("#codigo_tarifa_impuesto").val() + "&nombre_taimpuesto=" + $("#nombre_tarifa_impuesto").val() + "&descripcion_taimpuesto=" + $("#descripcion_tarifa_impuesto").val() + "&id_taimpuesto=" + $("#id_taimpuesto").val(),
        success: function (data) {
            var val = data;
            if (val == 1) {
                alertify.alert("Los datos se han Modificado Correctamente", function () {
                    location.reload();
                })
            } else {
                alertify.alert("Error en el proceso, revise los datos por favor");
            }
        }
    });
}

function validar_acceso() {
    $.ajax({
        url: '../../procesos/validar_acceso.php',
        type: 'POST',
        data: "clave=" + $("#clave").val(),
        success: function (data) {
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

function aceptar() {
    $.ajax({
        type: "POST",
        url: "guardar_parametros.php",
        data: "ivacompra=" + $("#idIvaC").val() + "&ivaventa=" + $("#idIvaV").val() + "&cgeneral=" + $("#idCajaGeneral").val() + "&cchica=" + $("#idCajaChica").val() + "&ccobrar=" + $("#idCxc").val() + "&cpagar=" + $("#idCxp").val() + "&dcobrar=" + $("#idDxc").val() + "&valoriva=" + $("#valorIva").val() + "&mercaderia=" + $("#mercaderia").val(),
        success: function (data) {
            var val = data;
            if (val == 1) {
                alertify.success('Datos Guardados Correctamente');
                setTimeout(function () {
                    location.reload();
                }, 1000);
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

function modificar() {
    datos = {
        nombre: ($("#nombre").val()).toUpperCase(),
        representante: ($("#representante").val()).toUpperCase(),
        ruc: $("#ruc").val(), direccion: ($("#direccion").val()).toUpperCase(),
        telefono: $("#telefono").val(), celular: $("#celular").val(),
        fax: $("#fax").val(), pais: ($("#pais").val()).toUpperCase(),
        ciudad: ($("#ciudad").val()).toUpperCase(), email: $("#email").val(),
        pagina: $("#pagina").val(), descripcion: ($("#descripcion").val()).toUpperCase(),
        num_items: ($("#num_items").val()).toUpperCase(), nombre_comercial: ($("#nombre_comercial").val()).toUpperCase(),
        obligacion: ($("#obligacion").val()).toUpperCase(), contribuyente_espe: ($("#contribuyente_espe").val()).toUpperCase(),
        token: $("#token").val(), clave: $("#clave").val(), establecimiento: ($("#establecimiento").val()).toUpperCase(),
        punto_emision: ($("#punto_emision").val()).toUpperCase(), porcentaje_tarjeta: ($("#porcen_tc").val()).toUpperCase()
    }
    $.ajax({
        type: "POST",
        url: "modificar_empresa.php",
        data: datos,
        success: function (data) {
            var val = data;
            if (val == 1) {
                alertify.alert("Los datos se han Modificado Correctamente", function () {
                    location.reload();
                })
            } else {
                alertify.alert("Error en el proceso, revise los datos por favor");
            }
        }
    });
}

function guardar_ambiente() {

    if ($("#tipo_ambiente").val() === "") {
        $("#tipo_ambiente").focus();
        alertify.error("Ingrese");
    } else {
        if ($("#codigo_ambi").val() === "") {
            $("#codigo_ambi").focus();
            alertify.error("Ingrese");
        } else {
            $.ajax({
                type: "POST",
                url: "../ambiente/guardar_ambiente.php",
                data: "nombre_ambi=" + $("#tipo_ambiente").val() + "&codigo_ambi=" + $("#codigo_ambi").val() + "&estado_tipo_ambiente=" + $("#estado_tipo_ambiente").val(),
                success: function (data) {
                    var val = data;

                    if (val == 1) {
                        alertify.success('Datos Agregados Correctamente');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                }
            });
        }
    }

}

function guardar_tipo_compro() {
    if ($("#nombre_tipo_compro").val() === "") {
        $("#nombre_tipo_compro").focus();
        alertify.error("Ingrese");
    } else {
        if ($("#abreviatura_tipo_compro").val() === "") {
            $("#abreviatura_tipo_compro").focus();
            alertify.error("Ingrese");
        } else {
            if ($("#codigo_tipo_compro").val() === "") {
                $("#codigo_tipo_compro").focus();
                alertify.error("Ingrese");
            } else {
                $.ajax({
                    type: "POST",
                    url: "../tipo_comprobante/guardar_tipo_compro.php",
                    data: "descripcion=" + $("#nombre_tipo_compro").val() + "&abreviatura=" + $("#abreviatura_tipo_compro").val() + "&codigo=" + $("#codigo_tipo_compro").val(),
                    success: function (data) {
                        var val = data;

                        if (val == 1) {
                            alertify.success('Datos Agregados Correctamente');
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                        }
                    }
                });
            }
        }
    }
}

function guardar_tipo_emision() {
    if ($("#nombre_tipo_emision").val() === "") {
        $("#nombre_tipo_emision").focus();
        alertify.error("Ingrese");
    } else {
        if ($("#codigo_tipo_emision").val() === "") {
            $("#codigo_tipo_emision").focus();
            alertify.error("Ingrese");
        } else {
            $.ajax({
                type: "POST",
                url: "../tipo_emision/guardar_tipo_emision.php",
                data: "nombre_temision=" + $("#nombre_tipo_emision").val() + "&codigo_temision=" + $("#codigo_tipo_emision").val() + "&estado_tipo_emision=" + $("#estado_tipo_emision").val(),
                success: function (data) {
                    var val = data;

                    if (val == 1) {
                        alertify.success('Datos Agregados Correctamente');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                }
            });
        }
    }
}

function guardar_tipo_impuesto() {
    if ($("#codigo_timpu").val() === "") {
        $("#codigo_timpu").focus();
        alertify.error("Ingrese");
    } else {
        if ($("#codigo_timpu").val() === "") {
            $("#codigo_timpu").focus();
            alertify.error("Ingrese");
        } else {
            $.ajax({
                type: "POST",
                url: "../tipo_impuesto/guardar_tipo_impuesto.php",
                data: "nombre_timpu=" + $("#nombre_timpu").val() + "&codigo_timpu=" + $("#codigo_timpu").val(),
                success: function (data) {
                    var val = data;

                    if (val == 1) {
                        alertify.success('Datos Agregados Correctamente');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                }
            });
        }
    }
}

function guardar_Forma_pagos() {
    if ($("#moneda_form_pagos").val() === "") {
        $("#moneda_form_pagos").focus();
        alertify.error("Ingrese");
    } else {
        if ($("#tipo_form_pagos").val() === "") {
            $("#tipo_form_pagos").focus();
            alertify.error("Ingrese");
        } else {
            $.ajax({
                type: "POST",
                url: "../forma_pagos/guardar_forma_pagos.php",
                data: "moneda_form_pagos=" + $("#moneda_form_pagos").val() + "&tipo_form_pagos=" + $("#tipo_form_pagos").val(),
                success: function (data) {
                    var val = data;

                    if (val == 1) {
                        alertify.success('Datos Agregados Correctamente');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                }
            });
        }
    }
}

function guardar_Tarifa_impuesto() {
    if ($("#nombre_tarifa_impuesto").val() === "") {
        $("#nombre_tarifa_impuesto").focus();
        alertify.error("Ingrese");
    } else {
        if ($("#descripcion_tarifa_impuesto").val() === "") {
            $("#descripcion_tarifa_impuesto").focus();
            alertify.error("Ingrese");
        } else {
            if ($("#codigo_tarifa_impuesto").val() === "") {
                $("#codigo_tarifa_impuesto").focus();
                alertify.error("Ingrese");
            } else {
                $.ajax({
                    type: "POST",
                    url: "../tarifa_impuesto/guardar_tarifa_impuesto.php",
                    data: "id_timpu=" + $("#tipo_impuesto").val() + "&codigo_taimpuesto=" + $("#codigo_tarifa_impuesto").val() + "&nombre_taimpuesto=" + $("#nombre_tarifa_impuesto").val() + "&descripcion_taimpuesto=" + $("#descripcion_tarifa_impuesto").val(),
                    success: function (data) {
                        var val = data;
                        if (val == 1) {
                            alertify.success('Datos Agregados Correctamente');
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                        }
                    }
                });
            }
        }
    }
}

function comprobar() {
    if ($("#nombre").val() == "") {
        alertify.alert("Por favor ingrese el nombre de la empresa", function () {
            $("#nombre").focus();
        });
    } else {
        if ($("#representante").val() == "") {
            alertify.alert("Por favor ingrese un representante", function () {
                $("#representante").focus();
            });
        } else {
            if ($("#ruc").val() == "") {
                alertify.alert("Por favor ingrese el ruc de la empresa", function () {
                    $("#ruc").focus();
                });
            } else {
                if ($("#direccion").val() == "") {
                    alertify.alert("Por favor ingrese la dirección de la empresa", function () {
                        $("#direccion").focus();
                    });
                } else {
                    if ($("#telefono").val() == "") {
                        alertify.alert("Por favor ingrese un número de teléfono", function () {
                            $("#telefono").focus();
                        });
                    } else {
                        if ($("#celular").val() == "") {
                            alertify.alert("Por favor ingrese un número celular", function () {
                                $("#celular").focus();
                            });
                        } else {
                            if ($("#pais").val() == "") {
                                alertify.alert("Por favor ingrese su país", function () {
                                    $("#pais").focus();
                                });
                            } else {
                                if ($("#ciudad").val() == "") {
                                    alertify.alert("Por favor ingrese su Ciudad", function () {
                                        $("#ciudad").focus();
                                    });
                                } else {
                                    if ($("#nombre_comercial").val() == "") {
                                        alertify.alert("Por favor ingrese Nombre Comercial", function () {
                                            $("#nombre_comercial").focus();
                                        });
                                    } else {
                                        if ($("#obligacion").val() == "") {
                                            alertify.alert("Por favor ingrese Obligaciòn", function () {
                                                $("#obligacion").focus();
                                            });
                                        } else {
                                            if ($("#contribuyente_espe").val() == "") {
                                                alertify.alert("Por favor ingrese Contribuyente", function () {
                                                    $("#contribuyente_espe").focus();
                                                });
                                            } else {
                                                if ($("#token").val() == "") {
                                                    alertify.alert("Por favor ingrese Token", function () {
                                                        $("#token").focus();
                                                    });
                                                } else {
                                                    if ($("#clave").val() == "") {
                                                        alertify.alert("Por favor ingrese Clave Token", function () {
                                                            $("#clave").focus();
                                                        });
                                                    } else {
                                                        if ($("#establecimiento").val() == "") {
                                                            alertify.alert("Por favor ingrese Establecimiento", function () {
                                                                $("#establecimiento").focus();
                                                            });
                                                        } else {
                                                            if ($("#establecimiento").val() == "") {
                                                                alertify.alert("Por favor ingrese Establecimiento", function () {
                                                                    $("#establecimiento").focus();
                                                                });
                                                            } else {
                                                                if ($("#punto_emision").val() == "") {
                                                                    alertify.alert("Por favor ingrese Punto Emisiòn", function () {
                                                                        $("#punto_emision").focus();
                                                                    });
                                                                } else {
                                                                    $("#codigo_activacion").dialog("open");
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
                        }
                    }
                }
            }
        }
    }
}

function activar() {
    var codigo = $("#codigo").val();
    var uno = ($("#direccion").val()).substring(0, 1);
    uno = uno.toUpperCase();
    var dos = ($("#nombre").val()).substring(1, 2);
    dos = dos.toUpperCase();
    var tres = ($("#pais").val()).substring(($("#pais").val()).length - 1);
    tres = tres.toUpperCase();
    var cuatro = ($("#ciudad").val()).substring(($("#ciudad").val()).length - 1);
    cuatro = cuatro.toUpperCase();
    var cinco = ($("#telefono").val()).substring(4, 5);
    var seis = ($("#celular").val()).substring(4, 5);
    var siete = ($("#ruc").val()).substring(4, 5);
    var ocho = ($("#representante").val()).substring(0, 1);
    ocho = ocho.toUpperCase();
    var fin = uno + dos + tres + cuatro + cinco + seis + siete + ocho;
    if (fin == codigo) {
        $("#codigo_empresa").dialog("open");
    } else {
        alertify.alert("El código ingresado no es válido")
    }
}

function cancelar_act() {
    $("#codigo_activacion").dialog("close");
}

function activar_codigo() {
    if ($("#codigo_emp").val() == "1977") {
        $.ajax({
            type: "POST",
            url: "guardar_empresa.php",
            data: "nombre=" + ($("#nombre").val()).toUpperCase() + "&representante=" + ($("#representante").val()).toUpperCase() + "&ruc=" + $("#ruc").val() + "&direccion=" + ($("#direccion").val()).toUpperCase() + "&telefono=" + $("#telefono").val() + "&celular=" + $("#celular").val() + "&fax=" + $("#fax").val() + "&pais=" + ($("#pais").val()).toUpperCase() + "&ciudad=" + ($("#ciudad").val()).toUpperCase() + "&email" + $("#email").val() + "&pagina=" + $("#pagina").val() + "&descripcion=" + ($("#descripcion").val()).toUpperCase() + "&num_items=" + ($("#num_items").val()).toUpperCase() + "&nombre_comercial=" + ($("#nombre_comercial").val()).toUpperCase() + "&obligacion=" + ($("#obligacion").val()).toUpperCase() + "&contribuyente_espe=" + ($("#contribuyente_espe").val()).toUpperCase() + "&token=" + $("#token").val() + "&clave=" + $("#clave").val() + "&establecimiento=" + ($("#establecimiento").val()).toUpperCase() + "&punto_emision=" + ($("#punto_emision").val()).toUpperCase() + "&porcen_tc=" + $("#porcen_tc").val(),
            success: function (data) {
                var val = data;
                if (val == 1) {
                    alertify.alert("El SISTEMA HA SIDO ACTIVADO SATISFACTORIAMENTE", function () {
                        location.reload();
                    })
                } else {
                    alertify.alert("Error en el proceso, revise los datos por favor");
                }
            }
        });
    }
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
    $("#btnGuardar").click(function (e) {
        e.preventDefault();
    });
    $("#btnModificar").click(function (e) {
        e.preventDefault();
    });
    $("#btnModificar").attr('disabled',true);
    $("#btnModificarAmbi").click(function (e) {
        e.preventDefault();
    });
    $("#btnAceptar").click(function (e) {
        e.preventDefault();
    });
    $("#btnSalir").click(function (e) {
        e.preventDefault();
    });
    $("#btnAcceder").click(function (e) {
        e.preventDefault();
    });
    $("#btnCancelar").click(function (e) {
        e.preventDefault();
    });
    $("#btnActivar").click(function (e) {
        e.preventDefault();
    });
    $("#btnCancelarAct").click(function (e) {
        e.preventDefault();
    });
    $("#btnActivarCod").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardarAmbi").click(function (e) {
        e.preventDefault();
    });
    $("#btnBuscarAmbi").click(function (e) {
        e.preventDefault();
    });
    $("#btnNuevoAmbi").click(function (e) {
        e.preventDefault();
    });
    $("#btnEliminarAmbi").click(function (e) {
        e.preventDefault();
    });
    /////Tipo Comproante/////
    $("#btnGuardartipo_compro").click(function (e) {
        e.preventDefault();
    });
    $("#btnModificartipo_compro").click(function (e) {
        e.preventDefault();
    });
    $("#btnEliminartipo_compro").click(function (e) {
        e.preventDefault();
    });
    $("#btnBuscartipo_compro").click(function (e) {
        e.preventDefault();
    });
    $("#btnNuevotipo_compro").click(function (e) {
        e.preventDefault();
    });

    //////////////////
    $("#btnGuardartipo_emision").click(function (e) {
        e.preventDefault();
    });
    $("#btnModificartipo_emision").click(function (e) {
        e.preventDefault();
    });
    $("#btnEliminartipo_emision").click(function (e) {
        e.preventDefault();
    });
    $("#btnBuscartipo_emision").click(function (e) {
        e.preventDefault();
    });
    $("#btnNuevotipo_emision").click(function (e) {
        e.preventDefault();
    });

    ///////
    $("#btnGuardartipo_impuesto").click(function (e) {
        e.preventDefault();
    });
    $("#btnModificartipo_impuesto").click(function (e) {
        e.preventDefault();
    });
    $("#btnEliminartipo_impuesto").click(function (e) {
        e.preventDefault();
    });
    $("#btnBuscartipo_impuesto").click(function (e) {
        e.preventDefault();
    });
    $("#btnNuevotipo_impuesto").click(function (e) {
        e.preventDefault();
    });

    ///////
    $("#btnGuardarForma_pagos").click(function (e) {
        e.preventDefault();
    });
    $("#btnModificarForma_pagos").click(function (e) {
        e.preventDefault();
    });
    $("#btnEliminarForma_pagos").click(function (e) {
        e.preventDefault();
    });
    $("#btnBuscarForma_pagos").click(function (e) {
        e.preventDefault();
    });
    $("#btnNuevoForma_pagos").click(function (e) {
        e.preventDefault();
    });

    ///////
    $("#btnGuardartarifa_impuesto").click(function (e) {
        e.preventDefault();
    });
    $("#btnModificartarifa_impuesto").click(function (e) {
        e.preventDefault();
    });
    $("#btnEliminartarifa_impuesto").click(function (e) {
        e.preventDefault();
    });
    $("#btnBuscartarifa_impuesto").click(function (e) {
        e.preventDefault();
    });
    $("#btnNuevotarifa_impuesto").click(function (e) {
        e.preventDefault();
    });

    $("#btnGuardartipo_compro").on("click", guardar_tipo_compro);
    $("#btnModificartipo_compro").on("click", modificar_tipo_compro);
    $("#btnEliminartipo_compro").on("click", eliminar_ambiente);
    $("#btnBuscartipo_compro").on("click", abrirDialogo_tipo_compro);
    $("#btnNuevotipo_compro").on("click", nuevo_cliente);

    $("#btnGuardartipo_emision").on("click", guardar_tipo_emision);
    $("#btnModificartipo_emision").on("click", modificar_tipo_emision);
    $("#btnEliminartipo_emision").on("click", eliminar_tipo_emision);
    $("#btnBuscartipo_emision").on("click", abrirDialogo_tipo_emision);
    $("#btnNuevotipo_emision").on("click", nuevo_cliente);
//    
    $("#btnGuardartipo_impuesto").on("click", guardar_tipo_impuesto);
    $("#btnModificartipo_impuesto").on("click", modificar_tipo_impuesto);

    $("#btnBuscartipo_impuesto").on("click", abrirDialogo_tipo_impuesto);
//  $("#btnNuevotipo_impuesto").on("click", nuevo_cliente);
//    
    $("#btnGuardarForma_pagos").on("click", guardar_Forma_pagos);
    $("#btnModificarForma_pagos").on("click", modificar_Forma_pagos);
    $("#btnEliminarForma_pagos").on("click", eliminar_Forma_pagos);
    $("#btnBuscarForma_pagos").on("click", abrirDialogo_forma_pagos);
//  $("#btnNuevoForma_pagos").on("click", nuevo_cliente);  

    $("#btnGuardartarifa_impuesto").on("click", guardar_Tarifa_impuesto);
    $("#btnModificartarifa_impuesto").on("click", modificar_tarifa_impuesto);
//    $("#btnEliminartarifa_impuesto").on("click", eliminar_Forma_pagos);
    $("#btnBuscartarifa_impuesto").on("click", abrirDialogo_tarifa_impuesto);
//  $("#btnNuevotarifa_impuesto").on("click", nuevo_cliente);  

    $("#btnNuevoAmbi").on("click", nuevo_cliente);
    $("#btnEliminarAmbi").on("click", eliminar_ambiente);
    $("#btnBuscarAmbi").on("click", abrirDialogo);
    $("#btnGuardarAmbi").on("click", guardar_ambiente);
    $("#btnModificarAmbi").on("click", modificar_ambiente);
    $("#btnGuardar").on("click", comprobar);
    //$("#btnModificar").on("click", confirmar);
    $("#btnAcceder").on("click", validar_acceso);
    $("#btnCancelar").on("click", cancelar_acceso);
    $("#btnAceptar").on("click", modificar);
    $("#btnSalir").on("click", cancelar);
    $("#btnActivar").on("click", activar);
    $("#btnCancelarAct").on("click", cancelar_act);
    $("#btnActivarCod").on("click", activar_codigo);

    $("#clave_permiso").dialog(dialogo3);
    $("#seguro").dialog(dialogo4);
    $("#cuentas").dialog(dialogo_cuenta);
    $("#codigo_activacion").dialog(dialogo_codigo);
    $("#codigo_empresa").dialog(dialogo_empresa);
    $("#ambientes").dialog(dialogo);
    $("#tipo_comprobante").dialog(dialogoTipo_compro);
    $("#tipo_emision").dialog(dialogoTipo_emision);
    $("#tipo_impuestoList").dialog(dialogoTipo_impuesto);
    $("#tipo_forma_pagos").dialog(dialogoforma_pagos);
    $("#tipo_tarifa_impuesto").dialog(dialogotarifa_impuesto);

    $("#num_items").validCampoFranz("0123456789");
    $("#establecimiento").validCampoFranz("0123456789");
    $("#contribuyente_espe").validCampoFranz("0123456789");
    $("#punto_emision").validCampoFranz("0123456789");
    $("#celular").validCampoFranz("0123456789");
    $("#telefono").validCampoFranz("0123456789");
    $("#ruc").validCampoFranz("0123456789");

    jQuery("#list").jqGrid({
        url: '../ambiente/datos_ambiente.php',
        datatype: 'xml',
        colNames: ['Código', 'Tipo Ambiente', 'Código'],
        colModel: [
            {name: 'id_ambi', index: 'id_ambi', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'tipo_ambiente', index: 'tipo_ambiente', editable: true, align: 'center', width: '120', search: false, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'codigo_ambi', index: 'codigo_ambi', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}}

        ],
        rowNum: 10,
        width: 830,
        height: 200,
        rowList: [10, 20, 30],
        pager: jQuery('#pagerl'),
        sortname: 'id_ambi',
        shrinkToFit: false,
        sortorder: 'asc',
        caption: 'Lista de Tipos de Ambiente',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list").jqGrid('getGridParam', 'selrow');
            jQuery('#list').jqGrid('restoreRow', id);
            jQuery("#list").jqGrid('GridToForm', id, "#parametros_form");
            $("#btnGuardarAmbi").attr("disabled", true);
            $("#ambientes").dialog("close");
        }
    }).jqGrid('navGrid', '#pagerl',
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
        onClickButton: function () {
            var id = jQuery("#list").jqGrid('getGridParam', 'selrow');
            jQuery('#list').jqGrid('restoreRow', id);
            if (id) {
                jQuery("#list").jqGrid('GridToForm', id, "#parametros_form");
                $("#btnGuardarAmbi").attr("disabled", true);
                $("#ambientes").dialog("close");
            } else {
                alertify.alert("Seleccione un fila");
            }
        }
    });

    jQuery("#listTipo").jqGrid({
        url: '../tipo_comprobante/datos_tipo_compro.php',
        datatype: 'xml',
        colNames: ['Código', 'Tipo Comprobante', 'Abreviatura', 'Código'],
        colModel: [
            {name: 'id_tipo_comprobante', index: 'id_tipo_comprobante', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'nombre_tipo_compro', index: 'nombre_tipo_compro', editable: true, align: 'center', width: '120', search: false, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'abreviatura_tipo_compro', index: 'abreviatura_tipo_compro', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'codigo_tipo_compro', index: 'codigo_tipo_compro', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
        ],
        rowNum: 10,
        width: 830,
        height: 200,
        rowList: [10, 20, 30],
        pager: jQuery('#pagert'),
        sortname: 'id_tipo_comprobante',
        shrinkToFit: false,
        sortorder: 'asc',
        caption: 'Lista de Tipos de Comprobantes',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#listTipo").jqGrid('getGridParam', 'selrow');
            jQuery('#listTipo').jqGrid('restoreRow', id);
            jQuery("#listTipo").jqGrid('GridToForm', id, "#parametros_form");
            $("#btnGuardartipo_compro").attr("disabled", true);
            $("#tipo_comprobante").dialog("close");
        }
    }).jqGrid('navGrid', '#pagert',
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
    jQuery("#listTipo").jqGrid('navButtonAdd', '#pagert', {caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#listTipo").jqGrid('getGridParam', 'selrow');
            jQuery('#listTipo').jqGrid('restoreRow', id);
            if (id) {
                jQuery("#listTipo").jqGrid('GridToForm', id, "#parametros_form");
                $("#btnGuardartipo_compro").attr("disabled", true);
                $("#tipo_comprobante").dialog("close");
            } else {
                alertify.alert("Seleccione un fila");
            }
        }
    });

    jQuery("#listTipo_emision").jqGrid({
        url: '../tipo_emision/datos_tipo_emision.php',
        datatype: 'xml',
        colNames: ['Código', 'Tipo Emisión', 'Código'],
        colModel: [
            {name: 'id_temision', index: 'id_temision', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'nombre_tipo_emision', index: 'nombre_tipo_emision', editable: true, align: 'center', width: '120', search: false, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'codigo_tipo_emision', index: 'codigo_tipo_emision', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}}
        ],
        rowNum: 10,
        width: 830,
        height: 200,
        rowList: [10, 20, 30],
        pager: jQuery('#pagere'),
        sortname: 'id_temision',
        shrinkToFit: false,
        sortorder: 'asc',
        caption: 'Lista de Tipos de Emisión',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#listTipo_emision").jqGrid('getGridParam', 'selrow');
            jQuery('#listTipo_emision').jqGrid('restoreRow', id);
            jQuery("#listTipo_emision").jqGrid('GridToForm', id, "#parametros_form");
            $("#btnGuardartipo_emision").attr("disabled", true);
            $("#tipo_emision").dialog("close");
        }
    }).jqGrid('navGrid', '#pagere',
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
    jQuery("#listTipo_emision").jqGrid('navButtonAdd', '#pagere', {caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#listTipo_emision").jqGrid('getGridParam', 'selrow');
            jQuery('#listTipo_emision').jqGrid('restoreRow', id);
            if (id) {
                jQuery("#listTipo_emision").jqGrid('GridToForm', id, "#parametros_form");
                $("#btnGuardartipo_emision").attr("disabled", true);
                $("#tipo_emision").dialog("close");
            } else {
                alertify.alert("Seleccione un fila");
            }
        }
    });

    jQuery("#listTipo_impuesto").jqGrid({
        url: '../tipo_impuesto/datos_tipo_impuesto.php',
        datatype: 'xml',
        colNames: ['Código', 'Tipo Impuesto', 'Código'],
        colModel: [
            {name: 'id_timpu', index: 'id_timpu', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'nombre_timpu', index: 'nombre_timpu', editable: true, align: 'center', width: '120', search: false, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'codigo_timpu', index: 'codigo_timpu', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}}
        ],
        rowNum: 10,
        width: 830,
        height: 200,
        rowList: [10, 20, 30],
        pager: jQuery('#pageri'),
        sortname: 'id_timpu',
        shrinkToFit: false,
        sortorder: 'asc',
        caption: 'Lista de Tipos de Impuestos',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#listTipo_impuesto").jqGrid('getGridParam', 'selrow');
            jQuery('#listTipo_impuesto').jqGrid('restoreRow', id);
            jQuery("#listTipo_impuesto").jqGrid('GridToForm', id, "#parametros_form");
            $("#btnGuardartipo_impuesto").attr("disabled", true);
            $("#tipo_impuesto").dialog("close");
        }
    }).jqGrid('navGrid', '#pageri',
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
    jQuery("#listTipo_impuesto").jqGrid('navButtonAdd', '#pageri', {caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#listTipo_impuesto").jqGrid('getGridParam', 'selrow');
            jQuery('#listTipo_impuesto').jqGrid('restoreRow', id);
            if (id) {
                jQuery("#listTipo_impuesto").jqGrid('GridToForm', id, "#parametros_form");
                $("#btnGuardartipo_impuesto").attr("disabled", true);
                $("#tipo_impuesto").dialog("close");
            } else {
                alertify.alert("Seleccione un fila");
            }
        }
    });

    jQuery("#listForma_pagos").jqGrid({
        url: '../forma_pagos/datos_forma_pagos.php',
        datatype: 'xml',
        colNames: ['Código', 'Moneda', 'Tipo'],
        colModel: [
            {name: 'id_form_pagos', index: 'id_form_pagos', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'moneda_form_pagos', index: 'moneda_form_pagos', editable: true, align: 'center', width: '120', search: false, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'tipo_form_pagos', index: 'tipo_form_pagos', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
        ],
        rowNum: 10,
        width: 830,
        height: 200,
        rowList: [10, 20, 30],
        pager: jQuery('#pagerf'),
        sortname: 'id_form_pagos',
        shrinkToFit: false,
        sortorder: 'asc',
        caption: 'Lista de Forma de Pago',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#listForma_pagos").jqGrid('getGridParam', 'selrow');
            jQuery('#listForma_pagos').jqGrid('restoreRow', id);
            jQuery("#listForma_pagos").jqGrid('GridToForm', id, "#parametros_form");
            $("#btnGuardarForma_pagos").attr("disabled", true);
            $("#tipo_forma_pagos").dialog("close");
        }
    }).jqGrid('navGrid', '#pagerf',
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
    jQuery("#listForma_pagos").jqGrid('navButtonAdd', '#pagerf', {caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#listForma_pagos").jqGrid('getGridParam', 'selrow');
            jQuery('#listForma_pagos').jqGrid('restoreRow', id);
            if (id) {
                jQuery("#listForma_pagos").jqGrid('GridToForm', id, "#parametros_form");
                $("#btnGuardarForma_pagos").attr("disabled", true);
                $("#tipo_forma_pagos").dialog("close");
            } else {
                alertify.alert("Seleccione un fila");
            }
        }
    });

    jQuery("#listTarifa_impuesto").jqGrid({
        url: '../tarifa_impuesto/datos_tarifa_impuesto.php',
        datatype: 'xml',
        colNames: ['Código', 'Nombre', 'Descripción', 'Código'],
        colModel: [
            {name: 'id_taimpuesto', index: 'id_taimpuesto', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'nombre_tarifa_impuesto', index: 'nombre_tarifa_impuesto', editable: true, align: 'center', width: '120', search: false, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'descripcion_tarifa_impuesto', index: 'descripcion_tarifa_impuesto', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'codigo_tarifa_impuesto', index: 'codigo_tarifa_impuesto', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
        ],
        rowNum: 10,
        width: 830,
        height: 200,
        rowList: [10, 20, 30],
        pager: jQuery('#pager'),
        sortname: 'id_taimpuesto',
        shrinkToFit: false,
        sortorder: 'asc',
        caption: 'Lista de Tarifas de Impuestos',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#listTarifa_impuesto").jqGrid('getGridParam', 'selrow');
            jQuery('#listTarifa_impuesto').jqGrid('restoreRow', id);
            jQuery("#listTarifa_impuesto").jqGrid('GridToForm', id, "#parametros_form");
            $("#btnGuardartarifa_impuesto").attr("disabled", true);
            $("#tipo_tarifa_impuesto").dialog("close");
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
    jQuery("#listTarifa_impuesto").jqGrid('navButtonAdd', '#pager', {caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#listTarifa_impuesto").jqGrid('getGridParam', 'selrow');
            jQuery('#listTarifa_impuesto').jqGrid('restoreRow', id);
            if (id) {
                jQuery("#listTarifa_impuesto").jqGrid('GridToForm', id, "#parametros_form");
                $("#btnGuardartarifa_impuesto").attr("disabled", true);
                $("#tipo_tarifa_impuesto").dialog("close");
            } else {
                alertify.alert("Seleccione un fila");
            }
        }
    });

    $.ajax({
        type: "POST",
        url: "comprobar_empresa.php",
        data: "valor",
        success: function (data) {
            var val = data;
            var valores;
            if (val != "") {
                valores = val.split("*");
                $("#nombre").val(valores[1]);
                $("#ruc").val(valores[2]);
                $("#direccion").val(valores[3]);
                $("#telefono").val(valores[4]);
                $("#celular").val(valores[5]);
                $("#pais").val(valores[6]);
                $("#ciudad").val(valores[7]);
                $("#fax").val(valores[8]);
                $("#email").val(valores[9]);
                $("#pagina").val(valores[10]);
                $("#descripcion").val(valores[11]);
                $("#representante").val(valores[12]);
                $("#imagen").val(valores[13]);
                $("#estado").val(valores[14]);
                $("#nombre_comercial").val(valores[17]);
                $("#obligacion").val(valores[18]);
                $("#contribuyente_espe").val(valores[19]);
                $("#token").val(valores[20]);
                $("#claveToken").val(valores[21]);
                $("#establecimiento").val(valores[22]);
                $("#punto_emision").val(valores[23]);
                $("#btnGuardar").attr("disabled", "disabled");
            } else {
                alertify.alert("Bienvenido a Sisweb, por favor ingrese su empresa", function () {
                    $("#btnGuardar").attr("disabled", false);
                    $("#btnModificar").attr("disabled", "disabled");
                });
            }
        }
    });
}