$(document).ready(inicio);

function inicio() {
    $('.nav-tabs a').on('shown.bs.tab', function (event) {
        if (event.delegateTarget.hash == "#transferencias") {
            cofigTablaTransferenciasPendientes();
            recargarTalbaTransferenciasPendientes();
        } else if (event.delegateTarget.hash == "#transferencias_h") {
            cofigTablaTransferencias();
            recargarTalbaTransferencias();
        }
    });
}

function cofigTablaTransferenciasPendientes() {
    jQuery("#table_trp").jqGrid({
        url: 'transferencias/buscar_transferencias_pendientes_xml.php',
        datatype: 'xml',
        colNames: [
            '',
            'ID',
            'FECHA',
            'TRANSFEREIDO POR',
            'BODEGA ORIGEN',
            'BODEGA DESTINO',
            'CONFIRMAR',
        ],
        colModel: [
            {
                name: 'act1',
                index: 'act1',
                align: 'center',
                width: 10,
                formatter: function (cellvalue, options, rowObject) {
                    let btnaceptar = `<a id="act_aceptar_${cellvalue}" href="#" style="color:white; background:#33691E; padding:5px; margin:1px; border-radius:6px;" title="Aceptar transferencia"><span class="glyphicon glyphicon-ok"></span></a>`;
                    let btnrechazar = `<a id="act_rechazar_${cellvalue}" href="#" style="color:white; background:#B71C1C; padding:5px; margin:1px; border-radius:6px;" title="Rechazar transferencia"><span class="glyphicon glyphicon-remove"></span></a>`;
                    let btnmostrar = `<a id="act_mostrar_${cellvalue}" href="#" style="color:white; background:#01579B; padding:5px; margin:1px; border-radius:6px;" title="Mostrar transferencia"><span class="glyphicon glyphicon-print"></span></a>`;
                    return btnmostrar;
                }
            },
            {
                name: 'id_transferencia_bodega',
                index: 'tb.id_transferencia_bodega',
                align: 'center',
                width: 30
            },
            {
                name: 'fecha_creacion',
                index: 'tb.fecha_creacion',
                align: 'center',
                width: 50
            },
            {
                name: 'usuario_origen',
                index: 'uo.nombre_usuario',
                align: 'left',
                width: 80
            },
            {
                name: 'origen',
                index: 'b.nombre_punto',
                align: 'left',
                width: 80
            },
            {
                name: 'destino',
                index: 'bd.nombre_punto',
                align: 'left',
                width: 80
            },
            {
                name: 'act',
                index: 'act',
                align: 'center',
                width: 60,
                formatter: function (cellvalue, options, rowObject) {
                    let btnaceptar = `<a id="act_aceptar_${cellvalue}" href="#" style="color:white; background:#33691E; padding:5px; margin:1px; border-radius:6px;" title="Aceptar transferencia"><span class="glyphicon glyphicon-ok"></span> ACEPTAR</a>`;
                    let btnrechazar = `<a id="act_rechazar_${cellvalue}" href="#" style="color:white; background:#B71C1C; padding:5px; margin:1px; border-radius:6px;" title="Rechazar transferencia"><span class="glyphicon glyphicon-remove"></span> RECHAZAR</a>`;
                    let btnmostrar = `<a id="act_mostrar_${cellvalue}" href="#" style="color:white; background:#01579B; padding:5px; margin:1px; border-radius:6px;" title="Mostrar transferencia"><span class="glyphicon glyphicon-print"></span></a>`;
                    return btnaceptar + btnrechazar// + btnmostrar;
                }
            },

        ],
        autowidth: true,
        rowNum: 30,
        shrinkToFit: true,
        width: null,
        height: 220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager_trp'),
        sortname: 'tb.id_transferencia_bodega',
        sortorder: 'desc',
        viewrecords: true,
        gridview: true,
        gridComplete: function () {
            let ids = jQuery("#table_trp").jqGrid("getDataIDs");
            ids.forEach(el => {
                $(`#act_aceptar_${el}`).click(function (e) {
                    alertify.confirm("<b>¿Desea aceptar la transferencia?</b>", function (e) {
                        if (e) {
                            aceptarTransferencia(el);
                        } else {
                            alertify.log("Acción Cancelada");
                        }
                    });
                    $("#alertify-ok").text("SI");
                    $("#alertify-cancel").text("NO")
                });
                $(`#act_rechazar_${el}`).click(function (e) {
                    alertify.confirm("<b>¿Desea rechazar la transferencia?</b>", function (e) {
                        if (e) {
                            rechazarTransferencia(el);
                        } else {
                            alertify.log("Acción Cancelada");
                        }
                    });
                    $("#alertify-ok").text("SI");
                    $("#alertify-cancel").text("NO");

                });
                $(`#act_mostrar_${el}`).click(function (e) {
                    window.open(
                        "../../reportes/transferencia.php?id=" + el,
                        "_blank"
                    );
                });
            });
        },
        rowattr: function (rd) {
            return { "style": "background:#FFD54F" }
        },
    })
        .jqGrid('navGrid', '#pager_trp',
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
            });
}

function aceptarTransferencia(idtransferencia) {
    $.ajax({
        url: 'transferencias/aceptar_transferencia.php',
        type: 'POST',
        data: {
            'id': idtransferencia
        },
        success: function (data) {
            if (Number.isNaN(Number.parseFloat(data))) {
                alertify.alert(`<b>No se pudo aceptar la transferencia. ${data}</b>`);
                $("#alertify-ok").css({ background: "red" });
                return;
            }
            alertify.alert("<b>Transferencia Aceptada.</b>", function () {
                recargarTalbaTransferenciasPendientes();
            });
        }
    });
}

