#!/usr/bin/env python3
"""
Script para actualizar clases de Bootstrap 3 a Bootstrap 5
Sistema Geaturim S.A.
"""

import os
import re
from pathlib import Path

# Mapeo de clases Bootstrap 3 → Bootstrap 5
BOOTSTRAP_REPLACEMENTS = {
    # Panels → Cards
    r'panel panel-default': 'card',
    r'panel panel-primary': 'card border-primary',
    r'panel panel-success': 'card border-success',
    r'panel panel-info': 'card border-info',
    r'panel panel-warning': 'card border-warning',
    r'panel panel-danger': 'card border-danger',
    r'panel-heading': 'card-header',
    r'panel-title': 'card-title',
    r'panel-body': 'card-body',
    r'panel-footer': 'card-footer',
    
    # Buttons
    r'btn-default': 'btn-secondary',
    
    # Hidden classes
    r'hidden-xs': 'd-none d-sm-block',
    r'hidden-sm': 'd-sm-none d-md-block',
    r'hidden-md': 'd-md-none d-lg-block',
    r'hidden-lg': 'd-lg-none d-xl-block',
    r'visible-xs': 'd-block d-sm-none',
    r'visible-sm': 'd-none d-sm-block d-md-none',
    r'visible-md': 'd-none d-md-block d-lg-none',
    r'visible-lg': 'd-none d-lg-block d-xl-none',
    
    # Grid system - col-xs- → col-
    r'col-xs-(\d+)': r'col-\1',
    r'col-xs-offset-(\d+)': r'offset-\1',
    r'col-xs-pull-(\d+)': r'order-\1',
    r'col-xs-push-(\d+)': r'order-\1',
    
    # Pull/Push classes
    r'pull-right': 'float-end',
    r'pull-left': 'float-start',
    
    # Text alignment
    r'text-right': 'text-end',
    r'text-left': 'text-start',
    
    # Responsive utilities
    r'center-block': 'mx-auto',
    
    # Labels → Badges
    r'label label-default': 'badge bg-secondary',
    r'label label-primary': 'badge bg-primary',
    r'label label-success': 'badge bg-success',
    r'label label-info': 'badge bg-info',
    r'label label-warning': 'badge bg-warning',
    r'label label-danger': 'badge bg-danger',
    
    # Navbar
    r'navbar-default': 'navbar-light bg-light',
    r'navbar-inverse': 'navbar-dark bg-dark',
    
    # Forms
    r'form-group-lg': 'form-control-lg',
    r'form-group-sm': 'form-control-sm',
    r'help-block': 'form-text',
    r'control-label': 'form-label',
    
    # Wells
    r'well well-sm': 'card card-body p-2',
    r'well well-lg': 'card card-body p-4',
    r'well': 'card card-body',
    
    # Thumbnails
    r'thumbnail': 'card',
    
    # Media
    r'media-left': 'me-3',
    r'media-right': 'ms-3',
    r'media-body': 'flex-grow-1',
    
    # Spacing
    r'no-margin': 'm-0',
    r'no-padding': 'p-0',
}

def actualizar_archivo(filepath):
    """Actualiza un archivo con las nuevas clases de Bootstrap 5"""
    try:
        with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
            contenido = f.read()
        
        contenido_original = contenido
        cambios_realizados = []
        
        # Aplicar reemplazos
        for patron, reemplazo in BOOTSTRAP_REPLACEMENTS.items():
            if re.search(patron, contenido):
                nuevo_contenido = re.sub(patron, reemplazo, contenido)
                if nuevo_contenido != contenido:
                    cambios_realizados.append(f"{patron} → {reemplazo}")
                    contenido = nuevo_contenido
        
        # Solo guardar si hubo cambios
        if contenido != contenido_original:
            with open(filepath, 'w', encoding='utf-8') as f:
                f.write(contenido)
            return True, cambios_realizados
        
        return False, []
        
    except Exception as e:
        print(f"Error procesando {filepath}: {e}")
        return False, []

def main():
    """Función principal"""
    directorio_base = Path('/home/ubuntu/github_repos/Sistema-Geaturim.S.A')
    extensiones = ['*.php', '*.html', '*.js', '*.css']
    
    # Excluir directorios que no deben modificarse
    excluir = ['vendor', 'node_modules', '.git', 'PHPMailer']
    
    archivos_modificados = []
    total_archivos = 0
    
    print("Iniciando actualización de Bootstrap 3 a Bootstrap 5...")
    print("=" * 60)
    
    for extension in extensiones:
        for filepath in directorio_base.rglob(extension):
            # Verificar si el archivo debe excluirse
            if any(exc in str(filepath) for exc in excluir):
                continue
            
            total_archivos += 1
            modificado, cambios = actualizar_archivo(filepath)
            
            if modificado:
                archivos_modificados.append((str(filepath.relative_to(directorio_base)), cambios))
                print(f"✓ {filepath.relative_to(directorio_base)}")
    
    print("=" * 60)
    print(f"Archivos analizados: {total_archivos}")
    print(f"Archivos modificados: {len(archivos_modificados)}")
    print("=" * 60)
    
    # Guardar reporte
    with open(directorio_base / 'reporte_bootstrap.txt', 'w', encoding='utf-8') as f:
        f.write("REPORTE DE ACTUALIZACIÓN BOOTSTRAP 3 → BOOTSTRAP 5\n")
        f.write("=" * 60 + "\n\n")
        f.write(f"Archivos analizados: {total_archivos}\n")
        f.write(f"Archivos modificados: {len(archivos_modificados)}\n\n")
        f.write("Archivos modificados:\n")
        f.write("-" * 60 + "\n")
        for archivo, cambios in archivos_modificados:
            f.write(f"\n{archivo}\n")
            for cambio in cambios[:5]:  # Limitar a 5 cambios por archivo en el reporte
                f.write(f"  - {cambio}\n")
    
    print("Reporte guardado en reporte_bootstrap.txt")

if __name__ == '__main__':
    main()
