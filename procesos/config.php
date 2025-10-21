<?php

/**
 * Archivo de Configuración del Sistema Geaturim S.A.
 * 
 * Este archivo carga las variables de entorno desde el archivo .env
 * y proporciona funciones auxiliares para acceder a la configuración de manera segura.
 * 
 * @package Geaturim
 * @version 2.0
 * @date 2025-10-21
 */

// Cargar autoloader de Composer
$autoloadPath = __DIR__ . '/../vendor/autoload.php';

if (!file_exists($autoloadPath)) {
    die('Error: Composer autoload no encontrado. Ejecute: composer install');
}

require_once $autoloadPath;

// Cargar variables de entorno desde .env
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
    
    // Validar variables requeridas
    $dotenv->required([
        'DB_HOST',
        'DB_PORT',
        'DB_NAME',
        'DB_USER',
        'DB_PASSWORD'
    ])->notEmpty();
    
} catch (Exception $e) {
    error_log('Error cargando .env: ' . $e->getMessage());
    die('Error de configuración. Verifique el archivo .env');
}

/**
 * Obtiene el valor de una variable de entorno de manera segura
 * 
 * @param string $key Clave de la variable de entorno
 * @param mixed $default Valor por defecto si no existe la variable
 * @return mixed Valor de la variable o el valor por defecto
 */
function env(string $key, $default = null)
{
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? $default;
    
    // Convertir valores booleanos tipo string
    if (is_string($value)) {
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }
    }
    
    return $value;
}

/**
 * Obtiene la configuración de la base de datos principal
 * 
 * @return array Configuración de la base de datos
 */
function getDbConfig(): array
{
    return [
        'host' => env('DB_HOST', 'localhost'),
        'port' => env('DB_PORT', '5432'),
        'dbname' => env('DB_NAME', 'syswebfe'),
        'user' => env('DB_USER', 'postgres'),
        'password' => env('DB_PASSWORD', '')
    ];
}

/**
 * Obtiene la configuración de la base de datos alternativa
 * 
 * @return array Configuración de la base de datos alternativa
 */
function getDbAltConfig(): array
{
    return [
        'host' => env('DB_ALT_HOST', env('DB_HOST', 'localhost')),
        'port' => env('DB_ALT_PORT', env('DB_PORT', '5432')),
        'dbname' => env('DB_ALT_NAME', 'syswebfe_inven_ant'),
        'user' => env('DB_ALT_USER', env('DB_USER', 'postgres')),
        'password' => env('DB_ALT_PASSWORD', env('DB_PASSWORD', ''))
    ];
}

/**
 * Obtiene la lista de esquemas permitidos
 * 
 * @return array Lista de esquemas permitidos
 */
function getAllowedSchemas(): array
{
    $schemas = env('ALLOWED_SCHEMAS', 'public');
    return array_map('trim', explode(',', $schemas));
}

/**
 * Valida si un esquema está permitido
 * 
 * @param string $schema Nombre del esquema a validar
 * @return bool True si el esquema está permitido, false en caso contrario
 */
function isSchemaAllowed(string $schema): bool
{
    $allowedSchemas = getAllowedSchemas();
    
    // Si ALLOWED_SCHEMAS está vacío o es '*', permitir todos
    if (empty($allowedSchemas) || in_array('*', $allowedSchemas)) {
        // Validar que el esquema solo contenga caracteres alfanuméricos y guiones bajos
        return preg_match('/^[a-zA-Z0-9_]+$/', $schema) === 1;
    }
    
    return in_array($schema, $allowedSchemas, true);
}

/**
 * Sanitiza el nombre de un esquema para prevenir SQL Injection
 * 
 * @param string $schema Nombre del esquema
 * @return string|null Esquema sanitizado o null si es inválido
 */
function sanitizeSchema(string $schema): ?string
{
    // Remover espacios en blanco
    $schema = trim($schema);
    
    // Validar formato: solo letras, números y guiones bajos
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $schema)) {
        return null;
    }
    
    // Validar longitud máxima (PostgreSQL permite hasta 63 caracteres)
    if (strlen($schema) > 63) {
        return null;
    }
    
    // Verificar que esté en la lista de esquemas permitidos
    if (!isSchemaAllowed($schema)) {
        error_log("Intento de acceso a esquema no permitido: {$schema}");
        return null;
    }
    
    return $schema;
}

// Configurar opciones de PHP según variables de entorno
if (env('DISPLAY_ERRORS', false)) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
}

if (env('ERROR_REPORTING')) {
    error_reporting(constant(env('ERROR_REPORTING')));
}

if (env('LOG_ERRORS', true)) {
    ini_set('log_errors', '1');
    if ($logPath = env('ERROR_LOG_PATH')) {
        ini_set('error_log', $logPath);
    }
}

// Configurar zona horaria
date_default_timezone_set(env('APP_TIMEZONE', 'America/Guayaquil'));

// Configuración de sesiones seguras
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', env('SESSION_HTTPONLY', true) ? '1' : '0');
    ini_set('session.cookie_secure', env('SESSION_SECURE', false) ? '1' : '0');
    ini_set('session.cookie_samesite', env('SESSION_SAMESITE', 'Lax'));
    ini_set('session.gc_maxlifetime', env('SESSION_LIFETIME', 7200));
}
