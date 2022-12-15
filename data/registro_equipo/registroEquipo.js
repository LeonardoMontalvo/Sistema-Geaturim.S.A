$(document).on("ready", inicio);
function evento(e) {
    e.preventDefault();
}
var idRegistro = 0;

var formatoFecha = {
    // showButtonPanel: true,
    // changeMonth: true,
    // changeYear: true,
    dateFormat: "yy-mm-dd" + "  " + getCurrentTime(),
    showAnim: "slide",
};

var formatoFecha1 = {
    // showButtonPanel: true,
    // changeMonth: true,
    // changeYear: true,
    dateFormat: "yy-mm-dd",
    showAnim: "slide",
};

var dialogos = {
    autoOpen: false,
    resizable: false,
    width: 760,
    height: 400,
    modal: true,
};

var dialogo_categoria = {
    autoOpen: false,
    resizable: false,
    width: 250,
    height: 180,
    modal: true,
};

var dialogo_marca = {
    autoOpen: false,
    resizable: false,
    width: 230,
    height: 180,
    modal: true,
};

var dialogo_color = {
    autoOpen: false,
    resizable: false,
    width: 230,
    height: 180,
    modal: true,
};

var dialogo_permisos = {
    autoOpen: false,
    resizable: false,
    width: 420,
    height: 150,
    modal: true,
    position: "center",
    show: "explode",
    hide: "blind",
};

var dialogo_seguro = {
    autoOpen: false,
    resizable: false,
    width: 380,
    height: 150,
    modal: true,
    position: "center",
    show: "explode",
    hide: "blind",
};

function abrirCategoria() {
    $("#categorias").dialog("open");
}

function abrirMarca() {
    $("#marcas").dialog("open");
}

function abrirColor() {
    $("#color").dialog("open");
}

function agregar_categoria() {
    if ($("#nombre_categoria").val() === "") {
        $("#nombre_categoria").focus();
        alertify.error("Imgrese Tipo de Equipo");
    } else {
        $.ajax({
            type: "POST",
            url: "guardar_categoria.php",
            data: "descripcion=" + $("#nombre_categoria").val(),
            success: function (data) {
                var val = data;
                if (val == 1) {
                    $("#nombre_categoria").val("");
                    $("#categoria").load("categorias_combos.php");
                    $("#categorias").dialog("close");
                } else {
                    $("#nombre_categoria").val("");
                    alertify.error("Error.... El tipo de equipo ya existe");
                }
            },
        });
    }
}

function agregar_marca() {
    if ($("#nombre_marca").val() === "") {
        $("#nombre_marca").focus();
        alertify.error("Nombre de la Marca");
    } else {
        $.ajax({
            type: "POST",
            url: "guardar_marca.php",
            data: "nombre_marca=" + $("#nombre_marca").val(),
            success: function (data) {
                var val = data;
                if (val == 1) {
                    $("#nombre_marca").val("");
                    $("#marca").load("marcas_combos.php");
                    $("#marcas").dialog("close");
                } else {
                    $("#nombre_marca").val("");
                    alertify.error("Error.... La marca ya existe");
                }
            },
        });
    }
}

function agregar_color() {
    if ($("#nombre_color").val() === "") {
        $("#nombre_color").focus();
        alertify.error("Nombre del Color");
    } else {
        $.ajax({
            type: "POST",
            url: "guardar_color.php",
            data: "nombre_color=" + $("#nombre_color").val(),
            success: function (data) {
                var val = data;
                if (val == 1) {
                    $("#nombre_color").val("");
                    $("#colores").load("colores_combos.php");
                    $("#color").dialog("close");
                } else {
                    $("#nombre_color").val("");
                    alertify.error("Error.... El color ya existe");
                }
            },
        });
    }
}

function getCurrentTime() {
    var CurrentTime = "";
    try {
        var CurrentDate = new Date();
        var CurrentHours = CurrentDate.getHours();
        var CurrentMinutes = CurrentDate.getMinutes();
        var CurrentAmPm = "A'M'";
        if (CurrentMinutes < 10) {
            CurrentMinutes = "0" + CurrentMinutes;
        }

        if (CurrentHours === 12) {
            CurrentHours = 12;
            CurrentAmPm = " P'M'";
        } else if (CurrentHours === 0) {
            CurrentHours = 12;
            CurrentAmPm = " A'M'";
        } else if (CurrentHours > 12) {
            CurrentHours = CurrentHours - 12;
            CurrentAmPm = " P'M'";
        } else {
            CurrentAmPm = " A'M'";
        }
        CurrentTime = "" + CurrentHours + ":" + CurrentMinutes + CurrentAmPm + "";
    } catch (ex) {
    }
    return CurrentTime;
}

