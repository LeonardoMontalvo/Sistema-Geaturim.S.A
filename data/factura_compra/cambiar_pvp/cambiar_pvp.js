let idproducto;
let umedidaproducto;
let preciocomprafac;
let arrpvpumedidaproducto = [];

function initCambiarPvp() {
    initDialogoCamibarPvp();
    initTablaNuevosPrecios();

    $("#pvp_minorista")[0].addEventListener("input", function (e) {
        if ($("#util_minorista").val().trim() != "" && $("#util_minorista").val().trim() > 0) {
            nuevaUtilidadMinorista();
        }

    });
    $("#util_minorista")[0].addEventListener("input", function (e) {
        nuevoPrecioMinorista();
    });

    $("#pvp_mayorista")[0].addEventListener("input", function (e) {
        if ($("#util_mayorista").val().trim() != "" && $("#util_mayorista").val().trim() > 0) {
            nuevaUtilidadMayorista();
        }
    });
    $("#util_mayorista")[0].addEventListener("input", function (e) {
        nuevoPrecioMayorista();
    });

    $("#pvp_negocio")[0].addEventListener("input", function (e) {
        if ($("#util_negocio").val().trim() != "" && $("#util_negocio").val().trim() > 0) {
            nuevaUtilidadNegocio();
        }
    });
    $("#util_negocio")[0].addEventListener("input", function (e) {
        nuevoPrecioNegocio();
    });
}

function guardarNuevosPrecios() {
    $("#alertify-logs").empty();
    if (umedidaproducto == "") {
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
    } else {
        cambiarPrecioUmedidaProducto();
    }
}

