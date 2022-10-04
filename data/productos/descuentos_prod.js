$(document).ready(inicioDescuentosProd);

var idDescuento = 0;

function inicioDescuentosProd() {
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
                url: "buscar_productos.php?tipo=codigo",
                method: "GET",
                dataType: "json",
                data: { term: $("#buscar_prod_desc").val() },
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

function guardar() {
    if ($("#desc_descripcion").val() == "") {
        $("#desc_descripcion").focus();
        alertify.error("Ingrese descripción");
        return false;
    }
    if ($("#desc_nro_prod").val() == "") {
        $("#desc_nro_prod").focus();
        alertify.error("Ingrese número de producto");
        return false;
    }
    if (Number($("#desc_nro_prod").val()) <= 0) {
        $("#desc_nro_prod").focus();
        alertify.error("N debe ser un número mayor que 0");
        return false;
    }
    if ($("#desc_porcentaje").val() == "") {
        $("#desc_porcentaje").focus();
        alertify.alert("Ingrese número de porcentaje");
        return false;
    }
    if ((Number($("#desc_porcentaje").val()) < 0) || (Number($("#desc_porcentaje").val()) > 100)) {
        $("#desc_porcentaje").focus();
        alertify.alert("El Porcentaje X debe ser un número entre 0 y 100");
        return false;
    }

    if (idDescuento > 0) {
        modificarDescuentoProducto();
    } else {
        guardarDescuentoProducto();
    }
}

function inicioTablaDescuentos() {
    jQuery("#list_descuentos").jqGrid({
        url: 'json_lista_descuentos.php',
        datatype: 'json',
        colNames: ['DESCRIPCIÓN', 'EN LA COMPRA DEL PRODUCTO NRO.', 'PORCENTAJE DESCUENTO', ''],
        colModel: [
            { name: 'descripcion', index: 'descripcion', search: true, width: 180 },
            { name: 'nro_producto', index: 'nro_producto', search: false, width: 220 },
            { name: 'porcentaje_descuento', index: 'porcentaje_descuento', search: false, width: 180 },
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
        caption: 'Lista de Descuentos',
        viewrecords: true,
        ondblClickRow: function (rowid, iRow, iCol, e) {
            let rowData = jQuery('#list_descuentos').jqGrid('getRowData', rowid);
            idDescuento = rowid;
            $("#desc_descripcion").val(rowData.descripcion);
            $("#desc_descripcion").select();
            $("#desc_nro_prod").val(rowData.nro_producto);
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

function inicioTablaDetDescuentos() {
    jQuery("#list_det_descuentos").jqGrid({
        url: 'json_lista_detalle_descuentos.php',
        datatype: 'json',
        colNames: ['ID_PRODUCTO', 'COD. BARRAS', 'ARTÍCULO', ""],
        colModel: [
            { name: 'id_producto', index: 'id_producto', search: false, hidden: true },
            { name: 'cod_barras', index: 'cod_barras', search: false, width: 180 },
            { name: 'articulo', index: 'articulo', search: false, width: 220 },
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

function guardarDescuentoProducto() {
    let desc = $("#desc_descripcion").val();
    let nro = $("#desc_nro_prod").val();
    let porc = $("#desc_porcentaje").val();

    $.ajax({
        url: "guardar_descuento.php",
        method: "post",
        dataType: "json",
        data: {
            descripcion: desc,
            nro_producto: nro,
            porcentaje: porc
        },
        success: function (data) {
            $("#list_descuentos").trigger("reloadGrid");
            cancelarModificacion();
            $("#alertify-logs").empty();
            alertify.success("Registro guardado.");
        }
    });
}

function modificarDescuentoProducto() {
    let desc = $("#desc_descripcion").val();
    let nro = $("#desc_nro_prod").val();
    let porc = $("#desc_porcentaje").val();

    $.ajax({
        url: "modificar_descuento_producto.php",
        method: "post",
        dataType: "json",
        data: {
            id_descuento: idDescuento,
            descripcion: desc,
            nro_producto: nro,
            porcentaje: porc
        },
        success: function (data) {
            $("#list_descuentos").trigger("reloadGrid");
            cancelarModificacion();
            $("#alertify-logs").empty();
            alertify.success("Registro modificado.");
        }
    });
}

function eliminarDescuentoProducto(iddescuento) {
    $.ajax({
        url: "eliminar_descuento_producto.php",
        method: "post",
        dataType: "json",
        data: {
            id_descuento: iddescuento
        },
        success: function (data) {
            $("#list_descuentos").trigger("reloadGrid");
            cancelarModificacion();
        }
    });
}

function estadoUiGuardar() {
    $("#div_guardar_desc").show();
    $("#div_modificar_desc").hide();
    $("#div_sel_desc_prod").hide();
}

function estadoUiModificar() {
    $("#div_guardar_desc").hide();
    $("#div_modificar_desc").show();
    $("#div_sel_desc_prod").show();
}

function cancelarModificacion() {
    idDescuento = 0;
    $("#desc_descripcion").val("");
    $("#desc_nro_prod").val("");
    $("#desc_porcentaje").val("");
    estadoUiGuardar();
}

function guardarDetalleDescuento(idproducto, iddescuento) {
    $.ajax({
        url: "guardar_detalle_descuento.php",
        method: "post",
        dataType: "json",
        data: {
            id_descuento: iddescuento,
            id_producto: idproducto,
        },
        success: function (data) {
            $("#list_det_descuentos").trigger("reloadGrid");
            alertify.success("Producto añadido");
        }
    });
}

function eliminarDetalleDescuentoProducto(iddescuento, idproducto) {
    $.ajax({
        url: "eliminar_detalle_descuento.php",
        method: "post",
        dataType: "json",
        data: {
            id_descuento: iddescuento,
            id_producto: idproducto
        },
        success: function (data) {
            $("#list_det_descuentos").trigger("reloadGrid");
        }
    });
}