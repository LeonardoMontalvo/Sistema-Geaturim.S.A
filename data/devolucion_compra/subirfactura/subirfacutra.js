var infofac;
var productosfactura = [];
var productostablafact = [];
var registroProduto;
var numserie;
var numautorizacion;
var fechaEmision;
var buscando = false;
/* var buscandoProductosProv = false; */
var registrandoCodigosFactura = false;

$(document).ready(function () {
    $("#dialog_subir_factura").dialog({
        modal: true,
        width: 950,
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
                    llenarTablaCompras();
                    $(this).dialog("close");
                }
            }
        ],
        close: function (event, ui) {
            //productosfactura = [];
            productostablafact = [];
            jQuery("#tabla_subir_fac").jqGrid("clearGridData");
        },
        open: function (event, ui) {
            inicioTabla();
            cargarTablaFac();
        }
    });

    $("#dialog_form_registro_producto").dialog({
        modal: true,
        width: window.innerWidth - 100,
        height: window.innerHeight - 100,
        minHeight: 600,
        minHeight: 700,
        autoOpen: false,
        title: "REGISTRAR PRODUCTO",
        close: function (event, ui) {
            registroProduto.limpiarFormulario();
        },
        buttons: [
            {
                text: "Guardar",
                icon: "ui-icon-heart",
                style: "background:#4CAF50; color:#fff",
                type: "button",
                click: function () {
                    let rowid = $("#dialog_form_registro_producto").data("codPrincipal");
                    if (registroProduto.validarFormulario()) {
                        registroProduto.guardarProducto().then(function (data) {
                            llenarProductoSistemaTablaFac(rowid, data, "codigo_barras");
                            if (!!data) {
                                $("#dialog_form_registro_producto").dialog("close");
                            }
                        });
                    }
                }

                // Uncommenting the following line would hide the text,
                // resulting in the label being used as a tooltip
                //showText: false
            }
        ],
    });

    $("#facutaxml").change(function (e) {
        let files = e.target.files;
        let file = files[0];
        productosfactura = productostablafact = [];
        $("#facutaxml").val("");
        if (file.type != "text/xml") {
            infofac = undefined;
            alertify.error("Solo puede cargar archivos XML");
            cargarTablaFac();
            return;
        }
        subirXmls(file, "file");
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
        let codigosfac = productosfactura.map(_ => _.codigoPrincipal);
        console.log(codigosfac);
        registrandoCodigosFactura = true;
        estadoRegistrarCodigosFactura();
        registrarCodigosProveedorFactura($("#id_proveedor").val(), codigosfac).then(data => {
            registrandoCodigosFactura = false;
            estadoRegistrarCodigosFactura();
            $("#dialog_subir_factura").dialog("open");
        }).fail(function () {
            registrandoCodigosFactura = false;
            estadoRegistrarCodigosFactura();
            alertify.error("Hubo un problema al registrar los códigos de factura");
        });
    });
    cargarRegistroProductos();
})