function guardarRegistro() {
    if ($("#txtClienteId").val() === "") {
        $("#txtCliente").focus();
        alertify.error("Ingrese un cliente");
    } else {
        if ($("#categoria").val() === "") {
            $("#categoria").focus();
            alertify.error("Ingrese el tipo de equipo");
        } else {
            if ($("#txtModelo").val() === "") {
                $("#txtModelo").focus();
                alertify.error("Ingrese un modelo");
            } else {
                if ($("#txtSerie").val() === "") {
                    $("#txtSerie").focus();
                    alertify.error("Ingrese la serie");
                } else {
                    if ($("#marca").val() === "") {
                        $("#marca").focus();
                        alertify.error("Ingrese un marca");
                    } else {
                        if ($("#colores").val() === "") {
                            $("#colores").focus();
                            alertify.error("Ingrese una color");
                        } else {
                            $("#btnGuardar")[0].disabled = true;
                            $("#tiporegis").val("g");
                            let formData = new FormData($("#registro_form")[0]);

                            $.ajax({
                                type: "POST",
                                url: "procesosRegistroEquipo.php",
                                data: formData,
                                processData: false,
                                contentType: false,
                                encode: true,
                                success: function (data) {
                                    var val = data;
                                    if (val == 0) {
                                        alertify.alert(
                                            "Datos Agregados Correctamente",
                                            function () {
                                                id = $("#txtRegistro").val();
                                                window.open(
                                                    "../../reportes/reporteRegistro.php?id=" + id
                                                );
                                                location.reload();
                                                //limpiarDatos();
                                                $("#txtRegistro").val(parseInt(id) + 1);
                                            }
                                        );
                                    }
                                    if (val == 1) {
                                        alertify.alert("Error.. durante el proceso");
                                    }
                                },
                            });
                        }
                    }
                }
            }
        }
    }
}

function modificarRegistro(e) {
    if ($("#txtClienteId").val() === "") {
        $("#txtCliente").focus();
        alertify.error("Ingrese un registro");
    } else {
        if ($("#categoria").val() === "") {
            $("#categoria").focus();
            alertify.error("Ingrese el tipo de equipo");
        } else {
            if ($("#txtModelo").val() === "") {
                $("#txtModelo").focus();
                alertify.error("Ingrese un modelo");
            } else {
                if ($("#txtSerie").val() === "") {
                    $("#txtSerie").focus();
                    alertify.error("Ingrese la serie");
                } else {
                    if ($("#marca").val() === "") {
                        $("#marca").focus();
                        alertify.error("Ingrese un marca");
                    } else {
                        if ($("#colores").val() === "") {
                            $("#colores").focus();
                            alertify.error("Ingrese una color");
                        } else {
                            $("#btnModificar")[0].disabled = true;
                            $("#tiporegis").val("m");
                            let formData = new FormData($("#registro_form")[0]);
                            $.ajax({
                                type: "POST",
                                url: "procesosRegistroEquipo.php",
                                data: formData,
                                processData: false,
                                contentType: false,
                                encode: true,

                                success: function (data) {
                                    var val = data;
                                    if (val == 0) {
                                        alertify.alert("Datos Modificados", function () {
                                            $("#txtRegistro").val("");
                                            location.reload();
                                        });
                                    }
                                    if (val == 1) {
                                        alertify.alert("Error.. durante el proceso");
                                    }
                                },
                            });
                        }
                    }
                }
            }
        }
    }
}

function flecha_atras() {
    $("#btnAdelante")[0].disabled = false;
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data:
            "comprobante=" +
            $("#txtRegistro").val() +
            "&tabla=" +
            "registro_equipo" +
            "&id_tabla=" +
            "id_registro" +
            "&tipo=" +
            1,
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#txtRegistro").val(val);
                idRegistro = val;
                var valor = $("#txtRegistro").val();

                ///////////////////llamar proforma flechas primera parte/////
                $("#btnGuardar").attr("disabled", true);
                $("#btnModificar").attr("disabled", true);

                $.getJSON("retornar_registro_equipo.php?com=" + valor, function (data) {
                    if (data[14] != "Activo") {
                        $("#alert_eliminado").css({ display: "" });
                        $("#btnModificar").attr("disabled", true);
                        $("#btnEliminar").attr("disabled", true);
                    } else {
                        $("#alert_eliminado").css({ display: "none" });
                        $("#btnModificar").attr("disabled", false);
                        $("#btnEliminar").attr("disabled", false);
                    }
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 20) {
                            $("#txtClienteId").val(data[i]);
                            $("#txtCliente").val(data[i + 1]);
                            $("#categoria").val(data[i + 2]);
                            $("#txtIngreso").val(data[i + 4]);
                            $("#txtSalida").val(data[i + 5]);
                            $("#txtModelo").val(data[i + 6]);
                            $("#txtSerie").val(data[i + 7]);
                            $("#marca").val(data[i + 8]);
                            $("#colores").val(data[i + 10]);
                            $("#txtObservaciones").val(data[i + 12]);
                            $("#txtAccesorios").val(data[i + 13]);
                            $("#foto_ins_1").attr("src", "fotos_registro_equipos/" + data[i + 15]);
                            $("#foto_ins_2").attr("src", "fotos_registro_equipos/" + data[i + 16]);
                            $("#foto_ins_3").attr("src", "fotos_registro_equipos/" + data[i + 17]);
                            $("#foto_ins_4").attr("src", "fotos_registro_equipos/" + data[i + 18]);
                            $("#foto_ins_5").attr("src", "fotos_registro_equipos/" + data[i + 19]);
                        }
                    }
                });
            } else {
                $("#btnAtras")[0].disabled = true;
                alertify.alert("No hay mas registros posteriores!!");
            }
        },
    });
}

