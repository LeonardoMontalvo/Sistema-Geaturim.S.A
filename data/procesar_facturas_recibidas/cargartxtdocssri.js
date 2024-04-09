var refdoc;
var facturasarchivo = [];

$(document).ready(inicio)
function inicio() {
    initTablaDocs();
    leerArchivo();
    leerArchivoZip();
    initDialogInfoFac();
}

function initTablaDocs() {
    $("#tabla_docs").jqGrid({
        dataType: "local",
        colNames: [
            "PROCESAR",
            "COMPROBANTE",
            "SERIE",
            "ID RECEPTOR",
            "RUC EMISOR",
            "RAZÓN SOCIAL EMISOR",
            "FECHA EMI.",
            "AUTORIZACIÓN",
            "IMPORTE",
            "nombre_xml"
        ],
        colModel: [
            {
                name: "act",
                index: "act",
                formatter: function (cellvalue, options, rowObject) {
                    if (cellvalue == undefined) {
                        if (rowObject.comprobante == 'Factura') {
                            let btnfactura = `<button id="btn_fact_${options.rowId}" class="btn-success">FACTURA</button>`;
                            let btngasto = `<button id="btn_gas_${options.rowId}" class="btn-primary">GASTO</button>`;
                            let btngastop = `<button id="btn_gasp_${options.rowId}" class="btn-warning">GASTO P.</button>`;
                            return `<div style="display:flex; justify-content: space-around; margin-top:0.15rem; margin-bottom:0.15rem;">${btnfactura}${btngasto}${btngastop}</div>`;
                        } else if (rowObject.comprobante == 'Comprobante de Retenci?n') {
                            let btnRegistrar = `<button id="btn_reg_ret_${options.rowId}" class="btn-success btn-block">REGISTRAR</button>`;
                            return `<div style="display:flex; justify-content: space-around; margin:0.2rem; font-size:small;">${btnRegistrar}</div>`;
                        } else {
                            return `<div style="text-align:center; color:#000; background:#B0BEC5; opacity:0.8; font-weight:bold;">NO APLICA</div>`;
                        }
                    }
                    return cellvalue;
                },
                width: 250,
                resizable: false
            },
            {
                name: "comprobante",
                index: "comprobante",
                width: 120
            },
            {
                name: "serie",
                index: "serie",
                width: 150,
                align: "center",
                resizable: false,
                formatter: function (cellvalue, options, rowObject) {
                    let btnmostrarf = `<button style="padding:0" type="button" id="btn_show_f_${options.rowId}" class="btn btn-link">${cellvalue}</button>`;
                    let loader = `<img style="display:none;" id="loader_show_f_${options.rowId}" src="../../images/ui-anim_basic_16x16.gif">`;
                    if (rowObject.comprobante == 'Factura') {
                        return `<div style="text-align:center">${btnmostrarf}${loader}</div>`;
                    }
                    return cellvalue;
                }
            },
            {
                name: "id_receptor",
                index: "id_receptor",
                width: 115
            },
            {
                name: "ruc",
                index: "ruc",
                width: 115
            },
            {
                name: "razon_social",
                index: "razon_social"
            },
            {
                name: "fecha_emision",
                index: "fecha_emision",
                width: 90,
                align: "center",
            },
            {
                name: "autorizacion",
                index: "autorizacion"
            },
            {
                name: "importe",
                index: "importe",
                align: "right",
                width: 80
            },
            {
                name: "nombre_xml",
                hidden: true
            }
        ],
        width: (window.innerWidth - 300 < 600) ? 600 : window.innerWidth - 300,
        rownumbers: true,
        height: 300,
        afterInsertRow: function (rowid, rowdata, rowelem) {
            if (rowdata.comprobante == 'Factura') {
                comprobarFactura(rowdata["autorizacion"])
                    .then(res => {
                        if (res == 1) {
                            cambiarFacturaProcesada(rowid);
                        }
                    });
            }
            if (rowdata.comprobante == 'Comprobante de Retenci?n') {
                comprobarFactura(rowdata["autorizacion"], 'comp_ret')
                    .then(res => {
                        if (res == 1) {
                            cambiarFacturaProcesada(rowid);
                        }
                    });
            }

            $("#btn_fact_" + rowid).click(function (e) {
                abrirFacturaCompra(rowdata["autorizacion"], rowid);
            });
            $("#btn_gas_" + rowid).click(function (e) {
                abrirRegistroGasto(rowdata["autorizacion"], rowid);
            });
            $("#btn_gasp_" + rowid).click(function (e) {
                abrirGastoPersonal(rowdata["autorizacion"], rowid);
            });
            $("#btn_reg_ret_" + rowid).click(function (e) {
                abrirRetencionVenta(rowdata["autorizacion"], rowid);
            });

            if (rowdata.comprobante == 'Factura') {
                $("#btn_show_f_" + rowid).click(function (e) {

                    if (!rowdata.nombre_xml) {
                        let clave = rowdata.autorizacion;
                        $("#loader_show_f_" + rowid).show();
                        $("#btn_show_f_" + rowid).hide();
                        consultarFacturaAutorizada(clave)
                            .then(val => {
                                llenarInfoFactura(val);
                                $("#dialog_info_fac").dialog("open");
                            })
                            .finally(() => {
                                $("#loader_show_f_" + rowid).hide();
                                $("#btn_show_f_" + rowid).show();
                            });
                    } else {
                        obtenerXml(rowdata.nombre_xml)
                            .then(blob => {
                                consultarFacturaAutorizada(null, blob)
                                    .then(val => {
                                        llenarInfoFactura(val);
                                        $("#dialog_info_fac").dialog("open");
                                    })
                                    .finally(() => {
                                        $("#loader_show_f_" + rowid).hide();
                                        $("#btn_show_f_" + rowid).show();
                                    });
                            });
                    }
                });


            }

        },
        /*  pager: jQuery('#pager_docs'), */
    })
    /* .jqGrid('navGrid', '#pager_docs', {
        add: false,
        edit: false,
        del: false,
        refresh: true,
        search: false,
        // multipleSearch: true,
        view: false
    }); */
    $(window).off('resize');
    $(window).on('resize', function () {

        $('#tabla_docs').jqGrid('setGridWidth', (window.innerWidth - 300 < 600) ? 600 : window.innerWidth - 300);
    }).trigger('resize');
}

