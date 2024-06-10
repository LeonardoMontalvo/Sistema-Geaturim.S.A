<?php
session_start();
include('../menu/app.php');
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>PERMISOS USUARIOS</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../../font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="../../plugins/icon/ionicons.min.css" rel="stylesheet" type="text/css" />
    <link href="../../dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <link href="../../dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    <link href="../../plugins/iCheck/flat/blue.css" rel="stylesheet" type="text/css" />
    <link href="../../plugins/morris/morris.css" rel="stylesheet" type="text/css" />
    <link href="../../plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <link href="../../plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
    <link href="../../plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
    <link href="../../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
    <link href="../../plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="../../dist/css/alertify.core.css" rel="stylesheet" />
    <link href="../../dist/css/alertify.default.css" id="toggleCSS" rel="stylesheet" />
    <link href="../../dist/css/jquery-ui-1.10.4.custom.css" rel="stylesheet" type="text/css" />
    <link href="../../dist/css/ui.jqgrid.css" rel="stylesheet" type="text/css" />

</head>

<body class="skin-blue">
    <div class="wrapper">
        <?php banner_1(); ?>
        <?php menu_lateral_1(); ?>
        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                    Asignar Permisos a Usuarios
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Ingresos</a></li>
                    <li class="active">Permisos Usuarios</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="tabbable" id="general" name="general">
                                    <label class="col-md-2">BUSCAR USUARIO: </label>
                                    <div class="form-group col-md-5 ">
                                        <input type="text" name="busca_usuario" id="busca_usuario" required class="form-control" />
                                    </div>
                                    <div class="form-group col-md-5 ">
                                        <button class="btn btn-primary" id='btnBuscarPermiso'><i class="fa fa-search"></i> Buscar</button>
                                        <button class="btn btn-primary" id='btnLimpiarPermiso'><i class="fa fa-eraser"></i> Limpiar</button>
                                        <button class="btn btn-primary" id='btnGuardarPermisos'><i class="fa fa-save"></i> Guardar</button>
                                    </div>
                                    <input type="hidden" name="id_usuarioC" id="id_usuarioC" />
                                    <P>
                                        <br />
                                    </P>

                                </div>

                                <div class="tabbable" id="general" name="general">
                                    <!-- MENU DE ASIGNACION DE ROLES} -->
                                    <!-- <fieldset> -->
                                    <ul class="menuPermisos">
                                        <div class="col-md-4" id="derecha" name="derecha">
                                            <li class="active treeview">
                                                <input type="checkbox" name="parametros" value="parametros" id="parametros"></input> <span>Parámetros</span>
                                                <div name="menuParametros" id="menuParametros">
                                                    <ul class="treeview-menu">
                                                        <li>
                                                            <input type="checkbox" menu="inventario" value="inventario" id="inventario"></input> <span>Inventario</span>
                                                            <div name="menuInventario" id="menuInventario">
                                                                <ul class="treeview-menu">
                                                                    <li><input type="checkbox" name="bodegas" value="bodegas" id="bodegas"></input> <span>Bodegas</span></li>
                                                                    <li><input type="checkbox" name="categorias" value="categorias" id="categorias"></input> <span>Categoria</span></li>
                                                                    <li><input type="checkbox" name="marcas" value="marcas" id="marcas"></input> <span>Marcas</span></li>
                                                                    <li><input type="checkbox" name="generico" value="generico" id="generico"></input> <span>Genèrico</span></li>
                                                                    <li><input type="checkbox" name="aplicacion" value="aplicacion" id="aplicacion"></input> <span>Aplicaciòn</span></li>
                                                                    <li><input type="checkbox" name="unidadesProd" value="unidadesProd" id="unidadesProd"></input> <span></i>Unidades Productos</span></li>
                                                                    <!--                                                                    <li><input type="checkbox" name="retFuente" value="retFuente" id="retFuente"></input> <span></i>Retenciones en la Fuente</span></li>
                                                                                <li><input type="checkbox" name="retIva" value="retIva" id="retIva"></input> <span></i>Unidades Productos</span></li>
                                                                                <li><input type="checkbox" name="cuentaParametro" value="cuentaParametro" id="cuentaParametro"></input> <span></i>Parametros Contables</span></li>-->
                                                                </ul>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" menu="parametrosContables" value="parametrosContables" id="parametrosContables"></input> <span>Parametros Contables</span>
                                                            <div name="menuparametrosContables" id="menuparametrosContables">
                                                                <ul class="treeview-menu">
                                                                    <li><input type="checkbox" name="retFuente" value="retFuente" id="retFuente"></input> <span></i>Retenciones en la Fuente</span></li>
                                                                    <li><input type="checkbox" name="retIva" value="retIva" id="retIva"></input> <span></i>Unidades Productos</span></li>
                                                                    <li><input type="checkbox" name="cuentaParametro" value="cuentaParametro" id="cuentaParametro"></input> <span></i>Parametros Plan Cuentas</span></li>
                                                                    <li><input type="checkbox" name="planCuentas" value="planCuentas" id="planCuentas"></input> <span>Plan de Cuentas</span></li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                        <!--<li><input type="checkbox" name="respaldo" value="respaldo" id="respaldo"></input> <span>Respaldo</span></li>-->

                                                        <li><input type="checkbox" name="empresa" value="empresa" id="empresa"></input> <span>Empresa</span></li>
                                                        <input type="checkbox" name="esquemasBd" value="esquemasBd" id="esquemasBd"></input> <span>Esquemas BD</span>
                                                        <li><input type="checkbox" name="promocionVenta" value="promocionVenta" id="promocionVenta"></input> <span>Promociónes Ventas</span></li>
                                                    </ul>
                                                </div>
                                            </li>

                                            <li class="treeview">
                                                <input type="checkbox" name="ingresosUsuarios" value="ingresosUsuarios" id="ingresosUsuarios"></input> <span>Ingresos</span>
                                            </li>
                                            <div name="menuIngresos" id="menuIngresos">
                                                <ul class="treeview-menu">
                                                    <li><input type="checkbox" name="usuarios" id="usuarios" value="usuarios"></input> <span>Usuarios</span></li>
                                                    <div name="menuUsuarios" id="menuUsuarios">
                                                        <ul class="treeview-menu">
                                                            <li><input type="checkbox" name="ingresoUsu" id="ingresoUsu" value="ingresoUsu"></input> <span> Ingreso de Usuarios</span></li>
                                                            <li><input type="checkbox" name="permisoUsu" id="permisoUsu" value="permisoUsu"></input> <span>Permisos de Usuario</span></li>
                                                        </ul>
                                                    </div>
                                                    </li>
                                                    <li><input type="checkbox" name="proveedores" id="proveedores" value="proveedores"></input> <span>Proveedores</span></li>


                                                    <li><input type="checkbox" name="vendedores" id="vendedores" value="vendedores"></input> <span>Vendedores</span></li>
                                                    <li><input type="checkbox" name="beneficiario" id="beneficiario" value="beneficiario"></input> <span>Rutas</span></li>
                                                    <li><input type="checkbox" name="clientes" id="clientes" value="clientes"></input> <span>Clientes</span></li>
                                                    <li><input type="checkbox" name="productos" id="productos" value="productos"></input> <span>Productos</span></li>
                                                    <li><input type="checkbox" name="registroFac" id="registroFac" value="registroFac"></input> <span>Registro Factureros</span></li>
                                                    <li><input type="checkbox" name="anulaFac" id="anulaFac" value="anulaFac"></input> <span>Anular Facturas</span></li>
                                                    <li><input type="checkbox" name="conductores" id="conductores" value="conductores"></input> <span>Conductores</span></li>
                                                    <li><input type="checkbox" name="vehiculos" id="vehiculos" value="vehiculos"></input> <span>Vehiculos</span></li>
                                                    <li><input type="checkbox" name="contratos" id="contratos" value="contratos"></input> <span>Contratos</span></li>

                                                    <li><input type="checkbox" name="costos" id="costos" value="costos"></input> <span>Costos</span></li>
                                                    <div name="menuCostos" id="menuCostos">
                                                        <ul class="treeview-menu">
                                                            <li><input type="checkbox" name="ingresoCCosto" id="ingresoCCosto" value="ingresoCCosto"></input> <span> Centros de Costo</span></li>
                                                        </ul>
                                                    </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            </li>
                                        </div>
                                        <div class="col-md-4" id="centro" name="centro">
                                            <li class="treeview">
                                                <input type="checkbox" menu="procesos" id="procesos" value="procesos"></input> <span>Procesos</span>
                                                <div name="menuProcesos" id="menuProcesos">
                                                    <ul class="treeview-menu">
                                                        <li><input type="checkbox" name="proCierreCaja" id="proCierreCaja" value="proCierreCaja"></input> <span>Cierre de Caja</span></li>
                                                        <li><input type="checkbox" name="proInventario" id="proInventario" value="proInventario"></input> <span>Inventario</span></li>
                                                        <li><input type="checkbox" name="proforma" id="proforma" value="proforma"></input> <span>Proforma</span></li>
                                                        <li><input type="checkbox" name="liquidacion_compra" id="liquidacion_compra" value="liquidacion_compra"></input> <span>Liquidación Compra</span></li>
                                                        <li>
                                                            <input type="checkbox" name="compras" id="compras" value="compras"></input> <span>Compras</span>
                                                            <div name="menuCompras" id="menuCompras">
                                                                <ul class="treeview-menu">
                                                                    <li><input type="checkbox" name="productosBod" id="productosBod" value="productosBod"></input> <span>Productos Bodega</span></li>
                                                                    <li><input type="checkbox" name="devolucionCom" id="devolucionCom" value="devolucionCom"></input> <span>Devolución Compra</span></li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" name="ventas" id="ventas" value="ventas"></input> <span>Ventas</span></a>
                                                            <div name="menuVentas" id="menuVentas">
                                                                <ul class="treeview-menu">
                                                                    <li><input type="checkbox" name="ventasFac" id="ventasFac" value="ventasFac"></input> <span>Ventas facturación</span></li>
                                                                      <li><input type="checkbox" name="ventasFac_guia" id="ventasFac_guia" value="ventasFac_guia"></input> <span>Guia Remisión</span></li>
																   <li><input type="checkbox" name="ventasFacv2" id="ventasFacv2" value="ventasFacv2"></input> <span>Ventas facturación v2</span></li>
                                                                    <li><input type="checkbox" name="notasCre" id="notasCre" value="notasCre"></input> <span>Notas de crédito</span></li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" name="cartera" id="cartera" value="cartera"></input> <span>Cartera</span>
                                                            <div name="menuCartera" id="menuCartera">
                                                                <ul class="treeview-menu">
                                                                    <li><input type="checkbox" name="anticiposCli" id="anticiposCli" value="anticiposCli"></input> <span>Anticipo Clientes</span></li>
                                                                    <li><input type="checkbox" name="anticiposPro" id="anticiposPro" value="anticiposPro"></input> <span>Anticipo Proveedores</span></li>
                                                                    <li><input type="checkbox" name="cuentasCob" id="cuentasCob" value="cuentasCob"></input> <span>Cuentas por cobrar</span></li>
                                                                    <li><input type="checkbox" name="cuentasPag" id="cuentasPag" value="cuentasPag"></input> <span>Cuentas por pagar</span></li>
                                                                    <li>
                                                                        <input type="checkbox" name="externas" id="externas" value="externas"></input> <span>Externas</span>
                                                                        <div name="menuExternas" id="menuExternas">
                                                                            <ul class="treeview-menu">
                                                                                <li><input type="checkbox" name="cuentasCobExt" id="cuentasCobExt" value="cuentasCobExt"></input> <span>Cuentas por cobrar</span></li>
                                                                                <li><input type="checkbox" name="cuentasPagExt" id="cuentasPagExt" value="cuentasPagExt"></input> <span>Cuentas por pagar</span></li>
                                                                            </ul>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" name="transferencias" id="transferencias" value="transferencias"></input> <span>Transferencias</span>
                                                            <div name="menuTransferencias" id="menuTransferencias">
                                                                <ul class="treeview-menu">
                                                                    <li><input type="checkbox" name="ingresos" id="ingresos" value="ingresos"></input> <span>Ingresos</span></li>
                                                                    <li><input type="checkbox" name="egresos" id="egresos" value="egresos"></input> <span>Egresos</span></li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                        <li><input type="checkbox" name="asientoCont" id="asientoCont" value="asientoCont"></input> <span>Asientos Contables</span></li>
                                                        <li><input type="checkbox" name="registrosGas" id="registrosGas" value="registrosGas"></input> <span>Registro Gastos</span></li>
                                                        <li><input type="checkbox" name="gastosInt" id="gastosInt" value="gastosInt"></input> <span>Gastos Internos</span></li>
                                                        <li><input type="checkbox" name="gastosPersonales" id="gastosPersonales" value="gastosPersonales"></input> <span>Gastos Personales</span></li>
                                                        <li>
                                                            <input type="checkbox" name="kardex" id="kardex" value="kardex"></input> <span>Kardex</span>
                                                            <div name="menuKardex" id="menuKardex">
                                                                <ul class="treeview-menu">
                                                                    <li><input type="checkbox" name="kardexGeneral" id="kardexGeneral" value="kardexGeneral"></input> <span>Kardex General</span></li>
                                                                    <li><input type="checkbox" name="kardexVal" id="kardexVal" value="kardexVal"></input> <span>Kardex Valorado</span></li>
                                                                    <li><input type="checkbox" name="kardexPro" id="kardexPro" value="kardexPro"></input> <span>Kardex a la Fecha Productos</span></li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" name="reservaciones" id="reservaciones" value="reservaciones"></input> <span>Reservaciones</span>
                                                            <div name="menuReservaciones" id="menuReservaciones">
                                                                <ul class="treeview-menu">
                                                                    <li><input type="checkbox" name="ingresoReservacion" id="ingresoReservacion" value="ingresoReservacion"></input> <span>Ingreso Reservación</span></li>
                                                                    <li><input type="checkbox" name="cobroReservacion" id="cobroReservacion" value="cobroReservacion"></input> <span>Cobro Reservacion</span></li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                        <li><input type="checkbox" name="conciliacionBancaria" id="conciliacionBancaria" value="conciliacionBancaria"></input> <span>Conciliación Bancaria</span></li>
                                                        <li>
                                                            <input type="checkbox" name="mantenimiento" id="mantenimiento" value="mantenimiento"></input> <span>Mantenimiento</span>
                                                            <div name="menuMantenimiento" id="menuMantenimiento">
                                                                <ul class="treeview-menu">
                                                                    <li><input type="checkbox" name="ingresosEquipos" id="ingresosEquipos" value="ingresosEquipos"></input> <span>Ingresos Equipos</span></li>
                                                                    <li><input type="checkbox" name="proformaTecnico" id="proformaTecnico" value="proformaTecnico"></input> <span>Registro Técnico</span></li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" name="ordenes_produccion" id="ordenes_produccion" value="ordenes_produccion"></input> <span>Ordenes de Producción</span>
                                                            <div name="menuOrden" id="menuOrden">
                                                                <ul class="treeview-menu">
                                                                    <li><input type="checkbox" name="recetas" id="recetas" value="recetas"></input> <span>Recetas</span></li>
                                                                    <li><input type="checkbox" name="nuevaOrden" id="nuevaOrden" value="nuevaOrden"></input> <span>Crear Orden de Producción</span></li>
                                                                    <li><input type="checkbox" name="aprobarOrden" id="aprobarOrden" value="aprobarOrden"></input> <span>Aprobar Orden de Producción</span></li>
                                                                </ul>
                                                            </div>
                                                        </li>


                                                        <li>
                                                            <input type="checkbox" name="nomina" id="nomina" value="nomina"></input> <span>Nomina</span>
                                                            <div name="menunomina" id="menunomina">
                                                                <ul class="treeview-menu">
                                                                    <li><input type="checkbox" name="prolpagos_anti" id="prolpagos_anti" value="prolpagos_anti"></input> <span>Anticipo Nomina</span></li>
                                                                    <li><input type="checkbox" name="prolpagos" id="prolpagos" value="prolpagos"></input> <span>Rol de Pagos Parametros</span></li>
                                                                    <li><input type="checkbox" name="crolpagos" id="crolpagos" value="crolpagos"></input> <span>Rol de Pagos Individual</span></li>

                                                                </ul>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" name="auditoria" id="auditoria" value="auditoria"></input> <span>Auditoria</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" name="restaurantes" id="restaurantes" value="restaurantes"></input> <span>Restaurantes</span>
                                                            <div name="menuRestaurantes" id="menuRestaurantes">
                                                                <ul class="treeview-menu">
                                                                    <li><input type="checkbox" name="restaurantesOrdenes" id="restaurantesOrdenes" value="restaurantesOrdenes"></input> <span>Ordenes</span></li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" name="procesarFacRec" id="procesarFacRec" value="procesarFacRec"></input> <span>Procesar Facturas Recibidas</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                        </div>
                                        <div class="col-md-4" id="izquierda" name="izquierda">
                                            <li>
                                                <input type="checkbox" name="reportes" id="reportes" value="reportes"></input> <span>Reportes</span>
                                                <div name="menuReportes" id="menuReportes">
                                                    <ul class="treeview-menu">
                                                          <li><input type="checkbox" name="repListaClientes" id="repListaClientes" value="repListaClientes"></input> <span>Clientes</span></li>
                                                        <li><input type="checkbox" name="repCierresCaja" id="repCierresCaja" value="repCierresCaja"></input> <span>Reporte Cierres de Caja</span></li>
                                                        <li>
                                                            <input type="checkbox" name="repCentCostos" id="repCentCostos" value="repCentCostos"></input> <span>Centro de Costos</span>
                                                            <div name="menuRepCentCostos" id="menuRepCentCostos">
                                                                <ul class="treeview-menu">
                                                                    <li><input type="checkbox" name="repResDocsCC" id="repResDocsCC" value="repResDocsCC"></input> <span>Resumen Documentos</span></li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" name="repProductos" id="repProductos" value="repProductos"></input> <span>Productos</span>
                                                            <div name="menuRepProductos" id="menuRepProductos">
                                                                <ul class="treeview-menu">
                                                                    <li><input type="checkbox" name="repConsultarProdPv" id="repConsultarProdPv" value="repConsultarProdPv"></input> <span>Consultar Existencias en Bodegas</span></li>
                                                                    <li><input type="checkbox" name="repListaPrecios" id="repListaPrecios" value="repListaPrecios"></input> <span>Lista de Precios</span></li>
                                                                    <li><input type="checkbox" name="repProductosGen" id="repProductosGen" value="repProductosGen"></input> <span>General</span></li>
                                                                    <li><input type="checkbox" name="repProdCatMar" id="repProdCatMar" value="repProdCatMar"></input> <span>Categorías y Marcas</span></li>
                                                                    <li><input type="checkbox" name="repProdCat" id="repProdCat" value="repProdCat"></input> <span>Categorías</span></li>
                                                                    <li><input type="checkbox" name="repProdMar" id="repProdMar" value="repProdMar"></input> <span>Marcas</span></li>
                                                                    <li><input type="checkbox" name="repProProv" id="repProProv" value="repProProv"></input> <span>Proveedores</span></li>
                                                                    <li><input type="checkbox" name="repExisMin" id="repExisMin" value="repExisMin"></input> <span>Existencia Mínima</span></li>
                                                                    <li><input type="checkbox" name="repPlantContP" id="repPlantContP" value="repPlantContP"></input> <span>Plantilla para conteo de productos</span></li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                        <li><input type="checkbox" name="repInventario" id="repInventario" value="repInventario"></input> <span>Inventario</span></li>
                                                        <li>
                                                            <input type="checkbox" name="repCompras" id="repCompras" value="repCompras"></input> <span>Compras</span>
                                                            <div name="menuRepCompras" id="menuRepCompras">
                                                                <ul class="treeview-menu">
                                                                    <li>
                                                                        <input type="checkbox" name="repComprasLocales" id="repComprasLocales" value="repComprasLocales"></input> <span>Resúmenes</span>
                                                                        <div name="menuRepComprasLocales" id="menuRepComprasLocales">
                                                                            <ul class="treeview-menu">
                                                                                <li><input type="checkbox" name="repFacturasCompras" id="repFacturasCompras" value="repFacturasCompras"></input> <span>Facturas</span></li>
                                                                                <li><input type="checkbox" name="repFacturasProveedor" id="repFacturasProveedor" value="repFacturasProveedor"></input> <span>Facturas General</span></li>
                                                                                <li><input type="checkbox" name="repAgrupadosProvCompras" id="repAgrupadosProvCompras" value="repAgrupadosProvCompras"></input> <span>Agrupados Proveedor</span></li>
                                                                                <li><input type="checkbox" name="repDevolucionCompras" id="repDevolucionCompras" value="repDevolucionCompras"></input> <span>Devolución Compra</span></li>
                                                                                <li><input type="checkbox" name="repFactAgrupadas" id="repFactAgrupadas" value="repFactAgrupadas"></input> <span>Facturas Agrupadas</span></li>
                                                                                <li><input type="checkbox" name="repFactDetalladas" id="repFactDetalladas" value="repFactDetalladas"></input> <span>Facturas Detalladas</span></li>
                                                                                <li><input type="checkbox" name="repFactDevolucion" id="repFactDevolucion" value="repFactDevolucion"></input> <span>Devolucion Detalladas</span></li>
                                                                                <li><input type="checkbox" name="repNotaVenta" id="repNotaVenta" value="repNotaVenta"></input> <span>Notas de Venta</span></li>
                                                                            </ul>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <input type="checkbox" name="repRetFactCompra" id="repRetFactCompra" value="repRetFactCompra"></input> <span>Retenciones</span>
                                                                        <div name="menuRepRetFactCompra" id="menuRepRetFactCompra">
                                                                            <ul class="treeview-menu">
                                                                                <li><input type="checkbox" name="repFCRetFuente" id="repFCRetFuente" value="repFCRetFuente"></input> <span>Retenciones en la Fuente</span></li>
                                                                                <li><input type="checkbox" name="repFCRetIva" id="repFCRetIva" value="repFCRetIva"></input> <span>Retenciones de IVA</span></li>
                                                                                <li>
                                                                                    <input type="checkbox" name="repFCBuscarRet" id="repFCBuscarRet" value="repFCBuscarRet"></input> <span>Buscar Retención</span>
                                                                                    <div name="menuRepFCBuscarRet" id="menuRepFCBuscarRet">
                                                                                        <ul class="treeview-menu">
                                                                                            <li><input type="checkbox" name="repFCBusRetFuente" id="repFCBusRetFuente" value="repFCBusRetFuente"></input> <span>Retenciones en la Fuente</span></li>
                                                                                            <li><input type="checkbox" name="repFCBusRetIva" id="repFCBusRetIva" value="repFCBusRetIva"></input> <span>Retenciones de IVA</span></li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" name="repVentas" id="repVentas" value="repVentas"></input> <span>Ventas</span>
                                                            <div name="menuRepVentas" id="menuRepVentas">
                                                                <ul class="treeview-menu">
                                                                    <li>
                                                                        <input type="checkbox" name="repFlujoCaja" id="repFlujoCaja" value="repFlujoCaja"></input> <span>Flujo de Caja</span>
                                                                        <div name="menuRepFlujoCaja" id="menuRepFlujoCaja">
                                                                            <ul class="treeview-menu">
                                                                                <li><input type="checkbox" name="repVentaGeneral" id="repVentaGeneral" value="repVentaGeneral"></input> <span>General</span></li>
                                                                                <li><input type="checkbox" name="repVentaGenCliente" id="repVentaGenCliente" value="repVentaGenCliente"></input> <span>Clientes</span></li>
                                                                                <li><input type="checkbox" name="repVentaGenUsuario" id="repVentaGenUsuario" value="repVentaGenUsuario"></input> <span>Usuarios</span></li>
                                                                                <li><input type="checkbox" name="repVendedorVentas" id="repVendedorVentas" value="repVendedorVentas"></input> <span>Vendedores</span></li>
                                                                                <li><input type="checkbox" name="repDiarioCaja" id="repDiarioCaja" value="repDiarioCaja"></input> <span>Diario de caja</span></li>
                                                                                <li><input type="checkbox" name="repDiarioCajaTotal" id="repDiarioCajaTotal" value="repDiarioCajaTotal"></input> <span>Diario de Caja Total</span></li>
                                                                                <li><input type="checkbox" name="repVentaProductos" id="repVentaProductos" value="repVentaProductos"></input> <span>Resumen de productos vendidos</span></li>
                                                                            </ul>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <input type="checkbox" name="repResumenDe" id="repResumenDe" value="repResumenDe"></input> <span>Resúmenes</span>
                                                                        <div name="menuRepResumenDe" id="menuRepResumenDe">
                                                                            <ul class="treeview-menu">
                                                                                <li><input type="checkbox" name="repFacturasResumen" id="repFacturasResumen" value="repFacturasResumen"></input> <span>Facturas</span></li>
                                                                                <li><input type="checkbox" name="repFacturasAnuladas" id="repFacturasAnuladas" value="repFacturasAnuladas"></input> <span>Facturas Anuladas</span></li>
                                                                                <li><input type="checkbox" name="repNotasCredito" id="repNotasCredito" value="repNotasCredito"></input> <span>Notas de Crédito</span></li>
                                                                                <li><input type="checkbox" name="repGeneralFacturas" id="repGeneralFacturas" value="repGeneralFacturas"></input> <span>General Facturas</span></li>
                                                                                <li><input type="checkbox" name="repGeneralNotaVenta" id="repGeneralNotaVenta" value="repGeneralNotaVenta"></input> <span>General Notas de Venta</span></li>
                                                                                <li><input type="checkbox" name="repFacturaDetallada" id="repFacturaDetallada" value="repFacturaDetallada"></input> <span>Facturas Detalladas</span></li>
                                                                                <li><input type="checkbox" name="repClienteProducto" id="repClienteProducto" value="repClienteProducto"></input> <span>Productos por Cliente</span></li>
                                                                            </ul>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <input type="checkbox" name="repRetFactVenta" id="repRetFactVenta" value="repRetFactVenta"></input> <span>Retenciones</span>
                                                                        <div name="menuRepRetFactVenta" id="menuRepRetFactVenta">
                                                                            <ul class="treeview-menu">
                                                                                <li><input type="checkbox" name="repFVRetFuente" id="repFVRetFuente" value="repFVRetFuente"></input> <span>Retenciones en la Fuente</span></li>
                                                                                <li><input type="checkbox" name="repFVRetIva" id="repFVRetIva" value="repFVRetIva"></input> <span>Retenciones de IVA</span></li>
                                                                                <li>
                                                                                    <input type="checkbox" name="repFVBuscarRet" id="repFVBuscarRet" value="repFVBuscarRet"></input> <span>Buscar Retención</span>
                                                                                    <div name="menuRepFVBuscarRet" id="menuRepFVBuscarRet">
                                                                                        <ul class="treeview-menu">
                                                                                            <li><input type="checkbox" name="repFVBusRetFuente" id="repFVBusRetFuente" value="repFVBusRetFuente"></input> <span>Retenciones en la Fuente</span></li>
                                                                                            <li><input type="checkbox" name="repFVBusRetIva" id="repFVBusRetIva" value="repFVBusRetIva"></input> <span>Retenciones de IVA</span></li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <input type="checkbox" name="repAutorizaciones" id="repAutorizaciones" value="repAutorizaciones"></input> <span>Autorizaciones</span>
                                                                        <div name="menuRepAutorizaciones" id="menuRepAutorizaciones">
                                                                            <ul class="treeview-menu">
                                                                                <li><input type="checkbox" name="repClienteAut" id="repClienteAut" value="repClienteAut"></input> <span>Clientes</span></li>
                                                                                <li><input type="checkbox" name="repClienteFechas" id="repClienteFechas" value="repClienteFechas"></input> <span>Clientes Fechas</span></li>
                                                                                <li><input type="checkbox" name="repCaducidadClientes" id="repCaducidadClientes" value="repCaducidadClientes"></input> <span>Caducidad Clientes</span></li>
                                                                            </ul>
                                                                        </div>
                                                                    </li>
                                                                    <li><input type="checkbox" name="repUtilidadProducto" id="repUtilidadProducto" value="repUtilidadProducto"></input> <span>Utilidad de producto</span></li>
                                                                    <li><input type="checkbox" name="repUtilidadFactura" id="repUtilidadFactura" value="repUtilidadFactura"></input> <span>Utilidad por factura</span></li>
                                                                    <li><input type="checkbox" name="repUtilidadGenFacturas" id="repUtilidadGenFacturas" value="repUtilidadGenFacturas"></input> <span>Utilidad General Facturas</span></li>
                                                                    <li><input type="checkbox" name="repNumeroSerie" id="repNumeroSerie" value="repNumeroSerie"></input> <span>Números de Serie</span></li>
                                                                    <li><input type="checkbox" name="repVentasClientes" id="repVentasClientes" value="repVentasClientes"></input> <span>Reporte de Ventas por Cliente y Beneficiario</span></li>
                                                                    <li><input type="checkbox" name="repAporteSocios" id="repAporteSocios" value="repAporteSocios"></input> <span>Reporte de Socios Pagados</span></li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" name="repReservaciones" id="repReservaciones" value="repReservaciones"></input> <span>Reservaciones</span>
                                                            <div name="menuRepVentas" id="menuRepReservaciones">
                                                                <ul class="treeview-menu">
                                                                    <li><input type="checkbox" name="repReservacionGeneral" id="repReservacionGeneral" value="repReservacionGeneral"></input> <span>Reservaciones General</span></li>
                                                                    <li><input type="checkbox" name="repProductoReservacion" id="repProductoReservacion" value="repProductoReservacion"></input> <span>Reservación por Producto</span></li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" name="repCartera" id="repCartera" value="repCartera"></input> <span>Cartera</span>
                                                            <div name="menuRepCartera" id="menuRepCartera">
                                                                <ul class="treeview-menu">
                                                                    <li>
                                                                        <input type="checkbox" name="repCuentasCobrar" id="repCuentasCobrar" value="repCuentasCobrar"></input> <span>Cuentas por cobrar</span>
                                                                        <div name="menuRepCuentasCobrar" id="menuRepCuentasCobrar">
                                                                            <ul class="treeview-menu">
                                                                                <li><input type="checkbox" name="repFactCanceladasCob" id="repFactCanceladasCob" value="repFactCanceladasCob"></input> <span>Resumen Cuentas por Cobrar</span></li>
                                                                                <li><input type="checkbox" name="repFactXCobrar" id="repFactXCobrar" value="repFactXCobrar"></input> <span>Facturas por cobrar</span></li>
                                                                                <li><input type="checkbox" name="repCobRealizados" id="repCobRealizados" value="repCobRealizados"></input> <span>Cobros realizados</span></li>
                                                                            </ul>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <input type="checkbox" name="repCuentasPagar" id="repCuentasPagar" value="repCuentasPagar"></input> <span>Cuentas por pagar</span>
                                                                        <div name="menuRepCuentasPagar" id="menuRepCuentasPagar">
                                                                            <ul class="treeview-menu">
                                                                                <li><input type="checkbox" name="repFactCanceladasPag" id="repFactCanceladasPag" value="repFactCanceladasPag"></input> <span>Facturas Canceladas</span></li>
                                                                                <li><input type="checkbox" name="repFactXPagar" id="repFactXPagar" value="repFactXPagar"></input> <span>Facturas por pagar</span></li>
                                                                                <li><input type="checkbox" name="repPagRealizados" id="repPagRealizados" value="repPagRealizados"></input> <span>Pagos realizados</span></li>
                                                                            </ul>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" name="repTransferencias" id="repTransferencias" value="repTransferencias"></input> <span>Transferencias</span>
                                                            <div name="menuRepTransferencias" id="menuRepTransferencias">
                                                                <ul class="treeview-menu">
                                                                    <li><input type="checkbox" name="repIngresos" id="repIngresos" value="repIngresos"></input> <span>Ingresos</span></li>
                                                                    <li><input type="checkbox" name="repEgresos" id="repEgresos" value="repEgresos"></input> <span>Egresos</span></li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" name="repGastos" id="repGastos" value="repGastos"></input> <span>Gastos</span>
                                                            <div name="menuRepGastos" id="menuRepGastos">
                                                                <ul class="treeview-menu">
                                                                    <li><input type="checkbox" name="repGastFactura" id="repGastFactura" value="repGastFactura"></input> <span>Gastos por factura</span></li>
                                                                    <li><input type="checkbox" name="repGasGenerales" id="repGasGenerales" value="repGasGenerales"></input> <span>Gastos Generales</span></li>
                                                                    <li><input type="checkbox" name="repGasIntFechas" id="repGasIntFechas" value="repGasIntFechas"></input> <span>Gastos Internos Fechas</span></li>
                                                                     <li><input type="checkbox" name="gastos_personales_re" id="gastos_personales_re" value="gastos_personales_re"></input> <span>Gastos Personales</span></li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" name="repFletes" id="repFletes" value="repFletes"></input> <span>Fletes</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" name="repBalances" id="repBalances" value="repBalances"></input> <span>Balances</span>
                                                            <div name="menuRepBalances" id="menuRepBalances">
                                                                <ul class="treeview-menu">
                                                                    <li><input type="checkbox" name="repBalComprobacion" id="repBalComprobacion" value="repBalComprobacion"></input> <span>Balance de Comprobación</span></li>
                                                                    <li><input type="checkbox" name="repBalResultados" id="repBalResultados" value="repBalResultados"></input> <span>Balance de Resultados</span></li>
                                                                    <li><input type="checkbox" name="repBalGeneral" id="repBalGeneral" value="repBalGeneral"></input> <span>Balance General</span></li>
                                                                    <li><input type="checkbox" name="repEstadoEfe" id="repEstadoEfe" value="repEstadoEfe"></input> <span>Estado de Flujo de Efectivo</span></li>
                                                                    <li><input type="checkbox" name="repEstadoPatr" id="repEstadoPatr" value="repEstadoPatr"></input> <span>Estado de Evolucion del Patrimonio</span></li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" name="repConta" id="repConta" value="repConta"></input> <span>Contabilidad</span>
                                                            <div name="menuRepConta" id="menuRepConta">
                                                                <ul class="treeview-menu">
                                                                    <li><input type="checkbox" name="repCuentaContable" id="repCuentaContable" value="repCuentaContable"></input> <span>Cuenta Contable</span></li>

                                                                    <li><input type="checkbox" name="repLibroDiario" id="repLibroDiario" value="repLibroDiario"></input> <span>Libro Diario</span></li>
                                                                    <li><input type="checkbox" name="repMayorGeneral" id="repMayorGeneral" value="repMayorGeneral"></input> <span>Mayor General</span></li>
                                                                    <li><input type="checkbox" name="repSaldosCuenta" id="repSaldosCuenta" value="repSaldosCuenta"></input> <span>Saldos Cartera</span></li>
                                                                    <li><input type="checkbox" name="repEstadosCuenta" id="repEstadosCuenta" value="repEstadosCuenta"></input> <span>Estados de Cuenta</span></li>
                                                                    <!-- <li><input type="checkbox" name="repEstadoPG" id="repEstadoPG" value="repEstadoPG"></input> <span>Estado de Pérdidas y Ganancias</span></li> -->
                                                                    <li><input type="checkbox" name="repPlanCuenta" id="repPlanCuenta" value="repPlanCuenta"></input> <span>Plan de Cuentas</span></li>
                                                                    <li><input type="checkbox" name="repGiraProv" id="repGiraProv" value="repGiraProv"></input> <span>Giras Proveedores</span></li>
                                                                    <li><input type="checkbox" name="repConciBancaria" id="repConciBancaria" value="repConciBancaria"></input> <span>Conciliación Bancaria</span></li>
                                                                </ul>
                                                            </div>
                                                        </li>

                                                        <li>
                                                            <input type="checkbox" name="repOrdenes" id="repOrdenes" value="repOrdenes"></input> <span>Ordenes de Producción</span>
                                                            <div name="menuRepOrdenes" id="menuRepOrdenes">
                                                                <ul class="treeview-menu">
                                                                    <li><input type="checkbox" name="repOrdenesGen" id="repOrdenesGen" value="repOrdenesGen"></input> <span>Ordenes General</span></li>
                                                                    <li><input type="checkbox" name="repOrdenesDes" id="repOrdenesDes" value="repOrdenesDes"></input> <span>Ordenes No Aprobadas</span></li>
                                                                    <li><input type="checkbox" name="repRecetasGen" id="repRecetasGen" value="repRecetasGen"></input> <span>Recetas General</span></li>
                                                                </ul>
                                                            </div>
                                                        </li>

                                                        <li>
                                                            <input type="checkbox" name="repMantenimiento" id="repMantenimientos" value="repMantenimientos"></input> <span>Mantenimiento</span>
                                                            <div name="menuRepMantenimiento" id="menuRepMantenimiento">
                                                                <ul class="treeview-menu">
                                                                    <li><input type="checkbox" name="repMantenimiento" id="repMantenimiento" value="repMantenimiento"></input> <span>Mantenimintos Registrados</span></li>
                                                                    <li><input type="checkbox" name="repManteniminetoPend" id="repManteniminetoPend" value="repManteniminetoPend"></input> <span>Mantenimientos Pendientes de Cobro</span></li>
                                                                </ul>
                                                            </div>
                                                        </li>

                                                        <li><input type="checkbox" name="repRetenciones" id="repRetenciones" value="repRetenciones"></input> <span>Retenciones</span></li>
                                                        <li>
                                                            <input type="checkbox" name="repNomina" id="repNomina" value="repNomina"></input> <span>Nomina</span>
                                                            <div name="menuRepNomina" id="menuRepNomina">
                                                                <ul class="treeview-menu">
                                                                    <li><input type="checkbox" name="repNomina" id="repNomina" value="repNomina"></input> <span>Reporte de Nomina</span></li>

                                                                </ul>
                                                            </div>
                                                        </li>
                                                        <li><input type="checkbox" name="repAts" id="repAts" value="repAts"></input> <span>ATS</span></li>
                                                        <li><input type="checkbox" name="repositorio" id="repositorio" value="repositorio"></input> <span>Repositorio</span></li>
                                                    </ul>
                                                </div>
                                            </li>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <?php footer(); ?>
    </div>

    <script src="../../plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <script src="../../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../../plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
    <script src="../../plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
    <script src="../../plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
    <script src="../../plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
    <script src="../../plugins/colorpicker/bootstrap-colorpicker.min.js" type="text/javascript"></script>
    <script src="../../plugins/timepicker/bootstrap-timepicker.min.js" type="text/javascript"></script>
    <script src="../../plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="../../plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script src='../../plugins/fastclick/fastclick.min.js'></script>
    <script src='../../plugins/select2/select2.full.min.js'></script>
    <script src="../../dist/js/app.min.js" type="text/javascript"></script>
    <script src="../../dist/js/validCampoFranz.js" type="text/javascript"></script>
    <script src="../../dist/js/alertify.min.js" type="text/javascript"></script>
    <script src="../../dist/js/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>
    <script src="../../dist/js/jquery.jqGrid.src.js" type="text/javascript"></script>
    <script src="../../dist/js/grid.locale-es.js" type="text/javascript"></script>
    <script src="usuarios.js" type="text/javascript"></script>
    <link href="../../dist/css/style.css" rel="stylesheet" type="text/css" />
    <script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>

</body>

</html>