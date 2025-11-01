<?php

/**
 * Utilidades de Seguridad - Sistema Geaturim S.A.
 * 
 * Este archivo proporciona funciones de utilidad para validar, sanitizar
 * y manejar datos de manera segura en toda la aplicación.
 * 
 * CATEGORÍAS DE FUNCIONES:
 * - Validación de Inputs
 * - Sanitización de Datos
 * - Ejecución Segura de Consultas SQL
 * - Manejo Seguro de Sesiones y Cookies
 * - Protección contra Ataques Comunes
 * 
 * @package Geaturim
 * @version 2.0
 * @date 2025-10-21
 */

// ============================================
// VALIDACIÓN DE INPUTS
// ============================================

/**
 * Valida y obtiene un parámetro GET de manera segura
 * 
 * @param string $key Clave del parámetro
 * @param mixed $default Valor por defecto si no existe
 * @param string $type Tipo de dato esperado (string, int, float, bool, email, date)
 * @return mixed Valor validado o valor por defecto
 */
function getSecure(string $key, $default = null, string $type = 'string')
{
    if (!isset($_GET[$key])) {
        return $default;
    }
    
    return validateInput($_GET[$key], $type, $default);
}

/**
 * Valida y obtiene un parámetro POST de manera segura
 * 
 * @param string $key Clave del parámetro
 * @param mixed $default Valor por defecto si no existe
 * @param string $type Tipo de dato esperado
 * @return mixed Valor validado o valor por defecto
 */
function postSecure(string $key, $default = null, string $type = 'string')
{
    if (!isset($_POST[$key])) {
        return $default;
    }
    
    return validateInput($_POST[$key], $type, $default);
}

/**
 * Valida y obtiene un parámetro REQUEST de manera segura
 * 
 * @param string $key Clave del parámetro
 * @param mixed $default Valor por defecto si no existe
 * @param string $type Tipo de dato esperado
 * @return mixed Valor validado o valor por defecto
 */
function requestSecure(string $key, $default = null, string $type = 'string')
{
    if (!isset($_REQUEST[$key])) {
        return $default;
    }
    
    return validateInput($_REQUEST[$key], $type, $default);
}

/**
 * Valida un input según su tipo esperado
 * 
 * @param mixed $value Valor a validar
 * @param string $type Tipo de dato esperado
 * @param mixed $default Valor por defecto si la validación falla
 * @return mixed Valor validado o valor por defecto
 */
function validateInput($value, string $type = 'string', $default = null)
{
    switch ($type) {
        case 'int':
        case 'integer':
            return filter_var($value, FILTER_VALIDATE_INT) !== false 
                ? (int)$value 
                : $default;
            
        case 'float':
        case 'decimal':
            return filter_var($value, FILTER_VALIDATE_FLOAT) !== false 
                ? (float)$value 
                : $default;
            
        case 'bool':
        case 'boolean':
            return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) 
                ?? $default;
            
        case 'email':
            $email = filter_var($value, FILTER_VALIDATE_EMAIL);
            return $email !== false ? $email : $default;
            
        case 'url':
            $url = filter_var($value, FILTER_VALIDATE_URL);
            return $url !== false ? $url : $default;
            
        case 'date':
            // Validar formato de fecha YYYY-MM-DD
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                $date = DateTime::createFromFormat('Y-m-d', $value);
                if ($date && $date->format('Y-m-d') === $value) {
                    return $value;
                }
            }
            return $default;
            
        case 'datetime':
            // Validar formato de fecha y hora
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $value);
            if ($date && $date->format('Y-m-d H:i:s') === $value) {
                return $value;
            }
            return $default;
            
        case 'alphanumeric':
            // Solo letras y números
            return preg_match('/^[a-zA-Z0-9]+$/', $value) ? $value : $default;
            
        case 'string':
        default:
            // Sanitizar string básico
            return is_string($value) ? trim($value) : $default;
    }
}

// ============================================
// SANITIZACIÓN DE DATOS
// ============================================

/**
 * Sanitiza un string para uso en HTML
 * 
 * @param string $string String a sanitizar
 * @return string String sanitizado
 */
