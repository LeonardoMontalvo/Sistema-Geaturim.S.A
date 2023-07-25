/* import { ref, computed } from 'vue';
function useDescuentoOrden(totalVena,iva) {
    const porcDescuento = computed(() => {
        return (this.valorDescuento * 100) / (this.totalVenta);
    })
    const totalDescuento = computed(() => {
        let prcdesc = this.porcDescuento;
        let desct0 = (this.totalTarifa0 * (prcdesc / 100));
        let desct12 = (this.totalTarifa12 * (prcdesc / 100));
        return desct12 + desct0;
    });
    const totalT0ConDescuento = computed(() => {
        let prcdesc = this.porcDescuento;
        let desct0 = (this.totalTarifa0 * (1 - (prcdesc / 100)));
        return desct0;
    });
    const totalT12ConDescuento = computed(() => {
        let prcdesc = this.porcDescuento;
        let desct12 = (this.totalTarifa12 * (1 - (prcdesc / 100)));
        return desct12;
    });
    const totalIvaConDescuento = computed(() => {
        return this.totalT12ConDescuento * (this.iva / 100);
    })
}
 */
export default {
    template: `#pantalla_pago`,
    props: {
        totalVenta: {
            type: Number,
            required: true
        },
        totalTarifa0: {
            type: Number,
            required: true
        },
        totalTarifa12: {
            type: Number,
            required: true
        },
        iva: {
            type: Number,
            required: true
        },
        cliente: {
            type: Object,
            required: true
        }
    },
    emits: ["pagar"],
    data() {
        return {
            formasPago: [],
            valorMixtoContado: 0,
            valorMixtoTarjeta: 0,
            valorMixtoTransferencia: 0,
            valorMixtoCredito: 0,
            nroDocTransferenciaMixto: "",
            nroDocTransferencia: "",
            fechaVenceCreditoMixto: "",
            formaPagoSeleccionada: "",
            valorFormaPago: 0,
            valorDescuento: 0
        }
    },
    mounted() {
        const vm = this;
        $("#dialog_fp").dialog({
            modal: true,
            width: 600,
            maxHeight: 400,
            autoOpen: false,
            title: "PAGO",
            close: function (event, ui) {
                vm.formaPagoSeleccionada = "";
                vm.valorFormaPago = 0;
                vm.valorDescuento = 0;
            },
            open: function (event, ui) {
                vm.valorFormaPago = +vm.totalPagar.toFixed(2);

                $("#nro_documento_fp_div").hide();
                document.getElementById("nro_documento_fp").removeAttribute("required");

                if (vm.formaPagoSeleccionada == vm.fpTransferencia) {
                    $("#nro_documento_fp_div").show();
                    document.getElementById("nro_documento_fp").setAttribute("required", "required");
                }


                $("#valor_recibido_fp")[0].select();
            }
        });
        $("#dialog_fp_mixto").dialog({
            modal: true,
            width: 600,
            height: 410,
            autoOpen: false,
            title: "PAGO",
            close: function (event, ui) {
                vm.valorMixtoContado = 0;
                vm.valorMixtoTarjeta = 0;
                vm.valorMixtoTransferencia = 0;
                vm.valorMixtoCredito = 0;

                $("#nro_documento_mixto_fp_div").hide()
                $("#fecha_vence_mixto_fp_div").hide()

                document.getElementById("nro_documento_mixto_fp").removeAttribute("required");
                document.getElementById("fecha_vence_mixto_fp").removeAttribute("required");
                vm.nroDocTransferenciaMixto = "";
            },
            open: function (event, ui) {
            }
        });
        $("#btn_fp_contado").click(this.onClickFPContado);
        $("#btn_fp_tarjeta").click(this.onClickFPTarjeta);
        $("#btn_fp_transferencia").click(this.onClickFPTransferencia);
        $("#btn_fp_mixto").click(this.onClickFPMixto);
        $("#btn_fp_orden").click(function (e) {
            vm.irOrden();
        });
    },
    computed: {
        fpContado() {
            return "CONTADO";
        },
        fpTarjeta() {
            return "TCREDITO"
        },
        fpTransferencia() {
            return "TRANSFERENCIAS";
        },
        fpCredito() {
            return "CREDITO";
        },
        cambio() {
            let cambio = (this.valorFormaPago - this.totalPagar).toFixed(2);
            if (cambio > 0) {
                return cambio;
            }
            return 0.00;
        },
        restante() {
            let restante = this.totalPagar -
                (this.valorMixtoContado +
                    this.valorMixtoTarjeta +
                    this.valorMixtoTransferencia + this.valorMixtoCredito);
            return restante.toFixed(2);
        },
        porcDescuento() {
            return (this.valorDescuento * 100) / (this.totalVenta);
        },
        totalDescuento() {
            let prcdesc = this.porcDescuento;
            let desct0 = (this.totalTarifa0 * (prcdesc / 100));
            let desct12 = (this.totalTarifa12 * (prcdesc / 100));
            return desct12 + desct0;
        },
        totalT0ConDescuento() {
            let prcdesc = this.porcDescuento;
            let desct0 = (this.totalTarifa0 * (1 - (prcdesc / 100)));
            return desct0;
        },
        totalT12ConDescuento() {
            let prcdesc = this.porcDescuento;
            let desct12 = (this.totalTarifa12 * (1 - (prcdesc / 100)));
            return desct12;
        },
        totalIvaConDescuento() {
            return this.totalT12ConDescuento * (this.iva / 100);
        },
        totalPagar() {
            let totalt0 = this.totalT0ConDescuento;
            let totalt12 = this.totalT12ConDescuento;
            return totalt0 + totalt12 + this.totalIvaConDescuento;
        }
    },
    methods: {
        onClickFPContado(e) {
            if (e.originalEvent.pointerType === '') {
                return;
            }
            if (!this.validarCliente()) {
                return;
            }
            this.formaPagoSeleccionada = this.fpContado;
            $("#dialog_fp").dialog("open");
        },
        onClickFPTarjeta(e) {
            if (e.originalEvent.pointerType === '') {
                return;
            }
            if (!this.validarCliente()) {
                return;
            }
            this.formaPagoSeleccionada = this.fpTarjeta;
            $("#dialog_fp").dialog("open");
        },
        onClickFPTransferencia(e) {
            if (e.originalEvent.pointerType === '') {
                return;
            }
            if (!this.validarCliente()) {
                return;
            }
            this.formaPagoSeleccionada = this.fpTransferencia;
            $("#dialog_fp").dialog("open");
        },
        onClickFPCredito(e) {
            if (e.originalEvent.pointerType === '') {
                return;
            }
            if (!this.validarCliente()) {
                return;
            }
            this.formaPagoSeleccionada = this.fpCredito;
            $("#dialog_fp").dialog("open");
        },
        onClickFPMixto(e) {
            if (e.originalEvent.pointerType === '') {
                return;
            }
            if (!this.validarCliente()) {
                return;
            }
            $("#dialog_fp_mixto").dialog("open");
            document.getElementById("valor_contado_fp").focus();
        },
        onAceptarFP(e) {

            if (!this.validarFomularioFormaPago()) {
                return;
            }
            this.formasPago = [
                {
                    formaPago: this.formaPagoSeleccionada,
                    valor: this.valorFormaPago,
                    nroDoc: this.nroDocTransferencia,
                    cambio: this.cambio,
                }
            ];

            let valoresDescuento = {
                totalDescuento: this.totalDescuento,
                totalT0: this.totalT0ConDescuento,
                totalT12: this.totalT12ConDescuento,
                totalIva: this.totalIvaConDescuento,
                totalVenta: this.totalPagar
            };

            $("#dialog_fp").dialog("close");
            this.$emit("pagar", {
                formasPago: this.formasPago,
                valoresDescuento
            });
        },
        onAceptarFPMixto(e) {
            if (!this.validarFomularioFormaPagoMixto()) {
                return;
            }
            if (this.restante != 0) {
                if (this.restante > 0) {
                    alertify.alert("<b>No puede continuar. Hay un valor restante pendiente.</b>",
                        function (e) {
                            document.getElementById("valor_contado_fp").focus();
                        });
                } else if (this.restante < 0) {
                    alertify.alert("<b>No puede continuar. El valor restante no puede ser negativo.</b>",
                        function (e) {
                            document.getElementById("valor_contado_fp").focus();
                        });
                }
                return;
            }
            this.formasPago = [];
            if (this.valorMixtoContado > 0) {
                this.formasPago.push({
                    formaPago: this.fpContado,
                    valor: this.valorMixtoContado,
                    nroDoc: ""
                });
            }
            if (this.valorMixtoTarjeta > 0) {
                this.formasPago.push({
                    formaPago: this.fpTarjeta,
                    valor: this.valorMixtoTarjeta,
                    nroDoc: ""
                });
            }
            if (this.valorMixtoTransferencia > 0) {
                this.formasPago.push({
                    formaPago: this.fpTransferencia,
                    valor: this.valorMixtoTransferencia,
                    nroDoc: this.nroDocTransferenciaMixto
                })
            }
            if (this.valorMixtoCredito > 0) {
                this.formasPago.push({
                    formaPago: this.fpCredito,
                    valor: this.valorMixtoCredito,
                    nroDoc: "",
                    fechaVence: this.fechaVenceCreditoMixto
                })
            }
            this.formasPago = this.formasPago.map(el => {
                return el;
            });

            let valoresDescuento = {
                totalDescuento: this.totalDescuento,
                totalT0: this.totalT0ConDescuento,
                totalT12: this.totalT12ConDescuento,
                totalIva: this.totalIvaConDescuento,
                totalVenta: this.totalPagar
            };

            $("#dialog_fp_mixto").dialog("close");
            this.$emit("pagar", {
                formasPago: this.formasPago,
                valoresDescuento
            });
        },
        onEnterValorRecibido(e) {
            let hasnrodoc = document.getElementById("nro_documento_fp").hasAttribute("required");
            if (hasnrodoc) {
                if (this.validarFomularioFormaPago()) {
                    document.getElementById("nro_documento_fp").focus();
                }

            } else {
                this.onAceptarFP(null);
            }
        },
        onEnterValorDescuento(e) {
            if (this.validarFomularioFormaPago()) {
                document.getElementById("valor_recibido_fp").focus();
            }
        },
        onEnterValorDescuentoMixto(e) {
            document.getElementById("valor_contado_fp").focus();
        },
        onEnterNroDocumento(e) {
            this.onAceptarFP(null);
        },
        onEnterValorContado(e) {
            document
                .getElementById("valor_tarjeta_fp")
                .focus();
        },
        onEnterValorTarjeta(e) {
            document
                .getElementById("valor_transferencia_fp")
                .focus();
        },
        onEnterValorTransferencia(e) {
            let hasnrodoc = document.getElementById("nro_documento_mixto_fp").hasAttribute("required")
            if (hasnrodoc) {
                document.getElementById("nro_documento_mixto_fp").focus();
            } else {
                document.getElementById("valor_credito_fp").focus();
                //this.onAceptarFPMixto(null);
            }
        },
        onEnterValorCredito(e) {
            let hasnrodoc = document.getElementById("fecha_vence_mixto_fp").hasAttribute("required")
            if (hasnrodoc) {
                document.getElementById("fecha_vence_mixto_fp").focus();
            } else {
                this.onAceptarFPMixto(null);
            }
        },
        onEnterNroDocumentoMixto(e) {
            this.onAceptarFPMixto(null);
        },
        onInputValorTransferencia(e) {
            if (!!this.valorMixtoTransferencia) {
                $("#nro_documento_mixto_fp_div").show()
                document.getElementById("nro_documento_mixto_fp").setAttribute("required", "");
                $("#dialog_fp_mixto").dialog({
                    height: 440
                });
            } else {
                $("#nro_documento_mixto_fp_div").hide()
                document.getElementById("nro_documento_mixto_fp").removeAttribute("required");
                this.nroDocTransferencia = "";
                $("#dialog_fp_mixto").dialog({
                    height: 410
                });
            }
        },
        onInputValorCredito(e) {
            if (!!this.valorMixtoCredito) {
                //$("#nro_documento_mixto_fp_div").show();
                $("#fecha_vence_mixto_fp_div").show();
                document.getElementById("fecha_vence_mixto_fp").setAttribute("required", "");
                $("#dialog_fp_mixto").dialog({
                    height: 480
                });
            } else {
                //$("#nro_documento_mixto_fp_div").hide()
                $("#fecha_vence_mixto_fp_div").hide();
                document.getElementById("fecha_vence_mixto_fp").removeAttribute("required");
                //this.nroDocTransferenciaMixto = "";
                this.fechaVenceCreditoMixto = "";
                $("#dialog_fp_mixto").dialog({
                    height: 410
                });
            }
        },
        validarCliente() {
            if (!!!this.cliente) {
                alertify.alert("<b>Seleccione un cliente para continuar.<b>");
                return false;
            }
            return true;
        },
        validarFomularioFormaPago() {
            let form = document.getElementById("form_fp");
            return form.reportValidity()
        },
        validarFomularioFormaPagoMixto() {
            let form = document.getElementById("form_mixto_fp");
            return form.reportValidity()
        },
    }
}