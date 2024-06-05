<?php
session_start();
include_once __DIR__ . '/../../procesos/base.php';
require_once __DIR__ . '/../../procesos/detalleProductosBodega.php';

error_reporting(0);
$pvinv = $_SESSION['PV_INV'];
$conexion = conectarse();
$stock = obtenerStock($_GET["id"], $pvinv);
echo (!empty($stock) ? $stock : 0);
