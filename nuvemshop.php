<?php
/**
 * nuvemshop-webhook.php
 * Loga eventos da Nuvemshop no STDOUT (Render)
 */

// Sempre responder rápido
http_response_code(200);

// Captura headers
$headers = function_exists('getallheaders')
    ? getallheaders()
    : [];

// Captura body
$rawBody = file_get_contents("php://input");

// Monta log estruturado
$log = [
    "date" => date("c"),
    "method" => $_SERVER["REQUEST_METHOD"] ?? null,
    "headers" => $headers,
    "body" => json_decode($rawBody, true),
];

// 🔥 LOGA NO STDOUT (Render mostra!)
error_log("NUVEMSHOP_WEBHOOK " . json_encode($log, JSON_UNESCAPED_UNICODE));

// Resposta
echo "OK";