async function subirXmls(file, tipo) {
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

    try {
        let res = await fetch("../../procesos/obtener_nota_credito_autorizada.php", { method: "POST", body: formdata });
        res = await res.json();

        if (res < 0) {
            $("#alertify-ok").css({ background: "red" });
            buscando = false;
            cargarTablaFac();
            limipiarInfoFactura();
            restoreFormDatosNota();
            estadoBotonBuscar();
            if (res == -1) {
                alertify.alert("<b>El comprobante no es una nota de crédito..</b>");
            }
            if (res == -2) {
                alertError("El comprobante modificado por la nota de crédito no es una factura.");
            }
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

        productosfactura = res["productos"];

        let idfactura = await buscarFactura(nroFacModificada, idProveedor, idComprador);
        if (idfactura <= 0) {
            if (idfactura == -1) {
                alertError("La identificación del comprador no coincide con el RUC de empresa del sistema.");
            } else if (idfactura == -2) {
                //alertError("La factura de compra no está registrada en el sistema.");
                //alertify.alert(`<b><i class="fa fa-info-circle" aria-hidden="true"></i></b><br><b>La factura de compra no está registrada en el sistema.</b>`);
            }
        } else {
            console.log("abc");
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
        alertify.success("Nota de crédito cargada correctamente");
    } catch (error) {
        alertError("No se pudo cargar la nota de crédito.");
        console.error(error);
        buscando = false;
        cargarTablaFac();
        limipiarInfoFactura();
        restoreFormDatosNota();
        estadoBotonBuscar();
    }
}

function inicioTabla() {
    let lastsel;
    jQuery("#tabla_subir_fac").jqGrid({
        datatype: "local",
        colNames: [
            "Código Factura",
            "Descripción Factura",
            "Código Sistema",
            "Descripción Sistema",
            "Unidad Medida",
            "C. Costo",
            "",
            "cod_productos"
        ],
        colModel: [
            {
                name: "codigoPrincipal",
                width: 100,
                editable: false,
                hidden: true
            },
            {
                name: "descripcion",
                width: 250,
                editable: false
            },
            {
                name: "codigo_barras_sistema",
                width: 100,
                editable: true,
                edittype: 'custom',
                editoptions: {
                    custom_element: inputCodigoBarras,
                    custom_value: valueCodigoBarras
                },
            },
            {
                name: "descripcion_sistema",
                width: 250,
                editable: true,
                edittype: 'custom',
                editoptions: {
                    custom_element: inputArticulo,
                    custom_value: valueArticulo
                }
            },
            {
                name: "envase_frac",
                width: 200,
                formatter: function (cellvalue, options, rowObject) {
                    return `<div><select style="width:100%" id="unidadm_${options.rowId}"></select><div/>`
                }
            },
            {
                name: "centro_costo",
                width: 100,
                formatter: function (cellvalue, options, rowObject) {
                    return `<div><select style="width:100%" id="sel_centro_c_${options.rowId}"></select><div/>`
                },
                hidden: true
            },
            {
                name: "reg_prod",
                width: 150,
                formatter: function (cellvalue, options, rowObject) {
                    return `<button id="nuevopr_${options.rowId}" type="button" class="btn btn-success"><i class="fa fa-plus"></i> Registrar Prod.</button>`;
                },
                hidden: true
            },
            {
                name: "cod_productos",
                hidden: true
            }
        ],
        onSelectRow: function (id) {
            if (id && id !== lastsel) {
                jQuery('#tabla_subir_fac').jqGrid('restoreRow', lastsel);
                jQuery('#tabla_subir_fac').jqGrid('editRow', id, true);
                lastsel = id;
            }
        },
        afterSaveCell: function (rowid, cellname, value, iRow, iCol) {
            if (cellname == "codigo_barras_sistema") {
                if (!value) {
                    llenarProductoSistemaTablaFac(rowid, null, "codigo_barras");
                } else {
                    llenarProductoSistemaTablaFac(rowid, value, "codigo_barras");
                }
            }
            if (cellname == "descripcion_sistema") {
                llenarProductoSistemaTablaFac(rowid, value, "cod_productos");
            }
        },
        afterInsertRow: function (rowid, rowdata, rowelem) {
            iniciarBtnRegistrarProd(rowid);
        },
        /*   loadComplete: function (data) {
              if (!buscandoProductosProv) {
                  cargarCodigosProveedor();
              }
          }, */
        rowNum: 1000,
        sortname: 'num',
        sortorder: "asc",
        height: '100%',
        width: 910,
        shrinkToFit: false,
        cellEdit: true,
        cellsubmit: 'clientArray',
    });
}

function obtenerProductosTablaFact() {
    productosfactura.forEach((el, i) => {
        let prodt = productostablafact.some(el1 => el1.codigoPrincipal == i);
        if (!prodt) {
            productostablafact.push({ codigoPrincipal: i, descripcion: el.descripcion });
        }
    });
}

function llenarTablaFact() {
    let productos = productostablafact;
    jQuery("#tabla_subir_fac").jqGrid("clearGridData");
    productos.forEach((el, i) => {
        let obj = {
            codigoPrincipal: i,
            descripcion: el.descripcion
        };
        jQuery("#tabla_subir_fac").jqGrid("addRowData", i, obj);
    });
    jQuery("#tabla_subir_fac").trigger("reloadGrid");
}
function llenarTablaCompras() {
    productosfactura = productosfactura.map((el, i) => {
        let prodt = productostablafact.find(el1 => el1.codigoPrincipal == i);
        el.cod_barras = prodt.codigo_sistema;
        el.codigo = prodt.codigo_sistema;
        el.detalle = prodt.descripcion_sistema;
        if ($("#descuentof1")[0].checked) {
            el.detalle = prodt.descripcion;
        }
        el.cod_productos = prodt.cod_productos;
        el.iva_minorista = prodt.iva_minorista;
        el.inventariable = prodt.inventariable;
        return el;
    })
    jQuery("#list").jqGrid("clearGridData");
    productosfactura.forEach((el, i) => {
        let selum = null;

        if (document.getElementById("unidadm_" + i).selectedOptions.length > 0) {
            if (document.getElementById("unidadm_" + i).value != "") {
                selum = document.getElementById("unidadm_" + i).selectedOptions[0].text;
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

        /*let porcdesc = Number((+descuento * 100) / (+preciosinimp + +descuento));
        porcdesc = Number(porcdesc);//Number(Math.ceil(porcdesc));
        let preciototaltmp = Number(cantidadfac * preciou);
        let preciototal = Number(+preciototaltmp * ((100 - +porcdesc) / 100));

        descuento = preciototaltmp * (porcdesc / 100);*/


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

        let descp = descuento;

        /*let descp = (Number(descuento) * 100) / (preciou * Number(el.cantidad));
        descp = Number(descp);*/

        let datarow = {
            cod_producto: el.cod_productos,
            codigo: el.codigo,
            detalle: el.detalle,
            cantidad: cantidadfac,
            precio_u: preciou,
            descuento: descp,
            cal_des: descuento,
            total: preciosinimp,
            precio_ux: preciou,
            descuentox: descp,
            cal_desx: descuento,
            totalx: preciosinimp,
            iva: iva,
            incluye: "No",
            precio_v: Number(el.iva_minorista),
            cantidad_unidad: cantidad,
            unidad_medida: um,
            inventariable: el.inventariable,
            valor_iva: impuestoiva.valor,
            tarifa: impuestoiva.tarifa,
            cod_impuesto: impuestoiva.codigo,
            cod_tarifa: impuestoiva.codigoPorcentaje,
        };

        /*  if (document.getElementById("sel_centro_c_" + el.codigoPrincipal).value > 0) {
             datarow["id_centro_costo"] = document.getElementById("sel_centro_c_" + el.codigoPrincipal).value;
             datarow["centro_costo"] = document.getElementById("sel_centro_c_" + el.codigoPrincipal).options[$("#sel_centro_c_" + el.codigoPrincipal)[0].selectedIndex].text;
         } */
        jQuery("#list").jqGrid('addRowData', el.cod_productos, datarow);
    });
    //calcularTotales();
    calcularTotalesTablaProductos();
}
function llenarProductoSistemaTablaFac(codPrincipalProdFact, term, tipo, guardarCodProveedor = true) {
    if (codPrincipalProdFact == undefined) {
        return;
    }

    let prodt = productostablafact.find(el => el.codigoPrincipal == codPrincipalProdFact);

    prodt.cod_productos = "";
    prodt.codigo_barras_sistema = "";
    prodt.descripcion_sistema = "";
    prodt.codigo_sistema = "";
    prodt.iva_minorista = "";

    $.ajax({
        method: "GET",
        data: { term: term, tipo: tipo },
        url: "./subirfactura/buscar_producto_sinid.php",
        dataType: "json"
    }).then(function (data) {
        if (data.length > 0) {
            prodt.cod_productos = data[0].cod_productos;
            prodt.codigo_barras_sistema = data[0].cod_barras;
            prodt.descripcion_sistema = data[0].articulo;
            prodt.codigo_sistema = data[0].codigo;
            prodt.iva_minorista = data[0].iva_minorista;
            prodt.inventariable = data[0].inventariable;
            /*  if (guardarCodProveedor) {
                 guardarCodProdProveedor($("#id_proveedor").val(), codPrincipalProdFact, prodt.cod_productos);
             } */
        }
        actualizarFilaTablaFact(codPrincipalProdFact);
    }).fail(function (err) { console.error(err) });
}

function limipiarInfoFactura() {
    infofac = undefined;
    productosfactura = productostablafact = [];
    $("#tipo_docu").val("").trigger("change");
    $("#ruc_ci").val("");
    $("#empresa").val("");
    $("#correo").val("");
    $("#id_proveedor").val("");
    $("#serie").val("");
    $("#autorizacion").val("");
    $("#fecha_emision").val(new Date().toLocaleDateString("fr-CA"));
    $("#list").jqGrid("clearGridData", true);

    //calcularTotales();
    calcularTotalesTablaProductos();
}
function inputCodigoBarras(value, options) {
    let input = $("<input style='width:100%; text-transform:uppercase;' type='text' value='" + value + "'/>");
    return input;
}
function valueCodigoBarras(value) {
    return value.val();
}
function inputArticulo(value, options) {
    let url = "./subirfactura/buscar_producto.php";
    let input = $("<input style='width:100%;' type='text' value='" + value + "'/>");
    if ($("#id_factura_compra").val() != "" && $("#si_no_factura").val() == 1) {
        if ($("#descuentof2")[0].checked) {
            url = "./subirfactura/buscar_producto.php";
        } else {
            url = "./subirfactura/buscar_producto_sinid.php";
        }
    } else if ($("#id_factura_compra").val() == "" && $("#si_no_factura").val() == 2) {
        url = "./subirfactura/buscar_producto_sinid.php";
    }
    input
        .autocomplete({
            source: function (request, response) {
                var data = { term: request.term, tipo: "articulo", id_factura: $("#id_factura_compra").val() };
                $.get(
                    url,
                    data,
                    response,
                    "json"
                );
            },
            minLength: 0,
            select: function (event, ui) {
                let item = ui.item;
                input.val(item.cod_productos)
                return false;
            },
            focus: function (event, ui) {
                return false;
            }
        })
        .data("ui-autocomplete")._renderItem = function (ul, item) {
            return $("<li>")
                .append("<a>" + item["articulo"] + "</a>")
                .appendTo(ul);
        };
    return input;
}
function valueArticulo(value) {
    return value.val();
}

function actualizarFilaTablaFact(rowid) {
    let rowdata = $('#tabla_subir_fac').jqGrid('getRowData', rowid);
    let prodt = productostablafact.find(el => el.codigoPrincipal == rowid);
    rowdata.codigo_barras_sistema = prodt.codigo_barras_sistema;
    rowdata.descripcion_sistema = prodt.descripcion_sistema;
    rowdata.cod_productos = prodt.cod_productos;
    if (prodt.codigo_barras_sistema == "" && prodt.cod_productos == "") {
        rowdata.descripcion_sistema = "";
    }

    $('#tabla_subir_fac').jqGrid('setRowData', rowid, rowdata);

    if (rowdata.descripcion_sistema != "") {
        iniciarControlesFilaTablaFact(rowid);
    }
    iniciarBtnRegistrarProd(rowid);
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
function cargarRegistroProductos() {
    $.getScript("subirfactura/registro_producto/registro_producto.js", function () {
        registroProduto = new RegistroProducto($("#form_registro_producto"));
    });
}
function validarProductosTablaCompras() {
    let valido = productostablafact.some(el => !el.cod_productos);
    return !valido;
}
function realonlyFormDatosFactura() {
    $("#serie").css({
        "background": "#42A5F5",
        "font-weight": "bold",
        "color": "black"
    });
    $("#tipo_docu")[0].disabled = true;
    $("#tipo_comprobante")[0].disabled = true;
    $("#serie")[0].readOnly = true;
    $("#autorizacion")[0].readOnly = true;
}
function restoreFormDatosFactura() {
    $("#serie").css({
        "background": "#fff",
        "font-weight": "normal",
        "color": "#555"
    });
    $("#tipo_docu")[0].disabled = false;
    $("#tipo_comprobante")[0].disabled = false;
    $("#serie")[0].readOnly = false;
    $("#autorizacion")[0].readOnly = false;
}
function cargarTablaFac() {

    obtenerProductosTablaFact();
    llenarTablaFact();
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

/* function estadoBuscandoProdProv() {
    if (buscandoProductosProv) {
        $("#loading_tabla_subir_fac").show();
        $("#container_tabla_subir_fac").hide();
    } else {
        $("#loading_tabla_subir_fac").hide();
        $("#container_tabla_subir_fac").show();
    }
} */

function estadoRegistrarCodigosFactura() {
    if (registrandoCodigosFactura) {
        $("#btn_cargar_prods")[0].disabled = true;
        $("#icono_buscando_2").css({ display: "" });
    } else {
        $("#btn_cargar_prods")[0].disabled = false;
        $("#icono_buscando_2").css({ display: "none" });
    }
}

function guardarCodProdProveedor(idproveedor, codprodprov, codprod) {
    return $.ajax({
        url: "./subirfactura/guardar_cod_prod_proveedor.php",
        method: "POST",
        data: {
            id_proveedor: idproveedor,
            cod_prod_proveedor: codprodprov,
            id_producto: codprod
        }
    });
}
function buscarCodProdProveedor(idproveedor, codprodprov) {
    return $.ajax({
        url: "./subirfactura/buscar_cod_prod_proveedor.php",
        method: "GET",
        dataType: "json",
        data: {
            id_proveedor: idproveedor,
            cod_prod_proveedor: codprodprov
        }
    });
}

/* async function cargarCodigosProveedor() {
    buscandoProductosProv = true;
    estadoBuscandoProdProv();
    try {
        for (let el of productosfactura) {
            let codprod = await buscarCodProdProveedor($("#id_proveedor").val(), el.codigoPrincipal);
            llenarProductoSistemaTablaFac(codprod.cod_prod_proveedor, codprod.cod_productos, "cod_productos", false);
        }
        buscandoProductosProv = false;
        estadoBuscandoProdProv();
    } catch (error) {
        buscandoProductosProv = false;
        estadoBuscandoProdProv();
    }
} */

function iniciarControlesFilaTablaFact(rowid) {
    let prodt = productostablafact.find(el => el.codigoPrincipal == rowid);
    var tipo_comprobante = $("#tipo_comprobante").val();
    let num_fact_venta = $("#serie").val();
    /* obtenerCentrosCostos().then(cc => {
        document.getElementById("sel_centro_c_" + rowid).innerHTML = "";
        let elem = document.createElement("template");
        elem.innerHTML = `<option value="">---Seleccione---</option>`;
        document.getElementById("sel_centro_c_" + rowid).appendChild(elem.content);

        cc.forEach(el => {
            let elem = document.createElement("template");
            elem.innerHTML = `<option value="${el.id_centro_costo}">${el.nombre}</option>`;
            document.getElementById("sel_centro_c_" + rowid).appendChild(elem.content);

        });
        if ($("#sel_centro_costo").val() != "") {
            document.getElementById("sel_centro_c_" + rowid).value = $("#sel_centro_costo").val();
        }
    }); */
    $.ajax({
        url: "./retornar_series_unidad_sinid.php",
        method: "GET",
        dataType: "json",
        data: { "cod": prodt.cod_productos, "tipo_comprobante": tipo_comprobante, "num_fac_venta": num_fact_venta },
        success: function (data) {
            document.getElementById("unidadm_" + rowid).innerHTML = "";
            let elem = document.createElement("template");
            elem.innerHTML = `<option value="">---Seleccione---</option>`;
            document.getElementById("unidadm_" + rowid).appendChild(elem.content);
            let tama = data.length;
            for (var i = 0; i < tama; i = i + 2) {
                let elem = document.createElement("template");
                elem.innerHTML = `<option val="${data[i]}">${data[i + 1]}</option>`;
                document.getElementById("unidadm_" + rowid).appendChild(elem.content);

            }
        }
    });

}

function iniciarBtnRegistrarProd(rowid) {
    document.getElementById("nuevopr_" + rowid).addEventListener("click", function (e) {
        $("#dialog_form_registro_producto")
            .data("codPrincipal", rowid)
            .dialog('open');

        let prodfac = productosfactura.find(el => el.codigoPrincipal == rowid);

        registroProduto.codProducto = prodfac.codigoPrincipal;
        registroProduto.codBarraProcuto = prodfac.codigoPrincipal;
        registroProduto.nombreProducto = prodfac.descripcion;
        registroProduto.precioProductoSinIva = prodfac.precioUnitario;
        prodfac.impuestos.forEach(el => {
            if (el.codigo == 2) {
                if (Number(el.tarifa) > 0) {
                    registroProduto.tarifaIvaProducto = 2
                } else {
                    registroProduto.tarifaIvaProducto = 1
                }
            }
        });
    });
}

function registrarCodigosProveedorFactura(idproveedor, codigosfactura) {
    return $.ajax({
        url: "./subirfactura/buscar_registrar_cod_prod_proveedor.php",
        method: "POST",
        data: {
            id_proveedor: idproveedor,
            codigos_factura: codigosfactura
        }
    });
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