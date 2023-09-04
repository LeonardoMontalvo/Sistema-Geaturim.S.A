$(document).ready(inicioCaracteristicasProd_pvpv);

var caracteristicas_pvpv = [];
var caractCodProd_pvpv = 0;
var nombre_art_pvpv = "";
function inicioCaracteristicasProd_pvpv() {
    obtenerCaracteristicasProducto_pvpv();
    $("#btn_prod_caracteristicas_pvpv").click(function (e) {

        $("#dialogo_prod_caracteristicas_pvpv").dialog("open");
    });
    $("#btn_add_caracteristica_pvpv").click(function (e) {
        if (!!!caractCodProd_pvpv) {
            alertify.error("Seleccione un producto.");
            $("#btn_prod_caracteristicas_pvpv").focus();
            return;
        }
        if (!!!$("#nombre_caracteristica_pvpv").val()) {
            $("#nombre_caracteristica_pvpv").focus();
            return;
        }

        let obj = {
            cod_productos: caractCodProd_pvpv,
            articulo: nombre_art_pvpv,
            editar_pvp: $("#nombre_caracteristica_pvpv").val().toUpperCase()
        };
        addCaracteristica_pvpv(obj);
        $("#btn btn-primary").focus();

    });

    $("#nombre_caracteristica_pvpv").keypress(function (e) {
        if (e.key == "Enter") {
            $("#btn_add_caracteristica_pvpv").click();
            return false;
        }
        e.stopPropagation();
    });

    $("#dialogo_prod_caracteristicas_pvpv").dialog({
        modal: true,
        width: 860,
        autoOpen: false,
        title: "PRODUCTOS",
        close: function (event, ui) {
        }
    });

    jQuery("#list_prod_caracteristicas_pvpv").jqGrid({
        url: 'datos_productos.php',
        datatype: 'xml',
        colNames: ['ID', 'CÓDIGO', 'CÓDIGO BARRAS', 'ARTICULO', 'IVA', 'SERIES', 'PRECIO COMPRA', 'UTILIDAD MINORISTA', 'PRECIO MINORISTA', 'UTILIDAD MAYORISTA', 'PRECIO MAYORISTA', 'GENERICO', 'CATEGORÍA', 'DESCUENTO', 'STOCK', 'ID USUARIO', 'MÌNIMO', 'MÀXIMO', 'FECHA COMPRA', 'MARCA', 'APLICACION', 'ESTADO', 'INVENTARIABLE', 'IMAGEN', '', 'BODEGA', 'INCLUYE IVA', 'UTILIDAD NEGOCIO', 'PRECIO NEGOCIO', 'ID PLAN CUENTAS', 'CUENTA CONTABLE', 'NOMBRE PROVEEDOR', 'PROVEEEDOR', 'CANTIDAD DESCUENTO', 'BIEN / SERVICIO', 'CANTIDAD MAYORISTA', 'CANTIDAD NEGOCIO'],
        colModel: [
            {name: 'cod_productos', index: 'cod_productos', editable: true, align: 'center', width: '60', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'cod_prod', index: 'cod_prod', editable: true, align: 'center', width: '120', search: false, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'cod_barras', index: 'cod_barras', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: {elmsuffix: " (*)"}, editrules: {required: true}},
            {name: 'nombre_art', index: 'nombre_art', editable: true, align: 'center', width: '180', search: true, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'iva', index: 'iva', editable: true, align: 'center', width: '50', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'series', index: 'series', editable: true, align: 'center', width: '50', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'precio_compra', index: 'precio_compra', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'utilidad_minorista', index: 'utilidad_minorista', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'precio_minorista', index: 'precio_minorista', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'utilidad_mayorista', index: 'utilidad_mayorista', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'precio_mayorista', index: 'precio_mayorista', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'categoria', index: 'categoria', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'marca', index: 'marca', editable: true, align: 'center', width: '180', search: true, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'descuento', index: 'descuento', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'stock', index: 'stock', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'id_usuario', index: 'id_usuario', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'minimo', index: 'minimo', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'maximo', index: 'maximo', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'fecha_creacion', index: 'fecha_creacion', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'modelo', index: 'modelo', editable: true, align: 'center', width: '180', search: true, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'aplicacion', index: 'aplicacion', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'vendible', index: 'vendible', editable: true, align: 'center', hidden: true, width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'inventario', index: 'inventario', editable: true, align: 'center', hidden: true, width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'imagen', index: 'imagen', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'bodegas', index: 'bodegas', hidden: true, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'descripcion', index: 'descripcion', hidden: false, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'incluye', index: 'incluye', hidden: false, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'utilidad_negocio', index: 'utilidad_mayorista', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'precio_negocio', index: 'precio_negocio', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'idcontable', index: 'incluye', hidden: true, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'ccontable', index: 'incluye', hidden: false, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'nombre_proveedor', index: 'incluye', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'proveedor', index: 'incluye', hidden: true, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'cantidad_descuento', index: 'incluye', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'bien_servicio', index: 'incluye', hidden: true, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'cantidad_mayorista', index: 'cantidad_mayorista', hidden: false, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
            {name: 'cantidad_negocio', index: 'cantidad_negocio', hidden: false, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: {readonly: 'readonly'}, formoptions: {elmprefix: ""}},
        ],
        rowNum: 10,
        width: 830,
        height: 200,
        rowList: [10, 20, 30],
        pager: jQuery('#pager_prod_caracteristicas_pvpv'),
        sortname: 'cod_productos',
        shrinkToFit: false,
        sortorder: 'asc',
        caption: 'Lista de Productos',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list_prod_caracteristicas_pvpv").jqGrid('getGridParam', 'selrow');
            var ret = jQuery("#list_prod_caracteristicas_pvpv").jqGrid('getRowData', id);
            $("#cod_barras_prod_caracteristicas_pvpv").text(ret.cod_barras);

            $("#nombre_prod_caracteristicas_pvpv").text(ret.nombre_art);
            caractCodProd_pvpv = ret.cod_productos;
            nombre_art_pvpv = ret.nombre_art;
            $("#dialogo_prod_caracteristicas_pvpv").dialog("close");
            $("#nombre_caracteristica_pvpv").focus();
            mostrarInfoProdCaracteristicas_pvpv();
        }
    }).jqGrid('navGrid', '#pager_prod_caracteristicas_pvpv',
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

function addCaracteristica_pvpv(item) {
    guardarCaracteristica_pvpv(item);
}

function quitarCaracteristica_pvpv(idcaracteristica_pvpv) {
    eliminarCaracteristica_pvpv(idcaracteristica_pvpv);

}

function llenarTablaCaracteristicas_pvpv() {
    jQuery("#list_caracteristicas_pvpv").jqGrid("clearGridData");
    caracteristicas_pvpv.forEach((el, i) => {
        let obj = {
            cod_productos: el.cod_productos,
            articulo: el.nombre_art,
            editar_pvp: el.nombre,
        };
        jQuery("#list_caracteristicas_pvpv").jqGrid("addRowData", el.id_caracteristica, obj);
    });
}

function mostrarInfoProdCaracteristicas_pvpv() {
    $("#info_prod_caracteristicas_pvpv").show();
    $("#empty_prod_caracteristicas_pvpv").hide();
}

function mostrarMensajeEmptyProdCaracteristicas_pvpv() {
    $("#info_prod_caracteristicas_pvpv").hide();
    $("#empty_prod_caracteristicas_pvpv").show();
}

function guardarCaracteristica_pvpv(item) {
    return $.ajax({
        url: "guardar_caracteristica_pvpv.php",
        data: item,
        method: "post",
        success: function (item) {
            $("#list_caracteristicas_pvpv").trigger("reloadGrid");
            $("#alertify-logs").empty();
            alertify.success("Registro guardado.");
        }

    });
}

function eliminarCaracteristica_pvpv(idcaracteristica_pvpv) {
    return $.ajax({
        url: "eliminar_caracteristica_producto_pvpv.php",
        method: "post",
        data: {id_caracteristica_pvpv: idcaracteristica_pvpv},

        success: function (data) {
            $("#list_caracteristicas_pvpv").trigger("reloadGrid");
            alertify.success("Registro eliminado.");
        }
    });

}

function obtenerCaracteristicasProducto_pvpv() {

    jQuery("#list_caracteristicas_pvpv").jqGrid({
        url: 'lista_caracteristicas_producto_pvpv.php',
        datatype: "json",
        colNames: ["ID", "ID COD", " Cod Barras  ", "Producto", "PVP E.", ""],
        colModel: [
            {name: "id_pvp_venta_editable", align: "center", hidden: true, },
            {name: "cod_productos", align: "center", hidden: true, },
            {name: "cod_barras", align: "center", hidden: false, width: 150},
            {name: "articulo", align: "left", width: 250},
            {name: "editar_pvp", align: "center", width: 30},
            {name: "myac", width: 50, fixed: true, sortable: false, resize: false, formatter: "actions", formatoptions: {keys: false, delbutton: true, editbutton: false, }, },
        ],
        recordpos: 'left',
        rownumbers: true,
        width: null,
        shrinkToFit: false,
        delOptions: {
            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {
                let rowData = jQuery('#list_caracteristicas_pvpv').jqGrid('getRowData', rowid);
                quitarCaracteristica_pvpv(rowData.id_pvp_venta_editable);
                $(".ui-icon-closethick").trigger("click");

            },
            processing: true,
        },

    });
}