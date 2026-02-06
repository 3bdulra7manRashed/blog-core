<?php
/**
 * Arabic Encoding Recovery Script
 * 
 * Reverses double-encoded Arabic text in Blade files.
 * Corruption: UTF-8 → Windows-1256 → UTF-8 (double-encoded)
 * Fix: Reverse the erroneous interpretation
 */

// Configuration
$projectRoot = __DIR__;
$backupDir = $projectRoot . '/encoding_repair_backup';
$scanDirs = [
    $projectRoot . '/resources',
    $projectRoot . '/Modules',
];

// Exclusions
$excludeDirs = ['vendor', 'node_modules', 'storage'];

// Corruption detection patterns
$corruptionPatterns = [
    '/ط[§ظ]/u',  // Common double-encoded Arabic patterns
    '/ظ[„…†‡ˆ‰ٹ]/u',
];

// Sample Arabic words for validation
$expectedArabicWords = [
    'المديرين', 'الاسم', 'حذف', 'تعديل', 'إضافة', 'العودة',
    'القائمة', 'الوصف', 'الرابط', 'النشر', 'المحتوى'
];

$report = [
    'detected_files' => [],
    'repaired_files' => [],
    'skipped_files' => [],
    'failed_files' => [],
    'dry_run_sample' => null,
];

/**
 * Check if content contains corruption patterns
 */
function isCorrupted($content) {
    global $corruptionPatterns;
    foreach ($corruptionPatterns as $pattern) {
        if (preg_match($pattern, $content)) {
            return true;
        }
    }
    return false;
}

/**
 * Count readable Arabic words in content
 */
function countArabicWords($content) {
    global $expectedArabicWords;
    $count = 0;
    foreach ($expectedArabicWords as $word) {
        if (mb_strpos($content, $word) !== false) {
            $count++;
        }
    }
    return $count;
}

/**
 * Attempt to reverse the encoding corruption
 * 
 * The corruption was: UTF-8 text read as Windows-1256, then saved as UTF-8
 * To reverse: Read as UTF-8, convert to Windows-1256 bytes, interpret as UTF-8
 */
function reverseEncoding($content) {
    // Convert the corrupted UTF-8 back to what Windows-1256 bytes would produce
    // This is the reverse of: UTF-8 → misread as Windows-1256 → saved as UTF-8
    
    // Method: Convert UTF-8 string to Windows-1256, then interpret those bytes as UTF-8
    $converted = @mb_convert_encoding($content, 'Windows-1256', 'UTF-8');
    if ($converted === false) {
        return false;
    }
    
    // The result should now be proper UTF-8
    return $converted;
}

/**
 * Validate that the repair was successful
 */
function validateRepair($original, $repaired) {
    // Check that corruption is removed
    if (isCorrupted($repaired)) {
        return ['valid' => false, 'reason' => 'Corruption patterns still present'];
    }
    
    // Check for readable Arabic words
    $arabicWordCount = countArabicWords($repaired);
    if ($arabicWordCount < 3) {
        return ['valid' => false, 'reason' => "Only $arabicWordCount Arabic words detected (need ≥3)"];
    }
    
    // Check that we haven't introduced garbage (no increase in question marks or replacement chars)
    $originalGarbage = substr_count($original, '�') + substr_count($original, '?');
    $repairedGarbage = substr_count($repaired, '�') + substr_count($repaired, '?');
    if ($repairedGarbage > $originalGarbage) {
        return ['valid' => false, 'reason' => 'Increased garbage characters'];
    }
    
    return ['valid' => true, 'arabic_words' => $arabicWordCount];
}

/**
 * Recursively find all blade.php files
 */
function findBladeFiles($dir, $excludeDirs) {
    $files = [];
    $iterator = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
    $filter = new RecursiveCallbackFilterIterator($iterator, function ($current, $key, $iterator) use ($excludeDirs) {
        if ($current->isDir()) {
            return !in_array($current->getFilename(), $excludeDirs);
        }
        return true;
    });
    
    $iterator = new RecursiveIteratorIterator($filter);
    
    foreach ($iterator as $file) {
        if ($file->isFile() && preg_match('/\.blade\.php$/', $file->getFilename())) {
            $files[] = $file->getPathname();
        }
    }
    
    return $files;
}

// Step 1: Scan for corrupted files
echo "=== Arabic Encoding Recovery Tool ===\n\n";
echo "Step 1: Scanning for corrupted files...\n";

$allBladeFiles = [];
foreach ($scanDirs as $dir) {
    if (is_dir($dir)) {
        $allBladeFiles = array_merge($allBladeFiles, findBladeFiles($dir, $excludeDirs));
    }
}

$corruptedFiles = [];
foreach ($allBladeFiles as $file) {
    $content = file_get_contents($file);
    if (isCorrupted($content)) {
        $corruptedFiles[] = $file;
        $report['detected_files'][] = str_replace($projectRoot . '/', '', $file);
    }
}

echo "Found " . count($corruptedFiles) . " corrupted files out of " . count($allBladeFiles) . " total blade files.\n\n";

if (count($corruptedFiles) === 0) {
    echo "No corrupted files found. Exiting.\n";
    exit(0);
}

// Step 2: Dry run on first file
echo "Step 2: Performing dry run test...\n";

