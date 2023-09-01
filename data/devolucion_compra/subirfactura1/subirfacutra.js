var buscando = false;
var infofac = "";

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

        infofac = res["infoNotaC"];
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
        if (idfactura <= 0) {
            if (idfactura == -1) {
                alertError("La identificación del comprador no coincide con el RUC de empresa del sistema.");
            } else if (idfactura == -2) {
                //alertError("La factura de compra no está registrada en el sistema.");
                //alertify.alert(`<b><i class="fa fa-info-circle" aria-hidden="true"></i></b><br><b>La factura de compra no está registrada en el sistema.</b>`);
            }
        } else {
            $("#si_no_factura").val(1);
            $("#si_no_factura").trigger("change");
        }

        cargarProveedor(idProveedor, function () {
            cargarFactura(nroFacModificada);
            $("#secuencial_nc").val(nronotac);
            $("#fecha_registro_nc").val(fechaut);
            $("#fecha_emision_nc").val(fechaemi);
            $("#autorizacion_nc").val(claveAcceso);

            alertify.success("Documento cargado correctamente");
            readyonlyFormDatosNota();
        });

        buscando = false;
        estadoBotonBuscar();
    } catch (error) {
        alertError("No se pudo cargar la nota de crédito.");
        console.log(error);
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
        location.reload();
    });
    buscando = false;
    estadoBotonBuscar();
    restoreFormDatosNota();
    $("#alertify-ok").css({ background: "red" });

}

function cargarProveedor(rucproveedor, callback) {
    $("#tipo_docu").val("Ruc");
    $("#tipo_docu").trigger("change");
    $("#ruc_ci").autocomplete("search", rucproveedor);
    $("#ruc_ci").autocomplete({
        response: function (event, ui) {
            if (ui.content.length > 0) {
                let item = ui.content[0];
                $("#ruc_ci").val(item.value);
                $("#empresa").val(item.empresa);
                $("#id_proveedor").val(item.id_proveedor);
                callback();
            } else {
                alertify.alert(`El proveedor <b><i>${infofac.razonSocial}</i></b> no esta registrado. Por favor registre el proveedor.`,
                    function (e) {
                        $("#btnClientes").click();
                    });
            }
            $("#ruc_ci").blur();
            $("#ruc_ci").autocomplete({ response: function (event, ui) { } });
            return false;
        }
    });
}

function cargarFactura(nrofactura) {
    $("#descuentof2")[0].checked = true;
    $("#descuentof2").trigger("change");
    $("#tipo_comprobante").val("FACTURA");
    $("#serie").trigger("keyup");

    $("#serie").autocomplete("search", nrofactura);
    $("#serie").autocomplete({
        response: function (event, ui) {
            if (ui.content.length > 0) {
                let item = ui.content[0];
                $("#serie").val(item.value);
                $("#autorizacion").val(item.autorizacion);
                $("#id_factura_compra").val(item.id_factura_compra);
            } else {
                alertify.alert(`<div style="text-align:left;"><i style="color:#42A5F5;" class="fa fa-info-circle fa-2x" aria-hidden="true"></i> La factura <b>N° ${nrofactura}</b> a la que hace referencia la nota de crédito no está registrada en el sistema.</div>`, function (e) { $("#autorizacion_credito").focus(); });
                $("#alertify-ok").text("Continuar");
                $("#alertify-ok").css({ background: "#1E88E5" });

                $("#si_no_factura").val(2);
                $("#si_no_factura").trigger("change");
                $("#secuencial").val(nrofactura);
                $("#autorizacion_credito").val("");
                $("#autorizacion_credito").focus();
            }

            $("#serie").blur();
            $("#serie").autocomplete({ response: function (event, ui) { } });
            return false;
        }
    });
}

function readyonlyFormDatosNota() {
    $("#secuencial_nc").css({
        "background": "#42A5F5",
        "font-weight": "bold",
        "color": "black"
    });
    $("#tipo_docu")[0].disabled = true;
    $("#tipo_comprobante")[0].disabled = true;
    $("#si_no_factura")[0].disabled = true;
    $("#ruc_ci")[0].readOnly = true;
    $("#secuencial_nc")[0].readOnly = true;
    $("#autorizacion_nc")[0].readOnly = true;
    $("#fecha_registro_nc")[0].readOnly = true;
    $("#fecha_emision_nc")[0].readOnly = true;
    $("#serie")[0].readOnly = true;

    $("#btnClientes")[0].disabled = true;
}

function restoreFormDatosNota() {
    limpiar_input();
    limpiarTablaProductos();
    limpiar_datos();
    limpiarInfoNota();
    cambiarEstadoFacturaNoSeleccionado();

    $("#secuencial_nc").css({
        "background": "#fff",
        "font-weight": "normal",
        "color": "#555"
    });
    $("#clavefactura").val("");
    $("#tipo_docu").val("");
    $("#tipo_docu")[0].disabled = false;
    $("#tipo_comprobante")[0].disabled = false;
    $("#btnClientes")[0].disabled = false;
}