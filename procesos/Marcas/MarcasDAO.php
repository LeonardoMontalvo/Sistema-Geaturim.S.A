<?php

class MarcasDAO {

    public function obtenerMarca($id) {
        $sql = "SELECT M.nombre_marca FROM marcas M WHERE M.id_marca=$id";
        return pg_fetch_row(pg_query($sql))[0];
    }

}
