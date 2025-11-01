<?php
session_start();
include_once('../procesos/base.php');
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>LOGIN - GEATURIM S.A.</title>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="../../font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <link href="../../plugins/icon/ionicons.min.css" rel="stylesheet" type="text/css" />
  <link href="../dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
  <link href="../dist/css/alertify.core.css" rel="stylesheet" />
  <link href="../dist/css/alertify.default.css" id="toggleCSS" rel="stylesheet" />
  <link href="../plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />
  <link href="../dist/css/jquery-ui-1.10.4.custom.css" rel="stylesheet" type="text/css" />
  <link href="../dist/css/ui.jqgrid.css" rel="stylesheet" type="text/css" />
</head>

<body class="login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href=""><b>GEATURIM</b> S.A.</a>
    </div>
    <div class="login-box-body">
      <p class="login-box-msg">Bienvenido a Geaturim S.A. — Inicie sesión</p>
      <div id="login_esquema"></div>
      <form method="post" name="form_admin">
        <div class="form-group has-feedback">
          <input type="text" id="txt_usuario" name="txt_usuario" class="form-control" placeholder="Usuario" />
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="text-danger" id="usuario_incorrecto" style="display: none;">
          Usuario no encontrado
        </div>
        <div class="form-group has-feedback">
          <input type="password" id="txt_contra" name="txt_contra" class="form-control" placeholder="Contraseña" />
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
          <div class="col-md-12">
            <label>Punto de Venta (Geaturim): </label>
            <div class="input-group">
              <select class="form-control" name="punto_venta" id="punto_venta">
              </select>
              <span class="input-group-btn">
                <div class="col-12">
                  <button type="submit" class="btn btn-primary btn-block btn-flat" id="btnIngreso">INGRESAR</button>
                </div>
              </span>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  <script src="../plugins/jQuery/jquery-3.7.1.min.js"></script>
  <script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
  <script src="../plugins/iCheck/icheck.min.js" type="text/javascript"></script>
  <script src="../dist/js/validCampoFranz.js" type="text/javascript"></script>
  <script src="../dist/js/alertify.min.js" type="text/javascript"></script>
  <script src="../dist/js/index.js" type="text/javascript"></script>
  <link href="../dist/css/style.css" rel="stylesheet" type="text/css" />

  <script>
    $(function() {
      $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' 
      });
    });
  </script>
  <script src="../data/esquemas/login/seleccionar_esquema.js" type="text/javascript"></script>
</body>
</html>
