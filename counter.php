<?php
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

const COUNTER_FILE = 'count.txt';
const FILE_PERMISSIONS = 0644;

try {
    $fp = fopen(COUNTER_FILE, 'c+');
    if (!$fp || !flock($fp, LOCK_EX)) {
        throw new Exception('File access error');
    }

    $count = (int)fgets($fp) ?: 0;
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, ++$count);

    flock($fp, LOCK_UN);
    fclose($fp);
    chmod(COUNTER_FILE, FILE_PERMISSIONS);

    echo json_encode(['status' => 'success', 'count' => $count]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to update visitor count']);
    error_log("Error: " . $e->getMessage());
}
?>