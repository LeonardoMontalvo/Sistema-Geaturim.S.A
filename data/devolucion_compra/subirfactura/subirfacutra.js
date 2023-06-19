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
    if (tipo == "clave") {
        formdata.append("clave", file);
    } else {
        return;
    }

    try {
        let res = await fetch("../../procesos/obtener_nota_credito_autorizada.php", { method: "POST", body: formdata });
        res = await res.json();

        if (res == -1) {
            alertError("El comprobante no es una nota de crédito.");
            return;
        }
        if (res == -2) {
            alertError("El comprobante modificado por la nota de crédito no es una factura.");
            return;
        }

        let infofac = res["infoNotaC"];
        let estab = infofac["estab"];
        let ptoEmi = infofac["ptoEmi"];
        let secuencial = infofac["secuencial"];
        let nronotac = `${estab}-${ptoEmi}-${secuencial}`;
        infofac = res["infoNotaC"];
        let fecsplit = infofac["fechaEmision"].split("/");
        let fechaemi = fecsplit[2] + "-" + fecsplit[1] + "-" + fecsplit[0];
        let fechautsplit = infofac["fechaAutorizacion"].split("T");
        let fechaut = fechautsplit[0];
        let idComprador = infofac["identificacionComprador"];
        let idProveedor = infofac["ruc"];
        let nroFacModificada = infofac["numDocModificado"];
        let claveAcceso = infofac["claveAcceso"];

        let idfactura = await buscarFactura(nroFacModificada, idProveedor, idComprador);
        if (idfactura == -1) {
            alertError("La factura de compra no está registrada en el sistema.");
            return;
        }

        cargarProveedor(idProveedor, function () {
            cargarFactura(nroFacModificada);
            $("#secuencial_nc").val(nronotac);
            $("#fecha_registro_nc").val(fechaut);
            $("#autorizacion_nc").val(claveAcceso);
        });

        buscando = false;
        estadoBotonBuscar();
        alertify.success("Documento cargado correctamente");
    } catch (error) {
        console.log(error);
        alertError("No se pudo cargar la nota de crédito.");
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
    nroDocSustentoRet = "";
}

function buscarFactura(nrofactura, idproveedor, idcomprador) {
    return $.ajax({
        url: "./subirfactura/buscar_factura.php",
        method: "POST",
        data: {
            "ruc_comprador": idcomprador,
            "ruc_proveedor": idproveedor,
            "nro_factura": nrofactura
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

function cargarProveedor(rucproveedor, callback) {
    $("#tipo_docu").val("Ruc");
    $("#tipo_docu").trigger("change");
    $("#ruc_ci").autocomplete("search", rucproveedor);
    $("#ruc_ci").autocomplete({
        response: function (event, ui) {
            let item = ui.content[0];
            $("#ruc_ci").val(item.value);
            $("#empresa").val(item.empresa);
            $("#id_proveedor").val(item.id_proveedor);
            $("#ruc_ci").blur();
            callback();
            return false;
        }
    });
}

function cargarFactura(nrofactura) {
    $("#tipo_comprobante").val("FACTURA");
    $("#serie").trigger("keyup");

    $("#serie").autocomplete("search", nrofactura);
    $("#serie").autocomplete({
        response: function (event, ui) {
            let item = ui.content[0];
            $("#serie").val(item.value);
            $("#autorizacion").val(item.autorizacion);
            $("#id_factura_compra").val(item.id_factura_compra);
            $("#serie").blur();
            return false;
        }
    });
}