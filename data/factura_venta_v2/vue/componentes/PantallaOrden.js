
//import { ref, computed } from 'vue';

/* function dialogoCabmiarPrecio(iva) {
    console.log(iva);
    const calcularPrecioIvaItem = (precio) => {
        return Number(precio) * (1 + (this.iva / 100))
    };
    const diagprecio = ref(0);
    const diagprecioIva = computed(() => calcularPrecioIvaItem(diagprecio.value));
    return {
        diagprecio,
        diagprecioIva
    }
} */

function useControlPrecios() {
    const setearPrecioSegunCantidad = (item, cantidad) => {
        let pmin = +item.precio_minorista;
        let pmay = +item.precio_mayorista;
        let pneg = +item.precio_negocio;

        let cmay = +item.cantidad_mayorista;
        let cneg = +item.cantidad_negocio;

        let tpmin = "MINORISTA";
        let tpmay = "MAYORISTA";
        let tpneg = "NEGOCIO";

        if (!cmay || !cneg) {
            if (!cmay && !cneg) {
                item.precio = pmin;
                item.tipo_precio = tpmin;
            } else if (!cmay) {
                if (cantidad >= cneg) {
                    item.precio = pneg;
                    item.tipo_precio = tpneg;
                } else {
                    item.precio = pmin;
                    item.tipo_precio = tpmin;
                }
            } else if (!cneg) {
                if (cantidad >= cmay) {
                    item.precio = pmay;
                    item.tipo_precio = tpmay;
                } else {
                    item.precio = pmin;
                    item.tipo_precio = tpmin;
                }
            }
        } else {
            if (cantidad < cmay && cantidad < cneg) {
                item.precio = pmin;
                item.tipo_precio = tpmin;
            } else if (cmay < cneg) {
                if (cantidad >= cmay && cantidad < cneg) {
                    item.precio = pmay;
                    item.tipo_precio = tpmay;
                } else if (cantidad >= cneg) {
                    item.precio = pneg;
                    item.tipo_precio = tpneg;
                }
            } else if (cneg < cmay) {
                if (cantidad >= cneg && cantidad < cmay) {
                    item.precio = pneg;
                    item.tipo_precio = tpneg;
                } else if (cantidad >= cmay) {
                    item.precio = pmay;
                    item.tipo_precio = tpmay;
                }
            } else {
                item.precio = pmin
                item.tipo_precio = tpmin;
            }
        }
    }

    return {
        setearPrecioSegunCantidad
    }
}

