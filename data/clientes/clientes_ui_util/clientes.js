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
                url: "../clientes/comparar_cedulas.php",
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
                url: "../clientes/guardar_clientes.php",
                data: cliente
            }).fail(function () {
                alertify.error("Hubo un problema al registrar cliente");
            });
        }
        function consultaSri(ruc) {
            return $.ajax({
                dataType: "json",
                url: "../clientes/clientes_ui_util/consulta_sri.php",
                data: { ruc }
            });
        }
        return {
            obtenerTipoDocumento,
            compararCedula,
            guardarCliente,
            consultaSri
        };
    })();

    let selectTipoDoc;
    let inputNombreCli, inputNroTelelfono,
        inputPais, inputDireccion, inputRUCI,
        inputNroCel, inputCiudad, inputEmail, inputCupoC,
        inputTipoCli;
    let textaNotas;
    let btnGuardar, btnEnviarForm;
    let formCmp;

    let contenedor;
    let onGuardar;

    function init() {
        contenedor.load("../clientes/clientes_ui_util/formulario.html", function () {
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

                });
            inicioControles();
            inicioRUCI();
            inicioTipoDoc();
            inicioButtons();

            inputCupoC.keypress(validPunto);
            inputNroCel.keypress(validPunto);

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
        inputRUCI[0].addEventListener("paste", function (e) {
            inputRUCI.trigger("keyup");
        });
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
                    //inputRUCI.val("");
                    inputRUCI.off("keypress");
                    inputRUCI.removeAttr("disabled");
                    inputRUCI.attr("maxlength", "30");
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
        validarIdentificacion(inputRUCI, tipodoc, () => {
            setTimeout(() => {
                inputNombreCli.focus()
            }, 200);
        });
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
    function insertar_cliente(ruc, nombres, direccion, telefono, email, id_tdocu) {
        console.log("entro a la funcion insert");

        $.ajax({
            url: "http://181.188.216.198:81/clientes/data/clientes/guardar_clientes_ser.php",
            type: "POST",
            data: "ruc_ci=" + ruc
                + "&nombre_cliente=" + nombres
                + "&direccion_cliente=" + direccion
                + "&telefono_cliente=" + telefono
                + "&correo=" + email.toLowerCase()
                + "&id_tdocu=" + id_tdocu,
            success: function (data) {
                var val = data;
                if (val == 1) {
                    //                alertify.success("Cliente guardado correctamente en servidor");
                } else {
                    //                alertify.success("Cliente ya existe en servidor");
                }
            },
        });
    }

    function guardar() {
        if (!validarForm()) {
            return;
        }
        let cliente = {
            "ruc_ci": inputRUCI.val(),
            "nombres_cli": inputNombreCli.val(),
            "tipo_cli": inputTipoCli.val(),
            "direccion_cli": inputDireccion.val(),
            "nro_telefono": inputNroCel.val(),
            "nro_celular": inputNroCel.val(),
            "pais_cli": inputPais.val(),
            "ciudad_cli": inputCiudad.val(),
            "email": inputEmail.val(),
            "cupo_credito": inputCupoC.val(),
            "notas_cli": textaNotas.val(),
            "tipo_docu": selectTipoDoc.val()
        }
        insertar_cliente(inputRUCI.val(), inputNombreCli.val(), inputDireccion.val(), inputNroCel.val(), inputEmail.val(), selectTipoDoc.val());
        btnGuardar[0].disabled = true;
        servicios.guardarCliente(cliente)
            .done(handleGuardar)
            .always(function () {
                btnGuardar[0].disabled = false;
            });
    }

    function validarForm() {
        return formCmp[0].reportValidity();
    }

    function setIdentificacion(identificacion) {
        if (identificacion.length == 13) {
            servicios.consultaSri(identificacion).then(data => {
                if (Array.isArray(data)) {
                    inputNombreCli.val(data[0].razonSocial)
                }
            })
            selectTipoDoc.val(1);
        } else if (identificacion.length == 10) {
            selectTipoDoc.val(2);
        } else {
            selectTipoDoc.val("");
        }
        selectTipoDoc.change();
        inputRUCI.val(identificacion);
        inputRUCI.trigger("keyup");
        setTimeout(() => inputNombreCli.focus(), 200);
    }

    return {
        init,
        set contenedor(val) {
            contenedor = val;
        },
        set onGuardar(val) {
            onGuardar = val;
        },
        setIdentificacion
    }
}