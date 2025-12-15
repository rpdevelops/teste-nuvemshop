<?php
/**
 * nuvemshop-webhook.php
 * Recebe eventos da Nuvemshop
 */

// Captura headers
$headers = getallheaders();

// Captura body
$rawBody = file_get_contents("php://input");

$log = [
    "date" => date("c"),
    "method" => $_SERVER["REQUEST_METHOD"] ?? null,
    "headers" => $headers,
    "body_raw" => $rawBody,
    "body_json" => json_decode($rawBody, true),
];

// Salva log estruturado
file_put_contents(
    __DIR__ . "/logs-webhook.jsonl",
    json_encode($log, JSON_UNESCAPED_UNICODE) . PHP_EOL,
    FILE_APPEND
);

// Sempre responder rápido
http_response_code(200);
echo "OK";
