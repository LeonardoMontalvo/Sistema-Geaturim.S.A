export default {
    template: `#pantalla_orden`,
    emits: ["irPagar"],
    data() {
        return {
            iva: 12,
            productos: [],
            categorias: [],
            categoriaSeleccionada: 0,
            productosSeleccionados: [],
            prudctosPromocion: []
        }
    },
    computed: {
        totalVenta() {
            let total = 0;
            this.productosSeleccionados.forEach(el => {
                total += Number(el.precio_iva) * Number(el.cantidad);
            });
            total = Number(total.toFixed(2));
            return total;
        },
        totalTarifa0() {
            let tarifa0 = 0;
            this.productosSeleccionados.forEach(el => {
                if (el.iva == "No") {
                    tarifa0 += Number(el.precio) * Number(el.cantidad);
                }
            });
            return tarifa0;
        },
        totalTarifa12() {
            let tarifa12 = 0;
            this.productosSeleccionados.forEach(el => {
                if (el.iva == "Si") {
                    tarifa12 += Number(el.precio) * Number(el.cantidad);
                }
            });
            return tarifa12;
        },
        subtotalVenta() {
            let subt = 0;
            this.productosSeleccionados.forEach(el => {
                subt += Number(el.precio) * Number(el.cantidad);
            });
            return subt;
        },
        totalIva() {
            return Number((this.totalVenta - this.subtotalVenta));
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
                        formatter: function myformatter(cellvalue, options, rowObject) {
                            return /*html*/ `<div style="margin:5px; 0 5px 0;"><div id="mas_producto_${options.rowId}" class="item_orden_boton"><i class="fa fa-plus" aria-hidden="true"></i></div><div style="padding-top:5px; padding-bottom:5px;" class="col_grid">${cellvalue}</div><div id="menos_producto_${options.rowId}" class="item_orden_boton"><i class="fa fa-minus" aria-hidden="true"></i></div></div>`;
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
                            return /*html*/ `<div class="item_orden_quitar_boton" id="quitar_producto_${options.rowId}"><i style="font-size:2.5rem; color: red" class="fa fa-times-circle" aria-hidden="true"></i></div>`;
                        },
                    },
                ],
                height: 300,
                width: $("#lista_items").parent().width(),
                afterInsertRow: function (rowid, rowdata, rowelem) {
                    $("#mas_producto_" + rowid).click(function (e) {
                        vm.onClickMasProducto(e, rowid);
                    });
                    $("#menos_producto_" + rowid).click(function (e) {
                        vm.onClickMenosProducto(e, rowid);
                    });
                    $("#quitar_producto_" + rowid).click(function (e) {
                        vm.onClickQuitarProducto(e, rowid);
                    });
                    /*  const ids = jQuery("#lista_items").jqGrid('getDataIDs');
                     ids.forEach(el => {
                         $("#mas_producto_" + el).off("click");
                         $("#menos_producto_" + el).off("click");
                         $("#quitar_producto_" + el).off("click");
                         $("#mas_producto_" + el).click(function (e) {
                             vm.onClickMasProducto(e, el);
                         });
                         $("#menos_producto_" + el).click(function (e) {
                             vm.onClickMenosProducto(e, el);
                         });
                         $("#quitar_producto_" + el).click(function (e) {
                             vm.onClickQuitarProducto(e, el);
                         });
                     }); */
                },
                subGrid: true,
                subGridRowExpanded: function (subgrid_id, row_id) {
                    var subgrid_table_id, pager_id;
                    subgrid_table_id = subgrid_id + "_t";
                    pager_id = "p_" + subgrid_table_id;
                    $("#" + subgrid_id).html("<table id='" + subgrid_table_id + "' class='scroll'></table><div id='" + pager_id + "' class='scroll'></div>");
                    jQuery("#" + subgrid_table_id).jqGrid({
                        datatype: "local",
                        colNames: ["Cant.", "Descripción", "Precio", "Total", ""],
                        colModel: [
                            {
                                name: "cantidad",
                                width: 60,
                                align: "center",
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
                                    return /*html*/ `<div class="item_orden_quitar_boton" id="quitar_producto_${options.rowId}"><i style="font-size:2.5rem; color: red" class="fa fa-times-circle" aria-hidden="true"></i></div>`;
                                },
                            },
                        ],
                        rowNum: 20,
                        pager: pager_id,
                        sortname: 'num',
                        sortorder: "asc",
                        height: '100%'
                    });
                    jQuery("#" + subgrid_table_id).jqGrid('navGrid', "#" + pager_id, { edit: false, add: false, del: false })
                }
            });

            $(window).off('resize');
            $(window).on('resize', function () {
                $('#lista_items').jqGrid('setGridWidth', 0);
                $('#lista_items').jqGrid('setGridWidth', $(".panel_items_orden").width());
            }).trigger('resize');

            $("#buscar_productos")[0].addEventListener("input", function (e) {
                vm.buscarProductos(e.target.value, vm.categoriaSeleccionada).then(function (data) {
                    vm.productos = data;
                });
            });
            $("#limpiar_busqueda").click(function (e) {
                $("#buscar_productos").val("");
                vm.buscarProductos("", vm.categoriaSeleccionada).then(function (data) {
                    vm.productos = data;
                });
            });
            /*   $("#btn_pagar_orden").click(function (e) {
                  
                  vm.irPagar();
              }); */
            $("#btn_anular_orden").click(function (e) {
                vm.anularOrden();
            });
        },
        irPagar(tipoDoc) {
            if (this.productosSeleccionados.length == 0) {
                alertify.alert("<b>Debe añadir almenos un producto a la orden para continuar.</b>");
                return;
            }
            $("#pago").show();
            $("#ordenes").hide();
            this.$emit("irPagar", {
                productos: this.productosSeleccionados,
                totalVenta: this.totalVenta,
                totalTarifa0: this.totalTarifa0,
                totalTarifa12: this.totalTarifa12,
                totalIva: this.totalIva,
                tipoDocumento: tipoDoc
            });
        },
        anularOrden() {
            const vm = this;
            $("#overlay_pantalla").show();
            alertify.confirm("<b>¿Desea anular esta orden?</b>", function (e) {
                if (e) {
                    vm.productosSeleccionados = [];
                    vm.categoriaSeleccionada = 0;
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
        llenarTablaItems() {
            jQuery("#lista_items").jqGrid("clearGridData");
            this.productosSeleccionados.forEach((el, i) => {
                let obj = {
                    cantidad: el.cantidad,
                    descripcion: el.articulo,
                    precio: Number(el.precio_iva).toFixed(2),
                    total: (Number(el.cantidad) * Number(el.precio_iva)).toFixed(2)
                };
                jQuery("#lista_items").jqGrid("addRowData", el.cod_producto, obj);
            });
        },
        calcularPrecioIva(precio) {
            return Number(precio) * (1 + (this.iva / 100))
        },
        quitarItemTabla(codprod) {
            const vm = this;
            $("#overlay_pantalla").show();
            alertify.confirm("<b>¿Desea quitar el producto de la orden?</b>", function (e) {
                if (e) {
                    vm.productosSeleccionados = vm.productosSeleccionados.filter(el => el.cod_producto != codprod)
                    vm.llenarTablaItems();
                } else {
                    $("#alertify-logs").empty();
                    alertify.log("Acción cancelada");
                }
                $("#overlay_pantalla").hide();
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
        buscarProductos(term, idcategoria = 0) {
            return $.ajax({
                url: "buscar_productos.php?",
                method: "GET",
                dataType: "json",
                data: {
                    term: term.toUpperCase(),
                    id_categoria: idcategoria
                }
            });
        },
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
        obtnerPromocionProd(idprod) {
            return $.ajax({
                url: "obtener_promo_producto.php?id_producto=" + idprod,
                method: "GET",
                dataType: "json",
            });
        },
        addItem(item) {
            let prod = this.productosSeleccionados.find(el => el.cod_producto == item.cod_producto);
            if (!!prod) {
                prod.cantidad += 1;
            } else {
                item.cantidad = 1;
                if (item.iva == "Si") {
                    item.precio_iva = this.calcularPrecioIva(item.precio);
                } else {
                    item.precio_iva = item.precio;
                }
                this.productosSeleccionados.push(item);
            }
            this.cargarPromocion(item);
            this.llenarTablaItems();
        },
        cargarPromocion(item) {
            const vm = this;
            this.obtnerPromocionProd(item.cod_producto).then(function (data) {
                let aux = Math.floor(Number(item.cantidad) / item.cant_promo);
                if (aux > 0) {
                    data.forEach(el => {
                        console.log(el.cod_productos_promo);
                        vm.buscarProducto(el.cod_productos_promo).then(function (data) {
                            vm.addItemPromocion(el.cod_productos, data, (aux * el.cantidad_promocion), el.pvp_promocion);
                        })
                    });
                }

            });
        },
        addItemPromocion(idmainprod, itempromo, cantidad, precio) {
            itempromo.cantidad = cantidad;
            itempromo.precio = precio
            itempromo.id_main_prod = idmainprod;
            if (itempromo.iva == "Si") {
                itempromo.precio_iva = this.calcularPrecioIva(itempromo.precio);
            } else {
                itempromo.precio_iva = itempromo.precio;
            }
            let prodi = this.prudctosPromocion.findIndex(el => (el.cod_producto == itempromo.cod_producto) && (itempromo.id_main_prod == idmainprod));
            if (prodi == -1) {
                this.prudctosPromocion.push(itempromo);
            } else {
                this.prudctosPromocion[prodi] = itempromo;
            }
            this.recargarTablaPromociones();
        },
        recargarTablaPromociones() {
            let narr = [];
            this.productosSeleccionados.forEach(el => {
                if (!!!el.id_main_prod) {
                    narr = [...narr, el];
                }
                this.prudctosPromocion.forEach(elp => {
                    console.log(el, "el");
                    if (el.cod_producto == elp.id_main_prod) {
                        let pprod = this.pro
                        narr = [...narr, elp];
                    }
                });
            });
            this.productosSeleccionados = narr;
            this.llenarTablaItems();
        },
        quitarItem(item) {
            const vm = this;
            let prod = this.productosSeleccionados.find(el => el.cod_producto == item.cod_producto);
            if (!!prod) {
                let ncantidad = prod.cantidad - 1
                if (ncantidad > 0) {
                    prod.cantidad -= 1;
                } else {
                    this.quitarItemTabla(item.cod_producto);
                }

                this.obtnerPromocionProd(item.cod_producto).then(function (data) {
                    let aux = Math.floor(Number(item.cantidad) / item.cant_promo);
                    if (aux > 0) {
                        data.forEach(el => {
                            console.log(el.cod_productos_promo);
                            vm.buscarProducto(el.cod_productos_promo).then(function (data) {
                                vm.addItemPromocion(el.cod_productos, data, (aux * el.cantidad_promocion), el.pvp_promocion);
                                console.log(vm.prudctosPromocion);
                            })
                        });
                    }

                });
            }
            this.llenarTablaItems();
        },
        onClickItem(e, item) {
            if (!this.verificarStock(item.inventariable, item.stock)) {
                $("#alertify-logs").empty();
                alertify.error("El producto no tiene stock");
                return;
            }
            this.addItem(item);
        },
        onClickCategoria(event, idcategoria) {
            this.categoriaSeleccionada = idcategoria;
            this.buscarProductos($("#buscar_productos").val(), this.categoriaSeleccionada).then(function (data) {
                vm.productos = data;
            });
        },
        onClickMasProducto(e, codprod) {
            this.addItem(this.productosSeleccionados.find(el => el.cod_producto == codprod));
            $("#lista_items").jqGrid('setSelection', codprod);
        },
        onClickMenosProducto(e, codprod) {
            this.quitarItem(this.productosSeleccionados.find(el => el.cod_producto == codprod));
            $("#lista_items").jqGrid('setSelection', codprod);
        },
        onClickQuitarProducto(e, codprod) {
            this.quitarItemTabla(codprod);
        },
        verificarStock(inventariable, stock) {
            if (inventariable == 'Si') {
                if (stock <= 0) {
                    return false
                }
            }
            return true;
        }
    }
}