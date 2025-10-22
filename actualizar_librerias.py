#!/usr/bin/env python3
"""
Script de actualización automática Bootstrap 5.3.3 y jQuery 3.7.1
Para Sistema Geaturim S.A.
"""

import os
import shutil
import urllib.request
import zipfile
import re
from pathlib import Path
from datetime import datetime

# Colores para terminal
class Colors:
    GREEN = '\033[92m'
    YELLOW = '\033[93m'
    RED = '\033[91m'
    BLUE = '\033[94m'
    END = '\033[0m'

def print_step(message):
    print(f"{Colors.BLUE}➜ {message}{Colors.END}")

def print_success(message):
    print(f"{Colors.GREEN}✓ {message}{Colors.END}")

def print_warning(message):
    print(f"{Colors.YELLOW}⚠ {message}{Colors.END}")

def print_error(message):
    print(f"{Colors.RED}✗ {message}{Colors.END}")

# Configuración
BOOTSTRAP_VERSION = "5.3.3"
JQUERY_VERSION = "3.7.1"
BOOTSTRAP_URL = f"https://github.com/twbs/bootstrap/releases/download/v{BOOTSTRAP_VERSION}/bootstrap-{BOOTSTRAP_VERSION}-dist.zip"
JQUERY_URL = f"https://code.jquery.com/jquery-{JQUERY_VERSION}.min.js"

def create_backup():
    """Crear backup de archivos importantes"""
    print_step("Creando backup de seguridad...")
    backup_dir = f"backup_{datetime.now().strftime('%Y%m%d_%H%M%S')}"
    os.makedirs(backup_dir, exist_ok=True)
    
    # Backup de bootstrap
    if os.path.exists("bootstrap"):
        shutil.copytree("bootstrap", f"{backup_dir}/bootstrap")
        print_success(f"Backup de bootstrap creado en {backup_dir}/")
    
    # Backup de jQuery
    jquery_paths = ["plugins/jQuery", "js/jQuery", "jquery"]
    for path in jquery_paths:
        if os.path.exists(path):
            shutil.copytree(path, f"{backup_dir}/{path.replace('/', '_')}")
            print_success(f"Backup de {path} creado")
    
    return backup_dir

def download_bootstrap():
    """Descargar Bootstrap 5.3.3"""
    print_step(f"Descargando Bootstrap {BOOTSTRAP_VERSION}...")
    
    try:
        urllib.request.urlretrieve(BOOTSTRAP_URL, "bootstrap.zip")
        print_success("Bootstrap descargado")
        
        # Extraer
        print_step("Extrayendo archivos...")
        with zipfile.ZipFile("bootstrap.zip", 'r') as zip_ref:
            zip_ref.extractall("bootstrap_temp")
        
        # Reemplazar carpeta bootstrap
        if os.path.exists("bootstrap"):
            shutil.rmtree("bootstrap")
        
        shutil.move(f"bootstrap_temp/bootstrap-{BOOTSTRAP_VERSION}-dist", "bootstrap")
        
        # Limpiar
        os.remove("bootstrap.zip")
        shutil.rmtree("bootstrap_temp")
        
        print_success("Bootstrap 5.3.3 instalado correctamente")
        return True
    except Exception as e:
        print_error(f"Error descargando Bootstrap: {e}")
        return False

def download_jquery():
    """Descargar jQuery 3.7.1"""
    print_step(f"Descargando jQuery {JQUERY_VERSION}...")
    
    try:
        # Crear directorio si no existe
        os.makedirs("plugins/jQuery", exist_ok=True)
        
        # Descargar
        jquery_path = f"plugins/jQuery/jquery-{JQUERY_VERSION}.min.js"
        urllib.request.urlretrieve(JQUERY_URL, jquery_path)
        
        print_success(f"jQuery {JQUERY_VERSION} descargado en {jquery_path}")
        return True
    except Exception as e:
        print_error(f"Error descargando jQuery: {e}")
        return False

def update_file_references(file_path):
    """Actualizar referencias en un archivo"""
    try:
        with open(file_path, 'r', encoding='utf-8', errors='ignore') as f:
            content = f.read()
        
        original_content = content
        changes = []
        
        # Actualizar jQuery
        jquery_pattern = r'(plugins/jQuery/jquery-)[\d.]+(.min.js)'
        if re.search(jquery_pattern, content):
            content = re.sub(jquery_pattern, rf'\g<1>{JQUERY_VERSION}\g<2>', content)
            changes.append("jQuery")
        
        # Actualizar referencias de Bootstrap (si hay versión específica)
        bootstrap_pattern = r'(bootstrap/)[\d.]+(/)'
        if re.search(bootstrap_pattern, content):
            content = re.sub(bootstrap_pattern, r'\g<1>\g<2>', content)
            changes.append("Bootstrap path")
        
        # Solo escribir si hubo cambios
        if content != original_content:
            with open(file_path, 'w', encoding='utf-8', errors='ignore') as f:
                f.write(content)
            return changes
        
        return []
    except Exception as e:
        print_error(f"Error procesando {file_path}: {e}")
        return []

