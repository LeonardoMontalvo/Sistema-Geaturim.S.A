$(document).ready(inicioCaracteristicasProd);

var caracteristicas = [];
var caractCodProd = 0;

function inicioCaracteristicasProd() {
    $("#btn_prod_caracteristicas").click(function (e) {
        $("#dialogo_prod_caracteristicas").dialog("open");
    });
    $("#btn_add_caracteristica").click(function (e) {
        if (!!!caractCodProd) {
            alertify.error("Seleccione un producto.");
            $("#btn_prod_caracteristicas").focus();
            return;
        }
        if (!!!$("#nombre_caracteristica").val()) {
            alertify.error("Ingrese nombre de la característica");
            $("#nombre_caracteristica").focus();
            return;
        }

        let obj = {
            cod_productos: caractCodProd,
            nombre: $("#nombre_caracteristica").val().toUpperCase()
        };
        addCaracteristica(obj);
        $("#nombre_caracteristica").val("");
        $("#nombre_caracteristica").focus();

    });

    $("#nombre_caracteristica").keypress(function (e) {
        if (e.key == "Enter") {
            $("#btn_add_caracteristica").click();
            return false;
        }
        e.stopPropagation();
    });

    $("#dialogo_prod_caracteristicas").dialog({
        modal: true,
        width: 860,
        autoOpen: false,
        title: "PRODUCTOS",
        close: function (event, ui) {

        }
    });

    jQuery("#list_prod_caracteristicas").jqGrid({
        url: 'datos_productos.php',
        datatype: 'xml',
        colNames: ['ID', 'CÓDIGO', 'CÓDIGO BARRAS', 'ARTICULO', 'IVA', 'SERIES', 'PRECIO COMPRA', 'UTILIDAD MINORISTA', 'PRECIO MINORISTA', 'UTILIDAD MAYORISTA', 'PRECIO MAYORISTA', 'GENERICO', 'CATEGORÍA', 'DESCUENTO', 'STOCK', 'ID USUARIO', 'MÌNIMO', 'MÀXIMO', 'FECHA COMPRA', 'MARCA', 'APLICACION', 'ESTADO', 'INVENTARIABLE', 'IMAGEN', '', 'BODEGA', 'INCLUYE IVA', 'UTILIDAD NEGOCIO', 'PRECIO NEGOCIO', 'ID PLAN CUENTAS', 'CUENTA CONTABLE', 'NOMBRE PROVEEDOR', 'PROVEEEDOR', 'CANTIDAD DESCUENTO', 'BIEN / SERVICIO', 'CANTIDAD MAYORISTA', 'CANTIDAD NEGOCIO'],
        colModel: [
            { name: 'cod_productos', index: 'cod_productos', editable: true, align: 'center', width: '60', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'cod_prod', index: 'cod_prod', editable: true, align: 'center', width: '120', search: false, frozen: true, formoptions: { elmsuffix: " (*)" }, editrules: { required: true } },
            { name: 'cod_barras', index: 'cod_barras', editable: true, align: 'center', width: '120', search: true, frozen: true, formoptions: { elmsuffix: " (*)" }, editrules: { required: true } },
            { name: 'nombre_art', index: 'nombre_art', editable: true, align: 'center', width: '180', search: true, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'iva', index: 'iva', editable: true, align: 'center', width: '50', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'series', index: 'series', editable: true, align: 'center', width: '50', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'precio_compra', index: 'precio_compra', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'utilidad_minorista', index: 'utilidad_minorista', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'precio_minorista', index: 'precio_minorista', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'utilidad_mayorista', index: 'utilidad_mayorista', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'precio_mayorista', index: 'precio_mayorista', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'categoria', index: 'categoria', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'marca', index: 'marca', editable: true, align: 'center', width: '180', search: true, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'descuento', index: 'descuento', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'stock', index: 'stock', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'id_usuario', index: 'id_usuario', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'minimo', index: 'minimo', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'maximo', index: 'maximo', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'fecha_creacion', index: 'fecha_creacion', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'modelo', index: 'modelo', editable: true, align: 'center', width: '180', search: true, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'aplicacion', index: 'aplicacion', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'vendible', index: 'vendible', editable: true, align: 'center', hidden: true, width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'inventario', index: 'inventario', editable: true, align: 'center', hidden: true, width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'imagen', index: 'imagen', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'bodegas', index: 'bodegas', hidden: true, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'descripcion', index: 'descripcion', hidden: false, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'incluye', index: 'incluye', hidden: false, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'utilidad_negocio', index: 'utilidad_mayorista', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'precio_negocio', index: 'precio_negocio', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'idcontable', index: 'incluye', hidden: true, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'ccontable', index: 'incluye', hidden: false, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'nombre_proveedor', index: 'incluye', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'proveedor', index: 'incluye', hidden: true, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'cantidad_descuento', index: 'incluye', editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'bien_servicio', index: 'incluye', hidden: true, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'cantidad_mayorista', index: 'cantidad_mayorista', hidden: false, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
            { name: 'cantidad_negocio', index: 'cantidad_negocio', hidden: false, editable: true, align: 'center', width: '120', search: false, frozen: true, editoptions: { readonly: 'readonly' }, formoptions: { elmprefix: "" } },
        ],
        rowNum: 10,
        width: 830,
        height: 200,
        rowList: [10, 20, 30],
        pager: jQuery('#pager_prod_caracteristicas'),
        sortname: 'cod_productos',
        shrinkToFit: false,
        sortorder: 'asc',
        caption: 'Lista de Productos',
        viewrecords: true,
        ondblClickRow: function () {
            var id = jQuery("#list_prod_caracteristicas").jqGrid('getGridParam', 'selrow');
            var ret = jQuery("#list_prod_caracteristicas").jqGrid('getRowData', id);

            //$("#cod_barras_prod_caracteristicas").val(ret.cod_barras);
            $("#cod_barras_prod_caracteristicas").text(ret.cod_barras);
            //$("#nombre_prod_caracteristicas").val(ret.nombre_art);
            $("#nombre_prod_caracteristicas").text(ret.nombre_art);
            caractCodProd = ret.cod_productos;
            obtenerCaracteristicasProducto(caractCodProd)

            $("#dialogo_prod_caracteristicas").dialog("close");
            $("#nombre_caracteristica").focus();
            mostrarInfoProdCaracteristicas();
        }
    }).jqGrid('navGrid', '#pager_prod_caracteristicas',
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

    jQuery("#list_caracteristicas").jqGrid({
        datatype: "local",
        colNames: ["Caracteríastica", ""],
        colModel: [
            {
                name: "nombre",
                align: "center",
            },
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
        recordpos: 'left',
        rownumbers: true,
        width: null,
        shrinkToFit: false,
        delOptions: {
            modal: true,
            jqModal: true,
            onclickSubmit: function (rp_ge, rowid) {
                quitarCaracteristica(rowid);
                $(".ui-icon-closethick").trigger("click");
            },
            processing: true,
        },
    });

}

function addCaracteristica(item) {
    guardarCaracteristica(item)
        .then(function (data) {
            obtenerCaracteristicasProducto(caractCodProd);
        });
}

function quitarCaracteristica(idcaracteristica) {
    eliminarCaracteristica(idcaracteristica)
        .then(function (data) {
            obtenerCaracteristicasProducto(caractCodProd);
        });
}

function llenarTablaCaracteristicas() {
    jQuery("#list_caracteristicas").jqGrid("clearGridData");
    caracteristicas.forEach((el, i) => {
        let obj = {
            nombre: el.nombre,
            cod_productos: el.cod_productos
        };
        jQuery("#list_caracteristicas").jqGrid("addRowData", el.id_caracteristica, obj);
    });
}

function mostrarInfoProdCaracteristicas() {
    $("#info_prod_caracteristicas").show();
    $("#empty_prod_caracteristicas").hide();
}

function mostrarMensajeEmptyProdCaracteristicas() {
    $("#info_prod_caracteristicas").hide();
    $("#empty_prod_caracteristicas").show();
}

function guardarCaracteristica(item) {
    return $.ajax({
        url: "guardar_caracteristica.php",
        data: item,
        method: "post"
    });
}

function eliminarCaracteristica(idcaracteristica) {
    return $.ajax({
        url: "eliminar_caracteristica_producto.php",
        method: "post",
        data: { id_caracteristica: idcaracteristica }
    });
}

function obtenerCaracteristicasProducto(codprod) {
    $.ajax({
        url: "lista_caracteristicas_producto.php",
        method: "get",
        dataType: "json",
        data: { cod_productos: codprod },
        success: function (data) {
            caracteristicas = data;
            llenarTablaCaracteristicas();
        }
    });
}