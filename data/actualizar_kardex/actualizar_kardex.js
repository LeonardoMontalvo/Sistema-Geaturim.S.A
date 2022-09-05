$(document).on("ready", inicio);
var accion;
var instanciaGrid;
var arrayIdKardex = new Array();
function inicio() {
    //deshabiliraSeccion('listado');
    $('#nuevo').on('click', function () {
        reload();
    });
    buscarProducto('productos');
    buscarProducto('codProductos');
    deshabiliraSeccion('fechas');
    deshabiliraSeccion('prod');
    deshabiliraSeccion('ajustes');
    radioProcesar();
    abrirConfirmar();
    buscar();
//    datePickerGeneral("fechaInicio");
//    datePickerGeneral("fechaFin");
}

function radioProcesar() {
    $("input[name='opciones']").change(function () {
        accion = $(this).val()
        if (accion == "INV" || accion == "I") {
            deshabiliraSeccion('accionRealizar');
            deshabiliraSeccion('fechas');
            habiliraSeccion('procesosRealizar');
        }
        if (accion == "e" || accion == "s" || accion == "aj") {
            deshabiliraSeccion('procesosRealizar');
            deshabiliraSeccion('fechas');
            habiliraSeccion('accionRealizar');
            habiliraSeccion('ajustes');
        }
        if (accion == "all") {
            deshabiliraSeccion('accionRealizar');
            deshabiliraSeccion('procesosRealizar');
            habiliraSeccion('fechas');
        }
    });
}

function abrirConfirmar() {
    $('#procesar').on('click', function (e) {
        e.preventDefault();
        var mensaje;
        //accion = $("input[name='opciones']:checked").val();
        console.log("ACCION: " + accion);
        if (accion == "I") {
            mensaje = "Ingreso del comprobante: " + $('#comprobante').val();
        }
        if (accion == "INV") {
            mensaje = "Inventario del comprobante: " + $('#comprobante').val();
        }
        if (accion == "e") {
            mensaje = "Eliminar ";
        }
        if (accion == "s") {
            mensaje = "Sumar ";
        }
        if (accion == "aj") {
            mensaje = "Ajuste de producto: " + $('#productos').val();
        }
        if (accion == "all") {
            mensaje = "Procesar todo el kardex inventario desde: " + $('#fechaInicio').val() + " hasta: " + $('#fechaFin').val();
        }
        console.log(mensaje);
        $('#parrafor').text(mensaje);
        openConfirmar();
        accionBotonesConfirmar();
    });
}

function procesar() {
    radio = $("input[name='opciones']:checked").val();
    //console.log("ID_KARDEX: " + arrayIdKardex[0].id_kardex);
    var datos = {
        comprobante: $('#comprobante').val(),
        op: radio,
        obj: arrayIdKardex,
        fIni: formatearFecha2($('#fechaInicio').val(), '-'),
        fFin: formatearFecha2($('#fechaFin').val(), '-')
    };
    openModal();
    $.ajax({
        type: 'POST', //aqui puede ser igual get
        url: 'procesarKardex.php', //aqui va tu direccion donde esta tu funcion php
        dataType: 'JSON',
        data: datos, //aqui tus datos , bodega: $('#bodega').val()
        success: function (data) {
            console.log(data);
            str = $.parseJSON(JSON.stringify(data));
            if (str.data == 1) {
                closModal();
                recargarPagina(str.error);
            } else {
                closModal();
                recargarPagina(str.error);
            }
        }
    });
}

function openModal() {
    $('#exampleModal').modal({backdrop: 'static', keyboard: false});
}
function closModal() {
    $('#exampleModal').modal('toggle');
    /*alertify.alert(mensaje);
     setTimeout(recargarPagina(),5000);*/
}

function habiliraSeccion(clase) {
    $('.' + clase).show();
}

function deshabiliraSeccion(clase) {
    $('.' + clase).hide();
}

