import PantallaPago from "./componentes/PantallaPago.js";
import CargarCliente from "./componentes/CargarCliente.js";
import PantallaOrden from "./componentes/PantallaOrden.js";
import ListaOrdenes from "./componentes/ListaOrdenes.js";

export default {
    components: {
        "pantalla-pago": PantallaPago,
        "cargar-cliente": CargarCliente,
        "pantalla-orden": PantallaOrden,
        "lista-ordenes": ListaOrdenes
    },
    mounted() {
        this.intervalHora();
        this.obtenerPuntoEmision();
        this.initDialogos();
    },
    data() {
        return {
            totalDescuento: 0,
            totalVenta: 0,
            totalTarifa0: 0,
            totalTarifa12: 0,
            totalIva: 0,
            cliente: null,
            formasPago: [],
            productos: [],
            tipoDocumento: "FACTURA",
            keyCargarCliente: 0,
            keyPantallaOrden: 0,
            keyListaOrdenes: 0,
            horaActual: "",
            puntoEmision: "",
            mesa: "",
            loading: false,
            iva: 12,
            valoresDescuentoOrden:{}
        }
    },
    computed: {
        fechaActual() {
            return (new Date()).toLocaleDateString("fr-CA");
        }
    },
    methods: {
        initDialogos() {
            $("#dialog_lista_ordenes").dialog({
                modal: true,
                width: (window.screen.width * window.devicePixelRatio) - 300,
                height: (window.screen.height * window.devicePixelRatio) - 150,
                autoOpen: false,
                title: "Lista de Ordenes Generadas",
                close: function (event, ui) {

                },
                open: function (event, ui) {

                }
            });
        },
        onIrPagar(e) {
            $("#pago").show();
            $("#ordenes").hide();
            $("#nro_mesa").focus();
            this.totalVenta = e.totalVenta;
            this.totalTarifa0 = e.totalTarifa0;
            this.totalTarifa12 = e.totalTarifa12;
            this.totalIva = e.totalIva;
            this.productos = e.productos;
            this.tipoDocumento = e.tipoDocumento;
            this.totalDescuento = e.totalDescuento;
            this.iva = e.iva;
        },
        onPagar(e) {
            this.formasPago = e.formasPago;
            this.valoresDescuentoOrden = e.valoresDescuento
            this.guardarOrden();
        },
        onReimprimirOrden(e) {
            this.imprimirDocumento(e.iddoc, e.tipodoc);
        },
        onReimprimirOrdenCocia(e) {
            if (e.tipodoc == "FACTURA") {
                imprimir_cocina_factura(e.iddoc, true);
            } else if (e.tipodoc == "NOTA") {
                imprimir_cocina_nota(e.iddoc, true);
            }
        },
        volverOrden() {
            $("#ordenes").show();
            $("#pago").hide();

        },
        terminarVenta() {
            this.keyCargarCliente = this.keyCargarCliente + 1;
            this.keyPantallaOrden = this.keyPantallaOrden + 1;
            this.resetData();
            this.volverOrden();
        },
        resetData() {
            this.totalVenta = 0;
            this.totalTarifa0 = 0;
            this.totalTarifa12 = 0;
            this.totalIva = 0;
            this.cliente = null;
            this.formasPago = [];
            this.productos = [];
            this.mesa = "";
        },
        intervalHora() {
            return setInterval(() => {
                let date = new Date();
                let hora = date.getHours();
                let min = date.getMinutes();
                let seg = date.getSeconds();
                this.horaActual = `${(hora + '').padStart(2, '0')}:${(min + '').padStart(2, '0')}:${(seg + '').padStart(2, '0')}`;
            }, 1000);
        },
        obtenerPuntoEmision() {
            const vm = this;
            $.ajax({
                type: "GET",
                url: "../../procesos/buscar_p_emision.php",
                data: "",
                success: function (data) {
                    vm.puntoEmision = data;
                }
            });
        },
        async guardarOrden() {
            this.loading = true;
            let formapagocabecera = "otros";
            let valorrecibido = 0;
            let cambio = 0;

            if (this.formasPago.length == 1) {
                if (this.formasPago[0]["formaPago"] == "CONTADO") {
                    formapagocabecera = "Contado"
                    valorrecibido = this.formasPago[0]["valor"];
                    cambio = this.formasPago[0]["cambio"];
                }
            }

            let totalVenta = this.valoresDescuentoOrden["totalVenta"];
            let totalT0 = this.valoresDescuentoOrden["totalT0"];
            let totalT12 = this.valoresDescuentoOrden["totalT12"];
            let totalIva = this.valoresDescuentoOrden["totalIva"];
            let totalDescuento = this.valoresDescuentoOrden["totalDescuento"];

            //return;
            let data = {
                cabecera: {
                    id_cliente: this.cliente.id_cliente,
                    identificacion: this.cliente.identificacion,
                    nombres_cli: this.cliente.nombres_cli,
                    celular: this.cliente.celular,
                    direccion_cli: this.cliente.direccion_cli,
                    correo: this.cliente.correo,
                    totalTarifa0: totalT0.toFixed(2),
                    totalTarifa12: totalT12.toFixed(2),
                    totalVenta: totalVenta.toFixed(2),
                    totalIva: totalIva.toFixed(2),
                    tipoDocumento: this.tipoDocumento,
                    totalDescuento: totalDescuento.toFixed(2),
                    formaPago: formapagocabecera,
                    valorRecibido: valorrecibido,
                    cambio: cambio,
                    mesa: this.mesa
                },
                productos: this.productos,
                formasPago: this.formasPago
            };
            try {
                let res = await $.ajax({
                    method: "POST",
                    url: "guardar_orden.php",
                    data,
                    dataType: "json"
                });
                if (res.status == "correcto") {
                    if (!!res.factura) {
                        if (res.factura.estado == 2) {

                            //reenviarCorreo(res.factura.id);
                        }
                        this.autorizarFactura(res.factura.id, res.factura.clave);

                        this.imprimirDocumento(res.factura.id, "FACTURA");
                        //imprimir_cocina_factura(res.factura.id, true);
                    } else {
                        this.imprimirDocumento(res.nota.id, "NOTA");
                        //imprimir_cocina_nota(res.nota.id, true);
                    }
                    //this.alertMensaje(`<b>Orden cobrada correctamente.</b>`);
                    this.terminarVenta();
                } else {
                    if (!!res.mensaje) {
                        this.alertMensaje(`<b>${res.mensaje}</b>`);
                    } else {
                        this.alertMensaje(`<b>No se pudo guardar la orden.</b>`);
                    }

                }
            } catch (err) {
                console.error(err);
            }
            this.loading = false;
            //window.removeEventListener("keypress", bloqueare,false);
        },
        alertMensaje(mensaje) {
            $("#overlay_pantalla").show();
            alertify.alert(mensaje, function (e) {
                $("#overlay_pantalla").hide();
            });
        },
        cargarCliente(cliente) {
            this.cliente = cliente;
        },
        imprimirDocumento(iddoc, tipodoc) {
            if (tipodoc == 'FACTURA') {
                var myWindow = window.open(formatoFactura + "?hoja=A5&id=" + iddoc, "_blank");
                myWindow.focus();
                myWindow.print();
            } else if (tipodoc == "NOTA") {
                var myWindow = window.open(formatoNotaVenta + "?hoja=A2&id=" + iddoc, "_blank");
                myWindow.focus();
                myWindow.print();
            }
        },
        openDialogListaOrdenes() {
            this.keyListaOrdenes++;
            $("#dialog_lista_ordenes").dialog("open");
        },

        async autorizarFactura(idfact, clave) {
            let res = await $.ajax({
                method: "POST",
                url: "../../procesos/autorizacion_documentos/autorizar_factura.php",
                data: { id_factura: idfact, clave_acceso: clave },
                dataType: "json"
            });
        }
    }
    //19-09-2022
}
//19-09-2022