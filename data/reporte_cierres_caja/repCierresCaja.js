$(document).ready(inicio);
let idCajaSeleccionada = 0;
let fechaCierre = '';
function inicio() {
    initTablaCajasAbiertas();
    $("#btn_rep_aper").click(function (e) {
        imprimirApetura();
    });
    $("#btn_rep_cierr").click(function (e) {
        imprimirCierre();
    });
    $("#btn_rep_stock_cierr").click(function (e) {
        imprimirStockCierre();
    });
    $("#btn_rep_prod_ven").click(function (e) {
        imprimirProdVend();
    });
    $("#sel_usuario_ca").change(function (e) {
        reloadGrid();
    });
}
function initTablaCajasAbiertas() {
    jQuery("#lista_cierres")
        .jqGrid({
            url: `json_lista_cierres_caja.php`,
            datatype: "json",
            colNames: [
                'ID',
                'USUAIO',
                'FECHA APERTURA',
                'HORA APERTURA',
                'MONTO APERTURA',
                'FECHA CIERRE',
                'HORA CIERRE',
                'VALOR INFORMADO',
            ],
            colModel: [
                {
                    name: 'id_cierre_caja',
                    index: 'id_cierre_caja',
                    width: 120,
                    hidden: true
                },
                {
                    name: 'usuario',
                    index: 'usuario',
                    width: 120
                },
                {
                    name: 'fecha_actual',
                    index: 'fecha_actual',
                    width: 120
                },
                {
                    name: 'hora_actual',
                    index: 'hora_actual',
                    width: 120
                },
                {
                    name: 'monto_apertura',
                    index: 'monto_apertura',
                    width: 100
                },
                {
                    name: 'fecha_cierre',
                    index: 'fecha_cierre',
                    width: 100
                },
                {
                    name: 'hora_cierre',
                    index: 'hora_cierre',
                    width: 100
                },
                {
                    name: 'total_valor_ingresado',
                    index: 'total_valor_ingresado',
                    width: 100
                }
            ],
            rowNum: 30,
            width: null,
            shrinkToFit: false,
            height: 220,
            sortable: true,
            rowList: [10, 20, 30],
            pager: jQuery("#pager_lista_cierres"),
            rownumbers: true,
            sortname: "cc.fecha_actual desc, cc.hora_actual",
            sortorder: "desc",
            rowattr: function (rowData, currentObj, rowId) {
                console.log(currentObj.estado);
                if (currentObj.estado == 'Pasivo') {
                    return {
                        style: "background:#EF9A9A"
                    }
                }
            },
            onSelectRow: function (rowid, status, e) {
                if (status) {
                    let row = jQuery("#lista_cierres").jqGrid("getRowData", rowid);
                    idCajaSeleccionada = rowid;
                    fechaCierre = row["fecha_cierre"];
                }

            }
        })
        .jqGrid(
            "navGrid",
            "#pager_lista_cierres",
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

function imprimirApetura() {
    if (idCajaSeleccionada == 0) {
        alertify.alert("<b>Seleccione un registro de la tabla.</b>");
        return;
    }
    var myWindow = window.open("../../reportes/apertura_caja_ant.php?hoja=A4&id=" + idCajaSeleccionada, '_blank');
    myWindow.focus();

}
function imprimirCierre() {
    if (idCajaSeleccionada == 0) {
        alertify.alert("<b>Seleccione un registro de la tabla.</b>");
        return;
    }
    if (fechaCierre == '') {
        alertify.alert("<b>La caja seleccionada no esta cerrada.</b>");
        return;
    }
    var myWindow = window.open("../../reportes/cierre_caja_ant.php?hoja=A4&id=" + idCajaSeleccionada, '_blank');
    myWindow.focus();

}
function imprimirStockCierre() {
    if (idCajaSeleccionada == 0) {
        alertify.alert("<b>Seleccione un registro de la tabla.</b>");
        return;
    }
    if (fechaCierre == '') {
        alertify.alert("<b>La caja seleccionada no esta cerrada.</b>");
        return;
    }
    var myWindow = window.open("../../reportes/prod_rel_cantidad.php?hoja=A4&id=" + idCajaSeleccionada, '_blank');
    myWindow.focus();

}
function imprimirProdVend() {
    if (idCajaSeleccionada == 0) {
        alertify.alert("<b>Seleccione un registro de la tabla.</b>");
        return;
    }
    if (fechaCierre == '') {
        alertify.alert("<b>La caja seleccionada no esta cerrada.</b>");
        return;
    }
    var myWindow = window.open("../../reportes/resumenVentaProductosCierreCaja.php?hoja=A4&id=" + idCajaSeleccionada, '_blank');
    myWindow.focus();

}

function reloadGrid() {
    jQuery("#lista_cierres").setGridParam({
        url: 'json_lista_cierres_caja.php?id_usuario=' + $("#sel_usuario_ca").val(),
        page: 1
    }).trigger("reloadGrid");
}
