$(document).ready(inicio);
function inicio() {
    initTablaCajasAbiertas();
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
                    width: 20
                },
                {
                    name: 'articulo',
                    index: 'articulo',
                    width: 300
                },
                {
                    name: 'stock',
                    index: 'stock',
                    width: 80
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
                groupText: ['<b><span style="font-size:10pt">{0}</span></b>']
            },
            afterInsertRow:function(rowid,rowdata,rowelem){

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