function initDialogoCamibarPvp() {
    $("#dialog_cambiar_pvp_producto").dialog({
        modal: true,
        width: 800,
        height: 510,
        minHeight: 600,
        minHeight: 700,
        autoOpen: false,
        title: "CAMBIAR PVP DEL PRODUCTO",
        close: function (event, ui) {
            idproducto = null;
            umedidaproducto = null;
            arrpvpumedidaproducto = [];
        },
        buttons: [
            {
                text: "Guardar Nuevos Precios",
                icon: "ui-icon-disk",
                style: "background:#4CAF50; color:#fff",
                type: "button",
                click: function () {
                    guardarNuevosPrecios();
                }
            }
        ],
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
    umedidaproducto = umedidap;
    preciocomprafac = pcfactura;

    pmin = pmin ? Number(pmin) : "";
    pmay = pmay ? Number(pmay) : "";
    pneg = pneg ? Number(pneg) : "";
    umin = umin ? Number(umin) : "";
    umay = umay ? Number(umay) : "";
    uneg = uneg ? Number(uneg) : "";

    $("#pc_actual").text("$" + pc);
    $("#producto_compra").text(nomprod);
    let pctmp = pcfactura.toFixed(8);
    $("#precio_compra_factura").val(Number(pctmp))

    if (umedidaproducto != "") {
        $("#div_precios_actuales").hide();
        $("#div_precios_actuales_umedida").show();

        $("#div_precios_nuevos").hide();
        $("#div_precios_nuevos_umedida").show();

        generarTablaPvpActualUmedida();
    } else {
        $("#div_precios_actuales").show();
        $("#div_precios_actuales_umedida").hide();

        $("#div_precios_nuevos").show();
        $("#div_precios_nuevos_umedida").hide();

        $("#pvp_min_actual").text("$" + pmin);
        $("#pvp_may_actual").text("$" + pmay);
        $("#pvp_neg_actual").text("$" + pneg);
        $("#util_min_actual").text(umin);
        $("#util_may_actual").text(umay);
        $("#util_neg_actual").text(uneg);

        $("#util_minorista").val(umin);
        $("#util_mayorista").val(umay);
        $("#util_negocio").val(uneg);

        nuevoPrecioMinorista(pmin);
        nuevoPrecioMayorista(pmay);
        nuevoPrecioNegocio(pneg);
    }
}

function nuevoPrecioMinorista(pvpactual) {
    if ($("#util_minorista").val().trim() == "" || $("#util_minorista").val().trim() == 0) {
        $("#pvp_minorista").val(pvpactual);
        return;
    }
    var var_precio_compra = Number($("#precio_compra_factura").val());
    var var_utili_mino = Number($("#util_minorista").val());
    var cal_porcent = (var_utili_mino + 100) / 100;
    var val = var_precio_compra * cal_porcent;
    var entero = val.toFixed(4);
    $("#pvp_minorista").val(entero);
}
function nuevoPrecioMayorista(pvpactual) {
    if ($("#util_mayorista").val().trim() == "" || $("#util_mayorista").val().trim() == 0) {
        $("#pvp_mayorista").val(pvpactual);
        return;
    }
    var var_precio_compra = Number($("#precio_compra_factura").val());
    var var_utili_mino = Number($("#util_mayorista").val());
    var cal_porcent = (var_utili_mino + 100) / 100;
    var val = var_precio_compra * cal_porcent;
    var entero = val.toFixed(4);
    $("#pvp_mayorista").val(entero);
}
function nuevoPrecioNegocio(pvpactual) {
    if ($("#util_negocio").val().trim() == "" || $("#util_negocio").val().trim() == 0) {
        $("#pvp_negocio").val(pvpactual);
        return;
    }
    var var_precio_compra = Number($("#precio_compra_factura").val());
    var var_utili_mino = Number($("#util_negocio").val());
    var cal_porcent = (var_utili_mino + 100) / 100;
    var val = var_precio_compra * cal_porcent;
    var entero = val.toFixed(4);
    $("#pvp_negocio").val(entero);
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

function generarTablaPvpActualUmedida() {
    jQuery("#tabla_nuevos_precios_um").jqGrid("clearGridData");
    jQuery("#tabla_nuevos_precios_um").trigger("reloadGrid");
    let contenedor = $("#precios_actuales_umedida");
    contenedor.empty();
    obtenerPvpUmedidaProducto(idproducto)
        .then(res => {
            res.forEach(el => {
                contenedor.append(`
                    <tr style="border:solid 1px;">
                        <td style="font-weight: bold; border:solid 1px;">${el.descripcion}</td>
                        <td style="text-align: center; border:solid 1px;">$${el.pvpmino}</td>
                        <td style="text-align: center; border:solid 1px;">$${el.pvpmayo}</td>
                        <td style="text-align: center; border:solid 1px;">$${el.pvpnego}</td>
                    <tr>
                `);
                let datarow = {
                    id: el.id_unidad_medida_productos,
                    unidad: el.descripcion,
                    pvp_min: el.pvpmino,
                    pvp_may: el.pvpmayo,
                    pvp_neg: el.pvpnego,
                }
                arrpvpumedidaproducto.push(datarow)
                jQuery("#tabla_nuevos_precios_um").jqGrid('addRowData', el.id_unidad_medida_productos, datarow);
            });
        })
}

function initTablaNuevosPrecios() {
    jQuery("#tabla_nuevos_precios_um").jqGrid({
        datatype: "local",
        colNames: ["ID", "UNIDAD", "PVP MINORISTA", "PVP MAYORISTA", "PVP NEGOCIO"],
        colModel: [
            { name: "id", index: "id", hidden: true },
            {
                name: "unidad", index: "unidad", formatter: function (cellvalue, options, rowObject) {
                    return `<div style="font-size:15px; font-weight:bold">${cellvalue}</div>`;
                }
            },
            {
                name: "pvp_min", index: "pvp_min",
                formatter: function (cellvalue, options, rowObject) {
                    return `
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-usd"></i>
                        </div>
                        <input id="pvp_minorista_um_${options.rowId}" style="background-color: #FFEE58; font-size:14px" class="form-control" type="text" value="${cellvalue}">
                    </div>`;
                },
            },
            {
                name: "pvp_may", index: "pvp_may",
                formatter: function (cellvalue, options, rowObject) {
                    return `
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-usd"></i>
                        </div>
                        <input id="pvp_mayorista_um_${options.rowId}" style="background-color: #FFEE58; font-size:14px" class="form-control" type="text" value="${cellvalue}">
                    </div>`;
                }
            },
            {
                name: "pvp_neg", index: "pvp_neg",
                formatter: function (cellvalue, options, rowObject) {
                    return `
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-usd"></i>
                        </div>
                        <input id="pvp_negocio_um_${options.rowId}" style="background-color: #FFEE58; font-size:14px" class="form-control" type="text" value="${cellvalue}">
                    </div>`;
                }
            }
        ],
        afterInsertRow: function (rowid, rowdata, rowelem) {
            $(`#pvp_minorista_um_${rowid}`)[0].addEventListener("input", function (e) {
                let find = arrpvpumedidaproducto.find(el => el.id == rowid);
                find.pvp_min = e.target.value;
            });
            $(`#pvp_mayorista_um_${rowid}`)[0].addEventListener("input", function (e) {
                let find = arrpvpumedidaproducto.find(el => el.id == rowid);
                find.pvp_may = e.target.value;
            });
            $(`#pvp_negocio_um_${rowid}`)[0].addEventListener("input", function (e) {
                let find = arrpvpumedidaproducto.find(el => el.id == rowid);
                find.pvp_neg = e.target.value;
            });
        },
        width: 725,
        shrinkToFit: false
    });
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

function obtenerPvpUmedidaProducto(idproducto) {
    return $.ajax({
        url: "cambiar_pvp/buscar_pvp_umedida.php",
        method: "GET",
        dataType: "json",
        data: {
            id_producto: idproducto,
        }
    });
}

function cambiarPrecioUmedidaProducto() {
    return $.ajax({
        url: "cambiar_pvp/cambiar_precios_producto_umedida.php",
        method: "POST",
        dataType: "json",
        data: {
            id_producto: idproducto,
            precio_compra: preciocomprafac,
            precios: arrpvpumedidaproducto
        }
    })
        .then(res => {

            let lcids = $("#list").jqGrid("getDataIDs");
            for (let el of lcids) {
                let row = $("#list").jqGrid("getRowData", el);;
                if (row.cod_producto == idproducto) {
                    $(`#btn_cb_pvp_${el}`)[0].classList.remove("btn-danger");
                    $(`#btn_cb_pvp_${el}`)[0].classList.add("btn-default");
                    break;
                }
            }
            alertify.success("Nuevos precios guardados correctamente");
            $("#dialog_cambiar_pvp_producto").dialog("close");
        })
        .fail(err => {
            alertify.success("Hubo un problema al cambiar los precios");
        });
}
