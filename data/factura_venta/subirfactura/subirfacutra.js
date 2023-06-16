var buscando = false;

$(document).ready(function () {

    $("#btn_buscar_clave").click(function () {
        subirXmls($("#clavefactura").val(), 'clave');
    });
    $("#clavefactura").keyup(function (e) {
        if (e.key == "Enter") {
            $("#btn_buscar_clave").click();
        }
    });
})

async function subirXmls(file, tipo) {
    $("#btn_cargar_prods").hide();
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

        let numDocSustento = ""
        if (impuestos["impuesto"] != null) {
            numDocSustento = impuestos["impuesto"][0]["numDocSustento"];
        } else if (docsSustento["docSustento"] != null) {
            numDocSustento = docsSustento["docSustento"]["numDocSustento"];
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
