<?php
session_start();
include_once __DIR__ . '/../../procesos/base.php';
require_once __DIR__ . '/../../procesos/detalleProductosBodega.php';

error_reporting(0);
$conexion = conectarse();
$stock = obtenerStock($_GET["id"], $_SESSION["PV"]);
echo (!empty($stock) ? $stock : 0);
