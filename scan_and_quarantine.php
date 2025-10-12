<?php
/**
 * SCAN & QUARANTINE - BACKDOOR DETECTION
 * Save as /public_html/scan_and_quarantine.php
 * Run via browser: https://yourdomain.com/scan_and_quarantine.php
 *
 * Behavior:
 * - Scans recursively from current dir
 * - Detects suspicious patterns (eval, base64, gzinflate, etc.)
 * - Detects high-entropy files (likely obfuscated)
 * - Moves suspicious files to quarantine folder (preserve structure)
 * - Writes a report file and optionally sends email (disabled by default)
 *
 * IMPORTANT: Remove this file after use.
 */

ini_set('memory_limit', '512M');
set_time_limit(300);
date_default_timezone_set('Asia/Jakarta');

// ---------- CONFIG ----------
$base_dir = __DIR__;                // directory to scan (default public_html)
$quarantine_base = __DIR__ . '/.quarantine_' . date('Ymd_His'); // quarantine folder
$report_path = __DIR__ . '/quarantine_report_' . date('Ymd_His') . '.txt';
$enable_email_report = false;       // set true to try sending email via mail()
$email_to = 'info@saleshinoindonesia.com'; // change if enabling email
$max_file_size_scan = 5 * 1024 * 1024; // skip files larger than 5MB for content scan
// ---------------------------

// Patterns that commonly indicate PHP backdoors or obfuscation
$patterns = [
    'eval\s*\(',
    'base64_decode\s*\(',
    'gzinflate\s*\(',
    'gzuncompress\s*\(',
    'str_rot13\s*\(',
    'preg_replace\s*\(.*\/e',
    'create_function\s*\(',
    'assert\s*\(',
    'shell_exec\s*\(',
    'passthru\s*\(',
    'system\s*\(',
    'popen\s*\(',
    'proc_open\s*\(',
    'pcntl_exec\s*\(',
    'curl_exec\s*\(',
    'chmod\s*\(',
    'fopen\s*\(.*php',
    'file_put_contents\s*\(',
    'move_uploaded_file\s*\(',
    'eval\$_POST',
    '\$_REQUEST\[',
    'base64_encode\(',
    'stripslashes\(',
    'gzdecode\(',
    'unserialize\s*\('
];

// Additional heuristics
$min_entropy_for_flag = 6.8; // entropy threshold
$min_suspect_matches = 1;    // how many pattern matches to decide suspicious

$results = [];
$quarantine_created = false;

/**
 * Calculate Shannon entropy of a string
 */
function shannon_entropy($data) {
    if (empty($data)) return 0;
    $h = 0.0;
    $len = strlen($data);
    $counts = array_count_values(str_split($data));
    foreach ($counts as $c) {
        $p = $c / $len;
        $h -= $p * log($p, 2);
    }
    return $h;
}

/**
 * Ensure quarantine folder and .htaccess to block web access
 */
function ensure_quarantine($path) {
    global $quarantine_created;
    if ($quarantine_created) return true;
    if (!is_dir($path)) {
        if (!@mkdir($path, 0700, true)) {
            return false;
        }
    }
    // write .htaccess to block web access (best-effort)
    $ht = "Order allow,deny\nDeny from all\n";
    @file_put_contents($path . '/.htaccess', $ht);
    $quarantine_created = true;
    return true;
}

/**
 * Move file to quarantine preserving directory structure
 */
function quarantine_file($filePath, $baseDir, $quarantineBase) {
    $rel = ltrim(str_replace($baseDir, '', $filePath), '/\\');
    $destDir = dirname($quarantineBase . '/' . $rel);
    if (!is_dir($destDir)) {
        @mkdir($destDir, 0700, true);
    }
    $dest = $quarantineBase . '/' . $rel;
    // move file (rename). If fails, copy then unlink
    if (@rename($filePath, $dest)) {
        return $dest;
    } else {
        if (@copy($filePath, $dest)) {
            @unlink($filePath);
            return $dest;
        }
    }
    return false;
}

/**
 * Scan recursively
 */
