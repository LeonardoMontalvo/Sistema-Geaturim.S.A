$(document).ready(inicio);

var flagDuplicar = false;
var flagDuplicarM = false;
var idUsadmin="";

const dialogoEmpresa = {
    autoOpen: false,
    resizable: false,
    draggable: false,
    width: (window.screen.width * window.devicePixelRatio) - 300,
    height: (window.screen.height * window.devicePixelRatio) - 150,
    modal: true,
    show: "explode",
    hide: "blind",
    title: "Crear Empresa",
    buttons: [
        {
            text: "Guardar",
            click: function () {
                if (validarFormulario()) {
                    guardarEmpresa();
                }
            }
            //showText: false
        },
        {
            text: "Cancelar",
            click: function () {
                $(this).dialog("close");
            }
        }
    ],
    open: function () {
        $(this).dialog("widget").css({ "z-index": "1100" });
        $(this).dialog("widget").find("button").each(function (i) {
            if (this.textContent == "Guardar") {
                $(this).css({ background: "#388E3C", color: "#fff" });
            } else if (this.textContent == "Cancelar") {
                $(this).css({ background: "#B71C1C", color: "#fff" });
            }
        });
    },
    close: function () {
        $("#crear_empresa_form")[0].reset();
        $("#info_empresa").show();
    }
};
const dialogoDuplicarEmpresa = {
    autoOpen: false,
    resizable: false,
    draggable: false,
    width: 860,
    height: 180,
    modal: true,
    show: "explode",
    hide: "blind",
    title: "Duplicar Empresa",
    open: function () {

        $("#esquema_destino").empty();
        let option = $(`<option value="">--Seleccione Destino--</option>`);
        $("#esquema_destino").append(option);

        obtenerEsquemas().then(function (data) {
            $("#esquema_origen").empty();
            let option = $(`<option value="">--Seleccione Origen--</option>`);
            $("#esquema_origen").append(option);
            data.forEach(e => {
                let option = $(`<option value="${e.nombre}">${e.nombre}</option>`);
                $("#esquema_origen").append(option);
            });
        });
    }
}
const dialogConfirmar = {
    autoOpen: false,
    resizable: false,
    draggable: false,
    width: 400,
    height: 240,
    modal: true,
    show: "explode",
    hide: "blind",
    title: "Confirmar acción",
    buttons: [
        {
            text: "Aceptar",
            click: function () {
                if (flagDuplicar) {
                    confirmarDuplicar();
                } else if (flagDuplicarM) {
                    confirmarDuplicarMaestros();
                }

            }
            //showText: false
        },
        {
            text: "Cancelar",
            click: function () {
                $(this).dialog("close");
            }
        }
    ],
    open: function () {
        $("#clave_invalida").hide();
        $(this).dialog("widget").find("button").each(function (i) {
            if (this.textContent == "Aceptar") {
                $(this).css({ background: "#388E3C", color: "#fff" });
            } else if (this.textContent == "Cancelar") {
                $(this).css({ background: "#B71C1C", color: "#fff" });
            }
        });
    },
};
let animarfila = false;

