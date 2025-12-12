<?php
/**
 * nuvemshop-webhook.php
 * 
 * Recebe eventos enviados pela Nuvemshop.
 */

$input = file_get_contents("php://input");

if (!$input) {
    http_response_code(400);
    die("Nenhum dado recebido");
}

// Salva log (para debugar)
file_put_contents("logs-webhook.txt", date("c") . " - " . $input . "\n", FILE_APPEND);

// Sempre responda 200
http_response_code(200);
echo "OK";
