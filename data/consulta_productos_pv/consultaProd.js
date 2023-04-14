$(document).ready(inicio);
function inicio() {

}

function initTablaCajasAbiertas() {
    jQuery("#lista_prod")
        .jqGrid({
            url: `xmlCajasAbiertas.php`,
            datatype: "xml",
            colNames: [
                'COD. BARRAS',
                'ARTÍCULO',
            ],
            colModel: [
                {
                    name: 'cod_barras',
                    index: 'cod_barras',
                    width: 120
                },
                {
                    name: 'articulo',
                    index: 'articulo',
                    width: 120
                },
            ],
            rowNum: 30,
            width: null,
            shrinkToFit: false,
            height: 220,
            sortable: true,
            rowList: [10, 20, 30],
            pager: jQuery("#pager_lista_prod"),
            //rownumbers: true,
            sortname: "cc.fecha_actual",
            sortorder: "desc",
            ondblClickRow: function (rowid, iRow, iCol, e) {
            },
            afterInsertRow: function (rowid, rowdata, rowelem) {
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