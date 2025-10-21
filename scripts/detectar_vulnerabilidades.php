<?php

/**
 * Script de Detección de Vulnerabilidades
 * Sistema Geaturim S.A.
 * 
 * Este script escanea el código PHP en busca de patrones vulnerables
 * y genera un reporte detallado.
 * 
 * Uso: php scripts/detectar_vulnerabilidades.php
 * 
 * @version 1.0
 * @date 2025-10-21
 */

// Configuración
$directorio = __DIR__ . '/..';
$extensiones = ['php'];
$excluirDirectorios = ['vendor', 'node_modules', '.git', 'scripts'];

// Contadores
$stats = [
    'archivos_escaneados' => 0,
    'archivos_vulnerables' => 0,
    'vulnerabilidades_totales' => 0,
    'sql_injection' => 0,
    'xss' => 0,
    'csrf' => 0,
    'session_insecure' => 0,
    'credenciales_hardcodeadas' => 0
];

// Patrones de vulnerabilidades
$patrones = [
    'sql_injection' => [
        'descripcion' => 'SQL Injection',
        'severidad' => 'CRÍTICA',
        'patrones' => [
            '/pg_query\s*\([^)]*\$_(GET|POST|REQUEST|COOKIE)\[/',
            '/pg_query\s*\([^)]*"\s*\.\s*\$_(GET|POST|REQUEST|COOKIE)/',
            '/pg_query_params\s*\([^)]*\$_(GET|POST|REQUEST|COOKIE)\[/',
            '/SELECT\s+.*\s+FROM\s+.*\$_(GET|POST|REQUEST|COOKIE)/',
            '/INSERT\s+INTO\s+.*\$_(GET|POST|REQUEST|COOKIE)/',
            '/UPDATE\s+.*\s+SET\s+.*\$_(GET|POST|REQUEST|COOKIE)/',
            '/DELETE\s+FROM\s+.*\$_(GET|POST|REQUEST|COOKIE)/',
        ]
    ],
    'xss' => [
        'descripcion' => 'Cross-Site Scripting (XSS)',
        'severidad' => 'ALTA',
        'patrones' => [
            '/echo\s+\$_(GET|POST|REQUEST|COOKIE)\[/',
            '/print\s+\$_(GET|POST|REQUEST|COOKIE)\[/',
            '/<\?=\s*\$_(GET|POST|REQUEST|COOKIE)\[/',
        ]
    ],
    'csrf' => [
        'descripcion' => 'Falta de protección CSRF',
        'severidad' => 'MEDIA',
        'patrones' => [
            '/<form[^>]*method=["\']post["\'][^>]*>(?![\s\S]*csrf_token)/',
        ]
    ],
    'session_insecure' => [
        'descripcion' => 'Sesiones inseguras',
        'severidad' => 'MEDIA',
        'patrones' => [
            '/session_start\s*\(\s*\)/',
            '/\$_SESSION\[["\']user["\']]\s*=/',
        ]
    ],
    'credenciales_hardcodeadas' => [
        'descripcion' => 'Credenciales hardcodeadas',
        'severidad' => 'CRÍTICA',
        'patrones' => [
            '/password\s*=\s*["\'][^"\']{8,}["\']/',
            '/dbname\s*=\s*["\'][^"\']+["\'].*user\s*=/',
        ]
    ]
];

// Función para escanear archivos recursivamente
function escanearDirectorio($dir, $excluir = []) {
    $archivos = [];
    
    if (!is_dir($dir)) {
        return $archivos;
    }
    
    $items = scandir($dir);
    
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        
        $path = $dir . '/' . $item;
        $basename = basename($path);
        
        // Verificar si está en la lista de exclusión
        $excluido = false;
        foreach ($excluir as $excl) {
            if (strpos($path, '/' . $excl . '/') !== false || $basename === $excl) {
                $excluido = true;
                break;
            }
        }
        
        if ($excluido) {
            continue;
        }
        
        if (is_dir($path)) {
            $archivos = array_merge($archivos, escanearDirectorio($path, $excluir));
        } elseif (is_file($path) && pathinfo($path, PATHINFO_EXTENSION) === 'php') {
            $archivos[] = $path;
        }
    }
    
    return $archivos;
}

