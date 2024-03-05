window.onkeydown = (e) => {
    if (e.key == "F2") {
        $("#ventana_busqueda").dialog("open");
        return false;
    }
}

const initDialogBusqueda = () => {
    $("#ventana_busqueda").dialog({
        modal: true,
        width: 810,
        height: 380,
        autoOpen: false,
        dialogClass: "dialog--fixed",
        title: "BUSCAR PRODUCTO",
        close: function (event, ui) {
            $("#tabla_vbusqueda").jqGrid("clearGridData");
            $("#term_vbusqueda").val("");

        },
        open: function (event, ui) {
            let nomprod = $("#producto").val();
            if (nomprod) {
                $("#term_vbusqueda").val(nomprod);
                $("#tabla_vbusqueda").jqGrid("clearGridData");
                console.log("getprod 2");
                getProductos(nomprod)
                    .then(data => {
                        $("#tabla_vbusqueda").jqGrid("addRowDataaddJSONData", data);
                    })
            }
        }
    });
}

const initTablaBusqueda = () => {
    $("#tabla_vbusqueda").jqGrid({
        datatype: "local",
        colNames: [
            "cod_barras",
            "PRODUCTO",
            "PVP",
            "CANTIDAD MAYORISTA",
            "PVP MAYORISTA",
            "CANTIDAD NEGOCIO",
            "PVP NEGOCIO",
            "STOCK"
        ],
        colModel: [
            {
                name: "cod_barras",
                index: "cod_barras",
                editable: false,
                serarch: false,
                sortable: false,
                hidden: true
            },
            {
                name: "articulo",
                index: "articulo",
                editable: false,
                serarch: false,
                sortable: true,
                width: 500,
                formatter: function (cellvalue, options, rowObject) {
                    return `<sapn style="font-weight:bold;">${cellvalue}</span>`;
                }
            },
            {
                name: "iva_minorista",
                index: "iva_minorista",
                editable: false,
                serarch: false,
                sortable: false,
                align: "right",
            },
            {
                name: "cantidad_mayorista",
                index: "cantidad_mayorista",
                editable: false,
                serarch: false,
                sortable: false,
                align: "right",
            },
            {
                name: "iva_mayorista",
                index: "iva_mayorista",
                editable: false,
                serarch: false,
                sortable: false,
                align: "right",
            },
            {
                name: "cantidad_negocio",
                index: "cantidad_negocio",
                editable: false,
                serarch: false,
                sortable: false,
                align: "right",
            },
            {
                name: "iva_negocio",
                index: "iva_negocio",
                editable: false,
                serarch: false,
                sortable: false,
                align: "right",
            },
            {
                name: "stock",
                index: "stock",
                editable: false,
                serarch: false,
                sortable: false,
                align: "right",
            },
        ],
        rowNum: 30,
        width: 750,
        height: 225,
        sortable: true,
        shrinkToFit: true,
        ondblClickRow: function (rowid, iRow, iCol, e) {
            selectItemTabla(rowid);
        }
    });

    jQuery("#tabla_vbusqueda").jqGrid('bindKeys', {
        onEnter: (e) => {
            selectItemTabla(e);
        }
    });
}

const getProductos = (term) => {
    return $.ajax({
        url: "ventana_busqueda/buscar_productos.php",
        method: "GET",
        dataType: "json",
        data: {
            term
        }
    });
}

const selectItemTabla = (idrow) => {
    let rowdata = $("#tabla_vbusqueda").jqGrid("getRowData", idrow);
    $("#ventana_busqueda").dialog("close");
    $("#codigo_barras").focus();
    $("#codigo_barras").val(rowdata["cod_barras"]);
    $("#codigo_barras").change()
}

$(document).ready(function () {
    initDialogBusqueda();
    initTablaBusqueda();

    $("#term_vbusqueda")[0].addEventListener("keyup", (e) => {
        if (e.key == "ArrowUp" || e.key == "ArrowLeft" || e.key == "ArrowDown" || e.key == "ArrowRight") {
            let selrow = $('#tabla_vbusqueda').jqGrid('getGridParam', 'selrow');
            let rowid = $('#tabla_vbusqueda').jqGrid('getDataIDs')[0];
            if (rowid != undefined) {
                $("#tabla_vbusqueda").focus();
            }
            if (selrow != null) {
                return false;
            }
            $("#tabla_vbusqueda").setSelection(rowid, true);
            return false;
        }
        if (e.key == "Enter" || e.key == 'Control') {
            return false;
        }
        $("#tabla_vbusqueda").jqGrid("clearGridData");
        if (e.target.value.length > 1) {
            console.log("getprod 1");
            getProductos(e.target.value)
                .then(data => {
                    data.forEach((el, i) => {
                        $("#tabla_vbusqueda").jqGrid("addRowData", i, el);
                    });
                })
        }
    });
});

