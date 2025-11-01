# 📦 Guía de Instalación - Sistema Geaturim S.A.

## Requisitos del Sistema

### Software Requerido

- **PHP:** >= 7.4 (Recomendado: PHP 8.0+)
- **PostgreSQL:** >= 10.0
- **Composer:** >= 2.0
- **Extensiones PHP:**
  - `php-pgsql` - Soporte para PostgreSQL
  - `php-mbstring` - Manejo de strings multibyte
  - `php-xml` - Procesamiento XML
  - `php-zip` - Manejo de archivos ZIP
  - `php-gd` - Procesamiento de imágenes
  - `php-curl` - Cliente HTTP

### Verificar Requisitos

```bash
# Verificar versión de PHP
php -v

# Verificar extensiones instaladas
php -m | grep -E "(pgsql|mbstring|xml|zip|gd|curl)"

# Verificar Composer
composer --version

# Verificar PostgreSQL
psql --version
```

---

## 🚀 Instalación Rápida

### 1. Clonar el Repositorio

```bash
git clone https://github.com/LeonardoMontalvo/Sistema-Geaturim.S.A.git
cd Sistema-Geaturim.S.A
```

### 2. Instalar Dependencias

```bash
# Instalar dependencias de Composer
composer install
```

### 3. Configurar Variables de Entorno

```bash
# Copiar archivo de ejemplo
cp .env.example .env

# Editar con tus credenciales
nano .env
```

**Configuración mínima en `.env`:**

```env
# Base de datos principal
DB_HOST=localhost
DB_PORT=5432
DB_NAME=syswebfe
DB_USER=tu_usuario
DB_PASSWORD=tu_password

# Base de datos alternativa
DB_ALT_NAME=syswebfe_inven_ant

# Esquemas permitidos
ALLOWED_SCHEMAS=public,esquema1,esquema2

# Entorno
APP_ENV=production
APP_DEBUG=false
```

### 4. Configurar Permisos

```bash
# Permisos del archivo .env
chmod 600 .env

# Permisos de directorios de escritura
chmod -R 755 logs/
chmod -R 755 data/
chmod -R 755 xmls/
chmod -R 755 atsxml/
chmod -R 755 reportes/

# Propietario (ajustar según tu servidor web)
chown -R www-data:www-data .
```

### 5. Verificar Instalación

Crear archivo `test.php` en el directorio raíz:

```php
<?php
require_once __DIR__ . '/procesos/config.php';

echo "✅ Configuración cargada\n";
echo "Base de datos: " . env('DB_NAME') . "\n";
echo "Host: " . env('DB_HOST') . "\n";

// Probar conexión
require_once __DIR__ . '/procesos/base.php';
$conn = conectarse();

if ($conn) {
    echo "✅ Conexión a base de datos exitosa\n";
} else {
    echo "❌ Error al conectar a base de datos\n";
}
```

Ejecutar:

```bash
php test.php
```

---

## 🔧 Instalación Detallada

### Instalación de PHP 8.1 (Ubuntu/Debian)

```bash
# Agregar repositorio
sudo add-apt-repository ppa:ondrej/php
sudo apt update

# Instalar PHP y extensiones
sudo apt install php8.1 php8.1-cli php8.1-fpm php8.1-pgsql \
                 php8.1-mbstring php8.1-xml php8.1-zip \
                 php8.1-gd php8.1-curl

# Verificar instalación
php -v
```

### Instalación de Composer

```bash
# Descargar instalador
curl -sS https://getcomposer.org/installer -o composer-setup.php

# Instalar globalmente
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Verificar
composer --version
```

### Instalación de PostgreSQL (Ubuntu/Debian)

```bash
# Instalar PostgreSQL
sudo apt install postgresql postgresql-contrib

# Iniciar servicio
sudo systemctl start postgresql
sudo systemctl enable postgresql

# Crear base de datos
sudo -u postgres psql

# En el prompt de PostgreSQL:
CREATE DATABASE syswebfe;
CREATE DATABASE syswebfe_inven_ant;
CREATE USER tu_usuario WITH PASSWORD 'tu_password';
GRANT ALL PRIVILEGES ON DATABASE syswebfe TO tu_usuario;
GRANT ALL PRIVILEGES ON DATABASE syswebfe_inven_ant TO tu_usuario;
\q
```

---

## 🌐 Configuración del Servidor Web

### Apache

**Habilitar mod_rewrite:**

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

**Configurar Virtual Host (`/etc/apache2/sites-available/geaturim.conf`):**

```apache
<VirtualHost *:80>
    ServerName geaturim.local
    ServerAlias www.geaturim.local
    DocumentRoot /var/www/geaturim
    
    <Directory /var/www/geaturim>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/geaturim-error.log
    CustomLog ${APACHE_LOG_DIR}/geaturim-access.log combined
</VirtualHost>
```

**Habilitar sitio:**

