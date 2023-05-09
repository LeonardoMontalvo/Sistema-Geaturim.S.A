<?php

if (empty($_SESSION['id'])) {
    header('Location: ../');
}

$nombre_esquema = mb_strtoupper($_COOKIE["esquema"]);
$valores_app = json_decode($_COOKIE["valores_app"], true);

// pie de pagina
function footer()
{
    print ' <footer class="main-footer">
        <strong>Copyright &copy; 2015 <a href="">P&S System</a>.</strong> Todos los derechos reservados.
      </footer>';
}

///
// banner o cabecera
function banner_1()
{
    global $nombre_esquema;
    global $valores_app;
    $nombrepv = $_SESSION["PV_NOMBRE"];
    $color_nav_header = (!empty($valores_app["color_esquema"]) ? ' style="background-color: ' . $valores_app["color_esquema"] . '"' : "");

    print '
    <style>
        .ui-datepicker select.ui-datepicker-month,
        .ui-datepicker select.ui-datepicker-year {
            color:black!important;
        }
        .ui-autocomplete {
            max-height: 300px;
            overflow-y: auto;
            /* prevent horizontal scrollbar */
            overflow-x: hidden;
          }
    </style>
	<header class="main-header">
        <!-- Logo -->
        <a href="index.php" class="logo" ' . $color_nav_header . '><b>SISWEB</b></a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation" ' . $color_nav_header . '>
          <!-- Sidebar toggle button-->
          <a href="" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <div class="collapse navbar-collapse pull-left">
          <ul class="nav navbar-nav" style="background:#263238;">
          <li><a style="font-weight:bold;"> EMPRESA SELECCIONADA: "' . $nombre_esquema . '"</a></li>
          </ul>
          </div>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu" style="display:flex">
               <div style="align-self:center; color:#fff; padding:14px; background:#263238; font-weight:bold;">PUNTO VENTA: "' . $nombrepv . '"</div>
                <a href="" class="dropdown-toggle" data-toggle="dropdown">
                  <span class="hidden-xs">' . $_SESSION['nombres'] . '</span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="../../dist/img/defaul.png" class="img-circle" alt="User Image" />
                    <p>
                      ' . $_SESSION['nombres'] . '
                    </p>
                  </li>
                                
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="../configuracion" class="btn btn-default btn-flat">Ajustes</a>
                    </div>
                    <div class="pull-right">
                      <a href="../usuario.php?accion=salir" class="btn btn-default btn-flat">Salir</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>
';
}

