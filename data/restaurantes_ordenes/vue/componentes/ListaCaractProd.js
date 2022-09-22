export default {
    template: `#lista_caract_prod`,
    emits: ["aceptar"],
    /* props: {
        codProd: {
            type: String,
            required: true
        },
    }, */
    data() {
        return {
            caracteristicas: [],
            caracteristicasSelect: []
        }
    },
    mounted() {
        //this.obtenerCaracteristicasProd();
    },
    methods: {
        /*  obtenerCaracteristicasProd() {
             const vm = this;
             $.ajax({
                 url: "../productos/lista_caracteristicas_producto.php",
                 method: "get",
                 dataType: "json",
                 data: { cod_productos: vm.codProd },
                 success: function (data) {
                     vm.caracteristicas = data;
                 }
             });
         }, */
        selectCaracteristica(idcar) {
            let car = this.caracteristicas.find(el => el.id_caracteristica == idcar);
            if (!!car) {
                this.caracteristicasSelect.push(car);
            }
        },
        quitarCaracteristicaSeleccionada(idcar) {
            this.caracteristicasSelect =
                this.caracteristicasSelect.filter(el => el.id_caracteristica != idcar);
        },
        onChangeCaracteristica(e, idcar) {
            if (e.target.checked) {
                this.selectCaracteristica(idcar);
            } else {
                this.quitarCaracteristicaSeleccionada(idcar);
            }
        },
        aceptar() {
            this.$emit("aceptar", this.caracteristicasSelect);
        }
    }
}