function rechazarTransferencia(idtransferencia) {
    $.ajax({
        url: 'transferencias/rechazar_transferencia.php',
        type: 'POST',
        data: {
            'id': idtransferencia
        },
        success: function (data) {
            if (Number.isNaN(Number.parseFloat(data))) {
                alertify.alert(`<b>No se pudo rechazar la transferencia. ${data}</b>`);
                $("#alertify-ok").css({ background: "red" });
                return;
            }
            alertify.alert("<b>Transferencia Rechazada.</b>", function () {
                recargarTalbaTransferenciasPendientes();
            });
        }
    });
}

function recargarTalbaTransferenciasPendientes() {
    jQuery("#table_trp").jqGrid("clearGridData");
    jQuery("#table_trp").jqGrid("setGridParam", {
        page: 1
    });
    jQuery("#table_trp").trigger("reloadGrid");
}


function cofigTablaTransferencias() {
    jQuery("#table_tr").jqGrid({
        url: 'transferencias/buscar_transferencias_xml.php',
        datatype: 'xml',
        colNames: [
            "",
            'ID',
            'FECHA',
            'ESTADO',
            'TRANSFEREIDO POR',
            'BODEGA ORIGEN',
            'BODEGA DESTINO',
            'CONFIRMADO POR',
            'FECHA CONFIRMADO',
        ],
        colModel: [
            {
                name: 'act',
                index: 'act',
                align: 'center',
                width: 15,
                formatter: function (cellvalue, options, rowObject) {
                    let estadot = rowObject.getElementsByTagName("cell")[3].childNodes[0].nodeValue;
                    let btnmostrar = `<a id="act_mostrar_${cellvalue}" href="#" style="color:white; background:#01579B; padding:5px; margin:1px; border-radius:6px;" title="Mostrar transferencia"><span class="glyphicon glyphicon-print"></span></a>`;
                    return btnmostrar;
                }
            },
            {
                name: 'id_transferencia_bodega',
                index: 'tb.id_transferencia_bodega',
                align: 'center',
                width: 30
            },
            {
                name: 'fecha_creacion',
                index: 'tb.fecha_creacion',
                align: 'center',
                width: 50
            },
            {
                name: 'estado_transferencia',
                index: 'tb.estado_trasferencia',
                align: 'center',
                width: 50,
                formatter: function (cellvalue, options, rowObject) {
                    return cellvalue.toUpperCase();
                }
            },
            {
                name: 'usuario_origen',
                index: 'uo.nombre_usuario',
                align: 'left',
                width: 100
            },
            {
                name: 'origen',
                index: 'bo.nombre_punto',
                align: 'left',
                width: 100
            },
            {
                name: 'destino',
                index: 'b.nombre_punto',
                align: 'left',
                width: 100
            },
            {
                name: 'usuario_destino',
                index: 'ud.nombre_usuario',
                align: 'left',
                width: 100
            },
            {
                name: 'fecha_modificacion',
                index: 'tb.fecha_modificacion',
                align: 'center',
                width: 80
            },
        ],
        autowidth: true,
        rowNum: 30,
        shrinkToFit: true,
        width: null,
        height: 220,
        sortable: true,
        rowList: [10, 20, 30],
        pager: jQuery('#pager_tr'),
        sortname: 'tb.id_transferencia_bodega',
        sortorder: 'desc',
        viewrecords: true,
        gridview: true,
        toolbar: [true, "top"],
        gridComplete: function () {
            let ids = jQuery("#table_tr").jqGrid("getDataIDs");
            ids.forEach(el => {
                $(`#act_mostrar_${el}`).click(function (e) {
                    window.open(
                        "../../reportes/transferencia.php?id=" + el,
                        "_blank"
                    );
                });
            });
        },
        rowattr: function (rd) {
            if (rd.estado_transferencia == 'aceptado') {
                return { "style": "background:#81C784" }
            } else if (rd.estado_transferencia == 'rechazado') {
                return { "style": "background:#E57373" }
            }
        },
    })
        .jqGrid('navGrid', '#pager_tr',
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
            });
    configToolbarTablaTransferencias();
}

function recargarTalbaTransferencias(url = undefined) {
    let params = { page: 1 };
    if (!!url) {
        params.url = url;
    }
    jQuery("#table_tr").jqGrid("clearGridData");
    jQuery("#table_tr").jqGrid("setGridParam", params);
    jQuery("#table_tr").trigger("reloadGrid");
}

function configToolbarTablaTransferencias() {
    $("#t_table_tr").empty();
    $("#t_table_tr").css({
        height: "35px",
        "text-align": "left",
        background: "#E0E0E0"
    });

    const radio1 = $(`<label style="margin-right:25px;"><input value="todo" name="estados_t" style="vertical-align: text-bottom" type="radio"> <span>TODO</span><label>`);
    const radio3 = $(`<label style="margin-right:25px;"><input value="rechazado" name="estados_t" style="vertical-align: text-bottom" type="radio"> <span>RECHAZADOS</span><label>`);
    const radio4 = $(`<label style="margin-right:25px;"><input value="aceptado" name="estados_t" style="vertical-align: text-bottom" type="radio"> <span>ACEPTADOS</span><label>`);
    radio1.find("input")[0].checked = true;

    let arrradio = [radio1, radio3, radio4];
    arrradio.forEach((el, i) => {
        let radio = el.find("input");
        if (i == 0) {
            $(radio).change(function (e) {
                recargarTalbaTransferencias("transferencias/buscar_transferencias_xml.php");
            });

        } else {
            $(radio).change(function (e) {
                recargarTalbaTransferencias("transferencias/buscar_transferencias_xml.php?estado=" + radio.val());
            });
        }
    });


    $("#t_table_tr").append(`<label style="margin-right:20px; margin-left: 5px;">MOSTRAR: </label>`, ...arrradio);
}