$testFile = $corruptedFiles[0];
$testContent = file_get_contents($testFile);
$repairedContent = reverseEncoding($testContent);

if ($repairedContent === false) {
    echo "ERROR: Encoding conversion failed on test file.\n";
    exit(1);
}

$validation = validateRepair($testContent, $repairedContent);

echo "Test file: " . str_replace($projectRoot . '/', '', $testFile) . "\n";
echo "Validation: " . ($validation['valid'] ? 'PASSED' : 'FAILED') . "\n";

if (!$validation['valid']) {
    echo "Reason: " . $validation['reason'] . "\n";
    echo "ABORTING: Dry run failed. Manual review required.\n";
    exit(1);
}

echo "Arabic words detected: " . $validation['arabic_words'] . "\n";

// Store sample for report
$sampleBefore = mb_substr($testContent, 0, 500);
$sampleAfter = mb_substr($repairedContent, 0, 500);
$report['dry_run_sample'] = [
    'file' => str_replace($projectRoot . '/', '', $testFile),
    'before' => $sampleBefore,
    'after' => $sampleAfter,
];

echo "\nDry run PASSED. Proceeding with bulk repair...\n\n";

// Step 3: Bulk repair
echo "Step 3: Repairing corrupted files...\n";

foreach ($corruptedFiles as $file) {
    $relativePath = str_replace($projectRoot . '/', '', $file);
    $content = file_get_contents($file);
    $repaired = reverseEncoding($content);
    
    if ($repaired === false) {
        echo "  SKIP: $relativePath (conversion failed)\n";
        $report['skipped_files'][] = ['file' => $relativePath, 'reason' => 'Conversion failed'];
        continue;
    }
    
    $validation = validateRepair($content, $repaired);
    
    if (!$validation['valid']) {
        echo "  SKIP: $relativePath ({$validation['reason']})\n";
        $report['skipped_files'][] = ['file' => $relativePath, 'reason' => $validation['reason']];
        continue;
    }
    
    // Write repaired content (ensuring no BOM and LF line endings)
    $repaired = str_replace("\r\n", "\n", $repaired);
    $repaired = ltrim($repaired, "\xEF\xBB\xBF");
    
    if (file_put_contents($file, $repaired) !== false) {
        echo "  REPAIRED: $relativePath (Arabic words: {$validation['arabic_words']})\n";
        $report['repaired_files'][] = $relativePath;
    } else {
        echo "  FAILED: $relativePath (write error)\n";
        $report['failed_files'][] = ['file' => $relativePath, 'reason' => 'Write error'];
    }
}

echo "\n";

// Step 4: Post-repair validation
echo "Step 4: Post-repair validation...\n";

$remainingCorrupted = 0;
foreach ($corruptedFiles as $file) {
    $content = file_get_contents($file);
    if (isCorrupted($content)) {
        $remainingCorrupted++;
    }
}

echo "Remaining corrupted files: $remainingCorrupted\n\n";

// Step 5: Generate report
echo "Step 5: Generating report...\n";

$timestamp = date('Y-m-d H:i:s');
$backupTimestamp = basename(glob($backupDir . '/*')[0] ?? 'unknown');

$reportContent = <<<MARKDOWN
# Arabic Encoding Repair Report

**Generated:** $timestamp

---

## Summary

| Metric | Count |
|--------|-------|
| **Total Files Scanned** | {count($allBladeFiles)} |
| **Total Files Detected** | {count($report['detected_files'])} |
| **Files Successfully Repaired** | {count($report['repaired_files'])} |
| **Files Skipped** | {count($report['skipped_files'])} |
| **Files Failed** | {count($report['failed_files'])} |
| **Remaining Corrupted** | $remainingCorrupted |

---

## Backup Location

`encoding_repair_backup/$backupTimestamp/`

---

## Validation Status

**{$status = (count($report['failed_files']) === 0 && $remainingCorrupted === 0) ? 'SUCCESS' : ($remainingCorrupted > 0 ? 'PARTIAL' : 'FAILED')}**


MARKDOWN;

// Add repaired files list
$reportContent .= "\n## Repaired Files\n\n";
if (count($report['repaired_files']) > 0) {
    foreach ($report['repaired_files'] as $f) {
        $reportContent .= "- `$f`\n";
    }
} else {
    $reportContent .= "None\n";
}

// Add skipped files list
$reportContent .= "\n## Skipped Files\n\n";
if (count($report['skipped_files']) > 0) {
    foreach ($report['skipped_files'] as $s) {
        $reportContent .= "- `{$s['file']}` — {$s['reason']}\n";
    }
} else {
    $reportContent .= "None\n";
}

// Add sample before/after
if ($report['dry_run_sample']) {
    $reportContent .= "\n---\n\n## Sample Before/After\n\n";
    $reportContent .= "**File:** `{$report['dry_run_sample']['file']}`\n\n";
    $reportContent .= "### Before (Corrupted)\n```\n{$report['dry_run_sample']['before']}\n```\n\n";
    $reportContent .= "### After (Repaired)\n```\n{$report['dry_run_sample']['after']}\n```\n";
}

$reportPath = $projectRoot . '/ENCODING_REPAIR_REPORT.md';
file_put_contents($reportPath, $reportContent);

echo "Report saved to: ENCODING_REPAIR_REPORT.md\n\n";
echo "=== Repair Complete ===\n";
