let idproducto;
let idumedidaproducto
let umedidaproducto;
function initCambiarPvp() {
    initDialogoCamibarPvp();

    $("#pvp_minorista")[0].addEventListener("input", function (e) {
        nuevaUtilidadMinorista();
    });
    $("#util_minorista")[0].addEventListener("input", function (e) {
        nuevoPrecioMinorista();
    });

    $("#pvp_mayorista")[0].addEventListener("input", function (e) {
        nuevaUtilidadMayorista();
    });
    $("#util_mayorista")[0].addEventListener("input", function (e) {
        nuevoPrecioMayorista();
    });

    $("#pvp_negocio")[0].addEventListener("input", function (e) {
        nuevaUtilidadNegocio();
    });
    $("#util_negocio")[0].addEventListener("input", function (e) {
        nuevoPrecioNegocio();
    });
    $("#guardar_nuevos_precios").click(function (e) {
        $("#alertify-logs").empty();
        if (!!idumedidaproducto) {
            cambiarPrecioUmedidaProducto(
                idproducto,
                idumedidaproducto,
                $("#pvp_minorista").val(),
                $("#pvp_mayorista").val(),
                $("#pvp_negocio").val(),
                $("#precio_compra_factura").val()
            );
        } else {
            cambiarPrecioProducto(
                idproducto,
                $("#pvp_minorista").val(),
                $("#pvp_mayorista").val(),
                $("#pvp_negocio").val(),
                $("#util_minorista").val(),
                $("#util_mayorista").val(),
                $("#util_negocio").val(),
                $("#precio_compra_factura").val()
            )
        }
    });
}

function initDialogoCamibarPvp() {
    $("#dialog_cambiar_pvp_producto").dialog({
        modal: true,
        width: 800,
        height: 510,
        minHeight: 600,
        minHeight: 700,
        autoOpen: false,
        title: "CAMBIAR PVP DEL PRODUCTO"
    });
}

function llenarDatosProducto(
    pc,
    pmin,
    pmay,
    pneg,
    umin,
    umay,
    uneg,
    idprod,
    nomprod,
    pcfactura,
    umedidap,
) {
    idproducto = idprod;
    umedidaproducto = umedidap

    pmin = pmin ? Number(pmin) : "";
    pmay = pmay ? Number(pmay) : "";
    pneg = pneg ? Number(pneg) : "";
    umin = umin ? Number(umin) : "";
    umay = umay ? Number(umay) : "";
    uneg = uneg ? Number(uneg) : "";

    $("#pc_actual").text("$" + pc);


    $("#producto_compra").text(nomprod);
    $("#precio_compra_factura").val(pcfactura)

    if (umedidaproducto != "") {
        $("#div_util_actual").hide();
        $("#div_util_nuevo").hide();
        $("#pc_umedida").text(umedidaproducto);
        $("#div_pc_umedida").show();
        obtenerPvpUmedidaProducto(idproducto, umedidap)
            .then(el => {
                idumedidaproducto = el.id_unidad_medida_productos
                $("#pvp_min_actual").text("$" + el.pvpmino);
                $("#pvp_may_actual").text("$" + el.pvpmayo);
                $("#pvp_neg_actual").text("$" + el.pvpnego);
            });

        $("#util_min_actual").text("");
        $("#util_may_actual").text("");
        $("#util_neg_actual").text("");

        $("#pvp_minorista").val("");
        $("#pvp_mayorista").val("");
        $("#pvp_negocio").val("");
    } else {
        $("#pc_umedida").text("");
        $("#div_pc_umedida").hide();

        $("#pvp_min_actual").text("$" + pmin);
        $("#pvp_may_actual").text("$" + pmay);
        $("#pvp_neg_actual").text("$" + pneg);
        $("#util_min_actual").text(umin);
        $("#util_may_actual").text(umay);
        $("#util_neg_actual").text(uneg);

        $("#util_minorista").val(umin);
        $("#util_mayorista").val(umay);
        $("#util_negocio").val(uneg);

        nuevoPrecioMinorista();
        nuevoPrecioMayorista();
        nuevoPrecioNegocio();

        $("#div_util_actual").show();
        $("#div_util_nuevo").show();
    }
}

function nuevoPrecioMinorista() {
    if ($("#util_minorista").val().trim() == "") {
        $("#pvp_minorista").val("");
        return;
    }
    var var_precio_compra = Number($("#precio_compra_factura").val());
    var var_utili_mino = Number($("#util_minorista").val());
    var cal_porcent = (var_utili_mino + 100) / 100;
    var val = var_precio_compra * cal_porcent;
    var entero = val.toFixed(4);
    $("#pvp_minorista").val(entero);
}
function nuevoPrecioMayorista() {
    if ($("#util_mayorista").val().trim() == "") {
        $("#pvp_mayorista").val("");
        return;
    }
    var var_precio_compra = Number($("#precio_compra_factura").val());
    var var_utili_mino = Number($("#util_mayorista").val());
    var cal_porcent = (var_utili_mino + 100) / 100;
    var val = var_precio_compra * cal_porcent;
    var entero = val.toFixed(4);
    $("#pvp_mayorista").val(entero);
}
function nuevoPrecioNegocio() {
    if ($("#util_negocio").val().trim() == "") {
        $("#pvp_negocio").val("");
        return;
    }
    var var_precio_compra = Number($("#precio_compra_factura").val());
    var var_utili_mino = Number($("#util_negocio").val());
    var cal_porcent = (var_utili_mino + 100) / 100;
    var val = var_precio_compra * cal_porcent;
    var entero = val.toFixed(4);
    $("#pvp_negocio").val(entero);
}

