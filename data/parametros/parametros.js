$(document).on("ready", inicio);

function inicio() {
  $(window)
    .bind("resize", function () {
      jQuery("#list").setGridWidth($("#centro").width());
    })
    .trigger("resize");
  jQuery("#list")
    .jqGrid({
      url: "xmlParametros.php",
      datatype: "xml",
      colNames: [
        "Código",
        "Descripción",
        "Valor",
        "Cuenta Débito",
        "Cuenta Crédito",
      ],
      colModel: [
        {
          name: "id_parametro",
          index: "id_parametro",
          editable: false,
          align: "center",
          width: "50",
          search: false,
          frozen: true,
        },
        {
          name: "descripcion",
          index: "descripcion",
          editable: true,
          align: "left",
          width: "300",
          search: true,
          frozen: true,
          formoptions: { elmsuffix: " (*)" },
          editrules: { required: true },
        },
        {
          name: "valor",
          index: "valor",
          editable: true,
          align: "center",
          width: "80",
          search: false,
          frozen: true,
          editrules: { required: false },
        },
        {
          name: "cuenta_debito",
          index: "cuenta_debito",
          editable: true,
          align: "left",
          width: "350",
          search: true,
          frozen: true,
          editrules: { required: false },
          edittype: "select",
          editoptions: { dataUrl: "../../procesos/combo-plan-cuentas.php" },
        },
        {
          name: "cuenta_credito",
          index: "cuenta_credito",
          editable: true,
          align: "left",
          width: "350",
          search: true,
          frozen: true,
          editrules: { required: false },
          edittype: "select",
          editoptions: { dataUrl: "../../procesos/combo-plan-cuentas.php", defaultValue: "Seleccione" },
        },
      ],
      rowNum: 20,
      rowList: [10, 20, 30],
      height: 350,
      pager: jQuery("#pager"),
      editurl: "procesosParametros.php",
      sortname: "id_parametro",
      shrinkToFit: true,
      sortordezr: "asc",
      caption: "Lista de Parámetros",
      viewrecords: true,
    })
    .jqGrid(
      "navGrid",
      "#pager",
      {
        add: true,
        edit: true,
        del: false,
        refresh: true,
        search: true,
        view: true,
        addtext: "Nuevo",
        edittext: "Modificar",
        refreshtext: "Recargar",
        viewtext: "Consultar",
        searchtext: "Buscar",
      },
      {
        reloadAfterSubmit: true,
        closeAfterAdd: true,
        closeOnEscape: true,
        bottominfo: "Los campos marcados con (*) son obligatorios",
        width: 650,
      },
      {
        reloadAfterSubmit: true,
        closeAfterAdd: true,
        closeOnEscape: true,
        bottominfo: "Los campos marcados con (*) son obligatorios",
        width: 650,
      },
    );
  jQuery("#list").setGridWidth($("#centro").width());

  initTablaParamsIva();
}

