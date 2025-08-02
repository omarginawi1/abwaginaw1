<?php

$botToken = '6405710577:AAEd84CeLRczpEbwE8yKNiVSz1MORKudw0w';

$fileId = $_GET['file_id'] ?? '';
if (!$fileId) {
    echo json_encode(['status' => 'fail', 'error' => 'file_id مفقود']);
    exit;
}

$url = "https://api.telegram.org/bot$botToken/getFile?file_id=$fileId";
$response = json_decode(file_get_contents($url), true);

if (!$response || !isset($response['result']['file_path'])) {
    echo json_encode(['status' => 'fail', 'error' => 'فشل في استخراج file_path']);
    exit;
}

$path = $response['result']['file_path'];
$link = "https://api.telegram.org/file/bot$botToken/$path";
echo json_encode(['status' => 'success', 'link' => $link]);
?>
