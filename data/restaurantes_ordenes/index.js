$(document).ready(inicio);

var formatoFactura = "";
var formatoNotaVenta = "";
function obtenerParametrosEmpresa() {
    fetch("obtener_parametros_empresa.php")
            .then(function (d) {
                return d.json();
            })
            .then(function (json) {
                formatoFactura = json["formato_imperesion_factura"];
                formatoNotaVenta = json["formato_imperesion_nota"];
            });
}
function imprimir_cocina(id, enviar = false) {
    //alertify.set({ labels: { ok: "Guardar e Imprimir", cancel: "Guardar" } });
    console.log("IMPRIMIR COCINA");
    alertify.confirm("¿Imprimir a cocina?",
            function (e) {
                if (e) {
                    $("#imprimiendo-cocina").css("display", "");
                    var ajaxobj = $.ajax({
                        url: 'pedido_cocina.php',
                        type: 'GET',
                        data: {id: id},
                        success: function (data) {

                        },
                        complete: function (data) {
                            $("#imprimiendo-cocina").css("display", "none");
                            if (data.status != 200) {
                                alertify.set({delay: 3000});
                                alertify.error("Hubo un problema y no se pudo imprimir el pedido.");
                                setTimeout(function () {
                                    if (enviar) {
//                                        reenviar(id);
                                    }
                                    location.reload();
                                }, 3000);
                            } else {
                                if (enviar) {
//                                    reenviar(id);
                                }
                                location.reload();
                            }

                        }
                    });
                    $("#cancelar-impr-cocina").off("click");
                    $("#cancelar-impr-cocina").click(function (e) {
                        e.preventDefault();
                        if (ajaxobj && ajaxobj.readyState != 4) {
                            ajaxobj.abort();
                        }
                        $("#imprimiendo-cocina").css("display", "none");
                    });
                } else {
                    if (enviar) {
                        reenviar(id);
                    }
                    location.reload();
                }
            })
}
function appendOverlay() {
    var overlay = $(`<div id="imprimiendo-cocina"></div>`);
    var spinner = $(` <div class="loader">Imprimiendo...<div><button class="btn btn-danger" id="cancelar-impr-cocina">Cancelar</buton></div></div>`);
    overlay.css({
        "display": "none",
        "position": "fixed",
        "z-index": "1000",
        "height": "100%",
        "width": "100%",
        "text-align": "center",
        "padding": "40px",
        "background-color": " rgba(255,255,255,0.5)"
    });
    overlay.append(spinner);
    spinner.css({
        "position": "relative",
        "top": "40%",
        "left": "40%",
        "height": "10%",
        "width": "20%",
        "text-align": "center",
        "background-color": " rgba(0,0,0,1)",
        "color": "#FFFFFF",
        "padding": "10px"
    });
    $('body').prepend(overlay);
}

function inicio() {
    obtenerParametrosEmpresa();
     appendOverlay();
}

