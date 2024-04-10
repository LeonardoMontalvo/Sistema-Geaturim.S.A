function validarIdentificacion(docelem, tipodoc, acceptcallback = function () { }, cancelcallback = function () { }) {
    let validAccept = false;
    function validarCedulaRuc(docelem, tipodoc) {
        let doc = docelem.val();
        
        
     
        
        
        if (tipodoc == "ci") {
               if (Number.isNaN(Number(doc))) {
            mensajeIdentificacionInvalida();
            return;
        }
            console.log("../12");
            if (docelem.val().length == 10) {
                $.ajax({
                    type: "POST",
                    url: "../../procesos/validacion_identificacion.php",
                    data: { identificacion: docelem.val() },
                    dataType: "json",
                    success: function (data) {
                        if (!data) {
                            alertify.error('El número de Cédula/RUC es incorrecto.');
                            resetElem();
                            return;
                        }
                        alertify.success('El número de Cédula/RUC es correcto.');
                    }
                }).fail(function () {
                    alertify.error('No se pudo válidar la identficación');
                    resetElem();
                });
            }
        } else if (tipodoc == "ruc") {
               if (Number.isNaN(Number(doc))) {
            mensajeIdentificacionInvalida();
            return;
        }
             console.log("../122");
            if (docelem.val().length == 13) {
                $.ajax({
                    type: "POST",
                    url: "../../procesos/validacion_identificacion.php",
                    data: { identificacion: docelem.val() },
                    dataType: "json",
                    success: function (data) {
                        if (!data) {
                            dialogoRuc(docelem.val());
                            return;
                        }
                        alertify.success('El número de Cédula/RUC es correcto.');
                    }
                }).fail(function () {
                    mensajeIdentificacionInvalida();
                });
            }
        }
    }
    function dialogoRuc(nroruc) {
        let dialogodiv = $(`<div id="dialog-confirm" title="Verificar RUC">
        <!--<div><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span></div>-->
        <p style="text-align: justify;">El sistema no pudo validar el número de RUC ingresado.</p>
        <p style="text-align: justify;">Verifique que el número de RUC <b><em><u>${nroruc}</u></em></b> sea válido en el portal del SRI dando clic <a class="ui-state-hover" href="https://srienlinea.sri.gob.ec/sri-en-linea/SriRucWeb/ConsultaRuc/Consultas/consultaRuc" target="_blank">AQUÍ<a> antes de registrarlo.</p>
        <div style="font-size:9pt"><a class="ui-state-hover" target="_blank" href="https://facturacion.securitydata.net.ec/Manuales/Comunicado_digito_verificador.pdf"><i class="fa fa-info-circle"></i> Más información</a></div>
        <hr style="border:1px solid gray; margin: 5px 0 5px 0;">
        </div>`);
        let buttonok = $(`<button style="margin:5px" class="btn btn-success"><i class="fa fa-check"></i> SI, el RUC es válido</button>`);
        let buttoncancel = $(`<button style="margin:5px" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar</button>`);
        let buttons = $(`<div style="text-align:center"> </div>`);

        buttonok.click(function () {
            validAccept = true;
            acceptcallback();
            dialogodiv.dialog("close");
        });
        buttoncancel.click(function () {
            resetElem();
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
            //closeOnEscape: false,
            open: function (event, ui) {

                //estilo para poner al frente el diálogo en caso de haber un diálogo previo mostrandose
                let dialog = $(this).parent();
                let overlay = $(this).parent().prev();
                overlay.css({ "z-index": "1100" })
                dialog.attr('style', function (i, s) { return (s || '') + 'z-index: 1200 !important;' });

                validAccept = false;
            },
            close: function (event, ui) {
                if (!validAccept) {
                    resetElem();
                    cancelcallback();
                }
                validAccept = false;
                dialogodiv.remove();
            }
        });

        dialogodiv.dialog("open");
    }

    function resetElem() {
        docelem.val("");
        docelem.focus();
    }
    function mensajeIdentificacionInvalida() {
        alertify.error('El número de Cédula/RUC es incorrecto.../');
        resetElem();
    }

    validarCedulaRuc(docelem, tipodoc);
}