function initDialogInfoFac() {
    $("#dialog_info_fac").dialog({
        autoOpen: false,
        resizable: false,
        width: 700,
        height: 400,
        modal: true,
        position: "center",
        title: "INFORMACIÓN"
    });
}

function leerArchivo() {
    let inputarch = $("#archivo");

    inputarch.change(function (e) {
        let f = e.target.files[0];
        if (!f) {
            return;
        }
        let fd = new FormData();
        fd.append("file", f, f.name);

        $("#tabla_docs").jqGrid("clearGridData");
        $("#load_tabla_docs").show();
        //$("#lui_tabla_docs").show();
        fetch("leercsv.php", {
            method: "POST",
            headers: {
            },
            body: fd
        })
            .then(res => {
                return res.json();
            })
            .then(json => {
                let data = json.filter(el => el["RUC_EMISOR"]);
                data.forEach((el, i) => {
                    let row = {
                        comprobante: el["TIPO_COMPROBANTE"],
                        id_receptor: el["IDENTIFICACION_RECEPTOR"],
                        serie: el["SERIE_COMPROBANTE"],
                        ruc: el["RUC_EMISOR"],
                        razon_social: el["RAZON_SOCIAL_EMISOR"],
                        fecha_emision: el["FECHA_EMISION"],
                        autorizacion: el["CLAVE_ACCESO"],
                        importe: el["IMPORTE_TOTAL"]
                    };
                    facturasarchivo.push(row);
                    $("#tabla_docs").jqGrid("addRowData", i, row);
                });
            })
            .finally(() => {
                $("#load_tabla_docs").hide();
                //$("#lui_tabla_docs").hide();
            });
        inputarch.val("");
    });
}

function leerArchivoZip() {
    $("#archivo_zip").change(function (e) {
        let f = e.target.files[0];
        if (!f) {
            return;
        }

        $("#archivo_zip").val("");
        if (f.type == "application/zip" || f.type == "application/x-zip-compressed") {
            if (f.size > 2 * 1024 * 1024) {
                alert("Solo puede subir archivos de máximo 2MB.");
                return;
            }

            $("#tabla_docs").jqGrid("clearGridData");
            $("#load_tabla_docs").show();

            let fd = new FormData();
            fd.append("file", f, f.name);
            fetch("procesar_zip_xml.php", {
                method: "POST",
                headers: {
                },
                body: fd
            })
                .then(res => {
                    return res.json();
                })
                .then(json => {
                    let data = json.filter(el => el.length > 1);
                    data.forEach((el, i) => {
                        let row = {
                            comprobante: el[1],
                            id_receptor: el[3],
                            serie: el[2],
                            ruc: el[4],
                            razon_social: el[5],
                            fecha_emision: el[6],
                            autorizacion: el[7],
                            importe: el[8],
                            nombre_xml: el[0]
                        };
                        facturasarchivo.push(row);
                        $("#tabla_docs").jqGrid("addRowData", i, row);
                    });
                })
                .finally(() => {
                    $("#load_tabla_docs").hide();
                });
        } else {
            alert("Solo puede subir archivos .zip");
            return;
        }
    });
}

