<?php

//include_once 'procesarKardex.php';
session_start();
include '../../procesos/base.php';
include_once './funcionesProcesarKardex.php';
conectarse();

/* echo '<br>';
  echo 'PRODUCTO: ' . $_GET['product'];
  echo '<br>'; */

$bodega = '1';
$respuesta = "";
$page = $_GET['page']; // get the requested page 
$limit = $_GET['rows']; // get how many rows we want to have into the grid 
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort 
$sord = $_GET['sord']; // get the direction 
if (!$sidx)
    $sidx = 1;

$sqlTotal = "SELECT COUNT(*)AS total FROM kardex WHERE cod_productos=$_GET[product]  AND estado!=''";
//$resulTotal= pg_query($sqlTotal);
$rowTotal = pg_fetch_assoc(pg_query($sqlTotal));
$count = $rowTotal['total'];
/* echo '<br>';
  echo 'COUNT: '.$count;
  echo '<br>';
  echo 'LIMIT: '.$limit;
  echo '<br>'; */
if ($count > 0 && $limit > 0) {
    $total_pages = ceil($count / $limit);
} else {
    $total_pages = 0;
}
/* echo '<br>';
  echo 'TOTAL PAGE: '.$total_pages;
  echo '<br>'; */
if ($page > $total_pages)
    $page = $total_pages;
$start = $limit * $page - $limit;

$sql = "SELECT * FROM kardex WHERE cod_productos=$_GET[product]  AND estado!='' ORDER BY id_kardex offset $start limit $limit";
$result = pg_query($sql);
/* echo '<br>';
  echo 'CONSULTA PAGINACION';
  echo '<br>';
  echo $sql;
  echo '<br>'; */
$respuesta = (object) array('page' => $page, 'total' => $total_pages, 'records' =>$count, 'rows' => "");
$respuesta->page = $page;
$respuesta->total = $total_pages;
$respuesta->records = $count;
$i = 0;
while ($row = pg_fetch_assoc($result)) {
    $respuesta->rows[$i]['id'] = $row['id_kardex'];
    $respuesta->rows[$i]['cell'] = array($row['id_kardex'], $row['comprobante'], $row['detalle'], $row['fecha_kardex'], $row['cod_productos'],
        $row['cantidad'], $row['saldo']);
    $i++;
}
var_dump($respuesta);
echo json_encode($respuesta);