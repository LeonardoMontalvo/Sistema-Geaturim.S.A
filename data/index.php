<?php
session_start();
include_once('../procesos/base.php');
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>LOGIN</title>
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
      <a href=""><b>Admin</b>SISWEB</a>
    </div><!-- /.login-logo -->
    <div class="login-box-body">
      <p class="login-box-msg">Por favor, proporcione sus datos</p>
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
            <label>Punto de Venta: </label>
            <div class="input-group">
              <select class="form-control" name="punto_venta" id="punto_venta">
                <!--                             <option value="1">Puntos de venta...</option>-->
                <?php
                //                                $consulta = pg_query("select * from punto_venta ");
                //                                while ($row = pg_fetch_row($consulta)) {
                //                                    echo "<option id=$row[0] value=$row[0]>$row[1]</option>";
                //                                }
                ?>
              </select>
              <span class="input-group-btn">
                <!--<button class="btn btn-primary" type="button" id="btnPuntoventa">Agregar</button>-->
                <div class="col-xs-12">
                  <button type="submit" class="btn btn-primary btn-block btn-flat" id="btnIngreso">INGRESAR</button>
                </div>
              </span>
            </div>
          </div>
          <!--                            <div class="col-xs-4">    
                            <div class="checkbox icheck">
                           <label>
                           <input type="checkbox"> Recordar 
                           </label>
                         </div>                        
                       </div> /.col -->


          <!-- /.col -->
          <!-- <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat" id="btnRetornar">RETORNAR</button>
            </div> -->
        </div>
      </form>

      <!--  <div class="social-auth-links text-center">
          <p>- OR -</p>
          <a href="" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i>Facebook</a>
          <a href="" class="btn btn-block btn-social btn-google-plus btn-flat"><i class="fa fa-google-plus"></i>Google+</a>
        </div> -->
    </div><!-- /.login-box-body -->
  </div><!-- /.login-box -->

  <!-- jQuery 2.1.3 -->
  <script src="../plugins/jQuery/jQuery-2.1.3.min.js"></script>
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
        increaseArea: '20%' // optional
      });
    });
  </script>
   <script src="../data/esquemas/login/seleccionar_esquema.js" type="text/javascript"></script>
</body>

</html>