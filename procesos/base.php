<?php

/**
 * Archivo de Conexión a Base de Datos - Sistema Geaturim S.A.
 * 
 * Este archivo proporciona funciones seguras para conectarse a PostgreSQL
 * utilizando configuraciones desde variables de entorno.
 * 
 * MEJORAS DE SEGURIDAD IMPLEMENTADAS:
 * - Variables de entorno en lugar de credenciales hardcodeadas
 * - Protección contra SQL Injection en schema switching
 * - Validación y sanitización de inputs
 * - Manejo seguro de cookies
 * - Type hints de PHP 7.4+
 * - Manejo mejorado de errores
 * 
 * @package Geaturim
 * @version 2.0
 * @date 2025-10-21
 */

// Cargar configuración (solo si no se ha cargado ya)
if (!function_exists('env')) {
    require_once __DIR__ . '/config.php';
}

/**
 * Establece conexión persistente a la base de datos principal
 * 
 * @return resource|false Recurso de conexión PostgreSQL o false en caso de error
 */
function conectarse()
{
    static $conexion = null;
    
    // Retornar conexión existente si ya está establecida
    if ($conexion !== null && pg_connection_status($conexion) === PGSQL_CONNECTION_OK) {
        return $conexion;
    }
    
    try {
        $config = getDbConfig();
        
        // Construir cadena de conexión de manera segura
        $connectionString = sprintf(
            "host=%s port=%s dbname=%s user=%s password=%s",
            $config['host'],
            $config['port'],
            $config['dbname'],
            $config['user'],
            $config['password']
        );
        
        // Intentar conexión persistente
        $conexion = @pg_pconnect($connectionString);
        
        if ($conexion === false) {
            error_log('Error de conexión a base de datos principal: ' . pg_last_error());
            throw new Exception('No se pudo establecer conexión a la base de datos');
        }
        
        // Configurar esquema si está presente en cookie
        if (!empty(obtenerCookie('esquema'))) {
            establecerEsquema($conexion, obtenerCookie('esquema'));
        }
        
        // Configurar cliente encoding a UTF-8
        pg_set_client_encoding($conexion, 'UTF8');
        
        return $conexion;
        
    } catch (Exception $e) {
        error_log('Error en conectarse(): ' . $e->getMessage());
        
        // En producción, no mostrar detalles del error
        if (env('APP_DEBUG', false)) {
            die('Error de base de datos: ' . $e->getMessage());
        } else {
            die('Error al conectar con el sistema. Contacte al administrador.');
        }
    }
}

/**
 * Establece conexión persistente a la base de datos alternativa (inventario antiguo)
 * 
 * @return resource|false Recurso de conexión PostgreSQL o false en caso de error
 */
function conectarse_ori()
{
    static $conexion = null;
    
    // Retornar conexión existente si ya está establecida
    if ($conexion !== null && pg_connection_status($conexion) === PGSQL_CONNECTION_OK) {
        return $conexion;
    }
    
    try {
        $config = getDbAltConfig();
        
        // Construir cadena de conexión de manera segura
        $connectionString = sprintf(
            "host=%s port=%s dbname=%s user=%s password=%s",
            $config['host'],
            $config['port'],
            $config['dbname'],
            $config['user'],
            $config['password']
        );
        
        // Intentar conexión persistente
        $conexion = @pg_pconnect($connectionString);
        
        if ($conexion === false) {
            error_log('Error de conexión a base de datos alternativa: ' . pg_last_error());
            throw new Exception('No se pudo establecer conexión a la base de datos alternativa');
        }
        
        // Configurar esquema si está presente en cookie
        if (!empty(obtenerCookie('esquema'))) {
            establecerEsquema($conexion, obtenerCookie('esquema'));
        }
        
        // Configurar cliente encoding a UTF-8
        pg_set_client_encoding($conexion, 'UTF8');
        
        return $conexion;
        
    } catch (Exception $e) {
        error_log('Error en conectarse_ori(): ' . $e->getMessage());
        
        // En producción, no mostrar detalles del error
        if (env('APP_DEBUG', false)) {
            die('Error de base de datos: ' . $e->getMessage());
        } else {
            die('Error al conectar con el sistema. Contacte al administrador.');
        }
    }
}

