$(document).ready(inicio);

function inicio() {
    $('head').append($('<link rel="stylesheet" type="text/css" />').attr('href', '../data/esquemas/login/seleccionar_equema.css'));
    let overlay = $(`<div style="display:none;" class="esquema_overlay"></div>`);
    let box = $(`<div class="login-box-body form_esquema"></div>`);
    let select = $(`<div><label>Seleccione Empresa</label><select class="form-control" id="select_esquema"></select><div>`);
    let aceptar = $(`<button type="button" style="margin-top:5px;" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-ok"></span> Aceptar</button>`);
    aceptar.click(function (e) {
        e.preventDefault();
        seleccionarEsquema().then(function (data) {
            mostrarEsquema(data);
        });
    });
    box.append(select, aceptar);
    overlay.append(box);
    $("body").append(overlay);
    obtenerEsquemas();

    let sel_esquema = $(`<div class="row"><div class="col-md-12"><b>Empresa:</b> <span id="nombre_esquema"></span></div><div class='col-md-12'><button class="btn btn-primary btn-block" style="margin-bottom:15px; margin-top:5px;"><span style="margin-right:10px;" class="glyphicon glyphicon-hdd" aria-hidden="true"></span> Seleccionar Otra Empresa</button></div></div>`);
    sel_esquema.click(function (e) {
        $(".esquema_overlay").show();
    });
    $("#login_esquema").append(sel_esquema);

    seleccionarEsquema(true).then(function (data) {
        mostrarEsquema(data);
    });
}

function obtenerEsquemas() {
    $.ajax({
        url: "../data/esquemas/obtener_esquemas.php",
        dataType: "json",
        method: "GET",
        success: function (data) {
            $("#select_esquema").empty();
            data.forEach(e => {
                let option = $(`<option value="${e.nombre}">${e.nombre.toUpperCase()}</option>`);
                $("#select_esquema").append(option);
            });
        }
    });
}

function seleccionarEsquema(pordefecto = false) {
    let data = { esquema: $("#select_esquema").val() };
    if (pordefecto) {
        data["tipo"] = "por_defecto"
    }
    return $.ajax({
        url: "../data/esquemas/login/seleccionar_esquema.php",
        dataType: "json",
        method: "POST",
        data: data,
    });
}

function mostrarEsquema(esquema) {
    $("#nombre_esquema").text(esquema.toUpperCase());
    $(".esquema_overlay").hide();
    $("form")[0].reset();
    $("form").find("select").empty();
    $("form").find("select").append($(`<option value="">---------------</option>`));
}