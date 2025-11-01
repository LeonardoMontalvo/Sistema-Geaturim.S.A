export default {
    template: `#lista_ordenes`,
    emits: ["reimprimirOrden","reimprimirOrdenCocina"],
    data() {
        return {
            fechaInicio: (new Date()).toLocaleDateString("fr-CA"),
            fechaFin: (new Date()).toLocaleDateString("fr-CA"),
            tipoDocumento: "factura"
        }
    },
    mounted() {
        const vm=this;
        this.initTablaListaOrdenes();
        $("#tipodoc_lo").change(function(e){
            vm.reloadGrid();
        });
    },
    methods: {
        initTablaListaOrdenes() {
            const vm = this;
            jQuery("#lo_lista")
                .jqGrid({
                    url: `json_lista_ordenes.php?inicio=${this.fechaInicio}&fin=${this.fechaFin}&tipo_doc=${this.tipoDocumento}`,
                    datatype: "json",
                    colNames: [
                        'N° Orden',
                        'Tipo doc.',
                        'Cliente',
                        'Fecha',
                        'Usuario',
                        'Total',
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
                            name: 'fecha_actual',
                            index: 'fecha_actual',
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
                                let btnfactura=`<button style="background:#9CCC65" class="btn btn-secondary btn-block" type="button" id="reimprimir_${options.rowId}" ><i class="fa fa-print" style="font-size:1.3rem; font-weight:bold"></i> IMPRIMIR</button>`;
                                //let  btncocina=`<button type="button" id="reimprimir_cocina_${options.rowId}" style="background:#A5D6A7; padding:5px;"><i class="fa fa-print" style="font-size:1rem;"></i> COCINA</i></button>`;

                                return btnfactura
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
                    sortname: vm.tipoDocumento=='factura'?"ro.id_factura_venta":"ro.id_facturas_novalidas",
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
            const vm=this;
            $("#lo_lista").jqGrid("clearGridData");
            $("#lo_lista").jqGrid("setGridParam", {
                url: `json_lista_ordenes.php?inicio=${this.fechaInicio}&fin=${this.fechaFin}&tipo_doc=${this.tipoDocumento}`,
                page: 1,
                sortname: vm.tipoDocumento=='factura'?"ro.id_factura_venta":"ro.id_facturas_novalidas",
            });
            $("#lo_lista").trigger("reloadGrid");
        },
        reimprimirOrden(iddoc, tipodoc) {
            if(tipodoc=="NOTA_VENTA"){
                tipodoc="NOTA";
            }
            this.$emit("reimprimirOrden", { iddoc, tipodoc });
        },
        reimprimirOrdenCocina(iddoc, tipodoc) {
            this.$emit("reimprimirOrdenCocina", { iddoc, tipodoc });
        }
    }
}