function flecha_siguiente() {
    $("#btnAtras")[0].disabled = false;
    $.ajax({
        type: "POST",
        url: "../../procesos/flechas.php",
        data:
            "comprobante=" +
            $("#txtRegistro").val() +
            "&tabla=" +
            "registro_equipo" +
            "&id_tabla=" +
            "id_registro" +
            "&tipo=" +
            2,
        success: function (data) {
            var val = data;
            if (val != "") {
                $("#txtRegistro").val(val);
                idRegistro = val;
                var valor = $("#txtRegistro").val();

                ///////////////////llamar proforma flechas primera parte/////
                $("#btnGuardar").attr("disabled", true);

                $.getJSON("retornar_registro_equipo.php?com=" + valor, function (data) {
                    if (data[14] != "Activo") {
                        $("#alert_eliminado").css({ display: "" });
                        $("#btnModificar").attr("disabled", true);
                        $("#btnEliminar").attr("disabled", true);
                    } else {
                        $("#alert_eliminado").css({ display: "none" });
                        $("#btnModificar").attr("disabled", false);
                        $("#btnEliminar").attr("disabled", false);
                    }
                    var tama = data.length;
                    if (tama !== 0) {
                        for (var i = 0; i < tama; i = i + 15) {
                            $("#txtClienteId").val(data[i]);
                            $("#txtCliente").val(data[i + 1]);
                            $("#categoria").val(data[i + 2]);
                            $("#txtIngreso").val(data[i + 4]);
                            $("#txtSalida").val(data[i + 5]);
                            $("#txtModelo").val(data[i + 6]);
                            $("#txtSerie").val(data[i + 7]);
                            $("#marca").val(data[i + 8]);
                            $("#colores").val(data[i + 10]);
                            $("#txtObservaciones").val(data[i + 12]);
                            $("#txtAccesorios").val(data[i + 13]);
                        }
                    }
                });
            } else {
                $("#btnAdelante")[0].disabled = true;
                alertify.alert("No hay mas registros superiores!!");
            }
        },
    });
}

function limpiarDatos() {
    $("input").val("");
    $("textarea").val("");
}

function abrirDialogo(e) {
    $("#bRegistros").dialog("open");
    $("#list").trigger("reloadGrid");
}

function limpiar_campo() {
    if ($("#txtCliente").val() === "") {
        $("#txtClienteId").val("");
    }
}
//$(function () {
//    Test = {
//        UpdatePreview: function (obj) {
//            if (!window.FileReader) {
//                // don't know how to proceed to assign src to image tag
//            } else {
//                var reader = new FileReader();
//                var target = null;
//                reader.onload = function (e) {
//                    target = e.target || e.srcElement;
//                    $("#foto_ins_1").prop("src", target.result);
//                    
//                  
//                    
//                    
//                };
//                reader.readAsDataURL(obj.files[0]);
//            }
//        }
//    };
//});
var tmpFile;
function fileValidation_1(obj) {
    var fileInput1 = document.getElementById(obj);
    var filePath = fileInput1.value;
    var allowedExtensions = /(.jpg|.jpeg|.png|.gif)$/i;
    if (!allowedExtensions.exec(filePath)) {
        alertify.alert(
            "Por favor seleccione el archivo acorde a las siguiente extencion jpeg, jpg, png, gif."
        );
        fileInput1.value = "";
        return false;
    } else {

        if (fileInput1.files && fileInput1.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                console.log("fff2");
                //                document.getElementById('foto_ins_1').innerHTML = '<img style="max-width: 70%; max-height: 100%;" src="' + e.target.result + '"/>';
                $("#foto_ins_1").prop("src", e.target.result);
                var formData = new FormData($("#registro_form")[0]);
                console.log(formData);
                formData.append("image", $("input[type=archivo_ins_1]")[0].files[0]);
                console.log("ggg");
                //                $.ajax({
                //                    url: "fileUploadClass.php",
                //                    type: "POST",
                //                    data: formData,
                //                    contentType: false,
                //                    cache: false,
                //                    processData: false,
                //                    success: function (data) {
                //                        tmpFile = data;
                //                    }
                //                });
            };
            reader.readAsDataURL(fileInput1.files[0]);
        }
    }
}