function comprobarFactura(clave, tipo = 'factura') {
    return $.ajax({
        url: "comprobarFactura.php",
        method: "GET",
        dataType: "json",
        data: { clave_acceso: clave, tipo_doc: tipo }
    });
}

function llenarInfoFactura(datos) {
    let contenido = $("#dialog_info_fac_body");
    contenido.empty();
    if (datos == undefined) {
        contenido.append($(`<div style="text-align:center;"><b>No se pudo obtener la informacón</b></div>`));
        return;
    }
    let { infoFac, productos } = datos;

    let razonsempresa = infoFac.razonSocial;
    let nombrecempresa = infoFac.nombreComercial;
    let rucempresa = infoFac.ruc;
    let matriz = infoFac.dirMatriz;
    let nrofact = infoFac.estab + "-" + infoFac.ptoEmi + "-" + infoFac.secuencial;
    let fechaemision = infoFac.fechaEmision;
    let claveacceso = infoFac.claveAcceso;
    let idcomprador = infoFac.identificacionComprador;
    let nombrecomprador = infoFac.razonSocialComprador;
    let totalsinimp = infoFac.totalSinImpuestos;
    let totalconimp = infoFac.totalConImpuestos;
    let totaldesc = infoFac.totalDescuento;
    let importeTotal = infoFac.importeTotal;

    let tablainfofac = $(`<table style="width:100%"></table>`);

    let trruc = $(`<tr><td style="width:100px"><label>RUC: </label></td><td>${rucempresa}</td></tr>`);
    let trnombre = $(`<tr><td><label>Empresa: </label></td><td>${razonsempresa} (${nombrecempresa})</td></tr>`);
    let trmatriz = $(`<tr><td><label>Matriz: </label></td><td>${matriz}</td></tr>`);
    let trnrofact = $(`<tr><td><label>Nro. Factura: </label></td><td>${nrofact}</td></tr>`);
    let trfechemi = $(`<tr><td><label>F. Emisión: </label></td><td>${fechaemision}</td></tr>`);
    let trcacceso = $(`<tr><td><label>Autorización: </label></td><td>${claveacceso}</td></tr>`);
    let trcompradorid = $(`<tr><td><label>Com. Id: </label></td><td>${idcomprador}</td></tr>`);
    let trcompradornom = $(`<tr><td><label>Com. Nombre: </label></td><td>${nombrecomprador}</td></tr>`);

    tablainfofac.append([
        trnombre,
        trruc,
        trmatriz,
        trnrofact,
        trfechemi,
        trcacceso,
        trcompradorid,
        trcompradornom]);


    let tablainfoprod = $(`<table style="width:100%; margin-top:15px; border-collapse:collaps; border:solid 1px black;"></table>`);
    let cabecera = $(`<tr><th style="text-align:center; width:350px;" >Producto</th><th style="text-align:center">Cantidad</th><th style="text-align:center">P. U</th><th style="text-align:center">Desc.</th><th style="text-align:center">Total</th><tr>`);

    tablainfoprod.append([cabecera]);
    productos.forEach(el => {
        let prod = $(`<tr><td>${el.descripcion}</td><td style="text-align:right">${el.cantidad}</td><td style="text-align:right">${el.precioUnitario}</td><td style="text-align:right">${el.descuento}</td><td style="text-align:right">${el.precioTotalSinImpuesto}</td></tr>`);
        tablainfoprod.append(prod);
    });

    let tablatotales = $(`<table style="width:100%; margin-top:15px; border-collapse:collaps;"></table>`);
    let trtotalsinimp = $(`<tr><th style="text-align:right;">Subtotal Sin Impuestos:</th><td style="text-align:right; width:100px;">${totalsinimp}</td><tr>`);
    let trtotadesc = $(`<tr><th style="text-align:right;">Descuento:</th><td style="text-align:right; width:100px;">${totaldesc}</td><tr>`);

    tablatotales.append([trtotalsinimp, trtotadesc]);
    totalconimp.forEach(el => {
        let trsubt = "";
        if (el.codigo == 2) {
            trsubt = $(`<tr><th style="text-align:right;">IVA:</th><td style="text-align:right; width:100px;">${el.valor}</td><tr>`);
            tablatotales.append(trsubt);
        }
        if (el.codigo == 3) {
            trsubt = $(`<tr><th style="text-align:right;">ICE:</th><td style="text-align:right; width:100px;">${el.valor}</td><tr>`);
            tablatotales.append(trsubt);
        }
        if (el.codigo == 5) {
            trsubt = $(`<tr><th style="text-align:right;">IRBPNR:</th><td style="text-align:right; width:100px;">${el.valor}</td><tr>`);
            tablatotales.append(trsubt);
        }
    });
    let trtotal = $(`<tr><th style="text-align:right;">Valor Total:</th><td style="text-align:right; width:100px;">${importeTotal}</td><tr>`);
    tablatotales.append(trtotal);

    contenido.append([tablainfofac, tablainfoprod, tablatotales]);
}

