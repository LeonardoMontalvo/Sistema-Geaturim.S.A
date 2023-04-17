$(document).ready(inicio);
function inicio() {
    initTablaCajasAbiertas();
    $("#buscar_producto").keyup(buscarProductos);
    $("#limpiar_buscar_producto").click(function (e) {
        $("#buscar_producto").val("");
        $("#buscar_producto").trigger("keyup");
    });
}

function initTablaCajasAbiertas() {
    jQuery("#lista_prod")
        .jqGrid({
            url: `json_lista_productos.php`,
            datatype: "json",
            colNames: [
                'BODEGA',
                'ARTÍCULO',
                'STOCK'
            ],
            colModel: [
                {
                    name: 'nombre_punto',
                    index: 'nombre_punto',
                    width: 20,
                    cellattr: function (rowid, val, rawObject, cm, rdata) {
                        return `"style="font-weight: bold; font-size:9pt"`;
                    }
                },
                {
                    name: 'articulo',
                    index: 'articulo',
                    width: 300
                },
                {
                    name: 'stock',
                    index: 'stock',
                    width: 80,
                    cellattr: function (rowid, val, rawObject, cm, rdata) {
                        return `"style="font-weight: bold; font-size:9pt"`;
                    }
                },
            ],
            rowNum: 30,
            //width: 800,
            autowidth: true,
            shrinkToFit: true,
            height: 220,
            sortable: true,
            rowList: [10, 20, 30],
            pager: jQuery("#pager_lista_prod"),
            //rownumbers: true,
            sortname: "articulo, pv.id_punto_venta",
            sortorder: "asc",
            grouping: true,
            groupingView: {
                groupField: ['articulo'],
                groupCollapse: true,
                hideFirstGroupCol: false,
                groupColumnShow: [false],
                groupText: ['<b><i><span style="font-size:10pt">{0}</span></i></b>']
            },
            rowattr: function (rowData, currentObj, rowId) {
                console.log(currentObj);
                return {
                    style: "background:#E0E0E0"
                }
            }
        })
        .jqGrid(
            "navGrid",
            "#pager_lista_prod",
            {
                add: false,
                edit: false,
                del: false,
                refresh: true,
                search: false,
                view: false,
            },
            {
                recreateForm: true,
                closeAfterEdit: true,
                checkOnUpdate: true,
                reloadAfterSubmit: true,
                closeOnEscape: true,
            },
            {
                reloadAfterSubmit: true,
                closeAfterAdd: true,
                checkOnUpdate: true,
                closeOnEscape: true,
                bottominfo: "Todos los campos son obligatorios",
            },
            {
                width: 300,
                closeOnEscape: true,
            },
            {
                closeOnEscape: true,
                multipleSearch: false,
                overlay: false,
            },
            {},
            {
                closeOnEscape: true,
            }
        );
}

function buscarProductos(e) {
    jQuery("#lista_prod")
        .jqGrid('setGridParam', {
            url: "json_lista_productos.php?term=" + $("#buscar_producto").val().toUpperCase(), page: 1
        }).trigger("reloadGrid");
}
