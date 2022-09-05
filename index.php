<?php

/*
  echo 'HTTP_HOST: ' . $_SERVER['HTTP_HOST'];
  echo '<br>';
  echo 'HTTP_REFERER: ' . $_SERVER['HTTP_REFERER'];
  echo '<br>';
  echo 'REQUEST_URI: ' . $_SERVER['REQUEST_URI'];
  echo '<br>';
  echo 'SERVER_NAME: ' . $_SERVER['SERVER_NAME']; */

$ruta = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "data/";

//echo '<br>RUTA: ' . $ruta;
//header("Location: http://" . $ruta, true, 301);
header("Location: http://" . $ruta);
exit();