function initTablaParamsIva() {
  jQuery("#listpiva")
    .jqGrid({
      url: "jsonListaTarifasIva.php",
      datatype: "json",
      colNames: [
        "id",
        "Tarifa IVA",
        "Cuenta Cédito Tributario Compras",
        "Cuenta Ventas",
        "Cuenta IVA Ventas",
      ],
      colModel: [
        {
          name: "id_taimpuesto",
          index: "id_taimpuesto",
          hidden: true
        },
        {
          name: "nombre_taimpuesto",
          index: "nombre_taimpuesto",
        },
        {
          name: "id_cuenta_iva_compras",
          index: "id_cuenta_iva_compras",
          width: 300,
          formatter: function (cellvalue, options, rowObject) {
            return `<div><select id="sel_cc_iva_compras_${options.rowId}"></select></div>`;
          },
        },
        {
          name: "id_cuenta_ventas",
          index: "id_cuenta_ventas",
          width: 300,
          formatter: function (cellvalue, options, rowObject) {
            return `<div><select id="sel_cc_ventas_${options.rowId}"></select></div>`;
          },
        },
        {
          name: "id_cuenta_iva_ventas",
          index: "id_cuenta_iva_ventas",
          width: 300,
          formatter: function (cellvalue, options, rowObject) {
            return `<div><select id="sel_cc_iva_ventas_${options.rowId}"></select></div>`;
          },
        }
      ],
      rowNum: 20,
      rowList: [10, 20, 30],
      height: 350,
      sortname: "id_taimpuesto",
      shrinkToFit: true,
      sortordezr: "asc",
      caption: "Lista de Parámetros IVA",
      viewrecords: true,
      afterInsertRow: function (rowid, rowdata, rowelem) {
        $("#sel_cc_iva_compras_" + rowid).select2(
          {
            placeholder: '--Seleccionar--',
            allowClear: true,
            width: "100%",
            ajax: {
              url: "obtener_plan_cuentas_select.php",
              dataType: "json",
              processResults: function (data) {
                let mapped = data.items.map(el => {
                  el["id"] = el.id_plan_cuentas;
                  el["text"] = `${el.codigo_plan} --- ${el.descripcion}`;
                  return el;
                });
                return {
                  results: mapped,
                  pagination: {
                    more: data.more
                  }
                };
              },
              data: function (params) {
                var query = {
                  search: params.term,
                  page: params.page || 1
                }
                return query;
              }
            }
          }
        );
        $("#sel_cc_iva_ventas_" + rowid).select2(
          {
            placeholder: '--Seleccionar--',
            allowClear: true,
            width: "100%",
            ajax: {
              url: "obtener_plan_cuentas_select.php",
              dataType: "json",
              processResults: function (data) {
                let mapped = data.items.map(el => {
                  el["id"] = el.id_plan_cuentas;
                  el["text"] = `${el.codigo_plan} --- ${el.descripcion}`;
                  return el;
                });
                return {
                  results: mapped,
                  pagination: {
                    more: data.more
                  }
                };
              },
              data: function (params) {
                var query = {
                  search: params.term,
                  page: params.page || 1
                }
                return query;
              }
            }
          }
        );
        $("#sel_cc_ventas_" + rowid).select2(
          {
            placeholder: '--Seleccionar--',
            allowClear: true,
            width: "100%",
            ajax: {
              url: "obtener_plan_cuentas_select.php",
              dataType: "json",
              processResults: function (data) {
                let mapped = data.items.map(el => {
                  el["id"] = el.id_plan_cuentas;
                  el["text"] = `${el.codigo_plan} --- ${el.descripcion}`;
                  return el;
                });
                return {
                  results: mapped,
                  pagination: {
                    more: data.more
                  }
                };
              },
              data: function (params) {
                var query = {
                  search: params.term,
                  page: params.page || 1
                }
                return query;
              }
            }
          }
        );
        $("#sel_cc_iva_compras_" + rowid).change(function (e) {
          let data = $('#sel_cc_iva_compras_' + rowid).select2('data');
          if (data.length > 0) {
            guardarParametroCuentaCIva(rowid, "id_cuenta_iva_compras", data[0].id);
          } else {
            guardarParametroCuentaCIva(rowid, "id_cuenta_iva_compras", null);
          }

        });
        $("#sel_cc_iva_ventas_" + rowid).change(function (e) {
          let data = $('#sel_cc_iva_ventas_' + rowid).select2('data');
          if (data.length > 0) {
            guardarParametroCuentaCIva(rowid, "id_cuenta_iva_ventas", data[0].id);
          } else {
            guardarParametroCuentaCIva(rowid, "id_cuenta_iva_ventas", null);
          }

        });
        $("#sel_cc_ventas_" + rowid).change(function (e) {
          let data = $('#sel_cc_ventas_' + rowid).select2('data');
          if (data.length > 0) {
            guardarParametroCuentaCIva(rowid, "id_cuenta_ventas", data[0].id);
          } else {
            guardarParametroCuentaCIva(rowid, "id_cuenta_ventas", null);
          }

        });
        if (rowdata.id_cuenta_iva_compras) {
          obtenerCuentaContable(rowdata.id_cuenta_iva_compras).then(data => {
            let option = `<option value=">${data.id_plan_cuentas}">${data.codigo_plan} --- ${data.descripcion}</option>`;
            $("#sel_cc_iva_compras_" + rowid).append(option);
          });
        }
        if (rowdata.id_cuenta_iva_ventas) {
          obtenerCuentaContable(rowdata.id_cuenta_iva_ventas).then(data => {
            let option = `<option value=">${data.id_plan_cuentas}">${data.codigo_plan} --- ${data.descripcion}</option>`;
            $("#sel_cc_iva_ventas_" + rowid).append(option);
          });
        }
        if (rowdata.id_cuenta_ventas) {
          obtenerCuentaContable(rowdata.id_cuenta_ventas).then(data => {
            let option = `<option value=">${data.id_plan_cuentas}">${data.codigo_plan} --- ${data.descripcion}</option>`;
            $("#sel_cc_ventas_" + rowid).append(option);
          });
        }
      }
    });
}

function guardarParametroCuentaCIva(id_tarifa, nombre_param, valor) {
  $.ajax({
    url: "guardar_parametro_cuenta_contable_iva.php",
    method: "POST",
    dataType: "json",
    data: { id_tarifa, nombre_param, valor }
  })
    .done(data => {
      $("#alertify-logs").empty();
      if (Number(data) > 0) {
        alertify.success("Cambio guardado");
      } else {
        alertify.error("No se pudo guardar el cambio");
      }
    })
    .fail(() => {
      alertify.error("Hubo un problema al guardar el cambio");
    });
}

function obtenerCuentaContable(id_cuenta) {
  return $.ajax({
    url: "obtener_cuenta_contable.php",
    dataType: "json",
    data: { id_cuenta }
  });
}