function inicio() {
    $("[data-mask]").inputmask();
    inicioTabla();
    $("#dialog_empresa").dialog(dialogoEmpresa);
    $("#dialog_duplicar").dialog(dialogoDuplicarEmpresa);
    $("#dialog_validar_acceso").dialog(dialogConfirmar);
    $("#crear_empresa").click(function (e) {
        $("#dialog_empresa").dialog("open");
    });
    $("#duplicar_empresa").click(function (e) {
        $("#dialog_duplicar").dialog("open");
        flagDuplicar = true;
        flagDuplicarM = false;
    });
    $("#duplicar_maestros_empresa").click(function (e) {
        $("#dialog_duplicar").dialog("open");
        flagDuplicar = false;
        flagDuplicarM = true;
    });
    $("#datos_prueba").change(function (e) {
        if (this.checked) {
            $("#info_empresa").hide();
            $("#dialog_empresa").dialog({
                height: 390
            });
        } else {
            $("#info_empresa").show();
            $("#dialog_empresa").dialog({
                height: (window.screen.height * window.devicePixelRatio) - 150
            });
        }
    });
    $("#duplicar").click(function (e) {
        if (validarFormularioDuplicar()) {
            $("#dialog_validar_acceso").dialog("open");
        }
    });
    $("#ruc_empresa").validCampoFranz("0123456789");
    $("#telefono").validCampoFranz("0123456789");
    $("#celular").validCampoFranz("0123456789");
    $("#establecimiento").validCampoFranz("0123456789");
    $("#p_emision").validCampoFranz("0123456789");
    $("#nombre_esquema").validCampoFranz("abcdefghijklmnopqrstuvwxyz0123456789-_");
    $("#ruc_empresa")[0].addEventListener("input", function (e) {
        if (e.target.value.length == 13) {
            validarRuc();
        }
    });

    $("#esquema_origen").change(function (e) {
        obtenerEsquemas().then(function (data) {
            data = data.filter(el => el.nombre != $("#esquema_origen").val() && el.nombre != 'public');
            $("#esquema_destino").empty();
            let option = $(`<option value="">--Seleccione Destino--</option>`);
            $("#esquema_destino").append(option);
            data.forEach(e => {
                let option = $(`<option value="${e.nombre}">${e.nombre}</option>`);
                $("#esquema_destino").append(option);
            });
        });
    });

    $("#crear_empresa_form").find("input, textarea").each(function (i) {
        if (this.type == "text" || this.type == "textarea") {
            this.placeholder = "Ingrese Información".toUpperCase();
        }
    });

    autocompleteUsadmin();
}

