var AddCliente = function () {
    let servicios = (function () {
        function obtenerTipoDocumento() {
            return $.ajax({
                url: "../tipo_documento/retornar_tipo_documento.php",
                dataType: "json",
                method: "GET"
            });
        }
        function compararCedula(ruci, tipodoc) {
            return $.ajax({
                dataType: "json",
                url: "../registro_gastos/proveedores/comparar_cedulas.php",
                method: "POST",
                data: {
                    "cedula": ruci,
                    "tipo_docu": tipodoc
                }
            });
        }
        function guardarCliente(cliente) {
            return $.ajax({
                dataType: "json",
                method: "POST",
                url: "../proveedores/guardar_proveedores.php",
                data: cliente
            }).fail(function () {
                alertify.error("Hubo un problema al registrar cliente");
            });
        }
        return {
            obtenerTipoDocumento,
            compararCedula,
            guardarCliente
        };
    })();

    let selectTipoDoc;
    let inputNombreCli, inputNroTelelfono,
        inputPais, inputDireccion, inputRUCI,
        inputNroCel, inputCiudad, inputEmail, inputCupoC,
        inputTipoCli, selectVisitador,
        selectFax, selectFormaPago, selectPrincialPro, selectRepresentanteLegal
        ;
    let textaNotas;
    let btnGuardar, btnEnviarForm;
    let formCmp;

    let contenedor;
    let onGuardar;

    function init() {
        contenedor.load("../registro_gastos/proveedores/formulario.html", function () {
            $.when(
                $.getScript("../../plugins/input-mask/jquery.inputmask.js"),
                $.getScript("../../plugins/input-mask/jquery.inputmask.date.extensions.js"),
                $.getScript("../../plugins/input-mask/jquery.inputmask.extensions.js"),
                $.getScript("../../dist/js/validar_identificacion.js"),
                $.Deferred(function (deferred) {
                    $(deferred.resolve);
                })
            )
                .done(function () {
                    $("[data-mask]").inputmask();
                });
            inicioControles();
            inicioRUCI();
            inicioTipoDoc();
            inicioButtons();

            inputCupoC.keypress(validPunto);

            servicios.obtenerTipoDocumento().done(llenarTipoDoc);
        });
    }

    function inicioControles() {
        selectTipoDoc = $("#tipo_docu_cmp");
        inputNombreCli = $("#nombres_cli_cmp");
        inputNroTelelfono = $("#nro_telefono_cmp");
        inputPais = $("#pais_cli_cmp");
        inputDireccion = $("#direccion_cli_cmp");
        textaNotas = $("#notas_cli_cmp");
        inputRUCI = $("#ruc_ci_cmp");
        inputNroCel = $("#nro_celular_cmp");
        inputCiudad = $("#ciudad_cli_cmp");
        inputEmail = $("#email_cmp");
        inputCupoC = $("#cupo_credito_cmp");
        inputTipoCli = $("#tipo_cli_cmp");

        selectVisitador = $("#visitador");
        selectFax = $("#fax");
        selectFormaPago = $("#forma_pago_cmp");
        selectPrincialPro = $("#principal_pro");
        selectRepresentanteLegal = $("#representante_legal");




        btnGuardar = $("#btnGuardar_cmp");
        btnEnviarForm = $("#submit_form_cmp");
        formCmp = $("#form_cmp");
    }

    function inicioButtons() {
        btnGuardar.click(function (e) {
            guardar();
        })
    }

    function inicioRUCI() {
        inputRUCI.val("");
        inputRUCI[0].focus();
        inputRUCI.attr("maxlength", "10");
        inputRUCI.keypress(ValidNum);

        inputRUCI.keyup(function (e) {
            if (!!!selectTipoDoc.val()) {
                $("#alertify-logs").empty();
                inputRUCI.val("");
                alertify.error('Seleccione tipo documento.');
                return;
            }
            servicios.compararCedula(inputRUCI.val(), selectTipoDoc.val()).done(handleCompararRUCI);
        });
    }

    function inicioTipoDoc() {
        selectTipoDoc.change(function () {

            if (selectTipoDoc.val() === '2') {
                inputRUCI.val("");
                inputRUCI.keypress(ValidNum);
                inputRUCI.removeAttr("disabled");
                inputRUCI.attr("maxlength", "10");
                inputRUCI.attr("minlength", "10");

            } else {
                if (selectTipoDoc.val() === '1') {

                    inputRUCI.val("");
                    inputRUCI.keypress(ValidNum);
                    inputRUCI.removeAttr("disabled");
                    /* inputRUCI.removeAttr("maxlength");
                    inputRUCI.removeAttr("minlength"); */
                    inputRUCI.attr("maxlength", "13");
                    inputRUCI.attr("minlength", "13");
                } else {
                    if (selectTipoDoc.val() === '3') {
                        inputRUCI.val("");
                        inputRUCI.unbind("keypress");
                        inputRUCI.removeAttr("disabled");
                        inputRUCI.attr("maxlength", "30");
                    }
                }
            }
        });
    }

    function llenarTipoDoc(data) {
        let option = $(`<option selected disabled value="">Seleccione tipo Documento</option>`);
        selectTipoDoc.empty();
        selectTipoDoc.append(option);
        data.forEach(el => {
            let option = $(`<option value="${el["id_tdocu"]}">${el["nombre_tdocu"]}</option>`);
            selectTipoDoc.append(option);
        });
    }

    function ValidNum(event) {
        if (event.keyCode < 48 || event.keyCode > 57) {
            return false;
        }
        return true;
    }

    function validRUCI() {

        $("#alertify-logs").empty();

        let tipodoc = "";
        if (selectTipoDoc.val() == 2) { tipodoc = 'ci'; }
        if (selectTipoDoc.val() == 1) { tipodoc = 'ruc'; }
        validarCedulaRuc(inputRUCI, tipodoc);

        return;
        var numero = inputRUCI.val();
        var suma = 0;
        var residuo = 0;
        var pri = false;
        var pub = false;
        var nat = false;
        var modulo = 11;
        var p1;
        var p2;
        var p3;
        var p4;
        var p5;
        var p6;
        var p7;
        var p8;
        var p9;
        var d1 = numero.substr(0, 1);
        var d2 = numero.substr(1, 1);
        var d3 = numero.substr(2, 1);
        var d4 = numero.substr(3, 1);
        var d5 = numero.substr(4, 1);
        var d6 = numero.substr(5, 1);
        var d7 = numero.substr(6, 1);
        var d8 = numero.substr(7, 1);
        var d9 = numero.substr(8, 1);
        var d10 = numero.substr(9, 1);

        // if (d3 < 6) {
        nat = true;
        p1 = d1 * 2;
        if (p1 >= 10)
            p1 -= 9;
        p2 = d2 * 1;
        if (p2 >= 10)
            p2 -= 9;
        p3 = d3 * 2;
        if (p3 >= 10)
            p3 -= 9;
        p4 = d4 * 1;
        if (p4 >= 10)
            p4 -= 9;
        p5 = d5 * 2;
        if (p5 >= 10)
            p5 -= 9;
        p6 = d6 * 1;
        if (p6 >= 10)
            p6 -= 9;
        p7 = d7 * 2;
        if (p7 >= 10)
            p7 -= 9;
        p8 = d8 * 1;
        if (p8 >= 10)
            p8 -= 9;
        p9 = d9 * 2;
        if (p9 >= 10)
            p9 -= 9;
        modulo = 10;
        /*  } else if (d3 == 6) {
             pub = true;
             p1 = d1 * 3;
             p2 = d2 * 2;
             p3 = d3 * 7;
             p4 = d4 * 6;
             p5 = d5 * 5;
             p6 = d6 * 4;
             p7 = d7 * 3;
             p8 = d8 * 2;
             p9 = 0;
         } else if (d3 == 9) {
             pri = true;
             p1 = d1 * 4;
             p2 = d2 * 3;
             p3 = d3 * 2;
             p4 = d4 * 7;
             p5 = d5 * 6;
             p6 = d6 * 5;
             p7 = d7 * 4;
             p8 = d8 * 3;
             p9 = d9 * 2;
         } */

        suma = p1 + p2 + p3 + p4 + p5 + p6 + p7 + p8 + p9;
        residuo = suma % modulo;

        var digitoVerificador = residuo == 0 ? 0 : modulo - residuo;

        if (selectTipoDoc.val() === '2') {
            if (numero.length === 10) {

                if (nat == true) {
                    if (digitoVerificador != d10) {
                        alertify.error('El número de cédula es incorrecto.');
                        inputRUCI.val("");
                    } else {
                        if (inputRUCI.val() === "0000000000") {
                            alertify.error('El número de cédula es incorrecto.');
                            inputRUCI.val("");
                        } else {
                            alertify.success('El número de cédula es correcto.');
                        }
                    }
                }
            }
        } else {
            if (selectTipoDoc.val() === '1') {

                var ruc = numero.substr(10, 13);
                var digito3 = numero.substring(2, 3);

                if (ruc == "001") {
                    //if (digito3 < 6) {
                    if (nat == true) {
                        if (digitoVerificador != d10) {
                            alertify.error('El ruc persona natural es incorrecto.');
                            inputRUCI.val("");
                        } else {
                            alertify.success('El ruc persona natural es correcto.');
                        }
                    }
                    /*  } else {
                         if (digito3 == 6) {
                             if (pub == true) {
                                 if (digitoVerificador != d9) {
                                     alertify.error('El ruc público es incorrecto.');
                                     inputRUCI.val("");
                                 } else {
                                     alertify.success('El ruc público es correcto.');
                                 }
                             }
                         } else {
                             if (digito3 == 9) {
                                 if (pri == true) {
                                     if (digitoVerificador != d10) {
                                         alertify.error('El ruc privado es incorrecto.');
                                         inputRUCI.val("");
                                     } else {
                                         alertify.success('El ruc privado es correcto.');
                                     }
                                 }
                             }
                         }
                     } */
                } else {
                    if (numero.length === 13) {
                        alertify.error('El ruc es incorrecto.');
                        inputRUCI.val("");
                    }
                }
            }
        }
    }

    function validPunto(e) {
        var key;
        if (window.event) {
            key = e.keyCode;
        } else if (e.which) {
            key = e.which;
        }

        if (key < 48 || key > 57) {
            if (key === 46 || key === 8) {
                return true;
            } else {
                return false;
            }
        }
        return true;
    }

    function handleCompararRUCI(data) {
        console.log(data);
        if (data == 1) {
            inputRUCI.val("");
            inputRUCI[0].focus();
            alertify.error("El número de cédula ya está registrado");
        } else {
            validRUCI();
        }
    }

    function handleGuardar(data) {
        if (data == 1) {
            onGuardar(inputRUCI.val());
            alertify.success('Datos Agregados Correctamente');
            formCmp[0].reset();
        } else {
            alertify.error("No se pudo guardar");
            onGuardar(null);
        }
    }

    function guardar() {
        if (!validarForm()) {
            return;
        }
        let cliente = {
            "ruc_ci": inputRUCI.val(),
            "empresa_pro": inputNombreCli.val(),
            "tipo_docu": selectTipoDoc.val(),
            "direccion_pro": inputDireccion.val(),
            "nro_telefono": inputNroTelelfono.val(),
            "nro_celular": inputNroCel.val(),
            "pais_pro": inputPais.val(),
            "ciudad_pro": inputCiudad.val(),
            "correo": inputEmail.val(),
            "cupo_credito": inputCupoC.val(),
            "observaciones_pro": textaNotas.val(),
            "tipo_pro": inputTipoCli.val(),
            "visitador": selectVisitador.val(),
            "fax": selectFax.val(),
            "forma_pago": selectFormaPago.val(),
            "principal_pro": selectPrincialPro.val(),
            "representante_legal": selectRepresentanteLegal.val()
        }
        btnGuardar[0].disabled = true;
        servicios.guardarCliente(cliente)
            .done(handleGuardar)
            .always(function () {
                btnGuardar[0].disabled = false;
            });
    }

    function validarForm() {
        let valid = formCmp[0].checkValidity();
        if (!valid) {
            btnEnviarForm.click();
            return false;
        }
        return true;
    }

    return {
        init,
        set contenedor(val) {
            contenedor = val;
        },
        set onGuardar(val) {
            onGuardar = val;
        }
    }
}