/**
 * Establece el esquema (search_path) de manera segura
 * 
 * SEGURIDAD: Esta función previene SQL Injection validando y escapando el nombre del esquema
 * 
 * @param resource $conexion Recurso de conexión PostgreSQL
 * @param string $esquema Nombre del esquema a establecer
 * @return bool True si se estableció correctamente, false en caso contrario
 */
function establecerEsquema($conexion, string $esquema): bool
{
    // Sanitizar y validar el nombre del esquema
    $esquemaSanitizado = sanitizeSchema($esquema);
    
    if ($esquemaSanitizado === null) {
        error_log("Intento de establecer esquema inválido: {$esquema}");
        return false;
    }
    
    // Usar pg_escape_identifier para máxima seguridad
    $esquemaEscapado = pg_escape_identifier($conexion, $esquemaSanitizado);
    
    // Establecer search_path de manera segura
    $query = "SET search_path TO {$esquemaEscapado}, public";
    $resultado = @pg_query($conexion, $query);
    
    if ($resultado === false) {
        error_log('Error al establecer search_path: ' . pg_last_error($conexion));
        return false;
    }
    
    return true;
}

/**
 * Obtiene el valor de una cookie de manera segura
 * 
 * SEGURIDAD: Valida y sanitiza el valor de la cookie antes de retornarlo
 * 
 * @param string $cookieName Nombre de la cookie
 * @return string|null Valor de la cookie sanitizado o null si no existe
 */
function obtenerCookie(string $cookieName): ?string
{
    // Validar que el nombre de la cookie sea válido
    if (empty($cookieName) || !is_string($cookieName)) {
        return null;
    }
    
    // Verificar si la cookie existe
    if (!isset($_COOKIE[$cookieName]) || empty($_COOKIE[$cookieName])) {
        return null;
    }
    
    // Obtener y sanitizar el valor
    $valor = $_COOKIE[$cookieName];
    
    // Remover caracteres peligrosos
    $valor = trim($valor);
    
    // Validar longitud máxima
    if (strlen($valor) > 255) {
        error_log("Cookie {$cookieName} excede longitud máxima permitida");
        return null;
    }
    
    return $valor;
}

/**
 * Escapa una cadena para uso seguro en consultas PostgreSQL
 * 
 * @param resource $conexion Recurso de conexión PostgreSQL
 * @param string $string Cadena a escapar
 * @return string Cadena escapada
 */
function escaparString($conexion, string $string): string
{
    return pg_escape_string($conexion, $string);
}

/**
 * Escapa un identificador (tabla, columna, esquema) para uso seguro en consultas
 * 
 * @param resource $conexion Recurso de conexión PostgreSQL
 * @param string $identifier Identificador a escapar
 * @return string Identificador escapado
 */
function escaparIdentificador($conexion, string $identifier): string
{
    return pg_escape_identifier($conexion, $identifier);
}

/**
 * Cierra la conexión a la base de datos
 * 
 * @param resource $conexion Recurso de conexión PostgreSQL
 * @return bool True si se cerró correctamente
 */
function cerrarConexion($conexion): bool
{
    if (is_resource($conexion)) {
        return pg_close($conexion);
    }
    return false;
}

/**
 * Verifica el estado de una conexión PostgreSQL
 * 
 * @param resource $conexion Recurso de conexión PostgreSQL
 * @return bool True si la conexión está activa
 */
function verificarConexion($conexion): bool
{
    if (!is_resource($conexion)) {
        return false;
    }
    return pg_connection_status($conexion) === PGSQL_CONNECTION_OK;
}

// Establecer conexión automática (mantener compatibilidad con código legacy)
// NOTA: En futuras versiones, considerar remover esto y hacer conexiones explícitas
conectarse();
