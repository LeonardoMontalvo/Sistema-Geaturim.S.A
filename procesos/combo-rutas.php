<?php
session_start();
include 'base.php';
conectarse();

$consulta = pg_query("SELECT id_ruta, nombre_ruta from rutas where estado='Activo'");
echo "<select id=id_ruta>";
echo "<option value='0'>TODAS</option></select>";
while ($row = pg_fetch_assoc($consulta)) {
    echo "<option value='$row[id_ruta]'>$row[nombre_ruta]</option>";
}
echo "</select>";
