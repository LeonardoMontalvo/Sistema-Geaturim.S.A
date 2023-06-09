var AperturaForm = function () {
    let contenedor;
    let loading = false;
    let onGuardarApertura = function () { };
    let servicios = (function () {
        function guardarApertura(monto, observacion) {
            return $.ajax({
                url: "../apertura_caja/guardar_apertura.php",
                method: "POST",
                dataType: "json",
                data: {
                    monto: monto,
                    observacion: observacion
                }
            });
        }
        function consultarCaja(consulta) {
            return $.ajax({
                url: "../apertura_caja/consultar_caja.php",
                method: "GET",
                dataType: "json",
                data: {
                    consulta: consulta,
                }
            });
        }
        return {
            guardarApertura,
            consultarCaja
        };
    })();
    function init() {
        contenedor.load("../apertura_caja/apertura_ui_util/formulario.php", function () {
            $("#cmp_apertura_monto").keypress(punto);
            $("#cmp_apertura_guardar").click(function (e) {
                if (loading) {
                    return;
                }
                guardarApertura();
            });
        });
    }

    function punto(e) {
        var key;
        if (window.event) {
            key = e.keyCode;
        } else if (e.which) {
            key = e.which;
        }

        if (key < 48 || key > 57) {
            if (key == 46 || key == 8) {
                return true;
            } else {
                return false;
            }
        }
        return true;
    }

    function validaFormulario() {
        let form = document.getElementById("cmp_apertura_form");
        if (!form.reportValidity()) {
            return false;
        }
        return true;
    }

    function guardarApertura() {
        if (validaFormulario()) {
            loadingGuardarApertura();
            let formData = new FormData(document.getElementById("cmp_apertura_form"));
            servicios.guardarApertura(formData.get("monto"), formData.get("observacion")).then(rs => {
                stopLoadingGuardarApertura();
                onGuardarApertura(rs);
            }, err => {
                stopLoadingGuardarApertura();
                alertify.alert("<b>Hubo un problema al guardar.</b>");
                $("#alertify-ok").css({ "background": "red" });
            });
        }
    }

    function estaCajaAbierta() {
        return servicios.consultarCaja("esta_caja_abierta");
    }

    function loadingGuardarApertura() {
        loading = true;
        $("#cmp_apertura_guardar")[0].disabled = true;
    }

    function stopLoadingGuardarApertura() {
        loading = false;
        $("#cmp_apertura_guardar")[0].disabled = false;
    }

    return {
        init,
        estaCajaAbierta,
        set contenedor(val) {
            contenedor = val;
        },
        set onGuardarApertura(val) {
            onGuardarApertura = val;
        }
    }
}