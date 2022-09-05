$(document).ready(inicioUsuarios);
function inicioUsuarios() {
    fetch("obtener_esquemas.php", {
        method: "get"
    }).then(function (d) {
        return d.json();
    }).then(function (res) {
        console.log(res);
    })
}

/* function inicioTablaUsuarios() {
    jQuery("#tabla_esquemas_usuarios")
        .jqGrid({
            datatype: "json",
            url: "json_esquemas.php",
            colNames: ["NOMBRE", "DESCRIPCION", "POR DEFECTO", "COLOR"],
            colModel: [
                {
                    name: "nombre",
                    index: "e.nombre",
                    align: "left",
                    search: true,
                    searchoptions: { sopt: ["cn"] },
                    editable: false,
                    editrules: { required: true, custom: true, custom_func: nombreEsquemacheck },
                    formoptions: { elmprefix: " (*)" },
                    formatter: function (cellvalue, options, rowObject) {
                        return cellvalue.toUpperCase();
                    }
                },
                {
                    name: "descripcion",
                    index: "e.descripcion",
                    frozen: true,
                    align: "left",
                    search: true,
                    searchoptions: { sopt: ["cn"] },
                    autowidth: true,
                    editable: false,
                    edittype: "textarea",
                    editrules: { required: true },
                    formoptions: { elmprefix: " (*)" },
                },
                {
                    name: "por_defecto",
                    index: "e.por_defecto",
                    frozen: true,
                    align: "left",
                    formatter: function (cellvalue, options, rowObject) {
                        return `<input id="por_defecto_${rowObject.id_esquema}" type='checkbox' ${cellvalue == "t" ? "checked" : ""}>`;
                    }
                },
                {
                    name: "color",
                    index: "color",
                    align: "center",
                    formatter: function (cellvalue, options, rowObject) {
                        return `<input id="color_${options.rowId}" type="color" value="${cellvalue}">`;
                    }
                }
            ],
            rownumbers: true,
            rowNum: 10,
            autowidth: true,
            shrinkToFit: true,
            height: "auto",
            pager: jQuery("#pager_esquemas"),
            sortname: "por_defecto desc,id_esquema asc",
            sortorder: "",
            caption: "Esquemas",
            viewrecords: true,
            gridComplete: function () {
                let ids = $("#tabla_esquemas").jqGrid("getDataIDs");
                if (animarfila) {
                    $("#" + ids[0]).css({ "animation": "anim_fondo 4s" });
                    animarfila = false;
                }
                ids.forEach(el => {
                    $("#por_defecto_" + el).change(function (e) {
                        if (e.target.checked) {
                            $.ajax({
                                method: "POST",
                                url: "editar_por_defecto.php",
                                data: { por_defecto: "t", id: el },
                                success: function (data) {
                                    recargarTabla();
                                    animarfila = true;
                                    $("#alertify-logs").empty();
                                    alertify.success("Cambio guardado");
                                }
                            });
                        }
                    });
                    $("#color_" + el).change(function (e) {
                        $.ajax({
                            method: "POST",
                            url: "editar_color.php",
                            data: { color: e.target.value, id: el },
                            success: function (data) {
                                //recargarTabla();
                                //animarfila = true;
                                $("#alertify-logs").empty();
                                alertify.success("Cambio guardado");
                            }
                        });
                    });
                });
            }
        })
        .jqGrid('navGrid', '#pager_esquemas', {
            add: false,
            edit: false,
            del: false,
            refresh: true,
            search: true,
            view: false
        }, {
            recreateForm: true,
            closeAfterEdit: true,
            checkOnUpdate: true,
            reloadAfterSubmit: true,
            closeOnEscape: true,
        }, {
            recreateForm: true,
            reloadAfterSubmit: true,
            closeAfterAdd: true,
            checkOnUpdate: true,
            closeOnEscape: true,
            bottominfo: "Todos los campos son obligatorios",
            beforeSubmit: function (postdata, formid) {
                $("#overlay").css({ display: "flex", "justify-content": "center" });
                $("#overlay").append(`<img width="16" src="img/ui-anim_basic_16x16.gif" style="align-self:center"/>`);
                return [true, ""];
            },
            afterComplete: function (response, postdata, formid) {
                $("#overlay").css({ "justify-content": "" });
                $("#overlay").hide();
                $("#overlay").empty();
            }
        }, {
            width: 300,
            closeOnEscape: true
        }, {
            closeOnEscape: true,
            multipleSearch: false,
            overlay: false
        }, {}, {
            closeOnEscape: true
        });
} */
