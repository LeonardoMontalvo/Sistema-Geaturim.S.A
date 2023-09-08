var buscando = false;
var retenciones = [];

$(document).ready(function () {

    $("#btn_buscar_clave").click(function () {
        subirXmls($("#clavefactura").val(), 'clave');
    });
    $("#clavefactura").keyup(function (e) {
        if (e.key == "Enter") {
            $("#btn_buscar_clave").click();
        }
    });

    initDialogCargarValoresRetencion();
    $("#btn_cargar_valores").click(function (e) {
        if (retenciones.length == 0) {
            alertify.alert(`<b>No hay retención cargada.</b>`,
                function (e) { });
            return;
        }
        $("#cargar_valores_retencion").dialog("open");
    });
})

async function subirXmls(file, tipo) {
    buscando = true;
    estadoBotonBuscar();
    let formdata = new FormData();
    limpiarCamposRetencion();
    if (tipo == "clave") {
        formdata.append("clave", file);
    } else {
        return;
    }

    try {
        let res = await fetch("../../procesos/obtener_retencion_autorizada.php", { method: "POST", body: formdata });
        res = await res.json();

        if (res == -1) {
            alertError("El comprobante no es una retención.");
            return;
        }

        let infofac = res["infoRet"];
        let estab = infofac["estab"];
        let impuestos = res["impuestos"];
        let docsSustento = res["docsSustento"];
        let identificacionSujetoRetenido = infofac["identificacionSujetoRetenido"];

        let ptoEmi = infofac["ptoEmi"];
        let secuencial = infofac["secuencial"];
        infofac = res["infoRet"];
        let fecsplit = infofac["fechaEmision"].split("/");
        let fechaemi = fecsplit[2] + "-" + fecsplit[1] + "-" + fecsplit[0];
        let fechautsplit = infofac["fechaAutorizacion"].split("T");
        let fechaut = fechautsplit[0];

        $("#serie_retencion").val(estab + "-" + ptoEmi + "-" + secuencial);
        $("#autorizacion_retencion").val(infofac["claveAcceso"]);
        $("#fecha_retencion").val(fechaemi);
        $("#fecha_aut_retencion").val(fechaut);

        retenciones = [];
        let numDocSustento = ""
        if (impuestos.length > 0) {
            retenciones = impuestos;
            numDocSustento = impuestos[0]["numDocSustento"];
        } else if (docsSustento.length > 0) {
            retenciones = docsSustento[0].retenciones;
            numDocSustento = docsSustento[0]["numDocSustento"];
        }
        let nrofactura = {};
        nrofactura["estab"] = numDocSustento.substring(0, 3);
        nrofactura["ptoEmi"] = numDocSustento.substring(3, 6);
        nrofactura["secuencial"] = numDocSustento.substring(6);

        let idfactura = await buscarFactura(nrofactura, identificacionSujetoRetenido);
        if (idfactura <= 0) {
            if (idfactura == -1) {
                alertError("El Identificador del sujeto retenido no coincide con el RUC de empresa del sistema.");
            } else if (idfactura == -2) {
                alertError("No se encontró la factura correspondiente a la retención en el sistema.");
            }
        } else {
            cargarFacturaDblclick(idfactura);
        }

        buscando = false;
        estadoBotonBuscar();
        alertify.success("Documento cargado correctamente");
    } catch (error) {
        alertError("No se pudo cargar la retención.");;
    }
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

function limpiarCamposRetencion() {
    $("#serie_retencion").val("");
    $("#autorizacion_retencion").val("");
    $("#fecha_retencion").val("");
    $("#fecha_aut_retencion").val("");
}

function buscarFactura(nrofactura, idSujetoRet) {
    return $.ajax({
        url: "./subirfactura/buscar_factura.php",
        method: "POST",
        data: {
            "id_sujeto_ret": idSujetoRet,
            "estab": nrofactura["estab"],
            "ptoemi": nrofactura["ptoEmi"],
            "secuencial": nrofactura["secuencial"],
        },
        dataType: "json"
    });
}

function alertError(msg) {
    alertify.alert(`<b>${msg}</b>`, function (e) {
        localStorage.setItem("load_retencion_tab", '1');
        location.reload();
    });
    buscando = false;
    estadoBotonBuscar();
    limpiarCamposRetencion();
    $("#alertify-ok").css({ background: "red" });

}

function llenarTablaCargarRetencion() {
    retenciones = retenciones.map(el => {
        if (el.codigo == 1) {
            el["impuestoRetener"] = "RENTA";
        } else if (el.codigo == 2) {
            el["impuestoRetener"] = "IVA";
        } else if (el.codigo == 6) {
            el["impuestoRetener"] = "ISD";
        }
        return el;
    });
    let tablacreten = $("#tabla_cargar_retenciones");
    tablacreten.empty();
    let headertabla = $(`
    <tr style="border:solid 1px black;">
        <th style="text-align: center; width: 20%;">Impuesto a Retener</th>
        <th style="text-align: center; width: 20%;">Base Imponible</th>
        <th style="text-align: center; width: 20%;">Valor Retenido</th>
        <th style="text-align: center; width: 20%;">Porcentaje Retención</th>
        <th style="text-align: center; width: 20%;">Porcentaje Sistema</th>
        <th style="text-align: center; width: 20%;">Bien/Servicio</th>
    </tr>
    `);
    tablacreten.append(headertabla);

    retenciones.forEach(el => {
        let tr = $(`<tr style="border-bottom:solid 1px black;">
        <td style="text-align:center;">${el.impuestoRetener}</td>
        <td style="text-align:center;">${el.baseImponible}</td>
        <td style="text-align:center;">${el.valorRetenido}</td>
        <td style="text-align:center;">${el.porcentajeRetener}%</td>
        </tr>`);

        let selBienServicio = $(`<select><option value="" selected disabled>---Seleccione---</option><option value="b">BIEN</option><option value="s">SERVICIO</option><select>`)
        let selPrcRenta = document.getElementById("tipoRetencionesF").cloneNode(true);
        let selPrcIva = document.getElementById("tipoRetencionesI").cloneNode(true);

        selPrcRenta.id = `sel_prc_${el.codigo}`;
        selPrcIva.id = `sel_prc_${el.codigo}`;
        selPrcRenta.disabled = false;
        selPrcIva.disabled = false;
        selPrcRenta.classList.remove("form-control");
        selPrcIva.classList.remove("form-control");
        selPrcRenta.style.width = "100%";
        selPrcIva.style.width = "100%";

        if (el.bienServicio) {
            selBienServicio.val(el.bienServicio);
        }


        $(selBienServicio).change(function (e) {
            el["bienServicio"] = e.target.value;
        });
        $(selPrcRenta).change(function (e) {
            el["codigoRetencionSistema"] = e.target.value;
        });
        $(selPrcIva).change(function (e) {
            el["codigoRetencionSistema"] = e.target.value;
        });

        let tdSelPrcIva = $(`<td></td>`);
        if (el.codigo == 1) {
            if (el.codigoRetencionSistema) {
                selPrcRenta.value = el.codigoRetencionSistema;
            }
            tdSelPrcIva.append(selPrcRenta);
        } else if (el.codigo == 2) {
            if (el.codigoRetencionSistema) {
                selPrcIva.value = el.codigoRetencionSistema;
            }
            tdSelPrcIva.append(selPrcIva);
        }

        let tdSelBienServicio = $(`<td></td>`);
        tdSelBienServicio.append(selBienServicio);

        tr.append(tdSelPrcIva);
        tr.append(tdSelBienServicio);

        tablacreten.append([tr]);
    });
}

function initDialogCargarValoresRetencion() {
    $("#cargar_valores_retencion").dialog({
        modal: true,
        width: 800,
        height: 250,
        autoOpen: false,
        title: "CARGAR FACTURA",
        buttons: [
            {
                text: "Ok",
                icon: "ui-icon-heart",
                style: "background:#4CAF50; color:#fff",
                type: "button",
                click: function () {
                    llenarListPagoReten();
                }
            }
        ],
        close: function (event, ui) {
            //jQuery("#tabla_subir_fac").jqGrid("clearGridData");
        },
        open: function (event, ui) {
            llenarTablaCargarRetencion();
        }
    });
}

function llenarListPagoReten() {

    let emptybs = retenciones.some(el => el.bienServicio == "" || el.bienServicio == undefined || el.bienServicio == "0");
    let emptypr = retenciones.some(el => el.codigoRetencionSistema == "" || el.codigoRetencionSistema == undefined || el.codigoRetencionSistema == "0");
    $("#alertify-logs").empty();
    if (emptypr) {
        alertify.error(`Debe seleccionar un valor para "Porcentaje Sistema"`);
        return;
    }
    if (emptybs) {
        alertify.error(`Debe seleccionar un valor para "Bien/Servicio"`);
        return;
    }
    jQuery("#listPagoreten").jqGrid("clearGridData");

    let totalret = 0;
    retenciones.forEach(el => {
        let impuesto = "";
        if (el.codigo == 1 && el.bienServicio == "b") {
            impuesto = "RENTA BIENES";
        } else if (el.codigo == 1 && el.bienServicio == "s") {
            impuesto = "RENTA SERVICIOS";
        } else if (el.codigo == 2 && el.bienServicio == "b") {
            impuesto = "IVA";
        } else if (el.codigo == 2 && el.bienServicio == "s") {
            impuesto = "IVA SERVICIOS";
        }
        let datarow = {
            base_imponible: el.baseImponible,
            impuesto: impuesto,
            porcent_reten: el.porcentajeRetener,
            valor_retenido: el.valorRetenido,
            id_retenciones_ser: el.codigoRetencionSistema,
            tipo_ret: el.bienServicio,
            codigo_imp: el.codigo
        };
        su = jQuery("#listPagoreten").jqGrid("addRowData", el.codigo, datarow);
        totalret += Number(el.valorRetenido);
    });
    alertify.success(`Valores de la retención cargados correctamente`);
    $("#cargar_valores_retencion").dialog("close");

    $("#total_retencion").val(totalret);

    $("#cuenta_contable_reten").attr("disabled", false);
    $("#btnCuenta_reten").attr("disabled", false);
    $("#formaspago_mixto_reten").attr("disabled", false);
}
