var infofac;
var productosfactura = [];
var registroProduto;
var numserie;
var numautorizacion;
var fechaEmision;
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
            restoreFormDatosFactura();
            estadoBotonBuscar();
            return;
        }
        infofac = res["infoFac"];
        productosfactura = res["productos"];
        numserie = infofac["estab"] + "-" + infofac["ptoEmi"] + "-" + infofac["secuencial"];
        numautorizacion = infofac["claveAcceso"];
        fechaEmision = infofac["fechaEmision"];
        cargarTablaFac();
        llenarInfoFactura();
        buscando = false;
        estadoBotonBuscar();
        alertify.success("Factura cargada correctamente");
        $("#btn_cargar_prods").show();
    } catch (error) {
        alertify.error("No se pudo cargar la factura.");
        console.error(error);
        buscando = false;
        cargarTablaFac();
        limipiarInfoFactura();
        restoreFormDatosFactura();
        estadoBotonBuscar();
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
    $("#iden_comprador").val(infofac.identificacionComprador);
    $("#rs_comprador").val(infofac.razonSocialComprador);
}
function limipiarInfoFactura() {
    infofac = undefined;
    productosfactura = [];
    $("#tipo_docu").val("").trigger("change");
    $("#ruc_ci").val("");
    $("#empresa").val("");
    $("#id_proveedor").val("");
    $("#factura").val("");
    $("#autorizacion").val("");
    $("#fecha_emision").val(new Date().toLocaleDateString("fr-CA"));
    $("#list").jqGrid("clearGridData", true);
    $("#iden_comprador").val("");
    $("#rs_comprador").val("");

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
    $("#list").jqGrid("clearGridData");
    productosfactura.forEach(el => {
        addProducto(el.precioUnitario, el.descuento, el.cantidad, el.impuestos[0].tarifa, el.descripcion, "", "");
    });
}
