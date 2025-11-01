# 🚀 Guía de Actualización y Deployment - Sistema Geaturim S.A.

**Versión:** 2.0.0  
**Fecha:** 21 de Octubre de 2025  
**Rama:** `actualizacion-frontend-2025`  
**Pull Request:** #1

---

## 📋 Tabla de Contenidos

1. [Resumen Ejecutivo](#resumen-ejecutivo)
2. [Requisitos Previos](#requisitos-previos)
3. [Plan de Deployment](#plan-de-deployment)
4. [Proceso de Migración Paso a Paso](#proceso-de-migración-paso-a-paso)
5. [Checklist de Verificación Post-Deployment](#checklist-de-verificación-post-deployment)
6. [Plan de Rollback](#plan-de-rollback)
7. [Troubleshooting Común](#troubleshooting-común)
8. [Monitoreo Post-Deployment](#monitoreo-post-deployment)

---

## 🎯 Resumen Ejecutivo

### Alcance de la Actualización

Esta actualización abarca:
- ✅ **Frontend:** jQuery 3.7.x y Bootstrap 5.3.x (302 archivos)
- ✅ **Backend:** Infraestructura de seguridad moderna (10 archivos)
- ✅ **Documentación:** 8 documentos completos
- ✅ **Total:** 11 commits organizados, ~12,000 líneas

### Objetivos

1. **Modernizar Frontend:** Eliminar warnings de deprecación, mejorar compatibilidad
2. **Asegurar Backend:** Eliminar vulnerabilidades críticas, establecer bases seguras
3. **Documentar:** Proveer guías completas para equipo de desarrollo

### Impacto Estimado

- **Tiempo de Deployment:** 2-4 horas
- **Downtime Necesario:** 15-30 minutos (configurable)
- **Riesgo:** 🟡 MEDIO (con testing previo: 🟢 BAJO)
- **Reversibilidad:** ✅ ALTA (git, backups)

---

## ✅ Requisitos Previos

### 1. Verificaciones Pre-Deployment

#### Sistema y Software

```bash
# Verificar versión de PHP (mínimo 7.4)
php -v
# Debe mostrar: PHP 7.4.x o superior

# Verificar extensiones PHP requeridas
php -m | grep -E "(pgsql|mbstring|xml|zip|gd|curl)"
# Deben aparecer todas las extensiones

# Verificar Composer
composer --version
# Debe mostrar: Composer version 2.x

# Verificar PostgreSQL
psql --version
# Debe mostrar: psql (PostgreSQL) 10.x o superior

# Verificar Git
git --version
```

#### Permisos y Accesos

```bash
# Verificar permisos de escritura en directorios críticos
ls -la logs/ data/ xmls/ atsxml/ reportes/

# Verificar usuario del servidor web
ps aux | grep -E "(apache|httpd|nginx)"

# Verificar acceso a base de datos
psql -U tu_usuario -d syswebfe -c "SELECT version();"
```

### 2. Backups Obligatorios

#### Base de Datos

```bash
# Crear directorio para backups
mkdir -p ~/backups/geaturim_$(date +%Y%m%d)

# Backup completo de base de datos principal
pg_dump -U tu_usuario -h localhost -d syswebfe \
  -F c -b -v -f ~/backups/geaturim_$(date +%Y%m%d)/syswebfe_backup.dump

# Backup de base de datos alterna
pg_dump -U tu_usuario -h localhost -d syswebfe_inven_ant \
  -F c -b -v -f ~/backups/geaturim_$(date +%Y%m%d)/syswebfe_inven_ant_backup.dump

# Verificar backups
ls -lh ~/backups/geaturim_$(date +%Y%m%d)/
```

#### Archivos del Sistema

```bash
# Backup completo del directorio del sistema
cd /var/www/
tar -czf ~/backups/geaturim_$(date +%Y%m%d)/sistema_completo_backup.tar.gz geaturim/

# Backup de configuración de Apache/Nginx
sudo cp -r /etc/apache2/sites-available/ ~/backups/geaturim_$(date +%Y%m%d)/apache_config/
# O para Nginx:
sudo cp -r /etc/nginx/sites-available/ ~/backups/geaturim_$(date +%Y%m%d)/nginx_config/

# Verificar integridad del backup
tar -tzf ~/backups/geaturim_$(date +%Y%m%d)/sistema_completo_backup.tar.gz | head -20
```

#### Snapshot del Estado Git Actual

```bash
cd /var/www/geaturim

# Anotar rama y commit actual
git branch -v
git log -1 --oneline

# Crear tag del estado actual
git tag -a "pre-update-v2.0-$(date +%Y%m%d)" -m "Estado antes de actualización v2.0"
git push origin --tags

# Guardar información del estado actual
git status > ~/backups/geaturim_$(date +%Y%m%d)/git_status_pre.txt
git log -10 --oneline > ~/backups/geaturim_$(date +%Y%m%d)/git_log_pre.txt
```

### 3. Ambiente de Testing

⚠️ **OBLIGATORIO:** Antes de desplegar en producción, probar en ambiente de testing/staging.

```bash
# Opción 1: Clonar en directorio de testing
cd /var/www/
cp -r geaturim geaturim_testing

# Opción 2: Usar máquina virtual o contenedor separado
# (Recomendado para producción crítica)
```

---

## 📅 Plan de Deployment

### Estrategia Recomendada: Deployment Gradual

#### Fase 1: Preparación (1 hora)
- ✅ Backups completos
- ✅ Testing en ambiente staging
- ✅ Notificar a usuarios
- ✅ Preparar ventana de mantenimiento

#### Fase 2: Core Update (30 min)
- ✅ Actualizar código base
- ✅ Instalar dependencias
- ✅ Configurar variables de entorno
- ✅ Testing básico

#### Fase 3: Verificación (30 min)
- ✅ Testing de módulos críticos
- ✅ Verificar logs
- ✅ Monitorear performance
- ✅ Confirmar con usuarios clave

#### Fase 4: Monitoreo (24 horas)
- ✅ Monitoreo continuo
- ✅ Respuesta a incidentes
- ✅ Ajustes menores

### Ventana de Mantenimiento Recomendada

**Opción 1: Con Downtime Mínimo (Recomendado)**
- Viernes después de horario laboral (18:00 - 20:00)
- Impacto mínimo en operaciones
- Tiempo para resolver problemas antes del lunes

**Opción 2: Sin Downtime (Avanzado)**
- Usar blue-green deployment
- Requiere configuración adicional de infraestructura
- Recomendado para sistemas 24/7

---

## 🔧 Proceso de Migración Paso a Paso

### PASO 1: Preparación del Entorno

#### 1.1. Modo de Mantenimiento

```bash
# Crear página de mantenimiento
cat > /var/www/geaturim/maintenance.html << 'EOF'
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema en Mantenimiento - Geaturim</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .container {
            text-align: center;
            padding: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }
        h1 { font-size: 3em; margin: 0; }
        p { font-size: 1.5em; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Sistema en Mantenimiento</h1>
        <p>Estamos actualizando el sistema para mejorar tu experiencia.</p>
        <p>Tiempo estimado: 30 minutos</p>
        <p>Gracias por tu paciencia.</p>
    </div>
</body>
</html>
EOF

# Activar modo de mantenimiento (Apache)
sudo a2enmod rewrite
cat > /var/www/geaturim/.htaccess << 'EOF'
RewriteEngine On
RewriteCond %{REMOTE_ADDR} !^123\.456\.789\.0$ # IP del admin
RewriteCond %{REQUEST_URI} !^/maintenance\.html$
RewriteRule ^(.*)$ /maintenance.html [R=503,L]
EOF

# Reiniciar Apache
sudo systemctl reload apache2
```

#### 1.2. Notificar a Usuarios

```bash
# Enviar notificación por email (ejemplo con PHP)
cat > notify_users.php << 'EOF'
<?php
$to = "equipo@geaturim.com";
$subject = "Actualización del Sistema - Mantenimiento Programado";
$message = "
Estimado equipo,

El sistema Geaturim entrará en mantenimiento:
- Fecha: " . date('d/m/Y') . "
- Hora: " . date('H:i') . "
- Duración estimada: 30 minutos

El sistema estará temporalmente inaccesible.

Gracias por su comprensión.
Equipo de TI
";
mail($to, $subject, $message);
echo "Notificación enviada.\n";
?>
EOF

php notify_users.php
```

### PASO 2: Actualización del Código

#### 2.1. Obtener Código Actualizado

```bash
cd /var/www/geaturim

# Verificar estado actual
git status
git branch

# Si estás en main, crear branch de respaldo
git checkout -b backup-pre-v2.0-$(date +%Y%m%d)
git push origin backup-pre-v2.0-$(date +%Y%m%d)

# Volver a main
git checkout main

# Fetch cambios
git fetch origin

# Revisar cambios antes de aplicar
git log --oneline main..origin/actualizacion-frontend-2025 | head -20

# Opción 1: Merge (recomendado después de review del PR)
git merge origin/actualizacion-frontend-2025

# Opción 2: Checkout directo a rama de actualización (para testing)
git checkout actualizacion-frontend-2025
git pull origin actualizacion-frontend-2025
```

#### 2.2. Verificar Archivos Descargados

```bash
# Verificar archivos críticos de seguridad
ls -l procesos/config.php procesos/security_utils.php procesos/session_manager.php

# Verificar archivos de configuración
ls -l .env.example composer.json .gitignore

# Verificar documentación
ls -l CHANGELOG.md GUIA_ACTUALIZACION.md RESUMEN_COMPLETO.md

# Verificar commits aplicados
git log --oneline -11
```

### PASO 3: Instalación de Dependencias

#### 3.1. Composer

```bash
# Limpiar cache de Composer
composer clear-cache

# Instalar dependencias (producción)
composer install --no-dev --optimize-autoloader

# Verificar instalación
composer show -i | grep phpdotenv
# Debe mostrar: vlucas/phpdotenv 5.6.x

# Verificar autoloader
ls -l vendor/autoload.php
```

#### 3.2. Verificar Carga de Autoloader

```bash
# Test rápido
php -r "require 'vendor/autoload.php'; echo 'Autoloader OK\n';"
```

### PASO 4: Configuración de Variables de Entorno

#### 4.1. Crear Archivo .env

```bash
# Copiar plantilla
cp .env.example .env

# Editar con credenciales reales
nano .env
```

#### 4.2. Configuración Mínima Requerida

```env
# Base de datos principal
DB_HOST=localhost
DB_PORT=5432
DB_NAME=syswebfe
DB_USER=postgres_usuario
DB_PASSWORD=TU_PASSWORD_SEGURO_AQUI

# Base de datos alternativa
DB_ALT_NAME=syswebfe_inven_ant

# Esquemas permitidos (separados por coma)
# IMPORTANTE: Listar TODOS los esquemas que usa tu sistema
ALLOWED_SCHEMAS=public,esquema_empresa1,esquema_empresa2

# Entorno (development, staging, production)
APP_ENV=production

# Debug (true en desarrollo, false en producción)
APP_DEBUG=false

# Timezone
APP_TIMEZONE=America/Guayaquil

# Configuración de logs
ERROR_LOG_PATH=/var/log/geaturim/errors.log
SECURITY_LOG_PATH=/var/log/geaturim/security.log

# Configuración de sesión
SESSION_LIFETIME=1800
SESSION_REGENERATE_INTERVAL=300
SESSION_VALIDATE_IP=true
SESSION_VALIDATE_USER_AGENT=true
```

#### 4.3. Proteger Archivo .env

```bash
# Permisos restrictivos
chmod 600 .env

# Verificar propietario
chown www-data:www-data .env

# Verificar que esté en .gitignore
grep -q "^\.env$" .gitignore && echo "✅ .env en .gitignore" || echo "❌ Agregar .env a .gitignore"
```

#### 4.4. Verificar Configuración

```bash
# Test de carga de configuración
php -r "
require_once 'procesos/config.php';
echo 'DB Name: ' . env('DB_NAME') . PHP_EOL;
echo 'DB Host: ' . env('DB_HOST') . PHP_EOL;
echo 'Environment: ' . env('APP_ENV') . PHP_EOL;
echo '✅ Configuración cargada correctamente' . PHP_EOL;
"
```

### PASO 5: Configuración de Permisos

```bash
# Propietario de archivos (ajustar según tu configuración)
sudo chown -R www-data:www-data /var/www/geaturim

# Permisos de directorios
sudo find /var/www/geaturim -type d -exec chmod 755 {} \;

# Permisos de archivos
sudo find /var/www/geaturim -type f -exec chmod 644 {} \;

# Permisos especiales para directorios de escritura
sudo chmod -R 775 /var/www/geaturim/logs
sudo chmod -R 775 /var/www/geaturim/data
sudo chmod -R 775 /var/www/geaturim/xmls
sudo chmod -R 775 /var/www/geaturim/atsxml
sudo chmod -R 775 /var/www/geaturim/reportes

# Permisos restrictivos para .env
chmod 600 /var/www/geaturim/.env

# Permisos para vendor
chmod -R 755 /var/www/geaturim/vendor
```

### PASO 6: Testing de Conexión a Base de Datos

#### 6.1. Test de Conexión

```bash
# Crear script de test
cat > /var/www/geaturim/test_conexion.php << 'EOF'
<?php
require_once __DIR__ . '/procesos/config.php';
require_once __DIR__ . '/procesos/base.php';

echo "=== Test de Conexión a Base de Datos ===\n\n";

// Test de configuración
echo "1. Configuración:\n";
echo "   - DB Name: " . env('DB_NAME') . "\n";
echo "   - DB Host: " . env('DB_HOST') . "\n";
echo "   - DB Port: " . env('DB_PORT') . "\n\n";

// Test de conexión principal
echo "2. Conexión principal:\n";
try {
    $conn = conectarse();
    if ($conn) {
        echo "   ✅ Conexión exitosa\n";
        
        // Test de query básico
        $result = pg_query($conn, "SELECT version()");
        if ($result) {
            $row = pg_fetch_assoc($result);
            echo "   ✅ Query exitoso\n";
            echo "   PostgreSQL: " . substr($row['version'], 0, 50) . "...\n";
        }
    } else {
        echo "   ❌ Error de conexión\n";
        exit(1);
    }
} catch (Exception $e) {
    echo "   ❌ Excepción: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n3. Conexión alternativa:\n";
try {
    $connAlt = conectarseAlterno();
    if ($connAlt) {
        echo "   ✅ Conexión alternativa exitosa\n";
    } else {
        echo "   ⚠️  Conexión alternativa falló (puede ser normal)\n";
    }
} catch (Exception $e) {
    echo "   ⚠️  Excepción: " . $e->getMessage() . "\n";
}

echo "\n✅ Tests completados exitosamente\n";
?>
EOF

# Ejecutar test
php /var/www/geaturim/test_conexion.php
```

#### 6.2. Test de Funciones de Seguridad

```bash
# Crear script de test de seguridad
cat > /var/www/geaturim/test_seguridad.php << 'EOF'
<?php
require_once __DIR__ . '/procesos/config.php';
require_once __DIR__ . '/procesos/base.php';
require_once __DIR__ . '/procesos/security_utils.php';
require_once __DIR__ . '/procesos/session_manager.php';

echo "=== Test de Funciones de Seguridad ===\n\n";

// Test 1: Sanitización
echo "1. Test de sanitización:\n";
$testHtml = '<script>alert("XSS")</script>Test';
$sanitized = sanitizeHtml($testHtml);
echo "   Input: $testHtml\n";
echo "   Output: $sanitized\n";
echo "   " . ($sanitized !== $testHtml ? "✅ OK" : "❌ FAIL") . "\n\n";

// Test 2: CSRF Token
echo "2. Test de CSRF Token:\n";
$token = generateCsrfToken();
echo "   Token generado: " . substr($token, 0, 20) . "...\n";
$valid = validateCsrfToken($token);
echo "   Validación: " . ($valid ? "✅ OK" : "❌ FAIL") . "\n\n";

// Test 3: SessionManager
echo "3. Test de SessionManager:\n";
try {
    SessionManager::init();
    echo "   Inicialización: ✅ OK\n";
    
    SessionManager::set('test_key', 'test_value');
    $value = SessionManager::get('test_key');
    echo "   Set/Get: " . ($value === 'test_value' ? "✅ OK" : "❌ FAIL") . "\n";
    
    SessionManager::flash('success', 'Test message');
    $flash = SessionManager::getFlash();
    echo "   Flash messages: " . (!empty($flash) ? "✅ OK" : "❌ FAIL") . "\n";
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n✅ Tests de seguridad completados\n";
?>
EOF

# Ejecutar test
php /var/www/geaturim/test_seguridad.php
```

### PASO 7: Ejecutar Scanner de Vulnerabilidades

```bash
# Ejecutar scanner
cd /var/www/geaturim
php scripts/detectar_vulnerabilidades.php

# El script generará un reporte
cat REPORTE_VULNERABILIDADES_*.txt | head -100

# Revisar estadísticas
grep -A 20 "ESTADÍSTICAS GENERALES" REPORTE_VULNERABILIDADES_*.txt
```

### PASO 8: Desactivar Modo de Mantenimiento

```bash
# Remover archivo .htaccess de mantenimiento
rm /var/www/geaturim/.htaccess

# O si tienes .htaccess personalizado, restaurar el original
# git checkout .htaccess

# Reiniciar servidor web
sudo systemctl reload apache2
# O para Nginx:
sudo systemctl reload nginx

# Verificar que el sitio esté accesible
curl -I http://localhost/
```

### PASO 9: Testing Post-Deployment

Ver sección [Checklist de Verificación Post-Deployment](#checklist-de-verificación-post-deployment)

---

## ✅ Checklist de Verificación Post-Deployment

### Testing Crítico (Obligatorio) ⚠️

#### 1. Sistema de Autenticación

```bash
# Test manual en navegador:
# 1. Abrir http://tu-dominio/login.php
# 2. Intentar login con credenciales válidas
# 3. Verificar que redirija correctamente
# 4. Verificar que muestre información del usuario
# 5. Hacer logout
# 6. Verificar que redirija a login
```

**Checklist:**
- [ ] Login con credenciales válidas funciona
- [ ] Login con credenciales inválidas muestra error apropiado
- [ ] Sesión persiste al navegar entre páginas
- [ ] Logout funciona correctamente
- [ ] Protección contra acceso no autorizado funciona

#### 2. Módulos de Facturación

**Factura de Venta:**
- [ ] Crear nueva factura
- [ ] Agregar productos a factura
- [ ] Calcular totales correctamente
- [ ] Guardar factura
- [ ] Generar PDF de factura
- [ ] Autorizar factura electrónica (si aplica)
- [ ] Imprimir factura

**Factura de Compra:**
- [ ] Registrar nueva compra
- [ ] Validar datos del proveedor
- [ ] Guardar compra correctamente
- [ ] Generar reporte de compra

#### 3. Módulo de Inventario

- [ ] Consultar productos existentes
- [ ] Buscar productos por código/nombre
- [ ] Ver stock de productos
- [ ] Registrar entrada de productos
- [ ] Registrar salida de productos
- [ ] Generar reporte de inventario

#### 4. Reportes

- [ ] Generar reporte de ventas
- [ ] Generar reporte de compras
- [ ] Generar reporte de inventario
- [ ] Exportar a PDF
- [ ] Exportar a Excel
- [ ] Filtrar por fechas
- [ ] Filtrar por parámetros

### Testing Importante (Recomendado) 📋

#### 5. Gestión de Clientes y Proveedores

**Clientes:**
- [ ] Crear nuevo cliente
- [ ] Editar cliente existente
- [ ] Buscar clientes
- [ ] Ver historial de compras de cliente

**Proveedores:**
- [ ] Crear nuevo proveedor
- [ ] Editar proveedor existente
- [ ] Buscar proveedores
- [ ] Ver historial de compras a proveedor

#### 6. Contabilidad

**Asientos Contables:**
- [ ] Crear asiento contable
- [ ] Editar asiento
- [ ] Consultar libro diario
- [ ] Generar balance de comprobación

**Conciliación Bancaria:**
- [ ] Registrar movimientos bancarios
- [ ] Conciliar cuentas
- [ ] Generar reporte de conciliación

#### 7. Finanzas

**Anticipos:**
- [ ] Registrar anticipo de cliente
- [ ] Registrar anticipo a proveedor
- [ ] Aplicar anticipo a factura

**Egresos/Ingresos:**
- [ ] Registrar egreso
- [ ] Registrar ingreso
- [ ] Consultar movimientos

### Testing de UI/UX (Opcional) 🎨

#### 8. Responsividad

Probar en diferentes dispositivos:
- [ ] Desktop (1920x1080)
- [ ] Laptop (1366x768)
- [ ] Tablet (768x1024)
- [ ] Móvil (375x667)

Verificar:
- [ ] Menús se adaptan correctamente
- [ ] Tablas son scrollables en móvil
- [ ] Formularios son usables en touch
- [ ] Botones tienen tamaño adecuado

#### 9. Compatibilidad de Navegadores

Probar en:
- [ ] Google Chrome (última versión)
- [ ] Mozilla Firefox (última versión)
- [ ] Microsoft Edge (última versión)
- [ ] Safari (si es posible)

#### 10. Componentes JavaScript

**Grids y Tablas:**
- [ ] jqGrid carga datos correctamente
- [ ] DataTables funciona (ordenar, filtrar, paginar)
- [ ] Búsqueda en tablas funciona

**Calendarios:**
- [ ] FullCalendar muestra eventos
- [ ] Datepicker funciona en formularios
- [ ] Selección de rangos de fechas funciona

**Gráficos:**
- [ ] Morris.js muestra gráficos
- [ ] Flot muestra gráficos interactivos
- [ ] Sparklines se muestran correctamente

**Otros:**
- [ ] Select2 funciona en selectores
- [ ] Modales se abren y cierran correctamente
- [ ] Alertas/notificaciones se muestran

### Testing de Seguridad 🔒

#### 11. Verificaciones de Seguridad

**Configuración:**
- [ ] Archivo `.env` tiene permisos 600
- [ ] Archivo `.env` NO está en Git
- [ ] Variables de entorno se cargan correctamente
- [ ] Credenciales NO están en código fuente

**Protección SQL Injection:**
- [ ] Intentar inyección SQL en campos de búsqueda
- [ ] Verificar que queries usan parámetros
- [ ] Revisar logs para intentos de inyección

**Protección XSS:**
- [ ] Intentar inyectar script en campos de texto
- [ ] Verificar que output esté sanitizado
- [ ] Revisar consola del navegador (no debe haber warnings)

**Protección CSRF:**
- [ ] Verificar que formularios tengan token CSRF
- [ ] Intentar submit sin token (debe fallar)
- [ ] Verificar regeneración de token

**Sesiones:**
- [ ] Verificar timeout de inactividad (30 min)
- [ ] Verificar regeneración de ID de sesión
- [ ] Verificar que sesión se destruye al logout

### Testing de Performance ⚡

#### 12. Métricas de Performance

**Tiempos de Carga:**
- [ ] Página principal carga en < 3 segundos
- [ ] Listados de datos cargan en < 5 segundos
- [ ] Reportes se generan en < 10 segundos

**Base de Datos:**
```bash
# Verificar conexiones activas
psql -U tu_usuario -d syswebfe -c "
SELECT count(*) as conexiones_activas 
FROM pg_stat_activity 
WHERE datname = 'syswebfe';
"

# Verificar queries lentos (si hay logging habilitado)
tail -f /var/log/postgresql/postgresql-*.log | grep "duration"
```

**Servidor Web:**
```bash
# Verificar uso de memoria
free -h

# Verificar uso de CPU
top -bn1 | head -20

# Verificar procesos de Apache/Nginx
ps aux | grep -E "(apache|nginx)" | wc -l
```

### Logs y Monitoreo 📊

#### 13. Revisar Logs

```bash
# Logs de errores PHP
tail -f /var/log/geaturim/errors.log

# Logs de seguridad
tail -f /var/log/geaturim/security.log

# Logs de Apache
sudo tail -f /var/log/apache2/error.log

# Logs de PostgreSQL
sudo tail -f /var/log/postgresql/postgresql-*.log

# Buscar errores recientes
grep -i "error\|fatal\|warning" /var/log/geaturim/errors.log | tail -20
```

#### 14. Verificar que NO haya errores

```bash
# En los últimos 10 minutos, NO debe haber errores críticos
sudo journalctl -u apache2 --since "10 minutes ago" | grep -i "error"
sudo journalctl -u postgresql --since "10 minutes ago" | grep -i "error"
```

---

## 🔄 Plan de Rollback

### Cuándo Hacer Rollback

Hacer rollback inmediatamente si:
- ❌ Módulos críticos no funcionan (login, facturación)
- ❌ Errores críticos en logs
- ❌ Base de datos no conecta
- ❌ Sistema completamente inaccesible
- ❌ Performance degradado significativamente (>50%)

### Procedimiento de Rollback

#### Opción 1: Rollback de Git (Recomendado)

```bash
cd /var/www/geaturim

# Ver commits recientes
git log --oneline -15

# Rollback a commit anterior al merge
git reset --hard <commit_hash_pre_update>

# O rollback a tag creado
git reset --hard pre-update-v2.0-<fecha>

# Forzar push (CUIDADO - solo si es necesario)
# git push -f origin main

# Restaurar composer dependencies previas
composer install

# Reiniciar servidor
sudo systemctl restart apache2
```

#### Opción 2: Restaurar desde Backup

```bash
# Detener servidor web
sudo systemctl stop apache2

# Restaurar archivos
cd /var/www/
rm -rf geaturim
tar -xzf ~/backups/geaturim_<fecha>/sistema_completo_backup.tar.gz

# Restaurar base de datos
pg_restore -U tu_usuario -d syswebfe -c \
  ~/backups/geaturim_<fecha>/syswebfe_backup.dump

# Reiniciar servidor
sudo systemctl start apache2
```

#### Opción 3: Checkout de Branch de Backup

```bash
cd /var/www/geaturim

# Checkout a branch de backup creado antes
git checkout backup-pre-v2.0-<fecha>

# Reinstalar dependencies
composer install

# Reiniciar
sudo systemctl restart apache2
```

### Post-Rollback

```bash
# Verificar que sistema funcione
curl -I http://localhost/

# Verificar logs
tail -f /var/log/apache2/error.log

# Notificar a usuarios
# (enviar email/mensaje que sistema está restaurado)

# Documentar razones del rollback
cat > ~/rollback_reason_$(date +%Y%m%d_%H%M).txt << 'EOF'
Fecha: $(date)
Razón del rollback: [DESCRIBIR RAZÓN]
Errores encontrados: [DETALLAR ERRORES]
Pasos dados: [DETALLAR PASOS]
EOF
```

---

## 🔧 Troubleshooting Común

### Problema 1: Error "Composer autoload no encontrado"

**Síntoma:**
```
Fatal error: require_once(): Failed opening required 'vendor/autoload.php'
```

**Solución:**
```bash
cd /var/www/geaturim
composer install
```

---

### Problema 2: Error de conexión a base de datos

**Síntoma:**
```
Warning: pg_connect(): Unable to connect to PostgreSQL server
```

**Diagnóstico:**
```bash
# Verificar que PostgreSQL esté corriendo
sudo systemctl status postgresql

# Verificar .env
cat .env | grep DB_

# Test de conexión manual
psql -U tu_usuario -h localhost -d syswebfe -c "SELECT 1;"
```

**Soluciones:**

```bash
# 1. Verificar credenciales en .env
nano .env

# 2. Verificar permisos de usuario en PostgreSQL
sudo -u postgres psql
# En el prompt:
GRANT ALL PRIVILEGES ON DATABASE syswebfe TO tu_usuario;
\q

# 3. Verificar pg_hba.conf
sudo nano /etc/postgresql/*/main/pg_hba.conf
# Debe tener línea como:
# host    all             all             127.0.0.1/32            md5

# 4. Reiniciar PostgreSQL
sudo systemctl restart postgresql
```

---

### Problema 3: Variables de entorno no se cargan

**Síntoma:**
```
Warning: Undefined constant "DB_HOST"
```

**Solución:**
```bash
# Verificar que .env existe
ls -la /var/www/geaturim/.env

# Verificar que phpdotenv está instalado
composer show | grep phpdotenv

# Reinstalar si es necesario
composer require vlucas/phpdotenv:^5.6

# Verificar que config.php carga dotenv
head -20 procesos/config.php
```

---

### Problema 4: Errores de permisos

**Síntoma:**
```
Warning: file_put_contents(): Permission denied
```

**Solución:**
```bash
# Verificar propietario
ls -la /var/www/geaturim

# Corregir propietario
sudo chown -R www-data:www-data /var/www/geaturim

# Corregir permisos de directorios de escritura
sudo chmod -R 775 /var/www/geaturim/logs
sudo chmod -R 775 /var/www/geaturim/data
sudo chmod -R 775 /var/www/geaturim/xmls
```

---

### Problema 5: Esquema no válido

**Síntoma:**
```
ERROR: Esquema no permitido en whitelist
```

**Solución:**
```bash
# Editar .env y agregar el esquema a ALLOWED_SCHEMAS
nano .env

# Ejemplo:
ALLOWED_SCHEMAS=public,esquema1,esquema2,nuevo_esquema

# Limpiar cache de PHP (si aplica)
sudo systemctl restart php8.1-fpm
sudo systemctl restart apache2
```

---

### Problema 6: Warnings de jQuery en consola

**Síntoma:**
```
jQuery.fn.bind() is deprecated
```

**Diagnóstico:**
- Archivo no fue actualizado en la migración
- Plugin de tercero usando API deprecada

**Solución:**
```bash
# Buscar archivos con .bind()
grep -r "\.bind(" --include="*.js" | grep -v vendor | grep -v node_modules

# Actualizar manualmente o reportar el archivo
```

---

### Problema 7: Estilos rotos (Bootstrap)

**Síntoma:**
- Layout se ve descuadrado
- Componentes no se ven correctamente

**Diagnóstico:**
```bash
# Verificar versión de Bootstrap cargada
curl http://localhost/ | grep bootstrap

# Verificar cache del navegador (Ctrl+Shift+R para force refresh)
```

**Solución:**
```bash
# Limpiar cache de servidor
# Apache:
sudo a2enmod expires
sudo systemctl restart apache2

# Verificar que se cargue Bootstrap 5
curl http://localhost/ | grep "bootstrap.*5\."
```

---

### Problema 8: Sesión no persiste

**Síntoma:**
- Usuario se desloguea constantemente
- Sesión se pierde al navegar

**Diagnóstico:**
```bash
# Verificar configuración de sesión en php.ini
php -i | grep -A 10 "session"

# Verificar permisos de directorio de sesiones
ls -la /var/lib/php/sessions
```

**Solución:**
```bash
# Verificar que session_manager.php esté incluido
grep -r "SessionManager::init()" --include="*.php" | head -5

# Verificar cookies en navegador (dev tools)
# Debe haber cookie con nombre de sesión PHP

# Si usa proxy/load balancer, verificar sticky sessions
```

---

### Problema 9: Performance lento

**Síntoma:**
- Páginas cargan muy lento
- Timeout en queries

**Diagnóstico:**
```bash
# Verificar queries lentos en PostgreSQL
sudo tail -f /var/log/postgresql/postgresql-*.log

# Verificar uso de recursos
htop

# Verificar conexiones a BD
psql -U tu_usuario -d syswebfe -c "
SELECT count(*), state 
FROM pg_stat_activity 
GROUP BY state;
"
```

**Solución:**
```bash
# Optimizar PostgreSQL (si es necesario)
sudo -u postgres psql -d syswebfe -c "VACUUM ANALYZE;"

# Verificar índices
psql -U tu_usuario -d syswebfe -c "
SELECT schemaname, tablename, indexname 
FROM pg_indexes 
WHERE schemaname = 'public';
"

# Aumentar recursos de PHP (si es necesario)
sudo nano /etc/php/8.1/apache2/php.ini
# Aumentar: memory_limit, max_execution_time
sudo systemctl restart apache2
```

---

### Problema 10: Errores 500 en Apache

**Síntoma:**
```
500 Internal Server Error
```

**Diagnóstico:**
```bash
# Ver logs de Apache
sudo tail -50 /var/log/apache2/error.log

# Ver logs de PHP
tail -50 /var/log/geaturim/errors.log
```

**Soluciones comunes:**
```bash
# 1. Verificar .htaccess
cat .htaccess

# 2. Verificar mod_rewrite está habilitado
sudo a2enmod rewrite
sudo systemctl restart apache2

# 3. Verificar sintaxis de PHP
php -l archivo_con_error.php

# 4. Verificar permisos
ls -la

# 5. Modo debug temporal (SOLO DESARROLLO)
# En .env:
APP_DEBUG=true
```

---

## 📊 Monitoreo Post-Deployment

### Primeras 24 Horas - Monitoreo Intensivo

#### 1. Cada Hora (Horas 0-8)

```bash
# Script de monitoreo automatizado
cat > monitor_sistema.sh << 'EOF'
#!/bin/bash

echo "=== Monitoreo Sistema Geaturim - $(date) ==="

echo -e "\n1. Estado del servidor web:"
systemctl status apache2 --no-pager | grep "Active:"

echo -e "\n2. Estado de PostgreSQL:"
systemctl status postgresql --no-pager | grep "Active:"

echo -e "\n3. Errores recientes (última hora):"
sudo journalctl -u apache2 --since "1 hour ago" | grep -i "error" | tail -5

echo -e "\n4. Uso de recursos:"
echo "CPU: $(top -bn1 | grep "Cpu(s)" | sed "s/.*, *\([0-9.]*\)%* id.*/\1/" | awk '{print 100 - $1"%"}')"
echo "RAM: $(free -h | grep Mem | awk '{print $3 "/" $2}')"
echo "Disco: $(df -h / | tail -1 | awk '{print $5}')"

echo -e "\n5. Conexiones a BD:"
psql -U tu_usuario -d syswebfe -t -c "SELECT count(*) FROM pg_stat_activity WHERE datname='syswebfe';"

echo -e "\n6. Últimas líneas de error log:"
tail -5 /var/log/geaturim/errors.log

echo -e "\n7. Últimas líneas de security log:"
tail -5 /var/log/geaturim/security.log

echo -e "\n========================================\n"
EOF

chmod +x monitor_sistema.sh

# Ejecutar cada hora
watch -n 3600 ./monitor_sistema.sh
```

#### 2. Métricas a Monitorear

**Disponibilidad:**
- [ ] Sistema accesible (uptime > 99%)
- [ ] Tiempo de respuesta < 3 segundos
- [ ] No hay errores 500

**Performance:**
- [ ] Uso de CPU < 70%
- [ ] Uso de RAM < 80%
- [ ] Uso de disco < 90%
- [ ] Conexiones BD < 50

**Errores:**
- [ ] Sin errores críticos en logs
- [ ] Sin warnings repetitivos
- [ ] Sin intentos de ataque masivos

**Usuarios:**
- [ ] Usuarios pueden loguearse
- [ ] No hay quejas de lentitud
- [ ] Funcionalidades críticas operan

### Semana 1 - Monitoreo Regular

#### Diario:
- Revisar logs de errores
- Verificar performance
- Recopilar feedback de usuarios
- Documentar problemas encontrados

#### Al finalizar la semana:
- Generar reporte de incidencias
- Evaluar estabilidad del sistema
- Decidir si continuar con migración de archivos restantes

### Herramientas de Monitoreo Recomendadas

#### Uptime Monitoring
```bash
# Usar UptimeRobot, Pingdom, o similar
# O crear script simple:
cat > check_uptime.sh << 'EOF'
#!/bin/bash
URL="http://tu-dominio.com"
STATUS=$(curl -o /dev/null -s -w "%{http_code}" $URL)

if [ $STATUS -eq 200 ]; then
    echo "$(date): ✅ Sistema UP - HTTP $STATUS"
else
    echo "$(date): ❌ Sistema DOWN - HTTP $STATUS"
    # Enviar alerta
    mail -s "ALERTA: Sistema Geaturim DOWN" admin@geaturim.com <<< "Status code: $STATUS"
fi
EOF

chmod +x check_uptime.sh

# Agregar a crontab (cada 5 minutos)
(crontab -l 2>/dev/null; echo "*/5 * * * * /path/to/check_uptime.sh >> /var/log/uptime_check.log") | crontab -
```

#### Log Monitoring
```bash
# Instalar logwatch (opcional)
sudo apt install logwatch

# Configurar para enviar reporte diario
sudo logwatch --detail high --mailto admin@geaturim.com --service all
```

---

## 📝 Documentación Post-Deployment

### Reporte de Deployment

Crear documento con:

```markdown
# Reporte de Deployment - Sistema Geaturim v2.0

## Información General
- **Fecha de deployment:** [FECHA]
- **Duración total:** [X] horas
- **Downtime:** [X] minutos
- **Responsable:** [NOMBRE]

## Resultado
- Estado: [EXITOSO / FALLIDO / ROLLBACK]
- Versión final: [X.X.X]

## Problemas Encontrados
1. [Descripción del problema]
   - Causa: [...]
   - Solución: [...]

## Testing Realizado
- Login: [OK/FAIL]
- Facturación: [OK/FAIL]
- Inventario: [OK/FAIL]
- Reportes: [OK/FAIL]

## Métricas Post-Deployment
- Tiempo de respuesta: [X]s
- Uso de CPU: [X]%
- Uso de RAM: [X]%

## Feedback de Usuarios
- [Usuario 1]: [Comentario]
- [Usuario 2]: [Comentario]

## Próximos Pasos
1. [Acción pendiente]
2. [Acción pendiente]

## Conclusión
[Evaluación general del deployment]
```

---

## 🎯 Conclusión

Esta guía cubre el proceso completo de actualización del Sistema Geaturim S.A. a la versión 2.0. 

### Resumen de Fases:

1. ✅ **Preparación** - Backups y requisitos
2. ✅ **Deployment** - Actualización de código y configuración
3. ✅ **Testing** - Verificación exhaustiva
4. ✅ **Monitoreo** - Seguimiento post-deployment
5. 🔄 **Rollback** - Plan de contingencia si es necesario

### Soporte

Para asistencia durante el deployment:
- 📧 Email: soporte@geaturim.com
- 📱 Teléfono: [NÚMERO]
- 💬 Slack/Chat: #deployment-v2

### Recursos Adicionales

- [CHANGELOG.md](CHANGELOG.md) - Historial de cambios completo
- [RESUMEN_COMPLETO.md](RESUMEN_COMPLETO.md) - Resumen ejecutivo
- [SEGURIDAD_README.md](SEGURIDAD_README.md) - Guía de seguridad
- [INSTALACION.md](INSTALACION.md) - Guía de instalación

---

**© 2025 Sistema Geaturim S.A.**  
**Guía de Actualización v2.0 - 21 de Octubre de 2025**

¡Buena suerte con el deployment! 🚀