function nuevosPreciosUm(pvpmin, pvpmay, pvpneg) {
    $("#pvp_minorista").val(pvpmin);
    $("#pvp_mayorista").val(pvpmay);
    $("#pvp_negocio").val(pvpneg);
}

function nuevaUtilidadMinorista() {
    if ($("#pvp_minorista").val().trim() == "") {
        $("#util_minorista").val("");
        return;
    }
    var var_precio_compra = Number($("#precio_compra_factura").val());
    var var_utili_mino = Number($("#pvp_minorista").val());
    var multi = var_precio_compra;
    var val = var_utili_mino / multi;
    var entero = val.toFixed(2);
    var resulente = entero * 100 - 100;
    var resulente = resulente.toFixed(2);
    $("#util_minorista").val(resulente);
}
function nuevaUtilidadMayorista() {
    if ($("#pvp_mayorista").val().trim() == "") {
        $("#util_mayorista").val("");
        return;
    }
    var var_precio_compra = Number($("#precio_compra_factura").val());
    var var_utili_mino = Number($("#pvp_mayorista").val());
    var multi = var_precio_compra;
    var val = var_utili_mino / multi;
    var entero = val.toFixed(2);
    var resulente = entero * 100 - 100;
    var resulente = resulente.toFixed(2);
    $("#util_mayorista").val(resulente);
}
function nuevaUtilidadNegocio() {
    if ($("#pvp_negocio").val().trim() == "") {
        $("#util_negocio").val("");
        return;
    }
    var var_precio_compra = Number($("#precio_compra_factura").val());
    var var_utili_mino = Number($("#pvp_negocio").val());
    var multi = var_precio_compra;
    var val = var_utili_mino / multi;
    var entero = val.toFixed(2);
    var resulente = entero * 100 - 100;
    var resulente = resulente.toFixed(2);
    $("#util_negocio").val(resulente);
}


function obtenerPvpProducto(idproducto) {
    return $.ajax({
        url: "cambiar_pvp/buscar_pvp_producto.php",
        method: "GET",
        dataType: "json",
        data: {
            id_producto: idproducto
        }
    });
}

function cambiarPrecioProducto(idproducto, pvpmin, pvpmay, pvpneg, utilmin, utilmay, utilneg, pc) {
    $.ajax({
        url: "cambiar_pvp/cambiar_precios_producto.php",
        method: "POST",
        dataType: "json",
        data: {
            id_producto: idproducto,
            pvp_minorista: pvpmin,
            pvp_mayorista: pvpmay,
            pvp_negocio: pvpneg,
            util_minorista: utilmin,
            util_mayorista: utilmay,
            util_negocio: utilneg,
            precio_compra: pc
        }
    })
        .then(el => {
            alertify.success("Nuevos precios guardados correctamente");
            $("#dialog_cambiar_pvp_producto").dialog("close");

            let lcids = $("#list").jqGrid("getDataIDs");
            for (let el of lcids) {
                let row = $("#list").jqGrid("getRowData", el);
                if (row.cod_producto == idproducto) {
                    $(`#btn_cb_pvp_${el}`)[0].classList.remove("btn-danger");
                    $(`#btn_cb_pvp_${el}`)[0].classList.add("btn-default");
                    return;
                }
            }
        })
        .fail(err => {
            alertify.success("Hubo un problema al cambiar los precios");
        });
}
function cambiarPrecioUmedidaProducto(idproducto, idumedidaprod, pvpmin, pvpmay, pvpneg, pc) {
    return $.ajax({
        url: "cambiar_pvp/cambiar_precios_producto_umedida.php",
        method: "POST",
        dataType: "json",
        data: {
            id_producto: idproducto,
            id_umedidia_prducto: idumedidaprod,
            pvp_minorista: pvpmin,
            pvp_mayorista: pvpmay,
            pvp_negocio: pvpneg,
            precio_compra: pc
        }
    })
        .then(el => {
            alertify.success("Nuevos precios guardados correctamente");
            $("#dialog_cambiar_pvp_producto").dialog("close");

            let lcids = $("#list").jqGrid("getDataIDs");
            for (let el of lcids) {
                let row = $("#list").jqGrid("getRowData", el);
                if (row.cod_producto == idproducto) {
                    $(`#btn_cb_pvp_${el}`)[0].classList.remove("btn-danger");
                    $(`#btn_cb_pvp_${el}`)[0].classList.add("btn-default");
                    return;
                }
            }
        })
        .fail(err => {
            alertify.success("Hubo un problema al cambiar los precios");
        });
}

function obtenerPvpUmedidaProducto(idproducto, umedida) {
    return $.ajax({
        url: "cambiar_pvp/buscar_pvp_umedida.php",
        method: "GET",
        dataType: "json",
        data: {
            id_producto: idproducto,
            unidad_medida: umedida
        }
    });
}