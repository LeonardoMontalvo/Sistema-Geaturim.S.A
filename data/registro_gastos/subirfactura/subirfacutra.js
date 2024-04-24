var infofac;
var productosfactura = [];
var productosFactSelec = [];
var prodFactSelConcepto = [];
var registroProduto;
var numserie;
var numautorizacion;
var fechaEmision;
var buscando = false;

$(document).ready(function () {
    $("#btn_subir_xml").click(function (e) {
        $("#facutaxml").click();
    });
    $("#facutaxml").change(function (e) {
        let files = e.target.files;
        let file = files[0];
        productosfactura = productostablafact = [];
        $("#facutaxml").val("");
        if (file.type != "text/xml") {
            alertify.error("Solo puede cargar archivos XML");
            $("#clavefactura").val("");
            $("#facutaxml").val("");
            cargarTablaFac();
            limipiarInfoFactura();
            restoreFormDatosFactura();
            estadoBotonBuscar();
            return;
        }
        subirXmls(file, "file");
    });
    $("#dialog_subir_factura").dialog({
        modal: true,
        width: 800,
        height: (window.screen.height * window.devicePixelRatio) - (window.screen.height * window.devicePixelRatio) * 0.5,
        autoOpen: false,
        title: "CARGAR FACTURA",
        buttons: [
            {
                text: "Ok",
                icon: "ui-icon-heart",
                style: "background:#4CAF50; color:#fff",
                type: "button",
                click: function () {
                    if (!validarProductosTablaCompras()) {
                        alertify.alert('<b>Debe seleccionar el producto del sistema correspondiente para todos los productos de la factura.</b>');
                        return;
                    }
                    productosFactSelec = jQuery('#tabla_subir_fac').jqGrid('getGridParam', 'selarrrow');
                    let val = 0;
                    let codi = "";
                    let codt = "";
                    let tarifavalida = true;

                    productosFactSelec.forEach(el => {
                        //let find = productosfactura.find(p => p.codigoPrincipal == el.split("/_/")[0]);
                        let find = productosfactura[el.split("/_/")[1]];
                        if (!!find) {
                            if (codi != "" && codt != "") {
                                if (Number(codi) != Number(find.impuestos[0].codigo) || Number(codt) != Number(find.impuestos[0].codigoPorcentaje)) {
                                    tarifavalida = false;
                                }
                            }
                            val += +find.precioTotalSinImpuesto;
                            codi = find.impuestos[0].codigo;
                            codt = find.impuestos[0].codigoPorcentaje;
                        }
                    });

                    if (!tarifavalida) {
                        productosFactSelec = [];
                        alertify.alert("Los prouctos seleccionados deben tener la misma tarifa de IVA.");
                        $("#alertify-ok").css({ "background": "red" });
                        return;
                    }

                    $("#valor").val(val.toFixed(2));
                    let seltarifas = $("#tipo_iva")[0].options;
                    seltarifas = Array.from(seltarifas);
                    seltarifas.forEach(el => {
                        if (el.dataset.codimp == codi && el.dataset.codtarifa == codt) {
                            el.selected = true;
                        }
                    });
                    setTimeout(function () {
                        $("#valor").focus();
                    }, 0)
                    $(this).dialog("close");
                }
            }
        ],
        close: function (event, ui) {
            jQuery("#tabla_subir_fac").jqGrid("clearGridData");
        },
        open: function (event, ui) {
            productosFactSelec = [];
            $("#valor").val("");
            inicioTabla();
            cargarTablaFac();
        }
    });

    $("#btn_buscar_clave").click(function () {
        subirXmls($("#clavefactura").val(), 'clave');
    });
    $("#clavefactura").keyup(function (e) {
        if (e.key == "Enter") {
            $("#btn_buscar_clave").click();
        }
    });
    $("#btn_cargar_prods").click(function () {
        if (!infofac) {
            alertify.alert(`<b>No hay factura cargada.</b>`,
                function (e) { });
            return;
        }
        if ($("#id_proveedor").val() == "") {
            alertify.alert(`El proveedor <b><i>${infofac.razonSocial}</i></b> no esta registrado. Por favor registre el proveedor.`,
                function (e) {
                    $("#btnClientes").click();
                });
            return;
        }
        $("#dialog_subir_factura").dialog("open");
    });
})

