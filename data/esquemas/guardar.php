<?php
include_once __DIR__ . '/../../procesos/base.php';
$conexion = conectarse();

pg_query($conexion, "BEGIN");
echo json_encode(guardar());
pg_query($conexion, "COMMIT");

function guardar()
{
    $esquema = guardarEsquema(mb_strtolower($_POST["nombre_esquema"]), $_POST["descripcion_esquema"], $_POST["color_esquema"]);
    if (!empty($esquema) && empty($_POST["datos_prueba"])) {
        $empresa = guardarEmpresa($_POST['nombre_esquema'], $_POST);
        if (empty($empresa)) {
            return null;
        }
    }
    return $esquema;
}

function guardarEsquema($nombre, $descripcion, $color)
{
    global $conexion;
    $id = siguienteId();
    $sql = "insert into manejo_esquemas.esquemas values($id,'$nombre','$descripcion','Activo','f','$color');";
    $res = pg_query($conexion, $sql);
    if (empty($res)) {
        return null;
    }
    if (!is_dir(__DIR__ . "/../../xmls/$nombre")) {
        mkdir(__DIR__ . "/../../xmls/$nombre");
    }
    if (!is_dir(__DIR__ . "/../../atsxml/$nombre")) {
        mkdir(__DIR__ . "/../../atsxml/$nombre");
    }
    copiarUsadmin($_POST["usuario_admin"]);
    return $id;
}

function siguienteId()
{
    global $conexion;
    $sql = "select max(id_esquema) from manejo_esquemas.esquemas";
    $res = pg_query($conexion, $sql);
    $row = pg_fetch_row($res);
    return $row[0] + 1;
}

function guardarEmpresa($esquema, $datosempresa)
{
    global $conexion;
    $fecha = date("Y-m-d");
    $hora = date("g:m:s A");

    $sql = "
    update $esquema.empresa set
    nombre_empresa='" . mb_strtoupper($datosempresa["nombre_empresa"]) . "',
    ruc_empresa='$datosempresa[ruc_empresa]',
    direccion_empresa='$datosempresa[direccion_empresa]',
    telefono_empresa='$datosempresa[telefono]',
    celular_empresa='$datosempresa[celular]',
    pais_empresa='" . mb_strtoupper($datosempresa["pais"]) . "',
    ciudad_empresa='" . mb_strtoupper($datosempresa["ciudad"]) . "',
    email_empresa='$datosempresa[email]',
    pagina_web='$datosempresa[pagina_web]',
    propietario='" . mb_strtoupper($datosempresa["propietario_empresa"]) . "',
    nombre_comercial='" . mb_strtoupper($datosempresa["nombre_comercial"]) . "',
    obligacion='$datosempresa[obligacion]',
    contribuyente_espe='$datosempresa[contribuyente]',
    establecimiento='$datosempresa[establecimiento]',
    punto_emision='$datosempresa[p_emision]',
    token='',
    clave='';

    update $esquema.punto_venta set 
    nombre_punto='" . mb_strtoupper($datosempresa["punto_venta_nombre"]) . "',
    fecha_actual='$fecha',
    hora_actual='$hora',
    ubicacion='$datosempresa[punto_venta_direccion]',
    telefono='$datosempresa[punto_venta_telefono]';
    ";

    $res = pg_query($conexion, $sql);

    if (empty($res)) {
        return false;
    }
    return true;
}

function copiarUsadmin($idusuario)
{
    $sql = "select max(id_usuario) from " . $_POST["nombre_esquema"] . ".usuario";
    $res = pg_query($sql);
    $row = pg_fetch_row($res);
    $id = $row[0];
    if (empty($id)) {
        $id = 1;
    } else {
        $id += 1;
    }

    $sql = "insert into " . $_POST["nombre_esquema"] . ".usuario(
            id_usuario, nombre_usuario, apellido_usuario, ci_usuario, telefono_usuario, 
            celular_usuario, id_cargo_usuario, clave, email_usuario, direccion_usuario, 
            usuario, estado, permisos, hora_entrada, hora_salida, id_empresa, 
            estado_ingreso, fecha_actual
    )  select '$id' id_usuario,
            nombre_usuario, apellido_usuario, ci_usuario, telefono_usuario, 
            celular_usuario, id_cargo_usuario, clave, email_usuario, direccion_usuario, 
            usuario, estado, permisos, hora_entrada, hora_salida, id_empresa, 
            estado_ingreso, fecha_actual
     from public.usuario u
    where u.id_usuario=$idusuario";
    //var_dump($sql);
    $res = pg_query($sql);
    return $res;
}
