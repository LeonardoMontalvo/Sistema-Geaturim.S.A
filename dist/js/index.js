$(document).on("ready", inicio);
$(document).keydown(function (e) {
    var e = e || event;
    var keycode = e.which || e.keyCode;
    var obj = e.target || e.srcElement;
    // No activar el evento si estamos en un formulario
    //if(obj.tagName.toLowerCase()=="textarea") { return; }
    //if(obj.tagName.toLowerCase()=="input") { return; }
    //Guardar Factura
    //if(keycode == 118) { guardar_factura()}
    //if(keycode == 119) { ingresar_cambio()}
    //Tecla Control Cliente
//    if(keycode == 17) { $("#ruc_ci").select() }
});
var dialogo = {
    autoOpen: false,
    resizable: false,
    width: 500,
    height: 600,
    modal: true
};

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

function este() {
    window.open('../../fpdf/ayuda_general.pdf');
}

function enter(e) {
    if (e.which === 13 || e.keyCode === 13) {
        ingresarSistema();
        return false;
    }
    return true;
}

function cancelar() {
    $("#crear_empresa").dialog("close");
    $("#txt_usuario").val("");
    $("#txt_contra").val("");
}

function guardar_empresa() {
    if ($("#nombre_empresa").val() === "") {
        $("#nombre_empresa").focus();
        alertify.error("Ingrese nombre de la empresa");
    } else {
        if ($("#ruc_empresa").val() === "") {
            $("#ruc_empresa").focus();
            alertify.error("Ingrese ruc de la empresa");
        } else {
            if ($("#direccion_empresa").val() === "") {
                $("#direccion_empresa").focus();
                alertify.error("Ingrese dirección de la empresa");
            } else {
                if ($("#telefono_empresa").val() === "") {
                    $("#telefono_empresa").focus();
                    alertify.error("Ingrese telefóno de la empresa");
                } else {
                    if ($("#pais_empresa").val() === "") {
                        $("#pais_empresa").focus();
                        alertify.error("Ingrese el país");
                    } else {
                        if ($("#ciudad_empresa").val() === "") {
                            $("#ciudad_empresa").focus();
                            alertify.error("Ingrese la ciudad");
                        } else {
                            $.ajax({
                                type: "POST",
                                url: "../procesos/guardar_empresa.php",
                                data: "nombre_empresa=" + encodeURIComponent($("#nombre_empresa").val()) + "&ruc_empresa=" + $("#ruc_empresa").val() + "&direccion_empresa=" + $("#direccion_empresa").val() +
                                        "&telefono_empresa=" + $("#telefono_empresa").val() + "&celular_empresa=" + $("#celular_empresa").val() + "&pais_empresa=" + $("#pais_empresa").val() + "&ciudad_empresa=" + $("#ciudad_empresa").val() + "&fax_empresa=" + $("#fax_empresa").val() + "&correo_empresa=" + $("#correo_empresa").val() + "&pagina_empresa=" + $("#pagina_empresa").val() + "&propietario_empresa=" + $("#propietario_empresa").val() + "&descripcion_empresa=" + $("#descripcion_empresa").val(),
                                success: function (data) {
                                    var val = data;
                                    if (val == 1) {
                                        alertify.alert("Empresa guardada Correctamente", function () {
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
    }
}
function abrirPuntoventa() {
    $.ajax({
        type: "POST",
        url: "../../factura_venta/guardar_punto_venta.php",
        data: "id=" + $("#sel_punto_venta").val(),
        success: function (data) {
            var val = data;
            if (val == 1) {
                alertify.alert("Guardado Correctamente");
            } else {
                alertify.error("Error.... El Punto de Venta ya existe");
            }
        }
    });
}
function retornar() {
//     location.href("../");
    window.location.assign("../");
    window.location.hash = "no-back-button";
    window.location.hash = "Again-No-back-button";
    window.onhashchange = function () {
        window.location.hash = "no-back-button";
    }
}

function inicio() {
      $("#punto_venta").append($(`<option value="">---------------</option>`));
    $("#txt_usuario").change(function (e) {
        $("#usuario_incorrecto").hide();
        $("#usuario_correcto").hide();
        $("#punto_venta").empty();
        $("#punto_venta").append($(`<option value="">---------------</option>`));
        obtenerPuntosVenta($(this).val()).then(function (data) {
            $("#punto_venta").empty();
            if (data.length > 0) {
                $("#usuario_incorrecto").hide();
            } else {
                $("#usuario_incorrecto").show();
                $("#punto_venta").append($(`<option value="">---------------</option>`));
            }
            data.forEach(el => {
                let option = $(`<option value="${el.id_punto_venta}">${el.nombre_punto}</option>`);
                $("#punto_venta").append(option);
            });
        });
    });
    window.location.hash = "no-back-button";
    window.location.hash = "Again-No-back-button";
    window.onhashchange = function () {
        window.location.hash = "no-back-button";
    }
    $("#ruc_empresa").validCampoFranz("0123456789");
    $("#telefono_empresa").validCampoFranz("0123456789");
    $("#celular_empresa").validCampoFranz("0123456789");
    $("#ruc_empresa").attr("maxlength", "13");

    $("#btnIngreso").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardar").click(function (e) {
        e.preventDefault();
    });
    $("#btnRetornar").click(function (e) {
        e.preventDefault();
    });
    $("#btnPuntoventa").click(function (e) {
        e.preventDefault();
    });
    ////////////eventos botones/////////////
    $("#btnPuntoventa").on("click", abrirPuntoventa);
    $("#btnCancelar").on("click", cancelar);
    $("#btnGuardar").on("click", guardar_empresa);
    $("#btnIngreso").on("click", ingresarSistema);
    $("#btnRetornar").on("click", retornar);
    //////////////////////////////////////

    ////////////dialogo///////////////
    // $("#crear_empresa").dialog(dialogo);

    ///////////////////////////////
    $("#txt_usuario").focus();
    $("#txt_contra").on("keypress", enter);
    $("#txt_usuario").on("keypress", enter);
}

function ingresarSistema() {
    if ($("#txt_usuario").val() === "") {
        $("#txt_usuario").focus();
        alertify.alert("Ingrese el usuario");
    } else {
        if ($("#txt_contra").val() === "") {
            $("#txt_contra").focus();
            alertify.alert("Ingrese la contraseña");
        } else {
            var punto = document.getElementById("punto_venta").value;

            if (punto == 0) {
                alertify.alert("Seleccione Punto de Venta");
            } else {

                $.ajax({
                    url: '../procesos/index.php',
                    type: 'POST',
                    data: "usuario=" + $("#txt_usuario").val() + "&clave=" + $("#txt_contra").val() + "&id_empresa=" + $("#empresa").val() + "&id_punto_venta=" + punto,
                    success: function (data) {
                        var val = data;
                        if (val == 1) {
                            if ($("#empresa").val() === null) {
                                alertify.confirm("Desea crear una nueva empresa", function (e) {
                                    if (e) {
                                        $("#crear_empresa").dialog("open");
                                    } else {
                                        $("#txt_usuario").val("");
                                        $("#txt_contra").val("");
                                    }
                                });
                            } else {
                                window.location.href = "principal";
                            }
                        } else {
                            if (val == 2) {
                                if ($("#empresa").val() === "" || $("#empresa").val() === null) {
                                    $("#txt_usuario").val("");
                                    $("#txt_contra").val("");
                                    $("#txt_usuario").focus();
                                    alertify.alert("Imposible acceder al sistema");
                                } else {
                                    window.location.href = "principal";
                                }
                            } else {
                                if (val == 0) {
                                    $("#txt_contra").val("")
                                    $("#txt_contra").focus();
                                    alertify.alert("Error... Los datos son incorrectos ingrese nuevamente");
                                } else {
                                    if (val == 3) {
                                        $("#txt_contra").val("")
                                        $("#txt_contra").focus();
                                        alertify.alert("Error su usuario no puede ingresar en este horario");
                                    } else {
                                        if (val == 10) {
                                            alertify.alert("El Usuario ya esta en un punto de venta");
                                        }
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }
    }
}
function obtenerPuntosVenta(usuario) {
    return $.ajax({
        url: '../procesos/buscar_puntos_venta_usuario.php',
        method: "GET",
        data: { usuario },
        dataType: "json"
    });
}

