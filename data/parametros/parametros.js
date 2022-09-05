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
}

