<?php
$path = 'C:/Users/Administrator/.cursor/projects/c-laragon-www-codecanyon-MNzPySlM-olance-global-freelancing-marketplace-Files-core/agent-transcripts/3dc3a7cb-a0d7-492d-9258-dffb3ada8c8a/3dc3a7cb-a0d7-492d-9258-dffb3ada8c8a.jsonl';
foreach (file($path) as $line) {
    if (!str_contains($line, 'Module 13')) {
        continue;
    }
    $d = json_decode($line, true);
    $t = $d['message']['content'][0]['text'] ?? '';
    preg_match_all('/#### \*\*Module (\d+).*?(?=#### \*\*Module |\Z)/s', $t, $matches, PREG_SET_ORDER);
    foreach ($matches as $m) {
        if ((int) $m[1] >= 13) {
            echo "=== Module {$m[1]} ===\n";
            echo substr($m[0], 0, 1200) . "\n\n";
        }
    }
    break;
}
