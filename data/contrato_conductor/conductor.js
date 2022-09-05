$(document).ready(inicio);

function inicio() {
    tablaRegistro();
}

function tablaRegistro() {
    jQuery("#list").jqGrid({
        url: 'xmlConductor.php',
        datatype: 'xml',
        colNames: ['id_conductor', 'Cédula', 'Nombres', 'Apellidos', 'Teléfono', 'Dirección','Correo'],
        colModel: [
            {
                name: 'id_conductor',
                index: 'id_conductor',
                editable: true,
                align: 'center',
                width: '100',
                hidden: true
            },
            {
                name: 'ci',
                index: 'dni',
                editable: true,
                align: 'center',
                width: '100',
                size: '10',
                search: true,
                frozen: true,
                formoptions: { elmsuffix: " (*)" },
                editrules: { required: true },
                editoptions: {
                    maxlength: 10, size: 20,
                    dataInit: function (elem) {
                        $(elem).bind("keypress", function (e) {
                            return numeros(e)
                        })
                    }
                },
                searchoptions: { sopt: ["cn", "eq"] }
            },
            {
                name: 'nombres',
                index: 'nombres',
                editable: true,
                align: 'center',
                width: '100',
                search: true,
                frozen: true,
                formoptions: { elmsuffix: " (*)" },
                editrules: { required: true },
                searchoptions: { sopt: ["cn", "eq"] }
            },
            {
                name: 'apellidos',
                index: 'apellidos',
                editable: true,
                align: 'center',
                width: '100',
                search: true,
                frozen: true,
                formoptions: { elmsuffix: " (*)" },
                editrules: { required: true },
                searchoptions: { sopt: ["cn", "eq"] }
            },
            {
                name: 'telefono',
                index: 'telefono',
                editable: true,
                align: 'center',
                width: '100',
                search: false,
                frozen: true,
                editoptions: {
                    maxlength: 10, size: 20,
                    dataInit: function (elem) {
                        $(elem).bind("keypress", function (e) {
                            return numeros(e)
                        })
                    }
                },
            },
            {
                name: 'direccion',
                index: 'direccion',
                editable: true,
                align: 'center',
                width: '100',
                search: false,
                frozen: true
            },
            {
                name: 'correo',
                index: 'correo',
                editable: true,
                align: 'center',
                width: '100',
                search: false,
                frozen: true
            }
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        width: null,
        height: 400,
        pager: jQuery('#pager'),
        editurl: "procesosConductor.php",
        sortname: 'apellidos',
        shrinkToFit: false,
        sortordezr: 'asc',
        caption: 'Lista Conductores',
        viewrecords: true
    }).jqGrid('navGrid', '#pager',
        {
            add: true,
            edit: true,
            del: true,
            refresh: true,
            search: true,
            view: false,
            addtext: "Nuevo",
            edittext: "Modificar",
            deltext: "Eliminar"
        },
        {
            recreateForm: true,
            closeAfterEdit: true,
            checkOnUpdate: true,
            reloadAfterSubmit: true,
            closeOnEscape: true
        },
        {
            reloadAfterSubmit: true, closeAfterAdd: true, checkOnUpdate: true, closeOnEscape: true,
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
}

function numeros(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla == 8) return true;
    patron = /\d/;
    te = String.fromCharCode(tecla);
    return patron.test(te);
}