<?php

session_start();
error_reporting(0);

$usuario_id=$_SESSION[id];

echo $usuario_id;
?>