// Función para detectar vulnerabilidades en un archivo
function detectarVulnerabilidades($archivo, $patrones) {
    $vulnerabilidades = [];
    $contenido = file_get_contents($archivo);
    $lineas = explode("\n", $contenido);
    
    foreach ($patrones as $tipo => $config) {
        foreach ($config['patrones'] as $patron) {
            preg_match_all($patron, $contenido, $matches, PREG_OFFSET_CAPTURE);
            
            foreach ($matches[0] as $match) {
                $offset = $match[1];
                $numeroLinea = substr_count(substr($contenido, 0, $offset), "\n") + 1;
                
                $vulnerabilidades[] = [
                    'tipo' => $tipo,
                    'descripcion' => $config['descripcion'],
                    'severidad' => $config['severidad'],
                    'linea' => $numeroLinea,
                    'codigo' => trim($lineas[$numeroLinea - 1] ?? ''),
                    'patron' => $patron
                ];
            }
        }
    }
    
    return $vulnerabilidades;
}

// Main
echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║  DETECTOR DE VULNERABILIDADES - Sistema Geaturim S.A.    ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n\n";

echo "📁 Escaneando directorio: $directorio\n";
echo "🔍 Buscando archivos PHP...\n\n";

$archivos = escanearDirectorio($directorio, $excluirDirectorios);
$totalArchivos = count($archivos);

echo "✅ Encontrados $totalArchivos archivos PHP\n";
echo "🔬 Iniciando análisis de vulnerabilidades...\n\n";

$reporteDetallado = [];
$barra = 0;

foreach ($archivos as $i => $archivo) {
    $stats['archivos_escaneados']++;
    
    // Barra de progreso simple
    $porcentaje = (int)(($i + 1) / $totalArchivos * 100);
    if ($porcentaje !== $barra && $porcentaje % 10 === 0) {
        echo "   Progreso: $porcentaje%\n";
        $barra = $porcentaje;
    }
    
    $vulnerabilidades = detectarVulnerabilidades($archivo, $patrones);
    
    if (!empty($vulnerabilidades)) {
        $stats['archivos_vulnerables']++;
        $stats['vulnerabilidades_totales'] += count($vulnerabilidades);
        
        $reporteDetallado[$archivo] = $vulnerabilidades;
        
        foreach ($vulnerabilidades as $vuln) {
            $stats[$vuln['tipo']]++;
        }
    }
}

echo "\n";
echo "═══════════════════════════════════════════════════════════\n";
echo "                    RESUMEN DEL ANÁLISIS                   \n";
echo "═══════════════════════════════════════════════════════════\n\n";

echo "📊 Estadísticas Generales:\n";
echo "   • Archivos escaneados: {$stats['archivos_escaneados']}\n";
echo "   • Archivos vulnerables: {$stats['archivos_vulnerables']}\n";
echo "   • Vulnerabilidades totales: {$stats['vulnerabilidades_totales']}\n\n";

echo "🔴 Vulnerabilidades por Tipo:\n";
echo "   • SQL Injection: {$stats['sql_injection']} (CRÍTICA)\n";
echo "   • Credenciales hardcodeadas: {$stats['credenciales_hardcodeadas']} (CRÍTICA)\n";
echo "   • XSS: {$stats['xss']} (ALTA)\n";
echo "   • CSRF: {$stats['csrf']} (MEDIA)\n";
echo "   • Sesiones inseguras: {$stats['session_insecure']} (MEDIA)\n\n";

// Generar reporte detallado en archivo
$reporteArchivo = __DIR__ . '/../REPORTE_VULNERABILIDADES.txt';
$fp = fopen($reporteArchivo, 'w');

fwrite($fp, "═══════════════════════════════════════════════════════════\n");
fwrite($fp, " REPORTE DETALLADO DE VULNERABILIDADES\n");
fwrite($fp, " Sistema Geaturim S.A.\n");
fwrite($fp, " Fecha: " . date('Y-m-d H:i:s') . "\n");
fwrite($fp, "═══════════════════════════════════════════════════════════\n\n");