function scan_dir($dir) {
    global $patterns, $results, $base_dir, $quarantine_base, $max_file_size_scan, $min_entropy_for_flag, $min_suspect_matches;
    $it = @scandir($dir);
    if ($it === false) return;
    foreach ($it as $entry) {
        if ($entry === '.' || $entry === '..') continue;
        $path = $dir . '/' . $entry;
        // skip the quarantine folder itself if present
        if (strpos($path, basename($quarantine_base)) !== false) continue;
        // skip this scanner file to avoid self-quarantine
        if (realpath($path) === realpath(__FILE__)) continue;

        if (is_dir($path)) {
            scan_dir($path);
        } elseif (is_file($path)) {
            $size = @filesize($path);
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $is_php_like = ($ext === 'php' || $ext === 'phtml' || $ext === 'php5' || $ext === 'php7' || stripos($entry, '.php') !== false);

            $content = '';
            $skipped_for_size = false;
            if ($size !== false && $size <= $max_file_size_scan) {
                $content = @file_get_contents($path);
            } else {
                $skipped_for_size = true;
            }

            $match_count = 0;
            if ($content !== false && $content !== '') {
                foreach ($patterns as $pat) {
                    // use case-insensitive regex search
                    if (@preg_match("/$pat/i", $content)) {
                        $match_count++;
                    }
                }
            }

            // entropy check (only if we have content)
            $entropy = ($content !== false && $content !== '') ? shannon_entropy($content) : 0.0;

            // Heuristics to flag suspicious:
            // - pattern matches
            // - php-like extension with suspicious patterns
            // - extremely high entropy (likely obfuscated)
            // - small files with suspicious functions
            $suspicious = false;
            $reason = [];

            if ($match_count >= $min_suspect_matches) {
                $suspicious = true;
                $reason[] = "pattern_matches={$match_count}";
            }
            if ($entropy >= $min_entropy_for_flag) {
                $suspicious = true;
                $reason[] = "high_entropy=" . round($entropy, 2);
            }
            // presence of php code in non-php extension (e.g., image with <?php)
            if ($content !== false && strpos($content, '<?php') !== false && !$is_php_like) {
                $suspicious = true;
                $reason[] = "php_tag_in_nonphp_file";
            }
            // tiny php files with eval/base64 etc
            if ($is_php_like && $size !== false && $size < 15000 && $match_count > 0) {
                $suspicious = true;
                $reason[] = "small_php_with_patterns";
            }

            if ($suspicious) {
                $results[] = [
                    'file' => $path,
                    'size' => $size,
                    'ext' => $ext,
                    'match_count' => $match_count,
                    'entropy' => round($entropy, 3),
                    'skipped_for_size' => $skipped_for_size,
                    'modified' => date('Y-m-d H:i:s', @filemtime($path)),
                    'reason' => implode(',', $reason)
                ];
            }
        }
    }
}

// Run scan
echo "<pre>";
echo "=== SCAN & QUARANTINE START ===\n";
echo "Scan dir: $base_dir\n";
scan_dir($base_dir);
echo "Found suspicious files: " . count($results) . "\n\n";

if (count($results) === 0) {
    echo "No suspicious files detected by heuristics.\n";
    echo "Done.\n</pre>";
    exit;
}

// Prepare quarantine
if (!ensure_quarantine($quarantine_base)) {
    echo "ERROR: Could not create quarantine folder at $quarantine_base\n";
    echo "Abort.\n</pre>";
    exit;
}

// Move suspicious files to quarantine
$report_lines = [];
$report_lines[] = "Quarantine Report - " . date('Y-m-d H:i:s');
$report_lines[] = "Scan dir: $base_dir";
$report_lines[] = "Quarantine dir: $quarantine_base";
$report_lines[] = "Total suspicious files: " . count($results);
$report_lines[] = str_repeat('=', 50);

foreach ($results as $r) {
    $orig = $r['file'];
    $qdest = quarantine_file($orig, $base_dir, $quarantine_base);
    if ($qdest !== false) {
        $report_lines[] = "[QUARANTINED] {$orig} -> {$qdest}";
        $report_lines[] = "  size={$r['size']} ext={$r['ext']} matches={$r['match_count']} entropy={$r['entropy']} mod={$r['modified']} reasons={$r['reason']}";
    } else {
        $report_lines[] = "[FAILED MOVE] {$orig} (could not quarantine)";
        $report_lines[] = "  size={$r['size']} ext={$r['ext']} matches={$r['match_count']} entropy={$r['entropy']} mod={$r['modified']} reasons={$r['reason']}";
    }
}

// Write report
$report_content = implode("\n", $report_lines) . "\n";
file_put_contents($report_path, $report_content);

// Attempt to send email if enabled
if ($enable_email_report) {
    $subject = 'Malware Quarantine Report - ' . $_SERVER['HTTP_HOST'];
    $headers = "From: noreply@" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "\r\n";
    @mail($email_to, $subject, $report_content, $headers);
}

// Output to browser
echo implode("\n", $report_lines);
echo "\n\nReport saved to: $report_path\n";
echo "Quarantine folder: $quarantine_base\n";
echo "\nIMPORTANT:\n - Inspect files in quarantine before permanently deleting.\n - After verification, restore legitimate files from clean backup.\n - Remove this scanner script from public_html after use.\n";
echo "\n=== SCAN & QUARANTINE END ===\n</pre>";
?>
