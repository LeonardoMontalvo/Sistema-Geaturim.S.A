$(document).on("ready", inicio);
function evento(e) {
    e.preventDefault();
}
function scrollToBottom() {
    $('html, body').animate({
        scrollTop: $(document).height()
    }, 'slow');
}



function scrollToTop() {
    $('html, body').animate({
        scrollTop: 0
    }, 'slow');
}


function ValidNum() {
    if (event.keyCode < 48 || event.keyCode > 57) {
        event.returnValue = false;
    }
    return true;
}

function Num_Let() {
    if ((event.keyCode !== 32) && (event.keyCode < 65) || (event.keyCode > 90) && (event.keyCode < 97) || (event.keyCode > 122)) {
        event.returnValue = false;
    }
    return true;
}

function punto(e) {
    var key;
    if (window.event) {
        key = e.keyCode;
    } else if (e.which) {
        key = e.which;
    }

    if (key < 48 || key > 57) {
        if (key === 46 || key === 8) {
            return true;
        } else {
            return false;
        }
    }
    return true;
}


function inicioTablaDescuentos() {
    jQuery("#list_descuentos").jqGrid({
        url: 'json_lista_descuentos.php',
        datatype: 'json',
        colNames: ['DESCRIPCIÓN', 'FECHA DESDE', 'FECHA HASTA', 'CATEGORIA', ''],
        colModel: [
            {name: 'descripcion', index: 'descripcion', search: true, width: 180},
            {name: 'fecha_desde', index: 'fecha_desde', search: false, width: 220},
            {name: 'fecha_hasta', index: 'fecha_hasta', search: false, width: 180},
            {name: 'categoria', index: 'categoria', search: false, width: 180},
            {
                name: "myac",
                width: 50,
                fixed: true,
                sortable: false,
                resize: false,
                formatter: "actions",
                formatoptions: {
                    keys: false,
                    delbutton: true,
                    editbutton: false,
                },
            },
        ],
        rowNum: 10,
        width: null,
        height: 200,
        rowList: [10, 20, 30],
        pager: jQuery('#pager_descuentos'),
        sortname: 'descripcion',
        shrinkToFit: false,
        sortorder: 'asc',
        caption: 'Lista de promociones',
        viewrecords: true,
        ondblClickRow: function (rowid, iRow, iCol, e) {
            let rowData = jQuery('#list_descuentos').jqGrid('getRowData', rowid);
            idDescuento = rowid;
            $("#desc_descripcion").val(rowData.descripcion);
            $("#desc_descripcion").select();
            $("#desc_nro_prod").val(rowData.nro_producto);
            $("#fecha_desde").val(rowData.nro_producto);
            $("#fecha_hasta").val(rowData.nro_producto);
            $("#categoria").val(rowData.nro_producto);
            $("#id_categoria").val(rowData.nro_producto);
            $("#desc_porcentaje").val(rowData.porcentaje_descuento);
            estadoUiModificar();
            $("#alertify-logs").empty();
            alertify.success("Registro cargado.");
        },
        delOptions: {
            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {
                eliminarDescuentoProducto(rowid);
                $(".ui-icon-closethick").trigger("click");
            },
            processing: true,
        },
    }).jqGrid('navGrid', '#pager_descuentos',
            {
                add: false,
                edit: false,
                del: false,
                refresh: true,
                search: true,
                view: false
            },
            {
                recreateForm: true, closeAfterEdit: true, checkOnUpdate: true, reloadAfterSubmit: true, closeOnEscape: true
            },
            {
                reloadAfterSubmit: true, closeAfterAdd: true, checkOnUpdate: true, closeOnEscape: true,
                bottominfo: "Todos los campos son obligatorios"
            },
            {
                width: 300, closeOnEscape: true
            },
            {
                closeOnEscape: true,
                multipleSearch: false, overlay: false
            },
            {
            },
            {
                closeOnEscape: true
            }
    );
}
var idDescuento = 0;
function inicioTablaDetDescuentos() {
    jQuery("#list_det_descuentos").jqGrid({
        url: 'json_lista_detalle_descuentos.php',
        datatype: 'json',
        colNames: ['ID_PRODUCTO', 'COD. BARRAS', 'ARTÍCULO', ""],
        colModel: [
            {name: 'id_producto', index: 'id_producto', search: false, hidden: true},
            {name: 'cod_barras', index: 'cod_barras', search: false, width: 180},
            {name: 'articulo', index: 'articulo', search: false, width: 220},
            {
                name: "myac",
                width: 50,
                fixed: true,
                sortable: false,
                resize: false,
                formatter: "actions",
                formatoptions: {
                    keys: false,
                    delbutton: true,
                    editbutton: false,
                },
            },
        ],
        rowNum: 10,
        width: null,
        height: 200,
        rowList: [10, 20, 30],
        pager: jQuery('#pager_det_descuentos'),
        sortname: 'articulo',
        shrinkToFit: false,
        sortorder: 'asc',
        caption: 'Descuentos',
        viewrecords: true,
        delOptions: {
            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {
                let rowData = jQuery('#list_det_descuentos').jqGrid('getRowData', rowid);
                eliminarDetalleDescuentoProducto(idDescuento, rowData.id_producto);
                $(".ui-icon-closethick").trigger("click");
            },
            processing: true,
        },
    }).jqGrid('navGrid', '#pager_det_descuentos',
            {
                add: false,
                edit: false,
                del: false,
                refresh: true,
                search: true,
                view: false
            },
            {
                recreateForm: true, closeAfterEdit: true, checkOnUpdate: true, reloadAfterSubmit: true, closeOnEscape: true
            },
            {
                reloadAfterSubmit: true, closeAfterAdd: true, checkOnUpdate: true, closeOnEscape: true,
                bottominfo: "Todos los campos son obligatorios"
            },
            {
                width: 300, closeOnEscape: true
            },
            {
                closeOnEscape: true,
                multipleSearch: false, overlay: false
            },
            {
            },
            {
                closeOnEscape: true
            }
    );
}
function guardar() {
    if ($("#desc_descripcion").val() == "") {
        $("#desc_descripcion").focus();
        alertify.error("Ingrese descripción");
        return false;
    }
    if ($("#fecha_desde").val() == "") {
        $("#fecha_desde").focus();
        alertify.error("Ingrese Fecha Desde");
        return false;
    }
    if ($("#fecha_hasta").val() == "") {
        $("#fecha_hasta").focus();
        alertify.error("Ingrese Fecha Hasta");
        return false;
    }
    if ($("#id_categoria").val() == "") {
        $("#id_categoria").focus();
        alertify.alert("Ingrese la Categoría");
        return false;
    }
    if ($("#porcentaje_promo").val() == "") {
        $("#porcentaje_promo").focus();
        alertify.alert("Ingrese número de Promoción");
        return false;
    }


    if (idDescuento > 0) {
        modificarDescuentoProducto();
    } else {
        guardarDescuentoProducto();
    }
}
function inicio() {
    ////////////////////////////////////
    $("#categoria").autocomplete({
        source: "buscar_categoria.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#categoria").val(ui.item.value);
            $("#id_categoria").val(ui.item.id_categoria);
            return false;
        },
        select: function (event, ui) {
            $("#categoria").val(ui.item.value);
            $("#id_categoria").val(ui.item.id_categoria);
            return false;
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
    };
    inicioTablaDescuentos();
    inicioTablaDetDescuentos();
    $("#dialogo_sel_prod_desc").dialog({
        modal: true,
        width: 860,
        autoOpen: false,
        title: "PRODUCTOS",
        open: function (event, ui) {
            $("#list_det_descuentos").jqGrid("setGridParam", {
                url: 'json_lista_detalle_descuentos.php?id_descuento=' + idDescuento,
                page: 1
            });
            $("#list_det_descuentos").trigger("reloadGrid");
        },
        close: function (event, ui) {

        }
    });
    $("#btn_add_descuento").click(function (e) {
        guardar();
    });
    $("#btn_cancel_update").click(function (e) {
        cancelarModificacion();
        $("#alertify-logs").empty();
        alertify.log("Acción cancelada");
    });
    $("#btn_update_descuento").click(function (e) {
        modificarDescuentoProducto();
    });
    $("#btn_sel_desc_prods").click(function (e) {
        $("#dialogo_sel_prod_desc").dialog("open");
    });

    $("#buscar_prod_desc").autocomplete({
        source: "buscar_productos.php",
        minLength: 1,
        focus: function (event, ui) {
            $("#buscar_prod_desc").val(ui.item.value);
            return false;
        },
        select: function (event, ui) {
            $("#buscar_prod_desc").val(ui.item.value);
            guardarDetalleDescuento(ui.item.cod_producto, idDescuento);
            $("#buscar_prod_desc").val("");
            return false;
        }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
    };

    $("#buscar_prod_desc").keydown(function (e) {
        if (e.key == "Enter") {
            $.ajax({
                url: "buscar_categoria.php?tipo=codigo",
                method: "GET",
                dataType: "json",
                data: {term: $("#buscar_prod_desc").val()},
                success: function (data) {
                    if (data.length == 1) {
                        guardarDetalleDescuento(data[0].cod_producto, idDescuento);
                        $("#buscar_prod_desc").autocomplete("search", "");
                        $("#buscar_prod_desc").val("");
                    }
                }
            });
        }
    });

    $("#desc_descripcion").keypress(function (e) {
        if (e.key == "Enter") {
            $("#desc_nro_prod").focus();
            return false;
        }
        e.stopPropagation();
    });
    $("#desc_nro_prod").keypress(function (e) {
        if (e.key == "Enter") {
            $("#desc_porcentaje").focus();
            return false;
        }
        e.stopPropagation();
    });
    $("#desc_porcentaje").keypress(function (e) {
        if (e.key == "Enter") {
            guardar();
            return false;
        }
        e.stopPropagation();
    });

}

