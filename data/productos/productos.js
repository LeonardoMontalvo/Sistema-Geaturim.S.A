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
var dialogos_promo = {
    autoOpen: false,
    resizable: false,
    width: 860,
    height: 350,
    modal: true
};
var dialogos = {
    autoOpen: false,
    resizable: false,
    width: 860,
    height: 350,
    modal: true
};
var dialogos_categoria = {
    autoOpen: false,
    resizable: false,
    width: 230,
    height: 180,
    modal: true
};
var dialogos_marca = {
    autoOpen: false,
    resizable: false,
    width: 230,
    height: 180,
    modal: true
};
var dialogos_generico = {
    autoOpen: false,
    resizable: false,
    width: 230,
    height: 180,
    modal: true
};
var dialogos_aplicacion = {
    autoOpen: false,
    resizable: false,
    width: 230,
    height: 180,
    modal: true
};
var dialogos_proveedor = {
    autoOpen: false,
    resizable: false,
    width: 600,
    height: 800,
    modal: true
};
var dialogo3 = {
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

var dialogo5 = {
    autoOpen: false,
    resizable: false,
    width: 500,
    height: 400,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind"
}

var dialogo_cuenta = {
    autoOpen: false,
    resizable: false,
    width: 800,
    height: 400,
    modal: true,
    position: "top",
    show: "explode",
    hide: "blind"
}

function enterpvpmi(e) {
    if (e.which == 13 || e.keyCode == 13) {
        porcentamino();
        return false;
    }
    return true;
}

function enterpvpmayo(e) {
    if (e.which === 13 || e.keyCode === 13) {
        porcentamayo();
        return false;
    }
    return true;
}

function enterpvpnego(e) {
    if (e.which === 13 || e.keyCode === 13) {
        porcentanego();
        return false;
    }
    return true;
}

function porcentamino() {
    if ($("#utilidad_minorista").val() == "") {
        var var_precio_compra = parseFloat($("#precio_compra").val());
        var var_utili_mino = parseFloat($("#precio_minorista").val());
        var var_iva = parseFloat($("#valor_iva_pro").val());
        var multi = var_precio_compra;
        var val = var_utili_mino / multi;
        var entero = val.toFixed(2);
        var resulente = entero * 100 - 100;
        var resulente = resulente.toFixed(2);
        $("#utilidad_minorista").val(resulente);
    } else {
        alertify.error("UTILIDAD MINORISTA: Ya tiene valor")
    }
}

function porcentamayo() {
    if ($("#utilidad_mayorista").val() == "") {
        var var_precio_compra = parseFloat($("#precio_compra").val());
        var var_utili_mino = parseFloat($("#precio_mayorista").val());
        var multi = var_precio_compra
        var val = var_utili_mino / multi;
        var entero = val.toFixed(2);
        var resulente = entero * 100 - 100;
        var resulente = resulente.toFixed(2);
        $("#utilidad_mayorista").val(resulente);
    } else {
        alertify.error("UTILIDAD MAYORISTA: Ya tiene valor")
    }
}

function porcentanego() {
    if ($("#utilidad_negocio").val() == "") {
        var var_precio_compra = parseFloat($("#precio_compra").val());
        var var_utili_mino = parseFloat($("#precio_negocio").val());
        var multi = var_precio_compra
        var val = var_utili_mino / multi;
        var entero = val.toFixed(2);
        var resulente = entero * 100 - 100;
        var resulente = resulente.toFixed(2);
        $("#utilidad_negocio").val(resulente);
    } else {
        alertify.error("UTILIDAD NEGOCIO: Ya tiene valor")
    }
}
function enter(e) {
    if (e.which == 13 || e.keyCode == 13) {
        porcenta();
        return false;
    }
    return true;
}

function enter31(e) {
    if (e.which === 13 || e.keyCode === 13) {
        porcenta3();
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

function porcenta() {
    if ($("#precio_minorista").val() == "") {
        var var_precio_compra = parseFloat($("#precio_compra").val());
        var var_utili_mino = parseFloat($("#utilidad_minorista").val());
        var var_iva = parseFloat($("#valor_iva_pro").val());
        var cal_porcent = (var_utili_mino + 100) / 100;
        var cal_iva = (var_iva + 100) / 100;
        var val = var_precio_compra * cal_porcent;
        var entero = val.toFixed(4);
        $("#precio_minorista").val(entero);
    } else {
        alertify.error("PVP Minorista: Ya tiene valor")
    }
}

function porcenta2() {
    if ($("#precio_mayorista").val() == "") {
        var var_precio_compra_may = parseFloat($("#precio_compra").val());
        var var_utili_mino_may = parseFloat($("#utilidad_mayorista").val());
        var var_iva_may = parseFloat($("#valor_iva_pro").val());
        var cal_porcent_may = (var_utili_mino_may + 100) / 100;
        var cal_iva_may = (var_iva_may + 100) / 100;
        var val_may = var_precio_compra_may * cal_porcent_may;
        var entero_may = val_may.toFixed(4);
        $("#precio_mayorista").val(entero_may);
    } else {
        alertify.error("PVP Mayorista: Ya tiene valor")
    }

}

function porcenta3() {
    if ($("#precio_negocio").val() == "") {
        var var_precio_compra_may = parseFloat($("#precio_compra").val());
        var var_utili_mino_may = parseFloat($("#utilidad_negocio").val());
        var var_iva_may = parseFloat($("#valor_iva_pro").val());
        var cal_porcent_may = (var_utili_mino_may + 100) / 100;
        var cal_iva_may = (var_iva_may + 100) / 100;
        var val_may = var_precio_compra_may * cal_porcent_may;
        var entero_may = val_may.toFixed(4);
        $("#precio_negocio").val(entero_may);
    } else {
        alertify.error("PVP Negocio: Ya tiene valor")
    }
}

function abrirDialogo() {
    $("#productos").dialog("open");
}
function abrirDialogo_promo() {
    $("#buscar_promo").dialog("open");
}

function abrirCategoria() {
    $("#categorias").dialog("open");
}

function abrirMarca() {
    $("#marcas").dialog("open");
}

function abrirGenerico() {
    $("#generico").dialog("open");
}

function abrirAplicacion() {
    $("#aplicacionlist").dialog("open");
}

function abrirCuenta() {
    $("#cuentas").dialog("open");
}

$(function () {
    Test = {
        UpdatePreview: function (obj) {
            if (!window.FileReader) {
                // don't know how to proceed to assign src to image tag
            } else {
                var reader = new FileReader();
                var target = null;
                reader.onload = function (e) {
                    target = e.target || e.srcElement;
                    $("#foto").prop("src", target.result);
                };
                reader.readAsDataURL(obj.files[0]);
            }
        }
    };
});
function guardar_producto() {
    if ($("#cod_prod").val() === "") {
        $("#cod_prod").focus();
        alertify.error("Indique un Código");
    } else {
        if ($("#cod_barras").val() === "") {
            $("#cod_barras").focus();
            alertify.error("Indique un Código");
        } else {
            if ($("#nombre_art").val() === "") {
                $("#nombre_art").focus();
                alertify.error("Nombre del producto");
            } else {
                if ($("#iva").val() === "") {
                    $("#iva").focus();
                    alertify.error("Seleccione una opción");
                } else {
                    if ($("#precio_compra").val() === "") {
                        $("#precio_compra").focus();
                        alertify.error("Indique un precio");
                    } else {
                        if ($("#series").val() === "") {
                            $("#series").focus();
                            alertify.error("Seleccione una opción");
                        } else {
                            if ($("#precio_minorista").val() === "") {
                                $("#precio_minorista").focus();
                                alertify.error("Ingrese precio minorista");
                            } else {
                                if ($("#precio_mayorista").val() === "") {
                                    $("#precio_mayorista").focus();
                                    alertify.error("Ingrese precio mayorista");
                                } else {
                                    if ($("#fecha_creacion").val() === "") {
                                        $("#fecha_creacion").focus();
                                        alertify.error("Indique una fecha");
                                    } else {
                                        if ($("#idcontable").val() === "") {
                                            $("#btnCuenta").focus();
                                            alertify.error("Selecione una Cuenta Contable");
                                        } else {
                                            $("#btnGuardar").attr("disabled", true);
                                            $("#productos_form").submit(function (e) {
                                                var formObj = $(this);
                                                var formURL = formObj.attr("action");
                                                if (window.FormData !== undefined) {
                                                    var formData = new FormData(this);
                                                    formURL = formURL;
                                                    $.ajax({
                                                        url: "guardar_productos.php",
                                                        type: "POST",
                                                        data: formData,
                                                        mimeType: "multipart/form-data",
                                                        contentType: false,
                                                        cache: false,
                                                        processData: false,
                                                        success: function (data, textStatus, jqXHR) {
                                                            var res = data;
                                                            if (res == 1) {
                                                                alertify.success('Datos Agregados Correctamente');
                                                                setTimeout(function () {
                                                                    location.reload();
                                                                }, 1000);
                                                            } else {
                                                                alertify.error("Error..... Datos no Guardados");
                                                            }
                                                        },
                                                        error: function (jqXHR, textStatus, errorThrown) {
                                                        }
                                                    });
                                                    e.preventDefault();
                                                } else {
                                                    var iframeId = "unique" + (new Date().getTime());
                                                    var iframe = $('<iframe src="javascript:false;" name="' + iframeId + '" />');
                                                    iframe.hide();
                                                    formObj.attr("target", iframeId);
                                                    iframe.appendTo("body");
                                                    iframe.load(function (e) {
                                                        var doc = getDoc(iframe[0]);
                                                        var docRoot = doc.body ? doc.body : doc.documentElement;
                                                        var data = docRoot.innerHTML;
                                                    });
                                                }
                                            });
                                            $("#productos_form").submit();
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


function modificar_producto() {
    if ($("#cod_productos").val() === "") {
        alertify.error("Seleccione un producto");
    } else {
        if ($("#cod_prod").val() === "") {
            $("#cod_prod").focus();
            alertify.error("Indique un Código");
        } else {
            if ($("#nombre_art").val() === "") {
                $("#nombre_art").focus();
                alertify.error("Nombre del producto");
            } else {
                if ($("#iva").val() === "") {
                    $("#iva").focus();
                    alertify.error("Seleccione una opción");
                } else {
                    if ($("#precio_compra").val() === "") {
                        $("#precio_compra").focus();
                        alertify.error("Indique un precio");
                    } else {
                        if ($("#series").val() === "") {
                            $("#series").focus();
                            alertify.error("Seleccione una serie");
                        } else {
                            if ($("#precio_minorista").val() === "") {
                                $("#precio_minorista").focus();
                                alertify.error("Ingrese precio minorista");
                            } else {
                                if ($("#precio_mayorista").val() === "") {
                                    $("#precio_mayorista").focus();
                                    alertify.error("Ingrese precio mayorista");
                                } else {
                                    if ($("#fecha_creacion").val() === "") {
                                        $("#fecha_creacion").focus();
                                        alertify.error("Indique una fecha");
                                    } else {
                                        if ($("#idcontable").val() === "") {
                                            alertify.error("Selecione una Cuenta Contable");
                                        } else {
                                            $("#productos_form").submit(function (e) {
                                                var formObj = $(this);
                                                var formURL = formObj.attr("action");
                                                if (window.FormData !== undefined) {
                                                    var formData = new FormData(this);
                                                    formURL = formURL;
                                                    $.ajax({
                                                        url: "modificar_productos.php",
                                                        type: "POST",
                                                        data: formData,
                                                        mimeType: "multipart/form-data",
                                                        contentType: false,
                                                        cache: false,
                                                        processData: false,
                                                        success: function (data, textStatus, jqXHR) {
                                                            var res = data;
                                                            if (res == 1) {
                                                                alertify.success('Datos Modificados Correctamente');
                                                                setTimeout(function () {
                                                                    location.reload();
                                                                }, 1000);
                                                            } else {
                                                                alertify.error("Error..... Datos no Modificados");
                                                            }
                                                        },
                                                        error: function (jqXHR, textStatus, errorThrown) {
                                                        }
                                                    });
                                                    e.preventDefault();
                                                } else {
                                                    var iframeId = "unique" + (new Date().getTime());
                                                    var iframe = $('<iframe src="javascript:false;" name="' + iframeId + '" />');
                                                    iframe.hide();
                                                    formObj.attr("target", iframeId);
                                                    iframe.appendTo("body");
                                                    iframe.load(function (e) {
                                                        var doc = getDoc(iframe[0]);
                                                        var docRoot = doc.body ? doc.body : doc.documentElement;
                                                        var data = docRoot.innerHTML;
                                                    });
                                                }
                                            });
                                            $("#productos_form").submit();
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

function eliminar_productos() {
    if ($("#cod_productos").val() === "") {
        alertify.error("Seleccione un producto");
    } else {
        $("#clave_permiso").dialog("open");
    }
}

function validar_acceso() {
    if ($("#clave").val() == "") {
        $("#clave").focus();
        alertify.error("Ingrese la clave");
    } else {
        $.ajax({
            url: '../../procesos/validar_acceso.php',
            type: 'POST',
            data: "clave=" + $("#clave").val(),
            success: function (data) {
                var val = data;
                if (val == 0) {
                    $("#clave").val("");
                    $("#clave").focus();
                    alertify.error("Error... La clave es incorrecta, ingrese nuevamente");
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

    if ($("#id_promociones_modulo").val() != "") {
        $.ajax({
            type: "POST",
            url: "eliminar_promocion.php",
            data: "id_promociones_modulo=" + $("#id_promociones_modulo").val(),
            success: function (data) {
                var val = data;
                if (val == 1) {
                    alertify.error('Error... El Producto tiene movimientos en el sistema');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                } else {
                    alertify.success(' Eliminado Correctamente');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }
            }
        });

    } else {
        $.ajax({
            type: "POST",
            url: "eliminar_productos.php",
            data: "cod_productos=" + $("#cod_productos").val(),
            success: function (data) {
                var val = data;
                if (val == 1) {
                    alertify.error('Error... El Producto tiene movimientos en el sistema');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                } else {
                    alertify.success('Producto Eliminado Correctamente');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }
            }
        });
    }
}

function activar() {
    $.ajax({
        type: "POST",
        url: "activar_productos.php",
        data: "cod_productos=" + $("#cod_productos").val(),
        success: function (data) {
            var val = data;
            if (val == 1) {
                alertify.success('Producto Activado Correctamente');
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

function nuevo_producto() {
    location.reload();
}
function modificar_producto_promo() {

    if ($("#cod_producto_p").val() != "") {
        if ($("#id_promocion_pro").val() != "") {

            if ($("#cantidad_promocion").val() == "") {
                $("#cantidad_promocion").focus();
                alertify.error("Debe ingresar cantidad para promoción");
            } else {
                $("#btnGuardarum").attr("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "modificar_productos_promo.php",
                    data: "id_promocion=" + $("#cod_producto_p").val() + "&id_promocion_pro=" + $("#id_promocion_pro").val() + "&promocion_pro=" + $("#promocion_pro").val() + "&id_promociones_modulo=" + $("#id_promociones_modulo").val() + "&cantidad_promocion=" + $("#cantidad_promocion").val() + "&pvp_promocion=" + $("#pvp_promocion").val(),
                    success: function (data) {
                        var val = data;
                        if (val == 1) {
                            alertify.alert("Mdificado correctamente", function () {

                                location.reload();

                            });
                        } else
                            if (val == 2) {
                                alertify.error("NO TIENE ASIGNADO A NINGUN PRODUCTO");
                                location.reload();
                            }
                    }
                });
            }
        }
    } else {
        alertify.error("DEBE BUSCAR UN PRODUCTO PROMOCION")
    }
}
function eliminar_promocion() {

    if ($("#id_promociones_modulo").val() === "") {
        alertify.error("Seleccione una Promoción");
    } else {
        $("#clave_permiso").dialog("open");
    }
}
function guardar_producto_promo() {
    console.log("si entro");
    if ($("#stock").val() == "0.00" || $("#stock").val() == "" || $("#stock").val() == "0") {
        $("#stock").focus();

        alertify.error("Debe buscar un producto e ingresar cantidad para promoción?");
        $("#stock").focus();
        $("#stock").select();


    } else {
        if ($("#cod_productos").val() != "") {
            if ($("#id_promocion_pro").val() == "") {
                alertify.error("DEBE BUSCAR UN PRODUCTO");
            } else {
                if ($("#cantidad_promocion").val() == "") {
                    $("#cantidad_promocion").focus();
                    alertify.error("Debe ingresar cantidad para promoción");
                } else {
                    if ($("#pvp_promocion").val() == "") {
                        $("#pvp_promocion").val("0.00")
                        $("#pvp_promocion").focus();
                        alertify.success(" Opcional ingresar precio minorista");
                    } else {
                        var codigo_articulo = ($("#cod_productos").val());
                        var codigo_articulo_promo = ($("#id_promocion_pro").val());
                        $.ajax({
                            type: "POST",
                            url: "comparar_producto_articulo.php",
                            data: "codigo=" + codigo_articulo + "&id_promocion_pro=" + codigo_articulo_promo,
                            success: function (data) {
                                var val = data;
                                if (val != 0) {
                                    alertify.error("Error.... El articulo ya tiene promoción");
                                } else {
                                    $.ajax({
                                        type: "POST",
                                        url: "guardar_productos_promo.php",
                                        data: "id_promocion=" + $("#cod_producto_p").val() + "&id_promocion_pro=" + $("#id_promocion_pro").val() + "&promocion_pro=" + $("#promocion_pro").val() + "&cod_productos=" + $("#cod_productos").val() + "&cantidad_promocion=" + $("#cantidad_promocion").val() + "&pvp_promocion=" + $("#pvp_promocion").val(),
                                        success: function (data) {
                                            var val = data;
                                            if (val == 1) {
                                                $("#btnGuardarum").attr("disabled", true);
                                                alertify.alert("Guardado correctamente", function () {

                                                    location.reload();

                                                });
                                            } else
                                                if (val == 2) {
                                                    alertify.error("NO TIENE ASIGNADO A NINGUN PRODUCTO");
                                                    location.reload();
                                                }
                                        }
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
function agregar_categoria() {
    if ($("#nombre_categoria").val() == "") {
        $("#nombre_categoria").focus();
        alertify.error("Nombre Categoria");
    } else {
        $.ajax({
            type: "POST",
            url: "guardar_categoria.php",
            data: "nombre_categoria=" + $("#nombre_categoria").val(),
            success: function (data) {
                var val = data;
                if (val == 1) {
                    $("#nombre_categoria").val("");
                    $("#categoria").load("categorias_combos.php");
                    $("#categorias").dialog("close");
                } else {
                    $("#nombre_categoria").val("");
                    alertify.error("Error.... La categoría ya existe");
                }
            }
        });
    }
}

function agregar_marca() {
    if ($("#nombre_marca").val() == "") {
        $("#nombre_marca").focus();
        alertify.error("Nombre Laboratorio");
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
                    alertify.error("Error.... El Laboratorio ya existe");
                }
            }
        });
    }
}

function agregar_generico() {
    if ($("#nombre_generico").val() == "") {
        $("#nombre_generico").focus();
        alertify.error("Nombre Genèrico");
    } else {
        $.ajax({
            type: "POST",
            url: "guardar_generico.php",
            data: "nombre_generico=" + $("#nombre_generico").val(),
            success: function (data) {
                var val = data;
                if (val == 1) {
                    $("#nombre_generico").val("");
                    $("#generico").load("generico_combos.php");
                    $("#generico").dialog("close");
                } else {
                    $("#nombre_generico").val("");
                    alertify.error("Error.... El Nombre ya existe");
                }
            }
        });
    }
}

function agregar_aplicacion() {
    if ($("#nombre_aplicacion").val() == "") {
        $("#nombre_aplicacion").focus();
        alertify.error("Nombre Aplicaciòn");
    } else {
        $.ajax({
            type: "POST",
            url: "guardar_aplicacion.php",
            data: "nombre_aplicacion=" + $("#nombre_aplicacion").val(),
            success: function (data) {
                var val = data;
                if (val == 1) {
                    $("#nombre_aplicacion").val("");
                    $("#aplicacion").load("aplicacion_combos.php");
                    $("#aplicacion").dialog("close");
                } else {
                    $("#nombre_aplicacion").val("");
                    alertify.error("Error.... El Nombre ya existe");
                }
            }
        });
    }
}

function actualizar_Proveedor() {
    $("#proveedor").load("proveedor_combos.php");
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
function limpiar_campo1() {
    if ($("#promocion_pro").val() == "") {
        $("#promocion_codigo").val("");
        $("#promocion_cod_barras").val("");
    }
}
function limpiar_campo2() {
    if ($("#promocion_codigo").val() == "") {
        $("#promocion_pro").val("");
        $("#promocion_cod_barras").val("");
    }
}
function limpiar_campo3() {
    if ($("#promocion_cod_barras").val() == "") {
        $("#promocion_codigo").val("");
        $("#promocion_pro").val("");
    }
}
function inicio() {

    $("#bien_servicio").on("change", function () {
        var x = document.getElementById("bien_servicio").selectedIndex;

        console.log(x);
        if (x == '0') {
            $("#inven_si").prop("selected", true);
        } else if (x == '1') {
            $("#inven_no").prop("selected", true);
        }

    })
    $("#promocion_cod_barras").autocomplete({
        source: "buscar_productos_cod_barras.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#promocion_cod_barras").val(ui.item.value);
            $("#id_promocion_pro").val(ui.item.cod_producto);
            $("#promocion_pro").val(ui.item.articulo);
            $("#promocion_codigo").val(ui.item.codigo);
            return false;
        },
        select: function (event, ui) {
            $("#promocion_cod_barras").val(ui.item.value);
            $("#id_promocion_pro").val(ui.item.cod_producto);
            $("#promocion_pro").val(ui.item.articulo);
            $("#promocion_codigo").val(ui.item.codigo);
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };
    $("#promocion_codigo").autocomplete({
        source: "buscar_productos_codigo.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#promocion_codigo").val(ui.item.value);
            $("#id_promocion_pro").val(ui.item.cod_producto);
            $("#promocion_cod_barras").val(ui.item.cod_barras);
            $("#promocion_pro").val(ui.item.articulo);
            return false;
        },
        select: function (event, ui) {
            $("#promocion_codigo").val(ui.item.value);
            $("#id_promocion_pro").val(ui.item.cod_producto);
            $("#promocion_cod_barras").val(ui.item.cod_barras);
            $("#promocion_pro").val(ui.item.articulo);
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };
    $("#promocion_pro").autocomplete({
        source: "buscar_productos_promocion.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#promocion_pro").val(ui.item.value);
            $("#id_promocion_pro").val(ui.item.cod_producto);
            $("#promocion_cod_barras").val(ui.item.codigo);
            $("#promocion_codigo").val(ui.item.cod_barras);
            return false;
        },
        select: function (event, ui) {
            $("#promocion_pro").val(ui.item.value);
            $("#id_promocion_pro").val(ui.item.cod_producto);
            $("#promocion_cod_barras").val(ui.item.codigo);
            $("#promocion_codigo").val(ui.item.cod_barras);
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };
    //////////////////////////////7
    $("#iva").change(function () {
        if ($("#iva").val() == "1") {

            $("#tarifa").val("12%");
            $("#tarifa").attr("readOnly", false);
        } else {
            if ($("#iva").val() == "4") {

                $("#tarifa").val("1");
                $("#tarifa").attr("readOnly", false);
            }
        }
    });
    ////////////////////////////////////
    $("#categoria").autocomplete({
        source: "buscar_categoria.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#categoria").val(ui.item.value);
            $("#id_categoria").val(ui.item.id_categoria);
            return false;
        },
        select: function (event, ui) {
            $("#categoria").val(ui.item.value);
            $("#id_categoria").val(ui.item.id_categoria);
            return false;
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };
    /////////////////////////////////////////////
    $("#aplicacion").autocomplete({
        source: "buscar_aplicacion.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#aplicacion").val(ui.item.value);
            $("#id_aplicacion").val(ui.item.id_aplicacion);
            return false;
        },
        select: function (event, ui) {
            $("#aplicacion").val(ui.item.value);
            $("#id_aplicacion").val(ui.item.id_aplicacion);
            return false;
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };
    /////////////////////////////////////////////////////////////7
    $("#marca").autocomplete({
        source: "buscar_marca.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#marca").val(ui.item.value);
            $("#id_marca").val(ui.item.id_marca);
            return false;
        },
        select: function (event, ui) {
            $("#marca").val(ui.item.value);
            $("#id_marca").val(ui.item.id_marca);
            return false;
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };
    /////////////////////////////////////////////////////7
    $("#modelo").autocomplete({
        source: "buscar_generico.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#modelo").val(ui.item.value);
            $("#id_modelo").val(ui.item.id_modelo);
            return false;
        },
        select: function (event, ui) {
            $("#modelo").val(ui.item.value);
            $("#id_modelo").val(ui.item.id_modelo);
            return false;
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };
    //////////////////////////////////////
    function getDoc(frame) {
        var doc = null;
        try {
            if (frame.contentWindow) {
                doc = frame.contentWindow.document;
            }
        } catch (err) {
        }
        if (doc) {
            return doc;
        }
        try {
            doc = frame.contentDocument ? frame.contentDocument : frame.document;
        } catch (err) {

            doc = frame.document;
        }
        return doc;
    }

    $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
    });
    alertify.set({ delay: 4000 });
    $("#cod_prod").focus();
    $.ajax({
        type: "POST",
        url: "extraer_cuenta_producto.php",
        data: "",
        success: function (data) {
            var val = data;
            if (val != "") {
                var vec = val.split("/");
                $("#idcontable").val(vec[0]);
                $("#ccontable").val(vec[1] + "  -  " + vec[2]);
            }
        }
    });
    /////////////cambiar idioma///////
    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);
    $("#cod_prod").change(function () {
        $.ajax({
            type: "POST",
            url: "comparar_codigo.php",
            data: "codigo=" + $("#cod_prod").val(),
            success: function (data) {
                var val = data;
                if (val == 1) {
                    $("#cod_prod").val("");
                    $("#cod_prod").focus();
                    alertify.error("Error... El código ya existe");
                }
            }
        });
    });
    $("#cod_barras").change(function () {
        $.ajax({
            type: "POST",
            url: "comparar_codigo2.php",
            data: "codigo=" + $("#cod_barras").val(),
            success: function (data) {
                var val = data;
                if (val == 1) {
                    $("#cod_barras").val("");
                    $("#cod_barras").focus();
                    alertify.error("Error... El código de barras ya existe");
                }
            }
        });
    });
    //    $("#promocion_pro").change(function () {
    //        $.ajax({
    //            type: "POST",
    //            url: "comparar_producto_promo.php",
    //            data: "codigo=" + $("#id_promocion_pro").val(),
    //            success: function (data) {
    //                var val = data;
    //                if (val == 1) {
    //                    $("#promocion_pro").val("");
    //                    $("#promocion_codigo").val("");
    //                    $("#promocion_cod_barras").val("");
    //                    alertify.error("Error... ");
    //                }
    //            }
    //        });
    //    });

    $("#utilidad_minorista").attr("maxlength", "10");
    $("#utilidad_mayorista").attr("maxlength", "10");
    $("#precio_minorista").attr("maxlength", "10");
    $("#precio_mayorista").attr("maxlength", "10");
    inputmaskDecimal("#precio_compra", false, 4);
    inputmaskDecimal("#precio_minorista", false, 4);
    inputmaskDecimal("#precio_mayorista", false, 4);
    inputmaskDecimal("#utilidad_minorista", false, 4);
    inputmaskDecimal("#utilidad_mayorista", false, 4);
    inputmaskDecimal("#precio_negocio", false, 4);
    inputmaskDecimal("#stock", false, 2);
    $("#utilidad_minorista").keypress(Valida_punto);
    $("#utilidad_mayorista").keypress(Valida_punto);
    $("#descuento").keypress(ValidNum);
    $("#maximo").keypress(ValidNum);
    $("#maximo").attr("maxlength", "5");
    $("#minimo").keypress(ValidNum);
    $("#minimo").attr("maxlength", "5");
    $("#promocion_pro").on("keyup", limpiar_campo1);
    $("#promocion_codigo").on("keyup", limpiar_campo2);
    $("#promocion_cod_barras").on("keyup", limpiar_campo3);
    $("#btnCategoria").click(function (e) {
        e.preventDefault();
    });
    $("#btnMarca").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardarum").click(function (e) {
        e.preventDefault();
    });
    $("#btnModificarum").click(function (e) {
        e.preventDefault();
    });
    $("#btnGenerico").click(function (e) {
        e.preventDefault();
    });
    $("#btnAplicacion").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardarCategoria").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardarMarca").click(function (e) {
        e.preventDefault();
    });
    $("#btnActualizar").click(function (e) {
        e.preventDefault();
    });
    $("#btnGuardar").click(function (e) {
        e.preventDefault();
    });
    $("#btnModificar").click(function (e) {
        e.preventDefault();
    });
    $("#btnEliminar").click(function (e) {
        e.preventDefault();
    });
    $("#btnBuscar").click(function (e) {
        e.preventDefault();
    });
    $("#btnBuscar_promo").click(function (e) {
        e.preventDefault();
    });
    $("#btnAnularum").click(function (e) {
        e.preventDefault();
    });
    $("#btnNuevo").click(function (e) {
        e.preventDefault();
    });
    $("#btnNuevo").click(function (e) {
        e.preventDefault();
    });
    $("#btnCuenta").click(function (e) {
        e.preventDefault();
    });
    $("#btnAnularum").on("click", eliminar_promocion);
    $("#btnGuardarum").on("click", guardar_producto_promo);
    $("#btnGuardarCategoria").on("click", agregar_categoria);
    $("#btnGuardarMarca").on("click", agregar_marca);
    $("#btnGuardarGenerico").on("click", agregar_generico);
    $("#btnGuardarAplicacion").on("click", agregar_aplicacion);
    $("#btnModificarum").on("click", modificar_producto_promo);
    $("#btnGuardar").on("click", guardar_producto);
    $("#btnModificar").on("click", modificar_producto);
    $("#btnNuevo").on("click", nuevo_producto);
    $("#btnCategoria").on("click", abrirCategoria);
    $("#btnMarca").on("click", abrirMarca);
    $("#btnActualizar").on("click", actualizar_Proveedor);
    $("#btnBuscar").on("click", abrirDialogo);
    $("#btnBuscar_promo").on("click", abrirDialogo_promo);
    $("#btnEliminar").on("click", eliminar_productos);
    $("#btnActivar").on("click", activar);
    $("#btnAceptar").on("click", aceptar);
    $("#btnSalir").on("click", cancelar);
    $("#btnAcceder").on("click", validar_acceso);
    $("#btnCancelar").on("click", cancelar_acceso);
    $("#btnCuenta").on("click", abrirCuenta);
    $("#btnMarcas").on("click", abrirMarca);
    $("#btnGenerico").on("click", abrirGenerico);
    $("#btnAplicacion").on("click", abrirAplicacion);
    $("#utilidad_minorista").on("keypress", enter);
    $("#utilidad_mayorista").on("keypress", enter2);
    $("#utilidad_negocio").on("keypress", enter31);
    $("#precio_minorista").on("keypress", enterpvpmi);
    $("#precio_mayorista").on("keypress", enterpvpmayo);
    $("#precio_negocio").on("keypress", enterpvpnego);
    $("#btnEliminar").attr("disabled", "disabled");
    $("#btnActivar").attr("disabled", "disabled");
    $("#buscar_promo").dialog(dialogos_promo);
    $("#productos").dialog(dialogos);
    $("#categorias").dialog(dialogos_categoria);
    $("#marcas").dialog(dialogos_marca);
    $("#generico").dialog(dialogos_generico);
    $("#aplicacionlist").dialog(dialogos_aplicacion);
    $("#proveedores").dialog(dialogos_proveedor);
    $("#clave_permiso").dialog(dialogo3);
    $("#seguro").dialog(dialogo4);
    $("#cuentas").dialog(dialogo_cuenta);
    $("#fecha_creacion").datepicker({
        dateFormat: 'yy-mm-dd'
    }).datepicker('setDate', 'today');
    ////////////cambio evento/////////////
    $("#iva").change(function () {
        if ($("#iva").val() == "Si") {
            $("#incluye").val("Si");
            $("#incluye").attr("readOnly", false);
        } else {
            if ($("#iva").val() == "No") {
                $("#incluye").val("No");
                $("#incluye").attr("readOnly", true);
            }
        }
    });
    /////////////////////////////////////
    $("#precio_minorista").keyup(function () {
        if ($("#precio_compra").val() == "") {
            $("#precio_minorista").val("");
            $("#precio_compra").focus();
            alertify.error("Error... Ingrese precio compra");
        } else {
            if ($("#precio_minorista").val() == "") {
                $("#utilidad_minorista").val("");
            }
        }
    });
    $("#precio_mayorista").keyup(function () {
        if ($("#precio_compra").val() == "") {
            $("#precio_mayorista").val("");
            $("#precio_compra").focus();
            alertify.error("Error... Ingrese precio compra");
        } else {
            if ($("#precio_mayorista").val() == "") {
                $("#utilidad_mayorista").val("");
            }
        }
    });
    jQuery("#list").jqGrid({
        url: 'datos_productos.php',
        datatype: 'xml',
        colNames: ['ID', 'CÓDIGO', 'CÓDIGO BARRAS', 'ARTICULO', 'IVA', 'SERIES', 'PRECIO COMPRA', 'UTILIDAD MINORISTA', 'PRECIO MINORISTA', 'UTILIDAD MAYORISTA', 'PRECIO MAYORISTA', 'GENERICO', 'CATEGORÍA', 'DESCUENTO', 'STOCK', 'ID USUARIO', 'MÌNIMO', 'MÀXIMO', 'FECHA COMPRA', 'MARCA', 'APLICACION', 'ESTADO', 'INVENTARIABLE', 'IMAGEN', '', 'BODEGA', 'INCLUYE IVA', 'UTILIDAD NEGOCIO', 'PRECIO NEGOCIO', 'ID PLAN CUENTAS', 'CUENTA CONTABLE', 'NOMBRE PROVEEDOR', 'PROVEEEDOR', 'CANTIDAD DESCUENTO', 'BIEN / SERVICIO', 'CANTIDAD MAYORISTA', 'CANTIDAD NEGOCIO'],
        colModel: [
            { name: 'cod_productos', index: 'cod_productos', editable: true, align: 'center', width: '60', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'cod_prod', index: 'cod_prod', editable: true, align: 'center', width: '120', search: false, frozen: true, formoptions: { elmsuffix: " (*)" }, editrules: { required: true } },
            { name: 'cod_barras', index: 'cod_barras', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: { elmsuffix: " (*)" }, editrules: { required: true } },
            { name: 'nombre_art', index: 'nombre_art', editable: true, align: 'center', width: '180', search: true, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'iva', index: 'iva', editable: true, align: 'center', width: '50', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'series', index: 'series', editable: true, align: 'center', width: '50', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'precio_compra', index: 'precio_compra', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'utilidad_minorista', index: 'utilidad_minorista', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'precio_minorista', index: 'precio_minorista', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'utilidad_mayorista', index: 'utilidad_mayorista', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'precio_mayorista', index: 'precio_mayorista', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'categoria', index: 'categoria', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'marca', index: 'marca', editable: true, align: 'center', width: '180', search: true, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'descuento', index: 'descuento', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'stock', index: 'stock', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'id_usuario', index: 'id_usuario', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'minimo', index: 'minimo', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'maximo', index: 'maximo', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'fecha_creacion', index: 'fecha_creacion', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'modelo', index: 'modelo', editable: true, align: 'center', width: '180', search: true, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'aplicacion', index: 'aplicacion', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'vendible', index: 'vendible', editable: true, align: 'center', hidden: true, width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'inventario', index: 'inventario', editable: true, align: 'center', hidden: true, width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'imagen', index: 'imagen', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'bodegas', index: 'bodegas', hidden: true, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'descripcion', index: 'descripcion', hidden: false, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'incluye', index: 'incluye', hidden: false, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'utilidad_negocio', index: 'utilidad_mayorista', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'precio_negocio', index: 'precio_negocio', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'idcontable', index: 'incluye', hidden: true, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'ccontable', index: 'incluye', hidden: false, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'nombre_proveedor', index: 'incluye', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'proveedor', index: 'incluye', hidden: true, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'cantidad_descuento', index: 'incluye', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'bien_servicio', index: 'incluye', hidden: true, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'cantidad_mayorista', index: 'cantidad_mayorista', hidden: false, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'cantidad_negocio', index: 'cantidad_negocio', hidden: false, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
        ],
        rowNum: 10,
        width: 830,
        height: 200,
        rowList: [10, 20, 30],
        pager: jQuery('#pager'),
        sortname: 'cod_productos',
        shrinkToFit: false,
        sortorder: 'asc',
        caption: 'Lista de Productos',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list").jqGrid('getGridParam', 'selrow');
            var ret = jQuery("#list").jqGrid('getRowData', id);
            $("#foto").attr("src", "fotos_productos/" + ret.imagen);
            console.log("ID PROVEEDOR: " + ret.id_proveedor);
            $("#btnGuardar").attr("disabled", true);
            document.getElementById("cod_prod").readOnly = true;
            if (id) {
                var valor = ret.cod_productos;
                $.getJSON('retornar_productos.php?com=' + valor, function (data) {
                    var tama = data.length;
                    t = data[7];
                    if (tama !== 0) {
                        jQuery("#list").jqGrid('GridToForm', id, "#productos_form");
                        for (var i = 0; i < tama; i = i + (tama + 1)) {

                            $("#id_modelo").val(data[i]);
                            $("#modelo").val(data[i + 1]);
                            $("#id_categoria").val(data[i + 2]);
                            $("#categoria").val(data[i + 3]);
                            $("#id_marca").val(data[i + 4]);
                            $("#marca").val(data[i + 5]);
                            $("#id_aplicacion").val(data[i + 6]);
                            $("#aplicacion").val(data[i + 7]);
                            $("#iva").val(data[i + 8]).change();
                            $("#tarifa").val(data[i + 9]).change();
                        }
                    }
                });
                $("#proveedor").val(ret.id_proveedor).change();
                $("#productos").dialog("close");
            } else {
                alertify.alert("Seleccione una Factura");
            }

            if (ret.vendible == "Pasivo") {
                $("#btnEliminar").attr("disabled", "disabled");
                $("#btnModificar").attr("disabled", "disabled");
                $("#btnActivar").attr("disabled", false);
            } else {
                $("#btnActivar").attr("disabled", "disabled");
                $("#btnModificar").attr("disabled", false);
                $("#btnEliminar").attr("disabled", false);
            }
        }
    }).jqGrid('navGrid', '#pager',
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
        }
    );
    jQuery("#list").jqGrid('navButtonAdd', '#pager', {
        caption: "Añadir",
        onClickButton: function () {
            var id = jQuery("#list").jqGrid('getGridParam', 'selrow');
            if (id) {
                jQuery('#list').jqGrid('restoreRow', id);
                var ret = jQuery("#list").jqGrid('getRowData', id);
                $("#foto").attr("src", "fotos_productos/" + ret.imagen);
                jQuery("#list").jqGrid('GridToForm', id, "#productos_form");
                $("#btnGuardar").attr("disabled", true);
                document.getElementById("cod_prod").readOnly = true;
                $("#productos").dialog("close");
            } else {
                alertify.alert("Seleccione un fila");
            }
        }
    });
    $(window).bind('resize', function () {
        jQuery("#list2").setGridWidth($('#pager2').width());
    }).trigger('resize');
    jQuery("#list2").jqGrid({
        url: 'xmlPlanCuentas.php',
        datatype: 'xml',
        colNames: ['Cod. Cuenta', 'Descripcion', 'Cuenta'],
        colModel: [
            { name: 'id_plan_cuentas', index: 'id_plan_cuentas', editable: true, align: 'left', width: '120', search: true, frozen: true, formoptions: { elmsuffix: " (*)" }, editrules: { required: true } },
            { name: 'descripcion', index: 'descripcion', editable: true, align: 'left', width: '490', search: true, frozen: true, formoptions: { elmsuffix: " (*)" }, editrules: { required: true } },
            { name: 'cuenta', index: 'cuenta', editable: true, align: 'left', width: '120', search: true, frozen: true, formoptions: { elmsuffix: " (*)" }, editrules: { required: true } }
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        height: 255,
        pager: jQuery('#pager2'),
        sortname: 'cuenta',
        shrinkToFit: false,
        sortordezr: 'asc',
        caption: 'Plan de Cuentas',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list2").jqGrid('getGridParam', 'selrow');
            jQuery('#list2').jqGrid('restoreRow', id);
            var ret = jQuery("#list2").jqGrid('getRowData', id);
            var ccuenta = jQuery("#list2").jqGrid('getCell', id, 0) + "  -  " + jQuery("#list2").jqGrid('getCell', id, 1);
            $("#idcontable").val(id);
            $("#ccontable").val(ccuenta);
            document.getElementById("idcontable").readOnly = true;
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
    jQuery("#list_promocion").jqGrid({

        url: 'datos_productos_promo.php',
        datatype: 'xml',
        colNames: ['', 'ID PROMOCION', 'ID PRODUCTO', 'NOMBRE ARTICULO', 'ARTICULO PROMOCION', 'POR CADA', 'COD_PRODUCTO_PROMO', 'CANTIDAD PROMO', 'PVP PROMO'],
        colModel: [
            { name: 'myac', width: 50, fixed: true, sortable: false, resize: false, hidden: true, formatter: 'actions', formatoptions: { keys: false, delbutton: true, editbutton: false } },
            { name: 'id_promociones', index: 'id_promociones', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'center', frozen: true, width: 3 },
            { name: 'id_promocion_pro', index: 'id_promocion_pro', editable: false, search: false, hidden: true, editrules: { required: true }, align: 'center', frozen: true, width: 50 },
            { name: 'promocion_pro', index: 'promocion_pro', editable: false, frozen: true, editrules: { required: true }, align: 'left', width: 80 },
            { name: 'nombre_producto_promo', index: 'nombre_producto_promo', editable: false, frozen: true, editrules: { required: true }, align: 'left', width: 80 },
            { name: 'cantidad_promo', index: 'cantidad_promo', editable: false, frozen: true, editrules: { required: true }, align: 'left', width: 30 },
            { name: 'cod_producto_promo', index: 'cod_producto_promo', editable: false, frozen: true, hidden: true, editrules: { required: true }, align: 'left', width: 30 },
            { name: 'cantidad_para_promo', index: 'cantidad_para_promo', editable: false, frozen: true, hidden: false, editrules: { required: true }, align: 'left', width: 30 },
            { name: 'pvp_promo', index: 'pvp_promo', editable: false, frozen: true, hidden: false, editrules: { required: true }, align: 'left', width: 30 }

        ],
        rowNum: 30,
        width: 700,
        height: 200,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager_promocion'),
        sortname: 'id_promociones',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'clientArray',
        shrinkToFit: true,
        editoptions: {

            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {
                console.log("entroooaww111");
                var id = jQuery("#list_promocion").jqGrid('getGridParam', 'selrow');
                jQuery('#list_promocion').jqGrid('restoreRow', id);
                var ret = jQuery("#list_promocion").jqGrid('getRowData', id);
                var fil = jQuery("#list_promocion").jqGrid("getRowData");
                var su = jQuery("#list_promocion").jqGrid('delRowData', rowid);
                if (su === true) {
                    rp_ge.processing = true;
                    $(".ui-icon-closethick").trigger('click');
                }
                return true;
            },
            processing: true
        },
        afterSaveCell: function (rowid, name, val, iRow, iCol) {
            console.log("entroooaww");
            var id = jQuery("#list_promocion").jqGrid('getGridParam', 'selrow');
            jQuery('#list_promocion').jqGrid('restoreRow', id);
            var ret = jQuery("#list_promocion").jqGrid('getRowData', id);
        },
    });
    jQuery("#list_promo").jqGrid({

        url: 'datos_productos_promo.php',
        datatype: 'xml',
        colNames: ['', 'ID PROMOCION', 'ID PRODUCTO', 'NOMBRE ARTICULO', 'ARTICULO PROMOCION', 'POR CADA ', 'COD_PRODUCTO_PROMO BUSCAR', 'CANTIDAD DESCUENTO', 'PVP PROMO', 'COD_BARRAS', 'CODIGO'],
        colModel: [
            { name: 'myac', width: 50, fixed: true, sortable: false, resize: false, hidden: true, formatter: 'actions', formatoptions: { keys: false, delbutton: true, editbutton: false } },
            { name: 'id_promociones', index: 'id_promociones', editable: false, search: false, hidden: true, editrules: { edithidden: false }, align: 'center', frozen: true, width: 3 },
            { name: 'cod_productos', index: 'cod_productos', editable: false, search: false, hidden: true, editrules: { required: true }, align: 'center', frozen: true, width: 50 },
            { name: 'articulo', index: 'articulo', editable: false, frozen: true, editrules: { required: true }, align: 'left', width: 50 },
            { name: 'nombre_producto_promo', index: 'nombre_producto_promo', editable: false, frozen: true, editrules: { required: true }, align: 'left', width: 50 },
            { name: 'cantidad_promo', index: 'cantidad_promo', editable: false, frozen: true, editrules: { required: true }, align: 'left', width: 20 },
            { name: 'cod_producto_promo', index: 'cod_producto_promo', editable: false, frozen: true, hidden: true, editrules: { required: true }, align: 'left', width: 20 },
            { name: 'cantidad_para_promo', index: 'cantidad_para_promo', editable: false, frozen: true, hidden: false, editrules: { required: true }, align: 'left', width: 20 },
            { name: 'pvp_promo', index: 'pvp_promo', editable: false, frozen: true, hidden: false, editrules: { required: true }, align: 'left', width: 20 },
            { name: 'promocion_cod_barras', index: 'promocion_cod_barras', editable: false, frozen: true, hidden: true, editrules: { required: true }, align: 'left', width: 20 },
            { name: 'promocion_codigo', index: 'promocion_codigo', editable: false, frozen: true, hidden: true, editrules: { required: true }, align: 'left', width: 20 }
        ],
        rowNum: 30,
        width: 700,
        height: 200,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager_promo'),
        sortname: 'id_promociones',
        sortorder: 'asc',
        viewrecords: true,
        cellEdit: true,
        cellsubmit: 'clientArray',
        shrinkToFit: true,
        ondblClickRow: function () {
            var id = jQuery("#list_promo").jqGrid('getGridParam', 'selrow');
            jQuery('#list_promo').jqGrid('restoreRow', id);
            jQuery("#list_promo").jqGrid('GridToForm', id, "#rutas_form");
            $("#btnGuardarum").attr("disabled", true);
            var ret = jQuery("#list_promo").jqGrid('getRowData', id);
            $("#promocion_pro").val(ret.nombre_producto_promo);
            $("#id_promocion_pro").val(ret.cod_producto_promo);
            $("#cod_producto_p").val(ret.cod_productos);
            $("#id_promociones_modulo").val(ret.id_promociones);

            $("#cantidad_promocion").val(ret.cantidad_para_promo);
            $("#pvp_promocion").val(ret.pvp_promo);
            //              $("#promocion_cod_barras").val(ret.promocion_cod_barras);
            //            $("#promocion_codigo").val(ret.promocion_codigo);


            $("#buscar_promo").dialog("close");
        },
        afterSaveCell: function (rowid, name, val, iRow, iCol) {
            console.log("entroooaww");
            var id = jQuery("#list_promo").jqGrid('getGridParam', 'selrow');
            jQuery('#list_promo').jqGrid('restoreRow', id);
            var ret = jQuery("#list_promo").jqGrid('getRowData', id);
        },
    });

    $("#input_buscar_articulo_nombre").autocomplete({
        source: "buscar_productos.php",
        minLength: 1,
        focus: function (event, ui) {
            /*   $("#input_buscar_articulo_nombre").val(ui.item.value);
              $("#input_buscar_articulo_nombre_id").val(ui.item.cod_producto); */
            return false;
        },
        select: function (event, ui) {
            $("#input_buscar_articulo_nombre").val(ui.item.value);
            $("#input_buscar_articulo_nombre_id").val(ui.item.cod_producto);
            buscarProductoTabla(ui.item.value, ui.item.cod_producto);
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };

    $("#input_buscar_articulo_nombre").keydown(function (e) {
        if (e.key == "Enter") {
            $.ajax({
                url: "buscar_productos.php?tipo=codigo",
                method: "GET",
                dataType: "json",
                data: { term: $("#input_buscar_articulo_nombre").val() },
                success: function (data) {
                    if (data.length == 1) {
                        buscarProductoTabla(data[0].value, data[0].cod_producto);
                        $( "#input_buscar_articulo_nombre" ).autocomplete( "search", "" );
                    }
                }
            });
        }
    });
}

function cargarProducto(codprod) {
    var id = codprod;
    var ret = jQuery("#list").jqGrid('getRowData', id);
    $("#foto").attr("src", "fotos_productos/" + ret.imagen);
    console.log("ID PROVEEDOR: " + ret.id_proveedor);
    $("#btnGuardar").attr("disabled", true);
    document.getElementById("cod_prod").readOnly = true;
    if (id) {
        var valor = ret.cod_productos;
        $.getJSON('retornar_productos.php?com=' + valor, function (data) {
            var tama = data.length;
            t = data[7];
            if (tama !== 0) {
                jQuery("#list").jqGrid('GridToForm', id, "#productos_form");
                for (var i = 0; i < tama; i = i + (tama + 1)) {

                    $("#id_modelo").val(data[i]);
                    $("#modelo").val(data[i + 1]);
                    $("#id_categoria").val(data[i + 2]);
                    $("#categoria").val(data[i + 3]);
                    $("#id_marca").val(data[i + 4]);
                    $("#marca").val(data[i + 5]);
                    $("#id_aplicacion").val(data[i + 6]);
                    $("#aplicacion").val(data[i + 7]);
                    $("#iva").val(data[i + 8]).change();
                    $("#tarifa").val(data[i + 9]).change();

                    $("#list").trigger("reloadGrid");
                }
            }
        });
        $("#input_buscar_articulo_nombre").val("");
        $("#input_buscar_articulo_nombre_id").val("");
        alertify.success("Producto cargado");

        $("#proveedor").val(ret.id_proveedor).change();
    }

}

function buscarProductoTabla(articulo, idprod) {
    $("#list").jqGrid("clearGridData");
    $("#list").jqGrid("setGridParam", {
        search: true,
        url: `datos_productos.php?searchField=nombre_art&searchString=${articulo}&searchOper=eq`,
        page: 1,
        sidx: "cod_productos",
        sord: "asc",
        rows: 10,
        gridComplete: function () {
            cargarProducto(idprod);
            $("#list").jqGrid("setGridParam", {
                url: `datos_productos.php`,
                page: 1,
                search: false,
                gridComplete: function () {
                }
            });
        }
    });
    $("#list").trigger("reloadGrid");
}
