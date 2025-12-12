<?php
/**
 * nuvemshop-auth.php
 * 
 * Roda quando o lojista instala o app.
 * Troca o ?code pelo access_token da loja e cria webhooks automaticamente.
 */

$client_id = "24014";
$client_secret = "a8ab5b50e1f6443653c39eab1bcb35fa42937e2d2bd1a865";
$redirect_uri = "https://teste-nuvemshop.onrender.com/nuvemshop-auth.php"; // EXATAMENTE igual ao configurado no painel

if (!isset($_GET['code'])) {
    die("Erro: código de autorização não encontrado.");
}

$code = $_GET['code'];

// 1 — Troca o code pelo token
$tokenData = [
    "client_id" => $client_id,
    "client_secret" => $client_secret,
    "grant_type" => "authorization_code",
    "code" => $code,
    "redirect_uri" => $redirect_uri
];

$ch = curl_init("https://www.nuvemshop.com.br/apps/token");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $tokenData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if (!isset($data["access_token"])) {
    die("Erro ao obter o token da loja: " . $response);
}

$access_token = $data["access_token"];
$store_id = $data["user_id"];

// (Opcional) salvar em banco se quiser
file_put_contents("tokens.txt", "Loja: $store_id | Token: $access_token\n", FILE_APPEND);

// 2 — Criar webhooks

function criarWebhook($store_id, $access_token, $evento) {
    $payload = [
        "url" => "https://teste-nuvemshop.onrender.com/nuvemshop.php",
        "event" => $evento
    ];

    $ch = curl_init("https://api.nuvemshop.com.br/v1/$store_id/webhooks");
    curl_setopt_array($ch, [
        CURLOPT_HTTPHEADER => [
            "Authentication: Bearer $access_token",
            "User-Agent: ConvertaApp (converta.app)",
            "Content-Type: application/json"
        ],
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_RETURNTRANSFER => true
    ]);
    
    $resp = curl_exec($ch);
    curl_close($ch);

    return $resp;
}

// Crie os eventos que quiser ouvir
criarWebhook($store_id, $access_token, "order/created");
criarWebhook($store_id, $access_token, "order/updated");
criarWebhook($store_id, $access_token, "product/created");
criarWebhook($store_id, $access_token, "customer/created");

echo "App instalado com sucesso! Webhooks configurados.";