function abrirFacturaCompra(autorizacion, rowid) {
    refdoc = window.open('../factura_compra/', "_blank");
    refdoc.addEventListener("load", function (event) {
        let row = $("#tabla_docs").jqGrid("getRowData", rowid);
        if (row.nombre_xml) {
            obtenerXml(row.nombre_xml)
                .then(blob => {
                    refdoc.subirXmls(blob, "file");
                });
        } else {
            refdoc.document.getElementById("clavefactura").value = autorizacion;
            refdoc.document.getElementById("btn_buscar_clave").click();
        }
    }, true);
    refdoc.addEventListener("unload", function (e) {
        comprobarFactura(autorizacion)
            .then(res => {
                if (res == 1) {
                    cambiarFacturaProcesada(rowid);
                    this.close();
                }
            });
    });
}
function abrirRegistroGasto(autorizacion, rowid) {
    refdoc = window.open('../registro_gastos/', "_blank");
    refdoc.addEventListener("load", function (event) {
        let row = $("#tabla_docs").jqGrid("getRowData", rowid);
        if (row.nombre_xml) {
            obtenerXml(row.nombre_xml)
                .then(blob => {
                    refdoc.subirXmls(blob, "file");
                });
        } else {
            refdoc.document.getElementById("clavefactura").value = autorizacion;
            refdoc.document.getElementById("btn_buscar_clave").click();
        }
    }, true);
    refdoc.addEventListener("unload", function (e) {
        comprobarFactura(autorizacion)
            .then(res => {
                if (res == 1) {
                    cambiarFacturaProcesada(rowid);
                    this.close();
                }
            });
    });
}
function abrirGastoPersonal(autorizacion, rowid) {
    refdoc = window.open('../gastos_personales/', "_blank");
    refdoc.addEventListener("load", function (event) {
        let row = $("#tabla_docs").jqGrid("getRowData", rowid);
        if (row.nombre_xml) {
            obtenerXml(row.nombre_xml)
                .then(blob => {
                    refdoc.subirXmls(blob, "file");
                });
        } else {
            refdoc.document.getElementById("clavefactura").value = autorizacion;
            refdoc.document.getElementById("btn_buscar_clave").click();
        }
    }, true);
    refdoc.addEventListener("unload", function (e) {
        comprobarFactura(autorizacion)
            .then(res => {
                if (res == 1) {
                    cambiarFacturaProcesada(rowid);
                    this.close();
                }
            });
    });
}
function abrirRetencionVenta(autorizacion, rowid) {
    refdoc = window.open('../factura_venta/', "_blank");
    refdoc.addEventListener("load", function (event) {
        refdoc.showTabRetenciones();
        refdoc.document.getElementById("clavefactura").value = autorizacion;
        refdoc.document.getElementById("btn_buscar_clave").click();
    }, true);
    refdoc.addEventListener("unload", function (e) {
        comprobarFactura(autorizacion, 'comp_ret')
            .then(res => {
                if (res == 1) {
                    cambiarFacturaProcesada(rowid);
                    this.close();
                }
            });
    });
}
function cambiarFacturaProcesada(rowid) {
    $("#tabla_docs").jqGrid('setRowData', rowid, {
        act: `<div style="text-align:center; color:#000; background:#43A047; opacity:0.8; font-weight:bold;">PROCESADA</div>`
    });
}
function consultarFacturaAutorizada(clave, file = null) {
    let fd = new FormData();
    if (clave) {
        fd.append("clave", clave);
    } else if (file) {
        fd.append("file", file, "file.xml");
    }
    return fetch("../../procesos/obtener_factura_autorizada.php",
        {
            method: "POST",
            body: fd,
        })
        .then(res => {
            return res.json();
        })
        .catch(err => {

        })

}
function obtenerXml(filename) {
    let fd = new FormData();
    fd.append("nombre_xml", filename);
    return fetch("obtener_xml_tmp.php",
        {
            method: "POST",
            body: fd
        })
        .then(res => {
            return res.blob();
        });
}
