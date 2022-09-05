<?php

class CategoriasDAO {

    public function obtenerCategoria($id) {
        $sql = "SELECT C.nombre_categoria FROM categoria C WHERE C.id_categoria=$id";
        return pg_fetch_row(pg_query($sql))[0];
    }

}
