<?php 
session_start();
include('../menu/app.php'); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>PROVEEDORES</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- ✅ CSS ONLINE -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>

  <style>
    .content-header h1 { font-weight: 600; color: #0d6efd; }
    label { font-weight: 500; }
  </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">

  <!-- ✅ BANNER Y MENÚ -->
  <?php banner_1(); ?>
  <?php menu_lateral_1(); ?>

  <!-- ✅ CONTENIDO PRINCIPAL -->
  <main class="app-main">
    <div class="app-content-header">
      <div class="container-fluid">
        <h3 class="m-3"><i class="fas fa-truck"></i> Registro de Proveedores</h3>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Ingresos</a></li>
          <li class="breadcrumb-item active">Proveedores</li>
        </ol>
      </div>
    </div>

    <div class="app-content">
      <div class="container-fluid">

        <!-- 🧱 FORMULARIO PRINCIPAL -->
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-edit"></i> Datos del Proveedor</h5>
          </div>
          <div class="card-body">
            <form id="proveedores_form" name="proveedores_form" method="post">
              <div class="row g-4">
                <!-- COLUMNA 1 -->
                <div class="col-md-4">
                  <label>Tipo Documento:</label>
                  <select class="form-select" name="tipo_docu" id="tipo_docu">
                    <option value="Cedula">Cédula</option>
                    <option value="Ruc">RUC</option>
                    <option value="Pasaporte">Pasaporte</option>
                  </select>

                  <input type="hidden" name="id_proveedor" id="id_proveedor">

                  <label class="mt-3">Empresa:</label>
                  <input class="form-control" name="empresa_pro" id="empresa_pro" placeholder="Nombre de la Empresa">

                  <label class="mt-3">Visitador:</label>
                  <input class="form-control" name="visitador" id="visitador" placeholder="Empleado Empresa">

                  <label class="mt-3">Teléfono:</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-phone"></i></span>
                    <input class="form-control" name="nro_telefono" id="nro_telefono">
                  </div>

                  <label class="mt-3">E-mail:</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                    <input class="form-control" name="correo" id="correo" placeholder="Email">
                  </div>

                  <label class="mt-3">País:</label>
                  <input class="form-control" name="pais_pro" id="pais_pro" placeholder="Ingrese un país">
                </div>

                <!-- COLUMNA 2 -->
                <div class="col-md-4">
                  <label>RUC/CI:</label>
                  <input class="form-control" name="ruc_ci" id="ruc_ci">

                  <label class="mt-3">Representante Legal:</label>
                  <input class="form-control" name="representante_legal" id="representante_legal">

                  <label class="mt-3">Dirección:</label>
                  <input class="form-control" name="direccion_pro" id="direccion_pro">

                  <label class="mt-3">Celular:</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-mobile"></i></span>
                    <input class="form-control" name="nro_celular" id="nro_celular">
                  </div>

                  <label class="mt-3">Fax:</label>
                  <input class="form-control" name="fax" id="fax">

                  <label class="mt-3">Ciudad:</label>
                  <input class="form-control" name="ciudad_pro" id="ciudad_pro">
                </div>

                <!-- COLUMNA 3 -->
                <div class="col-md-4">
                  <label>Forma de Pago:</label>
                  <select class="form-select" name="forma_pago" id="forma_pago">
                    <option value="Contado" selected>Contado</option>
                    <option value="Credito">Crédito</option>
                  </select>

                  <label class="mt-3">Proveedor con Retención:</label>
                  <select class="form-select" name="principal_pro" id="principal_pro">
                    <option value="Si" selected>Si</option>
                    <option value="No">No</option>
                  </select>

                  <label class="mt-3">Cupo de Crédito:</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-money-bill"></i></span>
                    <input class="form-control" name="cupo_credito" id="cupo_credito" placeholder="0.00">
                  </div>

                  <label class="mt-3">Tipo:</label>
                  <select class="form-select" name="tipo_pro" id="tipo_pro">
                    <option value="Persona Natural" selected>Persona Natural</option>
                    <option value="Persona Jurídica">Persona Jurídica</option>
                  </select>

                  <label class="mt-3">Comentarios:</label>
                  <textarea class="form-control" name="observaciones_pro" id="observaciones_pro" rows="3"></textarea>
                </div>
              </div>
            </form>

            <!-- BOTONES -->
            <div class="mt-4 text-center">
              <button class="btn btn-success" id='btnGuardar'><i class="fa fa-save"></i> Guardar</button>
              <button class="btn btn-warning" id='btnModificar'><i class="fa fa-edit"></i> Modificar</button>
              <button class="btn btn-danger" id='btnEliminar'><i class="fa fa-trash"></i> Eliminar</button>
              <button class="btn btn-info" id='btnBuscar'><i class="fa fa-search"></i> Buscar</button>
              <button class="btn btn-secondary" id='btnNuevo'><i class="fa fa-pencil"></i> Nuevo</button>
            </div>

          </div>
        </div>
      </div>
    </div>
  </main>

  <?php footer(); ?>
</div>

<!-- ✅ JS ONLINE -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

<!-- Tu JS propio -->
<script src="proveedores.js"></script>

</body>
</html>
