#!/usr/bin/env python3
"""
Script para actualizar código jQuery obsoleto a jQuery 3.7.x
Sistema Geaturim S.A.
"""

import os
import re
from pathlib import Path

def actualizar_archivo(filepath):
    """Actualiza un archivo con las nuevas prácticas de jQuery"""
    try:
        with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
            contenido = f.read()
        
        contenido_original = contenido
        cambios_realizados = []
        
        # 1. Reemplazar .bind() → .on()
        # Patrón: .bind('evento', función)
        patron_bind = r'\.bind\(\s*[\'"]([^\'"]+)[\'"]\s*,'
        if re.search(patron_bind, contenido):
            nuevo = re.sub(patron_bind, r'.on("\1",', contenido)
            if nuevo != contenido:
                cambios_realizados.append(".bind() → .on()")
                contenido = nuevo
        
        # 2. Reemplazar .unbind() → .off()
        # Sin argumentos: .unbind() → .off()
        patron_unbind_simple = r'\.unbind\(\s*\)'
        if re.search(patron_unbind_simple, contenido):
            nuevo = re.sub(patron_unbind_simple, '.off()', contenido)
            if nuevo != contenido:
                cambios_realizados.append(".unbind() → .off()")
                contenido = nuevo
        
        # Con evento: .unbind('evento') → .off('evento')
        patron_unbind = r'\.unbind\(\s*[\'"]([^\'"]+)[\'"]\s*\)'
        if re.search(patron_unbind, contenido):
            nuevo = re.sub(patron_unbind, r'.off("\1")', contenido)
            if nuevo != contenido:
                cambios_realizados.append(".unbind('evento') → .off('evento')")
                contenido = nuevo
        
        # Con evento y función: .unbind('evento', fn) → .off('evento', fn)
        patron_unbind_fn = r'\.unbind\(\s*[\'"]([^\'"]+)[\'"]\s*,\s*([^\)]+)\)'
        if re.search(patron_unbind_fn, contenido):
            nuevo = re.sub(patron_unbind_fn, r'.off("\1", \2)', contenido)
            if nuevo != contenido:
                cambios_realizados.append(".unbind('evento', fn) → .off('evento', fn)")
                contenido = nuevo
        
        # 3. Reemplazar .live() → .on() (delegado al document)
        # Nota: .live() requiere delegación, así que se convierte a $(document).on()
        patron_live = r'\$\(([^\)]+)\)\.live\(\s*[\'"]([^\'"]+)[\'"]\s*,\s*'
        if re.search(patron_live, contenido):
            # Este es más complejo, requiere análisis manual en muchos casos
            # Por ahora solo documentamos
            cambios_realizados.append(".live() encontrado (requiere revisión manual)")
        
        # 4. Reemplazar .delegate() → .on()
        patron_delegate = r'\.delegate\(\s*[\'"]([^\'"]+)[\'"]\s*,\s*[\'"]([^\'"]+)[\'"]\s*,'
        if re.search(patron_delegate, contenido):
            nuevo = re.sub(patron_delegate, r'.on("\2", "\1",', contenido)
            if nuevo != contenido:
                cambios_realizados.append(".delegate() → .on()")
                contenido = nuevo
        
        # 5. Reemplazar .size() → .length
        patron_size = r'\.size\(\)'
        if re.search(patron_size, contenido):
            nuevo = re.sub(patron_size, '.length', contenido)
            if nuevo != contenido:
                cambios_realizados.append(".size() → .length")
                contenido = nuevo
        
        # 6. Reemplazar $.browser (deprecado)
        if '$.browser' in contenido or 'jQuery.browser' in contenido:
            cambios_realizados.append("$.browser encontrado (deprecado, requiere revisión)")
        
        # 7. Reemplazar .error() → .on('error', ...)
        patron_error = r'\.error\(\s*function'
        if re.search(patron_error, contenido):
            nuevo = re.sub(patron_error, r'.on("error", function', contenido)
            if nuevo != contenido:
                cambios_realizados.append(".error() → .on('error', ...)")
                contenido = nuevo
        
        # 8. Reemplazar .load() de eventos → .on('load', ...)
        # Solo si es un evento, no el método load de AJAX
        patron_load = r'\.load\(\s*function'
        if re.search(patron_load, contenido):
            nuevo = re.sub(patron_load, r'.on("load", function', contenido)
            if nuevo != contenido:
                cambios_realizados.append(".load(function) → .on('load', function)")
                contenido = nuevo
        
        # 9. Reemplazar .unload() → .on('unload', ...)
        patron_unload = r'\.unload\(\s*function'
        if re.search(patron_unload, contenido):
            nuevo = re.sub(patron_unload, r'.on("unload", function', contenido)
            if nuevo != contenido:
                cambios_realizados.append(".unload() → .on('unload', ...)")
                contenido = nuevo
        
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
    
    # Buscar archivos JavaScript y archivos PHP con JavaScript inline
    extensiones = ['*.js', '*.php', '*.html']
    
    # Excluir directorios que no deben modificarse
    excluir = ['vendor', 'node_modules', '.git', 'PHPMailer', 'dompdf', 'phpexcel', 
               'phpseclib', 'escpos-php', 'plugins/jQuery']
    
    archivos_modificados = []
    total_archivos = 0
    
    print("Iniciando actualización de jQuery obsoleto a jQuery 3.7.x...")
    print("=" * 70)
    
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
                for cambio in cambios[:3]:  # Mostrar primeros 3 cambios
                    print(f"  - {cambio}")
    
    print("=" * 70)
    print(f"Archivos analizados: {total_archivos}")
    print(f"Archivos modificados: {len(archivos_modificados)}")
    print("=" * 70)
    
    # Guardar reporte
    with open(directorio_base / 'reporte_jquery.txt', 'w', encoding='utf-8') as f:
        f.write("REPORTE DE ACTUALIZACIÓN JQUERY A 3.7.x\n")
        f.write("=" * 70 + "\n\n")
        f.write(f"Archivos analizados: {total_archivos}\n")
        f.write(f"Archivos modificados: {len(archivos_modificados)}\n\n")
        f.write("Archivos modificados:\n")
        f.write("-" * 70 + "\n")
        for archivo, cambios in archivos_modificados:
            f.write(f"\n{archivo}\n")
            for cambio in cambios:
                f.write(f"  - {cambio}\n")
    
    print("Reporte guardado en reporte_jquery.txt")
    
    return len(archivos_modificados)

if __name__ == '__main__':
    total_modificados = main()
    print(f"\n✓ Proceso completado. Se modificaron {total_modificados} archivos.")