function buscarProducto(id) {
    var source = "";
    if (id === "codProductos") {
        source = "buscarProducto.php?op=c";
    } else {
        source = "buscarProducto.php";
    }
    $("#" + id).autocomplete({
        source: source,
        minLength: 1,
        select: function (event, ui) {
            asignarDatos(ui, id);
            return false;
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
    };
    $("#" + id).on('keyup', function (e) {
        if (id === 'productos' || id === 'codProductos') {
            if (e.keyCode === 8) {
                $(this).text().substring(0, 1);
                limpiarDAtos(id);
            }
        }
    });
}

function asignarDatos(ui, id) {
    $('#idProducto').val('');
    $('#codProductos').val('');
    $('#productos').val('');
    $('#idProducto').val(ui.item.cod_producto);
    $('#codProductos').val(ui.item.codigo);
    $('#productos').val(ui.item.articulo);
}

function limpiarDAtos(id) {
    if (id === 'productos') {
        $('#idProducto').val('');
        $('#codProductos').val('');
    } else {
        $('#idProducto').val('');
        $('#productos').val('');
    }
}

function openConfirmar() {
    $('#confirmar').modal({backdrop: 'static', keyboard: false});
}

function closeConfirmar() {
    $('#confirmar').modal('toggle');
}

function accionBotonesConfirmar() {
    $('#btnAceptarConfirmar').on('click', function (e) {
        e.preventDefault();
        closeConfirmar();
        //procesar();
        if (accion === "s") {
            if (arrayIdKardex.length === 2) {
                console.log("ENTRO DI SON 2 REGISTROS");
                procesar();
            } else {
                alertify.alert("SELECIONE OTRO REGISTRO PARA SUMAR");
            }
        } else {
            if (accion === "all") {
                if ($('#fechaInicio').val() !== '' && $('#fechaFin').val() !== '') {
                    procesar();
                } else {
                    alertify.alert("SELECIONE EL RANGO DE FECHAS");
                }
            } else {
                procesar();
            }
        }
//location.reload();
    });
    $('#btnSalirConfirmar').on('click', function () {
        closeConfirmar();
        location.reload();
    });
}

/////////////////////////////////// BUSCAR KARDEX ///////////////////////////////////
function crearListaGrid() {
    instanciaGrid = $("#listado").jqGrid({
        datatype: "local",
        //url: 'buscarKardex.php?product='+$('#idProducto').val(),
        //datatype: "json",
        colNames: ['id', 'Comprobante', 'Transacción', 'Fecha', 'Producto', 'Cantidad', 'Stock'],
        colModel: [
            /*{name: 'myac', width: 50, fixed: true, sortable: false, resize: false, formatter: 'actions',
             formatoptions: {keys: false, delbutton: true, editbutton: false}
             },*/
            {name: 'id_kardex', index: 'id_kardex', width: 60, sorttype: "int", align: "center"},
            {name: 'comprobante', index: 'comprobante', width: 60, sorttype: "int", align: "center"},
            {name: 'detalle', index: 'detalle', width: 90, align: "center"},
            {name: 'fecha_kardex', index: 'fecha_kardex', width: 100, sorttype: "date", align: "center"},
            {name: 'cod_productos', index: 'cod_productos', width: 400, align: "left", hidden: true},
            {name: 'cantidad', index: 'cantidad', width: 80, align: "right", sorttype: "int"},
            {name: 'saldo', index: 'saldo', width: 80, align: "right", sorttype: "int"}
        ],
        multiselect: false,
        caption: "Registros de Kardex",
        width: 990,
        height: 300,
        shrinkToFit: true,
        rowNum: 10,
        rowList: [10, 20, 30],
        pager: '#pager',
        viewrecords: true,
        loadonce: true
    });
}

function buscar() {
    $('#buscar').on('click', function () {
        if ($('#idProducto').val() !== '') {
            habiliraSeccion('listado');
            crearListaGrid();
            cargarDatosLista();
        } else {
            alertify.alert("Seleccione el producto");
        }
    });
}

function cargarDatosLista() {
    clearGrid('listado');
    var datos = {
        acc: "b",
        product: $('#idProducto').val()
    };
    $.ajax({
        type: "POST",
        url: "procesarKardex.php",
        dataType: 'JSON',
        data: datos,
        success: function (data) {
            //openModal();
            str = $.parseJSON(JSON.stringify(data));
            if (str.data == 1) {
                //closModal();
                for (var i = 0; i <= str.lista.length; i++) {
                    instanciaGrid.jqGrid('addRowData', i + 1, str.lista[i]);
                    console.log("DATOS: " + str.lista[i]);
                    //closModal();
                }
            } else {
                //closModal();
                alertify.alert(str.error);
            }
        }
    });
    seleccionarItemGrid(instanciaGrid);
}

function clearGrid(id) {
    $("#" + id).jqGrid("clearGridData");
}

function seleccionarItemGrid(obj) {
    $('#listado').on('click', function () {
        var id = obj.jqGrid('getGridParam', 'selrow');
        if (id) {
            selec = obj.jqGrid('getRowData', id);
            console.log("id: " + selec.id_kardex + " Comprobante: " + selec.comprobante);
            if (accion === "e" || accion === "aj") {
//console.log("ENTRO EN IF DE E");
                if (arrayIdKardex.length < 1) {
                    arrayIdKardex.push(selec);
                    console.log("ARRAY: " + arrayIdKardex + " tamaño: " + arrayIdKardex.length);
                }
            }
            if (accion === "s") {
//console.log("ENTRO EN IF DE S");
                if (arrayIdKardex.length < 2) {
                    arrayIdKardex.push(selec);
                    console.log("ARRAY: " + arrayIdKardex + " tamaño: " + arrayIdKardex.length);
                } else {
                    alertify.alert("Seleccione 2 registros a sumar");
                    //console.log("Seleccione 2 registros a sumar");
                }
            }
        }
    });
}

function recargarPagina(mensaje) {
    alertify.alert(mensaje);
    setTimeout(reload, 3000);
}

function reload() {
    location.reload()
}