function sanitizeHtml(string $string): string
{
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Sanitiza un string para uso en atributos HTML
 * 
 * @param string $string String a sanitizar
 * @return string String sanitizado
 */
function sanitizeAttribute(string $string): string
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Sanitiza un string para uso en JavaScript
 * 
 * @param string $string String a sanitizar
 * @return string String sanitizado
 */
function sanitizeJs(string $string): string
{
    return json_encode($string, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

/**
 * Sanitiza un nombre de archivo
 * 
 * @param string $filename Nombre del archivo
 * @return string Nombre de archivo sanitizado
 */
function sanitizeFilename(string $filename): string
{
    // Remover caracteres peligrosos
    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
    
    // Prevenir directory traversal
    $filename = str_replace(['../', '..\\'], '', $filename);
    
    return $filename;
}

/**
 * Sanitiza un número de identificación (cédula, RUC)
 * 
 * @param string $identificacion Número de identificación
 * @return string Identificación sanitizada
 */
function sanitizeIdentificacion(string $identificacion): string
{
    // Remover todo excepto números y guiones
    return preg_replace('/[^0-9-]/', '', $identificacion);
}

// ============================================
// CONSULTAS SQL SEGURAS
// ============================================

/**
 * Ejecuta una consulta parametrizada segura
 * 
 * EJEMPLO DE USO:
 * $result = querySecure($conn, 
 *     "SELECT * FROM usuarios WHERE id = $1 AND email = $2",
 *     [$id, $email]
 * );
 * 
 * @param resource $conexion Conexión PostgreSQL
 * @param string $query Consulta SQL con placeholders ($1, $2, etc.)
 * @param array $params Parámetros para la consulta
 * @return resource|false Resultado de la consulta
 */
function querySecure($conexion, string $query, array $params = [])
{
    if (empty($params)) {
        return pg_query($conexion, $query);
    }
    
    // Convertir valores a strings seguros para PostgreSQL
    $safeParams = array_map(function($param) use ($conexion) {
        if ($param === null) {
            return null;
        }
        // No necesitamos escapar aquí porque pg_query_params lo hace automáticamente
        return $param;
    }, $params);
    
    return pg_query_params($conexion, $query, $safeParams);
}

/**
 * Ejecuta una consulta SELECT segura con un solo resultado
 * 
 * @param resource $conexion Conexión PostgreSQL
 * @param string $query Consulta SQL
 * @param array $params Parámetros
 * @return array|null Fila de resultado o null
 */
function querySingle($conexion, string $query, array $params = []): ?array
{
    $result = querySecure($conexion, $query, $params);
    
    if ($result === false) {
        error_log('Error en querySingle: ' . pg_last_error($conexion));
        return null;
    }
    
    $row = pg_fetch_assoc($result);
    pg_free_result($result);
    
    return $row !== false ? $row : null;
}

/**
 * Ejecuta una consulta SELECT segura y retorna todos los resultados
 * 
 * @param resource $conexion Conexión PostgreSQL
 * @param string $query Consulta SQL
 * @param array $params Parámetros
 * @return array Array de resultados
 */
function queryAll($conexion, string $query, array $params = []): array
{
    $result = querySecure($conexion, $query, $params);
    
    if ($result === false) {
        error_log('Error en queryAll: ' . pg_last_error($conexion));
        return [];
    }
    
    $rows = pg_fetch_all($result);
    pg_free_result($result);
    
    return $rows !== false ? $rows : [];
}

/**
 * Ejecuta una consulta INSERT segura
 * 
 * EJEMPLO DE USO:
 * $id = insertSecure($conn, 'usuarios', [
 *     'nombre' => 'Juan',
 *     'email' => 'juan@example.com'
 * ], 'id_usuario');
 * 
 * @param resource $conexion Conexión PostgreSQL
 * @param string $table Nombre de la tabla
 * @param array $data Array asociativo con columna => valor
 * @param string $returnColumn Columna a retornar (generalmente el ID)
 * @return mixed Valor de la columna retornada o false en error
 */
function insertSecure($conexion, string $table, array $data, string $returnColumn = null)
{
    if (empty($data)) {
        return false;
    }
    
    $columns = array_keys($data);
    $values = array_values($data);
    
    // Escapar nombres de columnas
    $columnsSafe = array_map(function($col) use ($conexion) {
        return pg_escape_identifier($conexion, $col);
    }, $columns);
    
    // Crear placeholders ($1, $2, etc.)
    $placeholders = [];
    for ($i = 1; $i <= count($values); $i++) {
        $placeholders[] = '$' . $i;
    }
    
    $tableSafe = pg_escape_identifier($conexion, $table);
    $columnsStr = implode(', ', $columnsSafe);
    $placeholdersStr = implode(', ', $placeholders);
    
    $query = "INSERT INTO {$tableSafe} ({$columnsStr}) VALUES ({$placeholdersStr})";
    
    if ($returnColumn) {
        $returnColumnSafe = pg_escape_identifier($conexion, $returnColumn);
        $query .= " RETURNING {$returnColumnSafe}";
    }
    
    $result = pg_query_params($conexion, $query, $values);
    
    if ($result === false) {
        error_log('Error en insertSecure: ' . pg_last_error($conexion));
        return false;
    }
    
    if ($returnColumn) {
        $row = pg_fetch_assoc($result);
        pg_free_result($result);
        return $row ? $row[$returnColumn] : false;
    }
    
    pg_free_result($result);
    return true;
}

/**
 * Ejecuta una consulta UPDATE segura
 * 
 * EJEMPLO DE USO:
 * $affected = updateSecure($conn, 'usuarios', 
 *     ['nombre' => 'Juan Actualizado'],
 *     ['id_usuario' => 123]
 * );
 * 
 * @param resource $conexion Conexión PostgreSQL
 * @param string $table Nombre de la tabla
 * @param array $data Datos a actualizar (columna => valor)
 * @param array $where Condiciones WHERE (columna => valor)
 * @return int|false Número de filas afectadas o false en error
 */
function updateSecure($conexion, string $table, array $data, array $where)
{
    if (empty($data) || empty($where)) {
        return false;
    }
    
    $tableSafe = pg_escape_identifier($conexion, $table);
    
    // Construir SET clause
    $setValues = [];
    $params = [];
    $paramIndex = 1;
    
    foreach ($data as $column => $value) {
        $columnSafe = pg_escape_identifier($conexion, $column);
        $setValues[] = "{$columnSafe} = \${$paramIndex}";
        $params[] = $value;
        $paramIndex++;
    }
    
    // Construir WHERE clause
    $whereConditions = [];
    foreach ($where as $column => $value) {
        $columnSafe = pg_escape_identifier($conexion, $column);
        $whereConditions[] = "{$columnSafe} = \${$paramIndex}";
        $params[] = $value;
        $paramIndex++;
    }
    
    $setStr = implode(', ', $setValues);
    $whereStr = implode(' AND ', $whereConditions);
    
    $query = "UPDATE {$tableSafe} SET {$setStr} WHERE {$whereStr}";
    
    $result = pg_query_params($conexion, $query, $params);
    
    if ($result === false) {
        error_log('Error en updateSecure: ' . pg_last_error($conexion));
        return false;
    }
    
    $affected = pg_affected_rows($result);
    pg_free_result($result);
    
    return $affected;
}

/**
 * Ejecuta una consulta DELETE segura
 * 
 * @param resource $conexion Conexión PostgreSQL
 * @param string $table Nombre de la tabla
 * @param array $where Condiciones WHERE
 * @return int|false Número de filas eliminadas o false en error
 */
function deleteSecure($conexion, string $table, array $where)
{
    if (empty($where)) {
        error_log('Error: DELETE sin WHERE no está permitido');
        return false;
    }
    
    $tableSafe = pg_escape_identifier($conexion, $table);
    
    $whereConditions = [];
    $params = [];
    $paramIndex = 1;
    
    foreach ($where as $column => $value) {
        $columnSafe = pg_escape_identifier($conexion, $column);
        $whereConditions[] = "{$columnSafe} = \${$paramIndex}";
        $params[] = $value;
        $paramIndex++;
    }
    
    $whereStr = implode(' AND ', $whereConditions);
    $query = "DELETE FROM {$tableSafe} WHERE {$whereStr}";
    
    $result = pg_query_params($conexion, $query, $params);
    
    if ($result === false) {
        error_log('Error en deleteSecure: ' . pg_last_error($conexion));
        return false;
    }
    
    $affected = pg_affected_rows($result);
    pg_free_result($result);
    
    return $affected;
}

// ============================================
// PROTECCIÓN CSRF
// ============================================

/**
 * Genera un token CSRF para proteger formularios
 * 
 * @return string Token CSRF
 */
function generateCsrfToken(): string
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Valida un token CSRF
 * 
 * @param string $token Token a validar
 * @return bool True si es válido
 */
function validateCsrfToken(string $token): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Genera un campo hidden con el token CSRF para formularios
 * 
 * @return string HTML del campo hidden
 */
function csrfField(): string
{
    $token = generateCsrfToken();
    return '<input type="hidden" name="csrf_token" value="' . sanitizeAttribute($token) . '">';
}

// ============================================
// MANEJO SEGURO DE SESIONES
// ============================================

/**
 * Inicia una sesión de manera segura
 * 
 * @return bool True si se inició correctamente
 */
function startSecureSession(): bool
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return true;
    }
    
    // Configurar opciones de sesión seguras
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_samesite', 'Lax');
    
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        ini_set('session.cookie_secure', 1);
    }
    
    return session_start();
}

/**
 * Regenera el ID de sesión de manera segura
 * 
 * @return bool True si se regeneró correctamente
 */
function regenerateSession(): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        startSecureSession();
    }
    
    return session_regenerate_id(true);
}

/**
 * Destruye una sesión de manera segura
 * 
 * @return bool True si se destruyó correctamente
 */
function destroySession(): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        return true;
    }
    
    $_SESSION = [];
    
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
    
    return session_destroy();
}

// ============================================
// LOGGING SEGURO
// ============================================

/**
 * Registra un evento de seguridad
 * 
 * @param string $event Tipo de evento
 * @param string $message Mensaje
 * @param array $context Contexto adicional
 * @return void
 */
function logSecurityEvent(string $event, string $message, array $context = []): void
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    $timestamp = date('Y-m-d H:i:s');
    
    $logMessage = sprintf(
        "[%s] %s: %s | IP: %s | User-Agent: %s",
        $timestamp,
        $event,
        $message,
        $ip,
        $userAgent
    );
    
    if (!empty($context)) {
        $logMessage .= ' | Context: ' . json_encode($context);
    }
    
    error_log($logMessage);
}
