$(document).ready(inicio);
let idCuentaEf = 0;

function inicio() {
    inicioInputs();
    inicioTablaCuentasEf();

    $("#btnImprimirE").click(function (e) {
        imprimir();
    })
}

function inicioInputs() {
    $("#cuenta_efectivo")
        .autocomplete({
            source: function (request, response) {
                idCuentaEf = 0;
                $.ajax({
                    url: "../../procesos/retornar_plan_cuentas.php",
                    data: { term: request.term },
                    dataType: "json",
                    success: function (data) {
                        response(data);
                    },
                    error: function () {
                        response([]);
                    },
                });
            },
            minLength: 1,
            focus: function (event, ui) {
                return false;
            },
            select: function (event, ui) {
                $(this).val(ui.item.value);
                idCuentaEf = ui.item.id_plan_cuentas;
                return false;
            },
        })
        .data("ui-autocomplete")
        ._renderItem = function (ul, item) {
            return $("<li>")
                .append("<a>" + `${item.value} ` + "</a>")
                .appendTo(ul);
        };
    $("#cuenta_efectivo").keyup(function (e) {
        if (e.key == "Enter") {
            if (!!idCuentaEf) {
                $.ajax({
                    url: "guardarCuentas.php?cuenta=efectivo",
                    method: "POST",
                    data: { id: idCuentaEf },
                    dataType: "json",
                }).done(function (data) {
                    recargarTablaCuentasEf();
                    $("#cuenta_efectivo").val("");
                    idCuentaEf = 0;
                    alertify.success("Cuenta añadida correctamente.");
                });
            }
        }
    });

    /*$("#inputDesde").change(function (e) {
        let anio = e.target.value.split("-")[0];
        $("#inputHasta")[0].max = anio + '-12-31';
        $("#inputHasta")[0].min = e.target.value;
        $("#inputHasta").val(e.target.value);
    });*/
}

function inicioTablaCuentasEf() {
    $("#tabla_cuentas_ef").jqGrid({
        datatype: "json",
        url: "obtenerCuentas.php",
        colNames: [
            "",
            "ID",
            "CÓDIGO CUENTA",
            "DESCRIPCIÓN",
        ],
        colModel: [
            {
                hidden: false,
                name: "myac",
                width: 40,
                fixed: true,
                sortable: false,
                resize: false,
                formatter: "actions",
                formatoptions: { keys: false, delbutton: true, editbutton: false },
            },
            {
                name: "id_plan_cuentas",
                index: "id_plan_cuentas",
                searchoptions: { sopt: ["eq", "cn"] },
                search: true,
                align: "left",
                width: 40,
            },
            {
                name: "codigo_plan",
                index: "codigo_plan",
                searchoptions: { sopt: ["eq", "cn"] },
                search: true,
                align: "left",
                width: 40,
            },
            {
                name: "descripcion",
                index: "descripcion",
                searchoptions: { sopt: ["cn"] },
                search: true,
                align: "left",
            },
        ],
        autowidth: true,
        rowNum: 10,
        height: 220,
        rownumbers: true,
        sortable: true,
        rowList: [10, 20, 30],
        sortname: "codigo_plan",
        sortorder: "asc",
        viewrecords: true,
        shrinkToFit: true,
        pager: $("#pager_cuentas_ef"),
        delOptions: {
            modal: true,
            left: window.innerWidth - window.innerWidth / 2,
            top: window.innerHeight - 60,
            recreateForm: true,
            onclickSubmit: function (rp_ge, rowid) {
                $.ajax({
                    url: "eliminarCuentas.php?cuenta=efectivo",
                    method: "POST",
                    data: { id: rowid },
                    dataType: "json"
                }).done(function (data) {
                    recargarTablaCuentasEf();
                });
                $(".ui-icon-closethick").trigger("click");
                return true;
            },
            processing: true,
        },
    }).jqGrid('navGrid', '#pager_cuentas_ef',
        {
            add: false,
            edit: false,
            del: false,
            refresh: true,
            search: true,
            view: false,
            addtext: "Nuevo",
            edittext: "Modificar",
            refreshtext: "Recargar",
            viewtext: "Consultar",
            searchtext: "Buscar"
        });
}

function recargarTablaCuentasEf() {
    $("#tabla_cuentas_ef").jqGrid("clearGridData");
    $("#tabla_cuentas_ef").jqGrid("setGridParam", {
        url: "obtenerCuentas.php",
        page: 1,
    });
    $("#tabla_cuentas_ef").trigger("reloadGrid");
}

function validarFechas() {
    if (!!!$("#inputDesde").val()) {
        alertify.error("Ingrese fecha desde.");
        $("#inputDesde").focus();
        return false;
    }
    if (!!!$("#inputHasta").val()) {
        alertify.error("Ingrese fecha hasta.");
        $("#inputHasta").focus();
        return false;
    }
    return true;
}

function imprimir() {
    if (!validarFechas()) {
        return;
    }
    window.open("../../phpexcel/flujo_efectivo.php?inicio=" + $("#inputDesde").val() + "&fin=" + $("#inputHasta").val(), "_blank");
}