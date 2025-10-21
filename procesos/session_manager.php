<?php

/**
 * Gestor de Sesiones Seguro - Sistema Geaturim S.A.
 * 
 * Este archivo proporciona funcionalidades avanzadas para el manejo
 * seguro de sesiones de usuario con protecciones adicionales.
 * 
 * CARACTERÍSTICAS DE SEGURIDAD:
 * - Protección contra Session Hijacking
 * - Protección contra Session Fixation
 * - Timeout de inactividad
 * - Validación de IP y User-Agent
 * - Regeneración periódica de ID de sesión
 * 
 * @package Geaturim
 * @version 2.0
 * @date 2025-10-21
 */

// Cargar utilidades de seguridad
require_once __DIR__ . '/security_utils.php';

class SessionManager
{
    /**
     * Tiempo máximo de inactividad en segundos (30 minutos por defecto)
     */
    private const INACTIVITY_TIMEOUT = 1800;
    
    /**
     * Tiempo para regenerar ID de sesión en segundos (5 minutos)
     */
    private const REGENERATE_TIMEOUT = 300;
    
    /**
     * Valida si se debe verificar IP del usuario
     */
    private const VALIDATE_IP = true;
    
    /**
     * Valida si se debe verificar User-Agent
     */
    private const VALIDATE_USER_AGENT = true;
    
    /**
     * Inicializa una sesión segura
     * 
     * @return bool True si se inició correctamente
     */
    public static function init(): bool
    {
        // Iniciar sesión segura
        if (!startSecureSession()) {
            return false;
        }
        
        // Validar sesión existente
        if (!self::validate()) {
            self::destroy();
            return false;
        }
        
        // Actualizar timestamp de actividad
        self::updateActivity();
        
        // Regenerar ID si es necesario
        self::regenerateIfNeeded();
        
        return true;
    }
    
