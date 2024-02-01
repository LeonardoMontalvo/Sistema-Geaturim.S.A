<?php
session_start();
$tmp = $_SESSION["dirtmp_facturas_xml"];
if (empty($tmp)) {
    exit();
}
$filename = $tmp . '/' . $_POST["nombre_xml"];
header('Content-Description: File Transfer');
header("Content-Type: application/xml");
header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filename));
readfile($filename);
