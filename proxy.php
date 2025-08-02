<?php
if (!isset($_GET['url'])) {
  http_response_code(400);
  exit('Missing URL');
}

$url = $_GET['url'];
$context = stream_context_create([
  'http' => ['header' => 'User-Agent: PHP']
]);

$fp = fopen($url, 'rb', false, $context);
if (!$fp) {
  http_response_code(500);
  exit('Could not open URL');
}

// Send headers from original source
$meta = stream_get_meta_data($fp);
$headers = $meta["wrapper_data"] ?? [];
foreach ($headers as $header) {
  if (stripos($header, "Content-Type:") === 0) {
    header($header);
  }
  if (stripos($header, "Content-Length:") === 0) {
    header($header);
  }
}

fpassthru($fp);
fclose($fp);
?>
