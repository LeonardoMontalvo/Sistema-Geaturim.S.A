export default {
    template: `#cargar_cliente`,
    emits: ["selectCliente"],
    data() {
        return {
            cmpAddCliente: null,
            clienteSeleccioado: null,
        }
    },
    computed: {
        rucCliente() {
            return !!this.clienteSeleccioado ? this.clienteSeleccioado.identificacion : "";
        },
        dirCliente() {
            return !!this.clienteSeleccioado ? this.clienteSeleccioado.direccion_cli : "";
        },
        nombreCliente() {
            return !!this.clienteSeleccioado ? this.clienteSeleccioado.nombres_cli : "";
        },
        telCliente() {
            if (!!this.clienteSeleccioado) {
                return !!this.clienteSeleccioado.celular ? this.clienteSeleccioado.celular : this.clienteSeleccioado.telefono;
            }
            return "";
        },
        correoCliente() {
            return !!this.clienteSeleccioado ? this.clienteSeleccioado.correo : "";
        }
    },
    mounted() {
        const vm = this;
        this.autocompleteClientes();
        $("#dialog_form_cliente").dialog({
            modal: true,
            width: 800,
            height: 505,
            minHeight: 600,
            minHeight: 600,
            autoOpen: false,
            title: "REGISTRAR CLIENTE",
            close: function (event, ui) {
                $("#form_cmp")[0].reset();
            }
        });

        $(window).off('resize');
        /*  $(window).on('resize', function () {
            $("#dialog_form_cliente").dialog("option", "width", window.innerWidth - 50);
            $("#dialog_form_cliente").dialog("option", "height ", window.innerHeight - 30);
        }).trigger('resize'); */

        this.addCliente();
        $("#nuevo_cliente").click(function (e) {
            $("#dialog_form_cliente").dialog("open")
        });

        this.buscarClientes("9999999999").then(function (data) {
            vm.clienteSeleccioado = data[0];
            $("#buscar_clientes").val(vm.nombreCliente);
            vm.seleccionarCliente();
        });
    },
    methods: {
        autocompleteClientes() {
            const vm = this;
            $("#buscar_clientes")[0].addEventListener("input", function (e) {
                if (e.target.value == "") {
                    vm.limpiarCliente();
                }
            })
            $("#buscar_clientes")
                .autocomplete({
                    source: function (request, response) {
                        vm.limpiarCliente();
                        var data = { term: request.term };
                        $.get(
                            "./buscar_clientes.php",
                            data,
                            response,
                            "json"
                        );
                    },
                    minLength: 0,
                    select: function (event, ui) {
                        $("#buscar_clientes").val(ui.item["nombres_cli"]);
                        vm.clienteSeleccioado = ui.item;
                        vm.seleccionarCliente();
                        return false;
                    },
                    focus: function (event, ui) {
                        return false;
                    }
                })
                .data("ui-autocomplete")._renderItem = function (ul, item) {
                    return $("<li>")
                        .append("<a>" + item["nombres_cli"] + "</a>")
                        .appendTo(ul);
                };
        },
        addCliente() {
            const vm = this;
            $.getScript("../clientes/clientes_ui_util/clientes.js", function () {
                vm.cmpAddCliente = new AddCliente();
                vm.cmpAddCliente.contenedor = $("#form_cliente");
                vm.cmpAddCliente.onGuardar = function (data) {
                    if (!!data) {
                        vm.buscarClientes(data)
                            .then(function (data) {
                                vm.clienteSeleccioado = data[0];
                                $("#buscar_clientes").val(vm.nombreCliente);
                                $("#dialog_form_cliente").dialog("close");
                                vm.seleccionarCliente();
                            });

                    }
                };
                vm.cmpAddCliente.init();
            });
        },
        buscarClientes(term) {
            return $.ajax({
                url: "./buscar_clientes.php",
                method: "GET",
                dataType: "json",
                data: { term: term }
            }).fail(function (err) {
                alertify.error("Hubo un problema al buscar cliente");
            });
        },
        seleccionarCliente() {
            this.$emit("selectCliente", this.clienteSeleccioado);
        },
        limpiarCliente(e) {
            this.clienteSeleccioado = null;
            this.seleccionarCliente();
            if (e) {
                document.getElementById("buscar_clientes").value = "";
                document.getElementById("buscar_clientes").focus();
            }
        },
        obtenerClienteServ(identificacion) {
            let url = "http://181.188.216.198:81/clientes/data/clientes/buscar_cliente_ser.php";
            return $.ajax({
                url: url,
                method: "GET",
                dataType: "json",
                data: {
                    term: identificacion
                }
            });
        },
        async onEnterInputCli(e) {
            let inputval = e.target.value;
            try {
                //let inputval = e.target.value;
                let cliente = await this.buscarClientes(inputval);

                if (cliente.length == 0) {
                    let clientesrv = await this.obtenerClienteServ(inputval);
                    console.log(clientesrv);
                    if (clientesrv) {
                        let clientesave = {
                            "ruc_ci": clientesrv[0].value,
                            "nombres_cli": clientesrv[0].nombre_cliente,
                            "tipo_cli": "",
                            "direccion_cli": clientesrv[0].direccion_cliente,
                            "nro_telefono": clientesrv[0].telefono_cliente,
                            "nro_celular": clientesrv[0].telefono_cliente,
                            "pais_cli": "",
                            "ciudad_cli": "",
                            "email": clientesrv[0].correo,
                            "cupo_credito": "",
                            "notas_cli": "",
                            "tipo_docu": clientesrv[0].id_tdocu
                        }
                        await this.guardarCliente(clientesave);
                    } else {
                        this.cmpAddCliente.setIdentificacion(inputval);
                        $("#dialog_form_cliente").dialog("open")
                        return;
                    }
                }

                cliente = await this.buscarClientes(inputval);

                this.clienteSeleccioado = cliente[0];
                this.seleccionarCliente();
                $("#buscar_clientes").val(this.nombreCliente);
                $("#buscar_clientes").autocomplete("close");
            } catch (error) {
                this.cmpAddCliente.setIdentificacion(inputval);
                $("#dialog_form_cliente").dialog("open")
                console.log(error);
            }
        },
        guardarCliente(cliente) {
            return $.ajax({
                dataType: "json",
                method: "POST",
                url: "../clientes/guardar_clientes.php",
                data: cliente
            });
        }
    }
}