function fileValidation_2(obj) {
    var fileInput2 = document.getElementById(obj);
    var filePath = fileInput2.value;
    var allowedExtensions = /(.jpg|.jpeg|.png|.gif)$/i;
    if (!allowedExtensions.exec(filePath)) {
        alertify.alert(
            "Por favor seleccione el archivo acorde a las siguiente extencion jpeg, jpg, png, gif."
        );
        fileInput2.value = "";
        return false;
    } else {
        if (fileInput2.files && fileInput2.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                //                document.getElementById('foto_ins_2').innerHTML = '<img style="max-width: 70%; max-height: 100%;" src="' + e.target.result + '"/>';
                $("#foto_ins_2").prop("src", e.target.result);
                var formData = new FormData($("#registro_form")[0]);
                formData.append("image", $("input[type=archivo_ins_2]")[0].files[0]);
                //                $.ajax({
                //                   url: "fileUploadClass.php",
                //                    type: "POST",
                //                    data: formData,
                //                    contentType: false,
                //                    cache: false,
                //                    processData: false,
                //                    success: function (data) {
                //                        tmpFile = data;
                //                    }
                //                });
            };
            reader.readAsDataURL(fileInput2.files[0]);
        }
    }
}
function fileValidation_3(obj) {
    var fileInput3 = document.getElementById(obj);
    var filePath = fileInput3.value;
    var allowedExtensions = /(.jpg|.jpeg|.png|.gif)$/i;
    if (!allowedExtensions.exec(filePath)) {
        alertify.alert(
            "Por favor seleccione el archivo acorde a las siguiente extencion jpeg, jpg, png, gif."
        );
        fileInput3.value = "";
        return false;
    } else {
        if (fileInput3.files && fileInput3.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                //                document.getElementById('foto_ins_2').innerHTML = '<img style="max-width: 70%; max-height: 100%;" src="' + e.target.result + '"/>';
                $("#foto_ins_3").prop("src", e.target.result);
                var formData = new FormData($("#registro_form")[0]);
                formData.append("image", $("input[type=archivo_ins_3]")[0].files[0]);
                //                $.ajax({
                //                   url: "fileUploadClass.php",
                //                    type: "POST",
                //                    data: formData,
                //                    contentType: false,
                //                    cache: false,
                //                    processData: false,
                //                    success: function (data) {
                //                        tmpFile = data;
                //                    }
                //                });
            };
            reader.readAsDataURL(fileInput3.files[0]);
        }
    }
}
function fileValidation_4(obj) {
    var fileInput4 = document.getElementById(obj);
    var filePath = fileInput4.value;
    var allowedExtensions = /(.jpg|.jpeg|.png|.gif)$/i;
    if (!allowedExtensions.exec(filePath)) {
        alertify.alert(
            "Por favor seleccione el archivo acorde a las siguiente extencion jpeg, jpg, png, gif."
        );
        fileInput4.value = "";
        return false;
    } else {
        if (fileInput4.files && fileInput4.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                //                document.getElementById('foto_ins_2').innerHTML = '<img style="max-width: 70%; max-height: 100%;" src="' + e.target.result + '"/>';
                $("#foto_ins_4").prop("src", e.target.result);
                var formData = new FormData($("#registro_form")[0]);
                formData.append("image", $("input[type=archivo_ins_4]")[0].files[0]);
                //                $.ajax({
                //                    url: "fileUploadClass.php",
                //                    type: "POST",
                //                    data: formData,
                //                    contentType: false,
                //                    cache: false,
                //                    processData: false,
                //                    success: function (data) {
                //                        tmpFile = data;
                //                    }
                //                });
            };
            reader.readAsDataURL(fileInput4.files[0]);
        }
    }
}
function fileValidation_5(obj) {
    var fileInput5 = document.getElementById(obj);
    var filePath = fileInput5.value;
    var allowedExtensions = /(.jpg|.jpeg|.png|.gif)$/i;
    if (!allowedExtensions.exec(filePath)) {
        alertify.alert(
            "Por favor seleccione el archivo acorde a las siguiente extencion jpeg, jpg, png, gif."
        );
        fileInput5.value = "";
        return false;
    } else {
        if (fileInput5.files && fileInput5.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                //                document.getElementById('foto_ins_2').innerHTML = '<img style="max-width: 70%; max-height: 100%;" src="' + e.target.result + '"/>';
                $("#foto_ins_5").prop("src", e.target.result);
                var formData = new FormData($("#registro_form")[0]);
                formData.append("image", $("input[type=archivo_ins_5]")[0].files[0]);
                //                $.ajax({
                //                    url: "fileUploadClass.php",
                //                    type: "POST",
                //                    data: formData,
                //                    contentType: false,
                //                    cache: false,
                //                    processData: false,
                //                    success: function (data) {
                //                        tmpFile = data;
                //                    }
                //                });
            };
            reader.readAsDataURL(fileInput5.files[0]);
        }
    }
}
function inicio() {

    /////////////cambiar idioma///////
    $.datepicker.regional["es"] = {
        closeText: "Cerrar",
        prevText: "<Ant",
        nextText: "Sig>",
        currentText: "Hoy",
        monthNames: [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre",
        ],
        monthNamesShort: [
            "Ene",
            "Feb",
            "Mar",
            "Abr",
            "May",
            "Jun",
            "Jul",
            "Ago",
            "Sep",
            "Oct",
            "Nov",
            "Dic",
        ],
        dayNames: [
            "Domingo",
            "Lunes",
            "Martes",
            "Miércoles",
            "Jueves",
            "Viernes",
            "Sábado",
        ],
        dayNamesShort: ["Dom", "Lun", "Mar", "Mié", "Juv", "Vie", "Sáb"],
        dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
        weekHeader: "Sm",
        dateFormat: "dd/mm/yy",
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: "",
    };
    $.datepicker.setDefaults($.datepicker.regional["es"]);

    //////////////////////////////////////
    alertify.set({ delay: 1000 });
    $("#txtCliente").focus();

    $("#btnGuardar").click(function (e) {
        e.preventDefault();
    });

    $("#btnBuscar").click(function (e) {
        e.preventDefault();
    });

    $("#btnModificar").click(function (e) {
        e.preventDefault();
    });

    $("#btnNuevo").click(function (e) {
        location.reload();
    });

    $("#btnAtras").click(function (e) {
        e.preventDefault();
    });
    $("#btnAdelante").click(function (e) {
        e.preventDefault();
    });

    $("#btnImprimir").click(function () {
        $.ajax({
            type: "POST",
            url: "../../procesos/validacion.php",
            data:
                "comprobante=" +
                $("#txtRegistro").val() +
                "&tabla=" +
                "registro_equipo" +
                "&id_tabla=" +
                "id_registro" +
                "&tipo=" +
                1,
            success: function (data) {
                var val = data;
                if (val != "") {
                    window.open(
                        "../../reportes/reporteRegistro.php?id=" + $("#txtRegistro").val()
                    );
                } else {
                    alertify.alert("Ingreso no creado!!");
                }
            },
        });
    });

    $("#bRegistros").dialog(dialogos);
    $("#btnBuscar").on("click", abrirDialogo);
    $("#btnGuardar").on("click", guardarRegistro);
    $("#btnModificar").on("click", modificarRegistro);
    $("#btnAtras").on("click", flecha_atras);
    $("#btnAdelante").on("click", flecha_siguiente);
    $("#btnNuevo").on("click", limpiarDatos);
    $("#txtIngreso").datepicker(formatoFecha);
    $("#txtSalida").datepicker(formatoFecha1);
    $("#btnCategoria").on("click", abrirCategoria);
    $("#btnGuardarCategoria").on("click", agregar_categoria);
    $("#btnMarcas").on("click", abrirMarca);
    $("#btnGuardarMarca").on("click", agregar_marca);
    $("#btnColores").on("click", abrirColor);
    $("#btnGuardarColor").on("click", agregar_color);

    $("#txtCliente").on("keyup", limpiar_campo);

    $("#categorias").dialog(dialogo_categoria);
    $("#marcas").dialog(dialogo_marca);
    $("#color").dialog(dialogo_color);

    //////////////////BUSCADORES////////////////////
    $("#txtCliente")
        .autocomplete({
            source: "busquedaCliente.php",
            minLength: 1,
            focus: function (event, ui) {
                $("#txtCliente").val(ui.item.value);
                $("#txtClienteId").val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
                $("#txtCliente").val(ui.item.value);
                $("#txtClienteId").val(ui.item.label);
                return false;
            },
        })
        .data("ui-autocomplete")._renderItem = function (ul, item) {
            return $("<li>")
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
        };

    // $("#txtTipoEquipo").autocomplete({
    //     source: "busquedaEquipo.php",
    //     minLength: 1,
    //     focus: function(event, ui) {
    //     $("#txtTipoEquipo").val(ui.item.value);
    //     $("#txtTipoEquipoId").val(ui.item.label);
    //     return false;
    //     },
    //     select: function(event, ui) {
    //     $("#txtTipoEquipo").val(ui.item.value);
    //     $("#txtTipoEquipoId").val(ui.item.label);
    //     return false;
    //     }
    //     }).data("ui-autocomplete")._renderItem = function(ul, item) {
    //     return $("<li>")
    //     .append("<a>" + item.value + "</a>")
    //     .appendTo(ul);
    // };

    // $("#txtColor").autocomplete({
    //     source: "busquedaColor.php",
    //     minLength: 1,
    //     focus: function(event, ui) {
    //     $("#txtColor").val(ui.item.value);
    //     $("#txtColorId").val(ui.item.label);
    //     return false;
    //     },
    //     select: function(event, ui) {
    //     $("#txtColor").val(ui.item.value);
    //     $("#txtColorId").val(ui.item.label);
    //     return false;
    //     }
    //     }).data("ui-autocomplete")._renderItem = function(ul, item) {
    //     return $("<li>")
    //     .append("<a>" + item.value + "</a>")
    //     .appendTo(ul);
    // };

    // $("#txtMarca").autocomplete({
    //     source: "busquedaMarca.php",
    //     minLength: 1,
    //     focus: function(event, ui) {
    //     $("#txtMarca").val(ui.item.value);
    //     $("#txtMarcaId").val(ui.item.label);
    //     return false;
    //     },
    //     select: function(event, ui) {
    //     $("#txtMarca").val(ui.item.value);
    //     $("#txtMarcaId").val(ui.item.label);
    //     return false;
    //     }
    //     }).data("ui-autocomplete")._renderItem = function(ul, item) {
    //     return $("<li>")
    //     .append("<a>" + item.value + "</a>")
    //     .appendTo(ul);
    // };

    ////////
    $.ajax({
        type: "POST",
        url: "contadorRegistro.php",
        success: function (data) {
            var val = data;
            $("#txtRegistro").val(val);
        },
    });

    //cargar fechas/////
    $("#txtIngreso")
        .datepicker({
            dateFormat: "yy-mm-dd",
        })
        .datepicker("setDate", "today");

    $("#txtSalida")
        .datepicker({
            dateFormat: "yy-mm-dd",
        })
        .datepicker("setDate", "today");

    jQuery("#list")
        .jqGrid({
            url: "xmlRegistroEquipo.php",
            datatype: "xml",
            colNames: [
                "Nro Registro",
                "Id Cliente",
                "RUC/CI Cliente",
                "Nombres Cliente",
                "Id categoria",
                "Tipo de Equipo",
                "Fecha Ingreso",
                "Fecha Salida",
                "Modelo",
                "Nro. de Serie",
                "Id Marca",
                "Marca",
                "Id color",
                "Nombre Color",
                "Accesosios",
                "Observaciones",
                "Estado",
            ],
            colModel: [
                {
                    name: "txtRegistro",
                    index: "R.id_registro",
                    editable: true,
                    align: "center",
                    width: "100",
                    search: true,
                    frozen: true,
                    searchoptions: { sopt: ["eq"] },
                },
                {
                    name: "txtClienteId",
                    index: "txtClienteId",
                    search: false,
                    editable: false,
                    hidden: true,
                    editrules: { edithidden: false },
                    align: "center",
                    frozen: true,
                    width: 80,
                },
                {
                    name: "txtRUCI",
                    index: "C.identificacion",
                    editable: true,
                    align: "center",
                    width: "150",
                    search: true,
                    frozen: true,
                    searchoptions: { sopt: ["eq"] },
                },
                {
                    name: "txtCliente",
                    index: "C.nombres_cli",
                    editable: true,
                    align: "center",
                    width: "150",
                    search: true,
                    frozen: true,
                    searchoptions: { sopt: ["cn"] },
                },
                {
                    name: "categoria",
                    index: "categoria",
                    search: false,
                    editable: false,
                    hidden: true,
                    editrules: { edithidden: false },
                    align: "center",
                    frozen: true,
                    width: 80,
                },
                {
                    name: "txtTipoEquipo",
                    index: "A.descripcion",
                    editable: true,
                    align: "center",
                    width: "150",
                    search: true,
                    frozen: true,
                    searchoptions: { sopt: ["cn"] },
                },
                {
                    name: "txtIngreso",
                    index: "txtIngreso",
                    editable: true,
                    align: "center",
                    width: "150",
                    search: false,
                    frozen: true,
                },
                {
                    name: "txtSalida",
                    index: "txtSalida",
                    editable: true,
                    align: "center",
                    width: "120",
                    search: false,
                    frozen: true,
                },
                {
                    name: "txtModelo",
                    index: "R.modelo",
                    editable: true,
                    align: "center",
                    width: "150",
                    search: true,
                    frozen: true,
                    searchoptions: { sopt: ["cn"] },
                },
                {
                    name: "txtSerie",
                    index: "R.nro_serie",
                    editable: true,
                    align: "center",
                    width: "150",
                    search: true,
                    frozen: true,
                    searchoptions: { sopt: ["eq"] },
                },
                {
                    name: "marca",
                    index: "marca",
                    search: false,
                    editable: false,
                    hidden: true,
                    editrules: { edithidden: false },
                    align: "center",
                    frozen: true,
                    width: 80,
                },
                {
                    name: "txtMarca",
                    index: "M.nombre_marca",
                    editable: true,
                    align: "center",
                    width: "150",
                    search: true,
                    frozen: true,
                    searchoptions: { sopt: ["cn"] },
                },
                {
                    name: "colores",
                    index: "colores",
                    search: false,
                    editable: false,
                    hidden: true,
                    editrules: { edithidden: false },
                    align: "center",
                    frozen: true,
                    width: 80,
                },
                {
                    name: "txtColor",
                    index: "O.nombre_color",
                    editable: true,
                    align: "center",
                    width: "150",
                    search: true,
                    frozen: true,
                    searchoptions: { sopt: ["cn"] },
                },
                {
                    name: "txtObservaciones",
                    index: "txtObservaciones",
                    editable: true,
                    align: "center",
                    width: "150",
                    search: false,
                    frozen: true,
                },
                {
                    name: "txtAccesorios",
                    index: "txtAccesorios",
                    editable: true,
                    align: "center",
                    width: "150",
                    search: false,
                    frozen: true,
                },
                {
                    name: "estado",
                    index: "estado",
                    editable: true,
                    align: "center",
                    width: "150",
                    search: false,
                    frozen: true,
                    hidden: true
                },
            ],
            rowNum: 10,
            rowList: [10, 20, 30],
            width: 720,
            height: 250,
            pager: jQuery("#pager"),
            sortname: "R.id_registro",
            shrinkToFit: false,
            sortorder: "asc",
            caption: "Lista Registro",
            viewrecords: true,
            gridview: true,
            ondblClickRow: function () {
                var id = jQuery("#list").jqGrid("getGridParam", "selrow");
                idRegistro = id;
                if (id) {
                    var ret = jQuery("#list").jqGrid("getRowData", id);
                    jQuery("#list").jqGrid("GridToForm", id, "#registro_form");

                    $.getJSON("retornar_registro_equipo.php?com=" + id, function (data) {
                        if (data[14] != "Activo") {
                            $("#alert_eliminado").css({ display: "" });
                            $("#btnModificar").attr("disabled", true);
                            $("#btnEliminar").attr("disabled", true);
                        } else {
                            $("#alert_eliminado").css({ display: "none" });
                            $("#btnModificar").attr("disabled", false);
                            $("#btnEliminar").attr("disabled", false);
                        }
                        var tama = data.length;
                        if (tama !== 0) {
                            for (var i = 0; i < tama; i = i + 20) {

                                $("#foto_ins_1").attr("src", "fotos_registro_equipos/" + data[i + 15]);
                                $("#foto_ins_2").attr("src", "fotos_registro_equipos/" + data[i + 16]);
                                $("#foto_ins_3").attr("src", "fotos_registro_equipos/" + data[i + 17]);
                                $("#foto_ins_4").attr("src", "fotos_registro_equipos/" + data[i + 18]);
                                $("#foto_ins_5").attr("src", "fotos_registro_equipos/" + data[i + 19]);
                            }
                        }
                    });
                    $("#bRegistros").dialog("close");
                    $("#btnGuardar").attr("disabled", true);
                } else {
                    alertify.alert("Seleccione un fila");
                }
            },
        })
        .jqGrid(
            "navGrid",
            "#pager",
            {
                add: false,
                edit: false,
                del: false,
                refresh: true,
                search: true,
                view: true,
            },
            {
                recreateForm: true,
                closeAfterEdit: true,
                checkOnUpdate: true,
                reloadAfterSubmit: true,
                closeOnEscape: true,
            },
            {
                reloadAfterSubmit: true,
                closeAfterAdd: true,
                checkOnUpdate: true,
                closeOnEscape: true,
                bottominfo: "Todos los campos son obligatorios son obligatorios",
            },
            {
                width: 300,
                closeOnEscape: true,
            },
            {
                sopt: ["eq", "bw"],
                multipleSearch: false,
                overlay: false,
            },
            {
                closeOnEscape: true,
                width: 400,
            },
            {
                closeOnEscape: true,
            }
        );
    jQuery("#list").jqGrid("navButtonAdd", "#pager", {
        caption: "Modificar",
        onClickButton: function () {
            var id = jQuery("#list").jqGrid("getGridParam", "selrow");
            if (id) {
                var ret = jQuery("#list").jqGrid("getRowData", id);
                jQuery("#list").jqGrid("GridToForm", id, "#registro_form");
                $("#bRegistros").dialog("close");
                $("#btnGuardar").attr("disabled", true);
            } else {
                alertify.alert("Seleccione un fila");
            }
        },
    });

    jQuery("#list").jqGrid("navButtonAdd", "#pager", {
        caption: "PDF",
        onClickButton: function () {
            var id = jQuery("#list").jqGrid("getGridParam", "selrow");
            if (id) {
                var ret = jQuery("#list").jqGrid("getRowData", id);
                window.open(
                    "../reportes/reportes/reporteRegistro.php?id=" + ret.txtRegistro
                );
            } else {
                alertify.alert("Seleccione un fila");
            }
        },
    });
    /////////////////////
    $("#clave_permiso").dialog(dialogo_permisos);
    $("#seguro").dialog(dialogo_seguro);

    $("#btnEliminar").click(eliminar_registros);
    $("#btnAcceder").click(validar_acceso);
    $("#btnSalir").click(cancelar);
    $("#btnAceptar").click(aceptar);

    function cancelar(e) {
        e.preventDefault();
        cerrarDialogosPermiso();
    }

    function aceptar(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "eliminar_registro.php",
            data: "id_registro=" + idRegistro,
            dataType: "json",
            success: function (data) {
                var val = data;
                if (val == 0) {
                    alertify.alert(
                        `<b>Registro eliminado Correctamente.</b>`,
                        function (e) {
                            location.reload();
                        }
                    );
                } else {
                    alertify.alert(
                        `<b>Error.. No se pudo eliminar el registro.</b>`,
                        function (e) {
                            cerrarDialogosPermiso();
                        }
                    );
                    $("#alertify-ok").css("background-color", "red");
                }
            },
        });
    }

    function eliminar_registros(e) {
        e.preventDefault();
        if (idRegistro === 0) {
            alertify.error("Seleccione un registro");
        } else {
            $("#clave_permiso").dialog("open");
        }
    }

    function validar_acceso(e) {
        e.preventDefault();
        if ($("#clave").val() == "") {
            $("#clave").focus();
            alertify.error("Ingrese la clave");
        } else {
            $.ajax({
                url: "../../procesos/validar_acceso.php",
                type: "POST",
                data: "clave=" + $("#clave").val(),
                success: function (data) {
                    var val = data;
                    if (val == 0) {
                        $("#clave").val("");
                        $("#clave").focus();
                        alertify.error(
                            "Error... La clave es incorrecta ingrese nuevamente"
                        );
                    } else {
                        if (val == 1) {
                            $("#seguro").dialog("open");
                        }
                    }
                },
            });
        }
    }

    function cerrarDialogosPermiso() {
        $("#seguro").dialog("close");
        $("#clave_permiso").dialog("close");
        $("#clave").val("");
    }

    $("#dialog_form_cliente").dialog({
        modal: true,
        width: window.innerWidth - 200,
        height: window.innerHeight - 150,
        minHeight: 600,
        minHeight: 600,
        autoOpen: false,
        title: "REGISTRAR CLIENTE"
    });

    addCliente();

    $("#btnClientes").click(function (e) {
        $("#dialog_form_cliente").dialog("open")
    });
}

function addCliente() {
    $.getScript("../clientes/clientes_ui_util/clientes.js", function () {
        let cmpAddCliente = new AddCliente();
        cmpAddCliente.contenedor = $("#form_cliente");
        cmpAddCliente.onGuardar = function (data) {
            if (!!data) {
                $("#dialog_form_cliente").dialog("close")
                buscarCliente(data).done(function (data) {
                    console.log(data);
                    $("#txtCliente").val(data[0].value);
                    $("#txtClienteId").val(data[0].label);
                });

            }
        };
        cmpAddCliente.init();
    });
}

function buscarCliente(term) {
    return $.ajax({
        url: "busquedaCliente.php",
        dataType: "json",
        method: "GET",
        data: { term: term }
    });
}