// menu principal lateral
function menu_lateral_1()
{
    echo '
<aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">          
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="active treeview">';
    $x = count($_SESSION['permisos']);
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'parametros') {
            echo '<a href="">
                    <i class="fa fa-share"></i> <span>Parámetros</span>
                    <i class="fa fa-angle-left pull-right"></i>
                  </a>';
        }
    }
    echo '<ul class="treeview-menu">
                <!--<li><a href=""><i class="fa fa-circle-o"></i>Empresa</a></li>-->
                <!--<li><a href=""><i class="fa fa-circle-o"></i>Privilegios</a></li>-->';
    echo '
                    <!--<li>';
    echo '<a href=""><i class="fa fa-circle-o"></i>Facturación<i class="fa fa-angle-left pull-right"></i></a>';
    echo '<ul class="treeview-menu">';
    echo '<li><a href=""><i class="fa fa-circle-o"></i>Impuestos Ventas/Compras</a></li>';
    echo '<li><a href=""><i class="fa fa-circle-o"></i>Retención en Impuesto</a></li>';
    echo '<li><a href=""><i class="fa fa-circle-o"></i>Retención en Fuente</a></li>';
    echo '<li><a href=""><i class="fa fa-circle-o"></i>Segundo Impuesto Ventas/Compras</a></li>';
    echo '</ul>
                    </li>-->
                    <li>';
    $x = count($_SESSION['permisos']);
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'inventario')
            echo '<a href=""><i class="fa fa-circle-o"></i>Inventario<i class="fa fa-angle-left pull-right"></i></a>';
    }
    echo '<ul class="treeview-menu">';
    $x = count($_SESSION['permisos']);
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'bodegas')
            echo '<li><a href="../bodegas" target="_blank"><i class="fa fa-circle-o"></i>Bodegas</a></li>';
        if ($_SESSION['permisos'][$i] == 'categorias')
            echo '<li><a href="../categorias" target="_blank"><i class="fa fa-circle-o"></i>Categoria</a></li>';
        if ($_SESSION['permisos'][$i] == 'marcas')
            echo '<li><a href="../marcas" target="_blank"><i class="fa fa-circle-o"></i>Marcas</a></li>';
        if ($_SESSION['permisos'][$i] == 'generico')
            echo '<li><a href="../generico" target="_blank"><i class="fa fa-circle-o"></i>Nombres Genèricos</a></li>';
        if ($_SESSION['permisos'][$i] == 'aplicacion')
            echo '<li><a href="../Aplicacion" target="_blank"><i class="fa fa-circle-o"></i>Aplicaciòn</a></li>';
        if ($_SESSION['permisos'][$i] == 'unidadesProd')
            echo '<li><a href="../medida" target="_blank"><i class="fa fa-circle-o"></i>Unidades Productos</a></li>';
        //        if ($_SESSION['permisos'][$i] == 'retFuente')
        //            echo '<li><a href="../retenciones_fuente" target="_blank"><i class="fa fa-circle-o"></i>Ret. en la Fuente</a></li>';
        //        if ($_SESSION['permisos'][$i] == 'retIva')
        //            echo '<li><a href="../retenciones_iva" target="_blank"><i class="fa fa-circle-o"></i>Ret. de IVA</a></li>';
        //        if ($_SESSION['permisos'][$i] == 'cuentaParametro')
        //            echo '<li><a href="../parametros" target="_blank"><i class="fa fa-circle-o"></i>Parámetros Contables</a></li>';
    }
    echo '</ul>
                  </li> 
                   <li>';
    $x = count($_SESSION['permisos']);
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'parametrosContables')
            echo '<a href=""><i class="fa fa-circle-o"></i>Parametros Contables<i class="fa fa-angle-left pull-right"></i></a>';
    }

    echo '<ul class="treeview-menu">';
    $x = count($_SESSION['permisos']);
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'retFuente')
            echo '<li><a href="../retenciones_fuente" target="_blank"><i class="fa fa-circle-o"></i>Ret. en la Fuente</a></li>';
        if ($_SESSION['permisos'][$i] == 'retIva')
            echo '<li><a href="../retenciones_iva" target="_blank"><i class="fa fa-circle-o"></i>Ret. de IVA</a></li>';
        if ($_SESSION['permisos'][$i] == 'cuentaParametro')
            echo '<li><a href="../parametros" target="_blank"><i class="fa fa-circle-o"></i>Parámetros Plan cuentas</a></li>';

        if ($_SESSION['permisos'][$i] == 'planCuentas')
            echo '<li><a href="../plan_cuentas" target="_blank"><i class="fa fa-circle-o"></i>Plan de Cuentas</a></li>';
    }
    echo '</ul>
                    </li>
                    
                   ';
    $x = count($_SESSION['permisos']);
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'respaldo')
            echo '<li><a href="../../procesos/backup.php"><i class="fa fa-circle-o"></i>Respaldo</a></li>';
        //        if ($_SESSION['permisos'][$i] == 'planCuentas')
        //            echo '<li><a href="../plan_cuentas" target="_blank"><i class="fa fa-circle-o"></i>Plan de Cuentas</a></li>';
        if ($_SESSION['permisos'][$i] == 'empresa')
            echo '<li><a href="../empresa" target="_blank"><i class="fa fa-circle-o"></i>Empresa</a></li>';
        if ($_SESSION['permisos'][$i] == 'esquemasBd')
            echo '<li><a href="../esquemas" target="_blank"><i class="fa fa-circle-o"></i>Empresas BD</a></li>';
    }
    echo '</ul>
            </li>

            <li class="treeview">';
    $x = count($_SESSION['permisos']);
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'ingresosUsuarios') {
            echo ' <a href="">
                    <i class="fa fa-laptop"></i> <span>Ingresos</span> <i class="fa fa-angle-left pull-right"></i>
                  </a>';
        }
    }
    echo '<ul class="treeview-menu">';
    echo '<li>';
    $x = count($_SESSION['permisos']);
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'usuarios') {
            echo '<a href="" target="_blank"><i class="fa fa-circle-o"></i> Usuarios<i class="fa fa-angle-left pull-right"></i></a>';
        }
    }
    echo '<ul class="treeview-menu">';
    $x = count($_SESSION['permisos']);
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'ingresoUsu')
            echo '<li><a href="../usuarios" target="_blank"><i class="fa fa-circle-o"></i> Ingreso de Usuarios</a></li>';
        if ($_SESSION['permisos'][$i] == 'permisoUsu')
            echo '<li><a href="../usuarios/permisosUsuarios.php" target="_blank"><i class="fa fa-circle-o"></i> Permisos de Usuario</a></li>';
    }
    echo '</ul>
                </li>';
    $x = count($_SESSION['permisos']);
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'proveedores')
            echo '<li><a href="../proveedores" target="_blank"><i class="fa fa-circle-o"></i> Proveedores</a></li>';
        if ($_SESSION['permisos'][$i] == 'vendedores')
            echo '<li><a href="../vendedores" target="_blank"><i class="fa fa-circle-o"></i> Vendedores</a></li>';
        if ($_SESSION['permisos'][$i] == 'beneficiario')
            echo '<li><a href="../rutas" target="_blank"><i class="fa fa-circle-o"></i> Rutas</a></li>';
        if ($_SESSION['permisos'][$i] == 'clientes')
            echo '<li><a href="../clientes" target="_blank"><i class="fa fa-circle-o"></i> Clientes</a></li>';
        if ($_SESSION['permisos'][$i] == 'productos')
            echo '<li><a href="../productos" target="_blank"><i class="fa fa-circle-o"></i> Productos</a></li>';
        if ($_SESSION['permisos'][$i] == 'registroFac')
            echo '<li><a href="../autorizacion_venta" target="_blank"><i class="fa fa-circle-o"></i> Registro Factureros</a></li>';
        if ($_SESSION['permisos'][$i] == 'anulaFac')
            echo '<li><a href="../anular_facturas" target="_blank"><i class="fa fa-circle-o"></i> Anular Facturas</a></li>';
        if ($_SESSION['permisos'][$i] == 'conductores')
            echo '<li><a href="../contrato_conductor" target="_blank"><i class="fa fa-circle-o"></i> Conductores</a></li>';
        if ($_SESSION['permisos'][$i] == 'vehiculos')
            echo '<li><a href="../contrato_vehiculo" target="_blank"><i class="fa fa-circle-o"></i> Vehículos</a></li>';
        if ($_SESSION['permisos'][$i] == 'contratos')
            echo '<li><a href="../contrato" target="_blank"><i class="fa fa-circle-o"></i> Fletes</a></li>';
        if ($_SESSION['permisos'][$i] == 'costos') {
            //TODO costos
            echo '<li>';
            echo '<a href="" target="_blank"><i class="fa fa-circle-o"></i> Costos<i class="fa fa-angle-left pull-right"></i></a>';
            echo '<ul class="treeview-menu">';
            $x = count($_SESSION['permisos']);
            for ($i = 0; $i < $x; $i++) {
                if ($_SESSION['permisos'][$i] == 'ingresoCCosto') {
                    echo '<li><a href="../centro_costos" target="_blank"><i class="fa fa-circle-o"></i> Centros de Costo</a></li>';
                }
            }
            echo '</ul>';
            echo '</li>';
        }
    }

    echo '</ul>
            </li>

            <li class="treeview">';
    $x = count($_SESSION['permisos']);
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'procesos') {
            echo '<a href="">
                    <i class="fa fa-files-o"></i> <span>Procesos</span> <i class="fa fa-angle-left pull-right"></i>
                  </a>';
        }
    }
    echo '<ul class="treeview-menu">';
    $x = count($_SESSION['permisos']);
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'proInventario')
            echo '<li><a href="../inventario" target="_blank"><i class="fa fa-circle-o"></i> Inventario</a></li>';
        if ($_SESSION['permisos'][$i] == 'proforma')
            echo '<li><a href="../proformas" target="_blank"><i class="fa fa-circle-o"></i> Proforma</a></li>';
        if ($_SESSION['permisos'][$i] == 'liquidacion_compra')
            echo '<li><a href="../liquidacion_compras" target="_blank"><i class="fa fa-circle-o"></i> Liquidación Compra</a></li>';
    }
    echo '<li>';
    $x = count($_SESSION['permisos']);
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'compras')
            echo '<a href=""><i class="fa fa-circle-o"></i>Compras<i class="fa fa-angle-left pull-right"></i></a>';
    }
    echo '<ul class="treeview-menu">';
    $x = count($_SESSION['permisos']);
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'productosBod')
            echo '<li><a href="../factura_compra" target="_blank"><i class="fa fa-circle-o"></i>Productos Bodega</a></li>';
        if ($_SESSION['permisos'][$i] == 'devolucionCom')
            echo '<li><a href="../devolucion_compra" target="_blank"><i class="fa fa-circle-o"></i>Notas de crédito</a></li>';
    }
    echo '</ul>
                </li>

                <li>';
    $x = count($_SESSION['permisos']);
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'ventas')
            echo '<a href=""><i class="fa fa-circle-o"></i>Ventas<i class="fa fa-angle-left pull-right"></i></a>';
    }
    echo '<ul class="treeview-menu">';
    $x = count($_SESSION['permisos']);
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'ventasFac')
            echo '<li><a href="../factura_venta" target="_blank"><i class="fa fa-circle-o"></i>Ventas facturación</a></li>';
        if ($_SESSION['permisos'][$i] == 'notasCre')
            echo '<li><a href="../notas_credito" target="_blank"><i class="fa fa-circle-o"></i>Notas de crédito</a></li>';
    }
    echo '</ul>
                </li>

                <li>';
    $x = count($_SESSION['permisos']);
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'cartera')
            echo '<a href=""><i class="fa fa-circle-o"></i>Cartera<i class="fa fa-angle-left pull-right"></i></a>';
    }
    echo '<ul class="treeview-menu">';
    $x = count($_SESSION['permisos']);
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'anticiposCli')
            echo '<li><a href="../anticipo_clientes" target="_blank"><i class="fa fa-circle-o"></i>Anticipo Clientes</a></li>';
        if ($_SESSION['permisos'][$i] == 'anticiposPro')
            echo '<li><a href="../anticipo_proveedores" target="_blank"><i class="fa fa-circle-o"></i>Anticipo Proveedores</a></li>';
    }
    $x = count($_SESSION['permisos']);
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'cuentasCob')
            echo '<li><a href="../cuentas_cobrar" target="_blank"><i class="fa fa-circle-o"></i>Cuentas por cobrar</a></li>';
        if ($_SESSION['permisos'][$i] == 'cuentasPag')
            echo '<li><a href="../cuentas_pagar" target="_blank"><i class="fa fa-circle-o"></i>Cuentas por pagar</a></li>';
    }
    echo '<li>';
    $x = count($_SESSION['permisos']);
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'cartera')
            echo '<a href=""><i class="fa fa-circle-o"></i>Externas<i class="fa fa-angle-left pull-right"></i></a>';
    }
    echo '<ul class="treeview-menu">';
    $x = count($_SESSION['permisos']);
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'cuentasCobExt')
            echo '<li><a href="../cxc_externa" target="_blank"><i class="fa fa-circle-o"></i>Cuentas por cobrar</a></li>';
        if ($_SESSION['permisos'][$i] == 'cuentasPagExt')
            echo '<li><a href="../cxp_externa" target="_blank"><i class="fa fa-circle-o"></i>Cuentas por pagar</a></li>';
    }
    echo '</ul>
                    </li>
                  </ul>
                </li>

                <li>';
    $x = count($_SESSION['permisos']);
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'transferencias')
            echo '<a href=""><i class="fa fa-circle-o"></i>Transferencias<i class="fa fa-angle-left pull-right"></i></a>';
    }
    echo '<ul class="treeview-menu">';
    $x = count($_SESSION['permisos']);
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'ingresos')
            echo '<li><a href="../ingresos" target="_blank"><i class="fa fa-circle-o"></i>Ingresos</a></li>';
        if ($_SESSION['permisos'][$i] == 'egresos')
            echo '<li><a href="../egresos" target="_blank"><i class="fa fa-circle-o"></i>Egresos</a></li>';
    }
    echo '</ul>
                </li>';
    $x = count($_SESSION['permisos']);
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'asientoCont')
            echo '<li><a href="../asientos_contables" target="_blank"><i class="fa fa-circle-o"></i>Asientos Contables</a></li>';
        if ($_SESSION['permisos'][$i] == 'registrosGas')
            echo '<li><a href="../registro_gastos" target="_blank"><i class="fa fa-circle-o"></i>Registro Gastos</a></li>';
        if ($_SESSION['permisos'][$i] == 'gastosInt')
            echo '<li><a href="../gastos" target="_blank"><i class="fa fa-circle-o"></i>Gastos Internos</a></li>';
    }
    echo '<li>';
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'kardex')
            echo '<a href=""><i class="fa fa-circle-o"></i>Kardex<i class="fa fa-angle-left pull-right"></i></a>';
    }
    echo '<ul class="treeview-menu">';
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'kardexGeneral')
            echo '<li><a href="../kardex" target="_blank"><i class="fa fa-circle-o"></i>Kardex General</a></li>';
        if ($_SESSION['permisos'][$i] == 'kardexVal')
            echo '<li><a href="../kardex_valorado" target="_blank"><i class="fa fa-circle-o"></i>Kardex Valorado</a></li>';
        if ($_SESSION['permisos'][$i] == 'kardexPro')
            echo '<li><a href="../kardex_producto" target="_blank"><i class="fa fa-circle-o"></i>Kardex a la fecha productos</a></li>';
    }
    echo '</ul>
                  </li>';
    echo '<li>';
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'reservaciones')
            echo '<a href=""><i class="fa fa-circle-o"></i>Reservaciones<i class="fa fa-angle-left pull-right"></i></a>';
    }
    echo '<ul class="treeview-menu">';
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'ingresoReservacion')
            echo '<li><a href="../reservaciones" target="_blank"><i class="fa fa-circle-o"></i>Ingreso Reservación</a></li>';
        if ($_SESSION['permisos'][$i] == 'cobroReservacion')
            echo '<li><a href="../cobro_reservaciones" target="_blank"><i class="fa fa-circle-o"></i>Cobro Reservación</a></li>';
    }
    echo '</ul>
                  </li>';
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'conciliacionBancaria')
            echo '<li><a href="../conciliacion_bancaria" target="_blank"><i class="fa fa-circle-o"></i>Conciliación Bancaria</a></li>';
    }
    echo '<li>';

    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'nomina')
            echo '<a href=""><i class="fa fa-circle-o"></i>Nomina<i class="fa fa-angle-left pull-right"></i></a>';
    }
    echo '<ul class="treeview-menu">';
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'prolpagos_anti')
            echo '<li><a href="../Anticipo" target="_blank"><i class="fa fa-circle-o"></i>Anticipo Nomina</a></li>';
        if ($_SESSION['permisos'][$i] == 'prolpagos')
            echo '<li><a href="../nomina" target="_blank"><i class="fa fa-circle-o"></i>Rol de Pagos Parametros</a></li>';
        if ($_SESSION['permisos'][$i] == 'crolpagos')
            echo '<li><a href="../rol_pagos" target="_blank"><i class="fa fa-circle-o"></i>Rol de Pagos Individual</a></li>';
    }
    echo '</ul>
                </li>';
    echo '<li>';
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'mantenimiento')
            echo '<a href=""><i class="fa fa-circle-o"></i>Mantenimiento<i class="fa fa-angle-left pull-right"></i></a>';
    }
    echo '<ul class="treeview-menu">';
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'ingresosEquipos')
            echo '<li><a href="../registro_equipo" target="_blank"><i class="fa fa-circle-o"></i>Ingreso Equipos</a></li>';
        if ($_SESSION['permisos'][$i] == 'proformaTecnico')
            echo '<li><a href="../ingreso_tecnico" target="_blank"><i class="fa fa-circle-o"></i>Registro Técnico</a></li>';
    }
    echo '</ul>
                </li>';
    echo '<li>';
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'ordenes_produccion')
            echo '<a href=""><i class="fa fa-circle-o"></i>Ordenes de Producción<i class="fa fa-angle-left pull-right"></i></a>';
    }
    echo '<ul class="treeview-menu">';
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'recetas')
            echo '<li><a href="../recetas" target="_blank"><i class="fa fa-circle-o"></i>Recetas</a></li>';
        if ($_SESSION['permisos'][$i] == 'nuevaOrden')
            echo '<li><a href="../orden_produccion" target="_blank"><i class="fa fa-circle-o"></i>Crear Orden de Producción</a></li>';
        if ($_SESSION['permisos'][$i] == 'aprobarOrden')
            echo '<li><a href="../aprobar_orden" target="_blank"><i class="fa fa-circle-o"></i>Aprobar Orden de Producción</a></li>';
    }
    echo '</ul></li>';
    if (in_array('auditoria', $_SESSION['permisos'])) {
        echo '<li><a href="../auditoria" target="_blank"><i class="fa fa-circle-o"></i>Registro Auditoria</a></li>';
    }

    echo '<li>';
    $x = count($_SESSION['permisos']);
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'restaurantes')
            echo '<a href=""><i class="fa fa-circle-o"></i>Restaurantes<i class="fa fa-angle-left pull-right"></i></a>';
    }
    echo '<ul class="treeview-menu">';
    $x = count($_SESSION['permisos']);
    for ($i = 0; $i < $x; $i++) {
        if ($_SESSION['permisos'][$i] == 'restaurantesOrdenes')
            echo '<li><a href="../restaurantes_ordenes" target="_blank"><i class="fa fa-circle-o"></i>Ordenes</a></li>';
    }
    echo '</ul>
                            </li>';

    echo '</ul></li>';
    // Reportes
    if (in_array('reportes', $_SESSION['permisos'])) {
        echo '<li>';
        echo '<a href=""><i class="fa fa-circle-o"></i>Reportes<i class="fa fa-angle-left pull-right"></i></a>';
        echo '<ul class="treeview-menu">';
        // Reportes Centro Costos
        if (in_array('repCentCostos', $_SESSION['permisos'])) {
            echo "<li>";
            echo '<a href=""><i class="fa fa-circle-o"></i>Centro de Costos<i class="fa fa-angle-left pull-right"></i></a>';
            echo '<ul class="treeview-menu">';
            if (in_array('repResDocsCC', $_SESSION['permisos'])) {
                echo '<li><a id="repResDocsCC" href="" target="_blank"><i class="fa fa-files-o"></i>Resumen</a></li>';
            }
            echo '</ul>';
            echo "</li>";
        }
        // Reportes Productos
        if (in_array('repProductos', $_SESSION['permisos'])) {
            echo '<li>';
            echo '<a href=""><i class="fa fa-circle-o"></i>Productos<i class="fa fa-angle-left pull-right"></i></a>';
            echo '<ul class="treeview-menu">';
            if (in_array('repConsultarProdPv', $_SESSION['permisos'])) {
                echo '<li><a href="../consulta_productos_pv" target="_blank"><i class="fa fa-files-o"></i>Consultar Existencias en Bodegas</a></li>';
            }
            if (in_array('repListaPrecios', $_SESSION['permisos']))
                echo '<li><a href="" id="producto_general"><i class="fa fa-files-o"></i>Lista de Precios</a></li>';
            if (in_array('repProductosGen', $_SESSION['permisos']))
                echo '<li><a href="" id="producto_general_precio"><i class="fa fa-files-o"></i>General</a></li>';
            if (in_array('repProdCatMar', $_SESSION['permisos']))
                echo '<li><a href="" id="producto_marca_categoria"><i class="fa fa-files-o"></i>Categorías y Marcas</a></li>';
            if (in_array('repProdCat', $_SESSION['permisos']))
                echo '<li><a href="" id="producto_categoria"><i class="fa fa-files-o"></i>Categorías</a></li>';
            if (in_array('repProdMar', $_SESSION['permisos']))
                echo '<li><a href="" id="producto_marca"><i class="fa fa-files-o"></i>Marcas</a></li>';
            if (in_array('repProProv', $_SESSION['permisos']))
                echo '<li><a href="" id="producto_proveedor"><i class="fa fa-files-o"></i>Proveedores</a></li>';
            if (in_array('repExisMin', $_SESSION['permisos']))
                echo '<li><a href="" id="producto_existencia_minima"><i class="fa fa-files-o"></i>Existencia Mínima</a></li>';
            if (in_array('repPlantContP', $_SESSION['permisos']))
                echo '<li><a href="" id="plantilla_conteo_prod"><i class="fa fa-files-o"></i>Plantilla para conteo de productos</a></li>';
            echo '</ul></li>';
        }
        // Rrporte Inventario
        if (in_array('repInventario', $_SESSION['permisos']))
            echo '<li><a href="" id="reporte_inventario"><i class="fa fa-files-o"></i>Inventario</a></li>';
        // Reportes Compras
        if (in_array('repCompras', $_SESSION['permisos'])) {
            echo '<li>';
            echo '<a href=""><i class="fa fa-circle-o"></i>Compras<i class="fa fa-angle-left pull-right"></i></a>';
            echo '<ul class="treeview-menu">';
            // Resumenes
            if (in_array('repComprasLocales', $_SESSION['permisos'])) {
                echo '<li>';
                echo '<a href=""><i class="fa fa-circle-o"></i>Resúmenes<i class="fa fa-angle-left pull-right"></i></a>';
                echo '<ul class="treeview-menu">';
                if (in_array('repFacturasProveedor', $_SESSION['permisos']))
                    echo '<li><a href="#" id="resumenFacturas"><i class="fa fa-files-o"></i>General</a></li>';
                if (in_array('repAgrupadosProvCompras', $_SESSION['permisos']))
                    echo '<li><a href="#" id="agrupados_proveedor"><i class="fa fa-files-o"></i>Agrupados Proveedor</a></li>';
                if (in_array('repDevolucionCompras', $_SESSION['permisos']))
                    echo '<li><a href="#" id="reporte_dev_compras"><i class="fa fa-files-o"></i>Devolución Compra</a></li>';
                if (in_array('repFactAgrupadas', $_SESSION['permisos']))
                    echo '<li><a href="#" id="resumenFacturasCompras"><i class="fa fa-files-o"></i>Facturas Agrupadas</a></li>';
                if (in_array('repFactDetalladas', $_SESSION['permisos']))
                    echo '<li><a href="#" id="resumenDetalleCompras"><i class="fa fa-files-o"></i>Facturas Detalladas</a></li>';
                if (in_array('repNotaVenta', $_SESSION['permisos']))
                    echo '<li><a href="#" id="resumenCNotaVenta"><i class="fa fa-files-o"></i>Notas de Venta</a></li>';
                echo '</ul></li>';
            }
            // Retenciones
            if (in_array('repRetFactCompra', $_SESSION['permisos'])) {
                echo '<li>';
                echo '<a href=""><i class="fa fa-circle-o"></i>Retenciones<i class="fa fa-angle-left pull-right"></i></a>';
                echo '<ul class="treeview-menu">';
                // Buscar 
                if (in_array('repFCBuscarRet', $_SESSION['permisos'])) {
                    echo '<li>';
                    echo '<a href=""><i class="fa fa-circle-o"></i>Buscar Retención<i class="fa fa-angle-left pull-right"></i></a>';
                    echo '<ul class="treeview-menu">';
                    if (in_array('repFCBusRetFuente', $_SESSION['permisos']))
                        echo '<li><a href="#" id="fc_retencion_fuente"><i class="fa fa-files-o"></i>En la Fuente</a></li>';
                    if (in_array('repFCBusRetIva', $_SESSION['permisos']))
                        echo '<li><a href="#" id="fc_retencion_iva"><i class="fa fa-files-o"></i>IVA</a></li>';
                    echo '</ul></li>';
                }
                if (in_array('repFCRetFuente', $_SESSION['permisos']))
                    echo '<li><a href="#" id="rf_factura_compra"><i class="fa fa-files-o"></i>En la Fuente</a></li>';
                if (in_array('repFCRetIva', $_SESSION['permisos']))
                    echo '<li><a href="#" id="ri_factura_compra"><i class="fa fa-files-o"></i>IVA</a></li>';
                echo '</ul></li>';
            }
            // Comprobante
            if (in_array('repFacturasCompras', $_SESSION['permisos']))
                echo '<li><a href="#" id="reporte_factura_compra"><i class="fa fa-files-o"></i>Comprobantes</a></li>';
            // Otros
            echo '<li><a href="" id="repPlantillaCompras"><i class="fa fa-files-o"></i>Plantilla Compras</a></li>';
            echo '<li><a href="" id="resumenCNotaVentahcp"><i class="fa fa-files-o"></i>Historial Compras</a></li>';
            echo '</ul></li>';
        }
        // Ventas
        if (in_array('repVentas', $_SESSION['permisos'])) {
            echo '<li>';
            echo '<a href=""><i class="fa fa-circle-o"></i>Ventas<i class="fa fa-angle-left pull-right"></i></a>';
            echo '<ul class="treeview-menu">';
            // Flujo de Caja
            if (in_array('repFlujoCaja', $_SESSION['permisos'])) {
                echo '<li>';
                echo '<a href=""><i class="fa fa-circle-o"></i>Flujo de Caja<i class="fa fa-angle-left pull-right"></i></a>';
                echo '<ul class="treeview-menu">';
                if (in_array('repVentaGenCliente', $_SESSION['permisos']))
                    echo '<li><a href="" id="ventaGeneralClientes"><i class="fa fa-files-o"></i>Ventas Clientes</a></li>';
                if (in_array('repVentaGeneral', $_SESSION['permisos']))
                    echo '<li><a href="" id="ventaGeneral"><i class="fa fa-files-o"></i>Ventas General</a></li>';
                if (in_array('repVentaGenUsuario', $_SESSION['permisos']))
                    echo '<li><a href="" id="ventaGeneralUsuarios"><i class="fa fa-files-o"></i>Ventas Usuarios</a></li>';
                if (in_array('repDiarioCaja', $_SESSION['permisos']))
                    echo '<li><a href="" id="diario_caja"><i class="fa fa-files-o"></i>Diario de caja por Usuario</a></li>';
                if (in_array('repDiarioCajaTotal', $_SESSION['permisos']))
                    echo '<li><a href="" id="diario_caja_total"><i class="fa fa-files-o"></i>Diario de caja Total</a></li>';
                if (in_array('repVentaProductos', $_SESSION['permisos']))
                    echo '<li><a href="" id="resumenVentaProductos"><i class="fa fa-files-o"></i>Resumen de Productos Vendidos</a></li>';
                echo '</ul></li>';
            }
            // Resúmenes
            if (in_array('repResumenDe', $_SESSION['permisos'])) {
                echo '<li>';
                echo '<a href=""><i class="fa fa-circle-o"></i>Resúmenes<i class="fa fa-angle-left pull-right"></i></a>';
                echo '<ul class="treeview-menu">';
                if (in_array('repFacturasAnuladas', $_SESSION['permisos']))
                    echo '<li><a href="" id="reporte_facturas_notas_anuladas"><i class="fa fa-files-o"></i>Facturas Anuladas</a></li>';
                if (in_array('repGeneralFacturas', $_SESSION['permisos']))
                    echo '<li><a href="" id="reporte_general"><i class="fa fa-files-o"></i>General Facturas</a></li>';
                if (in_array('repNotasCredito', $_SESSION['permisos']))
                    echo '<li><a href="" id="reporte_general_notas_credito"><i class="fa fa-files-o"></i>General Notas de Credito</a></li>';
                if (in_array('repGeneralNotaVenta', $_SESSION['permisos']))
                    echo '<li><a href="" id="reporte_general_notas"><i class="fa fa-files-o"></i>General Notas de Venta</a></li>';
                if (in_array('repFacturaDetallada', $_SESSION['permisos']))
                    echo '<li><a href="" id="resumenDetalleVentas"><i class="fa fa-files-o"></i>Facturas Detalladas</a></li>';
                if (in_array('repClienteProducto', $_SESSION['permisos']))
                    echo '<li><a href="" id="resumenClienteProductos"><i class="fa fa-files-o"></i>Clientes por Productos</a></li>';
                if (in_array('repVendedorVentas', $_SESSION['permisos']))
                    echo '<li><a href="" id="resumenVendedorVentas"><i class="fa fa-files-o"></i>Facturas por Vendedor</a></li>';
                echo '</ul></li>';
            }
            // Retenciones
            if (in_array('repRetFactVenta', $_SESSION['permisos'])) {
                echo '<li>';
                echo '<a href=""><i class="fa fa-circle-o"></i>Retenciones<i class="fa fa-angle-left pull-right"></i></a>';
                echo '<ul class="treeview-menu">';
                // Buscar
                if (in_array('repFVBuscarRet', $_SESSION['permisos'])) {
                    echo '<li>';
                    echo '<a href=""><i class="fa fa-circle-o"></i>Buscar Retención<i class="fa fa-angle-left pull-right"></i></a>';
                    echo '<ul class="treeview-menu">';
                    if (in_array('repFVBusRetFuente', $_SESSION['permisos']))
                        echo '<li><a href="#" id="fv_retencion_fuente"><i class="fa fa-files-o"></i>En la Fuente</a></li>';
                    if (in_array('repFVBusRetIva', $_SESSION['permisos']))
                        echo '<li><a href="#" id="fv_retencion_iva"><i class="fa fa-files-o"></i>IVA</a></li>';
                    echo '</ul></li>';
                }
                if (in_array('repFVRetFuente', $_SESSION['permisos']))
                    echo '<li><a href="#" id="rf_factura_venta"><i class="fa fa-files-o"></i>En la Fuente</a></li>';
                if (in_array('repFVRetIva', $_SESSION['permisos']))
                    echo '<li><a href="#" id="ri_factura_venta"><i class="fa fa-files-o"></i>IVA</a></li>';
                echo '</ul></li>';
            }
            // Autorizaciones
            if (in_array('repAutorizaciones', $_SESSION['permisos'])) {
                echo '<li>';
                echo '<a href=""><i class="fa fa-circle-o"></i>Autorizaciones<i class="fa fa-angle-left pull-right"></i></a>';
                echo '<ul class="treeview-menu">';
                if (in_array('repClienteAut', $_SESSION['permisos']))
                    echo '<li><a href="" id="autorizaciones_cliente"><i class="fa fa-files-o"></i>Clientes</a></li>';
                if (in_array('repClienteFechas', $_SESSION['permisos']))
                    echo '<li><a href="" id="autorizaciones_cliente_fechas"><i class="fa fa-files-o"></i>Clientes Fechas</a></li>';
                if (in_array('repCaducidadClientes', $_SESSION['permisos']))
                    echo '<li><a href="" id="autorizaciones_cliente_caducidad"><i class="fa fa-files-o"></i>Caducidad Clientes</a></li>';
                echo '</ul></li>';
            }
            // Utilidades
            echo '<li>';
            echo '<a href=""><i class="fa fa-circle-o"></i>Utilidades<i class="fa fa-angle-left pull-right"></i></a>';
            echo '<ul class="treeview-menu">';
            if (in_array('repUtilidadGenFacturas', $_SESSION['permisos']))
                echo '<li><a href="" id="reporte_utilidad_factura_general"><i class="fa fa-files-o"></i>General Facturas</a></li>';
            if (in_array('repUtilidadProducto', $_SESSION['permisos']))
                echo '<li><a href="" id="reporte_utilidad_producto"><i class="fa fa-files-o"></i>Detalladas</a></li>';
            if (in_array('repUtilidadFactura', $_SESSION['permisos']))
                echo '<li><a href="" id="reporte_utilidad_factura"><i class="fa fa-files-o"></i>Por Factura</a></li>';
            echo '</ul></li>';
            // Otros
            if (in_array('repFacturasResumen', $_SESSION['permisos']))
                echo '<li><a href="" id="reporte_factura_venta"><i class="fa fa-files-o"></i>Comprobante Factura</a></li>';
            if (in_array('repNotasCredito', $_SESSION['permisos']))
                echo '<li><a href="" id="reporte_nota_credito"><i class="fa fa-files-o"></i>Comprobante N. Crédito</a></li>';
            if (in_array('repNumeroSerie', $_SESSION['permisos']))
                echo '<li><a href="" id="buscar_serie"><i class="fa fa-files-o"></i>Números de Serie</a></li>';
            if (in_array('repVentasClientes', $_SESSION['permisos']))
                echo '<li><a href="" id="venta_clientes"><i class="fa fa-files-o"></i>Clientes y Beneficiarios</a></li>';
            if (in_array('repAporteSocios', $_SESSION['permisos']))
                echo '<li><a href="" id="aporte_socios"><i class="fa fa-files-o"></i>Socios Pagados</a></li>';
            echo '</ul></li>';
        }
        // Reservaciones
        if (in_array('repReservaciones', $_SESSION['permisos'])) {
            echo '<li>';
            echo '<a href=""><i class="fa fa-circle-o"></i>Reservaciones<i class="fa fa-angle-left pull-right"></i></a>';
            echo '<ul class="treeview-menu">';
            if (in_array('repReservacionGeneral', $_SESSION['permisos']))
                echo '<li><a href="" id="reporte_reservacion"><i class="fa fa-circle-o"></i>Reservaciones General</a></li>';
            if (in_array('repProductoReservacion', $_SESSION['permisos']))
                echo '<li><a href="" id="reporte_producto_reservacion"><i class="fa fa-circle-o"></i>Reservación por Producto</a></li>';
            echo '</ul></li>';
        }
        // Cartera
        if (in_array('repCartera', $_SESSION['permisos'])) {
            echo '<li>';
            echo '<a href=""><i class="fa fa-circle-o"></i>Cartera<i class="fa fa-angle-left pull-right"></i></a>';
            echo '<ul class="treeview-menu">';
            // Cuentas por Cobrar
            if (in_array('repCuentasCobrar', $_SESSION['permisos'])) {
                echo '<li>';
                echo '<a href=""><i class="fa fa-circle-o"></i>Cuentas por cobrar<i class="fa fa-angle-left pull-right"></i></a>';
                echo '<ul class="treeview-menu">';
                // Resumenes
                if (in_array('repFactCanceladasCob', $_SESSION['permisos'])) {
                    echo '<li><a href=""><i class="fa fa-circle-o"></i>Resúmenes<i class="fa fa-angle-left pull-right"></i></a>';
                    echo '<ul class="treeview-menu">';
                    echo '<li><a href="" id="resumen_cxc"><i class="fa fa-files-o"></i>General</a></li>';
                    echo '<li><a href="" id="facturas_canceladas"><i class="fa fa-files-o"></i>Canceladas</a></li>';
                    echo '<li><a href="" id="reporte_cxc_activos"><i class="fa fa-files-o"></i>Por Caducidad</a></li>';
                    echo '</ul> </li>';
                }
                // Por Cobrar
                if (in_array('repFactXCobrar', $_SESSION['permisos'])) {
                    echo '<li><a href=""><i class="fa fa-circle-o"></i>Pendintes de Cobro<i class="fa fa-angle-left pull-right"></i></a>';
                    echo '<ul class="treeview-menu">';
                    echo '<li><a href="" id="facturas_cobrar_clientes"><i class="fa fa-files-o"></i>General</a></li>';
                    //echo '<li><a href="" id="facturas_cobrar_cliente"><i class="fa fa-files-o"></i>Por Cliente</a></li>';
                    echo '</ul> </li>';
                }
                // Cobros
                if (in_array('repCobRealizados', $_SESSION['permisos'])) {
                    echo '<li><a href=""><i class="fa fa-circle-o"></i>Cobros<i class="fa fa-angle-left pull-right"></i></a>';
                    echo '<ul class="treeview-menu">';
                    echo '<li><a href="" id="cobros_realizados"><i class="fa fa-files-o"></i>General</a></li>';
                    //echo '<li><a href="" id="cobros_clientes"><i class="fa fa-files-o"></i>Por Cliente</a></li>';
                    echo '</ul> </li>';
                }
                echo '</ul></li>';
            }
            // Cuentas por Pagar
            if (in_array('repCuentasPagar', $_SESSION['permisos'])) {
                echo '<li>';
                echo '<a href=""><i class="fa fa-circle-o"></i>Cuentas por pagar<i class="fa fa-angle-left pull-right"></i></a>';
                echo '<ul class="treeview-menu">';
                if (in_array('repFactCanceladasPag', $_SESSION['permisos'])) {
                    echo '<li><a href=""><i class="fa fa-circle-o"></i>Resúmenes<i class="fa fa-angle-left pull-right"></i></a>';
                    echo '<ul class="treeview-menu">';
                    echo '<li><a href="" id="resumen_cxp"><i class="fa fa-files-o"></i>General</a></li>';
                    echo '<li><a href="" id="facturas_canceladas_proveedor"><i class="fa fa-files-o"></i>Canceladas</a></li>';
                    echo '</ul> </li>';
                }
                if (in_array('repFactXPagar', $_SESSION['permisos'])) {
                    echo '<li><a href=""><i class="fa fa-circle-o"></i>Pendientes de Pago<i class="fa fa-angle-left pull-right"></i></a>';
                    echo '<ul class="treeview-menu">';
                    echo '<li><a href="" id="facturas_pagar"><i class="fa fa-files-o"></i>General</a></li>';
                    //echo '<li><a href="" id="facturas_pagar_proveedor"><i class="fa fa-files-o"></i>Por Proveedor</a></li>';
                    echo '</ul> </li>';
                }
                if (in_array('repPagRealizados', $_SESSION['permisos'])) {
                    echo '<li><a href=""><i class="fa fa-circle-o"></i>Pagos<i class="fa fa-angle-left pull-right"></i></a>';
                    echo '<ul class="treeview-menu">';
                    echo '<li><a href="" id="pagos_realizados"><i class="fa fa-files-o"></i>General</a></li>';
                    //echo '<li><a href="" id="pagos_proveedor"><i class="fa fa-files-o"></i>Por Proveedor</a></li>';
                    echo '</ul> </li>';
                }
                echo '</ul></li>';

                echo '<li><a href="" id="resumen_valores_favor_clientes_nc"><i class="fa fa-files-o"></i>Valores a Favor de Clientes por Notas de Crédito</a></li>';
                echo '<li><a href="" id="resumen_valores_favor_empresa_nc"><i class="fa fa-files-o"></i>Valores a Favor de la Epresa por Notas de Crédito en Compras</a></li>';
            }
            echo '</ul></li>';
        }
        // Transferencias
        if (in_array('repTransferencias', $_SESSION['permisos'])) {
            echo '<li>';
            echo '<a href=""><i class="fa fa-circle-o"></i>Transferencias<i class="fa fa-angle-left pull-right"></i></a>';
            echo '<ul class="treeview-menu">';
            if (in_array('repIngresos', $_SESSION['permisos']))
                echo '<li><a href="" id="repIngresos"><i class="fa fa-files-o"></i>Ingresos</a></li>';
            if (in_array('repEgresos', $_SESSION['permisos']))
                echo '<li><a href="" id="repEgresos"><i class="fa fa-files-o"></i>Egresos</a></li>';
            echo '</ul></li>';
        }
        // Gastos
        if (in_array('repGastos', $_SESSION['permisos'])) {
            echo '<li>';
            echo '<a href=""><i class="fa fa-circle-o"></i>Gastos<i class="fa fa-angle-left pull-right"></i></a>';
            echo '<ul class="treeview-menu">';
            if (in_array('repGastos', $_SESSION['permisos']))
                echo '<li><a href="" id="gastos"><i class="fa fa-files-o"></i>Gastos por Proveedor</a></li>';
            if (in_array('repGastos', $_SESSION['permisos']))
                echo '<li><a href="" id="gastos_general"><i class="fa fa-files-o"></i>Gastos Generales</a></li>';
            if (in_array('repGastos', $_SESSION['permisos']))
                echo '<li><a href="" id="gastos_internos"><i class="fa fa-files-o"></i>Gastos Internos Fechas</a></li>';
            echo '</ul></li>';
        }
        // Fletes
        if (in_array('repFletes', $_SESSION['permisos'])) {
            echo '<li>';
            echo '<a href=""><i class="fa fa-circle-o"></i>Fletes<i class="fa fa-angle-left pull-right"></i></a>';
            echo '<ul class="treeview-menu">';
            echo '<li><a href="" id="fletes_fechas"><i class="fa fa-files-o"></i>Fletes entre Fechas</a></li>';
            echo '<li><a href="" id="fletes_transporte"><i class="fa fa-files-o"></i>Fletes por Vehiculo</a></li>';
            echo '<li><a href="" id="fletes_conductor"><i class="fa fa-files-o"></i>Fletes por Conductor</a></li>';
            echo '<li><a href="" id="fletes_cliente"><i class="fa fa-files-o"></i>Fletes por Cliente</a></li>';
            echo '</ul></li>';
        }
        // Balances
        if (in_array('repBalances', $_SESSION['permisos'])) {
            echo '<li>';
            echo '<a href=""><i class="fa fa-circle-o"></i>Balances<i class="fa fa-angle-left pull-right"></i></a>';
            echo '<ul class="treeview-menu">';
            if (in_array('repBalComprobacion', $_SESSION['permisos']))
                echo '<li><a href="" id="repBalComprobacion"><i class="fa fa-files-o"></i>Balance de Comprobación</a></li>';
            if (in_array('repBalResultados', $_SESSION['permisos']))
                echo '<li><a href="" id="repBalResultados"><i class="fa fa-files-o"></i>Balance de Resultados</a></li>';
            if (in_array('repBalGeneral', $_SESSION['permisos']))
                echo '<li><a href="" id="repBalGeneral"><i class="fa fa-files-o"></i>Balance General</a></li>';
            if (in_array('repEstadoEfe', $_SESSION['permisos']))
                echo '<li><a href="../reporte_flujo_efectivo/index.php" target="_blank" id="repEstadoEfe"><i class="fa fa-files-o"></i>Estado Flujo de Efectivo</a></li>';
            if (in_array('repEstadoPatr', $_SESSION['permisos']))
                echo '<li><a href="" id="repEstadoPatr1"><i class="fa fa-files-o"></i>Estado de Evolucion del Patrimonio</a></li>';

            echo '</ul></li>';
        }
        // Contabilidad
        if (in_array("repConta", $_SESSION['permisos'])) {
            echo '<li>';
            echo '<a href=""><i class="fa fa-circle-o"></i>Contabilidad<i class="fa fa-angle-left pull-right"></i></a>';
            echo '<ul class="treeview-menu">';
            if (in_array('repCuentaContable', $_SESSION['permisos']))
                echo '<li><a href="" id="repCuentaContable"><i class="fa fa-files-o"></i>Cuenta Contable</a></li>';

            if (in_array('repLibroDiario', $_SESSION['permisos']))
                echo '<li><a href="" id="repLibroDiario"><i class="fa fa-files-o"></i>Libro Diario</a></li>';
            if (in_array('repMayorGeneral', $_SESSION['permisos']))
                echo '<li><a href="" id="repMayorGeneral"><i class="fa fa-files-o"></i>Mayor General</a></li>';
            if (in_array('repSaldosCuenta', $_SESSION['permisos']))
                echo '<li><a href="" id="repSaldosCuenta"><i class="fa fa-files-o"></i>Saldos Cartera</a></li>';
            if (in_array('repEstadosCuenta', $_SESSION['permisos']))
                echo '<li><a href="" id="repEstadosCuenta"><i class="fa fa-files-o"></i>Estados de Cuenta</a></li>';
            // if (in_array('repEstadoPG', $_SESSION['permisos']))
            // echo '<li><a href="" id="repEstadoPG"><i class="fa fa-files-o"></i>Estado de Pérdidas y Ganancias</a></li>';
            if (in_array('repPlanCuenta', $_SESSION['permisos']))
                echo '<li><a href="" id="rep_plan_cuenta"><i class="fa fa-files-o"></i>Plan de Cuentas</a></li>';
            if (in_array('repGiraProv', $_SESSION['permisos']))
                echo '<li><a href="" id="rep_gira_prov"><i class="fa fa-files-o"></i>Giras Proveedores</a></li>';
            if (in_array('repConciBancaria', $_SESSION['permisos']))
                echo '<li><a href="" id="rep_conci_bancaria"><i class="fa fa-files-o"></i>Conciliación Bancaria</a></li>';
            echo '</ul></li>';
        }
        // Ordenes de Produccion
        if (in_array('repOrdenes', $_SESSION['permisos'])) {
            echo '<li>';
            echo '<a href=""><i class="fa fa-circle-o"></i>Ordenes de Producción<i class="fa fa-angle-left pull-right"></i></a>';
            echo '<ul class="treeview-menu">';
            if (in_array('repOrdenesGen', $_SESSION['permisos']))
                echo '<li><a href="" id="repOrdenesGen"><i class="fa fa-circle-o"></i>Ordenes General</a></li>';
            if (in_array('repOrdenesDes', $_SESSION['permisos']))
                echo '<li><a href="" id="repOrdenesDes"><i class="fa fa-circle-o"></i>Ordenes No Aprobadas</a></li>';
            if (in_array('repRecetasGen', $_SESSION['permisos']))
                echo '<li><a href="" id="repRecetasGen"><i class="fa fa-circle-o"></i>Recetas General</a></li>';
            echo '</ul></li>';
        }
        // Mantenimiento
        if (in_array('repMantenimientos', $_SESSION['permisos'])) {
            echo '<li>';
            echo '<a href=""><i class="fa fa-circle-o"></i>Mantenimiento<i class="fa fa-angle-left pull-right"></i></a>';
            echo '<ul class="treeview-menu">';
            if (in_array('repMantenimiento', $_SESSION['permisos']))
                echo ' <li><a href="" id="repMante"><i class="fa fa-circle-o"></i>Mantenimientos Registrados</a></li>';
            if (in_array('repManteniminetoPend', $_SESSION['permisos']))
                echo ' <li><a href="" id="repMantePendientes"><i class="fa fa-circle-o"></i>Mantenimientos Pendientes de Cobro</a></li>';
            echo '</ul></li>';
        }
        // Retenciones
        if (in_array("repRetenciones", $_SESSION['permisos'])) {
            echo '<li>';
            echo '<a href=""><i class="fa fa-circle-o"></i>Retenciones<i class="fa fa-angle-left pull-right"></i></a>';
            echo '<ul class="treeview-menu">';
            echo '<li><a href="" id="retenciones_tesoreria"><i class="fa fa-circle-o"></i>General Tesoreria</a></li>';
            echo '<li><a href="" id="ri_factura_compra_compras_reten"><i class="fa fa-circle-o"></i>Renta General Factura Compra</a></li>';
            echo '<li><a href="" id="ri_factura_compra_compras_reten_gastos"><i class="fa fa-circle-o"></i>Impuesto Renta General Gastos</a></li>';
            echo '<li><a href="" id="ri_factura_compra_compras_reten_gastos_iva"><i class="fa fa-circle-o"></i>IVA Gastos</a></li>';
            echo '</ul></li>';
        }
        // Resumen de Compras y Ventas
        if (in_array("repCompras", $_SESSION['permisos']) && in_array("repVentas", $_SESSION['permisos']) && in_array("repRetenciones", $_SESSION['permisos'])) {
            echo '<li><a href="" id="resumen_compra_venta"><i class="fa fa-files-o"></i>Resumen de Compras y Ventas</a></li>';
        }
        ///REPORTE NOMINA
        if (in_array('repNomina', $_SESSION['permisos'])) {
            echo '<li>';
            echo '<a href=""><i class="fa fa-circle-o"></i>Nomina<i class="fa fa-angle-left pull-right"></i></a>';
            echo '<ul class="treeview-menu">';
            if (in_array('repNomina', $_SESSION['permisos']))
                echo '<li><a href="" id="nomina_repo"><i class="fa fa-files-o"></i>Nomina</a></li>';

            echo '</ul></li>';
        }
        // ATS
        if (in_array('repAts', $_SESSION['permisos']))
            echo '<li><a href="" id="ats"><i class="fa fa-files-o"></i>ATS</a></li>';
        // Repositorio
        if (in_array('repositorio', $_SESSION['permisos']))
            echo '<li><a href="../admin_repositorio" target="_blank"><i class="fa fa-files-o"></i>Repositorio</a></li>';
        echo '</ul></li>';
    }
    echo '<!--<li class="header">Otros.</li>
            <li><a href=""><i class="fa fa-circle-o text-danger"></i> Important</a></li>
            <li><a href=""><i class="fa fa-circle-o text-warning"></i> Warning</a></li>
            <li><a href=""><i class="fa fa-circle-o text-info"></i> Information</a></li>-->
          </ul>
        </section>
        <!-- /.sidebar -->';
    echo '</aside>';
}
