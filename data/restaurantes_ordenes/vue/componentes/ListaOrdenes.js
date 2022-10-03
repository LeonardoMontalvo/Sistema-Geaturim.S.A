export default {
    template: `#lista_ordenes`,
    emits: ["reimprimirOrden","reimprimirOrdenCocina"],
    data() {
        return {
            fechaInicio: (new Date()).toLocaleDateString("fr-CA"),
            fechaFin: (new Date()).toLocaleDateString("fr-CA")
        }
    },
    mounted() {
        this.initTablaListaOrdenes();
    },
    methods: {
        initTablaListaOrdenes() {
            const vm = this;
            jQuery("#lo_lista")
                .jqGrid({
                    url: `json_lista_ordenes.php?inicio=${this.fechaInicio}&fin=${this.fechaFin}`,
                    datatype: "json",
                    colNames: [
                        'N° Orden',
                        'Tipo doc.',
                        'Cliente',
                        'Fecha',
                        'Usuario',
                        'Total Orden',
                        'Imprimir'
                    ],
                    colModel: [
                        {
                            name: 'id_documento',
                            index: 'id_documento',
                            width:80
                        },
                        {
                            name: 'tipo_documento',
                            index: 'tipo_documento',
                            width:80
                        },
                        {
                            name: 'nombres_cli',
                            index: 'nombres_cli',
                            width:180
                        },
                        {
                            name: 'fecha_creacion',
                            index: 'fecha_creacion',
                            width:90
                        },
                        {
                            name: 'usuario',
                            index: 'usuario',
                            width:180
                        },
                        {
                            name: 'total',
                            index: 'total',
                            align: "right",
                            width:100
                        },
                        {
                            name: "acciones",
                            index: "accines",
                            width: 140,
                            align: "center",
                            formatter: function myformatter(cellvalue, options, rowObject) {
                                let btnfactura=`<button type="button" id="reimprimir_${options.rowId}" style="background:#C8E6C9; margin-right:2px; padding:5px;"><i class="fa fa-print" style="font-size:1rem;"> DOC.</i></button>`;
                                let  btncocina=`<button type="button" id="reimprimir_cocina_${options.rowId}" style="background:#A5D6A7; padding:5px;"><i class="fa fa-print" style="font-size:1rem;"></i> COCINA</i></button>`;

                                return btnfactura+btncocina
                            }
                        }
                    ],
                    rowNum: 30,
                    //width: $("#lo_lista").parent().width(),
                    //autowidth: true,
                    width:null,
                    shrinkToFit: false,
                    height: 220,
                    sortable: true,
                    rowList: [10, 20, 30],
                    pager: jQuery("#lo_pager"),
                    rownumbers: true,
                    sortname: "ro.fecha_creacion",
                    sortorder: "desc",
                    afterInsertRow: function (rowid, rowdata, rowelem) {
                        $(`#reimprimir_${rowid}`).click(function (e) {
                            vm.reimprimirOrden(rowdata.id_documento, rowdata.tipo_documento);
                        });
                        $(`#reimprimir_cocina_${rowid}`).click(function (e) {
                            vm.reimprimirOrdenCocina(rowdata.id_documento, rowdata.tipo_documento);
                        });
                    }
                    //viewrecords: true,
                })
                .jqGrid(
                    "navGrid",
                    "#lo_pager",
                    {
                        add: false,
                        edit: false,
                        del: false,
                        refresh: true,
                        search: true,
                        view: true,
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
        },
        reloadGrid() {
            $("#lo_lista").jqGrid("clearGridData");
            $("#lo_lista").jqGrid("setGridParam", {
                url: `json_lista_ordenes.php?inicio=${this.fechaInicio}&fin=${this.fechaFin}`,
                page: 1,
            });
            $("#lo_lista").trigger("reloadGrid");
        },
        reimprimirOrden(iddoc, tipodoc) {
            this.$emit("reimprimirOrden", { iddoc, tipodoc });
        },
        reimprimirOrdenCocina(iddoc, tipodoc) {
            this.$emit("reimprimirOrdenCocina", { iddoc, tipodoc });
        }
    }
}