#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import os, re, sys
from datetime import datetime

class C:
    G='\033[92m'; Y='\033[93m'; R='\033[91m'; B='\033[94m'; E='\033[0m'
def info(m): print(f"{C.B}➜ {m}{C.E}")
def ok(m):   print(f"{C.G}✓ {m}{C.E}")
def warn(m): print(f"{C.Y}⚠ {m}{C.E}")

EXTS = ('.php', '.html', '.htm')
EXCLUDE_DIRS = {'vendor','node_modules','.git'}

# Actualizaciones a aplicar (de → a)
REPLACEMENTS = [
    # 1) CDN antiguos (admin_repositorio, etc.)
    (re.compile(r'https?://code\.jquery\.com/jquery-1\.12\.4\.js', re.I),
     'https://code.jquery.com/jquery-3.7.1.min.js',
     'CDN jQuery 1.12.4 → 3.7.1'),
    (re.compile(r'https?://code\.jquery\.com/ui/1\.12\.1/jquery-ui\.js', re.I),
     'https://code.jquery.com/ui/1.13.3/jquery-ui.min.js',
     'CDN jQuery UI 1.12.1 → 1.13.3'),

    # 2) Locales antiguos en dist/js y js/
    (re.compile(r'(\.\./\.\./dist/js/)jquery-1\.7\.2(\.min)?\.js', re.I),
     r'\1jquery-3.7.1.min.js',
     'dist/js jquery 1.7.2 → 3.7.1'),
    (re.compile(r'(\.\./\.\./dist/js/)jquery-1\.10\.2(\.min)?\.js', re.I),
     r'\1jquery-3.7.1.min.js',
     'dist/js jquery 1.10.2 → 3.7.1'),
    (re.compile(r'(\.\./\.\./js/)jquery-1\.7\.2(\.min)?\.js', re.I),
     r'\1jquery-3.7.1.min.js',
     'js/ jquery 1.7.2 → 3.7.1'),

    # 3) Documentación/legacy (opcional, si quieres modernizar demos)
    (re.compile(r'(dompdf_antiguo/[\w/]*?)jquery-1\.4\.2\.js', re.I),
     r'\1jquery-3.7.1.min.js',
     'dompdf_antiguo jQuery 1.4.2 → 3.7.1'),

    # 4) jQuery UI antiguo local (si decides subir una versión más nueva local)
    # Nota: Solo cambiar si tienes el archivo nuevo local. Si no, mejor dejarlo o usar CDN.
    (re.compile(r'(\.\./\.\./dist/js/)jquery-ui-1\.10\.4\.custom\.min\.js', re.I),
     r'\1jquery-ui-1.13.3.min.js',
     'dist/js jQuery UI 1.10.4 → 1.13.3 (requiere archivo)'),
    (re.compile(r'(\.\./\.\./dist/css/)jquery-ui-1\.10\.4\.custom\.css', re.I),
     r'\1jquery-ui-1.13.3.min.css',
     'dist/css jQuery UI 1.10.4 CSS → 1.13.3 (requiere archivo)'),
    (re.compile(r'http://code\.jquery\.com/ui/1\.11\.2/jquery-ui\.min\.js', re.I),
     'https://code.jquery.com/ui/1.13.3/jquery-ui.min.js',
     'CDN http jQuery UI 1.11.2 → https 1.13.3'),
]

DRY_RUN = '--dry-run' in [a.lower() for a in sys.argv[1:]]
BACKUP = '--backup' in [a.lower() for a in sys.argv[1:]]
LOG = f"jquery_adjust_{datetime.now().strftime('%Y%m%d_%H%M%S')}.log"

def process_file(fp):
    try:
        with open(fp, 'r', encoding='utf-8', errors='ignore') as f:
            content = f.read()
    except Exception as e:
        warn(f"No se pudo leer {fp}: {e}")
        return 0, []

    original = content
    changes = []
    total = 0
    for rgx, repl, desc in REPLACEMENTS:
        matches = list(rgx.finditer(content))
        if matches:
            total += len(matches)
            content = rgx.sub(repl, content)
            changes.append(f"{desc} ({len(matches)})")

    if total and not DRY_RUN and content != original:
        try:
            if BACKUP:
                with open(fp + '.bak', 'w', encoding='utf-8') as b:
                    b.write(original)
            with open(fp, 'w', encoding='utf-8', errors='ignore') as f:
                f.write(content)
        except Exception as e:
            warn(f"Error escribiendo {fp}: {e}")
            return 0, []
    return total, changes

def main():
    if DRY_RUN: warn("Modo DRY-RUN: no se escribirán cambios.")
    if BACKUP:  warn("Se crearán backups .bak de archivos modificados.")

    total_files = 0
    total_hits = 0
    updated = []

    with open(LOG, 'w', encoding='utf-8') as log:
        for root, dirs, files in os.walk('.'):
            dirs[:] = [d for d in dirs if d not in EXCLUDE_DIRS and not d.startswith('backup_')]
            for fn in files:
                if not fn.lower().endswith(EXTS):
                    continue
                fp = os.path.join(root, fn)
                total_files += 1
                hits, changes = process_file(fp)
                if hits:
                    total_hits += hits
                    updated.append((fp, changes))
                    log.write(f"{fp}\n")
                    for c in changes:
                        log.write(f"  - {c}\n")

    ok(f"Escaneados: {total_files} archivos")
    if total_hits == 0:
        warn("No se encontraron coincidencias a actualizar con las reglas actuales.")
    else:
        ok(f"Reemplazos aplicados (coincidencias): {total_hits}")
        print(f"Archivos tocados: {len(updated)} (ver log: {LOG})")

    print("\nUso recomendado:")
    print("  py actualizar_referencias_jquery.py --dry-run")
    print("  py actualizar_referencias_jquery.py --backup")
    print("  git status  (verifica cambios)")

if __name__ == '__main__':
    main()