function inicioTabla() {
    jQuery("#tabla_esquemas")
        .jqGrid({
            datatype: "json",
            url: "json_esquemas.php",
            colNames: ["NOMBRE", "DESCRIPCION", "POR DEFECTO", "COLOR", "URL ACCESO"],
            colModel: [
                {
                    name: "nombre",
                    index: "e.nombre",
                    align: "left",
                    search: true,
                    searchoptions: { sopt: ["cn"] },
                    editable: false,
                    editrules: { required: true, custom: true, custom_func: nombreEsquemacheck },
                    formoptions: { elmprefix: " (*)" },
                    formatter: function (cellvalue, options, rowObject) {
                        return cellvalue.toUpperCase();
                    }
                },
                {
                    name: "descripcion",
                    index: "e.descripcion",
                    frozen: true,
                    align: "left",
                    search: true,
                    searchoptions: { sopt: ["cn"] },
                    autowidth: true,
                    editable: false,
                    edittype: "textarea",
                    editrules: { required: true },
                    formoptions: { elmprefix: " (*)" },
                },
                {
                    name: "por_defecto",
                    index: "e.por_defecto",
                    frozen: true,
                    align: "left",
                    formatter: function (cellvalue, options, rowObject) {
                        return `<input id="por_defecto_${rowObject.id_esquema}" type='checkbox' ${cellvalue == "t" ? "checked" : ""}>`;
                    }
                },
                {
                    name: "color",
                    index: "color",
                    align: "center",
                    formatter: function (cellvalue, options, rowObject) {
                        return `<input id="color_${options.rowId}" type="color" value="${cellvalue}">`;
                    }
                },
                {
                    name: "urlesquema",
                    index: "urlesquema",
                    frozen: "true",
                    align: "center",
                    formatter: function (cellvalue, options, rowObject) {
                        return `<button class="btn btn-link" onclick="return getUrlEsquema('${rowObject.nombre}')"><span class="glyphicon glyphicon-duplicate"></span> Copiar URL</button>`;
                    }
                }
            ],
            rownumbers: true,
            rowNum: 10,
            autowidth: true,
            shrinkToFit: true,
            height: "auto",
            pager: jQuery("#pager_esquemas"),
            sortname: "por_defecto desc,id_esquema asc",
            sortorder: "",
            caption: "Esquemas",
            viewrecords: true,
            gridComplete: function () {
                let ids = $("#tabla_esquemas").jqGrid("getDataIDs");
                if (animarfila) {
                    $("#" + ids[0]).css({ "animation": "anim_fondo 4s" });
                    animarfila = false;
                }
                ids.forEach(el => {
                    $("#por_defecto_" + el).change(function (e) {
                        if (e.target.checked) {
                            $.ajax({
                                method: "POST",
                                url: "editar_por_defecto.php",
                                data: { por_defecto: "t", id: el },
                                success: function (data) {
                                    recargarTabla();
                                    animarfila = true;
                                    $("#alertify-logs").empty();
                                    alertify.success("Cambio guardado");
                                }
                            });
                        }
                    });
                    $("#color_" + el).change(function (e) {
                        $.ajax({
                            method: "POST",
                            url: "editar_color.php",
                            data: { color: e.target.value, id: el },
                            success: function (data) {
                                //recargarTabla();
                                //animarfila = true;
                                $("#alertify-logs").empty();
                                alertify.success("Cambio guardado");
                            }
                        });
                    });
                });
            }
        })
        .jqGrid('navGrid', '#pager_esquemas', {
            add: false,
            edit: false,
            del: false,
            refresh: true,
            search: true,
            view: false
        }, {
            recreateForm: true,
            closeAfterEdit: true,
            checkOnUpdate: true,
            reloadAfterSubmit: true,
            closeOnEscape: true,
        }, {
            recreateForm: true,
            reloadAfterSubmit: true,
            closeAfterAdd: true,
            checkOnUpdate: true,
            closeOnEscape: true,
            bottominfo: "Todos los campos son obligatorios",
            beforeSubmit: function (postdata, formid) {
                $("#overlay").css({ display: "flex", "justify-content": "center" });
                $("#overlay").append(`<img width="16" src="img/ui-anim_basic_16x16.gif" style="align-self:center"/>`);
                return [true, ""];
            },
            afterComplete: function (response, postdata, formid) {
                $("#overlay").css({ "justify-content": "" });
                $("#overlay").hide();
                $("#overlay").empty();
            }
        }, {
            width: 300,
            closeOnEscape: true
        }, {
            closeOnEscape: true,
            multipleSearch: false,
            overlay: false
        }, {}, {
            closeOnEscape: true
        });
}

function nombreEsquemacheck(value, colname) {
    let re = new RegExp(/^[a-z0-9-_]+$/);
    if (!re.test(value))
        return [false, "El formato del nombre de empresa no es correcto."];
    else
        return [true, ""];
}

function guardarEmpresa() {
    mostrarLoader();
    let form = new FormData($("#crear_empresa_form")[0]);
    form.set("usuario_admin",idUsadmin);
    $.ajax({
        type: "POST",
        url: "guardar.php",
        data: form,
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
    }).done(function (data) {
        recargarTabla();
        $("#dialog_empresa").dialog("close");
        alertify.success("Empresa creada");
        ocultarLoader();
    }).fail(function (err) {
        ocultarLoader();
    });
}

function duplicarEmpresa() {
    mostrarLoader();
    $("#duplicar")[0].disabled = true;
    $.ajax({
        url: "duplicar.php",
        method: "POST",
        dataType: "json",
        data: { origen: $("#esquema_origen").val(), destino: $("#esquema_destino").val() },
        success: function (data) {
            alertify.alert("<b>Empresa duplicada correctamente.</b>");
            $("#dialog_duplicar").dialog("close");
        },
        error: function (err) {
            alertify.alert("<b>Hubo un problema al duplicar la empresa.</b>");
            $(".alertify-button-ok").css({ 'background': 'red' });
        }
    })
        .always(function () {
            $("#duplicar")[0].disabled = false;
            ocultarLoader();
        });
}

