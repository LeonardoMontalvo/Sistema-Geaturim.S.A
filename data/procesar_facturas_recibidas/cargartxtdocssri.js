var refdoc;
var facturasarchivo = [];

$(document).ready(inicio)
function inicio() {
    initTablaDocs();
    leerArchivo();
}

function initTablaDocs() {
    $("#tabla_docs").jqGrid({
        dataType: "local",
        colNames: [
            "PROCESAR",
            "COMPROBANTE",
            "SERIE",
            "RUC",
            "RAZÓN SOCIAL",
            "FECHA EMISION",
            "AUTORIZACIÓN",
            "IMPORTE"
        ],
        colModel: [
            {
                name: "act",
                index: "act",
                formatter: function (cellvalue, options, rowObject) {
                    if (cellvalue == undefined) {
                        let btnfactura = `<button id="btn_fact_${options.rowId}" class="btn-success">FACTURA</button>`;
                        let btngasto = `<button id="btn_gas_${options.rowId}" class="btn-primary">GASTO</button>`;
                        //let btndescargar = `<button id="btn_des_${options.rowId}" class="btn-danger"><i class="fa fa-times"></i></button>`;
                        return `<div style="display:flex; justify-content: space-around;">${btnfactura}${btngasto}</div>`;
                    }
                    return cellvalue;
                },
                //width: 160
            },
            {
                name: "comprobante",
                index: "comprobante",
                width: 100
            },
            {
                name: "serie",
                index: "serie",
                width: 120
            },
            {
                name: "ruc",
                index: "ruc",
                width: 110
            },
            {
                name: "razon_social",
                index: "razon_social"
            },
            {
                name: "fecha_emision",
                index: "fecha_emision",
                width: 110
            },
            {
                name: "autorizacion",
                index: "autorizacion"
            },
            {
                name: "importe",
                index: "importe",
                align: "right",
                width: 100
            },
        ],
        width: (window.innerWidth - 300 < 600) ? 600 : window.innerWidth - 300,
        rownumbers: true,
        height: 300,
        afterInsertRow: function (rowid, rowdata, rowelem) {
            comprobarFactura(rowdata["autorizacion"])
                .then(res => {
                    if (res == 1) {
                        cambiarFacturaProcesada(rowid);
                    }
                });
            $("#btn_fact_" + rowid).click(function (e) {
                abrirFacturaCompra(rowdata["autorizacion"], rowid);
            });
            $("#btn_gas_" + rowid).click(function (e) {
                abrirRegistroGasto(rowdata["autorizacion"], rowid);
            });
            /*  $("#btn_des_" + rowid).click(function (e) {
                 cambiarFacturaDescartada(rowid);
             }); */
        },
        /*  pager: jQuery('#pager_docs'), */
    })
    /* .jqGrid('navGrid', '#pager_docs', {
        add: false,
        edit: false,
        del: false,
        refresh: true,
        search: false,
        // multipleSearch: true,
        view: false
    }); */
    $(window).off('resize');
    $(window).on('resize', function () {

        $('#tabla_docs').jqGrid('setGridWidth', (window.innerWidth - 300 < 600) ? 600 : window.innerWidth - 300);
    }).trigger('resize');
}

function leerArchivo() {
    let inputarch = $("#archivo");

    inputarch.change(function (e) {
        let f = e.target.files[0];
        if (!f) {
            return;
        }
        let fd = new FormData();
        fd.append("file", f, f.name);

        $("#tabla_docs").jqGrid("clearGridData");
        $("#load_tabla_docs").show();
        //$("#lui_tabla_docs").show();
        fetch("leercsv.php", {
            method: "POST",
            headers: {
            },
            body: fd
        })
            .then(res => {
                return res.json();
            })
            .then(json => {
                let data = json.filter(el => el.length > 1);
                data.forEach((el, i) => {
                    let row = {
                        comprobante: el[0],
                        serie: el[1],
                        ruc: el[2],
                        razon_social: el[3],
                        fecha_emision: el[4],
                        autorizacion: el[10],
                        importe: el[11]
                    };
                    facturasarchivo.push(row);
                    $("#tabla_docs").jqGrid("addRowData", i, row);
                });
            })
            .finally(() => {
                $("#load_tabla_docs").hide();
                //$("#lui_tabla_docs").hide();
            });
        inputarch.val("");
    });
}

function comprobarFactura(clave) {
    return $.ajax({
        url: "comprobarFactura.php",
        method: "GET",
        dataType: "json",
        data: { clave_acceso: clave }
    });
}


function abrirFacturaCompra(autorizacion, rowid) {
    /*  let params = `scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,
                 width=${screen.availWidth},height=${screen.availHeight},left=100,top=100`;
     refdoc = window.open('../factura_compra/', "test", params); */
    refdoc = window.open('../factura_compra/', "_blank");
    refdoc.addEventListener("load", function (event) {
        refdoc.document.getElementById("clavefactura").value = autorizacion;
        refdoc.document.getElementById("btn_buscar_clave").click();
    }, true);
    refdoc.addEventListener("unload", function (e) {
        comprobarFactura(autorizacion)
            .then(res => {
                if (res == 1) {
                    cambiarFacturaProcesada(rowid);
                }
            });
    });
}
function abrirRegistroGasto(autorizacion, rowid) {
    refdoc = window.open('../registro_gastos/', "_blank");
    refdoc.addEventListener("load", function (event) {
        refdoc.document.getElementById("clavefactura").value = autorizacion;
        refdoc.document.getElementById("btn_buscar_clave").click();
    }, true);
    refdoc.addEventListener("unload", function (e) {
        comprobarFactura(autorizacion)
            .then(res => {
                if (res == 1) {
                    cambiarFacturaProcesada(rowid);
                }
            });
    });
}
function cambiarFacturaProcesada(rowid) {
    $("#tabla_docs").jqGrid('setRowData', rowid, {
        act: `<div style="text-align:center; color:#000; background:#43A047; opacity:0.8; font-weight:bold;">PROCESADA</div>`
    });
}
/* function cambiarFacturaDescartada(rowid) {
    $("#tabla_docs").jqGrid('setRowData', rowid, {
        act: `<div style="text-align:center; color:#FFF; background:#F44336; opacity:0.8; font-weight:bold;">DESCARTADA</div>`
    });
} */