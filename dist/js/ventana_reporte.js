$(document).on("ready", inicio);

var modal = (function () {
  var method = {},
    $overlay,
    $modal,
    $content,
    $close;
  method.center = function () {
    var top, left;
    top = Math.max($(window).height() - $modal.outerHeight(), 0) / 2;
    left = Math.max($(window).width() - $modal.outerWidth(), 0) / 2;
    $modal.css({
      top: top + $(window).scrollTop(),
      left: left + $(window).scrollLeft(),
    });
  };
  method.open = function (settings) {
    $content.empty().append(settings.content);
    $modal.css({
      width: settings.width || "auto",
      height: settings.height || "auto",
    });

    method.center();
    $(window).bind("resize.modal", method.center);
    $modal.show();
    $overlay.show();
  };

  method.close = function () {
    $modal.hide();
    $overlay.hide();
    $content.empty();
    $(window).unbind("resize.modal");
  };
  ///////////////////////
  $overlay = $('<div id="overlay"></div>');
  $modal = $('<div id="modal"></div>');
  $content = $('<div id="content"></div>');
  $close = $('<a id="close" href="">close</a>');

  $modal.hide();
  $overlay.hide();
  $modal.append($content, $close);

  $(document).ready(function () {
    $("body").append($overlay, $modal);
  });

  $close.click(function (e) {
    e.preventDefault();
    method.close();
  });

  return method;
})();

function valores_incompletos() {
  alertify.error("Ingrese valores requeridos");
}

function inicio() {
  // cambiar idioma
  $.datepicker.regional["es"] = {
    closeText: "Cerrar",
    prevText: "<Ant",
    nextText: "Sig>",
    currentText: "Hoy",
    monthNames: [
      "Enero",
      "Febrero",
      "Marzo",
      "Abril",
      "Mayo",
      "Junio",
      "Julio",
      "Agosto",
      "Septiembre",
      "Octubre",
      "Noviembre",
      "Diciembre",
    ],
    monthNamesShort: [
      "Ene",
      "Feb",
      "Mar",
      "Abr",
      "May",
      "Jun",
      "Jul",
      "Ago",
      "Sep",
      "Oct",
      "Nov",
      "Dic",
    ],
    dayNames: [
      "Domingo",
      "Lunes",
      "Martes",
      "Miércoles",
      "Jueves",
      "Viernes",
      "Sábado",
    ],
    dayNamesShort: ["Dom", "Lun", "Mar", "Mié", "Juv", "Vie", "Sáb"],
    dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
    weekHeader: "Sm",
    dateFormat: "dd/mm/yy",
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: "",
  };
  $.datepicker.setDefaults($.datepicker.regional["es"]);

  // fin
  // Productos
  $("#producto_general").on("click", ventana);
  $("#producto_general_precio").on("click", ventana_precio);
  $("#plantilla_conteo_prod").on("click", ventana_plantilla_conteo);
  $("#producto_marca_categoria").on("click", ventana_mar_cat);
  $("#producto_marca").on("click", ventana_marca);
  $("#producto_proveedor").on("click", ventana_proveedor);
  $("#producto_categoria").on("click", ventana_categoria);
  $("#producto_existencia_minima").on("click", ventana_existencia);
  $("#agrupados_proveedor").on("click", ventana_agrupados_prov);
  $("#reporte_producto_reservacion").on("click", ventana_producto_reservacion);
  $("#reporte_reservacion").on("click", ventana_reservacion_general);
  $("#reporte_factura_compra").on("click", ventana_factura_compra);
  $("#reporte_factura_venta").on("click", ventana_factura_venta);
  $("#resumenFacturas").on("click", resumen_facturas);
  $("#resumenFacturasCompras").on("click", resumen_facturas_compras);
  $("#resumenDetalleCompras").on("click", resumen_detalle_compras);
  $("#ventaGeneralClientes").on("click", venta_general_clientes);
  $("#ventaGeneralUsuarios").on("click", reporte_ventas_usuario);
  $("#resumenCNotaVenta").on("click", resumen_detalle_compras_nota);
  $("#ventaGeneral").on("click", venta_general);
  // Balance
  $("#repEstadosCuenta").on("click", estadosCuenta);
  // $("#estadosCuentaProveedores").on("click", estadosCuentaProveedores);
  // $("#estadosCuentaClientes").on("click", estadosCuentaClientes);
  // $("#repEstadoPG").on("click", estado_pg);
  $("#repBalComprobacion").on("click", balance_comprobacion);
  $("#repEstadoPatr1").on("click", estado_patrimonio);
  $("#repBalResultados").on("click", balance_resultados);
  $("#repBalGeneral").on("click", balance_general);
  $("#repCuentaContable").on("click", ventana_cuenta_contable);
  $("#resumenVentaProductos").on("click", reporte_ventas_producto);
  $("#repLibroDiario").on("click", ventana_libro_diario);
  $("#repMayorGeneral").on("click", ventana_mayor_general);
  $("#repSaldosCuenta").on("click", ventana_saldo_cuenta);
  $("#rep_plan_cuenta").on("click", ventana_plan_cuenta);
  $("#rep_gira_prov").on("click", ventana_giras_proveedor);
  $("#rep_conci_bancaria").on("click", ventana_conciliacion_bancaria);
  //
  $("#repOrdenesGen").on("click", ordenes_general);
  $("#repOrdenesDes").on("click", ordenes_des);
  $("#repRecetasGen").on("click", recetas_general);
  // Retenciones
  $("#retenciones_tesoreria").on("click", ventana_retenciones_tesoreria);
  $("#ri_factura_compra_compras_reten").on(
    "click",
    ri_factura_compra_compras_reten
  );
  $("#ri_factura_compra_compras_reten_gastos").on("click", function (e) {
    ri_factura_compra_compras_reten_gastos(e, "ir");
  });
  $("#ri_factura_compra_compras_reten_gastos_iva").on("click", function (e) {
    ri_factura_compra_compras_reten_gastos(e, "iva");
  });
  $("#resumen_compra_venta").on("click", resumen_compra_venta);
  $("#reporte_nota_credito").on("click", reporte_nota_credito);
  $("#proformas").on("click", proformas);
  $("#reporte_dev_compras").on("click", reporte_dev_compras);
  $("#reporte_facturas_notas_anuladas").on(
    "click",
    reporte_facturas_notas_anuladas
  );
  $("#reporte_general").on("click", reporte_general);
  $("#reporte_general_notas").on("click", reporte_general_notas);
  $("#reporte_general_notas_credito").on(
    "click",
    reporte_general_notas_credito
  );
  $("#resumenDetalleVentas").on("click", resumen_detalle_ventas);
  $("#cobros_realizados").on("click", cobros_realizados);
  $("#buscar_seriehvp").on("click", cobros_realizadoshvp);
  $("#resumenCNotaVentahcp").on("click", cobros_realizadoshcp);
  $("#resumen_cxc").on("click", resumen_cxc);
  $("#reporte_cxc_activos").on("click", facturas_por_caducidad);
  $("#cobros_clientes").on("click", cobros_clientes);
  $("#facturas_cobrar_cliente").on("click", cxc_por_clientes);
  $("#pagos_realizados").on("click", pagos_realizados);
  $("#resumen_cxp").on("click", resumen_cxp);
  $("#resumen_valores_favor_clientes_nc").on("click", resumen_valor_favor_nc);
  $("#resumen_valores_favor_empresa_nc").on("click", resumen_valor_favor_nc_compras);
  $("#facturas_canceladas").on("click", facturas_canceladas);
  $("#resumenVendedorVentas").on("click", facturas_vendedor);
  $("#facturas_canceladas_proveedor").on(
    "click",
    facturas_canceladas_proveedor
  );
  $("#facturas_pagar").on("click", facturas_pagar);
  $("#facturas_pagar_proveedor").on("click", facturas_pagar_proveedor);
  $("#pagos_proveedor").on("click", pagos_proveedor);
  $("#facturas_cobrar_clientes").on("click", ventana_facturas_cobrar_clientes);
  $("#reporte_inventario").on("click", reporte_inventario);
  $("#reporte_utilidad_producto").on("click", reporte_utilidad_producto);
  $("#reporte_utilidad_factura").on("click", reporte_utilidad_factura);
  $("#reporte_utilidad_factura_general").on(
    "click",
    reporte_utilidad_factura_general
  );
  $("#orden_produccion").on("click", orden_produccion);
  $("#lista_proformas").on("click", lista_proformas);
  $("#equipos_recibidos").on("click", equipos_recibidos);
  $("#equipos_reparados").on("click", equipos_reparados);
  $("#equipos_en_reparacion").on("click", equipos_en_reparacion);
  $("#equipos_entregados").on("click", equipos_entregados);
  $("#ats").on("click", ats_ventana);
  $("#nomina_repo").on("click", reporte_nomina);
  $("#resumenClienteProductos").on("click", reporte_cliente_producto);
  $("#repIngresos").on("click", reporte_ingresos);
  $("#repEgresos").on("click", reporte_egresos);

  $("#autorizaciones_cliente").on("click", autorizaciones_cliente);
  $("#autorizaciones_cliente_fechas").on(
    "click",
    autorizaciones_cliente_fechas
  );
  $("#autorizaciones_cliente_caducidad").on(
    "click",
    autorizaciones_cliente_caducidad
  );

  $("#gastos").on("click", gastos);
  $("#gastos_general").on("click", gastos_general);
  $("#buscar_serie").on("click", buscar_serie);
  $("#gastos_internos").on("click", gastos_internos);
  $("#diario_caja").on("click", diario_caja);
  $("#diario_caja_total").on("click", diario_caja_total);
  $("#venta_clientes").on("click", venta_por_clientes);
  $("#aporte_socios").on("click", venta_por_socios);

  // FLETES
  $("#fletes_fechas").on("click", fletes_fechas);
  $("#fletes_transporte").on("click", fletes_transporte);
  $("#fletes_conductor").on("click", fletes_conductor);
  $("#fletes_cliente").on("click", fletes_cliente);

  //      $("#factura_venta").on("click",factura_venta_combo);
  $("#ordenes_produccion_fechas").on("click", ordenes_produccion_fechas);
  $("#total_director").on("click", total_director);

  $("#rf_factura_compra").on("click", rf_factura_compra);
  $("#ri_factura_compra").on("click", ri_factura_compra);
  $("#rf_factura_venta").on("click", rf_factura_venta);
  $("#ri_factura_venta").on("click", ri_factura_venta);

  $("#fc_retencion_fuente").on("click", fc_retencion_fuente);
  $("#fc_retencion_iva").on("click", fc_retencion_iva);
  $("#fv_retencion_fuente").on("click", fv_retencion_fuente);
  $("#fv_retencion_iva").on("click", fv_retencion_iva);
  $("#repPlantillaCompras").on("click", reporte_plantilla_compras);

  ///Mantenimineto///
  $("#repMante").on("click", ventana_mante);
  $("#repMantePendientes").on("click", ventana_mante_pend);
  ///Mantenimineto///
}

///Mantenimineto///
function ventana_mante_pend(e) {
  let cont = $(`<div>
    <label>Punto de Venta</label>
    <select id='sel_resu_vent_mant_p' style='width:150px;float:right'></select><br> 
    <input type='radio' name='group1' id='pdf_mante' value='Reporte Pdf' checked> 
    <label for='pdf'>Reporte Pdf</label><br> 
    <label>Mostrar:&nbsp;&nbsp;Todo <input checked type='radio' id='todo_mante' name='mostrar_mante'></label>
    <label>Por fecha <input type='radio' id='por_fecha_mante' name='mostrar_mante'></label><br>
    <div id="div_fechas_mante" style='display:none'>
    <label>Fecha Inicio</label> <input type='text' id='inicio_mante'style='float: right;'><br>
    <label>Fecha Fin</label> <input type='text' id='fin_mante' style='float: right;'><br>
    </div>
    <a id='generar' class='btn btn-success form-control' 
    class='generarReporte' 
    href='#'>Generar Reporte</a></div>`);

  let inicio = cont.find("#inicio_mante");
  let fin = cont.find("#fin_mante");
  let todo = cont.find("#todo_mante");
  let fecha = cont.find("#por_fecha_mante");
  let fechasc = cont.find("#div_fechas_mante");
  let btngenerar = cont.find("#generar");
  let selectpv = cont.find("#sel_resu_vent_mant_p");
  let datepi = {
    dateFormat: "yy-mm-dd",
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 1,
    changeMonth: true,
    changeYear: true,
    onClose: function (selectedDate) {
      fin.datepicker("option", "minDate", selectedDate);
    },
  };
  let datepf = {
    dateFormat: "yy-mm-dd",
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 1,
    changeMonth: true,
    changeYear: true,
  };

  inicio.datepicker(datepi);
  fin.datepicker(datepf);

  todo.change(function (e) {
    if (e.target.checked) {
      fechasc.css({ display: "none" });
    }
  });
  fecha.change(function (e) {
    if (e.target.checked) {
      fechasc.css({ display: "" });
    }
  });

  modal.open({
    content: cont,
  });
  selectpv.load("../factura_venta/punto_venta_combos_inactivo.php");

  $(".generarReporte").button();

  btngenerar.click(function (e) {
    if (todo[0].checked) {
      window.open(
        "../../reportes/matenimiento_registros_pendietes.php?id=" +
        selectpv.val(),
        "_blank"
      );
    } else if (fecha[0].checked) {
      window.open(
        "../../reportes/matenimiento_registros_pendietes.php?id=" +
        selectpv.val() +
        "&inicio=" +
        inicio.val() +
        "&fin=" +
        fin.val(),
        "_blank"
      );
    }
    modal.close();
    e.preventDefault();
  });

  e.preventDefault();
}

function ventana_mante(e) {
  let cont = $(`<div>
    <label>Punto de Venta</label><select id='sel_resu_fact_ventas' style='width:150px;float:right'></select><br>
    <input type='radio' name='group1' id='pdf_mante' value='Reporte Pdf' checked>
    <label for='pdf'>Reporte Pdf</label> <br> 
    <label>Mostrar:&nbsp;&nbsp;Todo <input checked type='radio' id='todo_mante' name='mostrar_mante'></label>
    <label>Cobrados <input type='radio' id='cobrados_mante' name='mostrar_mante'></label><br>
      <label>Entregados sin Factura <input type='radio' id='entregados_sin' name='mostrar_mante'></label><br>
    <label>Fecha Inicio</label> 
    <input type='text' id='inicio'style='float: right;'><br>
    <label>Fecha Fin</label> <input type='text' id='fin' style='float: right;'><br>
    <a id='generar' 
    class='btn btn-success form-control' 
    class='generarReporteVenta' 
    href='#'>Generar Reporte</a></div>`);

  let inicio = cont.find("#inicio");
  let fin = cont.find("#fin");
  let todo = cont.find("#todo_mante");
  let cobrado = cont.find("#cobrados_mante");
  let entregado = cont.find("#entregados_sin");
  let btngenerar = cont.find("#generar");
  let selectpv = cont.find("#sel_resu_fact_ventas");

  let datepi = {
    dateFormat: "yy-mm-dd",
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 1,
    changeMonth: true,
    changeYear: true,
    onClose: function (selectedDate) {
      fin.datepicker("option", "minDate", selectedDate);
    },
  };
  let datepf = {
    dateFormat: "yy-mm-dd",
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 1,
    changeMonth: true,
    changeYear: true,
  };

  inicio.datepicker(datepi);
  fin.datepicker(datepf);

  modal.open({
    content: cont,
  });
  selectpv.load("../factura_venta/punto_venta_combos_inactivo.php");
  $(".generarReporteVenta").button();

  btngenerar.click(function (e) {
    if (todo[0].checked) {
      window.open(
        "../../reportes/matenimiento_registros.php?id=" +
        selectpv.val() +
        "&inicio=" +
        inicio.val() +
        "&fin=" +
        fin.val(),
        "_blank"
      );
    } else if (cobrado[0].checked) {
      window.open(
        "../../reportes/matenimiento_registros.php?id=" +
        selectpv.val() +
        "&inicio=" +
        inicio.val() +
        "&fin=" +
        fin.val() +
        "&cobrados=1",
        "_blank"
      );
    } else {
      window.open(
        "../../reportes/matenimiento_registros.php?id=" +
        selectpv.val() +
        "&inicio=" +
        inicio.val() +
        "&fin=" +
        fin.val() +
        "&entregado=1",
        "_blank"
      );
    }
    modal.close();
    e.preventDefault();
  });

  e.preventDefault();
}
///Mantenimineto///

function Defecto(e) {
  e.preventDefault();
}
// Productos
// Reporte Lista de Precios
function ventana(e) {
  modal.open({
    content: `<label>Lista de Precios</label><br>
    <input type='radio' name='group1' id='pdf' value='Reporte Pdf' checked><label for='pdf'>Reporte en PDF</label><br>
    <input type='radio' name='group1' id='excel' value='Reporte en Excel'><label for='excel'>Reporte en Excel</label><br>
    <button type='button' class='btn btn-success form-control' id='generarReportePrecios' 
    onclick='return fn_reporte(event)'>Generar Reporte</button>`,
  });
  e.preventDefault();
}
function fn_reporte(e) {
  if ($("#excel").is(":checked")) {
    window.open("../../phpexcel/reporte_productos.php", "_blank");
  } else {
    window.open("../../reportes/reporte_productos.php", "_blank");
  }
}
// Productos General
function ventana_precio(e) {
  modal.open({
    content: `<label>Productos</label><br>
    <input type='checkbox' id='constock'><label for='pdf'>Mostrar Solo Productos con Stock mayor a 0</label><br>
    <input type='radio' name='group1' id='pdf' value='Reporte Pdf' checked> <label for='pdf'>Reporte en PDF</label><br>
    <input type='radio' name='group1' id='excel' value='Reporte en Excel'><label for='excel'>Reporte en Excel</label><br>
    <button type='button' class='btn btn-success form-control' id='generarReporte' 
    onclick='return fn_reporte_precios(event)'>Generar Reporte</button>`,
  });
  e.preventDefault();
}
function fn_reporte_precios(e) {
  let constock = !$("#constock")[0].checked ? "" : "?stock=true";
  if ($("#excel").is(":checked")) {
    window.open("../../phpexcel/reporte_productos_general.php" + constock, "_blank");
  } else {
    window.open("../../reportes/reporte_productos_general.php" + constock, "_blank");
  }
}

