<?php

$botToken = '6405710577:AAEd84CeLRczpEbwE8yKNiVSz1MORKudw0w';
$chatId = '999798180';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['video'])) {
    $filePath = $_FILES['video']['tmp_name'];

    $url = "https://api.telegram.org/bot$botToken/sendVideo";

    $post_fields = [
        'chat_id'   => $chatId,
        'video'     => new CURLFile(realpath($filePath)),
        'caption'   => 'تم رفع الفيديو بواسطة المكنة 👑'
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $post_fields
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    if (isset($result['result']['video']['file_id'])) {
        $fileId = $result['result']['video']['file_id'];
        $getFileUrl = "https://api.telegram.org/bot$botToken/getFile?file_id=" . $fileId;
        $fileInfo = json_decode(file_get_contents($getFileUrl), true);

        if (isset($fileInfo['result']['file_path'])) {
            $filePath = $fileInfo['result']['file_path'];
            $directLink = "https://api.telegram.org/file/bot$botToken/$filePath";
            echo json_encode(['status' => 'success', 'link' => $directLink]);
            exit;
        } else {
            echo json_encode(['status' => 'fail', 'error' => 'تم رفع الفيديو لكن لم يتم استخراج الرابط']);
            exit;
        }
    } else {
        echo json_encode(['status' => 'fail', 'error' => 'فشل في رفع الفيديو']);
        exit;
    }
}
?>
