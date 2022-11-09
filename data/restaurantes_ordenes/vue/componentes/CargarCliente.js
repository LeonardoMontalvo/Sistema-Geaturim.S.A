export default {
    template: `#cargar_cliente`,
    emits: ["selectCliente"],
    data() {
        return {
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
        correoCliente(){
            return !!this.clienteSeleccioado ? this.clienteSeleccioado.correo : "";
        }
    },
    mounted() {
        const vm = this;
        this.autocompleteClientes();
        $("#dialog_form_cliente").dialog({
            modal: true,
            width: window.innerWidth - 50,
            height: window.innerHeight - 30,
            minHeight: 600,
            minHeight: 600,
            autoOpen: false,
            title: "REGISTRAR CLIENTE",
            close: function (event, ui) {
                $("#form_cmp")[0].reset();
            }
        });

        $(window).off('resize');
        $(window).on('resize', function () {
            $("#dialog_form_cliente").dialog("option", "width", window.innerWidth - 50);
            $("#dialog_form_cliente").dialog("option", "height ", window.innerHeight - 30);
        }).trigger('resize');

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
            $.getScript("../restaurantes_ordenes/clientes/clientes.js", function () {
                let cmpAddCliente = new AddCliente();
                cmpAddCliente.contenedor = $("#form_cliente");
                cmpAddCliente.onGuardar = function (data) {
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
                cmpAddCliente.init();
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
        }
    }
}