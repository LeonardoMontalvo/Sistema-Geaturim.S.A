<?php
include '../../procesos/base.php';
conectarse();

$idprod = $_GET["id_producto"];
$sql = "select cod_productos from productos where cod_productos=$idprod and inventariable='Si'";
$res = pg_query($sql);
if (pg_num_rows($res) > 0) {
    echo json_encode("Si");
} else {
    echo json_encode("No");
}