async function subirXmls(file, tipo) {
    $("#btn_cargar_prods").hide();
    buscando = true;
    estadoBotonBuscar();
    let formdata = new FormData();
    if (tipo == "file") {
        formdata.append("file", file);
    } else if (tipo == "clave") {
        formdata.append("clave", file);
    } else {
        return;
    }
    limipiarInfoFactura();
    try {
        let res = await fetch("../../procesos/obtener_factura_autorizada.php", { method: "POST", body: formdata });
        res = await res.json();
        if (res == -1) {
            alertify.alert("<b>El comprobante no es una factura.</b>");
            $("#alertify-ok").css({ background: "red" });
            buscando = false;
            cargarTablaFac();
            limipiarInfoFactura();
            restoreFormDatosFactura();
            estadoBotonBuscar();
            return;
        }
        infofac = res["infoFac"];
        productosfactura = res["productos"];
        productosfactura = productosfactura.map((el, i) => {
            el["indice"] = i;
            return el;
        });
        numserie = infofac["estab"] + "-" + infofac["ptoEmi"] + "-" + infofac["secuencial"];
        numautorizacion = infofac["claveAcceso"];
        fechaEmision = infofac["fechaEmision"];
        //cargarTablaFac();
        llenarInfoFactura();
        buscando = false;
        estadoBotonBuscar();
        alertify.success("Factura cargada correctamente");
        $("#btn_cargar_prods").show();

        if (tipo == "file") {
            $("#clavefactura").val(infofac["claveAcceso"]);
        }
    } catch (error) {
        alertify.error("No se pudo cargar la factura.");
        console.error(error);
        buscando = false;
        cargarTablaFac();
        limipiarInfoFactura();
        restoreFormDatosFactura();
        estadoBotonBuscar();

        if (tipo == "file") {
            $("#clavefactura").val("");
            $("#facutaxml").val("");
        }
    }
}
function inicioTabla() {
    let lastsel;
    jQuery("#tabla_subir_fac").jqGrid({
        datatype: "local",
        colNames: [
            "Código Principal",
            "Descripción",
            "Cantidad",
            "Precio U.",
            "Descuento",
            "Tarifa IVA"
        ],
        colModel: [
            {
                name: "codigoPrincipal",
                index: "codigoPrincipal",
                width: 100,
                editable: false
            },
            {
                name: "descripcion",
                index: "descripcion",
                width: 280,
                editable: false
            },
            {
                name: "cantidad",
                index: "cantidad",
                width: 80,
                editable: false
            },
            {
                name: "precioUnitario",
                index: "precioUnitario",
                width: 80,
                editable: false
            },
            {
                name: "descuento",
                index: "descuento",
                width: 80,
                editable: false
            },
            {
                name: "impuestos",
                index: "impuestos",
                width: 80,
                editable: false,
                formatter: function (cellvalue, options, rowObject) {
                    let valor = "0.00";
                    cellvalue.forEach(el => {
                        if (el.codigo == 2) {
                            valor = el.tarifa;
                        }
                    });
                    return valor;
                }
            }
        ],
        onSelectRow: function (id) {
            if (id && id !== lastsel) {
                jQuery('#tabla_subir_fac').jqGrid('restoreRow', lastsel);
                jQuery('#tabla_subir_fac').jqGrid('editRow', id, true);
                lastsel = id;
            }
        },
        rowNum: 1000,
        sortname: 'num',
        sortorder: "asc",
        height: '100%',
        width: null,
        shrinkToFit: false,
        cellEdit: true,
        multiselect: true,
        cellsubmit: 'clientArray',
    });
}
function llenarInfoFactura() {
    if (!infofac) {
        return;
    }
    $("#tipo_docu").val("Ruc").trigger("change");
    realonlyFormDatosFactura();
    $("#ruc_ci").autocomplete({
        response: function (event, ui) {
            $("#ruc_ci").autocomplete({
                response: function (event, ui) {

                }
            });
            if (ui.content.length == 0) {
                alertify.alert(`El proveedor <b><i>${infofac.razonSocial}</i></b> no esta registrado. Por favor registre el proveedor.`,
                    function (e) {
                        $("#btnClientes").click();
                    });
                return;
            }
            let item = ui.content[0];
            $("#ruc_ci").val(item.value);
            $("#empresa").val(item.empresa);
            /* $("#correo").val(item.correo); */
            $("#id_proveedor").val(item.id_proveedor);

            $("#ruc_ci").autocomplete("close");
        }
    });
    $("#ruc_ci").autocomplete("search", infofac.ruc);

    $("#factura").val(numserie);
    $("#autorizacion").val(numautorizacion);

    let date = fechaEmision.split("/");
    $("#fecha_emision").val(`${date[2]}-${date[1]}-${date[0]}`);
}
function limipiarInfoFactura() {
    infofac = undefined;
    productosfactura = productosFactSelec = prodFactSelConcepto = [];
    $("#tipo_docu").val("").trigger("change");
    $("#ruc_ci").val("");
    $("#empresa").val("");
    $("#id_proveedor").val("");
    $("#factura").val("");
    $("#autorizacion").val("");
    $("#fecha_emision").val(new Date().toLocaleDateString("fr-CA"));
    $("#list").jqGrid("clearGridData", true);

}
function realonlyFormDatosFactura() {
    $("#factura").css({
        "background": "#42A5F5",
        "font-weight": "bold",
        "color": "black"
    });
    $("#tipo_docu")[0].disabled = true;
    $("#tipo_comprobante")[0].disabled = true;
    $("#factura")[0].readOnly = true;
    $("#autorizacion")[0].readOnly = true;
}
function restoreFormDatosFactura() {
    $("#factura").css({
        "background": "#fff",
        "font-weight": "normal",
        "color": "#555"
    });
    $("#tipo_docu")[0].disabled = false;
    $("#tipo_comprobante")[0].disabled = false;
    $("#factura")[0].readOnly = false;
    $("#autorizacion")[0].readOnly = false;
}
function estadoBotonBuscar() {
    if (buscando) {
        $("#icono_buscar").hide();
        $("#icono_buscando").show();
        $("#btn_buscar_clave")[0].disabled = true;
    } else {
        $("#icono_buscar").show();
        $("#icono_buscando").hide();
        $("#btn_buscar_clave")[0].disabled = false;
    }
}

