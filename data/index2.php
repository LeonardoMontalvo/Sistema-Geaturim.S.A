<?php
session_start();
include_once('../procesos/base.php');
include_once __DIR__ . "/../procesos/configuracion.php";

$config = new Configuracion();

$url = pageURL();
$url = explode($config->getPrefijoUrlEsquema(), $url);
$esquema = $url[1];

$esquema = seleccionar($esquema);
if (!$esquema) {
  $cookie_name = "esquema";
  unset($_COOKIE[$cookie_name]);
}

function seleccionar($nomesquema)
{
  if (empty($nomesquema)) {
    return false;
  } else {
    $esquema = obtenerEsquema($nomesquema);

    if (empty($esquema)) {
      return false;
    }
  }
  return setCookieEsquema($nomesquema, $esquema);
}

function obtenerEsquema($nombre)
{
  $sql = "select * from manejo_esquemas.esquemas where estado='Activo' and nombre='$nombre'";
  $res = pg_query($sql);
  $rows = pg_fetch_all($res);
  return $rows[0];
}

function pageURL()
{

  $url = $_SERVER['REQUEST_URI'];
  $url = explode('/', $url);
  $lastPart = array_pop($url);

  return $lastPart;
}

function getFullUrl()
{
  $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  return $actual_link;
}

function setCookieEsquema($nomesquema, $esquema)
{
  $cookie_name = "esquema";
  $cookie_value = $nomesquema;
  setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
  $data = $cookie_value;

  $valores = [];
  if (!empty($esquema["color"])) {
    $valores["color_esquema"] = $esquema["color"];
  }
  $valores["url_esquema"] = getFullUrl();
  $cookie_name1 = "valores_app";
  setcookie($cookie_name1, json_encode($valores), time() + (86400 * 30), "/"); // 86400 = 1 day
  return $data;
}
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
  <div class="alert" style="background-color: #0097A7; color:#fff; font-size:12pt; text-align:center;display: <?php echo (empty($esquema) ? 'none' : '') ?>;">
    Pantalla de Acceso - Empresa <strong><?php echo mb_strtoupper($esquema) ?></strong>
  </div>
  <div class="alert" style="background-color: #FFA726; color:#fff; font-size:12pt; text-align:center;display: <?php echo (empty($esquema) ? '' : 'none') ?>;">
    <span class="glyphicon glyphicon-alert"></span> Empresa no encontrada
  </div>
  <div class="login-box" style="display: <?php echo (empty($esquema) ? 'none' : '') ?>;">
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
  <!-- <script src="../data/esquemas/login/seleccionar_esquema.js" type="text/javascript"></script> -->
</body>

</html>