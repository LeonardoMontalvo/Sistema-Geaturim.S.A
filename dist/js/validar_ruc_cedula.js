/* function validarCedulaRuc(doc, tipodoc) {
    $("#alertify-logs").empty();

    if (tipodoc === "ci") {
        if (doc.length == 10) {
            $.ajax({
                type: "POST",
                url: "../../procesos/validacion_identificacion.php",
                data: { identificacion: $("#ruc_ci").val() },
                dataType: "json",
                success: function (data) {
                    if (!data) {
                        alertify.error('El número de Cédula/RUC es incorrecto.');
                        $("#ruc_ci").val("");
                        return;
                    }
                    alertify.success('El número de Cédula/RUC es correcto.');
                }
            }).fail(function () {
                alertify.error('No se pudo válidar la identficación');
                $("#ruc_ci").val("");
            });
        }

    }
    if (tipodoc == "ruc") {
        $.ajax({
            type: "POST",
            url: "../../procesos/validacion_identificacion.php",
            data: { identificacion: $("#ruc_ci").val() },
            dataType: "json",
            success: function (data) {
                if (!data) {
                    alertify.error('El número de Cédula/RUC es incorrecto.');
                    $("#ruc_ci").val("");
                    return;
                }
                alertify.success('El número de Cédula/RUC es correcto.');
            }
        }).fail(function () {
            alertify.error('No se pudo válidar la identficación');
            $("#ruc_ci").val("");
        });
    }
    if (Number.isNaN(Number($("#tipo_docu").val()))) {
        alertify.error("Seleccione un tipo de documento");
        $("#ruc_ci").val("");
        $("#tipo_docu").focus();
        return;
    }
} */

function dialogoRuc(nroruc, acceptcallback = function () { }, cancelcallback = function () { }) {
    let dialogodiv = $(`<div id="dialog-confirm" title="Verificar RUC">
    <!--<div><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span></div>-->
    <p style="text-align: justify;">El sistema no pudo validar el número de RUC ingresado.</p>
    <p style="text-align: justify;">Verifique que el número de RUC <b><em><u>${nroruc}</u></em></b> es válido en el portal del SRI dando click <a class="ui-state-hover" href="https://srienlinea.sri.gob.ec/sri-en-linea/SriRucWeb/ConsultaRuc/Consultas/consultaRuc" target="_blank">AQUÍ<a></p>
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
        /*  buttons: {
             "Registrar RUC": function () {
                 acceptcallback();
                 $(this).dialog("close");
             },
             "No Registrar RUC": function () {
                 cancelcallback();
                 $(this).dialog("close");
             }
         }, */
        /* buttons: [
            {
                text: "Si, el RUC es válido",
                "class": 'ui-priority-primary',
                click: function () {
                    acceptcallback();
                    $(this).dialog("close");
                }
            },
            {
                text: "No, el RUC no es válido",
                click: function () {
                    cancelcallback();
                    $(this).dialog("close");
                }
            } 
        ], */
        close: function (event, ui) {
            dialogodiv.remove();
        }
    });

    dialogodiv.dialog("open");
}