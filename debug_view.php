<?php

$botToken = '6405710577:AAEd84CeLRczpEbwE8yKNiVSz1MORKudw0w';
$telegramUrl = $_GET['url'] ?? null;

function getFilePathDebug($fileId, $botToken) {
    $getFileUrl = "https://api.telegram.org/bot$botToken/getFile?file_id=$fileId";
    $fileInfo = json_decode(file_get_contents($getFileUrl), true);
    return $fileInfo;
}

if (!$telegramUrl || !preg_match('#t\.me/([^/]+)/(\d+)#', $telegramUrl, $matches)) {
    echo "❌ رابط غير صالح.";
    exit;
}

$channelUsername = $matches[1];
$messageId = $matches[2];
$chatId = '999798180';

function extractFileId($media) {
    foreach (['video', 'document', 'animation', 'video_note', 'voice'] as $type) {
        if (isset($media[$type]['file_id'])) {
            return $media[$type]['file_id'];
        }
    }
    return null;
}

// step 1: forward
$forwardUrl = "https://api.telegram.org/bot$botToken/forwardMessage";
$payload = [
    'chat_id' => $chatId,
    'from_chat_id' => "@$channelUsername",
    'message_id' => $messageId
];
$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($payload),
    ],
];

$context  = stream_context_create($options);
$result = file_get_contents($forwardUrl, false, $context);
$response = json_decode($result, true);

if (!isset($response['result'])) {
    echo "❌ فشل في forwardMessage:<br><pre>" . print_r($response, true) . "</pre>";
    exit;
}

$fileId = extractFileId($response['result']);

if (!$fileId) {
    echo "❌ file_id غير موجود:<br><pre>" . print_r($response, true) . "</pre>";
    exit;
}

$getFileResponse = getFilePathDebug($fileId, $botToken);

echo "<h3>✅ file_id</h3><code>$fileId</code>";
echo "<h3>📦 رد getFile</h3><pre>" . print_r($getFileResponse, true) . "</pre>";

?>