def update_all_references():
    """Actualizar referencias en todos los archivos PHP y HTML"""
    print_step("Actualizando referencias en archivos...")
    
    extensions = ['.php', '.html', '.htm']
    updated_files = []
    
    for root, dirs, files in os.walk('.'):
        # Ignorar directorios de backup y vendor
        dirs[:] = [d for d in dirs if not d.startswith('backup_') and d != 'vendor' and d != 'node_modules']
        
        for file in files:
            if any(file.endswith(ext) for ext in extensions):
                file_path = os.path.join(root, file)
                changes = update_file_references(file_path)
                if changes:
                    updated_files.append((file_path, changes))
    
    if updated_files:
        print_success(f"Actualizados {len(updated_files)} archivos:")
        for file_path, changes in updated_files:
            print(f"  • {file_path} ({', '.join(changes)})")
    else:
        print_warning("No se encontraron referencias para actualizar")
    
    return len(updated_files)

def verify_installation():
    """Verificar que todo se instaló correctamente"""
    print_step("Verificando instalación...")
    
    checks = []
    
    # Verificar Bootstrap CSS
    if os.path.exists("bootstrap/css/bootstrap.min.css"):
        checks.append(("Bootstrap CSS", True))
    else:
        checks.append(("Bootstrap CSS", False))
    
    # Verificar Bootstrap JS
    if os.path.exists("bootstrap/js/bootstrap.bundle.min.js"):
        checks.append(("Bootstrap JS", True))
    else:
        checks.append(("Bootstrap JS", False))
    
    # Verificar jQuery
    jquery_path = f"plugins/jQuery/jquery-{JQUERY_VERSION}.min.js"
    if os.path.exists(jquery_path):
        checks.append(("jQuery", True))
    else:
        checks.append(("jQuery", False))
    
    print("\n" + "="*50)
    print("VERIFICACIÓN DE INSTALACIÓN")
    print("="*50)
    
    all_ok = True
    for name, status in checks:
        if status:
            print_success(f"{name}: Instalado")
        else:
            print_error(f"{name}: NO encontrado")
            all_ok = False
    
    return all_ok

def main():
    print("\n" + "="*50)
    print("ACTUALIZACIÓN AUTOMÁTICA")
    print(f"Bootstrap {BOOTSTRAP_VERSION} + jQuery {JQUERY_VERSION}")
    print("Sistema Geaturim S.A.")
    print("="*50 + "\n")
    
    # Verificar que estamos en el directorio correcto
    if not os.path.exists("index.php") and not os.path.exists("bootstrap"):
        print_error("No se detectó el proyecto. Ejecuta este script desde la raíz del proyecto.")
        return
    
    print_warning("Este script va a:")
    print("  1. Crear backup de archivos actuales")
    print("  2. Descargar Bootstrap 5.3.3")
    print("  3. Descargar jQuery 3.7.1")
    print("  4. Actualizar referencias en archivos PHP/HTML")
    print()
    
    response = input("¿Continuar? (s/n): ").lower()
    if response != 's':
        print("Operación cancelada")
        return
    
    print()
    
    # Paso 1: Backup
    backup_dir = create_backup()
    print()
    
    # Paso 2: Descargar Bootstrap
    if not download_bootstrap():
        print_error("Falló la descarga de Bootstrap")
        return
    print()
    
    # Paso 3: Descargar jQuery
    if not download_jquery():
        print_error("Falló la descarga de jQuery")
        return
    print()
    
    # Paso 4: Actualizar referencias
    updated_count = update_all_references()
    print()
    
    # Paso 5: Verificar
    if verify_installation():
        print()
        print_success("="*50)
        print_success("¡ACTUALIZACIÓN COMPLETADA EXITOSAMENTE!")
        print_success("="*50)
        print()
        print("Próximos pasos:")
        print("  1. Prueba tu aplicación en el navegador")
        print("  2. Limpia la caché del navegador (Ctrl+Shift+R)")
        print("  3. Si todo funciona, haz commit:")
        print(f"     git add .")
        print(f"     git commit -m 'Actualización Bootstrap 5.3.3 y jQuery 3.7.1'")
        print(f"     git push")
        print()
        print(f"Backup guardado en: {backup_dir}/")
    else:
        print_error("Hubo problemas en la instalación. Revisa los errores arriba.")

if __name__ == "__main__":
    main()