    /**
     * Valida la sesión actual
     * 
     * @return bool True si la sesión es válida
     */
    private static function validate(): bool
    {
        // Si es una sesión nueva, inicializarla
        if (!isset($_SESSION['_initialized'])) {
            return self::initialize();
        }
        
        // Validar timeout de inactividad
        if (!self::validateTimeout()) {
            logSecurityEvent('SESSION_TIMEOUT', 'Sesión expiró por inactividad');
            return false;
        }
        
        // Validar IP del usuario
        if (self::VALIDATE_IP && !self::validateIp()) {
            logSecurityEvent('SESSION_HIJACK_ATTEMPT', 'IP de sesión no coincide', [
                'expected' => $_SESSION['_ip'] ?? 'unknown',
                'actual' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            return false;
        }
        
        // Validar User-Agent
        if (self::VALIDATE_USER_AGENT && !self::validateUserAgent()) {
            logSecurityEvent('SESSION_HIJACK_ATTEMPT', 'User-Agent de sesión no coincide');
            return false;
        }
        
        return true;
    }
    
    /**
     * Inicializa una nueva sesión
     * 
     * @return bool True si se inicializó correctamente
     */
    private static function initialize(): bool
    {
        $_SESSION['_initialized'] = true;
        $_SESSION['_created'] = time();
        $_SESSION['_last_activity'] = time();
        $_SESSION['_last_regenerate'] = time();
        $_SESSION['_ip'] = $_SERVER['REMOTE_ADDR'] ?? null;
        $_SESSION['_user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? null;
        
        return true;
    }
    
    /**
     * Valida el timeout de inactividad
     * 
     * @return bool True si la sesión no ha expirado
     */
    private static function validateTimeout(): bool
    {
        if (!isset($_SESSION['_last_activity'])) {
            return false;
        }
        
        $inactive = time() - $_SESSION['_last_activity'];
        return $inactive <= self::INACTIVITY_TIMEOUT;
    }
    
    /**
     * Valida la IP del usuario
     * 
     * @return bool True si la IP coincide
     */
    private static function validateIp(): bool
    {
        if (!isset($_SESSION['_ip'])) {
            return false;
        }
        
        $currentIp = $_SERVER['REMOTE_ADDR'] ?? null;
        return $_SESSION['_ip'] === $currentIp;
    }
    
    /**
     * Valida el User-Agent del usuario
     * 
     * @return bool True si el User-Agent coincide
     */
    private static function validateUserAgent(): bool
    {
        if (!isset($_SESSION['_user_agent'])) {
            return false;
        }
        
        $currentUserAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        return $_SESSION['_user_agent'] === $currentUserAgent;
    }
    
    /**
     * Actualiza el timestamp de última actividad
     * 
     * @return void
     */
    private static function updateActivity(): void
    {
        $_SESSION['_last_activity'] = time();
    }
    
    /**
     * Regenera el ID de sesión si es necesario
     * 
     * @return void
     */
    private static function regenerateIfNeeded(): void
    {
        if (!isset($_SESSION['_last_regenerate'])) {
            $_SESSION['_last_regenerate'] = time();
            return;
        }
        
        $elapsed = time() - $_SESSION['_last_regenerate'];
        
        if ($elapsed >= self::REGENERATE_TIMEOUT) {
            regenerateSession();
            $_SESSION['_last_regenerate'] = time();
            logSecurityEvent('SESSION_REGENERATED', 'ID de sesión regenerado automáticamente');
        }
    }
    
    /**
     * Inicia sesión para un usuario
     * 
     * @param int $userId ID del usuario
     * @param array $userData Datos adicionales del usuario
     * @return bool True si se inició correctamente
     */
    public static function login(int $userId, array $userData = []): bool
    {
        // Regenerar ID de sesión para prevenir session fixation
        regenerateSession();
        
        // Guardar datos del usuario
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_data'] = $userData;
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();
        
        // Re-inicializar sesión
        self::initialize();
        
        logSecurityEvent('USER_LOGIN', "Usuario {$userId} inició sesión");
        
        return true;
    }
    
    /**
     * Cierra la sesión del usuario
     * 
     * @return bool True si se cerró correctamente
     */
    public static function logout(): bool
    {
        $userId = $_SESSION['user_id'] ?? 'unknown';
        
        logSecurityEvent('USER_LOGOUT', "Usuario {$userId} cerró sesión");
        
        return self::destroy();
    }
    
    /**
     * Destruye completamente la sesión
     * 
     * @return bool True si se destruyó correctamente
     */
    public static function destroy(): bool
    {
        return destroySession();
    }
    
    /**
     * Verifica si el usuario está autenticado
     * 
     * @return bool True si está autenticado
     */
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    /**
     * Obtiene el ID del usuario actual
     * 
     * @return int|null ID del usuario o null si no está autenticado
     */
    public static function getUserId(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Obtiene datos del usuario actual
     * 
     * @param string|null $key Clave específica o null para todos los datos
     * @return mixed Datos del usuario
     */
    public static function getUserData(?string $key = null)
    {
        if (!isset($_SESSION['user_data'])) {
            return null;
        }
        
        if ($key === null) {
            return $_SESSION['user_data'];
        }
        
        return $_SESSION['user_data'][$key] ?? null;
    }
    
    /**
     * Establece un valor en la sesión
     * 
     * @param string $key Clave
     * @param mixed $value Valor
     * @return void
     */
    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }
    
    /**
     * Obtiene un valor de la sesión
     * 
     * @param string $key Clave
     * @param mixed $default Valor por defecto
     * @return mixed Valor o valor por defecto
     */
    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Verifica si existe una clave en la sesión
     * 
     * @param string $key Clave
     * @return bool True si existe
     */
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }
    
    /**
     * Elimina una clave de la sesión
     * 
     * @param string $key Clave
     * @return void
     */
    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }
    
    /**
     * Establece un mensaje flash (mensaje de una sola vez)
     * 
     * @param string $type Tipo de mensaje (success, error, warning, info)
     * @param string $message Mensaje
     * @return void
     */
    public static function flash(string $type, string $message): void
    {
        if (!isset($_SESSION['_flash'])) {
            $_SESSION['_flash'] = [];
        }
        
        $_SESSION['_flash'][] = [
            'type' => $type,
            'message' => $message
        ];
    }
    
    /**
     * Obtiene y elimina los mensajes flash
     * 
     * @return array Array de mensajes flash
     */
    public static function getFlash(): array
    {
        $flash = $_SESSION['_flash'] ?? [];
        unset($_SESSION['_flash']);
        return $flash;
    }
    
    /**
     * Obtiene información sobre la sesión para debugging
     * 
     * @return array Información de la sesión
     */
    public static function getInfo(): array
    {
        return [
            'session_id' => session_id(),
            'created' => $_SESSION['_created'] ?? null,
            'last_activity' => $_SESSION['_last_activity'] ?? null,
            'last_regenerate' => $_SESSION['_last_regenerate'] ?? null,
            'user_id' => $_SESSION['user_id'] ?? null,
            'logged_in' => self::isLoggedIn(),
            'ip' => $_SESSION['_ip'] ?? null,
            'time_inactive' => isset($_SESSION['_last_activity']) 
                ? time() - $_SESSION['_last_activity'] 
                : null
        ];
    }
}

// Inicializar sesión automáticamente si no está en modo CLI
if (php_sapi_name() !== 'cli') {
    SessionManager::init();
}
