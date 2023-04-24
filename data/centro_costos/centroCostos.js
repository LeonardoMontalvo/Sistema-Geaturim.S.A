$(document).ready(inicio);
let idCentroCosto = 0;

function inicio() {
    initTablaCentrosCosto();
    initDialogoBuscar();
    $("#btn_nuevo").click(function (e) {
        resetForm();
    });
    $("#btn_guardar").click(function (e) {
        if (validarFormulario()) {
            guardarCentroCosto();
        }
    });
    $("#btn_modificar").click(function (e) {
        if (validarFormulario()) {
            modificarCentroCosto();
        }
    });
    $("#btn_buscar").click(function (e) {
        $("#dialogo_ccsosto").dialog("open");
    });

    $("#btn_guardar")[0].disabled = false;
    $("#btn_modificar")[0].disabled = true;
}

function initTablaCentrosCosto() {
    jQuery("#lista_ccosto")
        .jqGrid({
            url: `json_lista_ccosto.php`,
            datatype: "json",
            colNames: [
                'ID',
                'NOBMRE',
                'DESCRIPCION'
            ],
            colModel: [
                {
                    name: 'id_centro_costo',
                    index: 'id_centro_costo',
                    width: 10,
                    search: false
                },
                {
                    name: 'nombre',
                    index: 'nombre',
                    width: 20,
                    searchoptions: { sopt: ["cn"] },
                },
                {
                    name: 'descripcion',
                    index: 'descripcion',
                    width: 70,
                    search: false
                },
            ],
            rowNum: 30,
            autowidth: true,
            height: 220,
            sortable: true,
            rowList: [10, 20, 30],
            pager: jQuery("#pager_lista_ccosto"),
            sortname: "nombre",
            sortorder: "asc",
            ondblClickRow: function (rowid, iRow, iCol, e) {
                let data = $("#lista_ccosto").jqGrid("getRowData", rowid);
                $("#nombre_ccosto").val(data.nombre);
                $("#descripcion_ccosto").val(data.descripcion);
                idCentroCosto = rowid;
                $("#dialogo_ccsosto").dialog("close");
                $("#btn_guardar")[0].disabled = true;
                $("#btn_modificar")[0].disabled = false;
            }
        })
        .jqGrid(
            "navGrid",
            "#pager_lista_ccosto",
            {
                add: false,
                edit: false,
                del: false,
                refresh: true,
                search: true,
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

function initDialogoBuscar() {
    var dialogo =
    {
        autoOpen: false,
        resizable: false,
        width: 860,
        height: 350,
        modal: true
    };

    $("#dialogo_ccsosto").dialog(dialogo);
}

function guardarCentroCosto() {
    let formData = new FormData(document.getElementById("form_ccosto"));
    $.ajax({
        url: 'guardar_ccosto.php',
        data: formData,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: "json",
        success: function (data) {
            resetForm();
            if (data > 1) {
                alertify.success("Guardado Correctamente");
            } else {
                alertify.error("Hubo un problema al guardar");
            }

        }
    }).fail(function () {
        alertify.error("Hubo un problema al guardar");
    });
}

function modificarCentroCosto() {
    let formData = new FormData(document.getElementById("form_ccosto"));
    formData.append("id", idCentroCosto);
    $.ajax({
        url: 'modificar_ccosto.php',
        data: formData,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: "json",
        success: function (data) {
            resetForm();
            if (data > 1) {
                alertify.success("Guardado Correctamente");
            } else {
                alertify.error("Hubo un problema al guardar");
            }
        }
    }).fail(function () {
        alertify.error("Hubo un problema al guardar");
    });
}

function validarFormulario() {
    let form = document.getElementById("form_ccosto");
    if (!form.reportValidity()) {
        return false;
    }
    return true;
}

function reloadTablaCentroCosto() {
    jQuery("#lista_ccosto").setGridParam({
        page: 1
    }).trigger("reloadGrid");
}

function resetForm() {
    idCentroCosto = 0;
    $("#form_ccosto")[0].reset();
    reloadTablaCentroCosto();
    $("#btn_guardar")[0].disabled = false;
    $("#btn_modificar")[0].disabled = true;
}