$(document).ready(inicio);
var parametros = undefined;

function mostrarcampos() {

    if (document.getElementById('check_agente_reten').checked == true) {
        $('#id_agente_reten').show();
        $('#id_agente_reten_resolucion').show();
    } else {
        $('#id_agente_reten').hide();
        $('#id_agente_reten_resolucion').hide();
    }
}


function inicio() {
    $.ajax({
        type: "POST",
        url: "comprobar_valoriva.php",
        data: "valor",
        success: function (data) {
            var val = data;
            var valores;
            if (val != "") {
                valores = val.split("*");
                console.log(valores);
                $("#valor_iva").val(valores[1]);

            }
        }
    });
    $("#check_agente_reten").on("change", mostrarcampos);



    $("#logo_empresa").change(function (e) {
        if (e.target.files.length >= 0) {
            cargarImagen($("#mostrar_logo_empresa")[0], e.target.files[0]);
            console.log(e.target.files[0]);
        }
    });


    /*    document
     .getElementById("btn_guardar_parametrose_imagen")
     .addEventListener("click", function (e) {
     guardar();
     }); */
    document
            .getElementById("btn_guardar_parametrose_correo")
            .addEventListener("click", function (e) {
                guardar();
            });
    document
            .getElementById("archivo_p12")
            .addEventListener("change", function (e) {
                guardarArchivoP12();
            });
    document
            .getElementById("logo_empresa")
            .addEventListener("change", function (e) {
                guardarLogo();
            });
    document
            .getElementById("btn_quiar_logo")
            .addEventListener("click", function (e) {
                quitarParametro("logo_empresa");
            });
    document
            .getElementById("btn_quiar_p12")
            .addEventListener("click", function (e) {
                quitarParametro("archivo_p12");
            });

    llenarParametrosEmpresa();
}
function cargarImagen(elem, file) {
    var img = new Image(150);
    if (typeof file === 'string') {
        img.src = file;
    } else {
        img.src = URL.createObjectURL(file);
    }
    img.onload = function () {
        URL.revokeObjectURL(this.src);
    };
    elem.innerHTML = "";
    elem.appendChild(img);

    mostrarImagenLogo();

}

function mostrarNombreArchivoP12(nombre) {
    $("#archivo_p12").css({display: "none"});
    $("#mostrar_nombre_archivo_p12").css({display: ""});

    if (nombre.length > 25) {
        $("#nombre_archivo_p12").text("..." + nombre.substring(nombre.length - 25));
    } else {
        $("#nombre_archivo_p12").text(nombre);
    }
    //$("#archivo_p12").css({ display: "none" });
}

function mostrarImagenLogo() {
    $("#btn_quiar_logo").css({display: ""});
    $("#mostrar_logo_empresa").css({display: ""});
    $("#logo_empresa").css({display: "none"});
}

function guardar() {
    let form = new FormData();
    form.append("host_correo", $("#host_correo").val());
    form.append("user_correo", $("#user_correo").val());
    form.append("pass_correo", $("#pass_correo").val());
    form.append("port_correo", $("#port_correo").val());
    form.append("secure_correo", $("#secure_correo").val());
    form.append("copia_correo", $("#copia_correo").val());
    form.append("app_firma", $("#app_firma").val());
    form.append("formato_imperesion_factura", $("#formato_imperesion_factura").val());
    form.append("formato_imperesion_nota", $("#formato_imperesion_nota").val());
    form.append("formato_imperesion_nota_credito", $("#formato_imperesion_nota_credito").val());
    form.append("formato_imperesion_factura_compra", $("#formato_imperesion_factura_compra").val());
    form.append("formato_imperesion_retencion_compra", $("#formato_imperesion_retencion_compra").val());
    form.append("clave_firma", $("#clave_firma").val());
    form.append("autorizar_fac_auto", $("#autorizar_fac_auto")[0].checked ? 1 : '');
    form.append("val_rimpe", $("#val_rimpe").val());
    form.append("agente_reten", $("#agente_reten").val());
    form.append("apertura_caja", $("#apertura_caja")[0].checked ? 1 : '');
    form.append("agente_reten_resolucion", $("#agente_reten_resolucion").val());
    form.append("check_agente_reten", $("#check_agente_reten")[0].checked ? 1 : '');
    form.append("valor_iva", $("#valor_iva").val());
    form.append("defecto_iva", $("#defecto_iva1")[0].checked ? 'Si' : 'No');
    fetch("guardar_parametros_empresa.php", {
        method: "post",
        body: form
    }).then(function (d) {
        return d.text();
    }).then(function (res) {
        if (res == 0) {
            alertify.success("Parámetros guardados.");
        }
    })
}

function guardarArchivoP12() {
    console.log($("#archivo_p12")[0].files[0]);
    let form = new FormData();
    form.append("archivo_p12", $("#archivo_p12")[0].files[0]);
    fetch("guardar_parametros_empresa.php", {
        method: "post",
        body: form
    }).then(function (d) {
        return d.text();
    }).then(function (res) {
        if (res == 0) {
            mostrarNombreArchivoP12($("#archivo_p12")[0].files[0].name);
            alertify.success("Parámetros guardados.");
            $("#archivo_p12").val("");
        }
    })
}

function guardarLogo() {
    let form = new FormData();
    form.append("logo_empresa", $("#logo_empresa")[0].files[0]);
    fetch("guardar_parametros_empresa.php", {
        method: "post",
        body: form
    }).then(function (d) {
        return d.text();
    }).then(function (res) {
        if (res == 0) {
            $("#logo_empresa").val("");
            alertify.success("Parámetros guardados.");
        }
    });
}