function cargarTablaFac() {

    llenarTablaFact();
}
function llenarTablaFact() {
    /* let filtro = productosfactura.filter((el,i) => {
        return !prodFactSelConcepto.some(el1 => el1.productos.some(el2 => el2.split("/_/")[0] == el.codigoPrincipal))
    }); */
    let filtro = productosfactura.filter((el, i) => {
        return !prodFactSelConcepto.some(el1 => el1.productos.some(el2 => el2.split("/_/")[1] == i))
    });

    filtro.forEach((el, i) => {
        jQuery("#tabla_subir_fac").jqGrid("addRowData", el.codigoPrincipal + "/_/" + el.indice, el);
    });
    jQuery("#tabla_subir_fac").trigger("reloadGrid");
}



function validarProductosTablaCompras() {
    /* let valido = productostablafact.some(el => !el.cod_productos);
    return !valido; */
    return true;
}


/* function llenarTablaCompras() {
    productosfactura = productosfactura.map(el => {
        let prodt = productostablafact.find(el1 => el1.codigoPrincipal == el.codigoPrincipal);
        el.cod_barras = prodt.codigo_sistema;
        el.codigo = prodt.codigo_sistema;
        el.detalle = prodt.descripcion_sistema;
        el.cod_productos = prodt.cod_productos;
        el.iva_minorista = prodt.iva_minorista;

        return el;
    })
    jQuery("#list").jqGrid("clearGridData");
    productosfactura.forEach(el => {
        let selum = null;

        if ($("#unidadm_" + el.codigoPrincipal)[0].selectedOptions.length > 0) {
            if ($("#unidadm_" + el.codigoPrincipal).val() != "") {
                selum = $("#unidadm_" + el.codigoPrincipal)[0].selectedOptions[0].text;
            }

        }

        let um = "";
        let cantidadum = 0;
        let cantidadfac = Number(el.cantidad);
        let cantidad = 0;
        let iva = "No";
        let impuestoiva = el.impuestos.filter(el1 => el1.codigo == 2)[0];
        let preciou = Number(el.precioUnitario);
        let descuento = Number(el.descuento);
        let preciosinimp = Number(el.precioTotalSinImpuesto);
        if (!!selum) {
            let splitselum = selum.split(" ---- ");
            um = splitselum[0];
            cantidadum = splitselum[1];
        }
        if (cantidadum > 0) {
            preciou = preciou / cantidadum;
            cantidad = cantidadfac * cantidadum;
            descuento = descuento / cantidadum;
        }
        if (Number(impuestoiva.tarifa) > 0) {
            iva = "Si";
        } else {
            iva = "No";
        }
        let descp = (Number(descuento) * 100) / (preciou * Number(el.cantidad));
        descp = Number(descp.toFixed(4));
        let datarow = {
            cod_producto: el.cod_productos,
            codigo: el.codigo,
            detalle: el.detalle,
            cantidad: cantidadfac,
            precio_u: preciou,
            descuento: descp,
            cal_des: descuento,
            total: preciosinimp,
            precio_ux: preciou.toFixed(4),
            descuentox: descp,
            cal_desx: descuento.toFixed(4),
            totalx: preciosinimp.toFixed(4),
            iva: iva,
            incluye: "No",
            precio_v: Number(el.iva_minorista),
            cantidad_unidad: cantidad,
            unidad_medida: um,
        };

        if ($("#sel_centro_c_" + el.codigoPrincipal).val() > 0) {
            datarow["id_centro_costo"] = $("#sel_centro_c_" + el.codigoPrincipal).val();
            datarow["centro_costo"] = $("#sel_centro_c_" + el.codigoPrincipal)[0].options[$("#sel_centro_c_" + el.codigoPrincipal)[0].selectedIndex].text;
        }
        jQuery("#list").jqGrid('addRowData', el.cod_productos, datarow);
    });
    calcularTotales();
}
function calcularTotales() {
    var subtotal0 = 0;
    var subtotal12 = 0;
    var subtotal_total = 0;
    var iva12 = 0;
    var total_total = 0;
    var descu_total = 0;

    // calcular valores
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
        if (dd['iva'] == "Si") {
            // if(dd['incluye'] == "No"){
            subtotal = dd['total'];
            sub1 = subtotal;

            iva1 = sub1 * toFixedDown((calculoIVA / 100), 3);
            subtotal0 = parseFloat(subtotal0) + 0;
            subtotal12 = parseFloat(subtotal12) + parseFloat(sub1);
            subtotal_total = parseFloat(subtotal0) + parseFloat(subtotal12);
            descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);
            iva12 = parseFloat(iva12) + parseFloat(iva1);

            subtotal0 = parseFloat(subtotal0);
            subtotal12 = parseFloat(subtotal12);
            subtotal_total = parseFloat(subtotal_total);
            iva12 = parseFloat(iva12);
            descu_total = parseFloat(descu_total);

        } else {
            if (dd['iva'] == "No") {
                subtotal = dd['total'];
                sub = subtotal;

                subtotal0 = parseFloat(subtotal0) + parseFloat(sub);
                subtotal12 = parseFloat(subtotal12) + 0;
                subtotal_total = parseFloat(subtotal0) + parseFloat(subtotal12);
                iva12 = parseFloat(iva12) + 0;
                descu_total = parseFloat(descu_total) + parseFloat(dd['cal_des']);

                subtotal0 = parseFloat(subtotal0);
                subtotal12 = parseFloat(subtotal12);
                subtotal_total = parseFloat(subtotal_total);
                iva12 = parseFloat(iva12);
                descu_total = parseFloat(descu_total);
            }
        }
    }
    total_total = parseFloat(total_total) + (parseFloat(subtotal0) + parseFloat(subtotal12) + parseFloat(iva12));
    total_total = parseFloat(total_total);

    $("#total_p").val(subtotal0);
    $("#total_p2").val(subtotal12);
    $("#sub").val(subtotal_total);
    $("#iva").val(iva12);
    $("#desc").val(descu_total);
    $("#tot").val(total_total);
    $("#total_px").val(subtotal0.toFixed(2));
    $("#total_p2x").val(subtotal12.toFixed(2));
    $("#subx").val(subtotal_total.toFixed(2));
    $("#ivax").val(iva12.toFixed(2));
    $("#descx").val(descu_total.toFixed(2));
    $("#totx").val(total_total.toFixed(2));
    $("#valor_factura").val(total_total.toFixed(2));
    //$("#codigo_barras").focus();
}
 */