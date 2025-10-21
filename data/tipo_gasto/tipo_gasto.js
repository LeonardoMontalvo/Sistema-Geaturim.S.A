$(document).ready(function () {
    initTabla();
});

function initTabla() {
    $(window).on("resize", function () {
        jQuery("#list").setGridWidth($('#centro'));
    }).trigger('resize');


    jQuery("#list").jqGrid({
        url: 'xmlTipoGasto.php',
        datatype: 'xml',
        colNames: ['', 'ID', 'Tipo Gasto', 'Cuenta Contable', 'Cuenta Contable'],
        colModel: [
            {
                name: "myac",
                width: 50,
                fixed: true,
                sortable: false,
                resize: false,
                formatter: "actions",
                search:false,
                formatoptions: {
                    keys: false,
                    delbutton: true,
                    editbutton: false,
                },
            },
            {
                name: 'id_tipo_gasto',
                index: 'id_tipo_gasto',
                editable: false,
                width: '120',
                search: false,
                frozen: true,
                hidden: true
            },
            {
                name: 'nombre_tipo_gasto',
                index: 'nombre_tipo_gasto',
                editable: true,
                width: '200',
                search: true,
                frozen: true,
                formoptions: { elmprefix: "*" },
                editrules: { required: true },
                searchoptions:{sopt:["cn","eq"]}
            },
            {
                name: 'descripcion',
                index: 'descripcion',
                editable: false,
                width: '200',
                search: false,
                frozen: true,
                editrules: { required: true }
            },
            {
                name: 'id_plan_cuentas',
                index: 'id_plan_cuentas',
                editable: true,
                align: 'center',
                width: '120',
                search: false,
                frozen: true,
                formoptions: { elmprefix: "*" },
                editoptions: {
                    dataInit: function (el) {
                        let idselrow = $("#list").jqGrid('getGridParam', 'selrow');
                        let row = $("#list").jqGrid("getRowData", idselrow);
                        $(el).hide();
                        let auxel = $(`<input type="text" class="FormElement ui-widget-content ui-corner-all" style="height:28px; width:148px;"></input>`);
                        auxel.val(row.descripcion);
                        auxel.autocomplete({
                            source: "search.php",
                            minLength: 1,
                            focus: function (event, ui) {
                                auxel.val(ui.item.descripcion);
                                $(el).val(ui.item.id_plan_cuentas);
                                return false;
                            },
                            select: function (event, ui) {
                                auxel.val(ui.item.descripcion);
                                $(el).val(ui.item.id_plan_cuentas);
                                return false;
                            }

                        }).data("ui-autocomplete")._renderItem = function (ul, item) {
                            return $("<li>")
                                .append("<a>" + item.codigo_plan + "</a>")
                                .appendTo(ul);
                        };
                        let paretn = $(el).parent();
                        paretn.append(auxel);
                    }
                },
                hidden: true,
                editrules: { edithidden: true }

            },
        ],
        rownumbers: true,
        rowNum: 10,
        rowList: [10, 20, 30],
        height: 255,
        pager: jQuery('#pager'),
        editurl: "procesoTipoGasto.php",
        sortname: 'nombre_tipo_gasto',
        shrinkToFit: false,
        sortordezr: 'asc',
        caption: 'Tipos Gasto',
        viewrecords: true,
    }).jqGrid('navGrid', '#pager',
        {
            add: true,
            edit: true,
            del: false,
            refresh: true,
            search: true,
            view: true,
            addtext: "Nuevo",
            edittext: "Modificar",
            refreshtext: "Recargar",
            viewtext: "Consultar"
        },
        {
            recreateForm: true, closeAfterEdit: true, checkOnUpdate: true, reloadAfterSubmit: true, closeOnEscape: true
        },
        {
            recreateForm: true, reloadAfterSubmit: true, closeAfterAdd: true, checkOnUpdate: true, closeOnEscape: true,
            bottominfo: "Los campos marcados con (*) son obligatorios", width: 350, checkOnSubmit: false
        },
        {
            width: 300, closeOnEscape: true
        },
        {
            closeOnEscape: true,
            multipleSearch: false, overlay: false
        },
        {
            closeOnEscape: true,
            width: 400
        },
        {
            closeOnEscape: true
        });
    jQuery("#list").setGridWidth($('#centro').width());
}