```bash
sudo a2ensite geaturim.conf
sudo systemctl reload apache2
```

### Nginx

**Configurar sitio (`/etc/nginx/sites-available/geaturim`):**

```nginx
server {
    listen 80;
    server_name geaturim.local www.geaturim.local;
    root /var/www/geaturim;
    index index.php index.html;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

**Habilitar sitio:**

```bash
sudo ln -s /etc/nginx/sites-available/geaturim /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 🔒 Configuración de Seguridad Post-Instalación

### 1. Configurar SSL/HTTPS (Recomendado para Producción)

```bash
# Instalar Certbot
sudo apt install certbot python3-certbot-apache

# Obtener certificado (Apache)
sudo certbot --apache -d geaturim.com -d www.geaturim.com

# O para Nginx
sudo certbot --nginx -d geaturim.com -d www.geaturim.com
```

### 2. Configurar Firewall

```bash
# UFW (Ubuntu)
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

### 3. Configurar PHP para Producción

Editar `/etc/php/8.1/apache2/php.ini` (o `/etc/php/8.1/fpm/php.ini` para Nginx):

```ini
display_errors = Off
log_errors = On
error_log = /var/log/php/error.log
max_execution_time = 60
memory_limit = 256M
upload_max_filesize = 20M
post_max_size = 20M
session.cookie_httponly = On
session.cookie_secure = On
session.use_strict_mode = On
```

Reiniciar servicio:

```bash
# Apache
sudo systemctl restart apache2

# Nginx + PHP-FPM
sudo systemctl restart php8.1-fpm
sudo systemctl restart nginx
```

### 4. Configurar Logs

```bash
# Crear directorio de logs
sudo mkdir -p /var/log/geaturim
sudo chown www-data:www-data /var/log/geaturim
sudo chmod 755 /var/log/geaturim

# Actualizar .env
echo "ERROR_LOG_PATH=/var/log/geaturim/errors.log" >> .env
```

---

## 🧪 Pruebas Post-Instalación

### 1. Verificar Conexión a Base de Datos

```bash
php -r "
require 'procesos/base.php';
\$conn = conectarse();
echo \$conn ? '✅ Conexión exitosa' : '❌ Error de conexión';
"
```

### 2. Verificar Carga de Variables de Entorno

```bash
php -r "
require 'procesos/config.php';
echo 'DB: ' . env('DB_NAME') . PHP_EOL;
"
```

### 3. Ejecutar Script de Detección de Vulnerabilidades

```bash
php scripts/detectar_vulnerabilidades.php
```

---

## 📊 Monitoreo y Mantenimiento

### Logs a Monitorear

```bash
# Logs de errores PHP
tail -f /var/log/geaturim/errors.log

# Logs de Apache
tail -f /var/log/apache2/geaturim-error.log

# Logs de PostgreSQL
sudo tail -f /var/log/postgresql/postgresql-14-main.log
```

### Backups Regulares

```bash
# Backup de base de datos
pg_dump -U tu_usuario -h localhost syswebfe > backup_syswebfe_$(date +%Y%m%d).sql

# Backup de archivos
tar -czf backup_geaturim_$(date +%Y%m%d).tar.gz /var/www/geaturim
```

### Actualizar Dependencias

```bash
# Actualizar Composer
composer update

# Verificar vulnerabilidades de seguridad
composer audit
```

---

## 🆘 Solución de Problemas

### Error: "Composer autoload no encontrado"

```bash
composer install
```

### Error: "No se pudo establecer conexión a la base de datos"

1. Verificar que PostgreSQL esté corriendo:
   ```bash
   sudo systemctl status postgresql
   ```

2. Verificar credenciales en `.env`

3. Verificar que el usuario tenga permisos:
   ```sql
   GRANT ALL PRIVILEGES ON DATABASE syswebfe TO tu_usuario;
   ```

### Error: "Permission denied" al escribir archivos

```bash
sudo chown -R www-data:www-data /var/www/geaturim
sudo chmod -R 755 /var/www/geaturim
```

### Error: "Call to undefined function pg_connect"

```bash
# Instalar extensión PHP para PostgreSQL
sudo apt install php8.1-pgsql
sudo systemctl restart apache2
```

---

## 📚 Recursos Adicionales

- [Documentación de Seguridad](SEGURIDAD_README.md)
- [Guía de Migración de Código](SEGURIDAD_README.md#guía-de-migración)
- [Documentación PHP](https://www.php.net/manual/es/)
- [Documentación PostgreSQL](https://www.postgresql.org/docs/)
- [Documentación Composer](https://getcomposer.org/doc/)

---

## 📞 Soporte

Para problemas de instalación:
1. Verificar requisitos del sistema
2. Revisar logs de error
3. Consultar sección de solución de problemas
4. Contactar al equipo de desarrollo

---

**© 2025 Geaturim S.A.**