function duplicarMaestrosEmpresa() {
    mostrarLoader();
    $("#duplicar")[0].disabled = true;
    $.ajax({
        url: "duplicar_mestros.php",
        method: "POST",
        dataType: "json",
        data: { origen: $("#esquema_origen").val(), destino: $("#esquema_destino").val() },
        success: function (data) {
            alertify.alert("<b>Empresa duplicada correctamente.</b>");
            $("#dialog_duplicar").dialog("close");
        },
        error: function (err) {
            alertify.alert("<b>Hubo un problema al duplicar la empresa.</b>");
            $(".alertify-button-ok").css({ 'background': 'red' });
        }
    })
        .always(function () {
            $("#duplicar")[0].disabled = false;
            ocultarLoader();
        });
}

function validarFormulario() {
    if ($("#datos_prueba")[0].checked) {
        if ($("#nombre_esquema")[0].checkValidity()) {
            return true;
        }
    } else {
        if ($("#crear_empresa_form")[0].checkValidity()) {
            return true;
        }
    }
    $("#crear_empresa_form_submit").click();
    return false;
}

function validarFormularioDuplicar() {
    if ($("#duplicar_empresa_form")[0].checkValidity()) {
        return true;
    }
    $("#duplicar_empresa_submit").click();
    return false;
}

function recargarTabla() {
    jQuery("#tabla_esquemas").jqGrid("clearGridData");
    jQuery("#tabla_esquemas").jqGrid("setGridParam", {
        page: 1,
    });
    jQuery("#tabla_esquemas").trigger("reloadGrid");
}

function obtenerEsquemas() {
    return $.ajax({
        url: "../esquemas/obtener_esquemas.php",
        dataType: "json",
        method: "GET",
    });
}

function validarAcceso(clave) {
    return $.ajax({
        method: "POST",
        url: "validar_acceso.php",
        data: { clave }
    });
}

function mostrarLoader() {
    $("#overlay").css({ display: "flex", "justify-content": "center" });
    $("#overlay").append(`<img width="16" src="img/ui-anim_basic_16x16.gif" style="align-self:center"/>`);
}
function ocultarLoader() {
    $("#overlay").css({ "justify-content": "" });
    $("#overlay").hide();
    $("#overlay").empty();
}