// Plantilla Conteo
function ventana_plantilla_conteo(e) {
  modal.open({
    content: `<label>Plantilla para Conteo de Productos</label><br>
    <input type='radio' name='group1' id='pdf' value='Reporte Pdf' checked> <label for='pdf'>Reporte en PDF</label><br>
    <input type='radio' name='group1' id='excel' value='Reporte en Excel'><label for='excel'>Reporte en Excel</label><br>
    <button type='button' class='btn btn-success form-control' id='generarReporte' 
    onclick='return fn_plantilla_conteo(event)'>Generar Reporte</button>`,
  });
  e.preventDefault();
}
function fn_plantilla_conteo(e) {
  if ($("#excel").is(":checked")) {
    window.open("../../phpexcel/reporte_plantilla_conteo_prod.php", "_blank");
  } else {
    window.open("../../reportes/reporte_plantilla_conteo_prod.php", "_blank");
  }
}
// Por Marcas Categorias
function ventana_mar_cat(e) {
  modal.open({
    content: `<label>Por Categorias y Marcas</label><br>
    <input type='radio' name='group1' id='pdf' value='Reporte Pdf' checked><label for='pdf'>Reporte en PDF</label><br>
    <input type='radio' name='group1' id='excel' value='Reporte en Excel'><label for='excel'>Reporte en Excel</label><br>
    <label>Categoría: </label><select id='sel_categoria' style='width:150px;float:right'></select><br>
    <label>Marca: </label><select id='sel_marcas' style='width:150px;float:right'></select><br>
    <button type='button' class='btn btn-success form-control' id='generarReporte_mar_cat' 
    onclick='return fn_reporte_mar_cat(event)'>Generar Reporte</button>`,
  });
  $("#sel_marcas").load("../productos/marcas_combos.php");
  $("#sel_categoria").load("../productos/categorias_combos.php");
  e.preventDefault();
}
function fn_reporte_mar_cat(e) {
  if ($("#excel").is(":checked")) {
    window.open(
      "../../phpexcel/reporte_categoria_marcas.php?marca=" +
      $("#sel_marcas").val() +
      "&categoria=" +
      $("#sel_categoria").val(),
      "_blank"
    );
  } else {
    window.open(
      "../../reportes/reporte_categoria_marcas.php?marca=" +
      $("#sel_marcas").val() +
      "&categoria=" +
      $("#sel_categoria").val(),
      "_blank"
    );
  }
}
// Por Marcas
function ventana_marca(e) {
  modal.open({
    content: `<label>Por Marcas</label><br>
    <input type='radio' name='group1' id='pdf' value='Reporte Pdf' checked><label for='pdf'>Reporte en PDF</label><br>
    <input type='radio' name='group1' id='excel' value='Reporte en Excel'><label for='excel'>Reporte en Excel</label><br>
    <label>Marca: </label><select id='sel_marcas' style='width:150px;float:right'></select><br>
    <button type='button' class='btn btn-success form-control' id='generarReporte_marca' 
    onclick='return fn_reporte_marca(event)'>Generar Reporte</button>`,
  });
  $("#sel_marcas").load("../productos/marcas_combos.php");
  e.preventDefault();
}
function fn_reporte_marca(e) {
  if ($("#excel").is(":checked")) {
    window.open(
      "../../phpexcel/reporte_marcas.php?marca=" + $("#sel_marcas").val(),
      "_blank"
    );
  } else {
    window.open(
      "../../reportes/reporteMarca.php?id=" + $("#sel_marcas").val(),
      "_blank"
    );
  }
}
// Categorias
function ventana_categoria(e) {
  modal.open({
    content: `<label>Por Categorias</label><br>
    <input type='radio' name='group1' id='pdf' value='Reporte Pdf' checked><label for='pdf'>Reporte en PDF</label><br>
    <input type='radio' name='group1' id='excel' value='Reporte en Excel'><label for='excel'>Reporte en Excel</label><br>
    <label>Categorías</label><select id='sel_categoria' style='width:150px;float:right'></select><br>
    <button type='button' class='btn btn-success form-control' id='generarReporte_categoria' 
    onclick='return fn_reporte_categoria(event)'>Generar Reporte</button>`,
  });
  $("#sel_categoria").load("../productos/categorias_combos.php");
  e.preventDefault();
}
function fn_reporte_categoria(e) {
  if ($("#excel").is(":checked")) {
    window.open(
      "../../phpexcel/reporte_categoria.php?categoria=" +
      $("#sel_categoria").val(),
      "_blank"
    );
  } else {
    window.open(
      "../../reportes/reporteCategorias.php?" +
      "&id=" +
      $("#sel_categoria").val(),
      "_blank"
    );
  }
}
// Por Proveedores
function ventana_proveedor(e) {
  modal.open({
    content: `<label>Por Proveedores</label><br>
    <input type='radio' name='group1' id='pdf' value='Reporte Pdf' checked><label for='pdf'>Reporte en PDF</label><br>
    <input type='radio' name='group1' id='excel' value='Reporte en Excel'><label for='excel'>Reporte en Excel</label><br>
    <label>Proveedor: </label><select id='sel_proveedor' style='width:150px;float:right'></select><br>
    <button type='button' class='btn btn-success form-control' id='generarReporte_proveedor' 
    onclick='return fn_reporte_proveedor(event)'>Generar Reporte</button>`,
  });
  $("#sel_proveedor").load("../productos/proveedor_combos.php");
  e.preventDefault();
}
function fn_reporte_proveedor(e) {
  if ($("#excel").is(":checked")) {
    window.open(
      "../../phpexcel/reporte_proveedor.php?proveedor=" +
      $("#sel_proveedor").val(),
      "_blank"
    );
  } else {
    window.open(
      "../../reportes/reporteProveedor.php?id=" + $("#sel_proveedor").val(),
      "_blank"
    );
  }
}
// Existencia Minima
function ventana_existencia(e) {
  modal.open({
    content: `<label>Existencia Mínima</label><br>
    <input type='radio' name='group1' id='pdf' value='Reporte Pdf' checked><label for='pdf'>Reporte en PDF</label><br>
    <input type='radio' name='group1' id='excel' value='Reporte en Excel'><label for='excel'>Reporte en Excel</label><br>
    <button type='button' class='btn btn-success form-control' id='generarReporte_existencia' 
    onclick='return fn_reporte_existencia(event)'>Generar Reporte</button>`,
  });
  e.preventDefault();
}
function fn_reporte_existencia(e) {
  if ($("#excel").is(":checked")) {
    window.open("../../phpexcel/reporte_existencia_minima.php", "_blank");
  } else {
    window.open("../../reportes/reporte_existencia_minima.php", "_blank");
  }
}
// Fin Productos
/////////////////// Inventarios
function reporte_inventario(e) {
  modal.open({
    content: `<label>Inventarios</label><br>
    <input type='radio' name='group1' id='pdf' value='Reporte Pdf' checked> <label for='pdf'>Reporte Pdf</label><br>
    <input type='radio' name='group1' id='excel' value='Reporte en Excel'/><label for='excel'>Reporte en Excel</label><br>
    <label>Fecha Inicio</label><input type='text' id='inicio'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteInventario' 
    onclick='return fn_reporte_inventario(event)'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_reporte_inventario(e) {
  if ($("#excel").is(":checked")) {
    window.open(
      "../../phpexcel/Inventario.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  } else {
    if ($("#fin").val() === "") {
      valores_incompletos();
    } else {
      window.open(
        "../../reportes/reporteInventario.php?inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val(),
        "_blank"
      );
    }
  }
}
// Compras
// Agrupados por proveedor
function ventana_agrupados_prov(e) {
  modal.open({
    content: `<label>Productos Proveedores</label><br>
    <label for='buscarProv'>Buscar</label><input type='text' name='buscarProv' id='buscarProv'/><input type='hidden' id='idProv'/><br>
    <input type='radio' name='group1' id='pdf' value='Reporte Pdf' checked> <label for='pdf'>Reporte en PDF</label><br>
    <input type='radio' name='group1' id='excel' value='Reporte en Excel' ><label for='excel'>Reporte en Excel</label><br>
    <button type='button' class='btn btn-success form-control' id='generarReporte_agrupados_prov' 
    onclick='return fn_reporte_agrupados_prov(event)'>Generar Reporte</button>`,
  });
  $("#buscarProv")
    .autocomplete({
      source: "../../procesos/buscar_proveedor_nombre.php",
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarProv").val(ui.item.value);
        $("#idProv").val(ui.item.id_proveedor);
        return false;
      },
      select: function (event, ui) {
        $("#buscarProv").val(ui.item.value);
        $("#idProv").val(ui.item.id_proveedor);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  e.preventDefault();
}
function fn_reporte_agrupados_prov(e) {
  if ($("#excel").is(":checked")) {
    window.open(
      "../../phpexcel/reporte_agrupados_prov.php?id=" + $("#idProv").val(),
      "_blank"
    );
  } else {
    if ($("#buscarProv").val() === "") {
      valores_incompletos();
    } else {
      window.open(
        "../../reportes/reporte_agrupados_prov.php?id=" + $("#idProv").val(),
        "_blank"
      );
    }
  }
}
// Devolucion Compra
function reporte_dev_compras(e) {
  modal.open({
    content: `<label>Devoluciones de Compra</label><br>
    <label for='buscar_dev_compra' style='padding:6px;'>Buscar</label>
    <input type='text' name='buscar_dev_compra' id='buscar_dev_compra' style='float: right;padding:2px;'/><input type='hidden' id='idDevCom'/><br>
    <button type='button' class='btn btn-success form-control' id='generarReporte_devCompra' 
    onclick='return fn_reporte_dev_compras(event)'>Generar Reporte</button>`,
  });
  $("#buscar_dev_compra")
    .autocomplete({
      source: "../../procesos/buscar_dev_compra.php",
      minLength: 1,
      focus: function (event, ui) {
        $("#buscar_dev_compra").val(ui.item.value);
        $("#idDevCom").val(ui.item.id_devolucion_compra);
        return false;
      },
      select: function (event, ui) {
        $("#buscar_dev_compra").val(ui.item.value);
        $("#idDevCom").val(ui.item.id_devolucion_compra);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  e.preventDefault();
}
function fn_reporte_dev_compras(e) {
  if ($("#buscar_dev_compra").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/devolucion_compra.php?id=" + $("#idDevCom").val(),
      "_blank"
    );
  }
}
// Facturas General
function resumen_facturas(e) {
  modal.open({
    content: `<label>Facturas General</label><br>
    <input type='radio' name='group1' id='pdf' value='Reporte Pdf' checked> <label for='pdf'>Reporte Pdf</label><br>
    <input type='radio' name='group1' id='excel' value='Reporte en Excel'><label for='excel'>Reporte en Excel</label><br>
    <label>Fecha Inicio</label> <input type='text' id='inicio'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteResumen' 
    onclick='return fn_resumen_facturas(event)'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_resumen_facturas(e) {
  if ($("#excel").is(":checked")) {
    window.open(
      "../../phpexcel/facturasCompras.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  } else {
    if ($("#fin").val() === "") {
      valores_incompletos();
    } else {
      window.open(
        "../../reportes/facturasCompras.php?inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val(),
        "_blank"
      );
    }
  }
}
// Facturas Agrupadas
function resumen_facturas_compras(e) {
  modal.open({
    content: `<label>Facturas Agrupadas</label><br>
      <input type='radio' name='group1' id='excel' value='Reporte en Excel'><label for='excel'>Reporte en Excel</label><br>
      <input type='radio' name='group1' id='pdf' value='Reporte Pdf' checked><label for='pdf'>Reporte en PDF</label><br>
      <label>Fecha Inicio</label> <input type='text' id='inicio'><br>
      <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
      <button type='button' class='btn btn-success form-control' id='generarReporteFacturasCompras' 
      onclick='return fn_reporte_factura_compra(event)'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_reporte_factura_compra(e) {
  if ($("#excel").is(":checked")) {
    window.open(
      "../../phpexcel/resumenFacturasCompras.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  } else {
    if ($("#fin").val() === "") {
      valores_incompletos();
    } else {
      window.open(
        "../../reportes/resumenFacturasCompras.php?inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val(),
        "_blank"
      );
    }
  }
}
// Facturas Detalladas
function resumen_detalle_compras(e) {
  modal.open({
    content: `<label>Facturas Detalladas</label><br>
    <input type='radio' name='group1' id='excel' value='Reporte en Excel'><label for='excel'>Reporte en Excel</label><br>
    <input type='radio' name='group1' id='pdf' value='Reporte Pdf' checked> <label for='pdf'>Reporte en PDF</label><br>
    <div><label>Buscar Proveedor</label><input id='proveedor_compras_g' type='text' style='width: 100%;' placeholder='RUC/CI/NOMBRE'> <input hidden id='id_proveedor_compras_g'></div>
    <label>Fecha Inicio</label> <input type='text' id='inicio'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteFacturasCompras' 
    onclick='return fn_reporte_detalle_compra(event)'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });

  $("#proveedor_compras_g")[0].addEventListener("input", function (e) {
    if (e.target.value == "") {
      $("#id_proveedor_compras_g").val("");
    }
  })
  $("#proveedor_compras_g")
    .autocomplete({
      source: function (request, response) {
        var data = { term: request.term };
        $.get(
          "../../procesos/buscar_proveedor.php",
          data,
          response,
          "json"
        );
      },
      minLength: 1,
      select: function (event, ui) {
        $("#proveedor_compras_g").val(ui.item.value);
        $("#id_proveedor_compras_g").val(ui.item["id_proveedor"]);
        return false;
      },
      focus: function (event, ui) {
        return false;
      }
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item["value"] + "</a>")
        .appendTo(ul);
    };
  e.preventDefault();
}
function fn_reporte_detalle_compra(e) {
  if ($("#excel").is(":checked")) {
    window.open(
      "../../phpexcel/facturasComprasGeneral.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val() + "&id_proveedor=" + $("#id_proveedor_compras_g").val(),
      "_blank"
    );
  } else {
    if ($("#fin").val() === "") {
      valores_incompletos();
    } else {
      window.open(
        "../../reportes/facturasComprasGeneral.php?inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val() + "&id_proveedor=" + $("#id_proveedor_compras_g").val(),
        "_blank"
      );
    }
  }
}
// Notas de Compra
function resumen_detalle_compras_nota(e) {
  modal.open({
    content: `<label>Compras por Nota</label><br>
    <label>Fecha Inicio</label><input type='text' id='inicio'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteFacturasComprasNota' 
    onclick='return fn_reporte_factura_compra_nota(event)'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_reporte_factura_compra_nota(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/facturasCompraNotaVentaGeneral.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Retencion Fuente
function fc_retencion_fuente(e) {
  modal.open({
    content: `<label>Retecion Fuente</label><br>
    <label>Ret. Fuente</label><select id='sel_rfuente' style='width:150px;float:right'></select><br>
    <label>Fecha Inicio</label> <input type='text' id='inicio' style='width:150px;float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='width:150px;float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarRet_Fuente_FCFechas' 
    onclick='return fn_fc_ret_fuente(event)'>Generar Reporte</button>`,
  });
  $("#sel_rfuente").load("../../procesos/comboRetencionFuente.php");
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_fc_ret_fuente(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/fcRetencionFuente.php?id_retencion=" +
      $("#sel_rfuente").val() +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Retencion IVA
function fc_retencion_iva(e) {
  modal.open({
    content: `<label>Retencion IVA</label><br>
    <label>Cuenta</label><select id='sel_riva' style='width:150px;float:right'></select><br>
    <label>Fecha Inicio</label><input type='text' id='inicio'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <a type='button' class='btn btn-success form-control' class='generarRet_iva_FCFechas' 
    onclick='return fn_fc_ret_iva(event)' href='#'>Generar Reporte</a>`,
  });
  $("#sel_riva").load("../../procesos/comboRetencionIVA.php");
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_fc_ret_iva(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/fcRetencionIva.php?id_retencion=" +
      $("#sel_riva").val() +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Retencion Fuente Fechas
function rf_factura_compra(e) {
  modal.open({
    content: `<label>Retenciones a la Fuente</label><br>
    <label>Fecha Inicio</label> <input type='text' id='inicio' style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarRet_Fuente_FCFechas' 
    onclick='return fn_rf_fact_compra(event)'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_rf_fact_compra(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/retencionFuentefacturasCompras.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
function ri_factura_compra(e) {
  modal.open({
    content: `<label>Retenciones IVA</label><br>
    <label>Fecha Inicio</label><input type='text' id='inicio' style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarRet_iva_FCFechas' 
    onclick='return fn_ri_fact_compra(event)'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_ri_fact_compra(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/retencionIvafacturasCompras.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Comprobantes
function ventana_factura_compra(e) {
  modal.open({
    content: `<label>Comprobante Compra</label><br>
    <label for='buscarNumero'>Factura: </label><input type='text' name='buscarNumero' id='buscarNumero'/><input type='hidden' id='idFac' /><br>
    <button type='button' class='btn btn-success form-control' id='generarReporte_factura_compra' 
    onclick='return fn_reporte_factura_compras(event)'>Generar Reporte</button>`,
  });
  $("#buscarNumero")
    .autocomplete({
      source: "../../procesos/buscar_factura_compra.php",
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarNumero").val(ui.item.value);
        $("#idFac").val(ui.item.id_factura_compra);
        return false;
      },
      select: function (event, ui) {
        $("#buscarNumero").val(ui.item.value);
        $("#idFac").val(ui.item.id_factura_compra);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  e.preventDefault();
}
function fn_reporte_factura_compras(e) {
  if ($("#buscarNumero").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/factura_compra.php?id=" + $("#idFac").val(),
      "_blank"
    );
  }
}
// Plantilla Compras
function reporte_plantilla_compras(e) {
  modal.open({
    content: `<label>Plantilla en Excel</label><br>
    <label>Fecha Inicio<font color='red'>*</font></label><input type='text' id='inicio'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarPlanillaCompra' 
    onclick='return fn_reporte_plantilla_compras(event)'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_reporte_plantilla_compras(e) {
  if ($("#inicio").val() !== "" && $("#fin").val() !== "") {
    window.open(
      "../../phpexcel/plantilla_compras_excel.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  } else {
    valores_incompletos();
  }
}
// Historial Compras
function cobros_realizadoshcp(e) {
  modal.open({
    content: `<label>Historial de Compras</label><br>
    <label>Fecha Inicio</label> <input type='text' id='inicio' style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <label for='buscarClientehcp'>Nombre Producto: </label>
    <input type='text' name='buscarClientehcp' id='buscarClientehcp'style='float: right;' />
    <input type='hidden' id='idcli' style='float: right;'/><br>
    <button type='button' class='btn btn-success form-control' id='generarReporte_cobrosRealizadoshcp' 
    onclick='return fn_cobros_realizadoshcp(event)'>Generar Reporte</button>`,
  });
  $("#buscarClientehcp")
    .autocomplete({
      source: "../../procesos/productos_combos.php",
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarClientehcp").val(ui.item.value);
        $("#idcli").val(ui.item.id_cliente);

        return false;
      },
      select: function (event, ui) {
        $("#buscarClientehcp").val(ui.item.value);
        $("#idcli").val(ui.item.id_cliente);

        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_cobros_realizadoshcp(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    if ($("#idcli").val() != "") {
      window.open(
        "../../reportes/cobros_realizadoshcp.php?id=" +
        $("#idcli").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val(),
        "_blank"
      );
    } else {
      alertify.error("Ingrese un producto");
    }
  }
}
// Ventas
// Clientes
function venta_general_clientes(e) {
  modal.open({
    content: `<label>Ventas por Cliente</label><br>
    <input type='radio' name='group1' id='excel' value='Reporte en Excel' > 
    <label for='excel'>Reporte en Excel</label> <br>
    <input type='radio' name='group1' id='pdf' value='Reporte en PDF' checked> 
    <label for='pdf'>Reporte Pdf</label> </br>
        <label style="width:40%">Documento: </label><select id="documento" style="width:60%">
    <option value="fc">Factura</option><option value="nv">Nota Venta</option></select><br>
        
        
<label>Punto de Venta</label>
    <select id='sel_resu_fact_ventas' style='width:150px;float:right'></select><br>
    <label for='buscarCli' style='padding:6px;'>Buscar Nombre Cliente</font></label>
    <input type='text' name='buscarCli' id='buscarCli' class='form-control' />
    <input type='hidden' id='idCli' /><br><label style='padding:6px;'>Fecha Inicio</label> 
    <input type='text' id='inicio'style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label> 
    <input type='text' id='fin' style='float: right;'><br>
    <button type='button'class='btn btn-success form-control' id='generarReporteVentaClientes' 
    onclick='return fn_venta_general_clientes(event)'>Generar Reporte</button>`,
  });
  $("#sel_resu_fact_ventas").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );
  $("#buscarCli")
    .autocomplete({
      source: "../../procesos/busquedaCliente.php",
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarCli").val(ui.item.value);
        $("#idCli").val(ui.item.id_cliente);
        return false;
      },
      select: function (event, ui) {
        $("#buscarCli").val(ui.item.value);
        $("#idCli").val(ui.item.id_cliente);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_venta_general_clientes(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    if ($("#documento").val() == 'fc') {
      console.log("entro si aqui2:");
      if ($("#pdf").is(":checked")) {
        window.open(
          "../../reportes/resumenFacturasVentas.php?id=" +
          $("#sel_resu_fact_ventas").val() +
          "&id1=" +
          $("#idCli").val() +
          "&inicio=" +
          $("#inicio").val() +
          "&fin=" +
          $("#fin").val(),
          "_blank"
        );
      } else {
        if ($("#inicio").val() === "") {
          alertify.error("Ingrese fecha de inicio");
        } else {
          window.open(
            "../../phpexcel/resumenFacturasVentas.php?id=" +
            $("#sel_resu_fact_ventas").val() +
            "&inicio=" +
            $("#inicio").val() +
            "&fin=" +
            $("#fin").val(),
            "_blank"
          );
        }
      }

    } else {
      if ($("#pdf").is(":checked")) {
        window.open(
          "../../reportes/resumenFacturasVentas_nv.php?id=" +
          $("#sel_resu_fact_ventas").val() +
          "&id1=" +
          $("#idCli").val() +
          "&inicio=" +
          $("#inicio").val() +
          "&fin=" +
          $("#fin").val(),
          "_blank"
        );
      } else {

        window.open(
          "../../phpexcel/resumenFacturasVentas_nv.php?id=" +
          $("#sel_resu_fact_ventas").val() +
          "&id1=" +
          $("#idCli").val() +
          "&inicio=" +
          $("#inicio").val() +
          "&fin=" +
          $("#fin").val(),
          "_blank"
        );

      }
    }


  }
}
// Usuarios
function reporte_ventas_usuario(e) {
  modal.open({
    content: `<label>Ventas por Usuario</label><br>
    <label>Usuario</label><select id='sel_usuario' style='width:150px;float:right'></select><br> 
    <label>Fecha Inicio</label><input type='text' id='inicio'style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label> 
    <input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteVentasUsuarios' 
    onclick='return fn_reporte_ventas_usuarios(event)'>Generar Reporte</button>`,
  });
  $("#sel_usuario").load("../factura_venta/usuarios_combos.php");
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_reporte_ventas_usuarios(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/general_ventas_usuarios.php?id=" +
      $("#sel_usuario").val() +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// General
function venta_general(e) {
  modal.open({
    content: `<label>Ventas General</label><br>
    <input type='radio' name='group1' id='excel' value='Reporte en Excel'><label for='excel'>Reporte en Excel</label><br>
    <input type='radio' name='group1' id='pdf' value='Reporte Pdf' checked> <label for='pdf'>Reporte en PDF</label><br>
    <label>Punto de Venta</label><select id='sel_venta_general' style='width:150px;float:right'></select><br>
    <label>Fecha Inicio</label><input type='text' id='inicio'style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteVentaGeneral' 
    onclick='return fn_venta_general(event)'>Generar Reporte</button>`,
  });
  $("#sel_venta_general").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_venta_general(e) {
  if ($("#excel").is(":checked")) {
    window.open(
      "../../phpexcel/facturasVentas.php?id=" +
      $("#sel_venta_general").val() +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  } else {
    if ($("#fin").val() === "") {
      valores_incompletos();
    } else {
      window.open(
        "../../reportes/facturasVentas.php?id=" +
        $("#sel_venta_general").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val(),
        "_blank"
      );
    }
  }
}
//////////////////////////////////////
//TOTAL///
function diario_caja_total(e) {
  modal.open({
    content: `<label>Diario de Caja</label><br>
    
    <label>Punto de Venta</label><select id='sel_punto_venta' style='width:150px;float:right'></select><br>
    <label>Fecha Inicio</label> <input type='text' id='inicio'style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarDiarioCaja' 
    onclick='return fn_diario_caja_total(event)'>Generar Reporte</button>`,
  });

  $("#sel_punto_venta").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );
  $("#inicio").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_diario_caja_total(e) {
  if ($("#excel").is(":checked")) {
    window.open(
      "../../reportes/diario_caja_total.php?id=" +
      1 +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  } else {
    if ($("#fin").val() === "") {
      valores_incompletos();
    } else {
      window.open(
        "../../reportes/diario_caja_total.php?id1=" +
        $("#sel_punto_venta").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val(),
        "_blank"
      );
    }
  }
}
// Diario de Caja
function diario_caja(e) {
  modal.open({
    content: `<label>Diario de Caja</label><br>
    <label>Usuario</label><select id='sel_usuario' style='width:150px;float:right'></select><br>
    <label>Punto de Venta</label><select id='sel_punto_venta' style='width:150px;float:right'></select><br>
    <label>Fecha Inicio</label> <input type='text' id='inicio'style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarDiarioCaja' 
    onclick='return fn_diario_caja(event)'>Generar Reporte</button>`,
  });

  $("#sel_usuario").load("../factura_venta/usuarios_combos.php");
  $("#sel_punto_venta").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );
  $("#inicio").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_diario_caja(e) {
  if ($("#excel").is(":checked")) {
    window.open(
      "../../reportes/diario_caja.php?id=" +
      1 +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  } else {
    if ($("#fin").val() === "") {
      valores_incompletos();
    } else {
      window.open(
        "../../reportes/diario_caja.php?id=" +
        $("#sel_usuario").val() +
        "&id1=" +
        $("#sel_punto_venta").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val(),
        "_blank"
      );
    }
  }
}
// Comprobante Venta
function ventana_factura_venta(e) {
  modal.open({
    content: `<label>Comprobante Venta</label><br>
    <label for='buscarNumero'>Buscar Factura: </label>
    <input type='text' name='buscarNumero' id='buscarNumero'/>
    <input type='hidden' id='idFac'/><br>
    <button type='button' class='btn btn-success form-control' id='generarReporte_factura_venta' 
    onclick='return fn_reporte_factura_venta(event)' href='#'>Generar Reporte</button>`,
  });
  $("#buscarNumero")
    .autocomplete({
      source: "../../procesos/buscar_factura_venta.php",
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarNumero").val(ui.item.value);
        $("#idFac").val(ui.item.id_factura_venta);
        return false;
      },
      select: function (event, ui) {
        $("#buscarNumero").val(ui.item.value);
        $("#idFac").val(ui.item.id_factura_venta);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  e.preventDefault();
}
function fn_reporte_factura_venta(e) {
  if ($("buscarNumero").val() === "") {
    valores_incompletos;
  } else {
    window.open(
      "../../reportes/factura_venta.php?hoja=" +
      "A4" +
      "&id=" +
      $("#idFac").val(),
      "_blank"
    );
  }
}
// Anuladas
function reporte_facturas_notas_anuladas(e) {
  modal.open({
    content: `<label>Ventas Anuladas</label><br>
    <label>Punto de Venta</label><select id='sel_facturas_anuladas' style='width:150px;float:right'></select><br>
    <label>Fecha Inicio</label> <input type='text' id='inicio'style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteFacturasNotasAnuladas' 
    onclick='return fn_reporte_facturas_notas_anuladas(event)'>Generar Reporte</button>`,
  });
  $("#sel_facturas_anuladas").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_reporte_facturas_notas_anuladas(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/resumenFacturasNotasAnuladas.php?id=" +
      $("#sel_facturas_anuladas").val() +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Notas de Credito
function reporte_nota_credito(e) {
  modal.open({
    content: `<label>Nota de Credito</label><br>
    <label for='buscarNotaCredito' style='padding:6px;'>Buscar</label>
    <input type='text' name='buscarNotaCredito' id='buscarNotaCredito' style='float: right;padding:2px;'/><input type='hidden' id='idNC'/><br>
    <button type='button' class='btn btn-success form-control' id='generarReporte_notasCredito' 
    onclick='return fn_reporte_nota_credito(event)'>Generar Reporte</button>`,
  });
  $("#buscarNotaCredito")
    .autocomplete({
      source: "../../procesos/buscar_dev_venta.php",
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarNotaCredito").val(ui.item.value);
        $("#idNC").val(ui.item.id_devolucion_venta);
        return false;
      },
      select: function (event, ui) {
        $("#buscarNotaCredito").val(ui.item.value);
        $("#idNC").val(ui.item.id_devolucion_venta);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  e.preventDefault();
}
function fn_reporte_nota_credito(e) {
  if ($("#buscarNotaCredito").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/notaCredito.php?id=" + $("#idNC").val(),
      "_blank"
    );
  }
}
// Notas de credito general
function reporte_general_notas_credito(e) {
  modal.open({
    content: `<label>General Notas de Crédito</label><br>
    <input type='radio' name='group1' id='pdf' value='Reporte pdf' checked><label for='pdf'>Reporte en PDF</label><br>
    <input type='radio' name='group1' id='excel' value='Reporte EXCEL'><label for='excel'>Reporte en Excel</label><br>
    <label>Punto de Venta</label><select id='sel_general' style='width:150px;float:right'></select><br>
    <div><label>Buscar Cliente</label><input id='cliente_notasc_g' type='text' style='width: 100%;' placeholder='RUC/CI/NOMBRE'> <input hidden id='id_cliente_notasc_g'></div>
    <label>Fecha Inicio</label> <input type='text' id='inicio'style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteGeneral' 
    onclick='return fn_reporte_general_notas_credito(event)'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  $("#sel_general").load("../factura_venta/punto_venta_combos_inactivo.php");

  $("#cliente_notasc_g")[0].addEventListener("input", function (e) {
    if (e.target.value == "") {
      $("#id_cliente_ventas_g").val("");
    }
  })

  $("#cliente_notasc_g")
    .autocomplete({
      source: function (request, response) {
        var data = { term: request.term };
        $.get(
          "../../procesos/busquedaCliente_2.php",
          data,
          response,
          "json"
        );
      },
      minLength: 1,
      select: function (event, ui) {
        $("#cliente_notasc_g").val(ui.item.value);
        $("#id_cliente_notasc_g").val(ui.item.label);
        return false;
      },
      focus: function (event, ui) {
        return false;
      }
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item["value"] + "</a>")
        .appendTo(ul);
    };

  e.preventDefault();
}
function fn_reporte_general_notas_credito(e) {
  if ($("#excel").is(":checked")) {
    window.open(
      "../../phpexcel/generalNotasCredito.php?id=" +
      $("#sel_general").val() +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val() + '&id_cliente=' + $("#id_cliente_notasc_g").val(),
      "_blank"
    );
  } else {
    if ($("#fin").val() === "") {
      valores_incompletos();
    } else {
      window.open(
        "../../reportes/general_nota_credito.php?id=" +
        $("#sel_general").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val() + '&id_cliente=' + $("#id_cliente_notasc_g").val(),
        "_blank"
      );
    }
  }
}
// General Facturas
function reporte_general(e) {
  modal.open({
    content: `<label>General Facturas</label><br>
    <input type='radio' name='group1' id='pdf' value='Reporte pdf' checked><label for='pdf'>Reporte en PDF</label><br>
    <input type='radio' name='group1' id='excel' value='Reporte EXCEL'><label for='excel'>Reporte en Excel</label><br>
    <label>Punto de Venta</label><select id='sel_general' style='width:150px;float:right'></select><br>
    <div><label>Buscar Cliente</label><input id='cliente_ventas_g' type='text' style='width: 100%;' placeholder='RUC/CI/NOMBRE'> <input hidden id='id_cliente_ventas_g'></div>
    <label>Fecha Inicio</label><input type='text' id='inicio'style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteGeneral' 
    onclick='return fn_reporte_general(event)'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  $("#sel_general").load("../factura_venta/punto_venta_combos_inactivo.php");

  $("#cliente_ventas_g")[0].addEventListener("input", function (e) {
    if (e.target.value == "") {
      $("#id_cliente_ventas_g").val("");
    }
  })

  $("#cliente_ventas_g")
    .autocomplete({
      source: function (request, response) {
        var data = { term: request.term };
        $.get(
          "../../procesos/busquedaCliente_2.php",
          data,
          response,
          "json"
        );
      },
      minLength: 1,
      select: function (event, ui) {
        $("#cliente_ventas_g").val(ui.item.value);
        $("#id_cliente_ventas_g").val(ui.item.label);
        return false;
      },
      focus: function (event, ui) {
        return false;
      }
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item["value"] + "</a>")
        .appendTo(ul);
    };

  e.preventDefault();
}
function fn_reporte_general(e) {
  if ($("#excel").is(":checked")) {
    window.open(
      "../../phpexcel/generalFacturas.php?id=" +
      $("#sel_general").val() +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val() + '&id_cliente=' + $("#id_cliente_ventas_g").val(),
      "_blank"
    );
  } else {
    if ($("#fin").val() === "") {
      valores_incompletos();
    } else {
      window.open(
        "../../reportes/general.php?id=" +
        $("#sel_general").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val() + '&id_cliente=' + $("#id_cliente_ventas_g").val(),
        "_blank"
      );
    }
  }
}
// General Notas de Venta
function reporte_general_notas(e) {
  modal.open({
    content: `<label>General Notas de Venta</label><br>
    <input type='radio' name='group1' id='pdf' value='Reporte pdf' checked><label for='pdf'>Reporte en PDF</label><br>
    <input type='radio' name='group1' id='excel' value='Reporte EXCEL'><label for='excel'>Reporte en Excel</label><br>
    <label>Punto de Venta</label><select id='sel_general' style='width:150px;float:right'></select><br>
    <label>Fecha Inicio</label><input type='text' id='inicio'style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteGeneral' 
    onclick='return fn_reporte_general_notas(event)'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  $("#sel_general").load("../factura_venta/punto_venta_combos_inactivo.php");
  e.preventDefault();
}
function fn_reporte_general_notas(e) {
  if ($("#excel").is(":checked")) {
    window.open(
      "../../phpexcel/generalFacturas.php?id=" +
      $("#sel_general").val() +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  } else {
    if ($("#fin").val() === "") {
      valores_incompletos();
    } else {
      window.open(
        "../../reportes/general_nota.php?id=" +
        $("#sel_general").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val(),
        "_blank"
      );
    }
  }
}
// Facturas Detalladas
function resumen_detalle_ventas(e) {
  modal.open({
    content: `<label>Facturas Detalladas</label><br>
    <label>Punto de Venta</label><select id='id_facturas_detalladas' style='width:150px;float:right'></select><br>
    <label>Fecha Inicio</label><input type='text' id='inicio'style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteFacturasCompras' 
    onclick='return fn_reporte_detalle_venta(event)'>Generar Reporte</button>`,
  });
  $("#id_facturas_detalladas").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_reporte_detalle_venta(e) {
  if ($("#excel").is(":checked")) {
    window.open(
      "../../phpexcel/facturasVentasGeneral.php?id=" +
      1 +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  } else {
    if ($("#fin").val() === "") {
      valores_incompletos();
    } else {
      window.open(
        "../../reportes/facturasVentasGeneral.php?id=" +
        $("#id_facturas_detalladas").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val(),
        "_blank"
      );
    }
  }
}
// Productos por Clientes
function reporte_cliente_producto(e) {
  modal.open({
    content: `<label>Clientes por Productos</label><br>
    <label>Fecha Inicio</label> <input type='text' id='inicio'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteUtilidadProducto' 
    onclick='return fn_reporte_cliente_producto(event)'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_reporte_cliente_producto(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/cliente_productos.php?cliente=" +
      "" +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Facturas por Vendedor
function facturas_vendedor(e) {
  modal.open({
    content: `<label>Ventas por Ruta/Vendedor</label><br>
    <label>Punto de Venta</label><select id='selPunto' style='width:150px;float:right'></select><br>
    <label>Fecha Inicio</label> <input type='text' id='inicio'style='width:150px;float:right'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='width:150px;float: right;'><br>
    <label>Vendedor</label><select id='selVendedor' style='width:150px;float:right'></select><br>
    <label>Ruta</label><select class form-control id='selRuta' style='width:150px;float:right'></select><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteFacturasporVendedor' 
    onclick='return fn_facturas_por_vendedor(event)'>Generar Reporte</button>`,
  });
  $("#selRuta").load("../../procesos/combo-rutas.php");
  $("#selPunto").load("../factura_venta/punto_venta_combos_inactivo.php");
  $("#selVendedor").load("../../procesos/combo-vendedores.php");
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_facturas_por_vendedor(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/facturas_por_vendedor.php?idm=" +
      $("#selPunto").val() +
      "&id=" +
      $("#selVendedor").val() +
      "&idr=" +
      $("#selRuta").val() +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Busqueda Fuente Venta
function fv_retencion_fuente(e) {
  modal.open({
    content: `<label>Retención a la Fuente</label><br>
    <label>Punto de Venta</label><select id='sel_fv_rfuente' style='width:150px;float:right'></select><br>
    </select><label>Ret. Fuente</label><select id='sel_rfuente' style='width:150px;float:right'></select><br>
    <label>Fecha Inicio</label> <input type='text' id='inicio' style='width:150px;float:right'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='width:150px;float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarRet_Fuente_FVFechas' 
    onclick='return fn_fv_retencion_fuente(event)'>Generar Reporte</button>`,
  });
  $("#sel_fv_rfuente").load("../factura_venta/punto_venta_combos_inactivo.php");
  $("#sel_rfuente").load("../../procesos/comboRetencionFuente.php");
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_fv_retencion_fuente(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/fvRetencionFuente.php?id=" +
      $("#sel_fv_rfuente").val() +
      "&id_retencion=" +
      $("#sel_rfuente").val() +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Busqueda Iva
function fv_retencion_iva(e) {
  modal.open({
    content: `<label>Retención IVA</label><br>
    <label>Punto de Venta</label><select id='sel_fn_riva' style='width:150px;float:right'></select><br> 
    <label>Ret. Fuente</label><select id='sel_riva' style='width:150px;float:right'></select><br>
    <label>Fecha Inicio</label> <input type='text' id='inicio'style='width:150px;float:right'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='width:150px;float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarRet_iva_FVFechas' 
    onclick='return fn_fv_retencion_iva(event)'>Generar Reporte</button>`,
  });
  $("#sel_fn_riva").load("../factura_venta/punto_venta_combos_inactivo.php");
  $("#sel_riva").load("../../procesos/comboRetencionIVA.php");
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_fv_retencion_iva(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/fvRetencionIva.php?id=" +
      $("#sel_fn_riva").val() +
      "&id_retencion=" +
      $("#sel_riva").val() +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Fuente Factrua Venta
function rf_factura_venta(e) {
  modal.open({
    content: `<label>Retención Fuente</label><br>
    <label>Punto de Venta</label><select id='sel_retencion_venta' style='width:150px;float:right'></select><br>
    <label>Fecha Inicio</label> <input type='text' id='inicio'style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarRet_Fuente_FVFechas' 
    onclick='return fn_rf_fact_venta(event)'>Generar Reporte<buttona>`,
  });
  $("#sel_retencion_venta").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_rf_fact_venta(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/retencionFuentefacturasVentas.php?id=" +
      $("#sel_retencion_venta").val() +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Iva Factura Venta
function ri_factura_venta(e) {
  modal.open({
    content: `<label>Retención IVA</label><br>
    <label>Punto de Venta</label><select id='sel_iva_venta' style='width:150px;float:right'></select><br>
    <label>Fecha Inicio</label> <input type='text' id='inicio'style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarRet_iva_FVFechas' 
    onclick='return fn_ri_fact_venta(event)'>Generar Reporte</button>`,
  });
  $("#sel_iva_venta").load("../factura_venta/punto_venta_combos_inactivo.php");
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_ri_fact_venta(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/retencionIvafacturasVentas.php?id=" +
      $("#sel_iva_venta").val() +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Autorizaciones
// Cliente
function autorizaciones_cliente(e) {
  modal.open({
    content: `<label>Autorizaciones</label><br>
    <label for='buscarCli' style='padding:6px;'>Buscar<font color='red'>*</font></label>
    <input type='text' name='buscarCli' id='buscarCli' style='float: right;padding:2px;'/><input type='hidden' id='idCli' /><br>
    <button type='button' class='btn btn-success form-control' id='generarReporte_autorizacion' 
    onclick='return fn_autorizaciones_cliente(event)'>Generar Reporte</button>`,
  });
  $("#buscarCli")
    .autocomplete({
      source: "../../procesos/busquedaCliente.php",
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarCli").val(ui.item.value);
        $("#idCli").val(ui.item.id_cliente);
        return false;
      },
      select: function (event, ui) {
        $("#buscarCli").val(ui.item.value);
        $("#idCli").val(ui.item.id_cliente);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  e.preventDefault();
}
function fn_autorizaciones_cliente(e) {
  if ($("#buscarCli").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/reporte_autorizacion.php?id=" + $("#idCli").val(),
      "_blank"
    );
  }
}
// Fechas
function autorizaciones_cliente_fechas(e) {
  modal.open({
    content: `<label>Autorizaciones entre Fechas</label><br>
    <label>Fecha Inicio</label><input type='text' id='inicio' style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteEntregadosFecha' 
    onclick='return fn_autorizaciones_cliente_fechas(event)'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_autorizaciones_cliente_fechas(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/reporte_autorizacion_fechas.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Caducidad
function autorizaciones_cliente_caducidad(e) {
  modal.open({
    content: `<label>Autorizaciones A Caducar</label><br>
    <label>Fecha Caducidad<font color='red'>*</font></label>
    <input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteEntregadosCaducidad' 
    onclick='return fn_autorizaciones_cliente_caducidad(event)'>Generar Reporte</button>`,
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
  });
  e.preventDefault();
}
function fn_autorizaciones_cliente_caducidad(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/reporte_autorizacion_caducidad.php?&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Utilidades
// General
function reporte_utilidad_factura_general(e) {
  modal.open({
    content: `<label>Utilidades General</label><br>
    <label>Punto de Venta</label><select id='sel_utilidad_general' style='width:150px;float:right'></select><br>
    <label>Tipo Documento</label>
    <select id='sel_tdoc' style='width:150px;float:right'>
      <option value="factura">FACTURA</option>
      <option value="nota">NOTA VENTA</option>
    </select><br>
    <label>Fecha Inicio</label> <input type='text' id='inicio'style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteUtilidadFacturaGeneral' 
    onclick='return fn_reporte_utilidad_factura_general(event)'>Generar Reporte</button>`,
  });
  $("#sel_utilidad_general").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_reporte_utilidad_factura_general(e) {
  if ($("#sel_tdoc").val() == 'factura') {
    if ($("#fin").val() === "") {
      valores_incompletos();
    } else {
      window.open(
        "../../reportes/utilidad_factura_general.php?id=" +
        $("#sel_utilidad_general").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val(),
        "_blank"
      );
    }
  } else if ($("#sel_tdoc").val() == 'nota') {
    if ($("#fin").val() === "") {
      valores_incompletos();
    } else {
      window.open(
        "../../reportes/utilidad_nota_venta_general.php?id=" +
        $("#sel_utilidad_general").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val(),
        "_blank"
      );
    }
  }
}
// Utilidad Producto
function reporte_utilidad_producto(e) {
  modal.open({
    content: `<label>Utilidades Detalladas</label><br>
    <label>Fecha Inicio</label> <input type='text' id='inicio'><br>
    <label>Fecha Fin<font color='red'>*</font></label> <input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteUtilidadProducto' 
    onclick='return fn_reporte_utilidad_producto(event)'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_reporte_utilidad_producto(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/utilidad_productos.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Por Factura
function reporte_utilidad_factura(e) {
  modal.open({
    content: `<label>Utilidades por Factura</label><br>
    <label>Punto de Venta</label><select id='sel_utilidad_factura' style='width:150px;float:right'></select><br>
    <label>Fecha Inicio</label> <input type='text' id='inicio'style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteUtilidadFactura' 
    onclick='return fn_reporte_utilidad_factura(event)'>Generar Reporte</button>`,
  });
  $("#sel_utilidad_factura").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_reporte_utilidad_factura(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/utilidad_factura.php?id=" +
      $("#sel_utilidad_factura").val() +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Numeros de Serie
function buscar_serie(e) {
  modal.open({
    content: `<label>Numeros de Serie</label><br>
    <label for='buscarPro'>Buscar por Produ.</label><input type='text' name='buscarPro' id='buscarPro'/><input type='hidden' id='idPro'/><br>
    <label for='buscarSerie' style='padding:6px;'>Buscar por Serie</label>
    <input type='text' name='buscarSerie' id='buscarSerie' style='float: right;padding:2px;' /><input type='hidden' id='idSerie' /><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteSerie' 
    onclick='return fn_buscar_serie(event)'>Generar Reporte</button>`,
  });
  $("#buscarSerie")
    .autocomplete({
      source: "../../procesos/buscar_serie.php",
      minLength: 1,
      focus: function (event, ui) {
        $("#idSerie").val(ui.item.value);
        $("#buscarSerie").val(ui.item.label);
        return false;
      },
      select: function (event, ui) {
        $("#idSerie").val(ui.item.value);
        $("#buscarSerie").val(ui.item.label);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  $("#buscarPro")
    .autocomplete({
      source: "../../procesos/buscar_productos.php",
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarPro").val(ui.item.value);
        $("#idPro").val(ui.item.cod_producto);
        return false;
      },
      select: function (event, ui) {
        $("#buscarPro").val(ui.item.value);
        $("#idPro").val(ui.item.cod_producto);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  e.preventDefault();
}
function fn_buscar_serie(e) {
  if ($("#buscarPro").val() === "" && $("#buscarSerie").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/reporte_serie.php?id=" +
      $("#idSerie").val() +
      "&idp=" +
      $("#idPro").val(),
      "_blank"
    );
  }
}
// Por Socios
function venta_por_socios(e) {
  modal.open({
    content: `<label>Ventas por Socios</label><br>
    <label>Fecha Inicio</label> <input type='text' id='inicio' style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label> <input type='text' id='fin' style='float: right;'><br>
    <label for='buscarSocio'>Ced Socio: </label><input type='text' name='buscarSocio' id='buscarSocio'style='float: right;'/>
    <input type='hidden' id='idcli' style='float: right;'/><br><input type='text' id='idnombre' size='35'/><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteVentasporSocio' 
    onclick='return fn_venta_por_socios(event)'>Generar Reporte</button>`,
  });
  $("#buscarSocio")
    .autocomplete({
      source: "../../procesos/clientes_combos.php",
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarCliente").val(ui.item.value);
        $("#idcli").val(ui.item.id_cliente);
        $("#idnombre").val(ui.item.nombres_cli);
        return false;
      },
      select: function (event, ui) {
        $("#buscarCliente").val(ui.item.value);
        $("#idcli").val(ui.item.id_cliente);
        $("#idnombre").val(ui.item.nombres_cli);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_venta_por_socios(e) {
  window.open(
    "../../reportes/reporte_ventas_socios.php?id=" +
    $("#idcli").val() +
    "&inicio=" +
    $("#inicio").val() +
    "&fin=" +
    $("#fin").val(),
    "_blank"
  );
}
// Por Clientes
function venta_por_clientes(e) {
  modal.open({
    content: `<label>Ventas por Cliente</label><br>
    <label>Fecha Inicio</label> <input type='text' id='inicio' style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label> <input type='text' id='fin' style='float: right;'><br>
    <label for='buscarCliente'>Ced Cliente: </label><input type='text' name='buscarCliente' id='buscarCliente'style='float: right;'/>
    <input type='hidden' id='idcli' style='float: right;'/><br><input type='text' id='idnombre' size='35'/><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteVentasporCliente' 
    onclick='return fn_venta_por_clientes(event)'>Generar Reporte</button>`,
  });
  $("#buscarCliente")
    .autocomplete({
      source: "../../procesos/clientes_combos.php",
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarCliente").val(ui.item.value);
        $("#idcli").val(ui.item.id_cliente);
        $("#idnombre").val(ui.item.nombres_cli);
        return false;
      },
      select: function (event, ui) {
        $("#buscarCliente").val(ui.item.value);
        $("#idcli").val(ui.item.id_cliente);
        $("#idnombre").val(ui.item.nombres_cli);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_venta_por_clientes(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/reporte_ventas.php?id=" +
      $("#idcli").val() +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Reservaciones
// Resumen
function ventana_reservacion_general(e) {
  modal.open({
    content: `<label>Resumen Reservaciones</label><br>
    <label>Fecha Inicio</label><input type='text' id='inicio'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteResGeneral' 
    onclick='return fn_reporte_res_general(event)'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_reporte_res_general(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/resumenReservaciones.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Por prodcucto
function ventana_producto_reservacion(e) {
  modal.open({
    content: `<label>Reservaciones por Producto</label><br>
    <label for='buscarPro'>Nombre Producto: </label><input type='text' name='buscarPro' id='buscarPro' />
    <input type='hidden' id='idPro' /><br>
    <label>Fecha Inicio</label><input type='text' id='inicio' style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteResProducto' 
    onclick='return fn_reporte_res_producto(event)'>Generar Reporte</button>`,
  });
  $("#buscarPro")
    .autocomplete({
      source: "../../procesos/buscar_productos.php",
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarPro").val(ui.item.value);
        $("#idPro").val(ui.item.cod_producto);
        return false;
      },
      select: function (event, ui) {
        $("#buscarPro").val(ui.item.value);
        $("#idPro").val(ui.item.cod_producto);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  $("#inicio").datepicker({
    defaultDate: "+1w",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "+1w",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_reporte_res_producto(e) {
  if ($("#fin").val() === "" || $("#buscarPro").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/resumenReservacionProducto.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val() +
      "&cod=" +
      $("#idPro").val(),
      "_blank"
    );
  }
}
// Cartera
// CxC
// Resumenes
// General
function resumen_cxc(e) {
  modal.open({
    content: `<label>Resúmen General</label><br>
      <div>
        <label>Tipo Reporte: </label>
        <label><input id="tipo_pdf" type="radio" name="tipo_rep" checked> PDF</label>
        <label><input id="tipo_excel" type="radio" name="tipo_rep"> EXCEL</label>
      </div>
      <label>Punto de Venta: </label><select id='sel_punto_venta' style='width:150px;float:right'></select><br>
      <label>Usuario: </label><select id='sel_usuario' style='width:150px;float:right'></select><br> 
      <label>Cuenta: </label><select name='tipoCobro' id='tipoCobro' style='float: right;padding:2px;'>
      <option value='3'>Internas y Externas</option><option value='1'>Cuentas Internas</option><option value='2'>Cuentas Externas</option></select><br> 
      <div style="text-align:center">
        <label><input checked id="chk_cli" name="chk_tpb" type="radio"/> Cliente</labe>
        <label><input id="chk_rut" name="chk_tpb" type="radio"/> Ruta</labe>
        <label><input id="chk_ven" name="chk_tpb" type="radio"/> Vendedor</labe>
      </div>

      <div id="div_bcli"><label for='buscarCliente'>Cliente: </label><input placeholder="CI/RUC/NOMBRE" type='text' name='buscarCliente' id='buscarCliente' style="float: right;"/><input type='hidden' id='idCli'/></div>
      <div style="display:none" id="div_brut"><label for='buscarRuta'>Ruta: </label><input placeholder="INGRESE RUTA" type='text' name='buscarRuta' id='buscarRuta' style="float: right;"/><input type='hidden' id='idRuta'/></div>
      <div style="display:none" id="div_bven"><label for='buscarVendedor'>Vendedor: </label><input placeholder="CI/NOMBRE" type='text' name='buscarVendedor' id='buscarVendedor' style="float: right;"/><input type='hidden' id='idVen'/></div>
      
      <label>Fecha Inicio: </label> <input type='text' id='inicio' style="float: right;"><br>
      <label>Fecha Fin: <font color='red'>*</font></label><input type='text' id='fin' style='float: right;'></br>
      <button type='button' class='btn btn-success form-control' id='generarReporteCuentasporCobrar' 
      onclick='return fn_reporte_resumen_cuentas_cobrar(event)'>Generar Reporte</button>`,
  });
  $("#sel_usuario").load("../factura_venta/usuarios_combos.php");
  $("#sel_punto_venta").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );

  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });

  $("#buscarCliente")[0].addEventListener('input', function (e) {
    if (e.target.value == '') {
      $("#idCli").val("");
    }
  });
  $("#buscarRuta")[0].addEventListener('input', function (e) {
    if (e.target.value == '') {
      $("#idRuta").val("");
    }
  });
  $("#buscarVendedor")[0].addEventListener('input', function (e) {
    if (e.target.value == '') {
      $("#idVen").val("");
    }
  });

  $("#buscarCliente")
    .autocomplete({
      source: function (request, response) {
        $("#idCli").val("");
        var data = { term: request.term };
        $.get(
          "../../procesos/busquedaCliente_2.php",
          data,
          response,
          "json"
        );
      },
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarCliente").val(ui.item.value);
        $("#idCli").val(ui.item.label);
        return false;
      },
      select: function (event, ui) {
        $("#buscarCliente").val(ui.item.value);
        $("#idCli").val(ui.item.label);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };

  $("#buscarRuta")
    .autocomplete({
      source: function (request, response) {
        $("#idRuta").val("");
        var data = { term: request.term };
        $.get(
          "../../procesos/busquedaRuta.php",
          data,
          response,
          "json"
        );
      },
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarRuta").val(ui.item.value);
        $("#idRuta").val(ui.item.label);
        return false;
      },
      select: function (event, ui) {
        $("#buscarRuta").val(ui.item.value);
        $("#idRuta").val(ui.item.label);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };

  $("#buscarVendedor")
    .autocomplete({
      source: function (request, response) {
        $("#idVen").val("");
        var data = { term: request.term };
        $.get(
          "../../procesos/busquedaVendedor.php",
          data,
          response,
          "json"
        );
      },
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarVendedor").val(ui.item.value);
        $("#idVen").val(ui.item.label);
        return false;
      },
      select: function (event, ui) {
        $("#buscarVendedor").val(ui.item.value);
        $("#idVen").val(ui.item.label);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };

  let divsb = document.getElementsByName("chk_tpb");
  divsb = Array.from(divsb);
  divsb.forEach(el => {
    $(el).change(function (e) {
      switch (e.target.id) {
        case "chk_cli":
          $("#div_bcli").css({ display: "" });
          $("#div_brut").css({ display: "none" });
          $("#div_bven").css({ display: "none" });
          $("#buscarCliente").val("");
          $("#idCli").val("");
          break;
        case "chk_rut":
          $("#div_bcli").css({ display: "none" });
          $("#div_brut").css({ display: "" });
          $("#div_bven").css({ display: "none" });
          $("#buscarRuta").val("");
          $("#idRuta").val("");
          break;
        case "chk_ven":
          $("#div_bcli").css({ display: "none" });
          $("#div_brut").css({ display: "none" });
          $("#div_bven").css({ display: "" });
          $("#buscarVendedor").val("");
          $("#idVen").val("");
          break;
      }
    });
  });
  e.preventDefault();
}
function fn_reporte_resumen_cuentas_cobrar(e) {
  let tipo = '';
  if ($("#tipoCobro").val() == 1) {
    // reporte resumen_cuentas_cobrar_internas.php
    tipo = "Internas";
  } else if ($("#tipoCobro").val() == 2) {
    tipo = "Externas";
  }
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    let divsb = document.getElementsByName("chk_tpb");
    divsb = Array.from(divsb);
    let rchecked = divsb.find(el => el.checked);
    let querytb = "";
    switch (rchecked.id) {
      case "chk_cli":
        querytb = "&id_cliente=" + $("#idCli").val();
        break;
      case "chk_rut":
        querytb = "&id_ruta=" + $("#idRuta").val();
        break;
      case "chk_ven":
        querytb = "&id_vendedor=" + $("#idVen").val();
        break;
    }
    if ($("#tipo_pdf")[0].checked) {
      if (tipo == "Internas") {
        window.open(
          "../../reportes/resumen_cuentas_cobrar_internas.php?id_empre=" +
          $("#sel_punto_venta").val() +
          "&inicio=" +
          $("#inicio").val() +
          "&fin=" +
          $("#fin").val() +
          "&id=" +
          $("#sel_usuario").val() +
          "&tipo=" + tipo +
          querytb,
          "_blank"
        );
      } else {
        window.open(
          "../../reportes/resumen_cuentas_cobrar" +
          ".php?id_empre=" +
          $("#sel_punto_venta").val() +
          "&inicio=" +
          $("#inicio").val() +
          "&fin=" +
          $("#fin").val() +
          "&id=" +
          $("#sel_usuario").val() +
          "&tipo=" + tipo +
          querytb,
          "_blank"
        );
      }
    } else {
      window.open(
        "../../phpexcel/resumen_cuentas_cobrar" +
        ".php?id_empre=" +
        $("#sel_punto_venta").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val() +
        "&id=" +
        $("#sel_usuario").val() +
        "&tipo=" + tipo +
        querytb,
        "_blank"
      );
    }
  }
}
// Canceladas
function facturas_canceladas(e) {
  modal.open({
    content: `<label>Cuentas x Cobrar Canceladas</label><br>
    <div>
        <label>Tipo Reporte: </label>
        <label><input id="tipo_pdf" type="radio" name="tipo_rep" checked> PDF</label>
        <label><input id="tipo_excel" type="radio" name="tipo_rep"> EXCEL</label>
      </div>
    <label>Punto de Venta</label><select id='sel_punto_venta' style='width:150px;float:right'></select><br>
   <label>Usuario</label><select id='sel_usuario' style='width:150px;float:right'></select><br> 
   
   <label for='buscarCliente2'>Cliente:</label><input placeholder="CI/RUC/NOMBRE" type='text' name='buscarCliente2' id='buscarCliente2' style="float: right;"/><input type='hidden' id='idCli'/><br>
   
   <label>Fecha Inicio</label> <input type='text' id='inicio' style="float: right;"><br> 
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'></br>
    <button type='button' class='btn btn-success form-control' id='generarReporteCXCCanceladas' 
    onclick='return fn_facturas_canceladas(event)'>Generar Reporte</button>`,
  });
  $("#sel_usuario").load("../factura_venta/usuarios_combos.php");
  $("#sel_punto_venta").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  $("#buscarCliente2")[0].addEventListener('input', function (e) {
    if (e.target.value == '') {
      $("#idCli").val("");
    }
  });

  $("#buscarCliente2")
    .autocomplete({
      source: function (request, response) {
        $("#idCli").val("");
        var data = { term: request.term };
        $.get(
          "../../procesos/busquedaCliente_2.php",
          data,
          response,
          "json"
        );
      },
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarCliente2").val(ui.item.value);
        $("#idCli").val(ui.item.label);
        return false;
      },
      select: function (event, ui) {
        $("#buscarCliente2").val(ui.item.value);
        $("#idCli").val(ui.item.label);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  e.preventDefault();
}
function fn_facturas_canceladas(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    if ($("#tipo_pdf")[0].checked) {
      window.open(
        "../../reportes/facturas_canceladas_cliente.php?id_empre=" +
        $("#sel_punto_venta").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val() +
        "&id=" +
        $("#sel_usuario").val() +
        "&id_cliente=" +
        $("#idCli").val(),
        "_blank"
      );
    } else {
      window.open(
        "../../phpexcel/facturas_canceladas_cliente.php?id_empre=" +
        $("#sel_punto_venta").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val() +
        "&id=" +
        $("#sel_usuario").val() +
        "&id_cliente=" +
        $("#idCli").val(),
        "_blank"
      );
    }

  }
}
// Por Caducidad
function facturas_por_caducidad(e) {
  modal.open({
    content: `<label>Cuentas x Cobrar por Caducidad</label><br>
       <label>Punto de Venta</label><select id='sel_punto_venta' style='width:150px;float:right'></select><br>
   <label>Usuario</label><select id='sel_usuario' style='width:150px;float:right'></select><br> 
   
    <label for='tipoCobro' style='padding:6px;'>Cuenta</label>
    <select name='tipoCobro' id='tipoCobro' style='float: right;padding:2px;'>
    <option value='1'>Cuentas Internas</option><option value='2'>Cuentas Externas</option></select></br>
    <label>Caducidad</label><select name='caducidad' id='caducidad' style='float: right;padding:2px;'>
    <option value='1'>Caducadas</option><option value='2'>15 días plazo</option>
    <option value='3'>30 días plazo</option><option value='4'>45 días plazo</option>
    <option value='5'>Canceladas</option></select></br>
    <button type='button' class='btn btn-success form-control' id='generarReporteCaducidad' 
    onclick='return fn_cxc_caducidad(event)'>Generar Reporte</button>`,
  });

  $("#sel_usuario").load("../factura_venta/usuarios_combos.php");
  $("#sel_punto_venta").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );

  e.preventDefault();
}
function fn_cxc_caducidad(e) {
  let fin;
  switch ($("#caducidad").val()) {
    case "1":
      fin = 0;
      break;
    case "2":
      fin = 15;
      break;
    case "3":
      fin = 30;
      break;
    case "4":
      fin = 45;
      break;
    case "5":
      fin = 1;
      break;
    default:
      break;
  }
  let tipo;
  if ($("#tipoCobro").val() == 1) {
    tipo = "Internas";
  } else {
    tipo = "Externas";
  }
  window.open(
    "../../reportes/cxc_caducidad.php?tipo=" +
    tipo +
    "&fin=" +
    fin +
    "&id_empre=" +
    $("#sel_punto_venta").val() +
    "&id=" +
    $("#sel_usuario").val(),
    "_blank"
  );
}
// Por Cobrar
// General
function ventana_facturas_cobrar_clientes(e) {
  modal.open({
    content: `<label>Cuentas Pendientes de Cobro</label><br>
    <div>
      <label>Tipo Reporte: </label>
      <label><input id="tipo_pdf" type="radio" name="tipo_rep" checked> PDF</label>
      <label><input id="tipo_excel" type="radio" name="tipo_rep"> EXCEL</label>
    </div>
      <label>Punto de Venta</label><select id='sel_punto_venta' style='width:150px;float:right'></select><br>
  
      <label>Usuario</label><select id='sel_usuario_cobros' style='width:150px;float:right'></select><br>

      <label for='tipoCobro' style='padding:6px;'>Cuenta</label>
      <select name='tipoCobro' id='tipoCobro' style='float: right;padding:2px;'>
      <option value=''>Internas y Externas</option><option value='1'>Cuentas Internas</option><option value='2'>Cuentas Externas</option></select></br>
      
      <div style="text-align:center">
        <label><input checked id="chk_cli" name="chk_tpb" type="radio"/> Cliente</labe>
        <label><input id="chk_rut" name="chk_tpb" type="radio"/> Ruta</labe>
        <label><input id="chk_ven" name="chk_tpb" type="radio"/> Vendedor</labe>
      </div>

      <div id="div_bcli"><label id="div_bcli" for='buscarCliente'>Cliente:</label><input placeholder="CI/RUC/NOMBRE" type='text' name='buscarCliente' id='buscarCliente' style='float: right;'/><input type='hidden' id='idCli'/></div>
      <div style="display:none" id="div_brut"><label for='buscarRuta'>Ruta: </label><input placeholder="INGRESE RUTA" type='text' name='buscarRuta' id='buscarRuta' style="float: right;"/><input type='hidden' id='idRuta'/></div>
      <div style="display:none" id="div_bven"><label for='buscarVendedor'>Vendedor: </label><input placeholder="CI/NOMBRE" type='text' name='buscarVendedor' id='buscarVendedor' style="float: right;"/><input type='hidden' id='idVen'/></div>

      <label>Fecha Inicio</label><input type='text' id='inicio' style='float: right;'><br>
      <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
      
      <label for='tipo_documento_id' style='padding:6px;'>Tipo Documento</label>
      <select name='tipo_documento_id' id='tipo_documento_id' style='float: right;padding:2px;'>
      <option>TODOS</option></select></br>
      <button type='button' class='btn btn-success' id='generarReporteCuentasporCobrar' 
      onclick='return fn_facturas_cobrar_clientes(event)'>Generar Reporte</button>`,
  });

  let seltipoc = $("#tipoCobro");
  let seltipod = $("#tipo_documento_id");

  seltipoc.change(function (e) {
    switch (e.target.value) {
      case "1":
        seltipod.empty();
        seltipod.append("<option value='1'>Factura</option>");
        seltipod.append("<option value='2'>Nota Venta</option></select>");
        break;
      case "2":
        seltipod.empty();
        seltipod.append("<option>TODOS</option>");
        break;
      default:
        seltipod.empty();
        seltipod.append("<option>TODOS</option>");
        break;
    }
    console.log();
  });

  $("#sel_usuario_cobros").load("../factura_venta/usuarios_combos.php");
  $("#sel_punto_venta").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );

  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });

  $("#buscarCliente")[0].addEventListener('input', function (e) {
    if (e.target.value == '') {
      $("#idCli").val("");
    }
  });
  $("#buscarRuta")[0].addEventListener('input', function (e) {
    if (e.target.value == '') {
      $("#idRuta").val("");
    }
  });
  $("#buscarVendedor")[0].addEventListener('input', function (e) {
    if (e.target.value == '') {
      $("#idVen").val("");
    }
  });

  $("#buscarCliente")
    .autocomplete({
      source: function (request, response) {
        $("#idCli").val("");
        var data = { term: request.term };
        $.get(
          "../../procesos/busquedaCliente_2.php",
          data,
          response,
          "json"
        );
      },
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarCliente").val(ui.item.value);
        $("#idCli").val(ui.item.label);
        return false;
      },
      select: function (event, ui) {
        $("#buscarCliente").val(ui.item.value);
        $("#idCli").val(ui.item.label);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };

  $("#buscarRuta")
    .autocomplete({
      source: function (request, response) {
        $("#idRuta").val("");
        var data = { term: request.term };
        $.get(
          "../../procesos/busquedaRuta.php",
          data,
          response,
          "json"
        );
      },
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarRuta").val(ui.item.value);
        $("#idRuta").val(ui.item.label);
        return false;
      },
      select: function (event, ui) {
        $("#buscarRuta").val(ui.item.value);
        $("#idRuta").val(ui.item.label);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };

  $("#buscarVendedor")
    .autocomplete({
      source: function (request, response) {
        $("#idVen").val("");
        var data = { term: request.term };
        $.get(
          "../../procesos/busquedaVendedor.php",
          data,
          response,
          "json"
        );
      },
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarVendedor").val(ui.item.value);
        $("#idVen").val(ui.item.label);
        return false;
      },
      select: function (event, ui) {
        $("#buscarVendedor").val(ui.item.value);
        $("#idVen").val(ui.item.label);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };



  let divsb = document.getElementsByName("chk_tpb");
  divsb = Array.from(divsb);
  divsb.forEach(el => {
    $(el).change(function (e) {
      switch (e.target.id) {
        case "chk_cli":
          $("#div_bcli").css({ display: "" });
          $("#div_brut").css({ display: "none" });
          $("#div_bven").css({ display: "none" });
          $("#buscarCliente").val("");
          $("#idCli").val("");
          break;
        case "chk_rut":
          $("#div_bcli").css({ display: "none" });
          $("#div_brut").css({ display: "" });
          $("#div_bven").css({ display: "none" });
          $("#buscarRuta").val("");
          $("#idRuta").val("");
          break;
        case "chk_ven":
          $("#div_bcli").css({ display: "none" });
          $("#div_brut").css({ display: "none" });
          $("#div_bven").css({ display: "" });
          $("#buscarVendedor").val("");
          $("#idVen").val("");
          break;
      }
    });
  });
  e.preventDefault();
}
function fn_facturas_cobrar_clientes(e) {
  let tipo;
  if ($("#tipoCobro").val() == 1) {
    tipo = "Internas";
  } else if ($("#tipoCobro").val() == 2) {
    tipo = "Externas";
  } else {
    tipo = "";
  }

  let tipo_documento;
  if ($("#tipo_documento_id").val() == 1) {
    tipo_documento = "factura";
  } else {
    tipo_documento = "nota1";
  }

  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    let divsb = document.getElementsByName("chk_tpb");
    divsb = Array.from(divsb);
    let rchecked = divsb.find(el => el.checked);
    let querytb = "";
    switch (rchecked.id) {
      case "chk_cli":
        querytb = "&id_cliente=" + $("#idCli").val();
        break;
      case "chk_rut":
        querytb = "&id_ruta=" + $("#idRuta").val();
        break;
      case "chk_ven":
        querytb = "&id_vendedor=" + $("#idVen").val();
        break;
    }
    if ($("#tipo_pdf")[0].checked) {
      window.open(
        "../../reportes/facturas_por_cobrar.php?id_empre=" +
        $("#sel_punto_venta").val() +
        "&tipo=" +
        tipo +
        "&tipo_documento=" +
        tipo_documento +
        "&id=" +
        $("#sel_usuario_cobros").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val() +
        querytb,
        "_blank"
      );
    } else {
      window.open(
        "../../phpexcel/facturas_por_cobrar.php?id_empre=" +
        $("#sel_punto_venta").val() +
        "&tipo=" +
        tipo +
        "&tipo_documento=" +
        tipo_documento +
        "&id=" +
        $("#sel_usuario_cobros").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val() +
        querytb,
        "_blank"
      );
    }

  }
}
// ???
function facturas_cobrar_clientes(e) {
  if ($("#tipoCobro").val() == 1) {
    window.open(
      "../../reportes/facturas_por_cobrar.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val() +
      "&id=" +
      $("#sel_usuario").val() +
      "&tipo_documento=" +
      $("#tipo_documento_id").val(),
      "_blank"
    );
  } else {
    window.open(
      "../../reportes/facturas_por_cobrar_2.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}

// Por cliente
function cxc_por_clientes(e) {
  modal.open({
    content: `<label>Clientes por Cobrar</label><br> 
   <label>Punto de Venta</label><select id='sel_punto_venta' style='width:150px;float:right'></select><br>
   <label>Usuario</label><select id='sel_usuario' style='width:150px;float:right'></select><br> 
   
   
    <label for='tipoCobro' style='padding:6px;'>Cuenta</label>
    <select name='tipoCobro' id='tipoCobro' style='float: right;padding:2px;'>
    <option value='1'>Cuentas Internas</option><option value='2'>Cuentas Externas</option></select></br>
    <label>Fecha Inicio</label><input type='text' id='inicio' style='float: right;'><br> 
    <label>Fecha Fin<font color='red'>*</font></label> <input type='text' id='fin' style='float: right;'><br>
    <label for='buscarCliente'>Ced Cliente: <font color='red'>*</font></label>
    <input type='text' name='buscarCliente' id='buscarCliente'style='float: right;' />
    <input type='hidden' id='idcli' style='float: right;'/><br>
    <input type='text' id='idnombre' size='35'/><br>
    <label for='tipo_documentocc' style='padding:6px;'>Tipo Documento</label>
    <select name='tipo_documentocc' id='tipo_documentocc' style='float: right;padding:2px;'>
    <option value='factura'>Factura</option><option value='nota'>Nota Venta</option></select></br>
    <button type='button' class='btn btn-success form-control' id='generarReporteCuentasporCliente' 
    onclick='return fn_facturas_cobrar_cliente(event)'>Generar Reporte</button>`,
  });
  $("#sel_usuario").load("../factura_venta/usuarios_combos.php");
  $("#sel_punto_venta").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );

  $("#buscarCliente")
    .autocomplete({
      source: "../../procesos/clientes_combos.php",
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarCliente").val(ui.item.value);
        $("#idcli").val(ui.item.id_cliente);
        $("#idnombre").val(ui.item.nombres_cli);
        return false;
      },
      select: function (event, ui) {
        $("#buscarCliente").val(ui.item.value);
        $("#idcli").val(ui.item.id_cliente);
        $("#idnombre").val(ui.item.nombres_cli);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: (selectedDate) => {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: (selectedDate) => {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_facturas_cobrar_cliente(e) {
  let tipo;
  if ($("#tipoCobro").val() == 1) {
    tipo = "Internas";
  } else {
    tipo = "Externas";
  }
  if ($("#fin").val() === "" || $("#buscarCliente").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/cxc_cliente.php?id_empre=" +
      $("#sel_punto_venta").val() +
      "&id=" +
      $("#idcli").val() +
      "&tipo=" +
      tipo +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val() +
      "&tipo_documento=" +
      $("#tipo_documentocc").val() +
      "&id_usuario=" +
      $("#sel_usuario").val(),
      "_blank"
    );
  }
}
// Cobros
// General
function cobros_realizados(e) {
  modal.open({
    content: `<label>Cobros General</label><br> 
    <div>
      <label>Tipo Reporte: </label>
      <label><input id="tipo_pdf" type="radio" name="tipo_rep" checked> PDF</label>
      <label><input id="tipo_excel" type="radio" name="tipo_rep"> EXCEL</label>
    </div>
     <label>Punto de Venta</label><select id='sel_punto_venta' style='width:150px;float:right'></select><br>
   <label>Usuario</label><select id='sel_usuario' style='width:150px;float:right'></select><br>    
    <label for='tipoCobro' style='padding:6px;'>Cuenta</label>
    <select name='tipoCobro' id='tipoCobro' style='float: right;'>
    <option value='1'>Cuentas Internas</option><option value='2'>Cuentas Externas</option></select><br> 


    <div style="text-align:center">
        <label><input checked id="chk_cli" name="chk_tpb" type="radio"/> Cliente</labe>
        <label><input id="chk_rut" name="chk_tpb" type="radio"/> Ruta</labe>
        <label><input id="chk_ven" name="chk_tpb" type="radio"/> Vendedor</labe>
    </div>

    <div id="div_bcli"><label for='buscarCliente'>Cliente:</label><input placeholder="CI/RUC/NOMBRE" type='text' name='buscarCliente' id='buscarCliente' style='float: right;'/><input type='hidden' id='idCli'/></div>
    <div style="display:none" id="div_brut"><label for='buscarRuta'>Ruta: </label><input placeholder="INGRESE RUTA" type='text' name='buscarRuta' id='buscarRuta' style="float: right;"/><input type='hidden' id='idRuta'/></div>
    <div style="display:none" id="div_bven"><label for='buscarVendedor'>Vendedor: </label><input placeholder="CI/NOMBRE" type='text' name='buscarVendedor' id='buscarVendedor' style="float: right;"/><input type='hidden' id='idVen'/></div>

    <label>Fecha Inicio</label> <input type='text' id='inicio' style="float:right;";><br> 
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteCobrosRealizados' 
    onclick='return fn_cobros_realizados(event)'>Generar Reporte</button>`,
  });
  $("#sel_usuario").load("../factura_venta/usuarios_combos.php");
  $("#sel_punto_venta").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );

  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });

  $("#buscarCliente")[0].addEventListener('input', function (e) {
    if (e.target.value == '') {
      $("#idCli").val("");
    }
  });

  $("#buscarRuta")[0].addEventListener('input', function (e) {
    if (e.target.value == '') {
      $("#idRuta").val("");
    }
  });
  $("#buscarVendedor")[0].addEventListener('input', function (e) {
    if (e.target.value == '') {
      $("#idVen").val("");
    }
  });

  $("#buscarCliente")
    .autocomplete({
      source: function (request, response) {
        $("#idCli").val("");
        var data = { term: request.term };
        $.get(
          "../../procesos/busquedaCliente_2.php",
          data,
          response,
          "json"
        );
      },
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarCliente").val(ui.item.value);
        $("#idCli").val(ui.item.label);
        return false;
      },
      select: function (event, ui) {
        $("#buscarCliente").val(ui.item.value);
        $("#idCli").val(ui.item.label);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };

  $("#buscarRuta")
    .autocomplete({
      source: function (request, response) {
        $("#idRuta").val("");
        var data = { term: request.term };
        $.get(
          "../../procesos/busquedaRuta.php",
          data,
          response,
          "json"
        );
      },
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarRuta").val(ui.item.value);
        $("#idRuta").val(ui.item.label);
        return false;
      },
      select: function (event, ui) {
        $("#buscarRuta").val(ui.item.value);
        $("#idRuta").val(ui.item.label);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };

  $("#buscarVendedor")
    .autocomplete({
      source: function (request, response) {
        $("#idVen").val("");
        var data = { term: request.term };
        $.get(
          "../../procesos/busquedaVendedor.php",
          data,
          response,
          "json"
        );
      },
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarVendedor").val(ui.item.value);
        $("#idVen").val(ui.item.label);
        return false;
      },
      select: function (event, ui) {
        $("#buscarVendedor").val(ui.item.value);
        $("#idVen").val(ui.item.label);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };

  let divsb = document.getElementsByName("chk_tpb");
  divsb = Array.from(divsb);
  divsb.forEach(el => {
    $(el).change(function (e) {
      switch (e.target.id) {
        case "chk_cli":
          $("#div_bcli").css({ display: "" });
          $("#div_brut").css({ display: "none" });
          $("#div_bven").css({ display: "none" });
          $("#buscarCliente").val("");
          $("#idCli").val("");
          break;
        case "chk_rut":
          $("#div_bcli").css({ display: "none" });
          $("#div_brut").css({ display: "" });
          $("#div_bven").css({ display: "none" });
          $("#buscarRuta").val("");
          $("#idRuta").val("");
          break;
        case "chk_ven":
          $("#div_bcli").css({ display: "none" });
          $("#div_brut").css({ display: "none" });
          $("#div_bven").css({ display: "" });
          $("#buscarVendedor").val("");
          $("#idVen").val("");
          break;
      }
    });
  });
  e.preventDefault();
}
function fn_cobros_realizados(e) {

  let divsb = document.getElementsByName("chk_tpb");
  divsb = Array.from(divsb);
  let rchecked = divsb.find(el => el.checked);
  let querytb = "";
  switch (rchecked.id) {
    case "chk_cli":
      querytb = "&id_cliente=" + $("#idCli").val();
      break;
    case "chk_rut":
      querytb = "&id_ruta=" + $("#idRuta").val();
      break;
    case "chk_ven":
      querytb = "&id_vendedor=" + $("#idVen").val();
      break;
  }
  if ($("#tipoCobro").val() == 1) {
    if ($("#tipo_pdf")[0].checked) {
      window.open(
        "../../reportes/cobros_realizados_internos.php?id_empre=" +
        $("#sel_punto_venta").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val() +
        "&id=" +
        $("#sel_usuario").val() +
        querytb,
        "_blank"
      );
    } else {
      window.open(
        "../../phpexcel/cobros_realizados.php?id_empre=" +
        $("#sel_punto_venta").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val() +
        "&id=" +
        $("#sel_usuario").val() +
        querytb +
        "&tipo=Internas",
        "_blank"
      );
    }

  } else {
    if ($("#tipo_pdf")[0].checked) {
      window.open(
        "../../reportes/cobros_realizados.php?id_empre=" +
        $("#sel_punto_venta").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val() +
        "&id=" +
        $("#sel_usuario").val() +
        querytb,
        "_blank"
      );
    } else {
      window.open(
        "../../phpexcel/cobros_realizados.php?id_empre=" +
        $("#sel_punto_venta").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val() +
        "&id=" +
        $("#sel_usuario").val() +
        querytb +
        "&tipo=Externas",
        "_blank"
      );
    }

  }
}
// Por Cliente
function cobros_clientes(e) {
  modal.open({
    content: `
   <label>Punto de Venta</label><select id='sel_punto_venta' style='width:150px;float:right'></select><br>
   <label>Usuario</label><select id='sel_usuario' style='width:150px;float:right'></select><br> 
   
<label for='tipo_documento' style='padding:6px;'>Documento</label>
    <select name='tipo_documento' id='tipo_documento' style='float: right;padding:2px;'>
    <option value='factura'>Factura</option><option value='nota'>Nota Venta</option></select> <br>
    <label for='tipoCobro' style='padding:6px;'>Cuenta</label>
    <select name='tipoCobro' id='tipoCobro' style='float: right;padding:2px;'>
    <option value='1'>Cuentas Internas</option><option value='2'>Cuentas Externas</option></select></br>
    <label>Fecha Inicio</label> <input type='text' id='inicio' style='float: right;'><br> 
    <label>Fecha Fin<font color='red'>*</font></label> <input type='text' id='fin' style='float: right;'></br>
    <label for='buscarCliente'>Ced Cliente: <font color='red'>*</font></label>
    <input type='text' name='buscarCliente' id='buscarCliente' style='float: right;' />
    <input type='hidden' id='idcli' style='float: right;'/></br>
    <input type='text' id='idnombre' size='35' readonly /></br>
    <button type='button' class='btn btn-success form-control' id='generarReporteCuentasporCliente' 
    onclick='return fn_cobros_clientes(event)' >Generar Reporte</button>`,
  });

  $("#sel_usuario").load("../factura_venta/usuarios_combos.php");
  $("#sel_punto_venta").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );

  $("#buscarCliente")
    .autocomplete({
      source: "../../procesos/clientes_combos.php",
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarCliente").val(ui.item.value);
        $("#idcli").val(ui.item.id_cliente);
        $("#idnombre").val(ui.item.nombres_cli);
        return false;
      },
      select: function (event, ui) {
        $("#buscarCliente").val(ui.item.value);
        $("#idcli").val(ui.item.id_cliente);
        $("#idnombre").val(ui.item.nombres_cli);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_cobros_clientes(e) {
  let tipo;
  if ($("#tipoCobro").val() == 1) {
    tipo = "Internas";
  } else {
    tipo = "Externas";
  }
  if ($("#fin").val() === "" || $("#idnombre").val() === "") {
    alertify.error("Ingrese valores requeridos");
  } else {
    window.open(
      "../../reportes/cobros_cliente.php?id_empre=" +
      $("#sel_punto_venta").val() +
      "&id=" +
      $("#idcli").val() +
      "&tipo=" +
      tipo +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val() +
      "&tipo_documento=" +
      $("#tipo_documento").val() +
      "&id_usuario=" +
      $("#sel_usuario").val(),
      "_blank"
    );
  }
}
// CXP
// Resuemenes
// General
function resumen_cxp(e) {
  modal.open({
    content: `<label>Resúmen General</label></br>
    <div>
      <label>Tipo Reporte: </label>
      <label><input id="tipo_pdf" type="radio" name="tipo_rep" checked> PDF</label>
      <label><input id="tipo_excel" type="radio" name="tipo_rep"> EXCEL</label>
    </div>
      <label>Punto de Venta</label><select id='sel_punto_venta' style='width:150px;float:right'></select><br>
    <label>Usuario</label><select id='sel_usuario' style='width:150px;float:right'></select><br>    
    <label>Cuenta</label><select name='tipoCobro' id='tipoCobro' style='float: right;padding:2px;'>
    <option value='3'>Internas y Externas</option><option value='1'>Cuentas Internas</option><option value='2'>Cuentas Externas</option></select><br> 
    <label for='buscarProvrcp'>Proveedor:</label><input placeholder="CI/RUC/NOMBRE" type='text' name='buscarProvrcp' id='buscarProvrcp' style="float:right"/><input type='hidden' id='idProv'/><br>
    <label>Fecha Inicio</label> <input type='text' id='inicio' style="float:right"></br>
    <label>Fecha Fin <font color='red'>*</font></label><input type='text' id='fin' style='float: right;' style="float:right"><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteCuentasporPagar' 
    onclick='return fn_reporte_resumen_cuentas_pagar(event)'>Generar Reporte</button>`,
  });
  $("#sel_usuario").load("../factura_venta/usuarios_combos.php");
  $("#sel_punto_venta").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );

  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });

  $("#buscarProvrcp")[0].addEventListener('input', function (e) {
    if (e.target.value == '') {
      $("#idProv").val("");
    }
  });

  $("#buscarProvrcp")
    .autocomplete({
      //source: "../../procesos/buscar_proveedor.php",
      source: function (request, response) {
        $("#idProv").val("");
        var data = { term: request.term };
        $.get(
          "../../procesos/buscar_proveedor.php",
          data,
          response,
          "json"
        );
      },
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarProvrcp").val(ui.item.value);
        $("#idProv").val(ui.item.id_proveedor);
        return false;
      },
      select: function (event, ui) {
        $("#buscarProvrcp").val(ui.item.value);
        $("#idProv").val(ui.item.id_proveedor);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  e.preventDefault();
}
function fn_reporte_resumen_cuentas_pagar(e) {
  let tipo;
  if ($("#tipoCobro").val() == 1) {
    tipo = "Internas";
  } else if ($("#tipoCobro").val() == 2) {
    tipo = "Externas";
  } else {
    tipo = ''
  }

  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    if ($("#tipo_pdf")[0].checked) {
      if (tipo == "Internas") {
        window.open(
          "../../reportes/resumen_cuentas_pagar_internas.php?id_empre=" +
          $("#sel_punto_venta").val() +
          "&tipo=" +
          tipo +
          "&inicio=" +
          $("#inicio").val() +
          "&fin=" +
          $("#fin").val() +
          "&id=" +
          $("#sel_usuario").val() +
          "&id_proveedor=" +
          $("#idProv").val(),
          "_blank"
        );
      } else {
        window.open(
          "../../reportes/resumen_cuentas_pagar.php?id_empre=" +
          $("#sel_punto_venta").val() +
          "&tipo=" +
          tipo +
          "&inicio=" +
          $("#inicio").val() +
          "&fin=" +
          $("#fin").val() +
          "&id=" +
          $("#sel_usuario").val() +
          "&id_proveedor=" +
          $("#idProv").val(),
          "_blank"
        );
      }

    } else {
      window.open(
        "../../phpexcel/resumen_cuentas_pagar.php?id_empre=" +
        $("#sel_punto_venta").val() +
        "&tipo=" +
        tipo +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val() +
        "&id=" +
        $("#sel_usuario").val() +
        "&id_proveedor=" +
        $("#idProv").val(),
        "_blank"
      );
    }
  }
}
// Canceladas
function facturas_canceladas_proveedor(e) {
  modal.open({
    content: `<label>Cuentas Canceladas</label><br>
    <div>
      <label>Tipo Reporte: </label>
      <label><input id="tipo_pdf" type="radio" name="tipo_rep" checked> PDF</label>
      <label><input id="tipo_excel" type="radio" name="tipo_rep"> EXCEL</label>
    </div>
       <label>Punto de Venta</label><select id='sel_punto_venta' style='width:150px;float:right'></select><br>
   <label>Usuario</label><select id='sel_usuario' style='width:150px;float:right'></select><br>    
   
   <label for='buscarProvrcpc'>Proveedor:</label><input placeholder="CI/RUC/NOMBRE" type='text' name='buscarProvrcpc' id='buscarProvrcpc' style="float:right"/><input type='hidden' id='idProv'/><br>
   
   <label>Fecha Inicio</label> <input type='text' id='inicio' style="float:right"><br> 
    <label>Fecha Fin <font color='red'>*</font></label> <input type='text' id='fin' style='float: right;'></br>
    <button type='button' class='btn btn-success form-control' id='generarReporteCuentasporCobrarp' 
    onclick='return fn_facturas_canceladas_proveedor(event)'>Generar Reporte</button>`,
  });
  $("#sel_usuario").load("../factura_venta/usuarios_combos.php");
  $("#sel_punto_venta").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );

  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });

  $("#buscarProvrcpc")[0].addEventListener('input', function (e) {
    if (e.target.value == '') {
      $("#idProv").val("");
    }
  });

  $("#buscarProvrcpc")
    .autocomplete({
      //source: "../../procesos/buscar_proveedor.php",
      source: function (request, response) {
        $("#idProv").val("");
        var data = { term: request.term };
        $.get(
          "../../procesos/buscar_proveedor.php",
          data,
          response,
          "json"
        );
      },
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarProvrcpc").val(ui.item.value);
        $("#idProv").val(ui.item.id_proveedor);
        return false;
      },
      select: function (event, ui) {
        $("#buscarProvrcpc").val(ui.item.value);
        $("#idProv").val(ui.item.id_proveedor);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  e.preventDefault();
}
function fn_facturas_canceladas_proveedor(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    if ($("#tipo_pdf")[0].checked) {
      window.open(
        "../../reportes/facturas_canceladas_proveedor.php?id_empre=" +
        $("#sel_punto_venta").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val() +
        "&id=" +
        $("#sel_usuario").val() +
        "&id_proveedor=" +
        $("#idProv").val(),
        "_blank"
      );
    } else {
      window.open(
        "../../phpexcel/facturas_canceladas_proveedor.php?id_empre=" +
        $("#sel_punto_venta").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val() +
        "&id=" +
        $("#sel_usuario").val() +
        "&id_proveedor=" +
        $("#idProv").val(),
        "_blank"
      );
    }

  }
}
// Por Pagar
// General
function facturas_pagar(e) {
  modal.open({
    content: `<label>Cuentas Pendientes de Pago</label><br>
    <div>
      <label>Tipo Reporte: </label>
      <label><input id="tipo_pdf" type="radio" name="tipo_rep" checked> PDF</label>
      <label><input id="tipo_excel" type="radio" name="tipo_rep"> EXCEL</label>
    </div>
         <label>Punto de Venta</label><select id='sel_punto_venta' style='width:150px;float:right'></select><br>
     <label>Usuario</label><select id='sel_usuario' style='width:150px;float:right'></select><br> 
      <label>Cuenta</label><select name='tipoCobro' id='tipoCobro' style='float: right;padding:2px;'>
      <option value='0'>Internas y Externas</option><option value='1'>Cuentas Internas</option><option value='2'>Cuentas Externas</option></select><br>
     
      <label for='buscarProvrcp2'>Proveedor:</label><input placeholder="CI/RUC/NOMBRE" type='text' name='buscarProvrcp2' id='buscarProvrcp2' style="float:right"/><input type='hidden' id='idProv'/><br>
      
      <label>Fecha Inicio</label> <input type='text' id='inicio' style="float:right"><br>
      <label>Fecha Fin <font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
      <button type='button' class='btn btn-success form-control' id='generarReporteCuentasporPagar' 
      onclick='return fn_facturas_pagar(event)'>Generar Reporte</button>`,
  });
  $("#sel_usuario").load("../factura_venta/usuarios_combos.php");
  $("#sel_punto_venta").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );

  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });

  $("#buscarProvrcp2")[0].addEventListener('input', function (e) {
    if (e.target.value == '') {
      $("#idProv").val("");
    }
  });

  $("#buscarProvrcp2")
    .autocomplete({
      //source: "../../procesos/buscar_proveedor.php",
      source: function (request, response) {
        $("#idProv").val("");
        var data = { term: request.term };
        $.get(
          "../../procesos/buscar_proveedor.php",
          data,
          response,
          "json"
        );
      },
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarProvrcp2").val(ui.item.value);
        $("#idProv").val(ui.item.id_proveedor);
        return false;
      },
      select: function (event, ui) {
        $("#buscarProvrcp2").val(ui.item.value);
        $("#idProv").val(ui.item.id_proveedor);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  e.preventDefault();
}
function fn_facturas_pagar(e) {
  let tipo;
  if ($("#tipoCobro").val() == 1) {
    tipo = "Internas";
  } else if ($("#tipoCobro").val() == 2) {
    tipo = "Externas";
  } else {
    tipo = "";
  }
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    if ($("#tipo_pdf")[0].checked) {
      window.open(
        "../../reportes/facturas_por_pagar.php?id_empre=" +
        $("#sel_punto_venta").val() +
        "&tipo=" +
        tipo +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val() +
        "&id=" +
        $("#sel_usuario").val() +
        "&id_pro=" +
        $("#idProv").val(),
        "_blank"
      );
    } else {
      window.open(
        "../../phpexcel/facturas_por_pagar.php?id_empre=" +
        $("#sel_punto_venta").val() +
        "&tipo=" +
        tipo +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val() +
        "&id=" +
        $("#sel_usuario").val() +
        "&id_pro=" +
        $("#idProv").val(),
        "_blank"
      );
    }

  }
}

// Por Proveedor
function facturas_pagar_proveedor(e) {
  modal.open({
    content: `
   <label>Punto de Venta</label><select id='sel_punto_venta' style='width:150px;float:right'></select><br>
   <label>Usuario</label><select id='sel_usuario' style='width:150px;float:right'></select><br> 
   
<label>Cuenta</label><select name='tipoCobro' id='tipoCobro' style='float: right;padding:2px;'>
    <option value='1'>Cuentas Internas</option><option value='2'>Cuentas Externas</option></select><br>
    <label for='buscarProv'>Proveedor <font color='red'>*</font></label>
    <input type='text' name='buscarProv' id='buscarProv'/><input type='hidden' id='idProv' /><br>
    <label>Fecha Inicio</label><input type='text' id='inicio'style='float:right'><br> 
    <label>Fecha Fin <font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteFacturasporVendedorp' 
      onclick='return fn_facturas_pagar_proveedor(event)'>Generar Reporte</button>`,
  });
  $("#sel_usuario").load("../factura_venta/usuarios_combos.php");
  $("#sel_punto_venta").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );

  $("#buscarProv")
    .autocomplete({
      source: "../../procesos/buscar_proveedor_nombre.php",
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarProv").val(ui.item.value);
        $("#idProv").val(ui.item.id_proveedor);
        return false;
      },
      select: function (event, ui) {
        $("#buscarProv").val(ui.item.value);
        $("#idProv").val(ui.item.id_proveedor);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_facturas_pagar_proveedor(e) {
  let tipo;
  if ($("#tipoCobro").val() == 1) {
    tipo = "Internas";
  } else {
    tipo = "Externas";
  }
  if ($("#fin").val() === "" || $("#buscarProv").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/facturas_por_pagar.php?id_empre=" +
      $("#sel_punto_venta").val() +
      "&id_pro=" +
      $("#idProv").val() +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val() +
      "&tipo=" +
      tipo +
      "&id=" +
      $("#sel_usuario").val(),
      "_blank"
    );
  }
}
// Pagos
// General
function pagos_realizados(e) {
  modal.open({
    content: `<label>Pagos Realizados</label><br>
    <div>
    <label>Tipo Reporte: </label>
    <label><input id="tipo_pdf" type="radio" name="tipo_rep" checked> PDF</label>
    <label><input id="tipo_excel" type="radio" name="tipo_rep"> EXCEL</label>
  </div>
       <label>Punto de Venta</label><select id='sel_punto_venta' style='width:150px;float:right'></select><br>
   <label>Usuario</label><select id='sel_usuario' style='width:150px;float:right'></select><br> 
   
   <label for='buscarProvrcp3'>Proveedor:</label><input placeholder="CI/RUC/NOMBRE" type='text' name='buscarProvrcp3' id='buscarProvrcp3' style="float:right"/><input type='hidden' id='idProv'/><br>
    
   <label>Fecha Inicio</label><input type='text' id='inicio' style="float:right"><br>
    <label>Fecha Fin <font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <label for='tipoCobro' style='padding:6px;'>Cuenta</label>
    <select name='tipoCobro' id='tipoCobro' style='float: right;padding:2px;'><option value='1'>Cuentas Internas</option>
    <option value='2'>Cuentas Externas</option></select><br>
    <button type='button' class='btn btn-success form-control' id='generarReporte_pagosRealizados' 
      onclick='return fn_pagos_realizados(event)'>Generar Reporte</button>`,
  });
  $("#sel_usuario").load("../factura_venta/usuarios_combos.php");
  $("#sel_punto_venta").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );

  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });

  $("#buscarProvrcp3")[0].addEventListener('input', function (e) {
    if (e.target.value == '') {
      $("#idProv").val("");
    }
  });

  $("#buscarProvrcp3")
    .autocomplete({
      //source: "../../procesos/buscar_proveedor.php",
      source: function (request, response) {
        $("#idProv").val("");
        var data = { term: request.term };
        $.get(
          "../../procesos/buscar_proveedor.php",
          data,
          response,
          "json"
        );
      },
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarProvrcp3").val(ui.item.value);
        $("#idProv").val(ui.item.id_proveedor);
        return false;
      },
      select: function (event, ui) {
        $("#buscarProvrcp3").val(ui.item.value);
        $("#idProv").val(ui.item.id_proveedor);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  e.preventDefault();
}
function fn_pagos_realizados(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    if ($("#tipoCobro").val() == 1) {
      if ($("#tipo_pdf")[0].checked) {
        window.open(
          "../../reportes/pagos_realizados_internos.php?id_empre=" +
          $("#sel_punto_venta").val() +
          "&inicio=" +
          $("#inicio").val() +
          "&fin=" +
          $("#fin").val() +
          "&id=" +
          $("#sel_usuario").val() +
          "&id_proveedor=" +
          $("#idProv").val(),
          "_blank"
        );
      } else {
        window.open(
          "../../phpexcel/pagos_realizados.php?id_empre=" +
          $("#sel_punto_venta").val() +
          "&inicio=" +
          $("#inicio").val() +
          "&fin=" +
          $("#fin").val() +
          "&id=" +
          $("#sel_usuario").val() +
          "&id_proveedor=" +
          $("#idProv").val() +
          "&tipo=Internas",
          "_blank"
        );
      }
    } else {
      if ($("#tipo_pdf")[0].checked) {
        window.open(
          "../../reportes/pagos_realizados.php?id_empre=" +
          $("#sel_punto_venta").val() +
          "&inicio=" +
          $("#inicio").val() +
          "&fin=" +
          $("#fin").val() +
          "&id=" +
          $("#sel_usuario").val() +
          "&id_proveedor=" +
          $("#idProv").val(),
          "_blank"
        );
      } else {
        window.open(
          "../../phpexcel/pagos_realizados.php?id_empre=" +
          $("#sel_punto_venta").val() +
          "&inicio=" +
          $("#inicio").val() +
          "&fin=" +
          $("#fin").val() +
          "&id=" +
          $("#sel_usuario").val() +
          "&id_proveedor=" +
          $("#idProv").val() +
          "&tipo=Externas",
          "_blank"
        );
      }

    }
  }
}
// Por Proveedor
function pagos_proveedor(e) {
  modal.open({
    content: `<label>Pagos Proveedor</label><br>
       <label>Punto de Venta</label><select id='sel_punto_venta' style='width:150px;float:right'></select><br>
   <label>Usuario</label><select id='sel_usuario' style='width:150px;float:right'></select><br> 
   
   
    <label for='tipoCobro' style='padding:6px;'>Buscar</label>
    <select name='tipoCobro' id='tipoCobro' style='float: right;padding:2px;'>
    <option value='1'>Cuentas Internas</option><option value='2'>Cuentas Externas</option></select></br>
    <label>Fecha Inicio</label><input type='text' id='inicio' style='float: right;'><br>
    <label>Fecha Fin <font color='red'>*</font></label> <input type='text' id='fin' style='float: right;'><br>
    <label for='buscarProv'>Proveedor <font color='red'>*</font></label>
    <input type='text' name='buscarProv' id='buscarProv'/><input type='hidden' id='idProv' /><br>
    <button type='button' class='btn btn-success form-control' id='generarReportePagosporProveedor' 
    onclick='return fn_pagos_proveedor(event)'>Generar Reporte</button>`,
  });
  $("#sel_usuario").load("../factura_venta/usuarios_combos.php");
  $("#sel_punto_venta").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );

  $("#buscarProv")
    .autocomplete({
      source: "../../procesos/buscar_proveedor_nombre.php",
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarProv").val(ui.item.value);
        $("#idProv").val(ui.item.id_proveedor);
        return false;
      },
      select: function (event, ui) {
        $("#buscarProv").val(ui.item.value);
        $("#idProv").val(ui.item.id_proveedor);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_pagos_proveedor(e) {
  let tipo;
  if ($("#tipoCobro").val() == 1) {
    tipo = "Internos";
  } else {
    tipo = "Externos";
  }
  if ($("#fin").val() === "" || $("#buscarProv").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/pagos_proveedor.php?id_empre=" +
      $("#sel_punto_venta").val() +
      "&id_pro=" +
      $("#idProv").val() +
      "&tipo=" +
      tipo +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val() +
      "&id=" +
      $("#sel_usuario").val(),
      "_blank"
    );
  }
}

// Resuemenes
// Valores a favor clientes nc
function resumen_valor_favor_nc(e) {
  modal.open({
    content: `<label>Resúmen General</label><br>
      <div>
        <label>Tipo Reporte: </label>
        <label><input id="tipo_pdf" type="radio" name="tipo_rep" checked> PDF</label>
        <!--<label><input id="tipo_excel" type="radio" name="tipo_rep"> EXCEL</label>-->
      </div>
      <label>Punto de Venta: </label><select id='sel_punto_venta' style='width:150px;float:right'></select><br>
      <label>Usuario: </label><select id='sel_usuario' style='width:150px;float:right'></select><br> 
      <!--<div style="text-align:center">
        <label><input checked id="chk_cli" name="chk_tpb" type="radio"/> Cliente</labe>
        <label><input id="chk_rut" name="chk_tpb" type="radio"/> Ruta</labe>
        <label><input id="chk_ven" name="chk_tpb" type="radio"/> Vendedor</labe>
      </div>-->

      <div id="div_bcli"><label for='buscarCliente'>Cliente: </label><input placeholder="CI/RUC/NOMBRE" type='text' name='buscarCliente' id='buscarCliente' style="float: right;"/><input type='hidden' id='idCli'/></div>
      <div style="display:none" id="div_brut"><label for='buscarRuta'>Ruta: </label><input placeholder="INGRESE RUTA" type='text' name='buscarRuta' id='buscarRuta' style="float: right;"/><input type='hidden' id='idRuta'/></div>
      <div style="display:none" id="div_bven"><label for='buscarVendedor'>Vendedor: </label><input placeholder="CI/NOMBRE" type='text' name='buscarVendedor' id='buscarVendedor' style="float: right;"/><input type='hidden' id='idVen'/></div>
      
      <label>Fecha Inicio: </label> <input type='text' id='inicio' style="float: right;"><br>
      <label>Fecha Fin: <font color='red'>*</font></label><input type='text' id='fin' style='float: right;'></br>
      <button type='button' class='btn btn-success form-control' id='generarReporteCuentasporCobrar' 
      onclick='return fn_reporte_resumen_valor_favor_nc(event)'>Generar Reporte</button>`,
  });
  $("#sel_usuario").load("../factura_venta/usuarios_combos.php");
  $("#sel_punto_venta").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );

  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });

  $("#buscarCliente")[0].addEventListener('input', function (e) {
    if (e.target.value == '') {
      $("#idCli").val("");
    }
  });
  /*   $("#buscarRuta")[0].addEventListener('input', function (e) {
      if (e.target.value == '') {
        $("#idRuta").val("");
      }
    });
    $("#buscarVendedor")[0].addEventListener('input', function (e) {
      if (e.target.value == '') {
        $("#idVen").val("");
      }
    });
   */
  $("#buscarCliente")
    .autocomplete({
      source: function (request, response) {
        $("#idCli").val("");
        var data = { term: request.term };
        $.get(
          "../../procesos/busquedaCliente_2.php",
          data,
          response,
          "json"
        );
      },
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarCliente").val(ui.item.value);
        $("#idCli").val(ui.item.label);
        return false;
      },
      select: function (event, ui) {
        $("#buscarCliente").val(ui.item.value);
        $("#idCli").val(ui.item.label);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };

  /* $("#buscarRuta")
    .autocomplete({
      source: function (request, response) {
        $("#idRuta").val("");
        var data = { term: request.term };
        $.get(
          "../../procesos/busquedaRuta.php",
          data,
          response,
          "json"
        );
      },
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarRuta").val(ui.item.value);
        $("#idRuta").val(ui.item.label);
        return false;
      },
      select: function (event, ui) {
        $("#buscarRuta").val(ui.item.value);
        $("#idRuta").val(ui.item.label);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };

  $("#buscarVendedor")
    .autocomplete({
      source: function (request, response) {
        $("#idVen").val("");
        var data = { term: request.term };
        $.get(
          "../../procesos/busquedaVendedor.php",
          data,
          response,
          "json"
        );
      },
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarVendedor").val(ui.item.value);
        $("#idVen").val(ui.item.label);
        return false;
      },
      select: function (event, ui) {
        $("#buscarVendedor").val(ui.item.value);
        $("#idVen").val(ui.item.label);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    }; */

  /* let divsb = document.getElementsByName("chk_tpb");
  divsb = Array.from(divsb);
  divsb.forEach(el => {
    $(el).change(function (e) {
      switch (e.target.id) {
        case "chk_cli":
          $("#div_bcli").css({ display: "" });
          $("#div_brut").css({ display: "none" });
          $("#div_bven").css({ display: "none" });
          $("#buscarCliente").val("");
          $("#idCli").val("");
          break;
        case "chk_rut":
          $("#div_bcli").css({ display: "none" });
          $("#div_brut").css({ display: "" });
          $("#div_bven").css({ display: "none" });
          $("#buscarRuta").val("");
          $("#idRuta").val("");
          break;
        case "chk_ven":
          $("#div_bcli").css({ display: "none" });
          $("#div_brut").css({ display: "none" });
          $("#div_bven").css({ display: "" });
          $("#buscarVendedor").val("");
          $("#idVen").val("");
          break;
      }
    });
  }); */
  e.preventDefault();
}
function fn_reporte_resumen_valor_favor_nc(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    /* let divsb = document.getElementsByName("chk_tpb");
    divsb = Array.from(divsb);
    let rchecked = divsb.find(el => el.checked);
    let querytb = "";
    switch (rchecked.id) {
      case "chk_cli":
        querytb = "&id_cliente=" + $("#idCli").val();
        break;
      case "chk_rut":
        querytb = "&id_ruta=" + $("#idRuta").val();
        break;
      case "chk_ven":
        querytb = "&id_vendedor=" + $("#idVen").val();
        break;
    } */
    let querytb = "&id_cliente=" + $("#idCli").val();
    if ($("#tipo_pdf")[0].checked) {
      window.open(
        "../../reportes/resumen_valores_favor_clientes_nc.php?id_empre=" +
        $("#sel_punto_venta").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val() +
        "&id=" +
        $("#sel_usuario").val() +
        querytb,
        "_blank"
      );
    } else {
      /* window.open(
        "../../phpexcel/resumen_cuentas_cobrar" +
        ".php?id_empre=" +
        $("#sel_punto_venta").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val() +
        "&id=" +
        $("#sel_usuario").val() +
        "&tipo=" + tipo +
        querytb,
        "_blank"
      ); */
    }
  }
}
// Valores a favor clientes nc
function resumen_valor_favor_nc_compras(e) {
  modal.open({
    content: `<label>Resúmen General</label><br>
      <div>
        <label>Tipo Reporte: </label>
        <label><input id="tipo_pdf" type="radio" name="tipo_rep" checked> PDF</label>
        <!--<label><input id="tipo_excel" type="radio" name="tipo_rep"> EXCEL</label>-->
      </div>
      <label>Punto de Venta: </label><select id='sel_punto_venta' style='width:150px;float:right'></select><br>
      <label>Usuario: </label><select id='sel_usuario' style='width:150px;float:right'></select><br> 
      <!--<div style="text-align:center">
        <label><input checked id="chk_cli" name="chk_tpb" type="radio"/> Cliente</labe>
        <label><input id="chk_rut" name="chk_tpb" type="radio"/> Ruta</labe>
        <label><input id="chk_ven" name="chk_tpb" type="radio"/> Vendedor</labe>
      </div>-->

      <label for='buscarProvrcp'>Proveedor:</label><input placeholder="CI/RUC/NOMBRE" type='text' name='buscarProvrcp' id='buscarProvrcp' style="float:right"/><input type='hidden' id='idProv'/><br>
      <div style="display:none" id="div_brut"><label for='buscarRuta'>Ruta: </label><input placeholder="INGRESE RUTA" type='text' name='buscarRuta' id='buscarRuta' style="float: right;"/><input type='hidden' id='idRuta'/></div>
      <div style="display:none" id="div_bven"><label for='buscarVendedor'>Vendedor: </label><input placeholder="CI/NOMBRE" type='text' name='buscarVendedor' id='buscarVendedor' style="float: right;"/><input type='hidden' id='idVen'/></div>
      
      <label>Fecha Inicio: </label> <input type='text' id='inicio' style="float: right;"><br>
      <label>Fecha Fin: <font color='red'>*</font></label><input type='text' id='fin' style='float: right;'></br>
      <button type='button' class='btn btn-success form-control' id='generarReporteCuentasporCobrar' 
      onclick='return fn_reporte_resumen_valor_favor_nc_compras(event)'>Generar Reporte</button>`,
  });
  $("#sel_usuario").load("../factura_venta/usuarios_combos.php");
  $("#sel_punto_venta").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );

  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });

  $("#buscarProvrcp")[0].addEventListener('input', function (e) {
    if (e.target.value == '') {
      $("#idProv").val("");
    }
  });

  $("#buscarProvrcp")
    .autocomplete({
      //source: "../../procesos/buscar_proveedor.php",
      source: function (request, response) {
        $("#idProv").val("");
        var data = { term: request.term };
        $.get(
          "../../procesos/buscar_proveedor.php",
          data,
          response,
          "json"
        );
      },
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarProvrcp").val(ui.item.value);
        $("#idProv").val(ui.item.id_proveedor);
        return false;
      },
      select: function (event, ui) {
        $("#buscarProvrcp").val(ui.item.value);
        $("#idProv").val(ui.item.id_proveedor);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  e.preventDefault();
}
function fn_reporte_resumen_valor_favor_nc_compras(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    let querytb = "&id_proveedor=" + $("#idProv").val();
    if ($("#tipo_pdf")[0].checked) {
      window.open(
        "../../reportes/resumen_valores_favor_empresa_nc.php?id_empre=" +
        $("#sel_punto_venta").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val() +
        "&id=" +
        $("#sel_usuario").val() +
        querytb,
        "_blank"
      );
    } else {
      /* window.open(
        "../../phpexcel/resumen_cuentas_cobrar" +
        ".php?id_empre=" +
        $("#sel_punto_venta").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val() +
        "&id=" +
        $("#sel_usuario").val() +
        "&tipo=" + tipo +
        querytb,
        "_blank"
      ); */
    }
  }
}

// Transferencias
// Ingresos
function reporte_ingresos(e) {
  modal.open({
    content: `<label>Ingresos Fechas</label><br>
    <label>Punto de Venta</label><select id='sel_punto_venta' style='width:150px; float:right;'></select><br>
    <label>Fecha Inicio</label> <input type='text' id='inicio' style='float:right'><br>
    <label>Fecha Fin<font color='red'>*</font></label> <input type='text' id='fin' style='float:right'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteIngresos' 
    onclick='return fn_reporte_ingresos(event)'>Generar Reporte</button>`,
  });
  $("#sel_punto_venta").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_reporte_ingresos(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/ingresos.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val() +
      "&id_bodega=" +
      $("#sel_punto_venta").val(),
      "_blank"
    );
  }
}
// Egresos
function reporte_egresos(e) {
  modal.open({
    content: `<label>Egresos Fechas</label><br>
    <label>Punto de Venta</label><select id='sel_punto_venta' style='width:150px; float:right;'></select><br>
    <label>Fecha Inicio</label> <input type='text' id='inicio' style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteEgresos' 
    onclick='return fn_reporte_egresos(event)'>Generar Reporte</button>`,
  });
  $("#sel_punto_venta").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_reporte_egresos(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/egresos.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val() +
      "&id_bodega=" +
      $("#sel_punto_venta").val(),
      "_blank"
    );
  }
}
// Gastos
// Por Factura
function gastos(e) {
  modal.open({
    content: `<label>Gastos por Proveedor</label><br> 
    <label>Fecha Inicio</label> <input type='text' id='inicio'><br>
    <label>Fecha Fin<font color='red'>*</font></label> <input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarGastos' 
    onclick='return fn_gastos(event)'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_gastos(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/gastos_realizados.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Generales
function gastos_general(e) {
  modal.open({
    content: `<label>Gastos Generales</label><br>
    <input type='radio' name='group1' id='excel' value='Reporte EXCEL'> <label for='excel'>Reporte Excel</label><br>
    <input type='radio' name='group1' id='pdf' value='Reporte Pdf' checked> <label for='pdf'>Reporte Pdf</label> <br>
    <label>Fecha Inicio</label> <input type='text' id='inicio' style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarGastosGeneral' 
    onclick='return fn_gastos_general(event)'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_gastos_general(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    if ($("#excel").is(":checked")) {
      if ($("#inicio").val() === "") {
        valores_incompletos();
      } else {
        window.open(
          "../../phpexcel/gastoAcumulado.php?inicio=" +
          $("#inicio").val() +
          "&fin=" +
          $("#fin").val(),
          "_blank"
        );
      }
    } else {
      window.open(
        "../../reportes/gasto_acumulado.php?inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val(),
        "_blank"
      );
    }
  }
}
// Internos Fechas
function gastos_internos(e) {
  modal.open({
    content: `<label for='pdf'>Gastos Internos</label><br> 
    <label>Fecha Inicio</label> <input type='text' id='inicio'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarGastoInternoFechas' 
    onclick='return fn_gastos_internos(event)'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_gastos_internos(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/gastos_fechas.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Fletes
// entre Fechas
function fletes_fechas(e) {
  modal.open({
    content: `<label>Fletes Fechas</label> <br>
    <label>Fecha Inicio<font color='red'>*</font></label> <input type='text' id='inicio' style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteVentasporCliente' 
    onclick='return fn_fletes_fechas(event)'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_fletes_fechas(e) {
  if ($("#fin").val() === "" || $("#inicio").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/fletes_fechas.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Por Vehiculos
function fletes_transporte(e) {
  modal.open({
    content: `<label>Fletes por Vehiculo</label><br>
    <label>Fecha Inicio<font color='red'>*</font></label><input type='text' id='inicio' style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <label for='buscarVehiculo'>Placa: </label><input type='text' name='buscarVehiculo' id='buscarVehiculo'style='float: right;' />
    <input type='hidden' id='idvehiculo' style='float: right;'/><br>
    <input type='text' id='idnombre' size='35'/><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteVentasporCliente' 
    onclick='return fn_fletes_transporte(event)'>Generar Reporte</button>`,
  });
  $("#buscarVehiculo")
    .autocomplete({
      source: "../../procesos/vehiculos_combos.php",
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarVehiculo").val(ui.item.value);
        $("#idvehiculo").val(ui.item.id_vehiculo);
        $("#idnombre").val(ui.item.modelo);
        return false;
      },
      select: function (event, ui) {
        $("#buscarVehiculo").val(ui.item.value);
        $("#idvehiculo").val(ui.item.id_vehiculo);
        $("#idnombre").val(ui.item.modelo);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_fletes_transporte(e) {
  if ($("#fin").val() === "" || $("#inicio").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/fletes_transporte.php?idv=" +
      $("#idvehiculo").val() +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Por Conductor
function fletes_conductor(e) {
  modal.open({
    content: `<label>Por Conductor</label><br>
    <label>Fecha Inicio<font color='red'>*</font></label><input type='text' id='inicio' style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <label for='buscarConductor'>Ced Conductor: </label><input type='text' name='buscarConductor' id='buscarConductor'style='float: right;' /><input type='hidden' id='idconductor' style='float: right;'/><br>
    <input type='text' id='idnombre' size='35'/><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteVentasporCliente' 
    onclick='return fn_fletes_conductor(event)'>Generar Reporte</button>`,
  });
  $("#buscarConductor")
    .autocomplete({
      source: "../../procesos/conductores_combos.php",
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarConductor").val(ui.item.value);
        $("#idconductor").val(ui.item.id_conductor);
        $("#idnombre").val(ui.item.nombres);
        return false;
      },
      select: function (event, ui) {
        $("#buscarConductor").val(ui.item.value);
        $("#idconductor").val(ui.item.id_conductor);
        $("#idnombre").val(ui.item.nombres);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_fletes_conductor(e) {
  if ($("#fin").val() === "" || $("#inicio").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/fletes_conductor.php?idc=" +
      $("#idconductor").val() +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Por Cliente
function fletes_cliente(e) {
  modal.open({
    content: `<label for='pdf'>Reporte Pdf</label><br>
    <label>Fecha Inicio<font color='red'>*</font></label> <input type='text' id='inicio' style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label> <input type='text' id='fin' style='float: right;'><br>
    <label for='buscarCliente'>Ced Cliente: </label><input type='text' name='buscarCliente' id='buscarCliente'style='float: right;' />
    <input type='hidden' id='idcli' style='float: right;'/><br>
    <input type='text' id='idnombre' size='35'/><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteVentasporCliente' 
    onclick='return fn_fletes_cliente(event)'>Generar Reporte</button>`,
  });
  $("#buscarCliente")
    .autocomplete({
      source: "../../procesos/clientes_combos.php",
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarCliente").val(ui.item.value);
        $("#idcli").val(ui.item.id_cliente);
        $("#idnombre").val(ui.item.nombres_cli);
        return false;
      },
      select: function (event, ui) {
        $("#buscarCliente").val(ui.item.value);
        $("#idcli").val(ui.item.id_cliente);
        $("#idnombre").val(ui.item.nombres_cli);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_fletes_cliente(e) {
  if ($("#fin").val() === "" || $("#inicio").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/fletes_cliente.php?id=" +
      $("#idcli").val() +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Balances
// Estado patrimonio
function estado_patrimonio(e) {
  modal.open({
    content: `<label>Estado de Evolución de Patrimonio</label><br>
    <label>Fecha Inicio</label> <input type='text' id='inicio'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteEstadoPatrimonio' 
    onclick='return fn_estado_patrimonio(event)'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_estado_patrimonio(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/estado_patrimonio.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}

// Comprobacion
function balance_comprobacion(e) {
  modal.open({
    content: `<label>Balance de Comprobación</label><br>
    <label>Fecha Inicio</label> <input type='text' id='inicio'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteBalComprobacion' 
    onclick='return fn_reporte_bal_comprobacion(event)'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_reporte_bal_comprobacion(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/balance_comprobacion.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Resultados
function balance_resultados(e) {
  modal.open({
    content: `<label>Balance de Resultados</label><br> 
    <label>Fecha Inicio</label> <input type='text' id='inicio'><br> 
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteBalResultados' 
    onclick='return fn_reporte_bal_resultados(event)'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_reporte_bal_resultados(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/balance_resultados.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// General
function balance_general(e) {
  modal.open({
    content: `<label>Balance General</label><br> 
    <label>Fecha Inicio</label><input type='text' id='inicio'><br>
    <label>Fecha Fin<font color='red'>*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteBalGeneral' 
    onclick='return fn_reporte_bal_general(event)'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_reporte_bal_general(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/balance_general.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Contabilidad
// Cuenta Contable
function reporte_ventas_producto(e) {
  modal.open({
    content:
      `<input type='radio' name='group1' id='pdf' value='Reporte Pdf' checked> <label for='pdf'>Reporte en PDF</label><br>
      <input type='radio' name='group1' id='excel' value='Reporte en Excel'><label for='excel'>Reporte en Excel</label><br>
      <label>Punto de Venta</label>
      <select id='sel_resu_fact_ventas' style='width:150px;float:right'></select><br> 
      <label for='buscarCliente'>Cliente: </label><input placeholder="CI/RUC/NOMBRE" type='text' name='buscarCliente' id='buscarCliente' style="float: right;"/><input type='hidden' id='idCli'/><br>
      <label>Fecha Inicio</label> 
      <input type='text' id='inicio'style='float: right;'><br>
      <label>Fecha Fin</label> <input type='text' id='fin' style='float: right;'><br>
      <a 'id='generar' style='cursor:pointer;font-size:12px;margin-left:40px' class='generarReporteVenta' onclick='return fn_reporte_ventas_producto(event)' href='#'>Generar Reporte</a>`,
  });
  $("#sel_resu_fact_ventas").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );
  $(".generarReporteVenta").button();
  $("#inicio").datepicker({
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  $("#buscarCliente")[0].addEventListener('input', function (e) {
    if (e.target.value == '') {
      $("#idCli").val("");
    }
  });

  $("#buscarCliente")
    .autocomplete({
      source: function (request, response) {
        $("#idCli").val("");
        var data = { term: request.term };
        $.get(
          "../../procesos/busquedaCliente_2.php",
          data,
          response,
          "json"
        );
      },
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarCliente").val(ui.item.value);
        $("#idCli").val(ui.item.label);
        return false;
      },
      select: function (event, ui) {
        $("#buscarCliente").val(ui.item.value);
        $("#idCli").val(ui.item.label);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  e.preventDefault();
}

function fn_reporte_ventas_producto(e) {
  var hoja = $("#tam_hoja").val();
  if ($("#pdf")[0].checked) {
    if ($("#matriz").is(":checked")) {
      window.open(
        "../../reportes/resumenVentaProductos.php?id=" +
        $("#sel_resu_fact_ventas").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val() +
        "&tipo=venta",
        "_blank"
      );
    } else {
      if (!!!$("#idCli")) {
        window.open(
          "../../reportes/resumenVentaProductos.php?id=" +
          $("#sel_resu_fact_ventas").val() +
          "&inicio=" +
          $("#inicio").val() +
          "&fin=" +
          $("#fin").val() +
          "&tipo=venta",
          "_blank"
        );
      } else {
        window.open(
          "../../reportes/resumenVentaProductosCliente.php?id=" +
          $("#sel_resu_fact_ventas").val() +
          "&inicio=" +
          $("#inicio").val() +
          "&fin=" +
          $("#fin").val() +
          "&tipo=venta" +
          "&id_cliente=" + $("#idCli").val(),
          "_blank"
        );
      }
    }
  } else if ($("#excel")[0].checked) {
    if ($("#matriz").is(":checked")) {
      window.open(
        "../../phpexcel/resumenVentaProductos.php?id=" +
        $("#sel_resu_fact_ventas").val() +
        "&inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val() +
        "&tipo=venta",
        "_blank"
      );
    } else {
      if (!!!$("#idCli")) {
        window.open(
          "../../phpexcel/resumenVentaProductos.php?id=" +
          $("#sel_resu_fact_ventas").val() +
          "&inicio=" +
          $("#inicio").val() +
          "&fin=" +
          $("#fin").val() +
          "&tipo=venta",
          "_blank"
        );
      } else {
        window.open(
          "../../phpexcel/resumenVentaProductosCliente.php?id=" +
          $("#sel_resu_fact_ventas").val() +
          "&inicio=" +
          $("#inicio").val() +
          "&fin=" +
          $("#fin").val() +
          "&tipo=venta" +
          "&id_cliente=" + $("#idCli").val(),
          "_blank"
        );
      }
    }
  }

}
function ventana_cuenta_contable(e) {
  modal.open({
    content: `<label>Transacciones de Cuentas</label><br>
    <label>Punto de Venta</label><select id='sel_punto_venta' style='width:150px;float:right'></select><br>

    <label for='buscarPro'>Buscar</label><input type='text' name='buscarPro' id='buscarPro' style='float: right;'/>
    <input type='hidden' id='idPro' /><br>
    <label>Fecha Inicio</label> <input type='text' id='inicio' style='float: right;'><br>
    <label>Fecha Fin<font color='red'>*</font></label> <input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteCContable' 
    onclick='return fn_reporte_cuenta_contable(event)'>Generar Reporte</button>`,
  });
  $("#sel_punto_venta").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );

  $("#buscarPro").focus(function (e) {
    $(this).data("ui-autocomplete").search($(this).val());
  });

  $("#buscarPro")
    .autocomplete({
      source: "../../procesos/retornar_plan_cuentas.php",
      minLength: 0,
      focus: function (event, ui) {
        /*  $("#buscarPro").val(ui.item.value);
         $("#idPro").val(ui.item.id_plan_cuentas); */
        return false;
      },
      select: function (event, ui) {
        $("#buscarPro").val(ui.item.value);
        $("#idPro").val(ui.item.id_plan_cuentas);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_reporte_cuenta_contable(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/cuenta_contable.php?id_empre=" +
      $("#sel_punto_venta").val() +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val() +
      "&cod=" +
      $("#idPro").val(),
      "_blank"
    );
  }
}
// Libro Diario
function ventana_libro_diario(e) {
  modal.open({
    content: `
    <label>Punto de Venta</label><select id='sel_punto_venta' style='width:150px;float:right'></select><br>
        <label for='sel_tipo_trans'>Tipo</label><select id="sel_tipo_trans" ></select><br>
        <label>Fecha Inicio</label> <input type='text' id='inicio' /><br> 
        <label>Fecha Fin<font color="red">*</font></label> <input type='text' id='fin' style='float: right;' /><br>
        <button type="button" class="btn btn-success form-control" id='generarReporteLibroDiario' 
        onclick='return fn_reporte_libro_diario(event)'>Generar</button>`,
  });
  $("#sel_tipo_trans").load("../../procesos/tipo_trans.php");
  $("#sel_punto_venta").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: (selectedDate) => {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: (selectedDate) => {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_reporte_libro_diario(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/libro_diario.php?id_empre=" +
      $("#sel_punto_venta").val() +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val() +
      "&tipo=" +
      $("#sel_tipo_trans").val(),
      "_blank"
    );
  }
}
// Mayor General
function ventana_mayor_general(e) {
  modal.open({
    content: `<label>Buscar Plan de Cuentas:</label><br/>
     <label>Punto de Venta</label><select id='sel_punto_venta' style='width:150px;float:right'></select><br>

        <label for='buscarPCI'>Desde</label><input type='text' name='buscarPCI' id='buscarPCI' style='float: right;'/>
        <input type='hidden' id='idPCInicio' /><br>
        <label for='buscarPCF'>Hasta <font color="red">*</font></label><input type='text' name='buscarPCF' id='buscarPCF' style='float: right;'/>
        <input type='hidden' id='idPCFin' /><br>
        <label>Entre las Fechas:</label><br/>
        <label>Fecha Inicio</label> <input type='text' id='inicio'><br> 
        <label>Fecha Fin <font color="red">*</font></label> <input type='text' id='fin' style='float: right;'><br>
        <button type='button' class='btn btn-success form-control' id='generarReporteMayorGeneral' 
        onclick='return fn_reporte_mayor_general(event)'>Generar</button>`,
  });

  $("#sel_punto_venta").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );

  $("#buscarPCI").focus(function (e) {
    $(this).data("ui-autocomplete").search($(this).val());
  });


  $("#buscarPCI")
    .autocomplete({
      source: "../../procesos/retornar_plan_cuentas.php",
      minLength: 0,
      focus: function (event, ui) {
        /*  $("#buscarPCI").val(ui.item.value);
         $("#idPCInicio").val(ui.item.id_plan_cuentas); */
        return false;
      },
      select: function (event, ui) {
        $("#buscarPCI").val(ui.item.value);
        $("#idPCInicio").val(ui.item.id_plan_cuentas);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };

  $("#buscarPCF").focus(function (e) {
    $(this).data("ui-autocomplete").search($(this).val());
  });
  $("#buscarPCF")
    .autocomplete({
      source: "../../procesos/retornar_plan_cuentas.php",
      minLength: 0,
      focus: function (event, ui) {
        /*  $("#buscarPCF").val(ui.item.value);
         $("#idPCFin").val(ui.item.id_plan_cuentas); */
        return false;
      },
      select: function (event, ui) {
        $("#buscarPCF").val(ui.item.value);
        $("#idPCFin").val(ui.item.id_plan_cuentas);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: (selectedDate) => {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: (selectedDate) => {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_reporte_mayor_general(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/mayor_general.php?id_empre=" +
      $("#sel_punto_venta").val() +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val() +
      "&id_inicio=" +
      $("#idPCInicio").val() +
      "&id_fin=" +
      $("#idPCFin").val(),
      "_blank"
    );
  }
}
// Saldos Cartera
function ventana_saldo_cuenta(e) {
  modal.open({
    content: `<label>Saldos de Cuentas</label><br/>
        <label for='buscarPC'>Cuenta Cartera<font color="red">*</font></label>
        <select id="idC" class='form-control'>
        <option selected value="cobrar">Cobrar</option>
        <option value="pagar">Pagar</option></select>
        <label>Entre las Fechas:</label><br/>
        <label>Fecha Inicio</label> <input type='text' id='inicio'><br> 
        <label>Fecha Fin <font color="red">*</font></label> <input type='text' id='fin' style='float: right;'><br>
        <button type='button' class='btn btn-success form-control' id='generarReporteSaldoCta' 
        onclick='return fn_reporte_saldo_cuenta(event)'>Generar</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: (selectedDate) => {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: (selectedDate) => {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_reporte_saldo_cuenta(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/saldos_cuenta.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val() +
      "&id=" +
      $("#idC").val(),
      "_blank"
    );
  }
}
// Estados de Cuenta
function estadosCuenta(e) {
  modal.open({
    content: `<label>Estados de Cuentas Contables</label><br/>
    <label>Seleccione</label><select id='tipo_cuenta' class='form-control'>
    <option selected value='proveedores'>Proveedores</option>
    <option value='clientes'>Clientes</option></select></br>
    <button type='button' id='generarEstadoCuenta' class='btn btn-success form-control'
    onclick='return fn_estadosCuenta(event);'>Generar</button>`,
  });
  e.preventDefault();
}

function fn_estadosCuenta(e) {
  if ($("#tipo_cuenta").val() === "proveedores") {
    estadosCuentaProveedores(e);
  } else {
    estadosCuentaClientes(e);
  }
}
// Proveedores
function estadosCuentaProveedores(e) {
  modal.open({
    content: `<label for='buscarProv' style='padding:6px;'>Buscar Nombre Proveedor <font color='red'>*</font></label>
    <input type='text' name='buscarProv' id='buscarProv' class='form-control' />
    <input type='hidden' id='idProv' /><br><label style='padding:6px;'>Fecha Inicio</label> 
    <input type='text' id='inicio' style='padding:2px;'><br> 
    <label style='padding:6px;'>Fecha Fin <font color='red'>*</font></label> 
    <input type='text' id='fin' style='float: right;padding:2px;'><br> 
    <button class='btn btn-success form-control' id='generarReporte_estadosCuentaProveedores' 
    onclick='return fn_estadosCuentaProveedores(event)'>Generar Reporte</button>`,
  });
  $("#buscarProv")
    .autocomplete({
      source: "../../procesos/buscar_proveedor.php",
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarProv").val(ui.item.value);
        $("#idProv").val(ui.item.id_proveedor);
        return false;
      },
      select: function (event, ui) {
        $("#buscarProv").val(ui.item.value);
        $("#idProv").val(ui.item.id_proveedor);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_estadosCuentaProveedores(e) {
  if ($("#fin").val() === "" && $("#idProv").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/estadoCuentaProveedores.php?id=" +
      $("#idProv").val() +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Clientes
function estadosCuentaClientes(e) {
  modal.open({
    content: `<label>Punto de Venta</label><select id='sel_punto_venta' style='width:150px;float:right'></select><br>


<label for='buscarCli' style='padding:6px;'>Buscar Nombre Cliente <font color='red'>*</font></label>
      <input type='text' name='buscarCli' id='buscarCli' class='form-control' />
      <input type='hidden' id='idCli' /><br><label style='padding:6px;'>Fecha Inicio</label> 
      <input type='text' id='inicio' style='padding:2px;'><br> 
      <label style='padding:6px;'>Fecha Fin <font color='red'>*</font></label> 
      <input type='text' id='fin' style='float: right;padding:2px;'><br>
      <button class='btn btn-success form-control' id='generarReporte_estadosCuentaClientes' 
      onclick='return fn_estadosCuentaClientes(event)'>Generar Reporte</button>`,
  });
  $("#sel_punto_venta").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );
  $("#buscarCli")
    .autocomplete({
      source: "../../procesos/busquedaCliente.php",
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarCli").val(ui.item.value);
        $("#idCli").val(ui.item.id_cliente);
        return false;
      },
      select: function (event, ui) {
        $("#buscarCli").val(ui.item.value);
        $("#idCli").val(ui.item.id_cliente);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_estadosCuentaClientes(e) {
  if ($("#fin").val() === "" && $("#idCli").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/estadoCuentaClientes.php?id_empre=" +
      $("#sel_punto_venta").val() +
      "&id=" +
      $("#idCli").val() +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Plan de Cuentas
function ventana_plan_cuenta(e) {
  modal.open({
    content: `<label>Cuentas Contables</label><br/>
    <label>Seleccione </label>
    <select id='sel_tipo_plan' style='float: right;padding:2px;'><option selected value='0'>TODAS</option>
    <option value='M'>M</option><option value='G'>G</option></select><br>
    <button type='button' id='generarPlanCuenta' class='btn btn-success form-control'
    onclick='return fn_plan_cuenta(event);'>Generar</button>`,
  });
  e.preventDefault();
}
function fn_plan_cuenta(e) {
  window.open(
    "../../reportes/reporte_plan_cuentas.php?tipo=" + $("#sel_tipo_plan").val(),
    "_blank"
  );
}
// Giras Proveedor
function ventana_giras_proveedor(e) {
  modal.open({
    content: `<label>Giras Proveedores</label><br>
<label>Punto de Venta</label><select id='sel_punto_venta' style='width:150px;float:right'></select><br>

<label style='padding:6px;'>Fecha Inicio</label> 
    <input type='text' id='inicio' style='padding:2px;'><br> 
    <label style='padding:6px;'>Fecha Fin <font color='red'>*</font></label> 
    <input type='text' id='fin' style='float: right;padding:2px;'><br>
    <button type='button' id='generarPlanCuenta' class='btn btn-success form-control'
    onclick='return fn_giras_proveedor(event);'>Generar</button>`,
  });
  $("#sel_punto_venta").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );

  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_giras_proveedor(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/reporte_giras_proveedor.php?id_empre=" +
      $("#sel_punto_venta").val() +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Conciliacion Bancaria
function ventana_conciliacion_bancaria(e) {
  modal.open({
    content: `<label>Conciliación Bancaria</label><br>
    <label>Punto de Venta</label><select id='sel_punto_venta' style='width:150px;float:right'></select><br>

      <label for='buscarPro'>Buscar</label><input type='text' name='buscarPro' id='buscarPro' style='float: right;'/>
    <input type='hidden' id='id_plan' /><br>
    <label style='padding:6px;'>Fecha Inicio</label> 
    <input type='text' id='inicio' style='float: right;padding:2px;'></br> 
    <label style='padding:6px;'>Fecha Fin <font color='red'>*</font></label> 
    <input type='text' id='fin' style='float: right;padding:2px;'></br>
    <button type='button' id='generarPlanCuenta' class='btn btn-success form-control'
    onclick='return fn_conciliacion_bancaria(event);'>Generar</button>`,
  });
  $("#sel_punto_venta").load(
    "../factura_venta/punto_venta_combos_inactivo.php"
  );

  $("#buscarPro").focus(function (e) {
    $(this).data("ui-autocomplete").search($(this).val());
  });

  $("#buscarPro")
    .autocomplete({
      source: "../../procesos/retornar_plan_cuentas_cb.php",
      minLength: 0,
      focus: function (event, ui) {
        /*  $("#buscarPro").val(ui.item.value);
         $("#id_plan").val(ui.item.id_plan_cuentas); */
        return false;
      },
      select: function (event, ui) {
        $("#buscarPro").val(ui.item.value);
        $("#id_plan").val(ui.item.id_plan_cuentas);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_conciliacion_bancaria(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/conciliacion_reporte.php?id_empre=" +
      $("#sel_punto_venta").val() +
      "&id_plan=" +
      $("#id_plan").val() +
      "&inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val() +
      "&buscarPro=" +
      $("#buscarPro").val(),
      "_blank"
    );
  }
}
// Ordenes de Producción
// Ordenes General
function ordenes_general(e) {
  modal.open({
    content: `<label>Ordenes de Producción General</label><br>
    <label>Fecha Inicio</label> <input type='text' id='inicio' style='float: right;'><br> 
    <label>Fecha Fin</label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteBalGeneral' 
    onclick='return fn_reporte_ord_general(event)'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_reporte_ord_general(e) {
  if ($("#excel").is(":checked")) {
    window.open(
      "../../phpexcel/ordenesGeneral.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  } else {
    window.open(
      "../../reportes/ordenesGeneral.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// No aprobadas
function ordenes_des(e) {
  modal.open({
    content: `<label>Ordenes no Aprobadas</label><br> 
    <label>Fecha Inicio</label> <input type='text' id='inicio'><br> 
    <label>Fecha Fin</label> <input type='text' id='fin' style='float: right;'><br>
    <a type='button' class='btn btn-success form-control' class='generarReporteBalGeneral' 
    onclick='return fn_reporte_ord_des(event)' href='#'>Generar Reporte</a>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_reporte_ord_des(e) {
  if ($("#excel").is(":checked")) {
    window.open(
      "../../phpexcel/ordenesGeneralDes.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  } else {
    window.open(
      "../../reportes/ordenesGeneralDes.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Recetas General
function recetas_general(e) {
  modal.open({
    content: `<label>Recetas General</label> <br> 
    <label>Fecha Inicio</label> <input type='text' id='inicio'><br> 
    <label>Fecha Fin</label> <input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarReporteBalGeneral' 
    onclick='return fn_reporte_rec_general(event)' href='#'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_reporte_rec_general(e) {
  if ($("#excel").is(":checked")) {
    window.open(
      "../../phpexcel/ordenesGeneral.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  } else {
    window.open(
      "../../reportes/ordenesGeneral.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Proformas
function proformas(e) {
  modal.open({
    content: `<label>Proformas</label> <br>
    <label for='buscarProforma' style='padding:6px;'>Buscar</label>
    <input type='text' name='buscarProforma' id='buscarProforma' style='float: right;padding:2px;' />
    <input type='hidden' id='idProf' /><br>
    <button type='button' class='btn btn-success form-control' id='generarReporte_proforma' 
    onclick='return fn_proformas(event)'>Generar Reporte</button>`,
  });
  $("#buscarProforma")
    .autocomplete({
      source: "../../procesos/buscar_proforma.php",
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarProforma").val(ui.item.value);
        $("#idProf").val(ui.item.id_proforma);
        return false;
      },
      select: function (event, ui) {
        $("#buscarProforma").val(ui.item.value);
        $("#idProf").val(ui.item.id_proforma);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  e.preventDefault();
}
function fn_proformas(e) {
  window.open("../../reportes/proforma.php?id=" + $("#idProf").val(), "_blank");
}
//
////////////////////////////
function orden_produccion(e) {
  modal.open({
    content:
      "<label for='buscar_orden' style='padding:6px;'>Buscar</label><input type='text' name='buscar_orden' id='buscar_orden' style='float: right;padding:2px;' /><input type='hidden' id='idOrden' /><br><input type='radio' name='group1' id='pdf' value='Reporte Pdf' checked> <label for='pdf'>Reporte Pdf</label> <br><a type='button' class='btn btn-success form-control' class='generarReporte_Orden' onclick='return fn_orden_produccion(event)' href='#'>Generar Reporte</a>",
  });
  $("#buscar_orden")
    .autocomplete({
      source: "../../procesos/buscar_orden.php",
      minLength: 1,
      focus: function (event, ui) {
        $("#buscar_orden").val(ui.item.value);
        $("#idOrden").val(ui.item.id_ordenes);
        return false;
      },
      select: function (event, ui) {
        $("#buscar_orden").val(ui.item.value);
        $("#idOrden").val(ui.item.id_ordenes);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  $(".generarReporte_Orden").button();
  e.preventDefault();
}

function fn_orden_produccion(e) {
  var hoja = $("#tam_hoja").val();
  var tipo;
  if ($("#excel").is(":checked")) {
    //window.open('../phpexcel/reporte_agrupados_prov.php?id='+$('#idProv').val(), '_blank');
  } else {
    window.open(
      "../../reportes/orden_produccion.php?id=" + $("#idOrden").val(),
      "_blank"
    );
  }
}

/////////////////////////
function lista_proformas(e) {
  modal.open({
    content:
      "<input type='radio' name='group1' id='pdf' value='Reporte Pdf' checked> <label for='pdf'>Reporte Pdf</label> <br> <label>Fecha Inicio</label> <input type='text' id='inicio'><br> <label>Fecha Fin</label> <input type='text' id='fin' style='float: right;'><br><br><a type='button' class='btn btn-success form-control' class='generarReporteListaProformas' onclick='return fn_lista_proformas(event)' href='#'>Generar Reporte</a>",
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}

function fn_lista_proformas(e) {
  var hoja = $("#tam_hoja").val();
  if ($("#excel").is(":checked")) {
    window.open(
      "../../phpexcel/resumenFacturasCompras.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  } else {
    window.open(
      "../../reportes/lista_proformas.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
////////////////////////////
function equipos_recibidos(e) {
  modal.open({
    content:
      "<input type='radio' name='group1' id='pdf' value='Reporte Pdf' checked> <label for='pdf'>Reporte Pdf</label> <br> <label>Fecha Inicio</label> <input type='text' id='inicio'><br> <label>Fecha Fin</label> <input type='text' id='fin' style='float: right;'><br><br><a type='button' class='btn btn-success form-control' class='generarReporteRecibidos' onclick='return fn_equipos_recibidos(event)' href='#'>Generar Reporte</a>",
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_equipos_recibidos(e) {
  var hoja = $("#tam_hoja").val();
  if ($("#excel").is(":checked")) {
    window.open(
      "../../phpexcel/resumenFacturasCompras.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  } else {
    window.open(
      "../../reportes/reporteCliente.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
/////////////////////////
function equipos_reparados(e) {
  modal.open({
    content:
      "<input type='radio' name='group1' id='pdf' value='Reporte Pdf' checked> <label for='pdf'>Reporte Pdf</label> <br> <label>Fecha Inicio</label> <input type='text' id='inicio'><br> <label>Fecha Fin</label> <input type='text' id='fin' style='float: right;'><br><br><a type='button' class='btn btn-success form-control' class='generarReporteReparados' onclick='return fn_equipos_reparados(event)' href='#'>Generar Reporte</a>",
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_equipos_reparados(e) {
  var hoja = $("#tam_hoja").val();
  if ($("#excel").is(":checked")) {
    window.open(
      "../../phpexcel/resumenFacturasCompras.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  } else {
    window.open(
      "../../reportes/reporteReparados.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
/////////////////////////
function equipos_en_reparacion(e) {
  modal.open({
    content:
      "<input type='radio' name='group1' id='pdf' value='Reporte Pdf' checked> <label for='pdf'>Reporte Pdf</label> <br> <label>Fecha Inicio</label> <input type='text' id='inicio'><br> <label>Fecha Fin</label> <input type='text' id='fin' style='float: right;'><br><br><a type='button' class='btn btn-success form-control' class='generarReporteReparacion' onclick='return fn_equipos_en_reparacion(event)' href='#'>Generar Reporte</a>",
  });
  $(".generarReporteReparacion").button();
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}

function fn_equipos_en_reparacion(e) {
  var hoja = $("#tam_hoja").val();
  if ($("#excel").is(":checked")) {
    window.open(
      "../../phpexcel/resumenFacturasCompras.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  } else {
    window.open(
      "../../reportes/reporteClienteReparacion.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
/////////////////////////
function equipos_entregados(e) {
  modal.open({
    content:
      "<input type='radio' name='group1' id='pdf' value='Reporte Pdf' checked> <label for='pdf'>Reporte Pdf</label> <br> <label>Fecha Inicio</label> <input type='text' id='inicio'><br> <label>Fecha Fin</label> <input type='text' id='fin' style='float: right;'><br><br><a type='button' class='btn btn-success form-control' class='generarReporteEntregados' onclick='return fn_equipos_entregados(event)' href='#'>Generar Reporte</a>",
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_equipos_entregados(e) {
  var hoja = $("#tam_hoja").val();
  if ($("#excel").is(":checked")) {
    window.open(
      "../../phpexcel/resumenFacturasCompras.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  } else {
    window.open(
      "../../reportes/reporteEntregados.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
////////////////////////////////////////////7
//////NOMINA
function reporte_nomina(e) {
  var anio = new Date().getFullYear();
  var anios = "";
  for (var i = 2000; i <= anio; i++) {
    anios = "<option value='" + i + "'>" + i + "</option>" + anios;
  }
  var nombre = "ATS01" + anio;
  var meses = "";
  meses = meses + "<option value='01'>Enero</option>";
  meses = meses + "<option value='02'>Febrero</option>";
  meses = meses + "<option value='03'>Marzo</option>";
  meses = meses + "<option value='04'>Abril</option>";
  meses = meses + "<option value='05'>Mayo</option>";
  meses = meses + "<option value='06'>Junio</option>";
  meses = meses + "<option value='07'>Julio</option>";
  meses = meses + "<option value='08'>Agosto</option>";
  meses = meses + "<option value='09'>Septiembre</option>";
  meses = meses + "<option value='10'>Octubre</option>";
  meses = meses + "<option value='11'>Noviembre</option>";
  meses = meses + "<option value='12'>Diciembre</option>";
  modal.open({
    content:
      "<label>Año: </label><select name='aniosnomina' id='aniosnomina' onchange='cambio_nomina(event)' style='float: center;padding:2px;'>" +
      anios +
      "</select><br/><label>Mes: </label><select name='mesesnomina' id='mesesnomina' onchange='cambio_nomina(event)' style='float: center;padding:2px;'>" +
      meses +
      "</select><br><label> </label> <input type='hidden' id='nombre'  value='" +
      nombre +
      "' style='float: right;'><br><br><a type='button' class='btn btn-success form-control' class='generarXml' onclick='return fn_nomina(event)' href='#'>Generar</a>",
  });
  $(".generarXml").button();
  e.preventDefault();
}

function fn_nomina(e) {
  window.open(
    "../../reportes/reporte_nomina.php?anio=" +
    $("#aniosnomina").val() +
    "&mes=" +
    $("#mesesnomina").val(),
    "_blank"
  );
}
/////////////////////////////
function ats_ventana(e) {
  var anio = new Date().getFullYear();
  var anios = "";
  for (var i = 2000; i <= anio; i++) {
    anios = "<option value='" + i + "'>" + i + "</option>" + anios;
  }
  var nombre = "ATS01" + anio;
  var meses = "";
  meses = meses + "<option value='01'>Enero</option>";
  meses = meses + "<option value='02'>Febrero</option>";
  meses = meses + "<option value='03'>Marzo</option>";
  meses = meses + "<option value='04'>Abril</option>";
  meses = meses + "<option value='05'>Mayo</option>";
  meses = meses + "<option value='06'>Junio</option>";
  meses = meses + "<option value='07'>Julio</option>";
  meses = meses + "<option value='08'>Agosto</option>";
  meses = meses + "<option value='09'>Septiembre</option>";
  meses = meses + "<option value='10'>Octubre</option>";
  meses = meses + "<option value='11'>Noviembre</option>";
  meses = meses + "<option value='12'>Diciembre</option>";
  modal.open({
    content:
      "<label>Año: </label><select name='aniosATS' id='aniosATS' onchange='cambio_ats(event)' style='float: center;padding:2px;'>" +
      anios +
      "</select><br/><label>Mes: </label><select name='mesesATS' id='mesesATS' onchange='cambio_ats(event)' style='float: center;padding:2px;'>" +
      meses +
      "</select><br><label>Archivo: </label> <input type='text' id='nombre'  value='" +
      nombre +
      "' style='float: right;'><br><br><a type='button' class='btn btn-success form-control' class='generarXmlAts' onclick='return fn_ats_xml(event)' href='#'>Generar XML</a>",
  });
  $(".generarXmlAts").button();
  e.preventDefault();
}

function cambio_ats(e) {
  $("#nombre").val("ATS" + $("#mesesATS").val() + $("#aniosATS").val());
}

function fn_ats_xml(e) {
  if ($("#nombre").val() != "") {
    window.open(
      "../../reportes/anexoxml.php?anio=" +
      $("#aniosATS").val() +
      "&mes=" +
      $("#mesesATS").val() +
      "&nombrearchivo=" +
      $("#nombre").val(),
      "_blank"
    );
  } else {
    alertify.alert("Por favor ingrese un nombre para el Archivo");
  }
}
// Retenciones
// General Tesoreria
function ventana_retenciones_tesoreria(e) {
  modal.open({
    content: `<label>Retenciones General Tesorería</label></br>
    <label>Fecha Inicio</label><input type='text' id='inicio' style='float: right;' /></br>
    <label>Fecha Fin <font color="red">*</font></label><input type='text' id='fin' style='float: right;'/></br>
    <button type='button' class='btn btn-success form-control' id='generarReporteRT' 
    onclick='return fn_retenciones_tesoreria(event)' >Generar</button>`,
  });
  // default un mes antes
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: (selectedDate) => {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  // default hoy
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: (selectedDate) => {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_retenciones_tesoreria(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/retenciones_tesoreria.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
  e.preventDefault();
}
// Retenciones General Factura Compra
function ri_factura_compra_compras_reten(e) {
  modal.open({
    content: `<label>Retenciones Compras</label> <br>
    <label>Fecha Inicio</label> <input type='text' id='inicio' style='float: right;'><br> 
    <label>Fecha Fin<font color="red">*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarRet_Fuente_FCFechas' 
    onclick='return fn_rf_fact_compra_reten(event)'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_rf_fact_compra_reten(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/retencion_I_R.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
// Iva General Gastos
function ri_factura_compra_compras_reten_gastos(e, impuesto) {
  modal.open({
    content: `<label>Retenciones Gastos</label> <br>
    <label>Fecha Inicio</label> <input type='text' id='inicio' style='float: right;'><br> 
    <label>Fecha Fin<font color="red">*</font></label><input type='text' id='fin' style='float: right;'><br>
    <button type='button' class='btn btn-success form-control' id='generarRet_Fuente_FCFechas' 
    onclick='return fn_rf_fact_compra_reten_gastos(event,"${impuesto}")'>Generar Reporte</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_rf_fact_compra_reten_gastos(e, impuesto = "ir") {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    if (impuesto === "ir") {
      window.open(
        "../../reportes/retencion_I_R_G.php?inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val(),
        "_blank"
      );
    } else if (impuesto === "iva") {
      window.open(
        "../../reportes/retencion_I_V_A_G.php?inicio=" +
        $("#inicio").val() +
        "&fin=" +
        $("#fin").val(),
        "_blank"
      );
    }
  }
}
// Resumen Compra Venta
function resumen_compra_venta(e) {
  modal.open({
    content: `<label>Compra - Venta - Gastos</label><br> 
        <label>Fecha Inicio</label><input type='text' id='inicio' style='float: right;' /></br> 
        <label>Fecha Fin <font color='red'>*</font></label><input type='text' id='fin' style='float: right;' /></br>
        <button type='button' class='btn btn-success form-control' id='generarResumenCV' 
        onclick='return fn_resumen_compra_venta(event)' >Generar</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_resumen_compra_venta(e) {
  if ($("#fin").val() !== "") {
    window.open(
      "../../reportes/resumenCompraVenta.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  } else {
    valores_incompletos();
  }
}
/////////////////////////////
function factura_venta_combo(e) {
  modal.open({
    content:
      "<label>Punto de Venta</label><select id='sel_punto_venta' style='width:150px;float:right'></select><br><a 'id='generarCombo' style.display == 'none' class='btn btn-success form-control' class='generarFacturaVentaCombos' onclick='return fn_Factura_Venta_Combos(event)' href='#'>Guardar</a>  <button type='button' name='punto_venta_dist'  id='punto_venta_dist' class='generarFacturaVentaCombos' onclick='return fn_Factura_Venta_Combos_abrir(event)' disabled class='btn btn-primary '>Abrir Facturación</button>  ",
  });
  $("#sel_punto_venta").load("../factura_venta/punto_venta_combos.php");
  $(".generarFacturaVentaCombos").button();
  e.preventDefault();
}
function fn_Factura_Venta_Combos(e) {
  var abrir = window.open(
    "../factura_venta/guardar_punto_venta.php?id=" +
    $("#sel_punto_venta").val(),
    "_blank"
  );
  $("#punto_venta_dist").prop("disabled", false);

  abrir.close();
}

function fn_Factura_Venta_Combos_abrir(e) {
  $("#generar").activatedItems;
  window.open("../factura_Venta ", "_blank");
  modal.close();
}
//////////////////////////
function ordenes_produccion_fechas(e) {
  modal.open({
    content:
      "<input type='radio' name='group1' id='pdf' value='Reporte Pdf' checked> <label for='pdf'>Reporte Pdf</label> <br> <label>Fecha Inicio</label> <input type='text' id='inicio'><br> <label>Fecha Fin</label> <input type='text' id='fin' style='float: right;'><br><br><a type='button' class='btn btn-success form-control' class='generarOrdenProduccionFecha' onclick='return fn_ordenes_produccion_fechas(event)' href='#'>Generar Reporte</a>",
  });
  $(".generarOrdenProduccionFecha").button();
  $("#inicio").datepicker({
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_ordenes_produccion_fechas(e) {
  var hoja = $("#tam_hoja").val();
  if ($("#excel").is(":checked")) {
    window.open(
      "../../phpexcel/resumenFacturasCompras.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  } else {
    window.open(
      "../../reportes/ordenes_produccion_fechas.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
////////////////////////////
function total_director(e) {
  modal.open({
    content:
      "<label for='buscarDir' style='padding:6px;'>Buscar</label><input type='text' name='buscarDir' id='buscarDir' style='float: right;padding:2px;' /><input type='hidden' id='idDir' /><br><label style='padding:6px;'>Fecha Inicio</label> <input type='text' id='inicio' style='padding:2px;'><br> <label style='padding:6px;'>Fecha Fin</label> <input type='text' id='fin' style='float: right;padding:2px;'><br><input type='radio' name='group1' id='pdf' value='Reporte Pdf' checked> <label for='pdf'>Reporte Pdf</label> <br><a type='button' class='btn btn-success form-control' class='generarReporte_totalDirector' onclick='return fn_total_director(event)' href='#'>Generar Reporte</a>",
  });
  $("#buscarDir")
    .autocomplete({
      source: "../../procesos/busquedaDirector.php",
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarDir").val(ui.item.label);
        $("#idDir").val(ui.item.value);
        return false;
      },
      select: function (event, ui) {
        $("#buscarDir").val(ui.item.label);
        $("#idDir").val(ui.item.value);
        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.label + "</a>")
        .appendTo(ul);
    };
  $(".generarReporte_totalDirector").button();
  $("#inicio").datepicker({
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_total_director(e) {
  window.open(
    "../../reportes/reporte_director.php?id=" +
    $("#idDir").val() +
    "&inicio=" +
    $("#inicio").val() +
    "&fin=" +
    $("#fin").val(),
    "_blank"
  );
}
// Estado de Perdidas y Ganancias
function estado_pg(e) {
  modal.open({
    content: `<label style='padding:6px;'>Fecha Inicio</label> 
    <input type='text' id='inicio' style='padding:2px;'></br> 
    <label style='padding:6px;'>Fecha Fin <font color='red'>*</font></label> 
    <input type='text' id='fin' style='float: right;padding:2px;'></br>
    <button type='button' id='generarEstadoPG' class='btn btn-success form-control'
    onclick='return fn_estado_pg(event);'>Generar</button>`,
  });
  $("#inicio").datepicker({
    defaultDate: "-1m",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "t",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_estado_pg(e) {
  if ($("#fin").val() === "") {
    valores_incompletos();
  } else {
    window.open(
      "../../reportes/estado_perdidas_ganancias.php?inicio=" +
      $("#inicio").val() +
      "&fin=" +
      $("#fin").val(),
      "_blank"
    );
  }
}
//
function cobros_realizadoshvp(e) {
  modal.open({
    content: `<label>Fecha Inicio</label> <input type='text' id='inicio' style='float: right;'><br> <label>Fecha Fin</label> <input type='text' id='fin' style='float: right;'><br><label for='buscarClientehvp'>Nombre Producto: </label><input type='text' name='buscarClientehvp' id='buscarClientehvp'style='float: right;' /><input type='hidden' id='idcli' style='float: right;'/><br><br> <a type='button' class='btn btn-success form-control' class='generarReporte_cobrosRealizadoshvp' 
    onclick='return fn_cobros_realizadoshvp(event)' href='#'>Generar Reporte</a>`,
  });
  $("#buscarClientehvp")
    .autocomplete({
      source: "../../procesos/productos_combos.php",
      minLength: 1,
      focus: function (event, ui) {
        $("#buscarClientehvp").val(ui.item.value);
        $("#idcli").val(ui.item.id_cliente);

        return false;
      },
      select: function (event, ui) {
        $("#buscarClientehvp").val(ui.item.value);
        $("#idcli").val(ui.item.id_cliente);

        return false;
      },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
      return $("<li>")
        .append("<a>" + item.value + "</a>")
        .appendTo(ul);
    };
  $(".generarReporte_cobrosRealizadoshvp").button();
  $("#inicio").datepicker({
    defaultDate: "+1w",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#fin").datepicker("option", "minDate", selectedDate);
    },
  });
  $("#fin").datepicker({
    defaultDate: "+1w",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    changeYear: true,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 2,
    onClose: function (selectedDate) {
      $("#inicio").datepicker("option", "maxDate", selectedDate);
    },
  });
  e.preventDefault();
}
function fn_reporte_proveedor(e) {
  var hoja = $("#tam_hoja").val();
  var tipo;
  if ($("#excel").is(":checked")) {
    window.open(
      "../../phpexcel/reporte_proveedor.php?proveedor=" +
      $("#sel_proveedor").val(),
      "_blank"
    );
  } else {
    window.open(
      "../../reportes/reporteProveedor.php?id=" + $("#sel_proveedor").val(),
      "_blank"
    );
  }
  modal.close();
}

/**
 * FUNCIÓN PARA IMPRIMIR REPORTE CON ENVIO DDE DATOS POST
 *
 * @param {type} url - RUTA DEL REPORTE
 * @param {type} data - PARAMETROS ENVIADOS POR POST
 */
function abrirReporte(url, data) {
  var form = document.createElement("form");
  form.target = "_blank";
  form.method = "POST";
  //form.action = '../../reportes/reporteCPM.php'; //reporte en pdf
  form.action = url;

  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "obj";
  input.value = JSON.stringify(data);
  form.appendChild(input);

  document.body.appendChild(form);
  form.submit();
}
