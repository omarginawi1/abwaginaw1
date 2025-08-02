<?php

$botToken = '6405710577:AAEd84CeLRczpEbwE8yKNiVSz1MORKudw0w';
$telegramUrl = $_GET['url'] ?? null;

if (!$telegramUrl || !preg_match('#t\.me/([^/]+)/(\d+)#', $telegramUrl, $matches)) {
    echo "❌ رابط غير صالح.";
    exit;
}

$channelUsername = $matches[1];
$messageId = $matches[2];

$forwardUrl = "https://api.telegram.org/bot$botToken/forwardMessage";
$payload = [
    'chat_id' => '999798180',
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

if (!$response || !isset($response['result'])) {
    echo "❌ فشل في استخراج تفاصيل الرسالة.";
    exit;
}

$media = $response['result'];
$fileId = null;

foreach (['video', 'document', 'animation', 'video_note'] as $type) {
    if (isset($media[$type]['file_id'])) {
        $fileId = $media[$type]['file_id'];
        echo "✅ file_id: " . $fileId;
        exit;
    }
}

echo "❌ ما قدرنا نلقى file_id.";
?>