function validarRuc() {
    var numero = $("#ruc_empresa").val();
    var suma = 0;
    var residuo = 0;
    var pri = false;
    var pub = false;
    var nat = false;
    var numeroProvincias = 22;
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
    var d1 = numero.substr(0, 1);
    var d2 = numero.substr(1, 1);
    var d3 = numero.substr(2, 1);
    var d4 = numero.substr(3, 1);
    var d5 = numero.substr(4, 1);
    var d6 = numero.substr(5, 1);
    var d7 = numero.substr(6, 1);
    var d8 = numero.substr(7, 1);
    var d9 = numero.substr(8, 1);
    var d10 = numero.substr(9, 1);

    if (d3 < 6) {
        nat = true;
        p1 = d1 * 2;
        if (p1 >= 10)
            p1 -= 9;
        p2 = d2 * 1;
        if (p2 >= 10)
            p2 -= 9;
        p3 = d3 * 2;
        if (p3 >= 10)
            p3 -= 9;
        p4 = d4 * 1;
        if (p4 >= 10)
            p4 -= 9;
        p5 = d5 * 2;
        if (p5 >= 10)
            p5 -= 9;
        p6 = d6 * 1;
        if (p6 >= 10)
            p6 -= 9;
        p7 = d7 * 2;
        if (p7 >= 10)
            p7 -= 9;
        p8 = d8 * 1;
        if (p8 >= 10)
            p8 -= 9;
        p9 = d9 * 2;
        if (p9 >= 10)
            p9 -= 9;
        modulo = 10;
    } else if (d3 == 6) {
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
    } else if (d3 == 9) {
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

    var digitoVerificador = residuo == 0 ? 0 : modulo - residuo;

    if (true) {

        var ruc = numero.substr(10, 13);
        var digito3 = numero.substring(2, 3);

        if (ruc == "001") {
            if (digito3 < 6) {
                if (nat == true) {
                    if (digitoVerificador != d10) {
                        alertify.error('El ruc persona natural es incorrecto.');
                        $("#ruc_empresa").val("");
                    } else {
                        alertify.success('El ruc persona natural es correcto.');
                    }
                }
            } else {
                if (digito3 == 6) {
                    if (pub == true) {
                        if (digitoVerificador != d9) {
                            alertify.error('El ruc público es incorrecto.');
                            $("#ruc_empresa").val("");
                        } else {
                            alertify.success('El ruc público es correcto.');
                        }
                    }
                } else {
                    if (digito3 == 9) {
                        if (pri == true) {
                            console.log(digito3);
                            if (digitoVerificador != d10) {
                                alertify.error('El ruc privado es incorrecto.');
                                $("#ruc_empresa").val("");
                            } else {
                                alertify.success('El ruc privado es correcto.');
                            }
                        }
                    } else {
                        if (d3 == 7 || d3 == 8) {

                            alertify.error('El tercer dígito ingresado es inválido');

                        } else {
                            if (numero.substr(10, 3) != '001') {

                                alertify.error('El ruc de la empresa del sector privado debe terminar con 001');

                            }
                        }
                    }
                }
            }
        } else {

            if (numero.length === 13) {
                alertify.error('El ruc es incorrecto.');
                $("#ruc_empresa").val("");
            }
        }
    }
}

function confirmarDuplicar() {
    $("#clave_invalida").hide();
    validarAcceso($("#clave_seguridad").val()).then(function (data) {
        if (data == 0) {
            $("#clave_invalida").show();
            $("#clave_seguridad").focus();
            return;
        } else {
            duplicarEmpresa();
            $("#dialog_validar_acceso").dialog("close");
        }
    });
}

function confirmarDuplicarMaestros() {
    $("#clave_invalida").hide();
    validarAcceso($("#clave_seguridad").val()).then(function (data) {
        if (data == 0) {
            $("#clave_invalida").show();
            $("#clave_seguridad").focus();
            return;
        } else {
            duplicarMaestrosEmpresa();
            $("#dialog_validar_acceso").dialog("close");
        }
    });
}

function getUrlEsquema(esquema) {
    fetch("obtener_url_esquema.php?esquema=" + esquema)
        .then(data => {
            return data.text();
        })
        .then(url => {
            if (!navigator.clipboard) {
                // Clipboard API not available
                return
            }
            navigator.clipboard.writeText(url)
                .then(data => {
                    alertify.success("URL copiada");
                })
                .catch(err => {
                    alertify.error("No se pudo copiar la URL");
                });
        })
}

function autocompleteUsadmin() {
    $("#usuario_admin")[0].addEventListener("input", function (e) {
        if (e.target.value == "") {
            idUsadmin="";
        }
    })
    $("#usuario_admin")
        .autocomplete({
            source: function (request, response) {
                var data = { term: request.term };
                $.get(
                    "obtener_usuarios_admin_public.php",
                    data,
                    response,
                    "json"
                );
            },
            minLength: 1,
            select: function (event, ui) {
                $("#usuario_admin").val(ui.item.nombre);
                idUsadmin=ui.item["id_usuario"];
                return false;
            },
            focus: function (event, ui) {
                return false;
            }
        })
        .data("ui-autocomplete")._renderItem = function (ul, item) {
            return $("<li>")
                .append("<a>" + item["nombre"] + " ("+item["ci_usuario"]+")</a>")
                .appendTo(ul);
        };
}