
<?php
header('Content-Type: application/json');

if (!isset($_GET['video_id']) || !isset($_GET['quality'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
    exit;
}

$video_id = preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['video_id']);
$quality = preg_replace('/[^0-9p]/', '', $_GET['quality']);

$command = escapeshellcmd("yt-dlp -f 'bestvideo[height<=${quality}]+bestaudio/best[height<=${quality}]' -g https://www.youtube.com/watch?v={$video_id}");

exec($command, $output, $return_var);

if ($return_var === 0 && count($output) > 0) {
    echo json_encode([
        'status' => 'success',
        'download_url' => $output[0]
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to fetch download link'
    ]);
}
?>