fwrite($fp, "RESUMEN:\n");
fwrite($fp, "• Archivos escaneados: {$stats['archivos_escaneados']}\n");
fwrite($fp, "• Archivos vulnerables: {$stats['archivos_vulnerables']}\n");
fwrite($fp, "• Vulnerabilidades totales: {$stats['vulnerabilidades_totales']}\n\n");

fwrite($fp, "VULNERABILIDADES POR TIPO:\n");
fwrite($fp, "• SQL Injection: {$stats['sql_injection']}\n");
fwrite($fp, "• XSS: {$stats['xss']}\n");
fwrite($fp, "• CSRF: {$stats['csrf']}\n");
fwrite($fp, "• Sesiones inseguras: {$stats['session_insecure']}\n");
fwrite($fp, "• Credenciales hardcodeadas: {$stats['credenciales_hardcodeadas']}\n\n");

fwrite($fp, "═══════════════════════════════════════════════════════════\n");
fwrite($fp, "DETALLE DE ARCHIVOS VULNERABLES\n");
fwrite($fp, "═══════════════════════════════════════════════════════════\n\n");

// Top 20 archivos más vulnerables
$archivosPorVuln = [];
foreach ($reporteDetallado as $archivo => $vulns) {
    $archivosPorVuln[$archivo] = count($vulns);
}
arsort($archivosPorVuln);
$top20 = array_slice($archivosPorVuln, 0, 20, true);

echo "🎯 Top 20 Archivos Más Vulnerables:\n";
fwrite($fp, "TOP 20 ARCHIVOS MÁS VULNERABLES:\n\n");

$rank = 1;
foreach ($top20 as $archivo => $count) {
    $archivoCorto = str_replace($directorio . '/', '', $archivo);
    echo "   $rank. $archivoCorto ($count vulnerabilidades)\n";
    fwrite($fp, "$rank. $archivoCorto\n");
    fwrite($fp, "   Vulnerabilidades: $count\n");
    
    // Listar vulnerabilidades del archivo
    foreach ($reporteDetallado[$archivo] as $vuln) {
        fwrite($fp, "   • Línea {$vuln['linea']}: {$vuln['descripcion']} ({$vuln['severidad']})\n");
        fwrite($fp, "     Código: {$vuln['codigo']}\n");
    }
    fwrite($fp, "\n");
    
    $rank++;
}

fclose($fp);

echo "\n";
echo "═══════════════════════════════════════════════════════════\n\n";
echo "📄 Reporte detallado guardado en: REPORTE_VULNERABILIDADES.txt\n\n";

// Recomendaciones
echo "💡 RECOMENDACIONES:\n\n";

if ($stats['sql_injection'] > 0) {
    echo "   🔴 SQL Injection ({$stats['sql_injection']} casos)\n";
    echo "      → Usar querySecure(), querySingle(), queryAll()\n";
    echo "      → Usar getSecure(), postSecure() para inputs\n";
    echo "      → Consultar SEGURIDAD_README.md sección \"Guía de Migración\"\n\n";
}

if ($stats['credenciales_hardcodeadas'] > 0) {
    echo "   🔴 Credenciales Hardcodeadas ({$stats['credenciales_hardcodeadas']} casos)\n";
    echo "      → Mover credenciales a archivo .env\n";
    echo "      → Usar env() para acceder a configuración\n\n";
}

if ($stats['xss'] > 0) {
    echo "   🟠 XSS ({$stats['xss']} casos)\n";
    echo "      → Usar sanitizeHtml() antes de echo\n";
    echo "      → Usar sanitizeAttribute() en atributos HTML\n\n";
}

if ($stats['csrf'] > 0) {
    echo "   🟡 CSRF ({$stats['csrf']} casos)\n";
    echo "      → Agregar csrfField() en formularios POST\n";
    echo "      → Validar con validateCsrfToken() al procesar\n\n";
}

if ($stats['session_insecure'] > 0) {
    echo "   🟡 Sesiones Inseguras ({$stats['session_insecure']} casos)\n";
    echo "      → Reemplazar session_start() por SessionManager::init()\n";
    echo "      → Usar SessionManager para login/logout\n\n";
}

echo "📚 Para más información, consulta: SEGURIDAD_README.md\n\n";

echo "═══════════════════════════════════════════════════════════\n";
echo "  Análisis completado\n";
echo "═══════════════════════════════════════════════════════════\n";
