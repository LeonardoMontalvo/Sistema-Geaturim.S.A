//var valid_accept=false;
function validarCedulaRuc(docelem, tipodoc) {
    if (tipodoc == "ci") {
        if (docelem.val().length == 10) {
            $.ajax({
                type: "POST",
                url: "../../procesos/validacion_identificacion.php",
                data: { identificacion: docelem.val() },
                dataType: "json",
                success: function (data) {
                    if (!data) {
                        alertify.error('El número de Cédula/RUC es incorrecto.');
                        docelem.val("");
                        return;
                    }
                    alertify.success('El número de Cédula/RUC es correcto.');
                }
            }).fail(function () {
                alertify.error('El número de cédula es inválido.');
                docelem.val("");
            });
        }
    } else if (tipodoc == "ruc") {
        if (docelem.val().length == 13) {
            $.ajax({
                type: "POST",
                url: "../../procesos/validacion_identificacion.php",
                data: { identificacion: docelem.val() },
                dataType: "json",
                success: function (data) {
                    if (!data) {
                        dialogoRuc(docelem.val(), function () { }, function () { docelem.val(""); docelem.focus(); });
                        return;
                    }
                    alertify.success('El número de Cédula/RUC es correcto.');
                }
            }).fail(function () {
                alertify.error('No se pudo válidar la identficación');
                docelem.val("");
            });
        }
    }
}

function dialogoRuc(nroruc, acceptcallback = function () { }, cancelcallback = function () { }) {
    let dialogodiv = $(`<div id="dialog-confirm" title="Verificar RUC">
    <!--<div><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span></div>-->
    <p style="text-align: justify;">El sistema no pudo validar el número de RUC ingresado.</p>
    <p style="text-align: justify;">Verifique que el número de RUC <b><em><u>${nroruc}</u></em></b> es válido en el portal del SRI dando clic <a class="ui-state-hover" href="https://srienlinea.sri.gob.ec/sri-en-linea/SriRucWeb/ConsultaRuc/Consultas/consultaRuc" target="_blank">AQUÍ<a></p>
    </div>`);
    let buttonok = $(`<button style="margin:5px" class="btn btn-success"><i class="fa fa-check"></i> SI, el RUC es válido</button>`);
    let buttoncancel = $(`<button style="margin:5px" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar</button>`);
    let buttons = $(`<div style="text-align:center"> </div>`);

    buttonok.click(function () {
        acceptcallback();
        dialogodiv.dialog("close");
    });
    buttoncancel.click(function () {
        cancelcallback();
        dialogodiv.dialog("close");
    });

    buttons.append(buttonok, buttoncancel);
    dialogodiv.append(buttons);

    $("body").append(dialogodiv);

    dialogodiv.dialog({
        resizable: false,
        height: "auto",
        width: 400,
        modal: true,
        closeOnEscape: false,
        close: function (event, ui) {
            dialogodiv.remove();
        }
    });

    dialogodiv.dialog("open");
}