export default {
    template: `#pantalla_orden`,
    emits: ["irPagar"],
    setup(props, context) {
        return {
            ...useControlPrecios()
        }

    },
    data() {
        return {
            iva: 12,
            productos: [],
            categorias: [],
            categoriaSeleccionada: 0,
            productosSeleccionados: [],
            productosPromocion: [],
            caracteristicas: [],
            caracteristicasSelect: [],
            productoSeleccionado: null,
            precioIvaModalCp: 0,
            cantidadModalCp: 1
        }
    },
    computed: {
        totalVenta() {
            let total = 0;
            this.productosSeleccionados.forEach(el => {
                total += Number(el.precio_iva_descuento) * Number(el.cantidad);
            });
            total = Number(total.toFixed(4));
            return +(total + this.totalVentaPromo).toFixed(4);
        },
        totalVentaPromo() {
            let total = 0;
            this.productosPromocion.forEach(el => {
                total += Number(el.precio_iva) * Number(el.cantidad);
            });
            total = Number(total.toFixed(4));
            return total;
        },
        totalTarifa0() {
            let tarifa0 = 0;
            this.productosSeleccionados.forEach(el => {
                if (el.iva == "No") {
                    tarifa0 += Number(el.precio_descuento) * Number(el.cantidad);
                }
            });
            return +(tarifa0 + this.totalTarifa0Promo).toFixed(4);
        },
        totalTarifa0Promo() {
            let tarifa0 = 0;
            this.productosPromocion.forEach(el => {
                if (el.iva == "No") {
                    tarifa0 += Number(el.precio) * Number(el.cantidad);
                }
            });
            return +tarifa0.toFixed(4);
        },
        totalTarifa12() {
            let tarifa12 = 0;
            this.productosSeleccionados.forEach(el => {
                if (el.iva == "Si") {
                    tarifa12 += Number(el.precio_descuento) * Number(el.cantidad);
                }
            });
            return +(tarifa12 + this.totalTarifa12Promo).toFixed(4);
        },
        totalTarifa12Promo() {
            let tarifa12 = 0;
            this.productosPromocion.forEach(el => {
                if (el.iva == "Si") {
                    tarifa12 += Number(el.precio) * Number(el.cantidad);
                }
            });
            return +tarifa12.toFixed(4);
        },
        subtotalVenta() {
            let subt = 0;
            this.productosSeleccionados.forEach(el => {
                subt += Number(el.precio_descuento) * Number(el.cantidad);
            });
            return +(subt + this.subtotalVentaPromo).toFixed(4);
        },
        subtotalVentaPromo() {
            let subt = 0;
            this.productosPromocion.forEach(el => {
                subt += Number(el.precio) * Number(el.cantidad);
            });
            return +subt.toFixed(4);
        },
        totalIva() {
            return +(this.totalVenta - this.subtotalVenta).toFixed(4);
        },
        totalDescuento() {
            let desc = 0;
            this.productosSeleccionados.forEach(el => {
                desc += Number(el.valor_descuento);
            });
            return +desc.toFixed(4);
        },
        cantidadProductosPromoOrden() {
            let cont = 0;
            this.productosPromocion.forEach(el => {
                cont += el.cantidad;
            });
            return cont;
        },
        precioSinIvaModalCp() {
            if (!!!this.productoSeleccionado) {
                return 0;
            }
            if (this.productoSeleccionado.iva == "Si") {
                return this.calcularPrecioSinIva(this.precioIvaModalCp).toFixed(4);
            }
            return this.precioIvaModalCp;
        }
    },
    watch: {
        cantidadModalCp(val) {
            if (!this.productoSeleccionado) {
                return;
            }
            let prodt = this.productosSeleccionados.find(el => el.cod_producto == this.productoSeleccionado.cod_producto);
            let ncant = +val;
            if (!!prodt) {
                ncant += prodt.cantidad;
            }
            this.setearPrecioSegunCantidad(this.productoSeleccionado, ncant);
            this.precioIvaModalCp = this.calcularPrecioIvaItem(this.productoSeleccionado).toFixed(4);
        },
        precioIvaModalCp(val) {
            //this.calcularPrecioIvaItem(this.productoSeleccionado).toFixed(4);
            this.productoSeleccionado.precio = this.calcularPrecioSinIva(val);
        }
    },
    mounted() {
        const vm = this;
        this.obtenerIva();
        this.inicioPantallaOrdenes();
        this.buscarProductos("").then(function (data) {
            vm.productos = data;
        });
        this.obtenerCategorias();
        this.llenarTablaItems();
        $("#dialog_cantidad").dialog({
            modal: true,
            width: 300,
            maxHeight: 400,
            autoOpen: false,
            title: "CAMBIAR CANTIDAD",
        });
        $("#buscar_productos").focus();
    },
    methods: {
        inicioPantallaOrdenes() {
            const vm = this;
            $(".sidebar-toggle").click(function (e) {
                setTimeout(function () {
                    $('#lista_items').jqGrid('setGridWidth', 0);
                    $('#lista_items').jqGrid('setGridWidth', $(".panel_items_orden").width());
                }, 300)
            });

            jQuery("#lista_items").jqGrid({
                datatype: "local",
                colNames: ["Cant.", "Descripción", "Precio", "Total", ""],
                colModel: [
                    {
                        name: "cantidad",
                        width: 60,
                        align: "center",
                        formatter: function (cellvalue, options, rowObject) {
                            return /*html*/ `<div style="margin:5px; 0 5px 0;"><div style="display:block;" id="mas_producto_${options.rowId}" class="item_orden_boton"><i class="fa fa-plus" aria-hidden="true"></i></div><div style="padding-top:5px; padding-bottom:5px;" class="col_grid">${cellvalue}</div><div id="menos_producto_${options.rowId}" class="item_orden_boton" style="display:block;"><i class="fa fa-minus" aria-hidden="true"></i></div></div>`;
                        },
                        hidden: false
                    },
                    {
                        name: "descripcion",
                        classes: "col_grid",
                        formatter: function (cellvalue, options, rowObject) {
                            let prod = vm.productosSeleccionados.find(el => el.id == options.rowId);
                            let cart = "";
                            if (!!prod.caracteristicas) {
                                prod.caracteristicas.forEach(el => {
                                    cart += `<span class="label label-success">${el.nombre}</span><br>`;
                                });
                            }
                            let cprs = vm.productosSeleccionados.filter(el => el.cod_producto == rowObject.cod_producto);
                            let cant = cprs.length;
                            return `<div title='HAY ${rowObject.cantidad} "${cellvalue}" EN LA ORDEN'>${cellvalue}<br><div>${cart}</div></div>`;
                        }
                    },
                    {
                        name: "precio",
                        width: 70,
                        align: "center",
                        classes: "col_grid",
                        formatter: function (cellvalue, options, rowObject) {
                            if (Number(rowObject.descuento) > 0) {
                                return `<div><div style="background:#C2185B; color:#64DD17; border-radius:15px; font-size:13px; font-weight:bold;">-${rowObject.descuento}% <i class="fa fa-tag"></i></div><div>${cellvalue}</div></div>`;
                            }
                            return cellvalue;
                        }
                    },
                    {
                        name: "total",
                        width: 70,
                        align: "center",
                        classes: "col_grid",
                    },
                    {
                        name: "quitar",
                        width: 55,
                        align: "center",
                        classes: "col_grid",
                        formatter: function myformatter(cellvalue, options, rowObject) {
                            return /*html*/ `<div class="item_orden_quitar_boton" id="quitar_producto_${options.rowId}"><i style="font-size:2.5rem; color: red" class="fa fa-times-circle" aria-hidden="true"></i></div>`;
                        },
                    }
                ],
                height: 300,
                width: $("#lista_items").parent().width(),
                afterInsertRow: function (rowid, rowdata, rowelem) {
                    let index = vm.productosSeleccionados.findIndex(el => el.id == rowid);
                    if (index % 2) {
                        $("#" + rowid).css({ background: "#B0BEC5" });
                    }
                    $("#mas_producto_" + rowid).click(function (e) {
                        vm.onClickMasProducto(e, rowid);
                    });
                    $("#menos_producto_" + rowid).click(function (e) {
                        vm.onClickMenosProducto(e, rowid);
                    });
                    $("#quitar_producto_" + rowid).click(function (e) {
                        vm.onClickQuitarProducto(e, rowid);
                    });
                }
            });

            jQuery("#lista_promo_prods").jqGrid({
                datatype: "local",
                colNames: ["Por la compra de", "Cant.", "Prod. Promoción", "Precio", "Total", ""],
                colModel: [
                    {
                        name: "por_la_compra",
                        formatter: function myformatter(cellvalue, options, rowObject) {
                            let mainp = vm.productosSeleccionados.find(el => el.cod_producto == rowObject.id_main_prod);
                            if (!!mainp) {
                                //return `<b>(x${Math.floor(Number(mainp.cant_promo))}) - ${mainp.articulo}</b>`;
                                return `<b>${mainp.articulo}</b>`;
                            }

                            return `<b>---</b>`;
                        }
                    },
                    {
                        name: "cantidad",
                        width: 60,
                        align: "center",
                        formatter: function myformatter(cellvalue, options, rowObject) {
                            //return `<div id="cantidad_${subgrid_id}_${options.rowId}" data-cantidad="${cellvalue}">${cellvalue}</div>`;
                            return /*html*/ `<div style="margin:5px; 0 5px 0;"><div id="mas_producto_lista_promo_prods_${options.rowId}" class="item_orden_boton_sub"><i class="fa fa-plus" aria-hidden="true"></i></div><div style="padding-top:5px; padding-bottom:5px;" class="col_grid">${cellvalue}</div><div id="menos_producto_lista_promo_prods_${options.rowId}" class="item_orden_boton_sub"><i class="fa fa-minus" aria-hidden="true"></i></div></div>`;
                        }
                    },
                    {
                        name: "descripcion",
                        classes: "col_grid",
                    },
                    {
                        name: "precio",
                        width: 70,
                        align: "center",
                        classes: "col_grid",
                    },
                    {
                        name: "total",
                        width: 70,
                        align: "center",
                        classes: "col_grid",
                    },
                    {
                        name: "quitar",
                        width: 55,
                        align: "center",
                        classes: "col_grid",
                        formatter: function myformatter(cellvalue, options, rowObject) {
                            return /*html*/ `<div class="item_orden_quitar_boton" id="quitar_lista_promo_prods_${options.rowId}"><i style="font-size:2.5rem; color: red" class="fa fa-times-circle" aria-hidden="true"></i></div>`;
                        },
                    },
                ],
                afterInsertRow: function (rowid, rowdata, rowelem) {
                    let index = vm.productosPromocion.findIndex(el => el.cod_producto == rowid);
                    if (index % 2) {
                        $("#" + rowid).css({ background: "#B0BEC5" });
                    }
                    $(`#quitar_lista_promo_prods_${rowid}`).click(function (e) {
                        vm.quitarItemPromoTabla(rowid);
                    })
                    $(`#cantidad_lista_promo_prods_${rowid}`).click(function (e) {
                        //vm.cambiarCantidadPromo(this.dataset.cantidad);
                    });
                    $(`#menos_producto_lista_promo_prods_${rowid}`).click(function (e) {
                        vm.quitarItemPromo(rowid);
                    });
                    $(`#mas_producto_lista_promo_prods_${rowid}`).click(function (e) {
                        vm.addItemPromocion2(rowid);
                    });
                },
                rowNum: 20,
                sortname: 'num',
                sortorder: "asc",
                height: '100%',
                width: null,
                shrinkToFit: false,
            });

            $(window).off('resize');
            $(window).on('resize', function () {
                $('#lista_items').jqGrid('setGridWidth', 0);
                $('#lista_items').jqGrid('setGridWidth', $(".panel_items_orden").width());
            }).trigger('resize');

            $("#buscar_productos")[0].addEventListener("keypress", function (e) {
                if (e.key == "Enter") {
                    vm.buscarProductos(e.target.value, vm.categoriaSeleccionada).then(function (data) {
                        vm.productos = data;
                        if (vm.productos.length == 1) {
                            vm.productoSeleccionado = vm.productos[0];
                            $("#dialog_precio_prod").modal("toggle");
                        }

                    });
                }
            });
            $("#buscar_productos")[0].addEventListener("input", function (e) {
                vm.buscarProductos(e.target.value, vm.categoriaSeleccionada).then(function (data) {
                    vm.productos = data;
                });
            });

            $("#limpiar_busqueda").click(function (e) {
                $("#buscar_productos").val("");
                $("#buscar_productos")[0].focus();
                vm.buscarProductos("", vm.categoriaSeleccionada).then(function (data) {
                    vm.productos = data;
                });
            });
            $("#btn_anular_orden").click(function (e) {
                vm.anularOrden();
            });

            $("#dialog_precio_prod").on("shown.bs.modal", function (e) {
                let prodt = vm.productosSeleccionados.find(el => el.cod_producto == vm.productoSeleccionado.cod_producto);
                let ncant = 1;
                if (!!prodt) {
                    ncant += prodt.cantidad;
                }
                vm.setearPrecioSegunCantidad(vm.productoSeleccionado, ncant);
                vm.precioIvaModalCp = vm.calcularPrecioIvaItem(vm.productoSeleccionado).toFixed(4);
                vm.$nextTick(() => {
                    //vm.precioIvaModalCp = vm.calcularPrecioIvaItem(vm.productoSeleccionado).toFixed(4);
                    $("#cantidad_modal_po").select();
                });

            });
            $("#dialog_precio_prod").on("hidden.bs.modal", function (e) {
                $("#buscar_productos")[0].select();
            });
        },
        irPagar(tipoDoc) {
            if (this.productosSeleccionados.length == 0) {
                alertify.alert("<b>Debe añadir almenos un producto a la orden para continuar.</b>");
                return;
            }
            this.productosSeleccionados.forEach(el => {
                if (!!el.caracteristicas) {
                    el.caracteristicas = el.caracteristicas.map(el => {
                        return el.nombre;
                    });
                }
            });

            //añadir atributos necesarios en productos promocion para guardar orden;
            this.productosPromocion = this.productosPromocion.map(el => {
                el.descuento = 0;
                el.total_con_descuentos = el.cantidad * el.precio;
                return el;
            });
            /////////////

            this.$emit("irPagar", {
                productos: [...this.productosSeleccionados, ...this.productosPromocion],
                totalVenta: this.totalVenta,
                totalTarifa0: this.totalTarifa0,
                totalTarifa12: this.totalTarifa12,
                totalIva: this.totalIva,
                totalDescuento: this.totalDescuento,
                tipoDocumento: tipoDoc,
                iva: this.iva
            });
        },
        anularOrden() {
            const vm = this;
            $("#overlay_pantalla").show();
            alertify.confirm("<b>¿Desea anular esta orden?</b>", function (e) {
                if (e) {
                    vm.productosSeleccionados = [];
                    vm.categoriaSeleccionada = 0;
                    vm.productosPromocion = [];
                    vm.productoSeleccionado = null;
                    vm.totalIva = 0;
                    vm.totalVenta = 0;
                    vm.totalTarifa0 = 0;
                    vm.totalTarifa12 = 0;
                    vm.llenarTablaItems();
                    vm.onClickCategoria(null, 0);
                } else {
                    $("#alertify-logs").empty();
                    alertify.log("Acción cancelada");
                }
                $("#overlay_pantalla").hide();
            });

        },
        llenarTablaItems(scroll = true) {
            jQuery("#lista_items").jqGrid("clearGridData");
            this.productosSeleccionados.forEach((el, i) => {
                let obj = {
                    id: el.id,
                    cantidad: el.cantidad,
                    descripcion: el.articulo,
                    precio: Number(el.precio_iva_descuento).toFixed(2),
                    total: (Number(el.cantidad) * Number(el.precio_iva_descuento)).toFixed(2),
                    cod_producto: el.cod_producto,
                    descuento: Number(el.descuento)
                };
                jQuery("#lista_items").jqGrid("addRowData", el.id, obj);
            });

            if (scroll) {
                if (this.productosSeleccionados.length > 0) {
                    this.scrollBottomList("#lista_items");
                }
            }
            /* var groupBy = function(xs, key) {
                return xs.reduce(function(rv, x) {
                  (rv[x[key]] = rv[x[key]] || []).push(x);
                  return rv;
                }, {});
              };
            let group = groupBy(this.productosSeleccionados,"cod_producto");
            console.log(group); */

        },
        calcularPrecioIvaItem(item) {
            if (item.iva == "Si") {
                return Number(item.precio) * (1 + (this.iva / 100))
            }
            return +item.precio;
        },
        calcularPrecioSinIva(precio) {
            return Number(precio) / (1 + (this.iva / 100))
        },
        async quitarItemTabla(id) {
            const vm = this;
            $("#overlay_pantalla").show();
            alertify.confirm("<b>¿Desea quitar el producto de la orden?</b>", async function (e) {
                if (e) {
                    let prod = vm.productosSeleccionados.find(el => el.id == id);
                    vm.productosSeleccionados = vm.productosSeleccionados.filter(el => el.id != id);
                    let auxlist = [...vm.productosSeleccionados];
                    vm.productosSeleccionados = [];
                    for (const el of auxlist) {
                        await vm.addItemOrden(el, vm.productosSeleccionados);
                    }
                    vm.llenarTablaItems();
                    //vm.comprobarPromocion(prod, true);

                } else {
                    $("#alertify-logs").empty();
                    alertify.log("Acción cancelada");
                }
                $("#overlay_pantalla").hide();
            });

        },
        quitarItemPromoTabla(codprod) {
            const vm = this;
            $("#overlay_pantalla").show();
            alertify.confirm("<b>¿Desea quitar el producto de la orden?</b>", function (e) {
                if (e) {
                    vm.productosPromocion = vm.productosPromocion.filter(el => {
                        return !((el.cod_producto == codprod))
                    });
                    jQuery("#lista_promo_prods").jqGrid("clearGridData");
                    vm.productosPromocion
                        .forEach((el, i) => {
                            let obj = {
                                cantidad: el.cantidad,
                                descripcion: el.articulo,
                                precio: Number(el.precio_iva).toFixed(2),
                                total: (Number(el.cantidad) * Number(el.precio_iva)).toFixed(2)
                            };
                            jQuery("#lista_promo_prods").jqGrid("addRowData", el.cod_producto, obj);
                        });
                } else {
                    $("#alertify-logs").empty();
                    alertify.log("Acción cancelada");
                }
                $("#overlay_pantalla").hide();
            });
        },
        buscarProductos(term, idcategoria = 0) {
            return $.ajax({
                url: "buscar_productos.php?",
                method: "GET",
                dataType: "json",
                data: {
                    term: term.toUpperCase(),
                    id_categoria: idcategoria
                }
            }).done(function (data) {
                return data.map(el => {
                    el.precio = el.precio_minorista;
                    el.tipo_precio = 'MINORISTA';
                    return el;
                });
            });
        },
        obtenerIva() {
            const vm = this;
            $.ajax({
                url: "obtener_iva.php",
                method: "GET",
                dataType: "json",
                success: function (data) {
                    vm.iva = Number(data);
                }
            });
        },
        obtenerCategorias() {
            const vm = this;
            $.ajax({
                url: "obtener_categorias.php",
                method: "GET",
                dataType: "json",
                success: function (data) {
                    vm.categorias = data;
                }
            });
        },
        calcularItemSeleccionado(item) {
            item.precio_iva = +this.calcularPrecioIvaItem(item).toFixed(4);
            //this.calcularValorDescuentoProducto(item);
            item.descuento = 0;
            item.valor_descuento = 0;
            item.precio_descuento = +item.precio;
            item.precio_iva_descuento = +item.precio_iva;
            item.total_con_descuentos = item.cantidad * item.precio_descuento;
        },
        addItem(item) {
            item.id = (new Date()).getTime();
            /*  //item.cantidad = 1;
             item.precio_iva = +this.calcularPrecioIvaItem(item).toFixed(4);
             //this.calcularValorDescuentoProducto(item);
             item.descuento = 0;
             item.valor_descuento = 0;
             item.precio_descuento = +item.precio;
             item.precio_iva_descuento = +item.precio_iva;
             item.total_con_descuentos = item.cantidad * item.precio_descuento; */
            this.calcularItemSeleccionado(item);
            this.productosSeleccionados.push(item);

            //this.comprobarPromocion(item);
            /*  this.llenarTablaItems(); */
        },
        acumularItem(item) {
            let prod = this.productosSeleccionados.find(el => el.id == item.id);
            if (!!prod) {
                prod.cantidad += this.cantidadModalCp;
                this.calcularItemSeleccionado(prod)
            }
        },
        addItemPromocion2(codprod) {
            const vm = this;
            let prod = this.productosPromocion.find(el => {
                return ((el.cod_producto == codprod))
            });
            if (!!prod) {
                /* let mainprod = this.productosSeleccionados.find(el => el.cod_producto == codprodmain);
                let aux = Math.floor(Number(mainprod.cantidad) / mainprod.cant_promo);
                this.obtnerPromocionProd(codprodmain).then(function (data) {
                    let promoprod = data.find(el => el.cod_productos_promo == codprod);
                    let maxcant = (aux * promoprod.cantidad_promocion); */
                if (/* prod.cantidad < maxcant */true) {
                    prod.cantidad += 1;
                } else {
                    $("#alertify-logs").empty();
                    alertify.error("Máximo número de items alcanzado para esta promoción");
                }
                jQuery("#lista_promo_prods").jqGrid("clearGridData");
                vm.productosPromocion
                    .forEach((el, i) => {
                        let obj = {
                            cantidad: el.cantidad,
                            descripcion: el.articulo,
                            precio: Number(el.precio_iva).toFixed(2),
                            total: (Number(el.cantidad) * Number(el.precio_iva)).toFixed(2)
                        };
                        jQuery("#lista_promo_prods").jqGrid("addRowData", el.cod_producto, obj);
                    });
                /* }); */
            }
        },
        quitarItem(item) {
            const vm = this;
            let prod = this.productosSeleccionados.find(el => el.id == item.id);
            if (!!prod) {
                let ncantidad = prod.cantidad - 1
                if (ncantidad > 0) {
                    prod.cantidad -= 1;
                    this.setearPrecioSegunCantidad(prod, prod.cantidad)
                    this.calcularItemSeleccionado(prod)
                    //this.calcularValorDescuentoProducto(item);
                } else {
                    this.quitarItemTabla(item.id);
                }
                //this.comprobarPromocion(item);
            }
            this.llenarTablaItems(false);
        },
        quitarItemPromo(codprod) {
            const vm = this;
            //let prod = this.productosSeleccionados.find(el => el.cod_producto == item.cod_producto);
            let prod = this.productosPromocion.find(el => {
                return ((el.cod_producto == codprod))
            });
            if (!!prod) {
                let ncantidad = prod.cantidad - 1
                if (ncantidad > 0) {
                    prod.cantidad -= 1;
                } else {
                    this.quitarItemPromoTabla(codprod);
                }
                jQuery("#lista_promo_prods").jqGrid("clearGridData");
                vm.productosPromocion
                    .forEach((el, i) => {
                        let obj = {
                            cantidad: el.cantidad,
                            descripcion: el.articulo,
                            precio: Number(el.precio_iva).toFixed(2),
                            total: (Number(el.cantidad) * Number(el.precio_iva)).toFixed(2)
                        };
                        jQuery("#lista_promo_prods").jqGrid("addRowData", el.cod_producto, obj);
                    });
            }
        },
        onClickItem(e, item) {
            if (!this.verificarStock(item.inventariable, item.stock, item.cod_producto)) {
                $("#alertify-logs").empty();
                alertify.error("El producto no tiene stock");
                return;
            }
            this.selectItem({ ...item });
        },
        onClickCategoria(event, idcategoria) {
            const vm = this;
            this.categoriaSeleccionada = idcategoria;
            this.buscarProductos($("#buscar_productos").val(), this.categoriaSeleccionada).then(function (data) {
                vm.productos = data;
            });
        },
        async onClickMasProducto(e, id) {
            let item = this.productosSeleccionados.find(el => el.id == id);
            if (!this.verificarStock(item.inventariable, item.stock, item.cod_producto)) {
                $("#alertify-logs").empty();
                alertify.error("El producto no tiene stock");
                return;
            }
            item.cantidad += 1;
            this.setearPrecioSegunCantidad(item, item.cantidad)
            this.calcularItemSeleccionado(item)
            this.llenarTablaItems(false);
            //await this.addItemOrden({ ...item }, [...this.productosSeleccionados]);
            $("#lista_items").jqGrid('setSelection', id);
        },
        onClickMenosProducto(e, id) {
            this.quitarItem(this.productosSeleccionados.find(el => el.id == id));
            $("#lista_items").jqGrid('setSelection', id);
        },
        onClickQuitarProducto(e, id) {
            this.quitarItemTabla(id);
        },
        verificarStock(inventariable, stock, codprod = null) {
            if (inventariable == 'Si') {
                if (stock <= 0) {
                    return false
                }
                if (!!codprod) {
                    let prod = this.productosSeleccionados.find(el => el.cod_producto == codprod);
                    if (!!prod) {
                        if (prod.cantidad >= stock) {
                            return false;
                        }
                    }
                }
            }
            return true;
        },
        cambiarCantidadPromo(maxcant) {
            $("#dialog_cantidad").dialog('open');
            let cant = document.getElementById("po_diag_cantidad");
            cant.value = maxcant;
            cant.max = maxcant;
            cant.min = 0;
            $("#po_diag_aceptar").off("click");
            $("#po_diag_aceptar").click(function (e) {
            });
        },
        async selectItem(item) {
            this.productoSeleccionado = item;
            $("#dialog_precio_prod").modal("toggle");
        },
        async addItemOrden(item, listaitems) {
            item.cantidad = this.cantidadModalCp;
            let prod = null;
            if (this.productoSeleccionado != null) {
                prod = this.productosSeleccionados.find(el => el.cod_producto == this.productoSeleccionado.cod_producto);
            }

            if (!prod) {
                this.addItem({ ...item });
            } else {
                prod.precio = this.productoSeleccionado.precio;
                this.acumularItem(prod);
            }
            this.productoSeleccionado = null;
            this.cantidadModalCp = 1;
        },
        addCaracteristicasProducto() {
            if (this.caracteristicasSelect.length > 0) {
                this.productoSeleccionado["caracteristicas"] = this.caracteristicasSelect;
                this.caracteristicas = [];
                this.caracteristicasSelect = [];
            }
            $("#dialog_caract_prod").modal("toggle");
            this.addItem({ ...this.productoSeleccionado });
            this.calcualrTotalConDescuentoSelectProds();
            this.llenarTablaItems();

        },
        selectCaracteristica(idcar) {
            let car = this.caracteristicas.find(el => el.id_caracteristica == idcar);
            if (!!car) {
                this.caracteristicasSelect.push(car);
            }
        },
        quitarCaracteristicaSelect(idcar) {
            this.caracteristicasSelect =
                this.caracteristicasSelect.filter(el => el.id_caracteristica != idcar);
        },
        onChangeCaracteristica(e, idcar) {
            if (e.target.checked) {
                this.selectCaracteristica(idcar);
            } else {
                this.quitarCaracteristicaSelect(idcar);
            }
        },
        llenarTablaProdPromociones() {
            $("#dialog_prmo_prod").modal("toggle");
            jQuery("#lista_promo_prods").jqGrid("clearGridData");
            this.productosPromocion
                .forEach((el, i) => {
                    let obj = {
                        cantidad: el.cantidad,
                        descripcion: el.articulo,
                        precio: Number(el.precio_iva).toFixed(2),
                        total: (Number(el.cantidad) * Number(el.precio_iva)).toFixed(2),
                        id_main_prod: el.id_main_prod
                    };
                    jQuery("#lista_promo_prods").jqGrid("addRowData", el.cod_producto, obj);
                });
        },
        async onDialogPrecioAceptar() {
            let form = document.getElementById("form_modal_po");
            if (!form.reportValidity()) {
                return;
            }
            await this.addItemOrden(this.productoSeleccionado, [...this.productosSeleccionados]);

            this.llenarTablaItems();

            $("#dialog_precio_prod").modal("toggle");
        },
        scrollBottomList(targetGrid) {
            function getGridRowHeight(targetGrid) {
                var height = null; // Default

                try {
                    height = jQuery(targetGrid).find('tbody').outerHeight();
                }
                catch (e) {
                    //catch and just suppress error
                }

                return height;
            }

            function scrollToRow(targetGrid) {
                var rowHeight = getGridRowHeight(targetGrid) || 23; // Default height
                jQuery(targetGrid).closest(".ui-jqgrid-bdiv").scrollTop(rowHeight);
            }

            scrollToRow(targetGrid);
        },
        onEnterCantidadModalCp() {
            document.getElementById("precio_modal_po").focus();
        },
        /*
        buscarProducto(idprod) {
            return $.ajax({
                url: "buscar_producto.php",
                method: "GET",
                dataType: "json",
                data: {
                    id_producto: idprod
                }
            });
        }, 
        calcularDescuento(precio, descuento) {
            if (descuento == 0) {
                return 0;
            }
            return Number(precio) * (Number(descuento) / 100);
        },
        calcularValorDescuentoProducto(item) {
            item.valor_descuento = this.calcularDescuento(item.precio, item.descuento) * item.cantidad;;
        }, */
        /*  obtnerPromocionProd(idprod) {
             return $.ajax({
                 url: "obtener_promo_producto.php?id_producto=" + idprod,
                 method: "GET",
                 dataType: "json",
             });
         }, */
        /* obtenerCaracteristicasProd(codprod) {
            const vm = this;
            return $.ajax({
                url: "../productos/lista_caracteristicas_producto.php",
                method: "get",
                dataType: "json",
                data: { cod_productos: codprod }
            });
        }, */
        /*  comprobarPromocion(item, quitar = false) {
             const vm = this;
                if (Number.isNaN(Number(item.cant_promo))) {
                    return;
                }
                if (Number(item.cant_promo) <= 0) {
                    return;
                }
    
             this.obtnerPromocionProd(item.cod_producto).then(function (data) {
                 let prod = vm.productosSeleccionados.filter(el => {
                     let c1 = el.cod_producto == item.cod_producto;
                     let c2 = el.id != item.id;
                     return c1 && c2;
                 });
                 let cantp = 0;
                 prod.forEach(el => {
                     cantp += Number(el.cantidad);
                 });
                 let aux = Math.floor(Number(item.cantidad + cantp) / item.cant_promo);
                 if (quitar) {
                     aux = Math.floor(Number(cantp) / item.cant_promo);
                 }
                 if (aux > 0) {
                     data.forEach(el => {
                         vm.buscarProducto(el.cod_productos_promo).then(function (data) {
                             vm.addItemPromocion(el.cod_productos, data, (aux * el.cantidad_promocion), el.pvp_promocion);
                         })
                     });
                 } else {
                     data.forEach(el => {
                         vm.productosPromocion = vm.productosPromocion.filter(elp => !((el.cod_productos_promo == elp.cod_producto) && (elp.id_main_prod == item.cod_producto)));
                     });
                 }
     
             });
         }, */
        /* addItemPromocion(idmainprod, itempromo, cantidad, precio) {
            itempromo.cantidad = cantidad;
            itempromo.precio = precio
            itempromo.id_main_prod = idmainprod;
            itempromo.precio_iva = this.calcularPrecioIvaItem(itempromo);
            let prodi = this.productosPromocion.findIndex(el => (el.cod_producto == itempromo.cod_producto) && (el.id_main_prod == idmainprod));
            if (prodi == -1) {
                this.productosPromocion.push(itempromo);
            } else {
                this.productosPromocion[prodi] = itempromo;
            }
        }, */
        /* tienePromocion(codprod) {
            return this.productosPromocion.some(el => el.id_main_prod == codprod);
        }, */
        /* calcualrTotalConDescuentoSelectProds() {
            this.productosSeleccionados = this.productosSeleccionados.map(el => {
                el.total_con_descuentos = el.cantidad * el.precio_descuento;
                return el;
            });
    
        }, */
        /* calcularPrecioIva(precio) {
    return Number(precio) * (1 + (this.iva / 100))
    }, */
    }
}