function obtenerParametrosEmpresa() {
    return fetch("obtener_parametros_empresa.php")
            .then(function (d) {
                return d.json();
            })
            .then(function (json) {
                return json;
            });
}

function llenarParametrosEmpresa() {
    obtenerParametrosEmpresa().then(function (json) {
        if (json.length > 0) {
            json.forEach(el => {
                switch (el.nombre_parametro) {
                    case "logo_empresa":
                        if (!!el.valor_parametro) {
                            cargarImagen($("#mostrar_logo_empresa")[0], "../../images/" + el.valor_parametro);
                        } else {
                            mostrarIputLogo();
                        }
                        break;
                    case "host_correo":
                        $("#host_correo").val(el.valor_parametro);
                        break;
                    case "user_correo":
                        $("#user_correo").val(el.valor_parametro);
                        break;
                    case "pass_correo":
                        $("#pass_correo").val(el.valor_parametro);
                        break;
                    case "port_correo":
                        $("#port_correo").val(el.valor_parametro);
                        break;
                    case "smtpsecure_correo":
                        $("#secure_correo").val(el.valor_parametro);
                        break;
                    case "copia_correo":
                        $("#copia_correo").val(el.valor_parametro);
                        break;
                    case "app_firma":
                        $("#app_firma").val(el.valor_parametro);
                        break;
                    case "formato_imperesion_factura":
                        $("#formato_imperesion_factura").val(el.valor_parametro);
                        break;
                    case "formato_imperesion_nota":
                        $("#formato_imperesion_nota").val(el.valor_parametro);
                        break;
                    case "formato_imperesion_nota_credito":
                        $("#formato_imperesion_nota_credito").val(el.valor_parametro);
                        break;
                    case "formato_imperesion_factura_compra":
                        $("#formato_imperesion_factura_compra").val(el.valor_parametro);
                        break;
                    case "formato_imperesion_retencion_compra":
                        $("#formato_imperesion_retencion_compra").val(el.valor_parametro);
                        break;
                    case "archivo_p12":
                        if (!!el.valor_parametro) {
                            mostrarNombreArchivoP12(el.valor_parametro);
                        } else {
                            mostrarInputArchivoP12();
                        }
                        break;
                    case "clave_firma":
                        $("#clave_firma").val(el.valor_parametro);
                        break;
                    case "autorizar_fac_auto":
                        $("#autorizar_fac_auto")[0].checked = false
                        if (el.valor_parametro == 1) {
                            $("#autorizar_fac_auto")[0].checked = true
                        }
                        break;
                    case "val_rimpe":
                        console.log("dd",el.valor_parametro);
                        $("#val_rimpe").val(el.valor_parametro);
                        break;
                    case "agente_reten":
                        $("#agente_reten").val(el.valor_parametro);
                        break;
                    case "apertura_caja":
                        $("#apertura_caja")[0].checked = false
                        if (el.valor_parametro == 1) {
                            $("#apertura_caja")[0].checked = true
                        }
                        break;
                    case "check_agente_reten":
                        $("#check_agente_reten")[0].checked = false
                        if (el.valor_parametro == 1) {
                            $("#check_agente_reten")[0].checked = true
                            $('#id_agente_reten').show();
                            $('#id_agente_reten_resolucion').show();

                        } else {
                            $('#id_agente_reten').hide();
                            $('#id_agente_reten_resolucion').hide();
                        }

                        break;
                    case "agente_reten_resolucion":
                        $("#agente_reten_resolucion").val(el.valor_parametro);
                        break;
                        
                        /////
                              case "defecto_iva":
                        $("#defecto_iva1")[0].checked = false
                             $("#defecto_iva2")[0].checked = false
                        if (el.valor_parametro == 'Si') {
                            $("#defecto_iva1")[0].checked = true
                          

                        } else  if (el.valor_parametro == 'No') {
                             $("#defecto_iva2")[0].checked = true
                        }

                        break;
                }
            });
        }

    });
}

function quitarParametro(nombreparam) {
    let msg = "";
    if (nombreparam == "logo_empresa") {
        msg = "logo de empresa";
    } else if (nombreparam == "archivo_p12") {
        msg = "archivo P12";
    }
    alertify.confirm(`¿Desea quitar ${msg}?`, function (e) {
        if (e) {
            let form = new FormData();
            form.append("quitar_parametro", nombreparam);
            fetch("guardar_parametros_empresa.php", {
                method: "post",
                body: form
            }).then(function (d) {
                return d.text();
            }).then(function (res) {
                if (res == 0) {
                    if (nombreparam == "logo_empresa") {
                        mostrarIputLogo();
                    } else if (nombreparam == "archivo_p12") {
                        mostrarInputArchivoP12();
                    }

                    alertify.success("Parámetros guardados.");
                }
            })

        } else {
            alertify.log("Acción cancelada.");
        }
    });
}

function mostrarIputLogo() {
    $("#btn_quiar_logo").css({display: "none"});
    $("#mostrar_logo_empresa").css({display: "none"});
    $("#logo_empresa").css({display: ""});
}

function mostrarInputArchivoP12() {
    $("#btn_quiar_p12").css({display: ""});
    $("#mostrar_nombre_archivo_p12").css({display: "none"});
    $("#archivo_p12").css({display: ""});
}