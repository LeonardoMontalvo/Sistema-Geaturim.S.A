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
            productosPromocion: [],
            caracteristicas: [],
            caracteristicasSelect: [],
            productoSeleccionado: null
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

        this.$nextTick(() => {
            console.log(this.show, this.$refs.content);
        })
        $("#dialog_cantidad").dialog({
            modal: true,
            width: 300,
            maxHeight: 400,
            autoOpen: false,
            title: "CAMBIAR CANTIDAD",
        });
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
                            return /*html*/ `<div style="margin:5px; 0 5px 0;"><div id="mas_producto_${options.rowId}" class="item_orden_boton"><i class="fa fa-plus" aria-hidden="true"></i></div><div style="padding-top:5px; padding-bottom:5px;" class="col_grid">${cellvalue}</div><div id="menos_producto_${options.rowId}" class="item_orden_boton"><i class="fa fa-minus" aria-hidden="true"></i></div></div>`;
                        }
                    },
                    {
                        name: "descripcion",
                        classes: "col_grid",
                        formatter: function (cellvalue, options, rowObject) {
                            let prod = vm.productosSeleccionados.find(el => el.id == options.rowId);
                            let cart = "";
                            if (!!prod.caracteristicas) {
                                prod.caracteristicas.forEach(el => {
                                    cart += `<span class="label label-danger">${el.nombre}</span><br>`;
                                });
                            }
                            return `${cellvalue}<br><div>${cart}</div>`;
                        }
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
                    }
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
                },
                subGrid: true,
                subGridRowExpanded: function (subgrid_id, row_id) {
                    var subgrid_table_id, pager_id;
                    subgrid_table_id = subgrid_id + "_t";
                    pager_id = "p_" + subgrid_table_id;
                    $("#" + subgrid_id).html("<table id='" + subgrid_table_id + "' class='scroll'></table><!--<div id='" + pager_id + "' class='scroll'></div>-->");
                    jQuery("#" + subgrid_table_id).jqGrid({
                        datatype: "local",
                        colNames: ["Cant.", "Descripción", "Precio", "Total", ""],
                        colModel: [
                            {
                                name: "cantidad",
                                width: 60,
                                align: "center",
                                formatter: function myformatter(cellvalue, options, rowObject) {
                                    //return `<div id="cantidad_${subgrid_id}_${options.rowId}" data-cantidad="${cellvalue}">${cellvalue}</div>`;
                                    return /*html*/ `<div style="margin:5px; 0 5px 0;"><div id="mas_producto_${subgrid_id}_${options.rowId}" class="item_orden_boton_sub"><i class="fa fa-plus" aria-hidden="true"></i></div><div style="padding-top:5px; padding-bottom:5px;" class="col_grid">${cellvalue}</div><div id="menos_producto_${subgrid_id}_${options.rowId}" class="item_orden_boton_sub"><i class="fa fa-minus" aria-hidden="true"></i></div></div>`;
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
                                    return /*html*/ `<div class="item_orden_quitar_boton" id="quitar_${subgrid_id}_${options.rowId}"><i style="font-size:2.5rem; color: red" class="fa fa-times-circle" aria-hidden="true"></i></div>`;
                                },
                            },
                        ],
                        afterInsertRow: function (rowid, rowdata, rowelem) {
                            $(`#quitar_${subgrid_id}_${rowid}`).click(function (e) {
                                vm.quitarItemPromoTabla(row_id, rowid, subgrid_table_id);
                            })
                            $(`#cantidad_${subgrid_id}_${rowid}`).click(function (e) {
                                vm.cambiarCantidadPromo(this.dataset.cantidad);
                            });
                            $(`#menos_producto_${subgrid_id}_${rowid}`).click(function (e) {
                                vm.quitarItemPromo(row_id, rowid, subgrid_table_id);
                            });
                            $(`#mas_producto_${subgrid_id}_${rowid}`).click(function (e) {
                                vm.addItemPromocion2(row_id, rowid, subgrid_table_id);
                            });
                        },
                        rowNum: 20,
                        pager: pager_id,
                        sortname: 'num',
                        sortorder: "asc",
                        height: '100%',
                        toolbar: [true, "top"]
                    });

                    $("#t_" + subgrid_table_id).append(`<div style="width:100%; text-align:center; background:#1E88E5; color:white; padding:2px;">PRODUCTOS DE PROMOCIÓN</div>`);
                    //jQuery("#" + subgrid_table_id).jqGrid('navGrid', "#" + pager_id, { edit: false, add: false, del: false })

                    vm.productosPromocion
                        .filter(el => el.id_main_prod == row_id)
                        .forEach((el, i) => {
                            let obj = {
                                cantidad: el.cantidad,
                                descripcion: el.articulo,
                                precio: Number(el.precio_iva).toFixed(2),
                                total: (Number(el.cantidad) * Number(el.precio_iva)).toFixed(2)
                            };
                            jQuery("#" + subgrid_table_id).jqGrid("addRowData", el.cod_producto, obj);
                        });
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
                $("#buscar_productos")[0].focus();
                vm.buscarProductos("", vm.categoriaSeleccionada).then(function (data) {
                    vm.productos = data;
                });
            });
            $("#btn_anular_orden").click(function (e) {
                vm.anularOrden();
            });
        },
        initDialogCaracteristicas() {
            const vm = this;

            //destruir y volver a construir diálogo
            //para refresar contenido
            if ($("#dialog_caract_prod").hasClass('ui-dialog-content')) {
                // $("#dialog_caract_prod").dialog("destroy");
            }
            $("#dialog_caract_prod").dialog({
                modal: true,
                width: 400,
                height: 450,
                autoOpen: false,
                title: "PRODUCTO",
                close: function () {
                    vm.caracteristicas = [];
                    vm.caracteristicasSelect = [];
                }
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
            this.$emit("irPagar", {
                productos: [...this.productosSeleccionados, ...this.productosPromocion],
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
                    id: el.id,
                    cantidad: el.cantidad,
                    descripcion: el.articulo,
                    precio: Number(el.precio_iva).toFixed(2),
                    total: (Number(el.cantidad) * Number(el.precio_iva)).toFixed(2),
                    cod_producto: el.cod_producto
                };
                jQuery("#lista_items").jqGrid("addRowData", el.id, obj);
            });

        },
        calcularPrecioIva(precio) {
            return Number(precio) * (1 + (this.iva / 100))
        },
        quitarItemTabla(id) {
            const vm = this;
            $("#overlay_pantalla").show();
            alertify.confirm("<b>¿Desea quitar el producto de la orden?</b>", function (e) {
                if (e) {
                    vm.productosSeleccionados = vm.productosSeleccionados.filter(el => el.id != id)
                    vm.llenarTablaItems();
                } else {
                    $("#alertify-logs").empty();
                    alertify.log("Acción cancelada");
                }
                $("#overlay_pantalla").hide();
            });

        },
        quitarItemPromoTabla(codprodmain, codprod, subgrid_table_id) {
            const vm = this;
            $("#overlay_pantalla").show();
            alertify.confirm("<b>¿Desea quitar el producto de la orden?</b>", function (e) {
                if (e) {
                    vm.productosPromocion = vm.productosPromocion.filter(el => {
                        return !((el.cod_producto == codprod) && (el.id_main_prod == codprodmain))
                    });
                    jQuery("#" + subgrid_table_id).jqGrid("clearGridData");
                    vm.productosPromocion
                        .filter(el => el.id_main_prod == codprodmain)
                        .forEach((el, i) => {
                            let obj = {
                                cantidad: el.cantidad,
                                descripcion: el.articulo,
                                precio: Number(el.precio_iva).toFixed(2),
                                total: (Number(el.cantidad) * Number(el.precio_iva)).toFixed(2)
                            };
                            jQuery("#" + subgrid_table_id).jqGrid("addRowData", el.cod_producto, obj);
                        });
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
        obtenerCaracteristicasProd(codprod) {
            const vm = this;
            return $.ajax({
                url: "../productos/lista_caracteristicas_producto.php",
                method: "get",
                dataType: "json",
                data: { cod_productos: codprod }
            });
        },
        addItem(item) {
            item.id = (new Date()).getTime();
            item.cantidad = 1;
            if (item.iva == "Si") {
                item.precio_iva = this.calcularPrecioIva(item.precio);
            } else {
                item.precio_iva = item.precio;
            }
            this.productosSeleccionados.push(item);

            this.comprobarPromocion(item);
            this.llenarTablaItems();
            this.productoSeleccionado = null;

            console.log(this.productosSeleccionados);
        },
        acumularItem(item) {
            let prod = this.productosSeleccionados.find(el => el.id == item.id);
            if (!!prod) {
                prod.cantidad += 1;
            }
            this.comprobarPromocion(item);
            this.llenarTablaItems();
        },
        comprobarPromocion(item) {
            const vm = this;
            this.obtnerPromocionProd(item.cod_producto).then(function (data) {
                let aux = Math.floor(Number(item.cantidad) / item.cant_promo);
                if (aux > 0) {
                    data.forEach(el => {
                        vm.buscarProducto(el.cod_productos_promo).then(function (data) {
                            vm.addItemPromocion(el.cod_productos, data, (aux * el.cantidad_promocion), el.pvp_promocion);
                        })
                    });
                } else {
                    data.forEach(el => {
                        vm.productosPromocion = vm.productosPromocion.filter(elp => (elp.cod_productos_promo == el.cod_producto) && (elp.id_main_prod == el.cod_producto));
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
            let prodi = this.productosPromocion.findIndex(el => (el.cod_producto == itempromo.cod_producto) && (el.id_main_prod == idmainprod));
            if (prodi == -1) {
                this.productosPromocion.push(itempromo);
            } else {
                this.productosPromocion[prodi] = itempromo;
            }
        },
        addItemPromocion2(codprodmain, codprod, subgrid_table_id) {
            const vm = this;
            let prod = this.productosPromocion.find(el => {
                return ((el.cod_producto == codprod) && (el.id_main_prod == codprodmain))
            });
            if (!!prod) {
                let mainprod = this.productosSeleccionados.find(el => el.cod_producto == codprodmain);
                let aux = Math.floor(Number(mainprod.cantidad) / mainprod.cant_promo);
                this.obtnerPromocionProd(codprodmain).then(function (data) {
                    let promoprod = data.find(el => el.cod_productos_promo == codprod);
                    let maxcant = (aux * promoprod.cantidad_promocion);
                    if (prod.cantidad < maxcant) {
                        prod.cantidad += 1;
                    } else {
                        $("#alertify-logs").empty();
                        alertify.error("Máximo número de items alcanzado para esta promoción");
                    }
                    jQuery("#" + subgrid_table_id).jqGrid("clearGridData");
                    vm.productosPromocion
                        .filter(el => el.id_main_prod == codprodmain)
                        .forEach((el, i) => {
                            let obj = {
                                cantidad: el.cantidad,
                                descripcion: el.articulo,
                                precio: Number(el.precio_iva).toFixed(2),
                                total: (Number(el.cantidad) * Number(el.precio_iva)).toFixed(2)
                            };
                            jQuery("#" + subgrid_table_id).jqGrid("addRowData", el.cod_producto, obj);
                        });
                });
            }
        },
        quitarItem(item) {
            const vm = this;
            let prod = this.productosSeleccionados.find(el => el.id == item.id);
            if (!!prod) {
                let ncantidad = prod.cantidad - 1
                if (ncantidad > 0) {
                    prod.cantidad -= 1;
                } else {
                    this.quitarItemTabla(item.id);
                }
                this.comprobarPromocion(item);
            }
            this.llenarTablaItems();
        },
        quitarItemPromo(codprodmain, codprod, subgrid_table_id) {
            const vm = this;
            //let prod = this.productosSeleccionados.find(el => el.cod_producto == item.cod_producto);
            let prod = this.productosPromocion.find(el => {
                return ((el.cod_producto == codprod) && (el.id_main_prod == codprodmain))
            });
            if (!!prod) {
                let ncantidad = prod.cantidad - 1
                if (ncantidad > 0) {
                    prod.cantidad -= 1;
                } else {
                    this.quitarItemPromoTabla(codprodmain, codprod, subgrid_table_id);
                }
                jQuery("#" + subgrid_table_id).jqGrid("clearGridData");
                vm.productosPromocion
                    .filter(el => el.id_main_prod == codprodmain)
                    .forEach((el, i) => {
                        let obj = {
                            cantidad: el.cantidad,
                            descripcion: el.articulo,
                            precio: Number(el.precio_iva).toFixed(2),
                            total: (Number(el.cantidad) * Number(el.precio_iva)).toFixed(2)
                        };
                        jQuery("#" + subgrid_table_id).jqGrid("addRowData", el.cod_producto, obj);
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
        onClickMasProducto(e, id) {
            let item = this.productosSeleccionados.find(el => el.id == id);
            if (!this.verificarStock(item.inventariable, item.stock, item.cod_producto)) {
                $("#alertify-logs").empty();
                alertify.error("El producto no tiene stock");
                return;
            }
            this.acumularItem(item);
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
                console.log(maxcant);
            });
        },
        tienePromocion(codprod) {
            return this.productosPromocion.some(el => el.id_main_prod == codprod);
        },
        selectItem(item) {

            /* this.initDialogCaracteristicas(); */

            this.productoSeleccionado = item;
            this.obtenerCaracteristicasProd(item.cod_producto)
                .then((data) => {
                    if (data.length > 0) {
                        this.caracteristicas = data;
                        /* $("#dialog_caract_prod").dialog(
                            {
                                title: item.articulo
                            }
                        );
                        $("#dialog_caract_prod").dialog("open"); */
                        $("#dialog_caract_prod").modal("toggle");
                    } else {
                        let prod = this.productosSeleccionados.find(el => {
                            let a = el.cod_producto == item.cod_producto;
                            let b = !!!el.caracteristicas;
                            return a && b;
                        });
                        if (!!!prod) {
                            this.addItem({ ...item });
                        } else {
                            this.acumularItem(prod);
                        }
                    }
                });

        },
        addCaracteristicasProducto() {
            this.productoSeleccionado["caracteristicas"] = this.caracteristicasSelect;
            $("#dialog_caract_prod").modal("toggle");
            this.caracteristicas = [];
            this.caracteristicasSelect = [];
            this.addItem({ ...this.productoSeleccionado });
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
        }
    }
}